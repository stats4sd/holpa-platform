<template>
    <div class="public-map">
        <h2 class="mb-8 text-3xl font-bold">Previous implementations</h2>
        <div class="border border-blue-500 rounded-lg p-4 mb-8 space-y-4">
            <p class="">
                Placeholder text describing the previous implementations shown on the map below. This text can provide context and information about what users are seeing.
            </p>
        </div>

        <div class="border border-blue-500 rounded-lg p-4 mb-8 grid grid-cols-3">
            <div class="col-span-1 space-y-2">

                <VueSelect
                    v-model="selectedCountries"
                    :is-multi="true"
                    :options="allCountries"
                    placeholder="Filter by country"
                />
                <VueSelect
                    v-model="selectedGender"
                    :is-multi="false"
                    :options="[
                        { label: 'Female-headed farm households', value: 'Female' },
                        { label: 'Male-headed farm households', value: 'Male' },
                    ]"
                    placeholder="Filter by gender"
                />
                <VueSelect
                    v-model="selectedEducationLevel"
                    :is-multi="false"
                    :options="[
                        { label: 'No formal education', value: 'None' },
                        { label: 'Primary education', value: 'Primary' },
                        { label: 'Secondary education', value: 'Secondary' },
                        { label: 'Tertiary education', value: 'Tertiary' },
                    ]"
                    placeholder="Filter by education level"
                />
            </div>
            <div class="col-span-2 ps-4">
                <h4 class="ps-4">Summary</h4>
                <p class="ps-4">Total Surveys Conducted: {{ filteredResults.length }}</p>

                <p class="ps-4">Female-headed farm households: {{ filteredResults.filter(result => result.gender === 'Female').length }}</p>
                <p class="ps-4">Male-headed farm households: {{ filteredResults.filter(result => result.gender === 'Male').length }}</p>

            </div>
        </div>

        <div id="map" style="height: 60vh; width: 100%; background-color: #e0e0e0;">

        </div>
        <div v-if="loadingState" class="mx-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index:1000;">
            <pulse-loader :loading="loadingState" :size="'100px'"/>
        </div>
    </div>
</template>

<script setup>
import "leaflet/dist/leaflet.css"
import * as L from 'leaflet';
import {onMounted, ref, watch} from 'vue';
import axios from 'axios';
import PulseLoader from 'vue-spinner/src/PulseLoader.vue'
import 'leaflet.markercluster/dist/MarkerCluster.css';
import 'leaflet.markercluster/dist/MarkerCluster.Default.css';
import 'leaflet.markercluster';
import VueSelect from "vue3-select-component";
import "vue3-select-component/styles";

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
const selectedCountries = ref([]);


const initialMap = ref(null)
const loadingState = ref(true);
const allresults = ref([]);
const filteredResults = ref([]);

const selectCountries = function () {

    console.log(allCountries.value);
    console.log(selectedCountries.value.length);

    if (selectedCountries.value.length === 0) {
        filteredResults.value = allresults.value;
    } else {
        filteredResults.value = allresults.value.filter(result =>
            selectedCountries.value.includes(result.country_id.toString())
        );
    }

    console.log(filteredResults.value.length);
}

const selectedGender = ref(null);
const selectedEducationLevel = ref(null);

watch(selectedCountries, () => {
    loadingState.value = true;
    selectCountries();

    if (selectedGender.value) {
        filteredResults.value = allresults.value.filter(result => result.gender === selectedGender.value);
    }

    if (selectedEducationLevel.value) {
        filteredResults.value = filteredResults.value.filter(result => result.education_level === selectedEducationLevel.value);
    }


    updateMapMarkers();
}, {deep: true});


watch(selectedGender, () => {
    loadingState.value = true;
    selectCountries();
    if (selectedGender.value) {
        filteredResults.value = allresults.value.filter(result => result.gender === selectedGender.value);
    }

    if (selectedEducationLevel.value) {
        filteredResults.value = filteredResults.value.filter(result => result.education_level === selectedEducationLevel.value);
    }

    updateMapMarkers();
})

watch(selectedEducationLevel, () => {
    loadingState.value = true;
    selectCountries();

    if (selectedGender.value) {
        filteredResults.value = allresults.value.filter(result => result.gender === selectedGender.value);
    }


    if (selectedEducationLevel.value) {
        filteredResults.value = filteredResults.value.filter(result => result.education_level === selectedEducationLevel.value);
    }
    updateMapMarkers();
})

const updateMapMarkers = function () {

    allCountries.value.forEach(country => country.markerClusterGroup.clearLayers());


    let uniqueCountryIds = selectedCountries.value.map(country => country);

    // if no countries are seleted, show all countries
    if (uniqueCountryIds.length === 0) {
        uniqueCountryIds = allCountries.value.map(country => country.value);
    }


    uniqueCountryIds.forEach(countryId => {

        const results = filteredResults.value.filter(result => result.country_id === countryId.toString());

        results.forEach(result => {
            if (result.latitude && result.longitude) {
                const marker = L.marker([result.latitude, result.longitude]);
                allCountries.value.find(country => country.value === countryId.toString())
                    .markerClusterGroup
                    .addLayer(marker);
            }
        });
    })
    loadingState.value = false;
}


// You can add any necessary imports or setup logic here
onMounted(() => {
    loadingState.value = true;
    // Initialize the map when the component is mounted
    initialMap.value = L.map('map').setView([5.4, 19.3], 3);

    const temp = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'

    L.tileLayer(temp, {
        maxZoom: 18,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(initialMap.value);

    initialMap.value.on('zoomend', function () {
        console.log('Map zoomed');
        console.log(initialMap.value.getZoom());
    });

    initialMap.value.on('moveend', function () {
        console.log('Map panned');
        console.log(initialMap.value.getCenter());
    });

    initialMap.value.on('click', function (e) {
        console.log('Map clicked at ' + e.latlng);
    });

    // create marker cluster

    // set up country layers
    allCountries.value.forEach((country) => {
        country.markerClusterGroup = L.markerClusterGroup().addTo(initialMap.value);
    })


    // get TempResults data
    axios.get('/temp-results')
        .then(response => {
            const tempResults = response.data;

            // results are grouped by country
            console.log(tempResults);

            tempResults.forEach(result => {
                allresults.value.push(result);
            });
        })
        .catch(error => {
            console.error('Error fetching TempResults:', error);
        })
        .finally(() => {
            filteredResults.value = allresults.value;
            updateMapMarkers();
        });

});

</script>

<style scoped>
.menu {
    z-index: 2000;
}
</style>
