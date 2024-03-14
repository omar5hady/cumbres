<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <strong><a style="color:#FFFFFF;" href="/">Home</a></strong>
            </li>
        </ol>

        <LoadingComponent v-if="loading" />
        <div class="container-fluid" v-else>
            <!-- Ejemplo de tabla Listado -->
            <div class="card scroll-box">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i>Devoluciones por
                    cancelación
                    <button
                        class="btn btn-danger"
                        v-if="listado == 1"
                        @click="(listado = 2), listarDevoluciones(1)"
                    >
                        Historial de devoluciones
                    </button>
                    <button
                        class="btn btn-warning"
                        v-if="listado == 2"
                        @click="listado = 1"
                    >
                        Volver a las devoluciones
                    </button>
                </div>

                <!-- listado de devoluciones -->
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control" v-model="b_empresa"
                                >
                                    <option value="">Empresa constructora</option>
                                    <option
                                        v-for="empresa in empresas"
                                        :key="empresa" :value="empresa" v-text="empresa"
                                    ></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-9">
                            <div class="input-group">
                                <!--Criterios para el listado de busqueda -->
                                <select
                                    class="form-control col-md-4"
                                    v-model="criterio"
                                    @change="selectFraccionamientos()"
                                >
                                    <option value="lotes.fraccionamiento_id"
                                        >Proyecto</option
                                    >
                                    <option value="personal.nombre"
                                        >Cliente</option
                                    >
                                    <option value="creditos.id"># Folio</option>
                                </select>

                                <select
                                    class="form-control"
                                    v-if="
                                        criterio == 'lotes.fraccionamiento_id'
                                    "
                                    v-model="buscar"
                                    @keyup.enter="listarContratos(1), listarDevoluciones(1)"
                                    @change="selectEtapa(buscar)"
                                >
                                    <option value="">Seleccione</option>
                                    <option
                                        v-for="fraccionamientos in arrayFraccionamientos"
                                        :key="fraccionamientos.id"
                                        :value="fraccionamientos.id"
                                        v-text="fraccionamientos.nombre"
                                    ></option>
                                </select>

                                <input
                                    type="text"
                                    class="form-control"
                                    v-if="
                                        criterio != 'lotes.fraccionamiento_id'
                                    "
                                    v-model="buscar"
                                    @keyup.enter="listarContratos(1), listarDevoluciones(1)"
                                    placeholder="Texto a Buscar"
                                />
                            </div>
                            <div class="input-group">
                                <select
                                    class="form-control"
                                    v-if="
                                        criterio == 'lotes.fraccionamiento_id'
                                    "
                                    v-model="b_etapa"
                                    @keyup.enter="listarContratos(1), listarDevoluciones(1)"
                                    @change="selectManzanas(buscar, b_etapa)"
                                >
                                    <option value="">Etapa</option>
                                    <option
                                        v-for="etapas in arrayEtapas"
                                        :key="etapas.id"
                                        :value="etapas.id"
                                        v-text="etapas.num_etapa"
                                    ></option>
                                </select>

                                <select
                                    class="form-control"
                                    v-if="
                                        criterio == 'lotes.fraccionamiento_id'
                                    "
                                    v-model="b_manzana"
                                >
                                    <option value="">Seleccione</option>
                                    <option
                                        v-for="manzana in arrayManzanas"
                                        :key="manzana.manzana"
                                        :value="manzana.manzana"
                                        v-text="manzana.manzana"
                                    ></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <template v-if="listado == 1">
                        <div class="form-group row">
                            <div class="col-md-5">
                                <div class="input-group">
                                    <select
                                        class="form-control"
                                        v-model="b_status"
                                    >
                                        <option value="">Estatus</option>
                                        <option value="Pendiente"
                                            >Pendiente</option
                                        >
                                        <option value="Solicitado"
                                            >Solicitado</option
                                        >
                                        <option value="Dictaminado"
                                            >Dictaminado</option
                                        >
                                        <option value="Fondeo Parcial"
                                            >Fondeo Parcial</option
                                        >
                                        <option value="Fondeado"
                                            >Fondeado</option
                                        >
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <button
                                        type="submit"
                                        @click="listarContratos(1)"
                                        class="btn btn-primary"
                                    >
                                        <i class="fa fa-search"></i> Buscar
                                    </button>
                                    <a
                                        :href="
                                            '/devolucion/excel?buscar=' +
                                                buscar +
                                                '&b_etapa=' +
                                                b_etapa +
                                                '&b_manzana=' +
                                                b_manzana +
                                                '&b_lote=' +
                                                b_lote +
                                                '&criterio=' +
                                                criterio +
                                                '&b_empresa=' +
                                                b_empresa
                                        "
                                        class="btn btn-success"
                                        ><i class="fa fa-file-text"></i> Excel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </template>
                    <div class="form-group row" v-if="listado == 2">
                        <div class="col-md-8">
                            <div class="input-group">
                                <button
                                    type="submit"
                                    @click="listarDevoluciones(1)"
                                    class="btn btn-primary"
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                                <a
                                    :href="
                                        '/devoluciones/excel?buscar=' +
                                            buscar +
                                            '&b_etapa_d=' +
                                            b_etapa +
                                            '&b_manzana_d=' +
                                            b_manzana +
                                            '&b_lote=' +
                                            b_lote +
                                            '&criterio_d=' +
                                            criterio +
                                            '&b_empresa=' +
                                            b_empresa
                                    "
                                    class="btn btn-success"
                                    ><i class="fa fa-file-text"></i> Excel
                                </a>
                            </div>
                        </div>
                    </div>
                    <template v-if="listado == 1">
                        <TableComponent
                            :cabecera="[
                                '',
                                '# Ref',
                                'Cliente ',
                                'Proyecto',
                                'Etapa',
                                'Manzana',
                                'Lote',
                                'Depositos',
                                'Pendiente Devolver',
                                'Fecha cancelación',
                                'Observaciones'
                            ]"
                        >
                            <template v-slot:tbody>
                                <tr
                                    v-for="contrato in arrayContratos.data"
                                    :key="contrato.id"
                                >
                                    <template v-if="contrato.devolver > 0">
                                        <td class="td2">
                                            <button
                                                v-if="
                                                    contrato.status_devolucion ==
                                                        'Pendiente'
                                                "
                                                class="btn btn-primary"
                                                @click="
                                                    cambiarStatus(
                                                        contrato,
                                                        'Solicitado'
                                                    )
                                                "
                                            >
                                                Solicitar
                                            </button>
                                            <button
                                                v-if="
                                                    contrato.status_devolucion ==
                                                        'Solicitado'
                                                "
                                                class="btn btn-primary"
                                                @click="
                                                    cambiarStatus(
                                                        contrato,
                                                        'Dictaminar'
                                                    )
                                                "
                                            >
                                                Dictaminar
                                            </button>
                                            <button
                                                v-if="
                                                    contrato.status_devolucion ==
                                                        'Dictaminado'
                                                "
                                                class="btn btn-primary"
                                                @click="
                                                    cambiarStatus(
                                                        contrato,
                                                        'Fondeo Parcial'
                                                    )
                                                "
                                            >
                                                Fondeo parcial
                                            </button>
                                            <button
                                                v-if="
                                                    contrato.status_devolucion ==
                                                        'Dictaminado' ||
                                                        contrato.status_devolucion ==
                                                            'Fondeo Parcial'
                                                "
                                                class="btn btn-success"
                                                @click="
                                                    cambiarStatus(
                                                        contrato,
                                                        'Fondeado'
                                                    )
                                                "
                                            >
                                                Fondeo completo
                                            </button>
                                        </td>
                                        <td class="td2">
                                            <a
                                                class="nav-link dropdown-toggle"
                                                data-toggle="dropdown"
                                                href="#"
                                                role="button"
                                                aria-haspopup="false"
                                                aria-expanded="false"
                                                >{{ contrato.id }}</a
                                            >
                                            <div
                                                class="dropdown-menu"
                                                x-placement="bottom-start"
                                                style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);"
                                            >
                                                <a title="Click para crear devolución"
                                                    class="dropdown-item" @click="abrirPDF(contrato.id)" >Estado de cuenta</a>
                                            </div>
                                        </td>
                                        <td class="td2">
                                            <a
                                                v-if="
                                                    contrato.status_devolucion ==
                                                        'Fondeado'
                                                "
                                                @click="
                                                    abrirModal(
                                                        'devolucion',
                                                        contrato
                                                    )
                                                "
                                                href="#"
                                                v-text="contrato.nombre_cliente"
                                            ></a>
                                            <label v-else>{{
                                                contrato.nombre_cliente
                                            }}</label>
                                        </td>
                                        <td
                                            class="td2"
                                            v-text="contrato.proyecto"
                                        ></td>
                                        <td
                                            class="td2"
                                            v-text="contrato.etapa"
                                        ></td>
                                        <td
                                            class="td2"
                                            v-text="contrato.manzana"
                                        ></td>
                                        <td
                                            class="td2"
                                            v-text="contrato.num_lote"
                                        ></td>
                                        <td
                                            class="td2"
                                            v-text="
                                                '$' +
                                                    $root.formatNumber(
                                                        contrato.sumaPagares -
                                                            contrato.sumaRestante
                                                    )
                                            "
                                        ></td>
                                        <td
                                            class="td2"
                                            v-text="
                                                '$' +
                                                    $root.formatNumber(
                                                        contrato.devolver
                                                    )
                                            "
                                        ></td>
                                        <td
                                            class="td2"
                                            v-text="
                                                this.moment(
                                                    contrato.fecha_status
                                                )
                                                    .locale('es')
                                                    .format('DD/MMM/YYYY')
                                            "
                                        ></td>
                                        <td class="td2">
                                            <button
                                                title="Ver todas las observaciones"
                                                type="button"
                                                class="btn btn-info pull-right"
                                                @click="
                                                    abrirModal(
                                                        'observaciones',
                                                        contrato
                                                    )
                                                "
                                            >
                                                Observaciones
                                            </button>
                                        </td>
                                    </template>
                                </tr>
                            </template>
                        </TableComponent>
                        <div class="container" v-if="arrayContratos">
                            <NavComponent
                                :current="
                                    arrayContratos.current_page
                                        ? arrayContratos.current_page
                                        : 1
                                "
                                :last="
                                    arrayContratos.last_page
                                        ? arrayContratos.last_page
                                        : 1
                                "
                                @changePage="listarContratos"
                            ></NavComponent>
                        </div>
                    </template>
                    <template v-if="listado == 2">
                        <div class="container" v-if="arrayDevoluciones">
                            <TableComponent
                                :cabecera="[
                                    '# Ref',
                                    'Cliente ',
                                    'Proyecto',
                                    'Etapa',
                                    'Manzana',
                                    'Lote',
                                    'Depositos',
                                    'Pendiente Devolver',
                                    'Fecha cancelación',
                                    'Observaciones'
                                ]"
                            >
                                <template v-slot:tbody>
                                    <tr
                                        v-for="(devoluciones,
                                        index) in arrayDevoluciones.data"
                                        :key="index"
                                        @dblclick="
                                            abrirModal('info', devoluciones)
                                        "
                                        title="Doble click"
                                    >
                                        <template
                                            v-if="
                                                devoluciones.sumaPagares -
                                                    devoluciones.sumaRestante >
                                                    0
                                            "
                                        >
                                            <td class="td2">
                                                <a
                                                    class="nav-link dropdown-toggle"
                                                    data-toggle="dropdown"
                                                    href="#"
                                                    role="button"
                                                    aria-haspopup="false"
                                                    aria-expanded="false"
                                                    >{{ devoluciones.id }}</a
                                                >
                                                <div
                                                    class="dropdown-menu"
                                                    x-placement="bottom-start"
                                                    style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);"
                                                >
                                                    <a
                                                        class="dropdown-item"
                                                        @click="
                                                            abrirPDF(
                                                                devoluciones.id
                                                            )
                                                        "
                                                        >Estado de cuenta</a
                                                    >
                                                </div>
                                            </td>
                                            <td class="td2">
                                                <a
                                                    href="#"
                                                    v-text="
                                                        devoluciones.nombre_cliente
                                                    "
                                                ></a>
                                            </td>
                                            <td
                                                class="td2"
                                                v-text="devoluciones.proyecto"
                                            ></td>
                                            <td
                                                class="td2"
                                                v-text="devoluciones.etapa"
                                            ></td>
                                            <td
                                                class="td2"
                                                v-text="devoluciones.manzana"
                                            ></td>
                                            <td
                                                class="td2"
                                                v-text="devoluciones.num_lote"
                                            ></td>
                                            <td
                                                class="td2"
                                                v-text="
                                                    '$' +
                                                        $root.formatNumber(
                                                            devoluciones.devolver
                                                        )
                                                "
                                            ></td>
                                            <td
                                                class="td2"
                                                v-text="
                                                    this.moment( devoluciones.fecha_status )
                                                        .locale('es')
                                                        .format('DD/MMM/YYYY')
                                                "
                                            ></td>
                                            <td
                                                class="td2"
                                                v-text="
                                                    this.moment( devoluciones.fecha )
                                                        .locale('es')
                                                        .format('DD/MMM/YYYY')
                                                "
                                            ></td>
                                            <td
                                                class="td2"
                                                v-text="devoluciones.cheque"
                                            ></td>
                                            <td
                                                class="td2"
                                                v-text="devoluciones.cuenta"
                                            ></td>
                                            <td class="td2">
                                                <button
                                                    title="Ver todas las observaciones"
                                                    type="button"
                                                    class="btn btn-info pull-right"
                                                    @click="
                                                        abrirModal(
                                                            'observaciones',
                                                            devoluciones
                                                        )
                                                    "
                                                >
                                                    Observaciones
                                                </button>
                                            </td>
                                            <td></td>
                                        </template>
                                    </tr>
                                </template>
                            </TableComponent>
                            <NavComponent
                                :current="
                                    arrayDevoluciones.current_page
                                        ? arrayDevoluciones.current_page
                                        : 1
                                "
                                :last="
                                    arrayDevoluciones.last_page
                                        ? arrayDevoluciones.last_page
                                        : 1
                                "
                                @changePage="listarDevoluciones"
                            ></NavComponent>
                        </div>
                    </template>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>

        <!--Inicio del modal -->
        <ModalDevolucionCancelacion
            v-if="modal == 1"
            :titulo="tituloModal"
            :data="datos"
            :tipoAccion="tipoAccion"
            @close="cerrarModal"
        ></ModalDevolucionCancelacion>
        <!--Fin del modal consulta-->

        <!--Inicio del modal observaciones-->
        <ModalComponent
            v-if="modal == 2"
            :titulo="tituloModal"
            @closeModal="cerrarModal()"
        >
            <template v-slot:body>
                <RowModal
                    clsRow1="col-md-4"
                    label1="Celular"
                    label2="Telefono"
                    clsRow2="col-md-3"
                >
                    <input
                        type="text" disabled class="form-control"
                        v-model="datos.celular"
                    />
                    <template v-slot:input2>
                        <input
                            type="text"
                            disabled
                            class="form-control"
                            v-model="datos.telefono"
                        />
                    </template>
                </RowModal>

                <RowModal clsRow1="col-md-6" label1="Email">
                    <input
                        type="text" disabled class="form-control"
                        v-model="datos.email"
                    />
                </RowModal>
                <RowModal label1="Vendedor:" clsRow1="col-md-4">
                    <input type="text" class="form-control" v-model="datos.vendedor" disabled/>
                </RowModal>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input"
                        >Observacion</label
                    >
                    <div class="col-md-6">
                        <textarea
                            rows="3" cols="30" v-model="observacion"
                            class="form-control" placeholder="Observacion"
                        ></textarea>
                    </div>
                    <div class="col-md-2">
                        <button
                            type="button"
                            class="btn btn-primary"
                            @click="agregarComentario(observacion)"
                        >
                            Guardar
                        </button>
                    </div>
                </div>

                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Observacion</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="observacion in arrayObservacion"
                            :key="observacion.id"
                        >
                            <td v-text="observacion.usuario"></td>
                            <td v-text="observacion.observacion"></td>
                            <td v-text="observacion.created_at"></td>
                        </tr>
                    </tbody>
                </table>
            </template>
        </ModalComponent>

        <ModalComponent
            v-if="modal == 3"
            :titulo="tituloModal"
            @closeModal="cerrarModal()"
        >
            <template v-slot:body>
                <RowModal clsRow1="col-md-6" label1="Cantidad a Devolver:">
                    <h5 class="form-control">
                        {{ $root.formatNumber(datos.devolver) }}
                    </h5>
                </RowModal>

                <RowModal
                    clsRow1="col-md-4"
                    label1="Monto en efectivo:"
                    label2=""
                    clsRow2="col-md-3"
                >
                    <input
                        type="text"
                        pattern="\d*"
                        @keypress="$root.isNumber($event)"
                        v-model="datos.cant_efectivo"
                        @change="verificarEfectivo"
                        class="form-control"
                        placeholder="Monto"
                    />
                    <template v-slot:input2>
                        <h6>{{ $root.formatNumber(datos.cant_efectivo) }}</h6>
                    </template>
                </RowModal>
                <RowModal
                    clsRow1="col-md-4"
                    label1="Monto en cheque:"
                    label2=""
                    clsRow2="col-md-3"
                >
                    <input
                        type="text"
                        pattern="\d*"
                        @keypress="$root.isNumber($event)"
                        @change="verificarCheque"
                        v-model="datos.cant_cheque"
                        class="form-control"
                        placeholder="Monto"
                    />
                    <template v-slot:input2>
                        <h6>{{ $root.formatNumber(datos.cant_cheque) }}</h6>
                    </template>
                </RowModal>
            </template>
            <template v-slot:buttons-footer>
                <button class="btn btn-primary" @click="dictaminar()">
                    Dictaminar
                </button>
            </template>
        </ModalComponent>
    </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
