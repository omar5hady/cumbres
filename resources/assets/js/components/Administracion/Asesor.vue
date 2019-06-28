<template>
    <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                  <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
            </ol>
            <!--- Listado para Asesores-->
            <div class="container-fluid" v-if="listadoProspectos==0">
                <!-- Ejemplo de tabla Listado -->
                <div class="card scroll-box">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Mis Asesores
                        <!--   Boton Nuevo    -->
                        <button type="button" @click="abrirModal('Personal','registrar')" class="btn btn-success">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-5" @click="limpiarBusqueda()"  v-model="criterio">
                                      <option value="personal.nombre">Nombre</option>
                                      <option value="users.usuario">Usuario</option>
                                      <option value="vendedores.tipo">Tipo</option>
                                      <option value="vendedores.inmobiliaria">Inmobiliaria</option>
                                    </select>
                                    
                                 
                                    <select class="form-control col-md-5"  v-if="criterio=='vendedores.tipo'" v-model="buscar" @keyup.enter="listarPersonal(1,buscar,criterio)" >
                                        <option value="0" >Interno</option>
                                        <option value="1" >Externo</option>
                                    </select>
                                    <input v-else type="text" v-model="buscar" @keyup.enter="listarPersonal(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">                                     
                                    <button type="submit" @click="listarPersonal(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Nombre</th>
                                        <th>Usuario</th>
                                        <th>Rol</th>
                                        <th>Tipo</th>
                                        <th>Inmobiliaria</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="Personal in arrayPersonal" :key="Personal.id"  @dblclick="mostrarProspectos(Personal.nombre, Personal.id)">
                                        <td width="10%">
                                            <button type="button" @click="abrirModal('Personal','actualizar',Personal)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                            </button>
                                            <template v-if="Personal.condicion">
                                                <button type="button" @click="desactivarPersonal(Personal.id)" class="btn btn-danger btn-sm">
                                                <i class="fa fa-user-times"></i>
                                                </button>
                                            </template>
                                            <template v-else>
                                                <button type="button" @click="activarPersonal(Personal.id)" class="btn btn-success btn-sm">
                                                    <i class="icon-check"></i>
                                                </button>
                                            </template>
                                      
                                        </td>
                                        <td v-text="Personal.nombre + ' ' + Personal.apellidos" ></td>
                                        
                                        <td v-text="Personal.usuario"></td>
                                        <td v-text="Personal.rol"></td>
                                        <td v-if="Personal.tipo==0" v-text="'Interno'"></td>
                                        <td v-else v-text="'Externo'"></td>
                                        <td v-text="Personal.inmobiliaria"></td>
                                        <td>
                                            <span v-if = "Personal.condicion==1" class="badge badge-success">Activo</span>
                                            <span v-if = "Personal.condicion==0" class="badge badge-danger">Inactivo</span>
                                        </td>
                                                           
                                    
                                    </tr>                               
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--- Listado para Prospectos segun asesor-->
            <div class="container-fluid" v-if="listadoProspectos==1">
                <!-- Ejemplo de tabla Listado -->
                <div class="card scroll-box">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> 
                        <strong><label v-text="'  Prospectos de: '+ asesor"></label></strong>
                        <!--   Boton Nuevo    -->
                        &nbsp;
                        <button type="button" @click="regresarAsesores()" class="btn btn-secondary">
                            <i class="icon-action-undo"></i>&nbsp;Regresar
                        </button>
                        <!---->
                    </div>
                    <template v-if="listadoProspectos==1">
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <!--Criterios para el listado de busqueda -->
                                        <select class="form-control col-md-5" @click="limpiarBusqueda()"  v-model="criterio2">
                                        <option value="personal.nombre">Nombre</option>
                                        <option value="personal.rfc">RFC</option>
                                        <option value="personal.id"># Identificador</option>
                                        </select>
                                        
                                    
                                        <input type="text" v-model="buscar2" @keyup.enter="listarProspectos(1,buscar2,criterio2,id_vendedor)" class="form-control" placeholder="Texto a buscar">                                     
                                        <button type="submit" @click="listarProspectos(1,buscar2,criterio2,id_vendedor)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table2 table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th># Id.</th>
                                            <th>Nombre</th>
                                            <th>Lugar de contacto</th>
                                            <th>Proyecto de interes</th>
                                            <th>Correo</th>
                                            <th>Celular</th>
                                            <th>RFC</th>
                                            <th>Empresa donde trabaja</th>
                                            <th>Clasificación</th>
                                            <th>Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="Personal in arrayProspectos" :key="Personal.id" @dblclick="abrirModal4('ver',Personal)">
                                            <td class="td2" width="10%">
                                                <button type="button" @click="abrirModalCambio(Personal)" class="btn btn-primary btn-sm">
                                                    <i class="fa fa-exchange"></i>
                                                </button>
                                                <button type="button" @click="abrirModal4('actualizar',Personal)" class="btn btn-warning btn-sm">
                                                    <i class="fa fa-pencil"></i>
                                                </button>
                                            </td>
                                            <td class="td2" v-text="Personal.id" ></td>
                                            <td class="td2" v-text="Personal.nombre + ' ' + Personal.apellidos" ></td>
                                            <td class="td2" v-text="Personal.lugar_contacto"></td>
                                            <td class="td2" v-text="Personal.proyecto"></td>
                                            <td class="td2" v-text="Personal.email"></td>
                                            <td class="td2" v-text="Personal.celular"></td>
                                            <td class="td2" v-text="Personal.rfc"></td>
                                            <td class="td2" v-text="Personal.empresa"></td>
                                                <td class="td2" v-if="Personal.clasificacion==1" > No viable</td>
                                                <td class="td2" v-if="Personal.clasificacion==2" > Tipo A</td>
                                                <td class="td2" v-if="Personal.clasificacion==3" > Tipo B</td>
                                                <td class="td2" v-if="Personal.clasificacion==4" > Tipo C</td>
                                                <td class="td2" v-if="Personal.clasificacion==5" > Ventas</td>
                                                <td class="td2" v-if="Personal.clasificacion==6" > Cancelado</td>
                                            <td class="td2">
                                                <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right" @click="abrirModal3('prospecto','ver_todo',Personal.id),listarObservacion(1,Personal.id)">Ver todas</button> 
                                            </td>
                                        </tr>                               
                                    </tbody>
                                </table>
                            </div>
                            <nav>
                                <!--Botones de paginacion -->
                                <ul class="pagination">
                                    <li class="page-item" v-if="pagination2.current_page > 1">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page - 1,buscar2,criterio2,id_vendedor)">Ant</a>
                                    </li>
                                    <li class="page-item" v-for="page in pagesNumber2" :key="page" :class="[page == isActived ? 'active' : '']">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina2(page,buscar2,criterio2,id_vendedor)" v-text="page"></a>
                                    </li>
                                    <li class="page-item" v-if="pagination2.current_page < pagination2.last_page">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page + 1,buscar2,criterio2,id_vendedor)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </template>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar Asesor-->
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
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                  <div class="form-group row" v-if="tipoAccion > 1">
                                    <label class="col-md-3 form-control-label" for="text-input">Apellidos</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="apellidos" class="form-control" placeholder="Apellidos" >
                                    </div>
                                </div>
                                   <div class="form-group row" v-if="tipoAccion > 1">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" maxlength="25" v-model="nombre" class="form-control" placeholder="Nombre" >
                                    </div>
                                </div>
                                <div class="form-group row" v-if="tipoAccion > 1">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de nacimiento</label>
                                    <div class="col-md-6">
                                        <input type="date" v-model="f_nacimiento" class="form-control" placeholder="Fecha de nacimiento" >
                                    </div>
                                </div>
                                     <div class="form-group row" v-if="tipoAccion > 1">
                                    <label class="col-md-3 form-control-label" for="text-input">RFC</label>
                                    <div class="col-md-4">
                                        <input type="text" maxlength="10" style="text-transform:uppercase" v-model="rfc" class="form-control" placeholder="RFC" >
                                    </div>
                                    <div class="col-md-4" >
                                        <input type="text" maxlength="3" style="text-transform:uppercase" v-model="homoclave" class="form-control" placeholder="Homoclave" >
                                    </div>
                                </div>

                                <div class="form-group row" v-if="tipoAccion > 1">
                                    <label class="col-md-3 form-control-label" for="text-input">Codigo Postal</label>
                                    <div class="col-md-4">
                                        <input type="text" pattern="\d*" maxlength="5" v-model="cp" v-on:keypress="isNumber($event)" @keyup="selectColonias(cp)" class="form-control" placeholder="Codigo postal" >
                                    </div>
                                </div>

                                <div class="form-group row" v-if="tipoAccion > 1">
                                    <label class="col-md-3 form-control-label" for="text-input">Colonia</label>
                                    <div class="col-md-6">
                                        <select class="form-control" v-model="colonia" >
                                            <option value="0">Seleccione</option>
                                            <option v-for="colonias in arrayColonias" :key="colonias.colonia" :value="colonias.colonia" v-text="colonias.colonia"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" v-if="tipoAccion > 1">
                                    <label class="col-md-3 form-control-label" for="text-input">Direccion</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="direccion" class="form-control" placeholder="Direccion" >
                                    </div>
                                </div>

                                <div class="form-group row" v-if="tipoAccion > 1">
                                    <label class="col-md-3 form-control-label" for="text-input">Telefono</label>
                                    <div class="col-md-5">
                                        <input type="text" pattern="\d*" maxlength="7" v-on:keypress="isNumber($event)" class="form-control" v-model="telefono"  placeholder="Telefono" >
                                    </div>
                                </div>

                                <div class="form-group row" v-if="tipoAccion > 1">
                                    <label class="col-md-3 form-control-label" for="text-input">Extension</label>
                                    <div class="col-md-3">
                                        <input type="text" pattern="\d*" maxlength="3" v-on:keypress="isNumber($event)" v-model="ext" class="form-control" placeholder="Extension" >
                                    </div>
                                </div>
                            
                                <div class="form-group row" v-if="tipoAccion > 1">
                                    <label class="col-md-3 form-control-label" for="text-input">Celular</label>
                                    <div class="col-md-5">
                                        <input type="text" pattern="\d*" maxlength="10" v-on:keypress="isNumber($event)" v-model="celular" class="form-control" placeholder="Celular" >
                                    </div>
                                </div>

                                <div class="form-group row" v-if="tipoAccion > 1">
                                    <label class="col-md-3 form-control-label" for="text-input">Correo electronico</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="email" class="form-control" placeholder="Correo electronico" >
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Usuario</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="usuario" class="form-control" placeholder="Usuario" >
                                    </div>
                                </div>

                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Contraseña</label>
                                    <div class="col-md-9">
                                        <input type="password" v-model="password" class="form-control" placeholder="Contraseña" >
                                    </div>
                                </div>

                                <div class="form-group row" v-if="rol_id==2">
                                    <label class="col-md-3 form-control-label" for="text-input">Tipo</label>
                                    <div class="col-md-6">
                                       <select class="form-control" v-model="tipo_vendedor" >
                                            <option value="0">Interno</option>
                                            <option value="1">Externo</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row" v-if="tipo_vendedor==1 && rol_id==2">
                                    <label class="col-md-3 form-control-label" for="text-input">Inmobiliaria</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="inmobiliaria" class="form-control" placeholder="Inmobiliaria" >
                                    </div>
                                </div>

                                <!-- Div para mostrar los errores que mande validerPersonal -->
                                <div v-show="errorPersonal" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjPersonal" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==3" class="btn btn-primary" @click="registrarPersonal()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarPersonal()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->

            <!--Inicio del modal asignar prospecto-->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal2}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="'Reasignar cliente a: ' + tituloModal2"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                    <!--Criterios para el listado de busqueda -->

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Asesor</label>
                                    <div class="col-md-6">
                                        <select class="form-control" v-model="asesor_id" >
                                            <option value="0">Seleccione</option>
                                            <option v-for="asesor in arrayAsesores" :key="asesor.id" :value="asesor.id" v-text="asesor.nombre + ' '+ asesor.apellidos"></option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" class="btn btn-primary" @click="asignarProspecto(asesor_id,prospecto_id)">Reasignar </button>
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
                            <h4 class="modal-title" v-text="tituloModal3"></h4>
                            <button type="button" class="close" @click="cerrarModal3()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nueva observación</label>
                                    <div class="col-md-9">
                                        <textarea rows="1" cols="30" class="form-control" v-model="observacion" placeholder="Observaciones"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary" @click="registrarObservacion()">Guardar</button>
                                    </div>
                                </div>
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
                                
                            
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal3()">Cerrar</button>
                        </div>
                    </div>
                      <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>

            <!--Inicio del modal actualizar/ver Prospecto-->
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
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Apellidos</label>
                                    <div class="col-md-4">
                                        <input type="text" :disabled="tipoAccion==2" v-model="apellidosProspecto" class="form-control" placeholder="Apellidos" >
                                    </div>
                                    <label class="col-md-2 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-4">
                                        <input type="text" :disabled="tipoAccion==2" v-model="nombreProspecto" class="form-control" placeholder="Apellidos" >
                                    </div>
                                </div>
                                 
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Clasificación</label>
                                    <div class="col-md-3">
                                        <select :disabled="tipoAccion==2" class="form-control" v-model="clasificacionProspecto" >
                                            <option value="1">No viable</option>
                                            <option value="2">Tipo A</option>
                                            <option value="3">Tipo B</option>
                                            <option value="4">Tipo C</option>
                                            <option value="5">Ventas</option>
                                            <option value="6">Cancelado</option>                               
                                        </select>
                                    </div>
                                    <label class="col-md-1 form-control-label" for="text-input">Sexo</label>
                                    <div class="col-md-3">
                                        <select :disabled="tipoAccion==2" class="form-control" v-model="sexoProspecto" >
                                            <option value="">Seleccione</option>
                                            <option value="F">Femenino</option>
                                            <option value="M">Masculino</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Direccion</label>
                                    <div class="col-md-6">
                                        <input :disabled="tipoAccion==2" type="text" style="text-transform:uppercase" v-model="direccionProspecto" class="form-control" placeholder="RFC" >
                                    </div>
                                    <label class="col-md-1 form-control-label" for="text-input">C.P. </label>
                                    <div class="col-md-3" >
                                        <input :disabled="tipoAccion==2" type="text" style="text-transform:uppercase" pattern="\d*" maxlength="5" v-on:keypress="isNumber($event)" @keyup="selectColonias(cpProspecto)" v-model="cpProspecto" class="form-control" placeholder="Codigo Postal" >
                                    </div>
                                </div>

                                <div class="form-group row">
                                    
                                    <label class="col-md-2 form-control-label" for="text-input">Colonia</label>
                                    <div class="col-md-4">
                                        <select :disabled="tipoAccion==2" class="form-control" v-model="coloniaProspecto" >
                                            <option value="">Seleccione</option>
                                            <option v-for="colonias in arrayColonias" :key="colonias.colonia" :value="colonias.colonia" v-text="colonias.colonia"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Telefono</label>
                                    <div class="col-md-4">
                                        <input :disabled="tipoAccion==2" type="text" v-on:keypress="isNumber($event)" pattern="\d*" maxlength="10" v-model="telefonoProspecto" class="form-control" placeholder="Telefono" >
                                    </div>

                                    <label class="col-md-2 form-control-label" for="text-input">Celular</label>
                                    <div class="col-md-4">
                                        <input :disabled="tipoAccion==2" type="text" v-on:keypress="isNumber($event)" pattern="\d*" maxlength="10" v-model="celProspecto" class="form-control" placeholder="Celular" >
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Email</label>
                                    <div class="col-md-4">
                                        <input :disabled="tipoAccion==2" type="text" class="form-control" v-model="correoProspecto"  placeholder="Email" >
                                    </div>
                                    <label class="col-md-2 form-control-label" for="text-input">Email Inst.</label>
                                    <div class="col-md-4">
                                        <input :disabled="tipoAccion==2" type="text" class="form-control" v-model="correo_instProspecto"  placeholder="Email" >
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">F. Nacimiento</label>
                                    <div class="col-md-3">
                                        <input :disabled="tipoAccion==2" type="date" class="form-control"  v-model="fecha_nacProspecto" >
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">RFC</label>
                                    <div class="col-md-4">
                                        <input :disabled="tipoAccion==2" type="text" maxlength="10" style="text-transform:uppercase" v-model="rfcProspecto" class="form-control" placeholder="RFC" >
                                    </div>
                                    <div class="col-md-2" >
                                        <input :disabled="tipoAccion==2" type="text" maxlength="3" style="text-transform:uppercase" v-model="homoclaveProspecto" class="form-control" placeholder="Homoclave" >
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">CURP</label>
                                    <div class="col-md-4">
                                        <input :disabled="tipoAccion==2" type="text" maxlength="13" style="text-transform:uppercase" v-model="curpProspecto" class="form-control" placeholder="CURP" >
                                    </div>
                                    <label class="col-md-1 form-control-label" for="text-input">NSS</label>
                                    <div class="col-md-4" >
                                        <input :disabled="tipoAccion==2" type="text" v-on:keypress="isNumber($event)" pattern="\d*" maxlength="11" style="text-transform:uppercase" v-model="nssProspecto" class="form-control" placeholder="Homoclave" >
                                    </div>
                                </div>
                            
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Edo. Civil</label>
                                    <div class="col-md-4">
                                        <select :disabled="tipoAccion==2" class="form-control" v-model="e_civilProspecto" >
                                            <option value="0">Seleccione</option> 
                                            <option value="1">Casado - separacion de bienes</option> 
                                            <option value="2">Casado - sociedad conyugal</option> 
                                            <option value="3">Divorciado</option> 
                                            <option value="4">Soltero</option> 
                                            <option value="5">Union libre</option>
                                            <option value="6">Viudo</option> 
                                            <option value="7">Otro</option>    
                                        </select>
                                    </div>

                                    <label class="col-md-2 form-control-label" for="text-input">Vive en casa</label>
                                    <div class="col-md-4">
                                        <select :disabled="tipoAccion==2" class="form-control" v-model="tipo_casaProspecto" >
                                            <option value="0">Seleccione</option>  
                                            <option value="De familiares">De familiares</option>
                                            <option value="Prestada">Prestada</option>
                                            <option value="Propia">Propia</option>
                                            <option value="Rentada">Rentada</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-4 form-control-label" for="text-input">Proyecto en el que esta interesado</label>
                                    <div class="col-md-4">
                                        <select :disabled="tipoAccion==2" class="form-control" v-model="ProyectoProspecto" >
                                            <option value="0">Seleccione</option>
                                            <option v-for="proyecto in arrayProyectos" :key="proyecto.id" :value="proyecto.id" v-text="proyecto.nombre"></option>
                                        </select>
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-md-4 form-control-label" for="text-input">Medio donde se entero de nostros</label>
                                    <div class="col-md-4">
                                        <select :disabled="tipoAccion==2" class="form-control" v-model="publicidad_id" >
                                            <option value="0">Seleccione</option>
                                            <option v-for="medios in arrayMediosPublicidad" :key="medios.id" :value="medios.id" v-text="medios.nombre"></option>    
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal4()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="actualizarProspecto()">Actualizar</button>
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
                listadoProspectos:0,
                proceso:false,
                asesor:'',
                id:0,
                id_vendedor:0,
                prospecto_id:0,
                asesor_id:0,
                usuario: '',
                password: '',
                condicion: 1,
                rol_id: 2,
                departamento_id : 9,
                empresa_id : 1,
                nombre : '',
                apellidos : '',
                f_nacimiento: '',
                rfc : '',
                homoclave : '',
                colonia : '',
                direccion : '',
                observacion:'',
                cp: 0,
                telefono: 0,
                ext: 0,
                celular: 0,
                email: '',
                activo: 1, 
                tipo_vendedor:0,

                nombreProspecto:'',
                apellidosProspecto:'',
                clasificacionProspecto:'',
                sexoProspecto:'',
                direccionProspecto:'',
                cpProspecto:'',
                coloniaProspecto:'',
                telefonoProspecto:'',
                celProspecto:'',
                correoProspecto:'',
                correo_instProspecto:'',
                fecha_nacProspecto:'',
                rfcProspecto:'',
                nssProspecto:'',
                curpProspecto:'',
                homoclaveProspecto:'',
                e_civilProspecto: 0,
                tipo_casaProspecto: 0,
                ProyectoProspecto: 0,
                publicidad_id:0,

                inmobiliaria:'',
                arrayPersonal : [],
                arrayProspectos : [],
                arrayAsesores : [],
                arrayObservacion : [],
                arrayProyectos:[],
                arrayMediosPublicidad:[],
                modal : 0,
                modal2 : 0,
                modal3 : 0,
                modal4 : 0,
                tituloModal : '',
                tituloModal2 : '',
                tituloModal3 : '',
                tipoAccion: 0,
                errorPersonal : 0,
                errorMostrarMsjPersonal : [],
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                pagination2 : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'personal.nombre', 
                buscar : '',
                buscar2 : '',
                criterio2 : 'personal.nombre',
                arrayColonias : []

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
            }
        },
        
        methods : {
            /**Metodo para mostrar los registros */
            listarPersonal(page, buscar, criterio){
                let me = this;
                var url = '/asesores?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPersonal = respuesta.personas.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listarProspectos(page, buscar, criterio,vendedor){
                let me = this;
                var url = '/asesores/clientes?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio + '&vendedor=' + vendedor;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayProspectos = respuesta.personas.data;
                    me.pagination2 = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listarObservacion(page, buscar){
                let me = this;
                var url = '/clientes/observacion?page=' + page + '&buscar=' + buscar ;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayObservacion = respuesta.observacion.data;
                    console.log(url);
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },
            selectColonias(buscar){
                let me = this;
                me.arrayColonias=[];
                var url = '/select_colonias?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayColonias = respuesta.colonias;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectMedioPublicidad(){
                let me = this;
                me.arrayMediosPublicidad=[];
                var url = '/select_medio_publicidad';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayMediosPublicidad = respuesta.medios_publicitarios;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
             selectFraccionamientos(){
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
            selectAsesores(){
                let me = this;
                me.arrayAsesores=[];
                var url = '/select/asesores';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayAsesores = respuesta.personas;
                })
                .catch(function (error) {
                    console.log(error);
                });
              
            },
            limpiarBusqueda(){
                let me=this;
                me.buscar= "";
            },
            selectEmpresa(){
                let me = this;
                me.arrayEmpresas=[];
                var url = '/select_empresas';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEmpresas = respuesta.empresas;
                })
                .catch(function (error) {
                    console.log(error);
                });
              
            },
            cambiarPagina(page, buscar, criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarPersonal(page,buscar,criterio);
            },
             cambiarPagina2(page, buscar, criterio,id_vendedor){
                let me = this;
                //Actualiza la pagina actual
                me.pagination2.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarProspectos(page,buscar,criterio,id_vendedor);
            },
            mostrarProspectos(nombre,id){
                this.id_vendedor = id;
                this.listarProspectos(1,this.buscar2,this.criterio2,id)
                this.listadoProspectos=1;
                this.asesor = nombre;
            },
            regresarAsesores(){
                this.listadoProspectos=0;
                this.asesor='';
                this.arrayProspectos=[];
                this.buscar2='';
                this.criterio2='personal.nombre';
            },
            /**Metodo para registrar  */
            registrarPersonal(){
                if(this.validarPersonal() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;

                let me = this;
                //Con axios se llama el metodo store de PersonalController
                axios.post('/usuarios/registrar',{
                    'departamento_id': this.departamento_id,
                    'nombre': this.nombre,
                    'apellidos': this.apellidos,
                    'f_nacimiento': this.f_nacimiento,
                    'rfc': this.rfc,
                    'colonia': this.colonia,
                    'direccion': this.direccion,
                    'cp': this.cp,
                    'telefono': this.telefono,
                    'ext': this.ext,
                    'celular': this.celular,
                    'email': this.email,
                    'activo': this.activo,
                    'empresa_id': this.empresa_id,
                    'usuario': this.usuario,
                    'password': this.password,
                    'rol_id' : this.rol_id,
                    'condicion':this.condicion,
                    'tipo_vendedor':this.tipo_vendedor,
                    'inmobiliaria':this.inmobiliaria 
                    
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarPersonal(1,'','Personal'); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Personal agregado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    swal({
                        type: 'error',
                        title: 'Oops...',
                        text: 'El RFC que ha ingresado ya se encuentra registrado!',
                        })
                    console.log(error);
                });
            },
            registrarObservacion(){

                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/clientes/storeObservacion',{
                    'cliente_id':this.prospecto_id,
                    'observacion':this.observacion                 
                }).then(function (response){
                    me.listarObservacion(1,me.prospecto_id);
                    me.observacion='';
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Observacion agregada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            actualizarPersonal(){
                if(this.validarPersonal() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;

                let me = this;
                //Con axios se llama el metodo update de PersonalController
                axios.put('/usuarios/actualizar',{
                    'departamento_id': this.departamento_id,
                    'nombre': this.nombre,
                    'apellidos': this.apellidos,
                    'f_nacimiento': this.f_nacimiento,
                    'rfc': this.rfc.toUpperCase(),
                    'colonia': this.colonia,
                    'direccion': this.direccion,
                    'cp': this.cp,
                    'telefono': this.telefono,
                    'ext': this.ext,
                    'celular': this.celular,
                    'email': this.email,
                    'activo': this.activo,
                    'id' : this.id,
                    'empresa_id' : this.empresa_id,
                    'usuario': this.usuario,
                    'password': this.password,
                    'rol_id' : this.rol_id,
                    'condicion':this.condicion,
                    'tipo_vendedor':this.tipo_vendedor,
                    'inmobiliaria':this.inmobiliaria
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal();
                    me.listarPersonal(1,'','nombre');
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
            /**Metodo para actualizar  */
            actualizarProspecto(){
                /*if(this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;*/

                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.put('/clientes/actualizar2',{
                    'id':this.prospecto_id,
                    'clasificacion':this.clasificacionProspecto,
                    'nombre':this.nombreProspecto,
                    'apellidos':this.apellidosProspecto,
                    'telefono':this.telefonoProspecto ,
                    'celular':this.celProspecto ,
                    'email':this.correoProspecto,
                    'email_institucional':this.correo_instProspecto,
                    'nss':this.nssProspecto,
                    'sexo':this.sexoProspecto,
                    'f_nacimiento':this.fecha_nacProspecto,
                    'curp':this.curpProspecto,
                    'rfc':this.rfcProspecto,
                    'homoclave':this.homoclaveProspecto,
                    'edo_civil':this.e_civilProspecto,
                    'tipo_casa':this.tipo_casaProspecto,
                    'publicidad_id':this.publicidad_id,
                    'proyecto_interes_id':this.ProyectoProspecto,
                    'direccion':this.direccionProspecto,
                    'colonia':this.coloniaProspecto,
                    'cp':this.cpProspecto

                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal4();
                    me.listarProspectos(1,'','personal.nombre',me.id_vendedor);
                    
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Prospecto actualizado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            desactivarPersonal(id){
               swal({
                title: 'Esta seguro de desactivar a este usuario?',
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

                    axios.put('/usuarios/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarPersonal(1,'','Personal');
                        swal(
                        'Desactivado!',
                        'El registro ha sido desactivado con éxito.',
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
            asignarProspecto(asesor,prospecto){
               swal({
                title: 'Esta seguro de reasignar a este cliente?',
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

                    axios.put('/cliente/reasignar',{
                        'id': prospecto,
                        'asesor_id':asesor
                    }).then(function (response) {
                        me.listarProspectos(1,'','personal.nombre',me.id_vendedor);
                        me.cerrarModal();
                        swal(
                        'Hecho!',
                        'El cliente ha sido reasignado con éxito.',
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
            activarPersonal(id){
               swal({
                title: 'Esta seguro de activar a este usuario?',
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

                    axios.put('/usuarios/activar',{
                        'id': id
                    }).then(function (response) {
                        me.listarPersonal(1,'','Personal');
                        swal(
                        'Activado!',
                        'El registro ha sido activado con éxito.',
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
            eliminarPersonal(data =[]){
                this.id=data['id'];
                this.departamento_id=data['departamento_id'];
                this.nombre=data['nombre'];
                this.apellidos=data['apellidos'];
                this.f_nacimiento=data['f_nacimiento'];
                this.rfc=data['rfc'];
                this.colonia=data['colonia'];
                this.direccion=data['direccion'];
                this.cp=data['cp'];
                this.telefono=data['telefono'];
                this.ext=data['ext'];
                this.celular=data['celular'];
                this.email=data['email'];
                this.activo=data['activo'];
                //console.log(this.Personal_id);
                swal({
                title: '¿Desea eliminar?',
                text: "Esta acción no se puede revertir!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si, eliminar!'
                }).then((result) => {
                if (result.value) {
                    let me = this;

                axios.delete('/personal/eliminar', 
                        {params: {'id': this.id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Personal borrado correctamente.',
                        'success'
                        )
                        me.listarPersonal(1,'','Personal');
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            validarPersonal(){
                this.errorPersonal=0;
                this.errorMostrarMsjPersonal=[];

                if(!this.nombre || !this.apellidos) //Si la variable Personal esta vacia
                    this.errorMostrarMsjPersonal.push("El nombre de la Persona no puede ir vacio.");

                if(!this.email)
                    this.errorMostrarMsjPersonal.push("El correo no debe ir vacio");
                
                if(!this.rfc || this.rfc.length<10)
                    this.errorMostrarMsjPersonal.push("El RFC no debe ir vacio (10 caracteres)");
                
                if(!this.celular || this.celular.length<10)
                    this.errorMostrarMsjPersonal.push("El número de celular debe ser de 10 digitos");
                
                if(!this.direccion)
                    this.errorMostrarMsjPersonal.push("La direccion no puede ir vacio");

                if(!this.departamento_id)
                    this.errorMostrarMsjPersonal.push("Debe seleccionar el departamento");

                if(!this.f_nacimiento)
                    this.errorMostrarMsjPersonal.push("La fecha de nacimiento no puede ir vacia");

                if(!this.usuario)
                    this.errorMostrarMsjPersonal.push("Ingresar nombre de usuario");
                
                if(!this.password)
                    this.errorMostrarMsjPersonal.push("Ingresar contraseña");

                if(!this.rol_id)
                    this.errorMostrarMsjPersonal.push("Seleccionar rol");

                if(this.errorMostrarMsjPersonal.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorPersonal = 1;

                return this.errorPersonal;
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
            cerrarModal(){
                this.modal = 0;
                this.departamento_id = 9;
                this.empresa_id = 1;
                this.nombre='';
                this.apellidos='';
                this.f_nacimiento='';
                this.rfc='';
                this.colonia='';
                this.direccion='';
                this.cp='';
                this.telefono='';
                this.ext='';
                this.celular='';
                this.email='';
                this.activo=1;
                this.password = '';
                this.usuario = '';
                this.condicion = 1;
                this.errorPersonal = 0;
                this.errorMostrarMsjPersonal = [];
                this.inmobiliaria='';
                this.tipo_vendedor=0;
                this.tituloModal2='';
                this.modal2=0;
                this.arrayAsesores=[];
            

            },
            cerrarModal3(){
                this.modal3 = 0;
                this.tituloModal3 = '';
            },
            cerrarModal4(){
                this.modal4=0;
                this.prospecto_id=0;
                this.nombreProspecto='';
                this.apellidosProspecto='';
                this.clasificacionProspecto='';
                this.sexoProspecto='';
                this.direccionProspecto='';
                this.cpProspecto='';
                this.coloniaProspecto='';
                this.telefonoProspecto='';
                this.celProspecto='';
                this.correoProspecto='';
                this.correo_instProspecto='';
                this.fecha_nacProspecto='';
                this.rfcProspecto='';
                this.homoclaveProspecto='';
                this.e_civilProspecto= 0;
                this.tipo_casaProspecto= 0;
                this.ProyectoProspecto= 0;
                this.publicidad_id=0;
                this.nssProspecto='';
                this.curpProspecto='';
            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(modelo, accion,data =[]){
                switch(modelo){
                    case "Personal":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Personal';
                                this.departamento_id = '9',
                                this.empresa_id = 1,
                                this.nombre='';
                                this.apellidos='';
                                this.f_nacimiento='';
                                this.rfc='';
                                this.homoclave='';
                                this.colonia='0';
                                this.direccion='';
                                this.cp='';
                                this.telefono='';
                                this.ext='';
                                this.celular='';
                                this.email='';
                                this.usuario='';
                                this.password='';
                                this.condicion=1;
                                this.rol_id=2;
                                this.inmobiliaria='';
                                this.tipo_vendedor=0;

                                this.activo='1';
                                this.tipoAccion = 3;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal =1;
                                this.tituloModal='Actualizar Personal';
                                this.tipoAccion=2;
                                this.id=data['id'];
                                this.departamento_id=data['departamento_id'];
                                this.empresa_id=data['empresa_id'];
                                this.nombre=data['nombre'];
                                this.apellidos=data['apellidos'];
                                this.f_nacimiento=data['f_nacimiento'];
                                this.rfc=data['rfc'];
                                this.homoclave=data['homoclave'];
                                this.colonia=data['colonia'];
                                this.direccion=data['direccion'];
                                this.cp=data['cp'];
                                this.telefono=data['telefono'];
                                this.ext=data['ext'];
                                this.celular=data['celular'];
                                this.email=data['email'];
                                this.activo=data['activo'];
                                this.usuario=data['usuario'];
                                this.rol_id=data['rol_id'];
                                this.password=data['password'];
                                this.condicion=data['condicion'];
                                this.tipoAccion = 2;
                                this.inmobiliaria='';
                                this.tipo_vendedor=0;
                                break;
                            }
                        }
                    }
                }
                this.selectColonias(this.cp);
            },
            abrirModal3(prospectos,accion,id){
             switch(prospectos){
                    case "prospecto":
                    {
                        switch(accion){
                         
                            case 'ver_todo':
                            {
                                this.modal3 =1;
                                this.tituloModal3='Consulta Observaciones';
                                this.prospecto_id = id;
                                break;  
                            }
                            
                        }
                    }
                 
             }
            },
            abrirModalCambio(data=[]){
                this.selectAsesores();
                this.tituloModal2 = data['nombre'] + ' ' + data['apellidos'];
                this.modal2=1;
                this.prospecto_id = data['id'];
            },
            abrirModal4(accion,data=[]){
                this.selectMedioPublicidad();
                this.selectFraccionamientos();
                this.selectColonias(data['cp']);

                this.prospecto_id=data['id'];
                this.nombreProspecto=data['nombre'];
                this.apellidosProspecto=data['apellidos'];
                this.clasificacionProspecto=data['clasificacion'];
                this.sexoProspecto=data['sexo'];
                this.direccionProspecto=data['direccion'];
                this.cpProspecto=data['cp'];
                this.coloniaProspecto=data['colonia'];
                this.telefonoProspecto=data['telefono'];
                this.celProspecto=data['celular'];
                this.correoProspecto=data['email'];
                this.correo_instProspecto=data['email_institucional'];
                this.fecha_nacProspecto=data['f_nacimiento'];
                this.rfcProspecto=data['rfc'];
                this.homoclaveProspecto=data['homoclave'];
                this.e_civilProspecto= data['edo_civil'];
                this.tipo_casaProspecto= data['tipo_casa'];
                this.ProyectoProspecto= data['proyecto_interes_id'];
                this.publicidad_id= data['publicidad_id'];
                this.nssProspecto=data['nss'];
                this.curpProspecto=data['curp'];
                switch(accion){
                    case "actualizar":{
                        this.tituloModal='Editar Prospecto ';
                        this.tipoAccion=1;
                        this.modal4=1;
                        break;
                    }
                    case "ver":{
                        this.tituloModal='Datos del Prospecto ';
                        this.tipoAccion=2;
                        this.modal4=1;
                        break;
                    }
                }
            }
            
        },
        mounted() {
            this.listarPersonal(1,this.buscar,this.criterio);
        }
    }
</script>
<style>
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

input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
  -webkit-appearance: none; 
   margin: 0;  
} 


</style>
