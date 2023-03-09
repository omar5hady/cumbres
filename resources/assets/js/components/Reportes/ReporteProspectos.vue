<template>
    <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card scroll-box">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Reporte Prospectos &nbsp;&nbsp;
                        <a :href="'/excel/reportes/prospectos?listado='+listado"  class="btn btn-success"><i class="fa fa-file-text"></i> Excel </a>

                    </div>
                    <div class="card-body">

                        <ul class="nav nav2 nav-tabs" id="myTab1" role="tablist">
                            <li class="nav-item" @click="listado=0, listarReporte(listado)">
                                <a class="nav-link active show" id="internos-tab" data-toggle="tab" href="#internos" role="tab" aria-controls="internos" v-text="'Asesores Internos'"></a>
                            </li>
                             <li class="nav-item" @click="listado=1, listarReporte(listado)">
                                <a class="nav-link " id="externos-tab" data-toggle="tab" href="#externos" role="tab" aria-controls="externos" aria-selected="false" v-text="'Asesores Externos'"></a>
                            </li>
                            <li class="nav-item" @click="listado=3">
                                <a class="nav-link " id="clasificacion-tab" data-toggle="tab" href="#clasificacion" role="tab" aria-controls="clasificacion" aria-selected="false" v-text="'Por Clasificación'"></a>
                            </li>
                        </ul>



                        <div class="tab-content" id="myTab1Content">
                            <!-- Listado por ingresos -->
                            <div class="tab-pane fade active show" id="internos" role="tabpanel" aria-labelledby="internos-tab" >
                                       <div v-if="arrayVendedores.length !=[]">
                                                <div class="form-group row">

                                                    <div class="col-md-12">
                                                        <div class="table-responsive">
                                                            <table class="table2 table-light table-bordered table-striped table-sm">
                                                                <thead>
                                                                    <tr>
                                                                        <th colspan="3" class="text-center">SEGUIMIENTO DE PROSPECTOS</th>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2"></td>
                                                                        <th colspan="3" class="text-center">Seguimiento asesor</th>
                                                                        <td></td>
                                                                        <th colspan="3" class="text-center">Seguimiento gerente</th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Gerente</th>
                                                                        <th> Asesor</th>
                                                                        <th> Prospectos en verde</th>
                                                                        <th> Prospectos amarillo</th>
                                                                        <th> Prospectos retirados</th>
                                                                        <th></th>
                                                                        <th> Prospectos en verde</th>
                                                                        <th> Prospectos amarillo</th>
                                                                        <th> Prospectos rojo</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr v-for="asesor in arrayVendedores" :key="asesor.id">
                                                                        <th class="td2" v-text="asesor.gerente"></th>
                                                                        <td class="td2" v-text="asesor.vendedor"></td>
                                                                        <td class="td2 table-success" v-text="asesor.reg"></td>
                                                                        <td class="td2 table-warning" v-text="asesor.dif7"></td>
                                                                        <td class="td2 table-danger">
                                                                            <a @click="abrirModal('retirados', asesor.retirados)" href="#">{{asesor.n_retirados}}</a>
                                                                        </td>
                                                                        <td></td>
                                                                        <td class="td2 table-success" v-text="asesor.ger"></td>
                                                                        <td class="td2 table-warning" v-text="asesor.ger7"></td>
                                                                        <td class="td2 table-danger" v-text="asesor.ger15"></td>
                                                                    </tr>

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>

                                                </div>

                                       </div>



                            </div>

                            <div class="tab-pane fade" id="externos" role="tabpanel" aria-labelledby="externos-tab" >
                                <div v-if="arrayVendedores.length !=[]">
                                        <div class="form-group row">

                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="table2 table-light table-bordered table-striped table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="3" class="text-center">SEGUIMIENTO DE PROSPECTOS</th>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2"></td>
                                                                <th colspan="3" class="text-center">Seguimiento asesor</th>
                                                                <td></td>
                                                                <th colspan="3" class="text-center">Seguimiento gerente</th>
                                                            </tr>
                                                            <tr>

                                                                <th> Asesor</th>
                                                                <th> Prospectos en verde</th>
                                                                <th> Prospectos amarillo</th>
                                                                <th> Prospectos retirados</th>
                                                                <th></th>
                                                                <th> Prospectos en verde</th>
                                                                <th> Prospectos amarillo</th>
                                                                <th> Prospectos rojo</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="asesor in arrayVendedores" :key="asesor.id">
                                                                <td class="td2" v-text="asesor.vendedor"></td>
                                                                <td class="td2 table-success" v-text="asesor.reg"></td>
                                                                <td class="td2 table-warning" v-text="asesor.dif7"></td>
                                                                <td class="td2 table-danger">
                                                                    <a @click="abrirModal('retirados', asesor.retirados)" href="#">{{asesor.n_retirados}}</a>
                                                                </td>
                                                                <td></td>
                                                                <td class="td2 table-success" v-text="asesor.ger"></td>
                                                                <td class="td2 table-warning" v-text="asesor.ger7"></td>
                                                                <td class="td2 table-danger" v-text="asesor.ger15"></td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </div>

                                </div>


                            </div>


                            <div class="tab-pane fade" id="clasificacion" role="tabpanel" aria-labelledby="clasificacion-tab" >
                                <div v-if="arrayVendedores.length !=[]">
                                        <div class="form-group row">

                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="table2 table-light table-bordered table-striped table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="3" class="text-center">SEGUIMIENTO DE PROSPECTOS</th>
                                                            </tr>
                                                            <tr>
                                                                <td colspan=""></td>
                                                                <th colspan="4" class="text-center">Clasificacion</th>
                                                            </tr>
                                                            <tr>
                                                                <th> Asesor</th>
                                                                <th>A</th>
                                                                <th>B</th>
                                                                <th>C</th>
                                                                <th>No viable</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="asesor in arrayClasificacion" :key="asesor.id">
                                                                <td class="td2" v-text="asesor.vendedor"></td>
                                                                <td class="td2 table-danger" title="Ver Prospectos">
                                                                    <a @click="abrirModal('A', asesor.tipoA)" href="#">{{asesor.a}}</a>
                                                                </td>
                                                                <td class="td2 table-warning" title="Ver Prospectos">
                                                                    <a @click="abrirModal('B', asesor.tipoB)" href="#">{{asesor.b}}</a>
                                                                </td>
                                                                <td class="td2 table-success" title="Ver Prospectos">
                                                                    <a @click="abrirModal('C', asesor.tipoC)" href="#">{{asesor.c}}</a>
                                                                </td>
                                                                <td class="td2 table-dark" title="Ver Prospectos">
                                                                    <a @click="abrirModal('No viable', asesor.tipoNV)" href="#">{{asesor.nv}}</a>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </div>

                                </div>


                            </div>

                        </div>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>

            <ModalComponent v-if="modal == 1"
                @closeModal="modal = 0"
                :titulo="tituloModal"
            >
                <template v-slot:body>
                    <RowModal :clsRow1="'col-md-12'">
                        <TableComponent :cabecera="['Prospecto', 'Proyecto de interes']">
                            <template v-slot:tbody>
                                <tr v-for="c in prospectos" :key="c.id">
                                    <td class="td2" v-text="c.nombre+' '+c.apellidos"></td>
                                    <td class="td2" v-text="c.proyecto"></td>
                                    <td>
                                        <button v-if="c.obs.length" class="btn btn-scarlet"
                                            @click="verObs(c.obs)" title="Ver Observaciones"
                                        >
                                            <i class="fa fa-address-book" aria-hidden="true"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </TableComponent>
                    </RowModal>
                </template>
            </ModalComponent>

            <ModalComponent v-if="modal == 2"
                @closeModal="modal = 0"
                :titulo="tituloModal"
            >
                <template v-slot:body>
                    <RowModal :clsRow1="'col-md-12'">
                        <TableComponent :cabecera="['Prospecto', 'Fecha de retiro']">
                            <template v-slot:tbody>
                                <tr v-for="c in prospectos" :key="c.id">
                                    <td class="td2" v-text="c.c_nombre+' '+c.c_apellidos"></td>
                                    <td class="td2" v-text="c.created_at"></td>
                                </tr>
                            </template>
                        </TableComponent>
                    </RowModal>
                </template>
            </ModalComponent>


            <ModalComponent v-if="modalObs"
                @closeModal="modalObs = 0, modal = 1"
                :titulo="'Observaciones'"
            >
                <template v-slot:body>
                    <RowModal :clsRow1="'col-md-12'">
                        <TableComponent :cabecera="['Usuario', 'Comentario','']">
                            <template v-slot:tbody>
                                <tr v-for="o in obs" :key="o.id">
                                    <td class="td2" v-text="o.usuario"></td>
                                    <td v-text="o.comentario"></td>
                                    <td class="td2" v-text="o.created_at"></td>
                                </tr>
                            </template>
                        </TableComponent>
                    </RowModal>
                </template>
            </ModalComponent>

        </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    import ModalComponent from '../Componentes/ModalComponent.vue';
    import TableComponent from '../Componentes/TableComponent.vue';
    import RowModal from '../Componentes/ComponentesModal/RowModalComponent.vue'
    export default {
        components:{
            ModalComponent,
            TableComponent,
            RowModal,
        },
        data(){
            return{

                arrayLeads : [],
                arrayFraccionamientos : [],
                arrayAsesores : [],
                arrayVendedores : [],
                listado:0,
                arrayClasificacion: [],
                modal:0,
                modalObs:0,
                tituloModal: '',
                prospectos:[],
                obs:[]
            }
        },
        computed:{
        },
        methods : {
            /**Metodo para mostrar los registros */
            listarReporte(lis){
                let me = this;
                me.arrayVendedores = [];
                const url = '/reportes/prospectos?listado='+lis;

               axios.get(url).then(function (response) {
                    const respuesta = response.data;
                    me.arrayVendedores = respuesta.vendedores;
                    me.arrayVendedores.sort((b, a) => a.dif15 - b.dif15);

                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            getPorClasificacion(){
                let me = this;
                me.arrayClasificacion = [];
                const url = '/reportes/reporteAsesoresClasificacion';

                axios.get(url).then(function (response) {
                    const respuesta = response.data;
                    me.arrayClasificacion = respuesta;
                    me.arrayClasificacion.sort((b, a) => a.a - b.a);

                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },

            abrirModal(tipo,data){
                this.prospectos = data;
                if(tipo === 'retirados'){
                    this.modal = 2
                    this.tituloModal = 'Prospectos retirados';
                }
                else{
                    this.modal = 1;
                    this.tituloModal = 'Prospectos tipo: '+tipo;
                    this.modalObs = 0;
                }
            },

            verObs(data){
                this.modalObs = 1;
                this.obs = data;
                this.modal = 0;
            }

        },
        mounted() {
            this.listarReporte(this.listado);
            this.getPorClasificacion();
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
