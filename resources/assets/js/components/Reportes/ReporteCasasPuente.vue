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
                        <i class="fa fa-align-justify"></i> Casas con Crédito Puente &nbsp;&nbsp;
                        <!--   Boton Nuevo    -->
                         <!-- <a class="btn btn-success" v-bind:href="'/reprotes/excelReporteRecursosPropios'">
                            <i class="fa fa-file-text"></i>&nbsp; Excel
                        </a> -->
                       
                    </div>
                    <div class="card-body">
                        
                        <div class="form-group row">
                            <div class="col-md-7">
                                <div class="input-group">
                                    <select class="form-control" v-model="proyecto" @click="selectEtapa(proyecto)">
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>

                                    <select class="form-control" v-model="b_etapa"> 
                                        <option value="">Etapa</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <button type="submit" @click="listarReporte(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th colspan="11" class="text-center"> Reporte al {{fecha}} </th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th>Fraccionamiento</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th>Lote</th>
                                        <th>Estatus</th>
                                        <th>Avance</th>
                                        <th>Valor de venta</th>
                                        <th>Cobrado</th>
                                        <th>Por cobrar</th>
                                        <th>Credito Puente</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(lote,index) in arrayLotes" :key="lote.id">
                                        <template>
                                            <td v-text="index+1"></td>
                                            <td class="td2" v-text="lote.proyecto"></td>
                                            <td class="td2" v-text="lote.num_etapa"></td>
                                            <td class="td2" v-text="lote.manzana"></td>
                                            <td class="td2" v-text="lote.num_lote"></td>
                                            <td class="td2">
                                                <span v-if="lote.status == 0" class="badge badge-success">Disponible</span>
                                                <span v-if="lote.status == 1" class="badge badge-dark">Vendida</span>
                                            </td>
                                            <td class="td2" v-text="lote.avance + '%'"></td>
                                            <td class="td2" v-text="'$'+formatNumber(lote.valor_venta)"></td>
                                            <td class="td2" v-text="'$'+formatNumber(lote.cobrado)"></td>
                                            <td class="td2" v-text="'$'+formatNumber(lote.porCobrar)"></td>
                                            <td class="td2" v-text="lote.credito_puente"></td>
                                        </template>
                                        
                                    </tr>                             
                                </tbody>
                                
                                <thead>
                                    <tr>
                                        <th colspan="7">Total</th>
                                        <th v-text="'$'+formatNumber(total1)" class="td2 text-center"></th>
                                        <th v-text="'$'+formatNumber(total2)" class="td2 text-center"></th>
                                        <th v-text="'$'+formatNumber(total3)" class="td2 text-center"></th>
                                        <th></th>
                                    </tr>       
                                </thead>
                            </table>
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page > 7">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(1)">Inicio</a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1)">Sig</a>
                                </li>
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page<pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.last_page)">Ultimo</a>
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
               
                arrayLotes : [],
                arrayFraccionamientos:[],
                arrayEtapas:[],
                total1:0,
                total2:0,
                total3:0,
                fecha:'',
                proyecto:'',
                b_etapa:'',
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
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
            listarReporte(page){
                let me = this;
                var url = '/reprotes/reporteCasasCreditoPuente?page=' + page + '&proyecto=' + this.proyecto + '&etapa=' + this.b_etapa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLotes = respuesta.lotes.data;
                    me.pagination = respuesta.pagination;

                    me.total1 = respuesta.suma1;
                    me.total2 = respuesta.suma2;
                    me.total3 = respuesta.suma3;
                    
                    
                    me.fecha = moment().locale('es').format('DD-MMMM-YYYY');

                })
                .catch(function (error) {
                    console.log(error);
                });
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
                me.b_etapa=""
                
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

            cambiarPagina(page){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarReporte(page);
            },

            
            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
        
        },
        mounted() {
            this.listarReporte(1);
            this.selectFraccionamientos();
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
