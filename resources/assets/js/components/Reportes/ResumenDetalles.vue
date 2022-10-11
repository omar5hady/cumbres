<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Reporte de Detalles
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <select class="form-control" @change="selectEtapas(b_proyecto)" v-model="b_proyecto" >
                                        <option value="">Fraccionamiento</option>
                                        <option v-for="proyecto in arrayFraccionamientos" :key="proyecto.id" :value="proyecto.id" v-text="proyecto.nombre"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <select class="form-control" v-model="b_etapa" >
                                        <option value="">Etapa</option>
                                        <option v-for="etapa in arrayAllEtapas" :key="etapa.id" :value="etapa.id" v-text="etapa.num_etapa"></option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="date"  v-model="desde" class="form-control" placeholder="Fecha inicial">
                                    <input type="date"  v-model="hasta" @keyup.enter="listarResumen(1)" class="form-control" placeholder="Fecha final">

                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-7">
                                <div class="input-group">
                                    <select class="form-control" v-model="b_contratista" >
                                        <option value="">Contratista</option>
                                        <option v-for="contratista in arrayContratistas" :key="contratista.id" :value="contratista.id" v-text="contratista.nombre"></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="input-group">
                                    <button type="submit" @click="listarResumen(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>

                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a @click="b_contratista='', b_status = '', listarResumen(1), tab=1" class="nav-link" v-bind:class="{ 'text-info active': tab==1 }">
                                    Resumen
                                    <span v-if="tab ==1" style="font-size: 1em; text-align:center;" class="badge badge-light"></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a @click="tab=2" class="nav-link" v-bind:class="{ 'text-info active': tab==2 }">
                                    Solicitudes ({{arrayResProyecto.total}})
                                    <span v-if="tab ==2" style="font-size: 1em; text-align:center;" class="badge badge-light"></span>
                                </a>
                            </li>
                        </ul>

                        <template v-if="tab == 1">
                            <div class="form-group row">
                                <div class="col-md-8">
                                    <div class="input-group">
                                    <table class="table table-bordered table-striped table-sm">
                                        <tr>
                                            <th colspan="2">Solicitudes por contratista</th>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>Total</td>
                                            <td>Concluidos</td>
                                            <td>Pendientes</td>
                                            <td>Proceso</td>
                                            <td>Cancelados</td>
                                        </tr>
                                        <tr v-for="contratista in arrayContratistaDet" :key="contratista.id">
                                            <template v-if="contratista.conteo != 0">
                                                    <th v-text="contratista.nombre"></th>
                                                    <td>
                                                        <a class="btn btn-primary"
                                                            @click="verSolicitudes(contratista.id,'')"
                                                            href="#" title="Ver Solicitudes">
                                                            {{contratista.conteo}}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-success" href="#"
                                                            @click="verSolicitudes(contratista.id,2)"
                                                            title="Ver Solicitudes">{{contratista.num_concluidos}}</a>
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-warning" href="#"
                                                            @click="verSolicitudes(contratista.id,0)"
                                                            title="Ver Solicitudes">{{contratista.num_pendientes}}</a>
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-info" href="#"
                                                            @click="verSolicitudes(contratista.id,1)"
                                                            title="Ver Solicitudes">{{contratista.num_proceso}}</a>
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-danger" href="#"
                                                            @click="verSolicitudes(contratista.id,3)"
                                                            title="Ver Solicitudes">{{contratista.num_cancelados}}</a>
                                                    </td>
                                            </template>

                                        </tr>
                                    </table>
                                    </div>
                                </div>

                                <div class="col-md-1"></div>

                                <div class="col-md-3">
                                    <div class="input-group">
                                    <table class="table table-bordered table-striped table-sm">
                                        <tr>
                                            <th colspan="2">Conteo de Detalles </th>
                                        </tr>
                                        <tr>
                                            <td colspan="2"></td>
                                        </tr>
                                        <tr v-for="detalle in arrayDetalles" :key="detalle.id">
                                            <template v-if="detalle.conteo != 0">
                                                    <th v-text="detalle.detalles"></th>
                                                    <td>
                                                        <a class="btn btn-light" href="#"
                                                            title="Ver Solicitudes">{{detalle.conteo}}</a>
                                                    </td>
                                            </template>
                                        </tr>

                                    </table>
                                    </div>
                                </div>


                            </div>

                            <!-- <div class="form-group row">
                                <div class="col-md-3">
                                    <a class="btn btn-success" v-bind:href="'/reportes/reporteDetallesExcel?proyecto='+ b_proyecto + '&etapa='+ b_etapa + '&contratista='+ b_contratista  + '&desde='+ desde + '&hasta='+ hasta">
                                        <i class="fa fa-file-text"></i>&nbsp; Descargar excel
                                    </a>
                                </div>
                            </div> -->
                        </template>

                        <template v-if="tab == 2">
                            <TableComponent
                                :cabecera="[
                                    '','Proyecto','Etapa','Manzana','# Lote','Cliente',
                                    'Fecha de solicitud','Status','Contratista'
                                ]"
                            >
                                <template v-slot:tbody>
                                    <tr v-for="contrato in arrayResProyecto.data" :key="contrato.id">
                                        <td>
                                            <button @click="verDetalle(contrato)" class="btn btn-dark" title="Ver Detalle">
                                                <i class="icon-eye"></i>
                                            </button>
                                        </td>
                                        <td class="td2" v-text="contrato.proyecto"></td>
                                        <td class="td2" v-text="contrato.num_etapa"></td>
                                        <td class="td2" v-text="contrato.manzana"></td>
                                        <td class="td2">
                                            {{(contrato.sublote) ? `${contrato.num_lote}-${contrato.sublote}` : contrato.num_lote}}
                                        </td>
                                        <td class="td2" v-text="contrato.cliente"></td>
                                        <td class="td2" v-text="this.moment(contrato.created_at).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td class="td2">
                                            <span class="badge badge-warning" v-if="contrato.status == 0">Solicitud Creada</span>
                                            <span class="badge badge-info" v-if="contrato.status == 1">En Proceso</span>
                                            <span class="badge badge-success" v-if="contrato.status == 2">Concluido</span>
                                            <span class="badge badge-danger" v-if="contrato.status == 3">Cancelado</span>
                                        </td>
                                        <td class="td2" v-text="contrato.nombre"></td>
                                    </tr>
                                </template>
                            </TableComponent>
                        </template>

                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <ModalComponent v-if="modal==1"
                :titulo="'Descripción de detalles'"
                @closeModal="modal=0"
            >
                <template v-slot:body>
                    <div class="form-group row">
                        <label class="col-md-2 form-control-label" for="text-input">Cliente</label>
                        <div class="col-md-6">
                            <input type="text" disabled v-model="descripcion.cliente" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 form-control-label" for="text-input">Pryecto</label>
                        <div class="col-md-4">
                            <input type="text" disabled v-model="descripcion.proyecto" class="form-control">
                        </div>
                        <label class="col-md-2 form-control-label" for="text-input">Etapa</label>
                        <div class="col-md-4">
                            <input type="text" disabled v-model="descripcion.etapa" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 form-control-label" for="text-input">Manzana</label>
                        <div class="col-md-4">
                            <input type="text" disabled v-model="descripcion.manzana" class="form-control">
                        </div>
                        <label class="col-md-2 form-control-label" for="text-input">Lote</label>
                        <div class="col-md-4">
                            <input type="text" disabled v-model="descripcion.lote" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 form-control-label" for="text-input">Contratista</label>
                        <div class="col-md-6">
                            <input type="text" disabled v-model="descripcion.contratista" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <TableComponent :cabecera="[
                               'Detalle', 'Descripcion', 'Fecha concluido'
                            ]">
                                <template v-slot:tbody>
                                    <tr v-for="det in descripcion.data" :key="det.id">
                                        <td class="td2" v-text="det.detalle"></td>
                                        <td class="td2" v-text="det.observacion"></td>
                                        <td class="td2" v-if="det.fecha_concluido"
                                            v-text="this.moment(det.fecha_concluido).locale('es').format('DD/MMM/YYYY')">
                                        </td>
                                        <td class="td2" v-else>
                                            Detalle sin concluir
                                        </td>
                                    </tr>
                                </template>
                            </TableComponent>
                        </div>
                    </div>
                </template>
            </ModalComponent>
        </main>
