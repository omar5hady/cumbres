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
                        <a :href="'/pagares/excel?buscar=' + buscar + '&buscar2=' + buscar2 + '&buscar3=' + buscar3 + '&b_vencidos=' + 
                                b_vencidos + '&criterio=' + criterio+'&b_empresa='+b_empresa"
                            class="btn btn-success"><i class="fa fa-file-text"></i> Excel Pagares
                        </a>
                         <button type="button" class="btn btn-default" @click="abrirModal('depositos')">
                            Excel Depositos
                        </button>
                    </div>
                    <div class="card-header" v-if="deposito==0">
                        <button type="button" class="btn btn-dark" @click="listarHistorialDep(1)">
                            Historial de Depositos
                        </button>
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
                                   
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="date" v-if="criterio=='pagos_contratos.fecha_pago'" v-model="buscar" @keyup.enter="listarPagares(1,buscar, buscar2, buscar3, b_vencidos,criterio)" class="form-control" >
                                    <input type="date" v-if="criterio=='pagos_contratos.fecha_pago'" v-model="buscar2" @keyup.enter="listarPagares(1,buscar, buscar2, buscar3, b_vencidos,criterio)" class="form-control" >
                                    <input type="text" v-if="criterio=='contratos.id'|| criterio=='personal.nombre'" v-model="buscar" @keyup.enter="listarPagares(1,buscar, buscar2, buscar3, b_vencidos,criterio)" class="form-control" placeholder="Texto a buscar">
                                    
                                    <button v-if="b_vencidos==0" type="submit" @click="b_vencidos=1" class="btn btn-success"><i class="fa fa-check-square"></i> Todos</button>
                                    <button v-if="b_vencidos==1" type="submit" @click="b_vencidos=2" class="btn btn-danger"><i class="fa fa-window-close-o"></i> Vencidos</button>
                                    <button v-if="b_vencidos==2" type="submit" @click="b_vencidos=0" class="btn btn-warning"><i class="fa fa-window-close-o"></i> Pagares cancelados</button>
                                    <button type="submit" @click="listarPagares(1,buscar, buscar2, buscar3, b_vencidos,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table2 table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th># Ref</th>
                                        <th>Cliente</th>
                                        <th>Gestor</th>
                                        <th>Proyecto</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th># Lote</th>
                                        <th># Pagare</th>
                                        <th>Saldo</th>
                                        <th>Total depositado</th>
                                        <th>Fecha limite de Pago</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="pagare in arrayPagares" :key="pagare.id">
                                        <td class="td2" style="width:8%">
                                            <button type="button" @click="verDepositos(pagare)" class="btn btn-default btn-sm" title="Ver depositos">
                                            <i class="icon-eye"></i>
                                            </button> &nbsp;
                                        </td>
                                        <td class="td2" v-on:dblclick="abrirPDF(pagare.folio)" title="Solicitud de compra venta">
                                            <a href="#" v-text="pagare.folio" ></a>
                                        </td>
                                        <td class="td2" v-on:dblclick="abrirModal('datosCliente',pagare)" title="Datos del cliente">
                                            <a href="#" v-text="pagare.nombre + ' ' +pagare.apellidos"></a>
                                        </td>
                                        <template>
                                             <td class="td2" v-if="pagare.gestor == 'NULL'"> <b>Sin asignar</b></td>
                                             <td class="td2" v-else v-text="pagare.gestor"></td>
                                        </template>
                                        <td class="td2" v-text="pagare.fraccionamiento"></td>
                                        <td class="td2" v-text="pagare.etapa"></td>
                                        <td class="td2" v-text="pagare.manzana"></td>
                                        <td class="td2" v-text="pagare.num_lote"></td>
                                        <td class="td2" v-text="parseInt(pagare.num_pago)+1"></td>
                                        <td class="td2" v-if="pagare.restante < 0" v-text="'$'+formatNumber(0)"></td>
                                        <td class="td2" v-else v-text="'$'+formatNumber(pagare.restante)"></td>
                                        <td class="td2" v-text="'$'+formatNumber(pagare.monto_pago - pagare.restante)"></td>
                                        <td class="td2" v-text="this.moment(pagare.fecha_pago).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td class="td2" >
                                            <span v-if="pagare.diferencia > 0 && pagare.pagado < 2" class="badge badge-danger">Vencido</span>
                                            <span v-if="pagare.diferencia < 0 && pagare.pagado < 2" class="badge badge-warning"> Pendiente</span>
                                            <span v-if="pagare.pagado == 2" class="badge badge-success"> Pagado</span>
                                            <span v-if="pagare.pagado == 3" class="badge badge-success"> Liquidado</span>
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
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th># Ref</th>
                                        <th>Cliente</th>
                                        <th>Cuenta</th>
                                        <th># Recibo</th>
                                        <th>Monto</th>
                                        <th>Fecha de deposito</th>
                                        <template v-if="modelo == 'Terreno'">
                                            <th>Pago a capital</th>
                                            <th>Pago de interes</th>
                                            <th>Descuento de interes</th>
                                        </template>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="deposito in arrayDepositos" :key="deposito.id">
                                        <td class="td2" style="width:12%">   
                                        <a type="button" target="_blank" class="btn btn-danger btn-sm" title="Imprimir" v-bind:href="'deposito/reciboPDF/'+deposito.id"> <i class="fa fa-file-pdf-o"></i></a>
                                         &nbsp;
                                            <button type="button" @click="abrirModal('actualizar',deposito)" class="btn btn-warning btn-sm" title="Editar deposito">
                                            <i class="icon-pencil"></i>
                                            </button> &nbsp;
                                            <button type="button" @click="eliminarDeposito(deposito)" class="btn btn-danger btn-sm" title="Eliminar deposito">
                                            <i class="icon-trash"></i>
                                            </button> &nbsp;
                                        </td>
                                        <td class="td2" v-text="referencia"></td>
                                        <td class="td2" v-text="cliente"></td>
                                        <td class="td2" v-text="deposito.banco"></td>
                                        <td class="td2" v-text="deposito.num_recibo"></td>
                                        <td class="td2" v-text="'$'+formatNumber(deposito.cant_depo)"></td>
                                        <template v-if="modelo == 'Terreno'">
                                            <td class="td2" v-text="'$'+formatNumber(deposito.pago_capital)"></td>
                                            <td class="td2" v-text="'$'+formatNumber(deposito.interes_pago)"></td>
                                            <td class="td2" v-text="'$'+formatNumber(deposito.desc_interes)"></td>
                                        </template>
                                        <td class="td2" v-text="this.moment(deposito.fecha_pago).locale('es').format('DD/MMM/YYYY')"></td>
                                    </tr>                               
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-header" v-if="deposito==2">
                        <i class="fa fa-align-justify"></i> Historial de Depositos
                        <!--   Boton Nuevo    -->
                        <button type="button" @click="listarPagares(1,buscar, buscar2, buscar3, b_vencidos,criterio)" class="btn btn-secondary">
                            <i class="fa fa-mail-reply"></i>&nbsp;Regresar
                        </button>
                        <a :href="'/depositos/historial/excel?fecha1=' + b_fecha1 + '&fecha2=' + b_fecha2 + '&banco=' + banco + 
                                '&monto=' + b_deposito+'&b_empresa='+b_empresa"
                            class="btn btn-success"><i class="fa fa-file-text"></i> Excel Pagares
                        </a>
                        <!---->
                    </div>
                    
                    <div class="card-body" v-if="deposito==2">
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
                                    <input type="date" v-model="b_fecha1" @keyup.enter="listarHistorialDep(1)" class="form-control" >
                                    <input type="date" v-model="b_fecha2" @keyup.enter="listarHistorialDep(1)" class="form-control" >
                                </div>
                            </div>
                        </div>
                         <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <input type="text" readonly placeholder="Cliente:" class="form-control col-sm-4">
                                    <input type="text" v-model="b_nombre" @keyup.enter="listarHistorialDep(1)" class="form-control" >
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <select class="form-control" v-model="banco">
                                        <option value="">Seleccione</option>
                                        <option v-for="banco in arrayBancos" :key="banco.num_cuenta + '-' + banco.banco" :value="banco.num_cuenta + '-' + banco.banco" v-text="banco.num_cuenta + '-' + banco.banco"></option>
                                    </select>
                                    
                                    <input type="text" pattern="\d*" v-on:keypress="isNumber($event)" v-model="b_deposito" maxlength="10" class="form-control" placeholder="Monto">
                                    <button type="submit" @click="listarHistorialDep(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
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
                                        <th># Lote</th>
                                        <th>Fecha de deposito</th>
                                        <th>Cuenta</th>
                                        <th># Recibo</th>
                                        <th>$ Monto</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="depo in arrayHistorial" :key="depo.depId">
                                        <td class="td2" v-text="depo.id"></td>
                                        <td class="td2" v-text="depo.nombre + ' ' +depo.apellidos"></td>
                                        <td class="td2" v-text="depo.fraccionamiento"></td>
                                        <td class="td2" v-text="depo.etapa"></td>
                                        <td class="td2" v-text="depo.manzana"></td>
                                        <td class="td2" v-text="depo.num_lote"></td>
                                        <td class="td2" v-text="this.moment(depo.fecha_pago).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td class="td2" v-text="depo.banco"></td>
                                        <td class="td2" v-text="depo.num_recibo"></td>
                                        <td class="td2" v-text="'$'+formatNumber(depo.cant_depo)"></td>
                                        
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
                                <li class="page-item" v-for="page in pagesNumber2" :key="page" :class="[page == isActived2 ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(page)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination2.current_page < pagination2.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page + 1)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>

                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>

            <!--Inicio del modal agregar/actualizar un deposito-->
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
                                    <div class="col-md-4">
                                        <input type="text" disabled v-model="referencia" maxlength="10" class="form-control" placeholder="Numero de referencia">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Proyecto</label>
                                    <div class="col-md-3">
                                        <input type="text" disabled v-model="proyecto" maxlength="50" class="form-control">
                                    </div>
                                    <label class="col-md-2 form-control-label" for="text-input">Etapa</label>
                                    <div class="col-md-3">
                                        <input type="text" v-model="etapa" disabled maxlength="50" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Manzana</label>
                                    <div class="col-md-3">
                                        <input type="text" disabled v-model="manzana" maxlength="50" class="form-control">
                                    </div>
                                    <label class="col-md-2 form-control-label" for="text-input"># Lote</label>
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
                                        <input type="date" v-model="fecha_deposito" @change="calcularDescuento()" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input"><strong>Cantidad a depositar</strong></label>
                                    <div class="col-md-4">
                                        <input type="text" pattern="\d*" @change="calcularDescuento()" v-on:keypress="isNumber($event)" v-model="cant_depo" maxlength="10" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <h6 v-text="'$'+formatNumber(cant_depo)"></h6>
                                    </div>
                                </div>

                                <div class="form-group row" v-if="modelo == 'Terreno' && cant_depo > 0">
                                    <label class="col-md-3 form-control-label" for="text-input">Pago a capital</label>
                                    <div class="col-md-3">
                                        <strong><p v-text="'$'+formatNumber(pago_capital)"></p></strong>
                                    </div>
                                    <label class="col-md-3 form-control-label" for="text-input">Pago a interés</label>
                                    <div class="col-md-3">
                                        <strong><p v-text="'$'+formatNumber(pago_interes)"></p></strong>
                                    </div>
                                </div>


                                <div class="form-group row" v-if="modelo == 'Terreno' && int_terr > 0 && cant_depo > 0">
                                    <label class="col-md-3 form-control-label" for="text-input">Descuento de intereses</label>
                                    <div class="col-md-4">
                                        <input type="text" pattern="\d*" v-on:keypress="isNumber($event)" v-model="descuento" maxlength="10" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <p v-text="'$'+formatNumber(descuento)"></p>
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

                                <div class="form-group row" v-if="interes_mor > 0">
                                    <label class="col-md-3 form-control-label" for="text-input">Observación Interes Moratorio</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="obs_mor" class="form-control">
                                    </div>
                                </div>

                                 <div class="form-group row" v-if="fecha_deposito > fecha_limite">
                                    <label class="col-md-3 form-control-label" for="text-input">Interes Ordinario</label>
                                    <div class="col-md-4">
                                        <input type="text" pattern="\d*" v-on:keypress="isNumber($event)" v-model="interes_ord" maxlength="10" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <p v-text="'$'+formatNumber(interes_ord)"></p>
                                    </div>
                                </div>

                                <div class="form-group row" v-if="interes_ord > 0">
                                    <label class="col-md-3 form-control-label" for="text-input">Observación Interes Ordinario</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="obs_ord" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row" v-if="tipoAccion==1">
                                    <label class="col-md-3 form-control-label" for="text-input">Saldo Restante</label>
                                    <div class="col-md-4">
                                        <p v-text="'$'+formatNumber(restanteTotal = totalRestante)"></p>
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
                                            <option v-for="banco in arrayBancos" :key="banco.num_cuenta + '-' + banco.banco" :value="banco.num_cuenta + '-' + banco.banco" v-text="banco.num_cuenta + '-' + banco.banco"></option>
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
                            <button type="button" v-if="tipoAccion==1" :disabled="disabled == 1" class="btn btn-primary" @click="registrarDeposito(), disabled = 1">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" :disabled="disabled == 1" class="btn btn-primary" @click="actualizarDeposito(), disabled = 1">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->

            <!--Inicio del modal agregar/actualizar un deposito-->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modalDepositos}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
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
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de deposito</label>
                                    <div class="col-md-4">
                                        <label class="col-md-1 form-control-label" for="text-input">Desde</label>
                                        <input type="date" v-model="desde" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="col-md-1 form-control-label" for="text-input">Hasta</label>
                                        <input type="date" v-model="hasta" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Banco</label>
                                    <div class="col-md-6">
                                        <select class="form-control" v-model="banco">
                                            <option value="">Seleccione</option>
                                            <option v-for="banco in arrayBancos" :key="banco.num_cuenta + '-' + banco.banco" :value="banco.num_cuenta + '-' + banco.banco" v-text="banco.num_cuenta + '-' + banco.banco"></option>
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
                            <a :href="'/depositos/excel?desde=' + desde + '&hasta=' + hasta + '&banco=' + banco"  class="btn btn-success"><i class="fa fa-file-text"></i> Descargar</a>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->

             <!--Inicio del modal para mostrar los datos del cliente -->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal1}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" v-text="tituloModal"></h5>
                            <button type="button" class="close" @click="cerrarModal1()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" disabled v-model="nombre_cliente" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Sexo</label>
                                    <div class="col-md-3">
                                        <input type="text" v-model="sexo_cliente" disabled class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Telefono</label>
                                    <div class="col-md-3">
                                        <input type="text" disabled v-model="telefono_cliente" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Celular</label>
                                    <div class="col-md-3">
                                        <input type="text" v-model="celular_cliente" disabled class="form-control">
                                    </div>
                                    <a title="Llamar" class="btn btn-dark" :href="'tel:'+celular_cliente"><i class="fa fa-phone fa-lg"></i></a>
                                    <a title="Enviar whatsapp" class="btn btn-success" target="_blank" :href="'https://api.whatsapp.com/send?phone=+52'+celular_cliente+'&text=Hola'"><i class="fa fa-whatsapp fa-lg"></i></a>             
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Email</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="email_cliente" disabled class="form-control">
                                    </div>
                                    <a title="Enviar correo" class="btn btn-secondary" :href="'mailto:'+email_cliente"> <i class="fa fa-envelope-o fa-lg"></i> </a>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Direccion</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="direccion_cliente" disabled class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">C.P</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="cp_cliente" disabled class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Colonia</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="colonia_cliente" disabled class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Estado</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="estado_cliente" disabled class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Ciudad</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="ciudad_cliente" disabled class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de nacimiento</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="fechanac_cliente" disabled class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">CURP</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="curp_cliente" disabled class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">RFC</label>
                                    <div class="col-md-3">
                                        <input type="text" v-model="rfc_cliente" disabled class="form-control">
                                    </div>
                                    <label class="col-md-3 form-control-label" for="text-input">Homoclave</label>
                                    <div class="col-md-2">
                                        <input type="text" v-model="homoclave_cliente" disabled class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">NSS</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="nss_cliente" disabled class="form-control">
                                    </div>
                                </div>

                                <hr>
                                <h3 style="text-align:center;">LUGAR DE TRABAJO</h3>
                                <hr>
                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Tipo de economia</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="tipoeconomia_cliente" disabled class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Empresa</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="empresa_cliente" disabled class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Giro del negocio</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="gironegocio_cliente" disabled class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Domicilio Empresa</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="domicilio_empresa" disabled class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">C.P</label>
                                    <div class="col-md-3">
                                        <input type="text" v-model="cpempresa_cliente" disabled class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Colonia</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="coloniaempresa_cliente" disabled class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Estado</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="estadoempresa_cliente" disabled class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Ciudad</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="ciudadempresa_cliente" disabled class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Email institucional</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="emailinstitucional_cliente" disabled class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Telefono de la empresa</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="telefonoempresa_cliente" disabled class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">EXT</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="ext_cliente" disabled class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Estado civil</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="edocivil_cliente" disabled class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input"># Dependientes economicos</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="depeconomicos_cliente" disabled class="form-control">
                                    </div>
                                </div>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal1()">Cerrar</button>
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
                arrayHistorial : [],
                arrayBancos : [],
                modal : 0,
                modalDepositos : 0,
                modal1: 0,
                deposito : 0,
                tituloModal : '',
                tipoAccion: 0,
                errorDeposito : 0,
                errorMostrarMsjDeposito : [],

                disabled:0,

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
                restanteTotal:0,
                monto_pagare:0,
                pago_id:0,
                diferencia:0,
                desde:'',
                hasta:'',

                descuento:0,

                //para los datos del cliente
                nombre_cliente: '',
                sexo_cliente: '',
                telefono_cliente: '',
                celular_cliente:'',
                email_cliente: '',
                direccion_cliente: '',
                cp_cliente: 0,
                colonia_cliente: '',
                estado_cliente: '',
                ciudad_cliente: '',
                fechanac_cliente:'',
                curp_cliente:'',
                rfc_cliente:'',
                homoclave_cliente:'',
                nss_cliente:0,
                tipoeconomia_cliente:'',
                empresa_cliente:'',
                gironegocio_cliente:'',
                domicilio_empresa:'',
                cpempresa_cliente: 0,
                coloniaempresa_cliente: '',
                estadoempresa_cliente: '',
                ciudadempresa_cliente: '',
                emailinstitucional_cliente: '',
                telefonoempresa_cliente: '',
                ext_cliente: 0,
                edocivil_cliente: '',
                depeconomicos_cliente: 0,

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
                b_vencidos : 0,
                b_deposito : '',
                b_fecha1 : '',
                b_fecha2 : '',
                b_nombre :'',
                b_empresa:'',
                empresas:[],

                dias_int:0,
                int_terr:0,
                pago_capital:0,
                pago_interes:0,
                modelo:'',
                dias_desc:0,
            }
        },
        computed:{
            
            totalRestante: function(){
                var totalRestante = parseFloat(this.restante) + parseFloat(this.interes_mor) + parseFloat(this.interes_ord) - parseFloat(this.cant_depo);
                return totalRestante;
            },

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
            /**Metodo para mostrar los registros */
            listarHistorialDep(page){
                let me = this;
                me.deposito = 2;
                var url = '/depositos/historial?page=' + page + '&fecha1=' + me.b_fecha1 + '&fecha2=' + me.b_fecha2 + 
                    '&banco=' + me.banco + '&monto=' + me.b_deposito +'&b_empresa='+this.b_empresa + '&nombre=' + me.b_nombre;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayHistorial = respuesta.depositos.data;
                    me.pagination2 = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
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
                var url = '/pagares?page=' + page + '&buscar=' + buscar + '&buscar2=' + buscar2 + '&buscar3=' + buscar3 + 
                '&b_vencidos=' + b_vencidos + '&criterio=' + criterio +'&b_empresa='+this.b_empresa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPagares = respuesta.pagares.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            calcularDescuento(){
                this.descuento = 0;
                this.pago_capital = 0;
                this.pago_interes = 0;
                if(this.modelo == 'Terreno' && this.monto_pagare <= this.cant_depo)
                {
                    var a = moment(this.fecha_limite);
                    var b = moment(this.fecha_deposito);
                    var interes = this.int_terr / this.dias_int;
                    this.dias_desc = a.diff(b, 'days');
                    
                    if(this.dias_desc > 0){
                        this.descuento = this.dias_desc * interes;
                        if(this.descuento > this.int_terr)
                            this.descuento = this.int_terr;

                    }
                    
                        
                }
                if(this.modelo == 'Terreno' && this.monto_pagare >= this.cant_depo)
                {
                        var capital = this.monto_pagare - this.int_terr;
                        var porcent_cap = capital/this.monto_pagare;
                        var porcent_int = this.int_terr/this.monto_pagare;
                        let diff=0;

                        this.pago_interes = this.cant_depo*porcent_int;
                        if(this.pago_interes > this.int_terr){
                            dif = this.pago_interes - this.int_terr;
                            this.pago_interes = this.int_terr;
                        }
                        this.pago_capital = this.cant_depo*porcent_cap+diff;
                    
                        
                }
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
            cambiarPagina2(page){
                let me = this;
                //Actualiza la pagina actual
                me.pagination2.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarHistorialDep(page);
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

            abrirPDF(id){
                const win = window.open('/estadoCuenta/estadoPDF/'+id, '_blank');
                win.focus();
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

                Swal.showLoading()

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
                    'restante':this.restanteTotal,

                    'pago_capital':this.pago_capital,
                    'pago_interes':this.pago_interes,
                    'descuento':this.descuento,
                }).then(function (response){
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarDepositos(); //se enlistan nuevamente los registros
                    me.disabled=0;
                    Swal.enableLoading()	
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
                    me.disabled=0;
                    Swal.enableLoading()
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
                    
                    'pago_capital':this.pago_capital,
                    'pago_interes':this.pago_interes,
                    'descuento':this.descuento,
                }).then(function (response){
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarDepositos(); //se enlistan nuevamente los registros
                    me.disabled=0;
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
                    me.disabled=0;
                });
            },
            eliminarDeposito(data =[]){
                   let me = this;
                me.id=data['id'];
                me.pago_id = data['pago_id'];
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
                 

                axios.delete('/deposito/eliminar', 
                         { params: {'id': me.id,
                        'pago_id': me.pago_id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Deposito borrado correctamente.',
                        'success'
                        )
                         me.listarDepositos();
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
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
                this.descuento = 0;
                this.num_recibo='';
                this.banco='';
                this.concepto='';
                this.monto_pagare = data['monto_pago'];
                this.restante= data['restante'];
                this.pago_id = data['pago'];
                this.diferencia=data['diferencia'];
                this.modelo = data['modelo'];

                if(this.modelo == 'Terreno'){
                    this.getDatosPago(this.pago_id);
                }

                this.listarDepositos();

               
            },
            getDatosPago(id){
                let me = this;
                var url = '/pagaresLotes/getDatosPago?pagare_id=' + id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    var datos;
                    datos = respuesta.datos_pago;
                    me.int_terr = datos[0].interes_monto;
                    me.dias_int = datos[0].dias;
                })
                .catch(function (error) {
                    console.log(error);
                });
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
                this.modalDepositos = 0;
                this.desde = '';
                this.hasta = '';

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
                this.descuento = 0;
                this.pago_interes = 0;
                this.pago_capital = 0;

                this.errorDeposito = 0;
                this.errorMostrarMsjDeposito = [];

            },
            cerrarModal1(){
                this.modal1 = 0;
                this.tituloModal = '';
                this.nombre_cliente = '';
                this.sexo_cliente= '';
                this.telefono_cliente = '';
                this.celular_cliente = '';
                this.email_cliente = '';
                this.direccion_cliente = '';
                this.cp_cliente=0;
                this.colonia_cliente = '';
                this.estado_cliente='';
                this.ciudad_cliente='';
                this.fechanac_cliente='';
                this.curp_cliente='';
                this.rfc_cliente='';
                this.homoclave_cliente='';
                this.nss_cliente=0;
                this.tipoeconomia_cliente='';
                this.empresa_cliente='';
                this.gironegocio_cliente='';
                this.domicilio_empresa='';
                this.cpempresa_cliente=0;
                this.coloniaempresa_cliente='';
                this.estadoempresa_cliente='';
                this.ciudadempresa_cliente='';
                this.emailinstitucional_cliente='';
                this.telefonoempresa_cliente='';
                this.ext_cliente=0;
                this.edocivil_cliente='';
                this.depeconomicos_cliente=0;
        

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
                        this.descuento = data['desc_interes'];
                        this.pago_capital = data['pago_capital'];
                        this.pago_interes = data['interes_pago'];
                        
                        this.id=data['id'];
                        break;
                    }
                    case 'datosCliente':
                    {
                        this.modal1 =1;
                        this.tituloModal='Datos del prospecto';
                        this.tipoAccion=3;
                        this.nombre_cliente = data['nombre']+' '+ data['apellidos'];
                        if(data['sexo'] == "M"){
                            this.sexo_cliente= 'Masculino';
                        }else{
                            this.sexo_cliente= 'Femenino';
                        }
                        this.telefono_cliente = data['telefono'];
                        this.celular_cliente = data['celular'];
                        this.email_cliente = data['email'];
                        this.direccion_cliente =data['direccion'];
                        this.cp_cliente= data['cp'];
                        this.colonia_cliente = data['colonia'];
                        this.estado_cliente=data['estado'];
                        this.ciudad_cliente=data['ciudad'];
                        this.fechanac_cliente=data['f_nacimiento'];
                        this.curp_cliente=data['curp'];
                        this.rfc_cliente=data['rfc'];
                        this.homoclave_cliente=data['homoclave'];
                        this.nss_cliente=data['nss'];
                        this.tipoeconomia_cliente=data['tipo_economia'];
                        this.empresa_cliente=data['empresa'];
                        this.gironegocio_cliente=data['puesto'];
                        this.domicilio_empresa=data['direccion_empresa'];
                        this.cpempresa_cliente=data['cp_empresa'];
                        this.coloniaempresa_cliente=data['colonia_empresa'];
                        this.estadoempresa_cliente=data['estado_empresa'];
                        this.ciudadempresa_cliente=data['ciudad_empresa'];
                        this.emailinstitucional_cliente=data['email_institucional'];
                        this.telefonoempresa_cliente=data['telefono_empresa'];
                        this.ext_cliente=data['ext_empresa'];

                        switch(data['edo_civil']){
                            case 1: {
                                this.edocivil_cliente = 'Casado - separacion de bienes';
                                break;
                            }
                            case 2:{
                                this.edocivil_cliente = 'Casado - sociedad conyugal';
                                break;
                            }
                            case 3:{
                                this.edocivil_cliente = 'Divorciado';
                                break;
                            }
                            case 4:{
                                this.edocivil_cliente = 'Soltero';
                                break;
                            }
                            case 5:{
                                this.edocivil_cliente = 'Union libre';
                                break;
                            }
                            case 6:{
                                this.edocivil_cliente = 'Viudo';
                                break;
                            }
                            default:{
                                this.edocivil_cliente = 'Otro';
                                break;
                            }
                        }
                        
                        this.depeconomicos_cliente=data['num_dep_economicos'];
                        break;
                    }
                    case 'depositos':
                    {
                        this.modalDepositos = 1;
                        this.tituloModal = 'Depositos';
                        this.desde = '';
                        this.hasta = '';
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
            this.listarPagares(1,this.buscar, this.buscar2, this.buscar3, this.b_vencidos, this.criterio);
            this.selectFraccionamiento();
            this.selectCuenta();
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

</style>
