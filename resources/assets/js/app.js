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

Vue.component('calendar-component', require('./components/Calendar/CalendarComponent.vue'));
Vue.component('notificacion-component', require('./components/Notificacion.vue'));

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
Vue.component('asesores-fracc', require('./components/Administracion/AsesoresFracc.vue'));
Vue.component('notarias', require('./components/Administracion/Notaria.vue'));
Vue.component('proveedores', require('./components/Administracion/Proveedores.vue'));
Vue.component('campanias', require('./components/Administracion/Campania.vue'));
Vue.component('vehiculos', require('./components/Administracion/Vehiculos.vue'));

//Componentes Desarrollo
Vue.component('fraccionamiento', require('./components/Desarrollo/Fraccionamiento.vue'));
Vue.component('etapa', require('./components/Desarrollo/Etapa.vue'));
Vue.component('modelo', require('./components/Desarrollo/Modelo.vue'));
Vue.component('lote', require('./components/Desarrollo/Lote.vue'));
Vue.component('asignar-modelo', require('./components/Desarrollo/AsignarModelo.vue'));
Vue.component('asignar-especificaciones', require('./components/Desarrollo/EspecificacionModelo.vue'));
Vue.component('licencias', require('./components/Desarrollo/Licencias.vue'));
Vue.component('lotes-ruv', require('./components/Desarrollo/LotesRuv.vue'));
Vue.component('seguimiento-ruv', require('./components/Desarrollo/Ruvs.vue'));
Vue.component('actadeterminacion', require('./components/Desarrollo/ActaDeTerminacion.vue'));
Vue.component('publicidad-etapa', require('./components/Desarrollo/PublicidadEtapa.vue'));
Vue.component('publicidad-fraccionamiento', require('./components/Desarrollo/PublicidadFraccionamiento.vue'));

Vue.component('prediales-descarga', require('./components/Desarrollo/DescargaActas.vue'));

    // Proyectos
    Vue.component('planos-proyectos', require('./components/Desarrollo/Proyectos/Planos.vue'));


//Componentes Precios
Vue.component('precios-vivienda', require('./components/Precios/PreciosVivienda.vue'));
Vue.component('agregar-sobreprecios', require('./components/Precios/SobrepreciosAdd.vue'));
Vue.component('precio-etapa', require('./components/Precios/PrecioEtapa.vue'));
Vue.component('sobreprecios', require('./components/Precios/Sobreprecio.vue'));
Vue.component('paquetes', require('./components/Precios/Paquete.vue'));
Vue.component('promociones', require('./components/Precios/Promocion.vue'));
Vue.component('cat-bonos', require('./components/Precios/CatalogoBono.vue'));
Vue.component('precios-terrenos', require('./components/Precios/PreciosTerrenos.vue'));

//Componentes Obra
Vue.component('contratistas', require('./components/Obra/Contratista.vue'));
Vue.component('iniobra', require('./components/Obra/IniObra.vue'));
Vue.component('aviso-obra', require('./components/Obra/AvisoObra.vue'));
Vue.component('partidas', require('./components/Obra/Partidas.vue'));
Vue.component('avance', require('./components/Obra/Avance.vue'));
Vue.component('visita-avaluo', require('./components/Obra/VisitaAvaluo.vue'));
Vue.component('solicitar-equipamiento', require('./components/Ventas/Equipamientos.vue'));
Vue.component('uri-equipamiento', require('./components/Obra/EquipamientosUri.vue'));
    //OBRA DEPARTAMENTOS
    Vue.component('aviso-dep', require('./components/Obra/Departamentos/AvisoObraDep.vue'));
    Vue.component('estimaciones-dep', require('./components/Obra/Departamentos/EstimacionesDep.vue'));
    Vue.component('partidas-dep', require('./components/Obra/Departamentos/PartidasDep.vue'));

