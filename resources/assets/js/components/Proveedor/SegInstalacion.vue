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
                        <i class="fa fa-align-justify"></i> Equipamientos solicitados
                    </div>

                <!-------------------  Div historial equipamientos  --------------------->
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-5">
                                <div class="input-group">
                                    <select class="form-control col-md-4" v-model="status">
                                        <option value="">Todos</option>
                                        <option value="1">Pendiente</option>
                                        <option value="2">En proceso</option>
                                        <option value="3">Revisión</option>
                                        <option value="4">Aprobados</option>
                                        <option value="0">Rechazados</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-3" v-model="criterio2" @click="selectFraccionamientos()">
                                        <option value="lotes.fraccionamiento_id">Proyecto</option>
                                        <option value="c.nombre">Cliente</option>
                                        <option value="contratos.id"># Folio</option>
                                    </select>

                                    <select class="form-control" v-if="criterio2=='lotes.fraccionamiento_id'" v-model="buscar2" @click="selectEtapa(buscar2)">
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamiento in arrayFraccionamientos2" :key="fraccionamiento.nombre" :value="fraccionamiento.id" v-text="fraccionamiento.nombre"></option>
                                    </select>

                                    <select class="form-control" v-if="criterio2=='lotes.fraccionamiento_id'" v-model="b_etapa2"> 
                                        <option value="">Etapa</option>
                                        <option v-for="etapa in arrayEtapas2" :key="etapa.num_etapa" :value="etapa.id" v-text="etapa.num_etapa"></option>
                                    </select>

                                </div>
                                <div class="input-group">

                                    <input type="text" v-if="criterio2=='lotes.fraccionamiento_id'" v-model="b_manzana2" class="form-control" placeholder="Manzana a buscar">
                                    <input type="text" v-if="criterio2=='lotes.fraccionamiento_id'" v-model="b_lote2" class="form-control" placeholder="Lote a buscar">

                                    <input v-else type="text"  v-model="buscar2" @keyup.enter="listarHistorial(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarHistorial(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                   
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr> 
                                        <th></th>
                                        <th># Ref</th>
                                        <th>Cliente</th>
                                        <th>Proyecto</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th>Lote</th>
                                        <th>Fecha de solicitud</th>
                                        <th>Equipamiento</th>
                                        <th>Anticipo</th>
                                        <th>Fecha programada de instalación</th>
                                        <th>Fecha fin de instalación</th>
                                        <th>Dias transcurridos</th>
                                        <th>Status</th>
                                        <th>Liquidación</th>
                                        <th>Total pagado</th>
                                        <th>Pendiente</th>
                                        <th>Imprimir Recepción</th>
                                        <th>Observaciones</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="equipamientos in arrayHistorialEquipamientos" :key="equipamientos.id">
                                        <template>
                                            <td v-if="equipamientos.control == 0">
                                                <i class="btn btn-success btn-sm fa fa-check"></i>
                                            </td>
                                            <td class="td2" v-else-if="equipamientos.control == 1">
                                                <i class="btn btn-primary btn-sm fa fa-exchange"></i> A reasignar
                                            </td>
                                            <td  class="td2" v-else>
                                                <i title="Cancelado" class="btn btn-danger btn-sm fa fa-exclamation-triangle"></i> Cancelado
                                            </td>
                                            <td class="td2" v-text="equipamientos.folio"></td>
                                            <td class="td2" v-text="equipamientos.nombre_cliente"></td>
                                            <td class="td2" v-text="equipamientos.proyecto"></td>
                                            <td class="td2" v-text="equipamientos.etapa"></td>
                                            <td class="td2" v-text="equipamientos.manzana"></td>
                                            <td class="td2" v-text="equipamientos.num_lote"></td>
                                            <td class="td2" v-text="this.moment(equipamientos.fecha_solicitud).locale('es').format('DD/MMM/YYYY')"></td>
                                            <td class="td2" v-text="equipamientos.equipamiento"></td>
                                            <template>
                                                <td v-if="equipamientos.fecha_anticipo" class="td2" v-text=" this.moment(equipamientos.fecha_anticipo).locale('es').format('DD/MMM/YYYY') + ': '+ '$'+formatNumber(equipamientos.anticipo)"></td>
                                                <td v-else class="td2" v-text="'Sin anticipo'"></td>    
                                            </template>
                                            <template>
                                                <td @click="abrirModal('colocacion', equipamientos)" v-if="equipamientos.fecha_colocacion" class="td2">
                                                    <a href="#" v-text=" this.moment(equipamientos.fecha_colocacion).locale('es').format('DD/MMM/YYYY')"></a>
                                                </td>
                                                <td @click="abrirModal('colocacion', equipamientos)" v-else class="td2" >
                                                    <a href="#" v-text="'Sin fecha'"></a>
                                                </td>    
                                            </template>
                                            <template>
                                                <td v-if="equipamientos.fin_instalacion" class="td2" >
                                                    <span v-text=" this.moment(equipamientos.fin_instalacion).locale('es').format('DD/MMM/YYYY')"></span>
                                                </td>
                                                <td @click="abrirModal('fin_instalacion', equipamientos)" v-else class="td2">
                                                    <a href="#" v-text="'Sin fecha'"></a>
                                                </td>    
                                            </template>
                                            <td class="text-center">
                                                <span v-if="equipamientos.dias_rev >= 1 || equipamientos.dias_rev <= -1" v-text="equipamientos.dias_rev"></span>
                                                <span v-else>0</span>
                                            </td>
                                            <template>
                                                <td v-if="equipamientos.status == '0'" class="td2">
                                                    <span class="badge badge-warning">Rechazado</span>
                                                </td>
                                                <td v-if="equipamientos.status == '1'" class="td2">
                                                    <span class="badge badge-primary">Pendiente</span>
                                                </td>
                                                <td v-if="equipamientos.status == '2'" class="td2">
                                                    <span class="badge badge-primary">En proceso de instalación</span>
                                                </td>
                                                <td v-if="equipamientos.status == '3'" class="td2">
                                                    <span class="badge badge-primary">En Revisión</span>
                                                </td>
                                                <td v-if="equipamientos.status == '4'" class="td2">
                                                    <span class="badge badge-success">Aprobado</span>
                                                </td>    
                                                <td v-if="equipamientos.status == '5'" class="td2">
                                                    <span class="badge badge-danger">Cancelado</span>
                                                </td>  
                                            </template>
                                            <template>
                                                <td v-if="equipamientos.fecha_liquidacion" class="td2" v-text="this.moment(equipamientos.fecha_liquidacion).locale('es').format('DD/MMM/YYYY')+ ': '+ '$'+formatNumber(equipamientos.liquidacion)"></td>
                                                <td v-else class="td2" v-text="'Sin programar'"></td>    
                                            </template>
                                            <td class="td2" v-text="'$'+formatNumber(equipamientos.anticipo + equipamientos.liquidacion)"></td>
                                            <td class="td2" v-text="'$'+formatNumber(equipamientos.costo - equipamientos.anticipo - equipamientos.liquidacion)"></td>

                                                <td >
                                                    <a v-if="equipamientos.tipoRecepcion == 1 && equipamientos.recepcion == 1"
                                                        class="btn btn-warning btn-sm"  target="_blank" 
                                                        v-bind:href="'/equipamiento/recepcionCocina/'+equipamientos.id">
                                                        Ver Recepción
                                                    </a>
                                                    <a v-else-if="equipamientos.tipoRecepcion == 2 && equipamientos.recepcion == 1"
                                                        class="btn btn-warning btn-sm"  target="_blank" 
                                                        v-bind:href="'/equipamiento/recepcionClosets/'+equipamientos.id">
                                                        Ver Recepción
                                                    </a>
                                                    <a v-else-if="equipamientos.tipoRecepcion == 0 && equipamientos.recepcion == 1"
                                                        class="btn btn-warning btn-sm"  target="_blank" 
                                                        v-bind:href="'/equipamiento/recepcionGeneral/'+equipamientos.id">
                                                        Ver Recepción
                                                    </a>                                                    
                                                </td>

                                            <td> 
                                                <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right" 
                                                    @click="abrirModal('observaciones', equipamientos),listarObservacion(1,equipamientos.id)">Ver observaciones
                                                </button> 
                                            </td>
                                        </template>
                                    </tr>
                                </tbody>
                            </table>  
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination2.last_page > 7 && pagination2.current_page > 7">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)">Inicio</a>
                                </li>
                                <li class="page-item" v-if="pagination2.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page - 1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber2" :key="page" :class="[page == isActived2 ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(page,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination2.current_page < pagination2.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page + 1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)">Sig</a>
                                </li>
                                <li class="page-item" v-if="pagination2.last_page > 7 && pagination2.current_page<pagination2.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.last_page,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)">Ultimo</a>
                                </li>
                            </ul>
                        </nav>
                        <button class="btn btn-sm btn-default" data-toggle="modal" data-target="#manualId">Manual</button>
                    </div>
                <!-------------------  Fin Div para Contratos que tienen paquete o promoción  --------------------->

                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
         
            <!--Inicio del modal para diversos llenados de tabla en historial -->
                <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal2}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-primary modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" v-text="tituloModal"></h5>
                                <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                                <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                <div class="form-group row" v-if="tipoAccion == 2">
                                    <label class="col-md-2 form-control-label" for="text-input">Fecha de colocacion</label>
                                    <div class="col-md-3">
                                        <input type="date" v-model="fecha_colocacion" class="form-control">
                                    </div>
                                </div>

                                 <div class="form-group row" v-if="tipoAccion == 2">
                                    <label class="col-md-2 form-control-label" for="text-input">Observacion</label>
                                    <div class="col-md-8">
                                        <textarea v-model="observacion" cols="50" rows="4"></textarea>
                                    </div>
                                </div>

                                
                            </div>
                            <!-- Botones del modal -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                                <button type="button" v-if="tipoAccion == 2" class="btn btn-success" @click="actColocacion()">Guardar</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
            <!--Fin del modal-->

             <!--Inicio del modal para diversos llenados de tabla en historial -->
                <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal2}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-primary modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" v-text="tituloModal"></h5>
                                <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                                <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                <div class="form-group row" v-if="tipoAccion == 2">
                                    <label class="col-md-2 form-control-label" for="text-input">Fecha programada para instalación</label>
                                    <div class="col-md-3">
                                        <input type="date" v-model="fecha_colocacion" class="form-control">
                                    </div>
                                </div>

                                 <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Observacion (*)</label>
                                    <div class="col-md-8">
                                        <textarea v-model="observacion" cols="50" rows="4"></textarea>
                                    </div>
                                </div>

                                <!-- Div para mostrar los errores  -->
                                <div v-show="errorColocacion" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjColocacion" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>

                                
                            </div>
                            <!-- Botones del modal -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                                <button type="button" v-if="tipoAccion ==1" class="btn btn-success" @click="setInstalacion()">Finalizar instalación</button>
                                <button type="button" v-if="tipoAccion == 2" class="btn btn-success" @click="actColocacion()">Guardar</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
            <!--Fin del modal-->

            <!--Inicio del modal observaciones-->
                <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal3}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-primary modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" v-text="tituloModal"></h4>
                                <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                                <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Nueva observación</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="observacion" class="form-control">
                                    </div>

                                    <div class="col-md-3">
                                        <button class="btn btn-primary" @click="storeObservacion()">Guardar</button>
                                    </div>
                                </div>


                                <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                    
                                    <table class="table table-bordered table-striped table-sm">
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
                                                <td v-text="observacion.comentario" ></td>
                                                <td v-text="observacion.created_at"></td>
                                            </tr>                               
                                        </tbody>
                                    </table>
                                    
                                </form>
                            </div>
                            <!-- Botones del modal -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
            <!--Fin del modal-->
            
            <!-- Manual -->
            <div class="modal fade" id="manualId" tabindex="-1" role="dialog" aria-labelledby="manualIdTitle" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="manualIdTitle">Manual</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>
                            Dentro del listado de “Seguimiento de instalación” podrá ver el listado de aquellas 
                            solicitudes de instalación de algún lote (pueden ser una o más por lote).
                        </p>
                        <p>
                            <strong>Fechas,</strong> se pueden asignar las fechas tentativas de instalación y esta indicara que la 
                            solicitud se encuentra en proceso, también puede indicar una fecha definitiva de 
                            instalación para indicar que la instalación fuese realizada con éxito, una vez colocada 
                            la “Fecha fin de instalación” el registro pasara a estatus de “En revisión” a 
                            espera de la revisión de obra (vea el modulo de <strong>“Obra -> Solic. Equipamiento”</strong>),
                        </p>
                        <p>
                            <strong>Instalación rechazada</strong>, en caso de que una instalación sea rechazada debe asignar una nueva 
                            “Fecha programada de instalación” y al finalizar la instalación una “Fecha fin de instalación”, 
                            esto permitirá volver a realizar la revisión.
                        </p>
                        <p>
                            <strong>Nota:</strong> el módulo también permitirá ver saldos.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    </div>
                </div>
            </div>
         
     </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    export default {
        data(){
            return{
                observacion:'',

                arrayObservacion : [],

                arrayFraccionamientos2:[],
                arrayEtapas2:[],

                modal: 0,
                modal2: 0,
                modal3: 0,
                tituloModal: '',

                //variables
                lote_id: 0,
                contrato_id: 0,
                solicitud_id: 0,
                offset : 3,

                // Criterios para historial de equipamientos
                pagination2 : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                criterio2 : 'lotes.fraccionamiento_id', 
                buscar2 : '',
                b_etapa2: '',
                b_manzana2: '',
                b_lote2: '',
                tipoAccion:0,
                fecha_colocacion:'',
                fin_instalacion:'',
                observacion:'',
                status:'',

                errorColocacion : 0,
                errorMostrarMsjColocacion : [],

                arrayHistorialEquipamientos : []
               
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


            isActived2: function(){
                return this.pagination2.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber2:function(){
                if(!this.pagination2.to){
                    return [];
                }

                var from = this.pagination2.current_page - this.offset;
                if(from < 1){
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.pagination2.last_page){
                    to = this.pagination2.last_page;
                }

                var pagesArray = [];
                while(from <= to){
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            },

        },

        
        methods : {

            listarHistorial(page, buscar, b_etapa, b_manzana, b_lote, criterio){
                let me = this;
                var url = '/equipamiento/indexHistorial?page=' + page + '&buscar=' + buscar + '&b_etapa=' + b_etapa + '&b_manzana=' + b_manzana + '&b_lote=' + b_lote +  '&criterio=' + criterio + '&status=' + this.status;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayHistorialEquipamientos = respuesta.equipamientos.data;
                    me.pagination2 = respuesta.pagination;
                    
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            
            selectFraccionamientos(){
                let me = this;
                me.buscar=""
                
                me.arrayFraccionamientos=[];
                me.arrayFraccionamientos2=[];
                var url = '/select_fraccionamiento';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayFraccionamientos = respuesta.fraccionamientos;
                    me.arrayFraccionamientos2 = respuesta.fraccionamientos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectEtapa(buscar){
                let me = this;
                me.b_etapa=""
                
                me.arrayEtapas=[];
                me.arrayEtapas2=[];
                var url = '/select_etapa_proyecto?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEtapas = respuesta.etapas;
                    me.arrayEtapas2 = respuesta.etapas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            cambiarPagina2(page,buscar,b_etapa,b_manzana,b_lote,criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination2.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarHistorial(page,buscar,b_etapa,b_manzana,b_lote,criterio);
            },

            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
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

            storeObservacion(){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.post('/equipamiento/registrarObservacion',{
                    'comentario' : this.observacion,
                    'solic_id':this.solicitud_id,
                    
                }).then(function (response){
                    me.listarObservacion(1,me.solicitud_id);
                    //window.alert("Cambios guardados correctamente");
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

            listarObservacion(page, buscar){
                let me = this;
                var url = '/equipamiento/indexObservacion?page=' + page + '&buscar=' + buscar ;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayObservacion = respuesta.observacion.data;
                    console.log(url);
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },

            actColocacion(){

                if(this.validarColocacion()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/equipamiento/actColocacion',{
                    'fecha_colocacion':this.fecha_colocacion,
                    'comentario' : this.observacion,
                    'id':this.solicitud_id,
                    
                }).then(function (response){
                    me.cerrarModal();
                    me.listarHistorial(me.pagination2.current_page,me.buscar2,me.b_etapa2,me.b_manzana2,me.b_lote2,me.criterio2);
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Datos guardados correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            setInstalacion(){

                if(this.validarInstalacion()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/equipamiento/set_instalacion',{
                    'fin_instalacion':this.fin_instalacion,
                    'comentario' : this.observacion,
                    'id':this.solicitud_id,
                    
                }).then(function (response){
                    me.cerrarModal();
                    me.listarHistorial(me.pagination2.current_page,me.buscar2,me.b_etapa2,me.b_manzana2,me.b_lote2,me.criterio2);
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Datos guardados correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },

            validarColocacion(){
                this.errorColocacion=0;
                this.errorMostrarMsjColocacion=[];

                if(!this.observacion) //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjColocacion.push("Debe ingresar una observación.");

                if(!this.fecha_colocacion) //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjColocacion.push("Ingresar fecha de colocación.");


                if(this.errorMostrarMsjColocacion.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorColocacion = 1;

                return this.errorColocacion;
            },

            
            validarInstalacion(){
                this.errorColocacion=0;
                this.errorMostrarMsjColocacion=[];

                if(!this.observacion) //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjColocacion.push("Debe ingresar una observación.");


                if(this.errorMostrarMsjColocacion.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorColocacion = 1;

                return this.errorColocacion;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.modal2 = 0;
                this.modal3 = 0;
                this.fecha_colocacion = '',
                this.observacion = '',
                this.proveedor = '';
                this.equipamiento = '';
                this.equipamientos = '';
                this.fin_instalacion = '';
                this.errorMostrarMsjColocacion = [];
                this.errorColocacion = 0;
            },

            abrirModal(accion,data =[]){
                    switch(accion){

                        case 'observaciones':{
                            this.modal3 =1;
                            this.tituloModal='Observaciones';
                            this.solicitud_id = data['id'];
                            this.observacion = '';
                            break;
                        }

                        case 'colocacion':{
                            this.modal2 =2;
                            this.tituloModal='Programacion para instalación';
                            this.tipoAccion=2;
                            this.fecha_colocacion = data['fecha_colocacion'];
                            this.solicitud_id = data['id'];
                            this.observacion = '';
                            break;
                        }

                        case 'fin_instalacion':{
                            this.modal2 =2;
                            this.tituloModal='Fecha de instalación';
                            this.tipoAccion=1;
                            this.fin_instalacion = data['fin_instalacion'];
                            this.solicitud_id = data['id'];
                            this.observacion = '';
                            break;
                        }
                    }
                }
            
        },
       
        mounted() {
            this.listarHistorial(1,this.buscar2,this.b_etapa2,this.b_manzana2,this.b_lote2,this.criterio2);
            this.selectFraccionamientos();
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
    font-size: 0.85rem;
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
