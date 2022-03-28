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
                        <i class="fa fa-align-justify"></i> Integración de cobros
                        <!--   Boton Nuevo    -->
                        <button type="button" 
                        v-if="vista== 0 && userName == 'jovanni.t' || vista== 0 && userName == 'shady'
                            || vista== 0 && userName == 'j.gaitan'
                            || vista== 0 && userName == 'ale.teran'
                            || vista== 0 && userName == 'sandra.rdz'
                            || vista== 0 && userName == 'eli_hdz'
                        " 
                        @click="abrirModal('registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                        <!---->
                        <button type="button" v-if="vista== 1" @click="limpiarDatos()" class="btn btn-secondary">
                            <i class="fa fa-reply"></i>&nbsp;Regresar
                        </button>
                    </div>
                    <div class="card-body" v-if="vista == 0">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text"  v-model="buscar" @keyup.enter="listarIntegraciones(1)" class="form-control" placeholder="Cliente">
                                    <button type="submit" @click="listarIntegraciones(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>

                        <ul class="nav nav2 nav-tabs" id="myTab1" role="tablist">
                            <li class="nav-item" >
                                <a @click="tab = 0, listarIntegraciones(1)" class="nav-link" v-bind:class="{ 'text-info active': tab==0 }" v-text="'Pendientes'"></a>
                            </li>
                            <li class="nav-item">
                                <a @click="tab = 1, listarIntegraciones(1)" class="nav-link" v-bind:class="{ 'text-info active': tab==1 }" v-text="'Historial'"></a>
                            </li>
                        </ul>

                        <div class="tab-content" id="myTab1Content"> 
                            <!-- Pendientes -->
                            <div class="tab-pane fade" v-bind:class="{ 'active show': tab==0 }" v-if="tab == 0">

                                <div class="table-responsive"> 
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Folio</th>
                                                <th>Cliente</th>
                                                <th>Proyecto</th>
                                                <th>Etapa</th>
                                                <th>Manzana</th>
                                                <th>Lote</th>
                                                <th>Valor</th>
                                                <th>Crédito</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="integracion in arrayIntegraciones.data" :key="integracion.id">
                                                <td class="td2">
                                                    <button 
                                                    type="button" @click="verDetalle(integracion, 1)" class="btn btn-primary btn-sm"
                                                        title="Ver Detalle"
                                                    >
                                                        <i class="icon-eye"></i>
                                                    </button>
                                                    <button title="Descargar integración"
                                                        type="button" class="btn btn-success btn-sm">
                                                        <a style="color:white" :href="'/integracionCobros/exportFormat?id=' + integracion.id">
                                                            <i class="fa fa-file-text"></i>
                                                        </a>
                                                    </button>
                                                </td>
                                                <td class="td2" v-text="integracion.contrato_id"></td>
                                                <td class="td2" v-text="integracion.nombre_completo"></td>
                                                <td class="td2" v-text="integracion.proyecto"></td>
                                                <td class="td2" v-text="integracion.etapa"></td>
                                                <td class="td2" v-text="integracion.manzana"></td>
                                                <td class="td2" v-text="integracion.num_lote"></td>
                                                <td class="td2" v-text="'$'+ formatNumber(integracion.valor_escrituras)"></td>
                                                <td class="td2" v-text="integracion.tipo_credito+'-'+integracion.institucion"></td>
                                            </tr>                               
                                        </tbody>
                                    </table>
                                </div>
                                <hr>
                                <nav>
                                    <!--Botones de paginacion -->
                                <!--Botones de paginacion -->
                                    <ul class="pagination">
                                        <li class="page-item" v-if="arrayIntegraciones.current_page > 5" @click="listarIntegraciones(1)">
                                            <a class="page-link" href="#" >Inicio</a>
                                        </li>
                                        <li class="page-item" v-if="arrayIntegraciones.current_page > 1"
                                            @click="listarIntegraciones(arrayIntegraciones.current_page-1)">
                                            <a class="page-link" href="#" >Ant</a>
                                        </li>

                                        <li class="page-item" v-if="arrayIntegraciones.current_page-3 >= 1"
                                            @click="listarIntegraciones(arrayIntegraciones.current_page-3)">
                                            <a class="page-link" href="#" v-text="arrayIntegraciones.current_page-3"></a>
                                        </li>
                                        <li class="page-item" v-if="arrayIntegraciones.current_page-2 >= 1"
                                            @click="listarIntegraciones(arrayIntegraciones.current_page-2)">
                                            <a class="page-link" href="#" v-text="arrayIntegraciones.current_page-2"></a>
                                        </li>
                                        <li class="page-item" v-if="arrayIntegraciones.current_page-1 >= 1"
                                            @click="listarIntegraciones(arrayIntegraciones.current_page-1)">
                                            <a class="page-link" href="#" v-text="arrayIntegraciones.current_page-1"></a>
                                        </li>
                                        <li class="page-item active" >
                                            <a class="page-link" href="#" v-text="arrayIntegraciones.current_page"></a>
                                        </li>
                                        <li class="page-item" 
                                            v-if="arrayIntegraciones.current_page+1 <= arrayIntegraciones.last_page">
                                            <a class="page-link" href="#" @click="listarIntegraciones(arrayIntegraciones.current_page+1)" 
                                            v-text="arrayIntegraciones.current_page+1"></a>
                                        </li>
                                        <li class="page-item" 
                                            v-if="arrayIntegraciones.current_page+2 <= arrayIntegraciones.last_page">
                                            <a class="page-link" href="#" @click="listarIntegraciones(arrayIntegraciones.current_page+2)"
                                            v-text="arrayIntegraciones.current_page+2"></a>
                                        </li>
                                        <li class="page-item" 
                                            v-if="arrayIntegraciones.current_page+3 <= arrayIntegraciones.last_page">
                                            <a class="page-link" href="#" @click="listarIntegraciones(arrayIntegraciones.current_page+3)"
                                            v-text="arrayIntegraciones.current_page+3"></a>
                                        </li>

                                        <li class="page-item" v-if="arrayIntegraciones.current_page < arrayIntegraciones.last_page"
                                            @click="listarIntegraciones(arrayIntegraciones.current_page+1)">
                                            <a class="page-link" href="#" >Sig</a>
                                        </li>
                                        <li class="page-item" v-if="arrayIntegraciones.current_page < 5 && arrayIntegraciones.last_page > 5" @click="listarIntegraciones(arrayIntegraciones.last_page)">
                                            <a class="page-link" href="#" >Ultimo</a>
                                        </li>
                                    </ul>
                                </nav>

                            </div>
                        </div>

                        <div class="tab-content" id="myTab1Content"> 
                            <!-- Pendientes -->
                            <div class="tab-pane fade" v-bind:class="{ 'active show': tab==1 }" v-if="tab == 1">

                                <div class="table-responsive"> 
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Folio</th>
                                                <th>Cliente</th>
                                                <th>Proyecto</th>
                                                <th>Etapa</th>
                                                <th>Manzana</th>
                                                <th>Lote</th>
                                                <th>Valor</th>
                                                <th>Crédito</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="integracion in arrayIntegraciones.data" :key="integracion.id">
                                                <td class="td2">
                                                    <button 
                                                    type="button" @click="verDetalle(integracion, 1)" class="btn btn-primary btn-sm"
                                                        title="Ver Detalle"
                                                    >
                                                        <i class="icon-eye"></i>
                                                    </button>
                                                    <button title="Descargar integración"
                                                        type="button" class="btn btn-success btn-sm">
                                                        <a style="color:white" :href="'/integracionCobros/exportFormat?id=' + integracion.id">
                                                            <i class="fa fa-file-text"></i>
                                                        </a>
                                                    </button>
                                                    <button title="Descargar contrato"
                                                        type="button" class="btn btn-danger btn-sm">
                                                        <a style="color:white" target="_blank" v-bind:href="'/integracionCobros/printConvenioModificatorio?id='+integracion.contrato_id">
                                                            <i class="fa fa-file-pdf-o"></i>
                                                        </a>
                                                    </button>
                                                </td>
                                                <td class="td2" v-text="integracion.contrato_id"></td>
                                                <td class="td2" v-text="integracion.nombre_completo"></td>
                                                <td class="td2" v-text="integracion.proyecto"></td>
                                                <td class="td2" v-text="integracion.etapa"></td>
                                                <td class="td2" v-text="integracion.manzana"></td>
                                                <td class="td2" v-text="integracion.num_lote"></td>
                                                <td class="td2" v-text="'$'+ formatNumber(integracion.valor_escrituras)"></td>
                                                <td class="td2" v-text="integracion.tipo_credito+'-'+integracion.institucion"></td>
                                            </tr>                               
                                        </tbody>
                                    </table>
                                </div>
                                <hr>
                                <nav>
                                    <!--Botones de paginacion -->
                                <!--Botones de paginacion -->
                                    <ul class="pagination">
                                        <li class="page-item" v-if="arrayIntegraciones.current_page > 5" @click="listarIntegraciones(1)">
                                            <a class="page-link" href="#" >Inicio</a>
                                        </li>
                                        <li class="page-item" v-if="arrayIntegraciones.current_page > 1"
                                            @click="listarIntegraciones(arrayIntegraciones.current_page-1)">
                                            <a class="page-link" href="#" >Ant</a>
                                        </li>

                                        <li class="page-item" v-if="arrayIntegraciones.current_page-3 >= 1"
                                            @click="listarIntegraciones(arrayIntegraciones.current_page-3)">
                                            <a class="page-link" href="#" v-text="arrayIntegraciones.current_page-3"></a>
                                        </li>
                                        <li class="page-item" v-if="arrayIntegraciones.current_page-2 >= 1"
                                            @click="listarIntegraciones(arrayIntegraciones.current_page-2)">
                                            <a class="page-link" href="#" v-text="arrayIntegraciones.current_page-2"></a>
                                        </li>
                                        <li class="page-item" v-if="arrayIntegraciones.current_page-1 >= 1"
                                            @click="listarIntegraciones(arrayIntegraciones.current_page-1)">
                                            <a class="page-link" href="#" v-text="arrayIntegraciones.current_page-1"></a>
                                        </li>
                                        <li class="page-item active" >
                                            <a class="page-link" href="#" v-text="arrayIntegraciones.current_page"></a>
                                        </li>
                                        <li class="page-item" 
                                            v-if="arrayIntegraciones.current_page+1 <= arrayIntegraciones.last_page">
                                            <a class="page-link" href="#" @click="listarIntegraciones(arrayIntegraciones.current_page+1)" 
                                            v-text="arrayIntegraciones.current_page+1"></a>
                                        </li>
                                        <li class="page-item" 
                                            v-if="arrayIntegraciones.current_page+2 <= arrayIntegraciones.last_page">
                                            <a class="page-link" href="#" @click="listarIntegraciones(arrayIntegraciones.current_page+2)"
                                            v-text="arrayIntegraciones.current_page+2"></a>
                                        </li>
                                        <li class="page-item" 
                                            v-if="arrayIntegraciones.current_page+3 <= arrayIntegraciones.last_page">
                                            <a class="page-link" href="#" @click="listarIntegraciones(arrayIntegraciones.current_page+3)"
                                            v-text="arrayIntegraciones.current_page+3"></a>
                                        </li>

                                        <li class="page-item" v-if="arrayIntegraciones.current_page < arrayIntegraciones.last_page"
                                            @click="listarIntegraciones(arrayIntegraciones.current_page+1)">
                                            <a class="page-link" href="#" >Sig</a>
                                        </li>
                                        <li class="page-item" v-if="arrayIntegraciones.current_page < 5 && arrayIntegraciones.last_page > 5" @click="listarIntegraciones(arrayIntegraciones.last_page)">
                                            <a class="page-link" href="#" >Ultimo</a>
                                        </li>
                                    </ul>
                                </nav>

                            </div>
                        </div>
                        
                        
                    </div>


                    <div class="card-body" v-if="vista == 1"> 

                        <div class="col-md-12">
                            <div class="form-group" v-if="datos.emp_constructora != datos.emp_terreno">
                                <center> <h5 style="color: #153157;">CONCRETANIA / GRUPO CONSTRUCTOR CUMBRES S.A. DE C.V.</h5> </center>
                            </div>
                            <div class="form-group" v-else>
                                <center> <h5 style="color: #153157;">{{datos.emp_constructora}}</h5> </center>
                            </div>
                        </div> 

                        <div class="col-md-12">
                            <div class="form-group">
                                <center><h6>Integración de Cobros para la Escrituración</h6></center>
                            </div>
                        </div> 

                        <hr>

                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-md-2 form-control-label" style="color:#14396B;" for="text-input">
                                    <strong>Nombre del Cliente:</strong>
                                </label>
                                <div class="col-md-4">
                                    <input disabled type="text" v-model="datos.nombre_completo" class="form-control" placeholder="Nombre del cliente">
                                </div>
                                <label class="col-md-2 form-control-label" style="color:#14396B;" for="text-input">
                                    <strong>Proyecto</strong>
                                </label>
                                <div class="col-md-4">
                                    <input disabled type="text" v-model="datos.proyecto" class="form-control">
                                </div>
                            </div>
                        </div> 

                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-md-1 form-control-label" style="color:#14396B;" for="text-input">
                                    <strong>Mnz.</strong>
                                </label>
                                <div class="col-md-3">
                                    <input disabled type="text" v-model="datos.manzana" class="form-control">
                                </div>
                                <div class="col-md-1"></div>
                                <label class="col-md-1 form-control-label" style="color:#14396B;" for="text-input">
                                    <strong>Lote.</strong>
                                </label>
                                <div class="col-md-2" v-if="datos.num_lote">
                                    <input disabled type="text" v-model="datos.num_lote" class="form-control">
                                </div>
                            </div>
                        </div> 

                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-md-1 form-control-label" style="color:#14396B;" for="text-input">
                                    <strong>Calle</strong>
                                </label>
                                <div class="col-md-3">
                                    <input disabled type="text" v-model="datos.calle" class="form-control">
                                </div>
                                <label class="col-md-1 form-control-label" style="color:#14396B;" for="text-input">
                                    <strong>Num.</strong>
                                </label>
                                <div class="col-md-1">
                                    <input disabled type="text" v-model="datos.numero" class="form-control">
                                </div>
                                <div class="col-md-1">
                                    <input disabled type="text" v-model="datos.interior" class="form-control">
                                </div>
                                <div class="col-md-1"></div>
                                <label class="col-md-1 form-control-label" style="color:#14396B;" for="text-input">
                                    <strong>Ciudad</strong>
                                </label>
                                <div class="col-md-3">
                                    <input disabled type="text" v-model="datos.ciudad_proy" class="form-control">
                                </div>
                            </div>
                        </div> 
                        
                        <hr>

                        <div class="col-md-12">
                            <div class="form-group row">
                                <div class="col-md-3"></div>
                                <label class="col-md-3 form-control-label" style="color:#14396B; font-size: 17px;" for="text-input">
                                    <strong>Valor de escrituración: </strong>
                                </label>
                                <label class="col-md-2 form-control-label" style="font-size: 17px;" v-if="editar == 0">
                                    <strong>${{formatNumber(datos.valor_escrituras)}}</strong>
                                </label>
                                <div class="col-md-2" v-if="editar == 1">
                                    <input type="number" v-model="datos.valor_escrituras" @change="calcularTerreno()" class="form-control">
                                </div>
                                <div class="col-md-2" v-if="tipoAccion == 0">
                                    <button v-if="editar == 0" @click="editar = 1" class="btn btn-warning" title="Editar"><i class="icon-pencil"></i></button>
                                    <button v-if="editar == 1" @click="editar = 0" class="btn btn-success" title="Finalizar edicion"><i class="icon-check"></i></button>
                                </div>
                            </div>
                        </div> 

                        <div class="col-md-12" v-if="datos.emp_constructora != datos.emp_terreno">
                            <div class="form-group row">
                                <label class="col-md-2 form-control-label" style="color:#14396B;" for="text-input">
                                    <strong>Valor del Terreno</strong>
                                </label>
                                <label class="col-md-2 form-control-label">
                                    ${{formatNumber(datos.valor_terreno)}}
                                </label>
                                <label class="col-md-1 form-control-label">
                                    {{formatNumber(datos.porcentaje_terreno)}}%
                                </label>
                                <div class="col-md-2"></div>
                                <label class="col-md-2 form-control-label" style="color:#14396B;" for="text-input">
                                    <strong>Valor de Construcción</strong>
                                </label>
                                <label class="col-md-2 form-control-label">
                                    ${{formatNumber(datos.valor_construccion)}}
                                </label>
                                <label class="col-md-1 form-control-label">
                                    {{formatNumber(datos.porcentaje_construccion)}}%
                                </label>
                            </div>
                        </div> 

                        <hr>

                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-md-2 form-control-label" style="color:#14396B;" for="text-input">
                                    <strong>Fecha de operación</strong>
                                </label>
                                <div class="col-md-3">
                                    <input type="date" disabled v-model="datos.fecha" class="form-control" >
                                </div>
                            </div>
                        </div> 

                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" style="color:#14396B;" for="text-input">
                                    <strong>Datos de la operacion o acto, forma de pago</strong>
                                </label>
                                <div class="col-md-2">
                                    <input type="text" disabled v-model="datos.tipo_credito" class="form-control" >
                                </div>
                                <div class="col-md-2">
                                    <input type="text" disabled v-model="datos.institucion" class="form-control" >
                                </div>

                                <label class="col-md-3 form-control-label" style="color:#14396B;" for="text-input">
                                    <strong>Monto Crédito Neto 1</strong>
                                </label>
                                <label class="col-md-2 form-control-label" v-if="editar == 0">
                                    ${{formatNumber(datos.monto_credito)}}
                                </label>
                                <div class="col-md-2" v-if="editar == 1">
                                    <input type="number" v-model="datos.monto_credito" class="form-control">
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-12" v-if="datos.segundo_credito > 0 || editar == 1">
                            <div class="form-group row">
                                <div class="col-md-7"></div>

                                <label class="col-md-3 form-control-label" style="color:#14396B;" for="text-input">
                                    <strong>Monto Crédito Neto 2</strong>
                                </label>
                                <label class="col-md-2 form-control-label" v-if="editar == 0">
                                    ${{formatNumber(datos.segundo_credito)}}
                                </label>
                                <div class="col-md-2" v-if="editar == 1">
                                    <input type="number" v-model="datos.segundo_credito" class="form-control">
                                </div>
                            </div>
                        </div> 

                        <hr>

                        <div class="col-md-12">
                            <div class="table-responsive" >
                                <table class="table2 table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th v-if="tipoAccion == 1 && (userName == 'antonio.nv' || userName=='shady') && datos.status == 0">
                                                <button type="button" @click="abrirModal('nuevoCobro')" class="btn btn-success btn-sm" title="Nuevo cobro">
                                                    <i class="icon-plus"></i>
                                                </button>
                                            </th>
                                            <th>Fecha</th>
                                            <th>Número de Cheque</th>
                                            <th>Forma de pago</th>
                                            <th>Clave o Folio</th>
                                            <th>Institución de Crédito</th>
                                            <th>No. de Cuenta</th>
                                            <th>Digitos de la tarjeta</th>
                                            <th>Importe</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="pago in datos.depositos" :key="pago.id">
                                            <td v-if="tipoAccion == 1 && (userName == 'antonio.nv' || userName=='shady') && datos.status == 0">
                                                <button type="button" @click="abrirModal('editarCobro',pago)" class="btn btn-warning btn-sm">
                                                    <i class="icon-pencil"></i>
                                                </button>
                                                <button type="button" @click="eliminarCobro(pago.id)" class="btn btn-danger btn-sm">
                                                    <i class="icon-trash"></i>
                                                </button>
                                            </td>
                                            <td class="td2" v-text="pago.fecha_pago"></td>
                                            <td class="td2" v-text="pago.num_cheque"></td>
                                            <td class="td2" v-text="pago.forma_pago"></td>
                                            <td class="td2" v-text="pago.clave"></td>
                                            <td class="td2" v-text="pago.institucion"></td>
                                            <td class="td2" v-text="pago.cuenta"></td>
                                            <td class="td2" v-text="pago.tarjeta"></td>
                                            <td class="td2" v-text="'$'+formatNumber(pago.cant_depo)"></td>
                                        </tr>
                                        <tr>
                                            <th v-if="tipoAccion == 1 && datos.status == 0"></th>
                                            <th colspan="7">Total:</th>
                                            <th> ${{formatNumber(total = pagoTotal)}}</th>
                                        </tr>
                                        <tr>
                                            <th v-if="tipoAccion == 1 && datos.status == 0"></th>
                                            <th colspan="7">Diferencia</th>
                                            <th> ${{formatNumber(diferencia = diferenciaTotal)}}</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <hr>

                        <div class="col-md-12">
                            <div class="form-group">
                                <center><h5 style="color: #153157;">Datos Fiscales</h5></center>
                            </div>
                        </div> 

                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-md-2 form-control-label" for="text-input">
                                    <strong>Nombre:</strong>
                                </label>
                                <div class="col-md-6">
                                    <input :disabled="tipoAccion == 1" type="text" v-model="datos.nombre_fisc" class="form-control" placeholder="Nombre">
                                </div>
                                <label class="col-md-1 form-control-label" for="text-input">
                                    <strong>RFC:</strong>
                                </label>
                                <div class="col-md-3">
                                    <input :disabled="tipoAccion == 1" type="text"  style="text-transform:uppercase" v-model="datos.rfc_fisc" class="form-control" placeholder="RFC">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-md-2 form-control-label" for="text-input">
                                    <strong>Correo eléctronico:</strong>
                                </label>
                                <div class="col-md-4">
                                    <input :disabled="tipoAccion == 1" type="text" v-model="datos.email_fisc" class="form-control" placeholder="Email">
                                </div>
                                <label class="col-md-2 form-control-label" for="text-input">
                                    <strong>Teléfono:</strong>
                                </label>
                                <div class="col-md-3">
                                    <input :disabled="tipoAccion == 1" type="text" v-model="datos.tel_fisc" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-md-2 form-control-label" for="text-input">
                                    <strong>Dirección:</strong>
                                </label>
                                <div class="col-md-7">
                                    <input :disabled="tipoAccion == 1" type="text" v-model="datos.direccion_fisc" class="form-control" placeholder="Dirección">
                                </div>
                            </div>
                        </div> 

                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-md-2 form-control-label" for="text-input">
                                    <strong>Colonia:</strong>
                                </label>
                                <div class="col-md-4">
                                    <input :disabled="tipoAccion == 1" type="text" v-model="datos.col_fisc" class="form-control" placeholder="Colonia">
                                </div>
                                <label class="col-md-1 form-control-label" for="text-input">
                                    <strong>C.P.:</strong>
                                </label>
                                <div class="col-md-2">
                                    <input :disabled="tipoAccion == 1" type="text" v-model="datos.cp_fisc" class="form-control" placeholder="C.P.">
                                </div>
                            </div>
                        </div> 

                        <hr>

                        <div class="col-md-12" >
                            <div class="form-group row">
                                <div class="col-md-9"></div>
                                <div class="col-md-3">
                                    <button v-if="tipoAccion == 0" @click="generarIntegracion()" class="btn btn-success" title="Guardar integración"><i class="icon-check"></i>&nbsp;Guardar</button>
                                    <button v-if="tipoAccion == 1 && datos.status == 0 && (userName == 'antonio.nv' || userName=='shady')" @click="finalizarIntegracion()" class="btn btn-success" title="Guardar integración"><i class="icon-check"></i>&nbsp;Finalizar</button>
                                </div>
                            </div>
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
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>

                        <template v-if="modal == 1">

                            <div class="modal-body">                       
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">
                                        Cliente <span style="color:red;" v-show="b_nombre==''">*</span>
                                    </label>
                                    <div class="col-md-5">
                                        <input type="text" @keyup.enter="buscarContrato(b_nombre)" v-model="b_nombre" class="form-control" placeholder="Nombre del cliente">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" @click="buscarContrato(b_nombre)" class="btn btn-primary" title="Buscar"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                                
                                <div class="form-group row" v-if="arrayContratos.length">
                                    <div class="col-md-12">
                                        
                                            <table class="table table-bordered table-striped table-sm">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>Folio</th>
                                                        <th>Cliente</th>
                                                        <th>Proyecto</th>
                                                        <th>Etapa</th>
                                                        <th>Manzana</th>
                                                        <th>Num Lote</th>
                                                    </tr>
                                                </thead>
                                                <tbody >
                                                    <tr v-for="contrato in arrayContratos" :key="contrato.id">
                                                        <td>
                                                            <button type="submit" @click="verDetalle(contrato,0)" class="btn btn-dark btn-sm" title="Seleccionar"><i class="fa fa-hand-o-right"></i></button>
                                                        </td>
                                                        <td v-text="contrato.id"></td>
                                                        <td class="td2" v-text="contrato.nombre + ' '+contrato.apellidos"></td>
                                                        <td class="td2" v-text="contrato.proyecto"></td>
                                                        <td class="td2" v-text="contrato.etapa"></td>
                                                        <td class="td2" v-text="contrato.manzana"></td>
                                                        <td v-text="contrato.num_lote"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        
                                    </div>
                                </div>
                                
                            </div>
                            
                        </template>

                        <template v-if="modal >= 2">
                            <div class="modal-body">
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Fecha</label>
                                    <div class="col-md-3">
                                        <input type="date" v-model="cobro.fecha_pago" class="form-control" placeholder="Fecha">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Num. Cheque</label>
                                    <div class="col-md-5">
                                        <input type="text" v-on:keypress="isNumber($event)" pattern="\d*" v-model="cobro.num_cheque" class="form-control" placeholder="Número de cheque">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Forma de Pago</label>
                                    <div class="col-md-5">
                                        <input type="text" v-model="cobro.forma_pago" class="form-control" placeholder="Forma de pago">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Clave o Folio</label>
                                    <div class="col-md-5">
                                        <input type="text" v-model="cobro.clave" class="form-control" placeholder="Clave">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Banco</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="cobro.banco" class="form-control" placeholder="Clave">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Digitos de la tarjeta</label>
                                    <div class="col-md-5">
                                        <input type="text" v-on:keypress="isNumber($event)" pattern="\d*" v-model="cobro.tarjeta" class="form-control" placeholder="Digitos de la tarjeta">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Importe</label>
                                    <div class="col-md-4">
                                        <input type="text" pattern="\d*" v-on:keypress="isNumber($event)" v-model="cobro.cant_depo" maxlength="10" class="form-control" placeholder="Importe">
                                    </div>
                                    <div class="col-md-4" v-if="cobro.cant_depo != null">
                                        <h6 v-text="'$'+formatNumber(cobro.cant_depo)"></h6>
                                    </div>
                                </div>
                            </div>
                        </template>
                        
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="modal == 2" @click="updateCobro()" class="btn btn-primary">Guardar cambios</button>
                            <button type="button" v-if="modal == 3" @click="guardarCobro()" class="btn btn-success">Guardar</button>
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
        props:{
            userName:{type: String},
        },
        data(){
            return{
                arrayIntegraciones : [],
                arrayContratos : [],
                arrayBancos : [],
                modal : 0,
                tituloModal : '',
                buscar : '',
                id : '',
                b_nombre : '',
                vista : 0,
                datos:{},
                editar : 0,
                total: 0,
                diferencia : 0,
                tipoAccion : 0,
                tab : 0,
                cobro:{}
            }
        },
        computed:{
            pagoTotal: function(){
                var total = 0.0;
                this.datos.depositos.forEach(element => {
                    total += parseFloat(element.cant_depo)
                });
                return total;
            },
            diferenciaTotal: function(){
                var diferencia = 0.0;
                diferencia = parseFloat(this.datos.valor_escrituras) - 
                    ( parseFloat(this.total) + parseFloat(this.datos.monto_credito) + parseFloat(this.datos.segundo_credito));

                return diferencia;
            }
        },
        methods : {
            /**Metodo para mostrar los registros */
            listarIntegraciones(page){
                let me = this;
                var url = '/integracionCobros/getIntegraciones?page=' + page+'&buscar= +'
                    +this.buscar + '&status='+this.tab;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayIntegraciones = respuesta;
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

            buscarContrato(buscar){
                let me = this;
                var url = '/integracionCobros/getContratos?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayContratos = respuesta;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getCobros(id){
                let me = this;
                var url = '/integracionCobros/getCobros?id=' + id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.datos.depositos = respuesta;
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
            /**Metodo para registrar  */
            generarIntegracion(){
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/integracionCobros/generarIntegración',{
                    'contrato_id' : this.datos.id,
                    'valor_terreno' : this.datos.valor_terreno,
                    'valor_const' : this.datos.valor_construccion,
                    'porcentaje_terreno' : this.datos.porcentaje_terreno,
                    'porcentaje_construccion' : this.datos.porcentaje_construccion,
                    'valor_escrituras' : this.datos.valor_escrituras,
                    'email_fisc' : this.datos.email_fisc,
                    'tel_fisc' : this.datos.tel_fisc,
                    'nombre_fisc' : this.datos.nombre_fisc,
                    'direccion_fisc' : this.datos.direccion_fisc,
                    'col_fisc' : this.datos.col_fisc,
                    'cp_fisc' : this.datos.cp_fisc,
                    'rfc_fisc' : this.datos.rfc_fisc,
                    'pagos' : this.datos.depositos,
                    'monto_credito' : this.datos.monto_credito,
                    'segundo_credito' : this.datos.segundo_credito
                }).then(function (response){
                    me.limpiarDatos();
                    me.listarIntegraciones(1); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Integración de cobros generada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            guardarCobro(){
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/integracionCobros/storeCobro',{
                    'integracion_id' : me.datos.id,
                    'fecha_pago' : me.cobro.fecha_pago,
                    'banco' : me.cobro.banco,
                    'num_cheque' : me.cobro.num_cheque,
                    'forma_pago' : me.cobro.forma_pago,
                    'clave' : me.cobro.clave,
                    'tarjeta' : me.cobro.tarjeta,
                    'cant_depo' : me.cobro.cant_depo
                }).then(function (response){
                    me.cerrarModal();
                    me.getCobros(me.datos.id);
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Cobro agregado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            eliminarCobro(id){
                let me = this;
                //console.log(me.fraccionamiento_id);
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
                 
                axios.delete('/integracionCobros/eliminarCobro', 
                         { params: {'id': id }
                         }).then(function (response){
                        swal(
                        'Borrado!',
                        'Deposito borrado correctamente.',
                        'success'
                        )
                         me.getCobros(me.datos.id);
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            updateCobro(){
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.put('/integracionCobros/updateCobro',{
                    'id' : me.cobro.id,
                    'integracion_id' : me.datos.id,
                    'fecha_pago' : me.cobro.fecha_pago,
                    'banco' : me.cobro.banco,
                    'num_cheque' : me.cobro.num_cheque,
                    'forma_pago' : me.cobro.forma_pago,
                    'clave' : me.cobro.clave,
                    'tarjeta' : me.cobro.tarjeta,
                    'cant_depo' : me.cobro.cant_depo
                }).then(function (response){
                    me.cerrarModal();
                    me.getCobros(me.datos.id);
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Cobro actualizado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            finalizarIntegracion(){
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.put('/integracionCobros/finalizarIntegracion',{
                    'id' : me.datos.id,
                }).then(function (response){
                    me.limpiarDatos();
                    me.listarIntegraciones(1); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Integración de cobros finalizada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            calcularTerreno(){
                if(this.datos.valor_escrituras != 0){
                    this.datos.porcentaje_terreno = (this.datos.valor_terreno * 100)/this.datos.valor_escrituras;
                    this.datos.porcentaje_construccion = 100 - this.datos.porcentaje_terreno;
                    this.datos.valor_construccion = this.datos.valor_escrituras - this.datos.valor_terreno;
                }
                else{
                    this.datos.porcentaje_terreno = 0;
                    this.datos.valor_construccion = 0;
                    this.datos.porcentaje_construccion = 0;
                }
            },
            cerrarModal(){
               this.modal = 0;
            },
            limpiarDatos(){
                this.b_nombre = '';
                this.arrayContratos = [];
                this.datos = {};
                this.vista = 0;
            },
            verDetalle(data,tipo){
                this.datos = data;
                this.cerrarModal();
                this.vista = 1;
                this.editar = 0;
                this.calcularTerreno();
                this.tipoAccion = tipo;
            },
            
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(accion,data =[]){
                switch(accion){
                    case 'registrar':
                    {
                        this.modal = 1;
                        this.tituloModal = 'Buscar contrato';
                        this.b_nombre = '';
                        this.arrayContratos = [];
                        break;
                    }
                    case 'editarCobro':{
                        this.modal = 2;
                        this.tituloModal = 'Actualizar Pago';
                        this.cobro = data;
                        break;
                    }
                    case 'nuevoCobro':{
                        this.modal = 3;
                        this.tituloModal = 'Nuevo Pago';
                        this.cobro = {};
                        break;
                    }
                   
                }
            }
        },
        mounted() {
            this.listarIntegraciones(1);
            this.selectCuenta();
        }
    }
</script>
<style>
    .modal-content{
        width: 100% !important;
        position: absolute !important;
       
    }
    .modal-body{
        height: 450px;
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
