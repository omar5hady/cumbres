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
                        <i class="fa fa-align-justify"></i> Créditos Puente
                        <button v-if="vista == 1" @click="listarAvisos(1)" class="btn btn-secondary">
                            <i class="fa fa-mail-reply"></i>&nbsp;Regresar
                        </button>
                    </div>
                    <!-- Div Card Body para listar -->
                    <template v-if="vista == 0">
                        <div class="card-body"> 
                            <div class="form-group row">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <select class="form-control" @keyup.enter="listarAvisos(1)" v-model="buscar" >
                                            <option value="">Seleccione</option>
                                            <option v-for="proyecto in arrayProyectos" :key="proyecto.id" :value="proyecto.id" v-text="proyecto.nombre"></option>
                                        </select>
                                        <input type="text" class="form-control" v-model="b_folio" @keyup.enter="listarAvisos(1)" placeholder="Folio">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <select class="form-control" @keyup.enter="listarAvisos(1)" v-model="b_status" >
                                            <option value="3">Aprobado</option>
                                            <option value="2">Rechazado</option>
                                            <option value="4">Liquidado</option>
                                        </select>
                                        <button type="submit" @click="listarAvisos(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table2 table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Folio</th>
                                            <th>Proyecto</th>
                                            <th>Número de Cuenta</th>
                                            <th>Tasa de interes</th>
                                            <th>Apertura</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th></th>
                                            <!-- <th>Fecha solicitud</th>
                                            <th>Status</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="creditosPuente in arrayCreditosPuente" :key="creditosPuente.id" @dblclick="verEdoCuenta(creditosPuente)">
                                            <td>
                                                <a href="#" v-text="creditosPuente.folio"></a>
                                            </td>
                                            <td class="td2" v-text="creditosPuente.proyecto"></td>
                                            <td class="td2" v-if="creditosPuente.num_cuenta == null">
                                                <button type="button" @click="numCuenta(creditosPuente.id)" class="btn btn-primary btn-sm" title="Num Cuenta">
                                                    <i class="fa fa-credit-card-alt">&nbsp;No. De cuenta</i>
                                                </button>
                                            </td>
                                            <td class="td2" v-else v-text="creditosPuente.num_cuenta"></td>
                                            <td class="td2"> TIEE+{{formatNumber(creditosPuente.interes)}}</td>
                                            <td class="td2"> {{formatNumber(creditosPuente.apertura)}}%</td>
                                            <td class="td2"> ${{formatNumber(creditosPuente.total)}}</td>
                                            <td class="td2" v-if="creditosPuente.status == 0">
                                                <span class="badge badge-info">Pendiente</span>
                                            </td>
                                            <td class="td2" v-if="creditosPuente.status == 1">
                                                <span class="badge badge-warning">Expediente integrado:</span> {{creditosPuente.fecha_integracion}}
                                            </td>
                                            <td class="td2">
                                                <button type="button" @click="abrirModal('obs',creditosPuente.id)" class="btn btn-dark btn-sm" title="Observaciones">
                                                    <i class="fa fa-book">&nbsp;Observaciones</i>
                                                </button>
                                            </td>
                                        </tr>                               
                                    </tbody>
                                </table>
                            </div>
                            <nav>
                                <!--Botones de paginacion -->
                                <ul class="pagination">
                                    <li class="page-item" v-if="pagination.current_page > 1">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1)">Ant</a>
                                    </li>
                                    <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                                    </li>
                                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </template>

                    <!-- Div para ver estado de cuenta -->
                    <template v-if="vista == 1">
                        <div class="card-body"> 
                            <div class="row">
                                <div class="col-xl-12 col-lg-5 col-md-4">
                                    <div class="card">
                                        <div>
                                             <div class="card-body p-12 d-flex align-items-center">
                                                <div>
                                                    <div class="text-value text-uppercase font-weight-bold text-primary text-center">
                                                        Estado de cuenta del Crédito Puente "{{datosPuente.proyecto}} {{datosPuente.lotes}} VIV"
                                                    </div>
                                                </div>
                                                <br>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-left:0px;">
                                            <div class="card-body p-3 d-flex align-items-center">
                                                <div>
                                                    <div class="text-uppercase font-weight-bold" 
                                                        v-text="'Institucion de crédito: '">
                                                    </div>
                                                    <div class="text-uppercase font-weight-bold" 
                                                        v-text="'Número de casas: '">
                                                    </div>
                                                    <div class="text-uppercase font-weight-bold" 
                                                        v-text="'Ingresos a: '">
                                                    </div>
                                                    <div class="text-uppercase font-weight-bold" 
                                                        v-text="'TASA: '">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body p-3 d-flex align-items-center">
                                                <div>
                                                    <div class="text-uppercase" 
                                                        v-text="datosPuente.banco">
                                                    </div>
                                                    <div class="text-uppercase" 
                                                        v-text="datosPuente.lotes">
                                                    </div>
                                                    <div class="text-uppercase" 
                                                        v-text="'CTA DE '+ datosPuente.banco+' '+datosPuente.num_cuenta">
                                                    </div>
                                                    <div class="text-uppercase" 
                                                        v-text="'TIIE PROMEDIO 28 DÍAS + ' + formatNumber(datosPuente.interes)">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body p-3 d-flex align-items-center">
                                                <div>
                                                    <div class="text-muted text-uppercase font-weight-bold" 
                                                        v-text="'Comisión S/Apert.: '">
                                                    </div>
                                                    
                                                    <!-- <div class="text-muted text-uppercase font-weight-bold" 
                                                        v-text="'Anticipo: '">
                                                    </div>
                                                    <div class="text-muted text-uppercase font-weight-bold" 
                                                        v-text="'Pre Puente: '">
                                                    </div> -->
                                                </div>
                                            </div>
                                            <div class="card-body p-3 d-flex align-items-center">
                                                <div>
                                                    <div class="text-muted text-uppercase" 
                                                        v-text=" formatNumber(datosPuente.apertura)+'%'">
                                                    </div>
                                                    
                                                    <!-- <div class="text-muted text-uppercase" 
                                                        v-text="'20.0%'">
                                                    </div>
                                                    <div class="text-muted text-uppercase" 
                                                        v-text="'20.0%'">
                                                    </div> -->
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row" style="margin-left:0px;">
                                            <div class="card-body p-4 d-flex align-items-center">
                                            </div>
                                            <div class="card-body p-4 d-flex align-items-center">
                                                <div>
                                                    <div class="text-uppercase font-weight-bold text-primary" 
                                                        v-text="'Monto total del crédito:   $'+formatNumber(datosPuente.credito_otorgado)">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body p-4 d-flex align-items-center">
                                                <button v-if="vista == 1" @click="abrirModal('movimiento',datosPuente.id)" class="btn btn-success">
                                                    <i class="icon-plus"></i>&nbsp;Nuevo movimiento
                                                </button>
                                            </div>
                                           
                                        </div>
                                        

                                        <div>
                                            <div class="card-footer px-3 py-2 text-right">
                                                <table class="table table-bordered table-striped table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Fecha</th>
                                                            <th>Concepto</th>
                                                            <th>Ingreso</th>
                                                            <th>Liq. de Viv.</th>
                                                            <th>Saldo</th>
                                                            <th>Sin IVA Comisiones</th>
                                                            <th>Sin IVA Honorarios</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!--Comisiones Bancarias--->
                                                            <tr v-for="pago in arrayPagos" :key="pago.id">
                                                                <td v-text="pago.fecha"></td>
                                                                <td v-text="pago.concepto"></td>
                                                                <td v-text="'$'+formatNumber(pago.cargo)"></td>
                                                                <td v-text="'$'+formatNumber(pago.abono)"></td>
                                                                <td v-text="'$'+formatNumber(pago.saldo)"></td>
                                                                <td v-text="'$'+formatNumber(pago.comisiones)"></td>
                                                                <td v-text="'$'+formatNumber(pago.honorarios)"></td>
                                                            </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </template>
                    
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>

            <!--Inicio del modal obs-->
                <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal == 1}" 
                    role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-primary modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" v-text="tituloModal"></h4>
                                <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                                <span aria-hidden="true">×</span>
                                </button>
                            </div>

                                <div class="modal-body">
                                    <template v-if="tipoAccion == 1">
                                        
                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Nueva observación</label>
                                                <div class="col-md-6">
                                                    <input type="text" v-model="observacion" class="form-control">
                                                </div>

                                                <div class="col-md-3">
                                                    <button v-if="observacion != ''" class="btn btn-primary" @click="storeObs()">Guardar</button>
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
                                                        <tr v-for="observacion in arrayObs" :key="observacion.id">
                                                            <td v-text="observacion.usuario" ></td>
                                                            <td v-text="observacion.observacion" ></td>
                                                            <td v-text="observacion.created_at"></td>
                                                        </tr>                               
                                                    </tbody>
                                                </table>
                                                
                                            </form>
                                        
                                        
                                    </template>

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
            <!--Fin del modal-->

            <!--Inicio del modal nuevo movimiento-->
                <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal == 2}" 
                    role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-primary modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" v-text="tituloModal"></h4>
                                <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                                <span aria-hidden="true">×</span>
                                </button>
                            </div>

                                <div class="modal-body">
                                    <template v-if="tipoAccion == 1">
                                        
                                        <div class="form-group row">
                                            <label class="col-md-2 form-control-label" for="text-input">Fecha</label>
                                            <div class="col-md-4">
                                                <input type="date" v-model="fecha" @change="getTiie(fecha)" class="form-control">
                                            </div>
                                            <label v-if="tiie != 0" class="col-md-2 form-control-label" for="text-input">TIIE a 28 dias:</label>
                                            <div v-if="tiie != 0" class="col-md-4">
                                                <label disabled type="text" v-text="tiie+' + '+datosPuente.interes" class="form-control"></label>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-2 form-control-label" for="text-input">Concepto</label>
                                            <div class="col-md-6">
                                                <input type="text" name="concepto" list="conceptName" class="form-control" v-model="concepto">
                                                <datalist id=conceptName @click="getDatos(recomendado)">
                                                    <option value="">Seleccione</option>
                                                    <option value="Prepuente">Prepuente</option>
                                                    <option value="Liquidación Prepuente">Liquidación Prepuente</option>
                                                    <option value="Renovación Prepuente">Renovación Prepuente</option>
                                                    <option value="Anticipo por Firma de Crédito">Anticipo por Firma de Crédito</option>
                                                    <option value="Ministración">Ministración</option>
                                                </datalist>
                                            </div>

                                            <div class="col-md-4">
                                                <select v-model="tipo" class="form-control" @change="iva_comision = 0, iva_honorario = 0, interes = 0">
                                                    <option value="">Tipo de movimiento</option>
                                                    <option value="0">Cargo</option>
                                                    <option value="1">Abono</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row" v-if="tipo != ''">
                                            <label class="col-md-2 form-control-label" for="text-input">Monto</label>
                                            <div class="col-md-4">
                                                <input v-if="tipo == 1" type="number" v-model="cantidad" class="form-control" v-on:change="calcularInteres()">
                                                <input v-else type="number" v-model="cantidad" class="form-control">
                                            </div>
                                            <label class="col-md-3 form-control-label" for="text-input">${{formatNumber(cantidad)}}</label>
                                        </div>

                                        <div class="form-group row" v-if="tipo == 1">
                                            <label class="col-md-2 form-control-label" for="text-input">Interes Ordinario</label>
                                            <div class="col-md-4">
                                                <input type="number" v-model="interes" class="form-control">
                                            </div>
                                            <label class="col-md-3 form-control-label" for="text-input">${{formatNumber(interes)}}</label>
                                        </div>


                                        <div class="form-group row" v-if="concepto == 'Prepuente' || concepto == 'Anticipo por Firma de Crédito'">
                                            <label class="col-md-2 form-control-label" for="text-input">IVA Comisiones</label>
                                            <div class="col-md-4">
                                                <input type="number" v-model="iva_comision" class="form-control">
                                            </div>
                                            <label class="col-md-3 form-control-label" for="text-input">${{formatNumber(iva_comision)}}</label>
                                        </div>

                                        <div class="form-group row" v-if="concepto == 'Prepuente' || concepto == 'Anticipo por Firma de Crédito'">
                                            <label class="col-md-2 form-control-label" for="text-input">IVA Honorarios</label>
                                            <div class="col-md-4">
                                                <input type="number" v-model="iva_honorario" class="form-control">
                                            </div>
                                            <label class="col-md-3 form-control-label" for="text-input">${{formatNumber(iva_honorario)}}</label>
                                        </div>
                                           
                                        
                                        
                                    </template>

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
            <!--Fin del modal-->
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
                arrayCreditosPuente : [],
                vista:0,
                tipoAccion: 0,
                errorcreditosPuente : 0,
                errorMostrarMsjcreditosPuente : [],
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                cabecera : {
                    'banco' : '',
                    'interes' : 0,
                    'fecha_solic' : '',
                    'status' : 0,
                    'total' : 0,
                    'cobrado' : 0,
                    'folio' : '',
                    'apertura' : 0,
                    'fraccionamiento' : '',
                },
                offset : 3,
                buscar : '',
                b_folio:'',
                b_status:3,
                arrayProyectos : [],

                datosPuente:{
                    'banco':'',
                    'apertura':'',
                    'anticipo':'',
                    'pre_puente':'',
                    'interes':'',
                    'viv_canceladas':'',
                    'viv_por_cancelar':'',
                    'lotes':0,
                    'num_cuenta':'',
                    'credito_otorgado':0,


                },
                movimientos:[],
                

                modal:0,
                tituloModal:'',
                tipoAccion:1,
                arrayObs:[],
                arrayPagos:[],
                observacion:'',

                fecha:'',
                cantidad:0,
                concepto:'',
                iva_comision:0,
                iva_honorario:0,
                fecha_interes:'',
                tiie:0,
                porcInteres:0,
                interes:0,
                saldo:0,
                tipo:'',


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
            listarAvisos(page){
                let me = this;
                me.vista = 0;
                var url = '/cPuentes/indexCreditos?page=' + page + '&fraccionamiento=' + me.buscar + '&folio=' + me.b_folio + '&status=' + me.b_status;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayCreditosPuente = respuesta.creditos.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getObs(id){
                let me = this;
                me.arrayObs=[];
                var url = '/cPuentes/getObs?id=' + id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayObs = respuesta.obs;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            calcularInteres(){
                let me = this;
                me.interes = 0;
                var url = '/cPuentes/calcularInteres?fechaIni='+me.fecha_interes+'&fechaFin='+me.fecha+'&tiie='
                    +me.tiie+'&interes='+me.datosPuente.interes+'&saldo='+me.saldo;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.interes = respuesta.interes;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            numCuenta(id){

                 (async function getFruit () {
                    const {value: comentario} = await Swal({
                    title: 'Numero de cuenta',
                    input: 'text',
                    inputPlaceholder: 'Numero de cuenta...',
                    showCancelButton: true,
                    inputValidator: (value) => {
                        return new Promise((resolve) => {
                        if (value != '') {
                            resolve()
                        } else {
                            resolve('Es necesario escribir el numero de cuenta :)')
                        }
                        })
                    }
                    })
                    if (comentario) {
                        axios.put('/cPuentes/numCuenta',{
                            'id' : id,
                            'numCuenta': comentario
                            
                        }).then(function (response){
                            me.ocultarDetalle();
                            swal({
                                position: 'top-end',
                                type: 'success',
                                title: 'Cambios guardados correctamente',
                                showConfirmButton: false,
                                timer: 1500
                                })
                        }).catch(function (error){
                            console.log(error);
                        });
                    }
                })()

            },

            getEdoCuenta(id){
                let me = this;
                me.arrayObs=[];
                var url = '/cPuentes/getEdoCuenta?id=' + id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPagos = respuesta.pagos;
                    me.fecha_interes = respuesta.ultimoAbono;
                    me.saldo = respuesta.saldo;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectProyectos(){
                let me = this;
                me.arrayProyectos=[];
                var url = '/select_fraccionamiento';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayProyectos = respuesta.fraccionamientos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getTiie(fecha){
                let me = this;
                me.tiie = 0;
                var url = '/cPuentes/tiie?fecha='+fecha+'&fecha_int='+this.fecha_interes;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.tiie = respuesta[0][0].dato;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarAvisos(page);
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
            verEdoCuenta(data){
                this.datosPuente = data;
                this.vista = 1;
                this.getEdoCuenta(this.datosPuente.id);
            },
            storeObs(){
                let me = this;
                axios.post('/cPuentes/storeObs',{
                    'id': this.id,
                    'observacion': this.observacion,
                   
                }).then(function (response){
                    //Obtener detalle
                        swal({
                            position: 'top-end',
                            type: 'success',
                            title: 'Comentario guardado correctamente',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        me.getObs(me.id);
                        me.observacion = '';
                    
                }).catch(function (error){
                    console.log(error);
                });
            },
            abrirModal(opcion,id){
                switch(opcion){
                    case 'obs':{
                        this.modal = 1;
                        this.tituloModal = 'Comentarios para el Crédito Puente';
                        this.arrayObs = [];
                        this.id = id;
                        this.tipoAccion = 1;
                        this.getObs(id);
                        break;
                    }
                    case 'movimiento':{
                        this.modal = 2;
                        this.tituloModal = 'Nuevo movimiento';
                        this.tipoAccion = 1;
                        this.id = id;
                        this.concepto;
                        this.cantidad = 0;
                        this.fecha = '';
                        this.iva_comision = 0;
                        this.iva_honorario = 0;
                        this.tiie = 0;
                        this.tipo = '';
                        break;
                    }
                }
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.observacion = '';
                this.listarAvisos(1);
            },
        },
        mounted() {
            this.listarAvisos(1);
            this.selectProyectos();
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