//Componentes Ventas
Vue.component('lote-disponible', require('./components/Ventas/LotesDisp.vue'));
Vue.component('prospectos', require('./components/Ventas/Prospectos.vue'));
Vue.component('digital-leads', require('./components/Ventas/DigitalLead.vue'));
Vue.component('prospectos-publicidad', require('./components/Ventas/ProspectosPublicidad.vue'));
Vue.component('simulacion', require('./components/Ventas/SimulacionDeCredito.vue'));
Vue.component('historialsim', require('./components/Ventas/HistorialSimulacion.vue'));
Vue.component('historialcreditos', require('./components/Ventas/HistorialDeCreditos.vue'));
Vue.component('crear-contrato', require('./components/Ventas/Contrato.vue'));
Vue.component('docs', require('./components/Ventas/docs.vue'));
Vue.component('obra-equipamiento', require('./components/Obra/ObraEquipamientos.vue'));
Vue.component('obra-entrega', require('./components/Obra/EntregaPendiente.vue'));
Vue.component('prospectos-reasignados', require('./components/Ventas/ProspectosReasignar.vue'));
Vue.component('reubicacion-component', require('./components/Ventas/Reubicaciones.vue'));

Vue.component('equip-lotes', require('./components/Equipamiento/SolicEquipamiento.vue'));
Vue.component('equip-proveedor', require('./components/Equipamiento/EquipProveedor.vue'));
Vue.component('equip-obra', require('./components/Equipamiento/EquipObra.vue'));

//Componentes Rentas
Vue.component('admin-rentas', require('./components/Rentas/AdminRentas.vue'));
Vue.component('contrato-rentas', require('./components/Rentas/ContratoRentas.vue'));
Vue.component('pagos-rentas', require('./components/Rentas/PagosRentas.vue'));

//Componentes Acceso
Vue.component('rol', require('./components/Rol.vue'));
Vue.component('usuario', require('./components/Usuarios.vue'));

//Componentes gestoria
Vue.component('depositos', require('./components/Deposito.vue'));
Vue.component('expediente', require('./components/IntegracionExp.vue'));
Vue.component('asignargestor', require('./components/AsignarGestor.vue'));
Vue.component('seguimiento-tramite', require('./components/SeguimientoTramite.vue'));
Vue.component('facturacion', require('./components/Facturacion.vue'));
Vue.component('devolucion-cancelacion', require('./components/Devolucion.vue'));
Vue.component('devolucion-virtual', require('./components/Tesoreria/DevolucionVirtual.vue'));
Vue.component('devolucion-credito', require('./components/CreditoDevolucion.vue'));
Vue.component('bono-recomendado', require('./components/BonoRecomendado.vue'));

Vue.component('reubicaciones-devolucion', require('./components/Tesoreria/DepositosTransferir.vue'));

// Componentes Tesoreria
Vue.component('ingresos-concretania', require('./components/Tesoreria/IngresosConcretania.vue'));

// Proveedores
Vue.component('proveedor-seguimiento', require('./components/Proveedor/SegInstalacion.vue'));

// Contratistas
Vue.component('contratista-solicitud', require('./components/Contratista/Solicitudes.vue'));
Vue.component('contratista-revprevia', require('./components/Contratista/RevisionPrevia.vue'));

// PostVenta
Vue.component('postventa-entrega', require('./components/Postventa/Entrega.vue'));
Vue.component('postventa-etapa', require('./components/Postventa/PostventaEtapa.vue'));
Vue.component('solicitud-detalles', require('./components/Postventa/SolicitudDetalle.vue'));
Vue.component('revision-previa', require('./components/Postventa/RevisionPrevia.vue'));
Vue.component('datos-admin', require('./components/Postventa/DatosAdmin.vue'));

Vue.component('notification', require('./components/Notification.vue'));
Vue.component('perfil-user', require('./components/Perfil.vue'));
Vue.component('listar-notifications', require('./components/ListarNotifications.vue'));

