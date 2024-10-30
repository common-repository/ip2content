<script>
import languages from "./../config/languages.json";
import ToggleSwitch from "./components/ToggleSwitch";

export default {
    name: "Settings",
    components: { ToggleSwitch },
    data: () => ({
        settings: {
            ip2c: false,
            tracking: false,
            utm_tracking: false,
            company_detection: false,
            language: "en",
            // cache: false,
        },
        languages: [],
    }),

    async beforeMount() {
        this.prepareLanguages();
        this.settings = await this.$API.settings().get();
    },

    methods: {
        prepareLanguages() {
            this.languages = languages.all.map((language) => ({
                value: language,
                text: language.toUpperCase(),
            }));
        },
        async clearCache() {
            console.log(this.$API.settings().clearCache())
        },
        async save() {
            try {
                await this.$API.settings().create(this.settings);
                await this.$store.dispatch(
                    "app/updateLanguage",
                    this.settings.language
                );
                this.$i18n.locale = this.settings.language;
                await this.$store.dispatch(
                    "alert/showSuccess",
                    this.$t("alert.saved")
                );

                setTimeout(() => {
                    location.reload();
                }, 1000);
            } catch (e) {
                console.log(e);
                await this.$store.dispatch("alert/showError", e.message);
            }
        },
    },
};
</script>

<template>
    <div id="tabs_settings">
        <v-container fluid class="settings_container">
            <v-row class="title_row">
                <v-col cols="12">{{ $t("settings.title") }}</v-col>
            </v-row>
            <v-row>
                <v-col>{{ $t("settings.ip2c") }}</v-col>
                <v-col>
                    <toggle-switch v-model="settings.ip2c"></toggle-switch>
                </v-col>
            </v-row>
            <v-row>
                <v-col>{{ $t("settings.tracking") }}</v-col>
                <v-col>
                    <toggle-switch v-model="settings.tracking"></toggle-switch>
                </v-col>
            </v-row>
            <v-row>
                <v-col>{{ $t("settings.utm_tracking") }}</v-col>
                <v-col>
                    <toggle-switch
                        v-model="settings.utm_tracking"
                    ></toggle-switch>
                </v-col>
            </v-row>
            <v-row>
                <v-col>{{ $t("settings.company_detection") }}</v-col>
                <v-col>
                    <toggle-switch
                        v-model="settings.company_detection"
                    ></toggle-switch>
                </v-col>
            </v-row>
            <v-row>
                <v-col>{{ $t("settings.cache") }}</v-col>
                <v-col>
                    <toggle-switch
                        v-model="settings.cache"
                    ></toggle-switch>
                </v-col>
            </v-row>
            <v-row>
                <v-col>{{ $t("settings.language") }}</v-col>
                <v-col>
                    <div style="width: 80px; height: 55px; margin-left: auto">
                        <v-select
                            v-model="settings.language"
                            :items="languages"
                            hide-details
                        >
                        </v-select>
                    </div>
                </v-col>
            </v-row>
            <v-row class="footer_row">
                <v-col cols="6">
                    <v-btn @click="save" class="save_settings">{{
                        $t("buttons.save")
                    }}</v-btn>
                </v-col>
                <v-col cols="6" align="right">
                     <v-btn @click="clearCache" color="error">{{
                        $t("settings.clear_cache")
                    }}</v-btn>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<style lang="scss">
$grayColor: #f3f3f3;
$grayBorderColor: #dfdfdf;
$grayTextColor: #707070;
$orangeColor: #f69d32;

#tabs_settings {
    max-width: 500px;
    margin: 50px 100px;

    .settings_container {
        border: solid $grayBorderColor 1px;
        color: $grayTextColor;
        font-weight: bold;
    }
}

#tabs_settings .row {
    background-color: $grayColor;
    padding: 0 15px;
    line-height: 55px;

    .col {
        padding: 0;
        margin: 0;
    }

    .save_settings {
        background-color: $orangeColor;
        color: white;
    }

    .col:last-child:not(:first-child) {
        text-align: right;
    }

    &:nth-child(even):not(.title_row, .footer_row, :last-child) {
        background-color: #fff;
    }

    &.title_row {
        padding: 15px 15px 40px;
    }

    &.footer_row {
        height: 100px;
    }

    &.footer_row .v-btn {
        margin-top: 30px;
        font-weight: bold;
    }
}

@media screen and (max-width: 960px) {
    #tabs_settings {
        margin: 50px 50px;
    }
}
</style>
