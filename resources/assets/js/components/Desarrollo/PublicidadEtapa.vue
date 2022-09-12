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
                        <i class="fa fa-align-justify"></i> Documentos anexos
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-4" v-model="criterio" @click="limpiarBusqueda()">
                                        <option value="fraccionamientos.nombre">Fraccionamiento</option>
                                        <option value="num_etapa">Etapa</option>
                                    </select>
                                    <input type="text"  v-model="buscar" @keyup.enter="listarEtapa(1,buscar,buscar2,criterio)" class="form-control" placeholder="Texto a buscar">
                                </div>
                                <div class="input-group">
                                    <button type="submit" @click="listarEtapa(1,buscar,buscar2,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <TableComponent
                            :cabecera="['','Etapa','Fraccionamiento','Fecha de inicio ','Fecha de termino',
                            'Reglamento','Plantilla para carta de servicios','Costo de mantenimiento Casa',
                            'Costo de mantenimiento Lote','Empresa(s) de telecomunicacion',
                            'Empresa(s) de telecomunicacion satelital',
                            'Plantilla servicios de telecomunicacion',
                        ]">
                            <template v-slot:tbody>
                                <tr v-for="etapa in arrayEtapa" :key="etapa.id">
                                    <td class="td2" style="width:10%">
                                        <button type="button" @click="abrirModal('etapa','subirArchivo',etapa)" class="btn btn-info btn-sm">
                                        <i class="icon-cloud-upload"></i>
                                        </button> &nbsp;
                                        <button title="Asignar costo de mantenimiento" type="button" @click="abrirModal('etapa','costo',etapa)" class="btn btn-warning btn-sm">
                                        <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                    </td>
                                    <td class="td2" v-text="etapa.num_etapa"></td>
                                    <td class="td2" v-text="etapa.fraccionamiento"></td>
                                    <td class="td2" v-text="etapa.f_ini"></td>
                                    <td class="td2" v-text="etapa.f_fin"></td>
                                    <td class="td2" style="width:7%" v-if = "etapa.archivo_reglamento"><a target="_blank" class="btn btn-success btn-sm" v-bind:href="'/downloadReglamento/'+etapa.archivo_reglamento"><i class="fa fa-download fa-spin"></i></a></td>
                                    <td class="td2" v-else></td>
                                    <td class="td2"  v-if="etapa.plantilla_carta_servicios || etapa.plantilla_carta_servicios2">
                                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">Plantilla de servicio</a>
                                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
                                            <a target="_blank" v-if="etapa.plantilla_carta_servicios" class="btn btn-success btn-sm" v-bind:href="'/downloadPlantilla/cartaServicios/'+etapa.plantilla_carta_servicios"><i class="fa fa-download"> Casa</i></a>
                                            <a target="_blank" v-if="etapa.plantilla_carta_servicios2" class="btn btn-primary btn-sm" v-bind:href="'/downloadPlantilla/cartaServicios2/'+etapa.plantilla_carta_servicios2"><i class="fa fa-download"> Lote</i></a>
                                        </div>
                                    </td>
                                    <td class="td2" v-else></td>
                                    <td class="td2" v-text="'$' + etapa.costo_mantenimiento"></td>
                                    <td class="td2" v-text="'$' + etapa.costo_mantenimiento2"></td>
                                    <td class="td2" v-text="etapa.empresas_telecom"></td>
                                    <td class="td2" v-text="etapa.empresas_telecom_satelital"></td>
                                    <td class="td2" v-if = "etapa.plantilla_telecom"><a target="_blank" class="btn btn-success btn-sm" v-bind:href="'/downloadPlantilla/ServiciosTelecom/'+etapa.plantilla_telecom"><i class="fa fa-download fa-spin"></i></a></td>
                                    <td class="td2" v-else></td>
                                </tr>
                            </template>
                        </TableComponent>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,buscar2,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,buscar2,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,buscar2,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <ModalComponent
                :titulo="tituloModal"
                :size="'modal-md'"
                @closeModal="cerrarModal()"
                v-if="modal"

            >
                <template
                    v-slot:body>
                    <template  v-if="tipoAccion != 2">
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Numero de etapa</label>
                            <div class="col-md-5">
                                <input type="text" readonly v-model="num_etapa" class="form-control" placeholder="# de etapa">
                            </div>
                        </div>
                        <select class=" form-control " @click="limpiaInputArchivos()" v-model="formActive">
                            <option class=" form-control " value="">Seleccione archivo a subir.</option>
                            <option class=" form-control " value="casa">Plantilla para la carta de los servicios Casa. </option>
                            <option class=" form-control " value="lote">Plantilla para la carta de los servicios Lote. </option>
                            <option class=" form-control " value="telecom">Plantilla para los servicios de telecomunicacion. </option>
                            <option class=" form-control " value="reglamento">Reglamento para esta etapa</option>
                        </select>

                        <div v-if="formActive" >
                            <div class="contenedor-modal">

                                <div class="form-sub">
                                    <form  method="post" @submit="formSubmit" enctype="multipart/form-data">
                                         <div class="form-opc">
                                            <label v-if="formActive == 'casa'" class="tite-form">Sube aqui la plantilla para la carta de los servicios Casa  <u>794 x 986</u></label>
                                            <label v-if="formActive == 'lote'" class="tite-form">Sube aqui la plantilla para la carta de los servicios Lote  <u>794 x 986</u></label>
                                            <label v-if="formActive == 'telecom'" class="tite-form">Sube aqui la plantilla para los servicios de telecomunicacion</label>
                                            <label v-if="formActive == 'reglamento'" class="tite-form">Sube aqui el reglamento para esta etapa</label>
                                            <div class="form-archivo">
                                                <input ref="imageSelectorArchivo" v-show="false" type="file"  v-on:change="onImageChange">

                                                                <label class="label-button"
                                                                    @click="onSelectArchivo"
                                                                    >
                                                                    Sube aqui la plantilla
                                                                    <br>
                                                                <i class="fa fa-upload" style=" justify-content: center; align-self: center;"></i>
                                                                </label>
                                                    <div v-if="nom_archivo=='Seleccione Archivo'" class="text-file-hide"   v-text="nom_archivo" ></div>

                                                    <div v-else class="text-file"  v-text="nom_archivo"></div>
                                            </div>
                                                <div class="boton-modal">
                                                    <button v-show="nom_archivo!='Seleccione Archivo'" type="submit"  class="btn btn-success boton-modal">Subir Archivo</button>
                                                </div>
                                         </div>

                                    </form>

                                </div>



                            </div>


                        </div>
                            <br/>


                    </template>
                    <template v-if="tipoAccion == 2"
                    >
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Costo mantenimiento Casa</label>
                            <div class="col-md-4">
                                <input type="text" v-on:keypress="isNumber($event)" v-model="costo_mantenimiento" class="form-control" placeholder="$">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Costo mantenimiento Lote</label>
                            <div class="col-md-4">
                                <input type="text" v-on:keypress="isNumber($event)" v-model="costo_mantenimiento2" class="form-control" placeholder="$">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Empresa(s) de telecomunicacion</label>
                            <div class="col-md-9">
                                <input type="text" v-model="empresas_telecom" class="form-control" placeholder="Empresa 1, Empresa 2, Empresa 3, Empresa 4">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Empresa(s) de telecomunicacion satelital</label>
                            <div class="col-md-9">
                                <input type="text" v-model="empresas_telecom_satelital" class="form-control" placeholder="Empresa 1, Empresa 2, Empresa 3, Empresa 4">
                            </div>
                        </div>
                    </template>
                </template>

                <template v-if="tipoAccion==2" v-slot:buttons-footer>
                    <button type="button" class="btn btn-info" @click="registrarCostosMantenimiento()">Guardar</button>
                </template>
            </ModalComponent>
            <!--Fin del modal-->
        </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
