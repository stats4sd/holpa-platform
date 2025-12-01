<template>
    <div class="public-map">
        <h2 class="mb-8 text-3xl font-bold">Previous implementations</h2>
        <div class="border border-blue-500 rounded-lg p-8 mb-8">
            <p class="mb-4">
                This is a public map showcasing previous implementations. Users can explore various locations and view details about each implementation.
            </p>
            <ul class="list-disc list-inside">
                <li>Implementation 1: Description of the first implementation.</li>
                <li>Implementation 2: Description of the second implementation.</li>
                <li>Implementation 3: Description of the third implementation.</li>
            </ul>
        </div>

        <div id="map" style="height: 60vh; width: 100%; background-color: #e0e0e0;">

            <div class="mx-auto" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                <pulse-loader :loading="loadingState" :size="'100px'"/>
            </div>
        </div>
    </div>
</template>

<script setup>
import "leaflet/dist/leaflet.css"
import * as L from 'leaflet';
import {ref, onMounted} from 'vue';
import axios from 'axios';
import PulseLoader from 'vue-spinner/src/PulseLoader.vue'
import 'leaflet.markercluster/dist/MarkerCluster.css';
import 'leaflet.markercluster/dist/MarkerCluster.Default.css';
import 'leaflet.markercluster';

const initialMap = ref(null)
const loadingState = ref(false);


// You can add any necessary imports or setup logic here
onMounted(() => {
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

    // get country list


    loadingState.value = true;

    // get TempResults data
    axios.get('/temp-results')
        .then(response => {
            const tempResults = response.data;

            // results are grouped by country
            console.log(tempResults);

            Object.keys(tempResults).forEach(country_id => {

                const countryMarkers = L.markerClusterGroup().addTo(initialMap.value);

                const results = tempResults[country_id];
                results.forEach(result => {
                    const marker = L.marker([result.latitude, result.longitude]);
                    marker.bindPopup(`<b>${result.name}</b><br>${result.description}`);
                    countryMarkers.addLayer(marker);
                });

            });
        })
        .catch(error => {
            console.error('Error fetching TempResults:', error);
        })
        .finally(() => {
            loadingState.value = false;
        });

});

</script>
