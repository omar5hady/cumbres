<template>
    <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card scroll-box">
                    

                    <div class="card-header" v-if="deposito==2">
                        <i class="fa fa-align-justify"></i> Ingresos de enganches 
                        <!--   Boton Nuevo    -->
                        
                        <a :href="'/depositos/historial/excel?fecha1=' + b_fecha1 + '&fecha2=' + b_fecha2 + '&banco=' + banco + 
                                '&monto=' + b_deposito+'&b_empresa='+b_empresa"
                            class="btn btn-success"><i class="fa fa-file-text"></i> Excel Pagares
                        </a>
                        <!---->
                    </div>
                    
                    <div class="card-body" v-if="deposito==2">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <select class="form-control" v-model="b_empresa" >
                                    <option value="">Empresa constructora</option>
                                    <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <input type="date" v-model="b_fecha1" @keyup.enter="listarHistorialDep(1)" class="form-control" >
                                    <input type="date" v-model="b_fecha2" @keyup.enter="listarHistorialDep(1)" class="form-control" >
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <select class="form-control" v-model="banco">
                                        <option value="">Seleccione</option>
                                        <option v-for="banco in arrayBancos" :key="banco.num_cuenta + '-' + banco.banco" :value="banco.num_cuenta + '-' + banco.banco" v-text="banco.num_cuenta + '-' + banco.banco"></option>
                                    </select>
                                    
                                    <input type="text" pattern="\d*" v-on:keypress="isNumber($event)" v-model="b_deposito" maxlength="10" class="form-control" placeholder="Monto">
                                    <button type="submit" @click="listarHistorialDep(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th># Ref</th>
                                        <th>Cliente</th>
                                        <th>Proyecto</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th># Lote</th>
                                        <th>Fecha de deposito</th>
                                        <th>Cuenta</th>
                                        <th># Recibo</th>
                                        <th>$ Monto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="depo in arrayHistorial" :key="depo.depId">
                                        <td v-text="depo.id"></td>
                                        <td v-text="depo.nombre + ' ' +depo.apellidos"></td>
                                        <td v-text="depo.fraccionamiento"></td>
                                        <td v-text="depo.etapa"></td>
                                        <td v-text="depo.manzana"></td>
                                        <td v-text="depo.num_lote"></td>
                                        <td v-text="this.moment(depo.fecha_pago).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td v-text="depo.banco"></td>
                                        <td v-text="depo.num_recibo"></td>
                                        <td v-text="'$'+formatNumber(depo.cant_depo)"></td>
                                    </tr>                               
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination2.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page - 1)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber2" :key="page" :class="[page == isActived2 ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(page)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination2.current_page < pagination2.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page + 1)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>

                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            

        </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    export default {
        data(){
            return{
                hoy:moment(),
                proceso:false,
                id:0,
                
                arrayFraccionamientos : [],
                arrayEtapas : [],
                arrayManzana: [],
                
                arrayHistorial : [],
                arrayBancos : [],
                
                deposito : 2,
                

                disabled:0,

                

                
                pagination2 : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'creditos.fraccionamiento', 
                buscar : '',
                buscar2: '',
                buscar3:'',
                b_vencidos : 0,
                b_deposito : '',
                b_fecha1 : '',
                b_fecha2 : '',
                b_empresa:'',
                banco:'',
                empresas:[],
            }
        },
        computed:{
            
            
            isActived2: function(){
                return this.pagination2.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber2:function(){
                if(!this.pagination2.to){
                    return [];
                }

                var from = this.pagination2.current_page - this.offset;
                if(from < 1){
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.pagination2.last_page){
                    to = this.pagination2.last_page;
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
            listarHistorialDep(page){
                let me = this;
                me.deposito = 2;
                var url = '/depositos/historial?page=' + page + '&fecha1=' + me.b_fecha1 + '&fecha2=' + me.b_fecha2 + 
                    '&banco=' + me.banco + '&monto=' + me.b_deposito +'&b_empresa='+this.b_empresa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayHistorial = respuesta.depositos.data;
                    me.pagination2 = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
           
            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
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
            
            cambiarPagina2(page){
                let me = this;
                //Actualiza la pagina actual
                me.pagination2.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarHistorialDep(page);
            },
            selectFraccionamiento(){
                let me = this;
                me.buscar="";
                me.buscar2="";
                me.buscar3="";
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
            selectEtapa(buscar){
                let me = this;
                me.buscar2="";
                me.buscar3="";
                                
                me.arrayEtapas=[];
                var url = '/select_etapa?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEtapas = respuesta.etapas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectManzana(buscar,buscar2){
                let me = this;
                me.buscar3=""
                                
                me.arrayManzana=[];
                var url = '/selectManzana_dist?buscar=' + buscar +'&buscar2=' + buscar2;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayManzana = respuesta.manzanas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            getEmpresa(){
                let me = this;
                me.empresas=[];
                var url = '/lotes/empresa/select';
                axios.get(url).then(function (response) {
                    var respuesta = response;
                    me.empresas = respuesta.data.empresas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        },
        mounted() {
            this.listarHistorialDep(1);
            this.selectFraccionamiento();
            this.selectCuenta();
            this.getEmpresa();
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

</style>
