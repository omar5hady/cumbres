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
                        <i class="fa fa-align-justify"></i> Mis prospectos
                        <!--   Boton agregar    -->
                        <button type="button" @click="mostrarDetalle()" class="btn btn-secondary" v-if="listado==1">
                            <i class="icon-plus"></i>&nbsp;Agregar
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
                                <div class="col-md-12">
                                    <div class="form-group">
                                  <center> <h2>Prospecto</h2> </center>
                                    </div>
                                </div> 

                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="">Nombre </label>
                                    <input type="text" class="form-control" v-model="nombre" placeholder="Nombre">
                                    </div>
                                </div> 


                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="">Apellidos</label>
                                    <input type="text" class="form-control" v-model="apellidos" placeholder="Apellidos">
                                </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="">Sexo </label>
                                    <select class="form-control" v-model="sexo" >
                                            <option value="">Seleccione</option>
                                            <option value="F">Femenino</option>
                                            <option value="M">Masculino</option>
                                    </select>
                                </div>
                                </div>

                                  <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="">Telefono </label>
                                    <input type="text" pattern="\d*" class="form-control" v-on:keypress="isNumber($event)" v-model="telefono" placeholder="Telefono">
                                </div>
                                </div>

                                  <div class="col-md-4">
                                     <div class="form-group">
                                    <label for="">Celular </label>
                                    <input type="text" pattern="\d*" maxlength="7" class="form-control" v-on:keypress="isNumber($event)" v-model="celular" placeholder="Celular">
                                </div>
                                 </div>

                                 <div class="col-md-4">
                                     <div class="form-group">
                                    <label for="">Email personal</label>
                                    <input type="text" class="form-control" v-model="email" placeholder="E-mail">
                                </div>
                                 </div>

                                   <div class="col-md-8">
                                    <div class="form-group">
                                    <label for="">Empresa </label>
                                        <v-select 
                                            :on-search="selectEmpresaVueselect"
                                            label="nombre"
                                            :options="arrayEmpresa"
                                            placeholder="Buscar empresa..."
                                            :onChange="getDatosEmpresa"
                                        >
                                        </v-select>
                                </div>
                                </div>

                                
                                <div class="col-md-4">
                                     <div class="form-group">
                                    <label for="">Email institucional </label>
                                    <input type="text" class="form-control" v-model="email_inst" placeholder="E-mail">
                                </div>
                                 </div>


                                  <div class="col-md-2">
                                    <div class="form-group">
                                    <label for="">Fecha de nacimiento</label>
                                    <input type="date" class="form-control"  v-model="fecha_nac" >
                                </div>
                                </div>

                                 <div class="col-md-3">
                                     <div class="form-group">
                                    <label for="">CURP</label>
                                    <input type="text" class="form-control"  v-model="curp" placeholder="CURP">
                                </div>
                                 </div>

                                  <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">RFC</label>
                                        <input type="text" class="form-control"  v-model="rfc" placeholder="RFC">
                                    </div>
                                 </div>
                                       
                                <div align="left" class="col-md-1">
                                   <div class="form-group">
                                    <label for="">Homoclave</label>
                                         <input type="text" class="form-control"  v-model="homoclave" placeholder="AA0">
                                   </div>
                                </div>

                                 
                                 <div class="col-md-3">
                                     <div class="form-group">
                                    <label for="">NSS </label>
                                    <input type="text" pattern="\d*" class="form-control" v-on:keypress="isNumber($event)" v-model="nss" placeholder="NSS">
                                </div>
                                 </div>

                                
                            </div>

                <!--  apartado  de datos vive en casa , edo civil, conyuge-->
                           <div class="form-group row border" >    
                                        
                                <div class="col-md-3">
                                    <div class="form-group">
                                    <label for="">Vive en casa </label>
                                        <select class="form-control" v-model="tipo_casa" >
                                            <option value="0">seleccione</option>  
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                  <div class="form-group">
                                  <label for="">Estado civil</label>
                                    <select class="form-control" v-model="e_civil" >
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

                                <div class="col-md-2">
                                    <div class="bmd-form-group checkbox">
                                    <label for=""> Conyuge o coacreditado </label>
                                    <br>
                                   <input id="checkcoa" type="checkbox" v-model="coacreditado">
                                </div>
                                </div>

                                <div class="col-md-3" v-if="coacreditado==true">
                                    <div class="form-group">
                                        <label for="">Buscar coacreditado... </label>
                                        <v-select 
                                            :on-search="selectFraccionamientoVueselect"
                                            label="nombre"
                                            :options="arrayFraccionamientos"
                                            placeholder="Buscar..."
                                            :onChange="getDatosFraccionamiento"
                                        >
                                        </v-select>
                                    </div>
                                </div>

                                <div class="col-md-1"  v-if="coacreditado==true">
                                    <div class="form-group">
                                        <button @click="abrirModal('coacreditado','registrar')" class="btn btn-success form-control btnagregar"><i class="icon-plus"></i></button>
                                    </div>
                                </div>
                                
                            </div>

                 <!--  lugar de contacto , clasificacion y otros-->
                        <div class="form-group row border">
                              <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Lugar de contacto </label>
                                          <select class="form-control" v-model="clasificacion" >
                                            <option value="0">Seleccione</option>
                                            <option value="Asesor externo">Asesor externo</option>
                                            <option value="Oficina central">Oficina central</option>
                                            <option value="Modulo">Modulo</option>
                                            <option value="Pagina web">Pagina web</option>
                                            <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                    <label for="">Clasificacion </label>
                                    <select class="form-control" v-model="clasificacion" >
                                            <option value="1">No viable</option>
                                            <option value="2">Tipo A</option>
                                            <option value="3">Tipo B</option>
                                            <option value="4">Tipo C</option>
                                            <option value="5">Ventas</option>
                                            <option value="6">Cancelado</option>                               
                                    </select>
                                </div>
                                </div>
                                 
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Proyecto en el que esta interesado </label>
                                        <v-select 
                                            :on-search="selectFraccionamientoVueselect"
                                            label="nombre"
                                            :options="arrayFraccionamientos"
                                            placeholder="Buscar proyecto..."
                                            :onChange="getDatosFraccionamiento"
                                        >
                                        </v-select>
                                    </div>
                                </div>

                                 <div class="col-md-3">
                                     <div class="form-group">
                                  <label for="">Medio donde se entero de nosotros</label>
                                    <select class="form-control" v-model="publicidad_id" >
                                            <option value="0">seleccione</option>
                                             <option v-for="medios in arrayMediosPublicidad" :key="medios.id" :value="medios.id" v-text="medios.nombre"></option>    
                                    </select>
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

                                    
                            <div class="form-group">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-secondary" @click="ocultarDetalle()"> Cerrar </button>
                                    <button type="button" class="btn btn-primary" @click="registrarAvisoObra()"> Guardar </button>
                                </div>
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
                                        <p>{{ costo_indirecto=costo_directo*costo_indirecto_porcentaje/100 | currency}}</p>
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
                                                <th>Obra extra</th>
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
                                                <td style="text-align: right;" v-text="detalle.construccion">
                                                   
                                                </td>
                                                <td style="text-align: right;">
                                                    <input v-model="detalle.costo_directo" type="text" class="form-control">
                                                </td>
                                                <td style="text-align: right;">
                                                    {{ detalle.costo_indirecto=detalle.costo_directo*costo_indirecto_porcentaje/100 | currency}}
                                                </td>
                                                <td style="text-align: right;">
                                                    <input v-model="detalle.obra_extra" type="text" class="form-control">
                                                </td>
                                                <td style="text-align: right;">
                                                    {{parseFloat(detalle.costo_directo) + parseFloat(detalle.costo_indirecto) | currency}}
                                                  <!-- <input readonly v-model="detalle.importe" type="text" class="form-control">  -->
                                                </td>
                                            </tr>
                                  
                                            <tr style="background-color: #CEECF5;">
                                                
                                                <td align="right" colspan="4"> <strong>{{ total_construccion=totalConstruccion}}</strong> </td>
                                                <td align="right" > <strong>{{ total_costo_directo=totalCostoDirecto | currency}}</strong> </td>
                                                <td align="right"> <strong>{{ total_costo_indirecto=totalCostoIndirecto | currency}}</strong> </td>
                                                <td align="right" colspan="2"> <strong>{{ total_importe=totalImporte | currency}}</strong> </td>
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
                                                <th>Obra extra</th>
                                                <th>Importe</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="arrayAvisoObraLotes.length">
                                            <tr v-for="detalle in arrayAvisoObraLotes" :key="detalle.id">
                                                <td v-text="detalle.descripcion"></td>
                                                <td v-text="detalle.lote"></td>
                                                <td v-text="detalle.manzana"></td>
                                                <td style="text-align: right;" v-text="detalle.construccion"></td>
                                                <td style="text-align: right;" v-text="'$'+formatNumber(detalle.costo_directo)"></td>
                                                <td style="text-align: right;" v-text="'$'+formatNumber(detalle.costo_indirecto)"></td>
                                                <td style="text-align: right;" v-text="'$'+formatNumber(detalle.obra_extra)"></td>
                                                <td align="right">
                                                    {{'$'+formatNumber(parseFloat(detalle.costo_directo) + parseFloat(detalle.costo_indirecto))}}
                                                  <!-- <input readonly v-model="detalle.importe" type="text" class="form-control">  -->
                                                </td>
                                            </tr>
                                  
                                            <tr style="background-color: #CEECF5;">
                                                <td align="right" colspan="4"> <strong>{{ formatNumber(total_construccion)}}</strong> </td>
                                                <td align="right"> <strong>${{ formatNumber(total_costo_directo=totalCostoDirecto)}}</strong> </td>
                                                <td align="right"> <strong>${{ formatNumber(total_costo_indirecto=totalCostoIndirecto)}}</strong> </td>
                                                <td align="right" colspan="2"> <strong>${{ formatNumber(total_importe=totalImporte)}}</strong> </td>
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
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-secondary" @click="ocultarDetalle()"> Cerrar </button>
                                </div>
                                   <div class="col-md-9">
                                    <a class="btn btn-success" v-bind:href="'/iniobra/relacion/excel/'+ id " >
                                        <i></i>Exportar relacion en excel
                                    </a>
                                </div>
                                <div class="col-md-2">
                                    <a class="btn btn-primary" v-bind:href="'/iniobra/pdf/'+ id " >
                                        <i></i>Imprimir Contrato
                                    </a>
                                </div>
                            </div>
                        </div>
                    </template>
                    
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
           
             <!--Inicio del modal agregar/actualizar coacreditado-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form  action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                           

                               <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre </label>
                                     <div class="col-md-6">
                                    <input type="text" class="form-control" v-model="nombre_coa" placeholder="Nombre">
                                    </div>
                                </div> 

                               <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Apellidos</label>
                                 <div class="col-md-6">
                                    <input type="text" class="form-control" v-model="apellidos_coa" placeholder="Apellidos">
                                </div>
                                </div>

                               <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Telefono </label>
                                     <div class="col-md-3">
                                    <input type="text" pattern="\d*" maxlength="7" class="form-control" v-on:keypress="isNumber($event)" v-model="telefono_coa" placeholder="Telefono">
                                </div>
                                </div>

                               <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Extension </label>
                                    <div class="col-md-2">
                                    <input type="text" pattern="\d*" maxlength="3" class="form-control" v-on:keypress="isNumber($event)" v-model="extencion_coa" placeholder="ext">
                                </div>
                                </div>

                               <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Celular </label>
                                    <div class="col-md-3">
                                    <input type="text" pattern="\d*" maxlength="10" class="form-control" v-on:keypress="isNumber($event)" v-model="celular_coa" placeholder="Celular">
                                </div>
                                 </div>

                               <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Email personal</label>
                                     <div class="col-md-4">
                                    <input type="text" class="form-control"  v-model="email_coa" placeholder="email">
                                </div>
                                 </div>

                             

                               <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Email institucional </label>
                                         <div class="col-md-4">
                                    <input type="text" class="form-control" v-model="email_institucional_coa" placeholder="email">
                                </div>
                                </div>

                   
                               <div class="form-group row">                                 
                                    <label class="col-md-3 form-control-label" for="text-input">Sexo </label>
                                     <div class="col-md-2">
                                    <select class="form-control" v-model="sexo_coa" >
                                            <option value="F">Femenino</option>
                                            <option value="M">Masculino</option>
                                    </select>
                                </div>
                                </div>

                               <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de nacimiento</label>
                                        <div class="col-md-3">
                                    <input type="date" class="form-control"  v-model="fecha_nac_coa" >
                                </div>
                                </div>

                               <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">CURP</label>
                                       <div class="col-md-4">
                                    <input type="text" class="form-control"  v-model="curp_coa" placeholder="CURP">
                                      </div>
                                 </div>

                            
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">RFC</label>
                                    <div class="col-md-3">
                                        <input type="text" maxlength="10" style="text-transform:uppercase" v-model="rfc_coa" class="form-control" placeholder="RFC" :disabled="tipoAccion == 3">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" maxlength="3" style="text-transform:uppercase" v-model="homoclave_coa" class="form-control" placeholder="Homoclave" :disabled="tipoAccion == 3">
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">NSS </label>
                                     <div class="col-md-4">
                                    <input type="text" pattern="\d*" class="form-control" v-on:keypress="isNumber($event)" v-model="nss_coa" placeholder="NSS">
                                    </div>
                                 </div>

                               <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Vive en casa </label>
                                    <div class="col-md-3">
                                        <select class="form-control" v-model="tipo_casa_coa" >
                                            <option value="0">seleccione</option>  
                                        </select>
                                    </div>
                                </div>


                               <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Codigo postal </label>
                                     <div class="col-md-2">
                                    <input type="text" maxlength="5" class="form-control" v-on:keypress="isNumber($event)"  @keyup="selectColonias(cp)" v-model="cp_coa" placeholder="C.P">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Direccion</label>
                                     <div class="col-md-5">
                                     <input type="text" class="form-control" v-model="direccion_coa" placeholder="Direccion">
                                </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Colonia</label>
                                    <div class="col-md-6">
                                        <select class="form-control" v-model="colonia_coa">
                                            <option value="0">Seleccione</option>
                                            <option v-for="colonias in arrayColonias" :key="colonias.colonia" :value="colonias.colonia" v-text="colonias.colonia"></option>
                                        </select>
                                    </div>
                                </div>


                               <div class="form-group row">
                                  <label class="col-md-3 form-control-label" for="text-input">Estado civil</label>
                                    <div class="col-md-3">
                                        <select class="form-control" v-model="e_civil_coa" >
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


                                 
                                    <!-- Div para mostrar los errores que mande validerFraccionamiento -->
                                    <div v-show="errorAvisoObra" class="form-group row div-error">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMostrarMsjAvisoObra" :key="error" v-text="error">
                                            </div>
                                        </div>
                                    </div>
                               
 
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarEmpresa()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarEmpresa()">Actualizar</button>
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
    import vSelect from 'vue-select';
    export default {
        data(){
            return{
                id:0,
                clasificacion:0,
                nombre:'',
                apellidos:'',
                telefono : '',
                celular : '',
                email:'',
                email_institucional:'',
                nss:'',
                sexo:'',
                fecha_nac: '',
                curp:'',
                rfc:'',
                homoclave: '',
                e_civil: 0,
                tipo_casa:0,
                coacreditado: '',
                publicidad_id: 0,
                proyecto_interes_id: 0,
                empresa: '',


                nombre_coa:'',
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
                colonia: '',
                cp:'',
                direccion: '',


                arrayEmpresa: [],
                arrayColonias: [],

                anticipo:0,
                costo_indirecto_porcentaje:0,
                total_anticipo:0,
                f_ini : new Date().toISOString().substr(0, 10),
                f_fin : '',
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
                arrayFraccionamientosVue : [],
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
       
      

        totalImporte: function(){
            var resultado_importe_total =0.0;
            for(var i=0;i<this.arrayAvisoObraLotes.length;i++){
                resultado_importe_total = parseFloat(resultado_importe_total) + parseFloat(this.arrayAvisoObraLotes[i].costo_directo) + parseFloat(this.arrayAvisoObraLotes[i].costo_indirecto)
            }
            return resultado_importe_total;
        },

        totalConstruccion: function(){
            var resultado_construccion_total =0.0;
            for(var i=0;i<this.arrayAvisoObraLotes.length;i++){
                resultado_construccion_total = parseFloat(resultado_construccion_total) + parseFloat(this.arrayAvisoObraLotes[i].construccion)
            }
            return resultado_construccion_total;
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
                me.empresa = val1.nombre;
               
            }, 
            getDatosEmpresaCoa(val1){
                let me = this;
                me.loading = true;
                me.empresa_coa = val1.nombre;
               
            },
            
            selectColonias(search, loading){
                let me = this;
                loading(true)

                var url = '/select_colonias_vue?filtro='+search;
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    q: search
                    me.arrayColonias = respuesta.colonias;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            getDatosEmpresaCoa(val1){
                let me = this;
                me.loading = true;
                me.colonia = val1.colonia;
               
            },
            
             selectFraccionamientoVueselect(search, loading){
                let me = this;
                loading(true)

                var url = '/select_fraccionamiento2?filtro='+search;
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    q: search
                    me.arrayFraccionamientosVue = respuesta.fraccionamientos;
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

            selectColonias(buscar){
                let me = this;
                me.arrayColonias=[];
                var url = '/select_colonias?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayColonias = respuesta.colonias;
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
                    costo_indirecto: parseFloat(me.costo_indirecto),
                    obra_extra:0,
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
                        'lote_id' : this.lote_id,
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
                    'descripcion_corta':this.descripcion_corta,
                    'total_superficie':this.total_construccion
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
                    'descripcion_corta':this.descripcion_corta,
                    'total_superficie':this.total_construccion
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
                this.total_construccion=0;
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
                    me.total_construccion=me.arrayAvisoT[0]['total_superficie'];
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

            cerrarModal(){
                this.modal = 0;
            },


         abrirModal(modelo, accion,data =[]){
                switch(modelo){
                    case "coacreditado":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Conyuge o coacreditado';
                              
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal =1;
                                this.tituloModal='Actualizar Empresa';
                                this.tipoAccion=2;
                               
                                break;
                            }
                        }
                    }
                }
                this.selectColonias(this.cp);
                this.selectCiudades(this.estado);
            }

           
        },
        mounted() {
            this.listarAvisos(1,this.buscar,this.criterio);
            this.selectColonias(this.cp);
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
    }
   

</style>
