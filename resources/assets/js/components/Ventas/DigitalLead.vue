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
                    <Button :btnClass="'btn-success'" :icon="'icon-people'" @click="abrirModal('nuevo')" v-if="rolId != 2 && rolId != 12 && rolId != 3">
                        Nuevo
                    </Button>
                    <Button v-if="b_motivo == 1" :btnClass="'btn-scarlet'" @click="abrirModal('inventario')">Inventario</Button>
                    <!-- <button v-if="rolId == 1" type="button" class="btn btn-dark" @click="sms()">
                        PRUEBA SMS
                    </button> -->
                    &nbsp;
                </div>
                <LoadingComponent v-if="loading"></LoadingComponent>
                <div class="card-body" v-else>
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
                        <div class="col-md-10">
                            <div class="input-group">
                                <input type="text" readonly placeholder="Nombre" class="form-control col-sm-3">
                                <input type="text"
                                    v-model="b_cliente" @keyup.enter="listarLeads(1)" placeholder="Nombre" class="form-control col-sm-6">
                                <input type="text"
                                    v-model="b_apellidos" @keyup.enter="listarLeads(1)" placeholder="Apellidos" class="form-control col-sm-6">
                            </div>
                        </div>
                        <!-- Buscador por nombre de usuario -->
                        <div class="col-md-8" v-if="rolId != 2">
                            <div class="input-group">
                                <input type="text" readonly placeholder="Usuario" class="form-control col-sm-3">
                                <input type="text"
                                    v-model="b_user_name" @keyup.enter="listarLeads(1)" placeholder="Nombre de Usuario" class="form-control col-sm-6">
                                <input type="text"
                                    v-model="b_user_lastname" @keyup.enter="listarLeads(1)" placeholder="Nombre de Usuario" class="form-control col-sm-6">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" v-if="b_motivo == 3" v-model="b_modelo" @keyup.enter="listarLeads(1)" placeholder="Modelo" class="form-control col-sm-6">
                                <select v-if="b_motivo != 1" class="form-control col-sm-4" v-model="b_status">
                                    <option value="">Todos</option>
                                    <option value="1">Pendientes</option>
                                    <option value="3">Finalizados</option>
                                </select>
                                <!-- Busacador por campaña -->
                                <template v-if="rolId != 2">
                                    <input type="text" v-if="b_motivo == 1" v-model="b_campania" @keyup.enter="listarLeads(1)" placeholder="Campaña publicitaria" class="form-control col-sm-6">
                                    <input type="text" v-if="b_motivo == 1" v-model="b_contacto" @keyup.enter="listarLeads(1)" placeholder="Medio de contacto" class="form-control col-sm-6">
                                </template>
                            </div>
                        </div>
                        <div class="col-md-8" v-if="b_motivo == 1">
                            <div class="input-group" >
                                <select class="form-control" v-model="b_asesor" v-if="rolId != 2">
                                    <option value="">Vendedor asignado</option>
                                    <option v-for="asesor in arrayAsesores" :key="asesor.id" :value="asesor.id" v-text="asesor.nombre + ' '+ asesor.apellidos"></option>
                                </select>
                                <!--Criterios para el listado de busqueda -->
                                <select class="form-control col-sm-4" v-model="b_status">
                                    <option value="">Status</option>
                                    <option value="1">En Seguimiento</option>
                                    <option value="0">Descartado</option>
                                    <option value="2">Potencial</option>
                                    <option value="3">Enviado a prospectos</option>
                                    <option value="4">Venta</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8" v-if="b_motivo ==1 || b_motivo == 2">
                            <div class="input-group">
                                <input type="text" readonly placeholder="Fecha de alta:" class="form-control col-sm-3">
                                <input type="date" v-model="b_fecha1" @keyup.enter="listarLeads(1)" class="form-control col-sm-6">
                                <input type="date" v-model="b_fecha2" @keyup.enter="listarLeads(1)" class="form-control col-sm-6">
                            </div>
                        </div>
                        <div class="col-md-8" v-if="b_motivo ==1 && rolId != 2">
                            <div class="input-group">
                                <input type="text" readonly placeholder="Fecha de Canalizacion:" class="form-control col-sm-3">
                                <input type="date" v-model="b_fasign1" @keyup.enter="listarLeads(1)" class="form-control col-sm-6">
                                <input type="date" v-model="b_fasign2" @keyup.enter="listarLeads(1)" class="form-control col-sm-6">
                            </div>
                        </div>
                        <div class="col-md-6" v-if="b_motivo ==4">
                            <div class="input-group">
                                <input type="text" readonly placeholder="Prioridad:" class="form-control col-sm-3">
                                <select class="form-control"  v-model="b_prioridad" >
                                    <option value="">Todos</option>
                                    <option value="Baja">Baja</option>
                                    <option value="Media">Media</option>
                                    <option value="Alta">Alta</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6" v-if="b_motivo == 1">
                            <div class="input-group">
                                <input type="text" readonly placeholder="Semaforo Atención:" class="form-control col-sm-3">
                                <select class="form-control"  v-model="b_semaforo" v-if="rolId==2" >
                                    <option value="">Todos</option>
                                    <option value="1">Al corriente</option>
                                    <option value="2">7-15 dias</option>
                                    <option value="3">+16 dias</option>
                                </select>
                                <select class="form-control"  v-model="b_semaforo_recepcion"
                                v-if="userId == 25511 //Adrian
                                    || userId == 28669 //Ashly
                                    || userId == 28271 //Alejandro Ort
                                    || userId == 28128 //Ale Escobar
                                    || userId == 33095 //Dany muñoz
                                    || rolId == 1" >
                                    <option value="">Todos</option>
                                    <option value="1">Al corriente</option>
                                    <option value="2">7-15 dias</option>
                                    <option value="3">+16 dias</option>
                                </select>
                                <select class="form-control"  v-model="b_semaforo_gerente"
                                v-if="userId == 11 //Yas
                                    || userId == 29692 //Alex Torres
                                    || userId == 13" >
                                    <option value="">Todos</option>
                                    <option value="1">Al corriente</option>
                                    <option value="2">7-15 dias</option>
                                    <option value="3">+16 dias</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8" v-if="b_motivo ==1">
                            <div class="input-group">
                                <input type="text" readonly placeholder="Proyecto de interes:" class="form-control col-sm-3">
                                <select class="form-control" v-model="b_proyecto">
                                    <option value="">Seleccione</option>
                                    <option v-for="proyecto in $root.$data.proyectos" :key="proyecto.id"
                                        :value="proyecto.id" v-text="proyecto.nombre">
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-10" v-if="userId == 25511 //Adrian
                                    || userId == 28669 //Ashly
                                    || userId == 28271 //Alejandro Ort
                                    || userId == 28128 //Ale Escobar
                                    || userId == 33095 || rolId == 1 || userId == 10">
                            <div class="input-group" v-if="b_motivo == 1">
                                <input type="text" readonly placeholder="Leads hibernando:" class="form-control col-sm-3">
                                <select class="form-control  col-sm-4" v-model="b_hibernar">
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                                <input type="text" readonly placeholder="Pendiente de envio de cupon:" class="form-control col-sm-3">
                                <select class="form-control" v-model="b_cupon">
                                    <option value="3">Todos</option>
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-10" v-if="userId == 25511 //Adrian
                                    || userId == 28669 //Ashly
                                    || userId == 28271 //Alejandro Ort
                                    || userId == 28128 //Ale Escobar
                                    || userId == 33095 || rolId == 1 || userId == 10">
                            <div class="input-group" v-if="b_motivo == 1">
                                <input type="text" readonly placeholder="Leads auditados:" class="form-control col-sm-3">
                                <select class="form-control  col-sm-4" v-model="b_auditado">
                                    <option value="">Todos</option>
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="input-group">
                                <Button :icon="'fa fa-search'" @click="listarLeads(1)">Buscar</Button>
                                <a v-if="b_motivo == 1" class="btn btn-success" v-bind:href="'/campanias/excelLeads'+
                                        '?buscar='+ b_cliente+'&campania='+ b_campania+
                                        '&status='+ b_status+'&asesor='+ b_asesor+
                                        '&motivo='+ b_motivo+'&fecha1='+ b_fecha1+
										'&hibernar=' + b_hibernar +
                                        '&fecha2='+ b_fecha2+'&proyecto='+ b_proyecto">
                                    <i class="fa fa-file-text"></i>&nbsp; Excel
                                </a>
                                <a v-if="(userId == 25511 //Adrian
                                    || userId == 28669 //Ashly
                                    || userId == 28271 //Alejandro Ort
                                    || userId == 28128 //Ale Escobar
                                    || userId == 33095 || userId == 10) && b_motivo == 1" class="btn btn-primary" v-bind:href="'/campanias/excelToImport'+
                                        '?buscar='+ b_cliente+'&campania='+ b_campania+
                                        '&status='+ b_status+'&asesor='+ b_asesor+
                                        '&motivo='+ b_motivo+'&fecha1='+ b_fecha1+
                                        '&f_asign1='+b_fasign1      + '&f_asign2='+b_fasign2 +
										'&hibernar=' + b_hibernar + '&b_auditado=' + b_auditado +
                                        '&fecha2='+ b_fecha2+'&proyecto='+ b_proyecto">
                                    <i class="fa fa-file-text"></i>&nbsp; Excel para Audiencia
                                </a>
                                <button disabled class="btn btn-primary">
                                    {{'Total: '+arrayLeads.total}}
                                </button>

                            </div>
                        </div>
                    </div>
                    <br>
                    <TableComponent v-if="b_motivo == 1"
                        :cabecera="[
                            'Obs','','Avance', ' ','Nombre','Celular','Correo','Campaña','Proyecto o zona de interés ',
                            'Presupuesto','Modelo recomendado','Estatus','Vendedor asignado',
                            'Fecha de alta', '   ', '     ',
                        ]"
                    >
                        <template v-slot:tbody>
                            <tr v-for="lead in arrayLeads.data" :key="lead.id">
                                <td class="td2">
                                    <Button :btnClass="'btn-info'" title="Ver Observaciones" :icon="'icon-eye'" @click="abrirModal1(lead.id,lead.motivo)"></Button>
                                </td>
                                <td class="td2" style="width:10%">
                                    <Button title="Editar" :btnClass="'btn-warning'" :size="'btn-sm'" :icon="'icon-pencil'"
                                        @click="abrirModal('actualizar',lead)">
                                    </Button>
                                    <Button title="Asignar vendedor aleatorio" :size="'btn-sm'" :icon="'fa fa-exchange'" v-if="lead.vendedor_asign == null"
                                        @click="asignarVendedor(lead.id)"
                                    ></Button>
                                    <Button :btnClass="'btn-danger'" :icon="'icon-trash'" :size="'btn-sm'"
                                        v-if="userId == 25511 //Adrian
                                    || userId == 28669 //Ashly
                                    || userId == 28271 //Alejandro Ort
                                    || userId == 28128 //Ale Escobar
                                    || userId == 33095 || rolId == 1"
                                        title="Eliminar"  @click="eliminar(lead.id)">
                                    </Button>
                                </td>
                                <td>
                                    <div class="clearfix">
                                        <div class="float-left"><strong>{{lead.progress}}%</strong></div>
                                    </div>
                                    <div class="progress progress-xs">
                                        <div class="progress-bar bg-success" role="progressbar" v-bind:style="{ width: lead.progress + '%' }" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </td>
                                <td class="td2">
                                        <template v-if="lead.premio.length == 0">
                                            <Button v-if="userId == 25511 //Adrian
                                                    || userId == 28669 //Ashly
                                                    || userId == 28271 //Alejandro Ort
                                                    || userId == 28128 //Ale Escobar
                                                    || userId == 33095 || rolId == 1"
                                                :btnClass="'btn-success'" :icon="'fa fa-gift'" @click="premioPaste(`https://siicumbres.com/ruleta?gc=${lead.id}&d=${lead.name_user}`)"></Button>
                                        </template>
                                        <template v-if="lead.premio.length > 0">
                                            <a v-if="lead.premio[0].premio > 0" target="_blank" class="btn btn-scarlet" title="Descargar cupón" v-bind:href="'/premios/cuponPDF'+
                                                    '?gc='+ lead.id+'&d='+ lead.name_user">
                                                <i class="fa fa-gift"></i>&nbsp;
                                            </a>

                                            <label></label>

                                            <Button v-if="lead.envio_cupon == null && (userId == 25511 //Adrian
                                                || userId == 28669 //Ashly
                                                || userId == 28271 //Alejandro Ort
                                                || userId == 28128 //Ale Escobar
                                                || userId == 33095 || rolId == 1)" :btnClass="'btn-warning'"
                                                :icon="'icon-check'" title="Indicar envio de cupón" @click="setCuponEnviado(lead.id)">
                                            </Button>
                                        </template>
                                </td>
                                <td class="td2">
                                    <span v-if="lead.diferencia < 7 || lead.status == 0 || lead.status == 3 || lead.status == 4" >{{ lead.nombre + ' ' + lead.apellidos }}</span>
                                    <span v-else-if="lead.diferencia >= 7 && lead.diferencia <= 15" class="badge2 badge-warning">{{ lead.nombre.toUpperCase()+' '+lead.apellidos}}</span>
                                    <span v-else-if="lead.diferencia > 15" class="badge2 badge-danger">{{ lead.nombre.toUpperCase()+' '+lead.apellidos}}</span>
                                </td>
                                <td class="td2">
                                    <a v-if="lead.celular != null" title="Enviar whatsapp"
                                        class="btn btn-success" target="_blank" :href="'https://api.whatsapp.com/send?phone='+lead.clv_lada+lead.celular+'&text='">
                                        <i class="fa fa-whatsapp fa-lg"></i>
                                    </a>
                                </td>
                                <td class="td2">
                                    <a v-if="lead.email != null" title="Enviar correo"
                                        class="btn btn-secondary" :href="'mailto:'+lead.email+ ';'">
                                        <i class="fa fa-envelope-o fa-lg"></i>
                                    </a>
                                </td>
                                <td class="td2">
                                    {{ (lead.nombre_campania != null) ? `${lead.nombre_campania} - ${lead.medio_digital}` : 'Tráfico organico' }}
                                </td>
                                <td class="td2">
                                    {{(lead.proyecto_interes != 0) ? lead.proyecto : lead.zona_interes}}
                                </td>
                                <td class="td2">
                                    {{ (lead.rango1 != null) ? `$${$root.formatNumber(lead.rango1)} - $${$root.formatNumber(lead.rango2)}` : '' }}
                                </td>
                                <td class="td2" v-text="lead.modelo_interes"></td>
                                <td class="td2">
                                    <span v-if="lead.status == '1'" class="badge badge-warning">En Seguimiento</span>
                                    <span v-if="lead.status == '0'" class="badge badge-danger">Descartado</span>
                                    <span v-if="lead.status == '2'" class="badge badge-success">Potencial</span>
                                    <span v-if="lead.status == '3'" class="badge badge-success">Enviado a prospectos</span>
                                    <span v-if="lead.status == '4'" class="badge badge-success">Venta</span>
                                </td>
                                <td class="td2" v-text="lead.vendedor"></td>
                                <td class="td2" v-text="this.moment(lead.created_at).locale('es').format('DD/MMM/YYYY')"></td>

                                <td class="td2" v-if="userId == 25511 //Adrian
                                    || userId == 28669 //Ashly
                                    || userId == 28271 //Alejandro Ort
                                    || userId == 28128 //Ale Escobar
                                    || userId == 33095 || rolId == 1">
                                    <template v-if="lead.ini_dormir == null">
                                        <Button :btnClass="'btn-dark'" title="Hibernar lead" :icon="'fa fa-power-off'" @click="abrirModal('hibernar', lead)"></Button>
                                    </template>
                                </td>
                                <td class="td2" v-if="userId == 25511 //Adrian
                                    || userId == 28271 //Alejandro Ort
                                    || rolId == 1">
                                    <Button v-if="lead.f_audit == null" :btnClass="'btn-danger'" title="Auditar" :icon="'fa fa-user-secret'" @click="abrirModal('auditar', lead)"></Button>
                                    <label v-else>Auditado el: {{this.moment(lead.f_audit).locale('es').format('DD/MMM/YYYY')}}</label>
                                </td>
                            </tr>
                        </template>
                    </TableComponent>
                    <TableComponent v-if="b_motivo == 2"
                        :cabecera="[
                            '','Nombre','Celular','Correo','Dirección','Descripción del problema','Status','Fecha de alta',
                            'Observaciones'
                        ]"
                    >
                        <template v-slot:tbody>
                            <tr v-for="lead in arrayLeads.data" :key="lead.id">
                                <td class="td2" style="width:10%">
                                    <Button title="Editar" :btnClass="'btn-warning'" :size="'btn-sm'" :icon="'icon-pencil'"
                                        @click="abrirModal('actualizar',lead)">
                                    </Button>
                                    <Button :btnClass="'btn-success'" :size="'btn-sm'" :icon="'icon-check'" v-if="lead.status == 1" title="Finalizar"
                                        @click="changeStatus(lead.id)"
                                    ></Button>
                                    <Button :btnClass="'btn-danger'" :icon="'icon-trash'" :size="'btn-sm'" v-if="userId == 25511 || userId == 28669 || rolId == 1 || userId == 28270"
                                        title="Eliminar"  @click="eliminar(lead.id)">
                                    </Button>
                                </td>
                                <td class="td2">
                                    <span v-if="lead.diferencia < 7" v-text="lead.nombre + ' ' + lead.apellidos"></span>
                                    <span v-else-if="lead.diferencia >= 7 && lead.diferencia <= 15" class="badge2 badge-warning">
                                        {{ lead.nombre.toUpperCase()+' '+lead.apellidos.toUpperCase()}}
                                    </span>
                                    <span class="badge2 badge-danger" v-else-if="lead.diferencia > 15">
                                        {{ lead.nombre.toUpperCase()+' '+lead.apellidos.toUpperCase()}}
                                    </span>
                                </td>
                                <td class="td2">
                                    <a v-if="lead.celular != null" title="Enviar whatsapp" class="btn btn-success"
                                        target="_blank" :href="'https://api.whatsapp.com/send?phone=+'+lead.clv_lada+lead.celular+'&text='">
                                        <i class="fa fa-whatsapp fa-lg"></i>
                                    </a>
                                </td>
                                <td class="td2">
                                    <a v-if="lead.email != null" title="Enviar correo" class="btn btn-secondary"
                                        :href="'mailto:'+lead.email+ ';'"> <i class="fa fa-envelope-o fa-lg"></i>
                                    </a>
                                </td>
                                <td v-text="lead.direccion"></td>
                                <td v-text="lead.descripcion"></td>
                                <td class="td2">
                                    <span  v-if="lead.status == '1'" class="badge badge-warning">En Seguimiento</span>
                                    <span v-if="lead.status == '0'" class="badge badge-danger">Descartado</span>
                                    <span v-if="lead.status == '3'" class="badge badge-success">Finalizado</span>
                                </td>
                                <td class="td2" v-text="this.moment(lead.created_at).locale('es').format('DD/MMM/YYYY')"></td>
                                <td class="td2">
                                    <Button :btnClass="'btn-info'" title="Ver Observaciones" :icon="'icon-eye'" @click="abrirModal1(lead.id,lead.motivo)"></Button>
                                </td>
                            </tr>
                        </template>
                    </TableComponent>
                    <TableComponent v-if="b_motivo == 3"
                        :cabecera="['','Nombre','Celular','Correo','Proyecto o zona de interés ','Modelo de interes',
                            'Mensualidad deseada','Status','Fecha de alta','Observaciones'
                        ]"
                    >
                        <template v-slot:tbody>
                            <tr v-for="lead in arrayLeads.data" :key="lead.id">
                                <td class="td2" style="width:10%">
                                    <Button title="Editar" :btnClass="'btn-warning'" :size="'btn-sm'" :icon="'icon-pencil'"
                                        @click="abrirModal('actualizar',lead)">
                                    </Button>
                                    <Button title="Finalizar" :size="'btn-sm'" :icon="'icon-check'" v-if="lead.status == 1"
                                        @click="changeStatus(lead.id)"
                                    ></Button>
                                    <Button :btnClass="'btn-danger'" :icon="'icon-trash'" :size="'btn-sm'" v-if="userId == 25511 || userId == 28669 || rolId == 1 || userId == 28270"
                                        title="Eliminar"  @click="eliminar(lead.id)">
                                    </Button>
                                </td>
                                <td>
                                    <span v-if="lead.diferencia < 7" class="td2" v-text="lead.nombre + ' ' + lead.apellidos"></span>
                                    <span class="badge2 badge-warning" v-else-if="lead.diferencia >= 7 && lead.diferencia <= 15">
                                        {{ lead.nombre.toUpperCase()+' '+lead.apellidos.toUpperCase()}}
                                    </span>
                                    <span class="badge2 badge-danger" v-else-if="lead.diferencia > 15">
                                        {{ lead.nombre.toUpperCase()+' '+lead.apellidos.toUpperCase()}}
                                    </span>
                                </td>
                                <td class="td2">
                                    <a v-if="lead.celular != null" title="Enviar whatsapp" class="btn btn-success" target="_blank"
                                        :href="'https://api.whatsapp.com/send?phone=+'+lead.clv_lada+lead.celular+'&text='">
                                        <i class="fa fa-whatsapp fa-lg"></i>
                                    </a>
                                </td>
                                <td class="td2">
                                    <a  v-if="lead.email != null" title="Enviar correo" class="btn btn-secondary"
                                        :href="'mailto:'+lead.email+ ';'"> <i class="fa fa-envelope-o fa-lg"></i>
                                    </a>
                                </td>
                                <td class="td2" v-if="lead.proyecto_interes != 0" v-text="lead.proyecto"></td>
                                <td class="td2" v-else v-text="lead.zona_interes"></td>
                                <td class="td2" v-text="lead.modelo_interes"></td>
                                <td class="td2">
                                    {{(lead.rango1 != null) ? '$'+$root.formatNumber(lead.rango1) + ' - $'+$root.formatNumber(lead.rango2) : ''}}
                                </td>
                                <td class="td2">
                                    <span v-if="lead.status == '1'" class="badge badge-warning">En Seguimiento</span>
                                    <span v-if="lead.status == '0'" class="badge badge-danger">Descartado</span>
                                    <span v-if="lead.status == '3'" class="badge badge-success">Finalizado</span>
                                </td>
                                <td class="td2" v-text="this.moment(lead.created_at).locale('es').format('DD/MMM/YYYY')"></td>
                                <td class="td2">
                                    <Button :btnClass="'btn-info'" title="Ver Observaciones" :icon="'icon-eye'" @click="abrirModal1(lead.id,lead.motivo)"></Button>
                                </td>
                            </tr>
                        </template>
                    </TableComponent>
                    <TableComponent v-if="b_motivo == 4"
                        :cabecera="[
                            '','Nombre','Celular','Correo','Prioridad','Descripción del problema','Status',
                            'Fecha de alta','Observaciones'
                        ]"
                    >
                        <template v-slot:tbody>
                            <tr v-for="lead in arrayLeads.data" :key="lead.id">
                                <td class="td2" style="width:10%">
                                    <Button title="Editar" :btnClass="'btn-warning'" :size="'btn-sm'" :icon="'icon-pencil'"
                                        @click="abrirModal('actualizar',lead)">
                                    </Button>
                                    <Button title="Finalizar" :size="'btn-sm'" :icon="'icon-check'" v-if="lead.status == 1"
                                        @click="changeStatus(lead.id)"
                                    ></Button>
                                    <Button :btnClass="'btn-danger'" :icon="'icon-trash'" :size="'btn-sm'" v-if="userId == 25511 || userId == 28669 || rolId == 1 || userId == 28270"
                                        title="Eliminar"  @click="eliminar(lead.id)">
                                    </Button>
                                </td>
                                <td class="td2">
                                    <span v-if="lead.diferencia < 7" v-text="lead.nombre + ' ' + lead.apellidos"></span>
                                    <span v-else-if="lead.diferencia >= 7 && lead.diferencia <= 15" class="badge2 badge-warning">
                                        {{ lead.nombre.toUpperCase()+' '+lead.apellidos.toUpperCase()}}
                                    </span>
                                    <span v-else-if="lead.diferencia > 15" class="badge2 badge-danger">
                                        {{ lead.nombre.toUpperCase()+' '+lead.apellidos.toUpperCase()}}
                                    </span>
                                </td>
                                <td class="td2">
                                    <a v-if="lead.celular != null" title="Enviar whatsapp" class="btn btn-success" target="_blank"
                                        :href="'https://api.whatsapp.com/send?phone=+'+lead.clv_lada+lead.celular+'&text='">
                                        <i class="fa fa-whatsapp fa-lg"></i>
                                    </a>
                                </td>
                                <td class="td2">
                                    <a  v-if="lead.email != null" title="Enviar correo" class="btn btn-secondary"
                                        :href="'mailto:'+lead.email+ ';'"> <i class="fa fa-envelope-o fa-lg"></i>
                                    </a>
                                </td>
                                <td>
                                    <span v-if="lead.prioridad == 'Baja'" class="badge badge-light">Baja</span>
                                    <span v-if="lead.prioridad == 'Media'" class="badge badge-warning">Media</span>
                                    <span v-if="lead.prioridad == 'Alta'" class="badge badge-danger">Alta</span>
                                </td>
                                <td v-text="lead.descripcion"></td>
                                <td class="td2">
                                    <span v-if="lead.status == '1'" class="badge badge-warning">En Seguimiento</span>
                                    <span v-if="lead.status == '0'" class="badge badge-danger">Descartado</span>
                                    <span v-if="lead.status == '3'" class="badge badge-success">Finalizado</span>
                                </td>
                                <td class="td2" v-text="this.moment(lead.created_at).locale('es').format('DD/MMM/YYYY')"></td>
                                <td class="td2">
                                    <Button :btnClass="'btn-info'" title="Ver Observaciones" :icon="'icon-eye'" @click="abrirModal1(lead.id,lead.motivo)"></Button>
                                </td>
                            </tr>
                        </template>
                    </TableComponent>
                    <TableComponent v-if="b_motivo == 5"
                        :cabecera="[
                            '','Nombre','Celular','Correo','Dirección','Descripción','Fecha de alta','Observaciones'
                        ]"
                    >
                        <template v-slot:tbody>
                            <tr v-for="lead in arrayLeads.data" :key="lead.id">
                                <td class="td2" style="width:10%">
                                    <Button title="Editar" :btnClass="'btn-warning'" :size="'btn-sm'" :icon="'icon-pencil'"
                                        @click="abrirModal('actualizar',lead)">
                                    </Button>
                                    <Button :btnClass="'btn-danger'" :icon="'icon-trash'" :size="'btn-sm'" v-if="userId == 25511 || userId == 28669 || rolId == 1 || userId == 28270"
                                        title="Eliminar"  @click="eliminar(lead.id)">
                                    </Button>
                                    <button title="Editar" type="button" @click="abrirModal('actualizar',lead)" class="btn btn-warning btn-sm">
                                        <i class="icon-pencil"></i>
                                    </button>
                                    <button title="Eliminar" type="button" @click="eliminar(lead.id)" class="btn btn-danger btn-sm">
                                        <i class="icon-close"></i>
                                    </button>
                                </td>
                                <td class="td2">
                                    <span v-if="lead.diferencia < 7" v-text="lead.nombre + ' ' + lead.apellidos"></span>
                                    <span v-else-if="lead.diferencia >= 7 && lead.diferencia <= 15" class="badge2 badge-warning">
                                        {{ lead.nombre.toUpperCase()+' '+lead.apellidos.toUpperCase()}}
                                    </span>
                                    <span v-else-if="lead.diferencia > 15" class="badge2 badge-danger">
                                        {{ lead.nombre.toUpperCase()+' '+lead.apellidos.toUpperCase()}}
                                    </span>
                                </td>
                                <td class="td2">
                                    <a v-if="lead.celular != null" title="Enviar whatsapp" class="btn btn-success" target="_blank"
                                        :href="'https://api.whatsapp.com/send?phone=+'+lead.clv_lada+lead.celular+'&text='">
                                        <i class="fa fa-whatsapp fa-lg"></i>
                                    </a>
                                </td>
                                <td class="td2">
                                    <a v-if="lead.email != null" title="Enviar correo" class="btn btn-secondary"
                                        :href="'mailto:'+lead.email+ ';'">
                                        <i class="fa fa-envelope-o fa-lg"></i>
                                    </a>
                                </td>
                                <td v-text="lead.direccion"></td>
                                <td v-text="lead.descripcion"></td>
                                <td class="td2" v-text="this.moment(lead.created_at).locale('es').format('DD/MMM/YYYY')"></td>
                                <td class="td2">
                                    <Button :btnClass="'btn-info'" title="Ver Observaciones" :icon="'icon-eye'" @click="abrirModal1(lead.id,lead.motivo)"></Button>
                                </td>
                            </tr>
                        </template>
                    </TableComponent>
                    <TableComponent v-if="b_motivo == 6"
                        :cabecera="[
                            '','Nombre','Celular','Correo','Dirección del terreno','Costo m&sup2;',
                            'Medidas','Fecha de alta','Notas','Observaciones',
                        ]"
                    >
                        <template v-slot:tbody>
                            <tr v-for="lead in arrayLeads.data" :key="lead.id">
                                <td class="td2" style="width:10%">
                                    <Button title="Editar" :btnClass="'btn-warning'" :size="'btn-sm'" :icon="'icon-pencil'"
                                        @click="abrirModal('actualizar',lead)">
                                    </Button>
                                    <Button title="Finalizar" :size="'btn-sm'" :icon="'icon-check'" v-if="lead.status == 1"
                                        @click="changeStatus(lead.id)"
                                    ></Button>
                                    <Button :btnClass="'btn-danger'" :icon="'icon-trash'" :size="'btn-sm'" v-if="userId == 25511 || userId == 28669 || rolId == 1 || userId == 28270"
                                        title="Eliminar"  @click="eliminar(lead.id)">
                                    </Button>
                                </td>
                                <td class="td2">
                                    <span v-if="lead.diferencia < 7" v-text="lead.nombre + ' ' + lead.apellidos"></span>
                                    <span v-else-if="lead.diferencia >= 7 && lead.diferencia <= 15" class="badge2 badge-warning">
                                        {{ lead.nombre.toUpperCase()+' '+lead.apellidos.toUpperCase()}}
                                    </span>
                                    <span v-else-if="lead.diferencia > 15" class="badge2 badge-danger">
                                        {{ lead.nombre.toUpperCase()+' '+lead.apellidos.toUpperCase()}}
                                    </span>
                                </td>
                                <td class="td2">
                                    <a v-if="lead.celular != null" title="Enviar whatsapp" class="btn btn-success"
                                        target="_blank" :href="'https://api.whatsapp.com/send?phone=+'+lead.clv_lada+lead.celular+'&text='">
                                        <i class="fa fa-whatsapp fa-lg"></i>
                                    </a>
                                </td>
                                <td class="td2">
                                    <a v-if="lead.email != null" title="Enviar correo" class="btn btn-secondary"
                                        :href="'mailto:'+lead.email+ ';'"> <i class="fa fa-envelope-o fa-lg"></i>
                                    </a>
                                </td>
                                <td v-text="lead.direccion"></td>
                                <td>${{$root.formatNumber(lead.rango1)}}</td>
                                <td>{{$root.formatNumber(lead.rango2)}}m&sup2;</td>
                                <td class="td2" v-text="this.moment(lead.created_at).locale('es').format('DD/MMM/YYYY')"></td>
                                <td v-text="lead.descripcion"></td>
                                <td class="td2">
                                    <Button :btnClass="'btn-info'" title="Ver Observaciones" :icon="'icon-eye'" @click="abrirModal1(lead.id,lead.motivo)"></Button>
                                </td>
                            </tr>
                        </template>
                    </TableComponent>
                    <TableComponent v-if="b_motivo == 7"
                        :cabecera="[
                            '','Nombre','Celular','Correo','Descripción','Status','Fecha de alta','Observaciones',
                        ]"
                    >
                        <template v-slot:tbody>
                            <tr v-for="lead in arrayLeads.data" :key="lead.id">
                                <td class="td2" style="width:10%">
                                    <Button title="Editar" :btnClass="'btn-warning'" :size="'btn-sm'" :icon="'icon-pencil'"
                                        @click="abrirModal('actualizar',lead)">
                                    </Button>
                                    <Button title="Finalizar" :size="'btn-sm'" :icon="'icon-check'" v-if="lead.status == 1"
                                        @click="changeStatus(lead.id)"
                                    ></Button>
                                    <Button :btnClass="'btn-danger'" :icon="'icon-trash'" :size="'btn-sm'" v-if="userId == 25511 || userId == 28669 || rolId == 1 || userId == 28270"
                                        title="Eliminar"  @click="eliminar(lead.id)">
                                    </Button>
                                </td>
                                <td class="td2">
                                    <span v-if="lead.diferencia < 7" v-text="lead.nombre + ' ' + lead.apellidos"></span>
                                    <span v-else-if="lead.diferencia >= 7 && lead.diferencia <= 15" class="badge2 badge-warning">
                                        {{ lead.nombre.toUpperCase()+' '+lead.apellidos.toUpperCase()}}
                                    </span>
                                    <span v-else-if="lead.diferencia > 15" class="badge2 badge-danger">
                                        {{ lead.nombre.toUpperCase()+' '+lead.apellidos.toUpperCase()}}
                                    </span>
                                </td>
                                <td class="td2">
                                    <a v-if="lead.celular != null" title="Enviar whatsapp" class="btn btn-success" target="_blank"
                                        :href="'https://api.whatsapp.com/send?phone=+'+lead.clv_lada+lead.celular+'&text='">
                                        <i class="fa fa-whatsapp fa-lg"></i>
                                    </a>
                                </td>
                                <td class="td2">
                                    <a v-if="lead.email != null" title="Enviar correo" class="btn btn-secondary"
                                        :href="'mailto:'+lead.email+ ';'"> <i class="fa fa-envelope-o fa-lg"></i>
                                    </a>
                                </td>
                                <td v-text="lead.descripcion"></td>
                                <td class="td2">
                                    <span v-if="lead.status == '1'" class="badge badge-warning">En Seguimiento</span>
                                    <span v-if="lead.status == '0'" class="badge badge-danger">Descartado</span>
                                    <span v-if="lead.status == '3'" class="badge badge-success">Finalizado</span>
                                </td>
                                <td class="td2" v-text="this.moment(lead.created_at).locale('es').format('DD/MMM/YYYY')"></td>
                                <td class="td2">
                                    <Button :btnClass="'btn-info'" title="Ver Observaciones" :icon="'icon-eye'" @click="abrirModal1(lead.id,lead.motivo)"></Button>
                                </td>
                            </tr>
                        </template>
                    </TableComponent>
                    <Nav :current="arrayLeads.current_page ? arrayLeads.current_page : 1"
                        :last="arrayLeads.last_page ? arrayLeads.last_page : 1"
                        @changePage="listarLeads">
                    </Nav>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>

        <!--Inicio del modal -->
        <ModalComponent v-if="modal==1"
            :titulo="tituloModal"
            @closeModal="cerrarModal()"
        >
            <template v-slot:body>
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

                        <div class="form-group row" v-if="rolId != 2 && tipoAccion == 2">
                            <label class="col-md-2 form-control-label" for="text-input"><strong>Usuario:</strong><span style="color:red;" v-show="nombre==''">(*)</span></label>
                            <div class="col-md-4">
                                <input type="text" disabled v-model="name_user" class="form-control" placeholder="Nombre">
                            </div>
                            <div class="col-md-4">
                                <input type="text" disabled v-model="last_name_user" class="form-control" placeholder="Apellidos">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 form-control-label" for="text-input">Campaña publicitaria</label>
                            <div class="col-md-8">
                                <v-select
                                        :on-search="campaniaSelect"
                                        label="campania_comp"
                                        :options="arrayCampanias"
                                        :value="campania"
                                        placeholder="Campaña"
                                        :onChange="getCampania"
                                    >
                                    </v-select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 form-control-label" for="text-input">Medio de contacto</label>
                            <div class="col-md-4">
                                <input type="text" name="city" list="cityname" class="form-control" v-model="medio_contacto" placeholder="Medio de publicidad">
                                <datalist id="cityname">
                                    <option value="">Seleccione</option>
                                    <option value="Facebook Cumbres">Facebook Cumbres</option>
                                    <option value="Facebook Sirenia">Facebook Sirenia</option>
                                    <option value="Facebook Catara">Facebook Catara</option>
                                    <option value="Facebook Valle Real">Facebook Valle Real</option>
                                    <option value="Facebook Andaluz">Facebook Andaluz</option>
                                    <option value="Facebook Imperia">Facebook Imperia</option>
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
                                    <option v-for="proyecto in $root.$data.proyectos" :key="proyecto.id"
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
                                <p><strong>${{ $root.formatNumber(rango1)}}</strong></p>
                            </div>
                            <div class="col-md-3">
                                <input class="form-control" type="text" v-model="rango1" placeholder="Minimo">
                                <input class="form-control" type="range" name="price-min" id="price-min" v-model="rango1" min="300000" max="2500000">
                            </div>
                            <div class="col-md-2">
                                <p><strong>${{ $root.formatNumber(rango2)}}</strong></p>
                            </div>
                            <div class="col-md-3">
                                <input class="form-control" type="text" v-model="rango2" placeholder="Maximo">
                                <input class="form-control" type="range" name="price-max" id="price-max" v-model="rango2" min="300000" max="2500000">
                            </div>
                        </div>

                        <div class="form-group row line-separator"></div>

                        <div v-if="vendedor_asign != 0 && vendedor_asign != null" class="col-md-12">
                            <h6 v-if="vendedor_asign != 0 && vendedor_asign != null" align="center">Vendedor asignado: <strong> {{vendedor}} </strong></h6>

                            <select class="form-control"
                                v-if="userId == 25511 //Adrian
                                    || userId == 28669 //Ashly
                                    || userId == 11 //Yas
                                    || userId == 28271 //Alejandro Ort
                                    || userId == 28128 //Ale Escobar
                                    || userId == 29692 //Alex Torres
                                    || userId == 13 //Quique
                                    || userId == 33095 //Dany muñoz
                                    || rolId == 1"  v-model="vendedor_asign" >
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
                                    <option v-for="clave in $root.$data.arrayClaves" :key="clave.clave+clave.pais" :value="clave.clave" v-text="clave.pais+' +'+clave.clave"></option>
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
                            <label class="col-md-2 form-control-label" for="text-input">Lugar de nacimiento:</label>
                            <div class="col-md-6">
                                <input type="text" name="city3" list="cityname3" class="form-control" v-model="lugar_nacimiento">
                                <datalist id="cityname3">
                                    <option value="">Seleccione</option>
                                    <option v-for="estados in arrayEstados" :key="estados.estado" :value="estados.estado" v-text="estados.estado"></option>
                                </datalist>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-md-1 form-control-label" for="text-input">RFC:</label>
                            <div class="col-md-3">
                                <input type="text" v-model="rfc" @keyup="selectRFC(rfc)"
                                :disabled="prospecto==1 && rfc != '' && rfc != null"
                                class="form-control" placeholder="RFC" maxlength="10">
                            </div>
                            <label class="col-md-1 form-control-label" for="text-input">NSS:</label>
                            <div class="col-md-4">
                                <input type="text" v-model="nss" pattern="\d*" class="form-control" v-on:keypress="$root.isNumber($event)" placeholder="NSS" maxlength="11">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-1 form-control-label" for="text-input">CURP:</label>
                            <div class="col-md-3">
                                <input type="text" v-model="curp" class="form-control" placeholder="CURP" maxlength="18">
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
                                    <option :value="0">No</option>
                                    <option :value="1">Si</option>
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
                                    <option v-for="proyecto in $root.$data.proyectos" :key="proyecto.id"
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
                                <p><strong>${{ $root.formatNumber(rango1)}}</strong></p>
                            </div>
                            <div class="col-md-3">
                                <input class="form-control" type="text" v-model="rango1" placeholder="Minimo">
                                <input class="form-control" type="range" name="price-min" id="price-min" v-model="rango1" min="5000" max="30000">
                            </div>
                            <div class="col-md-2">
                                <p><strong>${{ $root.formatNumber(rango2)}}</strong></p>
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
                                <p><strong>{{ $root.formatNumber(rango2)}}m&sup2;</strong></p>

                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Precio por m&sup2;: </label>
                            <div class="col-md-5">
                                <input class="form-control" type="number" v-model="rango1">
                                <p><strong>${{ $root.formatNumber(rango1)}}</strong></p>
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

                <!-- Div para mostrar los errores de captura -->
                <div v-show="errorProspecto" class="form-group row div-error">
                    <div class="text-center text-error">
                        <div v-for="error in errorMostrarMsjProspecto" :key="error" v-text="error">
                        </div>
                    </div>
                </div>
            </template>
            <template v-slot:buttons-footer>
                <template v-if="motivo == 1">
                    <Button v-if="(tipoAccion == 2 && vendedor_asign == userId && prospecto == 0 && rfc != '' && status !=0) || rolId == 1 && prospecto == 0 && status !=0"
                        @click="sendProspecto()" :btnClass="'btn-success'" title="Registrar lead en prospectos" :icon="'fa fa-paper-plane'"
                    >Enviar a prospectos</Button>
                    <Button v-if="(tipoAccion == 2 && status !=0)" :btnClass="'btn-danger'" @click="descartar()" title="Descartar lead" :icon="'icon-close'">
                        Descartar
                    </Button>
                </template>
                    <div></div>
                <Button v-if="tipoAccion == 1 && motivo != 0" @click="storeLead()">Registrar</Button>
                <Button v-if="tipoAccion == 2" @click="updateLead()">Guardar cambios</Button>
            </template>
        </ModalComponent>
        <!--Fin del modal consulta-->
        <!--Inicio del modal observaciones-->
        <ModalComponent v-if="modal==2"
            :titulo="tituloModal"
            @closeModal="cerrarModal()"
        >
            <template v-slot:body>
                <div class="form-group row">
                    <label class="col-md-2 form-control-label" for="text-input">Observacion</label>
                    <div class="col-md-7">
                            <textarea rows="3" cols="30" v-model="comentario" class="form-control" placeholder="Observacion"></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Fecha para recordatorio</label>
                    <div class="col-md-3">
                            <input type="date" class="form-control" v-model="fecha_aviso" placeholder="Fecha de notificación">
                    </div>
                    <div class="col-md-2">
                        <Button @click="storeObs()">Guardar</Button>
                    </div>
                </div>
                <TableComponent :cabecera="['Usuario','Observación','Fecha']">
                    <template v-slot:tbody>
                        <tr v-for="observacion in arrayObs.data" :key="observacion.id">
                            <td v-text="observacion.usuario" ></td>
                            <td v-text="observacion.comentario" ></td>
                            <td v-text="observacion.created_at"></td>
                        </tr>
                    </template>
                </TableComponent>
            </template>
        </ModalComponent>
        <ModalComponent v-if="modal==3"
            :titulo="tituloModal"
            @closeModal="cerrarModal()"
        >
            <template v-slot:body>
                <div class="form-group row">
                    <label class="col-md-2 form-control-label" for="text-input"><strong>Proyecto</strong></label>
                    <div class="col-md-6">
                        <select class="form-control" v-model="buscadores.fraccionamiento_id" v-on:change="$root.selectEtapa(buscadores.fraccionamiento_id)">
                            <option value="">Seleccione</option>
                            <option v-for="proyecto in $root.$data.proyectos" :key="proyecto.id"
                                :value="proyecto.id" v-text="proyecto.nombre">
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 form-control-label" for="text-input"><strong>Etapa</strong></label>
                    <div class="col-md-6">
                        <select class="form-control" v-model="buscadores.etapa_id" v-on:change="getInventario(), getInventarioFull()">
                            <option value="">Seleccione</option>
                            <option
                                v-for="etapa in $root.$data.etapas"
                                :key="etapa.id"
                                :value="etapa.id"
                                v-text="etapa.num_etapa">
                            </option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <ul class="nav nav-tabs">
                            <li class="nav-item"><a class="nav-link"  v-bind:class="{ 'active': paso==1 }" @click="paso = 1">Resumen</a></li>
                            <li class="nav-item"><a class="nav-link"  v-bind:class="{ 'active': paso==2 }" @click="paso = 2">Completo</a></li>
                        </ul>
                    </div>
                    <div class="col-md-12" v-if="paso == 1">
                        <TableComponent :cabecera="['Modelo','Manzana', '#Lote', 'Precio']">
                            <template v-slot:tbody>
                                <tr v-for="lote in inventario" :key="lote.id">
                                    <td class="td2">
                                        <Button v-if="lote.ficha_tecnica != null" title="Ver ficha tecnica" :btnClass="'btn-success'" :size="'btn-sm'"
                                            @click="fichaTecnica(lote.ficha_tecnica)"
                                        > {{lote.modelo}} </Button>
                                        <p v-else>{{lote.modelo}}</p>
                                    </td>
                                    <td class="td2">{{lote.lote.manzana}}</td>
                                    <td class="td2">
                                        {{ (lote.lote.sublote) ? lote.lote.num_lote + ' ' + lote.lote.sublote
                                            : lote.lote.num_lote
                                         }}
                                    </td>
                                    <td class="td2" v-text="'$'+ $root.formatNumber(lote.lote.p_venta)"></td>
                                </tr>
                            </template>
                        </TableComponent>
                    </div>
                    <div class="col-md-12" v-if="paso == 2">
                        <TableComponent :cabecera="['Modelo','Manzana', '#Lote', 'Sup. Terreno', 'Precio', 'Promoción']">
                            <template v-slot:tbody>
                                <tr v-for="lote in inventarioFull" :key="lote.id">
                                    <td class="td2">{{lote.modelo}}</td>
                                    <td class="td2">{{lote.manzana}}</td>
                                    <td class="td2">
                                        {{ (lote.sublote) ? lote.num_lote + ' ' + lote.sublote
                                            : lote.num_lote
                                         }}
                                    </td>
                                    <td class="td2">{{$root.formatNumber(lote.terreno)}} m&sup2;</td>
                                    <td class="td2" v-text="'$'+ $root.formatNumber(lote.p_venta)"></td>
                                    <td>
                                        <Button @click="mostrarPromo(lote.promocion)" v-if="lote.promocion != ''" :icon="'icon-eye'"></Button>
                                    </td>
                                </tr>
                            </template>
                        </TableComponent>
                    </div>
                </div>
            </template>
        </ModalComponent>
        <ModalComponent v-if="modal==4"
            :titulo="tituloModal"
            @closeModal="cerrarModal()"
        >
            <template v-slot:body>
                <div class="form-group row">
                    <label class="col-md-2 form-control-label" for="text-input"><strong>Fin de Hibernación</strong></label>
                    <div class="col-md-6">
                        <input type="date" class="form-control" v-model="fin_dormir" placeholder="Fin">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 form-control-label" for="text-input"><strong>Motivo</strong></label>
                    <div class="col-md-8">
                        <textarea rows="3" cols="30" class="form-control" v-model="comentario"></textarea>
                    </div>
                </div>
            </template>
            <template v-slot:buttons-footer>
                <Button v-if="fin_dormir != '' && comentario != ''" @click="hibernarLead()" :icon="'fa fa-power-off'"></Button>
            </template>
        </ModalComponent>
        <ModalComponent v-if="modal==5"
            :titulo="tituloModal"
            @closeModal="cerrarModal()"
        >
            <template v-slot:body>
                <div class="form-group row">
                    <label class="col-md-2 form-control-label" for="text-input"><strong>Resultado de la auditoria</strong></label>
                    <div class="col-md-8">
                        <textarea rows="3" cols="30" class="form-control" v-model="comentario"></textarea>
                    </div>
                </div>
            </template>
            <template v-slot:buttons-footer>
                <Button v-if="comentario != ''" @click="auditar()" :icon="'icon-check'"></Button>
            </template>
        </ModalComponent>
    </main>
