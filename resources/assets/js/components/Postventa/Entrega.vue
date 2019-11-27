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
                            <div class="col-md-8">
                                <button type="submit" @click="listarContratos(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                            
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr> 
                                        <th># Ref</th>
                                        <th>Proyecto</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th>Lote</th>
                                        <th>Cliente</th>
                                        <th>Contacto</th>
                                        <th>Fecha de firma</th>
                                        <th>Fecha entrega (Obra)</th>
                                        <th>Paquete y/o Promocioón</th>
                                        <th>Equipamiento</th>
                                        <th>Fecha entrega programada</th>
                                        <th>Hora entrega programada</th>
                                        <th>Finalizar Entrega</th>
                                        <th>Observaciones</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="contratos in arrayContratos" :key="contratos.id">
                                        <template>
                                            <td class="td2">
                                                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">{{contratos.folio}}</a>
                                                <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
                                                    <a class="dropdown-item" @click="abrirPDF(contratos.folio)">Estado de cuenta</a>
                                                    <a class="dropdown-item" target="_blank" v-bind:href="'/contratoCompraVenta/pdf/'+ contratos.folio">Contrato de compra venta</a>
                                                    <a v-if="contratos.carta_bienvenida" class="dropdown-item" target="_blank"  v-bind:href="'/downloadCartaBienvenida/'+contratos.carta_bienvenida">Carta de bienvenida</a>
                                                    <a class="dropdown-item" target="_blank" v-bind:href="'/serviciosTelecom/pdf/'+ contratos.folio">Servicios de telecomunición</a>
                                                    <a class="dropdown-item" v-bind:href="'/descargarReglamento/contrato/'+ contratos.folio">Reglamento de la etapa</a>
                                                    <a class="dropdown-item" @click="selectNombreArchivoModelo(contratos.folio)">Catalogo de especificaciones</a>
                                                    <a class="dropdown-item" target="_blank" v-bind:href="'/postventa/cartaMantenimiento/'+ contratos.folio">Carta de mantenimiento</a>
                                                    <a class="dropdown-item" target="_blank" v-bind:href="'/postventa/cartaRecepcion/'+ contratos.folio">Carta de recepción</a>
                                                    <a class="dropdown-item" target="_blank" v-bind:href="'/postventa/polizaDeGarantia/'+ contratos.folio">Poliza de garantia</a>
                                                    <a v-if="contratos.foto_predial" class="dropdown-item" v-bind:href="'/downloadPredial/'+ contratos.foto_predial">Predial</a>
                                                    <a v-if="contratos.num_licencia" class="dropdown-item"  v-text="'Licencia: '+contratos.num_licencia" v-bind:href="'/downloadLicencias/'+contratos.foto_lic"></a>
                                                </div>
                                            </td>
                                            <td class="td2" v-text="contratos.proyecto"></td>
                                            <td class="td2" v-text="contratos.etapa"></td>
                                            <td class="td2" v-text="contratos.manzana"></td>
                                            <td class="td2" v-text="contratos.num_lote"></td>
                                            <td class="td2" @click="abrirModal('ver_personal',contratos)" v-text="contratos.nombre_cliente"></td>
                                            <td class="td2">
                                                 <a title="Llamar" class="btn btn-dark" :href="'tel:'+contratos.celular"><i class="fa fa-phone fa-lg"></i></a>
                                                 <a title="Enviar whatsapp" class="btn btn-success" target="_blank" :href="'https://api.whatsapp.com/send?phone=+52'+contratos.celular+'&text=Hola'"><i class="fa fa-whatsapp fa-lg"></i></a>
                                                 <a title="Enviar correo" class="btn btn-secondary" :href="'mailto:'+contratos.email"> <i class="fa fa-envelope-o fa-lg"></i> </a>
                                            </td>
                                            <td class="td2" v-text="this.moment(contratos.fecha_firma_esc).locale('es').format('DD/MMM/YYYY')"></td>
                                            <template>
                                                <td class="td2" v-if="contratos.fecha_entrega_obra && contratos.diferencia_obra < 2">
                                                    <span v-text="this.moment(contratos.fecha_entrega_obra).locale('es').format('DD/MMM/YYYY')" class="badge badge-success"></span>
                                                </td>
                                                <td class="td2" v-else-if="contratos.fecha_entrega_obra && contratos.diferencia_obra > 2">
                                                    <span v-text="this.moment(contratos.fecha_entrega_obra).locale('es').format('DD/MMM/YYYY')" class="badge badge-danger"></span>
                                                </td>
                                                <td class="td2" v-else>
                                                    Sin fecha asignada
                                                </td>
                                            </template>
                                            <template>
                                                <td class="td2" v-if="contratos.paquete && contratos.promocion" v-text="'Paquete: '+contratos.paquete +' Promoción: '+contratos.promocion"></td>
                                                <td class="td2" v-else-if="contratos.paquete && !contratos.promocion" v-text="'Paquete: '+contratos.paquete" ></td>
                                                <td class="td2" v-else-if="!contratos.paquete && contratos.promocion" v-text="'Promoción: '+contratos.promocion" ></td>
                                                <td class="td2" v-else v-text="'Sin equipamiento'" ></td> 
                                            </template>
                                            <template>
                                                <td class="td2" v-if="contratos.paquete && contratos.promocion && contratos.equipamiento == 0">
                                                    <button title="Programar fecha" type="button" class="btn btn-danger pull-right" 
                                                        @click="ultimaFecha(contratos.folio)">
                                                        Equipamiento sin solicitarse
                                                    </button>
                                                </td>
                                                <td class="td2" v-else-if="contratos.paquete && !contratos.promocion && contratos.equipamiento == 0">
                                                     <button title="Programar fecha" type="button" class="btn btn-danger pull-right" 
                                                        @click="ultimaFecha(contratos.folio)">
                                                        Equipamiento sin solicitarse
                                                    </button>
                                                </td>
                                                <td class="td2" v-else-if="!contratos.paquete && contratos.promocion && contratos.equipamiento == 0">
                                                     <button title="Programar fecha" type="button" class="btn btn-danger pull-right" 
                                                        @click="ultimaFecha(contratos.folio)">
                                                        Equipamiento sin solicitarse
                                                    </button>
                                                </td>

                                                <td class="td2" v-else-if="contratos.paquete && contratos.promocion && contratos.equipamiento == 1">
                                                    <button title="Programar fecha" type="button" class="btn btn-warning pull-right" 
                                                        @click="ultimaFecha(contratos.folio)">
                                                        En proceso de instalación
                                                    </button>
                                                    
                                                </td>
                                                <td class="td2" v-else-if="contratos.paquete && !contratos.promocion && contratos.equipamiento == 1" >
                                                    <button title="Programar fecha" type="button" class="btn btn-warning pull-right" 
                                                        @click="ultimaFecha(contratos.folio)">
                                                        En proceso de instalación
                                                    </button>
                                                </td>
                                                <td class="td2" v-else-if="!contratos.paquete && contratos.promocion && contratos.equipamiento == 1">
                                                    <button title="Programar fecha" type="button" class="btn btn-warning pull-right" 
                                                        @click="ultimaFecha(contratos.folio)">
                                                        En proceso de instalación
                                                    </button>
                                                </td>

                                                <td class="td2" v-else-if="contratos.paquete && contratos.promocion && contratos.equipamiento == 2">
                                                    <button title="Programar fecha" type="button" class="btn btn-success pull-right" 
                                                        @click="ultimaFecha(contratos.folio)">
                                                        Equipamiento instalado
                                                    </button>
                                                   
                                                </td>
                                                <td class="td2" v-else-if="contratos.paquete && !contratos.promocion && contratos.equipamiento == 2">
                                                    <button title="Programar fecha" type="button" class="btn btn-success pull-right" 
                                                        @click="ultimaFecha(contratos.folio)">
                                                        Equipamiento instalado
                                                    </button>
                                                </td>
                                                <td class="td2" v-else-if="!contratos.paquete && contratos.promocion && contratos.equipamiento == 2">
                                                    <button title="Programar fecha" type="button" class="btn btn-success pull-right" 
                                                        @click="ultimaFecha(contratos.folio)">
                                                        Equipamiento instalado
                                                    </button>
                                                </td>
                                                <td class="td2" v-else v-text="'Sin equipamiento'" ></td> 
                                            </template>
                                            <template>
                                                <td class="td2" @click="abrirModal('programar_fecha', contratos)" v-if="contratos.fecha_program">
                                                    <span v-text="this.moment(contratos.fecha_program).locale('es').format('DD/MMM/YYYY')" class="badge badge-success"></span>
                                                </td>
                                                <td class="td2" v-else>
                                                    <button title="Programar fecha" type="button" class="btn btn-default pull-right" 
                                                        @click="abrirModal('programar_fecha', contratos)">
                                                        Programar fecha&nbsp;&nbsp;<i class="fa fa-calendar-plus-o fa-lg"></i>
                                                    </button>
                                                </td>
                                            </template>
                                            <template>
                                                <td class="td2" @click="abrirModal('programar_hora', contratos)" v-if="contratos.hora_entrega_prog">
                                                    <span v-text="contratos.hora_entrega_prog" class="badge badge-success"></span>
                                                </td>
                                                <td class="td2" v-else>
                                                    <button title="Programar hora" type="button" class="btn btn-default pull-right" 
                                                        @click="abrirModal('programar_hora', contratos)">
                                                        Programar hora&nbsp;&nbsp;<i class="fa fa-clock-o fa-lg"></i>
                                                    </button>
                                                </td>
                                            </template>
                                            <td class="td2">
                                                <button v-if="contratos.fecha_program" title="Finalizar entrega" type="button" class="btn btn-dark pull-right" 
                                                    @click="abrirModal('finalizar', contratos)">
                                                    Finalizar&nbsp;&nbsp;<i class="fa fa-trophy fa-lg"></i>
                                                </button>
                                                <label v-else>No se ha programado una fecha</label>
                                            </td>
                                            <td> 
                                                <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right" 
                                                    @click="abrirModal('observaciones', contratos),listarObservacion(1,contratos.folio)">Ver observaciones
                                                </button> 
                                            </td>
                                        </template>
                                    </tr>
                                </tbody>
                            </table>  
                        </div>
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
                    </div>
                <!-------------------  Fin Div para Contratos que tienen paquete o promoción  --------------------->


                <!-------------------  Div contratos pendientes por entregar --------------------->
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
                            <div class="col-md-8">
                                <button type="submit" @click="listarEntregas(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                            
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr> 
                                        <th># Ref</th>
                                        <th>Proyecto</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th>Lote</th>
                                        <th>Cliente</th>
                                        <th>Contacto</th>
                                        <th>Fecha de firma</th>
                                        <th>Fecha entrega (Obra)</th>
                                        <th>Paquete y/o Promocioón</th>
                                        <th>Equipamiento</th>
                                        <th>Fecha de Entrega</th>
                                        <th>Observaciones</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="entregas in arrayEntregas" :key="entregas.id">
                                        <template>
                                            <td class="td2">
                                                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">{{entregas.folio}}</a>
                                                <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
                                                    <a class="dropdown-item" @click="abrirPDF(entregas.folio)">Estado de cuenta</a>
                                                    <a class="dropdown-item" target="_blank" v-bind:href="'/contratoCompraVenta/pdf/'+ entregas.folio">Contrato de compra venta</a>
                                                    <a v-if="entregas.carta_bienvenida" class="dropdown-item" target="_blank"  v-bind:href="'/downloadCartaBienvenida/'+entregas.carta_bienvenida">Carta de bienvenida</a>
                                                    <a class="dropdown-item" target="_blank" v-bind:href="'/serviciosTelecom/pdf/'+ entregas.folio">Servicios de telecomunición</a>
                                                    <a class="dropdown-item" v-bind:href="'/descargarReglamento/contrato/'+ entregas.folio">Reglamento de la etapa</a>
                                                    <a class="dropdown-item" @click="selectNombreArchivoModelo(entregas.folio)">Catalogo de especificaciones</a>
                                                    <a class="dropdown-item" target="_blank" v-bind:href="'/postventa/cartaMantenimiento/'+ entregas.folio">Carta de mantenimiento</a>
                                                    <a class="dropdown-item" target="_blank" v-bind:href="'/postventa/cartaRecepcion/'+ entregas.folio">Carta de recepción</a>
                                                    <a class="dropdown-item" target="_blank" v-bind:href="'/postventa/polizaDeGarantia/'+ entregas.folio">Poliza de garantia</a>
                                                    <a v-if="entregas.foto_predial" class="dropdown-item" v-bind:href="'/downloadPredial/'+ entregas.foto_predial">Predial</a>
                                                    <a v-if="entregas.num_licencia" class="dropdown-item"  v-text="'Licencia: '+entregas.num_licencia" v-bind:href="'/downloadLicencias/'+entregas.foto_lic"></a>
                                                </div>
                                            </td>
                                            <td class="td2" v-text="entregas.proyecto"></td>
                                            <td class="td2" v-text="entregas.etapa"></td>
                                            <td class="td2" v-text="entregas.manzana"></td>
                                            <td class="td2" v-text="entregas.num_lote"></td>
                                            <td class="td2" @click="abrirModal('ver_personal',entregas)" v-text="entregas.nombre_cliente"></td>
                                            <td class="td2">
                                                 <a title="Llamar" class="btn btn-dark" :href="'tel:'+entregas.celular"><i class="fa fa-phone fa-lg"></i></a>
                                                 <a title="Enviar whatsapp" class="btn btn-success" target="_blank" :href="'https://api.whatsapp.com/send?phone=+52'+entregas.celular+'&text=Hola'"><i class="fa fa-whatsapp fa-lg"></i></a>
                                                 <a title="Enviar correo" class="btn btn-secondary" :href="'mailto:'+entregas.email"> <i class="fa fa-envelope-o fa-lg"></i> </a>
                                            </td>
                                            <td class="td2" v-text="this.moment(entregas.fecha_firma_esc).locale('es').format('DD/MMM/YYYY')"></td>
                                            <template>
                                                <td class="td2" v-if="entregas.fecha_entrega_obra && entregas.diferencia_obra < 2">
                                                    <span v-text="this.moment(entregas.fecha_entrega_obra).locale('es').format('DD/MMM/YYYY')" class="badge badge-success"></span>
                                                </td>
                                                <td class="td2" v-else>
                                                    <span v-text="this.moment(entregas.fecha_entrega_obra).locale('es').format('DD/MMM/YYYY')" class="badge badge-danger"></span>
                                                </td>
                                            </template>
                                            <template>
                                                <td class="td2" v-if="entregas.paquete && entregas.promocion" v-text="'Paquete: '+entregas.paquete +' Promoción: '+entregas.promocion"></td>
                                                <td class="td2" v-else-if="entregas.paquete && !entregas.promocion" v-text="'Paquete: '+entregas.paquete" ></td>
                                                <td class="td2" v-else-if="!entregas.paquete && entregas.promocion" v-text="'Promoción: '+entregas.promocion" ></td>
                                                <td class="td2" v-else v-text="'Sin equipamiento'" ></td> 
                                            </template>
                                            <template>
                                                <td class="td2" v-if="entregas.paquete && entregas.promocion && entregas.equipamiento == 0">
                                                     <span class="badge badge-danger">Equipamiento sin solicitarse</span>
                                                </td>
                                                <td class="td2" v-else-if="entregas.paquete && !entregas.promocion && entregas.equipamiento == 0">
                                                    <span class="badge badge-danger">Equipamiento sin solicitarse</span>
                                                </td>
                                                <td class="td2" v-else-if="!entregas.paquete && entregas.promocion && entregas.equipamiento == 0">
                                                    <span class="badge badge-danger">Equipamiento sin solicitarse</span>
                                                </td>

                                                <td class="td2" v-else-if="entregas.paquete && entregas.promocion && entregas.equipamiento == 1">
                                                    <span class="badge badge-warning">En proceso de instalación</span>
                                                </td>
                                                <td class="td2" v-else-if="entregas.paquete && !entregas.promocion && entregas.equipamiento == 1" >
                                                    <span class="badge badge-warning">En proceso de instalación</span>
                                                </td>
                                                <td class="td2" v-else-if="!entregas.paquete && entregas.promocion && entregas.equipamiento == 1">
                                                    <span class="badge badge-warning">En proceso de instalación</span>
                                                </td>

                                                <td class="td2" v-else-if="entregas.paquete && entregas.promocion && entregas.equipamiento == 2">
                                                    <span class="badge badge-success">Equipamiento instalado</span>
                                                </td>
                                                <td class="td2" v-else-if="entregas.paquete && !entregas.promocion && entregas.equipamiento == 2">
                                                    <span class="badge badge-success">Equipamiento instalado</span>
                                                </td>
                                                <td class="td2" v-else-if="!entregas.paquete && entregas.promocion && entregas.equipamiento == 2">
                                                    <span class="badge badge-success">Equipamiento instalado</span>
                                                </td>
                                                <td class="td2" v-else v-text="'Sin equipamiento'" ></td> 
                                            </template>
                                            <td class="td2">
                                                <span v-text="this.moment(entregas.fecha_entrega_real).locale('es').format('DD/MMM/YYYY')" class="badge badge-success"></span>
                                            </td>
                                            <td> 
                                                <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right" 
                                                    @click="abrirModal('observaciones', entregas),listarObservacion(1,entregas.folio)">Ver observaciones
                                                </button> 
                                            </td>
                                        </template>
                                    </tr>
                                </tbody>
                            </table>  
                        </div>
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
                    </div>
                <!-------------------  Fin Div para Contratos que tienen paquete o promoción  --------------------->


                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
         
            <!--Inicio del modal para diversos llenados de tabla en historial -->
                <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal2}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-primary modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" v-text="tituloModal"></h5>
                                <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                                <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group row" v-if="tipoAccion == 1">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de entrega programada</label>
                                    <div class="col-md-3">
                                        <input v-model="fecha_program" type="date" class="form-control">
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
 
                            </div>
                            <!-- Botones del modal -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                                <button type="button" v-if="tipoAccion == 1" class="btn btn-success" @click="progFecha()">Programar fecha</button>
                                <button type="button" v-if="tipoAccion == 2" class="btn btn-success" @click="progHora()">Programar hora</button>
                                <button type="button" v-if="tipoAccion == 3 && fecha_entrega_real != '' && hora_entrega_real != ''" class="btn btn-success" @click="finalizarEntrega()">Finalizar</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
            <!--Fin del modal-->

            <!--Inicio del modal observaciones-->
                <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal3}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-primary modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" v-text="tituloModal"></h4>
                                <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                                <span aria-hidden="true">×</span>
                                </button>
                            </div>
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


                                <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                    
                                    <table class="table table-bordered table-striped table-sm">
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
                                                <td v-text="observacion.comentario" ></td>
                                                <td v-text="observacion.created_at"></td>
                                            </tr>                               
                                        </tbody>
                                    </table>
                                    
                                </form>
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

            <!--Inicio del modal para mostrar los datos del cliente -->
                <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal1}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
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

                modal1:0,
                modal2: 0,
                modal3: 0,
                tituloModal: '',
                tipoAccion:0,

                //variables
                lote_id: 0,
                folio:0,
                contrato_id: 0,
                offset : 3,
                nombre_archivo_modelo:'',
                fecha_program:'',
                hora_entrega_prog:'',
                fecha_entrega_real:'',
                hora_entrega_real:'',
                cero_detalles : 0,
                

                //Datos clientes
                nombre_cliente:'',
                sexo_cliente:'',
                telefono_cliente:'',
                celular_cliente:'',
                email_cliente:'',
                direccion_cliente:'',
                cp_cliente:'',
                colonia_cliente:'',
                estado_cliente:'',
                ciudad_cliente:'',
                fechanac_cliente:'',
                curp_cliente:'',
                rfc_cliente:'',
                homoclave_cliente:'',
                nss_cliente:'',
                tipoeconomia_cliente:'',
                empresa_cliente:'',
                gironegocio_cliente:'',
                domicilio_empresa:'',
                cpempresa_cliente:'',
                coloniaempresa_cliente:'',
                estadoempresa_cliente:'',
                ciudadempresa_cliente:'',
                emailinstitucional_cliente:'',
                telefonoempresa_cliente:'',
                ext_cliente:'',
                edocivil_cliente:'',
                depeconomicos_cliente:'',

                // Criterios para historial de contratos
                pagination2 : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                criterio2 : 'entregas.fecha_program', 
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

                tipoAccion:0,
                observacion:'',

                errorEntrega : 0,
                errorMostrarMsjEntrega : [],
               
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
            listarContratos(page, buscar, b_etapa, b_manzana, b_lote, criterio){
                let me = this;
                var url = '/postventa/index?page=' + page + '&buscar=' + buscar + '&b_etapa=' + b_etapa + '&b_manzana=' + b_manzana + '&b_lote=' + b_lote +  '&criterio=' + criterio;
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

            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
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
                            me.fecha = "Sin fecha";
                            }
                        else{
                            me.fecha = me.arrayUltimaFecha[0].fin_instalacion;
                            }
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
                this.modal2 = 0;
                this.modal3 = 0;
                this.modal1= 0;
                this.observacion = '';
                this.arrayObservacion = [];
                this.nombre_cliente="";
                this.sexo_cliente="";
                this.telefono_cliente="";
                this.celular_cliente="";
                this.email_cliente="";
                this.direccion_cliente="";
                this.cp_cliente="";
                this.colonia_cliente="";
                this.estado_cliente="";
                this.ciudad_cliente="";
                this.fechanac_cliente="";
                this.curp_cliente="";
                this.rfc_cliente="";
                this.homoclave_cliente="";
                this.nss_cliente="";
                this.tipoeconomia_cliente="";
                this.empresa_cliente="";
                this.gironegocio_cliente="";
                this.domicilio_empresa="";
                this.cpempresa_cliente="";
                this.coloniaempresa_cliente="";
                this.estadoempresa_cliente="";
                this.ciudadempresa_cliente="";
                this.emailinstitucional_cliente="";
                this.telefonoempresa_cliente="";
                this.ext_cliente="";
                this.edocivil_cliente="";
                this.depeconomicos_cliente="";
                this.errorEntrega = 0;
                this.errorMostrarMsjEntrega = [];
            },

            abrirModal(accion,data =[]){
                    switch(accion){

                        case 'observaciones':{
                            this.modal3 =1;
                            this.modal1=0;
                            this.tituloModal='Observaciones';
                            this.folio = data['folio'];
                            this.observacion = '';
                            break;
                        }

                        case 'ver_personal':
                        {
                            this.modal1 =1;
                            this.tituloModal='Datos del prospecto';
                            this.tipoAccion=3;
                            this.nombre_cliente = data['nombre_cliente'];
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

                        case 'programar_fecha':{
                            this.folio = data['folio'];
                            this.modal2 = 1;
                            this.tituloModal = "Programar Fecha";
                            this.fecha_program = data['fecha_program'];
                            this.tipoAccion = 1;
                            break;
                        }

                        case 'programar_hora':{
                            this.folio = data['folio'];
                            this.modal2 = 1;
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
                                    this.modal2 = 1;
                                    this.tituloModal = "Finalizar entrega";
                                    this.tipoAccion = 3;
                                    this.cero_detalles = 0;
                                }
                                })
                            }
                            else{
                                this.folio = data['folio'];
                                this.modal2 = 1;
                                this.tituloModal = "Finalizar entrega";
                                this.tipoAccion = 3;
                                this.cero_detalles = 0;

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

    .badge2 {
    display: inline-block;
    padding: 0.25em 0.4em;
    font-size: 90%;
    font-weight: bold;
    line-height: 1;
    color: #fff;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
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