import ModalComponent from "./Componentes/ModalComponent";
import RowModal from "./Componentes/ComponentesModal/RowModalComponent";
import TableComponent from "./Componentes/TableComponent";
import NavComponent from "./Componentes/NavComponent";
import ModalDevolucionCancelacion from "./Saldos/components/ModalDevolucionCancelacion";
import LoadingComponent from "./Componentes/LoadingComponent";
export default {
    props: {
        userName: { type: String }
    },
    components: {
        ModalComponent,
        RowModal,
        TableComponent,
        NavComponent,
        ModalDevolucionCancelacion,
        LoadingComponent
    },
    data() {
        return {
            proceso: false,
            loading: false,
            id: 0,

            arrayContratos: [],
            arrayFraccionamientos: [],
            arrayEtapas: [],
            arrayManzanas: [],
            arrayDevoluciones: [],
            arrayObservacion: [],

            errorDev: 0,
            errorMostrarMsjDev: [],

            modal: 0,
            observacion: "",
            tituloModal: "",
            tipoAccion: 0,
            criterio: "lotes.fraccionamiento_id",
            buscar: "",
            b_etapa: "",
            b_manzana: "",
            b_lote: "",
            listado: 1,
            b_empresa: "",
            b_status: "",
            empresas: [],
            datos: {
                cant_cheque: 0,
                cant_efectivo: 0
            }
        };
    },
    methods: {
        verificarEfectivo() {
            if (this.datos.cant_cheque == "" || this.datos.cant_cheque <= 0)
                this.datos.cant_cheque = 0;
            let saldo = this.datos.devolver - this.datos.cant_cheque;
            if (this.datos.cant_efectivo > saldo)
                this.datos.cant_efectivo = saldo;
        },
        verificarCheque() {
            if (this.datos.cant_efectivo == "" || this.datos.cant_efectivo <= 0)
                this.datos.cant_efectivo = 0;
            let saldo = this.datos.devolver - this.datos.cant_efectivo;
            if (this.datos.cant_cheque > saldo) this.datos.cant_cheque = saldo;
        },
        /**Metodo para mostrar los registros */
        listarContratos(page) {
            let me = this;
            me.loading = true;
            const url =
                "/devolucion/index?page=" +
                page +
                "&buscar=" +
                me.buscar +
                "&b_etapa=" +
                me.b_etapa +
                "&b_manzana=" +
                me.b_manzana +
                "&b_lote=" +
                me.b_lote +
                "&criterio=" +
                me.criterio +
                "&b_empresa=" +
                me.b_empresa +
                "&b_status=" +
                me.b_status;
            axios
                .get(url)
                .then(function(response) {
                    const respuesta = response.data;
                    me.arrayContratos = respuesta.contratos;
                    me.loading = false;
                })
                .catch(function(error) {
                    console.log(error);
                    me.loading = false;
                });
        },

        listarDevoluciones(page) {
            let me = this;
            me.loading = true;
            var url =
                "/devolucion/indexDevoluciones?page=" +
                page +
                "&buscar=" +
                me.buscar +
                "&b_etapa=" +
                me.b_etapa +
                "&b_manzana=" +
                me.b_manzana +
                "&b_lote=" +
                me.b_lote +
                "&criterio=" +
                me.criterio +
                "&b_empresa=" +
                me.b_empresa;
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.arrayDevoluciones = respuesta.devoluciones;
                    me.loading = false;
                })
                .catch(function(error) {
                    console.log(error);
                    me.loading = false;
                });
        },

        async dictaminar() {
            await this.cambiarStatus(this.datos, "Dictaminado");
            if (parseFloat(this.datos.cant_cheque) > 0) {
                const observacion = `Se dictamina solicitud por un monto en cheque de: ${
                    this.datos.cant_cheque
                }`;
                await this.agregarComentario(observacion);
            }
            if (parseFloat(this.datos.cant_efectivo) > 0) {
                const observacion = `Se dictamina solicitud por un monto en efectivo de: ${
                    this.datos.cant_efectivo
                }`;
                await this.agregarComentario(observacion);
            }
            this.cerrarModal();
        },

        listarObservacion(buscar) {
            let me = this;
            var url = "/devoluciones/listarObservacionesCanc?id=" + buscar;
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.arrayObservacion = respuesta.observacion;
                    console.log(url);
                })
                .catch(function(error) {
                    console.log(error);
                });
        },

        agregarComentario(observacion) {
            let me = this;
            //Con axios se llama el metodo store de DepartamentoController
            axios
                .post("/devoluciones/storeObservacionCanc", {
                    id: this.datos.id,
                    observacion: observacion
                })
                .then(function(response) {
                    me.listarObservacion(me.datos.id);
                    me.observacion = "";
                    //me.cerrarModal3(); //al guardar el registro se cierra el modal

                    const toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000
                    });

                    toast({
                        type: "success",
                        title: "Observación Agregada Correctamente"
                    });
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        cambiarStatus(contrato, status) {
            let me = this;
            if (status == "Dictaminar") {
                this.abrirModal("dictaminar", contrato);
                return;
            }
            axios
                .put("/devoluciones/cambiarStatus", {
                    status: status,
                    id: contrato.id
                })
                .then(function(response) {
                    me.listarContratos(me.arrayContratos.current_page);
                    const toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000
                    });

                    toast({
                        type: "success",
                        title: "Cambios guardados"
                    });
                })
                .catch(function(error) {
                    console.log(error);
                });
        },

        selectFraccionamientos() {
            let me = this;
            me.buscar = "";

            me.arrayFraccionamientos = [];
            var url = "/select_fraccionamiento";
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.arrayFraccionamientos = respuesta.fraccionamientos;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },

        selectEtapa(buscar) {
            let me = this;
            me.buscar2 = "";

            me.arrayEtapas = [];
            var url = "/select_etapa_proyecto?buscar=" + buscar;
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.arrayEtapas = respuesta.etapas;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },

        selectManzanas(buscar1, buscar2) {
            let me = this;
            me.b_manzana = "";
            me.b_lote = "";

            me.arrayManzanas = [];
            var url =
                "/select_manzanas_etapa?buscar=" +
                buscar1 +
                "&buscar1=" +
                buscar2;
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.arrayManzanas = respuesta.manzana;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },

        abrirPDF(id) {
            const win = window.open("/estadoCuenta/estadoPDF/" + id, "_blank");
            win.focus();
        },

        cerrarModal() {
            this.modal = 0;
            this.tituloModal = "";
            this.datos = {
                cant_cheque: 0,
                cant_efectivo: 0
            };
            this.listarContratos(1);
            this.listarDevoluciones(1);
        },

        abrirModal(accion, data = []) {
            switch (accion) {
                case "devolucion": {
                    this.tituloModal = "Generar devolución";
                    this.datos.id = data["id"];
                    this.datos.proyecto = data["proyecto"];
                    this.datos.etapa = data["etapa"];
                    this.datos.manzana = data["manzana"];
                    this.datos.lote = data["num_lote"];
                    this.datos.cliente = data["nombre_cliente"];
                    this.datos.depositos =
                        data["sumaPagares"] - data["sumaRestante"];
                    this.datos.devolver = 0;
                    this.datos.cheque = "";
                    this.datos.observaciones = "";
                    this.datos.cant_dev = 0;
                    this.datos.descuento = data["descuento"];
                    this.datos.obs_descuento = data["obs_descuento"];
                    this.datos.arrayGastos = [];
                    this.tipoAccion = 1;
                    this.modal = 1;
                    break;
                }

                case "info": {
                    this.tituloModal = "Devolución";
                    this.tipoAccion = 2;
                    this.datos.id = data["id"];
                    this.datos.proyecto = data["proyecto"];
                    this.datos.etapa = data["etapa"];
                    this.datos.manzana = data["manzana"];
                    this.datos.lote = data["num_lote"];
                    this.datos.cliente = data["nombre_cliente"];
                    this.datos.depositos =
                        data["sumaPagares"] - data["sumaRestante"];
                    this.datos.devolver = data["devolver"];
                    this.datos.cant_dev = data["devolver"];
                    this.datos.cheque = data["cheque"];
                    this.datos.observaciones = data["observaciones"];
                    this.datos.fecha_devolucion = data["fecha"];
                    this.datos.banco = data["cuenta"];
                    this.datos.arrayGastos = [];
                    this.modal = 1;
                    break;
                }

                case "dictaminar": {
                    this.datos.id = data["id"];
                    this.datos.devolver = data["devolver"];
                    this.datos.cant_efectivo = 0;
                    this.datos.cant_cheque = 0;
                    this.modal = 3;
                    this.tituloModal = "Dictaminar Solicitud";
                    break;
                }

                case "observaciones": {
                    this.modal = 2;
                    this.tituloModal = "Observaciones";
                    this.observacion = "";
                    this.datos.id = data["id"];
                    this.datos.email = data["email"];
                    this.datos.telefono = data["telefono"];
                    this.datos.celular = data["celular"];
                    this.datos.vendedor = data['nombre_vendedor'];
                    this.listarObservacion(this.datos.id);
                    break;
                }
            }
        },
        getEmpresa() {
            let me = this;
            me.empresas = [];
            var url = "/lotes/empresa/select";
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response;
                    me.empresas = respuesta.data.empresas;
                })
                .catch(function(error) {
                    console.log(error);
                });
        }
    },
    mounted() {
        this.listarContratos(1);
        this.selectFraccionamientos();
        this.getEmpresa();
    }
};
</script>
<style>
.line-separator {
    height: 1px;
    background: #717171;
    border-bottom: 1px solid #c2cfd6;
}
.form-control:disabled,
.form-control[readonly] {
    background-color: rgba(0, 0, 0, 0.06);
    opacity: 1;
    font-size: 1rem;
    color: #27417b;
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
</style>
