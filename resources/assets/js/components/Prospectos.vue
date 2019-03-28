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
                            <i class="icon-people"></i>&nbsp;Agregar
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
                                            <option value="personal.nombre">Nombre</option>
                                            <option value="personal.rfc">RFC</option>
                                            <option value="clientes.curp">CURP</option>
                                            <option value="clientes.nss">NSS</option>
                                            <option value="fraccionamientos.nombre">Proyecto</option>
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
                                            
                                            <template v-if="prospecto.activo">
                                                <button title="Desactivar cliente" type="button" @click="desactivarProspecto(prospecto.id)" class="btn btn-danger btn-sm">
                                                <i class="fa fa-user-times"></i>
                                                </button>
                                            </template>
                                            <template v-else>
                                                <button title="Activar cliente" type="button" @click="activarProspecto(prospecto.id)" class="btn btn-success btn-sm">
                                                    <i class="icon-check"></i>
                                                </button>
                                            </template>
                                                <button title="Editar" type="button" class="btn btn-warning btn-sm" @click="actualizarProspectoBTN(prospecto.id)">
                                                    <i class="icon-pencil"></i>
                                                </button>
                                            </td>
                                            <td v-text="prospecto.nombre + ' ' + prospecto.apellidos "></td>
                                            <td >
                                                 <a title="Llamar" class="btn btn-dark" :href="'tel:'+prospecto.celular"><i class="fa fa-phone fa-lg"></i></a>
                                                 <a title="Enviar whatsapp" class="btn btn-success" target="_blank" :href="'https://api.whatsapp.com/send?phone=+52'+prospecto.celular+'&text=Hola'"><i class="fa fa-whatsapp fa-lg"></i></a>
                                                 
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
                                    <label for="">Nombre <span style="color:red;" v-show="nombre==''">(*)</span> </label>
                                    <input type="text" class="form-control" v-model="nombre" placeholder="Nombre">
                                    </div>
                                </div> 


                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="">Apellidos <span style="color:red;" v-show="apellidos==''">(*)</span></label>
                                    <input type="text" class="form-control" v-model="apellidos" placeholder="Apellidos">
                                </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="">Sexo <span style="color:red;" v-show="sexo==''">(*)</span></label>
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
                                    <input type="text" maxlength="10" pattern="\d*" class="form-control" v-on:keypress="isNumber($event)" v-model="telefono" placeholder="Telefono">
                                </div>
                                </div>

                                  <div class="col-md-4">
                                     <div class="form-group">
                                    <label for="">Celular <span style="color:red;" v-show="celular==''">(*)</span></label>
                                    <input type="text" pattern="\d*" maxlength="10" class="form-control" v-on:keypress="isNumber($event)" v-model="celular" placeholder="Celular">
                                </div>
                                 </div>

                                 <div class="col-md-4">
                                     <div class="form-group">
                                    <label for="">Email personal <span style="color:red;" v-show="email==''">(*)</span></label>
                                    <input type="text" class="form-control" v-model="email" placeholder="E-mail">
                                </div>
                                 </div>

                                   <div class="col-md-8">
                                    <div class="form-group">
                                    <label for="">Empresa <span style="color:red;" v-show="empresa==0">(*)</span></label>
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
                                    <label for="">Fecha de nacimiento <span style="color:red;" v-show="fecha_nac==''">(*)</span></label>
                                    <input type="date" class="form-control"  v-model="fecha_nac" >
                                </div>
                                </div>

                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Lugar de nacimiento <span style="color:red;" v-show="lugar_nacimiento==''">(*)</span></label>
                                        <select class="form-control" v-model="lugar_nacimiento">
                                            <option value="">Seleccione</option>
                                            <option v-for="estados in arrayEstados" :key="estados.estado" :value="estados.estado" v-text="estados.estado"></option>    
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                </div>

                                 <div class="col-md-3">
                                     <div class="form-group">
                                    <label for="">CURP</label>
                                    <input type="text" maxlength="18" class="form-control"  v-model="curp" placeholder="CURP">
                                </div>
                                 </div>

                                  <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">RFC <span style="color:red;" v-show="rfc==''">(*)</span></label>
                                        <input type="text" maxlength="10" class="form-control"  v-model="rfc" placeholder="RFC">
                                    </div>
                                 </div>
                                       
                                <div align="left" class="col-md-1">
                                   <div class="form-group">
                                    <label for="">Homoclave</label>
                                         <input type="text" maxlength="3" class="form-control"  v-model="homoclave" placeholder="AA0">
                                   </div>
                                </div>

                                 
                                <div class="col-md-3">
                                     <div class="form-group">
                                    <label for="">NSS <span style="color:red;" v-show="nss==''">(*)</span></label>
                                    <input type="text" maxlength="11" pattern="\d*" class="form-control" v-on:keypress="isNumber($event)" v-model="nss" placeholder="NSS">
                                </div>
                                </div>

                                
                            </div>
                  <!--  lugar de contacto , clasificacion y otros-->
                        <div class="form-group row border">
                              <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Lugar de contacto </label>
                                          <select class="form-control" v-model="lugar_contacto" >
                                            <option value="0">Seleccione</option>
                                            <option value="Asesor externo">Asesor externo</option>
                                            <option value="Oficina central">Oficina central</option>
                                            <option value="Modulo">Modulo</option>
                                            <option value="Pagina web">Pagina web</option>
                                            <option v-for="fraccionamientos in arrayFraccionamientos2" :key="fraccionamientos.nombre" :value="fraccionamientos.nombre" v-text="fraccionamientos.nombre"></option>
                                            <option v-for="lugares in arrayLugarContacto" :key="lugares.id" :value="lugares.nombre" v-text="lugares.nombre"></option>
                                    </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                    <label for="">Clasificacion</label>
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
                                        <label for="">Proyecto en el que esta interesado <span style="color:red;" v-show="proyecto_interes_id==0">(*)</span></label>
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

                                <div class="col-md-12">
                                </div>

                                 <div class="col-md-3">
                                     <div class="form-group">
                                  <label for="">Medio donde se entero de nosotros <span style="color:red;" v-show="publicidad_id==0">(*)</span></label>
                                    <select class="form-control" v-model="publicidad_id" @click="nombre_recomendado=''" >
                                            <option value="0">Seleccione</option>
                                            <option v-for="medios in arrayMediosPublicidad" :key="medios.id" :value="medios.id" v-text="medios.nombre"></option>    
                                    </select>
                                </div>
                                </div>

                                <div class="col-md-4" v-if="publicidad_id == 1">
                                     <div class="form-group">
                                    <label for="">Nombre de la persona que te recomendo </label>
                                     <input type="text" class="form-control" v-model="nombre_recomendado" placeholder="Nombre">
                                </div>
                                </div>


                        </div>
                  <!--  apartado  de datos vive en casa , edo civil, conyuge-->
                           <div class="form-group row border" >    
                                        
                                <div class="col-md-3">
                                    <div class="form-group">
                                    <label for="">Vive en casa <span style="color:red;" v-show="tipo_casa==0">(*)</span></label>
                                        <select class="form-control" v-model="tipo_casa" >
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

                                <div class="col-md-6">
                                    <div class="bmd-form-group checkbox">
                                    <label for=""> Conyuge o coacreditado </label>
                                    <br>
                                   <input id="checkcoa" type="checkbox" v-model="coacreditado">
                                </div>
                                </div>

                                <div class="col-md-1"  v-if="coacreditado==true">
                                    <div class="form-group">
                                        <button @click="abrirModal('coacreditado','registrar')" style="margin-top:1.5rem;" class="btn btn-success form-control btnagregar" title="Agregar nuevo coacreditado"><i class="icon-plus"></i></button>
                                    </div>
                                </div>

                                <div class="col-md-3" v-if="coacreditado==true">
                                    <div class="form-group">
                                        <label for="">Buscar coacreditado... </label>
                                        <v-select 
                                            :on-search="selectCoacreditadoVueselect"
                                            label="n_completo"
                                            :options="arrayCoacreditados"
                                            placeholder="Buscar..."
                                            :onChange="getDatosCoacreditado"
                                        >
                                        </v-select>
                                    </div>
                                </div>

                                <div class="col-md-3" v-if="coacreditado==true">
                                     <div class="form-group">
                                    <label for="">Parentesco </label>
                                    <input type="text" class="form-control" v-model="parentesco_coa" placeholder="Parentesco">
                                </div>
                                </div>

                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label for="">Observaciones <span style="color:red;" v-show="observacion==''">(*)</span></label>
                                        <textarea rows="3" cols="30" v-model="observacion" class="form-control" placeholder="Observaciones"></textarea>
                                    </div>
                                </div>
                                 
                                 <div class="col-md-12">
                                    <!-- Div para mostrar los errores que mande validerFraccionamiento -->
                                    <div v-show="errorProspecto" class="form-group row div-error">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMostrarMsjProspecto" :key="error" v-text="error">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                    
                            <div class="form-group">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-secondary" @click="ocultarDetalle()"> Cerrar </button>
                                    <button type="button" class="btn btn-primary" @click="registrarProspecto()"> Guardar </button>
                                </div>
                            </div>

                                
                                
                            </div>

                 

                        </div>
                    </template>

     <!-- Div Card Body para actualizar -->
                    <template v-else-if="listado == 3">
                        <div class="card-body"> 
                            <div class="form-group row border">
                                <div class="col-md-12">
                                    <div class="form-group">
                                  <center> <h2>Prospecto</h2> </center>
                                    </div>
                                </div> 

                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="">Nombre <span style="color:red;" v-show="nombre==''">(*)</span> </label>
                                    <input type="text" class="form-control" v-model="nombre" placeholder="Nombre">
                                    </div>
                                </div> 


                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="">Apellidos <span style="color:red;" v-show="apellidos==''">(*)</span></label>
                                    <input type="text" class="form-control" v-model="apellidos" placeholder="Apellidos">
                                </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="">Sexo <span style="color:red;" v-show="sexo==''">(*)</span></label>
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
                                    <input type="text" maxlength="10" pattern="\d*" class="form-control" v-on:keypress="isNumber($event)" v-model="telefono" placeholder="Telefono">
                                </div>
                                </div>

                                  <div class="col-md-4">
                                     <div class="form-group">
                                    <label for="">Celular <span style="color:red;" v-show="celular==''">(*)</span></label>
                                    <input type="text" pattern="\d*" maxlength="10" class="form-control" v-on:keypress="isNumber($event)" v-model="celular" placeholder="Celular">
                                </div>
                                 </div>

                                 <div class="col-md-4">
                                     <div class="form-group">
                                    <label for="">Email personal <span style="color:red;" v-show="email==''">(*)</span></label>
                                    <input type="text" class="form-control" v-model="email" placeholder="E-mail">
                                </div>
                                 </div>

                                   <div class="col-md-8">
                                    <div class="form-group">
                                    <label for="">Empresa <span style="color:red;" v-show="empresa==0">(*)</span></label>
                                        <v-select 
                                            :on-search="selectEmpresaVueselect"
                                            label="nombre"
                                            :options="arrayEmpresa"
                                            placeholder="Buscar empresa..."
                                            :onChange="getDatosEmpresa"
                                        >
                                        </v-select>
                                         <input v-if="empresa != null" type="text" class="form-control" readonly  v-model="empresa">
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
                                    <label for="">Fecha de nacimiento <span style="color:red;" v-show="fecha_nac==''">(*)</span></label>
                                    <input type="date" class="form-control"  v-model="fecha_nac" >
                                </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Lugar de nacimiento <span style="color:red;" v-show="lugar_nacimiento==''">(*)</span></label>
                                        <select class="form-control" v-model="lugar_nacimiento">
                                            <option value="">Seleccione</option>
                                            <option v-for="estados in arrayEstados" :key="estados.estado" :value="estados.estado" v-text="estados.estado"></option>    
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                </div>

                                 <div class="col-md-3">
                                     <div class="form-group">
                                    <label for="">CURP</label>
                                    <input type="text" maxlength="18" class="form-control"  v-model="curp" placeholder="CURP">
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
                                         <input type="text" maxlength="3" class="form-control"  v-model="homoclave" placeholder="AA0">
                                   </div>
                                </div>

                                 
                                <div class="col-md-3">
                                     <div class="form-group">
                                    <label for="">NSS <span style="color:red;" v-show="nss==''">(*)</span></label>
                                    <input type="text" maxlength="11" pattern="\d*" class="form-control" v-on:keypress="isNumber($event)" v-model="nss" placeholder="NSS">
                                </div>
                                </div>

                                
                            </div>
                  <!--  lugar de contacto , clasificacion y otros-->
                        <div class="form-group row border">
                              <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Lugar de contacto </label>
                                          <select class="form-control" v-model="lugar_contacto" >
                                            <option value="0">Seleccione</option>
                                            <option value="Asesor externo">Asesor externo</option>
                                            <option value="Oficina central">Oficina central</option>
                                            <option value="Modulo">Modulo</option>
                                            <option value="Pagina web">Pagina web</option>
                                            <option v-for="fraccionamientos in arrayFraccionamientos2" :key="fraccionamientos.id" :value="fraccionamientos.nombre" v-text="fraccionamientos.nombre"></option>
                                            <!-- <option v-for="lugares in arrayLugarContacto" :key="lugares.id" :value="lugares.nombre" v-text="lugares.nombre"></option> -->
                                    </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                    <label for="">Clasificacion</label>
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
                                        <label for="">Proyecto en el que esta interesado <span style="color:red;" v-show="proyecto_interes_id==0">(*)</span></label>
                                        <v-select 
                                            :on-search="selectFraccionamientoVueselect"
                                            label="nombre"
                                            :options="arrayFraccionamientos"
                                            placeholder="Buscar proyecto..."
                                            :onChange="getDatosFraccionamiento"
                                        >
                                        </v-select>
                                         <input type="text" v-if="proyecto != null" class="form-control" readonly  v-model="proyecto">
                                    </div>
                                </div>

                                 <div class="col-md-3">
                                     <div class="form-group">
                                  <label for="">Medio donde se entero de nosotros <span style="color:red;" v-show="publicidad_id==0">(*)</span></label>
                                    <select class="form-control" v-model="publicidad_id" >
                                            <option value="0">Seleccione</option>
                                            <option v-for="medios in arrayMediosPublicidad" :key="medios.id" :value="medios.id" v-text="medios.nombre"></option>    
                                    </select>
                                </div>
                                </div>

                                <div class="col-md-4" v-if="publicidad_id == 1">
                                     <div class="form-group">
                                    <label for="">Nombre de la persona que te recomendo </label>
                                     <input type="text" class="form-control" v-model="nombre_recomendado" placeholder="Nombre">
                                </div>
                                </div>

                        </div>

                  <!--  apartado  de datos vive en casa , edo civil, conyuge-->
                        <div class="form-group row border" >    
                                        
                                <div class="col-md-3">
                                    <div class="form-group">
                                    <label for="">Vive en casa <span style="color:red;" v-show="tipo_casa==0">(*)</span></label>
                                        <select class="form-control" v-model="tipo_casa" >
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

                                <div class="col-md-6">
                                    <div class="bmd-form-group checkbox">
                                    <label for=""> Conyuge o coacreditado </label>
                                    <br>
                                   <input id="checkcoa" type="checkbox" v-model="coacreditado">
                                </div>
                                </div>

                                <div class="col-md-1"  v-if="coacreditado==true">
                                    <div class="form-group">
                                        <button @click="abrirModal('coacreditado','registrar')" style="margin-top:1.5rem;" class="btn btn-success form-control btnagregar" title="Agregar nuevo coacreditado"><i class="icon-plus"></i></button>
                                    </div>
                                </div>

                                <div class="col-md-3" v-if="coacreditado==true">
                                    <div class="form-group">
                                        <label for="">Buscar coacreditado... </label>
                                        <v-select 
                                            :on-search="selectCoacreditadoVueselect"
                                            label="n_completo"
                                            :options="arrayCoacreditados"
                                            placeholder="Buscar..."
                                            :onChange="getDatosCoacreditado"
                                        >
                                        </v-select>
                                        <input type="text" v-if="conyugeNom != null" class="form-control" readonly v-model="conyugeNom">
                                    </div>
                                </div>

                                <div class="col-md-3" v-if="coacreditado==true">
                                     <div class="form-group">
                                    <label for="">Parentesco </label>
                                    <input type="text" class="form-control" v-model="parentesco_coa" placeholder="Parentesco">
                                </div>
                                </div>

                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label for="">Observaciones <span style="color:red;" v-show="observacion==''">(*)</span></label>
                                        <textarea rows="3" cols="30" v-model="observacion" class="form-control" placeholder="Observaciones"></textarea>
                                    </div>
                                </div>
                                 
                                 <div class="col-md-12">
                                    <!-- Div para mostrar los errores que mande validerFraccionamiento -->
                                    <div v-show="errorProspecto" class="form-group row div-error">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMostrarMsjProspecto" :key="error" v-text="error">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                    
                            <div class="form-group">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-secondary" @click="ocultarDetalle()"> Cerrar </button>
                                    <button type="button" class="btn btn-primary" @click="actualizarProspecto()"> Actualizar </button>
                                </div>
                            </div>

                                
                                
                            </div>

                 

                        </div>
                    </template>
                   
                    
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
           
             <!--Inicio del modal agregar/actualizar coacreditado-->
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
                                    <input type="text" pattern="\d*" maxlength="10" class="form-control" v-on:keypress="isNumber($event)" v-model="telefono_coa" placeholder="Telefono">
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
                                            <option value="">Seleccione</option>
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
                                    <label for="">Lugar de nacimiento <span style="color:red;" v-show="lugar_nacimiento_coa==''">(*)</span></label>
                                    <select class="form-control" v-model="lugar_nacimiento_coa">
                                        <option value="">Seleccione</option>
                                        <option v-for="estados in arrayEstados" :key="estados.estado" :value="estados.estado" v-text="estados.estado"></option>    
                                    </select>
                                </div>

                               <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">CURP</label>
                                       <div class="col-md-4">
                                    <input type="text" maxlength="18" class="form-control"  v-model="curp_coa" placeholder="CURP">
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
                                    <input type="text" maxlength="11" pattern="\d*" class="form-control" v-on:keypress="isNumber($event)" v-model="nss_coa" placeholder="NSS">
                                    </div>
                                 </div>

                               <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Vive en casa </label>
                                    <div class="col-md-3">
                                        <select class="form-control" v-model="tipo_casa_coa" >
                                            <option value="0">Seleccione</option>  
                                            <option value="De familiares">De familiares</option>
                                            <option value="Prestada">Prestada</option>
                                            <option value="Propia">Propia</option>
                                            <option value="Rentada">Rentada</option>
                                        </select>
                                    </div>
                                </div>

                               <div class="form-group row">
                                  <label class="col-md-3 form-control-label" for="text-input">Estado civil</label>
                                    <div class="col-md-3">
                                        <select class="form-control" v-model="e_civil_coa" >
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


                                 
                                    <!-- Div para mostrar los errores que mande validerFraccionamiento -->
                                    <div v-show="errorCoacreditado" class="form-group row div-error">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMostrarMsjCoacreditado" :key="error" v-text="error">
                                            </div>
                                        </div>
                                    </div>
                               
 
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarCoacreditado()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarEmpresa()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->

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
                nombre:'',
                apellidos:'',
                telefono : '',
                celular : '',
                email:'',
                email_inst:'',
                nss:'',
                sexo:'',
                fecha_nac: '',
                curp:'',
                rfc:'',
                homoclave: '',
                e_civil: 0,
                tipo_casa:0,
                coacreditado: 0,
                publicidad_id: 0,
                proyecto_interes_id: 0,
                nombre_recomendado:'',
                proyecto: '',
                empresa: '',
                observacion:'',
                lugar_contacto: 0,
                lugar_nacimiento:'',
                conyugeNom: '',


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
                lugar_nacimiento_coa:'',
                tipo_casa_coa:0,


                arrayEmpresa: [],
                arrayMediosPublicidad:[],
                arrayEstados:[],

                modal : 0,
                modal3: 0,
                listado:1,
                tituloModal : '',
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
                arrayFraccionamientosVue : [],
                arrayObservacion: [],
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
        },
       
        methods : {
            /**Metodo para mostrar los registros */
            listarProspectos(page, buscar, criterio){
                let me = this;
                var url = '/clientes?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayProspectos = respuesta.personas.data;
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
                me.arrayFraccionamientos=[];
                var url = '/select_medio_publicidad';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayMediosPublicidad = respuesta.medios_publicitarios;
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
            selectFraccionamientos(){
                let me = this;
                me.arrayFraccionamientos=[];
                var url = '/select_fraccionamiento';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayFraccionamientos2 = respuesta.fraccionamientos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectFraccionamientoVueselect(search, loading){
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
                me.proyecto_interes_id = val1.id;
            },
            selectCoacreditadoVueselect(search, loading){
                let me = this;
                loading(true)

                var url = '/select_coacreditadoVue?filtro='+search;
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    q: search
                    me.arrayCoacreditados = respuesta.coacreditados;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getDatosCoacreditado(val1){
                let me = this;
                me.loading = true;
                me.nombre_coa = val1.nombre;
                me.apellidos_coa = val1.apellidos;
                me.telefono_coa = val1.telefono;
                me.celular_coa = val1.celular;
                me.email_coa = val1.email;
                me.email_institucional_coa = val1.email_institucional;
                me.sexo_coa = val1.sexo;
                me.fecha_nac_coa = val1.f_nacimiento;
                me.edo_civil_coa = val1.edo_civil;
                me.rfc_coa = val1.rfc;
                me.curp_coa = val1.curp;
                me.nss_coa = val1.nss;
                me.homoclave_coa = val1.homoclave;
                me.tipo_casa_coa = val1.tipo_casa;
                me.lugar_nacimiento_coa = val1.lugar_nacimiento;
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
                    'lugar_nacimiento': this.lugar_nacimiento,

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
                    'lugar_nacimiento_coa': this.lugar_nacimiento_coa,
                    'nombre_recomendado':this.nombre_recomendado,
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
                    'lugar_nacimiento':this.lugar_nacimiento_coa,
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
                    'lugar_nacimiento': this.lugar_nacimiento,
                    'nombre_recomendado':this.nombre_recomendado,

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
                    'lugar_nacimiento_coa': this.lugar_nacimiento_coa,
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
            desactivarProspecto(id){
               swal({
                title: 'Esta seguro de desactivar a este cliente?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/clientes/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listado=1;
                        me.limpiarDatos();
                        me.listarProspectos(1,'','ini_obras.clave');
                        swal(
                        'Desactivado!',
                        'El registro ha sido desactivado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },
            activarProspecto(id){
               swal({
                title: 'Esta seguro de activar a este cliente?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/clientes/activar',{
                        'id': id
                    }).then(function (response) {
                    me.listado=1;
                    me.limpiarDatos();
                    me.listarProspectos(1,'','ini_obras.clave');
                        swal(
                        'Activado!',
                        'El registro ha sido activado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
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
                this.lugar_nacimiento='';

                this.nombre_coa='';
                this.parentesco_coa='';
                this.apellidos_coa='';
                this.telefono_coa = '';
                this.celular_coa = '';
                this.email_coa='';
                this.email_institucional_coa='';
                this.lugar_nacimiento_coa='';
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
            mostrarDetalle(){
                this.limpiarDatos();
                this.listado=0;
            },
            ocultarDetalle(){
                this.listado=1;
            },

            actualizarProspectoBTN(id){
              
                let me= this;
                this.listado=3;
                var arrayDatosProspecto=[];
                var url = '/clientes/obtenerDatos?id=' + id;

                 axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayDatosProspecto = respuesta.personas;

                    me.nombre= me.arrayDatosProspecto[0]['nombre'];
                    me.apellidos= me.arrayDatosProspecto[0]['apellidos'];
                    me.sexo= me.arrayDatosProspecto[0]['sexo'];
                    me.telefono= me.arrayDatosProspecto[0]['telefono'];
                    me.celular= me.arrayDatosProspecto[0]['celular'];
                    me.email_inst= me.arrayDatosProspecto[0]['email_institucional'];
                    me.email = me.arrayDatosProspecto[0]['email'];
                    me.empresa=me.arrayDatosProspecto[0]['empresa'];
                    me.fecha_nac=me.arrayDatosProspecto[0]['f_nacimiento'];
                    me.lugar_nacimiento =me.arrayDatosProspecto[0]['lugar_nacimiento'];
                    me.curp=me.arrayDatosProspecto[0]['curp'];
                    me.rfc=me.arrayDatosProspecto[0]['rfc'];
                    me.homoclave=me.arrayDatosProspecto[0]['homoclave'];
                    me.nss=me.arrayDatosProspecto[0]['nss'];
                    me.lugar_contacto=me.arrayDatosProspecto[0]['lugar_contacto'];
                    me.clasificacion=me.arrayDatosProspecto[0]['clasificacion'];
                    me.proyecto_interes_id=me.arrayDatosProspecto[0]['proyecto_interes_id'];
                    me.publicidad_id=me.arrayDatosProspecto[0]['publicidad_id'];
                    me.tipo_casa=me.arrayDatosProspecto[0]['tipo_casa'];
                    me.e_civil=me.arrayDatosProspecto[0]['edo_civil'];
                    me.parentesco_coa=me.arrayDatosProspecto[0]['parentesco_coa'];
                    me.coacreditado=me.arrayDatosProspecto[0]['coacreditado'];
                    me.conyugeNom = me.arrayDatosProspecto[0]['n_completo_coa'];
                    me.nombre_coa = me.arrayDatosProspecto[0]['nombre_coa'];
                    me.apellidos_coa = me.arrayDatosProspecto[0]['apellidos_coa'];
                    me.lugar_nacimiento_coa =me.arrayDatosProspecto[0]['lugar_nacimiento_coa'];
                    me.proyecto = me.arrayDatosProspecto[0]['proyecto'];

                    
                    
                    me.id=id;
                })
                .catch(function (error) {
                    console.log(error);
                });
               

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
                this.lugar_nacimiento_coa='';
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

         abrirModal(modelo, accion,data =[]){
                switch(modelo){
                    case "coacreditado":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Conyuge o coacreditado';
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
                                this.tipoAccion = 1;
                                this.lugar_nacimiento_coa='';
                                break;
                            }
                            
                        }
                    }
                }
            }

           
        },
        mounted() {
            this.listarProspectos(1,this.buscar,this.criterio);
            this.selectMedioPublicidad();
            this.selectFraccionamientos();
            this.selectLugarContacto();
            this.selectEstados();
          
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
