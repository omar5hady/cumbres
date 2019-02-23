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
                        <i class="fa fa-align-justify"></i> Simulacion de credito
                    </div>
                    <!-- Div Card Body para listar -->
                     <template v-if="listado == 1">
                        <div class="card-body"> 
                            <div class="form-group row">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <!--Criterios para el listado de busqueda -->
                                        <select class="form-control col-md-4" v-model="criterio" @click="limpiarBusqueda()">
                                            <option value="personal.nombre">Nombre</option>
                                            <option value="personal.rfc">RFC</option>
                                            <option value="clientes.curp">CURP</option>
                                            <option value="clientes.nss">NSS</option>
                                            <option value="clientes.proyecto">Proyecto</option>
                                        </select>
                                        <input  type="text" v-model="buscar" @keyup.enter="listarProspectos(1,buscar,criterio)" class="form-control">
                                        <button type="submit" @click="listarProspectos(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Nombre</th>
                                            <th>Celular</th>
                                            <th>Email</th>
                                            <th>RFC</th>
                                            <th>IMSS</th>
                                            <th>CURP </th>
                                            <th>Proyecto de interes</th>
                                            <th>Observaciones</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="prospecto in arrayProspectos" :key="prospecto.id">
                                            <td>
                                            
                                         
                                                <button title="Editar" type="button" class="btn btn-warning btn-sm" @click="actualizarProspectoBTN(prospecto)">
                                                    <i class="icon-pencil"></i>
                                                </button>
                                            </td>
                                            <td v-text="prospecto.nombre + ' ' + prospecto.apellidos "></td>
                                            <td >
                                                 <a title="Llamar" class="btn btn-dark" :href="'tel:'+prospecto.celular"><i class="fa fa-phone fa-lg"></i></a>
                                                 <a title="Enviar whatsapp" class="btn btn-success" :href="'https://api.whatsapp.com/send?phone=+52'+prospecto.celular+'&text=Hola'"><i class="fa fa-whatsapp fa-lg"></i></a>
                                                 
                                            </td>
                                            <td v-if="prospecto.email_institucional == null"> 
                                                <a title="Enviar correo" class="btn btn-secondary" :href="'mailto:'+prospecto.email"> <i class="fa fa-envelope-o fa-lg"></i> </a>
                                            </td>
                                              <td v-else> 
                                                <a title="Enviar correo" class="btn btn-secondary" :href="'mailto:'+prospecto.email+ ';'+prospecto.email_institucional"> <i class="fa fa-envelope-o fa-lg"></i> </a>
                                            </td>
                                            <td v-text="prospecto.rfc"></td>
                                            <td v-text="prospecto.nss"></td>
                                            <td v-text="prospecto.curp"></td>
                                            <td v-text="prospecto.proyecto"></td>
                                            <td> <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right" @click="abrirModal3('prospecto','ver_todo', prospecto.id),listarObservacion(1,prospecto.id)">Ver todos</button> </td>
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
                    

     <!-- Div Card Body para actualizar -->
                    <template v-else-if="listado == 3">
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
                                                <input type="text" disabled class="form-control" v-model="nombre" placeholder="Nombre">
                                                </div>
                                            </div> 


                                            <div class="col-md-4">
                                                <div class="form-group">
                                                <label for="">Apellidos <span style="color:red;" v-show="apellidos==''">(*)</span></label>
                                                <input type="text" disabled class="form-control" v-model="apellidos" placeholder="Apellidos">
                                            </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                <label for="">Sexo <span style="color:red;" v-show="sexo==''">(*)</span></label>
                                                <select disabled class="form-control" v-model="sexo" >
                                                        <option value="">Seleccione</option>
                                                        <option value="F">Femenino</option>
                                                        <option value="M">Masculino</option>
                                                </select>
                                            </div>
                                            </div>

                                                <div class="col-md-4">
                                                <div class="form-group">
                                                <label for="">Telefono </label>
                                                <input disabled type="text" maxlength="7" pattern="\d*" class="form-control" v-on:keypress="isNumber($event)" v-model="telefono" placeholder="Telefono">
                                            </div>
                                            </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                <label for="">Celular <span style="color:red;" v-show="celular==''">(*)</span></label>
                                                <input disabled type="text" pattern="\d*" maxlength="10" class="form-control" v-on:keypress="isNumber($event)" v-model="celular" placeholder="Celular">
                                            </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                <label for="">Email personal <span style="color:red;" v-show="email==''">(*)</span></label>
                                                <input disabled type="text" class="form-control" v-model="email" placeholder="E-mail">
                                            </div>
                                                </div>

                                            <div class="col-md-3">
                                                    <div class="form-group">
                                                <label for="">Direccion<span style="color:red;" v-show="direccion==''">(*)</span></label>
                                                <input type="text" class="form-control"  v-model="direccion_coa" placeholder="Direccion">
                                                </div>
                                            </div>

                                            <div class="col-md-1">
                                                    <div class="form-group">
                                                <label for="">C.P<span style="color:red;" v-show="cp==''">(*)</span></label>
                                                <input type="text" class="form-control"  pattern="\d*" maxlength="5" v-on:keypress="isNumber($event)" @keyup="selectColonias(cp,0)"  v-model="cp" placeholder="C.Postal">
                                            </div>
                                            </div>

                                            <div class="col-md-2">
                                                    <div class="form-group">
                                                <label for="">Colonia<span style="color:red;" v-show="colonia==''">(*)</span></label>
                                                <select class="form-control" v-model="colonia">
                                                        <option value="0">Seleccione</option>
                                                        <option v-for="colonias in arrayColonias" :key="colonias.colonia " :value="colonias.colonia" v-text="colonias.colonia"></option>
                                                    </select>
                                            </div>
                                            </div>

                                            
                                            <div class="col-md-3">
                                                    <div class="form-group">
                                                <label for="">Estado <span style="color:red;" v-show="estado==''">(*)</span></label>
                                                    <select class="form-control" v-model="estado" @click="selectCiudades(estado,0)" >
                                                        <option value="0">Seleccione</option>
                                                        <option v-for="estados in arrayEstados" :key="estados.estado" :value="estados.estado" v-text="estados.estado"></option>    
                                                </select>
                                                    </div>
                                                </div>


                                                    
                                            <div class="col-md-3">
                                                    <div class="form-group">
                                                <label for="">Ciudad <span style="color:red;" v-show="ciudad==''">(*)</span></label>
                                                    <select class="form-control" v-model="ciudad" >
                                                        <option value="0">Seleccione</option>
                                                        <option v-for="ciudades in arrayCiudades" :key="ciudades.municipio" :value="ciudades.municipio" v-text="ciudades.municipio"></option>    
                                                </select>
                                                    </div>
                                                </div>

                                                <!--------------------------------------------------------------------------------------------------
                                                ------------------------------------------------>


                                                <div class="col-md-2">
                                                <div class="form-group">
                                                <label for="">Fecha de nacimiento <span style="color:red;" v-show="fecha_nac==''">(*)</span></label>
                                                <input disabled type="date" class="form-control"  v-model="fecha_nac" >
                                            </div>
                                            </div>

                                            <div class="col-md-2" v-if="coacreditado==true">
                                                    <div class="form-group">
                                                <label for="">Nacionalidad</label>
                                                <select class="form-control" v-model="nacionalidad" >
                                                        <option value="0">Mexicana</option>
                                                        <option value="1">Extranjera</option>    
                                                </select>
                                            </div>
                                            </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                <label for="">CURP</label>
                                                <input disabled type="text" maxlength="18" class="form-control"  v-model="curp" placeholder="CURP">
                                            </div>
                                                </div>

                                                <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">RFC <span style="color:red;" v-show="rfc==''">(*)</span></label>
                                                    <input type="text" maxlength="10" class="form-control"  disabled  v-model="rfc" placeholder="RFC">
                                                </div>
                                                </div>
                                                    
                                            <div align="left" class="col-md-1">
                                                <div class="form-group">
                                                <label for="">Homoclave</label>
                                                        <input disabled type="text" maxlength="3" class="form-control"  v-model="homoclave" placeholder="AA0">
                                                </div>
                                            </div>

                                                
                                            <div class="col-md-2">
                                                    <div class="form-group">
                                                <label for="">NSS <span style="color:red;" v-show="nss==''">(*)</span></label>
                                                <input disabled type="text" maxlength="11" pattern="\d*" class="form-control" v-on:keypress="isNumber($event)" v-model="nss" placeholder="NSS">
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
                                                    <select class="form-control" v-model="tipo_economia" >
                                                        <option value="0">Seleccione</option>  
                                                        <option value="formal">Formal</option>
                                                        <option value="informal">Informal</option>
                                                        <option value="mixta">Mixta</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6" v-if="tipo_economia!=0">
                                                <div class="form-group">
                                                    <label for="">Empresa <span style="color:red;" v-show="empresa==0">(*)</span></label>
                                                            <input v-if="empresa != null" type="text" class="form-control" readonly  v-model="empresa">
                                                    </div>
                                                </div>

                                                <div class="col-md-3" v-if="tipo_economia=='formal' ||tipo_economia=='mixta'">
                                                    <div class="form-group">
                                                    <label for="">Puesto <span style="color:red;" v-show="!puesto || puesto==''">(*)</span></label>
                                                            <input type="text" class="form-control"  v-model="puesto">
                                                </div>
                                                </div>

                                                    <div class="col-md-3" v-if="tipo_economia=='informal'">
                                                    <div class="form-group">
                                                    <label for="">Giro del negocio <span style="color:red;" v-show="!puesto || puesto==''">(*)</span></label>
                                                            <input type="text" class="form-control"  v-model="puesto">
                                                </div>
                                                </div>

                                                
                                                <div class="col-md-3" v-if="tipo_economia!=0">
                                                        <div class="form-group">
                                                    <label for="">Email institucional </label>
                                                    <input disabled type="text" class="form-control" v-model="email_inst" placeholder="E-mail">
                                                </div>
                                                </div>
                                        </div>  
                                    <!-- Fin Lugar de trabajo-->
                                    <!--------------------------------------------  Apartado  de datos vive en casa , edo civil -------------------------------->
                                                    <div class="form-group row border border-primary" style="margin-right:0px; margin-left:0px;" >    
                                                                    
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                <label for="">Vive en casa <span style="color:red;" v-show="tipo_casa==0">(*)</span></label>
                                                                    <select class="form-control" disabled v-model="tipo_casa" >
                                                                        <option value="0">Seleccione</option>  
                                                                        <option value="De familiares">De familiares</option>
                                                                        <option value="Prestada">Prestada</option>
                                                                        <option value="Propia">Propia</option>
                                                                        <option value="Rentada">Rentada</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3">
                                                            <div class="form-group">
                                                            <label for="">Estado civil <span style="color:red;" v-show="e_civil==0">(*)</span></label>
                                                                <select class="form-control" disabled v-model="e_civil" >
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
                                                                    <input type="text" class="form-control" v-model="dep_economicos">
                                                                </div>
                                                            </div>

                                                        
                                                            <div class="col-md-10">
                                                                <div class="form-group">
                                                                    <label for="">Observaciones <span style="color:red;" v-show="observacion==''">(*)</span></label>
                                                                    <textarea rows="3" cols="30" v-model="observacion" class="form-control" placeholder="Observaciones"></textarea>
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

                                                <div class="col-md-4" v-if="coacreditado==true">
                                                    <div class="form-group">
                                                        <label for=""> Nombre completo del conyuge </label>
                                                        <input type="text" v-if="conyugeNom != null" class="form-control" readonly v-model="conyugeNom">
                                                    </div>
                                                </div>

                                                <div class="col-md-4" v-if="coacreditado==true">
                                                        <div class="form-group">
                                                    <label for="">Parentesco </label>
                                                    <input type="text" class="form-control" disabled v-model="parentesco_coa" placeholder="Parentesco">
                                                </div>
                                                </div>

                                                
                                                <div class="col-md-4" v-if="coacreditado==true">
                                                        <div class="form-group">
                                                    <label for="">Fecha de nacimiento </label>
                                                    <input type="date" class="form-control" disabled v-model="fecha_nac_coa" placeholder="fecha de nacimiento">
                                                </div>
                                                </div>


                                                <div class="col-md-2" v-if="coacreditado==true">
                                                        <div class="form-group">
                                                    <label for="">RFC</label>
                                                    <input type="text" class="form-control" disabled v-model="rfc_coa" placeholder="rfc">
                                                </div>
                                                </div>

                                                    <div class="col-md-2" v-if="coacreditado==true">
                                                        <div class="form-group">
                                                    <label for="">Homoclave</label>
                                                    <input type="text" class="form-control" disabled v-model="homoclave_coa" placeholder="homoclave">
                                                </div>
                                                </div>


                                                <div class="col-md-3" v-if="coacreditado==true">
                                                        <div class="form-group">
                                                    <label for="">CURP </label>
                                                    <input type="text" class="form-control" disabled v-model="curp_coa" placeholder="CURP">
                                                </div>
                                                </div>


                                                <div class="col-md-2" v-if="coacreditado==true">
                                                        <div class="form-group">
                                                    <label for="">NSS</label>
                                                    <input type="text" class="form-control" disabled v-model="nss_coa" placeholder="NSS">
                                                </div>
                                                </div>

                                                <div class="col-md-3" v-if="coacreditado==true">
                                                        <div class="form-group">
                                                    <label for="">Nacionalidad</label>
                                                    <select class="form-control" v-model="nacionalidad_coa" >
                                                            <option value="0">Mexicana</option>
                                                            <option value="1">Extranjera</option>    
                                                    </select>
                                                </div>
                                                </div>

                                                <div class="col-md-3" v-if="coacreditado==true">
                                                        <div class="form-group">
                                                    <label for="">Direccion</label>
                                                    <input type="text" class="form-control"  v-model="direccion_coa" placeholder="Direccion">
                                                    </div>
                                                </div>

                                                <div class="col-md-1" v-if="coacreditado==true">
                                                        <div class="form-group">
                                                    <label for="">C.P</label>
                                                    <input type="text" class="form-control"  pattern="\d*" maxlength="5" v-on:keypress="isNumber($event)" @keyup="selectColonias(cp_coa,1)"  v-model="cp_coa" placeholder="C.Postal">
                                                </div>
                                                </div>

                                                <div class="col-md-2" v-if="coacreditado==true">
                                                        <div class="form-group">
                                                    <label for="">Colonia</label>
                                                    <select class="form-control" v-model="colonia_coa">
                                                            <option value="0">Seleccione</option>
                                                            <option v-for="colonia in arrayColoniasCoa" :key="colonia.colonia " :value="colonia.colonia" v-text="colonia.colonia"></option>
                                                        </select>
                                                </div>
                                                </div>

                                                
                                                <div class="col-md-3">
                                                        <div class="form-group">
                                                    <label for="">Estado <span style="color:red;" v-show="estado_coa==''">(*)</span></label>
                                                        <select class="form-control" v-model="estado_coa" @click="selectCiudades(estado_coa,1)" >
                                                            <option value="0">Seleccione</option>
                                                            <option v-for="estado in arrayEstados" :key="estado.estado " :value="estado.estado" v-text="estado.estado"></option>    
                                                    </select>
                                                        </div>
                                                    </div>


                                                        
                                                <div class="col-md-3">
                                                        <div class="form-group">
                                                    <label for="">Ciudad <span style="color:red;" v-show="ciudad_coa==''">(*)</span></label>
                                                        <select class="form-control" v-model="ciudad_coa" >
                                                            <option value="0">Seleccione</option>
                                                            <option v-for="ciudades in arrayCiudadesCoa" :key="ciudades.municipio" :value="ciudades.municipio" v-text="ciudades.municipio"></option>    
                                                    </select>
                                                        </div>
                                                    </div>
                                                
                                                
                                                <div class="col-md-3" v-if="coacreditado==true">
                                                        <div class="form-group">
                                                    <label for="">Celular</label>
                                                    <input type="text" class="form-control" disabled v-model="celular_coa" placeholder="Celular">
                                                </div>
                                                </div>

                                                
                                                <div class="col-md-3" v-if="coacreditado==true">
                                                        <div class="form-group">
                                                    <label for="">Telefono</label>
                                                    <input type="text" class="form-control" disabled v-model="telefono_coa" placeholder="Telefono">
                                                </div>
                                                </div>

                                                
                                                <div class="col-md-3" v-if="coacreditado==true">
                                                        <div class="form-group">
                                                    <label for="">Email</label>
                                                    <input type="text" class="form-control" disabled v-model="email_coa" placeholder="Correo">
                                                </div>
                                                </div>


                                                <div class="col-md-3" v-if="coacreditado==true">
                                                        <div class="form-group">
                                                    <label for="">Email institucional</label>
                                                    <input type="text" class="form-control" disabled v-model="email_institucional_coa" placeholder="Correo institucional">
                                                </div>
                                                </div>

                                                    <div class="col-md-8">
                                                    <div class="form-group">
                                                    <label for="">Empresa <span style="color:red;" v-show="empresa_coa==0">(*)</span></label>
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


                                                <div class="col-md-4">
                                                        <div class="form-group">
                                                    <label for="">Nombre</label>
                                                    <input type="text" class="form-control"  v-model="nombre_referencia1" placeholder="Nombre">
                                                </div>
                                                </div>


                                                <div class="col-md-4">
                                                        <div class="form-group">
                                                    <label for="">Telefono</label>
                                                    <input type="text" class="form-control"  v-model="telefono_referencia1" placeholder="Telefono">
                                                </div>
                                                </div>

                                                
                                                <div class="col-md-4">
                                                        <div class="form-group">
                                                    <label for="">Celular</label>
                                                    <input type="text" class="form-control"  v-model="celular_referencia1" placeholder="Celular">
                                                </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                    <center> <h5>Segunda referencia</h5> </center>
                                                    </div>
                                                    </div> 

                                                <div class="col-md-4">
                                                        <div class="form-group">
                                                    <label for="">Nombre</label>
                                                    <input type="text" class="form-control"  v-model="nombre_referencia2" placeholder="Nombre">
                                                </div>
                                                </div>


                                                <div class="col-md-4">
                                                        <div class="form-group">
                                                    <label for="">Telefono</label>
                                                    <input type="text" class="form-control"  v-model="telefono_referencia2" placeholder="Telefono">
                                                </div>
                                                </div>

                                                
                                                <div class="col-md-4">
                                                        <div class="form-group">
                                                    <label for="">Celular</label>
                                                    <input type="text" class="form-control"  v-model="celular_referencia2" placeholder="Celular">
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
                                                            <label for="">Proyecto en el que esta interesado <span style="color:red;" v-show="proyecto_interes_id==0">(*)</span></label>
                                                        </div>
                                                    </div>

                                                    
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <select class="form-control" v-model="proyecto_interes_id" @click="selectEtapa(proyecto_interes_id)">
                                                                    <option value=0> Seleccione </option>
                                                                    <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                
                                                
                                                <div class="col-md-1">
                                                        <div class="form-group">
                                                            <label for="">Etapa<span style="color:red;" v-show="etapa==0">(*)</span></label>     
                                                        </div>
                                                    </div>

                                                    
                                                <div class="col-md-2">
                                                        <div class="form-group">
                                                            <select class="form-control" v-model="etapa" @click="selectManzana(etapa),selectPaquetes(etapa)">
                                                                    <option v-for="etapas in arrayEtapas" :key="etapas.etapa" :value="etapas.etapa" v-text="etapas.etapa"></option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12" >
                                                        <h6></h6>
                                                    </div>

                                                    
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="">Manzana<span style="color:red;" v-show="manzana==0">(*)</span></label>
                                                        </div>
                                                    </div>

                                                    
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <select class="form-control" v-model="manzana" @click="selectLotes(manzana)">
                                                                    <option v-for="manzanas in arrayManzanas" :key="manzanas.manzana" :value="manzanas.manzana" v-text="manzanas.manzana"></option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-1">
                                                        <div class="form-group">
                                                            <label for="">Lote<span style="color:red;" v-show="lote==0">(*)</span></label>
                                                        </div>
                                                    </div>

                                                    
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <select class="form-control" v-model="lote" @click="mostrarDatosLote(lote)">
                                                                    <option v-for="lotes in arrayLotes" :key="lotes.id" :value="lotes.id" v-text="lotes.num_lote"></option>
                                                            </select>
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

                                                    <div class="col-md-3">
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
                                                            <h5 style="color:#2271b3;" for=""><strong> Precio de venta: </strong></h5>
                                                        </div>
                                                    </div> 
                                                    <div class="col-md-3" v-if="precioVenta!=''">
                                                        <div class="form-group">
                                                            <h5 v-text="'$'+formatNumber(precioVenta)"></h5>
                                                        </div>
                                                    </div> 

                                                    <div class="col-md-12" >
                                                        <h6></h6>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                        <label for="">Tipo de credito <span style="color:red;" v-show="tipo_credito==0">(*)</span></label>
                                                        <select class="form-control" v-model="tipo_credito" @click="selectInstitucion(tipo_credito)" >
                                                            <option value="0">Seleccione</option>
                                                            <option v-for="creditos in arrayCreditos" :key="creditos.nombre" :value="creditos.nombre" v-text="creditos.nombre"></option>   
                                                        </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                        <label for="">Institucion financiera <span style="color:red;" v-show="inst_financiera==0">(*)</span></label>
                                                        <select class="form-control" v-model="inst_financiera" >
                                                            <option value="">Seleccione</option>
                                                            <option v-for="institucion in arrayInstituciones" :key="institucion.institucion_fin" :value="institucion.institucion_fin" v-text="institucion.institucion_fin"></option>      
                                                        </select>
                                                        </div>
                                                    </div>
                                            </div>
                                    </div>
                                </div>
                                <div class="card mb-0">
                                    <div class="card-header" id="headingFive" role="tab">
                                        <h5 class="mb-0">
                                            <a class="collapsed" data-toggle="collapse" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">Ayudanos a mejorar</a>
                                        </h5>
                                    </div>
                                    <div class="collapse" id="collapseFive" role="tabpanel" aria-labelledby="headingFive" data-parent="#accordion">
                                        <!--------------------------------  Apartado  Datos estadisticos ------------------------>
                                            <div class="form-group row border border-info" style="margin-right:0px; margin-left:0px;" >   

                                                        <div class="col-md-12">
                                                        <div class="form-group">
                                                        <center> <h4></h4> </center>
                                                        </div>
                                                        </div>  
                                                            

                                                    <div class="col-md-2">
                                                        <h6> ¿Tiene mascotas? </h6>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <select class="form-control" v-model="mascotas" @click="LimpiarMascotas()">
                                                                <option value="0">No</option>
                                                                <option value="1">Si</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-1" v-if="mascotas==1">
                                                        <label for=""># de Perros </label>
                                                    </div>
                                                    <div class="col-md-3" v-if="mascotas==1">
                                                        <div class="form-group">
                                                        <input type="text" maxlength="2" pattern="\d*" class="form-control" v-on:keypress="isNumber($event)" v-model="num_perros">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <h6> </h6>
                                                    </div>

                                                    <div class="col-md-12" >
                                                        <h6># Habitantes en el domicilio <span style="color:red;" v-show="valHab==1">Validar numero de habitantes</span> </h6>
                                                    </div>
                                                    <div class="col-md-2" >
                                                        <div class="form-group">
                                                        <label for=""># Total <span style="color:red;" v-show="valHab==1">(*)</span> </label>
                                                        <input type="text" maxlength="2" pattern="\d*" class="form-control" v-on:keypress="isNumber($event), validarHab()" v-model="num_habitantes">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3" >
                                                        <div class="form-group">
                                                        <label for="">Entre 0-10 años de edad <span style="color:red;" v-show="valHab==1">(*)</span> </label>
                                                        <input type="text" maxlength="2" pattern="\d*" class="form-control" v-on:keypress="isNumber($event),validarHab()" v-model="rang0_10">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3" >
                                                        <div class="form-group">
                                                        <label for="">Entre 11-20 años de edad <span style="color:red;" v-show="valHab==1">(*)</span> </label>
                                                        <input type="text" maxlength="2" pattern="\d*" class="form-control" v-on:keypress="isNumber($event),validarHab()" v-model="rang11_20">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3" >
                                                        <div class="form-group">
                                                        <label for="">Mayores a 21 años de edad <span style="color:red;" v-show="valHab==1">(*)</span> </label>
                                                        <input type="text" maxlength="2" pattern="\d*" class="form-control" v-on:keypress="isNumber($event),validarHab()" v-model="rang21">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3" v-if="e_civil==1 || e_civil==2 ||e_civil==5" >
                                                        <div class="form-group">
                                                        <label for="">¿Cónyuge es ama de casa? </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2" v-if="e_civil==1 || e_civil==2 ||e_civil==5" >
                                                        <div class="form-group">
                                                        <select class="form-control" v-model="ama_casa" >
                                                                <option value="0">No</option>
                                                                <option value="1">Si</option>
                                                        </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12" >
                                                        <h6></h6>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for=""> ¿Alguna de las personas que habitaran la casa cuenta con alguna discapacidad? </label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <select class="form-control" v-model="discapacidad" @click="LimpiarSillaRuedas()">
                                                                <option value="0">No</option>
                                                                <option value="1">Si</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2" v-if="discapacidad==1">
                                                        <label for=""> ¿Requiere silla de ruedas? </label>
                                                    </div>
                                                    <div class="col-md-2" v-if="discapacidad==1">
                                                        <select class="form-control" v-model="silla_ruedas" >
                                                                <option value="0">No</option>
                                                                <option value="1">Si</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <h6> </h6>
                                                    </div>

                                                    <div class="col-md-12" >
                                                        <h6># De vehiculos con los que cuenta </h6>
                                                    </div>
                                                    <div class="col-md-2" >
                                                        <div class="form-group">
                                                        <input type="text" maxlength="2" pattern="\d*" class="form-control" v-on:keypress="isNumber($event)" v-model="num_vehiculos">
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
                                                <div v-show="errorProspecto" class="form-group row div-error">
                                                    <div class="text-center text-error">
                                                        <div v-for="error in errorMostrarMsjProspecto" :key="error" v-text="error">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-secondary" @click="ocultarDetalle()"> Cerrar </button>
                                                <button type="button" class="btn btn-primary" @click="actualizarProspecto()"> Enviar </button>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </template>
                    
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
           

               <!--Inicio del modal observaciones-->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal3}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal3"></h4>
                            <button type="button" class="close" @click="cerrarModal3()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                
                                <table class="table table-bordered table-striped table-sm" v-if="tipoAccion == 4">
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
                                            <td v-text="observacion.comentario" ></td>
                                            <td v-text="observacion.created_at"></td>
                                        </tr>                               
                                    </tbody>
                                </table>
                                
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal3()">Cerrar</button>
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
    import vSelect from 'vue-select';
    export default {
        data(){
            return{
                proceso:false,
                id:0,
                clasificacion:1,
                dep_economicos:'',
                nombre:'',
                apellidos:'',
                telefono : '',
                celular : '',
                email:'',
                email_inst:'',
                puesto:'',
                nss:'',
                sexo:'',
                fecha_nac: '',
                curp:'',
                rfc:'',
                homoclave: '',
                e_civil: 0,
                ama_casa:0,
                tipo_casa:0,
                coacreditado: 0,
                publicidad_id: 0,
                proyecto: '',
                empresa: '',
                observacion:'',
                lugar_contacto: 0,
                conyugeNom: '',
                tipo_credito: 0,
                tipo_economia: 0,
                estado: '',
                ciudad:'',
                cp:'',
                colonia:'',
                direccion: '',
                inst_financiera:'',
                nacionalidad:0,

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

             

                arrayEmpresa: [],
                arrayMediosPublicidad:[],
                arrayInstituciones: [],
                arrayEstados: [],
                arrayCiudadesCoa: [],
                arrayCiudades:[],
                arrayColoniasCoa: [],
                arrayColonias: [],
                arrayEtapas: [],
                arrayManzanas: [],
                arrayLotes: [],
                arrayDatosLotes: [],
                arrayPaquetes: [],
                arrayDatosPaquetes: [],

                proyecto_interes_id: 0,
                etapa: '',
                manzana: '',
                lote: '',
                modelo: '',
                superficie: '',
                precioBase: 0,
                precioExcedente: 0,
                precioVenta: 0,
                promocion: '',
                descripcionPromo: '',
                descuentoPromo: 0,
                paquete_id: 0,
                descripcionPaquete: '',
                costoPaquete: 0,
                

                nombre_referencia1: '',
                telefono_referencia1: '',
                celular_referencia1: '',
                
                nombre_referencia2: '',
                telefono_referencia2: '',
                celular_referencia2: '',

             
                modal3: 0,
                listado:1,
                tituloModal3 : '',
                tipoAccion: 0,
                errorProspecto : 0,
                errorMostrarMsjProspecto : [],
                errorCoacreditado : 0,
                errorMostrarMsjCoacreditado : [],
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'personal.nombre', 
                buscar : '',
                arrayCoacreditados : [],
                arrayProspectos: [],
                arrayFraccionamientos : [],
                arrayLugarContacto: [],
                arrayFraccionamientos2 : [],
                arrayObservacion: [],
                fraccionamiento:'',
                mascotas:0,
                num_perros:0,
                num_habitantes:0,
                rang0_10:0,
                rang11_20:0,
                rang21:0,
                valHab:0,
                num_vehiculos:0,
                silla_ruedas:0,
                discapacidad:0,
                terreno_tam_excedente:0
                
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
        },
       
        methods : {
            /**Metodo para mostrar los registros */
            listarProspectos(page, buscar, criterio){
                let me = this;
                var url = '/clientes_simulacion?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayProspectos = respuesta.personas.data;
                    me.pagination = respuesta.pagination;
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
                me.empresa_coa = val1.nombre;
               
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

            selectEtapa(fraccionamiento){
                let me = this;
                me.arrayEtapas=[];
                var url = '/select_etapas_disp?buscar=' + fraccionamiento;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                     me.arrayEtapas = respuesta.lotes_etapas;
                    
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            selectManzana(etapa){
                let me = this;
                me.arrayManzanas=[];
                var url = '/select_manzanas_disp?buscar=' + etapa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                     me.arrayManzanas = respuesta.lotes_manzanas;
                    
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
            
            selectLotes(manzana){
                let me = this;
                me.arrayLotes=[];
                var url = '/select_lotes_disp?buscar=' + manzana;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                     me.arrayLotes = respuesta.lotes_disp;
                    
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

           mostrarDatosLote(lote){
               let me = this;
                me.arrayDatosLotes=[];
                var url = '/select_datos_lotes_disp?buscar=' + lote;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                     me.arrayDatosLotes = respuesta.lotes;
                     me.modelo = me.arrayDatosLotes[0]['modelo'];
                     me.superficie = me.arrayDatosLotes[0]['terreno'];
                     me.precioBase = me.arrayDatosLotes[0]['precio_base'];
                     me.precioExcedente = me.arrayDatosLotes[0]['excedente_terreno'];
                     me.precioVenta = me.arrayDatosLotes[0]['precio_venta'];
                     me.promocion = me.arrayDatosLotes[0]['promocion'];
                     me.descripcionPromo = me.arrayDatosLotes[0]['descripcionPromo'];
                     me.descuentoPromo = me.arrayDatosLotes[0]['descuentoPromo'];
                     me.terreno_tam_excedente= me.arrayDatosLotes[0]['terreno_tam_excedente'];

                     me.precioVenta = me.precioVenta - me.descuentoPromo;
                  
                    
                })
                .catch(function (error) {
                    console.log(error);
                });
               

            },
            LimpiarMascotas(){
                if(this.mascotas=="0"){
                    this.num_perros=0;
                }
            },
            LimpiarSillaRuedas(){
                if(this.discapacidad=="0")
                    this.silla_ruedas=0;
            },

            selectLugarContacto(){
                let me = this;
                me.arrayLugarContacto=[];
                var url = '/select_lugar_contacto';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLugarContacto = respuesta.lugares_contacto;
                })
                .catch(function (error) {
                    console.log(error);
                });

            },
            selectMedioPublicidad(){
                let me = this;
                me.arrayMediosPublicidad=[];
                var url = '/select_medio_publicidad';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayMediosPublicidad = respuesta.medios_publicitarios;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
           
            selectFraccionamientos2(){
                let me = this;
                me.arrayFraccionamientos2=[];
                var url = '/select_fraccionamiento';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayFraccionamientos2 = respuesta.fraccionamientos;
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


            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
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



            listarObservacion(page, buscar){
                let me = this;
                var url = '/clientes/observacion?page=' + page + '&buscar=' + buscar ;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayObservacion = respuesta.observacion.data;
                    me.pagination = respuesta.pagination;
                    console.log(url);
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },

            selectCreditos(){
                let me = this;
                me.arrayCreditos=[];
                var url = '/select_tipoCredito';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayCreditos = respuesta.Tipos_creditos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectInstitucion(credito){
                let me = this;
                if(me.tipo_credito==0){
                    me.inst_financiera="";
                }
                me.arrayInstituciones=[];
                var url = '/select_institucion?buscar='+credito;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayInstituciones = respuesta.instituciones;
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
                me.listarProspectos(page,buscar,criterio);
            },
            /**Metodo para registrar  */
            registrarProspecto(){
                if(this.validarProspecto() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;

                let me = this;
                //Con axios se llama el metodo store del controller
                axios.post('/clientes/registrar',{
                    'clasificacion':this.clasificacion,
                    'nombre':this.nombre,
                    'apellidos':this.apellidos,
                    'telefono':this.telefono ,
                    'celular':this.celular ,
                    'email':this.email,
                    'email_institucional':this.email_inst,
                    'nss':this.nss,
                    'sexo':this.sexo,
                    'f_nacimiento':this.fecha_nac,
                    'curp':this.curp,
                    'rfc':this.rfc,
                    'homoclave':this.homoclave,
                    'edo_civil':this.e_civil,
                    'tipo_casa':this.tipo_casa,
                    'coacreditado':this.coacreditado,
                    'publicidad_id':this.publicidad_id,
                    'proyecto_interes_id':this.proyecto_interes_id,
                    'empresa':this.empresa,
                    'observacion':this.observacion,
                    'lugar_contacto':this.lugar_contacto,

                    'nombre_coa':this.nombre_coa,
                    'parentesco_coa':this.parentesco_coa,
                    'apellidos_coa':this.apellidos_coa,
                    'telefono_coa':this.telefono_coa ,
                    'celular_coa':this.celular_coa ,
                    'email_coa':this.email_coa,
                    'email_institucional_coa':this.email_institucional_coa,
                    'nss_coa':this.nss_coa,
                    'sexo_coa':this.sexo_coa,
                    'f_nacimiento_coa':this.fecha_nac_coa,
                    'curp_coa':this.curp_coa,
                    'rfc_coa':this.rfc_coa,
                    'homoclave_coa':this.homoclave_coa,
                    'edo_civil_coa':this.e_civil_coa,
                    'tipo_casa_coa':this.tipo_casa_coa,
                }).then(function (response){
                    me.proceso=false;
                    me.listado=1;
                    me.limpiarDatos();
                    me.listarProspectos(1,'','ini_obras.clave'); //se enlistan nuevamente los registros
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

            registrarCoacreditado(){
                if(this.validarCoacreditado() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;

                let me = this;
                //Con axios se llama el metodo store del controller
                axios.post('/clientes/registrar_coacreditado',{
                    'clasificacion':this.clasificacion,
                    'nombre':this.nombre_coa,
                    'apellidos':this.apellidos_coa,
                    'telefono':this.telefono_coa,
                    'celular':this.celular_coa,
                    'email':this.email_coa,
                    'email_institucional':this.email_institucional_coa,
                    'nss':this.nss_coa,
                    'sexo':this.sexo_coa,
                    'f_nacimiento':this.fecha_nac_coa,
                    'curp':this.curp_coa,
                    'rfc':this.rfc_coa,
                    'homoclave':this.homoclave_coa,
                    'edo_civil':this.e_civil_coa,
                    'tipo_casa':this.tipo_casa_coa,
                    'coacreditado':0,
                    'proyecto_interes_id':this.proyecto_interes_id,
                    'lugar_contacto':this.lugar_contacto,
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal();
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Coacreditado agregada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },

               /**Metodo para actualizar  */
            actualizarProspecto(){
                if(this.validarProspecto() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;

                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.put('/clientes/actualizar',{
                    'id':this.id,
                    'clasificacion':this.clasificacion,
                    'nombre':this.nombre,
                    'apellidos':this.apellidos,
                    'telefono':this.telefono ,
                    'celular':this.celular ,
                    'email':this.email,
                    'email_institucional':this.email_inst,
                    'nss':this.nss,
                    'sexo':this.sexo,
                    'f_nacimiento':this.fecha_nac,
                    'curp':this.curp,
                    'rfc':this.rfc,
                    'homoclave':this.homoclave,
                    'edo_civil':this.e_civil,
                    'tipo_casa':this.tipo_casa,
                    'coacreditado':this.coacreditado,
                    'publicidad_id':this.publicidad_id,
                    'proyecto_interes_id':this.proyecto_interes_id,
                    'empresa':this.empresa,
                    'observacion':this.observacion,
                    'lugar_contacto':this.lugar_contacto,

                    'nombre_coa':this.nombre_coa,
                    'parentesco_coa':this.parentesco_coa,
                    'apellidos_coa':this.apellidos_coa,
                    'telefono_coa':this.telefono_coa ,
                    'celular_coa':this.celular_coa ,
                    'email_coa':this.email_coa,
                    'email_institucional_coa':this.email_institucional_coa,
                    'nss_coa':this.nss_coa,
                    'sexo_coa':this.sexo_coa,
                    'f_nacimiento_coa':this.fecha_nac_coa,
                    'curp_coa':this.curp_coa,
                    'rfc_coa':this.rfc_coa,
                    'homoclave_coa':this.homoclave_coa,
                    'edo_civil_coa':this.e_civil_coa,
                    'tipo_casa_coa':this.tipo_casa_coa,
                }).then(function (response){
                    me.proceso=false;
                    me.listado=1;
                    me.limpiarDatos();
                    me.listarProspectos(1,'','ini_obras.clave'); //se enlistan nuevamente los registros
                    
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Prospecto actualizado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            validarProspecto(){
                this.errorProspecto=0;
                this.errorMostrarMsjProspecto=[];

                if(this.nombre=='' || this.apellidos=='') 
                    this.errorMostrarMsjProspecto.push("El nombre del prospecto no puede ir vacio.");
                if(this.sexo=='') 
                    this.errorMostrarMsjProspecto.push("Seleccionar el sexo del prospecto.");
                if(this.celular=='') 
                    this.errorMostrarMsjProspecto.push("Ingresar numero de celular.");
                if(this.email=='') 
                    this.errorMostrarMsjProspecto.push("Ingresar email personal.");
                if(this.empresa=='') 
                    this.errorMostrarMsjProspecto.push("Seleccionar empresa.");
                if(this.fecha_nac=='') 
                    this.errorMostrarMsjProspecto.push("Ingresar fecha de nacimiento.");
                if(this.rfc=='') 
                    this.errorMostrarMsjProspecto.push("Ingresar RFC.");
                if(this.nss=='') 
                    this.errorMostrarMsjProspecto.push("Ingresar numero de seguro social.");
                if(this.tipo_casa==0) 
                    this.errorMostrarMsjProspecto.push("Seleccionar tipo de casa.");
                if(this.e_civil==0) 
                    this.errorMostrarMsjProspecto.push("Seleccionar estado civil.");
                if(this.proyecto_interes_id==0) 
                    this.errorMostrarMsjProspecto.push("Seleccionar proyecto de interes.");
                if(this.publicidad_id==0) 
                    this.errorMostrarMsjProspecto.push("Seleccionar medio de publicidad.");
                if(this.observacion=='') 
                    this.errorMostrarMsjProspecto.push("Escribir una observación.");

                if(this.errorMostrarMsjProspecto.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorProspecto = 1;

                return this.errorProspecto;
            },
            validarCoacreditado(){
                this.errorCoacreditado=0;
                this.errorMostrarMsjCoacreditado=[];

                if(this.nombre_coa=='' || this.apellidos_coa=='') 
                    this.errorMostrarMsjCoacreditado.push("El nombre del prospecto no puede ir vacio.");
                if(this.sexo_coa=='') 
                    this.errorMostrarMsjCoacreditado.push("Seleccionar el sexo del prospecto.");
                if(this.celular_coa=='') 
                    this.errorMostrarMsjCoacreditado.push("Ingresar numero de celular.");
                if(this.email_coa=='') 
                    this.errorMostrarMsjCoacreditado.push("Ingresar email personal.");
                if(this.fecha_nac_coa=='') 
                    this.errorMostrarMsjCoacreditado.push("Ingresar fecha de nacimiento.");
                if(this.rfc_coa=='') 
                    this.errorMostrarMsjCoacreditado.push("Ingresar RFC.");
                if(this.nss_coa=='') 
                    this.errorMostrarMsjCoacreditado.push("Ingresar numero de seguro social.");
                if(this.tipo_casa_coa==0) 
                    this.errorMostrarMsjCoacreditado.push("Seleccionar tipo de casa.");
                if(this.e_civil_coa==0) 
                    this.errorMostrarMsjCoacreditado.push("Seleccionar estado civil.");

                if(this.errorMostrarMsjCoacreditado.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorCoacreditado = 1;

                return this.errorCoacreditado;
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
            validarHab() {
                var sum = parseInt(this.rang0_10)+parseInt(this.rang11_20)+parseInt(this.rang21);
                
                if(parseInt(this.num_habitantes)<sum){
                    this.valHab=1
                }
                else{
                    this.valHab=0;
                }
            },
            limpiarDatos(){
                this.clasificacion=1;
                this.nombre='';
                this.apellidos='';
                this.telefono = '';
                this.celular = '';
                this.email='';
                this.email_inst='';
                this.nss='';
                this.sexo='';
                this.fecha_nac= '';
                this.curp='';
                this.rfc='';
                this.homoclave= '';
                this.e_civil= 0;
                this.tipo_casa=0;
                this.coacreditado= 0;
                this.publicidad_id= 0;
                this.proyecto_interes_id= 0;
                this.empresa= '';
                this.observacion='';
                this.lugar_contacto= 0;
                this.puesto='';
                this.dep_economicos='';

                this.nombre_coa='';
                this.parentesco_coa='';
                this.apellidos_coa='';
                this.telefono_coa = '';
                this.celular_coa = '';
                this.email_coa='';
                this.email_institucional_coa='';
                this.nss_coa='';
                this.sexo_coa='';
                this.fecha_nac_coa= '';
                this.curp_coa='';
                this.rfc_coa='';
                this.homoclave_coa= '';
                this.e_civil_coa= 0;
                this.tipo_casa_coa=0;
                this.conyugeNom = '';
                this.proceso=false;


                this.errorProspecto=0;
                this.errorMostrarMsjProspecto=[];
            },
            ocultarDetalle(){
                this.listado=1;
            },
            actualizarProspectoBTN(prospecto){
              
                //let me= this;
                
                var arrayDatosProspecto=[];
                /*var url = '/clientes/obtenerDatos?id=' + id;

                 axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayDatosProspecto = respuesta.personas;*/

                    this.nombre= prospecto['nombre'];
                    this.apellidos= prospecto['apellidos'];
                    this.sexo= prospecto['sexo'];
                    this.telefono= prospecto['telefono'];
                    this.celular= prospecto['celular'];
                    this.email_inst= prospecto['email_institucional'];
                    this.email = prospecto['email'];
                    this.empresa=prospecto['empresa'];
                    this.fecha_nac=prospecto['f_nacimiento'];
                    this.curp=prospecto['curp'];
                    this.rfc=prospecto['rfc'];
                    this.homoclave=prospecto['homoclave'];
                    this.nss=prospecto['nss'];
                    this.lugar_contacto=prospecto['lugar_contacto'];
                    this.clasificacion=prospecto['clasificacion'];
                    this.proyecto_interes_id=prospecto['proyecto_interes_id'];
                    this.publicidad_id=prospecto['publicidad_id'];
                    this.tipo_casa=prospecto['tipo_casa'];
                    this.e_civil=prospecto['edo_civil'];
                    this.parentesco_coa=prospecto['parentesco_coa'];
                    this.coacreditado=prospecto['coacreditado'];
                    this.conyugeNom = prospecto['n_completo_coa'];
                    this.proyecto = prospecto['proyecto'];
                    this.fecha_nac_coa = prospecto['f_nacimiento_coa'];
                    this.rfc_coa = prospecto['rfc_coa'];
                    this.homoclave_coa = prospecto['homoclave_coa'];
                    this.curp_coa = prospecto['curp_coa'];
                    this.nss_coa = prospecto['nss_coa'];
                    this.telefono_coa = prospecto['telefono_coa'];
                    this.celular_coa = prospecto['celular_coa'];
                    this.email_coa = prospecto['email_coa'];
                    this.email_institucional_coa = prospecto['email_institucional_coa'];
                    this.nacionalidad = prospecto['nacionalidad'];
                    this.nacionalidad_coa = prospecto['nacionalidad_coa'];
                    this.puesto = prospecto['puesto'];
                    this.dep_economicos='';
                    this.rang0_10=0;
                    this.rang11_20=0;
                    this.rang21=0;
                    this.num_habitantes=0;
                    this.valHab=0;
                    
                    this.id=prospecto['id'];
                    this.listado=3;
               /* })
                .catch(function (error) {
                    console.log(error);
                });*/
               

            },

            cerrarModal(){
                this.modal = 0;
                this.nombre_coa='';
                this.parentesco_coa='';
                this.apellidos_coa='';
                this.telefono_coa = '';
                this.celular_coa = '';
                this.email_coa='';
                this.email_institucional_coa='';
                this.nss_coa='';
                this.sexo_coa='';
                this.fecha_nac_coa= '';
                this.curp_coa='';
                this.rfc_coa='';
                this.homoclave_coa= '';
                this.e_civil_coa= 0;
                this.tipo_casa_coa=0;
                this.errorCoacreditado=0;
                this.errorMostrarMsjCoacreditado=[];
            },

             cerrarModal3(){
                this.modal3 = 0;
                this.tituloModal3 = '';
              
            },
            
  
             abrirModal3(prospectos,accion,prospecto){
             switch(prospectos){
                    case "prospecto":
                    {
                        switch(accion){
                         
                             case 'ver_todo':
                            {
                                this.modal3 =1;
                                this.tituloModal3='Consulta Observaciones';
                                this.tipoAccion= 4;
                                break;  
                            }
                            
                        }
                    }
                 
             }
                
         },

           
        },
        mounted() {
            this.listarProspectos(1,this.buscar,this.criterio);
            this.selectMedioPublicidad();
            this.selectFraccionamientos();
            this.selectEtapa(this.proyecto_interes_id);
            this.selectManzana(this.etapa);
            this.selectPaquetes(this.etapa);
            this.datosPaquetes(this.paquete_id);
            this.selectLotes(this.manzana);
            this.mostrarDatosLote(this.lote);
            this.selectLugarContacto();
            this.selectCreditos();
            this.selectInstitucion(this.tipo_credito);
            this.selectEstados();
            this.selectCiudades(this.estado,0);
            this.selectColonias(this.cp,0);
           
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
