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
                        <i class="fa fa-align-justify"></i> Mantenimiento de Vehiculos en Comodato
                        <!--   Boton Nuevo    -->
                        <button type="button" @click="abrirModal('registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nueva solicitud.
                        </button>
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text"  v-model="buscar" @keyup.enter="listarSolicitudes(1)" class="form-control" placeholder="Vehiculo">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-10">
                                <div class="input-group">
                                    <input type="text"  class="form-control col-md-3" disabled placeholder="Fecha: ">
                                    <input type="date"  v-model="b_fecha1" @keyup.enter="listarSolicitudes(1)" class="form-control">
                                    <input type="date"  v-model="b_fecha2" @keyup.enter="listarSolicitudes(1)" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <div class="input-group">
                                     <select class="form-control" v-model="b_status" >
                                        <option value="">Estatus</option>
                                        <option value="0">Cancelado</option>
                                        <option value="1">Pendiente</option>
                                        <option value="2">Aprobado</option>
                                        <option value="3">Liquidado</option>
                                    </select>
                                    <button type="submit" @click="listarSolicitudes(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive"> 
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Vehiculo</th>
                                        <th>Solicitante</th>
                                        <th>Servicio</th>
                                        <th>Importe total</th>
                                        <th>Aportación compañero</th>
                                        <th>Fecha de solic.</th>
                                        <th>Status</th>
                                        <th></th>
                                        <th>Jefe inmediato</th>
                                        <th>RH</th>
                                        <th>Control</th>
                                        <th>Dirección</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="vehiculo in arraySolicitudes.data" :key="vehiculo.id">
                                        <td class="td2">
                                            <button type="button" @click="abrirModal('ver',vehiculo)" class="btn btn-warning btn-sm">
                                                <i class="icon-eye"></i>
                                            </button>
                                        </td>
                                        <td class="td2" v-text="vehiculo.marca + ' ' + vehiculo.auto + ' ' + vehiculo.modelo"></td>
                                        <td class="td2" v-text="vehiculo.solicitante"></td>
                                        <td class="td2" v-text="vehiculo.reparacion"></td>
                                        <td class="td2" v-text="'$' + formatNumber(vehiculo.importe_total)"></td>
                                        <td class="td2" v-text="'$' + formatNumber(vehiculo.monto_comp)"></td>
                                        <td class="td2" v-text="this.moment(vehiculo.created_at).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td class="td2">
                                            <!-- 0 Cancelado, 1 Pendiente, 2 Aprobado, 3 Liquidado -->
                                            <span v-if="vehiculo.status == 0" class="badge badge-danger">Rechazado</span>
                                            <span v-if="vehiculo.status == 1" class="badge badge-warning">Pendiente</span>
                                            <span v-if="vehiculo.status == 2" class="badge badge-primary">Aprobado</span>
                                            <span v-if="vehiculo.status == 3" class="badge badge-success">Liquidado</span>
                                        </td>
                                        <td></td>
                                        <template v-if="adminMant == '1'">
                                            <td class="td2" v-if="vehiculo.recep_jefe == null">
                                                <button type="button" @click="firmaJefe(vehiculo.id)" class="btn btn-dark btn-sm" title="Firma de enterado Jefe inmediato">
                                                    <i class="icon-check"></i>
                                                </button>
                                            </td>
                                            <td class="td2" v-else v-text="'Firmado el dia: '+vehiculo.recep_jefe"></td>

                                            <td class="td2" v-if="vehiculo.recep_rh == null">
                                                <button type="button" @click="firmaRH(vehiculo.id)" class="btn btn-dark btn-sm" title="Firma de enterado RH">
                                                    <i class="icon-check"></i>
                                                </button>
                                            </td>
                                            <td class="td2" v-else v-text="'Firmado el dia: '+vehiculo.recep_rh"></td>

                                            <td class="td2" v-if="vehiculo.recep_control == null">
                                                <button type="button" @click="firmaControl(vehiculo.id)" class="btn btn-dark btn-sm" title="Firma de enterado Control">
                                                    <i class="icon-check"></i>
                                                </button>
                                            </td>
                                            <td class="td2" v-else v-text="'Firmado el dia: '+vehiculo.recep_control"></td>

                                            <td class="td2" v-if="vehiculo.recep_direccion == null">
                                                <button type="button" @click="firmaDireccion(vehiculo.id)" class="btn btn-dark btn-sm" title="Firma de enterado Dirección">
                                                    <i class="icon-check"></i>
                                                </button>
                                            </td>
                                            <td class="td2" v-else v-text="'Firmado el dia: '+vehiculo.recep_direccion"></td>
                                        </template>

                                        <template v-if="adminMant == '0'">
                                            <td class="td2" v-if="vehiculo.recep_jefe == null">
                                                Sin Firma de Jefe
                                            </td>
                                            <td class="td2" v-else v-text="'Firmado el dia: '+vehiculo.recep_jefe"></td>
                                            <td class="td2" v-if="vehiculo.recep_rh == null">
                                                Sin Firma de RH
                                            </td>
                                            <td class="td2" v-else v-text="'Firmado el dia: '+vehiculo.recep_rh"></td>
                                            <td class="td2" v-if="vehiculo.recep_control == null">
                                                Sin Firma de Control
                                            </td>
                                            <td class="td2" v-else v-text="'Firmado el dia: '+vehiculo.recep_control"></td>
                                            <td class="td2" v-if="vehiculo.recep_direccion == null">
                                                Sin Firma de Dirección
                                            </td>
                                            <td class="td2" v-else v-text="'Firmado el dia: '+vehiculo.recep_direccion"></td>
                                        </template>
                                        <td>
                                            <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right" 
                                                        @click="abrirModal('observaciones',vehiculo)">Observaciones</button>
                                        </td>
                                    </tr>                               
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
                           <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="arraySolicitudes.current_page > 5" @click="listarSolicitudes(1)">
                                    <a class="page-link" href="#" >Inicio</a>
                                </li>
                                <li class="page-item" v-if="arraySolicitudes.current_page > 1"
                                    @click="listarSolicitudes(arraySolicitudes.current_page-1)">
                                    <a class="page-link" href="#" >Ant</a>
                                </li>

                                <li class="page-item" v-if="arraySolicitudes.current_page-3 >= 1"
                                    @click="listarSolicitudes(arraySolicitudes.current_page-3)">
                                    <a class="page-link" href="#" v-text="arraySolicitudes.current_page-3"></a>
                                </li>
                                <li class="page-item" v-if="arraySolicitudes.current_page-2 >= 1"
                                    @click="listarSolicitudes(arraySolicitudes.current_page-2)">
                                    <a class="page-link" href="#" v-text="arraySolicitudes.current_page-2"></a>
                                </li>
                                <li class="page-item" v-if="arraySolicitudes.current_page-1 >= 1"
                                    @click="listarSolicitudes(arraySolicitudes.current_page-1)">
                                    <a class="page-link" href="#" v-text="arraySolicitudes.current_page-1"></a>
                                </li>
                                <li class="page-item active" >
                                    <a class="page-link" href="#" v-text="arraySolicitudes.current_page"></a>
                                </li>
                                <li class="page-item" 
                                    v-if="arraySolicitudes.current_page+1 <= arraySolicitudes.last_page">
                                    <a class="page-link" href="#" @click="listarSolicitudes(arraySolicitudes.current_page+1)" 
                                    v-text="arraySolicitudes.current_page+1"></a>
                                </li>
                                <li class="page-item" 
                                    v-if="arraySolicitudes.current_page+2 <= arraySolicitudes.last_page">
                                    <a class="page-link" href="#" @click="listarSolicitudes(arraySolicitudes.current_page+2)"
                                     v-text="arraySolicitudes.current_page+2"></a>
                                </li>
                                <li class="page-item" 
                                    v-if="arraySolicitudes.current_page+3 <= arraySolicitudes.last_page">
                                    <a class="page-link" href="#" @click="listarSolicitudes(arraySolicitudes.current_page+3)"
                                    v-text="arraySolicitudes.current_page+3"></a>
                                </li>

                                <li class="page-item" v-if="arraySolicitudes.current_page < arraySolicitudes.last_page"
                                    @click="listarSolicitudes(arraySolicitudes.current_page+1)">
                                    <a class="page-link" href="#" >Sig</a>
                                </li>
                                <li class="page-item" v-if="arraySolicitudes.current_page < 5 && arraySolicitudes.last_page > 5" @click="listarSolicitudes(arraySolicitudes.last_page)">
                                    <a class="page-link" href="#" >Ultimo</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
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
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Vehiculo
                                        <span style="color:red;" v-show="vehiculo==''">*</span>
                                    </label>
                                    <div class="col-md-6">
                                        <select :disabled="tipoAccion==2" class="form-control" v-model="vehiculo" >
                                            <option value="">Seleccione vehiculo</option>
                                            <option v-for="vehiculo in arrayVehiculos" :key="vehiculo.id" :value="vehiculo.id" v-text="vehiculo.marca + ' ' + vehiculo.vehiculo"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Servicio
                                        <span style="color:red;" v-show="reparacion==''">*</span>
                                    </label>
                                    <div class="col-md-8">
                                        <select class="form-control" @change="calcularMontos()" :disabled="tipoAccion==2" v-model="reparacion">
                                            <option value="">Seleccione servicio</option>
                                            <option value="Mantenimiento Preventivo">Mantenimiento Preventivo</option>
                                            <option value="Seguro Cobertura Amplia">Seguro Cobertura Amplia</option>
                                            <option value="Reparaciones mecánicas">Reparaciones mecánicas</option>
                                            <option value="Llantas">Llantas</option>
                                            <option value="Tenencia y placas a partir de que el vehículo se una a las filas de la empresa">Tenencia y placas a partir de que el vehículo se una a las filas de la empresa</option>
                                            <option value="Hojalatería y pintura derivado de accidente en horas de trabajo">Hojalatería y pintura derivado de accidente en horas de trabajo</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Descripción de la reparación</label>
                                    <div class="col-md-8">
                                        <input type="text" v-model="descripcion" :disabled="tipoAccion==2" class="form-control" placeholder="Descripción">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Taller
                                        <span style="color:red;" v-show="taller==''">*</span>
                                    </label>
                                    <div class="col-md-8">
                                        <input type="text" :disabled="tipoAccion==2" v-model="taller" maxlength="50" class="form-control" placeholder="Nombre del taller">
                                    </div>
                                </div>

                                <div class="form-group row" v-if="reparacion != ''">
                                    <label class="col-md-3 form-control-label" for="text-input">Importe Total
                                        <span style="color:red;" v-show="importe_total ==''">*</span>
                                    </label>
                                    <div class="col-md-4" v-if="tipoAccion == 1">
                                        <input type="number" v-on:change="calcularMontos()" v-model="importe_total" maxlength="12" class="form-control" placeholder="Importe total">
                                    </div>
                                    <div class="col-md-3">
                                        <label v-text="'$'+formatNumber(importe_total)"></label>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Forma de pago</label>
                                    <div class="col-md-4">
                                        <select :disabled="tipoAccion==2" class="form-control" v-model="forma_pago">
                                            <option value="0">Efectivo</option>
                                            <option value="1">Tarjeta de Debito</option>
                                            <option value="2">Tarjeta de Crédito</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row" v-if="importe_total != 0 && importe_total != ''">
                                    <label class="col-md-3 form-control-label" for="text-input">Aportación Compañero</label>
                                    
                                    <div class="col-md-3">
                                        <strong v-text="'$'+formatNumber(monto_comp)"></strong>
                                    </div>
                                </div>

                                <div class="form-group row" v-if="importe_total != 0 && importe_total != ''">
                                    <label class="col-md-3 form-control-label" for="text-input">Aportación GCC</label>
                                    
                                    <div class="col-md-3">
                                        <label v-text="'$'+formatNumber(monto_gcc)"></label>
                                    </div>
                                </div>

                                <div class="form-group row" >
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de inicio de renteciones</label>
                                    
                                    <div class="col-md-4">
                                        <input type="date" :disabled="tipoAccion==2" v-model="fecha_ini" @change="getFechas()" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row" v-if="fecha_ini != '' && importe_total != 0 && reparacion != ''">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Importe</th>
                                                    <th v-if="status_rev > 0">Status</th>
                                                    <template v-if="adminMant == 1">
                                                        <th v-if="status_rev > 1">
                                                            Autorización
                                                        </th>
                                                    </template>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="retencion in arrayRetenciones" :key="retencion.id">
                                                    <td class="td2" v-text="retencion.fecha_retencion"></td>
                                                    <td class="td2" v-if="tipoAccion == 1">
                                                        <input @change="validarMonto(retencion)"  type="number" v-model="retencion.importe">
                                                    </td>
                                                    <td class="td2" v-if="tipoAccion == 2"
                                                        v-text="'$' + formatNumber(retencion.importe)"
                                                    ></td>
                                                    <td class="td2" v-if="status_rev > 0">
                                                        <span v-if="retencion.status == 0" class="badge badge-warning">Pendiente</span>
                                                        <span v-if="retencion.status == 1" class="badge badge-warning">Retenido</span>
                                                    </td>
                                                    <template v-if="adminMant == 1">
                                                        <td v-if="status_rev > 1 && retencion.status == 0">
                                                            <button type="button" @click="retenerPago(retencion.id)" class="btn btn-success btn-sm" title="Retener pago">
                                                                <i class="icon-check"></i>
                                                            </button>
                                                        </td>
                                                        <td v-if="status_rev > 1 && retencion.status == 1">
                                                            Pago retenido el dia: {{retencion.fecha_real }}
                                                        </td>
                                                    </template>
                                                </tr>     
                                                <tr>
                                                    <td></td>
                                                    <th>
                                                        $ {{formatNumber(total_retener = totalRetenido)}}
                                                    </th>
                                                </tr>          
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <!-- Div para mostrar los errores que mande validerNotaria -->
                                <div v-show="errorVehiculo" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjVehiculo" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==1 && total_retener == monto_comp && importe_total > 0" class="btn btn-primary" @click="registrar()">Guardar</button>

                            <button type="button" v-if="status_rev==1 && revisado == 1 && adminMant == 1" class="btn btn-success" @click="changeStatus(2)">Aprobar</button>
                            <button type="button" v-if="status_rev==1 && revisado == 1 && adminMant == 1" class="btn btn-danger" @click="changeStatus(0)">Rechazar</button>


                            
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->

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
                                         <textarea rows="3" cols="30" v-model="observacion" class="form-control" placeholder="Observacion"></textarea>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button"  class="btn btn-primary" @click="agregarComentario()">Guardar</button>
                                    </div>
                                </div>

                                
                                <table class="table table-bordered table-striped table-sm" >
                                    <thead>
                                        <tr>
                                            <th>Usuario</th>
                                            <th>Observacion</th>
                                            <th>Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="observacion in arrayObservaciones" :key="observacion.id">
                                            
                                            <td v-text="observacion.usuario" ></td>
                                            <td v-text="observacion.observacion" ></td>
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

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    export default {
        props:{
            adminMant:{type: String}
        },
        data(){
            return{
                arraySolicitudes : [],
                arrayVehiculos : [],
                arrayRetenciones :[],
                arrayObservaciones : [],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                errorVehiculo : 0,
                errorMostrarMsjVehiculo : [],
                buscar : '',
                b_fecha1 : '',
                b_fecha2 : '',
                b_status : '',
                b_solicitante : '',
                importe_total: 0,
                monto_comp : 0,
                monto_gcc : 0,
                forma_pago : 0,
                reparacion : '',
                descripcion : '',
                solicitante : '',
                id:'',
                vehiculo : '',
                taller : '',
                fecha_ini:'',
                mes : '',
                anio : '',
                dia: '',
                total_retener : 0,
                saldo : 0,
                revisado : 0,
                status_rev : 0,
                observacion:'',
            }
        },
        computed:{
            totalRetenido: function(){
                let me = this;
                var total = 0;

                for(var i=0;i<this.arrayRetenciones.length;i++)
                    total += parseFloat(this.arrayRetenciones[i].importe)

                return total;
            },
        },
        methods : {
            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
            /**Metodo para mostrar los registros */
            listarSolicitudes(page){
                let me = this;
                var url = '/vehiculos/getSolicitudes?page=' + page 
                    + '&b_fecha1=' + me.b_fecha1
                    + '&b_fecha2=' + me.b_fecha2
                    + '&b_status=' + me.b_status
                    + '&buscar=' + me.buscar;

                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arraySolicitudes = respuesta;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getObservaciones(id){
                let me = this;
                var url = '/vehiculos/getObservaciones?id=' + id;;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayObservaciones = respuesta;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            validarMonto(retencion){
                let me = this;
                me.saldo = parseFloat(me.monto_comp) - parseFloat(me.total_retener) + parseFloat(retencion.importe);

                if(parseFloat(retencion.importe) > parseFloat(me.saldo))
                    me.arrayRetenciones[retencion.id].importe = parseFloat(me.saldo);

                if(me.arrayRetenciones[retencion.id].importe < 0)
                    me.arrayRetenciones[retencion.id].importe = 0;
                    
            },
            getVehiculos(){
                let me = this;
                var url = '/vehiculos/getComoDato';

                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayVehiculos = respuesta;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getFechas(){
                let me = this;
                me.arrayRetenciones = [];
                me.mes = me.fecha_ini.substring(5,7);
                me.dia = me.fecha_ini.substring(8,10);
                me.anio = me.fecha_ini.substring(0, 4);

                let lastDay = moment(me.fecha_ini).endOf('month').format('YYYY-MM-DD');
                    if(parseInt(me.dia) <= 15){
                        me.fecha_ini = me.anio+'-'+me.mes+'-'+'15';
                    }    
                    if(parseInt(me.dia) > 15){
                        me.fecha_ini = lastDay;
                    }
                var fecha = me.fecha_ini;

                for(let i = 0; i<4; i ++){
                    
                    me.arrayRetenciones.push({
                        id:i,
                        fecha_retencion: fecha,
                        importe: 0
                    });

                    let aux_mes = fecha.substring(5,7);
                    let aux_dia = fecha.substring(8,10);
                    let aux_anio = fecha.substring(0, 4);

                    let lastDay = moment(fecha).endOf('month').format('YYYY-MM-DD');
                    if(parseInt(aux_dia) <= 15){
                        fecha = lastDay;
                    }    
                    if(parseInt(aux_dia) > 15){
                        if(parseInt(aux_mes) == 12){
                            aux_anio = parseInt(aux_anio) + 1;
                            fecha = aux_anio +'-'+ '01-15';
                        }
                        else{
                            aux_mes = parseInt(aux_mes) + 1;
                            fecha = aux_anio +'-'+ aux_mes + '-15';
                        }
                    }
                }
            },
            /**Metodo para registrar  */
            registrar(){
                if(this.validarRegistro()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/vehiculos/storeSolicitud',{
                    
                    'vehiculo' : this.vehiculo,
                    'reparacion' : this.reparacion,
                    'descripcion' : this.descripcion,
                    'taller' : this.taller,
                    'forma_pago' : this.forma_pago,
                    'importe_total' : this.importe_total,
                    'monto_comp' : this.monto_comp,
                    'monto_gcc' : this.monto_gcc,

                    'retenciones' : this.arrayRetenciones,
                   
                }).then(function (response){
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarSolicitudes(1); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Solicitud registrada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            agregarComentario(){
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/vehiculos/storeObs',{
                    'id' : me.id,
                    'obs' : me.observacion
                }).then(function (response){
                    me.getObservaciones(me.id); //se enlistan nuevamente los registros
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
            firmaJefe(id){
                let me = this;
                Swal.fire({
                    title: '¿Estas seguro de firmar esta solicitud',
                    text: "Este cambio no se podrá deshacer!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si continuar!',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.value) {
                         axios.put('/vehiculos/setRecepJefe',{
                            'id' : id
                        }).then(function (response){
                            me.listarSolicitudes(1); //se enlistan nuevamente los registros
                            //Se muestra mensaje Success
                            swal({
                                position: 'top-end',
                                type: 'success',
                                title: 'Solicitud Firmada por el jefe inmediato correctamente',
                                showConfirmButton: false,
                                timer: 1500
                                })
                        }).catch(function (error){
                            console.log(error);
                        });
                    }
                });
               
            },
            firmaRH(id){
                let me = this;
                Swal.fire({
                    title: '¿Estas seguro de firmar esta solicitud',
                    text: "Este cambio no se podrá deshacer!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si continuar!',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.value) {
                         axios.put('/vehiculos/setRecepRH',{
                            'id' : id
                        }).then(function (response){
                            me.listarSolicitudes(1); //se enlistan nuevamente los registros
                            //Se muestra mensaje Success
                            swal({
                                position: 'top-end',
                                type: 'success',
                                title: 'Solicitud Firmada por el dpto. RH correctamente',
                                showConfirmButton: false,
                                timer: 1500
                                })
                        }).catch(function (error){
                            console.log(error);
                        });
                    }
                });
               
            },
            firmaControl(id){
                let me = this;
                Swal.fire({
                    title: '¿Estas seguro de firmar esta solicitud',
                    text: "Este cambio no se podrá deshacer!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si continuar!',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.value) {
                         axios.put('/vehiculos/setRecepControl',{
                            'id' : id
                        }).then(function (response){
                            me.listarSolicitudes(1); //se enlistan nuevamente los registros
                            //Se muestra mensaje Success
                            swal({
                                position: 'top-end',
                                type: 'success',
                                title: 'Solicitud Firmada por Control inmediato correctamente',
                                showConfirmButton: false,
                                timer: 1500
                                })
                        }).catch(function (error){
                            console.log(error);
                        });
                    }
                });
               
            },
            firmaDireccion(id){
                let me = this;
                Swal.fire({
                    title: '¿Estas seguro de firmar esta solicitud',
                    text: "Este cambio no se podrá deshacer!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si continuar!',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.value) {
                         axios.put('/vehiculos/setRecepDireccion',{
                            'id' : id
                        }).then(function (response){
                            me.listarSolicitudes(1); //se enlistan nuevamente los registros
                            //Se muestra mensaje Success
                            swal({
                                position: 'top-end',
                                type: 'success',
                                title: 'Solicitud Firmada por Dirección inmediato correctamente',
                                showConfirmButton: false,
                                timer: 1500
                                })
                        }).catch(function (error){
                            console.log(error);
                        });
                    }
                });
               
            },
            retenerPago(id){
                let me = this;
                Swal.fire({
                    title: '¿Estas seguro de retener este pago?',
                    text: "Este cambio no se podrá deshacer!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si continuar!',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.value) {
                         axios.put('/vehiculos/retenerPago',{
                            'id' : id
                        }).then(function (response){
                            me.cerrarModal();
                            me.listarSolicitudes(1); //se enlistan nuevamente los registros
                            //Se muestra mensaje Success
                            swal({
                                position: 'top-end',
                                type: 'success',
                                title: 'El pago seleccionado ha sido retenido',
                                showConfirmButton: false,
                                timer: 1500
                                })
                        }).catch(function (error){
                            console.log(error);
                        });
                    }
                });
               
            },
            changeStatus(status){
                let me = this;
                let estado = '';
                if(status == 0)
                    estado = 'Rechazar';
                else
                    estado = 'Aprobar';
                Swal.fire({
                    title: '¿Estas seguro de ' + estado + ' esta solicitud',
                    text: "Este cambio no se podrá deshacer!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si continuar!',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.value) {
                         axios.put('/vehiculos/recep/changeStatus',{
                            'id' : me.id,
                            'status':status
                        }).then(function (response){
                            me.listarSolicitudes(1); //se enlistan nuevamente los registros
                            me.cerrarModal();
                            //Se muestra mensaje Success
                            swal({
                                position: 'top-end',
                                type: 'success',
                                title: 'Cambios realizados correctamente',
                                showConfirmButton: false,
                                timer: 1500
                                })
                        }).catch(function (error){
                            console.log(error);
                        });
                    }
                });
               
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
            validarRegistro(){
                this.errorVehiculo=0;
                this.errorMostrarMsjVehiculo=[];

                if(!this.vehiculo)
                    this.errorMostrarMsjVehiculo.push("Seleccionar vehiculo");

                if(!this.reparacion)
                    this.errorMostrarMsjVehiculo.push("Seleccionar reparación");

                if(!this.taller)
                    this.errorMostrarMsjVehiculo.push("Nombre del taller");

                if(this.errorMostrarMsjVehiculo.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorVehiculo = 1;

                return this.errorVehiculo;
            },
            cerrarModal(){
               this.modal = 0;
            },
            calcularMontos(){
                let me = this;
                switch(me.reparacion){
                    case 'Mantenimiento Preventivo':{
                        me.monto_gcc = me.importe_total;
                        me.monto_comp = 0;
                        break;
                    }
                    case "Seguro Cobertura Amplia":{
                        me.monto_gcc = me.importe_total;
                        me.monto_comp = 0;
                        break;
                    }
                    case "Reparaciones mecánicas":{
                        me.monto_gcc = me.importe_total*.5;
                        me.monto_comp = me.importe_total*.5;
                        break;
                    }
                    case "Llantas":{
                        me.monto_gcc = me.importe_total*.5;
                        me.monto_comp = me.importe_total*.5;
                        break;
                    }
                    case "Tenencia y placas a partir de que el vehículo se una a las filas de la empresa":{
                        me.monto_gcc = me.importe_total*.5;
                        me.monto_comp = me.importe_total*.5;
                        break;
                    }
                    case "Hojalatería y pintura derivado de accidente en horas de trabajo":{
                        me.monto_gcc = me.importe_total*.5;
                        me.monto_comp = me.importe_total*.5;
                        break;
                    }
                }
            },
            //Retorna el ultimo dia del mes.
            daysInMonth (month, year) {
                return new Date(year, month, 0).getDate();
            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(accion,data =[]){
                this.getVehiculos();
                switch(accion){
                    case 'registrar':
                    {
                        this.modal = 1;
                        this.tituloModal = 'Nueva Solicitud de Mantenimiento Vehicular';
                        
                        this.tipoAccion = 1;
                        this.vehiculo = '';
                        this.importe_total = 0;
                        this.monto_comp = 0;
                        this.monto_gcc = 0;
                        this.reparacion = '';
                        this.descripcion = '';
                        this.taller = '';
                        this.fecha = '';
                        this.forma_pago = 0;

                        this.mes = '';
                        this.anio = '';
                        this.dia = '';

                        this.arrayRetenciones = [];
                        let date = moment().format('YYYY-MM-DD');
                        this.fecha_ini = '';
                        this.saldo = 0;
                        this.total_retener = 0;
                        this.status_rev = 0;
                        break;
                    }
                    case 'ver':
                    {
                        this.revisado = 0;
                        this.modal = 1;
                        this.tituloModal = 'Solicitud de Mantenimiento Vehicular #'+ data['id'];
                        
                        this.tipoAccion = 2;
                        this.id = data['id'];
                        this.vehiculo = data['vehiculo'];
                        this.importe_total = data['importe_total'];
                        this.monto_comp = data['monto_comp'];
                        this.monto_gcc = data['monto_gcc'];
                        this.reparacion = data['reparacion'];
                        this.descripcion = data['descripcion'];
                        this.taller = data['taller'];
                        this.fecha = data['created_at'];
                        this.forma_pago = data['forma_pago'];

                        this.arrayRetenciones = data['retenciones'];
                        this.fecha_ini = this.arrayRetenciones[0]['fecha_retencion'];
                        this.status_rev = data['status'];

                        if(data['recep_jefe'] != null &&
                            data['recep_rh'] != null &&
                            data['recep_control'] != null &&
                            data['recep_direccion'] != null
                        )   this.revisado = 1;
                        
                        
                        break;
                    }
                    case 'observaciones':
                    {
                        this.modal = 2;
                        this.tituloModal = 'Observaciones';
                        this.id = data['id'];
                        this.observacion = '';
                        this.getObservaciones(this.id)
                        break;
                    }
                }
            }
        },
        mounted() {
            this.listarSolicitudes(1);
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
        position: fixed !important;
        background-color: #3c29297a !important;
        overflow-y: auto;
    }
    .div-error{
        display:flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
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
