<script>
import statisticsMockup from './mockup_data/statistics_tab.json'
import Chart from './components/Chart'
import ToggleSwitch from "./components/ToggleSwitch.vue";

export default {
    name: "Results",

    components: {ToggleSwitch, Chart},

    props: {
        imagesUrl: {
            type: String,
            required: true,
        }
    },

    data: () => ({
        readyToMount: false,
        results: {},
        topDetectedCompanies: [],
        topConditions: [],
        settings: {
            tracking: null,
            ip2c: null,
        },
        licenses: {
            tracking: "",
            ip2c: ""
        },
        chart: {
            colors: {
                totalVisits: '#8dc466',
                conditionsHits: '#df543d',
                noData: '#707070',

            },
            options: {
                borderWidth: 0,
                hoverBorderWidth: 0
            },
            data: {
                labels: ['Inactive'],
                datasets: [1],
                backgroundColor: ['#707070']
            }
        },
        skeletonTableOptions: {
            'table-heading': 'text',
            'table-tbody': 'table-row-divider@11',
            'table-row': 'table-cell@3',
            'table-thead': 'heading@3',
            'table-tfoot': '',
        }
    }),

    async beforeMount() {
        await this.loadSettings();
        await this.loadLicenses();
        await this.loadData();
        this.configureChart();
        this.readyToMount = true;
    },

    computed: {
        isIp2CompanyActive() {
            return this.settings.ip2c && !!this.licenses.ip2c;
        },
        getResults() {
            return this.isIp2CompanyActive ? this.results : statisticsMockup.results;
        },
        getLatestDetectedCompanies() {
            return this.isIp2CompanyActive ? this.topDetectedCompanies : statisticsMockup.topDetectedCompanies;
        },
        getTopCondition() {
            return this.topConditions.length ? this.topConditions : statisticsMockup.topCondition;
        }
    },
    methods: {
        async loadData() {
            try {
                this.topConditions = await this.$API.statistics().getTopConditions();

            } catch (e) {
                console.log(e)
            }

            try {
                if (this.isIp2CompanyActive) {
                    const [results, topDetected] = await Promise.all([
                        this.$API.statistics().getResults(),
                        this.$API.statistics().getLatestDetectedCompanies(),
                    ])

                    this.results = results;
                    this.topDetectedCompanies = topDetected;
                }
            } catch (e) {
                console.log(e);
            }
        },
        async loadSettings() {
            try {
                this.settings = await this.$API.settings().get();

            } catch (e) {
                console.log(e);
            }
        },
        async loadLicenses() {
            try {
                this.licenses = await this.$API.licenses().get();
            } catch (e) {
                console.log(e);
            }
        },
        configureChart() {
            const activeChartData = {
                labels: [
                    'Total visits',
                    'Conditions Hits',
                ],
                datasets: [{
                    label: 'Results',
                    data: [
                        this.getResults.total_views || 0,
                        this.getResults.total_hits || 0,
                    ],
                    backgroundColor: [
                        this.chart.colors.totalVisits,
                        this.chart.colors.conditionsHits,
                    ],
                    hoverOffset: 0
                }]
            };

            const inactiveChartData = {
                labels: ['Inactive'],
                datasets: [{
                    label: 'Inactive',
                    data: [1],
                    backgroundColor: [
                        this.chart.colors.noData
                    ],
                    hoverOffset: 0
                }],
            }

            if (this.isIp2CompanyActive && (this.getResults.total_views || this.getResults.total_hits)) {
                this.chart.data = activeChartData;
                return;
            }

            this.chart.data = inactiveChartData
        },
        getLatestCompanyByIndex(index) {
            return this.getLatestDetectedCompanies[index] || {};
        },
        getTopConditionByIndex(index) {
            return this.getTopCondition[index] || {};
        },
        switchTabToConditions() {
            this.$emit('switchToConditions');
        },
        switchTabToSettings() {
            this.$emit('switchToSettings');
        },
        switchTabToLicenses() {
            this.$emit('switchToLicenses');
        },
        getLocalizedConditionType(rowIndex) {
            const conditionType = this.getTopConditionByIndex(rowIndex).type;
            if (!conditionType) {
                return '';
            }
            return this.$t(`conditions_fields.${conditionType.toLowerCase()}`)
        },
    }
}
</script>

