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
                        <i class="fa fa-align-justify"></i> Equipamientos solicitados
                    </div>

                <!-------------------  Div historial equipamientos  --------------------->
                <template v-if="checklist == 0">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-10">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-5" v-model="criterio2" @click="selectFraccionamientos()">
                                        <option value="lotes.fraccionamiento_id">Proyecto</option>
                                        <option value="c.nombre">Cliente</option>
                                        <option value="contratos.id"># Folio</option>
                                        <option value="proveedores.proveedor">Proveedor</option>
                                    </select>

                                    
                                    <select class="form-control" v-if="criterio2=='lotes.fraccionamiento_id'" v-model="buscar2" @click="selectEtapa(buscar2)">
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamiento in arrayFraccionamientos2" :key="fraccionamiento.nombre" :value="fraccionamiento.id" v-text="fraccionamiento.nombre"></option>
                                    </select>

                                    <select class="form-control" v-if="criterio2=='lotes.fraccionamiento_id'" v-model="b_etapa2"> 
                                        <option value="">Etapa</option>
                                        <option v-for="etapa in arrayEtapas2" :key="etapa.num_etapa" :value="etapa.id" v-text="etapa.num_etapa"></option>
                                    </select>

                                    
                                    <input type="text" v-if="criterio2=='lotes.fraccionamiento_id'" v-model="b_manzana2" class="form-control" placeholder="Manzana a buscar">
                                    <input type="text" v-if="criterio2=='lotes.fraccionamiento_id'" v-model="b_lote2" class="form-control" placeholder="Lote a buscar">

                                    <input v-else type="text"  v-model="buscar2" @keyup.enter="listarHistorial(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)" class="form-control" placeholder="Texto a buscar">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-4" v-model="status">
                                        <option value="">Todos</option>
                                        <option value="1">Pendiente</option>
                                        <option value="2">En proceso</option>
                                        <option value="3">Revisión</option>
                                        <option value="4">Aprobados</option>
                                        <option value="0">Rechazados</option>
                                    </select>
                                    <button type="submit" @click="listarHistorial(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr> 
                                        <th></th>
                                        <th># Ref</th>
                                        <th>Cliente</th>
                                        <th>Proyecto</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th>Lote</th>
                                        <th>Proveedor</th>
                                        <th>Equipamiento</th>
                                        <th>Fecha colocación</th>
                                        <th>Fecha fin de instalación</th>
                                        <th>Status</th>
                                        <th>Recepción</th>
                                        <th>Observaciones</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="equipamientos in arrayHistorialEquipamientos" :key="equipamientos.id">
                                        <template>
                                            <td v-if="equipamientos.control == 0">
                                                <i class="btn btn-success btn-sm fa fa-check"></i>
                                            </td>
                                            <td class="td2" v-else-if="equipamientos.control == 1">
                                                <i class="btn btn-primary btn-sm fa fa-exchange"></i> A reasignar
                                            </td>
                                            <td  class="td2" v-else>
                                                <i title="Cancelado" class="btn btn-danger btn-sm fa fa-exclamation-triangle"></i> Cancelado
                                            </td>
                                            <td class="td2" v-text="equipamientos.folio"></td>
                                            <td class="td2" v-text="equipamientos.nombre_cliente"></td>
                                            <td class="td2" v-text="equipamientos.proyecto"></td>
                                            <td class="td2" v-text="equipamientos.etapa"></td>
                                            <td class="td2" v-text="equipamientos.manzana"></td>
                                            <td class="td2" v-text="equipamientos.num_lote"></td>
                                            <td class="td2" v-text="equipamientos.proveedor"></td>
                                            <td class="td2" v-text="equipamientos.equipamiento"></td>
                                            <template>
                                                <td v-if="equipamientos.fecha_colocacion" class="td2" v-text=" this.moment(equipamientos.fecha_colocacion).locale('es').format('DD/MMM/YYYY')"></td>
                                                <td v-else class="td2" v-text="'Sin fecha'"></td>    
                                            </template>
                                            <template>
                                                <td v-if="equipamientos.fin_instalacion" class="td2" v-text=" this.moment(equipamientos.fin_instalacion).locale('es').format('DD/MMM/YYYY')"></td>
                                                <td v-else class="td2" v-text="'Sin fecha'"></td>    
                                            </template>
                                            <template>
                                                <td v-if="equipamientos.status == '0'" class="td2">
                                                    <span class="badge badge-warning">Rechazado</span>
                                                </td>
                                                <td v-if="equipamientos.status == '1'" class="td2">
                                                    <span class="badge badge-primary">Pendiente</span>
                                                </td>
                                                <td v-if="equipamientos.status == '2'" class="td2">
                                                    <span class="badge badge-primary">En proceso de colocación</span>
                                                </td>
                                                <td v-if="equipamientos.status == '3'" class="td2">
                                                    <span class="badge badge-primary">En Revisión</span>
                                                </td>
                                                <td v-if="equipamientos.status == '4'" class="td2">
                                                    <span class="badge badge-success">Aprobado</span>
                                                </td>  
                                                <td v-if="equipamientos.status == '5'" class="td2">
                                                    <span class="badge badge-danger">Cancelado</span>
                                                </td>   
                                            </template>
                                            <td> 
                                                <button v-if="equipamientos.recepcion == 1 && equipamientos.status == 3" title="Realizar recepcion" type="button" 
                                                @click="mostrarCheckList(equipamientos)" class="btn btn-dark pull-right">
                                                    <i class="fa fa-check-square-o"></i> Realizar recepción
                                                </button> 
                                                <button v-else-if="equipamientos.recepcion == 0 && equipamientos.status == 3" title="Realizar recepcion" type="button" 
                                                @click="mostrarCheckList(equipamientos)" class="btn btn-dark pull-right">
                                                    <i class="fa fa-check-square-o"></i> Realizar recepción
                                                </button>
                                                <button v-else-if="equipamientos.recepcion == 1 && equipamientos.status == 4" title="Realizar recepcion" type="button" 
                                                @click="mostrarCheckList(equipamientos)" class="btn btn-dark pull-right">
                                                    <i class="fa fa-check-square-o"></i> Ver recepción
                                                </button> 
                                                <span v-else-if="equipamientos.recepcion == 1 && equipamientos.status == 0" class="badge badge-danger">Corrigiendo detalles</span>
                                                <span v-else class="badge badge-primary">Equipamiento sin instalarse</span>
                                            </td>
                                            <td>
                                                <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right" 
                                                    @click="abrirModal('observaciones', equipamientos),listarObservacion(1,equipamientos.id)">Ver observaciones
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
                <!-------------------  Fin Div historial equipamientos  --------------------->

                <!-------------------  Div checklist recepcion  --------------------->
                <template v-if="checklist == 1">
                    <div class="card-body" v-if="tipoRecepcion == 1"> 
                        <h5 v-text="tituloRecepcion"></h5>
                            <div class="form-group row border">
                                <div class="col-md-12">
                                    <div class="form-group">
                                  <center> <h5>Supervisión de acabados</h5> </center>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row border">
                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i> Cubierta </a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="cubierta_acab_uniones" type="checkbox" value="1"/> Uniones</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="cubierta_acab_silicon" type="checkbox" value="1"/> Uso silicón</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="cubierta_acab_cortes" type="checkbox" value="1"/> Cortes</a>
                                                </li>
                                            </ul>                                       
                                    </div>
                                </div> 

                                <div class="col-md-6">
                                    <div class="form-group row border">
                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i> Puertas </a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="puerta_acab_alineados" type="checkbox" value="1"/> Alineadas</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="puerta_acab_cantos" type="checkbox" value="1"/> Cantos</a>
                                                </li>
                                            </ul>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                  <center> <h5>Revisión de puertas, alacenas y cajones</h5> </center>
                                    </div>
                                </div> 

                                <div class="col-md-4">
                                    <div class="form-group row border">
                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i> Puertas </a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="puerta_danos" type="checkbox" value="1"/> Daños</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="puerta_tornillos" type="checkbox" value="1"/> Tornillos aju</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="puerta_abatimiento" type="checkbox" value="1"/> Abatimiento</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="puerta_limpieza" type="checkbox" value="1"/> Limpieza</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="puerta_jaladera" type="checkbox" value="1"/> Jaladeras</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="puerta_gomas" type="checkbox" value="1"/> Gomas cierre</a>
                                                </li>
                                            </ul>
                                        
                                    </div>
                                </div> 

                                <div class="col-md-4">
                                    <div class="form-group row border">
                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i> Cajones </a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="cajones_uniones" type="checkbox" value="1"/> Uniones</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="cajones_silicon" type="checkbox" value="1"/> Silicón/Past</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="cajones_limpieza" type="checkbox" value="1"/> Limpieza</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="cajones_jaladeras" type="checkbox" value="1"/> Jaladeras</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="cajones_cantos" type="checkbox" value="1"/> Cantos</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="cajones_rieles" type="checkbox" value="1"/> Rieles</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="cajones_estantes" type="checkbox" value="1"/> Estantes</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="cajones_pzas_comp" type="checkbox" value="1"/> Pzas completas</a>
                                                </li>
                                            </ul>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group row border">
                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i> Alacenas </a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="alacena_entrepano" type="checkbox" value="1"/> Entrepaños</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="alacena_pistones" type="checkbox" value="1"/> Pistones</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="alacena_jaladeras" type="checkbox" value="1"/> Jaladeras</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="alacena_micro" type="checkbox" value="1"/> Hoyo micro</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="alacena_cantos" type="checkbox" value="1"/> Cantos</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="alacena_limpieza" type="checkbox" value="1"/> Limpieza</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="alacena_parches" type="checkbox" value="1"/> Parches tor.</a>
                                                </li>
                                            </ul>
                                        
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                  <center> <h5>Otras revisiones</h5> </center>
                                    </div>
                                </div> 

                                <div class="col-md-6">
                                    <div class="form-group row border">
                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i> Estufa </a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="estufa_instalacion" type="checkbox" value="1"/> Instalación</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="estufa_pzas_extra" type="checkbox" value="1"/> Piezas extra</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="estufa_manuales" type="checkbox" value="1"/> Manuales</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="estufa_danos" type="checkbox" value="1"/> Daños</a>
                                                </li>
                                            </ul>                                       
                                    </div>
                                </div> 

                                <div class="col-md-6">
                                    <div class="form-group row border">
                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i> Tarja </a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="tarja_danos" type="checkbox" value="1"/> Daños</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="tarja_pzas_extra" type="checkbox" value="1"/> Piezas extra</a>
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
                                <div class="col-md-9">
                                    <button type="button" class="btn btn-secondary" @click="cerrarRecepcion()"> Cerrar </button>
                                </div>

                                <div class="col-md-3" v-if="recibido == 0">
                                    <button type="button" class="btn btn-success" @click="terminarRevision(2)"> Aprobar </button>
                                    <button type="button" class="btn btn-danger" @click="terminarRevision(0)"> Rechazar </button>
                                </div>
                                <div class="col-md-3" v-else>
                                    <button type="button" class="btn btn-success" @click="actualizarRevision(2)"> Aprobar </button>
                                    <button type="button" class="btn btn-danger" @click="actualizarRevision(0)"> Rechazar </button>
                                </div>
                            </div>

                 

                    </div>

                    <div class="card-body" v-if="tipoRecepcion == 2"> 
                        <h5 v-text="tituloRecepcion"></h5>
                            <div class="form-group row border">
                                <div class="col-md-12">
                                    <div class="form-group">
                                  <center> <h5>Supervisión de acabados</h5> </center>
                                    </div>
                                </div>
                                <!-- listado para privilegios del menu Administracion -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <center> <h6>Recamara Derecha</h6> </center>
                                    </div>
                                    <div class="form-group row border">
                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i> Puertas </a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="p_ali_der" type="checkbox" value="1"/> Alineados</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="p_limp_der" type="checkbox" value="1"/> Limpieza</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="p_sil_der" type="checkbox" value="1"/> Uso silicón</a>
                                                </li>
                                            </ul>

                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i> Cajones </a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_ali_der" type="checkbox" value="1"/> Alineados</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_cant_der" type="checkbox" value="1"/> Cantos</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_union_der" type="checkbox" value="1"/> Uniones</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_sil_der" type="checkbox" value="1"/> Silicón/Past</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="c_limp_der" type="checkbox" value="1"/> Limpieza</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_torn_der" type="checkbox" value="1"/> Tornillos aju</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="c_parch_der" type="checkbox" value="1"/> Parches tor</a>
                                                </li>
                                            </ul>
                                        
                                    </div>
                                </div> 

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <center> <h6>Recamara Izquierda</h6> </center>
                                    </div>
                                    <div class="form-group row border">
                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i> Puertas </a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="p_ali_izq" type="checkbox" value="1"/> Alineados</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="p_limp_izq" type="checkbox" value="1"/> Limpieza</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="p_sil_izq" type="checkbox" value="1"/> Uso silicón</a>
                                                </li>
                                            </ul>

                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i> Cajones </a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_ali_izq" type="checkbox" value="1"/> Alineados</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_cant_izq" type="checkbox" value="1"/> Cantos</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_union_izq" type="checkbox" value="1"/> Uniones</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_sil_izq" type="checkbox" value="1"/> Silicón/Past</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="c_limp_izq" type="checkbox" value="1"/> Limpieza</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_torn_izq" type="checkbox" value="1"/> Tornillos aju</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="c_parch_izq" type="checkbox" value="1"/> Parches tor</a>
                                                </li>
                                            </ul>
                                        
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <center> <h6>Recamara Principal</h6> </center>
                                    </div>
                                    <div class="form-group row border">
                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i> Puertas </a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="p_ali_princ" type="checkbox" value="1"/> Alineados</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="p_limp_princ" type="checkbox" value="1"/> Limpieza</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="p_sil_princ" type="checkbox" value="1"/> Uso silicón</a>
                                                </li>
                                            </ul>

                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i> Cajones </a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_ali_princ" type="checkbox" value="1"/> Alineados</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_cant_princ" type="checkbox" value="1"/> Cantos</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_union_princ" type="checkbox" value="1"/> Uniones</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_sil_princ" type="checkbox" value="1"/> Silicón/Past</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="c_limp_princ" type="checkbox" value="1"/> Limpieza</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_torn_princ" type="checkbox" value="1"/> Tornillos aju</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="c_parch_princ" type="checkbox" value="1"/> Parches tor</a>
                                                </li>
                                            </ul>
                                        
                                    </div>
                                </div>

                                <div class="col-md-3" v-if="modelo == 'SAN FERNANDO'">
                                    <div class="form-group">
                                        <center> <h6>Recamara planta baja</h6> </center>
                                    </div>
                                    <div class="form-group row border">
                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i> Puertas </a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="p_ali_baja" type="checkbox" value="1"/> Alineados</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="p_limp_baja" type="checkbox" value="1"/> Limpieza</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="p_sil_baja" type="checkbox" value="1"/> Uso silicón</a>
                                                </li>
                                            </ul>

                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i> Cajones </a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_ali_baja" type="checkbox" value="1"/> Alineados</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_cant_baja" type="checkbox" value="1"/> Cantos</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_union_baja" type="checkbox" value="1"/> Uniones</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_sil_baja" type="checkbox" value="1"/> Silicón/Past</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="c_limp_baja" type="checkbox" value="1"/> Limpieza</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_torn_baja" type="checkbox" value="1"/> Tornillos aju</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="c_parch_baja" type="checkbox" value="1"/> Parches tor</a>
                                                </li>
                                            </ul>
                                        
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                  <center> <h5>Supervisión de interiores</h5> </center>
                                    </div>
                                </div> 

                                <!-- listado para privilegios del menu Administracion -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <center> <h6>Recamara Derecha</h6> </center>
                                    </div>
                                    <div class="form-group row border">
                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i> Puertas </a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="p_tira_der" type="checkbox" value="1"/> Tiradores</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="p_func_der" type="checkbox" value="1"/> Funcionamiento</a>
                                                </li>
                                            </ul>

                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i> Cajones </a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_jalad_der" type="checkbox" value="1"/> Jaladeras</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_riel_der" type="checkbox" value="1"/> Rieles</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_estant_der" type="checkbox" value="1"/> Estantes</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_entr_der" type="checkbox" value="1"/> Entrepaños</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="c_tubos_der" type="checkbox" value="1"/> Tubos colga</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_danos_der" type="checkbox" value="1"/> Daños</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="c_correct_der" type="checkbox" value="1"/> Abre correctamente</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="c_pzasc_der" type="checkbox" value="1"/> Pzas completas</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="c_abatim_der" type="checkbox" value="1"/> Abatimiento</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="c_visagras_der" type="checkbox" value="1"/> Visagras</a>
                                                </li>
                                            </ul>
                                        
                                    </div>
                                </div> 

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <center> <h6>Recamara Izquierda</h6> </center>
                                    </div>
                                    <div class="form-group row border">
                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i> Puertas </a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="p_tira_izq" type="checkbox" value="1"/> Tiradores</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="p_func_izq" type="checkbox" value="1"/> Funcionamiento</a>
                                                </li>
                                            </ul>

                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i> Cajones </a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_jalad_izq" type="checkbox" value="1"/> Jaladeras</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_riel_izq" type="checkbox" value="1"/> Rieles</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_estant_izq" type="checkbox" value="1"/> Estantes</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_entr_izq" type="checkbox" value="1"/> Entrepaños</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="c_tubos_izq" type="checkbox" value="1"/> Tubos colga</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_danos_izq" type="checkbox" value="1"/> Daños</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="c_correct_izq" type="checkbox" value="1"/> Abre correctamente</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="c_pzasc_izq" type="checkbox" value="1"/> Pzas completas</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="c_abatim_izq" type="checkbox" value="1"/> Abatimiento</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="c_visagras_izq" type="checkbox" value="1"/> Visagras</a>
                                                </li>
                                            </ul>
                                        
                                    </div>>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <center> <h6>Recamara Principal</h6> </center>
                                    </div>
                                    <div class="form-group row border">
                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i> Puertas </a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="p_tira_princ" type="checkbox" value="1"/> Tiradores</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="p_func_princ" type="checkbox" value="1"/> Funcionamiento</a>
                                                </li>
                                            </ul>

                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i> Cajones </a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_jalad_princ" type="checkbox" value="1"/> Jaladeras</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_riel_princ" type="checkbox" value="1"/> Rieles</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_estant_princ" type="checkbox" value="1"/> Estantes</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_entr_princ" type="checkbox" value="1"/> Entrepaños</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="c_tubos_princ" type="checkbox" value="1"/> Tubos colga</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_danos_princ" type="checkbox" value="1"/> Daños</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="c_correct_princ" type="checkbox" value="1"/> Abre correctamente</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="c_pzasc_princ" type="checkbox" value="1"/> Pzas completas</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="c_abatim_princ" type="checkbox" value="1"/> Abatimiento</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="c_visagras_princ" type="checkbox" value="1"/> Visagras</a>
                                                </li>
                                            </ul>
                                        
                                    </div>
                                </div>

                                <div class="col-md-3" v-if="modelo == 'SAN FERNANDO'">
                                    <div class="form-group">
                                        <center> <h6>Recamara planta baja</h6> </center>
                                    </div>
                                    <div class="form-group row border">
                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i> Puertas </a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="p_tira_baja" type="checkbox" value="1"/> Tiradores</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="p_func_baja" type="checkbox" value="1"/> Funcionamiento</a>
                                                </li>
                                            </ul>

                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i> Cajones </a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_jalad_baja" type="checkbox" value="1"/> Jaladeras</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_riel_baja" type="checkbox" value="1"/> Rieles</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_estant_baja" type="checkbox" value="1"/> Estantes</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_entr_baja" type="checkbox" value="1"/> Entrepaños</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="c_tubos_baja" type="checkbox" value="1"/> Tubos colga</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="c_danos_baja" type="checkbox" value="1"/> Daños</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="c_correct_baja" type="checkbox" value="1"/> Abre correctamente</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="c_pzasc_baja" type="checkbox" value="1"/> Pzas completas</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="c_abatim_baja" type="checkbox" value="1"/> Abatimiento</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="c_visagras_baja" type="checkbox" value="1"/> Visagras</a>
                                                </li>
                                            </ul>
                                        
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                  <center> <h5>Otras revisiones</h5> </center>
                                    </div>
                                </div> 

                                <!-- listado para privilegios del menu Administracion -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <center> <h6>Recamara Derecha</h6> </center>
                                    </div>
                                    <div class="form-group row border">
                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i> Paredes </a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="pared_dan_der" type="checkbox" value="1"/> Daños</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="pared_limp_der" type="checkbox" value="1"/> Limpieza</a>
                                                </li>
                                            </ul>

                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i> Closet's </a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="clo_censup_der" type="checkbox" value="1"/> Cenefa sup</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="clo_ceninf_der" type="checkbox" value="1"/> Cenefas inf</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="clo_madera_der" type="checkbox" value="1"/> Color Mader</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="clo_alin_der" type="checkbox" value="1"/> Alinec jalad</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="clo_pande_der" type="checkbox" value="1"/> Pondeadura</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="clo_soporte_der" type="checkbox" value="1"/> Soport ajust</a>
                                                </li>
                                            </ul>
                                        
                                    </div>
                                </div> 

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <center> <h6>Recamara Izquierda</h6> </center>
                                    </div>
                                    <div class="form-group row border">
                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i> Paredes </a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="pared_dan_izq" type="checkbox" value="1"/> Daños</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="pared_limp_izq" type="checkbox" value="1"/> Limpieza</a>
                                                </li>
                                            </ul>

                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i> Closet's </a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="clo_censup_izq" type="checkbox" value="1"/> Cenefa sup</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="clo_ceninf_izq" type="checkbox" value="1"/> Cenefas inf</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="clo_madera_izq" type="checkbox" value="1"/> Color Mader</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="clo_alin_izq" type="checkbox" value="1"/> Alinec jalad</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="clo_pande_izq" type="checkbox" value="1"/> Pondeadura</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="clo_soporte_izq" type="checkbox" value="1"/> Soport ajust</a>
                                                </li>
                                            </ul>
                                        
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <center> <h6>Recamara Principal</h6> </center>
                                    </div>
                                    <div class="form-group row border">
                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i> Paredes </a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="pared_dan_princ" type="checkbox" value="1"/> Daños</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="pared_limp_princ" type="checkbox" value="1"/> Limpieza</a>
                                                </li>
                                            </ul>

                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i> Closet's </a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="clo_censup_princ" type="checkbox" value="1"/> Cenefa sup</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="clo_ceninf_princ" type="checkbox" value="1"/> Cenefas inf</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="clo_madera_princ" type="checkbox" value="1"/> Color Mader</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="clo_alin_princ" type="checkbox" value="1"/> Alinec jalad</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="clo_pande_princ" type="checkbox" value="1"/> Pondeadura</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="clo_soporte_princ" type="checkbox" value="1"/> Soport ajust</a>
                                                </li>
                                            </ul>
                                        
                                    </div>
                                </div>

                                <div class="col-md-3" v-if="modelo == 'SAN FERNANDO'">
                                    <div class="form-group">
                                        <center> <h6>Recamara planta baja</h6> </center>
                                    </div>
                                    <div class="form-group row border">
                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i> Paredes </a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="pared_dan_baja" type="checkbox" value="1"/> Daños</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="pared_limp_baja" type="checkbox" value="1"/> Limpieza</a>
                                                </li>
                                            </ul>

                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i> Closet's </a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="clo_censup_baja" type="checkbox" value="1"/> Cenefa sup</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="clo_ceninf_baja" type="checkbox" value="1"/> Cenefas inf</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="clo_madera_baja" type="checkbox" value="1"/> Color Mader</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="clo_alin_baja" type="checkbox" value="1"/> Alinec jalad</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"> <input v-model="clo_pande_baja" type="checkbox" value="1"/> Pondeadura</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"> <input v-model="clo_soporte_baja" type="checkbox" value="1"/> Soport ajust</a>
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
                                <div class="col-md-9">
                                    <button type="button" class="btn btn-secondary" @click="cerrarRecepcion()"> Cerrar </button>
                                </div>

                                <div class="col-md-3" v-if="recibido == 0">
                                    <button type="button" class="btn btn-success" @click="terminarRevision(2)"> Aprobar </button>
                                    <button type="button" class="btn btn-danger" @click="terminarRevision(0)"> Rechazar </button>
                                </div>
                                <div class="col-md-3" v-else>
                                    <button type="button" class="btn btn-success" @click="actualizarRevision(2)"> Aprobar </button>
                                    <button type="button" class="btn btn-danger" @click="actualizarRevision(0)"> Rechazar </button>
                                </div>
                            </div>

                 

                    </div>

                    <div class="card-body" v-if="tipoRecepcion == 0"> 
                        <h5 v-text="tituloRecepcion"></h5>
                            <div class="form-group row border">

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
                                <div class="col-md-9">
                                    <button type="button" class="btn btn-secondary" @click="cerrarRecepcion()"> Cerrar </button>
                                </div>

                                <div class="col-md-3" v-if="recibido == 0">
                                    <button type="button" class="btn btn-success" @click="terminarRevision(2)"> Aprobar </button>
                                    <button type="button" class="btn btn-danger" @click="terminarRevision(0)"> Rechazar </button>
                                </div>
                                <div class="col-md-3" v-else>
                                    <button type="button" class="btn btn-success" @click="actualizarRevision(2)"> Aprobar </button>
                                    <button type="button" class="btn btn-danger" @click="actualizarRevision(0)"> Rechazar </button>
                                </div>
                            </div>

                 

                    </div>

                    <!-- Div para mostrar los errores que mande validerFraccionamiento -->
                                <div v-show="errorRevision" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjRevision" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>
                </template>

                <!-------------------  Fin checklist recepcion  --------------------->

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
                                    <label class="col-md-2 form-control-label" for="text-input">Fecha de anticipo</label>
                                    <div class="col-md-3">
                                        <input type="date" v-model="fecha_anticipo" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row" v-if="tipoAccion == 1">
                                    <label class="col-md-2 form-control-label" for="text-input">$ Anticipo</label>
                                    <div class="col-md-4">
                                        <input type="text" pattern="\d*" maxlength="10" v-on:keypress="isNumber($event)" v-model="anticipo" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <h6 v-text="'$'+formatNumber(anticipo)"></h6>
                                    </div>
                                </div>

                                <div class="form-group row" v-if="tipoAccion == 2">
                                    <label class="col-md-2 form-control-label" for="text-input">Fecha de colocacion</label>
                                    <div class="col-md-3">
                                        <input type="date" v-model="fecha_colocacion" class="form-control">
                                    </div>
                                </div>

                                 <div class="form-group row" v-if="tipoAccion == 2">
                                    <label class="col-md-2 form-control-label" for="text-input">Observacion</label>
                                    <div class="col-md-8">
                                        <textarea v-model="observacion" cols="50" rows="4"></textarea>
                                    </div>
                                </div>

                                
                            </div>
                            <!-- Botones del modal -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                                <button type="button" v-if="tipoAccion == 1" class="btn btn-success" @click="actAnticipo()">Guardar</button>
                                <button type="button" v-if="tipoAccion == 2" class="btn btn-success" @click="actColocacion()">Guardar</button>
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

                modal: 0,
                modal2: 0,
                modal3: 0,
                tituloModal: '',

                tituloRecepcion: '',
                tipoRecepcion:0,
                recibido:0,
                modelo:'',

                //variables
                lote_id: 0,
                contrato_id: 0,
                solicitud_id: 0,
                offset : 3,

                checklist : 0,

                // Criterios para historial de equipamientos
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
                errorRevision:0,
                errorMostrarMsjRevision:[],
            /// SUPERVISION ACABADOS CLOSETS    
                //Puertas alineados
                p_ali_der:1, p_ali_izq:1, p_ali_princ:1, p_ali_baja:1,
                //Puertas limpieza
                p_limp_der:1, p_limp_izq:1, p_limp_princ:1, p_limp_baja:1,
                //Puertas silicon
                p_sil_der:1, p_sil_izq:1, p_sil_princ:1, p_sil_baja:1,
                //Cajones alineados
                c_ali_der:1, c_ali_izq:1, c_ali_princ:1, c_ali_baja:1,
                //Cajones cantos
                c_cant_der:1, c_cant_izq:1, c_cant_princ:1, c_cant_baja:1,
                //Cajones uniones
                c_union_der:1, c_union_izq:1, c_union_princ:1, c_union_baja:1,
                //Cajones silicon
                c_sil_der:1, c_sil_izq:1, c_sil_princ:1, c_sil_baja:1,
                //Cajones limpieza
                c_limp_der:1, c_limp_izq:1, c_limp_princ:1, c_limp_baja:1,
                //Cajones tornillos
                c_torn_der:1, c_torn_izq:1, c_torn_princ:1, c_torn_baja:1,
                //Cajones parches
                c_parch_der:1, c_parch_izq:1, c_parch_princ:1, c_parch_baja:1,
            /// SUPERVISION INTERIORES CLOSETS
                p_tira_der:1, p_tira_izq:1, p_tira_princ:1, p_tira_baja:1,
                //Puertas funcionamiento
                p_func_der:1, p_func_izq:1, p_func_princ:1, p_func_baja:1,
                //Cajones jaladeras
                c_jalad_der:1, c_jalad_izq:1, c_jalad_princ:1, c_jalad_baja:1,
                //Cajones rieles
                c_riel_der:1, c_riel_izq:1, c_riel_princ:1, c_riel_baja:1,
                //Cajones estantes
                c_estant_der:1, c_estant_izq:1, c_estant_princ:1, c_estant_baja:1,
                //Cajones entrepaños
                c_entr_der:1, c_entr_izq:1, c_entr_princ:1, c_entr_baja:1,
                //Cajones tubos colga
                c_tubos_der:1, c_tubos_izq:1, c_tubos_princ:1, c_tubos_baja:1,
                //Cajones daños
                c_danos_der:1, c_danos_izq:1, c_danos_princ:1, c_danos_baja:1,
                //Cajones abre correct
                c_correct_der:1, c_correct_izq:1, c_correct_princ:1, c_correct_baja:1,
                //Cajones pzas compl
                c_pzasc_der:1,c_pzasc_izq:1,c_pzasc_princ:1, c_pzasc_baja:1,
                //Cajones abatimiento
                c_abatim_der:1, c_abatim_izq:1, c_abatim_princ:1, c_abatim_baja:1,
                //Cajones visagras
                c_visagras_der:1, c_visagras_izq:1, c_visagras_princ:1, c_visagras_baja:1,

            /// OTRAS REVISIONES
                pared_dan_der:1, pared_dan_izq:1, pared_dan_princ:1, pared_dan_baja:1,
                //Paredes limpieza
                pared_limp_der:1, pared_limp_izq:1, pared_limp_princ:1, pared_limp_baja:1,
                //Closet Cenefa sup
                clo_censup_der:1, clo_censup_izq:1, clo_censup_princ:1, clo_censup_baja:1,
                //Closet Cenefa inf
                clo_ceninf_der:1, clo_ceninf_izq:1, clo_ceninf_princ:1, clo_ceninf_baja:1,
                //Closet color madera
                clo_madera_der:1, clo_madera_izq:1, clo_madera_princ:1, clo_madera_baja:1,
                //Closet Alinec jalad
                clo_alin_der:1, clo_alin_izq:1, clo_alin_princ:1, clo_alin_baja:1,
                //Closet pandeadura
                clo_pande_der:1, clo_pande_izq:1, clo_pande_princ:1, clo_pande_baja:1,
                //Closet soporte
                clo_soporte_der:1, clo_soporte_izq:1, clo_soporte_princ:1, clo_soporte_baja:1,

            /// SUPERVISION ACABADOS COCINA
                // Cubierta
                cubierta_acab_uniones:1,
                cubierta_acab_silicon:1,
                cubierta_acab_cortes:1,
                // Puertas
                puerta_acab_alineados:1,
                puerta_acab_cantos:1,

            /// SUPERVISION REVISION PUERTAS ALACENAS CAJONES
                puerta_danos:1,
                puerta_tornillos:1,
                puerta_abatimiento:1,
                puerta_limpieza:1,
                puerta_jaladera:1,
                puerta_gomas:1,
                //Cajones
                cajones_uniones:1,
                cajones_silicon:1,
                cajones_limpieza:1,
                cajones_jaladeras:1,
                cajones_cantos:1,
                cajones_rieles:1,
                cajones_estantes:1,
                cajones_pzas_comp:1,
                //Alacenas
                alacena_entrepano:1,
                alacena_pistones:1,
                alacena_jaladeras:1,
                alacena_micro:1,
                alacena_cantos:1,
                alacena_limpieza:1,
                alacena_parches:1,
            /// OTRAS REVISIONES COCINA
                estufa_instalacion:1,
                estufa_pzas_extra:1,
                estufa_manuales:1,
                estufa_danos:1,
                //Tarja
                tarja_danos:1,
                tarja_pzas_extra:1,
            

            //////////////////////////

                arrayHistorialEquipamientos : [],
                arrayResultados : [],
                resultado : 1
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

            listarHistorial(page, buscar, b_etapa, b_manzana, b_lote, criterio){
                let me = this;
                var url = '/equipamiento/indexHistorial?page=' + page + '&buscar=' + buscar + '&b_etapa=' + b_etapa + '&b_manzana=' + b_manzana + '&b_lote=' + b_lote +  '&criterio=' + criterio+  '&status=' + me.status;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayHistorialEquipamientos = respuesta.equipamientos.data;
                    me.pagination2 = respuesta.pagination;
                    
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

            getResultados(id){
                let me = this;
                me.arrayResultados = [];
                var url = '/equipamiento/getResultados?id=' + id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayResultados = respuesta.resultados;

                    me.cubierta_acab_uniones = me.arrayResultados[0].cubierta_acab_uniones;
                    me.cubierta_acab_silicon = me.arrayResultados[0].cubierta_acab_silicon;
                    me.cubierta_acab_cortes = me.arrayResultados[0].cubierta_acab_cortes;
                    me.puerta_acab_alineados = me.arrayResultados[0].puerta_acab_alineados;
                    me.puerta_acab_cantos = me.arrayResultados[0].puerta_acab_cantos;

                    me.estufa_instalacion = me.arrayResultados[0].estufa_instalacion;
                    me.estufa_pzas_extra = me.arrayResultados[0].estufa_pzas_extra;
                    me.estufa_manuales = me.arrayResultados[0].estufa_manuales;
                    me.estufa_danos = me.arrayResultados[0].estufa_danos;
                    me.tarja_danos = me.arrayResultados[0].tarja_danos;
                    me.tarja_pzas_extra = me.arrayResultados[0].tarja_pzas_extra;

                    me.puerta_danos = me.arrayResultados[0].puerta_danos;
                    me.puerta_tornillos = me.arrayResultados[0].puerta_tornillos;
                    me.puerta_abatimiento = me.arrayResultados[0].puerta_abatimiento;
                    me.puerta_limpieza = me.arrayResultados[0].puerta_limpieza;
                    me.puerta_jaladera = me.arrayResultados[0].puerta_jaladera;
                    me.puerta_gomas = me.arrayResultados[0].puerta_gomas;
                    me.cajones_uniones = me.arrayResultados[0].cajones_uniones;
                    me.cajones_silicon = me.arrayResultados[0].cajones_silicon;
                    me.cajones_limpieza = me.arrayResultados[0].cajones_limpieza;
                    me.cajones_jaladeras = me.arrayResultados[0].cajones_jaladeras;
                    me.cajones_cantos = me.arrayResultados[0].cajones_cantos;
                    me.cajones_rieles = me.arrayResultados[0].cajones_rieles;
                    me.cajones_estantes = me.arrayResultados[0].cajones_estantes;
                    me.cajones_pzas_comp = me.arrayResultados[0].cajones_pzas_comp;
                    me.alacena_entrepano = me.arrayResultados[0].alacena_entrepano;
                    me.alacena_pistones = me.arrayResultados[0].alacena_pistones;
                    me.alacena_jaladeras = me.arrayResultados[0].alacena_jaladeras;
                    me.alacena_micro = me.arrayResultados[0].alacena_micro;
                    me.alacena_cantos = me.arrayResultados[0].alacena_cantos;
                    me.alacena_limpieza = me.arrayResultados[0].alacena_limpieza;
                    me.alacena_parches = me.arrayResultados[0].alacena_parches;

                    me.p_ali_der = me.arrayResultados[0].p_ali_der;
                    me.p_ali_izq = me.arrayResultados[0].p_ali_izq;
                    me.p_ali_princ = me.arrayResultados[0].p_ali_princ;
                    me.p_ali_baja = me.arrayResultados[0].p_ali_baja;
                    //Puertas limpieza
                    me.p_limp_der = me.arrayResultados[0].p_limp_der;
                    me.p_limp_izq = me.arrayResultados[0].p_limp_izq;
                    me.p_limp_princ = me.arrayResultados[0].p_limp_princ;
                    me.p_limp_baja = me.arrayResultados[0].p_limp_baja;
                    //Puertas silicon
                    me.p_sil_der = me.arrayResultados[0].p_sil_der;
                    me.p_sil_izq = me.arrayResultados[0].p_sil_izq;
                    me.p_sil_princ = me.arrayResultados[0].p_sil_princ;
                    me.p_sil_baja = me.arrayResultados[0].p_sil_baja;
                    //Cajones alineados
                    me.c_ali_der = me.arrayResultados[0].c_ali_der;
                    me.c_ali_izq = me.arrayResultados[0].c_ali_izq;
                    me.c_ali_princ = me.arrayResultados[0].c_ali_princ;
                    me.c_ali_baja = me.arrayResultados[0].c_ali_baja;
                    //Cajones cantos
                    me.c_cant_der = me.arrayResultados[0].c_cant_der;
                    me.c_cant_izq = me.arrayResultados[0].c_cant_izq;
                    me.c_cant_princ = me.arrayResultados[0].c_cant_princ;
                    me.c_cant_baja = me.arrayResultados[0].c_cant_baja;
                    //Cajones uniones
                    me.c_union_der = me.arrayResultados[0].c_union_der;
                    me.c_union_izq = me.arrayResultados[0].c_union_izq;
                    me.c_union_princ = me.arrayResultados[0].c_union_princ;
                    me.c_union_baja = me.arrayResultados[0].c_union_baja;
                    //Cajones silicon
                    me.c_sil_der = me.arrayResultados[0].c_sil_der;
                    me.c_sil_izq = me.arrayResultados[0].c_sil_izq;
                    me.c_sil_princ = me.arrayResultados[0].c_sil_princ;
                    me.c_sil_baja = me.arrayResultados[0].c_sil_baja;
                    //Cajones limpieza
                    me.c_limp_der = me.arrayResultados[0].c_limp_der;
                    me.c_limp_izq = me.arrayResultados[0].c_limp_izq;
                    me.c_limp_princ = me.arrayResultados[0].c_limp_princ;
                    me.c_limp_baja = me.arrayResultados[0].c_limp_baja;
                    //Cajones tornillos
                    me.c_torn_der = me.arrayResultados[0].c_torn_der;
                    me.c_torn_izq = me.arrayResultados[0].c_torn_izq;
                    me.c_torn_princ = me.arrayResultados[0].c_torn_princ;
                    me.c_torn_baja = me.arrayResultados[0].c_torn_baja;
                    //Cajones parches
                    me.c_parch_der = me.arrayResultados[0].c_parch_der;
                    me.c_parch_izq = me.arrayResultados[0].c_parch_izq;
                    me.c_parch_princ = me.arrayResultados[0].c_parch_princ;
                    me.c_parch_baja = me.arrayResultados[0].c_parch_baja;

                    //Puertas tiradores
                    me.p_tira_der = me.arrayResultados[0].p_tira_der;
                    me.p_tira_izq = me.arrayResultados[0].p_tira_izq;
                    me.p_tira_princ = me.arrayResultados[0].p_tira_princ;
                    me.p_tira_baja = me.arrayResultados[0].p_tira_baja;
                        //Puertas funcionamiento
                    me.p_func_der = me.arrayResultados[0].p_func_der;
                    me.p_func_izq = me.arrayResultados[0].p_func_izq;
                    me.p_func_princ = me.arrayResultados[0].p_func_princ;
                    me.p_func_baja = me.arrayResultados[0].p_func_baja;
                        //Cajones jaladeras
                    me.c_jalad_der = me.arrayResultados[0].c_jalad_der;
                    me.c_jalad_izq = me.arrayResultados[0].c_jalad_izq;
                    me.c_jalad_princ = me.arrayResultados[0].c_jalad_princ;
                    me.c_jalad_baja = me.arrayResultados[0].c_jalad_baja;
                        //Cajones rieles
                    me.c_riel_der = me.arrayResultados[0].c_riel_der;
                    me.c_riel_izq = me.arrayResultados[0].c_riel_izq;
                    me.c_riel_princ = me.arrayResultados[0].c_riel_princ;
                    me.c_riel_baja = me.arrayResultados[0].c_riel_baja;
                        //Cajones estantes
                    me.c_estant_der = me.arrayResultados[0].c_estant_der;
                    me.c_estant_izq = me.arrayResultados[0].c_estant_izq;
                    me.c_estant_princ = me.arrayResultados[0].c_estant_princ;
                    me.c_estant_baja = me.arrayResultados[0].c_estant_baja;
                        //Cajones entrepaños
                    me.c_entr_der = me.arrayResultados[0].c_entr_der;
                    me.c_entr_izq = me.arrayResultados[0].c_entr_izq;
                    me.c_entr_princ = me.arrayResultados[0].c_entr_princ;
                    me.c_entr_baja = me.arrayResultados[0].c_entr_baja;
                        //Cajones tubos colga
                    me.c_tubos_der = me.arrayResultados[0].c_tubos_der;
                    me.c_tubos_izq = me.arrayResultados[0].c_tubos_izq;
                    me.c_tubos_princ = me.arrayResultados[0].c_tubos_princ;
                    me.c_tubos_baja = me.arrayResultados[0].c_tubos_baja;
                        //Cajones daños
                    me.c_danos_der = me.arrayResultados[0].c_danos_der;
                    me.c_danos_izq = me.arrayResultados[0].c_danos_izq;
                    me.c_danos_princ = me.arrayResultados[0].c_danos_princ;
                    me.c_danos_baja = me.arrayResultados[0].c_danos_baja;
                        //Cajones abre correct
                    me.c_correct_der = me.arrayResultados[0].c_correct_der;
                    me.c_correct_izq = me.arrayResultados[0].c_correct_izq;
                    me.c_correct_princ = me.arrayResultados[0].c_correct_princ;
                    me.c_correct_baja = me.arrayResultados[0].c_correct_baja;
                        //Cajones pzas compl
                    me.c_pzasc_der = me.arrayResultados[0].c_pzasc_der;
                    me.c_pzasc_izq = me.arrayResultados[0].c_pzasc_izq;
                    me.c_pzasc_princ = me.arrayResultados[0].c_pzasc_princ;
                    me.c_pzasc_baja = me.arrayResultados[0].c_pzasc_baja;
                        //Cajones abatimiento
                    me.c_abatim_der = me.arrayResultados[0].c_abatim_der;
                    me.c_abatim_izq = me.arrayResultados[0].c_abatim_izq;
                    me.c_abatim_princ = me.arrayResultados[0].c_abatim_princ;
                    me.c_abatim_baja = me.arrayResultados[0].c_abatim_baja;
                        //Cajones visagras
                    me.c_visagras_der = me.arrayResultados[0].c_visagras_der;
                    me.c_visagras_izq = me.arrayResultados[0].c_visagras_izq;
                    me.c_visagras_princ = me.arrayResultados[0].c_visagras_princ;
                    me.c_visagras_baja = me.arrayResultados[0].c_visagras_baja;

                    me.pared_dan_der = me.arrayResultados[0].pared_dan_der;
                    me.pared_dan_izq = me.arrayResultados[0].pared_dan_izq;
                    me.pared_dan_princ = me.arrayResultados[0].pared_dan_princ;
                    me.pared_dan_baja = me.arrayResultados[0].pared_dan_baja;
                    //Paredes limpieza
                    me.pared_limp_der = me.arrayResultados[0].pared_limp_der;
                    me.pared_limp_izq = me.arrayResultados[0].pared_limp_izq;
                    me.pared_limp_princ = me.arrayResultados[0].pared_limp_princ;
                    me.pared_limp_baja = me.arrayResultados[0].pared_limp_baja;
                    //Closet Cenefa sup
                    me.clo_censup_der = me.arrayResultados[0].clo_censup_der;
                    me.clo_censup_izq = me.arrayResultados[0].clo_censup_izq;
                    me.clo_censup_princ = me.arrayResultados[0].clo_censup_princ;
                    me.clo_censup_baja = me.arrayResultados[0].clo_censup_baja;
                    //Closet Cenefa inf
                    me.clo_ceninf_der = me.arrayResultados[0].clo_ceninf_der;
                    me.clo_ceninf_izq = me.arrayResultados[0].clo_ceninf_izq;
                    me.clo_ceninf_princ = me.arrayResultados[0].clo_ceninf_princ;
                    me.clo_ceninf_baja = me.arrayResultados[0].clo_ceninf_baja;
                    //Closet color madera
                    me.clo_madera_der = me.arrayResultados[0].clo_madera_der;
                    me.clo_madera_izq = me.arrayResultados[0].clo_madera_izq;
                    me.clo_madera_princ = me.arrayResultados[0].clo_madera_princ;
                    me.clo_madera_baja = me.arrayResultados[0].clo_madera_baja;
                    //Closet Alinec jalad
                    me.clo_alin_der = me.arrayResultados[0].clo_alin_der;
                    me.clo_alin_izq = me.arrayResultados[0].clo_alin_izq;
                    me.clo_alin_princ = me.arrayResultados[0].clo_alin_princ;
                    me.clo_alin_baja = me.arrayResultados[0].clo_alin_baja;
                    //Closet pandeadura
                    me.clo_pande_der = me.arrayResultados[0].clo_pande_der;
                    me.clo_pande_izq = me.arrayResultados[0].clo_pande_izq;
                    me.clo_pande_princ = me.arrayResultados[0].clo_pande_princ;
                    me.clo_pande_baja = me.arrayResultados[0].clo_pande_baja;
                    //Closet soporte
                    me.clo_soporte_der = me.arrayResultados[0].clo_soporte_der;
                    me.clo_soporte_izq = me.arrayResultados[0].clo_soporte_izq;
                    me.clo_soporte_princ = me.arrayResultados[0].clo_soporte_princ;
                    me.clo_soporte_baja = me.arrayResultados[0].clo_soporte_baja;
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
                me.listarHistorial(page,buscar,b_etapa,b_manzana,b_lote,criterio);
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
                axios.post('/equipamiento/registrarObservacion',{
                    'comentario' : this.observacion,
                    'solic_id':this.solicitud_id,
                    
                }).then(function (response){
                    me.listarObservacion(1,me.solicitud_id);
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

            terminarRevision(resultado){
                if(this.validarRevision()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.post('/equipamiento/storeRecepcion',{
                    'comentario' : this.observacion,
                    'id': this.solicitud_id,
                    'tipoRecepcion' : this.tipoRecepcion,
                    'resultado' : resultado,
                    'cubierta_acab_uniones': this.cubierta_acab_uniones,
                    'cubierta_acab_silicon': this.cubierta_acab_silicon,
                    'cubierta_acab_cortes': this.cubierta_acab_cortes,
                    'puerta_acab_alineados': this.puerta_acab_alineados,
                    'puerta_acab_cantos': this.puerta_acab_cantos,

                    'estufa_instalacion': this.estufa_instalacion,
                    'estufa_pzas_extra': this.estufa_pzas_extra,
                    'estufa_manuales': this.estufa_manuales,
                    'estufa_danos': this.estufa_danos,
                    'tarja_danos': this.tarja_danos,
                    'tarja_pzas_extra': this.tarja_pzas_extra,

                    'puerta_danos': this.puerta_danos,
                    'puerta_tornillos': this.puerta_tornillos,
                    'puerta_abatimiento': this.puerta_abatimiento,
                    'puerta_limpieza': this.puerta_limpieza,
                    'puerta_jaladera': this.puerta_jaladera,
                    'puerta_gomas': this.puerta_gomas,
                    'cajones_uniones': this.cajones_uniones,
                    'cajones_silicon': this.cajones_silicon,
                    'cajones_limpieza': this.cajones_limpieza,
                    'cajones_jaladeras': this.cajones_jaladeras,
                    'cajones_cantos': this.cajones_cantos,
                    'cajones_rieles': this.cajones_rieles,
                    'cajones_estantes': this.cajones_estantes,
                    'cajones_pzas_comp': this.cajones_pzas_comp,
                    'alacena_entrepano': this.alacena_entrepano,
                    'alacena_pistones': this.alacena_pistones,
                    'alacena_jaladeras': this.alacena_jaladeras,
                    'alacena_micro': this.alacena_micro,
                    'alacena_cantos': this.alacena_cantos,
                    'alacena_limpieza': this.alacena_limpieza,
                    'alacena_parches': this.alacena_parches,

                    'p_ali_der': this.p_ali_der,
                    'p_ali_izq': this.p_ali_izq,
                    'p_ali_princ': this.p_ali_princ,
                    'p_ali_baja': this.p_ali_baja,
                    //Puertas limpieza
                    'p_limp_der': this.p_limp_der,
                    'p_limp_izq': this.p_limp_izq,
                    'p_limp_princ': this.p_limp_princ,
                    'p_limp_baja': this.p_limp_baja,
                    //Puertas silicon
                    'p_sil_der': this.p_sil_der,
                    'p_sil_izq': this.p_sil_izq,
                    'p_sil_princ': this.p_sil_princ,
                    'p_sil_baja': this.p_sil_baja,
                    //Cajones alineados
                    'c_ali_der': this.c_ali_der,
                    'c_ali_izq': this.c_ali_izq,
                    'c_ali_princ': this.c_ali_princ,
                    'c_ali_baja': this.c_ali_baja,
                    //Cajones cantos
                    'c_cant_der': this.c_cant_der,
                    'c_cant_izq': this.c_cant_izq,
                    'c_cant_princ': this.c_cant_princ,
                    'c_cant_baja': this.c_cant_baja,
                    //Cajones uniones
                    'c_union_der': this.c_union_der,
                    'c_union_izq': this.c_union_izq,
                    'c_union_princ': this.c_union_princ,
                    'c_union_baja': this.c_union_baja,
                    //Cajones silicon
                    'c_sil_der': this.c_sil_der,
                    'c_sil_izq': this.c_sil_izq,
                    'c_sil_princ': this.c_sil_princ,
                    'c_sil_baja': this.c_sil_baja,
                    //Cajones limpieza
                    'c_limp_der': this.c_limp_der,
                    'c_limp_izq': this.c_limp_izq,
                    'c_limp_princ': this.c_limp_princ,
                    'c_limp_baja': this.c_limp_baja,
                    //Cajones tornillos
                    'c_torn_der': this.c_torn_der,
                    'c_torn_izq': this.c_torn_izq,
                    'c_torn_princ': this.c_torn_princ,
                    'c_torn_baja': this.c_torn_baja,
                    //Cajones parches
                    'c_parch_der': this.c_parch_der,
                    'c_parch_izq': this.c_parch_izq,
                    'c_parch_princ': this.c_parch_princ,
                    'c_parch_baja': this.c_parch_baja,

                    //Puertas tiradores
                    'p_tira_der': this.p_tira_der,
                    'p_tira_izq': this.p_tira_izq,
                    'p_tira_princ': this.p_tira_princ,
                    'p_tira_baja': this.p_tira_baja,
                        //Puertas funcionamiento
                    'p_func_der': this.p_func_der,
                    'p_func_izq': this.p_func_izq,
                    'p_func_princ': this.p_func_princ,
                    'p_func_baja': this.p_func_baja,
                        //Cajones jaladeras
                    'c_jalad_der': this.c_jalad_der,
                    'c_jalad_izq': this.c_jalad_izq,
                    'c_jalad_princ': this.c_jalad_princ,
                    'c_jalad_baja': this.c_jalad_baja,
                        //Cajones rieles
                    'c_riel_der': this.c_riel_der,
                    'c_riel_izq': this.c_riel_izq,
                    'c_riel_princ': this.c_riel_princ,
                    'c_riel_baja': this.c_riel_baja,
                        //Cajones estantes
                    'c_estant_der': this.c_estant_der,
                    'c_estant_izq': this.c_estant_izq,
                    'c_estant_princ': this.c_estant_princ,
                    'c_estant_baja': this.c_estant_baja,
                        //Cajones entrepaños
                    'c_entr_der': this.c_entr_der,
                    'c_entr_izq': this.c_entr_izq,
                    'c_entr_princ': this.c_entr_princ,
                    'c_entr_baja': this.c_entr_baja,
                        //Cajones tubos colga
                    'c_tubos_der': this.c_tubos_der,
                    'c_tubos_izq': this.c_tubos_izq,
                    'c_tubos_princ': this.c_tubos_princ,
                    'c_tubos_baja': this.c_tubos_baja,
                        //Cajones daños
                    'c_danos_der': this.c_danos_der,
                    'c_danos_izq': this.c_danos_izq,
                    'c_danos_princ': this.c_danos_princ,
                    'c_danos_baja': this.c_danos_baja,
                        //Cajones abre correct
                    'c_correct_der': this.c_correct_der,
                    'c_correct_izq': this.c_correct_izq,
                    'c_correct_princ': this.c_correct_princ,
                    'c_correct_baja': this.c_correct_baja,
                        //Cajones pzas compl
                    'c_pzasc_der': this.c_pzasc_der,
                    'c_pzasc_izq': this.c_pzasc_izq,
                    'c_pzasc_princ': this.c_pzasc_princ,
                    'c_pzasc_baja': this.c_pzasc_baja,
                        //Cajones abatimiento
                    'c_abatim_der': this.c_abatim_der,
                    'c_abatim_izq': this.c_abatim_izq,
                    'c_abatim_princ': this.c_abatim_princ,
                    'c_abatim_baja': this.c_abatim_baja,
                        //Cajones visagras
                    'c_visagras_der': this.c_visagras_der,
                    'c_visagras_izq': this.c_visagras_izq,
                    'c_visagras_princ': this.c_visagras_princ,
                    'c_visagras_baja': this.c_visagras_baja,

                    'pared_dan_der': this.pared_dan_der,
                    'pared_dan_izq': this.pared_dan_izq,
                    'pared_dan_princ': this.pared_dan_princ,
                    'pared_dan_baja': this.pared_dan_baja,
                    //Paredes limpieza
                    'pared_limp_der': this.pared_limp_der,
                    'pared_limp_izq': this.pared_limp_izq,
                    'pared_limp_princ': this.pared_limp_princ,
                    'pared_limp_baja': this.pared_limp_baja,
                    //Closet Cenefa sup
                    'clo_censup_der': this.clo_censup_der,
                    'clo_censup_izq': this.clo_censup_izq,
                    'clo_censup_princ': this.clo_censup_princ,
                    'clo_censup_baja': this.clo_censup_baja,
                    //Closet Cenefa inf
                    'clo_ceninf_der': this.clo_ceninf_der,
                    'clo_ceninf_izq': this.clo_ceninf_izq,
                    'clo_ceninf_princ': this.clo_ceninf_princ,
                    'clo_ceninf_baja': this.clo_ceninf_baja,
                    //Closet color madera
                    'clo_madera_der': this.clo_madera_der,
                    'clo_madera_izq': this.clo_madera_izq,
                    'clo_madera_princ': this.clo_madera_princ,
                    'clo_madera_baja': this.clo_madera_baja,
                    //Closet Alinec jalad
                    'clo_alin_der': this.clo_alin_der,
                    'clo_alin_izq': this.clo_alin_izq,
                    'clo_alin_princ': this.clo_alin_princ,
                    'clo_alin_baja': this.clo_alin_baja,
                    //Closet pandeadura
                    'clo_pande_der': this.clo_pande_der,
                    'clo_pande_izq': this.clo_pande_izq,
                    'clo_pande_princ': this.clo_pande_princ,
                    'clo_pande_baja': this.clo_pande_baja,
                    //Closet soporte
                    'clo_soporte_der': this.clo_soporte_der,
                    'clo_soporte_izq': this.clo_soporte_izq,
                    'clo_soporte_princ': this.clo_soporte_princ,
                    'clo_soporte_baja': this.clo_soporte_baja,
                    
                }).then(function (response){
                    me.cerrarRecepcion();
                    me.listarHistorial(me.pagination2.current_page,me.buscar2,me.b_etapa2,me.b_manzana2,me.b_lote2,me.criterio2)
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Recepcion guardada correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            actualizarRevision(resultado){
                 if(this.validarRevision()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/equipamiento/updateRecepcion',{
                    'comentario' : this.observacion,
                    'id': this.solicitud_id,
                    'tipoRecepcion' : this.tipoRecepcion,
                    'resultado' : resultado,
                    'cubierta_acab_uniones': this.cubierta_acab_uniones,
                    'cubierta_acab_silicon': this.cubierta_acab_silicon,
                    'cubierta_acab_cortes': this.cubierta_acab_cortes,
                    'puerta_acab_alineados': this.puerta_acab_alineados,
                    'puerta_acab_cantos': this.puerta_acab_cantos,

                    'estufa_instalacion': this.estufa_instalacion,
                    'estufa_pzas_extra': this.estufa_pzas_extra,
                    'estufa_manuales': this.estufa_manuales,
                    'estufa_danos': this.estufa_danos,
                    'tarja_danos': this.tarja_danos,
                    'tarja_pzas_extra': this.tarja_pzas_extra,

                    'puerta_danos': this.puerta_danos,
                    'puerta_tornillos': this.puerta_tornillos,
                    'puerta_abatimiento': this.puerta_abatimiento,
                    'puerta_limpieza': this.puerta_limpieza,
                    'puerta_jaladera': this.puerta_jaladera,
                    'puerta_gomas': this.puerta_gomas,
                    'cajones_uniones': this.cajones_uniones,
                    'cajones_silicon': this.cajones_silicon,
                    'cajones_limpieza': this.cajones_limpieza,
                    'cajones_jaladeras': this.cajones_jaladeras,
                    'cajones_cantos': this.cajones_cantos,
                    'cajones_rieles': this.cajones_rieles,
                    'cajones_estantes': this.cajones_estantes,
                    'cajones_pzas_comp': this.cajones_pzas_comp,
                    'alacena_entrepano': this.alacena_entrepano,
                    'alacena_pistones': this.alacena_pistones,
                    'alacena_jaladeras': this.alacena_jaladeras,
                    'alacena_micro': this.alacena_micro,
                    'alacena_cantos': this.alacena_cantos,
                    'alacena_limpieza': this.alacena_limpieza,
                    'alacena_parches': this.alacena_parches,

                    'p_ali_der': this.p_ali_der,
                    'p_ali_izq': this.p_ali_izq,
                    'p_ali_princ': this.p_ali_princ,
                    'p_ali_baja': this.p_ali_baja,
                    //Puertas limpieza
                    'p_limp_der': this.p_limp_der,
                    'p_limp_izq': this.p_limp_izq,
                    'p_limp_princ': this.p_limp_princ,
                    'p_limp_baja': this.p_limp_baja,
                    //Puertas silicon
                    'p_sil_der': this.p_sil_der,
                    'p_sil_izq': this.p_sil_izq,
                    'p_sil_princ': this.p_sil_princ,
                    'p_sil_baja': this.p_sil_baja,
                    //Cajones alineados
                    'c_ali_der': this.c_ali_der,
                    'c_ali_izq': this.c_ali_izq,
                    'c_ali_princ': this.c_ali_princ,
                    'c_ali_baja': this.c_ali_baja,
                    //Cajones cantos
                    'c_cant_der': this.c_cant_der,
                    'c_cant_izq': this.c_cant_izq,
                    'c_cant_princ': this.c_cant_princ,
                    'c_cant_baja': this.c_cant_baja,
                    //Cajones uniones
                    'c_union_der': this.c_union_der,
                    'c_union_izq': this.c_union_izq,
                    'c_union_princ': this.c_union_princ,
                    'c_union_baja': this.c_union_baja,
                    //Cajones silicon
                    'c_sil_der': this.c_sil_der,
                    'c_sil_izq': this.c_sil_izq,
                    'c_sil_princ': this.c_sil_princ,
                    'c_sil_baja': this.c_sil_baja,
                    //Cajones limpieza
                    'c_limp_der': this.c_limp_der,
                    'c_limp_izq': this.c_limp_izq,
                    'c_limp_princ': this.c_limp_princ,
                    'c_limp_baja': this.c_limp_baja,
                    //Cajones tornillos
                    'c_torn_der': this.c_torn_der,
                    'c_torn_izq': this.c_torn_izq,
                    'c_torn_princ': this.c_torn_princ,
                    'c_torn_baja': this.c_torn_baja,
                    //Cajones parches
                    'c_parch_der': this.c_parch_der,
                    'c_parch_izq': this.c_parch_izq,
                    'c_parch_princ': this.c_parch_princ,
                    'c_parch_baja': this.c_parch_baja,

                    //Puertas tiradores
                    'p_tira_der': this.p_tira_der,
                    'p_tira_izq': this.p_tira_izq,
                    'p_tira_princ': this.p_tira_princ,
                    'p_tira_baja': this.p_tira_baja,
                        //Puertas funcionamiento
                    'p_func_der': this.p_func_der,
                    'p_func_izq': this.p_func_izq,
                    'p_func_princ': this.p_func_princ,
                    'p_func_baja': this.p_func_baja,
                        //Cajones jaladeras
                    'c_jalad_der': this.c_jalad_der,
                    'c_jalad_izq': this.c_jalad_izq,
                    'c_jalad_princ': this.c_jalad_princ,
                    'c_jalad_baja': this.c_jalad_baja,
                        //Cajones rieles
                    'c_riel_der': this.c_riel_der,
                    'c_riel_izq': this.c_riel_izq,
                    'c_riel_princ': this.c_riel_princ,
                    'c_riel_baja': this.c_riel_baja,
                        //Cajones estantes
                    'c_estant_der': this.c_estant_der,
                    'c_estant_izq': this.c_estant_izq,
                    'c_estant_princ': this.c_estant_princ,
                    'c_estant_baja': this.c_estant_baja,
                        //Cajones entrepaños
                    'c_entr_der': this.c_entr_der,
                    'c_entr_izq': this.c_entr_izq,
                    'c_entr_princ': this.c_entr_princ,
                    'c_entr_baja': this.c_entr_baja,
                        //Cajones tubos colga
                    'c_tubos_der': this.c_tubos_der,
                    'c_tubos_izq': this.c_tubos_izq,
                    'c_tubos_princ': this.c_tubos_princ,
                    'c_tubos_baja': this.c_tubos_baja,
                        //Cajones daños
                    'c_danos_der': this.c_danos_der,
                    'c_danos_izq': this.c_danos_izq,
                    'c_danos_princ': this.c_danos_princ,
                    'c_danos_baja': this.c_danos_baja,
                        //Cajones abre correct
                    'c_correct_der': this.c_correct_der,
                    'c_correct_izq': this.c_correct_izq,
                    'c_correct_princ': this.c_correct_princ,
                    'c_correct_baja': this.c_correct_baja,
                        //Cajones pzas compl
                    'c_pzasc_der': this.c_pzasc_der,
                    'c_pzasc_izq': this.c_pzasc_izq,
                    'c_pzasc_princ': this.c_pzasc_princ,
                    'c_pzasc_baja': this.c_pzasc_baja,
                        //Cajones abatimiento
                    'c_abatim_der': this.c_abatim_der,
                    'c_abatim_izq': this.c_abatim_izq,
                    'c_abatim_princ': this.c_abatim_princ,
                    'c_abatim_baja': this.c_abatim_baja,
                        //Cajones visagras
                    'c_visagras_der': this.c_visagras_der,
                    'c_visagras_izq': this.c_visagras_izq,
                    'c_visagras_princ': this.c_visagras_princ,
                    'c_visagras_baja': this.c_visagras_baja,

                    'pared_dan_der': this.pared_dan_der,
                    'pared_dan_izq': this.pared_dan_izq,
                    'pared_dan_princ': this.pared_dan_princ,
                    'pared_dan_baja': this.pared_dan_baja,
                    //Paredes limpieza
                    'pared_limp_der': this.pared_limp_der,
                    'pared_limp_izq': this.pared_limp_izq,
                    'pared_limp_princ': this.pared_limp_princ,
                    'pared_limp_baja': this.pared_limp_baja,
                    //Closet Cenefa sup
                    'clo_censup_der': this.clo_censup_der,
                    'clo_censup_izq': this.clo_censup_izq,
                    'clo_censup_princ': this.clo_censup_princ,
                    'clo_censup_baja': this.clo_censup_baja,
                    //Closet Cenefa inf
                    'clo_ceninf_der': this.clo_ceninf_der,
                    'clo_ceninf_izq': this.clo_ceninf_izq,
                    'clo_ceninf_princ': this.clo_ceninf_princ,
                    'clo_ceninf_baja': this.clo_ceninf_baja,
                    //Closet color madera
                    'clo_madera_der': this.clo_madera_der,
                    'clo_madera_izq': this.clo_madera_izq,
                    'clo_madera_princ': this.clo_madera_princ,
                    'clo_madera_baja': this.clo_madera_baja,
                    //Closet Alinec jalad
                    'clo_alin_der': this.clo_alin_der,
                    'clo_alin_izq': this.clo_alin_izq,
                    'clo_alin_princ': this.clo_alin_princ,
                    'clo_alin_baja': this.clo_alin_baja,
                    //Closet pandeadura
                    'clo_pande_der': this.clo_pande_der,
                    'clo_pande_izq': this.clo_pande_izq,
                    'clo_pande_princ': this.clo_pande_princ,
                    'clo_pande_baja': this.clo_pande_baja,
                    //Closet soporte
                    'clo_soporte_der': this.clo_soporte_der,
                    'clo_soporte_izq': this.clo_soporte_izq,
                    'clo_soporte_princ': this.clo_soporte_princ,
                    'clo_soporte_baja': this.clo_soporte_baja,
                    
                }).then(function (response){
                    me.cerrarRecepcion();
                    me.listarHistorial(me.pagination2.current_page,me.buscar2,me.b_etapa2,me.b_manzana2,me.b_lote2,me.criterio2)
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Recepcion guardada correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            mostrarCheckList(data = []){
                this.checklist = 1;
                
                this.tipoRecepcion = data['tipoRecepcion'];
                if(this.tipoRecepcion == 1)
                    this.tituloRecepcion = 'Recepción de Cocina';
                else if(this.tipoRecepcion == 2){
                    this.tituloRecepcion = 'Recepción de Closets y/o vestidor';
                }
                else{
                    this.tituloRecepcion = 'Recepción de Equipamiento';
                }
                this.modelo = data['modelo'];
                this.observacion = '';
                this.solicitud_id = data['id'];
                this.recibido = data['recepcion'];
                if(this.recibido == 1)  
                    this.getResultados(this.solicitud_id);
            },

            cerrarRecepcion(){
                this.checklist = 0;
                this.tipoRecepcion = 0;
                this.tituloRecepcion = '';
                this.modelo = '';

                this.errorRevision = 0;
                this.errorMostrarMsjRevision = [];

                this.cubierta_acab_uniones = 0;
                this.cubierta_acab_silicon = 0;
                this.cubierta_acab_cortes = 0;
                this.puerta_acab_alineados = 0;
                this.puerta_acab_cantos = 0;

                this.estufa_instalacion = 0;
                this.estufa_pzas_extra = 0;
                this.estufa_manuales = 0;
                this.estufa_danos = 0;
                this.tarja_danos = 0;
                this.tarja_pzas_extra = 0;

                this.puerta_danos = 0;
                this.puerta_tornillos = 0;
                this.puerta_abatimiento = 0;
                this.puerta_limpieza = 0;
                this.puerta_jaladera = 0;
                this.puerta_gomas = 0;
                this.cajones_uniones = 0;
                this.cajones_silicon = 0;
                this.cajones_limpieza = 0;
                this.cajones_jaladeras = 0;
                this.cajones_cantos = 0;
                this.cajones_rieles = 0;
                this.cajones_estantes = 0;
                this.cajones_pzas_comp = 0;
                this.alacena_entrepano = 0;
                this.alacena_pistones = 0;
                this.alacena_jaladeras = 0;
                this.alacena_micro = 0;
                this.alacena_cantos = 0;
                this.alacena_limpieza = 0;
                this.alacena_parches = 0;

                this.p_ali_der = 0;
                this.p_ali_izq = 0;
                this.p_ali_princ = 0;
                this.p_ali_baja = 0;
                //Puertas limpieza
                this.p_limp_der = 0;
                this.p_limp_izq = 0;
                this.p_limp_princ = 0;
                this.p_limp_baja = 0;
                //Puertas silicon
                this.p_sil_der = 0;
                this.p_sil_izq = 0;
                this.p_sil_princ = 0;
                this.p_sil_baja = 0;
                //Cajones alineados
                this.c_ali_der = 0;
                this.c_ali_izq = 0;
                this.c_ali_princ = 0;
                this.c_ali_baja = 0;
                //Cajones cantos
                this.c_cant_der = 0;
                this.c_cant_izq = 0;
                this.c_cant_princ = 0;
                this.c_cant_baja = 0;
                //Cajones uniones
                this.c_union_der = 0;
                this.c_union_izq = 0;
                this.c_union_princ = 0;
                this.c_union_baja = 0;
                //Cajones silicon
                this.c_sil_der = 0;
                this.c_sil_izq = 0;
                this.c_sil_princ = 0;
                this.c_sil_baja = 0;
                //Cajones limpieza
                this.c_limp_der = 0;
                this.c_limp_izq = 0;
                this.c_limp_princ = 0;
                this.c_limp_baja = 0;
                //Cajones tornillos
                this.c_torn_der = 0;
                this.c_torn_izq = 0;
                this.c_torn_princ = 0;
                this.c_torn_baja = 0;
                //Cajones parches
                this.c_parch_der = 0;
                this.c_parch_izq = 0;
                this.c_parch_princ = 0;
                this.c_parch_baja = 0;

                //Puertas tiradores
                this.p_tira_der = 0;
                this.p_tira_izq = 0;
                this.p_tira_princ = 0;
                this.p_tira_baja = 0;
                    //Puertas funcionamiento
                this.p_func_der = 0;
                this.p_func_izq = 0;
                this.p_func_princ = 0;
                this.p_func_baja = 0;
                    //Cajones jaladeras
                this.c_jalad_der = 0;
                this.c_jalad_izq = 0;
                this.c_jalad_princ = 0;
                this.c_jalad_baja = 0;
                    //Cajones rieles
                this.c_riel_der = 0;
                this.c_riel_izq = 0;
                this.c_riel_princ = 0;
                this.c_riel_baja = 0;
                    //Cajones estantes
                this.c_estant_der = 0;
                this.c_estant_izq = 0;
                this.c_estant_princ = 0;
                this.c_estant_baja = 0;
                    //Cajones entrepaños
                this.c_entr_der = 0;
                this.c_entr_izq = 0;
                this.c_entr_princ = 0;
                this.c_entr_baja = 0;
                    //Cajones tubos colga
                this.c_tubos_der = 0;
                this.c_tubos_izq = 0;
                this.c_tubos_princ = 0;
                this.c_tubos_baja = 0;
                    //Cajones daños
                this.c_danos_der = 0;
                this.c_danos_izq = 0;
                this.c_danos_princ = 0;
                this.c_danos_baja = 0;
                    //Cajones abre correct
                this.c_correct_der = 0;
                this.c_correct_izq = 0;
                this.c_correct_princ = 0;
                this.c_correct_baja = 0;
                    //Cajones pzas compl
                this.c_pzasc_der = 0;
                this.c_pzasc_izq = 0;
                this.c_pzasc_princ = 0;
                this.c_pzasc_baja = 0;
                    //Cajones abatimiento
                this.c_abatim_der = 0;
                this.c_abatim_izq = 0;
                this.c_abatim_princ = 0;
                this.c_abatim_baja = 0;
                    //Cajones visagras
                this.c_visagras_der = 0;
                this.c_visagras_izq = 0;
                this.c_visagras_princ = 0;
                this.c_visagras_baja = 0;

                this.pared_dan_der = 0;
                this.pared_dan_izq = 0;
                this.pared_dan_princ = 0;
                this.pared_dan_baja = 0;
                //Paredes limpieza
                this.pared_limp_der = 0;
                this.pared_limp_izq = 0;
                this.pared_limp_princ = 0;
                this.pared_limp_baja = 0;
                //Closet Cenefa sup
                this.clo_censup_der = 0;
                this.clo_censup_izq = 0;
                this.clo_censup_princ = 0;
                this.clo_censup_baja = 0;
                //Closet Cenefa inf
                this.clo_ceninf_der = 0;
                this.clo_ceninf_izq = 0;
                this.clo_ceninf_princ = 0;
                this.clo_ceninf_baja = 0;
                //Closet color madera
                this.clo_madera_der = 0;
                this.clo_madera_izq = 0;
                this.clo_madera_princ = 0;
                this.clo_madera_baja = 0;
                //Closet Alinec jalad
                this.clo_alin_der = 0;
                this.clo_alin_izq = 0;
                this.clo_alin_princ = 0;
                this.clo_alin_baja = 0;
                //Closet pandeadura
                this.clo_pande_der = 0;
                this.clo_pande_izq = 0;
                this.clo_pande_princ = 0;
                this.clo_pande_baja = 0;
                //Closet soporte
                this.clo_soporte_der = 0;
                this.clo_soporte_izq = 0;
                this.clo_soporte_princ = 0;
                this.clo_soporte_baja = 0;
            },

            listarObservacion(page, buscar){
                let me = this;
                var url = '/equipamiento/indexObservacion?page=' + page + '&buscar=' + buscar ;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayObservacion = respuesta.observacion.data;
                    console.log(url);
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },

            validarRevision(){
                this.errorRevision=0;
                this.errorMostrarMsjRevision=[];

                if(!this.observacion) //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjRevision.push("Escribir una observación");

                if(this.errorMostrarMsjRevision.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorRevision = 1;

                return this.errorRevision;
            },

            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.modal2 = 0;
                this.modal3 = 0;
                this.fecha_anticipo = '';
                this.anticipo = '';
                this.proveedor = '';
                this.equipamiento = '';
                this.equipamientos = '';
            },

            abrirModal(accion,data =[]){
                    switch(accion){

                        case 'observaciones':{
                            this.modal3 =1;
                            this.tituloModal='Observaciones';
                            this.solicitud_id = data['id'];
                            this.observacion = '';
                        }
                    }
                }
            
        },
       
        mounted() {
            this.listarHistorial(1,this.buscar2,this.b_etapa2,this.b_manzana2,this.b_lote2,this.criterio2);
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
