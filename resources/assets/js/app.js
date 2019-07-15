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

//Componentes Administracion
Vue.component('departamento', require('./components/Administracion/Departamento.vue'));
Vue.component('cuenta', require('./components/Administracion/Cuenta.vue'));
Vue.component('personal', require('./components/Administracion/personal.vue'));
Vue.component('empresa', require('./components/Administracion/Empresa.vue'));
Vue.component('asignar-servicio', require('./components/Administracion/AsignarServicio.vue'));
Vue.component('medio-publicitario', require('./components/Administracion/MedioPublicitario.vue'));
Vue.component('servicio', require('./components/Administracion/Servicio.vue'));
Vue.component('lugar-contacto', require('./components/Administracion/LugarContacto.vue'));
Vue.component('institucion-financiamiento', require('./components/Administracion/InstitucionFinanciamiento.vue'));
Vue.component('tipo-credito', require('./components/Administracion/TipoCredito.vue'));
Vue.component('asesores', require('./components/Administracion/Asesor.vue'));
Vue.component('notarias', require('./components/Administracion/Notaria.vue'));

//Componentes Desarrollo
Vue.component('fraccionamiento', require('./components/Desarrollo/Fraccionamiento.vue'));
Vue.component('etapa', require('./components/Desarrollo/Etapa.vue'));
Vue.component('modelo', require('./components/Desarrollo/Modelo.vue'));
Vue.component('lote', require('./components/Desarrollo/Lote.vue'));
Vue.component('asignar-modelo', require('./components/Desarrollo/AsignarModelo.vue'));
Vue.component('licencias', require('./components/Desarrollo/Licencias.vue'));
Vue.component('actadeterminacion', require('./components/Desarrollo/ActaDeTerminacion.vue'));
Vue.component('publicidad-fraccionamiento', require('./components/Desarrollo/PublicidadFraccionamiento.vue'));
Vue.component('publicidad-etapa', require('./components/Desarrollo/PublicidadEtapa.vue'));

//Componentes Precios
Vue.component('precios-vivienda', require('./components/Precios/PreciosVivienda.vue'));
Vue.component('agregar-sobreprecios', require('./components/Precios/SobrepreciosAdd.vue'));
Vue.component('precio-etapa', require('./components/Precios/PrecioEtapa.vue'));
Vue.component('sobreprecios', require('./components/Precios/Sobreprecio.vue'));
Vue.component('paquetes', require('./components/Precios/Paquete.vue'));
Vue.component('promociones', require('./components/Precios/Promocion.vue'));

//Componentes Obra
Vue.component('contratistas', require('./components/Obra/Contratista.vue'));
Vue.component('iniobra', require('./components/Obra/IniObra.vue'));
Vue.component('aviso-obra', require('./components/Obra/AvisoObra.vue'));
Vue.component('partidas', require('./components/Obra/Partidas.vue'));
Vue.component('avance', require('./components/Obra/Avance.vue'));
Vue.component('visita-avaluo', require('./components/Obra/VisitaAvaluo.vue'));

//Componentes Ventas
Vue.component('lote-disponible', require('./components/Ventas/LotesDisp.vue'));
Vue.component('prospectos', require('./components/Ventas/Prospectos.vue'));
Vue.component('simulacion', require('./components/Ventas/SimulacionDeCredito.vue'));
Vue.component('historialsim', require('./components/Ventas/HistorialSimulacion.vue'));
Vue.component('historialcreditos', require('./components/Ventas/HistorialDeCreditos.vue'));
Vue.component('crear-contrato', require('./components/Ventas/Contrato.vue'));

//Componentes Acceso
Vue.component('rol', require('./components/Rol.vue'));
Vue.component('usuario', require('./components/Usuarios.vue'));

//Componentes gestoria
Vue.component('depositos', require('./components/Deposito.vue'));
Vue.component('expediente', require('./components/IntegracionExp.vue'));
Vue.component('asignargestor', require('./components/AsignarGestor.vue'));
Vue.component('seguimiento-tramite', require('./components/SeguimientoTramite.vue'));

Vue.component('notification', require('./components/Notification.vue'));
Vue.component('perfil-user', require('./components/Perfil.vue'));
Vue.component('listar-notifications', require('./components/ListarNotifications.vue'));
Vue.component('datos-extra', require('./components/EstaditicaDatosExtra.vue'));
Vue.component('avaluos', require('./components/Avaluos.vue'));
Vue.component('gastos-admin', require('./components/GastosAdministrativos.vue'));
Vue.component('estado-cuenta', require('./components/EstadoCuenta.vue'));

const app = new Vue({
    el: '#app',
    data: {
        menu: 100,
        notifications: []
    },
    created() {
        let me = this;
        axios.post('notification/get').then(function(response) {
            // console.log(response.data);
            me.notifications = response.data;
        }).catch(function(error) {
            console.log(error);
        });

        var userId = $('meta[name="userId"]').attr('content');

        window.Echo.private('App.User.' + userId).listen(('PackageNotification'), (e) => {
            me.notifications.unshift(notification);
        });

    }
});