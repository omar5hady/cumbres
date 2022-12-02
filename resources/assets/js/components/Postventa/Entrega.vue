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
                        <i class="fa fa-align-justify"></i> Entregas de vivienda
                        &nbsp;&nbsp;<button type="submit" v-if="historial == 0" @click="historial = 1" class="btn btn-success"><i class="fa fa-check"></i> Historial de Entregas</button>
                        &nbsp;&nbsp;<button type="submit" v-if="historial == 1" @click="historial = 0" class="btn btn-default"><i class="fa fa-mail-reply-all"></i> Regresar</button>
                        <a v-if="historial == 1" :href="'/postventa/excelEntregas?buscar=' + buscar + '&b_etapa=' + b_etapa + '&b_manzana=' + b_manzana + '&b_lote=' + b_lote +  '&criterio=' + criterio"  class="btn btn-success"><i class="fa fa-file-text"></i> Excel</a>
                    </div>
                <!-------------------  Div contratos pendientes por entregar --------------------->
                    <div class="card-body" v-if="historial == 0">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-5" v-model="criterio2" @click="selectFraccionamientos()">
                                        <option value="lotes.fraccionamiento_id">Proyecto</option>
                                        <option value="c.nombre">Cliente</option>
                                        <option value="entregas.fecha_program">Fecha programada</option>
                                        <option value="contratos.id"># Folio</option>
                                    </select>

                                    <select class="form-control" v-if="criterio2=='lotes.fraccionamiento_id'" v-model="buscar2" @click="selectEtapa(buscar2)">
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamiento in arrayFraccionamientos2" :key="fraccionamiento.nombre" :value="fraccionamiento.id" v-text="fraccionamiento.nombre"></option>
                                    </select>

                                    <select class="form-control" v-if="criterio2=='lotes.fraccionamiento_id'" @click="selectManzanas(buscar2,b_etapa2)" v-model="b_etapa2">
                                        <option value="">Etapa</option>
                                        <option v-for="etapa in arrayEtapas2" :key="etapa.num_etapa" :value="etapa.id" v-text="etapa.num_etapa"></option>
                                    </select>

                                    <input v-if="criterio2 == 'entregas.fecha_program'" type="date"  v-model="buscar2" @keyup.enter="listarContratos(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)" class="form-control" placeholder="Texto a buscar">
                                    <input v-if="criterio2 == 'entregas.fecha_program'" type="date"  v-model="b_etapa2" @keyup.enter="listarContratos(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)" class="form-control" placeholder="Texto a buscar">

                                    <input v-if="criterio2 == 'c.nombre' || criterio2 == 'contratos.id'" type="text"  v-model="buscar2" @keyup.enter="listarContratos(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)" class="form-control" placeholder="Texto a buscar">

                                </div>
                            </div>
                            <div class="col-md-6" v-if="criterio2=='lotes.fraccionamiento_id'">
                                <div class="input-group">
                                    <select class="form-control" v-if="criterio2=='lotes.fraccionamiento_id'" @click="selectLotesManzana(buscar2,b_etapa2,b_manzana2)" @keyup.enter="listarContratos(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)" v-model="b_manzana2" >
                                        <option value="">Manzana</option>
                                        <option v-for="manzana in arrayAllManzanas" :key="manzana.manzana" :value="manzana.manzana" v-text="manzana.manzana"></option>
                                    </select>

                                    <select class="form-control" v-if="criterio2=='lotes.fraccionamiento_id'" @keyup.enter="listarContratos(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)" v-model="b_lote2" >
                                        <option value="">Lote</option>
                                        <option v-for="lotes in arrayAllLotes" :key="lotes.id" :value="lotes.num_lote" v-text="lotes.num_lote"></option>
                                    </select>
                                </div>
                            </div>


                        </div>
                        <div class="form-group row">
                            <div class="col-md-6" v-if="criterio2=='lotes.fraccionamiento_id'">
                                <div class="input-group">
                                    <input type="date"  v-model="b_desde" @keyup.enter="listarContratos(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)" class="form-control" placeholder="Texto a buscar">
                                    <input type="date"  v-model="b_hasta" @keyup.enter="listarContratos(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)" class="form-control" placeholder="Texto a buscar">
                                </div>
                            </div>


                            <div class="col-md-8">
                                <button type="submit" @click="listarContratos(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                <a :href="'/postventa/excel?buscar='+buscar2 + '&b_etapa='+b_etapa2 + '&b_manzana='+b_manzana2+'&b_lote='+b_lote2+
                                    '&criterio='+criterio2 +  '&b_desde='+b_desde +  '&b_hasta='+b_hasta"
                                    class="btn btn-success"><i class="fa fa-file-excel-o"></i> Excel
                                </a>
                            </div>
                        </div>

                        <TableComponent :cabecera="['','#','Proyecto','Etapa','Manzana','Lote','Cliente',
                                'Fecha entrega (Obra)','Paquete y/o Promocioón',
                                'Equipamiento','Firma de escrituras','Fecha de entrega','Finalizar Entrega',''
                        ]">
                            <template v-slot:tbody>
                                <tr v-for="contratos in arrayContratos" :key="contratos.id" v-bind:style="{ backgroundColor : contratos.fecha_firma_esc == null ? '#C26F6F' : '#FFFFFF'}">
                                        <td>
                                            <button v-if="contratos.planos.length > 0" @click="abrirModal('verPlanos',contratos.planos)" class="btn btn-scarlet">
                                                Planos
                                            </button>
                                        </td>
                                        <td class="td2">
                                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">{{contratos.folio}}</a>
                                            <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
                                                <a class="dropdown-item" @click="abrirPDF(contratos.folio)">Estado de cuenta</a>
                                                <a class="dropdown-item" target="_blank" v-bind:href="'/contratoCompraVenta/pdf/'+ contratos.folio">Contrato de compra venta</a>
                                                <a class="dropdown-item" target="_blank" v-bind:href="'/postventa/cartaRecepcion/'+ contratos.folio">Carta de recepción</a>
                                                <a class="dropdown-item" target="_blank" v-bind:href="'/postventa/cartaAlarma?id='+ contratos.folio">Carta de alarma</a>
                                                <a class="dropdown-item" target="_blank" v-bind:href="'/docs/entregaInterapas?id='+ contratos.folio">Acta de Entrega-Vivienda</a>
                                                <a class="dropdown-item" target="_blank" v-bind:href="'/contrato/printProcServicios'">Procedimiento de Agua-Luz</a>
                                                <a class="dropdown-item" target="_blank" v-bind:href="'/contrato/printProcGarantia'">Procedimiento de Garantia</a>
                                                <a v-if="contratos.fecha_program" class="dropdown-item" target="_blank"
                                                        v-bind:href="'/contrato/printGarantia/'+ contratos.folio">Poliza de garantia
                                                </a>
                                                <a class="dropdown-item" target="_blank" v-bind:href="'/postventa/polizaDeGarantia/'+ contratos.folio+'?tiempo=2'">Poliza de garantia 2 años</a>

                                                <!-- <a class="dropdown-item" target="_blank" v-bind:href="'/postventa/polizaDeGarantia/'+ contratos.folio+'?tiempo=5'">Poliza de garantia 5 años</a> -->
                                            </div>
                                        </td>
                                        <td class="td2">
                                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">{{contratos.proyecto}}</a>
                                            <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
                                                <a class="dropdown-item" v-bind:href="'/descargarReglamento/contrato/'+ contratos.folio">Reglamento de la etapa</a>
                                                <a class="dropdown-item" target="_blank" v-bind:href="'/serviciosTelecom/pdf/'+ contratos.folio">Servicios de telecomunición</a>
                                                <a class="dropdown-item" @click="selectNombreArchivoModelo(contratos.folio)">Catalogo de especificaciones</a>
                                                <a v-if="contratos.carta_bienvenida" class="dropdown-item" target="_blank"  v-bind:href="'/downloadCartaBienvenida/'+contratos.carta_bienvenida">Carta de bienvenida</a>
                                                <a v-if="contratos.foto_predial" class="dropdown-item" v-bind:href="'/downloadPredial/'+ contratos.foto_predial">Predial</a>
                                                <a v-if="contratos.factibilidad" class="dropdown-item" v-bind:href="'/downloadFactibilidad/'+ contratos.factibilidad" onclick="window.open('/pdf/INTERAPAS.pdf','_blank')">Factibilidad</a>
                                                <a v-if="contratos.num_licencia" class="dropdown-item"  v-text="'Licencia: '+contratos.num_licencia" v-bind:href="'/downloadLicencias/'+contratos.foto_lic"></a>
                                            </div>

                                        </td>
                                        <td class="td2" v-text="contratos.etapa"></td>
                                        <td class="td2" v-text="contratos.manzana"></td>
                                        <td class="td2" v-text="contratos.num_lote"></td>
                                        <td class="td2">
                                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">{{contratos.nombre_cliente}}</a>
                                            <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
                                                <a title="Llamar" target="_blank"  class="dropdown-item" :href="'tel:'+contratos.celular"><i class="fa fa-phone fa-lg" style="color:blue;"></i>Llamar</a>
                                                <a title="Enviar whatsapp" class="dropdown-item" target="_blank" :href="'https://api.whatsapp.com/send?phone=+52'+contratos.celular+'&text=Hola'"><i class="fa fa-whatsapp fa-lg" style="color:green;"></i>Whatsapp</a>
                                                <a title="Enviar correo" target="_blank"  class="dropdown-item" :href="'mailto:'+contratos.email"> <i class="fa fa-envelope-o fa-lg" style="color:orange;"></i>Email</a>
                                                <a class="dropdown-item" target="_blank" @click="abrirModal('ver_personal',contratos)">Mas información</a>
                                            </div>
                                        </td>
                                        <td class="td2">
                                            <span v-if="contratos.fecha_entrega_obra && contratos.diferencia_obra < 2" class="badge badge-success">
                                                {{this.moment(contratos.fecha_entrega_obra).locale('es').format('DD/MMM/YYYY')}}
                                            </span>
                                            <span v-else-if="contratos.fecha_entrega_obra && contratos.diferencia_obra > 2" class="badge badge-danger">
                                                {{this.moment(contratos.fecha_entrega_obra).locale('es').format('DD/MMM/YYYY')}}
                                            </span>
                                            <span v-else class="badge badge-info">
                                                Sin fecha asignada
                                            </span>

                                        </td>
                                        <td class="td2">
                                            <button title="Ver equipamiento" type="button" class="btn btn-primary pull-right"
                                                v-if="(contratos.paquete != '' || contratos.promocion != '') && (contratos.paquete != null || contratos.promocion != null)"
                                                @click="abrirModal('equipamiento', contratos)">Ver Equipamiento
                                            </button>
                                            <span v-else>Sin equipamiento</span>
                                        </td>
                                        <template>
                                            <td class="td2" v-if="contratos.descripcion_paquete && contratos.descripcion_promocion && contratos.equipamiento == 0">
                                                <button title="Programar fecha" type="button" class="btn btn-danger pull-right"
                                                    @click="ultimaFecha(contratos.folio)">
                                                    Equipamiento sin solicitarse
                                                </button>
                                            </td>
                                            <td class="td2" v-else-if="contratos.descripcion_paquete && !contratos.descripcion_promocion && contratos.equipamiento == 0">
                                                    <button title="Programar fecha" type="button" class="btn btn-danger pull-right"
                                                    @click="ultimaFecha(contratos.folio)">
                                                    Equipamiento sin solicitarse
                                                </button>
                                            </td>
                                            <td class="td2" v-else-if="!contratos.descripcion_paquete && contratos.descripcion_promocion && contratos.equipamiento == 0">
                                                    <button title="Programar fecha" type="button" class="btn btn-danger pull-right"
                                                    @click="ultimaFecha(contratos.folio)">
                                                    Equipamiento sin solicitarse
                                                </button>
                                            </td>

                                            <td class="td2" v-else-if="contratos.descripcion_paquete && contratos.descripcion_promocion && contratos.equipamiento == 1">
                                                <button title="Programar fecha" type="button" class="btn btn-warning pull-right"
                                                    @click="ultimaFecha(contratos.folio)">
                                                    En proceso de instalación
                                                </button>

                                            </td>
                                            <td class="td2" v-else-if="contratos.descripcion_paquete && !contratos.descripcion_promocion && contratos.equipamiento == 1" >
                                                <button title="Programar fecha" type="button" class="btn btn-warning pull-right"
                                                    @click="ultimaFecha(contratos.folio)">
                                                    En proceso de instalación
                                                </button>
                                            </td>
                                            <td class="td2" v-else-if="!contratos.descripcion_paquete && contratos.descripcion_promocion && contratos.equipamiento == 1">
                                                <button title="Programar fecha" type="button" class="btn btn-warning pull-right"
                                                    @click="ultimaFecha(contratos.folio)">
                                                    En proceso de instalación
                                                </button>
                                            </td>

                                            <td class="td2" v-else-if="contratos.descripcion_paquete && contratos.descripcion_promocion && contratos.equipamiento == 2">
                                                <button title="Programar fecha" type="button" class="btn btn-success pull-right"
                                                    @click="ultimaFecha(contratos.folio)">
                                                    Equipamiento instalado
                                                </button>

                                            </td>
                                            <td class="td2" v-else-if="contratos.descripcion_paquete && !contratos.descripcion_promocion && contratos.equipamiento == 2">
                                                <button title="Programar fecha" type="button" class="btn btn-success pull-right"
                                                    @click="ultimaFecha(contratos.folio)">
                                                    Equipamiento instalado
                                                </button>
                                            </td>
                                            <td class="td2" v-else-if="!contratos.descripcion_paquete && contratos.descripcion_promocion && contratos.equipamiento == 2">
                                                <button title="Programar fecha" type="button" class="btn btn-success pull-right"
                                                    @click="ultimaFecha(contratos.folio)">
                                                    Equipamiento instalado
                                                </button>
                                            </td>
                                            <td class="td2" v-else v-text="'Sin equipamiento'" ></td>
                                        </template>
                                        <td  class="td2">
                                            <span v-if="contratos.fecha_firma_esc != null">{{this.moment(contratos.fecha_firma_esc).locale('es').format('DD/MMM/YYYY')}}</span>
                                            <span v-else>Sin firma</span>
                                        </td>
                                        <td class="td2">
                                            <button title="Programar fecha" type="button" class="btn" :class="!contratos.fecha_program ? 'btn-default' :' btn-primary'"
                                                @click="abrirModal('programar_fecha', contratos)">
                                                {{ !contratos.fecha_program ? 'Programar fecha' : this.moment(contratos.fecha_program).locale('es').format('DD/MMM/YYYY') }}
                                                <i class="fa fa-calendar-plus-o fa-lg"></i>
                                            </button>
                                            <button title="Programar Hora" type="button" class="btn" :class="!contratos.hora_entrega_prog ? 'btn-default' :' btn-primary'"
                                                @click="abrirModal('programar_hora', contratos)">
                                                {{ !contratos.hora_entrega_prog ? 'Programar Hora' : contratos.hora_entrega_prog }}
                                                <i class="fa fa-clock-o fa-lg"></i>
                                            </button>
                                        </td>
                                        <td class="td2">
                                            <button v-if="contratos.fecha_program" title="Finalizar entrega" type="button" class="btn btn-dark pull-right"
                                                @click="abrirModal('finalizar', contratos)">
                                                Finalizar&nbsp;&nbsp;<i class="fa fa-trophy fa-lg"></i>
                                            </button>
                                            <span class="badge badge-warning" v-else>No se ha programado una fecha</span>
                                        </td>
                                        <td>
                                            <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right"
                                                @click="abrirModal('observaciones', contratos),listarObservacion(1,contratos.folio)">Ver observaciones
                                            </button>
                                        </td>
                                </tr>
                            </template>
                        </TableComponent>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination2.last_page > 7 && pagination2.current_page > 7">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)">Inicio</a>
                                </li>
                                <li class="page-item" v-if="pagination2.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page - 1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber2" :key="page" :class="[page == isActived2 ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(page,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination2.current_page < pagination2.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page + 1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)">Sig</a>
                                </li>
                                <li class="page-item" v-if="pagination2.last_page > 7 && pagination2.current_page<pagination2.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.last_page,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)">Ultimo</a>
                                </li>
                            </ul>
                        </nav>
                        <button class="btn btn-sm btn-default" data-toggle="modal" data-target="#manualId">Manual</button>
                    </div>
                <!-------------------  Fin Div para Contratos que tienen paquete o promoción  --------------------->

                <!-------------------  Div historial de entregas --------------------->
                    <div class="card-body" v-if="historial == 1">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-5" v-model="criterio" @click="selectFraccionamientos()">
                                        <option value="lotes.fraccionamiento_id">Proyecto</option>
                                        <option value="c.nombre">Cliente</option>
                                        <option value="entregas.fecha_entrega_real">Fecha entrega</option>
                                        <option value="contratos.id"># Folio</option>
                                    </select>

                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar" @click="selectEtapa(buscar)">
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamiento in arrayFraccionamientos2" :key="fraccionamiento.nombre" :value="fraccionamiento.id" v-text="fraccionamiento.nombre"></option>
                                    </select>

                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" @click="selectManzanas(buscar,b_etapa)" v-model="b_etapa">
                                        <option value="">Etapa</option>
                                        <option v-for="etapa in arrayEtapas2" :key="etapa.num_etapa" :value="etapa.id" v-text="etapa.num_etapa"></option>
                                    </select>

                                    <input v-if="criterio == 'entregas.fecha_entrega_real'" type="date"  v-model="buscar" @keyup.enter="listarEntregas(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <input v-if="criterio == 'entregas.fecha_entrega_real'" type="date"  v-model="b_etapa" @keyup.enter="listarEntregas(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="form-control" placeholder="Texto a buscar">

                                    <input v-if="criterio == 'c.nombre' || criterio == 'contratos.id'" type="text"  v-model="buscar" @keyup.enter="listarEntregas(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="form-control" placeholder="Texto a buscar">

                                </div>
                            </div>
                            <div class="col-md-6" v-if="criterio=='lotes.fraccionamiento_id'">
                                <div class="input-group">
                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" @click="selectLotesManzana(buscar,b_etapa,b_manzana)" @keyup.enter="listarEntregas(1,buscar,b_etapa,b_manzana,b_lote,criterio)" v-model="b_manzana" >
                                        <option value="">Manzana</option>
                                        <option v-for="manzana in arrayAllManzanas" :key="manzana.manzana" :value="manzana.manzana" v-text="manzana.manzana"></option>
                                    </select>

                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" @keyup.enter="listarEntregas(1,buscar,b_etapa,b_manzana,b_lote,criterio)" v-model="b_lote" >
                                        <option value="">Lote</option>
                                        <option v-for="lotes in arrayAllLotes" :key="lotes.id" :value="lotes.num_lote" v-text="lotes.num_lote"></option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <button type="button" @click="listarEntregas(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>&nbsp;&nbsp;
                                    <strong><label class="text-muted" v-text="'Total: '+total_entregas"></label></strong>
                                </div>
                            </div>
                        </div>

                        <TableComponent :cabecera="[
                            '','# Ref','Proyecto','Etapa','Manzana','Lote','Cliente','Fecha de firma',
                            'Fecha entrega (Obra)','Paquete y/o Promocioón','Fecha de Entrega',
                            '# Reprogramaciones','Observaciones',
                        ]">
                            <template v-slot:tbody>
                                <tr v-for="entregas in arrayEntregas" :key="entregas.id">
                                    <td>
                                        <button v-if="entregas.planos.length > 0" @click="abrirModal('verPlanos',entregas.planos)" class="btn btn-scarlet">
                                            Planos
                                        </button>
                                    </td>
                                    <td class="td2">
                                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">{{entregas.folio}}</a>
                                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
                                            <a class="dropdown-item" target="_blank" v-bind:href="'/contratoCompraVenta/pdf/'+ entregas.folio">Contrato de compra venta</a>
                                            <a class="dropdown-item" target="_blank" v-bind:href="'/postventa/cartaAlarma?id='+ entregas.folio">Carta de alarma</a>
                                            <a class="dropdown-item" @click="abrirModal('garantia',entregas)">Poliza de Garantia</a>
                                            <a class="dropdown-item" @click="abrirModal('acta_entrega',entregas)">Acta de entrega</a>
                                        </div>
                                    </td>
                                    <td class="td2">
                                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">{{entregas.proyecto}}</a>
                                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
                                            <a class="dropdown-item" target="_blank" v-bind:href="'/serviciosTelecom/pdf/'+ entregas.folio">Servicios de telecomunición</a>
                                            <a class="dropdown-item" @click="selectNombreArchivoModelo(entregas.folio)">Catalogo de especificaciones</a>
                                            <a v-if="entregas.foto_predial" class="dropdown-item" v-bind:href="'/downloadPredial/'+ entregas.foto_predial">Predial</a>
                                            <a v-if="entregas.factibilidad" class="dropdown-item" v-bind:href="'/downloadFactibilidad/'+ entregas.factibilidad" onclick="window.open('/pdf/INTERAPAS.pdf','_blank')">Factibilidad</a>
                                        </div>

                                    </td>
                                    <td class="td2" v-text="entregas.etapa"></td>
                                    <td class="td2" v-text="entregas.manzana"></td>
                                    <td class="td2" v-text="entregas.num_lote"></td>
                                    <td class="td2">
                                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">{{entregas.nombre_cliente}}</a>
                                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
                                            <a title="Llamar" target="_blank"  class="dropdown-item" :href="'tel:'+entregas.celular"><i class="fa fa-phone fa-lg" style="color:blue;"></i>Llamar</a>
                                            <a title="Enviar whatsapp" class="dropdown-item" target="_blank" :href="'https://api.whatsapp.com/send?phone=+52'+entregas.celular+'&text=Hola'"><i class="fa fa-whatsapp fa-lg" style="color:green;"></i>Whatsapp</a>
                                            <a title="Enviar correo" target="_blank"  class="dropdown-item" :href="'mailto:'+entregas.email"> <i class="fa fa-envelope-o fa-lg" style="color:orange;"></i>Email</a>
                                            <a class="dropdown-item" target="_blank" @click="abrirModal('ver_personal',entregas)">Mas información</a>
                                        </div>
                                    </td>
                                    <td class="td2" v-text="this.moment(entregas.fecha_firma_esc).locale('es').format('DD/MMM/YYYY')"></td>
                                    <td class="td2" >
                                        <span v-if="entregas.fecha_entrega_obra && entregas.diferencia_obra < 2"
                                            v-text="this.moment(entregas.fecha_entrega_obra).locale('es').format('DD/MMM/YYYY')" class="badge badge-success"></span>
                                        <span v-else
                                            v-text="this.moment(entregas.fecha_entrega_obra).locale('es').format('DD/MMM/YYYY')" class="badge badge-danger"></span>
                                    </td>
                                    <template>
                                        <td class="td2" >
                                            <button v-if="entregas.descripcion_paquete || entregas.descripcion_promocion"
                                                title="Ver equipamiento" type="button" class="btn btn-primary pull-right"
                                                @click="abrirModal('equipamiento', entregas)">Ver Equipamiento
                                            </button>
                                            <span v-else>
                                                Sin equipamiento
                                            </span>
                                        </td>
                                    </template>
                                    <td class="td2">
                                        <span v-text="this.moment(entregas.fecha_entrega_real).locale('es').format('DD/MMM/YYYY')" class="badge badge-success"></span>
                                    </td>
                                    <td class="td2" v-text="entregas.cont_reprogram"></td>
                                    <td>
                                        <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right"
                                            @click="abrirModal('observaciones', entregas),listarObservacion(1,entregas.folio)">Ver observaciones
                                        </button>
                                    </td>
                                </tr>
                            </template>

                        </TableComponent>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page > 7">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(1,buscar,b_etapa,b_manzana,b_lote,criterio)">Inicio</a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,b_etapa,b_manzana,b_lote,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,b_etapa,b_manzana,b_lote,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,b_etapa,b_manzana,b_lote,criterio)">Sig</a>
                                </li>
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page<pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.last_page,buscar,b_etapa,b_manzana,b_lote,criterio)">Ultimo</a>
                                </li>
                            </ul>
                        </nav>
                        <button class="btn btn-sm btn-default" data-toggle="modal" data-target="#manualId">Manual</button>
                    </div>
                <!-------------------  Fin Div para Contratos que tienen paquete o promoción  --------------------->
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>

            <!--Inicio del modal para diversos llenados de tabla en historial -->
            <ModalComponent v-if="modal == 2"
                @closeModal="cerrarModal()"
                :titulo="tituloModal"
            >
                <template v-slot:body>
                    <div class="form-group row" v-if="tipoAccion == 1">
                        <label class="col-md-3 form-control-label" for="text-input">Fecha de entrega programada</label>
                        <div class="col-md-3">
                            <input v-model="fecha_program" type="date" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row" v-if="tipoAccion == 1 && reprogramar == 1">
                        <label class="col-md-3 form-control-label" for="text-input">Motivo reprogramación</label>
                        <div class="col-md-6">
                            <select class="form-control col-md-6" v-model="mot_reprogra">
                                <option value="">Seleccione</option>
                                <option value="Cliente">Cliente</option>
                                <option value="Contratista">Contratista</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row" v-if="tipoAccion == 1">
                        <label class="col-md-3 form-control-label" for="text-input">Observaciones</label>
                        <div class="col-md-8">
                            <input v-model="observacion" type="text" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row" v-if="tipoAccion == 2">
                        <label class="col-md-3 form-control-label" for="text-input">Hora de entrega programada</label>
                        <div class="col-md-3">
                            <input type="time" v-model="hora_entrega_prog" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row" v-if="tipoAccion == 3">
                        <label class="col-md-3 form-control-label" for="text-input">Fecha de entrega</label>
                        <div class="col-md-3">
                            <input v-model="fecha_entrega_real" type="date" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row" v-if="tipoAccion == 3">
                        <label class="col-md-3 form-control-label" for="text-input">Hora de entrega</label>
                        <div class="col-md-3">
                            <input type="time" v-model="hora_entrega_real" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row" v-if="tipoAccion == 3">
                        <label class="col-md-3 form-control-label" for="text-input">Cero detalles</label>
                        <div class="col-md-4">
                            <select class="form-control col-md-4" v-model="cero_detalles">
                                <option value="1">Si</option>
                                <option value="2">No</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row" v-if="tipoAccion == 3">
                        <label class="col-md-3 form-control-label" for="text-input">Comentarios</label>
                        <div class="col-md-8">
                            <input type="text" v-model="observacion" class="form-control">
                        </div>
                    </div>

                    <!-- Div para mostrar los errores que mande validerDepartamento -->
                    <div v-show="errorEntrega" class="form-group row div-error">
                        <div class="text-center text-error">
                            <div v-for="error in errorMostrarMsjEntrega" :key="error" v-text="error">
                            </div>
                        </div>
                    </div>
                </template>
                <template v-slot:buttons-footer>
                    <button type="button" v-if="tipoAccion == 1" class="btn btn-success" @click="progFecha()">Programar fecha</button>
                    <button type="button" v-if="tipoAccion == 2" class="btn btn-success" @click="progHora()">Programar hora</button>
                    <button type="button" v-if="tipoAccion == 3 && fecha_entrega_real != ''
                                && hora_entrega_real != '' && fecha_entrega_real != null && hora_entrega_real != null"
                        class="btn btn-success" @click="finalizarEntrega()">Finalizar
                    </button>
                </template>
            </ModalComponent>
            <!--Fin del modal-->

            <!--Inicio del modal observaciones-->
            <ModalComponent v-if="modal == 3"
                :titulo="tituloModal"
                @closeModal="cerrarModal()"
            >
                <template v-slot:body>
                    <template v-if="tipoAccion == 1">
                        <div class="modal-body">
                            <div class="form-group row">
                                <label class="col-md-2 form-control-label" for="text-input">Nueva observación</label>
                                <div class="col-md-6">
                                    <input type="text" v-model="observacion" class="form-control">
                                </div>

                                <div class="col-md-3">
                                    <button class="btn btn-primary" @click="storeObservacion()">Guardar</button>
                                </div>
                            </div>

                            <TableComponent :cabecera="['Usuario','Observacion','Fecha']">
                                <template v-slot:tbody>
                                    <tr v-for="observacion in arrayObservacion" :key="observacion.id">
                                        <td v-text="observacion.usuario" ></td>
                                        <td v-text="observacion.comentario" ></td>
                                        <td v-text="observacion.created_at"></td>
                                    </tr>
                                </template>
                            </TableComponent>
                        </div>
                    </template>
                    <template v-if="tipoAccion == 2">
                        <div class="modal-body">
                            <div class="form-group row">
                                <label class="col-md-2 form-control-label" for="text-input">Promoción</label>
                                <div class="col-md-10">
                                    <textarea disabled rows="5" cols="30" v-model="promocion" class="form-control" placeholder="Promoción"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 form-control-label" for="text-input">Paquete</label>
                                <div class="col-md-10">
                                    <textarea disabled rows="5" cols="30" v-model="paquete" class="form-control" placeholder="Paquete"></textarea>
                                </div>
                            </div>
                        </div>
                    </template>
                </template>
            </ModalComponent>
            <!--Fin del modal-->

            <!--Inicio del modal para mostrar los datos del cliente -->
            <ModalComponent v-if="modal==1"
                @closeModal="cerrarModal()"
                :titulo="tituloModal"
            >
                <template v-slot:body>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                        <div class="col-md-9">
                            <input type="text" disabled v-model="datosCliente.nombre_cliente" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Telefono</label>
                        <div class="col-md-3">
                            <input type="text" disabled v-model="datosCliente.telefono" class="form-control">
                        </div>
                        <label class="col-md-2 form-control-label" for="text-input">Celular</label>
                        <div class="col-md-4">
                            <input type="text" v-model="datosCliente.celular" disabled class="form-control">
                            <a title="Llamar" class="btn btn-dark" :href="'tel:'+datosCliente.celular"><i class="fa fa-phone fa-lg"></i></a>
                        <a title="Enviar whatsapp" class="btn btn-success" target="_blank" :href="'https://api.whatsapp.com/send?phone=+52'+datosCliente.celular+'&text=Hola'"><i class="fa fa-whatsapp fa-lg"></i></a>
                        </div>

                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Email</label>
                        <div class="col-md-6">
                            <input type="text" v-model="datosCliente.email" disabled class="form-control">
                        </div>
                        <a title="Enviar correo" class="btn btn-secondary" :href="'mailto:'+ datosCliente.email"> <i class="fa fa-envelope-o fa-lg"></i> </a>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">CURP</label>
                        <div class="col-md-3">
                            <input type="text" v-model="datosCliente.curp" disabled class="form-control">
                        </div>
                        <label class="col-md-2 form-control-label" for="text-input">NSS</label>
                        <div class="col-md-3">
                            <input type="text" v-model="datosCliente.nss" disabled class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">RFC</label>
                        <div class="col-md-3">
                            <input type="text" v-model="datosCliente.rfc" disabled class="form-control">
                        </div>
                        <label class="col-md-3 form-control-label" for="text-input">Homoclave</label>
                        <div class="col-md-2">
                            <input type="text" v-model="datosCliente.homoclave" disabled class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Fecha de nacimiento</label>
                        <div class="col-md-6">
                            <input type="text" v-model="datosCliente.f_nacimiento" disabled class="form-control">
                        </div>
                    </div>

                    <hr>
                    <h3 style="text-align:center;">LUGAR DE TRABAJO</h3>

                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Empresa</label>
                        <div class="col-md-6">
                            <input type="text" v-model="datosCliente.empresa" disabled class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Giro del negocio</label>
                        <div class="col-md-6">
                            <input type="text" v-model="datosCliente.puesto" disabled class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Domicilio Empresa</label>
                        <div class="col-md-4">
                            <input type="text" v-model="datosCliente.direccion_empresa" disabled class="form-control">
                        </div>
                         <label class="col-md-2 form-control-label" for="text-input">C.P</label>
                        <div class="col-md-3">
                            <input type="text" v-model="datosCliente.cp_empresa" disabled class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Colonia</label>
                        <div class="col-md-6">
                            <input type="text" v-model="datosCliente.colonia_empresa" disabled class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Estado</label>
                        <div class="col-md-3">
                            <input type="text" v-model="datosCliente.estado_empresa" disabled class="form-control">
                        </div>
                        <label class="col-md-2 form-control-label" for="text-input">Ciudad</label>
                        <div class="col-md-3">
                            <input type="text" v-model="datosCliente.ciudad_empresa" disabled class="form-control">
                        </div>
                    </div>

                    <div class="form-group row" v-if="datosCliente.email_institucional">
                        <label class="col-md-3 form-control-label" for="text-input">Email institucional</label>
                        <div class="col-md-6">
                            <input type="text" v-model="datosCliente.email_institucional" disabled class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Telefono de la empresa</label>
                        <div class="col-md-4">
                            <input type="text" v-model="datosCliente.telefono_empresa" disabled class="form-control">
                        </div>
                        <label class="col-md-2 form-control-label" for="text-input">EXT</label>
                        <div class="col-md-3">
                            <input type="text" v-model="datosCliente.ext_empresa" disabled class="form-control">
                        </div>
                    </div>
                </template>
            </ModalComponent>
            <!--Fin del modal-->

            <!-- Manual -->
            <ModalComponent v-if="modal==4"
                :titulo="'Manual'"
                @closeModal="cerrarModal()"
            >
                <template v-slot:body>
                    <p>
                        Dentro del modulo de entregas de vivienda podrá agendar la fecha y la hora en la que se
                        realizará la entrega de la vivienda.
                    </p>
                    <p>
                        Reprogramaciones, en caso de necesitar una re reprogramación puede cambiar la fecha y
                        la hora dando doble clic sobre la fecha y la hora, solo debe indicar la causa
                        (si es a causa del cliente o es a casusa de un contratista) deberá agregar una observación y guardar.
                    </p>
                    <p>
                        Podrá ver el estatus del equipamiento y en caso de que lo desee puede ver la
                        ultima fecha del proceso de instalación dando clic sobre el botón de la columna de
                        “Equipamiento”.
                    </p>
                    <p>
                        En caso de que alguna vivienda o registro (renglón) se muestre con un color de
                        fondo color rojo indicara que ese registro (lote) no cuenta aún con la firma de escritura.
                    </p>
                    <p>
                        Una vez concretada la venta podrá dar clic sobre el botón de “Finalizar”,
                        vera una ventana donde debe de indicar la fecha, hora, si existen detalles
                        por arreglar y un comentario para finalizar.
                    </p>
                </template>
            </ModalComponent>
            <!-- -->

            <!-- Inicio Modal Archivo Fiscal-->
            <ModalComponent v-if="modal==5"
                :titulo="tituloModal"
                @closeModal="cerrarModal()"
            >
                <template v-slot:body>
                    <template v-if="tipoAccion == 1">
                        <div class="form-group row">
                            <input type="file"
                                v-show="false"
                                ref="archivoSelector"
                                @change="onSelectedArchivo"
                                accept="image/png, image/jpeg, image/gif, application/pdf"
                            >
                            <div class="col-md-9" v-if="!archivo">
                                <button
                                    @click="onSelectArchivo"
                                    class="btn btn-scarlet">
                                    Seleccionar Poliza de Garantia
                                    <i class="fa fa-upload"></i>
                                </button>

                            </div>

                            <div class="col-md-7" v-else>
                                <h6 style="color:#1e1d40;">Archivo seleccionado: {{archivo.name}}</h6>
                                <button
                                    @click="onSelectArchivo"
                                    class="btn btn-info">
                                    Cambiar Archivo
                                    <i class="fa fa-upload"></i>
                                </button>
                            </div>
                            <div class="col-md-3" v-if="archivo">
                                <button
                                    @click="saveArchivo"
                                    class="btn btn-scarlet">
                                    Guardar Poliza
                                    <i class="icon-check"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                    <template v-if="tipoAccion == 2">
                        <div class="form-group row">
                            <input type="file"
                                v-show="false"
                                ref="archivoSelector"
                                @change="onSelectedArchivo"
                                accept="image/png, image/jpeg, image/gif, application/pdf"
                            >
                            <div class="col-md-9" v-if="!archivo">
                                <button
                                    @click="onSelectArchivo"
                                    class="btn btn-scarlet">
                                    Seleccionar Acta de Entrega
                                    <i class="fa fa-upload"></i>
                                </button>

                            </div>

                            <div class="col-md-7" v-else>
                                <h6 style="color:#1e1d40;">Archivo seleccionado: {{archivo.name}}</h6>
                                <button
                                    @click="onSelectArchivo"
                                    class="btn btn-info">
                                    Cambiar Archivo
                                    <i class="fa fa-upload"></i>
                                </button>
                            </div>
                            <div class="col-md-3" v-if="archivo">
                                <button
                                    @click="formSubmitFileEntrega"
                                    class="btn btn-scarlet">
                                    Guardar Acta de Entega
                                    <i class="icon-check"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                </template>
                <template v-slot:buttons-footer>
                    <a v-if="polizaGarantia != null && tipoAccion == 1" class="btn btn-primary btn-sm" target="_blank" v-bind:href="'/entregas/downloadPoliza/'+polizaGarantia">Descargar poliza</a>
                    <a v-if="entrega_file != null && tipoAccion == 2" class="btn btn-primary btn-sm" target="_blank" :href="entrega_file">Ver Acta de Entrega</a>
                </template>
            </ModalComponent>
            <!--Fin del modal-->

            <ModalComponent v-if="modal == 6"
                    :titulo="tituloModal"
                    @closeModal="cerrarModal"
                >
                    <template v-slot:body>
                        <div class="col-md-12">
                            <TableComponent :cabecera="['Tipo','Descripcion','']">
                                <template v-slot:tbody>
                                    <tr v-for="p in planos" :key="p.id">
                                        <td class="td2" v-text="p.tipo"></td>
                                        <td class="td2" v-text="p.description"></td>
                                        <td class="td2">
                                            <a :href="p.file.public_url" target="_blank" class="btn btn-success">Ver Plano</a>
                                        </td>
                                    </tr>
                                </template>
                            </TableComponent>
                        </div>
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
        data(){
            return{
                planos:[],
                observacion:'',
                arrayObservacion : [],

                historial : 0,

                arrayFraccionamientos2:[],
                arrayEtapas2:[],
                arrayAllManzanas:[],
                arrayAllLotes:[],
                arrayContratos:[],
                arrayUltimaFecha: [],

                arrayEntregas:[],
                // Criterios para historial de contratos
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                criterio : 'entregas.fecha_program',
                buscar : '',
                b_etapa: '',
                b_manzana: '',
                b_lote: '',
                archivo:'',

                modal:0,
                tituloModal: '',
                tipoAccion:0,

                //variables
                lote_id: 0,
                folio:0,
                contrato_id: 0,
                offset : 2,
                nombre_archivo_modelo:'',
                fecha_program:'',
                hora_entrega_prog:'',
                fecha_entrega_real:'',
                hora_entrega_real:'',
                cero_detalles : 1,

                reprogramar : 0,


                //Datos clientes
                datosCliente:{

                },

                mot_reprogra : '',
                promocion :'',
                paquete:'',
                polizaGarantia:'',
                entrega_file : '',

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

                criterio : 'entregas.fecha_entrega_real',
                buscar : '',
                b_etapa: '',
                b_manzana: '',
                b_lote: '',
                fecha: '',
                b_desde : '',
                b_hasta : '',

                tipoAccion:0,
                observacion:'',

                errorEntrega : 0,
                errorMostrarMsjEntrega : [],
                total_entregas : 0
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
            onSelectArchivo(){
                this.$refs.archivoSelector.click()
            },
            onSelectedArchivo( event ){
                this.archivo = {}
                this.archivo = event.target.files[0]
            },
            saveArchivo(){
                let formData = new FormData();

                formData.append('archivo', this.archivo);
                formData.append('id', this.id);
                let me = this;
                axios.post('/entregas/formSubmitPoliza/'+me.contrato_id, formData)
                .then(function (response) {

                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Archivo guardado correctamente',
                        showConfirmButton: false,
                        timer: 2000
                        })
                    me.polizaGarantia = me.archivo.name
                    me.archivo = undefined;
                    me.listarContratos(me.pagination.current_page,me.buscar2,me.buscar3,me.b_etapa2,me.b_manzana2,me.b_lote2,me.criterio2);

                }).catch(function (error) {
                    console.log(error);
                });
            },
            formSubmitFileEntrega(){
                let formData = new FormData();

                formData.append('file', this.archivo);
                formData.append('id', this.contrato_id);
                let me = this;
                axios.post('/entregas/formSubmitFileEntrega', formData)
                .then(function (response) {
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Archivo guardado correctamente',
                        showConfirmButton: false,
                        timer: 2000
                        })
                    me.archivo = undefined;
                    me.cerrarModal();
                    me.listarContratos(me.pagination.current_page,me.buscar2,me.buscar3,me.b_etapa2,me.b_manzana2,me.b_lote2,me.criterio2);

                }).catch(function (error) {
                    console.log(error);
                });
            },
            listarContratos(page, buscar, b_etapa, b_manzana, b_lote, criterio){
                let me = this;
                var url = '/postventa/index?page=' + page + '&buscar=' + buscar + '&b_etapa=' + b_etapa + '&b_manzana=' + b_manzana + '&b_lote=' + b_lote +  '&criterio=' + criterio +  '&b_desde=' + this.b_desde +  '&b_hasta=' + this.b_hasta ;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayContratos = respuesta.contratos.data;
                    me.pagination2 = respuesta.pagination;
                    me.hora_entrega_real = respuesta.hora;
                    me.fecha_entrega_real = respuesta.hoy;

                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listarEntregas(page, buscar, b_etapa, b_manzana, b_lote, criterio){
                let me = this;
                var url = '/postventa/indexEntregas?page=' + page + '&buscar=' + buscar + '&b_etapa=' + b_etapa + '&b_manzana=' + b_manzana + '&b_lote=' + b_lote +  '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEntregas = respuesta.contratos.data;
                    me.pagination = respuesta.pagination;
                    me.total_entregas = respuesta.contratos.total;

                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            abrirPDF(id){
                const win = window.open('/estadoCuenta/estadoPDF/'+id, '_blank');
                win.focus();
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
            selectFraccionamientos(){
                let me = this;
                me.buscar=""

                me.arrayFraccionamientos=[];
                me.arrayFraccionamientos2=[];
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
            //Select todos los lotes
            selectLotesManzana(buscar1, buscar2, buscar3){
                let me = this;
                me.b_lote2="";

                me.arrayAllLotes=[];
                var url = '/select_lotes_manzana?buscar=' + buscar1 + '&buscar1='+ buscar2 + '&buscar2='+ buscar3;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayAllLotes = respuesta.lote_manzana;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina2(page,buscar,b_etapa,b_manzana,b_lote,criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination2.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarContratos(page,buscar,b_etapa,b_manzana,b_lote,criterio);
            },
            cambiarPagina(page,buscar,b_etapa,b_manzana,b_lote,criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarEntregas(page,buscar,b_etapa,b_manzana,b_lote,criterio);
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
            progFecha(){
                if(this.validarFecha()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/postventa/setFechaProg',{
                    'fecha_program' : this.fecha_program,
                    'folio' : this.folio,
                    'observacion' : this.observacion,
                    'mot_program' : this.mot_reprogra,

                }).then(function (response){
                    me.cerrarModal();
                    me.listarContratos(1,me.buscar2,me.b_etapa2,me.b_manzana2,me.b_lote2,me.criterio2);
                    //window.alert("Cambios guardados correctamente");
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'fecha agregada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            progHora(){
                if(this.validarHora()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/postventa/setHoraProg',{
                    'hora_entrega_prog' : this.hora_entrega_prog,
                    'folio' : this.folio,

                }).then(function (response){
                    me.cerrarModal();
                    me.listarContratos(1,me.buscar2,me.b_etapa2,me.b_manzana2,me.b_lote2,me.criterio2);
                    //window.alert("Cambios guardados correctamente");
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'hora agregada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            ultimaFecha(id){

                let me = this;
                me.arrayUltimaFecha = [];
                var url = '/select_ultima_fecha_instalacion?id=' + id;

                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayUltimaFecha = respuesta.fecha_ultima;
                        if(me.arrayUltimaFecha[0].fin_instalacion == null){
                            if(me.arrayUltimaFecha[0].fecha_colocacion != null){
                                me.fecha ='Programada la instalación para el dia: ' + me.arrayUltimaFecha[0].fecha_colocacion;
                            }
                            else
                                me.fecha = "Sin fecha de instalación";
                        }
                        else
                            me.fecha ='Instalado el dia: ' + me.arrayUltimaFecha[0].fin_instalacion;

                    Swal({
                    title: me.fecha,
                    animation: false,
                    customClass: 'animated fadeInUp'
                    })
                    console.log(url);
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            finalizarEntrega(){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/postventa/finalizarEntrega',{
                    'hora_entrega_real' : this.hora_entrega_real,
                    'fecha_entrega_real' : this.fecha_entrega_real,
                    'comentario' : this.observacion,
                    'id' : this.folio,
                    'cero_detalles' : this.cero_detalles,

                }).then(function (response){
                    me.cerrarModal();
                    me.listarContratos(1,me.buscar2,me.b_etapa2,me.b_manzana2,me.b_lote2,me.criterio2);
                    //window.alert("Cambios guardados correctamente");
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Entrega finalizada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            validarFecha(){
                this.errorEntrega=0;
                this.errorMostrarMsjEntrega=[];

                if(!this.fecha_program) //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjEntrega.push("Ingresar fecha de entrega.");


                if(this.errorMostrarMsjEntrega.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorEntrega = 1;

                return this.errorEntrega;
            },
            validarHora(){
                this.errorEntrega=0;
                this.errorMostrarMsjEntrega=[];

                if(!this.hora_entrega_prog) //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjEntrega.push("Ingresar hora de entrega.");


                if(this.errorMostrarMsjEntrega.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorEntrega = 1;

                return this.errorEntrega;
            },
            cerrarModal(){
                this.tituloModal = '';
                this.modal= 0;
                this.observacion = '';
                this.arrayObservacion = [];
                this.nombre_cliente="";
                this.sexo_cliente="";
                this.datosCliente = {};
                this.errorEntrega = 0;
                this.errorMostrarMsjEntrega = [];
                this.planos = [];
            },
            abrirModal(accion,data =[]){
                switch(accion){

                    case 'observaciones':{
                        this.modal = 3;
                        this.tituloModal='Observaciones';
                        this.folio = data['folio'];
                        this.observacion = '';
                        this.tipoAccion = 1;
                        break;
                    }

                    case 'verPlanos':{
                        this.planos = data;
                        this.modal = 6;
                        this.tituloModal = 'Planos del lote';
                        break;
                    }

                    case 'garantia':{
                        this.modal = 5;
                        this.tipoAccion = 1;
                        this.tituloModal = 'Subir Poliza de Garantia';
                        this.contrato_id = data['folio'];
                        this.polizaGarantia = data['garantia_file'];
                        break;
                    }

                    case 'acta_entrega':{
                        this.modal = 5
                        this.tipoAccion = 2;
                        this.tituloModal = 'Subir Acta de Entrega';
                        this.contrato_id = data['folio'];
                        this.entrega_file = data['entrega_file'];
                        break;
                    }

                    case 'equipamiento':{
                        this.modal = 3;
                        this.tituloModal = 'Equipamiento en la casa';
                        this.promocion = data['descripcion_promocion'];
                        this.paquete = data['descripcion_paquete'];
                        this.tipoAccion = 2;
                        break;
                    }

                    case 'ver_personal':
                    {
                        this.modal =1;
                        this.tituloModal='Datos del prospecto';
                        this.tipoAccion=3;
                        this.datosCliente = data;

                        switch(data['edo_civil']){
                            case 1: {
                                this.datosCliente.edocivil_cliente = 'Casado - separacion de bienes';
                                break;
                            }
                            case 2:{
                                this.datosCliente.edocivil_cliente = 'Casado - sociedad conyugal';
                                break;
                            }
                            case 3:{
                                this.datosCliente.edocivil_cliente = 'Divorciado';
                                break;
                            }
                            case 4:{
                                this.datosCliente.edocivil_cliente = 'Soltero';
                                break;
                            }
                            case 5:{
                                this.datosCliente.edocivil_cliente = 'Union libre';
                                break;
                            }
                            case 6:{
                                this.datosCliente.edocivil_cliente = 'Viudo';
                                break;
                            }
                            default:{
                                this.datosCliente.edocivil_cliente = 'Otro';
                                break;
                            }
                        }
                        break;
                    }

                    case 'programar_fecha':{
                        this.folio = data['folio'];
                        this.modal = 2;
                        this.tituloModal = "Programar Fecha";
                        this.fecha_program = data['fecha_program'];
                        if(data['fecha_program'] != null){
                            this.reprogramar = 1;
                        }
                        this.mot_reprogra = '',
                        this.tipoAccion = 1;
                        break;
                    }

                    case 'programar_hora':{
                        this.folio = data['folio'];
                        this.modal = 2;
                        this.tituloModal = "Programar Hora";
                        this.hora_entrega_prog = data['hora_entrega_prog'];
                        this.tipoAccion = 2;
                        break;
                    }

                    case 'finalizar':{
                        if(data['revision_previa'] == 0){
                            Swal.fire({
                            title: 'Sin revisión previa',
                            text: "No se ha realizado la revision previa, ¿Desea Continuar?",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Si!'
                            }).then((result) => {
                            if (result.value) {
                                this.folio = data['folio'];
                                this.modal = 2;
                                this.tituloModal = "Finalizar entrega";
                                this.tipoAccion = 3;
                                this.cero_detalles = 1;
                                this.fecha_entrega_real = data['fecha_program'];
                                this.hora_entrega_real = data['hora_entrega_prog'];
                            }
                            })
                        }
                        else{
                            this.folio = data['folio'];
                            this.modal = 2;
                            this.tituloModal = "Finalizar entrega";
                            this.tipoAccion = 3;
                            this.cero_detalles = 1;
                            this.fecha_entrega_real = data['fecha_program'];
                            this.hora_entrega_real = data['hora_entrega_prog'];
                        }
                        break;
                    }
                }
            }

        },

        mounted() {
            this.listarContratos(1,this.buscar2,this.b_etapa2,this.b_manzana2,this.b_lote2,this.criterio2);
            this.listarEntregas(1,this.buscar,this.b_etapa,this.b_manzana,this.b_lote,this.criterio);
            this.selectFraccionamientos();
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
