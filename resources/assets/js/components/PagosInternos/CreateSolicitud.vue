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
                    <i class="fa fa-align-justify"></i> Solicitud de Pago
                    <Button v-if="vista == 0" :btnClass="'btn-success'" :icon="'icon-plus'" @click="vistaFormulario('nuevo')">
                        Nueva solicitud
                    </Button>
                    <Button :btnClass="'btn-light'" :icon="'icon-close'" @click="cerrarFormulario()" v-if="vista == 1">
                        Regresar
                    </Button>
                </div>
                <div class="card-body">
                    <template v-if="vista == 0">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input class="form-control col-md-2" type="text" disabled placeholder="Empresa:">
                                    <select class="form-control col-md-6" v-model="b_empresa">
                                        <option value="">Seleccione</option>
                                        <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input class="form-control col-md-2" type="text" disabled placeholder="Proveedor:">
                                    <input type="text" class="form-control col-md-6" @keyup.enter="indexSolicitudes(1)" v-model="b_proveedor" placeholder="Proveedor a buscar">
                                </div>
                            </div>
                            <div class="col-md-12" v-if="encargado == 1 || admin > 0">
                                <div class="input-group">
                                    <input class="form-control col-md-2" type="text" disabled placeholder="Tipo Pago:">
                                    <select class="form-control col-md-4" v-model="b_tipo_pago" @change="b_forma_pago = ''">
                                        <option value="">Tipo Pago</option>
                                        <option value="0">CF</option>
                                        <option value="1">Banco</option>
                                    </select>
                                    <select class="form-control col-md-4" v-if="b_tipo_pago == 1" v-model="b_forma_pago">
                                        <option value="">Forma de pago</option>
                                        <option value="0">Transferencia</option>
                                        <option value="1">Cheque</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12" v-if="admin > 0">
                                <div class="input-group">
                                    <input class="form-control col-md-2" type="text" disabled placeholder="Cuenta de salida:">
                                    <input type="text" class="form-control col-md-6" @keyup.enter="indexSolicitudes(1)" v-model="b_cuenta_pago" placeholder="# Cuenta de salida">
                                </div>
                            </div>
                            <div class="col-md-12" v-if="admin > 0 || encargado == 1">
                                <div class="input-group">
                                    <input class="form-control col-md-2" type="text" disabled placeholder="Solicitante:">
                                    <input type="text" class="form-control col-md-6" @keyup.enter="indexSolicitudes(1)" v-model="b_solicitante" placeholder="Solicitante a buscar">
                                </div>
                            </div>
                            <div class="col-md-12" v-if="encargado == 1 || admin > 0">
                                <div class="input-group">
                                    <input class="form-control col-md-2" type="text" disabled placeholder="Fecha de solicitud:">
                                    <input type="date" class="form-control col-md-4" @keyup.enter="indexSolicitudes(1)" v-model="b_fecha1">
                                    <input type="date" class="form-control col-md-4" @keyup.enter="indexSolicitudes(1)" v-model="b_fecha2">
                                </div>
                            </div>
                            <div class="col-md-12" v-if="usuario == 'shady' || usuario == 'jorge.diaz'">
                                <div class="input-group">
                                    <input class="form-control col-md-2" type="text" disabled placeholder="Devoluciones?:">
                                    <select class="form-control col-md-4" v-model="b_devolucion">
                                        <option value="">No</option>
                                        <option value="1">Si</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <Button :btnClass="'btn-primary'" :icon="'fa fa-search'" @click="indexSolicitudes(1)">
                                        Buscar
                                    </Button>&nbsp;
                                    <a class="btn btn-success" :href="'/sp/printExcel?b_proveedor=' + b_proveedor
                                        + '&b_empresa=' + b_empresa
                                        + '&b_solicitante=' + b_solicitante
                                        + '&b_vbgerente=' + b_vbgerente
                                        + '&b_vbdireccion=' + b_vbdireccion
                                        + '&b_fecha1=' + b_fecha1
                                        + '&b_fecha2=' + b_fecha2
                                        + '&b_rechazado=' + b_rechazado
                                        + '&b_tipo_pago=' + b_tipo_pago
                                        + '&b_forma_pago=' + b_forma_pago
                                        + '&b_cuenta_pago=' + b_cuenta_pago
                                        + '&b_devolucion=' + b_devolucion
                                        + '&b_status=' + b_status">
                                        <i class="fa fa-file-text"></i>&nbsp; Excel
                                    </a>&nbsp;

                                    <a v-if="usuario == 'jeremias' || usuario == 'shady'" class="btn btn-success" :href="'/sp/printExcelDetalles?b_proveedor=' + b_proveedor
                                        + '&b_empresa=' + b_empresa
                                        + '&b_solicitante=' + b_solicitante
                                        + '&b_vbgerente=' + b_vbgerente
                                        + '&b_vbdireccion=' + b_vbdireccion
                                        + '&b_fecha1=' + b_fecha1
                                        + '&b_fecha2=' + b_fecha2
                                        + '&b_rechazado=' + b_rechazado
                                        + '&b_tipo_pago=' + b_tipo_pago
                                        + '&b_forma_pago=' + b_forma_pago
                                        + '&b_cuenta_pago=' + b_cuenta_pago
                                        + '&b_status=' + b_status">
                                        <i class="fa fa-file-text"></i>&nbsp; Excel Detalles
                                    </a>&nbsp;
                                </div>
                            </div>
                        </div>

                        <ul class="nav nav-tabs" id="myTab1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link"
                                @click="b_status = '', b_vbgerente = '',  b_rechazado = 1, b_vbdireccion = '', solicCheck = [],
                                    indexSolicitudes(1)"
                                v-bind:class="{ 'btn-danger text-info': b_status === ''}"
                                role="tab">{{ (b_status === '') ? `Rechazados: ${arraySolic.total ? arraySolic.total : ''}` : 'Rechazados' }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"
                                @click="b_status = 0, b_vbgerente = 0,  b_rechazado = '', b_vbdireccion = '', solicCheck = [],
                                    indexSolicitudes(1)"
                                v-bind:class="{ 'text-primary active': b_status === 0}"
                                role="tab">{{ (b_status === 0) ? `Nuevos: ${arraySolic.total ? arraySolic.total : 0}` : 'Nuevos' }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"
                                    @click="b_status = 1, b_vbgerente = '',  b_rechazado = '', b_vbdireccion = '', solicCheck = [],
                                        indexSolicitudes(1)"
                                    v-bind:class="{ 'text-primary active': b_status === 1}"
                                    role="tab">{{ (b_status === 1) ? `En Proceso: ${arraySolic.total}` : 'En Proceso' }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"
                                @click="b_status = 2, b_vbgerente = '', b_vbdireccion = 0,  b_rechazado = '', solicCheck = [],
                                    indexSolicitudes(1)"
                                v-bind:class="{ 'text-primary active': b_status === 2}"
                                role="tab">{{ (b_status === 2) ? `Aprobadas: ${arraySolic.total}` : 'Aprobadas' }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"
                                @click="b_status = 3, b_vbgerente = '', b_rechazado = '',  b_vbdireccion = '', solicCheck = [],
                                    indexSolicitudes(1)"
                                v-bind:class="{ 'text-primary active': b_status === 3}"
                                role="tab">{{ (b_status === 3) ? `Por Pagar: ${arraySolic.total}` : 'Por Pagar' }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"
                                @click="b_status = 4, b_vbgerente = '',  b_rechazado = '', b_vbdireccion = '', solicCheck = [],
                                    indexSolicitudes(1)"
                                v-bind:class="{ 'text-primary active': b_status === 4}"
                                role="tab">{{ (b_status === 4) ? `Pagados: ${arraySolic.total ? arraySolic.total : ''}` : 'Pagados' }}</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="myTab1Content">
                            <!-- Listado por Solicitudes Rechazados -->
                            <div class="tab-pane fade"  v-bind:class="{ 'active show': b_status === '' }" v-if="b_status ===  ''">
                                <TableComponent :cabecera="[
                                    '',
                                    'Proveedor',
                                    'Solicitante',
                                    'Fecha solic',
                                    'Importe',
                                    'Tipo de pago',
                                    ' '
                                ]">
                                    <template v-slot:tbody>
                                        <tr v-for="solic in arraySolic.data" :key="solic.id" :class="solic.pagado ? 'table-success' : solic.extraordinario ? 'table-danger' : ''">
                                            <td class="td2">
                                                <Button :btnClass="'btn-warning'" :size="'btn-sm'" title="Editar"
                                                    @click="vistaFormulario('actualizar', solic)" :icon="'icon-pencil'"
                                                ></Button>
                                                <Button v-if="solic.vb_gerente == 0 ||
                                                        usuario == 'shady' ||
                                                        usuario == 'jorge.diaz' ||
                                                        usuario == 'dora.m' ||
                                                        usuario == 'uriel.al'"
                                                        :btnClass="'btn-danger'" :size="'btn-sm'" title="Eliminar"
                                                        :icon="'icon-trash'" @click="deleteSolic(solic.id)"
                                                ></Button>
                                            </td>
                                            <td class="td2">
                                                <template v-if="solic.const_fisc">
                                                    <a :href="solic.const_fisc" target="_blank" title="Ver constancia fiscal" class="btn btn-primary">{{solic.proveedor}}</a>
                                                </template>
                                                <template v-else>
                                                    {{solic.proveedor}}
                                                </template>
                                            </td>
                                            <td class="td2" v-text="solic.solicitante"></td>
                                            <td class="td2"
                                                v-text="this.moment(solic.created_at).locale('es').format('DD/MMM/YYYY')">
                                            </td>
                                            <td class="td2" v-text="'$'+$root.formatNumber(solic.importe)"></td>
                                            <td class="td2">
                                                {{ solic.tipo_pago == 0 ? 'C.F.' : 'Bancos' }}
                                            </td>
                                            <td>
                                                <Button :btnClass="'btn-light'" title="Ver Observaciones"
                                                    @click="verObs(solic)"
                                                >Observaciones
                                                </Button>
                                            </td>
                                        </tr>
                                    </template>
                                </TableComponent>
                            </div>
                            <!-- Listado por Nuevas solicitudes -->
                            <div class="tab-pane fade"  v-bind:class="{ 'active show': b_status===0 }" v-if="b_status === 0">
                                <ul class="nav nav-tabs" id="myTab2" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link"
                                        @click="b_vbgerente = 0, indexSolicitudes(1)" v-bind:class="{ 'text-primary active': b_vbgerente==0}"
                                        role="tab">{{ (b_vbgerente == 0) ? `Sin revisar: ${arraySolic.total ? arraySolic.total : 0}` : 'Sin revisar' }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                        @click="b_vbgerente = 1, indexSolicitudes(1)" v-bind:class="{ 'text-primary active': b_vbgerente==1}"
                                        role="tab">{{ (b_vbgerente == 1) ? `Revisadas: ${arraySolic.total}` : 'Revisadas' }}</a>
                                    </li>
                                </ul>

                                <div class="tab-content" id="myTab1Content">
                                    <!-- Pendientes por revisar -->
                                    <div class="tab-pane fade"  v-bind:class="{ 'active show': b_vbgerente==0 }" v-if="b_vbgerente == 0">
                                        <TableComponent :cabecera="[
                                            '',
                                            'Proveedor',
                                            'Solicitante',
                                            'Fecha solic',
                                            'Importe',
                                            'Tipo de pago',
                                            '  ',
                                            ' '
                                        ]">
                                            <template v-slot:tbody>
                                                <tr v-for="solic in arraySolic.data" :key="solic.id" :class="solic.pagado ? 'table-success' : solic.extraordinario ? 'table-danger' : ''">
                                                    <td class="td2">
                                                        <Button :btnClass="'btn-warning'" :size="'btn-sm'" title="Editar" v-if="solic.vb_gerente == 0"
                                                            @click="vistaFormulario('actualizar', solic)" :icon="'icon-pencil'"
                                                        ></Button>
                                                        <!-- <Button :btnClass="'btn-primary'" :size="'btn-sm'" title="Ver solicitud"
                                                                :icon="'icon-eye'" @click="vistaFormulario('ver', solic)"
                                                        ></Button> -->
                                                        <Button :btnClass="'btn-danger'" :size="'btn-sm'" title="Eliminar" v-if="solic.vb_gerente == 0"
                                                                :icon="'icon-trash'" @click="deleteSolic(solic.id)"
                                                        ></Button>
                                                        <a class="btn btn-scarlet"
                                                            target="_blank"
                                                            title="Imprimir solicitud"
                                                            :href="'/sp/printSolPago?id='+solic.id">
                                                            <i class="fa fa-file-pdf-o"></i>
                                                        </a>
                                                    </td>
                                                    <td class="td2">
                                                        <template v-if="solic.const_fisc">
                                                            <a :href="solic.const_fisc" target="_blank" title="Ver constancia fiscal" class="btn btn-primary">{{solic.proveedor}}</a>
                                                        </template>
                                                        <template v-else>
                                                            {{solic.proveedor}}
                                                        </template>
                                                    </td>
                                                    <td class="td2" v-text="solic.solicitante"></td>
                                                    <td class="td2"
                                                        v-text="this.moment(solic.created_at).locale('es').format('DD/MMM/YYYY')">
                                                    </td>
                                                    <td class="td2" v-text="'$'+$root.formatNumber(solic.importe)"></td>
                                                    <td class="td2">
                                                        {{ solic.tipo_pago == 0 ? 'C.F.' : 'Bancos' }}
                                                    </td>
                                                    <td class="td2">
                                                        Solicitud pendiente de revisar
                                                    </td>
                                                    <td class="text-center">
                                                        <Button :btnClass="'btn-light'" title="Ver Observaciones"
                                                            @click="verObs(solic)"
                                                        >Observaciones
                                                        </Button>
                                                        <Button :btnClass="'btn-success'" title="Indicar pago"
                                                            v-if="usuario == 'cp.martin' && solic.pagado == 0 || usuario == 'shady' && solic.pagado == 0"
                                                            @click="pagado(solic.id)"
                                                            :icon="'fa fa-money'"
                                                        ></Button>
                                                    </td>
                                                </tr>
                                            </template>
                                        </TableComponent>
                                    </div>
                                    <!-- Pendientes por autorizar -->
                                    <div class="tab-pane fade"  v-bind:class="{ 'active show': b_vbgerente==1 }" v-if="b_vbgerente == 1">
                                        <TableComponent :cabecera="[
                                            '',
                                            'Proveedor',
                                            'Solicitante',
                                            'Fecha solic',
                                            'Importe',
                                            'Tipo de pago',
                                            '  ',
                                            ' '
                                        ]">
                                            <template v-slot:tbody>
                                                <tr v-for="solic in arraySolic.data" :key="solic.id" :class="solic.pagado ? 'table-success' : solic.extraordinario ? 'table-danger' : ''">
                                                    <td class="td2">
                                                        <Button :btnClass="'btn-primary'" :size="'btn-sm'" title="Ver solicitud"
                                                            :icon="'icon-eye'" @click="vistaFormulario('ver', solic)"
                                                        ></Button>
                                                        <a class="btn btn-scarlet"
                                                            target="_blank"
                                                            title="Imprimir solicitud"
                                                            :href="'/sp/printSolPago?id='+solic.id">
                                                            <i class="fa fa-file-pdf-o"></i>
                                                        </a>
                                                    </td>
                                                    <td class="td2">
                                                        <template v-if="solic.const_fisc">
                                                            <a :href="solic.const_fisc" target="_blank" title="Ver constancia fiscal" class="btn btn-primary">{{solic.proveedor}}</a>
                                                        </template>
                                                        <template v-else>
                                                            {{solic.proveedor}}
                                                        </template>
                                                    </td>
                                                    <td class="td2" v-text="solic.solicitante"></td>
                                                    <td class="td2"
                                                        v-text="this.moment(solic.created_at).locale('es').format('DD/MMM/YYYY')">
                                                    </td>
                                                    <td class="td2" v-text="'$'+$root.formatNumber(solic.importe)"></td>
                                                    <td class="td2">
                                                        {{ solic.tipo_pago == 0 ? 'C.F.' : 'Bancos' }}
                                                    </td>
                                                    <td class="td2">
                                                        Solicitud pendiente de autorizar
                                                    </td>
                                                    <td class="text-center">
                                                        <Button :btnClass="'btn-light'" title="Ver Observaciones"
                                                            @click="verObs(solic)"
                                                        >Observaciones
                                                        </Button>
                                                        <Button :btnClass="'btn-success'" title="Indicar pago"
                                                            v-if="usuario == 'cp.martin' && solic.pagado == 0 || usuario == 'shady' && solic.pagado == 0"
                                                            @click="pagado(solic.id)"
                                                            :icon="'fa fa-money'"
                                                        ></Button>
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <th colspan="4">Total</th>
                                                    <th v-text="'$'+$root.formatNumber(total)"></th>
                                                </tr>
                                            </template>
                                        </TableComponent>
                                    </div>
                                </div>

                            </div>
                            <!-- Listado por En Proceso -->
                            <div class="tab-pane fade"  v-bind:class="{ 'active show': b_status===1 }" v-if="b_status === 1">
                                <TableComponent :cabecera="[
                                    '',
                                    'Proveedor',
                                    'Solicitante',
                                    'Fecha solic',
                                    'Importe',
                                    'Tipo de pago',
                                    ' '
                                ]">
                                    <template v-slot:tbody>
                                        <tr v-for="solic in arraySolic.data" :key="solic.id" :class="solic.pagado ? 'table-success' : solic.extraordinario ? 'table-danger' : ''">
                                            <td class="td2">
                                                <Button :btnClass="'btn-primary'" title="Ver solicitd" :size="'btn-sm'"
                                                    @click="vistaFormulario('ver', solic)" :icon="'icon-eye'"
                                                ></Button>
                                                <a class="btn btn-scarlet"
                                                    target="_blank"
                                                    title="Imprimir solicitud"
                                                    :href="'/sp/printSolPago?id='+solic.id">
                                                    <i class="fa fa-file-pdf-o"></i>
                                                </a>
                                            </td>
                                            <td class="td2">
                                                <template v-if="solic.const_fisc">
                                                    <a :href="solic.const_fisc" target="_blank" title="Ver constancia fiscal" class="btn btn-primary">{{solic.proveedor}}</a>
                                                </template>
                                                <template v-else>
                                                    {{solic.proveedor}}
                                                </template>
                                            </td>
                                            <td class="td2" v-text="solic.solicitante"></td>
                                            <td class="td2"
                                                v-text="this.moment(solic.created_at).locale('es').format('DD/MMM/YYYY')">
                                            </td>
                                            <td class="td2" v-text="'$'+$root.formatNumber(solic.importe)"></td>
                                            <td class="td2">
                                                <template v-if="solic.tipo_pago == 0">
                                                    C.F.
                                                </template>
                                                <template v-else>
                                                    <span class="badge" :class="[solic.forma_pago == 1 ? 'badge-danger' : 'badge-primary']">
                                                        {{ solic.forma_pago == 1 ? 'Cheques' : 'Transferencia' }}
                                                    </span>
                                                </template>
                                            </td>
                                            <td class="text-center">
                                                <Button :btnClass="'btn-light'" title="Ver Observaciones"
                                                    @click="verObs(solic)"
                                                >Observaciones
                                                </Button>
                                                <Button :btnClass="'btn-success'" title="Indicar pago"
                                                    v-if="usuario == 'cp.martin' && solic.pagado == 0 || usuario == 'shady' && solic.pagado == 0"
                                                    @click="pagado(solic.id)"
                                                    :icon="'fa fa-money'"
                                                ></Button>
                                            </td>
                                        </tr>
                                         <tr>
                                            <th colspan="4">Total</th>
                                            <th v-text="'$'+$root.formatNumber(total)"></th>
                                        </tr>
                                    </template>
                                </TableComponent>
                            </div>
                            <!-- Listado por Autorizar en Dirección -->
                            <div class="tab-pane fade"  v-bind:class="{ 'active show': b_status=== 2 }" v-if="b_status === 2">
                                <ul class="nav nav-tabs" id="myTab3" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link"
                                        @click="b_vbdireccion = 0, indexSolicitudes(1)" v-bind:class="{ 'text-primary active': b_vbdireccion==0}"
                                        role="tab">{{ (b_vbdireccion == 0) ? `Sin revisar: ${arraySolic.total ? arraySolic.total : 0}` : 'Sin revisar' }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                        @click="b_vbdireccion = 1, indexSolicitudes(1)" v-bind:class="{ 'text-primary active': b_vbdireccion==1}"
                                        role="tab">{{ (b_vbdireccion == 1) ? `Revisadas: ${arraySolic.total}` : 'Revisadas' }}</a>
                                    </li>
                                </ul>

                                <div class="tab-content" id="myTab1Content">
                                    <!-- Pendientes por revisar -->
                                    <div class="tab-pane fade"  v-bind:class="{ 'active show': b_vbgerente==0 }" v-if="b_vbgerente == 0">
                                        <TableComponent :cabecera="[
                                            '',
                                            'Proveedor',
                                            'Solicitante',
                                            'Fecha solic',
                                            'Importe',
                                            'Tipo de pago',
                                            '  ',
                                            ' '
                                        ]">
                                            <template v-slot:tbody>
                                                <tr v-for="solic in arraySolic.data" :key="solic.id" :class="solic.pagado ? 'table-success' : solic.extraordinario ? 'table-danger' : ''">
                                                    <td class="td2">
                                                        <Button :btnClass="'btn-warning'" :icon="'icon-check'" title="Indicar revision previa"
                                                            v-if="solic.rev_op == 0 && (admin == 1 || usuario == 'shady' || usuario == 'lomelin')"
                                                            @click="setRevOpc(solic.id)"
                                                        ></Button>

                                                        <Button :btnClass="'btn-primary'" :size="'btn-sm'" title="Ver solicitud"
                                                                :icon="'icon-eye'" @click="vistaFormulario('ver', solic)"
                                                        ></Button>

                                                        <a class="btn btn-scarlet"
                                                            target="_blank"
                                                            title="Imprimir solicitud"
                                                            :href="'/sp/printSolPago?id='+solic.id">
                                                            <i class="fa fa-file-pdf-o"></i>
                                                        </a>
                                                    </td>
                                                    <td class="td2">
                                                        <template v-if="solic.const_fisc">
                                                            <a :href="solic.const_fisc" target="_blank" title="Ver constancia fiscal" class="btn btn-primary">{{solic.proveedor}}</a>
                                                        </template>
                                                        <template v-else>
                                                            {{solic.proveedor}}
                                                        </template>
                                                    </td>
                                                    <td class="td2" v-text="solic.solicitante"></td>
                                                    <td class="td2"
                                                        v-text="this.moment(solic.created_at).locale('es').format('DD/MMM/YYYY')">
                                                    </td>
                                                    <td class="td2" v-text="'$'+$root.formatNumber(solic.importe)"></td>
                                                    <td class="td2">
                                                        <template v-if="solic.tipo_pago == 0">
                                                            C.F.
                                                        </template>
                                                        <template v-else>
                                                            <span class="badge" :class="[solic.forma_pago == 1 ? 'badge-danger' : 'badge-primary']">
                                                                {{ solic.forma_pago == 1 ? 'Cheques' : 'Transferencia' }}
                                                            </span>
                                                        </template>
                                                    </td>
                                                    <td class="td2">
                                                        <template v-if="encargado == 0 && !gerente">
                                                            Solicitud pendiente de revisar
                                                        </template>
                                                        <template v-else>
                                                            <Button :btnClass="'btn-primary'" v-if="solic.vb_gerente == 0"
                                                                title="Validar revisión de solicitud" :icon="'icon-check'"
                                                                @click="changeVbGerente(solic.id,1)"
                                                            >Revisar
                                                            </Button>
                                                        </template>
                                                    </td>
                                                    <td>
                                                        <Button :btnClass="'btn-light'" title="Ver Observaciones"
                                                            @click="verObs(solic)"
                                                        >Observaciones
                                                        </Button>
                                                        <Button :btnClass="'btn-success'" title="Indicar pago"
                                                            v-if="usuario == 'cp.martin' && solic.pagado == 0 || usuario == 'shady' && solic.pagado == 0"
                                                            @click="pagado(solic.id)"
                                                            :icon="'fa fa-money'"
                                                        ></Button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th colspan="4">Total</th>
                                                    <th v-text="'$'+$root.formatNumber(total)"></th>
                                                </tr>
                                            </template>
                                        </TableComponent>
                                    </div>
                                    <!-- Pendientes por autorizar -->
                                    <div class="tab-pane fade"  v-bind:class="{ 'active show': b_vbgerente==1 }" v-if="b_vbgerente == 1">
                                        <TableComponent :cabecera="[
                                            '',
                                            'Proveedor',
                                            'Solicitante',
                                            'Fecha solic',
                                            'Importe',
                                            'Tipo de pago',
                                            '  ',
                                            ' '
                                        ]">
                                            <template v-slot:tbody>
                                                <tr v-for="solic in arraySolic.data" :key="solic.id" :class="solic.pagado ? 'table-success' : solic.extraordinario ? 'table-danger' : ''">
                                                    <td class="td2">
                                                        <Button :btnClass="'btn-primary'" :size="'btn-sm'" title="Ver solicitud"
                                                            :icon="'icon-eye'" @click="vistaFormulario('ver', solic)"
                                                        ></Button>
                                                        <a class="btn btn-scarlet"
                                                            target="_blank"
                                                            title="Imprimir solicitud"
                                                            :href="'/sp/printSolPago?id='+solic.id">
                                                            <i class="fa fa-file-pdf-o"></i>
                                                        </a>
                                                    </td>
                                                    <td class="td2">
                                                        <template v-if="solic.const_fisc">
                                                            <a :href="solic.const_fisc" target="_blank" title="Ver constancia fiscal" class="btn btn-primary">{{solic.proveedor}}</a>
                                                        </template>
                                                        <template v-else>
                                                            {{solic.proveedor}}
                                                        </template>
                                                    </td>
                                                    <td class="td2" v-text="solic.solicitante"></td>
                                                    <td class="td2"
                                                        v-text="this.moment(solic.created_at).locale('es').format('DD/MMM/YYYY')">
                                                    </td>
                                                    <td class="td2" v-text="'$'+$root.formatNumber(solic.importe)"></td>
                                                     <td class="td2">
                                                        <template v-if="solic.tipo_pago == 0">
                                                            C.F.
                                                        </template>
                                                        <template v-else>
                                                            <span class="badge" :class="[solic.forma_pago == 1 ? 'badge-danger' : 'badge-primary']">
                                                                {{ solic.forma_pago == 1 ? 'Cheques' : 'Transferencia' }}
                                                            </span>
                                                        </template>
                                                    </td>
                                                    <td class="td2">
                                                        <template v-if="!gerente">
                                                            Solicitud pendiente de autorizar
                                                        </template>
                                                        <template v-else>
                                                            <Button :btnClass="'btn-scarlet'" v-if="solic.vb_gerente < 2 && gerente"
                                                                title="Autorizar solicitud" @click="changeVbGerente(solic.id,2)" :icon="'icon-check'"
                                                            >Autorizar Solicitud
                                                            </Button>
                                                        </template>

                                                    </td>
                                                    <td class="text-center">
                                                        <Button :btnClass="'btn-light'" title="Ver Observaciones"
                                                            @click="verObs(solic)"
                                                        >Observaciones
                                                        </Button>
                                                        <Button :btnClass="'btn-success'" title="Indicar pago"
                                                            v-if="usuario == 'cp.martin' && solic.pagado == 0 || usuario == 'shady' && solic.pagado == 0"
                                                            @click="pagado(solic.id)"
                                                            :icon="'fa fa-money'"
                                                        ></Button>
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <th colspan="4">Total</th>
                                                    <th v-text="'$'+$root.formatNumber(total)"></th>
                                                </tr>
                                            </template>
                                        </TableComponent>
                                    </div>
                                </div>

                            </div>
                            <!-- Listado por Pagar -->
                            <div class="tab-pane fade"  v-bind:class="{ 'active show': b_status === 3 }" v-if="b_status === 3">
                                <TableComponent>
                                    <template v-slot:thead>
                                        <tr v-if="admin === 3 || usuario == 'shady'">
                                            <th colspan="8" class="text-center" v-if="solicCheck.length>0">
                                                <Button :btnClass="'btn-scarlet'" :icon="'fa fa-credit-card-alt'"
                                                    @click="abrirModal('pagoMasa', solicCheck[0])"
                                                    title="Pagar Seleccion">Pagar solicitudes seleccionadas</Button>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th>Proveedor</th>
                                            <th>Solicitante</th>
                                            <th>Fecha solic</th>
                                            <th>Importe</th>
                                            <th>Tipo de pago</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </template>
                                    <template v-slot:tbody>
                                        <tr v-for="solic in arraySolic.data" :key="solic.id" :class="solic.pagado ? 'table-success' : solic.extraordinario ? 'table-danger' : ''">
                                            <td>
                                                <input type="checkbox" v-if="solic.tipo_pago == 1 && solic.forma_pago == 0"
                                                    :id="solic.id" :value="solic"
                                                    v-model="solicCheck" >
                                            </td>
                                            <td class="td2">
                                                <Button
                                                    title="Ver solicitud" :size="'btn-sm'"
                                                    @click="vistaFormulario('ver', solic)" :icon="'icon-eye'"
                                                ></Button>
                                                <Button v-if="solic.fecha_pago"
                                                    :btnClass="'btn-success'" :size="'btn-sm'" :title="'Ver Pago'"
                                                    @click="abrirModal('verPago',solic)" :icon="'fa fa-money'"
                                                ></Button>

                                                <a class="btn btn-scarlet"
                                                    target="_blank"
                                                    title="Imprimir solicitud"
                                                    :href="'/sp/printSolPago?id='+solic.id">
                                                    <i class="fa fa-file-pdf-o"></i>
                                                </a>
                                            </td>
                                            <td class="td2">
                                                <template v-if="solic.const_fisc">
                                                    <a :href="solic.const_fisc" target="_blank" title="Ver constancia fiscal" class="btn btn-primary">{{solic.proveedor}}</a>
                                                </template>
                                                <template v-else>
                                                    {{solic.proveedor}}
                                                </template>
                                            </td>
                                            <td class="td2" v-text="solic.solicitante"></td>
                                            <td class="td2"
                                                v-text="this.moment(solic.created_at).locale('es').format('DD/MMM/YYYY')">
                                            </td>
                                            <td class="td2" v-text="'$'+$root.formatNumber(solic.importe)"></td>
                                            <td class="td2">
                                                <template v-if="solic.tipo_pago == 0">
                                                    C.F.
                                                </template>
                                                <template v-else>
                                                    <span class="badge" :class="[solic.forma_pago == 1 ? 'badge-danger' : 'badge-primary']">
                                                        {{ solic.forma_pago == 1 ? 'Cheques' : 'Transferencia' }}
                                                    </span>
                                                </template>
                                            </td>
                                            <td class="td2">
                                                <Button v-if="solic.fecha_pago && !solic.comprobante_pago"
                                                    :btnClass="'btn-info'" :size="'btn-sm'" :title="'Cargar comprobante de Pago'"
                                                    @click="abrirModal('comprobante', solic)" :icon="'fa fa-upload'"
                                                ></Button>
                                            </td>
                                            <td>
                                                <Button :btnClass="'btn-light'" title="Ver Observaciones"
                                                    @click="verObs(solic)"
                                                >Observaciones
                                                </Button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="5">Total</th>
                                            <th v-text="'$'+$root.formatNumber(total)"></th>
                                        </tr>
                                    </template>
                                </TableComponent>
                            </div>
                            <!-- Listado Pagados -->
                            <div class="tab-pane fade"  v-bind:class="{ 'active show': b_status === 4 }" v-if="b_status === 4">
                                <TableComponent>
                                    <template v-slot:thead>
                                        <tr v-if="admin === 3 || usuario == 'shady'">
                                            <th colspan="9" class="text-center" v-if="solicCheck.length>0">
                                                <Button :btnClass="'btn-scarlet'" :icon="'fa fa-credit-card-alt'"
                                                    @click="abrirModal('comprobanteMasa')"
                                                    title="Pagar Seleccion">Cargar comprobante de pago para solicitudes seleccionadas</Button>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th>Proveedor</th>
                                            <th>Solicitante</th>
                                            <th>Fecha solic</th>
                                            <th>Importe</th>
                                            <th>Tipo de pago</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </template>
                                    <template v-slot:tbody>
                                        <tr v-for="solic in arraySolic.data" :key="solic.id" :class="solic.pagado ? 'table-success' : solic.extraordinario ? 'table-danger' : ''">
                                            <td>
                                                <input type="checkbox" v-if="solic.tipo_pago == 1 && solic.forma_pago == 0"
                                                    :id="solic.id" :value="solic.id"
                                                    :icon="'fa fa-upload'"
                                                    v-model="solicCheck" >
                                            </td>
                                            <td class="td2">
                                                <Button
                                                    title="Ver solicitud" :size="'btn-sm'"
                                                    @click="vistaFormulario('ver', solic)" :icon="'icon-eye'"
                                                ></Button>
                                                <Button v-if="solic.fecha_pago"
                                                    :btnClass="'btn-success'" :size="'btn-sm'" :title="'Ver Pago'"
                                                    @click="abrirModal('verPago',solic)" :icon="'fa fa-money'"
                                                ></Button>
                                                <a class="btn btn-scarlet"
                                                    target="_blank"
                                                    title="Imprimir solicitud"
                                                    :href="'/sp/printSolPago?id='+solic.id">
                                                    <i class="fa fa-file-pdf-o"></i>
                                                </a>
                                            </td>
                                            <td class="td2">
                                                <template v-if="solic.const_fisc">
                                                    <a :href="solic.const_fisc" target="_blank" title="Ver constancia fiscal" class="btn btn-primary">{{solic.proveedor}}</a>
                                                </template>
                                                <template v-else>
                                                    {{solic.proveedor}}
                                                </template>
                                            </td>
                                            <td class="td2" v-text="solic.solicitante"></td>
                                            <td class="td2"
                                                v-text="this.moment(solic.created_at).locale('es').format('DD/MMM/YYYY')">
                                            </td>
                                            <td class="td2" v-text="'$'+$root.formatNumber(solic.importe)"></td>
                                            <td class="td2">
                                                <template v-if="solic.tipo_pago == 0">
                                                    C.F.
                                                </template>
                                                <template v-else>
                                                    <span class="badge" :class="[solic.forma_pago == 1 ? 'badge-danger' : 'badge-primary']">
                                                        {{ solic.forma_pago == 1 ? 'Cheques' : 'Transferencia' }}
                                                    </span>
                                                </template>
                                            </td>
                                            <td class="td2">
                                                <Button v-if="solic.fecha_pago && !solic.comprobante_pago"
                                                    :btnClass="'btn-info'" :size="'btn-sm'" :title="'Cargar comprobante de Pago'"
                                                    @click="abrirModal('comprobante', solic)" :icon="'fa fa-upload'"
                                                ></Button>
                                                <Button v-if="solic.fecha_pago && solic.comprobante_pago"
                                                    :btnClass="'btn-scarlet'" :size="'btn-sm'" :title="'Ver comprobante de Pago'"
                                                    @click="abrirModal('comprobante', solic)" :icon="'fa fa-download'"
                                                ></Button>
                                            </td>
                                            <td>
                                                <Button :btnClass="'btn-light'" title="Ver Observaciones"
                                                    @click="verObs(solic)"
                                                >Observaciones
                                                </Button>
                                            </td>
                                            <td>
                                                <Button :btnClass="'btn-dark'" title="Ver detalle"
                                                    @click="abrirModal('detalles',solic)" :icon="'fa fa-database'"></Button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="5">Total</th>
                                            <th v-text="'$'+$root.formatNumber(total)"></th>
                                        </tr>
                                    </template>
                                </TableComponent>
                            </div>

                        </div>
                        <Nav :current="arraySolic.current_page ? arraySolic.current_page : 1"
                            :last="arraySolic.last_page ? arraySolic.last_page : 1"
                            @changePage="indexSolicitudes">
                        </Nav>
                    </template>
                    <template v-if="vista == 1">
                        <div id="accordion" role="tablist">
                            <!-- Cabecera de la solicitud -->
                            <div class="card mb-0">
                                <div class="card-header" id="headingOne" role="tab">
                                    <h5 class="mb-0">
                                        <a data-toggle="collapse" href="#collapseOne" aria-expanded="false"
                                            aria-controls="collapseOne" class="collapsed"
                                            >Solicitud
                                        </a>
                                    </h5>
                                </div>
                                <div class="collapse" id="collapseOne" role="tabpanel"
                                    aria-labelledby="headingOne" data-parent="#accordion">
                                    <div class="form-group row border border-primary" style="margin-right:0px; margin-left:0px;">
                                        <div class="col-md-12">
                                            <div class="form-group"><center><h3></h3></center></div>
                                        </div>
                                        <template v-if="tipoAccion > 1">
                                            <div class="col-md-5"></div>
                                            <div class="col-md-7">
                                                <template v-if="tipoAccion > 1">
                                                    <label for="">Fecha de elaboración </label>
                                                    <h5>{{solicitudData.created_at}}</h5>
                                                </template>
                                            </div>
                                        </template>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="">Empresa solicitante </label>
                                                <select class="form-control" v-model="solicitudData.empresa_solic" :disabled="tipoAccion == 3">
                                                    <option value="">Seleccione</option>
                                                    <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <template v-if="encargado || gerente || admin>0">
                                                <label for="">¿Solicitud Extraordinaria?</label>
                                                <select class="form-control" v-model="solicitudData.extraordinario" :disabled="tipoAccion == 3">
                                                    <option :value="0">No</option>
                                                    <option :value="1">SI</option>
                                                </select>
                                            </template>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Proveedor </label>
                                                <v-select v-if="tipoAccion == 1"
                                                    :on-search="selectProveedores"
                                                    label="proveedor"
                                                    :options="proveedoresForm"
                                                    placeholder="Buscar contratista..."
                                                    :onChange="getDatosProveedor"
                                                >
                                                </v-select>
                                                <input type="text" class="form-control" v-if="tipoAccion > 1"
                                                    v-model="solicitudData.proveedor" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label> Forma de pago </label>
                                            <select class="form-control" v-model="solicitudData.tipo_pago" :disabled="tipoAccion == 3 && admin < 3"
                                                @change="solicitudData.forma_pago = '', solicitudData.caja_chica = 0,
                                                solicitudData.convenio = '', solicitudData.referencia = ''">
                                                <option :value="0">C.F.</option>
                                                <option :value="1">Bancos</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group" v-if="solicitudData.tipo_pago == 1">
                                                <label for="">&nbsp;</label>
                                                <select class="form-control" v-model="solicitudData.forma_pago"
                                                    @change="solicitudData.caja_chica = 0"
                                                    :disabled="tipoAccion == 3 && admin < 3"
                                                >
                                                    <option value="">Metodo de pago</option>
                                                    <option :value="0">Transferencia</option>
                                                    <option :value="1">Cheque</option>
                                                </select>
                                            </div>
                                        </div>


                                        <template  v-if="solicitudData.tipo_pago == 1 && solicitudData.forma_pago === 0">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label for="">Covenio</label>
                                                    <input type="text" class="form-control"
                                                        v-model="solicitudData.convenio" :disabled="tipoAccion == 3">
                                                </div>
                                            </div>

                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label for="">Referencia</label>
                                                    <input type="text" class="form-control"
                                                        v-model="solicitudData.referencia" :disabled="tipoAccion == 3">
                                                </div>
                                            </div>
                                        </template>

                                        <div class="col-md-2">
                                            <div class="form-group" v-if="solicitudData.forma_pago == 1">
                                                <label for="">Caja chica?</label>
                                                <select class="form-control" v-model="solicitudData.caja_chica"
                                                    :disabled="tipoAccion == 3 && admin < 3"
                                                >
                                                    <option :value="0">No</option>
                                                    <option :value="1">Si</option>
                                                </select>
                                            </div>
                                        </div>

                                        <template v-if="tipoAccion == 2 && solicitudData.tipo_pago == 1 || tipoAccion == 3 && solicitudData.tipo_pago == 1">
                                            <div class="col-md-3">
                                                <label for="">Banco</label>
                                                <input type="text" class="form-control"
                                                    v-model="solicitudData.banco" disabled>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="">Num. Cuenta</label>
                                                <input type="text" class="form-control"
                                                    v-model="solicitudData.num_cuenta" disabled>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="">Clabe</label>
                                                <input type="text" class="form-control"
                                                    v-model="solicitudData.clabe" disabled>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="">&nbsp;</label>
                                                <button v-if="tipoAccion == 2 || tipoAccion == 3 && usuario == 'jorge.diaz' || tipoAccion == 3 && usuario == 'shady'"
                                                    class="btn btn-warning form-control"
                                                    @click="abrirModal('banco')"
                                                >Cambiar Cuenta</button>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="">Cuenta de salida: </label>
                                                <select class="form-control" v-model="solicitudData.cuenta_pago" :disabled="solicitudData.status > 1">
                                                    <option value="">Seleccione</option>
                                                    <option v-for="c in arrayCuentasPago" :key="c.id" :value="c.num_cuenta + '-' + c.banco" v-text="c.num_cuenta + '-' + c.banco"></option>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                            </div>

                                        </template>

                                        <div class="col-md-3" v-show="tipoAccion != 1">
                                            <label> Importe de la solicitud</label>
                                            <h6 class="form-control">${{$root.formatNumber(solicitudData.importe = sumaDet)}}</h6>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- Detalles -->
                            <div class="card mb-0"
                                v-show="solicitudData.proveedor_id != '' && solicitudData.empresa_solic != ''
                                    && (solicitudData.tipo_pago === 0
                                    || solicitudData.tipo_pago === 1 && (solicitudData.forma_pago === 0 || solicitudData.forma_pago === 1))">
                                <div class="card-header" id="headingTwo" role="tab">
                                    <h5 class="mb-0">
                                        <a data-toggle="collapse" href="#collapseTwo" aria-expanded="false"
                                            aria-controls="collapseTwo" class="collapsed"
                                            >Detalle
                                        </a>
                                    </h5>
                                </div>
                                <div class="collapse" id="collapseTwo" role="tabpanel"
                                    aria-labelledby="headingTwo" data-parent="#accordion">

                                    <div class="form-group row border border-primary" style="margin-right:0px; margin-left:0px; padding-bottom: 10px;" >
                                        <div class="col-md-12">
                                            <div class="form-group"><center><h3></h3></center></div>
                                        </div>


                                        <template v-if="arrayPendientes.length && tipoAccion == 1">
                                            <div class="col-md-12">
                                                <center>
                                                    <h6 style="color:#"> Cargos pendientes por pagar. </h6>
                                                </center>
                                            </div>

                                            <div class="col-md-12">
                                                <TableComponent :cabecera="[
                                                    '','Obra', ' ', 'Cargo', 'Subconcepto','Obs.', 'Saldo pendiente',
                                                ]">
                                                    <template v-slot:tbody>
                                                        <tr v-for="det in arrayPendientes"
                                                            :key="det.id">
                                                            <td>
                                                                <button class="btn btn-primary" title="Agregar"
                                                                    @click="añadirPendiente(det)"
                                                                >
                                                                    <i class="icon-plus"></i>
                                                                </button>
                                                            </td>
                                                            <td class="td2">
                                                                {{det.obra}} {{det.sub_obra }}
                                                            </td>
                                                            <td class="td2">
                                                                {{ det.contrato_id ? 'Contrato: ' + det.contrato_id +'. ' : ''}}
                                                                {{ det.lote_id ?
                                                                    det.sublote ? 'Lote: ' + det.num_lote + ' ' + det.sublote
                                                                    : 'Lote: ' + det.num_lote : ''
                                                                }}
                                                                </td>
                                                            <td class="td2">{{det.cargo}}</td>
                                                            <td>{{det.concepto}}</td>
                                                            <td>{{det.observacion}}</td>
                                                            <td class="td2">
                                                                ${{$root.formatNumber(det.saldo)}}
                                                            </td>
                                                        </tr>
                                                    </template>
                                                </TableComponent>
                                            </div>
                                        </template>

                                         <div class="col-md-12">
                                            <hr>
                                            <center>
                                                <h6 style="color:#"> Nuevo Detalle </h6>
                                            </center>
                                        </div>

                                        <template v-if="tipoAccion < 3">
                                            <div class="col-md-4">
                                                <label for="">Obra <span style="color:red;" v-show="datosDetalle.obra == ''">(*)</span></label>
                                                <select class="form-control" v-model="datosDetalle.obra" @change="selectEtapa(datosDetalle.obra)">
                                                    <option value="">Seleccione</option>
                                                    <option value="OFICINA">OFICINA</option>
                                                    <option value="NUEVOS PROYECTOS">NUEVOS PROYECTOS</option>
                                                    <option v-for="proyecto in $root.$data.proyectos" :key="proyecto.id" :value="proyecto.nombre" v-text="proyecto.nombre"></option>
                                                </select>
                                            </div>
                                            <div class="col-md-3" v-if="(datosDetalle.obra != 'OFICINA' && datosDetalle.obra != 'NUEVOS PROYECTOS') && datosDetalle.obra != ''">
                                                <label for="">&nbsp;</label>
                                                <select class="form-control"
                                                    v-model="datosDetalle.sub_obra"
                                                    @change="$root.getManzanas(datosDetalle.obra, datosDetalle.sub_obra),
                                                        datosDetalle.manzana = '',
                                                        datosDetalle.lote_id = ''"
                                                    >
                                                    <option value="">Etapa</option>
                                                    <option v-for="etapa in arrayEtapas" :key="etapa.id" :value="etapa.num_etapa" v-text="etapa.num_etapa"></option>
                                                </select>
                                            </div>
                                            <template v-if="(datosDetalle.obra != 'OFICINA' && datosDetalle.obra != 'NUEVOS PROYECTOS') && datosDetalle.obra != ''">
                                                <div class="col-md-3">
                                                    <label for="">&nbsp;</label>
                                                    <select class="form-control" v-model="datosDetalle.manzana"
                                                        @change="$root.searchLotes(datosDetalle.obra, datosDetalle.sub_obra, datosDetalle.manzana),
                                                        datosDetalle.lote_id = '',
                                                        datosDetalle.num_lote = '', datosDetalle.sublote = ''"
                                                    >
                                                        <option value="">Manzana</option>
                                                        <option v-for="m in $root.$data.manzanas" :key="m.manzana" :value="m.manzana" v-text="m.manzana"></option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <template v-if="datosDetalle.manzana != ''">
                                                        <label for="">Lotes</label>
                                                        <select class="form-control" v-model="datosDetalle.lote_id" @change="searchContrato(datosDetalle.lote_id), setLote(datosDetalle.lote_id)">
                                                            <option value="">Lote</option>
                                                            <option v-for="l in $root.$data.lotes" :key="l.id" :value="l.id" v-text="l.sublote ? `${l.num_lote} ${l.sublote}` : l.num_lote"></option>
                                                        </select>
                                                    </template>
                                                </div>
                                                <div class="col-md-6">
                                                    <template v-if="contratoVenta.length">
                                                        <label for="">¿Añadir a contrato?</label>
                                                        <select class="form-control" v-model="datosDetalle.contrato_id">
                                                            <option value="">No</option>
                                                            <option :value="contratoVenta[0].id">{{`${contratoVenta[0].id}. ${contratoVenta[0].nombre} ${contratoVenta[0].apellidos}`}}</option>
                                                        </select>
                                                    </template>
                                                </div>
                                            </template>
                                            <div v-else class="col-md-8"></div>

                                            <div class="col-md-6">
                                                <label for="">Cargo <span style="color:red;" v-show="datosDetalle.cargo == ''">(*)</span></label>
                                                <select class="form-control" v-model="datosDetalle.cargo" @change="getConceptos(datosDetalle.cargo), datosDetalle.concepto = ''">
                                                    <option value="">Seleccione</option>
                                                    <option v-for="cargo in arrayCargos" :key="cargo.cargo" :value="cargo.cargo" v-text="cargo.cargo"></option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">Concepto <span style="color:red;" v-show="datosDetalle.concepto == ''">(*)</span></label>
                                                <select class="form-control" v-model="datosDetalle.concepto">
                                                    <option value="">Seleccione</option>
                                                    <option v-for="concepto in arrayConceptos" :key="concepto.id" :value="concepto.concepto" v-text="concepto.concepto"></option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="">Tipo de Mov.</label>
                                                <select class="form-control" v-model="datosDetalle.tipo_mov">
                                                    <option :value="0">Anticipo</option>
                                                    <option :value="1">Liquidación</option>
                                                    <option :value="2">Pago Cta</option>
                                                </select>
                                            </div>
                                            <div class="col-md-5">
                                                <label for="">Observación</label>
                                                <textarea rows="3" class="form-control" v-model="datosDetalle.observacion"></textarea>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group" v-if="solicitudData.caja_chica == 1">
                                                    <label for="">Proveedor </label>
                                                    <v-select
                                                        :on-search="getProveedores"
                                                        label="proveedor"
                                                        :options="arrayProveedores"
                                                        placeholder="Buscar contratista..."
                                                        :onChange="getDatosProveedorDet"
                                                    >
                                                    </v-select>
                                                </div>
                                            </div>
                                            <template v-if="solicitudData.caja_chica == 1">
                                                <div class="col-md-3">
                                                    <label for="">Fecha de factura <span style="color:red;" v-show="datosDetalle.fecha_factura == ''">(*)</span></label>
                                                    <input class="form-control" type="date" v-model="datosDetalle.fecha_factura">
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="">Folio fiscal <span style="color:red;" v-show="datosDetalle.folio_fisc == ''">(*)</span></label>
                                                    <input class="form-control" type="text" v-model="datosDetalle.folio_fisc" maxlength="5">
                                                </div>

                                                <div class="col-md-6"></div>
                                            </template>
                                            <div class="col-md-3">
                                                <label for="">Importe a contratar <span style="color:red;" v-show="datosDetalle.total <= 0">(*)</span></label>
                                                <input class="form-control" pattern="\d*" maxlength="10" v-on:keypress="$root.isNumber($event)"
                                                    :disabled="datosDetalle.pendiente_id"
                                                    type="text" v-model="datosDetalle.total" @change="datosDetalle.tipo_mov == 1 ? datosDetalle.pago = datosDetalle.total : datosDetalle.pago = 0">
                                            </div>
                                            <div class="col-md-2" v-if="datosDetalle.total > 0">
                                                <label for="">&nbsp;</label>
                                                <label class="form-control">${{$root.formatNumber(datosDetalle.total)}}</label>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="">Este pago <span style="color:red;" v-show="datosDetalle.pago <= 0">(*)</span></label>
                                                <input class="form-control" pattern="\d*" maxlength="10" v-on:keypress="$root.isNumber($event)"
                                                    @change="verificarMonto()"
                                                    type="text" v-model="datosDetalle.pago">
                                            </div>
                                            <div class="col-md-2" v-if="datosDetalle.pago > 0">
                                                <label for="">&nbsp;</label>
                                                <label class="form-control">${{$root.formatNumber(datosDetalle.pago)}}</label>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="">&nbsp;</label>
                                                <button class="btn btn-success form-control" @click="addDetalle()" title="Añadir"><i class="icon-plus"></i></button>
                                            </div>
                                        </template>

                                        <hr>

                                        <div class="col-md-12">
                                            <div class="form-group"><center><h3></h3></center></div>
                                        </div>

                                         <div class="col-md-12">
                                            <center>
                                                <h5 style="color:#"> Detalle de la solicitud </h5>
                                            </center>
                                        </div>

                                        <div class="col-md-12">
                                            <center>
                                                <TableComponent :cabecera="[
                                                    '','Obra', '   ', ' ', 'Cargo', 'Subconcepto','Obs.',
                                                    'Tipo Mov.', 'Importe total', 'Este pago', 'Saldo',
                                                    `${solicitudData.caja_chica == 1 ? 'Fecha factura' : '    '}`,
                                                    `${solicitudData.caja_chica == 1 ? 'Folio fiscal' : '     '}`
                                                ]">
                                                    <template v-slot:tbody>
                                                        <tr v-for="det in solicitudData.detalle"
                                                            :key="det.id+det.obra+det.sub_obra+det.cargo+det.concepto+det.pago">
                                                            <td>
                                                                <button class="btn btn-danger" title="Eliminar" v-if="tipoAccion != 3"
                                                                    @click="removeDetalle(det)"
                                                                >
                                                                    <i class="icon-trash"></i>
                                                                </button>
                                                            </td>
                                                            <td class="td2">
                                                                {{det.obra}} {{det.sub_obra }}
                                                            </td>
                                                            <td class="td2">
                                                                <template v-if="det.const_fisc">
                                                                    <a :href="det.const_fisc" target="_blank" title="Ver constancia fiscal" class="btn btn-primary">{{det.proveedor}}</a>
                                                                </template>
                                                                <template v-else>
                                                                    {{ det.proveedor ? det.proveedor : '' }}
                                                                </template>
                                                            </td>
                                                            <td class="td2">
                                                                {{ det.contrato_id ? 'Contrato: ' + det.contrato_id +'. ' : ''}}
                                                                {{ det.lote_id ?
                                                                    det.sublote ? 'Mnz: ' + det.manzana + ' Lote: ' + det.num_lote + ' ' + det.sublote
                                                                    : 'Mnz: ' + det.manzana + ' Lote: ' + det.num_lote : ''
                                                                }}
                                                            </td>
                                                            <template v-if="tipoAccion == 2">
                                                                <td class="td2">
                                                                    <select class="form-control" v-model="det.cargo" @change="getConceptos(det.cargo), det.concepto = ''">
                                                                        <option></option>
                                                                        <option v-for="cargo in arrayCargos" :key="cargo.cargo" :value="cargo.cargo" v-text="cargo.cargo"></option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <select class="form-control" v-model="det.concepto">
                                                                        <option v-if="det.concepto!=''" :value="det.concepto" v-text="det.concepto"></option>
                                                                        <option v-for="concepto in arrayConceptos" :key="concepto.id" :value="concepto.concepto" v-text="concepto.concepto"></option>
                                                                    </select>
                                                                </td>
                                                                <td class="td2">
                                                                    <textarea v-model="det.observacion" cols="30" rows="3"></textarea>
                                                                </td>
                                                            </template>
                                                            <template v-else>
                                                                <td class="td2">{{det.cargo}}</td>
                                                                <td>{{det.concepto}}</td>
                                                                <td>{{det.observacion}}</td>
                                                            </template>
                                                            <td class="td2">
                                                                {{
                                                                    (det.tipo_mov === 0) ? 'Anticipo'
                                                                    : (det.tipo_mov === 1) ? 'Liquidación'
                                                                    : 'Pago Cta'
                                                                }}
                                                            </td>
                                                            <td class="td2">
                                                                ${{$root.formatNumber(det.total)}}
                                                            </td>
                                                            <td class="td2">
                                                                <strong>${{$root.formatNumber(det.pago)}}</strong>
                                                            </td>
                                                            <td class="td2">
                                                                ${{$root.formatNumber(det.total - det.pago)}}
                                                            </td>
                                                            <td>
                                                                {{det.fecha_factura}}
                                                            </td>
                                                            <td>{{det.folio_fisc}}</td>
                                                            <td v-if="det.pendiente_id">
                                                                <Button :btnClass="'btn-success'" :icon="'fa fa-money'" @click="abrirModal('edoCuenta', det)" v-if="vista == 1">
                                                                    Edo. Cuenta
                                                                </Button>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="9"></td>
                                                            <th>$ {{$root.formatNumber(solicitudData.saldo = sumaDet)}}</th>
                                                        </tr>
                                                    </template>
                                                </TableComponent>
                                            </center>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- Documentos anexos -->
                            <div class="card mb-0" v-if="tipoAccion > 1">
                                <div class="card-header" id="headingThree" role="tab">
                                    <h5 class="mb-0">
                                        <a data-toggle="collapse" href="#collapseThree" aria-expanded="false"
                                            aria-controls="collapseThree" class="collapsed"
                                            >Archivos Adjuntos
                                        </a>
                                    </h5>
                                </div>
                                <div class="collapse" id="collapseThree" role="tabpanel"
                                    aria-labelledby="headingTwo" data-parent="#accordion">

                                    <div class="form-group row border border-primary" style="margin-right:0px; margin-left:0px; padding-bottom: 10px;" >
                                        <div class="col-md-12">
                                            <div class="form-group"><center><h3></h3></center></div>
                                        </div>

                                        <template v-if="tipoAccion >= 2">
                                            <div class="form-sub">
                                                <form method="post" @submit="formSubmitFile"
                                                    enctype="multipart/form-data"
                                                >
                                                    <div class="form-opc">
                                                        <div class="form-archivo">
                                                            <input ref="fileSelector"
                                                                v-show="false"
                                                                type="file"
                                                                v-on:change="onChangeFile"
                                                            />

                                                            <label class="label-button" @click="onSelectFile">
                                                                Elige el archivo a cargar
                                                                <i class="fa fa-upload"></i>
                                                            </label>
                                                            <div :class="(newArchivo.nom_archivo == 'Seleccione Archivo')
                                                                ? 'text-file-hide' : 'text-file'"
                                                                v-text="newArchivo.nom_archivo"
                                                            ></div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group row">
                                                                <label class="col-md-3 form-control-label" for="text-input">Tipo de Archivo</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" name="categoria" list="categoria" class="form-control" v-model="newArchivo.tipo" placeholder="Tipo de Archivo">
                                                                    <datalist id="categoria">
                                                                        <option value="FACTURA">FACTURA</option>
                                                                        <option value="COTIZACION">COTIZACIÓN</option>
                                                                        <option value="RECEPCION">RECEPCIÓN DE TRABAJO</option>
                                                                    </datalist>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="boton-modal" v-if="cargando==0 && newArchivo.tipo != ''">
                                                            <button v-show="newArchivo.nom_archivo !='Seleccione Archivo' && newArchivo.tipo != ''"
                                                                type="submit" class="btn btn-scarlet"
                                                            >
                                                                Subir Archivo
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </template>

                                        <div class="col-md-12">
                                            <div class="form-group"><center><h3></h3></center></div>
                                        </div>

                                        <div class="col-md-12" v-if="solicitudData.files.length">
                                            <center>
                                                <TableComponent :cabecera="[
                                                    '','Tipo','Archivo', ' '
                                                ]">
                                                    <template v-slot:tbody>
                                                        <tr v-for="p in solicitudData.files"
                                                            :key="p.id">
                                                            <td>
                                                                <button class="btn btn-danger" title="Eliminar" v-if="tipoAccion >= 2 && solicitudData.status < 3 || usuario == 'shady'"
                                                                    @click="deleteFile(p.id)"
                                                                >
                                                                    <i class="icon-trash"></i>
                                                                </button>
                                                            </td>
                                                            <td class="td2">{{p.tipo}}</td>
                                                            <td class="td2">{{p.file.name}}</td>
                                                            <td class="td2">
                                                                <a :href="p.file.public_url"
                                                                target="_blank" class="btn btn-success"
                                                                    title="Ver Archivo"
                                                                ><i class="fa fa-download"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </template>
                                                </TableComponent>
                                            </center>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- Observaciones -->
                            <div class="card mb-0" v-if="tipoAccion > 1">
                                <div class="card-header" id="headingFour" role="tab">
                                    <h5 class="mb-0">
                                        <a data-toggle="collapse" href="#collapseFour" aria-expanded="false"
                                            aria-controls="collapseFour" class="collapsed"
                                            >Observaciones
                                        </a>
                                    </h5>
                                </div>
                                <div class="collapse" id="collapseFour" role="tabpanel"
                                    aria-labelledby="headingTwo" data-parent="#accordion">

                                    <div class="form-group row border border-primary" style="margin-right:0px; margin-left:0px; padding-bottom: 10px;" >
                                        <div class="col-md-12">
                                            <div class="form-group"><center><h3></h3></center></div>
                                        </div>

                                        <div class="col-md-10">
                                            <label for="">Nuevo Comentario</label>
                                            <textarea rows="3" class="form-control" v-model="comentario"></textarea>
                                            <Button :btnClass="'btn-primary'" :icon="'icon-plus'" @click="storeObs(solicitudData.id)" title="Guardar comentario"></Button>
                                        </div>



                                        <div class="col-md-12">
                                            <center>
                                                <TableComponent :cabecera="[
                                                    'Usuario','Observación','Fecha'
                                                ]">
                                                    <template v-slot:tbody>
                                                        <tr v-for="ob in arrayObs"
                                                            :key="ob.id">
                                                            <td>{{ob.usuario}}</td>
                                                            <td>{{ob.comentario}}</td>
                                                            <td>{{ob.created_at}}</td>
                                                        </tr>
                                                    </template>
                                                </TableComponent>
                                            </center>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <button class="btn btn-success" @click="storeSolic()"
                                    v-if="solicitudData.importe > 0
                                        && tipoAccion == 1">
                                    Guardar Solicitud
                                </button>
                                <button class="btn btn-success" @click="updateSolicitud()"
                                    v-if="solicitudData.importe > 0
                                        && tipoAccion == 2">
                                    Guardar Cambios
                                </button>
                                <template v-if="solicitudData.status == 0 && solicitudData.rechazado == 0">
                                    <Button :btnClass="'btn-primary'" v-if="solicitudData.vb_gerente == 0 && encargado == 1"
                                        title="Validar revisión de solicitud" :icon="'icon-check'"
                                        @click="changeVbGerente(solicitudData.id,1)"
                                    >Revisar
                                    </Button>
                                    <Button :btnClass="'btn-primary'" v-if="solicitudData.vb_gerente < 2 && gerente"
                                        title="Autorizar solicitud" @click="changeVbGerente(solicitudData.id,2)" :icon="'icon-check'"
                                    >Autorizar Solicitud
                                    </Button>
                                    <Button v-if="gerente || encargado == 1"
                                        :icon="'fa fa-times'"
                                        :btnClass="'btn-danger'"
                                        @click="abrirModal('rechazo')">
                                        Rechazar solicitud
                                    </Button>
                                </template>
                                <template v-if="solicitudData.status == 1 && solicitudData.rechazado == 0">
                                    <Button v-if="admin == 3 || usuario == 'shady' || usuario == 'cp.martin'" :icon="'fa fa-times'" :btnClass="'btn-danger'" @click="abrirModal('rechazo')">
                                        Rechazar solicitud
                                    </Button>
                                    <Button v-if="admin == 3 || usuario == 'shady' || usuario == 'cp.martin'" :icon="'icon-check'" :btnClass="'btn-success'" @click="changeVbTesoreria(1)">
                                        Aprobar solicitud
                                    </Button>
                                </template>

                                <template v-if="solicitudData.status == 2 && solicitudData.rechazado == 0">
                                    <Button v-if="admin === 1 || admin === 2"
                                        :icon="'fa fa-times'"
                                        :btnClass="'btn-danger'"
                                        @click="abrirModal('rechazo')">
                                        Rechazar solicitud
                                    </Button>
                                    <Button v-if="solicitudData.vb_direccion == 0 && admin === 1
                                        || solicitudData.vb_direccion == 0 && usuario == 'shady'
                                        || solicitudData.vb_direccion == 0 && usuario == 'cp.martin' && solicitudData.extraordinario == 1"
                                        :icon="'icon-check'"
                                        :btnClass="'btn-success'"
                                        @click="changeVbDireccion(1)">
                                        Aprobar solicitud
                                    </Button>
                                    <Button v-if="solicitudData.vb_direccion == 1 && admin === 2
                                        || solicitudData.vb_direccion == 1 && usuario == 'shady'
                                        || solicitudData.vb_direccion == 1 && usuario == 'cp.martin' && solicitudData.extraordinario == 1"
                                        :icon="'icon-check'"
                                        :btnClass="'btn-success'"
                                        @click="autorizarDireccion(1)">
                                        Autorizar solicitud
                                    </Button>
                                </template>
                                <Button v-if="(admin === 3 && solicitudData.status == 3
                                        && (solicitudData.fecha_pago == null || solicitudData.fecha_pago == ''))
                                        || (usuario == 'shady' && solicitudData.status == 3
                                        && (solicitudData.fecha_pago == null || solicitudData.fecha_pago == ''))
                                        || (usuario == 'cp.martin' && solicitudData.status == 3
                                        && (solicitudData.fecha_pago == null || solicitudData.fecha_pago == ''))"
                                    :icon="'fa fa-money'"
                                    @click="abrirModal('pagar')">
                                    Generar pago
                                </Button>
                            </div>
                        </div>
                    </template>
                </div>

                <!--Inicio del modal observaciones-->
                <ModalComponent v-if="modal == 1"
                    :titulo="tituloModal"
                    @closeModal="cerrarModal()"
                >
                    <template v-slot:body>
                        <RowModal :label1="'Observacion'" :clsRow1="'col-md-6'" :clsRow2="'col-md-3'">
                            <textarea rows="3" cols="30" v-model="comentario" class="form-control" placeholder="Observacion"></textarea>
                            <template v-slot:input2>
                                <Button :btnClass="'btn-primary'" :icon="'icon-plus'" @click="storeObs(id)" title="Guardar observación"></Button>
                            </template>
                        </RowModal>

                        <TableComponent :cabecera="['Usuario','Observación','Fecha']">
                            <template v-slot:tbody>
                                <tr v-for="observacion in arrayObs" :key="observacion.id">
                                    <td v-text="observacion.usuario" ></td>
                                    <td v-text="observacion.comentario" ></td>
                                    <td v-text="observacion.created_at"></td>
                                </tr>
                            </template>
                        </TableComponent>
                    </template>
                </ModalComponent>

                <!--Inicio del modal cuentas-->
                <ModalComponent v-if="modal == 2"
                    :titulo="tituloModal"
                    @closeModal="cerrarModal()"
                >
                    <template v-slot:body>
                        <TableComponent :cabecera="['','Banco','Fecha']">
                            <template v-slot:tbody>
                                <tr v-for="cuenta in arrayCuentas" :key="cuenta.id">
                                    <td class="td2" style="width:10%">
                                        <button type="button" class="btn btn-success btn-sm"
                                            @click="changeCuenta(cuenta)" title="Seleccionar cuenta">
                                            <i class="fa fa-check"></i>
                                        </button>
                                    </td>
                                    <td class="td2" v-text="cuenta.banco" ></td>
                                    <td class="td2" v-text="cuenta.num_cuenta" ></td>
                                    <td class="td2" v-text="cuenta.clabe" ></td>
                                </tr>
                            </template>
                        </TableComponent>
                    </template>
                </ModalComponent>

                <!-- Modal para motivo de rechazo en solicitudes -->
                <ModalComponent :titulo="tituloModal"
                    v-if="modal == 3"
                    @closeModal="cerrarModal()"
                >
                    <template v-slot:body>
                        <RowModal :clsRow1="'col-md-7'" :label1="'Motivo'">
                            <textarea v-model="comentario" class="form-control" rows="3"></textarea>
                        </RowModal>
                    </template>
                    <template v-slot:buttons-footer>
                        <Button v-if="comentario != '' && solicitudData.status == 0"
                            :icon="'fa fa-times'" :btnClass="'btn-danger'"
                            @click="changeVbGerente(solicitudData.id,0)">
                            Rechazar
                        </Button>
                        <Button v-if="comentario != '' && solicitudData.status == 1"
                            :icon="'fa fa-times'" :btnClass="'btn-danger'"
                            @click="changeVbTesoreria(0)">
                            Rechazar
                        </Button>
                        <Button v-if="comentario != '' && solicitudData.status == 2"
                            :icon="'fa fa-times'" :btnClass="'btn-danger'"
                            @click="changeVbDireccion(0)">
                            Rechazar
                        </Button>
                    </template>
                </ModalComponent>

                <!-- Modal para generar el pago -->
                <ModalComponent :titulo="tituloModal"
                    v-if="modal == 4"
                    @closeModal="cerrarModal()"
                >
                    <template v-slot:body>
                        <RowModal :label1="'Fecha de pago'">
                            <input :disabled="solicitudData.accionPago==2" type="date" class="form-control" v-model="solicitudData.fecha_pago">
                        </RowModal>
                        <RowModal :label1="'Importe a pagar'">
                            <h6>${{$root.formatNumber(solicitudData.importe)}}</h6>
                        </RowModal>
                        <RowModal :label1="'Proveedor'" :clsRow1="'col-md-6'">
                            <input disabled type="text" class="form-control" v-model="solicitudData.proveedor">
                        </RowModal>
                        <RowModal :label1="'Cuenta'" :clsRow2="'col-md-4'" :label2="'Clabe'"
                            v-if="solicitudData.tipo_pago == 1 && solicitudData.forma_pago == 0">
                                <label class="form-control">{{solicitudData.banco}} - {{solicitudData.num_cuenta}}</label>
                            <template v-slot:input2>
                                <label class="form-control">{{solicitudData.clabe}}</label>
                            </template>
                        </RowModal>
                        <RowModal v-if="solicitudData.tipo_pago == 0"
                             :label1="'Folio'">
                            <input :disabled="solicitudData.accionPago==2" type="text" class="form-control" v-model="solicitudData.num_factura">
                        </RowModal>
                        <template v-if="solicitudData.tipo_pago == 1">
                            <RowModal :clsRow1="'col-md-6'" :label1="'Cuenta de salida'">
                                <select :disabled="solicitudData.accionPago==2" class="form-control" v-model="solicitudData.cuenta_pago">
                                    <option value="">Seleccione</option>
                                    <option v-for="c in arrayCuentasPago" :key="c.id" :value="c.num_cuenta + '-' + c.banco" v-text="c.num_cuenta + '-' + c.banco"></option>
                                </select>
                            </RowModal>
                            <RowModal v-if="solicitudData.forma_pago == 1"
                                :label1="'Número de Cheque:'" :clsRow2="'col-md-4'" :label2="'A Beneficiario?'">
                                <input :disabled="solicitudData.accionPago==2" type="text" class="form-control" v-model="solicitudData.num_factura">
                                <template v-slot:input2>
                                    <select :disabled="solicitudData.accionPago==2" class="form-control" v-model="solicitudData.beneficiario">
                                        <option value="0">No</option>
                                        <option value="1">Si</option>
                                    </select>
                                </template>
                            </RowModal>
                        </template>
                    </template>
                    <template v-slot:buttons-footer>
                        <Button
                            v-if="solicitudData.fecha_pago != '' && solicitudData.num_factura != '' && solicitudData.accionPago == 1"
                            :btnClass="'btn-success'" :icon="'fa fa-money'"
                            @click="generarPago()"
                        >Crear pago</Button>
                        <Button
                            v-if="solicitudData.fecha_pago != '' && solicitudData.num_factura != '' && solicitudData.accionPago == 3"
                            :btnClass="'btn-success'" :icon="'fa fa-money'"
                            @click="generarPagoMasa()"
                        >Crear pago</Button>
                        <a class="btn btn-scarlet"
                            v-if="solicitudData.accionPago == 2 && solicitudData.tipo_pago == 0"
                            target="_blank"
                            :href="'/sp/printComprobante?id='+solicitudData.id">Imprimir</a>
                    </template>
                </ModalComponent>

                <!-- Modal para subir comprobante de pago -->
                <ModalComponent :titulo="tituloModal"
                    v-if="modal >= 5"
                    @closeModal="cerrarModal()"
                >
                    <template v-slot:body>
                        <template v-if="modal == 5">
                            <form method="post" @submit="formSubmitFile" enctype="multipart/form-data">
                                <div class="form-group row">
                                    <input ref="fileSelector"
                                        v-show="false"
                                        type="file" accept="application/pdf"
                                        v-on:change="onChangeFile"
                                    />

                                    <label class="label-button" @click="onSelectFile">
                                        Elige el archivo a cargar
                                        <i class="fa fa-upload"></i>
                                    </label>
                                    <div :class="(newArchivo.nom_archivo == 'Seleccione Archivo')
                                        ? 'text-file-hide' : 'text-file'"
                                        v-text="newArchivo.nom_archivo"
                                    ></div>
                                </div>
                                <div class="form-group row">
                                    <div class="boton-modal" v-if="cargando==0">
                                        <button v-show="newArchivo.nom_archivo !='Seleccione Archivo' && newArchivo.tipo != ''"
                                            type="submit" class="btn btn-scarlet"
                                        >
                                            Subir Archivo
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </template>
                        <template v-if="modal == 6">
                            <form method="post" @submit="formSubmitMasa" enctype="multipart/form-data">
                                <div class="form-group row">
                                    <input ref="fileSelector"
                                        v-show="false"
                                        type="file" accept="application/pdf"
                                        v-on:change="onChangeFile"
                                    />

                                    <label class="label-button" @click="onSelectFile">
                                        Elige el archivo a cargar
                                        <i class="fa fa-upload"></i>
                                    </label>
                                    <div :class="(newArchivo.nom_archivo == 'Seleccione Archivo')
                                        ? 'text-file-hide' : 'text-file'"
                                        v-text="newArchivo.nom_archivo"
                                    ></div>
                                </div>
                                <div class="form-group row">
                                    <div class="boton-modal" v-if="cargando==0">
                                        <button v-show="newArchivo.nom_archivo !='Seleccione Archivo' && newArchivo.tipo != ''"
                                            type="submit" class="btn btn-scarlet"
                                        >
                                            Subir Archivo
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </template>
                    </template>
                    <template v-slot:buttons-footer>
                        <template v-if="modal == 5">
                            <a v-if="solicitudData.comprobante_pago" :href="solicitudData.comprobante_pago"
                                class="btn btn-primary"
                                title="Ver Comprobante de pago"
                                target="_blank"
                            >
                                <i class="icon-eye"></i>
                            </a>
                        </template>
                    </template>
                </ModalComponent>

                <!--Inicio del modal Detalles-->
                <ModalComponent v-if="modal == 7"
                    :titulo="tituloModal"
                    @closeModal="cerrarModal()"
                >
                    <template v-slot:body>
                        <TableComponent :cabecera="[
                            'Obra', '   ', ' ', 'Cargo', 'Subconcepto','Obs.', 'Tipo Mov.', 'Importe total', 'Este pago', 'Saldo', ''
                        ]">
                            <template v-slot:tbody>
                                <tr v-for="det in solicitudData.detalle"
                                    :key="det.id+det.obra+det.sub_obra+det.cargo+det.concepto+det.pago">
                                    <td class="td2">
                                        {{det.obra}} {{det.sub_obra }}
                                    </td>
                                    <td class="td2">
                                        <template v-if="det.const_fisc">
                                            <a :href="det.const_fisc" target="_blank" title="Ver constancia fiscal" class="btn btn-primary">{{det.proveedor}}</a>
                                        </template>
                                        <template v-else>
                                            {{ det.proveedor ? det.proveedor : '' }}
                                        </template>
                                    </td>
                                    <td class="td2">
                                        {{ det.contrato_id ? 'Contrato: ' + det.contrato_id +'. ' : ''}}
                                        {{ det.lote_id ?
                                            det.sublote ? 'Mnz: ' + det.manzana + ' Lote: ' + det.num_lote + ' ' + det.sublote
                                            : 'Mnz: ' + det.manzana + ' Lote: ' + det.num_lote : ''
                                        }}
                                    </td>
                                    <td class="td2">{{det.cargo}}</td>
                                    <td class="td2">{{det.concepto}}</td>
                                    <td class="td2">{{det.observacion}}</td>
                                    <td class="td2">
                                        {{
                                            (det.tipo_mov === 0) ? 'Anticipo'
                                            : (det.tipo_mov === 1) ? 'Liquidación'
                                            : 'Pago Cta'
                                        }}
                                    </td>
                                    <td class="td2">
                                        ${{$root.formatNumber(det.total)}}
                                    </td>
                                    <td class="td2">
                                        <strong>${{$root.formatNumber(det.pago)}}</strong>
                                    </td>
                                    <td class="td2">
                                        ${{$root.formatNumber(det.total - det.pago)}}
                                    </td>
                                    <td v-if="det.pendiente_id">
                                        <Button :btnClass="'btn-success'" :icon="'fa fa-money'" @click="abrirModal('edoCuenta', det)">
                                            Edo. Cuenta
                                        </Button>
                                    </td>
                                </tr>
                            </template>
                        </TableComponent>
                    </template>
                </ModalComponent>

                <ModalComponent v-if="modal == 8"
                    :titulo="tituloModal"
                    @closeModal="cerrarModal()"
                >
                    <template v-slot:body>
                        <RowModal :clsRow1="'col-md-7'" :label1="'Proveedor'">
                            <input v-model="solicitudData.proveedor" class="form-control" disabled/>
                        </RowModal>
                        <RowModal :clsRow1="'col-md-5'" :label1="'Importe Total:'">
                            <label>{{$root.formatNumber(arrayEdoCuenta[0].total)}}</label>
                        </RowModal>
                        <RowModal :clsRow1="'col-md-12'" :label1="''">
                            <TableComponent :cabecera="[
                                '','Cargo', 'Concepto', 'Detalle', 'Pago', 'Saldo'
                            ]">
                                <template v-slot:tbody>
                                    <tr v-for="det in arrayEdoCuenta" :key="det.id" :class="{ 'table-danger' : det.extraordinario }">
                                        <td class="td2">
                                            {{det.fecha_solic}}
                                        </td>
                                        <td class="td2">
                                           {{det.cargo}}
                                        </td>
                                        <td class="td2">
                                           {{det.concepto}}
                                        </td>
                                        <td class="td2">
                                           {{det.observacion}}
                                        </td>
                                        <td class="td2" v-text="'$'+$root.formatNumber(det.pago)"></td>
                                        <td class="td2" v-text="'$'+$root.formatNumber(det.saldo)"></td>

                                    </tr>
                                </template>
                            </TableComponent>
                        </RowModal>
                    </template>
                </ModalComponent>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
    </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
