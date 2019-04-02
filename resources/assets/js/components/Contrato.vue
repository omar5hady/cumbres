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
                        <i class="fa fa-align-justify"></i> Contratos
                        <!--   Boton agregar    -->
                        <button type="button" @click="mostrarCreditosAprobados()" class="btn btn-primary" v-if="listado==0">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                        <button type="button" @click="listado=0" class="btn btn-success" v-if="listado==1">
                            <i class="fa fa-mail-reply"></i>&nbsp;Regresar
                        </button>
                    </div>

            <!----------------- Listado Contratos ------------------------------>
                    <!-- Div Card Body para listar -->
                     <template v-if="listado == 0">
                        <div class="card-body"> 
                            <div class="form-group row">
                                <div class="col-md-8">
                                       <div class="input-group">
                                        <!--Criterios para el listado de busqueda -->
                                        <select class="form-control col-md-4" v-model="criterio2" @click="limpiarBusqueda()">
                                            <option value="creditos.id"># Folio</option>
                                            <option value="personal.nombre">Cliente</option>
                                            <option value="v.nombre">Vendedor</option>
                                            <option value="creditos.fraccionamiento">Proyecto</option>
                                            <option value="contratos.fecha">Fecha</option>
                                        </select>
                                          <select class="form-control" v-if="criterio2=='creditos.fraccionamiento'" v-model="buscar2" >
                                            <option value="">Seleccione</option>
                                            <option v-for="proyecto in arrayFraccionamientos" :key="proyecto.id" :value="proyecto.nombre" v-text="proyecto.nombre"></option>
                                         </select>
                                    <input v-if="criterio2=='creditos.fraccionamiento'" type="text"  v-model="b_etapa2" @keyup.enter="listarContratos(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)" class="form-control" placeholder="Etapa">
                                    <input v-if="criterio2=='creditos.fraccionamiento'" type="text"  v-model="b_manzana2" @keyup.enter="listarContratos(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)" class="form-control" placeholder="Manzana">
                                    <input v-if="criterio2=='creditos.fraccionamiento'" type="text"  v-model="b_lote2" @keyup.enter="listarContratos(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)" class="form-control" placeholder="# Lote">
                                        <input  v-if="criterio2=='contratos.fecha'" type="date" v-model="buscar2" @keyup.enter="listarContratos(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)" class="form-control">
                                        <input  v-if="criterio2=='personal.nombre' || criterio2=='v.nombre' || criterio2=='creditos.id'" type="text" v-model="buscar2" @keyup.enter="listarContratos(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)" class="form-control">
                                        <button type="submit" @click="listarContratos(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                   
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table2 table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th># Contrato</th>
                                            <th>Cliente</th>
                                            <th>Vendedor</th>
                                            <th>Proyecto</th>
                                            <th>Etapa</th>
                                            <th>Manzana</th>
                                            <th># Lote</th>
                                            <th>Modelo</th>
                                            <th>Fecha del contrato</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="contrato in arrayContratos" :key="contrato.id" v-on:dblclick="verContrato(contrato)">
                                            <td class="td2" v-text="contrato.id"></td>
                                            <td class="td2" v-text="contrato.nombre + ' ' + contrato.apellidos "></td>
                                            <td class="td2" v-text="contrato.vendedor_nombre + ' ' + contrato.vendedor_apellidos "></td>
                                            <td class="td2" v-text="contrato.fraccionamiento"></td>
                                            <td class="td2" v-text="contrato.etapa"></td>
                                            <td class="td2" v-text="contrato.manzana"></td>
                                            <td class="td2" v-text="contrato.num_lote"></td>
                                            <td class="td2" v-text="contrato.modelo"></td>
                                            <td class="td2" v-text="this.moment(contrato.fecha).locale('es').format('DD/MMM/YYYY')"></td>
                                            <td class="td2" v-if="contrato.status == '0'">
                                                <span class="badge badge-danger">Cancelado</span>
                                            </td>
                                            <td class="td2" v-if="contrato.status == '1'">
                                                <span class="badge badge-warning">Pendiente</span>
                                            </td>
                                            <td class="td2" v-if="contrato.status == '2'">
                                                <span class="badge badge-danger">No firmado</span>
                                            </td>
                                            <td class="td2" v-if="contrato.status == '3'">
                                                <span class="badge badge-success">Firmado</span>
                                            </td>
                                        </tr>                               
                                    </tbody>
                                </table>
                            </div>
                            <nav>
                                <!--Botones de paginacion -->
                                <ul class="pagination">
                                    <li class="page-item" v-if="pagination2.current_page > 1">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page - 1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)">Ant</a>
                                    </li>
                                    <li class="page-item" v-for="page2 in pagesNumber2" :key="page2" :class="[page2 == isActived ? 'active' : '']">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina2(page2,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)" v-text="page2"></a>
                                    </li>
                                    <li class="page-item" v-if="pagination2.current_page < pagination2.last_page">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page + 1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </template>

            <!----------------- Listado de Simulaciones de Credito Aprobadas ------------------------------>
                    <!-- Div Card Body para listar -->
                     <template v-if="listado == 1">
                        <div class="card-body"> 
                            <div class="form-group row">
                                <div class="col-md-8">
                                       <div class="input-group">
                                        <!--Criterios para el listado de busqueda -->
                                        <select class="form-control col-md-4" v-model="criterio" @click="limpiarBusqueda()">
                                            <option value="creditos.id"># Folio</option>
                                            <option value="personal.nombre">Cliente</option>
                                            <option value="v.nombre">Vendedor</option>
                                            <option value="creditos.fraccionamiento">Proyecto</option>
                                            <option value="inst_seleccionadas.tipo_credito">Tipo de credito</option>
                                        </select>
                                          <select class="form-control" v-if="criterio=='creditos.fraccionamiento'" v-model="buscar" >
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.nombre" :value="fraccionamientos.nombre" v-text="fraccionamientos.nombre"></option>
                                         </select>
                                    <input v-if="criterio=='creditos.fraccionamiento'" type="text"  v-model="b_etapa" @keyup.enter="listarSimulaciones(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="form-control" placeholder="Etapa">
                                    <input v-if="criterio=='creditos.fraccionamiento'" type="text"  v-model="b_manzana" @keyup.enter="listarSimulaciones(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="form-control" placeholder="Manzana">
                                    <input v-if="criterio=='creditos.fraccionamiento'" type="text"  v-model="b_lote" @keyup.enter="listarSimulaciones(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="form-control" placeholder="# Lote">
                                        <input  v-else type="text" v-model="buscar" @keyup.enter="listarSimulaciones(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="form-control">
                                        <button type="submit" @click="listarSimulaciones(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                   
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table2 table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th># Folio</th>
                                            <th>Cliente</th>
                                            <th>Vendedor</th>
                                            <th>Proyecto</th>
                                            <th>Etapa</th>
                                            <th>Manzana</th>
                                            <th># Lote</th>
                                            <th>Modelo</th>
                                            <th>Precio Venta</th>
                                            <th>Credito Solicitado</th>
                                            <th>Plazo</th>
                                            <th>Institucion</th>
                                            <th>Tipo de credito</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="prospecto in arraySimulaciones" :key="prospecto.id" v-on:dblclick="obtenerDatosCredito(prospecto.id)">
                                            <td class="td2" v-text="prospecto.id"></td>
                                            <td class="td2" v-text="prospecto.nombre + ' ' + prospecto.apellidos "></td>
                                            <td class="td2" v-text="prospecto.vendedor_nombre + ' ' + prospecto.vendedor_apellidos "></td>
                                            <td class="td2" v-text="prospecto.proyecto"></td>
                                            <td class="td2" v-text="prospecto.etapa"></td>
                                            <td class="td2" v-text="prospecto.manzana"></td>
                                            <td class="td2" v-text="prospecto.num_lote"></td>
                                            <td class="td2" v-text="prospecto.modelo"></td>
                                            <td class="td2" v-text="'$'+formatNumber(prospecto.precio_venta)"></td>
                                            <td class="td2" v-text="'$'+formatNumber(prospecto.credito_solic)"></td>
                                            <td class="td2" v-text="prospecto.plazo + ' años'"></td>
                                            <td class="td2" v-text="prospecto.institucion"></td>
                                            <td class="td2" v-text="prospecto.tipo_credito"></td>
                                            <td class="td2" v-if="prospecto.status == '2'">
                                                <span class="badge badge-success">Aprobado</span>
                                            </td>
                                        </tr>                               
                                    </tbody>
                                </table>
                            </div>
                            <nav>
                                <!--Botones de paginacion -->
                                <ul class="pagination">
                                    <li class="page-item" v-if="pagination.current_page > 1">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,b_etapa,b_manzana,b_lote,criterio)">Ant</a>
                                    </li>
                                    <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,b_etapa,b_manzana,b_lote,criterio)" v-text="page"></a>
                                    </li>
                                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,b_etapa,b_manzana,b_lote,criterio)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </template>

            <!----------------- Vista para crear un contrato ------------------------------>
                    <!-- Div Card Body para registrar simulacion -->
                    <template v-else-if="listado == 3 || listado == 4">
                        <div class="card-body"> 

                            <!-- Acordeon -->
                            <div id="accordion" role="tablist">
                                <div class="card mb-0">
                                    <div class="card-header" id="headingOne" role="tab">
                                        <h5 class="mb-0">
                                            <a data-toggle="collapse" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne" class="collapsed">Datos del Prospecto</a>
                                        </h5>
                                    </div>
                                    <div class="collapse" id="collapseOne" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion" style="">
                                    <!--Datos Prospecto-->
                                    <div class="form-group row border border-primary" style="margin-right:0px; margin-left:0px;">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                <center> <h3></h3> </center>
                                                </div>
                                            </div> 

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                <label for="">Nombre <span style="color:red;" v-show="nombre==''">(*)</span> </label>
                                                <input type="text" :readonly="listado==4"  class="form-control" v-model="nombre" placeholder="Nombre">
                                                </div>
                                            </div> 


                                            <div class="col-md-4">
                                                <div class="form-group">
                                                <label for="">Apellidos <span style="color:red;" v-show="apellidos==''">(*)</span></label>
                                                <input type="text" :readonly="listado==4" class="form-control" v-model="apellidos" placeholder="Apellidos">
                                            </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                <label for="">Sexo <span style="color:red;" v-show="sexo==''">(*)</span></label>
                                                <select :disabled="listado==4" class="form-control" v-model="sexo" >
                                                        <option value="">Seleccione</option>
                                                        <option value="F">Femenino</option>
                                                        <option value="M">Masculino</option>
                                                </select>
                                            </div>
                                            </div>

                                                <div class="col-md-4">
                                                <div class="form-group">
                                                <label for="">Telefono </label>
                                                <input  type="text" :readonly="listado==4" maxlength="7" pattern="\d*" class="form-control" v-on:keypress="isNumber($event)" v-model="telefono" placeholder="Telefono">
                                            </div>
                                            </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                <label for="">Celular <span style="color:red;" v-show="celular==''">(*)</span></label>
                                                <input  type="text" :readonly="listado==4" pattern="\d*" maxlength="10" class="form-control" v-on:keypress="isNumber($event)" v-model="celular" placeholder="Celular">
                                            </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                <label for="">Email personal <span style="color:red;" v-show="email==''">(*)</span></label>
                                                <input  type="text" :readonly="listado==4" class="form-control" v-model="email" placeholder="E-mail">
                                            </div>
                                                </div>

                                            <div class="col-md-3">
                                                    <div class="form-group">
                                                <label for="">Direccion<span style="color:red;" v-show="direccion==''">(*)</span></label>
                                                <input type="text" :readonly="listado==4" class="form-control"  v-model="direccion" placeholder="Direccion">
                                                </div>
                                            </div>

                                            <div class="col-md-1">
                                                    <div class="form-group">
                                                <label for="">C.P<span style="color:red;" v-show="cp==''">(*)</span></label>
                                                <input type="text" :readonly="listado==4" class="form-control"  pattern="\d*" maxlength="5" v-on:keypress="isNumber($event)" @keyup="selectColonias(cp,0)"  v-model="cp" placeholder="C.Postal">
                                            </div>
                                            </div>

                                            <div class="col-md-2">
                                                    <div class="form-group">
                                                <label for="">Colonia<span style="color:red;" v-show="colonia==''">(*)</span></label>
                                                <select class="form-control" v-if="listado==3" v-model="colonia">
                                                    <option value="">Seleccione</option>
                                                    <option v-for="colonias in arrayColonias" :key="colonias.colonia " :value="colonias.colonia" v-text="colonias.colonia"></option>
                                                </select>
                                                <input type="text" v-if="listado==4" class="form-control" :readonly="listado==4" v-model="colonia">
                                            </div>
                                            </div>

                                            
                                            <div class="col-md-3">
                                                    <div class="form-group">
                                                <label for="">Estado <span style="color:red;" v-show="estado==''">(*)</span></label>
                                                    <select class="form-control" v-if="listado==3" v-model="estado" @click="selectCiudades(estado,0)" >
                                                        <option value="">Seleccione</option>
                                                        <option v-for="estados in arrayEstados" :key="estados.estado" :value="estados.estado" v-text="estados.estado"></option>    
                                                    </select>
                                                    <input type="text" v-if="listado==4" class="form-control" :readonly="listado==4" v-model="estado">
                                                    </div>
                                                </div>


                                                    
                                            <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="">Ciudad <span style="color:red;" v-show="ciudad==''">(*)</span></label>
                                                        <select class="form-control" v-if="listado==3" v-model="ciudad" >
                                                            <option value="">Seleccione</option>
                                                            <option v-for="ciudades in arrayCiudades" :key="ciudades.municipio" :value="ciudades.municipio" v-text="ciudades.municipio"></option>    
                                                        </select>
                                                        <input type="text" v-if="listado==4" class="form-control" :readonly="listado==4" v-model="ciudad">
                                                    </div>
                                                </div>

                                                <!--------------------------------------------------------------------------------------------------
                                                ------------------------------------------------>


                                                <div class="col-md-2">
                                                <div class="form-group">
                                                <label for="">Fecha de nacimiento <span style="color:red;" v-show="fecha_nac==''">(*)</span></label>
                                                <input :readonly="listado==4" type="date" class="form-control"  v-model="fecha_nac" >
                                            </div>
                                            </div>

                                            <div class="col-md-2">
                                                    <div class="form-group">
                                                <label for="">Nacionalidad</label>
                                                <select :readonly="listado==4" class="form-control" v-model="nacionalidad" >
                                                        <option value="0">Mexicana</option>
                                                        <option value="1">Extranjera</option>    
                                                </select>
                                            </div>
                                            </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                <label for="">CURP</label>
                                                <input :readonly="listado==4" type="text" maxlength="18" class="form-control"  v-model="curp" placeholder="CURP">
                                            </div>
                                                </div>

                                                <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">RFC <span style="color:red;" v-show="rfc==''">(*)</span></label>
                                                    <input :readonly="listado==4" type="text" maxlength="10" class="form-control"  v-model="rfc" placeholder="RFC">
                                                </div>
                                                </div>
                                                    
                                            <div align="left" class="col-md-1">
                                                <div class="form-group">
                                                <label for="">Homoclave</label>
                                                        <input :readonly="listado==4" type="text" maxlength="3" class="form-control"  v-model="homoclave" placeholder="AA0">
                                                </div>
                                            </div>

                                                
                                            <div class="col-md-2">
                                                    <div class="form-group">
                                                <label for="">NSS <span style="color:red;" v-show="nss==''">(*)</span></label>
                                                <input :readonly="listado==4" type="text" maxlength="11" pattern="\d*" class="form-control" v-on:keypress="isNumber($event)" v-model="nss" placeholder="NSS">
                                            </div>
                                            </div>                           
                                            
                                        </div>
                                    <!-- Fin datos prospecto-->
                                    <!-------------------------------------- DATOS DE TRABAJO ------------------------------------->
                                        <div class="form-group row border border-primary" style="margin-right:0px; margin-left:0px;">
                                            <div class="col-md-12">
                                                    <div class="form-group">
                                                    <center> <h5>Lugar de trabajo</h5> </center>
                                                    </div>
                                                </div>
                                            
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                <label for="">Tipo de economia <span style="color:red;" v-show="tipo_economia==0">(*)</span></label>
                                                    <select :disabled="listado==4" class="form-control" v-model="tipo_economia" >
                                                        <option value="0">Seleccione</option>  
                                                        <option value="Formal">Formal</option>
                                                        <option value="Informal">Informal</option>
                                                        <option value="Mixta">Mixta</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6" v-if="tipo_economia!=0">
                                                <div class="form-group">
                                                    <label for="">Empresa <span style="color:red;" v-show="empresa==0">(*)</span></label>
                                                        <select v-if="listado==3" class="form-control" v-model="empresa" @click="getDatosEmpresa(empresa,0)">
                                                            <option value="">Seleccione</option>
                                                            <option v-for="empresas in arryaEmpresas" :key="empresas.nombre" :value="empresas.nombre" v-text="empresas.nombre"></option>    
                                                        </select>
                                                        <input type="text" v-if="listado==4" class="form-control" :readonly="listado==4" v-model="empresa">
                                                </div>
                                            </div>

                                                <div class="col-md-3" v-if="tipo_economia=='Formal' ||tipo_economia=='Mixta'">
                                                    <div class="form-group">
                                                        <label for="">Puesto <span style="color:red;" v-show="!puesto || puesto==''">(*)</span></label>
                                                        <input :readonly="listado==4" type="text" class="form-control"  v-model="puesto">
                                                    </div>
                                                </div>

                                                <div class="col-md-3" v-if="tipo_economia=='Informal'">
                                                    <div class="form-group">
                                                        <label for="">Giro del negocio <span style="color:red;" v-show="!puesto || puesto==''">(*)</span></label>
                                                        <input :readonly="listado==4" type="text" class="form-control"  v-model="puesto">
                                                    </div>
                                                </div>

                                            
                                            <div class="col-md-3" v-if="tipo_economia!=0">
                                                <div class="form-group">
                                                    <label for="">Domicilio de empresa <span style="color:red;" v-show="empresa==0">(*)</span></label>
                                                        <input :readonly="listado==4" v-if="empresa != null" type="text" class="form-control" v-model="direccion_empresa">
                                                    </div>
                                            </div>
                                            <div class="col-md-1" v-if="tipo_economia!=0">
                                                <div class="form-group">
                                                    <label for="">CP <span style="color:red;" v-show="empresa==0">(*)</span></label>
                                                        <input :readonly="listado==4" v-if="empresa != null" type="text" class="form-control"  v-model="cp_empresa">
                                                </div>
                                            </div>

                                            <div class="col-md-2" v-if="tipo_economia!=0">
                                                <div class="form-group">
                                                    <label for="">Colonia <span style="color:red;" v-show="empresa==0">(*)</span></label>
                                                        <input :readonly="listado==4" v-if="empresa != null" type="text" class="form-control"  v-model="colonia_empresa">
                                                </div>
                                            </div>

                                            <div class="col-md-3" v-if="tipo_economia!=0">
                                                <div class="form-group">
                                                    <label for="">Estado <span style="color:red;" v-show="empresa==0">(*)</span></label>
                                                        <input :readonly="listado==4" v-if="empresa != null" type="text" class="form-control"  v-model="estado_empresa">
                                                </div>
                                            </div>

                                            <div class="col-md-3" v-if="tipo_economia!=0">
                                                <div class="form-group">
                                                    <label for="">Ciudad <span style="color:red;" v-show="empresa==0">(*)</span></label>
                                                        <input :readonly="listado==4" v-if="empresa != null" type="text" class="form-control"  v-model="ciudad_empresa">
                                                </div>
                                            </div>

                                                
                                            <div class="col-md-3" v-if="tipo_economia!=0">
                                                        <div class="form-group">
                                                    <label for="">Email institucional </label>
                                                    <input :readonly="listado==4" type="text" class="form-control" v-model="email_inst" placeholder="E-mail">
                                                </div>
                                            </div>

                                            <div class="col-md-3" v-if="tipo_economia!=0">
                                                <div class="form-group">
                                                    <label for="">Telefono de la empresa </label>
                                                    <input :readonly="listado==4" type="text" class="form-control" v-model="telefono_empresa" placeholder="Telefono">
                                                </div>
                                            </div>

                                            <div class="col-md-2" v-if="tipo_economia!=0">
                                                <div class="form-group">
                                                    <label for=""> Ext. </label>
                                                    <input :readonly="listado==4" type="text" class="form-control" v-model="ext_empresa" placeholder="Ext">
                                                </div>
                                            </div>
                                        </div>  
                                    <!-- Fin Lugar de trabajo-->
                                    <!--------------------------------------------  Apartado  de datos vive en casa , edo civil -------------------------------->
                                                    <div class="form-group row border border-primary" style="margin-right:0px; margin-left:0px;" >    

                                                            <div class="col-md-3">
                                                            <div class="form-group">
                                                            <label for="">Estado civil <span style="color:red;" v-show="e_civil==0">(*)</span></label>
                                                                <select :disabled="listado==4" class="form-control" v-model="e_civil" >
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
                                                            </div>

                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for=""># Dependientes económicos<span style="color:red;" v-show="dep_economicos==''">(*)</span></label>
                                                                    <input :readonly="listado==4" type="text" class="form-control" v-model="dep_economicos">
                                                                </div>
                                                            </div>

                                                    </div>
                                    </div>
                                </div>
                                <div class="card mb-0"  v-if="coacreditado==true">
                                    <div class="card-header" id="headingTwo" role="tab"  v-if="coacreditado==true">
                                        <h5 class="mb-0">
                                            <a class="collapsed" data-toggle="collapse" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Cónyuge o Coacreditado</a>
                                        </h5>
                                    </div>
                                    <div class="collapse" id="collapseTwo" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion">
                                        <!--------------------------------  Apartado de datos del Conyuge o Coacreditado ----------------------------------------------->
                                        <div class="form-group row border border-dark" style="margin-right:0px; margin-left:0px;" v-if="coacreditado==true" >  
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                    <center> <h3></h3> </center>
                                                    </div>
                                                </div>   

                                                <div class="col-md-3" v-if="coacreditado==true">
                                                    <div class="form-group">
                                                        <label for=""> Nombre del conyuge </label>
                                                        <input :readonly="listado==4" type="text" v-if="nombre_coa != null" class="form-control" v-model="nombre_coa">
                                                    </div>
                                                </div>

                                                <div class="col-md-3" v-if="coacreditado==true">
                                                    <div class="form-group">
                                                        <label for="">Apellidos del conyuge </label>
                                                        <input :readonly="listado==4" type="text" v-if="apellidos_coa != null" class="form-control" v-model="apellidos_coa">
                                                    </div>
                                                </div>

                                                <div class="col-md-2" v-if="coacreditado==true">
                                                        <div class="form-group">
                                                    <label for="">Parentesco </label>
                                                    <input :readonly="listado==4" type="text" class="form-control" v-model="parentesco_coa" placeholder="Parentesco">
                                                </div>
                                                </div>

                                                
                                                <div class="col-md-4" v-if="coacreditado==true">
                                                        <div class="form-group">
                                                    <label for="">Fecha de nacimiento </label>
                                                    <input :readonly="listado==4" type="date" class="form-control" v-model="fecha_nac_coa" placeholder="fecha de nacimiento">
                                                </div>
                                                </div>


                                                <div class="col-md-2" v-if="coacreditado==true">
                                                        <div class="form-group">
                                                    <label for="">RFC</label>
                                                    <input :readonly="listado==4" type="text" class="form-control" v-model="rfc_coa" placeholder="rfc">
                                                </div>
                                                </div>

                                                    <div class="col-md-2" v-if="coacreditado==true">
                                                        <div class="form-group">
                                                    <label for="">Homoclave</label>
                                                    <input :readonly="listado==4" type="text" class="form-control" v-model="homoclave_coa" placeholder="homoclave">
                                                </div>
                                                </div>


                                                <div class="col-md-3" v-if="coacreditado==true">
                                                        <div class="form-group">
                                                    <label for="">CURP </label>
                                                    <input :readonly="listado==4" type="text" class="form-control" v-model="curp_coa" placeholder="CURP">
                                                </div>
                                                </div>


                                                <div class="col-md-2" v-if="coacreditado==true">
                                                        <div class="form-group">
                                                    <label for="">NSS</label>
                                                    <input :readonly="listado==4" type="text" class="form-control" v-model="nss_coa" placeholder="NSS">
                                                </div>
                                                </div>

                                                <div class="col-md-3" v-if="coacreditado==true">
                                                        <div class="form-group">
                                                    <label for="">Nacionalidad</label>
                                                    <select :disabled="listado==4" class="form-control" v-model="nacionalidad_coa" >
                                                            <option value="">Seleccione</option>
                                                            <option value="0">Mexicana</option>
                                                            <option value="1">Extranjera</option>    
                                                    </select>
                                                </div>
                                                </div>

                                                <div class="col-md-3" v-if="coacreditado==true">
                                                        <div class="form-group">
                                                    <label for="">Direccion</label>
                                                    <input :readonly="listado==4" type="text" class="form-control"  v-model="direccion_coa" placeholder="Direccion">
                                                    </div>
                                                </div>

                                                <div class="col-md-1" v-if="coacreditado==true">
                                                        <div class="form-group">
                                                    <label for="">C.P</label>
                                                    <input :readonly="listado==4" type="text" class="form-control"  pattern="\d*" maxlength="5" v-on:keypress="isNumber($event)" @keyup="selectColonias(cp_coa,1)"  v-model="cp_coa" placeholder="C.Postal">
                                                </div>
                                                </div>

                                                <div class="col-md-2" v-if="coacreditado==true">
                                                        <div class="form-group">
                                                    <label for="">Colonia<span style="color:red;" v-show="colonia_coa==''">(*)</span></label>
                                                        <select v-if="listado==3" class="form-control" v-model="colonia_coa">
                                                            <option value="">Seleccione</option>
                                                            <option v-for="colonia in arrayColoniasCoa" :key="colonia.colonia " :value="colonia.colonia" v-text="colonia.colonia"></option>
                                                        </select>
                                                        <input type="text" v-if="listado==4" class="form-control" :readonly="listado==4" v-model="colonia_coa">
                                                </div>
                                                </div>

                                                
                                                <div class="col-md-3">
                                                        <div class="form-group">
                                                    <label for="">Estado <span style="color:red;" v-show="estado_coa==''">(*)</span></label>
                                                        <select v-if="listado==3" class="form-control" v-model="estado_coa" @click="selectCiudades(estado_coa,1)" >
                                                            <option value="">Seleccione</option>
                                                            <option v-for="estado in arrayEstados" :key="estado.estado " :value="estado.estado" v-text="estado.estado"></option>    
                                                        </select>
                                                        <input type="text" v-if="listado==4" class="form-control" :readonly="listado==4" v-model="estado_coa">
                                                        </div>
                                                    </div>


                                                        
                                                <div class="col-md-3">
                                                        <div class="form-group">
                                                    <label for="">Ciudad <span style="color:red;" v-show="ciudad_coa==''">(*)</span></label>
                                                        <select v-if="listado==3" class="form-control" v-model="ciudad_coa" >
                                                            <option value="">Seleccione</option>
                                                            <option v-for="ciudades in arrayCiudadesCoa" :key="ciudades.municipio" :value="ciudades.municipio" v-text="ciudades.municipio"></option>    
                                                        </select>
                                                        <input type="text" v-if="listado==4" class="form-control" :readonly="listado==4" v-model="ciudad_coa">
                                                        </div>
                                                    </div>
                                                
                                                
                                                <div class="col-md-3" v-if="coacreditado==true">
                                                        <div class="form-group">
                                                    <label for="">Celular</label>
                                                    <input :readonly="listado==4" type="text" maxlength="8" pattern="\d*" class="form-control" v-model="celular_coa" placeholder="Celular">
                                                </div>
                                                </div>

                                                
                                                <div class="col-md-3" v-if="coacreditado==true">
                                                        <div class="form-group">
                                                    <label for="">Telefono</label>
                                                    <input :readonly="listado==4" type="text" maxlength="7" pattern="\d*" class="form-control" v-model="telefono_coa" placeholder="Telefono">
                                                </div>
                                                </div>

                                                
                                                <div class="col-md-3" v-if="coacreditado==true">
                                                        <div class="form-group">
                                                    <label for="">Email</label>
                                                    <input :readonly="listado==4" type="text" class="form-control" v-model="email_coa" placeholder="Correo">
                                                </div>
                                                </div>


                                                <div class="col-md-3" v-if="coacreditado==true">
                                                        <div class="form-group">
                                                    <label for="">Email institucional</label>
                                                    <input :readonly="listado==4" type="text" class="form-control" v-model="email_institucional_coa" placeholder="Correo institucional">
                                                </div>
                                                </div>


                                            <div class="col-md-12" v-if="coacreditado==true">
                                                    <div class="form-group">
                                                    <center> <h5>Lugar de trabajo</h5> </center>
                                                    </div>
                                            </div>
                                            <div class="col-md-6" v-if="coacreditado==true">
                                                <div class="form-group">
                                                    <label for="">Empresa <span style="color:red;" v-show="empresa_coa==0">(*)</span></label>
                                                        <select v-if="listado==3" class="form-control" v-model="empresa_coa" @click="getDatosEmpresa(empresa_coa,1)">
                                                                <option value="">Seleccione</option>
                                                                <option v-for="empresas in arryaEmpresas" :key="empresas.nombre" :value="empresas.nombre" v-text="empresas.nombre"></option>    
                                                        </select>
                                                        <input type="text" v-if="listado==4" class="form-control" :readonly="listado==4" v-model="empresa_coa">
                                                </div>
                                            </div>

                                            <div class="col-md-3" v-if="coacreditado==true">
                                                <div class="form-group">
                                                    <label for="">Domicilio de empresa <span style="color:red;" v-show="empresa==0">(*)</span></label>
                                                        <input :readonly="listado==4" v-if="empresa != null" type="text" class="form-control" v-model="direccion_empresa_coa">
                                                </div>
                                            </div>
                                            <div class="col-md-2" v-if="coacreditado==true">
                                                <div class="form-group">
                                                    <label for="">CP <span style="color:red;" v-show="empresa==0">(*)</span></label>
                                                        <input :readonly="listado==4" v-if="empresa != null" type="text" class="form-control"  v-model="cp_empresa_coa">
                                                </div>
                                            </div>

                                            <div class="col-md-2" v-if="coacreditado==true">
                                                <div class="form-group">
                                                    <label for="">Colonia <span style="color:red;" v-show="empresa==0">(*)</span></label>
                                                        <input :readonly="listado==4" v-if="empresa != null" type="text" class="form-control"  v-model="colonia_empresa_coa">
                                                </div>
                                            </div>

                                            <div class="col-md-3" v-if="coacreditado==true">
                                                <div class="form-group">
                                                    <label for="">Estado <span style="color:red;" v-show="empresa==0">(*)</span></label>
                                                        <input :readonly="listado==4" v-if="empresa != null" type="text" class="form-control"  v-model="estado_empresa_coa">
                                                </div>
                                            </div>

                                            <div class="col-md-3" v-if="coacreditado==true">
                                                <div class="form-group">
                                                    <label for="">Ciudad <span style="color:red;" v-show="empresa==0">(*)</span></label>
                                                        <input :readonly="listado==4" v-if="empresa != null" type="text" class="form-control"  v-model="ciudad_empresa_coa">
                                                </div>
                                            </div>

                                            <div class="col-md-2" v-if="coacreditado==true">
                                                <div class="form-group">
                                                    <label for="">Tel.  </label>
                                                    <input :readonly="listado==4" type="text" class="form-control" v-model="telefono_empresa_coa" placeholder="Telefono">
                                                </div>
                                            </div>

                                            <div class="col-md-2" v-if="coacreditado==true">
                                                <div class="form-group">
                                                    <label for=""> Ext. </label>
                                                    <input :readonly="listado==4" type="text" class="form-control" v-model="ext_empresa_coa" placeholder="Ext">
                                                </div>
                                            </div>
                                                
                                                 
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-0">
                                    <div class="card-header" id="headingThree" role="tab">
                                        <h5 class="mb-0">
                                            <a class="collapsed" data-toggle="collapse" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">Referencias familiares</a>
                                        </h5>
                                    </div>
                                    <div class="collapse" id="collapseThree" role="tabpanel" aria-labelledby="headingThree" data-parent="#accordion">
                                        <!--------------------------------  Apartado Referencias familiares ------------------------------->
                                        <div class="form-group row border border-warning" style="margin-right:0px; margin-left:0px;">
                                                    <div class="col-md-12">
                                                    <div class="form-group">
                                                    <center> <h3></h3> </center>
                                                    </div>
                                                    </div> 
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <center> <h5>Primera referencia</h5> </center>
                                                    </div>
                                                </div> 


                                                <div class="col-md-4">
                                                        <div class="form-group">
                                                    <label for="">Nombre <span style="color:red;" v-show="nombre_referencia1==''">(*)</span></label>
                                                    <input :readonly="listado==4" type="text" class="form-control"  v-model="nombre_referencia1" placeholder="Nombre">
                                                </div>
                                                </div>


                                                <div class="col-md-4">
                                                        <div class="form-group">
                                                    <label for="">Telefono <span style="color:red;" v-show="telefono_referencia1==''">(*)</span></label>
                                                    <input :readonly="listado==4" type="text"  maxlength="10" pattern="\d*" class="form-control"  v-model="telefono_referencia1" placeholder="Telefono">
                                                </div>
                                                </div>

                                                
                                                <div class="col-md-4">
                                                        <div class="form-group">
                                                    <label for="">Celular <span style="color:red;" v-show="celular_referencia1==''">(*)</span></label>
                                                    <input :readonly="listado==4" type="text" maxlength="10"  pattern="\d*" class="form-control"  v-model="celular_referencia1" placeholder="Celular">
                                                </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                    <center> <h5>Segunda referencia</h5> </center>
                                                    </div>
                                                    </div> 

                                                <div class="col-md-4">
                                                        <div class="form-group">
                                                    <label for="">Nombre <span style="color:red;" v-show="nombre_referencia2==''">(*)</span></label>
                                                    <input :readonly="listado==4" type="text" class="form-control"  v-model="nombre_referencia2" placeholder="Nombre">
                                                </div>
                                                </div>


                                                <div class="col-md-4">
                                                        <div class="form-group">
                                                    <label for="">Telefono <span style="color:red;" v-show="telefono_referencia2==''">(*)</span></label>
                                                    <input :readonly="listado==4" type="text" maxlength="10" pattern="\d*" class="form-control"  v-model="telefono_referencia2" placeholder="Telefono">
                                                </div>
                                                </div>

                                                
                                                <div class="col-md-4">
                                                        <div class="form-group">
                                                    <label for="">Celular <span style="color:red;" v-show="celular_referencia2==''">(*)</span></label>
                                                    <input :readonly="listado==4" type="text" maxlength="10" pattern="\d*" class="form-control"  v-model="celular_referencia2" placeholder="Celular">
                                                </div>
                                                </div>

                                                
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-0">
                                    <div class="card-header" id="headingFour" role="tab">
                                        <h5 class="mb-0">
                                            <a class="collapsed" data-toggle="collapse" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">Datos económicos</a>
                                        </h5>
                                    </div>
                                    <div class="collapse" id="collapseFour" role="tabpanel" aria-labelledby="headingFour" data-parent="#accordion">
                                        <!--------------------------------  Apartado  de Datos economicos y tipo de credito ------------------------>
                                            <div class="form-group row border border-danger" style="margin-right:0px; margin-left:0px;">   

                                                        <div class="col-md-12">
                                                        <div class="form-group">
                                                        <center> <h4>Datos de la vivienda</h4> </center>
                                                        </div>
                                                        </div>  

                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for=""><strong> Proyecto </strong> </label>
                                                        </div>
                                                    </div>

                                                    
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <p v-text="proyecto"></p>
                                                        </div>
                                                    </div>
                                
                                                
                                                    <div class="col-md-1">
                                                        <div class="form-group">
                                                            <label for=""><strong> Etapa </strong></label>     
                                                        </div>
                                                    </div>

                                                    
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                           <p v-text="etapa"></p>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12" >
                                                        <h6></h6>
                                                    </div>

                                                    
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for=""><strong>Manzana</strong></label>
                                                        </div>
                                                    </div>

                                                    
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <p v-text="manzana"></p>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-1">
                                                        <div class="form-group">
                                                            <label for=""><strong>Lote</strong></label>
                                                        </div>
                                                    </div>

                                                    
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <p v-text="lote"></p>
                                                        </div>
                                                    </div>

                                                    

                                                    <div class="col-md-12" >
                                                        <h6></h6>
                                                    </div>
                            
                                                    <div class="col-md-3" v-if="modelo!=''">
                                                        <div class="form-group">
                                                            <label style="color:#2271b3;" for=""><strong> Modelo </strong></label>
                                                            <p v-text="modelo"></p>
                                                        </div>
                                                    </div>

                                                     <div class="col-md-3" v-if="precioBase!=''">
                                                        <div class="form-group">
                                                            <label style="color:#2271b3;" for=""><strong> Precio base </strong></label>
                                                            <p v-text="'$'+formatNumber(precioBase)"></p>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3" v-if="precioBase!=''">
                                                        <div class="form-group">
                                                            <label style="color:#2271b3;" for=""><strong> Precio obra extra </strong></label>
                                                            <p v-text="'$'+formatNumber(precioObraExtra)"></p>
                                                        </div>
                                                    </div>

                                                     <div class="col-md-12" v-if="precioBase!=''">
                                                        <div class="form-group">
                                                            <h6></h6>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3" v-if="superficie!=''">
                                                        <div class="form-group">
                                                            <label style="color:#2271b3;" for=""><strong> Superficie terreno m&sup2;</strong></label>
                                                            <p v-text="superficie"></p>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3" v-if="superficie!='' || terreno_tam_excedente>0">
                                                        <div class="form-group">
                                                            <label style="color:#2271b3;" for=""><strong> Terreno excedente m&sup2;</strong></label>
                                                            <p v-text="terreno_tam_excedente"></p>
                                                        </div>
                                                    </div>


                                                    <div class="col-md-3" v-if="precioExcedente!='' || terreno_tam_excedente>0">
                                                        <div class="form-group">
                                                            <label style="color:#2271b3;" for=""><strong> Precio terreno excedente </strong></label>
                                                            <p v-text="'$'+formatNumber(precioExcedente)"></p>
                                                        </div>
                                                    </div> 

                                                    <div class="col-md-12" v-if="precioBase!=''">
                                                        <div class="form-group">
                                                            <h6></h6>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-3" v-if="promocion!=''">
                                                        <div class="form-group">
                                                            <label style="color:#2271b3;" for=""><strong> Promocion </strong></label>
                                                            <p v-text="promocion"></p>
                                                        </div>
                                                    </div> 

                                                    <div class="col-md-3" v-if="descripcionPromo!=''" >
                                                        <div class="form-group">
                                                            <label style="color:#2271b3;" for=""><strong> Descripcion de la promocion </strong></label>
                                                            <p v-text="descripcionPromo"></p>
                                                        </div>
                                                    </div>

                                                        <div class="col-md-3" v-if="descuentoPromo!=0">
                                                        <div class="form-group">
                                                            <label style="color:#2271b3;" for=""><strong> Descuento de la promocion </strong></label>
                                                            <p v-text="'$'+formatNumber(descuentoPromo)"></p>
                                                        </div>
                                                    </div> 

                                                    <div class="col-md-12" >
                                                        <h6></h6>
                                                    </div>

                                                    <div class="col-md-3" v-if="listado==3">
                                                        <div class="form-group">
                                                            <label for="">Paquete</label>
                                                            <select class="form-control" v-model="paquete_id" @click="datosPaquetes(paquete_id)">
                                                                    <option value="0">Seleccione</option>
                                                                    <option v-for="paquetes in arrayPaquetes" :key="paquetes.id" :value="paquetes.id" v-text="paquetes.nombre"></option>
                                                            </select>
                                                        </div>
                                                    </div> 

                                                    <div class="col-md-3" v-if="descripcionPaquete!=''">
                                                        <div class="form-group">
                                                            <label style="color:#2271b3;" for=""><strong> Paquete </strong></label>
                                                            <p v-text="paquete"></p>
                                                        </div>
                                                    </div> 

                                                    <div class="col-md-3" v-if="descripcionPaquete!=''">
                                                        <div class="form-group">
                                                            <label style="color:#2271b3;" for=""><strong> Descripcion del paquete </strong></label>
                                                            <p v-text="descripcionPaquete"></p>
                                                        </div>
                                                    </div> 

                                                    <div class="col-md-3" v-if="costoPaquete!=''">
                                                        <div class="form-group">
                                                            <label style="color:#2271b3;" for=""><strong> Costo del paquete </strong></label>
                                                            <p v-text="'$'+formatNumber(costoPaquete)"></p>
                                                        </div>
                                                    </div> 

                                                    <div class="col-md-12" >
                                                        <h6></h6>
                                                    </div>

                                                    <div class="col-md-2" >
                                                        <h6></h6>
                                                    </div>

                                                    <div class="col-md-3" v-if="precioVenta!=''">
                                                        <div class="form-group">
                                                            <h5 style="color:#2271b3;" for=""><strong> Valor Total de la Casa: </strong></h5>
                                                        </div>
                                                    </div> 
                                                    <div class="col-md-3" v-if="precioVenta!=''">
                                                        <div class="form-group">
                                                            <h5 v-text="'$'+formatNumber(precioVenta)"></h5>
                                                        </div>
                                                    </div> 

                                                    <div class="col-md-12" ><hr>
                                                        <h6></h6>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <center> <h4>Credito</h4> </center>
                                                        </div>
                                                    </div>  

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                        <label style="color:#2271b3;" for=""><strong>Tipo de credito </strong><span style="color:red;" v-show="tipo_credito==0">(*)</span></label>
                                                        <p v-text="tipo_credito"></p>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                        <label style="color:#2271b3;" for=""><strong>Institucion financiera </strong><span style="color:red;" v-show="inst_financiera==0">(*)</span></label>
                                                        <p v-text="inst_financiera"></p>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3" v-if="monto_credito!=0">
                                                        <div class="form-group">
                                                            <h6 style="color:#2271b3;" for=""><strong> Credito Solicitado: </strong></h6>
                                                            <h6 v-text="'$'+formatNumber(monto_credito)"></h6>
                                                        </div>
                                                    </div> 

                                                     <div class="col-md-2" v-if="inst_financiera!=''">
                                                        <div class="form-group">
                                                        <label style="color:#2271b3;" for=""><strong>Plazo (años) </strong><span style="color:red;" v-show="plazo_credito==0">(*)</span></label>
                                                            <p v-text="plazo_credito"></p>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <h6></h6>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3" v-if="inst_financiera!='' && listado==3">
                                                        <div class="form-group">
                                                        <label for="">Comisión por apertura</label>
                                                            <input type="text" pattern="\d*" v-model="comision_apertura" maxlength="9" v-on:keypress="isNumber($event)" class="form-control" >
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3" >
                                                        <div class="form-group">
                                                            <h6 style="color:#2271b3;" for=""><strong> Comisión por apertura </strong></h6>
                                                            <h6 v-text="'$'+formatNumber(comision_apertura)"></h6>
                                                        </div>
                                                    </div> 

                                                    <div class="col-md-3" v-if="inst_financiera!=''"><hr>
                                                        <div class="form-group">
                                                            <h5 style="color:#2271b3;" for=""><strong> Credito Neto {{inst_financiera}}: </strong></h5>
                                                        </div>
                                                    </div> 
                                                    <div class="col-md-3" v-if="inst_financiera!=''"><hr>
                                                        <div class="form-group">
                                                            <h5><strong>${{ formatNumber(credito_neto=totalCreditoSolic)}}</strong></h5>
                                                        </div>
                                                    </div> 

                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <h6></h6>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3" v-if="inst_financiera!=''&& listado==3">
                                                        <div class="form-group">
                                                        <label for="">Investigación</label>
                                                            <input type="text" pattern="\d*" v-model="investigacion" maxlength="9" v-on:keypress="isNumber($event)" class="form-control" >
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <h6 style="color:#2271b3;" for=""><strong> Investigación </strong></h6>
                                                            <h6 v-text="'$'+formatNumber(investigacion)"></h6>
                                                        </div>
                                                    </div> 

                                                    <div class="col-md-3" v-if="tipo_credito=='Alia2' && listado==3 || tipo_credito=='Respalda2' && listado==3">
                                                        <div class="form-group">
                                                        <label for="">Fovissste</label>
                                                            <input type="text" pattern="\d*" v-model="fovissste" maxlength="9" v-on:keypress="isNumber($event)" class="form-control" >
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3" v-if="tipo_credito=='Cofinavit' && listado==3">
                                                        <div class="form-group">
                                                        <label for="">Infonavit</label>
                                                            <input type="text" pattern="\d*" v-model="infonavit" maxlength="9" v-on:keypress="isNumber($event)" class="form-control" >
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3" v-if="fovissste!=0">
                                                        <div class="form-group">
                                                            <h6 style="color:#2271b3;" for=""><strong> Fovissste </strong></h6>
                                                            <h6 v-text="'$'+formatNumber(fovissste)"></h6>
                                                        </div>
                                                    </div> 

                                                    <div class="col-md-3" v-if="infonavit!=0">
                                                        <div class="form-group">
                                                            <h6 style="color:#2271b3;" for=""><strong> Infonavit </strong></h6>
                                                            <h6 v-text="'$'+formatNumber(infonavit)"></h6>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <h6></h6>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3" v-if="inst_financiera!=''&& listado==3">
                                                        <div class="form-group">
                                                        <label for="">Avaluo por parte del banco</label>
                                                            <input type="text" pattern="\d*" v-model="avaluo" maxlength="9" v-on:keypress="isNumber($event)" class="form-control" >
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3" > 
                                                        <div class="form-group">
                                                            <h6 style="color:#2271b3;" for=""><strong> Avaluo banco</strong></h6>
                                                            <h6 v-text="'$'+formatNumber(avaluo)"></h6>
                                                        </div>
                                                    </div> 

                                                    <div class="col-md-3" v-if="inst_financiera!=''"><hr>
                                                        <div class="form-group">
                                                            <h5 style="color:#2271b3;" for=""><strong> Monto Neto Credito: </strong></h5>
                                                        </div>
                                                    </div> 
                                                    <div class="col-md-3" v-if="inst_financiera!=''"><hr>
                                                        <div class="form-group">
                                                            <h5><strong>${{ formatNumber(monto_total_credito=netoCredito)}}</strong></h5>
                                                        </div>
                                                    </div> 

                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <h6></h6>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3" v-if="inst_financiera!='' && listado==3">
                                                        <div class="form-group">
                                                        <label for="">Prima unica</label>
                                                            <input type="text" pattern="\d*" v-model="prima_unica" maxlength="9" v-on:keypress="isNumber($event)" class="form-control" >
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3" >
                                                        <div class="form-group">
                                                            <h6 style="color:#2271b3;" for=""><strong> Prima unica </strong></h6>
                                                            <h6 v-text="'$'+formatNumber(prima_unica)"></h6>
                                                        </div>
                                                    </div> 

                                                    <div class="col-md-3" v-if="inst_financiera!=''">
                                                        <div class="form-group">
                                                            <h4 style="color:#2271b3;" for=""><strong> Total a pagar: </strong></h4>
                                                        </div>
                                                    </div> 
                                                    <div class="col-md-3" v-if="inst_financiera!=''">
                                                        <div class="form-group">
                                                            <h4><strong>${{ formatNumber(total_pagar=totalPagar)}}</strong></h4>
                                                        </div>
                                                    </div> 


                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <h6></h6>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3" v-if="inst_financiera!=''&& listado==3">
                                                        <div class="form-group">
                                                        <label for="">Gastos de escrituración</label>
                                                            <input type="text" pattern="\d*" v-model="escrituras" maxlength="9" v-on:keypress="isNumber($event)" class="form-control" >
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <h6 style="color:#2271b3;" for=""><strong> Gastos de escrituración </strong></h6>
                                                            <h6 v-text="'$'+formatNumber(escrituras)"></h6>
                                                        </div>
                                                    </div> 

                                                    <div class="col-md-3" v-if="inst_financiera!='' && listado==3" ><hr>
                                                        <div class="form-group">
                                                        <label for="">Avaluo por parte del cliente</label>
                                                            <input type="text" pattern="\d*" v-model="avaluo_cliente" maxlength="9" v-on:keypress="isNumber($event)" class="form-control" >
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3" v-if="avaluo_cliente"><hr>
                                                        <div class="form-group">
                                                            <h6 style="color:#2271b3;" for=""><strong> Avaluo cliente</strong></h6>
                                                            <h6 v-text="'$'+formatNumber(avaluo_cliente)"></h6>
                                                        </div>
                                                    </div> 

                                                    <div class="col-md-12"><hr>
                                                        <div class="form-group">
                                                            <h6></h6>
                                                        </div>
                                                    </div>

                                                     <div class="col-md-3" >
                                                        <h6></h6>
                                                    </div>

                                                    <div class="col-md-3" v-if="inst_financiera!=''">
                                                        <div class="form-group">
                                                            <h4 style="color:#2271b3;" for=""><strong> Enganche total: </strong></h4>
                                                        </div>
                                                    </div> 
                                                    <div class="col-md-3" v-if="inst_financiera!=''">
                                                        <div class="form-group">
                                                            <h4><strong>${{ formatNumber(enganche_total=totalEnganche)}}</strong></h4>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12"><hr>
                                                        <div class="form-group">
                                                            <h6></h6>
                                                        </div>
                                                    </div>

                                                     <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="">Fecha del contrato</label>
                                                            <input :readonly="listado==4" type="date" v-model="fecha_contrato" class="form-control" >
                                                        </div>
                                                    </div> 

                                                    <div class="col-md-9">
                                                        <div class="form-group">
                                                            <label for="">Observaciones <span style="color:red;" v-show="observacion==''">(*)</span></label>
                                                            <textarea :readonly="listado==4" rows="3" cols="30" v-model="observacion" class="form-control" placeholder="Observaciones"></textarea>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12"><hr>
                                                        <div class="form-group">
                                                            <h6></h6>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6"> <hr>
                                                        <div class="form-group">
                                                            <center> <h4>Pagos</h4> </center>
                                                        </div>
                                                    </div> 

                                                    <div class="col-md-6" v-if="listado==3"> <hr>
                                                        <div class="form-group">
                                                            <center> <h6>Restante: </h6> </center>
                                                            <center> <h6><strong>${{ formatNumber(restante=totalRestante)}}</strong></h6> </center>
                                                        </div>
                                                    </div> 

                                                    <div class="col-md-3" v-if="inst_financiera!='' && listado==3">
                                                        <div class="form-group">
                                                        <label for="">Monto pago</label>
                                                            <input type="text" pattern="\d*" v-model="monto_pago" maxlength="9" v-on:keypress="isNumber($event)" class="form-control" >
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-2" v-if="monto_pago!='' && listado==3">
                                                        <div class="form-group">
                                                            <h6 style="color:#2271b3;" for=""><strong> Monto pago </strong></h6>
                                                            <h6><strong>${{ formatNumber(monto_pago)}}</strong></h6>
                                                        </div>
                                                    </div> 
                                                    
                                                    <div class="col-md-3" v-if="monto_pago!='' && listado==3">
                                                        <div class="form-group">
                                                             <label for="">Fecha del pago</label>
                                                            <input type="date" v-model="fecha_pago" class="form-control" >
                                                        </div>
                                                    </div> 

                                                    <div class="col-md-1" v-if="monto_pago!='' && listado==3">
                                                        <div class="form-group" v-if="restante>0">
                                                            <button @click="agregarPago()" class="btn btn-success form-control btnagregar"><i class="icon-plus"></i></button>
                                                        </div>
                                                    </div>

                                                     <div class="col-md-12" v-if="listado==4"><hr>
                                                        <div class="form-group">
                                                            <h6></h6>
                                                        </div>
                                                    </div>
                                                

                        <div class="col-md-6">
                            <div class="form-group row" v-if="arrayPagos.length">
                                <div class="table-responsive col-md-12">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th v-if="listado==3">Opciones</th>
                                                <th># Pago</th>
                                                <th>Fecha de pago</th>
                                                <th>Monto</th>
                                            </tr>
                                        </thead>
                                        <tbody >
                                            <tr v-for="(pago,index) in arrayPagos" :key="pago.fecha_pago">
                                                <td v-if="listado==3">
                                                    <button @click="eliminarPago(index)" type="button" class="btn btn-danger btn-sm">
                                                        <i class="icon-close"></i>
                                                    </button>
                                                </td>
                                                <td v-text="'Pago no. ' + parseInt(index+1)"></td>
                                                <td v-text="this.moment(pago.fecha_pago).locale('es').format('DD/MMM/YYYY')"></td>
                                                
                                                <td>
                                                    {{ pago.monto_pago | currency}}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>                                                
                                                    
                                            </div>
                                    </div>
                                </div>
                                <!--- Botones y div para errores -->
                                <div class="card-body">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <!-- Div para mostrar los errores que mande validerFraccionamiento -->
                                                <div v-show="errorContrato" class="form-group row div-error">
                                                    <div class="text-center text-error">
                                                        <div v-for="error in errorMostrarMsjContrato" :key="error" v-text="error">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12" style="position:absolute;">
                                                <button type="button" class="btn btn-secondary" @click="cerrarDetalle()"> Cerrar </button>
                                                <button type="button" v-if="listado==3" class="btn btn-primary" @click="crearContrato()"> Enviar </button>
                                            </div>
                                             <div style="text-align: right;">
                                                <a class="btn btn-info btn-sm" v-if="listado==4" target="_blank" v-bind:href="'/contratoCompraVenta/pdf/'+id">Imprimir contrato</a>
                                                <a class="btn btn-primary btn-sm" v-if="listado==4" target="_blank" v-bind:href="'/pagareContrato/pdf/'+id">Imprimir pagares</a>
                                                <a class="btn btn-success btn-sm" v-if="listado==4" target="_blank" v-bind:href="'/cartaServicios/pdf/'+id">Carta de servicios</a>
                                                <a class="btn btn-warning btn-sm" v-if="listado==4" target="_blank" v-bind:href="'/serviciosTelecom/pdf/'+id">Servicios de telecomunición</a>
                                                <a class="btn btn-danger btn-sm" v-if="listado==4" v-bind:href="'/descargarReglamento/contrato/'+id">Reglamento de la etapa</a>
                                            </div>
                                          
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </template>

                 
                    
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
        props:{
            rolId:{type: String}
        },
        data(){
            return{
                id:0,
                proceso:false,

                arraySimulaciones:[],
                arrayContratos:[],
                arrayDatosSimulacion:[],
                arrayFraccionamientos: [],
                arryaEmpresas:[],
                arrayPagos:[],

                /// variables datos del prospecto //
                    nombre:'',
                    apellidos:'',
                    sexo:'',
                    telefono : '',
                    celular : '',
                    email:'',
                    estado: '',
                    ciudad:'',
                    cp:'',
                    colonia:'',
                    direccion: '',
                    fecha_nac: '',
                    curp:'',
                    rfc:'',
                    homoclave: '',
                    nss:'',
                    nacionalidad:0,
                    
                    tipo_economia: 0,
                    empresa: '',
                    puesto:'',
                    e_civil: 0,
                    email_inst:'',
                    direccion_empresa:'',
                    cp_empresa:'',
                    colonia_empresa:'',
                    estado_empresa:'',
                    ciudad_empresa:'',
                    dep_economicos:'',
                    telefono_empresa:'',
                    ext_empresa:'',

                // variables coacreditado //
                    conyugeNom:'',
                    coacreditado: 0,
                    nombre_coa:'',
                    parentesco_coa:'',
                    apellidos_coa:'',
                    telefono_coa : '',
                    celular_coa : '',
                    email_coa:'',
                    email_institucional_coa:'',
                    nss_coa:'',
                    sexo_coa:'',
                    fecha_nac_coa: '',
                    curp_coa:'',
                    rfc_coa:'',
                    homoclave_coa: '',
                    e_civil_coa: 0,
                    tipo_casa_coa:0,
                    estado_coa: '',
                    ciudad_coa: '',
                    cp_coa:'',
                    nacionalidad_coa:0,
                    direccion_coa:'',
                    colonia_coa:'',
                    empresa_coa:'',
                    direccion_empresa_coa:'',
                    cp_empresa_coa:'',
                    colonia_empresa_coa:'',
                    estado_empresa_coa:'',
                    ciudad_empresa_coa:'',
                    telefono_empresa_coa:'',
                    ext_empresa_coa:'',


                /// variables referencias //
                    nombre_referencia1:'',
                    telefono_referencia1:'',
                    celular_referencia1:'',
                    nombre_referencia2:'',
                    telefono_referencia2:'',
                    celular_referencia2:'',

                /// variables datos economicos //
                    proyecto_interes_id: 0,
                    proyecto:'',
                    etapa: '',
                    manzana: '',
                    lote: '',
                    num_lote:'',
                    modelo: '',
                    superficie: '',
                    precioBase: 0,
                    precioExcedente: 0,
                    precioVenta: 0,
                    precioObraExtra:0,
                    promocion: '',
                    descripcionPromo: '',
                    descuentoPromo: 0,
                    paquete_id: 0,
                    descripcionPaquete: '',
                    costoPaquete: 0,
                    paquete:'',
                    terreno_tam_excedente:0,
                    tipo_credito:'',
                    inst_financiera:'',
                    plazo_credito:'',
                    monto_credito:'',
                    lote_id:'',

                    /// Credito datos extra //
                        comision_apertura:'0',
                        investigacion:'0',
                        avaluo:'0',
                        prima_unica:'0',
                        escrituras:'0',
                        credito_neto:'0',
                        avaluo_cliente:'0',
                        total_pagar:0,
                        enganche_total:0,
                        infonavit:'0',
                        fovissste:'0',
                        monto_total_credito:0,
                        observacion:'',
             
                prospecto_id:0,
                restante:0,
                monto_pago:0,
                fecha_pago:'',
                fecha_contrato:'',
                arrayEstados: [],
                arrayCiudadesCoa: [],
                arrayCiudades:[],
                arrayColoniasCoa: [],
                arrayColonias: [],
                arrayPaquetes: [],
                arrayDatosPaquetes: [],

                errorContrato : 0,
                errorMostrarMsjContrato : [],

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
                offset2 : 3,
                criterio : 'personal.nombre', 
                buscar : '',
                b_etapa: '',
                b_manzana: '',
                b_lote: '',
                criterio2 : 'personal.nombre', 
                buscar2 : '',
                b_etapa2: '',
                b_manzana2: '',
                b_lote2: '',
                listado:0,
               
            }
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },

            isActived2: function(){
                return this.pagination2.current_page;
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

            //Calculos 
            totalRestante: function(){
                var totalRestante =0.0;
                for(var i=0;i<this.arrayPagos.length;i++){
                    totalRestante += parseFloat(this.arrayPagos[i].monto_pago)
                }
                totalRestante = this.enganche_total - totalRestante;
                return totalRestante;
            },

            totalCreditoSolic: function(){
                var total_credito =0;
                    total_credito = parseFloat(this.monto_credito) - parseInt(this.comision_apertura)-parseInt(this.investigacion)+parseInt(this.avaluo)-parseInt(this.prima_unica)-parseInt(this.escrituras); 
                return total_credito;
            },

            netoCredito: function(){
                var neto_credito =0;
                    neto_credito = parseFloat(this.infonavit) + parseInt(this.fovissste) + parseInt(this.credito_neto); 
                return neto_credito;
            },

            totalPagar: function(){
                let me = this;
                var total_pago =0;
                    total_pago =parseInt(this.precioVenta) - parseFloat(this.monto_total_credito); 
                return total_pago;
            },

            totalEnganche: function(){
                let me = this;
                var total_engache =0;
                    total_engache =parseInt(this.total_pagar) + parseFloat(this.avaluo_cliente); 
                    me.restante = total_engache;
                return total_engache;
            },


        },
       
        methods : {
            listarSimulaciones(page, buscar, b_etapa, b_manzana,b_lote,criterio){
                let me = this;
                var url = '/creditos_aprobados?page=' + page + '&buscar=' + buscar + '&b_etapa=' +b_etapa+ '&b_manzana=' + b_manzana + '&b_lote='+ b_lote + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arraySimulaciones = respuesta.creditos.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                })
            },
            listarContratos(page, buscar, b_etapa, b_manzana,b_lote,criterio){
                let me = this;
                var url = '/contratos?page=' + page + '&buscar=' + buscar + '&b_etapa=' +b_etapa+ '&b_manzana=' + b_manzana + '&b_lote='+ b_lote + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayContratos = respuesta.contratos.data;
                    me.pagination2 = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                })
            },
            obtenerDatosCredito(buscar){
                let me = this;
                var url = '/credito/datos_credito?folio=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayDatosSimulacion = respuesta.creditos;
                    me.mostrarDetalle(me.arrayDatosSimulacion[0]);
                })
                .catch(function (error) {
                    console.log(error);
                })
            },
            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
            cambiarPagina(page, buscar, b_etapa, b_manzana,b_lote,criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarSimulaciones(page,buscar, b_etapa, b_manzana,b_lote,criterio);
            },

            cambiarPagina2(page, buscar, b_etapa, b_manzana,b_lote,criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination2.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarContratos(page,buscar, b_etapa, b_manzana,b_lote,criterio);
            },
            listarPagos(id){
                let me = this;
                me.arrayPagos=[];
                var url = '/contratos/pagos?contrato_id=' + id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPagos = respuesta.pagos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectFraccionamientos(){
                let me = this;
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
            selectEmpresas(){
                let me = this;
                me.arryaEmpresas=[];
                
                var url = '/select_empresas';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arryaEmpresas = respuesta.empresas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectPaquetes(etapa){
                let me = this;
                me.arrayPaquetes=[];
                var url = '/select_paquetes?buscar=' + etapa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                     me.arrayPaquetes = respuesta.paquetes;
                    
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectEstados(){
                let me = this;
                me.arrayEstados=[];
                
                var url = '/select_estados';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEstados = respuesta.estados;
                    
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectCiudades(estado,coacreditado){
                let me = this;
                var url = '/select_ciudades?buscar='+estado;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    if(coacreditado==1){
                        me.arrayCiudadesCoa=[];
                        me.arrayCiudadesCoa = respuesta.ciudades;
                    }
                    else{
                        me.arrayCiudades=[];
                        me.arrayCiudades = respuesta.ciudades;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectColonias(cp,coacreditado){
                let me = this;
                me.arrayColonias=[];
                var url = '/select_colonias?buscar=' + cp;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    if(coacreditado==1){
                        me.arrayColoniasCoa=[];
                        me.arrayColoniasCoa = respuesta.colonias;
                    }
                    else{
                        me.arrayColonias=[];
                        me.arrayColonias = respuesta.colonias;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getDatosEmpresa(nombre,coacreditado){
                let me = this;
                var arrayDatosEmpresa=[];
                var url = '/empresa/datos?nombre=' + nombre;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    arrayDatosEmpresa = respuesta.empresas;
                    if(coacreditado==1){
                        me.direccion_empresa_coa=arrayDatosEmpresa[0].direccion;
                        me.cp_empresa_coa=arrayDatosEmpresa[0].cp;
                        me.colonia_empresa_coa=arrayDatosEmpresa[0].colonia;
                        me.estado_empresa_coa=arrayDatosEmpresa[0].estado;
                        me.ciudad_empresa_coa=arrayDatosEmpresa[0].ciudad;
                        me.telefono_empresa_coa=arrayDatosEmpresa[0].telefono;
                        me.ext_empresa_coa=arrayDatosEmpresa[0].ext;
                    }
                    else{
                        me.direccion_empresa=arrayDatosEmpresa[0].direccion;
                        me.cp_empresa=arrayDatosEmpresa[0].cp;
                        me.colonia_empresa=arrayDatosEmpresa[0].colonia;
                        me.estado_empresa=arrayDatosEmpresa[0].estado;
                        me.ciudad_empresa=arrayDatosEmpresa[0].ciudad;
                        me.telefono_empresa=arrayDatosEmpresa[0].telefono;
                        me.ext_empresa=arrayDatosEmpresa[0].ext;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            datosPaquetes(paquete){
                let me = this;
                me.precioVenta = me.precioVenta - me.costoPaquete;
                if(paquete!=0){
                me.arrayDatosPaquetes=[];
                var url = '/select_datos_paquetes?buscar=' + paquete;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                     me.arrayDatosPaquetes = respuesta.datos_paquetes;
                     me.descripcionPaquete = me.arrayDatosPaquetes[0]['descripcion'];
                     me.costoPaquete = me.arrayDatosPaquetes[0]['costo'];
                     me.paquete = me.arrayDatosPaquetes[0]['nombre'];

                    me.precioVenta = me.precioVenta + me.costoPaquete;
                })
                .catch(function (error) {
                    console.log(error);
                });
                }
                else{
                    me.descripcionPaquete='';
                    me.costoPaquete=0;
                }
            },

            mostrarDetalle(data = []){
                this.prospecto_id = data['prospecto_id'];
                this.id= data['id'];
                this.nombre = data['nombre'];
                this.apellidos = data['apellidos'];
                this.sexo = data['sexo'];
                this.telefono = data['telefono'];
                this.celular = data['celular'];
                this.email = data['email'];
                this.direccion = data['direccion'];
                this.cp = data['cp'];
                this.colonia = data['colonia'];
                this.estado = data['estado'];
                this.ciudad = data['ciudad'];
                this.fecha_nac = data['f_nacimiento'];
                this.curp = data['curp'];
                this.rfc = data['rfc'];
                this.homoclave = data['homoclave'];
                this.nss = data['nss'];
                this.tipo_economia = data['tipo_economia'];
                this.empresa = data['empresa'];
                this.puesto = data['puesto'];
                this.email_inst = data['email_institucional'];
                this.tipo_casa = data['tipo_casa'];
                this.e_civil = data['edo_civil'];
                this.dep_economicos = data['num_dep_economicos']
                this.status = data['status'];
                this.lote_id = data['lote_id'];
                
                this.nombre_referencia1 = data['nombre_primera_ref'];
                this.telefono_referencia1 = data['telefono_primera_ref'];
                this.celular_referencia1 = data['celular_primera_ref'];
                this.nombre_referencia2 = data['nombre_segunda_ref'];
                this.telefono_referencia2 = data['telefono_segunda_ref'];
                this.celular_referencia2 = data['celular_segunda_ref'];

                this.coacreditado = data['coacreditado'];
                this.nombre_coa = data['nombre_coa'];
                this.apellidos_coa = data['apellidos_coa'];
                this.conyugeNom = this.nombre_coa + ' ' + this.apellidos_coa;
                this.fecha_nac_coa = data['f_nacimiento_coa'];
                this.rfc_coa = data['rfc_coa'];
                this.homoclave_coa = data['homoclave_coa'];
                this.curp_coa = data['curp_coa'];
                this.nss_coa = data['nss_coa'];
                this.nacionalidad_coa = data['nacionalidad_coa'];
                this.direccion_coa = data['direccion_coa'];
                this.cp_coa = data['cp_coa'];
                this.colonia_coa = data['colonia_coa'];
                this.estado_coa = data['estado_coa'];
                this.ciudad_coa = data['ciudad_coa'];
                this.celular_coa = data['celular_coa'];
                this.telefono_coa = data['telefono_coa'];
                this.email_coa = data['email_coa'];
                this.email_institucional_coa = data['email_institucional_coa'];
                this.empresa_coa = data['empresa_coa'];
                this.parentesco_coa = data['parentesco_coa'];


                this.proyecto = data['proyecto'];
                this.etapa = data['etapa'];
                this.manzana = data['manzana'];
                this.lote = data['num_lote'];
                this.modelo = data['modelo'];
                this.superficie = data['superficie'];
                this.terreno_tam_excedente = data['terreno_excedente'];
                this.precioBase = data['precio_base'];
                this.precioExcedente = data['precio_terreno_excedente'];
                this.precioObraExtra = data['precio_obra_extra'];
                this.precioVenta = data['precio_venta'];
                this.promocion = data['promocion'];
                this.descripcionPromo = data['descripcion_promocion'];
                this.descuentoPromo = data['descuento_promocion'];
                this.paquete = data['paquete'];
                this.descripcionPaquete = data['descripcion_paquete'];
                this.costoPaquete = data['costo_paquete'];
                this.tipo_credito = data['tipo_credito'];
                this.inst_financiera = data['institucion'];
                this.plazo_credito =data['plazo'];
                this.monto_credito = data['credito_solic'];
               

                this.listado=3;

                this.selectEstados();
                this.selectCiudades(this.estado,0);
                this.selectCiudades(this.estado_coa,1);
                this.selectColonias(this.cp,0);
                this.selectColonias(this.cp_coa,1);
                this.selectPaquetes(this.etapa);
                this.selectEmpresas();

                this.getDatosEmpresa(this.empresa,0);
                this.getDatosEmpresa(this.empresa_coa,1);
            },

            mostrarCreditosAprobados(){
                this.listarSimulaciones(1,this.buscar,this.b_etapa,this.b_manzana,this.b_lote,this.criterio);
                this.listado = 1;
            },

            cerrarDetalle(){
                this.limpiarDatos();
                this.listado = 0;
            },

            verContrato(data = []){
                this.prospecto_id = data['prospecto_id'];
                this.id= data['id'];
                this.nombre = data['nombre'];
                this.apellidos = data['apellidos'];
                this.sexo = data['sexo'];
                this.telefono = data['telefono'];
                this.celular = data['celular'];
                this.email = data['email'];
                this.direccion = data['direccion'];
                this.cp = data['cp'];
                this.colonia = data['colonia'];
                this.estado = data['estado'];
                this.ciudad = data['ciudad'];
                this.fecha_nac = data['f_nacimiento'];
                this.curp = data['curp'];
                this.rfc = data['rfc'];
                this.homoclave = data['homoclave'];
                this.nss = data['nss'];
                this.tipo_economia = data['tipo_economia'];
                this.empresa = data['empresa'];
                this.direccion_empresa = data['direccion_empresa'];
                this.cp_empresa = data['cp_empresa'];
                this.colonia_empresa = data['colonia_empresa'];
                this.estado_empresa = data['estado_empresa'];
                this.ciudad_empresa = data['ciudad_empresa'];
                this.telefono_empresa = data['telefono_empresa'];
                this.ext_empresa = data['ext_empresa'];
                this.puesto = data['puesto'];
                this.email_inst = data['email_institucional'];
                this.tipo_casa = data['tipo_casa'];
                this.e_civil = data['edo_civil'];
                this.dep_economicos = data['num_dep_economicos']
                this.status = data['status'];
                this.lote_id = data['lote_id'];
                
                this.nombre_referencia1 = data['nombre_primera_ref'];
                this.telefono_referencia1 = data['telefono_primera_ref'];
                this.celular_referencia1 = data['celular_primera_ref'];
                this.nombre_referencia2 = data['nombre_segunda_ref'];
                this.telefono_referencia2 = data['telefono_segunda_ref'];
                this.celular_referencia2 = data['celular_segunda_ref'];

                this.coacreditado = data['coacreditado'];
                this.nombre_coa = data['nombre_coa'];
                this.apellidos_coa = data['apellidos_coa'];
                this.conyugeNom = this.nombre_coa + ' ' + this.apellidos_coa;
                this.fecha_nac_coa = data['f_nacimiento_coa'];
                this.rfc_coa = data['rfc_coa'];
                this.homoclave_coa = data['homoclave_coa'];
                this.curp_coa = data['curp_coa'];
                this.nss_coa = data['nss_coa'];
                this.nacionalidad_coa = data['nacionalidad_coa'];
                this.direccion_coa = data['direccion_coa'];
                this.cp_coa = data['cp_coa'];
                this.colonia_coa = data['colonia_coa'];
                this.estado_coa = data['estado_coa'];
                this.ciudad_coa = data['ciudad_coa'];
                this.celular_coa = data['celular_coa'];
                this.telefono_coa = data['telefono_coa'];
                this.email_coa = data['email_coa'];
                this.email_institucional_coa = data['email_institucional_coa'];
                this.empresa_coa = data['empresa_coa'];
                this.parentesco_coa = data['parentesco_coa'];
                this.direccion_empresa_coa = data['direccion_empresa_coa'];
                this.cp_empresa_coa = data['cp_empresa_coa'];
                this.colonia_empresa_coa = data['colonia_empresa_coa'];
                this.estado_empresa_coa = data['estado_empresa_coa'];
                this.ciudad_empresa_coa = data['ciudad_empresa_coa'];
                this.telefono_empresa_coa = data['telefono_empresa_coa']
                this.ext_empresa_coa = data['ext_empresa_coa'];


                this.proyecto = data['proyecto'];
                this.etapa = data['etapa'];
                this.manzana = data['manzana'];
                this.lote = data['num_lote'];
                this.modelo = data['modelo'];
                this.superficie = data['superficie'];
                this.terreno_tam_excedente = data['terreno_excedente'];
                this.precioBase = data['precio_base'];
                this.precioExcedente = data['precio_terreno_excedente'];
                this.precioObraExtra = data['precio_obra_extra'];
                this.precioVenta = data['precio_venta'];
                this.promocion = data['promocion'];
                this.descripcionPromo = data['descripcion_promocion'];
                this.descuentoPromo = data['descuento_promocion'];
                this.paquete = data['paquete'];
                this.descripcionPaquete = data['descripcion_paquete'];
                this.costoPaquete = data['costo_paquete'];
                this.tipo_credito = data['tipo_credito'];
                this.inst_financiera = data['institucion'];
                this.plazo_credito =data['plazo'];
                this.monto_credito = data['credito_solic'];

                this.infonavit = data['infonavit'];
                this.fovissste = data['fovisste'];
                this.comision_apertura = data['comision_apertura'];
                this.investigacion = data['investigacion'];
                this.avaluo = data['avaluo'];
                this.prima_unica = data['prima_unica'];
                this.escrituras = data['escrituras'];
                this.credito_neto = data['credito_neto'];
                this.avaluo_cliente = data['avaluo_cliente'];
                this.fecha_contrato = data['fecha'];
                this.total_pagar = data['total_pagar'];
                this.monto_total_credito = data['monto_total_credito'];
                this.enganche_total = data['enganche_total'];
           
               
                this.listarPagos(this.id);
                this.listado=4;
            },

            agregarPago(){
                let me = this;
                if(me.monto_pago == 0 || me.monto_pago=='' || me.fecha_pago==''){

                }else{
                    if(me.monto_pago > me.restante){
                         swal({
                        type: 'error',
                        title: 'Error',
                        text: 'El monto supera al restante',
                        })
                    }else{
                    me.arrayPagos.push({
                    monto_pago: me.monto_pago,
                    fecha_pago: me.fecha_pago,
                    });
                    me.fecha_pago = '';
                    me.monto_pago = 0;
                    }
                }

            },
            eliminarPago(index){
                let me = this;
                me.arrayPagos.splice(index,1);
            },

            validarRegistro(){
                this.errorContrato=0;
                this.errorMostrarMsjContrato=[];

                //////////////// VALIDACIONES /////////////////
                /************* Datos personales del cliente *******************/
                    if(this.direccion=='' || this.cp=='' || this.colonia=="" || !this.direccion || !this.cp || !this.colonia) 
                        this.errorMostrarMsjContrato.push("Completar el domicilio del cliente");
                    if(this.ciudad=='' || this.estado=='' || !this.ciudad || !this.estado) 
                        this.errorMostrarMsjContrato.push("Seleccionar Ciudad y estado del cliente");
                    if(this.fecha_nac=='') 
                        this.errorMostrarMsjContrato.push("Ingresar fecha de nacimiento del cliente");
                    if(this.curp=='' || this.curp.length <18) 
                        this.errorMostrarMsjContrato.push("La Curp no es valida");
                    if(this.rfc=='' || this.rfc.length <10 || this.homoclave=='' || this.homoclave.length<3) 
                        this.errorMostrarMsjContrato.push("El RFC no es valido");
                    if(this.nss=='' || this.nss.length <11) 
                        this.errorMostrarMsjContrato.push("El NSS no es valido");
                /************* Datos del trabajo *******************/
                    if(this.tipo_economia=='' || this.empresa=='' || this.puesto=='' || this.direccion_empresa==''
                        || this.cp_empresa=='' || this.colonia_empresa=='' || this.estado_empresa=='' 
                        || this.ciudad_empresa=='' || this.telefono_empresa=='' || !this.empresa ) 
                        this.errorMostrarMsjContrato.push("Completar datos del lugar de trabajo");
                    if(this.e_civil=='' || !this.e_civil) 
                        this.errorMostrarMsjContrato.push("Seleccionar estado civil del cliente");
                    if(this.dep_economicos=='' || !this.dep_economicos) 
                        this.errorMostrarMsjContrato.push("Ingresar el numero de dependientes economicos");
                /************* Datos del coacreditado *******************/
                    if(this.coacreditado == true){
                        if(this.nombre_coa=='' || this.apellidos_coa=='') 
                            this.errorMostrarMsjContrato.push("Ingresar el nombre del cónyuge");
                        if(this.parentesco_coa=='') 
                            this.errorMostrarMsjContrato.push("Ingresar el parentesco del cónyuge");
                        if(this.fecha_nac_coa=='') 
                            this.errorMostrarMsjContrato.push("Ingresar la fecha de nacimiento del cónyuge");
                        if(this.rfc_coa=='' || this.rfc_coa.length <10 || this.homoclave_coa=='' || this.homoclave_coa.length<3) 
                            this.errorMostrarMsjContrato.push("El RFC del cónyuge no es valido");
                        if(this.curp_coa=='' || this.curp_coa.length <18) 
                            this.errorMostrarMsjContrato.push("La Curp del cónyuge no es valida");
                        if(this.nss_coa=='' || this.nss_coa.length <11) 
                            this.errorMostrarMsjContrato.push("El NSS no es valido");
                        if(this.direccion_coa=='' || this.cp_coa=='' || this.colonia_coa=="") 
                            this.errorMostrarMsjContrato.push("Completar el domicilio del cónyuge");
                        if(this.ciudad_coa=='' || this.estado_coa=='') 
                            this.errorMostrarMsjContrato.push("Seleccionar Ciudad y estado del cónyuge");
                        if(this.celular_coa=='' || this.telefono_coa=='' || this.email_coa=='') 
                            this.errorMostrarMsjContrato.push("Completar medios de contacto del cónyuge");
                        /************* Datos del trabajo *******************/
                        if(this.empresa_coa=='') 
                            this.errorMostrarMsjContrato.push("Completar datos del lugar de trabajo para el cónyuge");
                    }
                /************* Referencias personales *******************/
                    if(this.nombre_referencia1=='' || this.telefono_referencia1=='' || this.celular_referencia1=='' 
                        || this.nombre_referencia2=='' || this.telefono_referencia2=='' || this.celular_referencia2==''
                        ||!this.nombre_referencia1 || !this.telefono_referencia1 || !this.celular_referencia1 
                        || !this.nombre_referencia2 || !this.telefono_referencia2 || !this.celular_referencia2) 
                            this.errorMostrarMsjContrato.push("Completar datos de referencias personales");
                /************* Datos economicos *******************/
                if(this.comision_apertura=='' || this.investigacion == '' || this.avaluo == '' || this.prima_unica == '' || this.escrituras == '' || this.fovissste == '' || this.infonavit == '' || this.avaluo_cliente == '') 
                    this.errorMostrarMsjContrato.push("Verificar datos economicos (No deben quedar campos vacios)");
                if(this.restante>0) 
                    this.errorMostrarMsjContrato.push("Ingresar pago para el monto restante");
                if(this.fecha_contrato=='') 
                    this.errorMostrarMsjContrato.push("Ingresar fecha del contrato");
               
                if(this.errorMostrarMsjContrato.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorContrato = 1;

                return this.errorContrato;
            },

            crearContrato(){
                if(this.validarRegistro() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;

                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/contrato/registrar',{
                    'id': this.id,
                    'infonavit':this.infonavit,
                    'fovisste':this.fovissste,
                    'comision_apertura':this.comision_apertura,
                    'investigacion':this.investigacion,
                    'avaluo':this.avaluo,
                    'prima_unica':this.prima_unica,
                    'escrituras':this.escrituras,
                    'credito_neto':this.credito_neto,
                    'avaluo_cliente':this.avaluo_cliente,
                    'fecha':this.fecha_contrato,
                    'direccion_empresa': this.direccion_empresa,
                    'cp_empresa': this.cp_empresa,
                    'colonia_empresa': this.colonia_empresa,
                    'estado_empresa':this.estado_empresa,
                    'ciudad_empresa':this.ciudad_empresa,
                    'telefono_empresa':this.telefono_empresa,
                    'ext_empresa':this.ext_empresa,
                    'direccion_empresa_coa':this.direccion_empresa_coa,
                    'cp_empresa_coa':this.cp_empresa_coa,
                    'colonia_empresa_coa':this.colonia_empresa_coa,
                    'estado_empresa_coa':this.estado_empresa_coa,
                    'ciudad_empresa_coa':this.ciudad_empresa_coa,
                    'telefono_empresa_coa':this.telefono_empresa_coa,
                    'ext_empresa_coa':this.ext_empresa_coa,
                    'total_pagar':this.total_pagar,
                    'monto_total_credito':this.monto_total_credito,
                    'enganche_total':this.enganche_total,
                    'lote_id': this.lote_id,
                    'observacion' : this.observacion,

                    'data':this.arrayPagos,
                    
                }).then(function (response){
                    me.proceso=false;
                    me.actualizarDatosProspecto();
                    me.limpiarDatos();
                    me.listarSimulaciones(1,me.buscar,me.b_etapa,me.b_manzana,me.b_lote,me.criterio);
                    me.listado=0;
                    
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Contrato creado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },

            actualizarDatosProspecto(){
                //Con axios se llama el metodo store del controller
                axios.put('/contrato/actualizarCredito',{
                   'prospecto_id':this.prospecto_id,
                   'apellidos':this.apellidos,
                   'nombre':this.nombre,
                   'f_nacimiento':this.fecha_nac,
                   'rfc':this.rfc,
                   'homoclave':this.homoclave,
                   'direccion':this.direccion,
                   'cp':this.cp,
                   'colonia':this.colonia,
                   'telefono':this.telefono,
                   'celular':this.celular,
                   'email':this.email,
                   'sexo':this.sexo,
                   'email_institucional':this.email_inst,
                   'edo_civil':this.e_civil,
                   'nss':this.nss,
                   'curp':this.curp,
                   'empresa':this.empresa,
                   'coacreditado':this.coacreditado,
                   'ciudad':this.ciudad,
                   'estado':this.estado,
                   'nacionalidad':this.nacionalidad,
                   'puesto':this.puesto,
                   'sexo_coa':this.sexo_coa,
                   'direccion_coa':this.direccion_coa,
                   'email_institucional_coa':this.email_institucional_coa,
                   'edo_civil_coa':this.e_civil_coa,
                   'nss_coa':this.nss_coa,
                   'curp_coa':this.curp_coa,
                   'nombre_coa':this.nombre_coa,
                   'apellidos_coa':this.apellidos_coa,
                   'f_nacimiento_coa':this.fecha_nac_coa,
                   'colonia_coa':this.colonia_coa,
                   'cp_coa':this.cp_coa,
                   'rfc_coa':this.rfc_coa,
                   'homoclave_coa':this.homoclave_coa,
                   'ciudad_coa':this.ciudad_coa,
                   'estado_coa':this.estado_coa,
                   'empresa_coa':this.empresa_coa,
                   'nacionalidad_coa':this.nacionalidad_coa,
                   'telefono_coa':this.telefono_coa,
                   'celular_coa':this.celular_coa,
                   'email_coa':this.email_coa,
                   'parentesco_coa':this.parentesco_coa,
                   'id':this.id,
                   'num_dep_economicos':this.dep_economicos,
                   'lote_id': this.lote_id,
                })
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

            limpiarDatos(){
                let me = this;
                me.id=0;
                me.proceso=false;

                me.arrayDatosSimulacion=[];
                me.arryaEmpresas=[];
                me.arrayPagos=[];

                /// variables datos del prospecto //
                    me.nombre='';
                    me.apellidos='';
                    me.sexo='';
                    me.telefono = '';
                    me.celular = '';
                    me.email='';
                    me.estado= '';
                    me.ciudad='';
                    me.cp='';
                    me.colonia='';
                    me.direccion= '';
                    me.fecha_nac= '';
                    me.curp='';
                    me.rfc='';
                    me.homoclave= '';
                    me.nss='';
                    me.nacionalidad=0;
                    
                    me.tipo_economia= 0;
                    me.empresa= '';
                    me.puesto='';
                    me.e_civil= 0;
                    me.email_inst='';
                    me.direccion_empresa='';
                    me.cp_empresa='';
                    me.colonia_empresa='';
                    me.estado_empresa='';
                    me.ciudad_empresa='';
                    me.dep_economicos='';
                    me.telefono_empresa='';
                    me.ext_empresa='';

                // variables coacreditado //
                    me.conyugeNom='';
                    me.coacreditado= 0;
                    me.nombre_coa='';
                    me.parentesco_coa='';
                    me.apellidos_coa='';
                    me.telefono_coa = '';
                    me.celular_coa = '';
                    me.email_coa='';
                    me.email_institucional_coa='';
                    me.nss_coa='';
                    me.sexo_coa='';
                    me.fecha_nac_coa= '';
                    me.curp_coa='';
                    me.rfc_coa='';
                    me.homoclave_coa= '';
                    me.e_civil_coa= 0;
                    me.tipo_casa_coa=0;
                    me.estado_coa= '';
                    me.ciudad_coa= '';
                    me.cp_coa='';
                    me.nacionalidad_coa=0;
                    me.direccion_coa='';
                    me.colonia_coa='';
                    me.empresa_coa='';
                    me.direccion_empresa_coa='';
                    me.cp_empresa_coa='';
                    me.colonia_empresa_coa='';
                    me.estado_empresa_coa='';
                    me.ciudad_empresa_coa='';
                    me.telefono_empresa_coa='';
                    me.ext_empresa_coa='';
                    this.observacion='';


                /// variables referencias //
                    me.nombre_referencia1='';
                    me.telefono_referencia1='';
                    me.celular_referencia1='';
                    me.nombre_referencia2='';
                    me.telefono_referencia2='';
                    me.celular_referencia2='';

                /// variables datos economicos //
                    me.proyecto_interes_id= 0;
                    me.proyecto='';
                    me.etapa= '';
                    me.manzana= '';
                    me.lote= '';
                    me.num_lote='';
                    me.modelo= '';
                    me.superficie= '';
                    me.precioBase= 0;
                    me.precioExcedente= 0;
                    me.precioVenta= 0;
                    me.precioObraExtra=0;
                    me.promocion= '';
                    me.descripcionPromo= '';
                    me.descuentoPromo= 0;
                    me.paquete_id= 0;
                    me.descripcionPaquete= '';
                    me.costoPaquete= 0;
                    me.paquete='';
                    me.terreno_tam_excedente=0;
                    me.tipo_credito='';
                    me.inst_financiera='';
                    me.plazo_credito='';
                    me.monto_credito='';

                    /// Credito datos extra //
                        me.comision_apertura='0';
                        me.investigacion='0';
                        me.avaluo='0';
                        me.prima_unica='0';
                        me.escrituras='0';
                        me.credito_neto='0';
                        me.avaluo_cliente='0';
                        me.total_pagar=0;
                        me.enganche_total=0;
                        me.infonavit='0';
                        me.fovissste='0';
                        me.monto_total_credito=0;
             
                me.prospecto_id=0;
                me.restante=0;
                me.monto_pago=0;
                me.fecha_pago='';
                me.fecha_contrato='';
                me.arrayEstados= [];
                me.arrayCiudadesCoa= [];
                me.arrayCiudades=[];
                me.arrayColoniasCoa= [];
                me.arrayColonias= [];
                me.arrayPaquetes= [];
                me.arrayDatosPaquetes= [];

                me.errorContrato = 0;
                me.errorMostrarMsjContrato = [];

                me.criterio = 'personal.nombre'; 
                me.buscar = '';
                me.b_etapa= '';
                me.b_manzana= '';
                me.b_lote= '';

                me.criterio2 = 'personal.nombre'; 
                me.buscar2 = '';
                me.b_etapa2= '';
                me.b_manzana2= '';
                me.b_lote2= '';

            },

            limpiarBusqueda(){
                let me=this;
                me.buscar= "";
                me.b_etapa='';
                me.b_manzana='';
                me.b_lote='';
                me.buscar2= "";
                me.b_etapa2='';
                me.b_manzana2='';
                me.b_lote2='';
                
            },

           
        },
        mounted() {          
            this.listarContratos(1,this.buscar,this.b_etapa,this.b_manzana,this.b_lote,this.criterio);
            this.selectFraccionamientos();
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
    }
</style>
