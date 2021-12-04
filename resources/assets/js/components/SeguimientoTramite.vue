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
                        <i class="fa fa-align-justify"></i>Seguimiento de Tramite

                    </div>
                    <div class="card-body">
                        <ul class="nav nav2 nav-tabs" id="myTab1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active show" id="ingresar-tab" data-toggle="tab" href="#ingresar" role="tab" aria-controls="ingresar" aria-selected="true" v-text="'Por Ingresar (' + contadorIngresar +')'"></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="autorizado-tab" data-toggle="tab" href="#autorizado" role="tab" aria-controls="autorizado" aria-selected="false" v-text="'Autorizados (' + contadorAutorizados +')'"></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="liquidacion-tab" data-toggle="tab" href="#liquidacion" role="tab" aria-controls="liquidacion" aria-selected="false" v-text="'Liquidación (' + contadorLiquidacion +')'"></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="programacion-tab" data-toggle="tab" href="#programacion" role="tab" aria-controls="programacion" aria-selected="false" v-text="'Programación de firma (' + contadorProgramacion +')'"></a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" id="enviados-tab" data-toggle="tab" href="#enviados" role="tab" aria-controls="enviados" aria-selected="false" v-text="'Historial (' + contadorEnviados +')'"></a>
                            </li>
                        </ul>

                        <div class="tab-content" id="myTab1Content">

                            <!-- Listado por ingresar -->
                            <div class="tab-pane fade active show" id="ingresar" role="tabpanel" aria-labelledby="ingresar-tab">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <select class="form-control" v-model="b_empresa" >
                                                <option value="">Empresa constructora</option>
                                                <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <!--Criterios para el listado de busqueda -->
                                            <select class="form-control col-md-4" v-model="criterio" @change="selectFraccionamientos()">
                                                <option value="lotes.fraccionamiento_id">Proyecto</option>
                                                <option value="c.nombre">Cliente</option>
                                                <option v-if="rolId == 1 || rolId == 4 || rolId == 6" value="expedientes.gestor_id">Gestor</option>
                                                <option value="contratos.id"># Folio</option>
                                            </select>

                                            
                                            <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar" @change="selectEtapa(buscar)">
                                                <option value="">Seleccione</option>
                                                <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                            </select>

                                            <select class="form-control" v-else-if="criterio=='expedientes.gestor_id'" v-model="buscar"> 
                                                <option value="">Gestor</option>
                                                <option v-for="gestor in arrayGestores" :key="gestor.id" :value="gestor.id" v-text="gestor.nombre_gestor"></option>
                                            </select>

                                            <input v-else type="text"  v-model="buscar" @keyup.enter="listarAutorizados(1,buscar,b_etapa,b_manzana,b_lote,criterio), listarLiquidacion(1,buscar,b_etapa,b_manzana,b_lote,criterio), listarIngresoExp(1,buscar,b_etapa,b_manzana,b_lote,criterio),listarProgramacion(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="form-control" placeholder="Texto a buscar">
                                        
                                        </div>
                                        <div class="input-group" v-if="criterio=='lotes.fraccionamiento_id'">
                                            <select class="form-control col-md-6"  v-model="b_etapa"> 
                                                <option value="">Etapa</option>
                                                <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                            </select>
                                        </div>
                                        <div class="input-group" v-if="criterio=='lotes.fraccionamiento_id'">
                                            <!--Criterios para el listado de busqueda -->
                                            <input type="text" v-model="b_manzana" class="form-control" placeholder="Manzana a buscar">
                                            <input type="text" v-model="b_lote" class="form-control" placeholder="Lote a buscar">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <button type="submit" 
                                                @click="listarAutorizados(1,buscar,b_etapa,b_manzana,b_lote,criterio), 
                                                    listarLiquidacion(1,buscar,b_etapa,b_manzana,b_lote,criterio), 
                                                    listarIngresoExp(1,buscar,b_etapa,b_manzana,b_lote,criterio),
                                                    listarProgramacion(1,buscar,b_etapa,b_manzana,b_lote,criterio)" 
                                                class="btn btn-primary"><i class="fa fa-search"></i> Buscar
                                            </button>
                                            <a :href="'/expediente/excelIngresarExp?buscar=' + buscar + '&b_etapa=' + b_etapa + '&b_manzana=' + b_manzana + '&b_lote=' + 
                                                b_lote +  '&criterio=' + criterio+'&b_empresa='+b_empresa"  class="btn btn-success">
                                                <i class="fa fa-file-text"></i> Excel
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr> 
                                                <th></th>
                                                <th class="td2"> <i class="fa fa-hand-paper-o"></i> Detener</th>
                                                <th>Celular</th>
                                                <th>Email</th>
                                                <th># Ref</th>
                                                <th>Cliente</th>
                                                <th>Asesor</th>
                                                <th>Proyecto</th>
                                                <th>Etapa</th>
                                                <th>Manzana</th>
                                                <th># Lote</th>
                                                <th>Modelo</th>
                                                <th>Dirección</th>
                                                <th>Avance obra</th>
                                                <th>Firma Contrato</th>
                                                <th>Resultado avaluo</th>
                                                <th>Aviso preventivo</th>
                                                <th>Ingresar expediente</th>
                                                <th>Tipo de Crédito</th>
                                                <th>Institución de Fin.</th>
                                                <th>Valor de la vivienda</th>
                                                <th>Crédito Puente</th>
                                                <th>Fecha ultimo pagare</th>
                                                <th>Saldo</th>
                                                <th>Observaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="ingresar in arrayPorIngresar" :key="ingresar.id" v-bind:style="{ backgroundColor : !ingresar.detenido ? '#FFFFFF' : '#FF5F5F'}"> 
                                                <td class="td2">
                                                    <button v-if="ingresar.detenido == 0" type="button" class="btn btn-danger btn-sm" @click="regresarExpediente(ingresar.folio)" title="Regresar expediente">
                                                        <i class="fa fa-exclamation-triangle"></i>
                                                    </button>
                                                    <label v-else> DETENIDO </label>
                                                </td>
                                                <td class="td2">
                                                    <button v-if="ingresar.detenido == 0" type="button" @click="detenerContrato(ingresar.folio,1)" class="btn btn-danger btn-sm" title="Detener solicitud">
                                                        <i class="fa fa-hand-paper-o"></i>
                                                    </button>
                                                    <button v-if="ingresar.detenido == 1" type="button" @click="continuarContrato(ingresar.folio,0)" class="btn btn-success btn-sm" title="Reanudar solicitud">
                                                        <i class="fa fa-play"></i>
                                                    </button>
                                                </td>

                                                <td class="td2" >
                                                    <a title="Llamar" class="btn btn-dark" :href="'tel:'+ingresar.celular"><i class="fa fa-phone fa-lg"></i></a>
                                                    <a title="Enviar whatsapp" class="btn btn-success" target="_blank" :href="'https://api.whatsapp.com/send?phone=+52'+ingresar.celular+'&text=Hola'"><i class="fa fa-whatsapp fa-lg"></i></a>
                                                </td>
                                                <td class="td2" v-if="ingresar.email_institucional == null"> 
                                                    <a title="Enviar correo" class="btn btn-secondary" :href="'mailto:'+ingresar.email"> <i class="fa fa-envelope-o fa-lg"></i> </a>
                                                </td>
                                                <td class="td2" v-else> 
                                                    <a title="Enviar correo" class="btn btn-secondary" :href="'mailto:'+ingresar.email+ ';'+ingresar.email_institucional"> <i class="fa fa-envelope-o fa-lg"></i> </a>
                                                </td>

                                                <td class="td2">
                                                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">{{ingresar.folio}}</a>
                                                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
                                                        <a class="dropdown-item" v-if="ingresar.pdf != '' && ingresar.pdf != null"  v-bind:href="'/downloadAvaluo/'+ingresar.pdf">Avaluo</a>
                                                        <a class="dropdown-item" @click="abrirPDF(ingresar.folio)">Estado de cuenta</a>
                                                        <a class="dropdown-item" target="_blank" v-bind:href="'/contratoCompraVenta/pdf/'+ ingresar.folio">Contrato de compra venta</a>
                                                        <a class="dropdown-item" target="_blank" v-bind:href="'/cartaServicios/pdf/'+ ingresar.folio">Carta de servicios</a>
                                                        <a class="dropdown-item" target="_blank" v-bind:href="'/serviciosTelecom/pdf/'+ ingresar.folio">Servicios de telecomunición</a>
                                                        <a class="dropdown-item" v-bind:href="'/descargarReglamento/contrato/'+ ingresar.folio">Reglamento de la etapa</a>
                                                        <a class="dropdown-item" @click="selectNombreArchivoModelo(ingresar.folio)">Catalogo de especificaciones</a>
                                                        <a v-if="ingresar.foto_predial" class="dropdown-item" v-bind:href="'/downloadPredial/'+ ingresar.foto_predial">Predial</a>
                                                        <a v-if="ingresar.num_licencia" class="dropdown-item"  v-text="'Licencia: '+ingresar.num_licencia" v-bind:href="'/downloadLicencias/'+ingresar.foto_lic"></a>
                                                    </div>
                                                </td>
                                                <td class="td2" v-bind:style="{ color : ingresar.emp_constructora == 'Grupo Constructor Cumbres' ? '#2C36C2' : '#000000'}" v-text="ingresar.nombre_cliente"></td>
                                                <td class="td2" v-text="ingresar.nombre_vendedor"></td>
                                                <td class="td2" v-text="ingresar.proyecto"></td>
                                                <td class="td2" v-text="ingresar.etapa"></td>
                                                <td class="td2" v-text="ingresar.manzana"></td>
                                                <td class="td2" >
                                                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">{{ingresar.num_lote}}</a>
                                                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
                                                        <a v-if ="ingresar.foto_predial" class="dropdown-item" v-bind:href="'/downloadPredial/'+ingresar.foto_predial">Descargar predial</a>
                                                        <a v-if ="ingresar.foto_lic" class="dropdown-item" v-bind:href="'/downloadLicencias/'+ingresar.foto_lic">Descargar licencia</a>
                                                        <a v-if ="ingresar.foto_acta" class="dropdown-item" v-bind:href="'/downloadActa/'+ingresar.foto_acta">Descargar Acta de termino</a>
                                                    </div>
                                                </td>
                                                <td class="td2" v-text="ingresar.modelo"></td>
                                                <td class="td2" v-if="ingresar.interior" v-text="ingresar.calle + ' '+ ingresar.numero + ' '+ ingresar.interior"></td>
                                                <td class="td2" v-else v-text="ingresar.calle + ' '+ ingresar.numero"></td>
                                                <td class="td2" v-text="ingresar.avance_lote+ '%'"></td>
                                                <td class="td2" v-text="ingresar.fecha_status"></td>

                                                <td v-if="ingresar.avaluo_preventivo!='0000-01-01'" class="td2">
                                                    <span v-text="'$'+formatNumber(ingresar.resultado)"></span>
                                                        <!-- <button type="button" @click="abrirModal('avaluo',ingresar)" class="btn btn-success btn-sm" title="Actualizar avaluo">
                                                            <i class="fa fa-calendar-check-o"></i>
                                                        </button> -->
                                                </td>
                                                <td v-if="ingresar.avaluo_preventivo=='0000-01-01'" class="td2" v-text="'No aplica'"></td>

                                                <td @dblclick="abrirModal('fecha_recibido',ingresar)" v-if="ingresar.aviso_prev!='0000-01-01' && !ingresar.aviso_prev_venc && ingresar.aviso_prev!=null" class="td2" title="Doble click">
                                                    <a href="#" v-text="'Fecha solicitud: ' + this.moment(ingresar.aviso_prev).locale('es').format('DD/MMM/YYYY')"></a>
                                                </td>

                                                <td  @dblclick="abrirModal('fecha_recibido',ingresar)" v-else-if="ingresar.aviso_prev!='0000-01-01' && ingresar.aviso_prev_venc" class="td2">
                                                    
                                                    <span v-if = "ingresar.diferencia > 0" class="badge2 badge-danger" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(ingresar.aviso_prev_venc).locale('es').format('DD/MMM/YYYY')"></span>

                                                    <span v-if = "ingresar.diferencia < 0 && ingresar.diferencia >= -15 " class="badge2 badge-warning" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(ingresar.aviso_prev_venc).locale('es').format('DD/MMM/YYYY')"></span>

                                                    <span v-if = "ingresar.diferencia < -15 " class="badge2 badge-success" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(ingresar.aviso_prev_venc).locale('es').format('DD/MMM/YYYY')"></span>
                                                    
                                                </td>

                                            <td v-else-if="ingresar.aviso_prev=='0000-01-01' || ingresar.aviso_prev==null" class="td2" v-text="'No aplica'"></td>
                                            <td>
                                                <button v-if="ingresar.detenido == 0" type="button" @click="abrirModal('ingresar',ingresar)" class="btn btn-primary btn-sm" title="Ingresar">
                                                    <i class="fa fa-send-o"></i>
                                                </button>
                                                <label v-else> DETENIDO </label>
                                            </td>
                                            <td class="td2" v-text="ingresar.tipo_credito"></td>
                                            <td class="td2" v-text="ingresar.institucion"></td>
                                            <td class="td2" v-text="'$'+formatNumber(ingresar.precio_venta)"></td>
                                            <td class="td2" v-text="ingresar.credito_puente"></td>
                                            <td class="td2" v-text="this.moment(ingresar.ultimo_pagare).locale('es').format('DD/MMM/YYYY')"></td>
                                            <td class="td2" v-text="'$'+formatNumber(ingresar.saldo)"></td>
                                            
                                            <td class="td2">
                                                <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right" 
                                                    @click="abrirModal3(ingresar.folio)">Ver Observaciones</button>
                                            </td>
                                            </tr>                               
                                        </tbody>
                                    </table>  
                                </div>
                            </div>

                            <!-- Listado de autorizados -->
                            <div class="tab-pane fade" id="autorizado" role="tabpanel" aria-labelledby="autorizado-tab">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <select class="form-control" v-model="b_empresa" >
                                                <option value="">Empresa constructora</option>
                                                <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-10">
                                        <div class="input-group">
                                           
                                            <!--Criterios para el listado de busqueda -->
                                            <select class="form-control col-md-5" v-model="criterio" @change="selectFraccionamientos()">
                                                <option value="lotes.fraccionamiento_id">Proyecto</option>
                                                <option value="c.nombre">Cliente</option>
                                                <option v-if="rolId == 1 || rolId == 4 || rolId == 6" value="expedientes.gestor_id">Gestor</option>
                                                <option value="contratos.id"># Folio</option>
                                            </select>

                                            
                                            <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar" @change="selectEtapa(buscar)">
                                                <option value="">Seleccione</option>
                                                <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                            </select>

                                            <select class="form-control" v-else-if="criterio=='expedientes.gestor_id'" v-model="buscar"> 
                                                <option value="">Gestor</option>
                                                <option v-for="gestor in arrayGestores" :key="gestor.id" :value="gestor.id" v-text="gestor.nombre_gestor"></option>
                                            </select>

                                            <input v-else type="text"  v-model="buscar" @keyup.enter="listarAutorizados(1,buscar,b_etapa,b_manzana,b_lote,criterio), listarLiquidacion(1,buscar,b_etapa,b_manzana,b_lote,criterio), listarIngresoExp(1,buscar,b_etapa,b_manzana,b_lote,criterio),listarProgramacion(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="form-control" placeholder="Texto a buscar">
                                        
                                        </div>
                                        <div class="input-group">

                                            <select class="form-control col-md-6" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_etapa"> 
                                                <option value="">Etapa</option>
                                                <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                            </select>

                                          
                                        </div>
                                        <div class="input-group">
                                                                                        
                                            <input type="text" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_manzana" class="form-control" placeholder="Manzana a buscar">
                                            <input type="text" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_lote" class="form-control" placeholder="Lote a buscar">                                           

                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-10">
                                        
                                        <div class="input-group">
                                            <button type="submit" @click="listarAutorizados(1,buscar,b_etapa,b_manzana,b_lote,criterio), 
                                                    listarLiquidacion(1,buscar,b_etapa,b_manzana,b_lote,criterio), 
                                                    listarIngresoExp(1,buscar,b_etapa,b_manzana,b_lote,criterio),
                                                    listarProgramacion(1,buscar,b_etapa,b_manzana,b_lote,criterio)" 
                                                class="btn btn-primary"><i class="fa fa-search"></i> Buscar
                                            </button>
                                            <a :href="'/expediente/excelAutorizados?buscar=' + buscar + '&b_etapa=' + b_etapa + '&b_manzana=' + b_manzana + '&b_lote=' + 
                                                b_lote +  '&criterio=' + criterio+'&b_empresa='+b_empresa"  
                                                class="btn btn-success">
                                                <i class="fa fa-file-text"></i> Excel
                                            </a>
                                        
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr> 
                                                <th></th>
                                                <th class="td2"> <i class="fa fa-hand-paper-o"></i> Detener</th>
                                                <th>Celular</th>
                                                <th>Email</th>
                                                <th># Ref</th>
                                                <th>Cliente</th>
                                                <th>Asesor</th>
                                                <th>Proyecto</th>
                                                <th>Etapa</th>
                                                <th>Manzana</th>
                                                <th># Lote</th>
                                                <th>Modelo</th>
                                                <th>Dirección</th>
                                                <th>Avance obra</th>
                                                <th>Firma Contrato</th>
                                                <th>Resultado avaluo</th>
                                                <th>Aviso preventivo</th>
                                                <th>Tipo de Crédito</th>
                                                <th>Institución de Fin.</th>
                                                <th>Monto autorizado</th>
                                                <th>Fecha vigencia</th>
                                                <th>Valor de la vivienda</th>
                                                <th>Valor escriturar</th>
                                                <th>Crédito Puente</th>
                                                <th>Fecha ultimo pagare</th>
                                                <th>Saldo</th>
                                                <th>Inscripción Infonavit</th>
                                                <th>Observaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="preautorizados in arrayPreautorizados" :key="preautorizados.id" v-bind:style="{ backgroundColor : !preautorizados.detenido ? '#FFFFFF' : '#FF5F5F'}"> 
                                                <td class="td2">
                                                    <button v-if="preautorizados.detenido == 0" type="button" class="btn btn-danger btn-sm" @click="regresarExpediente(preautorizados.folio)">
                                                        <i class="fa fa-exclamation-triangle"></i>
                                                    </button>
                                                </td>
                                                <td class="td2">
                                                    <button v-if="preautorizados.detenido == 0" type="button" @click="detenerContrato(preautorizados.folio,1)" class="btn btn-danger btn-sm" title="Detener solicitud">
                                                        <i class="fa fa-hand-paper-o"></i>
                                                    </button>
                                                    <button v-if="preautorizados.detenido == 1" type="button" @click="continuarContrato(preautorizados.folio,0)" class="btn btn-success btn-sm" title="Reanudar solicitud">
                                                        <i class="fa fa-play"></i>
                                                    </button>
                                                </td>

                                                <td class="td2" >
                                                    <a title="Llamar" class="btn btn-dark" :href="'tel:'+preautorizados.celular"><i class="fa fa-phone fa-lg"></i></a>
                                                    <a title="Enviar whatsapp" class="btn btn-success" target="_blank" :href="'https://api.whatsapp.com/send?phone=+52'+preautorizados.celular+'&text=Hola'"><i class="fa fa-whatsapp fa-lg"></i></a>
                                                </td>
                                                <td class="td2" v-if="preautorizados.email_institucional == null"> 
                                                    <a title="Enviar correo" class="btn btn-secondary" :href="'mailto:'+preautorizados.email"> <i class="fa fa-envelope-o fa-lg"></i> </a>
                                                </td>
                                                <td class="td2" v-else> 
                                                    <a title="Enviar correo" class="btn btn-secondary" :href="'mailto:'+preautorizados.email+ ';'+preautorizados.email_institucional"> <i class="fa fa-envelope-o fa-lg"></i> </a>
                                                </td>
                                                
                                                <td class="td2">
                                                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">{{preautorizados.folio}}</a>
                                                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
                                                        <a class="dropdown-item" v-if="preautorizados.pdf != '' && preautorizados.pdf != null"  v-bind:href="'/downloadAvaluo/'+preautorizados.pdf">Avaluo</a>
                                                        <a class="dropdown-item" @click="abrirPDF(preautorizados.folio)">Estado de cuenta</a>
                                                        <a class="dropdown-item" target="_blank" v-bind:href="'/contratoCompraVenta/pdf/'+ preautorizados.folio">Contrato de compra venta</a>
                                                        <a class="dropdown-item" target="_blank" v-bind:href="'/cartaServicios/pdf/'+ preautorizados.folio">Carta de servicios</a>
                                                        <a class="dropdown-item" target="_blank" v-bind:href="'/serviciosTelecom/pdf/'+ preautorizados.folio">Servicios de telecomunición</a>
                                                        <a class="dropdown-item" v-bind:href="'/descargarReglamento/contrato/'+ preautorizados.folio">Reglamento de la etapa</a>
                                                        <a class="dropdown-item" @click="selectNombreArchivoModelo(preautorizados.folio)">Catalogo de especificaciones</a>
                                                        <a v-if="preautorizados.foto_predial" class="dropdown-item" v-bind:href="'/downloadPredial/'+ preautorizados.foto_predial">Predial</a>
                                                        <a v-if="preautorizados.num_licencia" class="dropdown-item"  v-text="'Licencia: '+preautorizados.num_licencia" v-bind:href="'/downloadLicencias/'+preautorizados.foto_lic"></a>
                                                    </div>
                                                </td>
                                                <td class="td2" v-bind:style="{ color : preautorizados.emp_constructora == 'Grupo Constructor Cumbres' ? '#2C36C2' : '#000000'}" v-text="preautorizados.nombre_cliente"></td>
                                                <td class="td2" v-text="preautorizados.nombre_vendedor"></td>
                                                <td class="td2" v-text="preautorizados.proyecto"></td>
                                                <td class="td2" v-text="preautorizados.etapa"></td>
                                                <td class="td2" v-text="preautorizados.manzana"></td>
                                                <td class="td2" >
                                                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">{{preautorizados.num_lote}}</a>
                                                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
                                                        <a v-if ="preautorizados.foto_predial" class="dropdown-item" v-bind:href="'/downloadPredial/'+preautorizados.foto_predial">Descargar predial</a>
                                                        <a v-if ="preautorizados.foto_lic" class="dropdown-item" v-bind:href="'/downloadLicencias/'+preautorizados.foto_lic">Descargar licencia</a>
                                                        <a v-if ="preautorizados.foto_acta" class="dropdown-item" v-bind:href="'/downloadActa/'+preautorizados.foto_acta">Descargar Acta de termino</a>
                                                    </div>
                                                </td>
                                                <td class="td2" v-text="preautorizados.modelo"></td>
                                                <td class="td2" v-text="preautorizados.calle + ' '+ preautorizados.numero + ' '+ preautorizados.interior"></td>
                                                <td class="td2" v-text="preautorizados.avance_lote+ '%'"></td>
                                                <td class="td2" v-text="preautorizados.fecha_status"></td>

                                                <td v-if="preautorizados.avaluo_preventivo!='0000-01-01'" class="td2">
                                                    <span v-text="'$'+formatNumber(preautorizados.resultado)"></span>
                                                        <!-- <button type="button" @click="abrirModal('avaluo',preautorizados)" class="btn btn-success btn-sm" title="Actualizar avaluo">
                                                            <i class="fa fa-calendar-check-o"></i>
                                                        </button> -->
                                                </td>
                                                <td v-if="preautorizados.avaluo_preventivo=='0000-01-01'" class="td2" v-text="'No aplica'"></td>

                                                <td @dblclick="abrirModal('fecha_recibido',preautorizados)" v-if="preautorizados.aviso_prev!='0000-01-01' && !preautorizados.aviso_prev_venc && preautorizados.aviso_prev!=null" class="td2" >
                                                    <a href="#" v-text="'Fecha solicitud: ' + this.moment(preautorizados.aviso_prev).locale('es').format('DD/MMM/YYYY')"></a>
                                                </td>

                                                <td  @dblclick="abrirModal('fecha_recibido',preautorizados)" v-else-if="preautorizados.aviso_prev!='0000-01-01' && preautorizados.aviso_prev_venc" class="td2">
                                                    
                                                    <span v-if = "preautorizados.diferencia > 0" class="badge2 badge-danger" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(preautorizados.aviso_prev_venc).locale('es').format('DD/MMM/YYYY')"></span>

                                                    <span v-if = "preautorizados.diferencia < 0 && preautorizados.diferencia >= -15 " class="badge2 badge-warning" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(preautorizados.aviso_prev_venc).locale('es').format('DD/MMM/YYYY')"></span>

                                                    <span v-if = "preautorizados.diferencia < -15 " class="badge2 badge-success" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(preautorizados.aviso_prev_venc).locale('es').format('DD/MMM/YYYY')"></span>
                                                    
                                                </td>

                                                <td v-else-if="preautorizados.aviso_prev=='0000-01-01' || preautorizados.aviso_prev==null" class="td2" v-text="'No aplica'"></td>
                                                <td class="td2" v-text="preautorizados.tipo_credito"></td>
                                                <td class="td2" v-text="preautorizados.institucion"></td>
                                                <td class="td2" v-text="'$'+formatNumber(preautorizados.credito_solic)"></td>

                                                <td class="td2" v-if="band==0" @dblclick="band=1" title="Doble click">
                                                    
                                                    <span v-if="!preautorizados.fecha_vigencia"></span>
                                                    <span v-else-if = "preautorizados.vigencia > 0" class="badge2 badge-danger" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(preautorizados.fecha_vigencia).locale('es').format('DD/MMM/YYYY')"></span>

                                                    <span v-else-if = "preautorizados.vigencia <= 0 && preautorizados.vigencia >= -15 " class="badge2 badge-warning" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(preautorizados.fecha_vigencia).locale('es').format('DD/MMM/YYYY')"></span>

                                                    <span v-else-if = "preautorizados.vigencia < -15 " class="badge2 badge-success" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(preautorizados.fecha_vigencia).locale('es').format('DD/MMM/YYYY')"></span>
                                                    
                                                </td>
                                                <td class="td2" v-if="band==1">
                                                    <input type="date"  @keyup.esc="band=0" v-on:change="actualizarVigencia(preautorizados.folio,$event.target.value)" :id="preautorizados.folio" :value="preautorizados.fecha_vigencia" class="form-control Fields" > 
                                                </td>
                                                
                                                <td class="td2" v-text="'$'+formatNumber(preautorizados.precio_venta)"></td>
                                                <td class="td2" v-text="'$'+formatNumber(preautorizados.valor_escrituras)"></td>
                                                <td class="td2" v-text="preautorizados.credito_puente"></td>
                                                <td class="td2" v-text="this.moment(preautorizados.ultimo_pagare).locale('es').format('DD/MMM/YYYY')"></td>
                                                <td class="td2" v-text="'$'+formatNumber(preautorizados.saldo)"></td>
                                               
                                                <template v-if="preautorizados.fecha_infonavit">
                                                    <td v-if="preautorizados.fecha_infonavit!='0000-01-01'" class="td2" v-text="this.moment(preautorizados.fecha_infonavit).locale('es').format('DD/MMM/YYYY')"></td>
                                                    <td v-if="preautorizados.fecha_infonavit=='0000-01-01'" class="td2" v-text="'No aplica'"></td>
                                                </template>
                                                <template v-else>
                                                    <td class="td2">
                                                        <button v-if="preautorizados.detenido == 0" type="button" @click="abrirModal('autorizado',preautorizados)" class="btn btn-success btn-sm" title="Inscribir Infonavit">
                                                            <i class="fa fa-calendar-check-o"></i>
                                                        </button>
                                                        <button v-if="preautorizados.detenido == 0" type="button" @click="noAplicaInfonavit(preautorizados.folio)" class="btn btn-danger btn-sm" title="No aplica">
                                                            <i class="fa fa-times-circle"></i>
                                                        </button>
                                                        <label v-else> DETENIDO </label>
                                                    </td>
                                                </template> 

                                                <td class="td2">
                                                    <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right" 
                                                        @click="abrirModal3(preautorizados.folio)">Ver Observaciones</button>
                                                </td>
                                            </tr>                               
                                        </tbody>
                                    </table>  
                                </div>
                            </div>

                            <!-- Listado de liquidacion -->
                            <div class="tab-pane fade" id="liquidacion" role="tabpanel" aria-labelledby="liquidacion-tab">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <select class="form-control" v-model="b_empresa" >
                                                <option value="">Empresa constructora</option>
                                                <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <!--Criterios para el listado de busqueda -->
                                            <select class="form-control col-md-5" v-model="criterio" @change="selectFraccionamientos()">
                                                <option value="lotes.fraccionamiento_id">Proyecto</option>
                                                <option value="c.nombre">Cliente</option>
                                                <option v-if="rolId == 1 || rolId == 4 || rolId == 6" value="expedientes.gestor_id">Gestor</option>
                                                <option value="contratos.id"># Folio</option>
                                            </select>
                                            
                                            <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar" @change="selectEtapa(buscar)">
                                                <option value="">Seleccione</option>
                                                <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                            </select>
                                            
                                            <select class="form-control" v-else-if="criterio=='expedientes.gestor_id'" v-model="buscar"> 
                                                <option value="">Gestor</option>
                                                <option v-for="gestor in arrayGestores" :key="gestor.id" :value="gestor.id" v-text="gestor.nombre_gestor"></option>
                                            </select>

                                            <input v-else type="text"  v-model="buscar" @keyup.enter="listarAutorizados(1,buscar,b_etapa,b_manzana,b_lote,criterio), listarLiquidacion(1,buscar,b_etapa,b_manzana,b_lote,criterio), listarIngresoExp(1,buscar,b_etapa,b_manzana,b_lote,criterio),listarProgramacion(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="form-control" placeholder="Texto a buscar">
                                        </div>
                                        <div class="input-group"  v-if="criterio=='lotes.fraccionamiento_id'">

                                            <select class="form-control col-md-6" v-model="b_etapa"> 
                                                <option value="">Etapa</option>
                                                <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                            </select>
                                           

                                            
                                        </div>
                                        <div class="input-group"  v-if="criterio=='lotes.fraccionamiento_id'">
                                            
                                            <input type="text" v-model="b_manzana" class="form-control" placeholder="Manzana a buscar">
                                            <input type="text" v-model="b_lote" class="form-control" placeholder="Lote a buscar">
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <button type="submit" @click="listarAutorizados(1,buscar,b_etapa,b_manzana,b_lote,criterio), 
                                                    listarLiquidacion(1,buscar,b_etapa,b_manzana,b_lote,criterio), 
                                                    listarIngresoExp(1,buscar,b_etapa,b_manzana,b_lote,criterio),
                                                    listarProgramacion(1,buscar,b_etapa,b_manzana,b_lote,criterio)" 
                                                class="btn btn-primary"><i class="fa fa-search"></i> Buscar
                                            </button>
                                            <a :href="'/expediente/excelLiquidacion?buscar=' + buscar + '&b_etapa=' + b_etapa + 
                                                    '&b_manzana=' + b_manzana + '&b_lote=' + b_lote +  '&criterio=' + criterio +
                                                    '&b_empresa='+b_empresa"  
                                                class="btn btn-success"><i class="fa fa-file-text"></i> Excel
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr> 
                                                <th></th>
                                                <th class="td2"> <i class="fa fa-hand-paper-o"></i> Detener</th>
                                                <th>Celular</th>
                                                <th>Email</th>
                                                <th># Ref</th>
                                                <th>Cliente</th>
                                                <th>Asesor</th>
                                                <th>Proyecto</th>
                                                <th>Etapa</th>
                                                <th>Manzana</th>
                                                <th># Lote</th>
                                                <th>Modelo</th>
                                                <th>Dirección</th>
                                                <th>Avance obra</th>
                                                <th>Firma Contrato</th>
                                                <th>Resultado avaluo</th>
                                                <th>Aviso preventivo</th>
                                                <th>Tipo de Crédito</th>
                                                <th>Institución de Fin.</th>
                                                <th>Monto autorizado</th>
                                                <th>Fecha vigencia</th>
                                                <th>Valor de la vivienda</th>
                                                <th>Valor escriturar</th>
                                                <th>Crédito Puente</th>
                                                <th>Fecha ultimo pagare</th>
                                                <th>Saldo</th>
                                                <th>Inscripción Infonavit</th>
                                                <th>Liquidación</th>
                                                <th>Entrega de vivienda</th>
                                                <th>Observaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="liquidacion in arrayLiquidados" :key="liquidacion.id" v-bind:style="{ backgroundColor : !liquidacion.detenido ? '#FFFFFF' : '#FF5F5F'}"> 
                                                <td class="td2">
                                                    <button v-if="!liquidacion.fecha_liquidacion && liquidacion.detenido == 0" type="button" class="btn btn-danger btn-sm" @click="regresarExpediente(liquidacion.folio)">
                                                        <i class="fa fa-exclamation-triangle"></i>
                                                    </button>
                                                </td>
                                                <td class="td2">
                                                    <button v-if="liquidacion.detenido == 0" type="button" @click="detenerContrato(liquidacion.folio,1)" class="btn btn-danger btn-sm" title="Detener solicitud">
                                                        <i class="fa fa-hand-paper-o"></i>
                                                    </button>
                                                    <button v-if="liquidacion.detenido == 1" type="button" @click="continuarContrato(liquidacion.folio,0)" class="btn btn-success btn-sm" title="Reanudar solicitud">
                                                        <i class="fa fa-play"></i>
                                                    </button>
                                                </td>
                                                <td class="td2" >
                                                    <a title="Llamar" class="btn btn-dark" :href="'tel:'+liquidacion.celular"><i class="fa fa-phone fa-lg"></i></a>
                                                    <a title="Enviar whatsapp" class="btn btn-success" target="_blank" :href="'https://api.whatsapp.com/send?phone=+52'+liquidacion.celular+'&text=Hola'"><i class="fa fa-whatsapp fa-lg"></i></a>
                                                </td>
                                                <td class="td2" v-if="liquidacion.email_institucional == null"> 
                                                    <a title="Enviar correo" class="btn btn-secondary" :href="'mailto:'+liquidacion.email"> <i class="fa fa-envelope-o fa-lg"></i> </a>
                                                </td>
                                                <td class="td2" v-else> 
                                                    <a title="Enviar correo" class="btn btn-secondary" :href="'mailto:'+liquidacion.email+ ';'+liquidacion.email_institucional"> <i class="fa fa-envelope-o fa-lg"></i> </a>
                                                </td>
                                                <td class="td2">
                                                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">{{liquidacion.folio}}</a>
                                                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
                                                        <a class="dropdown-item" v-if="liquidacion.pdf != '' && liquidacion.pdf != null"  v-bind:href="'/downloadAvaluo/'+liquidacion.pdf">Avaluo</a>
                                                        <a class="dropdown-item" @click="abrirPDF(liquidacion.folio)">Estado de cuenta</a>
                                                        <a class="dropdown-item" target="_blank" v-bind:href="'/contratoCompraVenta/pdf/'+ liquidacion.folio">Contrato de compra venta</a>
                                                        <a class="dropdown-item" target="_blank" v-bind:href="'/cartaServicios/pdf/'+ liquidacion.folio">Carta de servicios</a>
                                                        <a class="dropdown-item" target="_blank" v-bind:href="'/serviciosTelecom/pdf/'+ liquidacion.folio">Servicios de telecomunición</a>
                                                        <a class="dropdown-item" v-bind:href="'/descargarReglamento/contrato/'+ liquidacion.folio">Reglamento de la etapa</a>
                                                        <a class="dropdown-item" @click="selectNombreArchivoModelo(liquidacion.folio)">Catalogo de especificaciones</a>
                                                        <a v-if="liquidacion.foto_predial" class="dropdown-item" v-bind:href="'/downloadPredial/'+ liquidacion.foto_predial">Predial</a>
                                                        <a v-if="liquidacion.num_licencia" class="dropdown-item"  v-text="'Licencia: '+liquidacion.num_licencia" v-bind:href="'/downloadLicencias/'+liquidacion.foto_lic"></a>
                                                    </div>
                                                </td>
                                                <td class="td2" v-bind:style="{ color : liquidacion.emp_constructora == 'Grupo Constructor Cumbres' ? '#2C36C2' : '#000000'}" v-text="liquidacion.nombre_cliente"></td>
                                                <td class="td2" v-text="liquidacion.nombre_vendedor"></td>
                                                <td class="td2" v-text="liquidacion.proyecto"></td>
                                                <td class="td2" v-text="liquidacion.etapa"></td>
                                                <td class="td2" v-text="liquidacion.manzana"></td>
                                                <td class="td2" >
                                                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">{{liquidacion.num_lote}}</a>
                                                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
                                                        <a v-if ="liquidacion.foto_predial" class="dropdown-item" v-bind:href="'/downloadPredial/'+liquidacion.foto_predial">Descargar predial</a>
                                                        <a v-if ="liquidacion.foto_lic" class="dropdown-item" v-bind:href="'/downloadLicencias/'+liquidacion.foto_lic">Descargar licencia</a>
                                                        <a v-if ="liquidacion.foto_acta" class="dropdown-item" v-bind:href="'/downloadActa/'+liquidacion.foto_acta">Descargar Acta de termino</a>
                                                    </div>
                                                </td>
                                                <td class="td2" v-text="liquidacion.modelo"></td>
                                                <td class="td2" v-text="liquidacion.calle + ' '+ liquidacion.numero + ' '+ liquidacion.interior"></td>
                                                <td class="td2" v-text="liquidacion.avance_lote+ '%'"></td>
                                                <td class="td2" v-text="liquidacion.fecha_status"></td>

                                                <td v-if="liquidacion.avaluo_preventivo!='0000-01-01' && liquidacion.avaluo_preventivo!=null" class="td2">
                                                    <span v-text="'$'+formatNumber(liquidacion.resultado)"></span>
                                                </td>
                                                <td v-else class="td2" v-text="'No aplica'"></td>

                                                <td v-if="liquidacion.aviso_prev=='0000-01-01' || liquidacion.aviso_prev==null" class="td2" v-text="'No aplica'"></td>

                                                <td @dblclick="abrirModal('fecha_recibido',liquidacion)" v-else-if="liquidacion.aviso_prev!='0000-01-01' && !liquidacion.aviso_prev_venc" class="td2" title="Doble click">
                                                    <a href="#" v-text="'Fecha solicitud: ' + this.moment(liquidacion.aviso_prev).locale('es').format('DD/MMM/YYYY')"></a>
                                                </td>
                                                

                                                <td @dblclick="abrirModal('fecha_recibido',liquidacion)" v-else-if="liquidacion.aviso_prev!='0000-01-01' && liquidacion.aviso_prev_venc" class="td2">
                                                    
                                                    <span v-if = "liquidacion.diferencia > 0" class="badge2 badge-danger" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(liquidacion.aviso_prev_venc).locale('es').format('DD/MMM/YYYY')"></span>

                                                    <span v-if = "liquidacion.diferencia < 0 && liquidacion.diferencia >= -15 " class="badge2 badge-warning" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(liquidacion.aviso_prev_venc).locale('es').format('DD/MMM/YYYY')"></span>

                                                    <span v-if = "liquidacion.diferencia < -15 " class="badge2 badge-success" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(liquidacion.aviso_prev_venc).locale('es').format('DD/MMM/YYYY')"></span>
                                                    
                                                </td>

                                                <td class="td2" v-text="liquidacion.tipo_credito"></td>
                                                <td class="td2" v-text="liquidacion.institucion"></td>
                                                <td class="td2" v-text="'$'+formatNumber(liquidacion.credito_solic)"></td>

                                                <td class="td2" v-if="band==0" @dblclick="band=1" title="Doble click">
                                                    <span v-if="!liquidacion.fecha_vigencia"></span>
                                                    <span v-else-if = "liquidacion.vigencia > 0" class="badge2 badge-danger" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(liquidacion.fecha_vigencia).locale('es').format('DD/MMM/YYYY')"></span>

                                                    <span v-else-if = "liquidacion.vigencia <= 0 && liquidacion.vigencia >= -15 " class="badge2 badge-warning" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(liquidacion.fecha_vigencia).locale('es').format('DD/MMM/YYYY')"></span>

                                                    <span v-else-if = "liquidacion.vigencia < -15 " class="badge2 badge-success" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(liquidacion.fecha_vigencia).locale('es').format('DD/MMM/YYYY')"></span>
                                                    
                                                </td>
                                                <td class="td2" v-if="band==1">
                                                    <input type="date" @keyup.esc="band=0" v-on:change="actualizarVigencia(liquidacion.folio,$event.target.value)" :id="liquidacion.folio" :value="liquidacion.fecha_vigencia" class="form-control Fields" > 
                                                </td>
                                                
                                                <td class="td2" v-text="'$'+formatNumber(liquidacion.precio_venta)"></td>
                                                <td class="td2" v-text="'$'+formatNumber(liquidacion.valor_escrituras)"></td>
                                                <td class="td2" v-text="liquidacion.credito_puente"></td>
                                                <td class="td2" v-text="this.moment(liquidacion.ultimo_pagare).locale('es').format('DD/MMM/YYYY')"></td>
                                                <td class="td2" v-text="'$'+formatNumber(liquidacion.saldo)"></td>
                                                
                                               
                                               <template v-if="liquidacion.fecha_infonavit">
                                                    <td v-if="liquidacion.fecha_infonavit!='0000-01-01'" class="td2" v-text="this.moment(liquidacion.fecha_infonavit).locale('es').format('DD/MMM/YYYY')"></td>
                                                    <td v-if="liquidacion.fecha_infonavit=='0000-01-01'" class="td2" v-text="'No aplica'"></td>
                                                </template>
                                                <td class="td2">
                                                    <button v-if="!liquidacion.fecha_liquidacion && liquidacion.detenido == 0" title="Generar liquidación" type="button" class="btn btn-danger pull-right" 
                                                        @click="abrirModal('liquidacion',liquidacion)">Generar</button>
                                                    <button v-else-if="liquidacion.liquidado == 0 && liquidacion.fecha_liquidacion && liquidacion.detenido == 0" title="Intereses" type="button" class="btn btn-danger pull-right" 
                                                        @click="abrirModal('intereses',liquidacion)">Generar intereses</button>
                                                    <label v-else> DETENIDO </label>
            
                                                </td>
                                                <td class="td2">
                                                    <button v-if="liquidacion.postventa == 0 && liquidacion.detenido == 0" title="Solicitar entrega de vivienda" type="button" class="btn btn-warning pull-right" 
                                                        @click="abrirModal('solic_entrega',liquidacion)">Solicitar
                                                    </button>
                                                    <label v-else-if="liquidacion.postventa == 0 && liquidacion.detenido == 1">DETENIDO</label>
                                                    <label v-else></label>
                                                </td>
                                                <td class="td2">
                                                    <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right" 
                                                        @click="abrirModal3(liquidacion.folio)">Ver Observaciones</button>
                                                </td>
                                            </tr>                               
                                        </tbody>
                                    </table>   
                                </div>
                                <nav>
                                
                                </nav>
                            </div>

                            <!-- Listado de programacion de firma -->
                            <div class="tab-pane fade" id="programacion" role="tabpanel" aria-labelledby="programacion-tab">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <select class="form-control" v-model="b_empresa" >
                                                <option value="">Empresa constructora</option>
                                                <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <!--Criterios para el listado de busqueda -->
                                            <select class="form-control col-md-5" v-model="criterio" @change="selectFraccionamientos()">
                                                <option value="lotes.fraccionamiento_id">Proyecto</option>
                                                <option value="c.nombre">Cliente</option>
                                                <option v-if="rolId == 1 || rolId == 4 || rolId == 6" value="expedientes.gestor_id">Gestor</option>
                                                <option value="contratos.id"># Folio</option>
                                            </select>
                                            
                                            <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar" @change="selectEtapa(buscar)">
                                                <option value="">Seleccione</option>
                                                <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                            </select>

                                            <select class="form-control" v-else-if="criterio=='expedientes.gestor_id'" v-model="buscar"> 
                                                <option value="">Gestor</option>
                                                <option v-for="gestor in arrayGestores" :key="gestor.id" :value="gestor.id" v-text="gestor.nombre_gestor"></option>
                                            </select>

                                            <input v-else type="text"  v-model="buscar" @keyup.enter="listarAutorizados(1,buscar,b_etapa,b_manzana,b_lote,criterio), listarLiquidacion(1,buscar,b_etapa,b_manzana,b_lote,criterio), listarIngresoExp(1,buscar,b_etapa,b_manzana,b_lote,criterio),listarProgramacion(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="form-control" placeholder="Texto a buscar">
                                        
                                        </div>
                                        <div class="input-group" v-if="criterio=='lotes.fraccionamiento_id'">
                                            <select class="form-control col-md-6"  v-model="b_etapa"> 
                                                <option value="">Etapa</option>
                                                <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                            </select>
                                        </div>
                                        <div class="input-group" v-if="criterio=='lotes.fraccionamiento_id'">
                                            <input type="text"  v-model="b_manzana" class="form-control" placeholder="Manzana a buscar">
                                            <input type="text"  v-model="b_lote" class="form-control" placeholder="Lote a buscar">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <button type="submit" @click="listarAutorizados(1,buscar,b_etapa,b_manzana,b_lote,criterio), 
                                                    listarLiquidacion(1,buscar,b_etapa,b_manzana,b_lote,criterio), 
                                                    listarIngresoExp(1,buscar,b_etapa,b_manzana,b_lote,criterio),
                                                    listarProgramacion(1,buscar,b_etapa,b_manzana,b_lote,criterio)" 
                                                class="btn btn-primary"><i class="fa fa-search"></i> Buscar
                                            </button>
                                            <a :href="'/expediente/excelProgramacion?buscar=' + buscar + '&b_etapa=' + b_etapa + '&b_manzana=' + b_manzana + 
                                                    '&b_lote=' + b_lote +  '&criterio=' + criterio +'&b_empresa='+b_empresa"
                                                class="btn btn-success"><i class="fa fa-file-text"></i> Excel
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr> 
                                                <th>Celular</th>
                                                <th>Email</th>
                                                <th># Ref</th>
                                                <th>Cliente</th>
                                                <th>Asesor</th>
                                                <th>Proyecto</th>
                                                <th>Etapa</th>
                                                <th>Manzana</th>
                                                <th># Lote</th>
                                                <th>Modelo</th>
                                                <th>Dirección</th>
                                                <th>Avance obra</th>
                                                <th>Firma Contrato</th>
                                                <th>Resultado avaluo</th>
                                                <th>Aviso preventivo</th>
                                                <th>Ingreso expediente</th>
                                                <th>Tipo de Crédito</th>
                                                <th>Institución de Fin.</th>
                                                <th>Monto autorizado</th>
                                                <th>Fecha vigencia</th>
                                                <th>Valor de la vivienda</th>
                                                <th>Valor escriturar</th>
                                                <th>Inscripción Infonavit</th>
                                                <th>Liquidación</th>
                                                <th>Firma de Escrituras</th>
                                                <th>Fecha ultimo pagare</th>
                                                <th>Saldo</th>
                                                <th>Entrega de vivienda</th>
                                                <th>Proceso concluido</th>
                                                <th>Observaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="programacion in arrayProgramacion" :key="programacion.id">
                                                <td class="td2" >
                                                    <a title="Llamar" class="btn btn-dark" :href="'tel:'+programacion.celular"><i class="fa fa-phone fa-lg"></i></a>
                                                    <a title="Enviar whatsapp" class="btn btn-success" target="_blank" :href="'https://api.whatsapp.com/send?phone=+52'+programacion.celular+'&text=Hola'"><i class="fa fa-whatsapp fa-lg"></i></a>
                                                </td>
                                                <td class="td2" v-if="programacion.email_institucional == null"> 
                                                    <a title="Enviar correo" class="btn btn-secondary" :href="'mailto:'+programacion.email"> <i class="fa fa-envelope-o fa-lg"></i> </a>
                                                </td>
                                                <td class="td2" v-else> 
                                                    <a title="Enviar correo" class="btn btn-secondary" :href="'mailto:'+programacion.email+ ';'+programacion.email_institucional"> <i class="fa fa-envelope-o fa-lg"></i> </a>
                                                </td>
                                                
                                                <td class="td2">
                                                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">{{programacion.folio}}</a>
                                                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
                                                        <a class="dropdown-item" v-if="programacion.pdf != '' && programacion.pdf != null"  v-bind:href="'/downloadAvaluo/'+programacion.pdf">Avaluo</a>
                                                        <a class="dropdown-item" @click="abrirPDF(programacion.folio)">Estado de cuenta</a>
                                                        <a class="dropdown-item" target="_blank" v-bind:href="'/contratoCompraVenta/pdf/'+ programacion.folio">Contrato de compra venta</a>
                                                        <a class="dropdown-item" target="_blank" v-bind:href="'/cartaServicios/pdf/'+ programacion.folio">Carta de servicios</a>
                                                        <a class="dropdown-item" target="_blank" v-bind:href="'/serviciosTelecom/pdf/'+ programacion.folio">Servicios de telecomunición</a>
                                                        <a class="dropdown-item" v-bind:href="'/descargarReglamento/contrato/'+ programacion.folio">Reglamento de la etapa</a>
                                                        <a class="dropdown-item" @click="selectNombreArchivoModelo(programacion.folio)">Catalogo de especificaciones</a>
                                                        <a v-if="programacion.foto_predial" class="dropdown-item" v-bind:href="'/downloadPredial/'+ programacion.foto_predial">Predial</a>
                                                        <a v-if="programacion.num_licencia" class="dropdown-item"  v-text="'Licencia: '+programacion.num_licencia" v-bind:href="'/downloadLicencias/'+programacion.foto_lic"></a>
                                                    </div>
                                                </td>
                                                <td class="td2" v-bind:style="{ color : programacion.emp_constructora == 'Grupo Constructor Cumbres' ? '#2C36C2' : '#000000'}" v-text="programacion.nombre_cliente"></td>
                                                <td class="td2" v-text="programacion.nombre_vendedor"></td>
                                                <td class="td2" v-text="programacion.proyecto"></td>
                                                <td class="td2" v-text="programacion.etapa"></td>
                                                <td class="td2" v-text="programacion.manzana"></td>
                                                <td class="td2" >
                                                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">{{programacion.num_lote}}</a>
                                                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
                                                        <a v-if ="programacion.foto_predial" class="dropdown-item" v-bind:href="'/downloadPredial/'+programacion.foto_predial">Descargar predial</a>
                                                        <a v-if ="programacion.foto_lic" class="dropdown-item" v-bind:href="'/downloadLicencias/'+programacion.foto_lic">Descargar licencia</a>
                                                        <a v-if ="programacion.foto_acta" class="dropdown-item" v-bind:href="'/downloadActa/'+programacion.foto_acta">Descargar Acta de termino</a>
                                                    </div>
                                                </td>
                                                <td class="td2" v-text="programacion.modelo"></td>
                                                <td class="td2" v-text="programacion.calle + ' '+ programacion.numero + ' ' + programacion.interior"></td>
                                                <td class="td2" v-text="programacion.avance_lote + '%'"></td>
                                                <td class="td2" v-text="this.moment(programacion.fecha_status).locale('es').format('DD/MMM/YYYY')"></td>
                                                
                                                <td v-if="programacion.avaluo_preventivo!='0000-01-01'" class="td2">
                                                    <span v-text="'$'+formatNumber(programacion.resultado)"></span>
                                                        <!-- <button type="button" @click="abrirModal('avaluo',programacion)" class="btn btn-success btn-sm" title="Actualizar avaluo">
                                                            <i class="fa fa-calendar-check-o"></i>
                                                        </button> -->
                                                </td>
                                                <td v-if="programacion.avaluo_preventivo=='0000-01-01'" class="td2" v-text="'No aplica'"></td>

                                                <td v-if="programacion.aviso_prev=='0000-01-01' || programacion.aviso_prev==null" class="td2" v-text="'No aplica'"></td>
                                                <td @dblclick="abrirModal('fecha_recibido',programacion)" v-else-if="programacion.aviso_prev!='0000-01-01' && !programacion.aviso_prev_venc" class="td2" title="Doble click">
                                                    <a href="#" v-text="'Fecha solicitud: ' + this.moment(programacion.aviso_prev).locale('es').format('DD/MMM/YYYY')"></a>
                                                </td>

                                                <td  @dblclick="abrirModal('fecha_recibido',programacion)" v-else-if="programacion.aviso_prev!='0000-01-01' && programacion.aviso_prev_venc" class="td2">
                                                    
                                                    <span v-if = "programacion.diferencia > 0" class="badge2 badge-danger" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(programacion.aviso_prev_venc).locale('es').format('DD/MMM/YYYY')"></span>

                                                    <span v-if = "programacion.diferencia < 0 && programacion.diferencia >= -15 " class="badge2 badge-warning" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(programacion.aviso_prev_venc).locale('es').format('DD/MMM/YYYY')"></span>

                                                    <span v-if = "programacion.diferencia < -15 " class="badge2 badge-success" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(programacion.aviso_prev_venc).locale('es').format('DD/MMM/YYYY')"></span>
                                                    
                                                </td>

                                                <td class="td2" v-text="this.moment(programacion.fecha_ingreso).locale('es').format('DD/MMM/YYYY')"></td>
                                                <td class="td2" v-text="programacion.tipo_credito"></td>
                                                <td class="td2" v-text="programacion.institucion"></td>
                                                <td class="td2" v-text="'$'+formatNumber(programacion.credito_solic)"></td>
                                                
                                                <td class="td2" v-if="band==0" @dblclick="band=1">
                                                    
                                                    <span v-if="!programacion.fecha_vigencia"></span>
                                                    <span v-else-if = "programacion.vigencia > 0" class="badge2 badge-danger" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(programacion.fecha_vigencia).locale('es').format('DD/MMM/YYYY')"></span>

                                                    <span v-else-if = "programacion.vigencia <= 0 && programacion.vigencia >= -15 " class="badge2 badge-warning" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(programacion.fecha_vigencia).locale('es').format('DD/MMM/YYYY')"></span>

                                                    <span v-else-if = "programacion.vigencia < -15 " class="badge2 badge-success" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(programacion.fecha_vigencia).locale('es').format('DD/MMM/YYYY')"></span>
                                                    
                                                </td>
                                                <td class="td2" v-if="band==1">
                                                    <input type="date" @keyup.esc="band=0" v-on:change="actualizarVigencia(programacion.folio,$event.target.value)" :id="programacion.folio" :value="programacion.fecha_vigencia" class="form-control Fields" > 
                                                </td>

                                                <td class="td2" v-text="'$'+formatNumber(programacion.precio_venta)"></td>
                                                <td class="td2" v-text="'$'+formatNumber(programacion.valor_escrituras)"></td>
                                                
                                                <template>
                                                    <td v-if="programacion.fecha_infonavit!='0000-01-01'" class="td2" v-text="this.moment(programacion.fecha_infonavit).locale('es').format('DD/MMM/YYYY')"></td>
                                                    <td v-if="programacion.fecha_infonavit=='0000-01-01'" class="td2" v-text="'No aplica'"></td>
                                                </template>

                                                <td class="td2">
                                                  <a class="btn btn-warning btn-sm" target="_blank" v-bind:href="'/expediente/liquidacionPDF/'+programacion.folio">Imprimir</a>
                                                </td>

                                                
                                                <td class="td2"  @click="abrirModal('firma_esc_act',programacion)" v-if="programacion.fecha_firma_esc" v-text="this.moment(programacion.fecha_firma_esc).locale('es').format('DD/MMM/YYYY')"></td>
                                                <td class="td2" v-else>
                                                    <button title="Programar Firma" type="button" class="btn btn-warning pull-right" 
                                                    @click="abrirModal('firma_esc',programacion)">Generar</button>
                                                </td>

                                                <td class="td2" v-text="this.moment(programacion.ultimo_pagare).locale('es').format('DD/MMM/YYYY')"></td>
                                                <td class="td2" v-text="'$'+formatNumber(programacion.saldo)"></td>

                                                <td class="td2">
                                                    <button title="Solicitar entrega de vivienda" type="button" class="btn btn-warning pull-right" 
                                                        @click="abrirModal('solic_entrega',programacion)">Solicitar entrega
                                                    </button>
                                                </td>
                                                <!-- <td class="td2" v-else>Faltan Documentos
                                                </td> -->
                                                <td class="td2" v-if="programacion.saldo != 0" v-text="'Saldo Pendiente ó sin solicitud'"></td>
                                                <td class="td2" v-else v-text="'Concluido'"></td>

                                                <td class="td2">
                                                    <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right" 
                                                        @click="abrirModal3(programacion.folio)">Ver Observaciones</button>
                                                </td>
                                            </tr>                               
                                        </tbody>
                                    </table>  
                                </div>
                                <nav>
                                
                                </nav>
                            </div>

                            <!-- Listado de Historial -->
                            <div class="tab-pane fade" id="enviados" role="tabpanel" aria-labelledby="enviados-tab">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <select class="form-control" v-model="b_empresa" >
                                                <option value="">Empresa constructora</option>
                                                <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <select class="form-control col-md-5" v-model="criterio" @change="selectFraccionamientos()">
                                                <option value="lotes.fraccionamiento_id">Proyecto</option>
                                                <option value="c.nombre">Cliente</option>
                                                <option v-if="rolId == 1 || rolId == 4 || rolId == 6" value="expedientes.gestor_id">Gestor</option>
                                                <option value="contratos.id"># Folio</option>
                                            </select>
                                            
                                            <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar" @change="selectEtapa(buscar)">
                                                <option value="">Seleccione</option>
                                                <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                            </select>

                                            <select class="form-control" v-else-if="criterio=='expedientes.gestor_id'" v-model="buscar"> 
                                                <option value="">Gestor</option>
                                                <option v-for="gestor in arrayGestores" :key="gestor.id" :value="gestor.id" v-text="gestor.nombre_gestor"></option>
                                            </select>

                                            <input v-else type="text"  v-model="buscar" @keyup.enter="listarEnviados(1,buscar,b_etapa,b_manzana,b_lote,criterio),listarAutorizados(1,buscar,b_etapa,b_manzana,b_lote,criterio), listarLiquidacion(1,buscar,b_etapa,b_manzana,b_lote,criterio), listarIngresoExp(1,buscar,b_etapa,b_manzana,b_lote,criterio),listarProgramacion(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="form-control" placeholder="Texto a buscar">
                                        
                                        </div>
                                        <div class="input-group" v-if="criterio=='lotes.fraccionamiento_id'">

                                            <select class="form-control col-md-6"  v-model="b_etapa"> 
                                                <option value="">Etapa</option>
                                                <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                            </select>
                                        
                                        </div>
                                        <div class="input-group"  v-if="criterio=='lotes.fraccionamiento_id'">
                                            
                                            <input type="text" v-model="b_manzana" class="form-control" placeholder="Manzana a buscar">
                                            <input type="text" v-model="b_lote" class="form-control" placeholder="Lote a buscar">
                                        
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <button type="submit" @click="listarEnviados(1,buscar,b_etapa,b_manzana,b_lote,criterio),
                                                    listarAutorizados(1,buscar,b_etapa,b_manzana,b_lote,criterio), 
                                                    listarLiquidacion(1,buscar,b_etapa,b_manzana,b_lote,criterio), 
                                                    listarIngresoExp(1,buscar,b_etapa,b_manzana,b_lote,criterio),
                                                    listarProgramacion(1,buscar,b_etapa,b_manzana,b_lote,criterio)" 
                                                class="btn btn-primary"><i class="fa fa-search"></i> Buscar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr> 
                                                <th>Celular</th>
                                                <th>Email</th>
                                                <th># Ref</th>
                                                <th>Cliente</th>
                                                <th>Asesor</th>
                                                <th>Proyecto</th>
                                                <th>Etapa</th>
                                                <th>Manzana</th>
                                                <th># Lote</th>
                                                <th>Modelo</th>
                                                <th>Dirección</th>
                                                <th>Avance obra</th>
                                                <th>Firma Contrato</th>
                                                <th>Resultado avaluo</th>
                                                <th>Aviso preventivo</th>
                                                <th>Ingreso expediente</th>
                                                <th>Tipo de Crédito</th>
                                                <th>Institución de Fin.</th>
                                                <th>Monto autorizado</th>
                                                <th>Fecha vigencia</th>
                                                <th>Valor de la vivienda</th>
                                                <th>Valor escriturar</th>
                                                <th>Inscripción Infonavit</th>
                                                <th>Liquidación</th>
                                                <th>Firma de Escrituras</th>
                                                <th>Fecha ultimo pagare</th>
                                                <th>Saldo</th>
                                                <th>Observaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="programacion in arrayEnviados" :key="programacion.id"> 
                                                <td class="td2" >
                                                    <a title="Llamar" class="btn btn-dark" :href="'tel:'+programacion.celular"><i class="fa fa-phone fa-lg"></i></a>
                                                    <a title="Enviar whatsapp" class="btn btn-success" target="_blank" :href="'https://api.whatsapp.com/send?phone=+52'+programacion.celular+'&text=Hola'"><i class="fa fa-whatsapp fa-lg"></i></a>
                                                </td>
                                                <td class="td2" v-if="programacion.email_institucional == null"> 
                                                    <a title="Enviar correo" class="btn btn-secondary" :href="'mailto:'+programacion.email"> <i class="fa fa-envelope-o fa-lg"></i> </a>
                                                </td>
                                                <td class="td2" v-else> 
                                                    <a title="Enviar correo" class="btn btn-secondary" :href="'mailto:'+programacion.email+ ';'+programacion.email_institucional"> <i class="fa fa-envelope-o fa-lg"></i> </a>
                                                </td>
                                                
                                                <td class="td2">
                                                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">{{programacion.folio}}</a>
                                                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
                                                        <a class="dropdown-item" v-if="programacion.pdf != '' && programacion.pdf != null"  v-bind:href="'/downloadAvaluo/'+programacion.pdf">Avaluo</a>
                                                        <a class="dropdown-item" @click="abrirPDF(programacion.folio)">Estado de cuenta</a>
                                                        <a class="dropdown-item" target="_blank" v-bind:href="'/contratoCompraVenta/pdf/'+ programacion.folio">Contrato de compra venta</a>
                                                        <a class="dropdown-item" target="_blank" v-bind:href="'/cartaServicios/pdf/'+ programacion.folio">Carta de servicios</a>
                                                        <a class="dropdown-item" target="_blank" v-bind:href="'/serviciosTelecom/pdf/'+ programacion.folio">Servicios de telecomunición</a>
                                                        <a class="dropdown-item" v-bind:href="'/descargarReglamento/contrato/'+ programacion.folio">Reglamento de la etapa</a>
                                                        <a class="dropdown-item" @click="selectNombreArchivoModelo(programacion.folio)">Catalogo de especificaciones</a>
                                                        <a v-if="programacion.foto_predial" class="dropdown-item" v-bind:href="'/downloadPredial/'+ programacion.foto_predial">Predial</a>
                                                        <a v-if="programacion.num_licencia" class="dropdown-item"  v-text="'Licencia: '+programacion.num_licencia" v-bind:href="'/downloadLicencias/'+programacion.foto_lic"></a>
                                                    </div>
                                                </td>
                                                
                                                <td class="td2" v-text="programacion.nombre_cliente"></td>
                                                <td class="td2" v-text="programacion.nombre_vendedor"></td>
                                                <td class="td2" v-text="programacion.proyecto"></td>
                                                <td class="td2" v-text="programacion.etapa"></td>
                                                <td class="td2" v-text="programacion.manzana"></td>
                                                <td class="td2" >
                                                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">{{programacion.num_lote}}</a>
                                                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
                                                        <a v-if ="programacion.foto_predial" class="dropdown-item" v-bind:href="'/downloadPredial/'+programacion.foto_predial">Descargar predial</a>
                                                        <a v-if ="programacion.foto_lic" class="dropdown-item" v-bind:href="'/downloadLicencias/'+programacion.foto_lic">Descargar licencia</a>
                                                        <a v-if ="programacion.foto_acta" class="dropdown-item" v-bind:href="'/downloadActa/'+programacion.foto_acta">Descargar Acta de termino</a>
                                                    </div>
                                                </td>
                                                <td class="td2" v-text="programacion.modelo"></td>
                                                <td class="td2" v-text="programacion.calle + ' '+ programacion.numero + ' ' + programacion.interior"></td>
                                                <td class="td2" v-text="programacion.avance_lote + '%'"></td>
                                                <td class="td2" v-text="this.moment(programacion.fecha_status).locale('es').format('DD/MMM/YYYY')"></td>
                                                
                                                <td v-if="programacion.avaluo_preventivo!='0000-01-01'" class="td2">
                                                    <span v-text="'$'+formatNumber(programacion.resultado)"></span>
                                                        <!-- <button type="button" @click="abrirModal('avaluo',programacion)" class="btn btn-success btn-sm" title="Actualizar avaluo">
                                                            <i class="fa fa-calendar-check-o"></i>
                                                        </button> -->
                                                </td>
                                                <td v-if="programacion.avaluo_preventivo=='0000-01-01'" class="td2" v-text="'No aplica'"></td>

                                                <td v-if="programacion.aviso_prev=='0000-01-01'" class="td2" v-text="'No aplica'"></td>
                                                <td @dblclick="abrirModal('fecha_recibido',programacion)" v-if="programacion.aviso_prev!='0000-01-01' && !programacion.aviso_prev_venc" class="td2" v-text="'Fecha solicitud: ' 
                                                + this.moment(programacion.aviso_prev).locale('es').format('DD/MMM/YYYY')"></td>

                                                <td  @dblclick="abrirModal('fecha_recibido',programacion)" v-if="programacion.aviso_prev!='0000-01-01' && programacion.aviso_prev_venc" class="td2">
                                                    
                                                    <span v-if = "programacion.diferencia > 0" class="badge2 badge-danger" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(programacion.aviso_prev_venc).locale('es').format('DD/MMM/YYYY')"></span>

                                                    <span v-if = "programacion.diferencia < 0 && programacion.diferencia >= -15 " class="badge2 badge-warning" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(programacion.aviso_prev_venc).locale('es').format('DD/MMM/YYYY')"></span>

                                                    <span v-if = "programacion.diferencia < -15 " class="badge2 badge-success" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(programacion.aviso_prev_venc).locale('es').format('DD/MMM/YYYY')"></span>
                                                    
                                                </td>

                                                <td class="td2" v-text="this.moment(programacion.fecha_ingreso).locale('es').format('DD/MMM/YYYY')"></td>
                                                <td class="td2" v-text="programacion.tipo_credito"></td>
                                                <td class="td2" v-text="programacion.institucion"></td>
                                                <td class="td2" v-text="'$'+formatNumber(programacion.credito_solic)"></td>
                                                
                                                <td class="td2" v-if="band==0" @dblclick="band=1">
                                                    <span v-if="!programacion.fecha_vigencia"></span>
                                                    <span v-else-if = "programacion.vigencia > 0" class="badge2 badge-danger" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(programacion.fecha_vigencia).locale('es').format('DD/MMM/YYYY')"></span>

                                                    <span v-else-if = "programacion.vigencia <= 0 && programacion.vigencia >= -15 " class="badge2 badge-warning" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(programacion.fecha_vigencia).locale('es').format('DD/MMM/YYYY')"></span>

                                                    <span v-else-if = "programacion.vigencia < -15 " class="badge2 badge-success" v-text="'Fecha vencimiento: ' 
                                                    + this.moment(programacion.fecha_vigencia).locale('es').format('DD/MMM/YYYY')"></span>
                                                    
                                                </td>
                                                <td class="td2" v-if="band==1">
                                                    <input type="date" @keyup.esc="band=0" v-on:change="actualizarVigencia(programacion.folio,$event.target.value)" :id="programacion.folio" :value="programacion.fecha_vigencia" class="form-control Fields" > 
                                                </td>

                                                <td class="td2" v-text="'$'+formatNumber(programacion.precio_venta)"></td>
                                                <td class="td2" v-text="'$'+formatNumber(programacion.valor_escrituras)"></td>
                                                
                                                <template>
                                                    <td v-if="programacion.fecha_infonavit!='0000-01-01'" class="td2" v-text="this.moment(programacion.fecha_infonavit).locale('es').format('DD/MMM/YYYY')"></td>
                                                    <td v-if="programacion.fecha_infonavit=='0000-01-01'" class="td2" v-text="'No aplica'"></td>
                                                </template>

                                                <td class="td2">
                                                  <a class="btn btn-warning btn-sm" target="_blank" v-bind:href="'/expediente/liquidacionPDF/'+programacion.folio">Imprimir</a>
                                                </td>

                                                
                                                <td class="td2"  @click="abrirModal('firma_esc_act',programacion)" v-if="programacion.fecha_firma_esc" v-text="this.moment(programacion.fecha_firma_esc).locale('es').format('DD/MMM/YYYY')"></td>
                                                <td class="td2" v-else>
                                                    <button title="Programar Firma" type="button" class="btn btn-warning pull-right" 
                                                    @click="abrirModal('firma_esc',programacion)">Generar</button>
                                                </td>
                                                <td class="td2" v-text="this.moment(programacion.ultimo_pagare).locale('es').format('DD/MMM/YYYY')"></td>
                                                <td class="td2" v-text="'$'+formatNumber(programacion.saldo)"></td>

                                                <td class="td2">
                                                    <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right" 
                                                        @click="abrirModal3(programacion.folio)">Ver Observaciones</button>
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
                        <button class="btn btn-sm btn-default" data-toggle="modal" data-target="#manualId">Manual</button>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
         

            <!--Inicio modal-->
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
                            <!-- form para captura de fecha recibido -->
                            <form v-if="tipoAccion == 2" action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Fecha</label>
                                        <div class="col-md-4">
                                            <input type="date"  v-model="fecha_ingreso" class="form-control" >
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Valor a escriturar</label>
                                        <div class="col-md-4">
                                            <input type="text" maxlength="10" v-model="valor_escrituras" pattern="\d*" v-on:keypress="isNumber($event)" class="form-control" placeholder="Monto">
                                        </div>
                                        <div class="col-md-3">
                                            <h6 v-text="'$'+formatNumber(valor_escrituras)"></h6>
                                        </div>

                                    </div>

                                    <!-- Div para mostrar los errores que mande validerDepartamento -->
                                <div v-show="errorIngreso" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjIngreso" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>
                                    
                                    
                            </form>
                           

                            <!-- form para captura de fecha recibido -->
                            <form v-if="tipoAccion == 3" action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de recibido</label>
                                    <div class="col-md-4">
                                        <input type="date"  v-model="fecha_recibido" class="form-control" >
                                    </div>
                                </div>

                            </form>

                            <!-- form para captura de fecha recibido -->
                            <form v-if="tipoAccion == 4" action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de inscripción</label>
                                    <div class="col-md-4">
                                        <input type="date"  v-model="fecha_infonavit" class="form-control" >
                                    </div>
                                </div>

                                 <!-- Div para mostrar los errores que mande validerDepartamento -->
                                <div v-show="errorIngreso" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjIngreso" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>

                            </form>

                            <!-- form para solicitud de avaluo -->
                            <form v-if="tipoAccion == 5" action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha</label>
                                    <div class="col-md-4">
                                        <input type="date" v-model="fecha_concluido" class="form-control">
                                    </div>
                                </div>

                                 <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Valor</label>
                                    <div class="col-md-4">
                                        <input type="text" maxlength="10" v-model="resultado" pattern="\d*" v-on:keypress="isNumber($event)" class="form-control" placeholder="Monto">
                                    </div>
                                    <div class="col-md-3">
                                        <h6 v-text="'$'+formatNumber(resultado)"></h6>
                                    </div>
                                </div>
                            </form>
                            <!-- fin del form solicitud de avaluo -->
                            

                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==2 && valor_escrituras >0" class="btn btn-primary" @click="enviarIngreso()">Ingresar</button>
                            <button type="button" v-if="tipoAccion==4" class="btn btn-primary" @click="inscribirInfonavit()">Inscribir</button>
                            <button type="button" v-if="tipoAccion==5" class="btn btn-primary" @click="setFechaConcluido()">Guardar</button>
                            <a v-bind:href="'/expediente/solicitudPDF/' + id" v-if="tipoAccion==3" type="button" target="_blank" class="btn btn-primary">Imprimir</a>
                        </div>
                    </div>
                      <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal -->

            <!--Inicio modal Liquidación-->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal2}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- form para captura de fecha recibido -->
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Cliente</label>
                                        <div class="col-md-6">
                                            <input type="text" disabled v-model="cliente" class="form-control" placeholder="Cliente" >
                                        </div>
                                        <label class="col-md-1 form-control-label" for="text-input">Fecha</label>
                                        <div class="col-md-3">
                                            <input type="date" v-model="fecha_liquidacion" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Tipo de Credito</label>
                                        <div class="col-md-3">
                                            <input type="text" disabled v-model="credito" class="form-control" >
                                        </div>
                                        <label class="col-md-3 form-control-label" for="text-input">Inst. Financiamiento</label>
                                        <div class="col-md-3">
                                            <input type="text" disabled v-model="inst_fin" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Proyecto</label>
                                        <div class="col-md-3">
                                            <input type="text" disabled v-model="proyecto" class="form-control" >
                                        </div>

                                        <label class="col-md-1 form-control-label" for="text-input">Etapa</label>
                                        <div class="col-md-3">
                                            <input type="text" disabled v-model="etapa" class="form-control">
                                        </div>

                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Manzana</label>
                                        <div class="col-md-3">
                                            <input type="text" disabled v-model="manzana" class="form-control" >
                                        </div>

                                        <label class="col-md-1 form-control-label" for="text-input">Lote</label>
                                        <div class="col-md-2">
                                            <input type="text" disabled v-model="lote" class="form-control">
                                        </div>

                                    </div>

                                    <div class="form-group row">
                                       
                                        <label class="col-md-2 form-control-label" for="text-input">Fecha firma de contrato</label>
                                        <div class="col-md-3">
                                            <input type="date" disabled v-model="fecha_firma_contrato" class="form-control">
                                        </div>

                                    </div>

                                    <div class="form-group row line-separator"></div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Valor de venta</label>
                                        <div class="col-md-3">
                                            <h6 v-text="'$'+formatNumber(valor_venta)"></h6>
                                        </div>

                                        <label class="col-md-2 form-control-label" for="text-input">Valor de escrituración</label>
                                        <div class="col-md-2">
                                            <input maxlength="10" v-model="valor_escrituras" pattern="\d*" v-on:keypress="isNumber($event)" class="form-control">
                                        </div>

                                        <div class="col-md-2">
                                            <h6 v-text="'$'+formatNumber(valor_escrituras)"></h6>
                                        </div>

                                    </div>

                                    <div v-if="arrayGastos.length">
                                        <div class="form-group row"  v-for="gasto in arrayGastos" :key="gasto.id">
                                            <label class="col-md-2 form-control-label" for="text-input" v-text="gasto.concepto"></label>
                                            <div class="col-md-3">
                                                <h6>${{ formatNumber(gasto.costo)}}</h6>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Enganche</label>
                                        <div class="col-md-3">
                                            <h6 v-text="'$'+formatNumber(totalEnganghe)"></h6>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Pagado</label>
                                        <div class="col-md-3">
                                            <h6 v-text="'$'+formatNumber(pagado=pagos)"></h6>
                                        </div>

                                        <label v-if="avaluo" class="col-md-2 form-control-label" for="text-input">Resultado avaluo</label>
                                        <div class="col-md-2" v-if="avaluo">
                                            <h6 v-text="'$'+formatNumber(avaluo)"></h6>
                                        </div>

                                        
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Descuento</label>
                                        <div class="col-md-2">
                                            <input maxlength="10" v-model="descuento" pattern="\d*" v-on:keypress="isNumber($event)" class="form-control">
                                        </div>

                                        <div class="col-md-2">
                                            <h6 v-text="'$'+formatNumber(descuento)"></h6>
                                        </div>
                                    </div>

                                    <div class="form-group row" v-if="descuento > 0">
                                        <label class="col-md-2 form-control-label" for="text-input">Observacion del descuento</label>
                                        <div class="col-md-8">
                                            <textarea rows="2" cols="30" class="form-control" v-model="obs_descuento" placeholder="Observaciones"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Credito autorizado</label>
                                        <div class="col-md-3">
                                            <input v-model="monto_credito" @keyup.enter="updateMontoCredito()" pattern="\d*" v-on:keypress="isNumber($event)" class="form-control">
                                        </div>
                                        <div class="col-md-3">
                                            <h6>${{ formatNumber(netoCredito)}}</h6>
                                        </div>
                                    </div>

                                    <div class="form-group row" v-if="credito=='Alia2' || credito=='Respalda2' || credito=='INFONAVIT-FOVISSSTE' || credito == 'Fovissste para todos'">
                                        <label  class="col-md-2 form-control-label" for="text-input">Fovissste</label>
                                        <div class="col-md-2">
                                            <input type="text" pattern="\d*" v-model="fovissste" maxlength="10" v-on:keypress="isNumber($event)" class="form-control" >
                                        </div>
                                        <div class="col-md-2">
                                            <h6 v-text="'$'+formatNumber(fovissste)"></h6>
                                        </div>
                                    </div>

                                    <div class="form-group row" v-if="credito=='Cofinavit' || credito == 'COFINAVIT I.A'">
                                        <label  class="col-md-2 form-control-label" for="text-input">Infonavit</label>
                                        <div class="col-md-2">
                                            <input type="text" pattern="\d*" v-model="infonavit" maxlength="10" v-on:keypress="isNumber($event)" class="form-control" >
                                        </div>
                                        <div class="col-md-2">
                                            <h6 v-text="'$'+formatNumber(infonavit)"></h6>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input"><strong> Total a liquidar </strong></label>
                                        <div class="col-md-3">
                                            <h6><strong> ${{ formatNumber(total_liquidar=totalLiquidar)}} </strong></h6>
                                        </div>
                                    </div>

                                    <div class="form-group row line-separator"></div>

                                    
                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Notas</label>
                                        <div class="col-md-8">
                                            <textarea rows="2" cols="30" class="form-control" v-model="notas_liquidacion" placeholder="Notas extra"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row line-separator"></div>

                                    <div class="col-md-12" v-if="arrayPagares.length">
                                        <div class="form-group">
                                            <center> <h5>Pagares pendientes</h5> </center>
                                        </div>
                                    </div>  

                                    <div class="col-md-12">
                                        <div class="form-group row" v-if="arrayPagares.length">
                                            <div class="table-responsive col-md-12">
                                                <table class="table table-bordered table-striped table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th># Pago</th>
                                                            <th>Fecha de pago</th>
                                                            <th>Monto</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody >
                                                        <tr v-for="(pago,index) in arrayPagares" :key="pago.fecha_pago">
                                                            <td v-text="'Pago no. ' + parseInt(index+1)"></td>
                                                            <td v-text="this.moment(pago.fecha_pago).locale('es').format('DD/MMM/YYYY')"></td>
                                                            <td>
                                                                {{ pago.monto_pago | currency}}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div> 

                                    <!--<div class="form-group row line-separator" v-if="total_liquidar != 0"></div>

                                    <div class="form-group row" v-if="total_liquidar != 0">
                                        <label class="col-md-2 form-control-label" for="text-input">Fecha a liquidar</label>
                                        <div class="col-md-3">
                                            <input type="date" class="form-control" v-model="fecha_pagarefin">
                                        </div>

                                        <div class="col-md-2">
                                            <h6 v-text="'$'+formatNumber(total_liquidar)"></h6>
                                        </div>

                                    </div>-->



                                    <!-- Div para mostrar los errores que mande validerDepartamento -->
                                <div v-show="errorLiquidacion" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjLiquidacion" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>

                                
                                    
                                    
                            </form>
                            

                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" class="btn btn-primary" @click="generarLiquidacion()">Generar</button>
                        </div>
                    </div>
                      <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal -->

             <!--Inicio modal Intereses (Pagares)-->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal4}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal4()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- form para captura de fecha recibido -->
                            <!-- <form enctype="multipart/form-data" class="form-horizontal"> -->

                                    <div class="form-group row line-separator"></div>

                                   
                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input"><strong> Total a liquidar </strong></label>
                                        <div class="col-md-3">
                                            <h6><strong> ${{ formatNumber(total_liquidar1)}} </strong></h6>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Intereses Ordinarios (%)</label>
                                        <div class="col-md-2">
                                            <input type="text" maxlength="5" v-model="int_oridinario" class="form-control" placeholder="%" >
                                        </div>
                                        <label class="col-md-4 form-control-label" for="text-input">Fecha de inicio de intereses</label>
                                        <div class="col-md-3">
                                            <input type="date" pattern="\d*" maxlength="3" v-on:keypress="isNumber($event)" v-model="fecha_ini_interes" class="form-control" >
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Intereses Moratorios (%)</label>
                                        <div class="col-md-2">
                                            <input type="text" pattern="\d*" maxlength="3" v-on:keypress="isNumber($event)" v-model="int_moratorio" class="form-control" placeholder="%" >
                                        </div>
                                    </div>
                                    

                                    <div class="form-group row line-separator"></div>

                                    <div class="form-group row">
                                        <div class="col-md-12">  
                                            <center> <h5>Pagares</h5> </center>
                                        </div>
                                    </div>  

                                <div class="form-group row" v-if="fecha_pago!=''">
                                    <div class="col-md-12">
                                             <h6 style="text-align: right;">Restante: </h6>
                                             <h4 style="text-align: right;"><strong>${{ formatNumber(restante_pago)}}</strong></h4>
                                    </div> 
                                </div>

                                
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Fecha del pago</label>
                                        <div class="col-md-3">
                                            <input @change="mostrarRestante()" type="date" v-model="fecha_pago" class="form-control" >
                                        </div>
                                        
                                </div>

                                <div class="form-group row" v-if="fecha_pago!=''">
                                    <label class="col-md-2 form-control-label" for="text-input">Monto pago</label>
                                        <div class="col-md-3">
                                            <input  type="text" pattern="\d*" v-model="monto_pago" maxlength="10" v-on:keypress="isNumber($event)" class="form-control" >
                                        </div>
                                        <div class="col-md-4">
                                            <h6 style="color:#2271b3;" ><strong> Monto pago </strong></h6>
                                            <h6><strong>${{ formatNumber(monto_pago)}}</strong></h6>
                                        </div>
                                </div> 

                        
                                <div class="form-group row" v-if="restante_pago>0">
                                    <div class="col-md-1" v-if="monto_pago!=''">
                                            <button @click="agregarPago()" class="btn btn-success form-control btnagregar"><i class="icon-plus"></i></button>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group row" v-if="arrayPagos.length">
                                        <div class="table-responsive col-md-12">
                                            <table class="table table-bordered table-striped table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Opciones</th>
                                                        <th># Pago</th>
                                                        <th>Fecha de pago</th>
                                                        <th>Dias</th>
                                                        <th>Pendiente + Intereses</th>
                                                        <th>Monto</th>
                                                        <th>Restante</th>
                                                    </tr>
                                                </thead>
                                                <tbody >
                                                    <tr v-for="(pago,index) in arrayPagos" :key="pago.fecha_pago">
                                                        <td>
                                                            <button @click="eliminarPago(index)" type="button" class="btn btn-danger btn-sm">
                                                                <i class="icon-close"></i>
                                                            </button>
                                                           
                                                        </td>
                                                        <td v-text="'Pago no. ' + parseInt(index+1)"></td>
                                                        <td v-text="this.moment(pago.fecha_pago).locale('es').format('DD/MMM/YYYY')"></td>
                                                        <td v-text="pago.dias">
                                                            
                                                        </td>
                                                          <td>
                                                            {{pago.restanteAnterior | currency}}
                                                        </td>
                                                        <td>
                                                            {{ pago.monto_pago | currency}}
                                                        </td>
                                                        <td>
                                                            {{pago.restante | currency}}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div> 

                                    <div class="form-group row line-separator"></div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <center> <h5>Primer Aval</h5> </center>
                                        </div>
                                    </div>  

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                        <div class="col-md-6">
                                            <input type="text" v-model="nombre_aval" class="form-control" placeholder="Nombre" >
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Dirección</label>
                                        <div class="col-md-6">
                                            <input type="text" v-model="direccion_aval" class="form-control" placeholder="Direccion" >
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Telefono</label>
                                        <div class="col-md-6">
                                            <input type="text" pattern="\d*" maxlength="10" v-on:keypress="isNumber($event)" v-model="telefono_aval" class="form-control" placeholder="Telefono" >
                                        </div>
                                    </div>

                                    <div class="form-group row line-separator"></div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <center> <h5>Segundo Aval</h5> </center>
                                        </div>
                                    </div>  

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                        <div class="col-md-6">
                                            <input type="text" v-model="nombre_aval2" class="form-control" placeholder="Nombre" >
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Dirección</label>
                                        <div class="col-md-6">
                                            <input type="text" v-model="direccion_aval2" class="form-control" placeholder="Direccion" >
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Telefono</label>
                                        <div class="col-md-6">
                                            <input type="text" pattern="\d*" maxlength="10" v-on:keypress="isNumber($event)" v-model="telefono_aval2" class="form-control" placeholder="Telefono" >
                                        </div>
                                    </div>

                                 
                                    <!-- Div para mostrar los errores que mande validerDepartamento -->
                                <div v-show="errorLiquidacion" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjLiquidacion" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>
 
                                    
                            <!-- </form> -->
                            

                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal4()">Cerrar</button>
                            <button v-if="restante_pago == 0" type="button" class="btn btn-primary" @click="generarPagare()">Generar</button>
                        </div>
                    </div>
                      <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal -->

            <!--Inicio modal Firma de escrituras-->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal5}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal5()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- form para captura de fecha recibido -->
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Estado</label>
                                        <div class="col-md-3">
                                            <select class="form-control" v-model="estado" @change="selectCiudades(estado)">
                                                <option value=""> Seleccione </option>
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
                                            <!--<input type="text" v-model="estado" class="form-control" placeholder="Estado">-->
                                        </div>
                                        <label class="col-md-2 form-control-label" for="text-input">Ciudad</label>
                                        <div class="col-md-3">
                                            <select class="form-control" v-model="ciudad" @click="selectNotarias(estado,ciudad)">
                                                <option value=""> Seleccione </option>
                                                <option v-for="ciudades in arrayCiudades" :key="ciudades.municipio" :value="ciudades.municipio" v-text="ciudades.municipio"></option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Notarias</label>
                                        <div class="col-md-3">
                                            <select class="form-control" v-model="notaria_id" @change="mostrarDatosNotaria(notaria_id)">
                                                <option value=0> Seleccione </option>
                                                <option v-for="notarias in arrayNotarias" :key="notarias.id" :value="notarias.id" v-text="notarias.notaria"></option>
                                            </select>
                                        </div>

                                        <label class="col-md-2 form-control-label" for="text-input" v-if="notaria_id != 0">Notario</label>
                                        <div class="col-md-4" v-if="notaria_id != 0">
                                            <input type="text" disabled v-model="notario" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group row" v-if="notaria_id != 0">
                                        <label class="col-md-2 form-control-label" for="text-input">Direccion</label>
                                        <div class="col-md-6">
                                           <input type="text" v-model="direccion_firma" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group row line-separator"></div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Proyecto</label>
                                        <div class="col-md-3">
                                            <input type="text" disabled v-model="proyecto" class="form-control" >
                                        </div>

                                        <label class="col-md-1 form-control-label" for="text-input">Etapa</label>
                                        <div class="col-md-2">
                                            <input type="text" disabled v-model="etapa" class="form-control">
                                        </div>

                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Manzana</label>
                                        <div class="col-md-3">
                                            <input type="text" disabled v-model="manzana" class="form-control" >
                                        </div>

                                        <label class="col-md-1 form-control-label" for="text-input">Lote</label>
                                        <div class="col-md-2">
                                            <input type="text" disabled v-model="lote" class="form-control">
                                        </div>

                                    </div>

                                    <div class="form-group row line-separator"></div>

                                    

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input"><strong> Valor de venta </strong></label>
                                        <div class="col-md-3">
                                            <h6><strong> ${{ formatNumber(valor_venta)}} </strong></h6>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input"> Credito autorizado </label>
                                        <div class="col-md-3">
                                            <h6> ${{ formatNumber(monto_credito)}} </h6>
                                        </div>
                                    </div>

                                    <div class="form-group row" v-if="infonavit!=0">
                                        <label class="col-md-2 form-control-label" for="text-input"> Infonavit </label>
                                        <div class="col-md-3">
                                            <h6> ${{ formatNumber(infonavit)}} </h6>
                                        </div>
                                    </div>

                                    <div class="form-group row" v-if="fovissste!=0">
                                        <label class="col-md-2 form-control-label" for="text-input"> Fovissste </label>
                                        <div class="col-md-3">
                                            <h6> ${{ formatNumber(fovissste)}} </h6>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input"> Diferencia </label>
                                        <div class="col-md-3">
                                            <h6> ${{ formatNumber(diferencia)}} </h6>
                                        </div>
                                    </div>

                                    <div class="form-group row line-separator"></div>

                                    <div class="form-group row">  
                                        <label class="col-md-3 form-control-label" for="text-input">Fecha firma de escrituras</label>
                                        <div class="col-md-3">
                                            <input type="date" v-model="fecha_firma_esc" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group row">  
                                        <label class="col-md-3 form-control-label" for="text-input">Hora</label>
                                        <div class="col-md-3">
                                            <input type="time" v-model="hora_firma" class="form-control">
                                        </div>
                                    </div>


                                    <!-- Div para mostrar los errores que mande validerDepartamento -->
                                <div v-show="errorProgramacionFirma" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjProgramacion" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>

                                
                                    
                                    
                            </form>
                            

                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal5()">Cerrar</button>
                            <button v-if="tipoAccion==1" type="button" class="btn btn-primary" @click="generarInstNot()">Generar</button>
                            <button v-if="tipoAccion==2" type="button" class="btn btn-primary" @click="generarInstNot()">Guardar</button>
                        </div>
                    </div>
                      <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal -->

            <!--Inicio modal Solicitar Entrega -->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal6}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- form para captura de fecha recibido -->
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Observacion</label>
                                    <div class="col-md-6">
                                         <textarea rows="3" cols="30" v-model="observacion" class="form-control" placeholder="Observacion"></textarea>
                                    </div>
                                </div>                             
                            </form>
                            

                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="observacion != ''" class="btn btn-primary" @click="SolicitarEntrega()"> Solicitar </button>
                        </div>
                    </div>
                      <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal -->

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

            <!-- Manual -->
            <div class="modal fade" id="manualId" tabindex="-1" role="dialog" aria-labelledby="manualIdTitle" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="manualIdTitle">Manual</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>
                            Dentro del módulo de seguimiento de tramite usted podrá dar el seguimiento a la venta de un lote, 
                            además en caso de ser necesario usted podrá detener o regresar el registro de lote al modulo de 
                            "Expediente" en caso de que sea necesario con el uso de los botones 
                            <button type="button" class="btn btn-danger btn-sm" title="Detener solicitud">
                                <i class="fa fa-hand-paper-o"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm">
                                <i class="fa fa-exclamation-triangle"></i>
                            </button>.
                        </p>
                        <p>
                            <strong>Por ingresar:</strong> dentro de la pestaña de por ingresar podrá encontrar todos aquellos lotes que fueron 
                            enviados desde el módulo de expediente (vea modulo <strong>“Gestoría -> Expediente”</strong>), además, 
                            podrá realizar el cambio de la fecha del “aviso preventivo en caso de que así lo desee, 
                            también podrá ver o agregar observaciones e ingresar el expediente a la pestaña de 
                            “autorizados” con el botón de la columna “ingresar expediente” .
                        </p>
                        <p>
                            <strong>Autorizados:</strong> dentro del módulo de autorizados usted podrá.
                            <ul>
                                <li>Actualizar la fecha de aviso preventivo.</li>
                                <li>Cambiar la fecha de vigencia.</li>
                                <li>Realizar la inscripción a Infonavit.</li>
                                <li>Agregar observaciones.</li>
                            </ul>
                            Una vez que el lotea sea enviado tenga inscripción a Infonavit (en caso de que no 
                            aplique se debe indicar que no aplica) será enviado a la pestaña de “liquidación”.
                        </p>
                        <p>
                            <strong>Liquidación:</strong> dentro del módulo de liquidación usted podrá.
                            <ul>
                                <li>Actualizar la fecha de aviso preventivo.</li>
                                <li>Cambiar la fecha de vigencia.</li>
                                <li>Liquidar</li>
                                <li>Generar intereses.</li>
                                <li>Solicitar la entrega de vivienda.</li>
                                <li>Agregar observaciones.</li>
                            </ul>
                            En caso de que al generar la liquidación quede algún saldo pendiente debe tomar en 
                            cuenta que el sistema pedirá generar intereses (si aplica), para esto, aparecerá un 
                            botón con la leyenda “Generar Intereses” que le permitirá generar intereses por el 
                            adeudo pendiente (si aplica), al dar clic dentro del botón vera una ventana que le 
                            indicara el moto y le permitirá llenar los campos según sea necesario 
                            (en caso de que no genere intereses solo deje en 0% el interés), al finalizar el registro 
                            será enviado a la pestaña de programación de firma. <br>
                            En caso de que el saldo pendiente sea 0, al generar la liquidación el registro del lote
                             será enviado a la pestaña de programación de firma.
                        </p>
                        <p>
                            <strong>Programación de firma:</strong> dentro del módulo de programación de firma usted podrá.
                            <ul>
                                <li>Actualizar la fecha de aviso preventivo.</li>
                                <li>Cambiar la fecha de vigencia.</li>
                                <li>Imprimir liquidación.</li>
                                <li>Programar la fecha de firma de escritura.</li>
                                <li>Solicitar la entrega de vivienda.</li>
                                <li>Agregar observaciones.</li>
                            </ul>
                            Una vez que sea programado la fecha de la firma de escritura el registro del lote será 
                            enviado a la pestaña de Historial.
                        </p>
                        <p>
                            <strong>Historial:</strong> dentro de la pestaña historial usted podrá.
                            <ul>
                                <li>Cambiar la fecha de vigencia.</li>
                                <li>Imprimir liquidación.</li>
                                <li>Agregar observaciones.</li>
                            </ul>
                            Adema podrá ver el historial a todos los registros de lotes que se entregaron con anterioridad, 
                            además podrá ver el registro del lote en el módulo de <strong>“Obra -> Viviendas por entregar”</strong>. 
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
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
        props:{
            rolId:{type: String}
        },
        data(){
            return{
                arrayPreautorizados: [],
                arrayRechazados: [],
                arrayAutorizados: [],
                arrayLiquidados: [],
                arrayProgramacion: [],
                arrayPorIngresar:[],
                arrayObservacion:[],
                arrayPagares:[],
                totalEnganghe:[],
                totalRestante:[],

                arrayGestores:[],

                arrayEnviados:[],

                arrayFraccionamientos:[],
                arrayEtapas:[],
                arrayGastos:[],

                arrayPagos: [],
                arrayCiudades:[],
                arrayNotarias:[],
                arrayDatosNotaria: [],

                errorIngreso:0,
                errorMostrarMsjIngreso:[],

                errorLiquidacion: 0,
                errorMostrarMsjLiquidacion: [],

                errorProgramacionFirma: 0,
                errorMostrarMsjProgramacion: [],

                band: 0,
                
                //variables para filtros de Por ingresar
                criterio:'lotes.fraccionamiento_id',
                buscar:'',
                b_etapa:'',
                b_manzana:'',
                b_lote:'',

                //variables para filtros de Preautorizados
                criterio:'lotes.fraccionamiento_id',
                buscar:'',
                b_etapa:'',
                b_manzana:'',
                b_lote:'',

                //variables para filtros de Rechazados
                criterio_rechazados:'lotes.fraccionamiento_id',
                buscar_rechazados:'',
                b_etapa_rechazados:'',
                b_manzana_rechazados:'',
                b_lote_rechazados:'',

                fecha_recibido:'',
                id:0,
                tipoAccion: 0,
                fecha_ingreso:'',
                valor_escrituras:'',
                valor_venta:'',
                totalGastos:0,
                totalIntOrd:0,
                rest_real: 0,

                fecha_infonavit:'',
                fecha_liquidacion:'',
                fecha_firma_contrato:'',
                fecha_pagarefin:'',
                monto_pagare:0,
                cliente:'',
                credito:'',
                inst_fin:'',
                proyecto:'',
                etapa:'',
                manzana:'',
                lote:'',
                descuento:0,
                obs_descuento:'',
                notas_liquidacion:'',
                pagado:0,
                monto_credito:0,
                infonavit:0,
                fovissste:0,
                avaluo:0,
                total_liquidar:0,
                total_liquidar1:0,
                totalCredito: 0,
                estado:'',
                ciudad:'',
                notaria_id:0,
                notaria:'',
                direccion_firma:'',
                fecha_firma_esc: '',
                notario:'',
                diferencia: 0,
                hora_firma:'',

                int_oridinario:0,
                int_moratorio:0,
                fecha_ini_interes: '',
                fecha_pago: '',
                monto_pago: 0,
                restante_pago: 0,
                nombre_aval: '',
                direccion_aval: '',
                telefono_aval: '',

                nombre_aval2: '',
                direccion_aval2: '',
                telefono_aval2: '',
                dias: 0,
                interes: 0,
            
                fecha_concluido: '',
                resultado: 0,
                avaluoId:0,

               
                modal:0,
                modal2:0,
                modal3 :0,
                modal4:0,
                modal5:0,
                modal6:0,
                tituloModal:'',
                tituloModal3:'Observaciones',
                observacion:'',
                contadorIngresar : 0,
                contadorAutorizados : 0,
                contadorLiquidacion : 0,
                contadorProgramacion : 0,
                contadorEnviados: 0,
                pagos:0,

                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                b_empresa:'',
                empresas:[],
               
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
            totalLiquidar: function(){
                var neto_credito =0;
                    neto_credito = parseFloat(this.valor_venta) - parseFloat(this.descuento) + parseFloat(this.totalGastos) - parseFloat(this.monto_credito) - 
                    parseFloat(this.infonavit) - parseFloat(this.fovissste) - parseFloat(this.pagos); 
                return neto_credito;
            },

             netoCredito: function(){
                var total =0;
                    total = parseFloat(this.infonavit) + parseFloat(this.fovissste) + parseFloat(this.monto_credito);
                return total; 
            },

        },

        
        methods : {
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

            mostrarDatosNotaria(notaria){
                let me = this;
                me.arrayDatosNotaria=[];
                var url = '/select_datos_notaria?id=' + notaria;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                        me.arrayDatosNotaria = respuesta.notarias;
                        me.notaria = me.arrayDatosNotaria[0]['notaria'];
                        me.notario = me.arrayDatosNotaria[0]['titular'];
                        me.direccion_firma = me.arrayDatosNotaria[0]['direccion'] + ', ' + me.arrayDatosNotaria[0]['colonia'] + ', C.P. ' + me.arrayDatosNotaria[0]['cp'];

                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            abrirPDF(id){
                const win = window.open('/estadoCuenta/estadoPDF/'+id, '_blank');
                win.focus();
            },

            regresarExpediente(folio){
                swal({
                title: '¿Desea regresar este expediente a integración?',
                text: "Esta acción no se puede revertir!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si, regresar!'
                }).then((result) => {
                if (result.value) {
                    let me = this;

                axios.delete('/expediente/regresarExpediente', 
                        {params: {'folio': folio}}).then(function (response){
                        swal(
                        'Listo!',
                        'El expediente ha sido regresado a integración.',
                        'success'
                        )
                        me.listarIngresoExp(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                        me.listarAutorizados(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                        me.listarLiquidacion(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                        me.listarProgramacion(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
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

            detenerContrato(id,detenido){
               
                let me = this;
                 
                //Con axios se llama el metodo update de LoteController
                axios.put('/contrato/cambiarProceso',{
                    'id':id,
                    'detenido' : detenido

                }).then(function (response){
                    me.listarIngresoExp(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    me.listarAutorizados(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    me.listarLiquidacion(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    me.listarProgramacion(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
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
                    me.listarIngresoExp(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    me.listarAutorizados(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    me.listarLiquidacion(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    me.listarProgramacion(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
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

            SolicitarEntrega(){
                if(this.proceso==true){
                    return;
                }
                this.proceso=true;
                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/entrega/registrar',{
                    'id': this.id,
                    'comentario': this.observacion
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal();
                    me.listarIngresoExp(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    me.listarAutorizados(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    me.listarLiquidacion(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    me.listarProgramacion(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    
                    const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                    });

                    toast({
                    type: 'success',
                    title: 'Solicitud enviada correctamente'
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

            mostrarRestante(){
                if(this.arrayPagos.length == 0){
                var Restante = this.total_liquidar1;
                var a = moment(this.fecha_pago);
                var b = moment(this.fecha_ini_interes);
                this.dias = a.diff(b, 'days'); //[days, years, months, seconds, ...]
                var intereses = (this.int_oridinario / 100) * (this.dias/30) * (this.total_liquidar1);
                this.interes = Math.round(intereses*100)/100;
                
                
                Restante += this.interes;
                Restante = Math.round(Restante*100)/100;
                this.restante_pago = Restante;

                }else{
                    var b = this.arrayPagos[this.arrayPagos.length-1].fecha_pago;
                    var a = moment(this.fecha_pago);
                    this.dias = a.diff(b, 'days'); //[days, years, months, seconds, ...]
                    if(this.dias == 0){
                        this.restante_pago = this.rest_real;
                    }
                    var Restante =this.restante_pago;
                    var intereses = (this.int_oridinario / 100) * (this.dias/30) * (Restante);
                    this.interes = Math.round(intereses*100)/100;
                   
                    Restante += this.interes;
                    this.restante_pago = Restante;

                }
            },

            calcularRestante(){
                if(this.arrayPagos.length == 0){
                    var Restante = this.total_liquidar1;
                    var a = moment(this.fecha_pago);
                    var b = moment(this.fecha_ini_interes);
                    this.dias = a.diff(b, 'days'); //[days, years, months, seconds, ...]
                    var intereses = (this.int_oridinario / 100) * (this.dias/30) * (this.total_liquidar1);
                    this.interes = Math.round(intereses*100)/100;
                    
                    if(this.dias > 0){
                        Restante += this.interes;
                    }
                    
                    Restante = Math.round(Restante*100)/100;
                    this.rest_real = Restante;
                    // this.restante_pago = Restante;
                    return Restante;

                }else{
                    var b = this.arrayPagos[this.arrayPagos.length-1].fecha_pago;
                    var a = moment(this.fecha_pago);
                    this.dias = a.diff(b, 'days'); //[days, years, months, seconds, ...]
                    var Restante =this.restante_pago;
                    
                    Restante = Math.round(Restante*100)/100;
                    this.rest_real = Restante;
                    return Restante;

                }
            },

            agregarPago(){
                let me = this;
                if(me.monto_pago == 0 || me.monto_pago=='' || me.fecha_pago==''){

                }else{
                    if(me.monto_pago > Math.round(me.restante_pago*100)/100){
                         swal({
                        type: 'error',
                        title: 'Error',
                        text: 'El monto supera al restante',
                        });
                        }
                    else
                    {
                        if(me.encuentraFecha(me.fecha_pago)){
                            swal({
                            type: 'error',
                            title: 'Error',
                            text: 'La fecha de pago ya se enceuntra o es menor',
                            })
                        }
                        else{
                            me.restante_pago = me.calcularRestante();
                            me.arrayPagos.push({
                            monto_pago: me.monto_pago,
                            fecha_pago: me.fecha_pago,
                            restanteAnterior: me.restante_pago,
                            dias: me.dias,
                            restante: me.restante_pago - me.monto_pago,
                            });
                            me.restante_pago -= me.monto_pago;
                        
                            me.monto_pago = 0;
                        }
                    }
                }

            },

            eliminarPago(index){
                let me = this;      

                me.arrayPagos.splice(index,1);

                    if(index != 0 ){
                        if(me.arrayPagos.length > 1)
                            for(var i=0;i<me.arrayPagos.length;i++){
                                var b = me.arrayPagos[i].fecha_pago;
                                var a = moment(me.arrayPagos[i+1].fecha_pago);
                                me.arrayPagos[i+1].dias = a.diff(b, 'days'); //[days, years, months, seconds, ...]
                                var Restante = me.arrayPagos[i].restante;
                                var intereses = (me.int_oridinario / 100) * (me.arrayPagos[i+1].dias/30) * (Restante);
                                var interes1 = Math.round(intereses*100)/100;
                                Restante += interes1;
                                Restante = Math.round(Restante*100)/100;
                                me.arrayPagos[i+1].restanteAnterior = Restante;
                                me.arrayPagos[i+1].restante = me.arrayPagos[i+1].restanteAnterior - me.arrayPagos[i+1].monto_pago;
                                me.restante_pago =  me.arrayPagos[i+1].restante;
                            }
                            else{
                                me.restante_pago = me.arrayPagos[0].restante;
                            }
                        
                    }else{
                        if(me.arrayPagos.length == 0){
                             var Restante = this.total_liquidar1;
                            var a = moment(this.fecha_pago);
                            var b = moment(this.fecha_ini_interes);
                            this.dias = a.diff(b, 'days'); //[days, years, months, seconds, ...]
                            var intereses = (this.int_oridinario / 100) * (this.dias/30) * (this.total_liquidar1);
                            this.interes = Math.round(intereses*100)/100;
                            
                            
                            Restante += this.interes;
                            Restante = Math.round(Restante*100)/100;
                            me.restante_pago = Restante;
                        }
                        else{
                            var b = me.fecha_ini_interes
                            var a = moment(me.arrayPagos[0].fecha_pago);
                            me.arrayPagos[0].dias = a.diff(b, 'days');
                            var Restante = me.total_liquidar1;
                            var intereses = (me.int_oridinario / 100) * (me.arrayPagos[0].dias/30) * (Restante);
                            var interes1 = Math.round(intereses*100)/100;
                            Restante += interes1;
                            Restante = Math.round(Restante*100)/100;
                            me.arrayPagos[0].restanteAnterior = Restante;
                            me.arrayPagos[0].restante = me.arrayPagos[0].restanteAnterior - me.arrayPagos[0].monto_pago;
                        
                            for(var i=0;i<me.arrayPagos.length;i++){
                                var b = me.arrayPagos[i].fecha_pago;
                                var a = moment(me.arrayPagos[i+1].fecha_pago);
                                me.arrayPagos[i+1].dias = a.diff(b, 'days'); //[days, years, months, seconds, ...]
                                var Restante = me.arrayPagos[i].restante;
                                var intereses = (me.int_oridinario / 100) * (me.arrayPagos[i+1].dias/30) * (Restante);
                                var interes1 = Math.round(intereses*100)/100;
                                Restante += interes1;
                                Restante = Math.round(Restante*100)/100;
                                me.arrayPagos[i+1].restanteAnterior = Restante;
                                me.arrayPagos[i+1].restante = me.arrayPagos[i+1].restanteAnterior - me.arrayPagos[i+1].monto_pago;
                                me.restante_pago =  me.arrayPagos[i+1].restante;
                            }
                        }

                }
           
            },

            encuentraFecha(fecha){
                var sw=0;
                for(var i=0;i<this.arrayPagos.length;i++)
                {
                    if(this.arrayPagos[i].fecha_pago == fecha || this.arrayPagos[i].fecha_pago > fecha)
                    {
                        sw=true;
                    }

                }

                return sw;
            },

            enviarIngreso(){
                if(this.validarIngreso()){
                    return;
                }
                
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/expediente/ingresarExp',{
                    'folio':this.id,
                    'fecha_ingreso' : this.fecha_ingreso,
                    'valor_escrituras' : this.valor_escrituras,
                    
                }).then(function (response){
                   
                    me.cerrarModal();
                    me.listarIngresoExp(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    me.listarAutorizados(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    me.listarLiquidacion(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    me.listarProgramacion(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Expediente ingresado correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            actualizarVigencia(id,fecha_vigencia){
                let me = this;
                //Con axios se llama el metodo update de PartidaController
                if(fecha_vigencia=="")
                    return
                else{
                    axios.put('/creditos_select/setFechaVigencia',{
                        'folio':id,
                        'fecha_vigencia': fecha_vigencia,
                    }).then(function (response){
                        
                        me.listarIngresoExp(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                        me.listarAutorizados(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                        me.listarLiquidacion(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                        me.listarProgramacion(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                        me.band = 0;
                        //window.alert("Cambios guardados correctamente");
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
                }
                
            },

            generarLiquidacion(){
                if(this.validarLiquidacion()){
                    return;
                }
                
                let me = this;
                Swal.showLoading()
                //Con axios se llama el metodo update de LoteController
                axios.put('/expediente/generarLiquidacion',{
                    'folio':this.id,
                    'fecha_liquidacion' : this.fecha_liquidacion,
                    'valor_escrituras' : this.valor_escrituras,
                    'descuento' : this.descuento,
                    'total_liquidar' : this.total_liquidar,
                    'infonavit' : this.infonavit,
                    'fovissste': this.fovissste,
                    'monto_credito' : this.monto_credito,
                    'obs_descuento' : this.obs_descuento,
                    'notas_liquidacion' : this.notas_liquidacion
                    
                }).then(function (response){
                   
                    me.cerrarModal();
                    me.listarIngresoExp(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    me.listarAutorizados(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    me.listarLiquidacion(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    me.listarProgramacion(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    Swal.enableLoading()
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Liquidacion generada correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                    Swal.enableLoading()
                });
            },

            generarInstNot(){
                if(this.validarProgramacion()){
                    return;
                }

                let me = this;
                Swal.showLoading()
                //Con axios se llama el metodo update de LoteController
                axios.put('/expediente/generarInstruccionNot',{
                    'folio':this.id,
                    'fecha_firma_esc' : this.fecha_firma_esc,
                    'notaria_id' : this.notaria_id,
                    'notaria' : this.notaria,
                    'notario' : this.notario,
                    'hora_firma' : this.hora_firma,
                    'direccion_firma': this.direccion_firma
                    
                }).then(function (response){
                   
                    me.cerrarModal5();
                    me.listarIngresoExp(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    me.listarAutorizados(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    me.listarLiquidacion(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    me.listarProgramacion(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    Swal.enableLoading()
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Programacion de firma generada correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                    Swal.enableLoading()
                });
            },

            generarPagare(){
                // if(this.validarPagares()){
                //     return;
                // }
                
                let me = this;
                Swal.showLoading()
                //Con axios se llama el metodo update de LoteController
                axios.post('/expediente/generarPagares',{
                    'folio':this.id,
                    'intereses_ordinarios' : this.int_oridinario,
                    'intereses_moratorios' : this.int_moratorio,
                    'fecha_ini_interes' : this.fecha_ini_interes,
                    'nombre_aval' : this.nombre_aval,
                    'direccion_aval' : this.direccion_aval,
                    'telefono_aval': this.telefono_aval,
                    'nombre_aval2' : this.nombre_aval2,
                    'direccion_aval2' : this.direccion_aval2,
                    'telefono_aval2': this.telefono_aval2,
                    'pagares' : this.arrayPagos
                    
                }).then(function (response){
                   
                    me.cerrarModal4();
                    me.listarIngresoExp(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    me.listarAutorizados(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    me.listarLiquidacion(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    me.listarProgramacion(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    Swal.enableLoading()
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Pagares y liquidacion generados correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                    Swal.enableLoading()
                });
            },

            inscribirInfonavit(){
                if(this.validarInscripcion()){
                    return;
                }
                
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/expediente/inscInfonavit',{
                    'folio':this.id,
                    'fecha_infonavit' : this.fecha_infonavit,
                    
                }).then(function (response){
                   
                    me.cerrarModal();
                    me.listarIngresoExp(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    me.listarAutorizados(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    me.listarLiquidacion(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    me.listarProgramacion(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Expediente ingresado correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            listarIngresoExp(page, buscar, b_etapa, b_manzana, b_lote, criterio){
                let me = this;
                var url = '/expediente/ingresarIndex?page=' + page + '&buscar=' + buscar + '&b_etapa=' + b_etapa + '&b_manzana=' + 
                b_manzana + '&b_lote=' + b_lote +  '&criterio=' + criterio+'&b_empresa='+this.b_empresa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPorIngresar = respuesta.contratos;
                    me.contadorIngresar = respuesta.contador;
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },

            listarAutorizados(page, buscar, b_etapa, b_manzana, b_lote, criterio){
                let me = this;
                var url = '/expediente/autorizadosIndex?page=' + page + '&buscar=' + buscar + '&b_etapa=' + b_etapa + '&b_manzana=' + 
                b_manzana + '&b_lote=' + b_lote +  '&criterio=' + criterio +'&b_empresa='+this.b_empresa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPreautorizados = respuesta.contratos;
                    me.contadorAutorizados = respuesta.contador;
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
                me.listarEnviados(page,buscar, b_etapa, b_manzana, b_lote, criterio);
            },

            listarEnviados(page, buscar, b_etapa, b_manzana, b_lote, criterio){
                let me = this;
                var url = '/expediente/enviadosIndex?page=' + page + '&buscar=' + buscar + '&b_etapa=' + b_etapa + '&b_manzana=' + 
                b_manzana + '&b_lote=' + b_lote +  '&criterio=' + criterio+'&b_empresa='+this.b_empresa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEnviados = respuesta.contratos.data;
                    me.pagination = respuesta.pagination;
                    me.contadorEnviados = respuesta.contador;
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },

            listarLiquidacion(page, buscar, b_etapa, b_manzana, b_lote, criterio){
                let me = this;
                var url = '/expediente/liquidacionIndex?page=' + page + '&buscar=' + buscar + '&b_etapa=' + b_etapa + '&b_manzana=' + 
                b_manzana + '&b_lote=' + b_lote +  '&criterio=' + criterio +'&b_empresa='+this.b_empresa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLiquidados = respuesta.contratos;
                    me.contadorLiquidacion = respuesta.contador;
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },

            listarProgramacion(page, buscar, b_etapa, b_manzana, b_lote, criterio){
                let me = this;
                var url = '/expediente/ProgramacionIndex?page=' + page + '&buscar=' + buscar + '&b_etapa=' + b_etapa + 
                '&b_manzana=' + b_manzana + '&b_lote=' + b_lote +  '&criterio=' + criterio +'&b_empresa='+this.b_empresa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayProgramacion = respuesta.contratos;
                    me.contadorProgramacion = respuesta.contador;
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },

            mostrarPagares(){
                let me = this;
                var url = '/expediente/pagaresExpediente?folio=' + this.id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPagares = respuesta.pagares;
                    me.totalEnganghe = respuesta.calculos[0].enganche;
                    me.totalRestante = respuesta.calculos[0].total_restante;
                    me.pagos = respuesta.depositos.pagado;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            listarGastos(){
                let me = this;
                var url = '/expediente/gastosExpediente?folio=' + this.id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayGastos = respuesta.gastos;
                    me.totalGastos = respuesta.totalGastos[0].sumGasto;
                    me.totalIntOrd = respuesta.totalIntereses[0].sumIntereses;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            validarIngreso(){
                this.errorIngreso=0;
                this.errorMostrarMsjIngreso=[];

                if(this.fecha_ingreso== '') //Si la variable departamento esta vacia
                    this.errorMostrarMsjIngreso.push("Ingresar una fecha.");

                if(this.valor_escrituras== '') //Si la variable departamento esta vacia
                    this.errorMostrarMsjIngreso.push("Ingresar el valor a escriturar.");
                              
                if(this.errorMostrarMsjIngreso.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorIngreso = 1;

                return this.errorIngreso;
            },

            validarInscripcion(){
                this.errorIngreso=0;
                this.errorMostrarMsjIngreso=[];

                if(this.fecha_infonavit== '') //Si la variable departamento esta vacia
                    this.errorMostrarMsjIngreso.push("Ingresar la fecha de inscripción.");
                              
                if(this.errorMostrarMsjIngreso.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorIngreso = 1;

                return this.errorIngreso;
            },

            validarLiquidacion(){
                this.errorLiquidacion=0;
                this.errorMostrarMsjLiquidacion=[];

                if(this.fecha_liquidacion== '') //Si la variable departamento esta vacia
                    this.errorMostrarMsjLiquidacion.push("Ingresar una fecha.");

                if(this.valor_escrituras== '') //Si la variable departamento esta vacia
                    this.errorMostrarMsjLiquidacion.push("Ingresar el valor a escriturar.");

                if(this.descuento== '') //Si la variable departamento esta vacia
                    this.errorMostrarMsjLiquidacion.push("Ingresar descuento.");
                              
                if(this.errorMostrarMsjLiquidacion.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorLiquidacion = 1;

                return this.errorLiquidacion;
            },

            validarProgramacion(){
                this.errorProgramacionFirma=0;
                this.errorMostrarMsjProgramacion=[];

                if(this.notaria== '') //Si la variable departamento esta vacia
                    this.errorMostrarMsjProgramacion.push("Seleccionar notaria.");

                if(this.fecha_firma_esc== '') //Si la variable departamento esta vacia
                    this.errorMostrarMsjProgramacion.push("Ingresar fecha para firma de escrituras.");

                              
                if(this.errorMostrarMsjProgramacion.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorProgramacionFirma = 1;

                return this.errorProgramacionFirma;
            },

            noAplicaInfonavit(id){
                this.id = id;
                swal({
                title: '¿Esta seguro de que la inscripción de Infonavit no aplica para este registro?',
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

                    axios.put('/expediente/InfonavitNoAplica',{
                        'folio': me.id
                    }).then(function (response) {
                        me.cerrarModal();
                        me.listarIngresoExp(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                        me.listarAutorizados(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                        me.listarLiquidacion(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                        me.listarProgramacion(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
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

            setFechaConcluido(){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/avaluos/fechaConcluido',{
                    'avaluoId':this.avaluoId,
                    'fecha_concluido' : this.fecha_concluido,
                    'resultado' : this.resultado,
                    
                }).then(function (response){
                    me.cerrarModal();
                    me.listarIngresoExp(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    me.listarAutorizados(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    me.listarLiquidacion(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    me.listarProgramacion(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Fecha de pago ingresada correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            updateMontoCredito(){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/update/montocredito/liquidacion',{
                    'id':this.id,
                    'monto_credito' : this.monto_credito,
                    
                }).then(function (response){
                    me.listarIngresoExp(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    me.listarAutorizados(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    me.listarLiquidacion(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    me.listarProgramacion(1, me.buscar, me.b_etapa, me.b_manzana, me.b_lote, me.criterio);
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Monto actualizado correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
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
                    case 'fecha_recibido': 
                    {
                        this.modal = 1;
                        this.tituloModal='Fecha recibido';
                        this.tipoAccion = 3;
                        this.fecha_recibido = data['aviso_prev_venc'];
                        this.id = data['folio'];
                        break;
                    }
                    case 'ingresar': 
                    {
                        this.modal = 1;
                        this.tituloModal='Ingresar expediente';
                        this.tipoAccion = 2;
                        this.fecha_ingreso = '';
                        this.valor_escrituras= '0';
                        this.id = data['folio'];
                        break;
                    }

                    case 'autorizado': 
                    {
                        this.modal = 1;
                        this.tituloModal='Inscripción a Infonavit';
                        this.tipoAccion = 4;
                        this.fecha_infonavit = '';
                        this.id = data['folio'];
                        break;
                    }

                    case 'liquidacion': 
                    {
                         
                        this.modal2 = 1;
                        this.tituloModal='Generar Liquidación';
                        this.id = data['folio'];
                        this.fecha_firma_contrato=data['fecha_status'];
                        this.cliente=data['nombre_cliente'];
                        this.credito=data['tipo_credito'];
                        this.inst_fin=data['institucion'];
                        this.proyecto=data['proyecto'];
                        this.etapa=data['etapa'];
                        this.manzana=data['manzana'];
                        this.lote=data['num_lote'];
                        this.valor_escrituras = data['valor_escrituras'];
                        this.valor_venta = data['precio_venta'];
                        this.descuento = 0;
                        this.totalEnganghe=0;
                        this.pagado=0;
                        this.monto_credito = data['credito_solic'];
                        this.infonavit = data['infonavit'];
                        this.fovissste = data['fovissste'];
                        this.avaluo = data['resultado'];

                        this.mostrarPagares();
                        this.listarGastos();
                        
                        break;
                    }

                    case 'intereses': 
                    {
                        this.modal4 = 1;
                        this.tituloModal='Intereses';
                        this.id = data['folio'];
                        this.total_liquidar1=data['total_liquidar'];
                        this.int_oridinario = 5;
                        this.int_moratorio = 5;
                        this.fecha_ini_interes = '';
                        this.fecha_pago = '';
                        this.nombre_aval = '';
                        this.direccion_aval = '';
                        this.telefono_aval = '';
                        this.nombre_aval2 = '';
                        this.direccion_aval2 = '';
                        this.telefono_aval2 = '';
                        this.arrayPagos = [];
                        this.restante_pago=this.total_liquidar1;

                        break;
                    }

                    case 'firma_esc': 
                    {
                        if(data['nombre_recomendado'] == null && data['publicidad_id'] == 1 || data['nombre_recomendado'] == '' && data['publicidad_id'] == 1){
                            Swal({
                                    title: 'Recomendado!',
                                    text: 'No se encuentra registrado el nombre de la persona que lo recomienda',
                                    type: 'info',
                                    animation: false,
                                    customClass: 'animated bounceInRight'
                                })
                        }
                        else{
                        this.modal5 = 1;
                        this.tipoAccion = 1;
                        this.tituloModal='Instrucción Notarial';
                        this.id = data['folio'];
                        this.proyecto = data['proyecto'];
                        this.etapa = data['etapa'];
                        this.manzana = data['manzana'];
                        this.lote = data['num_lote'];
                        this.valor_venta = data['precio_venta'];
                        this.monto_credito = data['credito_solic'];
                        this.infonavit = data['infonavit'];
                        this.fovissste = data['fovissste'];
                        this.diferencia = parseFloat(this.valor_venta) - parseFloat(this.monto_credito) - 
                                            parseFloat(this.infonavit) - parseFloat(this.fovissste);

                        this.hora_firma = '';
                        this.notaria_id = 0;
                        this.estado = '';
                        this.ciudad = '';
                        }
                    

                        break;
                    }

                    case 'firma_esc_act': 
                    {
                        this.modal5 = 1;
                        this.tipoAccion = 2;
                        this.tituloModal='Instrucción Notarial';
                        this.id = data['folio'];
                        this.proyecto = data['proyecto'];
                        this.etapa = data['etapa'];
                        this.manzana = data['manzana'];
                        this.lote = data['num_lote'];
                        this.valor_venta = data['precio_venta'];
                        this.monto_credito = data['credito_solic'];
                        this.infonavit = data['infonavit'];
                        this.fovissste = data['fovissste'];
                        this.diferencia = parseFloat(this.valor_venta) - parseFloat(this.monto_credito) - 
                                            parseFloat(this.infonavit) - parseFloat(this.fovissste);

                        this.hora_firma = data['hora_firma'];
                        this.notaria_id = data['notaria_id'];
                        this.direccion_firma = data['direccion_firma'];
                        this.notario = data['notario'];
                        this.estado = 'San Luis Potosí';
                        this.ciudad = 'San Luis Potosí';
                        this.fecha_firma_esc = data['fecha_firma_esc'];

                        this.selectCiudades(this.estado);
                        this.selectNotarias(this.ciudad);
                    

                        break;
                    }

                    case 'avaluo':{
                        this.modal =1;
                        this.tituloModal='Avaluo concluido';
                        this.tipoAccion = 5;
                        this.fecha_concluido = data['fecha_concluido'];
                        this.resultado = data['resultado'];
                        this.avaluoId = data['avaluoId'];
                        break;
                    }

                    case 'solic_entrega':{
                        this.modal6 =1;
                        this.id = data['folio'];
                        this.tituloModal='Solicitar Entrega';
                        this.observacion='';
                        break;
                    }
                }

            },

            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.fecha_ingreso='';
                this.valor_escrituras='0';
                this.errorIngreso=0;
                this.fecha_concluido= '';
                this.resultado=0;
                this.errorMostrarMsjIngreso=[];
                this.errorLiquidacion=0;
                this.errorMostrarMsjLiquidacion=[];

                this.modal2 = 0;
                this.modal6 = 0;
                this.observacion = '';
                
            },

            cerrarModal4(){
                this.tituloModal = '';
                this.modal4 = 0;                
            },

            cerrarModal5(){
                this.modal5 = 0;
                this.tituloModal='';
                this.id = 0;
                this.proyecto = "";
                this.etapa = "";
                this.manzana = "";
                this.lote = "";
                this.valor_venta = 0;
                this.monto_credito = 0;
                this.infonavit = 0;
                this.fovissste = 0;
                this.diferencia = 0;

                this.hora_firma = '';
                this.notaria_id = 0;
                this.estado = '';
                this.ciudad = '';
            },

            cerrarModal3(){
                this.modal3 = 0;
                this.tituloModal3 = '';
                this.usuario = '';
                this.observacion = '';
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
            this.selectFraccionamientos();
            this.selectGestores();
            this.listarIngresoExp(1, this.buscar, this.b_etapa, this.b_manzana, this.b_lote, this.criterio);
            this.listarAutorizados(1, this.buscar, this.b_etapa, this.b_manzana, this.b_lote, this.criterio);
            this.listarLiquidacion(1, this.buscar, this.b_etapa, this.b_manzana, this.b_lote, this.criterio);
            this.listarProgramacion(1, this.buscar, this.b_etapa, this.b_manzana, this.b_lote, this.criterio);
            this.listarEnviados(1, this.buscar, this.b_etapa, this.b_manzana, this.b_lote, this.criterio);
            this.getEmpresa();
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

    @media (min-width: 300px){
    .btnagregar{
            margin-top: 2rem;
            }

        .nav2 {
            overflow-x: scroll;
        }
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
    /* .nav2 {
    display: -ms-flexbox;
    display: flex;
    padding-left: 0;
    margin-bottom: 0;
    list-style: none;
    overflow-x: scroll;
    } */
</style>
