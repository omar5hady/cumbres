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
                    <i class="fa fa-align-justify"></i> Reporte de Ventas y Cancelaciones &nbsp;&nbsp;
                    <!--   Boton Nuevo    -->
                    <a class="btn btn-success" v-bind:href="'/reprotes/reporteVentasExcel?fecha=' + fecha + '&fecha2=' + fecha2 + '&empresa=' + emp_constructora + '&empresa2=' + emp_terreno  + '&publicidad=' + b_publicidad">
                        <i class="fa fa-file-text"></i>&nbsp; Excel
                    </a>
                    <!-- <a :href="'/etapa/excel?buscar=' + buscar + '&buscar2=' + buscar2 + '&criterio=' + criterio"  class="btn btn-success"><i class="fa fa-file-text"></i> Excel </a> -->
                    <!---->
                </div>
                <div class="card-body">
                        <ul class="nav nav2 nav-tabs" id="myTab1" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active show" id="venta-tab" data-toggle="tab" href="#venta" role="tab" aria-controls="venta" v-text="'Ventas'"></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="cancelacion-tab" data-toggle="tab" href="#cancelacion" role="tab" aria-controls="cancelacion" aria-selected="false" v-text="'Cancelaciones'"></a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="reubicacion-tab" data-toggle="tab" href="#reubicacion" role="tab" aria-controls="reubicacion" aria-selected="false" v-text="'Reubicaciones'"></a>
                        </li>
                    </ul>

                    <div class="tab-content" id="myTab1Content">

                        <!-- Listado por Ventas -->
                        <div class="tab-pane fade active show" id="venta" role="tabpanel" aria-labelledby="venta-tab">
                    
                            <div class="form-group row">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input type="date"  v-model="fecha" @keyup.enter="listarReporte()" class="form-control" placeholder="Fecha inicial">
                                        <input type="date"  v-model="fecha2" @keyup.enter="listarReporte()" class="form-control" placeholder="Fecha final">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-8" v-if="fecha != ''">
                                    <div class="input-group">
                                        <!--Criterios para el listado de busqueda -->
                                        <label class="form-control col-md-4" disabled>
                                            Medio publicitario:
                                        </label>

                                        <select class="form-control" v-model="b_publicidad" >
                                            <option value="">Publicidad</option>
                                            <option v-for="publicidad in arrayMediosPublicidad" :key="publicidad.id" :value="publicidad.id" v-text="publicidad.nombre"></option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <select class="form-control col-md-4" v-model="emp_constructora">
                                            <option value="">Empresa constructora</option>
                                            <option value="Grupo Constructor Cumbres">Grupo Constructor Cumbres</option>
                                            <option value="CONCRETANIA">CONCRETANIA</option>
                                        </select>
                                        <select class="form-control col-md-4" v-model="emp_terreno">
                                            <option value="">Empresa terreno</option>
                                            <option value="Grupo Constructor Cumbres">Grupo Constructor Cumbres</option>
                                            <option value="CONCRETANIA">CONCRETANIA</option>
                                        </select>
                                        <button type="submit" @click="listarReporte()" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive" >
                                <table v-if="cont1 != 0" class="table2 table-bordered table-striped table-sm">
                                    <thead>
                                        <tr v-if="activo == 1">
                                            <th colspan="12" class="text-center"> Ventas en el periodo ({{cont1}}) </th>
                                            <th colspan="2" class="text-center"> Ventas en el periodo ({{cont1}}) </th>
                                            <th colspan="11" class="text-center"> Ventas en el periodo ({{cont1}}) </th>
                                        </tr>
                                        <tr v-else></tr>
                                            <th colspan="25" class="text-center"> Ventas en el periodo ({{cont1}}) </th>
                                        <tr @dblclick="cambiar()" >
                                            <th>Archivo Fisc</th>
                                            <th>RFC Fisc</th>
                                            <th>Fraccionamiento</th>
                                            <th>Etapa</th>
                                            <th>Manzana</th>
                                            <th>Modelo</th>
                                            <th>Lote</th>
                                            <th>% Avance</th>
                                            <th>Cliente</th>
                                            <th>Asesor</th>
                                            <th>Fecha de venta</th>
                                            <th>Crédito</th>
                                            <th>Institución</th>
                                            <th>Medio de publicidad</th>
                                            <th v-if="activo == 1">Promocion/paquete</th>
                                            <th colspan="3" class="text-center">
                                                Valor de escrituración
                                                <tr>
                                                    <th>Precio venta</th>
                                                    <th>Valor de terreno</th>
                                                    <th>Valor de construcción</th>
                                                </tr>
                                            </th>
                                            
                                            <th></th>
                                            <th>Descuento precio de casa o equipamiento</th>
                                            <th class="td2">Descuento en el terreno</th>
                                            <th class="td2">Costo de Alarma </th>
                                            <th class="td2">Cuota de mantenimiento</th>
                                            <th class="td2">Protecciones</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="lote in arrayLotes" :key="lote.id" v-bind:style="{ backgroundColor : !lote.status == 0 ? '#FFFFFF' : '#D23939'}">
                                            <td>
                                                <template v-if="lote.archivo_fisc == null">
                                                    <span class="badge badge-warning">Sin cargar</span>
                                                </template>
                                                <template v-else>
                                                    <button 
                                                        type="button" @click="verImagen(lote.archivo_fisc)" class="btn btn-dark btn-sm"
                                                            title="Ver Archivo Fiscal"
                                                        >
                                                            <i class="icon-eye"></i>
                                                            &nbsp; Cargado el dia: {{lote.fecha_archivo}}
                                                    </button>
                                                </template>
                                            </td>
                                            <td>
                                                <template v-if="lote.fecha_rfc == null">
                                                    <span class="badge badge-warning">Sin registrar</span>
                                                </template>
                                                <template v-else>
                                                    <span class="badge badge-dark">Registrado el dia: {{lote.fecha_rfc}}</span>
                                                </template>

                                            </td>
                                            <td class="td2" v-text="lote.proyecto"></td>
                                            <td class="td2" v-text="lote.num_etapa"></td>
                                            <td class="td2" v-text="lote.manzana"></td>
                                            <td class="td2" v-text="lote.modelo"></td>
                                            <td class="td2" v-text="lote.num_lote"></td>
                                            <td class="td2" v-text="lote.avance_lote + ' %'"></td>
                                            <td class="td2" v-text="lote.nombre.toUpperCase() + ' ' + lote.apellidos.toUpperCase()"></td>
                                            <td class="td2" v-text="lote.a_nombre.toUpperCase() + ' ' + lote.a_apellidos.toUpperCase()"></td>
                                            <td class="td2" v-text="lote.fecha"></td>
                                            <td class="td2" v-text="lote.tipo_credito"></td>
                                            <td class="td2" v-text="lote.institucion"></td>
                                            <td class="td2" v-text="lote.publicidad"></td>
                                            <template v-if="activo == 1">
                                                <td class="td2" v-if="lote.descripcion_promocion == null && lote.descripcion_paquete == null" v-text="''"></td>
                                                <td class="td2" v-else-if="lote.descripcion_promocion != null && lote.descripcion_paquete == null" v-text="'Promo: '+lote.descripcion_promocion"></td>
                                                <td class="td2" v-else-if="lote.descripcion_promocion == null && lote.descripcion_paquete != null" v-text="'Paquete: '+lote.descripcion_paquete"></td>
                                                <td class="td2" v-else v-text="'Promo: ' + lote.descripcion_promocion + ' / Paquete:' + lote.descripcion_paquete"></td>
                                            </template>
                                            <template v-if="lote.emp_constructora != 'CONCRETANIA'">
                                                <td colspan="3" class="td2" v-text="'$'+formatNumber(lote.precio_venta)"></td>
                                            </template>
                                            <template v-else>
                                                <td class="td2" v-text="'$'+formatNumber(lote.precio_venta)"></td>
                                                <td class="td2" v-text="'$'+formatNumber(lote.valor_terreno)"></td>
                                                <td class="td2" v-text="'$'+formatNumber(lote.precio_venta - lote.valor_terreno)"></td>
                                            </template>
                                            
                                            <td></td>
                                            <td class="td2">
                                                <input type="text" pattern="\d*" @keyup.enter="actualizarDescuento(lote.id,$event.target.value)" :id="lote.id" :value="lote.costo_descuento|currency" step="1"  v-on:keypress="isNumber($event)" class="form-control" >
                                            </td>
                                            <td class="td2">
                                                <input type="text" pattern="\d*" @keyup.enter="updateDescuentoTerreno(lote.id,$event.target.value)" :id="lote.id" :value="lote.descuento_terreno|currency" step="1"  v-on:keypress="isNumber($event)" class="form-control" >
                                            </td>
                                            <td class="td2">
                                                <input type="text" pattern="\d*" @keyup.enter="updateCostoAlarma(lote.id,$event.target.value)" :id="lote.id" :value="lote.costo_alarma|currency" step="1"  v-on:keypress="isNumber($event)" class="form-control" >
                                            </td>
                                            <td class="td2">
                                                <input type="text" pattern="\d*" @keyup.enter="updateCostoCuotaMant(lote.id,$event.target.value)" :id="lote.id" :value="lote.costo_cuota_mant|currency" step="1"  v-on:keypress="isNumber($event)" class="form-control" >
                                            </td>
                                            <td class="td2">
                                                <input type="text" pattern="\d*" @keyup.enter="updateCostoProtecciones(lote.id,$event.target.value)" :id="lote.id" :value="lote.costo_protecciones|currency" step="1"  v-on:keypress="isNumber($event)" class="form-control" >
                                            </td>

                                            <template>
                                                <td v-if="lote.status == 0" class="td2"> <span class="badge badge-danger">Cancelado</span></td>
                                                <td v-else-if="lote.status == 1" class="td2"> <span class="badge badge-warning">Vendida</span></td>
                                                <td v-else-if="lote.status == 3 && lote.firmado == 0" class="td2"> <span class="badge badge-warning">Vendida</span></td>
                                                <td v-else-if="lote.status == 3 && lote.firmado == 1" class="td2"> <span class="badge badge-success">Individualizada</span></td>
                                            </template>
                                        </tr>                             
                                    </tbody>
                                </table>

                                
                            </div>
                        </div>

                        <!-- Listado por Cancelaciones -->
                        <div class="tab-pane fade" id="cancelacion" role="tabpanel" aria-labelledby="cancelacion-tab">
                    
                            <div class="form-group row">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input type="date"  v-model="fecha" @keyup.enter="listarReporte()" class="form-control" placeholder="Fecha inicial">
                                        <input type="date"  v-model="fecha2" @keyup.enter="listarReporte()" class="form-control" placeholder="Fecha final">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-8" v-if="fecha != ''">
                                    <div class="input-group">
                                        <!--Criterios para el listado de busqueda -->
                                        <label class="form-control col-md-4" disabled>
                                            Medio publicitario:
                                        </label>

                                        <select class="form-control" v-model="b_publicidad" >
                                            <option value="">Publicidad</option>
                                            <option v-for="publicidad in arrayMediosPublicidad" :key="publicidad.id" :value="publicidad.id" v-text="publicidad.nombre"></option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <select class="form-control col-md-4" v-model="emp_constructora">
                                            <option value="">Empresa constructora</option>
                                            <option value="Grupo Constructor Cumbres">Grupo Constructor Cumbres</option>
                                            <option value="CONCRETANIA">CONCRETANIA</option>
                                        </select>
                                        <select class="form-control col-md-4" v-model="emp_terreno">
                                            <option value="">Empresa terreno</option>
                                            <option value="Grupo Constructor Cumbres">Grupo Constructor Cumbres</option>
                                            <option value="CONCRETANIA">CONCRETANIA</option>
                                        </select>
                                        <button type="submit" @click="listarReporte()" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table2 table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th colspan="5" class="text-center"> Cancelaciones en el periodo ({{cont2}}) </th>
                                            <th colspan="6" class="text-center"> Cancelaciones en el periodo ({{cont2}}) </th>
                                            <th colspan="5" class="text-center"> Cancelaciones en el periodo ({{cont2}}) </th>
                                        </tr>
                                        <tr>
                                            <th>Fraccionamiento</th>
                                            <th>Etapa</th>
                                            <th>Manzana</th>
                                            <th>Modelo</th>
                                            <th>Lote</th>
                                            <th>Cliente</th>
                                            <th>Asesor</th>
                                            <th>Fecha de cancelación</th>
                                            <th>Fecha de venta</th>
                                            <th>Crédito</th>
                                            <th>Institución</th>
                                            <th>Medio de publicidad</th>
                                            <th>Promoción / Paquete</th>
                                            <th colspan="3" class="text-center">
                                                Valor de escrituración
                                                <tr>
                                                    <th>Precio venta</th>
                                                    <th>Valor de terreno</th>
                                                    <th>Valor de construcción</th>
                                                </tr>
                                            </th>
                                            <th class="text-center">
                                                Motivo de cancelación
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="cancelacion in arrayCancelaciones" :key="cancelacion.id">
                                            <td class="td2" v-text="cancelacion.proyecto"></td>
                                            <td class="td2" v-text="cancelacion.num_etapa"></td>
                                            <td class="td2" v-text="cancelacion.manzana"></td>
                                            <td class="td2" v-text="cancelacion.modelo"></td>
                                            <td class="td2" v-text="cancelacion.num_lote"></td>
                                            <td class="td2" v-text="cancelacion.nombre.toUpperCase() + ' ' + cancelacion.apellidos.toUpperCase()"></td>
                                            <td class="td2" v-text="cancelacion.a_nombre.toUpperCase() + ' ' + cancelacion.a_apellidos.toUpperCase()"></td>
                                            <td class="td2" v-text="cancelacion.fecha_status"></td>
                                            <td class="td2" v-text="cancelacion.fecha"></td>
                                            <td class="td2" v-text="cancelacion.tipo_credito"></td>
                                            <td class="td2" v-text="cancelacion.institucion"></td>
                                            <td class="td2" v-text="cancelacion.publicidad"></td>
                                            <template>
                                                <td class="td2" v-if="cancelacion.descripcion_promocion == null && cancelacion.descripcion_paquete == null || cancelacion.descripcion_promocion == '' && cancelacion.descripcion_paquete == ''" v-text="''"></td>
                                                <td class="td2" v-else-if="cancelacion.descripcion_promocion != null && cancelacion.descripcion_paquete == null" v-text="'Promo: '+cancelacion.descripcion_promocion"></td>
                                                <td class="td2" v-else-if="cancelacion.descripcion_promocion == null && cancelacion.descripcion_paquete != null" v-text="'Paquete: '+cancelacion.descripcion_paquete"></td>
                                                <td class="td2" v-else v-text="'Promo: ' + cancelacion.descripcion_promocion + ' / Paquete:' + cancelacion.descripcion_paquete"></td>
                                            </template>
                                            <template v-if="cancelacion.emp_constructora != 'CONCRETANIA'">
                                                <td colspan="3" class="td2" v-text="'$'+formatNumber(cancelacion.precio_venta)"></td>
                                            </template>
                                            <template v-else>
                                                <td class="td2" v-text="'$'+formatNumber(cancelacion.precio_venta)"></td>
                                                <td class="td2" v-text="'$'+formatNumber(cancelacion.valor_terreno)"></td>
                                                <td class="td2" v-text="'$'+formatNumber(cancelacion.precio_venta - cancelacion.valor_terreno)"></td>
                                            </template>
                                            <td v-text="cancelacion.motivo_cancel">

                                            </td>
                                        </tr>                             
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Listado por Reubicaciones -->
                        <div class="tab-pane fade" id="reubicacion" role="tabpanel" aria-labelledby="reubicacion-tab">
                    
                            <div class="form-group row">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input type="date"  v-model="fecha" @keyup.enter="listarReporte()" class="form-control" placeholder="Fecha inicial">
                                        <input type="date"  v-model="fecha2" @keyup.enter="listarReporte()" class="form-control" placeholder="Fecha final">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-8" v-if="fecha != ''">
                                    <div class="input-group">
                                        <!--Criterios para el listado de busqueda -->
                                        <label class="form-control col-md-4" disabled>
                                            Medio publicitario:
                                        </label>

                                        <select class="form-control" v-model="b_publicidad" >
                                            <option value="">Publicidad</option>
                                            <option v-for="publicidad in arrayMediosPublicidad" :key="publicidad.id" :value="publicidad.id" v-text="publicidad.nombre"></option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <select class="form-control col-md-4" v-model="emp_constructora">
                                            <option value="">Empresa constructora</option>
                                            <option value="Grupo Constructor Cumbres">Grupo Constructor Cumbres</option>
                                            <option value="CONCRETANIA">CONCRETANIA</option>
                                        </select>
                                        <select class="form-control col-md-4" v-model="emp_terreno">
                                            <option value="">Empresa terreno</option>
                                            <option value="Grupo Constructor Cumbres">Grupo Constructor Cumbres</option>
                                            <option value="CONCRETANIA">CONCRETANIA</option>
                                        </select>
                                        <button type="submit" @click="listarReporte()" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table2 table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th colspan="16" class="text-center"> Reubicaciones en el periodo ({{cont2}}) </th>
                                        </tr>
                                        <tr>
                                            <th>Fecha de reubicación</th>
                                            <th>Folio</th>
                                            <th>Fraccionamiento</th>
                                            <th>Etapa</th>
                                            <th>Manzana</th>
                                            <th>Lote</th>
                                            <th>Asesor</th>
                                            <th>Cliente</th>
                                            <th>Valor de terreno</th>
                                            <th>Crédito</th>
                                            <th>Institución</th>
                                            <th>Promoción</th>
                                            <th>Emp Constructora</th>
                                            <th>Emp Terreno</th>
                                            <th>Observación</th>
                                        </tr>
                                    </thead>
                                    <tbody v-for="reubicacion in arrayReubicaciones"  :key="reubicacion.id">
                                        <tr style="backgroundColor:#FF8E8E">
                                            <td class="td2" v-text="reubicacion.fecha_reubicacion"></td>
                                            <td class="td2" v-text="reubicacion.contrato_id"></td>
                                            <td class="td2" v-text="reubicacion.proyecto"></td>
                                            <td class="td2" v-text="reubicacion.etapa"></td>
                                            <td class="td2" v-text="reubicacion.manzana"></td>
                                            <td class="td2" v-text="reubicacion.num_lote"></td>
                                            <td class="td2" v-text="reubicacion.a_nombre.toUpperCase() + ' ' + reubicacion.a_apellidos.toUpperCase()"></td>
                                            <td class="td2" v-text="reubicacion.c_nombre.toUpperCase() + ' ' + reubicacion.c_apellidos.toUpperCase()"></td>
                                            <td class="td2" v-text="'$'+formatNumber(reubicacion.valor_terreno)"></td>
                                            <td class="td2" v-text="reubicacion.tipo_credito"></td>
                                            <td class="td2" v-text="reubicacion.institucion"></td>
                                            <td class="td2" v-text="reubicacion.promocion"></td>
                                            <td class="td2" v-text="reubicacion.emp_constructora"></td>
                                            <td class="td2" v-text="reubicacion.emp_terreno"></td>
                                            <td class="td2" v-text="reubicacion.observacion"></td>
                                        </tr>
                                        <tr style="backgroundColor:#8EFF9B">
                                            <td class="td2" v-text="reubicacion.venta.fecha"></td>
                                            <td class="td2" v-text="reubicacion.venta.id"></td>
                                            <td class="td2" v-text="reubicacion.venta.proyecto"></td>
                                            <td class="td2" v-text="reubicacion.venta.etapa"></td>
                                            <td class="td2" v-text="reubicacion.venta.manzana"></td>
                                            <td class="td2" v-text="reubicacion.venta.num_lote"></td>
                                            <td class="td2" v-text="reubicacion.venta.a_nombre.toUpperCase() + ' ' + reubicacion.venta.a_apellidos.toUpperCase()"></td>
                                            <td class="td2" v-text="reubicacion.venta.c_nombre.toUpperCase() + ' ' + reubicacion.venta.c_apellidos.toUpperCase()"></td>
                                            <td class="td2" v-text="'$'+formatNumber(reubicacion.venta.valor_terreno)"></td>
                                            <td class="td2" v-text="reubicacion.venta.tipo_credito"></td>
                                            <td class="td2" v-text="reubicacion.venta.institucion"></td>
                                            <td class="td2" v-text="reubicacion.venta.promocion"></td>
                                            <td class="td2" v-text="reubicacion.venta.emp_constructora"></td>
                                            <td class="td2" v-text="reubicacion.venta.emp_terreno"></td>
                                            <template>
                                                <td v-if="reubicacion.venta.status == 0" class="td2"> <span class="badge badge-danger">Cancelado</span></td>
                                                <td v-else-if="reubicacion.venta.status == 1" class="td2"> <span class="badge badge-warning">Vendida</span></td>
                                                <td v-else-if="reubicacion.venta.status == 3 && reubicacion.venta.firmado == 0" class="td2"> <span class="badge badge-warning">Vendida</span></td>
                                                <td v-else-if="reubicacion.venta.status == 3 && reubicacion.venta.firmado == 1" class="td2"> <span class="badge badge-success">Individualizada</span></td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <td colspan="13"><br></td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
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
               
                arrayLotes : [],
                arrayCancelaciones : [],
                arrayReubicaciones : [],
                arrayMediosPublicidad : [],
                b_publicidad : '',
                fecha:'',
                fecha2:'',
                cont1:0,
                cont2:0,
                cont3:0,
                activo:1,
                emp_constructora:'',
                emp_terreno:''
            }
        },
        computed:{
        },
        methods : {
            /**Metodo para mostrar los registros */
            listarReporte(){
                let me = this;
                var url = '/reprotes/reporteVentas?fecha=' + me.fecha + '&fecha2=' + me.fecha2 + '&empresa=' + me.emp_constructora + '&empresa2=' + me.emp_terreno + '&publicidad=' + me.b_publicidad;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLotes = respuesta.ventas;
                    me.arrayReubicaciones = respuesta.reubicaciones;
                    me.arrayCancelaciones = respuesta.cancelaciones;
                    me.cont1 = respuesta.contVentas;
                    me.cont2 = respuesta.contCancelaciones;
                    me.conte = respuesta.contReubicaciones;

                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            actualizarDescuento(id,monto){
                let me = this;
                axios.put('/creditos/updateCostoDescuento',{
                    'monto':monto,
                    'id' : id
                }).then(function (response){ 
                me.listarReporte();
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

            updateDescuentoTerreno(id,monto){
                let me = this;
                axios.put('/creditos/updateDescuentoTerreno',{
                    'monto':monto,
                    'id' : id
                }).then(function (response){ 
                me.listarReporte();
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

            selectMedioPublicidad(){
                let me = this;
                var url = '/select_medio_publicidad';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayMediosPublicidad = respuesta.medios_publicitarios;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            updateCostoAlarma(id,monto){
                let me = this;
                axios.put('/creditos/updateCostoAlarma',{
                    'monto':monto,
                    'id' : id
                }).then(function (response){ 
                me.listarReporte();
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
            verImagen(imagen){
                let url = '/files/datosFisc/'+imagen;
                Swal.fire({
                imageUrl: url,
                imageWidth: 650,
                imageHeight: 800,
                imageAlt: 'Datos Fiscales',
                })
            },
            updateCostoCuotaMant(id,monto){
                let me = this;
                axios.put('/creditos/updateCostoCuotaMant',{
                    'monto':monto,
                    'id' : id
                }).then(function (response){ 
                me.listarReporte();
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

            updateCostoProtecciones(id,monto){
                let me = this;
                axios.put('/creditos/updateCostoProtecciones',{
                    'monto':monto,
                    'id' : id
                }).then(function (response){ 
                me.listarReporte();
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

            cambiar(){
                if(this.activo == 1)
                    this.activo = 0;
                else
                    this.activo = 1;
            },

            isNumber: function(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                    evt.preventDefault();
                } else {
                    return true;
                }
            },
            
            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
        
        },
        mounted() {
            this.selectMedioPublicidad();
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
</style>
