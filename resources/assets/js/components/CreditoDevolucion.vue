<template >
    <main class="main" >
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card scroll-box">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i>Devoluciones por excedente &nbsp;
                        <button class="btn btn-danger" v-if="listado == 1" @click="listado = 2 , listarDevoluciones(1, buscar_d, b_etapa_d, b_manzana_d, b_lote_d, criterio_d)">Historial de devoluciones</button>
                        <button class="btn btn-warning" v-if="listado == 2" @click="listado = 1">Volver a las devoluciones</button>
                    </div>

                    <!-- listado de devoluciones -->
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-7">
                                <div class="input-group">
                                    <select class="form-control" v-model="b_empresa" >
                                        <option value="">Empresa constructora</option>
                                        <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-10">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-4" v-model="criterio" @change="selectFraccionamientos()">
                                        <option value="lotes.fraccionamiento_id">Proyecto</option>
                                        <option value="personal.nombre">Cliente</option>
                                        <option value="creditos.id"># Folio</option>
                                    </select>

                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar"  @keyup.enter="listarContratos(1)" @change="selectEtapa(buscar)">
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>

                                    <input v-else type="text"  v-model="buscar" @keyup.enter="listarContratos(1)" class="form-control" placeholder="Texto a buscar">

                                </div>
                                <div class="input-group" v-if="criterio=='lotes.fraccionamiento_id'">
                                    <select class="form-control" v-model="b_etapa"  @keyup.enter="listarContratos(1)" @change="selectManzanas(buscar,b_etapa)">
                                        <option value="">Etapa</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                    </select>

                                    <select class="form-control" v-model="b_manzana" >
                                        <option value="">Seleccione</option>
                                        <option v-for="manzana in arrayManzanas" :key="manzana.manzana" :value="manzana.manzana" v-text="manzana.manzana"></option>
                                    </select>
                                </div>
                                <div class="input-group" v-if="criterio=='lotes.fraccionamiento_id'">
                                    <input type="text" v-model="b_lote" class="form-control col-md-4" placeholder="Lote a buscar">
                                </div>
                            </div>
                        </div>
                        <template v-if="listado == 1">
                            <div class="form-group row">
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <select class="form-control" v-model="b_status" >
                                            <option value="">Estatus</option>
                                            <option value="Pendiente">Pendiente</option>
                                            <option value="Solicitado">Solicitado</option>
                                            <option value="Fondeado">Fondeado</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <button type="submit" @click="listarContratos(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                        <a :href="'/credito_devolucion/excel?buscar=' + buscar + '&b_etapa=' + b_etapa + '&b_manzana=' + b_manzana +
                                                '&b_lote=' + b_lote +  '&criterio=' + criterio+'&b_empresa='+b_empresa"
                                            class="btn btn-success"><i class="fa fa-file-text"></i> Excel
                                        </a>

                                    </div>
                                </div>
                            </div>
                        </template>
                        <div class="form-group row" v-if="listado == 2">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <button type="submit" @click="listarDevoluciones(1, buscar_d, b_etapa_d, b_manzana_d, b_lote_d, criterio_d)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    <a :href="'/devoluciones_credito/excel?buscar=' + buscar_d + '&b_etapa=' + b_etapa_d +
                                            '&b_manzana=' + b_manzana_d + '&b_lote=' + b_lote_d +  '&criterio=' + criterio_d+
                                            '&b_empresa='+b_empresa"
                                        class="btn btn-success"><i class="fa fa-file-text"></i> Excel
                                    </a>
                                </div>
                            </div>
                        </div>
                        <template v-if="listado == 1">
                            <div class="container" v-if="arrayContratos">
                                <TableComponent
                                    :cabecera="[
                                        '',
                                        '# Ref', 'Cliente', 'Proyecto',
                                        'Etapa', 'Manzana', 'Lote',
                                        'Fecha de firma de escrituras',
                                        'Pendiente a devolver', 'Observaciones',
                                    ]"
                                >
                                    <template v-slot:tbody>
                                        <tr v-for="contrato in arrayContratos.data" :key="contrato.id" title="Doble click">
                                            <td class="td2">
                                                <button v-if="contrato.status_devolucion == 'Pendiente'"
                                                    class="btn btn-primary" @click="cambiarStatus(contrato.id, 'Solicitado')">Solicitar
                                                </button>
                                                <button v-if="contrato.status_devolucion == 'Solicitado'"
                                                    class="btn btn-primary" @click="cambiarStatus(contrato.id, 'Fondeado')">Fondear
                                                </button>
                                            </td>
                                            <td class="td2">
                                                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">{{contrato.id}}</a>
                                                <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
                                                    <a class="dropdown-item" @click="abrirPDF(contrato.id)">Estado de cuenta</a>
                                                </div>
                                            </td>
                                            <td class="td2">
                                                <a v-if="contrato.status_devolucion == 'Fondeado'" @click="abrirModal('devolucion',contrato)" href="#" v-text="contrato.nombre_cliente"></a>
                                                <label v-else>{{ contrato.nombre_cliente }}</label>
                                            </td>
                                            <td class="td2" v-text="contrato.proyecto"></td>
                                            <td class="td2" v-text="contrato.etapa"></td>
                                            <td class="td2" v-text="contrato.manzana"></td>
                                            <td class="td2" v-text="contrato.num_lote"></td>
                                            <td class="td2" v-if="contrato.fecha_firma_esc != null" v-text="this.moment(contrato.fecha_firma_esc).locale('es').format('DD/MMM/YYYY')"></td>
                                            <td class="td2" v-else>Sin firmar</td>
                                            <td class="td2" v-text="'$'+$root.formatNumber(contrato.saldo*(-1))"></td>
                                            <td class="td2">
                                                <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right"
                                                            @click="abrirModal('observaciones',contrato)">Observaciones</button>
                                            </td>
                                        </tr>
                                    </template>
                                </TableComponent>
                                <NavComponent
                                    :current="arrayContratos.current_page ? arrayContratos.current_page : 1"
                                    :last="arrayContratos.last_page ? arrayContratos.last_page : 1"
                                    @changePage="listarContratos"
                                ></NavComponent>
                            </div>
                        </template>
                        <template v-if="listado == 2">
                            <div class="container" v-if="arrayDevoluciones">
                                <TableComponent :cabecera="[
                                    '# Ref', 'Cliente', 'Proyecto',
                                    'Etapa', 'Manzana', 'Lote',
                                    'Devuelto', 'Fecha de contrato',
                                    'Fecha de devolucion', 'Cheque',
                                    'Cuenta', 'Observaciones',
                                ]">
                                    <template v-slot:tbody>
                                        <tr v-for="devoluciones in arrayDevoluciones.data" :key="devoluciones.id" @dblclick="abrirModal('info',devoluciones)" title="Doble click">
                                            <td class="td2">
                                                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">{{devoluciones.id}}</a>
                                                <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
                                                    <a class="dropdown-item" @click="abrirPDF(devoluciones.id)">Estado de cuenta</a>
                                                </div>
                                            </td>
                                            <td class="td2">
                                                <a href="#" v-text="devoluciones.nombre_cliente"></a>
                                            </td>
                                            <td class="td2" v-text="devoluciones.proyecto"></td>
                                            <td class="td2" v-text="devoluciones.etapa"></td>
                                            <td class="td2" v-text="devoluciones.manzana"></td>
                                            <td class="td2" v-text="devoluciones.num_lote"></td>
                                            <td class="td2" v-text="'$'+$root.formatNumber(devoluciones.devolver)"></td>
                                            <td class="td2" v-text="this.moment(devoluciones.fecha_status).locale('es').format('DD/MMM/YYYY')"></td>
                                            <td class="td2" v-text="this.moment(devoluciones.fecha).locale('es').format('DD/MMM/YYYY')"></td>
                                            <td class="td2" v-text="devoluciones.cheque"></td>
                                            <td class="td2" v-text="devoluciones.cuenta"></td>
                                            <td class="td2">
                                                <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right"
                                                            @click="abrirModal('observaciones',devoluciones)">Observaciones</button>
                                            </td>
                                        </tr>
                                    </template>
                                </TableComponent>
                                <NavComponent
                                    :current="arrayDevoluciones.current_page ? arrayDevoluciones.current_page : 1"
                                    :last="arrayDevoluciones.last_page ? arrayDevoluciones.last_page : 1"
                                    @changePage="listarDevoluciones"
                                ></NavComponent>
                            </div>
                        </template>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>


            <!--Inicio del modal -->
            <ModalDevolucionExcedente v-if="modal == 1"
                :titulo="tituloModal"
                :data="datos"
                :tipoAccion="tipoAccion"
                @close="cerrarModal"
            ></ModalDevolucionExcedente>
            <!--Fin del modal consulta-->

            <!--Inicio del modal observaciones-->
            <ModalComponent v-if="modal == 2"
                :titulo="tituloModal"
                @closeModal="cerrarModal()"
            >
                <template v-slot:body>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Observacion</label>
                        <div class="col-md-6">
                                <textarea rows="3" cols="30" v-model="observacion" class="form-control" placeholder="Observacion"></textarea>
                        </div>
                        <div class="col-md-2">
                            <button type="button"  class="btn btn-primary" @click="agregarComentario()">Guardar</button>
                        </div>
                    </div>


                    <table class="table table-bordered table-striped table-sm" >
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Observacion</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="observacion in arrayObservacion" :key="observacion.id">

                                <td v-text="observacion.usuario" ></td>
                                <td v-text="observacion.observacion" ></td>
                                <td v-text="observacion.created_at"></td>
                            </tr>
                        </tbody>
                    </table>
                </template>
            </ModalComponent>
     </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