import ModalComponent from '../Componentes/ModalComponent.vue'
import TableComponent from '../Componentes/TableComponent.vue'
import vSelect from 'vue-select';
    export default {
        components:{
            ModalComponent,
            TableComponent
        },
        data(){
            return{
                proceso : false,
                id:0,
                num_etapa : 0,
                archivo:'',
                costo_mantenimiento: 0,
                costo_mantenimiento2: 0,
                empresas_telecom: '',
                empresas_telecom_satelital: '',
                arrayEtapa : [],
                modal : 0,
                tituloModal : '',

                formActive:'',
                nom_archivo:'Seleccione Archivo',
                tipoAccion: 0,
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'fraccionamientos.nombre',
                buscar : '',
                buscar2: '',
                arrayFraccionamientos : [],

            }
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber:function(){
                if(!this.pagination.to){
                    return [];
                }

                var from = this.pagination.current_page - this.offset;
                if(from < 1){
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to){
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            }
        },
        methods : {

            onImageChange(e){
                this.nom_archivo ='Seleccione Archivo'

                this.archivo = e.target.files[0];
                this.nom_archivo = e.target.files[0].name;
            },
            onSelectArchivo(){
                 this.$refs.imageSelectorArchivo.click()
                },

            formSubmit(e) {
                e.preventDefault();
                let currentObj = this;
                let nombre='';
                let url='';

                let formData = new FormData();

                if (this.formActive == 'casa' ) {
                     nombre ='plantilla_carta_servicios'
                     url ='/formSubmitCartaServicios/'
                }
                if (this.formActive == 'lote' ) {
                     nombre ='plantilla_carta_servicios2'
                     url ='/formSubmitCartaServicios2/'
                }
                if (this.formActive == 'telecom' ) {
                     nombre ='plantilla_telecom'
                     url ='/formSubmitTelecom/'
                }
                if (this.formActive == 'reglamento' ) {
                     nombre ='archivo_reglamento'
                     url ='/formSubmitReglamento/'
                }

                formData.append(nombre, this.archivo);
                let me = this;
                axios.post(url+this.id, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Archivo guardado correctamente',
                        showConfirmButton: false,
                        timer: 2000
                        })
                    me.cerrarModal();
                    me.listarEtapa(1,'','','fraccionamiento.nombre');

                }).catch(function (error) {
                    currentObj.output = error;

                });

            },




             registrarCostosMantenimiento(){
                if(this.proceso==true){
                    return;
                }

                this.proceso=true;
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/etapas/costoMantenimiento/registrar/'+this.id,{
                    'costo_mantenimiento': this.costo_mantenimiento,
                    'costo_mantenimiento2': this.costo_mantenimiento2,
                    'empresas_telecom': this.empresas_telecom,
                    'empresas_telecom_satelital': this.empresas_telecom_satelital,
                    'id' : this.id
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarEtapa(this.pagination.current_page,'','','fraccionamiento.nombre');

                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Asignaciones realizadas correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },



            /**Metodo para mostrar los registros */
            listarEtapa(page, buscar, buscar2, criterio){
                let me = this;
                var url = '/etapa?page=' + page + '&buscar=' + buscar + '&buscar2=' + buscar2 + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEtapa = respuesta.etapas.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            cambiarPagina(page, buscar, buscar2, criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarEtapa(page,buscar,buscar2,criterio);
            },
            isNumber: function(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                    evt.preventDefault();;
                } else {
                    return true;
                }
            },
             limpiarBusqueda(){
                let me=this;
                me.buscar= "";
                me.buscar2="";
            },
            limpiaInputArchivos(){
                this.archivo='';
                this.nom_archivo='Seleccione Archivo';
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.num_etapa = '';
                this.archivo='';
                this.nom_archivo='Seleccione Archivo';
                this.formActive='';

            },

            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(modelo, accion,data =[]){
                switch(modelo){
                    case "etapa":
                    {
                        switch(accion){
                            case 'subirArchivo':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Subir archivos';
                                this.num_etapa = data['num_etapa'];
                                this.id=data['id'];
                                this.archivo_reglamento=data['archivo_reglamento'];
                                this.archivo_plantilla_servicio=data['plantilla_carta_servicios'];
                                this.archivo_plantilla_telecom=data['plantilla_telecom'];
                                this.tipoAccion = 1;
                                break;
                            }
                             case 'costo':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Asigna un costo de mantenimiento para esta etapa';
                                this.num_etapa = data['num_etapa'];
                                this.id=data['id'];
                                this.costo_matenimiento = data['costo_mantenimiento'];
                                this.costo_mantenimiento2 = data['costo_mantenimiento2'];
                                this.empresas_telecom =data['empresas_telecom'];
                                this.empresas_telecom_satelital =data['empresas_telecom_satelital'];
                                this.tipoAccion = 2;
                                break;
                            }

                        }
                    }
                }
            }
        },
        mounted() {
            this.listarEtapa(1,this.buscar,this.buscar2,this.criterio);
        }
    }
