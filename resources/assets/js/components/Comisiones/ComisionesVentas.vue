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
                                            <th>Total Comisión</th>
                                            <th>Total Pagado</th>
                                            <!-- <th>Total por Pagar</th> -->
                                            
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
                                            <td class="td2" v-text="'$'+formatNumber(contrato.total_comision)"></td>
                                            <td class="td2" v-text="'$'+formatNumber(contrato.total_pagado)"></td>
                                            <!-- <td class="td2" v-text="'$'+formatNumber(contrato.total_porPagar)"></td> -->
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
                                    <button type="button" @click="getComision(asesor_id), ventas = 1" class="btn btn-primary">
                                        <i class="fa fa-search-plus"></i> &nbsp;Buscar ventas
                                    </button>
                                </div>
                            </div>

                            <div class="form-group row line-separator"></div>

                            <!-- VENTAS -->
                                <div class="form-group row">
                                    <h6 v-if="ventas==1 && numVentas>0"  style="font-weight: bold; color: blue;" class="col-md-3">Ventas: {{this.numVentas}}</h6>
                                </div>

                                <div class="form-group row" v-if="ventas==1 && tipoVendedor == 0 && numVentas>0">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Folio</th>
                                                    <th>Proyecto</th>
                                                    <th>Etapa</th>
                                                    <th>Manzana</th>
                                                    <th>Lote</th>
                                                    <th>Cliente</th>
                                                    <th>Precio Venta</th>
                                                    <th>Crédito</th>
                                                    <th>% Avance</th>
                                                    <th>% De comisión</th>
                                                    <th>Comisión a pagar</th>
                                                    <th>Este pago</th>
                                                    <th>Por pagar</th>
                                                    <!-- <th>Observaciones</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="venta in arrayVentas" :key="venta.id">
                                                    <td class="td2" v-text="venta.fecha"></td>
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
                                                    <td class="td2" v-text="venta.nombre_cliente"></td>
                                                    <td v-text="'$'+formatNumber(venta.precio_venta)"></td>
                                                    <td class="td2" v-text="venta.tipo_credito + '(' + venta.institucion + ')'"></td>
                                                    <td v-text="venta.avance_lote + '%'"></td>
                                                    <!--- Calculo de la comision que corresponde segun ventas y avance ----->
                                                        <template v-if="numVentas == 1">
                                                            <td v-if="venta.avance_lote<=90">
                                                                {{ venta.porcentaje_comision = (venta.extra + (0.80*(venta.porcentaje_exp/100)) ).toFixed(3) }}%
                                                            </td>
                                                            <td v-else>
                                                                {{ venta.porcentaje_comision = (venta.extra + ((venta.porcentaje_exp/100)*1) ).toFixed(3) }}%
                                                            </td>
                                                        </template>
                                                        <template v-if="numVentas == 2">
                                                            <td v-if="venta.avance_lote<=90">
                                                                {{venta.porcentaje_comision = (venta.extra + (1.00*(venta.porcentaje_exp/100)) ).toFixed(3)}}%
                                                            </td>
                                                            <td v-else>
                                                                {{venta.porcentaje_comision = (venta.extra + ((venta.porcentaje_exp/100)*1.25) ).toFixed(3)}}%
                                                            </td>
                                                        </template>
                                                        <template v-if="numVentas == 3">
                                                            <td v-if="venta.avance_lote<=90">
                                                                {{venta.porcentaje_comision = (venta.extra + (1.30*(venta.porcentaje_exp/100)) ).toFixed(3)}}%
                                                            </td>
                                                            <td v-else>
                                                                {{venta.porcentaje_comision = (venta.extra + ((venta.porcentaje_exp/100)*1.55) ).toFixed(3)}}%
                                                            </td>
                                                        </template>
                                                        <template v-if="numVentas == 4">
                                                            <td v-if="venta.avance_lote<=90">
                                                                {{venta.porcentaje_comision = (venta.extra + (1.50*(venta.porcentaje_exp/100)) ).toFixed(3)}}%
                                                            </td>
                                                            <td v-else>
                                                                {{venta.porcentaje_comision = (venta.extra + ((venta.porcentaje_exp/100)*1.75) ).toFixed(3)}}%
                                                            </td>

                                                        </template>
                                                        <template v-if="numVentas == 5">
                                                            <td v-if="venta.avance_lote<=90">
                                                                {{venta.porcentaje_comision = (venta.extra + (1.70*(venta.porcentaje_exp/100)) ).toFixed(3)}}%
                                                            </td>
                                                            <td v-else>
                                                                {{venta.porcentaje_comision = (venta.extra + ((venta.porcentaje_exp/100)*2.00) ).toFixed(3)}}%
                                                            </td>
                                                        </template>
                                                        <template v-if="numVentas >= 6">
                                                            <td v-if="venta.avance_lote<=90">
                                                                {{venta.porcentaje_comision = (venta.extra + (2.00*(venta.porcentaje_exp/100)) ).toFixed(3)}}%
                                                            </td>
                                                            <td v-else>
                                                                {{venta.porcentaje_comision = (venta.extra + ((venta.porcentaje_exp/100)*2.00) ).toFixed(3)}}%
                                                            </td>
                                                        </template>
                                                    <!----------------------->
                                                    <td v-text="'$'+formatNumber(venta.comision_pagar = (venta.precio_venta * (venta.porcentaje_comision/100)))"></td>
                                                    
                                                    <!--- Verifica si la venta esta individualizada para determinar de cuanto sera el pago --->
                                                        <td v-if="venta.indiv == 0 && venta.pagado > 1" v-text="'$'+formatNumber(venta.este_pago = (venta.comision_pagar / 2))"></td>
                                                        <td v-else-if="venta.indiv == 1 && venta.pagado > 1" v-text="'$'+formatNumber(venta.este_pago = (venta.comision_pagar))"></td>
                                                        <td v-else-if="venta.pagado < 2" v-text="'$'+formatNumber(venta.este_pago = 0)"></td>
                                                    <!------->
                                                        <td v-if="venta.indiv == 0 && venta.pagado > 1" v-text="'$'+formatNumber(venta.por_pagar = (venta.comision_pagar / 2))"></td>
                                                        <td v-else-if="venta.indiv == 1 && venta.pagado > 1" v-text="'$'+formatNumber(venta.por_pagar = 0)"></td>
                                                        <td v-else-if="venta.pagado < 2" v-text="'$'+formatNumber(venta.por_pagar = (venta.comision_pagar))"></td>
                                                </tr>  
                                                <tr>
                                                    <td align="right" colspan="11"><strong>Total: </strong></td>
                                                    <td> ${{formatNumber(total_comision = totalComision)}} </td>
                                                    <td><strong> ${{formatNumber(total_pagado = totalPagado)}} </strong></td>
                                                    <td> ${{formatNumber(total_porPagar = totalPorPagar)}} </td>
                                                    
                                                    
                                                </tr>                     
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="form-group row" v-else-if="ventas==1 && tipoVendedor == 1 && numVentas>0">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Folio</th>
                                                    <th>Proyecto</th>
                                                    <th>Etapa</th>
                                                    <th>Manzana</th>
                                                    <th>Lote</th>
                                                    <th>Cliente</th>
                                                    <th>Precio Venta</th>
                                                    <th>Crédito</th>
                                                    <th>% Avance</th>
                                                    <th>% De comisión</th>
                                                    <th>Comisión a pagar</th>
                                                    <th>IVA</th>
                                                    <th>Retención</th>
                                                    <th>ISR</th>
                                                    
                                                    <th>Este pago</th>
                                                    <th>Por pagar</th>
                                                    <!-- <th>Observaciones</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="venta in arrayVentas" :key="venta.id">
                                                    <td class="td2" v-text="venta.fecha"></td>
                                                    <td v-text="venta.id"></td>
                                                    <td class="td2" v-text="venta.proyecto"></td>
                                                    <td class="td2" v-text="venta.etapa"></td>
                                                    <td class="td2" v-text="venta.manzana"></td>
                                                    <td v-text="venta.num_lote"></td>
                                                    <td class="td2" v-text="venta.nombre_cliente"></td>
                                                    <td v-text="'$'+formatNumber(venta.precio_venta)"></td>
                                                    <td class="td2" v-text="venta.tipo_credito + '(' + venta.institucion + ')'"></td>
                                                    <td v-text="venta.avance_lote + '%'"></td>
                                                    <td>
                                                        {{ venta.porcentaje_comision = (venta.extra_ext + esquema).toFixed(1) }}%
                                                    </td>
                                                    <td v-text="'$'+formatNumber(venta.comision_pagar = (venta.precio_venta * (venta.porcentaje_comision/100)))"></td>
                                                    <td v-text="'$'+formatNumber(venta.iva = (venta.comision_pagar * 0.16 ))"></td>
                                                    <td v-if="bandRetencion == 1" v-text="'$'+formatNumber(venta.retencion = (venta.iva * (2/3)))"></td>
                                                    <td v-else v-text="'$'+formatNumber(venta.retencion = 0)"></td>
                                                    <td v-if="bandISR == 1" v-text="'$'+formatNumber(venta.isr = (venta.comision_pagar * .10 ))"></td>
                                                    <td v-else v-text="'$'+formatNumber(venta.isr = 0 )"></td>
                                                    
                                                    
                                                    <!--- Verifica si la venta esta individualizada para determinar de cuanto sera el pago --->
                                                        <td v-if="venta.indiv == 0 && venta.pagado > 1" v-text="'$'+formatNumber(venta.este_pago = (venta.comision_pagar / 2) + venta.iva - venta.isr - venta.retencion)"></td>
                                                        <td v-else-if="venta.indiv == 1 && venta.pagado > 1" v-text="'$'+formatNumber(venta.este_pago = (venta.comision_pagar) + venta.iva - venta.isr - venta.retencion )"></td>
                                                        <td v-else-if="venta.pagado < 2" v-text="'$'+formatNumber(venta.este_pago = 0)"></td>
                                                    <!------->
                                                        <td v-if="venta.indiv == 0 && venta.pagado > 1" v-text="'$'+formatNumber(venta.por_pagar = (venta.comision_pagar / 2))"></td>
                                                        <td v-else-if="venta.indiv == 1 && venta.pagado > 1" v-text="'$'+formatNumber(venta.por_pagar = 0)"></td>
                                                        <td v-else-if="venta.pagado < 2" v-text="'$'+formatNumber(venta.por_pagar = (venta.comision_pagar) + venta.iva - venta.isr - venta.retencion )"></td>
                                                </tr>      
                                                <tr>
                                                    <td align="right" colspan="11"><strong>Total:</strong></td>
                                                    <td> ${{formatNumber(total_comision = totalComision)}} </td>
                                                    <td> ${{formatNumber(total_iva = totalIva)}} </td>
                                                    <td> ${{formatNumber(total_retencion = totalRetencion)}} </td>
                                                    <td> ${{formatNumber(total_isr = totalIsr)}} </td>
                                                    <td><strong> ${{formatNumber(total_pagado = totalPagado)}} </strong></td>
                                                    <td> ${{formatNumber(total_porPagar = totalPorPagar)}} </td>
                                                    
                                                </tr>                       
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            
                            <!-- Individualizadas -->
                                <div class="form-group row">
                                    <h6 v-if="ventas==1 && numIndividualizadas != 0"  style="font-weight: bold; color: blue;" class="col-md-3">Individualizadas: {{this.numIndividualizadas }}</h6>
                                </div>

                                <div class="form-group row" v-if="ventas==1 && numIndividualizadas != 0 && tipoVendedor == 0">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Folio</th>
                                                    <th>Proyecto</th>
                                                    <th>Etapa</th>
                                                    <th>Manzana</th>
                                                    <th>Lote</th>
                                                    <th>Cliente</th>
                                                    <th>Precio Venta</th>
                                                    <th>Crédito</th>
                                                    <th>% Avance</th>
                                                    <th>% De comisión</th>
                                                    <th>Comisión a pagar</th>
                                                    
                                                    <th>Este pago</th>
                                                    <th>Por pagar</th>
                                                    <!-- <th>Observaciones</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="indiv in arrayIndividualizadas" :key="indiv.id">
                                                    <td class="td2" v-text="indiv.fecha"></td>
                                                    <td v-text="indiv.id"></td>
                                                    <td class="td2" v-text="indiv.proyecto"></td>
                                                    <td class="td2" v-text="indiv.etapa"></td>
                                                    <td class="td2" v-text="indiv.manzana"></td>
                                                    <td v-text="indiv.num_lote"></td>
                                                    <td class="td2" v-text="indiv.nombre_cliente"></td>
                                                    <td v-text="'$'+formatNumber(indiv.precio_venta)"></td>
                                                    <td class="td2" v-text="indiv.tipo_credito + '(' + indiv.institucion + ')'"></td>
                                                    <td v-text="indiv.avance_lote + '%'"></td>
                                                    <td v-text="indiv.porcentaje_comision + '%'"></td>
                                                    <td v-text="'$'+formatNumber(indiv.comision_pagar)"></td>
                                                    
                                                    <td v-text="'$'+formatNumber(indiv.por_pagar)"></td>
                                                    <td v-text="'$'+formatNumber(0)"></td>
                                                </tr>      
                                                <tr>
                                                    <td align="right" colspan="11"><strong>Total: </strong></td>
                                                    <td> ${{formatNumber(total_comision_indiv = totalComisionIndiv)}} </td>
                                                    <td><strong> ${{formatNumber(total_pagado_indiv = totalPagadoIndiv)}} </strong></td>
                                                    <td> ${{formatNumber(0)}} </td>
                                                </tr>                       
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="form-group row" v-else-if="ventas==1 && tipoVendedor == 1 && numIndividualizadas>0">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Folio</th>
                                                    <th>Proyecto</th>
                                                    <th>Etapa</th>
                                                    <th>Manzana</th>
                                                    <th>Lote</th>
                                                    <th>Cliente</th>
                                                    <th>Precio Venta</th>
                                                    <th>Crédito</th>
                                                    <th>% Avance</th>
                                                    <th>% De comisión</th>
                                                    <th>Comisión a pagar</th>
                                                    <th>IVA</th>
                                                    <th>Retención</th>
                                                    <th>ISR</th>
                                                    
                                                    <th>Este pago</th>
                                                    <th>Por pagar</th>
                                                    <!-- <th>Observaciones</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="indiv in arrayIndividualizadas" :key="indiv.id">
                                                    <td class="td2" v-text="indiv.fecha"></td>
                                                    <td v-text="indiv.id"></td>
                                                    <td class="td2" v-text="indiv.proyecto"></td>
                                                    <td class="td2" v-text="indiv.etapa"></td>
                                                    <td class="td2" v-text="indiv.manzana"></td>
                                                    <td v-text="indiv.num_lote"></td>
                                                    <td class="td2" v-text="indiv.nombre_cliente"></td>
                                                    <td v-text="'$'+formatNumber(indiv.precio_venta)"></td>
                                                    <td class="td2" v-text="indiv.tipo_credito + '(' + indiv.institucion + ')'"></td>
                                                    <td v-text="indiv.avance_lote + '%'"></td>
                                                    <td v-text="indiv.porcentaje_comision + '%'"></td>
                                                    <td v-text="'$'+formatNumber(indiv.comision_pagar)"></td>
                                                    <td v-text="'$'+formatNumber(indiv.iva)"></td>
                                                    <td v-text="'$'+formatNumber(indiv.retencion)"></td>
                                                    <td v-text="'$'+formatNumber(indiv.isr)"></td>
                                                    
                                                    <td v-text="'$'+formatNumber(indiv.por_pagar)"></td>
                                                    <td v-text="'$'+formatNumber(0)"></td>
                                                    
                                                </tr>      
                                                <tr>
                                                    <td align="right" colspan="11"><strong>Total:</strong></td>
                                                    <td> ${{formatNumber(total_comision_indiv = totalComisionIndiv)}} </td>
                                                    <td> ${{formatNumber(total_iva_indiv  = totalIvaIndiv)}} </td>
                                                    <td> ${{formatNumber(total_retencion_indiv  = totalRetencionIndiv)}} </td>
                                                    <td> ${{formatNumber(total_isr_indiv  = totalIsrIndiv)}} </td>
                                                    <td><strong> ${{formatNumber(total_pagado_indiv = totalPagadoIndiv)}} </strong></td>
                                                    <td> ${{formatNumber(0)}} </td>
                                                    
                                                </tr>                       
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            <div class="form-group row line-separator"  v-if="ventas==1"></div>
                            
                            <!-- VENTAS CANCELADAS -->
                                <div class="form-group row">
                                    <h6 v-if="ventas==1 & numCancelaciones != 0"  style="font-weight: bold; color: blue;" class="col-md-3">Canceladas: {{this.numCancelaciones}}</h6>
                                </div>

                                <div class="form-group row" v-if="ventas==1 && numCancelaciones != 0">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Folio</th>
                                                    <th>Proyecto</th>
                                                    <th>Etapa</th>
                                                    <th>Manzana</th>
                                                    <th>Lote</th>
                                                    <th>Cliente</th>
                                                    <th>Fecha de cancelación</th>
                                                    <th>$ Anticipo</th>
                                                    <th>Bono</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="anticipo in arrayCanceladas" :key="anticipo.folio">
                                                    <td v-text="anticipo.id"></td>
                                                    <td class="td2" v-text="anticipo.proyecto"></td>
                                                    <td class="td2" v-text="anticipo.etapa"></td>
                                                    <td class="td2" v-text="anticipo.manzana"></td>
                                                    <td v-text="anticipo.num_lote"></td>
                                                    <td class="td2" v-text="anticipo.nombre_cliente"></td>
                                                    <td v-text="anticipo.fecha_status"></td>
                                                    <td v-text="'$'+formatNumber(anticipo.este_pago)"></td>
                                                    <td v-text="'$'+formatNumber(anticipo.bono)"></td>
                                                </tr>      
                                                <tr>
                                                    <td align="right" colspan="7"><strong>Total: </strong></td>
                                                    <td> ${{formatNumber(total_anticipo = totalAnticipo)}} </td>
                                                    <td> ${{formatNumber(total_bono = totalBono)}} </td>
                                                    
                                                </tr>                       
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            <!-- COMISIONES QUE HABIAN QUEDADO PENDIENTES POR FALTA DE PAGARE -->
                                <div class="form-group row">
                                    <h6 v-if="ventas==1 & numPendientes != 0"  style="font-weight: bold; color: blue;" class="col-md-3">Pendientes: {{this.numPendientes}}</h6>
                                </div>

                                <div class="form-group row" v-if="ventas==1 && numPendientes != 0 && tipoVendedor == 0">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Folio</th>
                                                    <th>Proyecto</th>
                                                    <th>Etapa</th>
                                                    <th>Manzana</th>
                                                    <th>Lote</th>
                                                    <th>Cliente</th>
                                                    <th>Precio Venta</th>
                                                    <th>Crédito</th>
                                                    <th>% Avance</th>
                                                    <th>% De comisión</th>
                                                    <th>Comisión a pagar</th>
                                                    <th>Este pago</th>
                                                    <th>Por pagar</th>
                                                    <!-- <th>Observaciones</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="pendiente in arrayPendientes" :key="pendiente.id">
                                                    <td class="td2" v-text="pendiente.fecha"></td>
                                                    <td v-text="pendiente.id"></td>
                                                    <td class="td2" v-text="pendiente.proyecto"></td>
                                                    <td class="td2" v-text="pendiente.etapa"></td>
                                                    <td class="td2" v-text="pendiente.manzana"></td>
                                                    <td v-text="pendiente.num_lote"></td>
                                                    <td class="td2" v-text="pendiente.nombre_cliente"></td>
                                                    <td v-text="'$'+formatNumber(pendiente.precio_venta)"></td>
                                                    <td class="td2" v-text="pendiente.tipo_credito + '(' + pendiente.institucion + ')'"></td>
                                                    <td v-text="pendiente.avance_lote + '%'"></td>
                                                    <td v-text="pendiente.porcentaje_comision + '%'"></td>
                                                    <td v-text="'$'+formatNumber(pendiente.comision_pagar)"></td>
                                                    
                                                    <td v-if="pendiente.indiv == 0" v-text="'$'+formatNumber(pendiente.este_pago = (pendiente.comision_pagar / 2) )"></td>
                                                    <td v-else v-text="'$'+formatNumber(pendiente.este_pago = pendiente.comision_pagar )"></td>
                                                    <td v-if="pendiente.indiv == 0" v-text="'$'+formatNumber(pendiente.por_pagar = (pendiente.comision_pagar / 2) )"></td>
                                                    <td v-else v-text="'$'+formatNumber(pendiente.por_pagar=0)"></td>
                                                </tr>      
                                                <tr>
                                                    <td align="right" colspan="11"><strong>Total: </strong></td>
                                                    <td> ${{formatNumber(total_comision_pendiente = totalComisionPendiente)}} </td>
                                                    <td><strong> ${{formatNumber(total_pagado_pendiente = totalPagadoPendiente)}} </strong></td>
                                                    <td> ${{formatNumber(total_porPagar_pendiente = totalPorPagarPendiente)}} </td>
                                                      
                                                </tr>                       
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="form-group row" v-if="ventas==1 && numPendientes != 0 && tipoVendedor == 1">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Folio</th>
                                                    <th>Proyecto</th>
                                                    <th>Etapa</th>
                                                    <th>Manzana</th>
                                                    <th>Lote</th>
                                                    <th>Cliente</th>
                                                    <th>Precio Venta</th>
                                                    <th>Crédito</th>
                                                    <th>% Avance</th>
                                                    <th>% De comisión</th>
                                                    <th>Comisión a pagar</th>
                                                    <th>IVA</th>
                                                    <th>Retención</th>
                                                    <th>ISR</th>
                                                    
                                                    <th>Este pago</th>
                                                    <th>Por pagar</th>
                                                    <!-- <th>Observaciones</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="pendiente in arrayPendientes" :key="pendiente.id">
                                                    <td class="td2" v-text="pendiente.fecha"></td>
                                                    <td v-text="pendiente.id"></td>
                                                    <td class="td2" v-text="pendiente.proyecto"></td>
                                                    <td class="td2" v-text="pendiente.etapa"></td>
                                                    <td class="td2" v-text="pendiente.manzana"></td>
                                                    <td v-text="pendiente.num_lote"></td>
                                                    <td class="td2" v-text="pendiente.nombre_cliente"></td>
                                                    <td v-text="'$'+formatNumber(pendiente.precio_venta)"></td>
                                                    <td class="td2" v-text="pendiente.tipo_credito + '(' + pendiente.institucion + ')'"></td>
                                                    <td v-text="pendiente.avance_lote + '%'"></td>
                                                    <td v-text="pendiente.porcentaje_comision + '%'"></td>
                                                    <td v-text="'$'+formatNumber(pendiente.comision_pagar)"></td>
                                                    <td v-text="'$'+formatNumber(pendiente.iva)"></td>
                                                    <td v-text="'$'+formatNumber(pendiente.retencion)"></td>
                                                    <td v-text="'$'+formatNumber(pendiente.isr)"></td>
                                                    
                                                    <td v-if="pendiente.indiv == 0" v-text="'$'+formatNumber(pendiente.este_pago = (pendiente.comision_pagar / 2) + pendiente.iva - pendiente.isr - pendiente.retencion)"></td>
                                                    <td v-else v-text="'$'+formatNumber(pendiente.este_pago = (pendiente.comision_pagar) + pendiente.iva - pendiente.isr - pendiente.retencion )"></td>
                                                    <td v-if="pendiente.indiv == 0" v-text="'$'+formatNumber(pendiente.por_pagar = (pendiente.comision_pagar / 2) )"></td>
                                                    <td v-else v-text="'$'+formatNumber(pendiente.por_pagar=0)"></td>
                                                </tr>      
                                                <tr>
                                                    <td align="right" colspan="11"><strong>Total:</strong></td>
                                                    <td> ${{formatNumber(total_comision_pendiente = totalComisionPendiente)}} </td>
                                                    <td> ${{formatNumber(total_iva_pendiente = totalIvaPendiente)}} </td>
                                                    <td> ${{formatNumber(total_retencion_pendiente = totalRetencionPendiente)}} </td>
                                                    <td> ${{formatNumber(total_isr_pendiente = totalIsrPendiente)}} </td>
                                                    <td><strong> ${{formatNumber(total_pagado_pendiente = totalPagadoPendiente)}} </strong></td>
                                                    <td> ${{formatNumber(total_porPagar_pendiente = totalPorPagarPendiente)}} </td>
                                                      
                                                </tr>                       
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            <!-- CAMBIOS -->
                                <div class="form-group row">
                                    <h6 v-if="ventas==1 & numCambios != 0"  style="font-weight: bold; color: blue;" class="col-md-3">Cambios: {{this.numCambios}}</h6>
                                </div>

                                <div class="form-group row" v-if="ventas==1 && numCambios != 0 && tipoVendedor == 0">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Folio</th>
                                                    <th>Proyecto</th>
                                                    <th>Etapa</th>
                                                    <th>Manzana</th>
                                                    <th>Lote</th>
                                                    <th>Cliente</th>
                                                    <th>Precio Venta</th>
                                                    <th>Crédito</th>
                                                    <th>% Avance</th>
                                                    <th>% De comisión</th>
                                                    <th>Comisión a pagar</th>
                                                    <th>Este pago</th>
                                                    <th>Por pagar</th>
                                                    <th>Anticipo anterior (-)</th>
                                                    <th></th>
                                                    <!-- <th>Observaciones</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="cambio in arrayCambios" :key="cambio.id">
                                                    <td class="td2" v-text="cambio.fecha"></td>
                                                    <td v-text="cambio.id"></td>
                                                    <td class="td2" v-text="cambio.proyecto_ant + ' -> ' + cambio.proyecto"></td>
                                                    <td class="td2" v-text="cambio.etapa_ant + ' -> ' + cambio.etapa"></td>
                                                    <td class="td2" v-text="cambio.manzana_ant + ' -> ' + cambio.manzana"></td>
                                                    <td class="td2" v-text="cambio.lote_ant + ' -> ' + cambio.num_lote"></td>
                                                    <td class="td2" v-text="cambio.nombre_cliente"></td>
                                                    <td class="td2" v-text="'$'+formatNumber(cambio.precio_venta_ant) + ' -> ' + formatNumber(cambio.precio_venta)"></td>
                                                    <td class="td2" v-text="cambio.tipo_credito + '(' + cambio.institucion + ')'"></td>
                                                    <td v-text="cambio.avance_lote + '%'"></td>
                                                    <td v-text="cambio.porcentaje_comision + '%'"></td>
                                                    <td class="td2" v-text="'$'+formatNumber(cambio.comision_pagar = (cambio.precio_venta * (cambio.porcentaje_comision/100) ) )"></td>
                                                    
                                                    <td v-if="cambio.indiv == 0" v-text="'$'+formatNumber(cambio.este_pago = (cambio.comision_pagar / 2) )"></td>
                                                    <td v-else v-text="'$'+formatNumber(cambio.este_pago = cambio.comision_pagar )"></td>
                                                    <td v-if="cambio.indiv == 0" v-text="'$'+formatNumber(cambio.por_pagar = (cambio.comision_pagar / 2) )"></td>
                                                    <td v-else v-text="'$'+formatNumber(cambio.por_pagar=0)"></td>
                                                    <td v-text="'$'+formatNumber(cambio.pago_ant)"></td>
                                                    <td>
                                                        <button title="No aplica cambio" type="button" class="btn btn-danger btn-sm" @click="noAplica(cambio.id)">
                                                            <i class="fa fa-close"></i>
                                                        </button>
                                                    </td>
                                                </tr>      
                                                <tr>
                                                    <td align="right" colspan="11"><strong>Total: </strong></td>
                                                    <td> ${{formatNumber(total_comision_cambio = totalComisionCambio)}} </td>
                                                    <td><strong> ${{formatNumber(total_pagado_cambio = totalPagadoCambio)}} </strong></td>
                                                    <td> ${{formatNumber(total_porPagar_cambio = totalPorPagarCambio)}} </td>
                                                    <td colspan="2"> ${{formatNumber(total_anticipo_cambio = totalAnticipoCambio)}} </td>
                                                      
                                                </tr>                       
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="form-group row" v-if="ventas==1 && numPendientes != 0 && tipoVendedor == 1">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Folio</th>
                                                    <th>Proyecto</th>
                                                    <th>Etapa</th>
                                                    <th>Manzana</th>
                                                    <th>Lote</th>
                                                    <th>Cliente</th>
                                                    <th>Precio Venta</th>
                                                    <th>Crédito</th>
                                                    <th>% Avance</th>
                                                    <th>% De comisión</th>
                                                    <th>Comisión a pagar</th>
                                                    <th>IVA</th>
                                                    <th>Retención</th>
                                                    <th>ISR</th>
                                                    
                                                    <th>Este pago</th>
                                                    <th>Por pagar</th>
                                                    <th>Anticipo anterior (-)</th>
                                                    <th></th>
                                                    <!-- <th>Observaciones</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="cambio in arrayCambios" :key="cambio.id">
                                                    <td class="td2" v-text="cambio.fecha"></td>
                                                    <td v-text="cambio.id"></td>
                                                    <td class="td2" v-text="cambio.proyecto_ant + '->' + cambio.proyecto"></td>
                                                    <td class="td2" v-text="cambio.etapa_ant + '->' + cambio.etapa"></td>
                                                    <td class="td2" v-text="cambio.manzana_ant + '->' + cambio.manzana"></td>
                                                    <td v-text="cambio.lote_ant + '->' + cambio.num_lote"></td>
                                                    <td class="td2" v-text="cambio.nombre_cliente"></td>
                                                    <td v-text="'$'+formatNumber(cambio.precio_venta)"></td>
                                                    <td class="td2" v-text="cambio.tipo_credito + '(' + cambio.institucion + ')'"></td>
                                                    <td v-text="cambio.avance_lote + '%'"></td>
                                                    <td v-text="cambio.porcentaje_comision + '%'"></td>
                                                    <td class="td2" v-text="'$'+formatNumber(cambio.comision_pagar = (cambio.precio_venta * (cambio.porcentaje_comision/100) ) )"></td>
                                                    <td v-text="'$'+formatNumber(cambio.iva = cambio.comision_pagar * 0.16)"></td>
                                                    <td v-if="bandRetencion == 1" v-text="'$'+formatNumber(cambio.retencion = (cambio.iva * (2/3)))"></td>
                                                    <td v-else v-text="'$'+formatNumber(cambio.retencion = 0)"></td>
                                                    <td v-if="bandISR == 1" v-text="'$'+formatNumber(cambio.isr = (cambio.comision_pagar * .10 ))"></td>
                                                    <td v-else v-text="'$'+formatNumber(cambio.isr = 0 )"></td>
                                                    
                                                    <td v-if="pendiente.indiv == 0" v-text="'$'+formatNumber(cambio.este_pago = (cambio.comision_pagar / 2) + cambio.iva - cambio.isr - cambio.retencion)"></td>
                                                    <td v-else v-text="'$'+formatNumber(cambio.este_pago = (cambio.comision_pagar) + cambio.iva - cambio.isr - cambio.retencion )"></td>
                                                    <td v-if="cambio.indiv == 0" v-text="'$'+formatNumber(cambio.por_pagar = (cambio.comision_pagar / 2) )"></td>
                                                    <td v-else v-text="'$'+formatNumber(cambio.por_pagar=0)"></td>
                                                    <td v-text="'$'+formatNumber(cambio.pago_ant)"></td>
                                                    <td>
                                                        <button title="No aplica cambio" type="button" class="btn btn-danger btn-sm" @click="noAplica(cambio.id)">
                                                            <i class="fa fa-close"></i>
                                                        </button>
                                                    </td>
                                                </tr>      
                                                <tr>
                                                    <td align="right" colspan="11"><strong>Total:</strong></td>
                                                     <td> ${{formatNumber(total_comision_cambio = totalComisionCambio)}} </td>
                                                     <td><strong> ${{formatNumber(total_iva_cambio = totalIvaCambio)}} </strong></td>
                                                     <td><strong> ${{formatNumber(total_retencion_cambio = totalRetencionCambio)}} </strong></td>
                                                     <td><strong> ${{formatNumber(total_isr_cambio = totalIsrCambio)}} </strong></td>
                                                    <td><strong> ${{formatNumber(total_pagado_cambio = totalPagadoCambio)}} </strong></td>
                                                    <td> ${{formatNumber(total_porPagar_cambio = totalPorPagarCambio)}} </td>
                                                    <td colspan="2"> ${{formatNumber(total_anticipo_cambio = totalAnticipoCambio)}} </td>
                                                      
                                                </tr>                       
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            <!-- <div class="form-group row line-separator"  v-if="ventas==1"></div> -->

                            <!-- DATOS DE VENTA -->

                                <div class="form-group row"  v-if="ventas==1 && tipoVendedor == 0">
                                    
                                    <div class="col-md-8">

                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th> Total </th>
                                                    <td>  $ {{formatNumber(total = total_pagado + total_pagado_indiv + total_pagado_pendiente + total_pagado_cambio)}} </td>
                                                </tr>
                                                <tr>
                                                    <th>- Ventas canceladas </th>
                                                    <td>  $ {{formatNumber(total_anticipo)}} </td>
                                                </tr>
                                                <tr v-if="total_anticipo_cambio != 0">
                                                    <th>- Cambios </th>
                                                    <td>  $ {{formatNumber(total_anticipo_cambio)}} </td>
                                                </tr>
                                                <tr>
                                                    <th>- Bonos cancelados </th>
                                                    <td>  $ {{formatNumber(total_bono)}} </td>
                                                </tr>
                                                <tr>
                                                    <th>- Sueldo del mes </th>
                                                    <td class="td2">  
                                                        <input type="text" v-if="sueldoBand" pattern="\d*" v-model="apoyo"  v-on:keypress="isNumber($event)" class="col-md-4" >
                                                        &nbsp; $ {{formatNumber(apoyo)}}
                                                    </td>
                                                </tr>
                                                <tr v-if=" restante != 0">
                                                    <th>- Acumulado anterior </th>
                                                    <td>  $ {{formatNumber(restante)}} </td>
                                                </tr>
                                                <tr>
                                                    <th>Total a pagar</th>
                                                    <td> $ {{formatNumber(total_a_pagar = totalAPagar )}}</td>
                                                </tr>
                                            </thead>
                                        </table>

                                    </div>

                                </div>

                                <div class="form-group row line-separator"></div>

                                <div class="form-group row"  v-if="ventas==1 && tipoVendedor == 1">
                                    
                                    <div class="col-md-8">

                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th> Total </th>
                                                    <td>  $ {{formatNumber(total = total_pagado + total_pagado_indiv + total_pagado_pendiente + total_pagado_cambio)}} </td>
                                                </tr>
                                                <tr v-if="total_anticipo_cambio != 0">
                                                    <th>- Cambios </th>
                                                    <td>  $ {{formatNumber(total_anticipo_cambio)}} </td>
                                                </tr>
                                                <tr v-if="numCancelaciones > 0">
                                                    <th>- Ventas canceladas </th>
                                                    <td>  $ {{formatNumber(total_anticipo)}} </td>
                                                </tr>
                                                <tr v-if="numCancelaciones > 0">
                                                    <th>- Bonos cancelados </th>
                                                    <td>  $ {{formatNumber(total_bono)}} </td>
                                                </tr>

                                                <tr v-if=" restante != 0">
                                                    <th>- Acumulado anterior </th>
                                                    <td>  $ {{formatNumber(restante)}} </td>
                                                </tr>
                                                
                                                <tr>
                                                    <th>Total a pagar</th>
                                                    <td> $ {{formatNumber(total_a_pagar = totalAPagar )}}</td>
                                                </tr>
                                            </thead>
                                        </table>

                                    </div>
                                </div>
                            
                            <!-- Div para mostrar los errores que mande validerPersonal -->
                            <div v-show="errorComision" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjComision" :key="error" v-text="error"></div>
                                </div>
                            </div>
                      
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

            <!-- Inicio Modal VER COMISION -->
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
                                    <select disabled class="form-control"  v-model="anio" >
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
                                <div class="col-md-6">
                                    <h6 v-text="asesor"></h6>
                                </div>
                            </div>

                            <div class="form-group row line-separator"></div>

                            <!-- VENTAS -->
                                <div class="form-group row">
                                    <h6   style="font-weight: bold; color: blue;" class="col-md-3">Ventas: {{this.numVentas}}</h6>
                                </div>

                                <div class="form-group row" v-if="tipoVendedor == 0 && numVentas != 0">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Folio</th>
                                                    <th>Proyecto</th>
                                                    <th>Etapa</th>
                                                    <th>Manzana</th>
                                                    <th>Lote</th>
                                                    <th>Cliente</th>
                                                    <th>Precio Venta</th>
                                                    <th>Crédito</th>
                                                    <th>% Avance</th>
                                                    <th>% De comisión</th>
                                                    <th>Comisión a pagar</th>
                                                    <th>Este pago</th>
                                                    <th>Por pagar</th>
                                                    <!-- <th>Observaciones</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="venta in arrayVentas" :key="venta.id">
                                                    <td class="td2" v-text="venta.fecha"></td>
                                                    <td>
                                                        <span v-if="venta.fecha_exp == null" class="badge2 badge-danger" v-text="venta.folio"></span>
                                                        <span v-else class="badge2 badge-success" v-text="venta.folio"></span>
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
                                                    <td class="td2" v-text="venta.nombre_cliente"></td>
                                                    <td v-text="'$'+formatNumber(venta.precio_venta)"></td>
                                                    <td class="td2" v-text="venta.tipo_credito + '(' + venta.institucion + ')'"></td>
                                                    <td v-text="venta.avance_lote + '%'"></td>
                                                    <!--- Calculo de la comision que corresponde segun ventas y avance ----->
                                                        
                                                    <td v-text="venta.porcentaje_comision + '%'"></td>
                                                    <!----------------------->
                                                    <td v-text="'$'+formatNumber(venta.comision_pagar )"></td>
                                                    
                                                    <!--- Verifica si la venta esta individualizada para determinar de cuanto sera el pago --->
                                                        <td v-text="'$'+formatNumber(venta.este_pago)"></td>
                                                    <!------->
                                                        <td v-text="'$'+formatNumber(venta.por_pagar)"></td>
                                                </tr>  
                                                <tr>
                                                    <td align="right" colspan="11"><strong>Total: </strong></td>
                                                    <td> ${{formatNumber(total_comision = totalComision)}} </td>
                                                    <td><strong> ${{formatNumber(total_pagado = totalPagado)}} </strong></td>
                                                    <td> ${{formatNumber(total_porPagar = totalPorPagar)}} </td>
                                                    
                                                    
                                                </tr>                     
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="form-group row" v-else-if="tipoVendedor == 1 && numVentas != 0">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Folio</th>
                                                    <th>Proyecto</th>
                                                    <th>Etapa</th>
                                                    <th>Manzana</th>
                                                    <th>Lote</th>
                                                    <th>Cliente</th>
                                                    <th>Precio Venta</th>
                                                    <th>Crédito</th>
                                                    <th>% Avance</th>
                                                    <th>% De comisión</th>
                                                    <th>Comisión a pagar</th>
                                                    <th>IVA</th>
                                                    <th>Retención</th>
                                                    <th>ISR</th>
                                                    
                                                    <th>Este pago</th>
                                                    <th>Por pagar</th>
                                                    <!-- <th>Observaciones</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="venta in arrayVentas" :key="venta.id">
                                                    <td class="td2" v-text="venta.fecha"></td>
                                                    <td v-text="venta.id"></td>
                                                    <td class="td2" v-text="venta.proyecto"></td>
                                                    <td class="td2" v-text="venta.etapa"></td>
                                                    <td class="td2" v-text="venta.manzana"></td>
                                                    <td v-text="venta.num_lote"></td>
                                                    <td class="td2" v-text="venta.nombre_cliente"></td>
                                                    <td v-text="'$'+formatNumber(venta.precio_venta)"></td>
                                                    <td class="td2" v-text="venta.tipo_credito + '(' + venta.institucion + ')'"></td>
                                                    <td v-text="venta.avance_lote + '%'"></td>
                                                    
                                                    <td v-text="venta.porcentaje_comision + '%'"></td>


                                                    <td v-text="'$'+formatNumber(venta.comision_pagar )"></td>
                                                    <td v-text="'$'+formatNumber(venta.iva )"></td>
                                                    <td v-text="'$'+formatNumber(venta.retencion )"></td>
                                                    <td v-text="'$'+formatNumber(venta.isr)"></td>
                                                    
                                                    <!--- Verifica si la venta esta individualizada para determinar de cuanto sera el pago --->
                                                        <td v-text="'$'+formatNumber(venta.este_pago)"></td>
                                                    <!------->
                                                        <td v-text="'$'+formatNumber(venta.por_pagar)"></td>
                                                </tr> 
                                                     
                                                <tr>
                                                    <td align="right" colspan="11"><strong>Total:</strong></td>
                                                    <td> ${{formatNumber(total_comision = totalComision)}} </td>
                                                    <td> ${{formatNumber(total_iva = totalIva)}} </td>
                                                    <td> ${{formatNumber(total_retencion = totalRetencion)}} </td>
                                                    <td> ${{formatNumber(total_isr = totalIsr)}} </td>
                                                    <td><strong> ${{formatNumber(total_pagado = totalPagado)}} </strong></td>
                                                    <td> ${{formatNumber(total_porPagar = totalPorPagar)}} </td>
                                                    
                                                </tr>                       
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            
                            <!-- Individualizadas -->
                                <div class="form-group row">
                                    <h6 v-if="numIndividualizadas != 0"  style="font-weight: bold; color: blue;" class="col-md-3">Individualizadas: {{this.numIndividualizadas}}</h6>
                                </div>

                                <div class="form-group row" v-if="numIndividualizadas != 0 && tipoVendedor == 0">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Folio</th>
                                                    <th>Proyecto</th>
                                                    <th>Etapa</th>
                                                    <th>Manzana</th>
                                                    <th>Lote</th>
                                                    <th>Cliente</th>
                                                    <th>Precio Venta</th>
                                                    <th>Crédito</th>
                                                    <th>% Avance</th>
                                                    <th>% De comisión</th>
                                                    <th>Comisión a pagar</th>
                                                    
                                                    <th>Este pago</th>
                                                    <th>Por pagar</th>
                                                    <!-- <th>Observaciones</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="indiv in arrayIndividualizadas" :key="indiv.id">
                                                    <td class="td2" v-text="indiv.fecha"></td>
                                                    <td v-text="indiv.folio"></td>
                                                    <td class="td2" v-text="indiv.proyecto"></td>
                                                    <td class="td2" v-text="indiv.etapa"></td>
                                                    <td class="td2" v-text="indiv.manzana"></td>
                                                    <td v-text="indiv.num_lote"></td>
                                                    <td class="td2" v-text="indiv.nombre_cliente"></td>
                                                    <td v-text="'$'+formatNumber(indiv.precio_venta)"></td>
                                                    <td class="td2" v-text="indiv.tipo_credito + '(' + indiv.institucion + ')'"></td>
                                                    <td v-text="indiv.avance_lote + '%'"></td>
                                                    <td v-text="indiv.porcentaje_comision + '%'"></td>
                                                    <td v-text="'$'+formatNumber(indiv.comision_pagar)"></td>
                                                    
                                                    <td v-text="'$'+formatNumber(indiv.por_pagar)"></td>
                                                    <td v-text="'$'+formatNumber(0)"></td>
                                                </tr>      
                                                <tr>
                                                    <td align="right" colspan="11"><strong>Total: </strong></td>
                                                    <td> ${{formatNumber(total_comision_indiv = totalComisionIndiv)}} </td>
                                                    <td><strong> ${{formatNumber(total_pagado_indiv = totalPagadoIndiv)}} </strong></td>
                                                    <td> ${{formatNumber(0)}} </td>
                                                </tr>                       
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="form-group row" v-else-if="tipoVendedor == 1 && numIndividualizadas>0">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Folio</th>
                                                    <th>Proyecto</th>
                                                    <th>Etapa</th>
                                                    <th>Manzana</th>
                                                    <th>Lote</th>
                                                    <th>Cliente</th>
                                                    <th>Precio Venta</th>
                                                    <th>Crédito</th>
                                                    <th>% Avance</th>
                                                    <th>% De comisión</th>
                                                    <th>Comisión a pagar</th>
                                                    <th>IVA</th>
                                                    <th>Retención</th>
                                                    <th>ISR</th>
                                                    
                                                    <th>Este pago</th>
                                                    <th>Por pagar</th>
                                                    <!-- <th>Observaciones</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="indiv in arrayIndividualizadas" :key="indiv.id">
                                                    <td class="td2" v-text="indiv.fecha"></td>
                                                    <td v-text="indiv.id"></td>
                                                    <td class="td2" v-text="indiv.proyecto"></td>
                                                    <td class="td2" v-text="indiv.etapa"></td>
                                                    <td class="td2" v-text="indiv.manzana"></td>
                                                    <td v-text="indiv.num_lote"></td>
                                                    <td class="td2" v-text="indiv.nombre_cliente"></td>
                                                    <td v-text="'$'+formatNumber(indiv.precio_venta)"></td>
                                                    <td class="td2" v-text="indiv.tipo_credito + '(' + indiv.institucion + ')'"></td>
                                                    <td v-text="indiv.avance_lote + '%'"></td>
                                                    <td v-text="indiv.porcentaje_comision + '%'"></td>
                                                    <td v-text="'$'+formatNumber(indiv.comision_pagar)"></td>
                                                    <td v-text="'$'+formatNumber(indiv.iva)"></td>
                                                    <td v-text="'$'+formatNumber(indiv.retencion)"></td>
                                                    <td v-text="'$'+formatNumber(indiv.isr)"></td>
                                                    
                                                    <td v-text="'$'+formatNumber(indiv.por_pagar)"></td>
                                                    <td v-text="'$'+formatNumber(0)"></td>
                                                    
                                                </tr>      
                                                <tr>
                                                    <td align="right" colspan="11"><strong>Total:</strong></td>
                                                    <td> ${{formatNumber(total_comision_indiv = totalComisionIndiv)}} </td>
                                                    <td> ${{formatNumber(total_iva_indiv  = totalIvaIndiv)}} </td>
                                                    <td> ${{formatNumber(total_retencion_indiv  = totalRetencionIndiv)}} </td>
                                                    <td> ${{formatNumber(total_isr_indiv  = totalIsrIndiv)}} </td>
                                                    <td><strong> ${{formatNumber(total_pagado_indiv = totalPagadoIndiv)}} </strong></td>
                                                    <td> ${{formatNumber(0)}} </td>
                                                    
                                                </tr>                       
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            <div class="form-group row line-separator" ></div>
                            
                            <!-- VENTAS CANCELADAS -->
                                <div class="form-group row">
                                    <h6 v-if="numCancelaciones != 0"  style="font-weight: bold; color: blue;" class="col-md-3">Canceladas: {{this.numCancelaciones}}</h6>
                                </div>

                                <div class="form-group row" v-if="numCancelaciones != 0">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Folio</th>
                                                    <th>Proyecto</th>
                                                    <th>Etapa</th>
                                                    <th>Manzana</th>
                                                    <th>Lote</th>
                                                    <th>Cliente</th>
                                                    <th>Fecha de cancelación</th>
                                                    <th>$ Anticipo</th>
                                                    <th>Bono</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="anticipo in arrayCanceladas" :key="anticipo.folio">
                                                    <td v-text="anticipo.folio"></td>
                                                    <td class="td2" v-text="anticipo.proyecto"></td>
                                                    <td class="td2" v-text="anticipo.etapa"></td>
                                                    <td class="td2" v-text="anticipo.manzana"></td>
                                                    <td v-text="anticipo.num_lote"></td>
                                                    <td class="td2" v-text="anticipo.nombre_cliente"></td>
                                                    <td v-text="anticipo.fecha_status"></td>
                                                    <td v-text="'$'+formatNumber(anticipo.este_pago)"></td>
                                                    <td v-text="'$'+formatNumber(anticipo.bono)"></td>
                                                </tr>      
                                                <tr>
                                                    <td align="right" colspan="7"><strong>Total: </strong></td>
                                                    <td> ${{formatNumber(total_anticipo = totalAnticipo)}} </td>
                                                    <td> ${{formatNumber(total_bono = totalBono)}} </td>
                                                    
                                                </tr>                       
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            <!-- COMISIONES QUE HABIAN QUEDADO PENDIENTES POR FALTA DE PAGARE -->
                                <div class="form-group row">
                                    <h6 v-if=" numPendientes != 0"  style="font-weight: bold; color: blue;" class="col-md-3">Pendientes: {{this.numPendientes}}</h6>
                                </div>

                                <div class="form-group row" v-if="numPendientes != 0 && tipoVendedor == 0">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Folio</th>
                                                    <th>Proyecto</th>
                                                    <th>Etapa</th>
                                                    <th>Manzana</th>
                                                    <th>Lote</th>
                                                    <th>Cliente</th>
                                                    <th>Precio Venta</th>
                                                    <th>Crédito</th>
                                                    <th>% Avance</th>
                                                    <th>% De comisión</th>
                                                    <th>Comisión a pagar</th>
                                                    <th>Este pago</th>
                                                    <th>Por pagar</th>
                                                    <!-- <th>Observaciones</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="pendiente in arrayPendientes" :key="pendiente.id">
                                                    <td class="td2" v-text="pendiente.fecha"></td>
                                                    <td v-text="pendiente.folio"></td>
                                                    <td class="td2" v-text="pendiente.proyecto"></td>
                                                    <td class="td2" v-text="pendiente.etapa"></td>
                                                    <td class="td2" v-text="pendiente.manzana"></td>
                                                    <td v-text="pendiente.num_lote"></td>
                                                    <td class="td2" v-text="pendiente.nombre_cliente"></td>
                                                    <td v-text="'$'+formatNumber(pendiente.precio_venta)"></td>
                                                    <td class="td2" v-text="pendiente.tipo_credito + '(' + pendiente.institucion + ')'"></td>
                                                    <td v-text="pendiente.avance_lote + '%'"></td>
                                                    <td v-text="pendiente.porcentaje_comision + '%'"></td>
                                                    <td v-text="'$'+formatNumber(pendiente.comision_pagar)"></td>
                                                    
                                                    <td v-text="'$'+formatNumber(pendiente.este_pago  )"></td>
                                                    <td v-text="'$'+formatNumber(pendiente.por_pagar )"></td>
                                                    
                                                </tr>      
                                                <tr>
                                                    <td align="right" colspan="11"><strong>Total: </strong></td>
                                                    <td> ${{formatNumber(total_comision_pendiente = totalComisionPendiente)}} </td>
                                                    <td><strong> ${{formatNumber(total_pagado_pendiente = totalPagadoPendiente)}} </strong></td>
                                                    <td> ${{formatNumber(total_porPagar_pendiente = totalPorPagarPendiente)}} </td>
                                                      
                                                </tr>                       
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="form-group row" v-if="numPendientes != 0 && tipoVendedor == 1">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Folio</th>
                                                    <th>Proyecto</th>
                                                    <th>Etapa</th>
                                                    <th>Manzana</th>
                                                    <th>Lote</th>
                                                    <th>Cliente</th>
                                                    <th>Precio Venta</th>
                                                    <th>Crédito</th>
                                                    <th>% Avance</th>
                                                    <th>% De comisión</th>
                                                    <th>Comisión a pagar</th>
                                                    <th>IVA</th>
                                                    <th>Retención</th>
                                                    <th>ISR</th>
                                                    
                                                    <th>Este pago</th>
                                                    <th>Por pagar</th>
                                                    <!-- <th>Observaciones</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="pendiente in arrayPendientes" :key="pendiente.id">
                                                    <td class="td2" v-text="pendiente.fecha"></td>
                                                    <td v-text="pendiente.id"></td>
                                                    <td class="td2" v-text="pendiente.proyecto"></td>
                                                    <td class="td2" v-text="pendiente.etapa"></td>
                                                    <td class="td2" v-text="pendiente.manzana"></td>
                                                    <td v-text="pendiente.num_lote"></td>
                                                    <td class="td2" v-text="pendiente.nombre_cliente"></td>
                                                    <td v-text="'$'+formatNumber(pendiente.precio_venta)"></td>
                                                    <td class="td2" v-text="pendiente.tipo_credito + '(' + pendiente.institucion + ')'"></td>
                                                    <td v-text="pendiente.avance_lote + '%'"></td>
                                                    <td v-text="pendiente.porcentaje_comision + '%'"></td>
                                                    <td v-text="'$'+formatNumber(pendiente.comision_pagar)"></td>
                                                    <td v-text="'$'+formatNumber(pendiente.iva)"></td>
                                                    <td v-text="'$'+formatNumber(pendiente.retencion)"></td>
                                                    <td v-text="'$'+formatNumber(pendiente.isr)"></td>
                                                    
                                                    <td v-if="pendiente.indiv == 0" v-text="'$'+formatNumber(pendiente.este_pago = (pendiente.comision_pagar / 2) + pendiente.iva - pendiente.isr - pendiente.retencion)"></td>
                                                    <td v-else v-text="'$'+formatNumber(pendiente.este_pago = (pendiente.comision_pagar) + pendiente.iva - pendiente.isr - pendiente.retencion )"></td>
                                                    <td v-if="pendiente.indiv == 0" v-text="'$'+formatNumber(pendiente.por_pagar = (pendiente.comision_pagar / 2) )"></td>
                                                    <td v-else v-text="'$'+formatNumber(pendiente.por_pagar=0)"></td>
                                                </tr>      
                                                <tr>
                                                    <td align="right" colspan="11"><strong>Total:</strong></td>
                                                    <td> ${{formatNumber(total_comision_pendiente = totalComisionPendiente)}} </td>
                                                    <td> ${{formatNumber(total_iva_pendiente = totalIvaPendiente)}} </td>
                                                    <td> ${{formatNumber(total_retencion_pendiente = totalRetencionPendiente)}} </td>
                                                    <td> ${{formatNumber(total_isr_pendiente = totalIsrPendiente)}} </td>
                                                    <td><strong> ${{formatNumber(total_pagado_pendiente = totalPagadoPendiente)}} </strong></td>
                                                    <td> ${{formatNumber(total_porPagar_pendiente = totalPorPagarPendiente)}} </td>
                                                      
                                                </tr>                       
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            <!-- <div class="form-group row line-separator"  v-if="ventas==1"></div> -->

                            <!-- CAMBIOS  -->
                                <div class="form-group row">
                                    <h6 v-if=" numCambios != 0"  style="font-weight: bold; color: blue;" class="col-md-3">Cambios: {{this.numCambios}}</h6>
                                </div>

                                <div class="form-group row" v-if="numCambios != 0">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Descripcion</th>
                                                    <th>Monto</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="cambio in arrayCambios" :key="cambio.id">
                                                    <td class="td2" v-text="cambio.descripcion"></td>
                                                    <td v-text="'$'+formatNumber(cambio.pago_ant)"></td>
                                                </tr>      
                                                <tr>
                                                    <td align="right"><strong>Total: </strong></td>
                                                    <td> ${{formatNumber(total_anticipo_cambio = totalAnticipoCambio)}} </td>
                                                      
                                                </tr>                       
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            <!-- DATOS DE VENTA -->

                                <div class="form-group row"  v-if="tipoVendedor == 0">
                                    
                                    <div class="col-md-8">

                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th> Total </th>
                                                    <td>  &nbsp; $ {{formatNumber(total )}} </td>
                                                </tr>
                                                <tr v-if="total_anticipo_cambio != 0">
                                                    <th>- Cambios </th>
                                                    <td>  $ {{formatNumber(total_anticipo_cambio)}} </td>
                                                </tr>
                                                <tr>
                                                    <th>- Ventas canceladas </th>
                                                    <td>  &nbsp; $ {{formatNumber(total_anticipo)}} </td>
                                                </tr>
                                                <tr>
                                                    <th>- Bonos cancelados </th>
                                                    <td>  &nbsp; $ {{formatNumber(total_bono)}} </td>
                                                </tr>
                                                <tr>
                                                    <th>- Sueldo del mes </th>
                                                    <td>  &nbsp; $ {{formatNumber(apoyo)}} </td>
                                                </tr>
                                                <tr v-if=" restante != 0">
                                                    <th>- Acumulado anterior </th>
                                                    <td>  &nbsp; $ {{formatNumber(restante)}} </td>
                                                </tr>
                                                <tr>
                                                    <th>Total a pagar</th>
                                                    <td><strong>&nbsp; $ {{formatNumber(total_a_pagar)}}</strong> </td>
                                                </tr>
                                            </thead>
                                        </table>

                                    </div>

                                </div>

                                <div class="form-group row line-separator"></div>

                                <div class="form-group row"  v-if="tipoVendedor == 1">
                                    
                                    <div class="col-md-8">

                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th> Total </th>
                                                    <td>  $ {{formatNumber(total )}} </td>
                                                </tr>
                                                <tr v-if="numCancelaciones > 0">
                                                    <th>- Ventas canceladas </th>
                                                    <td>  $ {{formatNumber(total_anticipo)}} </td>
                                                </tr>
                                                <tr v-if="numCancelaciones > 0">
                                                    <th>- Bonos cancelados </th>
                                                    <td>  $ {{formatNumber(total_bono)}} </td>
                                                </tr>

                                                <tr v-if=" restante != 0">
                                                    <th>- Acumulado anterior </th>
                                                    <td>  $ {{formatNumber(restante)}} </td>
                                                </tr>
                                                
                                                <tr>
                                                    <th>Total a pagar</th>
                                                    <td> $ {{formatNumber(total_a_pagar )}}</td>
                                                </tr>
                                            </thead>
                                        </table>

                                    </div>
                                </div>
                            
                            <!-- Div para mostrar los errores que mande validerPersonal -->
                            <div v-show="errorComision" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjComision" :key="error" v-text="error"></div>
                                </div>
                            </div>
                      
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <a class="btn btn-success" v-bind:href=" 
                                '/comision/excel?' + 
                                'mes=' + mes +
                                '&anio=' + anio +
                                '&asesor=' + asesor +
                                '&tipoVendedor=' + tipoVendedor +
                                //Ventas
                                '&numVentas=' + numVentas +
                                
                                '&total_comision=' + total_comision +
                                '&total_pagado=' + total_pagado +
                                '&total_porPagar=' + total_porPagar +
                                '&total_iva=' + total_iva +
                                '&total_retencion=' + total_retencion +
                                '&total_isr=' + total_isr +
                                //Individualizadas
                                '&numIndividualizadas=' + numIndividualizadas +
                                '&total_comision_indiv=' + total_comision_indiv +
                                '&total_pagado_indiv=' + total_pagado_indiv +
                                '&total_iva_indiv=' + total_iva_indiv +
                                '&total_retencion_indiv=' + total_retencion_indiv +
                                '&total_isr_indiv=' + total_isr_indiv +
                                //Canceladas
                                '&numCancelaciones=' + numCancelaciones +
                                '&total_anticipo=' + total_anticipo +
                                '&total_bono=' + total_bono +
                                //Pendientes
                                '&numPendientes=' + numPendientes +
                                '&total_comision_pendiente=' + total_comision_pendiente +
                                '&total_pagado_pendiente=' + total_pagado_pendiente +
                                '&total_porPagar_pendiente=' + total_porPagar_pendiente +
                                '&total_iva_pendiente=' + total_iva_pendiente +
                                '&total_retencion_pendiente=' + total_retencion_pendiente +
                                '&total_isr_pendiente=' + total_isr_pendiente +
                                //Cambios
                                '&numCambios=' + numCambios +
                                '&total_anticipo_cambio=' + total_anticipo_cambio +

                                //Datos
                                '&total=' + total +
                                '&apoyo=' + apoyo +
                                '&restante=' + restante +
                                '&total_a_pagar=' + total_a_pagar + 
                                '&comision_id=' + id
                            " >
                                &nbsp;Descargar
                            </a>
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
                b_asesor_id:'',
                modal: 0,
                tituloModal: '',
                errorComision : 0,
                errorMostrarMsjComision : [],
                ventas:0,
                numIndividualizadas:0,
                numCancelaciones:0,
                numPendientes:0,
                numCambios:0,

                arrayVentas:[],
                arrayDetalle:[],
                arrayCanceladas:[],
                arrayIndividualizadas:[],
                arrayPendientes : [],
                arrayCambios:[],
                
                numVentas:'', 

                tipoVendedor:0,
                esquema:2,
                restante:0,
                apoyo:0,
                aPagar:0,
                comision:0,
                anticipo:0,
                sueldoBand:true,
                
                asesor_id:'',
                
                modal1:0,

                total_comision:0,
                total_pagado:0,
                total_porPagar:0,

                total_comision_indiv:0,
                total_pagado_indiv:0,
                total_porPagar_indiv:0,

                total_comision_pendiente:0,
                total_pagado_pendiente:0,
                total_porPagar_pendiente:0,

                total_comision_cambio:0,
                total_pagado_cambio:0,
                total_porPagar_cambio:0,
                total_anticipo_cambio:0,
                total_iva_cambio:0,
                total_retencion_cambio:0,
                total_isr_cambio:0,

                total_iva:0,
                total_isr:0,
                total_retencion:0,

                total_iva_indiv:0,
                total_retencion_indiv:0,
                total_isr_indiv:0,

                total_iva_pendiente:0,
                total_retencion_pendiente:0,
                total_isr_pendiente:0,

                total_anticipo:0,
                total_bono:0,

                total:0,
                total_a_pagar:0,
                bandISR:0,
                bandRetencion:0,

                asesor : '',
                
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
                    totalComision += parseFloat(this.arrayVentas[i].comision_pagar)
                }
                if(totalComision < 0)
                    totalComision = 0;
                totalComision = Math.round(totalComision*100)/100;
                return totalComision;
            },

            totalPagado: function(){
                var totalPagado =0.0;
                for(var i=0;i<this.arrayVentas.length;i++){
                    totalPagado += parseFloat(this.arrayVentas[i].este_pago)
                }
                if(totalPagado < 0)
                    totalPagado = 0;
                totalPagado = Math.round(totalPagado*100)/100;
                return totalPagado;
            },

            totalPorPagar: function(){
                var totalPorPagar =0.0;
                for(var i=0;i<this.arrayVentas.length;i++){
                    totalPorPagar += parseFloat(this.arrayVentas[i].por_pagar)
                }
                if(totalPorPagar < 0)
                    totalPorPagar = 0;
                totalPorPagar = Math.round(totalPorPagar*100)/100;
                return totalPorPagar;
            },

            totalComisionPendiente: function(){
                var totalComision =0.0;
                for(var i=0;i<this.arrayPendientes.length;i++){
                    totalComision += parseFloat(this.arrayPendientes[i].comision_pagar)
                }
                if(totalComision < 0)
                    totalComision = 0;
                totalComision = Math.round(totalComision*100)/100;
                return totalComision;
            },

            totalPagadoPendiente: function(){
                var totalPagado =0.0;
                for(var i=0;i<this.arrayPendientes.length;i++){
                    totalPagado += parseFloat(this.arrayPendientes[i].este_pago)
                }
                if(totalPagado < 0)
                    totalPagado = 0;
                totalPagado = Math.round(totalPagado*100)/100;
                return totalPagado;
            },

            totalPorPagarPendiente: function(){
                var totalPorPagar =0.0;
                for(var i=0;i<this.arrayPendientes.length;i++){
                    totalPorPagar += parseFloat(this.arrayPendientes[i].por_pagar)
                }
                if(totalPorPagar < 0)
                    totalPorPagar = 0;
                totalPorPagar = Math.round(totalPorPagar*100)/100;
                return totalPorPagar;
            },

            totalComisionCambio: function(){
                var totalComision =0.0;
                for(var i=0;i<this.arrayCambios.length;i++){
                    totalComision += parseFloat(this.arrayCambios[i].comision_pagar)
                }
                if(totalComision < 0)
                    totalComision = 0;
                totalComision = Math.round(totalComision*100)/100;
                return totalComision;
            },

            totalPagadoCambio: function(){
                var totalPagado =0.0;
                for(var i=0;i<this.arrayCambios.length;i++){
                    totalPagado += parseFloat(this.arrayCambios[i].este_pago)
                }
                if(totalPagado < 0)
                    totalPagado = 0;
                totalPagado = Math.round(totalPagado*100)/100;
                return totalPagado;
            },

            totalPorPagarCambio: function(){
                var totalPorPagar =0.0;
                for(var i=0;i<this.arrayCambios.length;i++){
                    totalPorPagar += parseFloat(this.arrayCambios[i].por_pagar)
                }
                if(totalPorPagar < 0)
                    totalPorPagar = 0;
                totalPorPagar = Math.round(totalPorPagar*100)/100;
                return totalPorPagar;
            },

            totalAnticipoCambio: function(){
                var totalAnticipo =0.0;
                for(var i=0;i<this.arrayCambios.length;i++){
                    totalAnticipo += parseFloat(this.arrayCambios[i].pago_ant)
                }
                if(totalAnticipo < 0)
                    totalAnticipo = 0;
                totalAnticipo = Math.round(totalAnticipo*100)/100;
                return totalAnticipo;
            },

            totalIvaCambio: function(){
                var totalIva =0.0;
                for(var i=0;i<this.arrayCambios.length;i++){
                    totalIva += parseFloat(this.arrayCambios[i].iva)
                }
                if(totalIva < 0)
                    totalIva = 0;
                totalIva = Math.round(totalIva*100)/100;
                return totalIva;
            },

            totalIsrCambio: function(){
                var totalIsr =0.0;
                for(var i=0;i<this.arrayCambios.length;i++){
                    totalIsr += parseFloat(this.arrayCambios[i].isr)
                }
                if(totalIsr < 0)
                    totalIsr = 0;
                totalIsr = Math.round(totalIsr*100)/100;
                return totalIsr;
            },

            totalRetencionCambio: function(){
                var totalRetencion =0.0;
                for(var i=0;i<this.arrayCambios.length;i++){
                    totalRetencion += parseFloat(this.arrayCambios[i].retencion)
                }
                if(totalRetencion < 0)
                    totalRetencion = 0;
                totalRetencion = Math.round(totalRetencion*100)/100;
                return totalRetencion;
            },

            totalIva: function(){
                var totalIva =0.0;
                for(var i=0;i<this.arrayVentas.length;i++){
                    totalIva += parseFloat(this.arrayVentas[i].iva)
                }
                if(totalIva < 0)
                    totalIva = 0;
                totalIva = Math.round(totalIva*100)/100;
                return totalIva;
            },

            totalIsr: function(){
                var totalIsr =0.0;
                for(var i=0;i<this.arrayVentas.length;i++){
                    totalIsr += parseFloat(this.arrayVentas[i].isr)
                }
                if(totalIsr < 0)
                    totalIsr = 0;
                totalIsr = Math.round(totalIsr*100)/100;
                return totalIsr;
            },

            totalRetencion: function(){
                var totalRetencion =0.0;
                for(var i=0;i<this.arrayVentas.length;i++){
                    totalRetencion += parseFloat(this.arrayVentas[i].retencion)
                }
                if(totalRetencion < 0)
                    totalRetencion = 0;
                totalRetencion = Math.round(totalRetencion*100)/100;
                return totalRetencion;
            },

            totalIvaPendiente: function(){
                var totalIva =0.0;
                for(var i=0;i<this.arrayPendientes.length;i++){
                    totalIva += parseFloat(this.arrayPendientes[i].iva)
                }
                if(totalIva < 0)
                    totalIva = 0;
                totalIva = Math.round(totalIva*100)/100;
                return totalIva;
            },

            totalIsrPendiente: function(){
                var totalIsr =0.0;
                for(var i=0;i<this.arrayPendientes.length;i++){
                    totalIsr += parseFloat(this.arrayPendientes[i].isr)
                }
                if(totalIsr < 0)
                    totalIsr = 0;
                totalIsr = Math.round(totalIsr*100)/100;
                return totalIsr;
            },

            totalRetencionPendiente: function(){
                var totalRetencion =0.0;
                for(var i=0;i<this.arrayPendientes.length;i++){
                    totalRetencion += parseFloat(this.arrayPendientes[i].retencion)
                }
                if(totalRetencion < 0)
                    totalRetencion = 0;
                totalRetencion = Math.round(totalRetencion*100)/100;
                return totalRetencion;
            },

            totalComisionIndiv: function(){
                var totalComision =0.0;
                for(var i=0;i<this.arrayIndividualizadas.length;i++){
                    totalComision += parseFloat(this.arrayIndividualizadas[i].comision_pagar)
                }
                if(totalComision < 0)
                    totalComision = 0;
                totalComision = Math.round(totalComision*100)/100;
                return totalComision;
            },

            totalPagadoIndiv: function(){
                var totalPagado =0.0;
                for(var i=0;i<this.arrayIndividualizadas.length;i++){
                    totalPagado += parseFloat(this.arrayIndividualizadas[i].por_pagar)
                }
                if(totalPagado < 0)
                    totalPagado = 0;
                totalPagado = Math.round(totalPagado*100)/100;
                return totalPagado;
            },

            totalIvaIndiv: function(){
                var totalIva =0.0;
                for(var i=0;i<this.arrayIndividualizadas.length;i++){
                    totalIva += parseFloat(this.arrayIndividualizadas[i].iva)
                }
                if(totalIva < 0)
                    totalIva = 0;
                totalIva = Math.round(totalIva*100)/100;
                return totalIva;
            },

            totalIsrIndiv: function(){
                var totalIsr =0.0;
                for(var i=0;i<this.arrayIndividualizadas.length;i++){
                    totalIsr += parseFloat(this.arrayIndividualizadas[i].isr)
                }
                if(totalIsr < 0)
                    totalIsr = 0;
                totalIsr = Math.round(totalIsr*100)/100;
                return totalIsr;
            },

            totalRetencionIndiv: function(){
                var totalRetencion =0.0;
                for(var i=0;i<this.arrayIndividualizadas.length;i++){
                    totalRetencion += parseFloat(this.arrayIndividualizadas[i].retencion)
                }
                if(totalRetencion < 0)
                    totalRetencion = 0;
                totalRetencion = Math.round(totalRetencion*100)/100;
                return totalRetencion;
            },

            totalAnticipo: function(){
                var totalAnticipo =0.0;
                for(var i=0;i<this.arrayCanceladas.length;i++){
                    totalAnticipo += parseFloat(this.arrayCanceladas[i].este_pago)
                }
                if(totalAnticipo < 0)
                    totalAnticipo = 0;
                totalAnticipo = Math.round(totalAnticipo*100)/100;
                return totalAnticipo;
            },

            totalBono: function(){
                var totalBono =0.0;
                for(var i=0;i<this.arrayCanceladas.length;i++){
                    totalBono += parseFloat(this.arrayCanceladas[i].bono)
                }
                if(totalBono < 0 || totalBono == null)
                    totalBono = 0;
                totalBono = Math.round(totalBono*100)/100;
                return totalBono;
            },

            totalAPagar: function(){
                var total_a_pagar = 0;

                total_a_pagar = parseFloat(this.total) - parseFloat(this.apoyo) - parseFloat(this.total_anticipo) - parseFloat(this.total_bono) - parseFloat(this.restante) - parseFloat(this.total_anticipo_cambio);

                return total_a_pagar;
            }
          
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
            getComision(id){
                let me = this;
                var url = '/comision/getComision?mes=' + me.mes + '&anio=' + me.anio + '&vendedor=' + id;
                 axios.get(url).then(function (response) {
                     
                    var respuesta = response.data;
                    me.numVentas = respuesta.numVentas;
                    me.arrayVentas = respuesta.ventas;
                    me.esquema = respuesta.esquema;
                    me.tipoVendedor = respuesta.tipo;
                    
                    me.arrayCanceladas = respuesta.canceladas;
                    me.arrayIndividualizadas = respuesta.individualizadas;
                    me.arrayPendientes = respuesta.pendientes;
                    me.arrayCambios = respuesta.cambios;

                    me.numCancelaciones = respuesta.numCancelaciones;
                    me.numIndividualizadas = respuesta.numIndividualizadas;
                    me.numPendientes = respuesta.numPendientes;
                    me.numCambios = respuesta.numCambios;
                    me.apoyo = 0;
                    me.aPagar = 0;
                    me.restante = respuesta.acumuladoAnt;
                    me.sueldoBand = respuesta.sueldo;

                    me.bandISR = respuesta.isr;
                    me.bandRetencion = respuesta.retencion;

                    if(me.tipoVendedor == 0){
                        if(me.sueldoBand)
                            me.apoyo = 10500;
                        else{
                            me.apoyo = 0;
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
                Swal({
                    title: 'Estas seguro?',
                    animation: false,
                    customClass: 'animated bounceInDown',
                    text: "Se registrará la comisión",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    
                    confirmButtonText: 'Si, registrar!'
                    }).then((result) => {

                    if (result.value) {
                        //Con axios se llama el metodo store de PersonalController
                        axios.post('/comision/storeComision',{
                            'mes': this.mes,
                            'anio': this.anio,
                            'total_pagado' : this.total_a_pagar,
                            'total_comision' : this.total,
                            'apoyo_mes' : this.apoyo,
                            'asesor_id' : this.asesor_id,
                            'total_porPagar' : this.total_porPagar,
                            'restanteAnt' : this.restante,

                            'dataVentas':this.arrayVentas,
                            'dataIndividualizadas': this.arrayIndividualizadas,
                            'dataCanceladas' : this.arrayCanceladas,
                            'dataPendientes' : this.arrayPendientes,
                            'dataCambios' : this.arrayCambios,

                            'tipo_vendedor' : this.tipoVendedor,
                            'total_bono' : this.total_bono,
                            'total_anticipo' : this.total_anticipo,
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
                    }
                    else{
                        me.proceso = false;
                    }
                })
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

            cerrarModal(){
                this.modal = 0;
                this.modal1 = 0;
                this.tituloModal = '';
                this.asesor_id = '';
                this.mes = '';
                this.arrayVentas = [];
                this.arrayCanceladas = [];
                this.arrayPendientes = [];
                this.arrayIndividualizadas = [];

                this.asesor = '';
                this.numVentas = 0;
                this.aPagar = 0;
                this.restante = 0;
                this.ventas = 0;

                this.total_comision = 0;
                this.total_pagado = 0;
                this.total_porPagar = 0;

                this.total_comision_indiv = 0;
                this.total_pagado_indiv = 0;
                this.total_porPagar_indiv = 0;

                this.total_comision_pendiente = 0;
                this.total_pagado_pendiente = 0;
                this.total_porPagar_pendiente = 0;

                this.total_iva = 0;
                this.total_isr = 0;
                this.total_retencion = 0;

                this.total_iva_indiv = 0;
                this.total_retencion_indiv = 0;
                this.total_isr_indiv = 0;

                this.total_iva_pendiente = 0;
                this.total_retencion_pendiente = 0;
                this.total_isr_pendiente = 0;

                this.total_anticipo = 0;
                this.total_bono = 0;

                this.total = 0;
                this.total_a_pagar = 0;
                this.bandISR = 0;
                this.bandRetencion = 0;

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

            noAplica(id){
                 
                let me = this;
                //Con axios se llama el metodo update de LoteController
            
                Swal({
                    title: 'Estas seguro?',
                    animation: false,
                    customClass: 'animated bounceInDown',
                    text: "Se descartaran los cambios de esta comisión",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    
                    confirmButtonText: 'Si, cambiar!'
                    }).then((result) => {

                    if (result.value) {
                    
                        axios.put('/comision/desartarCambio',{
                            'id':id
                        }); 
                        
                        me.getComision(me.asesor_id);
                        Swal({
                            title: 'Hecho!',
                            text: 'Cambio descartado',
                            type: 'success',
                            animation: false,
                            customClass: 'animated bounceInRight'
                        })
                    }
                })
                
              
            },

            detalleComision(comision_id){
                let me = this;
                var url = '/comision/detalleComision?comision_id=' + comision_id;
                 axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayVentas = respuesta.ventas;
                    me.numVentas = respuesta.numVentas;

                    me.arrayPendientes = respuesta.pendientes;
                    me.numPendientes = respuesta.numPendientes;

                    me.arrayCanceladas = respuesta.canceladas;
                    me.numCancelaciones = respuesta.numCanceladas;

                    me.arrayIndividualizadas = respuesta.individualizadas;
                    me.numIndividualizadas = respuesta.numIndividualizadas;

                    me.arrayCambios = respuesta.cambios;
                    me.numCambios = respuesta.numCambios;

                    
                })
                .catch(function (error) {
                    console.log(error);
                })
            },

            abrirModal(accion,data =[]){
                switch(accion){
                    case 'nuevo':
                    {
                        this.modal = 1;
                        this.tituloModal='Comisión';
                        this.arrayVentas = [];
                        this.arrayCanceladas = [];
                        this.arrayPendientes = [];
                        this.arrayIndividualizadas = [];
                        this.numVentas = 0;
                        this.aPagar = 0;
                        this.restante = 0;
                        this.ventas = 0;

                        this.total_comision = 0;
                        this.total_pagado = 0;
                        this.total_porPagar = 0;

                        this.total_comision_indiv = 0;
                        this.total_pagado_indiv = 0;
                        this.total_porPagar_indiv = 0;

                        this.total_comision_pendiente = 0;
                        this.total_pagado_pendiente = 0;
                        this.total_porPagar_pendiente = 0;

                        this.total_iva = 0;
                        this.total_isr = 0;
                        this.total_retencion = 0;

                        this.total_iva_indiv = 0;
                        this.total_retencion_indiv = 0;
                        this.total_isr_indiv = 0;

                        this.total_iva_pendiente = 0;
                        this.total_retencion_pendiente = 0;
                        this.total_isr_pendiente = 0;

                        this.total_anticipo = 0;
                        this.total_bono = 0;

                        this.total = 0;
                        this.total_a_pagar = 0;
                        this.bandISR = 0;
                        this.bandRetencion = 0;
                        break;
                    }
                    case 'detalle':{
                        this.tituloModal="Detalle de la comisión";
                        this.modal1 = 1;
                        this.mes = data['mes'];
                        this.anio = data['anio'];
                        this.asesor = data['asesor'];
                        this.id = data['id'];

                        this.total_a_pagar = data['total_pagado'];
                        this.total = data['total_comision'];
                        this.apoyo = data['apoyo_mes'];
                        this.restante = data['restanteAnt'];
                        this.total_bono = data['total_bono'];
                        this.total_anticipo = data['total_anticipo'];
                        this.tipoVendedor = data['tipo_vendedor'];
                        this.detalleComision(this.id);
                        

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
    @media (min-width: 1020px) {
        .modal-lg {
            max-width: 1020px;
        }
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
