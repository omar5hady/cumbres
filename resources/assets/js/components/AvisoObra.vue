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
                        <i class="fa fa-align-justify"></i> Aviso de obra
                        <!--   Boton Nuevo    -->
                        <button type="button" @click="mostrarDetalle()" class="btn btn-secondary" v-if="listado==1">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                        <!---->
                    </div>
                    <!-- Div Card Body para listar -->
                    <template v-if="listado == 1">
                        <div class="card-body"> 
                            <div class="form-group row">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <!--Criterios para el listado de busqueda -->
                                        <select class="form-control col-md-4" v-model="criterio" @click="limpiarBusqueda()">
                                            <option value="ini_obras.clave">Clave</option>
                                            <option value="contratistas.nombre">Contratista</option>
                                            <option value="ini_obras.f_ini">Fecha de inicio</option>
                                            <option value="ini_obras.f_fin">Fecha de termino</option>
                                        </select>
                                         <input v-if="criterio=='ini_obras.f_ini'" type="date" v-model="buscar" @keyup.enter="listarAvisos(1,buscar,criterio)" class="form-control">
                                         <input v-else-if="criterio=='ini_obras.f_fin'" type="date" v-model="buscar" @keyup.enter="listarAvisos(1,buscar,criterio)" class="form-control">
                                        <input v-else type="text" v-model="buscar" @keyup.enter="listarAvisos(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarAvisos(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Clave</th>
                                            <th>Contratista</th>
                                            <th>Fraccionamiento</th>
                                            <th>Fecha de inicio </th>
                                            <th>Fecha de termino</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="avisoObra in arrayAvisoObra" :key="avisoObra.id">
                                            <td>
                                                <button type="button" @click="verAviso(avisoObra.id)" class="btn btn-success btn-sm">
                                                <i class="icon-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm" @click="eliminarContrato(avisoObra)">
                                                    <i class="icon-trash"></i>
                                                </button>
                                                <button type="button" class="btn btn-warning btn-sm" @click="actualizarContrato(avisoObra.id)">
                                                    <i class="icon-pencil"></i>
                                                </button>
                                            </td>
                                            <td v-text="avisoObra.clave"></td>
                                            <td v-text="avisoObra.contratista"></td>
                                            <td v-text="avisoObra.proyecto"></td>
                                            <td v-text="avisoObra.f_ini"></td>
                                            <td v-text="avisoObra.f_fin"></td>
                                        </tr>                               
                                    </tbody>
                                </table>
                            </div>
                            <nav>
                                <!--Botones de paginacion -->
                                <ul class="pagination">
                                    <li class="page-item" v-if="pagination.current_page > 1">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                    </li>
                                    <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                    </li>
                                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </template>
                    
                    <!-- Div Card Body para nuevo registro -->
                    <template v-else-if="listado == 0">
                        <div class="card-body"> 
                            <div class="form-group row border">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="">Contratista </label>
                                        <v-select 
                                            :on-search="selectContratista"
                                            label="nombre"
                                            :options="arrayContratista"
                                            placeholder="Buscar contratista..."
                                            :onChange="getDatosContratista"
                                        >
                                        </v-select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Clave </label>
                                    <input type="text" class="form-control" v-model="clave" placeholder="CLV-00-00">
                                </div> 
                                <div class="col-md-3">
                                    <label for="">Fecha de inicio </label>
                                    <input type="date" class="form-control" v-model="f_ini">
                                </div>
                                <div class="col-md-3">
                                    <label for="">Fecha de termino </label>
                                    <input type="date" class="form-control" v-model="f_fin">
                                </div>
                                <div class="col-md-3">
                                    <label for="">% Anticipo </label>
                                    <input type="number" class="form-control" min="0" max="100" v-model="anticipo" v-on:keypress="isNumber($event)">
                                </div>
                                <div class="col-md-3">
                                    <label for="">% Costo Indirecto </label>
                                    <input type="number" class="form-control" min="0" max="100" v-model="costo_indirecto_porcentaje" v-on:keypress="isNumber($event)">
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Fraccionamiento </label>
                                        <v-select 
                                            :on-search="selectFraccionamiento"
                                            label="nombre"
                                            :options="arrayFraccionamientos"
                                            placeholder="Buscar proyecto..."
                                            :onChange="getDatosFraccionamiento"
                                            
                                        >
                                        </v-select>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Manzana</label>
                                        <div class="form-inline">
                                        <select class="form-control" v-model="manzana" @click="selectLotes(manzana,fraccionamiento_id)">
                                            <option value="">Seleccione</option>
                                            <option v-for="manzana in arrayManzanaLotes" :key="manzana.id" :value="manzana.manzana" v-text="manzana.manzana"></option>
                                        </select>
                                        </div>
                                    </div>
                                </div>

                                 <div class="col-md-12">
                                    <!-- Div para mostrar los errores que mande validerFraccionamiento -->
                                    <div v-show="errorAvisoObra" class="form-group row div-error">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMostrarMsjAvisoObra" :key="error" v-text="error">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="form-group row border">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Lote</label> 
                                        <div class="form-inline">
                                        <select class="form-control" v-model="lote_id" @click="selectDatosLotes(lote_id)">
                                            <option value="0">Seleccione</option>
                                            <option v-for="lotes in arrayLotes" :key="lotes.id" :value="lotes.id" v-text="lotes.num_lote"></option>
                                        </select>
                                           
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Descripcion <span style="color:red;" v-show="descripcion==''">(*Ingrese)</span> </label>
                                        <input type="text" class="form-control" v-model="descripcion"  placeholder="Descripcion">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Costo directo<span style="color:red;" v-show="costo_directo==0">(*Ingrese)</span></label>
                                        <input type="text" class="form-control" v-model="costo_directo" v-on:keypress="isNumber($event)" placeholder="Costo directo">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Costo indirecto <span style="color:red;" v-show="costo_indirecto==0">(*Ingrese)</span></label>
                                        <p>{{ costo_indirecto=costo_directo*costo_indirecto_porcentaje/100}}</p>
                                        <!--<input type="text" class="form-control" readonly v-model="costo_indirecto" v-on:keypress="isNumber($event)" placeholder="Costo indirecto">-->
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <div class="form-group">
                                        <button @click="agregarLote()" class="btn btn-success form-control btnagregar"><i class="icon-plus"></i></button>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="form-group row">
                                <div class="table-responsive col-md-12">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Opciones</th>
                                                <th>Descripcion</th>
                                                <th>Lote</th>
                                                <th>M&sup2;</th>
                                                <th>Costo Directo</th>
                                                <th>Costo Indirecto</th>
                                                <th>Importe</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="arrayAvisoObraLotes.length">
                                            <tr v-for="(detalle,index) in arrayAvisoObraLotes" :key="detalle.id">
                                                <td>
                                                    <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm">
                                                        <i class="icon-close"></i>
                                                    </button>
                                                </td>
                                                <td>
                                                    <input v-model="detalle.descripcion" type="text" class="form-control">
                                                </td>
                                                <td v-text="detalle.lote">
                                                    
                                                </td>
                                                <td v-text="detalle.superficie">
                                                   
                                                </td>
                                                <td>
                                                    <input v-model="detalle.costo_directo" type="text" class="form-control">
                                                </td>
                                                <td>
                                                    {{ detalle.costo_indirecto=detalle.costo_directo*costo_indirecto_porcentaje/100}}
                                                </td>
                                                <td>
                                                    {{parseFloat(detalle.costo_directo) + parseFloat(detalle.costo_indirecto)}}
                                                  <!-- <input readonly v-model="detalle.importe" type="text" class="form-control">  -->
                                                </td>
                                            </tr>
                                  
                                            <tr style="background-color: #CEECF5;">
                                               
                                                <td align="right" colspan="5"> <strong>${{ total_costo_directo=totalCostoDirecto}}</strong> </td>
                                                 <td align="right"> <strong>${{ total_costo_indirecto=totalCostoIndirecto}}</strong> </td>
                                                 <td align="right"> <strong>${{ total_importe=totalImporte}}</strong> </td>
                                            </tr>
                                        </tbody>

                                        <tbody v-else>
                                            <tr>
                                                <td colspan="7">
                                                    No hay lotes seleccionados
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Parametros para contrato -->
                            <div class="form-group row border"  v-if="arrayAvisoObraLotes.length">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Tipo</label> 
                                        <div class="form-inline">
                                        <select class="form-control" v-model="tipo">
                                            <option value="Vivienda">Vivienda</option>
                                            <option value="Urbanización">Urbanización</option>
                                            <option value="Casa club">Casa club</option>
                                            <option value="Caseta">Caseta</option>
                                            <option value="Locales">Locales</option>
                                        </select>
                                           
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label>IVA</label> 
                                        <div class="form-inline">
                                        <select class="form-control" v-model="iva">
                                            <option value="0">No</option>
                                            <option value="1">Si</option>
                                        </select>
                                           
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Descripcion corta para contrato<span style="color:red;" v-show="descripcion_corta==''">(*Ingrese)</span> </label>
                                        <input type="text" class="form-control" v-model="descripcion_corta"  placeholder="Descripcion corta">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Descripcion larga para contrato<span style="color:red;" v-show="descripcion_larga==''">(*Ingrese)</span> </label>
                                        <input type="text" class="form-control" v-model="descripcion_larga"  placeholder="Descripcion larga">
                                    </div>
                                </div>
                                
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-secondary" @click="ocultarDetalle()"> Cerrar </button>
                                    <button type="button" class="btn btn-primary" @click="registrarAvisoObra()"> Guardar </button>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Div Card Body para actualizar registros -->
                    <template v-else-if="listado == 3">
                        <div class="card-body"> 
                            <div class="form-group row border">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="">Contratista </label>
                                        <v-select 
                                            :on-search="selectContratista"
                                            label="nombre"
                                            :options="arrayContratista"
                                            placeholder="Buscar contratista..."
                                            :onChange="getDatosContratista"
                                        >
                                        </v-select>
                                         <input type="text" class="form-control" readonly  v-model="contratista">
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <label for="">Clave </label>
                                    <input type="text" class="form-control" v-model="clave" placeholder="CLV-00-00">
                                </div> 
                                <div class="col-md-3">
                                    <label for="">Fecha de inicio </label>
                                    <input type="date" class="form-control" v-model="f_ini">
                                </div>
                                <div class="col-md-3">
                                    <label for="">Fecha de termino </label>
                                    <input type="date" class="form-control" v-model="f_fin">
                                </div>
                                <div class="col-md-3">
                                    <label for="">% Anticipo </label>
                                    <input type="number" class="form-control" min="0" max="100" v-model="anticipo" v-on:keypress="isNumber($event)">
                                </div>
                                <div class="col-md-3">
                                    <label for="">% Costo Indirecto </label>
                                    <input type="number" class="form-control" min="0" max="100" v-model="costo_indirecto_porcentaje" v-on:keypress="isNumber($event)">
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                       <label for="">Fraccionamiento </label>
                                        <input type="text" class="form-control" readonly  v-model="fraccionamiento">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Manzana</label>
                                        <div class="form-inline">
                                        <select class="form-control" v-model="manzana" @click="selectLotes(manzana,fraccionamiento_id)">
                                            <option value="">Seleccione</option>
                                            <option v-for="manzana in arrayManzanaLotes" :key="manzana.id" :value="manzana.manzana" v-text="manzana.manzana"></option>
                                        </select>
                                        </div>
                                    </div>
                                </div>

                                 <div class="col-md-12">
                                    <!-- Div para mostrar los errores que mande validerFraccionamiento -->
                                    <div v-show="errorAvisoObra" class="form-group row div-error">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMostrarMsjAvisoObra" :key="error" v-text="error">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="form-group row border">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Lote</label> 
                                        <div class="form-inline">
                                        <select class="form-control" v-model="lote_id" @click="selectDatosLotes(lote_id)">
                                            <option value="0">Seleccione</option>
                                            <option v-for="lotes in arrayLotes" :key="lotes.id" :value="lotes.id" v-text="lotes.num_lote"></option>
                                        </select>
                                           
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Descripcion <span style="color:red;" v-show="descripcion==''">(*Ingrese)</span> </label>
                                        <input type="text" class="form-control" v-model="descripcion"  placeholder="Descripcion">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Costo directo<span style="color:red;" v-show="costo_directo==0">(*Ingrese)</span></label>
                                        <input type="text" class="form-control" v-model="costo_directo" v-on:keypress="isNumber($event)" placeholder="Costo directo">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Costo indirecto <span style="color:red;" v-show="costo_indirecto==0">(*Ingrese)</span></label>
                                        <p>{{ costo_indirecto=costo_directo*costo_indirecto_porcentaje/100}}</p>
                                        <!--<input type="text" class="form-control" readonly v-model="costo_indirecto" v-on:keypress="isNumber($event)" placeholder="Costo indirecto">-->
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <div class="form-group">
                                        <button @click="registrarLote()" class="btn btn-success form-control btnagregar"><i class="icon-plus"></i></button>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="form-group row">
                                <div class="table-responsive col-md-12">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Opciones</th>
                                                <th>Descripcion</th>
                                                <th>Lote</th>
                                                <th>M&sup2;</th>
                                                <th>Costo Directo</th>
                                                <th>Costo Indirecto</th>
                                                <th>Importe</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="arrayAvisoObraLotes.length">
                                            <tr v-for="detalle in arrayAvisoObraLotes" :key="detalle.id">
                                                <td>
                                                    <button @click="eliminarLote(detalle)" type="button" class="btn btn-danger btn-sm">
                                                        <i class="icon-close"></i>
                                                    </button>
                                                </td>
                                                <td>
                                                    <input v-model="detalle.descripcion" type="text" class="form-control">
                                                </td>
                                                <td v-text="detalle.lote">
                                                    
                                                </td>
                                                <td v-text="detalle.superficie">
                                                   
                                                </td>
                                                <td>
                                                    <input v-model="detalle.costo_directo" type="text" class="form-control">
                                                </td>
                                                <td>
                                                    {{ detalle.costo_indirecto=detalle.costo_directo*costo_indirecto_porcentaje/100}}
                                                </td>
                                                <td>
                                                    {{parseFloat(detalle.costo_directo) + parseFloat(detalle.costo_indirecto)}}
                                                  <!-- <input readonly v-model="detalle.importe" type="text" class="form-control">  -->
                                                </td>
                                            </tr>
                                  
                                            <tr style="background-color: #CEECF5;">
                                               
                                                <td align="right" colspan="5"> <strong>${{ total_costo_directo=totalCostoDirecto}}</strong> </td>
                                                 <td align="right"> <strong>${{ total_costo_indirecto=totalCostoIndirecto}}</strong> </td>
                                                 <td align="right"> <strong>${{ total_importe=totalImporte}}</strong> </td>
                                            </tr>
                                        </tbody>

                                        <tbody v-else>
                                            <tr>
                                                <td colspan="7">
                                                    No hay lotes seleccionados
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- Parametros para contrato -->
                            <div class="form-group row border"  v-if="arrayAvisoObraLotes.length">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Tipo</label> 
                                        <div class="form-inline">
                                        <select class="form-control" v-model="tipo">
                                            <option value="Vivienda">Vivienda</option>
                                            <option value="Urbanización">Urbanización</option>
                                            <option value="Casa club">Casa club</option>
                                            <option value="Caseta">Caseta</option>
                                            <option value="Locales">Locales</option>
                                        </select>
                                           
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label>IVA</label> 
                                        <div class="form-inline">
                                        <select class="form-control" v-model="iva">
                                            <option value="0">No</option>
                                            <option value="1">Si</option>
                                        </select>
                                           
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Descripcion corta para contrato<span style="color:red;" v-show="descripcion_corta==''">(*Ingrese)</span> </label>
                                        <input type="text" class="form-control" v-model="descripcion_corta"  placeholder="Descripcion corta">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Descripcion larga para contrato<span style="color:red;" v-show="descripcion_larga==''">(*Ingrese)</span> </label>
                                        <input type="text" class="form-control" v-model="descripcion_larga"  placeholder="Descripcion larga">
                                    </div>
                                </div>
                                
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-secondary" @click="ocultarDetalle()"> Cerrar </button>
                                    <button type="button" class="btn btn-primary" @click="actualizarAvisoObra()"> Actualizar </button>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!--Div para ver detalle del aviso -->
                    <template v-else-if="listado == 2">
                        <div class="card-body"> 
                            <div class="form-group row border">
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label style="color:#2271b3;" for=""><strong> Contratista </strong></label>
                                        <p v-text="contratista"></p>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label style="color:#2271b3;" for=""><strong>Clave</strong> </label>
                                    <p v-text="clave"></p>
                                </div> 
                                <div class="col-md-3">
                                    <label style="color:#2271b3;" for=""><strong>Fecha de inicio</strong></label>
                                    <p v-text="f_ini"></p>
                                </div>
                                <div class="col-md-3">
                                    <label style="color:#2271b3;" for=""><strong>Fecha de termino </strong></label>
                                    <p v-text="f_fin"></p>
                                </div>
                                <div class="col-md-3">
                                    <label style="color:#2271b3;" for=""><strong>% Anticipo </strong></label>
                                    <p v-text="anticipo+'%'"></p>
                                </div>
                                <div class="col-md-3">
                                    <label style="color:#2271b3;" for=""><strong>% Costo Indirecto </strong></label>
                                    <p v-text="costo_indirecto_porcentaje+'%'"></p>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label style="color:#2271b3;" for=""><strong>Fraccionamiento </strong></label>
                                        <p v-text="fraccionamiento"></p>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label style="color:#2271b3;"><strong>Total de Anticipo</strong></label>
                                        <div class="form-inline">
                                        <p v-text="'$'+formatNumber(total_anticipo)"></p>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>

                            <div class="form-group row">
                                <div class="table-responsive col-md-12">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Descripcion</th>
                                                <th>Lote</th>
                                                <th>Manzana</th>
                                                <th>M&sup2;</th>
                                                <th>Costo Directo</th>
                                                <th>Costo Indirecto</th>
                                                <th>Importe</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="arrayAvisoObraLotes.length">
                                            <tr v-for="detalle in arrayAvisoObraLotes" :key="detalle.id">
                                                <td v-text="detalle.descripcion"></td>
                                                <td v-text="detalle.lote"></td>
                                                <td v-text="detalle.manzana"></td>
                                                <td v-text="detalle.construccion"></td>
                                                <td v-text="'$'+formatNumber(detalle.costo_directo)"></td>
                                                <td v-text="'$'+formatNumber(detalle.costo_indirecto)"></td>
                                                <td>
                                                    {{'$'+formatNumber(parseFloat(detalle.costo_directo) + parseFloat(detalle.costo_indirecto))}}
                                                  <!-- <input readonly v-model="detalle.importe" type="text" class="form-control">  -->
                                                </td>
                                            </tr>
                                  
                                            <tr style="background-color: #CEECF5;">
                                               
                                                <td align="right" colspan="5"> <strong>${{ formatNumber(total_costo_directo=totalCostoDirecto)}}</strong> </td>
                                                <td align="right"> <strong>${{ formatNumber(total_costo_indirecto=totalCostoIndirecto)}}</strong> </td>
                                                <td align="right"> <strong>${{ formatNumber(total_importe=totalImporte)}}</strong> </td>
                                            </tr>
                                        </tbody>

                                        <tbody v-else>
                                            <tr>
                                                <td colspan="7">
                                                    No hay lotes seleccionados
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-secondary" @click="ocultarDetalle()"> Cerrar </button>
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
    import vSelect from 'vue-select';
    export default {
        data(){
            return{
                id:0,
                aviso_id:0,
                contratista_id:0,
                etapa_id:0,
                fraccionamiento_id : 0,
                num_etapa : 0,
                descripcion_larga:'',
                descripcion_corta:'',
                tipo:'Vivienda',
                iva:0,
                clave:'',
                total_importe:0.0,
                total_costo_directo:0.0,
                total_costo_indirecto:0.0,
                anticipo:0,
                costo_indirecto_porcentaje:0,
                total_anticipo:0,
                f_ini : new Date().toISOString().substr(0, 10),
                f_fin : '',
                arrayAvisoObra : [],
                arrayAvisoObraLotes : [],
                arrayContratista : [],
                modal : 0,
                listado:1,
                tituloModal : '',
                tipoAccion: 0,
                errorAvisoObra : 0,
                errorMostrarMsjAvisoObra : [],
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'ini_obras.clave', 
                buscar : '',
                arrayFraccionamientos : [],
                arrayLotes:[],
                arrayManzanaLotes: [],
                arrayDatosLotes: [],
                lote_id:0,
                lote:'',
                manzana:'',
                construccion:'',
                costo_directo:0,
                costo_indirecto:0,
                descripcion: '',
                manzana: '',
                modelo:'',
                importe: 0.0,
                contratista:'',
                fraccionamiento:''
                
            }
        },
        components:{
            vSelect
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
            },
        totalCostoDirecto: function(){
            var resultado_costo_directo =0.0;
            for(var i=0;i<this.arrayAvisoObraLotes.length;i++){
                resultado_costo_directo = parseFloat(resultado_costo_directo) + parseFloat(this.arrayAvisoObraLotes[i].costo_directo)
            }
            return resultado_costo_directo;
        },
        totalCostoIndirecto: function(){
            var resultado_costo_indirecto =0.0;
            for(var i=0;i<this.arrayAvisoObraLotes.length;i++){
                resultado_costo_indirecto = parseFloat(resultado_costo_indirecto) + parseFloat(this.arrayAvisoObraLotes[i].costo_indirecto) 
            }
            return resultado_costo_indirecto;
        },

        totalImporte: function(){
            var resultado_importe_total =0.0;
            for(var i=0;i<this.arrayAvisoObraLotes.length;i++){
                resultado_importe_total = parseFloat(resultado_importe_total) + parseFloat(this.arrayAvisoObraLotes[i].costo_directo) + parseFloat(this.arrayAvisoObraLotes[i].costo_indirecto)
            }
            return resultado_importe_total;
        }


        },
       
        methods : {
            /**Metodo para mostrar los registros */
            listarAvisos(page, buscar, criterio){
                let me = this;
                var url = '/iniobra?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayAvisoObra = respuesta.ini_obra.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectContratista(search, loading){
                let me = this;
                loading(true)

                var url = '/select_contratistas2?filtro='+search;
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    q: search
                    me.arrayContratista = respuesta.contratistas;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getDatosContratista(val1){
                let me = this;
                me.loading = true;
                me.contratista_id = val1.id;
                me.contratista = val1.nombre;
            },
             selectFraccionamiento(search, loading){
                let me = this;
                loading(true)

                var url = '/select_fraccionamiento2?filtro='+search;
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    q: search
                    me.arrayFraccionamientos = respuesta.fraccionamientos;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getDatosFraccionamiento(val1){
                let me = this;
                me.loading = true;
                me.fraccionamiento_id = val1.id;
                me.selectManzanaLotes(me.fraccionamiento_id);
            },
            selectManzanaLotes(buscar){
                let me = this;
              
                me.arrayManzanaLotes=[];
                var url = '/select_manzana_lotes?buscar='+buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayManzanaLotes = respuesta.lotesManzana;
                    
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectLotes(buscar , buscar2){
                let me = this;
              
                me.arrayLotes=[];
                var url = '/select_lotes_obra?buscar='+buscar + '&buscar2=' + buscar2;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLotes = respuesta.lotes;
                    
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectDatosLotes(buscar){
                let me = this;
              
                me.arrayDatosLotes = [];
                var url = '/select_datos_lotes?buscar='+buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayDatosLotes = respuesta.lotesDatos;
                    me.lote = me.arrayDatosLotes[0].num_lote;
                    me.construccion = me.arrayDatosLotes[0].construccion;
                    me.manzana = me.arrayDatosLotes[0].manzana;
                    me.modelo=me.arrayDatosLotes[0].modelo;
                    me.descripcion=me.arrayDatosLotes[0].modelo;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page, buscar, criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarAvisos(page,buscar,criterio);
            },
            encuentra(id){
                var sw=0;
                for(var i=0;i<this.arrayAvisoObraLotes.length;i++)
                {
                    if(this.arrayAvisoObraLotes[i].lote_id == id)
                    {
                        sw=true;
                    }
                }

                return sw;
            },
            agregarLote(){
                let me = this;
                if(me.descripcion == '' || me.costo_directo==0 || me.costo_indirecto==0){

                }else{
                    if(me.encuentra(me.lote_id)){
                         swal({
                        type: 'error',
                        title: 'Error',
                        text: 'Este lote ya se encuentra agregado',
                        })
                    }else{
                    me.costo_indirecto = parseFloat(me.costo_directo) * parseFloat(me.costo_indirecto_porcentaje)/100;
                    me.importe = parseFloat(me.costo_directo) + parseFloat(me.costo_indirecto);
                    me.arrayAvisoObraLotes.push({
                    lote_id: me.lote_id,
                    lote: me.lote,
                    superficie: me.construccion,
                    manzana: me.manzana,
                    descripcion: me.descripcion,
                    importe: me.importe,
                    modelo:me.modelo,
                    costo_directo: parseFloat(me.costo_directo),
                    costo_indirecto: parseFloat(me.costo_indirecto)
                    });
                    me.lote = '';
                    me.lote_id =0;
                    me.construccion = 0;
                    me.manzana='';
                    me.descripcion='';
                    me.costo_directo = 0;
                    me.costo_indirecto = 0;
                    me.modelo='';
                    }
                }

            },
            registrarLote(){
                let me = this;
                if(me.descripcion == '' || me.costo_directo==0 || me.costo_indirecto==0){

                }else{
                    if(me.encuentra(me.lote_id)){
                         swal({
                        type: 'error',
                        title: 'Error',
                        text: 'Este lote ya se encuentra agregado',
                        })
                    }else{
                    me.costo_indirecto = parseFloat(me.costo_directo) * parseFloat(me.costo_indirecto_porcentaje)/100;
                    me.importe = parseFloat(me.costo_directo) + parseFloat(me.costo_indirecto);
                   
                    axios.post('/iniobra/lote/registrar',{
                        'id': this.id,
                        'lote': this.lote,
                        'manzana' : this.manzana,
                        'modelo' : this.modelo,
                        'superficie' : this.construccion,
                        'costo_directo' : this.costo_directo,
                        'costo_indirecto' : this.costo_indirecto,
                        'importe' : this.importe,
                        'descripcion' : this.descripcion,
                        'lote_id' : this.lote_id
                    }).then(function (response){
                        //Obtener detalle
                            me.arrayAvisoObraLotes=[];
                            var urld = '/iniobra/obtenerDetalles?id=' + me.id;
                            axios.get(urld).then(function (response) {
                                var respuesta = response.data;
                                me.arrayAvisoObraLotes = respuesta.detalles;
                            })
                            .catch(function (error) {
                                console.log(error);
                            });
                      
                    }).catch(function (error){
                        console.log(error);
                    });

                    me.lote = '';
                    me.lote_id =0;
                    me.construccion = 0;
                    me.manzana='';
                    me.descripcion='';
                    me.costo_directo = 0;
                    me.costo_indirecto = 0;
                    me.modelo='';
                    }
                }

            },
             eliminarLote(data =[]){
                //this.lote_id=data['id'];
                swal({
                title: '¿Desea remover este lote?',
                text: "Esta acción no se puede revertir!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si, eliminar!'
                }).then((result) => {
                if (result.value) {
                    let me = this;

                axios.delete('/iniobra/lote/eliminar', 
                        {params: {'id': data['id']}}).then(function (response){
                        
                         //Obtener detalle
                            me.arrayAvisoObraLotes=[];
                            var urld = '/iniobra/obtenerDetalles?id=' + me.id;
                            axios.get(urld).then(function (response) {
                                var respuesta = response.data;
                                me.arrayAvisoObraLotes = respuesta.detalles;
                            })
                            .catch(function (error) {
                                console.log(error);
                            });
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            eliminarDetalle(index){
                let me = this;
                me.arrayAvisoObraLotes.splice(index,1);
            },
            /**Metodo para registrar  */
            registrarAvisoObra(){
                if(this.validarAviso()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                let me = this;
                me.total_anticipo=(me.anticipo/100)*me.total_importe;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/iniobra/registrar',{
                    'fraccionamiento_id': this.fraccionamiento_id,
                    'contratista_id': this.contratista_id,
                    'clave': this.clave,
                    'f_ini': this.f_ini,
                    'f_fin': this.f_fin,
                    'total_importe' :this.total_importe,
                    'total_costo_directo':this.total_costo_directo,
                    'total_costo_indirecto':this.total_costo_indirecto,
                    'anticipo':this.anticipo,
                    'total_anticipo':this.total_anticipo,
                    'data':this.arrayAvisoObraLotes,
                    'costo_indirecto_porcentaje':this.costo_indirecto_porcentaje,
                    'tipo':this.tipo,
                    'iva':this.iva,
                    'descripcion_larga':this.descripcion_larga,
                    'descripcion_corta':this.descripcion_corta
                }).then(function (response){
                    me.listado=1;
                    me.limpiarDatos();
                    me.listarAvisos(1,'','ini_obras.clave'); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Etapa agregada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },

             /**Metodo para actualizar  */
            actualizarAvisoObra(){
                if(this.validarAviso()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                let me = this;
                me.total_anticipo=(me.anticipo/100)*me.total_importe;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.put('/iniobra/actualizar',{
                    'id':this.id,
                    'fraccionamiento_id': this.fraccionamiento_id,
                    'contratista_id': this.contratista_id,
                    'clave': this.clave,
                    'f_ini': this.f_ini,
                    'f_fin': this.f_fin,
                    'total_importe' :this.total_importe,
                    'total_costo_directo':this.total_costo_directo,
                    'total_costo_indirecto':this.total_costo_indirecto,
                    'anticipo':this.anticipo,
                    'total_anticipo':this.total_anticipo,
                    'data':this.arrayAvisoObraLotes,
                    'costo_indirecto_porcentaje':this.costo_indirecto_porcentaje,
                    'tipo':this.tipo,
                    'iva':this.iva,
                    'descripcion_larga':this.descripcion_larga,
                    'descripcion_corta':this.descripcion_corta
                }).then(function (response){
                    me.listado=1;
                    me.limpiarDatos();
                    me.listarAvisos(1,'','ini_obras.clave'); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Contrato actualizado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            
             limpiarBusqueda(){
                let me=this;
                me.buscar= "";
            },
  

            limpiarDatos(){
                this.contratista_id=0;
                this.f_fin='';
                this.clave='';
                this.fraccionamiento_id=0;
                this.anticipo=0;
                this.total_anticipo=0;
                this.total_importe=0;
                this.total_costo_directo=0;
                this.total_costo_indirecto=0;
                this.manzana='';
                this.lote='';
                this.modelo='';
                this.construccion=0;
                this.descripcion='';
                this.arrayAvisoObraLotes=[];
                this.arrayLotes=[];
                this.arrayDatosLotes=[];
                this.arrayManzanaLotes=[];
                this.descripcion_larga='';
                this.descripcion_corta='';
                this.iva=0;
                this.tipo='Vivienda';
            },
            eliminarContrato(data =[]){
                this.id=data['id'];
                swal({
                title: '¿Desea eliminar?',
                text: "Esta acción no se puede revertir!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si, eliminar!'
                }).then((result) => {
                if (result.value) {
                    let me = this;

                axios.delete('/iniobra/contrato/eliminar', 
                        {params: {'id': this.id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Contrato borrada correctamente.',
                        'success'
                        )
                         me.listarAvisos(1,'','ini_obras.clave'); 
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            validarAviso(){
                this.errorAvisoObra=0;
                this.errorMostrarMsjAvisoObra=[];

                if(this.contratista_id==0) //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjAvisoObra.push("Seleccionar un contratista.");
                    if(this.fraccionamiento_id==0) //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjAvisoObra.push("Seleccionar un fraccionamiento.");
                if(this.clave=='') //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjAvisoObra.push("Ingresar clave.");
                if(this.arrayAvisoObraLotes.length<=0) //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjAvisoObra.push("No se ha ingresado ningun lote");

                if(this.errorMostrarMsjAvisoObra.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorAvisoObra = 1;

                return this.errorAvisoObra;
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
            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            },
            mostrarDetalle(){
                this.limpiarDatos();
                this.listado=0;
            },
            ocultarDetalle(){
                this.listado=1;
            },
            verAviso(id){
                let me= this;
                this.listado=2;

                //Obtener datos de cabecera
                var arrayAvisoT=[];
                var url = '/iniobra/obtenerCabecera?id=' + id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayAvisoT = respuesta.ini_obra;

                    me.contratista= me.arrayAvisoT[0]['contratista'];
                    me.clave= me.arrayAvisoT[0]['clave'];
                    me.f_ini= me.arrayAvisoT[0]['f_ini'];
                    me.f_fin= me.arrayAvisoT[0]['f_fin'];
                    me.anticipo= me.arrayAvisoT[0]['anticipo'];
                    me.fraccionamiento= me.arrayAvisoT[0]['proyecto'];
                    me.total_anticipo = me.arrayAvisoT[0]['total_anticipo'];
                    me.costo_indirecto_porcentaje=me.arrayAvisoT[0]['costo_indirecto_porcentaje'];
                })
                .catch(function (error) {
                    console.log(error);
                });
                //Obtener detalle
                var arrayAvisoT=[];
                var urld = '/iniobra/obtenerDetalles?id=' + id;
                axios.get(urld).then(function (response) {
                    var respuesta = response.data;
                    me.arrayAvisoObraLotes = respuesta.detalles;
                })
                .catch(function (error) {
                    console.log(error);
                });

            },

            actualizarContrato(id){
                let me= this;
                this.listado=3;

                //Obtener datos de cabecera
                var arrayAvisoT=[];
                var url = '/iniobra/obtenerCabecera?id=' + id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayAvisoT = respuesta.ini_obra;

                    me.contratista_id= me.arrayAvisoT[0]['contratista_id'];
                    me.clave= me.arrayAvisoT[0]['clave'];
                    me.f_ini= me.arrayAvisoT[0]['f_ini'];
                    me.f_fin= me.arrayAvisoT[0]['f_fin'];
                    me.anticipo= me.arrayAvisoT[0]['anticipo'];
                    me.fraccionamiento_id= me.arrayAvisoT[0]['fraccionamiento_id'];
                    me.fraccionamiento= me.arrayAvisoT[0]['proyecto'];
                    me.total_anticipo = me.arrayAvisoT[0]['total_anticipo'];
                    me.costo_indirecto_porcentaje=me.arrayAvisoT[0]['costo_indirecto_porcentaje'];
                    me.contratista= me.arrayAvisoT[0]['contratista'];
                    me.descripcion_larga=me.arrayAvisoT[0]['descripcion_larga'];
                    me.descripcion_corta=me.arrayAvisoT[0]['descripcion_corta'];
                    me.tipo=me.arrayAvisoT[0]['tipo'];
                    me.iva=me.arrayAvisoT[0]['iva'];
                    me.selectManzanaLotes(me.fraccionamiento_id);
                    me.id=id;
                  
                })
                .catch(function (error) {
                    console.log(error);
                });
                //Obtener detalle
                var arrayAvisoT=[];
                var urld = '/iniobra/obtenerDetalles?id=' + id;
                axios.get(urld).then(function (response) {
                    var respuesta = response.data;
                    me.arrayAvisoObraLotes = respuesta.detalles;
                })
                .catch(function (error) {
                    console.log(error);
                });

            },
           
        },
        mounted() {
            this.listarAvisos(1,this.buscar,this.criterio);
            this.selectManzanaLotes(this.fraccionamiento_id);
            this.selectLotes(this.manzana,this.fraccionamiento_id);
            this.selectDatosLotes(this.lote_id);
          
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
        position: absolute !important;
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
    .scroll-box {
            overflow-x: scroll;
            width: auto;
            padding: 1rem
    }
    @media (min-width: 600px){
        .btnagregar{
        margin-top: 2rem;
        }
    }
</style>
