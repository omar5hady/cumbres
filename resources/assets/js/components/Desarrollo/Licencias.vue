<template>
    <main class="main" >
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card scroll-box">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i>Licencias

                    <!--   Boton   -->
                    <a v-if="rolId != '5'" class="btn btn-success" v-bind:href="'/licencias/resume_excel'" >
                        <i class="icon-pencil"></i>&nbsp;Descargar resumen
                    </a>
                    <!---->
                    <button class="btn btn-info btn-sm" @click="abrirModal('asignarMasa')"  v-if="allLic.length > 0 && rolId != '5'" >
                        <i class="icon-pencil"></i>&nbsp;Asignar en masa
                    </button>

                    <button class="btn btn-dark btn-sm" @click="abrirModal('asignarLicencias')"  v-if="allLic.length > 0 && rolId != '5'" >
                        <i class="fa fa-drivers-license-o "></i>&nbsp;Asignar licencias
                    </button>


                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-10">
                            <div class="input-group">
                                <!--Criterios para el listado de busqueda -->
                                <select class="form-control col-md-3" v-model="criterio" @change="selectFraccionamientos()">
                                    <option value="lotes.fraccionamiento_id">Proyecto</option>
                                    <option value="modelos.nombre">Modelo</option>
                                    <option value="licencias.num_licencia">Num.Licencia</option>
                                    <option value="licencias.f_planos">Planos</option>
                                    <option value="lotes.siembra">Siembra</option>
                                    <option value="licencias.credito_puente">Crédito puente</option>
                                    <option value="licencias.perito_dro">DRO</option>
                                </select>

                                <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar" @change="selectPuente(buscar),selectEtapas(buscar),selectRuv()" >
                                    <option value="">Seleccione</option>
                                    <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                </select>

                                <select class="form-control"  v-if="criterio=='licencias.perito_dro'" v-model="buscar">
                                        <option value="">Seleccione</option>
                                        <option value="1">Por Asignar</option>
                                        <option value="15044">Ing. Alejandro F. Perez Espinosa</option>
                                        <option value="23679">Raúl Palos López</option>
                                </select>
                                <input type="date" v-if="criterio=='licencias.f_planos'" v-model="buscar" @keyup.enter="listarLicencias(1,buscar,b_manzana,b_lote,b_modelo,b_arquitecto,criterio,buscar2)" class="form-control col-md-6" placeholder="Desde" >
                                <input type="date" v-if="criterio=='licencias.f_planos'" v-model="buscar2"  @keyup.enter="listarLicencias(1,buscar,b_manzana,b_lote,b_modelo,b_arquitecto,criterio,buscar2)" class="form-control col-md-6" placeholder="Hasta" >

                                <input type="date" v-if="criterio=='lotes.siembra'" v-model="buscar" @keyup.enter="listarLicencias(1,buscar,b_manzana,b_lote,b_modelo,b_arquitecto,criterio,buscar2)" class="form-control col-md-6" placeholder="Desde" >

                                <input v-if="criterio!='lotes.fraccionamiento_id' && criterio!='licencias.f_planos' && criterio!='lotes.siembra' && criterio!='licencias.perito_dro' " type="text"  v-model="buscar" @keyup.enter="listarLicencias(1,buscar,b_manzana,b_lote,b_modelo,b_arquitecto,criterio,buscar2)" class="form-control" placeholder="Texto a buscar">
                            </div>
                            <div class="input-group" v-if="criterio=='lotes.fraccionamiento_id'">
                                <select class="form-control" v-model="b_etapa" @change="selectRuv()" >
                                    <option value="">Etapa</option>
                                    <option v-for="etapa in arrayAllEtapas" :key="etapa.id" :value="etapa.id" v-text="etapa.num_etapa"></option>
                                </select>
                                <input type="text"  v-model="b_manzana" @keyup.enter="listarLicencias(1,buscar,b_manzana,b_lote,b_modelo,b_arquitecto,criterio,buscar2)" class="form-control" placeholder="Manzana">
                            </div>
                            <div class="input-group" v-if="criterio=='lotes.fraccionamiento_id'">
                                <!--Criterios para el listado de busqueda -->
                                <input type="text"  v-model="b_lote" @keyup.enter="listarLicencias(1,buscar,b_manzana,b_lote,b_modelo,b_arquitecto,criterio,buscar2)" class="form-control" placeholder="# Lote">
                                <input type="text"  v-model="b_modelo" @keyup.enter="listarLicencias(1,buscar,b_manzana,b_lote,b_modelo,b_arquitecto,criterio,buscar2)" class="form-control" placeholder="Modelo">
                            </div>
                            <div class="input-group" v-if="criterio=='lotes.fraccionamiento_id'">
                                <!--Criterios para el listado de busqueda -->
                                <input type="text"  v-model="b_arquitecto" @keyup.enter="listarLicencias(1,buscar,b_manzana,b_lote,b_modelo,b_arquitecto,criterio,buscar2)" class="form-control" placeholder="Arquitecto">
                            </div>
                            <div class="input-group" v-if="criterio=='lotes.fraccionamiento_id'">
                                <!--Criterios para el listado de busqueda -->
                                <select class="form-control"  v-model="b_puente" @keyup.enter="listarLicencias(1,buscar,b_manzana,b_lote,b_modelo,b_arquitecto,criterio,buscar2)">
                                    <option value="">Credito Puente</option>
                                    <option v-for="puente in arrayPuentes" :key="puente.credito_puente" :value="puente.credito_puente" v-text="puente.credito_puente"></option>
                                </select>

                                <input  type="text"  v-model="b_num_inicio" @keyup.enter="listarLicencias(1,buscar,b_manzana,b_lote,b_modelo,b_arquitecto,criterio,buscar2)" class="form-control" placeholder="# Inicio">
                            </div>
                            <div class="input-group" v-if="criterio=='lotes.fraccionamiento_id'">
                                <!--Criterios para el listado de busqueda -->
                                <select class="form-control" v-model="b_ruv" @keyup.enter="listarLicencias(1,buscar,b_manzana,b_lote,b_modelo,b_arquitecto,criterio,buscar2)">
                                    <option value="">Paquete Ruv</option>
                                    <option v-for="ruv in arrayRuv" :key="ruv.paq_ruv" :value="ruv.paq_ruv" v-text="ruv.paq_ruv"></option>
                                </select>
                            </div>
                            <div class="input-group">
                                <select class="form-control" v-model="b_empresa" >
                                    <option value="">Empresa constructora</option>
                                    <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                </select>
                                <select class="form-control" v-model="b_empresa2" >
                                    <option value="">Empresa terreno</option>
                                    <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-8">
                            <div class="input-group">
                                <button type="submit" @click="listarLicencias(1,buscar,b_manzana,b_lote,b_modelo,b_arquitecto,criterio,buscar2)"
                                    class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                <a v-if="rolId != '5'" class="btn btn-success" v-bind:href="'/licencias/excel?buscar=' + buscar + '&b_manzana=' + b_manzana +
                                        '&b_lote='+ b_lote + '&b_modelo='+ b_modelo + '&b_arquitecto='+ b_arquitecto + '&criterio=' + criterio +
                                        '&buscar2=' + buscar2 + '&b_puente=' + b_puente + '&b_num_inicio=' + b_num_inicio + '&b_empresa=' + b_empresa +
                                        '&b_empresa2=' + b_empresa2 + '&b_etapa=' + b_etapa + '&b_ruv=' + b_ruv" >
                                    <i class="icon-pencil"></i>&nbsp;Excel
                                </a>
                            </div>
                        </div>
                    </div>
                    <TableComponent>
                        <template v-slot:thead>
                            <tr v-if="rolId != '5'">
                                <th v-if="rolId != '5'" colspan="9"></th>
                                <th v-if="rolId != '5'" class="text-center" colspan="2">Inicio</th>
                                <th v-if="rolId != '5'" colspan="8"></th>
                            </tr>
                            <tr>
                                <th v-if="rolId != '5'">
                                    <input type="checkbox" @click="selectAll" v-model="allSelected">
                                </th>
                                <th v-if="rolId != '5'">Opciones</th>
                                <th>Proyecto</th>
                                <th>Etapa</th>
                                <th>Manzana</th>
                                <th># Lote</th>
                                <th>Terreno mts&sup2;</th>
                                <th>Construcción mts&sup2;</th>
                                <th>Modelo</th>
                                <th>Arquitecto</th>
                                <th>% Avance</th>
                                <th v-if="rolId != '5'">No. Inicio</th>
                                <th v-if="rolId != '5'">RUV</th>
                                <th v-if="rolId != '5'">Fecha</th>
                                <th v-if="rolId != '5'">Siembra de obra</th>
                                <th v-if="rolId != '5'">Planos licencia</th>
                                <th>DRO</th>
                                <th v-if="rolId != '5'">Ingreso</th>
                                <th v-if="rolId != '5'">Salida</th>
                                <th>Num. Licencia</th>
                                <th v-if="rolId != '5'">Credito puente</th>
                                <th v-if="rolId == '5'">Catalogo de especificaciones</th>
                                <th>Empresa constructora</th>
                            </tr>
                        </template>
                        <template v-slot:tbody>
                            <tr v-on:dblclick="abrirModal('ver',licencias)" v-for="licencias in arrayLicencias" :key="licencias.id" title="Ver detalle">

                                <td v-if="rolId != '5'" class="td2">
                                <input type="checkbox"  @click="select" :id="licencias.id" :value="licencias.id" v-model="allLic" >
                                </td>

                                <td v-if="rolId != '5'" class="td2" >
                                    <button title="Editar" type="button" @click="abrirModal('actualizar',licencias)" class="btn btn-warning btn-sm">
                                    <i class="icon-pencil"></i>
                                    </button>
                                    <button title="Subir foto y predial" type="button" @click="abrirModal('subirArchivo',licencias)" class="btn btn-default btn-sm">
                                    <i class="icon-cloud-upload"></i>
                                    </button>
                                    <a title="Descargar predial" v-if ="licencias.foto_predial" class="btn btn-success btn-sm" v-bind:href="'/downloadPredial/'+licencias.foto_predial">
                                        <i class="fa fa-arrow-circle-down fa-lg"></i>
                                    </a>

                                </td>
                                <td class="td2">
                                    <a href="#" v-text="licencias.proyecto"></a>
                                </td>
                                <td class="td2">
                                    <a href="#" v-text="licencias.num_etapa"></a>
                                </td>
                                <td class="td2" v-text="licencias.manzana"></td>
                                <td class="td2" v-text="licencias.num_lote"></td>
                                <td class="td2" v-text="formatNumber(licencias.terreno)"></td>
                                <td class="td2" v-text="formatNumber(licencias.construccion)"></td>
                                <!--Modelo-->
                                <td class="td2">
                                    <span v-if = "licencias.modelo!='Por Asignar' && licencias.cambios==0" class="badge badge-success" v-text="licencias.modelo"></span>
                                    <span v-if = "licencias.modelo=='Por Asignar'" class="badge badge-danger">Por Asignar</span>
                                    <span v-if = "licencias.cambios==1 && licencias.modelo_ant !== 'N/A'" class="badge badge-dark" v-text="licencias.modelo_ant + '->' + licencias.modelo"></span>
                                    <span v-if = "licencias.cambios==1 && licencias.modelo_ant == 'N/A'" class="badge badge-warning" v-text="licencias.modelo"></span>
                                </td>
                                <!--Arquitecto -->
                                <td class="td2">
                                    <span v-if = "licencias.arquitecto!='Sin Asignar  '" class="badge badge-success" v-text="'Arq. '+licencias.arquitecto"></span>
                                    <span v-else class="badge badge-danger"> Por Asignar </span>
                                </td>
                                <td v-text="licencias.avance + '%'"></td>
                                <!-- SIEMBRA -->
                                <template v-if="rolId != '5'">
                                        <td class="td2" v-if="!licencias.siembra" v-text="''"></td>
                                        <td class="td2" v-else v-text="licencias.num_inicio"></td>
                                </template>
                                <!--RUV-->
                                <template v-if="rolId != '5'">
                                        <td class="td2" v-text="licencias.paq_ruv"></td>
                                </template>
                                <template v-if="rolId != '5'">
                                        <td class="td2" v-if="!licencias.siembra" v-text="''"></td>
                                        <td class="td2" v-else v-text="this.moment(licencias.siembra).locale('es').format('DD/MMM/YYYY')"></td>
                                </template>

                                    <!-- Fecha planos obra -->
                                <template v-if="rolId != '5'">
                                    <td class="td2" v-if="!licencias.f_planos_obra" v-text="''"></td>
                                    <td class="td2" v-else v-text="this.moment(licencias.f_planos_obra).locale('es').format('DD/MMM/YYYY')"></td>
                                </template>

                                <!-- Fecha planos licencias-->
                                <template v-if="rolId != '5'">
                                    <td class="td2" v-if="!licencias.f_planos" v-text="''"></td>
                                    <td class="td2" v-else v-text="this.moment(licencias.f_planos).locale('es').format('DD/MMM/YYYY')"></td>
                                </template>

                                <!-- perito -->
                                <td class="td2">
                                    <span v-if = "licencias.perito!='Sin Asignar  '" class="badge badge-success" v-text="licencias.perito"></span>
                                    <span v-else class="badge badge-danger"> Por Asignar </span>
                                </td>

                                <!-- Fecha Ingreso -->
                                <template v-if="rolId != '5'">
                                        <td class="td2" v-if="!licencias.f_ingreso" v-text="''"></td>
                                        <td class="td2" v-else v-text="this.moment(licencias.f_ingreso).locale('es').format('DD/MMM/YYYY')"></td>
                                </template>

                                <!-- Fecha Salida -->
                                <template v-if="rolId != '5'">
                                        <td class="td2" v-if="!licencias.f_salida" v-text="''"></td>
                                        <td class="td2" v-else v-text="this.moment(licencias.f_salida).locale('es').format('DD/MMM/YYYY')"></td>
                                </template>

                                <template>
                                        <td class="td2"  v-if="!licencias.foto_lic" v-text="licencias.num_licencia"></td>
                                        <td class="td2" v-else style="width:7%"><a class="btn btn-default btn-sm"  v-text="licencias.num_licencia" v-bind:href="'/downloadLicencias/'+licencias.foto_lic"></a></td>
                                </template>

                                <td v-if="rolId != '5'" class="td2"  v-text="licencias.credito_puente"></td>

                                <template v-if="rolId == '5'">
                                    <td class="td2" style="width:7%" v-if="licencias.archivo"><a class="btn btn-default btn-sm" v-bind:href="'/downloadModelo/'+licencias.archivo"><i class="icon-cloud-download"></i></a></td>
                                    <td class="td2" v-else ></td>
                                </template>
                                <td class="td2" v-text="licencias.emp_constructora"></td>
                            </tr>
                        </template>
                    </TableComponent>
                    <nav>
                        <!--Botones de paginacion -->
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page > 7">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(1,buscar,b_manzana,b_lote,b_modelo,b_arquitecto,criterio,buscar2)">Inicio</a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,b_manzana,b_lote,b_modelo,b_arquitecto,criterio,buscar2)">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,b_manzana,b_lote,b_modelo,b_arquitecto,criterio,buscar2)" v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,b_manzana,b_lote,b_modelo,b_arquitecto,criterio,buscar2)">Sig</a>
                            </li>
                            <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page<pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.last_page,buscar,b_manzana,b_lote,b_modelo,b_arquitecto,criterio,buscar2)">Ultimo</a>
                            </li>
                        </ul>
                    </nav>
                    <button class="btn btn-sm btn-default" @click="modal=3">Manual</button>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <!--Inicio del modal agregar/actualizar-->
        <ModalComponent :titulo="tituloModal"
            v-if="modal == 1"
            @closeModal="cerrarModal()"
        >
            <template v-slot:body>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Arquitecto</label>
                    <div class="col-md-6">
                        <select class="form-control" v-model="arquitecto_id">
                            <option value="0">Seleccione</option>
                            <option v-for="arquitectos in arrayArquitectos" :key="arquitectos.id" :value="arquitectos.id" v-text="'Arq. ' + arquitectos.name"></option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">DRO</label>
                    <div class="col-md-6">
                        <select class="form-control" v-model="perito_dro">
                            <option value="0">Seleccione</option>
                            <option value="15044">Ing. Alejandro F. Perez Espinosa</option>
                            <option value="23679">Raúl Palos López</option>
                            <option value="29836">Juan Antonio De La Torre</option>
                        </select>
                    </div>
                </div>
                <br><br>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Fecha planos obra</label>
                    <div class="col-md-3">
                        <input type="date" v-model="f_planos_obra" class="form-control" >
                    </div>
                    <label class="col-md-3 form-control-label" for="text-input">Fecha planos licencia</label>
                    <div class="col-md-3">
                        <input type="date" v-model="f_planos" class="form-control" >
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Ingreso de licencia</label>
                    <div class="col-md-3">
                        <input type="date" v-model="f_ingreso" class="form-control" >
                    </div>
                    <label class="col-md-3 form-control-label" for="text-input">Salida de licencia</label>
                    <div class="col-md-3">
                        <input type="date" v-model="f_salida" class="form-control" >
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Numero de licencia</label>
                    <div class="col-md-4">
                        <input type="text" maxlength="20" v-model="num_licencia" class="form-control" >
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Agregar Observación</label>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-danger" @click="abrirModalObs('observacion',id)">Nueva Observación</button>
                    </div>
                </div>
            </template>
            <template v-slot:buttons-footer>
                <button type="button"  class="btn btn-primary" @click="actualizarLicencia()">Actualizar</button>
            </template>
        </ModalComponent>
        <!--Fin del modal-->
        <!--Inicio del modal planos/obra en masa-->
        <ModalComponent :titulo="tituloModal"
            v-if="modal== 5"
            @closeModal="cerrarModal()"
        >
            <template v-slot:body>
                <div class="form-group row" v-if="tipoAccion == 1">
                    <label class="col-md-3 form-control-label" for="text-input">DRO</label>
                    <div class="col-md-6">
                        <select class="form-control" v-model="perito_dro">
                            <option value="0">Seleccione</option>
                            <option value="15044">Ing. Alejandro F. Perez Espinosa</option>
                            <option value="23679">Raúl Palos López</option>
                            <option value="29836">Juan Antonio De La Torre</option>
                        </select>
                    </div>
                </div>
                <br><br>
                <div class="form-group row" v-if="tipoAccion == 1">
                    <label class="col-md-3 form-control-label" for="text-input">Planos obra</label>
                    <div class="col-md-6">
                        <input type="date" v-model="f_planos_obra" class="form-control" >
                    </div>
                </div>
                <div class="form-group row" v-if="tipoAccion == 2">
                    <label class="col-md-3 form-control-label" for="text-input">Ingreso de licencia</label>
                    <div class="col-md-6">
                        <input type="date" v-model="f_ingreso" class="form-control" >
                    </div>
                </div>
                <div class="form-group row" v-if="tipoAccion == 2">
                    <label class="col-md-3 form-control-label" for="text-input">Salida</label>
                    <div class="col-md-6">
                        <input type="date" v-model="f_salida" class="form-control" >
                    </div>
                </div>
                <div class="form-group row" v-if="tipoAccion == 2">
                    <label class="col-md-3 form-control-label" for="text-input">Numero de licencia</label>
                    <div class="col-md-6">
                        <input type="text" maxlength="20" v-model="num_licencia" class="form-control" >
                    </div>
                </div>
            </template>
            <template v-slot:buttons-footer>
                <button type="button"  class="btn btn-primary" @click="actualizarMasa()" v-if="tipoAccion == 1">Actualizar</button>
                <button type="button"  class="btn btn-primary" @click="updateMasaLicencias()" v-if="tipoAccion == 2">Actualizar</button>
            </template>
        </ModalComponent>
        <!--Fin del modal-->

        <!--Inicio del modal consulta-->
        <ModalComponent v-if="modal == 2"
            @closeModal="cerrarModal"
            :titulo="tituloModal"
        >
            <template v-slot:body>

                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Proyecto</label>
                    <div class="col-md-6">
                        <input type="text" disabled v-model="fraccionamiento"  class="form-control"  placeholder="Proyecto">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Clave catastral</label>
                    <div class="col-md-4">
                        <input type="text" disabled v-model="clv_catastral" class="form-control"  placeholder="Clave catastral">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Etapa de servicios</label>
                    <div class="col-md-4">
                        <input type="text" disabled v-model="etapa_servicios" class="form-control"  placeholder="Etapa de servicios">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Manzana</label>
                    <div class="col-md-4">
                        <input type="text" disabled v-model="manzana" class="form-control" placeholder="Manzana">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input"># Lote</label>
                    <div class="col-md-4">
                        <input type="text" disabled v-model="num_lote" class="form-control" placeholder="Numero de Lote">
                    </div>
                    <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Duplex</label>
                    <div class="col-md-4" >
                        <input type="text" disabled v-model="sublote" style="width:200px;"  class="form-control" placeholder="Duplex">
                    </div>
                </div>
                </div>
                    <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Prototipo</label>
                    <div class="col-md-6">
                        <input type="text" disabled v-model="modelo" class="form-control" placeholder="Prototipo">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Dirección</label>
                    <div class="col-md-4">
                        <input type="text" disabled v-model="calle " class="form-control" placeholder="Calle">
                    </div>
                    <div class="col-md-2">
                        <input type="text" disabled v-model="numero" class="form-control" placeholder="Numero">
                    </div>
                    <div class="col-md-2">
                        <input type="text" disabled v-model="interior" class="form-control" placeholder="Interior">
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Construcción (mts&sup2;)</label>
                    <div class="col-md-4">

                        <input type="text" style="width:200px;" disabled v-model="construccion" class="form-control" placeholder="Construccion">

                    </div>
                    <div class="form-group row">
                    <label class="col-md-4 form-control-label" for="text-input">Terreno(mts&sup2;)</label>
                    <div class="col-md-3" >

                        <input type="text" style="width:150px;" disabled v-model="terreno" class="form-control" placeholder="Terreno">

                    </div>
                </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Arquitecto</label>
                    <div class="col-md-4">

                        <input type="text"  disabled v-model="arquitecto" class="form-control" placeholder="Arquitecto">

                    </div>
                </div>

                <hr style="border-top: 2px solid rgba(100,155,255,0.5);" />
                <h5 style="text-align:center">Licencia</h5>
                <br>

                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Siembra</label>
                    <div class="col-md-4">

                        <input type="text" style="width:200px;" disabled v-model="siembra" class="form-control" placeholder="Siembra">

                    </div>

                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Planos obra</label>
                    <div class="col-md-4">

                        <input type="text" style="width:200px;" disabled v-model="f_planos_obra" class="form-control" placeholder="Planos obra">

                    </div>
                    </div>

                </div>

                    <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Planos licencia</label>
                    <div class="col-md-4">

                        <input type="text" style="width:200px;" disabled v-model="f_planos" class="form-control" placeholder="Planos licencia">

                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Ingreso</label>
                    <div class="col-md-4">

                        <input type="text" style="width:200px;" disabled v-model="f_ingreso" class="form-control" placeholder="Ingreso">

                    </div>
                    <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Salida</label>
                    <div class="col-md-4">

                        <input type="text" style="width:200px;" disabled v-model="f_salida" class="form-control" placeholder="Salida">

                    </div>
                </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Num. Licencia</label>
                    <div class="col-md-4">

                        <input type="text"  disabled v-model="num_licencia" class="form-control" placeholder="Num. Licencia">

                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Observación </label>
                    <div class="col-md-6">

                        <textarea rows="5" cols="30"  disabled v-model="observacion_completa" class="form-control" placeholder="Observacion"></textarea>
                        <button type="button" class="btn btn-info pull-right" @click="abrirModalObs('ver_todo',id)">Ver todos</button>
                    </div>
                </div>


            </template>
        </ModalComponent>
        <!--Fin del modal consulta-->

        <!--Inicio del modal observaciones-->
        <ModalComponent v-if="modalObs"
            :titulo="tituloObs"
            @closeModal="cerrarObs()"
        >
            <template v-slot:body>
                <div class="form-group row" v-if="tipoAccion==3">
                    <label class="col-md-3 form-control-label" for="text-input">Observacion</label>
                    <div class="col-md-6">
                            <textarea rows="5" cols="30" v-model="observacion" class="form-control" placeholder="Observacion"></textarea>
                    </div>
                </div>
                <TableComponent v-if="tipoAccion == 4" :cabecera="[
                    'Usuario','Observacion','Fecha']">
                    <template v-slot:tbody>
                        <tr v-for="observacion in arrayObservacion" :key="observacion.id">
                            <td class="td2" v-text="observacion.usuario" ></td>
                            <td class="td2" v-text="observacion.comentario" ></td>
                            <td class="td2" v-text="observacion.created_at"></td>
                        </tr>
                    </template>
                    <template  v-if="tipoAccion==3" v-slot:buttons-footer>
                        <button type="button"  class="btn btn-primary" @click="agregarComentario()">Guardar</button>
                    </template>
                </TableComponent>
            </template>
        </ModalComponent>

        <!-- Modal para la carga de foto de licencia -->
        <ModalComponent v-if="modal == 4"
            :titulo="tituloModal"
            @closeModal="cerrarModal()"
        >
            <template v-slot:body>
                <div style="float:left;">
                    <form  method="post" @submit="formSubmit" enctype="multipart/form-data">
                        <strong>Licencia:</strong>

                        <input disabled type="text" class="form-control" v-model="num_licencia" >

                        <strong>Sube aqui foto de licencia</strong>

                        <input type="file" class="form-control" v-on:change="onImageChange">
                        <br/>
                        <button type="submit" class="btn btn-success">Cargar</button>
                    </form>
                </div>

                <div style="float:right;">
                    <form  method="post" @submit="formSubmitPredial" enctype="multipart/form-data">
                        <strong>Sube aqui foto del predial</strong>

                        <input type="file" class="form-control" v-on:change="onImageChangePredial">
                        <br/>
                        <button type="submit" class="btn btn-success">Cargar</button>
                    </form>
                </div>
            </template>
        </ModalComponent>
        <!--Fin del modal-->

        <!-- Manual -->
        <ModalComponent v-if="modal==3"
            :titulo="'Manual'" @closeModal="cerrarModal()"
        >
            <template v-slot:body>
                 <p>
                    Para poder agregar una licencia a un lote debe asegurarse de tener creado(s) los lotes deseados,
                    puede crearlos desde el módulo “Desarrollo->Lotes”.
                </p>
                <p>
                    Para poder ver los botones que permite asignar la licencia debe asegurarse de seleccionar al menos
                        un lote de la lista dando clic sobre la casilla que aparece del lado izquierdo.
                </p>
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
            TableComponent,
            ModalComponent
        },
        props:{
            rolId:{type: String}
        },
        data(){
            return{
                proceso:false,
                id: 0,
                siembra:'',
                f_planos:'',
                f_planos_obra: '',
                f_ingreso:'',
                f_salida:'',
                fecha_comentario:'',
                arquitecto_id:0,
                perito_dro:0,
                arquitecto:'',
                fraccionamiento:'',
                num_licencia:0,
                foto_lic: '',
                foto_predial: '',
                credito_puente: '',
                etapa_servicios: '',
                clv_catastral: '',
                fraccionamiento_id : 0,
                observacion:'',
                observacion_completa:'',
                usuario: '',
                etapa_id: 0,
                manzana: '',
                num_lote: 0,
                sublote: '',
                modelo: 0,
                empresa_id: 0,
                calle: '',
                numero: '',
                interior: '',
                terreno : 0.00,
                construccion : 0.00,
                allLic: [],
                allSelected: false,
                arrayLicencias : [],
                arrayArquitectos:[],
                arrayFraccionamientos:[],
                arrayObservacion:[],
                arrayPuentes: [],
                arrayRuv: [],
                modal : 0,
                modalObs : 0,
                tituloModal : '',
                tituloObs: '',
                tipoAccion: 0,
                errorLote : 0,
                errorMostrarMsjLote : [],
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
                buscar2 : '',
                b_manzana : '',
                b_lote : '',
                b_modelo : '',
                b_arquitecto : '',
                b_puente:'',
                b_num_inicio:'',
                empresas:[],
                b_empresa:'',
                b_empresa2:'',
                b_etapa:'',
                b_ruv:'',
                arrayAllEtapas:[]
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
            selectAll: function() {
                this.allLic = [];

                if (!this.allSelected) {
                    for (var lote in this.arrayLicencias
                    ) {
                        this.allLic.push(this.arrayLicencias[lote].id.toString());
                    }
                }
            },
            select: function() {
                this.allSelected =   false;
            },
            onImageChangePredial(e){

                console.log(e.target.files[0]);

                this.foto_predial = e.target.files[0];

            },

            formSubmitPredial(e) {

                e.preventDefault();

                let currentObj = this;

                let formData = new FormData();

                formData.append('foto_predial', this.foto_predial);
                axios.post('/formSubmitPredial/'+this.id, formData)
                .then(function (response) {

                    currentObj.success = response.data.success;
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Archivo guardado correctamente',
                        showConfirmButton: false,
                        timer: 2000
                        })
                    me.cerrarModal();
                   me.listarLicencias(me.pagination.current_page,me.buscar,me.b_manzana,me.b_lote,me.b_modelo,me.b_arquitecto,me.criterio,me.buscar2);

                })

                .catch(function (error) {

                    currentObj.output = error;

                });

            },

            //funciones para carga de las licencias
            onImageChange(e){

                console.log(e.target.files[0]);

                this.foto_lic = e.target.files[0];

            },

            formSubmit(e) {
                e.preventDefault();

                let currentObj = this;

                let formData = new FormData();

                formData.append('foto_lic', this.foto_lic);
                let me = this;
                axios.post('/formSubmitLicencias/'+this.id, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Archivo guardado correctamente',
                        showConfirmButton: false,
                        timer: 2000
                        })
                    me.cerrarModal();
                    me.listarLicencias(me.pagination.current_page,me.buscar,me.b_manzana,me.b_lote,me.b_modelo,me.b_arquitecto,me.criterio,me.buscar2);
                })

                .catch(function (error) {
                    currentObj.output = error;
                });

            },

            /**Metodo para mostrar los registros */
            listarLicencias(page, buscar,b_manzana,b_lote,b_modelo,b_arquitecto, criterio,buscar2){
                let me = this;
                var url = '/licencias?page=' + page + '&buscar=' + buscar + '&b_manzana=' + b_manzana + '&b_lote='+ b_lote +
                            '&b_modelo='+ b_modelo + '&b_arquitecto='+ b_arquitecto + '&criterio=' + criterio + '&buscar2=' + buscar2
                            + '&b_puente=' + me.b_puente + '&b_num_inicio=' + me.b_num_inicio + '&b_empresa=' + me.b_empresa + '&b_empresa2=' + me.b_empresa2
                            + '&b_etapa=' + me.b_etapa + '&b_ruv=' + me.b_ruv;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLicencias = respuesta.licencias.data;
                    me.pagination = respuesta.pagination;
                    console.log(url);
                })
                .catch(function (error) {
                    console.log(error);
                });

            },
            listarObservacion(page, buscar){
                let me = this;
                var url = '/observacion?page=' + page + '&buscar=' + buscar ;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayObservacion = respuesta.observacion.data;
                    me.pagination = respuesta.pagination;
                    console.log(url);
                })
                .catch(function (error) {
                    console.log(error);
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
                    console.log(error);
                });
            },

            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },

            selectArquitectos(){
                let me = this;
                me.arrayArquitectos=[];
                var url = '/select_personal?departamento_id=3';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayArquitectos = respuesta.personal;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectPuente(id){
                let me = this;

                me.arrayPuentes=[];
                var url = '/selectCreditoPuente?fraccionamiento=' + id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPuentes = respuesta.creditos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectRuv(){
                let me = this;

                me.arrayRuv=[];
                me.b_ruv = '';
                var url = '/ruvs/selectRuv?proyecto=' + me.buscar + '&etapa=' + me.b_etapa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayRuv = respuesta.ruvs;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectUltimoComentario(lote){
                let me = this;
                var url = '/observacion/select_ultima?buscar=' + lote;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.observacion = respuesta.observacion.comentario;
                    me.usuario = respuesta.observacion.usuario;
                    me.fecha_comentario = moment(respuesta.observacion.created_at,"YYYY-MM-DD hh:mm:ss").locale('es').fromNow();

                    me.observacion_completa= me.observacion + ' ' + '(' + me.usuario + ' - ' + me.fecha_comentario + ')';
                })
                .catch(function (error) {
                    console.log(error);
                });

            },
            selectFraccionamientos(){
                let me = this;
                me.buscar="";
                me.b_arquitecto="";
                me.b_manzana="";
                me.b_lote="";
                me.b_modelo="";
                me.buscar2="";
                me.b_ruv = '';
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

            cambiarPagina(page,buscar,b_manzana,b_lote,b_modelo,b_arquitecto,criterio,buscar2){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarLicencias(page,buscar,b_manzana,b_lote,b_modelo,b_arquitecto,criterio,buscar2);
            },
            selectEtapas(buscar){
                let me = this;
                me.b_etapa="";

                me.arrayAllEtapas=[];
                var url = '/select_etapa_proyecto?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayAllEtapas = respuesta.etapas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            agregarComentario(){
                if(this.proceso==true){
                    return;
                }
                this.proceso=true;
                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/observacion/registrar',{
                    'lote_id': this.lote_id,
                    'comentario': this.observacion,
                    'usuario': this.usuario
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarObs(); //al guardar el registro se cierra el modal

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

            actualizarMasa(){
                 if(this.proceso==true){
                    return;
                }
                this.proceso=true;
                let me = this;
                //Con axios se llama el metodo update de LoteController

                 Swal({
                    title: 'Estas seguro?',
                    animation: false,
                    customClass: 'animated bounceInDown',
                    text: "DRO y fecha se asignaran a los lotes seleccionados",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',

                    confirmButtonText: 'Si, asignar!'
                    }).then((result) => {

                    if (result.value) {
                        me.allLic.forEach(element => {
                            axios.put('/licencias/actualizarMasa',{
                                'id':element,
                                'f_planos_obra' : this.f_planos_obra,
                                'perito_dro' : this.perito_dro

                            });
                        })
                        me.proceso=false;
                        me.cerrarModal();
                        me.listarLicencias(me.pagination.current_page,me.buscar,me.b_manzana,me.b_lote,me.b_modelo,me.b_arquitecto,me.criterio,me.buscar2);
                        Swal({
                            title: 'Hecho!',
                            text: 'Se han asignado',
                            type: 'success',
                            animation: false,
                            customClass: 'animated bounceInRight'
                        })
                    }})

            },

            updateMasaLicencias(){
                 if(this.proceso==true){
                    return;
                }
                this.proceso=true;
                let me = this;
                //Con axios se llama el metodo update de LoteController

                 Swal({
                    title: 'Estas seguro?',
                    animation: false,
                    customClass: 'animated bounceInDown',
                    text: "Las licencias se asignaran a los lotes seleccionados",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',

                    confirmButtonText: 'Si, asignar!'
                    }).then((result) => {

                    if (result.value) {
                        me.allLic.forEach(element => {
                            axios.put('/licencias/updateMasaLicencias',{
                                'id':element,
                                'f_ingreso' : this.f_ingreso,
                                'f_salida' : this.f_salida,
                                'num_licencia' : this.num_licencia

                            });
                        })
                        me.proceso=false;
                        me.cerrarModal();
                        me.listarLicencias(me.pagination.current_page,me.buscar,me.b_manzana,me.b_lote,me.b_modelo,me.b_arquitecto,me.criterio,me.buscar2);
                        Swal({
                            title: 'Hecho!',
                            text: 'Se han asignado',
                            type: 'success',
                            animation: false,
                            customClass: 'animated bounceInRight'
                        })
                    }})

            },

            actualizarLicencia(){
                if(this.proceso==true){
                    return;
                }
                this.proceso=true;
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/licencias/actualizar',{
                    'id':this.id,
                    'f_planos' : this.f_planos,
                    'f_planos_obra' : this.f_planos_obra,
                    'f_ingreso' : this.f_ingreso,
                    'f_salida' : this.f_salida,
                    'num_licencia' : this.num_licencia,
                    'arquitecto_id':this.arquitecto_id,
                    'perito_dro' : this.perito_dro


                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal();
                    me.listarLicencias(me.pagination.current_page,me.buscar,me.b_manzana,me.b_lote,me.b_modelo,me.b_arquitecto,me.criterio,me.buscar2);
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
            validarLote(){
                this.errorLote=0;
                this.errorMostrarMsjLote=[];

                if(!this.fraccionamiento_id) //Si la variable Lote esta vacia
                    this.errorMostrarMsjLote.push("El nombre del proyecto para el Lote no puede ir vacio.");

                if(this.errorMostrarMsjLote.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorLote = 1;

                return this.errorLote;
            },

            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.f_planos = '';
                this.f_planos_obra = '';
                this.f_ingreso = '';
                this.f_salida = '';
                this.num_licencia = '';
                this.arquitecto='';
                this.fraccionamiento='';
                this.etapa_id=0;
                this.manzana='';
                this.num_lote='';
                this.sublote='';
                this.modelo='';
                this.calle='';
                this.numero=''
                this.interior=''
                this.terreno='';
                this.construccion='';
                this.clv_catastral='';
                this.etapa_servicios='';
                this.siembra='';
                this.id=0;
                this.observacion_completa='';
                this.foto_lic = '';
                this.foto_predial = '';
                this.perito_dro = 0;

                this.errorLote = 0;
                this.errorMostrarMsjLote = [];

            },
            cerrarObs(){
                this.modalObs = 0;
                this.tituloObs = '';
                this.usuario = '';
                this.observacion = '';
            },

            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(accion,data =[]){
                switch(accion){

                    case 'actualizar':
                    {
                        this.modal =1;
                        this.tituloModal='Actualizar Licencia';
                        this.f_planos=data['f_planos'];
                        this.f_planos_obra=data['f_planos_obra'];
                        this.f_ingreso=data['f_ingreso'];
                        this.f_salida=data['f_salida'];
                        this.num_licencia=data['num_licencia'];
                        this.arquitecto_id=data['arquitecto_id'];
                        this.perito_dro=data['perito_dro'];
                        this.id=data['id'];
                        break;
                    }

                    case 'subirArchivo':
                    {
                        this.modal =4;
                        this.tituloModal='Subir Archivo';
                        this.tipoAccion=5;
                        this.id=data['id'];
                        this.num_licencia=data['num_licencia'];
                        this.foto_lic=data['foto_lic'];
                        this.foto_predial=data['foto_predial'];
                        break;
                    }

                    case 'asignarMasa':
                    {
                        this.modal = 5;
                        this.tituloModal='Asignación en masa';
                        this.perito_dro = '';
                        this.f_planos_obra='';
                        this.tipoAccion = 1;
                        break;
                    }
                    case 'asignarLicencias':
                    {
                        this.modal = 5;
                        this.tituloModal='Asignación de licencias';
                        this.f_ingreso = '';
                        this.f_salida='';
                        this.num_licencia = '';
                        this.tipoAccion = 2;
                        break;
                    }

                    case 'ver':
                    {
                        this.modal = 2;
                        this.tituloModal='Consulta ';
                            if(data['f_planos_obra'])
                            this.f_planos_obra=moment(data['f_planos_obra']).locale('es').format('DD/MMM/YYYY');
                        if(data['f_planos'])
                            this.f_planos=moment(data['f_planos']).locale('es').format('DD/MMM/YYYY');
                        if(data['f_ingreso'])
                            this.lic_ingreso=moment(data['f_ingreso']).locale('es').format('DD/MMM/YYYY');
                        if(data['f_salida'])
                            this.lic_salida=moment(data['f_salida']).locale('es').format('DD/MMM/YYYY');
                        this.num_licencia=data['num_licencia'];
                        this.arquitecto=data['arquitecto'];
                        this.fraccionamiento=data['fraccionamiento'];
                        this.etapa_id=data['etapa_id'];
                        this.manzana=data['manzana'];
                        this.num_lote=data['num_lote'];
                        this.sublote=data['sublote'];
                        this.modelo=data['modelo'];
                        this.calle=data['calle'];
                        this.numero=data['numero'];
                        this.interior=data['interior'];
                        this.terreno=data['terreno'];
                        this.construccion=data['construccion'];
                        this.clv_catastral=data['clv_catastral'];
                        this.etapa_servicios=data['etapa_servicios'];
                        this.siembra=moment(data['siembra']).locale('es').format('DD/MMM/YYYY');
                        this.id=data['id'];

                        this.selectUltimoComentario(data['id']);
                        this.listarObservacion(1, data['id']);
                        break;
                    }
                }
                this.selectArquitectos();

            },
            abrirModalObs(accion,lote){
                switch(accion){
                    case 'observacion':
                    {
                        this.modalObs =1;
                        this.tituloObs='Agregar Observación';
                        this.observacion='';
                        this.usuario='';
                        this.lote_id=lote;
                        this.tipoAccion= 3;
                        break;
                    }
                    case 'ver_todo':
                    {
                        this.modalObs =1;
                        this.tituloObs='Consulta Observaciones';
                        this.tipoAccion= 4;
                        break;
                    }

                }
            },


        },
        mounted() {
            this.listarLicencias(1,this.buscar,this.b_manzana,this.b_lote,this.b_modelo,this.b_arquitecto,this.criterio,this.buscar2);
            this.selectFraccionamientos();
            this.getEmpresa();
            this.selectRuv();
        }
    }
</script>
<style scoped>
    .form-control:disabled, .form-control[readonly] {
    background-color: rgba(0, 0, 0, 0.06);
    opacity: 1;
    font-size: 1rem;
    color: #27417b;
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
