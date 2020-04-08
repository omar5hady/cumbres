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
                        <i class="fa fa-align-justify"></i> Reporte de Asesores
                        <!--   Boton Nuevo    -->
                        
                        <!-- <a :href="'/etapa/excel?buscar=' + buscar + '&buscar2=' + buscar2 + '&criterio=' + criterio"  class="btn btn-success"><i class="fa fa-file-text"></i> Excel </a> -->
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <select class="form-control" v-model="b_proyecto">
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>

                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="date" v-model="b_fecha1" @keyup.enter="listarDescargas()" class="form-control" >
                                    <input type="date" v-model="b_fecha2" @keyup.enter="listarDescargas()" class="form-control" >
                                    <button type="submit" @click="listarDescargas()" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive" v-if="vista == 1 && mostrar == 0">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th colspan="11" class="text-center">Periodo del X al X</th>
                                    </tr>
                                    <tr>
                                        <th>Vendedor</th>
                                        <th>Atendio</th>
                                        <th>Ventas</th>
                                        <th>Cancelaciones</th>
                                        <th>A</th>
                                        <th>B</th>
                                        <th>C</th>
                                        <th>N/V</th>
                                        <th>% Venta</th>
                                        <th>% Cancelación</th>
                                        <th>% Bateo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="vendedor in arrayVendedores" :key="vendedor.id">
                                        <td class="td2" v-text="vendedor.nombre + ' ' + vendedor.apellidos"></td>
                                        <td class="td2" v-text="vendedor.clientes"></td>
                                        <td class="td2" v-text="vendedor.ventas"></td>
                                        <td class="td2" v-text="vendedor.canceladas"></td>
                                        <td class="td2" v-text="vendedor.tipoA"></td>
                                        <td class="td2" v-text="vendedor.tipoB"></td>
                                        <td class="td2" v-text="vendedor.tipoC"></td>
                                        <td class="td2" v-text="vendedor.nv"></td>
                                        <td class="td2" v-text="vendedor.por_venta"></td>
                                        <td class="td2" v-text="vendedor.por_cancel"></td>
                                        <td class="td2" v-text="vendedor.por_bat"></td>
                                    </tr>                               
                                </tbody>
                            </table>
                        </div>

                        <div class="table-responsive" v-if="vista == 1 && mostrar == 1">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th colspan="11" class="text-center">Periodo del {{fecha1}}  al  {{fecha2}}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="2"></th>
                                        <th colspan="4" class="text-center">Ventas</th>
                                        <th colspan="8"></th>
                                    </tr>
                                    <tr>
                                        <th>Vendedor</th>
                                        <th>Atendio</th>
                                        <th>Ventas en el periodo</th>
                                        <th>Ventas en 30 dias</th>
                                        <th>Ventas en 60 dias</th>
                                        <th>Ventas en 90 dias</th>
                                        <th>Cancelaciones</th>
                                        <th>A</th>
                                        <th>B</th>
                                        <th>C</th>
                                        <th>N/V</th>
                                        <th>% Venta</th>
                                        <th>% Cancelación</th>
                                        <th>% Bateo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="vendedor in arrayVendedores" :key="vendedor.id">
                                        <td class="td2" v-text="vendedor.nombre + ' ' + vendedor.apellidos"></td>
                                        <td class="td2" v-text="vendedor.clientes"></td>
                                        <td class="td2" v-text="vendedor.ventas"></td>
                                        <td class="td2" v-text="vendedor.ventas30"></td>
                                        <td class="td2" v-text="vendedor.ventas60"></td>
                                        <td class="td2" v-text="vendedor.ventas90"></td>
                                        <td class="td2" v-text="vendedor.canceladas"></td>
                                        <td class="td2" v-text="vendedor.tipoA"></td>
                                        <td class="td2" v-text="vendedor.tipoB"></td>
                                        <td class="td2" v-text="vendedor.tipoC"></td>
                                        <td class="td2" v-text="vendedor.nv"></td>
                                        <td class="td2" >% V</td>
                                        <td class="td2" >% C</td>
                                        <td class="td2" >% B</td>
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
               
                b_fecha1 : '',
                b_fecha2 : '',
                b_proyecto : '',
                fecha1 : '',
                fecha2 : '',
                fechaAnt : '',
                arrayVendedores : [],
                arrayFraccionamientos:[],
                criterio : '',
                vista:0,
                mostrar:0
            }
        },
        computed:{
        },
        methods : {
            /**Metodo para mostrar los registros */
            listarDescargas(){
                let me = this;
                var url = '/reprotes/reporteVendedores?fecha1=' + me.b_fecha1 + '&fecha2=' + me.b_fecha2 + '&proyecto=' + me.b_proyecto;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayVendedores = respuesta.vendedores;
                    me.mostrar = respuesta.mostrar;
                    me.vista = 1;

                    var startdate = moment(me.b_fecha1);
                    startdate = startdate.subtract(1, "days");
                    me.fechaAnt = startdate.locale('es').format("DD/MMM/YYYY");
                    
                    me.fecha1 = moment(me.b_fecha1).locale('es').format('DD/MMM/YYYY');
                    me.fecha2 = moment(me.b_fecha2).locale('es').format('DD/MMM/YYYY');

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

            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
        
        },
        mounted() {
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
