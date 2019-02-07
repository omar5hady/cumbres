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
                        <i class="fa fa-align-justify"></i>Licencias

                        <!--   Boton   -->
                        <a class="btn btn-success" v-bind:href="'/licencias/resume_excel'" >
                            <i class="icon-pencil"></i>&nbsp;Descargar resumen
                        </a>
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-5" v-model="criterio" @click="selectFraccionamientos()">
                                        <option value="lotes.fraccionamiento_id">Proyecto</option>
                                        <option value="modelos.nombre">Modelo</option>
                                        <option value="arquitecto">Arquitecto</option>
                                        <option value="licencias.num_licencia">Num.Licencia</option>
                                        <option value="licencias.f_planos">Planos</option>
                                    </select>

                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar" >
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>
                                    <input v-if="criterio=='lotes.fraccionamiento_id'" type="text"  v-model="b_manzana" @keyup.enter="listarLicencias(1,buscar,b_manzana,b_lote,b_modelo,b_arquitecto,criterio,buscar2)" class="form-control" placeholder="Manzana">
                                    <input v-if="criterio=='lotes.fraccionamiento_id'" type="text"  v-model="b_lote" @keyup.enter="listarLicencias(1,buscar,b_manzana,b_lote,b_modelo,b_arquitecto,criterio,buscar2)" class="form-control" placeholder="# Lote">
                                    <input v-if="criterio=='lotes.fraccionamiento_id'" type="text"  v-model="b_modelo" @keyup.enter="listarLicencias(1,buscar,b_manzana,b_lote,b_modelo,b_arquitecto,criterio,buscar2)" class="form-control" placeholder="Modelo">
                                    <input v-if="criterio=='lotes.fraccionamiento_id'" type="text"  v-model="b_arquitecto" @keyup.enter="listarLicencias(1,buscar,b_manzana,b_lote,b_modelo,b_arquitecto,criterio,buscar2)" class="form-control" placeholder="Arquitecto">

                                    <input type="date" v-if="criterio=='licencias.f_planos'" v-model="buscar" @keyup.enter="listarLicencias(1,buscar,b_manzana,b_lote,b_modelo,b_arquitecto,criterio,buscar2)" class="form-control col-md-6" placeholder="Desde" >
                                    <input type="date" v-if="criterio=='licencias.f_planos'" v-model="buscar2"  @keyup.enter="listarLicencias(1,buscar,b_manzana,b_lote,b_modelo,b_arquitecto,criterio,buscar2)" class="form-control col-md-6" placeholder="Hasta" >

                                    <input v-if="criterio!='lotes.fraccionamiento_id' && criterio!='licencias.f_planos' " type="text"  v-model="buscar" @keyup.enter="listarLicencias(1,buscar,b_manzana,b_lote,b_modelo,b_arquitecto,criterio,buscar2)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarLicencias(1,buscar,b_manzana,b_lote,b_modelo,b_arquitecto,criterio,buscar2)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        
                                        <th>Opciones</th>
                                        <th>Proyecto</th>
                                        <th>Manzana</th>
                                        <th># Lote</th>
                                        <th>Terreno mts&sup2;</th>
                                        <th>Construcción mts&sup2;</th>
                                        <th>Modelo</th>
                                        <th>Arquitecto</th>
                                        <th>Siembra</th>
                                        <th>Planos</th>
                                        <th>DRO</th>
                                        <th>Ingreso</th>
                                        <th>Salida</th>
                                        <th>Num. Licencia</th>
                                        <th>Credito puente</th>
                                
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="licencias in arrayLicencias" :key="licencias.id">
                                        
                                        <td >
                                            <button title="Editar" type="button" @click="abrirModal('lote','actualizar',licencias)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                            </button> 
                                            <button type="button" @click="abrirModal2('lote','ver',licencias)" class="btn btn-info btn-sm">
                                            <i class="icon-magnifier"></i>
                                            </button>
                                            <button title="Subir foto y predial" type="button" @click="abrirModal('lote','subirArchivo',licencias)" class="btn btn-default btn-sm">
                                            <i class="icon-cloud-upload"></i>
                                            </button>
                                            <a  title="Descargar predial" v-if ="licencias.foto_predial" class="btn btn-success btn-sm" v-bind:href="'/downloadPredial/'+licencias.foto_predial">
                                            <i class="fa fa-arrow-circle-down fa-lg"></i>
                                            </a>
                                        
                                        </td>
                                        <td v-text="licencias.proyecto"></td>
                                        <td v-text="licencias.manzana"></td>
                                        <td v-text="licencias.num_lote"></td>
                                        <td v-text="licencias.terreno"></td>
                                        <td v-text="licencias.construccion"></td>
                                        <!--Modelo-->
                                        <td>
                                            <span v-if = "licencias.modelo!='Por Asignar' && licencias.cambios==0" class="badge badge-success" v-text="licencias.modelo"></span>
                                            <span v-if = "licencias.modelo=='Por Asignar'" class="badge badge-danger">Por Asignar</span>
                                            <span v-if = "licencias.cambios==1 && licencias.modelo_ant !== 'N/A'" class="badge badge-warning" v-text="licencias.modelo_ant + '->' + licencias.modelo"></span>
                                            <span v-if = "licencias.cambios==1 && licencias.modelo_ant == 'N/A'" class="badge badge-warning" v-text="licencias.modelo"></span>
                                        </td>
                                        <!--Arquitecto -->
                                        <td>
                                            <span v-if = "licencias.arquitecto!='Sin Asignar  '" class="badge badge-success" v-text="'Arq. '+licencias.arquitecto"></span>
                                            <span v-else class="badge badge-danger"> Por Asignar </span>
                                        </td>
                                        <!-- SIEMBRA -->
                                        <td v-if="!licencias.siembra" v-text="''"></td>
                                        <td v-else v-text="this.moment(licencias.siembra).locale('es').format('DD/MMM/YYYY')"></td>
                                        <!-- Fecha planos -->    
                                        <td v-if="!licencias.f_planos" v-text="''"></td>
                                        <td v-else v-text="this.moment(licencias.f_planos).locale('es').format('DD/MMM/YYYY')"></td>
                                        <!-- Fecha planos -->
                                        <td>
                                            <span v-if = "licencias.perito!='Sin Asignar  '" class="badge badge-success" v-text="'Arq. '+licencias.perito"></span>
                                            <span v-else class="badge badge-danger"> Por Asignar </span>
                                        </td>

                                        <!-- Fecha Ingreso -->
                                        <td v-if="!licencias.f_ingreso" v-text="''"></td>
                                        <td v-else v-text="this.moment(licencias.f_ingreso).locale('es').format('DD/MMM/YYYY')"></td>
                                        <!-- Fecha Salida -->
                                        <td v-if="!licencias.f_salida" v-text="''"></td>
                                        <td v-else v-text="this.moment(licencias.f_salida).locale('es').format('DD/MMM/YYYY')"></td>
                                        
                                        <td  v-if="!licencias.foto_lic" v-text="licencias.num_licencia"></td>
                                        <td v-else style="width:7%"><a class="btn btn-default btn-sm"  v-text="licencias.num_licencia" v-bind:href="'/downloadLicencias/'+licencias.foto_lic"></a></td>
                                        <td  v-text="licencias.credito_puente"></td>
                                        
                                        
                                        
                                    </tr>                               
                                </tbody>
                            </table>  
                        </div>
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
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Arquitectos</label>
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
                                            <option v-for="arquitectos in arrayArquitectos" :key="arquitectos.id" :value="arquitectos.id" v-text="'Arq. ' + arquitectos.name"></option>
                                        </select>
                                    </div>
                                </div>
                                <br><br>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Planos</label>
                                    <div class="col-md-6">
                                       <input type="date" v-model="f_planos" class="form-control" >
                                    </div>
                                </div>

                                  <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Ingreso de licencia</label>
                                    <div class="col-md-6">
                                       <input type="date" v-model="f_ingreso" class="form-control" >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Salida</label>
                                    <div class="col-md-6">
                                       <input type="date" v-model="f_salida" class="form-control" >
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Numero de licencia</label>
                                    <div class="col-md-6">
                                       <input type="number" v-model="num_licencia" class="form-control" >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Agregar Observación</label>
                                    <div class="col-md-6">
                                       <button type="button" class="btn btn-danger" @click="abrirModal3('lote','observacion',id)">Nueva Observación</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button"  class="btn btn-primary" @click="actualizarLicencia()">Actualizar</button>
                            
                        </div>
                    </div>
                      <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->

            <!--Inicio del modal consulta-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar': modal2}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal2"></h4>
                            <button type="button" class="close" @click="cerrarModal2()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

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
                                    <label class="col-md-3 form-control-label" for="text-input">Planos</label>
                                    <div class="col-md-4">

                                        <input type="text" style="width:200px;" disabled v-model="f_planos" class="form-control" placeholder="Planos">
                                  
                                    </div>
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
                                        <button type="button" class="btn btn-info pull-right" @click="abrirModal3('lote','ver_todo',id)">Ver todos</button>
                                    </div>
                                </div>
                                

                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal2()">Cerrar</button>
                           
                        </div>
                    </div>
                      <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal consulta-->

             <!--Inicio del modal observaciones-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar': modal3}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
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

                                <div class="form-group row" v-if="tipoAccion==3">
                                    <label class="col-md-3 form-control-label" for="text-input">Observacion</label>
                                    <div class="col-md-6">
                                         <textarea rows="5" cols="30" v-model="observacion" class="form-control" placeholder="Observacion"></textarea>

                                    </div>
                                </div>
                                <!-- <div class="form-group row"  v-if="tipoAccion==3">
                                    <label class="col-md-3 form-control-label" for="text-input">Usuario</label>
                                    <div class="col-md-6">
                                        <input type="text" disabled v-model="usuario" class="form-control" placeholder="Usuario">
                                    </div>
                                </div> -->
                                <!--//////////tabla de consulta de observaciones//////////////-->
                                <table class="table table-bordered table-striped table-sm" v-if="tipoAccion == 4">
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
                            <button type="button" class="btn btn-secondary" @click="cerrarModal3()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button"  v-if="tipoAccion==3"  class="btn btn-primary" @click="agregarComentario()">Guardar</button>
                        </div>
                    </div>
                      <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            

