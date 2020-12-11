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
                        <i class="fa fa-align-justify"></i>Facturacion
                    </div>
                    <div class="card-body">

                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a @click="tab = 1" v-text="'Depositos de pagares (' + arrayDepositos.total +')'" class="nav-link" v-bind:class="{ 'text-info active': tab==1 }">
                                </a>
                            </li>
                            <li class="nav-item">
                                <a @click="tab = 2" v-text="'Creditos Directos(' + arrayContratos.total +')'" class="nav-link" v-bind:class="{ 'text-info active': tab==2 }">
                                </a>
                            </li>
                            <li class="nav-item">
                                <a @click="tab = 3" v-text="'Creditos Escriturados(' + arrayLiqCredit.total +')'" class="nav-link" v-bind:class="{ 'text-info active': tab==3 }"></a>
                            </li>
                            <li class="nav-item">
                                <a @click="tab = 4" v-text="'Deposito a credito (' + arrayDepCredit.total +')'" class="nav-link" v-bind:class="{ 'text-info active': tab==4 }"></a>
                            </li>
                        </ul>

                        <!-- Facturas de Depositos (DEPOSITOS DE PAGARE) -->
                        <div class="container" v-if="tab == 1">
                            <div class="row"><!-- campos de busqueda-->
                                <select class="form-control col-md-2" v-model="b_empresa" >
                                    <option value="">Empresa constructora</option>
                                    <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                </select>
                                
                                <select class="form-control col-md-2" v-model="criterio">
                                    <option value="lotes.fraccionamiento_id">Proyecto</option>
                                    <option value="nombre">Cliente</option>
                                    <option value="depositos.folio_factura">Folio Factura</option>
                                    <option value="depositos.monto">Monto de Factura</option>
                                    <option value="depositos.cant_depo">Monto de Deposito</option>
                                </select>

                                <template v-if="criterio=='lotes.fraccionamiento_id'">
                                    <select class="form-control col-md-2" v-model="buscar" @click="selectEtapa(buscar)">
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>
                                    <select class="form-control col-md-2" v-model="b_etapa">
                                        <option value="">Etapa</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.num_etapa" v-text="etapas.num_etapa"></option>
                                    </select>
                                </template>
                                <input type="text" v-on:keyup.enter="listarDepositos()" v-model="b_gen" class="form-control col-md-2" placeholder="Buscar">
                            </div>
                            <div class="row"><!-- boton de busqueda-->
                                <div class="col-sm-3 text-info"><strong>Depositos de pagares</strong></div>
                                <div class="col-sm-9 text-right">
                                    <button v-if="historial == 0" type="submit" @click="historial = 1,listarDepositos(), listarContratos(), listarDepCredit(), listarLiqCredit()" class="btn btn-warning"><i class="fa fa-exclamation-circle"></i> PENDIENTES</button>
                                    <button v-if="historial == 1" type="submit" @click="historial = 0,listarDepositos(), listarContratos(), listarDepCredit(), listarLiqCredit()" class="btn btn-success"><i class="fa fa-check"></i> HISTORIAL</button>
                                    <button type="submit" @click="listarDepositos()" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                            <br>
                            <div class="row"><!-- table-->
                                <table class="table-responsive table2 table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th class="text-center" colspan="12">Datos de deposito</th>
                                            <th class="text-center" colspan="">Factura</th>
                                            <th class="text-center" colspan="3">Deposito</th>
                                            <th class="text-center" colspan="3">Lote</th>
                                        </tr>
                                        <tr>
                                            <th>Folio</th>
                                            <th>Cliente</th>
                                            <th>RFC</th>
                                            <th>Fraccionamiento</th>
                                            <th>Etapa</th>
                                            <th>Manzana</th>
                                            <th>Lote</th>
                                            <th>Cuenta</th>
                                            <th>Monto</th>
                                            <th>Concepto</th>
                                            <th>F. Deposito</th>
                                            <th>Solicitud de contrato</th>
                                            <th v-if="b_empresa == 'CONCRETANIA'">Valor de terreno</th>
                                            <th>Factura</th>
                                            <th>Folio Factura</th>
                                            <th>Valor</th>
                                            <th>F. de carga</th>
                                            <th>Folio Factura</th>
                                            <th>Valor</th>
                                            <th>F. de carga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="deposito in arrayDepositos.data" :key="deposito.id">
                                            <td v-text="deposito.id" class="text-center"></td>
                                            <td>
                                                <span v-if="newDiferencia(deposito.fecha_pago) < 1 && !deposito.factura" v-text="deposito.nombre" class="badge badge-info"></span>
                                                <span v-else-if="newDiferencia(deposito.fecha_pago) >= 1 && newDiferencia(deposito.fecha_pago) <= 7 && !deposito.factura" v-text="deposito.nombre" class="badge badge-warning"></span>
                                                <span v-else-if="newDiferencia(deposito.fecha_pago) > 7 && !deposito.factura" v-text="deposito.nombre" class="badge badge-danger"></span>
                                                <span v-else-if="deposito.factura" v-text="deposito.nombre" class="badge badge-success"></span>
                                            </td>
                                            <td style="text-transform:uppercase" v-text="deposito.rfc">RFC</td>
                                            <td v-text="deposito.fraccionamiento">Fraccionamiento</td>
                                            <td v-text="deposito.etapa">Etapa</td>
                                            <td v-text="deposito.manzana">Etapa</td>
                                            <td v-text="deposito.num_lote">Lote</td>
                                            <td v-text="deposito.banco">Cuenta</td>
                                            <td v-text="'$'+deposito.cant_depo.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2})">Monto</td>
                                            <td v-text="deposito.concepto">Concepto</td>
                                            <td v-text="this.moment(deposito.fecha_pago).locale('es').format('DD/MMM/YYYY')"></td>
                                            <td>
                                                <a :href="'/contratoCompraVenta/pdf/'+deposito.cId" target="_blank" class="btn btn-info btn-sm">Ver contrato</a>
                                            </td>
                                            <td v-if="b_empresa == 'CONCRETANIA'" v-text="'$'+ deposito.valor_terreno.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2})">Terreno</td>
                                            <td>
                                                <button id="btnFiles" class="dropdown-toggle btn-primary btn btn-sm" data-toggle="dropdown" type="button"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-cloud-upload"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelleadby="btnFiles">
                                                    <a href="#" class="dropdown-item btn btn-primary text-center" style="background-color: #00B0BB !important;"
                                                        data-toggle="modal" data-target="#modAdvFilesUp" @click="setForm(deposito, 'deposito')">
                                                        <i class="fa fa-cloud-upload"></i> Subir archivo
                                                    </a>
                                                    <a v-if="deposito.factura" :href="'/facturas/depositos/download/'+deposito.factura" class="dropdown-item btn text-info">Descargar Factura de Deposito</a>
                                                    <a v-if="deposito.factura_terreno" :href="'/facturas/terreno/download/'+deposito.factura_terreno" class="dropdown-item btn text-info">Descargar Factura de Terreno</a>
                                                </div>
                                            </td>
                                            <td v-text="deposito.folio_factura">folio</td>
                                            <td v-text="'$'+deposito.monto.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2})">monto</td>
                                            <td v-if="deposito.f_carga_factura" v-text="this.moment(deposito.f_carga_factura).locale('es').format('DD/MMM/YYYY')">F. de carga</td>
                                            <td v-else></td>
                                            <template v-if="deposito.emp_constructora != 'Grupo Constructor Cumbres'">
                                                <td v-text="deposito.folio_factura_terreno">folio</td>
                                                <td v-text="'$'+deposito.monto_terreno.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2})">monto</td>
                                                <td v-if="deposito.f_carga_factura_terreno" v-text="this.moment(deposito.f_carga_factura_terreno).locale('es').format('DD/MMM/YYYY')">F. de carga</td>
                                                <td v-else></td>
                                            </template>
                                            <td v-else colspan="3"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row"> <!-- Pagination-->
                                <nav>
                                    <!--Botones de paginacion -->
                                    <ul class="pagination">
                                        <li class="page-item">
                                            <a class="page-link" href="#" @click.prevent="listarDepositos(1)">Inicio</a>
                                        </li>
                                        <li v-if="arrayDepositos.current_page-3 >= 1">
                                            <a class="page-link" href="#" 
                                            @click.prevent="listarDepositos(arrayDepositos.current_page-3)" 
                                            v-text="arrayDepositos.current_page-3" ></a>
                                        </li>
                                        <li v-if="arrayDepositos.current_page-2 >= 1">
                                            <a class="page-link" href="#" 
                                            @click.prevent="listarDepositos(arrayDepositos.current_page-2)" 
                                            v-text="arrayDepositos.current_page-2" ></a>
                                        </li>
                                        <li v-if="arrayDepositos.current_page-1 >= 1">
                                            <a class="page-link" href="#" 
                                            @click.prevent="listarDepositos(arrayDepositos.current_page-1)" 
                                            v-text="arrayDepositos.current_page-1" ></a>
                                        </li>
                                        
                                        <li class="page-item active">
                                            <a class="page-link" href="#" v-text="arrayDepositos.current_page" ></a>
                                        </li>
                                        
                                        <li v-if="arrayDepositos.current_page+1 <= arrayDepositos.last_page">
                                            <a class="page-link" href="#" 
                                            @click.prevent="listarDepositos(arrayDepositos.current_page+1)" 
                                            v-text="arrayDepositos.current_page+1" ></a>
                                        </li>
                                        <li v-if="arrayDepositos.current_page+2 <= arrayDepositos.last_page">
                                            <a class="page-link" href="#" 
                                            @click.prevent="listarDepositos(arrayDepositos.current_page+2)" 
                                            v-text="arrayDepositos.current_page+2" ></a>
                                        </li>
                                        <li v-if="arrayDepositos.current_page+3 <= arrayDepositos.last_page">
                                            <a class="page-link" href="#" 
                                            @click.prevent="listarDepositos(arrayDepositos.current_page+3)" 
                                            v-text="arrayDepositos.current_page+3" ></a>
                                        </li>
                                        
                                        <li class="page-item">
                                            <a class="page-link" href="#" @click.prevent="listarDepositos(arrayDepositos.last_page)">Ultimo</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>

                        <!-- Creditos Directos -->
                        <div class="container" v-else-if="tab == 2">
                            <!-- Formularios -->
                            <div class="row">
                                <select class="form-control col-md-2" v-model="b_empresa" >
                                    <option value="">Empresa constructora</option>
                                    <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                </select>

                                <select class="form-control col-md-2" v-model="criterio">
                                    <option value="lotes.fraccionamiento_id">Proyecto</option>
                                    <option value="nombre">Cliente</option>
                                    <option value="contratos.e_folio_factura">Folio Factura</option>
                                    <option value="contratos.e_monto">Monto de Factura</option>
                                </select>

                                <template v-if="criterio=='lotes.fraccionamiento_id'">
                                    <select class="form-control col-md-2" v-model="buscar" @click="selectEtapa(buscar)">
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>
                                    <select class="form-control col-md-2" v-model="b_etapa">
                                        <option value="">Etapa</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.num_etapa" v-text="etapas.num_etapa"></option>
                                    </select>
                                </template>
                                <input type="text" v-on:keyup.enter="listarContratos()" v-model="b_gen" class="form-control col-md-2" placeholder="Buscar">
                            </div>
                            <div class="row">
                                <div class="col-sm-3 text-info"><strong>Creditos Directos</strong></div>
                                <div class="col-sm-9 text-right">
                                   <button v-if="historial == 0" type="submit" @click="historial = 1,listarDepositos(), listarContratos(), listarDepCredit(), listarLiqCredit()" class="btn btn-warning"><i class="fa fa-exclamation-circle"></i> PENDIENTES</button>
                                    <button v-if="historial == 1" type="submit" @click="historial = 0,listarDepositos(), listarContratos(), listarDepCredit(), listarLiqCredit()" class="btn btn-success"><i class="fa fa-check"></i> HISTORIAL</button>
                                    <button type="submit" @click="listarContratos()" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                            <br>

                            <!-- Tabla -->
                            <div class="row">
                                <table class="table-responsive table2 table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Folio</th>
                                            <th>Cliente</th>
                                            <th>RFC</th>
                                            <th>Fraccionamiento</th>
                                            <th>Etapa</th>
                                            <th>Manzana</th>
                                            <th>Lote</th>
                                            <th>F. firma contrato</th>
                                            <th>Solicitud de contrato</th>
                                            <th v-if="b_empresa == 'CONCRETANIA'">Valor de terreno</th>
                                            <th>Factura</th>
                                            <th>Folio Factura</th>
                                            <th>Valor a escriturar</th>
                                            <th>F. de carga</th>

                                            <th>Folio Factura (CONCRETANIA)</th>
                                            <th>Valor a escriturar (CONCRETANIA)</th>
                                            <th>F. de carga (CONCRETANIA)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="contrato in arrayContratos.data" :key="contrato.id">
                                            <td v-text="contrato.id" class="text-center"></td>
                                            <td>
                                                <span v-if="newDiferencia(contrato.fecha_status) <= 3 && !contrato.e_factura" v-text="contrato.nombre" class="badge badge-info"></span>
                                                <span v-else-if="newDiferencia(contrato.fecha_status) > 3 && newDiferencia(contrato.fecha_status) <= 7 && !contrato.e_factura" v-text="contrato.nombre" class="badge badge-warning"></span>
                                                <span v-else-if="newDiferencia(contrato.fecha_status) > 7 && !contrato.e_factura" v-text="contrato.nombre" class="badge badge-danger"></span>
                                                <span v-else-if="contrato.e_factura" v-text="contrato.nombre" class="badge badge-success"></span>
                                            </td>
                                            <td style="text-transform:uppercase" v-text="contrato.rfc">RFC</td>
                                            <td v-text="contrato.fraccionamiento">Fraccionamiento</td>
                                            <td v-text="contrato.etapa">Etapa</td>
                                            <td v-text="contrato.manzana"></td>
                                            <td v-text="contrato.num_lote">Lote</td>
                                            <td v-text="this.moment(contrato.fecha_status).locale('es').format('DD/MMM/YYYY')"></td>
                                            <td>
                                                <a :href="'/contratoCompraVenta/pdf/'+contrato.id" target="_blank" class="btn btn-info btn-sm">Ver contrato</a>
                                            </td>
                                            <td v-if="b_empresa == 'CONCRETANIA'" v-text="'$'+ contrato.valor_terreno.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2})">Terreno</td>
                                            <td>
                                                <section>
                                                    <button id="btnFiles" class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" 
                                                        aria-haspopup="true" aria-expanded="false">
                                                        <i class="fa fa-cloud-upload"></i>
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="btnFiles">
                                                        <a href="#" class="dropdown-item btn btn-primary text-center" style="background-color: #00B0BB !important;"
                                                            data-toggle="modal" data-target="#modAdvFilesUp" @click="setForm(contrato, 'contrato')">
                                                            <i class="fa fa-cloud-upload"></i> Subir archivo
                                                        </a>
                                                        <a v-if="contrato.e_factura" :href="'/facturas/contratos/download/'+contrato.e_factura" target="_blank" class="dropdown-item btn text-info">Descargar</a>
                                                    </div>
                                                </section>

                                                <section v-if="contrato.emp_constructora == 'CONCRETANIA'">
                                                    <button id="" class="dropdown-toggle btn-info btn btn-sm" data-toggle="dropdown" type="button"
                                                        aria-haspopup="true" aria-expanded="false" title="Factura de CONCRETANIA">
                                                        <i class="fa fa-cloud-upload"></i>
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelleadby="btn2Files">
                                                        <a href="#" class="dropdown-item btn btn-primary text-center" style="background-color: #00B0BB !important;"
                                                            data-toggle="modal" data-target="#modAdvFilesUpConcretania" @click="setForm(contrato, 'contrato2')">
                                                            <i class="fa fa-cloud-upload"></i> Subir archivo (CONCRETANIA)
                                                        </a>
                                                        <a v-if="contrato.e_factura_concretania" 
                                                            :href="'/facturas/contratos/concretania/download/'+contrato.e_factura_concretania" 
                                                            target="_blank" class="dropdown-item btn text-info">
                                                            Descargar
                                                        </a>
                                                    </div>
                                                </section>
                                            </td>
                                            <td v-text="contrato.e_folio_factura">folio</td>
                                            <td v-text="'$'+contrato.e_monto.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2})">monto</td>
                                            <td v-if="contrato.e_f_carga_factura" v-text="this.moment(contrato.e_f_carga_factura).locale('es').format('DD/MMM/YYYY')">F. de carga</td>
                                            <td v-else></td>

                                            <td v-text="contrato.e_folio_factura_concretania">folio</td>
                                            <td v-text="'$'+contrato.e_monto_concretania.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2})">monto</td>
                                            <td v-if="contrato.e_f_carga_factura_concretania" v-text="this.moment(contrato.e_f_carga_factura_concretania).locale('es').format('DD/MMM/YYYY')">F. de carga</td>
                                            <td v-else></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row"> <!-- Pagination-->
                                <nav>
                                    <!--Botones de paginacion -->
                                    <ul class="pagination">
                                        <li class="page-item">
                                            <a class="page-link" href="#" @click.prevent="listarContratos(1)">Inicio</a>
                                        </li>
                                        <li v-if="arrayContratos.current_page-3 >= 1">
                                            <a class="page-link" href="#" 
                                            @click.prevent="listarContratos(arrayContratos.current_page-3)" 
                                            v-text="arrayContratos.current_page-3" ></a>
                                        </li>
                                        <li v-if="arrayContratos.current_page-2 >= 1">
                                            <a class="page-link" href="#" 
                                            @click.prevent="listarContratos(arrayContratos.current_page-2)" 
                                            v-text="arrayContratos.current_page-2" ></a>
                                        </li>
                                        <li v-if="arrayContratos.current_page-1 >= 1">
                                            <a class="page-link" href="#" 
                                            @click.prevent="listarContratos(arrayContratos.current_page-1)" 
                                            v-text="arrayContratos.current_page-1" ></a>
                                        </li>
                                        
                                        <li class="page-item active">
                                            <a class="page-link" href="#" v-text="arrayContratos.current_page" ></a>
                                        </li>
                                        
                                        <li v-if="arrayContratos.current_page+1 <= arrayContratos.last_page">
                                            <a class="page-link" href="#" 
                                            @click.prevent="listarContratos(arrayContratos.current_page+1)" 
                                            v-text="arrayContratos.current_page+1" ></a>
                                        </li>
                                        <li v-if="arrayContratos.current_page+2 <= arrayContratos.last_page">
                                            <a class="page-link" href="#" 
                                            @click.prevent="listarContratos(arrayContratos.current_page+2)" 
                                            v-text="arrayContratos.current_page+2" ></a>
                                        </li>
                                        <li v-if="arrayContratos.current_page+3 <= arrayContratos.last_page">
                                            <a class="page-link" href="#" 
                                            @click.prevent="listarContratos(arrayContratos.current_page+3)" 
                                            v-text="arrayContratos.current_page+3" ></a>
                                        </li>
                                        
                                        <li class="page-item">
                                            <a class="page-link" href="#" @click.prevent="listarContratos(arrayContratos.last_page)">Ultimo</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>

                        <!-- Escritura -->
                        <div class="container" v-else-if="tab == 3">
                            <div class="row"><!-- campos de busqueda-->
                                <select class="form-control col-md-2" v-model="b_empresa" >
                                    <option value="">Empresa constructora</option>
                                    <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                </select>
                            
                                <select class="form-control col-md-2" v-model="criterio">
                                    <option value="lotes.fraccionamiento_id">Proyecto</option>
                                    <option value="nombre">Cliente</option>
                                    <option value="creditos.folio_factura">Folio Factura</option>
                                    <option value="creditos.monto">Monto de Factura</option>
                                </select>

                                <template v-if="criterio=='lotes.fraccionamiento_id'">
                                    <select class="form-control col-md-2" v-model="buscar" @click="selectEtapa(buscar)">
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>
                                    <select class="form-control col-md-2" v-model="b_etapa">
                                        <option value="">Etapa</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.num_etapa" v-text="etapas.num_etapa"></option>
                                    </select>
                                </template>
                                <input type="text" v-on:keyup.enter="listarLiqCredit()" v-model="b_gen" class="form-control col-md-2" placeholder="Buscar">
                            </div>
                            <div class="row"><!-- boton de busqueda-->
                                <div class="col-sm-3 text-info"><strong>Creditos Escriturados</strong></div>
                                <div class="col-sm-9 text-right">
                                    <button v-if="historial == 0" type="submit" @click="historial = 1,listarDepositos(), listarContratos(), listarDepCredit(), listarLiqCredit()" class="btn btn-warning"><i class="fa fa-exclamation-circle"></i> PENDIENTES</button>
                                    <button v-if="historial == 1" type="submit" @click="historial = 0,listarDepositos(), listarContratos(), listarDepCredit(), listarLiqCredit()" class="btn btn-success"><i class="fa fa-check"></i> HISTORIAL</button>
                                    <button type="submit" @click="listarLiqCredit()" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <table class="table-responsive table2 table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Folio</th>
                                            <th>Cliente</th>
                                            <th>RFC</th>
                                            <th>Fraccionamiento</th>
                                            <th>Etapa</th>
                                            <th>Manzana</th>
                                            <th>Lote</th>
                                            <th>F. firma escritura</th>
                                            <th>Solicitud de contrato</th>
                                            <th v-if="b_empresa == 'CONCRETANIA'">Valor de terreno</th>
                                            <th>Factura</th>
                                            <th>Folio Factura</th>
                                            <th>Valor</th>
                                            <th>F. de carga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="liqCredit in arrayLiqCredit.data" :key="liqCredit.id">
                                            <td v-text="liqCredit.id" class="text-center"></td>
                                            <td>
                                                <span v-if="newDiferencia(liqCredit.fecha_firma_esc) <= 3 && !liqCredit.factura" v-text="liqCredit.nombre" class="badge badge-info"></span>
                                                <span v-else-if="newDiferencia(liqCredit.fecha_firma_esc) > 3 && newDiferencia(liqCredit.fecha_firma_esc) <= 7 && !liqCredit.factura" v-text="liqCredit.nombre" class="badge badge-warning"></span>
                                                <span v-else-if="newDiferencia(liqCredit.fecha_firma_esc) > 7 && !liqCredit.factura" v-text="liqCredit.nombre" class="badge badge-danger"></span>
                                                <span v-else-if="liqCredit.factura" v-text="liqCredit.nombre" class="badge badge-success"></span>
                                            </td>
                                            <td style="text-transform:uppercase" v-text="liqCredit.rfc">RFC</td>
                                            <td v-text="liqCredit.fraccionamiento">Fraccionamiento</td>
                                            <td v-text="liqCredit.etapa">Etapa</td>
                                            <td v-text="liqCredit.manzana">manzana</td>
                                            <td v-text="liqCredit.num_lote">Lote</td>
                                            <td v-text="this.moment(liqCredit.fecha_firma_esc).locale('es').format('DD/MMM/YYYY')"></td>
                                            <td>
                                                <a :href="'/contratoCompraVenta/pdf/'+liqCredit.cId" target="_blank" class="btn btn-info btn-sm">Ver contrato</a>
                                            </td>
                                            <td v-if="b_empresa == 'CONCRETANIA'" v-text="'$'+ liqCredit.valor_terreno.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2})">Terreno</td>
                                            <td>
                                                <button id="btnFiles" class="dropdown-toggle btn-primary btn btn-sm" data-toggle="dropdown" type="button"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-cloud-upload"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelleadby="btnFiles">
                                                    <a href="#" class="dropdown-item btn btn-primary text-center" style="background-color: #00B0BB !important;"
                                                        data-toggle="modal" data-target="#modAdvFilesUp" @click="setForm(liqCredit, 'liqDeposito')">
                                                        <i class="fa fa-cloud-upload"></i> Subir archivo
                                                    </a>
                                                    <a v-if="liqCredit.factura" :href="'/facturas/liq/credito/download/'+liqCredit.factura"  target="_blank" class="dropdown-item btn text-info">Descargar</a>
                                                </div>
                                            </td>
                                            <td v-text="liqCredit.folio_factura">folio</td>
                                            <td v-text="'$'+liqCredit.monto.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2})">monto</td>
                                            <td v-if="liqCredit.f_carga_factura" v-text="this.moment(liqCredit.f_carga_factura).locale('es').format('DD/MMM/YYYY')">F. de carga</td>
                                            <td v-else></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <nav>
                                    <!--Botones de paginacion -->
                                    <ul class="pagination">
                                        <li class="page-item">
                                            <a class="page-link" href="#" @click.prevent="listarLiqCredit(1)">Inicio</a>
                                        </li>
                                        <li v-if="arrayLiqCredit.current_page-3 >= 1">
                                            <a class="page-link" href="#" 
                                            @click.prevent="listarLiqCredit(arrayLiqCredit.current_page-3)" 
                                            v-text="arrayLiqCredit.current_page-3" ></a>
                                        </li>
                                        <li v-if="arrayLiqCredit.current_page-2 >= 1">
                                            <a class="page-link" href="#" 
                                            @click.prevent="listarLiqCredit(arrayLiqCredit.current_page-2)" 
                                            v-text="arrayLiqCredit.current_page-2" ></a>
                                        </li>
                                        <li v-if="arrayLiqCredit.current_page-1 >= 1">
                                            <a class="page-link" href="#" 
                                            @click.prevent="listarLiqCredit(arrayLiqCredit.current_page-1)" 
                                            v-text="arrayLiqCredit.current_page-1" ></a>
                                        </li>
                                        
                                        <li class="page-item active">
                                            <a class="page-link" href="#" v-text="arrayLiqCredit.current_page" ></a>
                                        </li>
                                        
                                        <li v-if="arrayLiqCredit.current_page+1 <= arrayLiqCredit.last_page">
                                            <a class="page-link" href="#" 
                                            @click.prevent="listarLiqCredit(arrayLiqCredit.current_page+1)" 
                                            v-text="arrayLiqCredit.current_page+1" ></a>
                                        </li>
                                        <li v-if="arrayLiqCredit.current_page+2 <= arrayLiqCredit.last_page">
                                            <a class="page-link" href="#" 
                                            @click.prevent="listarLiqCredit(arrayLiqCredit.current_page+2)" 
                                            v-text="arrayLiqCredit.current_page+2" ></a>
                                        </li>
                                        <li v-if="arrayLiqCredit.current_page+3 <= arrayLiqCredit.last_page">
                                            <a class="page-link" href="#" 
                                            @click.prevent="listarLiqCredit(arrayLiqCredit.current_page+3)" 
                                            v-text="arrayLiqCredit.current_page+3" ></a>
                                        </li>
                                        
                                        <li class="page-item">
                                            <a class="page-link" href="#" @click.prevent="listarLiqCredit(arrayLiqCredit.last_page)">Ultimo</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>

                        <!-- depositos de credito -->
                        <div class="container" v-else-if="tab == 4">
                            <div class="row">
                                <select class="form-control col-md-2" v-model="b_empresa" >
                                    <option value="">Empresa constructora</option>
                                    <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                </select>
                                
                                <select class="form-control col-md-2" v-model="criterio">
                                    <option value="lotes.fraccionamiento_id">Proyecto</option>
                                    <option value="nombre">Cliente</option>
                                    <option value="dep_creditos.folio_factura">Folio Factura</option>
                                    <option value="dep_creditos.monto">Monto de Factura</option>
                                    <option value="dep_creditos.cant_depo">Monto de Deposito</option>
                                </select>

                                <template v-if="criterio=='lotes.fraccionamiento_id'">
                                    <select class="form-control col-md-2" v-model="buscar" @click="selectEtapa(buscar)">
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>
                                    <select class="form-control col-md-2" v-model="b_etapa">
                                        <option value="">Etapa</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.num_etapa" v-text="etapas.num_etapa"></option>
                                    </select>
                                </template>
                                <input type="text" v-on:keyup.enter="listarDepCredit()" v-model="b_gen" class="form-control col-md-2" placeholder="Buscar">
                            </div>
                            <div class="row">
                                <div class="col-sm-3 text-info"><strong>Deposito a credito</strong></div>
                                <div class="col-sm-9 text-right">
                                    <button v-if="historial == 0" type="submit" @click="historial = 1,listarDepositos(), listarContratos(), listarDepCredit(), listarLiqCredit()" class="btn btn-warning"><i class="fa fa-exclamation-circle"></i> PENDIENTES</button>
                                    <button v-if="historial == 1" type="submit" @click="historial = 0,listarDepositos(), listarContratos(), listarDepCredit(), listarLiqCredit()" class="btn btn-success"><i class="fa fa-check"></i> HISTORIAL</button>
                                    <button type="submit" @click="listarDepCredit()" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <table class="table-responsive table2 table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th class="text-center" colspan="12">Datos de deposito</th>
                                            <th class="text-center" colspan="">Factura</th>
                                            <th class="text-center" colspan="3">Deposito</th>
                                            <th class="text-center" colspan="3">Lote</th>
                                        </tr>
                                        <tr>
                                            <th>Folio</th>
                                            <th>Cliente</th>
                                            <th>RFC</th>
                                            <th>Fraccionamiento</th>
                                            <th>Etapa</th>
                                            <th>Manzana</th>
                                            <th>Lote</th>
                                            <th>Cuenta</th>
                                            <th>Monto</th>
                                            <th>Concepto</th>
                                            <th>Fecha de deposito</th>
                                            <th>Solicitud de contrato</th>
                                            <th v-if="b_empresa == 'CONCRETANIA'">Valor de terreno</th>
                                            <th>Factura</th>
                                            <th>Folio Factura</th>
                                            <th>Valor</th>
                                            <th>F. de carga</th>
                                            <th>Folio Factura</th>
                                            <th>Valor</th>
                                            <th>F. de carga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="deposito in arrayDepCredit.data" :key="deposito.id">
                                            <td v-text="deposito.id" class="text-center"></td>
                                            <td>
                                                <span v-if="newDiferencia(deposito.fecha_deposito) < 1 && !deposito.factura" v-text="deposito.nombre" class="badge badge-info"></span>
                                                <span v-else-if="newDiferencia(deposito.fecha_deposito) >= 1 && newDiferencia(deposito.fecha_deposito) <= 7 && !deposito.factura" v-text="deposito.nombre" class="badge badge-warning"></span>
                                                <span v-else-if="newDiferencia(deposito.fecha_deposito) > 7 && !deposito.factura" v-text="deposito.nombre" class="badge badge-danger"></span>
                                                <span v-else-if="deposito.factura" v-text="deposito.nombre" class="badge badge-success"></span>
                                            </td>
                                            <td style="text-transform:uppercase" v-text="deposito.rfc">RFC</td>
                                            <td v-text="deposito.fraccionamiento">Fraccionamiento</td>
                                            <td v-text="deposito.etapa">Etapa</td>
                                            <td v-text="deposito.manzana">manzana</td>
                                            <td v-text="deposito.num_lote">Lote</td>
                                            <td v-text="deposito.banco">Cuenta</td>
                                            <td v-text="'$'+deposito.cant_depo.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2})">Monto</td>
                                            <td v-text="deposito.concepto">Concepto</td>
                                            <td v-text="this.moment(deposito.fecha_deposito).locale('es').format('DD/MMM/YYYY')"></td>
                                            <td>
                                                <a :href="'/contratoCompraVenta/pdf/'+deposito.cId" target="_blank" class="btn btn-info btn-sm">Ver contrato</a>
                                            </td>
                                            <td v-if="b_empresa == 'CONCRETANIA'" v-text="'$'+ deposito.valor_terreno.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2})">Terreno</td>
                                            <td>
                                                <button id="btnFiles" class="dropdown-toggle btn-primary btn btn-sm" data-toggle="dropdown" type="button"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-cloud-upload"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelleadby="btnFiles">
                                                    <a href="#" class="dropdown-item btn btn-primary text-center" style="background-color: #00B0BB !important;"
                                                        data-toggle="modal" data-target="#modAdvFilesUp" @click="setForm(deposito, 'pagDeposito')">
                                                        <i class="fa fa-cloud-upload"></i> Subir archivo
                                                    </a>
                                                    <a v-if="deposito.factura" :href="'/facturas/dep/credito/download/'+deposito.factura"  target="_blank" class="dropdown-item btn text-info">Descargar Factura de Deposito</a>
                                                    <a v-if="deposito.factura_terreno" :href="'/facturas/dep/credito/downloadt/'+deposito.factura_terreno" class="dropdown-item btn text-info">Descargar Factura de Terreno</a>
                                                </div>
                                            </td>
                                            <td v-text="deposito.folio_factura">folio</td>
                                            <td v-text="'$'+deposito.monto.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2})">monto</td>
                                            <td v-if="deposito.f_carga_factura" v-text="this.moment(deposito.f_carga_factura).locale('es').format('DD/MMM/YYYY')">F. de carga</td>
                                            <td v-else></td>
                                            
                                            <template v-if="deposito.emp_constructora != 'Grupo Constructor Cumbres'">
                                                <td v-text="deposito.folio_factura_terreno">folio</td>
                                                <td v-text="'$'+deposito.monto_terreno.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2})">monto</td>
                                                <td v-if="deposito.f_carga_factura_terreno" v-text="this.moment(deposito.f_carga_factura_terreno).locale('es').format('DD/MMM/YYYY')">F. de carga</td>
                                                <td v-else></td>
                                            </template>
                                            <td v-else colspan="3"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row"> <!-- Pagination-->
                                <nav>
                                    <!--Botones de paginacion -->
                                    <ul class="pagination">
                                        <li class="page-item">
                                            <a class="page-link" href="#" @click.prevent="listarDepCredit(1)">Inicio</a>
                                        </li>
                                        <li v-if="arrayDepCredit.current_page-3 >= 1">
                                            <a class="page-link" href="#" 
                                            @click.prevent="listarDepCredit(arrayDepCredit.current_page-3)" 
                                            v-text="arrayDepCredit.current_page-3" ></a>
                                        </li>
                                        <li v-if="arrayDepCredit.current_page-2 >= 1">
                                            <a class="page-link" href="#" 
                                            @click.prevent="listarDepCredit(arrayDepCredit.current_page-2)" 
                                            v-text="arrayDepCredit.current_page-2" ></a>
                                        </li>
                                        <li v-if="arrayDepCredit.current_page-1 >= 1">
                                            <a class="page-link" href="#" 
                                            @click.prevent="listarDepCredit(arrayDepCredit.current_page-1)" 
                                            v-text="arrayDepCredit.current_page-1" ></a>
                                        </li>
                                        
                                        <li class="page-item active">
                                            <a class="page-link" href="#" v-text="arrayDepCredit.current_page" ></a>
                                        </li>
                                        
                                        <li v-if="arrayDepCredit.current_page+1 <= arrayDepCredit.last_page">
                                            <a class="page-link" href="#" 
                                            @click.prevent="listarDepCredit(arrayDepCredit.current_page+1)" 
                                            v-text="arrayDepCredit.current_page+1" ></a>
                                        </li>
                                        <li v-if="arrayDepCredit.current_page+2 <= arrayDepCredit.last_page">
                                            <a class="page-link" href="#" 
                                            @click.prevent="listarDepCredit(arrayDepCredit.current_page+2)" 
                                            v-text="arrayDepCredit.current_page+2" ></a>
                                        </li>
                                        <li v-if="arrayDepCredit.current_page+3 <= arrayDepCredit.last_page">
                                            <a class="page-link" href="#" 
                                            @click.prevent="listarDepCredit(arrayDepCredit.current_page+3)" 
                                            v-text="arrayDepCredit.current_page+3" ></a>
                                        </li>
                                        
                                        <li class="page-item">
                                            <a class="page-link" href="#" @click.prevent="listarDepCredit(arrayDepCredit.last_page)">Ultimo</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>

            <!-- Upload bill files -->
            <div class="modal fade" id="modAdvFilesUp" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="color: #fff; background-color: #00B0BB;">
                            <h5 class="modal-title" id="">Subir Factura</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="" method="post" enctype="multipart/form-data" id="formUpAdvFile" @submit="subirFacturaC">
                        <div class="modal-body">
                                <div class="row">
                                    <label class="col-sm-4" for="">Factura</label>
                                    <input type="file" name="upfil" id="upfil" style="right: 10px;" class="form-control col-sm-8" required>
                                </div>
                                <br>
                                <div class="row">
                                    <label class="col-sm-4" for="">Folio Factura</label>
                                    <input type="text" name="upFolio" id="upFolio" style="right: 10px;" class="form-control col-sm-8" required>
                                </div>
                                <br>
                                <div class="row">
                                    <label class="col-sm-4" for="">Valor (monto)</label>
                                    <input type="number" name="upMonto" id="upMonto" step="0.01" style="right: 10px;" class="form-control col-sm-8" required>
                                </div>

                                <template v-if="statusTerreno.emp_constructora == 'CONCRETANIA' && (statusTerreno.pendiente_terre != 0 || statusTerreno.factura_terreno)">
                                    
                                    <hr>
                                    <div class="row">
                                        <label class="col-sm-4" for="">Factura de Terreno</label>
                                        <input type="file" name="upfilTer" id="upfilTer" style="right: 10px;" class="form-control col-sm-8" required>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <label class="col-sm-4" for="">Folio Factura de Terreno</label>
                                        <input type="text" v-model="foliTer" name="upFolioTer" id="upFolioTer" style="right: 10px;" class="form-control col-sm-8" required>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <label class="col-sm-4" for="">Valor (monto) de Factura de Terreno</label>
                                        <input type="number" v-model="montoTer" name="upMontoTer" id="upMontoTer" step="0.0001" style="right: 10px;" class="form-control col-sm-8" required>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <h5 class="text-info" v-text="'El porcentage de este deposito para la factura del terreno es: $'+statusTerreno.porc_deposito"></h5>
                                            <h5 class="text-info" v-text="'El saldo pendiente a facturar para este terreno es: $'+statusTerreno.pendiente_terre"></h5>
                                            <h4 class="text-danger" v-if="(statusTerreno.pendiente_terre - montoTer) < 0"
                                                v-text="'El monto de la factura(s) no puede exceder el valor pendiente del terreno.'">
                                            </h4>
                                        </div>
                                    </div>
                                </template>
                        </div>
                        <div class="modal-footer">
                            <template v-if="statusTerreno.emp_constructora == 'CONCRETANIA'">
                                <button v-if="(statusTerreno.pendiente_terre - montoTer) >= 0" type="submit" class="btn btn-primary">Guardar</button>
                            </template>
                            <button v-else type="submit" class="btn btn-primary">Guardar</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Upload bill files CONCRETANIA-->
            <div class="modal fade" id="modAdvFilesUpConcretania" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="color: #fff; background-color: #00B0BB;">
                            <h5 class="modal-title" id="">Subir Factura CONCRETANIA</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="" method="post" enctype="multipart/form-data" id="formUpAdvFile" @submit="subirFacturaC">
                        <div class="modal-body">
                            <div class="row">
                                <label class="col-sm-4" for="">Factura CONCRETANIA</label>
                                <input type="file" name="upfilCon" id="upfilCon" style="right: 10px;" class="form-control col-sm-8" required>
                            </div>
                            <br>
                            <div class="row">
                                <label class="col-sm-4" for="">Folio Factura de CONCRETANIA</label>
                                <input type="text" name="upFolioCon" id="upFolioCon" style="right: 10px;" class="form-control col-sm-8" required>
                            </div>
                            <br>
                            <div class="row">
                                <label class="col-sm-4" for="">Valor (monto) de Factura de CONCRETANIA</label>
                                <input type="number" name="upMontoCon" id="upMontoCon" step="0.0001" style="right: 10px;" class="form-control col-sm-8" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                        </form>
                    </div>
                </div>
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
                criterio:'lotes.fraccionamiento_id',
                buscar:'',
                b_etapa:'',
                b_gen:'',
                b_empresa:'',
                generalId:0,
                tipoFactura:'',
                tab:1,
                historial:0,
                statusTerreno:'',
                montoTer:0,
                foliTer:'',
                empresas:[],
                arrayFraccionamientos:[],
                arrayEtapas:[],
                arrayContratos:[],
                arrayDepositos:[],
                arrayLiqCredit:[],
                arrayDepCredit:[],
                myAlerts:{
                    popAlert : function(title = 'Alert',type = "success", description =''){
                        swal({
                            title: title,
                            type: type,
                            text: description,
                            showConfirmButton:false,
                            timer:1500,
                        })
                    }
                },
            }
        },
        computed:{
        },
        methods : {
            
            selectFraccionamientos(){
                let me = this;
                me.buscar=""
                
                me.arrayFraccionamientos=[];
                var url = '/select_fraccionamiento';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayFraccionamientos = respuesta.fraccionamientos;
                }).catch(function (error) {
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
            setForm(datos, tipo){
                this.tipoFactura = tipo;
                this.generalId = datos.id;
                this.statusTerreno = datos;

                if(datos.monto_terreno){
                    this.montoTer = datos.monto_terreno;
                }else this.montoTer = 0;

                this.foliTer = datos.folio_factura_terreno;

                document.getElementById('upfil').value = "";

                if(tipo == 'contrato'){
                    document.getElementById('upFolio').value = datos.e_folio_factura;

                    if(datos.e_monto == 0 && datos.cant_depo != ""){
                        document.getElementById('upMonto').value = datos.e_monto;
                    }else document.getElementById('upMonto').value = datos.e_monto;
                }else if(tipo == 'contrato2'){
                    document.getElementById('upfilCon').value = "";
                    document.getElementById('upFolioCon').value = datos.e_folio_factura_concretania;
                    document.getElementById('upMontoCon').value = datos.e_monto_concretania;
                }else{
                    document.getElementById('upFolio').value = datos.folio_factura;
                    
                    if(datos.monto == 0 && datos.cant_depo != ""){
                        document.getElementById('upMonto').value = datos.cant_depo;
                    }else document.getElementById('upMonto').value = datos.monto;
                }
            },
            subirFacturaC(e){
                e.preventDefault();

                let me = this;
                let formData = new FormData();

                formData.append('id', this.generalId);

                if(this.tipoFactura != "contrato2"){
                    formData.append('upfil', e.target.upfil.files[0]);
                    formData.append('upFolio', e.target.upFolio.value);
                    formData.append('upMonto', e.target.upMonto.value);
                }else{
                    formData.append('upfilCon', e.target.upfilCon.files[0]);
                    formData.append('upFolioCon', e.target.upFolioCon.value);
                    formData.append('upMontoCon', e.target.upMontoCon.value);
                }
                switch(this.tipoFactura){
                    case 'deposito':

                        if(this.montoTer != ""){
                            formData.append('upfilTer', e.target.upfilTer.files[0]);
                            formData.append('upFolioTer', e.target.upFolioTer.value);
                            formData.append('upMontoTer', e.target.upMontoTer.value);
                        }

                        axios.post('/facturas/depositos/update', formData).then(
                            () => {
                                me.listarDepositos(me.arrayContratos.current_page)
                                me.myAlerts.popAlert('Guardado correctamente')
                                $('#modAdvFilesUp').modal('hide');
                            }
                        ).catch(error => console.log(error));
                        break;
                    case 'contrato':
                        axios.post('/facturas/contratos/update', formData).then(
                            () => {
                                me.listarContratos(me.arrayContratos.current_page)
                                me.myAlerts.popAlert('Guardado correctamente')
                                $('#modAdvFilesUp').modal('hide');
                            }
                        ).catch(error => console.log(error));
                        break;
                    case 'contrato2':
                        axios.post('/facturas/contratos/concretania/update', formData).then(
                            () => {
                                me.listarContratos(me.arrayContratos.current_page);
                                me.myAlerts.popAlert('Guardado correctamente');
                                $('#modAdvFilesUpConcretania').modal('hide');
                            }
                        ).catch(error => console.log(error));
                        break;
                    case 'liqDeposito':
                        axios.post('/facturas/liq/credito/update', formData).then(
                            () => {
                                me.listarContratos(me.arrayContratos.current_page)
                                me.myAlerts.popAlert('Guardado correctamente')
                                $('#modAdvFilesUp').modal('hide');
                            }
                        ).catch(error => console.log(error));
                        break;
                    case 'pagDeposito':

                        if(this.montoTer != ""){
                            formData.append('upfilTer', e.target.upfilTer.files[0]);
                            formData.append('upFolioTer', e.target.upFolioTer.value);
                            formData.append('upMontoTer', e.target.upMontoTer.value);
                        }

                        axios.post('/facturas/dep/credito/update', formData).then(
                            () => {
                                me.listarDepCredit(me.arrayDepCredit.current_page)
                                me.myAlerts.popAlert('Guardado correctamente')
                                $('#modAdvFilesUp').modal('hide');
                            }
                        ).catch(error => console.log(error));
                        break;
                }
            },
            newDiferencia(date){

                let cDate = moment().format('YYYY-MM-DD');

                if(date == '0000-00-00') date = '2000-01-01';

                var from = moment(date),
                        to = moment(cDate),
                        days = 0;
                    
                while (!from.isAfter(to,'day')) {
                    // Si no es sabado ni domingo
                    if (from.isoWeekday() !== 6 && from.isoWeekday() !== 7) {
                        days++;
                    }
                    from.add(1, 'days');
                }

                return days;
            },
            //facturas contratos
            listarContratos(page = 1){
                let me = this;
                
                axios.get(
                    '/facturas/contratos/get?page='+page+'&criterio='+this.criterio+'&buscar='+this.buscar+
                    '&b_etapa='+this.b_etapa+'&b_gen='+this.b_gen+'&historial='+this.historial+
                    '&b_empresa='+this.b_empresa
                ).then(
                    response => me.arrayContratos = response.data
                ).catch(error => console.log(error))
            },
            //facturas depositos
            listarDepositos(page = 1){
                let me = this;
                
                axios.get(
                    '/facturas/depositos/get?page='+page+'&criterio='+this.criterio+'&buscar='+this.buscar+
                    '&b_etapa='+this.b_etapa+'&b_gen='+this.b_gen+'&historial='+this.historial+
                    '&b_empresa='+this.b_empresa
                ).then(
                    response => me.arrayDepositos = response.data
                ).catch(error => console.log(error))
            },
            //liquidacion credito
            listarLiqCredit(page = 1){
                let me = this;
                
                axios.get(
                    '/facturas/liq/credito/get?page='+page+'&criterio='+this.criterio+'&buscar='+this.buscar+
                    '&b_etapa='+this.b_etapa+'&b_gen='+this.b_gen+'&historial='+this.historial+
                    '&b_empresa='+this.b_empresa
                ).then(
                    response => me.arrayLiqCredit = response.data
                ).catch(error => console.log(error))
            },
            //deposito credito
            listarDepCredit(page = 1){
                let me = this;
                
                axios.get(
                    '/facturas/dep/credito/get?page='+page+'&criterio='+this.criterio+'&buscar='+this.buscar+
                    '&b_etapa='+this.b_etapa+'&b_gen='+this.b_gen+'&historial='+this.historial+
                    '&b_empresa='+this.b_empresa
                ).then(
                    response => me.arrayDepCredit = response.data
                ).catch(error => console.log(error))
            },
            getEmpresa(){
                let me = this;
                me.empresas=[];
                var url = '/lotes/empresa/select';
                axios.get(url).then(function (response) {
                    var respuesta = response;
                    me.empresas = respuesta.data.empresas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        },
        mounted() {
            this.selectFraccionamientos();
            this.listarContratos();
            this.listarDepositos();
            this.listarLiqCredit();
            this.listarDepCredit();
            this.getEmpresa();
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
