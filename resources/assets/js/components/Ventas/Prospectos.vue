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
                                <div class="col-md-8" v-if="criterio =='clientes.created_at'">
                                    <div class="input-group">
                                        <!--Criterios para el listado de busqueda -->
                                        <select class="form-control col-md-4" v-model="criterio" @click="limpiarBusqueda()">
                                            <option value="personal.nombre">Nombre</option>
                                            <option value="personal.rfc">RFC</option>
                                            <option value="clientes.curp">CURP</option>
                                            <option value="clientes.nss">NSS</option>
                                            <option value="fraccionamientos.nombre">Proyecto</option>
                                            <option v-if="rolId != 2" value="clientes.created_at">Fecha de alta</option>
                                            <option v-if="rolId != 2" value="clientes.vendedor_id">Asesor</option>
                                        </select>
                                        <template v-if="criterio=='clientes.created_at'">
                                            <input v-if="criterio=='clientes.created_at'" type="text" placeholder="Desde" onfocus="(this.type='date')" onblur="(this.type='text')" v-model="buscar" class="form-control">
                                            <input v-if="criterio=='clientes.created_at' && rolId != 2" type="text" placeholder="Hasta" onfocus="(this.type='date')" onblur="(this.type='text')" v-model="buscar2" class="form-control">
                                        </template>
                                        
                                    </div>
                                    <div class="input-group" v-if="criterio=='clientes.created_at'">
                                        <select class="form-control" v-if="criterio=='clientes.created_at' && rolId != 2" v-model="buscar3" >
                                            <option value="">Seleccione</option>
                                            <option v-for="proyecto in arrayFraccionamientos" :key="proyecto.id" :value="proyecto.id" v-text="proyecto.nombre"></option>
                                        </select>
                                        <select class="form-control" v-model="b_publicidad">
                                            <option value="">Seleccione</option>
                                            <option v-for="medios in arrayMediosPublicidad" :key="medios.id" :value="medios.id" v-text="medios.nombre"></option>    
                                        </select>
                                    </div>
                                    <div class="input-group">
                                        <select class="form-control" v-model="b_clasificacion" >
                                            <option value="">Clasificación</option>
                                            <option value="1">No viable</option>
                                            <option value="2">Tipo A</option>
                                            <option value="3">Tipo B</option>
                                            <option value="4">Tipo C</option>
                                            <option value="5">Ventas</option>
                                            <option value="6">Cancelado</option>                               
                                            <option value="7">Coacreditado</option>  
                                        </select>
                                        <button type="submit" @click="listarProspectos(1,buscar,buscar2,buscar3,b_clasificacion,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                    
                                        <a v-if="rolId == 2" :href="'/prospectos/excel?buscar=' + buscar + '&buscar2=' + buscar2 + '&buscar3=' + buscar3 + '&b_clasificacion=' + b_clasificacion + '&b_publicidad=' + b_publicidad + '&criterio=' + criterio"  class="btn btn-success"><i class="fa fa-file-text"></i>Excel</a>
                                        <a v-if="rolId != 2" :href="'/prospectos/excel/gerente?buscar=' + buscar + '&buscar2=' + buscar2 + '&buscar3=' + buscar3 + '&b_clasificacion=' + b_clasificacion + '&b_publicidad=' + b_publicidad + '&criterio=' + criterio"  class="btn btn-success"><i class="fa fa-file-text"></i>Excel</a>
                                        <span style="font-size: 1em; text-align:center;" class="badge badge-dark" v-text="'Clientes en total: '+ contador"> </span>
                                </div>
                                <div class="col-md-8" v-else>
                                    <div class="input-group">
                                        <!--Criterios para el listado de busqueda -->
                                        <select class="form-control col-md-4" v-model="criterio" @click="limpiarBusqueda()">
                                            <option value="personal.nombre">Nombre</option>
                                            <option value="personal.rfc">RFC</option>
                                            <option value="clientes.curp">CURP</option>
                                            <option value="clientes.nss">NSS</option>
                                            <option value="fraccionamientos.nombre">Proyecto</option>
                                            <option value="clientes.created_at">Fecha de alta</option>
                                            <option v-if="rolId != 2" value="clientes.vendedor_id">Asesor</option>
                                        </select>
                                        <template v-if="criterio=='clientes.vendedor_id'">
                                            <select class="form-control" v-model="buscar" >
                                                <option value="">Seleccione</option>
                                                <option v-for="asesor in arrayAsesores" :key="asesor.id" :value="asesor.id" v-text="asesor.nombre + ' '+ asesor.apellidos"></option>
                                            </select>
                                            <input type="text" v-if="buscar" placeholder="Nombre del cliente" v-model="buscar2" @keyup.enter="listarProspectos(1,buscar,buscar2,buscar3,b_clasificacion,criterio)" class="form-control">
                                        </template>
                                        <input v-else type="text" v-model="buscar" @keyup.enter="listarProspectos(1,buscar,buscar2,buscar3,b_clasificacion,criterio)" class="form-control">
                                        <select class="form-control" v-model="b_clasificacion" >
                                            <option value="">Clasificación</option>
                                            <option value="1">No viable</option>
                                            <option value="2">Tipo A</option>
                                            <option value="3">Tipo B</option>
                                            <option value="4">Tipo C</option>
                                            <option value="5">Ventas</option>
                                            <option value="6">Cancelado</option>                               
                                            <option value="7">Coacreditado</option>  
                                        </select>
                                    </div>
                                    <button type="submit" @click="listarProspectos(1,buscar,buscar2,buscar3,b_clasificacion,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                        <a v-if="rolId == 2" :href="'/prospectos/excel?buscar=' + buscar + '&buscar2=' + buscar2 + '&buscar3=' + buscar3 + '&b_clasificacion=' + b_clasificacion + '&b_publicidad=' + b_publicidad + '&criterio=' + criterio"  class="btn btn-success"><i class="fa fa-file-text"></i>Excel</a>
                                        <a v-if="rolId != 2" :href="'/prospectos/excel/gerente?buscar=' + buscar + '&buscar2=' + buscar2 + '&buscar3=' + buscar3 + '&b_clasificacion=' + b_clasificacion + '&b_publicidad=' + b_publicidad + '&criterio=' + criterio"  class="btn btn-success"><i class="fa fa-file-text"></i>Excel</a>
                                        <span style="font-size: 1em; text-align:center;" class="badge badge-dark" v-text="'Clientes en total: '+ contador"> </span>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table2 table-bordered table-striped table-sm">
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
                                            <th>Clasificación</th>
                                            <th>Fecha de alta</th>
                                            <th>Observaciones</th>
                                            <th v-if="rolId != 2">Vendedor</th>
                                            <th v-if="rolId != 2">Publicidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="prospecto in arrayProspectos" :key="prospecto.id">
                                            <td class="td2">
                                            
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
                                                <button v-if="rolId != 2" type="button" @click="abrirModalCambio(prospecto)" class="btn btn-primary btn-sm">
                                                    <i class="fa fa-exchange"></i>
                                                </button>
                                            </td>
                                            <template>
                                                <td v-if="prospecto.clasificacion == 1 || prospecto.clasificacion >= 5 || prospecto.clasificacion >= 2 && prospecto.clasificacion < 5 && prospecto.diferencia < 7" class="td2" v-text="prospecto.nombre + ' ' + prospecto.apellidos "></td>                                                    
                                                <td v-else-if="prospecto.clasificacion >= 2 && prospecto.clasificacion < 5 && prospecto.diferencia >= 7 && prospecto.diferencia <= 15  " class="td2">
                                                    <span class="badge2 badge-warning">{{ prospecto.nombre.toUpperCase()+' '+prospecto.apellidos.toUpperCase()}}</span>
                                                </td>    
                                                <td v-else-if="prospecto.clasificacion >= 2 && prospecto.clasificacion < 5 && prospecto.diferencia > 15" class="td2">
                                                    <span class="badge2 badge-danger">{{ prospecto.nombre.toUpperCase()+' '+prospecto.apellidos.toUpperCase()}}</span>
                                                </td>                                                
                                            </template>
                                            <td class="td2" >
                                                 <a title="Llamar" class="btn btn-dark" :href="'tel:'+prospecto.celular"><i class="fa fa-phone fa-lg"></i></a>
                                                 <a title="Enviar whatsapp" class="btn btn-success" target="_blank" :href="'https://api.whatsapp.com/send?phone=+52'+prospecto.celular+'&text=Hola'"><i class="fa fa-whatsapp fa-lg"></i></a>
                                                 
                                            </td>
                                            <td class="td2" v-if="prospecto.email_institucional == null"> 
                                                <a title="Enviar correo" class="btn btn-secondary" :href="'mailto:'+prospecto.email"> <i class="fa fa-envelope-o fa-lg"></i> </a>
                                            </td>
                                              <td class="td2" v-else> 
                                                <a title="Enviar correo" class="btn btn-secondary" :href="'mailto:'+prospecto.email+ ';'+prospecto.email_institucional"> <i class="fa fa-envelope-o fa-lg"></i> </a>
                                            </td>
                                            <td class="td2" style="text-transform:uppercase" v-text="prospecto.rfc.toUpperCase()"></td>
                                            <td class="td2" v-text="prospecto.nss"></td>
                                            <td class="td2" style="text-transform:uppercase" v-text="prospecto.curp"></td>
                                            <td class="td2" v-text="prospecto.proyecto"></td>
                                            <td class="td2" v-if="prospecto.clasificacion==1">No viable</td>
                                            <td class="td2" v-if="prospecto.clasificacion==2">Tipo A</td>
                                            <td class="td2" v-if="prospecto.clasificacion==3">Tipo B</td>
                                            <td class="td2" v-if="prospecto.clasificacion==4">Tipo C</td>
                                            <td class="td2" v-if="prospecto.clasificacion==5">Ventas</td>
                                            <td class="td2" v-if="prospecto.clasificacion==6">Cancelado</td>
                                            <td class="td2" v-if="prospecto.clasificacion==7">Coacreditado</td>
                                            <td class="td2" v-text="this.moment(prospecto.created_at).locale('es').format('DD/MMM/YYYY')"></td>
                                            <td class="td2"> <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right" @click="abrirModal3('prospecto','ver_todo', prospecto.nombre, prospecto.apellidos),listarObservacion(1,prospecto.id)">Ver todos</button> </td>
                                            <td class="td2" v-if="rolId != 2" v-text="prospecto.v_completo"></td>
                                            <td class="td2" v-if="rolId != 2" v-text="prospecto.publicidad"></td>
                                        </tr>                               
                                    </tbody>
                                </table>
                            </div>
                            <nav>
                                <!--Botones de paginacion -->
                                <ul class="pagination">
                                    <li class="page-item" v-if="pagination.current_page > 1">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,buscar2,buscar3,b_clasificacion,criterio)">Ant</a>
                                    </li>
                                    <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,buscar2,buscar3,b_clasificacion,criterio)" v-text="page"></a>
                                    </li>
                                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,buscar2,buscar3,b_clasificacion,criterio)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                            <button class="btn btn-sm btn-default" data-toggle="modal" data-target="#manualId">Manual</button>
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
                                    <input type="text" class="form-control" v-model="nombre" placeholder="Nombre" onchange="this.value = this.value.trim()" onkeyup="this.value = this.value.replace('  ', ' ')">
                                    </div>
                                </div> 


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">1° apellido <span style="color:red;" v-show="apellidos==''">(*)</span></label>
                                        <input type="text" class="form-control" v-model="apellidos" placeholder="Apellidos" onchange="this.value = this.value.trim()" onkeyup="this.value = this.value.replace('  ', ' ')">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">2° apellido</label>
                                        <input type="text" class="form-control" v-model="apellidos2" placeholder="2° apellido" onchange="this.value = this.value.trim()" onkeyup="this.value = this.value.replace('  ', ' ')">
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

                                <div class="col-md-4">
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
                                    <label for="">Ingresos</label>
                                    <div class="input-group">
                                    <div class="input-group-addon">
                                        $
                                    </div>
                                    <input type="text" pattern="\d*" maxlength="20" class="form-control" v-on:keypress="isNumber($event)" v-model="ingreso" placeholder="ingreso">
                                    </div>
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
                                        <input type="text" name="city3" list="cityname3" class="form-control" v-model="lugar_nacimiento">
                                        <datalist id="cityname3">
                                            <option value="">Seleccione</option>
                                            <option v-for="estados in arrayEstados" :key="estados.estado" :value="estados.estado" v-text="estados.estado"></option>    
                                        </datalist>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                </div>

                                <div class="col-md-1" v-if="nombre != '' && apellidos != '' && sexo != '' && fecha_nac != '' && lugar_nacimiento != ''" style="padding-top: 30px;">
                                    <div class="form-group">
                                    <button type="button" class="btn btn-primary" @click="CreateCurp()" style="padding-left: 2px;padding-right: 2px;">
                                        CURP/RFC
                                    </button>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                     <div class="form-group">
                                    <label for="">CURP</label>
                                    <input type="text" maxlength="18" style="text-transform:uppercase" class="form-control"  v-model="curp" placeholder="CURP">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">RFC  <span style="color:red;" v-show="encuentraRFC==1"> Ya se encuentra este rfc registrado</span> <span style="color:red;" v-show="rfc==''">(*)</span></label>
                                        <input type="text" v-on:keypress="isSpace($event)" maxlength="10" style="text-transform:uppercase" class="form-control" @keyup="selectRFC(rfc)"  v-model="rfc" placeholder="RFC">
                                    </div>
                                </div>
                                       
                                <div align="left" class="col-md-1">
                                   <div class="form-group">
                                    <label for="">Homoclave</label>
                                         <input type="text" maxlength="3" style="text-transform:uppercase" class="form-control"  v-model="homoclave" placeholder="AA0">
                                   </div>
                                </div>

                                 
                                <div class="col-md-3">
                                     <div class="form-group">
                                    <label for="">NSS</label>
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
                                            <option v-if="rolId!=2" value="7">Coacreditado</option>                                 
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


                                <div class="col-md-3">
                                     <div class="form-group">
                                  <label for="">Precio de interes para vivienda</label>
                                    <select class="form-control" v-model="precio_rango" >
                                            <option value="0">Seleccione</option>
                                            <option value="1">$600,000.00 - $800,000.00</option>
                                            <option value="2">$800,000.00 - $1,000,000.00</option>   
                                            <option value="3">$1,200,000.00 - $1,400,000.00</option> 
                                            <option value="4">$1,400,000.00 - $1,600,000.00</option>     
                                            <option value="5">$1,600,000.00 - $1,800,000.00</option> 
                                            <option value="6">$1,800,000.00 - $2,000,000.00</option>
                                            <option value="7">$2,000,000.00 - $2,200,000.00</option> 
                                            <option value="8">$2,200,000.00 - $2,400,000.00</option> 
                                            <option value="9">$2,400,000.00 - $2,600,000.00</option> 
                                    </select>
                                </div>
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
                                    <input type="text" class="form-control" v-model="nombre" placeholder="Nombre" onchange="this.value = this.value.trim()" onkeyup="this.value = this.value.replace('  ', ' ')">
                                    </div>
                                </div> 


                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="">Apellidos <span style="color:red;" v-show="apellidos==''">(*)</span></label>
                                    <input type="text" class="form-control" v-model="apellidos" placeholder="Apellidos" onchange="this.value = this.value.trim()" onkeyup="this.value = this.value.replace('  ', ' ')">
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
                                    <label for="">Ingresos</label>
                                    <div class="input-group">
                                    <div class="input-group-addon">
                                        $
                                    </div>
                                    <input type="text" pattern="\d*" maxlength="20" class="form-control" v-on:keypress="isNumber($event)" v-model="ingreso" placeholder="ingreso">
                                    </div>
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
                                        <input type="text" name="city" list="cityname" class="form-control" v-model="lugar_nacimiento">
                                        <datalist id="cityname">
                                            <option value="">Seleccione</option>
                                            <option v-for="estados in arrayEstados" :key="estados.estado" :value="estados.estado" v-text="estados.estado"></option>    
                                        </datalist>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                </div>

                                 <div class="col-md-3">
                                     <div class="form-group">
                                    <label for="">CURP</label>
                                    <input type="text" maxlength="18" style="text-transform:uppercase" class="form-control"  v-model="curp" placeholder="CURP">
                                </div>
                                 </div>

                                  <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">RFC <span style="color:red;" v-show="encuentraRFC==1"> Ya se encuentra este rfc registrado</span> <span style="color:red;" v-show="rfc==''">(*)</span></label>
                                        <input type="text" maxlength="10" style="text-transform:uppercase"  @keyup="selectRFC(rfc)" class="form-control"  disabled  v-model="rfc" placeholder="RFC">
                                    </div>
                                 </div>
                                       
                                <div align="left" class="col-md-1">
                                   <div class="form-group">
                                    <label for="">Homoclave</label>
                                         <input type="text" maxlength="3" style="text-transform:uppercase" class="form-control"  v-model="homoclave" placeholder="AA0">
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
                                         <input type="text" v-if="proyecto != null" class="form-control" readonly  v-model="proyecto">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                     <div class="form-group">
                                  <label for="">Precio de interes para vivienda</label>
                                    <select class="form-control" v-model="precio_rango" >
                                            <option value="0">Seleccione</option>
                                            <option value="1">$600,000.00 - $800,000.00</option>
                                            <option value="2">$800,000.00 - $1,000,000.00</option>   
                                            <option value="3">$1,200,000.00 - $1,400,000.00</option> 
                                            <option value="4">$1,400,000.00 - $1,600,000.00</option>     
                                            <option value="5">$1,600,000.00 - $1,800,000.00</option> 
                                            <option value="6">$1,800,000.00 - $2,000,000.00</option>
                                            <option value="7">$2,000,000.00 - $2,200,000.00</option> 
                                            <option value="8">$2,200,000.00 - $2,400,000.00</option> 
                                            <option value="9">$2,400,000.00 - $2,600,000.00</option> 
                                    </select>
                                </div>
                                </div>

                                <div class="col-md-3" v-if="rolId == 2">
                                    <div class="form-group">
                                        <label for="">Medio donde se entero de nosotros <span style="color:red;" v-show="publicidad_id==0">(*)</span></label>
                                        <select disabled class="form-control" v-model="publicidad_id" >
                                                <option value="0">Seleccione</option>
                                                <option v-for="medios in arrayMediosPublicidad" :key="medios.id" :value="medios.id" v-text="medios.nombre"></option>    
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3" v-else>
                                    <div class="form-group">
                                        <label for="">Medio donde se entero de nosotros <span style="color:red;" v-show="publicidad_id==0">(*)</span></label>
                                        <select class="form-control" v-model="publicidad_id" >
                                                <option value="0">Seleccione</option>
                                                <option v-for="medios in arrayMediosPublicidad" :key="medios.id" :value="medios.id" v-text="medios.nombre"></option>    
                                        </select>
                                    </div>
                                </div>


                                <!-- <div class="col-md-4" v-if="publicidad_id == 1 && rolId == 2">
                                    <div class="form-group">
                                        <label for="">Nombre de la persona que te recomendo </label>
                                        <input disabled type="text" class="form-control" v-model="nombre_recomendado" placeholder="Nombre">
                                    </div>
                                </div> -->

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
                                    <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right" @click="abrirModal3('prospecto','ver_todo', nombre, apellidos),listarObservacion(1,id)">Ver todos</button>
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
                                    <input type="text" class="form-control" v-model="nombre_coa" placeholder="Nombre" onchange="this.value = this.value.trim()" onkeyup="this.value = this.value.replace('  ', ' ')">
                                    </div>
                                </div> 

                               <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Apellidos</label>
                                 <div class="col-md-6">
                                    <input type="text" class="form-control" v-model="apellidos_coa" placeholder="Apellidos" onchange="this.value = this.value.trim()" onkeyup="this.value = this.value.replace('  ', ' ')">
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
                                    <input type="text" class="form-control" v-model="email_institucional_coa" placeholder="email institucional">
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
                                    <label  class="col-md-3 form-control-label" for="">Lugar de nacimiento <span style="color:red;" v-show="lugar_nacimiento_coa==''">(*)</span></label>
                                    <div class="col-md-6">
                                        <input type="text" name="city2" list="cityname2" class="form-control" v-model="lugar_nacimiento_coa">
                                        <datalist id="cityname2">
                                            <option value="">Seleccione</option>
                                            <option v-for="estados in arrayEstados" :key="estados.estado" :value="estados.estado" v-text="estados.estado"></option>    
                                        </datalist>
                                    </div>
                                </div>

                               <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">CURP</label>
                                       <div class="col-md-4">
                                    <input type="text" maxlength="18" style="text-transform:uppercase" class="form-control"  v-model="curp_coa" placeholder="CURP">
                                      </div>
                                 </div>

                            
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">RFC</label>
                                    <div class="col-md-3">
                                        <span style="color:red;" v-show="encuentraRFC==1"> Ya se encuentra este rfc registrado</span>
                                        <input type="text" maxlength="10" style="text-transform:uppercase"  @keyup="selectRFC(rfc_coa)" v-model="rfc_coa" class="form-control" placeholder="RFC" :disabled="tipoAccion == 3">
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

            <!--Inicio del modal asignar prospecto-->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal2}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="'Reasignar cliente a: ' + tituloModal2"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                    <!--Criterios para el listado de busqueda -->

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Asesor</label>
                                    <div class="col-md-6">
                                        <select class="form-control" v-model="asesor_id" >
                                            <option value="0">Seleccione</option>
                                            <option v-for="asesor in arrayAsesores" :key="asesor.id" :value="asesor.id" v-text="asesor.nombre + ' '+ asesor.apellidos"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">CLasificación</label>
                                    <div class="col-md-6">
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

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Observación</label>
                                    <div class="col-md-9">
                                        <textarea rows="1" cols="30" class="form-control" v-model="observacion" placeholder="Observaciones"></textarea>
                                    </div>
                                </div>

                                
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" class="btn btn-primary" @click="asignarProspecto()">Reasignar </button>
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
            
            <!-- Manual -->
            <div class="modal fade" id="manualId" tabindex="-1" role="dialog" aria-labelledby="manualIdTitle" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="manualIdTitle">Manual</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>
                            El modulo de mis prospectos permitirá llevar un registro ordenado de aquellos clientes que cada asesor registre o este atendiendo.
                        </p>
                        <p>
                            Para agregar un nuevo prospecto o cliente solo debe dar clic sobre el botón de “Agregar” que se encuentra 
                            en la parte superior izquierda de la pantalla, posterior aparecerá una nueva ventana donde deberá llenar 
                            los campos según se requiera.
                        </p>
                        <p>
                            Es importante saber que los registros de los prospectos o clientes debe ser único, además los clientes 
                            serán asignados al asesor que los registre y podrán ser reasignados solo por el coordinador de 
                            ventas del asesor.
                        </p>
                        <p>
                            Los prospectos serán identificados mediante su RFC por lo que es importante que al realizar la 
                            captura se verifique que el RFC sea el correcto, en caso de que el prospecto fuese registrado 
                            con anterioridad el sistema arrojara un mensaje indicando el nombre del prospecto que actualmente 
                            se encuentre registrado con ese RFC.
                        </p>
                        <p>
                            En la columna de “Nombre” podrá observar un color de fondo en el nombre del prospecto que indicara 
                            el estatus de la atención que el asesor esta prestando al prospecto, es decir, si el asesor no 
                            agrega nuevas observaciones relacionadas al seguimiento de la atención del prospecto el color 
                            podrá cambiar de entre los siguientes colores para indicar un estado. <br>
                            Rojo = más de 15 días sin comentarios de seguimiento.<br>
                            Naranja = más de 7 días sin comentarios de seguimiento.<br>
                            Sin color = menos de 7 días sin comentarios de seguimiento.
                        </p>
                        <p>
                            <strong>Cónyuge o coacreditado</strong>, en caso de que el prospecto cuente con un conyugue o coacreditado se debe 
                            registrar el conyugue o coacreditado (con la misma importancia que el prospecto ya que es un requisito 
                            para concretar la venta), dentro del campo “Buscar coacreditado...” debe escribir el nombre del coacreditado 
                            para asegurarse de que no se encuentre registrado en caso contrario puede realizar el registro de la misma 
                            manera como se registra un prospecto, solo debe seleccionar en el apartado de “Clasificación” la opción de 
                            “coacreditado”. También puede dar clic sobre el icono 
                            <button type="button" class="btn btn-success btn-sm">
                                <i class="icon-plus"></i>
                            </button>
                            y llenar los campos de la ventana que aparecerán.
                        </p>
                        <p v-if="rolId != 2">
                            <strong>Reasignar un prospecto</strong>, para reasignar un prospecto solo debe dar clic sobre el icono  
                            <button type="button" class="btn btn-primary btn-sm">
                                <i class="fa fa-exchange"></i>
                            </button>
                            , en seguida aparecerá una ventana donde podrá seleccionar el nombre del asesor al que desea reasignar el prospecto 
                            asignar una clasificación al prospecto y agregar un comentario, para finalizar solo debe dar clic 
                            sobre el botón “Reasignar” para finalizar (podrá reasignar los asesores en el momento que usted desee).
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    </div>
                </div>
            </div>
        </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    import vSelect from 'vue-select';
    export default {
        props:{
            rolId:{type: String}
        },
        data(){
            return{
                proceso:false,
                id:0,
                clasificacion:1,
                nombre:'',
                apellidos:'',
                apellidos2:'',
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
                contador: 0,
                b_publicidad:'',

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
                precio_rango: 0,
                ingreso:0,

                arrayEmpresa: [],
                arrayMediosPublicidad:[],
                arrayEstados:[],
                encuentraRFC:0,
                asesor_id:0,

                modal : 0,
                modal2 : 0,
                modal3: 0,
                listado:1,
                tituloModal : '',
                tituloModal2 : '',
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
                buscar2: '',
                buscar3:'2',
                b_clasificacion: '2',
                arrayCoacreditados : [],
                arrayProspectos: [],
                arrayFraccionamientos : [],
                arrayLugarContacto: [],
                arrayFraccionamientos2 : [],
                arrayFraccionamientosVue : [],
                arrayObservacion: [],
                arrayAsesores : [],
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
            listarProspectos(page, buscar, buscar2, buscar3, b_clasificacion, criterio){
                let me = this;
                var url = '/clientes?page=' + page + '&buscar=' + buscar + '&buscar2=' + buscar2 + '&buscar3=' + buscar3 + '&b_clasificacion=' + b_clasificacion + '&b_publicidad=' + me.b_publicidad + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayProspectos = respuesta.personas.data;
                    me.pagination = respuesta.pagination;
                    me.contador = respuesta.contadorClientes;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            isSpace: function(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode == 32)) {
                    evt.preventDefault();;
                } else {
                    return true;
                }
            },CreateCurp(){

                var special = ['!','"','#','$','%','&','(',')','*','+',',','-','.','/','^',
                                '[',']','{','}','~',"'"];
                var prepo = ["DA", "DAS", "DE", "DEL", "DER", "DI", "DIE", "DD", "EL", "LA", 
                            "LOS", "LAS", "LE", "LES", "MAC", "MC", "VAN", "VON", "Y"];
                var i;

                //{{{{{{{{{{{{{{{{{{paterno}}}}}}}}}}}}}}}}}}
                var paterno = this.apellidos.toUpperCase();
                var paterno2 = paterno.split(" ");
                var paternoL ="";

                if(paterno2.length > 1){
                    var paterno1 ="";
                    if(prepo.indexOf(paterno2[0]) >= 0){
                        for(i = 1; i < paterno2.length; i++){
                            paterno1 = paterno1+paterno2[i]+" ";
                        }
                    }else{
                        for(i = 0; i < paterno2.length; i++){
                            paterno1 = paterno1+paterno2[i]+" ";
                        }
                    }
                    paterno = paterno1.split("");
                }else{paterno = paterno2[0].split("");}
                
                for (i = 1; i < 100; i++) {
                    if (paterno[i] == "A") {
                        paternoL = paterno[i];
                        i = 101;
                    }
                    if (paterno[i] == "E") {
                        paternoL = paterno[i];
                        i = 101;
                    }
                    if (paterno[i] == "I") {
                        paternoL = paterno[i];
                        i = 101;
                    }
                    if (paterno[i] == "O") {
                        paternoL = paterno[i];
                        i = 101;
                    }
                    if (paterno[i] == "U") {
                        paternoL = paterno[i];
                        i = 101;
                    }
                }

                if(paternoL == ""){ paternoL = "X" }
                if(paterno[0] == "\u00d1"){paterno[0]="X"; }//para la Ñ
                if(special.indexOf(paterno[0]) > 0){ paterno[0] = "X";}
                if(special.indexOf(paternoL) > 0){ paternoL = "X";}

                //{{{{{{{{{{{{{{{{{{materno}}}}}}}}}}}}}}}}}}
                var materno;
                materno = this.apellidos2.toUpperCase();
                var materno2 = materno.split(" ");
                
                if(materno2.length > 1){
                    var materno1 = "";
                    if(prepo.indexOf(materno2[0]) >= 0){
                        for(i = 1; i < materno2.length; i++){
                            materno1 = materno1+materno2[i]+" ";
                        }
                    }else{
                        for(i = 0; i < materno2.length; i++){
                            materno1 = materno1+materno2[i]+" ";
                        }
                    }
                    materno = materno1.split("");
                }else{materno = materno2[0].split("");}
                
                if(materno == ""){materno[0]="X"; }
                if(materno[0] == "\u00d1"){materno[0]="X"; }//para la Ñ
                if(special.indexOf(materno[0]) > 0){ materno[0] = "X";}
                
                //{{{{{{{{{{{{{{{{{{nombre}}}}}}}}}}}}}}}}}}
                var names = ["MARIA","MA.","MA","JOSE","J","J.","DA","DAS", "DE", "DEL", "DER", 
                            "DI", "DIE", "DD", "EL", "LA", "LOS", "LAS", "LE", "LES", "MAC", 
                            "MC", "VAN", "VON", "Y"];
                var name;
                name = this.nombre.toUpperCase();
                var name2 = name.split(' ');
                        
                if(name2.length > 1){
                    var name1 = "";
                    if(names.indexOf(name2[0]) >= 0){
                        for(i = 1; i < name2.length; i++){
                            name1 = name1+name2[i]+" ";
                        }
                    }else{
                        for(i = 0; i < name2.length; i++){
                            name1 = name1+name2[i]+" ";
                        }
                    }
                    name = name1.split("");
                }else{name = name2[0].split("");}
                
                if(name[0] == "\u00d1"){name[0]="X"; }//para la Ñ
                if(special.indexOf(name[0]) > 0){ name[0] = "X";}
                
                //{{{{{{{{{{{{{{{{{{part 1}}}}}}}}}}}}}}}}}}
                var ants = ["BACA","BAKA","BUEI","BUEY","CACA","CACO","CAGA","CAGO","CAKA",
                            "CAKO","COGE","COGI","COJA","COJE","COJI","COJO","COLA","CULO",
                            "FALO","FETO","GETA","GUEI","GUEY","JETA","JOTO","KACA","KACO",
                            "KAGA","KAGO","KAKA","KAKO","KOGE","KOGI","KOJA","KOJE","KOJI",
                            "KOJO","KOLA","KULO","LILO","LOCA","LOCO","LOKA","LOKO","MAME",
                            "MAMO","MEAR","MEAS","MEON","MIAR","MION","MOCO","MOKO","MULA",
                            "MULO","NACA","NACO","PEDA","PEDO","PENE","PIPI","PITO","POPO",
                            "PUTA","PUTO","QULO","RATA","ROBA","ROBE","ROBO","RUIN","SENO",
                            "TETA","VACA","VAGA","VAGO","VAKA","VUEI","VUEY","WUEI","WUEY"];

                var p1 = paterno[0] + paternoL + materno[0] + name[0];
                
                if(ants.indexOf(p1) >= 0){
                    var x = p1.split("");
                    x[1] = "X";
                    p1="";
                    for(i=0; i < 4; i++){
                        p1=p1+x[i];
                    }
                }

                //{{{{{{{{{{{{{{{{{{date}}}}}}}}}}}}}}}}}}
                var date = this.fecha_nac.split('');
                var p2 = date[2]+date[3]+date[5]+date[6]+date[8]+date[9];

                //{{{{{{{{{{{{{{{{{{part 3}}}}}}}}}}}}}}}}}}
                var sex = this.sexo;
                var p3 = "";
                if(sex == "F"){p3 = "M";}
                if(sex == "M"){p3 = "H";}

                //{{{{{{{{{{{{{{{{{{estado}}}}}}}}}}}}}}}}}}
                var estado = ["Aguascalientes","Baja California","Baja California Sur",
                            "Campeche","Coahuila de Zaragoza","Colima","Chiapas","Chihuahua",
                            "Distrito Federal","Durango","Guanajuato","Guerrero","Hidalgo",
                            "Jalisco","México","Michoacán de Ocampo","Morelos","Nayarit","Nuevo León",
                            "Oaxaca","Puebla","Querétaro","Quintana Roo","San Luis Potosí",
                            "Sinaloa","Sonora","Tabasco","Tamaulipas","Tlaxcala","Veracruz de Ignacio de la Llave",
                            "Yucatán","Zacatecas","EXTRANJERO"];
                var ef = ["AS","BC","BS","CC","CL","CM","CS","CH","DF","DG","GT","GR","HG",
                            "JC","MC","MN","MS","NT","NL","OC","PL","QT","QR","SP","SL","SR",
                            "TC","TS","TL","VZ","YN","ZS","NE"];
                            
                var stat = this.lugar_nacimiento;
                var p4 ="";
                p4= ef[estado.indexOf(stat)];

                //{{{{{{{{{{{{{{{{{{conson}}}}}}}}}}}}}}}}}}
                var abc = ["B","C","D","F","G","H","J","K","L","M","N","Ñ","P","Q","R","S",
                            "T","V","W","X","Y","Z","\u00d1"];
                var cname = ["MARIA","JOSE"];
                var c14 = "";
                var vtemp = "";
                var i2 ="";

                c14 = this.apellidos.toUpperCase().split(" ");

                if(c14.length > 1){
                    for(i=0;i < c14.length;i++){
                        vtemp = c14[i].split("");
                        for(i2 = 1; i2 < vtemp.length;i2++){
                            if(abc.indexOf(vtemp[i2])>=0){
                                c14 = vtemp[i2];
                                i = c14.length+1;
                                i2 = vtemp.length+1
                            }
                        }
                    }
                }else{
                    vtemp = c14[0].split("");
                    for(i2 = 1; i2 < vtemp.length;i2++){
                        if(abc.indexOf(vtemp[i2])>=0){
                            c14 = vtemp[i2];
                            i = c14.length+1;
                            i2 = vtemp.length+1
                        }
                    }
                }
                
                if(abc.indexOf(c14)>=0){}else{
                    c14 = "X";
                }
                if(c14 == "\u00d1"){c14="X"; }//para la Ñ
                //{{{{{{p15}}}}}}
                var c15 = "";

                c15 = this.apellidos2.toUpperCase().split(" ");

                if(c15.length > 1){
                    for(i=0;i < c15.length;i++){
                        vtemp = c15[i].split("");
                        for(i2 = 1; i2 < vtemp.length;i2++){
                            if(abc.indexOf(vtemp[i2])>=0){
                                c15 = vtemp[i2];
                                i = c15.length+1;
                                i2 = vtemp.length+1
                            }
                        }
                    }
                }else{
                    vtemp = c15[0].split("");
                    for(i2 = 1; i2 < vtemp.length;i2++){
                        if(abc.indexOf(vtemp[i2])>=0){
                            c15 = vtemp[i2];
                            i = c15.length+1;
                            i2 = vtemp.length+1
                        }
                    }
                }
                
                if(abc.indexOf(c15)>=0){}else{
                    c15 = "X";
                }
                if(c15 == "\u00d1"){c15="X"; }//para la Ñ
                //{{{{{{p16}}}}}}
                var c16 = "";

                c16 = this.nombre.toUpperCase().split(" ");

                if(c16.length > 1){
                    if(cname.indexOf(c16[0]) >= 0){
                        for(i=1;i < c16.length;i++){
                            vtemp = c16[i].split("");
                            for(i2 = 1; i2 < vtemp.length;i2++){
                                if(abc.indexOf(vtemp[i2])>=0){
                                    c16 = vtemp[i2];
                                    i = c16.length+1;
                                    i2 = vtemp.length+1
                                }
                            }
                        }
                    }else{
                        for(i=0;i < c16.length;i++){
                            vtemp = c16[i].split("");
                            for(i2 = 1; i2 < vtemp.length;i2++){
                                if(abc.indexOf(vtemp[i2])>=0){
                                    c16 = vtemp[i2];
                                    i = c16.length+1;
                                    i2 = vtemp.length+1
                                }
                            }
                        }
                    }
                }else{
                    vtemp = c16[0].split("");
                    for(i2 = 1; i2 < vtemp.length;i2++){
                        if(abc.indexOf(vtemp[i2])>=0){
                            c16 = vtemp[i2];
                            i = c16.length+1;
                            i2 = vtemp.length+1
                        }
                    }
                }
                
                if(abc.indexOf(c16)>=0){}else{
                    c16 = "X";
                }
                if(c16 == "\u00d1"){c16="X"; }//para la Ñ
                
                this.curp = p1 + p2 + p3 + p4 + c14 + c15 + c16;
                this.rfc = p1 + p2;

                this.selectRFC(this.rfc);
        },
            limpiarBusqueda(){
                let me=this;
                me.buscar= "";
                me.b_clasificacion = "";
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
            selectFraccionamientos2(){
                let me = this;
                me.arrayFraccionamientos=[];
                var url = '/select_fraccionamiento2?filtro=';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayFraccionamientos = respuesta.fraccionamientos;
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
            selectAsesores(){
                let me = this;
                me.arrayAsesores=[];
                var url = '/select/asesores';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayAsesores = respuesta.personas;
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
                    console.log(url);
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },

            cambiarPagina(page, buscar, buscar2, buscar3, b_clasificacion, criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarProspectos(page,buscar,buscar2,buscar3,b_clasificacion,criterio);
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
                    'apellidos':this.apellidos+" "+this.apellidos2,
                    'telefono':this.telefono ,
                    'celular':this.celular ,
                    'email':this.email,
                    'email_institucional':this.email_inst,
                    'ingreso': this.ingreso,
                    'precio_rango': this.precio_rango,
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
                    me.listarProspectos(1,me.buscar,me.buscar2,me.buscar3,me.b_clasificacion,me.criterio);
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Prospecto agregado correctamente',
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
                    'ingreso': this.ingreso,
                    'precio_rango': this.precio_rango,
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
                    me.listarProspectos(me.pagination.current_page,me.buscar,me.buscar2,me.buscar3,me.b_clasificacion,me.criterio);
                    
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
                        me.listarProspectos(1,me.buscar,me.buscar2,me.buscar3,me.b_clasificacion,me.criterio);
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
            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
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
                    me.listarProspectos(1,me.buscar,me.buscar2,me.buscar3,me.b_clasificacion,me.criterio);
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
                if(this.rfc=='' || this.rfc.length < 10) 
                    this.errorMostrarMsjProspecto.push("RFC no valido");
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
                this.ingreso='';
                this.precio_rango='0';
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

            selectRFC(rfc){
                var url = '/select_rfcs?rfc=' + rfc;
                let me = this;
                me.encuentraRFC=0;
                axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.encuentraRFC = respuesta.rfc1; 

                if(me.encuentraRFC==1) {
                    var vendedorrfc = [];
                    var nombrevendedor = '';
                    vendedorrfc = respuesta.vendedor;
                    nombrevendedor = vendedorrfc[0]['nombre'] + ' ' + vendedorrfc[0]['apellidos'];
                    Swal({
                    title: 'Este RFC ya ha sido agregado por: ' + nombrevendedor ,
                    animation: false,
                    customClass: 'animated tada'
                    })
                } 
                })
                .catch(function (error) {
                    console.log(error);
                });


            },
            asignarProspecto(){
               swal({
                title: 'Esta seguro de reasignar a este cliente?',
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

                    axios.post('/cliente/reasignar2',{
                        'id': me.id,
                        'asesor_id':me.asesor_id,
                        'clasificacion':me.clasificacion,
                        'observacion':me.observacion
                    }).then(function (response) {
                        me.listarProspectos(1,me.buscar,me.buscar2,me.buscar3,me.b_clasificacion,me.criterio);
                        me.cerrarModal();
                        swal(
                        'Hecho!',
                        'El cliente ha sido reasignado con éxito.',
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
                    me.precio_rango=me.arrayDatosProspecto[0]['precio_rango'];
                    me.ingreso=me.arrayDatosProspecto[0]['ingreso'];
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
                    me.nombre_recomendado = me.arrayDatosProspecto[0]['nombre_recomendado'];
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
                this.modal2 = 0;
                this.tituloModal2 = '';
            },

             cerrarModal3(){
                this.modal3 = 0;
                this.tituloModal3 = '';
              
            },
            
            abrirModalCambio(data=[]){
                this.selectAsesores();
                this.tituloModal2 = data['nombre'] + ' ' + data['apellidos'];
                this.modal2=1;
                this.id = data['id'];
                this.asesor_id = data['vendedor_id'];
                this.clasificacion = data['clasificacion'];
            },
  
             abrirModal3(prospectos,accion,nombre, apellidos){
             switch(prospectos){
                    case "prospecto":
                    {
                        switch(accion){
                         
                             case 'ver_todo':
                            {
                                this.modal3 =1;
                                this.tituloModal3='Observaciones de: '+nombre+' '+apellidos;
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
            this.listarProspectos(1,this.buscar,this.buscar2,this.buscar3,this.b_clasificacion,this.criterio);
            this.selectMedioPublicidad();
            this.selectFraccionamientos();
            this.selectFraccionamientos2();
            this.selectLugarContacto();
            this.selectEstados();
            this.selectAsesores();
          
        }
    }
</script>
<style>

    .modal-content{
        width: 100% !important;
        position: absolute !important;
       
    }
    .badge2 {
    display: inline-block;
    padding: 0.25em 0.4em;
    font-size: 95%;
    font-weight: bold;
    line-height: 1;
    color: #fff;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
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
