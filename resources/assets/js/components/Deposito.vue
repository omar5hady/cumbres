<template>
    <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card scroll-box">
                    <div class="card-header" v-if="deposito==0">
                        <i class="fa fa-align-justify"></i> Pagares
                       
                    </div>
                    <div class="card-header" v-if="deposito==1">
                        <i class="fa fa-align-justify"></i> Depositos
                        <!--   Boton Nuevo    -->
                        <button v-if="restante!=0" type="button" @click="abrirModal('registrar')" class="btn btn-primary">
                            <i class="icon-plus"></i>&nbsp;Nuevo Deposito
                        </button>
                        <button type="button" @click="listarPagares(1,buscar, buscar2, buscar3, b_vencidos,criterio)" class="btn btn-secondary">
                            <i class="fa fa-mail-reply"></i>&nbsp;Regresar
                        </button>
                        <!---->
                    </div>
                    <div class="card-body" v-if="deposito==0">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-4" v-model="criterio" @click="buscar='', buscar2=''">
                                      <option value="creditos.fraccionamiento">Proyecto</option>
                                      <option value="contratos.id"># Referencia</option>
                                      <option value="personal.nombre">Cliente</option>
                                      <option value="pagos_contratos.pagado">Estatus</option>
                                      <option value="pagos_contratos.fecha_pago">Fecha de pago</option>
                                    </select>
                                    <select class="form-control" v-if="criterio=='pagos_contratos.pagado'" v-model="buscar">
                                        <option value="0">Pendientes</option>
                                        <option value="1">Abonados</option>
                                        <option value="2">Liquidados</option>
                                    </select>
                                    <select class="form-control" v-if="criterio=='creditos.fraccionamiento'" @click="selectEtapa(buscar)" v-model="buscar" >
                                        <option value="">Seleccionar</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.nombre" v-text="fraccionamientos.nombre"></option>
                                    </select>
                                     <select class="form-control" v-if="criterio=='creditos.fraccionamiento'" v-model="buscar2"  @keyup.enter="listarPagares(1,buscar, buscar2, buscar3, b_vencidos,criterio)" @click="selectManzana(buscar, buscar2)"> 
                                        <option value="">Etapa</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.num_etapa" v-text="etapas.num_etapa"></option>
                                    </select>
                                    <select class="form-control" v-if="criterio=='creditos.fraccionamiento'" v-model="buscar3" @keyup.enter="listarPagares(1,buscar, buscar2, buscar3, b_vencidos,criterio)"> 
                                        <option value="">Manzana</option>
                                        <option v-for="manzana in arrayManzana" :key="manzana.manzana" :value="manzana.manzana" v-text="manzana.manzana"></option>
                                    </select>
                                    
                                    <input type="date" v-if="criterio=='pagos_contratos.fecha_pago'" v-model="buscar" @keyup.enter="listarPagares(1,buscar, buscar2, buscar3, b_vencidos,criterio)" class="form-control" >
                                    <input type="date" v-if="criterio=='pagos_contratos.fecha_pago'" v-model="buscar2" @keyup.enter="listarPagares(1,buscar, buscar2, buscar3, b_vencidos,criterio)" class="form-control" >
                                    <input type="text" v-if="criterio=='contratos.id'|| criterio=='personal.nombre'" v-model="buscar" @keyup.enter="listarPagares(1,buscar, buscar2, buscar3, b_vencidos,criterio)" class="form-control" placeholder="Texto a buscar">
                                    
                                    <button v-if="b_vencidos==0" type="submit" @click="b_vencidos=1" class="btn btn-success"><i class="fa fa-check-square"></i> Todos</button>
                                    <button v-if="b_vencidos==1" type="submit" @click="b_vencidos=0" class="btn btn-danger"><i class="fa fa-window-close-o"></i> Vencidos</button>
                                    <button type="submit" @click="listarPagares(1,buscar, buscar2, buscar3, b_vencidos,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
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
                                        <th># Pagare</th>
                                        <th>Saldo</th>
                                        <th>Fecha limite de Pago</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="pagare in arrayPagares" :key="pagare.id">
                                        <td style="width:8%">
                                            <button type="button" @click="verDepositos(pagare)" class="btn btn-default btn-sm" title="Ver depositos">
                                            <i class="icon-eye"></i>
                                            </button> &nbsp;
                                        </td>
                                        <td v-text="pagare.folio"></td>
                                        <td v-text="pagare.nombre + ' ' +pagare.apellidos"></td>
                                        <td v-text="pagare.fraccionamiento"></td>
                                        <td v-text="pagare.etapa"></td>
                                        <td v-text="pagare.manzana"></td>
                                        <td v-text="pagare.num_lote"></td>
                                        <td v-text="parseInt(pagare.num_pago)+1"></td>
                                        <td v-text="'$'+formatNumber(pagare.restante)"></td>
                                        <td v-text="this.moment(pagare.fecha_pago).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td >
                                            <span v-if="pagare.diferencia > 0 && pagare.pagado != 2" class="badge badge-danger">Vencido</span>
                                            <span v-if="pagare.diferencia < 0 && pagare.pagado != 2" class="badge badge-warning"> Pendiente</span>
                                            <span v-if="pagare.pagado == 2" class="badge badge-success"> Pagado</span>
                                        </td>
                                    </tr>                               
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar, buscar2, buscar3, b_vencidos,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar, buscar2, buscar3, b_vencidos,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar, buscar2, buscar3, b_vencidos,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="card-body" v-if="deposito==1">
                        <div class="form-group row">
                            
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th># Ref</th>
                                        <th>Cliente</th>
                                        <th>Cuenta</th>
                                        <th># Recibo</th>
                                        <th>Monto</th>
                                        <th>Fecha de deposito</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="deposito in arrayDepositos" :key="deposito.id">
                                        <td style="width:12%">   
                                        <a type="button" target="_blank" class="btn btn-danger btn-sm" title="Imprimir" v-bind:href="'deposito/reciboPDF/'+deposito.id"> <i class="fa fa-file-pdf-o"></i></a>
                                         &nbsp;
                                            <button type="button" @click="abrirModal('actualizar',deposito)" class="btn btn-warning btn-sm" title="Editar deposito">
                                            <i class="icon-pencil"></i>
                                            </button> &nbsp;
                                        </td>
                                        <td v-text="referencia"></td>
                                        <td v-text="cliente"></td>
                                        <td v-text="deposito.banco"></td>
                                        <td v-text="deposito.num_recibo"></td>
                                        <td v-text="'$'+formatNumber(deposito.cant_depo)"></td>
                                        <td v-text="this.moment(deposito.fecha_pago).locale('es').format('DD/MMM/YYYY')"></td>
                                    </tr>                               
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
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
                                    <label class="col-md-3 form-control-label" for="text-input">Monto de pagare</label>
                                    <div class="col-md-3">
                                        <p v-text="'$'+formatNumber(monto_pagare)"></p>
                                    </div>
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha limite de pago</label>
                                    <div class="col-md-3">
                                        <input type="date" v-model="fecha_limite" disabled class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha deposito</label>
                                    <div class="col-md-6">
                                        <input type="date" v-model="fecha_deposito" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Cantidad a depositar</label>
                                    <div class="col-md-4">
                                        <input type="text" pattern="\d*" v-on:keypress="isNumber($event)" v-model="cant_depo" maxlength="10" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <p v-text="'$'+formatNumber(cant_depo)"></p>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Interes Moratorio</label>
                                    <div class="col-md-4">
                                        <input type="text" pattern="\d*" v-on:keypress="isNumber($event)" v-model="interes_mor" maxlength="10" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <p v-text="'$'+formatNumber(interes_mor)"></p>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Observación Interes Moratorio</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="obs_mor" class="form-control">
                                    </div>
                                </div>

                                 <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Interes Ordinario</label>
                                    <div class="col-md-4">
                                        <input type="text" pattern="\d*" v-on:keypress="isNumber($event)" v-model="interes_ord" maxlength="10" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <p v-text="'$'+formatNumber(interes_ord)"></p>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Observación Interes Ordinario</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="obs_ord" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row" v-if="tipoAccion==1">
                                    <label class="col-md-3 form-control-label" for="text-input">Saldo Restante</label>
                                    <div class="col-md-4">
                                        <p v-text="'$'+formatNumber(restante-cant_depo)"></p>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Numero de recibo</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="num_recibo" class="form-control">
                                    </div>
                                </div>

                                 <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Concepto</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="concepto" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Banco</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="banco">
                                            <option value="">Seleccione</option>
                                            <option v-for="banco in arrayBancos" :key="banco.num_cuenta" :value="banco.num_cuenta + '-' + banco.banco" v-text="banco.num_cuenta + '-' + banco.banco"></option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Div para mostrar los errores que mande validerDepartamento -->
                                <div v-show="errorDeposito" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjDeposito" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarDeposito()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarDeposito()">Actualizar</button>
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
                hoy:moment(),
                proceso:false,
                id:0,
                arrayPagares : [],
                arrayFraccionamientos : [],
                arrayEtapas : [],
                arrayManzana: [],
                arrayDepositos : [],
                arrayBancos : [],
                modal : 0,
                deposito : 0,
                tituloModal : '',
                tipoAccion: 0,
                errorDeposito : 0,
                errorMostrarMsjDeposito : [],

                cliente:'',
                referencia:'',
                num_pagare:'',
                proyecto:'',
                etapa:'',
                manzana:'',
                lote:'',
                fecha_limite:'',
                fecha_deposito:'',
                cant_depo:0,
                interes_mor:0,
                obs_mor:'',
                interes_ord:0,
                obs_ord:'',
                saldo:0,
                num_recibo:'',
                banco:'',
                concepto:'',
                restante:0,
                monto_pagare:0,
                pago_id:0,
                diferencia:0,

                pagination : {
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
                b_vencidos : 0
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
        },
        methods : {
            /**Metodo para mostrar los registros */
            listarPagares(page, buscar,buscar2,buscar3, b_vencidos, criterio){
                let me = this;
                me.cliente='';
                me.referencia='';
                me.num_pagare='';
                me.proyecto='';
                me.etapa='';
                me.manzana='';
                me.lote='';
                me.fecha_limite='';
                me.fecha_deposito='';
                me.cant_depo=0;
                me.interes_mor=0;
                me.obs_mor='';
                me.interes_ord=0;
                me.obs_ord='';
                me.saldo=0;
                me.num_recibo='';
                me.banco='';
                me.concepto='';
                me.restante=0;
                me.deposito=0;
                me.monto_pagare=0;
                me.pago_id=0;
                me.diferencia=0;
                var url = '/pagares?page=' + page + '&buscar=' + buscar + '&buscar2=' + buscar2 + '&b_vencidos=' + b_vencidos + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPagares = respuesta.pagares.data;
                    me.pagination = respuesta.pagination;
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
            cambiarPagina(page, buscar, buscar2, buscar3, b_vencidos, criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarPagares(page,buscar, buscar2,buscar3, b_vencidos ,criterio);
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

            selectCuenta(){
                let me = this;
                me.arrayBancos=[];
                var url = '/select_cuenta';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayBancos = respuesta.cuentas;
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
            registrarDeposito(){
                if(this.validarDeposito()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.restante = this.restante - this.cant_depo;

                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/deposito/registrar',{
                    'pago_id':this.pago_id,
                    'cant_depo':this.cant_depo,
                    'interes_mor':this.interes_mor,
                    'interes_ord':this.interes_ord,
                    'obs_mor':this.obs_mor,
                    'obs_ord':this.obs_ord,
                    'num_recibo':this.num_recibo,
                    'banco':this.banco,
                    'concepto':this.concepto,
                    'fecha_pago':this.fecha_deposito,
                    'restante':this.restante
                }).then(function (response){
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarDepositos(); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Deposito agregado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            actualizarDeposito(){
                if(this.validarDeposito()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                let me = this;
                //Con axios se llama el metodo update de DepartamentoController
                axios.put('/deposito/actualizar',{
                    'pago_id':this.pago_id,
                    'cant_depo':this.cant_depo,
                    'interes_mor':this.interes_mor,
                    'interes_ord':this.interes_ord,
                    'obs_mor':this.obs_mor,
                    'obs_ord':this.obs_ord,
                    'num_recibo':this.num_recibo,
                    'banco':this.banco,
                    'concepto':this.concepto,
                    'fecha_pago':this.fecha_deposito,
                    'id':this.id,
                }).then(function (response){
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarDepositos(); //se enlistan nuevamente los registros
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
            verDepositos(data=[]){
                this.cliente=data['nombre']+' '+data['apellidos'];
                this.referencia=data['folio'];
                this.num_pagare= parseInt(data['num_pago']) + 1;
                this.proyecto=data['fraccionamiento'];
                this.etapa=data['etapa'];
                this.manzana=data['manzana'];
                this.lote=data['num_lote'];
                this.fecha_limite=data['fecha_pago'];
                this.fecha_deposito='';
                this.cant_depo=0;
                this.interes_mor=0;
                this.obs_mor='';
                this.interes_ord=0;
                this.obs_ord='';
                this.saldo=0;
                this.num_recibo='';
                this.banco='';
                this.concepto='';
                this.monto_pagare = data['monto_pago'];
                this.restante= data['restante'];
                this.pago_id = data['pago'];
                this.diferencia=data['diferencia'];

                this.listarDepositos();

               
            },
            listarDepositos(){
                let me = this;
                var url = '/depositos?buscar=' + me.pago_id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayDepositos = respuesta.depositos;
                    me.deposito = 1;
                    me.restante = respuesta.restante;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';

                this.fecha_deposito='';
                this.cant_depo=0;
                this.interes_mor=0;
                this.obs_mor='';
                this.interes_ord=0;
                this.obs_ord='';
                this.saldo=0;
                this.num_recibo='';
                this.banco='';
                this.concepto='';

                this.errorDeposito = 0;
                this.errorMostrarMsjDeposito = [];

            },
            validarDeposito(){
                this.errorDeposito=0;
                this.errorMostrarMsjDeposito=[];

                if(this.fecha_deposito== '') //Si la variable departamento esta vacia
                    this.errorMostrarMsjDeposito.push("Ingresar fecha de deposito.");

                if(this.monto_pagare== '') //Si la variable departamento esta vacia
                    this.errorMostrarMsjDeposito.push("Ingresar monto a depositar.");
                
                if(this.num_recibo == '') //Si la variable departamento esta vacia
                    this.errorMostrarMsjDeposito.push("Ingresar numero de recibo.");
                
                if(this.concepto == '') //Si la variable departamento esta vacia
                    this.errorMostrarMsjDeposito.push("Ingresar concepto.");

                if(this.banco == '') //Si la variable departamento esta vacia
                    this.errorMostrarMsjDeposito.push("Seleccionar cuenta.");
                
                if(this.tipoAccion==1)
                    if(this.restante<0){
                        this.errorMostrarMsjDeposito.push("El deposito supera a la cantidad restante.");
                    }

                if(this.errorMostrarMsjDeposito.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorDeposito = 1;

                return this.errorDeposito;
            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(accion,data =[]){
                this.selectCuenta();
                switch(accion){
                    case 'registrar':
                    {
                        this.modal = 1;
                        this.tituloModal = 'Nuevo deposito para pagare: '+ this.num_pagare;
                        if(this.diferencia>0){
                            this.interes_mor = ((this.monto_pagare * .05)/30) * this.diferencia;
                            this.interes_mor=this.interes_mor.toFixed(2);
                        }
                       
                        this.tipoAccion = 1;
                        break;
                    }
                    case 'actualizar':
                    {
                        //console.log(data);
                        this.modal =1;
                        this.tituloModal='Actualizar deposito para pagare: '+ this.num_pagare;
                        this.tipoAccion=2;

                        this.fecha_deposito=data['fecha_pago'];
                        this.cant_depo=data['cant_depo'];
                        this.interes_mor=data['interes_mor'];
                        this.obs_mor=data['obs_mor'];
                        this.pago_id=data['pago_id'];
                        this.interes_ord=data['interes_ord'];
                        this.obs_ord=data['obs_ord'];
                        this.num_recibo=data['num_recibo'];
                        this.banco=data['banco'];
                        this.concepto=data['concepto'];
                        
                        this.id=data['id'];
                        break;
                    }
                }
            }
        },
        mounted() {
            this.listarPagares(1,this.buscar, this.buscar2, this.buscar3, this.b_vencidos, this.criterio);
            this.selectFraccionamiento();

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