// REPORTES
Vue.component('rep-empresas', require('./components/Reportes/ReporteEmpresas.vue'));
Vue.component('datos-extra', require('./components/Reportes/EstaditicaDatosExtra.vue'));
Vue.component('res-proyecto', require('./components/Reportes/ResumenProyecto.vue'));
Vue.component('res-puplicidad', require('./components/Reportes/ReportePublicidad.vue'));
Vue.component('rep-inventario', require('./components/Reportes/Inventario.vue'));
Vue.component('rep-vendedores', require('./components/Reportes/ReporteVendedores.vue'));
Vue.component('rep-lotes', require('./components/Reportes/ReporteIniTermVenta.vue'));
Vue.component('rep-ventascanc', require('./components/Reportes/VentasCancelaciones.vue'));
Vue.component('rep-acumulado', require('./components/Reportes/ReporteAcumulado.vue'));
Vue.component('rep-ingresos', require('./components/Reportes/ReporteIngresos.vue'));
Vue.component('rep-ingresosenganche', require('./components/Reportes/IngresosEnganche.vue'));
Vue.component('rep-escrituras', require('./components/Reportes/ReporteEscrituras.vue'));
Vue.component('recursos-propios', require('./components/Reportes/ReporteRecursosPropios.vue'));
Vue.component('repcredito-puente', require('./components/Reportes/ReporteCasasPuente.vue'));
Vue.component('reporte-modelos', require('./components/Reportes/ReporteModelos.vue'));
Vue.component('reporte-detalles', require('./components/Reportes/ResumenDetalles.vue'));
Vue.component('reporte-leads', require('./components/Reportes/ReporteLeads.vue'));
Vue.component('reporte-entregas', require('./components/Reportes/ReporteEntregas.vue'));
Vue.component('reporte-prospectos', require('./components/Reportes/ReporteProspectos.vue'));


Vue.component('avaluos', require('./components/Avaluos.vue'));
Vue.component('gastos-admin', require('./components/GastosAdministrativos.vue'));
Vue.component('estado-cuenta', require('./components/EstadoCuenta.vue'));
Vue.component('cobro-credito', require('./components/CobroCredito.vue'));
Vue.component('pagos-anticipados', require('./components/Tesoreria/AdelantoDep.vue'));

// CATALOGO DETALLES
Vue.component('catalogo-detalles', require('./components/Postventa/CatalogoDetalles.vue'));
Vue.component('detalles-generales', require('./components/Postventa/DetallesGenerales.vue'));

//Comisiones
Vue.component('comision-expediente', require('./components/Comisiones/IngresoComision.vue'));
Vue.component('comision-asesores', require('./components/Comisiones/ComisionesVentas.vue'));
Vue.component('comision-bonos', require('./components/Comisiones/BonosVentas.vue'));

//Pagos Internos
Vue.component('solicitud-crear', require('./components/PagosInternos/CreateSolicitud.vue'));
Vue.component('solicitud-pendiente', require('./components/PagosInternos/PendientesSaldo.vue'));
Vue.component('ventas-rep', require('./components/PagosInternos/RepVentas.vue'));
Vue.component('resumen-concetrado', require('./components/PagosInternos/ResumenConcetrado.vue'));


//Cotizador de lotes
Vue.component('cotizador-lote', require('./components/Cotizador/Cotizador.vue'));
Vue.component('cotizador-opciones', require('./components/Cotizador/CotizadorOpciones.vue'));
Vue.component('cotizador-editar', require('./components/Cotizador/CotizadorEditar.vue'));

//Estimaciones
Vue.component('estimaciones', require('./components/Obra/Estimaciones.vue'));

//CREDITOS PUENTE
Vue.component('solic-puente', require('./components/CPuente/SolicitarCredito.vue'));
Vue.component('creditos-puente', require('./components/CPuente/CreditoPuenteSeg.vue'));
Vue.component('base-presupuestal', require('./components/CPuente/BasePresupuestal.vue'));
Vue.component('puente-cuenta', require('./components/CPuente/CreditoPuenteCuenta.vue'));
Vue.component('puente-bbva', require('./components/CPuente/CreditoPuenteCuentaBBVA.vue'));
Vue.component('puente-avances', require('./components/CPuente/Avances.vue'));
Vue.component('puente-resumen', require('./components/CPuente/Resumen.vue'));

// RH
Vue.component('vehiculo-comodato', require('./components/Rh/VehiculoComodato.vue'));
Vue.component('fondo-ahorro', require('./components/Rh/FondoAhorro.vue'));
Vue.component('fondo-pension', require('./components/Rh/FondoPension.vue'));
Vue.component('prestamos-personal', require('./components/Rh/PrestamosPersonal.vue'));
Vue.component('ficha-medica', require('./components/Rh/FichaMedica.vue'));

Vue.component('panel-items', require('./components/Rh/Donativos/PanelControl.vue'));
Vue.component('listado-items', require('./components/Rh/Donativos/ListadoDonaciones.vue'));

