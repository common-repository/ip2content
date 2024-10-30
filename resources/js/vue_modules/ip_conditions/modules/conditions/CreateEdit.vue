<script>
import ShortcodeCopy from "../components/ShortcodeCopy";
import OperatorsEnum from "./config/OperatorsEnum";
import ToggleSwitch from "../components/ToggleSwitch";

export default {
    name: "CreateEdit",

    components: {ShortcodeCopy, ToggleSwitch},

    props: {
        id: {
            type: Number,
            default: 0,
        },
        show: {
            type: Boolean,
            default: false,
        }
    },

    data: () => ({
            editAction: false,

            readyToMount: false,
            loading: false,
            copyShortcode: false,

            editItem: {
                id: null,
                label: '',
                active: false,
                field_id: null,
                default_content_id: null,
                conditions: []
            },
            errorMessages: {},
            contents: [],
            contentsSelect: [],
            fieldsSelect: [],
            operatorSelect: [],
            countriesSelect: [],
            valuesSelect: [],
            utmSelect: [],
            selectedField: {},
            defaultUtm: 'utm_source',
            specialFieldsIds: {
                country_code: null,
                zip_code: null
            },
            selectedFieldName: '',
        }
    ),

    watch: {
        async show(value) {
            if (!value) {
                return;
            }

            await this.loadData();
        }
    },

    async beforeMount() {
        await this.loadData();

        this.readyToMount = true;
    },

    computed: {
        indexedOperatorsByAlias() {
            const indexedData = {};
            this.operatorSelect.forEach(operator => indexedData[operator.alias] = operator);

            return indexedData;
        },
    },

    methods: {
        //  Init Data
        initEntityEditItem: () => JSON.parse(JSON.stringify({
            id: null,
            label: "",
            active: false,
            field_id: null,
            default_content_id: null,
            conditions: [],
        })),
        initEntityConditionVariation: () => JSON.parse(JSON.stringify({
            value: "",
            operator_id: null,
            dynamic_content_id: null,
            zip_country: "",
            utm_tag: "",

            allowCustomValue: false,
            allowCustomUTM: false,
            selectedUTM: {},
            selectedValues: [],
            selectSearch: "",
            selectedValuesCount: 0,
        })),

        async loadData() {
            await this.loadConditionContents();
            await this.loadFields();

            if (this.id) {
                this.editAction = true;
                await this.loadEditData();
            }
        },
        async loadEditData() {
            this.editItem = await this.$API.conditions().edit(this.id);
            this.prepareEditData();

            const fieldSelect = this.fieldsSelect.find(field => Number(this.editItem.field_id) === Number(field.value));
            this.selectedFieldName = fieldSelect.text;
            await this.handleFieldChanged(fieldSelect, false);
            this.setPreviousSelects();
        },
        prepareEditData() {
            this.editItem.conditions ||= [];
            if (!this.editItem.conditions.length) {
                this.editItem.conditions.push(this.initEntityConditionVariation());
            }
        },
        async loadConditionContents() {
            try {
                this.contents = await this.$API.conditionsContent().get({
                    _fields: "id,title,link",
                    wmip2c_per_page: 999999
                });
                this.contentsSelect = this.contents.map(content => ({
                    value: content.id,
                    text: content.title.rendered
                }));
            } catch (e) {
                console.log(e);
            }
        },
        async loadFields() {
            try {
                const untranslatedFieldsList = await this.$API.fields().getList();

                this.fieldsSelect = untranslatedFieldsList.map(field => {
                    if (field.text === 'country_code') {
                        this.specialFieldsIds.country_code = field.value;
                    }

                    if (field.text === 'zip') {
                        this.specialFieldsIds.zip_code = field.value;
                    }

                    if (field.text === 'utm_tag') {
                        this.specialFieldsIds.utm = field.value;
                    }

                    return {
                        value: field.value,
                        text: this.$t(`conditions_fields.${field.text}`),
                        default_operator_id: field.default_operator_id,
                        disabled: field.disabled
                    };
                });
            } catch (e) {
                console.log(e);
            }
        },
        async loadCountries() {
            try {
                if (this.countriesSelect.length) {
                    return;
                }

                const countryFieldId = this.specialFieldsIds.country_code;
                this.countriesSelect = await this.$API.fields().getValues(countryFieldId);
            } catch (e) {
                console.log(e);
            }
        },
        async handleFieldChanged(field, resetEditItem = true) {
            this.selectedField = field;
            if (!this.editItem.conditions.length) {
                this.editItem.conditions.push(this.initEntityConditionVariation());
            }

            if (resetEditItem) {
                this.editItem.conditions.forEach(condition => {
                    condition.operator_id = field.default_operator_id;
                    condition.value = "";
                    condition.zip_country = "";
                    condition.utm_tag = "";
                    condition.selectedUTM = {};
                    condition.selectedValues = [];
                    condition.selectSearch = "";
                    condition.selectedValuesCount = 0;
                });
            }

            this.editItem.field_id = field.value;
            this.selectedFieldName = field.text;
            this.valuesSelect = [];
            this.operatorSelect = [];

            if (field.value === this.specialFieldsIds.country_code) {
                await this.loadCountries();
                this.valuesSelect = this.countriesSelect;
            }

            if (field.value === this.specialFieldsIds.zip_code) {
                await this.loadCountries();
            }

            await this.loadValues();
            await this.loadOperators();
        },
        async loadValues() {
            try {
                if (!this.editItem.field_id) {
                    return;
                }

                if (this.editItem.field_id === this.specialFieldsIds.utm) {
                    await this.loadUtmData();
                    this.setAllowCustom();
                    return;
                }

                this.valuesSelect = await this.$API.fields().getValues(this.editItem.field_id);
                this.setAllowCustom();
            } catch (e) {
                console.log(e);
            }
        },
        async loadOperators() {
            try {
                if (!this.editItem.field_id) {
                    return;
                }

                this.operatorSelect = await this.$API.fields().getOperators(this.editItem.field_id);
            } catch (e) {
                console.log(e);
            }
        },
        async loadUtmData() {
            this.utmSelect = await this.$API.fields().getValues(this.editItem.field_id);
            const indexedUtmSelect = {};
            this.utmSelect.forEach(utmValue => indexedUtmSelect[utmValue.value] = utmValue);

            this.editItem.conditions.forEach(variation => {
                const selectedUtmTag = variation.utm_tag || this.defaultUtm;
                variation.utm_tag = selectedUtmTag;
                variation.selectedUTM = indexedUtmSelect[selectedUtmTag];

                if (!variation.selectedUTM) {
                    variation.allowCustomUTM = true;
                    variation.selectedUTM = indexedUtmSelect[""];
                }
            });
        },
        addNewConditionVariation() {
            const initEntityConditionVariation = this.initEntityConditionVariation();
            initEntityConditionVariation.allowCustomValue = !this.valuesSelect.length;
            initEntityConditionVariation.operator_id = this.selectedField.default_operator_id;
            this.editItem.conditions.push(initEntityConditionVariation);

            this.$forceUpdate();
        },
        removeConditionVariation(index) {
            this.editItem.conditions.splice(index, 1);

            this.$forceUpdate();
        },
        addValues(values, conditionVariation) {
            conditionVariation.selectSearch = "";

            conditionVariation.allowCustomValue = values.some(value => !value.value);
            const previousSelectedValuesCount = conditionVariation.selectedValuesCount;
            conditionVariation.selectedValuesCount = values.length;

            if (conditionVariation.allowCustomValue) {
                if (previousSelectedValuesCount > values.length) {
                    return;
                }

                const oldValues = conditionVariation.value ? `${conditionVariation.value}` : '';
                const newValue = values[values.length - 1]?.value;
                const separator = oldValues && newValue ? ',' : '';
                const rawValue = `${oldValues}${separator}${newValue}`;

                conditionVariation.value = rawValue.replaceAll(/,{2,}/g, ',').replaceAll(/^,+|,+$/g, '');
                this.$forceUpdate();
                return;
            }

            conditionVariation.value = "";
            conditionVariation.value = values.map(fieldValue => fieldValue.value).filter(v => !!v).join(',')

            this.$forceUpdate();
        },
        handleUTMTagChange(utmValue, conditionVariation) {
            conditionVariation.selectSearch = "";

            if (utmValue?.value) {
                conditionVariation.utm_tag = utmValue.value;
                conditionVariation.allowCustomUTM = false;
                this.$forceUpdate();
                return;
            }

            conditionVariation.utm_tag = "";
            conditionVariation.allowCustomUTM = true;
            this.$forceUpdate();
        },
        setAllowCustom() {
            let allowCustom = false;

            if (
                this.editItem.field_id === this.specialFieldsIds.utm
                || this.editItem.field_id === this.specialFieldsIds.zip_code
                || !this.valuesSelect.length
            ) {
                allowCustom = true;
            }

            this.editItem.conditions.forEach(conditionVariation => {
                conditionVariation.allowCustomValue = allowCustom;
            });
        },
        setPreviousSelects() {
            if (this.valuesSelect.length) {
                this.editItem.conditions.forEach(conditionVariation => {
                    const values = conditionVariation.value;
                    const selectedValues = this.valuesSelect.filter(valueSelect => {
                        const regexp = new RegExp(`(?<=^|,)${valueSelect.value}(?=,|$)`, 'gi');

                        return valueSelect.value && values.search(regexp) > -1
                    });
                    const selectedValuesString = selectedValues.map(v => v.value).join(',');
                    const selectedValuesElementsCount = selectedValuesString.split(',').filter(v => v).length;

                    conditionVariation.allowCustomValue = selectedValuesElementsCount !== values.split(',').length;

                    if (conditionVariation.allowCustomValue) {
                        selectedValues.push(this.valuesSelect.find(valueSelect => valueSelect.value === ''));
                    }

                    conditionVariation.selectedValues = selectedValues;
                    conditionVariation.selectedValuesCount = selectedValues.length;
                })
            }
            this.$forceUpdate();
        },

        triggerShortcodeCopy() {
            this.copyShortcode = true;

            setTimeout(() => this.copyShortcode = false, 1000);
        },
        async save() {
            if (this.$refs.form.validate()) {
                this.loading = true;
                try {
                    const conditionModel = {
                        id: this.editItem.id,
                        label: this.editItem.label,
                        active: this.editItem.active,
                        field_id: this.editItem.field_id,
                        default_content_id: this.editItem.default_content_id,
                        conditions: this.editItem.conditions.map(variation => ({
                            value: variation.value,
                            operator_id: variation.operator_id,
                            dynamic_content_id: variation.dynamic_content_id,
                            zip_country: variation.zip_country,
                            utm_tag: variation.utm_tag,
                        })),
                    }

                    if (!this.id) {
                        await this.$API.conditions().create(conditionModel);
                        await this.$store.dispatch('alert/showSuccess', this.$t('alert.created'));
                    } else {
                        await this.$API.conditions().update(conditionModel.id, conditionModel);
                        await this.$store.dispatch('alert/showSuccess', this.$t('alert.updated'));
                    }

                    this.resetForm();
                    this.$emit('saved');
                    this.loading = false;
                } catch (e) {
                    console.log(e);
                    this.errorMessages = e.errors ?? {};
                    await this.$store.dispatch('alert/showError', this.$t('alert.invalid_data'));
                }
            }
        },
        cancel() {
            this.loading = true;
            this.$emit('canceled');
            this.resetForm();
            this.loading = false;
        },
        resetForm() {
            this.editAction = false;
            this.allowCustomUTM = false;
            this.$refs.form.reset();
            this.editItem = this.initEntityEditItem();

            this.operatorSelect = [];
            this.valuesSelect = [];
        },
        previewContent(contentId) {
            const content = this.contents.find(content => Number(content.id) === Number(contentId));
            window.open(content.link, '_blank');
        },
        createNewConditionContent() {
            window.open(`${location.origin}/wp-admin/post-new.php?post_type=conditions_content`, '_blank');
        },
        toggleEditItemStatus() {
            this.editItem.active = !this.editItem.active;
            this.$forceUpdate();
        },
        getValueSeparatorHint(variation) {
            const defaultHint = this.$t('conditions.form.separator_hints.comma');

            if (!this.operatorSelect.length) {
                return defaultHint;
            }

            const zipcodeRangeOperator = this.indexedOperatorsByAlias[OperatorsEnum.ZIPCODE_RANGE] || {};
            if (Number(variation.operator_id) === Number(zipcodeRangeOperator.value)) {
                return this.$t('conditions.form.separator_hints.zipcode_range');
            }

            return defaultHint;
        },
    }
}
</script>