import ModalComponent from "../Componentes/ModalComponent.vue";
import TableComponent from "../Componentes/TableComponent.vue";
import Button from "../Componentes/ButtonComponent.vue";
import Nav from "../Componentes/NavComponent.vue";
import RowModal from "../Componentes/ComponentesModal/RowModalComponent.vue";
import vSelect from 'vue-select';

export default {
    components: {
        ModalComponent,
        TableComponent,
        Button, RowModal,Nav,
        vSelect
    },
    props: {
        encargado:{type: String},
        usuario:{type: String},
    },
    data() {
        return {
            arrayGerentes:[
                'eli_hdz', //Comercializacion 9
                'sajid.m', //Postventa 4
                'bd_raul', //Proyectos 3
                'lucy.hdz',//Presupuestos 5
                'cp.martin',//Administracion 7
                'ing_david',//Direccion 1
                'meza.marco60',//Contabilidad 6
                'guadalupe.ff',// Obra 2
                'uriel.al',
                'shady'
            ],
            solicCheck:[],
            arrayPendientes:[],
            contratoVenta:{},
            arraySolic: [],
            empresas: [],
            arrayProveedores : [],
            proveedoresForm : [],
            arrayProyectos : [],
            arrayEtapas : [],
            arrayCargos: [],
            arrayConceptos: [],
            arrayObs : [],
            arrayCuentas : [],
            arrayCuentasPago : [],

            arrayEdoCuenta : [],

            solicitudData:{},
            datosDetalle:{},

            b_proveedor : '',
            b_solicitante : '',
            b_status : 0,
            b_fecha1 : '',
            b_fecha2 : '',
            b_vbgerente : 0,
            b_vbdireccion : 0,
            b_rechazado: '',
            b_empresa : '',
            b_tipo_pago : '',
            b_forma_pago : '',
            b_cuenta_pago : '',
            b_devolucion : '',
            loading : false,
            id : '',
            comentario : '',

            modal: 0,
            tituloModal: "",
            newArchivo:{},
            proyectoSel:'',
            etapaSel:'',
            tipoAccion:1,
            cargando:0,

            vista:0,
            admin:0,
            total:0,
            gerente: null,

            archivo: '',
        };
    },
    computed: {
        sumaDet: function(){
            let me = this;
            let total = 0.0;
            me.solicitudData.detalle.forEach( e => total += Math.round(e.pago*100)/100 )
            return Math.round(total*100)/100;
        },
    },
    methods: {
        setLote(id){
            let me = this;
            me.datosDetalle.num_lote = '';
            me.datosDetalle.sublote = '';
            var url = '/lote/getDatosLote?lote_id=' + id;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.datosDetalle.num_lote = respuesta.num_lote;
                me.datosDetalle.sublote = respuesta.sublote;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        selectCuenta(empresa){
            let me = this;
            me.arrayCuentas=[];
            var url = '/select_cuenta?empresa='+empresa;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayCuentasPago = respuesta.cuentas;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        onChangeFile(e){
            this.newArchivo.file = e.target.files[0];
            this.newArchivo.nom_archivo = e.target.files[0].name;
        },
        changeVbGerente(id,estado){
            let me = this;

            let titulo = '¿Seguro de revisar esta solicitud?';
            if(estado == 2)
                titulo = '¿Seguro de autorizar esta solicitud?';

            if(estado == 0)
                titulo = '¿Seguro de rechazar esta solicitud?';

            swal({
                title: titulo,
                text: "Esta acción no se puede revertir!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si, continuar'
                }).then((result) => {
                if (result.value) {
                    axios.put(`/solic-pagos/changeVbGerente/${id}`,{
                        'id': id,
                        'estado' : estado,
                        'motivo' : me.comentario,
                    }).then(function (response){
                        me.indexSolicitudes(me.arraySolic.current_page); //se enlistan nuevamente los registros
                        me.cerrarFormulario();
                        me.cerrarModal();
                        //Se muestra mensaje Success
                        const toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                            });
                            toast({
                            type: 'success',
                            title: 'Solicitud actualizada'
                        })
                    }).catch(function (error){
                    });
                }
            })

        },
        autorizarDireccion(estado){
            let me = this;
            const titulo = '¿Seguro de autorizar esta solicitud?';

            swal({
                title: titulo,
                text: "Esta acción no se puede revertir!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si, continuar'
                }).then((result) => {
                if (result.value) {
                    axios.put(`/solic-pagos/autorizarDireccion/${me.solicitudData.id}`,{
                        'id': me.solicitudData.id,
                        'estado' : estado,
                    }).then(function (response){
                        me.cerrarFormulario();
                        me.indexSolicitudes(me.arraySolic.current_page); //se enlistan nuevamente los registros
                        //Se muestra mensaje Success
                        const toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                            });
                            toast({
                            type: 'success',
                            title: 'Solicitud actualizada'
                        })
                    }).catch(function (error){
                    });
                }
            })
        },
        pagado(id){
            let me = this;
            const titulo = '¿Seguro de indicar pago para esta solicitud?';

            swal({
                title: titulo,
                text: "Esta acción no se puede revertir!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si, continuar'
                }).then((result) => {
                if (result.value) {
                    axios.put(`/solic-pagos/pagado/${id}`,{
                        'id': id,
                    }).then(function (response){
                        me.cerrarFormulario();
                        me.indexSolicitudes(me.arraySolic.current_page); //se enlistan nuevamente los registros
                        //Se muestra mensaje Success
                        const toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                            });
                            toast({
                            type: 'success',
                            title: 'Solicitud actualizada'
                        })
                    }).catch(function (error){
                    });
                }
            })
        },
        changeVbTesoreria(estado){
            let me = this;

            let titulo = '¿Seguro de aprobar esta solicitud?';
            if(estado == 0)
                titulo = '¿Seguro de rechazar esta solicitud?';
            swal({
                title: titulo,
                text: "Esta acción no se puede revertir!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si, continuar'
                }).then((result) => {
                if (result.value) {
                    axios.put(`/solic-pagos/changeVbTesoreria/${me.solicitudData.id}`,{
                        'id': me.solicitudData.id,
                        'estado' : estado,
                        'motivo' : me.comentario,
                        'cuenta_pago' : me.solicitudData.cuenta_pago
                    }).then(function (response){
                        me.cerrarModal();
                        me.cerrarFormulario();
                        me.indexSolicitudes(me.arraySolic.current_page); //se enlistan nuevamente los registros
                        //Se muestra mensaje Success
                        const toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                            });
                            toast({
                            type: 'success',
                            title: 'Solicitud actualizada'
                        })
                    }).catch(function (error){
                    });
                }
            })
        },
        changeVbDireccion(estado){
            let me = this;

            let titulo = '¿Seguro de aprobar esta solicitud?';
            if(estado == 0)
                titulo = '¿Seguro de rechazar esta solicitud?';
            swal({
                title: titulo,
                text: "Esta acción no se puede revertir!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si, continuar'
                }).then((result) => {
                if (result.value) {
                    axios.put(`/solic-pagos/changeVbDireccion/${me.solicitudData.id}`,{
                        'id': me.solicitudData.id,
                        'estado' : estado,
                        'motivo' : me.comentario
                    }).then(function (response){
                        me.cerrarModal();
                        me.cerrarFormulario();
                        me.indexSolicitudes(me.arraySolic.current_page); //se enlistan nuevamente los registros
                        //Se muestra mensaje Success
                        const toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                            });
                            toast({
                            type: 'success',
                            title: 'Solicitud actualizada'
                        })
                    }).catch(function (error){
                    });
                }
            })
        },
        setRevOpc(id){
            let me = this;

            const titulo = '¿Seguro de marcar como revisada esta solicitud?';
            swal({
                title: titulo,
                text: "Esta acción no se puede revertir!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si, continuar'
                }).then((result) => {
                if (result.value) {
                    axios.put('/sp/setRevOpc/',{
                        'id': id,
                    }).then(function (response){
                        me.indexSolicitudes(me.arraySolic.current_page); //se enlistan nuevamente los registros
                        //Se muestra mensaje Success
                        const toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                            });
                            toast({
                            type: 'success',
                            title: 'Solicitud actualizada'
                        })
                    }).catch(function (error){
                    });
                }
            })
        },
        generarPagoMasa(){
            let me = this;
            const titulo = '¿Seguro de generar el pago para las solicitudes seleccionadas?';
            swal({
                title: titulo,
                text: "Esta acción no se puede revertir!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si, continuar'
                }).then((result) => {
                if (result.value) {
                    me.solicCheck.forEach(e => {
                        axios.put(`/solic-pagos/generarPago/${e.id}`,{
                            'id': e.id,
                            'fecha_pago' : me.solicitudData.fecha_pago,
                            'num_factura' : me.solicitudData.num_factura,
                            'cuenta_pago' : me.solicitudData.cuenta_pago,
                            'beneficiario' : me.solicitudData.beneficiario
                        }).then(function (response){
                            me.cerrarModal();
                            me.cerrarFormulario();
                            me.indexSolicitudes(me.arraySolic.current_page); //se enlistan nuevamente los registros
                            //Se muestra mensaje Success
                            const toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                                });
                                toast({
                                type: 'success',
                                title: 'Pago creado correctamente'
                            })
                        }).catch(function (error){
                        });
                    })
                }
            })
        },
        generarPago(){
            let me = this;
            const titulo = '¿Seguro de generar el pago para esta solicitud?';
            swal({
                title: titulo,
                text: "Esta acción no se puede revertir!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si, continuar'
                }).then((result) => {
                if (result.value) {
                    axios.put(`/solic-pagos/generarPago/${me.solicitudData.id}`,{
                        'id': me.solicitudData.id,
                        'fecha_pago' : me.solicitudData.fecha_pago,
                        'num_factura' : me.solicitudData.num_factura,
                        'cuenta_pago' : me.solicitudData.cuenta_pago,
                        'beneficiario' : me.solicitudData.beneficiario
                    }).then(function (response){
                        if(me.solicitudData.tipo_pago == 0)
                            window.open('/sp/printComprobante?id='+me.solicitudData.id, '_blank')
                        me.cerrarModal();
                        me.cerrarFormulario();
                        me.indexSolicitudes(me.arraySolic.current_page); //se enlistan nuevamente los registros
                        //Se muestra mensaje Success
                        const toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                            });
                            toast({
                            type: 'success',
                            title: 'Pago creado correctamente'
                        })
                    }).catch(function (error){
                    });
                }
            })
        },
        onSelectFile(){
            this.$refs.fileSelector.click()
        },
        //Busqueda de lotes por nombre de proyecto, nombre de etapa y manzana
        searchContrato(lote_id){
            let me = this;

            me.contratoVenta = {};
            me.datosDetalle.contrato_id = '';
            var url = '/contratos/searchContrato?lote_id=' + lote_id;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.contratoVenta = respuesta;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        formSubmitMasa(e) {
            e.preventDefault();
            let currentObj = this;
            this.cargando = 1;

            this.solicCheck.forEach(e => {
                let formData = new FormData();
                formData.append('file', this.newArchivo.file);
                formData.append('id', e);
                formData.append('tipo', this.newArchivo.tipo);
                let me = this;
                axios.post('/files-solic', formData)
                .then(function (response) {
                    me.cerrarModal();
                    me.cargando = 0;
                    me.indexSolicitudes(me.arraySolic.current_page);
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Archivo guardado correctamente',
                        showConfirmButton: false,
                        timer: 2000
                    })
                }).catch(function (error) {
                    currentObj.output = error;
                    me.cargando = 0;
                });
            })
        },
        formSubmitFile(e) {
            e.preventDefault();
            let currentObj = this;
            this.cargando = 1;

            let formData = new FormData();
            formData.append('file', this.newArchivo.file);
            formData.append('id', this.solicitudData.id);
            formData.append('tipo', this.newArchivo.tipo);
            let me = this;
            axios.post('/files-solic', formData)
            .then(function (response) {
                me.cerrarModal();
                me.cargando = 0;
                me.solicitudData.files = response.data.data;
                me.newArchivo = {
                    description: "",
                    tipo: "",
                    file: "",
                    nom_archivo: 'Seleccione Archivo'
                };
                swal({
                    position: 'top-end',
                    type: 'success',
                    title: 'Archivo guardado correctamente',
                    showConfirmButton: false,
                    timer: 2000
                })
            }).catch(function (error) {
                currentObj.output = error;
                me.cargando = 0;
            });
        },
        deleteDetalle(id){
            axios.delete(`/solic-detalles/deleteDetalle/${id}`,
                {params: {'id': id}}).then(function (response){
            }).catch(function (error){
                console.log(error);
            });
        },
        deleteFile(id){
            let me = this;

            swal({
                title: '¿Desea eliminar el archivo?',
                text: "Esta acción no se puede revertir!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si, eliminar!'
                }).then((result) => {
                if (result.value) {

                    axios.delete(`/files-solic/${id}`,
                        {params: {'id': id}}).then(function (response){
                    }).catch(function (error){
                        console.log(error);
                    });

                    me.solicitudData.files = me.solicitudData.files.filter(
                            a => a.id != id
                    )
                    //Se muestra mensaje Success
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Archivo removido'
                    })
                }
            })
        },
        changeCuenta(cuenta){
            let me = this;
            axios.put(`/solic-pagos/changeCuenta/${me.solicitudData.id}`,{
                'id': me.solicitudData.id,
                'banco' : cuenta.banco,
                'num_cuenta' : cuenta.num_cuenta,
                'clabe' : cuenta.clabe,
            }).then(function (response){
                me.solicitudData.banco = cuenta.banco
                me.solicitudData.num_cuenta = cuenta.num_cuenta
                me.solicitudData.clabe = cuenta.clabe
                me.cerrarModal();
                //Se muestra mensaje Success
                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                    });
                    toast({
                    type: 'success',
                    title: 'Solicitud actualizada'
                })
            }).catch(function (error){
            });
        },
        removeDetalle(det){
            let me = this;

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
                    if(det.id != null)
                        me.deleteDetalle(det.id);

                    me.solicitudData.detalle = me.solicitudData.detalle.filter(
                            a => a != det
                    )
                    //Se muestra mensaje Success
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Detalle removido'
                    })
                }
            })
        },
        cerrarModal(){
            this.modal = 0;
            this.tituloModal = '';
            this.arrayObs = [];
        },
        addDetalle(){
            let me = this;
            if(me.verificarCaptura()){
                const detalle = {...me.datosDetalle}
                me.solicitudData.detalle.push(detalle);
                me.limpiarFormularioDetalle();
                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                    });
                    toast({
                    type: 'success',
                    title: 'Detalle agregado'
                })
            }
            else{
                window.alert("Verifica los campos faltantes");
            }

        },
        añadirPendiente(det){
            let me = this;
            me.selectEtapa(det.obra)
            me.$root.getManzanas(det.obra, det.sub_obra)
            me.searchContrato(det.lote_id)
            me.datosDetalle = {
                id : '',
                solic_id  : det.id,
                obra: det.obra,
                sub_obra: det.sub_obra,
                cargo : det.cargo,
                concepto : det.concepto,
                observacion : 'Monto pendiente: ' + det.observacion,
                tipo_mov : 1,
                total : det.saldo,
                pago : det.saldo,
                saldo : 0,
                pendiente_id : det.id,
                manzana: '',
                lote_id: det.lote_id,
                contrato_id : det.contrato_id,
                num_lote : det.num_lote,
                sublote : det.sublote,
                proveedor_id : det.proveedor_id,
                fecha_factura : det.fecha_factura,
                folio_fisc : det.folio_fisc
            }
                // me.solicitudData.detalle.push(detalle);
                me.arrayPendientes = me.arrayPendientes.filter(
                        a => a != det
                )

                // const toast = Swal.mixin({
                //     toast: true,
                //     position: 'top-end',
                //     showConfirmButton: false,
                //     timer: 3000
                //     });
                //     toast({
                //     type: 'success',
                //     title: 'Detalle agregado'
                // })
        },
        verificarCaptura(){
            let me = this;

            if( me.datosDetalle.obra != ''
                && me.datosDetalle.cargo != ''
                && me.datosDetalle.concepto != ''
                && me.datosDetalle.total > 0
                && me.datosDetalle.pago > 0
            )return true;

            return false;
        },
        limpiarFormularioDetalle(){
            let me = this;
            me.contratoVenta = {};
            me.datosDetalle = {
                id  : '',
                obra: '',
                sub_obra: '',
                cargo : '',
                concepto : '',
                observacion : '',
                tipo_mov : 0,
                total : 0,
                pago : 0,
                saldo : 0,
                pendiente_id : null,
                manzana: '',
                lote_id: '',
                contrato_id : '',
                proveedor_id : '',
                fecha_factura : '',
                folio_fisc : ''
            }
        },
        vistaFormulario(accion,data=[]){
            this.getCargos();
            this.limpiarFormularioDetalle();

            switch(accion){
                case 'nuevo':{
                    this.tipoAccion = 1;
                    this.solicitudData={
                        id : '',
                        empresa_solic : '',
                        proveedor_id : '',
                        importe : 0,
                        saldo : 0,
                        tipo_pago : 0,
                        forma_pago : '',
                        convenio : '',
                        referencia : '',
                        fecha_compra: '',
                        banco : '',
                        num_cuenta : '',
                        clabe : '',
                        num_factura : '',
                        extraordinario : 0,
                        detalle : [],
                        caja_chica : 0
                    }
                    break;
                }
                case 'actualizar':{
                    this.newArchivo = {
                        description: "",
                        tipo: "",
                        file: "",
                        nom_archivo: 'Seleccione Archivo'
                    };
                    this.solicitudData = data;
                    this.arrayObs = this.solicitudData.obs;
                    this.tipoAccion = 2;
                    this.cargando = 0;
                    break;
                }
                case 'ver':{
                    this.solicitudData = data;
                    this.arrayObs = this.solicitudData.obs;
                    this.tipoAccion = 3;
                    if(this.solicitudData.status >= 1 && this.solicitudData.tipo_pago == 1)
                        this.selectCuenta(this.solicitudData.empresa_solic);
                    break;
                }
            }
            this.vista = 1;
        },
        cerrarFormulario(){
            this.vista = 0;
            this.solicitudData = {};
            this.indexSolicitudes(this.arraySolic.current_page);
        },
        deleteSolic(id){
            let me = this;
            swal({
                title: '¿Desea eliminar esta solicitud?',
                text: "Esta acción no se puede revertir!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si, eliminar!'
                }).then((result) => {
                if (result.value) {
                    axios.delete(`/solic-pagos/${id}`, {
                            params: {'id': id}
                    }).then(function (response){
                        me.indexSolicitudes(me.arraySolic.current_page); //se enlistan nuevamente los registros
                        //Se muestra mensaje Success
                        const toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                            });
                        toast({
                            type: 'success',
                            title: 'Solicitud eliminada correctamente'
                        })
                    }).catch(function (error){
                        console.log(error);
                    });
                }
            })


        },
        /**Metodo para registrar  */
        storeObs(id){
            let me = this;
            //Con axios se llama el metodo store de FraccionaminetoController
            axios.post('/sp/storeObs',{
                'solicitud_id': id,
                'comentario' : me.comentario
            }).then(function (response){
                me.indexSolicitudes(me.arraySolic.current_page); //se enlistan nuevamente los registros
                me.comentario = '';
                me.arrayObs.push(response.data)
                //me.cerrarModal();
                //Se muestra mensaje Success
                swal({
                    position: 'top-end',
                    type: 'success',
                    title: 'Observación creada correctamente',
                    showConfirmButton: false,
                    timer: 1500
                    })
            }).catch(function (error){
            });
        },
        /**Metodo para registrar  */
        storeSolic(){
            let me = this;
            //Con axios se llama el metodo store de FraccionaminetoController
            axios.post('/solic-pagos',{
                'solicitud': me.solicitudData,
            }).then(function (response){
                me.indexSolicitudes(me.arraySolic.current_page); //se enlistan nuevamente los registros
                me.cerrarFormulario();
                //Se muestra mensaje Success
                swal({
                    position: 'top-end',
                    type: 'success',
                    title: 'Solicitud creada correctamente',
                    showConfirmButton: false,
                    timer: 1500
                    })
            }).catch(function (error){
            });
        },
        updateSolicitud(){
            let me = this;
            //Con axios se llama el metodo store de FraccionaminetoController
            axios.put(`/solic-pagos/${me.solicitudData.id}`,{
                'solicitud': me.solicitudData,
            }).then(function (response){
                me.indexSolicitudes(me.arraySolic.current_page); //se enlistan nuevamente los registros
                me.cerrarFormulario();
                //Se muestra mensaje Success
                swal({
                    position: 'top-end',
                    type: 'success',
                    title: 'Solicitud actualizada correctamente',
                    showConfirmButton: false,
                    timer: 1500
                    })
            }).catch(function (error){
            });
        },
        checkGerente(){
            let me = this;
            me.gerente = me.arrayGerentes.find(element => element == me.usuario)
        },
        /**Metodo para mostrar los registros */
        indexSolicitudes(page) {
            let me = this;
            me.arraySolic = [];
            var url =
                "/solic-pagos?page=" + page
                + '&b_proveedor=' + me.b_proveedor
                + '&b_empresa=' + me.b_empresa
                + '&b_solicitante=' + me.b_solicitante
                + '&b_vbgerente=' + me.b_vbgerente
                + '&b_vbdireccion=' + me.b_vbdireccion
                + '&b_fecha1=' + me.b_fecha1
                + '&b_fecha2=' + me.b_fecha2
                + '&b_rechazado=' + me.b_rechazado
                + '&b_tipo_pago=' + me.b_tipo_pago
                + '&b_forma_pago=' + me.b_forma_pago
                + '&b_cuenta_pago=' + me.b_cuenta_pago
                + '&b_devolucion=' + me.b_devolucion
                + '&b_status=' + me.b_status;
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.arraySolic = respuesta.solicitudes;
                    me.admin = respuesta.admin;
                    me.total = respuesta.total;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        getProveedores(search,loading){
            let me = this;
            loading(true)
            me.arrayProveedores=[];
            var url = '/select_proveedor?proveedor='+search;
            axios.get(url).then(function (response) {
                const respuesta = response.data;
                q: search
                const data = [...respuesta.proveedor]
                me.arrayProveedores = data;
                loading(false)
            })
            .catch(function (error) {
                console.log(error);
                loading(false)
            });
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
            });
        },
        getCuentasProv(id){
            let me = this;
            me.arrayCuentas=[];
            var url = '/proveedor/getCuentasProv?id='+id;
            axios.get(url).then(function (response) {
                var respuesta = response;
                me.arrayCuentas = respuesta.data;
            })
            .catch(function (error) {
            });
        },
        getProyectos(search){
            let me = this;
            me.arrayProyectos = [];
            var url = '/select_fraccionamiento2?filtro='+search;
            axios.get(url).then(function (response) {
                const respuesta = response.data;
                me.arrayProyectos = respuesta.fraccionamientos;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        verificarMonto(){
            let me = this;

            if(parseFloat(me.datosDetalle.total) < parseFloat(me.datosDetalle.pago))
                me.datosDetalle.pago = me.datosDetalle.total
        },
        getCargos(){
            let me = this;
            me.arrayCargos=[];
            var url = '/sp/getCargos';
            axios.get(url).then(function (response) {
                me.arrayCargos = response.data;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        getDetallesPendientes(id){
            let me = this;
            me.arrayPendientes=[];
            var url = '/sp/getDetallesPendientes?proveedor_id='+id;
            axios.get(url).then(function (response) {
                me.arrayPendientes = response.data;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        getConceptos(cargo){
            let me = this;
            me.arrayConceptos=[];
            var url = '/sp/getConceptos?cargo='+cargo;
            axios.get(url).then(function (response) {
                me.arrayConceptos = response.data;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        selectEtapa(buscar){
            let me = this;
            me.datosDetalle.sub_obra = '';
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
        selectProveedores(search,loading){
            let me = this;
            loading(true)
            me.proveedoresForm=[];
            var url = '/select_proveedor?proveedor='+search;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                q: search
                const data = [...respuesta.proveedor, ...respuesta.usuarios, ...respuesta.clientes]
                me.proveedoresForm = data;
                loading(false)
            })
            .catch(function (error) {
                console.log(error);
                loading(false)
            });
        },
        getDatosProveedor(val1){
            let me = this;
            //me.loading = true;
            me.solicitudData.proveedor_id = val1.id;
            me.solicitudData.proveedor = val1.proveedor;
            me.getDetallesPendientes(val1.id);
        },
        getDatosProveedorDet(val1){
            let me = this;
            //me.loading = true;
            me.datosDetalle.proveedor_id = val1.id;
            me.datosDetalle.proveedor = val1.proveedor;
        },
        cerrarModal() {
            this.modal = 0;
            this.planos = [];
            this.newArchivo = {};
            this.lotes_ini = [];
            this.proyectoSel = '';
            this.etapaSel = '';
            this.indexSolicitudes(this.arraySolic.current_page)
            this.cargando = 0;
        },
        /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
        abrirModal(accion, data=[]) {
            let me = this;
            switch (accion) {
                case 'banco':{
                    me.modal = 2;
                    me.tituloModal = 'Cambiar cuenta bancaria';
                    me.getCuentasProv(me.solicitudData.proveedor_id);
                    break;
                }
                case 'rechazo':{
                    me.comentario = ''
                    me.modal = 3;
                    me.tituloModal = 'Solicitud rechazada'
                    break;
                }
                case 'pagar':{
                    me.selectCuenta(me.solicitudData.empresa_solic);
                    me.solicitudData.fecha_pago = '';
                    me.solicitudData.num_factura = '';
                    me.solicitudData.beneficiario = 0;
                    me.modal = 4;
                    me.tituloModal = 'Generar pago'
                    me.solicitudData.accionPago = 1;
                    break;
                }
                case 'pagoMasa':{
                    me.selectCuenta('');
                    me.solicitudData = {...data};
                    me.modal =  4;
                    me.tituloModal = 'Generar pago'
                    me.solicitudData.accionPago = 3;
                    break;
                }
                case 'verPago':{
                    me.solicitudData = {...data};
                    me.selectCuenta(me.solicitudData.empresa_solic);
                    me.modal = 4;
                    me.tituloModal = 'Pago'
                    me.solicitudData.accionPago = 2;
                    break;
                }
                case 'comprobante':{
                    me.solicitudData = {...data};
                    me.modal = 5;
                    me.tituloModal = 'Comprobante de pago'
                    this.newArchivo = {
                        description: "COMPROBANTE DE PAGO",
                        tipo: "COMPROBANTE DE PAGO",
                        file: "",
                        nom_archivo: 'Seleccione Archivo'
                    };
                    break;
                }
                case 'comprobanteMasa':{
                    me.modal = 6;
                    me.tituloModal = 'Comprobante de pago'
                    this.newArchivo = {
                        description: "COMPROBANTE DE PAGO",
                        tipo: "COMPROBANTE DE PAGO",
                        file: "",
                        nom_archivo: 'Seleccione Archivo'
                    };
                    break;
                }
                case 'detalles':{
                    me.modal = 7;
                    me.tituloModal = 'Detalles'
                    me.solicitudData = { ...data }
                    break;
                }
                case 'edoCuenta':{
                    me.modal = 8;
                    me.tituloModal = 'Edo. de Cuenta';
                    me.getPagosAnteriores(data['id']);
                    break;
                }
            }
        },
        getPagosAnteriores(id){
            let me = this;
            me.arrayEdoCuenta=[];
            var url = 'sp/getPagosAnteriores?id=' + id;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayEdoCuenta = respuesta;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        verObs(solicitud){
            let me = this;
            me.arrayObs = solicitud.obs;
            me.id = solicitud.id;
            me.comentario = '';
            me.modal = 1;
            me.tituloModal = 'Observaciones';
        }
    },
    mounted() {
        this.indexSolicitudes(1);
        this.checkGerente();
        this.getEmpresa();
        // this.getProveedores();
        this.$root.selectFraccionamientos();
    }
};
</script>
<style>
    .text-formfile{
        color: grey;
        display:flex;
        padding-top: 13px;
        justify-content: left;

    }
    .contenedor-modal{
        display: block;
        flex-direction: column;
        margin: auto;
        overflow-x: auto;
        width: fit-content;
        max-width: 100%;
    }

    .label-button{
        border-style: solid;
        cursor:pointer;
        color: #fff;
        background-color: #00ADEF;
        border-color: #00ADEF;
        padding: 10px;
        margin: 15px;
    }

    .label-button:hover {
        color: #fff;
        background-color: #1b8eb7;
        border-color: #00b0bb;
    }
    .form-sub{
        border: 1px solid #c2cfd6;
        margin-top: 20px;
        width: 100%;
    }
    .form-opc{
        display: flex;
        flex-direction: column;
    }
    .form-archivo{
        display: flex;
        flex-direction: row;
        width: 100%;
    }
    .text-file{
        color: rgb(39, 38, 38);
        font-size:12px;
        word-break: break-all;
        font-weight: bold;
        width: 300px;
        padding: 15px;
    }
    .text-file-hide{
        color: rgb(127, 130, 134);
        font-size:13px;
        word-break: break-all;
        font-weight: bold;
        width: 300px;
        padding: 15px;
    }
    .boton-modal{
        margin-top: 15px;
        display: flex;
        flex-direction: row;
        justify-content: center;
    }
    .div-error{
        display:flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
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

