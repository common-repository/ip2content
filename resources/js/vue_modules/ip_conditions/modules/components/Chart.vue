<script>
import { Doughnut } from "vue-chartjs/legacy";
import { Chart as ChartJS, ArcElement, LinearScale } from 'chart.js';

ChartJS.register(ArcElement, LinearScale);
export default {
    name: "Chart",
    components: { Doughnut },
    props: {
        chartData: {
            type: Object,
            required: true,
        },
        chartOptions: {
            type: Object,
            default: () => {},
        },
        datasetIdKey: {
            type: String,
            default: "",
        },
        chartId: {
            type: String,
            default: "",
        },
        width: {
            type: Number,
            default: null,
        },
        height: {
            type: Number,
            default: null,
        },
        cssClasses: {
            type: String,
            default: "",
        },
        styles: {
            type: Object,
            default: () => {},
        },
        plugins: {
            type: Array,
            default: () => [],
        },
    },
};
</script>

<template>
    <div class="pa-3">
        <Doughnut
            :chart-options="chartOptions"
            :chart-data="chartData"
            :chart-id="chartId"
            :dataset-id-key="datasetIdKey"
            :plugins="plugins"
            :css-classes="cssClasses"
            :styles="styles"
            :width="width"
            :height="height"
        />
    </div>
</template>
