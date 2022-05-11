<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <strong><a style="color:#FFFFFF;" href="/">Home</a></strong>
            </li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card scroll-box">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Edo. Cuenta-Rentas
                    <!--   Boton agregar    -->
                    <button @click="abrirModal('nuevo')"  type="button" class="btn btn-primary" v-if="listado > 1">
                        <i class="icon-plus"></i>&nbsp;Nuevo Deposito
                    </button>
                    <button type="button" @click="salir()" class="btn btn-success" v-if="listado > 1">
                        <i class="fa fa-mail-reply"></i>&nbsp;Regresar
                    </button>      
                </div>
                

                <!----------------- Listado Contratos ------------------------------>
                <!-- Div Card Body para listar -->
                <template v-if="listado == 1">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-10">
                                <div class="input-group">
                                    <select class="form-control col-md-5" v-model="b_proyecto" @change="selectEtapas(b_proyecto)">
                                        <option value="">Fraccionamiento</option>
                                        <option v-for="proyecto in $root.$data.proyectos" :key="proyecto.id"  :value="proyecto.id" v-text="proyecto.nombre"></option>
                                    </select>
                                    <select class="form-control col-md-4" v-model="b_etapa">
                                        <option value="">Etapa</option>
                                        <option v-for="etapa in arrayEtapas" :key="etapa.id"  :value="etapa.id" v-text="etapa.num_etapa"></option>
                                    </select>
                                </div>
                                <div class="input-group">
                                    <input type="text" v-model="b_direccion" @keyup.enter="listarContratos(1)" class="form-control" placeholder="Dirección"/>
                                </div>
                                <div class="input-group">
                                    <input type="text" v-model="b_cliente" @keyup.enter="listarContratos(1)" class="form-control" placeholder="Arrendatario"/>
                                </div>
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="b_status">
                                        <option value="2">Vigente</option>
                                        <option value="3">Finalizado</option>
                                        <option value="0">Cancelado</option>
                                    </select>
                                    <button type="submit" @click="listarContratos(1)" class="btn btn-primary">
                                        <i class="fa fa-search"></i> Buscar
                                    </button>
                                    <a  :href="'/rentas/indexEdoCtaExcel?b_proyecto=' + b_proyecto
                                        + '&b_etapa=' + b_etapa
                                        + '&b_direccion=' + b_direccion
                                        + '&b_status=' + b_status
                                        + '&b_cliente=' + b_cliente"  
                                    class="btn btn-success"><i class="fa fa-file-text"></i> Excel</a>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th># Contrato</th>
                                        <th>Cliente</th>
                                        <th>Proyecto</th>
                                        <th>Etapa</th>
                                        <th>Modelo</th>
                                        <th>Dirección</th>
                                        <th>Renta mensual</th>
                                        <th>IVA</th>
                                        <th>Pagos pendientes</th>
                                        <th>Ultimo mes pagado</th>
                                        <th>Termino de contrato</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="contrato in arrayContratos.data" :key="contrato.id">
                                        <td>
                                            <button v-if="contrato.facturar == 1"
                                                type="button" title="Ver datos fiscales" @click="vistaArchivo(contrato.id)" class="btn btn-primary btn-sm">
                                                <i class="icon-eye"></i>
                                            </button>
                                        </td>
                                        <td class="td2" v-text="contrato.id"></td>
                                        <td class="td2"  v-text="contrato.nombre_arrendatario.toUpperCase()"></td>
                                        <td class="td2" v-text="contrato.proyecto"></td>
                                        <td class="td2" v-text="contrato.etapa"></td>
                                        <td class="td2" v-text="contrato.modelo"></td>
                                        <td class="td2" v-if="contrato.interior == null" v-text="contrato.calle+' Num. ' + contrato.numero"></td>
                                        <td class="td2" v-else v-text="contrato.calle+' Num. ' + contrato.numero + '-'+ contrato.interior"></td>
                                        <td class="td2" v-text="'$' +formatNumber(contrato.monto_renta)"></td>
                                        <td class="td2" v-if="contrato.facturar == 1" v-text="'$' +formatNumber(contrato.monto_renta*.16)"></td>
                                        <td class="td2" v-if="contrato.facturar == 0" v-text="'N/A'"></td>
                                        <td v-if="contrato.num_pendientes > 0" class="td2"
                                            style="text-align:center"
                                        >
                                            <a v-on:dblclick="verPagares(contrato.id)" 
                                            title="Ver pagos pendientes"
                                            href="#" v-text="contrato.num_pendientes + ' de ' + contrato.num_meses"></a>
                                        </td>
                                        <td style="text-align:center" v-else class="td2" v-text="contrato.num_pendientes + ' de ' + contrato.num_meses"></td>
                                        <td v-if="contrato.ultimo.fecha != null" v-text="this.moment(contrato.ultimo.fecha).locale('es').format('MMM/YYYY')"></td>
                                        <td v-else></td>
                                        <td class="td2" v-text="this.moment(contrato.fecha_fin).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td class="td2">
                                            <span v-if="contrato.status == 0" class="badge badge-danger">Cancelado</span>
                                            <span v-if="contrato.status == 1" class="badge badge-warning">Pendiente</span>
                                            <span v-if="contrato.status == 2" class="badge badge-success">Vigente</span>
                                            <span v-if="contrato.status == 3" class="badge badge-primary">Finalizado</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <nav>
                           <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="arrayContratos.current_page > 5" @click="listarContratos(1)">
                                    <a class="page-link" href="#" >Inicio</a>
                                </li>
                                <li class="page-item" v-if="arrayContratos.current_page > 1"
                                    @click="listarContratos(arrayContratos.current_page-1)">
                                    <a class="page-link" href="#" >Ant</a>
                                </li>

                                <li class="page-item" v-if="arrayContratos.current_page-3 >= 1"
                                    @click="listarContratos(arrayContratos.current_page-3)">
                                    <a class="page-link" href="#" v-text="arrayContratos.current_page-3"></a>
                                </li>
                                <li class="page-item" v-if="arrayContratos.current_page-2 >= 1"
                                    @click="listarContratos(arrayContratos.current_page-2)">
                                    <a class="page-link" href="#" v-text="arrayContratos.current_page-2"></a>
                                </li>
                                <li class="page-item" v-if="arrayContratos.current_page-1 >= 1"
                                    @click="listarContratos(arrayContratos.current_page-1)">
                                    <a class="page-link" href="#" v-text="arrayContratos.current_page-1"></a>
                                </li>
                                <li class="page-item active" >
                                    <a class="page-link" href="#" v-text="arrayContratos.current_page"></a>
                                </li>
                                <li class="page-item" 
                                    v-if="arrayContratos.current_page+1 <= arrayContratos.last_page">
                                    <a class="page-link" href="#" @click="listarContratos(arrayContratos.current_page+1)" 
                                    v-text="arrayContratos.current_page+1"></a>
                                </li>
                                <li class="page-item" 
                                    v-if="arrayContratos.current_page+2 <= arrayContratos.last_page">
                                    <a class="page-link" href="#" @click="listarContratos(arrayContratos.current_page+2)"
                                     v-text="arrayContratos.current_page+2"></a>
                                </li>
                                <li class="page-item" 
                                    v-if="arrayContratos.current_page+3 <= arrayContratos.last_page">
                                    <a class="page-link" href="#" @click="listarContratos(arrayContratos.current_page+3)"
                                    v-text="arrayContratos.current_page+3"></a>
                                </li>

                                <li class="page-item" v-if="arrayContratos.current_page < arrayContratos.last_page"
                                    @click="listarContratos(arrayContratos.current_page+1)">
                                    <a class="page-link" href="#" >Sig</a>
                                </li>
                                <li class="page-item" v-if="arrayContratos.current_page < 5 && arrayContratos.last_page > 5" @click="listarContratos(arrayContratos.last_page)">
                                    <a class="page-link" href="#" >Ultimo</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </template>

                <!-- Div Card Body para listar los pagares y depositos -->
                <template v-if="listado == 2">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-10">
                                <div class="input-group">
                                    <input type="text" disabled class="col-md-2 form-control" placeholder="Cliente: "/>
                                    <label class="form-control" for="text-input">&nbsp; {{datosRenta.nombre_arrendatario}} </label>
                                </div>
                                <div class="input-group">
                                    <input type="text" disabled class="col-md-2 form-control" placeholder="Ubicación"/>
                                    <label class="form-control" for="text-input">&nbsp; {{datosRenta.calle}} #{{datosRenta.numero}}</label>
                                </div>
                                <div class="input-group">
                                    <input type="text" disabled v-model="datosRenta.proyecto" class="col-md-4 form-control" placeholder="Cliente"/>
                                    <input type="text" disabled v-model="datosRenta.etapa" class="col-md-4 form-control" placeholder="Cliente"/>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <br>
                                <div class="input-group">
                                    <h6 class="form-control" for="text-input">&nbsp; Total Depositado:</h6>
                                    <h6 class="form-control" for="text-input">&nbsp; $ {{formatNumber(dep_total = depTotal)}}</h6>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <ul class="nav nav2 nav-tabs" id="myTab1" role="tablist">
                            <li class="nav-item">
                                <a @click="tab = 1" class="nav-link" v-bind:class="{ 'text-info active': tab==1 }" v-text="'Pagares pendientes'"></a>
                            </li>
                            <li class="nav-item">
                                <a @click="tab = 2" class="nav-link" v-bind:class="{ 'text-info active': tab==2 }" v-text="'Depositos'"></a>
                            </li>
                        </ul>
                        <!-- Pagares pendientes del contrato -->
                        <div class="tab-content" id="myTab1Content">
                            <div class="tab-pane fade" v-bind:class="{ 'active show': tab==1 }" v-if="tab == 1">
                                <div class="table-responsive">
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Fecha de pago</th>
                                                <th>Monto</th>
                                                <th v-if="datosRenta.facturar == 1">IVA</th>
                                                <th></th>
                                                <th>Saldo</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="pagare in datosRenta.pagares" :key="pagare.id">
                                                <template v-if="pagare.status <= 1">
                                                    <td class="td2"  v-text="pagare.num_pago"></td>
                                                    <td class="td2" v-text="this.moment(pagare.fecha).locale('es').format('DD/MMM/YYYY')"></td>
                                                    <td class="td2" v-text="'$' +formatNumber(pagare.importe)"></td>
                                                    <td v-if="datosRenta.facturar == 1" v-text="'$' +formatNumber(pagare.iva)"></td>
                                                    <td></td>
                                                    <td class="td2" v-text="'$' +formatNumber(pagare.saldo)"></td>
                                                    <td class="td2">
                                                        <span v-if="pagare.status == 1" class="badge badge-warning">Pendiente</span>
                                                        <span v-if="pagare.status == 0" class="badge badge-danger">Vencido</span>
                                                    </td>
                                                </template>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- Depositos del contrato -->
                        <div class="tab-content" id="myTab2Content">
                            <div class="tab-pane fade" v-bind:class="{ 'active show': tab==2 }" v-if="tab == 2">
                                <div class="table-responsive">
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Fecha de deposito</th>
                                                <th>Monto</th>
                                                <th>Interes</th>
                                                <th>Monto total</th>
                                                <th># Recibo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="deposito in datosRenta.depositos" :key="deposito.id">
                                                <td class="td2">
                                                    <button
                                                        type="button" title="Actualizar" @click="abrirModal('actualizar',deposito)" class="btn btn-warning btn-sm">
                                                        <i class="icon-pencil"></i>
                                                    </button>
                                                    <button
                                                        type="button" title="Eliminar" @click="deleteDeposito(deposito.id)" class="btn btn-danger btn-sm">
                                                        <i class="icon-close"></i>
                                                    </button>
                                                </td>
                                                <td class="td2" v-text="this.moment(deposito.fecha_dep).locale('es').format('DD/MMM/YYYY')"></td>
                                                <td class="td2" v-text="'$' +formatNumber(deposito.monto_cap)"></td>
                                                <td class="td2" v-text="'$' +formatNumber(deposito.monto_int)"></td>
                                                <td class="td2" v-text="'$' +formatNumber(deposito.monto_int + deposito.monto_cap)"></td>
                                                <td class="td2" v-text="'# '+deposito.num_recibo"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>

        <!-- Inicio Modal -->
        <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal >=1}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="close" @click="modal=0" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body" v-if="modal == 1 || modal == 2">
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Fecha deposito</label>
                            <div class="col-md-6">
                                <input type="date" @change="calcularInteres()" v-model="deposito.fecha_dep" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Concepto</label>
                            <div class="col-md-6">
                                <input type="text" v-model="deposito.concepto" class="form-control" placeholder="Concepto">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Monto</label>
                            <div class="col-md-3">
                                <input type="text" pattern="\d*" v-on:keypress="isNumber($event)" v-model="deposito.monto_cap" maxlength="10" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <p v-text="'$'+formatNumber(deposito.monto_cap)"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">¿Interés?</label>
                            <div class="col-md-4">
                                <button type="button" v-if="deposito.interes == 1"
                                    class="btn btn-success"
                                    @click="deposito.interes=0, deposito.fecha_int = '', deposito.monto_int = 0"
                                >
                                    Si
                                </button>
                                <button type="button" v-if="deposito.interes == 0"
                                    class="btn btn-primary"
                                    @click="deposito.interes=1, deposito.fecha_int = '', deposito.monto_int = 0"
                                >
                                    No
                            </button>
                            </div>
                        </div>

                        <div class="form-group row" v-if="deposito.interes == 1">
                            <label class="col-md-3 form-control-label" for="text-input">Fecha interés</label>
                            <div class="col-md-6">
                                <input type="date" v-model="deposito.fecha_int" @change="calcularInteres()" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row" v-if="deposito.interes == 1">
                            <label class="col-md-3 form-control-label" for="text-input">Monto interés</label>
                            <div class="col-md-3">
                                <input type="text" pattern="\d*" v-on:keypress="isNumber($event)" v-model="deposito.monto_int" maxlength="10" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <p v-text="'$'+formatNumber(deposito.monto_int)"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Banco</label>
                            <div class="col-md-6">
                                <select class="form-control" v-model="deposito.banco">
                                    <option value="">Seleccione</option>
                                    <option v-for="banco in arrayBancos" :key="banco.id" :value="banco.num_cuenta + '-' + banco.banco" v-text="banco.num_cuenta + '-' + banco.banco"></option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Num. Recibo</label>
                            <div class="col-md-3">
                                <input type="text" v-model="deposito.num_recibo" class="form-control" placeholder="# Recibo">
                            </div>
                        </div>

                    </div>

                    <div class="modal-body" v-if="modal == 4">
                        <!-- DATOS FISCALES -->
                            <template> 
                                <div class="form-group row">
                                    <div class="col-md-4">
                                    </div>
                                    <h5 style="text-align:center;">DATOS FISCALES</h5>
                                </div>
                                

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Correo Electrónico</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="datosRenta.email_fisc" disabled class="form-control">
                                    </div>
                                    <label class="col-md-2 form-control-label" for="text-input">Teléfono</label>
                                    <div class="col-md-3">
                                        <input type="text" v-model="datosRenta.tel_fisc" disabled class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="datosRenta.nombre_fisc" disabled class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Dirección</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="datosRenta.direccion_fisc" disabled class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Colonia</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="datosRenta.col_fisc" disabled class="form-control">
                                    </div>

                                    <label class="col-md-1 form-control-label" for="text-input">CP</label>
                                    <div class="col-md-3">
                                        <input type="text" v-model="datosRenta.cp_fisc" disabled class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">RFC</label>
                                    <div class="col-md-3">
                                        <input disabled type="text" maxlength="13"  style="text-transform:uppercase"
                                        v-model="datosRenta.rfc_fisc" class="form-control" placeholder="RFC">
                                    </div>
                                    <label class="col-md-2 form-control-label" for="text-input">Uso del C.F.D.I.</label>
                                    <div class="col-md-5">
                                        <input disabled type="text" style="text-transform:uppercase"
                                        v-model="datosRenta.cfi_fisc" class="form-control" placeholder="C.F.D.I.">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Régimen Fiscal del cliente</label>
                                    <div class="col-md-6">
                                        <input disabled type="text" 
                                        v-model="datosRenta.regimen_fisc" class="form-control" placeholder="Régimen">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Banco</label>
                                    <div class="col-md-4">
                                        <input disabled type="text" 
                                        v-model="datosRenta.banco_fisc" class="form-control" placeholder="Banco">
                                    </div>
                                    <label class="col-md-2 form-control-label" for="text-input">No. Cuenta</label>
                                    <div class="col-md-4">
                                        <input disabled type="text" 
                                        v-model="datosRenta.num_cuenta_fisc" class="form-control" placeholder="# Cuenta">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Clabe</label>
                                    <div class="col-md-4">
                                        <input disabled type="text" 
                                        v-model="datosRenta.clabe_fisc" class="form-control" placeholder="Clabe">
                                    </div>
                                </div>

                                
                            </template>
                    </div>
                    <!-- Botones del modal -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="modal=0">Cerrar</button>
                        <button type="button" v-if="modal == 1" class="btn btn-primary" @click="storeDeposito()">Guardar</button>
                        <button type="button" v-if="modal == 2" class="btn btn-success" @click="updateDeposito()">Actualizar</button>
                        <template v-if="modal == 4 && datosRenta.archivo_contrato != null">
                            <button type="button" class="btn btn-success" @click="verArchivo(datosRenta.archivo_contrato)">Ver Contrato</button>
                        </template>
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
    props: {
        rolId: { type: String }
    },
    data() {
        return {
            arrayContratos : [],
            arrayBancos : [],
            datosRenta: {},
            deposito: {},
            arrayEtapas: [],
            listado: 1,
            b_cliente: '',
            b_proyecto: '',
            b_etapa: '',
            b_direccion: '',
            tituloModal: '',
            modal:0,
            tab : 1,
            tituloModal : '',
            dep_total : 0,
            b_status : 2
        };
    },
    computed: {
        depTotal: function(){
            var totalDep = 0.0;
            for(var i=0;i<this.datosRenta.depositos.length;i++){
                totalDep += parseFloat(this.datosRenta.depositos[i].monto_cap)
            }
            totalDep = Math.round(totalDep*100)/100;
            return totalDep;
        },
    },
    methods: {
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
        verArchivo(archivo){
            window.open('/files/rentas/contratos/'+archivo, '_blank')
        },
        vistaArchivo(id){
            this.modal = 4;
            this.tituloModal = 'Datos fiscales'
            this.getDatos(id);
            this.archivo = '';
        },
        abrirModal(opcion, data= []){
            let me = this;
            me.selectCuenta();
            switch(opcion){
                case 'nuevo':
                    me.tituloModal = 'Nuevo deposito';
                    me.limpiarDatosDep();
                    me.deposito.renta_id = this.datosRenta.id;
                    me.modal = 1;
                    break;

                case 'actualizar':
                    me.tituloModal = 'Actualizar deposito';
                    me.limpiarDatosDep();
                    me.deposito = data;
                    me.modal = 2;
                    break;
            }
        },
        /**Metodo para mostrar los registros */
        listarContratos(page){
            let me = this;
            me.arrayContratos = [];
            var url = '/rentas/indexEdoCta?page=' + page
                + '&b_proyecto=' + this.b_proyecto
                + '&b_etapa=' + this.b_etapa
                + '&b_direccion=' + this.b_direccion
                + '&b_status=' + this.b_status
                + '&b_cliente=' + this.b_cliente;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayContratos = respuesta;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        /**Metodo para mostrar los registros */
        verPagares(id){
            let me = this;
            me.getDatos(id);
            me.listado = 2;
            me.tab = 1;
        },
        //Select todas las etapas
        selectEtapas(buscar){
            let me = this;
            me.b_etapa='';
            
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
        limpiarDatosRenta(){
            this.datosRenta = {
                'fraccionamiento_id':'',
                'etapa_id': '',
                'lote_id': '',
                'tipo_arrendatario': 0,
                'nombre_arrendatario': '',
                'tel_arrendatario': '',
                'clv_lada_arr':52,
                //Moral arrendatario
                'representante_arrendatario': '',
                'dir_arrendatario': '',
                'cp_arrendatario': '',
                'col_arrendatario': '',
                'estado_arrendatario': '',
                'municipio_arrendatario': '',
                'rfc_arrendatario':'',
                //Aval (Fiador)
                'tipo_aval': 0,
                'nombre_aval': '',
                'tel_aval':'',
                'clv_lada_aval':52,
                //Moral aval
                'representante_aval': '',
                'dir_aval': '',
                'cp_aval': '',
                'col_aval': '',
                'estado_aval': '',
                'municipio_aval': '',
                //Testigo
                'nombre': '',
                //Datos contrato
                'monto_renta' : '',
                'status': '',
                'fecha_firma' : '',
                'fecha_ini' : '',
                'fecha_fin' : '',
                'num_meses' : 0,
                'modelo' : '',
                'pagares': [],
                'dep_garantia' : 0,
                'muebles' : 0,
                'servicios' : 0,
                'luz' : 0,
                'agua' : 0,
                'gas' : 0,
                'television' : 0,
                'telefonia' : 0,
                'adendum'  : 0
            };
        },
        limpiarDatosDep(){
            this.deposito = {
                'id' : '',
                'renta_id' : '',
                'monto_cap' : 0,
                'fecha_dep' : '',
                'monto_int' : 0,
                'num_recibo' : '',
                'banco' : '',
                'concepto' : '',
                'interes' : 0,
                'fecha_int' : '',
            }
        },
        calcularInteres(){
            if(this.deposito.interes == 1){
                var a = moment(this.deposito.fecha_int);
                var b = moment(this.deposito.fecha_dep);
                var diferencia = b.diff(a,'days');
                var montoReal = this.datosRenta.monto_renta;

                if(diferencia>0){
                    if(this.datosRenta.facturar == 1)
                        montoReal = montoReal + (montoReal*.16)
                    this.deposito.monto_int = ((montoReal * .05)/30) * diferencia;
                    this.deposito.monto_int = this.deposito.monto_int.toFixed(2);
                }
            }
        },
        storeDeposito(){
            let me = this;
            //Con axios se llama el metodo store de FraccionaminetoController
            axios.post('/rentas/storeDeposito',{
                'datosDeposito' : this.deposito,
            }).then(function (response){
                me.modal = 0;
                me.limpiarDatosDep();
                me.verPagares(me.datosRenta.id);
                //Se muestra mensaje Success
                swal({
                    position: 'top-end',
                    type: 'success',
                    title: 'Renta creada correctamente',
                    showConfirmButton: false,
                    timer: 1500
                    })
            }).catch(function (error){
                console.log(error);
            });
        },
        updateDeposito(){
            let me = this;
            //Con axios se llama el metodo update
            axios.put('/rentas/updateDeposito',{
                'datosDeposito' : this.deposito,
            }).then(function (response){
                me.modal = 0;
                me.limpiarDatosDep();
                me.verPagares(me.datosRenta.id);
                //Se muestra mensaje Success
                swal({
                    position: 'top-end',
                    type: 'success',
                    title: 'Renta creada correctamente',
                    showConfirmButton: false,
                    timer: 1500
                    })
            }).catch(function (error){
                console.log(error);
            });
        },
        deleteDeposito(id){
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
                axios.delete('/rentas/deleteDeposito', 
                    { params: {'id': id}}).then(function (response){
                        swal(
                            'Borrado!',
                            'Deposito borrado correctamente.',
                            'success'
                        )
                        me.verPagares(me.datosRenta.id);
                    }).catch(function (error){
                        console.log(error);
                    });
                }
            })
        },
        getDatos(id){
            let me = this;
            me.limpiarDatosRenta();
            var url = '/rentas/getDatosRenta?id='+id;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.datosRenta = respuesta;
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
        salir(){
            let me = this;
            me.limpiarDatosRenta();
            me.limpiarDatosDep();
            me.listado = 1;
            me.listarContratos();
        }
    },
    mounted() {
        this.$root.selectFraccionamientos();
        this.listarContratos(1);
    }
};
</script>
<style>
    .modal-content {
        width: 100% !important;
        position: absolute !important;
    }
    .modal-body {
        height: 400px;
        width: 100%;
        overflow-y: auto;
    }
    .modal-body2{
        height: 400px;
    }
    .mostrar {
        display: list-item !important;
        opacity: 1 !important;
        position: fixed !important;
        background-color: #3c29297a !important;
    }
    .div-error {
        display: flex;
        justify-content: center;
    }
    .text-error {
        color: red !important;
        font-weight: bold;
    }
    @media (min-width: 600px) {
        .btnagregar {
            margin-top: 2rem;
        }
        .table2 {
            margin: auto;
            border-collapse: collapse;
            overflow-x: auto;
            display: block;
            width: fit-content;
            max-width: 100%;
            box-shadow: 0 0 1px 1px rgba(0, 0, 0, 0.1);
        }

        .td2,
        .th2 {
            border: solid rgb(200, 200, 200) 1px;
            padding: 0.5rem;
        }

        .td2 {
            white-space: nowrap;
            border-bottom: none;
            color: rgb(20, 20, 20);
        }

        .td2:first-of-type,
        th:first-of-type {
            border-left: none;
        }

        .td2:last-of-type,
        th:last-of-type {
            border-right: none;
        }
    }
</style>
