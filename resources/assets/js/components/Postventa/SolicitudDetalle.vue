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
                        <i class="fa fa-align-justify"></i> Solicitud de detalles&nbsp;
                        <!--   Boton Nuevo    -->
                        <template v-if="descripcion == 0">
                            <Button :icon="'icon-plus'"
                                @click="abrirModal('nuevo')">Nueva Solicitud
                            </Button>
                            <Button :btnClass="'btn-dark'" :icon="'icon-calendar'"  @click="abrirModal('agenda')">
                                Agenda
                            </Button>
                        </template>
                        <Button v-else @click="listarSolicitudes(pagination2.current_page,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2,status)"
                            :icon="'fa fa-mail-reply-all'" :btnClass="'btn_secondary'">
                            Regresar
                        </Button>
                    </div>
                    <div class="info-center" v-if="loading">
                        <LoadingComponent></LoadingComponent>
                    </div>

                    <template v-else>
                    <!-------------------  Div Reportes de detalles  --------------------->
                        <div class="card-body" v-if="descripcion == 0">
                            <div class="form-group row">
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <!--Criterios para el listado de busqueda -->
                                        <select class="form-control col-md-5" v-model="criterio2" @change="selectFraccionamientos()">
                                            <option value="lotes.fraccionamiento_id">Proyecto</option>
                                            <option value="solic_detalles.cliente">Cliente</option>
                                            <option value="contratistas.nombre">Contratista</option>
                                            <option value="contratos.id"># Folio</option>
                                        </select>

                                        <select class="form-control" v-if="criterio2=='lotes.fraccionamiento_id'" v-model="buscar2" @change="selectEtapa(buscar2)">
                                            <option value="">Seleccione</option>
                                            <option v-for="fraccionamiento in arrayFraccionamientos2" :key="fraccionamiento.nombre" :value="fraccionamiento.id" v-text="fraccionamiento.nombre"></option>
                                        </select>

                                        <input v-else type="text" v-model="buscar2" @keyup.enter="listarSolicitudes(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2,status)" class="form-control" placeholder="Texto a buscar">

                                    </div>
                                    <div class="input-group" v-if="criterio2=='lotes.fraccionamiento_id'">
                                        <select class="form-control col-md-6" @change="selectManzanas(buscar2,b_etapa2)" v-model="b_etapa2">
                                            <option value="">Etapa</option>
                                            <option v-for="etapa in arrayEtapas2" :key="etapa.num_etapa" :value="etapa.id" v-text="etapa.num_etapa"></option>
                                        </select>
                                    </div>
                                    <div class="input-group" v-if="criterio2=='lotes.fraccionamiento_id'">
                                        <select class="form-control" @change="selectLotesManzana(buscar2,b_etapa2,b_manzana2)" @keyup.enter="listarSolicitudes(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2,status)" v-model="b_manzana2" >
                                            <option value="">Manzana</option>
                                            <option v-for="manzana in arrayAllManzanas" :key="manzana.manzana" :value="manzana.manzana" v-text="manzana.manzana"></option>
                                        </select>
                                        <input type="text"  v-model="b_lote2" @keyup.enter="listarSolicitudes(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2,status)" class="form-control" placeholder="Lote a buscar">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <!--Criterios para el listado de busqueda -->
                                        <input type="text" disabled class="form-control" v-model="b_fecha1" placeholder="Fecha:">
                                        <input type="date" class="form-control" v-model="b_fecha1">
                                        <input type="date" class="form-control" v-model="b_fecha2">
                                    </div>
                                </div>

                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <select class="form-control" v-model="status" >
                                            <option value="">Status</option>
                                            <option value="0">Pendiente</option>
                                            <option value="1">En proceso</option>
                                            <option value="2">Concluido</option>
                                            <option value="3">Cancelado</option>
                                        </select>
                                        <Button :icon="'fa fa-search'"
                                            @click="listarSolicitudes(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2,status)">
                                            Buscar
                                        </Button>
                                        <a class="btn btn-success"
                                            :href="'/detalles/excelSolicitudes?buscar=' + buscar2 + '&b_etapa=' + b_etapa2 + '&b_manzana=' + b_manzana2 + '&b_lote=' + b_lote2 + '&criterio=' + criterio2 + '&status=' + status">
                                            <i class="fa fa-file-text"></i> Excel
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <TableComponent :cabecera="[
                                '','# Folio','Proyecto','Etapa','Manzana',
                                'Lote','Fecha de reporte','Fecha conclusión','Status','Reporte de conclusión'
                            ]">
                                <template v-slot:tbody>
                                    <tr v-for="contratos in arraySolicitudes" :key="contratos.id" @dblclick="verDetalles(contratos.id)" title="Doble click">
                                        <td class="td2">
                                            <Button  v-if="contratos.status == 0" title="Cancelar"
                                                :icon="'icon-trash'"
                                                :btnClass="'btn-danger'" :size="'btn-sm'"
                                                @click="cancelarSolicitud(contratos.id)" >
                                            </Button>
                                        </td>
                                        <td class="td2">
                                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">{{contratos.id}}</a>
                                            <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
                                                <a class="dropdown-item" v-if="!contratos.nom_contrato" @click="abrirModal('subir_archivo',contratos)">Subir Solicitud firmada</a>
                                                <template v-else>
                                                    <a class="dropdown-item" v-bind:href="'/files/'+'solicitudDetalle/'+ contratos.nom_contrato + '/download'" >Descargar solicitud</a>
                                                    <a class="dropdown-item" @click="eliminarArchivo(contratos.nom_contrato, contratos.id)" >Eliminar archivo</a>
                                                </template>
                                            </div>
                                        </td>
                                        <td class="td2" v-text="contratos.fraccionamiento"></td>
                                        <td class="td2" v-text="contratos.etapa"></td>
                                        <td class="td2" v-text="contratos.manzana"></td>
                                        <td class="td2" v-text="contratos.num_lote"></td>
                                        <td class="td2" v-text="this.moment(contratos.created_at).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td class="td2">
                                            {{  contratos.fecha_concluido ? this.moment(contratos.fecha_concluido).locale('es').format('DD/MMM/YYYY') : '' }}
                                            <Button v-if="!contratos.fecha_concluido && (contratos.status == 1 || contratos.status == 0)"
                                                :btnClass="'btn-scarlet'" :icon="'icon-check'"
                                                @click="abrirModal('finalizar', contratos)"
                                            >
                                                Finalizar
                                            </Button>
                                        </td>
                                        <td class="td2">
                                            <span v-if="contratos.status == '0'" class="badge badge-warning">Pendiente</span>
                                            <span v-if="contratos.status == '1'" class="badge badge-warning">En proceso</span>
                                            <span v-if="contratos.status == '2'" class="badge badge-success">Concluido</span>
                                            <span v-if="contratos.status == '3'" class="badge badge-danger">Cancelado</span>
                                        </td>
                                        <!-- <td class="td2">
                                            <a title="Ver revision" type="button"
                                                    class="btn btn-danger pull-right" target="_blank" :href="'/detalles/reporteDetalles/pdf/'+contratos.id">
                                                    <i class="fa fa-file-pdf-o"></i>&nbsp;Reporte
                                            </a>
                                        </td> -->
                                        <td class="td2">
                                            <a  v-if="contratos.status == '2'" title="Ver revision" type="button"
                                                class="btn btn-danger pull-right" target="_blank" :href="'/detalles/reporteDetalles/reporteConclusionPDF/'+contratos.id">
                                                <i class="fa fa-file-pdf-o"></i>&nbsp;Reporte conclusión
                                            </a>
                                            <span v-if="contratos.status == '0'" class="badge badge-warning">Pendiente</span>
                                            <span v-if="contratos.status == '1'" class="badge badge-warning">En proceso</span>
                                            <span v-if="contratos.status == '3'" class="badge badge-danger">Cancelado</span>
                                        </td>
                                    </tr>
                                </template>
                            </TableComponent>
                            <NavComponent :current="pagination2.current_page"
                                :last="pagination2.last_page"
                                @changePage="cambiarPagina2"
                            >
                            </NavComponent>
                        </div>
                    <!-------------------  Fin Div para Reportes de Detalles  --------------------->

                    <!-------------------  Div descripcion de detalles  --------------------->
                        <div class="card-body" v-if="descripcion == 1">
                            <TableComponent :cabecera="[
                                'General','Subconcepto','Detalle','Observación',
                                'Garantia','Costo','Fecha conclusión',''
                            ]">
                                <template v-slot:tbody>
                                    <tr v-for="descripcion in arrayDescripcion" :key="descripcion.id">
                                        <td class="td2" v-text="descripcion.general"></td>
                                        <td class="td2" v-text="descripcion.subconcepto"></td>
                                        <td class="td2" v-text="descripcion.detalle"></td>
                                        <td class="td2" v-text="descripcion.observacion"></td>
                                        <td v-if="descripcion.garantia == 1"><span class="badge badge-success">Si</span> </td>
                                        <td v-else> <span  class="badge badge-danger">No</span> </td>
                                        <td class="td2" v-text="'$'+$root.formatNumber(descripcion.costo)"></td>
                                        <td class="td2" v-if="descripcion.fecha_concluido" v-text="this.moment(descripcion.fecha_concluido).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td class="td2" v-else v-text="'En proceso'"></td>
                                        <template>
                                            <td class="td2" v-if="descripcion.fecha_concluido && descripcion.revisado==0">
                                                <Button :btnClass="'btn-success'" :size="'btn-sm'" title="Aprobar"
                                                    :icon="'fa fa-check-square'"
                                                    @click="revisarDetalle(descripcion.id, 1,'')"
                                                ></Button>
                                                <Button :btnClass="'btn-danger'" :size="'btn-sm'" title="Rechazado"
                                                    :icon="'fa fa-times-circle-o'"
                                                    @click="abrirModal('revisar',descripcion)"
                                                ></Button>
                                            </td>
                                            <td class="td2" v-else-if="descripcion.fecha_concluido && descripcion.revisado==2">
                                                <span  class="badge badge-success">Concluido</span>
                                            </td>
                                            <td v-else></td>
                                        </template>
                                    </tr>
                                </template>
                            </TableComponent>
                        </div>
                    </template>
                <!-------------------  Fin Div para Reportes de Detalles  --------------------->

                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>

            <!--Inicio del modal para diversos llenados de tabla en historial -->
            <ModalComponent :titulo="tituloModal"
                @closeModal="cerrarModal()"
                v-if="modal==1"
            >
                <template v-slot:body>
                    <RowModal :clsRow1="'col-md-4'" :label1="'Proyecto'" :clsRow2="'col-md-4'" :label2="'Etapa'">
                        <select class="form-control" v-model="proyecto" @change="selectEtapaEntregada(proyecto),etapa_entregada = '',manzana_entregada = '',lote_entregado=''">
                            <option value="">Seleccione</option>
                            <option v-for="fraccionamiento in arrayFraccionamientos2" :key="fraccionamiento.nombre" :value="fraccionamiento.id" v-text="fraccionamiento.nombre"></option>
                        </select>
                        <template v-slot:input2>
                            <select class="form-control" v-model="etapa_entregada" @change="selectManzanaEntregada(etapa_entregada),manzana_entregada = '',lote_entregado=''">
                                <option value=''> Seleccione </option>
                                <option v-for="etapas in arrayEtapas" :key="etapas.etapa" :value="etapas.etapa" v-text="etapas.etapa"></option>
                            </select>
                        </template>
                    </RowModal>
                    <RowModal :clsRow1="'col-md-4'" :label1="'Manzana'" :clsRow2="'col-md-4'" :label2="'Lote'">
                        <select class="form-control" v-model="manzana_entregada" @change="selectLotesEntregado(manzana_entregada, etapa_entregada, proyecto),lote_entregado=''">
                            <option value=''> Seleccione </option>
                            <option v-for="manzanas in arrayManzanas" :key="manzanas.manzana" :value="manzanas.manzana" v-text="manzanas.manzana"></option>
                        </select>
                        <template v-slot:input2>
                            <select class="form-control" v-model="lote_entregado" @change="getDatosLote(lote_entregado)">
                                <option value=''> Seleccione </option>
                                <option v-for="lotes in arrayLotes" :key="lotes.id" :value="lotes.id">
                                    {{ lotes.sublote ? `${lotes.num_lote} ${lotes.sublote}` : lotes.num_lote }}
                                </option>
                            </select>
                        </template>
                    </RowModal>

                    <template v-if="direccion != '' || direccion">
                        <div class="form-group row line-separator"></div>
                        <RowModal :clsRow1="'col-md-4'" :label1="'Modelo'">
                            <input type="text" disabled v-model="modelo" class="form-control">
                        </RowModal>
                        <RowModal :clsRow1="'col-md-7'" :label1="'Dirección'">
                            <input type="text" disabled v-model="direccion" class="form-control">
                        </RowModal>
                        <RowModal :clsRow1="'col-md-4'" :label1="'Cliente'" :clsRow2="'col-md-4'" :label2="'Celular'">
                            <input type="text" disabled v-model="cliente" class="form-control">
                            <template v-slot:input2>
                                <input type="text" v-model="celular" maxlength="10" v-on:keypress="$root.isNumber($event)" class="form-control">
                            </template>
                        </RowModal>
                        <RowModal :clsRow1="'col-md-4'" :label1="'# Dias desde entrega'">
                            <h6 class="form-control" v-text="dias_entregado"></h6>
                        </RowModal>
                        <div class="form-group row">
                            <label class="col-md-2 form-control-label" for="text-input">Disponibilidad cliente</label>
                            <div class="col-md-1">
                                L <input v-model="lunes" type="checkbox" value="1"/>
                            </div>
                            <div class="col-md-1">
                                M <input v-model="martes" type="checkbox" value="1"/>
                            </div>
                            <div class="col-md-1">
                                Mi <input v-model="miercoles" type="checkbox" value="1"/>
                            </div>
                            <div class="col-md-1">
                                J <input v-model="jueves" type="checkbox" value="1"/>
                            </div>
                            <div class="col-md-1">
                                V <input v-model="viernes" type="checkbox" value="1"/>
                            </div>
                            <div class="col-md-1">
                                S <input v-model="sabado" type="checkbox" value="1"/>
                            </div>
                            <div class="col-md-4">
                                <input class="form-control" v-model="horario" type="text" placeholder="Horario disponible de cliente"/>
                            </div>
                        </div>
                        <RowModal :clsRow1="'col-md-6'" :label1="'Contratista'">
                            <select class="form-control" v-model="contratista" :disabled="dias_entregado <= 60">
                                <option value=''> Seleccione </option>
                                <option v-for="contratista in arrayContratistas" :key="contratista.id" :value="contratista.id" v-text="contratista.nombre"></option>
                            </select>
                        </RowModal>
                        <div class="form-group row line-separator"></div>
                        <RowModal :clsRow1="'col-md-4'" :label1="'Detalle General'" :label2="'Subconcepto'" :clsRow2="'col-md-4'">
                            <select class="form-control" v-model="id_gral" @change="selectSubconcepto(id_gral)" >
                                <option value=''> Seleccione </option>
                                <option v-for="general in arrayGenerales" :key="general.id" :value="general.id" v-text="general.general"></option>
                            </select>
                            <template v-slot:input2>
                                <select class="form-control" v-model="id_sub" @change="selectDetalle(id_sub)" >
                                    <option value=''> Seleccione </option>
                                    <option v-for="subconcepto in arraySubconceptos" :key="subconcepto.id" :value="subconcepto.id" v-text="subconcepto.subconcepto"></option>
                                </select>
                            </template>
                        </RowModal>
                        <RowModal :clsRow1="'col-md-6'" :label1="'Detalle'">
                            <select class="form-control" v-model="id_detalle" @change="getDatosDetalle(id_detalle)">
                                <option value=''> Seleccione </option>
                                <option v-for="detalle in arrayDetalles" :key="detalle.id" :value="detalle.id" v-text="detalle.detalle"></option>
                            </select>
                        </RowModal>
                        <RowModal :clsRow1="'col-md-6'" :label1="'Descripción'" v-if="detalle != ''" :label2="''" :clsRow2="'col-md-2'">
                            <input type="text" v-model="observacion" class="form-control">
                            <template v-slot:input2>
                                <Button @click="addDetalle(id_detalle)" title="Añadir"
                                    :btnClass="'btn-success'" :icon="'icon-plus'"
                                >
                                </Button>
                            </template>
                        </RowModal>
                        <RowModal :clsRow1="'col-md-12'" :label1="''">
                            <TableComponent :cabecera="[
                                '','General','Subconcepto','Detalle','Descripción','Garantia'
                            ]">
                                <template v-slot:tbody>
                                    <tr v-for="(detalle,index) in arrayListadoDetalles" :key="detalle.id_detalle">
                                        <td>
                                            <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm" title="Quitar concepto">
                                                <i class="icon-close"></i>
                                            </button>
                                        </td>
                                        <td v-text="detalle.general"></td>
                                        <td v-text="detalle.subconcepto"></td>
                                        <td v-text="detalle.detalle"></td>
                                        <td v-text="detalle.observacion"></td>
                                        <td v-if="detalle.garantia == 1" v-text="'Si'"></td>
                                        <td v-else v-text="'No'"></td>
                                    </tr>
                                </template>
                            </TableComponent>
                        </RowModal>
                        <div v-show="errorEntrega" class="form-group row div-error">
                            <div class="text-center text-error">
                                <div v-for="error in errorMostrarMsjEntrega" :key="error" v-text="error">
                                </div>
                            </div>
                        </div>
                    </template>
                </template>
                <template v-slot:buttons-footer>
                    <Button v-if="arrayListadoDetalles.length"
                        :btnClass="'btn-primary'" :icon="'icon-check'"
                        @click="registrarSolicitud()"
                    >
                        Guardar
                    </Button>
                </template>
            </ModalComponent>
            <!--Fin del modal-->

            <!--Inicio del modal para archivos -->
            <ModalComponent v-if="modalArchivo"
                :titulo="tituloModal"
                @closeModal="cerrarModal()"
            >
                <template v-slot:body>
                    <RowModal :clsRow1="'col-md-8'" :label1="''">
                        <form class="form-horizontal" @submit="dropboxSubmit" method="POST" enctype="multipart/form-data">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                    <i class="fa fa-file"></i>
                                    </span>
                                    <input type="file" v-on:change="dropboxFile" name="file" required>
                                    <button class="btn btn-primary" type="submit">Guardar archivo</button>
                                </div>
                            </div>
                        </form>
                    </RowModal>
                </template>
            </ModalComponent>
            <!--Fin del modal-->

            <!--Inicio del modal observaciones-->
            <ModalComponent v-if="modal2"
                :titulo="'Rechazar'"
                @closeModal="cerrarModal()"
            >
                <template v-slot:body>
                    <RowModal :clsRow1="'col-md-8'" :label1="'Observación'">
                        <input type="text" v-model="observacion" class="form-control">
                    </RowModal>
                </template>
                <template v-slot:buttons-footer>
                    <Button :icon="'icon-save'"
                        @click="revisarDetalle(descripcion_id,0,observacion)"
                    >Guardar</Button>
                </template>
            </ModalComponent>
            <!--Fin del modal-->

            <!--Inicio del modal para agenda contratista -->
            <ModalComponent v-if="modal == 2"
                :titulo="tituloModal"
                @closeModal="cerrarModal()"
            >
                <template v-slot:body>
                    <RowModal :clsRow1="'col-md-6'" :label1="'Proyecto'" :clsRow2="'col-md-4'" :label2="''">
                        <select class="form-control" v-model="proyecto" @change="selectEtapa(proyecto)">
                            <option value="">Fraccionamiento</option>
                            <option v-for="fraccionamiento in arrayFraccionamientos2" :key="fraccionamiento.nombre" :value="fraccionamiento.id" v-text="fraccionamiento.nombre"></option>
                        </select>
                        <template v-slot:input2>
                            <select class="form-control" v-model="etapa">
                                <option value="">Etapa</option>
                                <option v-for="etapa in arrayEtapas2" :key="etapa.num_etapa" :value="etapa.id" v-text="etapa.num_etapa"></option>
                            </select>
                        </template>
                    </RowModal>

                    <RowModal :clsRow1="'col-md-6'" :label1="'Contratista'">
                        <select class="form-control" v-model="contratista">
                            <option value=''> Seleccione </option>
                            <option v-for="contratista in arrayContratistas" :key="contratista.id" :value="contratista.id" v-text="contratista.nombre"></option>
                        </select>
                    </RowModal>

                    <RowModal :clsRow1="'col-md-4'" :label1="'Fecha'" :label2="''" :clsRow2="'col-md-4'">
                        <input type="date" v-model="fecha1" class="form-control">
                        <template v-slot:input2>
                            <input type="date" v-model="fecha2" class="form-control">
                        </template>
                    </RowModal>
                </template>
                <template v-slot:buttons-footer>
                    <a :href="'/detalles/agendaContratista?proyecto=' + proyecto + '&etapa='+ etapa +'&contratista=' + contratista + '&fecha1=' + fecha1 + '&fecha2=' + fecha2"  class="btn btn-success">
                        <i class="fa fa-file-text"></i> Descargar
                    </a>
                </template>
            </ModalComponent>
            <!--Fin del modal-->

            <ModalComponent v-if="modal == 3"
                :titulo="tituloModal"
                @closeModal="cerrarModal()"
            >
                <template v-slot:body>
                    <RowModal
                        :clsRow1="'col-md-4'"
                        :label1="'Fecha de conclusión'">
                        <input type="date" v-model="fecha1" class="form-control">
                    </RowModal>
                </template>
                <template v-slot:buttons-footer>
                    <Button :btnClass="'btn-success'"
                        :icon="'icon-check'"
                        @click="finalizarSolicitud()"
                    >
                    Finalizar
                </Button>
                </template>
            </ModalComponent>


     </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    import LoadingComponent from "../Componentes/LoadingComponent.vue";
    import TableComponent from "../Componentes/TableComponent.vue";
    import ModalComponent from "../Componentes/ModalComponent.vue";
    import NavComponent from "../Componentes/NavComponent.vue";
    import RowModal from '../Componentes/ComponentesModal/RowModalComponent.vue';
    import Button from "../Componentes/ButtonComponent.vue";

    export default {
        components:{
            LoadingComponent,
            TableComponent,
            ModalComponent,
            NavComponent,
            RowModal,
            Button
        },
        data(){
            return{
                loading : true,
                observacion:'',
                arrayObservacion : [],

                arrayFraccionamientos2:[],
                arrayEtapas2:[],
                arrayAllManzanas:[],
                arraySolicitudes:[],
                arrayContratistas:[],

                arrayGenerales : [],
                arraySubconceptos : [],
                arrayDetalles : [],
                arrayDatosDetalle : [],
                arrayDescripcion : [],

                // Variables para buscar lotes entregados
                arrayLotes:[],
                arrayEtapas:[],
                arrayManzanas:[],
                arrayDatosLotes:[],
                proyecto:'',
                etapa_entregada:'',
                manzana_entregada:'',
                lote_entregado:'',

                modal: 0,
                modal2 : 0,
                modalArchivo : 0,
                tituloModal: '',

                //variables
                lote_id: 0,
                direccion:'',
                contratista:'',
                cliente:'',
                dias_entregado:0,
                id_gral:'',
                id_sub:'',
                id_detalle:'',
                modelo :'',
                celular : '',
                proceso : false,
                file: '',

                lunes : 0,
                martes : 0,
                miercoles : 0,
                jueves : 0,
                viernes : 0,
                sabado : 0,

                horario : '',

                detalle : '',
                subconcepto:'',
                general:'',
                dias_garantia : '',
                garantia : '',
                solicitud_id:0,
                obs_gen:'',

                arrayListadoDetalles : [],

                folio:0,
                offset : 3,

                // Criterios para historial de contratos
                pagination2 : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                criterio2 : 'lotes.fraccionamiento_id',
                buscar2 : '',
                b_etapa2: '',
                b_manzana2: '',
                b_lote2: '',
                b_fecha1: '',
                b_fecha2: '',
                status:'',
                tipoAccion:0,
                observacion:'',
                descripcion: 0,
                descripcion_id :0,

                errorEntrega : 0,
                errorMostrarMsjEntrega : [],

                proyecto :'',
                etapa:'',
                fecha1:'',
                fecha2:''


            }
        },
        computed:{
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
            dropboxFile(e){

                console.log(e.target);

                this.file = e.target.files[0];

            },

            dropboxSubmit(e) {

                e.preventDefault();

                let formData = new FormData();

                formData.append('file', this.file);
                axios.post('/dropbox/files/'+this.solicitud_id+'/solicitudDetalle', formData)
                .then(function (response) {

                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Archivo subido correctamente',
                        showConfirmButton: false,
                        timer: 2000
                        })
                })

                .catch(function (error) {
                    console.log(error);

                });

            },

            eliminarArchivo(archivo,id){
                swal({
                title: '¿Desea eliminar este archivo?',
                text: "El archivo se borrara completamente",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si, borrar!'
                }).then((result) => {
                if (result.value) {
                    let me = this;

                //Con axios se llama el metodo update de LoteController
                    axios.delete('/files/delete',{
                        params: {
                        'file' : archivo,
                        'id' : id,
                        'sub' : 'solicitudDetalle'
                        }
                    }).then(function (response){
                        //window.alert("Cambios guardados correctamente");
                        me.listarSolicitudes(me.pagination2.current_page,me.buscar2,me.b_etapa2,me.b_manzana2,me.b_lote2,me.criterio2,me.status);
                        const toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                            });
                            toast({
                            type: 'success',
                            title: 'Archivo borrado correctamente'
                        })
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })

            },

            listarSolicitudes(page, buscar, b_etapa, b_manzana, b_lote, criterio, status){
                let me = this;
                this.descripcion = 0;
                me.loading = true;
                let url = '/detalles/indexSolicitudes?page=' + page
                    + '&buscar=' + buscar + '&b_etapa=' + b_etapa
                    + '&b_manzana=' + b_manzana + '&b_lote=' + b_lote + '&criterio=' + criterio + '&status=' + status
                    + '&b_fecha1=' + me.b_fecha1 + '&b_fecha2=' + me.b_fecha2;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arraySolicitudes = respuesta.solicitudes.data;
                    me.pagination2 = respuesta.pagination;
                    me.loading = false;

                })
                .catch(function (error) {
                    me.loading = false;
                    console.log(error);
                });
            },

            selectNombreArchivoModelo(id){
                let me = this;
                me.nombre_archivo_modelo='';
                var url = '/contrato/modelo/caracteristicas/pdf/' + id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                        me.nombre_archivo_modelo = respuesta.modelo[0].archivo;
                        window.open('/files/modelos/'+me.nombre_archivo_modelo, '_blank')
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectContratistas(){
                let me = this;
                me.arrayContratistas = [];
                var url = '/select_contratistas';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayContratistas = respuesta.contratista;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectFraccionamientos(){
                let me = this;
                me.buscar=""

                me.arrayFraccionamientos=[];
                me.arrayFraccionamientos2=[];
                me.arrayEtapas=[];
                var url = '/select_fraccionamiento';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayFraccionamientos = respuesta.fraccionamientos;
                    me.arrayFraccionamientos2 = respuesta.fraccionamientos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            actualizarFechaProgram(fecha,id){
                let me = this;

                axios.put('/detalles/updateFecha',{
                    'fecha_program':fecha,
                    'id': id,
                }).then(function (response){

                    me.listarSolicitudes(me.pagination2.current_page,me.buscar2,me.b_etapa2,me.b_manzana2,me.b_lote2,me.criterio2,me.status);

                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000

                    });

                    toast({
                    type: 'success',
                    title: 'Cambios guardados'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            finalizarSolicitud(){
                let me = this;
                axios.put('detalles/finalizarSolicitud',{
                    'fecha' : me.fecha1,
                    'id' : me.solicitud_id
                }).then(function (response){
                    me.listarSolicitudes(me.pagination2.current_page,me.buscar2,me.b_etapa2,me.b_manzana2,me.b_lote2,me.criterio2,me.status);

                    const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000

                    });

                    toast({
                        type: 'success',
                        title: 'Solicitud finalizada'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            actualizarHoraProgram(hora,id){
                let me = this;

                axios.put('/detalles/updateHora',{
                    'hora_program':hora,
                    'id': id,
                }).then(function (response){

                    me.listarSolicitudes(me.pagination2.current_page,me.buscar2,me.b_etapa2,me.b_manzana2,me.b_lote2,me.criterio2,me.status);

                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000

                    });

                    toast({
                    type: 'success',
                    title: 'Cambios guardados'
                    })
                }).catch(function (error){
                    console.log(error);
                });

            },

            selectEtapa(buscar){
                let me = this;
                me.b_etapa=""

                me.arrayEtapas=[];
                me.arrayEtapas2=[];
                var url = '/select_etapa_proyecto?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEtapas = respuesta.etapas;
                    me.arrayEtapas2 = respuesta.etapas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            verDetalles(id){
                let me = this;
                this.descripcion = 1;
                this.solicitud_id = id;
                me.arrayDescripcion=[];
                var url = '/detalles/indexDescripciones?id=' + id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayDescripcion = respuesta.detalles;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            //Select todas las manzanas
            selectManzanas(buscar1, buscar2){
                let me = this;
                me.b_manzana2="";
                me.b_lote2="";

                me.arrayAllManzanas=[];
                var url = '/select_manzanas_etapa?buscar=' + buscar1 + '&buscar1='+ buscar2;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayAllManzanas = respuesta.manzana;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            /// SELECT PARA LOTES ENTREGADOS
                selectEtapaEntregada(fraccionamiento){
                    let me = this;

                    me.arrayEtapas=[];
                    me.arrayManzanas=[];
                    var url = '/select_etapas_entregados?buscar=' + fraccionamiento;
                    axios.get(url).then(function (response) {
                        var respuesta = response.data;
                        me.arrayEtapas = respuesta.lotes_etapas;

                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                },
                selectManzanaEntregada(etapa){
                    let me = this;

                    me.arrayManzanas=[];
                    me.arrayLotes=[];
                    var url = '/select_manzanas_entregados?buscar=' + etapa;
                    axios.get(url).then(function (response) {
                        var respuesta = response.data;
                        me.arrayManzanas = respuesta.lotes_manzanas;

                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                },
                selectLotesEntregado(manzana,etapa,fraccionamiento){
                    let me = this;
                    me.arrayLotes=[];
                    var url = '/select_lotes_entregados?buscar=' + manzana + '&buscar2=' + etapa + '&buscar3=' + fraccionamiento;
                    axios.get(url).then(function (response) {
                        var respuesta = response.data;
                        me.arrayLotes = respuesta.lotes_entregados;

                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                },

                revisarDetalle(id,resultado,comentario){
                    let me = this;


                    axios.put('/detalles/updateResultado',{
                        'resultado':resultado,
                        'solicitud_id': me.solicitud_id,
                        'id' : id,
                        'comentario':comentario
                    }).then(function (response){

                        me.verDetalles(me.solicitud_id);
                        me.modal2 = 0;
                        me.observacion ='';

                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000

                        });

                        toast({
                        type: 'success',
                        title: 'Cambios guardados'
                        })
                    }).catch(function (error){
                        console.log(error);
                    });
                },

                getDatosLote(lote){
                    let me = this;
                    me.arrayDatosLotes=[];
                    var url = '/postventa/getDatosLoteEntregado?lote=' + lote;
                    axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    var arrayDatosContratista = [];
                        me.arrayDatosLotes = respuesta.datosLote;
                        me.arrayDatosContratista = respuesta.datosContratista;
                        me.lote_id = me.arrayDatosLotes[0]['lote_id'];
                        me.direccion = me.arrayDatosLotes[0]['direccion'];
                        me.cliente = me.arrayDatosLotes[0]['nombre_cliente'];
                        me.dias_entregado = me.arrayDatosLotes[0]['diferencia'];
                        me.folio = me.arrayDatosLotes[0]['folio'];
                        me.celular = me.arrayDatosLotes[0]['celular'];
                        me.modelo = me.arrayDatosLotes[0]['modelo'];

                        me.contratista = respuesta.datosContratista[0]['id'];

                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                },
            ///////////

            addDetalle(){
                let me = this;

                    me.arrayListadoDetalles.push({
                    id_gral: me.id_gral,
                    id_sub: me.id_sub,
                    id_detalle: me.id_detalle,
                    detalle: me.detalle,
                    garantia: me.garantia,
                    subconcepto: me.subconcepto,
                    general: me.general,
                    observacion : me.observacion,

                });
                me.id_gral = '';
                me.id_sub = '';
                me.id_detalle = '';
                me.detalle = '';
                me.garantia = '';
                me.subconcepto = '';
                me.general = '';
                me.observacion = '';

            },

            eliminarDetalle(index){
                let me = this;
                me.arrayListadoDetalles.splice(index,1);
            },

            /**Metodo para registrar  */
            registrarSolicitud(){
                if(this.proceso==true){
                    return;
                }

                this.proceso=true;
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/detalles/storeSolicitud',{
                    'data':this.arrayListadoDetalles,
                    'cliente': this.cliente,
                    'folio' : this.folio,
                    'lote_id':this.lote_entregado,
                    'contratista_id':this.contratista,
                    'dias_entrega': this.dias_entregado,
                    'lunes' : this.lunes,
                    'martes' : this.martes,
                    'miercoles' : this.miercoles,
                    'jueves' : this.jueves,
                    'viernes' : this.viernes,
                    'sabado' : this.sabado,
                    'horario' : this.horario,
                    'celular' : this.celular,
                    'obs_gen' : this.obs_gen

                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal();
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Solicitud registrada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                    me.proceso = false;
                });
            },

            cancelarSolicitud(id){
                let me = this;
                    Swal.fire({
                    title: 'Cancelar solicitud',
                    text: "La solicitud quedara cancelada, ¿Desea Continuar?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si!'
                    }).then((result) => {
                    if (result.value) {
                        //Con axios se llama el metodo update de LoteController
                        axios.put('/detalles/finalizarReporte',{
                            'solicitud_id': id,

                        }).then(function (response){
                            me.listarSolicitudes(me.pagination2.current_page,me.buscar2,me.b_etapa2,me.b_manzana2,me.b_lote2,me.criterio2,me.status);
                            //window.alert("Cambios guardados correctamente");
                            const toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                                });
                                toast({
                                type: 'success',
                                title: 'Solicitud anulada correctamente'
                            })
                        }).catch(function (error){
                            console.log(error);
                        });
                    }
                })

            },

            /// SELECT PARA CATALOGO DE DETALLES
                selectGenerales(fraccionamiento){
                    let me = this;

                    me.arrayGenerales=[];
                    var url = '/catalogoDetalle/selectGeneral';
                    axios.get(url).then(function (response) {
                        var respuesta = response.data;
                        me.arrayGenerales = respuesta.general;

                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                },
                selectSubconcepto(id_gral){
                    let me = this;

                    me.arraySubconceptos=[];
                    var url = '/catalogoDetalle/selectSub?id_gral=' + id_gral;
                    axios.get(url).then(function (response) {
                        var respuesta = response.data;
                        me.arraySubconceptos = respuesta.subconcepto;

                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                },
                selectDetalle(id_sub){
                    let me = this;

                    me.arrayDetalles=[];
                    var url = '/catalogoDetalle/selectDetalle?id_sub=' + id_sub;
                    axios.get(url).then(function (response) {
                        var respuesta = response.data;
                        me.arrayDetalles = respuesta.detalle;

                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                },

                getDatosDetalle(id_detalle){
                let me = this;
                me.arrayDatosDetalle=[];
                var url = '/catalogoDetalle/getDatosDetalle?id_detalle=' + id_detalle;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                        me.arrayDatosDetalle = respuesta.datosDetalle;
                        me.detalle = me.arrayDatosDetalle[0]['detalle'];
                        me.dias_garantia = me.arrayDatosDetalle[0]['dias_garantia'];
                        me.subconcepto = me.arrayDatosDetalle[0]['subconcepto'];
                        me.general = me.arrayDatosDetalle[0]['general'];
                        if(me.dias_garantia >= me.dias_entregado)
                            me.garantia = 1;
                        else{
                            me.garantia = 0;
                        }

                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                },

            cambiarPagina2(page){
                let me = this;
                //Actualiza la pagina actual
                me.pagination2.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarSolicitudes(page,me.buscar2,me.b_etapa2,me.b_manzana2,me.b_lote2,me.criterio2,me.status);
            },

            storeObservacion(){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.post('/postventa/registrarObservacion',{
                    'comentario' : this.observacion,
                    'entrega_id':this.folio,

                }).then(function (response){
                    me.listarObservacion(1,me.folio);
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Observación guardada correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            listarObservacion(page, buscar){
                let me = this;
                var url = '/postventa/indexObservacion?page=' + page + '&buscar=' + buscar ;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayObservacion = respuesta.observacion.data;
                    console.log(url);
                })
                .catch(function (error) {
                    console.log(error);
                });

            },

            validarFecha(){
                this.errorEntrega=0;
                this.errorMostrarMsjEntrega=[];

                if(!this.observacion) //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjEntrega.push("Debe ingresar una observación.");

                if(!this.fecha_entrega_obra) //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjEntrega.push("Ingresar fecha de entrega.");


                if(this.errorMostrarMsjEntrega.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorEntrega = 1;

                return this.errorEntrega;
            },
            cerrarModal(){
                this.tituloModal = '';
                this.modal = 0;
                this.modal2 = 0;
                this.modalArchivo = 0;
                this.observacion = '';
                this.arrayObservacion = [];
                this.id_gral = '';
                this.id_sub = '';
                this.id_detalle = '';
                this.detalle = '';
                this.direccion = '';
                this.contratista = '';
                this.dias_garantia = '';
                this.dias_entregado = '';
                this.etapa_entregada = '';
                this.manzana_entregada = '';
                this.lote_entregado = '';

                this.lunes = 0;
                this.martes = 0;
                this.miercoles = 0;
                this.jueves = 0;
                this.viernes = 0;
                this.sabado = 0;
                this.horario = '';
                this.arrayListadoDetalles = [];
                this.listarSolicitudes(this.pagination2.current_page,this.buscar2,this.b_etapa2,this.b_manzana2,this.b_lote2,this.criterio2,this.status);
            },

            abrirModal(accion,data =[]){
                switch(accion){

                    case 'nuevo':{
                        this.modal = 1;
                        this.tituloModal='Nueva solicitud de detalles';
                        this.folio = '';
                        this.observacion = '';
                        this.lote_id = '';
                        this.obs_gen = '';
                        break;
                    }

                    case 'revisar':{
                        this.modal2 =1;
                        this.tituloModal='Observaciones';
                        this.descripcion_id = data['id'];
                        this.solicitud_id = data['solicitud_id'];
                        this.observacion = '';
                        break;
                    }

                    case 'subir_archivo':{
                        this.solicitud_id = data['id'];
                        this.modalArchivo = 1;
                        this.tituloModal = 'Subir solicitud para solicitud: ' + this.solicitud_id;

                        break;
                    }

                    case 'agenda':{
                        this.proyecto = '';
                        this.etapa = '';
                        this.contratista = '';
                        this.fecha1 = '';
                        this.fecha2 = '';

                        this.modal = 2;
                        this.tituloModal = 'Descargar Agenda';
                        break;
                    }
                    case 'finalizar':{
                        this.fecha1 = '';
                        this.tituloModal = 'Finalizar solicitud';
                        this.solicitud_id = data['id'];
                        this.modal = 3;
                        break;
                    }
                }
            }

        },

        mounted() {
            this.listarSolicitudes(1,this.buscar2,this.b_etapa2,this.b_manzana2,this.b_lote2,this.criterio2,this.status);
            this.selectFraccionamientos();
            this.selectContratistas();
            this.selectGenerales();
        }
    }
</script>
<style>
    .line-separator{
        height:1px;
        background:#717171;
        border-bottom:1px solid #c2cfd6;
    }

    .form-control:disabled, .form-control[readonly] {
        background-color: rgba(0, 0, 0, 0.06);
        opacity: 1;
        font-size: 0.85rem;
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
