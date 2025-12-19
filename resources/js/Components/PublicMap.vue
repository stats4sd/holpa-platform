<template>

    <div class="w-full">

        <h2 class="mb-8 text-3xl font-bold">Previous implementations</h2>

        <div class="grid grid-cols-12 mb-8 gap-4">

            <div class="col-span-6 lg:col-span-4 xl:col-span-3">
                <div class="border border-green-700 p-4">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">
                        Showing {{ selectedCountry.value ?? 'All Countries' }}
                    </h3>
                    <VueSelect
                        v-model="selectedCountry"
                        :is-multi="true"
                        :options="allCountries"
                        placeholder="Select country"
                        class="menu"
                    />
                </div>
            </div>
            <div class="col-span-6 lg:col-span-8 xl:col-span-9">
                <h4 class="ps-4">Summary</h4>
                <p class="ps-4">Total Surveys Conducted: {{ filteredResults.length }}</p>

                <p class="ps-4">Female-headed farm households: {{ filteredResults.filter(result => result.gender === 'Female').length }}</p>
                <p class="ps-4">Male-headed farm households: {{ filteredResults.filter(result => result.gender === 'Male').length }}</p>
            </div>
        </div>

        <div class="border border-blue-500 rounded-lg p-4 mb-8 grid grid-cols-3">
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
                <AeChartsComponent
                    :allCountries="allCountries"
                    :selectedCountry="selectedCountry"
                    :filteredResults="deferredFilteredResults"
                    @load-complete="chartsLoadComplete"
                />
            </div>
        </div>
        <div class="mx-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index:1000;">
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
import AeChartsComponent from "./AeChartsComponent.vue";

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
const selectedCountry = ref({});


const mapLoading = ref(true);
const chartLoading = ref(true);
const loadingState = computed(() => mapLoading.value || chartLoading.value);

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
