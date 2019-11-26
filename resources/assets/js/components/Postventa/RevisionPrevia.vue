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
                        <i class="fa fa-align-justify"></i> Revisión previa
                        &nbsp;&nbsp;<button type="submit" v-if="revision == 1" @click="cerrarRevision()" class="btn btn-default"><i class="fa fa-mail-reply-all"></i> Regresar</button>
                    </div>

                <!-------------------  Div lotes por revisar --------------------->
                <template v-if="revision == 0">
                    <div class="card-body">
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
                                        <th>Fecha entrega programada</th>
                                        <th>Revisión</th>
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
                                            <td class="td2" v-text="contratos.nombre_cliente"></td>
                                            <td class="td2">
                                                <span style="font-size:85%;" v-text="this.moment(contratos.fecha_program).locale('es').format('DD/MMM/YYYY')" class="badge badge-success"></span>
                                            </td>
                                             <td>
                                                <button v-if="contratos.revision_previa == 0" title="Realizar revision" type="button" 
                                                    class="btn btn-success pull-right" @click="realizarRevision(contratos.folio,contratos.diferencia)">
                                                    <i class="fa fa-check-square-o"></i> Realizar revisión
                                                </button>
                                                <a v-if="contratos.revision_previa == 2" title="Ver revision" type="button" 
                                                    class="btn btn-danger pull-right" target="_blank" :href="'/postventa/checklist/pdf/'+contratos.folio">
                                                    <i class="fa fa-check-square-o"></i> Ver revisión
                                                </a> 
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
                </template>
                <!---------- Check list --------------->
                <template v-else>
                    <div class="card-body"> 
                        <h5 v-text="'CHECK-LIST'"></h5>
                            <div class="form-group row border" id="accordion" role="tablist">
                                <div class="col-md-12">
                                    
                                </div>
                                <!--- COCHERA --->
                                <div class="col-md-4">
                                    <div class="form-group border">
                                        <center> <h5>
                                            <a data-toggle="collapse" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne" class="collapsed">
                                                Cochera </a></h5> </center>
                                    </div>
                                    <div class="form-group row border collapse" id="collapseOne" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion" style="">
                                        <ul class="nav-dropdown-items">
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="mona_cochera" type="checkbox" value="1" @click="mona_cochera_obs = ''"/> Mona</a>
                                                <input v-model="mona_cochera_obs" v-if="mona_cochera == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="centro_carga_cochera" type="checkbox" value="1" @click="centro_carga_cochera_obs = ''"/> Centro de carga</a>
                                                <input v-model="centro_carga_cochera_obs" v-if="centro_carga_cochera == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="cuadro_hidraulico_cochera" type="checkbox" value="1" @click="cuadro_hidraulico_cochera_obs = ''"/> Cuadro hidráulico</a>
                                                <input v-model="cuadro_hidraulico_cochera_obs" v-if="cuadro_hidraulico_cochera == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="interfon_cochera" type="checkbox" value="1" @click="interfon_cochera_obs = ''"/> Interfon</a>
                                                <input v-model="interfon_cochera_obs" v-if="interfon_cochera == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="cisterna_cochera" type="checkbox" value="1" @click="cisterna_cochera_obs = ''"/> Cisterna</a>
                                                <input v-model="cisterna_cochera_obs" v-if="cisterna_cochera == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="bomba_cochera" type="checkbox" value="1" @click="bomba_cochera_obs = ''"/> Bomba</a>
                                                <input v-model="bomba_cochera_obs" v-if="bomba_cochera == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="tapa_cisterna_cochera" type="checkbox" value="1" @click="tapa_cisterna_cochera_obs = ''"/> Tapa cisterna</a>
                                                <input v-model="tapa_cisterna_cochera_obs" v-if="tapa_cisterna_cochera == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="tapa_registro_cochera" type="checkbox" value="1" @click="tapa_registro_cochera_obs = ''"/> Tapas registros</a>
                                                <input v-model="tapa_registro_cochera_obs" v-if="tapa_registro_cochera == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acc_electrico_cochera" type="checkbox" value="1" @click="acc_electrico_cochera_obs = ''"/> Acc. Eléctricos</a>
                                                <input v-model="acc_electrico_cochera_obs" v-if="acc_electrico_cochera == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acabado_muros_cochera" type="checkbox" value="1" @click="acabado_muros_cochera_obs = ''"/> Acabado muros</a>
                                                <input v-model="acabado_muros_cochera_obs" v-if="acabado_muros_cochera == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acabado_plafon_cochera" type="checkbox" value="1" @click="acabado_plafon_cochera_obs = ''"/> Acabado plafón</a>
                                                <input v-model="acabado_plafon_cochera_obs" v-if="acabado_plafon_cochera == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="piso_cochera" type="checkbox" value="1" @click="piso_cochera_obs = ''"/> Piso</a>
                                                <input v-model="piso_cochera_obs" v-if="piso_cochera == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="zoclo_cochera" type="checkbox" value="1" @click="zoclo_cochera_obs = ''"/> Zoclo</a>
                                                <input v-model="zoclo_cochera_obs" v-if="zoclo_cochera == 1" type="text"/>
                                            </li>
                                        </ul>                                       
                                    </div>
                                </div> 

                                <!--- SALA COMEDOR --->
                                <div class="col-md-4">
                                    <div class="form-group border">
                                        <center> <h5>
                                            <a data-toggle="collapse" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" class="collapsed">
                                                Sala Comedor </a></h5> </center>
                                    </div>
                                    <div class="form-group row border collapse" id="collapseTwo" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion" style="">
                                        <ul class="nav-dropdown-items">
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="puerta_pric_sala" type="checkbox" value="1" @click="puerta_pric_sala_obs = ''"/> Puerta principal</a>
                                                <input v-model="puerta_pric_sala_obs" v-if="puerta_pric_sala == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="chapa_sala" type="checkbox" value="1" @click="chapa_sala_obs = ''"/> Chapa</a>
                                                <input v-model="chapa_sala_obs" v-if="chapa_sala == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="sello_marco_sala" type="checkbox" value="1" @click="sello_marco_sala_obs = ''"/> Sello marco</a>
                                                <input v-model="sello_marco_sala_obs" v-if="sello_marco_sala == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="ventana_sala" type="checkbox" value="1" @click="ventana_sala_obs = ''"/> Ventana</a>
                                                <input v-model="ventana_sala_obs" v-if="ventana_sala == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="sello_ventana_sala" type="checkbox" value="1" @click="sello_ventana_sala_obs = ''"/> Sello ventana</a>
                                                <input v-model="sello_ventana_sala_obs" v-if="sello_ventana_sala == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="vidrio_ventana_sala" type="checkbox" value="1" @click="vidrio_ventana_sala_obs = ''"/> Vidrio ventana</a>
                                                <input v-model="vidrio_ventana_sala_obs" v-if="vidrio_ventana_sala == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="mosquitero_sala" type="checkbox" value="1" @click="mosquitero_sala_obs = ''"/> Mosquitero</a>
                                                <input v-model="mosquitero_sala_obs" v-if="mosquitero_sala == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="cancel_sala" type="checkbox" value="1" @click="cancel_sala_obs = ''"/> Cancel</a>
                                                <input v-model="cancel_sala_obs" v-if="cancel_sala == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="sello_cancel_sala" type="checkbox" value="1" @click="sello_cancel_sala_obs = ''"/> Sello cancel</a>
                                                <input v-model="sello_cancel_sala_obs" v-if="sello_cancel_sala == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="vidrio_cancel_sala" type="checkbox" value="1" @click="vidrio_cancel_sala_obs = ''"/> Vidrio cancel</a>
                                                <input v-model="vidrio_cancel_sala_obs" v-if="vidrio_cancel_sala == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="salida_alarma_sala" type="checkbox" value="1" @click="salida_alarma_sala_obs = ''"/> Salida alarma</a>
                                                <input v-model="salida_alarma_sala_obs" v-if="salida_alarma_sala == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acc_electrico_sala" type="checkbox" value="1" @click="acc_electrico_sala_obs = ''"/> Acc. Eléctricos</a>
                                                <input v-model="acc_electrico_sala_obs" v-if="acc_electrico_sala == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acabado_muros_sala" type="checkbox" value="1" @click="acabado_muros_sala_obs = ''"/> Acabado muros</a>
                                                <input v-model="acabado_muros_sala_obs" v-if="acabado_muros_sala == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acabado_plafon_sala" type="checkbox" value="1" @click="acabado_plafon_sala_obs = ''"/> Acabado plafón</a>
                                                <input v-model="acabado_plafon_sala_obs" v-if="acabado_plafon_sala == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="piso_sala" type="checkbox" value="1" @click="piso_sala_obs = ''"/> Piso</a>
                                                <input v-model="piso_sala_obs" v-if="piso_sala == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="zoclo_sala" type="checkbox" value="1" @click="zoclo_sala_obs = ''"/> Zoclo</a>
                                                <input v-model="zoclo_sala_obs" v-if="zoclo_sala == 1" type="text"/>
                                            </li>
                                     
                                        </ul>                                       
                                    </div>
                                </div>

                                <!--- COCINA --->
                                <div class="col-md-4">
                                    <div class="form-group border">
                                        <center> <h5>
                                            <a data-toggle="collapse" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree" class="collapsed">
                                                Cocina </a></h5> </center>
                                    </div>
                                    <div class="form-group row border collapse" id="collapseThree" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion" style="">
                                        <ul class="nav-dropdown-items">
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="tarja_cocina" type="checkbox" value="1" @click="tarja_cocina_obs = ''"/> Tarja</a>
                                                <input v-model="tarja_cocina_obs" v-if="tarja_cocina == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="puerta_cocina" type="checkbox" value="1" @click="puerta_cocina_obs = ''"/> Puerta/ventana</a>
                                                <input v-model="puerta_cocina_obs" v-if="puerta_cocina == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="chapa_cocina" type="checkbox" value="1" @click="chapa_cocina_obs = ''"/> Chapa</a>
                                                <input v-model="chapa_cocina_obs" v-if="chapa_cocina == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="sello_pv_cocina" type="checkbox" value="1" @click="sello_pv_cocina_obs = ''"/> Sello en p/v</a>
                                                <input v-model="sello_pv_cocina_obs" v-if="sello_pv_cocina == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="vidrio_pv_cocina" type="checkbox" value="1" @click="vidrio_pv_cocina_obs = ''"/> Vidrio en p/v</a>
                                                <input v-model="vidrio_pv_cocina_obs" v-if="vidrio_pv_cocina == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="mosquitero_cocina" type="checkbox" value="1" @click="mosquitero_cocina_obs = ''"/> Mosquitero</a>
                                                <input v-model="mosquitero_cocina_obs" v-if="mosquitero_cocina == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="salida_alarma_cocina" type="checkbox" value="1" @click="salida_alarma_cocina_obs = ''"/> Salida de alarma</a>
                                                <input v-model="salida_alarma_cocina_obs" v-if="salida_alarma_cocina == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="interfon_cocina" type="checkbox" value="1" @click="interfon_cocina_obs = ''"/> Interfón</a>
                                                <input v-model="interfon_cocina_obs" v-if="interfon_cocina == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acc_electrico_cocina" type="checkbox" value="1" @click="acc_electrico_cocina_obs = ''"/> Acc. Eléctricos</a>
                                                <input v-model="acc_electrico_cocina_obs" v-if="acc_electrico_cocina == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="centro_carga_cocina" type="checkbox" value="1" @click="centro_carga_cocina_obs = ''"/> Centro de carga</a>
                                                <input v-model="centro_carga_cocina_obs" v-if="centro_carga_cocina == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="inst_gas_cocina" type="checkbox" value="1" @click="inst_gas_cocina_obs = ''"/> Inst. Gas</a>
                                                <input v-model="inst_gas_cocina_obs" v-if="inst_gas_cocina == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="inst_refrigerador_cocina" type="checkbox" value="1" @click="inst_refrigerador_cocina_obs = ''"/> Inst. Refrigerador</a>
                                                <input v-model="inst_refrigerador_cocina_obs" v-if="inst_refrigerador_cocina == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="barra_cocina" type="checkbox" value="1" @click="barra_cocina_obs = ''"/> Barra</a>
                                                <input v-model="barra_cocina_obs" v-if="barra_cocina == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="azulejo_cocina" type="checkbox" value="1" @click="azulejo_cocina_obs = ''"/> Azulejo muro</a>
                                                <input v-model="azulejo_cocina_obs" v-if="azulejo_cocina == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acabado_muro_cocina" type="checkbox" value="1" @click="acabado_muro_cocina_obs = ''"/> Acabado muros</a>
                                                <input v-model="acabado_muro_cocina_obs" v-if="acabado_muro_cocina == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acabado_plafon_cocina" type="checkbox" value="1" @click="acabado_plafon_cocina_obs = ''"/> Acabado plafón</a>
                                                <input v-model="acabado_plafon_cocina_obs" v-if="acabado_plafon_cocina == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="piso_cocina" type="checkbox" value="1" @click="piso_cocina_obs = ''"/> Piso</a>
                                                <input v-model="piso_cocina_obs" v-if="piso_cocina == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="zoclo_cocina" type="checkbox" value="1" @click="zoclo_cocina_obs = ''"/> Zoclo</a>
                                                <input v-model="zoclo_cocina_obs" v-if="zoclo_cocina == 1" type="text"/>
                                            </li>
                                            
                                     
                                        </ul>                                       
                                    </div>
                                </div>

                                <!--- MEDIO BAÑO --->
                                <div class="col-md-4">
                                    <div class="form-group border">
                                        <center> <h5>
                                            <a data-toggle="collapse" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour" class="collapsed">
                                                Medio Baño </a></h5> </center>
                                    </div>
                                    <div class="form-group row border collapse" id="collapseFour" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion" style="">
                                        <ul class="nav-dropdown-items">
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="puerta_mb" type="checkbox" value="1" @click="puerta_mb_obs = ''"/> Puerta</a>
                                                <input v-model="puerta_mb_obs" v-if="puerta_mb == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="chapa_mb" type="checkbox" value="1" @click="chapa_mb_obs = ''"/> Chapa</a>
                                                <input v-model="chapa_mb_obs" v-if="chapa_mb == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="barra_lavabo_mb" type="checkbox" value="1" @click="barra_lavabo_mb_obs = ''"/> Barra lavabo</a>
                                                <input v-model="barra_lavabo_mb_obs" v-if="barra_lavabo_mb == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="lavabo_mb" type="checkbox" value="1" @click="lavabo_mb_obs = ''"/> Lavabo</a>
                                                <input v-model="lavabo_mb_obs" v-if="lavabo_mb == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="monomando_mb" type="checkbox" value="1" @click="monomando_mb_obs = ''"/> Monomando</a>
                                                <input v-model="monomando_mb_obs" v-if="monomando_mb == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="wc_mb" type="checkbox" value="1" @click="wc_mb_obs = ''"/> WC</a>
                                                <input v-model="wc_mb_obs" v-if="wc_mb == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acc_bano_mb" type="checkbox" value="1" @click="acc_bano_mb_obs = ''"/> Acc. Baño</a>
                                                <input v-model="acc_bano_mb_obs" v-if="acc_bano_mb == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acc_electrico_mb" type="checkbox" value="1" @click="acc_electrico_mb_obs = ''"/> Acc. Eléctricos</a>
                                                <input v-model="acc_electrico_mb_obs" v-if="acc_electrico_mb == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="ventana_mb" type="checkbox" value="1" @click="ventana_mb_obs = ''"/> Ventana</a>
                                                <input v-model="ventana_mb_obs" v-if="ventana_mb == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="sello_ventana_mb" type="checkbox" value="1" @click="sello_ventana_mb_obs = ''"/> Sello ventana</a>
                                                <input v-model="sello_ventana_mb_obs" v-if="sello_ventana_mb == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="vidrio_mb" type="checkbox" value="1" @click="vidrio_mb_obs = ''"/> Vidrio ventana</a>
                                                <input v-model="vidrio_mb_obs" v-if="vidrio_mb == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="mosquitero_mb" type="checkbox" value="1" @click="mosquitero_mb_obs = ''"/> Mosquitero</a>
                                                <input v-model="mosquitero_mb_obs" v-if="mosquitero_mb == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acabado_muro_mb" type="checkbox" value="1" @click="acabado_muro_mb_obs = ''"/> Acabado muros</a>
                                                <input v-model="acabado_muro_mb_obs" v-if="acabado_muro_mb == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acabado_plafon_mb" type="checkbox" value="1" @click="acabado_plafon_mb_obs = ''"/> Acabado plafón</a>
                                                <input v-model="acabado_plafon_mb_obs" v-if="acabado_plafon_mb == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="piso_mb" type="checkbox" value="1" @click="piso_mb_obs = ''"/> Piso</a>
                                                <input v-model="piso_mb_obs" v-if="piso_mb == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="zoclo_mb" type="checkbox" value="1" @click="zoclo_mb_obs = ''"/> Zoclo</a>
                                                <input v-model="zoclo_mb_obs" v-if="zoclo_mb == 1" type="text"/>
                                            </li>

                                        </ul>                                       
                                    </div>
                                </div>

                                <!--- PATIO --->
                                <div class="col-md-4">
                                    <div class="form-group border">
                                        <center> <h5>
                                            <a data-toggle="collapse" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive" class="collapsed">
                                                Patio </a></h5> </center>
                                    </div>
                                    <div class="form-group row border collapse" id="collapseFive" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion" style="">
                                        <ul class="nav-dropdown-items">
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="calentador_patio" type="checkbox" value="1" @click="calentador_patio_obs = ''"/> Calentador</a>
                                                <input v-model="calentador_patio_obs" v-if="calentador_patio == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="inst_gas_patio" type="checkbox" value="1" @click="inst_gas_patio_obs = ''"/> Inst. Gas</a>
                                                <input v-model="inst_gas_patio_obs" v-if="inst_gas_patio == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acc_electrico_patio" type="checkbox" value="1" @click="acc_electrico_patio_obs = ''"/> Acc. Eléctricos</a>
                                                <input v-model="acc_electrico_patio_obs" v-if="acc_electrico_patio == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="lavadero_patio" type="checkbox" value="1" @click="lavadero_patio_obs = ''"/> Lavadero</a>
                                                <input v-model="lavadero_patio_obs" v-if="lavadero_patio == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="llaves_nariz_patio" type="checkbox" value="1" @click="llaves_nariz_patio_obs = ''"/> Llaves nariz</a>
                                                <input v-model="llaves_nariz_patio_obs" v-if="llaves_nariz_patio == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="descarga_lavadora_patio" type="checkbox" value="1" @click="descarga_lavadora_patio_obs = ''"/> Descarga lavadora</a>
                                                <input v-model="descarga_lavadora_patio_obs" v-if="descarga_lavadora_patio == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="coladera_patio" type="checkbox" value="1" @click="coladera_patio_obs = ''"/> Coladera</a>
                                                <input v-model="coladera_patio_obs" v-if="coladera_patio == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="tapa_registro_patio" type="checkbox" value="1" @click="tapa_registro_patio_obs = ''"/> Tapa registro</a>
                                                <input v-model="tapa_registro_patio_obs" v-if="tapa_registro_patio == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="escalera_marina_patio" type="checkbox" value="1" @click="escalera_marina_patio_obs = ''"/> Escalera marina</a>
                                                <input v-model="escalera_marina_patio_obs" v-if="escalera_marina_patio == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="techumbre_patio" type="checkbox" value="1" @click="techumbre_patio_obs = ''"/> Techumbre patio</a>
                                                <input v-model="techumbre_patio_obs" v-if="techumbre_patio == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="firme_cilindros_patio" type="checkbox" value="1" @click="firme_cilindros_patio_obs = ''"/> Firme cilindros</a>
                                                <input v-model="firme_cilindros_patio_obs" v-if="firme_cilindros_patio == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="rodapie_patio" type="checkbox" value="1" @click="rodapie_patio_obs = ''"/> Rodapie</a>
                                                <input v-model="rodapie_patio_obs" v-if="rodapie_patio == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acabado_muros_patio" type="checkbox" value="1" @click="acabado_muros_patio_obs = ''"/> Acabado muros</a>
                                                <input v-model="acabado_muros_patio_obs" v-if="acabado_muros_patio == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acabado_volado_patio" type="checkbox" value="1" @click="acabado_volado_patio_obs = ''"/> Acabado volado</a>
                                                <input v-model="acabado_volado_patio_obs" v-if="acabado_volado_patio == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="piso_patio" type="checkbox" value="1" @click="piso_patio_obs = ''"/> Piso</a>
                                                <input v-model="piso_patio_obs" v-if="piso_patio == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="zoclo_patio" type="checkbox" value="1" @click="zoclo_patio_obs = ''"/> Zoclo</a>
                                                <input v-model="zoclo_patio_obs" v-if="zoclo_patio == 1" type="text"/>
                                            </li>
                                            


                                        </ul>                                       
                                    </div>
                                </div>

                                <!--- ESCALERA --->
                                <div class="col-md-4">
                                    <div class="form-group border">
                                        <center> <h5>
                                            <a data-toggle="collapse" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix" class="collapsed">
                                                Escalera </a></h5> </center>
                                    </div>
                                    <div class="form-group row border collapse" id="collapseSix" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion" style="">
                                        <ul class="nav-dropdown-items">
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="nicho_escalera" type="checkbox" value="1" @click="nicho_escalera_obs = ''"/> Nicho</a>
                                                <input v-model="nicho_escalera_obs" v-if="nicho_escalera == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="escalones_escalera" type="checkbox" value="1" @click="escalones_escalera_obs = ''"/> Escalones</a>
                                                <input v-model="escalones_escalera_obs" v-if="escalones_escalera == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="piso_escalera" type="checkbox" value="1" @click="piso_escalera_obs = ''"/> Piso</a>
                                                <input v-model="piso_escalera_obs" v-if="piso_escalera == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="zoclo_escalera" type="checkbox" value="1" @click="zoclo_escalera_obs = ''"/> Zoclo</a>
                                                <input v-model="zoclo_escalera_obs" v-if="zoclo_escalera == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="barandal_escalera" type="checkbox" value="1" @click="barandal_escalera_obs = ''"/> Barandal</a>
                                                <input v-model="barandal_escalera_obs" v-if="barandal_escalera == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="pasamanos_escalera" type="checkbox" value="1" @click="pasamanos_escalera_obs = ''"/> Pasamanos</a>
                                                <input v-model="pasamanos_escalera_obs" v-if="pasamanos_escalera == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="sardinel_escalera" type="checkbox" value="1" @click="sardinel_escalera_obs = ''"/> Sardinel</a>
                                                <input v-model="sardinel_escalera_obs" v-if="sardinel_escalera == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="macetero_escalera" type="checkbox" value="1" @click="macetero_escalera_obs = ''"/> Macetero</a>
                                                <input v-model="macetero_escalera_obs" v-if="macetero_escalera == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="cajillos_escalera" type="checkbox" value="1" @click="cajillos_escalera_obs = ''"/> Cajillos</a>
                                                <input v-model="cajillos_escalera_obs" v-if="cajillos_escalera == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acc_electricos_escalera" type="checkbox" value="1" @click="acc_electricos_escalera_obs = ''"/> Acc. Eléctricos</a>
                                                <input v-model="acc_electricos_escalera_obs" v-if="acc_electricos_escalera == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acabado_muro_escalera" type="checkbox" value="1" @click="acabado_muro_escalera_obs = ''"/> Acabado muros</a>
                                                <input v-model="acabado_muro_escalera_obs" v-if="acabado_muro_escalera == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acabado_plafon_escalera" type="checkbox" value="1" @click="acabado_plafon_escalera_obs = ''"/> Acabado plafón</a>
                                                <input v-model="acabado_plafon_escalera_obs" v-if="acabado_plafon_escalera == 1" type="text"/>
                                            </li>
                                            
                                        </ul>                                       
                                    </div>
                                </div>

                                <!--- BAÑO COMUN --->
                                <div class="col-md-4">
                                    <div class="form-group border">
                                        <center> <h5>
                                            <a data-toggle="collapse" href="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven" class="collapsed">
                                                Baño Común </a></h5> </center>
                                    </div>
                                    <div class="form-group row border collapse" id="collapseSeven" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion" style="">
                                        <ul class="nav-dropdown-items">
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="puerta_bc" type="checkbox" value="1" @click="puerta_bc_obs = ''"/> Puerta</a>
                                                <input v-model="puerta_bc_obs" v-if="puerta_bc == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="chapa_bc" type="checkbox" value="1" @click="chapa_bc_obs = ''"/> Chapa</a>
                                                <input v-model="chapa_bc_obs" v-if="chapa_bc == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="sello_marco_bc" type="checkbox" value="1" @click="sello_marco_bc_obs = ''"/> Sello marco</a>
                                                <input v-model="sello_marco_bc_obs" v-if="sello_marco_bc == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="barra_lavabo_bc" type="checkbox" value="1" @click="barra_lavabo_bc_obs = ''"/> Barra lavabo</a>
                                                <input v-model="barra_lavabo_bc_obs" v-if="barra_lavabo_bc == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="lavabo_bc" type="checkbox" value="1" @click="lavabo_bc_obs = ''"/> Lavabo</a>
                                                <input v-model="lavabo_bc_obs" v-if="lavabo_bc == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="monomando_bc" type="checkbox" value="1" @click="monomando_bc_obs = ''"/> Monomando</a>
                                                <input v-model="monomando_bc_obs" v-if="monomando_bc == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="wc_bc" type="checkbox" value="1" @click="wc_bc_obs = ''"/> WC</a>
                                                <input v-model="wc_bc_obs" v-if="wc_bc == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="regadera_bc" type="checkbox" value="1" @click="regadera_bc_obs = ''"/> Regadera</a>
                                                <input v-model="regadera_bc_obs" v-if="regadera_bc == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="manerales_bc" type="checkbox" value="1" @click="manerales_bc_obs = ''"/> Manerales</a>
                                                <input v-model="manerales_bc_obs" v-if="manerales_bc == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="coladera_bc" type="checkbox" value="1" @click="coladera_bc_obs = ''"/> Coladera</a>
                                                <input v-model="coladera_bc_obs" v-if="coladera_bc == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acc_bano_bc" type="checkbox" value="1" @click="acc_bano_bc_obs = ''"/> Acc. Baño</a>
                                                <input v-model="acc_bano_bc_obs" v-if="acc_bano_bc == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acc_electricos_bc" type="checkbox" value="1" @click="acc_electricos_bc_obs = ''"/> Acc. Eléctricos</a>
                                                <input v-model="acc_electricos_bc_obs" v-if="acc_electricos_bc == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="ventana_bc" type="checkbox" value="1" @click="ventana_bc_obs = ''"/> Ventana</a>
                                                <input v-model="ventana_bc_obs" v-if="ventana_bc == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="sello_ventana_bc" type="checkbox" value="1" @click="sello_ventana_bc_obs = ''"/> Sello ventana</a>
                                                <input v-model="sello_ventana_bc_obs" v-if="sello_ventana_bc == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="vidrio_ventana_bc" type="checkbox" value="1" @click="vidrio_ventana_bc_obs = ''"/> Vidrio ventana</a>
                                                <input v-model="vidrio_ventana_bc_obs" v-if="vidrio_ventana_bc == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="mosquitero_bc" type="checkbox" value="1" @click="mosquitero_bc_obs = ''"/> Mosquitero</a>
                                                <input v-model="mosquitero_bc_obs" v-if="mosquitero_bc == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acabado_muro_bc" type="checkbox" value="1" @click="acabado_muro_bc_obs = ''"/> Acabado muros</a>
                                                <input v-model="acabado_muro_bc_obs" v-if="acabado_muro_bc == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acabado_plafon_bc" type="checkbox" value="1" @click="acabado_plafon_bc_obs = ''"/> Acabado plafón</a>
                                                <input v-model="acabado_plafon_bc_obs" v-if="acabado_plafon_bc == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="sardinel_bc" type="checkbox" value="1" @click="sardinel_bc_obs = ''"/> Sardinel</a>
                                                <input v-model="sardinel_bc_obs" v-if="sardinel_bc == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="piso_bc" type="checkbox" value="1" @click="piso_bc_obs = ''"/> Piso</a>
                                                <input v-model="piso_bc_obs" v-if="piso_bc == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="zoclo_bc" type="checkbox" value="1" @click="zoclo_bc_obs = ''"/> Zoclo</a>
                                                <input v-model="zoclo_bc_obs" v-if="zoclo_bc == 1" type="text"/>
                                            </li>
                                            
                                        </ul>                                       
                                    </div>
                                </div>

                                <!--- ESTANCIA --->
                                <div class="col-md-4">
                                    <div class="form-group border">
                                        <center> <h5>
                                            <a data-toggle="collapse" href="#collapseEight" aria-expanded="false" aria-controls="collapseEight" class="collapsed">
                                                Estancia </a></h5> </center>
                                    </div>
                                    <div class="form-group row border collapse" id="collapseEight" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion" style="">
                                        <ul class="nav-dropdown-items">
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="ventana_estancia" type="checkbox" value="1" @click="ventana_estancia_obs = ''"/> Ventana</a>
                                                <input v-model="ventana_estancia_obs" v-if="ventana_estancia == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="sello_ventana_estancia" type="checkbox" value="1" @click="sello_ventana_estancia_obs = ''"/> Sello ventana</a>
                                                <input v-model="sello_ventana_estancia_obs" v-if="sello_ventana_estancia == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="vidrio_ventana_estancia" type="checkbox" value="1" @click="vidrio_ventana_estancia_obs = ''"/> Vidrio ventana</a>
                                                <input v-model="vidrio_ventana_estancia_obs" v-if="vidrio_ventana_estancia == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="mosquitero_estancia" type="checkbox" value="1" @click="mosquitero_estancia_obs = ''"/> Mosquitero</a>
                                                <input v-model="mosquitero_estancia_obs" v-if="mosquitero_estancia == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="interfon_estancia" type="checkbox" value="1" @click="interfon_estancia_obs = ''"/> Interfón</a>
                                                <input v-model="interfon_estancia_obs" v-if="interfon_estancia == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acc_electricos_estancia" type="checkbox" value="1" @click="acc_electricos_estancia_obs = ''"/> Acc. Eléctricos</a>
                                                <input v-model="acc_electricos_estancia_obs" v-if="acc_electricos_estancia == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acabado_muro_estancia" type="checkbox" value="1" @click="acabado_muro_estancia_obs = ''"/> Acabado muros</a>
                                                <input v-model="acabado_muro_estancia_obs" v-if="acabado_muro_estancia == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acabado_plafon_estancia" type="checkbox" value="1" @click="acabado_plafon_estancia_obs = ''"/> Acabado plafón</a>
                                                <input v-model="acabado_plafon_estancia_obs" v-if="acabado_plafon_estancia == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="piso_estancia" type="checkbox" value="1" @click="piso_estancia_obs = ''"/> Piso</a>
                                                <input v-model="piso_estancia_obs" v-if="piso_estancia == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="zoclo_estancia" type="checkbox" value="1" @click="zoclo_estancia_obs = ''"/> Zoclo</a>
                                                <input v-model="zoclo_estancia_obs" v-if="zoclo_estancia == 1" type="text"/>
                                            </li>
                                        </ul>                                       
                                    </div>
                                </div>

                                <!--- RECÁMARA PRINCIPAL --->
                                <div class="col-md-4">
                                    <div class="form-group border">
                                        <center> <h5>
                                            <a data-toggle="collapse" href="#collapseNine" aria-expanded="false" aria-controls="collapseNine" class="collapsed">
                                                Recámara Principal </a></h5> </center>
                                    </div>
                                    <div class="form-group row border collapse" id="collapseNine" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion" style="">
                                        <ul class="nav-dropdown-items">
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="puerta_rp" type="checkbox" value="1" @click="puerta_rp_obs = ''"/> Puerta</a>
                                                <input v-model="puerta_rp_obs" v-if="puerta_rp == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="chapa_rp" type="checkbox" value="1" @click="chapa_rp_obs = ''"/> Chapa</a>
                                                <input v-model="chapa_rp_obs" v-if="chapa_rp == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="sello_marco_rp" type="checkbox" value="1" @click="sello_marco_rp_obs = ''"/> Sello marco</a>
                                                <input v-model="sello_marco_rp_obs" v-if="sello_marco_rp == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="cancel_rp" type="checkbox" value="1" @click="cancel_rp_obs = ''"/> Cancel</a>
                                                <input v-model="cancel_rp_obs" v-if="cancel_rp == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="sello_cancel_rp" type="checkbox" value="1" @click="sello_cancel_rp_obs = ''"/> Sello cancel</a>
                                                <input v-model="sello_cancel_rp_obs" v-if="sello_cancel_rp == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="vidrio_cancel_rp" type="checkbox" value="1" @click="vidrio_cancel_rp_obs = ''"/> Vidrio cancel</a>
                                                <input v-model="vidrio_cancel_rp_obs" v-if="vidrio_cancel_rp == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="mosquitero_rp" type="checkbox" value="1" @click="mosquitero_rp_obs = ''"/> Mosquitero</a>
                                                <input v-model="mosquitero_rp_obs" v-if="mosquitero_rp == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="balcon_rp" type="checkbox" value="1" @click="balcon_rp_obs = ''"/> Balcón</a>
                                                <input v-model="balcon_rp_obs" v-if="balcon_rp == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="barandal_rp" type="checkbox" value="1" @click="barandal_rp_obs = ''"/> Barandal</a>
                                                <input v-model="barandal_rp_obs" v-if="barandal_rp == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acc_electricos_rp" type="checkbox" value="1" @click="acc_electricos_rp_obs = ''"/> Acc. Eléctricos</a>
                                                <input v-model="acc_electricos_rp_obs" v-if="acc_electricos_rp == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="interfon_rp" type="checkbox" value="1" @click="interfon_rp_obs = ''"/> Interfón</a>
                                                <input v-model="interfon_rp_obs" v-if="interfon_rp == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="salida_alarma_rp" type="checkbox" value="1" @click="salida_alarma_rp_obs = ''"/> Salida alarma</a>
                                                <input v-model="salida_alarma_rp_obs" v-if="salida_alarma_rp == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acabado_muro_rp" type="checkbox" value="1" @click="acabado_muro_rp_obs = ''"/> Acabado muros</a>
                                                <input v-model="acabado_muro_rp_obs" v-if="acabado_muro_rp == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acabado_plafon_rp" type="checkbox" value="1" @click="acabado_plafon_rp_obs = ''"/> Acabado plafón</a>
                                                <input v-model="acabado_plafon_rp_obs" v-if="acabado_plafon_rp == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="piso_rp" type="checkbox" value="1" @click="piso_rp_obs = ''"/> Piso</a>
                                                <input v-model="piso_rp_obs" v-if="piso_rp == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="zoclo_rp" type="checkbox" value="1" @click="zoclo_rp_obs = ''"/> Zoclo</a>
                                                <input v-model="zoclo_rp_obs" v-if="zoclo_rp == 1" type="text"/>
                                            </li>
                                        </ul>                                       
                                    </div>
                                </div>

                                <!--- BAÑO RECÁMARA PRINCIPAL --->
                                <div class="col-md-4">
                                    <div class="form-group border">
                                        <center> <h5>
                                            <a data-toggle="collapse" href="#collapseTen" aria-expanded="false" aria-controls="collapseTen" class="collapsed">
                                                Baño Recámara Principal </a></h5> </center>
                                    </div>
                                    <div class="form-group row border collapse" id="collapseTen" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion" style="">
                                        <ul class="nav-dropdown-items">
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="puerta_brp" type="checkbox" value="1" @click="puerta_brp_obs = ''"/> Puerta</a>
                                                <input v-model="puerta_brp_obs" v-if="puerta_brp == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="chapa_brp" type="checkbox" value="1" @click="chapa_brp_obs = ''"/> Chapa</a>
                                                <input v-model="chapa_brp_obs" v-if="chapa_brp == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="sello_marco_brp" type="checkbox" value="1" @click="sello_marco_brp_obs = ''"/> Sello marco</a>
                                                <input v-model="sello_marco_brp_obs" v-if="sello_marco_brp == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="barra_lavabo_brp" type="checkbox" value="1" @click="barra_lavabo_brp_obs = ''"/> Barra lavabo</a>
                                                <input v-model="barra_lavabo_brp_obs" v-if="barra_lavabo_brp == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="lavabo_brp" type="checkbox" value="1" @click="lavabo_brp_obs = ''"/> Lavabo</a>
                                                <input v-model="lavabo_brp_obs" v-if="lavabo_brp == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="monomando_brp" type="checkbox" value="1" @click="monomando_brp_obs = ''"/> Monomando</a>
                                                <input v-model="monomando_brp_obs" v-if="monomando_brp == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="wc_brp" type="checkbox" value="1" @click="wc_brp_obs = ''"/> WC</a>
                                                <input v-model="wc_brp_obs" v-if="wc_brp == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="regadera_brp" type="checkbox" value="1" @click="regadera_brp_obs = ''"/> Regadera</a>
                                                <input v-model="regadera_brp_obs" v-if="regadera_brp == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="manerales_brp" type="checkbox" value="1" @click="manerales_brp_obs = ''"/> Manerales</a>
                                                <input v-model="manerales_brp_obs" v-if="manerales_brp == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="coladera_brp" type="checkbox" value="1" @click="coladera_brp_obs = ''"/> Coladera</a>
                                                <input v-model="coladera_brp_obs" v-if="coladera_brp == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acc_bano_brp" type="checkbox" value="1" @click="acc_bano_brp_obs = ''"/> Acc. Baño</a>
                                                <input v-model="acc_bano_brp_obs" v-if="acc_bano_brp == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acc_electrico_brp" type="checkbox" value="1" @click="acc_electrico_brp_obs = ''"/> Acc. Eléctricos</a>
                                                <input v-model="acc_electrico_brp_obs" v-if="acc_electrico_brp == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="ventana_brp" type="checkbox" value="1" @click="ventana_brp_obs = ''"/> Ventana</a>
                                                <input v-model="ventana_brp_obs" v-if="ventana_brp == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="sello_ventana_brp" type="checkbox" value="1" @click="sello_ventana_brp_obs = ''"/> Sello ventana</a>
                                                <input v-model="sello_ventana_brp_obs" v-if="sello_ventana_brp == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="vidrio_ventana_brp" type="checkbox" value="1" @click="vidrio_ventana_brp_obs = ''"/> Vidrio ventana</a>
                                                <input v-model="vidrio_ventana_brp_obs" v-if="vidrio_ventana_brp == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="mosquitero_brp" type="checkbox" value="1" @click="mosquitero_brp_obs = ''"/> Mosquitero</a>
                                                <input v-model="mosquitero_brp_obs" v-if="mosquitero_brp == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acabado_muro_brp" type="checkbox" value="1" @click="acabado_muro_brp_obs = ''"/> Acabado muros</a>
                                                <input v-model="acabado_muro_brp_obs" v-if="acabado_muro_brp == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acabado_plafon_brp" type="checkbox" value="1" @click="acabado_plafon_brp_obs = ''"/> Acabado plafón</a>
                                                <input v-model="acabado_plafon_brp_obs" v-if="acabado_plafon_brp == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="sardinel_brp" type="checkbox" value="1" @click="sardinel_brp_obs = ''"/> Sardinel</a>
                                                <input v-model="sardinel_brp_obs" v-if="sardinel_brp == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="piso_brp" type="checkbox" value="1" @click="piso_brp_obs = ''"/> Piso</a>
                                                <input v-model="piso_brp_obs" v-if="piso_brp == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="zoclo_brp" type="checkbox" value="1" @click="zoclo_brp_obs = ''"/> Zoclo</a>
                                                <input v-model="zoclo_brp_obs" v-if="zoclo_brp == 1" type="text"/>
                                            </li>
                                        </ul>                                       
                                    </div>
                                </div>

                                <!--- VESTIDOR --->
                                <div class="col-md-4">
                                    <div class="form-group border">
                                        <center> <h5>
                                            <a data-toggle="collapse" href="#collapseEleven" aria-expanded="false" aria-controls="collapseEleven" class="collapsed">
                                                Vestidor </a></h5> </center>
                                    </div>
                                    <div class="form-group row border collapse" id="collapseEleven" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion" style="">
                                        <ul class="nav-dropdown-items">
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acc_electrico_vest" type="checkbox" value="1" @click="acc_electrico_vest_obs = ''"/> Acc. Eléctricos</a>
                                                <input v-model="acc_electrico_vest_obs" v-if="acc_electrico_vest == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acabado_muro_vest" type="checkbox" value="1" @click="acabado_muro_vest_obs = ''"/> Acabado muros</a>
                                                <input v-model="acabado_muro_vest_obs" v-if="acabado_muro_vest == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acabado_plafon_vest" type="checkbox" value="1" @click="acabado_plafon_vest_obs = ''"/> Acabado plafón</a>
                                                <input v-model="acabado_plafon_vest_obs" v-if="acabado_plafon_vest == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="piso_vest" type="checkbox" value="1" @click="piso_vest_obs = ''"/> Piso</a>
                                                <input v-model="piso_vest_obs" v-if="piso_vest == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="zoclo_vest" type="checkbox" value="1" @click="zoclo_vest_obs = ''"/> Zoclo</a>
                                                <input v-model="zoclo_vest_obs" v-if="zoclo_vest == 1" type="text"/>
                                            </li>
                                        </ul>                                       
                                    </div>
                                </div>

                                <!--- RECAMARA 2 --->
                                <div class="col-md-4">
                                    <div class="form-group border">
                                        <center> <h5>
                                            <a data-toggle="collapse" href="#collapseTwelve" aria-expanded="false" aria-controls="collapseTwelve" class="collapsed">
                                                Recamara 2 </a></h5> </center>
                                    </div>
                                    <div class="form-group row border collapse" id="collapseTwelve" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion" style="">
                                        <ul class="nav-dropdown-items">
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="puerta_rec2" type="checkbox" value="1" @click="puerta_rec2_obs = ''"/> Puerta</a>
                                                <input v-model="puerta_rec2_obs" v-if="puerta_rec2 == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="chapa_rec2" type="checkbox" value="1" @click="chapa_rec2_obs = ''"/> Chapa</a>
                                                <input v-model="chapa_rec2_obs" v-if="chapa_rec2 == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="sello_marco_rec2" type="checkbox" value="1" @click="sello_marco_rec2_obs = ''"/> Sello marco</a>
                                                <input v-model="sello_marco_rec2_obs" v-if="sello_marco_rec2 == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="cancel_rec2" type="checkbox" value="1" @click="cancel_rec2_obs = ''"/> Cancel</a>
                                                <input v-model="cancel_rec2_obs" v-if="cancel_rec2 == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="sello_cancel_rec2" type="checkbox" value="1" @click="sello_cancel_rec2_obs = ''"/> Sello cancel</a>
                                                <input v-model="sello_cancel_rec2_obs" v-if="sello_cancel_rec2 == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="vidrio_cancel_rec2" type="checkbox" value="1" @click="vidrio_cancel_rec2_obs = ''"/> Vidrio cancel</a>
                                                <input v-model="vidrio_cancel_rec2_obs" v-if="vidrio_cancel_rec2 == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="mosquitero_rec2" type="checkbox" value="1" @click="mosquitero_rec2_obs = ''"/> Mosquitero</a>
                                                <input v-model="mosquitero_rec2_obs" v-if="mosquitero_rec2 == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acc_rec2" type="checkbox" value="1" @click="acc_rec2_obs = ''"/> Acc. Eléctricos</a>
                                                <input v-model="acc_rec2_obs" v-if="acc_rec2 == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="salida_alarma_rec2" type="checkbox" value="1" @click="salida_alarma_rec2_obs = ''"/> Salida alarma</a>
                                                <input v-model="salida_alarma_rec2_obs" v-if="salida_alarma_rec2 == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acabado_muro_rec2" type="checkbox" value="1" @click="acabado_muro_rec2_obs = ''"/> Acabado muros</a>
                                                <input v-model="acabado_muro_rec2_obs" v-if="acabado_muro_rec2 == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acabado_plafon_rec2" type="checkbox" value="1" @click="acabado_plafon_rec2_obs = ''"/> Acabado plafón</a>
                                                <input v-model="acabado_plafon_rec2_obs" v-if="acabado_plafon_rec2 == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="piso_rec2" type="checkbox" value="1" @click="piso_rec2_obs = ''"/> Piso</a>
                                                <input v-model="piso_rec2_obs" v-if="piso_rec2 == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="zoclo_rec2" type="checkbox" value="1" @click="zoclo_rec2_obs = ''"/> Zoclo</a>
                                                <input v-model="zoclo_rec2_obs" v-if="zoclo_rec2 == 1" type="text"/>
                                            </li>
                                        </ul>                                       
                                    </div>
                                </div>

                                <!--- RECAMARA 3 --->
                                <div class="col-md-4">
                                    <div class="form-group border">
                                        <center> <h5>
                                            <a data-toggle="collapse" href="#collapseThirteen" aria-expanded="false" aria-controls="collapseThirteen" class="collapsed">
                                                Recamara 3 </a></h5> </center>
                                    </div>
                                    <div class="form-group row border collapse" id="collapseThirteen" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion" style="">
                                        <ul class="nav-dropdown-items">
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="puerta_rec3" type="checkbox" value="1" @click="puerta_rec3_obs = ''"/> Puerta</a>
                                                <input v-model="puerta_rec3_obs" v-if="puerta_rec3 == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="chapa_rec3" type="checkbox" value="1" @click="chapa_rec3_obs = ''"/> Chapa</a>
                                                <input v-model="chapa_rec3_obs" v-if="chapa_rec3 == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="sello_marco_rec3" type="checkbox" value="1" @click="sello_marco_rec3_obs = ''"/> Sello marco</a>
                                                <input v-model="sello_marco_rec3_obs" v-if="sello_marco_rec3 == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="cancel_rec3" type="checkbox" value="1" @click="cancel_rec3_obs = ''"/> Cancel</a>
                                                <input v-model="cancel_rec3_obs" v-if="cancel_rec3 == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="sello_cancel_rec3" type="checkbox" value="1" @click="sello_cancel_rec3_obs = ''"/> Sello cancel</a>
                                                <input v-model="sello_cancel_rec3_obs" v-if="sello_cancel_rec3 == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="vidrio_cancel_rec3" type="checkbox" value="1" @click="vidrio_cancel_rec3_obs = ''"/> Vidrio cancel</a>
                                                <input v-model="vidrio_cancel_rec3_obs" v-if="vidrio_cancel_rec3 == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="mosquitero_rec3" type="checkbox" value="1" @click="mosquitero_rec3_obs = ''"/> Mosquitero</a>
                                                <input v-model="mosquitero_rec3_obs" v-if="mosquitero_rec3 == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acc_rec3" type="checkbox" value="1" @click="acc_rec3_obs = ''"/> Acc. Eléctricos</a>
                                                <input v-model="acc_rec3_obs" v-if="acc_rec3 == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="salida_alarma_rec3" type="checkbox" value="1" @click="salida_alarma_rec3_obs = ''"/> Salida alarma</a>
                                                <input v-model="salida_alarma_rec3_obs" v-if="salida_alarma_rec3 == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acabado_muro_rec3" type="checkbox" value="1" @click="acabado_muro_rec3_obs = ''"/> Acabado muros</a>
                                                <input v-model="acabado_muro_rec3_obs" v-if="acabado_muro_rec3 == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="acabado_plafon_rec3" type="checkbox" value="1" @click="acabado_plafon_rec3_obs = ''"/> Acabado plafón</a>
                                                <input v-model="acabado_plafon_rec3_obs" v-if="acabado_plafon_rec3 == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="piso_rec3" type="checkbox" value="1" @click="piso_rec3_obs = ''"/> Piso</a>
                                                <input v-model="piso_rec3_obs" v-if="piso_rec3 == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="zoclo_rec3" type="checkbox" value="1" @click="zoclo_rec3_obs = ''"/> Zoclo</a>
                                                <input v-model="zoclo_rec3_obs" v-if="zoclo_rec3 == 1" type="text"/>
                                            </li>
                                        </ul>                                       
                                    </div>
                                </div>

                                <!--- AZOTEA --->
                                <div class="col-md-4">
                                    <div class="form-group border">
                                        <center> <h5>
                                            <a data-toggle="collapse" href="#collapseFourteen" aria-expanded="false" aria-controls="collapseFourteen" class="collapsed">
                                                Azotea </a></h5> </center>
                                    </div>
                                    <div class="form-group row border collapse" id="collapseFourteen" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion" style="">
                                        <ul class="nav-dropdown-items">
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="pretiles_azotea" type="checkbox" value="1" @click="pretiles_azotea_obs = ''"/> Pretiles</a>
                                                <input v-model="pretiles_azotea_obs" v-if="pretiles_azotea == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="impermeabilizacion" type="checkbox" value="1" @click="impermeabilizacion_obs = ''"/> Impermeabilización</a>
                                                <input v-model="impermeabilizacion_obs" v-if="impermeabilizacion == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="domos_azotea" type="checkbox" value="1" @click="domos_azotea_obs = ''"/> Domos</a>
                                                <input v-model="domos_azotea_obs" v-if="domos_azotea == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="mufas_azotea" type="checkbox" value="1" @click="mufas_azotea_obs = ''"/> Mufas</a>
                                                <input v-model="mufas_azotea_obs" v-if="mufas_azotea == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="jarros_azotea" type="checkbox" value="1" @click="jarros_azotea_obs = ''"/> Jarros de aire</a>
                                                <input v-model="jarros_azotea_obs" v-if="jarros_azotea == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="ventilas_azotea" type="checkbox" value="1" @click="ventilas_azotea_obs = ''"/> Ventilas inst. Sn.</a>
                                                <input v-model="ventilas_azotea_obs" v-if="ventilas_azotea == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="base_tinaco_azotea" type="checkbox" value="1" @click="base_tinaco_azotea_obs = ''"/> Base tinaco</a>
                                                <input v-model="base_tinaco_azotea_obs" v-if="base_tinaco_azotea == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="tinaco_azotea" type="checkbox" value="1" @click="tinaco_azotea_obs = ''"/> Tinaco</a>
                                                <input v-model="tinaco_azotea_obs" v-if="tinaco_azotea == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="calentador_solar_azotea" type="checkbox" value="1" @click="calentador_solar_azotea_obs = ''"/> Calentador solar</a>
                                                <input v-model="calentador_solar_azotea_obs" v-if="calentador_solar_azotea == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="punta_gas_azotea" type="checkbox" value="1" @click="punta_gas_azotea_obs = ''"/> Punta inst. Gas.</a>
                                                <input v-model="punta_gas_azotea_obs" v-if="punta_gas_azotea == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="anclas_azotea" type="checkbox" value="1" @click="anclas_azotea_obs = ''"/> Anclas escalera</a>
                                                <input v-model="anclas_azotea_obs" v-if="anclas_azotea == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="limpieza_azotea" type="checkbox" value="1" @click="limpieza_azotea_obs = ''"/> Limpieza</a>
                                                <input v-model="limpieza_azotea_obs" v-if="limpieza_azotea == 1" type="text"/>
                                            </li>
                                        </ul>                                       
                                    </div>
                                </div>

                                <!--- GENERALELS --->
                                <div class="col-md-4">
                                    <div class="form-group border">
                                        <center> <h5>
                                            <a data-toggle="collapse" href="#collapseFifteen" aria-expanded="false" aria-controls="collapseFifteen" class="collapsed">
                                                Generales </a></h5> </center>
                                    </div>
                                    <div class="form-group row border collapse" id="collapseFifteen" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion" style="">
                                        <ul class="nav-dropdown-items">
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="limpieza_interior" type="checkbox" value="1" @click="limpieza_interior_obs = ''"/> Limpieza interior</a>
                                                <input v-model="limpieza_interior_obs" v-if="limpieza_interior == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="limpieza_exterior" type="checkbox" value="1" @click="limpieza_exterior_obs = ''"/> Limpieza exterior</a>
                                                <input v-model="limpieza_exterior_obs" v-if="limpieza_exterior == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="limpieza_vidrios" type="checkbox" value="1" @click="limpieza_vidrios_obs = ''"/> Limpieza en vidrios</a>
                                                <input v-model="limpieza_vidrios_obs" v-if="limpieza_vidrios == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="limpieza_domos" type="checkbox" value="1" @click="limpieza_domos_obs = ''"/> Limpieza en domos</a>
                                                <input v-model="limpieza_domos_obs" v-if="limpieza_domos == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="plastico_muebles" type="checkbox" value="1" @click="plastico_muebles_obs = ''"/> Plástico en muebles</a>
                                                <input v-model="plastico_muebles_obs" v-if="plastico_muebles == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="candados" type="checkbox" value="1" @click="candados_obs = ''"/> Candados</a>
                                                <input v-model="candados_obs" v-if="candados == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="llaves" type="checkbox" value="1" @click="llaves_obs = ''"/> Llaves</a>
                                                <input v-model="llaves_obs" v-if="llaves == 1" type="text"/>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link"> <input v-model="num_oficial" type="checkbox" value="1" @click="num_oficial_obs = ''"/> Número Oficial</a>
                                                <input v-model="num_oficial_obs" v-if="num_oficial == 1" type="text"/>
                                            </li>
                                        </ul>                                       
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-md-3 form-control-label" for="text-input">Observaciones:</label>
                                        <textarea rows="3" cols="30" v-model="observacion" class="form-control" placeholder="Observaciones"></textarea>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                </div> 

                                
                            </div>

                            <div class="form-group row">
                                <div class="col-md-10">
                                    <button type="button" class="btn btn-secondary" @click="cerrarRecepcion()"> Cerrar </button>
                                </div>

                                <div class="col-md-2">
                                    <button type="button" class="btn btn-success" @click="terminarRevision()"> Guardar </button>
                                </div>
                            </div>

                    </div>
                </template>
                    
                <!-------------------  Fin Div para Contratos que tienen paquete o promoción  --------------------->


                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>

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

                revision : 0,
                diferencia : 0,

                arrayFraccionamientos2:[],
                arrayEtapas2:[],
                arrayAllManzanas:[],
                arrayAllLotes:[],
                arrayContratos:[],

                modal3: 0,
                tituloModal: '',
                tipoAccion:0,

                //variables
                lote_id: 0,
                folio:0,
                contrato_id: 0,
                offset : 3,
                nombre_archivo_modelo:'',  

                ////////////////// VARIABLES PARA REVISION PREVIA ///////////////////
                    ///////// COCHERA ////////////
                        mona_cochera : 0,
                        centro_carga_cochera : 0,
                        cuadro_hidraulico_cochera : 0,
                        interfon_cochera : 0,
                        cisterna_cochera : 0,
                        bomba_cochera : 0,
                        tapa_cisterna_cochera : 0,
                        tapa_registro_cochera : 0,
                        acc_electrico_cochera : 0,
                        acabado_muros_cochera : 0,
                        acabado_plafon_cochera : 0,
                        piso_cochera : 0,
                        zoclo_cochera : 0,
                        //////// Observacion /////////
                        mona_cochera_obs : '',
                        centro_carga_cochera_obs : '',
                        cuadro_hidraulico_cochera_obs : '',
                        interfon_cochera_obs : '',
                        cisterna_cochera_obs : '',
                        bomba_cochera_obs : '',
                        tapa_cisterna_cochera_obs : '',
                        tapa_registro_cochera_obs : '',
                        acc_electrico_cochera_obs : '',
                        acabado_muros_cochera_obs : '',
                        acabado_plafon_cochera_obs : '',
                        piso_cochera_obs : '',
                        zoclo_cochera_obs : '',

                    ///////// SALA COMEDOR /////////
                        puerta_pric_sala : 0,
                        chapa_sala : 0,
                        sello_marco_sala : 0,
                        ventana_sala : 0,
                        sello_ventana_sala : 0,
                        vidrio_ventana_sala : 0,
                        mosquitero_sala : 0,
                        cancel_sala : 0,
                        sello_cancel_sala : 0,
                        vidrio_cancel_sala : 0,
                        salida_alarma_sala : 0,
                        acc_electrico_sala : 0,
                        acabado_muros_sala : 0,
                        acabado_plafon_sala : 0,
                        piso_sala : 0,
                        zoclo_sala : 0,
                        ////// Observacion ///////
                        puerta_pric_sala_obs : '',
                        chapa_sala_obs : '',
                        sello_marco_sala_obs : '',
                        ventana_sala_obs : '',
                        sello_ventana_sala_obs : '',
                        vidrio_ventana_sala_obs : '',
                        mosquitero_sala_obs : '',
                        cancel_sala_obs : '',
                        sello_cancel_sala_obs : '',
                        vidrio_cancel_sala_obs : '',
                        salida_alarma_sala_obs : '',
                        acc_electrico_sala_obs : '',
                        acabado_muros_sala_obs : '',
                        acabado_plafon_sala_obs : '',
                        piso_sala_obs : '',
                        zoclo_sala_obs : '',
                    
                    ///////////////// COCINA //////////////
                        tarja_cocina : 0,
                        puerta_cocina : 0,
                        chapa_cocina : 0,
                        sello_pv_cocina : 0,
                        vidrio_pv_cocina : 0,
                        mosquitero_cocina : 0,
                        salida_alarma_cocina : 0,
                        interfon_cocina : 0,
                        acc_electrico_cocina : 0,
                        centro_carga_cocina : 0,
                        inst_gas_cocina : 0,
                        inst_refrigerador_cocina : 0,
                        barra_cocina : 0,
                        azulejo_cocina : 0,
                        acabado_muro_cocina : 0,
                        acabado_plafon_cocina : 0,
                        piso_cocina : 0,
                        zoclo_cocina : 0,
                        ///////// Observaciones ////////////
                        tarja_cocina_obs : '',
                        puerta_cocina_obs : '',
                        chapa_cocina_obs : '',
                        sello_pv_cocina_obs : '',
                        vidrio_pv_cocina_obs : '',
                        mosquitero_cocina_obs : '',
                        salida_alarma_cocina_obs : '',
                        interfon_cocina_obs : '',
                        acc_electrico_cocina_obs : '',
                        centro_carga_cocina_obs : '',
                        inst_gas_cocina_obs : '',
                        inst_refrigerador_cocina_obs : '',
                        barra_cocina_obs : '',
                        azulejo_cocina_obs : '',
                        acabado_muro_cocina_obs : '',
                        acabado_plafon_cocina_obs : '',
                        piso_cocina_obs : '',
                        zoclo_cocina_obs : '',
                    //////////  MEDIO BAÑO  /////////////
                        puerta_mb : 0, 
                        chapa_mb : 0,
                        barra_lavabo_mb : 0,
                        lavabo_mb : 0,
                        monomando_mb : 0,
                        wc_mb : 0,
                        acc_bano_mb : 0,
                        acc_electrico_mb : 0,
                        ventana_mb : 0,
                        sello_ventana_mb : 0,
                        vidrio_mb : 0,
                        mosquitero_mb : 0,
                        acabado_muro_mb : 0,
                        acabado_plafon_mb : 0,
                        piso_mb : 0,
                        zoclo_mb : 0,
                        ////////// Observacion //////////
                        puerta_mb_obs : '', 
                        chapa_mb_obs : '',
                        barra_lavabo_mb_obs : '',
                        lavabo_mb_obs : '',
                        monomando_mb_obs : '',
                        wc_mb_obs : '',
                        acc_bano_mb_obs : '',
                        acc_electrico_mb_obs : '',
                        ventana_mb_obs : '',
                        sello_ventana_mb_obs : '',
                        vidrio_mb_obs : '',
                        mosquitero_mb_obs : '',
                        acabado_muro_mb_obs : '',
                        acabado_plafon_mb_obs : '',
                        piso_mb_obs : '',
                        zoclo_mb_obs : '',

                    /////////////  PATIO  /////////////////
                        calentador_patio : 0, 
                        inst_gas_patio : 0, 
                        acc_electrico_patio : 0, 
                        lavadero_patio : 0, 
                        llaves_nariz_patio : 0, 
                        descarga_lavadora_patio : 0, 
                        coladera_patio : 0, 
                        tapa_registro_patio : 0, 
                        escalera_marina_patio : 0, 
                        techumbre_patio : 0, 
                        firme_cilindros_patio : 0, 
                        rodapie_patio : 0, 
                        acabado_muros_patio : 0, 
                        acabado_volado_patio : 0, 
                        piso_patio : 0, 
                        zoclo_patio : 0, 
                        ///////// Observacion //////////////
                        calentador_patio_obs : '', 
                        inst_gas_patio_obs : '', 
                        acc_electrico_patio_obs : '', 
                        lavadero_patio_obs : '', 
                        llaves_nariz_patio_obs : '', 
                        descarga_lavadora_patio_obs : '', 
                        coladera_patio_obs : '', 
                        tapa_registro_patio_obs : '', 
                        escalera_marina_patio_obs : '', 
                        techumbre_patio_obs : '', 
                        firme_cilindros_patio_obs : '', 
                        rodapie_patio_obs : '', 
                        acabado_muros_patio_obs : '', 
                        acabado_volado_patio_obs : '', 
                        piso_patio_obs : '', 
                        zoclo_patio_obs : '',
                    
                    /////////////// ESCALERA ////////////////
                        nicho_escalera : 0, 
                        escalones_escalera : 0,
                        piso_escalera : 0,
                        zoclo_escalera : 0,
                        barandal_escalera : 0,
                        pasamanos_escalera : 0,
                        sardinel_escalera : 0,
                        macetero_escalera : 0,
                        cajillos_escalera : 0,
                        acc_electricos_escalera : 0,
                        acabado_muro_escalera : 0,
                        acabado_plafon_escalera : 0,
                        ///////////// Observacion //////////////
                        nicho_escalera_obs : '', 
                        escalones_escalera_obs : '',
                        piso_escalera_obs : '',
                        zoclo_escalera_obs : '',
                        barandal_escalera_obs : '',
                        pasamanos_escalera_obs : '',
                        sardinel_escalera_obs : '',
                        macetero_escalera_obs : '',
                        cajillos_escalera_obs : '',
                        acc_electricos_escalera_obs : '',
                        acabado_muro_escalera_obs : '',
                        acabado_plafon_escalera_obs : '',
                    ///////////////  BAÑO COMUN  //////////////////
                        puerta_bc : 0,
                        chapa_bc : 0,
                        sello_marco_bc : 0,
                        barra_lavabo_bc : 0,
                        lavabo_bc : 0,
                        monomando_bc : 0,
                        wc_bc : 0,
                        regadera_bc : 0,
                        manerales_bc : 0,
                        coladera_bc : 0,
                        acc_bano_bc : 0,
                        acc_electricos_bc : 0,
                        ventana_bc : 0,
                        sello_ventana_bc : 0,
                        vidrio_ventana_bc : 0,
                        mosquitero_bc : 0,
                        acabado_muro_bc : 0,
                        acabado_plafon_bc : 0,
                        sardinel_bc : 0,
                        piso_bc : 0,
                        zoclo_bc : 0,
                        ///////////// Observacion ////////////////
                        puerta_bc_obs : '',
                        chapa_bc_obs : '',
                        sello_marco_bc_obs : '',
                        barra_lavabo_bc_obs : '',
                        lavabo_bc_obs : '',
                        monomando_bc_obs : '',
                        wc_bc_obs : '',
                        regadera_bc_obs : '',
                        manerales_bc_obs : '',
                        coladera_bc_obs : '',
                        acc_bano_bc_obs : '',
                        acc_electricos_bc_obs : '',
                        ventana_bc_obs : '',
                        sello_ventana_bc_obs : '',
                        vidrio_ventana_bc_obs : '',
                        mosquitero_bc_obs : '',
                        acabado_muro_bc_obs : '',
                        acabado_plafon_bc_obs : '',
                        sardinel_bc_obs : '',
                        piso_bc_obs : '',
                        zoclo_bc_obs : '',
                    
                    ///////////// ESTANCIA //////////////
                        ventana_estancia : 0,
                        sello_ventana_estancia : 0,
                        vidrio_ventana_estancia : 0,
                        mosquitero_estancia : 0,
                        interfon_estancia : 0,
                        acc_electricos_estancia : 0,
                        acabado_muro_estancia : 0,
                        acabado_plafon_estancia : 0,
                        piso_estancia : 0,
                        zoclo_estancia : 0,
                        //////////// Observacion /////////////
                        ventana_estancia_obs : '',
                        sello_ventana_estancia_obs : '',
                        vidrio_ventana_estancia_obs : '',
                        mosquitero_estancia_obs : '',
                        interfon_estancia_obs : '',
                        acc_electricos_estancia_obs : '',
                        acabado_muro_estancia_obs : '',
                        acabado_plafon_estancia_obs : '',
                        piso_estancia_obs : '',
                        zoclo_estancia_obs : '',

                    //////////// Recamara Principal //////////////
                        puerta_rp : 0,
                        chapa_rp : 0,
                        sello_marco_rp : 0,
                        cancel_rp : 0,
                        sello_cancel_rp : 0,
                        vidrio_cancel_rp : 0,
                        mosquitero_rp : 0,
                        balcon_rp : 0,
                        barandal_rp : 0,
                        acc_electricos_rp : 0,
                        interfon_rp : 0,
                        salida_alarma_rp : 0,
                        acabado_muro_rp : 0,
                        acabado_plafon_rp : 0,
                        piso_rp : 0,
                        zoclo_rp : 0,
                        //////////////// Observacion //////////////
                        puerta_rp_obs : '',
                        chapa_rp_obs : '',
                        sello_marco_rp_obs : '',
                        cancel_rp_obs : '',
                        sello_cancel_rp_obs : '',
                        vidrio_cancel_rp_obs : '',
                        mosquitero_rp_obs : '',
                        balcon_rp_obs : '',
                        barandal_rp_obs : '',
                        acc_electricos_rp_obs : '',
                        interfon_rp_obs : '',
                        salida_alarma_rp_obs : '',
                        acabado_muro_rp_obs : '',
                        acabado_plafon_rp_obs : '',
                        piso_rp_obs : '',
                        zoclo_rp_obs : '',

                    ///////////// BAÑO RECAMARA PRINCIPAL ////////////////
                        puerta_brp : 0,
                        chapa_brp : 0,
                        sello_marco_brp : 0,
                        barra_lavabo_brp : 0,
                        lavabo_brp : 0,
                        monomando_brp : 0,
                        wc_brp : 0,
                        regadera_brp : 0,
                        manerales_brp : 0,
                        coladera_brp : 0,
                        acc_bano_brp : 0,
                        acc_electrico_brp : 0,
                        ventana_brp : 0,
                        sello_ventana_brp : 0,
                        vidrio_ventana_brp : 0,
                        mosquitero_brp : 0,
                        acabado_muro_brp : 0,
                        acabado_plafon_brp : 0,
                        sardinel_brp : 0,
                        piso_brp : 0,
                        zoclo_brp : 0,
                        /////////// Observacion ///////////////
                        puerta_brp_obs : '',
                        chapa_brp_obs : '',
                        sello_marco_brp_obs : '',
                        barra_lavabo_brp_obs : '',
                        lavabo_brp_obs : '',
                        monomando_brp_obs : '',
                        wc_brp_obs : '',
                        regadera_brp_obs : '',
                        manerales_brp_obs : '',
                        coladera_brp_obs : '',
                        acc_bano_brp_obs : '',
                        acc_electrico_brp_obs : '',
                        ventana_brp_obs : '',
                        sello_ventana_brp_obs : '',
                        vidrio_ventana_brp_obs : '',
                        mosquitero_brp_obs : '',
                        acabado_muro_brp_obs : '',
                        acabado_plafon_brp_obs : '',
                        sardinel_brp_obs : '',
                        piso_brp_obs : '',
                        zoclo_brp_obs : '',

                    //////////////// Vestidor //////////////////
                        acc_electrico_vest : 0,
                        acabado_muro_vest : 0,
                        acabado_plafon_vest : 0,
                        piso_vest : 0,
                        zoclo_vest : 0,
                        /////////// Observacion ////////////
                        acc_electrico_vest_obs : '',
                        acabado_muro_vest_obs : '',
                        acabado_plafon_vest_obs : '',
                        piso_vest_obs : '',
                        zoclo_vest_obs : '',
                    /////////////// RECAMARA 2 ///////////////
                        puerta_rec2 : 0,
                        chapa_rec2 : 0,
                        sello_marco_rec2 : 0,
                        cancel_rec2 : 0,
                        sello_cancel_rec2 : 0,
                        vidrio_cancel_rec2 : 0,
                        mosquitero_rec2 : 0,
                        acc_rec2 : 0,
                        salida_alarma_rec2 : 0,
                        acabado_muro_rec2 : 0,
                        acabado_plafon_rec2 : 0,
                        piso_rec2 : 0,
                        zoclo_rec2 : 0,
                        //////////////  Observacion  ////////////
                        puerta_rec2_obs : '',
                        chapa_rec2_obs : '',
                        sello_marco_rec2_obs : '',
                        cancel_rec2_obs : '',
                        sello_cancel_rec2_obs : '',
                        vidrio_cancel_rec2_obs : '',
                        mosquitero_rec2_obs : '',
                        acc_rec2_obs : '',
                        salida_alarma_rec2_obs : '',
                        acabado_muro_rec2_obs : '',
                        acabado_plafon_rec2_obs : '',
                        piso_rec2_obs : '',
                        zoclo_rec2_obs : '',

                    /////////////// RECAMARA 3 ///////////////
                        puerta_rec3 : 0,
                        chapa_rec3 : 0,
                        sello_marco_rec3 : 0,
                        cancel_rec3 : 0,
                        sello_cancel_rec3 : 0,
                        vidrio_cancel_rec3 : 0,
                        mosquitero_rec3 : 0,
                        acc_rec3 : 0,
                        salida_alarma_rec3 : 0,
                        acabado_muro_rec3 : 0,
                        acabado_plafon_rec3 : 0,
                        piso_rec3 : 0,
                        zoclo_rec3 : 0,
                        //////////////  Observacion  ////////////
                        puerta_rec3_obs : '',
                        chapa_rec3_obs : '',
                        sello_marco_rec3_obs : '',
                        cancel_rec3_obs : '',
                        sello_cancel_rec3_obs : '',
                        vidrio_cancel_rec3_obs : '',
                        mosquitero_rec3_obs : '',
                        acc_rec3_obs : '',
                        salida_alarma_rec3_obs : '',
                        acabado_muro_rec3_obs : '',
                        acabado_plafon_rec3_obs : '',
                        piso_rec3_obs : '',
                        zoclo_rec3_obs : '',
                    ///////////// AZOTEA ////////////////
                        pretiles_azotea : 0,
                        impermeabilizacion : 0,
                        domos_azotea : 0,
                        mufas_azotea : 0,
                        jarros_azotea : 0,
                        ventilas_azotea : 0,
                        base_tinaco_azotea : 0,
                        tinaco_azotea : 0,
                        calentador_solar_azotea : 0,
                        punta_gas_azotea : 0,
                        anclas_azotea : 0,
                        limpieza_azotea : 0,
                        ///////// Observacion ///////////
                        pretiles_azotea_obs : '',
                        impermeabilizacion_obs : '',
                        domos_azotea_obs : '',
                        mufas_azotea_obs : '',
                        jarros_azotea_obs : '',
                        ventilas_azotea_obs : '',
                        base_tinaco_azotea_obs : '',
                        tinaco_azotea_obs : '',
                        calentador_solar_azotea_obs : '',
                        punta_gas_azotea_obs : '',
                        anclas_azotea_obs : '',
                        limpieza_azotea_obs : '',
                    ///////////  GENERALES  //////////////
                        limpieza_interior : 0,
                        limpieza_exterior : 0,
                        limpieza_vidrios : 0,
                        limpieza_domos : 0,
                        plastico_muebles : 0,
                        candados : 0,
                        llaves : 0,
                        num_oficial : 0,
                        /////////// Observacion ///////////
                        limpieza_interior_obs : '',
                        limpieza_exterior_obs : '',
                        limpieza_vidrios_obs : '',
                        limpieza_domos_obs : '',
                        plastico_muebles_obs : '',
                        candados_obs : '',
                        llaves_obs : '',
                        num_oficial_obs : '',

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

                errorRevision : 0,
                errorMostrarMsjRevision : [],
               
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
            listarContratos(page, buscar, b_etapa, b_manzana, b_lote, criterio){
                let me = this;
                var url = '/postventa/indexSinRevision?page=' + page + '&buscar=' + buscar + '&b_etapa=' + b_etapa + '&b_manzana=' + b_manzana + '&b_lote=' + b_lote +  '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayContratos = respuesta.contratos.data;
                    me.pagination2 = respuesta.pagination;
                    
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

            realizarRevision(folio,diferencia){
                this.folio = folio;
                this.revision = 1;
                this.diferencia = diferencia;

                ///////// COCHERA ////////////
                    this.mona_cochera = 0;
                    this.centro_carga_cochera = 0;
                    this.cuadro_hidraulico_cochera = 0;
                    this.interfon_cochera = 0;
                    this.cisterna_cochera = 0;
                    this.bomba_cochera = 0;
                    this.tapa_cisterna_cochera = 0;
                    this.tapa_registro_cochera = 0;
                    this.acc_electrico_cochera = 0;
                    this.acabado_muros_cochera = 0;
                    this.acabado_plafon_cochera = 0;
                    this.piso_cochera = 0;
                    this.zoclo_cochera = 0;
                    //////// Observacion /////////
                    this.mona_cochera_obs ='';
                    this.centro_carga_cochera_obs ='';
                    this.cuadro_hidraulico_cochera_obs ='';
                    this.interfon_cochera_obs ='';
                    this.cisterna_cochera_obs ='';
                    this.bomba_cochera_obs ='';
                    this.tapa_cisterna_cochera_obs ='';
                    this.tapa_registro_cochera_obs ='';
                    this.acc_electrico_cochera_obs ='';
                    this.acabado_muros_cochera_obs ='';
                    this.acabado_plafon_cochera_obs ='';
                    this.piso_cochera_obs ='';
                    this.zoclo_cochera_obs ='';

                ///////// SALA COMEDOR /////////
                    this.puerta_pric_sala = 0;
                    this.chapa_sala = 0;
                    this.sello_marco_sala = 0;
                    this.ventana_sala = 0;
                    this.sello_ventana_sala = 0;
                    this.vidrio_ventana_sala = 0;
                    this.mosquitero_sala = 0;
                    this.cancel_sala = 0;
                    this.sello_cancel_sala = 0;
                    this.vidrio_cancel_sala = 0;
                    this.salida_alarma_sala = 0;
                    this.acc_electrico_sala = 0;
                    this.acabado_muros_sala = 0;
                    this.acabado_plafon_sala = 0;
                    this.piso_sala = 0;
                    this.zoclo_sala = 0;
                    ////// Observacion ///////
                    this.puerta_pric_sala_obs ='';
                    this.chapa_sala_obs ='';
                    this.sello_marco_sala_obs ='';
                    this.ventana_sala_obs ='';
                    this.sello_ventana_sala_obs ='';
                    this.vidrio_ventana_sala_obs ='';
                    this.mosquitero_sala_obs ='';
                    this.cancel_sala_obs ='';
                    this.sello_cancel_sala_obs ='';
                    this.vidrio_cancel_sala_obs ='';
                    this.salida_alarma_sala_obs ='';
                    this.acc_electrico_sala_obs ='';
                    this.acabado_muros_sala_obs ='';
                    this.acabado_plafon_sala_obs ='';
                    this.piso_sala_obs ='';
                    this.zoclo_sala_obs ='';
                
                ///////////////// COCINA //////////////
                    this.tarja_cocina = 0;
                    this.puerta_cocina = 0;
                    this.chapa_cocina = 0;
                    this.sello_pv_cocina = 0;
                    this.vidrio_pv_cocina = 0;
                    this.mosquitero_cocina = 0;
                    this.salida_alarma_cocina = 0;
                    this.interfon_cocina = 0;
                    this.acc_electrico_cocina = 0;
                    this.centro_carga_cocina = 0;
                    this.inst_gas_cocina = 0;
                    this.inst_refrigerador_cocina = 0;
                    this.barra_cocina = 0;
                    this.azulejo_cocina = 0;
                    this.acabado_muro_cocina = 0;
                    this.acabado_plafon_cocina = 0;
                    this.piso_cocina = 0;
                    this.zoclo_cocina = 0;
                    ///////// Observaciones ////////////
                    this.tarja_cocina_obs ='';
                    this.puerta_cocina_obs ='';
                    this.chapa_cocina_obs ='';
                    this.sello_pv_cocina_obs ='';
                    this.vidrio_pv_cocina_obs ='';
                    this.mosquitero_cocina_obs ='';
                    this.salida_alarma_cocina_obs ='';
                    this.interfon_cocina_obs ='';
                    this.acc_electrico_cocina_obs ='';
                    this.centro_carga_cocina_obs ='';
                    this.inst_gas_cocina_obs ='';
                    this.inst_refrigerador_cocina_obs ='';
                    this.barra_cocina_obs ='';
                    this.azulejo_cocina_obs ='';
                    this.acabado_muro_cocina_obs ='';
                    this.acabado_plafon_cocina_obs ='';
                    this.piso_cocina_obs ='';
                    this.zoclo_cocina_obs ='';
                //////////  MEDIO BAÑO  /////////////
                    this.puerta_mb = 0; 
                    this.chapa_mb = 0;
                    this.barra_lavabo_mb = 0;
                    this.lavabo_mb = 0;
                    this.monomando_mb = 0;
                    this.wc_mb = 0;
                    this.acc_bano_mb = 0;
                    this.acc_electrico_mb = 0;
                    this.ventana_mb = 0;
                    this.sello_ventana_mb = 0;
                    this.vidrio_mb = 0;
                    this.mosquitero_mb = 0;
                    this.acabado_muro_mb = 0;
                    this.acabado_plafon_mb = 0;
                    this.piso_mb = 0;
                    this.zoclo_mb = 0;
                    ////////// Observacion //////////
                    this.puerta_mb_obs =''; 
                    this.chapa_mb_obs ='';
                    this.barra_lavabo_mb_obs ='';
                    this.lavabo_mb_obs ='';
                    this.monomando_mb_obs ='';
                    this.wc_mb_obs ='';
                    this.acc_bano_mb_obs ='';
                    this.acc_electrico_mb_obs ='';
                    this.ventana_mb_obs ='';
                    this.sello_ventana_mb_obs ='';
                    this.vidrio_mb_obs ='';
                    this.mosquitero_mb_obs ='';
                    this.acabado_muro_mb_obs ='';
                    this.acabado_plafon_mb_obs ='';
                    this.piso_mb_obs ='';
                    this.zoclo_mb_obs ='';

                /////////////  PATIO  /////////////////
                    this.calentador_patio = 0; 
                    this.inst_gas_patio = 0; 
                    this.acc_electrico_patio = 0; 
                    this.lavadero_patio = 0; 
                    this.llaves_nariz_patio = 0; 
                    this.descarga_lavadora_patio = 0; 
                    this.coladera_patio = 0; 
                    this.tapa_registro_patio = 0; 
                    this.escalera_marina_patio = 0; 
                    this.techumbre_patio = 0; 
                    this.firme_cilindros_patio = 0; 
                    this.rodapie_patio = 0; 
                    this.acabado_muros_patio = 0; 
                    this.acabado_volado_patio = 0; 
                    this.piso_patio = 0; 
                    this.zoclo_patio = 0; 
                    ///////// Observacion //////////////
                    this.calentador_patio_obs =''; 
                    this.inst_gas_patio_obs =''; 
                    this.acc_electrico_patio_obs =''; 
                    this.lavadero_patio_obs =''; 
                    this.llaves_nariz_patio_obs =''; 
                    this.descarga_lavadora_patio_obs =''; 
                    this.coladera_patio_obs =''; 
                    this.tapa_registro_patio_obs =''; 
                    this.escalera_marina_patio_obs =''; 
                    this.techumbre_patio_obs =''; 
                    this.firme_cilindros_patio_obs =''; 
                    this.rodapie_patio_obs =''; 
                    this.acabado_muros_patio_obs =''; 
                    this.acabado_volado_patio_obs =''; 
                    this.piso_patio_obs =''; 
                    this.zoclo_patio_obs ='';
                
                /////////////// ESCALERA ////////////////
                    this.nicho_escalera = 0; 
                    this.escalones_escalera = 0;
                    this.piso_escalera = 0;
                    this.zoclo_escalera = 0;
                    this.barandal_escalera = 0;
                    this.pasamanos_escalera = 0;
                    this.sardinel_escalera = 0;
                    this.macetero_escalera = 0;
                    this.cajillos_escalera = 0;
                    this.acc_electricos_escalera = 0;
                    this.acabado_muro_escalera = 0;
                    this.acabado_plafon_escalera = 0;
                    ///////////// Observacion //////////////
                    this.nicho_escalera_obs =''; 
                    this.escalones_escalera_obs ='';
                    this.piso_escalera_obs ='';
                    this.zoclo_escalera_obs ='';
                    this.barandal_escalera_obs ='';
                    this.pasamanos_escalera_obs ='';
                    this.sardinel_escalera_obs ='';
                    this.macetero_escalera_obs ='';
                    this.cajillos_escalera_obs ='';
                    this.acc_electricos_escalera_obs ='';
                    this.acabado_muro_escalera_obs ='';
                    this.acabado_plafon_escalera_obs ='';
                ///////////////  BAÑO COMUN  //////////////////
                    this.puerta_bc = 0;
                    this.chapa_bc = 0;
                    this.sello_marco_bc = 0;
                    this.barra_lavabo_bc = 0;
                    this.lavabo_bc = 0;
                    this.monomando_bc = 0;
                    this.wc_bc = 0;
                    this.regadera_bc = 0;
                    this.manerales_bc = 0;
                    this.coladera_bc = 0;
                    this.acc_bano_bc = 0;
                    this.acc_electricos_bc = 0;
                    this.ventana_bc = 0;
                    this.sello_ventana_bc = 0;
                    this.vidrio_ventana_bc = 0;
                    this.mosquitero_bc = 0;
                    this.acabado_muro_bc = 0;
                    this.acabado_plafon_bc = 0;
                    this.sardinel_bc = 0;
                    this.piso_bc = 0;
                    this.zoclo_bc = 0;
                    ///////////// Observacion ////////////////
                    this.puerta_bc_obs ='';
                    this.chapa_bc_obs ='';
                    this.sello_marco_bc_obs ='';
                    this.barra_lavabo_bc_obs ='';
                    this.lavabo_bc_obs ='';
                    this.monomando_bc_obs ='';
                    this.wc_bc_obs ='';
                    this.regadera_bc_obs ='';
                    this.manerales_bc_obs ='';
                    this.coladera_bc_obs ='';
                    this.acc_bano_bc_obs ='';
                    this.acc_electricos_bc_obs ='';
                    this.ventana_bc_obs ='';
                    this.sello_ventana_bc_obs ='';
                    this.vidrio_ventana_bc_obs ='';
                    this.mosquitero_bc_obs ='';
                    this.acabado_muro_bc_obs ='';
                    this.acabado_plafon_bc_obs ='';
                    this.sardinel_bc_obs ='';
                    this.piso_bc_obs ='';
                    this.zoclo_bc_obs ='';
                
                ///////////// ESTANCIA //////////////
                    this.ventana_estancia = 0;
                    this.sello_ventana_estancia = 0;
                    this.vidrio_ventana_estancia = 0;
                    this.mosquitero_estancia = 0;
                    this.interfon_estancia = 0;
                    this.acc_electricos_estancia = 0;
                    this.acabado_muro_estancia = 0;
                    this.acabado_plafon_estancia = 0;
                    this.piso_estancia = 0;
                    this.zoclo_estancia = 0;
                    //////////// Observacion /////////////
                    this.ventana_estancia_obs ='';
                    this.sello_ventana_estancia_obs ='';
                    this.vidrio_ventana_estancia_obs ='';
                    this.mosquitero_estancia_obs ='';
                    this.interfon_estancia_obs ='';
                    this.acc_electricos_estancia_obs ='';
                    this.acabado_muro_estancia_obs ='';
                    this.acabado_plafon_estancia_obs ='';
                    this.piso_estancia_obs ='';
                    this.zoclo_estancia_obs ='';

                //////////// Recamara Principal //////////////
                    this.puerta_rp = 0;
                    this.chapa_rp = 0;
                    this.sello_marco_rp = 0;
                    this.cancel_rp = 0;
                    this.sello_cancel_rp = 0;
                    this.vidrio_cancel_rp = 0;
                    this.mosquitero_rp = 0;
                    this.balcon_rp = 0;
                    this.barandal_rp = 0;
                    this.acc_electricos_rp = 0;
                    this.interfon_rp = 0;
                    this.salida_alarma_rp = 0;
                    this.acabado_muro_rp = 0;
                    this.acabado_plafon_rp = 0;
                    this.piso_rp = 0;
                    this.zoclo_rp = 0;
                    //////////////// Observacion //////////////
                    this.puerta_rp_obs ='';
                    this.chapa_rp_obs ='';
                    this.sello_marco_rp_obs ='';
                    this.cancel_rp_obs ='';
                    this.sello_cancel_rp_obs ='';
                    this.vidrio_cancel_rp_obs ='';
                    this.mosquitero_rp_obs ='';
                    this.balcon_rp_obs ='';
                    this.barandal_rp_obs ='';
                    this.acc_electricos_rp_obs ='';
                    this.interfon_rp_obs ='';
                    this.salida_alarma_rp_obs ='';
                    this.acabado_muro_rp_obs ='';
                    this.acabado_plafon_rp_obs ='';
                    this.piso_rp_obs ='';
                    this.zoclo_rp_obs ='';

                ///////////// BAÑO RECAMARA PRINCIPAL ////////////////
                    this.puerta_brp = 0;
                    this.chapa_brp = 0;
                    this.sello_marco_brp = 0;
                    this.barra_lavabo_brp = 0;
                    this.lavabo_brp = 0;
                    this.monomando_brp = 0;
                    this.wc_brp = 0;
                    this.regadera_brp = 0;
                    this.manerales_brp = 0;
                    this.coladera_brp = 0;
                    this.acc_bano_brp = 0;
                    this.acc_electrico_brp = 0;
                    this.ventana_brp = 0;
                    this.sello_ventana_brp = 0;
                    this.vidrio_ventana_brp = 0;
                    this.mosquitero_brp = 0;
                    this.acabado_muro_brp = 0;
                    this.acabado_plafon_brp = 0;
                    this.sardinel_brp = 0;
                    this.piso_brp = 0;
                    this.zoclo_brp = 0;
                    /////////// Observacion ///////////////
                    this.puerta_brp_obs ='';
                    this.chapa_brp_obs ='';
                    this.sello_marco_brp_obs ='';
                    this.barra_lavabo_brp_obs ='';
                    this.lavabo_brp_obs ='';
                    this.monomando_brp_obs ='';
                    this.wc_brp_obs ='';
                    this.regadera_brp_obs ='';
                    this.manerales_brp_obs ='';
                    this.coladera_brp_obs ='';
                    this.acc_bano_brp_obs ='';
                    this.acc_electrico_brp_obs ='';
                    this.ventana_brp_obs ='';
                    this.sello_ventana_brp_obs ='';
                    this.vidrio_ventana_brp_obs ='';
                    this.mosquitero_brp_obs ='';
                    this.acabado_muro_brp_obs ='';
                    this.acabado_plafon_brp_obs ='';
                    this.sardinel_brp_obs ='';
                    this.piso_brp_obs ='';
                    this.zoclo_brp_obs ='';

                //////////////// Vestidor //////////////////
                    this.acc_electrico_vest = 0;
                    this.acabado_muro_vest = 0;
                    this.acabado_plafon_vest = 0;
                    this.piso_vest = 0;
                    this.zoclo_vest = 0;
                    /////////// Observacion ////////////
                    this.acc_electrico_vest_obs ='';
                    this.acabado_muro_vest_obs ='';
                    this.acabado_plafon_vest_obs ='';
                    this.piso_vest_obs ='';
                    this.zoclo_vest_obs ='';
                /////////////// RECAMARA 2 ///////////////
                    this.puerta_rec2 = 0;
                    this.chapa_rec2 = 0;
                    this.sello_marco_rec2 = 0;
                    this.cancel_rec2 = 0;
                    this.sello_cancel_rec2 = 0;
                    this.vidrio_cancel_rec2 = 0;
                    this.mosquitero_rec2 = 0;
                    this.acc_rec2 = 0;
                    this.salida_alarma_rec2 = 0;
                    this.acabado_muro_rec2 = 0;
                    this.acabado_plafon_rec2 = 0;
                    this.piso_rec2 = 0;
                    this.zoclo_rec2 = 0;
                    //////////////  Observacion  ////////////
                    this.puerta_rec2_obs ='';
                    this.chapa_rec2_obs ='';
                    this.sello_marco_rec2_obs ='';
                    this.cancel_rec2_obs ='';
                    this.sello_cancel_rec2_obs ='';
                    this.vidrio_cancel_rec2_obs ='';
                    this.mosquitero_rec2_obs ='';
                    this.acc_rec2_obs ='';
                    this.salida_alarma_rec2_obs ='';
                    this.acabado_muro_rec2_obs ='';
                    this.acabado_plafon_rec2_obs ='';
                    this.piso_rec2_obs ='';
                    this.zoclo_rec2_obs ='';

                /////////////// RECAMARA 3 ///////////////
                    this.puerta_rec3 = 0;
                    this.chapa_rec3 = 0;
                    this.sello_marco_rec3 = 0;
                    this.cancel_rec3 = 0;
                    this.sello_cancel_rec3 = 0;
                    this.vidrio_cancel_rec3 = 0;
                    this.mosquitero_rec3 = 0;
                    this.acc_rec3 = 0;
                    this.salida_alarma_rec3 = 0;
                    this.acabado_muro_rec3 = 0;
                    this.acabado_plafon_rec3 = 0;
                    this.piso_rec3 = 0;
                    this.zoclo_rec3 = 0;
                    //////////////  Observacion  ////////////
                    this.puerta_rec3_obs ='';
                    this.chapa_rec3_obs ='';
                    this.sello_marco_rec3_obs ='';
                    this.cancel_rec3_obs ='';
                    this.sello_cancel_rec3_obs ='';
                    this.vidrio_cancel_rec3_obs ='';
                    this.mosquitero_rec3_obs ='';
                    this.acc_rec3_obs ='';
                    this.salida_alarma_rec3_obs ='';
                    this.acabado_muro_rec3_obs ='';
                    this.acabado_plafon_rec3_obs ='';
                    this.piso_rec3_obs ='';
                    this.zoclo_rec3_obs ='';
                ///////////// AZOTEA ////////////////
                    this.pretiles_azotea = 0;
                    this.impermeabilizacion = 0;
                    this.domos_azotea = 0;
                    this.mufas_azotea = 0;
                    this.jarros_azotea = 0;
                    this.ventilas_azotea = 0;
                    this.base_tinaco_azotea = 0;
                    this.tinaco_azotea = 0;
                    this.calentador_solar_azotea = 0;
                    this.punta_gas_azotea = 0;
                    this.anclas_azotea = 0;
                    this.limpieza_azotea = 0;
                    ///////// Observacion ///////////
                    this.pretiles_azotea_obs ='';
                    this.impermeabilizacion_obs ='';
                    this.domos_azotea_obs ='';
                    this.mufas_azotea_obs ='';
                    this.jarros_azotea_obs ='';
                    this.ventilas_azotea_obs ='';
                    this.base_tinaco_azotea_obs ='';
                    this.tinaco_azotea_obs ='';
                    this.calentador_solar_azotea_obs ='';
                    this.punta_gas_azotea_obs ='';
                    this.anclas_azotea_obs ='';
                    this.limpieza_azotea_obs ='';
                ///////////  GENERALES  //////////////
                    this.limpieza_interior = 0;
                    this.limpieza_exterior = 0;
                    this.limpieza_vidrios = 0;
                    this.limpieza_domos = 0;
                    this.plastico_muebles = 0;
                    this.candados = 0;
                    this.llaves = 0;
                    this.num_oficial = 0;
                    /////////// Observacion ///////////
                    this.limpieza_interior_obs ='';
                    this.limpieza_exterior_obs ='';
                    this.limpieza_vidrios_obs ='';
                    this.limpieza_domos_obs ='';
                    this.plastico_muebles_obs ='';
                    this.candados_obs ='';
                    this.llaves_obs ='';
                    this.num_oficial_obs ='';

            },

            cerrarRevision(){
                this.folio = '';
                this.revision = 0;
                this.diferencia = 0;
                this.errorRevision = 0;
                this.errorMostrarMsjRevision = [];

                ///////// COCHERA ////////////
                    this.mona_cochera = 0;
                    this.centro_carga_cochera = 0;
                    this.cuadro_hidraulico_cochera = 0;
                    this.interfon_cochera = 0;
                    this.cisterna_cochera = 0;
                    this.bomba_cochera = 0;
                    this.tapa_cisterna_cochera = 0;
                    this.tapa_registro_cochera = 0;
                    this.acc_electrico_cochera = 0;
                    this.acabado_muros_cochera = 0;
                    this.acabado_plafon_cochera = 0;
                    this.piso_cochera = 0;
                    this.zoclo_cochera = 0;
                    //////// Observacion /////////
                    this.mona_cochera_obs ='';
                    this.centro_carga_cochera_obs ='';
                    this.cuadro_hidraulico_cochera_obs ='';
                    this.interfon_cochera_obs ='';
                    this.cisterna_cochera_obs ='';
                    this.bomba_cochera_obs ='';
                    this.tapa_cisterna_cochera_obs ='';
                    this.tapa_registro_cochera_obs ='';
                    this.acc_electrico_cochera_obs ='';
                    this.acabado_muros_cochera_obs ='';
                    this.acabado_plafon_cochera_obs ='';
                    this.piso_cochera_obs ='';
                    this.zoclo_cochera_obs ='';

                ///////// SALA COMEDOR /////////
                    this.puerta_pric_sala = 0;
                    this.chapa_sala = 0;
                    this.sello_marco_sala = 0;
                    this.ventana_sala = 0;
                    this.sello_ventana_sala = 0;
                    this.vidrio_ventana_sala = 0;
                    this.mosquitero_sala = 0;
                    this.cancel_sala = 0;
                    this.sello_cancel_sala = 0;
                    this.vidrio_cancel_sala = 0;
                    this.salida_alarma_sala = 0;
                    this.acc_electrico_sala = 0;
                    this.acabado_muros_sala = 0;
                    this.acabado_plafon_sala = 0;
                    this.piso_sala = 0;
                    this.zoclo_sala = 0;
                    ////// Observacion ///////
                    this.puerta_pric_sala_obs ='';
                    this.chapa_sala_obs ='';
                    this.sello_marco_sala_obs ='';
                    this.ventana_sala_obs ='';
                    this.sello_ventana_sala_obs ='';
                    this.vidrio_ventana_sala_obs ='';
                    this.mosquitero_sala_obs ='';
                    this.cancel_sala_obs ='';
                    this.sello_cancel_sala_obs ='';
                    this.vidrio_cancel_sala_obs ='';
                    this.salida_alarma_sala_obs ='';
                    this.acc_electrico_sala_obs ='';
                    this.acabado_muros_sala_obs ='';
                    this.acabado_plafon_sala_obs ='';
                    this.piso_sala_obs ='';
                    this.zoclo_sala_obs ='';
                
                ///////////////// COCINA //////////////
                    this.tarja_cocina = 0;
                    this.puerta_cocina = 0;
                    this.chapa_cocina = 0;
                    this.sello_pv_cocina = 0;
                    this.vidrio_pv_cocina = 0;
                    this.mosquitero_cocina = 0;
                    this.salida_alarma_cocina = 0;
                    this.interfon_cocina = 0;
                    this.acc_electrico_cocina = 0;
                    this.centro_carga_cocina = 0;
                    this.inst_gas_cocina = 0;
                    this.inst_refrigerador_cocina = 0;
                    this.barra_cocina = 0;
                    this.azulejo_cocina = 0;
                    this.acabado_muro_cocina = 0;
                    this.acabado_plafon_cocina = 0;
                    this.piso_cocina = 0;
                    this.zoclo_cocina = 0;
                    ///////// Observaciones ////////////
                    this.tarja_cocina_obs ='';
                    this.puerta_cocina_obs ='';
                    this.chapa_cocina_obs ='';
                    this.sello_pv_cocina_obs ='';
                    this.vidrio_pv_cocina_obs ='';
                    this.mosquitero_cocina_obs ='';
                    this.salida_alarma_cocina_obs ='';
                    this.interfon_cocina_obs ='';
                    this.acc_electrico_cocina_obs ='';
                    this.centro_carga_cocina_obs ='';
                    this.inst_gas_cocina_obs ='';
                    this.inst_refrigerador_cocina_obs ='';
                    this.barra_cocina_obs ='';
                    this.azulejo_cocina_obs ='';
                    this.acabado_muro_cocina_obs ='';
                    this.acabado_plafon_cocina_obs ='';
                    this.piso_cocina_obs ='';
                    this.zoclo_cocina_obs ='';
                //////////  MEDIO BAÑO  /////////////
                    this.puerta_mb = 0; 
                    this.chapa_mb = 0;
                    this.barra_lavabo_mb = 0;
                    this.lavabo_mb = 0;
                    this.monomando_mb = 0;
                    this.wc_mb = 0;
                    this.acc_bano_mb = 0;
                    this.acc_electrico_mb = 0;
                    this.ventana_mb = 0;
                    this.sello_ventana_mb = 0;
                    this.vidrio_mb = 0;
                    this.mosquitero_mb = 0;
                    this.acabado_muro_mb = 0;
                    this.acabado_plafon_mb = 0;
                    this.piso_mb = 0;
                    this.zoclo_mb = 0;
                    ////////// Observacion //////////
                    this.puerta_mb_obs =''; 
                    this.chapa_mb_obs ='';
                    this.barra_lavabo_mb_obs ='';
                    this.lavabo_mb_obs ='';
                    this.monomando_mb_obs ='';
                    this.wc_mb_obs ='';
                    this.acc_bano_mb_obs ='';
                    this.acc_electrico_mb_obs ='';
                    this.ventana_mb_obs ='';
                    this.sello_ventana_mb_obs ='';
                    this.vidrio_mb_obs ='';
                    this.mosquitero_mb_obs ='';
                    this.acabado_muro_mb_obs ='';
                    this.acabado_plafon_mb_obs ='';
                    this.piso_mb_obs ='';
                    this.zoclo_mb_obs ='';

                /////////////  PATIO  /////////////////
                    this.calentador_patio = 0; 
                    this.inst_gas_patio = 0; 
                    this.acc_electrico_patio = 0; 
                    this.lavadero_patio = 0; 
                    this.llaves_nariz_patio = 0; 
                    this.descarga_lavadora_patio = 0; 
                    this.coladera_patio = 0; 
                    this.tapa_registro_patio = 0; 
                    this.escalera_marina_patio = 0; 
                    this.techumbre_patio = 0; 
                    this.firme_cilindros_patio = 0; 
                    this.rodapie_patio = 0; 
                    this.acabado_muros_patio = 0; 
                    this.acabado_volado_patio = 0; 
                    this.piso_patio = 0; 
                    this.zoclo_patio = 0; 
                    ///////// Observacion //////////////
                    this.calentador_patio_obs =''; 
                    this.inst_gas_patio_obs =''; 
                    this.acc_electrico_patio_obs =''; 
                    this.lavadero_patio_obs =''; 
                    this.llaves_nariz_patio_obs =''; 
                    this.descarga_lavadora_patio_obs =''; 
                    this.coladera_patio_obs =''; 
                    this.tapa_registro_patio_obs =''; 
                    this.escalera_marina_patio_obs =''; 
                    this.techumbre_patio_obs =''; 
                    this.firme_cilindros_patio_obs =''; 
                    this.rodapie_patio_obs =''; 
                    this.acabado_muros_patio_obs =''; 
                    this.acabado_volado_patio_obs =''; 
                    this.piso_patio_obs =''; 
                    this.zoclo_patio_obs ='';
                
                /////////////// ESCALERA ////////////////
                    this.nicho_escalera = 0; 
                    this.escalones_escalera = 0;
                    this.piso_escalera = 0;
                    this.zoclo_escalera = 0;
                    this.barandal_escalera = 0;
                    this.pasamanos_escalera = 0;
                    this.sardinel_escalera = 0;
                    this.macetero_escalera = 0;
                    this.cajillos_escalera = 0;
                    this.acc_electricos_escalera = 0;
                    this.acabado_muro_escalera = 0;
                    this.acabado_plafon_escalera = 0;
                    ///////////// Observacion //////////////
                    this.nicho_escalera_obs =''; 
                    this.escalones_escalera_obs ='';
                    this.piso_escalera_obs ='';
                    this.zoclo_escalera_obs ='';
                    this.barandal_escalera_obs ='';
                    this.pasamanos_escalera_obs ='';
                    this.sardinel_escalera_obs ='';
                    this.macetero_escalera_obs ='';
                    this.cajillos_escalera_obs ='';
                    this.acc_electricos_escalera_obs ='';
                    this.acabado_muro_escalera_obs ='';
                    this.acabado_plafon_escalera_obs ='';
                ///////////////  BAÑO COMUN  //////////////////
                    this.puerta_bc = 0;
                    this.chapa_bc = 0;
                    this.sello_marco_bc = 0;
                    this.barra_lavabo_bc = 0;
                    this.lavabo_bc = 0;
                    this.monomando_bc = 0;
                    this.wc_bc = 0;
                    this.regadera_bc = 0;
                    this.manerales_bc = 0;
                    this.coladera_bc = 0;
                    this.acc_bano_bc = 0;
                    this.acc_electricos_bc = 0;
                    this.ventana_bc = 0;
                    this.sello_ventana_bc = 0;
                    this.vidrio_ventana_bc = 0;
                    this.mosquitero_bc = 0;
                    this.acabado_muro_bc = 0;
                    this.acabado_plafon_bc = 0;
                    this.sardinel_bc = 0;
                    this.piso_bc = 0;
                    this.zoclo_bc = 0;
                    ///////////// Observacion ////////////////
                    this.puerta_bc_obs ='';
                    this.chapa_bc_obs ='';
                    this.sello_marco_bc_obs ='';
                    this.barra_lavabo_bc_obs ='';
                    this.lavabo_bc_obs ='';
                    this.monomando_bc_obs ='';
                    this.wc_bc_obs ='';
                    this.regadera_bc_obs ='';
                    this.manerales_bc_obs ='';
                    this.coladera_bc_obs ='';
                    this.acc_bano_bc_obs ='';
                    this.acc_electricos_bc_obs ='';
                    this.ventana_bc_obs ='';
                    this.sello_ventana_bc_obs ='';
                    this.vidrio_ventana_bc_obs ='';
                    this.mosquitero_bc_obs ='';
                    this.acabado_muro_bc_obs ='';
                    this.acabado_plafon_bc_obs ='';
                    this.sardinel_bc_obs ='';
                    this.piso_bc_obs ='';
                    this.zoclo_bc_obs ='';
                
                ///////////// ESTANCIA //////////////
                    this.ventana_estancia = 0;
                    this.sello_ventana_estancia = 0;
                    this.vidrio_ventana_estancia = 0;
                    this.mosquitero_estancia = 0;
                    this.interfon_estancia = 0;
                    this.acc_electricos_estancia = 0;
                    this.acabado_muro_estancia = 0;
                    this.acabado_plafon_estancia = 0;
                    this.piso_estancia = 0;
                    this.zoclo_estancia = 0;
                    //////////// Observacion /////////////
                    this.ventana_estancia_obs ='';
                    this.sello_ventana_estancia_obs ='';
                    this.vidrio_ventana_estancia_obs ='';
                    this.mosquitero_estancia_obs ='';
                    this.interfon_estancia_obs ='';
                    this.acc_electricos_estancia_obs ='';
                    this.acabado_muro_estancia_obs ='';
                    this.acabado_plafon_estancia_obs ='';
                    this.piso_estancia_obs ='';
                    this.zoclo_estancia_obs ='';

                //////////// Recamara Principal //////////////
                    this.puerta_rp = 0;
                    this.chapa_rp = 0;
                    this.sello_marco_rp = 0;
                    this.cancel_rp = 0;
                    this.sello_cancel_rp = 0;
                    this.vidrio_cancel_rp = 0;
                    this.mosquitero_rp = 0;
                    this.balcon_rp = 0;
                    this.barandal_rp = 0;
                    this.acc_electricos_rp = 0;
                    this.interfon_rp = 0;
                    this.salida_alarma_rp = 0;
                    this.acabado_muro_rp = 0;
                    this.acabado_plafon_rp = 0;
                    this.piso_rp = 0;
                    this.zoclo_rp = 0;
                    //////////////// Observacion //////////////
                    this.puerta_rp_obs ='';
                    this.chapa_rp_obs ='';
                    this.sello_marco_rp_obs ='';
                    this.cancel_rp_obs ='';
                    this.sello_cancel_rp_obs ='';
                    this.vidrio_cancel_rp_obs ='';
                    this.mosquitero_rp_obs ='';
                    this.balcon_rp_obs ='';
                    this.barandal_rp_obs ='';
                    this.acc_electricos_rp_obs ='';
                    this.interfon_rp_obs ='';
                    this.salida_alarma_rp_obs ='';
                    this.acabado_muro_rp_obs ='';
                    this.acabado_plafon_rp_obs ='';
                    this.piso_rp_obs ='';
                    this.zoclo_rp_obs ='';

                ///////////// BAÑO RECAMARA PRINCIPAL ////////////////
                    this.puerta_brp = 0;
                    this.chapa_brp = 0;
                    this.sello_marco_brp = 0;
                    this.barra_lavabo_brp = 0;
                    this.lavabo_brp = 0;
                    this.monomando_brp = 0;
                    this.wc_brp = 0;
                    this.regadera_brp = 0;
                    this.manerales_brp = 0;
                    this.coladera_brp = 0;
                    this.acc_bano_brp = 0;
                    this.acc_electrico_brp = 0;
                    this.ventana_brp = 0;
                    this.sello_ventana_brp = 0;
                    this.vidrio_ventana_brp = 0;
                    this.mosquitero_brp = 0;
                    this.acabado_muro_brp = 0;
                    this.acabado_plafon_brp = 0;
                    this.sardinel_brp = 0;
                    this.piso_brp = 0;
                    this.zoclo_brp = 0;
                    /////////// Observacion ///////////////
                    this.puerta_brp_obs ='';
                    this.chapa_brp_obs ='';
                    this.sello_marco_brp_obs ='';
                    this.barra_lavabo_brp_obs ='';
                    this.lavabo_brp_obs ='';
                    this.monomando_brp_obs ='';
                    this.wc_brp_obs ='';
                    this.regadera_brp_obs ='';
                    this.manerales_brp_obs ='';
                    this.coladera_brp_obs ='';
                    this.acc_bano_brp_obs ='';
                    this.acc_electrico_brp_obs ='';
                    this.ventana_brp_obs ='';
                    this.sello_ventana_brp_obs ='';
                    this.vidrio_ventana_brp_obs ='';
                    this.mosquitero_brp_obs ='';
                    this.acabado_muro_brp_obs ='';
                    this.acabado_plafon_brp_obs ='';
                    this.sardinel_brp_obs ='';
                    this.piso_brp_obs ='';
                    this.zoclo_brp_obs ='';

                //////////////// Vestidor //////////////////
                    this.acc_electrico_vest = 0;
                    this.acabado_muro_vest = 0;
                    this.acabado_plafon_vest = 0;
                    this.piso_vest = 0;
                    this.zoclo_vest = 0;
                    /////////// Observacion ////////////
                    this.acc_electrico_vest_obs ='';
                    this.acabado_muro_vest_obs ='';
                    this.acabado_plafon_vest_obs ='';
                    this.piso_vest_obs ='';
                    this.zoclo_vest_obs ='';
                /////////////// RECAMARA 2 ///////////////
                    this.puerta_rec2 = 0;
                    this.chapa_rec2 = 0;
                    this.sello_marco_rec2 = 0;
                    this.cancel_rec2 = 0;
                    this.sello_cancel_rec2 = 0;
                    this.vidrio_cancel_rec2 = 0;
                    this.mosquitero_rec2 = 0;
                    this.acc_rec2 = 0;
                    this.salida_alarma_rec2 = 0;
                    this.acabado_muro_rec2 = 0;
                    this.acabado_plafon_rec2 = 0;
                    this.piso_rec2 = 0;
                    this.zoclo_rec2 = 0;
                    //////////////  Observacion  ////////////
                    this.puerta_rec2_obs ='';
                    this.chapa_rec2_obs ='';
                    this.sello_marco_rec2_obs ='';
                    this.cancel_rec2_obs ='';
                    this.sello_cancel_rec2_obs ='';
                    this.vidrio_cancel_rec2_obs ='';
                    this.mosquitero_rec2_obs ='';
                    this.acc_rec2_obs ='';
                    this.salida_alarma_rec2_obs ='';
                    this.acabado_muro_rec2_obs ='';
                    this.acabado_plafon_rec2_obs ='';
                    this.piso_rec2_obs ='';
                    this.zoclo_rec2_obs ='';

                /////////////// RECAMARA 3 ///////////////
                    this.puerta_rec3 = 0;
                    this.chapa_rec3 = 0;
                    this.sello_marco_rec3 = 0;
                    this.cancel_rec3 = 0;
                    this.sello_cancel_rec3 = 0;
                    this.vidrio_cancel_rec3 = 0;
                    this.mosquitero_rec3 = 0;
                    this.acc_rec3 = 0;
                    this.salida_alarma_rec3 = 0;
                    this.acabado_muro_rec3 = 0;
                    this.acabado_plafon_rec3 = 0;
                    this.piso_rec3 = 0;
                    this.zoclo_rec3 = 0;
                    //////////////  Observacion  ////////////
                    this.puerta_rec3_obs ='';
                    this.chapa_rec3_obs ='';
                    this.sello_marco_rec3_obs ='';
                    this.cancel_rec3_obs ='';
                    this.sello_cancel_rec3_obs ='';
                    this.vidrio_cancel_rec3_obs ='';
                    this.mosquitero_rec3_obs ='';
                    this.acc_rec3_obs ='';
                    this.salida_alarma_rec3_obs ='';
                    this.acabado_muro_rec3_obs ='';
                    this.acabado_plafon_rec3_obs ='';
                    this.piso_rec3_obs ='';
                    this.zoclo_rec3_obs ='';
                ///////////// AZOTEA ////////////////
                    this.pretiles_azotea = 0;
                    this.impermeabilizacion = 0;
                    this.domos_azotea = 0;
                    this.mufas_azotea = 0;
                    this.jarros_azotea = 0;
                    this.ventilas_azotea = 0;
                    this.base_tinaco_azotea = 0;
                    this.tinaco_azotea = 0;
                    this.calentador_solar_azotea = 0;
                    this.punta_gas_azotea = 0;
                    this.anclas_azotea = 0;
                    this.limpieza_azotea = 0;
                    ///////// Observacion ///////////
                    this.pretiles_azotea_obs ='';
                    this.impermeabilizacion_obs ='';
                    this.domos_azotea_obs ='';
                    this.mufas_azotea_obs ='';
                    this.jarros_azotea_obs ='';
                    this.ventilas_azotea_obs ='';
                    this.base_tinaco_azotea_obs ='';
                    this.tinaco_azotea_obs ='';
                    this.calentador_solar_azotea_obs ='';
                    this.punta_gas_azotea_obs ='';
                    this.anclas_azotea_obs ='';
                    this.limpieza_azotea_obs ='';
                ///////////  GENERALES  //////////////
                    this.limpieza_interior = 0;
                    this.limpieza_exterior = 0;
                    this.limpieza_vidrios = 0;
                    this.limpieza_domos = 0;
                    this.plastico_muebles = 0;
                    this.candados = 0;
                    this.llaves = 0;
                    this.num_oficial = 0;
                    /////////// Observacion ///////////
                    this.limpieza_interior_obs ='';
                    this.limpieza_exterior_obs ='';
                    this.limpieza_vidrios_obs ='';
                    this.limpieza_domos_obs ='';
                    this.plastico_muebles_obs ='';
                    this.candados_obs ='';
                    this.llaves_obs ='';
                    this.num_oficial_obs ='';

                    this.listarContratos(this.pagination2.current_page,this.buscar2,this.b_etapa2,this.b_manzana2,this.b_lote2,this.criterio2);

            },

            terminarRevision(){
                if(this.validarRevision()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                swal({
                title: '¿Desea terminar la revision?',
                text: "Verificar que todo fue revisado",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si, finalizar!'
                }).then((result) => {
                if (result.value) {
                    let me = this;

                //Con axios se llama el metodo update de LoteController
                    axios.post('/postventa/finalizarRevisionPrevia',{
                        'observacion' : this.observacion,
                        'folio' : this.folio,
                        'diferencia' : this.diferencia,

                        ///////// COCHERA ////////////
                            'mona_cochera' : this.mona_cochera,
                            'centro_carga_cochera' : this.centro_carga_cochera,
                            'cuadro_hidraulico_cochera' : this.cuadro_hidraulico_cochera,
                            'interfon_cochera' : this.interfon_cochera,
                            'cisterna_cochera' : this.cisterna_cochera,
                            'bomba_cochera' : this.bomba_cochera,
                            'tapa_cisterna_cochera' : this.tapa_cisterna_cochera,
                            'tapa_registro_cochera' : this.tapa_registro_cochera,
                            'acc_electrico_cochera' : this.acc_electrico_cochera,
                            'acabado_muros_cochera' : this.acabado_muros_cochera,
                            'acabado_plafon_cochera' : this.acabado_plafon_cochera,
                            'piso_cochera' : this.piso_cochera,
                            'zoclo_cochera' : this.zoclo_cochera,
                            //////// Observacion /////////
                            'mona_cochera_obs' : this.mona_cochera_obs,
                            'centro_carga_cochera_obs' : this.centro_carga_cochera_obs,
                            'cuadro_hidraulico_cochera_obs' : this.cuadro_hidraulico_cochera_obs,
                            'interfon_cochera_obs' : this.interfon_cochera_obs,
                            'cisterna_cochera_obs' : this.cisterna_cochera_obs,
                            'bomba_cochera_obs' : this.bomba_cochera_obs,
                            'tapa_cisterna_cochera_obs' : this.tapa_cisterna_cochera_obs,
                            'tapa_registro_cochera_obs' : this.tapa_registro_cochera_obs,
                            'acc_electrico_cochera_obs' : this.acc_electrico_cochera_obs,
                            'acabado_muros_cochera_obs' : this.acabado_muros_cochera_obs,
                            'acabado_plafon_cochera_obs' : this.acabado_plafon_cochera_obs,
                            'piso_cochera_obs' : this.piso_cochera_obs,
                            'zoclo_cochera_obs' : this.zoclo_cochera_obs,

                        ///////// SALA COMEDOR /////////
                            'puerta_pric_sala' : this.puerta_pric_sala,
                            'chapa_sala' : this.chapa_sala,
                            'sello_marco_sala' : this.sello_marco_sala,
                            'ventana_sala' : this.ventana_sala,
                            'sello_ventana_sala' : this.sello_ventana_sala,
                            'vidrio_ventana_sala' : this.vidrio_ventana_sala,
                            'mosquitero_sala' : this.mosquitero_sala,
                            'cancel_sala' : this.cancel_sala,
                            'sello_cancel_sala' : this.sello_cancel_sala,
                            'vidrio_cancel_sala' : this.vidrio_cancel_sala,
                            'salida_alarma_sala' : this.salida_alarma_sala,
                            'acc_electrico_sala' : this.acc_electrico_sala,
                            'acabado_muros_sala' : this.acabado_muros_sala,
                            'acabado_plafon_sala' : this.acabado_plafon_sala,
                            'piso_sala' : this.piso_sala,
                            'zoclo_sala' : this.zoclo_sala,
                            ////// Observacion ///////
                            'puerta_pric_sala_obs' : this.puerta_pric_sala_obs,
                            'chapa_sala_obs' : this.chapa_sala_obs,
                            'sello_marco_sala_obs' : this.sello_marco_sala_obs,
                            'ventana_sala_obs' : this.ventana_sala_obs,
                            'sello_ventana_sala_obs' : this.sello_ventana_sala_obs,
                            'vidrio_ventana_sala_obs' : this.vidrio_ventana_sala_obs,
                            'mosquitero_sala_obs' : this.mosquitero_sala_obs,
                            'cancel_sala_obs' : this.cancel_sala_obs,
                            'sello_cancel_sala_obs' : this.sello_cancel_sala_obs,
                            'vidrio_cancel_sala_obs' : this.vidrio_cancel_sala_obs,
                            'salida_alarma_sala_obs' : this.salida_alarma_sala_obs,
                            'acc_electrico_sala_obs' : this.acc_electrico_sala_obs,
                            'acabado_muros_sala_obs' : this.acabado_muros_sala_obs,
                            'acabado_plafon_sala_obs' : this.acabado_plafon_sala_obs,
                            'piso_sala_obs' : this.piso_sala_obs,
                            'zoclo_sala_obs' : this.zoclo_sala_obs,
                        
                        ///////////////// COCINA //////////////
                            'tarja_cocina' : this.tarja_cocina,
                            'puerta_cocina' : this.puerta_cocina,
                            'chapa_cocina' : this.chapa_cocina,
                            'sello_pv_cocina' : this.sello_pv_cocina,
                            'vidrio_pv_cocina' : this.vidrio_pv_cocina,
                            'mosquitero_cocina' : this.mosquitero_cocina,
                            'salida_alarma_cocina' : this.salida_alarma_cocina,
                            'interfon_cocina' : this.interfon_cocina,
                            'acc_electrico_cocina' : this.acc_electrico_cocina,
                            'centro_carga_cocina' : this.centro_carga_cocina,
                            'inst_gas_cocina' : this.inst_gas_cocina,
                            'inst_refrigerador_cocina' : this.inst_refrigerador_cocina,
                            'barra_cocina' : this.barra_cocina,
                            'azulejo_cocina' : this.azulejo_cocina,
                            'acabado_muro_cocina' : this.acabado_muro_cocina,
                            'acabado_plafon_cocina' : this.acabado_plafon_cocina,
                            'piso_cocina' : this.piso_cocina,
                            'zoclo_cocina' : this.zoclo_cocina,
                            ///////// Observaciones ////////////
                            'tarja_cocina_obs' : this.tarja_cocina_obs,
                            'puerta_cocina_obs' : this.puerta_cocina_obs,
                            'chapa_cocina_obs' : this.chapa_cocina_obs,
                            'sello_pv_cocina_obs' : this.sello_pv_cocina_obs,
                            'vidrio_pv_cocina_obs' : this.vidrio_pv_cocina_obs,
                            'mosquitero_cocina_obs' : this.mosquitero_cocina_obs,
                            'salida_alarma_cocina_obs' : this.salida_alarma_cocina_obs,
                            'interfon_cocina_obs' : this.interfon_cocina_obs,
                            'acc_electrico_cocina_obs' : this.acc_electrico_cocina_obs,
                            'centro_carga_cocina_obs' : this.centro_carga_cocina_obs,
                            'inst_gas_cocina_obs' : this.inst_gas_cocina_obs,
                            'inst_refrigerador_cocina_obs' : this.inst_refrigerador_cocina_obs,
                            'barra_cocina_obs' : this.barra_cocina_obs,
                            'azulejo_cocina_obs' : this.azulejo_cocina_obs,
                            'acabado_muro_cocina_obs' : this.acabado_muro_cocina_obs,
                            'acabado_plafon_cocina_obs' : this.acabado_plafon_cocina_obs,
                            'piso_cocina_obs' : this.piso_cocina_obs,
                            'zoclo_cocina_obs' : this.zoclo_cocina_obs,
                        //////////  MEDIO BAÑO  /////////////
                            'puerta_mb' : this.puerta_mb, 
                            'chapa_mb' : this.chapa_mb,
                            'barra_lavabo_mb' : this.barra_lavabo_mb,
                            'lavabo_mb' : this.lavabo_mb,
                            'monomando_mb' : this.monomando_mb,
                            'wc_mb' : this.wc_mb,
                            'acc_bano_mb' : this.acc_bano_mb,
                            'acc_electrico_mb' : this.acc_electrico_mb,
                            'ventana_mb' : this.ventana_mb,
                            'sello_ventana_mb' : this.sello_ventana_mb,
                            'vidrio_mb' : this.vidrio_mb,
                            'mosquitero_mb' : this.mosquitero_mb,
                            'acabado_muro_mb' : this.acabado_muro_mb,
                            'acabado_plafon_mb' : this.acabado_plafon_mb,
                            'piso_mb' : this.piso_mb,
                            'zoclo_mb' : this.zoclo_mb,
                            ////////// Observacion //////////
                            'puerta_mb_obs' : this.puerta_mb_obs, 
                            'chapa_mb_obs' : this.chapa_mb_obs,
                            'barra_lavabo_mb_obs' : this.barra_lavabo_mb_obs,
                            'lavabo_mb_obs' : this.lavabo_mb_obs,
                            'monomando_mb_obs' : this.monomando_mb_obs,
                            'wc_mb_obs' : this.wc_mb_obs,
                            'acc_bano_mb_obs' : this.acc_bano_mb_obs,
                            'acc_electrico_mb_obs' : this.acc_electrico_mb_obs,
                            'ventana_mb_obs' : this.ventana_mb_obs,
                            'sello_ventana_mb_obs' : this.sello_ventana_mb_obs,
                            'vidrio_mb_obs' : this.vidrio_mb_obs,
                            'mosquitero_mb_obs' : this.mosquitero_mb_obs,
                            'acabado_muro_mb_obs' : this.acabado_muro_mb_obs,
                            'acabado_plafon_mb_obs' : this.acabado_plafon_mb_obs,
                            'piso_mb_obs' : this.piso_mb_obs,
                            'zoclo_mb_obs' : this.zoclo_mb_obs,

                        /////////////  PATIO  /////////////////
                            'calentador_patio' : this.calentador_patio, 
                            'inst_gas_patio' : this.inst_gas_patio, 
                            'acc_electrico_patio' : this.acc_electrico_patio, 
                            'lavadero_patio' : this.lavadero_patio, 
                            'llaves_nariz_patio' : this.llaves_nariz_patio, 
                            'descarga_lavadora_patio' : this.descarga_lavadora_patio, 
                            'coladera_patio' : this.coladera_patio, 
                            'tapa_registro_patio' : this.tapa_registro_patio, 
                            'escalera_marina_patio' : this.escalera_marina_patio, 
                            'techumbre_patio' : this.techumbre_patio, 
                            'firme_cilindros_patio' : this.firme_cilindros_patio, 
                            'rodapie_patio' : this.rodapie_patio, 
                            'acabado_muros_patio' : this.acabado_muros_patio, 
                            'acabado_volado_patio' : this.acabado_volado_patio, 
                            'piso_patio' : this.piso_patio, 
                            'zoclo_patio' : this.zoclo_patio, 
                            ///////// Observacion //////////////
                            'calentador_patio_obs' : this.calentador_patio_obs, 
                            'inst_gas_patio_obs' : this.inst_gas_patio_obs, 
                            'acc_electrico_patio_obs' : this.acc_electrico_patio_obs, 
                            'lavadero_patio_obs' : this.lavadero_patio_obs, 
                            'llaves_nariz_patio_obs' : this.llaves_nariz_patio_obs, 
                            'descarga_lavadora_patio_obs' : this.descarga_lavadora_patio_obs, 
                            'coladera_patio_obs' : this.coladera_patio_obs, 
                            'tapa_registro_patio_obs' : this.tapa_registro_patio_obs, 
                            'escalera_marina_patio_obs' : this.escalera_marina_patio_obs, 
                            'techumbre_patio_obs' : this.techumbre_patio_obs, 
                            'firme_cilindros_patio_obs' : this.firme_cilindros_patio_obs, 
                            'rodapie_patio_obs' : this.rodapie_patio_obs, 
                            'acabado_muros_patio_obs' : this.acabado_muros_patio_obs, 
                            'acabado_volado_patio_obs' : this.acabado_volado_patio_obs, 
                            'piso_patio_obs' : this.piso_patio_obs, 
                            'zoclo_patio_obs' : this.zoclo_patio_obs,
                        
                        /////////////// ESCALERA ////////////////
                            'nicho_escalera' : this.nicho_escalera, 
                            'escalones_escalera' : this.escalones_escalera,
                            'piso_escalera' : this.piso_escalera,
                            'zoclo_escalera' : this.zoclo_escalera,
                            'barandal_escalera' : this.barandal_escalera,
                            'pasamanos_escalera' : this.pasamanos_escalera,
                            'sardinel_escalera' : this.sardinel_escalera,
                            'macetero_escalera' : this.macetero_escalera,
                            'cajillos_escalera' : this.cajillos_escalera,
                            'acc_electricos_escalera' : this.acc_electricos_escalera,
                            'acabado_muro_escalera' : this.acabado_muro_escalera,
                            'acabado_plafon_escalera' : this.acabado_plafon_escalera,
                            ///////////// Observacion //////////////
                            'nicho_escalera_obs' : this.nicho_escalera_obs, 
                            'escalones_escalera_obs' : this.escalones_escalera_obs,
                            'piso_escalera_obs' : this.piso_escalera_obs,
                            'zoclo_escalera_obs' : this.zoclo_escalera_obs,
                            'barandal_escalera_obs' : this.barandal_escalera_obs,
                            'pasamanos_escalera_obs' : this.pasamanos_escalera_obs,
                            'sardinel_escalera_obs' : this.sardinel_escalera_obs,
                            'macetero_escalera_obs' : this.macetero_escalera_obs,
                            'cajillos_escalera_obs' : this.cajillos_escalera_obs,
                            'acc_electricos_escalera_obs' : this.acc_electricos_escalera_obs,
                            'acabado_muro_escalera_obs' : this.acabado_muro_escalera_obs,
                            'acabado_plafon_escalera_obs' : this.acabado_plafon_escalera_obs,
                        ///////////////  BAÑO COMUN  //////////////////
                            'puerta_bc' : this.puerta_bc,
                            'chapa_bc' : this.chapa_bc,
                            'sello_marco_bc' : this.sello_marco_bc,
                            'barra_lavabo_bc' : this.barra_lavabo_bc,
                            'lavabo_bc' : this.lavabo_bc,
                            'monomando_bc' : this.monomando_bc,
                            'wc_bc' : this.wc_bc,
                            'regadera_bc' : this.regadera_bc,
                            'manerales_bc' : this.manerales_bc,
                            'coladera_bc' : this.coladera_bc,
                            'acc_bano_bc' : this.acc_bano_bc,
                            'acc_electricos_bc' : this.acc_electricos_bc,
                            'ventana_bc' : this.ventana_bc,
                            'sello_ventana_bc' : this.sello_ventana_bc,
                            'vidrio_ventana_bc' : this.vidrio_ventana_bc,
                            'mosquitero_bc' : this.mosquitero_bc,
                            'acabado_muro_bc' : this.acabado_muro_bc,
                            'acabado_plafon_bc' : this.acabado_plafon_bc,
                            'sardinel_bc' : this.sardinel_bc,
                            'piso_bc' : this.piso_bc,
                            'zoclo_bc' : this.zoclo_bc,
                            ///////////// Observacion ////////////////
                            'puerta_bc_obs' : this.puerta_bc_obs,
                            'chapa_bc_obs' : this.chapa_bc_obs,
                            'sello_marco_bc_obs' : this.sello_marco_bc_obs,
                            'barra_lavabo_bc_obs' : this.barra_lavabo_bc_obs,
                            'lavabo_bc_obs' : this.lavabo_bc_obs,
                            'monomando_bc_obs' : this.monomando_bc_obs,
                            'wc_bc_obs' : this.wc_bc_obs,
                            'regadera_bc_obs' : this.regadera_bc_obs,
                            'manerales_bc_obs' : this.manerales_bc_obs,
                            'coladera_bc_obs' : this.coladera_bc_obs,
                            'acc_bano_bc_obs' : this.acc_bano_bc_obs,
                            'acc_electricos_bc_obs' : this.acc_electricos_bc_obs,
                            'ventana_bc_obs' : this.ventana_bc_obs,
                            'sello_ventana_bc_obs' : this.sello_ventana_bc_obs,
                            'vidrio_ventana_bc_obs' : this.vidrio_ventana_bc_obs,
                            'mosquitero_bc_obs' : this.mosquitero_bc_obs,
                            'acabado_muro_bc_obs' : this.acabado_muro_bc_obs,
                            'acabado_plafon_bc_obs' : this.acabado_plafon_bc_obs,
                            'sardinel_bc_obs' : this.sardinel_bc_obs,
                            'piso_bc_obs' : this.piso_bc_obs,
                            'zoclo_bc_obs' : this.zoclo_bc_obs,
                        
                        ///////////// ESTANCIA //////////////
                            'ventana_estancia' : this.ventana_estancia,
                            'sello_ventana_estancia' : this.sello_ventana_estancia,
                            'vidrio_ventana_estancia' : this.vidrio_ventana_estancia,
                            'mosquitero_estancia' : this.mosquitero_estancia,
                            'interfon_estancia' : this.interfon_estancia,
                            'acc_electricos_estancia' : this.acc_electricos_estancia,
                            'acabado_muro_estancia' : this.acabado_muro_estancia,
                            'acabado_plafon_estancia' : this.acabado_plafon_estancia,
                            'piso_estancia' : this.piso_estancia,
                            'zoclo_estancia' : this.zoclo_estancia,
                            //////////// Observacion /////////////
                            'ventana_estancia_obs' : this.ventana_estancia_obs,
                            'sello_ventana_estancia_obs' : this.sello_ventana_estancia_obs,
                            'vidrio_ventana_estancia_obs' : this.vidrio_ventana_estancia_obs,
                            'mosquitero_estancia_obs' : this.mosquitero_estancia_obs,
                            'interfon_estancia_obs' : this.interfon_estancia_obs,
                            'acc_electricos_estancia_obs' : this.acc_electricos_estancia_obs,
                            'acabado_muro_estancia_obs' : this.acabado_muro_estancia_obs,
                            'acabado_plafon_estancia_obs' : this.acabado_plafon_estancia_obs,
                            'piso_estancia_obs' : this.piso_estancia_obs,
                            'zoclo_estancia_obs' : this.zoclo_estancia_obs,

                        //////////// Recamara Principal //////////////
                            'puerta_rp' : this.puerta_rp,
                            'chapa_rp' : this.chapa_rp,
                            'sello_marco_rp' : this.sello_marco_rp,
                            'cancel_rp' : this.cancel_rp,
                            'sello_cancel_rp' : this.sello_cancel_rp,
                            'vidrio_cancel_rp' : this.vidrio_cancel_rp,
                            'mosquitero_rp' : this.mosquitero_rp,
                            'balcon_rp' : this.balcon_rp,
                            'barandal_rp' : this.barandal_rp,
                            'acc_electricos_rp' : this.acc_electricos_rp,
                            'interfon_rp' : this.interfon_rp,
                            'salida_alarma_rp' : this.salida_alarma_rp,
                            'acabado_muro_rp' : this.acabado_muro_rp,
                            'acabado_plafon_rp' : this.acabado_plafon_rp,
                            'piso_rp' : this.piso_rp,
                            'zoclo_rp' : this.zoclo_rp,
                            //////////////// Observacion //////////////
                            'puerta_rp_obs' : this.puerta_rp_obs,
                            'chapa_rp_obs' : this.chapa_rp_obs,
                            'sello_marco_rp_obs' : this.sello_marco_rp_obs,
                            'cancel_rp_obs' : this.cancel_rp_obs,
                            'sello_cancel_rp_obs' : this.sello_cancel_rp_obs,
                            'vidrio_cancel_rp_obs' : this.vidrio_cancel_rp_obs,
                            'mosquitero_rp_obs' : this.mosquitero_rp_obs,
                            'balcon_rp_obs' : this.balcon_rp_obs,
                            'barandal_rp_obs' : this.barandal_rp_obs,
                            'acc_electricos_rp_obs' : this.acc_electricos_rp_obs,
                            'interfon_rp_obs' : this.interfon_rp_obs,
                            'salida_alarma_rp_obs' : this.salida_alarma_rp_obs,
                            'acabado_muro_rp_obs' : this.acabado_muro_rp_obs,
                            'acabado_plafon_rp_obs' : this.acabado_plafon_rp_obs,
                            'piso_rp_obs' : this.piso_rp_obs,
                            'zoclo_rp_obs' : this.zoclo_rp_obs,

                        ///////////// BAÑO RECAMARA PRINCIPAL ////////////////
                            'puerta_brp' : this.puerta_brp,
                            'chapa_brp' : this.chapa_brp,
                            'sello_marco_brp' : this.sello_marco_brp,
                            'barra_lavabo_brp' : this.barra_lavabo_brp,
                            'lavabo_brp' : this.lavabo_brp,
                            'monomando_brp' : this.monomando_brp,
                            'wc_brp' : this.wc_brp,
                            'regadera_brp' : this.regadera_brp,
                            'manerales_brp' : this.manerales_brp,
                            'coladera_brp' : this.coladera_brp,
                            'acc_bano_brp' : this.acc_bano_brp,
                            'acc_electrico_brp' : this.acc_electrico_brp,
                            'ventana_brp' : this.ventana_brp,
                            'sello_ventana_brp' : this.sello_ventana_brp,
                            'vidrio_ventana_brp' : this.vidrio_ventana_brp,
                            'mosquitero_brp' : this.mosquitero_brp,
                            'acabado_muro_brp' : this.acabado_muro_brp,
                            'acabado_plafon_brp' : this.acabado_plafon_brp,
                            'sardinel_brp' : this.sardinel_brp,
                            'piso_brp' : this.piso_brp,
                            'zoclo_brp' : this.zoclo_brp,
                            /////////// Observacion ///////////////
                            'puerta_brp_obs' : this.puerta_brp_obs,
                            'chapa_brp_obs' : this.chapa_brp_obs,
                            'sello_marco_brp_obs' : this.sello_marco_brp_obs,
                            'barra_lavabo_brp_obs' : this.barra_lavabo_brp_obs,
                            'lavabo_brp_obs' : this.lavabo_brp_obs,
                            'monomando_brp_obs' : this.monomando_brp_obs,
                            'wc_brp_obs' : this.wc_brp_obs,
                            'regadera_brp_obs' : this.regadera_brp_obs,
                            'manerales_brp_obs' : this.manerales_brp_obs,
                            'coladera_brp_obs' : this.coladera_brp_obs,
                            'acc_bano_brp_obs' : this.acc_bano_brp_obs,
                            'acc_electrico_brp_obs' : this.acc_electrico_brp_obs,
                            'ventana_brp_obs' : this.ventana_brp_obs,
                            'sello_ventana_brp_obs' : this.sello_ventana_brp_obs,
                            'vidrio_ventana_brp_obs' : this.vidrio_ventana_brp_obs,
                            'mosquitero_brp_obs' : this.mosquitero_brp_obs,
                            'acabado_muro_brp_obs' : this.acabado_muro_brp_obs,
                            'acabado_plafon_brp_obs' : this.acabado_plafon_brp_obs,
                            'sardinel_brp_obs' : this.sardinel_brp_obs,
                            'piso_brp_obs' : this.piso_brp_obs,
                            'zoclo_brp_obs' : this.zoclo_brp_obs,

                        //////////////// Vestidor //////////////////
                            'acc_electrico_vest' : this.acc_electrico_vest,
                            'acabado_muro_vest' : this.acabado_muro_vest,
                            'acabado_plafon_vest' : this.acabado_plafon_vest,
                            'piso_vest' : this.piso_vest,
                            'zoclo_vest' : this.zoclo_vest,
                            /////////// Observacion ////////////
                            'acc_electrico_vest_obs' : this.acc_electrico_vest_obs,
                            'acabado_muro_vest_obs' : this.acabado_muro_vest_obs,
                            'acabado_plafon_vest_obs' : this.acabado_plafon_vest_obs,
                            'piso_vest_obs' : this.piso_vest_obs,
                            'zoclo_vest_obs' : this.zoclo_vest_obs,
                        /////////////// RECAMARA 2 ///////////////
                            'puerta_rec2' : this.puerta_rec2,
                            'chapa_rec2' : this.chapa_rec2,
                            'sello_marco_rec2' : this.sello_marco_rec2,
                            'cancel_rec2' : this.cancel_rec2,
                            'sello_cancel_rec2' : this.sello_cancel_rec2,
                            'vidrio_cancel_rec2' : this.vidrio_cancel_rec2,
                            'mosquitero_rec2' : this.mosquitero_rec2,
                            'acc_rec2' : this.acc_rec2,
                            'salida_alarma_rec2' : this.salida_alarma_rec2,
                            'acabado_muro_rec2' : this.acabado_muro_rec2,
                            'acabado_plafon_rec2' : this.acabado_plafon_rec2,
                            'piso_rec2' : this.piso_rec2,
                            'zoclo_rec2' : this.zoclo_rec2,
                            //////////////  Observacion  ////////////
                            'puerta_rec2_obs' : this.puerta_rec2_obs,
                            'chapa_rec2_obs' : this.chapa_rec2_obs,
                            'sello_marco_rec2_obs' : this.sello_marco_rec2_obs,
                            'cancel_rec2_obs' : this.cancel_rec2_obs,
                            'sello_cancel_rec2_obs' : this.sello_cancel_rec2_obs,
                            'vidrio_cancel_rec2_obs' : this.vidrio_cancel_rec2_obs,
                            'mosquitero_rec2_obs' : this.mosquitero_rec2_obs,
                            'acc_rec2_obs' : this.acc_rec2_obs,
                            'salida_alarma_rec2_obs' : this.salida_alarma_rec2_obs,
                            'acabado_muro_rec2_obs' : this.acabado_muro_rec2_obs,
                            'acabado_plafon_rec2_obs' : this.acabado_plafon_rec2_obs,
                            'piso_rec2_obs' : this.piso_rec2_obs,
                            'zoclo_rec2_obs' : this.zoclo_rec2_obs,

                        /////////////// RECAMARA 3 ///////////////
                            'puerta_rec3' : this.puerta_rec3,
                            'chapa_rec3' : this.chapa_rec3,
                            'sello_marco_rec3' : this.sello_marco_rec3,
                            'cancel_rec3' : this.cancel_rec3,
                            'sello_cancel_rec3' : this.sello_cancel_rec3,
                            'vidrio_cancel_rec3' : this.vidrio_cancel_rec3,
                            'mosquitero_rec3' : this.mosquitero_rec3,
                            'acc_rec3' : this.acc_rec3,
                            'salida_alarma_rec3' : this.salida_alarma_rec3,
                            'acabado_muro_rec3' : this.acabado_muro_rec3,
                            'acabado_plafon_rec3' : this.acabado_plafon_rec3,
                            'piso_rec3' : this.piso_rec3,
                            'zoclo_rec3' : this.zoclo_rec3,
                            //////////////  Observacion  ////////////
                            'puerta_rec3_obs' : this.puerta_rec3_obs,
                            'chapa_rec3_obs' : this.chapa_rec3_obs,
                            'sello_marco_rec3_obs' : this.sello_marco_rec3_obs,
                            'cancel_rec3_obs' : this.cancel_rec3_obs,
                            'sello_cancel_rec3_obs' : this.sello_cancel_rec3_obs,
                            'vidrio_cancel_rec3_obs' : this.vidrio_cancel_rec3_obs,
                            'mosquitero_rec3_obs' : this.mosquitero_rec3_obs,
                            'acc_rec3_obs' : this.acc_rec3_obs,
                            'salida_alarma_rec3_obs' : this.salida_alarma_rec3_obs,
                            'acabado_muro_rec3_obs' : this.acabado_muro_rec3_obs,
                            'acabado_plafon_rec3_obs' : this.acabado_plafon_rec3_obs,
                            'piso_rec3_obs' : this.piso_rec3_obs,
                            'zoclo_rec3_obs' : this.zoclo_rec3_obs,
                        ///////////// AZOTEA ////////////////
                            'pretiles_azotea' : this.pretiles_azotea,
                            'impermeabilizacion' : this.impermeabilizacion,
                            'domos_azotea' : this.domos_azotea,
                            'mufas_azotea' : this.mufas_azotea,
                            'jarros_azotea' : this.jarros_azotea,
                            'ventilas_azotea' : this.ventilas_azotea,
                            'base_tinaco_azotea' : this.base_tinaco_azotea,
                            'tinaco_azotea' : this.tinaco_azotea,
                            'calentador_solar_azotea' : this.calentador_solar_azotea,
                            'punta_gas_azotea' : this.punta_gas_azotea,
                            'anclas_azotea' : this.anclas_azotea,
                            'limpieza_azotea' : this.limpieza_azotea,
                            ///////// Observacion ///////////
                            'pretiles_azotea_obs' : this.pretiles_azotea_obs,
                            'impermeabilizacion_obs' : this.impermeabilizacion_obs,
                            'domos_azotea_obs' : this.domos_azotea_obs,
                            'mufas_azotea_obs' : this.mufas_azotea_obs,
                            'jarros_azotea_obs' : this.jarros_azotea_obs,
                            'ventilas_azotea_obs' : this.ventilas_azotea_obs,
                            'base_tinaco_azotea_obs' : this.base_tinaco_azotea_obs,
                            'tinaco_azotea_obs' : this.tinaco_azotea_obs,
                            'calentador_solar_azotea_obs' : this.calentador_solar_azotea_obs,
                            'punta_gas_azotea_obs' : this.punta_gas_azotea_obs,
                            'anclas_azotea_obs' : this.anclas_azotea_obs,
                            'limpieza_azotea_obs' : this.limpieza_azotea_obs,
                        ///////////  GENERALES  //////////////
                            'limpieza_interior' : this.limpieza_interior,
                            'limpieza_exterior' : this.limpieza_exterior,
                            'limpieza_vidrios' : this.limpieza_vidrios,
                            'limpieza_domos' : this.limpieza_domos,
                            'plastico_muebles' : this.plastico_muebles,
                            'candados' : this.candados,
                            'llaves' : this.llaves,
                            'num_oficial' : this.num_oficial,
                            /////////// Observacion ///////////
                            'limpieza_interior_obs' : this.limpieza_interior_obs,
                            'limpieza_exterior_obs' : this.limpieza_exterior_obs,
                            'limpieza_vidrios_obs' : this.limpieza_vidrios_obs,
                            'limpieza_domos_obs' : this.limpieza_domos_obs,
                            'plastico_muebles_obs' : this.plastico_muebles_obs,
                            'candados_obs' : this.candados_obs,
                            'llaves_obs' : this.llaves_obs,
                            'num_oficial_obs' : this.num_oficial_obs,

                    }).then(function (response){
                        //window.alert("Cambios guardados correctamente");
                        me.cerrarRevision();
                        const toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                            });
                            toast({
                            type: 'success',
                            title: 'Revisión guardada correctamente'
                        })
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })

            },

            validarRevision(){
                this.errorRevision=0;
                this.errorMostrarMsjRevision=[];

                if(!this.observacion || this.observacion == '') //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjRevision.push("Escribir una observación final.");

                if(this.errorMostrarMsjRevision.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorRevision = 1;

                return this.errorRevision;
            },

            cerrarModal(){
                this.tituloModal = '';
                this.modal3 = 0;
                this.observacion = '';
                this.arrayObservacion = [];
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
                    }
                }
            
        },
       
        mounted() {
            this.listarContratos(1,this.buscar2,this.b_etapa2,this.b_manzana2,this.b_lote2,this.criterio2);
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
