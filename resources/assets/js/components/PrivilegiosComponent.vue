<template>
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
                    <a class="nav-link nav-dropdown-toggle"><i class="fa fa-calendar"></i><input v-model="calendario" type="checkbox" value="1"/> Calendario de actividades </a>
                    <a class="nav-link nav-dropdown-toggle"><i class="fa fa-commenting"></i><input v-model="notifications" type="checkbox" value="1"/> Centro de notificaciones </a>
                    <a class="nav-link nav-dropdown-toggle"><i class="icon-energy"></i><input @click="limpiarAdministracion()" v-model="administracion" type="checkbox" value="1"/> Modulo Administración </a>
                    <ul v-if="administracion==1" class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-link"><i class="fa fa-car"></i> <input v-model="vehiculos" type="checkbox" value="1"/> Vehiculos</a>
                        </li>
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
                        <li class="nav-item">
                            <a class="nav-link"><i class="fa fa-industry"></i> <input v-model="digital_campain" type="checkbox" value="1"/> Campañas digitales</a>
                        </li>

                    </ul>
                </div>
            </div>

            <!-- RH -->
            <div class="col-md-4">
                <div class="form-group row border">
                    <a class="nav-link nav-dropdown-toggle"><i class="icon-people"></i> Modulo RH</a>
                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-link"><i class="fa fa-car"></i> <input v-model="mant_vehiculos" type="checkbox" value="1"/> Mantenimiento de vehiculos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link">
                                <i class="fa fa-car"></i><i class="icon-energy"></i><input v-model="admin_mant_vehiculos" type="checkbox" value="1"/> Admin. Mant. De vehiculos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"><i class="fa fa-suitcase"></i> <input v-model="fondo_ahorro" type="checkbox" value="1"/> Fondo de ahorro</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"><i class="fa fa-bank"></i> <input v-model="fondo_pension" type="checkbox" value="1"/> Fondo de pensión</a>
                        </li>
                            <li class="nav-item">
                            <a class="nav-link"><i class="fa fa-money"></i><input v-model="prestamos_personales" type="checkbox" value="1"/>  Prestamos personales. </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Oficina -->
            <div class="col-md-4">
                <div class="form-group row border">
                    <a class="nav-link nav-dropdown-toggle"><i class="fa fa-building-o"></i> Oficina</a>
                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-link"><i class="fa fa-coffee"></i> <input v-model="inventarios" type="checkbox" value="1"/> Inventarios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link">
                                <i class="fa fa-address-card"></i><input v-model="prov_inventarios" type="checkbox" value="1"/> Proveedores
                            </a>
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
                        <li class="nav-item">
                            <a class="nav-link"><i class="fa fa-vcard"></i> <input v-model="digital_lead" type="checkbox" value="1"/> Digital leads</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"><i class="fa fa-vcard"></i> <input v-model="reubicaciones" type="checkbox" value="1"/> Reubicaciones</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- listado para privilegios del menu Rentas -->
            <div class="col-md-4">
                <div class="form-group row border">
                    <a class="nav-link nav-dropdown-toggle"><i class="fa fa-building-o"></i> <input @click="limpiarRentas()" v-model="rentas" type="checkbox" value="1"/> Modulo Rentas</a>
                    <ul class="nav-dropdown-items" v-if="rentas==1">
                        <li class="nav-item">
                            <a class="nav-link"><i class="fa fa-book"></i> <input v-model="admin_rentas" type="checkbox" value="1"/> Admin Rentas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"><i class="fa fa-book"></i> <input v-model="pagos_rentas" type="checkbox" value="1"/> Edo Cuenta</a>
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
                        <li class="nav-item">
                            <a class="nav-link" ><i class="icon-reload"></i> <input v-model="dev_virtual" type="checkbox" value="1"/> Devoluciones virtuales</a>
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
                        <li class="nav-item">
                            <a class="nav-link" ><i class="fa fa-usd"></i> <input v-model="int_cobros" type="checkbox" value="1"/> Integración de cobros</a>
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
                            <a class="nav-link"><i class="fa fa-calculator"></i> <input v-model="estimaciones" type="checkbox" value="1"/> Estimaciones</a>
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
                            <a class="nav-link"><i class="icon-chart"></i> <input v-model="rep_entregas" type="checkbox" value="1"/> Reporte de Entregas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"><i class="icon-chart"></i> <input v-model="rep_acumulado" type="checkbox" value="1"/> Reporte de expedientes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"><i class="icon-chart"></i> <input v-model="rep_ingresos" type="checkbox" value="1"/> Reporte de ingresos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"><i class="icon-chart"></i> <input v-model="rep_escrituras" type="checkbox" value="1"/> Reporte de escrituras</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"><i class="icon-chart"></i> <input v-model="rep_leads" type="checkbox" value="1"/> Reporte Digital Leads</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"><i class="icon-chart"></i> <input v-model="rep_empresas" type="checkbox" value="1"/> Reporte Empresas</a>
                        </li>
                    </ul>
                </div>
            </div> <!-- listado para privilegios del menu Cotizador de lotes -->
            <div class="col-md-4">
                <div class="form-group row border">
                <a class="nav-link nav-dropdown-toggle"><i class="icon-people"></i> Modulo Cotizador de lotes</a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        <a class="nav-link"><i class="icon-book-open"></i> <input v-model="calc_lotes" type="checkbox" value="1"/> Calculadora de lotes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"><i class="icon-book-open"></i> <input v-model="edit_cotizacion" type="checkbox" value="1"/> Editar cotización</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"><i class="icon-book-open"></i> <input v-model="opc_cotizador" type="checkbox" value="1"/> Opciones de cotizador</a>
                    </li>
                </ul>
                </div>
            </div>

            <!--Creditos Puente -->
            <div class="col-md-4">
                <div class="form-group row border">
                    <a class="nav-link nav-dropdown-toggle"><i class="fa fa-bank"></i> Modulo Creditos Puente</a>
                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-link"><i class="icon-book-open"></i> <input v-model="bases" type="checkbox" value="1"/> Base presupuestal</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"><i class="icon-book-open"></i> <input v-model="solic_credito_puente" type="checkbox" value="1"/> Solicitar Crédito</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"><i class="icon-book-open"></i> <input v-model="seg_cp" type="checkbox" value="1"/> Créditos Puente</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"><i class="icon-book-open"></i> <input v-model="edo_cta_bancrea" type="checkbox" value="1"/> Estados de Cuenta</a>
                        </li>
                    </ul>
                </div>
            </div>

                <!-- listado para seguimiento de pagos -->
            <div class="col-md-4">
                <div class="form-group row border">
                    <a class="nav-link nav-dropdown-toggle"><i class="fa fa-laptop"></i>Solic. Pagos</a>
                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-link" ><i class="icon-user-following"></i> <input v-model="seg_pago" type="checkbox" value="1"/>Encargado Pagos</a>
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
<script>
export default {
    props:{
        userId:{
            type: Number,
            required: true
        }
    },
    data() {
        return {
            id: 0,
            //privilegios
            administracion:0,
            desarrollo:0,
            precios:0,
            obra:0,
            ventas:0,
            rentas:0,
            acceso:0,
            reportes:0,
            saldo:0,
            gestoria:0,
            postventa:0,
            comisiones:0,
            calendario:0,
            notifications:0,

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
            digital_campain : 0,

            //RH
            vehiculos : 0,
            mant_vehiculos : 0,
            admin_mant_vehiculos : 0,
            prestamos_personales:0,
            fondo_ahorro : 0,
            fondo_pension : 0,

            //Oficina
            inventarios : 0,
            prov_inventarios : 0,


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
            digital_lead : 0,
            reubicaciones : 0,

            //Rentas
            admin_rentas : 0,
            pagos_rentas : 0,

            //Saldos
            edo_cuenta:0,
            depositos:0,
            gastos_admn:0,
            cobro_credito:0,
            dev_exc :0,
            dev_cancel :0,
            facturas:0,
            ingresos_concretania:0,
            dev_virtual:0,

            //Gestoria
            expediente:0,
            asig_gestor:0,
            seg_tramite:0,
            avaluos:0,
            bonos_rec:0,
            int_cobros:0,

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

            //Calculador de lotes
            calc_lotes:0,
            edit_cotizacion:0,
            opc_cotizador:0,

            //Creditos Puente
            bases:0,
            solic_credito_puente:0,
            seg_cp:0,
            edo_cta_bancrea:0,

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
            rep_ingresos:0,
            rep_escrituras:0,
            rep_leads:0,
            rep_entregas:0,
            rep_empresas : 0,

        }
    },
    methods: {
        cerrarPrivilegios(){
            let me = this;
            me.limpiarAcceso()
            me.limpiarAdministracion()
            me.limpiarDesarrollo()
            me.limpiarComisiones()
            me.limpiarCreditosPuente()
            me.limpiarGestoria()
            me.limpiarObra()
            me.limpiarPostventa()
            me.limpiarPrecios()
            me.limpiarRentas()
            me.limpiarReportes()
            me.limpiarSaldo()
            me.limpiarVentas()
            me.$emit('close')
        },
        limpiarAdministracion(){
            //Administracion
            this.departamentos=0;
            this.vehiculos=0;
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
            this.digital_campain = 0;
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
            this.calc_lotes=0;
            this.edit_cotizacion=0;
            this.opc_cotizador=0;
            this.digital_lead = 0;
            this.reubicaciones = 0;
        },
        limpiarRentas(){
            this.admin_rentas=0;
            this.pagos_rentas=0;
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
            this.dev_virtual = 0;
        },
        limpiarCreditosPuente(){
            this.bases = 0;
            this.solic_credito_puente = 0;
            this.seg_cp = 0;
            this.edo_cta_bancrea = 0;
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
            this.rep_ingresos = 0;
            this.rep_escrituras = 0;
            this.rep_leads = 0;
            this.rep_entregas = 0;
            this.rep_empresas = 0;
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
                'calendario':this.calendario,
                'notifications':this.notifications,
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
                'digital_campain':this.digital_campain,
                    //RH
                'vehiculos':this.vehiculos,
                'mant_vehiculos':this.mant_vehiculos,
                'admin_mant_vehiculos':this.admin_mant_vehiculos,
                'prestamos_personales':this.prestamos_personales,
                'fondo_ahorro': this.fondo_ahorro,
                'fondo_pension': this.fondo_pension,
                    //Oficina
                'inventarios' : this.inventarios,
                'prov_inventarios' : this.prov_inventarios,
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
                'digital_lead':this.digital_lead,
                'reubicaciones':this.reubicaciones,
                    //Rentas
                'admin_rentas':this.admin_rentas,
                'pagos_rentas':this.pagos_rentas,
                    //Cotizador de lotes
                'calc_lotes':this.calc_lotes,
                'edit_cotizacion':this.edit_cotizacion,
                'opc_cotizador':this.opc_cotizador,
                    //Creditos Puente
                'bases':this.bases,
                'solic_credito_puente':this.solic_credito_puente,
                'seg_cp':this.seg_cp,
                'edo_cta_bancrea':this.edo_cta_bancrea,
                    //Saldo
                'edo_cuenta':this.edo_cuenta,
                'depositos':this.depositos,
                'gastos_admn':this.gastos_admn,
                'cobro_credito':this.cobro_credito,
                'dev_cancel':this.dev_cancel,
                'dev_exc':this.dev_exc,
                'facturas':this.facturas,
                'ingresos_concretania':this.ingresos_concretania,
                'dev_virtual':this.dev_virtual,
                    //Gestoria
                'expediente':this.expediente,
                'asig_gestor':this.asig_gestor,
                'seg_tramite':this.seg_tramite,
                'avaluos':this.avaluos,
                'bonos_rec':this.bonos_rec,
                'int_cobros':this.int_cobros,
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
                'rep_acumulado' : this.rep_acumulado,
                'rep_ingresos' : this.rep_ingresos,
                'rep_escrituras' : this.rep_escrituras,
                'rep_leads' : this.rep_leads,
                'rep_entregas' : this.rep_entregas,
                'rep_empresas' : this.rep_empresas

            }).then(function (response){
                me.$emit('close')
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
        getPrivilegios(id){
            let me = this;
            let usuarios=[];
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
                me.calendario = usuarios[0].calendario;
                me.notifications = usuarios[0].notifications;

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
                me.digital_campain = usuarios[0].digital_campain;

                //RH
                me.vehiculos = usuarios[0].vehiculos;
                me.mant_vehiculos = usuarios[0].mant_vehiculos;
                me.admin_mant_vehiculos = usuarios[0].admin_mant_vehiculos;
                me.prestamos_personales = usuarios[0].prestamos_personales;
                me.fondo_ahorro = usuarios[0].fondo_ahorro;
                me.fondo_pension = usuarios[0].fondo_pension;

                // Oficina
                me.inventarios = usuarios[0].inventarios;
                me.prov_inventarios = usuarios[0].prov_inventarios;

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

                //Cotizador de lotes
                me.calc_lotes = usuarios[0].calc_lotes;
                me.edit_cotizacion = usuarios[0].edit_cotizacion;
                me.opc_cotizador = usuarios[0].opc_cotizador;

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
                me.digital_lead = usuarios[0].digital_lead;
                me.reubicaciones = usuarios[0].reubicaciones;

                //Rentas
                me.admin_rentas = usuarios[0].admin_rentas;
                me.pagos_rentas = usuarios[0].pagos_rentas;
                if(me.admin_rentas == 1 || me.pagos_rentas == 2)
                    me.rentas = 1;

                //Saldos
                me.edo_cuenta = usuarios[0].edo_cuenta;
                me.depositos = usuarios[0].depositos;
                me.gastos_admn = usuarios[0].gastos_admn;
                me.cobro_credito = usuarios[0].cobro_credito;
                me.dev_cancel = usuarios[0].dev_cancel;
                me.dev_exc = usuarios[0].dev_exc;
                me.facturas = usuarios[0].facturas;
                me.ingresos_concretania = usuarios[0].ingresos_concretania;
                me.dev_virtual = usuarios[0].dev_virtual;

                //Gestoria
                me.expediente = usuarios[0].expediente;
                me.asig_gestor = usuarios[0].asig_gestor;
                me.seg_tramite = usuarios[0].seg_tramite;
                me.avaluos = usuarios[0].avaluos;
                me.bonos_rec = usuarios[0].bonos_rec;
                me.int_cobros = usuarios[0].int_cobros;

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
                me.rep_ingresos = usuarios[0].rep_ingresos;
                me.rep_escrituras = usuarios[0].rep_escrituras;
                me.rep_leads = usuarios[0].rep_leads;
                me.rep_entregas = usuarios[0].rep_entregas;
                me.rep_empresas = usuarios[0].rep_empresas;

                //Creditos Puente
                me.bases = usuarios[0].bases;
                me.solic_credito_puente = usuarios[0].solic_credito_puente;
                me.seg_cp = usuarios[0].seg_cp;
                me.edo_cta_bancrea = usuarios[0].edo_cta_bancrea;

                me.rol_id = usuarios[0].rol_id;

            })
            .catch(function (error) {
                console.log(error);
            });

        },

    },
    mounted() {
        this.getPrivilegios(this.userId)
    },

}
</script>
<style lang="">

</style>
