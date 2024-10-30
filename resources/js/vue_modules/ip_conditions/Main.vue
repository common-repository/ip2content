<script>
import Vue from "vue";
import Results from "./modules/Results";
import Conditions from "./modules/conditions/Index";
import Settings from "./modules/Settings";
import License from "./modules/License";
import Alert from "./modules/components/Alert";

export default Vue.extend({
    name: "Main",
    components: { License, Settings, Results, Conditions, Alert },

    data: () => ({
        imagesUrl: ipConditionsData.imagesUrl,
        tab: null,
        tabDefault: "results",
        pluginVersion: "",
    }),

    mounted() {
        this.pluginVersion =
            this.$store.getters["app/getDataBridge"]?.plugin_version;

        const url = new URL(location);
        this.tab = url.searchParams.get("tab") || this.tabDefault;
    },

    watch: {
        tab(value) {
            const url = new URL(location);
            if (url.searchParams.get("tab") === this.tab) {
                return;
            }
            url.searchParams.set("tab", value);
            history.replaceState({}, "", url);
        },
    },

    methods: {
        changeTabToConditions() {
            this.tab = "conditions";
        },
        changeTabToSettings() {
            this.tab = "settings";
        },
        changeTabToLicenses() {
            this.tab = "licenses";
        },
    },
});
</script>

<template>
    <v-app>
        <header class="header">
            <a class="logo" href="#">
                <img
                    :src="`${imagesUrl}ip_conditions/logo_white.svg`"
                    height="50"
                    alt=""
                />
            </a>
            <span class="version">V{{ pluginVersion }}</span>
            <div class="ip2c_nav">
                <v-tabs
                    v-model="tab"
                    background-color="transparent"
                    prev-icon="mdi-arrow-left-circle"
                    next-icon="mdi-arrow-right-circle"
                    show-arrows
                >
                    <v-tab href="results" @click.prevent>
                        {{ $t("statistics.tab_name") }}
                    </v-tab>
                    <v-tab href="conditions" @click.prevent>
                        {{ $t("conditions.tab_name") }}
                    </v-tab>
                    <v-tab href="settings" @click.prevent>
                        {{ $t("settings.tab_name") }}
                    </v-tab>
                    <v-tab href="licenses" @click.prevent>
                        {{ $t("licenses.tab_name") }}
                    </v-tab>
                </v-tabs>
            </div>
        </header>

        <v-main>
            <v-tabs-items v-model="tab">
                <v-tab-item value="results">
                    <results
                        :images-url="imagesUrl"
                        @switchToConditions="changeTabToConditions"
                        @switchToSettings="changeTabToSettings"
                        @switchToLicenses="changeTabToLicenses"
                    ></results>
                </v-tab-item>
                <v-tab-item value="conditions">
                    <conditions :images-url="imagesUrl"></conditions>
                </v-tab-item>
                <v-tab-item value="settings">
                    <settings></settings>
                </v-tab-item>
                <v-tab-item value="licenses">
                    <license></license>
                </v-tab-item>
            </v-tabs-items>
        </v-main>

        <v-footer class="footer">
            <a class="footer__logo" href="#">
                <img
                    :src="`${imagesUrl}ip_conditions/wiredminds_logo_white.svg`"
                    height="70"
                    alt=""
                />
            </a>
            <a :href="$t('faq.url')" target="_blank" class="faq_link">
                <v-btn class="faq_btn" elevation="0">FAQ</v-btn>
            </a>
        </v-footer>

        <alert></alert>
    </v-app>
</template>

<style lang="scss">
@import "./assets/styles/global";

$grayColor: #f3f3f3;
$orangeColor: #f69d32;

#ip_conditions {
    .v-input input.readonly,
    .v-input input[readonly],
    .v-input input[type="text"],
    .v-input input[type="number"],
    .v-input input[type="text"]:focus,
    .v-select input.readonly,
    .v-select input[readonly],
    .v-select input[type="text"],
    .v-select input[type="text"],
    .v-select input[type="text"]:focus {
        background: inherit;
        border: none;
        box-shadow: none;
    }

    .v-application--wrap {
        min-height: calc(100vh - 190px);
    }

    .header {
        .ip2c_nav {
            position: absolute;
            bottom: -3px;
            left: 0;
            right: 0;
            padding: 0 50px;
        }

        .v-tabs {
            align-items: end;

            .v-slide-group__prev *,
            .v-slide-group__next * {
                color: white;
            }

            .v-icon.v-icon--disabled * {
                color: rgb(255, 255, 255, 0.5);
            }

            .v-tab {
                color: #fff;
                padding: 12px 75px;
                border: 1px solid transparent;
                border-radius: 10px 10px 0 0;
                font-size: 20px;
                font-weight: bold;
            }

            .v-tab--active {
                border: 1px solid grey;
                background-color: #fff;
                color: grey;
                outline: none;
                border-bottom: white;
            }
        }
    }

    .faq_link {
        position: absolute;
        right: 20px;

        .faq_btn {
            color: white;
            background-color: $orangeColor;
            font-weight: bold;
        }
    }
}

@media screen and (max-width: 1500px) {
    #ip_conditions .header .v-tabs .v-tab {
        padding: 12px 50px;
        font-size: 18px;
    }
}

@media screen and (max-width: 1150px) {
    #ip_conditions .header .v-tabs .v-tab {
        padding: 12px 20px;
    }
}
</style>
