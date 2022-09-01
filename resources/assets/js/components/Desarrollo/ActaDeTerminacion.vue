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
                    <i class="fa fa-align-justify"></i>Acta de terminacion

                    <!--   Boton   -->
                    <button
                        class="btn btn-dark"
                        @click="abrirModal('actasMasa')"
                        v-if="allLic.length > 0"
                    >
                        <i class="fa fa-drivers-license-o "></i>&nbsp;Asignar
                        actas
                    </button>
                    <!---->
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-8">
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
                                    <option value="licencias.term_ingreso"
                                        >Fecha ingreso</option
                                    >
                                    <option value="licencias.term_salida"
                                        >Fecha salida</option
                                    >
                                    <option value="licencias.avance"
                                        >Porcentaje de avance</option
                                    >
                                    <option value="licencias.perito_dro"
                                        >DRO</option
                                    >
                                </select>
                                <select
                                    class="form-control"
                                    v-if="criterio == 'licencias.perito_dro'"
                                    v-model="buscar"
                                >
                                    <option value="">Seleccione</option>
                                    <option value="15044"
                                        >Ing. Alejandro F. Perez
                                        Espinosa</option
                                    >
                                    <option value="23679"
                                        >Raúl Palos López</option
                                    >
                                </select>
                                <select
                                    class="form-control"
                                    v-if="
                                        criterio == 'lotes.fraccionamiento_id'
                                    "
                                    v-model="buscar"
                                    @change="
                                        selectPuente(buscar),
                                            selectEtapas(buscar)
                                    "
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
                                    type="date"
                                    v-if="
                                        criterio == 'licencias.term_ingreso' ||
                                            criterio == 'licencias.term_salida'
                                    "
                                    v-model="buscar"
                                    @keyup.enter="
                                        listarActa(
                                            1,
                                            buscar,
                                            b_manzana,
                                            b_lote,
                                            criterio,
                                            buscar2
                                        )
                                    "
                                    class="form-control col-md-6"
                                    placeholder="Desde"
                                />
                                <input
                                    type="date"
                                    v-if="
                                        criterio == 'licencias.term_ingreso' ||
                                            criterio == 'licencias.term_salida'
                                    "
                                    v-model="buscar2"
                                    @keyup.enter="
                                        listarActa(
                                            1,
                                            buscar,
                                            b_manzana,
                                            b_lote,
                                            criterio,
                                            buscar2
                                        )
                                    "
                                    class="form-control col-md-6"
                                    placeholder="Hasta"
                                />

                                <input
                                    v-if="
                                        criterio !=
                                            'lotes.fraccionamiento_id' &&
                                            criterio !=
                                                'licencias.term_ingreso' &&
                                            criterio !=
                                                'licencias.term_salida' &&
                                            criterio != 'licencias.perito_dro'
                                    "
                                    type="text"
                                    v-model="buscar"
                                    @keyup.enter="
                                        listarActa(
                                            1,
                                            buscar,
                                            b_manzana,
                                            b_lote,
                                            criterio,
                                            buscar2
                                        )
                                    "
                                    class="form-control"
                                    placeholder="Texto a buscar"
                                />
                            </div>
                            <div
                                class="input-group"
                                v-if="criterio == 'lotes.fraccionamiento_id'"
                            >
                                <select
                                    class="form-control col-md-10"
                                    v-model="b_etapa"
                                >
                                    <option value="">Etapa</option>
                                    <option
                                        v-for="etapa in arrayAllEtapas"
                                        :key="etapa.id"
                                        :value="etapa.id"
                                        v-text="etapa.num_etapa"
                                    ></option>
                                </select>
                            </div>
                            <div
                                class="input-group"
                                v-if="criterio == 'lotes.fraccionamiento_id'"
                            >
                                <!--Criterios para el listado de busqueda -->
                                <input
                                    v-if="
                                        criterio == 'lotes.fraccionamiento_id'
                                    "
                                    type="text"
                                    v-model="b_manzana"
                                    @keyup.enter="
                                        listarActa(
                                            1,
                                            buscar,
                                            b_manzana,
                                            b_lote,
                                            criterio,
                                            buscar2
                                        )
                                    "
                                    class="form-control"
                                    placeholder="Manzana"
                                />
                                <input
                                    v-if="
                                        criterio == 'lotes.fraccionamiento_id'
                                    "
                                    type="text"
                                    v-model="b_lote"
                                    @keyup.enter="
                                        listarActa(
                                            1,
                                            buscar,
                                            b_manzana,
                                            b_lote,
                                            criterio,
                                            buscar2
                                        )
                                    "
                                    class="form-control"
                                    placeholder="# Lote"
                                />
                            </div>
                            <div class="input-group">
                                <select
                                    class="form-control"
                                    v-model="b_empresa"
                                >
                                    <option value=""
                                        >Empresa constructora</option
                                    >
                                    <option
                                        v-for="empresa in empresas"
                                        :key="empresa"
                                        :value="empresa"
                                        v-text="empresa"
                                    ></option>
                                </select>
                                <select
                                    class="form-control"
                                    v-model="b_empresa2"
                                >
                                    <option value="">Empresa terreno</option>
                                    <option
                                        v-for="empresa in empresas"
                                        :key="empresa"
                                        :value="empresa"
                                        v-text="empresa"
                                    ></option>
                                </select>
                            </div>
                            <div
                                class="input-group"
                                v-if="criterio == 'lotes.fraccionamiento_id'"
                            >
                                <select
                                    class="form-control"
                                    v-model="b_puente"
                                    @keyup.enter="
                                        listarLicencias(
                                            1,
                                            buscar,
                                            b_manzana,
                                            b_lote,
                                            b_modelo,
                                            b_arquitecto,
                                            criterio,
                                            buscar2
                                        )
                                    "
                                >
                                    <option value="">Credito Puente</option>
                                    <option
                                        v-for="puente in arrayPuentes"
                                        :key="puente.credito_puente"
                                        :value="puente.credito_puente"
                                        v-text="puente.credito_puente"
                                    ></option>
                                </select>
                                <input
                                    type="text"
                                    v-model="b_num_inicio"
                                    @keyup.enter="
                                        listarActa(
                                            1,
                                            buscar,
                                            b_manzana,
                                            b_lote,
                                            criterio,
                                            buscar2
                                        )
                                    "
                                    class="form-control"
                                    placeholder="# inicio de obra"
                                />
                            </div>
                            <div class="input-group">
                                <button
                                    type="submit"
                                    @click="
                                        listarActa(
                                            1,
                                            buscar,
                                            b_manzana,
                                            b_lote,
                                            criterio,
                                            buscar2
                                        )
                                    "
                                    class="btn btn-primary"
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                                <a
                                    class="btn btn-success"
                                    v-bind:href="
                                        '/acta_terminacion/excel?buscar=' +
                                            buscar +
                                            '&b_manzana=' +
                                            b_manzana +
                                            '&b_lote=' +
                                            b_lote +
                                            '&criterio=' +
                                            criterio +
                                            '&buscar2=' +
                                            buscar2 +
                                            '&b_puente=' +
                                            b_puente +
                                            '&b_num_inicio=' +
                                            b_num_inicio +
                                            '&b_empresa=' +
                                            b_empresa +
                                            '&b_empresa2=' +
                                            b_empresa2 +
                                            '&b_etapa=' +
                                            b_etapa
                                    "
                                >
                                    <i class="icon-pencil"></i>&nbsp;Excel
                                </a>
                            </div>
                        </div>
                    </div>
                    <TableComponent>
                        <template v-slot:thead>
                            <tr>
                                <th colspan="9"></th>
                                <th colspan="2" class="text-center">Inicio</th>
                                <th colspan="5"></th>
                            </tr>
                            <tr>
                                <th>
                                    <input
                                        type="checkbox"
                                        @click="selectAll"
                                        v-model="allSelected"
                                    />
                                </th>
                                <th>Opciones</th>
                                <th>Proyecto</th>
                                <th>Etapa</th>
                                <th>Manzana</th>
                                <th># Lote</th>
                                <th>Terreno mts&sup2;</th>
                                <th>Construcción mts&sup2;</th>
                                <th>DRO</th>
                                <th>Modelo</th>
                                <th>No.</th>
                                <th>Fecha</th>
                                <th>Avance</th>
                                <th>Ingreso Act. terminacion</th>
                                <th>Salida Act. terminacion</th>
                                <th>No. Acta</th>
                                <th>Credito puente</th>
                            </tr>
                        </template>
                        <template v-slot:tbody>
                            <tr
                                v-for="act_terminacion in arrayActaDeTerminacion"
                                :key="act_terminacion.id"
                            >
                                <td class="td2">
                                    <input
                                        type="checkbox"
                                        @click="select"
                                        :id="act_terminacion.id"
                                        :value="act_terminacion.id"
                                        v-model="allLic"
                                    />
                                </td>
                                <td class="td2">
                                    <button
                                        title="Editar"
                                        type="button"
                                        @click="
                                            abrirModal(
                                                'actualizar',
                                                act_terminacion
                                            )
                                        "
                                        class="btn btn-warning btn-sm"
                                    >
                                        <i class="icon-pencil"></i>
                                    </button>
                                    <button
                                        title="Subir foto acta"
                                        type="button"
                                        @click="
                                            abrirModal(
                                                'subirArchivo',
                                                act_terminacion
                                            )
                                        "
                                        class="btn btn-default btn-sm"
                                    >
                                        <i class="icon-cloud-upload"></i>
                                    </button>
                                </td>
                                <td class="td2">
                                    <a
                                        @click="
                                            abrirModal('ver', act_terminacion)
                                        "
                                        title="Ver Detalle"
                                        href="#"
                                        v-text="act_terminacion.proyecto"
                                    ></a>
                                </td>
                                <td class="td2">
                                    <a
                                        @click="
                                            abrirModal('ver', act_terminacion)
                                        "
                                        title="Ver Detalle"
                                        href="#"
                                        v-text="act_terminacion.num_etapa"
                                    ></a>
                                </td>
                                <td
                                    class="td2"
                                    v-text="act_terminacion.manzana"
                                ></td>
                                <td
                                    class="td2"
                                    v-text="act_terminacion.num_lote"
                                ></td>
                                <td
                                    class="td2"
                                    v-text="act_terminacion.terreno"
                                ></td>
                                <td
                                    class="td2"
                                    v-text="act_terminacion.construccion"
                                ></td>
                                <td class="td2">
                                    <span
                                        class="badge"
                                        :class="
                                            act_terminacion.perito !=
                                            'Sin Asignar  '
                                                ? 'badge-success'
                                                : 'badge-danger'
                                        "
                                    >
                                        {{
                                            act_terminacion.perito !=
                                            "Sin Asignar  "
                                                ? act_terminacion.perito
                                                : "Por asignar"
                                        }}
                                    </span>
                                </td>
                                <!--Modelo-->
                                <td class="td2">
                                    <span
                                        v-if="
                                            act_terminacion.modelo !=
                                                'Por Asignar' &&
                                                act_terminacion.cambios == 0
                                        "
                                        class="badge badge-success"
                                        v-text="act_terminacion.modelo"
                                    ></span>
                                    <span
                                        v-if="
                                            act_terminacion.modelo ==
                                                'Por Asignar'
                                        "
                                        class="badge badge-danger"
                                        >Por Asignar</span
                                    >
                                    <span
                                        v-if="act_terminacion.cambios == 1"
                                        class="badge badge-warning"
                                        v-text="act_terminacion.modelo"
                                    ></span>
                                </td>
                                <td class="td2">
                                    {{
                                        !act_terminacion.siembra
                                            ? ""
                                            : act_terminacion.num_inicio
                                    }}
                                </td>
                                <td class="td2">
                                    {{
                                        !act_terminacion.siembra
                                            ? ""
                                            : this.moment(
                                                  act_terminacion.siembra
                                              )
                                                  .locale("es")
                                                  .format("DD/MMM/YYYY")
                                    }}
                                </td>
                                <!--Avance-->
                                <td
                                    class="td2"
                                    v-text="act_terminacion.avance + '%'"
                                ></td>
                                <!-- Fecha Ingreso -->
                                <td class="td2">
                                    {{
                                        !act_terminacion.term_ingreso
                                            ? ""
                                            : this.moment(
                                                  act_terminacion.term_ingreso
                                              )
                                                  .locale("es")
                                                  .format("DD/MMM/YYYY")
                                    }}
                                </td>
                                <!-- Fecha Salida -->
                                <td class="td2">
                                    {{
                                        !act_terminacion.term_salida
                                            ? ""
                                            : this.moment(
                                                  act_terminacion.term_salida
                                              )
                                                  .locale("es")
                                                  .format("DD/MMM/YYYY")
                                    }}
                                </td>

                                <td class="td2">
                                    <span v-if="!act_terminacion.foto_acta">{{
                                        act_terminacion.num_acta
                                    }}</span>
                                    <a
                                        title="Descargar acta"
                                        v-else
                                        class="btn btn-primary btn-sm"
                                        v-text="act_terminacion.num_acta"
                                        v-bind:href="
                                            '/downloadActa/' +
                                                act_terminacion.foto_acta
                                        "
                                    ></a>
                                </td>
                                <td
                                    class="td2"
                                    v-text="act_terminacion.credito_puente"
                                ></td>
                            </tr>
                        </template>
                    </TableComponent>
                    <nav>
                        <!--Botones de paginacion -->
                        <ul class="pagination">
                            <li
                                class="page-item"
                                v-if="
                                    pagination.last_page > 7 &&
                                        pagination.current_page > 7
                                "
                            >
                                <a
                                    class="page-link"
                                    href="#"
                                    @click.prevent="
                                        cambiarPagina(
                                            1,
                                            buscar,
                                            b_manzana,
                                            b_lote,
                                            criterio,
                                            buscar2
                                        )
                                    "
                                    >Inicio</a
                                >
                            </li>
                            <li
                                class="page-item"
                                v-if="pagination.current_page > 1"
                            >
                                <a
                                    class="page-link"
                                    href="#"
                                    @click.prevent="
                                        cambiarPagina(
                                            pagination.current_page - 1,
                                            buscar,
                                            b_manzana,
                                            b_lote,
                                            criterio,
                                            buscar2
                                        )
                                    "
                                    >Ant</a
                                >
                            </li>
                            <li
                                class="page-item"
                                v-for="page in pagesNumber"
                                :key="page"
                                :class="[page == isActived ? 'active' : '']"
                            >
                                <a
                                    class="page-link"
                                    href="#"
                                    @click.prevent="
                                        cambiarPagina(
                                            page,
                                            buscar,
                                            b_manzana,
                                            b_lote,
                                            criterio,
                                            buscar2
                                        )
                                    "
                                    v-text="page"
                                ></a>
                            </li>
                            <li
                                class="page-item"
                                v-if="
                                    pagination.current_page <
                                        pagination.last_page
                                "
                            >
                                <a
                                    class="page-link"
                                    href="#"
                                    @click.prevent="
                                        cambiarPagina(
                                            pagination.current_page + 1,
                                            buscar,
                                            b_manzana,
                                            b_lote,
                                            criterio,
                                            buscar2
                                        )
                                    "
                                    >Sig</a
                                >
                            </li>
                            <li
                                class="page-item"
                                v-if="
                                    pagination.last_page > 7 &&
                                        pagination.current_page <
                                            pagination.last_page
                                "
                            >
                                <a
                                    class="page-link"
                                    href="#"
                                    @click.prevent="
                                        cambiarPagina(
                                            pagination.last_page,
                                            buscar,
                                            b_manzana,
                                            b_lote,
                                            criterio,
                                            buscar2
                                        )
                                    "
                                    >Ultimo</a
                                >
                            </li>
                        </ul>
                    </nav>
                    <button class="btn btn-sm btn-default" @click="modal = 5">
                        Manual
                    </button>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <!--Inicio del modal agregar/actualizar-->
        <ModalComponent
            :titulo="tituloModal"
            @closeModal="cerrarModal()"
            v-if="modal == 1"
        >
            <template v-slot:body>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input"
                        >Ingreso de acta</label
                    >
                    <div class="col-md-3">
                        <input
                            type="date"
                            v-model="term_ingreso"
                            class="form-control"
                        />
                    </div>
                    <label class="col-md-2 form-control-label" for="text-input"
                        >Salida de acta</label
                    >
                    <div class="col-md-3">
                        <input
                            type="date"
                            v-model="term_salida"
                            class="form-control"
                        />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input"
                        >No. Acta</label
                    >
                    <div class="col-md-4">
                        <input
                            type="text"
                            pattern="\d*"
                            maxlength="13"
                            v-on:keypress="isNumber(event)"
                            v-model="num_acta"
                            class="form-control"
                            placeholder="Numero de Acta"
                        />
                    </div>
                </div>

                <div class="form-group row" v-if="tipoAccion == 1">
                    <label class="col-md-3 form-control-label" for="text-input"
                        >Agregar Observación</label
                    >
                    <div class="col-md-6">
                        <button
                            type="button"
                            class="btn btn-danger"
                            @click="abrirObs('observacion', id)"
                        >
                            Nueva Observación
                        </button>
                    </div>
                </div>

                <div v-show="errorActa" class="form-group row div-error">
                    <div class="text-center text-error">
                        <div
                            v-for="error in errorMostrarMsjActa"
                            :key="error"
                            v-text="error"
                        ></div>
                    </div>
                </div>
            </template>
            <template v-slot:buttons-footer>
                <button
                    type="button"
                    v-if="tipoAccion == 1"
                    class="btn btn-success"
                    @click="actualizarActa()"
                >
                    Guardar cambios
                </button>
                <button
                    type="button"
                    v-if="tipoAccion == 2"
                    class="btn btn-primary"
                    @click="updateMasaActas()"
                >
                    Actualizar
                </button>
            </template>
        </ModalComponent>
        <!--Fin del modal-->

        <!--Inicio del modal consulta-->
        <ModalComponent
            :titulo="tituloModal"
            @closeModal="cerrarModal()"
            v-if="modal == 2"
        >
            <template v-slot:body>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input"
                        >Proyecto</label
                    >
                    <div class="col-md-6">
                        <input
                            type="text"
                            disabled
                            v-model="datos.proyecto"
                            class="form-control"
                            placeholder="Proyecto"
                        />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input"
                        >Clave catastral</label
                    >
                    <div class="col-md-4">
                        <input
                            type="text"
                            disabled
                            v-model="datos.clv_catastral"
                            class="form-control"
                            placeholder="Clave catastral"
                        />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input"
                        >Etapa de servicios</label
                    >
                    <div class="col-md-4">
                        <input
                            type="text"
                            disabled
                            v-model="datos.etapa_servicios"
                            class="form-control"
                            placeholder="Etapa de servicios"
                        />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input"
                        >Manzana</label
                    >
                    <div class="col-md-4">
                        <input
                            type="text"
                            disabled
                            v-model="datos.manzana"
                            class="form-control"
                            placeholder="Manzana"
                        />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input"
                        ># Lote</label
                    >
                    <div class="col-md-4">
                        <input
                            type="text"
                            disabled
                            v-model="datos.num_lote"
                            class="form-control"
                            placeholder="Numero de Lote"
                        />
                    </div>
                    <div class="form-group row">
                        <label
                            class="col-md-3 form-control-label"
                            for="text-input"
                            >Duplex</label
                        >
                        <div class="col-md-4">
                            <input
                                type="text"
                                disabled
                                v-model="datos.sublote"
                                style="width:200px;"
                                class="form-control"
                                placeholder="Duplex"
                            />
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input"
                        >Prototipo</label
                    >
                    <div class="col-md-6">
                        <input
                            type="text"
                            disabled
                            v-model="datos.modelo"
                            class="form-control"
                            placeholder="Prototipo"
                        />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input"
                        >Dirección</label
                    >
                    <div class="col-md-4">
                        <input
                            type="text"
                            disabled
                            v-model="datos.calle"
                            class="form-control"
                            placeholder="Calle"
                        />
                    </div>
                    <div class="col-md-2">
                        <input
                            type="text"
                            disabled
                            v-model="datos.numero"
                            class="form-control"
                            placeholder="Numero"
                        />
                    </div>
                    <div class="col-md-2">
                        <input
                            type="text"
                            disabled
                            v-model="datos.interior"
                            class="form-control"
                            placeholder="Interior"
                        />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input"
                        >Construcción (mts&sup2;)</label
                    >
                    <div class="col-md-4">
                        <input
                            type="text"
                            style="width:200px;"
                            disabled
                            v-model="datos.construccion"
                            class="form-control"
                            placeholder="Construccion"
                        />
                    </div>
                    <div class="form-group row">
                        <label
                            class="col-md-4 form-control-label"
                            for="text-input"
                            >Terreno(mts&sup2;)</label
                        >
                        <div class="col-md-3">
                            <input
                                type="text"
                                style="width:150px;"
                                disabled
                                v-model="datos.terreno"
                                class="form-control"
                                placeholder="Terreno"
                            />
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input"
                        >Arquitecto</label
                    >
                    <div class="col-md-4">
                        <input
                            type="text"
                            disabled
                            v-model="datos.arquitecto"
                            class="form-control"
                            placeholder="Arquitecto"
                        />
                    </div>
                </div>

                <hr style="border-top: 2px solid rgba(100,155,255,0.5);" />
                <h5 style="text-align:center">Licencia</h5>
                <br />

                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input"
                        >Siembra</label
                    >
                    <div class="col-md-4">
                        <input
                            type="text"
                            style="width:200px;"
                            disabled
                            v-model="datos.siembra"
                            class="form-control"
                            placeholder="Siembra"
                        />
                    </div>
                    <div class="form-group row">
                        <label
                            class="col-md-3 form-control-label"
                            for="text-input"
                            >Planos</label
                        >
                        <div class="col-md-4">
                            <input
                                type="text"
                                style="width:200px;"
                                disabled
                                v-model="datos.f_planos"
                                class="form-control"
                                placeholder="Planos"
                            />
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input"
                        >Ingreso</label
                    >
                    <div class="col-md-4">
                        <input
                            type="text"
                            style="width:200px;"
                            disabled
                            v-model="datos.lic_ingreso"
                            class="form-control"
                            placeholder="Ingreso"
                        />
                    </div>
                    <div class="form-group row">
                        <label
                            class="col-md-3 form-control-label"
                            for="text-input"
                            >Salida</label
                        >
                        <div class="col-md-4">
                            <input
                                type="text"
                                style="width:200px;"
                                disabled
                                v-model="datos.lic_salida"
                                class="form-control"
                                placeholder="Salida"
                            />
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input"
                        >Num. Licencia</label
                    >
                    <div class="col-md-4">
                        <input
                            type="text"
                            disabled
                            v-model="datos.num_licencia"
                            class="form-control"
                            placeholder="Num. Licencia"
                        />
                    </div>
                </div>

                <hr style="border-top: 2px solid rgba(100,155,255,0.5);" />
                <h5 style="text-align:center">Acta de Terminación</h5>
                <br />

                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input"
                        >Ingreso</label
                    >
                    <div class="col-md-4">
                        <input
                            type="text"
                            style="width:200px;"
                            disabled
                            v-model="datos.f_ingreso"
                            class="form-control"
                            placeholder="Ingreso"
                        />
                    </div>
                    <div class="form-group row">
                        <label
                            class="col-md-3 form-control-label"
                            for="text-input"
                            >Salida</label
                        >
                        <div class="col-md-4">
                            <input
                                type="text"
                                style="width:200px;"
                                disabled
                                v-model="datos.f_salida"
                                class="form-control"
                                placeholder="Salida"
                            />
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input"
                        >Observación
                    </label>
                    <div class="col-md-6">
                        <textarea
                            rows="5"
                            cols="30"
                            disabled
                            v-model="observacion_completa"
                            class="form-control"
                            placeholder="Observacion"
                        ></textarea>
                        <button
                            type="button"
                            class="btn btn-info pull-right"
                            @click="abrirObs('ver_todo', datos.id)"
                        >
                            Ver todos
                        </button>
                    </div>
                </div>
            </template>
        </ModalComponent>
        <!--Fin del modal consulta-->

        <!--Inicio del modal observaciones-->
        <ModalComponent
            :titulo="tituloModal3"
            @closeModal="cerrarObs()"
            v-if="modal3"
        >
            <template v-slot:body>
                <div class="form-group row" v-if="tipoAccion == 3">
                    <label class="col-md-3 form-control-label" for="text-input"
                        >Observacion</label
                    >
                    <div class="col-md-6">
                        <textarea
                            rows="5"
                            cols="30"
                            v-model="observacion"
                            class="form-control"
                            placeholder="Observacion"
                        ></textarea>
                    </div>
                </div>
                <!--//////////tabla de consulta de observaciones//////////////-->
                <TableComponent
                    v-if="tipoAccion == 4"
                    :cabecera="['Usuario', 'Observacióon', 'Fecha']"
                >
                    <template v-slot:tbody>
                        <tr
                            v-for="observacion in arrayObservacion"
                            :key="observacion.id"
                        >
                            <td class="td2" v-text="observacion.usuario"></td>
                            <td
                                class="td2"
                                v-text="observacion.comentario"
                            ></td>
                            <td
                                class="td2"
                                v-text="observacion.created_at"
                            ></td>
                        </tr>
                    </template>
                </TableComponent>
            </template>
            <template v-slot:buttons-footer>
                <button
                    type="button"
                    v-if="tipoAccion == 3"
                    class="btn btn-primary"
                    @click="agregarComentario()"
                >
                    Guardar
                </button>
            </template>
        </ModalComponent>
        <!--Fin del modal observaciones-->

        <!-- Modal para la carga de foto de acta -->
        <ModalComponent
            :titulo="tituloModal"
            @closeModal="cerrarModal()"
            v-if="modal == 4"
        >
            <template v-slot:body>
                <form
                    method="post"
                    @submit="formSubmit"
                    enctype="multipart/form-data"
                >
                    <strong
                        style="color:black; font-size:15px; font-weight: bold; padding: 10px;"
                        >Acta:</strong
                    >
                    <input
                        disabled
                        type="text"
                        class="form-control"
                        v-model="num_acta"
                    />
                    <div class="form-archivo">
                        <input
                            ref="imageSelectorActa"
                            v-show="false"
                            type="file"
                            v-on:change="onImageChange"
                        />

                        <label class="label-button" @click="onSelectActa">
                            Sube aqui el acta de terminacion
                            <i class="fa fa-upload"></i>
                        </label>
                        <div
                            v-if="nom_archivo == 'Seleccione Archivo'"
                            class="text-file-hide"
                            v-text="nom_archivo"
                        ></div>

                        <div
                            v-else
                            class="text-file"
                            v-text="nom_archivo"
                        ></div>
                    </div>
                    <div class="boton-modal">
                        <button
                            v-show="nom_archivo != 'Seleccione Archivo'"
                            type="submit"
                            class="btn btn-success boton-modal"
                        >
                            Subir Archivo
                        </button>
                    </div>
                </form>
            </template>
        </ModalComponent>
        <!--Fin del modal-->

        <!-- Manual -->
        <ModalComponent
            :titulo="'Manual'"
            @closeModal="modal = 0"
            v-if="modal == 5"
        >
            <template v-slot:body>
                <p>
                    Dentro del modulo de actas de terminación podrá asignar los
                    datos de las actas de terminación, así como subir un archivo
                    del acta de terminación correspondiente al lote.
                </p>
                <p>
                    Para que un lote(s) aparezca(n) dentro del listado de acta
                    de terminación, debe de asegurarse de haber creado
                    anteriormente el lote desde el módulo de
                    “Desarrollo->Lotes”.
                </p>
                <p>
                    Para que aparezca el botón de “asignar actas” debe
                    seleccionar un lote o mas dando clic en la casilla que
                    aparece del lado izquierdo del renglón del lote (puede
                    seleccionar cuantos desee).
                </p>
            </template>
        </ModalComponent>
    </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
