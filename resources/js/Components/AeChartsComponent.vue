<template>
    <VueSelect
        v-model="selectedPrinciple"
        :options="principles"
        placeholder="Select principle to show"
        class="mb-4 w-full"
        :clearable="false"
    ></VueSelect>

    <div v-for="principle in principles" :key="principle.value">
        <div v-if="barChartData[principle.value]" v-show="selectedPrinciple === principle.value" class="chart-wrapper">
            <Bar
                :key="'bar_'+principle.value"
                :data="barChartData[principle.value]"
                :options="barChartOptions"
            />

        </div>
    </div>
    <div class="violinChart">
        <canvas :id="'violin_principle'" :ref="'violin_principle'"></canvas>
    </div>

</template>

<script setup>

import {onMounted, ref, useTemplateRef, watch} from "vue";
import {Bar} from 'vue-chartjs'
import {Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, BarController} from 'chart.js';
import VueSelect from "vue3-select-component";
import * as ss from 'simple-statistics'
import {ViolinChart} from "@sgratzl/chartjs-chart-boxplot";

ChartJS.register(CategoryScale, LinearScale, BarElement, BarController, Title, Tooltip, Legend, ViolinChart);


const props = defineProps({
    allCountries: {
        type: Array,
        required: true
    },
    selectedCountry: {
        type: Object,
        required: true
    },
    filteredResults: {
        type: Array,
        required: true
    },
})

const emit = defineEmits([
    'loadComplete'
])

const principles = ref([
    {
        label: 'Overall AE',
        value: 'ae',
    },
    {
        label: 'Recycling',
        value: 'recycling',
    },
    {
        label: 'Input Reduction',
        value: 'input_reduction',
    },
    {
        label: 'Soil Health',
        value: 'soil_health',
    },
    {
        label: 'Animal Health',
        value: 'animal_health',
    },
    {
        label: 'Biodiversity',
        value: 'biodiversity',
    },
    {
        label: 'Synergy',
        value: 'synergy',
    },
    {
        label: 'Economic Diversification',
        value: 'economic_diversification',
    },
    {
        label: 'Co-creation of Knowledge',
        value: 'co_creation_knowledge',
    },
    {
        label: 'Governance',
        value: 'governance',
    },
    {
        label: 'Social Values and Diet',
        value: 'social_values_diet',
    },
    {
        label: 'Fairness',
        value: 'fairness',
    },
    {
        label: 'Connectivity',
        value: 'connectivity',
    },
    {
        label: 'Participation',
        value: 'participation',
    },
])

const selectedPrinciple = ref('ae')


// returns the filtered dataset in a format ready for ChartJS charts
const prepareChartData = function (principle_value) {

    const principle_overall_variable = 'overall_' + principle_value.toLowerCase().replace(/ /g, '_') + '_score';

    return props.allCountries.map(country => {

        let filteredData = props.filteredResults.filter(result => result.country_id === country.value);
        if (filteredData.length === 0) {
            return [];
        }
        return filteredData
            .map(result => {
                return result[principle_overall_variable]
            })
    })
}

const prepareBarChartData = function (principle_value) {
    return prepareChartData(principle_value).map(data => {
        return ss.mean(data)
    });
}


watch(() => props.filteredResults, () => {
        console.log('Filtered results changed, updating charts...');

        console.log(props.filteredResults);

        if (props.filteredResults.length === 0) {
            console.log('No results found');
            return;
        }

        updateCharts();

    }, {deep: true}
)

// BarChartData will contain 14 sets of data, one per principle + one for 'all'
const barChartData = ref({})
const violinChartData = ref({})

const barChartOptions = ref({
    maintainAspectRatio: true,
    scales: {
        y: {
            beginAtZero: true,
            max: 5,
        }
    }
})

const violinCanvas = useTemplateRef('violin_principle');
const violinPlot = ref(null);

watch(selectedPrinciple, () => {
    console.log('Selected principle changed to ' + selectedPrinciple.value);

    reRenderViolins();

})

onMounted(() => {

})


const updateCharts = function () {

    // scores per principle per country;

    principles.value.forEach((value, key) => {

        const principle = value.value

        console.log(principle);

        // chart data per country
        principles.value.forEach(principle => {
            barChartData.value[principle.value] = {
                labels: props.allCountries.map(country => country.label) ?? ["1", "2", "3"],
                datasets: [{
                    label: principle.value + ' Score',
                    data: prepareBarChartData(principle.value)
                }]
            }

            violinChartData.value[principle.value] = {
                labels: props.allCountries.map(country => country.label) ?? ["1", "2", "3"],
                datasets: [{
                    label: principle.value + ' Score',
                    data: prepareChartData(principle.value)
                }],
            }
        })

    })


    reRenderViolins();

    console.log(violinPlot.value);

    console.log('should emit charts loaded');
    emit('loadComplete')


}


const reRenderViolins = function () {
        // if a violin plot exists, destory it
    if (violinPlot.value) {
        violinPlot.value.destroy();
    }

    console.log(violinChartData.value[selectedPrinciple.value])

    violinPlot.value = new ViolinChart(violinCanvas.value, {
        type: 'violin',
        data: violinChartData.value[selectedPrinciple.value],
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Violin Plot for ' + selectedPrinciple.value
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 5,
                }
            }
        }
    });
}

</script>
