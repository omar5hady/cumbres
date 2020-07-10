<template>
    <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card scroll-box">
                    <div class="card-header" v-if="historial == 0">
                        <i class="fa fa-align-justify"></i> Bonos &nbsp;
                        <button type="button" @click="historial = 1" class="btn btn-default">
                            <i class="icon-eye"></i>&nbsp;Historial de bonos
                        </button>
                    </div>
                    <div class="card-header" v-if="historial == 1">
                        <i class="fa fa-align-justify"></i> Historial de bonos &nbsp;
                        <button type="button" @click="historial = 0" class="btn btn-default">
                            <i class="fa fa-reply"></i>&nbsp;Regresar
                        </button>
                    </div>

                <!----------------- Listado Contratos ------------------------------>
                <!-- Div Card Body para listar -->
                    <div class="card-body" v-if="historial == 0"> 
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <label class="form-control col-md-4" disabled>
                                        Cliente:
                                    </label>
                                    <input type="text" v-model="b_cliente" @keyup.enter="listarPendientes(1)" class="form-control" placeholder="Cliente">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <select class="form-control"  @click="selectEtapas(b_proyecto)" v-model="b_proyecto" >
                                        <option value="">Fraccionamiento</option>
                                        <option v-for="proyecto in arrayFraccionamientos" :key="proyecto.id" :value="proyecto.id" v-text="proyecto.nombre"></option>
                                    </select>

                                    <select class="form-control" v-model="b_etapa" >
                                        <option value="">Etapa</option>
                                        <option v-for="etapa in arrayEtapas" :key="etapa.id" :value="etapa.id" v-text="etapa.num_etapa"></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <label class="form-control col-md-4" disabled>
                                        Asesor:
                                    </label>
                                    <select class="form-control"  v-model="b_asesor_id" >
                                        <option value="">Seleccione</option>
                                        <option v-for="asesor in arrayAsesores" :key="asesor.id" :value="asesor.id" v-text="asesor.nombre + ' '+ asesor.apellidos"></option>
                                    </select>
                                </div>
                                <div class="input-group">
                                    <button type="submit" @click="listarPendientes(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>

                        
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Folio</th>
                                        <th>Proyecto</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th># Lote</th>
                                        <th>Cliente</th>
                                        <th>Asesor</th>
                                        <th>Bono</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="contrato in arrayPendientes" :key="contrato.folio">
                                        <td class="td2" v-text="contrato.id"> </td>
                                        <td class="td2" v-text="contrato.proyecto"></td>
                                        <td class="td2" v-text="contrato.etapa"></td>
                                        <td class="td2" v-text="contrato.manzana"></td>
                                        <td class="td2" v-text="contrato.num_lote "></td>
                                        <td class="td2" v-text="contrato.nombre_cliente"></td>
                                        <td class="td2" v-text="contrato.asesor"></td>
                                        <td>
                                            <button title="Generar bono" type="button" class="btn btn-success btn-sm" @click="abrirModal('generar',contrato)">
                                                <i class="fa fa-money">&nbsp; Generar bono</i>
                                            </button>
                                        </td>
                                    </tr>                               
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination2.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page - 1)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page2 in pagesNumber2" :key="page2" :class="[page2 == isActived2 ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(page2)" v-text="page2"></a>
                                </li>
                                <li class="page-item" v-if="pagination2.current_page < pagination2.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page + 1)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>


                <!-- Div Card Body para listar historial-->
                    <div class="card-body" v-if="historial == 1"> 
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <label class="form-control col-md-4" disabled>
                                        Cliente:
                                    </label>
                                    <input type="text" v-model="b_cliente" @keyup.enter="listarHistorial(1)" class="form-control" placeholder="Cliente">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <select class="form-control"  @click="selectEtapas(b_proyecto)" v-model="b_proyecto" >
                                        <option value="">Fraccionamiento</option>
                                        <option v-for="proyecto in arrayFraccionamientos" :key="proyecto.id" :value="proyecto.id" v-text="proyecto.nombre"></option>
                                    </select>

                                    <select class="form-control" v-model="b_etapa" >
                                        <option value="">Etapa</option>
                                        <option v-for="etapa in arrayEtapas" :key="etapa.id" :value="etapa.id" v-text="etapa.num_etapa"></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" v-model="b_manzana" @keyup.enter="listarHistorial(1)"  class="form-control" placeholder="Manzana">

                                    <input type="text" v-model="b_lote" @keyup.enter="listarHistorial(1)" class="form-control" placeholder="Numero de Lote">
                                </div>
                            </div>

                            <div class="col-md-7">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <label class="form-control col-md-4" disabled>
                                        Asesor:
                                    </label>
                                    <select class="form-control"  v-model="b_asesor_id" >
                                        <option value="">Seleccione</option>
                                        <option v-for="asesor in arrayAsesores" :key="asesor.id" :value="asesor.id" v-text="asesor.nombre + ' '+ asesor.apellidos"></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-7">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <label class="form-control col-md-4" disabled>
                                        Fecha:
                                    </label>
                                    <input type="date" v-model="desde" @keyup.enter="listarHistorial(1)" class="form-control">
                                    <input type="date" v-model="hasta" @keyup.enter="listarHistorial(1)" class="form-control">
                                </div>
                                <div class="input-group">
                                    <button type="submit" @click="listarHistorial(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Folio</th>
                                        <th>Proyecto</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th># Lote</th>
                                        <th>Asesor</th>
                                        <th>Fecha de bono</th>
                                        <th>Bono</th>
                                        <th>Descripcion</th>
                                        <th>Duplicar</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="contrato in arrayAnticipos" :key="contrato.id">
                                        <td>
                                            <a type="button" target="_blank" class="btn btn-danger btn-sm" title="Imprimir" v-bind:href="'bonos_ventas/reciboPDF/'+contrato.id"> <i class="fa fa-file-pdf-o"></i></a>
                                        </td>
                                        <td class="td2" v-text="contrato.folio"> </td>
                                        <td class="td2" v-text="contrato.fraccionamiento"></td>
                                        <td class="td2" v-text="contrato.etapa"></td>
                                        <td class="td2" v-text="contrato.manzana"></td>
                                        <td class="td2" v-text="contrato.num_lote "></td>
                                        <td class="td2" v-text="contrato.asesor"></td>
                                        <td class="td2" v-text="this.moment(contrato.fecha_pago).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td class="td2" v-text="'$'+formatNumber((contrato.monto).toFixed(2))"></td>
                                        <td class="td2" v-text="contrato.descripcion"></td>
                                        <template>
                                            <td v-if="contrato.status == 1 && contrato.num_bono == 1 && contrato.numVentas > 2 && contrato.ventaQuincena>0 && contrato.ventaAnt > 1">
                                                <button title="Duplicar bono" type="button" class="btn btn-success btn-sm" @click="abrirModal('duplicar',contrato)">
                                                    <i class="fa fa-money">&nbsp; Duplicar bono</i>
                                                </button>
                                            </td>
                                            <td v-else-if="contrato.status == 0">CANCELADO</td>
                                            <td v-else> No aplica </td>
                                        </template>
                                        <th>
                                            <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right" 
                                                    @click="abrirModal('observaciones',contrato)">Observaciones</button>
                                        </th>
                                    </tr>                               
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>

            <!-- Inicio Modal GENERAR BONO -->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
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
                                <label class="col-md-2 form-control-label" for="text-input">Asesor:</label>
                                <div class="col-md-4">
                                    <input type="text" readonly v-model="asesor" class="form-control" placeholder="Asesor">
                                </div>
                                <label class="col-md-3 form-control-label" for="text-input">Fecha de pago:</label>
                                <div class="col-md-3">
                                    <input type="date" v-model="fecha_pago" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 form-control-label" for="text-input">Proyecto:</label>
                                <div class="col-md-4">
                                    <input type="text" readonly v-model="proyecto" class="form-control">
                                </div>
                                <label class="col-md-1 form-control-label" for="text-input">Etapa:</label>
                                <div class="col-md-4">
                                    <input type="text" readonly v-model="etapa" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 form-control-label" for="text-input">Manzana:</label>
                                <div class="col-md-4">
                                    <input type="text" readonly v-model="manzana" class="form-control">
                                </div>
                                <label class="col-md-1 form-control-label" for="text-input"> Lote:</label>
                                <div class="col-md-3">
                                    <input type="text" readonly v-model="lote" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row line-separator"></div>

                            <br>

                            <div class="form-group row">
                                <h6 style="color: blue;" class="col-md-3 form-control-label" for="text-input">Bono</h6>
                                <div class="col-md-3">
                                    <h6 style="color: blue;" v-text="'$'+formatNumber(comision)"></h6>
                                </div>

                                <div class="col-md-6">
                                    <textarea rows="3" cols="5" v-model="descripcion" class="form-control" placeholder="Descripción"></textarea>
                                </div>
                            </div>


                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" class="btn btn-primary" v-if="tipoAccion==1 && proceso == 0" @click="generarBono()">Generar bono</button>
                            <button type="button" class="btn btn-primary" v-if="tipoAccion==2 && proceso == 0" @click="generarSegundoBono()">Generar segundo bono</button>
                        </div>
                    </div> 
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->

            <!--Inicio del modal observaciones-->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal2}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
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
                                
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                        </div>
                    </div>
                      <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>

           

        </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    export default {
        props:{
            rolId:{type: String}
        },
        data(){
            return{
                id:0,
                proceso:false,
                arrayPendientes:[],
                arrayAsesores:[], 
                arrayFraccionamientos:[],
                arrayEtapas:[],
                arrayAnticipos:[],
                arrayObservacion:[],

                observacion:'',
                comision:0,
                bono:0,
                fecha_pago:0,
                asesor:'',
                lote:'',
                etapa:'',
                manzana:'',
                proyecto:'',
                desde:'',
                hasta:'',
                descripcion:'',

                historial : 0,
                
                pagination2 : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },

                b_proyecto:'',
                b_etapa:'',
                b_manzana:'',
                b_lote:'',
                offset2 : 3,
                asesor_id:'',
                b_asesor_id:'',
                modal: 0,
                tituloModal: '',
                tipoAccion: 0,
                errorComision : 0,
                errorMostrarMsjComision : [],
                mes:'',
                anio:'',
                hoy:'',
                folio : '',
                bono_id:'',
                b_cliente:'',

                modal2:0,
                proceso:0,
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

                var from = this.pagination2.current_page - this.offset2;
                if(from < 1){
                    from = 1;
                }

                var to = from + (this.offset2 * 2);
                if(to >= this.pagination2.last_page){
                    to = this.pagination2.last_page;
                }

                var pagesArray2 = [];
                while(from <= to){
                    pagesArray2.push(from);
                    from++;
                }
                return pagesArray2;
            },

            isActived: function(){
                return this.pagination.current_page;
            },

            //Calcula los elementos de la paginación
            pagesNumber:function(){
                if(!this.pagination.to){
                    return [];
                }

                var from = this.pagination.current_page - this.offset2;
                if(from < 1){
                    from = 1;
                }

                var to = from + (this.offset2 * 2);
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
        },
       
        methods : {
            listarPendientes(page){
                let me = this;
                var url = '/bonos_ventas/index_contratos?page=' + page + '&b_proyecto=' + this.b_proyecto + 
                            '&b_etapa=' + this.b_etapa + '&b_asesor=' + this.b_asesor_id + '&cliente=' + this.b_cliente;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPendientes = respuesta.contratos.data;
                    me.pagination2 = respuesta.pagination;
                    me.hoy = respuesta.hoy;
                })
                .catch(function (error) {
                    console.log(error);
                })
            },

            listarHistorial(page){
                let me = this;
                var url = '/bonos_ventas/indexBonos?page=' + page + '&b_proyecto=' + this.b_proyecto + '&b_etapa=' + this.b_etapa
                                        + '&b_manzana=' + this.b_manzana + '&b_lote=' + this.b_lote  + '&cliente=' + this.b_cliente
                                         + '&b_asesor_id=' + this.b_asesor_id + '&desde=' + this.desde + '&hasta=' + this.hasta;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayAnticipos = respuesta.bonos.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                })
            },

            listarObservacion(buscar){
                let me = this;
                var url = '/bonos_ventas/listarObs?id=' + buscar ;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayObservacion = respuesta.observacion;
                    console.log(url);
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },

            agregarComentario(){
                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/bonos_ventas/storeObservacion',{
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

            //Select todas las etapas
            selectEtapas(buscar){
                let me = this;
                me.b_etapa = '';
                me.b_manzana = '';
                me.b_lote = '';
                
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

            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },

            cambiarPagina(page){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarHistorial(page);
            },

            cambiarPagina2(page){
                let me = this;
                //Actualiza la pagina actual
                me.pagination2.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarPendientes(page);
            },
            
            selectAsesores(){
                let me = this;
                me.arrayAsesores=[];
                var url = '/select/asesores2';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayAsesores = respuesta.personas;
                })
                .catch(function (error) {
                    console.log(error);
                });
              
            },  

            generarBono(){
                let me = this;
                me.proceso = 1;
                //Con axios se llama el metodo update de DepartamentoController
                axios.post('/bonos_ventas/storeBono',{
                    'contrato_id': this.id,
                    'fecha_pago' : this.fecha_pago,
                    'descripcion' : this.descripcion,
                    'num_bono' : 1
                    
                }).then(function (response){
                    me.listarPendientes(1);
                    me.listarHistorial(1);
                    me.cerrarModal();
                    me.proceso = 0;
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
                    me.proceso = 0;
                });
            },

            generarSegundoBono(){
                let me = this;
                me.proceso = 1;
                //Con axios se llama el metodo update de DepartamentoController
                axios.post('/bonos_ventas/segundoBono',{
                    'contrato_id': this.id,
                    'fecha_pago' : this.fecha_pago,
                    'descripcion' : this.descripcion,
                    'id' : this.bono_id
                }).then(function (response){
                    me.listarPendientes(1);
                    me.listarHistorial(1);
                    me.cerrarModal();
                    me.proceso = 0;
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
                    me.proceso = 0;
                });
            },

            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.asesor_id = '';
                this.descripcion = '';
                this.modal2 = '';
                this.observacion = '';
                this.arrayObservacion = [];
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

            abrirModal(accion,data =[]){
                switch(accion){
                    case 'generar':
                    {
                        this.modal = 1;
                        this.tituloModal='Generar Bono';
                        this.fecha_pago = this.hoy;
                        this.comision = 1000;
                        this.asesor = data['asesor'];
                        this.lote = data['num_lote'];
                        this.etapa = data['etapa'];
                        this.manzana = data['manzana'];
                        this.proyecto = data['proyecto'];
                        this.id = data['id'];
                        this.tipoAccion = 1
                        break;
                    }
                     case 'duplicar':
                    {
                        this.modal = 1;
                        this.tituloModal='Generar Segundo Bono';
                        this.fecha_pago = this.hoy;
                        this.comision = 1000;
                        this.asesor = data['asesor'];
                        this.lote = data['num_lote'];
                        this.etapa = data['etapa'];
                        this.manzana = data['manzana'];
                        this.proyecto = data['fraccionamiento'];
                        this.id = data['folio'];
                        this.bono_id = data['id'];
                        this.tipoAccion = 2;
                        break;
                    }
                    case 'observaciones':{
                        this.modal2 =1;
                        this.tituloModal='Observaciones';
                        this.observacion='';
                        this.usuario='';
                        this.id=data['id'];
                        this.listarObservacion(this.id);
                        break;
                    }
                }
            }
        
        },
        mounted() {          
            this.listarPendientes(1);
            this.listarHistorial(1);
            this.selectAsesores();
            this.selectFraccionamientos();
        }
    }
</script>
<style>

    .modal-content{
        width: 100% !important;
        position: absolute !important;
       
    }
    .modal-body{
        height: 350px;
        width: 100%;
        overflow-y: auto;
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
    @media (min-width: 600px){
        .btnagregar{
        margin-top: 2rem;
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
    .badge2 {
        display: inline-block;
        padding: 0.25em 0.4em;
        font-size: 85%;
        font-weight: bold;
        line-height: 1;
        color: #151b1f;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
    }
    .line-separator{
        height:1px;
        background:#717171;
        border-bottom:1px solid #c2cfd6;
    }
}
</style>
