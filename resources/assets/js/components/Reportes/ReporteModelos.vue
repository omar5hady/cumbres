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
                        <i class="fa fa-align-justify"></i> Reporte por modelo de casas &nbsp;&nbsp;
                        <!--   Boton Nuevo    -->
                         <!-- <a class="btn btn-success" v-bind:href="'/reprotes/excelReporteRecursosPropios'">
                            <i class="fa fa-file-text"></i>&nbsp; Excel
                        </a> -->
                        <!---->
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
                                        <th colspan="12" class="text-center"> Reporte al {{fecha}} </th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th>Modelo</th>
                                        <th>Total de casas</th>
                                        <th>Individualizadas</th>
                                        <th>Vendidas Proceso</th>
                                        <th>Vendidas Terminadas</th>
                                        <th>Vendidas</th>
                                        <th>Total vendidas</th>
                                        <th>Disponible Proceso</th>
                                        <th>Disponible Terminadas</th>
                                        <th>Disponibles</th>
                                        <th>Inventario</th>
                                        <th v-if="meses != 0">Promedio mensual de venta</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(lote,index) in arrayLotes" :key="lote.id">
                                        <template>
                                            <td v-text="index+1"></td>
                                            <td class="td2" v-text="lote.nombre"></td>
                                            <td class="td2 text-center" v-text="lote.total"></td>
                                            <td class="td2 text-center" v-text="lote.indiv"></td>
                                            <td class="td2 text-center" v-text="lote.vendidaProc"></td>
                                            <td class="td2 text-center" v-text="lote.vendidaTerm"></td>
                                            <td class="td2 text-center" v-text="lote.vendida"></td>
                                            <td class="td2 text-center" v-text="lote.total_vendidas"></td>
                                            <td class="td2 text-center" v-text="lote.disponibleProc"></td>
                                            <td class="td2 text-center" v-text="lote.disponibleTerm"></td>
                                            <td class="td2 text-center" v-text="lote.disponible"></td>
                                            <td class="td2 text-center" v-text="(lote.disponible + lote.vendida)"></td>
                                            <td v-if="meses != 0" class="td2 text-center"> {{((lote.vendida + lote.indiv)/meses).toFixed(2)}} </td>
                                            
                                        </template>
                                        
                                    </tr>                             
                                </tbody>
                                
                                
                            </table>
                        </div>
                        
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
                meses : 0,
               
                fecha:'',
                proyecto:'',
                b_etapa:'',
                
            }
        },
        computed:{
            

        },
        methods : {
            /**Metodo para mostrar los registros */
            listarReporte(page){
                let me = this;
                var url = '/reportes/reporteModelos?fraccionamiento=' + this.proyecto + '&etapa=' + this.b_etapa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLotes = respuesta.modelos;

                    me.meses = respuesta.diferencia;
                   
                    me.fecha = moment().locale('es').format('DD-MMMM-YYYY');

                    me.arrayLotes.sort((b, a) => a.total_vendidas - b.total_vendidas);

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
