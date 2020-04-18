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
                        <!--   Boton Nuevo    -->
                        <a class="btn btn-success" v-bind:href="'/reprotes/reporteVentasExcel?mes=' + mes + '&anio=' + anio">
                            <i class="fa fa-file-text"></i>&nbsp; Excel
                        </a>
                        <!-- <a :href="'/etapa/excel?buscar=' + buscar + '&buscar2=' + buscar2 + '&criterio=' + criterio"  class="btn btn-success"><i class="fa fa-file-text"></i> Excel </a> -->
                        <!---->
                    </div>
                    <div class="card-body">
                         <ul class="nav nav2 nav-tabs" id="myTab1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active show" id="expediente-tab" data-toggle="tab" href="#expediente" role="tab" aria-controls="expediente" v-text="'Expedientes entregados'"></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="cobranza-tab" data-toggle="tab" href="#cobranza" role="tab" aria-controls="cobranza" aria-selected="false" v-text="'Acumulado de cobranza'"></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="escrituras-tab" data-toggle="tab" href="#escrituras" role="tab" aria-controls="escrituras" aria-selected="false" v-text="'Escrituras'"></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="ingresos-tab" data-toggle="tab" href="#ingresos" role="tab" aria-controls="ingresos" aria-selected="false" v-text="'Ingresos'"></a>
                            </li>
                        </ul>

                        <div>
                            <div class="form-group row">
                                <div class="col-md-8">
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
                                                <th colspan="7" class="text-center">Reporte de Expedientes Entregados </th>
                                            </tr>
                                            <tr></tr>
                                            <tr>
                                                <th colspan="2" class="text-center"> </th>
                                                <th colspan="4" class="text-center"> Ubicación / Plantio </th>
                                                <th class="text-center"> </th>
                                            </tr>
                                            <tr>
                                                <th># Referencia</th>
                                                <th>Cliente</th>
                                                <th>Fraccionamiento</th>
                                                <th>Etapa</th>
                                                <th>Manzana</th>
                                                <th>Lote</th>
                                                <th>Canal de Ventas</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="lote in arrayExpCreditos" :key="lote.id">
                                                <td class="td2" v-text="lote.proyecto"></td>
                                                <td class="td2" v-text="lote.num_etapa"></td>
                                                <td class="td2" v-text="lote.manzana"></td>
                                                <td class="td2" v-text="lote.num_lote"></td>
                                                <td class="td2" v-text="lote.nombre + ' ' + lote.apellidos"></td>
                                                <td class="td2" v-text="lote.fecha"></td>
                                                <td class="td2" v-text="lote.tipo_credito"></td>
                                                
                                            </tr>                             
                                        </tbody>
                                    </table>

                                    
                                </div>
                            </div>

                            <!-- Listado por cobranza -->
                            <div class="tab-pane fade" id="cobranza" role="tabpanel" aria-labelledby="cobranza-tab">

                                <div class="table-responsive">
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th colspan="11" class="text-center"> Cancelaciones en el periodo </th>
                                            </tr>
                                            <tr>
                                                <th>Fraccionamiento</th>
                                                <th>Etapa</th>
                                                <th>Manzana</th>
                                                <th>Lote</th>
                                                <th>Cliente</th>
                                                <th>Fecha de cancelación</th>
                                                <th>Fecha de venta</th>
                                                <th>Crédito</th>
                                                <th>Institución</th>
                                                <th>Valor de escrituración</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="cancelacion in arrayCancelaciones" :key="cancelacion.id">
                                                <td class="td2" v-text="cancelacion.proyecto"></td>
                                                <td class="td2" v-text="cancelacion.num_etapa"></td>
                                                <td class="td2" v-text="cancelacion.manzana"></td>
                                                <td class="td2" v-text="cancelacion.num_lote"></td>
                                                <td class="td2" v-text="cancelacion.nombre + ' ' + cancelacion.apellidos"></td>
                                                <td class="td2" v-text="cancelacion.fecha_status"></td>
                                                <td class="td2" v-text="cancelacion.fecha"></td>
                                                <td class="td2" v-text="cancelacion.tipo_credito"></td>
                                                <td class="td2" v-text="cancelacion.institucion"></td>
                                                <td class="td2" v-text="'$'+formatNumber(cancelacion.precio_venta)"></td>
                                            </tr>                             
                                        </tbody>
                                    </table>

                                    
                                </div>
                            </div>

                            <!-- Listado por escrituras -->
                            <div class="tab-pane fade" id="escrituras" role="tabpanel" aria-labelledby="escrituras-tab">

                                <div class="table-responsive">
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th colspan="10" class="text-center"> Reporte Mensual de Escrituras Ventas de Crédito </th>
                                            </tr>
                                            <tr></tr>
                                            <tr>
                                                <th colspan="2"></th>
                                                <th colspan="4" class="text-center">Ubicación</th>
                                                <th colspan="4"></th>
                                            </tr>
                                            <tr>
                                                <th># Referencia</th>
                                                <th>Cliente</th>
                                                <th>Fraccionamiento</th>
                                                <th>Etapa</th>
                                                <th>Manzana</th>
                                                <th>Lote</th>
                                                <th>Crédito</th>
                                                <th>Fecha de firma de escrituras</th>
                                                <th>Valor de escrituración</th>
                                                <th>Notaria</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="escrituras in arrayEscrituras" :key="escrituras.id">
                                                <td class="td2" v-text="escrituras.id"></td>
                                                <td class="td2" v-text="escrituras.nombre + ' '+escrituras.apellidos"></td>
                                                <td class="td2" v-text="escrituras.proyecto"></td>
                                                <td class="td2" v-text="escrituras.num_etapa"></td>
                                                <td class="td2" v-text="escrituras.manzana"></td>
                                                <td class="td2" v-text="escrituras.num_lote"></td>
                                                <td class="td2" v-text="escrituras.tipo_credito+' ('+escrituras.institucion+')'"></td>
                                                <td class="td2" v-text="escrituras.fecha_firma_esc"></td>
                                                <td class="td2" v-text="'$'+formatNumber(escrituras.valor_escrituras)"></td>
                                                <td class="td2" v-text="escrituras.notaria"></td>
                                                
                                            </tr>                             
                                        </tbody>
                                    </table>

                                    
                                </div>
                            </div>

                            <!-- Listado por ingresos -->
                            <div class="tab-pane fade" id="ingresos" role="tabpanel" aria-labelledby="ingresos-tab">

                                <div class="table-responsive">
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th colspan="11" class="text-center"> Cancelaciones en el periodo </th>
                                            </tr>
                                            <tr>
                                                <th>Fraccionamiento</th>
                                                <th>Etapa</th>
                                                <th>Manzana</th>
                                                <th>Lote</th>
                                                <th>Cliente</th>
                                                <th>Fecha de cancelación</th>
                                                <th>Fecha de venta</th>
                                                <th>Crédito</th>
                                                <th>Institución</th>
                                                <th>Valor de escrituración</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="cancelacion in arrayCancelaciones" :key="cancelacion.id">
                                                <td class="td2" v-text="cancelacion.proyecto"></td>
                                                <td class="td2" v-text="cancelacion.num_etapa"></td>
                                                <td class="td2" v-text="cancelacion.manzana"></td>
                                                <td class="td2" v-text="cancelacion.num_lote"></td>
                                                <td class="td2" v-text="cancelacion.nombre + ' ' + cancelacion.apellidos"></td>
                                                <td class="td2" v-text="cancelacion.fecha_status"></td>
                                                <td class="td2" v-text="cancelacion.fecha"></td>
                                                <td class="td2" v-text="cancelacion.tipo_credito"></td>
                                                <td class="td2" v-text="cancelacion.institucion"></td>
                                                <td class="td2" v-text="'$'+formatNumber(cancelacion.precio_venta)"></td>
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

        </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    export default {
        data(){
            return{
               
                arrayExpCreditos:[],
                arrayExpContado:[],
                arrayPendientes:[],

                arrayEscrituras:[],

                arrayCancelaciones : [],
                mes:'',
                anio:''
            }
        },
        computed:{
        },
        methods : {
            /**Metodo para mostrar los registros */
            listarReporte(){
                let me = this;
                var url = '/reprotes/reporteAcumulado?mes=' + me.mes + '&anio=' + me.anio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayExpCreditos = respuesta.expCreditos;
                    me.arrayExpContado = respuesta.expContado;
                    me.arrayPendientes = respuesta.pendientes;
                    me.arrayEscrituras = respuesta.escrituras;

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