// Inventario
Vue.component('inventarios', require('./components/Oficina/Inventario.vue'));
Vue.component('inv-proveedor', require('./components/Oficina/Proveedor.vue'));

//Integración de cobros
Vue.component('integracion-cobros', require('./components/IntegracionCobros/Integracion.vue'));


//ENCUESTAS
Vue.component('encuesta-venta', require('./components/Postventa/Encuestas/EncuestaVenta.vue'));
const app = new Vue({
    el: '#app',
    data: {
        menu: 100,
        notifications: [],
        proyectos : [],
        modelos : [],
        etapas: [],
        manzanas:[],
        lotes:[],
        empresas:[],
        arrayClaves: [],
        arrayMediosPublicidad:[],
        buscar:''
    },

    methods:{
        callFunction: function () {
            var v = this;
            setInterval(function () {
                v.created();
            }, 1800000);
        },
        /* Listening to the channel and when it receives a notification it adds it to the notifications
        array. */
        created() {
            let me = this;
            Axios.post('notification/get').then(function(response) {
                // console.log(response.data);
                me.notifications = response.data;
            }).catch(function(error) {
                //console.log(error);
            });

            var userId = $('meta[name="userId"]').attr('content');

            window.Echo.private('App.User.' + userId).listen(('PackageNotification'), (e) => {
                me.notifications.unshift(notification);
            });
        },
        getClavesLadas(){
            let me = this;
            me.arrayClaves=[];
            var url = '/getClavesLadas';
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayClaves = respuesta.claves;
            })
            .catch(function (error) {
                console.log(error);
            });

        },
        /* Making an Axios call to the backend and getting the data from the database. */
        selectFraccionamientos(){
            let me = this;
            me.proyectos=[];
            var url = '/select_fraccionamiento';
            Axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.proyectos = respuesta.fraccionamientos;
            })
            .catch(function (error) {
                console.log(error);
            });
        },

        /* A function that is called when the user selects a project from the dropdown menu. It is used
        to populate the second dropdown menu with the stages of the selected project. */
        selectEtapa(proyecto){
            let me = this;
            me.etapas=[];
            var url = '/select_etapa_proyecto?buscar=' + proyecto;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.etapas = respuesta.etapas;
            })
            .catch(function (error) {
            });
        },

        selectModelo(proyecto){
            let me = this;

            me.modelos=[];
            var url = '/select_modelo_proyecto?buscar=' + proyecto;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.modelos = respuesta.modelos;
            })
            .catch(function (error) {
            });
        },
        selectManzanas(fraccionamiento, etapa){
            let me = this;

            me.arrayAllManzanas=[];
            var url = '/select_manzanas_etapa?buscar=' + fraccionamiento + '&buscar1='+ etapa;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayAllManzanas = respuesta.manzana;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        //Busqueda de manzanas por nombre de proyecto y nombre de etapa
        getManzanas(proyecto, etapa){
            let me = this;

            me.manzanas = [];
            var url = '/lotes/getManzanas?proyecto=' + proyecto + '&etapa='+ etapa;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.manzanas = respuesta;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        getEmpresa(){
            let me = this;
            me.empresas=[];
            var url = '/lotes/empresa/select';
            axios.get(url).then(function (response) {
                var respuesta = response;
                me.empresas = respuesta.data.empresas;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        selectMedioPublicidad(){
            let me = this;
            me.arrayMediosPublicidad=[];
            var url = '/select_medio_publicidad';
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayMediosPublicidad = respuesta.medios_publicitarios;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        //Busqueda de lotes por nombre de proyecto, nombre de etapa y manzana
        searchLotes(proyecto, etapa, manzana){
            let me = this;

            me.lotes = [];
            var url = '/lotes/searchLotes?proyecto=' + proyecto + '&etapa='+ etapa + '&manzana=' + manzana;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.lotes = respuesta;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        /* Formatting the number to have commas in the thousands place. */
        formatNumber(value) {
            let val = (value/1).toFixed(2)
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
        /* Checking if the key pressed is a number or not. */
        isNumber: function(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                evt.preventDefault();;
            } else {
                return true;
            }
        },

    },
    mounted () {
        this.created();
        this.callFunction();
      }
});
