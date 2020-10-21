<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
        </ol>

        <div class="container-fluid">
            <div class="card scroll-box">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Cotizador de lotes
                    &nbsp;
                    <button @click="back()" class="btn btn-primary btn-sm">Regresar</button>
                </div>
                
                <div class="card-body">

                    <!--TOTALES A PAGAR-->
                    <template v-if="editar == 0">
                        <!--FORMULARIO-->
                        <div class="row">
                            <input type="date" v-model="b_fecha" @keyup.enter="getCotizacion()" class="form-control col-sm-2">
                            
                            <input type="text" v-model="b_cliente" @keyup.enter="getCotizacion()" placeholder="Nombre Apellidos" class="form-control col-sm-4">

                            <select class="form-control col-sm-2" v-model="r_proyecto" v-on:change="selectEtapa(r_proyecto)" @keyup.enter="getCotizacion()">
                                <option value="">Proyecto</option>
                                <option v-for="proj in arrayFraccionamientos" :key="proj.id" :value="proj.id" v-text="proj.nombre">proyecto 1</option>
                            </select>
                            <select v-on:change="buscaLotes(r_etapa)" class="form-control col-md-2" v-model="r_etapa" @keyup.enter="getCotizacion()">
                                <option value="">Etapa</option>
                                <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                            </select>

                            <select class="form-control col-sm-2" v-model="b_lote" @keyup.enter="getCotizacion()">
                                <option value="">Lote</option>
                                <option v-for="lote in arrayLotes" :key="lote.id" :value="lote.id" v-text="lote.num_lote">lote 1</option>
                            </select>

                            <select class="form-control col-sm-2" @keyup.enter="getCotizacion()" v-model="b_mensualidad" title="Mensualidades" required>
                                <option value="" disabled selected>Pagos</option>
                                <option value="1">0 A 1 MES</option>
                                <option value="6">1 A 6 MESES</option>
                                <option value="12">7 A 12 MESES</option>
                                <option value="24">13 A 24 MESES</option>
                                <option value="36">25 A 36 MESES</option>
                                <option value="48">37 A 48 MESES</option>
                                
                            </select>

                            <button @click="getCotizacion()" class="btn btn-sm btn-primary col-sm-1">
                                <i class="fa fa-search"></i> Buscar
                            </button>
                            
                        </div>
                        <br>

                        <div class="row">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Opciones</th>
                                        <th>Cliente</th>
                                        <th>Proyecto</th>
                                        <th>Etapa</th>
                                        <th>Lote</th>
                                        <th>Valor de Venta</th>
                                        <th>Mensualidades</th>
                                        <th>Estatus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="cot in arrayCotizaciones.data" :key="cot.id">
                                        <td v-text="cot.id"></td>
                                        <th>
                                            <button v-if="cot.estatus == 0" @click="cancelar(cot.id)" class="btn btn-danger btn-sm" title="Cancelar">
                                                <i class="fa fa-close"></i>
                                            </button>
                                            <button v-if="cot.estatus == 0" @click="abrirModal(cot)" class="btn btn-success btn-sm" title="Aprobar">
                                                <i class="fa fa-check-square-o"></i>
                                            </button>
                                            <a :href="'/calc/generar/pdf/'+cot.id" target="_blank" class="btn btn-sm btn-success">
                                                <i class="fa fa-file-pdf-o"></i>
                                            </a>
                                        </th>
                                        <td>
                                            <a v-if="cot.estatus==0" href="#" @click="editarCotizacin(cot.id)" v-text="cot.n_completo"></a>
                                            <span v-else v-text="cot.n_completo"></span>
                                        </td>
                                        <td v-text="cot.fraccionamiento"></td>
                                        <td v-text="cot.num_etapa"></td>
                                        <td v-text="cot.num_lote"></td>
                                        <td v-text="'$'+cot.valor_venta.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2})"></td>
                                        <td v-text="cot.mensualidades"></td>
                                        <td>
                                            <span v-if="cot.estatus==0" class="badge-info">Pendiente</span>
                                            <span v-else-if="cot.estatus==1" class="badge-success">Aprobado</span>
                                            <span v-else-if="cot.estatus==2" class="badge-danger">Cancelado</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </template>
                    
                    <!--Editar-->
                    <template v-if="editar == 1">

                        <!--FORMULARIO-->
                        <div class="row">
                            <input type="date" v-model="r_fecha" v-on:change="actualizar()" class="form-control col-sm-2 text-info">
                            
                            <input readonly type="text" v-model="e_cliente" class="form-control col-sm-4">

                            <input readonly type="text" v-model="r_proyecto" class="form-control col-sm-2">
                            
                            <input readonly type="text" v-model="r_etapa" class="form-control col-sm-2">

                            <input readonly type="text" v-model="r_lote" class="form-control col-sm-2">
                            
                            <div v-text="r_sup_terreno+' m²'" class="text-info form-control col-sm-2" title="Superficie de Terreno"></div>
                            <div v-text="'Costo m² $ '+r_valor_m2.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2})" class="text-info form-control col-sm-2" title="Costo m²"></div>
                            <div v-text="'Valor de Venta $ '+r_valor_venta.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2})" class="text-info form-control col-sm-3" title="Valor de Venta"></div>

                            <select disabled class="form-control col-sm-2" v-on:change="generarLista()" v-model="r_mensualidad" title="Mensualidades" required>
                                <option value="" disabled selected>Pagos</option>
                                <option value="1">0 A 1 MES</option>
                                <option value="6">1 A 6 MESES</option>
                                <option value="12">7 A 12 MESES</option>
                                <option value="24">13 A 24 MESES</option>
                                <option value="36">25 A 36 MESES</option>
                                <option value="48">37 A 48 MESES</option>
                                <!--option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                    <option value="13">13</option>
                                    <option value="14">14</option>
                                    <option value="15">15</option>
                                    <option value="16">16</option>
                                    <option value="17">17</option>
                                    <option value="18">18</option>
                                    <option value="19">19</option>
                                    <option value="20">20</option>
                                    <option value="21">21</option>
                                    <option value="22">22</option>
                                    <option value="23">23</option>
                                    <option value="24">24</option>
                                    <option value="25">25</option>
                                    <option value="26">26</option>
                                    <option value="27">27</option>
                                    <option value="28">28</option>
                                    <option value="29">29</option>
                                    <option value="30">30</option>
                                    <option value="31">31</option>
                                    <option value="32">32</option>
                                    <option value="33">33</option>
                                    <option value="34">34</option>
                                    <option value="35">35</option>
                                    <option value="36">36</option>
                                    <option value="37">37</option>
                                    <option value="38">38</option>
                                    <option value="39">39</option>
                                    <option value="40">40</option>
                                    <option value="41">41</option>
                                    <option value="42">42</option>
                                    <option value="43">43</option>
                                    <option value="44">44</option>
                                    <option value="45">45</option>
                                    <option value="46">46</option>
                                    <option value="47">47</option>
                                    <option value="48">48</option>
                                    <option value="40">40</option>
                                    <option value="40">40</option>
                                <option value="40">40</option-->
                            </select>

                            <button @click="actualizar()" class="btn btn-sm btn-primary col-sm-1">Calcular</button>
                            <button @click="guardaCotizacion()" class="btn btn-sm btn-warning col-sm-1">Guardar</button>
                            <button @click="generarPdf()" class="btn btn-sm btn-success col-sm-1">
                                <i class="fa fa-file-pdf-o"></i> PDF
                            </button>
                        </div>

                        <br>
                        <div class="row">

                            <!--TOTALES A PAGAR-->
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <td class=" text-right">
                                            <strong v-if="r_mensualidad != 1" v-text="'Saldo inicial $ '+r_valor_venta.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2})"></strong>
                                            <template v-else>
                                                <strong v-text="
                                                    'Saldo inicial $ '
                                                    +r_valor_venta.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2})
                                                    +'  -  '"
                                                ></strong>
                                                <strong class="badge-warning" v-text="
                                                    'Descuento $ '
                                                    +r_valor_descuento.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2})
                                                    +'  =  '"
                                                ></strong>
                                                <strong class="badge-info" v-text="
                                                    'Total a Pagar $ '
                                                    +(r_valor_venta-r_valor_descuento).toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2})
                                                "></strong>
                                            </template>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <br>
                        <div class="row">

                            <!--PAGOS-->
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th># Pago</th>
                                        <th>Mensualidad</th>
                                        <th>Cantidad</th>
                                        <th>Fecha</th>
                                        <th>Dias</th>
                                        <th>% Descuento</th>
                                        <th>Descuento</th>
                                        <th>Interes</th>
                                        <th>Total a Pagar</th>
                                        <th>Saldo Pendiente</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="pago in arrayMensualidad" :key="pago.folio">
                                        <td v-text="pago.folio" class="text-info text-center">#</td>
                                        
                                        <td v-if="pago.pago == 0" >Enganche</td>
                                        <td v-else>Mensualidad</td>
                                        
                                        <td style="padding:0px;">
                                            <input v-model="pago.cantidad" v-on:keyup="calculaPrecoi(pago)" type="number" step=".01" class="form-control" style="height: 45px;">
                                        </td>

                                        <td style="padding:0px;" class="text-center">
                                            <input v-model="pago.fecha" v-on:change="calculaPrecoi(pago)" type="date" class="form-control" style="height: 45px;">
                                        </td>
                                        <td>
                                            <span v-text="pago.dias" class="badge" v-bind:class="!(r_mensualidad>6) ? 'badge-success' : 'badge-warning'"></span>
                                        </td>

                                        <td v-text="pago.descuento_porc+'%'"></td>
                                        <td v-text="'$ '+pago.descuento.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2})">Descuento</td>
                                        <td v-text="'$ '+pago.interes_monto.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2})">Interes</td>
                                        <td v-text="'$ '+pago.total_a_pagar.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2})">Total a Pagar</td>
                                        <td v-text="'$ '+pago.saldo.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2})">Saldo</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <br>
                        <div class="row">
                            <br>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <td class="text-center" colspan="10">
                                            <strong class="badge badge-warning">
                                                Nota: la presente cotización tiene vigencia de 8 días hábiles posteriores a la emisión y el lote cotizado estará sujeto a disponibilidad.
                                            </strong>
                                        </td>
                                    </tr>
                                </thead>
                                <thead>
                                    <tr>
                                        <th colspan="10">Plan Comercial de Pagos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center"><strong>De 0 a 1 mes</strong></td>
                                        <td class="text-center"><strong>De 1 a 6 mes</strong></td>
                                        <td class="text-center"><strong>De 7 a 12 meses</strong></td>
                                        <td class="text-center"><strong>De 13 a 24 meses</strong></td>
                                        <td class="text-center"><strong>De 25 a 36 meses</strong></td>
                                        <td class="text-center"><strong>De 37 a 48 meses</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">0% de Interes de tasa anual</td>
                                        <td class="text-center">0% de Interes de tasa anual</td>
                                        <td class="text-center">12% de Interes de tasa anual</td>
                                        <td class="text-center">16% de Interes de tasa anual</td>
                                        <td class="text-center">18% de Interes de tasa anual</td>
                                        <td class="text-center">20% de Interes de tasa anual</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </template>


                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>

        <!--Inicio del modal -->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="'Nueva solicitud de compra venta'"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- form para solicitud de avaluo -->

                                    <div class="">
                                        <div class="card-body">
                                            <ul class="nav nav-tabs">
                                                <li class="nav-item"><a class="nav-link"  v-bind:class="{ 'active': paso==1 }" @click="paso = 1">Datos del prospecto</a></li>
                                                <li v-if="datos.coacreditado == 1" class="nav-item"><a class="nav-link"  v-bind:class="{ 'active': paso==2 }" @click="paso = 2">Datos coacreditado</a></li>
                                                <li class="nav-item"><a class="nav-link"  v-bind:class="{ 'active': paso==3 }" @click="paso = 3">Referencias familiares</a></li>
                                            </ul>
                                        </div>

                                        <template v-if="paso == 1"> <!-- Datos del prospecto -->

                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Nombre: </label>
                                                <div class="col-md-4">
                                                    <input type="text" disabled v-model="datos.nombre" class="form-control" >
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" disabled v-model="datos.apellidos" class="form-control" >
                                                </div>
                                            </div>
                                            
                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Telefono: </label>
                                                <div class="col-md-3">
                                                    <input type="text" disabled v-model="datos.telefono" class="form-control" >
                                                </div>
                                                <label class="col-md-2 form-control-label" for="text-input">Celular: </label>
                                                <div class="col-md-3">
                                                    <input type="text" disabled v-model="datos.celular" class="form-control" >
                                                </div>
                                            </div>
                                            
                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Dirección: <span style="color:red;" v-show="datos.direccion==''">(*)</span></label>
                                                <div class="col-md-4">
                                                    <input type="text" v-model="datos.direccion" class="form-control" >
                                                </div>
                                                <label class="col-md-1 form-control-label" for="text-input">CP: <span style="color:red;" v-show="datos.cp==''">(*)</span></label>
                                                <div class="col-md-3">
                                                    <input type="text" v-model="datos.cp"  
                                                    pattern="\d*" maxlength="5" v-on:keypress="isNumber($event)" @keyup="selectColonias(datos.cp,0)"
                                                    class="form-control" >
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Colonia: <span style="color:red;" v-show="datos.colonia==''">(*)</span></label>
                                                <div class="col-md-7">
                                                    <input type="text" name="city3" list="cityname3" class="form-control" v-model="datos.colonia">
                                                    <datalist id="cityname3">
                                                        <option value="">Seleccione</option>
                                                        <option v-for="colonias in arrayColonias" :key="colonias.colonia " :value="colonias.colonia" v-text="colonias.colonia"></option>    
                                                    </datalist>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Estado: <span style="color:red;" v-show="datos.estado==''">(*)</span></label>
                                                <div class="col-md-3">
                                                    <select class="form-control" v-model="datos.estado" @click="selectCiudades(datos.estado,0)" >
                                                        <option value="">Seleccione</option>
                                                        <option v-for="estados in arrayEstados" :key="estados.estado" :value="estados.estado" v-text="estados.estado"></option>    
                                                    </select>
                                                </div>
                                                <label class="col-md-1 form-control-label" for="text-input">Ciudad: <span style="color:red;" v-show="datos.ciudad==''">(*)</span></label>
                                                <div class="col-md-5">
                                                    <select class="form-control" v-model="datos.ciudad" >
                                                        <option value="">Seleccione</option>
                                                        <option v-for="ciudades in arrayCiudades" :key="ciudades.municipio" :value="ciudades.municipio" v-text="ciudades.municipio"></option>    
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">CURP: <span style="color:red;" v-show="datos.curp==''">(*)</span></label>
                                                <div class="col-md-4">
                                                    <input type="text" v-model="datos.curp" maxlength="18" class="form-control" >
                                                </div>
                                                <label class="col-md-1 form-control-label" for="text-input">NSS: <span style="color:red;" v-show="datos.nss==''">(*)</span></label>
                                                <div class="col-md-4">
                                                    <input type="text" v-model="datos.nss" maxlength="11" pattern="\d*" class="form-control" v-on:keypress="isNumber($event)">
                                                </div>
                                            </div>

                                                <div class="form-group row line-separator"></div>

                                                <div class="col-md-12">
                                                    <h6 align="center"><strong> Lugar de trabajo </strong></h6>
                                                </div>

                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input" >Tipo de economia <span style="color:red;" v-show="tipo_economia==0">(*)</span></label>
                                                <div class="col-md-3">
                                                    <select class="form-control" v-model="tipo_economia" >
                                                        <option value="0">Seleccione</option>  
                                                        <option value="Formal">Formal</option>
                                                        <option value="Informal">Informal</option>
                                                        <option value="Mixta">Mixta</option>
                                                    </select>
                                                </div>

                                                <label class="col-md-2 form-control-label" for="text-input" v-if="tipo_economia=='Formal' ||tipo_economia=='Mixta'">Puesto: </label>
                                                <label class="col-md-2 form-control-label" for="text-input" v-else>Giro del negocio: </label>
                                                <div class="col-md-4">
                                                    <input type="text" v-model="puesto" maxlength="50" class="form-control" >
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="">Empresa <span style="color:red;" v-show="datos.empresa==0">(*)</span></label>
                                                <div class="col-md-6">
                                                    <v-select 
                                                        :on-search="selectEmpresaVueselect"
                                                        label="nombre"
                                                        :options="arrayEmpresa"
                                                        placeholder="Buscar empresa..."
                                                        :onChange="getDatosEmpresa2"
                                                    ></v-select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-3 form-control-label" for="">Numero de dependientes económicos  <span style="color:red;" v-show="datos.empresa==0">(*)</span></label>
                                                <div class="col-md-2">
                                                    <input type="number" v-model="num_dep_economicos" maxlength="2" class="form-control"  min="0">
                                                </div>
                                            </div>
                                                

                                        </template>

                                        <template v-if="paso == 2"> <!-- Datos del coacreditado -->

                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Nombre: </label>
                                                <div class="col-md-4">
                                                    <input type="text" disabled v-model="datos.nombre_coa" class="form-control" >
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" disabled v-model="datos.apellidos_coa" class="form-control" >
                                                </div>
                                            </div>
                                            
                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Telefono: </label>
                                                <div class="col-md-3">
                                                    <input type="text" disabled v-model="datos.telefono_coa" class="form-control" >
                                                </div>
                                                <label class="col-md-2 form-control-label" for="text-input">Celular: </label>
                                                <div class="col-md-3">
                                                    <input type="text" disabled v-model="datos.celular_coa" class="form-control" >
                                                </div>
                                            </div>
                                            
                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Dirección: <span style="color:red;" v-show="datos.direccion_coa==''">(*)</span></label>
                                                <div class="col-md-4">
                                                    <input type="text" v-model="datos.direccion_coa" class="form-control" >
                                                </div>
                                                <label class="col-md-1 form-control-label" for="text-input">CP: <span style="color:red;" v-show="datos.cp_coa==''">(*)</span></label>
                                                <div class="col-md-3">
                                                    <input type="text" v-model="datos.cp_coa" 
                                                    pattern="\d*" maxlength="5" v-on:keypress="isNumber($event)" @keyup="selectColonias(datos.cp_coa,1)"
                                                    class="form-control" >
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Colonia: <span style="color:red;" v-show="datos.colonia_coa==''">(*)</span></label>
                                                <div class="col-md-7">
                                                    <input type="text" name="city3" list="cityname3" class="form-control" v-model="datos.colonia_coa">
                                                    <datalist id="cityname3">
                                                        <option value="">Seleccione</option>
                                                        <option v-for="colonias in arrayColoniasCoa" :key="colonias.colonia " :value="colonias.colonia" v-text="colonias.colonia"></option>    
                                                    </datalist>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Estado: <span style="color:red;" v-show="datos.estado_coa==''">(*)</span></label>
                                                <div class="col-md-3">
                                                    <select class="form-control" v-model="datos.estado_coa" @click="selectCiudades(datos.estado_coa,0)" >
                                                        <option value="">Seleccione</option>
                                                        <option v-for="estados in arrayEstados" :key="estados.estado" :value="estados.estado" v-text="estados.estado"></option>    
                                                    </select>
                                                </div>
                                                <label class="col-md-1 form-control-label" for="text-input">Ciudad: <span style="color:red;" v-show="datos.ciudad_coa==''">(*)</span></label>
                                                <div class="col-md-5">
                                                    <select class="form-control" v-model="datos.ciudad_coa" >
                                                        <option value="">Seleccione</option>
                                                        <option v-for="ciudades in arrayCiudades" :key="ciudades.municipio" :value="ciudades.municipio" v-text="ciudades.municipio"></option>    
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">CURP: <span style="color:red;" v-show="datos.curp_coa==''">(*)</span></label>
                                                <div class="col-md-4">
                                                    <input type="text" v-model="datos.curp_coa" maxlength="18" class="form-control" >
                                                </div>
                                                <label class="col-md-1 form-control-label" for="text-input">NSS: </label>
                                                <div class="col-md-4">
                                                    <input type="text" v-model="datos.nss_coa" maxlength="11" pattern="\d*" class="form-control" v-on:keypress="isNumber($event)">
                                                </div>
                                            </div>

                                                <div class="form-group row line-separator"></div>

                                                <div class="col-md-12">
                                                    <h6 align="center"><strong> Lugar de trabajo </strong></h6>
                                                </div>


                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="">Empresa <span style="color:red;" v-show="datos.empresa_coa==0">(*)</span></label>
                                                <div class="col-md-6">
                                                    <v-select 
                                                        :on-search="selectEmpresaVueselect"
                                                        label="nombre"
                                                        :options="arrayEmpresa"
                                                        placeholder="Buscar empresa..."
                                                        :onChange="getDatosEmpresa"
                                                    ></v-select>
                                                </div>
                                            </div>

                                           

                                        </template>

                                        <template v-if="paso == 3"> <!-- Datos de referencias -->

                                                <div class="form-group row line-separator"></div>

                                                <div class="col-md-12">
                                                    <h6 align="center"><strong> Primera Referencia</strong></h6>
                                                </div>

                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Nombre:  <span style="color:red;" v-show="nombre_primera_ref ==''">(*)</span></label>
                                                <div class="col-md-5">
                                                    <input type="text" v-model="nombre_primera_ref" class="form-control" >
                                                </div>
                                            </div>

                                             <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Telefono:  <span style="color:red;" v-show="telefono_primera_ref ==''">(*)</span></label>
                                                <div class="col-md-3">
                                                    <input type="text" v-model="telefono_primera_ref"  pattern="\d*" 
                                                    maxlength="10" v-on:keypress="isNumber($event)" class="form-control" >
                                                </div>
                                                <label class="col-md-1 form-control-label" for="text-input">Cel:  <span style="color:red;" v-show="celular_primera_ref ==''">(*)</span></label>
                                                <div class="col-md-3">
                                                    <input type="text" v-model="celular_primera_ref"  pattern="\d*" 
                                                    maxlength="10" v-on:keypress="isNumber($event)" class="form-control" >
                                                </div>
                                            </div>

                                            <div class="form-group row line-separator"></div>

                                                <div class="col-md-12">
                                                    <h6 align="center"><strong> Segunda Referencia</strong></h6>
                                                </div>

                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Nombre: <span style="color:red;" v-show="nombre_segunda_ref ==''">(*)</span></label>
                                                <div class="col-md-5">
                                                    <input type="text" v-model="nombre_segunda_ref" class="form-control" >
                                                </div>
                                            </div>

                                             <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Telefono: <span style="color:red;" v-show="telefono_segunda_ref ==''">(*)</span> </label>
                                                <div class="col-md-3">
                                                    <input type="text" v-model="telefono_segunda_ref"  pattern="\d*" 
                                                    maxlength="10" v-on:keypress="isNumber($event)" class="form-control" >
                                                </div>
                                                <label class="col-md-1 form-control-label" for="text-input">Cel: <span style="color:red;" v-show="celular_segunda_ref ==''">(*)</span> </label>
                                                <div class="col-md-3">
                                                    <input type="text" v-model="celular_segunda_ref"  pattern="\d*" 
                                                    maxlength="10" v-on:keypress="isNumber($event)" class="form-control" >
                                                </div>
                                            </div>

                                            
                                                

                                        </template>
                                    </div>

                                
                            <!-- fin del form solicitud de avaluo -->


                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" class="btn btn-primary" @click="aprobar(id)">Asignar</button>
                        </div>
                    </div>
                      <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal consulta-->
    </main>
