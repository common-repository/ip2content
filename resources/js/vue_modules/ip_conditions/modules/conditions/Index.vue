<script>
import conditionsTableConfig from "./config/conditions_table.json";
import ShortcodeConfig from "../../config/ShortcodeConfig.json";

import Paginator from "../components/Paginator";
import ToggleSwitch from "../components/ToggleSwitch";
import CreateEdit from "./CreateEdit";
import ShortcodeCopy from "../components/ShortcodeCopy";

export default {
    name: "Conditions",

    components: { ShortcodeCopy, CreateEdit, ToggleSwitch, Paginator },

    props: {
        imagesUrl: {
            type: String,
            required: true,
        },
    },

    data: () => ({
        headers: conditionsTableConfig.headers,
        items: [],
        pagination: {
            page: 1,
            per_page: 10,
            last_page: 1,
            total_items: 0,
        },
        itemsPerPageOptions: [5, 10, 20, 40, 200],
        loading: false,
        readyToMount: false,
        createEditAction: false,
        mountCreateEditComponent: false,
        copyShortcode: false,
        copyShortcodeId: 0,
        shortcodeText: ShortcodeConfig.text,
        confirmDeleteDialog: false,
        conditionIdToDelete: 0,
        conditionIdToEdit: 0,
    }),

    async beforeMount() {
        await this.loadData();
        this.prepareHeaders();
        this.readyToMount = true;
    },

    methods: {
        async openCreateComponent() {
            this.mountCreateEditComponent = true;
            this.createEditAction = true;
        },
        async openEditComponent(conditionId) {
            this.mountCreateEditComponent = true;
            this.conditionIdToEdit = conditionId;
            this.createEditAction = true;
        },
        conditionSaved() {
            this.createEditAction = false;
            this.conditionIdToEdit = null;
            this.loadData();
        },
        conditionCreateEditCanceled() {
            this.createEditAction = false;
            this.conditionIdToEdit = null;
        },
        getFormattedDate(date) {
            return this.$moment(date).format("DD.MM.YYYY HH:mm");
        },
        triggerShortcodeCopy(conditionId) {
            this.copyShortcodeId = conditionId;
            this.copyShortcode = true;

            setTimeout(() => (this.copyShortcode = false), 1000);
        },
        async loadData() {
            this.loading = true;
            try {
                const response = await this.$API.conditions().get({
                    page: this.pagination.page,
                    per_page: this.pagination.per_page,
                });

                this.items = response.items;
                this.pagination = response.pagination;
            } catch (e) {
                console.log(e);
            }
            this.loading = false;
        },
        prepareHeaders() {
            const orderedHeaders = [];
            this.headers.forEach((header) => {
                header.text = this.$t(`conditions.headers.${header.value}`);

                if (header.value === "active") {
                    orderedHeaders.push({
                        value: "shortcode",
                        text: this.$t(`conditions.headers.shortcode`),
                        align: "center",
                        sortable: false,
                        width: 450,
                    });
                }

                orderedHeaders.push(header);
            });

            orderedHeaders.push({
                value: "actions",
                text: "",
                align: "center",
                sortable: false,
                width: 170,
            });

            this.headers = orderedHeaders;
        },
        openDeleteConfirmDialog(conditionId) {
            this.conditionIdToDelete = conditionId;
            this.confirmDeleteDialog = true;
        },
        async deleteCondition() {
            try {
                await this.$API.conditions().delete({
                    id: this.conditionIdToDelete,
                });

                const conditionIndexToDelete = this.items.findIndex(
                    (item) =>
                        Number(item.id) === Number(this.conditionIdToDelete)
                );
                this.items.splice(conditionIndexToDelete, 1);
                this.confirmDeleteDialog = false;

                await this.$store.dispatch(
                    "alert/showSuccess",
                    this.$t("alert.deleted")
                );
            } catch (e) {
                console.log(e);
                await this.$store.dispatch("alert/showError", e.message);
            }
        },
        async toggleConditionStatus(condition) {
            try {
                await this.$API
                    .conditions()
                    .updateStatus(condition.id, !condition.active);

                condition.active = !condition.active;

                await this.$store.dispatch(
                    "alert/showSuccess",
                    this.$t("alert.updated")
                );
            } catch (e) {
                console.log(e);
                await this.$store.dispatch("alert/showError", e.message);
            }
        },
    },
};
</script>

