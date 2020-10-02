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
                        <i class="fa fa-align-justify"></i> Usuarios
                        <!--   Boton Nuevo    -->
                        <button type="button" @click="abrirModal('Personal','registrar')" class="btn btn-secondary" v-if="privilegios==0">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                        <button type="button" @click="abrirModal('Personal','Asignar')" class="btn btn-warning" v-if="privilegios==0">
                            <i class="fa fa-user-plus"></i>&nbsp;Asignar rol
                        </button>
                        <button type="button" @click="cerrarPrivilegios()" class="btn btn-success" v-if="privilegios==1">
                            <i class="fa fa-mail-reply"></i>&nbsp;Regresar
                        </button>
                        <!---->
                    </div>
                    <template v-if="privilegios==0">
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <!--Criterios para el listado de busqueda -->
                                        <select class="form-control col-md-5" @click="selectDepartamento(),limpiarBusqueda()"  v-model="criterio">
                                            <option value="personal.nombre">Nombre</option>
                                            <option value="users.usuario">Usuario</option>
                                            <option value="roles.nombre">Rol</option>
                                        </select>
                                        
                                        
                                        <select class="form-control" v-if="criterio == 'roles.nombre'" v-model="buscar">
                                            <option value="">Seleccione</option>
                                            <option value="Administrador">Administrador</option>
                                            <option value="Asesor">Asesor</option>
                                            <option value="Gerente Proyectos">Gerente Proyectos</option>
                                            <option value="Gerente ventas">Gerente ventas</option>
                                            <option value="Gerente obra">Gerente obra</option>
                                            <option value="Admin Ventas">Admin Ventas</option>
                                            <option value="Publicidad">Publicidad</option>
                                            <option value="Gestor ventas">Gestor ventas</option>
                                            <option value="Contabilidad">Contabilidad</option>
                                            <option value="Proveedor">Proveedor</option>
                                            <option value="Contratista">Contratista</option>
                                        </select>
                                        <input type="text" v-else v-model="buscar" @keyup.enter="listarPersonal(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarPersonal(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table2 table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Nombre</th>
                                            <th>Usuario</th>
                                            <th>Rol</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="Personal in arrayPersonal" :key="Personal.id" v-on:dblclick="getPrivilegios(Personal.id)" title="Ver privilegios">
                                            <td class="td2" width="25%">
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
                                                 <button v-if="Personal.rol_id == 2" title="Asignar asesor a un gerente" type="button" @click="abrirModal('Personal','asignarGerente',Personal)" class="btn btn-dark btn-sm">
                                                <i class="icon-share"></i>
                                                </button>
                                        
                                            </td>
                                            <td class="td2" width="60%">
                                                <a href="#" v-text="Personal.nombre + ' ' + Personal.apellidos"></a>
                                            </td>
                                            
                                            <td class="td2" v-text="Personal.usuario"></td>
                                            <td class="td2" v-text="Personal.rol"></td>
                                            <td class="td2">
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
                    </template>
                    <template v-if="privilegios==1">
                        <div class="card-body"> 
                            <div class="form-group row border">
                                <div class="col-md-12">
                                    <div class="form-group">
                                  <center> <h4>Privilegios</h4> </center>
                                    </div>
                                </div> 

                                <!-- listado para privilegios del menu Administracion -->
                                <div class="col-md-4" >
                                    <div class="form-group row border">
                                            <a class="nav-link nav-dropdown-toggle"><i class="icon-energy"></i><input @click="limpiarAdministracion()" v-model="administracion" type="checkbox" value="1"/> Modulo Administración </a>
                                            <ul v-if="administracion==1" class="nav-dropdown-items">
                                                <li class="nav-item" >
                                                    <a class="nav-link"><i class="fa fa-object-group"></i> <input v-model="departamentos" type="checkbox" value="1"/> Departamentos</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"><i class="fa fa-vcard"></i> <input v-model="personas" type="checkbox" value="1"/> Personas</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"><i class="fa fa-industry"></i> <input v-model="empresas" type="checkbox" value="1"/> Empresas</a>
                                                </li>
                                                <li class="nav-item" >
                                                    <a class="nav-link"><i class="fa fa-bullhorn"></i> <input v-model="medios_public" type="checkbox" value="1"/> Medios Publicitarios</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"><i class="fa fa-street-view"></i> <input v-model="lugares_contacto" type="checkbox" value="1"/> Lugares de contacto</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"><i class="fa fa-credit-card"></i> <input v-model="servicios" type="checkbox" value="1"/> Servicios</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"><i class="fa fa-building-o"></i> <input v-model="inst_financiamiento" type="checkbox" value="1"/> Instituciones de financiamiento</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"><i class="fa fa-credit-card"></i> <input v-model="tipos_credito" type="checkbox" value="1"/> Tipos de crédito</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"><i class="fa fa-credit-card"></i> <input v-model="asig_servicios" type="checkbox" value="1"/> Asignar Servicios</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"><i class="fa fa-user-circle-o"></i> <input v-model="mis_asesores" type="checkbox" value="1"/> Mis Asesores</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"><i class="fa fa-credit-card"></i> <input v-model="cuenta" type="checkbox" value="1"/> Cuenta</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"><i class="fa fa-credit-card"></i> <input v-model="notaria" type="checkbox" value="1"/> Notarias</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link"><i class="fa fa-industry"></i> <input v-model="proveedores" type="checkbox" value="1"/> Proveedores</a>
                                                </li>
                                            </ul>
                                    </div>
                                </div> 

                                <!-- listado para privilegios del menu Desarrollo -->
                                <div class="col-md-4">
                                    <div class="form-group row border">
                                            <a class="nav-link nav-dropdown-toggle"><i class="icon-home"></i> <input @click="limpiarDesarrollo()" v-model="desarrollo" type="checkbox" value="1"/> Modulo Desarrollo</a>
                                                <ul class="nav-dropdown-items" v-if="desarrollo==1">
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="icon-bag"></i> <input v-model="fraccionamiento" type="checkbox" value="1"/> Fraccionamiento</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="icon-bag"></i> <input v-model="etapas" type="checkbox" value="1"/> Etapas</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="icon-bag"></i> <input v-model="modelos" type="checkbox" value="1"/> Modelos</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="icon-bag"></i> <input v-model="lotes" type="checkbox" value="1"/> Lotes</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="icon-bag"></i> <input v-model="asign_modelos" type="checkbox" value="1"/> Asignar Modelo</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="icon-bag"></i> <input v-model="licencias" type="checkbox" value="1"/> Licencias</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="icon-bag"></i> <input v-model="acta_terminacion" type="checkbox" value="1"/> Acta de terminacion</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="icon-bag"></i> <input v-model="p_etapa" type="checkbox" value="1"/> Publicidad-Etapas</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="icon-bag"></i> <input v-model="descarga_actas" type="checkbox" value="1"/> Prediales y actas</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="icon-bag"></i> <input v-model="ruv" type="checkbox" value="1"/> Solicitud de RUV</a>
                                                    </li>
                                                    <li class="nav-item" >
                                                        <a class="nav-link"><i class="icon-bag"></i> <input v-model="seg_ruv" type="checkbox" value="1"/> Seguimiento de RUV</a>
                                                    </li>
                                                </ul>
                                    </div>
                                </div> 

                                <!-- listado para privilegios del menu Ventas -->
                                <div class="col-md-4">
                                    <div class="form-group row border">
                                            <a class="nav-link nav-dropdown-toggle"><i class="icon-basket"></i> <input @click="limpiarVentas()" v-model="ventas" type="checkbox" value="1"/> Modulo Ventas</a>
                                                <ul class="nav-dropdown-items" v-if="ventas==1">
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="fa fa-circle-o-notch fa-spin"></i> <input v-model="lotes_disp" type="checkbox" value="1"/> Lotes Disponibles</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="fa fa-group"></i> <input v-model="mis_prospectos" type="checkbox" value="1"/> Mis prospectos</a>
                                                    </li> 
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="fa fa-calculator"></i> <input v-model="simulacion_credito" type="checkbox" value="1"/> Simulacion de credito</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="fa fa-archive"></i> <input v-model="hist_simulaciones" type="checkbox" value="1"/> Hist. de simulaciones</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="fa fa-archive"></i> <input v-model="hist_creditos" type="checkbox" value="1"/> Hist. creditos</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="fa fa-archive"></i> <input v-model="contratos" type="checkbox" value="1"/> Realizar contrato</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="fa fa-archive"></i> <input v-model="docs" type="checkbox" value="1"/> Docs</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="fa fa-archive"></i> <input v-model="equipamientos" type="checkbox" value="1"/> Equipamientos</a>
                                                    </li>
                                                </ul>
                                    </div>
                                </div>

                                <!-- listado para privilegios del menu Saldos -->
                                <div class="col-md-4">
                                    <div class="form-group row border">
                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-calculator"></i> <input @click="limpiarSaldo()" v-model="saldo" type="checkbox" value="1"/> Modulo Saldos</a>
                                                <ul class="nav-dropdown-items" v-if="saldo==1">
                                                    <li class="nav-item">
                                                        <a class="nav-link" ><i class="fa fa-dollar"></i> <input v-model="edo_cuenta" type="checkbox" value="1"/> Estado de cuenta</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" ><i class="fa fa-money"></i> <input v-model="depositos" type="checkbox" value="1"/> Depositos</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" ><i class="fa fa-money"></i> <input v-model="gastos_admn" type="checkbox" value="1"/> Gastos administrativos</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" ><i class="fa fa-credit-card-alt"></i> <input v-model="cobro_credito" type="checkbox" value="1"/> Cobro de credito</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" ><i class="icon-reload"></i> <input v-model="dev_cancel" type="checkbox" value="1"/>Devolución por cancelación</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" ><i class="icon-reload"></i> <input v-model="dev_exc" type="checkbox" value="1"/> Devolución por excedente</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" ><i class="icon-reload"></i> <input v-model="facturas" type="checkbox" value="1"/> Facturas</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" ><i class="icon-reload"></i> <input v-model="ingresos_concretania" type="checkbox" value="1"/> Ingresos Concretania</a>
                                                    </li>
                                                    
                                                </ul>
                                    </div>
                                </div> 

                                <!-- listado para privilegios del menu Gestoria -->
                                <div class="col-md-4">
                                    <div class="form-group row border">
                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-bank"></i> <input @click="limpiarGestoria()" v-model="gestoria" type="checkbox" value="1"/> Modulo Gestoria</a>
                                                <ul class="nav-dropdown-items" v-if="gestoria==1">
                                                    <li class="nav-item">
                                                        <a class="nav-link" ><i class="fa fa-folder-open"></i> <input v-model="expediente" type="checkbox" value="1"/> Expediente</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" ><i class="fa fa-group"></i> <input v-model="asig_gestor" type="checkbox" value="1"/> Asignar gestor</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" ><i class="fa fa-bullseye"></i> <input v-model="seg_tramite" type="checkbox" value="1"/> Seguimiento de tramite</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" ><i class="fa fa-money"></i> <input v-model="avaluos" type="checkbox" value="1"/> Avaluos</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" ><i class="fa fa-money"></i> <input v-model="bonos_rec" type="checkbox" value="1"/> Bonos por recomendación</a>
                                                    </li>
                                                </ul>
                                    </div>
                                </div> 

                                <!-- listado para privilegios del menu Obra -->
                                <div class="col-md-4">
                                    <div class="form-group row border">
                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-plug"></i> <input @click="limpiarObra()" v-model="obra" type="checkbox" value="1"/> Modulo Obra</a>
                                                <ul class="nav-dropdown-items" v-if="obra==1">
                                                    <li class="nav-item" >
                                                        <a class="nav-link" ><i class="fa fa-handshake-o"></i> <input v-model="contratistas" type="checkbox" value="1"/> Contratistas</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" ><i class="fa fa-play-circle"></i> <input v-model="ini_obra" type="checkbox" value="1"/> Inicio de obra</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" ><i class="fa fa-newspaper-o"></i> <input v-model="aviso_obra" type="checkbox" value="1"/> Aviso de obra</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" ><i class="fa fa-star-half"></i> <input v-model="partidas" type="checkbox" value="1"/> Partidas</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" ><i class="fa fa-star-half-o"></i> <input v-model="avance" type="checkbox" value="1"/> Avance</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="fa fa-archive"></i> <input v-model="equipamientos" type="checkbox" value="1"/> Equipamientos</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="fa fa-home"></i> <input v-model="entregas" type="checkbox" value="1"/> Viviendas por entregar</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="fa fa-home"></i> <input v-model="estimaciones" type="checkbox" value="1"/> Estimaciones</a>
                                                    </li>
                                                </ul>
                                    </div>
                                </div> 
                                
                                <!-- listado para privilegios del menu Reportes -->
                                <div class="col-md-4">
                                    <div class="form-group row border">
                                            <a class="nav-link nav-dropdown-toggle"><i class="icon-people"></i> <input @click="limpiarReportes()" v-model="reportes" type="checkbox" value="1"/> Modulo Reportes</a>
                                                <ul class="nav-dropdown-items" v-if="reportes==1">
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="icon-chart"></i> <input v-model="mejora" type="checkbox" value="1"/> Estadisticas Mejora</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="icon-chart"></i> <input v-model="rep_proy" type="checkbox" value="1"/> Resumen por proyecto</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="icon-chart"></i> <input v-model="rep_publi" type="checkbox" value="1"/> Estadisticas de Publicidad</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="icon-chart"></i> <input v-model="inventario" type="checkbox" value="1"/> Inventario contable</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="icon-chart"></i> <input v-model="rep_venta_canc" type="checkbox" value="1"/> Reporte de ventas y cancelaciones</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="icon-chart"></i> <input v-model="rep_asesores" type="checkbox" value="1"/> Reporte de asesores</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="icon-chart"></i> <input v-model="rep_ini_term_ventas" type="checkbox" value="1"/> Reporte de inicio, termino, ventas y cobranza</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="icon-chart"></i> <input v-model="rep_recursos_propios" type="checkbox" value="1"/> Reporte de recursos propios</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="icon-chart"></i> <input v-model="rep_vent_modelos" type="checkbox" value="1"/> Reporte por modelo</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="icon-chart"></i> <input v-model="rep_detalles_post" type="checkbox" value="1"/> Reporte de detalles</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="icon-chart"></i> <input v-model="rep_acumulado" type="checkbox" value="1"/> Reporte acumulado</a>
                                                    </li>
                                                    
                                                </ul>
                                    </div>
                                </div> 

                                 <!-- listado para seguimiento de pagos -->
                                <div class="col-md-4">
                                    <div class="form-group row border">
                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-laptop"></i> <input v-model="seg_pago" type="checkbox" value="1"/> Pagos Internos</a>
                                                <ul class="nav-dropdown-items" v-if="seg_pago==1">
                                                    <li class="nav-item">
                                                        <a class="nav-link" ><i class="icon-user-following"></i> <input v-model="seg_pago" type="checkbox" value="1"/> Seguimiento de pago</a>
                                                    </li>
                                                </ul>
                                    </div>
                                </div> 

                                <!-- listado para privilegios del menu Precios -->
                                <div class="col-md-4">
                                    <div class="form-group row border">
                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-money"></i> <input @click="limpiarPrecios()" v-model="precios" type="checkbox" value="1"/> Modulo Precios</a>
                                                <ul class="nav-dropdown-items" v-if="precios==1">
                                                     <li class="nav-item" >
                                                        <a class="nav-link"><i class="fa fa-usd"></i> <input v-model="agregar_sobreprecios" type="checkbox" value="1"/>Agregar sobreprecios</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="fa fa-usd"></i> <input v-model="precios_etapas" type="checkbox" value="1"/> Precios de etapa</a>
                                                    </li>
                                                    <li class="nav-item" >
                                                        <a class="nav-link"><i class="fa fa-usd"></i> <input v-model="precios_viviendas" type="checkbox" value="1"/> Precios de viviendas</a>
                                                    </li>
                                                    <li class="nav-item" >
                                                        <a class="nav-link"><i class="fa fa-plus-square-o"></i> <input v-model="sobreprecios" type="checkbox" value="1"/> Sobreprecios</a>
                                                    </li>
                                                    <li class="nav-item" >
                                                        <a class="nav-link"><i class="fa fa-shopping-bag"></i> <input v-model="paquetes" type="checkbox" value="1"/> Paquetes</a>
                                                    </li>
                                                    <li class="nav-item" >
                                                        <a class="nav-link"><i class="fa fa-percent"></i> <input v-model="promociones" type="checkbox" value="1"/> Promociones</a>
                                                    </li>
                                                </ul>
                                    </div>
                                </div> 

                                <!-- listado para privilegios del menu Postventa -->
                                <div class="col-md-4">
                                    <div class="form-group row border">
                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-handshake-o"></i> <input @click="limpiarPostventa()" v-model="postventa" type="checkbox" value="1"/> Modulo Postventa</a>
                                                <ul class="nav-dropdown-items" v-if="postventa==1">
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="fa fa-key"></i> <input v-model="entregas" type="checkbox" value="1"/> Entregas de vivienda</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="fa fa-wrench"></i> <input v-model="solic_detalles" type="checkbox" value="1"/> Solicitud de detalles</a>
                                                    </li>
                                                </ul>
                                    </div>
                                </div>

                                <!-- listado para privilegios del menu Comisiones -->
                                <div class="col-md-4">
                                    <div class="form-group row border">
                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-handshake-o"></i> <input @click="limpiarComisiones()" v-model="comisiones" type="checkbox" value="1"/> Modulo Comisiones</a>
                                                <ul class="nav-dropdown-items" v-if="comisiones==1">
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="fa fa-key"></i> <input v-model="exp_comision" type="checkbox" value="1"/> Expediente</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="fa fa-wrench"></i> <input v-model="gen_comision" type="checkbox" value="1"/> Comisiones</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="fa fa-wrench"></i> <input v-model="bono_com" type="checkbox" value="1"/> Bonos</a>
                                                    </li>
                                                </ul>
                                    </div>
                                </div>

                                <!-- listado para privilegios del menu Acceso -->
                                <div class="col-md-4">
                                    <div class="form-group row border">
                                            <a class="nav-link nav-dropdown-toggle"><i class="icon-people"></i> <input @click="limpiarAcceso()" v-model="acceso" type="checkbox" value="1"/> Modulo Acceso</a>
                                                <ul class="nav-dropdown-items" v-if="acceso==1">
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="icon-user"></i> <input v-model="usuarios" type="checkbox" value="1"/> Usuarios</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link"><i class="icon-user-following"></i> <input v-model="roles" type="checkbox" value="1"/> Roles</a>
                                                    </li>
                                                </ul>
                                    </div>
                                </div> 
                               
                                    
                            <div class="form-group">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-secondary" @click="cerrarPrivilegios()"> Cerrar </button>
                                    <button type="button" class="btn btn-primary" @click="actualizarPrivilegios()"> Guardar </button>
                                </div>
                            </div>

                                
                                
                            </div>

                 

                        </div>
                    </template>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
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
                            

                                    <!--Criterios para el listado de busqueda -->
                                  <div class="form-group row" v-if="tipoAccion > 1 && tipoAccion < 4">
                                    <label class="col-md-3 form-control-label" for="text-input">Apellidos</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="apellidos" class="form-control" placeholder="Apellidos" >
                                    </div>
                                </div>
                                   <div class="form-group row" v-if="tipoAccion > 1 && tipoAccion < 4">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" maxlength="25" v-model="nombre" class="form-control" placeholder="Nombre" >
                                    </div>
                                </div>
                                <div class="form-group row" v-if="tipoAccion > 1 && tipoAccion < 4">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de nacimiento</label>
                                    <div class="col-md-6">
                                        <input type="date" v-model="f_nacimiento" class="form-control" placeholder="Fecha de nacimiento" >
                                    </div>
                                </div>
                                     <div class="form-group row" v-if="tipoAccion > 1 && tipoAccion < 4">
                                    <label class="col-md-3 form-control-label" for="text-input">RFC</label>
                                    <div class="col-md-4">
                                        <input type="text" maxlength="10" style="text-transform:uppercase" v-model="rfc" class="form-control" placeholder="RFC" >
                                    </div>
                                    <div class="col-md-4" >
                                        <input type="text" maxlength="3" style="text-transform:uppercase" v-model="homoclave" class="form-control" placeholder="Homoclave" >
                                    </div>
                                </div>

                                <div class="form-group row" v-if="tipoAccion > 1 && tipoAccion < 4">
                                    <label class="col-md-3 form-control-label" for="text-input">Codigo Postal</label>
                                    <div class="col-md-4">
                                        <input type="text" pattern="\d*" maxlength="5" v-model="cp" v-on:keypress="isNumber($event)" @keyup="selectColonias(cp)" class="form-control" placeholder="Codigo postal" >
                                    </div>
                                </div>

                                <div class="form-group row" v-if="tipoAccion > 1 && tipoAccion < 4">
                                    <label class="col-md-3 form-control-label" for="text-input">Colonia</label>
                                    <div class="col-md-6">
                                        <select class="form-control" v-model="colonia" >
                                            <option value="0">Seleccione</option>
                                            <option v-for="colonias in arrayColonias" :key="colonias.colonia" :value="colonias.colonia" v-text="colonias.colonia"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" v-if="tipoAccion > 1 && tipoAccion < 4">
                                    <label class="col-md-3 form-control-label" for="text-input">Direccion</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="direccion" class="form-control" placeholder="Direccion" >
                                    </div>
                                </div>

                                <div class="form-group row" v-if="tipoAccion > 1 && tipoAccion < 4">
                                    <label class="col-md-3 form-control-label" for="text-input">Departamento</label>
                                    <div class="col-md-6">
                                          <select class="form-control" v-model="departamento_id" >
                                            <option value="0">Seleccione</option>
                                            <option v-for="departamentos in arrayDepartamentos" :key="departamentos.id_departamento" :value="departamentos.id_departamento" v-text="departamentos.departamento"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row" v-if="tipoAccion > 1 && tipoAccion < 4">
                                    <label class="col-md-3 form-control-label" for="text-input">Telefono</label>
                                    <div class="col-md-5">
                                        <input type="text" pattern="\d*" maxlength="10" v-on:keypress="isNumber($event)" class="form-control" v-model="telefono"  placeholder="Telefono" >
                                    </div>
                                </div>

                                <div class="form-group row" v-if="tipoAccion > 1 && tipoAccion < 4">
                                    <label class="col-md-3 form-control-label" for="text-input">Extension</label>
                                    <div class="col-md-3">
                                        <input type="text" pattern="\d*" maxlength="5" v-on:keypress="isNumber($event)" v-model="ext" class="form-control" placeholder="Extension" >
                                    </div>
                                </div>
                            
                                <div class="form-group row" v-if="tipoAccion > 1 && tipoAccion < 4">
                                    <label class="col-md-3 form-control-label" for="text-input">Celular</label>
                                    <div class="col-md-5">
                                        <input type="text" pattern="\d*" maxlength="10" v-on:keypress="isNumber($event)" v-model="celular" class="form-control" placeholder="Celular" >
                                    </div>
                                </div>

                                <div class="form-group row" v-if="tipoAccion > 1 && tipoAccion < 4">
                                    <label class="col-md-3 form-control-label" for="text-input">Correo electronico</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="email" class="form-control" placeholder="Correo electronico" >
                                    </div>
                                </div>

                                <div class="form-group row" v-if="tipoAccion == 1">
                                    <label class="col-md-3 form-control-label" for="text-input">Personal</label>
                                    <div class="col-md-6">
                                          <select class="form-control" v-model="id_persona">
                                            <option value="0">Seleccione</option>
                                            <option v-for="personal in arrayPersonas" :key="personal.personalId" :value="personal.personalId" v-text="personal.nombre"></option>
                                        </select>
                                    </div>
                                </div>


                                <div class="form-group row" v-if="tipoAccion != 4">
                                    <label class="col-md-3 form-control-label" for="text-input">Usuario</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="usuario" class="form-control" placeholder="Usuario" >
                                    </div>
                                </div>

                                
                                <div class="form-group row" v-if="tipoAccion != 4">
                                    <label class="col-md-3 form-control-label" for="text-input">Contraseña</label>
                                    <div class="col-md-9">
                                        <input type="password" v-model="password" class="form-control" placeholder="Contraseña" >
                                    </div>
                                </div>

                                <div class="form-group row" v-if="tipoAccion != 4">
                                    <label class="col-md-3 form-control-label" for="text-input">Rol</label>
                                    <div class="col-md-6">
                                       <select class="form-control" v-model="rol_id" >
                                            <option value="0">Seleccione</option>
                                            <option v-for="roles in arrayRoles" :key="roles.id" :value="roles.id" v-text="roles.nombre"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row" v-if="rol_id==2 && tipoAccion != 4">
                                    <label class="col-md-3 form-control-label" for="text-input">Tipo</label>
                                    <div class="col-md-6">
                                       <select class="form-control" v-model="tipo_vendedor" >
                                            <option value="0">Interno</option>
                                            <option value="1">Externo</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row" v-if="tipo_vendedor==1 && rol_id==2 && tipoAccion != 4">
                                    <label class="col-md-3 form-control-label" for="text-input">Inmobiliaria</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="inmobiliaria" class="form-control" placeholder="Inmobiliaria" >
                                    </div>
                                </div>

                                <div class="form-group row" v-if="tipo_vendedor==1 && rol_id==2 && tipoAccion != 4">
                                    <label class="col-md-3 form-control-label" for="text-input">Esquema</label>
                                    <div class="col-md-2">
                                        <select class="form-control" v-model="esquema" >
                                            <option value=2>2%</option>
                                            <option value=3>3%</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row" v-if="tipo_vendedor==1 && rol_id==2 && tipoAccion != 4">
                                    <label class="col-md-3 form-control-label" for="text-input">Retencion</label>
                                    <div class="col-md-2">
                                        <select class="form-control" v-model="retencion" >
                                            <option value=0>No</option>
                                            <option value=1>Si</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row" v-if="tipo_vendedor==1 && rol_id==2 && tipoAccion != 4">
                                    <label class="col-md-3 form-control-label" for="text-input">ISR</label>
                                    <div class="col-md-2">
                                        <select class="form-control" v-model="isr" >
                                            <option value=0>No</option>
                                            <option value=1>Si</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row" v-if="tipoAccion > 1 && tipoAccion < 4">
                                    <label class="col-md-3 form-control-label" for="text-input">Estatus</label>
                                    <div class="col-md-5">
                                        <select class="form-control" v-model="activo" >
                                            <option value="1">Activo</option>
                                            <option value="0"> Inactivo</option>
                                        </select>
                                        <!--<input type="text" v-model="estado" class="form-control" placeholder="Estado">-->
                                    </div>
                                </div>

                                 <div v-if="tipoAccion == 4" class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Gerentes</label>
                                    <div class="col-md-6">
                                       <select class="form-control" v-model="supervisor_id" >
                                            <option value="0">Seleccione</option>
                                            <option v-for="gerentes in arrayGerentes" :key="gerentes.id" :value="gerentes.id" v-text="gerentes.nombre+' '+gerentes.apellidos"></option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Div para mostrar los errores que mande validerPersonal -->
                                <div v-show="errorPersonal" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjPersonal" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==3" class="btn btn-primary" @click="registrarPersonal()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarPersonal()">Actualizar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="asignarUsuario()">Asignar</button>
                            <button type="button" v-if="tipoAccion==4" class="btn btn-primary" @click="asignarGerente()">Asignar</button>
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
                privilegios:0,
                proceso:false,
                id:0,
                id_persona:0,
                usuario: '',
                password: '',
                condicion: 1,
                rol_id: 0,
                departamento_id : 0,
                empresa_id : 1,
                nombre : '',
                apellidos : '',
                f_nacimiento: '',
                rfc : '',
                homoclave : '',
                colonia : '',
                direccion : '',
                cp: 0,
                telefono: 0,
                ext: 0,
                celular: 0,
                email: '',
                esquema: 2,
                activo: 1, 
                isr : 0,
                retencion : 0,
                tipo_vendedor:0,
                inmobiliaria:'',
                supervisor_id: 0,
                arrayPersonal : [],
                arrayPersonas : [],
                arrayGerentes: [],
                arrayDepartamentos: [],
                arrayRoles: [],
                arrayEmpresas: [],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                errorPersonal : 0,
                errorMostrarMsjPersonal : [],

                //privilegios
                administracion:0,
                desarrollo:0,
                precios:0,
                obra:0,
                ventas:0,
                acceso:0,
                reportes:0,
                saldo:0,
                gestoria:0,
                postventa:0,
                comisiones:0,

                    //Administracion
                    departamentos:0,
                    personas:0,
                    empresas:0,
                    medios_public:0,
                    lugares_contacto:0,
                    servicios:0,
                    inst_financiamiento:0,
                    tipos_credito:0,
                    asig_servicios:0,
                    mis_asesores:0,
                    cuenta:0,
                    notaria: 0,
                    proveedores : 0,


                    //Desarrollo
                    fraccionamiento:0,
                    etapas:0,
                    modelos:0,
                    lotes:0,
                    asign_modelos:0,
                    licencias:0,
                    acta_terminacion:0,
                    p_etapa:0,
                    descarga_actas:0,
                    seg_ruv:0,
                    ruv:0,
                   
                    //Pago interno
                    seg_pago:0,

                    //Precios
                    agregar_sobreprecios: 0,
                    precios_etapas:0,
                    precios_viviendas:0,
                    sobreprecios:0,
                    paquetes:0,
                    promociones:0,

                    //Obra
                    contratistas:0,
                    ini_obra:0,
                    aviso_obra:0,
                    partidas:0,
                    avance:0,
                    estimaciones:0,

                    //Ventas
                    lotes_disp:0,
                    mis_prospectos:0,
                    simulacion_credito:0,
                    hist_simulaciones:0,
                    hist_creditos:0,
                    contratos:0,
                    docs: 0,
                    equipamientos : 0,

                    //Saldos
                    edo_cuenta:0,
                    depositos:0,
                    gastos_admn:0,
                    cobro_credito:0,
                    dev_exc :0,
                    dev_cancel :0,
                    facturas:0,
                    ingresos_concretania:0,

                    //Gestoria
                    expediente:0,
                    asig_gestor:0,
                    seg_tramite:0,
                    avaluos:0,
                    bonos_rec:0,

                    //Postventa
                    entregas:0,
                    solic_detalles:0,

                    //Comisiones
                    exp_comision:0,
                    gen_comision:0,
                    bono_com:0,

                    //Acceso
                    usuarios:0,
                    roles:0,

                    //Reportes
                    mejora:0,
                    rep_proy:0,
                    rep_publi:0,
                    inventario:0,
                    rep_venta_canc:0,
                    rep_asesores:0,
                    rep_ini_term_ventas:0,
                    rep_recursos_propios:0,
                    rep_vent_modelos:0,
                    rep_detalles_post:0,
                    rep_acumulado:0,

                pagination : {
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
            }
        },
        methods : {
            /**Metodo para mostrar los registros */
            listarPersonal(page, buscar, criterio){
                let me = this;
                var url = '/usuarios?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPersonal = respuesta.personas.data;
                    me.pagination = respuesta.pagination;
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
            selectDepartamento(){
                let me = this;
                me.arrayDepartamentos=[];
                var url = '/select_departamentos';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayDepartamentos = respuesta.departamentos;
                })
                .catch(function (error) {
                    console.log(error);
                });
              
            },
            selectRoles(){
                let me = this;
                me.arrayRoles=[];
                var url = '/select_roles';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayRoles = respuesta.roles;
                })
                .catch(function (error) {
                    console.log(error);
                });
              
            },
            
            selectGerentes(){
                let me = this;
                me.arrayGerentes=[];
                var url = '/select_gerentes';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayGerentes = respuesta.gerentes;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            limpiarAdministracion(){
                //Administracion
                this.departamentos=0;
                this.personas=0;
                this.empresas=0;
                this.medios_public=0;
                this.lugares_contacto=0;
                this.servicios=0;
                this.inst_financiamiento=0;
                this.tipos_credito=0;
                this.asig_servicios=0;
                this.mis_asesores=0;
                this.cuenta = 0;
                this.notaria=0;
                this.proveedores = 0;
            },
            limpiarDesarrollo(){
                //Desarrollo
                this.fraccionamiento=0;
                this.etapas=0;
                this.modelos=0;
                this.lotes=0;
                this.asign_modelos=0;
                this.licencias=0;
                this.acta_terminacion=0;
                this.p_etapa=0;
                this.descarga_actas = 0;
                this.seg_ruv = 0;
                this.ruv = 0;
               
            },
            limpiarPrecios(){
                 //Precios
                this.agregar_sobreprecios=0; 
                this.precios_etapas=0;
                this.precios_viviendas=0;
                this.sobreprecios=0;
                this.paquetes=0;
                this.promociones=0;
            },
            limpiarObra(){
                 //Obra
                this.contratistas=0;
                this.ini_obra=0;
                this.aviso_obra=0;
                this.partidas=0;
                this.avance=0;
                this.equipamientos=0;
                this.entregas=0;
                this.estimaciones=0;
            },
            limpiarVentas(){
                 //Ventas
                this.lotes_disp=0;
                this.mis_prospectos=0;
                this.simulacion_credito=0;
                this.hist_simulaciones=0;
                this.hist_creditos=0;
                this.contratos=0;
                this.docs=0;
                this.equipamientos=0;
            },
            limpiarComisiones(){
                this.bono_com=0;
                this.exp_comision=0;
                this.gen_comision=0;
            },

            limpiarGestoria(){
                //Gestoria
                this.expediente=0;
                this.asig_gestor=0;
                this.seg_tramite=0;
                this.avaluos=0;
                this.bonos_rec=0;
            },
            limpiarPostventa(){
                //Gestoria
                this.entregas=0;
                this.solic_detalles=0;
            },

            limpiarSaldo(){
                 //Saldos
                this.edo_cuenta=0;
                this.depositos=0;
                this.gastos_admn=0;
                this.cobro_credito=0;
                this.dev_exc=0;
                this.dev_cancel=0;
                this.facturas=0;
                this.ingresos_concretania=0;
            },
            limpiarAcceso(){
                 //Acceso
                this.usuarios=0;
                this.roles=0;
            },
            limpiarReportes(){
                 //Reportes
                this.mejora=0;
                this.rep_proy=0;
                this.rep_publi=0;
                this.inventario=0;
                this.rep_venta_canc = 0;
                this.rep_asesores = 0;
                this.rep_ini_term_ventas = 0;
                this.rep_recursos_propios = 0;
                this.rep_vent_modelos = 0;
                this.rep_detalles_post = 0;
                this.rep_acumulado = 0;
            },

            selectPersonas(){
                let me = this;
                me.arrayPersonas=[];
                var url = '/select_personas_sin_user';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPersonas = respuesta.personas;
                })
                .catch(function (error) {
                    console.log(error);
                });
              
            },
            getPrivilegios(id){
                let me = this;
                var usuarios=[];
                this.id=id;
                var url = '/usuario/privilegios?id=' + id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    usuarios = respuesta.privilegios;

                    //privilegios
                    me.administracion=usuarios[0].administracion;
                    me.desarrollo=usuarios[0].desarrollo;
                    me.precios=usuarios[0].precios;
                    me.obra=usuarios[0].obra;
                    me.ventas=usuarios[0].ventas;
                    me.acceso=usuarios[0].acceso;
                    me.reportes=usuarios[0].reportes;
                    me.saldo = usuarios[0].saldo;
                    me.gestoria = usuarios[0].gestoria;
                    me.postventa = usuarios[0].postventa;
                    me.comisiones = usuarios[0].comisiones;

                    //Administracion
                    me.departamentos=usuarios[0].departamentos;
                    me.personas=usuarios[0].personas;
                    me.empresas=usuarios[0].empresas;
                    me.medios_public=usuarios[0].medios_public;
                    me.lugares_contacto=usuarios[0].lugares_contacto;
                    me.servicios=usuarios[0].servicios;
                    me.inst_financiamiento=usuarios[0].inst_financiamiento;
                    me.tipos_credito=usuarios[0].tipos_credito;
                    me.asig_servicios=usuarios[0].asig_servicios;
                    me.mis_asesores=usuarios[0].mis_asesores;
                    me.cuenta = usuarios[0].cuenta;
                    me.notaria = usuarios[0].notaria;
                    me.proveedores = usuarios[0].proveedores;

                    //Desarrollo
                    me.fraccionamiento=usuarios[0].fraccionamiento;
                    me.etapas=usuarios[0].etapas;
                    me.modelos=usuarios[0].modelos;
                    me.lotes=usuarios[0].lotes;
                    me.asign_modelos=usuarios[0].asign_modelos;
                    me.licencias=usuarios[0].licencias;
                    me.acta_terminacion=usuarios[0].acta_terminacion;
                    me.p_etapa=usuarios[0].p_etapa;
                    me.descarga_actas = usuarios[0].descarga_actas;
                    me.ruv = usuarios[0].ruv;
                    me.seg_ruv = usuarios[0].seg_ruv;

                    //Precios
                    me.agregar_sobreprecios = usuarios[0].agregar_sobreprecios;
                    me.precios_etapas=usuarios[0].precios_etapas;
                    me.precios_viviendas = usuarios[0].precios_viviendas;
                    me.sobreprecios=usuarios[0].sobreprecios;
                    me.paquetes=usuarios[0].paquetes;
                    me.promociones=usuarios[0].promociones;

                    //Obra
                    me.contratistas=usuarios[0].contratistas;
                    me.ini_obra=usuarios[0].ini_obra;
                    me.aviso_obra=usuarios[0].aviso_obra;
                    me.partidas=usuarios[0].partidas;
                    me.avance=usuarios[0].avance;
                    me.estimaciones=usuarios[0].estimaciones;

                    //Ventas
                    me.lotes_disp=usuarios[0].lotes_disp;
                    me.mis_prospectos=usuarios[0].mis_prospectos;
                    me.simulacion_credito=usuarios[0].simulacion_credito;
                    me.hist_simulaciones=usuarios[0].hist_simulaciones;
                    me.hist_creditos=usuarios[0].hist_creditos;
                    me.contratos=usuarios[0].contratos;
                    me.docs=usuarios[0].docs;
                    me.equipamientos = usuarios[0].equipamientos;

                    //Saldos
                    me.edo_cuenta = usuarios[0].edo_cuenta;
                    me.depositos = usuarios[0].depositos;
                    me.gastos_admn = usuarios[0].gastos_admn;
                    me.cobro_credito = usuarios[0].cobro_credito;
                    me.dev_cancel = usuarios[0].dev_cancel;
                    me.dev_exc = usuarios[0].dev_exc;
                    me.facturas = usuarios[0].facturas;
                    me.ingresos_concretania = usuarios[0].ingresos_concretania;

                    //Gestoria
                    me.expediente = usuarios[0].expediente;
                    me.asig_gestor = usuarios[0].asig_gestor;
                    me.seg_tramite = usuarios[0].seg_tramite;
                    me.avaluos = usuarios[0].avaluos;
                    me.bonos_rec = usuarios[0].bonos_rec;

                    //Postventa
                    me.entregas = usuarios[0].entregas;
                    me.solic_detalles = usuarios[0].solic_detalles;

                    //Comisiones
                    me.bono_com = usuarios[0].bono_com;
                    me.exp_comision = usuarios[0].exp_comision;
                    me.gen_comision = usuarios[0].gen_comision;

                    //Acceso
                    me.usuarios=usuarios[0].usuarios;
                    me.roles=usuarios[0].roles;
                    me.seg_pago = usuarios[0].seg_pago;

                    //Reportes
                    me.mejora=usuarios[0].mejora;
                    me.rep_proy = usuarios[0].rep_proy;
                    me.rep_publi = usuarios[0].rep_publi;
                    me.inventario = usuarios[0].inventario;
                    me.rep_venta_canc = usuarios[0].rep_venta_canc;
                    me.rep_asesores = usuarios[0].rep_asesores;
                    me.rep_ini_term_ventas = usuarios[0].rep_ini_term_ventas;
                    me.rep_recursos_propios = usuarios[0].rep_recursos_propios;
                    me.rep_vent_modelos = usuarios[0].rep_vent_modelos;
                    me.rep_detalles_post = usuarios[0].rep_detalles_post;
                    me.rep_acumulado = usuarios[0].rep_acumulado;

                    me.rol_id = usuarios[0].rol_id;

                    me.privilegios=1;
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
            asignarUsuario(){
                if(this.validarUsuario() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }
                this.proceso=true;
                  let me = this;
                //Con axios se llama el metodo store de PersonalController
                axios.post('/usuarios/asignar',{
                    'id_persona' : this.id_persona,
                    'usuario': this.usuario,
                    'password': this.password,
                    'rol_id' : this.rol_id,
                    'condicion':this.condicion 
                    
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarPersonal(1,'','Personal'); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Usuario y rol asignado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                
                    console.log(error);
                    me.proceso=false;
                });
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
                    'inmobiliaria':this.inmobiliaria,
                    'esquema':this.esquema,
                    'retencion':this.retencion,
                    'isr':this.isr
                    
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
                    'inmobiliaria':this.inmobiliaria,
                    'esquema':this.esquema,
                    'retencion':this.retencion,
                    'isr':this.isr
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
            asignarGerente(){

                let me = this;
                //Con axios se llama el metodo update de PersonalController
                axios.put('/usuarios/asignar/gerente',{
                    'supervisor_id': this.supervisor_id,
                    'id' : this.id,
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
            actualizarPrivilegios(){
                let me = this;
                //Con axios se llama el metodo update de PersonalController
                axios.put('/usuarios/act_privilegios',{
                    'id':this.id,
                    'administracion':this.administracion,
                    'desarrollo':this.desarrollo,
                    'precios':this.precios,
                    'obra':this.obra,
                    'ventas':this.ventas,
                    'acceso':this.acceso,
                    'reportes':this.reportes,
                    'saldo':this.saldo,
                    'gestoria':this.gestoria,
                    'postventa':this.postventa,
                    'comisiones':this.comisiones,
                        //Administracion
                    'departamentos':this.departamentos,
                    'personas':this.personas,
                    'empresas':this.empresas,
                    'medios_public':this.medios_public,
                    'lugares_contacto':this.lugares_contacto,
                    'servicios':this.servicios,
                    'inst_financiamiento':this.inst_financiamiento,
                    'tipos_credito':this.tipos_credito,
                    'asig_servicios':this.asig_servicios,
                    'mis_asesores':this.mis_asesores,
                    'cuenta':this.cuenta,
                    'notaria':this.notaria,
                    'proveedores':this.proveedores,
                        //Desarrollo
                    'fraccionamiento':this.fraccionamiento,
                    'etapas':this.etapas,
                    'modelos':this.modelos,
                    'lotes':this.lotes,
                    'asign_modelos':this.asign_modelos,
                    'licencias':this.licencias,
                    'acta_terminacion':this.acta_terminacion,
                    'p_etapa':this.p_etapa,
                    'descarga_actas':this.descarga_actas,
                    'ruv':this.ruv,
                    'seg_ruv':this.seg_ruv,
                   
                        //Precios
                    'agregar_sobreprecios':this.agregar_sobreprecios,
                    'precios_etapas':this.precios_etapas,
                    'precios_viviendas':this.precios_viviendas,
                    'sobreprecios':this.sobreprecios,
                    'paquetes':this.paquetes,
                    'promociones':this.promociones,
                        //Obra
                    'contratistas':this.contratistas,
                    'ini_obra':this.ini_obra,
                    'aviso_obra':this.aviso_obra,
                    'partidas':this.partidas,
                    'avance':this.avance,
                    'estimaciones':this.estimaciones,
                        //Ventas
                    'lotes_disp':this.lotes_disp,
                    'mis_prospectos':this.mis_prospectos,
                    'simulacion_credito':this.simulacion_credito,
                    'hist_simulaciones':this.hist_simulaciones,
                    'hist_creditos':this.hist_creditos,
                    'contratos':this.contratos,
                    'docs':this.docs,
                    'equipamientos':this.equipamientos,
                        //Saldo
                    'edo_cuenta':this.edo_cuenta,
                    'depositos':this.depositos,
                    'gastos_admn':this.gastos_admn,
                    'cobro_credito':this.cobro_credito,
                    'dev_cancel':this.dev_cancel,
                    'dev_exc':this.dev_exc,
                    'facturas':this.facturas,
                    'ingresos_concretania':this.ingresos_concretania,
                        //Gestoria
                    'expediente':this.expediente,
                    'asig_gestor':this.asig_gestor,
                    'seg_tramite':this.seg_tramite,
                    'avaluos':this.avaluos,
                    'bonos_rec':this.bonos_rec,
                        //Postventa
                    'entregas':this.entregas,
                    'solic_detalles':this.solic_detalles,
                        //Comisiones
                    'gen_comision':this.gen_comision,
                    'bono_com':this.bono_com,
                    'exp_comision':this.exp_comision,
                        //Acceso
                    'usuarios':this.usuarios,
                    'roles':this.roles,
                    'seg_pago':this.seg_pago,
                        //Reportes
                    'mejora':this.mejora,
                    'rep_proy':this.rep_proy,
                    'rep_publi':this.rep_publi,
                    'inventario':this.inventario,
                    'rep_venta_canc':this.rep_venta_canc,
                    'rep_asesores':this.rep_asesores,
                    'rep_ini_term_ventas':this.rep_ini_term_ventas,
                    'rep_recursos_propios':this.rep_recursos_propios,
                    'rep_vent_modelos':this.rep_vent_modelos,
                    'rep_detalles_post':this.rep_detalles_post,
                    'rep_acumulado' : this.rep_acumulado

                }).then(function (response){
                    me.listarPersonal(1,'','nombre');
                    me.cerrarPrivilegios();
                    //window.alert("Cambios guardados correctamente");
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Privilegios asignados correctamente',
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
            validarUsuario(){
                this.errorPersonal=0;
                this.errorMostrarMsjPersonal=[];

                if(!this.id_persona)
                    this.errorMostrarMsjPersonal.push("Seleccionar la persona");
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
            cerrarPrivilegios(){
                let me = this;
                //privilegios
                me.administracion=0;
                me.desarrollo=0;
                me.precios=0;
                me.obra=0;
                me.ventas=0;
                me.acceso=0;
                me.reportes=0;
                me.id=0;

                    //Administracion
                me.departamentos=0;
                me.personas=0;
                me.empresas=0;
                me.medios_public=0;
                me.lugares_contacto=0;
                me.servicios=0;
                me.inst_financiamiento=0;
                me.tipos_credito=0;
                me.asig_servicios=0;
                me.mis_asesores=0;
                me.cuenta = 0;
                me.notaria = 0;
                me.proveedores = 0;


                    //Desarrollo
                me.fraccionamiento=0;
                me.etapas=0;
                me.modelos=0;
                me.lotes=0;
                me.asign_modelos=0;
                me.licencias=0;
                me.acta_terminacion=0;
                me.p_etapa=0;
                me.descarga_actas = 0;
               

                    //Precios
                me.agregar_sobreprecios=0;
                me.precios_etapas=0;
                me.precios_viviendas=0;
                me.sobreprecios=0;
                me.paquetes=0;
                me.promociones=0;

                    //Obra
                me.contratistas=0;
                me.ini_obra=0;
                me.aviso_obra=0;
                me.partidas=0;
                me.avance=0;
                me.estimaciones=0;

                    //Ventas
                me.lotes_disp=0;
                me.mis_prospectos=0;
                me.simulacion_credito=0;
                me.hist_simulaciones=0;
                me.hist_creditos=0;
                me.contratos=0;
                me.docs=0;
                me.equipamientos = 0;

                    //Acceso
                me.usuarios=0;
                me.roles=0;

                    //Reportes
                me.mejora=0;
                me.rep_proy = 0;
                me.rep_publi = 0;
                me.rep_vent_modelos =0;

                me.rol_id =0;

                me.privilegios=0;
            },
            cerrarModal(){
                this.modal = 0;
                this.departamento_id = '';
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
                this.activo='';
                this.id_persona = 0;
                this.password = '';
                this.usuario = '';
                this.condicion = 1;
                this.errorPersonal = 0;
                this.errorMostrarMsjPersonal = [];
                this.inmobiliaria='',
                this.tipo_vendedor=0
            

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
                                this.departamento_id = '0',
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
                                this.rol_id=0;
                                this.inmobiliaria='';
                                this.tipo_vendedor=0;
                                this.esquema=2;
                                this.retencion =0;
                                this.isr = 0;

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
                                this.inmobiliaria=data['inmobiliaria'];
                                this.tipo_vendedor=data['tipo'];
                                this.esquema = data['esquema'];
                                this.retencion = data['retencion'];
                                this.isr = data['isr'];
                                break;
                            }
                            case 'Asignar':
                            {
                                //console.log(data);
                                this.modal =1;
                                this.tituloModal='Asignar rol';
                                this.id_persona = 0;
                                this.usuario='';
                                this.password='';
                                this.condicion=1;
                                this.rol_id=0;
                                this.tipoAccion = 1;
                                break;
                            }

                             case 'asignarGerente':
                            {
                                //console.log(data);
                                this.modal =1;
                                this.tituloModal='Asignar asesor a gerente';
                                this.id=data['id'];
                                this.tipoAccion = 4;
                                break;
                            }
                        }
                    }
                }
                this.selectDepartamento();
                this.selectEmpresa();
                this.selectColonias(this.cp);
                this.selectRoles();
                this.selectPersonas();
                this.selectGerentes();
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

input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
  -webkit-appearance: none; 
   margin: 0;  
} 
</style>