
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('departamento', require('./components/Departamento.vue'));
Vue.component('fraccionamiento', require('./components/Fraccionamiento.vue'));
Vue.component('personal', require('./components/personal.vue'));
Vue.component('empresa', require('./components/Empresa.vue'));
Vue.component('etapa', require('./components/Etapa.vue'));
Vue.component('modelo', require('./components/Modelo.vue'));
Vue.component('lote', require('./components/Lote.vue'));

const app = new Vue({
    el: '#app',
    data :{
        menu:0
    }
});