<template>
    <div id="tabs_condition">
        <div v-if="readyToMount" v-show="!loading">
            <create-edit
                v-if="mountCreateEditComponent"
                :show="createEditAction"
                :id="Number(conditionIdToEdit)"
                @canceled="conditionCreateEditCanceled"
                @saved="conditionSaved"
            ></create-edit>

            <shortcode-copy
                :condition-id="Number(copyShortcodeId)"
                :copy="copyShortcode"
            ></shortcode-copy>

            <div v-show="!createEditAction">
                <div class="actions_row">
                    <button id="add_condition" @click="openCreateComponent">
                        {{ $t("conditions.add_condition") }}
                    </button>
                </div>

                <br /><br />

                <div id="conditions_table">
                    <v-data-table
                        :headers.sync="headers"
                        :items.sync="items"
                        :loading="loading"
                        disable-filtering
                        disable-pagination
                        disable-sort
                        hide-default-footer
                    >
                        <template #item="{ item }">
                            <tr>
                                <template v-for="header in headers">
                                    <td
                                        :key="header.value"
                                        :class="[
                                            `text-${header.align || 'start'}`,
                                        ]"
                                        class="three_dots_overflow"
                                    >
                                        <template
                                            v-if="header.value === 'field'"
                                        >
                                            {{
                                                $t(
                                                    `conditions_fields.${
                                                        item[header.value]
                                                    }`
                                                )
                                            }}
                                        </template>
                                        <template
                                            v-else-if="
                                                header.value === 'updated_at'
                                            "
                                        >
                                            {{
                                                getFormattedDate(
                                                    item.updated_at
                                                )
                                            }}
                                        </template>
                                        <template
                                            v-else-if="
                                                header.value === 'shortcode'
                                            "
                                        >
                                            <span class="condition_shortcode">
                                                {{
                                                    shortcodeText.replace(
                                                        "{id}",
                                                        item.id
                                                    )
                                                }}
                                            </span>
                                        </template>
                                        <template
                                            v-else-if="
                                                header.value === 'active'
                                            "
                                        >
                                            <div>
                                                <toggle-switch
                                                    :value="item.active"
                                                    @input="
                                                        toggleConditionStatus(
                                                            item
                                                        )
                                                    "
                                                    :width="40"
                                                    :height="20"
                                                    :title="
                                                        $t(
                                                            'conditions.labels.toggle_status'
                                                        )
                                                    "
                                                ></toggle-switch>
                                            </div>
                                        </template>
                                        <template
                                            v-else-if="
                                                header.value === 'actions'
                                            "
                                        >
                                            <div class="row_action">
                                                <v-btn
                                                    @click="
                                                        triggerShortcodeCopy(
                                                            item.id
                                                        )
                                                    "
                                                    @contextmenu.prevent="
                                                        triggerShortcodeCopy(
                                                            item.id
                                                        )
                                                    "
                                                    icon
                                                    :title="
                                                        $t(
                                                            'conditions.labels.shortcode'
                                                        )
                                                    "
                                                >
                                                    <v-icon size="24"
                                                        >mdi-content-copy</v-icon
                                                    >
                                                </v-btn>
                                                <v-btn
                                                    icon
                                                    @click="
                                                        openEditComponent(
                                                            item.id
                                                        )
                                                    "
                                                    :title="
                                                        $t(
                                                            'conditions.labels.edit'
                                                        )
                                                    "
                                                >
                                                    <v-icon size="24">
                                                        mdi-pencil
                                                    </v-icon>
                                                </v-btn>
                                                <v-btn
                                                    @click="
                                                        openDeleteConfirmDialog(
                                                            item.id
                                                        )
                                                    "
                                                    icon
                                                    :title="
                                                        $t(
                                                            'conditions.labels.delete'
                                                        )
                                                    "
                                                >
                                                    <v-icon
                                                        color="red"
                                                        size="24"
                                                    >
                                                        mdi-trash-can-outline
                                                    </v-icon>
                                                </v-btn>
                                            </div>
                                        </template>
                                        <template v-else>
                                            <span :title="item[header.value]">{{
                                                item[header.value]
                                            }}</span>
                                        </template>
                                    </td>
                                </template>
                            </tr>
                        </template>
                        <template #body.append>
                            <tr>
                                <td></td>
                            </tr>
                        </template>
                        <template #footer>
                            <Paginator
                                v-model="pagination"
                                :items-per-page-options="itemsPerPageOptions"
                                @updatePerPage="loadData()"
                                @updatePage="loadData()"
                            />
                        </template>
                    </v-data-table>
                    <v-dialog v-model="confirmDeleteDialog" width="300">
                        <v-card>
                            <v-card-title>{{
                                $t("conditions.confirm_delete")
                            }}</v-card-title>

                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn
                                    @click="deleteCondition"
                                    class="orange_btn"
                                    >{{ $t("buttons.confirm") }}</v-btn
                                >
                                <v-btn @click="confirmDeleteDialog = false">{{
                                    $t("buttons.cancel")
                                }}</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </div>
            </div>
        </div>
        <div v-show="!readyToMount || loading">
            <v-skeleton-loader type="table"></v-skeleton-loader>
        </div>
    </div>
</template>

<style lang="scss">
$grayColor: #f3f3f3;
$orangeColor: #f69d32;

.condition_shortcode {
    user-select: all;
}

.orange_btn {
    color: white !important;
    background-color: $orangeColor !important;
    font-weight: bold !important;
}

#tabs_condition {
    margin: 30px;
    display: flex;
    flex-direction: column;

    #conditions_table {
        flex-grow: 1;
    }
}

#add_condition {
    font-weight: bold;
    color: #fff;
    background-color: orange;
    padding: 10px;
}

#conditions_table {
    border: 1px solid #dfdfdf;

    .condition_status {
        display: flex;
        padding-left: 17px;

        div {
            margin: 5px 0 0 5px;
            display: inline-block;
            height: 15px;
            width: 15px;
            -webkit-border-radius: 50%;
            -moz-border-radius: 50%;
            border-radius: 50%;
            background-color: red;
        }

        div.active {
            background-color: green;
        }
    }

    table tr th,
    table tr td,
    .v-data-footer * {
        border: none;
        font-size: 16px;
    }

    .v-data-table {
        table {
            tr {
                padding: 15px 0;

                &:hover {
                    background-color: initial;
                }

                &:nth-child(even):not(:last-child) {
                    background-color: $grayColor;
                }
            }

            tr td {
                color: #838b94;
                max-width: 300px;
            }

            thead {
                background: $grayColor;
                padding: 20px 30px 20px 10px;
            }

            tbody {
                tr td {
                    height: 50px;
                }
            }
        }

        .v-data-footer {
            background-color: $grayColor;
        }

        .custom-data-footer {
            height: 80px;
            border: none;
        }
    }
}

.status_switch {
    width: 175px;
    text-align: right;

    .toggle_switch {
        display: inline-block;
    }
}
</style>
