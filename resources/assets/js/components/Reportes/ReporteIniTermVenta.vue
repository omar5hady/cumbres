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
                        <i class="fa fa-align-justify"></i> Reporte de Inicio, Termino, Ventas y Cobranza &nbsp;&nbsp;
                        <!--   Boton Nuevo    -->
                         <a class="btn btn-success" v-bind:href="'/reprotes/excelReporteLotesVentas'">
                            <i class="fa fa-file-text"></i>&nbsp; Excel
                        </a>
                        <!-- <a :href="'/etapa/excel?buscar=' + buscar + '&buscar2=' + buscar2 + '&criterio=' + criterio"  class="btn btn-success"><i class="fa fa-file-text"></i> Excel </a> -->
                        <!---->
                    </div>
                    <div class="card-body">
                        
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">

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
                                        <th>Fraccionamiento</th>
                                        <th>Etapa</th>
                                        <th>Total Casas o Lotes</th>
                                        <th>Cobradas</th>
                                        <th>Terminadas No Cobradas</th>
                                        <th>Terminadas Vendidas No Cobradas</th>
                                        <th>Terminadas Disponibles</th>
                                        <th>En Proceso de Construccion No Cobradas</th>
                                        <th>En Proceso Vendidas No Cobradas</th>
                                        <th>En Proceso Disponible</th>
                                        <th>Disponibles Sin Avance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="lote in arrayLotes" :key="lote.id">
                                        <template v-if="lote.lotes != 0">
                                            <td class="td2" v-text="lote.proyecto"></td>
                                            <td class="td2" v-text="lote.num_etapa"></td>
                                            <td class="td2 text-center" v-text="lote.lotes"></td>
                                            <td class="td2 text-center" v-text="lote.cobradas"></td>
                                            <td class="td2 text-center" v-text="lote.terminadaNoCobrada"></td>
                                            <td class="td2 text-center" v-text="lote.termVendidaNoCobrada"></td>
                                            <td class="td2 text-center" v-text="lote.terminadaDisponible"></td>
                                            <td class="td2 text-center" v-text="lote.procesoNoCobrada"></td>
                                            <td class="td2 text-center" v-text="lote.procVendidaNoCobrada"></td>
                                            <td class="td2 text-center" v-text="lote.procesoDisponible"></td>
                                            <td class="td2 text-center" v-text="lote.sinAvanceDisponible"></td>
                                        </template>
                                        
                                    </tr>                             
                                </tbody>
                                
                                <thead>
                                    <tr>
                                        <th colspan="2">Total</th>
                                        <th v-text="total1" class="td2 text-center"></th>
                                        <th v-text="total2" class="td2 text-center"></th>
                                        <th v-text="total3" class="td2 text-center"></th>
                                        <th v-text="total4" class="td2 text-center"></th>
                                        <th v-text="total5" class="td2 text-center"></th>
                                        <th v-text="total6" class="td2 text-center"></th>
                                        <th v-text="total7" class="td2 text-center"></th>
                                        <th v-text="total8" class="td2 text-center"></th>
                                        <th v-text="total9" class="td2 text-center"></th>
                                    </tr>       
                                </thead>
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
                total1:0,
                total2:0,
                total3:0,
                total4:0,
                total5:0,
                total6:0,
                total7:0,
                total8:0,
                total9:0,
                fecha:'',
            }
        },
        computed:{
        },
        methods : {
            /**Metodo para mostrar los registros */
            listarReporte(){
                let me = this;
                var url = '/reprotes/reporteLotesVentas';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLotes = respuesta.lotes;
                    me.total1 = respuesta.total1;
                    me.total2 = respuesta.total2;
                    me.total3 = respuesta.total3;
                    me.total4 = respuesta.total4;
                    me.total5 = respuesta.total5;
                    me.total6 = respuesta.total6;
                    me.total7 = respuesta.total7;
                    me.total8 = respuesta.total8;
                    me.total9 = respuesta.total9;
                    
                    me.fecha = moment().locale('es').format('DD-MMMM-YYYY');

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
            this.listarReporte();
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