</script>
<style scoped>
    .text-formfile{

        color: grey;
        display:flex;
        padding-top: 13px;
        justify-content: left;

    }
    .contenedor-modal{

        display: block;
        flex-direction: column;

        margin: auto;
        overflow-x: auto;
        width: fit-content;
        max-width: 100%;


    }
    .tite-form{
        background-color: lightgray;
        color: black;
        font-size: 14px;
        padding-bottom: 15px;
        margin-bottom: 15px;

    }

    .label-button{
    border-style: solid;
    cursor:pointer;
    color: #fff;
    background-color: #00ADEF;
    border-color: #00ADEF;
    padding: 10px;
    margin-left: 15px;
    }

    .label-button:hover {
    color: #fff;
    background-color: #1b8eb7;
    border-color: #00b0bb;;

      }
    .form-sub{
        border: 1px solid #c2cfd6;
        margin-top: 20px;
        width: 100%;



    }
    .form-opc{
        display: flex;
        flex-direction: column;


    }
    .form-archivo{
        display: flex;
        flex-direction: row;

        width: 100%;
    }
    .text-file{

        color: rgb(39, 38, 38);
        font-size:12px;
        word-break: break-all;
        font-weight: bold;
        width: 300px;
        padding: 15px;



    }
    .text-file-hide{

        color: rgb(127, 130, 134);
        font-size:13px;
        word-break: break-all;
        font-weight: bold;
        width: 300px;
        padding: 15px;


    }
    .boton-modal{
        margin-top: 15px;
        display: flex;
        flex-direction: row;
        justify-content: center;
    }
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
</style>