<template>
    <v-card
        v-if="readyToMount"
        v-show="show"
        id="dialog_condition_edit"
        class="pa-8 relative"
        elevation="0"
    >
        <v-card-title>
            <span v-if="!editAction">{{ $t('conditions.form.create_title') }}</span>
            <span v-if="editAction">{{ $t('conditions.form.edit_title') }}</span>
            <v-spacer></v-spacer>
            <div>
                <v-btn
                    class="reload_content_list_btn"
                    @click="loadConditionContents"
                    elevation="0"
                >
                    <v-icon class="pr-2">mdi-reload</v-icon>
                    {{ $t('conditions.form.refresh_contents_list') }}
                </v-btn>
                <v-btn
                    class="content_create_btn"
                    @click="createNewConditionContent()"
                    elevation="0"
                    outlined
                >
                    <v-icon class="pr-2">mdi-plus</v-icon>
                    {{ $t('conditions.form.add_new_content_btn') }}
                </v-btn>
            </div>
            <div
                class="status_switch"
            >
                <button @click="toggleEditItemStatus">
                    {{ $t(`switches.${editItem.active ? 'active' : 'inactive'}`) }}
                </button>
                <toggle-switch v-model="editItem.active"></toggle-switch>
            </div>
        </v-card-title>

        <div class="pl-4 pb-4">
            <template v-if="editAction">
                <div class="col_title">
                    <b>{{ $t('conditions.form.shortcode') }}</b>
                    <v-btn
                        @click="triggerShortcodeCopy"
                        @contextmenu.prevent="triggerShortcodeCopy"
                        icon
                    >
                        <v-icon size="24">mdi-content-copy</v-icon>
                    </v-btn>
                    <shortcode-copy :condition-id="Number(editItem.id)" :copy="copyShortcode"></shortcode-copy>
                </div>
                <div class="col_description" style="margin-left: auto;">{{ $t('conditions.form.shortcode_description') }}</div>
            </template>
        </div>

        <v-form
            lazy-validation
            ref="form"
        >
            <v-row class="condition_fields_row">
                <v-col>
                    <div class="condition_field">
                        <div class="field_title">{{ $t('conditions.form.label') }} <span class="required">*</span></div>
                        <div>
                            <v-text-field
                                v-model="editItem.label"
                                :rules="[
                                    $formRules.required(),
                                    $formRules.minLength(5),
                                    $formRules.maxLength(255)
                                ]"
                                :error-messages="errorMessages.label"
                                outlined
                            ></v-text-field>
                        </div>
                    </div>
                    <div class="condition_field">
                        <div class="field_title">{{ $t('conditions.form.condition') }} <span class="required">*</span></div>
                        <div>
                            <v-select
                                :items="fieldsSelect"
                                :rules="[
                                    $formRules.required()
                                ]"
                                :error-messages="errorMessages.field_id"
                                :value="editItem.field_id"
                                @change="handleFieldChanged"
                                return-object
                                outlined
                            ></v-select>
                        </div>
                    </div>
                </v-col>
                <v-col lg="6" md="12" sm="12" xs="12" class="condition_content_col">
                    <div class="col_title">
                        <b>
                            {{ $t('conditions.form.default_content.label') }}
                            <span class="required">*</span>
                        </b>
                    </div>
                    <v-select
                        v-model="editItem.default_content_id"
                        :items="contentsSelect"
                        :error-messages="errorMessages.default_content_id"
                        :placeholder=" $t('conditions.form.default_content.placeholder') "
                        :rules="[
                            $formRules.required()
                        ]"
                        background-color="white"
                        outlined
                    >
                        <template #append-outer>
                            <v-btn
                                @click="previewContent(editItem.default_content_id)"
                                :disabled="!editItem.default_content_id"
                                :title="$t('conditions.form.default_content.preview')"
                                icon
                            >
                                <v-icon size="30">mdi-eye</v-icon>
                            </v-btn>
                        </template>
                    </v-select>
                    <div class="col_description">{{ $t('conditions.form.default_content.description') }}</div>
                </v-col>
            </v-row>

            <template v-if="editItem.field_id">
                <div v-for="(conditionVariation, index) in editItem.conditions" :key="index">
                    <v-expand-transition>
                        <v-card
                            class="mb-10"
                            elevation="5"
                        >
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn @click="removeConditionVariation(index)" icon>
                                    <v-icon size="30">mdi-close</v-icon>
                                </v-btn>
                            </v-card-actions>

                            <v-row class="condition_fields_row">
                                <v-col>
                                    <div class="condition_field" v-if="editItem.field_id && valuesSelect.length">
                                        <div class="field_title"> {{ $t('conditions.form.value') }} <span class="required">*</span></div>
                                        <div>
                                            <v-autocomplete
                                                v-model="conditionVariation.selectedValues"
                                                :search-input.sync="conditionVariation.selectSearch"
                                                :items="valuesSelect"
                                                :rules="[
                                                    $formRules.required()
                                                ]"
                                                @change="(values) => addValues(values, conditionVariation)"
                                                outlined
                                                multiple
                                                return-object
                                            ></v-autocomplete>
                                        </div>
                                    </div>
                                    <div class="condition_field" v-if="editItem.field_id && editItem.field_id === specialFieldsIds.zip_code" key="zip_code">
                                        <div class="field_title"> {{ $t('conditions.form.country_code') }} <span class="required">*</span></div>
                                        <div>
                                            <v-autocomplete
                                                v-model="conditionVariation.zip_country"
                                                :items="countriesSelect"
                                                :rules="[
                                                    $formRules.required()
                                                ]"
                                                outlined
                                            ></v-autocomplete>
                                        </div>
                                    </div>
                                    <template v-if="editItem.field_id && editItem.field_id === specialFieldsIds.utm">
                                        <div class="condition_field" key="utm_tag">
                                            <div class="field_title"> {{ $t('conditions.form.utm_tag') }} <span class="required">*</span></div>
                                            <div>
                                                <v-autocomplete
                                                    v-model="conditionVariation.selectedUTM"
                                                    :search-input.sync="conditionVariation.selectSearch"
                                                    :items="utmSelect"
                                                    :rules="[
                                                        $formRules.required('text')
                                                    ]"
                                                    @change="utmValue => handleUTMTagChange(utmValue,conditionVariation)"
                                                    return-object
                                                    outlined
                                                ></v-autocomplete>
                                            </div>
                                        </div>
                                        <div class="condition_field" v-if="conditionVariation.allowCustomUTM" key="custom_utm_tag">
                                            <div class="field_title"> {{ $t('conditions.form.custom_utm_tag') }} <span class="required">*</span></div>
                                            <div>
                                                <v-text-field
                                                    v-model="conditionVariation.utm_tag"
                                                    :rules="[
                                                        $formRules.required(),
                                                        $formRules.minLength(1),
                                                        $formRules.maxLength(255)
                                                    ]"
                                                    outlined
                                                ></v-text-field>
                                            </div>
                                        </div>
                                    </template>
                                    <div class="condition_field" v-if="operatorSelect.length && (!valuesSelect.length || conditionVariation.allowCustomValue)">
                                        <div class="field_title">{{ $t('conditions.form.operator') }} <span class="required">*</span></div>
                                        <div>
                                            <v-select
                                                v-model="conditionVariation.operator_id"
                                                :items="operatorSelect"
                                                :rules="[
                                                    $formRules.required()
                                                ]"
                                                outlined
                                            ></v-select>
                                        </div>
                                    </div>
                                    <div class="condition_field" v-if="editItem.field_id">
                                        <div class="field_title">{{ selectedFieldName }} <span class="required">*</span></div>
                                        <div>
                                            <v-text-field
                                                v-model="conditionVariation.value"
                                                :rules="[
                                                    $formRules.required(),
                                                    $formRules.minLength(1),
                                                    $formRules.maxLength(255)
                                                ]"
                                                :error-messages="errorMessages.value"
                                                :disabled="!conditionVariation.allowCustomValue"
                                                :hint="getValueSeparatorHint(conditionVariation)"
                                                outlined
                                            ></v-text-field>
                                        </div>
                                    </div>
                                </v-col>
                                <v-col lg="6" md="12" sm="12" xs="12" class="condition_content_col">
                                    <div class="col_title"><b>{{ $t('conditions.form.content.label') }}</b> <span class="required">*</span></div>
                                    <v-select
                                        v-model="conditionVariation.dynamic_content_id"
                                        :items="contentsSelect"
                                        :placeholder="$t('conditions.form.content.placeholder')"
                                        :rules="[
                                            $formRules.required()
                                        ]"
                                        background-color="white"
                                        outlined
                                    >
                                        <template #append-outer>
                                            <v-btn
                                                @click="previewContent(conditionVariation.dynamic_content_id)"
                                                :disabled="!conditionVariation.dynamic_content_id"
                                                :title="$t('conditions.form.default_content.preview')"
                                                icon
                                            >
                                                <v-icon size="30">mdi-eye</v-icon>
                                            </v-btn>
                                        </template>
                                    </v-select>
                                    <div class="col_description">{{ $t('conditions.form.content.description') }}</div>
                                    <br>
                                </v-col>
                            </v-row>
                        </v-card>
                    </v-expand-transition>
                </div>

                <v-btn @click="addNewConditionVariation" outlined>
                    <b>
                        <v-icon size="30">mdi-plus</v-icon>
                        {{ $t('conditions.form.add_new_condition_btn') }}
                    </b>
                </v-btn>
            </template>
        </v-form>

        <br><br>

        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn class="orange_btn" @click="save()" :loading="loading">
                <span>{{ $t('buttons.save') }}</span>
            </v-btn>
            <v-btn @click="cancel">{{ $t('buttons.cancel') }}</v-btn>
        </v-card-actions>
    </v-card>
</template>

<style scoped lang="scss">
$grayColor: #F3F3F3;
$orangeColor: #f69d32;

#dialog_condition_edit {
    .v-card__title {
        font-size: 24px;
        font-weight: bold;
    }

    .reload_content_list_btn,
    .content_create_btn {
        font-weight: bold;
        font-size: 18px;
        margin: 0 0 5px 0;

    }

    .reload_content_list_btn {
        background-color: $orangeColor;
        color: white;
    }

    .condition_content_col {
        .col_title {
            padding-bottom: 5px;
            font-weight: bold;

            .required {
                color: red;
            }
        }

        .col_description {
            width: 80%;
            padding-top: 10px;
        }

        .v-select .v-input__append-outer {
            margin-top: 10px;
        }
    }
}

.condition_fields_row {
    margin: 0 20px !important;

    .condition_field {
        margin: 0;

        .field_title {
            font-weight: bold;

            .required {
                color: red;
            }
        }

        div:first-child {
            font-weight: bold;
            padding-bottom: 5px;
        }
    }
}

</style>