</template>

<script>
import vSelect from 'vue-select';
import LoadingComponent from '../Componentes/LoadingComponent.vue';
import TableComponent from '../Componentes/TableComponent.vue';
import ModalComponent from '../Componentes/ModalComponent.vue';
import Nav from '../Componentes/NavComponent.vue';
import Button from '../Componentes/ButtonComponent.vue'

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
            b_cliente: '',
            b_apellidos : '',
            b_status : '',
            b_campania : '',
            b_asesor:'',
            b_motivo:1,
            b_fecha1:'',
            b_fecha2:'',
            b_fasign1 :'',
            b_fasign2: '',
            b_proyecto:'',
            b_prioridad:'',
            b_modelo:'',
            b_user_name : '',
            b_user_lastname : '',
            b_contacto : '',
            b_cupon: '3',
            b_auditado: '',
            b_hibernar: 0,
            proceso : false,
            loading: false,
            buscadores:{},
            inventario:[],
            inventarioFull:[],
            arrayColonias:[],
            arrayEstados:[],
            arrayCiudades:[],
            arrayCampanias:[],
            arrayModelos:[],
            arrayEmpresas:[],
            arrayCreditos:[],
            arrayObs:[],
            arrayAsesores:[],
            b_semaforo : '',
            b_semaforo_recepcion: '',
            b_semaforo_gerente: '',

            medio_contacto: '',
            medio_publicidad: '',
            campania_id: '',
            campania: '',
            nombre: '',
            apellidos: '',
            name_user : '',
            last_name_user : '',
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

            curp: '',
            lugar_nacimiento: '',

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

            fin_dormir : '',

            errorMostrarMsjProspecto : [],
            errorProspecto : 0,

        }
    },
    computed:{

    },
    components:{
        vSelect,
        ModalComponent,
        TableComponent,
        LoadingComponent,
        Nav,
        Button
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
            if(this.coacreditado === '' || this.coacreditado === null)
                this.errorMostrarMsjProspecto.push("Indica si será crédito con coacreditado");

            if(this.errorMostrarMsjProspecto.length)//Si el mensaje tiene almacenado algo en el array
                this.errorProspecto = 1;

            return this.errorProspecto;
        },
        async premioPaste(url){
            try {
                await navigator.clipboard.writeText(url.split(' ').join('%20'));
                alert('URL copiada');
            } catch($e) {
            }
        },
        mostrarPromo(promo){
            Swal({
            title: 'Promoción',
            html: "<h5 style='color:#111F4F'>" + promo + "</h5>",
            animation: false,
            customClass: 'animated tada'
            })
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
                'curp': this.curp,
                'lugar_nacimiento' : this.lugar_nacimiento,
                ////////////// Paso 3 /////////////////
                'coacreditado' : this.coacreditado,
            }).then(function (response){
                //me.cerrarModal();
                me.updateLead();
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
        hibernarLead(){
            let me = this;

            axios.put('/leads/hibernarLead',{
                'id': me.id,
                'status':1,
                'comentario': me.comentario,
                'fin_dormir' : me.fin_dormir
            }).then(
                rsponse => {
                    this.myAlerts.popAlert('Lead en hibernación');
                    this.listarLeads(this.arrayLeads.current_page);
                    this.cerrarModal();

                    return response.data;
                }
            ).catch(error => console.log(error));
        },
        auditar(){
            let me = this;

            axios.put('/leads/auditar',{
                'id': me.id,
                'comentario': me.comentario,
            }).then(
                rsponse => {
                    this.myAlerts.popAlert('Lead en auditado');
                    this.listarLeads(this.arrayLeads.current_page);
                    this.cerrarModal();

                    return response.data;
                }
            ).catch(error => console.log(error));
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
        setCuponEnviado(id){
            let me = this;

            axios.put('/leads/setCuponEnviado',{
                'id': id,
            }).then(
                rsponse => {
                    this.listarLeads(this.arrayLeads.current_page);
                    return response.data;
                }
            ).catch(error => console.log(error));
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
        fichaTecnica(archivo){
            window.open('/files/modelos/ficha/'+archivo, '_blank')
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
            this.b_fasign2='';
            this.b_fasign1='';
            this.b_prioridad='';
            this.b_modelo ='';
            this.b_contacto = '';
            this.b_cupon = '3';
            this.b_hibernar = '';
            this.b_auditado = '';

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
        selectAsesores(castigo = ''){
            let me = this;
            me.arrayAsesores=[];
            var url = '/select/asesores?tipo=0&condicion=1&castigo='+castigo;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayAsesores = respuesta.personas;
            })
            .catch(function (error) {
                console.log(error);
            });

        },
        listarLeads (page){
            let me = this;
            me.loading = true;
            if(me.rolId == 12)
                me.b_motivo = 2;

            if(me.rolId == 3)
                me.b_motivo = 6;

            axios.get('/leads/index'+'?buscar=' + me.b_cliente+ '&b_apellidos=' + me.b_apellidos +
                '&campania='+me.b_campania  + '&status='+me.b_status+
                '&asesor='+me.b_asesor      + '&motivo='+me.b_motivo +
                '&fecha1='+me.b_fecha1      + '&fecha2='+me.b_fecha2 +
                '&f_asign1='+me.b_fasign1      + '&f_asign2='+me.b_fasign2 +
                '&proyecto='+me.b_proyecto  + '&prioridad='+me.b_prioridad +
                '&b_cupon='+me.b_cupon + '&hibernar=' + me.b_hibernar +
                '&b_auditado=' + me.b_auditado +
                '&modelo='+me.b_modelo      + '&page=' + page +
                '&b_semaforo=' + me.b_semaforo +
                '&b_semaforo_gerente=' + me.b_semaforo_gerente +
                '&b_semaforo_recepcion=' + me.b_semaforo_recepcion +
                '&b_user_name='+me.b_user_name + '&b_user_lastname=' + me.b_user_lastname +
                '&b_contacto='+me.b_contacto
            ).then(function(response){
                me.arrayLeads = response.data;
                me.pagina = ''
                me.loading = false;
            }).catch(function (error) {
                console.log(error);
                me.loading = false;
            });
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
        campaniaSelect(search, loading){
            let me = this;
            loading(true)

            var url = '/campanias/campaniaActiva?buscar=1+&campania='+search;
            axios.get(url).then(function (response) {
                let respuesta = response.data;
                q: search
                me.arrayCampanias = respuesta;
                loading(false)
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        getCampania(val1){
            let me = this;
            me.campania_id = val1.id
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
        getInventario(){
            let me = this;
            me.inventario = [];

            var url = '/lote/getInventarioRes?fraccionamiento_id='+me.buscadores.fraccionamiento_id
                +   '&etapa_id=' + me.buscadores.etapa_id;
            axios.get(url).then(function(response){
                me.inventario = response.data;
            }).catch(function(error){
                console.log(error)
            })
        },
        getInventarioFull(){
            let me = this;
            me.inventarioFull = [];

            var url = '/lote/getInventarioFull?fraccionamiento_id='+me.buscadores.fraccionamiento_id
                +   '&etapa_id=' + me.buscadores.etapa_id;
            axios.get(url).then(function(response){
                me.inventarioFull = response.data;
            }).catch(function(error){
                console.log(error)
            })
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
            this.modal = 0;
            this.selectCampania(1);
            this.selectAsesores()
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

                'curp': this.curp,
                'lugar_nacimiento' : this.lugar_nacimiento,

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

                'curp': this.curp,
                'lugar_nacimiento' : this.lugar_nacimiento,

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
            this.listarObservacion(1,id)
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
                    this.selectAsesores(1)
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
                    this.last_name_user = data['last_name_user'];
                    this.name_user = data['name_user'];
                    this.apellidos = data['apellidos'];
                    this.telefono = data['telefono'];
                    this.clv_lada = data['clv_lada'];
                    this.celular = data['celular'];
                    this.campania_id = data['campania_id'];
                    this.campania = '';
                    if(this.campania_id != null)
                        this.campania = data['nombre_campania'] + '-' + data['medio_digital'];
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
                    this.curp = data['curp'];
                    this.lugar_nacimiento = data['lugar_nacimiento'];

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
                    this.campania = '';

                    this.curp = '';
                    this.lugar_nacimiento = '';

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

                case 'inventario':{
                    this.buscadores = {
                        fraccionamiento_id : '',
                        etapa_id : ''
                    };
                    this.inventario = [];
                    this.paso = 1;
                    this.inventarioFull = [];
                    this.tituloModal = 'Inventario';
                    this.modal = 3;
                    break;
                }
                case 'hibernar':{
                    this.id = data['id'];
                    this.fin_dormir = '';
                    this.comentario = '';
                    this.modal = 4;
                    this.tituloModal= 'Hibernar lead';
                    break;
                }
                case 'auditar':{
                    this.id = data['id'];
                    this.comentario = '';
                    this.modal = 5;
                    this.tituloModal= 'Auditar lead';
                    break;
                }
            }
        },
        back(){
            this.editar = 0;
        }
    },
    mounted() {
        this.b_cliente = this.$root.$data.buscar;
        this.listarLeads(1);
        this.selectCampania(1);
        this.$root.selectFraccionamientos();
        this.selectAsesores();
        this.$root.getClavesLadas();
        this.$root.$data.buscar = '';
    },
};
</script>
<style>
    .line-separator{
        height:1px;
        background:#717171;
        border-bottom:1px solid #c2cfd6;
    }

    .bg-gradient-primary {
        background: #00ADEF!important;
        background: linear-gradient(45deg,#321fdb 0%,#00ADEF 100%)!important;
        border-color: #00ADEF!important;
    }
    .p-3 {
        padding: 1rem!important;
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

