console.log('public map js loaded');

// create Vue app and mount to #public-map-app

import { createApp } from 'vue';
import PublicMap from './components/PublicMap.vue';

createApp({})
    .component('publicMap', PublicMap)
    .mount('#public-map-app');