import ModalComponent from './Componentes/ModalComponent';
import RowModal from './Componentes/ComponentesModal/RowModalComponent';
import TableComponent from './Componentes/TableComponent';
import NavComponent from './Componentes/NavComponent';
import LoadingComponent from './Componentes/LoadingComponent';
import ModalDevolucionExcedente from './Saldos/components/ModalDevolucionExcedente';
    export default {
        components:{
            ModalComponent,
            RowModal,
            TableComponent,
            NavComponent,
            LoadingComponent,
            ModalDevolucionExcedente
        },
        data(){
            return{
                proceso:false,
                loading: false,
                id: 0,

                arrayContratos : [],
                arrayFraccionamientos:[],
                arrayEtapas:[],
                arrayManzanas:[],
                arrayDevoluciones: [],
                arrayObservacion: [],
                observacion:'',

                modal : 0,
                errorDev:0,
                errorMostrarMsjDev:[],

                tituloModal : '',
                tipoAccion: 0,
                criterio : 'lotes.fraccionamiento_id',
                buscar : '',
                b_etapa: '',
                b_manzana: '',
                b_lote: '',
                b_empresa:'',
                b_status: '',
                listado : 1,
                empresas:[],
                datos: {}
            }
        },
        methods : {
            /**Metodo para mostrar los registros */
            listarContratos(page){
                let me = this;
                var url = '/credito_devolucion/index?page=' + page + '&buscar=' + me.buscar
                + '&b_etapa=' + me.b_etapa + '&b_manzana=' + me.b_manzana + '&b_lote=' + me.b_lote
                + '&criterio=' + me.criterio+'&b_empresa=' + me.b_empresa + '&b_status=' + me.b_status;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayContratos = respuesta.creditos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            listarDevoluciones(page){
                let me = this;
                var url = '/credito_devolucion/indexDevoluciones?page=' + page + '&buscar=' + me.buscar + '&b_etapa=' + me.b_etapa +
                '&b_manzana=' + me.b_manzana + '&b_lote=' + me.b_lote +  '&criterio=' + me.criterio+'&b_empresa=' + me.b_empresa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayDevoluciones = respuesta.devoluciones;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            listarObservacion(buscar){
                let me = this;
                var url = '/devoluciones/listarObservacionesCredit?id=' + buscar ;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayObservacion = respuesta.observacion;
                    console.log(url);
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            cambiarStatus(id, status){
                let me = this;
                axios.put('/devoluciones/cambiarStatus',{
                    'status':status,
                    'id' : id
                }).then(function (response){
                me.listarContratos(me.arrayContratos.current_page);
                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000

                    });

                    toast({
                    type: 'success',
                    title: 'Cambios guardados'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            agregarComentario(){
                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/devoluciones/storeObservacionCredit',{
                    'id': this.id,
                    'observacion': this.observacion
                }).then(function (response){
                    me.listarObservacion(me.id);
                    me.observacion = '';
                    //me.cerrarModal3(); //al guardar el registro se cierra el modal

                    const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                    });

                    toast({
                    type: 'success',
                    title: 'Observación Agregada Correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            abrirPDF(id){
                const win = window.open('/estadoCuenta/estadoPDF/'+id, '_blank');
                win.focus();
            },

            selectFraccionamientos(){
                let me = this;
                me.buscar="";
                me.b_etapa="";
                me.b_manzana="";
                me.b_lote="";

                me.arrayFraccionamientos=[];
                var url = '/select_fraccionamiento';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayFraccionamientos = respuesta.fraccionamientos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectEtapa(buscar){
                let me = this;
                me.b_etapa=""

                me.arrayEtapas=[];
                var url = '/select_etapa_proyecto?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEtapas = respuesta.etapas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectManzanas(buscar1, buscar2){
                let me = this;
                me.b_manzana="";
                me.b_lote="";

                me.arrayManzanas=[];
                var url = '/select_manzanas_etapa?buscar=' + buscar1 + '&buscar1='+ buscar2;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayManzanas = respuesta.manzana;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';;
                this.observacion = '';
                this.arrayObservacion = [];
                this.datos = {}
            },

            abrirModal(accion,data =[]){
                switch(accion){
                    case 'devolucion':
                    {
                        this.tipoAccion = 1;
                        this.datos.id = data['id'];
                        this.datos.proyecto = data['proyecto'];
                        this.datos.etapa = data['etapa'];
                        this.datos.manzana = data['manzana'];
                        this.datos.lote = data['num_lote'];
                        this.datos.cliente = data['nombre_cliente'];
                        this.datos.devolver = data['saldo'] * (-1);
                        this.datos.cant_dev = 0;
                        this.datos.cheque ='';
                        this.datos.observaciones = '';
                        this.modal = 1;
                        this.tituloModal='Generar devolución';
                        break;
                    }

                    case 'info':
                    {
                        this.datos.id = data['id'];
                        this.proyecto = data['proyecto'];
                        this.datos.etapa = data['etapa'];
                        this.datos.manzana = data['manzana'];
                        this.datos.lote = data['num_lote'];
                        this.datos.cliente = data['nombre_cliente'];
                        this.datos.devolver = data['devolver'];
                        this.datos.cant_dev = data['devolver'];
                        this.datos.cheque =data['cheque'];
                        this.datos.observaciones = data['observaciones'];
                        this.datos.fecha_devolucion = data['fecha'];
                        this.datos.banco = data['cuenta'];
                        this.datos.arrayGastos=[];
                        this.modal = 1;
                        this.tituloModal='Devolución';
                        this.tipoAccion = 2;
                        break;
                    }

                    case 'observaciones':{
                        this.modal = 2;
                        this.tituloModal='Observaciones';
                        this.observacion='';
                        this.datos.id=data['id'];
                        this.listarObservacion(this.datos.id);
                        break;
                    }
                }
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
        },

        mounted() {
            this.listarContratos(1);
            this.selectFraccionamientos();
            this.getEmpresa();
        }
    }
</script>
<style>
    .line-separator{
        height:1px;
        background:#717171;
        border-bottom:1px solid #c2cfd6;
    }
    .form-control:disabled, .form-control[readonly] {
        background-color: rgba(0, 0, 0, 0.06);
        opacity: 1;
        font-size: 1rem;
        color: #27417b;
    }
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: fixed !important;
        background-color: #3c29297a !important;
         overflow-y: auto;

    }
    .div-error{
        display:flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
    .table2 {
    margin: auto;
    border-collapse: collapse;
    overflow-x: auto;
    display: block;
    width: fit-content;
    max-width: 100%;
    box-shadow: 0 0 1px 1px rgba(0, 0, 0, .1);
    }

    .td2, .th2 {
    border: solid rgb(200, 200, 200) 1px;
    padding: .5rem;
    }

    .badge2 {
    display: inline-block;
    padding: 0.25em 0.4em;
    font-size: 90%;
    font-weight: bold;
    line-height: 1;
    color: #fff;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
}
    .td2 {
    white-space: nowrap;
    border-bottom: none;
    color: rgb(20, 20, 20);
    }

    .td2:first-of-type, th:first-of-type {
    border-left: none;
    }

    .td2:last-of-type, th:last-of-type {
    border-right: none;
    }
</style>
