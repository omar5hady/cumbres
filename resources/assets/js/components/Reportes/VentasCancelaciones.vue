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
                        <i class="fa fa-align-justify"></i> Reporte de Ventas y Cancelaciones &nbsp;&nbsp;
                        <!--   Boton Nuevo    -->
                        <a class="btn btn-success" v-bind:href="'/reprotes/reporteVentasExcel?fecha=' + fecha + '&fecha2=' + fecha2">
                            <i class="fa fa-file-text"></i>&nbsp; Excel
                        </a>
                        <!-- <a :href="'/etapa/excel?buscar=' + buscar + '&buscar2=' + buscar2 + '&criterio=' + criterio"  class="btn btn-success"><i class="fa fa-file-text"></i> Excel </a> -->
                        <!---->
                    </div>
                    <div class="card-body">
                         <ul class="nav nav2 nav-tabs" id="myTab1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active show" id="venta-tab" data-toggle="tab" href="#venta" role="tab" aria-controls="venta" v-text="'Ventas'"></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="cancelacion-tab" data-toggle="tab" href="#cancelacion" role="tab" aria-controls="cancelacion" aria-selected="false" v-text="'Cancelaciones'"></a>
                            </li>
                        </ul>

                        <div class="tab-content" id="myTab1Content">

                            <!-- Listado por Ventas -->
                            <div class="tab-pane fade active show" id="venta" role="tabpanel" aria-labelledby="venta-tab">
                        
                                <div class="form-group row">
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input type="date"  v-model="fecha" @keyup.enter="listarReporte()" class="form-control" placeholder="Fecha inicial">
                                            <input type="date"  v-model="fecha2" @keyup.enter="listarReporte()" class="form-control" placeholder="Fecha final">
                                            <button type="submit" @click="listarReporte()" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th colspan="11" class="text-center"> Ventas en el periodo ({{cont1}}) </th>
                                            </tr>
                                            <tr>
                                                <th>Fraccionamiento</th>
                                                <th>Etapa</th>
                                                <th>Manzana</th>
                                                <th>Lote</th>
                                                <th>Cliente</th>
                                                <th>Fecha de venta</th>
                                                <th>Crédito</th>
                                                <th>Institución</th>
                                                <th>Promocion/paquete</th>
                                                <th>Valor de escrituración</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="lote in arrayLotes" :key="lote.id" v-bind:style="{ backgroundColor : !lote.status == 0 ? '#FFFFFF' : '#D23939'}">
                                                <td class="td2" v-text="lote.proyecto"></td>
                                                <td class="td2" v-text="lote.num_etapa"></td>
                                                <td class="td2" v-text="lote.manzana"></td>
                                                <td class="td2" v-text="lote.num_lote"></td>
                                                <td class="td2" v-text="lote.nombre + ' ' + lote.apellidos"></td>
                                                <td class="td2" v-text="lote.fecha"></td>
                                                <td class="td2" v-text="lote.tipo_credito"></td>
                                                <td class="td2" v-text="lote.institucion"></td>
                                                <template>
                                                    <td class="td2" v-if="lote.descripcion_promocion == null && lote.descripcion_paquete == null" v-text="''"></td>
                                                    <td class="td2" v-else-if="lote.descripcion_promocion != null && lote.descripcion_paquete == null" v-text="'Promo: '+lote.descripcion_promocion"></td>
                                                    <td class="td2" v-else-if="lote.descripcion_promocion == null && lote.descripcion_paquete != null" v-text="'Paquete: '+lote.descripcion_paquete"></td>
                                                    <td class="td2" v-else v-text="'Promo: ' + lote.descripcion_promocion + ' / Paquete:' + lote.descripcion_paquete"></td>
                                                </template>
                                                <td class="td2" v-text="'$'+formatNumber(lote.precio_venta)"></td>
                                            </tr>                             
                                        </tbody>
                                    </table>

                                    
                                </div>
                            </div>

                            <!-- Listado por Cancelaciones -->
                            <div class="tab-pane fade" id="cancelacion" role="tabpanel" aria-labelledby="cancelacion-tab">
                        
                                <div class="form-group row">
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input type="date"  v-model="fecha" @keyup.enter="listarReporte()" class="form-control" placeholder="Fecha inicial">
                                            <input type="date"  v-model="fecha2" @keyup.enter="listarReporte()" class="form-control" placeholder="Fecha final">
                                            <button type="submit" @click="listarReporte()" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th colspan="11" class="text-center"> Cancelaciones en el periodo ({{cont2}}) </th>
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
                                                <th>Promoción / Paquete</th>
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
                                                <template>
                                                    <td class="td2" v-if="cancelacion.descripcion_promocion == null && cancelacion.descripcion_paquete == null" v-text="''"></td>
                                                    <td class="td2" v-else-if="cancelacion.descripcion_promocion != null && cancelacion.descripcion_paquete == null" v-text="'Promo: '+cancelacion.descripcion_promocion"></td>
                                                    <td class="td2" v-else-if="cancelacion.descripcion_promocion == null && cancelacion.descripcion_paquete != null" v-text="'Paquete: '+cancelacion.descripcion_paquete"></td>
                                                    <td class="td2" v-else v-text="'Promo: ' + cancelacion.descripcion_promocion + ' / Paquete:' + cancelacion.descripcion_paquete"></td>
                                                </template>
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
               
                arrayLotes : [],
                arrayCancelaciones : [],
                fecha:'',
                fecha2:''
            }
        },
        computed:{
        },
        methods : {
            /**Metodo para mostrar los registros */
            listarReporte(){
                let me = this;
                var url = '/reprotes/reporteVentas?fecha=' + me.fecha + '&fecha2=' + me.fecha2;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLotes = respuesta.ventas;
                    me.arrayCancelaciones = respuesta.cancelaciones;
                    me.cont1 = respuesta.contVentas;
                    me.cont2 = respuesta.contCancelaciones;

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