import ModalComponent from "../Componentes/ModalComponent.vue";
import TableComponent from "../Componentes/TableComponent.vue";

export default {
    components: {
        ModalComponent,
        TableComponent
    },
    data() {
        return {
            allLic: [],
            allSelected: false,
            datos: {},
            proceso: false,
            id: "",
            term_ingreso: "",
            term_salida: "",
            arquitecto_id: 0,
            perito_dro: 0,
            num_licencia: 0,
            num_acta: "",
            foto_acta: "",
            credito_puente: "",
            fraccionamiento_id: 0,
            avance: 0,
            empresa_id: 0,
            observacion: "",
            observacion_completa: "",
            arrayActaDeTerminacion: [],
            arrayArquitectos: [],
            arrayFraccionamientos: [],
            arrayObservacion: [],
            arrayPuentes: [],
            modal: 0,
            modal3: 0,
            tituloModal: "",
            tituloModal3: "",
            tipoAccion: 0,
            errorActa: 0,
            nom_archivo: "Seleccione Archivo",
            errorMostrarMsjActa: [],
            pagination: {
                total: 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0
            },
            offset: 3,
            criterio: "lotes.fraccionamiento_id",
            buscar: "",
            buscar2: "",
            b_manzana: "",
            b_lote: "",
            b_puente: "",
            b_num_inicio: "",
            empresas: [],
            b_empresa: "",
            b_empresa2: "",
            arrayAllEtapas: [],
            b_etapa: ""
        };
    },
    computed: {
        isActived: function() {
            return this.pagination.current_page;
        },
        //Calcula los elementos de la paginación
        pagesNumber: function() {
            if (!this.pagination.to) {
                return [];
            }

            var from = this.pagination.current_page - this.offset;
            if (from < 1) {
                from = 1;
            }

            var to = from + this.offset * 2;
            if (to >= this.pagination.last_page) {
                to = this.pagination.last_page;
            }

            var pagesArray = [];
            while (from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;
        }
    },

    methods: {
        selectAll: function() {
            this.allLic = [];

            if (!this.allSelected) {
                for (var lote in this.arrayActaDeTerminacion) {
                    this.allLic.push(
                        this.arrayActaDeTerminacion[lote].id.toString()
                    );
                }
            }
        },

        select: function() {
            this.allSelected = false;
        },

        updateMasaActas() {
            if (this.proceso == true) {
                return;
            }
            this.proceso = true;
            let me = this;
            //Con axios se llama el metodo update de LoteController

            Swal({
                title: "Estas seguro?",
                animation: false,
                customClass: "animated bounceInDown",
                text: "Las actas se asignaran a los lotes seleccionados",
                type: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                cancelButtonText: "Cancelar",

                confirmButtonText: "Si, asignar!"
            }).then(result => {
                if (result.value) {
                    me.allLic.forEach(element => {
                        axios.put("/acta_terminacion/updateMasaActas", {
                            id: element,
                            term_ingreso: this.term_ingreso,
                            term_salida: this.term_salida,
                            num_acta: this.num_acta
                        });
                    });
                    me.proceso = false;
                    me.cerrarModal();
                    me.listarActa(
                        1,
                        me.buscar,
                        me.b_manzana,
                        me.b_lote,
                        me.criterio,
                        me.buscar2
                    );
                    Swal({
                        title: "Hecho!",
                        text: "Se han asignado",
                        type: "success",
                        animation: false,
                        customClass: "animated bounceInRight"
                    });
                }
            });
        },

        onSelectActa() {
            this.$refs.imageSelectorActa.click();
        },

        onImageChange(e) {
            console.log(e.target.files[0]);
            this.foto_acta = e.target.files[0];
            this.nom_archivo = e.target.files[0].name;
        },

        formSubmit(e) {
            e.preventDefault();

            let currentObj = this;

            let formData = new FormData();

            formData.append("foto_acta", this.foto_acta);
            let me = this;
            axios
                .post("/formSubmitActa/" + this.id, formData)
                .then(function(response) {
                    currentObj.success = response.data.success;
                    swal({
                        position: "top-end",
                        type: "success",
                        title: "Archivo guardado correctamente",
                        showConfirmButton: false,
                        timer: 2000
                    });
                    me.cerrarModal();
                    me.listarActa(1, "", "", "", "fraccionamientos.nombre", "");
                })

                .catch(function(error) {
                    currentObj.output = error;
                });
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
        },

        /**Metodo para mostrar los registros */
        listarActa(page, buscar, b_manzana, b_lote, criterio, buscar2) {
            let me = this;
            var url =
                "/acta_terminacion?page=" +
                page +
                "&buscar=" +
                buscar +
                "&b_manzana=" +
                b_manzana +
                "&b_lote=" +
                b_lote +
                "&criterio=" +
                criterio +
                "&buscar2=" +
                buscar2 +
                "&b_puente=" +
                me.b_puente +
                "&b_num_inicio=" +
                me.b_num_inicio +
                "&b_empresa=" +
                me.b_empresa +
                "&b_empresa2=" +
                me.b_empresa2 +
                "&b_etapa=" +
                me.b_etapa;
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.arrayActaDeTerminacion = respuesta.actas.data;
                    me.pagination = respuesta.pagination;
                    console.log(url);
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        selectArquitectos() {
            let me = this;
            me.arrayArquitectos = [];
            var url = "/select_personal?departamento_id=3";
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.arrayArquitectos = respuesta.personal;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        selectFraccionamientos() {
            let me = this;
            me.buscar = "";
            me.b_arquitecto = "";
            me.b_manzana = "";
            me.b_lote = "";
            me.b_modelo = "";
            me.buscar2 = "";
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

        agregarComentario() {
            if (this.proceso === true) {
                return;
            }
            this.proceso = true;
            let me = this;
            //Con axios se llama el metodo store de DepartamentoController
            axios
                .post("/observacion/registrar", {
                    lote_id: this.lote_id,
                    comentario: this.observacion
                })
                .then(function(response) {
                    me.proceso = false;
                    me.cerrarObs(); //al guardar el registro se cierra el modal

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

        listarObservacion(page, buscar) {
            let me = this;
            var url = "/observacion?page=" + page + "&buscar=" + buscar;
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.arrayObservacion = respuesta.observacion.data;
                    me.pagination = respuesta.pagination;
                    console.log(url);
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        selectUltimoComentario(lote) {
            let me = this;
            var url = "/observacion/select_ultima?buscar=" + lote;
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.observacion = respuesta.observacion.comentario;
                    me.usuario = respuesta.observacion.usuario;
                    me.fecha_comentario = moment(
                        respuesta.observacion.created_at,
                        "YYYY-MM-DD hh:mm:ss"
                    )
                        .locale("es")
                        .fromNow();

                    me.observacion_completa =
                        me.observacion +
                        " " +
                        "(" +
                        me.usuario +
                        " - " +
                        me.fecha_comentario +
                        ")";
                })
                .catch(function(error) {
                    console.log(error);
                });
        },

        selectPuente(id) {
            let me = this;

            me.arrayPuentes = [];
            var url = "/selectCreditoPuente?fraccionamiento=" + id;
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.arrayPuentes = respuesta.creditos;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },

        selectEtapas(buscar) {
            let me = this;
            me.b_etapa = "";

            me.arrayAllEtapas = [];
            var url = "/select_etapa_proyecto?buscar=" + buscar;
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.arrayAllEtapas = respuesta.etapas;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },

        cambiarPagina(page, buscar, b_manzana, b_lote, criterio, buscar2) {
            let me = this;
            //Actualiza la pagina actual
            me.pagination.current_page = page;
            //Envia la petición para visualizar la data de esta pagina
            me.listarActa(page, buscar, b_manzana, b_lote, criterio, buscar2);
        },

        actualizarActa() {
            if (this.proceso === true) {
                return;
            }
            if (this.validarActa()) {
                //Se verifica si hay un error (campo vacio)
                return;
            }
            this.proceso = true;
            let me = this;
            //Con axios se llama el metodo update de LoteController
            axios
                .put("/acta_terminacion/actualizar", {
                    id: this.id,
                    term_ingreso: this.term_ingreso,
                    term_salida: this.term_salida,
                    avance: this.avance,
                    num_acta: this.num_acta
                })
                .then(function(response) {
                    me.proceso = false;
                    me.cerrarModal();
                    me.listarActa(1, "", "", "", "fraccionamientos.nombre", "");
                    //window.alert("Cambios guardados correctamente");
                    swal({
                        position: "top-end",
                        type: "success",
                        title: "Cambios guardados correctamente",
                        showConfirmButton: false,
                        timer: 1500
                    });
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        validarActa() {
            this.errorActa = 0;
            this.errorMostrarMsjActa = [];

            if (this.avance < 0 || this.avance > 100)
                //Si la variable Lote esta vacia
                this.errorMostrarMsjActa.push("Avance invalido");

            if (this.errorMostrarMsjActa.length)
                //Si el mensaje tiene almacenado algo en el array
                this.errorActa = 1;

            return this.errorActa;
        },

        cerrarModal() {
            this.modal = 0;
            this.tituloModal = "";
            this.avance = "";
            this.term_ingreso = "";
            this.term_salida = "";
            this.num_acta = "";
            this.datos = {};
            this.observacion_completa = "";
            this.num_acta = "";
            this.foto_acta = "";
            this.errorActa = 0;
            this.errorMostrarMsjActa = [];
        },
        cerrarObs() {
            this.modal3 = 0;
            this.tituloModal3 = "";
            this.usuario = "";
            this.observacion = "";
        },

        /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
        abrirModal(accion, data = []) {
            switch (accion) {
                case "actualizar": {
                    this.modal = 1;
                    this.tituloModal = "Actualizar Acta de terminación";
                    this.proceso = false;
                    this.term_ingreso = data["term_ingreso"];
                    this.term_salida = data["term_salida"];
                    this.avance = data["avance"];
                    this.id = data["id"];
                    this.num_acta = data["num_acta"];
                    this.tipoAccion = 1;
                    break;
                }
                case "actasMasa": {
                    this.modal = 1;
                    this.tituloModal = "Asignar Acta de terminación";
                    this.term_ingreso = "";
                    this.term_salida = "";
                    this.num_acta = "";
                    this.tipoAccion = 2;
                    break;
                }
                case "subirArchivo": {
                    this.modal = 4;
                    this.tituloModal = "Subir Archivo";
                    this.tipoAccion = 5;
                    this.id = data["id"];
                    this.num_acta = data["num_acta"];
                    this.foto_acta = data["foto_acta"];
                    break;
                }
                case "ver": {
                    this.modal = 2;
                    this.tituloModal = "Consulta ";
                    this.datos = data;
                    if (data["f_planos"])
                        this.datos.f_planos = moment(data["f_planos"])
                            .locale("es")
                            .format("DD/MMM/YYYY");
                    if (data["f_ingreso"])
                        this.datos.lic_ingreso = moment(data["f_ingreso"])
                            .locale("es")
                            .format("DD/MMM/YYYY");
                    if (data["f_salida"])
                        this.datos.lic_salida = moment(data["f_salida"])
                            .locale("es")
                            .format("DD/MMM/YYYY");
                    if (data["term_ingreso"])
                        this.datos.f_ingreso = moment(data["term_ingreso"])
                            .locale("es")
                            .format("DD/MMM/YYYY");
                    if (data["term_salida"])
                        this.datos.f_salida = moment(data["term_salida"])
                            .locale("es")
                            .format("DD/MMM/YYYY");
                    this.datos.siembra = moment(data["siembra"])
                        .locale("es")
                        .format("DD/MMM/YYYY");
                    this.listarObservacion(1, data["id"]);
                    this.selectUltimoComentario(data["id"]);
                    break;
                }
            }
            this.selectArquitectos();
        },
        abrirObs(accion, lote) {
            this.modal3 = 1;
            switch (accion) {
                case "observacion": {
                    this.proceso = false;
                    this.tituloModal3 = "Agregar Observación";
                    this.observacion = "";
                    this.usuario = "";
                    this.lote_id = lote;
                    this.tipoAccion = 3;
                    break;
                }
                case "ver_todo": {
                    this.tituloModal3 = "Consulta Observaciones";
                    this.tipoAccion = 4;
                    break;
                }
            }
        }
    },
    mounted() {
        this.listarActa(
            1,
            this.buscar,
            this.b_manzana,
            this.b_lote,
            this.criterio,
            this.buscar2
        );
        this.selectFraccionamientos();
        this.getEmpresa();
    }
};
</script>
<style scoped>
.form-control:disabled,
.form-control[readonly] {
    background-color: rgba(0, 0, 0, 0.06);
    opacity: 1;
    font-size: 1rem;
    color: #27417b;
}
.modal-content {
    width: 100% !important;
    position: absolute !important;
}
.mostrar {
    display: list-item !important;
    opacity: 1 !important;
    position: fixed !important;
    background-color: #3c29297a !important;
    overflow-y: auto;
}

.text-formfile {
    color: grey;
    display: flex;
    padding-top: 13px;
    /*  background-color: aqua; */
    justify-content: left;
}
.contenedor-modal {
    display: block;
    flex-direction: column;

    margin: auto;
    overflow-x: auto;
    width: fit-content;
    max-width: 100%;
}

.label-button {
    border-style: solid;
    cursor: pointer;
    color: #fff;
    background-color: #00adef;
    border-color: #00adef;
    padding: 10px;
}

.label-button:hover {
    color: #fff;
    background-color: #1b8eb7;
    border-color: #00b0bb;
}
.form-sub {
    border: 1px solid #c2cfd6;
    margin-top: 20px;
    width: 90%;
}
.form-opc {
    display: flex;
    flex-direction: column;
}
.form-archivo {
    display: flex;
    flex-direction: row;

    width: 100%;
}
.text-file {
    color: rgb(39, 38, 38);
    font-size: 12px;
    word-break: break-all;
    font-weight: bold;
    width: 300px;
    padding: 15px;
}
.text-file-hide {
    color: rgb(127, 130, 134);
    font-size: 13px;
    word-break: break-all;
    font-weight: bold;
    width: 300px;
    padding: 15px;
}
.boton-modal {
    margin-top: 15px;
    display: flex;
    flex-direction: row;
    justify-content: center;
}
.div-error {
    display: flex;
    justify-content: center;
}
.text-error {
    color: red !important;
    font-weight: bold;
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

input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
</style>
