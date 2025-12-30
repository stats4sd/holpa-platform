<template>

    <!-- A temporary overlay that covers the whole page content, greying out the content behind it and explaining that the dashboard will be available shortly -->
    <div v-if="showOverlay" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center" style="z-index:50000">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-md text-center">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Dashboard Coming Soon</h3>
            <p class="text-gray-600 mb-4">We are currently validating the agroecology scores results. The dashboard will be available here when that is complete. Please check back in a few days.</p>
            <p class="text-gray-600 text-xs">Last updated 2025-12-19</p>
        </div>
    </div>

    <div class="w-full max-w-7xl mx-auto">

        <h2 class="mb-8 text-3xl font-bold">Previous implementations</h2>


        <!-- SUMMARY -->
        <div class="p-4 grid grid-cols-12">

            <div class="col-span-12 md:col-span-5">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center space-x-8">
                    <span>Showing</span>
                    <VueSelect
                        v-model="selectedCountryId"
                        :is-multi="false"
                        :options="allCountries"
                        placeholder="All Countries"
                        class="menu"
                    />
                </h3>
            </div>
            <div class="col-span-12 md:col-span-5 md:col-start-7 grid grid-cols-6">
                <div class="col-span-6 md:col-span-3">
                    <h1 class="font-bold">{{ filteredResults.length }}</h1>
                    <h5>Farms Surveyed</h5>
                </div>
            </div>
        </div>


        <!-- FILTERS -->
        <div class="p-4 mb-8 grid grid-cols-3">
            <div class="col-span-1 space-y-2">

                <VueSelect
                    v-model="selectedGender"
                    :is-multi="false"
                    :options="[
                        { label: 'Female-headed farm households', value: 'Female' },
                        { label: 'Male-headed farm households', value: 'Male' },
                    ]"
                    placeholder="Filter by gender"
                />
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-8">

            <div class="col-span-1">
                <MapComponent
                    :allCountries="allCountries"
                    :selectedCountry="selectedCountry"
                    :filteredResults="deferredFilteredResults"
                    @load-complete="mapLoadComplete"
                />
            </div>

            <div class="col-span-1 p-4 border border-blue-500 rounded-lg">
                <h4 class="mb-4">Agroecology Scores</h4>
                <CountryComparisonChartsComponent
                    :allCountries="allCountries"
                    :selectedCountry="selectedCountry"
                    :filteredResults="deferredFilteredResults"
                    @load-complete="chartsLoadComplete"
                />
            </div>
        </div>
        <div class="absolute w-full h-[70vh] top-24 left-0 bg-gray-200 opacity-80" v-if="loadingState" style="z-index:99999;"></div>
        <div class="mx-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index:100000;">
            <pulse-loader :loading="loadingState" :size="'100px'"/>
        </div>
    </div>
</template>

<script setup>
import {computed, nextTick, onMounted, ref, watch} from 'vue';
import axios from 'axios';
import PulseLoader from 'vue-spinner/src/PulseLoader.vue'
import VueSelect from "vue3-select-component";
import "vue3-select-component/styles";
import MapComponent from "./MapComponent.vue";
import CountryComparisonChartsComponent from "./CountryComparisonChartsComponent.vue";

const showOverlay = ref(false); // Set to false to hide the overlay

const allCountries = ref([
    {
        label: 'Burkina Faso',
        value: '854'
    },
    {
        label: 'India',
        value: '356'
    },
    {
        label: 'Kenya',
        value: '404'
    },
    {
        label: 'Laos',
        value: '418'
    },
    {
        label: 'Peru',
        value: '604'
    },
    {
        label: 'Senegal',
        value: '686'
    },
    {
        label: 'Tunisia',
        value: '788'
    },
    {
        label: 'Zimbabwe',
        value: '716'
    },
]);
const selectedCountryId = ref(null);
const selectedCountry = computed(() => {
    return allCountries.value.find(country => country.value === selectedCountryId.value) || {};
});

const mapLoading = ref(true);
const chartLoading = ref(true);
const loadingState = computed(() => mapLoading.value || chartLoading.value);

// temp:


const mapLoadComplete = function () {
    console.log('Map loading complete');
    mapLoading.value = false;
}

const chartsLoadComplete = function () {
    console.log('Charts loading complete');
    chartLoading.value = false;
}


const allResults = ref([]);

const selectedGender = ref(null);

const filteredResults = computed(() => {

    console.log('updating filtered results...');

    let newResults = allResults.value;

    if (selectedCountry.hasOwnProperty('value')) {
        newResults = newResults.filter(result =>
            selectedCountry.value === result.country_id.toString()
        );
    }

    if (selectedGender.value) {
        newResults = newResults.filter(result => result.gender === selectedGender.value);
    }

    // TODO: add farm size
    return newResults;
}, {deep: true});


const deferredFilteredResults = ref([]);

// Watch the filters and set loading state before the computed updates
watch([selectedCountry, selectedGender], async () => {

    console.log('watcha!')
    mapLoading.value = true;
    chartLoading.value = true;

    await nextTick();
    setTimeout(() => {
        deferredFilteredResults.value = filteredResults.value;
    }, 50);


}, {deep: true});

// You can add any necessary imports or setup logic here
onMounted(() => {

    // get TempResults data
    axios.get('/temp-results')
        .then(response => {
            console.log('Fetched TempResults:', response.data);
            allResults.value = response.data;

            if (deferredFilteredResults.value.length === 0) {
                deferredFilteredResults.value = allResults.value;
            }
        })
        .catch(error => {
            console.error('Error fetching TempResults:', error);
        })


});

</script>

<style>
.menu {
    z-index: 2000;
}
</style>
