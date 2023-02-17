/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue');

import Axios from 'axios';




// Premios Ruleta 
Vue.component('premios-ruleta', require('./components/Ruleta/PremioCliente.vue'));
Vue.component('invalid-url', require('./components/Ruleta/InvalidURL.vue'));
Vue.component('ruleta-giratoria', require('./components/Ruleta/RuletaGira.vue'));




const app = new Vue({
    el: '#helper',
    
});
