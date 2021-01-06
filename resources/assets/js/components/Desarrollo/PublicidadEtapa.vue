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
                                        <option value="f_ini">Fecha de inicio</option>
                                        <option value="f_fin">Fecha de termino</option>
                                    </select>
                                    <input type="date" v-if="criterio=='f_ini'" v-model="buscar" @keyup.enter="listarEtapa(1,buscar,buscar2,criterio)" class="form-control col-md-6" placeholder="fecha inicio" >
                                    <input type="date" v-if="criterio=='f_ini'" v-model="buscar2"  @keyup.enter="listarEtapa(1,buscar,buscar2,criterio)" class="form-control col-md-6" placeholder="fecha fin" >
                                    <input type="date" v-if="criterio=='f_fin'" v-model="buscar" @keyup.enter="listarEtapa(1,buscar,buscar2,criterio)" class="form-control" placeholder="fecha inicio" >
                                    <input type="date" v-if="criterio=='f_fin'" v-model="buscar2"  @keyup.enter="listarEtapa(1,buscar,buscar2,criterio)" class="form-control" placeholder="fecha fin" >
                                    <input type="text" v-if="criterio=='fraccionamientos.nombre'"  v-model="buscar" @keyup.enter="listarEtapa(1,buscar,buscar2,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarEtapa(1,buscar,buscar2,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Etapa</th>
                                        <th>Fraccionamiento</th>
                                        <th>Fecha de inicio </th>
                                        <th>Fecha de termino</th>
                                        <th>Reglamento</th>
                                        <th>Plantilla para carta de servicios</th>
                                        <th>Costo de mantenimiento Casa</th>
                                        <th>Costo de mantenimiento Lote</th>
                                        <th>Empresa(s) de telecomunicacion</th>
                                        <th>Empresa(s) de telecomunicacion satelital</th>
                                        <th>Plantilla servicios de telecomunicacion</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
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
                                        <td class="td2" style="width:7%" v-if = "etapa.archivo_reglamento"><a class="btn btn-success btn-sm" v-bind:href="'/downloadReglamento/'+etapa.archivo_reglamento"><i class="fa fa-download fa-spin"></i></a></td>
                                        <td class="td2" v-else></td>
                                        <td class="td2"  v-if="etapa.plantilla_carta_servicios || etapa.plantilla_carta_servicios2">
                                            
                                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">Plantilla de servicio</a>
                                                <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
                                                    <a v-if="etapa.plantilla_carta_servicios" class="btn btn-success btn-sm" v-bind:href="'/downloadPlantilla/cartaServicios/'+etapa.plantilla_carta_servicios"><i class="fa fa-download"> Casa</i></a>
                                                    <a v-if="etapa.plantilla_carta_servicios2" class="btn btn-primary btn-sm" v-bind:href="'/downloadPlantilla/cartaServicios2/'+etapa.plantilla_carta_servicios2"><i class="fa fa-download"> Lote</i></a>
                                                </div>
                                        </td>
                                        <td class="td2" v-else></td>
                                        <td class="td2" v-text="'$' + etapa.costo_mantenimiento"></td>
                                        <td class="td2" v-text="'$' + etapa.costo_mantenimiento2"></td>
                                        <td class="td2" v-text="etapa.empresas_telecom"></td>
                                        <td class="td2" v-text="etapa.empresas_telecom_satelital"></td>
                                        <td class="td2" v-if = "etapa.plantilla_telecom"><a class="btn btn-success btn-sm" v-bind:href="'/downloadPlantilla/ServiciosTelecom/'+etapa.plantilla_telecom"><i class="fa fa-download fa-spin"></i></a></td>
                                        <td class="td2" v-else></td>
                                    </tr>                               
                                </tbody>
                            </table>
                        </div>
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
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div v-if="tipoAccion != 2" class="modal-body">

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Numero de etapa</label>
                                    <div class="col-md-4">
                                        <input type="text" readonly v-model="num_etapa" class="form-control" placeholder="# de etapa">
                                    </div>
                                </div>

                                <div>
                                    <form  method="post" @submit="formSubmitServicios" enctype="multipart/form-data">

                                        <strong>Sube aqui la plantilla para la carta de los servicios Casa <u>794 x 986</u></strong>

                                        <input type="file" accept="image/*" class="form-control" v-on:change="onImageChangeServicios">
                                        <br/>
                                        <button type="submit" class="btn btn-success">Cargar</button>
                                   </form>

                                    <br/>   

                                    <form  method="post" @submit="formSubmitServiciosL" enctype="multipart/form-data">

                                        <strong>Sube aqui la plantilla para la carta de los servicios Lote<u>794 x 986</u></strong>

                                        <input type="file" accept="image/*" class="form-control" v-on:change="onImageChangeServiciosL">
                                        <br/>
                                        <button type="submit" class="btn btn-success">Cargar</button>
                                   </form>

                                    <br/>   

                                    <form  method="post" @submit="formSubmitTelecom" enctype="multipart/form-data">

                                    <strong>Sube aqui la plantilla para los servicios de telecomunicacion <u>794 x 986</u></strong>

                                    <input type="file" accept="image/*" class="form-control" v-on:change="onImageChangeTelecom">
                                    <br/>
                                    <button type="submit" class="btn btn-success">Cargar</button>
                                    </form>

                                </div>
                            <br>
                        <div v-if="tipoAccion != 2">
                             <form  method="post" @submit="formSubmitReglamento" enctype="multipart/form-data">

                                    <strong>Sube aqui el reglamento para esta etapa</strong>

                                    <input type="file" class="form-control" v-on:change="onImageChangeReglamento">
                                    <br/>
                                    <button type="submit" class="btn btn-success">Cargar</button>
                            </form>
                        </div>
                        </div>       
                    
                        <div v-if="tipoAccion == 2" class="modal-body">
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
                      
                          </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" class="btn btn-info" v-if="tipoAccion==2" @click="registrarCostosMantenimiento()">Guardar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->

            

        </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    export default {
        data(){
            return{
                proceso : false,
                id:0,
                num_etapa : 0,
                archivo_plantilla_servicio: '',
                archivo_plantilla_servicio2: '',
                archivo_reglamento: '',
                costo_mantenimiento: 0,
                costo_mantenimiento2: 0,
                empresas_telecom: '',
                empresas_telecom_satelital: '',
                archivo_plantilla_telecom: '',
                arrayEtapa : [],
                modal : 0,
                tituloModal : '',
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

            onImageChangeServicios(e){
                console.log(e.target.files[0]);
                this.archivo_plantilla_servicios = e.target.files[0];
            },

            formSubmitServicios(e) {
                e.preventDefault();
                let currentObj = this;
            
                let formData = new FormData();
                formData.append('plantilla_carta_servicios', this.archivo_plantilla_servicios);
                let me = this;
                axios.post('/formSubmitCartaServicios/'+this.id, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Archivo guardado correctamente',
                        showConfirmButton: false,
                        timer: 2000
                        })
                    me.cerrarModal4();
                    me.listarEtapa(1,'','','fraccionamiento.nombre');

                }).catch(function (error) {
                    currentObj.output = error;

                });

            },

            onImageChangeServiciosL(e){
                console.log(e.target.files[0]);
                this.archivo_plantilla_servicios2 = e.target.files[0];
            },

            formSubmitServiciosL(e) {
                e.preventDefault();
                let currentObj = this;
            
                let formData = new FormData();
                formData.append('plantilla_carta_servicios2', this.archivo_plantilla_servicios2);
                let me = this;
                axios.post('/formSubmitCartaServicios2/'+this.id, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Archivo guardado correctamente',
                        showConfirmButton: false,
                        timer: 2000
                        })
                    me.cerrarModal4();
                    me.listarEtapa(1,'','','fraccionamiento.nombre');

                }).catch(function (error) {
                    currentObj.output = error;

                });

            },

            onImageChangeReglamento(e){

                console.log(e.target.files[0]);

                this.archivo_reglamento = e.target.files[0];

            },

            formSubmitReglamento(e) {

                e.preventDefault();

                let currentObj = this;
            
                let formData = new FormData();
           
                formData.append('archivo_reglamento', this.archivo_reglamento);
                let me = this;
                axios.post('/formSubmitReglamento/'+this.id, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Archivo guardado correctamente',
                        showConfirmButton: false,
                        timer: 2000
                        })
                    me.cerrarModal4();
                    me.listarEtapa(1,'','','fraccionamiento.nombre');

                }).catch(function (error) {
                    currentObj.output = error;
                });

            },

            onImageChangeTelecom(e){
                console.log(e.target.files[0]);
                this.archivo_plantilla_telecom = e.target.files[0];
            },

            formSubmitTelecom(e) {
                e.preventDefault();
                let currentObj = this;
            
                let formData = new FormData();
                formData.append('plantilla_telecom', this.archivo_plantilla_telecom);
                let me = this;
                axios.post('/formSubmitTelecom/'+this.id, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Archivo guardado correctamente',
                        showConfirmButton: false,
                        timer: 2000
                        })
                    me.cerrarModal4();
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
                    
                    
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarEtapa(1,'','','fraccionamiento.nombre');

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
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.num_etapa = '';
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
