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
                        <i class="fa fa-align-justify"></i> Entregas de vivienda
                    </div>

                <!-------------------  Div historial contratos  --------------------->
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-7">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-4" v-model="criterio2" @change="selectFraccionamientos()">
                                        <option value="lotes.fraccionamiento_id">Proyecto</option>
                                        <option value="c.nombre">Cliente</option>
                                        <option value="contratos.id"># Folio</option>
                                    </select>
                                    <select class="form-control" v-if="criterio2=='lotes.fraccionamiento_id'" v-model="buscar2" @change="selectEtapa(buscar2)">
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamiento in arrayFraccionamientos2" :key="fraccionamiento.nombre" :value="fraccionamiento.id" v-text="fraccionamiento.nombre"></option>
                                    </select>
                                    <input v-else type="text"  v-model="buscar2" @keyup.enter="listarContratos(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)" class="form-control" placeholder="Texto a buscar">
                                </div>
                                <div class="input-group" v-if="criterio2=='lotes.fraccionamiento_id'">
                                    <select class="form-control col-md-5" @change="selectManzanas(buscar2,b_etapa2)" v-model="b_etapa2"> 
                                        <option value="">Etapa</option>
                                        <option v-for="etapa in arrayEtapas2" :key="etapa.num_etapa" :value="etapa.id" v-text="etapa.num_etapa"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6" v-if="criterio2=='lotes.fraccionamiento_id'">
                                <div class="input-group">
                                    <select class="form-control" @change="selectLotesManzana(buscar2,b_etapa2,b_manzana2)" @keyup.enter="listarContratos(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)" v-model="b_manzana2" >
                                        <option value="">Manzana</option>
                                        <option v-for="manzana in arrayAllManzanas" :key="manzana.manzana" :value="manzana.manzana" v-text="manzana.manzana"></option>
                                    </select>
                                    <select class="form-control" @keyup.enter="listarContratos(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)" v-model="b_lote2" >
                                        <option value="">Lote</option>
                                        <option v-for="lotes in arrayAllLotes" :key="lotes.id" :value="lotes.num_lote" v-text="lotes.num_lote"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <button type="submit" @click="listarContratos(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                            
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr> 
                                        <th># Ref</th>
                                        <th>Cliente</th>
                                        <th>Proyecto</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th>Lote</th>
                                        <th>Fecha de firma</th>
                                        <th>Fecha entrega (Obra)</th>
                                        <th>Paquete y/o Promocioón</th>
                                        <th colspan="3">Equipamiento</th>
                                        <th>Observaciones</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="contratos in arrayContratos" :key="contratos.id">
                                        <template>
                                            <td class="td2">
                                                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">{{contratos.folio}}</a>
                                                <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
                                                    <a class="dropdown-item" target="_blank" v-bind:href="'/contratoCompraVenta/pdf/'+ contratos.folio">Contrato de compra venta</a>
                                                    <a class="dropdown-item" @click="selectNombreArchivoModelo(contratos.folio)">Catalogo de especificaciones</a>
                                                    <a v-if="contratos.foto_predial" class="dropdown-item" v-bind:href="'/downloadPredial/'+ contratos.foto_predial">Predial</a>
                                                    <a v-if="contratos.num_licencia" class="dropdown-item"  v-text="'Licencia: '+contratos.num_licencia" v-bind:href="'/downloadLicencias/'+contratos.foto_lic"></a>
                                                    <a v-if="contratos.revision_previa == 2" title="Ver revision" type="button" 
                                                        class="dropdown-item" target="_blank" :href="'/postventa/checklist/pdf/'+contratos.folio">
                                                    <i class="fa fa-file-pdf-o"></i>&nbsp;Ver revisión
                                                </a> 
                                                </div>
                                            </td>
                                            <td v-if="contratos.revision_previa != 2" class="td2" v-text="contratos.nombre_cliente">
                                            <td v-else class="td2 badge badge-primary" v-text="contratos.nombre_cliente">

                                            </td>
                                            <td class="td2" v-text="contratos.proyecto"></td>
                                            <td class="td2" v-text="contratos.etapa"></td>
                                            <td class="td2" v-text="contratos.manzana"></td>
                                            <td class="td2" v-text="contratos.num_lote"></td>
                                            <td class="td2" v-text="this.moment(contratos.fecha_firma_esc).locale('es').format('DD/MMM/YYYY')"></td>
                                            <template>
                                                <td class="td2" v-if="contratos.fecha_entrega_obra && contratos.diferencia_obra < 2">
                                                    <span v-text="this.moment(contratos.fecha_entrega_obra).locale('es').format('DD/MMM/YYYY')" class="badge badge-success"></span>
                                                </td>
                                                <td class="td2" v-else-if="contratos.fecha_entrega_obra && contratos.diferencia_obra > 2">
                                                    <span v-text="this.moment(contratos.fecha_entrega_obra).locale('es').format('DD/MMM/YYYY')" class="badge badge-danger"></span>
                                                </td>
                                                <td class="td2" v-else>
                                                    <button title="Programar fecha" type="button" class="btn btn-info pull-right" 
                                                        @click="abrirModal('fecha_entrega', contratos)">Programar fecha
                                                    </button>
                                                </td>
                                            </template>
                                            <template>
                                                <td class="td2" v-if="contratos.paquete && contratos.promocion" v-text="'Paquete: '+contratos.paquete +' Promoción: '+contratos.promocion"></td>
                                                <td class="td2" v-else-if="contratos.paquete && !contratos.promocion" v-text="'Paquete: '+contratos.paquete" ></td>
                                                <td class="td2" v-else-if="!contratos.paquete && contratos.promocion" v-text="'Promoción: '+contratos.promocion" ></td>
                                                <td class="td2" v-else v-text="'Sin equipamiento'" ></td> 
                                            </template>

                                            <template v-if="contratos.paquete && contratos.promocion && contratos.equipamiento == 0">
                                                <td class="td2" colspan="3">
                                                     <span class="badge badge-danger">Equipamiento sin solicitarse</span>
                                                </td>
                                            </template>
                                            <template v-else-if="contratos.paquete && !contratos.promocion && contratos.equipamiento == 0">
                                                <td class="td2" colspan="3">
                                                    <span class="badge badge-danger">Equipamiento sin solicitarse</span>
                                                </td>
                                            </template>
                                            <template v-else-if="!contratos.paquete && contratos.promocion && contratos.equipamiento == 0">
                                                <td class="td2" colspan="3">
                                                    <span class="badge badge-danger">Equipamiento sin solicitarse</span>
                                                </td>
                                            </template>
                                            <template v-else-if="contratos.paquete && contratos.promocion && contratos.equipamiento == 1">
                                                <td class="td2" colspan="2">
                                                    <span class="badge badge-warning">En proceso de instalación</span>
                                                </td>
                                                <td class="td2" >
                                                    Fecha programada: {{this.moment(contratos.fecha_colocacion).locale('es').format('DD/MMM/YYYY')}}
                                                </td>
                                                
                                            </template>
                                            <template v-else-if="contratos.paquete && !contratos.promocion && contratos.equipamiento == 1" >
                                                <td class="td2" colspan="2">
                                                    <span class="badge badge-warning">En proceso de instalación</span>
                                                </td>
                                                <td class="td2" >
                                                    Fecha programada: {{this.moment(contratos.fecha_colocacion).locale('es').format('DD/MMM/YYYY')}}
                                                </td>
                                            </template>
                                            <template v-else-if="!contratos.paquete && contratos.promocion && contratos.equipamiento == 1">
                                                <td class="td2" colspan="2">
                                                    <span class="badge badge-warning">En proceso de instalación</span>
                                                </td>
                                                <td class="td2" >
                                                    Fecha programada: {{this.moment(contratos.fecha_colocacion).locale('es').format('DD/MMM/YYYY')}}
                                                </td>
                                            </template>
                                            <template  v-else-if="contratos.paquete && contratos.promocion && contratos.equipamiento == 2">
                                                <td class="td2" colspan="2">
                                                    <span class="badge badge-success">Equipamiento instalado</span>
                                                </td>
                                                <td class="td2" >
                                                    Fecha de finalizacion: {{this.moment(contratos.fin_instalacion).locale('es').format('DD/MMM/YYYY')}}
                                                </td>
                                            </template>
                                            <template v-else-if="contratos.paquete && !contratos.promocion && contratos.equipamiento == 2">
                                                <td class="td2" colspan="2">
                                                    <span class="badge badge-success">Equipamiento instalado</span>
                                                </td>
                                                <td class="td2" >
                                                    Fecha de finalizacion: {{this.moment(contratos.fin_instalacion).locale('es').format('DD/MMM/YYYY')}}
                                                </td>
                                            </template>
                                            <template v-else-if="!contratos.paquete && contratos.promocion && contratos.equipamiento == 2">
                                                <td class="td2" colspan="2">
                                                    <span class="badge badge-success">Equipamiento instalado</span>
                                                </td>
                                                <td class="td2" >
                                                    Fecha de finalizacion: {{this.moment(contratos.fin_instalacion).locale('es').format('DD/MMM/YYYY')}}
                                                </td>
                                            </template>
                                            <template v-else>
                                                <td class="td2" colspan="3" v-text="'Sin equipamiento'" ></td> 
                                            </template>
                                            <td> 
                                                <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right" 
                                                    @click="abrirModal('observaciones', contratos),listarObservacion(1,contratos.folio)">Ver observaciones
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
                <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-primary modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" v-text="tituloModal"></h5>
                                <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                                <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Fecha de entrega</label>
                                    <div class="col-md-3">
                                        <input v-model="fecha_entrega_obra" type="date" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Observación</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="observacion" class="form-control">
                                    </div>
                                </div>

                                <!-- Div para mostrar los errores que mande validerDepartamento -->
                                <div v-show="errorEntrega" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjEntrega" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>
 
                            </div>
                            <!-- Botones del modal -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                                <button type="button" class="btn btn-success" @click="setFechaEntrega()">Guardar</button>
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
                            Dentro del modulo de viviendas por entregar podrá asignar una “Fecha entrega (Obra)” 
                            para indicar cuando será terminada la casa.
                        </p>
                        <p>
                            Podrá ver el estado de instalación de equipamiento.
                        </p>
                        <p>
                            En caso de ser necesario podrá agregar o ver observaciones.
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
                arrayAllManzanas:[],
                arrayAllLotes:[],
                arrayContratos:[],

                modal: 0,
                modal3: 0,
                tituloModal: '',

                //variables
                lote_id: 0,
                folio:0,
                contrato_id: 0,
                offset : 3,
                nombre_archivo_modelo:'',
                fecha_entrega_obra:'',

                // Criterios para historial de contratos
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
                observacion:'',

                errorEntrega : 0,
                errorMostrarMsjEntrega : [],
               
            }
        },
        computed:{
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
            listarContratos(page, buscar, b_etapa, b_manzana, b_lote, criterio){
                let me = this;
                var url = '/postventa/index?page=' + page + '&buscar=' + buscar + '&b_etapa=' + b_etapa + '&b_manzana=' + b_manzana + '&b_lote=' + b_lote +  '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayContratos = respuesta.contratos.data;
                    me.pagination2 = respuesta.pagination;
                    
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectNombreArchivoModelo(id){
                let me = this;
                me.nombre_archivo_modelo='';
                var url = '/contrato/modelo/caracteristicas/pdf/' + id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                        me.nombre_archivo_modelo = respuesta.modelo[0].archivo;
                        window.open('/files/modelos/'+me.nombre_archivo_modelo, '_blank')
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

            //Select todas las manzanas
            selectManzanas(buscar1, buscar2){
                let me = this;
                me.b_manzana2="";
                me.b_lote2="";

                me.arrayAllManzanas=[];
                var url = '/select_manzanas_etapa?buscar=' + buscar1 + '&buscar1='+ buscar2;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayAllManzanas = respuesta.manzana;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            //Select todos los lotes
            selectLotesManzana(buscar1, buscar2, buscar3){
                let me = this;
                me.b_lote2="";

                me.arrayAllLotes=[];
                var url = '/select_lotes_manzana?buscar=' + buscar1 + '&buscar1='+ buscar2 + '&buscar2='+ buscar3;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayAllLotes = respuesta.lote_manzana;
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
                me.listarContratos(page,buscar,b_etapa,b_manzana,b_lote,criterio);
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
                axios.post('/postventa/registrarObservacion',{
                    'comentario' : this.observacion,
                    'entrega_id':this.folio,
                    
                }).then(function (response){
                    me.listarObservacion(1,me.folio);
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
                var url = '/postventa/indexObservacion?page=' + page + '&buscar=' + buscar ;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayObservacion = respuesta.observacion.data;
                    console.log(url);
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },

            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },

            setFechaEntrega(){
                if(this.validarFecha()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/lotes/setFechaEntregaObra',{
                    'lote_id':this.lote_id,
                    'fecha_entrega_obra' : this.fecha_entrega_obra,
                    'folio' : this.folio,
                    'comentario': this.observacion,
                    
                }).then(function (response){
                    me.cerrarModal();
                    me.listarContratos(1,me.buscar2,me.b_etapa2,me.b_manzana2,me.b_lote2,me.criterio2);
                    //window.alert("Cambios guardados correctamente");
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'fecha agregada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },

            validarFecha(){
                this.errorEntrega=0;
                this.errorMostrarMsjEntrega=[];

                if(!this.observacion) //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjEntrega.push("Debe ingresar una observación.");

                if(!this.fecha_entrega_obra) //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjEntrega.push("Ingresar fecha de entrega.");


                if(this.errorMostrarMsjEntrega.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorEntrega = 1;

                return this.errorEntrega;
            },
            cerrarModal(){
                this.tituloModal = '';
                this.modal = 0;
                this.modal3 = 0;
                this.observacion = '';
                this.arrayObservacion = [];
            },

            abrirModal(accion,data =[]){
                    switch(accion){

                        case 'observaciones':{
                            this.modal3 =1;
                            this.tituloModal='Observaciones';
                            this.folio = data['folio'];
                            this.observacion = '';
                            break;
                        }

                        case 'fecha_entrega':{
                            this.modal = 1;
                            this.tituloModal='Programar fecha de entrega';
                            this.folio = data['folio'];
                            this.observacion = '';
                            this.fecha_entrega_obra = data['fecha_entrega_obra'];
                            this.lote_id = data['loteId'];
                            break;
                        }
                    }
                }
            
        },
       
        mounted() {
            this.listarContratos(1,this.buscar2,this.b_etapa2,this.b_manzana2,this.b_lote2,this.criterio2);
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