<template>
    <div style="padding: 50px;">
        <div id="tabs_statistics">
            <div v-if="readyToMount" class="statistics_col results">
                <div class="statistics_header pt-0">
                    {{ $t('statistics.chart_col.title') }}
                </div>
                <v-row class="chart_row">
                    <v-col>
                        <chart
                            :chart-data="chart.data"
                            :chart-options="chart.options"
                            :width="130"
                            :height="130"
                        ></chart>
                    </v-col>
                    <v-col>
                        <div class="chart_totals">
                            <div class="totals_circle" :style="{backgroundColor: chart.colors.totalVisits}"></div>
                            <span>{{ $t('statistics.chart_col.total_visits') }} </span>
                        </div>
                        <div class="chart_totals">
                            <div class="totals_circle"
                                 :style="{backgroundColor: chart.colors.conditionsHits}"></div>
                            <span>{{ $t('statistics.chart_col.conditions_hits') }}</span>
                        </div>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <div class="results_value">{{ getResults.total_views || 0 }}</div>
                        {{ $t('statistics.chart_col.total_visits') }}
                    </v-col>
                    <v-col>
                        <div class="results_value">{{ getResults.total_hits || 0 }}</div>
                        {{ $t('statistics.chart_col.conditions_hits') }}
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <div class="results_value">{{ getResults.companies_detected || 0 }}</div>
                        {{ $t('statistics.chart_col.detected_companies') }}
                    </v-col>
                    <v-col>
                        <div class="results_value">{{ getResults.ip2c_hits || 0 }}</div>
                        {{ $t('statistics.chart_col.ip2c_conditions_hits') }}
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <div class="results_value">{{ getResults.token_limit || 0 }}</div>
                        {{ $t('statistics.chart_col.tokens_available') }}
                    </v-col>
                    <v-col>
                        <div class="results_value">{{ getResults.token_left || 0 }}</div>
                        {{ $t('statistics.chart_col.tokens_left') }}
                    </v-col>
                </v-row>
                <v-row no-gutters>
                    <v-col>
                        <img
                            v-if="isIp2CompanyActive"
                            :src="`${imagesUrl}ip_conditions/happy.svg`"
                            alt=""
                            width="90"

                        >
                        <img v-else :src="`${imagesUrl}ip_conditions/sad.svg`" alt="" width="90">
                    </v-col>
                    <v-col>
                        <template v-if="!this.licenses.ip2c">
                            <a @click.prevent="switchTabToLicenses()">
                                {{ $t('statistics.chart_col.inactive_smile') }}
                                <br>
                                <span style="font-size: 14px;">{{ $t('statistics.chart_col.activate') }}</span>
                                <span style="font-size: 14px;color: #f69d32;">{{ $t('statistics.chart_col.here') }}</span>
                            </a>
                        </template>
                        <template v-else-if="!settings.ip2c">
                            <a @click.prevent="switchTabToSettings()">
                                {{ $t('statistics.chart_col.inactive_smile') }}
                                <br>
                                <span style="font-size: 14px;">{{ $t('statistics.chart_col.activate') }}</span>
                                <span style="font-size: 14px;color: #f69d32;">{{ $t('statistics.chart_col.here') }}</span>
                            </a>
                        </template>
                        <template v-else>
                            <span v-html="$t('statistics.chart_col.active_smile')"></span>
                        </template>
                    </v-col>
                </v-row>
            </div>
            <div v-else class="statistics_col">
                <v-skeleton-loader
                    type="card-avatar, article, date-picker-days"
                    height="700"
                ></v-skeleton-loader>
            </div>
            <!--       ________________________________________________________________         -->
            <div v-if="readyToMount" class="statistics_col latest_companies">
                <div class="statistics_header">
                    {{ $t('statistics.companies_col.title') }}
                </div>
                <div>
                    <v-row class="statistics_headers_row">
                        <v-col>{{ $t('statistics.companies_col.columns.company') }}</v-col>
                        <v-col>{{ $t('statistics.companies_col.columns.views') }}</v-col>
                        <v-col>{{ $t('statistics.companies_col.columns.branch_code') }}</v-col>
                    </v-row>
                    <v-row
                        v-for="index in 10"
                        :key="index"
                        class="data_row"
                        :class="[isIp2CompanyActive ? '' : 'inactive']"
                    >
                        <template v-if="!getLatestDetectedCompanies.length && index === 5">
                            <v-col class="text-center" cols="12">
                                {{ $t('statistics.no_latest_companies') }}
                            </v-col>
                        </template>
                        <template v-else>
                            <v-col class="three_dots_overflow" :title="getLatestCompanyByIndex(index - 1).company">{{ getLatestCompanyByIndex(index - 1).company || '&nbsp;' }}</v-col>
                            <v-col class="three_dots_overflow" :title="getLatestCompanyByIndex(index - 1).views">{{ getLatestCompanyByIndex(index - 1).views }}</v-col>
                            <v-col class="three_dots_overflow" :title="getLatestCompanyByIndex(index - 1).branch_code">{{ getLatestCompanyByIndex(index - 1).branch_code }}</v-col>
                        </template>
                    </v-row>
                    <v-row class="empty_row">
                        <v-col>&nbsp;</v-col>
                        <v-col>&nbsp;</v-col>
                        <v-col>&nbsp;</v-col>
                    </v-row>
                </div>
                <div class="statistics_footer">
                    <div>
                        <template v-if="!this.licenses.ip2c">
                            <span v-html="$t('statistics.companies_col.footer.token_missing')"></span>
                            <a class="footer_link" @click.prevent="switchTabToLicenses()"> {{ $t('statistics.companies_col.footer.links.token_missing') }}</a>
                        </template>
                        <template v-else-if="!settings.ip2c">
                            <span v-html="$t('statistics.companies_col.footer.ip2c_disabled')"></span>
                            <a class="footer_link" @click.prevent="switchTabToSettings()"> {{ $t('statistics.companies_col.footer.links.ip2c_disabled') }}</a>
                        </template>
                        <template v-else>
                            {{ $t('statistics.companies_col.footer.active') }} <a class="footer_link" :href="$t('statistics.companies_col.footer.links.url')" target="_blank">{{ $t('statistics.companies_col.footer.links.active') }}</a>
                        </template>
                    </div>
                </div>
            </div>
            <div v-else class="statistics_col">
                <v-skeleton-loader
                    type="table"
                    :types="skeletonTableOptions"
                    height="700"
                ></v-skeleton-loader>
            </div>
            <!--       ________________________________________________________________         -->
            <div v-if="readyToMount" class="statistics_col top_conditions">
                <div class="statistics_header">
                    {{ $t('statistics.conditions_col.title') }}
                </div>
                <div>
                    <v-row class="statistics_headers_row">
                        <v-col>{{ $t('statistics.conditions_col.columns.condition') }}</v-col>
                        <v-col>{{ $t('statistics.conditions_col.columns.views') }}</v-col>
                        <v-col>{{ $t('statistics.conditions_col.columns.type') }}</v-col>
                    </v-row>
                    <v-row
                        v-for="index in 10"
                        :key="index"
                        class="data_row"
                        :class="[topConditions.length ? '' : 'inactive']"
                    >
                        <v-col class="three_dots_overflow" :title="getTopConditionByIndex(index - 1).condition || ''">{{ getTopConditionByIndex(index - 1).condition || '&nbsp;' }}</v-col>
                        <v-col class="three_dots_overflow" :title="getTopConditionByIndex(index - 1).views">{{ getTopConditionByIndex(index - 1).views }}</v-col>
                        <v-col class="three_dots_overflow" :title="getLocalizedConditionType(index - 1)">{{ getLocalizedConditionType(index - 1) }}</v-col>
                    </v-row>
                    <v-row class="empty_row">
                        <v-col>&nbsp;</v-col>
                        <v-col>&nbsp;</v-col>
                        <v-col>&nbsp;</v-col>
                    </v-row>
                </div>
                <div class="statistics_footer">
                    <div>
                        <template v-if="topConditions.length">
                            <span v-html=" $t('statistics.conditions_col.footer.switch_tab')"></span> <a class="footer_link" @click.prevent="switchTabToConditions()">{{ $t('statistics.conditions_col.footer.links.switch_tab') }}</a>
                        </template>
                        <template v-else>
                            <span v-html=" $t('statistics.conditions_col.footer.no_conditions')"></span> <a class="footer_link" @click.prevent="switchTabToConditions()">{{ $t('statistics.conditions_col.footer.links.no_conditions') }}</a>
                        </template>
                    </div>
                </div>
            </div>
            <div v-else class="statistics_col">
                <v-skeleton-loader
                    type="table"
                    :types="skeletonTableOptions"
                    height="700"
                ></v-skeleton-loader>
            </div>
        </div>
    </div>