</template>

<script>
import vSelect from 'vue-select';
export default {
    
    data() {
        return{
            r_fecha:'',
            r_nombre:'',
            r_apellidos:'',
            r_proyecto:'',
            r_etapa:'',
            r_lote:'',
            r_sup_terreno:0,
            r_valor_m2:0,
            r_valor_venta:0,
            valor_enganche:0,
            valor_minMens:0,
            r_mensualidad:'',
            r_valor_descuento:0,
            idCliente:0,
            idLote:0,
            editar:0,
            b_cliente:'',
            b_fecha:'',
            b_lote:'',
            b_mensualidad:'',
            e_cliente:'',
            idCotizacionEdit:0,
            arrayCotizaciones:[],
            arrayMensualidad:[],
            arrayFraccionamientos:[],
            arrayEtapas:[],
            arrayLotes:[],
            arrayListA:[],
            arrayColonias:[],
            arrayColoniasCoa:[],
            arrayClientes:[],
            arrayEstados:[],
            arrayCiudades:[],
            arrayCiudadesCoa:[],
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

            id:0,
            paso:0,
            modal:0,

            datos : [],
            arrayEmpresa: [],
            tipo_economia:0,
            puesto:'',
            num_dep_economicos:0,
            nombre_primera_ref:'',
            nombre_segunda_ref:'',
            telefono_primera_ref:'',
            telefono_segunda_ref:'',
            celular_primera_ref:'',
            celular_segunda_ref:'',
           
        }
    },
    computed:{

    },
    components:{
        vSelect
    },
    methods: {
        selectEmpresaVueselect(search, loading){
            let me = this;
            loading(true)

            var url = '/select_empresas?filtro='+search;
            axios.get(url).then(function (response) {
                let respuesta = response.data;
                q: search
                me.arrayEmpresa = respuesta.empresas;
                loading(false)
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        getDatosEmpresa(val1){
                let me = this;
                me.loading = true;
                me.datos.empresa_coa = val1.nombre;
                me.datos.empresaCoa_id = val1.id;
               
            }, 
            getDatosEmpresa2(val1){
                let me = this;
                me.loading = true;
                me.datos.empresa_id = val1.id;
                me.datos.empresa = val1.nombre;
               
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
        cerrarModal(){
            this.datos = [];
            this.modal = 0;
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
        generarLista(){
            this.arrayMensualidad=[];
            this.arrayNMensualidad=[];
            this.valor_enganche=10000;

            this.r_mensualidad==1?(
                this.r_valor_descuento = this.r_valor_venta*(this.arrayListA[0].valor/100)
            ):this.r_valor_descuento=0;

            let fullPrice = this.r_valor_venta-this.r_valor_descuento;
            let fechaPago = '';

            //asignacion de fecha actual
                if(this.r_fecha =="") this.r_fecha = moment().format('YYYY-MM-DD');
                fechaPago = new Date(
                    this.r_fecha.substring(5,7)
                    +'/'+this.r_fecha.substring(8,10)
                    +'/'+this.r_fecha.substring(0, 4)
                );
            //asignacion de fecha actual

            //asignacion de primer pago o enganche
                if(this.r_mensualidad == 48){
                    this.valor_minMens = (fullPrice-(fullPrice*0.3))/this.r_mensualidad;
                    this.valor_enganche = fullPrice*0.3;

                }else if(this.r_mensualidad == 1){  
                    this.valor_enganche = fullPrice.toFixed(2);

                }else this.valor_minMens = (fullPrice-10000)/this.r_mensualidad;
            //asignacion de primer pago o enganche

            //creacion de array vacio
            for(let i=0; this.r_mensualidad >=i; i++){
                
                let monto = 0.0;
                if(i == this.r_mensualidad && this.r_mensualidad != 1){
                    this.arrayMensualidad.forEach(item =>{
                        monto = monto+parseFloat(item.cantidad);
                    });
                    monto = (fullPrice-monto).toFixed(2);
                }else monto = i?this.valor_minMens:this.valor_enganche;

                //Setear Fecha de pago
                    let mes = '',dia = '';
                    if(i==1)
                        fechaPago.setDate(fechaPago.getDate()+10);
                    else if(i > 1) fechaPago.setMonth(fechaPago.getMonth()+1);
                    if(fechaPago.getMonth()<9)
                        mes = '0'+(fechaPago.getMonth()+1);
                    else mes = fechaPago.getMonth()+1;
                    if(fechaPago.getDate()<10)
                        dia = '0'+fechaPago.getDate();
                    else dia = fechaPago.getDate();
                    let fechaPagoFinal = fechaPago.getFullYear()+'-'+mes+'-'+dia;
                //Setear Fecha de pago fin

                this.arrayMensualidad.push({
                    folio: i+1,
                    pago: this.r_mensualidad==1?1:i?1:0,
                    cantidad:parseFloat(monto).toFixed(2),
                    fecha: fechaPagoFinal,
                    descuento:0,
                    dias:0,
                    descuento_porc:0,
                    interes_monto:0,
                    total_a_pagar:0,
                    saldo:0
                });
            }
            
            //si la mensualidad es 1 se elimina un campo
            if(this.r_mensualidad == 1) this.arrayMensualidad.pop();

            //actualiza los precios
            this.actualizar();
        },
        calculaPrecoi(index){
            
            let cantidad = (index.cantidad=="")?0:parseFloat(index.cantidad);
            let descuento = this.montoDescuento(index);
            let dias = 0;//this.dias(this.r_fecha, index.fecha);
            if(index.folio == 1){
                dias = this.dias(this.r_fecha, index.fecha);
            }else{
                dias = this.dias(this.arrayMensualidad[parseInt(index.folio-2)].fecha, index.fecha);
            }
            let folio = index.folio-1;

            //fechas de pago
                let firstFechaPago = new Date(
                    index.fecha.substring(5,7)
                    +'/'+index.fecha.substring(8,10)
                    +'/'+index.fecha.substring(0, 4)
                );
                let lastFechaPago = new Date(
                    this.r_fecha.substring(5,7)
                    +'/'+this.r_fecha.substring(8,10)
                    +'/'+this.r_fecha.substring(0, 4)
                );
                let fechaFinalPago = index.fecha;

                lastFechaPago.setMonth(lastFechaPago.getMonth()+parseInt(this.r_mensualidad));

                if(lastFechaPago < firstFechaPago){
                    let dia = (lastFechaPago.getDate()<10)?('0'+lastFechaPago.getDate()):('0'+lastFechaPago.getDate());
                    let mes = (lastFechaPago.getMonth()<9)?('0'+(lastFechaPago.getMonth()+1)):(lastFechaPago.getMonth()+1);

                    fechaFinalPago = lastFechaPago.getFullYear()+'-'+mes+'-'+dia;
                }
            //fechas de pago

            this.arrayMensualidad[folio].cantidad = cantidad;
            this.arrayMensualidad[folio].fecha = fechaFinalPago;//index.fecha;

            if(this.r_mensualidad > 6) this.arrayMensualidad[folio].interes_monto = this.intereses(index);

            this.arrayMensualidad[folio].total_a_pagar = this.arrayMensualidad[folio].interes_monto+cantidad;

            this.arrayMensualidad[folio].descuento_porc = descuento[1];
            this.arrayMensualidad[folio].descuento = descuento[0];
            this.arrayMensualidad[folio].dias = dias;

            //Calcular saldo pendiente
            let saldo = this.r_valor_venta-this.r_valor_descuento;
            this.arrayMensualidad.forEach(
                m => {
                    saldo = saldo - ((m.total_a_pagar + m.descuento) - m.interes_monto);
                    m.saldo = saldo;
                }
            );
        },
        selectFraccionamientos(){
            let me = this;
            
            me.arrayFraccionamientos=[];
            var url = '/get/fraccionamientos/lotes';

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
            var url = '/get/etapas/lotes?buscar=' + buscar;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayEtapas = respuesta.etapas;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        montoDescuento(datos){

            let dias = this.dias(this.r_fecha, datos.fecha)
            let montoDescuento = 0;
            let descuento = 0;

            let date = new Date(this.r_fecha);
            let days = this.daysInMonth(date.getMonth()+1, date.getFullYear());
            
            //if(this.r_mensualidad == 1 && this.arrayMensualidad[0].pago != 0){
            //    if(dias > 0 && dias <= days){
            //        descuento = this.arrayListA[0].valor;
            //        montoDescuento = this.descuento(datos.cantidad, descuento);
            //    }
            //}else 
            if(datos.pago == "0" && datos.cantidad > 10000 && dias <= 10 && this.r_mensualidad != 48){
                let engancheExed = datos.cantidad-10000;
                
                descuento = 4;
                montoDescuento = this.descuento(engancheExed, descuento);

            }else if(datos.pago == "0" && datos.cantidad > (this.r_valor_venta*.30) && dias <= 10){
                let engancheExed = datos.cantidad-(this.r_valor_venta*.30);
                
                descuento = 4;
                montoDescuento = this.descuento(engancheExed, descuento);
            }

            let array = [montoDescuento, descuento];
            //return montoDescuento;
            return array;
        },
        dias(startDate, endDate){

            if(startDate == '0000-00-00'|| startDate == "") startDate = '2000-01-01';
            if(endDate == '0000-00-00' || endDate == "") endDate = '2000-01-01';

            var from = moment(startDate),
                    to = moment(endDate),
                    days = 0;
                
            while (!from.isAfter(to,'day')) {
                // Si no es sabado ni domingo
                //if (from.isoWeekday() !== 6 && from.isoWeekday() !== 7) {
                    days++;
                //}
                from.add(1, 'days');
            }

            return days;
        },
        abrirModal(cotizacion){
            this.selectEstados();

            this.id = cotizacion.id;
            this.paso = 1;
            this.modal = 1;
            this.datos = cotizacion;

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

        descuento(monto, descuento){
            let montoDesc = monto * (descuento/100);
            montoDesc = Number(montoDesc.toFixed(2));
            return montoDesc;
        },
        intereses(datos){

            if(datos.pago == 0) return 0;

            let dias = 0;//this.dias(this.r_fecha, datos.fecha);
            if(datos.folio == 1){
                dias = this.dias(this.r_fecha, datos.fecha);
            }else{
                dias = this.dias(this.arrayMensualidad[parseInt(datos.folio-2)].fecha, datos.fecha);
            }
                
            let montoInteres = 0;

            let saldo = 0;
            this.arrayMensualidad.forEach(
                m => {
                    if(m.folio < datos.folio){
                        saldo = saldo + ((m.total_a_pagar + m.descuento) - m.interes_monto)
                    }
                }
            );

            saldo = this.r_valor_venta-saldo;

            let ineres = 0;

            if(this.r_mensualidad == 12){
                ineres = this.arrayListA[6].valor/100;
                montoInteres = ((saldo*ineres)/365)*dias;

            }else if(this.r_mensualidad == 24){
                ineres = this.arrayListA[7].valor/100;
                montoInteres = ((saldo*ineres)/365)*dias;

            }else if(this.r_mensualidad == 36){
                ineres = this.arrayListA[8].valor/100;
                montoInteres = ((saldo*ineres)/365)*dias;

            }else if(this.r_mensualidad == 48){
                ineres = this.arrayListA[9].valor/100;
                montoInteres = ((saldo*ineres)/365)*dias;
            }

            montoInteres = Number(montoInteres.toFixed(2));

            return montoInteres;
        },
        actualizar(){
            this.arrayMensualidad.forEach(m => this.calculaPrecoi(m));
        },
        buscaLotes(etapa){
            axios.get('/get/lotes/lotes?etapaId='+etapa).then(
                response => this.arrayLotes = response.data
            ).catch(error => console.log(error));
        },
        selccionaLote(data){
            this.r_sup_terreno = data.terreno;
            this.r_valor_m2 = data.precio_base/data.terreno;
            this.r_valor_venta = this.r_sup_terreno*this.r_valor_m2;
            this.idLote = data.id;

            if(this.r_mensualidad != "") this.generarLista();
        },
        listarPorcentajes(){
            axios.get('/calc/descuentos').then(
                response => this.arrayListA = response.data
            ).catch(error => console.log(error));
        },
        getCotizacion(){
            
            axios.get('/get/cotizacion/clientes'+
                '?b_fecha='+this.b_fecha+
                '&b_cliente='+this.b_cliente+
                '&r_proyecto='+this.r_proyecto+
                '&r_etapa='+this.r_etapa+
                '&b_lote='+this.b_lote+
                '&b_mensualidad='+this.b_mensualidad
            ).then(
                response => this.arrayCotizaciones = response.data
            ).catch(error => console.log(error));
        },
        getDatosCliente(cliente){
            this.idCliente = cliente.id;
        },
        daysInMonth (month, year) {
            return new Date(year, month, 0).getDate();
        },
        guardaCotizacion(){
            this.actualizar();
            
            Swal.fire({
                title: '¿Estas seguro?',
                text: "Este cambio no se podrá deshacer!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si guardar!',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.value) {

                    axios.post('/calc/actualizar/cotizacion',{
                        'idCotizacionEdit': this.idCotizacionEdit,
                        'pago':this.arrayMensualidad,
                        'idCliente':this.idCliente,
                        'idLote':this.idLote,
                        'valor_venta':this.r_valor_venta,
                        'valor_descuento':this.r_valor_descuento,
                        'fecha':this.r_fecha,
                        'mensualidades':this.r_mensualidad,
                    }).then(
                        rsponse => {
                            this.myAlerts.popAlert('Guardado correctamente');

                            return response.data;
                        }
                    ).catch(error => console.log(error));
                }
            });

        },
        generarPdf(){

            Swal.fire({
                title: '¿Guardar cotización y generar PDF?',
                text: "Este cambio no se podrá deshacer!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si guardar!',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.value) {

                    axios.post('/calc/actualizar/cotizacion',{
                        'idCotizacionEdit': this.idCotizacionEdit,
                        'pago':this.arrayMensualidad,
                        'idCliente':this.idCliente,
                        'idLote':this.idLote,
                        'valor_venta':this.r_valor_venta,
                        'valor_descuento':this.r_valor_descuento,
                        'fecha':this.r_fecha,
                        'mensualidades':this.r_mensualidad,
                    }).then(
                        response => {
                            this.myAlerts.popAlert('Guardado correctamente');

                            //llamada a PDF
                            window.open('/calc/generar/pdf/'+response.data, '_blank');

                        }
                    ).catch(error => console.log(error));
                }
            });

        },
        cancelar(id){

            Swal.fire({
                title: '¿Cancelar esta cotización?',
                text: "Este cambio no se podrá deshacer!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si Cancelar!',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.value) {
                    
                    axios.post('/edita/cotizacion/cancelar?id='+id).then(
                        () => {
                            this.myAlerts.popAlert('Guardado correctamente');
                            this.getCotizacion();
                        }
                    ).catch(error => console.log(error));

                }
            });

        },
        aprobar(id){

            if(this.nombre_primera_ref == '' || this.nombre_segunda_ref == ''){
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'No se han capturado las referencias personales',
                })
            }
            else{

                Swal.fire({
                    title: '¿Aprobar esta cotización?',
                    text: "Este cambio no se podrá deshacer!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si Aprobar!',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.value) {

                        axios.post('/edita/cotizacion/aprovar',
                        {
                            'id':id,
                            'datos':this.datos,
                            'tipo_economia':this.tipo_economia,
                            'puesto':this.puesto,
                            'num_dep_economicos':this.num_dep_economicos,
                            'nombre_primera_ref':this.nombre_primera_ref,
                            'nombre_segunda_ref':this.nombre_segunda_ref,
                            'telefono_primera_ref':this.telefono_primera_ref,
                            'telefono_segunda_ref':this.telefono_segunda_ref,
                            'celular_segunda_ref':this.celular_segunda_ref,
                            'celular_primera_ref':this.celular_primera_ref,
                            
                        }).then(
                            () => {
                                this.myAlerts.popAlert('Guardado correctamente');
                                this.getCotizacion();
                                this.cerrarModal();
                            }
                        ).catch(error => console.log(error));

                    }
                });

            }


            

        },
        editarCotizacin(id){
            this.editar = 1;

            axios.get('/get/cotizacion/editar?idCotizacion='+id).then(
                response => {

                    let cot = response.data.cotizacion;

                    this.idCotizacionEdit = response.data.cotizacion.id;
                    this.r_fecha = cot.fecha;
                    this.e_cliente = cot.nombre+' '+cot.apellidos;
                    this.r_proyecto = cot.fraccionamiento;
                    this.r_etapa = cot.num_etapa;
                    this.r_lote = cot.num_lote;
                    this.r_sup_terreno = cot.terreno_m2;
                    this.r_valor_m2 = cot.valor_venta/cot.terreno_m2;
                    this.r_mensualidad = cot.mensualidades;
                    this.r_valor_venta = cot.valor_venta;
                    this.r_valor_descuento = cot.valor_descuento;

                    this.arrayMensualidad = response.data.pago;
                }
            )
        },
        back(){
            this.editar = 0;

            this.r_fecha = '';
            this.r_proyecto = '';
            this.r_etapa = '';
            this.r_lote = '';
            this.r_sup_terreno = 0;
            this.r_valor_m2 = 0;
            this.r_mensualidad = '';
            this.r_valor_venta = 0;
            this.r_valor_descuento = 0;
        }
    },
    mounted() {
        this.selectFraccionamientos();
        this.listarPorcentajes();
        this.getCotizacion();
    }
};
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
    .div-error {
    display: flex;
    justify-content: center;
    }
    .text-error {
    color: red !important;
    font-weight: bold;
    }
    .card-user2 .avatar.border-white {
        border: 5px solid #fff;
    }
    .card-user2 .avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        position: relative;
        margin-bottom: 15px;
    }
    .card2 .avatar {
        width: 200px;
        height: 200px;
        overflow: hidden;
        border-radius: 50%;
        margin-right: 5px;
    }
    .border-white {
        border-color: #fff!important;
    }
    .card-user2 .author {
        text-align: center;
        text-transform: none;
        margin-top: -65px;
    }
    .card2 .author {
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }
    .card2 {
        border-radius: 6px;
        box-shadow: 0 2px 2px hsla(38,16%,76%,.5);
        background-color: #fff;
        color: #252422;
        margin-bottom: 20px;
        position: relative;
        z-index: 1;
        border: none;
    }
    .card2 .card-image2 img  {
        width: 100%;
    }
    img {
        vertical-align: middle;
        border-style: none;
    }
    .bg-gradient-primary {
        background: #00ADEF!important;
        background: linear-gradient(45deg,#321fdb 0%,#00ADEF 100%)!important;
        border-color: #00ADEF!important;
    }
    .p-3 {
        padding: 1rem!important;
    }
</style>

