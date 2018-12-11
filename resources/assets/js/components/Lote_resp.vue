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
                        <i class="fa fa-align-justify"></i> Lotes
                        <!--   Boton Nuevo    -->
                        
                        
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-5" v-model="criterio" @click="selectFraccionamientos()">
                                        <option value="lotes.fraccionamiento_id">Proyecto</option>
                                        <option value="modelos.nombre">Modelo</option>
                                        <option value="lotes.calle">Calle</option>
                                    </select>
                                    
                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar" @click="selectEtapa(buscar)" >
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>

                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar2" @keyup.enter="listarLote(1,buscar,buscar2,buscar3,criterio)"> 
                                        <option value="">Seleccione</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                    </select>
                                    <input type="text" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar3" @keyup.enter="listarLote(1,buscar,buscar2,buscar3,criterio)" class="form-control" placeholder="Manzana a buscar">

                                    <input type="text" v-if="criterio=='modelos.nombre'" v-model="buscar" @keyup.enter="listarLote(1,buscar,buscar2,buscar3,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <input type="text" v-if="criterio=='lotes.calle'" v-model="buscar" @keyup.enter="listarLote(1,buscar,buscar2,buscar3,criterio)" class="form-control" placeholder="Texto a buscar">
                                                                        
                                    <input type="text" v-if="criterio=='fraccionamientos.nombre'" v-model="buscar" @keyup.enter="listarLote(1,buscar,buscar2,buscar3,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarLote(1,buscar,buscar2,buscar3,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Proyecto</th>
                                    <th>Etapa</th>
                                    <th>Manzana</th>
                                    <th># Lote</th>
                                    <th>Sublote</th>
                                    <th>Modelo</th>
                                    <th>Calle</th>
                                    <th>Numero</th>
                                    <th>Interior</th>
                                    <th>Terreno mts&sup2;</th>
                                    <th>Construcción mts&sup2;</th>
                                    <!--<th>Casa Muestra</th>-->
                                    <th>Credito puente</th>
                                    <th>Casa en venta</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="lote in arrayLote" :key="lote.id">
                                    <td style="width:12%">
                                        <button title="Editar" type="button" @click="abrirModal('lote','actualizar',lote)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <button title="Borrar" type="button" class="btn btn-danger btn-sm" @click="eliminarLote(lote)">
                                          <i class="icon-trash"></i>
                                        </button> &nbsp;
                                    </td>

                                    
                                    <td v-text="lote.proyecto"></td>
                                    <td v-text="lote.etapas"></td>
                                    <td v-text="lote.manzana"></td>
                                    <td v-text="lote.num_lote"></td>
                                    <td v-text="lote.sublote"></td>
                                    <td v-text="lote.modelo"></td>
                                    <td v-text="lote.calle"></td>
                                    <td v-text="lote.numero"></td>
                                    <td v-text="lote.interior"></td>
                                    <td v-text="lote.terreno"></td>
                                    <td v-text="lote.construccion"></td>
                                    <!--<td v-text="lote.casa_muestra"></td>
                                    <td v-text="lote.lote_comercial"></td>-->
                                    <td v-text="lote.credito_puente"></td>
                                    <td>
                                        <span v-if = "lote.casa_muestra==0 && lote.lote_comercial==0" class="badge badge-success">Activo</span>
                                        <span v-else class="badge badge-danger">Inactivo</span>
                                    </td> 
                                </tr>                               
                            </tbody>
                        </table>  
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page > 7">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(1,buscar,buscar2,buscar3,criterio)">Inicio</a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,buscar2,buscar3,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,buscar2,buscar3,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,buscar2,buscar3,criterio)">Sig</a>
                                </li>
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page<pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.last_page,buscar,buscar2,buscar3,criterio)">Ultimo</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Proyecto</label>
                                    <div class="col-md-6">
                                       <select class="form-control" v-model="fraccionamiento_id" @click="selectEtapa(fraccionamiento_id),selectModelo(fraccionamiento_id),selectManzana(fraccionamiento_id)" >
                                            <option value="0">Seleccione</option>
                                            <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                        </select>
                                    </div>
                                </div>

                                  <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Etapa</label>
                                    <div class="col-md-6">
                                       <select class="form-control" v-model="etapa_id">
                                            <option value="0">Seleccione</option>
                                            <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                        </select>
                                    </div>
                                </div>

                        <!--<div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Manzana</label>
                            <div class="col-md-6">
                                <select class="form-control" v-model="manzana">
                                    <option value="0">Seleccione</option>
                                    <option v-for="manzanas in arrayManzanas" :key="manzanas.id" :value="manzanas.id" v-text="manzanas.manzana"></option>
                                </select>
                            </div>
                                <button title="Editar" type="button" @click="abrirModal('lote','addmanzana')" class="btn btn-warning btn-sm">
                                    <i class="icon-pencil"></i>
                                </button>
                                <div class="modal fade" tabindex="-1" :class="{'mostrar': modal3}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                                <div class="modal-dialog modal-primary modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" v-text="tituloModal3"></h4>
                                            <button type="button" class="close" @click="cerrarModal3()" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                                <div class="form-group row">
                                                    <label class="col-md-3 form-control-label" for="text-input">Manzana</label>
                                                    <div class="col-md-4">
                                                        <input type="text" v-model="manzana" class="form-control" placeholder="manzana">
                                                    </div>
                                                </div>
                                    </div>
                                <div v-show="errorLote" class="form-group row div-error">
                                        <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjLote" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" @click="cerrarModal3()">Cerrar</button>-->
                                        <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                                        <!--<button type="button" v-if="tipoAccion==4" class="btn btn-primary" @click="registrarManzana()">Guardar</button>
                                        </div>
                            </div>
                        </div>
                        </div>
                        </div>-->
                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Manzana</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="manzana" class="form-control" placeholder="Manzana">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input"># Lote</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="num_lote" class="form-control" placeholder="num_lote">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Sublote</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="sublote" class="form-control" placeholder="sublote">
                                    </div>
                                </div>

                                  <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Modelo</label>
                                    <div class="col-md-6">
                                       <select class="form-control" @click="selectConsYTerreno(modelo_id)" v-model="modelo_id">
                                            <option value="0">Seleccione</option>
                                            <option v-for="modelos in arrayModelos" :key="modelos.id" :value="modelos.id" v-text="modelos.nombre"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Dirección</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="calle" class="form-control" placeholder="Calle">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" v-model="numero" class="form-control" placeholder="Numero">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" v-model="interior" class="form-control" placeholder="Interior">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Terreno (mts&sup2;)</label>
                                    <div class="col-md-4" >
                                     
                                        <input type="text"  v-model="terreno" class="form-control" placeholder="Terreno">
                                
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Construcción (mts&sup2;)</label>
                                    <div class="col-md-7">

                                        <input type="text" v-model="construccion" disabled class="form-control" placeholder="Construccion">
                                  
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Credito puente</label>
                                    <div class="col-md-7">

                                        <input type="text" v-model="credito_puente"  class="form-control" placeholder="Credito puente">
                                  
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Comentarios </label>
                                    <div class="col-md-7">
                                        <input type="text" v-model="comentarios" class="form-control" placeholder="Comentarios">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Casa muestra</label>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input class="form-check-input" v-model="casa_muestra" id="radio1" type="radio" value="1" name="radios">
                                            <label class="form-check-label" for="radio1">Si </label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input class="form-check-input" v-model="casa_muestra" id="radio2" type="radio" value="0" name="radios">
                                            <label class="form-check-label" for="radio2">No </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Lote comercial</label>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input class="form-check-input" v-model="lote_comercial" id="radio3" type="radio" value="1" name="radios2">
                                            <label class="form-check-label" for="radio3">Si </label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input class="form-check-input" v-model="lote_comercial" id="radio4" type="radio" value="0" name="radios2">
                                            <label class="form-check-label" for="radio4">No </label>
                                        </div>
                                    </div>

                                    <!-- Hidden para agregar el id de la empresa -->
                                    
                                  <div class="form-group row">
                                    <div class="col-md-4">
                                        <input type="hidden" value="1" v-model="empresa_id" class="form-control">
                                    </div>
                                </div>

                                </div>
                                <!-- Div para mostrar los errores que mande validerModelo -->
                                <div v-show="errorLote" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjLote" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarLote()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarLote()">Actualizar</button>
                        </div>
                    </div>
                      <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->

            <!-- Modal para el archivo excel -->
             <div class="modal fade" tabindex="-1" :class="{'mostrar': modal2}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal2"></h4>
                            <button type="button" class="close" @click="cerrarModal2()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                         <form method="post" @submit="formSubmit"  enctype="multipart/form-data">

                             <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Proyecto</label>
                                    <div class="col-md-6">
                                       <select class="form-control" v-model="fraccionamiento_id" @click="selectEtapa(fraccionamiento_id),selectModelo(fraccionamiento_id)" >
                                            <option value="0">Seleccione</option>
                                            <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                        </select>
                                    </div>
                                </div>

                             <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Etapa</label>
                                    <div class="col-md-6">
                                       <select class="form-control" v-model="etapa_id">
                                            <option value="0">Seleccione</option>
                                            <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Modelo</label>
                                    <div class="col-md-6">
                                       <select class="form-control" @click="selectConsYTerreno(modelo_id)" v-model="modelo_id">
                                            <option value="0">Seleccione</option>
                                            <option v-for="modelos in arrayModelos" :key="modelos.id" :value="modelos.id" v-text="modelos.nombre"></option>
                                        </select>
                                    </div>
                                </div>

                            <!-- {{ csrf_field() }} -->
                            Choose your xls/csv File : <input type="file" v-on:change="onImageChange" class="form-control">

                            <input type="submit" class="btn btn-primary btn-lg" style="margin-top: 3%">
                            
                         </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal2()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
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
 import _ from 'lodash'
    export default {
        data(){
            return{
                id: 0,
                fraccionamiento_id : 0,
                etapa_id: 0,
                manzana: '',
                num_lote: 0,
                sublote: '',
                modelo_id: 0,
                empresa_id: 0,
                calle: '',
                numero: '',
                interior: '',
                terreno : 0,
                construccion : 0,
                casa_muestra: 0,
                lote_comercial: 0,
                credito_puente:'',
                comentarios: '',
                
                file: '',
                modelostc :'',
                arrayLote : [],
                modal : 0,
                tituloModal : '',
                modal2: 0,
                tituloModal2: '',
                modal3: 0,
                tituloModal3: '',
                tipoAccion: 0,
                errorLote : 0,
                errorMostrarMsjLote : [],
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'modelos.nombre', 
                buscar2 : '',
                buscar3 : '',
                buscar : '',
                arrayFraccionamientos : [],
                arrayEtapas : [],
                arrayModelos : [],
                arrayModelosTC: [],
                arrayEmpresas : [],
                arrayManzanas: []
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
            },

            arrayProyectos(){ 
                return _.uniqBy(this.arrayLote, 'proyecto');
            }

        },

        
        methods : {

            onImageChange(e){

                console.log(e.target.files[0]);

                this.file = e.target.files[0];

            },
           
            formSubmit(e) {

                e.preventDefault();
               
               let formData = new FormData();
                formData.append('file', this.file);
                formData.append('fraccionamiento_id', this.fraccionamiento_id);
                formData.append('etapa_id', this.etapa_id);
                formData.append('modelo_id', this.modelo_id);
                let me = this;
                axios.post('/import',formData)
                .then(function (response) {
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Archivo cargado correctamente',
                        showConfirmButton: false,
                        timer: 2500
                        })
                    me.cerrarModal2();
                    me.listarLote(1,'','','','lote');

                })

                .catch(function (error) {

                  console.log(error);

                });

            },

            /**Metodo para mostrar los registros */
            listarLote(page, buscar, buscar2, buscar3, criterio){
                let me = this;
                var url = '/lote?page=' + page + '&buscar=' + buscar + '&buscar2=' + buscar2+ '&buscar3=' + buscar3 + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLote = respuesta.lotes.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            cambiarPagina(page, buscar, buscar2, buscar3, criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarLote(page,buscar,buscar2,buscar3,criterio);
            },

            selectFraccionamientos(){
                let me = this;
                me.buscar=""
                me.buscar2=""
                me.buscar3=""
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
                me.buscar2=""
                me.buscar3=""
                
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

            selectManzana(buscar){
                let me = this;
                
                me.arrayManzanas=[];
                var url = '/select_manzana_proyecto?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayManzanas = respuesta.manzanas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectModelo(buscar){
                let me = this;
              
                me.arrayModelos=[];
                var url = '/select_modelo_proyecto?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayModelos = respuesta.modelos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectConsYTerreno(buscar){
                let me = this;
              
                me.arrayModelosTC=[];
                var url = '/select_construcc_terreno?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayModelosTC = respuesta.modelosTc;

                    me.terreno = me.arrayModelosTC[0].terreno;
                    me.construccion = me.arrayModelosTC[0].construccion;


                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            /**Metodo para registrar  */
            registrarLote(){
                if(this.validarLote()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/lote/registrar',{                 
                    'fraccionamiento_id': this.fraccionamiento_id,
                    'etapa_id': this.etapa_id,
                    'manzana': this.manzana,
                    'num_lote': this.num_lote,
                    'sublote': this.sublote,
                    'modelo_id': this.modelo_id,
                    'empresa_id': this.empresa_id,
                    'calle': this.calle,
                    'numero': this.numero,
                    'interior': this.interior,
                    'terreno': this.terreno,
                    'construccion': this.construccion,
                    'casa_muestra': this.casa_muestra,
                    'lote_comercial': this.lote_comercial,
                    'comentarios': this.comentarios,
                }).then(function (response){
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarLote(1,'','','','lote'); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Lote agregado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },

            registrarManzana(){

               if(this.validarManzana()) //Se verifica si se selecciono un fraccionamiento
                {
                    return;
                }

                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/lote/registrar_manzana',{
                    'fraccionamiento_id': this.fraccionamiento_id,         
                    'manzana': this.manzana
                }).then(function (response){
                    me.cerrarModal3(); //al guardar el registro se cierra el modal
                   
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Manzana agregada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },

            actualizarLote(){
                if(this.validarLote()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/lote/actualizar2',{
                    'id' : this.id,
                    'fraccionamiento_id': this.fraccionamiento_id,
                    'etapa_id': this.etapa_id,
                    'manzana': this.manzana,
                    'num_lote': this.num_lote,
                    'sublote': this.sublote,
                    'modelo_id': this.modelo_id,
                    'empresa_id': this.empresa_id,
                    'calle': this.calle,
                    'numero': this.numero,
                    'interior': this.interior,
                    'terreno': this.terreno,
                    'construccion': this.construccion,
                    'casa_muestra': this.casa_muestra,
                    'lote_comercial': this.lote_comercial,
                    'credito_puente':this.credito_puente,
                    'comentarios': this.comentarios,
                    
                }).then(function (response){
                    me.cerrarModal();
                    me.listarLote(1,'','','','lote');
                    //window.alert("Cambios guardados correctamente");
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Cambios guardados correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            eliminarLote(data =[]){
                this.id=data['id'];
                this.fraccionamiento_id=data['fraccionamiento_id'];
                this.etapa_id=data['etapa_id'];
                this.manzana=data['manzana'];
                this.num_lote=data['num_lote'];
                this.sublote=data['sublote'];
                this.modelo_id=data['modelo_id'];
                this.empresa_id=data['empresa_id'];
                this.calle=data['calle'];
                this.numero=data['numero'];
                this.interior=data['interior'];
                this.terreno=data['terreno'];
                this.construccion=data['construccion'];
                this.casa_muestra=data['casa_muestra'];
                this.lote_comercial=data['lote_comercial'];
                this.credito_puente=data['credito_puente'];
                this.comentarios=data['comentarios'];
                swal({
                title: '¿Desea eliminar?',
                text: "Esta acción no se puede revertir!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si, eliminar!'
                }).then((result) => {
                if (result.value) {
                    let me = this;

                axios.delete('/lote/eliminar', 
                        {params: {'id': this.id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Lote borrado correctamente.',
                        'success'
                        )
                        me.listarLote(1,'','','','lote');
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            validarLote(){
                this.errorLote=0;
                this.errorMostrarMsjLote=[];

                if(!this.fraccionamiento_id) //Si la variable Lote esta vacia
                    this.errorMostrarMsjLote.push("El nombre del proyecto para el Lote no puede ir vacio.");

                if(this.errorMostrarMsjLote.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorLote = 1;

                return this.errorLote;
            },

            validarManzana(){
                this.errorLote=0;
                this.errorMostrarMsjLote=[];
                 if(this.fraccionamiento_id == 0) //Si la variable Lote esta vacia
                    this.errorMostrarMsjLote.push("Seleccione primero el fraccionamiento a asignar las manzanas");

                if(this.errorMostrarMsjLote.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorLote = 1;

                return this.errorLote;
            },

            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.fraccionamiento_id = '';
                this.etapa_id= '';
                this.manzana= '';
                this.num_lote= '';
                this.sublote= '';
                this.modelo_id= '';
                this.empresa_id= 1;
                this.calle= '';
                this.numero= '';
                this.interior= '';
                this.terreno = '';
                this.construccion = '';
                this.casa_muestra= '';
                this.lote_comercial= '';
                this.credito_puente='';
                this.comentarios= '';
                
                this.errorLote = 0;
                this.errorMostrarMsjLote = [];

            },
            cerrarModal2(){
                this.modal2 = 0;
                this.tituloModal2 = '';
            },

            cerrarModal3(){
                this.modal3 = 0;
                this.tituloModal3 = '';
                this.tipoAccion = 1;
            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(lote, accion,data =[]){
                switch(lote){
                    case "lote":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Lote';
                                this.fraccionamiento_id = '0';
                                this.etapa_id= '0';
                                this.num_lote= 0;
                                this.sublote= '';
                                this.modelo_id= '0';
                                this.empresa_id= 1;
                                this.calle= '';
                                this.numero= '';
                                this.interior= '';
                                this.comentarios= '';
                                this.terreno = this.terrenoModelo;
                                this.construccion = 0.0;
                                this.casa_muestra= 0;
                                this.lote_comercial= 0;
                                this.credito_puente='';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal =1;
                                this.tituloModal='Actualizar Lote';
                                this.tipoAccion=2;
                                this.id=data['id'];
                                this.fraccionamiento_id=data['fraccionamiento_id'];
                                this.etapa_id=data['etapa_id'];
                                this.manzana=data['manzana'];
                                this.num_lote=data['num_lote'];
                                this.sublote=data['sublote'];
                                this.modelo_id=data['modelo_id'];
                                this.empresa_id=data['empresa_id'];
                                this.calle=data['calle'];
                                this.numero=data['numero'];
                                this.interior=data['interior'];
                                this.terreno=data['terreno'];
                                this.construccion=data['construccion'];
                                this.casa_muestra=data['casa_muestra'];
                                this.lote_comercial=data['lote_comercial'];
                                this.credito_puente=data['credito_puente'];
                                this.comentarios=data['comentarios'];
                                break;
                            }

                            case 'excel':
                            {
                                this.modal2 =1;
                                this.tituloModal2= 'Cargar desde Excel';
                                this.tipoAccion=3;
                                break;
                            }

                            case 'addmanzana':
                            {
                                
                                this.modal3 =1;
                                this.manzana= '';
                                this.tituloModal3 = 'Agregar una manzana';
                                this.tipoAccion = 4;
                                break;
                            }
                        }
                    }
                }
                this.selectFraccionamientos();
                this.selectEtapa(this.fraccionamiento_id);
                this.selectModelo(this.fraccionamiento_id);
                this.selectConsYTerreno(this.modelo_id);
                this.selectManzana(this.fraccionamiento_id);

            }
        },
        mounted() {
            this.listarLote(1,this.buscar,this.buscar2,this.buscar3,this.criterio);
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
        position: absolute !important;
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
    .scroll-box {
            overflow-x: scroll;
            width: auto;
            padding: 1rem
        }
</style>
