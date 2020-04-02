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
                        <i class="fa fa-align-justify"></i>Solicitud de pago
                        <!--   Boton Nuevo    -->
                        <button type="button" @click="abrirModal('generar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nueva solicitud
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <label class="form-control"> Fecha de solicitud</label>
                                    <input type="date"  v-model="b_fecha1" @keyup.enter="listarOrdenes(1)" class="form-control">
                                    <input type="date"  v-model="b_fecha2" @keyup.enter="listarOrdenes(1)" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text"  v-model="buscar" @keyup.enter="listarOrdenes(1)" class="form-control" placeholder="# de Orden">
                                    <button type="submit" @click="listarOrdenes(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th colspan="4"></th>
                                        <th colspan="6" class="text-center">Solicitud</th>
                                        <th colspan="2"></th>
                                    </tr>
                                    <tr>
                                        <th>#</th>
                                        <th>Solicitante</th>
                                        <th>Fecha de solicitud</th>
                                        <th>Concepto</th>
                                        <th>Orden compra</th>
                                        <th class="td2">Solicitud de Cheque</th>
                                        <th>Cotización</th>
                                        <th class="td2">Pago en partes</th>
                                        <th>Factura</th>
                                        <th>Otros Documentos</th>
                                        <th>Status</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="orden in arraySolicitud" :key="orden.id">
                                        <td class="td2" v-text="orden.id"></td>
                                        <td class="td2" v-text="orden.nombre+' '+orden.apellidos"></td>
                                        <td class="td2" v-text="this.moment(orden.created_at).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td class="td2" v-text="orden.concepto"></td>
                                        <template v-if="orden.orden_compra == 0">
                                            <td class="td2"> No aplica</td>
                                            <td class="td2 text-center" >
                                                <button type="button" @click="verSoliCheque(orden.solic_cheque)" class="btn btn-primary btn-sm" title="Solicitud de Cheque">
                                                    <i class="icon-eye"></i>
                                                </button>
                                                <button v-if="orden.check1 == null" type="button" @click="abrirModal('cheque',orden)" class="btn btn-warning btn-sm" title=" Actualizar solicitud de cheque">
                                                    <i class="fa fa-cloud-upload"></i>
                                                </button>
                                            </td>
                                        </template>
                                        <template v-if="orden.orden_compra == 1">
                                            <td class="td2 text-center"> 
                                                <button type="button" @click="verOrdenCompra(orden.doc_orden)" class="btn btn-success btn-sm" title="Orden de compra">
                                                    <i class="icon-eye"></i>
                                                </button>
                                                <button v-if="orden.autorizacion_orden == null" type="button" @click="abrirModal('ordenCompra',orden)" class="btn btn-warning btn-sm" title=" Actualizar orden de compra">
                                                    <i class="fa fa-cloud-upload"></i>
                                                </button>
                                            </td>
                                            <td v-if="orden.autorizacion_orden == null">
                                                Orden de compra sin autorizacion
                                            </td>
                                            <td v-if="orden.autorizacion_orden && orden.solic_cheque == null">
                                                <button type="button" @click="abrirModal('solicitud_cheque',orden)" class="btn btn-primary btn-sm" title="Cargar solicitud de Cheque">
                                                    <i class="fa fa-pencil-square-o"></i>&nbsp;Solicitud de cheque
                                                </button>
                                            </td>
                                            <td class="td2 text-center" v-if="orden.autorizacion_orden && orden.solic_cheque">
                                                <button type="button" @click="verSoliCheque(orden.solic_cheque)" class="btn btn-primary btn-sm" title="Solicitud de Cheque">
                                                    <i class="icon-eye"></i>
                                                </button>
                                                <button v-if="orden.check1 == null" type="button" @click="abrirModal('cheque',orden)" class="btn btn-warning btn-sm" title=" Actualizar solicitud de cheque">
                                                    <i class="fa fa-cloud-upload"></i>
                                                </button>
                                            </td>
                                        </template>
                                        <!-- Cotizacion -->
                                            <td class="td2" v-if="orden.autorizacion_orden == null && orden.orden_compra == 1">
                                                Orden de compra sin autorizacion
                                            </td>
                                            <td class="td2" v-else-if="orden.autorizacion_orden && orden.solic_cheque == null || orden.solic_cheque == null && orden.orden_compra == 0">
                                                Sin solicitud de cheque
                                            </td>
                                            <td class="td2 text-center" v-else-if="orden.autorizacion_orden && orden.solic_cheque && orden.cotizacion == null && orden.check2 == null || orden.solic_cheque && orden.cotizacion == null && orden.orden_compra == 0 && orden.check2 == null">
                                                <button type="button" @click="abrirModal('cotizacion',orden)" class="btn btn-success btn-sm" title="Cotización">
                                                    <i class="icon-plus"></i> Subir cotización
                                                </button>
                                            </td>
                                            <td class="td2 text-center" v-else-if="orden.autorizacion_orden && orden.solic_cheque && orden.cotizacion == null && orden.check2 || orden.solic_cheque && orden.cotizacion == null && orden.orden_compra == 0 && orden.check2">
                                                Sin archivo
                                            </td>
                                            <td class="td2 text-center" v-else>
                                                <button type="button" @click="verCotizacion(orden.cotizacion)" class="btn btn-success btn-sm" title="Cotización">
                                                    <i class="icon-eye"></i>
                                                </button>
                                                <button v-if="orden.check1 == null" type="button" @click="abrirModal('cotizacion',orden)" class="btn btn-warning btn-sm" title=" Actualizar cotización">
                                                    <i class="fa fa-cloud-upload"></i>
                                                </button>
                                            </td>

                                        <!-- Pago en partes -->
                                            <td class="td2" v-if="orden.autorizacion_orden == null && orden.orden_compra == 1">
                                                Orden de compra sin autorizacion
                                            </td>
                                            <td class="td2" v-else-if="orden.autorizacion_orden && orden.solic_cheque == null || orden.solic_cheque == null && orden.orden_compra == 0">
                                                Sin solicitud de cheque
                                            </td>
                                            <td class="td2 text-center" v-else-if="orden.autorizacion_orden && orden.solic_cheque && orden.pago_partes == null && orden.check2 == null || orden.solic_cheque && orden.pago_partes == null && orden.orden_compra == 0 && orden.check2 == null">
                                                <button type="button" @click="abrirModal('pago_partes',orden)" class="btn btn-primary btn-sm" title="Pago en partes">
                                                    <i class="icon-plus"></i> Subir pago en partes
                                                </button>
                                            </td>
                                            <td class="td2 text-center" v-else-if="orden.autorizacion_orden && orden.solic_cheque && orden.pago_partes == null && orden.check2 || orden.solic_cheque && orden.pago_partes == null && orden.orden_compra == 0 && orden.check2">
                                                Sin archivo
                                            </td>
                                            <td class="td2 text-center" v-else>
                                                <button type="button" @click="verPagoPartes(orden.pago_partes)" class="btn btn-primary btn-sm" title="Pago en partes">
                                                    <i class="icon-eye"></i>
                                                </button>
                                                <button v-if="orden.check1 == null" type="button" @click="abrirModal('pago_partes',orden)" class="btn btn-warning btn-sm" title=" Actualizar pago en partes">
                                                    <i class="fa fa-cloud-upload"></i>
                                                </button>
                                            </td>

                                        <!-- Factura -->
                                            <td class="td2" v-if="orden.autorizacion_orden == null && orden.orden_compra == 1">
                                                Orden de compra sin autorizacion
                                            </td>
                                            <td class="td2" v-else-if="orden.autorizacion_orden && orden.solic_cheque == null || orden.solic_cheque == null && orden.orden_compra == 0">
                                                Sin solicitud de cheque
                                            </td>
                                            <td class="td2 text-center" v-else-if="orden.autorizacion_orden && orden.solic_cheque && orden.factura == null && orden.check2 == null  || orden.solic_cheque && orden.factura == null && orden.orden_compra == 0 && orden.check2 == null">
                                                <button type="button" @click="abrirModal('factura',orden)" class="btn btn-success btn-sm" title="Pago en partes">
                                                    <i class="icon-plus"></i> Subir factura
                                                </button>
                                            </td>
                                            <td class="td2 text-center" v-else-if="orden.autorizacion_orden && orden.solic_cheque && orden.factura == null && orden.check2 || orden.solic_cheque && orden.factura == null && orden.orden_compra == 0 && orden.check2">
                                                Sin archivo
                                            </td>
                                            <td class="td2 text-center" v-else>
                                                <button type="button" @click="verFactura(orden.factura)" class="btn btn-success btn-sm" title="Pago en partes">
                                                    <i class="icon-eye"></i>
                                                </button>
                                                <button v-if="orden.check1 == null" type="button" @click="abrirModal('factura',orden)" class="btn btn-warning btn-sm" title=" Actualizar factura">
                                                    <i class="fa fa-cloud-upload"></i>
                                                </button>
                                            </td>

                                        <!-- Otros documentos -->
                                            <td class="td2" v-if="orden.autorizacion_orden == null && orden.orden_compra == 1">
                                                Orden de compra sin autorizacion
                                            </td>
                                            <td class="td2" v-else-if="orden.autorizacion_orden && orden.solic_cheque == null || orden.solic_cheque == null && orden.orden_compra == 0">
                                                Sin solicitud de cheque
                                            </td>
                                            <td class="td2 text-center" v-else>
                                                <div class="form-group row text-center">
                                                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false" @click="getDocumentos(orden.id)"><i class="icon-cloud-download"></i></a>
                                                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
                                                        <a v-for="archivo in arrayDocumentos" :key="archivo.id" class="dropdown-item" href="#" v-on:click="verDocumento(archivo.archivo)">{{archivo.nombre}}</a>
                                                    </div>
                                                    <button v-if="orden.check2 == null" type="button" @click="abrirModal('otroDocumento',orden)" class="btn btn-success btn-sm" title="Agregar otro documento">
                                                        <i class="icon-plus"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        
                                        <td class="td2">
                                            <span v-if="orden.status == null" class="badge badge-warning">Pendiente</span>
                                            <span v-if="orden.status == 0" class="badge badge-warning">Orden de compra en proceso</span>
                                            <span v-if="orden.status == 1" class="badge badge-primary">Orden de compra autorizada</span>
                                            <span v-if="orden.status == 2" class="badge badge-primary">Solicitud de cheque en proceso</span>
                                            <span v-if="orden.status == 3" class="badge badge-primary">Solicitud de cheque autorizado</span>
                                            <span v-if="orden.status == 4" class="badge badge-success">Solicitud pagada</span>
                                            <span v-if="orden.status == 5" class="badge badge-danger">Cancelado</span>
                                        </td>
                                        <td>
                                            <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right" 
                                                    @click="abrirModal('observaciones',orden)">Observaciones</button>
                                        </td>
                                    </tr>                               
                                </tbody>
                            </table>
                        </div>  
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page > 7">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(1)">Inicio</a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1)">Sig</a>
                                </li>
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page<pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.last_page)">Ultimo</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal generacion-->
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

                            <template v-if="tipoAccion == 1">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input"> ¿Tiene orden de compra?</label>
                                    <div class="col-md-5">
                                        <select class="form-control col-md-5" v-model="bool_orden" >
                                            <option value=2>Seleccione</option>
                                            <option value=0>No</option>
                                            <option value=1>Si</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input"> Concepto</label>
                                    <div class="col-md-7">
                                        <input type="text" v-model="concepto" class="form-control" placeholder="Concepto">
                                    </div>
                                </div>

                                <div class="form-group" v-if="bool_orden == 1">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr><th>
                                                <form v-if="bool_orden == 1" method="post" @submit="formSubmitOrdenCompra" enctype="multipart/form-data">
                                                    <div class="form-group row" v-if="bool_orden == 1">
                                                        <label class="col-md-3 form-control-label" for="text-input"> <strong>Orden de compra</strong> </label>
                                                        <div class="col-md-5">
                                                            <input type="file" class="form-control" v-on:change="onImageChangeOrdenCompra">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="submit" class="btn btn-success">Cargar</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </th></tr>
                                        </thead>
                                    </table>
                                </div>

                                <div class="form-group" v-if="bool_orden == 0">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr><th>
                                                <form v-if="bool_orden == 0" method="post" @submit="formSubmitSolicCheque" enctype="multipart/form-data">
                                                    <div class="form-group row" v-if="bool_orden == 0">
                                                        <label class="col-md-3 form-control-label" for="text-input"> <strong>Solicitud de cheque</strong> </label>
                                                        <div class="col-md-5">
                                                            <input type="file" class="form-control" v-on:change="onImageChangeSolicCheque">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="submit" class="btn btn-success">Cargar</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </th></tr>
                                        </thead>
                                    </table>
                                </div>
                               
                            </template>

                            <template v-if="tipoAccion == 2">
                                <div class="form-group">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr><th>
                                                <form method="post" @submit="formSubmitSolicChequeAct" enctype="multipart/form-data">
                                                    <div class="form-group row">
                                                        <label class="col-md-3 form-control-label" for="text-input"> <strong>Solicitud de cheque</strong> </label>
                                                        <div class="col-md-5">
                                                            <input type="file" class="form-control" v-on:change="onImageChangeSolicCheque">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="submit" class="btn btn-success">Cargar</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </th></tr>
                                        </thead>
                                    </table>
                                </div>
                               
                            </template>

                            <template v-if="tipoAccion == 3">
                                <div class="form-group" >
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr><th>
                                                <form method="post" @submit="formSubmitCotizacion" enctype="multipart/form-data">
                                                    <div class="form-group row">
                                                        <label class="col-md-3 form-control-label" for="text-input"> <strong>Cotización</strong> </label>
                                                        <div class="col-md-5">
                                                            <input type="file" class="form-control" v-on:change="onImageChangeCotizacion">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="submit" class="btn btn-success">Cargar</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </th></tr>
                                        </thead>
                                    </table>
                                </div>
                               
                            </template>

                            <template v-if="tipoAccion == 4">
                                <div class="form-group" >
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr><th>
                                                <form method="post" @submit="formSubmitPagoPartes" enctype="multipart/form-data">
                                                    <div class="form-group row">
                                                        <label class="col-md-3 form-control-label" for="text-input"> <strong>Pago en partes</strong> </label>
                                                        <div class="col-md-5">
                                                            <input type="file" class="form-control" v-on:change="onImageChangePagoPartes">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="submit" class="btn btn-success">Cargar</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </th></tr>
                                        </thead>
                                    </table>
                                </div>
                               
                            </template>

                            <template v-if="tipoAccion == 5">
                                <div class="form-group" >
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr><th>
                                                <form method="post" @submit="formSubmitFactura" enctype="multipart/form-data">
                                                    <div class="form-group row">
                                                        <label class="col-md-3 form-control-label" for="text-input"> <strong>Factura</strong> </label>
                                                        <div class="col-md-5">
                                                            <input type="file" class="form-control" v-on:change="onImageChangeFactura">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="submit" class="btn btn-success">Cargar</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </th></tr>
                                        </thead>
                                    </table>
                                </div>
                               
                            </template>

                            <template v-if="tipoAccion == 7">
                                <div class="form-group" >
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr><th>
                                                <form method="post" @submit="formSubmitOrden" enctype="multipart/form-data">
                                                    <div class="form-group row">
                                                        <label class="col-md-3 form-control-label" for="text-input"> <strong>Orden de compra</strong> </label>
                                                        <div class="col-md-5">
                                                            <input type="file" class="form-control" v-on:change="onImageChangeOrden">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="submit" class="btn btn-success">Cargar</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </th></tr>
                                        </thead>
                                    </table>
                                </div>
                               
                            </template>

                            <template v-if="tipoAccion == 8">
                                <div class="form-group" >
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr><th>
                                                <form method="post" @submit="formSubmitCheque" enctype="multipart/form-data">
                                                    <div class="form-group row">
                                                        <label class="col-md-3 form-control-label" for="text-input"> <strong>Solicitud de cheque</strong> </label>
                                                        <div class="col-md-5">
                                                            <input type="file" class="form-control" v-on:change="onImageChangeCheque">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="submit" class="btn btn-success">Cargar</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </th></tr>
                                        </thead>
                                    </table>
                                </div>
                               
                            </template>

                            <template v-if="tipoAccion == 6">
                                <div class="form-group" >
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr><th>
                                                <form method="post" @submit="formSubmitOtro" enctype="multipart/form-data">
                                                    <div class="form-group row">
                                                        <label class="col-md-2 form-control-label" for="text-input"> <strong>Otros Documentos</strong> </label>
                                                        <div class="col-md-3">
                                                            <input type="text" v-model="nombre" class="form-control" placeholder="Nombre del archivo">
                                                        </div>
                                                        <div class="col-md-5">
                                                            <input type="file" class="form-control" v-on:change="onImageChangeOtro">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="submit" class="btn btn-success">Cargar</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </th></tr>
                                        </thead>
                                    </table>

                                    <div class="col-md-12" v-if="arrayDocumentos.length">
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <table class="table table-bordered table-striped table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th style="width:12%"></th>
                                                            <th>Archivo</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="version in arrayDocumentos" :key="version.id">
                                                            <td style="width:12%">
                                                                <button @click="eliminarArchivo(version.id)" type="button" class="btn btn-danger btn-sm" title="Quitar archivo">
                                                                    <i class="icon-close"></i>
                                                                </button>
                                                            </td>
                                                            <td>
                                                                <a href="#" v-on:click="verDocumento(version.archivo)">{{version.nombre}}</a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               
                            </template>

                            <template v-if="tipoAccion == 9">
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
                            </template>
                            
                              
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
                id: 0,
                arraySolicitud : [],
                arrayDocumentos : [],
                arrayObservacion : [],
                modal : 0,
                modal2 : 0,
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
                criterio : 'lotes.fraccionamiento_id', 
                buscar : '',
                b_fecha1:'',
                b_fecha2:'',
                bool_orden : 2,
                archivoOrden: '',
                archivoCheque:'',
                archivo:'',
                concepto:'',
                id:'',
                nombre:'',
                observacion : ''
                
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

            onImageChangeSolicCheque(e){

                console.log(e.target.files[0]);

                this.archivoCheque = e.target.files[0];

            },

            formSubmitSolicCheque(e) {

                e.preventDefault();

                let currentObj = this;
                let formData = new FormData();
               // formData.append('id', this.id);
                formData.append('archivo', this.archivoCheque);
                let me = this;
                axios.post('/solic_pago/storeSinOrden/'+ me.concepto, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Solicitud creada correctamente',
                        showConfirmButton: false,
                        timer: 2000
                        })
                    me.cerrarModal();
                    me.listarOrdenes(1);

                })

                .catch(function (error) {

                    currentObj.output = error;

                });

            },

            formSubmitSolicChequeAct(e) {

                e.preventDefault();

                let currentObj = this;
                let formData = new FormData();
               // formData.append('id', this.id);
                formData.append('archivo', this.archivoCheque);
                let me = this;
                axios.post('/solic_pago/putSolicCheque/'+ me.id, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Solicitud de cheque agregada correctamente',
                        showConfirmButton: false,
                        timer: 2000
                        })
                    me.cerrarModal();
                    me.listarOrdenes(1);

                })

                .catch(function (error) {

                    currentObj.output = error;

                });

            },

            onImageChangeCotizacion(e){

                console.log(e.target.files[0]);

                this.archivo = e.target.files[0];

            },

            formSubmitCotizacion(e) {

                e.preventDefault();

                let currentObj = this;
                let formData = new FormData();
               // formData.append('id', this.id);
                formData.append('archivo', this.archivo);
                let me = this;
                axios.post('/solic_pago/putCotizacion/'+ me.id, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Cotización agregada correctamente',
                        showConfirmButton: false,
                        timer: 2000
                        })
                    me.cerrarModal();
                    me.listarOrdenes(1);

                })

                .catch(function (error) {

                    currentObj.output = error;

                });

            },

            onImageChangePagoPartes(e){

                console.log(e.target.files[0]);

                this.archivo = e.target.files[0];

            },

            formSubmitPagoPartes(e) {

                e.preventDefault();

                let currentObj = this;
                let formData = new FormData();
               // formData.append('id', this.id);
                formData.append('archivo', this.archivo);
                let me = this;
                axios.post('/solic_pago/putPagoPartes/'+ me.id, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Pago en partes agregado correctamente',
                        showConfirmButton: false,
                        timer: 2000
                        })
                    me.cerrarModal();
                    me.listarOrdenes(1);

                })

                .catch(function (error) {

                    currentObj.output = error;

                });

            },

            onImageChangeFactura(e){

                console.log(e.target.files[0]);

                this.archivo = e.target.files[0];

            },

            formSubmitFactura(e) {

                e.preventDefault();

                let currentObj = this;
                let formData = new FormData();
               // formData.append('id', this.id);
                formData.append('archivo', this.archivo);
                let me = this;
                axios.post('/solic_pago/putFactura/'+ me.id, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Factura agregada correctamente',
                        showConfirmButton: false,
                        timer: 2000
                        })
                    me.cerrarModal();
                    me.listarOrdenes(1);

                })

                .catch(function (error) {

                    currentObj.output = error;

                });

            },

            onImageChangeOrden(e){

                console.log(e.target.files[0]);

                this.archivo = e.target.files[0];

            },

            formSubmitOrden(e) {

                e.preventDefault();

                let currentObj = this;
                let formData = new FormData();
               // formData.append('id', this.id);
                formData.append('archivo', this.archivo);
                let me = this;
                axios.post('/solic_pago/putOrden/'+ me.id, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Orden de compra agregada correctamente',
                        showConfirmButton: false,
                        timer: 2000
                        })
                    me.cerrarModal();
                    me.listarOrdenes(1);

                })

                .catch(function (error) {

                    currentObj.output = error;

                });

            },

            onImageChangeCheque(e){

                console.log(e.target.files[0]);

                this.archivo = e.target.files[0];

            },

            formSubmitCheque(e) {

                e.preventDefault();

                let currentObj = this;
                let formData = new FormData();
               // formData.append('id', this.id);
                formData.append('archivo', this.archivo);
                let me = this;
                axios.post('/solic_pago/putCheque/'+ me.id, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Solicitud de cheque agregada correctamente',
                        showConfirmButton: false,
                        timer: 2000
                        })
                    me.cerrarModal();
                    me.listarOrdenes(1);

                })

                .catch(function (error) {

                    currentObj.output = error;

                });

            },

            listarObservacion(buscar){
                let me = this;
                var url = '/solic_pago/indexComentarios?id=' + buscar ;
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
                axios.post('/solic_pago/storeComentarios',{
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

            onImageChangeOtro(e){

                console.log(e.target.files[0]);

                this.archivo = e.target.files[0];

            },

            formSubmitOtro(e) {

                e.preventDefault();

                let currentObj = this;
                let formData = new FormData();
               // formData.append('id', this.id);
                formData.append('archivo', this.archivo);
                let me = this;
                axios.post('/solic_pago/storeDocumento/'+ me.id + '/' + me.nombre, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Documento agregado correctamente',
                        showConfirmButton: false,
                        timer: 2000
                        })
                    me.getDocumentos(me.id);

                })

                .catch(function (error) {

                    currentObj.output = error;

                });

            },

            onImageChangeOrdenCompra(e){

                console.log(e.target.files[0]);

                this.archivoOrden = e.target.files[0];

            },

            formSubmitOrdenCompra(e) {

                e.preventDefault();

                let currentObj = this;
                let formData = new FormData();
               // formData.append('id', this.id);
                formData.append('archivo', this.archivoOrden);
                let me = this;
                axios.post('/solic_pago/storeConOrden/'+ this.concepto, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Solicitud creada correctamente',
                        showConfirmButton: false,
                        timer: 2000
                        })
                    me.cerrarModal();
                    me.listarOrdenes(1);

                })

                .catch(function (error) {

                    currentObj.output = error;

                });

            },

            eliminarArchivo(id){
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

                axios.delete('/solic_pago/deleteArchivo', 
                        {params: {'id': id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Archivo borrado correctamente.',
                        'success'
                        )
                        me.getDocumentos(me.id);
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },

            verSoliCheque(nombre){
                window.open('/files/solicPago/solicCheque/'+ nombre, '_blank')
            },

            verCotizacion(nombre){
                window.open('/files/solicPago/cotizacion/'+ nombre, '_blank')
            },

            verPagoPartes(nombre){
                window.open('/files/solicPago/pagoPartes/'+ nombre, '_blank')
            },

            verFactura(nombre){
                window.open('/files/solicPago/factura/'+ nombre, '_blank')
            },

            verDocumento(nombre){
                window.open('/files/solicPago/documentos/'+ nombre, '_blank')
            },

            verOrdenCompra(nombre){
                window.open('/files/solicPago/ordenCompra/'+ nombre, '_blank')
            },


            /**Metodo para mostrar los registros */
            listarOrdenes(page){
                let me = this;
                var url = '/solic_pago/indexSolicitudes?page=' + page + '&buscar=' + me.buscar + '&fecha1=' + me.b_fecha1 + '&fecha2=' + me.b_fecha2 + '&tipo=0'; 
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arraySolicitud = respuesta.solicitudes.data;
                    me.pagination = respuesta.pagination;
                    console.log(url);
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },

            cargarInformacion(){

                let me = this;
                //Con axios se llama el metodo update de FraccionaminetoController
                axios.put('/ruv/cargaInfo',{
                    'id' : this.id,
                    'fecha': this.fecha
                }).then(function (response){
                    me.cerrarModal();
                    me.listarOrdenes(1);
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

            getDocumentos(id){
                let me = this;
                me.arrayDocumentos=[];
                var url = '/solic_pago/getDocumentos?id='+id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayDocumentos = respuesta.versiones;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },


            cambiarPagina(page){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarOrdenes(page);
            },

            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.archivoCheque = '';
                this.archivoOrden = '';
                this.concepto = '';

            },

            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(accion,data =[]){
                switch(accion){
                    
                    case 'generar':
                    {
                        this.modal =1;
                        this.tituloModal='Nueva solicitud de pago';
                        this.tipoAccion=1;
                        break;
                    }
                    case 'solicitud_cheque':
                    {
                        this.id = data['id'];
                        this.modal = 1
                        this.tituloModal='Solicitud de cheque';
                        this.tipoAccion=2;
                        break;
                    }
                    case 'cotizacion':
                    {
                        this.id = data['id'];
                        this.modal = 1;
                        this.tituloModal = 'Cotización';
                        this.tipoAccion=3;
                        break;
                    }
                    case 'pago_partes':{
                        this.id = data['id'];
                        this.modal = 1;
                        this.tituloModal = 'Pago en partes';
                        this.tipoAccion=4;
                        break;
                    }
                    case 'factura':{
                        this.id = data['id'];
                        this.modal = 1;
                        this.tituloModal = 'Factura';
                        this.tipoAccion=5;
                        break;
                    }
                    case 'otroDocumento':{
                        this.id = data['id'];
                        this.modal = 1;
                        this.tituloModal = 'Otros documentos';
                        this.nombre = '';
                        this.tipoAccion=6;
                        break;
                    }
                    case 'ordenCompra':{
                        this.id = data['id'];
                        this.modal = 1;
                        this.tituloModal = 'Orden de compra';
                        this.tipoAccion=7;
                        break;
                    }

                    case 'cheque':{
                        this.id = data['id'];
                        this.modal = 1;
                        this.tituloModal = 'Solicitud de cheque';
                        this.tipoAccion=8;
                        break;
                    }
                    case 'observaciones':{
                        this.id = data['id'];
                        this.modal = 1;
                        this.tituloModal = 'Observaciones';
                        this.tipoAccion=9;
                        this.observacion = '';
                        this.listarObservacion(this.id)
                        break;
                    }
                }
            }       
        },
        
        
        mounted() {
            this.listarOrdenes(1);
        }
    }
</script>
<style>
    .form-control:disabled, .form-control[readonly] {
    background-color: rgba(0, 0, 0, 0.06);
    opacity: 1;
    font-size: 1rem;
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
    
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none; 
    margin: 0;  
    }
</style>