</template>

<style lang="scss">

$grayBgColor: #f3f3f3;
$grayBorderColor: #dfdfdf;
$grayTextColor: #707070;
$orangeColor: #f69d32;

#tabs_statistics {
    display: grid;
    grid: [row1-start] "results companies conditions" [row1-end] / 450px 1fr 1fr;
    gap: 20px;


    .results {
        grid-area: results;
    }

    .latest_companies {
        grid-area: companies;

    }

    .top_conditions {
        grid-area: conditions;
    }

    * {
        font-weight: bold;
        color: $grayTextColor;
    }


    .statistics_col {
        display: flex;
        flex-direction: column;
        background-color: $grayBgColor;
        border: 1px solid $grayBorderColor;
        padding: 0;

        .statistics_header {
            font-size: 22px;
            padding: 15px 20px 25px;
        }

        .statistics_headers_row {
            font-size: 20px;
            padding: 0 20px 15px;
        }

        .statistics_headers_row .col:not(:first-child),
        .data_row .col:not(:first-child) {
            text-align: center;
            flex: 0 0 auto;
        }

        .statistics_headers_row .col:nth-child(2),
        .data_row .col:nth-child(2) {
            width: 100px;
            max-width: 100px;
        }

        .statistics_headers_row .col:last-child,
        .data_row .col:last-child:not(:first-child) {
            width: 170px;
            max-width: 170px;
        }

        .statistics_footer {
            text-align: center;
            font-size: 20px;
            display: flex;
            flex: 1 1 auto;
            justify-content: center;
            align-items: center;

            .footer_link {
                color: $orangeColor;
            }

            div {
                width: 100%;
            }
        }

        .data_row, .empty_row, .statistics_headers_row {
            flex-wrap: nowrap;
            padding: 0 20px;
            margin: 0;

            .col {
                padding: 15px 0;
            }
        }

        .data_row.inactive * {
            color: rgb(200, 200, 200);
        }

        .data_row:nth-child(even), .empty_row {
            background-color: #fff;
        }
    }

    .results {
        padding: 20px;

        * {
            font-size: 20px;
        }

        .row {
            text-align: center;
            margin: 0;

            .col {
                background-color: #fff;
                height: 150px;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-direction: column;

                .results_value {
                    font-size: 30px;
                }
            }
        }

        .chart_row .col {
            height: 200px;
        }

        .chart_row .col:last-child {
            align-items: flex-start;
            padding-left: 10px;
        }

        .chart_totals {
            text-align: left;
            display: flex;
            align-items: flex-start;

            .totals_circle {
                margin-right: 5px;
                width: 20px;
                height: 20px;
                -webkit-border-radius: 50%;
                -moz-border-radius: 50%;
                border-radius: 50%;
            }
        }

        .row:not(.chart_row, :last-child) .col:first-child {
            margin-right: 10px;
        }

        .row:not(:last-child) .col {
            margin-bottom: 10px;
        }
    }

    .statistics_col,
    .statistics_col .row .col {
        min-width: 0;
    }
}

.three_dots_overflow {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

@media screen and (max-width: 1750px) {
    #tabs_statistics {
        grid:
            [row1-start] "results companies" [row1-end]
            [row2-start] "conditions conditions" [row2-end]
            / 450px 1fr;
        gap: 20px;
    }
}

@media screen and (max-width: 1300px) {
    #tabs_statistics {
        overflow: auto;

        grid:
            [row1-start] "results" [row1-end]
            [row2-start] "companies" [row2-end]
            [row3-start] "conditions" [row3-end]
            / minmax(400px, 1fr);
        gap: 20px;
    }
}

</style>
