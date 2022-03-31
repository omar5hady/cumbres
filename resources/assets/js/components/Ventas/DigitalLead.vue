<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
        </ol>

        <div class="container-fluid">
            <div class="card scroll-box">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Digital Leads
                    &nbsp;
                    <button v-if="rolId != 2 && rolId != 12 && rolId != 3" type="button" @click="abrirModal('nuevo')" class="btn btn-success">
                        <i class="icon-people"></i>&nbsp;Nuevo
                    </button>

                    <button v-if="rolId == 1" type="button" class="btn btn-dark" @click="sms()">
                        PRUEBA SMS
                    </button>
                    &nbsp;
                </div>
                
                <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-5">
                                <div class="input-group">

                                    <select @change="changeMotivo()" v-if="rolId != 2 && rolId != 12 && rolId != 3" class="form-control col-sm-5" v-model="b_motivo">
                                        <option value="1">Ventas</option>
                                        <option value="5">Recomendados</option>
                                        <option value="2">Postventa</option>
                                        <option value="3">Rentas</option>
                                        <option value="4">Dirección</option>
                                        <option value="6">Terrenos</option>
                                        <option value="7">Cumbres León</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" v-model="b_cliente" @keyup.enter="listarLeads(1)" placeholder="Nombre" class="form-control col-sm-6">
                                    <input type="text" v-if="b_motivo == 3" v-model="b_modelo" @keyup.enter="listarLeads(1)" placeholder="Modelo" class="form-control col-sm-6">
                                    <select v-if="b_motivo != 1" class="form-control col-sm-4" v-model="b_status">
                                        <option value="">Todos</option>
                                        <option value="1">Pendientes</option>
                                        <option value="3">Finalizados</option>
                                    </select>

                                    <select v-if="b_motivo == 1" class="form-control col-sm-5" v-model="b_campania">
                                        <option value="">Campaña publicitaria</option>
                                        <option v-for="medios in arrayCampanias" :key="medios.id" :value="medios.id" v-text="medios.nombre_campania + ' - ' + medios.medio_digital"></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-8" v-if="b_motivo ==1">
                                <div class="input-group">
                                    <input type="text" readonly placeholder="Fecha de alta:" class="form-control col-sm-4">
                                    <input type="date" v-model="b_fecha1" @keyup.enter="listarLeads(1)" class="form-control col-sm-6">
                                    <input type="date" v-model="b_fecha2" @keyup.enter="listarLeads(1)" class="form-control col-sm-6">
                                </div>
                            </div>
                            <div class="col-md-6" v-if="b_motivo ==4">
                                <div class="input-group">
                                    <input type="text" readonly placeholder="Prioridad:" class="form-control col-sm-4">
                                    <select class="form-control"  v-model="b_prioridad" >
                                        <option value="">Todos</option>
                                        <option value="Baja">Baja</option>
                                        <option value="Media">Media</option>
                                        <option value="Alta">Alta</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-8" v-if="b_motivo ==1">
                                <div class="input-group">
                                    <input type="text" readonly placeholder="Proyecto de interes:" class="form-control col-sm-4">
                                    <select class="form-control" v-model="b_proyecto">
                                        <option value="">Seleccione</option>
                                        <option v-for="proyecto in arrayFraccionamientos" :key="proyecto.id" 
                                            :value="proyecto.id" v-text="proyecto.nombre">
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="input-group" v-if="b_motivo == 1">
                                    <select class="form-control"  v-model="b_asesor" >
                                        <option value="">Vendedor asignado</option>
                                        <option v-for="asesor in arrayAsesores" :key="asesor.id" :value="asesor.id" v-text="asesor.nombre + ' '+ asesor.apellidos"></option>
                                    </select>
                                    <!--Criterios para el listado de busqueda -->
                                    <select v-if="b_motivo == 1" class="form-control col-sm-4" v-model="b_status">
                                        <option value="">Status</option>
                                        <option value="1">En Seguimiento</option>
                                        <option value="0">Descartado</option>
                                        <option value="2">Potencial</option>
                                        <option value="3">Enviado a prospectos</option>
                                    </select>
                                </div>
                                
                                <div class="input-group">
                                    <button @click="listarLeads(1)" class="btn btn-primary">
                                        <i class="fa fa-search"></i> Buscar
                                    </button>
                                    <a v-if="b_motivo == 1" class="btn btn-success" v-bind:href="'/campanias/excelLeads'+
                                            '?buscar='+ b_cliente+'&campania='+ b_campania+
                                            '&status='+ b_status+'&asesor='+ b_asesor+
                                            '&motivo='+ b_motivo+'&fecha1='+ b_fecha1+
                                            '&fecha2='+ b_fecha2+'&proyecto='+ b_proyecto">
                                        <i class="fa fa-file-text"></i>&nbsp; Excel
                                    </a>
                                    <button disabled class="btn btn-primary">
                                        {{'Total: '+arrayLeads.total}}
                                    </button>
                                    
                                </div>
                            </div>
                        </div>
                        <br>

                        <div class="table-responsive">
                            <table v-if="b_motivo == 1" class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Avance</th>
                                        <th>Nombre</th>
                                        <th>Celular</th>
                                        <th>Correo</th>
                                        <th>Campaña</th>
                                        <th>Proyecto o zona de interés </th>
                                        <th>Presupuesto</th>
                                        <th>Modelo recomendado</th>
                                        <th>Estatus</th>
                                        <th>Vendedor asignado</th>
                                        <th>Fecha de alta</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="lead in arrayLeads.data" :key="lead.id">
                                        <td class="td2" style="width:10%">
                                            <button title="Editar" type="button" @click="abrirModal('actualizar',lead)" class="btn btn-warning btn-sm">
                                                <i class="icon-pencil"></i>
                                            </button>   
                                            <button type="button" v-if="lead.vendedor_asign == null" @click="asignarVendedor(lead.id)" class="btn btn-primary btn-sm">
                                                <i class="fa fa-exchange"></i>
                                            </button>    
                                            <button v-if="userId == 25511 || rolId == 1 || userId == 28270" title="Eliminar" type="button" @click="eliminar(lead.id)" class="btn btn-danger btn-sm">
                                                <i class="icon-close"></i>
                                            </button>                           
                                        </td>
                                        <td>
                                            <div class="clearfix">
                                                <div class="float-left"><strong>{{lead.progress}}%</strong></div>
                                            </div>
                                            <div class="progress progress-xs">
                                                <div class="progress-bar bg-success" role="progressbar" v-bind:style="{ width: lead.progress + '%' }" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                        <td v-if="lead.diferencia < 7 || lead.status == 0 || lead.status == 3" class="td2" v-text="lead.nombre + ' ' + lead.apellidos"></td>                                                    
                                        <td v-else-if="lead.diferencia >= 7 && lead.diferencia <= 15  " class="td2">
                                            <span class="badge2 badge-warning">{{ lead.nombre.toUpperCase()+' '+lead.apellidos}}</span>
                                        </td>    
                                        <td v-else-if="lead.diferencia > 15" class="td2">
                                            <span class="badge2 badge-danger">{{ lead.nombre.toUpperCase()+' '+lead.apellidos}}</span>
                                        </td>
                                        <td class="td2" v-if="lead.celular != null">
                                            <a title="Enviar whatsapp" class="btn btn-success" target="_blank" :href="'https://api.whatsapp.com/send?phone=+'+lead.clv_lada+lead.celular+'&text='"><i class="fa fa-whatsapp fa-lg"></i></a>    
                                        </td><td class="td2" v-else ></td>
                                        <td class="td2" v-if="lead.email != null" >
                                            <a title="Enviar correo" class="btn btn-secondary" :href="'mailto:'+lead.email+ ';'"> <i class="fa fa-envelope-o fa-lg"></i> </a>
                                        </td><td class="td2" v-else ></td>
                                        <td class="td2" v-if="lead.nombre_campania != null" v-text="lead.nombre_campania + '-'+lead.medio_digital"></td>
                                        <td class="td2" v-else v-text="'Tráfico organico'"></td>
                                        <td class="td2" v-if="lead.proyecto_interes != 0" v-text="lead.proyecto"></td>
                                        <td class="td2" v-else v-text="lead.zona_interes"></td>
                                        <td class="td2" v-if="lead.rango1 != null" v-text="'$'+formatNumber(lead.rango1) + ' - $'+formatNumber(lead.rango2)"></td><td class="td2" v-else ></td>
                                        <td class="td2" v-text="lead.modelo_interes"></td>
                                        <td class="td2" v-if="lead.status == '1'"><span class="badge badge-warning">En Seguimiento</span></td>
                                        <td class="td2" v-if="lead.status == '0'"><span class="badge badge-danger">Descartado</span></td>
                                        <td class="td2" v-if="lead.status == '2'"><span class="badge badge-success">Potencial</span></td>
                                        <td class="td2" v-if="lead.status == '3'"><span class="badge badge-success">Enviado a prospectos</span></td>
                                        <td class="td2" v-text="lead.vendedor"></td>
                                        <td class="td2" v-text="this.moment(lead.created_at).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td class="td2"> 
                                            <button title="Ver observaciones" type="button" class="btn btn-info pull-right" 
                                            @click="abrirModal1(lead.id,lead.motivo),listarObservacion(1,lead.id)">Ver todos</button> </td>
                                        
                                       
                                    </tr>
                                </tbody>
                            </table>
                            <table v-else-if="b_motivo == 3" class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Nombre</th>
                                        <th>Celular</th>
                                        <th>Correo</th>
                                        <th>Proyecto o zona de interés </th>
                                        <th>Modelo de interes</th>
                                        <th>Mensualidad deseada</th>
                                        <th>Status</th>
                                        <th>Fecha de alta</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="lead in arrayLeads.data" :key="lead.id">
                                        <td class="td2" style="width:10%">
                                            <button title="Editar" type="button" @click="abrirModal('actualizar',lead)" class="btn btn-warning btn-sm">
                                                <i class="icon-pencil"></i>
                                            </button>    
                                            <button v-if="lead.status == 1" title="Finalizar" type="button" @click="changeStatus(lead.id)" class="btn btn-success btn-sm">
                                                <i class="icon-check"></i>
                                            </button>   
                                            <button v-if="userId == 25511 || rolId == 1 || userId == 28270" title="Eliminar" type="button" @click="eliminar(lead.id)" class="btn btn-danger btn-sm">
                                                <i class="icon-close"></i>
                                            </button>                            
                                        </td>
                                        <td v-if="lead.diferencia < 7" class="td2" v-text="lead.nombre + ' ' + lead.apellidos "></td>                                                    
                                        <td v-else-if="lead.diferencia >= 7 && lead.diferencia <= 15  " class="td2">
                                            <span class="badge2 badge-warning">{{ lead.nombre.toUpperCase()+' '+lead.apellidos.toUpperCase()}}</span>
                                        </td>    
                                        <td v-else-if="lead.diferencia > 15" class="td2">
                                            <span class="badge2 badge-danger">{{ lead.nombre.toUpperCase()+' '+lead.apellidos.toUpperCase()}}</span>
                                        </td>
                                        <td class="td2" v-if="lead.celular != null">
                                            <a title="Enviar whatsapp" class="btn btn-success" target="_blank" :href="'https://api.whatsapp.com/send?phone=+'+lead.clv_lada+lead.celular+'&text='"><i class="fa fa-whatsapp fa-lg"></i></a>    
                                        </td><td class="td2" v-else ></td>
                                        <td class="td2" v-if="lead.email != null" >
                                            <a title="Enviar correo" class="btn btn-secondary" :href="'mailto:'+lead.email+ ';'"> <i class="fa fa-envelope-o fa-lg"></i> </a>
                                        </td><td class="td2" v-else ></td>
                                        <td class="td2" v-if="lead.proyecto_interes != 0" v-text="lead.proyecto"></td>
                                        <td class="td2" v-else v-text="lead.zona_interes"></td>
                                        <td class="td2" v-text="lead.modelo_interes"></td>
                                        <td class="td2" v-if="lead.rango1 != null" v-text="'$'+formatNumber(lead.rango1) + ' - $'+formatNumber(lead.rango2)"></td><td class="td2" v-else ></td>
                                        <td class="td2" v-if="lead.status == '1'"><span class="badge badge-warning">En Seguimiento</span></td>
                                        <td class="td2" v-if="lead.status == '0'"><span class="badge badge-danger">Descartado</span></td>
                                        <td class="td2" v-if="lead.status == '3'"><span class="badge badge-success">Finalizado</span></td>
                                        <td class="td2" v-text="this.moment(lead.created_at).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td class="td2"> 
                                            <button title="Ver observaciones" type="button" class="btn btn-info pull-right" 
                                            @click="abrirModal1(lead.id,lead.motivo),listarObservacion(1,lead.id)">Ver todos</button> </td>
                                        
                                       
                                    </tr>
                                </tbody>
                            </table>
                            <table v-else-if="b_motivo == 2" class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Nombre</th>
                                        <th>Celular</th>
                                        <th>Correo</th>
                                        <th>Dirección</th>
                                        <th>Descripción del problema</th>
                                        <th>Status</th>
                                        <th>Fecha de alta</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="lead in arrayLeads.data" :key="lead.id">
                                        <td class="td2" style="width:10%">
                                            <button title="Editar" type="button" @click="abrirModal('actualizar',lead)" class="btn btn-warning btn-sm">
                                                <i class="icon-pencil"></i>
                                            </button>  
                                            <button v-if="lead.status == 1" title="Finalizar" type="button" @click="changeStatus(lead.id)" class="btn btn-success btn-sm">
                                                <i class="icon-check"></i>
                                            </button>      
                                            <button v-if="userId == 25511 || rolId == 1 || userId == 28270" title="Eliminar" type="button" @click="eliminar(lead.id)" class="btn btn-danger btn-sm">
                                                <i class="icon-close"></i>
                                            </button>                           
                                        </td>
                                        <td v-if="lead.diferencia < 7" class="td2" v-text="lead.nombre + ' ' + lead.apellidos "></td>                                                    
                                        <td v-else-if="lead.diferencia >= 7 && lead.diferencia <= 15  " class="td2">
                                            <span class="badge2 badge-warning">{{ lead.nombre.toUpperCase()+' '+lead.apellidos.toUpperCase()}}</span>
                                        </td>    
                                        <td v-else-if="lead.diferencia > 15" class="td2">
                                            <span class="badge2 badge-danger">{{ lead.nombre.toUpperCase()+' '+lead.apellidos.toUpperCase()}}</span>
                                        </td>
                                        <td class="td2" v-if="lead.celular != null">
                                            <a title="Enviar whatsapp" class="btn btn-success" target="_blank" :href="'https://api.whatsapp.com/send?phone=+'+lead.clv_lada+lead.celular+'&text='"><i class="fa fa-whatsapp fa-lg"></i></a>    
                                        </td><td class="td2" v-else ></td>
                                        <td class="td2" v-if="lead.email != null" >
                                            <a title="Enviar correo" class="btn btn-secondary" :href="'mailto:'+lead.email+ ';'"> <i class="fa fa-envelope-o fa-lg"></i> </a>
                                        </td><td class="td2" v-else ></td>
                                        <td v-text="lead.direccion"></td>  
                                        <td v-text="lead.descripcion"></td>  
                                        <td class="td2" v-if="lead.status == '1'"><span class="badge badge-warning">En Seguimiento</span></td>
                                        <td class="td2" v-if="lead.status == '0'"><span class="badge badge-danger">Descartado</span></td>
                                        <td class="td2" v-if="lead.status == '3'"><span class="badge badge-success">Finalizado</span></td>
                                        <td class="td2" v-text="this.moment(lead.created_at).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td class="td2"> 
                                            <button title="Ver observaciones" type="button" class="btn btn-info pull-right" 
                                            @click="abrirModal1(lead.id,lead.motivo),listarObservacion(1,lead.id)">Ver todos</button> </td>
                                        
                                       
                                    </tr>
                                </tbody>
                            </table>
                            <table v-else-if="b_motivo == 4" class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Nombre</th>
                                        <th>Celular</th>
                                        <th>Correo</th>
                                        <th>Prioridad</th>
                                        <th>Descripción del problema</th>
                                        <th>Status</th>
                                        <th>Fecha de alta</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="lead in arrayLeads.data" :key="lead.id">
                                        <td class="td2" style="width:10%">
                                            <button title="Editar" type="button" @click="abrirModal('actualizar',lead)" class="btn btn-warning btn-sm">
                                                <i class="icon-pencil"></i>
                                            </button>  
                                            <button v-if="lead.status == 1" title="Finalizar" type="button" @click="changeStatus(lead.id)" class="btn btn-success btn-sm">
                                                <i class="icon-check"></i>
                                            </button>      
                                            <button v-if="userId == 25511 || rolId == 1 || userId == 28270" title="Eliminar" type="button" @click="eliminar(lead.id)" class="btn btn-danger btn-sm">
                                                <i class="icon-close"></i>
                                            </button>                           
                                        </td>
                                        <td v-if="lead.diferencia < 7" class="td2" v-text="lead.nombre + ' ' + lead.apellidos "></td>                                                    
                                        <td v-else-if="lead.diferencia >= 7 && lead.diferencia <= 15  " class="td2">
                                            <span class="badge2 badge-warning">{{ lead.nombre.toUpperCase()+' '+lead.apellidos.toUpperCase()}}</span>
                                        </td>    
                                        <td v-else-if="lead.diferencia > 15" class="td2">
                                            <span class="badge2 badge-danger">{{ lead.nombre.toUpperCase()+' '+lead.apellidos.toUpperCase()}}</span>
                                        </td>
                                        <td class="td2" v-if="lead.celular != null">
                                            <a title="Enviar whatsapp" class="btn btn-success" target="_blank" :href="'https://api.whatsapp.com/send?phone=+'+lead.clv_lada+lead.celular+'&text='"><i class="fa fa-whatsapp fa-lg"></i></a>    
                                        </td><td class="td2" v-else ></td>
                                        <td class="td2" v-if="lead.email != null" >
                                            <a title="Enviar correo" class="btn btn-secondary" :href="'mailto:'+lead.email+ ';'"> <i class="fa fa-envelope-o fa-lg"></i> </a>
                                        </td><td class="td2" v-else ></td>
                                        <td>
                                            <span v-if="lead.prioridad == 'Baja'" class="badge badge-light">Baja</span>
                                            <span v-if="lead.prioridad == 'Media'" class="badge badge-warning">Media</span>
                                            <span v-if="lead.prioridad == 'Alta'" class="badge badge-danger">Alta</span>
                                        </td>  
                                        <td v-text="lead.descripcion"></td>  
                                        <td class="td2" v-if="lead.status == '1'"><span class="badge badge-warning">En Seguimiento</span></td>
                                        <td class="td2" v-if="lead.status == '0'"><span class="badge badge-danger">Descartado</span></td>
                                        <td class="td2" v-if="lead.status == '3'"><span class="badge badge-success">Finalizado</span></td>
                                        <td class="td2" v-text="this.moment(lead.created_at).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td class="td2"> 
                                            <button title="Ver observaciones" type="button" class="btn btn-info pull-right" 
                                            @click="abrirModal1(lead.id,lead.motivo),listarObservacion(1,lead.id)">Ver todos</button> </td>
                                        
                                       
                                    </tr>
                                </tbody>
                            </table>
                            <table v-else-if="b_motivo == 5" class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Nombre</th>
                                        <th>Celular</th>
                                        <th>Correo</th>
                                        <th>Dirección</th>
                                        <th>Descripción</th>
                                        <th>Fecha de alta</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="lead in arrayLeads.data" :key="lead.id">
                                        <td class="td2" style="width:10%">
                                            <button title="Editar" type="button" @click="abrirModal('actualizar',lead)" class="btn btn-warning btn-sm">
                                                <i class="icon-pencil"></i>
                                            </button>       
                                            <button title="Eliminar" type="button" @click="eliminar(lead.id)" class="btn btn-danger btn-sm">
                                                <i class="icon-close"></i>
                                            </button>                           
                                        </td>
                                        <td v-if="lead.diferencia < 7" class="td2" v-text="lead.nombre + ' ' + lead.apellidos "></td>                                                    
                                        <td v-else-if="lead.diferencia >= 7 && lead.diferencia <= 15  " class="td2">
                                            <span class="badge2 badge-warning">{{ lead.nombre.toUpperCase()+' '+lead.apellidos.toUpperCase()}}</span>
                                        </td>    
                                        <td v-else-if="lead.diferencia > 15" class="td2">
                                            <span class="badge2 badge-danger">{{ lead.nombre.toUpperCase()+' '+lead.apellidos.toUpperCase()}}</span>
                                        </td>
                                        <td class="td2" v-if="lead.celular != null">
                                            <a title="Enviar whatsapp" class="btn btn-success" target="_blank" :href="'https://api.whatsapp.com/send?phone=+'+lead.clv_lada+lead.celular+'&text='"><i class="fa fa-whatsapp fa-lg"></i></a>    
                                        </td><td class="td2" v-else ></td>
                                        <td class="td2" v-if="lead.email != null" >
                                            <a title="Enviar correo" class="btn btn-secondary" :href="'mailto:'+lead.email+ ';'"> <i class="fa fa-envelope-o fa-lg"></i> </a>
                                        </td><td class="td2" v-else ></td>
                                        <td v-text="lead.direccion"></td>  
                                        <td v-text="lead.descripcion"></td>  
                                        <td class="td2" v-text="this.moment(lead.created_at).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td class="td2"> 
                                            <button title="Ver observaciones" type="button" class="btn btn-info pull-right" 
                                            @click="abrirModal1(lead.id,lead.motivo),listarObservacion(1,lead.id)">Ver todos</button> </td>
                                    </tr>
                                </tbody>
                            </table>

                            <table v-else-if="b_motivo == 6" class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Nombre</th>
                                        <th>Celular</th>
                                        <th>Correo</th>
                                        <th>Dirección del terreno</th>
                                        <th>Costo m&sup2;</th>
                                        <th>Medidas</th>
                                        <th>Fecha de alta</th>
                                        <th>Notas</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="lead in arrayLeads.data" :key="lead.id">
                                        <td class="td2" style="width:10%">
                                            <button title="Editar" type="button" @click="abrirModal('actualizar',lead)" class="btn btn-warning btn-sm">
                                                <i class="icon-pencil"></i>
                                            </button>  
                                            <button v-if="lead.status == 1" title="Finalizar" type="button" @click="changeStatus(lead.id)" class="btn btn-success btn-sm">
                                                <i class="icon-check"></i>
                                            </button>      
                                            <button v-if="userId == 25511 || rolId == 1 || userId == 28270" title="Eliminar" type="button" @click="eliminar(lead.id)" class="btn btn-danger btn-sm">
                                                <i class="icon-close"></i>
                                            </button>                           
                                        </td>
                                        <td v-if="lead.diferencia < 7" class="td2" v-text="lead.nombre + ' ' + lead.apellidos "></td>                                                    
                                        <td v-else-if="lead.diferencia >= 7 && lead.diferencia <= 15  " class="td2">
                                            <span class="badge2 badge-warning">{{ lead.nombre.toUpperCase()+' '+lead.apellidos.toUpperCase()}}</span>
                                        </td>    
                                        <td v-else-if="lead.diferencia > 15" class="td2">
                                            <span class="badge2 badge-danger">{{ lead.nombre.toUpperCase()+' '+lead.apellidos.toUpperCase()}}</span>
                                        </td>
                                        <td class="td2" v-if="lead.celular != null">
                                            <a title="Enviar whatsapp" class="btn btn-success" target="_blank" :href="'https://api.whatsapp.com/send?phone=+'+lead.clv_lada+lead.celular+'&text='"><i class="fa fa-whatsapp fa-lg"></i></a>    
                                        </td><td class="td2" v-else ></td>
                                        <td class="td2" v-if="lead.email != null" >
                                            <a title="Enviar correo" class="btn btn-secondary" :href="'mailto:'+lead.email+ ';'"> <i class="fa fa-envelope-o fa-lg"></i> </a>
                                        </td><td class="td2" v-else ></td>
                                        <td v-text="lead.direccion"></td>  
                                        <td>${{formatNumber(lead.rango1)}}</td>  
                                        <td>{{formatNumber(lead.rango2)}}m&sup2;</td>  
                                        
                                        <td class="td2" v-text="this.moment(lead.created_at).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td v-text="lead.descripcion"></td> 
                                        <td class="td2"> 
                                            <button title="Ver observaciones" type="button" class="btn btn-info pull-right" 
                                            @click="abrirModal1(lead.id,lead.motivo),listarObservacion(1,lead.id)">Ver todos</button> </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table v-else-if="b_motivo == 7" class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Nombre</th>
                                        <th>Celular</th>
                                        <th>Correo</th>
                                        <th>Descripción</th>
                                        <th>Status</th>
                                        <th>Fecha de alta</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="lead in arrayLeads.data" :key="lead.id">
                                        <td class="td2" style="width:10%">
                                            <button title="Editar" type="button" @click="abrirModal('actualizar',lead)" class="btn btn-warning btn-sm">
                                                <i class="icon-pencil"></i>
                                            </button>  
                                            <button v-if="lead.status == 1" title="Finalizar" type="button" @click="changeStatus(lead.id)" class="btn btn-success btn-sm">
                                                <i class="icon-check"></i>
                                            </button>      
                                            <button v-if="userId == 25511 || rolId == 1 || userId == 28270" title="Eliminar" type="button" @click="eliminar(lead.id)" class="btn btn-danger btn-sm">
                                                <i class="icon-close"></i>
                                            </button>                           
                                        </td>
                                        <td v-if="lead.diferencia < 7" class="td2" v-text="lead.nombre + ' ' + lead.apellidos "></td>                                                    
                                        <td v-else-if="lead.diferencia >= 7 && lead.diferencia <= 15  " class="td2">
                                            <span class="badge2 badge-warning">{{ lead.nombre.toUpperCase()+' '+lead.apellidos.toUpperCase()}}</span>
                                        </td>    
                                        <td v-else-if="lead.diferencia > 15" class="td2">
                                            <span class="badge2 badge-danger">{{ lead.nombre.toUpperCase()+' '+lead.apellidos.toUpperCase()}}</span>
                                        </td>
                                        <td class="td2" v-if="lead.celular != null">
                                            <a title="Enviar whatsapp" class="btn btn-success" target="_blank" :href="'https://api.whatsapp.com/send?phone=+'+lead.clv_lada+lead.celular+'&text='"><i class="fa fa-whatsapp fa-lg"></i></a>    
                                        </td><td class="td2" v-else ></td>
                                        <td class="td2" v-if="lead.email != null" >
                                            <a title="Enviar correo" class="btn btn-secondary" :href="'mailto:'+lead.email+ ';'"> <i class="fa fa-envelope-o fa-lg"></i> </a>
                                        </td><td class="td2" v-else ></td>
                                        <td v-text="lead.descripcion"></td>  
                                        <td class="td2" v-if="lead.status == '1'"><span class="badge badge-warning">En Seguimiento</span></td>
                                        <td class="td2" v-if="lead.status == '0'"><span class="badge badge-danger">Descartado</span></td>
                                        <td class="td2" v-if="lead.status == '3'"><span class="badge badge-success">Finalizado</span></td>
                                        <td class="td2" v-text="this.moment(lead.created_at).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td class="td2"> 
                                            <button title="Ver observaciones" type="button" class="btn btn-info pull-right" 
                                            @click="abrirModal1(lead.id,lead.motivo),listarObservacion(1,lead.id)">Ver todos</button> </td>
                                    </tr>
                                </tbody>
                            </table>

                            
                        </div>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item">
                                    <a class="page-link" href="#" @click="listarLeads(1)">Inicio</a>
                                </li>
                                <li v-if="arrayLeads.current_page-3 >= 1">
                                    <a class="page-link" href="#" 
                                    @click="listarLeads(arrayLeads.current_page-3)" 
                                    v-text="arrayLeads.current_page-3" ></a>
                                </li>
                                <li v-if="arrayLeads.current_page-2 >= 1">
                                    <a class="page-link" href="#" 
                                    @click="listarLeads(arrayLeads.current_page-2)" 
                                    v-text="arrayLeads.current_page-2" ></a>
                                </li>
                                <li v-if="arrayLeads.current_page-1 >= 1">
                                    <a class="page-link" href="#" 
                                    @click="listarLeads(arrayLeads.current_page-1)" 
                                    v-text="arrayLeads.current_page-1" ></a>
                                </li>
                                
                                <li class="page-item active">
                                    <a class="page-link" href="#" v-text="arrayLeads.current_page" ></a>
                                </li>
                                
                                <li v-if="arrayLeads.current_page+1 <= arrayLeads.last_page">
                                    <a class="page-link" href="#" 
                                    @click="listarLeads(arrayLeads.current_page+1)" 
                                    v-text="arrayLeads.current_page+1" ></a>
                                </li>
                                <li v-if="arrayLeads.current_page+2 <= arrayLeads.last_page">
                                    <a class="page-link" href="#" 
                                    @click="listarLeads(arrayLeads.current_page+2)" 
                                    v-text="arrayLeads.current_page+2" ></a>
                                </li>
                                <li v-if="arrayLeads.current_page+3 <= arrayLeads.last_page">
                                    <a class="page-link" href="#" 
                                    @click="listarLeads(arrayLeads.current_page+3)" 
                                    v-text="arrayLeads.current_page+3" ></a>
                                </li>
                                
                                <li class="page-item">
                                    <a class="page-link" href="#" @click="listarLeads(arrayLeads.last_page)">Ultimo</a>
                                </li>
                                <li></li>
                                <li>
                                    <input class="page-link" type="text" placeholder="Pagina a buscar" v-model="pagina" @keyup.enter="listarLeads(pagina)">
                                </li>
                            </ul>
                        </nav>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>

        <!--Inicio del modal -->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal == 1}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- form para solicitud de avaluo -->

                            <div class="form-group row">
                                <label class="col-md-2 form-control-label" for="text-input">Motivo de contacto:</label>
                                <div class="col-md-4">
                                    <select class="form-control" :disabled="tipoAccion == 2" v-model="motivo">
                                        <option value="0">Seleccione</option>
                                        <option value="1">Ventas</option>
                                        <option value="5">Recomendados</option>
                                        <option value="2">Postventa</option>
                                        <option value="3">Rentas</option>
                                        <option value="4">Dirección</option>
                                        <option value="6">Terrenos</option>
                                        <option value="7">Cumbres León</option>
                                    </select>
                                </div>
                                
                            </div>
                            <!--  VENTAS  -->
                            <div v-if="motivo == 1" class="">
                                <div class="card-body">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item"><a class="nav-link"  v-bind:class="{ 'active': paso==1 }" @click="paso = 1">Lead</a></li>
                                        <li class="nav-item"><a class="nav-link"  v-bind:class="{ 'active': paso==2 }" @click="paso = 2">Datos personales</a></li>
                                        <li class="nav-item"><a class="nav-link"  v-bind:class="{ 'active': paso==3 }" @click="paso = 3">Datos importantes</a></li>
                                    </ul>
                                </div>

                                <template v-if="paso == 1"> <!-- Datos del lead -->

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input"><strong>Nombre:</strong><span style="color:red;" v-show="nombre==''">(*)</span></label>
                                        <div class="col-md-4">
                                            <input type="text" v-model="nombre" class="form-control" placeholder="Nombre">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" v-model="apellidos" class="form-control" placeholder="Apellidos">
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Campaña publicitaria</label>
                                        <div class="col-md-4">
                                            <select class="form-control" v-model="campania_id">
                                                <option value="">Seleccione</option>
                                                <option v-for="medios in arrayCampanias" :key="medios.id" :value="medios.id" v-text="medios.nombre_campania + ' - ' + medios.medio_digital"></option>
                                            </select>
                                        </div>

                                        <label class="col-md-2 form-control-label" for="text-input">Medio de contacto</label>
                                        <div class="col-md-4">
                                            <input type="text" name="city" list="cityname" class="form-control" v-model="medio_contacto" placeholder="Medio de publicidad">
                                            <datalist id="cityname">
                                                <option value="">Seleccione</option>
                                                <option value="Facebook">Facebook</option>
                                                <option value="Instagram">Instagram</option>
                                                <option value="Pagina web">Pagina web</option>
                                                <option value="Llamada Telefonica">Llamada Telefónica</option>
                                                <option value="Correo Electrónico">Correo Electrónico</option>
                                                
                                            </datalist>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input"><strong>Proyecto de interes</strong></label>
                                        <div class="col-md-6">
                                            <select class="form-control" v-model="proyecto_interes" v-on:change="selectModelo(proyecto_interes)">
                                                <option value="">Seleccione</option>
                                                <option v-for="proyecto in arrayFraccionamientos" :key="proyecto.id" 
                                                    :value="proyecto.id" v-text="proyecto.nombre">
                                                </option>
                                                <option value="0">Otro...</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row" v-if="proyecto_interes == 0">

                                        <label class="col-md-3 form-control-label" v-if="proyecto_interes == 0" for="text-input">Zona o proyecto: </label>
                                        <div class="col-md-6" v-if="proyecto_interes == 0">
                                            <input type="text" v-model="zona_interes" class="form-control" placeholder="Zona de interes">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Tipo de uso</label>
                                        <div class="col-md-2">
                                            <select class="form-control" v-model="tipo_uso">
                                                <option value="">Seleccione</option>
                                                <option value="0">Habitar</option>
                                                <option value="1">Inversión</option>
                                            </select>
                                        </div>
                                        
                                        <label class="col-md-2 form-control-label" for="text-input">Prototipo recomendado: </label>
                                        <div class="col-md-5">
                                            <input type="text" name="city" list="modelosName" @keyup="selectModelo(proyecto_interes)" class="form-control" v-model="modelo_interes" placeholder="Prototipo">
                                            <datalist id="modelosName">
                                                <option value="">Modelo</option>
                                                <option v-for="modelos in arrayModelos" :key="modelos.id" :value="modelos.nombre" v-text="modelos.nombre"></option>
                                            </datalist>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Presupuesto</label>
                                        <div class="col-md-2">
                                            <p><strong>${{ formatNumber(rango1)}}</strong></p>
                                        </div>
                                        <div class="col-md-3">
                                            <input class="form-control" type="text" v-model="rango1" placeholder="Minimo">
                                            <input class="form-control" type="range" name="price-min" id="price-min" v-model="rango1" min="300000" max="2500000">
                                        </div>
                                        <div class="col-md-2">
                                            <p><strong>${{ formatNumber(rango2)}}</strong></p>
                                        </div>
                                        <div class="col-md-3">
                                            <input class="form-control" type="text" v-model="rango2" placeholder="Maximo">
                                            <input class="form-control" type="range" name="price-max" id="price-max" v-model="rango2" min="300000" max="2500000">
                                        </div>
                                    </div>

                                    <div class="form-group row line-separator"></div>

                                    <div v-if="vendedor_asign != 0 && vendedor_asign != null" class="col-md-12">
                                        <h6 v-if="vendedor_asign != 0 && vendedor_asign != null" align="center">Vendedor asignado: <strong> {{vendedor}} </strong></h6>

                                        <select class="form-control" v-if="userId == 25511 ||userId == 28270 || userId == 11 || userId == 28271 || rolId == 1"  v-model="vendedor_asign" >
                                            <option value="">Vendedor asignado</option>
                                            <option v-for="asesor in arrayAsesores" :key="asesor.id" :value="asesor.id" v-text="asesor.nombre + ' '+ asesor.apellidos"></option>
                                        </select>

                                    </div>

                                </template>

                                <template v-if="paso == 2"> <!-- Datos personales -->

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Correo electrónico: </label>
                                        <div class="col-md-6">
                                            <input type="text" v-model="email" class="form-control" placeholder="Email">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Celular: </label>
                                        <div class="col-md-3">
                                            <select  v-model="clv_lada"  class="form-control" >
                                                <option value="">Clave lada</option>
                                                <option v-for="clave in arrayClaves" :key="clave.clave+clave.pais" :value="clave.clave" v-text="clave.pais+' +'+clave.clave"></option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" v-model="celular" class="form-control" placeholder="Celular" maxlength="10">
                                        </div>

                                       
                                    </div>

                                    <div class="form-group row">
                                        
                                        <label class="col-md-2 form-control-label" for="text-input">Teléfono: </label>
                                        <div class="col-md-3">
                                            <input type="text" v-model="telefono" class="form-control" placeholder="Teléfono" maxlength="10">
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-md-1 form-control-label" for="text-input">RFC:</label>
                                        <div class="col-md-3">
                                            <input type="text" v-model="rfc" @keyup="selectRFC(rfc)" class="form-control" placeholder="RFC" maxlength="10">
                                        </div>
                                        <label class="col-md-1 form-control-label" for="text-input">NSS:</label>
                                        <div class="col-md-4">
                                            <input type="text" v-model="nss" pattern="\d*" class="form-control" v-on:keypress="isNumber($event)" placeholder="NSS" maxlength="11">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-md-1 form-control-label" for="text-input">Sexo:</label>
                                        <div class="col-md-3">
                                            <select class="form-control" v-model="sexo">
                                                <option value="">Seleccione</option>
                                                <option value="F">Femenino</option>
                                                <option value="M">Masculino</option>
                                            </select>
                                        </div>
                                        <label class="col-md-2 form-control-label" for="text-input">Fecha de nacimiento:</label>
                                        <div class="col-md-3">
                                            <input type="date" v-model="f_nacimiento" class="form-control" >
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Estado civil:</label>
                                        <div class="col-md-3">
                                            <select class="form-control" v-model="edo_civil" >
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

                                        <label class="col-md-1 form-control-label" for="text-input">Hijos?</label>
                                        <div class="col-md-2">
                                            <select class="form-control" v-model="hijos" >
                                                <option value="">Seleccione</option> 
                                                <option value="1">Si</option> 
                                                <option value="0">No</option>   
                                            </select>
                                        </div>

                                        <label v-if="hijos == 1" class="col-md-2 form-control-label" for="text-input">¿Cuantos?</label>
                                        <div v-if="hijos == 1" class="col-md-2">
                                            <input type="number" min="0" v-model="num_hijos" class="form-control" >
                                        </div>
                                    </div>

                                    
                                        <div class="form-group row line-separator"></div>

                                        <div class="col-md-12">
                                            <h6 align="center"><strong> Lugar de trabajo </strong></h6>
                                        </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Empresa</label>
                                        <div class="col-md-5">
                                            <input type="text" name="city" list="cityname" class="form-control" v-model="empresa" v-on:keypress="selectEmpresa(empresa)" placeholder="Empresa">
                                            <datalist id="cityname">
                                                <option value="">Seleccione</option>
                                                <option v-for="empresa in arrayEmpresas" :key="empresa.id" :value="empresa.nombre" v-text="empresa.nombre"></option>
                                                
                                            </datalist>
                                        </div>

                                        <label class="col-md-2 form-control-label" for="text-input">Ingresos</label>
                                        <div class="col-md-3">
                                            <input type="number" min="0" v-model="ingresos" class="form-control" >
                                        </div>
                                    </div>
                                        
                                </template>

                                <template v-if="paso == 3"> <!-- Datos Importantes -->

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Tipo de Crédito</label>
                                        <div class="col-md-4">
                                            <select  v-model="tipo_credito"  class="form-control" >
                                                <option value="">Seleccione</option>
                                                <option v-for="creditos in arrayCreditos" :key="creditos.nombre" :value="creditos.nombre" v-text="creditos.nombre"></option>
                                            </select>
                                        </div>

                                        <label class="col-md-2 form-control-label" for="text-input">¿Coacreditado?</label>
                                        <div class="col-md-2">
                                        <select  v-model="coacreditado"  class="form-control" >
                                                <option value="0">No</option>
                                                <option value="1">Si</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">¿Pago mensual deseado?</label>
                                        <div class="col-md-3">
                                            <input type="number" min="0" v-model="pago_mensual" class="form-control" >
                                        </div>

                                        <label class="col-md-2 form-control-label" for="text-input">¿Enganche?</label>
                                        <div class="col-md-3">
                                            <input type="number" min="0" v-model="enganche" class="form-control" >
                                        </div>
                                    </div>

                                    <div class="form-group row line-separator"></div>

                                    <div class="form-group row">

                                        <label class="col-md-2 form-control-label" for="text-input">¿Mascotas?</label>
                                        <div class="col-md-2">
                                            <select class="form-control" v-model="mascotas" >
                                                <option value="1">Si</option> 
                                                <option value="0">No</option>   
                                            </select>
                                        </div>

                                        <label v-if="mascotas == 1" class="col-md-2 form-control-label" for="text-input">Cuantos?</label>
                                        <div class="col-md-2">
                                            <input v-if="mascotas == 1" type="number" min="0" v-model="num_mascotas" class="form-control" >
                                        </div>

                                        <label class="col-md-2 form-control-label" v-if="mascotas == 1" for="text-input">Tamaño de mascota?</label>
                                        <div v-if="mascotas == 1" class="col-md-2">
                                            <select class="form-control" v-model="tam_mascota" >
                                                <option value="0">Seleccione</option> 
                                                <option value="1">Chico</option> 
                                                <option value="2">Mediano</option>   
                                                <option value="3">Grande</option>   
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">

                                        <label class="col-md-2 form-control-label" for="text-input">¿Autos?</label>
                                        <div class="col-md-2">
                                            <select class="form-control" v-model="autos" >
                                                <option value="1">Si</option> 
                                                <option value="0">No</option>   
                                            </select>
                                        </div>

                                        <label v-if="autos == 1" class="col-md-2 form-control-label" for="text-input">¿Cuantos?</label>
                                        <div class="col-md-2">
                                            <input v-if="autos == 1" type="number" min="0" v-model="num_autos" class="form-control" >
                                        </div>

                                    </div>

                                    
                                        <div class="form-group row line-separator"></div>

                                    <div class="form-group row">
                                        <strong>
                                            <label class="col-md-12 form-control-label" for="text-input">¿Busca alguna amenidad en especial dentro de la privada?</label>
                                        </strong>

                                        <div class="col-md-12">
                                            <input type="text" v-model="amenidad_priv" maxlength="191" class="form-control" placeholder="Amenidad en privada" >
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <strong>
                                            <label class="col-md-12 form-control-label" for="text-input">¿Habrá algún detalle en especial, que busque dentro de su casa?</label>
                                        </strong>

                                        <div class="col-md-12">
                                            <input type="text" v-model="detalle_casa" maxlength="191" class="form-control" placeholder="Detalle en su nuevo hogar" >
                                        </div>
                                    </div>

                                    <div class="form-group row line-separator"></div>

                                    <div class="form-group row">
                                        <strong>
                                            <label class="col-md-12 form-control-label" for="text-input">Perfil del lead</label>
                                        </strong>

                                        <div class="col-md-12">
                                            <textarea v-model="perfil_cliente" class="form-control" rows="5"></textarea>
                                        </div>
                                    </div>
                                        
                                </template>


                            </div>

                            <!-- POSTVENTA -->
                            <div v-if="motivo == 2 || motivo == 4" class="">

                                <template v-if="paso == 1"> <!-- Datos del lead -->
                                    <div class="form-group row line-separator"></div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input"><strong>Nombre:</strong><span style="color:red;" v-show="nombre==''">(*)</span></label>
                                        <div class="col-md-4">
                                            <input type="text" v-model="nombre" class="form-control" placeholder="Nombre">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" v-model="apellidos" class="form-control" placeholder="Apellidos">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Medio de contacto</label>
                                        <div class="col-md-4">
                                            <input type="text" name="city" list="cityname" class="form-control" v-model="medio_contacto" placeholder="Medio de publicidad">
                                            <datalist id="cityname">
                                                <option value="">Seleccione</option>
                                                <option value="Facebook">Facebook</option>
                                                <option value="Instagram">Instagram</option>
                                                <option value="Pagina web">Pagina web</option>
                                                <option value="Llamada Telefonica">Llamada Telefónica</option>
                                                <option value="Correo Electrónico">Correo Electrónico</option>
                                            </datalist>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Correo electrónico: </label>
                                        <div class="col-md-6">
                                            <input type="text" v-model="email" class="form-control" placeholder="Email">
                                        </div>
                                        
                                    </div>

                                    <div class="form-group row">
                                        
                                        <label class="col-md-2 form-control-label" for="text-input">Celular: </label>
                                        <div class="col-md-3">
                                            <input type="text" v-model="celular" class="form-control" placeholder="Celular" maxlength="10">
                                        </div>

                                        <label class="col-md-2 form-control-label" for="text-input">Teléfono: </label>
                                        <div class="col-md-3">
                                            <input type="text" v-model="telefono" class="form-control" placeholder="Teléfono" maxlength="10">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Dirección: </label>
                                        <div class="col-md-6">
                                            <input type="text" v-model="direccion" class="form-control" placeholder="Direccion">
                                        </div>
                                    </div>

                                    <div class="form-group row" v-if="tipoAccion == 1">
                                        <label class="col-md-2 form-control-label" for="text-input">Prioridad: </label>
                                        <div class="col-md-6">
                                            <select class="form-control" v-model="prioridad">
                                                <option value="Baja">Baja</option>
                                                <option value="Media">Media</option>
                                                <option value="Alta">Alta</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row" v-if="tipoAccion == 2">
                                        <label class="col-md-2 form-control-label" for="text-input">Prioridad: </label>
                                        <div class="col-md-6">
                                            <span v-if="prioridad == 'Baja'" class="badge badge-light">Baja</span>
                                            <span v-if="prioridad == 'Media'" class="badge badge-warning">Media</span>
                                            <span v-if="prioridad == 'Alta'" class="badge badge-danger">Alta</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <strong>
                                            <label class="col-md-12 form-control-label" for="text-input">Descripción del problema</label>
                                        </strong>

                                        <div class="col-md-12">
                                            <textarea v-model="descripcion" class="form-control" rows="3"></textarea>
                                        </div>
                                    </div>

                                </template>




                            </div>

                            <!--  RENTAS  -->
                            <div v-if="motivo == 3" class="">
                                <div class="form-group row line-separator"></div>

                                <template v-if="paso == 1"> <!-- Datos del lead -->

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input"><strong>Nombre:</strong><span style="color:red;" v-show="nombre==''">(*)</span></label>
                                        <div class="col-md-4">
                                            <input type="text" v-model="nombre" class="form-control" placeholder="Nombre">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" v-model="apellidos" class="form-control" placeholder="Apellidos">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Correo electrónico: </label>
                                        <div class="col-md-6">
                                            <input type="text" v-model="email" class="form-control" placeholder="Email">
                                        </div>
                                        
                                    </div>

                                    <div class="form-group row">
                                        
                                        <label class="col-md-2 form-control-label" for="text-input">Celular: </label>
                                        <div class="col-md-3">
                                            <input type="text" v-model="celular" class="form-control" placeholder="Celular" maxlength="10">
                                        </div>

                                        <label class="col-md-2 form-control-label" for="text-input">Teléfono: </label>
                                        <div class="col-md-3">
                                            <input type="text" v-model="telefono" class="form-control" placeholder="Teléfono" maxlength="10">
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="form-group row">

                                        <label class="col-md-2 form-control-label" for="text-input">Medio de contacto</label>
                                        <div class="col-md-4">
                                            <input type="text" name="city" list="cityname" class="form-control" v-model="medio_contacto" placeholder="Medio de publicidad">
                                            <datalist id="cityname">
                                                <option value="">Seleccione</option>
                                                <option value="Facebook">Facebook</option>
                                                <option value="Instagram">Instagram</option>
                                                <option value="Pagina web">Pagina web</option>
                                                <option value="Llamada Telefonica">Llamada Telefónica</option>
                                                <option value="Correo Electrónico">Correo Electrónico</option>
                                                
                                            </datalist>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input"><strong>Proyecto de interes</strong></label>
                                        <div class="col-md-6">
                                            <select class="form-control" v-model="proyecto_interes" v-on:change="selectModelo(proyecto_interes)">
                                                <option value="">Seleccione</option>
                                                <option v-for="proyecto in arrayFraccionamientos" :key="proyecto.id" 
                                                    :value="proyecto.id" v-text="proyecto.nombre">
                                                </option>
                                                <option value="0">Otro...</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row" v-if="proyecto_interes == 0">

                                        <label class="col-md-3 form-control-label" v-if="proyecto_interes == 0" for="text-input">Zona o proyecto: </label>
                                        <div class="col-md-6" v-if="proyecto_interes == 0">
                                            <input type="text" v-model="zona_interes" class="form-control" placeholder="Zona de interes">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        
                                        <label class="col-md-3 form-control-label" for="text-input">Prototipo de interes: </label>
                                        <div class="col-md-5">
                                            <input type="text" name="city" list="modelosName" @keyup="selectModelo(proyecto_interes)" class="form-control" v-model="modelo_interes" placeholder="Prototipo">
                                            <datalist id="modelosName">
                                                <option value="">Modelo</option>
                                                <option v-for="modelos in arrayModelos" :key="modelos.id" :value="modelos.nombre" v-text="modelos.nombre"></option>
                                            </datalist>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Rango de mensualidad</label>
                                        <div class="col-md-2">
                                            <p><strong>${{ formatNumber(rango1)}}</strong></p>
                                        </div>
                                        <div class="col-md-3">
                                            <input class="form-control" type="text" v-model="rango1" placeholder="Minimo">
                                            <input class="form-control" type="range" name="price-min" id="price-min" v-model="rango1" min="5000" max="30000">
                                        </div>
                                        <div class="col-md-2">
                                            <p><strong>${{ formatNumber(rango2)}}</strong></p>
                                        </div>
                                        <div class="col-md-3">
                                            <input class="form-control" type="text" v-model="rango2" placeholder="Maximo">
                                            <input class="form-control" type="range" name="price-max" id="price-max" v-model="rango2" min="5000" max="35000">
                                        </div>
                                    </div>

                                    
                                </template>


                            </div>

                            <!-- RECOMENDADOS -->
                            <div v-if="motivo == 5" class="">

                                <template v-if="paso == 1"> <!-- Datos del lead -->
                                    <div class="form-group row line-separator"></div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input"><strong>RFC:</strong><span style="color:red;" v-show="nombre==''">(*)</span></label>
                                        <div class="col-md-4">
                                            <input type="text" v-model="rfc" class="form-control" placeholder="RFC">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" @click="getDatosCliente(rfc)" class="btn btn-primary btn-sm">
                                                <i class="fa fa-search"></i>
                                            </button> 
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input"><strong>Nombre:</strong><span style="color:red;" v-show="nombre==''">(*)</span></label>
                                        <div class="col-md-4">
                                            <input type="text" v-model="nombre" class="form-control" placeholder="Nombre">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" v-model="apellidos" class="form-control" placeholder="Apellidos">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Medio de contacto</label>
                                        <div class="col-md-4">
                                            <input type="text" name="city" list="cityname" class="form-control" v-model="medio_contacto" placeholder="Medio de publicidad">
                                            <datalist id="cityname">
                                                <option value="">Seleccione</option>
                                                <option value="Facebook">Facebook</option>
                                                <option value="Instagram">Instagram</option>
                                                <option value="Pagina web">Pagina web</option>
                                                <option value="Llamada Telefonica">Llamada Telefónica</option>
                                                <option value="Correo Electrónico">Correo Electrónico</option>
                                            </datalist>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Correo electrónico: </label>
                                        <div class="col-md-6">
                                            <input type="text" v-model="email" class="form-control" placeholder="Email">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        
                                        <label class="col-md-2 form-control-label" for="text-input">Celular: </label>
                                        <div class="col-md-3">
                                            <input type="text" v-model="celular" class="form-control" placeholder="Celular" maxlength="10">
                                        </div>

                                        <label class="col-md-2 form-control-label" for="text-input">Teléfono: </label>
                                        <div class="col-md-3">
                                            <input type="text" v-model="telefono" class="form-control" placeholder="Teléfono" maxlength="10">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Dirección: </label>
                                        <div class="col-md-6">
                                            <input type="text" v-model="direccion" class="form-control" placeholder="Direccion">
                                        </div>
                                    </div>

                                    <div class="form-group row line-separator"></div>

                                    <center><h6>Recomendado</h6></center>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input"><strong>Nombre:</strong><span style="color:red;" v-show="nombre==''">(*)</span></label>
                                        <div class="col-md-4">
                                            <input type="text" v-model="nombre_rec" class="form-control" placeholder="Nombre">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" v-model="apellidos_rec" class="form-control" placeholder="Apellidos">
                                        </div>
                                    </div>

                                     <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Correo electrónico: </label>
                                        <div class="col-md-6">
                                            <input type="text" v-model="email_rec" class="form-control" placeholder="Email">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        
                                        <label class="col-md-2 form-control-label" for="text-input">Celular: </label>
                                        <div class="col-md-3">
                                            <input type="text" v-model="celular_rec" class="form-control" placeholder="Celular" maxlength="10">
                                        </div>

                                        <label class="col-md-2 form-control-label" for="text-input">Teléfono: </label>
                                        <div class="col-md-3">
                                            <input type="text" v-model="telefono_rec" class="form-control" placeholder="Teléfono" maxlength="10">
                                        </div>
                                    </div>



                                    <div class="form-group row">
                                        <strong>
                                            <label class="col-md-12 form-control-label" for="text-input">Descripción</label>
                                        </strong>

                                        <div class="col-md-12">
                                            <textarea v-model="descripcion" class="form-control" rows="3"></textarea>
                                        </div>
                                    </div>

                                </template>
                            </div>

                            <!--  RENTAS  -->
                            <div v-if="motivo == 6" class="">
                                <div class="form-group row line-separator"></div>

                                <template v-if="paso == 1"> <!-- Datos del lead -->

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input"><strong>Nombre:</strong><span style="color:red;" v-show="nombre==''">(*)</span></label>
                                        <div class="col-md-4">
                                            <input type="text" v-model="nombre" class="form-control" placeholder="Nombre">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" v-model="apellidos" class="form-control" placeholder="Apellidos">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Correo electrónico: </label>
                                        <div class="col-md-6">
                                            <input type="text" v-model="email" class="form-control" placeholder="Email">
                                        </div>
                                        
                                    </div>

                                    <div class="form-group row">
                                        
                                        <label class="col-md-2 form-control-label" for="text-input">Celular: </label>
                                        <div class="col-md-3">
                                            <input type="text" v-model="celular" class="form-control" placeholder="Celular" maxlength="10">
                                        </div>

                                        <label class="col-md-2 form-control-label" for="text-input">Teléfono: </label>
                                        <div class="col-md-3">
                                            <input type="text" v-model="telefono" class="form-control" placeholder="Teléfono" maxlength="10">
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="form-group row">

                                        <label class="col-md-2 form-control-label" for="text-input">Medio de contacto</label>
                                        <div class="col-md-4">
                                            <input type="text" name="city" list="cityname" class="form-control" v-model="medio_contacto" placeholder="Medio de publicidad">
                                            <datalist id="cityname">
                                                <option value="">Seleccione</option>
                                                <option value="Facebook">Facebook</option>
                                                <option value="Instagram">Instagram</option>
                                                <option value="Pagina web">Pagina web</option>
                                                <option value="Llamada Telefonica">Llamada Telefónica</option>
                                                <option value="Correo Electrónico">Correo Electrónico</option>
                                                
                                            </datalist>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input"><strong>Ubicación del terreno</strong></label>
                                        <div class="col-md-8">
                                            <input type="text" v-model="direccion" class="form-control" placeholder="Dirección">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        
                                        <label class="col-md-3 form-control-label" for="text-input">Medida en m&sup2;: </label>
                                        <div class="col-md-5">
                                            <input class="form-control" type="number" v-model="rango2">
                                            <p><strong>{{ formatNumber(rango2)}}m&sup2;</strong></p>
                                            
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        
                                        <label class="col-md-3 form-control-label" for="text-input">Precio por m&sup2;: </label>
                                        <div class="col-md-5">
                                            <input class="form-control" type="number" v-model="rango1">
                                            <p><strong>${{ formatNumber(rango1)}}</strong></p>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <strong>
                                            <label class="col-md-12 form-control-label" for="text-input">Notas</label>
                                        </strong>

                                        <div class="col-md-12">
                                            <textarea v-model="descripcion" class="form-control" rows="3"></textarea>
                                        </div>
                                    </div>


                                    
                                </template>


                            </div>

                            <!-- POSTVENTA -->
                            <div v-if="motivo == 7" class="">

                                <template v-if="paso == 1"> <!-- Datos del lead -->
                                    <div class="form-group row line-separator"></div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input"><strong>Nombre:</strong><span style="color:red;" v-show="nombre==''">(*)</span></label>
                                        <div class="col-md-4">
                                            <input type="text" v-model="nombre" class="form-control" placeholder="Nombre">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" v-model="apellidos" class="form-control" placeholder="Apellidos">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Medio de contacto</label>
                                        <div class="col-md-4">
                                            <input type="text" name="city" list="cityname" class="form-control" v-model="medio_contacto" placeholder="Medio de publicidad">
                                            <datalist id="cityname">
                                                <option value="">Seleccione</option>
                                                <option value="Facebook">Facebook</option>
                                                <option value="Instagram">Instagram</option>
                                                <option value="Pagina web">Pagina web</option>
                                                <option value="Llamada Telefonica">Llamada Telefónica</option>
                                                <option value="Correo Electrónico">Correo Electrónico</option>
                                            </datalist>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Correo electrónico: </label>
                                        <div class="col-md-6">
                                            <input type="text" v-model="email" class="form-control" placeholder="Email">
                                        </div>
                                        
                                    </div>

                                    <div class="form-group row">
                                        
                                        <label class="col-md-2 form-control-label" for="text-input">Celular: </label>
                                        <div class="col-md-3">
                                            <input type="text" v-model="celular" class="form-control" placeholder="Celular" maxlength="10">
                                        </div>

                                        <label class="col-md-2 form-control-label" for="text-input">Teléfono: </label>
                                        <div class="col-md-3">
                                            <input type="text" v-model="telefono" class="form-control" placeholder="Teléfono" maxlength="10">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Dirección: </label>
                                        <div class="col-md-6">
                                            <input type="text" v-model="direccion" class="form-control" placeholder="Direccion">
                                        </div>
                                    </div>

                                   
                                    <div class="form-group row">
                                        <strong>
                                            <label class="col-md-12 form-control-label" for="text-input">Descripción </label>
                                        </strong>

                                        <div class="col-md-12">
                                            <textarea v-model="descripcion" class="form-control" rows="3"></textarea>
                                        </div>
                                    </div>

                                </template>

                            </div>

                            <!-- Div para mostrar los errores que mande validerFraccionamiento -->
                                    <div v-show="errorProspecto" class="form-group row div-error">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMostrarMsjProspecto" :key="error" v-text="error">
                                            </div>
                                        </div>
                                    </div>                                
                            <!-- fin del form solicitud de avaluo -->


                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <template v-if="motivo == 1">
                                <button type="button" 
                                v-if="(tipoAccion == 2 && vendedor_asign == userId && prospecto == 0 && rfc != '' && status !=0) || rolId == 1 && prospecto == 0 && status !=0"
                                class="btn btn-dark" @click="sendProspecto()">Enviar a prospectos</button>
                            </template>
                            
                            <button type="button" 
                                v-if="(tipoAccion == 2 && motivo == 1 && status !=0)"
                                class="btn btn-danger" @click="descartar()">Descartar
                            </button>

                            <!-- <button type="button" 
                                v-if="(tipoAccion == 2 && motivo == 1 && status !=0 && status !=3)"
                                class="btn btn-success" @click="finalizar()">Finalizar
                            </button> -->

                            <div></div>

                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion == 1 && motivo != 0" class="btn btn-success" @click="storeLead()">Registrar</button>
                            <button type="button" v-if="tipoAccion == 2" class="btn btn-primary" @click="updateLead()">Guardar cambios</button>
                        </div>
                    </div>
                      <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal consulta-->

             <!--Inicio del modal observaciones-->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal == 2}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
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
                                         <textarea rows="3" cols="30" v-model="comentario" class="form-control" placeholder="Observacion"></textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha para recordatorio</label>
                                    <div class="col-md-3">
                                         <input type="date" class="form-control" v-model="fecha_aviso" placeholder="Fecha de notificación">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button"  class="btn btn-primary" @click="storeObs()">Guardar</button>
                                    </div>
                                </div>

                                
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Usuario</th>
                                            <th>Observacion</th>
                                            <th>Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="observacion in arrayObs.data" :key="observacion.id">
                                            
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
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        </div>
                    </div>
                      <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
    </main>
