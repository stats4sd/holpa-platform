<template>
    <div id="map" class="public-map">
    </div>
</template>

<script setup>

import {onMounted, ref, watch} from "vue";

const initialMap = ref(null)
const loadingState = defineModel('loadingState', {
    type: Boolean,
    required: true
})

const props = defineProps({
    allCountries: {
        type: Array,
        required: true
    },
    selectedCountries: {
        type: Array,
        required: true
    },
    filteredResults: {
        type: Array,
        required: true
    }
})

watch(() => props.filteredResults, () => {
    console.log('Filtered results changed, updating map markers...');
    updateMapMarkers();
})


const updateMapMarkers = function () {

    loadingState.value = true;
    props.allCountries.forEach(country => country.markerClusterGroup.clearLayers());


    let uniqueCountryIds = props.selectedCountries.map(country => country);

    // if no countries are seleted, show all countries
    if (uniqueCountryIds.length === 0) {
        uniqueCountryIds = props.allCountries.map(country => country.value);
    }


    uniqueCountryIds.forEach(countryId => {

        const results = props.filteredResults.filter(result => result.country_id === countryId.toString());

        results.forEach(result => {
            if (result.latitude && result.longitude) {
                const marker = L.marker([result.latitude, result.longitude]);
                props.allCountries.find(country => country.value === countryId.toString())
                    .markerClusterGroup
                    .addLayer(marker);
            }
        });
    })
    loadingState.value = false;
}


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

    // set up country layers
    props.allCountries.forEach((country) => {
        country.markerClusterGroup = L.markerClusterGroup().addTo(initialMap.value);
    })

})

</script>
