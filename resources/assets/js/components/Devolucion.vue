<template >
    <main class="main" >
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card scroll-box">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i>Devoluciones

                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-10">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-5" v-model="criterio" @click="selectFraccionamientos()">
                                        <option value="lotes.fraccionamiento_id">Proyecto</option>
                                        <option value="personal.nombre">Cliente</option>
                                        <option value="creditos.id"># Folio</option>
                                    </select>
                                    
                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar"  @keyup.enter="listarContratos(1, buscar, b_etapa, b_manzana, b_lote, criterio)" @click="selectEtapa(buscar)">
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>

                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_etapa"  @keyup.enter="listarContratos(1, buscar, b_etapa, b_manzana, b_lote, criterio)" @click="selectManzanas(buscar,b_etapa)"> 
                                        <option value="">Etapa</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                    </select>

                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_manzana" >
                                        <option value="">Seleccione</option>
                                        <option v-for="manzana in arrayManzanas" :key="manzana.manzana" :value="manzana.manzana" v-text="manzana.manzana"></option>
                                    </select>

                                    <input type="text" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_lote" class="form-control" placeholder="Lote a buscar">

                                    <input v-else type="text"  v-model="buscar" @keyup.enter="listarContratos(1, buscar, b_etapa, b_manzana, b_lote, criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarContratos(1, buscar, b_etapa, b_manzana, b_lote, criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                   
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr> 
                                        
                                        <th># Ref</th>
                                        <th>Cliente</th>
                                       
                                        <th>Proyecto</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th>Lote</th>
                                        <th>Depositos</th>
                                        <th>Fecha cancelación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="contratos in arrayContratos" :key="contratos.id" v-on:dblclick="abrirModal('devolucion',contratos)"> 
                                    <template v-if="(contratos.sumaPagares - contratos.sumaRestante) > 0">
                                        <td class="td2" v-text="contratos.id"></td>
                                        <td class="td2" v-text="contratos.nombre_cliente"></td>
                                        <td class="td2" v-text="contratos.proyecto"></td>
                                        <td class="td2" v-text="contratos.etapa"></td>
                                        <td class="td2" v-text="contratos.manzana"></td>
                                        <td class="td2" v-text="contratos.num_lote"></td>
                                        <td class="td2" v-text="contratos.sumaPagares - contratos.sumaRestante"></td>
                                        <td class="td2" v-text="this.moment(contratos.fecha_status).locale('es').format('DD/MMM/YYYY')"></td>
                                    </template>
                                    </tr>                               
                                </tbody>
                            </table>  
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page > 7">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(1,buscar, b_etapa, b_manzana, b_lote, criterio)">Inicio</a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar, b_etapa, b_manzana, b_lote, criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar, b_etapa, b_manzana, b_lote, criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar, b_etapa, b_manzana, b_lote, criterio)">Sig</a>
                                </li>
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page<pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.last_page,buscar, b_etapa, b_manzana, b_lote, criterio)">Ultimo</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
         

            <!--Inicio del modal -->
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
                            <!-- form para solicitud de avaluo -->
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Proyecto</label>
                                    <div class="col-md-3">
                                        <input type="text" disabled v-model="proyecto" class="form-control" >
                                    </div>

                                    <label class="col-md-1 form-control-label" for="text-input">Etapa</label>
                                    <div class="col-md-2">
                                        <input type="text" disabled v-model="etapa" class="form-control">
                                    </div>

                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Manzana</label>
                                    <div class="col-md-3">
                                        <input type="text" disabled v-model="manzana" class="form-control" >
                                    </div>

                                    <label class="col-md-1 form-control-label" for="text-input">Lote</label>
                                    <div class="col-md-2">
                                        <input type="text" disabled v-model="lote" class="form-control">
                                    </div>

                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Cliente</label>
                                    <div class="col-md-8">
                                        <input type="text" disabled v-model="cliente" class="form-control" >
                                    </div>
                                </div>

                                <div class="form-group row line-separator"></div>

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Depositos</label>
                                    <div class="col-md-4">
                                        <h6><strong> ${{ formatNumber(depositos)}} </strong></h6>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Otro cargo</label>
                                    <div class="col-md-3">
                                        <input type="text" disabled v-model="concepto" class="form-control" >
                                    </div>

                                    <label class="col-md-2 form-control-label" for="text-input">Monto</label>
                                    <div class="col-md-4">
                                        <input type="text" disabled v-model="monto_cargo" class="form-control" >
                                        <h6><strong> ${{ formatNumber(monto_cargo)}} </strong></h6>
                                    </div>
                                </div>

                                <div class="form-group row" v-if="arrayGastos.length">
                                    <div class="col-md-12">
                                        <h6 align="center"><strong> GASTOS ADMINISTRATIVOS </strong></h6>
                                    </div>
                                </div>

                                <div v-if="arrayGastos.length">
                                        <div class="form-group row"  v-for="gasto in arrayGastos" :key="gasto.id">
                                            <label class="col-md-2 form-control-label" for="text-input" v-text="gasto.concepto"></label>
                                            <div class="col-md-3">
                                                <h6>${{ formatNumber(gasto.costo)}}</h6>
                                            </div>
                                        </div>
                                    </div>

                                <div class="form-group row line-separator"></div>

                                <div class="form-group row"> 
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha</label>
                                    <div class="col-md-3">
                                        <input type="date" v-model="fecha_devolucion" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row"> 
                                    <label class="col-md-3 form-control-label" for="text-input"># Cheque</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="cheque" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Cuenta de Banco</label>
                                    <div class="col-md-6">
                                        <select class="form-control" v-model="banco">
                                            <option value="">Seleccione</option>
                                            <option v-for="banco in arrayBancos" :key="banco.num_cuenta" :value="banco.num_cuenta + '-' + banco.banco" v-text="banco.num_cuenta + '-' + banco.banco"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Observaciones</label>
                                    <div class="col-md-9">
                                    <textarea rows="3" cols="30" v-model="observaciones" class="form-control" placeholder="Observaciones"></textarea>
                                    </div>
                                </div>

                                
                                
                                
                            </form>
                            <!-- fin del form solicitud de avaluo -->


                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button v-if="tipoAccion==1" type="button" class="btn btn-primary" @click="asignarGestor()">Asignar</button>
                        </div>
                    </div>
                      <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal consulta-->
            
         
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
                id: 0,
                
                gestor_id: 0,

                arrayContratos : [],
                arrayFraccionamientos:[],
                arrayEtapas:[],
                arrayManzanas:[],
                arrayBancos : [],
                arrayGastos : [],

                modal : 0,
                depositos : 0,
                proyecto : '',
                etapa: '',
                manzana: '',
                lote: '',
                cliente: '',
                banco:'',
                fecha_devolucion:'',
                cheque:'',
                observaciones: '',
                
                tituloModal : '',
           
                tipoAccion: 0,
                errorLote : 0,
                errorMostrarMsjLote : [],
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'lotes.fraccionamiento_id', 
                buscar : '',
                b_etapa: '',
                b_manzana: '',
                b_lote: ''
               
            }
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
            listarContratos(page, buscar, b_etapa, b_manzana, b_lote, criterio){
                let me = this;
                var url = '/devolucion/index?page=' + page + '&buscar=' + buscar + '&b_etapa=' + b_etapa + '&b_manzana=' + b_manzana + '&b_lote=' + b_lote +  '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayContratos = respuesta.contratos.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },

            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
        
            
            selectFraccionamientos(){
                let me = this;
                me.buscar=""
                
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
                me.buscar2=""
                
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

            selectManzanas(buscar1, buscar2){
                let me = this;
                me.b_manzana="";
                me.b_lote="";

                me.arrayManzanas=[];
                var url = '/select_manzanas_etapa?buscar=' + buscar1 + '&buscar1='+ buscar2;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayManzanas = respuesta.manzana;
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

            listarGastos(){
                let me = this;
                var url = '/expediente/gastosExpediente?folio=' + this.id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayGastos = respuesta.gastos;
                    me.totalGastos = respuesta.totalGastos[0].sumGasto;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },


            cambiarPagina(page,buscar, b_etapa, b_manzana, b_lote, criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarContratos(page,buscar, b_etapa, b_manzana, b_lote, criterio);
            },
       

            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                
            },
        
      
            abrirModal(accion,data =[]){
                
                switch(accion){
                    
                    case 'devolucion':
                    {
                        this.modal =1;
                        this.tituloModal='Generar devolución';
                        this.tipoAccion = 1;
                        this.id = data['id'];
                        this.proyecto = data['proyecto'];
                        this.etapa = data['etapa'];
                        this.manzana = data['manzana'];
                        this.lote = data['num_lote'];
                        this.cliente = data['nombre_cliente'];
                        this.depositos = data['sumaPagares'] - data['sumaRestante'];

                        this.listarGastos();
                        this.selectCuenta();
                        break;
                    }               
                }
            }
            
        
        },
       
        mounted() {
            this.listarContratos(1,this.buscar,this.b_etapa,this.b_manzana,this.b_lote,this.criterio);
            this.selectFraccionamientos();
            
        }
    }
</script>
<style>
    .line-separator{
        height:1px;
        background:#717171;
        border-bottom:1px solid #c2cfd6;
    }
    .form-control:disabled, .form-control[readonly] {
        background-color: rgba(0, 0, 0, 0.06);
        opacity: 1;
        font-size: 1rem;
        color: #27417b;
    }
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

    .badge2 {
    display: inline-block;
    padding: 0.25em 0.4em;
    font-size: 90%;
    font-weight: bold;
    line-height: 1;
    color: #fff;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
}

    /*th {
    text-align: left;
    background-color: rgb(190, 220, 250);
    text-transform: uppercase;
    padding-top: 1rem;
    padding-bottom: 1rem;
    border-bottom: rgb(50, 50, 100) solid 2px;
    border-top: none;
    }*/

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