</template>

<script>
import vSelect from 'vue-select';
export default {
    props:{
            rolId:{type: String},
            userId:{type: String}
        },
    
    data() {
        return{
            
            arrayLeads:[],
           
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
            tituloModal:'',
            tipoAccion: 0,
            editar:0,
            b_cliente:'',
            b_status : '',
            b_campania : '',
            b_asesor:'',
            b_motivo:1,
            b_fecha1:'',
            b_fecha2:'',
            b_proyecto:'',
            b_prioridad:'',
            b_modelo:'',
            proceso : false,

            datos : [],
            arrayEmpresa: [],
            arrayColonias:[],
            arrayEstados:[],
            arrayCiudades:[],
            arrayCampanias:[],
            arrayFraccionamientos:[],
            arrayModelos:[],
            arrayEmpresas:[],
            arrayCreditos:[],
            arrayObs:[],
            arrayAsesores:[], 
            arrayClaves:[],
            
            medio_contacto: '',
            medio_publicidad: '',
            campania_id: '',
            nombre: '',
            apellidos: '',
            email: '',
            celular: '',
            clv_lada:52,
            telefono: '',
            proyecto_interes: '',
            modelo_interes: '',
            rango1: '',
            rango2: '',
            edo_civil: '',
            perfil_cliente: '',
            ingresos: '',
            coacreditado: '',
            hijos: '',
            num_hijos: 0,
            mascotas: '',
            tam_mascota: '',
            tipo_credito: '',
            tipo_uso: '',
            empresa: '',
            status: '',
            vendedor_asign: 0,
            rfc:'',
            nss:'',
            sexo:'',
            f_nacimiento:'',
            num_mascotas:0,
            autos:'',
            num_autos:0,
            amenidad_priv:'',
            detalle_casa:'',
            comentario:'',
            zona_interes:'',
            pago_mensual:0,
            enganche:0,
            vendedor : '',
            prospecto:0,
            prioridad:'Baja',

            nombre_rec:'',
            apellidos_rec:'',
            email_rec:'',
            celular_rec:'',
            telefono_rec:'',

            motivo:0,
            descripcion:'',
            direccion:'',
            status:'',
            pagina : 0,
            fecha_aviso : '',

            errorMostrarMsjProspecto : [],
            errorProspecto : 0,

        }
    },
    computed:{

    },
    components:{
        vSelect
    },
    methods: {
        validarProspecto(){
            this.errorProspecto=0;
            this.errorMostrarMsjProspecto=[];
            if(this.rfc == null)
                this.rfc = '';

            if(this.nombre=='' || this.apellidos=='') 
                this.errorMostrarMsjProspecto.push("El nombre del prospecto no puede ir vacio.");
            if(this.sexo=='' || this.sexo == null) 
                this.errorMostrarMsjProspecto.push("Seleccionar el sexo del prospecto.");
            if(this.celular=='' || this.celular == null) 
                this.errorMostrarMsjProspecto.push("Ingresar numero de celular.");
            if(this.email=='' || this.email == null) 
                this.errorMostrarMsjProspecto.push("Ingresar email personal.");
            if(this.empresa=='' || this.empresa == null) 
                this.errorMostrarMsjProspecto.push("Seleccionar empresa.");
            if(this.f_nacimiento=='' || this.f_nacimiento == null) 
                this.errorMostrarMsjProspecto.push("Ingresar fecha de nacimiento.");
            if(this.rfc=='' || this.rfc.length < 10 || this.rfc == null) 
                this.errorMostrarMsjProspecto.push("RFC no valido");
            if(this.edo_civil==0 || this.edo_civil == '' || this.edo_civil == null) 
                this.errorMostrarMsjProspecto.push("Seleccionar estado civil.");
            if(this.proyecto_interes==0 || this.proyecto_interes == '') 
                this.errorMostrarMsjProspecto.push("Seleccionar proyecto de interes.");
            if(this.medio_contacto=='') 
                this.errorMostrarMsjProspecto.push("Escribir medio de contacto.");

            if(this.errorMostrarMsjProspecto.length)//Si el mensaje tiene almacenado algo en el array
                this.errorProspecto = 1;

            return this.errorProspecto;
        },

        sendProspecto(){
            if(this.validarProspecto()) //Se verifica si hay un error (campo vacio)
                return;
            
            let me = this;
            //Con axios se llama el metodo store del controller
            axios.post('/leads/sendProspectos',{
                'id' : this.id,
                'clv_lada' : this.clv_lada,
                'nombre' : this.nombre,
                'apellidos' : this.apellidos,
                'telefono' : this.telefono,
                'celular' : this.celular,
                'medio_publicidad' : this.medio_contacto,
                'proyecto_interes' : this.proyecto_interes,
                'email' : this.email,
                'vendedor_asign' : this.vendedor_asign,

                'rfc' : this.rfc,
                'nss' : this.nss,
                'sexo' : this.sexo,
                'f_nacimiento' : this.f_nacimiento,
                'edo_civil' : this.edo_civil,
                'empresa' : this.empresa,
                'ingresos' : this.ingresos,

                ////////////// Paso 3 /////////////////
                'coacreditado' : this.coacreditado,
            }).then(function (response){
                me.cerrarModal();
                me.listarLeads(1);
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
        asignarVendedor(id){
            Swal.fire({
                title: '¿Estas seguro de asignar un vendedor a este lead?',
                text: "Este cambio no se podrá deshacer!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si asignar!',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.value) {

                    axios.put('/leads/asignarLead',{
                        'id': id,
                    }).then(
                        rsponse => {
                            this.myAlerts.popAlert('Asignado correctamente');
                            this.listarLeads(this.arrayLeads.current_page);

                            return response.data;
                        }
                    ).catch(error => console.log(error));
                }
            });

        },

        eliminar(id){
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

            axios.delete('/leads/delete', 
                    {params: {'id': id}}).then(function (response){
                    swal(
                    'Borrado!',
                    'Lead borrado correctamente.',
                    'success'
                    )
                    me.listarLeads(1);
                }).catch(function (error){
                    console.log(error);
                });
            }
            })
        },

        changeStatus(id){
            Swal.fire({
                title: '¿Estas seguro de finalizar el seguimiento de este lead?',
                text: "Este cambio no se podrá deshacer!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si continuar!',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.value) {

                    axios.put('/leads/changeStatus',{
                        'id': id,
                        'status':3
                    }).then(
                        rsponse => {
                            this.myAlerts.popAlert('finalizado correctamente');
                            this.listarLeads(this.arrayLeads.current_page);

                            return response.data;
                        }
                    ).catch(error => console.log(error));
                }
            });

        },

        finalizar(){
            
            let me = this;
            (async function getFruit () {
                const {value: comentario} = await Swal({
                    title: 'Finalizar proceso de seguimiento',
                    input: 'textarea',
                    inputPlaceholder: 'Agregue una observación aqui...',
                    showCancelButton: true,
                    inputValidator: (value) => {
                        return new Promise((resolve) => {
                        if (value != '') {
                            resolve()
                        } else {
                            resolve('Es necesario escribir una observación :)')
                        }
                        })
                    }
                })

                if (comentario) {

                        axios.post('/leads/storeObs',{

                        'lead_id' : me.id,
                        'comentario' :comentario,
                        
                })
                    //Con axios se llama el metodo update de LoteController
                    axios.put('/leads/changeStatus',{
                    'id': me.id,
                    'status':3
                }).then(
                    rsponse => {
                        me.myAlerts.popAlert('finalizado correctamente');
                        me.listarLeads(me.arrayLeads.current_page);
                        me.cerrarModal();

                        return response.data;
                    }
                ).catch(error => console.log(error));
                }

                })()

        },

        descartar(){
            Swal.fire({
                title: '¿Estas seguro de finalizar el seguimiento de este lead?',
                text: "Este cambio no se podrá deshacer!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si continuar!',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.value) {

                    axios.put('/leads/changeStatus',{
                        'id': this.id,
                        'status':0
                    }).then(
                        rsponse => {
                            this.myAlerts.popAlert('finalizado correctamente');
                            this.listarLeads(this.arrayLeads.current_page);

                            return response.data;
                        }
                    ).catch(error => console.log(error));
                }
            });

        },
        changeMotivo(){
            this.b_cliente='';
            this.b_status ='';
            this.b_campania ='';
            this.b_asesor='';
            this.b_fecha1='';
            this.b_fecha2='';
            this.b_proyecto='';
            this.b_prioridad='';
            this.b_modelo ='';

            this.listarLeads(1);
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
                me.vendedor = vendedorrfc[0]['nombre'] + ' ' + vendedorrfc[0]['apellidos'];
                me.vendedor_asign = vendedorrfc[0]['id'] ;
                Swal({
                title: 'Este RFC ya ha sido agregado por: ' + me.vendedor ,
                animation: false,
                customClass: 'animated tada'
                })
            } 
            else{
                if(me.vendedor_asign == '' || me.vendedor_asign == 0)
                    me.vendedor_asign = 0;
            }
            })
            .catch(function (error) {
                console.log(error);
            });


        },

        getDatosCliente(rfc){
            var url = '/leads/getCliente?rfc=' + rfc;
            let me = this;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                var datos = respuesta.cliente[0];
                me.nombre = datos.nombre;
                me.apellidos = datos.apellidos;
                if(datos.interior != null)
                    me.direccion = datos.calle+' No.'+datos.numero+'-'+datos.interior+' Manzana: '+datos.manzana;
                else
                    me.direccion = datos.calle+' No.'+datos.numero+' Manzana: '+datos.manzana;
                me.celular = datos.celular;
                me.email = datos.email;
                me.telefono = datos.telefono;
                })
            .catch(function (error) {
                console.log(error);
            });


        },
        selectAsesores(){
            let me = this;
            me.arrayAsesores=[];
            var url = '/select/asesores?tipo=0';
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayAsesores = respuesta.personas;
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
        listarLeads (page){
            if(this.rolId == 12)
                this.b_motivo = 2;

            if(this.rolId == 3)
                this.b_motivo = 6;
            
            axios.get('/leads/index'+
                '?buscar='+this.b_cliente+
                '&campania='+this.b_campania+
                '&status='+this.b_status+
                '&asesor='+this.b_asesor+
                '&motivo='+this.b_motivo+
                '&fecha1='+this.b_fecha1+
                '&fecha2='+this.b_fecha2+
                '&proyecto='+this.b_proyecto+
                '&prioridad='+this.b_prioridad+
                '&modelo='+this.b_modelo+
                '&page='+page
                
            ).then(
                response => this.arrayLeads = response.data,

                this.pagina = ''
            ).catch(error => console.log(error));
        },
        selectEmpresa(buscar){
            let me = this;

            var url = '/empresa'+'?buscar='+buscar + '&criterio=nombre';
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayEmpresas = respuesta.empresas.data;
                
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
        selectModelo(buscar){
            let me = this;
            
            me.arrayModelos=[];
            var url = '/select_modelo_proyecto?buscar=' + buscar;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayModelos = respuesta.modelos;
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
        selectCampania(buscar){
            let me = this;

            var url = '/campanias/campaniaActiva?buscar='+buscar;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayCampanias = respuesta;
                
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
        selectCiudades(estado){
            let me = this;
            var url = '/select_ciudades?buscar='+estado;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayCiudades=[];
                me.arrayCiudades = respuesta.ciudades;
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
                me.arrayColonias=[];
                me.arrayColonias = respuesta.colonias;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        cerrarModal(){
            this.datos = [];
            this.modal = 0;
        },

        listarObservacion(page,id){
            axios.get('/leads/getObs'+
                '?id='+id+
                '&page='+page
                
            ).then(
                response => this.arrayObs = response.data
            ).catch(error => console.log(error));
        },
            sms(){
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/customsms').then(function (response){
                    
                    
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Deposito agregado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },

        storeLead(){
            if(this.nombre == '' || this.medio_contacto == '' ||this.proceso==true) //Se verifica si hay un error (campo vacio)
            {
                Swal({
                title: 'Verificar nombre o medio de contacto',
                animation: false,
                customClass: 'animated tada'
                })
                this.proceso = false;
                return;
            }

            this.proceso=true;

            let me = this;
            //Con axios se llama el metodo store de FraccionaminetoController
            axios.post('/leads/store',{

                'nombre' : this.nombre,
                'apellidos' : this.apellidos,
                'telefono' : this.telefono,
                'clv_lada' : this.clv_lada,
                'celular' : this.celular,
                'campania_id' : this.campania_id,
                'medio_contacto' : this.medio_contacto,
                'proyecto_interes' : this.proyecto_interes,
                'tipo_uso' : this.tipo_uso,
                'modelo_interes' : this.modelo_interes,
                'rango1' : this.rango1,
                'rango2' : this.rango2,
                'email' : this.email,
                'zona_interes' : this.zona_interes,
                'vendedor_asign' : this.vendedor_asign,
                'prioridad' : this.prioridad,

                'nombre_rec': this.nombre_rec,
                'apellidos_rec': this.apellidos_rec,
                'email_rec': this.email_rec,
                'celular_rec': this.celular_rec,
                'telefono_rec': this.telefono_rec,

                /////////////// PASO 2 ////////////////
                'rfc' : this.rfc,
                'nss' : this.nss,
                'sexo' : this.sexo,
                'f_nacimiento' : this.f_nacimiento,
                'edo_civil' : this.edo_civil,
                'hijos' : this.hijos,
                'num_hijos' : this.num_hijos,
                'empresa' : this.empresa,
                'ingresos' : this.ingresos,
                'pago_mensual' : this.pago_mensual,
                'enganche' : this.enganche,

                ////////////// Paso 3 /////////////////
                'mascotas' : this.mascotas,
                'tam_mascota' : this.tam_mascota,
                'num_mascotas' : this.num_mascotas,
                'tipo_credito' : this.tipo_credito,
                'coacreditado' : this.coacreditado,
                'num_autos' : this.num_autos,
                'autos' : this.autos,
                'amenidad_priv' : this.amenidad_priv,
                'detalle_casa' : this.detalle_casa,
                'perfil_cliente' : this.perfil_cliente,

                'motivo' : this.motivo,
                'descripcion' : this.descripcion,
                'direccion' : this.direccion,
                
            }).then(function (response){
                me.proceso=false;
                me.cerrarModal();
                me.listarLeads(1);
                
                me.myAlerts.popAlert('Lead registrado correctamente');
            }).catch(function (error){
                console.log(error);
                this.proceso = false;
            });
        },

        updateLead(){
            if(this.nombre == '' || this.proceso==true) //Se verifica si hay un error (campo vacio)
            {
                Swal({
                title: 'Verificar nombre o medio de contacto',
                animation: false,
                customClass: 'animated tada'
                })
                this.proceso = false;
                return;
            }

            this.proceso=true;

            let me = this;
            //Con axios se llama el metodo store de FraccionaminetoController
            axios.put('/leads/update',{

                'id' : this.id,
                'nombre' : this.nombre,
                'apellidos' : this.apellidos,
                'telefono' : this.telefono,
                'celular' : this.celular,
                'clv_lada' : this.clv_lada,
                'campania_id' : this.campania_id,
                'medio_contacto' : this.medio_contacto,
                'proyecto_interes' : this.proyecto_interes,
                'tipo_uso' : this.tipo_uso,
                'modelo_interes' : this.modelo_interes,
                'rango1' : this.rango1,
                'rango2' : this.rango2,
                'email' : this.email,
                'zona_interes' : this.zona_interes,
                'vendedor_asign' : this.vendedor_asign,
                'prioridad' : this.prioridad,

                'nombre_rec': this.nombre_rec,
                'apellidos_rec': this.apellidos_rec,
                'email_rec': this.email_rec,
                'celular_rec': this.celular_rec,
                'telefono_rec': this.telefono_rec,

                /////////////// PASO 2 ////////////////
                'rfc' : this.rfc,
                'nss' : this.nss,
                'sexo' : this.sexo,
                'f_nacimiento' : this.f_nacimiento,
                'edo_civil' : this.edo_civil,
                'hijos' : this.hijos,
                'num_hijos' : this.num_hijos,
                'empresa' : this.empresa,
                'ingresos' : this.ingresos,
                'pago_mensual' : this.pago_mensual,
                'enganche' : this.enganche,

                ////////////// Paso 3 /////////////////
                'mascotas' : this.mascotas,
                'tam_mascota' : this.tam_mascota,
                'num_mascotas' : this.num_mascotas,
                'tipo_credito' : this.tipo_credito,
                'coacreditado' : this.coacreditado,
                'num_autos' : this.num_autos,
                'autos' : this.autos,
                'amenidad_priv' : this.amenidad_priv,
                'detalle_casa' : this.detalle_casa,
                'perfil_cliente' : this.perfil_cliente,

                'motivo' : this.motivo,
                'descripcion' : this.descripcion,
                'direccion' : this.direccion,
                
            }).then(function (response){
                me.proceso=false;
                me.cerrarModal();
                me.listarLeads(1);
                
                me.myAlerts.popAlert('Guardado correctamente');
            }).catch(function (error){
                console.log(error);
                this.proceso = false;
            });
        },

        storeObs(){
            this.proceso=true;

            let me = this;
            //Con axios se llama el metodo store de FraccionaminetoController
            axios.post('/leads/storeObs',{

                'lead_id' : this.id,
                'comentario' : this.comentario,
                'fecha_aviso' : this.fecha_aviso
                
            }).then(function (response){
                me.proceso=false;
                me.listarObservacion(1,me.id);
                
                //Se muestra mensaje Success
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
                this.proceso = false;
            });
        },

        abrirModal1(id,motivo){
            this.modal = 2;
            this.id = id; 
            this.comentario = '';
            this.tituloModal='Observaciones';
            this.motivo = motivo;
            this.fecha_aviso = '';
        },
        
        abrirModal(accion,data =[]){
            this.selectEstados();
            
            this.selectEmpresa(this.empresa);
            this.selectCreditos();

            switch(accion){
                case 'actualizar':
                {
                    this.selectCampania(1);
                    this.tituloModal='Actualizar Lead';
                    this.paso = 1;
                    this.modal = 1;
                    this.tipoAccion = 2;

                    this.id = data['id'];

                    this.motivo = data['motivo'];
                    this.descripcion = data['descripcion'];
                    this.direccion = data['direccion'];
                    
                    //////////// PASO 1 //////////////////
                    this.nombre = data['nombre'];
                    this.apellidos = data['apellidos'];
                    this.telefono = data['telefono'];
                    this.clv_lada = data['clv_lada'];
                    this.celular = data['celular'];
                    this.campania_id = data['campania_id'];
                    this.medio_contacto = data['medio_contacto'];
                    this.proyecto_interes = data['proyecto_interes'];
                    this.zona_interes = data['zona_interes'];
                    this.tipo_uso = data['tipo_uso'];
                    this.modelo_interes = data['modelo_interes'];
                    this.rango1 = data['rango1'];
                    this.rango2 = data['rango2'];
                    this.email = data['email'];
                    this.vendedor_asign = data['vendedor_asign'];
                    this.vendedor = data['vendedor'];
                    this.prioridad = data['prioridad'];

                    this.nombre_rec = data['nombre_rec'];
                    this.apellidos_rec = data['apellidos_rec'];
                    this.email_rec = data['email_rec'];
                    this.celular_rec = data['celular_rec'];
                    this.telefono_rec = data['telefono_rec'];

                    /////////////// PASO 2 ////////////////
                    this.rfc = data['rfc'];
                    this.nss = data['nss'];
                    this.sexo = data['sexo'];
                    this.f_nacimiento = data['f_nacimiento'];
                    this.edo_civil = data['edo_civil'];
                    this.hijos = data['hijos'];
                    this.num_hijos = data['num_hijos'];
                    this.empresa = data['empresa'];
                    this.ingresos = data['ingresos'];
                    this.pago_mensual = data['pago_mensual'];
                    this.enganche = data['enganche'];

                    ////////////// Paso 3 /////////////////
                    this.mascotas = data['mascotas'];
                    this.tam_mascota = data['tam_mascota'];
                    this.num_mascotas = data['num_mascotas'];
                    this.tipo_credito = data['tipo_credito'];
                    this.coacreditado = data['coacreditado'];
                    this.num_autos = data['num_autos'];
                    this.autos = data['autos'];
                    this.amenidad_priv = data['amenidad_priv'];
                    this.detalle_casa = data['detalle_casa'];
                    this.perfil_cliente = data['perfil_cliente'];
                    this.prospecto = data['prospecto'];

                    this.status = data['status'];
                    
                    break;
                }

                case 'nuevo':{
                    this.selectCampania('');
                    this.tituloModal='Nuevo Lead';
                    this.paso = 1;
                    this.modal = 1;
                    this.tipoAccion = 1;

                    this.motivo = 0;
                    this.descripcion = '';
                    this.direccion = '';
                    
                    //////////// PASO 1 //////////////////
                    this.nombre = '';
                    this.apellidos = '';
                    this.telefono = '';
                    this.celular = '';
                    this.campania_id = '';
                    this.medio_contacto = '';
                    this.proyecto_interes = '';
                    this.zona_interes = '';
                    this.tipo_uso = '';
                    this.modelo_interes = '';
                    this.rango1 = '';
                    this.rango2 = '';
                    this.email = '';
                    this.vendedor_asign = 0;
                    this.vendedor = '';
                    this.prioridad = 'Baja';

                    this.nombre_rec = '';
                    this.apellidos_rec = '';
                    this.email_rec = '';
                    this.celular_rec = '';
                    this.telefono_rec = '';

                    /////////////// PASO 2 ////////////////
                    this.rfc = '';
                    this.nss = '';
                    this.sexo = '';
                    this.f_nacimiento = '';
                    this.edo_civil = '';
                    this.hijos = '';
                    this.num_hijos = 0;
                    this.empresa = '';
                    this.ingresos = 0;
                    this.pago_mensual = 0;
                    this.enganche = 0;

                    ////////////// Paso 3 /////////////////
                    this.mascotas = 0;
                    this.tam_mascota = 0;
                    this.num_mascotas = 0;
                    this.tipo_credito = '';
                    this.coacreditado = 0;
                    this.num_autos = 0;
                    this.autos = 0;
                    this.amenidad_priv = '';
                    this.detalle_casa = '';
                    this.perfil_cliente = '';
                    break;
                }
            }
            

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
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },

    
        back(){
            this.editar = 0;
        }
    },
    mounted() {
        this.listarLeads(1);
        this.selectCampania(1);
        this.selectFraccionamientos();
        this.selectAsesores();
        this.getClavesLadas();
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
    .modal-body{
        height: 500px;
        width: 100%;
        overflow-y: auto;
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
    
    .bg-gradient-primary {
        background: #00ADEF!important;
        background: linear-gradient(45deg,#321fdb 0%,#00ADEF 100%)!important;
        border-color: #00ADEF!important;
    }
    .p-3 {
        padding: 1rem!important;
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

