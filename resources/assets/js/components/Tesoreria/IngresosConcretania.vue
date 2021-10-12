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
                        <i class="fa fa-align-justify"></i> Relación de los ingresos de Concretania
                        <!--   Boton Nuevo    -->
                        <button  v-if="historial == 0" type="button" @click="abrirModal('enviar')" class="btn btn-dark">
                            <i class="icon-envelope-letter"></i> &nbsp;Solicitar
                        </button>
                        &nbsp;
                        <button v-if="historial == 0" type="button" @click="historial=1" class="btn btn-primary">
                            <i class="fa fa-calendar-check-o"></i>&nbsp;Historial de ingresos
                        </button>

                        <button v-if="historial == 0" type="button" @click="historial=2" class="btn btn-darkfont ">
                            <i class="fa fa-money"></i>&nbsp;Estado de cuenta
                        </button>

                        <button v-if="historial == 1 || historial == 2" type="button" @click="historial=0" class="btn btn-default">
                            <i class="fa fa-mail-reply"></i>&nbsp;Regresar
                        </button>
                        <a v-if="historial == 1 || historial == 2" :href="'/ingresosConcretania/indexEdoCuentaTerreno/excel?buscar=' + 
                                buscar + '&etapa=' + b_etapa + '&manzana=' + b_manzana + '&lote=' + 
                                b_lote + '&criterio=' + criterio"
                            class="btn btn-success">
                            <i class="fa fa-file-excel-o"></i>&nbsp;Excel
                        </a>
                        <!---->
                    </div>
                    <!-- Pendientes por Ingresar -->
                    <div class="card-body" v-if="historial == 0">
                        <div class="form-group row">
                            <div class="col-md-7">
                                <div class="input-group">
                                    <select class="form-control" disabled>
                                        <option value="fecha">Fecha de ingreso</option>
                                    </select>
                                    <!--Criterios para el listado de busqueda -->
                                    <input type="date" class="form-control" v-model="b_fecha">
                                    <input type="date" class="form-control" v-model="b_fecha2">
                                </div>
                            </div>
                            
                        </div>
                        <div class="form-group row">
                            <div class="col-md-9">
                                <div class="input-group">
                                    <button type="submit" @click="listarLotes()" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    <a :href="'/ingresosConcretania/pendeintesIngresarExcel?b_fecha=' + b_fecha + '&b_fecha2=' + b_fecha2"
                                        class="btn btn-success">
                                        <i class="fa fa-file-excel-o"></i>&nbsp;Excel
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" @click="selectAll" v-model="allSelected"> Todos
                                        </th>
                                        <th>Fraccionamiento</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th># Lote</th>
                                        <th>Cliente</th>
                                        <th>Clasificación</th>
                                        <th>Total de deposito</th>
                                        <th>Importe a ingresar</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="lote in arrayLotes" :key="lote.cont">
                                        <td class="text-center" style="width:8%; ">
                                            <input type="checkbox" @click="select" :id="lote.cont" :value="lote" v-model="lotes_ini">
                                        </td>
                                        <td class="td2" v-text="lote.fraccionamiento"></td>
                                        <td v-text="lote.etapa"></td>
                                        <td v-text="lote.manzana"></td>
                                        <td v-text="lote.num_lote"></td>
                                        <td class="td2" v-text="lote.nombre + ' ' + lote.apellidos"></td>
                                        <template v-if="lote.tipo == 0">
                                            <td class="td2" v-text="lote.concepto"></td>
                                        </template>
                                        <template v-else>
                                            <td class="td2" v-text="'Deposito de crédito'"></td>
                                        </template>
                                        <td class="td2" v-text="'$'+formatNumber(lote.cant_depo)"></td>
                                        <td class="td2" v-text="'$'+formatNumber(lote.monto_terreno)"></td>
                                        <td class="td2" v-text="lote.fecha_dep"></td>
                                    </tr>                               
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Historial de Ingresos -->
                    <div class="card-body" v-if="historial == 1">
                        <div class="form-group row">
                            <div class="col-md-7">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control" v-model="criterio">
                                        <option value="cliente">Cliente</option>
                                        <option value="fraccionamiento">Fraccionamiento</option>
                                    </select>
                                    <template v-if="criterio == 'cliente'">
                                        <input type="text" class="form-control" v-model="buscar" placeholder="Cliente" @keyup.enter="listarHistorial()">
                                    </template>
                                    <template v-else-if="criterio == 'fraccionamiento'">
                                        <select class="form-control" @click="selectEtapa(buscar)" v-model="buscar" @keyup.enter="listarHistorial()" >
                                            <option value="">Seleccionar</option>
                                            <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                        </select>
                                        <select class="form-control" v-model="b_etapa" @keyup.enter="listarHistorial()"> 
                                            <option value="">Etapa</option>
                                            <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                        </select>
                                    </template>
                                    
                                </div>
                            </div>
                            <div class="col-md-7" v-if="criterio=='fraccionamiento'">
                                <div class="input-group">
                                    <input type="text" class="form-control" v-model="b_manzana" placeholder="Manzana" @keyup.enter="listarHistorial()">
                                    <input type="text" class="form-control" v-model="b_lote" placeholder="Lote" @keyup.enter="listarHistorial()">
                                </div>
                            </div>
                            
                        </div>
                        <div class="form-group row">
                            <div class="col-md-7">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control" disabled>
                                        <option value="fecha">Fecha de ingreso</option>
                                    </select>
                                    <template>
                                        <input type="date" class="form-control" v-model="b_fecha">
                                        <input type="date" class="form-control" v-model="b_fecha2">
                                    </template>
                                    
                                    
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-5">
                                <div class="input-group">
                                    <select class="form-control" v-model="b_cuenta" @keyup.enter="listarHistorial()">
                                        <option value="">Seleccione</option>
                                        <option v-for="banco in arrayBancos" :key="banco.num_cuenta + '-' + banco.banco" :value="banco.num_cuenta + '-' + banco.banco" v-text="banco.num_cuenta + '-' + banco.banco"></option>
                                    </select>
                                    <button type="submit" @click="listarHistorial()" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Fraccionamiento</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th># Lote</th>
                                        <th>Cliente</th>
                                        <th>Clasificación</th>
                                        <th>Importe</th>
                                        <th>Fecha deposito</th>
                                        <th>Cuenta</th>
                                        <th>Fecha de ingreso</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="lote in arrayHistorial" :key="lote.depId" @dblclick="abrirModal('detalle',lote)" title="Doble clic">
                                        
                                        <td><a href="#" v-text="lote.fraccionamiento"></a></td>
                                        <td v-text="lote.etapa"></td>
                                        <td v-text="lote.manzana"></td>
                                        <td v-text="lote.num_lote"></td>
                                        <td class="td2" v-text="lote.nombre + ' ' + lote.apellidos"></td>
                                        <template v-if="lote.tipo == 0">
                                            <td class="td2" v-text="lote.concepto"></td>
                                        </template>
                                        <template v-else>
                                            <td class="td2" v-text="'Deposito de crédito'"></td>
                                        </template>
                                        <td class="td2" v-text="'$'+formatNumber(lote.monto_terreno)"></td>
                                        <td class="td2" v-text="lote.fecha"></td>
                                        <td class="td2" v-text="lote.cuenta"></td>
                                        <td class="td2" v-text="lote.fecha_ingreso_concretania"></td>
                                    </tr>  
                                    <tr>
                                        <th colspan="6" class="td2 text-right">Total</th>
                                        <th v-text="'$'+formatNumber(totalIngresos)" class="td2 text-center"></th>
                                        <th colspan="3"></th>    
                                    </tr>                             
                                </tbody>
                            </table>
                        </div>
                        
                    </div>

                    <!-- Edo de Cuenta del Terreno -->
                    <div class="card-body" v-if="historial == 2">
                        <div class="form-group row">
                            <div class="col-md-7">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control" v-model="criterio">
                                        <option value="cliente">Cliente</option>
                                        <option value="fraccionamiento">Fraccionamiento</option>
                                    </select>
                                    <template v-if="criterio == 'cliente'">
                                        <input type="text" class="form-control" v-model="buscar" placeholder="Cliente" @keyup.enter="listarContratos()">
                                    </template>
                                    <template v-else-if="criterio == 'fraccionamiento'">
                                        <select class="form-control" @click="selectEtapa(buscar)" v-model="buscar" @keyup.enter="listarContratos()">
                                            <option value="">Seleccionar</option>
                                            <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                        </select>
                                        <select class="form-control" v-model="b_etapa" @keyup.enter="listarContratos()"> 
                                            <option value="">Etapa</option>
                                            <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                        </select>
                                    </template>
                                    
                                </div>
                            </div>
                            <div class="col-md-7" v-if="criterio=='fraccionamiento'">
                                <div class="input-group">
                                    <input type="text" class="form-control" v-model="b_manzana" placeholder="Manzana" @keyup.enter="listarContratos()">
                                    <input type="text" class="form-control" v-model="b_lote" placeholder="Lote" @keyup.enter="listarContratos()">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-5">
                                <div class="input-group">
                                    <button type="submit" @click="listarContratos()" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>

                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a @click="cerrados = 0" class="nav-link" v-bind:class="{ 'text-info active': cerrados==0 }">
                                    Activos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a @click="cerrados = 1" class="nav-link" v-bind:class="{ 'text-info active': cerrados==1 }">
                                    Cerrados 
                                </a>
                            </li>
                        </ul>
                        
                        <div class="tab-content" v-if="cerrados == 0">
                            <br>

                            <div class="table-responsive">
                                <table class="table2 table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Fraccionamiento</th>
                                            <th>Etapa</th>
                                            <th>Manzana</th>
                                            <th># Lote</th>
                                            <th>Cliente</th>
                                            <th>Valor de venta</th>
                                            <th>Valor de escrituracion</th>
                                            <th>Valor de terreno</th>
                                            <th>Total pagado</th>
                                            <th>Por pagar</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="lote in arrayCuenta" :key="lote.id">
                                            
                                            <td v-text="lote.fraccionamiento"></td>
                                            <td v-text="lote.etapa"></td>
                                            <td v-text="lote.manzana"></td>
                                            <td v-text="lote.num_lote"></td>
                                            <td class="td2" v-text="lote.nombre + ' ' + lote.apellidos"></td>
                                            <td class="td2" v-text="'$'+formatNumber(lote.precio_venta)"></td>
                                            <td class="td2" v-if="lote.valor_escrituras != null" v-text="'$'+formatNumber(lote.valor_escrituras)"></td>
                                            <td class="td2" v-else v-text="'$'+formatNumber(0)"></td>
                                            <td class="td2" v-text="'$'+formatNumber(lote.monto_terreno)"></td>
                                            <td class="td2" v-text="'$'+formatNumber(lote.saldo_terreno)"></td>
                                            <td class="td2" v-text="'$'+formatNumber(lote.monto_terreno - lote.saldo_terreno)"></td>
                                            
                                                <td class="td2" v-if="lote.status == '1'">
                                                    <span class="badge badge-warning">Pendiente</span>
                                                </td>
                                                <td class="td2" v-if="lote.status == '3'">
                                                    <span class="badge badge-success">Firmado</span>
                                                </td>
                                        </tr>  
                                                                
                                    </tbody>
                                </table>
                            </div>
                            <nav>
                                <!--Botones de paginacion -->
                                <ul class="pagination">
                                    <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page > 7">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(1)">Inicio</a>
                                    </li>
                                    <li class="page-item" v-if="pagination.current_page > 1">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1)">Ant</a>
                                    </li>
                                    <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                                    </li>
                                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1)">Sig</a>
                                    </li>
                                    <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page<pagination.last_page">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.last_page)">Ultimo</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        <div class="tab-content" v-if="cerrados == 1">
                            <br>

                            <div class="table-responsive">
                                <table class="table2 table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Fraccionamiento</th>
                                            <th>Etapa</th>
                                            <th>Manzana</th>
                                            <th># Lote</th>
                                            <th>Cliente</th>
                                            <th>Valor de venta</th>
                                            <th>Valor de escrituracion</th>
                                            <th>Valor de terreno</th>
                                            <th>Total pagado</th>
                                            <th>Total devuelto</th>
                                            <th>Pendiente por devolver</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="lote in arrayCerrados" :key="lote.id" @dblclick="abrirModal('info',lote)">
                                            <td>
                                                <button v-if="(lote.transferido + lote.conc - lote.devuelto - lote.devueltoVirtual)<0" 
                                                    title="Generar transferencia a Concretania" type="button" @click="abrirModal('concretania',lote)" class="btn rounded btn-dark btn-sm">
                                                    <i class="fa fa-money"></i>
                                                </button>

                                            </td>
                                            <td>
                                                <a href="#" v-text="lote.fraccionamiento"></a>
                                            </td>
                                            <td v-text="lote.etapa"></td>
                                            <td v-text="lote.manzana"></td>
                                            <td v-text="lote.num_lote"></td>
                                            <td class="td2" v-text="lote.nombre + ' ' + lote.apellidos"></td>
                                            <td class="td2" v-text="'$'+formatNumber(lote.precio_venta)"></td>
                                            <td class="td2" v-if="lote.valor_escrituras != null" v-text="'$'+formatNumber(lote.valor_escrituras)"></td>
                                            <td class="td2" v-else v-text="'$'+formatNumber(0)"></td>
                                            <td class="td2" v-text="'$'+formatNumber(lote.monto_terreno)"></td>
                                            <td class="td2" v-text="'$'+formatNumber(lote.transferido)"></td>
                                            <td class="td2" v-text="'$'+formatNumber(lote.devuelto + lote.devueltoVirtual)"></td>
                                            <td v-if="lote.status == 0" class="td2" v-text="'$'+formatNumber(lote.transferido + lote.conc - lote.devuelto - lote.devueltoVirtual)"></td>
                                            <td v-else class="td2" v-text="'$'+formatNumber(0)"></td>
                                                <td class="td2" v-if="lote.status == '0'">
                                                    <span class="badge badge-danger">Cancelado</span>
                                                </td>
                                                <td class="td2" v-if="lote.status == '1'">
                                                    <span class="badge badge-warning">Pendiente</span>
                                                </td>
                                                <td class="td2" v-if="lote.status == '4'">
                                                    <span class="badge badge-success">Individualizado</span>
                                                </td>
                                                <td class="td2" v-if="lote.status == '3'">
                                                    <span class="badge badge-success">Vendido</span>
                                                </td>
                                        </tr>  
                                                                
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                    </div>

                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal-->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>

                        <div class="modal-body" v-if="tipoAccion == 1">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha</label>
                                    <div class="col-md-3">
                                        <input type="date" v-model="fecha" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Banco</label>
                                    <div class="col-md-6">
                                        <select class="form-control" v-model="cuenta">
                                            <option value="">Seleccione</option>
                                            <option v-for="banco in arrayBancos" :key="banco.num_cuenta + '-' + banco.banco" :value="banco.num_cuenta + '-' + banco.banco" v-text="banco.num_cuenta + '-' + banco.banco"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row"></div>

                                <div class="form-group row">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <h6 style="color:#2271b3;" ><strong> Total:  </strong></h6>&nbsp;
                                            <h6 style="color:#2271b3;" > &nbsp;{{'$'+formatNumber(total)}}</h6>
                                        </div>
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </form>
                        </div>

                        <div class="modal-body" v-if="tipoAccion == 2">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    
                                    <label class="col-md-2 form-control-label" for="text-input">Fraccionamiento</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="fraccionamiento" class="form-control" disabled>
                                    </div>

                                    
                                    <label class="col-md-1 form-control-label" for="text-input">Etapa</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="etapa" class="form-control" disabled>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    
                                    <label class="col-md-2 form-control-label" for="text-input">Manzana</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="manzana" class="form-control" disabled>
                                    </div>

                                    <label class="col-md-1 form-control-label" for="text-input">Lote</label>
                                    <div class="col-md-2">
                                        <input type="text" v-model="lote" class="form-control" disabled>
                                    </div>
                                </div>

                                <div class="form-group row"></div>
                                
                                <div class="form-group row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-8">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Valor de Terreno</th>
                                                    <th>Total pagado</th>
                                                    <th>Por pagar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    
                                                    <td v-text="'$'+formatNumber(monto_terreno)"></td>
                                                    <td v-text="'$'+formatNumber(pagado)"></td>
                                                    <td v-text="'$'+formatNumber(saldo)"></td>
                                                    
                                                </tr>  
                                                              
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                                
                            </form>
                        </div>

                        <div class="modal-body" v-if="tipoAccion == 3">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    
                                    <label class="col-md-2 form-control-label" for="text-input">Fraccionamiento</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="fraccionamiento" class="form-control" disabled>
                                    </div>

                                    
                                    <label class="col-md-1 form-control-label" for="text-input">Etapa</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="etapa" class="form-control" disabled>
                                    </div>
                                </div>
                                
                                
                                <div class="form-group row">
                                    
                                    <label class="col-md-2 form-control-label" for="text-input">Manzana</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="manzana" class="form-control" disabled>
                                    </div>

                                    <label class="col-md-1 form-control-label" for="text-input">Lote</label>
                                    <div class="col-md-2">
                                        <input type="text" v-model="lote" class="form-control" disabled>
                                    </div>
                                </div>

                                <div class="form-group row line-separator"></div>


                                <div class="form-group row" v-if="transferido > 0">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th colspan="3" style="color:green">Transferido</th>
                                                </tr>
                                                <tr>
                                                    <th>Fecha de ingreso</th>
                                                    <th>Cuenta</th>
                                                    <th>Monto</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="dev in arrayTransferidos" :key="dev.id+dev">
                                                    <td class="td2" v-text="dev.fecha_ingreso_concretania"></td>
                                                    <td class="td2" v-text="dev.cuenta"></td>
                                                    <td v-text="'$'+formatNumber(dev.monto_terreno)"></td>
                                                </tr>  
                                                <tr>
                                                    <td align="right" colspan="2"><strong>Total: </strong></td>
                                                    <td> ${{formatNumber(transferido)}} </td>
                                                </tr>                     
                                            </tbody>
                                        </table>
                                    </div>

                                </div>

                                <div class="form-group row" v-if="devuelto > 0">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th colspan="4" style="color:red">Devoluciones</th>
                                                </tr>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Cuenta</th>
                                                    <th>Cheque</th>
                                                    <th>Monto</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="dev in arrayDevoluciones" :key="dev.id">
                                                    <td class="td2" v-text="dev.fecha"></td>
                                                    <td class="td2" v-text="dev.cuenta"></td>
                                                    <td class="td2" v-text="dev.cheque"></td>
                                                    <td v-text="'$'+formatNumber(dev.devolver)"></td>
                                                </tr>  
                                                <tr>
                                                    <td align="right" colspan="3"><strong>Total: </strong></td>
                                                    <td> ${{formatNumber(devuelto)}} </td>
                                                </tr>                     
                                            </tbody>
                                        </table>
                                    </div>

                                </div>

                                <div class="form-group row" v-if="devueltoVirtual > 0">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th colspan="4" style="color:red">Devoluciones Virtuales</th>
                                                </tr>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Cuenta</th>
                                                    <th>Cheque</th>
                                                    <th>Monto</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="dev in dev_virtuales" :key="dev.id">
                                                    <td class="td2" v-text="dev.fecha"></td>
                                                    <td class="td2" v-text="dev.cuenta"></td>
                                                    <td class="td2" v-text="dev.cheque"></td>
                                                    <td v-text="'$'+formatNumber(dev.monto)"></td>
                                                </tr>  
                                                <tr>
                                                    <td align="right" colspan="3"><strong>Total: </strong></td>
                                                    <td> ${{formatNumber(devueltoVirtual)}} </td>
                                                </tr>                     
                                            </tbody>
                                        </table>
                                    </div>

                                </div>

                                <div class="form-group row" v-if="sumaConc > 0">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th colspan="4" style="color:red">Traspaso a Concretania</th>
                                                </tr>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Cuenta</th>
                                                    <th>Cheque</th>
                                                    <th>Monto</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="dev in depositoConcTransf" :key="dev.id">
                                                    <td class="td2" v-text="dev.fecha"></td>
                                                    <td class="td2" v-text="dev.cuenta"></td>
                                                    <td class="td2" v-text="dev.cheque"></td>
                                                    <td v-text="'$'+formatNumber(dev.monto)"></td>
                                                </tr>  
                                                <tr>
                                                    <td align="right" colspan="3"><strong>Total: </strong></td>
                                                    <td> ${{formatNumber(sumaConc)}} </td>
                                                </tr>                     
                                            </tbody>
                                        </table>
                                    </div>

                                </div>

                                <div class="form-group row">
                                    <div class="col-md-7">
                                    </div>

                                    <div class="col-md-5">
                                        <table class="table table-bordered table-striped table-sm">
                                            <tbody>
                                                <tr>
                                                    <th class="td2">+ Total transferido</th>
                                                    <td v-text="'$'+formatNumber(transferido)"></td>
                                                </tr> 
                                                <tr v-if="conc>0">
                                                    <th class="td2">+ Traspaso a Concretania</th>
                                                    <td v-text="'$'+formatNumber(sumaConc)"></td>
                                                </tr>  
                                                <tr>
                                                    <th class="td2">- Devoluciones virtuales</th>
                                                    <td v-text="'$'+formatNumber(devueltoVirtual)"></td>
                                                </tr>  
                                                <tr>
                                                    <th class="td2">- Devoluciones</th>
                                                    <td v-text="'$'+formatNumber(devuelto)"></td>
                                                </tr>  
                                                <tr>
                                                    <th>
                                                        Pendiente
                                                    </th>
                                                    <th v-text="'$'+formatNumber(transferido + sumaConc - devuelto - devueltoVirtual)"></th>
                                                </tr>
                                                                 
                                            </tbody>
                                        </table>
                                    </div>

                                </div>

                                
                                
                            </form>
                        </div>

                        <div class="modal-body" v-if="tipoAccion == 4">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Proyecto</label>
                                    <div class="col-md-3">
                                        <input type="text" disabled v-model="fraccionamiento" class="form-control" >
                                    </div>

                                    <label class="col-md-1 form-control-label" for="text-input">Etapa</label>
                                    <div class="col-md-4">
                                        <input type="text" disabled v-model="etapa" class="form-control">
                                    </div>

                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Manzana</label>
                                    <div class="col-md-5">
                                        <input type="text" disabled v-model="manzana" class="form-control" >
                                    </div>

                                    <label class="col-md-1 form-control-label" for="text-input">Lote</label>
                                    <div class="col-md-2">
                                        <input type="text" disabled v-model="lote" class="form-control">
                                    </div>

                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Cliente</label>
                                    <div class="col-md-8">
                                        <input type="text" disabled v-model="cliente" class="form-control" >
                                    </div>
                                </div>

                                <div class="form-group row line-separator"></div>

                                
                                <div class="form-group row" >
                                    <div class="col-md-12">
                                        <h5 align="center"><strong> Máximo a transferir </strong></h5>
                                    </div>
                                    <div class="col-md-12">
                                        <h5 align="center"><strong> ${{ formatNumber(sumaConc)}} </strong></h5>
                                    </div>
                                </div>

                                <div class="form-group row" >
                                    <label class="col-md-3 form-control-label" for="text-input"  >Cantidad a transferir</label>
                                    <div  class="col-md-2">
                                        <input type="text" pattern="\d*" v-on:keypress="isNumber($event)" @change="validar()" v-model="cant_dev" class="form-control" placeholder="Concepto" >
                                    </div>
                                    <div class="col-md-2">
                                        <h6><strong> ${{ formatNumber(cant_dev)}} </strong></h6>
                                    </div>
                                </div>
                                

                                <div class="form-group row line-separator"></div>

                                <div class="form-group row"> 
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha</label>
                                    <div class="col-md-4">
                                        <input type="date" v-model="fecha" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row"> 
                                    <label class="col-md-3 form-control-label" for="text-input"># Cheque</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="cheque" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Cuenta de Banco</label>
                                    <div class="col-md-6">
                                        <select class="form-control" v-model="cuenta">
                                            <option value="">Seleccione</option>
                                            <option v-for="banco in arrayBancos" :key="banco.num_cuenta + '-' + banco.banco" :value="banco.num_cuenta + '-' + banco.banco" v-text="banco.num_cuenta + '-' + banco.banco"></option>
                                        </select>
                                    </div>
                                </div>
                                
                                
                            </form>
                            <!-- fin del form solicitud de avaluo -->

                        </div>

                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==1 && fecha != '' && cuenta != '' && ver == 1" class="btn btn-primary" @click="registrarIngreso()">Enviar</button>
                            <button type="button" v-if="tipoAccion==4 && fecha != '' && cuenta != '' && cant_dev > 0" class="btn btn-primary" @click="guardarTransferencia()">Guardar</button>
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
                proceso:false,
                allSelected: false,
                id:0,
                arrayLotes : [],
                arrayHistorial : [],
                lotes_ini : [],
                arrayEtapas: [],
                arrayBancos: [],
                arrayFraccionamientos:[],
                arrayCuenta:[],
                arrayCerrados:[],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                historial:0,
                errorLotes : 0,
                errorMostrarMsjLotes : [],
                
                criterio : 'lotes.fraccionamiento_id', 
                buscar : '',
                b_etapa:'',
                b_manzana:'',
                b_lote:'',
                b_fecha:'',
                b_fecha2:'',
                b_cuenta:'',
                paquete:'',
                fecha:'',
                cuenta:'',
                criterio:'cliente',
                total:0,
                cant_dev:0,
                cliente:'',

                 pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,

                monto_terreno:0,
                saldo:0,
                pagado:0,
                fraccionamiento:'',
                etapa:'',
                manzana:'',
                lote:'',
                lote_id:'',

                ver:0,
                cerrados : 0,

                devuelto : 0,
                devueltoVirtual : 0,
                arrayDevoluciones:[],
                dev_virtuales:[],
                arrayTransferidos:[],
                transferido:0,
                depositoConcTransf:[],
                sumaConc : 0,
                cheque:'',

            }
        },
        computed:{
            totalIngresos: function(){
                var total =0.0;
                for(var i=0;i<this.arrayHistorial.length;i++){
                    total += parseFloat(this.arrayHistorial[i].monto_terreno)
                }
                if(total < 0)
                    total = 0;
                total = Math.round(total*100)/100;
                return total;
            },
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
            
            selectAll: function() {
                this.lotes_ini = [];

                if (!this.allSelected) {
                    for (var lote in this.arrayLotes) {
                        this.lotes_ini.push(this.arrayLotes[lote]);
                    }
                }
            },
            select: function() {
                this.allSelected = false;
            },

            selectFraccionamientos(){
                let me = this;
                if(me.modal == 0){
                    me.buscar=""
                    me.b_etapa=""
                    me.b_manzana=""
                }
                
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

            cambiarPagina(page){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarContratos(page);
            },

            selectEtapa(buscar){
                let me = this;
                if(me.modal == 0){
                    me.b_etapa="";
                    me.b_manzana="";
                    me.b_lote="";
                }
                
                me.arrayEtapas=[];
                var url = '/select_etapa_proyecto?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEtapas = respuesta.etapas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectCuenta(empresa){
                let me = this;
                me.arrayBancos=[];
                var url = '/select_cuenta?empresa='+empresa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayBancos = respuesta.cuentas;
                })
                .catch(function (error) {
                    console.log(error);
                });

            },

            /**Metodo para mostrar los registros */
            listarLotes(){
                let me = this;
                me.lotes_ini = [];
                var url = '/ingresosConcretania/pendeintesIngresar?b_fecha=' + me.b_fecha + '&b_fecha2=' + me.b_fecha2;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLotes = respuesta.pendientes;
                    me.arrayLotes.sort((b, a) => (a.fecha_dep < b.fecha_dep) ? 1 : -1);
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            listarContratos(page){
                let me = this;
                me.lotes_ini = [];
                var url = '/ingresosConcretania/indexEdoCuentaTerreno?page=' + page + '&buscar=' + me.buscar + '&etapa=' + me.b_etapa
                + '&manzana=' + me.b_manzana + '&lote=' + me.b_lote + '&criterio=' + me.criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayCuenta = respuesta.contratos.data;
                    me.pagination = respuesta.pagination;
                    me.listarCerrados();
                    
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listarCerrados(){
                let me = this;
                me.arrayCerrados = [];
                var url = '/ingresosConcretania/indexTerrenosCerrados?buscar=' + me.buscar + '&etapa=' + me.b_etapa
                + '&manzana=' + me.b_manzana + '&lote=' + me.b_lote + '&criterio=' + me.criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayCerrados = respuesta;
                    
                })
                .catch(function (error) {
                    console.log(error);
                });

            },

            /**Metodo para mostrar los registros */
            listarHistorial(){
                let me = this;
                var url = '/ingresosConcretania/historialIngresos?fecha=' + me.b_fecha + '&fecha2=' + me.b_fecha2 + '&buscar=' + me.buscar + '&etapa=' + me.b_etapa
                + '&manzana=' + me.b_manzana + '&lote=' + me.b_lote + '&criterio=' + me.criterio + '&cuenta=' + me.b_cuenta;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayHistorial = respuesta.ingresos;
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
                me.listarContratos(page);
            },

            registrarIngreso(){
                if(this.proceso==true){
                    return;
                }
                
                this.proceso=true;
                this.ver=0;

                let me = this;
                //Con axios se llama el metodo update de DepartamentoController
                
                me.lotes_ini.forEach(element => {

                    
                    axios.put('/ingresosConcretania/guardarIngreso',{
                    'id': element['id'],
                    'depId': element['depId'],
                    'tipo': element['tipo'],
                    'monto_terreno': element['monto_terreno'],
                    'cuenta' : this.cuenta,
                    'fecha_ingreso_concretania': this.fecha
                    }); 
                });
                Swal({
                    title: 'Enviado!',
                    text: 'RUVs solicitados correctamente.',
                    imageUrl: 'https://d2r6jp7chi630e.cloudfront.net/blog/aritic-pinpoint/wp-content/uploads/sites/3/2016/09/email-gif.gif',
                    imageWidth: 800,
                    imageHeight: 400,
                    imageAlt: 'Custom image',
                    animation: true
                })
                me.proceso=false;
                me.ver=1;
                me.cerrarModal();
                me.listarLotes();
                
            },
            
            /**Metodo para registrar  */
            
            
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.errorLotes = 0;
                this.errorMostrarMsjLotes = [];
                this.lotes_ini = [];
                this.allSelected = false;
                this.total = 0;
                this.selectCuenta('');
                this.sumaConc = 0;
                this.cant_dev = 0;
            },

            guardarTransferencia(){
                let me = this;
                axios.post('/reubicaciones/storeConc',{
                'contrato_id':this.id,
                'cuenta':this.cuenta,
                'fecha':this.fecha,
                'lote_id':this.lote_id,
                'cuenta_conc':this.cuenta,
                'cheque_conc':this.cheque,
                'monto_conc':this.cant_dev,
                'devolucion':1

                }).then(function (response){
                me.cerrarModal(); //al guardar el registro se cierra el modal
                me.listarCerrados();
                //Se muestra mensaje Success
                swal({
                    position: 'top-end',
                    type: 'success',
                    title: 'Traspaso creados correctamente',
                    showConfirmButton: false,
                    timer: 1500
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            isNumber: function(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46 ) {
                    evt.preventDefault();;
                } else {
                    return true;
                }
            },

            validar(){
                if(this.cant_dev>this.sumaConc)
                    this.cant_dev = this.sumaConc;
            },

            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(accion,data =[]){
                if(this.lotes_ini.length<1 && accion == 'enviar'){
                    Swal({
                    title: 'No se ha seleccionado ningun lote',
                    animation: false,
                    customClass: 'animated tada'
                    })
                    return;
                }
                switch(accion){
                    case 'enviar':
                    {
                        for (var lote in this.lotes_ini) {
                            this.total += this.lotes_ini[lote].monto_terreno;
                        }
                        this.modal = 1;
                        this.tituloModal = 'Enviar ingresos';
                        this.fecha ='';
                        this.cuenta = '';
                        this.tipoAccion = 1;
                        this.ver = 1;
                        break;
                    }
                    case 'detalle':
                    {
                        this.modal = 1;
                        this.tituloModal = 'Estado de cuenta';
                        this.fraccionamiento = data['fraccionamiento'];
                        this.etapa = data['etapa'];
                        this.manzana = data['manzana'];
                        this.lote = data['num_lote'];
                        this.pagado = data['saldo_terreno'];
                        this.monto_terreno = data['valor_terreno'];
                        this.saldo = this.monto_terreno - this.pagado;
                        this.tipoAccion = 2;
                        break;
                    }
                    case 'info':
                    {
                        this.modal = 1;
                        this.tituloModal = 'Detalle';
                        this.fraccionamiento = data['fraccionamiento'];
                        this.etapa = data['etapa'];
                        this.manzana = data['manzana'];
                        this.lote = data['num_lote'];
                        this.devuelto = data['devuelto'];
                        this.devueltoVirtual = data['devueltoVirtual']
                        this.arrayDevoluciones = data['devoluciones'];
                        this.dev_virtuales = data['dev_virtuales'];
                        this.arrayTransferidos = data['depositos_transferidos'];
                        this.transferido = data['transferido'];
                        this.depositoConcTransf = data['depositoConcTransf'];
                        this.sumaConc = data['conc'];
                        
                        this.tipoAccion = 3;
                        break;
                    }
                    case 'concretania':
                    {
                        this.modal = 1;
                        this.tipoAccion = 4;
                        this.tituloModal = 'Transferencia a Concretania';
                        this.fraccionamiento = data['fraccionamiento'];
                        this.etapa = data['etapa'];
                        this.manzana = data['manzana'];
                        this.lote = data['num_lote'];

                        this.sumaConc = (data['transferido'] + data['conc'] - data['devuelto'] - data['devueltoVirtual'])*-1;
                        this.cant_dev = this.sumaConc;
                        this.cheque = '';
                        this.cliente = data['nombre']+ ' ' + data['apellidos'];
                        this.id = data['id'];
                        this.lote_id = data['lote_id'];



                        this.selectCuenta('CONCRETANIA');
                    }
                }
            }
        },
        mounted() {
            this.listarLotes();
            this.selectFraccionamientos();
            this.selectCuenta('');
            this.listarContratos();
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
</style>
