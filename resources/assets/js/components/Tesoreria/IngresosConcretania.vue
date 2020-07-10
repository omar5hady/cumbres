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
                        <i class="fa fa-align-justify"></i> Relación de los ingresos de Concretania
                        <!--   Boton Nuevo    -->
                        <button  v-if="historial == 0" type="button" @click="abrirModal('enviar')" class="btn btn-dark">
                            <i class="icon-envelope-letter"></i> &nbsp;Solicitar
                        </button>
                        &nbsp;
                        <button v-if="historial == 0" type="button" @click="historial=1" class="btn btn-primary">
                            <i class="fa fa-calendar-check-o"></i>&nbsp;Historial
                        </button>
                        <button v-if="historial == 1" type="button" @click="historial=0" class="btn btn-default">
                            <i class="fa fa-mail-reply"></i>&nbsp;Regresar
                        </button>
                        <!---->
                    </div>
                    <div class="card-body" v-if="historial == 0">
                        <div class="form-group row">
                            <div class="col-md-7">
                                <div class="input-group">
                                    <select class="form-control" disabled>
                                        <option value="fecha">Fecha de ingreso</option>
                                    </select>
                                    <!--Criterios para el listado de busqueda -->
                                    <input type="date" class="form-control" v-model="b_fecha">
                                    <input type="date" class="form-control" v-model="b_fecha2">
                                </div>
                            </div>
                            
                        </div>
                        <div class="form-group row">
                            <div class="col-md-9">
                                <div class="input-group">
                                    <button type="submit" @click="listarLotes(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" @click="selectAll" v-model="allSelected"> Todos
                                        </th>
                                        <th>Fraccionamiento</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th># Lote</th>
                                        <th>Cliente</th>
                                        <th>Clasificación</th>
                                        <th>Importe</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="lote in arrayLotes" :key="lote.cont">
                                        <td class="text-center" style="width:8%; ">
                                            <input type="checkbox" @click="select" :id="lote.cont" :value="lote" v-model="lotes_ini">
                                        </td>
                                        <td class="td2" v-text="lote.fraccionamiento"></td>
                                        <td v-text="lote.etapa"></td>
                                        <td v-text="lote.manzana"></td>
                                        <td v-text="lote.num_lote"></td>
                                        <td class="td2" v-text="lote.nombre + ' ' + lote.apellidos"></td>
                                        <template v-if="lote.tipo == 0">
                                            <td class="td2" v-text="lote.concepto"></td>
                                        </template>
                                        <template v-else>
                                            <td class="td2" v-text="'Deposito de crédito'"></td>
                                        </template>
                                        <td class="td2" v-text="'$'+formatNumber(lote.monto_terreno)"></td>
                                        <td class="td2" v-text="lote.f_carga_factura_terreno"></td>
                                    </tr>                               
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-body" v-if="historial == 1">
                        <div class="form-group row">
                            <div class="col-md-7">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control" v-model="criterio">
                                        <option value="cliente">Cliente</option>
                                        <option value="fraccionamiento">Fraccionamiento</option>
                                    </select>
                                    <template v-if="criterio == 'cliente'">
                                        <input type="text" class="form-control" v-model="buscar" placeholder="Cliente">
                                    </template>
                                    <template v-else-if="criterio == 'fraccionamiento'">
                                        <select class="form-control" @click="selectEtapa(buscar)" v-model="buscar" >
                                            <option value="">Seleccionar</option>
                                            <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                        </select>
                                        <select class="form-control" v-model="b_etapa"> 
                                            <option value="">Etapa</option>
                                            <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                        </select>
                                    </template>
                                    
                                </div>
                            </div>
                            <div class="col-md-7" v-if="criterio=='fraccionamiento'">
                                <div class="input-group">
                                    <input type="text" class="form-control" v-model="b_manzana" placeholder="Manzana">
                                    <input type="text" class="form-control" v-model="b_lote" placeholder="Lote">
                                </div>
                            </div>
                            
                        </div>
                        <div class="form-group row">
                            <div class="col-md-7">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control" disabled>
                                        <option value="fecha">Fecha de ingreso</option>
                                    </select>
                                    <template>
                                        <input type="date" class="form-control" v-model="b_fecha">
                                        <input type="date" class="form-control" v-model="b_fecha2">
                                    </template>
                                    
                                    
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-5">
                                <div class="input-group">
                                    <select class="form-control" v-model="b_cuenta">
                                        <option value="">Seleccione</option>
                                        <option v-for="banco in arrayBancos" :key="banco.num_cuenta + '-' + banco.banco" :value="banco.num_cuenta + '-' + banco.banco" v-text="banco.num_cuenta + '-' + banco.banco"></option>
                                    </select>
                                    <button type="submit" @click="listarHistorial(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Fraccionamiento</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th># Lote</th>
                                        <th>Cliente</th>
                                        <th>Clasificación</th>
                                        <th>Importe</th>
                                        <th>Fecha deposito</th>
                                        <th>Cuenta</th>
                                        <th>Fecha de ingreso</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="lote in arrayHistorial" :key="lote.id" @dblclick="abrirModal('detalle',lote)" title="Doble clic">
                                        
                                        <td><a href="#" v-text="lote.fraccionamiento"></a></td>
                                        <td v-text="lote.etapa"></td>
                                        <td v-text="lote.manzana"></td>
                                        <td v-text="lote.num_lote"></td>
                                        <td class="td2" v-text="lote.nombre + ' ' + lote.apellidos"></td>
                                        <template v-if="lote.tipo == 0">
                                            <td class="td2" v-text="lote.concepto"></td>
                                        </template>
                                        <template v-else>
                                            <td class="td2" v-text="'Deposito de crédito'"></td>
                                        </template>
                                        <td class="td2" v-text="'$'+formatNumber(lote.monto_terreno)"></td>
                                        <td class="td2" v-text="lote.f_carga_factura_terreno"></td>
                                        <td class="td2" v-text="lote.cuenta"></td>
                                        <td class="td2" v-text="lote.fecha_ingreso_concretania"></td>
                                    </tr>  
                                    <tr>
                                        <th colspan="6" class="td2 text-right">Total</th>
                                        <th v-text="'$'+formatNumber(totalIngresos)" class="td2 text-center"></th>
                                        <th colspan="3"></th>    
                                    </tr>                             
                                </tbody>
                            </table>
                        </div>
                        
                    </div>

                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal-->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>

                        <div class="modal-body" v-if="tipoAccion == 1">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha</label>
                                    <div class="col-md-3">
                                        <input type="date" v-model="fecha" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Banco</label>
                                    <div class="col-md-6">
                                        <select class="form-control" v-model="cuenta">
                                            <option value="">Seleccione</option>
                                            <option v-for="banco in arrayBancos" :key="banco.num_cuenta + '-' + banco.banco" :value="banco.num_cuenta + '-' + banco.banco" v-text="banco.num_cuenta + '-' + banco.banco"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row"></div>

                                <div class="form-group row">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <h6 style="color:#2271b3;" ><strong> Total:  </strong></h6>&nbsp;
                                            <h6 style="color:#2271b3;" > &nbsp;{{'$'+formatNumber(total)}}</h6>
                                        </div>
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </form>
                        </div>

                        <div class="modal-body" v-if="tipoAccion == 2">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    
                                    <label class="col-md-2 form-control-label" for="text-input">Fraccionamiento</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="fraccionamiento" class="form-control" disabled>
                                    </div>

                                    
                                    <label class="col-md-1 form-control-label" for="text-input">Etapa</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="etapa" class="form-control" disabled>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    
                                    <label class="col-md-2 form-control-label" for="text-input">Manzana</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="manzana" class="form-control" disabled>
                                    </div>

                                    <label class="col-md-1 form-control-label" for="text-input">Lote</label>
                                    <div class="col-md-2">
                                        <input type="text" v-model="lote" class="form-control" disabled>
                                    </div>
                                </div>

                                <div class="form-group row"></div>
                                
                                <div class="form-group row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-8">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Valor de Terreno</th>
                                                    <th>Total pagado</th>
                                                    <th>Por pagar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    
                                                    <td v-text="'$'+formatNumber(monto_terreno)"></td>
                                                    <td v-text="'$'+formatNumber(pagado)"></td>
                                                    <td v-text="'$'+formatNumber(saldo)"></td>
                                                    
                                                </tr>  
                                                              
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                                
                            </form>
                        </div>

                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==1 && fecha != '' && cuenta != '' && ver == 1" class="btn btn-primary" @click="registrarIngreso()">Enviar</button>
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
        data(){
            return{
                proceso:false,
                allSelected: false,
                id:0,
                arrayLotes : [],
                arrayHistorial : [],
                lotes_ini : [],
                arrayEtapas: [],
                arrayBancos: [],
                arrayFraccionamientos:[],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                historial:0,
                errorLotes : 0,
                errorMostrarMsjLotes : [],
                
                criterio : 'lotes.fraccionamiento_id', 
                buscar : '',
                b_etapa:'',
                b_manzana:'',
                b_lote:'',
                b_fecha:'',
                b_fecha2:'',
                b_cuenta:'',
                paquete:'',
                fecha:'',
                cuenta:'',
                criterio:'cliente',
                total:0,

                monto_terreno:0,
                saldo:0,
                pagado:0,
                fraccionamiento:'',
                etapa:'',
                manzana:'',
                lote:'',

                ver:0,
            }
        },
        computed:{
            totalIngresos: function(){
                var total =0.0;
                for(var i=0;i<this.arrayHistorial.length;i++){
                    total += parseFloat(this.arrayHistorial[i].monto_terreno)
                }
                if(total < 0)
                    total = 0;
                total = Math.round(total*100)/100;
                return total;
            },


        },
        methods : {
            
            selectAll: function() {
                this.lotes_ini = [];

                if (!this.allSelected) {
                    for (var lote in this.arrayLotes) {
                        this.lotes_ini.push(this.arrayLotes[lote]);
                    }
                }
            },
            select: function() {
                this.allSelected = false;
            },

            selectFraccionamientos(){
                let me = this;
                if(me.modal == 0){
                    me.buscar=""
                    me.b_etapa=""
                    me.b_manzana=""
                }
                
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

            selectEtapa(buscar){
                let me = this;
                if(me.modal == 0){
                    me.b_etapa="";
                    me.b_manzana="";
                    me.b_lote="";
                }
                
                me.arrayEtapas=[];
                var url = '/select_etapa_proyecto?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEtapas = respuesta.etapas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectCuenta(){
                let me = this;
                me.arrayBancos=[];
                var url = '/select_cuenta';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayBancos = respuesta.cuentas;
                })
                .catch(function (error) {
                    console.log(error);
                });

            },

            /**Metodo para mostrar los registros */
            listarLotes(page){
                let me = this;
                me.lotes_ini = [];
                var url = '/ingresosConcretania/pendeintesIngresar?&b_fecha=' + me.b_fecha + '&b_fecha2=' + me.b_fecha2;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLotes = respuesta.pendientes;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            /**Metodo para mostrar los registros */
            listarHistorial(page){
                let me = this;
                var url = '/ingresosConcretania/historialIngresos?fecha=' + me.b_fecha + '&fecha2=' + me.b_fecha2;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayHistorial = respuesta.ingresos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            registrarIngreso(){
                if(this.proceso==true){
                    return;
                }
                
                this.proceso=true;
                this.ver=0;

                let me = this;
                //Con axios se llama el metodo update de DepartamentoController
                
                me.lotes_ini.forEach(element => {

                    
                    axios.put('/ingresosConcretania/guardarIngreso',{
                    'id': element['id'],
                    'depId': element['depId'],
                    'tipo': element['tipo'],
                    'monto_terreno': element['monto_terreno'],
                    'cuenta' : this.cuenta,
                    'fecha_ingreso_concretania': this.fecha
                    }); 
                });
                Swal({
                    title: 'Enviado!',
                    text: 'RUVs solicitados correctamente.',
                    imageUrl: 'https://d2r6jp7chi630e.cloudfront.net/blog/aritic-pinpoint/wp-content/uploads/sites/3/2016/09/email-gif.gif',
                    imageWidth: 800,
                    imageHeight: 400,
                    imageAlt: 'Custom image',
                    animation: true
                })
                me.proceso=false;
                me.ver=1;
                me.cerrarModal();
                me.listarLotes(1);
                
            },
            
            /**Metodo para registrar  */
            
            
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.errorLotes = 0;
                this.errorMostrarMsjLotes = [];
                this.lotes_ini = [];
                this.allSelected = false;
                this.total = 0;
            },

            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(accion,data =[]){
                if(this.lotes_ini.length<1 && accion == 'enviar'){
                    Swal({
                    title: 'No se ha seleccionado ningun lote',
                    animation: false,
                    customClass: 'animated tada'
                    })
                    return;
                }
                switch(accion){
                    case 'enviar':
                    {
                        for (var lote in this.lotes_ini) {
                            this.total += this.lotes_ini[lote].monto_terreno;
                        }
                        this.modal = 1;
                        this.tituloModal = 'Enviar ingresos';
                        this.fecha ='';
                        this.cuenta = '';
                        this.tipoAccion = 1;
                        break;
                    }
                    case 'detalle':
                    {
                        this.modal = 1;
                        this.tituloModal = 'Estado de cuenta';
                        this.fraccionamiento = data['fraccionamiento'];
                        this.etapa = data['etapa'];
                        this.manzana = data['manzana'];
                        this.lote = data['num_lote'];
                        this.pagado = data['saldo_terreno'];
                        this.monto_terreno = data['valor_terreno'];
                        this.saldo = this.monto_terreno - this.pagado;
                        this.tipoAccion = 2;
                        break;
                    }
                }
            }
        },
        mounted() {
            this.listarLotes(1);
            this.selectFraccionamientos();
            this.selectCuenta();
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
</style>
