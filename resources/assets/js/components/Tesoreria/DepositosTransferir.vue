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
                        <i class="fa fa-align-justify" v-if="listado == 1"> Pendientes por transferir</i>
                        <i class="fa fa-align-justify" v-if="listado == 2"> Traspasos a GCC</i>
                        <i class="fa fa-align-justify" v-if="listado == 3"> Traspasos a Concretania</i>

                        <button class="btn btn-primary" v-if="listado == 1" @click="listado = 2 , transferenciasGCC(1)">Traspasos a GCC</button>
                        <button class="btn btn-info" v-if="listado == 1" @click="listado = 3 , transferenciasConc(1)">Traspasos a Concretania</button>
                        <button class="btn btn-warning" v-if="listado == 2 || listado == 3" @click="listado = 1">Volver</button>
                    </div>

                    <!-- listado de devoluciones -->
                    <div v-if="listado == 1" class="card-body">
                        
                        <div class="form-group row">
                            <div class="col-md-7">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-4" v-model="criterio" @change="selectFraccionamientos()">
                                        <option value="lotes.fraccionamiento_id">Proyecto</option>
                                        <option value="creditos.id"># Folio</option>
                                    </select>
                                    
                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar"  @keyup.enter="listarDepositos()" @change="selectEtapa(buscar)">
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>

                                    <input type="text" class="form-control" v-if="criterio != 'lotes.fraccionamiento_id'" v-model="buscar" @keyup.enter="listarDepositos()" placeholder="Texto a Buscar">
                                   
                                </div>
                                <div class="input-group" v-if="criterio=='lotes.fraccionamiento_id'">
                                    <select class="form-control"  v-model="b_etapa"  @keyup.enter="listarDepositos()" @change="selectManzanas(buscar,b_etapa)"> 
                                        <option value="">Etapa</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                    </select>

                                    <select class="form-control" @keyup.enter="listarDepositos()" v-model="b_manzana" >
                                        <option value="">Seleccione</option>
                                        <option v-for="manzana in arrayManzanas" :key="manzana.manzana" :value="manzana.manzana" v-text="manzana.manzana"></option>
                                    </select>
                                </div>
                                <div class="input-group" v-if="criterio=='lotes.fraccionamiento_id'">
                                    <input type="text" class="form-control col-md-5" v-model="b_lote" @keyup.enter="listarDepositos()" placeholder="Número de Lote">
                                </div>
                                <div class="input-group">
                                    <input type="text" disabled class="form-control col-md-4" placeholder="Fecha de reubicación">
                                    <input type="date" class="form-control col-md-4" v-model="b_fecha1">
                                    <input type="date" class="form-control col-md-4" v-model="b_fecha2">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <button type="submit" @click="listarDepositos()" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr> 
                                        <th>Folio</th>
                                        <th>Fraccionamiento</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th>Lote</th>
                                        <th>Convenio</th>
                                        <th>Pago Deposito</th>
                                        <th>Concretania</th>
                                        <th>GCC</th>
                                        <th>V. Terreno</th>
                                        <th>Saldo Terreno</th>
                                    </tr>
                                </thead>
                                <tbody v-for="contratos in arrayContratos" :key="contratos.id">
                                    <tr v-if="contratos.reubicacion != null" style="backgroundColor:#B8FCC0" v-on:dblclick="abrirModal('devolucion',contratos)" title="Doble click" > 
                                        <td class="td2" v-text="contratos.folio"></td>
                                        <td class="td2">
                                            <a href="#" v-text="contratos.fraccionamiento"></a>
                                        </td>
                                        <td class="td2" v-text="contratos.etapa"></td>
                                        <td class="td2" v-text="contratos.manzana"></td>
                                        <td class="td2" v-text="contratos.num_lote"></td>
                                        <td class="td2">
                                            <template v-if="contratos.emp_constructora != contratos.emp_terreno">
                                                    GCC - Conc
                                            </template>
                                            <template v-else-if="contratos.emp_constructora == 'CONCRETANIA'">
                                                    Concretania
                                            </template>
                                             <template v-else>
                                                    GCC
                                            </template>
                                        </td>
                                        <td class="td2" v-text="'$'+formatNumber(contratos.cant_depo_act)"></td>
                                        <td class="td2" v-text="'$'+formatNumber(contratos.cant_depo_act - contratos.saldo_terreno_act)"></td>
                                        <td class="td2" v-text="'$'+formatNumber(contratos.saldo_terreno_act)"></td>
                                        <td class="td2" v-text="'$'+formatNumber(contratos.valor_terreno)"></td>
                                        <td class="td2" v-text="'$'+formatNumber(contratos.valor_terreno - contratos.saldo_terreno_act)"></td>
                                    </tr>
                                    <tr v-if="contratos.reubicacion != null" v-on:dblclick="abrirModal('devolucion',contratos)">
                                        <td class="td2"></td>
                                        <td class="td2">
                                            <a href="#" v-text="contratos.reubicacion.fraccionamiento"></a>
                                        </td>
                                        <td class="td2" v-text="contratos.reubicacion.etapa"></td>
                                        <td class="td2" v-text="contratos.reubicacion.manzana"></td>
                                        <td class="td2" v-text="contratos.reubicacion.num_lote"></td>
                                        <td class="td2">
                                            <template v-if="contratos.reubicacion.emp_constructora != contratos.reubicacion.emp_terreno">
                                                    GCC - Conc
                                            </template>
                                            <template v-else-if="contratos.reubicacion.emp_constructora == 'CONCRETANIA'">
                                                    Concretania
                                            </template>
                                             <template v-else>
                                                    GCC
                                            </template>
                                        </td>
                                        <td class="td2" v-text="'$'+formatNumber(contratos.reubicacion.cant_depo)"></td>
                                        <td class="td2" v-text="'$'+formatNumber(contratos.reubicacion.cant_depo - contratos.reubicacion.gcc)"></td>
                                        <td class="td2" v-text="'$'+formatNumber(contratos.reubicacion.gcc)"></td>
                                        <td class="td2" v-text="'$'+formatNumber(contratos.reubicacion.valor_terreno)"></td>
                                        <td class="td2" v-text="'$'+formatNumber(contratos.reubicacion.valor_terreno - contratos.reubicacion.saldo_terreno_ant)"></td>
                                    </tr>
                                    <tr v-if="contratos.reubicacion != null">
                                        <td colspan="11"> </td>
                                    </tr>                               
                                </tbody>
                            </table>  
                        </div>
                        
                    </div>

                    <!-- listado de historial de devoluciones -->
                    <div v-if="listado == 2" class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-4" v-model="criterio" @change="selectFraccionamientos()">
                                        <option value="lotes.fraccionamiento_id">Proyecto</option>
                                        <option value="personal.nombre">Cliente</option>
                                        <option value="creditos.id"># Folio</option>
                                    </select>
                                    
                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar"  @keyup.enter="transferenciasGCC(1)" @change="selectEtapa(buscar)">
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>
                                    <input type="text" v-else v-model="buscar" class="form-control col-md-6" placeholder="Texto a buscar">
                                </div>
                                <div class="input-group" v-if="criterio=='lotes.fraccionamiento_id'">
                                    <select class="form-control" v-model="b_etapa"  @keyup.enter="transferenciasGCC(1)" @change="selectManzanas(buscar,b_etapa)"> 
                                        <option value="">Etapa</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                    </select>

                                    <select class="form-control" v-model="b_manzana" >
                                        <option value="">Seleccione</option>
                                        <option v-for="manzana in arrayManzanas" :key="manzana.manzana" :value="manzana.manzana" v-text="manzana.manzana"></option>
                                    </select>
                                </div>
                                <div class="input-group" v-if="criterio=='lotes.fraccionamiento_id'">
                                    <input type="text" v-model="b_lote" class="form-control col-md-6" placeholder="Lote a buscar">
                                </div>
                                <div class="input-group">
                                    <button type="submit" @click="transferenciasGCC(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                               
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr> 
                                        
                                        <th># Ref</th>
                                        <th>Proyecto</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th>Lote</th>
                                        <th>Convenio</th>
                                        <th>Monto</th>
                                        <th>Fecha</th>
                                        <th># Cheque</th>
                                        <th>Cuenta</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="gcc in arrayTranspGCC" :key="gcc.id" > 
                                    <template>
                                        <td class="td2" v-text="gcc.id"></td>
                                        <td class="td2" v-text="gcc.proyecto"></td>
                                        <td class="td2" v-text="gcc.etapa"></td>
                                        <td class="td2" v-text="gcc.manzana"></td>
                                        <td class="td2" v-text="gcc.num_lote"></td>
                                        <td class="td2">
                                            <template v-if="gcc.emp_constructora != gcc.emp_terreno">
                                                    GCC - Conc
                                            </template>
                                            <template v-else-if="gcc.emp_constructora == 'CONCRETANIA'">
                                                    Concretania
                                            </template>
                                             <template v-else>
                                                    GCC
                                            </template>
                                        </td>
                                        <td class="td2" v-text="'$'+formatNumber(gcc.monto)"></td>
                                        <td class="td2" v-text="this.moment(gcc.fecha).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td class="td2" v-text="gcc.cheque"></td>
                                        <td class="td2" v-text="gcc.cuenta"></td>
                                    </template>
                                    </tr>                               
                                </tbody>
                            </table>  
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination2.last_page > 7 && pagination2.current_page > 7">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(1)">Inicio</a>
                                </li>
                                <li class="page-item" v-if="pagination2.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page - 1)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber2" :key="page" :class="[page == isActived2 ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(page)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination2.current_page < pagination2.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page + 1)">Sig</a>
                                </li>
                                <li class="page-item" v-if="pagination2.last_page > 7 && pagination2.current_page<pagination2.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.last_page)">Ultimo</a>
                                </li>
                            </ul>
                        </nav>
                    </div>

                    <!-- listado de historial de devoluciones -->
                    <div v-if="listado == 3" class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-4" v-model="criterio" @change="selectFraccionamientos()">
                                        <option value="lotes.fraccionamiento_id">Proyecto</option>
                                        <option value="personal.nombre">Cliente</option>
                                        <option value="creditos.id"># Folio</option>
                                    </select>
                                    
                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar"  @keyup.enter="transferenciasGCC(1)" @change="selectEtapa(buscar)">
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>
                                    <input type="text" v-else v-model="buscar" class="form-control col-md-6" placeholder="Texto a buscar">
                                </div>
                                <div class="input-group" v-if="criterio=='lotes.fraccionamiento_id'">
                                    <select class="form-control" v-model="b_etapa"  @keyup.enter="transferenciasGCC(1)" @change="selectManzanas(buscar,b_etapa)"> 
                                        <option value="">Etapa</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                    </select>

                                    <select class="form-control" v-model="b_manzana" >
                                        <option value="">Seleccione</option>
                                        <option v-for="manzana in arrayManzanas" :key="manzana.manzana" :value="manzana.manzana" v-text="manzana.manzana"></option>
                                    </select>
                                </div>
                                <div class="input-group" v-if="criterio=='lotes.fraccionamiento_id'">
                                    <input type="text" v-model="b_lote" class="form-control col-md-6" placeholder="Lote a buscar">
                                </div>
                                <div class="input-group">
                                    <button type="submit" @click="transferenciasConc(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                               
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr> 
                                        
                                        <th># Ref</th>
                                        <th>Proyecto</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th>Lote</th>
                                        <th>Convenio</th>
                                        <th>Monto</th>
                                        <th>Fecha</th>
                                        <th># Cheque</th>
                                        <th>Cuenta</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="conc in arrayTranspConc" :key="conc.id" > 
                                    <template>
                                        <td class="td2" v-text="conc.id"></td>
                                        <td class="td2" v-text="conc.proyecto"></td>
                                        <td class="td2" v-text="conc.etapa"></td>
                                        <td class="td2" v-text="conc.manzana"></td>
                                        <td class="td2" v-text="conc.num_lote"></td>
                                        <td class="td2">
                                            <template v-if="conc.emp_constructora != conc.emp_terreno">
                                                    GCC - Conc
                                            </template>
                                            <template v-else-if="conc.emp_constructora == 'CONCRETANIA'">
                                                    Concretania
                                            </template>
                                             <template v-else>
                                                    conc
                                            </template>
                                        </td>
                                        <td class="td2" v-text="'$'+formatNumber(conc.monto)"></td>
                                        <td class="td2" v-text="this.moment(conc.fecha).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td class="td2" v-text="conc.cheque"></td>
                                        <td class="td2" v-text="conc.cuenta"></td>
                                    </template>
                                    </tr>                               
                                </tbody>
                            </table>  
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination2.last_page > 7 && pagination2.current_page > 7">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(1)">Inicio</a>
                                </li>
                                <li class="page-item" v-if="pagination2.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page - 1)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber2" :key="page" :class="[page == isActived2 ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(page)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination2.current_page < pagination2.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page + 1)">Sig</a>
                                </li>
                                <li class="page-item" v-if="pagination2.last_page > 7 && pagination2.current_page<pagination2.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.last_page)">Ultimo</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
         

            <!--Inicio del modal -->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal == 1}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <!-- form para generar devolucion -->
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Proyecto</label>
                                    <div class="col-md-3">
                                        <input type="text" disabled v-model="proyecto" class="form-control" >
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
                                    <label class="col-md-2 form-control-label" for="text-input">Convenio actual</label>
                                    <div class="col-md-8">
                                        <input type="text" disabled v-model="convenio_act" class="form-control" >
                                    </div>
                                </div>

                                 <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Convenio anterior</label>
                                    <div class="col-md-8">
                                        <input type="text" disabled v-model="convenio_ant" class="form-control" >
                                    </div>
                                </div>

                                <div class="form-group row"> 
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha</label>
                                    <div class="col-md-4">
                                        <input type="date" v-model="fecha_devolucion" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row line-separator"></div>
                                    <div class="form-group row"> 
                                        <div class="col-md-12">
                                            <h6><center>GCC</center></h6>
                                        </div>
                                    </div>

                                    <div class="form-group row"> 
                                        <label class="col-md-3 form-control-label" for="text-input"># Cheque</label>
                                        <div class="col-md-4">
                                            <input :disabled="verGCC == 0" type="text" v-model="cheque_gcc" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Cuenta de Banco GCC</label>
                                        <div class="col-md-6">
                                            <select :disabled="verGCC == 0" class="form-control" v-model="banco_gcc">
                                                <option value="">Seleccione</option>
                                                <option v-for="banco in arrayCuentaGG" :key="banco.num_cuenta + '-' + banco.banco" :value="banco.num_cuenta + '-' + banco.banco" v-text="banco.num_cuenta + '-' + banco.banco"></option>
                                            </select>
                                        </div>
                                    </div>  

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input"><strong>Monto</strong></label>
                                        <div class="col-md-4">
                                            <input :disabled="verGCC == 0" type="text" pattern="\d*" v-on:keypress="isNumber($event)" v-model="cant_dev" maxlength="10" class="form-control">
                                        </div>
                                        <div class="col-md-4">
                                            <h6 v-text="'$'+formatNumber(cant_dev)"></h6>
                                        </div>
                                    </div>
                                   

                                
                                <div class="form-group row line-separator"></div>

                                <template v-if="tipoAccion == 1">

                                     <div class="form-group row"> 
                                        <div class="col-md-12">
                                            <h6><center>CONCRETANIA</center></h6>
                                        </div>
                                    </div>

                                    <div class="form-group row"> 
                                        <label class="col-md-3 form-control-label" for="text-input"># Cheque</label>
                                        <div class="col-md-4">
                                            <input :disabled="verConc == 0" type="text" v-model="cheque_conc" class="form-control">
                                        </div>
                                    </div>

                                   
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Cuenta de Banco Concretania</label>
                                        <div class="col-md-6">
                                            <select class="form-control" v-model="banco_conc">
                                                <option value="">Seleccione</option>
                                                <option v-for="banco in arrayCuentaConc" :key="banco.num_cuenta + '-' + banco.banco" :value="banco.num_cuenta + '-' + banco.banco" v-text="banco.num_cuenta + '-' + banco.banco"></option>
                                            </select>
                                        </div>
                                    </div>  

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input"><strong>Monto</strong></label>
                                        <div class="col-md-4">
                                            <input :disabled="verConc == 0" type="text" pattern="\d*" v-on:keypress="isNumber($event)" v-model="cant_conc" maxlength="10" class="form-control">
                                        </div>
                                        <div class="col-md-4">
                                            <h6 v-text="'$'+formatNumber(cant_conc)"></h6>
                                        </div>
                                    </div>

                                    
                                </template>
                                
                            </form>
                            <!-- fin del form solicitud de avaluo -->

                            <!-- Div para mostrar los errores que mande validerDepartamento -->
                            <div v-show="errorDev" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjDev" :key="error" v-text="error">
                                    </div>
                                </div>
                            </div>


                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" class="btn btn-primary" @click="guardarTransferencia()">Guardar</button>
                        </div>
                    </div>
                      <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal consulta-->
            
         
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
                id: 0,

                arrayContratos : [],
                arrayFraccionamientos:[],
                arrayEtapas:[],
                arrayManzanas:[],
                arrayCuentaGG : [],
                arrayCuentaConc:[],
                arrayTranspGCC: [],
                arrayTranspConc: [],

                devolucionVirutal:[],


                errorDev:0,
                errorMostrarMsjDev:[],

                modal : 0,
                proyecto : '',
                etapa: '',
                manzana: '',
                lote: '',
                lote_id:'',
                contrato_id:'',
                banco_gcc:'',
                banco_conc:'',
                fecha_devolucion:'',
                cheque_gcc:'',
                cheque_conc:'',
                observacion: '',
                cant_dev : 0,
                cant_conc:0,
                deposito_id:'',

                reubicacion:{},
                
                tituloModal : '',
           
                tipoAccion: 0,
                errorLote : 0,
                errorMostrarMsjLote : [],
                
                pagination2 : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset2 : 3,
                criterio : 'lotes.fraccionamiento_id', 
                buscar : '',
                b_etapa: '',
                b_manzana: '',
                b_lote: '',
                b_fecha1:'',
                b_fecha2:'',
                listado : 1,
                saldo_act : 0,
                convenio_act:'',
                convenio_ant:'',
                porc_terreno:'',
                verConc:0,
                verGCC:0
            }
        },
        computed:{
            // Funciones de la paginacion del historial de devoluciones
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

                var pagesArray = [];
                while(from <= to){
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            },

        },

        
        methods : {
            /**Metodo para mostrar los registros */
            listarDepositos(){
                let me = this;
                var url = '/reubicaciones/depositosPorReubicar?buscar=' + me.buscar + '&b_etapa=' + me.b_etapa + '&b_manzana=' + me.b_manzana + 
                '&b_lote=' + me.b_lote +  '&criterio=' + me.criterio + '&fecha1=' + me.b_fecha1 + '&fecha2=' + me.b_fecha2;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayContratos = respuesta;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            transferenciasGCC(page){
                let me = this;
                me.arrayTranspGCC = [];
                var url = '/reubicaciones/indexGCC?page=' + page + '&buscar=' + me.buscar + '&etapa=' + me.b_etapa + 
                '&manzana=' + me.b_manzana + '&lote=' + me.b_lote +  '&criterio=' + me.criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayTranspGCC = respuesta.gcc.data;
                    me.pagination2 = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },

            transferenciasConc(page){
                let me = this;
                me.arrayTranspConc = [];
                var url = '/reubicaciones/indexConc?page=' + page + '&buscar=' + me.buscar + '&etapa=' + me.b_etapa + 
                '&manzana=' + me.b_manzana + '&lote=' + me.b_lote +  '&criterio=' + me.criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayTranspConc = respuesta.conc.data;
                    me.pagination2 = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },

            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
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
            
            selectFraccionamientos(){
                let me = this;
                me.buscar=""
                
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

            selectEtapa(buscar){
                let me = this;
                me.buscar2=""
                
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

            selectManzanas(buscar1, buscar2){
                let me = this;
                me.b_manzana="";
                me.b_lote="";

                me.arrayManzanas=[];
                var url = '/select_manzanas_etapa?buscar=' + buscar1 + '&buscar1='+ buscar2;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayManzanas = respuesta.manzana;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectCuenta(empresa){
                let me = this;
                if(empresa == 'CONCRETANIA')
                    me.arrayCuentaConc=[];
                else
                    me.arrayCuentaGG=[];
                
                var url = '/select_cuenta?empresa='+empresa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    if(empresa == 'CONCRETANIA')
                        me.arrayCuentaConc = respuesta.cuentas;
                    else
                        me.arrayCuentaGG = respuesta.cuentas;
                })
                .catch(function (error) {
                    console.log(error);
                });

            },

            guardarTransferencia(){
                if(this.validarDev()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }
                let me = this;
                axios.post('/reubicaciones/storeDepositoReubicacion',{
                'id': this.id,
                'contrato_id':this.contrato_id,
                'cuenta_gcc':this.banco_gcc,
                'fecha':this.fecha_devolucion,
                'lote_id':this.lote_id,
                'cheque_gcc':this.cheque_gcc,
                'monto_gcc':this.cant_dev,

                'monto_conc':this.cant_conc,
                'cheque_conc':this.cheque_conc,
                'cuenta_conc':this.banco_conc,
                'devolucion':0

                }).then(function (response){
                me.cerrarModal(); //al guardar el registro se cierra el modal
                me.transferenciasGCC(me.pagination2.current_page);
                me.listarDepositos(); //se enlistan nuevamente los registros
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

            cambiarPagina2(page){
                let me = this;
                //Actualiza la pagina actual
                me.pagination2.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                if(me.listado == 2)
                    me.transferenciasGCC(page);
                else
                    me.transferenciasConc(page);
            },

            validarDev(){
                this.errorDev=0;
                this.errorMostrarMsjDev=[];

                if(this.cant_dev == '') //Si la variable departamento esta vacia
                    this.errorMostrarMsjDev.push("Ingresar cantidad a devolver.");

                if(this.fecha_devolucion == '') //Si la variable departamento esta vacia
                    this.errorMostrarMsjDev.push("Ingresar fecha de devolución.");

                if(this.errorMostrarMsjDev.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorDev = 1;

                return this.errorDev;
            },
       
            cerrarModal(){
                this.transferenciasGCC(this.pagination2.current_page);
                this.modal = 0;
                this.arrayObservacion = [];
                this.tituloModal = '';
                this.proyecto = "";
                this.etapa = "";
                this.manzana = "";
                this.lote = "";
                this.lote_id = '';
                this.convenio_act = "";
                this.banco_gcc = "";
                this.banco_conc
                this.fecha_devolucion = '';
                this.cheque_gcc = '';
                this.cheque_conc = '';
                this.cant_dev = 0;
                this.cant_conc = 0;
                this.verGCC = 0;
                this.verConc = 0;
                this.contrato_id = '';
            },

            getConvenio(constructora,terreno){
                let convenio = '';

                if(constructora != terreno)
                            convenio = 'GCC-Concretania';
                        else if(constructora == 'CONCRETANIA')
                            convenio = 'Concretania';
                        else
                            convenio = 'GCC';

                return convenio;
            },
        
      
            abrirModal(accion,data =[]){
                
                switch(accion){
                    
                    case 'devolucion':
                    {
                        this.modal =1;
                        this.tipoAccion = 1;
                        this.id = data['id'];
                        this.proyecto = data['fraccionamiento'];
                        this.etapa = data['etapa'];
                        this.manzana = data['manzana'];
                        this.lote = data['num_lote'];
                        this.lote_id = data['lote_id'];
                        this.contrato_id = data['folio'];

                        this.reubicacion = data['reubicacion'];
                        
                        this.convenio_act = this.getConvenio(data['emp_constructora'],data['emp_terreno']);
                        this.convenio_ant = this.getConvenio(this.reubicacion.emp_constructora, this.reubicacion.emp_terreno);

                        if(this.convenio_act == 'GCC'){
                            //Traspaso a cuenta de GCC
                            this.tituloModal='Generar traspaso a Cumbres';
                            this.cant_dev = data['cant_depo'] - data['gcc'];
                            this.tipoAccion = 2;
                            this.banco_gcc = '';
                            this.cheque_gcc = '';
                            this.verGCC = 1;
                        }
                        else{
                            if(this.convenio_ant == 'GCC'){
                                //Traspaso a cuenta de concretania, el resto se queda en GCC
                                this.tituloModal='Generar traspaso a Concretania';
                                this.tipoAccion = 1;
                                var porcentaje = 100-data['porcentaje_terreno'];
                                this.cant_conc = data['cant_depo']*(porcentaje/100);
                                this.cant_dev = data['cant_depo'] - this.cant_conc;
                                this.banco_conc = '';
                                this.cheque_conc = '';
                                this.verConc = 1;
                                this.banco_gcc = data['banco'];
                                this.cheque_gcc = 'Reubicacion de recibo: '+ data['num_recibo'];
                                this.verGCC = 0;
                            }
                            else{
                                this.tipoAccion = 1;
                                this.tituloModal='Reubicar depositos';
                                this.cant_dev = data['gcc'];
                                this.cant_conc = data['cant_depo']-data['gcc'];
                                this.banco_gcc = data['cuenta'];
                                this.banco_conc = data['banco'];
                                this.cheque_gcc = 'Reubicacion de recibo: '+ data['num_recibo'];
                                this.cheque_conc = 'Reubicacion de recibo: '+ data['num_recibo'];
                                this.verConc = 0;
                                this.verGCC = 0;
                            }
                        }

                        this.selectCuenta('CONCRETANIA');
                        this.selectCuenta('Grupo Constructor Cumbres');
                        break;
                    }      
                    
                    case 'info':
                    {
                        this.modal =1;
                        this.tituloModal='Devolución Virtual';
                        this.tipoAccion = 2;
                        this.id = data['id'];
                        this.proyecto = data['proyecto'];
                        this.etapa = data['etapa'];
                        this.manzana = data['manzana'];
                        this.lote = data['num_lote'];
                        this.cliente = data['nombre']+' '+data['apellidos'];
                        this.devolver = data['monto'];
                        this.cant_dev = data['monto'];
                        this.cheque_gcc =data['cheque_gcc'];
                        this.fecha_devolucion = data['fecha'];
                        this.banco = data['cuenta'];
                        this.observacion = data['observaciones'];

                        this.selectCuenta();
                        break;
                    }   
                }
            },
        
        },
       
        mounted() {
            this.listarDepositos();
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

    /*th {
    text-align: left;
    background-color: rgb(190, 220, 250);
    text-transform: uppercase;
    padding-top: 1rem;
    padding-bottom: 1rem;
    border-bottom: rgb(50, 50, 100) solid 2px;
    border-top: none;
    }*/

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
