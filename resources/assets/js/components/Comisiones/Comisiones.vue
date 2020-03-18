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
                        <i class="fa fa-align-justify"></i> Comisiones &nbsp;
                        <button type="button" @click="abrirModal('nuevo')" class="btn btn-primary">
                            <i class="icon-plus"></i>&nbsp;Nueva Comision
                        </button>
                    </div>

            <!----------------- Listado Contratos ------------------------------>
                    <!-- Div Card Body para listar -->
                        <div class="card-body"> 
                            <div class="form-group row">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <select class="form-control"  @keyup.enter="listarComisiones(1)" v-model="b_mes" >
                                            <option value="">Mes</option>
                                            <option value="01">Enero</option>
                                            <option value="02">Febrero</option>
                                            <option value="03">Marzo</option>
                                            <option value="04">Abril</option>
                                            <option value="05">Mayo</option>
                                            <option value="06">Junio</option>
                                            <option value="07">Julio</option>
                                            <option value="08">Agosto</option>
                                            <option value="09">Septiembre</option>
                                            <option value="10">Octubre</option>
                                            <option value="11">Noviembre</option>
                                            <option value="12">Diciembre</option>
                                            
                                        </select>
                                        <select class="form-control"  @keyup.enter="listarComisiones(1)" v-model="b_anio" >
                                            <option value="">Año</option>
                                            <option value="2019">2019</option>
                                            <option value="2020">2020</option>
                                            <option value="2021">2021</option>
                                            <option value="2022">2022</option>
                                            <option value="2023">2023</option>
                                            <option value="2024">2024</option>
                                            <option value="2025">2025</option>
                                            <option value="2026">2026</option>
                                            <option value="2027">2027</option>
                                            <option value="2028">2028</option>
                                            <option value="2029">2029</option>
                                            <option value="2030">2030</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <!--Criterios para el listado de busqueda -->
                                        <label class="form-control col-md-4" disabled>
                                            Asesor:
                                        </label>
                                        <select class="form-control"  v-model="b_asesor_id" >
                                            <option value="">Seleccione</option>
                                            <option v-for="asesor in arrayAsesores" :key="asesor.id" :value="asesor.id" v-text="asesor.nombre + ' '+ asesor.apellidos"></option>
                                        </select>
                                    </div>
                                    <div class="input-group">
                                        <button type="submit" @click="listarComisiones(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table2 table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Mes</th>
                                            <th>Año</th>
                                            <th>Asesor</th>
                                            <th># Ventas</th>
                                            <th># Cancelaciones</th>
                                            <th>Bono</th>
                                            <th>Comision</th>
                                            <th>Anticipo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="contrato in arrayComisiones" :key="contrato.id">
                                            <td class="td2">
                                                    <button type="button" title="Ver detalle de la comision" @click="abrirModal('detalle',contrato)" class="btn btn-primary btn-sm">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                </td>
                                            <td class="td2" v-if="contrato.mes == 1" v-text="'Enero'"></td>
                                            <td class="td2" v-else-if="contrato.mes == 2" v-text="'Febrero'"></td>
                                            <td class="td2" v-else-if="contrato.mes == 3" v-text="'Marzo'"></td>
                                            <td class="td2" v-else-if="contrato.mes == 4" v-text="'Abril'"></td>
                                            <td class="td2" v-else-if="contrato.mes == 5" v-text="'Mayo'"></td>
                                            <td class="td2" v-else-if="contrato.mes == 6" v-text="'Junio'"></td>
                                            <td class="td2" v-else-if="contrato.mes == 7" v-text="'Julio'"></td>
                                            <td class="td2" v-else-if="contrato.mes == 8" v-text="'Agosto'"></td>
                                            <td class="td2" v-else-if="contrato.mes == 9" v-text="'Septiembre'"></td>
                                            <td class="td2" v-else-if="contrato.mes == 10" v-text="'Octubre'"></td>
                                            <td class="td2" v-else-if="contrato.mes == 11" v-text="'Noviembre'"></td>
                                            <td class="td2" v-else-if="contrato.mes == 12" v-text="'Diciembre'"></td>
                                            <td class="td2" v-text="contrato.anio"></td>
                                            <td class="td2" v-text="contrato.asesor"></td>
                                            <td class="td2" v-text="contrato.num_ventas "></td>
                                            <td class="td2" v-text="contrato.num_cancelaciones"></td>
                                            <td class="td2" v-text="'$'+formatNumber(contrato.bono)"></td>
                                            <td class="td2" v-text="'$'+formatNumber(contrato.total)"></td>
                                            <td class="td2" v-text="'$'+formatNumber(contrato.totalAnticipo)"></td>
                                        </tr>                               
                                    </tbody>
                                </table>
                            </div>
                            <nav>
                                <!--Botones de paginacion -->
                                <ul class="pagination">
                                    <li class="page-item" v-if="pagination2.current_page > 1">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page - 1)">Ant</a>
                                    </li>
                                    <li class="page-item" v-for="page2 in pagesNumber2" :key="page2" :class="[page2 == isActived2 ? 'active' : '']">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina2(page2)" v-text="page2"></a>
                                    </li>
                                    <li class="page-item" v-if="pagination2.current_page < pagination2.last_page">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page + 1)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>

            <!-- Inicio Modal GENERAR COMISION -->
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

                            <div class="form-group row">
                                <label class="col-md-1 form-control-label" for="text-input">Mes</label>
                                <div class="col-md-3">
                                    <select class="form-control" v-model="mes" >
                                        <option value="">Mes</option>
                                        <option value="01">Enero</option>
                                        <option value="02">Febrero</option>
                                        <option value="03">Marzo</option>
                                        <option value="04">Abril</option>
                                        <option value="05">Mayo</option>
                                        <option value="06">Junio</option>
                                        <option value="07">Julio</option>
                                        <option value="08">Agosto</option>
                                        <option value="09">Septiembre</option>
                                        <option value="10">Octubre</option>
                                        <option value="11">Noviembre</option>
                                        <option value="12">Diciembre</option>
                                    </select>
                                </div>
                                <label class="col-md-1 form-control-label" for="text-input">Año</label>
                                <div class="col-md-2">
                                    <select class="form-control"  v-model="anio" >
                                        <option value="2018">2018</option>
                                        <option value="2019">2019</option>
                                        <option value="2020">2020</option>
                                        <option value="2021">2021</option>
                                        <option value="2022">2022</option>
                                        <option value="2023">2023</option>
                                        <option value="2024">2024</option>
                                        <option value="2025">2025</option>
                                        <option value="2026">2026</option>
                                        <option value="2027">2027</option>
                                        <option value="2028">2028</option>
                                        <option value="2029">2029</option>
                                        <option value="2030">2030</option>
                                        <option value="2031">2031</option>
                                        <option value="2032">2032</option>
                                        <option value="2033">2033</option>
                                        <option value="2034">2034</option>
                                        <option value="2035">2035</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-1 form-control-label" for="text-input">Asesor</label>
                                <div class="col-md-8">
                                    <select class="form-control"  v-model="asesor_id" >
                                        <option value="">Seleccione</option>
                                        <option v-for="asesor in arrayAsesores" :key="asesor.id" :value="asesor.id" v-text="asesor.nombre + ' '+ asesor.apellidos"></option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" @click="ventasAsesor(asesor_id), ventas = 1" class="btn btn-primary">
                                        <i class="fa fa-search-plus"></i> &nbsp;Buscar ventas
                                    </button>
                                </div>
                            </div>

                            <div class="form-group row line-separator"></div>

                            <!-- VENTAS -->
                                <div class="form-group row">
                                    <h6 v-if="ventas==1"  style="font-weight: bold; color: blue;" class="col-md-3">Ventas: {{this.numVentas}}</h6>
                                </div>

                                <div class="form-group row" v-if="ventas==1 && tipoVendedor == 0">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Folio</th>
                                                    <th>Proyecto</th>
                                                    <th>Etapa</th>
                                                    <th>Manzana</th>
                                                    <th>Lote</th>
                                                    <th>% Avance</th>
                                                    <th>Precio Venta</th>
                                                    <th>% De comisión</th>
                                                    <th>Comisión a pagar</th>
                                                    <th>Anticipo</th>
                                                    <th>Bono</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="venta in arrayVentas" :key="venta.id">
                                                    <td>
                                                        <span v-if="venta.fecha_exp == null" class="badge2 badge-danger" v-text="venta.id"></span>
                                                        <span v-else class="badge2 badge-success" v-text="venta.id"></span>
                                                    </td>
                                                    <td>
                                                        <span v-if="venta.fecha_exp == null" class="badge2 badge-danger" v-text="venta.proyecto"></span>
                                                        <span v-else class="badge2 badge-success" v-text="venta.proyecto"></span>
                                                    </td>
                                                    <td>
                                                        <span v-if="venta.fecha_exp == null" class="badge2 badge-danger" v-text="venta.etapa"></span>
                                                        <span v-else class="badge2 badge-success" v-text="venta.etapa"></span>
                                                    </td>
                                                    <td>
                                                        <span v-if="venta.fecha_exp == null" class="badge2 badge-danger" v-text="venta.manzana"></span>
                                                        <span v-else class="badge2 badge-success" v-text="venta.manzana"></span>
                                                    </td>
                                                    <td>
                                                        <span v-if="venta.fecha_exp == null" class="badge2 badge-danger" v-text="venta.num_lote"></span>
                                                        <span v-else class="badge2 badge-success" v-text="venta.num_lote"></span>
                                                    </td>
                                                    <td v-text="venta.avance_lote + '%'"></td>
                                                    <td v-text="'$'+formatNumber(venta.precio_venta)"></td>
                                                    <template v-if="numVentas == 1">
                                                        <td v-if="venta.avance_lote<=90">
                                                            {{ venta.comisionReal = (venta.extra + (0.80*(venta.porcentaje_exp/100)) ).toFixed(3) }}%
                                                        </td>
                                                        <td v-else>
                                                            {{ venta.comisionReal = (venta.extra + ((venta.porcentaje_exp/100)*1) ).toFixed(3) }}%
                                                        </td>
                                                    </template>
                                                    <template v-else-if="numVentas == 2">
                                                        <td v-if="venta.avance_lote<=90">
                                                            {{venta.comisionReal = (venta.extra + (1.00*(venta.porcentaje_exp/100)) ).toFixed(3)}}%
                                                        </td>
                                                        <td v-else>
                                                            {{venta.comisionReal = (venta.extra + ((venta.porcentaje_exp/100)*1.25) ).toFixed(3)}}%
                                                        </td>
                                                    </template>
                                                    <template v-else-if="numVentas == 3">
                                                        <td v-if="venta.avance_lote<=90">
                                                            {{venta.comisionReal = (venta.extra + (1.30*(venta.porcentaje_exp/100)) ).toFixed(3)}}%
                                                        </td>
                                                        <td v-else>
                                                            {{venta.comisionReal = (venta.extra + ((venta.porcentaje_exp/100)*1.55) ).toFixed(3)}}%
                                                        </td>
                                                    </template>
                                                    <template v-else-if="numVentas == 4">
                                                        <td v-if="venta.avance_lote<=90">
                                                            {{venta.comisionReal = (venta.extra + (1.50*(venta.porcentaje_exp/100)) ).toFixed(3)}}%
                                                        </td>
                                                        <td v-else>
                                                            {{venta.comisionReal = (venta.extra + ((venta.porcentaje_exp/100)*1.75) ).toFixed(3)}}%
                                                        </td>

                                                    </template>
                                                    <template v-else-if="numVentas == 5">
                                                        <td v-if="venta.avance_lote<=90">
                                                            {{venta.comisionReal = (venta.extra + (1.70*(venta.porcentaje_exp/100)) ).toFixed(3)}}%
                                                        </td>
                                                        <td v-else>
                                                            {{venta.comisionReal = (venta.extra + ((venta.porcentaje_exp/100)*2.00) ).toFixed(3)}}%
                                                        </td>
                                                    </template>
                                                    <template v-else-if="numVentas >= 6">
                                                        <td v-if="venta.avance_lote<=90">
                                                            {{venta.comisionReal = (venta.extra + (2.00*(venta.porcentaje_exp/100)) ).toFixed(3)}}%
                                                        </td>
                                                        <td v-else>
                                                            {{venta.comisionReal = (venta.extra + ((venta.porcentaje_exp/100)*2.00) ).toFixed(3)}}%
                                                        </td>
                                                    </template>
                                                    <td v-text="'$'+formatNumber(venta.comision = (venta.precio_venta * (venta.comisionReal/100)))"></td>
                                                    <td v-text="'$'+formatNumber(venta.anticipo = (venta.comision / 2))"></td>
                                                    <td v-text="'$'+formatNumber(venta.bono = bono)"></td>
                                                </tr>  
                                                <tr>
                                                    <td align="right" colspan="8"><strong>Total:</strong></td>
                                                    <td><strong>${{formatNumber(comision = totalComision)}}</strong></td>
                                                    <td><strong>${{formatNumber(anticipo = anticipoVentas)}}</strong></td>
                                                    <td><strong>${{formatNumber(bonoTotal = bonoVentas)}}</strong></td>
                                                    
                                                </tr>                     
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="form-group row" v-else-if="ventas==1 && tipoVendedor == 1">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Folio</th>
                                                    <th>Proyecto</th>
                                                    <th>Etapa</th>
                                                    <th>Manzana</th>
                                                    <th>Lote</th>
                                                    <th>% Avance</th>
                                                    <th>Precio Venta</th>
                                                    <th>% Obtenido</th>
                                                    <th>Comisión</th>
                                                    <th>Anticipo</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="venta in arrayVentas" :key="venta.id">
                                                    <td v-text="venta.id"></td>
                                                    <td v-text="venta.proyecto"></td>
                                                    <td v-text="venta.etapa"></td>
                                                    <td v-text="venta.manzana"></td>
                                                    <td v-text="venta.num_lote"></td>
                                                    <td v-text="venta.avance_lote + '%'"></td>
                                                    <td v-text="'$'+formatNumber(venta.precio_venta)"></td>
                                                    <td>
                                                        {{ venta.comisionReal = (venta.extra_ext + esquema).toFixed(1) }}%
                                                    </td>
                                                    <td v-text="'$'+formatNumber(venta.comision = (venta.precio_venta * (venta.comisionReal/100)))"></td>
                                                    <td v-text="'$'+formatNumber(venta.anticipo = (venta.comision / 2))"></td>
                                                </tr>      
                                                <tr>
                                                    <td align="right" colspan="8"><strong>Total:</strong></td>
                                                    <td><strong>${{formatNumber(comision = totalComision)}}</strong></td>
                                                    <td><strong>${{formatNumber(anticipo = anticipoVentas)}}</strong></td>
                                                </tr>                       
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            
                            <!-- Individualizadas -->
                                <div class="form-group row">
                                    <h6 v-if="ventas==1 && totalIndividualizadas != 0"  style="font-weight: bold; color: blue;" class="col-md-3">Individualizadas: {{this.numIndivi}}</h6>
                                </div>

                                <div class="form-group row" v-if="ventas==1 && totalIndividualizadas != 0">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Folio</th>
                                                    <th>Proyecto</th>
                                                    <th>Etapa</th>
                                                    <th>Manzana</th>
                                                    <th>Lote</th>
                                                    <th>Fecha de firma</th>
                                                    <th>$ Restante</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="indiv in arrayIndividualizadas" :key="indiv.folio">
                                                    <td v-text="indiv.folio"></td>
                                                    <td v-text="indiv.fraccionamiento"></td>
                                                    <td v-text="indiv.etapa"></td>
                                                    <td v-text="indiv.manzana"></td>
                                                    <td v-text="indiv.num_lote"></td>
                                                    <td v-text="indiv.fecha_firma_esc"></td>
                                                    <td v-text="'$'+formatNumber(indiv.anticipo)"></td>
                                                </tr>      
                                                <tr>
                                                    <td align="right" colspan="6"><strong>Total:</strong></td>
                                                    <td><strong>${{formatNumber(totalIndividualizadas)}}</strong></td>   
                                                </tr>                       
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            <div class="form-group row line-separator"  v-if="ventas==1"></div>
                            
                            <!-- CANCELACIONES -->
                                <div class="form-group row">
                                    <h6 v-if="ventas==1 & totalAnticipo != 0"  style="font-weight: bold; color: blue;" class="col-md-3">Cancelaciones: {{this.numAnticipos}}</h6>
                                </div>

                                <div class="form-group row" v-if="ventas==1 && totalAnticipo != 0">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Folio</th>
                                                    <th>Proyecto</th>
                                                    <th>Etapa</th>
                                                    <th>Manzana</th>
                                                    <th>Lote</th>
                                                    <th>Fecha de cancelación</th>
                                                    <th>$ Anticipo</th>
                                                    <th>Bono</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="anticipo in arrayAnticipos" :key="anticipo.folio">
                                                    <td v-text="anticipo.folio"></td>
                                                    <td v-text="anticipo.fraccionamiento"></td>
                                                    <td v-text="anticipo.etapa"></td>
                                                    <td v-text="anticipo.manzana"></td>
                                                    <td v-text="anticipo.num_lote"></td>
                                                    <td v-text="anticipo.fecha_status"></td>
                                                    <td v-text="'$'+formatNumber(anticipo.anticipo)"></td>
                                                    <td v-if="anticipo.fecha_bono" v-text="'$'+formatNumber(anticipo.bono)"></td>
                                                    <td v-else v-text="'$'+formatNumber(0)"></td>
                                                </tr>      
                                                <tr>
                                                    <td align="right" colspan="6"><strong>Total:</strong></td>
                                                    <td><strong>${{formatNumber(totalAnticipo)}}</strong></td>
                                                    <td><strong>${{formatNumber(totalBono)}}</strong></td>
                                                    
                                                </tr>                       
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            <div class="form-group row line-separator"  v-if="ventas==1"></div>

                            <!-- DATOS DE VENTA -->

                                <div class="form-group row"  v-if="ventas==1 && tipoVendedor == 0">
                                     <div class="col-md-6">
                                        <h6 style="font-weight: bold;" class="col-md-12">Bono: &nbsp;$ {{formatNumber(this.bonoTotal)}}</h6>
                                        <h6 style="font-weight: bold;" class="col-md-12">Comisión total:&nbsp; $ {{formatNumber(this.comision)}}</h6>
                                        <div class="form-group row line-separator"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label style="font-weight: bold;" class="col-md-12">+ Anticipo: &nbsp;$ {{formatNumber(this.anticipo)}} </label>
                                        <label style="font-weight: bold;" class="col-md-12">+ Individualizadas: &nbsp;$ {{formatNumber(this.totalIndividualizadas)}} </label>
                                        <label v-if="totalAnticipo != 0" style="font-weight: bold;" class="col-md-12">- Cancelaciones: &nbsp;$ {{formatNumber(this.totalAnticipo)}}</label>
                                        <label v-if="totalBono != 0" style="font-weight: bold;" class="col-md-12">- Bonos cancelados: &nbsp;$ {{formatNumber(this.totalBono)}}</label>
                                        <label v-if="sueldoBand" style="font-weight: bold;" class="col-md-4">- Apoyo mensual: &nbsp;</label><input type="text" v-if="sueldoBand" pattern="\d*" v-model="apoyo"  v-on:keypress="isNumber($event)" class="col-md-4" ><label style="font-weight: bold;" v-if="sueldoBand" class="col-md-4"> &nbsp; $ {{formatNumber(this.apoyo)}}</label>
                                        <label v-if="restante != 0" style="font-weight: bold;" class="col-md-12">- Restante anterior: &nbsp;$ {{formatNumber(this.restante)}}</label>
                                        <div class="form-group row line-separator"></div>
                                        <label style="font-weight: bold; color: blue;" class="col-md-12">= A pagar: &nbsp;$ {{formatNumber( this.aPagar = porPagar)}}</label>
                                    </div>
                                </div>

                                <div class="form-group row line-separator"></div>

                                <div class="form-group row"  v-if="ventas==1 && tipoVendedor == 1">
                                    <div class="col-md-6">
                                        <h6 style="font-weight: bold;" class="col-md-12">Comisión: &nbsp;$ {{formatNumber(this.comision)}}</h6>
                                    </div>
                                    <div class="col-md-6">
                                        <label style="font-weight: bold;" class="col-md-12">+ Anticipo: &nbsp;$ {{formatNumber(this.anticipo)}} </label>
                                        <label v-if="totalAnticipo != 0" style="font-weight: bold;" class="col-md-12">- Cancelaciones: &nbsp;$ {{formatNumber(this.totalAnticipo)}}</label>
                                        <label v-if="restante != 0" style="font-weight: bold;" class="col-md-12">- Restante anterior: &nbsp;$ {{formatNumber(this.restante)}}</label>
                                        <label v-if="ventas==1 && tipoVendedor == 1" style="font-weight: bold; color: blue;" class="col-md-12">= A pagar: &nbsp;$ {{formatNumber(this.aPagar =this.anticipo + this.totalIndividualizadas - this.totalAnticipo - this.restante)}}</label>
                                    </div>
                                </div>
                            

                            <!-- Div para mostrar los errores que mande validerPersonal -->
                            <div v-show="errorComision" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjComision" :key="error" v-text="error"></div>
                                </div>
                            </div>
                      </form>

                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="ventas==1" class="btn btn-primary" @click="registrarComision()">Generar comisión</button>
                         </div>
                    </div> 
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->

            <!-- Inicio Modal DETALLE COMISION -->
             <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal1}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
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
                                <label class="col-md-1 form-control-label" for="text-input">Mes</label>
                                <div class="col-md-3">
                                    <select disabled class="form-control" v-model="mes" >
                                        <option value="">Mes</option>
                                        <option value="1">Enero</option>
                                        <option value="2">Febrero</option>
                                        <option value="3">Marzo</option>
                                        <option value="4">Abril</option>
                                        <option value="5">Mayo</option>
                                        <option value="6">Junio</option>
                                        <option value="7">Julio</option>
                                        <option value="8">Agosto</option>
                                        <option value="9">Septiembre</option>
                                        <option value="10">Octubre</option>
                                        <option value="11">Noviembre</option>
                                        <option value="12">Diciembre</option>
                                    </select>
                                </div>
                                <label class="col-md-1 form-control-label" for="text-input">Año</label>
                                <div class="col-md-2">
                                    <input type="text" disabled v-model="anio" class="form-control">
                                </div>
                            </div>

                            
                            <!-- VENTAS -->
                                <div class="form-group row">
                                    <h6  style="font-weight: bold; color: blue;" class="col-md-3">Ventas: {{this.numVentas}}</h6>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Folio</th>
                                                    <th>Proyecto</th>
                                                    <th>Etapa</th>
                                                    <th>Manzana</th>
                                                    <th>Lote</th>
                                                    <th>% Avance</th>
                                                    <th>Precio Venta</th>
                                                    <th>% Obtenido</th>
                                                    <th>Comisión</th>
                                                    <th>Anticipo</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="venta in arrayDetalle" :key="venta.id">
                                                    <td>
                                                        <span v-if="venta.fecha_exp == null" class="badge2 badge-danger" v-text="venta.id"></span>
                                                        <span v-else class="badge2 badge-success" v-text="venta.id"></span>
                                                    </td>
                                                    <td>
                                                        <span v-if="venta.fecha_exp == null" class="badge2 badge-danger" v-text="venta.fraccionamiento"></span>
                                                        <span v-else class="badge2 badge-success" v-text="venta.fraccionamiento"></span>
                                                    </td>
                                                    <td>
                                                        <span v-if="venta.fecha_exp == null" class="badge2 badge-danger" v-text="venta.etapa"></span>
                                                        <span v-else class="badge2 badge-success" v-text="venta.etapa"></span>
                                                    </td>
                                                    <td>
                                                        <span v-if="venta.fecha_exp == null" class="badge2 badge-danger" v-text="venta.manzana"></span>
                                                        <span v-else class="badge2 badge-success" v-text="venta.manzana"></span>
                                                    </td>
                                                    <td>
                                                        <span v-if="venta.fecha_exp == null" class="badge2 badge-danger" v-text="venta.num_lote"></span>
                                                        <span v-else class="badge2 badge-success" v-text="venta.num_lote"></span>
                                                    </td>
                                                    <td v-text="venta.avance_lote + '%'"></td>
                                                    <td v-text="'$'+formatNumber(venta.precio_venta)"></td>
                                                    <td v-text="venta.comisionReal + '%'"></td>
                                                    <td v-text="'$'+formatNumber(venta.total)"></td>
                                                    <td v-text="'$'+formatNumber(venta.anticipo)"></td>
                                                </tr> 
                                                <tr>
                                                    <td align="right" colspan="8"><strong>Total:</strong></td>
                                                    <td><strong>${{formatNumber(comisionMes)}}</strong></td>
                                                    <td><strong>${{formatNumber(anticipoMes)}}</strong></td>
                                                </tr>                                             
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            <!-- Individualizadas -->
                                <div class="form-group row">
                                    <h6 v-if="totalIndividualizadas != 0"  style="font-weight: bold; color: blue;" class="col-md-3">Individualizadas: {{this.numIndivi}}</h6>
                                </div>

                                <div class="form-group row" v-if="totalIndividualizadas != 0">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Folio</th>
                                                    <th>Proyecto</th>
                                                    <th>Etapa</th>
                                                    <th>Manzana</th>
                                                    <th>Lote</th>
                                                    <th>Fecha de firma</th>
                                                    <th>$ Restante</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="indiv in arrayIndividualizadas" :key="indiv.folio">
                                                    <td v-text="indiv.folio"></td>
                                                    <td v-text="indiv.fraccionamiento"></td>
                                                    <td v-text="indiv.etapa"></td>
                                                    <td v-text="indiv.manzana"></td>
                                                    <td v-text="indiv.num_lote"></td>
                                                    <td v-text="indiv.fecha_firma_esc"></td>
                                                    <td v-text="'$'+formatNumber(indiv.anticipo)"></td>
                                                </tr>      
                                                <tr>
                                                    <td align="right" colspan="6"><strong>Total:</strong></td>
                                                    <td><strong>${{formatNumber(totalIndividualizadas)}}</strong></td>   
                                                </tr>                       
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            <div class="form-group row line-separator"></div>
                            
                            <!-- CANCELACIONES -->
                                <div class="form-group row">
                                    <h6 v-if="totalAnticipo != 0"  style="font-weight: bold; color: blue;" class="col-md-3">Cancelaciones: {{this.numAnticipos}}</h6>
                                </div>

                                <div class="form-group row" v-if="totalAnticipo != 0">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Folio</th>
                                                    <th>Proyecto</th>
                                                    <th>Etapa</th>
                                                    <th>Manzana</th>
                                                    <th>Lote</th>
                                                    <th>Fecha de cancelación</th>
                                                    <th>$ Anticipo</th>
                                                    <th>Bono</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="anticipo in arrayAnticipos" :key="anticipo.folio">
                                                    <td v-text="anticipo.folio"></td>
                                                    <td v-text="anticipo.fraccionamiento"></td>
                                                    <td v-text="anticipo.etapa"></td>
                                                    <td v-text="anticipo.manzana"></td>
                                                    <td v-text="anticipo.num_lote"></td>
                                                    <td v-text="anticipo.fecha_status"></td>
                                                    <td v-text="'$'+formatNumber(anticipo.anticipo)"></td>
                                                    <td v-if="anticipo.fecha_bono" v-text="'$'+formatNumber(anticipo.bono)"></td>
                                                    <td v-else v-text="'$'+formatNumber(0)"></td>
                                                </tr>      
                                                <tr>
                                                    <td align="right" colspan="6"><strong>Total:</strong></td>
                                                    <td><strong>${{formatNumber(totalAnticipo)}}</strong></td>
                                                    <td><strong>${{formatNumber(totalBono)}}</strong></td>
                                                    
                                                </tr>                       
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            <div class="form-group row line-separator"></div>

                            <!-- DATOS DE VENTA -->

                                <div class="form-group row"  v-if="tipoVendedor == 0">
                                     <div class="col-md-6">
                                        <h6 style="font-weight: bold;" class="col-md-12">Comisión total:&nbsp; $ {{formatNumber(this.comisionMes)}}</h6>
                                    </div>
                                    <div class="col-md-6">
                                        <label style="font-weight: bold;" class="col-md-12">+ Anticipo: &nbsp;$ {{formatNumber(this.anticipoMes)}} </label>
                                        <label style="font-weight: bold;" class="col-md-12">+ Individualizadas: &nbsp;$ {{formatNumber(this.totalIndividualizadas)}} </label>
                                        <label v-if="totalAnticipo != 0" style="font-weight: bold;" class="col-md-12">- Cancelaciones: &nbsp;$ {{formatNumber(this.totalAnticipo)}}</label>
                                        <label v-if="totalBono != 0" style="font-weight: bold;" class="col-md-12">- Bonos cancelados: &nbsp;$ {{formatNumber(this.totalBono)}}</label>
                                        <label style="font-weight: bold;" class="col-md-12">- Apoyo mensual: &nbsp;$ {{formatNumber(this.apoyo)}}</label>
                                        <label v-if="restante != 0" style="font-weight: bold;" class="col-md-12">- Restante anterior: &nbsp;$ {{formatNumber(this.restante)}}</label>
                                        <div class="form-group row line-separator"></div>
                                        <label v-if="modal1 ==1 && tipoVendedor == 0" style="font-weight: bold; color: blue;" class="col-md-12">= A pagar: &nbsp;$ {{formatNumber( this.aPagar = porPagar2)}}</label>
                                    </div>
                                    
                                </div>

                                <div class="form-group row line-separator"></div>

                                <div class="form-group row"  v-if="tipoVendedor == 1">
                                    <div class="col-md-6">
                                        <h6 style="font-weight: bold;" class="col-md-12">Comisión: &nbsp;$ {{formatNumber(this.comisionMes)}}</h6>
                                    </div>
                                    <div class="col-md-6">
                                        <label style="font-weight: bold;" class="col-md-12">+ Anticipo: &nbsp;$ {{formatNumber(this.anticipo)}} </label>
                                        <label v-if="totalAnticipo != 0" style="font-weight: bold;" class="col-md-12">- Cancelaciones: &nbsp;$ {{formatNumber(this.totalAnticipo)}}</label>
                                        <label v-if="restante != 0" style="font-weight: bold;" class="col-md-12">- Restante anterior: &nbsp;$ {{formatNumber(this.restante)}}</label>
                                        <label v-if="modal1 ==1 && tipoVendedor == 1" style="font-weight: bold; color: blue;" class="col-md-12">= A pagar: &nbsp;$ {{formatNumber(this.aPagar =this.anticipo + this.totalIndividualizadas - this.totalAnticipo - this.restante)}}</label>
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
                id:0,
                proceso:false,
                arrayComisiones:[],
                arrayAsesores:[], 
                arrayVentas:[],
                arrayDetalle:[],
                arrayAnticipos:[],
                arrayIndividualizadas:[],
                totalAnticipo:0,
                totalIndividualizadas:0,
                totalBono:0,
                numVentas:'', 
                numIndivi:'', 
                numAnticipos:'', 
                ventas:0,
                bono:0,
                bonoTotal:0,
                numPasadas:0,
                numQuincena:0,
                tipoVendedor:0,
                esquema:2,
                restante:0,
                apoyo:0,
                aPagar:0,
                comision:0,
                anticipo:0,
                comisionMes:0,
                anticipoMes:0,
                sueldoBand:true,
                
                pagination2 : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset2 : 3,
                b_mes : '',
                b_anio : '',
                asesor_id:'',
                b_asesor_id:'',
                modal: 0,
                modal1:0,
                tituloModal: '',
                tipoAccion: 0,
                errorComision : 0,
                errorMostrarMsjComision : [],

                ///
                mes:'',
                anio:'',
               
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

                var from = this.pagination2.current_page - this.offset2;
                if(from < 1){
                    from = 1;
                }

                var to = from + (this.offset2 * 2);
                if(to >= this.pagination2.last_page){
                    to = this.pagination2.last_page;
                }

                var pagesArray2 = [];
                while(from <= to){
                    pagesArray2.push(from);
                    from++;
                }
                return pagesArray2;
            },

            totalComision: function(){
                var totalComision =0.0;
                for(var i=0;i<this.arrayVentas.length;i++){
                    totalComision += parseFloat(this.arrayVentas[i].comision)
                }
                if(totalComision < 0)
                    totalComision = 0;
                totalComision = Math.round(totalComision*100)/100;
                return totalComision;
            },

            anticipoVentas: function(){
                var anticipoVentas =0.0;
                for(var i=0;i<this.arrayVentas.length;i++){
                    anticipoVentas += parseFloat(this.arrayVentas[i].anticipo)
                }
                if(anticipoVentas < 0)
                    anticipoVentas = 0;
                anticipoVentas = Math.round(anticipoVentas*100)/100;
                return anticipoVentas;
            },

            bonoVentas: function(){
                var bonoVentas =0.0;
                for(var i=0;i<this.arrayVentas.length;i++){
                    bonoVentas += parseFloat(this.arrayVentas[i].bono)
                }
                if(bonoVentas < 0)
                    bonoVentas = 0;
                bonoVentas = Math.round(bonoVentas*100)/100;
                return bonoVentas;
            },

            porPagar: function(){
                var porPagar =0.0;
                porPagar = this.anticipo + this.totalIndividualizadas;
                porPagar -= this.totalAnticipo;
                porPagar -= this.totalBono;
                porPagar -= this.apoyo;
                porPagar -=  this.restante;
                return porPagar;
            },

            porPagar2: function(){
                var porPagar =0.0;
                porPagar = this.anticipoMes + this.totalIndividualizadas;
                porPagar -= this.totalAnticipo;
                porPagar -= this.totalBono;
                porPagar -= this.apoyo;
                porPagar -=  this.restante;
                return porPagar;
            },
            
           
        },
       
        methods : {
            listarComisiones(page){
                let me = this;
                var url = '/comision/indexComisiones?page=' + page + '&b_mes=' + this.b_mes + '&b_anio=' + this.b_anio + '&b_asesor_id=' + this.b_asesor_id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayComisiones = respuesta.comisiones.data;
                    me.pagination2 = respuesta.pagination;
                    me.mes = respuesta.month;
                    me.anio = respuesta.year;
                })
                .catch(function (error) {
                    console.log(error);
                })
            },
            ventasAsesor(id){
                let me = this;
                var url = '/comision/ventasAsesor?mes=' + me.mes + '&anio=' + me.anio + '&vendedor=' + id;
                 axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayVentas = respuesta.ventas;
                    me.esquema = respuesta.esquema;
                    me.tipoVendedor = respuesta.tipo;
                    me.numVentas = respuesta.numVentas;
                    me.numAnticipos = respuesta.numAnticipos;
                    me.numIndivi = respuesta.numIndivi;
                    me.numPasadas = respuesta.pasada;
                    me.numQuincena = respuesta.quincena;
                    me.arrayAnticipos = respuesta.anticiposCan;
                    me.arrayIndividualizadas = respuesta.individualizadas;
                    me.totalAnticipo = respuesta.totalAnticipo;
                    me.totalIndividualizadas = respuesta.totalIndividualizadas;
                    me.totalBono = respuesta.totalBono;
                    me.bono = 0;
                    me.apoyo = 0;
                    me.aPagar = 0;
                    me.restante = respuesta.restante;
                    me.sueldoBand = respuesta.sueldo;

                    if(me.tipoVendedor == 0){
                        if(me.sueldoBand)
                            me.apoyo = 10500;
                        else{
                            me.apoyo = 0;
                        }
                        if(me.numVentas>=3 && me.numPasadas>=2 && me.numQuincena>=1){
                            me.bono =2000;
                        }
                        else{
                            me.bono = 1000;
                        }

                        if(me.num_ventas == 0){
                            me.bono = 0;
                        }
                    }
                    
                })
                .catch(function (error) {
                    console.log(error);
                })
            },
            registrarComision(){
                if( this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;

                let me = this;
                //Con axios se llama el metodo store de PersonalController
                axios.post('/comision/storeComision',{
                    'mes': this.mes,
                    'anio': this.anio,
                    'total': this.comision,
                    'num_ventas': this.numVentas,
                    'num_individualizadas': this.numIndivi,
                    'num_cancelaciones': this.numAnticipos,
                    'bono': this.bonoTotal,
                    'anticipo' : this.anticipo,
                    'asesor_id': this.asesor_id,
                    'data':this.arrayVentas,
                    'aPagar':this.aPagar,
                    'tipoVendedor' : this.tipoVendedor,
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarComisiones(1); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Comision creada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
            cambiarPagina2(page){
                let me = this;
                //Actualiza la pagina actual
                me.pagination2.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarComisiones(page);
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

            detalleComision(id, mes, anio){
                let me = this;
                var url = '/comision/detalleComision?id=' + id + '&mes=' + mes + '&anio=' + anio;
                 axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayDetalle = respuesta.detalle;

                    me.numAnticipos = respuesta.numAnticipos;
                    me.numIndivi = respuesta.numIndivi;
                    me.arrayAnticipos = respuesta.anticiposCan;
                    me.arrayIndividualizadas = respuesta.individualizadas;
                    me.totalAnticipo = respuesta.totalAnticipo;
                    me.totalIndividualizadas = respuesta.totalIndividualizadas;
                    me.totalBono = respuesta.totalBono;
                    me.restante = respuesta.restante;
                    
                })
                .catch(function (error) {
                    console.log(error);
                })
            },

            cerrarModal(){
                this.modal = 0;
                this.modal1 = 0;
                this.tituloModal = '';
                this.asesor_id = '';
                this.mes = '';
                this.arrayVentas = [];
                this.total = 0;
                this.bono = 0;
                this.numVentas = 0;
                this.comision = 0;
                this.anticipo = 0;
                this.comisionMes = 0;
                this.anticipoMes = 0;
                this.aPagar = '0';
                this.restante = 0;

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

            abrirModal(accion,data =[]){
                switch(accion){
                    case 'nuevo':
                    {
                        this.modal = 1;
                        this.tituloModal='Comisión';
                        this.ventas = 0;
                        this.mes = '';
                        this.bonoTotal = 0;
                        this.aPagar = 0;
                        this.restante = 0;
                        break;
                    }
                    case 'detalle':
                    {
                        this.bono = data['bono'];
                        this.numVentas = data['num_ventas'];
                        this.tipoVendedor = data['tipo'];
                        if(this.tipoVendedor == 0)
                        {
                            this.apoyo = 10500;
                        }
                        this.mes = data['mes'];
                        this.anio = data['anio'];
                        this.detalleComision(data['id'], this.mes, this.anio);
                        this.aPagar = data['aPagar'];
                        this.comisionMes = data['total'];
                        this.anticipoMes = data['totalAnticipo'];
                        this.modal1 = 1;
                        this.tituloModal='Detalle comision';
                        break;
                    }
                }
            }
        
        },
        mounted() {          
            this.listarComisiones(1);
            this.selectAsesores();
        }
    }
</script>
<style>

    .modal-content{
        width: 100% !important;
        position: absolute !important;
       
    }
    .modal-body{
        height: 450px;
        width: 100%;
        overflow-y: auto;
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
    @media (min-width: 600px){
        .btnagregar{
        margin-top: 2rem;
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
    .badge2 {
        display: inline-block;
        padding: 0.25em 0.4em;
        font-size: 85%;
        font-weight: bold;
        line-height: 1;
        color: #151b1f;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
    }
    .line-separator{
        height:1px;
        background:#717171;
        border-bottom:1px solid #c2cfd6;
    }
}
</style>
