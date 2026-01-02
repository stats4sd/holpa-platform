<template>
    <div id="map" class="public-map h-full">
    </div>
</template>

<script setup>
import "leaflet/dist/leaflet.css"
import * as L from 'leaflet';
import 'leaflet.markercluster/dist/MarkerCluster.css';
import 'leaflet.markercluster/dist/MarkerCluster.Default.css';
import 'leaflet.markercluster';

import {onMounted, ref, watch} from "vue";
import 'leaflet.markercluster.freezable';

const initialMap = ref(null)

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

watch(() => props.filteredResults, () => {
    console.log('Filtered results changed, updating map markers...');
    updateMapMarkers();
})


const updateMapMarkers = function () {

    props.allCountries.forEach(country => country.markerClusterGroup.clearLayers());


    let uniqueCountryIds = [];

    if (props.selectedCountry.hasOwnProperty('value')) {
        uniqueCountryIds.push(props.selectedCountry.value);
    }

    // if no countries are seleted, show all countries
    if (uniqueCountryIds.length === 0) {
        uniqueCountryIds = props.allCountries.map(country => country.value);
    }

    console.log(uniqueCountryIds);
    console.log(props.selectedCountry);
    console.log(props.allCountries);


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

    console.log('should emit map markers loaded');
    emit('loadComplete');

}


onMounted(() => {

    // Initialize the map when the component is mounted
    initialMap.value = L.map('map').setView([5.4, 19.3], 2);

    const temp = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'

    L.tileLayer(temp, {
        maxZoom: 18,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(initialMap.value);

    initialMap.value.on('zoomend', function () {
        console.log('Map zoomed');
        console.log(initialMap.value.getZoom());

        const currentZoom = initialMap.value.getZoom();

        if (currentZoom >= 8) {
            initialMap.value.eachLayer(function (layer) {
                if (layer instanceof L.MarkerClusterGroup) {
                    layer.freezeAtZoom(8);
                }
            });
        } else {
            initialMap.value.eachLayer(function (layer) {
                if (layer instanceof L.MarkerClusterGroup) {
                    layer.unfreeze();
                }
            });
        }

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


    console.log('Initial map setup complete, updating map markers...');
    updateMapMarkers();

})

</script>
