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
                        <i class="fa fa-align-justify"></i> Gastos administrativos
                        <button type="button" class="btn btn-primary" v-if="listado == 0" @click="listado = 1, listarContratos(1,b,b2,b3,criterio2)">
                            <i class="icon-plus"></i>&nbsp;Nuevo Gasto Administrativo
                        </button>
                        <button type="button" class="btn btn-basic" v-if="listado == 1" @click="listado = 0">
                            <i class="fa fa-arrow-left"></i>&nbsp;atras
                        </button>
                    </div>
                    
                <!-- Listado de los gastos administrativos -->
                <template v-if="listado==0">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <select class="form-control" v-model="b_empresa" >
                                    <option value="">Empresa constructora</option>
                                    <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-4" v-model="criterio" @click="buscar='', buscar2=''">
                                      <option value="creditos.fraccionamiento">Proyecto</option>
                                      <option value="contratos.id"># Referencia</option>
                                      <option value="personal.nombre">Cliente</option>
                                      <option value="gastos_admin.fecha">Fecha</option>
                                    </select>
                                    <select class="form-control" v-if="criterio=='creditos.fraccionamiento'" @click="selectEtapa(buscar)" v-model="buscar" >
                                        <option value="">Seleccionar</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.nombre" v-text="fraccionamientos.nombre"></option>
                                    </select>
                                     <select class="form-control" v-if="criterio=='creditos.fraccionamiento'" v-model="buscar2"  @keyup.enter="listarGastos(1,buscar, buscar2, buscar3, criterio)" @click="selectManzana(buscar, buscar2)"> 
                                        <option value="">Etapa</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.num_etapa" v-text="etapas.num_etapa"></option>
                                    </select>
                                    <select class="form-control" v-if="criterio=='creditos.fraccionamiento'" v-model="buscar3" @keyup.enter="listarGastos(1,buscar, buscar2, buscar3, criterio)"> 
                                        <option value="">Manzana</option>
                                        <option v-for="manzana in arrayManzana" :key="manzana.manzana" :value="manzana.manzana" v-text="manzana.manzana"></option>
                                    </select>
                                    
                                    <input type="date" v-if="criterio=='gastos_admin.fecha'" v-model="buscar" @keyup.enter="listarGastos(1,buscar, buscar2, buscar3, criterio)" class="form-control" >
                                    <input type="date" v-if="criterio=='gastos_admin.fecha'" v-model="buscar2" @keyup.enter="listarGastos(1,buscar, buscar2, buscar3, criterio)" class="form-control" >
                                    <input type="text" v-if="criterio=='contratos.id'|| criterio=='personal.nombre'" v-model="buscar" @keyup.enter="listarGastos(1,buscar, buscar2, buscar3, criterio)" class="form-control" placeholder="Texto a buscar">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <button type="submit" @click="listarGastos(1,buscar, buscar2, buscar3, criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    <a :href="'/gastos/excel?buscar=' + buscar + '&buscar2=' + buscar2 + '&buscar3=' + buscar3 + 
                                            '&criterio=' + criterio +'&b_empresa='+b_empresa"
                                        class="btn btn-success"><i class="fa fa-file-text"></i> Excel
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th># Ref</th>
                                        <th>Cliente</th>
                                        <th>Proyecto</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th># Lote</th>
                                        <th>Tipo de gasto</th>
                                        <th>Fecha</th>
                                        <th>Monto</th>
                                        <th>Observación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="gasto in arrayGastos" :key="gasto.gastoId">
                                        <td style="width:8%">
                                            <button type="button" @click="abrirModal('actualizar',gasto)" class="btn btn-warning btn-sm" title="Ver depositos">
                                            <i class="icon-pencil"></i>
                                            </button> &nbsp;
                                            <button type="button" class="btn btn-danger btn-sm" @click="eliminarGasto(gasto.gastoId)">
                                            <i class="icon-trash"></i>
                                            </button>
                                        </td>
                                        <td v-text="gasto.folio"></td>
                                        <td v-text="gasto.nombre + ' ' + gasto.apellidos"></td>
                                        <td v-text="gasto.fraccionamiento"></td>
                                        <td v-text="gasto.etapa"></td>
                                        <td v-text="gasto.manzana"></td>
                                        <td v-text="gasto.num_lote"></td>
                                        <td v-text="gasto.concepto"></td>
                                        <td v-text="this.moment(gasto.fecha).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td v-text="'$'+formatNumber(gasto.costo)"></td>
                                        <td v-text="gasto.observacion"></td>
                                    </tr>                               
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar, buscar2, buscar3, criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar, buscar2, buscar3, criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar, buscar2, buscar3, criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>                 
                </template>

                <!-- Listado de los contratos -->
                <template v-if="listado==1">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <select class="form-control" v-model="b_empresa" >
                                    <option value="">Empresa constructora</option>
                                    <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-4" v-model="criterio2" @click="b='', b2=''">
                                        <option value="creditos.fraccionamiento">Proyecto</option>
                                        <option value="contratos.id"># Referencia</option>
                                        <option value="personal.nombre">Cliente</option>
                                    </select>
                                    <select class="form-control" v-if="criterio2=='creditos.fraccionamiento'" @click="selectEtapa(b)" v-model="b" >
                                        <option value="">Seleccionar</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.nombre" v-text="fraccionamientos.nombre"></option>
                                    </select>
                                        <select class="form-control" v-if="criterio2=='creditos.fraccionamiento'" v-model="b2"  @keyup.enter="listarContratos(1,b,b2,b3,criterio2)" @click="selectManzana(b, b2)"> 
                                        <option value="">Etapa</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.num_etapa" v-text="etapas.num_etapa"></option>
                                    </select>
                                    <select class="form-control" v-if="criterio2=='creditos.fraccionamiento'" v-model="b3" @keyup.enter="listarContratos(1,b,b2,b3,criterio2)"> 
                                        <option value="">Manzana</option>
                                        <option v-for="manzana in arrayManzana" :key="manzana.manzana" :value="manzana.manzana" v-text="manzana.manzana"></option>
                                    </select>
                                    
                                    <input type="text" v-if="criterio2=='contratos.id'|| criterio2=='personal.nombre'" v-model="b" @keyup.enter="listarContratos(1,b,b2,b3,criterio2)" class="form-control" placeholder="Texto a buscar">
                                    
                                    <button type="submit" @click="listarContratos(1,b,b2,b3,criterio2)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                    
                                        <th># Ref</th>
                                        <th>Cliente</th>
                                        <th>Proyecto</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th># Lote</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="contrato in arrayContratos" :key="contrato.id" @dblclick="abrirModal('registrar',contrato)" title="Doble clic">
                                    
                                        <td v-text="contrato.folio"></td>
                                        <td>
                                            <a href="#" v-text="contrato.nombre_cliente"></a>
                                        </td>
                                        <td v-text="contrato.fraccionamiento"></td>
                                        <td v-text="contrato.etapa"></td>
                                        <td v-text="contrato.manzana"></td>
                                        <td v-text="contrato.num_lote"></td>
                                    </tr>                               
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination2.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page - 1,b,b2,b3,criterio2)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page2 in pagesNumber2" :key="page2" :class="[page2 == isActived2 ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(page2,b,b2,b3,criterio2)" v-text="page2"></a>
                                </li>
                                <li class="page-item" v-if="pagination2.current_page < pagination2.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page + 1,b,b2,b3,criterio2)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>                 
                </template>
                
            </div>
            
        </div>

        <!--Inicio del modal agregar/actualizar-->
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
                                <label class="col-md-3 form-control-label" for="text-input"># Ref</label>
                                <div class="col-md-9">
                                    <input type="text" disabled v-model="referencia" maxlength="10" class="form-control" placeholder="Numero de referencia">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Proyecto</label>
                                <div class="col-md-3">
                                    <input type="text" disabled v-model="proyecto" maxlength="50" class="form-control">
                                </div>
                                <label class="col-md-3 form-control-label" for="text-input">Etapa</label>
                                <div class="col-md-3">
                                    <input type="text" v-model="etapa" disabled maxlength="50" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Manzana</label>
                                <div class="col-md-3">
                                    <input type="text" disabled v-model="manzana" maxlength="50" class="form-control">
                                </div>
                                <label class="col-md-3 form-control-label" for="text-input"># Lote</label>
                                <div class="col-md-3">
                                    <input type="text" v-model="lote" disabled maxlength="50" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Fecha</label>
                                <div class="col-md-6">
                                    <input type="date" v-model="fecha" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Concepto</label>
                                <div class="col-md-6">
                                    <select class="form-control" v-model="concepto">
                                            <option value="Avaluo">Avaluo</option>
                                            <option value="Ajuste">Ajuste</option>
                                            <option value="2do Estudio">2do Estudio</option>
                                            <option value="Aviso preventivo">Aviso preventivo</option>
                                            <option value="Carta de no propiedad">Carta de no propiedad</option>
                                            <option value="Comision por apertura">Comision por apertura</option>
                                            <option value="Devolucion">Devolucion</option>
                                            <option value="Folio de escritura cancelado">Folio de escritura cancelado</option>
                                            <option value="Gastos de escrituracion">Gastos de escrituracion</option>
                                            <option value="Honorarios">Honorarios</option>
                                            <option value="Ingreso de expediente">Ingreso de expediente</option>
                                            <option value="Intereses moratorios">Intereses moratorios</option>
                                            <option value="Intereses ordinarios">Intereses ordinarios</option>
                                            <option value="Investigacion">Investigacion</option>
                                            <option value="Pedido especial">Pedido especial</option>
                                            <option value="Registro de credito">Registro de credito</option>
                                        </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Costo</label>
                                <div class="col-md-4">
                                    <input type="text" pattern="\d*" v-on:keypress="isNumber($event)" v-model="costo" maxlength="10" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <p v-text="'$'+formatNumber(costo)"></p>
                                </div>
                            </div>
                              <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Observacion</label>
                                <div class="col-md-6">
                                     <textarea rows="1" cols="30" class="form-control" v-model="observacion" placeholder="Observaciones"></textarea>
                                </div>
                            </div>


                            <!-- Div para mostrar los errores que mande validerDepartamento -->
                            <div v-show="errorGasto" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjGasto" :key="error" v-text="error">
                                    </div>
                                </div>
                            </div>
                    </div>
                    <!-- Botones del modal -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                        <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarGasto()">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarGasto()">Actualizar</button>
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
                
                proceso:false,
                id:0,
                
                arrayFraccionamientos : [],
                arrayEtapas : [],
                arrayManzana: [],
                arrayContratos: [],
                arrayGastos: [],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                errorGasto: 0,
                errorMostrarMsjGasto: [],

                fecha: '',
                costo: 0,
                observacion: '',
                referencia: 0,
                etapa: '',
                proyecto: '',
                manzana: '',
                lote: '',
                concepto: '',

                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                 pagination2 : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'creditos.fraccionamiento', 
                buscar : '',
                buscar2: '',
                buscar3:'',

                criterio2: 'creditos.fraccionamiento',
                b: '',
                b2: '',
                b3: '',

                listado: 0,
                b_empresa:'',
                empresas:[],
            }
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
             isActived2: function(){
                return this.pagination2.current_page;
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
            }
        },
          
        
        methods : {
            /**Metodo para mostrar los registros */
            listarGastos(page, buscar,buscar2,buscar3,criterio){
                let me = this;
                var url = '/gastos/index?page=' + page + '&buscar=' + buscar + '&buscar2=' + buscar2 + '&buscar3=' + buscar3 + 
                '&criterio=' + criterio +'&b_empresa='+this.b_empresa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayGastos = respuesta.gastos.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            listarContratos(page,b,b2,b3,criterio2){
                let me = this;
                var url = '/gastos/indexContratos?page=' + page + '&b=' + b + '&b2=' + b2 + '&b3=' + b3 + '&criterio2=' + criterio2
                +'&b_empresa='+this.b_empresa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayContratos = respuesta.contratos.data;
                    me.pagination2 = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
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

            cambiarPagina(page, buscar, buscar2, buscar3,  criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarGastos(page,buscar, buscar2,buscar3,criterio);
            },

            cambiarPagina2(page2, b, b2, b3,  criterio2){
                let me = this;
                //Actualiza la pagina actual
                me.pagination2.current_page = page2;
                //Envia la petición para visualizar la data de esta pagina
                me.listarContratos(page2,b, b2,b3,criterio2);
            },

            selectFraccionamiento(){
                let me = this;
                me.buscar="";
                me.buscar2="";
                me.buscar3="";
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
                me.buscar2="";
                me.buscar3="";
                                
                me.arrayEtapas=[];
                var url = '/select_etapa?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEtapas = respuesta.etapas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectManzana(buscar,buscar2){
                let me = this;
                me.buscar3=""
                                
                me.arrayManzana=[];
                var url = '/selectManzana_dist?buscar=' + buscar +'&buscar2=' + buscar2;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayManzana = respuesta.manzanas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            registrarGasto(){
                if(this.validarGasto()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/gastos/registrar',{
                    'contrato_id':this.referencia,
                    'concepto':this.concepto,
                    'costo':this.costo,
                    'observacion':this.observacion,
                    'fecha':this.fecha
                }).then(function (response){
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listado = 0;
                    me.listarGastos(me.pagination.current_page,me.buscar, me.buscar2, me.buscar3, me.criterio); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Gasto agregado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            actualizarGasto(){
                if(this.validarGasto()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                let me = this;
                //Con axios se llama el metodo update de DepartamentoController
                axios.put('/gastos/actualizar',{
                    'concepto':this.concepto,
                    'costo':this.costo,
                    'observacion':this.observacion,
                    'fecha':this.fecha,
                    'id':this.id,
                }).then(function (response){
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarGastos(me.pagination.current_page,me.buscar, me.buscar2, me.buscar3, me.criterio); //se enlistan nuevamente los registros
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

            eliminarGasto(id){
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

                axios.delete('/gastos/eliminar', 
                        {params: {'id': id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Gasto borrado correctamente.',
                        'success'
                        )
                        me.listarGastos(me.pagination.current_page,me.buscar, me.buscar2, me.buscar3, me.criterio);
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
        
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';

                this.fecha='';
                this.costo=0;
                this.observacion='';
                this.etapa='';
                this.proyecto='';
                this.manzana='';
                this.lote='';
                this.referencia=0;
                this.concepto='';

                this.errorGasto = 0;
                this.errorMostrarMsjGasto = [];

            },

            validarGasto(){
                this.errorGasto=0;
                this.errorMostrarMsjGasto=[];

                if(this.fecha== '') //Si la variable departamento esta vacia
                    this.errorMostrarMsjGasto.push("Ingresar una fecha.");

                if(this.costo== '') //Si la variable departamento esta vacia
                    this.errorMostrarMsjGasto.push("Ingresar un costo.");
                
                if(this.concepto == '') //Si la variable departamento esta vacia
                    this.errorMostrarMsjGasto.push("Indicar un concepto.");
              
                if(this.errorMostrarMsjGasto.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorGasto = 1;

                return this.errorGasto;
            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(accion,data =[]){
               
                switch(accion){
                    case 'registrar':
                    {
                        this.modal = 1;
                        this.tituloModal = 'Registrar un nuevo gasto administrativo';
                        this.referencia = data['folio'];
                        this.proyecto = data['fraccionamiento'];
                        this.etapa = data['etapa'];
                        this.manzana = data['manzana'];
                        this.lote = data['num_lote'];
                        this.tipoAccion = 1;
                        break;
                    }
                    case 'actualizar':
                    {
                        //console.log(data);
                        this.modal =1;
                        this.tituloModal='Actualizar gasto administrativo';
                        this.tipoAccion=2;
                        this.id = data['gastoId'];
                        this.referencia = data['folio'];
                        this.proyecto = data['fraccionamiento'];
                        this.etapa = data['etapa'];
                        this.manzana = data['manzana'];
                        this.lote = data['num_lote'];
                        this.fecha = data['fecha'];
                        this.concepto = data['concepto'];
                        this.observacion = data['observacion'];
                        this.costo = data['costo'];
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
            this.listarGastos(1,this.buscar, this.buscar2, this.buscar3, this.criterio);
            this.selectFraccionamiento();
            this.getEmpresa();
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


</style>
