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
                        <i class="fa fa-align-justify"></i> Créditos Puente
                        <button v-if="vista == 1" @click="listarAvisos(1)" class="btn btn-secondary">
                            <i class="fa fa-mail-reply"></i>&nbsp;Regresar
                        </button>
                    </div>
                    <!-- Div Card Body para listar -->
                    <template v-if="vista == 0">
                        <div class="card-body"> 
                            <div class="form-group row">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <select class="form-control" @keyup.enter="listarAvisos(1)" v-model="buscar" >
                                            <option value="">Seleccione</option>
                                            <option v-for="proyecto in arrayProyectos" :key="proyecto.id" :value="proyecto.id" v-text="proyecto.nombre"></option>
                                        </select>
                                        <input type="text" class="form-control" v-model="b_folio" @keyup.enter="listarAvisos(1)" placeholder="Folio">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <select class="form-control" @keyup.enter="listarAvisos(1)" v-model="b_status" >
                                            <option value="3">Aprobado</option>
                                            <option value="2">Rechazado</option>
                                            <option value="4">Liquidado</option>
                                        </select>
                                        <button type="submit" @click="listarAvisos(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table2 table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Folio</th>
                                            <th>Proyecto</th>
                                            <th>Número de Cuenta</th>
                                            <th>Tasa de interes</th>
                                            <th>Apertura</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th></th>
                                            <!-- <th>Fecha solicitud</th>
                                            <th>Status</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="creditosPuente in arrayCreditosPuente" :key="creditosPuente.id" @dblclick="verEdoCuenta(creditosPuente)">
                                            <td>
                                                <a href="#" v-text="creditosPuente.folio"></a>
                                            </td>
                                            <td class="td2" v-text="creditosPuente.proyecto"></td>
                                            <td class="td2" v-if="creditosPuente.num_cuenta == null">
                                                <button type="button" @click="numCuenta(creditosPuente.id)" class="btn btn-primary btn-sm" title="Num Cuenta">
                                                    <i class="fa fa-credit-card-alt">&nbsp;No. De cuenta</i>
                                                </button>
                                            </td>
                                            <td class="td2" v-else v-text="creditosPuente.num_cuenta"></td>
                                            <td class="td2"> TIEE+{{formatNumber(creditosPuente.interes)}}</td>
                                            <td class="td2"> {{formatNumber(creditosPuente.apertura)}}%</td>
                                            <td class="td2"> ${{formatNumber(creditosPuente.total)}}</td>
                                            <td class="td2" v-if="creditosPuente.status == 0">
                                                <span class="badge badge-info">Pendiente</span>
                                            </td>
                                            <td class="td2" v-if="creditosPuente.status == 1">
                                                <span class="badge badge-warning">Expediente integrado:</span> {{creditosPuente.fecha_integracion}}
                                            </td>
                                            <td class="td2">
                                                <button type="button" @click="abrirModal('obs',creditosPuente.id)" class="btn btn-dark btn-sm" title="Observaciones">
                                                    <i class="fa fa-book">&nbsp;Observaciones</i>
                                                </button>
                                            </td>
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
                    </template>

                    <!-- Div para ver estado de cuenta -->
                    <template v-if="vista == 1">
                        <div class="card-body"> 
                            <div class="row">
                                <div class="col-xl-12 col-lg-5 col-md-4">
                                    <div class="card">
                                        <!-- Cabecera con datos generales del crédito -->
                                        <div>
                                             <div class="card-body p-12 d-flex align-items-center">
                                                <div>
                                                    <div class="text-value text-uppercase font-weight-bold text-primary text-center">
                                                        Estado de cuenta del Crédito Puente "{{datosPuente.proyecto}} {{datosPuente.lotes}} VIV"
                                                    </div>
                                                </div>
                                                <br>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-left:0px;">
                                            <div class="card-body p-3 d-flex align-items-center">
                                                <div>
                                                    <div class="text-uppercase font-weight-bold" 
                                                        v-text="'Institucion de crédito: '">
                                                    </div>
                                                    <div class="text-uppercase font-weight-bold" 
                                                        v-text="'Número de casas: '">
                                                    </div>
                                                    <div class="text-uppercase font-weight-bold" 
                                                        v-text="'Ingresos a: '">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body p-3 d-flex align-items-center">
                                                <div>
                                                    <div class="text-uppercase" 
                                                        v-text="datosPuente.banco">
                                                    </div>
                                                    <div class="text-uppercase" 
                                                        v-text="datosPuente.lotes">
                                                    </div>
                                                    <div class="text-uppercase" 
                                                        v-text="'CTA DE '+ datosPuente.banco+' '+datosPuente.num_cuenta">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body p-3 d-flex align-items-center">
                                                <div>
                                                    <div class="text-muted text-uppercase font-weight-bold" 
                                                        v-text="'Comisión S/Apert.: '">
                                                    </div>
                                                    <div class="text-muted text-uppercase font-weight-bold" 
                                                        v-text="'TASA: '">
                                                    </div>
                                                    <div class="text-muted text-uppercase font-weight-bold" 
                                                        v-text="'Fecha de pago de intereses: '">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body p-3 d-flex align-items-center">
                                                <div>
                                                    <div class="text-muted text-uppercase" 
                                                        v-text=" formatNumber(datosPuente.apertura)+'%'">
                                                    </div>
                                                    <div class="text-muted text-uppercase" 
                                                        v-text="'TIIE PROMEDIO 28 DÍAS + ' + formatNumber(datosPuente.interes)">
                                                    </div>
                                                    
                                                    <div class="text-muted text-uppercase">
                                                        {{fecha_sig_int}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row" style="margin-left:0px;">
                                            <div class="card-body p-4 d-flex align-items-center">
                                                <button v-if="vistaLotes==0" @click="getLotesPuente(datosPuente.id),vistaLotes = 1" class="btn btn-success rounded">
                                                    <i class="icon-home"></i>&nbsp;Abono a Lote
                                                </button>
                                                <button v-else-if="vistaLotes==1" @click="getEdoCuenta(datosPuente.id)" class="btn btn-default rounded">
                                                    <i class="fa fa-reply"></i>&nbsp;Ver Edo. Cuenta
                                                </button>
                                            </div>
                                            <div class="card-body p-4 d-flex align-items-center">
                                                <div>
                                                    <div class="text-uppercase font-weight-bold text-primary" 
                                                        v-text="'Monto total del crédito:   $'+formatNumber(datosPuente.credito_otorgado)">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Botones para pagar intereses o registrar abono/cargo -->
                                            <div class="card-body p-4 d-flex align-items-center">
                                                <button v-if="vista == 1 && datosPuente.diasInt < 0 || arrayPagos.length == 0" @click="abrirModal('movimiento',datosPuente.id)" class="btn btn-success rounded">
                                                    <i class="icon-plus"></i>&nbsp;Nuevo movimiento
                                                </button>
                                                <button v-if="vista == 1 && datosPuente.diasInt >= 0" @click="abrirModal('intereses',datosPuente.id)" class="btn btn-success rounded">
                                                    <i class="fa fa-calendar-plus-o"></i>&nbsp;Pago de intereses
                                                </button>
                                                &nbsp;&nbsp;
                                                <button v-if="vista == 1 && datosPuente.diasInt < 0 && arrayPagosPendientes.length" @click="abrirModal('pendientes',datosPuente.id)" class="btn btn-warning rounded">
                                                    <i class=" fa fa-bank"></i>&nbsp;Hipotecas pendientes
                                                </button>
                                            </div>
                                            <!-- Fin Botones para pagar intereses o registrar abono/cargo -->
                                        </div>
                                        <!-- Fin Cabecera con datos generales del crédito -->

                                        <!-- Aqui se inicia el listado de movimientos Ingresos y abonos al credito -->
                                        <div>
                                            <div class="card-footer px-3 py-2 text-right">
                                                <table v-if="vistaLotes == 0" class="table table-bordered table-striped table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Fecha</th>
                                                            <th>Concepto</th>
                                                            <th>Cargo</th>
                                                            <th>Abono</th>
                                                            <th>Saldo</th>
                                                            <th>Interes</th>
                                                            <!-- <th>Sin IVA Comisiones</th>
                                                            <th>Sin IVA Honorarios</th> -->
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!--Comisiones Bancarias--->
                                                            <tr v-for="pago in arrayPagos" :key="pago.id">
                                                                <td class="td2" v-text="pago.fecha"></td>
                                                                <td class="td2" v-text="pago.concepto"></td>
                                                                <td class="td2" v-text="'$'+formatNumber(pago.cargo)"></td>
                                                                <td class="td2" v-text="'$'+formatNumber(pago.abono)"></td>
                                                                <td class="td2" v-text="'$'+formatNumber(pago.saldo)"></td>
                                                                <td class="td2" v-text="'$'+formatNumber(pago.monto_interes)"></td>
                                                                <!-- <td class="td2" v-text="'$'+formatNumber(pago.comisiones)"></td>
                                                                <td class="td2" v-text="'$'+formatNumber(pago.honorarios)"></td> -->
                                                            </tr>
                                                    </tbody>
                                                </table>

                                                <table v-if="vistaLotes == 1" class="table table-bordered table-striped table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th>Lote</th>
                                                            <th>Etapa</th>
                                                            <th>Manzana</th>
                                                            <th>Modelo</th>
                                                            <th>Saldo</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!--Comisiones Bancarias--->
                                                            <tr v-for="lote in arrayLotes" :key="lote.id">
                                                                <td class="td2" style="width:5%">
                                                                    <button v-if="lote.liberado == 0 && datosPuente.diasInt < 0" title="Seleccionar" type="button" @click="selectLote(lote)" class="btn btn-warning btn-sm">
                                                                        <i class="icon-pencil"></i>
                                                                    </button>
                                                                </td>
                                                                <td class="td2" v-text="lote.num_lote"></td>
                                                                <td class="td2" v-text="lote.num_etapa"></td>
                                                                <td class="td2" v-text="lote.manzana"></td>
                                                                <td class="td2" v-text="lote.modelo"></td>
                                                                <td class="td2" v-text="'$'+formatNumber(lote.saldo)"></td>
                                                               
                                                            </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- Fin Listado de movimientos -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>

            <!--Inicio del modal obs-->
                <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal == 1}" 
                    role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-primary modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" v-text="tituloModal"></h4>
                                <button type="button" class="close" @click="cerrarModal(0)" aria-label="Close">
                                <span aria-hidden="true">×</span>
                                </button>
                            </div>

                                <div class="modal-body">
                                    <template v-if="tipoAccion == 1">
                                        
                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Nueva observación</label>
                                                <div class="col-md-6">
                                                    <input type="text" v-model="observacion" class="form-control">
                                                </div>

                                                <div class="col-md-3">
                                                    <button v-if="observacion != ''" class="btn btn-primary" @click="storeObs()">Guardar</button>
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
                                                        <tr v-for="observacion in arrayObs" :key="observacion.id">
                                                            <td v-text="observacion.usuario" ></td>
                                                            <td v-text="observacion.observacion" ></td>
                                                            <td v-text="observacion.created_at"></td>
                                                        </tr>                               
                                                    </tbody>
                                                </table>
                                            </form>
                                    </template>

                                </div>
                                <!-- Botones del modal -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" @click="cerrarModal(0)">Cerrar</button>
                                </div>
                            
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
            <!--Fin del modal-->

            <!--Inicio del modal nuevo movimiento-->
                <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal == 2}" 
                    role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-primary modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" v-text="tituloModal"></h4>
                                <button type="button" class="close" @click="cerrarModal(1)" aria-label="Close">
                                <span aria-hidden="true">×</span>
                                </button>
                            </div>
                                <div class="modal-body">
                                    <!-- Modal para registrar ingreso/abono -->
                                    <template v-if="tipoAccion == 1">
                                        
                                        <div class="form-group row">
                                            <label class="col-md-2 form-control-label" for="text-input">Fecha</label>
                                            <div class="col-md-4">
                                                <input type="date" v-model="fecha" @change="getTiie(fecha)" class="form-control">
                                            </div>
                                            <label v-if="tiie != 0" class="col-md-2 form-control-label" for="text-input">TIIE a 28 dias:</label>
                                            <div v-if="tiie != 0" class="col-md-4">
                                                <label disabled type="text" v-text="tiie+' + '+datosPuente.interes" class="form-control"></label>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-2 form-control-label" for="text-input">Concepto</label>
                                            <div class="col-md-6">
                                                <input type="text" name="concepto" list="conceptName" class="form-control" v-model="concepto">
                                                <datalist id=conceptName @click="getDatos(recomendado)">
                                                    <option value="">Seleccione</option>
                                                    <option value="Prepuente">Prepuente</option>
                                                    <option value="Liquidación Prepuente">Liquidación Prepuente</option>
                                                    <option value="Renovación Prepuente">Renovación Prepuente</option>
                                                    <option value="Anticipo por Firma de Crédito">Anticipo por Firma de Crédito</option>
                                                    <option value="Ministración">Ministración</option>
                                                </datalist>
                                            </div>

                                            <div class="col-md-4">
                                                <select v-model="tipo" class="form-control" @change="iva_comision = 0, iva_honorario = 0, interes = 0" v-on:change="calcularInteres()">
                                                    <option value="">Tipo de movimiento</option>
                                                    <option value="0">Cargo</option>
                                                    <option value="1">Abono</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row" v-if="tipo != ''">
                                            <label class="col-md-2 form-control-label" for="text-input">Monto</label>
                                            <div class="col-md-4">
                                                <input v-if="tipo == 1" @change="calcularInteres()" type="number" v-model="cantidad" class="form-control">
                                                <input v-else type="number" v-model="cantidad" class="form-control">
                                            </div>
                                            <label class="col-md-3 form-control-label" for="text-input">${{formatNumber(cantidad)}}</label>
                                        </div>

                                        <div class="form-group row" v-if="tipo == 1">
                                            <label class="col-md-2 form-control-label" for="text-input">Interes</label>
                                            <div class="col-md-4">
                                                <input type="number" v-model="interes" class="form-control">
                                            </div>
                                            <label class="col-md-3 form-control-label" for="text-input">${{formatNumber(interes)}}</label>
                                        </div>

                                        <div class="form-group row" v-if="tipo == 1">
                                            <h6 class="col-md-2 form-control-label" for="text-input">Pago total</h6>
                                            <div class="col-md-4">
                                                <h6 class="form-control">${{formatNumber(total=totalPagar)}}</h6>
                                            </div>
                                        </div>

                                    </template>
                                    <!-- Fin Modal para registrar ingreso/abono -->
                                    <!-- Modal para pagar intereses -->
                                    <template v-if="tipoAccion == 2">
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <table class="table table-bordered table-striped table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Concepto</th>
                                                            <th>Saldo</th>
                                                            <th>Tasa</th>
                                                            <th>Interes generado</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="cargo in arrayCargos" :key="cargo.id">
                                                            <td class="td2" v-text="cargo.concepto"></td>
                                                            <td class="td2" v-text="'$'+formatNumber(cargo.saldo)"></td>
                                                            <td class="td2" v-text="formatNumber(cargo.tasa)+'%'"></td>
                                                            <td class="td2" v-text="'$'+formatNumber(cargo.interes)"></td>
                                                        </tr> 
                                                        <tr>
                                                            <td class="td2" colspan="4"></td>
                                                        </tr>                    
                                                        <tr>
                                                            <td class="td2" colspan="3">
                                                                <strong>Total</strong>
                                                            </td>
                                                            <td class="td2" v-text="'$'+formatNumber(cantidad)"></td>
                                                        </tr>  
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row">
                                            <label class="col-md-2 form-control-label" for="text-input">Fecha</label>
                                            <div class="col-md-4">
                                                <input type="date" disabled v-model="fecha" @change="getTiie(fecha)" class="form-control">
                                            </div>
                                            <label v-if="tiie != 0" class="col-md-2 form-control-label" for="text-input">TIIE a 28 dias:</label>
                                            <div v-if="tiie != 0" class="col-md-4">
                                                <label disabled type="text" v-text="tiie+' + '+datosPuente.interes" class="form-control"></label>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-2 form-control-label" for="text-input">Concepto</label>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" v-model="concepto">
                                            </div>

                                            <div class="col-md-4">
                                                <select disabled v-model="tipo" class="form-control" @change="iva_comision = 0, iva_honorario = 0, interes = 0" v-on:change="calcularInteres()">
                                                    <option value="">Tipo de movimiento</option>
                                                    <option value="0">Cargo</option>
                                                    <option value="1">Abono</option>
                                                    <option value="3">Pago de intereses</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row" v-if="tipo != ''">
                                            <h6 class="col-md-2 form-control-label" for="text-input">Total a pagar</h6>
                                            <h6 class="col-md-3 form-control-label" for="text-input">${{formatNumber(cantidad)}}</h6>
                                        </div>

                                    </template>
                                    <!--Fin Modal para pagar intereses -->
                                    <!-- Modal para registrar pago pendiente de lote -->
                                    <template v-if="tipoAccion == 3">
                                        <template v-if="edit == 0">
                                            <div class="form-group row">
                                                <div class="col-md-12">
                                                    <table class="table table-bordered table-striped table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th>Concepto</th>
                                                                <th>Fecha</th>
                                                                <th>Monto</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="anticipo in arrayPagosPendientes" :key="anticipo.id">
                                                                <td class="td2">
                                                                    <button title="Seleccionar" type="button" @click="addDatos(anticipo), edit = 1" class="btn btn-warning btn-sm">
                                                                        <i class="icon-pencil"></i>
                                                                    </button>
                                                                </td>
                                                                <td class="td2" v-text="anticipo.concepto"></td>
                                                                <td class="td2" v-text="anticipo.fecha"></td>
                                                                <td class="td2" v-text="'$'+formatNumber(anticipo.abono)"></td>
                                                            </tr>                     
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </template>

                                        <template v-if="edit == 1">
                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Fecha</label>
                                                <div class="col-md-4">
                                                    <input type="date" v-model="fecha" @change="getTiie(fecha)" class="form-control">
                                                </div>
                                                <label v-if="tiie != 0" class="col-md-2 form-control-label" for="text-input">TIIE a 28 dias:</label>
                                                <div v-if="tiie != 0" class="col-md-4">
                                                    <label disabled type="text" v-text="tiie+' + '+datosPuente.interes" class="form-control"></label>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Concepto</label>
                                                <div class="col-md-7">
                                                    <input type="text" disabled class="form-control" v-model="concepto">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Monto</label>
                                                <div class="col-md-4">
                                                    <input type="number" v-model="cantidad" class="form-control">
                                                </div>
                                                <label class="col-md-3 form-control-label" for="text-input">${{formatNumber(cantidad)}}</label>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Interes</label>
                                                <div class="col-md-4">
                                                    <input type="number" v-model="interes" class="form-control">
                                                </div>
                                                <label class="col-md-3 form-control-label" for="text-input">${{formatNumber(interes)}}</label>
                                            </div>

                                            <div class="form-group row">
                                                <h6 class="col-md-2 form-control-label" for="text-input">Pago total</h6>
                                                <div class="col-md-4">
                                                    <h6 class="form-control">${{formatNumber(total=totalPagar)}}</h6>
                                                </div>
                                            </div>
                                        </template>
                                    </template>
                                    <!-- Fin Modal para registrar ingreso/abono -->
                                    <!-- Modal para registrar abono a Lote-->
                                    <template v-if="tipoAccion == 4">
                                        
                                        <div class="form-group row">
                                            <label class="col-md-2 form-control-label" for="text-input">Fecha</label>
                                            <div class="col-md-4">
                                                <input type="date" v-model="fecha" @change="getTiie(fecha)" class="form-control">
                                            </div>
                                            <label v-if="tiie != 0" class="col-md-2 form-control-label" for="text-input">TIIE a 28 dias:</label>
                                            <div v-if="tiie != 0" class="col-md-4">
                                                <label disabled type="text" v-text="tiie+' + '+datosPuente.interes" class="form-control"></label>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-2 form-control-label" for="text-input">Concepto</label>
                                            <div class="col-md-6">
                                                <input type="text" disabled class="form-control" v-model="concepto">
                                            </div>

                                            <div class="col-md-4">
                                                <select v-model="tipo" disabled class="form-control" @change="iva_comision = 0, iva_honorario = 0, interes = 0" v-on:change="calcularInteres()">
                                                    <option value="">Tipo de movimiento</option>
                                                    <option value="0">Cargo</option>
                                                    <option value="1">Abono</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-2 form-control-label" for="text-input"></label>
                                            <h6 class="col-md-2 form-control-label" for="text-input">Saldo: </h6>
                                            <h6 class="col-md-3 form-control-label" for="text-input">${{formatNumber(saldo)}}</h6>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-2 form-control-label" for="text-input">Monto</label>
                                            <div class="col-md-4">
                                                <input type="number" @change="verificarSaldo(),calcularInteres()" v-model="cantidad" class="form-control">
                                            </div>
                                            <label class="col-md-3 form-control-label" for="text-input">${{formatNumber(cantidad)}}</label>
                                        </div>

                                        <div class="form-group row" v-if="tipo == 1">
                                            <label class="col-md-2 form-control-label" for="text-input">Interes</label>
                                            <div class="col-md-4">
                                                <input type="number" v-model="interes" class="form-control">
                                            </div>
                                            <label class="col-md-3 form-control-label" for="text-input">${{formatNumber(interes)}}</label>
                                        </div>

                                        <div class="form-group row" v-if="tipo == 1">
                                            <h6 class="col-md-2 form-control-label" for="text-input">Pago total</h6>
                                            <div class="col-md-4">
                                                <h6 class="form-control">${{formatNumber(total=totalPagar)}}</h6>
                                            </div>
                                        </div>

                                    </template>
                                    <!-- Fin Modal para registrar ingreso/abono -->
                                </div>
                                <!-- Botones del modal registarar abono o cargo -->
                                <div class="modal-footer" v-if="tipoAccion == 1">
                                    <button type="button" class="btn btn-secondary" @click="cerrarModal(1)">Cerrar</button>
                                    <button type="button" v-if="tipo == 0 && cantidad > 0" class="btn btn-success" @click="registrarCargo()">Guardar</button>
                                    <button type="button" v-if="tipo == 1 && cantidad > 0" class="btn btn-success" @click="registrarAbono(0)">Guardar</button>
                                </div>
                                <!-- Botones del modal pago de intereses -->
                                <div class="modal-footer" v-if="tipoAccion == 2">
                                    <button type="button" class="btn btn-secondary" @click="cerrarModal(1)">Cerrar</button>
                                    <button type="button" class="btn btn-success" @click="registrarInteres()">Guardar</button>
                                </div>
                                <!-- Botones del modal pago de abono pendiente -->
                                <div class="modal-footer" v-if="tipoAccion == 3">
                                    <button type="button" class="btn btn-secondary" @click="cerrarModal(1)">Cerrar</button>
                                    <button type="button" v-if="cantidad > 0" class="btn btn-success" @click="registrarPago()">Registrar pago</button>
                                </div>
                                <!-- Botones del modal pago de abono a lote -->
                                <div class="modal-footer" v-if="tipoAccion == 4">
                                    <button type="button" class="btn btn-secondary" @click="cerrarModal(1)">Cerrar</button>
                                    <button type="button" v-if="cantidad > 0 && fecha != ''" class="btn btn-success" @click="registrarAbono(1)">Registrar pago</button>
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
            rolId:{type: String}
        },
        data(){
            return{
                proceso:false,
                id:0,
                vistaLotes:0,
                pagoId:0,
                arrayCreditosPuente : [],
                vista:0,
                edit : 0,
                tipoAccion: 0,
                errorcreditosPuente : 0,
                errorMostrarMsjcreditosPuente : [],
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                cabecera : {
                    'banco' : '',
                    'interes' : 0,
                    'fecha_solic' : '',
                    'status' : 0,
                    'total' : 0,
                    'cobrado' : 0,
                    'folio' : '',
                    'apertura' : 0,
                    'fraccionamiento' : '',
                },
                offset : 3,
                buscar : '',
                b_folio:'',
                b_status:3,
                arrayProyectos : [],
                arrayCargos : [],
                datosPuente:{
                    'banco':'',
                    'apertura':'',
                    'anticipo':'',
                    'pre_puente':'',
                    'interes':'',
                    'viv_canceladas':'',
                    'viv_por_cancelar':'',
                    'lotes':0,
                    'num_cuenta':'',
                    'credito_otorgado':0,
                },
                movimientos:[],
                modal:0,
                tituloModal:'',
                tipoAccion:1,
                arrayObs:[],
                arrayPagos:[],
                arrayPagosPendientes : [],
                arrayLotes : [],
                observacion:'',
                fecha:'',
                cantidad:0,
                concepto:'',
                iva_comision:0,
                iva_honorario:0,
                fecha_interes:'',
                tiie:0,
                porcInteres:0,
                interes:0,
                saldo:0,
                saldoLote:0,
                tipo:'',
                total:0,
                fecha_sig_int:'',
                lote_id:'',
                lotePuenteId:''
            }
        },
        components:{
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            totalPagar: function(){
                let me = this;
                var total_pago =0;
                    total_pago =parseFloat(me.interes) + parseFloat(me.cantidad); 
                return total_pago;
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
            listarAvisos(page){
                let me = this;
                me.vista = 0;
                var url = '/cPuentes/indexCreditos?page=' + page + '&fraccionamiento=' + me.buscar + '&folio=' + me.b_folio + '&status=' + me.b_status
                                + '&banco=Bancrea';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayCreditosPuente = respuesta.creditos.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            verificarSaldo(){
                let me = this;
                if(me.cantidad > me.saldo)
                    me.cantidad = me.saldo;
            },
            selectLote(lote){
                let me = this;
                me.lote_id = lote.lote_id;
                me.lotePuenteId = lote.id;
                me.saldo = lote.saldo;
                me.concepto = 'Pago Hipoteca M.' + lote.manzana + ' L.' + lote.num_lote;
                
                me.abrirModal('pagoLote',me.id);
                

            },
            getLotesPuente(id){
                let me = this;
                me.id = id;
                me.arrayLotes=[];
                var url = '/cPuentes/getLotesPuente?id=' + id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLotes = respuesta.lotes;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            getObs(id){
                let me = this;
                me.arrayObs=[];
                var url = '/cPuentes/getObs?id=' + id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayObs = respuesta.obs;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            calcularInteres(){
                let me = this;
                me.interes = 0;
                me.total = 0;
                var url = '/cPuentes/interesCargos?id='+me.datosPuente.id+'&fecha='+me.fecha+'&pago='+me.cantidad;
                if(me.tipo == 1)
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.interes = respuesta.interes;
                    me.total = respuesta.interes;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            numCuenta(id){
                 (async function getFruit () {
                    const {value: comentario} = await Swal({
                    title: 'Numero de cuenta',
                    input: 'text',
                    inputPlaceholder: 'Numero de cuenta...',
                    showCancelButton: true,
                    inputValidator: (value) => {
                        return new Promise((resolve) => {
                        if (value != '') {
                            resolve()
                        } else {
                            resolve('Es necesario escribir el numero de cuenta :)')
                        }
                        })
                    }
                    })
                    if (comentario) {
                        axios.put('/cPuentes/numCuenta',{
                            'id' : id,
                            'numCuenta': comentario
                            
                        }).then(function (response){
                            me.ocultarDetalle();
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
                    }
                })()
            },
            getEdoCuenta(id){
                let me = this;
                me.arrayObs=[];
                me.vistaLotes = 0;
                var url = '/cPuentes/getEdoCuenta?id=' + id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPagos = respuesta.pagos;
                    me.arrayPagosPendientes = respuesta.depCreditos;
                    me.fecha_interes = respuesta.ultimoAbono;
                    me.saldo = respuesta.saldo;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectProyectos(){
                let me = this;
                me.arrayProyectos=[];
                var url = '/select_fraccionamiento';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayProyectos = respuesta.fraccionamientos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getTiie(fecha){
                let me = this;
                me.tiie = 0;
                var url = '/cPuentes/tiie?fecha='+fecha+'&fecha_int='+this.fecha_interes;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.tiie = respuesta[0][0].dato;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getInteresePeriodo(id,fecha){
                let me = this;
                var url = '/cPuentes/getInteresePeriodo?fecha_sig_int='+fecha+'&id='+id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayCargos = respuesta.cargos;
                    me.cantidad = respuesta.interes;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            registrarInteres(){
                let me = this;
                axios.post('/cPuentes/storeIntereses',{
                    'id': me.id,
                    'fecha': me.fecha,
                    'concepto' : me.concepto,
                    'cantidad' : me.cantidad,
                    'tipo' : me.tipo,
                    'interes' : me.datosPuente.interes,
                    'tiie' : me.tiie,
                    'datos' : me.arrayCargos,
                   
                }).then(function (response){
                    //Obtener detalle
                        swal({
                            position: 'top-end',
                            type: 'success',
                            title: 'Cargo registrado correctamente',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        me.cerrarModal(0);
                        me.listarAvisos(1);
                        me.getEdoCuenta(me.datosPuente.id);
                    
                }).catch(function (error){
                    console.log(error);
                });
            },
            cambiarPagina(page){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarAvisos(page);
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
            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
            verEdoCuenta(data){
                this.datosPuente = data;
                this.fecha_sig_int = moment(this.datosPuente.fecha_sig_int).locale('es').format('DD/MMM/YYYY');
                this.vista = 1;
                this.getEdoCuenta(this.datosPuente.id);
            },
            storeObs(){
                let me = this;
                axios.post('/cPuentes/storeObs',{
                    'id': this.id,
                    'observacion': this.observacion,
                   
                }).then(function (response){
                    //Obtener detalle
                        swal({
                            position: 'top-end',
                            type: 'success',
                            title: 'Comentario guardado correctamente',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        me.getObs(me.id);
                        me.observacion = '';
                    
                }).catch(function (error){
                    console.log(error);
                });
            },
            abrirModal(opcion,id){
                switch(opcion){
                    case 'obs':{
                        this.modal = 1;
                        this.tituloModal = 'Comentarios para el Crédito Puente';
                        this.arrayObs = [];
                        this.id = id;
                        this.tipoAccion = 1;
                        this.getObs(id);
                        break;
                    }
                    case 'movimiento':{
                        this.modal = 2;
                        this.tituloModal = 'Nuevo movimiento';
                        this.tipoAccion = 1;
                        this.id = id;
                        this.concepto = '';
                        this.cantidad = 0;
                        this.fecha = '';
                        this.iva_comision = 0;
                        this.iva_honorario = 0;
                        this.tiie = 0;
                        this.tipo = '';
                        this.total = 0;
                        break;
                    }
                    case 'intereses':{
                        this.modal = 2;
                        this.tituloModal = 'Pago de intereses';
                        this.tipoAccion = 2;
                        this.id = id;
                        this.concepto = 'Intereses Ordinarios';
                        this.cantidad = 0;
                        this.fecha = this.datosPuente.fecha_sig_int;
                        this.tiie = 0;
                        this.tipo = 3;
                        this.getInteresePeriodo(this.id, this.datosPuente.fecha_sig_int);
                        this.getTiie(this.datosPuente.fecha_sig_int);
                        break;
                    }
                    case 'pendientes':{
                        this.modal = 2;
                        this.tipoAccion = 3;
                        this.tituloModal = 'Hipotecas pendientes por cobrar';
                        this.id = id;
                        this.edit = 0;
                        break;
                    }
                    case 'pagoLote':{
                        this.modal = 2;
                        this.tipoAccion = 4;
                        this.tituloModal = 'Registro de abono a lote';
                        this.tipo = 1;
                        this.cantidad = 0;
                        this.fecha = '';
                        this.interes = 0;
                        break;
                    }
                }
            },
            registrarCargo(){
                let me = this;
                axios.post('/cPuentes/storeCargo',{
                    'id': me.id,
                    'fecha': me.fecha,
                    'concepto' : me.concepto,
                    'monto' : me.cantidad,
                    'tipo' : me.tipo,
                    'interes' : me.datosPuente.interes,
                    'tiie' : me.tiie,
                   
                }).then(function (response){
                    //Obtener detalle
                        swal({
                            position: 'top-end',
                            type: 'success',
                            title: 'Cargo registrado correctamente',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        me.cerrarModal(1);
                        me.getEdoCuenta(me.datosPuente.id);
                    
                }).catch(function (error){
                    console.log(error);
                });
            },
            registrarAbono(pagoLote){
                let me = this;
                axios.post('/cPuentes/storeAbono',{
                    'id': me.id,
                    'fecha': me.fecha,
                    'concepto' : me.concepto,
                    'monto' : me.cantidad,
                    'tipo' : me.tipo,
                    'interes' : me.interes,
                    'pagoLote' : pagoLote,
                    'lote_id' : me.lote_id,
                    'lotePuenteId' : me.lotePuenteId
                   
                }).then(function (response){
                    //Obtener detalle
                        swal({
                            position: 'top-end',
                            type: 'success',
                            title: 'Abono registrado correctamente',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        me.cerrarModal(1);
                        me.getEdoCuenta(me.datosPuente.id);
                    
                }).catch(function (error){
                    console.log(error);
                });
            },
            cerrarModal(vista){
                this.modal = 0;
                this.tituloModal = '';
                this.observacion = '';
                if(vista == 0)
                    this.listarAvisos(vista);
            },
            addDatos(datos){
                this.pagoId = datos.id;
                this.fecha = datos.fecha;
                this.getTiie(this.fecha);
                this.tipo = datos.tipo;
                this.cantidad = datos.abono;
                this.concepto = datos.concepto;
                this.id = datos.credito_puente_id;
                this.lote_id = datos.lote_id;
                this.lotePuenteId = datos.lotePuenteId;
                this.calcularInteres();
            },
            registrarPago(){
                let me = this;
                axios.put('/cPuentes/storePago',{
                    'id': me.id,
                    'fecha': me.fecha,
                    'monto' : me.cantidad,
                    'interes' : me.interes,
                    'lotePuenteId' : me.lotePuenteId,
                    'pagoId' : me.pagoId
                   
                }).then(function (response){
                    //Obtener detalle
                        swal({
                            position: 'top-end',
                            type: 'success',
                            title: 'Abono registrado correctamente',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        me.cerrarModal(1);
                        me.getEdoCuenta(me.datosPuente.id);
                    
                }).catch(function (error){
                    console.log(error);
                });
            }
        },
        mounted() {
            this.listarAvisos(1);
            this.selectProyectos();
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
    }
</style>