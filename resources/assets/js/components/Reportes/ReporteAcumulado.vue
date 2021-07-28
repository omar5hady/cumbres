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
                        <i class="fa fa-align-justify"></i> Reporte acumulado mensual &nbsp;&nbsp;
                        <a v-if="mes != '' && anio != ''" :href="'/reprotes/excelExpedientes?mes=' + mes + '&anio=' + anio + '&empresa=' + emp_constructora"  class="btn btn-success"><i class="fa fa-file-text"></i> Excel </a>
                        <!---->
                    </div>
                    <div class="card-body">
                         <ul class="nav nav2 nav-tabs" id="myTab1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active show" id="expediente-tab" data-toggle="tab" href="#expediente" role="tab" aria-controls="expediente" v-text="'Expedientes entregados'"></a>
                            </li>
                            
                        </ul>

                        <div>
                            <div class="form-group row">
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <select class="form-control col-md-4" v-model="mes">
                                            <option value="">Seleccione mes</option>
                                            <option value="01">Enero</option>
                                            <option value="02">Febrero</option>
                                            <option value="03">Marzo</option>
                                            <option value="04">Abril</option>
                                            <option value="05">Mayo</option>
                                            <option value="06">Junio</option>
                                            <option value="07">Julio</option>
                                            <option value="08">Agosto</option>
                                            <option value="09">Septiembre</option>
                                            <option value="10">Octubre</option>
                                            <option value="11">Noviembre</option>
                                            <option value="12">Diciembre</option>
                                        </select>
                                        <select class="form-control col-md-4" @keyup.enter="listarReporte()" v-model="anio">
                                            <option value="">Seleccione año</option>
                                            <option value="2018">2018</option>
                                            <option value="2019">2019</option>
                                            <option value="2020">2020</option>
                                            <option value="2021">2021</option>
                                            <option value="2022">2022</option>
                                            <option value="2023">2023</option>
                                            <option value="2024">2024</option>
                                            <option value="2025">2025</option>
                                            <option value="2026">2026</option>
                                            <option value="2027">2027</option>
                                            <option value="2028">2028</option>
                                            <option value="2029">2029</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <select class="form-control col-md-6" v-model="emp_constructora">
                                            <option value="">Empresa constructora</option>
                                            <option value="Grupo Constructor Cumbres">Grupo Constructor Cumbres</option>
                                            <option value="CONCRETANIA">CONCRETANIA</option>
                                        </select>
                                        <button type="submit" @click="listarReporte()" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        

                        <div class="tab-content" id="myTab1Content">

                            <!-- Listado por Expedientes entregados -->
                            <div class="tab-pane fade active show" id="expediente" role="tabpanel" aria-labelledby="expediente-tab">

                                <div class="table-responsive">
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th colspan="11" class="text-center">Reporte de Expedientes Entregados </th>
                                            </tr>
                                            <tr></tr>
                                            <tr>
                                                <th colspan="2" class="text-center"> </th>
                                                <th colspan="4" class="text-center"> Ubicación / Plantio </th>
                                                <th colspan="5" class="text-center"> </th>
                                            </tr>
                                            <tr>
                                                <th># Referencia</th>
                                                <th>Cliente</th>
                                                <th>Fraccionamiento</th>
                                                <th>Etapa</th>
                                                <th>Manzana</th>
                                                <th>Lote</th>
                                                <th>Crédito</th>
                                                <th></th>
                                                <th>Envio de envio</th>
                                                <th>Fecha de recibido</th>
                                                <th></th>
                                                <th v-if="rolId == 1 || userId == 24977"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="lote in arrayExpCreditos" :key="lote.id" v-if="lote.flag == 1 && lote.mes == 1" >
                                                <td class="td2" v-text="lote.id"></td>
                                                <td class="td2" v-text="lote.nombre.toUpperCase() + ' ' + lote.apellidos.toUpperCase()"></td>
                                                <td class="td2" v-text="lote.proyecto"></td>
                                                <td class="td2" v-text="lote.num_etapa"></td>
                                                <td class="td2" v-text="lote.manzana"></td>
                                                <td class="td2" v-text="lote.num_lote"></td>
                                                <td class="td2" v-text="lote.tipo_credito"></td>
                                                <td></td>

                                                <td class="td2" v-if="lote.send_exp == null">
                                                    <button class="btn btn-dark" @click="enviarExp(lote.id)">Enviar exp</button>
                                                </td>
                                                <td class="td2" v-else v-text="'Expediente enviado el: '+lote.send_exp"></td>
                                                <td class="td2" v-if="lote.send_exp == null">
                                                    Expediente pendiente de envio
                                                </td>
                                                <td class="td2" v-else-if="lote.received_exp == null">
                                                    <button class="btn btn-dark" @click="recibirExp(lote.id)">Confirmar expediente recibido</button>
                                                </td>
                                                <td class="td2" v-else v-text="'Expediente recibido el: '+lote.received_exp"></td>

                                                <td class="td2">
                                                    <button class="btn btn-primary" @click="comentarios(lote.id,0)">Ver Comentarios</button>
                                                </td>

                                                <td v-if="rolId == 1 || userId == 24977" class="td2">
                                                    <button class="btn btn-dark" @click="comentarios(lote.id,1)" v-if="lote.fecha_audit == null">Auditar</button>
                                                    <span v-else>{{'Auditado el: ' + this.moment(lote.fecha_audit).locale('es').format('DD/MMM/YYYY')}}</span>
                                                </td>
                                                
                                            </tr>                             
                                        </tbody>
                                    </table>
                                    <br>

                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th colspan="11" class="text-center">Expedientes de contado </th>
                                            </tr>
                                            <tr></tr>
                                            <tr>
                                                <th colspan="2" class="text-center"> </th>
                                                <th colspan="4" class="text-center"> Ubicación / Plantio </th>
                                                <th colspan="5" class="text-center"> </th>
                                            </tr>
                                            <tr>
                                                <th># Referencia</th>
                                                <th>Cliente</th>
                                                <th>Fraccionamiento</th>
                                                <th>Etapa</th>
                                                <th>Manzana</th>
                                                <th>Lote</th>
                                                <th>Crédito</th>
                                                 <th></th>
                                                <th>Envio de envio</th>
                                                <th>Fecha de recibido</th>
                                                <th></th>
                                                <th v-if="rolId == 1 || userId == 24977"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="lote in arrayExpContado" :key="lote.id" >
                                                <td class="td2" v-text="lote.id"></td>
                                                <td class="td2" v-text="lote.nombre.toUpperCase() + ' ' + lote.apellidos.toUpperCase()"></td>
                                                <td class="td2" v-text="lote.proyecto"></td>
                                                <td class="td2" v-text="lote.num_etapa"></td>
                                                <td class="td2" v-text="lote.manzana"></td>
                                                <td class="td2" v-text="lote.num_lote"></td>
                                                <td class="td2" v-text="lote.tipo_credito"></td>
                                                <td></td>

                                                <td class="td2" v-if="lote.send_exp == null">
                                                    <button class="btn btn-dark" @click="enviarExp(lote.id)">Enviar exp</button>
                                                </td>
                                                <td class="td2" v-else v-text="'Expediente enviado el: '+lote.send_exp"></td>
                                                <td class="td2" v-if="lote.send_exp == null">
                                                    Expediente pendiente de envio
                                                </td>
                                                <td class="td2" v-else-if="lote.received_exp == null">
                                                    <button class="btn btn-dark" @click="recibirExp(lote.id)">Confirmar expediente recibido</button>
                                                </td>
                                                <td class="td2" v-else v-text="'Expediente recibido el: '+lote.received_exp"></td>

                                                <td class="td2">
                                                    <button class="btn btn-primary" @click="comentarios(lote.id,0)">Ver Comentarios</button>
                                                </td>
                                                <td v-if="rolId == 1 || userId == 24977" class="td2">
                                                    <button class="btn btn-dark" @click="comentarios(lote.id,1)" v-if="lote.fecha_audit == null">Auditar</button>
                                                    <span v-else>{{'Auditado el: ' + this.moment(lote.fecha_audit).locale('es').format('DD/MMM/YYYY')}}</span>
                                                </td>
                                                
                                            </tr>                             
                                        </tbody>
                                    </table>

                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th colspan="7" class="text-center">Pendientes de cobro </th>
                                            </tr>
                                            <tr></tr>
                                            
                                            <tr>
                                                <th># Referencia</th>
                                                <th>Cliente</th>
                                                <th>Fraccionamiento</th>
                                                <th>Etapa</th>
                                                <th>Manzana</th>
                                                <th>Lote</th>
                                                <th>Crédito</th>
                                                <th>Monto pendiente</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="lote in arrayPendientes" :key="lote.crd_id" >
                                                <td class="td2" v-text="lote.id"></td>
                                                <td class="td2" v-text="lote.nombre.toUpperCase() + ' ' + lote.apellidos.toUpperCase()"></td>
                                                <td class="td2" v-text="lote.proyecto"></td>
                                                <td class="td2" v-text="lote.num_etapa"></td>
                                                <td class="td2" v-text="lote.manzana"></td>
                                                <td class="td2" v-text="lote.num_lote"></td>
                                                <td class="td2" v-text="lote.tipo_credito"></td>
                                                <td class="td2" v-text="`$ ${formatNumber(lote.monto_credito - lote.cobrado)}`"></td>
                                                <td class="td2">
                                                    <button class="btn btn-primary" @click="comentarios(lote.id,0)">Ver Comentarios</button>
                                                </td>
                                                
                                            </tr>                             
                                        </tbody>
                                    </table>

                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>    

            <!--Inicio del modal observaciones-->
                <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-primary modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" v-text="'Observaciones'"></h4>
                                <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                                <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Nueva observación</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="observacion" class="form-control">
                                    </div>

                                    <div v-if="tipo == 0" class="col-md-3">
                                        <button class="btn btn-primary" @click="storeObservacion()">Guardar</button>
                                    </div>
                                </div>


                                <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                    
                                    <table class="table table-bordered table-striped table-sm">
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
                                <button type="button" v-if="tipo == 1 && observacion != ''" class="btn btn-primary" @click="auditar()">Aceptar</button>
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
    export default {
        props:{
            rolId:{type: String},
            userId:{type: String}
        },
        data(){
            return{
               
                arrayExpCreditos:[],
                arrayExpContado:[],
                arrayPendientes:[],

                mes:'',
                anio:'',
                emp_constructora:'',

                arrayObservacion:[],
                observacion:'',

                modal:'',
                id:'',
                tipo:0,
            }
        },
        computed:{
        },
        methods : {
            /**Metodo para mostrar los registros */
            listarReporte(){
                let me = this;

                me.arrayExpCreditos = [];
                me.arrayExpContado = [];
                me.arrayPendientes = [];

                var url = '/reprotes/reporteAcumulado?mes=' + me.mes + '&anio=' +
                     me.anio + '&empresa=' + me.emp_constructora + '&opcion=Expedientes';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayExpCreditos = respuesta.expCreditos;
                    me.arrayExpContado = respuesta.expContado;
                    me.arrayPendientes = respuesta.pendientes;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            comentarios(id,tipo){
                let me = this;
                me.tipo = tipo;
                var url = '/contrato/getObsExpEntregados?page=1&id=' + id ;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayObservacion = respuesta.observacion.data;
                    console.log(url);
                })
                .catch(function (error) {
                    console.log(error);
                });

                this.modal = 1;
                this.observacion = '';
                this.id = id;
            },

            auditar(){
                 
                let me = this;
                //Con axios se llama el metodo update de LoteController
                
                 Swal({
                    title: 'Estas seguro?',
                    animation: false,
                    customClass: 'animated bounceInDown',
                    text: "El expediente quedara auditado",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    
                    confirmButtonText: 'Si, auditar!'
                    }).then((result) => {

                    if (result.value) {
                       
                        axios.post('/contratos/auditar',{
                            'id':this.id,
                            'comentario' : this.observacion,
                        }); 

                        me.observacion = 'Auditado: ' + me.observacion;

                        me.storeObservacion();
                        
                        //me.cerrarModal();
                        me.listarReporte();
                        Swal({
                            title: 'Hecho!',
                            text: 'Expediente auditado',
                            type: 'success',
                            animation: false,
                            customClass: 'animated bounceInRight'
                        })
                    }
                })
              
            },

            cerrarModal(){
                this.modal=0;
                this.tipo = 0;
                this.observacion='';
                this.arrayObservacion=[];
            },

            storeObservacion(){
                 let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.post('/contratos/storeObsExpEntregado',{
                    'observacion' : this.observacion,
                    'id':this.id,
                    
                }).then(function (response){
                    me.listarReporte();
                    me.cerrarModal();
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Observación guardada correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            enviarExp(id){

                let me = this;
                //Con axios se llama el metodo update de LoteController
                
                 Swal({
                    title: 'Estas seguro?',
                    animation: false,
                    customClass: 'animated bounceInDown',
                    text: "Confirmar envio de expediente",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    
                    confirmButtonText: 'Si, enviar!'
                    }).then((result) => {

                    if (result.value) {
                       
                        axios.put('/contrato/sendExp',{
                            'id':id,
                        }); 
                        
                        me.listarReporte();
                        Swal({
                            title: 'Hecho!',
                            text: 'Expediente enviado',
                            type: 'success',
                            animation: false,
                            customClass: 'animated bounceInRight'
                        })
                    }
                })

            },

            recibirExp(id){

                let me = this;
                //Con axios se llama el metodo update de LoteController
                
                 Swal({
                    title: 'Estas seguro?',
                    animation: false,
                    customClass: 'animated bounceInDown',
                    text: "Confirmar recibo de expediente",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    
                    confirmButtonText: 'Si, enviar!'
                    }).then((result) => {

                    if (result.value) {
                       
                        axios.put('/contrato/receivedExp',{
                            'id':id,
                        }); 
                        
                        me.listarReporte();
                        Swal({
                            title: 'Hecho!',
                            text: 'Expediente recibido',
                            type: 'success',
                            animation: false,
                            customClass: 'animated bounceInRight'
                        })
                    }
                })

            },

            
            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
        
        },
        mounted() {
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
