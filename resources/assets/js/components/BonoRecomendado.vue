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
                        <i class="fa fa-align-justify"></i> Bonos por recomendación
                        <!--   Boton Nuevo    -->
                        
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-9">
                                <select class="form-control" v-model="b_empresa" >
                                    <option value="">Empresa constructora</option>
                                    <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                </select>
                            </div>
                            <div class="col-md-9">
                                <div class="input-group" v-if="criterio != 'fraccionamiento'">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control" v-model="criterio">
                                        <option value="cliente">Cliente</option>
                                        <option value="recomendado">Recomendado por:</option>
                                        <option value="con.id">Folio</option>
                                        <option value="fraccionamiento">Proyecto</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarBonos(1)" class="form-control">

                                    

                                </div>
                                <div class="input-group" v-if="criterio == 'fraccionamiento'">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control" v-model="criterio">
                                        <option value="cliente">Cliente</option>
                                        <option value="recomendado">Recomendado por:</option>
                                        <option value="con.id">Folio</option>
                                        <option value="fraccionamiento">Proyecto</option>
                                    </select>
                                    <select class="form-control" v-model="buscar" @click="selectEtapa(buscar), b_etapa=''">
                                        <option value="">Fraccionamiento</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>

                                    <select class="form-control" v-model="b_etapa" @keyup.enter="listarBonos(1)"> 
                                        <option value="">Etapa</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                    </select>

                                </div>
                                <div class="input-group" v-if="criterio == 'fraccionamiento'">
                                    <div class="input-group">
                                        <input type="text" v-model="b_manzana" @keyup.enter="listarBonos(1)" class="form-control" placeholder="Manzana">
                                        <input type="text" v-model="b_lote" @keyup.enter="listarBonos(1)" class="form-control" placeholder="Lote">
                                    </div>
                                </div>
                                
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <select class="form-control" v-model="status">
                                        <option value="">Todos</option>
                                        <option value="0">Pendiente</option>
                                        <option value="1">Aprobado</option>
                                        <option value="2">Autorizado</option>
                                        <option value="3">Pagado</option>
                                        <option value="5">Cancelado</option>
                                    </select>
                                    <button type="submit" @click="listarBonos(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th v-if="rolId == 1 || rolId == 6 || rolId == 8"></th>
                                        <th></th>
                                        <th colspan="7">Datos de la venta</th>
                                        <th colspan="5" class="text-center" style="text-color:''"><font color="#00ADEF">Cliente que nos recomienda:</font></th>
                                        <th colspan="2" class="text-center">Periodo</th>
                                        <th colspan="6"></th>
                                    </tr>
                                    <tr>
                                        <th v-if="rolId == 1 || rolId == 6 || rolId == 8"></th>
                                        <th>Status</th>
                                        <th>Folio</th>
                                        <th>Proyecto</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th>Lote</th>
                                        <th>Cliente</th>
                                        <th>Fecha venta</th>
                                        <th>Nombre</th>
                                        <th>Proyecto</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th>Lote</th>
                                        <th>Inicio</th>
                                        <th>Fin</th>
                                        <th>Descripcion</th>
                                        <th>Monto $</th>
                                        <th>Aprobar</th>
                                        <th>Autorizar</th>
                                        <th>Pago</th>
                                        <th>Observaciones</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="bono in arrayBonos" :key="bono.id">
                                        <td class="td2"  v-if="rolId == 1 || rolId == 6 || rolId == 8" style="width:20%">
                                            <button v-if="bono.fecha_pago == null && bono.status != 5" type="button" title="Actualizar" @click="abrirModal('actualizar',bono)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                            </button> &nbsp;
                                            <button v-if="bono.fecha_pago == null && bono.status != 5 && rolId == 6" type="button" title="Cancelar" class="btn btn-danger btn-sm" @click="cancelarBono(bono.id)">
                                            <i class="icon-close"></i>
                                            </button>
                                        </td>
                                        <template>
                                            <td class="td2" v-if="bono.status == '5'">
                                                <span class="badge badge-danger">Cancelado</span>
                                            </td>
                                            <td class="td2" v-if="bono.status == '3'">
                                                <span class="badge badge-success">Pagado</span>
                                            </td>
                                            <td class="td2" v-if="bono.status == '2'">
                                                <span class="badge badge-primary">Autorizado</span>
                                            </td>
                                            <td class="td2" v-if="bono.status == '1'">
                                                <span class="badge badge-light">Aprobado</span>
                                            </td>
                                            <td class="td2" v-if="bono.status == '0'">
                                                <span class="badge badge-warning">Pendiente</span>
                                            </td>
                                        </template>
                                        
                                        <td class="td2" v-text="bono.id"></td>
                                        <td class="td2" v-text="bono.proyecto" ></td>
                                        <td class="td2" v-text="bono.num_etapa" ></td>
                                        <td class="td2" v-text="bono.manzana" ></td>
                                        <td class="td2" v-text="bono.num_lote" ></td>
                                        <td class="td2" v-text="bono.nombre_cliente.toUpperCase()" ></td>
                                        <td class="td2" v-text="this.moment(bono.fecha).locale('es').format('DD/MMM/YYYY')" ></td>
                                        <td class="td2" v-text="bono.recomendado.toUpperCase()" ></td>
                                        <td class="td2" v-text="bono.proyecto_rec" ></td>
                                        <td class="td2" v-text="bono.etapa_rec" ></td>
                                        <td class="td2" v-text="bono.manzana_rec" ></td>
                                        <td class="td2" v-text="bono.lote_rec" ></td>
                                        <td class="td2" v-if="bono.ini_promo != null" v-text="this.moment(bono.ini_promo).locale('es').format('DD/MMM/YYYY')" ></td>
                                        <td class="td2" v-else ></td>
                                        <td class="td2" v-if="bono.fin_promo != null" v-text="this.moment(bono.fin_promo).locale('es').format('DD/MMM/YYYY')" ></td>
                                        <td class="td2" v-else ></td>
                                        <td v-text="bono.descripcion" ></td>
                                        <td class="td2" v-text="'$'+formatNumber(bono.monto)" ></td>
                                        <template v-if="bono.status != 5">
                                            <th class="td2" v-if="bono.fecha_aut1 == null"> <button type="button" @click="aprobarBono(bono.id)" class="btn btn-dark btn-sm" title=" Aprobar bono"> <i class="fa fa-check"> &nbsp; Aprobar </i> </button></th>
                                            <th class="td2" v-else v-text="'Aprobado el: '+this.moment(bono.fecha_aut1).locale('es').format('DD/MMM/YYYY')" ></th>
                                        </template>
                                        <template v-else>
                                            <td class="td2" v-if="bono.fecha_aut1 == null"> <span class="badge2 badge-danger"> Cancelado</span> </td>
                                        </template>
                                        <template v-if="bono.status != 5">
                                            <th class="td2" v-if="bono.fecha_aut2 == null && bono.fecha_aut1 == null"> Sin aprobar</th>
                                            <th class="td2" v-else-if="bono.fecha_aut2 == null && bono.fecha_aut1 != null"> <button type="button" @click="autorizarBono(bono.id)" class="btn btn-primary btn-sm" title=" Autorizar bono"> <i class="fa fa-check"> &nbsp; Autorizar </i> </button></th>
                                            <th class="td2" v-else v-text="'Autorizado el: ' + this.moment(bono.fecha_aut2).locale('es').format('DD/MMM/YYYY')" ></th>
                                        </template>
                                        <template v-else>
                                            <td class="td2" v-if="bono.fecha_aut1 == null"> <span class="badge2 badge-danger"> Cancelado</span> </td>
                                        </template>
                                        <template v-if="bono.status != 5">
                                            <th class="td2" v-if="bono.fecha_aut2 == null && bono.fecha_aut1 == null && bono.fecha_pago == null"> Sin aprobar</th>
                                            <th class="td2" v-else-if="bono.fecha_aut2 == null && bono.fecha_aut1 != null  && bono.fecha_pago == null"> Sin autorizar</th>
                                            <th class="td2" v-else-if="bono.fecha_aut2 != null && bono.fecha_aut1 != null  && bono.fecha_pago == null"> <button type="button" @click="abrirModal('pagar',bono)" class="btn btn-success btn-sm" title=" Generar pago"> <i class="fa fa-money"> &nbsp; Generar pago </i> </button></th>
                                            <th class="td2" @dblclick="abrirModal('detalle',bono)" v-else title="Doble click">
                                                <a href="#" v-text="'Pagado el: ' + this.moment(bono.fecha_pago).locale('es').format('DD/MMM/YYYY')"></a>
                                            </th>
                                        </template>
                                        <template v-else>
                                            <td class="td2" v-if="bono.fecha_aut1 == null"> <span class="badge2 badge-danger"> Cancelado</span> </td>
                                        </template>
                                            <th>
                                                <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right" 
                                                        @click="abrirModal('observaciones',bono)">Observaciones</button>
                                            </th>
                                        
                                    </tr>                               
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
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
                        <div class="modal-body" v-if="tipoAccion == 1">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <h6 style="color: #153157;">Datos de la venta: </h6>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 form-control-label" for="text-input">Proyecto:</label>
                                <div class="col-md-4">
                                    <input type="text" readonly v-model="proyecto" class="form-control">
                                </div>
                                <label class="col-md-1 form-control-label" for="text-input">Etapa:</label>
                                <div class="col-md-4">
                                    <input type="text" readonly v-model="etapa" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 form-control-label" for="text-input">Manzana:</label>
                                <div class="col-md-4">
                                    <input type="text" readonly v-model="manzana" class="form-control">
                                </div>
                                <label class="col-md-1 form-control-label" for="text-input"> Lote:</label>
                                <div class="col-md-3">
                                    <input type="text" readonly v-model="lote" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row line-separator"></div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <h6 style="color: #153157;">Cliente que nos recomienda: </h6>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 form-control-label" for="text-input">Nombre:</label>
                                <div class="col-md-5">
                                    <input type="text" readonly v-model="recomendado" class="form-control" placeholder="Recomendado">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 form-control-label" for="text-input">Proyecto:</label>
                                <div class="col-md-4">
                                    <input type="text" readonly v-model="proyecto_rec" class="form-control">
                                </div>
                                <label class="col-md-1 form-control-label" for="text-input">Etapa:</label>
                                <div class="col-md-4">
                                    <input type="text" readonly v-model="etapa_rec" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 form-control-label" for="text-input">Manzana:</label>
                                <div class="col-md-4">
                                    <input type="text" readonly v-model="manzana_rec" class="form-control">
                                </div>
                                <label class="col-md-1 form-control-label" for="text-input"> Lote:</label>
                                <div class="col-md-3">
                                    <input type="text" readonly v-model="lote_rec" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row line-separator"></div>

                            <br>
                            <div class="form-group row">
                                <h6 class="col-md-3 form-control-label" for="text-input">Descripcion del bono</h6>
                                <div class="col-md-9">
                                    <h6 v-if="descripcion!=null" v-text="descripcion"></h6>
                                    <h6 v-else v-text="'Bono de $5,000 por recomendar.'"></h6>
                                </div>
                            </div>

                            <div class="form-group row">
                                <h6 style="color: blue;" class="col-md-3 form-control-label" for="text-input">Bono</h6>
                                <div class="col-md-3">
                                    <h6 style="color: blue;" v-text="'$'+formatNumber(monto)"></h6>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 form-control-label" for="text-input">Fecha de pago:</label>
                                <div class="col-md-3">
                                    <input type="date" v-model="fecha_pago" class="form-control">
                                </div>
                            </div>


                        </div>
                        <div class="modal-body" v-if="tipoAccion == 3">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <h6 style="color: #153157;">Datos de la venta: </h6>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 form-control-label" for="text-input">Proyecto:</label>
                                <div class="col-md-4">
                                    <input type="text" readonly v-model="proyecto" class="form-control">
                                </div>
                                <label class="col-md-1 form-control-label" for="text-input">Etapa:</label>
                                <div class="col-md-4">
                                    <input type="text" readonly v-model="etapa" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 form-control-label" for="text-input">Manzana:</label>
                                <div class="col-md-4">
                                    <input type="text" readonly v-model="manzana" class="form-control">
                                </div>
                                <label class="col-md-1 form-control-label" for="text-input"> Lote:</label>
                                <div class="col-md-3">
                                    <input type="text" readonly v-model="lote" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row line-separator"></div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <h6 style="color: #153157;">Cliente que nos recomienda: </h6>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 form-control-label" for="text-input">Nombre:</label>
                                <div class="col-md-5">
                                    
                                    <input type="text" readonly v-model="recomendado" class="form-control" placeholder="Recomendado">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 form-control-label" for="text-input">Proyecto:</label>
                                <div class="col-md-4">
                                    <input type="text" readonly v-model="proyecto_rec" class="form-control">
                                </div>
                                <label class="col-md-1 form-control-label" for="text-input">Etapa:</label>
                                <div class="col-md-4">
                                    <input type="text" readonly v-model="etapa_rec" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 form-control-label" for="text-input">Manzana:</label>
                                <div class="col-md-4">
                                    <input type="text" readonly v-model="manzana_rec" class="form-control">
                                </div>
                                <label class="col-md-1 form-control-label" for="text-input"> Lote:</label>
                                <div class="col-md-3">
                                    <input type="text" readonly v-model="lote_rec" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row line-separator"></div>

                            <br>
                            <div class="form-group row">
                                <h6 class="col-md-3 form-control-label" for="text-input">Descripcion del bono</h6>
                                <div class="col-md-9">
                                    <h6 v-if="descripcion!=null" v-text="descripcion"></h6>
                                    <h6 v-else v-text="'Bono de $5,000 por recomendar.'"></h6>
                                </div>
                            </div>

                            <div class="form-group row">
                                <h6 style="color: blue;" class="col-md-3 form-control-label" for="text-input">Bono</h6>
                                <div class="col-md-3">
                                    <h6 style="color: blue;" v-text="'$'+formatNumber(monto)"></h6>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 form-control-label" for="text-input">Fecha de pago:</label>
                                <div class="col-md-3">
                                    <input type="date" readonly v-model="fecha_pago" class="form-control">
                                </div>
                            </div>


                        </div>
                        <div class="modal-body" v-if="tipoAccion == 2">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <h6 style="color: #153157;">Datos de la venta: </h6>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 form-control-label" for="text-input">Proyecto:</label>
                                <div class="col-md-4">
                                    <input type="text" readonly v-model="proyecto" class="form-control">
                                </div>
                                <label class="col-md-1 form-control-label" for="text-input">Etapa:</label>
                                <div class="col-md-4">
                                    <input type="text" readonly v-model="etapa" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 form-control-label" for="text-input">Manzana:</label>
                                <div class="col-md-4">
                                    <input type="text" readonly v-model="manzana" class="form-control">
                                </div>
                                <label class="col-md-1 form-control-label" for="text-input"> Lote:</label>
                                <div class="col-md-3">
                                    <input type="text" readonly v-model="lote" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row line-separator"></div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <h6 style="color: #153157;">Cliente que nos recomienda: </h6>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 form-control-label" for="text-input">Nombre:</label>
                                <div class="col-md-5">
                                    <input type="text" name="city3" list="cityname3" class="form-control" v-model="recomendado" v-on:keypress="selectCliente(recomendado)" @click="getDatos(recomendado)" @keyup.enter="getDatos(recomendado)">
                                        <datalist id="cityname3" @click="getDatos(recomendado)">
                                            <option value="">Seleccione</option>
                                            <option v-for="cliente in arrayClientes" :key="cliente.id " :value="cliente.nombre_cliente" v-text="cliente.nombre_cliente"></option>    
                                        </datalist>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 form-control-label" for="text-input">Proyecto:</label>
                                <div class="col-md-4">
                                    <input type="text" v-model="proyecto_rec" class="form-control">
                                </div>
                                <label class="col-md-1 form-control-label" for="text-input">Etapa:</label>
                                <div class="col-md-4">
                                    <input type="text" v-model="etapa_rec" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 form-control-label" for="text-input">Manzana:</label>
                                <div class="col-md-4">
                                    <input type="text" v-model="manzana_rec" class="form-control">
                                </div>
                                <label class="col-md-1 form-control-label" for="text-input"> Lote:</label>
                                <div class="col-md-3">
                                    <input type="text" v-model="lote_rec" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row line-separator"></div>

                            <br>
                            <div class="form-group row">
                                <h6 class="col-md-3 form-control-label" for="text-input">Descripcion del bono</h6>
                                <div class="col-md-9">
                                    <input type="text" v-model="descripcion" class="form-control" placeholder="Descripción del bono">
                                </div>
                            </div>

                            <div class="form-group row">
                                <h6 style="color: blue;" class="col-md-3 form-control-label" for="text-input">Bono</h6>
                                <div class="col-md-3">
                                    <input type="text" v-model="monto" class="form-control" placeholder="Monto">
                                </div>
                                <div class="col-md-3">
                                    <h6 style="color: blue;" v-text="'$'+formatNumber(monto)"></h6>
                                </div>
                            </div>


                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==1 && fecha_pago!=''" class="btn btn-primary" @click="generarPago()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="updateBono()">Guardar cambios</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->

            <!--Inicio del modal observaciones-->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal2}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
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
                                    <label class="col-md-3 form-control-label" for="text-input">Observacion</label>
                                    <div class="col-md-6">
                                         <textarea rows="3" cols="30" v-model="observacion" class="form-control" placeholder="Observacion"></textarea>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button"  class="btn btn-primary" @click="agregarComentario()">Guardar</button>
                                    </div>
                                </div>

                                
                                <table class="table table-bordered table-striped table-sm" >
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
                                            <td v-text="observacion.observacion" ></td>
                                            <td v-text="observacion.created_at"></td>
                                        </tr>                               
                                    </tbody>
                                </table>
                                
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                        </div>
                    </div>
                      <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            

        </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    export default {
        props:{
            rolId:{ty6e: String}
        },
        data(){
            return{
                proceso:false,
                id :0,
                
                fecha_pago : new Date().toISOString().substr(0, 10),
                monto : '',
                descripcion : '',
                recomendado : '',
                proyecto: '',
                etapa: '',
                manzana : '',
                lote: '',
                cliente: '',
                arrayBonos : [],
                arrayFraccionamientos: [],
                arrayEtapas: [],
                arrayClientes:[],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                errorCatalogo : 0,
                criterio : 'fraccionamiento',
                errorMostrarMsjCatalogo : [],
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                buscar : '',
                b_etapa: '',
                b_manzana: '',
                b_lote: '',
                status : '',

                id:'',
                observacion:'',
                arrayObservacion:[],
                modal2:'',

                proyecto_rec :'',
                manzana_rec : '',
                etapa_rec:'',
                lote_rec:'',
                b_empresa:'',
                empresas:[],
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
            listarBonos(page){
                let me = this;
                var url = '/bono_recomendado/index?page=' + page  + '&criterio=' + me.criterio + '&buscar=' + me.buscar + '&b_etapa=' + me.b_etapa
                                     + '&b_manzana=' + me.b_manzana + '&b_lote=' + me.b_lote + '&status=' + me.status +'&b_empresa='+this.b_empresa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayBonos = respuesta.bonos.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listarObservacion(buscar){
                let me = this;
                var url = '/bono_recomendado/listarObs?id=' + buscar ;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayObservacion = respuesta.observacion;
                    console.log(url);
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },

            selectCliente(buscar){
                let me = this;
                me.arrayClientes = [];
                var url = '/select_clientesVenta?buscar=' + buscar ;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayClientes = respuesta.clientes;
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },

            getDatos(buscar){
                let me = this;
                var url = '/clientes/getDatosVentas?buscar=' + buscar ;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.proyecto_rec = respuesta.clientes[0].fraccionamiento;
                    me.etapa_rec = respuesta.clientes[0].etapa;
                    me.manzana_rec = respuesta.clientes[0].manzana;
                    me.lote_rec = respuesta.clientes[0].num_lote;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            aprobarBono(id){
                if( this.rolId == 6 || this.rolId == 1 ){ 
                    let me = this;
                    //Con axios se llama el metodo update de LoteController
                
                    Swal({
                        title: 'Estas seguro?',
                        animation: false,
                        customClass: 'animated bounceInDown',
                        text: "El bono sera aprobado.",
                        type: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        cancelButtonText: 'Cancelar',
                        
                        confirmButtonText: 'Si, aprobar!'
                        }).then((result) => {

                        if (result.value) {
                        
                            axios.post('/bono_recomendado/aprobarBono',{
                                'id':id
                            }); 
                            
                            me.listarBonos(me.pagination.current_page);
                            Swal({
                                title: 'Hecho!',
                                text: 'Bono aprobado',
                                type: 'success',
                                animation: false,
                                customClass: 'animated bounceInRight'
                            })
                        }
                    })
                }
                else{
                    Swal.fire({
                        type:'warning',
                        title: 'Sin permisos...',
                        text: 'No tiene permisos para realizar esta accion!',
                        })
                }
              
            },
            autorizarBono(id){
                if( this.rolId == 6 || this.rolId == 1 ){ 
                    let me = this;
                    //Con axios se llama el metodo update de LoteController
                
                    Swal({
                        title: 'Estas seguro?',
                        animation: false,
                        customClass: 'animated bounceInDown',
                        text: "El bono sera autorizado.",
                        type: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        cancelButtonText: 'Cancelar',
                        
                        confirmButtonText: 'Si, aprobar!'
                        }).then((result) => {

                        if (result.value) {
                        
                            axios.post('/bono_recomendado/autorizarBono',{
                                'id':id
                            }); 
                            
                            me.listarBonos(me.pagination.current_page);
                            Swal({
                                title: 'Hecho!',
                                text: 'Bono autorizado',
                                type: 'success',
                                animation: false,
                                customClass: 'animated bounceInRight'
                            })
                        }
                    })
                }
                else{
                    Swal.fire({
                        type:'warning',
                        title: 'Sin permisos...',
                        text: 'No tiene permisos para realizar esta accion!',
                        })
                }
              
            },

            cancelarBono(id){
                if( this.rolId == 6 || this.rolId == 1 ){ 
                    let me = this;
                    //Con axios se llama el metodo update de LoteController
                
                    Swal({
                        title: 'Estas seguro?',
                        animation: false,
                        customClass: 'animated bounceInDown',
                        text: "El bono sera cancelado.",
                        type: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        cancelButtonText: 'Cancelar',
                        
                        confirmButtonText: 'Si, cancelar!'
                        }).then((result) => {

                        if (result.value) {
                        
                            axios.post('/bono_recomendado/cancelarBono',{
                                'id':id
                            }); 
                            
                            me.listarBonos(me.pagination.current_page);
                            Swal({
                                title: 'Hecho!',
                                text: 'Bono cancelado',
                                type: 'success',
                                animation: false,
                                customClass: 'animated bounceInRight'
                            })
                        }
                    })
                }
                else{
                    Swal.fire({
                        type:'warning',
                        title: 'Sin permisos...',
                        text: 'No tiene permisos para realizar esta accion!',
                        })
                }
              
            },

            generarPago(){
                let me = this;
                //Con axios se llama el metodo update de DepartamentoController
                axios.post('/bono_recomendado/generarPago',{
                    'id': this.id,
                    'fecha_pago' : this.fecha_pago,
                }).then(function (response){
                    me.listarBonos(me.pagination.current_page);
                    me.cerrarModal();
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
            updateBono(){
                let me = this;
                //Con axios se llama el metodo update de DepartamentoController
                axios.put('/bono_recomendado/update',{
                    'id': this.id,
                    'monto' : this.monto,
                    'recomendado' : this.recomendado,
                    'descripcion' : this.descripcion,
                    'proyecto_rec':this.proyecto_rec,
                    'etapa_rec':this.etapa_rec,
                    'manzana_rec':this.manzana_rec,
                    'lote_rec':this.lote_rec,
                }).then(function (response){
                    me.listarBonos(me.pagination.current_page);
                    me.cerrarModal();
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
            cambiarPagina(page){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarBonos(page);
            },
            
            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "'")
            },
                  
            selectFraccionamientos(){
                let me = this;
                me.b_etapa = '';

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
                this.modal2 = 0;
                this.tituloModal = '';
                this.fraccionamiento_id = '';
                this.etapa_id = '';
                this.fecha_pago = new Date().toISOString().substr(0, 10);
                this.proyecto_rec='';
                this.etapa_rec='';
                this.manzana_rec='';
                this.lote_rec='';
                this.monto = '';
                this.descripcion = '';
                this.errorCatalogo = 0;
                this.errorMostrarMsjCatalogo = [];
                this.proceso=false;
                this.observacion = '';
                this.arrayObservacion = [];

            },
            agregarComentario(){
                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/bono_recomendado/storeObservacion',{
                    'id': this.id,
                    'observacion': this.observacion
                }).then(function (response){
                    me.listarObservacion(me.id);
                    me.observacion = '';
                    //me.cerrarModal3(); //al guardar el registro se cierra el modal
                    
                    const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                    });

                    toast({
                    type: 'success',
                    title: 'Observación Agregada Correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(accion,data =[]){
                switch(accion){
                    case 'actualizar':{
                        this.modal = 1
                        this.tituloModal = 'Actualizar Bono';
                        this.tipoAccion = 2;
                        this.id = data['id'];
                        this.monto=data['monto'];
                        this.descripcion=data['descripcion'];
                        this.cliente = data['nombre_cliente'];
                        this.recomendado = data['recomendado'];
                        this.proyecto = data['proyecto'];
                        this.etapa = data['num_etapa'];
                        this.manzana = data['manzana'];
                        this.lote = data['num_lote'];
                        this.proyecto_rec = data['proyecto_rec'];
                        this.etapa_rec = data['etapa_rec'];
                        this.manzana_rec = data['manzana_rec'];
                        this.lote_rec = data['lote_rec'];

                        this.selectCliente(this.recomendado);

                        break;
                    }
                    
                    case 'pagar':
                    {
                        if(this.rolId == 9 || this.rolId == 6){
                        //console.log(data);
                        this.modal =1;
                        this.tituloModal='Generar pago';
                        this.tipoAccion=1;
                        this.id=data['id'];
                        this.monto=data['monto'];
                        this.descripcion=data['descripcion'];
                        this.cliente = data['nombre_cliente'];
                        this.recomendado = data['recomendado'];
                        this.proyecto = data['proyecto'];
                        this.etapa = data['num_etapa'];
                        this.manzana = data['manzana'];
                        this.lote = data['num_lote'];
                        this.proyecto_rec = data['proyecto_rec'];
                        this.etapa_rec = data['etapa_rec'];
                        this.manzana_rec = data['manzana_rec'];
                        this.lote_rec = data['lote_rec'];
                        }
                        else{
                            Swal.fire({
                                type:'warning',
                                title: 'Sin permisos...',
                                text: 'No tiene permisos para realizar esta accion!',
                                })
                        }
                        

                        break;
                    }
                    case 'observaciones':{
                        this.modal2 =1;
                        this.tituloModal='Observaciones';
                        this.observacion='';
                        this.usuario='';
                        this.id=data['id'];
                        this.listarObservacion(this.id);
                        break;
                    }

                    case 'detalle':{
                        this.modal = 1;
                        this.tituloModal='Detalle del pago';
                        this.tipoAccion=3;
                        this.id=data['id'];
                        this.monto=data['monto'];
                        this.descripcion=data['descripcion'];
                        this.cliente = data['nombre_cliente'];
                        this.recomendado = data['recomendado'];
                        this.proyecto = data['proyecto'];
                        this.etapa = data['num_etapa'];
                        this.manzana = data['manzana'];
                        this.lote = data['num_lote'];
                        this.proyecto_rec = data['proyecto_rec'];
                        this.etapa_rec = data['etapa_rec'];
                        this.manzana_rec = data['manzana_rec'];
                        this.lote_rec = data['lote_rec'];
                        this.fecha_pago = data['fecha_pago'];
                    }
                }

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
            this.listarBonos(1);
            this.selectFraccionamientos();
            this.getEmpresa();
        }
    }
</script>
<style>
    .line-separator{
        height:1px;
        background:#717171;
        border-bottom:1px solid #c2cfd6;
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
</style>