<!-- Modal para la carga de foto de licencia -->
<div class="modal fade" tabindex="-1" :class="{'mostrar': modal4}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal4"></h4>
                            <button type="button" class="close" @click="cerrarModal4()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
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

                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal4()">Cerrar</button>
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
               
                id: 0,
                siembra:'',
                f_planos:'',
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
                terreno : 0,
                construccion : 0,
                arrayLicencias : [],
                arrayArquitectos:[],
                arrayFraccionamientos:[],
                arrayObservacion:[],
                modal : 0,
                modal2 : 0,
                modal3 : 0,
                modal4 : 0,
                tituloModal : '',
                tituloModal2 : '',
                tituloModal3: '',
                tituloModal4: '',
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
                criterio : 'modelos.nombre', 
                buscar : '',
                buscar2 : '',
                b_manzana : '',
                b_lote : '',
                b_modelo : '',
                b_arquitecto : '',
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

//funciones para carga de los prediales     

            onImageChangePredial(e){

                console.log(e.target.files[0]);

                this.foto_predial = e.target.files[0];

            },

            formSubmitPredial(e) {

                e.preventDefault();

                let currentObj = this;
            
                let formData = new FormData();
           
                formData.append('foto_predial', this.foto_predial);
                let me = this;
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
                    me.cerrarModal4();
                   me.listarLicencias(1,'','','','','','fraccionamientos.nombre','');

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
                    me.cerrarModal4();
                   me.listarLicencias(1,'','','','','','fraccionamientos.nombre','');

                })

                .catch(function (error) {

                    currentObj.output = error;

                });

            },

            /**Metodo para mostrar los registros */
            listarLicencias(page, buscar,b_manzana,b_lote,b_modelo,b_arquitecto, criterio,buscar2){
                let me = this;
                var url = '/licencias?page=' + page + '&buscar=' + buscar + '&b_manzana=' + b_manzana + '&b_lote='+ b_lote + '&b_modelo='+ b_modelo + '&b_arquitecto='+ b_arquitecto + '&criterio=' + criterio + '&buscar2=' + buscar2;
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

            agregarComentario(){
                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/observacion/registrar',{
                    'lote_id': this.lote_id,
                    'comentario': this.observacion,
                    'usuario': this.usuario
                }).then(function (response){
                    me.cerrarModal3(); //al guardar el registro se cierra el modal
                    
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
            
            actualizarLicencia(){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/licencias/actualizar',{
                    'id':this.id,
                    'f_planos' : this.f_planos,
                    'f_ingreso' : this.f_ingreso,
                    'f_salida' : this.f_salida,
                    'num_licencia' : this.num_licencia,
                    'arquitecto_id':this.arquitecto_id,
                    'perito_dro' : this.perito_dro
                    
                    
                }).then(function (response){
                    me.cerrarModal();
                    me.listarLicencias(1,'','','','','','fraccionamientos.nombre','');
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
                this.f_ingreso = '';
                this.f_salida = '';
                this.num_licencia = '';
                
                
                this.errorLote = 0;
                this.errorMostrarMsjLote = [];

            },
            cerrarModal2(){
                this.modal2 = 0;
                this.tituloModal2 = '';
                this.f_planos='';
                this.f_ingreso='';
                this.f_salida='';
                this.num_licencia='';
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
                
            },
            cerrarModal3(){
                this.modal3 = 0;
                this.tituloModal3 = '';
                this.usuario = '';
                this.observacion = '';
            },
           cerrarModal4(){
                this.modal4 = 0;
                this.tituloModal4 = '';
                this.num_licencia = '';
                this.foto_lic = '';
                this.foto_predial = '';
                this.errorLote = 0;
                this.errorMostrarMsjLote = [];

            },


           
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(licencias, accion,data =[]){
                switch(licencias){
                    case "lote":
                    {
                        switch(accion){
                           
                            case 'actualizar':
                            {
                                this.modal =1;
                                this.tituloModal='Actualizar Licencia';
                                this.f_planos=data['f_planos'];
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
                                this.modal4 =1;
                                this.tituloModal4='Subir Archivo';
                                this.tipoAccion=5;
                                this.id=data['id'];
                                this.num_licencia=data['num_licencia'];
                                this.foto_lic=data['foto_lic'];
                                this.foto_predial=data['foto_predial'];
                                break;
                            }
                           
                        }
                    }
                }this.selectArquitectos();

            },
             abrirModal3(licencias,accion,lote){
             switch(licencias){
                    case "lote":
                    {
                        switch(accion){
                            
                            case 'observacion':
                            {
                                this.modal3 =1;
                                this.tituloModal3='Agregar Observación';
                                this.observacion='';
                                this.usuario='';
                                this.lote_id=lote;
                                this.tipoAccion= 3;
                                break;
                            }
                             case 'ver_todo':
                            {
                                this.modal3 =1;
                                this.tituloModal3='Consulta Observaciones';
                                this.tipoAccion= 4;
                                break;  
                            }
                            
                        }
                    }
                 
             }
                
         },
            abrirModal2(licencias, accion,data =[]){
                switch(licencias){
                    case "lote":
                    {
                        switch(accion){
                           
                            case 'ver':
                            {
                                this.modal2 =1;
                                this.tituloModal2='Consulta ';
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
                                break;
                            }
                            
                        }
                    }
                }this.selectArquitectos();
                 this.selectUltimoComentario(data['id']);
                 this.listarObservacion(1, data['id']);

            }
        
        },
       
        mounted() {
            this.listarLicencias(1,this.buscar,this.b_manzana,this.b_lote,this.b_modelo,this.b_arquitecto,this.criterio,this.buscar2);
            this.selectFraccionamientos();
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
    .scroll-box {
            overflow-x: scroll;
            width: auto;
            padding: 1rem
        }
</style>