</template>

<script>
import TableComponent from '../Componentes/TableComponent.vue';
import ModalComponent from '../Componentes/ModalComponent.vue';

    export default {
        components:{
            TableComponent,
            ModalComponent
        },
        props:{
            rolId:{type: String}
        },
        data (){
            return {
                proceso:false,
                arrayResProyecto : {},
                arrayFraccionamientos: [],
                arrayAllEtapas:[],
                arrayContratistas:[],

                arrayDetalles:[],
                arrayContratistaDet:[],

                descripcion:{},

                b_proyecto : '',
                b_etapa : '',
                b_contratista:'',
                b_status:'',
                desde:'',
                hasta:'',
                tab:1,
                modal:0
            }
        },
        computed:{
        },
        methods : {
            listarResumen (page){
                let me=this;
                var url= '/reportes/reporteDetalles?page=' + page + '&proyecto='+ me.b_proyecto + '&etapa='+ me.b_etapa +
                        '&contratista='+this.b_contratista + '&desde='+ me.desde + '&hasta='+ me.hasta + '&status=' + me.b_status;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayDetalles = respuesta.detalles;
                    me.arrayContratistaDet = respuesta.contratistas;

                    me.arrayResProyecto = respuesta.solicitudes;

                    me.arrayDetalles.sort((b, a) => a.cont - b.cont);
                    me.arrayContratistaDet.sort((b, a) => a.cont - b.cont);
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
            selectFraccionamientos(){
                let me = this;
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
            selectContratistas(){
                let me = this;
                me.arrayContratistas = [];
                var url = '/select_contratistas';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayContratistas = respuesta.contratista;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectEtapas(buscar){
                let me = this;

                me.arrayAllEtapas=[];
                var url = '/select_etapa_proyecto?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayAllEtapas = respuesta.etapas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            verSolicitudes(contratista,status){
                this.b_status = status;
                this.b_contratista = contratista;
                this.listarResumen(1);
                this.tab = 2;
            },
            verDetalle(detalle){
                let lote = detalle.num_lote;
                if(detalle.sublote != null)
                    lote = detalle.num_lote+'-'+detalle.sublote;
                this.descripcion = {
                    'data' : detalle.descripcion,
                    'cliente': detalle.cliente,
                    'proyecto': detalle.proyecto,
                    'etapa' : detalle.num_etapa,
                    'manzana' : detalle.manzana,
                    'lote' : lote,
                    'contratista' : detalle.nombre
                }
                this.modal = 1;
            }
        },

        mounted() {
            this.selectFraccionamientos();
            this.selectContratistas();
            this.listarResumen(1);
        }
    }
</script>
<style>
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: fixed !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
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
