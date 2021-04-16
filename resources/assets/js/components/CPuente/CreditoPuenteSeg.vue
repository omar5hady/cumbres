<template>
    <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card scroll-box">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Créditos Puente
                        
                    </div>
                    <!-- Div Card Body para listar -->
                    <template v-if="listado == 1">
                        <div class="card-body"> 
                            <div class="form-group row">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <select class="form-control" @keyup.enter="listarAvisos(1)" v-model="buscar" >
                                            <option value="">Seleccione</option>
                                            <option v-for="proyecto in arrayProyectos" :key="proyecto.id" :value="proyecto.id" v-text="proyecto.nombre"></option>
                                        </select>
                                        <input type="text" class="form-control" v-model="b_folio" @keyup.enter="listarAvisos(1)" placeholder="Folio">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        
                                        <button type="submit" @click="listarAvisos(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table2 table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Folio</th>
                                            <th>Proyecto</th>
                                            <th>Tasa de interes</th>
                                            <th>Apertura</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th></th>
                                            <!-- <th>Fecha solicitud</th>
                                            <th>Status</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-on:dblclick="verDetalla(creditosPuente,2)" v-for="creditosPuente in arrayCreditosPuente" :key="creditosPuente.id" title="Ver detalle">
                                            <td>
                                                <button type="button" class="btn btn-warning btn-sm" @click="verDetalla(creditosPuente, 3)">
                                                    <i class="icon-pencil"></i>
                                                </button>
                                            </td>
                                            <td>
                                                <a href="#" v-text="creditosPuente.folio"></a>
                                            </td>
                                            <td class="td2" v-text="creditosPuente.proyecto"></td>
                                            <td class="td2"> TIEE+{{formatNumber(creditosPuente.interes)}}</td>
                                            <td class="td2"> {{formatNumber(creditosPuente.apertura)}}%</td>
                                            <td class="td2"> ${{formatNumber(creditosPuente.total)}}</td>
                                            <td class="td2" v-if="creditosPuente.status == 0">
                                                <span class="badge badge-info">Pendiente</span>
                                            </td>
                                            <td class="td2" v-if="creditosPuente.status == 1">
                                                <span class="badge badge-warning">Expediente integrado: {{creditosPuente.fecha_integracion}}</span> 
                                            </td>
                                            <td class="td2" v-if="creditosPuente.status == 2">
                                                <span class="badge badge-danger">Rechazado</span> 
                                            </td>
                                            <td class="td2" v-if="creditosPuente.status == 3">
                                                <span class="badge badge-success">Aprobado</span> 
                                            </td>
                                            <td class="td2">
                                                <button type="button" @click="abrirModal('obs',creditosPuente.id)" class="btn btn-dark btn-sm" title="Observaciones">
                                                    <i class="fa fa-book">&nbsp;Observaciones</i>
                                                </button>
                                            </td>
                                        </tr>                               
                                    </tbody>
                                </table>
                            </div>
                            <nav>
                                <!--Botones de paginacion -->
                                <ul class="pagination">
                                    <li class="page-item" v-if="pagination.current_page > 1">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1)">Ant</a>
                                    </li>
                                    <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                                    </li>
                                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </template>
                    <!-- Div Card Body para actualizar registros -->
                    <template v-else-if="listado == 3">
                        <div class="card-body"> 
                            <div class="form-group row border">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Empresa solicitante: </label>
                                        <strong><p>{{emp_constructora}}</p></strong>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Institución </label>
                                    <select :disabled="cabecera.status > 0" class="form-control" v-model="cabecera.banco">
                                        <option value="">Seleccione</option>
                                        <option v-for="banco in arrayBancos" :key="banco.nombre" :value="banco.nombre" v-text="banco.nombre"></option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="">Tasa de Interés (TIIE+) </label>
                                    <input :disabled="cabecera.status > 0" type="number" pattern="\d*" class="form-control" min="0" max="100" v-model="cabecera.interes" v-on:keypress="isNumber($event)">
                                </div>
                                <div class="col-md-2">
                                    <label for="">Apertura </label>
                                    <input :disabled="cabecera.status > 0" type="number" class="form-control" min="0" max="100" v-model="cabecera.apertura" v-on:keypress="isNumber($event)">
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Fraccionamiento </label>
                                        <select class="form-control" disabled v-model="cabecera.fraccionamiento" @click="selectEtapa(cabecera.fraccionamiento)">
                                            <option value="">Seleccione</option>
                                            <option v-for="proyecto in arrayProyectos" :key="proyecto.id" :value="proyecto.id" v-text="proyecto.nombre"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div v-if="cabecera.status == 0" class="form-group">
                                        <label for="">Etapa </label>
                                        <select class="form-control" v-model="etapa_id" @click="selectManzanas(cabecera.fraccionamiento,etapa_id)">
                                            <option value="">Seleccione</option>
                                            <option v-for="etapa in arrayEtapas" :key="etapa.id" :value="etapa.id" v-text="etapa.num_etapa"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div v-if="cabecera.status == 0" class="form-group">
                                        <label for="">Manzana </label>
                                        <select class="form-control" v-model="manzana" @click="selectLotes(cabecera.fraccionamiento,etapa_id,manzana)">
                                            <option value="">Seleccione</option>
                                            <option v-for="manzanas in arrayManzanas" :key="manzanas.manzana" :value="manzanas.manzana" v-text="manzanas.manzana"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div v-if="cabecera.status == 0" class="form-group">
                                        <label>Lote</label> 
                                        <div class="form-inline">
                                            <select class="form-control" v-model="lote_id">
                                                <option value="">Seleccione</option>
                                                <option v-for="lotes in arrayLotes" :key="lotes.id" :value="lotes.id" v-text="lotes.num_lote"></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1" v-if="lote_id != ''">
                                    <div class="form-group">
                                        <button @click="registrarLote()" class="btn btn-success form-control btnagregar"><i class="icon-plus"></i></button>
                                    </div>
                                </div>
                                 <div class="col-md-12">
                                    <!-- Div para mostrar los errores que mande validerFraccionamiento -->
                                    <div v-show="errorcreditosPuente" class="form-group row div-error">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMostrarMsjcreditosPuente" :key="error" v-text="error">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row border" v-if="cabecera.status == 0">
                                <div class="col-md-12">
                                    <h5><strong><center>Modelos</center></strong></h5>
                                </div>

                                <div class="col-md-3" v-for="modelo in arrayModelos" :key="modelo.id">
                                    <div class="form-group">
                                        <strong><label>{{modelo.modelo}}</label></strong>
                                        <div class="form-inline">
                                            <input :disabled="cabecera.status > 0" class="form-control" type="text" pattern="\d*"
                                                @keyup.enter="actualizarModelo(modelo.id,$event.target.value)" :id="modelo.id" 
                                                :value="modelo.precio|currency" step="1"  v-on:keypress="isNumber($event)"
                                            >
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            <div class="form-group row">
                                <div class="table-responsive col-md-12">
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Lote</th>
                                                <th>Etapa</th>
                                                <th>Manzana</th>
                                                <th>Modelo</th>
                                                <th>Precio</th>
                                                <th></th>
                                                <th>Modelo Ant</th>
                                                <th>Precio ant</th>
                                                <th></th>
                                                <th>Modelo Ant</th>
                                                <th>Precio ant</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="arrayLotesPuente.length">
                                            <tr v-for="detalle in arrayLotesPuente" :key="detalle.id">
                                                <td>
                                                    <button @click="eliminarLote(detalle)" type="button" class="btn btn-danger btn-sm">
                                                        <i class="icon-close"></i>
                                                    </button>
                                                </td>
                                                <td v-text="detalle.num_lote"></td>
                                                <td v-text="detalle.num_etapa"></td>
                                                <td v-text="detalle.manzana"></td>
                                                <td v-text="detalle.modelo"></td>
                                                <td v-text="'$ '+formatNumber(detalle.precio_p)"></td>
                                                <td></td>
                                                <td v-text="detalle.modeloAnt1"></td>
                                                <td v-text="'$ '+formatNumber(detalle.precio1)"></td>
                                                <td></td>
                                                <td v-text="detalle.modeloAnt2"></td>
                                                <td v-text="'$ '+formatNumber(detalle.precio2)"></td>
                                            </tr>
                                  
                                            <tr style="background-color: #CEECF5;">
                                                <td align="right" colspan="5"></td>
                                                <td align="right"> <strong>{{ total_precio=totalPrecio | currency}}</strong> </td>
                                                <td align="right" colspan="6"></td>
                                            </tr>
                                        </tbody>

                                        <tbody v-else>
                                            <tr>
                                                <td colspan="6">
                                                    No hay lotes seleccionados
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="card-header form-group col-md-5 border">
                                    <div class="row">
                                        <h5>Planos de urbanización</h5> 
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        
                                    </div>
                                    <br>

                                    <div class="table-responsive col-md-12" v-if="arrayUrbanizacion.length">
                                        <table class="table2 table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Archivo</th>
                                                    <th>Fecha de subida</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="urbanizacion in arrayUrbanizacion" :key="urbanizacion.id">
                                                    <td>
                                                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false"></a>
                                                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
                                                            <a class="dropdown-item" v-bind:href="'/files/'+'planosPuente/'+ urbanizacion.archivo + '/download'" >Descargar plano</a>
                                                        </div>
                                                    </td>
                                                    <td v-text="urbanizacion.descripcion"></td>
                                                    <td v-text="urbanizacion.created_at"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="form-group col-md-2">
                                </div>

                                <div class="card-header form-group col-md-5 border">
                                    <div class="row">
                                        <h5>Planos de edificación</h5> 
                                    </div>
                                    <br>

                                    <div class="table-responsive col-md-12" v-if="arrayUrbanizacion.length">
                                        <table class="table2 table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Archivo</th>
                                                    <th>Fecha de subida</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="edificacion in arrayEdificacion" :key="edificacion.id">
                                                    <td>
                                                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false"></a>
                                                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
                                                            <a class="dropdown-item" v-bind:href="'/files/'+'planosPuente/'+ edificacion.archivo + '/download'" >Descargar plano</a>
                                                        </div>
                                                    </td>
                                                    <td v-text="edificacion.descripcion"></td>
                                                    <td v-text="edificacion.created_at"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row border">
                                <div class="col-md-12">
                                    <br><center>
                                        <h5 v-text="`Checklist (${chk_listos}/${chk_total})`"></h5>
                                        <button v-if="verList == 0" type="button" title="Mostrar" 
                                            class="btn btn-primary btn-sm rounded-circle" @click="verList=1">
                                            <i class="icon-eye"></i>
                                        </button>
                                        <button v-if="verList == 1" type="button" title="Ocultar" 
                                            class="btn btn-warning btn-sm rounded-circle" @click="verList=0">
                                            <i class="icon-close"></i>
                                        </button>
                                    </center><br>
                                </div>
                                <div class="table-responsive col-md-12" v-if="verList == 1">
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Documento</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="checklist.length">
                                            <tr v-for="chk in checklist" :key="chk.id">
                                                <td v-text="chk.categoria"></td>
                                                <td><strong>{{chk.documento}}</strong></td>
                                                <td><input disabled type="checkbox" value="1" v-model="chk.listo" @change="cambiarChk(chk.id, chk.listo)">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-secondary" @click="ocultarDetalle()"> Cerrar </button>
                                    <button v-if="cabecera.status == 0" type="button" class="btn btn-primary" @click="actualizarCredito()"> Actualizar </button>
                                </div>
                            </div>
                        </div>
                    </template>
                    <!--Div para ver detalle del aviso -->
                    <template v-else-if="listado == 2">
                        <div class="card-body" v-if="bases == 0"> 
                            <div class="form-group row border">
                                <div class="col-md-3">
                                    <label for="">Institución </label>
                                    <p v-text="cabecera.banco"></p>
                                </div>
                                <div class="col-md-2">
                                    <label for="">Tasa de Interés </label>
                                    <p v-text="'TIIE+'+formatNumber(cabecera.interes)"></p>
                                </div>
                                <div class="col-md-2">
                                    <label for="">Apertura </label>
                                    <p v-text="formatNumber(cabecera.apertura) + '%'"></p>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Fraccionamiento </label>
                                        <select class="form-control" disabled v-model="cabecera.fraccionamiento" >
                                            <option value="">Seleccione</option>
                                            <option v-for="proyecto in arrayProyectos" :key="proyecto.id" :value="proyecto.id" v-text="proyecto.nombre"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="">Empresa solicitante: </label>
                                        <strong><label>{{emp_constructora}}</label></strong>
                                    </div>
                                </div>
                                <div class="col-md-2"></div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="">Tramite de licencia: </label>
                                        <strong>
                                            <label v-if="lic == 0" v-text="'Antes'"></label>
                                            <label v-if="lic == 1" v-text="'Despues'"></label>
                                        </strong>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row border">
                                <div class="col-md-12">
                                    <h5><strong><center>Modelos</center></strong></h5>
                                </div>
                                <div class="col-md-3" v-for="modelo in arrayModelos" :key="modelo.id" v-if="cabecera.status == 0">
                                    <div class="form-group" v-if="modelo.precio > 0">
                                        <strong><label>{{modelo.modelo}}</label></strong>
                                        <div class="form-inline">
                                            <label>$ {{formatNumber(modelo.precio)}}</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-dark" @click="bases=1,getBases()"> Base presupuestal </button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="table-responsive col-md-12">
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Lote</th>
                                                <th>Etapa</th>
                                                <th>Manzana</th>
                                                <th>Modelo</th>
                                                <th>Precio</th>
                                                <th></th>
                                                <th>Modelo Ant</th>
                                                <th>Precio ant</th>
                                                <th></th>
                                                <th>Modelo Ant</th>
                                                <th>Precio ant</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="arrayLotesPuente.length">
                                            <tr v-for="detalle in arrayLotesPuente" :key="detalle.id">
                                                <td>
                                                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">Docs</a>
                                                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
                                                        <a v-if="detalle.foto_predial" class="dropdown-item" v-bind:href="'/downloadPredial/'+ detalle.foto_predial">Predial</a>
                                                        <a v-if="detalle.factibilidad" class="dropdown-item" v-bind:href="'/downloadFactibilidad/'+ detalle.factibilidad" onclick="window.open('/pdf/INTERAPAS.pdf','_blank')">Factibilidad</a>
                                                        <a v-if="detalle.num_licencia" class="dropdown-item"  v-text="'Licencia: '+detalle.num_licencia" v-bind:href="'/downloadLicencias/'+detalle.foto_lic"></a>
                                                    </div>
                                                </td>
                                                <td v-text="detalle.num_lote"></td>
                                                <td v-text="detalle.num_etapa"></td>
                                                <td v-text="detalle.manzana"></td>
                                                <td v-text="detalle.modelo"></td>
                                                <td v-text="'$ '+formatNumber(detalle.precio_p)"></td>
                                                <td></td>
                                                <td v-text="detalle.modeloAnt1"></td>
                                                <td v-text="'$ '+formatNumber(detalle.precio1)"></td>
                                                <td></td>
                                                <td v-text="detalle.modeloAnt2"></td>
                                                <td v-text="'$ '+formatNumber(detalle.precio2)"></td>
                                            </tr>
                                  
                                            <tr style="background-color: #CEECF5;">
                                                <td align="right" colspan="5"></td>
                                                <td align="right"> <strong>{{ total_precio=totalPrecio | currency}}</strong> </td>
                                                <td align="right" colspan="6"></td>
                                            </tr>
                                        </tbody>
                                        <tbody v-else>
                                            <tr>
                                                <td colspan="6">
                                                    No hay lotes seleccionados
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="card-header form-group col-md-5 border">
                                    <div class="row">
                                        <h5>Planos de urbanización</h5> 
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <button v-if="cabecera.status == 0" type="button" title="Nuevo documento" class="btn btn-success btn-sm" @click="abrirModal('urbanizacion', id), clasificacion = 1">
                                            <i class="icon-plus"></i>
                                        </button>
                                    </div>
                                    <br>
                                    <div class="table-responsive col-md-12" v-if="arrayUrbanizacion.length">
                                        <table class="table2 table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Archivo</th>
                                                    <th>Fecha de entrega</th>
                                                    <th>Notas</th>
                                                    <th>Fecha de confirmación</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="urbanizacion in arrayUrbanizacion" :key="urbanizacion.id">
                                                    
                                                    <td v-text="urbanizacion.descripcion"></td>
                                                    <td v-text="urbanizacion.fecha_entrega"></td>
                                                    <td v-text="urbanizacion.notas"></td>
                                                    <td v-if="urbanizacion.fecha_confirm == null">
                                                        <button type="button" class="btn btn-dark">Confirmar entrega</button>
                                                    </td>
                                                    <td v-else>
                                                        Confirmado por: {{urbanizacion.user_confirm}} ( {{urbanizacion.fecha_confirm}} )
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="card-header form-group col-md-2 ">   
                                </div>

                                <div class="card-header form-group col-md-5 border">
                                    <div class="row">
                                        <h5>Planos de edificación</h5> 
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <button v-if="cabecera.status == 0" type="button" title="Nuevo documento" class="btn btn-success btn-sm" @click="abrirModal('edificacion', id), clasificacion = 2">
                                            <i class="icon-plus"></i>
                                        </button>
                                    </div>
                                    <br>
                                    <div class="table-responsive col-md-12" v-if="arrayUrbanizacion.length">
                                        <table class="table2 table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    
                                                    <th>Archivo</th>
                                                    <th>Fecha de entrega</th>
                                                    <th>Notas</th>
                                                    <th>Fecha de confirmación</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="edificacion in arrayEdificacion" :key="edificacion.id">
                                                    
                                                    <td v-text="edificacion.descripcion"></td>
                                                    <td v-text="edificacion.fecha_entrega"></td>
                                                    <td v-text="edificacion.notas"></td>
                                                    <td v-if="edificacion.fecha_confirm == null">
                                                        <button type="button" class="btn btn-dark">Confirmar entrega</button>
                                                    </td>
                                                    <td v-else>
                                                        Confirmado por: {{edificacion.user_confirm}} ( {{edificacion.fecha_confirm}} )
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row border">
                                <div class="col-md-12">
                                    <br><center>
                                        <h5 v-text="`Checklist (${chk_listos}/${chk_total})`"></h5>
                                        <button v-if="verList == 0" type="button" title="Mostrar" 
                                            class="btn btn-primary btn-sm rounded-circle" @click="verList=1">
                                            <i class="icon-eye"></i>
                                        </button>
                                        <button v-if="verList == 1" type="button" title="Ocultar" 
                                            class="btn btn-warning btn-sm rounded-circle" @click="verList=0">
                                            <i class="icon-close"></i>
                                        </button>
                                        <button v-if="verList == 1 && cabecera.status == 0" type="button" title="Add" 
                                            class="btn btn-success btn-sm rounded-circle" @click="abrirModal('checklist',id)">
                                            <i class="icon-plus"></i>
                                        </button>
                                    </center><br>
                                </div>
                                <div class="table-responsive col-md-12" v-if="verList == 1">
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th>Documento</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="checklist.length">
                                            <tr v-for="chk in checklist" :key="chk.id">
                                                <td>
                                                    <button v-if="cabecera.status == 0" type="button" title="No aplica" 
                                                    class="btn btn-danger btn-sm rounded-circle" @click="deleteChk(chk.id)">
                                                        <i class="icon-close"></i>
                                                    </button>
                                                </td>
                                                <td v-text="chk.categoria"></td>
                                                <td><strong>{{chk.documento}}</strong></td>
                                                <td><input :disabled="cabecera.status > 0" type="checkbox" value="1" v-model="chk.listo" @change="cambiarChk(chk.id, chk.listo)">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-secondary" @click="ocultarDetalle()"> Cerrar </button>
                                </div>
                                <div class="col-md-1" v-if="chk_listos == chk_total && cabecera.status == 0">
                                    <button type="button" class="btn btn-success" @click="enviarExp()"> Integrar expediente </button>
                                </div>

                                <div class="col-md-1" v-if="chk_listos == chk_total && cabecera.status == 1">
                                    <button type="button" class="btn btn-success" @click="resBanco(1)"> Aprobar </button>
                                </div>

                                <div class="col-md-1" v-if="chk_listos == chk_total && cabecera.status == 1">
                                    <button type="button" class="btn btn-danger" @click="resBanco(0)"> Rechazar </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" v-if="bases == 1">

                            <div class="form-group row">
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-secondary" @click="bases=0"> Regresar </button>
                                </div>
                                <div class="col-md-1">
                                    <button v-if="detalle == 0" type="button" class="btn btn-primary" @click="detalle=1"> Mostrar detalle </button>
                                    <button v-if="detalle == 1" type="button" class="btn btn-warning" @click="detalle=0"> Ocultar detalle </button>
                                </div>
                            </div>

                            <div class="row">
                                <div v-for="base in arrayBases" :key="base.id" class="col-xl-4 col-lg-5 col-md-4">
                                    <div class="card">
                                        <div class="card-body p-3 d-flex align-items-center">
                                            <div>
                                                <div class="text-value text-primary"><strong>{{base.nombre}} ({{base.cont}})</strong></div>
                                                <div class="text-muted text-uppercase font-weight-bold" v-text="'Valor de venta: $'+formatNumber(base.precio_puente)"></div>
                                                <strong>
                                                    <div class="text-muted text-uppercase font-weight-bold" v-text="'Valor de venta: $'+formatNumber(base.precio_puente * base.cont)"></div>
                                                </strong>
                                                <div class="text-muted text-uppercase font-weight-bold" v-text="'INC: '+ base.inc"></div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-sm">
                                                    <tbody>
                                                        <!--Comisiones Bancarias--->
                                                            <!-- <tr><th colspan="2">Comisiones Bancarias</th></tr> -->
                                                            <tr>
                                                                <td colspan="2">Adquisición del terreno</td>
                                                                <td v-text="'$'+formatNumber(base.adquisicion_terreno/base.cont)"></td>
                                                                <strong>
                                                                    <td v-text="'$'+formatNumber(base.adquisicion_terreno)"></td>
                                                                </strong>
                                                                
                                                            </tr>
                                                            <!------>
                                                                <tr class="table-warning" v-if="detalle == 1">
                                                                    <td></td>
                                                                    <td>Intereses por pago del terreno</td>
                                                                    <td v-text="'$'+formatNumber(base.int_pago_terreno*base.inc)"></td>
                                                                        <td v-text="'$'+formatNumber(base.int_pago_terreno*base.inc*base.cont)"></td>
                                                                </tr>
                                                                <tr class="table-warning" v-if="detalle == 1">
                                                                    <td></td>
                                                                    <td>Valor del terreno</td>
                                                                    <td v-text="'$'+formatNumber(base.valor_terreno*base.inc)"></td>
                                                                        <td v-text="'$'+formatNumber(base.valor_terreno*base.inc*base.cont)"></td>
                                                                </tr>
                                                                <tr class="table-warning" v-if="detalle == 1">
                                                                    <td></td>
                                                                    <td>Escritura a GCC, Juicio y Acta de Lotif.</td>
                                                                    <td v-text="'$'+formatNumber(base.escritura_gcc*base.inc)"></td>
                                                                        <td v-text="'$'+formatNumber(base.escritura_gcc*base.inc*base.cont)"></td>
                                                                </tr>
                                                                <tr class="table-warning" v-if="detalle == 1">
                                                                    <td></td>
                                                                    <td>Cargos adicionales al terreno</td>
                                                                    <td v-text="'$'+formatNumber(base.adicional_terreno*base.inc)"></td>
                                                                        <td v-text="'$'+formatNumber(base.adicional_terreno*base.inc*base.cont)"></td>
                                                                </tr>
                                                            <!------>
                                                            <tr>
                                                                <td colspan="2">Estudios y licencias (0.7)</td>
                                                                <td v-text="'$'+formatNumber(base.estudios_lic/base.cont)"></td>
                                                                <strong><td v-text="'$'+formatNumber(base.estudios_lic )"></td></strong>
                                                            </tr>  
                                                            <!------>
                                                                <tr class="table-warning" v-if="detalle == 1">
                                                                    <td></td>
                                                                    <td>Permisos, Lic., Y Autolizaciones Municipales</td>
                                                                    <td v-text="'$'+formatNumber(base.permisos*base.inc*0.7)"></td>
                                                                        <td v-text="'$'+formatNumber((base.permisos*base.inc)*base.cont*0.7)"></td>
                                                                </tr>
                                                            <!------>
                                                            <tr>
                                                                <td colspan="2">Proyectos y diseños (0.3)</td>
                                                                <td v-text="'$'+formatNumber(base.proyectos_disen/base.cont)"></td>
                                                                <strong><td v-text="'$'+formatNumber(base.proyectos_disen )"></td></strong>
                                                            </tr>  
                                                            <!------>
                                                                <tr class="table-warning" v-if="detalle == 1">
                                                                    <td></td>
                                                                    <td>Permisos, Lic., Y Autolizaciones Municipales</td>
                                                                    <td v-text="'$'+formatNumber(base.permisos*base.inc*0.3)"></td>
                                                                        <td v-text="'$'+formatNumber((base.permisos*base.inc*base.cont)*0.3)"></td>
                                                                </tr>
                                                            <!------>
                                                            <tr>
                                                                <td colspan="2">Edificación</td>
                                                                <td v-text="'$'+formatNumber(base.edificacion/base.cont)"></td>
                                                                <strong><td v-text="'$'+formatNumber(base.edificacion )"></td></strong>
                                                            </tr>  
                                                            <!------>
                                                                <tr class="table-warning" v-if="detalle == 1">
                                                                    <td></td>
                                                                    <td>Presupuesto de edificación de vivienda</td>
                                                                    <td v-text="'$'+formatNumber(base.presupuesto_edif*base.inc)"></td>
                                                                        <td v-text="'$'+formatNumber((base.presupuesto_edif*base.inc)*base.cont)"></td>
                                                                </tr>
                                                                <tr class="table-warning" v-if="detalle == 1">
                                                                    <td></td>
                                                                    <td>Laboratorio</td>
                                                                    <td v-text="'$'+formatNumber(base.laboratorio*base.inc)"></td>
                                                                        <td v-text="'$'+formatNumber((base.laboratorio*base.inc)*base.cont)"></td>
                                                                </tr>
                                                                <tr class="table-warning" v-if="detalle == 1">
                                                                    <td></td>
                                                                    <td>Partida Inflacionaria y Obra extra</td>
                                                                    <td v-text="'$'+formatNumber(base.partida_inflacionaria*base.inc)"></td>
                                                                        <td v-text="'$'+formatNumber((base.partida_inflacionaria*base.inc)*base.cont)"></td>
                                                                </tr>
                                                            <!------>
                                                            <tr>
                                                                <td colspan="2">Urbanización e Infraestructura</td>
                                                                <td v-text="'$'+formatNumber(base.urbanizacion_infra/base.cont)"></td>
                                                                <strong><td v-text="'$'+formatNumber(base.urbanizacion_infra )"></td></strong>
                                                            </tr>  
                                                                <tr class="table-warning" v-if="detalle == 1">
                                                                    <td></td>
                                                                    <td>Presupuesto de Urb. E Infraestructura</td>
                                                                    <td v-text="'$'+formatNumber(base.presupuesto_urb*base.inc)"></td>
                                                                    <td v-text="'$'+formatNumber((base.presupuesto_urb*base.inc)*base.cont)"></td>
                                                                </tr>
                                                                <tr class="table-warning" v-if="detalle == 1">
                                                                    <td></td>
                                                                    <td>Equipamiento</td>
                                                                    <td v-text="'$'+formatNumber(base.equipamiento*base.inc)"></td>
                                                                    <td v-text="'$'+formatNumber((base.equipamiento*base.inc)*base.cont)"></td>
                                                                </tr>
                                                                <tr class="table-warning" v-if="detalle == 1">
                                                                    <td></td>
                                                                    <td>Fianzas (Cumplimiento Urbanización)</td>
                                                                    <td v-text="'$'+formatNumber(base.fianzas*base.inc)"></td>
                                                                    <td v-text="'$'+formatNumber((base.fianzas*base.inc)*base.cont)"></td>
                                                                </tr>
                                                            <tr>
                                                                <td colspan="4">Supervisión de Obras</td>
                                                            </tr>  
                                                            <tr>
                                                                <td colspan="2">Promoción y Publicidad</td>
                                                                <td v-text="'$'+formatNumber(base.promocion_publi/base.cont)"></td>
                                                                <strong><td v-text="'$'+formatNumber(base.promocion_publi )"></td></strong>
                                                            </tr>
                                                            <tr class="table-warning" v-if="detalle == 1">
                                                                <td></td>
                                                                <td>Gastos de comercialización</td>
                                                                <td v-text="'$'+formatNumber(base.gastos_comerc*base.inc)"></td>
                                                                <td v-text="'$'+formatNumber((base.gastos_comerc*base.inc)*base.cont)"></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">Gastos de venta</td>
                                                                <td v-text="'$'+formatNumber(base.gastos_venta/base.cont)"></td>
                                                                <strong><td v-text="'$'+formatNumber(base.gastos_venta )"></td></strong>
                                                            </tr>
                                                            <tr class="table-warning" v-if="detalle == 1">
                                                                <td></td>
                                                                <td>Comisión por ventas (2.5% + 16%IVA)</td>
                                                                <td v-text="'$'+formatNumber(base.comicion_venta*base.inc)"></td>
                                                                <td v-text="'$'+formatNumber((base.comicion_venta*base.inc)*base.cont)"></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="4">Pago Registro Infonavit</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">Gastos Administración</td>
                                                                <td v-text="'$'+formatNumber(base.gastos_admin/base.cont)"></td>
                                                                <strong><td v-text="'$'+formatNumber(base.gastos_admin )"></td></strong>
                                                            </tr>
                                                            <tr class="table-warning" v-if="detalle == 1">
                                                                <td></td>
                                                                <td>Gastos Ind. De Operación e Incentivos</td>
                                                                <td v-text="'$'+formatNumber(base.gastos_ind_op*base.inc)"></td>
                                                                <td v-text="'$'+formatNumber((base.gastos_ind_op*base.inc)*base.cont)"></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">Gastos Notariales</td>
                                                                <td v-text="'$'+formatNumber(base.gastos_notariales/base.cont)"></td>
                                                                <strong><td v-text="'$'+formatNumber(base.gastos_notariales )"></td></strong>
                                                            </tr>
                                                            <tr class="table-warning" v-if="detalle == 1">
                                                                <td></td>
                                                                <td>Gastos de escrituración del Crédito Puente</td>
                                                                <td v-text="'$'+formatNumber(base.gastos_esc*base.inc)"></td>
                                                                <td v-text="'$'+formatNumber((base.gastos_esc*base.inc)*base.cont)"></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">Gastos Financieros (Comisiones)</td>
                                                                <td v-text="'$'+formatNumber(base.gastos_fin_com/base.cont)"></td>
                                                                <strong><td v-text="'$'+formatNumber(base.gastos_fin_com )"></td></strong>
                                                            </tr>
                                                            <tr class="table-warning" v-if="detalle == 1">
                                                                <td></td>
                                                                <td>Comisión Integral Apertura C.Puente</td>
                                                                <td v-text="'$'+formatNumber(base.comision_int*base.inc)"></td>
                                                                <td v-text="'$'+formatNumber((base.comision_int*base.inc)*base.cont)"></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">Intereses del Crédito Puente y comisiones</td>
                                                                <td v-text="'$'+formatNumber(base.int_cpuente_com/base.cont)"></td>
                                                                <strong><td v-text="'$'+formatNumber(base.int_cpuente_com )"></td></strong>
                                                            </tr>
                                                            <tr class="table-warning" v-if="detalle == 1">
                                                                <td></td>
                                                                <td>Inscripción de Conjunto</td>
                                                                <td v-text="'$'+formatNumber(base.insc_conjunto*base.inc)"></td>
                                                                <td v-text="'$'+formatNumber((base.insc_conjunto*base.inc)*base.cont)"></td>
                                                            </tr>
                                                            <tr class="table-warning" v-if="detalle == 1">
                                                                <td></td>
                                                                <td>Intereses Nafin o por Desplazamiento Lento</td>
                                                                <td v-text="'$'+formatNumber(base.int_nafin*base.inc)"></td>
                                                                <td v-text="'$'+formatNumber((base.int_nafin*base.inc)*base.cont)"></td>
                                                            </tr>
                                                            <tr class="table-warning" v-if="detalle == 1">
                                                                <td></td>
                                                                <td>Intereses Crédito Puente</td>
                                                                <td v-text="'$'+formatNumber(base.int_cpuente*base.inc)"></td>
                                                                <td v-text="'$'+formatNumber((base.int_cpuente*base.inc)*base.cont)"></td>
                                                            </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xl-4 col-lg-5 col-md-4" ></div>
                                <div class="col-xl-4 col-lg-5 col-md-4" >
                                    <div class="card" >
                                        <div class="card-body table-primary p-3 d-flex align-items-center" >
                                            <div>
                                                <div class="text-value text-primary"><strong> TOTAL </strong></div>
                                                <div class="text-muted text-uppercase font-weight-bold" v-text="t_cont"></div>
                                                <div class="text-muted text-uppercase font-weight-bold" v-text="'$' + formatNumber(t_venta)"></div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="table-responsive">
                                                <table class="table table-dark table-bordered table-striped table-sm">
                                                    <tbody>
                                                        <!--Comisiones Bancarias--->
                                                            <!-- <tr><th colspan="2">Comisiones Bancarias</th></tr> -->
                                                            <tr>
                                                                <td>Adquisición del terreno</td>
                                                                <strong>
                                                                    <td v-text="'$'+formatNumber(t_adquisicion_terreno)"></td>
                                                                </strong>
                                                            </tr>
                                                            <tr>
                                                                <td>Estudios y licencias</td>
                                                                <strong><td v-text="'$'+formatNumber(t_estudios_lic)"></td></strong>
                                                            </tr>  
                                                            <tr>
                                                                <td>Proyectos y diseños</td>
                                                                <strong><td v-text="'$'+formatNumber(t_proyectos_disen)"></td></strong>
                                                            </tr>  
                                                            <tr>
                                                                <td>Edificación</td>
                                                                <strong><td v-text="'$'+formatNumber(t_edificacion)"></td></strong>
                                                            </tr>  
                                                            <tr>
                                                                <td>Urbanización e Infraestructura</td>
                                                                <strong><td v-text="'$'+formatNumber(t_urbanizacion_infra)"></td></strong>
                                                            </tr>  
                                                            <tr>
                                                                <td colspan="3">Supervisión de Obras</td>
                                                            </tr>  
                                                            <tr>
                                                                <td>Promoción y Publicidad</td>
                                                                <strong><td v-text="'$'+formatNumber(t_promocion_publi)"></td></strong>
                                                            </tr>
                                                            <tr>
                                                                <td>Gastos de venta</td>
                                                                <strong><td v-text="'$'+formatNumber(t_gastos_venta)"></td></strong>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="3">Pago Registro Infonavit</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Gastos Administración</td>
                                                                <strong><td v-text="'$'+formatNumber(t_gastos_admin)"></td></strong>
                                                            </tr>
                                                            <tr>
                                                                <td>Gastos Notariales</td>
                                                                <strong><td v-text="'$'+formatNumber(t_gastos_notariales)"></td></strong>
                                                            </tr>
                                                            <tr>
                                                                <td>Gastos Financieros (Comisiones)</td>
                                                                <strong><td v-text="'$'+formatNumber(t_gastos_fin_com)"></td></strong>
                                                            </tr>
                                                            <tr>
                                                                <td>Intereses del Crédito Puente y comisiones</td>
                                                                <strong><td v-text="'$'+formatNumber(t_int_cpuente_com)"></td></strong>
                                                            </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-secondary" @click="bases=0"> Regresar </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>

            <!--Inicio del modal-->
                <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal}" 
                    role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-primary modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" v-text="tituloModal"></h4>
                                <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                                <span aria-hidden="true">×</span>
                                </button>
                            </div>

                                <div class="modal-body">
                                    <template v-if="tipoAccion == 1">
                                        
                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Nueva observación</label>
                                                <div class="col-md-6">
                                                    <input type="text" v-model="observacion" class="form-control">
                                                </div>

                                                <div class="col-md-3">
                                                    <button v-if="observacion != ''" class="btn btn-primary" @click="storeObs()">Guardar</button>
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
                                                        <tr v-for="observacion in arrayObs" :key="observacion.id">
                                                            <td v-text="observacion.usuario" ></td>
                                                            <td v-text="observacion.observacion" ></td>
                                                            <td v-text="observacion.created_at"></td>
                                                        </tr>                               
                                                    </tbody>
                                                </table>
                                                
                                            </form>
                                        
                                        
                                    </template>
                                    
                                    <!-- <template v-if="tipoAccion == 2">

                                        <form @submit="dropboxSubmit" method="POST" enctype="multipart/form-data">

                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Descripción</label>
                                                <div class="col-md-6">
                                                    <input type="text" v-model="descripcion" class="form-control" required>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-md-6">
                                                    <input type="file" v-on:change="dropboxFile" name="file" >    
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-md-6">
                                                    <button class="btn btn-primary" type="submit">Guardar archivo</button>
                                                </div>
                                            </div>
                                        </form>
                                        
                                    </template> -->

                                    <template v-if="tipoAccion == 2">

                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Descripción</label>
                                                <div class="col-md-6">
                                                    <input type="text" v-model="descripcion" class="form-control" required placeholder="Nob">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Fecha de entrega</label>
                                                <div class="col-md-6">
                                                    <input type="date" v-model="fecha_entrega" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Notas</label>
                                                <div class="col-md-6">
                                                    <textarea class="form-control" v-model="notas" cols="30" rows="4"></textarea>
                                                </div>
                                            </div>
                                        
                                    </template>

                                    <template v-if="tipoAccion == 3">
                                        
                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Documento</label>
                                                <div class="col-md-6">
                                                    <select class="form-control" v-model="chk_new" >
                                                        <option value="">Seleccione</option>
                                                        <option v-for="chk in arrayChk" :key="chk.id" :value="chk.id" v-text="chk.documento+'-'+chk.categoria"></option>
                                                    </select>
                                                </div>
                                            </div>
                                        
                                    </template>
                                </div>
                                <!-- Botones del modal -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                                    <button v-if="tipoAccion == 2" class="btn btn-primary" type="button" @click="guardarDoc()">Guardar</button>
                                    <button v-if="tipoAccion == 3 && chk_new != ''" type="button" class="btn btn-primary" @click="addDocumento()">Añadir documento</button>
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
    import vSelect from 'vue-select';
    export default {
        props:{
            rolId:{type: String}
        },
        data(){
            return{
                proceso:false,
                id:0,
                arrayCreditosPuente : [],
                arrayCreditosPuenteLotes : [],
                listado:1,
                tipoAccion: 0,
                errorcreditosPuente : 0,
                errorMostrarMsjcreditosPuente : [],
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                cabecera : {
                    'banco' : '',
                    'interes' : 0,
                    'fecha_solic' : '',
                    'status' : 0,
                    'total' : 0,
                    'cobrado' : 0,
                    'folio' : '',
                    'apertura' : 0,
                    'fraccionamiento' : '',
                },
                offset : 3,
                buscar : '',
                b_folio:'',
                arrayProyectos : [],
                arrayEtapas : [],
                arrayManzanas : [],
                arrayLotes:[],
                arrayLotesPuente:[],
                arrayModelos:[],
                lote_id:'',
                arrayBancos:[],
                checklist:[],
                arrayChk:[],
                chk_new:'',
                chk_total:0,
                chk_listos:0,
                verList:0,
                etapa_id:'',
                manzana:'',
                total_precio:0,
                file:'',
                descripcion:'',
                lic:0,

                t_adquisicion_terreno : 0,
                t_estudios_lic : 0,
                t_proyectos_disen : 0,
                t_edificacion : 0,
                t_urbanizacion_infra : 0,
                t_promocion_publi : 0,
                t_gastos_venta : 0,
                t_gastos_admin : 0,
                t_gastos_notariales : 0,
                t_gastos_fin_com : 0,
                t_int_cpuente_com : 0,
                t_cont:0,
                t_venta:0,

                modal:0,
                tituloModal:'',
                tipoAccion:1,
                arrayObs:[],
                observacion:'',
                clasificacion : 1,
                arrayUrbanizacion:[],
                arrayEdificacion:[],
                arrayBases:[],
                emp_constructora:'',
                bases:0,
                detalle:0,
                fecha_entrega:'',
                notas:''
            }
        },
        components:{
            vSelect
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
            totalPrecio: function(){
                var res =0.0;
                for(var i=0;i<this.arrayLotesPuente.length;i++){
                    res = parseFloat(res) + parseFloat(this.arrayLotesPuente[i].precio_p)
                }
                return res;
            },
        },
        methods : {
            // dropboxFile(e){

            //     console.log(e.target);

            //     this.file = e.target.files[0];

            // },
            // dropboxSubmit(e) {

            //     e.preventDefault();

            //     let formData = new FormData();
            //     let me = this;
           
            //     formData.append('file', me.file);
            //     formData.append('descripcion', me.descripcion);
            //     formData.append('clasificacion', me.clasificacion);
            //     axios.post('/dropbox/files/'+me.id+'/planosPuente', formData)
            //     .then(function (response) {
                   
            //         swal({
            //             position: 'top-end',
            //             type: 'success',
            //             title: 'Archivo subido correctamente',
            //             showConfirmButton: false,
            //             timer: 2000
            //             })
            //         me.getPlanos(me.id);
                    
            //     })

            //     .catch(function (error) {
            //         console.log(error);

            //     });

            // },
            // eliminarArchivo(archivo,id){
            //     swal({
            //     title: '¿Desea eliminar este archivo?',
            //     text: "El archivo se borrara completamente",
            //     type: 'warning',
            //     showCancelButton: true,
            //     confirmButtonColor: '#3085d6',
            //     cancelButtonColor: '#d33',
            //     cancelButtonText: 'Cancelar',
            //     confirmButtonText: 'Si, borrar!'
            //     }).then((result) => {
            //     if (result.value) {
            //         let me = this;

            //     //Con axios se llama el metodo update de LoteController
            //         axios.delete('/files/delete',{
            //             params: {
            //             'file' : archivo,
            //             'id' : id,
            //             'sub' : 'planosPuente'
            //             }
            //         }).then(function (response){
            //             //window.alert("Cambios guardados correctamente");
            //             me.getPlanos(me.id);
            //             const toast = Swal.mixin({
            //                 toast: true,
            //                 position: 'top-end',
            //                 showConfirmButton: false,
            //                 timer: 3000
            //                 });
            //                 toast({
            //                 type: 'success',
            //                 title: 'Archivo borrado correctamente'
            //             })
            //         }).catch(function (error){
            //             console.log(error);
            //         });
            //     }
            //     })

            // },
            /**Metodo para mostrar los registros */
            listarAvisos(page){
                let me = this;
                var url = '/cPuentes/indexCreditos?page=' + page + '&fraccionamiento=' + me.buscar + '&folio=' + me.b_folio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayCreditosPuente = respuesta.creditos.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            guardarDoc(){
                let me = this;

                axios.post('/cPuentes/saveDoc',{
                    'descripcion' : me.descripcion,
                    'clasificacion' : me.clasificacion,
                    'notas' : me.notas,
                    'fecha_entrega' : me.fecha_entrega,
                    'id' : me.id,
                }).then(function (response){
                     swal({
                            position: 'top-end',
                            type: 'success',
                            title: 'Lote agregado correctamente',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    me.getPlanos(me.id);
                    
                }).catch(function (error){
                    console.log(error);
                });



            },
            getObs(id){
                let me = this;
                me.arrayObs=[];
                var url = '/cPuentes/getObs?id=' + id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayObs = respuesta.obs;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getPlanos(id){
                let me = this;
                me.arrayUrbanizacion=[];
                me.arrayEdificacion=[];
                var url = '/cPuentes/getPlanos?id=' + id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayUrbanizacion = respuesta.urbanizacion;
                    me.arrayEdificacion = respuesta.edificacion;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            resBanco(resultado){

                Swal({
                    title: 'Estas seguro?',
                    animation: false,
                    customClass: 'animated bounceInDown',
                    text: "Esta acción no se puede cambiar",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    
                    confirmButtonText: 'Si, continuar!'
                    }).then((result) => {

                        if (result.value) {

                            let me = this;

                            if(resultado == 0)
                                (async function getFruit () {
                                    const {value: comentario} = await Swal({
                                    title: 'Observación',
                                    input: 'textarea',
                                    inputPlaceholder: 'Motivo de rechazo...',
                                    showCancelButton: true,
                                    inputValidator: (value) => {
                                        return new Promise((resolve) => {
                                        if (value != '') {
                                            resolve()
                                        } else {
                                            resolve('Es necesario escribir una observación :)')
                                        }
                                        })
                                    }
                                    })
                                    if (comentario) {
                                        axios.put('/cPuentes/resBanco',{
                                            'id' : me.id,
                                            'resultado': resultado,
                                            'comentario': comentario
                                            
                                        }).then(function (response){
                                            me.ocultarDetalle();
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
                                    }
                                })()

                            else{

                                axios.put('/cPuentes/resBanco',{
                                            'id' : me.id,
                                            'resultado': resultado,
                                            
                                        }).then(function (response){
                                            me.ocultarDetalle();
                                            swal({
                                                position: 'top-end',
                                                type: 'success',
                                                title: 'Crédito aprobado correctamente',
                                                showConfirmButton: false,
                                                timer: 1500
                                                })
                                        }).catch(function (error){
                                            console.log(error);
                                        });

                            }

                    }})

            },

            getChecklist(id){
                let me = this;
                me.checklist=[];
                var url = '/cPuentes/getChecklist?id=' + id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.checklist = respuesta.checklist;
                    me.chk_total = respuesta.total;
                    me.chk_listos = respuesta.listos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectBancos(){
                let me = this;
                me.arrayBancos=[];
                var url = '/select_inst_financiamiento';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayBancos = respuesta.instituciones_financiamiento;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectProyectos(){
                let me = this;
                me.arrayProyectos=[];
                var url = '/select_fraccionamiento';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayProyectos = respuesta.fraccionamientos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getBases(){
                let me = this;
                me.bases = 1;
                me.detalle = 0;
                me.arrayBases=[];
                var url = '/cPuentes/getModelosBase?id='+me.id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayBases = respuesta.modelos;

                    me.t_adquisicion_terreno = respuesta.t_adquisicion_terreno;
                    me.t_estudios_lic = respuesta.t_estudios_lic;
                    me.t_proyectos_disen = respuesta.t_proyectos_disen;
                    me.t_edificacion = respuesta.t_edificacion;
                    me.t_urbanizacion_infra = respuesta.t_urbanizacion_infra;
                    me.t_promocion_publi = respuesta.t_promocion_publi;
                    me.t_gastos_venta = respuesta.t_gastos_venta;
                    me.t_gastos_admin = respuesta.t_gastos_admin;
                    me.t_gastos_notariales = respuesta.t_gastos_notariales;
                    me.t_gastos_fin_com = respuesta.t_gastos_fin_com;
                    me.t_int_cpuente_com = respuesta.t_int_cpuente_com;
                    me.t_cont = respuesta.t_cont;
                    me.t_venta = respuesta.t_venta;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectLotes(fraccionamiento,etapa,manzana){
                let me = this;
                me.arrayLotes=[];
                var url = `/cPuentes/selectLotes?proyecto=${fraccionamiento}&etapa=${etapa}&manzana=${manzana}&puente=`;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLotes = respuesta.lotes.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectManzanas(fraccionamiento, etapa){
                let me = this;
                me.manzana ='';
                me.arrayManzanas=[];
                var url = `/select_manzanas_etapa?buscar=${fraccionamiento}&buscar1=${etapa}`;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayManzanas = respuesta.manzana;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectEtapa(buscar){
                let me = this;
                me.etapa_id = '';
                me.lote_id = '';
                me.manzana = '';
                me.arrayEtapas=[];
                me.arrayManzanas=[];
                me.arrayLotes=[];
                var url = '/select_etapa_proyecto?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEtapas = respuesta.etapas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getPreciosModelo(id){
                let me = this;
                me.arrayModelos=[];
                var url = '/cPuentes/getPreciosModelo?id=' + id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayModelos = respuesta.modelos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getLotesPuente(id){
                let me = this;
                me.arrayLotesPuente=[];
                me.emp_constructora = '';
                var url = '/cPuentes/getLotesPuente?id=' + id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLotesPuente = respuesta.lotes;
                    me.emp_constructora = me.arrayLotesPuente[0].emp_constructora;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarAvisos(page);
            },
            limpiarDatos(){
                this.arrayCreditosPuenteLotes=[];
                this.arrayLotes=[];
                this.arrayEtapas=[];
                this.arrayManzanas=[];
                this.arrayDatosLotes=[];
                this.arrayEtapas=[];
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
            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
            mostrarDetalle(){
                this.limpiarDatos();
                this.listado=0;
            },
            ocultarDetalle(){
                this.listado=1;
                this.listarAvisos(1);
            },
            verDetalla(data,vista){
                let me= this;
                this.listado=vista;
                //Obtener datos de cabecera
                me.id=data['id'];
                me.cabecera.banco = data['banco'];
                me.cabecera.interes = data['interes'];
                me.cabecera.apertura = data['apertura'];
                me.cabecera.status = data['status'];
                me.lic = data['lic'];
                me.cabecera.fraccionamiento = data['fraccionamiento'];
                me.selectEtapa(me.cabecera.fraccionamiento);
                me.getPreciosModelo(me.id);
                me.getLotesPuente(me.id);
                me.getPlanos(me.id);
                me.getChecklist(me.id);
                //me.getBases();
               
            },
            registrarLote(){
                let me = this;
                axios.post('/cPuentes/agregarLote',{
                    'solicitud_id': this.id,
                    'lote': this.lote_id,
                   
                }).then(function (response){
                    //Obtener detalle
                        swal({
                            position: 'top-end',
                            type: 'success',
                            title: 'Lote agregado correctamente',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        me.getLotesPuente(me.id);
                        me.arrayManzanas=[];
                        me.arrayLotes=[];
                        me.etapa_id='';
                        me.lote_id='';
                        me.manzana='';
                    
                }).catch(function (error){
                    console.log(error);
                });
            },
            storeObs(){
                let me = this;
                axios.post('/cPuentes/storeObs',{
                    'id': this.id,
                    'observacion': this.observacion,
                   
                }).then(function (response){
                    //Obtener detalle
                        swal({
                            position: 'top-end',
                            type: 'success',
                            title: 'Comentario guardado correctamente',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        me.getObs(me.id);
                        me.observacion = '';
                    
                }).catch(function (error){
                    console.log(error);
                });
            },
            enviarExp(){

                 Swal({
                    title: 'Estas seguro?',
                    animation: false,
                    customClass: 'animated bounceInDown',
                    text: "El expediente sera entregado al banco para su aprobación",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    
                    confirmButtonText: 'Si, continuar!'
                    }).then((result) => {

                        if (result.value) {

                            let me = this;
                            axios.put('/cPuentes/ingresarExpTecnico',{
                                'id': this.id
                            }).then(function (response){
                                //Obtener detalle
                                    const toast = Swal.mixin({
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 3000

                                        });

                                        toast({
                                        type: 'success',
                                        title: 'Expediente integrado correctamente'
                                    })
                                    me.getObs(me.id);
                                    me.ocultarDetalle();
                                    me.observacion = '';
                                
                            }).catch(function (error){
                                console.log(error);
                            });

                    }})

            },
            addDocumento(){
                let me = this;
                axios.post('/cPuentes/addDocChk',{
                    'id': me.id,
                    'documento': me.chk_new,
                   
                }).then(function (response){
                    //Obtener detalle
                        swal({
                            position: 'top-end',
                            type: 'success',
                            title: 'Comentario guardado correctamente',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        me.getChecklist(me.id);
                        me.cerrarModal();
                    
                }).catch(function (error){
                    console.log(error);
                });
            },
            eliminarLote(data){
                let me = this;
                axios.delete('/cPuentes/eliminarLote',{params:{
                    'lote': data.lote_id,
                    'lp': data.id,
                    'solicitud_id': me.id
                   
                }}).then(function (response){
                    //Obtener detalle
                        swal({
                            position: 'top-end',
                            type: 'success',
                            title: 'Lote eliminado correctamente',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        me.getLotesPuente(me.id);
                        me.arrayManzanas=[];
                        me.arrayLotes=[];
                        me.etapa_id='';
                        me.lote_id='';
                        me.manzana='';
                    
                }).catch(function (error){
                    console.log(error);
                });
            },
            getSinChk(){
                let me = this;
                me.arrayChk = [];

                 var url = '/cPuentes/getChkSinSolic';
                axios.get(url,{params:{
                        'id': me.id
                }}).then(function (response) {
                    var respuesta = response.data;
                    me.arrayChk = respuesta.chk;
                })
                .catch(function (error) {
                    console.log(error);
                });

            },
            deleteChk(id){
                swal({
                title: '¿Desea quitar este requisito de la solicitud?',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si, continuar!'
                }).then((result) => {
                if (result.value) {
                    let me = this;

                //Con axios se llama el metodo update de LoteController
                    axios.delete('/cPuentes/deleteChk',{
                        params: {
                        'id' : id,
                        }
                    }).then(function (response){
                        //window.alert("Cambios guardados correctamente");
                        me.getChecklist(me.id);
                        const toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                            });
                            toast({
                            type: 'success',
                            title: 'Requisito eliminado correctamente'
                        })
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })

            },
            actualizarModelo(modelo_id,precio){
                let me = this;

                axios.put('/cPuentes/actualizarPrecio',{
                    'precio': precio,
                    'id': modelo_id,
                   
                }).then(function (response){
                    //Obtener detalle
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
                        me.getLotesPuente(me.id);
                        me.getPreciosModelo(me.id);
                        me.arrayManzanas=[];
                        me.arrayLotes=[];
                        me.etapa_id='';
                        me.lote_id='';
                        me.manzana='';
                    
                }).catch(function (error){
                    console.log(error);
                });

            },
            actualizarCredito(){
                let me = this;

                axios.put('/cPuentes/actualizarSolicitud',{
                    'id': this.id,
                    'cabecera' : this.cabecera,
                    'total': this.total_precio,
                }).then(function (response){
                    //Obtener detalle
                        swal({
                            position: 'top-end',
                            type: 'success',
                            title: 'Cambios guardados correctamente',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        me.getLotesPuente(me.id);
                        me.getPreciosModelo(me.id);
                        me.ocultarDetalle();
                        me.arrayManzanas=[];
                        me.arrayLotes=[];
                        me.etapa_id='';
                        me.lote_id='';
                        me.manzana='';
                    
                }).catch(function (error){
                    console.log(error);
                });

            },
            cambiarChk(id,valor){
                let me = this;

                axios.put('/cPuentes/cambiarChk',{
                    'id': id,
                    'valor' : valor
                }).then(function (response){
                    //Obtener detalle
                        me.getChecklist(me.id);
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
            abrirModal(opcion,id){
                switch(opcion){
                    case 'obs':{
                        this.modal = 1;
                        this.tituloModal = 'Comentarios para el Crédito Puente';
                        this.arrayObs = [];
                        this.id = id;
                        this.tipoAccion = 1;
                        this.getObs(id);
                        break;
                    }
                    case 'urbanizacion':{
                        this.modal = 1;
                        this.tituloModal = 'Nuevo plano de urbanización';
                        this.clasificacion = 1;
                        this.file = '';
                        this.tipoAccion = 2;
                        this.descripcion = '';
                        this.notas ='';
                        this.fecha_entrega='';
                        break;
                    }
                    case 'edificacion':{
                        this.modal = 1;
                        this.tituloModal = 'Nuevo plano de edificación';
                        this.clasificacion = 2;
                        this.file = '';
                        this.tipoAccion = 2;
                        this.descripcion = '';
                        this.notas ='';
                        this.fecha_entrega='';
                        break;
                    }
                    case 'checklist':{
                        this.getSinChk();
                        this.modal = 1;
                        this.tituloModal = 'Checklist';
                        this.tipoAccion = 3;
                        break;
                    }
                   
                }
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.observacion = '';
                this.listarAvisos(1);
                this.chk_new = '';
                this.arrayChk =[];
            }
        },
        mounted() {
            this.listarAvisos(1);
            this.selectProyectos();
            this.selectBancos();
        }
    }
</script>
<style>
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: fixed !important;
        background-color: #3c29297a !important;
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
    .div-error{
        display:flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
    @media (min-width: 600px){
        .btnagregar{
        margin-top: 2rem;
        }
    }
</style>