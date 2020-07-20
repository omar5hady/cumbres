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
                        <button type="button" @click="abrirModal('nuevo')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nueva Solicitud
                        </button> &nbsp;&nbsp;
                        <button type="submit" v-if="descripcion == 1" @click="listarSolicitudes(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2,status)" class="btn btn-default">
                            <i class="fa fa-mail-reply-all"></i> Regresar
                        </button>
                    </div>

                <!-------------------  Div Reportes de detalles  --------------------->
                    <div class="card-body" v-if="descripcion == 0">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-5" v-model="criterio2" @click="selectFraccionamientos()">
                                        <option value="lotes.fraccionamiento_id">Proyecto</option>
                                        <option value="solic_detalles.cliente">Cliente</option>
                                        <option value="contratistas.nombre">Contratista</option>
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

                                    <input v-else type="text"  v-model="buscar2" @keyup.enter="listarSolicitudes(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2,status)" class="form-control" placeholder="Texto a buscar">
                                   
                                </div>
                            </div>
                            <div class="col-md-6" v-if="criterio2=='lotes.fraccionamiento_id'">
                                <div class="input-group">
                                    <select class="form-control" v-if="criterio2=='lotes.fraccionamiento_id'" @click="selectLotesManzana(buscar2,b_etapa2,b_manzana2)" @keyup.enter="listarSolicitudes(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2,status)" v-model="b_manzana2" >
                                        <option value="">Manzana</option>
                                        <option v-for="manzana in arrayAllManzanas" :key="manzana.manzana" :value="manzana.manzana" v-text="manzana.manzana"></option>
                                    </select>

                                    <input v-if="criterio2=='lotes.fraccionamiento_id'" type="text"  v-model="b_lote2" @keyup.enter="listarSolicitudes(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2,status)" class="form-control" placeholder="Lote a buscar">

                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <div class="input-group">
                                    <select class="form-control" v-model="status" >
                                        <option value="">Status</option>
                                        <option value="0">Pendiente</option>
                                        <option value="1">En proceso</option>
                                        <option value="2">Concluido</option>
                                        <option value="3">Cancelado</option>
                                    </select>
                                    <button type="submit" @click="listarSolicitudes(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2,status)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                            
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr> 
                                        <th></th>
                                        <th># Folio</th>
                                        <th>Cliente</th>
                                        <th>Contacto</th>
                                        <th>Proyecto</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th>Lote</th>
                                        <th>Modelo</th>
                                        <th>Contratista</th>
                                        <th>Fecha de reporte</th>
                                        <th>Disponibilidad</th>
                                        <th>Costo</th>
                                        <th>Status</th>
                                        <th>Imprimir Reporte</th>
                                        <th colspan="2">Fecha programada</th>
                                        <th>Reporte de conclusión</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="contratos in arraySolicitudes" :key="contratos.id" @dblclick="verDetalles(contratos.id)">
                                        <td class="td2">
                                            <button v-if="contratos.status == 0" title="Cancelar" type="button" @click="cancelarSolicitud(contratos.id)" class="btn btn-danger btn-sm">
                                                <i class="icon-trash"></i>
                                            </button>
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
                                        <td class="td2" v-text="contratos.cliente"></td>
                                        <td class="td2">
                                            <a title="Llamar" class="btn btn-dark" :href="'tel:'+contratos.celular"><i class="fa fa-phone fa-lg"></i></a>
                                            <a title="Enviar whatsapp" class="btn btn-success" target="_blank" :href="'https://api.whatsapp.com/send?phone=+52'+contratos.celular+'&text=Hola'"><i class="fa fa-whatsapp fa-lg"></i></a>
                                        </td>
                                        <td class="td2" v-text="contratos.fraccionamiento"></td>
                                        <td class="td2" v-text="contratos.etapa"></td>
                                        <td class="td2" v-text="contratos.manzana"></td>
                                        <td class="td2" v-text="contratos.num_lote"></td>
                                        <td class="td2" v-text="contratos.modelo"></td>
                                        <td class="td2" v-text="contratos.nombre"></td>
                                        <td class="td2" v-text="this.moment(contratos.created_at).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td>
                                           <p>
                                           <b><span style="color:blue;" v-if="contratos.lunes == 1">Lunes</span>
                                           <span style="color:blue;" v-if="contratos.martes == 1">Martes</span>
                                           <span style="color:blue;" v-if="contratos.miercoles == 1">Miercoles</span>
                                           <span style="color:blue;" v-if="contratos.jueves == 1">Jueves</span>
                                           <span style="color:blue;" v-if="contratos.viernes == 1">Viernes</span>
                                           <span style="color:blue;" v-if="contratos.sabado == 1">Sabado</span>
                                           </b></p>
                                       </td>
                                        <td class="td2" v-text="'$'+formatNumber(contratos.costo)"></td>
                                        <template>
                                            <td class="td2" v-if="contratos.status == '0'">
                                                <span class="badge badge-warning">Pendiente</span>
                                            </td>
                                            <td class="td2" v-if="contratos.status == '1'">
                                                <span class="badge badge-warning">En proceso</span>
                                            </td>
                                            <td class="td2" v-if="contratos.status == '2'">
                                                <span class="badge badge-success">Concluido</span>
                                            </td>
                                        </template>
                                        <td class="td2">
                                            <a title="Ver revision" type="button" 
                                                    class="btn btn-danger pull-right" target="_blank" :href="'/detalles/reporteDetalles/pdf/'+contratos.id">
                                                    <i class="fa fa-file-pdf-o"></i>&nbsp;Reporte
                                            </a> 
                                        </td>
                                        <template v-if="contratos.status == 3">
                                                
                                            <td class="td2" colspan="2" v-if="contratos.status == '3'">
                                                <span class="badge badge-danger">Cancelado</span>
                                            </td>  
                                        </template>
                                        <template v-else>
                                        
                                            <td class="td2">
                                                Fecha: <input v-if="contratos.status != '2'"  type="date" v-on:change="actualizarFechaProgram($event.target.value,contratos.id)"  :id="contratos.id" :value="contratos.fecha_program" class="form-control" >
                                                <label v-else v-text="this.moment(contratos.fecha_program).locale('es').format('DD/MMM/YYYY')"></label>
                                            </td>
                                            <td class="td2">                                           
                                                Hora: <input v-if="contratos.status != '2'"  type="time" v-on:change="actualizarHoraProgram($event.target.value,contratos.id)" :id="contratos.id" :value="contratos.hora_program" class="form-control" >
                                                <label v-else v-text="contratos.hora_program"></label>
                                            </td>
                                            
                                        </template>
                                        
                                        <template>    
                                            <td class="td2" v-if="contratos.status == '2'">
                                                <a title="Ver revision" type="button" 
                                                    class="btn btn-danger pull-right" target="_blank" :href="'/detalles/reporteDetalles/reporteConclusionPDF/'+contratos.id">
                                                    <i class="fa fa-file-pdf-o"></i>&nbsp;Reporte conclusión
                                                </a> 
                                            </td>  
                                            <td class="td2" v-if="contratos.status == '0'">
                                                <span class="badge badge-warning">Pendiente</span>
                                            </td>
                                            <td class="td2" v-if="contratos.status == '1'">
                                                <span class="badge badge-warning">En proceso</span>
                                            </td>  

                                            <td class="td2" v-if="contratos.status == '3'">
                                                <span class="badge badge-danger">Cancelado</span>
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
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2,status)">Inicio</a>
                                </li>
                                <li class="page-item" v-if="pagination2.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page - 1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2,status)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber2" :key="page" :class="[page == isActived2 ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(page,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2,status)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination2.current_page < pagination2.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page + 1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2,status)">Sig</a>
                                </li>
                                <li class="page-item" v-if="pagination2.last_page > 7 && pagination2.current_page<pagination2.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.last_page,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2,status)">Ultimo</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                <!-------------------  Fin Div para Reportes de Detalles  --------------------->

                <!-------------------  Div descripcion de detalles  --------------------->
                    <div class="card-body" v-if="descripcion == 1">
                            
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr> 
                                        <th>General</th>
                                        <th>Subconcepto</th>
                                        <th>Detalle</th>
                                        <th>Observación</th>
                                        <th>Garantia</th>
                                        <th>Costo</th>
                                        <th>Fecha conclusión</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="descripcion in arrayDescripcion" :key="descripcion.id">
                                        <td class="td2" v-text="descripcion.general"></td>
                                        <td class="td2" v-text="descripcion.subconcepto"></td>
                                        <td class="td2" v-text="descripcion.detalle"></td>
                                        <td class="td2" v-text="descripcion.observacion"></td>
                                        <td v-if="descripcion.garantia == 1"><span class="badge badge-success">Si</span> </td>
                                        <td v-else> <span  class="badge badge-danger">No</span> </td>
                                        <td class="td2" v-text="'$'+formatNumber(descripcion.costo)"></td> 
                                        <td class="td2" v-if="descripcion.fecha_concluido" v-text="this.moment(descripcion.fecha_concluido).locale('es').format('DD/MMM/YYYY')"></td>                      
                                        <td class="td2" v-else v-text="'En proceso'"></td>
                                        <template>
                                            <td class="td2" v-if="descripcion.fecha_concluido && descripcion.revisado==0">
                                                <button type="button" @click="revisarDetalle(descripcion.id, 1,'')" class="btn btn-success btn-sm" title="Aprobado">
                                                    <i class="fa fa-check-square"></i>
                                                </button>
                                                <button type="button" @click="abrirModal('revisar',descripcion)" class="btn btn-danger btn-sm" title="Rechazado">
                                                    <i class="fa fa-times-circle-o"></i>
                                                </button>
                                            </td>
                                            <td class="td2" v-else-if="descripcion.fecha_concluido && descripcion.revisado==2">
                                                <span  class="badge badge-success">Concluido</span>
                                            </td>
                                            <td v-else></td>
                                        </template>
                                    </tr>
                                </tbody>
                            </table>  
                        </div>
                    </div>
                <!-------------------  Fin Div para Reportes de Detalles  --------------------->

                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
         
            <!--Inicio del modal para diversos llenados de tabla en historial -->
                <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
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
                                    <label class="col-md-2 form-control-label" for="text-input">Proyecto</label>
                                    <div class="col-md-3">
                                        <select class="form-control" v-model="proyecto" @click="selectEtapaEntregada(proyecto),etapa_entregada = '',manzana_entregada = '',lote_entregado=''">
                                            <option value="">Seleccione</option>
                                            <option v-for="fraccionamiento in arrayFraccionamientos2" :key="fraccionamiento.nombre" :value="fraccionamiento.id" v-text="fraccionamiento.nombre"></option>
                                        </select>
                                    </div>

                                    <label class="col-md-1 form-control-label" for="text-input">Etapa</label>
                                    <div class="col-md-3">
                                        <select class="form-control" v-model="etapa_entregada" @click="selectManzanaEntregada(etapa_entregada),manzana_entregada = '',lote_entregado=''">
                                            <option value=''> Seleccione </option>
                                            <option v-for="etapas in arrayEtapas" :key="etapas.etapa" :value="etapas.etapa" v-text="etapas.etapa"></option>
                                        </select>
                                    </div>

                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Manzana</label>
                                    <div class="col-md-3">
                                        <select class="form-control" v-model="manzana_entregada" @click="selectLotesEntregado(manzana_entregada, etapa_entregada, proyecto),lote_entregado=''">
                                            <option value=''> Seleccione </option>
                                            <option v-for="manzanas in arrayManzanas" :key="manzanas.manzana" :value="manzanas.manzana" v-text="manzanas.manzana"></option>
                                        </select>
                                    </div>

                                    <label class="col-md-1 form-control-label" for="text-input">Lote</label>
                                    <div class="col-md-2">
                                        <select class="form-control" v-model="lote_entregado" @click="getDatosLote(lote_entregado)">
                                            <option value=''> Seleccione </option>
                                            <option v-for="lotes in arrayLotes" :key="lotes.id" :value="lotes.id" v-text="lotes.num_lote"></option>
                                        </select>
                                    </div>

                                </div>

                            <template v-if="direccion != '' || direccion">
                                <div class="form-group row line-separator"></div>

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Modelo</label>
                                    <div class="col-md-4">
                                        <input type="text" disabled v-model="modelo" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Dirección</label>
                                    <div class="col-md-8">
                                        <input type="text" disabled v-model="direccion" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Cliente</label>
                                    <div class="col-md-4">
                                        <input type="text" disabled v-model="cliente" class="form-control">
                                    </div>

                                    <label class="col-md-1 form-control-label" for="text-input">Celular</label>
                                    <div class="col-md-3">
                                        <input type="text" v-model="celular" maxlength="10" v-on:keypress="isNumber($event)" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input"># Dias desde entrega</label>
                                    <div class="col-md-3">
                                        <h6 v-text="dias_entregado"></h6>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Contratista</label>
                                    <div class="col-md-6">
                                        <select class="form-control" v-model="contratista" :disabled="dias_entregado <= 60">
                                            <option value=''> Seleccione </option>
                                            <option v-for="contratista in arrayContratistas" :key="contratista.id" :value="contratista.id" v-text="contratista.nombre"></option>
                                        </select>
                                    </div>
                                </div>
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

                                <div class="form-group row line-separator"></div>

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Detalle General</label>
                                    <div class="col-md-4">
                                        <select class="form-control" v-model="id_gral" @click="selectSubconcepto(id_gral)" >
                                            <option value=''> Seleccione </option>
                                            <option v-for="general in arrayGenerales" :key="general.id" :value="general.id" v-text="general.general"></option>
                                        </select>
                                    </div>

                                    <label class="col-md-2 form-control-label" for="text-input">Subconcepto</label>
                                    <div class="col-md-4">
                                        <select class="form-control" v-model="id_sub" @click="selectDetalle(id_sub)" >
                                            <option value=''> Seleccione </option>
                                            <option v-for="subconcepto in arraySubconceptos" :key="subconcepto.id" :value="subconcepto.id" v-text="subconcepto.subconcepto"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Detalle</label>
                                    <div class="col-md-4">
                                        <select class="form-control" v-model="id_detalle" @click="getDatosDetalle(id_detalle)">
                                            <option value=''> Seleccione </option>
                                            <option v-for="detalle in arrayDetalles" :key="detalle.id" :value="detalle.id" v-text="detalle.detalle"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" v-if="detalle != ''" for="text-input">Descripción</label>
                                    <div class="col-md-6" v-if="detalle != ''">
                                        <input type="text" v-model="observacion" class="form-control">
                                    </div>

                                    <div class="col-md-1" v-if="detalle != ''">
                                        <button type="button" @click="addDetalle(id_detalle)" title="Añadir" class="btn btn-success">
                                            <i class="icon-plus"></i>&nbsp;
                                        </button>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-12" v-if="arrayListadoDetalles.length">
                                    <div class="form-group row">
                                        <div class="table-responsive col-md-12">
                                            <table class="table table-bordered table-striped table-sm">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>General</th>
                                                        <th>Subconcepto</th>
                                                        <th>Detalle</th>
                                                        <th>Descripción</th>
                                                        <th>Garantia</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
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
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" v-if="arrayListadoDetalles.length" for="text-input">Observaciones generales</label>
                                    <div class="col-md-6" v-if="arrayListadoDetalles.length">
                                        <textarea v-model="obs_gen" cols="50" rows="4"></textarea>
                                    </div>

                                    
                                </div>

                                
                            </template>
                                
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
                                <button type="button" v-if="arrayListadoDetalles.length" class="btn btn-success" @click="registrarSolicitud()">Guardar</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
            <!--Fin del modal-->

            <!--Inicio del modal para diversos llenados de tabla en historial -->
                <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modalArchivo}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
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
                                    <div class="col-md-8">
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

            <!--Inicio del modal observaciones-->
                <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal2}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-primary modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" v-text="'Rechazar'"></h4>
                                <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                                <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Observación</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="observacion" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <!-- Botones del modal -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                                <button class="btn btn-primary" @click="revisarDetalle(descripcion_id,0,observacion)">Guardar</button>
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
                modal3: 0,
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
                status:'',
                tipoAccion:0,
                observacion:'',
                descripcion: 0,
                descripcion_id :0,

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
                var url = '/detalles/indexSolicitudes?page=' + page + '&buscar=' + buscar + '&b_etapa=' + b_etapa + '&b_manzana=' + b_manzana + '&b_lote=' + b_lote + '&criterio=' + criterio + '&status=' + status;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arraySolicitudes = respuesta.solicitudes.data;
                    me.pagination2 = respuesta.pagination;
                    
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
            
            cambiarPagina2(page,buscar,b_etapa,b_manzana,b_lote,criterio,status){
                let me = this;
                //Actualiza la pagina actual
                me.pagination2.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarSolicitudes(page,buscar,b_etapa,b_manzana,b_lote,criterio,status);
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
                this.modal3 = 0;
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

                    case 'observaciones':{
                        this.modal3 =1;
                        this.tituloModal='Observaciones';
                        this.folio = data['folio'];
                        this.observacion = '';
                        break;
                    }

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
