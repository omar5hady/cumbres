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
                        <i class="fa fa-align-justify"></i>Equipamientos Solicitados
                    </div>

                    <div class="info-center" v-if="loading">
                        <LoadingComponentVue></LoadingComponentVue>
                    </div>
                    <div class="card-body" v-else>
                        <div class="form-group row">
                            <select class="form-control col-md-4"
                                v-model="b_proyecto"
                                @change="$root.selectEtapa(b_proyecto),
                                        $root.selectModelo(b_proyecto)"
                            >
                                <option value="">Fraccionamiento</option>
                                <option
                                    v-for="proyecto in $root.$data.proyectos"
                                    :key="proyecto.id"
                                    :value="proyecto.id"
                                    v-text="proyecto.nombre"
                                ></option>
                            </select>
                            <select class="form-control col-md-3" v-model="b_etapa">
                                <option value="">Etapa</option>
                                <option
                                    v-for="etapa in $root.$data.etapas"
                                    :key="etapa.id"
                                    :value="etapa.id"
                                    v-text="etapa.num_etapa"
                                ></option>
                            </select>
                            <select
                                class="form-control col-md-4"
                                v-model="b_modelo"
                            >
                                <option value="">Modelo</option>
                                <option
                                    v-for="modelo in $root.$data.modelos"
                                    :key="modelo.id"
                                    :value="modelo.id"
                                    v-text="modelo.nombre"
                                ></option>
                            </select>

                            <input type="text" class="col-md-4 form-control"
                                v-model="b_manzana" placeholder="Manzana"
                            />
                            <input type="text" class="col-md-3 form-control"
                                v-model="b_lote" placeholder="Num. Lote"
                            />

                            <div class="input-group">
                                <select class="col-md-4 form-control" v-model="b_status">
                                    <option value="">Status</option>
                                    <option value="0">Rechazado</option>
                                    <option value="1">Pendiente</option>
                                    <option value="2">En proceso de colocación</option>
                                    <option value="3">En Revisión</option>
                                    <option value="4">Aprobado</option>
                                    <option value="5">Cancelado</option>

                                </select>
                                <button type="submit" class="btn btn-primary" @click="getSolicitudes(1)">
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                        <TableComponent :cabecera="[
                                '','Proyecto','Etapa','Manzana','Lote','Modelo','Equipamiento',
                                'Anticipo','Comp. de pago 1','Fecha programada','Fecha fin de instalación',
                                '# Dias transcurridos','Status','Total pagado','Pendiente','Liquidacion',
                                'Comp. de pago 2','Imprimir Recepción','Observaciones'
                        ]">
                            <template v-slot:tbody>
                                <tr v-for="s in arraySolicitudes.data" :key="s.id">
                                    <td></td>
                                    <td class="td2" v-text="s.proyecto"></td>
                                    <td class="td2" v-text="s.etapa"></td>
                                    <td class="td2" v-text="s.manzana"></td>
                                    <td class="td2">{{ (s.sublote) ? `${s.num_lote}-${s.sublote}` : s.num_lote }}</td>
                                    <td class="td2" v-text="s.modelo"></td>
                                    <td class="td2" v-text="s.equipamiento"></td>

                                    <template>
                                        <td class="td2">
                                            <p v-if="s.fecha_anticipo"
                                                v-text=" this.moment(s.fecha_anticipo).locale('es').format('DD/MMM/YYYY') + ': '+ '$'+ $root.formatNumber(s.anticipo)"></p>
                                            <p v-else v-text="'Sin anticipo'"></p>
                                        </td>
                                        <td class="text-center">
                                            <a v-if="s.comp_pago_1" :href="s.comp_pago_1" class="btn btn-sm btn-primary" title="Descargar archivo">
                                                <i class="fa fa-cloud-download"></i>
                                            </a>
                                            <p v-else>
                                                Sin Comprobante
                                            </p>
                                        </td>
                                    </template>

                                    <td @click="abrirModal('colocacion', s)" class="td2">
                                        <a v-if="s.fecha_colocacion" href="#" v-text=" this.moment(s.fecha_colocacion).locale('es').format('DD/MMM/YYYY')"></a>
                                        <a v-else href="#" v-text="'Sin fecha de colocación'"></a>
                                    </td>

                                    <td class="td2" @click="abrirModal('instalacion', s)">
                                        <a v-if="s.fin_instalacion" href="#" v-text=" this.moment(s.fin_instalacion).locale('es').format('DD/MMM/YYYY')"></a>
                                        <a v-else href="#" v-text="'Sin fecha de finalización'"></a>
                                    </td>
                                    <td class="text-center">
                                        <span v-if="s.dias_rev >= 1 || s.dias_rev <= -1" v-text="s.dias_rev"></span>
                                        <span v-else>0</span>
                                    </td>
                                    <td  class="td2">
                                        <span v-if="s.status == '0'" class="badge badge-warning">Rechazado</span>
                                        <span v-if="s.status == '1'" class="badge badge-primary">Pendiente</span>
                                        <span v-if="s.status == '2'" class="badge badge-primary">En proceso de colocación</span>
                                        <span v-if="s.status == '3'" class="badge badge-primary">En Revisión</span>
                                        <span v-if="s.status == '4'" class="badge badge-success">Aprobado</span>
                                        <span v-if="s.status == '5'" class="badge badge-danger">Cancelado</span>
                                    </td>
                                    <td class="td2" v-text="'$'+$root.formatNumber(s.anticipo + s.liquidacion)"></td>
                                    <td class="td2" v-text="'$'+$root.formatNumber(s.costo - s.anticipo - s.liquidacion)"></td>

                                    <td v-if="s.fecha_liquidacion" class="td2" v-text="this.moment(s.fecha_liquidacion).locale('es').format('DD/MMM/YYYY')+ ': '+ '$'+formatNumber(s.liquidacion)"></td>
                                    <td v-else class="td2" v-text="'Sin liquidar'"></td>

                                    <td class="text-center">
                                        <a v-if="s.comp_pago_2" :href="'/equipamiento/indexHistorial/downloadFile2/'+s.comp_pago_2" class="btn btn-sm btn-primary" title="Descargar archivo">
                                            <i class="fa fa-cloud-download"></i>
                                        </a>
                                        <i v-else class="fa fa-cloud" title="Sin archivo disponible"></i>
                                    </td>

                                    <td><!--Imprimir Recepción-->
                                        Imprimir recepcion
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-info" @click="abrirModal('obs',s)" title="Ver Observaciones">
                                            <i class="icon-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </TableComponent>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item"
                                    v-if="arraySolicitudes.current_page > 5"
                                    @click="getSolicitudes(1)"
                                >
                                    <a class="page-link" href="#">Inicio</a>
                                </li>
                                <li class="page-item"
                                    v-if="arraySolicitudes.current_page > 1"
                                    @click="getSolicitudes(arraySolicitudes.current_page - 1)"
                                >
                                    <a class="page-link" href="#">Ant</a>
                                </li>

                                <li class="page-item"
                                    v-if="arraySolicitudes.current_page - 3 >= 1"
                                    @click="getSolicitudes(arraySolicitudes.current_page - 3)"
                                >
                                    <a class="page-link" href="#"
                                        v-text="arraySolicitudes.current_page - 3"
                                    ></a>
                                </li>
                                <li
                                    class="page-item" v-if="arraySolicitudes.current_page - 2 >= 1"
                                    @click="getSolicitudes(arraySolicitudes.current_page - 2)"
                                >
                                    <a class="page-link" href="#"
                                        v-text="arraySolicitudes.current_page - 2"
                                    ></a>
                                </li>
                                <li class="page-item" v-if="arraySolicitudes.current_page - 1 >= 1"
                                    @click="getSolicitudes(arraySolicitudes.current_page - 1)"
                                >
                                    <a class="page-link" href="#"
                                        v-text="arraySolicitudes.current_page - 1"
                                    ></a>
                                </li>
                                <li class="page-item active">
                                    <a class="page-link" href="#"
                                        v-text="arraySolicitudes.current_page"
                                    ></a>
                                </li>
                                <li class="page-item"
                                    v-if="arraySolicitudes.current_page + 1 <= arraySolicitudes.last_page"
                                >
                                    <a class="page-link" href="#"
                                        @click="getSolicitudes(arraySolicitudes.current_page + 1)"
                                        v-text="arraySolicitudes.current_page + 1"
                                    ></a>
                                </li>
                                <li class="page-item"
                                    v-if="arraySolicitudes.current_page + 2 <=arraySolicitudes.last_page"
                                >
                                    <a class="page-link" href="#"
                                        @click="getSolicitudes(arraySolicitudes.current_page + 2)"
                                        v-text="arraySolicitudes.current_page + 2"
                                    ></a>
                                </li>
                                <li class="page-item"
                                    v-if="arraySolicitudes.current_page + 3 <= arraySolicitudes.last_page"
                                >
                                    <a class="page-link" href="#"
                                        @click="getSolicitudes(arraySolicitudes.current_page + 3)"
                                        v-text="arraySolicitudes.current_page + 3"
                                    ></a>
                                </li>

                                <li class="page-item"
                                    v-if="arraySolicitudes.current_page < arraySolicitudes.last_page"
                                    @click="getSolicitudes(arraySolicitudes.current_page + 1)"
                                >
                                    <a class="page-link" href="#">Sig</a>
                                </li>
                                <li class="page-item"
                                    v-if="arraySolicitudes.current_page < 5 && arraySolicitudes.last_page > 5"
                                    @click="getSolicitudes(arraySolicitudes.last_page)"
                                >
                                    <a class="page-link" href="#">Ultimo</a>
                                </li>
                            </ul>
                        </nav>
                    </div>

                    <ModalComponent v-if="modal == 2"
                        :titulo="tituloModal"
                        @closeModal="cerrarModal()"
                    >
                        <template v-slot:body>
                            <!-- Seccion para buscar el lote -->
                            <template v-if="tipoAccion == 1">
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Fecha de finalización</label>
                                    <div class="col-md-4">
                                        <input type="date" v-model="datosSolicitud.fin_instalacion" class="form-control">
                                    </div>
                                </div>
                            </template>
                            <template v-if="tipoAccion == 2">
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Fecha de colocación</label>
                                    <div class="col-md-4">
                                        <input type="date" v-model="datosSolicitud.fecha_colocacion" class="form-control">
                                    </div>
                                </div>
                            </template>
                             <div class="form-group row">
                                <label class="col-md-2 form-control-label" for="text-input">Observación</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" cols="40" rows="3" v-model="observacion"></textarea>
                                </div>
                            </div>
                        </template>
                        <template v-slot:buttons-footer>
                            <button type="button"
                                class="btn btn-success" v-if="tipoAccion == 2" @click="updateSolicitud('updateColocacion')">Guardar cambios
                            </button>
                            <button type="button"
                                class="btn btn-success" v-if="tipoAccion == 1" @click="updateSolicitud('updateFinInst')">Guardar cambios
                            </button>
                        </template>
                    </ModalComponent>

                    <ModalComponent v-if="modal == 3"
                        :titulo="tituloModal"
                        @closeModal="cerrarModal()"
                    >
                        <template v-slot:body>
                            <!-- Seccion para buscar el lote -->
                            <template>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Nueva observación</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="observacion" class="form-control">
                                    </div>

                                    <div class="col-md-3">
                                        <button class="btn btn-primary" @click="storeObservacion()">Guardar</button>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <TableComponent :cabecera="['Usuario','Observación','Fecha']">
                                            <template v-slot:tbody>
                                                <tr v-for="obs in arrayObs" :key="obs.id">
                                                    <td>{{obs.usuario}}</td>
                                                    <td>{{obs.observacion}}</td>
                                                    <td>{{obs.created_at}}</td>
                                                </tr>
                                            </template>
                                        </TableComponent>
                                    </div>
                                </div>
                            </template>
                        </template>
                    </ModalComponent>

                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
     </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    import ModalComponent from '../Componentes/ModalComponent.vue';
    import LoadingComponentVue from '../Componentes/LoadingComponent.vue'
    import TableComponent from '../Componentes/TableComponent.vue';

    export default {
        components:{
            TableComponent,
            ModalComponent,
            LoadingComponentVue
        },
        data(){
            return{
                arrayManzanas: [],
                arrayLotes: [],
                arraySolicitudes: [],
                arrayObs: [],

                b_etapa: '',
                b_manzana: '',
                b_lote: '',
                b_modelo: '',
                b_proyecto: '',
                b_status: '',
                loading: false,

                modal: 0,
                tituloModal: '',
                tipoAccion: 0,

                datosSolicitud: {},
                buscadores: {},
                observacion: ''
            }
        },
        computed:{
        },


        methods : {
            getSolicitudes(page){
                let me = this;

                me.arraySolicitudes=[];
                var url = '/equip-lotes?page='+page
                    + '&b_proyecto=' + me.b_proyecto
                    + '&b_etapa=' + me.b_etapa
                    + '&b_modelo=' + me.b_modelo
                    + '&b_lote=' + me.b_lote
                    + '&b_status=' + me.b_status
                ;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arraySolicitudes = respuesta;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            storeObservacion(){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.post('/equip-lotes/storeObservacion',{
                    'solicitud_id': this.datosSolicitud.id,
                    'observacion': this.observacion
                }).then(function (response){
                    me.getSolicitudes(1);
                    me.cerrarModal();
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Observación guardada correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },
            updateSolicitud(accion){
                let me = this;
                axios.put(`/equip-lotes/${me.datosSolicitud.id}`,{
                        'solicitud': me.datosSolicitud,
                        'accion': accion,
                        'observacion': this.observacion
                    }).then(function (response){
                        me.getSolicitudes();
                        me.cerrarModal();
                        const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });

                        toast({
                            type: 'success',
                            title: 'Cambios guardados correctamente.'
                        })
                    }).catch(function (error){
                        console.log(error);
                        me.getSolicitudes();
                        me.cerrarModal();
                });
            },

            selectManzana(etapa){
                let me = this;
                me.arrayManzanas=[];
                var url = '/select_manzanas_disp?buscar=' + etapa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                     me.arrayManzanas = respuesta.lotes_manzanas;

                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectLotes(manzana,etapa,fraccionamiento){
                let me = this;
                me.arrayLotes=[];
                var url = '/select_lotes_disp?buscar=' + manzana + '&buscar2=' + etapa + '&buscar3=' + fraccionamiento;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                     me.arrayLotes = respuesta.lotes_disp;

                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cerrarModal(){
                this.getSolicitudes(this.arraySolicitudes.current_page)
                this.modal = 0;
                this.tituloModal = '';
                this.datosSolicitud = {};
                this.buscadores = {};
                this.arrayManzanas = [];
                this.arrayLotes = [];
                this.observacion = '';
            },
            abrirModal(accion,data={}){
                this.datosSolicitud = data;
                switch(accion){
                    case 'instalacion':{
                        this.modal = 2;
                        this.tituloModal = 'Fin de Instalación';
                        this.tipoAccion = 1;
                        break;
                    }
                    case 'colocacion':{
                        this.modal = 2;
                        this.tipoAccion = 'Programar fecha de colocación';
                        this.tipoAccion = 2;
                        break;
                    }
                    case 'obs':{
                        this.modal = 3;
                        this.arrayObs = this.datosSolicitud.obs;
                        break;
                    }
                }
            }
        },

        mounted() {
            this.getSolicitudes(1);
            this.$root.selectFraccionamientos();
        }
    }
</script>
<style>

    .div-error{
        display:flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
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

    .info-center{
        display: flex;
        justify-content: center;
        width: 100% !important;
    }
</style>
