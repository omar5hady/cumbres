
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.$ = window.jQuery = require('jquery');
window.Vue = require('vue');


import VueCurrencyFilter from 'vue-currency-filter'
import Axios from 'axios';
import Echo from 'laravel-echo';

Vue.use(VueCurrencyFilter, {
  symbol: '$', // El símbolo, por ejemplo €
  thousandsSeparator: ',', // Separador de miles
  fractionCount: 2, // ¿Cuántos decimales mostrar?
  fractionSeparator: '.', // Separador de decimales
  symbolPosition: 'front', // Posición del símbolo. Puede ser al inicio ('front') o al final ('') es decir, si queremos que sea al final, en lugar de front ponemos una cadena vacía ''
  symbolSpacing: true // Indica si debe poner un espacio entre el símbolo y la cantidad
})


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
Vue.component('asignar-modelo', require('./components/AsignarModelo.vue'));
Vue.component('licencias', require('./components/Licencias.vue'));
Vue.component('actadeterminacion', require('./components/ActaDeTerminacion.vue'));
Vue.component('lote-disponible', require('./components/LotesDisp.vue'));
Vue.component('precios-vivienda', require('./components/PreciosVivienda.vue'));

Vue.component('agregar-sobreprecios', require('./components/SobrepreciosAdd.vue'));
Vue.component('precio-etapa', require('./components/PrecioEtapa.vue'));
Vue.component('sobreprecios', require('./components/Sobreprecio.vue'));
Vue.component('paquetes', require('./components/Paquete.vue'));
Vue.component('promociones', require('./components/Promocion.vue'));
Vue.component('asignar-servicio', require('./components/AsignarServicio.vue'));

Vue.component('contratistas', require('./components/Contratista.vue'));
Vue.component('iniobra', require('./components/IniObra.vue'));
Vue.component('aviso-obra', require('./components/AvisoObra.vue'));
Vue.component('partidas', require('./components/Partidas.vue'));
Vue.component('avance', require('./components/Avance.vue'));

Vue.component('rol', require('./components/Rol.vue'));
Vue.component('usuario', require('./components/Usuarios.vue'));
Vue.component('medio-publicitario', require('./components/MedioPublicitario.vue'));
Vue.component('servicio', require('./components/Servicio.vue'));
Vue.component('lugar-contacto', require('./components/LugarContacto.vue'));
Vue.component('prospectos', require('./components/Prospectos.vue'));
Vue.component('institucion-financiamiento', require('./components/InstitucionFinanciamiento.vue'));
Vue.component('tipo-credito', require('./components/TipoCredito.vue'));
Vue.component('simulacion', require('./components/SimulacionDeCredito.vue'));
Vue.component('historialsim', require('./components/HistorialSimulacion.vue'));
Vue.component('historialcreditos', require('./components/HistorialDeCreditos.vue'));
Vue.component('asesores', require('./components/Asesor.vue'));

Vue.component('notification', require('./components/Notification.vue'));
Vue.component('perfil-user', require('./components/Perfil.vue'));
Vue.component('listar-notifications', require('./components/ListarNotifications.vue'));

Vue.component('datos-extra', require('./components/EstaditicaDatosExtra.vue'));
Vue.component('crear-contrato', require('./components/Contrato.vue'));

Vue.component('publicidad-fraccionamiento', require('./components/PublicidadFraccionamiento.vue'));
Vue.component('publicidad-etapa', require('./components/PublicidadEtapa.vue'));

const app = new Vue({
    el: '#app',
    data :{
        menu:0,
        notifications:[]
    },
    created(){
      let me = this;
      axios.post('notification/get').then(function(response){
        // console.log(response.data);
        me.notifications=response.data;
      }).catch(function(error){
        console.log(error);
      });

      var userId = $('meta[name="userId"]').attr('content');

      window.Echo.private('App.User.'+userId).listen(('PackageNotification'),(e) => {
        me.notifications.unshift(notification);
      });

    }
});
