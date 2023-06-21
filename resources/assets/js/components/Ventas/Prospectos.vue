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
                        <Button v-if="listado==1" btnClass="btn-secondary" @click="mostrarDetalle()" icon="icon-people">Agregar</Button>
                        <Button v-if="rolId == 2 && reasignar == 0 && listado == 3"
                            @click="reasignToAsesor()" title="Pedir reasignar" icon="fa fa-exchange">
                        </Button>
                        <!---->
                    </div>
                    <!-- Div Card Body para listar -->
                     <template v-if="listado == 1">
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-8" v-if="criterio =='clientes.created_at'">
                                    <div class="input-group">
                                        <!--Criterios para el listado de busqueda -->
                                        <select class="form-control col-md-4" v-model="criterio" @change="limpiarBusqueda()">
                                            <option value="personal.nombre">Nombre</option>
                                            <option value="personal.email">Correo eléctronico</option>
                                            <option value="personal.rfc">RFC</option>
                                            <option value="fraccionamientos.nombre">Proyecto</option>
                                            <option value="personal.celular">Celular</option>
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
                                    </div>
                                    <div class="input-group">
                                        <select class="form-control" v-model="b_clasificacion" >
                                            <option value="">Clasificación</option>
                                            <option :value="STATUS.NO_VIABLE">No viable</option>
                                            <option :value="STATUS.A">Tipo A</option>
                                            <option :value="STATUS.B">Tipo B</option>
                                            <option :value="STATUS.C">Tipo C</option>
                                            <option :value="STATUS.VENTA">Ventas</option>
                                            <option :value="STATUS.CANCELADO">Cancelado</option>
                                            <option :value="STATUS.COACREDITADO">Coacreditado</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-8" v-else>
                                    <div class="input-group">
                                        <!--Criterios para el listado de busqueda -->
                                        <select class="form-control col-md-4" v-model="criterio" @change="limpiarBusqueda()">
                                            <option value="personal.nombre">Nombre</option>
                                            <option value="personal.rfc">RFC</option>
                                            <option value="personal.email">Correo eléctronico</option>
                                            <option value="fraccionamientos.nombre">Proyecto</option>
                                            <option value="personal.celular">Celular</option>
                                            <option value="clientes.created_at">Fecha de alta</option>
                                            <option v-if="rolId != 2" value="clientes.vendedor_id">Asesor</option>
                                        </select>
                                        <template v-if="criterio=='clientes.vendedor_id'">
                                            <select class="form-control" v-model="buscar" >
                                                <option value="">Seleccione</option>
                                                <option v-for="asesor in arrayAsesores" :key="asesor.id" :value="asesor.id" v-text="asesor.nombre + ' '+ asesor.apellidos"></option>
                                            </select>
                                            <input type="text" v-if="buscar" placeholder="Nombre del cliente" v-model="buscar2" @keyup.enter="listarProspectos(1,buscar,buscar2,buscar3)" class="form-control">
                                        </template>
                                        <input v-else type="text" v-model="buscar" @keyup.enter="listarProspectos(1,buscar,buscar2,buscar3)" class="form-control">
                                        <select class="form-control" v-model="b_clasificacion" >
                                            <option value="">Clasificación</option>
                                            <option :value="STATUS.NO_VIABLE">No viable</option>
                                            <option :value="STATUS.A">Tipo A</option>
                                            <option :value="STATUS.B">Tipo B</option>
                                            <option :value="STATUS.C">Tipo C</option>
                                            <option :value="STATUS.VENTA">Ventas</option>
                                            <option :value="STATUS.CANCELADO">Cancelado</option>
                                            <option :value="STATUS.COACREDITADO">Coacreditado</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-8" v-if="criterio=='clientes.vendedor_id'">
                                    <div class="input-group">
                                        <input type="text" class="form-control" disabled value="Seguimiento:" placeholder="Seguimiento">
                                        <select class="form-control" v-model="b_seguimiento" >
                                            <option value="">Todos</option>
                                            <option value="0">Al dia</option>
                                            <option value="1">Pendientes</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" disabled value="Prospectos reasignados:" placeholder="Prospectos reasignados">
                                        <select class="form-control" v-model="b_aux" >
                                            <option value="">No</option>
                                            <option value="1">Si</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" disabled value="Medio de publicidad:" placeholder="Medio de publicidad">
                                        <select class="form-control" v-model="b_publicidad">
                                            <option value="">Seleccione</option>
                                            <option v-for="medios in arrayMediosPublicidad" :key="medios.id" :value="medios.id" v-text="medios.nombre"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" disabled value="Apto para publicidad:" placeholder="Apto para publicidad">
                                        <select class="form-control" v-model="b_advertising" >
                                            <option value="">Todos</option>
                                            <option value="0">No</option>
                                            <option value="1">Si</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-8" >
                                    <div class="input-group">
                                        <Button icon="fa fa-search" @click="listarProspectos(1)">Buscar</Button>
                                        <a v-if="rolId == 2" :href="'/prospectos/excel?buscar=' + buscar + '&buscar2=' + buscar2 + '&buscar3=' + buscar3 + '&b_clasificacion=' + b_clasificacion + '&b_publicidad=' + b_publicidad +  + '&b_advertising=' + this.b_advertising + '&criterio=' + criterio"  class="btn btn-success"><i class="fa fa-file-text"></i>Excel</a>
                                        <a v-if="rolId != 2" :href="'/prospectos/excel/gerente?buscar=' + buscar + '&buscar2=' + buscar2 + '&buscar3=' + buscar3 + '&b_clasificacion=' + b_clasificacion + '&b_publicidad=' + b_publicidad +  + '&b_advertising=' + b_advertising + '&criterio=' + criterio"  class="btn btn-success"><i class="fa fa-file-text"></i>Excel</a>
                                        <span style="font-size: 1em; text-align:center;" class="badge badge-dark" v-text="'Clientes en total: '+ contador"> </span>
                                    </div>
                                </div>
                            </div>
                            <TableComponent>
                                <template v-slot:thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Nombre</th>
                                        <th v-if="rolId != 2"></th>
                                        <th>Celular</th>
                                        <th>Email</th>
                                        <th>RFC</th>
                                        <th>IMSS</th>
                                        <th>CURP </th>
                                        <th>Proyecto de interes</th>
                                        <th>Clasificación</th>
                                        <th>Fecha de alta</th>
                                        <th>Observaciones</th>
                                        <th></th>
                                        <th v-if="rolId != 2">Vendedor</th>
                                        <th v-if="rolId != 2">Vendedor Auxiliar</th>
                                        <th v-if="rolId != 2">Publicidad</th>
                                    </tr>
                                </template>
                                <template v-slot:tbody>
                                    <tr v-for="prospecto in arrayProspectos.data" :key="prospecto.id"
                                            :class="[prospecto.advertising == '0' ? 'table-danger' : '']"
                                        >
                                        <td class="td2">
                                            <button  v-if="prospecto.activo" title="Desactivar cliente" type="button" @click="desactivarProspecto(prospecto.id)" class="btn btn-danger btn-sm">
                                                <i class="fa fa-user-times"></i>
                                            </button>
                                            <button v-else title="Activar cliente" type="button" @click="activarProspecto(prospecto.id)" class="btn btn-success btn-sm">
                                                <i class="icon-check"></i>
                                            </button>
                                            <button  title="Editar" type="button" class="btn btn-warning btn-sm" @click="actualizarProspectoBTN(prospecto)">
                                                <i class="icon-pencil"></i>
                                            </button>
                                            <button v-if="rolId != 2" type="button" @click="abrirModalCambio(prospecto)" class="btn btn-primary btn-sm">
                                                <i class="fa fa-exchange"></i>
                                            </button>
                                        </td>
                                        <template><!-- Nombre del cliente con el semaforo -->
                                            <td v-if="prospecto.clasificacion == 1 || prospecto.clasificacion >= 5 || prospecto.clasificacion >= 2 && prospecto.clasificacion < 5 && prospecto.diferencia < 7" class="td2" v-text="prospecto.nombre + ' ' + prospecto.apellidos"></td>
                                            <td v-else-if="prospecto.clasificacion >= 2 && prospecto.clasificacion < 5 && prospecto.diferencia >= 7 && prospecto.diferencia <= 15  " class="td2">
                                                <span class="badge2 badge-warning">{{ prospecto.nombre.toUpperCase()+' '+prospecto.apellidos.toUpperCase()}}</span>
                                            </td>
                                            <td v-else-if="prospecto.clasificacion >= 2 && prospecto.clasificacion < 5 && prospecto.diferencia > 15" class="td2">
                                                <span class="badge2 badge-danger">{{ prospecto.nombre.toUpperCase()+' '+prospecto.apellidos.toUpperCase()}}</span>
                                            </td>
                                        </template>
                                        <td v-if="rolId != 2">
                                            <span class="badge2 badge-success" v-if="prospecto.diferenciaGer >= 0 && prospecto.diferenciaGer <= 7"><i class="fa fa-check"></i></span>
                                            <span class="badge2 badge-warning" v-if="prospecto.diferenciaGer > 7 && prospecto.diferenciaGer <= 15"><i class="fa fa-exclamation-triangle"></i></span>
                                            <span class="badge2 badge-danger" v-if="prospecto.diferenciaGer >15"><i class="fa fa-window-close-o"></i></span>
                                        </td>
                                        <td class="td2" >
                                            <a title="Llamar" class="btn btn-dark" :href="'tel:'+prospecto.celular"><i class="fa fa-phone fa-lg"></i></a>
                                            <a title="Enviar whatsapp" class="btn btn-success" target="_blank"
                                                :href="'https://api.whatsapp.com/send?phone=+'+prospecto.clv_lada+prospecto.celular+'&text=Hola'">
                                                <i class="fa fa-whatsapp fa-lg"></i>
                                            </a>
                                        </td>
                                        <td class="td2">
                                            <a  v-if="prospecto.email_institucional == null" title="Enviar correo" class="btn btn-secondary" :href="'mailto:'+prospecto.email"> <i class="fa fa-envelope-o fa-lg"></i> </a>
                                            <a v-else title="Enviar correo" class="btn btn-secondary" :href="'mailto:'+prospecto.email+ ';'+prospecto.email_institucional"> <i class="fa fa-envelope-o fa-lg"></i> </a>
                                        </td>
                                        <td class="td2" style="text-transform:uppercase" v-text="prospecto.rfc.toUpperCase()"></td>
                                        <td class="td2" v-text="prospecto.nss"></td>
                                        <td class="td2" style="text-transform:uppercase" v-text="prospecto.curp"></td>
                                        <td class="td2" v-text="prospecto.proyecto"></td>
                                        <td class="td2">
                                            {{  (prospecto.clasificacion == STATUS.NO_VIABLE) ? 'No viable'
                                                : (prospecto.clasificacion == STATUS.A) ? 'Tipo A'
                                                : (prospecto.clasificacion == STATUS.B) ? 'Tipo B'
                                                : (prospecto.clasificacion == STATUS.C)  ? 'Tipo C'
                                                : (prospecto.clasificacion == STATUS.VENTA)  ? 'Ventas'
                                                : (prospecto.clasificacion == STATUS.CANCELADO)  ? 'Cancelado'
                                                : (prospecto.clasificacion == STATUS.COACREDITADO)  ? 'Coacreditado' : ''
                                            }}
                                        </td>
                                        <td class="td2" v-text="this.moment(prospecto.created_at).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td class="td2"> <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right" @click="abrirModal3(prospecto.nombre, prospecto.apellidos),listarObservacion(1,prospecto.id)">Ver todos</button> </td>
                                        <td>
                                            <template v-if="prospecto.premio.length > 0">
                                                <a v-if="prospecto.premio[0].premio > 0" target="_blank" class="btn btn-scarlet" title="Descargar cupón" v-bind:href="'/premios/cuponPDF'+
                                                        '?gc='+ prospecto.premio[0].lead_id+'&d='+ prospecto.premio[0].name_user">
                                                    <i class="fa fa-gift"></i>&nbsp;
                                                </a>
                                            </template>
                                        </td>
                                        <td class="td2" v-if="rolId != 2" v-text="prospecto.v_completo"></td>
                                        <td class="td2" v-if="rolId != 2" v-text="prospecto.vAux_completo"></td>
                                        <td class="td2" v-if="rolId != 2" v-text="prospecto.publicidad"></td>
                                        <td v-if="rolId != 2">
                                            <button @click="setAdvertising(prospecto.id)"
                                                class="btn" :class="[prospecto.advertising == '0' ? 'btn-danger' : 'btn-success']">
                                                <i :class="[prospecto.advertising == '0' ? 'icon-close' : 'icon-check']"></i>
                                                {{( prospecto.advertising == '0') ? 'No' : 'Si'}}
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </TableComponent>
                            <Nav v-if="arrayProspectos"
                                :current="arrayProspectos.current_page"
                                :last="arrayProspectos.last_page"
                                @changePage="listarProspectos"
                            ></Nav>
                            <button class="btn btn-sm btn-default" @click="modal=4">Manual</button>
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
                                        <input type="text" class="form-control" v-model="apellidos" placeholder="Apellidos" @keyup="buscarCliente()" onchange="this.value = this.value.trim()" onkeyup="this.value = this.value.replace('  ', ' ')">
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

                                <div class="col-md-3">
                                    <div class="form-group">
                                    <label for="">Telefono </label>
                                    <input type="text" maxlength="10" pattern="\d*" class="form-control" v-on:keypress="$root.isNumber($event)" v-model="telefono" placeholder="Telefono">
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="form-group">
                                    <label for="">Celular <span style="color:red;" v-show="celular==''">(*)</span></label>
                                        <div class="input-group">
                                            <select  v-model="clv_lada"  class="form-control col-md-5" >
                                                <option value="">Clave lada</option>
                                                <option v-for="clave in arrayClaves" :key="clave.clave+clave.pais" :value="clave.clave" v-text="clave.pais+' +'+clave.clave"></option>
                                            </select>
                                            <input type="text" pattern="\d*" maxlength="10" class="form-control col-md-7" v-on:keypress="$root.isNumber($event)" v-model="celular" placeholder="Celular">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                     <div class="form-group">
                                    <label for="">Email personal <span style="color:red;" v-show="email==''">(*)</span></label>
                                    <input type="email" class="form-control" v-model="email" placeholder="E-mail">
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
                                    <input type="email" class="form-control" v-model="email_inst" placeholder="E-mail">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                     <div class="form-group">
                                    <label for="">Ingresos</label>
                                    <div class="input-group">
                                    <div class="input-group-addon">
                                        $
                                    </div>
                                    <input type="text" pattern="\d*" maxlength="20" class="form-control" v-on:keypress="$root.isNumber($event)" v-model="ingreso" placeholder="ingreso">
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
                                        <input type="text" v-on:keypress="isSpace($event)" maxlength="10" style="text-transform:uppercase" class="form-control" @keyup="selectRFC(rfc), findRFC(rfc)"  v-model="rfc" placeholder="RFC">
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
                                        <input type="text" maxlength="11" pattern="\d*" class="form-control" v-on:keypress="$root.isNumber($event)" v-model="nss" placeholder="NSS">
                                    </div>
                                </div>

                                <div class="col-md-2"></div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">No. INE</label>
                                        <input type="text" maxlength="13" pattern="\d*" class="form-control" v-on:keypress="$root.isNumber($event)" v-model="num_ine" placeholder="INE">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">No. Pasaporte</label>
                                        <input type="text" maxlength="11" class="form-control" v-model="num_pasaporte" placeholder="Pasaporte">
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
                                            <option :value="STATUS.NO_VIABLE">No viable</option>
                                            <option :value="STATUS.A">Tipo A</option>
                                            <option :value="STATUS.B">Tipo B</option>
                                            <option :value="STATUS.C">Tipo C</option>
                                            <option :value="STATUS.VENTA">Ventas</option>
                                            <option :value="STATUS.CANCELADO">Cancelado</option>
                                            <option v-if="rolId!=2" :value="STATUS.COACREDITADO">Coacreditado</option>
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
                                    <select class="form-control" :disabled="rfcLead.length > 0"
                                        v-model="publicidad_id" @change="nombre_recomendado=''" >
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
                                        <button @click="abrirModal('coacreditado')" style="margin-top:1.5rem;" class="btn btn-success form-control btnagregar" title="Agregar nuevo coacreditado"><i class="icon-plus"></i></button>
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
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <!--label for="">Crear recordatorio</label-->
                                        <button v-if="makeReminder == 1" @click="makeReminder = 0" class="btn-sm btn btn-primary">
                                            Crear recordatorio
                                            <i class="fa fa-toggle-on" style="font-size:24px"></i>
                                        </button>
                                        <button v-else @click="makeReminder = 1" class="btn-sm btn btn-primary">
                                            Crear recordatorio
                                            <i class="fa fa-toggle-off" style="font-size:24px"></i>
                                        </button>
                                        <br><br>
                                        <input v-if="makeReminder" v-model="makeRemember" type="date" class="form-control">
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
                                    <input :disabled="edit == 1" type="text" class="form-control" v-model="nombre" placeholder="Nombre" onchange="this.value = this.value.trim()" onkeyup="this.value = this.value.replace('  ', ' ')">
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="">Apellidos <span style="color:red;" v-show="apellidos==''">(*)</span></label>
                                    <input :disabled="edit == 1" type="text" class="form-control" v-model="apellidos" placeholder="Apellidos" onchange="this.value = this.value.trim()" onkeyup="this.value = this.value.replace('  ', ' ')">
                                </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="">Sexo <span style="color:red;" v-show="sexo==''">(*)</span></label>
                                    <select :disabled="edit == 1" class="form-control" v-model="sexo" >
                                            <option value="">Seleccione</option>
                                            <option value="F">Femenino</option>
                                            <option value="M">Masculino</option>
                                    </select>
                                </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                    <label for="">Telefono </label>
                                    <input :disabled="edit == 1" type="text" maxlength="10" pattern="\d*" class="form-control" v-on:keypress="$root.isNumber($event)" v-model="telefono" placeholder="Telefono">
                                </div>
                                </div>

                                <div class="col-md-5">
                                     <div class="form-group">
                                    <label for="">Celular <span style="color:red;" v-show="celular==''">(*)</span></label>
                                    <div class="input-group">
                                        <select :disabled="edit == 1" v-model="clv_lada"  class="form-control col-md-5" >
                                            <option value="">Clave lada</option>
                                            <option v-for="clave in arrayClaves" :key="clave.clave+clave.pais" :value="clave.clave" v-text="clave.pais+' +'+clave.clave"></option>
                                        </select>
                                        <input :disabled="edit == 1" type="text" pattern="\d*" maxlength="10" class="form-control col-md-7" v-on:keypress="$root.isNumber($event)" v-model="celular" placeholder="Celular">
                                    </div>
                                </div>
                                 </div>

                                 <div class="col-md-4">
                                     <div class="form-group">
                                    <label for="">Email personal <span style="color:red;" v-show="email==''">(*)</span></label>
                                    <input :disabled="edit == 1" type="text" class="form-control" v-model="email" placeholder="E-mail">
                                </div>
                                 </div>

                                   <div class="col-md-8">
                                    <div class="form-group">
                                    <label for="">Empresa <span style="color:red;" v-show="empresa==0">(*)</span></label>
                                        <v-select :disabled="edit == 1"
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
                                    <input :disabled="edit == 1" type="text" class="form-control" v-model="email_inst" placeholder="E-mail">
                                </div>
                                 </div>

                                 <div class="col-md-2">
                                     <div class="form-group">
                                    <label for="">Ingresos</label>
                                    <div class="input-group">
                                    <div class="input-group-addon">
                                        $
                                    </div>
                                    <input :disabled="edit == 1" type="text" pattern="\d*" maxlength="20" class="form-control" v-on:keypress="$root.isNumber($event)" v-model="ingreso" placeholder="ingreso">
                                    </div>
                                </div>
                                 </div>


                                  <div class="col-md-2">
                                    <div class="form-group">
                                    <label for="">Fecha de nacimiento <span style="color:red;" v-show="fecha_nac==''">(*)</span></label>
                                    <input :disabled="edit == 1" type="date" class="form-control"  v-model="fecha_nac" >
                                </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Lugar de nacimiento <span style="color:red;" v-show="lugar_nacimiento==''">(*)</span></label>
                                        <input :disabled="edit == 1" type="text" name="city" list="cityname" class="form-control" v-model="lugar_nacimiento">
                                        <datalist :disabled="edit == 1" id="cityname">
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
                                    <input :disabled="edit == 1" type="text" maxlength="18" style="text-transform:uppercase" class="form-control"  v-model="curp" placeholder="CURP">
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
                                         <input :disabled="edit == 1" type="text" maxlength="3" style="text-transform:uppercase" class="form-control"  v-model="homoclave" placeholder="AA0">
                                   </div>
                                </div>


                                <div class="col-md-3">
                                     <div class="form-group">
                                        <label for="">NSS <span style="color:red;" v-show="nss==''">(*)</span></label>
                                        <input :disabled="edit == 1" type="text" maxlength="11" pattern="\d*" class="form-control" v-on:keypress="$root.isNumber($event)" v-model="nss" placeholder="NSS">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">No. INE</label>
                                        <input type="text" maxlength="13" pattern="\d*" class="form-control" v-on:keypress="$root.isNumber($event)" v-model="num_ine" placeholder="INE">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">No. Pasaporte</label>
                                        <input type="text" maxlength="11" class="form-control" v-model="num_pasaporte" placeholder="Pasaporte">
                                    </div>
                                </div>
                            </div>
                  <!--  lugar de contacto , clasificacion y otros-->
                        <div class="form-group row border">
                              <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Lugar de contacto </label>
                                          <select :disabled="edit == 1" class="form-control" v-model="lugar_contacto" >
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
                                    <select :disabled="edit == 1" class="form-control" v-model="clasificacion" >
                                            <option :value="STATUS.NO_VIABLE">No viable</option>
                                            <option :value="STATUS.A">Tipo A</option>
                                            <option :value="STATUS.B">Tipo B</option>
                                            <option :value="STATUS.C">Tipo C</option>
                                            <option :value="STATUS.VENTA">Ventas</option>
                                            <option :value="STATUS.CANCELADO">Cancelado</option>
                                            <option :value="STATUS.COACREDITADO">Coacreditado</option>
                                    </select>
                                </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Proyecto en el que esta interesado <span style="color:red;" v-show="proyecto_interes_id==0">(*)</span></label>
                                        <v-select :disabled="edit == 1"
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
                                    <select :disabled="edit == 1" class="form-control" v-model="precio_rango" >
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
                                        <select :disabled="edit == 1" class="form-control" v-model="publicidad_id" >
                                                <option value="0">Seleccione</option>
                                                <option v-for="medios in arrayMediosPublicidad" :key="medios.id" :value="medios.id" v-text="medios.nombre"></option>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-4" v-if="publicidad_id == 1">
                                    <div class="form-group">
                                        <label for="">Nombre de la persona que te recomendo </label>
                                        <input :disabled="edit == 1" type="text" class="form-control" v-model="nombre_recomendado" placeholder="Nombre">
                                    </div>
                                </div>

                        </div>

                  <!--  apartado  de datos vive en casa , edo civil, conyuge-->
                        <div class="form-group row border" >

                                <div class="col-md-3">
                                    <div class="form-group">
                                    <label for="">Vive en casa <span style="color:red;" v-show="tipo_casa==0">(*)</span></label>
                                        <select :disabled="edit == 1" class="form-control" v-model="tipo_casa" >
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
                                    <select :disabled="edit == 1" class="form-control" v-model="e_civil" >
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
                                   <input :disabled="edit == 1" id="checkcoa" type="checkbox" v-model="coacreditado">
                                </div>
                                </div>

                                <div class="col-md-1"  v-if="coacreditado==true">
                                    <div class="form-group">
                                        <button :disabled="edit == 1" @click="abrirModal('coacreditado')" style="margin-top:1.5rem;" class="btn btn-success form-control btnagregar" title="Agregar nuevo coacreditado"><i class="icon-plus"></i></button>
                                    </div>
                                </div>

                                <div class="col-md-3" v-if="coacreditado==true">
                                    <div class="form-group">
                                        <label for="">Buscar coacreditado... </label>
                                        <v-select :disabled="edit == 1"
                                            :on-search="selectCoacreditadoVueselect"
                                            label="n_completo"
                                            :options="arrayCoacreditados"
                                            placeholder="Buscar..."
                                            :onChange="getDatosCoacreditado"
                                        >
                                        </v-select>
                                        <input :disabled="edit == 1" type="text" v-if="conyugeNom != null" class="form-control" readonly v-model="conyugeNom">
                                    </div>
                                </div>

                                <div class="col-md-3" v-if="coacreditado==true">
                                     <div class="form-group">
                                    <label for="">Parentesco </label>
                                    <input :disabled="edit == 1" type="text" class="form-control" v-model="parentesco_coa" placeholder="Parentesco">
                                </div>
                                </div>

                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label for="">Observaciones <span style="color:red;" v-show="observacion==''">(*)</span></label>
                                        <textarea :disabled="edit == 1" rows="3" cols="30" v-model="observacion" class="form-control" placeholder="Observaciones"></textarea>
                                    </div>
                                    <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right" @click="abrirModal3(nombre, apellidos),listarObservacion(1,id)">Ver todos</button>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <button :disabled="edit == 1" v-if="makeReminder == 1" @click="makeReminder = 0" class="btn-sm btn btn-primary">
                                            Crear recordatorio
                                            <i class="fa fa-toggle-on" style="font-size:24px"></i>
                                        </button>
                                        <button :disabled="edit == 1" v-else @click="makeReminder = 1" class="btn-sm btn btn-primary">
                                            Crear recordatorio
                                            <i class="fa fa-toggle-off" style="font-size:24px"></i>
                                        </button>
                                        <br><br>
                                        <input v-if="makeReminder" v-model="makeRemember" type="date" class="form-control">
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
                                    <button :disabled="edit == 1" type="button" class="btn btn-primary" @click="actualizarProspecto()"> Actualizar </button>
                                </div>
                            </div>



                            </div>



                        </div>
                    </template>

                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>

            <!--Inicio del modal agregar/actualizar coacreditado-->
            <ModalComponent :titulo="tituloModal"
                v-if="modal == 1"
                @closeModal="cerrarModal()"
            >
                <template v-slot:body>
                    <RowModal label1="Nombre" clsRow1="col-md-4" clsRow2="col-md-3" label2="Apellidos"
                        :span1="nombre_coa==''" :span2="apellidos_coa==''"
                    >
                        <input type="text" class="form-control" v-model.trim="nombre_coa"
                            placeholder="Nombre"  onkeyup="this.value = this.value.replace('  ', ' ')">
                        <template v-slot:input2>
                            <input type="text" class="form-control" v-model.trim="apellidos_coa" placeholder="Apellidos"
                                onkeyup="this.value = this.value.replace('  ', ' ')">
                        </template>
                    </RowModal>

                    <RowModal label1="Celular" clsRow1="col-md-3" clsRow2="col-md-4" :span1="celular_coa==''">
                        <select  v-model="clv_lada_coa" class="form-control" >
                            <option value="">Clave lada</option>
                            <option v-for="clave in arrayClaves" :key="clave.clave+clave.pais" :value="clave.clave" v-text="clave.pais+' +'+clave.clave"></option>
                        </select>
                        <template v-slot:input2>
                            <input type="text" pattern="\d*" maxlength="10" class="form-control" v-on:keypress="$root.isNumber($event)" v-model="celular_coa" placeholder="Celular">
                        </template>
                    </RowModal>

                    <RowModal label1="Telefono" clsRow1="col-md-3" :span1="telefono_coa==''">
                        <input type="text" pattern="\d*" maxlength="10" class="form-control" v-on:keypress="$root.isNumber($event)" v-model="telefono_coa" placeholder="Telefono">
                    </RowModal>

                    <RowModal label1="Email personal" clsRow1="col-md-5" :span1="email_coa == ''">
                        <input type="email" class="form-control"  v-model.trim="email_coa" placeholder="email">
                    </RowModal>

                    <RowModal label1="Email Institucional" clsRow1="col-md-5">
                        <input type="email" class="form-control"  v-model.trim="email_institucional_coa" placeholder="email">
                    </RowModal>

                    <RowModal label1="Sexo" clsRow1="col-md-3" :span1="sexo_coa==''">
                        <select class="form-control" v-model="sexo_coa" >
                            <option value="">Seleccione</option>
                            <option value="F">Femenino</option>
                            <option value="M">Masculino</option>
                        </select>
                    </RowModal>

                    <RowModal label1="Fecha de nacimiento" clsRow1="col-md-3" :span1="fecha_nac_coa==''">
                        <input type="date" class="form-control"  v-model="fecha_nac_coa" >
                    </RowModal>

                    <RowModal label1="Lugar de nacimiento" :span1="lugar_nacimiento_coa=''" clsRow1="col-md-6">
                        <input type="text" name="city2" list="cityname2" class="form-control" v-model="lugar_nacimiento_coa">
                            <datalist id="cityname2">
                                <option value="">Seleccione</option>
                                <option v-for="estados in arrayEstados" :key="estados.estado" :value="estados.estado" v-text="estados.estado"></option>
                            </datalist>
                    </RowModal>

                    <RowModal label1="CURP" clsRow1="col-md-4" label2="NSS" clsRow2="col-md-3">
                        <input type="text" maxlength="18" style="text-transform:uppercase" class="form-control"  v-model="curp_coa" placeholder="CURP">
                        <template v-slot:input2>
                            <input type="text" maxlength="11" pattern="\d*" class="form-control" v-on:keypress="$root.isNumber($event)" v-model="nss_coa" placeholder="NSS">
                        </template>
                    </RowModal>

                    <RowModal label1="INE" clsRow1="col-md-4" label2="Pasaporte" clsRow2="col-md-3">
                        <input type="text" maxlength="13" style="text-transform:uppercase" class="form-control"  v-model="num_ine_coa" placeholder="No. INE">
                        <template v-slot:input2>
                            <input type="text" maxlength="11" class="form-control" v-model="num_pasaporte_coa" placeholder="No. Passaporte">
                        </template>
                    </RowModal>

                    <RowModal label1="RFC" clsRow1="col-md-3" clsRow2="col-md-3">
                        <span style="color:red;" v-show="encuentraRFC==1"> Ya se encuentra este rfc registrado</span>
                        <input type="text" maxlength="10" style="text-transform:uppercase"  @keyup="selectRFC(rfc_coa)" v-model="rfc_coa" class="form-control" placeholder="RFC" :disabled="tipoAccion == 3">
                        <template v-slot:input2>
                            <input type="text" maxlength="3" style="text-transform:uppercase" v-model="homoclave_coa" class="form-control" placeholder="Homoclave" :disabled="tipoAccion == 3">
                        </template>
                    </RowModal>

                    <RowModal label1="Vive en casa" clsRow1="col-md-3" :span1="tipo_casa_coa == 0">
                        <select class="form-control" v-model="tipo_casa_coa" >
                            <option value="0">Seleccione</option>
                            <option value="De familiares">De familiares</option>
                            <option value="Prestada">Prestada</option>
                            <option value="Propia">Propia</option>
                            <option value="Rentada">Rentada</option>
                        </select>
                    </RowModal>

                    <RowModal label1="Estado civil" clsRow1="col-md-3" :span1="e_civil_coa == 0">
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
                    </RowModal>

                    <!-- Div para mostrar los errores que mande validerFraccionamiento -->
                    <div v-show="errorCoacreditado" class="form-group row div-error">
                        <div class="text-center text-error">
                            <div v-for="error in errorMostrarMsjCoacreditado" :key="error" v-text="error">
                            </div>
                        </div>
                    </div>
                </template>
                <template v-slot:buttons-footer>
                    <Button @click="registrarCoacreditado()" icon="icon-check">Guardar</Button>
                </template>

            </ModalComponent>
            <!--Fin del modal-->

            <!--Inicio del modal asignar prospecto-->
            <ModalComponent v-if="modal == 2"
                @closeModal="cerrarModal()"
                :titulo="tituloModal"
            >
                <template v-slot:body>
                    <RowModal label1="Asesor" clsRow1="col-md-6">
                        <select class="form-control" v-model="asesor_id" >
                            <option value="0">Seleccione</option>
                            <option v-for="asesor in arrayAsesores" :key="asesor.id" :value="asesor.id" v-text="asesor.nombre + ' '+ asesor.apellidos"></option>
                        </select>
                    </RowModal>

                    <RowModal label1="Clasificación" clsRow1="col-md-6">
                        <select class="form-control" v-model="clasificacion" >
                            <option :value="STATUS.NO_VIABLE">No viable</option>
                            <option :value="STATUS.A">Tipo A</option>
                            <option :value="STATUS.B">Tipo B</option>
                            <option :value="STATUS.C">Tipo C</option>
                            <option :value="STATUS.VENTA">Ventas</option>
                            <option :value="STATUS.CANCELADO">Cancelado</option>
                        </select>
                    </RowModal>

                    <RowModal label1="Observación" :span1="observacion==''" clsRow1="col-md-9">
                        <textarea rows="1" cols="30" class="form-control" v-model="observacion" placeholder="Observaciones"></textarea>
                    </RowModal>
                </template>
                <template v-slot:buttons-footer>
                    <Button icon="icon-check" @click="asignarProspecto()">Reasignar</Button>
                </template>
            </ModalComponent>
            <!--Fin del modal-->

            <!--Inicio del modal observaciones-->
            <ModalComponent v-if="modal==3"
                @closeModal="cerrarModal()"
                :titulo="tituloModal"
            >
                <template v-slot:body>
                    <RowModal label1="Observación" clsRow1="col-md-6" clsRow2="col-md-2">
                        <textarea rows="3" cols="30" v-model="observacion" class="form-control" placeholder="Observacion"></textarea>
                        <template v-slot:input2>
                            <Button  v-if="rolId != 2" @click="agregarComentario()" icon="icon-check">Guardar</Button>
                            <Button  v-if="rolId == 2" @click="storeObs()" icon="icon-check">Guardar</Button>
                        </template>
                    </RowModal>

                    <TableComponent v-if="tipoAccion==4"
                        :cabecera="['Usuario','Observacion','Fecha','Cita']"
                    >
                        <template v-slot:tbody>
                            <tr v-for="observacion in arrayObservacion" :key="observacion.id">

                                <td v-text="observacion.usuario" ></td>
                                <td v-text="observacion.comentario" ></td>
                                <td v-text="observacion.created_at"></td>
                                <td v-text="observacion.prox_cita"></td>
                            </tr>
                        </template>
                    </TableComponent>
                </template>
            </ModalComponent>

            <!-- Manual -->
            <ModalComponent v-if="modal==4"
                :titulo="'Manual'"
                @closeModal="cerrarModal"
            >
                <template v-slot:body>
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
                </template>
            </ModalComponent>
        </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    import ModalComponent from '../Componentes/ModalComponent.vue';
    import RowModal from '../Componentes/ComponentesModal/RowModalComponent.vue'
    import TableComponent from '../Componentes/TableComponent.vue';
    import vSelect from 'vue-select';
    import Button from '../Componentes/ButtonComponent.vue';
    import createCurpRFC from '../../helpers/createCurpRFC';
    import Nav from "../Componentes/NavComponent.vue";
    export default {
        props:{
            rolId:{type: String},
            userId:{type: String}
        },
        data(){
            return{
                STATUS : Object.freeze({
                    NO_VIABLE: 1,
                    A: 2,
                    B: 3,
                    C: 4,
                    VENTA: 5,
                    CANCELADO: 6,
                    COACREDITADO: 7
                }),

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
                num_ine:'',
                num_pasaporte:'',
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

                direccion:'',
                colonia:'',
                cp:'',
                estado:'',
                ciudad:'',

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
                num_ine_coa:'',
                num_pasaporte_coa:'',
                e_civil_coa: 0,
                lugar_nacimiento_coa:'',
                tipo_casa_coa:0,
                precio_rango: 0,
                ingreso:0,

                arrayEmpresa: [],
                arrayMediosPublicidad:[],
                arrayEstados:[],
                encuentraRFC:0,
                rfcLead:'',
                asesor_id:0,

                modal : 0,
                listado:1,
                tituloModal : '',
                tipoAccion: 0,
                errorProspecto : 0,
                errorMostrarMsjProspecto : [],
                errorCoacreditado : 0,
                errorMostrarMsjCoacreditado : [],
                criterio : 'personal.nombre',
                buscar : '',
                buscar2: '',
                buscar3:'2',
                b_clasificacion: '2',
                b_aux:'',
                b_seguimiento:'',
                arrayCoacreditados : [],
                arrayProspectos: [],
                arrayFraccionamientos : [],
                arrayLugarContacto: [],
                arrayFraccionamientos2 : [],
                arrayFraccionamientosVue : [],
                arrayObservacion: [],
                arrayClaves:[],
                arrayAsesores : [],
                fraccionamiento:'',
                makeRemember:'',
                makeReminder:0,
                usuario : '',
                edit:0,
                reasignar:0,
                clv_lada:52,
                clv_lada_coa:52,
                b_advertising:''
            }
        },
        components:{
            vSelect,
            ModalComponent,
            TableComponent,
            RowModal,
            Button,
            Nav
        },
        computed:{
        },

        methods : {
            /**Metodo para mostrar los registros */
            listarProspectos(page){
                let me = this;
                var url = '/clientes?page=' + page + '&buscar=' + me.buscar + '&buscar2=' + me.buscar2 + '&b_advertising=' + me.b_advertising +
                    '&buscar3=' + me.buscar3 + '&b_clasificacion=' + me.b_clasificacion + '&seguimiento='+me.b_seguimiento +
                    '&b_publicidad=' + me.b_publicidad + '&criterio=' + me.criterio + '&b_aux=' + me.b_aux;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayProspectos = respuesta.personas;
                    me.contador = respuesta.contadorClientes;
                    me.usuario = respuesta.user;
                })
                .catch(function (error) {
                    //console.log(error);
                });
            },
            buscarCliente(){
                let me = this;
                var url = '/clientes/buscar?nombre=' + me.nombre + '&apellidos=' + me.apellidos;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;

                    if(respuesta.length > 0) {
                        Swal({
                            title: 'Cliente localizado',
                            text: 'Se encontro un cliente con el mismo nombre en la BD de ' + respuesta[0].nombre + ' ' + respuesta[0].apellidos ,
                            animation: false,
                            customClass: 'animated tada'
                        })
                    }
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
            },
            CreateCurp(){
                const { curp, rfc } = createCurpRFC(
                    this.apellidos,
                    this.apellidos2,
                    this.nombre,
                    this.fecha_nac,
                    this.sexo,
                    this.lugar_nacimiento
                )

                this.curp = curp;
                this.rfc = rfc;

                this.selectRFC(this.rfc);
            },
            limpiarBusqueda(){
                let me=this;
                me.buscar= "";
                me.b_seguimiento='';
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
            getClavesLadas(){
                let me = this;
                me.arrayClaves=[];
                var url = '/getClavesLadas';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayClaves = respuesta.claves;
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
                    me.id = buscar;
                    me.observacion = '';
                    console.log(url);
                })
                .catch(function (error) {
                    console.log(error);
                });

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
                    'clv_lada':this.clv_lada,
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
                    'num_ine':this.num_ine,
                    'num_pasaporte':this.num_pasaporte,

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
                    'makeRemember':this.makeRemember,
                }).then(function (response){
                    me.proceso=false;
                    me.listado=1;
                    me.makeRemember = "";
                    me.limpiarDatos();
                    me.listarProspectos(1);
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
                    this.proceso=false;
                });
            },

            agregarComentario(){
                let me = this;
                //Con axios se llama el metodo store del controller
                axios.post('/clientes/storeObsGerente',{
                    'id':this.id,
                    'observacion':this.observacion,
                }).then(function (response){
                    me.listarProspectos(me.arrayProspectos.current_page,me.buscar,me.buscar2,me.buscar3);
                    me.cerrarModal();
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Comentario guardado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },

            storeObs(){
                let me = this;
                //Con axios se llama el metodo store del controller
                axios.post('/clientes/storeObs',{
                    'id':this.id,
                    'observacion':this.observacion,
                }).then(function (response){
                    me.listarProspectos(me.arrayProspectos.current_page,me.buscar,me.buscar2,me.buscar3);
                    me.cerrarModal();
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Comentario guardado correctamente',
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
                    'clv_lada':this.clv_lada_coa,
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
                    'num_ine':this.num_ine_coa,
                    'num_pasaporte':this.num_pasaporte_coa,
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

            setAdvertising(id){
                let me = this;
                axios.put('clientes/setAdvertising',{
                    'id' : id
                }).then(function (response){
                    //Se muestra mensaje Success
                    const index = me.arrayProspectos.data.map( e => e.id ).indexOf( id )
                    if(me.arrayProspectos.data[index].advertising == 1)
                        me.arrayProspectos.data[index].advertising = 0;
                    else
                        me.arrayProspectos.data[index].advertising = 1;
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
                    'clv_lada':this.clv_lada,
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
                    'num_ine':this.num_ine,
                    'num_pasaporte':this.num_pasaporte,

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
                    'makeRemember':this.makeRemember,
                }).then(function (response){
                    me.proceso=false;
                    me.listado=1;
                    me.makeRemember = "";
                    me.limpiarDatos();
                    me.listarProspectos(me.arrayProspectos.current_page,me.buscar,me.buscar2,me.buscar3);

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
                        me.listarProspectos(1);
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
                    me.listarProspectos(1);
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

            reasignToAsesor(){
                swal({
                title: 'Esta seguro de solicitar la reasignacion del prospecto?',
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

                    axios.put('/clientes/reasignarProspecto',{
                        'id': me.id
                    }).then(function (response) {
                    me.listado=1;
                    me.limpiarDatos();
                    me.listarProspectos(1);
                        swal(
                        'Hecho!',
                        'La solicitud ha llegado al gerente del fraccionamiento.',
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
                this.num_ine = '';
                this.num_pasaporte = '';

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
            findRFC(rfc){
                let me = this;
                if(rfc.length == 10 && me.encuentraRFC == 0){

                    var url = '/leads/findRFC?rfc=' + rfc;
                    me.rfcLead=[];
                    axios.get(url).then(function (response) {
                        me.rfcLead = response.data;

                        if(me.rfcLead.length > 0) {
                            let aviso = 'Este RFC esta registrado como Lead'
                            if(me.rfcLead[0].nombre)
                                aviso = 'Este RFC esta registrado como Lead y agregado por: ' + me.rfcLead[0].nombre + ' ' + me.rfcLead[0].apellidos
                            Swal({
                            title: aviso,
                            animation: false,
                            customClass: 'animated tada'
                            })
                            me.publicidad_id = 5;
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }

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
                        me.listarProspectos(1);
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

            actualizarProspectoBTN(data){

                let me= this;
                this.listado=3;
                    me.edit = 0;
                    me.nombre= data['nombre'];
                    me.apellidos= data['apellidos'];
                    me.sexo= data['sexo'];
                    me.telefono= data['telefono'];
                    me.celular= data['celular'];
                    me.email_inst= data['email_institucional'];
                    me.email = data['email'];
                    me.empresa=data['empresa'];
                    me.precio_rango=data['precio_rango'];
                    me.ingreso=data['ingreso'];
                    me.fecha_nac=data['f_nacimiento'];
                    me.lugar_nacimiento =data['lugar_nacimiento'];
                    me.curp=data['curp'];
                    me.rfc=data['rfc'];
                    me.homoclave=data['homoclave'];
                    me.clv_lada = data['clv_lada'];
                    me.nss=data['nss'];
                    me.lugar_contacto=data['lugar_contacto'];
                    me.clasificacion=data['clasificacion'];
                    me.proyecto_interes_id=data['proyecto_interes_id'];
                    me.publicidad_id=data['publicidad_id'];
                    me.tipo_casa=data['tipo_casa'];
                    me.e_civil=data['edo_civil'];
                    me.parentesco_coa=data['parentesco_coa'];
                    me.coacreditado=data['coacreditado'];
                    me.conyugeNom = data['n_completo_coa'];
                    me.nombre_coa = data['nombre_coa'];
                    me.nombre_recomendado = data['nombre_recomendado'];
                    me.apellidos_coa = data['apellidos_coa'];
                    me.lugar_nacimiento_coa =data['lugar_nacimiento_coa'];
                    me.proyecto = data['proyecto'];
                    me.reasignar = data['reasignar'];
                    me.num_ine = data['num_ine'];
                    me.num_pasaporte = data['num_pasaporte'];

                    if(data['vendedor_aux'] == me.usuario)
                        me.edit = 1;

                    me.id=data.id;
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

            abrirModalCambio(data=[]){
                this.selectAsesores();
                this.tituloModal = data['nombre'] + ' ' + data['apellidos'];
                this.modal=2;
                this.id = data['id'];
                this.asesor_id = data['vendedor_id'];
                this.clasificacion = data['clasificacion'];
            },

            abrirModal3(nombre, apellidos){
                this.modal =3;
                this.tituloModal='Observaciones de: '+nombre+' '+apellidos;
                this.tipoAccion= 4;
            },

            abrirModal(accion){
                switch(accion){
                    case 'coacreditado':
                    {
                        this.modal = 1;
                        this.tituloModal = 'Registrar Conyuge o coacreditado';
                        this.nombre_coa='';
                        this.parentesco_coa='';
                        this.apellidos_coa='';
                        this.telefono_coa = '';
                        this.celular_coa = '';
                        this.num_ine_coa = '';
                        this.num_pasaporte_coa = '';
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


        },
        mounted() {
            this.listarProspectos(1);
            this.selectMedioPublicidad();
            this.selectFraccionamientos();
            this.selectFraccionamientos2();
            this.selectLugarContacto();
            this.selectEstados();
            this.selectAsesores();
            this.getClavesLadas();
        }
    }
</script>
<style>
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
