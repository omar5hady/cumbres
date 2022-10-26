<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
        </ol>

        <div class="container-fluid">
            <div class="card scroll-box">
                <div class="card-header flex-md-column" >
                    <i class="fa fa-align-justify"></i>Prestamos Personales
                </div>
                <div class="card-body">
                    <div class="form-group flex-column">
                        <button class="btn btn-info" @click="abrirModal('registrar')"> <i class="fa fa-plus-circle"></i> Nueva Solicitud.</button>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-8" v-if="isRHCurrent">
                            <div class="input-group">
                                <input type="text"  class="form-control col-md-3" disabled placeholder="Colaborador:">
                                <input type="text"  v-model="b_colaborador" @keyup.enter="dataPrestamos(1)" class="form-control" placeholder="Nombre a buscar">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-10">
                            <div class="input-group">
                                <input type="text"  class="form-control col-md-3" disabled placeholder="Fecha:">
                                <input type="date"  v-model="b_fecha1" @keyup.enter="dataPrestamos(1)" class="form-control">
                                <input type="date"  v-model="b_fecha2" @keyup.enter="dataPrestamos(1)" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <div class="input-group">
                                    <select class="form-control" v-model="b_status" >
                                    <option value="">Estatus</option>
                                    <option value="0">Rechazado</option>
                                    <option value="1">Pendiente</option>
                                    <option value="2">Aprobado</option>
                                    <option value="3">Liquidado</option>
                                </select>
                                <button type="submit" @click="dataPrestamos(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <!-- inicio de tabla -->
                    <TableComponent v-if="vista_tabla == 1"
                        :cabecera="[
                            '','Colaborador','Monto solicitado','Fecha de solic.',
                            'Inicio de Retenciones','Saldo','Status','Jefe inmediato',
                            'RH','Dirección',' '
                        ]"
                    >
                        <template v-slot:tbody>
                            <tr v-for="prestamo in arrayDataPrestamos.data" :key="prestamo.id">
                                <td class="td2">
                                    <template v-if="isRHCurrent && prestamo.status_rh !=2" >
                                        <button type="button"
                                                @click="eliminaSolicitud(prestamo.id)"
                                                class="btn btn-danger btn-sm">
                                                <i class="icon-trash"></i>
                                        </button>
                                    </template>
                                    <button type="button"
                                            @click="abrirModal('ver',prestamo)"
                                            class="btn btn-dark btn-sm">
                                            <i class="icon-eye"></i>
                                    </button>
                                    <template v-if="isRHCurrent" >
                                        <button type="button"
                                                @click="abrirModal('editar',prestamo)"
                                                class="btn btn-warning btn-sm">
                                                <i class="icon-pencil"></i>
                                        </button>
                                    </template>
                                </td>


                                <td class="td2" v-text="prestamo.nombre + ' ' +prestamo.apellidos"></td>
                                <td class="td2" v-text="'$' + formatNumber(prestamo.monto_solicitado)"></td>
                                <td class="td2" v-text="this.moment(prestamo.created_at).locale('es').format('DD/MMM/YYYY')"></td>
                                <td class="td2" v-text="this.moment(prestamo.fecha_ini).locale('es').format('DD/MMM/YYYY')"></td>
                                <td class="td2" v-text="'$' + formatNumber(prestamo.saldo)"></td>
                                <td class="td2">
                                    <!-- 0 Cancelado, 1 Pendiente, 2 Aprobado, 3 Liquidado -->
                                    <span v-if="prestamo.status == 0" class="badge badge-danger">Rechazado</span>
                                    <span v-if="prestamo.status == 1" class="badge badge-warning">Pendiente</span>
                                    <span v-if="prestamo.status == 2" class="badge badge-primary">Aprobado</span>
                                    <span v-if="prestamo.status == 3" class="badge badge-success">Liquidado</span>
                                </td>
                                    <template v-if="isGerenteCurrent || isRHCurrent || isDireccionCurrent">

                                    <td class="td2" v-if="prestamo.jefe_band == 0">
                                        <button v-if="isGerenteCurrent"  class="btn btn-dark btn-sm"  type="button" @click="firmar('jefe',prestamo.id)"  >
                                            <i class="icon-check"></i>
                                        </button>

                                    </td>
                                    <td class="td2" v-else >Firmado</td>

                                        <td class="td2" v-if="prestamo.rh_band == 0">
                                        <button v-if="isRHCurrent" class="btn btn-dark btn-sm"  type="button" @click="firmar('rh',prestamo.id)"  >
                                            <i class="icon-check"></i>
                                        </button>
                                    </td>
                                    <td class="td2" v-else >Firmado</td>


                                        <td class="td2" v-if="prestamo.dir_band == 0">
                                        <button v-if="isDireccionCurrent" class="btn btn-dark btn-sm"   type="button" @click="firmar('dir',prestamo.id)" >
                                            <i class="icon-check"></i>
                                        </button>
                                    </td>
                                    <td class="td2" v-else >Firmado</td>


                                </template>

                                <template v-else >
                                    <td class="td2" v-if="prestamo.jefe_band == 0">
                                        Sin Firma de Jefe
                                    </td>
                                    <td class="td2" v-else > Firmado</td>


                                    <td class="td2" v-if="prestamo.rh_band == 0">
                                        Sin Firma de RH
                                    </td>
                                    <td class="td2" v-else > Firmado</td>


                                    <td class="td2" v-if="prestamo.dir_band == 0">
                                        Sin Firma de Dirección
                                    </td>
                                        <td class="td2" v-else > Firmado</td>

                                </template>


                                <td>
                                    <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right"
                                                @click="abrirModal('observaciones',prestamo)">Observaciones</button>
                                </td>
                            </tr>
                        </template>
                    </TableComponent>
                    <!-- fin de tabla -->
                    <nav>
                        <!--Botones de paginacion -->
                        <ul class="pagination">
                            <li class="page-item" v-if="arrayDataPrestamos.current_page > 5" @click="dataPrestamos(1)">
                                <a class="page-link" href="#" >Inicio</a>
                            </li>
                            <li class="page-item" v-if="arrayDataPrestamos.current_page > 1"
                                @click="dataPrestamos(arrayDataPrestamos.current_page-1)">
                                <a class="page-link" href="#" >Ant</a>
                            </li>

                            <li class="page-item" v-if="arrayDataPrestamos.current_page-3 >= 1"
                                @click="dataPrestamos(arrayDataPrestamos.current_page-3)">
                                <a class="page-link" href="#" v-text="arrayDataPrestamos.current_page-3"></a>
                            </li>
                            <li class="page-item" v-if="arrayDataPrestamos.current_page-2 >= 1"
                                @click="dataPrestamos(arrayDataPrestamos.current_page-2)">
                                <a class="page-link" href="#" v-text="arrayDataPrestamos.current_page-2"></a>
                            </li>
                            <li class="page-item" v-if="arrayDataPrestamos.current_page-1 >= 1"
                                @click="dataPrestamos(arrayDataPrestamos.current_page-1)">
                                <a class="page-link" href="#" v-text="arrayDataPrestamos.current_page-1"></a>
                            </li>
                            <li class="page-item active" >
                                <a class="page-link" href="#" v-text="arrayDataPrestamos.current_page"></a>
                            </li>
                            <li class="page-item"
                                v-if="arrayDataPrestamos.current_page+1 <= arrayDataPrestamos.last_page">
                                <a class="page-link" href="#" @click="dataPrestamos(arrayDataPrestamos.current_page+1)"
                                v-text="arrayDataPrestamos.current_page+1"></a>
                            </li>
                            <li class="page-item"
                                v-if="arrayDataPrestamos.current_page+2 <= arrayDataPrestamos.last_page">
                                <a class="page-link" href="#" @click="dataPrestamos(arrayDataPrestamos.current_page+2)"
                                    v-text="arrayDataPrestamos.current_page+2"></a>
                            </li>
                            <li class="page-item"
                                v-if="arrayDataPrestamos.current_page+3 <= arrayDataPrestamos.last_page">
                                <a class="page-link" href="#" @click="dataPrestamos(arrayDataPrestamos.current_page+3)"
                                v-text="arrayDataPrestamos.current_page+3"></a>
                            </li>

                            <li class="page-item" v-if="arrayDataPrestamos.current_page < arrayDataPrestamos.last_page"
                                @click="dataPrestamos(arrayDataPrestamos.current_page+1)">
                                <a class="page-link" href="#" >Sig</a>
                            </li>
                            <li class="page-item" v-if="arrayDataPrestamos.current_page < 5 && arrayDataPrestamos.last_page > 5" @click="dataPrestamos(arrayDataPrestamos.last_page)">
                                <a class="page-link" href="#" >Ultimo</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <ModalComponent
            v-if="modal == 1"
            :titulo="tituloModal"
            @closeModal="cerrarModal()"
        >
            <template v-slot:body>
                <div class="form-group row">
                    <label class="col-md-3"  for="text-input">Colaborador:
                    </label>
                    <template  v-if="isRHCurrent && modalVista == '0'">
                        <select class="col-md-6 form-control"  v-model="id_usuario"  name="" id="">
                            <option  class=" form-control " v-for="user in arrayUsers" :key="user.id" :value="user.id" v-text="user.nombre + ' '+ user.apellidos" ></option>
                        </select>
                    </template>
                    <template v-else>
                        <label  class="col-md-6 form-control" v-text="e_nombre"></label>
                    </template>
                </div>

                <div class="form-group row">
                    <label class="col-md-3" >Fecha de inicio de retencion:</label>
                    <template v-if="status_rh ==2 ||  modalVista == '1'" >
                        <input disabled class="col-md-6 form-control" type="date"  v-model="fecha_solic" >
                    </template>
                    <template v-else >
                        <input class="col-md-6 form-control" type="date"  v-model="fecha_solic" >
                    </template>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 form-control-label">Gerente a cargo:
                    </label>
                    <template  v-if="status_rh == 2 ||  modalVista == '1'">
                            <select disabled class="col-md-6 form-control"  name="" id=""  v-model="idJefe">
                                <option v-for="gerente in arrayIdGerentes" :key="gerente.id" :value="gerente.id" v-text="gerente.nombre" ></option>
                            </select>
                    </template>
                    <template v-else >
                            <select class="col-md-6 form-control"  name="" id=""  v-model="idJefe">
                                <option v-for="gerente in arrayIdGerentes" :key="gerente.id" :value="gerente.id" v-text="gerente.nombre" ></option>
                            </select>
                    </template>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 form-control-label" >Monto solicitado:
                        <span style="color:red;" v-show="monto_solic == 0">*</span>
                    </label>

                    <template v-if="status_rh ==2 ||  modalVista == '1'">
                        <input disabled class="col-md-3  form-control"  type="number" v-on:keypress="isNumber($event)" v-model="monto_solic" >
                    </template>
                    <template v-else>
                        <a  v-if="editAjusteMonto ==0"
                            class="form-control col-md-3"
                            style="cursor: pointer; color:deepskyblue; text-decoration: underline; text-shadow: slategrey;"
                            title="Click para editar"
                            @click="editAjusteMonto=1" v-text="'$'+formatNumber(monto_solic)" >
                        </a>
                        <input v-if="editAjusteMonto ==1"  aria-selected="true" class="form-control col-md-3" title="Enter para guardar.."  pattern="\d*" type="text"
                            @keyup.enter="editAjusteMonto=0"
                            v-on:keypress="isNumber($event)"  v-model="monto_solic">
                    </template>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-descuento">Descuento por quincena.
                        <span style="color:red;" v-show="desc_quin ==0">*</span>
                    </label>
                    <div class="form-group row col-md-9 ">
                        <template v-if="status_rh ==2 ||  modalVista == '1'">
                            <input disabled class="form-control col-md-2" type="number" v-on:keypress="isNumber($event)"  v-model="desc_quin" >
                        </template >
                        <template v-else>
                                <a v-if="editAjusteQuin ==0"
                                    class="form-control col-md-2"
                                    style="cursor: pointer; color:deepskyblue; text-decoration: underline;"
                                    title="Click para editar"
                                    @click="editAjusteQuin=1" v-text="'$'+formatNumber(desc_quin)" >
                                </a>
                                <input  v-if="editAjusteQuin ==1"  class="form-control col-md-2" title="Enter para guardar.."  pattern="\d*" type="text"
                                @keyup.enter="editAjusteQuin=0"
                                v-on:keypress="isNumber($event)"  v-model="desc_quin">

                        </template>
                        <template v-if="(modalVista == '0' || modalVista == '2') && status_rh !=2">
                                <button class="form-control col-md-2 btn btn-info"
                                        type="button"
                                        v-show="desc_quin"
                                        @click="generar_tab = 1 ,
                                        editAjuste=0,
                                        generarTablaPagos()">
                                        Generar
                                </button>
                        </template>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 form-control-label" >Motivo de prestamo.
                        <span style="color:red;" v-show="motivo">*</span>
                    </label>
                        <template v-if="status_rh == 2 || modalVista == '1'">
                            <textarea disabled class="col-md-6 form-control" cols="10" rows="2"  type="text"  maxlength="50" v-model="motivo" ></textarea>
                        </template>
                        <template v-else>
                            <textarea class="col-md-6 form-control" cols="10" rows="2"  type="text"  maxlength="50" v-model="motivo" ></textarea>
                        </template>
                </div>

                <div class="form-group row" v-if="status_rh == 2 && modalVista == '2' && saldoFaltante > 0">
                    <div class="col-md-4">
                        <button class="form-control btn btn-success"
                                type="button"
                                @click="abrirModal('monto_extra')">
                                <i class="icon-plus"></i>&nbsp;Nueva retención extaordinaria.
                        </button>
                    </div>
                </div>

                <template v-if="generar_tab == 1" >
                    <div class="form-group row">
                        <div class="col-md-12">
                            <TableComponent v-if="modalVista == '0' || modalVista == '1'"
                                :cabecera="['Quincena','Pago Nomina','Pago Extraordinario','Saldo por Retener']">
                                <template v-slot:tbody>
                                    <tr  v-for="pago in arrayPagos" :key="pago.id" v-show="pago.pago !=0">
                                            <td v-text="this.moment(pago.fecha_quincena).locale('es').format('DD/MMM/YYYY')"></td>
                                            <td v-text="'$'+formatNumber(pago.monto_pago)"></td>
                                            <td class="td2" v-text="'$'+formatNumber(pago.monto_pago_extra)"></td>
                                            <td>
                                                {{(pago.status == 1 || pago.status == 2) ? '$'+formatNumber(pago.saldo) : ''}}
                                            </td>
                                            <td v-if="pago.fecha_pago != null">
                                                <span class="badge badge-success"
                                                    v-text="'Pago retenido el dia: ' + this.moment(pago.fecha_pago).locale('es').format('DD/MMM/YYYY')"
                                                ></span>
                                            </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="font-1xl bg-info" v-text="'$'+formatNumber(total = totalNomina)"></td>
                                        <td class="font-1xl bg-info" v-text="'$'+formatNumber(totalExtra = totalExtraordinario)"></td>
                                        <td v-if="saldoFaltante > 0" class="font-1xl bg-danger" v-text="'$'+formatNumber(saldoFaltante = totalSaldo)"></td>
                                        <td v-else  class="font-1xl bg-success" v-text="'$'+formatNumber(saldoFaltante = totalSaldo)"></td>
                                    </tr>
                                </template>
                            </TableComponent>

                            <TableComponent v-else
                                :cabecera="['Quincena','Pago Nomina','Pago Extraordinario','Saldo por Retener','']"
                            >
                                <template v-slot:tbody>
                                    <tr  v-for="pago in arrayPagos" :key="pago.id" v-show="pago.pago !=0">
                                        <td v-text="this.moment(pago.fecha_quincena).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td v-text="'$'+formatNumber(pago.monto_pago)"></td>
                                        <template v-if="pago.status != 1 && id_prestamo !=null">
                                            <td class="td2">
                                                <a title="Click para editar" v-if="editAjuste == 0"
                                                    href="#" @click="editAjuste=1" v-text="'$'+formatNumber(pago.monto_pago_extra)" ></a>

                                                <input  v-if="editAjuste ==1" title="Enter para guardar.." class="form-control2" pattern="\d*" type="text"
                                                    @keyup.enter="validarMonto(pago,$event.target.value), editAjuste=0" step="1"
                                                    v-on:keypress="isNumber($event)"  v-model="pago.monto_pago_extra">
                                            </td>
                                        </template>
                                        <template v-else>
                                            <td class="td2" v-text="'$'+formatNumber(pago.monto_pago_extra)"></td>
                                        </template>
                                        <td v-if="pago.fecha_pago">
                                            {{ (pago.saldo >0) ? '$'+formatNumber(pago.saldo) : '$0.00'}}
                                        </td>
                                        <td v-else></td>
                                        <template v-if="status_rh == 2">
                                            <td v-if="pago.status == 0" class="form-group-sm" >
                                                <button v-show="pago.fecha_pago" v-if="editAjuste ==0 && pago.pago !=0"
                                                        class="bg-success py-sm-1 px-sm-1 btn row"
                                                        style=" margin:1px"
                                                        @click="capturarPago(pago)"
                                                >
                                                    <i class="fa small fa-2x icon-check"></i>
                                                    Retener Pago
                                                </button>

                                                <input v-if="(pago.monto_pago_extra > 0 || pago.monto_pago > 0)"
                                                    class="form-control"
                                                    style="cursor: pointer;"
                                                    name="fecha"
                                                    placeholder="Capturar fecha de retencion"
                                                    type="text"
                                                    onfocus="(this.type='date')"
                                                    v-model="pago.fecha_pago"
                                                >
                                            </td>
                                            <td v-if="pago.status == 1" class="py-sm-0 justify-content-center"  v-text="'Fecha de retencion de pago: '+ pago.fecha_pago" ></td>
                                        </template>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="font-1xl bg-info" v-text="'$'+formatNumber(total = totalNomina)"></td>
                                        <td class="font-1xl bg-info" v-text="'$'+formatNumber(totalExtra = totalExtraordinario)"></td>
                                        <td v-if="saldoFaltante > 0" class="font-1xl bg-danger" v-text="'$'+formatNumber(saldoFaltante = totalSaldo)"></td>
                                        <td v-else  class="font-1xl bg-success" v-text="'$'+formatNumber(saldoFaltante = totalSaldo)"></td>
                                    </tr>
                                </template>
                            </TableComponent>
                        </div>
                    </div>
                </template>
                <!-- Div para mostrar los errores -->
                <div v-show="error_en_solicitud" class="form-group row div-error">
                    <div class="text-center text-error">
                        <div v-for="error in arrayErrorSolicitud" :key="error" v-text="error">
                        </div>
                    </div>
                </div>

            </template>

            <template v-slot:buttons-footer>
                <div v-if="isRHCurrent && modalVista == '2' && tituloModal !='Nueva Solicitud' && status_rh !=2">
                    <button type="button" class="btn btn-success" @click="aprobar_rh(1)">Aprobar</button>
                    <button type="button" class="btn btn-danger" @click="aprobar_rh(0)">Rechazar solicitud</button>
                </div>

                <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                <div v-if="modalVista == '0'">
                        <button type="button" class="btn btn-success"   @click="enviarSolicitud()">Enviar</button>
                </div>
                <div v-if="modalVista == '2' && status_rh !=2">
                        <button type="button" class="btn btn-success"   @click="editarSolicitud()">Guardar cambios</button>
                </div>
            </template>

        </ModalComponent>

        <!-- inicio modal observaciones  -->
        <ModalComponent
            v-if="modal == 2"
            :titulo="tituloModal"
            @closeModal="cerrarModal()"
        >
            <template v-slot:body>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Observacion</label>
                    <div class="col-md-6">
                            <textarea rows="3" cols="30" v-model="obs_prestamo" class="form-control" placeholder="Observacion"></textarea>
                    </div>
                    <div class="col-md-2">
                        <button type="button"  class="btn btn-primary" @click="guardaObs()">Guardar</button>
                    </div>
                </div>
                <TableComponent :cabecera="['Usuario','Observacion','Fecha']">
                    <template v-slot:tbody>
                        <tr v-for="observacion in arrayObservaciones" :key="observacion.id">
                            <td v-text="observacion.usuario" ></td>
                            <td v-text="observacion.observacion" ></td>
                            <td v-text="observacion.created_at"></td>
                        </tr>
                    </template>
                </TableComponent>
            </template>
        </ModalComponent>
        <!--FIN DE MODAL  -->

        <ModalComponent v-if="modal == 3"
            :titulo="'Nueva retencion extraordinaria'"
            @closeModal="modal=1"
        >
            <template v-slot:body>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Monto a retener</label>
                    <div class="col-md-4">
                        <input pattern="\d*" type="text" class="form-control" v-on:keypress="isNumber($event)" @change="validarPagoExtra()" v-model="retencion_extra.monto_pago_extra">
                    </div>
                    <div class="col-md-3">
                        <p v-text="'$ ' + formatNumber(retencion_extra.monto_pago_extra)" class="form-control"></p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Fecha de retención</label>
                    <div class="col-md-5">
                        <input type="date" class="form-control"  v-model="retencion_extra.fecha_pago">
                    </div>
                </div>
            </template>
            <template v-slot:buttons-footer>
                <button type="button" v-if="retencion_extra.fecha_pago != '' && retencion_extra.monto_pago_extra > 0"
                    class="btn btn-primary" @click="storePago()">Registrar Retención</button>
            </template>
        </ModalComponent>
    </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>

