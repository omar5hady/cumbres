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
                        <i class="fa fa-align-justify"></i>Integracion de expediente

                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-10">
                                <div class="input-group">
                                    <select class="form-control" v-model="b_empresa" >
                                        <option value="">Empresa constructora</option>
                                        <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                    </select>

                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-5" v-model="criterio" @click="selectFraccionamientos()">
                                        <option value="lotes.fraccionamiento_id">Proyecto</option>
                                        <option value="c.nombre">Cliente</option>
                                        <option value="v.nombre">Asesor</option>
                                        <option value="contratos.id"># Folio</option>
                                    </select>

                                    
                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar" @click="selectEtapa(buscar)">
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="input-group">

                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_etapa"> 
                                        <option value="">Etapa</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                    </select>
                                    
                                    <input type="text" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_manzana" class="form-control" placeholder="Manzana a buscar">
                                    <input type="text" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_lote" class="form-control" placeholder="Lote a buscar">

                                    <input v-else type="text"  v-model="buscar" @keyup.enter="listarContratos(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="form-control" placeholder="Texto a buscar">

                                    <button v-if="btn_status == 2" @click="btn_status=0" type="button" class="btn btn-secondary btn-primary">Todos</button>
                                    <button v-if="btn_status == 0" @click="btn_status=1" type="button" class="btn btn-secondary btn-success">Activos</button>
                                    <button v-if="btn_status == 1" @click="btn_status=2" type="button" class="btn btn-secondary btn-warning">Detenidos</button>

                                    <button type="submit" @click="listarContratos(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    <a class="btn btn-success" v-bind:href="'/expediente/Excel?buscar=' + buscar + '&b_etapa=' + b_etapa + '&b_manzana=' + b_manzana + '&b_lote=' + b_lote +  '&criterio=' + criterio + '&btn_status=' + btn_status">
                                        <i class="icon-pencil"></i>&nbsp;Excel
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr> 
                                        <th class="td2"> <i class="fa fa-hand-paper-o"></i> Detener</th>
                                        <th># Ref</th>
                                        <th>Celular</th>
                                        <th>Email</th>
                                        <th>Cliente</th>
                                        <th>Asesor</th>
                                        <th>Proyecto</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th>Lote</th>
                                        <th>Avance</th>
                                        <th>Fecha de visita para avaluo</th>
                                        <th>Firma</th>
                                        <th>Tipo de credito</th>
                                        <th>Institucion de Fin.</th>
                                        <th>Solicitud de avaluo</th>
                                        <th>Depositado</th>
                                        <th>Fecha ultimo pagare</th>
                                        <th>Aviso preventivo</th>
                                        <th>Crédito puente</th>
                                        <th>Conyuge</th>
                                        <th>Integracion</th>
                                        <th>Entrega de vivienda</th>
                                        <th>Observaciones</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="contratos in arrayContratos" :key="contratos.id" v-bind:style="{ backgroundColor : !contratos.detenido ? '#FFFFFF' : '#D23939'}">
                                        <td class="td2">
                                            <button v-if="contratos.detenido == 0" type="button" @click="detenerContrato(contratos.folio,1)" class="btn btn-danger btn-sm" title="Detener solicitud">
                                                <i class="fa fa-hand-paper-o"></i>
                                            </button>
                                            <button v-if="contratos.detenido == 1" type="button" @click="continuarContrato(contratos.folio,0)" class="btn btn-success btn-sm" title="Reanudar solicitud">
                                                <i class="fa fa-play"></i>
                                            </button>
                                        </td>
                                        <td class="td2">
                                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">{{contratos.folio}}</a>
                                            <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
                                                <a class="dropdown-item" v-if="contratos.pdf != '' && contratos.pdf != null"  v-bind:href="'/downloadAvaluo/'+contratos.pdf">Avaluo</a>
                                                <a class="dropdown-item" @click="abrirPDF(contratos.folio)">Estado de cuenta</a>
                                                <a class="dropdown-item" target="_blank" v-bind:href="'/contratoCompraVenta/pdf/'+ contratos.folio">Contrato de compra venta</a>
                                                <a class="dropdown-item" target="_blank" v-bind:href="'/cartaServicios/pdf/'+ contratos.folio">Carta de servicios</a>
                                                <a class="dropdown-item" target="_blank" v-bind:href="'/serviciosTelecom/pdf/'+ contratos.folio">Servicios de telecomunición</a>
                                                <a class="dropdown-item" v-bind:href="'/descargarReglamento/contrato/'+ contratos.folio">Reglamento de la etapa</a>
                                                <a class="dropdown-item" @click="selectNombreArchivoModelo(contratos.folio)">Catalogo de especificaciones</a>
                                            </div>
                                        </td>
                                        <td class="td2" >
                                            <a title="Llamar" class="btn btn-dark" :href="'tel:'+contratos.celular"><i class="fa fa-phone fa-lg"></i></a>
                                            <a title="Enviar whatsapp" class="btn btn-success" target="_blank" :href="'https://api.whatsapp.com/send?phone=+52'+contratos.celular+'&text=Hola'"><i class="fa fa-whatsapp fa-lg"></i></a>
                                        </td>
                                        <td class="td2" v-if="contratos.email_institucional == null"> 
                                            <a title="Enviar correo" class="btn btn-secondary" :href="'mailto:'+contratos.email"> <i class="fa fa-envelope-o fa-lg"></i> </a>
                                        </td>
                                        <td class="td2" v-else> 
                                            <a title="Enviar correo" class="btn btn-secondary" :href="'mailto:'+contratos.email+ ';'+contratos.email_institucional"> <i class="fa fa-envelope-o fa-lg"></i> </a>
                                        </td>
                                        <td class="td2" v-text="contratos.nombre_cliente"></td>
                                        <td class="td2" v-text="contratos.nombre_vendedor"></td>
                                        <td class="td2" v-text="contratos.proyecto"></td>
                                        <td class="td2" v-text="contratos.etapa"></td>
                                        <td class="td2" v-text="contratos.manzana"></td>
                                        <td class="td2">
                                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">{{contratos.num_lote}}</a>
                                            <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
                                                <a v-if ="contratos.foto_predial" class="dropdown-item" v-bind:href="'/downloadPredial/'+contratos.foto_predial">Descargar predial</a>
                                                <a v-if ="contratos.foto_lic" class="dropdown-item" v-bind:href="'/downloadLicencias/'+contratos.foto_lic">Descargar licencia</a>
                                                <a v-if ="contratos.foto_acta" class="dropdown-item" v-bind:href="'/downloadActa/'+contratos.foto_acta">Descargar Acta de termino</a>
                                            </div>

                                        </td>
                                        <td class="td2" v-text="contratos.avance_lote+'%'"></td>
                                        
                                        <td class="td2" v-if="contratos.visita_avaluo" v-text="this.moment(contratos.visita_avaluo).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td class="td2" v-else v-text="'Sin fecha'"></td>
                                        <td class="td2" v-text="this.moment(contratos.fecha_status).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td class="td2" v-text="contratos.tipo_credito"></td>
                                        <td class="td2" v-text="contratos.institucion"></td>
                                        <template v-if="contratos.avaluo_preventivo">
                                            <td v-if="contratos.avaluo_preventivo!='0000-01-01'" class="td2" v-text="this.moment(contratos.avaluo_preventivo).locale('es').format('DD/MMM/YYYY')"></td>
                                            <td v-if="contratos.avaluo_preventivo=='0000-01-01'" class="td2" v-text="'No aplica'"></td>
                                        </template>
                                        <template v-else>
                                            <td class="td2" v-if="contratos.detenido == 0" >
                                                <button type="button" @click="abrirModal('avaluo',contratos)" class="btn btn-warning btn-sm" title="Solicitar avaluo">
                                                    <i class="fa fa-file-text-o"></i>
                                                </button>
                                                <button type="button" @click="noAplicaAvaluo(contratos.folio)" class="btn btn-danger btn-sm" title="No aplica">
                                                    <i class="fa fa-times-circle"></i>
                                                </button>
                                            </td>
                                            <td class="td2" v-else >
                                                DETENIDO
                                            </td>
                                        </template> 
                                        <td v-text="'$'+formatNumber(contratos.totPagare - contratos.totRest)"></td>
                                        <td class="td2" v-text="this.moment(contratos.ultimo_pagare).locale('es').format('DD/MMM/YYYY')"></td>
                                         <template v-if="contratos.aviso_prev">
                                            <td @dblclick="abrirModal('fecha_recibido',contratos)" v-if="contratos.aviso_prev!='0000-01-01' && !contratos.aviso_prev_venc" class="td2" v-text="'Fecha solicitud: ' 
                                                + this.moment(contratos.aviso_prev).locale('es').format('DD/MMM/YYYY')"></td>

                                            <td  @dblclick="abrirModal('fecha_recibido',contratos)" v-if="contratos.aviso_prev!='0000-01-01' && contratos.aviso_prev_venc" class="td2">
                                                
                                                <span v-if = "contratos.diferencia > 0" class="badge2 badge-danger" v-text="'Fecha vencimiento: ' 
                                                + this.moment(contratos.aviso_prev_venc).locale('es').format('DD/MMM/YYYY')"></span>

                                                <span v-if = "contratos.diferencia < 0 && contratos.diferencia >= -15 " class="badge2 badge-warning" v-text="'Fecha vencimiento: ' 
                                                + this.moment(contratos.aviso_prev_venc).locale('es').format('DD/MMM/YYYY')"></span>

                                                <span v-if = "contratos.diferencia < -15 " class="badge2 badge-success" v-text="'Fecha vencimiento: ' 
                                                + this.moment(contratos.aviso_prev_venc).locale('es').format('DD/MMM/YYYY')"></span>
                                                
                                            </td>

                                            <td v-if="contratos.aviso_prev=='0000-01-01'" class="td2" v-text="'No aplica'"></td>
                                        </template>
                                        <template v-else>
                                            <td class="td2" v-if="contratos.detenido == 0">
                                                <button type="button" @click="abrirModal('aviso_preventivo',contratos)" class="btn btn-warning btn-sm" title="Solicitar aviso">
                                                    <i class="fa fa-file-text-o"></i>
                                                </button>
                                                <button type="button" @click="noAplicaAviso(contratos.folio)" class="btn btn-danger btn-sm" title="No aplica">
                                                    <i class="fa fa-times-circle"></i>
                                                </button>
                                            </td>
                                            <td class="td2" v-else >
                                                DETENIDO
                                            </td>
                                        </template>
                                        <td class="td2" v-text="contratos.credito_puente"></td>
                                        <td v-if="contratos.coacreditado == 1" class="td2" v-text="contratos.nombre_conyuge"></td>
                                        <td v-else class="td2">Sin conyuge</td>
                                        <td class="td2">
                                            <button type="button" v-if="contratos.detenido == 0" @click="abrirModal('integrar',contratos)" class="btn btn-primary btn-sm" title="Integrar">
                                                 <i class="fa fa-check-square-o"> Integrar</i>
                                            </button>
                                            <label v-else>DETENIDO </label>
                                        </td>
                                        <td class="td2">
                                            <button v-if="contratos.detenido == 0" title="Solicitar entrega de vivienda" type="button" class="btn btn-warning pull-right"
                                               @click="generalId = contratos.folio" data-toggle="modal" data-target="#modalEntrega">Solicitar entrega
                                            </button>
                                            <label v-else>DETENIDO </label>
                                        </td>
                                        <td class="td2">
                                            <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right" 
                                                @click="abrirModal3(contratos.folio)">Ver Observaciones</button>
                                        </td>

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
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
         

            <!--Inicio del modal avaluo-->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- form para solicitud de avaluo -->
                            <form v-if="tipoAccion == 1" action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha solicitud</label>
                                    <div class="col-md-4">
                                        <input type="date"  v-model="fecha_solicitud" class="form-control" >
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Monto</label>
                                    <div class="col-md-4">
                                        <input type="text"  v-model="valor_requerido" class="form-control" placeholder="Valor requerido">
                                    </div>

                                    <div class="col-md-3">
                                        <h6 v-text="'$'+formatNumber(valor_requerido)"></h6>
                                    </div>
                                </div>
                                
                            </form>
                            <!-- fin del form solicitud de avaluo -->

                            <!-- form para aviso preventivo -->
                            <form  v-if="tipoAccion == 2" action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha solicitud</label>
                                    <div class="col-md-4">
                                        <input type="date"  v-model="fecha_aviso" class="form-control" >
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Estado</label>
                                    <div class="col-md-4">
                                        <select class="form-control" v-model="estado" @click="selectCiudades(estado)">
                                            <option value="San Luis Potosí">San Luis Potosí</option>
                                            <option value="Baja California">Baja California</option>
                                            <option value="Baja California Sur">Baja California Sur</option>
                                            <option value="Coahuila de Zaragoza">Coahuila de Zaragoza</option>
                                            <option value="Colima">Colima</option>
                                            <option value="Chiapas">Chiapas</option>
                                            <option value="Chihuahua">Chihuahua</option>
                                            <option value="Ciudad de México">Ciudad de México</option>
                                            <option value="Durango">Durango</option>
                                            <option value="Guanajuato">Guanajuato</option>
                                            <option value="Guerrero">Guerrero</option>
                                            <option value="Hidalgo">Hidalgo</option>
                                            <option value="Jalisco">Jalisco</option>
                                            <option value="México">México</option>
                                            <option value="Michoacán de Ocampo">Michoacán de Ocampo</option>
                                            <option value="Morelos">Morelos</option>
                                            <option value="Nayarit">Nayarit</option>
                                            <option value="Nuevo León">Nuevo León</option>
                                            <option value="Oaxaca">Oaxaca</option>
                                            <option value="Puebla">Puebla</option>
                                            <option value="Querétaro">Querétaro</option>
                                            <option value="Quintana Roo">Quintana Roo</option>
                                            <option value="Sinaloa">Sinaloa</option>
                                            <option value="Sonora">Sonora</option>
                                            <option value="Tabasco">Tabasco</option>
                                            <option value="Tamaulipas">Tamaulipas</option>
                                            <option value="Tlaxcala">Tlaxcala</option>
                                            <option value="Veracruz de Ignacio de la Llave">Veracruz de Ignacio de la Llave</option>
                                            <option value="Yucatán">Yucatán</option>
                                            <option value="Zacatecas">Zacatecas</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Ciudad</label>
                                    <div class="col-md-4">
                                        <select class="form-control" v-model="ciudad" @click="selectNotarias(estado,ciudad)">
                                            <option v-for="ciudades in arrayCiudades" :key="ciudades.municipio" :value="ciudades.municipio" v-text="ciudades.municipio"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Notarias</label>
                                    <div class="col-md-6">
                                        <select class="form-control" v-model="notaria">
                                            <option v-for="notarias in arrayNotarias" :key="notarias.id" :value="notarias.id" v-text="notarias.notaria + ' - ' + notarias.titular"></option>
                                        </select>
                                    </div>
                                </div>
                                
                            </form>
                            <!-- fin del form aviso preventivo -->

                             <!-- form para captura de fecha recibido -->
                            <form v-if="tipoAccion == 3" action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de recibido</label>
                                    <div class="col-md-4">
                                        <input type="date"  v-model="fecha_recibido" class="form-control" >
                                    </div>
                                </div>
                                
                                
                            </form>
                            <!-- fin del form para captura de fecha recibido -->

                            <!-- form para solicitud de avaluo -->
                            <form v-if="tipoAccion == 4" action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Gestores</label>
                                    <div class="col-md-4">
                                    <select class="form-control"  v-model="gestor_id">
                                        <option value="">Seleccione</option>
                                        <option value="1">Sin Asignar</option>
                                        <option v-for="gestores in arrayGestores" :key="gestores.id" :value="gestores.id" v-text="gestores.nombre_gestor"></option>
                                    </select>
                                    </div>
                                </div>
                            </form>
                            <!-- fin del form solicitud de avaluo -->

                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button v-if="tipoAccion==1" type="button" class="btn btn-primary" @click="solicitudAvaluo()">Enviar</button>
                            <button v-if="tipoAccion==2" type="button" class="btn btn-primary" @click="avisoPreventivo()">Enviar</button>
                            <button v-if="tipoAccion==3" type="button" class="btn btn-primary" @click="fechaRecibido()">Enviar</button>
                            <button v-if="tipoAccion==4" type="button" class="btn btn-primary" @click="integrar()">Integrar</button>
                            <a v-bind:href="'/expediente/solicitudPDF/' + id" v-if="tipoAccion==3" type="button" target="_blank" class="btn btn-primary">Imprimir</a>
                        </div>
                    </div>
                      <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal consulta-->

             <!--Inicio del modal observaciones-->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal3}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal3"></h4>
                            <button type="button" class="close" @click="cerrarModal3()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Observacion</label>
                                    <div class="col-md-6">
                                         <textarea rows="3" cols="30" v-model="observacion" class="form-control" placeholder="Observacion"></textarea>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button"  class="btn btn-primary" @click="agregarComentario()">Guardar</button>
                                    </div>
                                </div>

                                
                                <table class="table table-bordered table-striped table-sm" >
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
                                            <td v-text="observacion.observacion" ></td>
                                            <td v-text="observacion.created_at"></td>
                                        </tr>                               
                                    </tbody>
                                </table>
                                
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal3()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                        </div>
                    </div>
                      <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            
            <!-- Modal solicitar entrega -->
            <div class="modal fade" id="modalEntrega" role="dialog" aria-labelledby="modalEntregaLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #00ADEF; color:white;">
                            <h5 class="modal-title" id="modalEntregaLabel">Solicitar Entrega</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="" @submit="solcEntrega" method="post">
                            <div class="modal-body">
                                <div class="row">
                                    <label class="col-md-3 form-control-label" for="text-input">Observacion</label>
                                    <div class="col-md-6">
                                            <textarea id="solEntrObs" name="solEntrObs" rows="3" cols="30" v-model="btnObs" required class="form-control" placeholder="Observacion"></textarea>
                                    </div>
                                    <input type="hidden" id="solEntrId" name="solEntrId" value="">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button v-if="btnObs" type="submit" class="btn btn-primary">Solicitar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
     </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    export default {
        data(){
            return{
                btnObs:'',
                proceso:false,
                id: 0,
                
                fecha_comentario:'',
                observacion:'',
                usuario: '',
               
                fecha_solicitud: '',
                valor_requerido: '',
                fecha_vencimiento:'',
                fecha_aviso: '',
                fecha_recibido: '',
                notaria:0,
                gestor_id:0,

                arrayContratos : [],
                arrayCiudades:[],
                arrayNotarias:[],
                arrayGestores: [],
                arrayObservacion:[],

                arrayFraccionamientos:[],
                arrayEtapas:[],

                modal : 0,
                modal3 : 0,
              
                estado : 'San Luis Potosí',
                ciudad: 'San Luis Potosí',
                
                tituloModal : '',
                tituloModal3: '',
                nombre_archivo_modelo:'',
           
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
                b_etapa: '',
                b_manzana: '',
                b_lote: '',
                btn_status:2,
                generalId:0,
                b_empresa:'',
                empresas:[],
                myAlerts:{
                    popAlert : function(title = 'Alert',type = "success", description =''){
                        swal({
                            title: title,
                            type: type,
                            text: description,
                            showConfirmButton:false,
                            timer:1500,
                        })
                    }
                },
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

            /**Metodo para mostrar los registros */
            listarContratos(page, buscar, b_etapa, b_manzana, b_lote, criterio){
                let me = this;
                var url = '/expediente/listarContratos?page=' + page + '&buscar=' + buscar + '&b_etapa=' + b_etapa + '&b_manzana=' + 
                    b_manzana + '&b_lote=' + b_lote +  '&criterio=' + criterio + '&btn_status=' + me.btn_status +'&b_empresa='+this.b_empresa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayContratos = respuesta.contratos.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
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
            listarObservacion(buscar){
                let me = this;
                var url = '/observacionExpediente?folio=' + buscar ;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayObservacion = respuesta.observacion;
                    console.log(url);
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },
            
            selectFraccionamientos(){
                let me = this;
                me.buscar=""
                
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

            selectEtapa(buscar){
                let me = this;
                me.buscar2=""
                
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

            selectCiudades(buscar){
                let me = this;
                me.arrayCiudades=[];
                var url = '/select_ciudades?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayCiudades = respuesta.ciudades;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectNotarias(estado, ciudad){
                let me = this;
                me.arrayNotarias=[];
                var url = '/select_notarias?estado=' + estado + '&ciudad=' + ciudad;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayNotarias = respuesta.notarias;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectGestores(){
                let me = this;
                me.arrayGestores=[];
                var url = '/select_gestores';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayGestores = respuesta.gestores;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },



            cambiarPagina(page,buscar,b_etapa,b_manzana,b_lote,criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarContratos(page,buscar,b_etapa,b_manzana,b_lote,criterio);
            },

            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },

            abrirPDF(id){
                const win = window.open('/estadoCuenta/estadoPDF/'+id, '_blank');
                win.focus();
            },


            fechaRecibido(){
                if(this.proceso==true){
                    return;
                }
                this.proceso=true;
                let me = this;
                 
                //Con axios se llama el metodo update de LoteController
                axios.put('/expediente/fechaRecibido',{
                    'folio':this.id,
                    'fecha_recibido' : this.fecha_recibido,
                    'fecha_vencimiento' : moment(this.fecha_recibido).add(60,'day').format('YYYY-MM-DD'), 

                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal();
                    me.listarContratos(1,me.buscar,me.b_etapa,me.b_manzana,me.b_lote,me.criterio);
                    //window.alert("Cambios guardados correctamente");
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Solicitud enviada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });

            },

            detenerContrato(id,detenido){
               
                let me = this;
                 
                //Con axios se llama el metodo update de LoteController
                axios.put('/contrato/cambiarProceso',{
                    'id':id,
                    'detenido' : detenido

                }).then(function (response){
                    me.listarContratos(1,me.buscar,me.b_etapa,me.b_manzana,me.b_lote,me.criterio);
                    //window.alert("Cambios guardados correctamente");
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Contrato detenido correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });

            },

            continuarContrato(id,detenido){
               
                let me = this;
                 
                //Con axios se llama el metodo update de LoteController
                axios.put('/contrato/cambiarProceso',{
                    'id':id,
                    'detenido' : detenido

                }).then(function (response){
                    me.listarContratos(1,me.buscar,me.b_etapa,me.b_manzana,me.b_lote,me.criterio);
                    //window.alert("Cambios guardados correctamente");
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'El contrato continua correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
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
                axios.post('/observacionExpediente/registrar',{
                    'folio': this.id,
                    'observacion': this.observacion
                }).then(function (response){
                    me.proceso=false;
                    me.listarObservacion(me.id);
                    me.observacion = '';
                    //me.cerrarModal3(); //al guardar el registro se cierra el modal
                    
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

       
            solicitudAvaluo(){
                if(this.proceso==true){
                    return;
                }
                this.proceso=true;
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.post('/expediente/solicitarAvaluo',{
                    'folio':this.id,
                    'fecha_solicitud' : this.fecha_solicitud,
                    'valor_requerido' : this.valor_requerido,
                    
                    
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal();
                    me.listarContratos(1,me.buscar,me.b_etapa,me.b_manzana,me.b_lote,me.criterio);
                    //window.alert("Cambios guardados correctamente");
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Solicitud enviada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },

            noAplicaAvaluo(id){
                this.id = id;
                swal({
                title: '¿Esta seguro de que la solicitud de avaluo no aplica para este registro?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/expediente/AvaluoNoAplica',{
                        'folio': me.id
                    }).then(function (response) {
                        me.cerrarModal();
                        me.listarContratos(1,me.buscar,me.b_etapa,me.b_manzana,me.b_lote,me.criterio);
                        swal(
                        'Hecho!',
                        'No aplica.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },

            avisoPreventivo(){
                if(this.proceso==true){
                    return;
                }
                this.proceso=true;
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.post('/expediente/solicitarAviso',{
                    'folio':this.id,
                    'fecha_solicitud' : this.fecha_aviso,
                    'notaria_id' : this.notaria,
                    
                    
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal();
                    me.listarContratos(1,me.buscar,me.b_etapa,me.b_manzana,me.b_lote,me.criterio);
                    //window.alert("Cambios guardados correctamente");
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Solicitud enviada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },

            noAplicaAviso(id){
                this.id = id;
                swal({
                title: '¿Esta seguro de que el aviso preventivo no aplica para este registro?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/expediente/AvisoNoAplica',{
                        'folio': me.id
                    }).then(function (response) {
                        me.cerrarModal();
                        me.listarContratos(1,me.buscar,me.b_etapa,me.b_manzana,me.b_lote,me.criterio);
                        swal(
                        'Hecho!',
                        'No aplica.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },

            integrar(){
                    let me = this;

                    axios.post('/expediente/integrar',{
                        'folio': me.id,
                        'gestor_id' : me.gestor_id,
                    }).then(function (response) {
                        me.listarContratos(1,me.buscar,me.b_etapa,me.b_manzana,me.b_lote,me.criterio);
                        me.cerrarModal();
                        swal(
                        'Hecho!',
                        'Contrato integrado correctamente.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                
            },

            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                
            },
            cerrarModal3(){
                this.modal3 = 0;
                this.tituloModal3 = '';
                this.usuario = '';
                this.observacion = '';
            },
         
      
            abrirModal3(folio){
                this.modal3 =1;
                this.tituloModal3='Observaciones';
                this.observacion='';
                this.usuario='';
                this.id=folio;
                this.listarObservacion(folio);
            },

      
            abrirModal(accion,data =[]){
               
                        switch(accion){
                           
                            case 'avaluo':
                            {
                                this.modal =1;
                                this.tituloModal='Avaluo';
                                this.tipoAccion = 1;
                                this.fecha_solicitud = '';
                                this.id = data['folio'];
                                break;
                            }

                            case 'aviso_preventivo': 
                            {
                                this.modal = 1;
                                this.tituloModal='Aviso preventivo';
                                this.tipoAccion = 2;
                                this.ciudad = 'San Luis Potosí';
                                this.id = data['folio'];
                                break;
                            }

                            case 'fecha_recibido': 
                            {
                                this.modal = 1;
                                this.tituloModal='Fecha recibido';
                                this.tipoAccion = 3;
                                this.fecha_recibido = data['aviso_prev_venc'];
                                this.id = data['folio'];
                                
                                break;
                            }

                            case 'integrar': 
                            {
                                this.modal = 1;
                                this.tituloModal='Asigna un gestor';
                                this.tipoAccion = 4;
                                this.id = data['folio'];
                                this.selectGestores();    
                                break;
                            }
                            
                }
                this.selectCiudades(this.estado);
                this.selectNotarias(this.estado,this.ciudad);

            },

            solcEntrega(e){
                e.preventDefault();
                let me = this;

                axios.post('/entrega/registrar',{
                    'id': this.generalId,
                    'comentario': e.target.solEntrObs.value
                }).then(
                    () => {
                        me.listarContratos(1,me.buscar,me.b_etapa,me.b_manzana,me.b_lote,me.criterio)
                        me.myAlerts.popAlert('Guardado correctamente')
                    }
                ).catch(error => console.log(error));
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
        
        },
       
        mounted() {
            this.listarContratos(1,this.buscar,this.b_etapa,this.b_manzana,this.b_lote,this.criterio);
            this.selectFraccionamientos();
            this.getEmpresa();
        }
    }
</script>
<style>
    .form-control:disabled, .form-control[readonly] {
    background-color: rgba(0, 0, 0, 0.06);
    opacity: 1;
    font-size: 1rem;
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

    /*th {
    text-align: left;
    background-color: rgb(190, 220, 250);
    text-transform: uppercase;
    padding-top: 1rem;
    padding-bottom: 1rem;
    border-bottom: rgb(50, 50, 100) solid 2px;
    border-top: none;
    }*/

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