import ModalComponent from '../Componentes/ModalComponent.vue'
import TableComponent from '../Componentes/TableComponent.vue'

    export default {
        components:{
            ModalComponent,
            TableComponent
        },
        props:{
            userName:{type: String},
            userId:{type: String},
        },
        data(){
            return{
                arrayIdGerentes:[
                   {id:'10',user:'eli_hdz' ,nombre:'Elizabeth Hernandez'},
                   {id:'25695',user:'sajid.m' ,nombre:'Sajid  Meza'},
                   {id:'23679',user:'bd_raul' ,nombre:'Raúl Palos López'},
                   {id:'23684',user:'lucy.hdz' ,nombre:'Maria Lucia Hernandez'},
                   {id:'26546',user:'cp.martin' ,nombre:'Martin Herrera Sanchez'},
                   {id:'26310',user:'ing_david' ,nombre:'David Calvillo Martinez'},
                   {id:'33300',user:'meza.marco60' ,nombre:'Marco Antonio Meza'},
                   {id:'24100',user:'guadalupe.ff' ,nombre:'J. Guadalupe Flores Ferrer'},
                ],

                 arrayIdRH:[
                    '31298','2','3'
                ],
                 arrayIdDir:[
                    ,'26310','26546','3'
                ],
                arrayDataPrestamos:[],
                arrayPagos:[],
                arrayObservaciones:[],
                arrayErrorSolicitud:[],
                arrayUsers:[],
                arrPaginacion:[],
                editAjuste:0,
                editAjusteMonto:0,
                editAjusteQuin:0,
                vista_tabla:0,
                modalVista: '0',
                isGerenteCurrent:false,
                isRHCurrent:false,
                isDireccionCurrent:false,
                currentId:null,
                modal:null,
                tituloModal:null,
                generar_tab:0,
                b_status:'',
                b_colaborador:'',
                b_fecha1:'',
                b_fecha2:'',
                e_nombre:'',
                e_id_user:'',
                firma_jefe:0,
                firma_rh:0,
                firma_dir:0,
                saldo_ant_Cap:0,
                index_cap:null,
                error_en_solicitud:0,
                fecha_captura_pago:'',
                id_usuario:'',

               // variables colaborador //
                id_prestamo:null,
                monto_solic:0,
                motivo:null,
                obs_prestamo:'',
                desc_quin:null,
                fecha_solic:null,
                idJefe:null,
                saldoFaltante:null,
                pago_extraOrd:null,
                total:0,
                totalExtra:0,
                nom_colaborador:'',
                status_rh:0,

                retencion_extra:{
                    monto_pago_extra: 0,
                    fecha_pago: '',
                }
            }
        },
        computed:{
            totalNomina: function(){
                var total = 0.0;
                for(var i=0;i<this.arrayPagos.length;i++){
                    if(this.arrayPagos[i].fecha_pago != null)
                        total += this.arrayPagos[i].monto_pago;
                }
                return Math.round(total);
            },
            totalExtraordinario: function(){
                var total = 0.0;
                for(var i=0;i<this.arrayPagos.length;i++){
                    if(this.arrayPagos[i].fecha_pago != null)
                        total += this.arrayPagos[i].monto_pago_extra;
                }
                return Math.round(total);
            },

            totalSaldo: function(){
                var saldo = this.monto_solic;
                saldo = saldo - this.total - this.totalExtra;
                return saldo;
            }
        },
        methods : {
            validarRegistro(){
                this.error_en_solicitud=0;
                this.arrayErrorSolicitud=[];

                if(!this.monto_solic)
                    this.arrayErrorSolicitud.push("Escriba un monto solicitado");

                if(!this.fecha_solic)
                    this.arrayErrorSolicitud.push("Ingrese la fecha de inicio de retencion.");

                if(!this.motivo)
                    this.arrayErrorSolicitud.push("Escriba un motivo");

                if(!this.desc_quin)
                    this.arrayErrorSolicitud.push("Llena el campo de descuento quincenal");

                if(!this.idJefe)
                    this.arrayErrorSolicitud.push("Seleccione el gerente a cargo");
                if(!this.arrayPagos)
                    this.arrayErrorSolicitud.push("Genere la tabla de pagos con el boton de generar");

                if(this.arrayErrorSolicitud.length)//Si el mensaje tiene almacenado algo en el array
                    this.error_en_solicitud = 1;

                return this.error_en_solicitud;
            },
            generaFechaTabla(i,n){
                let me = this;
                var date= new Date(this.fecha_solic);
                var dia  = date.getDate();
                var mes = date.getMonth()+1;
                var anio = date.getFullYear();

                for( i; i<n; i++){
                    var lastDay = new Date(anio,mes,0).getDate();
                    if(dia <= 15){
                        var dat= new Date(anio , mes-1 , 15 );
                        var dateQ = moment(dat).locale('es').format('YYYY-MM-DD');
                        me.arrayPagos[i].fecha_quincena= dateQ
                        dia = 16
                    }else{
                        var dat= new Date(anio , mes-1 , lastDay );
                        var dateQ = moment(dat).locale('es').format('YYYY-MM-DD') ;
                        me.arrayPagos[i].fecha_quincena= dateQ

                        dia = 1;
                        if(mes == 12 ){
                            mes =1;
                            anio +=1;
                        }else{
                            mes +=1;
                        }
                    }
                }

            },
            generarTablaPagos(){
                this.borrarTabla();
                var saldo = parseFloat(this.monto_solic );
                var pagoQ = parseFloat(this.desc_quin )

                for(var i=0; i<24; i++){

                    var dataObj={'monto_pago_extra':0};
                        dataObj['id'] =i+1;
                        dataObj['fecha_pago'] = null;
                        dataObj['status'] = 2;


                    if(saldo <= 0 ){
                            dataObj['monto_pago']=0
                    }else{
                        if(saldo <= pagoQ ){
                            dataObj['monto_pago']=saldo
                            //this.total += saldo;
                            }
                        else{
                            dataObj['monto_pago']=pagoQ
                            // this.total += pagoQ;
                        }
                    }

                    if(saldo <= 0 ){
                        dataObj['saldo']=0

                    }else{
                        if(saldo <= pagoQ ){
                            saldo -=saldo;
                        }
                        else{
                            saldo -=pagoQ;
                        }
                        dataObj['saldo']=saldo;

                    }
                        this.arrayPagos.push(dataObj)
                }

                this.generaFechaTabla(0,24);
            },
            validarPagoExtra(){
                if(this.retencion_extra.monto_pago_extra > this.saldoFaltante)
                    this.retencion_extra.monto_pago_extra = this.saldoFaltante;
            },
            validarMonto(pago, monto_extra){
                if(monto_extra > pago.saldo){
                    monto_extra = 0
                }

                const index = this.arrayPagos.map( e => e.id ).indexOf( pago.id );
                this.arrayPagos[index].monto_pago_extra = monto_extra;
                let saldo = parseFloat(this.monto_solic);
                this.arrayPagos.forEach(e => {
                    if(saldo > 0){
                        e.monto_pago = parseFloat(this.desc_quin)
                        if(parseFloat(e.monto_pago) > saldo)
                            e.monto_pago = saldo
                        e.saldo = saldo - (parseFloat(e.monto_pago) + parseFloat(e.monto_pago_extra));
                        saldo = e.saldo;
                    }
                    else{
                        e.monto_pago = 0;
                        e.saldo = 0;
                    }

                });
                this.editAjuste = 0;
            },
            guardaObs(){
                let me = this;
                axios.post('/prestamos/post_obs',{
                    'id' :  me.id_prestamo,
                    'obs' : me.obs_prestamo,
                }).then(function (response){
                    me.getObservaciones(me.id_prestamo);
                    me.obs_prestamo='';
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Comentario guardado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            getObservaciones(id){
                    let me = this;
                var url = '/prestamos/get_obs?id=' + id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                     me.arrayObservaciones = respuesta;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            editarSolicitud(){
                let me = this;
                var url = '/prestamos/editarPrestamo?id=' + this.e_id_user +
                                                        '&monto='+ this.monto_solic +
                                                        '&solicitud_id='+ this.id_prestamo +
                                                        '&motivo='+ this.motivo +
                                                        '&desc_quin='+this.desc_quin +
                                                        '&fecha_solic='+ this.fecha_solic +
                                                        '&idJefe=' + this.idJefe;
                axios.put(url).then(function (response) {
                    me.guardaTablaPagos_editada();
                    me.cerrarModal();
                    me.dataPrestamos();
                })
                .catch(function (error) {
                    console.log(error);
                });

            },
            enviarSolicitud(){
                if(this.validarRegistro()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }
                let me = this;
                if(this.isRHCurrent && this.modalVista == '0'){
                    var user = me.id_usuario;
                }else{
                    var user = me.userId;
                }
                var url = '/prestamos/registrarPrestamo?id=' + user +
                                                        '&monto='+ this.monto_solic +
                                                        '&motivo='+ this.motivo +
                                                        '&desc_quin='+this.desc_quin +
                                                        '&fecha_solic='+ this.fecha_solic +
                                                        '&idJefe=' + this.idJefe;
                axios.post(url).then(function (response) {

                    var respuesta = response.data;
                    me.id_prestamo = respuesta;
                    me.guardaTablaPagos();
                    me.cerrarModal();
                    me.dataPrestamos();
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Se ha enviado solicitud'
                    })
                })
                .catch(function (error) {
                    console.log(error);
                });

            },
            capturarPago(pago){
                let me = this;
                Swal.fire({
                    title: '¿Estas seguro de retener pago?',
                    text:"Este cambio no se podrá deshacer!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si continuar!',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.value) {
                         axios.put('/prestamos/capturar_pago',{
                            'pagos' : me.arrayPagos,
                            'solic_id': me.id_prestamo,
                        }).then(function (response){
                            me.dataPrestamos();
                            const index = me.arrayPagos.map( e => e.id ).indexOf( pago.id );
                            me.arrayPagos[index].status = 1;
                            swal({
                                position: 'top-end',
                                text:"Se capturo el pago correctamente",
                                type: 'success',
                                showConfirmButton: false,
                                timer: 1500
                                })
                        }).catch(function (error){
                            console.log(error);
                        });
                    }
                });

            },
            firmar(firma_f,id){
                let me = this;
                Swal.fire({
                    title: '¿Estas seguro de firmar esta solicitud',
                    text:"Este cambio no se podrá deshacer!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si continuar!',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.value) {
                         axios.put('/prestamos/firmar',{
                            'id' : id ,
                            'firma_de':firma_f
                        }).then(function (response){
                            me.dataPrestamos();
                            swal({
                                position: 'top-end',
                                type: 'success',
                                title: 'Solicitud Firmada',
                                showConfirmButton: false,
                                timer: 1500
                                })
                        }).catch(function (error){
                            console.log(error);
                        });
                    }
                });

            },
            aprobar_rh(band){
                let me = this;
                Swal.fire({
                    title: 'AprobarSolicitud',
                    text:"Este cambio no se podrá deshacer!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si continuar!',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.value) {
                        axios.put('/prestamos/aprobar_rh',{
                             'band':band,
                            'id' : this.id_prestamo ,
                            'fecha_aprob':this.fecha_solic,
                        }).then(function (response){
                            me.editarSolicitud()
                            swal({
                                position: 'top-end',
                                type: 'success',
                                title: 'Solicitud Aprobada',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }).catch(function (error){
                            console.log(error);
                        });
                    }
                });

            },
            guardaTablaPagos(){
                let me = this;
                axios.post('/prestamos/post_tablaPagosAprobada',{
                    'id' :  me.id_prestamo,
                    'arrPagos' : me.arrayPagos,
                }).then(function (response){
                   me.cerrarModal();
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Tabla de pagos guardada',
                        showConfirmButton: false,
                        timer: 1500
                        })

                }).catch(function (error){
                    console.log(error);
                });
            },

            storePago(){
                let me = this;
                axios.post('/prestamos/storeNewPago',{
                    'id' :  me.id_prestamo,
                    'monto_pago_extra' : me.retencion_extra.monto_pago_extra,
                    'fecha_pago' : me.retencion_extra.fecha_pago,
                }).then(function (response){
                   me.getTablaPagos(me.id_prestamo)
                   me.dataPrestamos();
                   me.modal = 1;
                   const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Retención guardada correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            guardaTablaPagos_editada(){
                let me = this;
                axios.put('/prestamos/guardaTablaPagos',{
                    'id' :  me.id_prestamo,
                    'arrPagos' : me.arrayPagos,
                }).then(function (response){
                   me.getTablaPagos(me.id_prestamo)

                }).catch(function (error){
                    console.log(error);
                });
            },

            getTablaPagos(id_solicitud){
                let me = this;
                var url = '/prestamos/getTablaPagos?id=' + id_solicitud;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPagos=respuesta;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            eliminaSolicitud(id_solicitud){
                swal({
                    title: '¿Desea eliminar esta solicitud?',
                    text:"Esta acción no se puede revertir!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Si, eliminar!'
                }).then((result) => {
                if (result.value) {
                    let me = this;
                axios.delete('/prestamos/eliminarSolicitud',
                        {params: {'id': id_solicitud}}).then(function (response){
                        swal(
                            'Eliminado!',
                            'Solicitud eliminada correctamente.',
                            'success'
                        )
                        me.dataPrestamos();//se enlistan nuevamente los registros

                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            getUsers(){
                let me = this;
                var url = '/prestamos/getUsers';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                   me.arrayUsers=respuesta;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            nom_col(id_user){
                let me = this;
                var url = '/prestamos/getColaborador?id=' + id_user;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                     me.e_nombre=respuesta.nombre + ' ' +respuesta.apellidos ;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            dataPrestamos(page){
                let me = this;
                var url = '/prestamos/getDataPrestamos?page='+ page +
                            '&idUser='+this.userId+
                            '&isGerenteCurrent='+this.isGerenteCurrent +
                            '&b_colaborador='+ this.b_colaborador +
                            '&isRHCurrent='+ this.isRHCurrent +
                            '&b_fecha1='+ this.b_fecha1 +
                            '&b_fecha2='+ this.b_fecha2 +
                            '&b_status='+ this.b_status +
                            '&isDireccionCurrent='+ this.isDireccionCurrent;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                     me.arrayDataPrestamos = respuesta;

                     if(me.arrayDataPrestamos.data.length > 0){ // Mod
                        me.vista_tabla =1;
                     }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            isNumber: function(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                    evt.preventDefault();
                } else {
                    return true;
                }
            },
            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g,".")
            },
            abrirModal(accion,data=[]){
                switch(accion){
                    case 'monto_extra':{
                        this.modal = 3;
                        this.retencion_extra = {
                            monto_pago_extra: 0,
                            fecha_pago: '',
                        };
                        break;
                    }
                    case 'registrar':
                    {
                        this.modal=1;
                        this.modalVista='0';
                        this.nom_col(this.userId);
                        this.tituloModal='Nueva Solicitud';
                        //this.fecha_solic=anio+'-'+mes+'-'+dia;
                        break;
                    }
                    case 'ver':
                    {
                        this.modal=1;
                        this.modalVista='1';
                        this.id_prestamo=data['id'];
                        this.tituloModal='Solicitud';
                        this.status_rh=data['status_rh'];
                        this.firma_jefe=data['jefe_band'];
                        this.firma_rh=data['rh_band'];
                        this.firma_dir=data['dir_band'];
                        this.fecha_solic=data['fecha_ini'];
                        this.monto_solic=data['monto_solicitado'];
                        this.idJefe=data['jefe_id'];
                        this.nom_col(data['user_id']);
                        this.motivo=data['motivo'];
                        this.desc_quin=data['desc_quin'];
                        //this.getTablaPagos(data['id']);  // GENERAR OTRA FUNCION PARA CALCULAR FECHAS DE PAGO EN TABLA
                        this.arrayPagos = data['pagos'];
                        this.generar_tab=1;

                        //this.generarTablaPagos();
                        break;
                    }
                    case 'editar':
                    {
                        this.modal=1;
                        this.modalVista='2';
                        this.id_prestamo=data['id'];
                        this.tituloModal='Editar Solicitud';
                        this.fecha_solic=data['fecha_ini'];
                        this.monto_solic=data['monto_solicitado'];
                        this.idJefe=data['jefe_id'];
                        this.motivo=data['motivo'];
                        this.desc_quin=data['desc_quin'];
                        this.status_rh=data['status_rh'];
                        this.firma_jefe=data['jefe_band'];
                        this.firma_rh=data['rh_band'];
                        this.firma_dir=data['dir_band'];
                        this.id_usuario=data['user_id'];
                        this.nom_col(data['user_id']);
                        this.e_id_user=data['user_id'];
                        this.arrayPagos = data['pagos'];
                        this.generar_tab=1;
                        break;
                    }
                    case 'observaciones':
                    {
                        this.modal=2;
                        this.modalVista='1';
                        this.tituloModal='Observaciones';
                        this.id_prestamo=data['id'];
                        this.getObservaciones(this.id_prestamo);
                        this.obs_prestamo=null;

                        break;
                    }
                }
            },
            isGerenteCurrent_Id(){

                this.arrayIdGerentes.forEach(element => {
                    if(element.id == this.userId){
                        this.isGerenteCurrent = true;
                    }else{
                        if( element.user == this.userName){
                            this.isGerenteCurrent = true;
                        }
                    }

                });
            },
            isRHCurrent_Id(){
                this.isRHCurrent = this.arrayIdRH.includes(this.userId)
                if(!this.isRHCurrent)
                   this.isRHCurrent = this.arrayIdRH.includes(this.userName)
            },
            isDirecCurrent_Id(){
                this.isDireccionCurrent = this.arrayIdDir.includes(this.userId)
                if(!this.isDireccionCurrent)
                   this.isDireccionCurrent = this.arrayIdDir.includes(this.userName)
            },
            cerrarModal(){
                this.modal=null;
                this.id_prestamo=null;
                this.tituloModal=null;
                this.modalVista='0';
                this.fecha_solic=null;
                this.monto_solic=0;
                this.idJefe=null;
                this.motivo=null;
                this.desc_quin=null;
                this.e_id_user='';
                this.e_nombre='';
                this.status_rh=0;
                this.editAjuste=0;
                this.error_en_solicitud=0;
                this.arrayErrorSolicitud=[];
                this.id_usuario='';
                this.borrarTabla();
            },
            borrarTabla(){
                this.arrayPagos=[];
                this.saldoFaltante=0;
                this.pago_extraOrd=0;
                this.total=0;
                this.totalExtra=0;
            }
        },
        mounted() {
            this.isGerenteCurrent_Id();
            this.isRHCurrent_Id();
            this.isDirecCurrent_Id();
            this.dataPrestamos(1);
            this.getUsers();
        }
    }
</script>
<style scoped>
    body {
        margin: 0;
        font-family: -apple-system, BlinkMacSystemFont,"Segoe UI", Roboto,"Helvetica Neue", Arial, sans-serif;
        font-size: 0.875rem;
        font-weight: normal;
        line-height: 1.5;
        color: #151b1e;
        background-color: #e4e5e6;
    }
    .td2{
        white-space: nowrap;
        border-bottom: none;
        color: rgb(20, 20, 20);
    }

    button{
        font-family: -apple-system, BlinkMacSystemFont,"Segoe UI", Roboto,"Helvetica Neue", Arial, sans-serif;
    }

</style>
