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
                        <a v-if="b_fecha1 != '' && b_fecha2 != ''" :href="'/reprotes/excelIngresos?fecha1=' + b_fecha1 + '&fecha2=' + b_fecha2 + '&empresa=' + emp_constructora"  class="btn btn-success"><i class="fa fa-file-text"></i> Excel </a>
                       
                    </div>
                    <div class="card-body">
                         <ul class="nav nav2 nav-tabs" id="myTab1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active show" id="ingresos-tab" data-toggle="tab" href="#ingresos" role="tab" aria-controls="ingresos" v-text="'Ingresos'"></a>
                            </li>
                        </ul>

                        <div>
                            <div class="form-group row">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input type="date" v-model="b_fecha1" @keyup.enter="listarReporte(1)" class="form-control" >
                                        <input type="date" v-model="b_fecha2" @keyup.enter="listarReporte(1)" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <select class="form-control col-md-4" v-model="emp_constructora">
                                            <option value="">Empresa constructora</option>
                                            <option value="Grupo Constructor Cumbres">Grupo Constructor Cumbres</option>
                                            <option value="CONCRETANIA">CONCRETANIA</option>
                                        </select>
                                        <button type="submit" @click="listarReporte()" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        

                        <div class="tab-content" id="myTab1Content">

                            <!-- Listado por ingresos -->
                            <div class="tab-pane fade active show" id="ingresos" role="tabpanel" aria-labelledby="ingresos-tab">

                                <div class="table-responsive">
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th colspan="10" class="text-center"> Reporte de Ingresos (Cobranza Institucional) </th>
                                            </tr>
                                            <tr></tr>
                                            <tr>
                                                <th colspan="2"></th>
                                                <th colspan="4" class="text-center">Ubicación</th>
                                                <th colspan="4"></th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th>Cliente</th>
                                                <th>Fraccionamiento</th>
                                                <th>Etapa</th>
                                                <th>Manzana</th>
                                                <th>Lote</th>
                                                <th>Monto Credito Neto</th>
                                                <th>Fecha</th>
                                                <th>Banco</th>
                                                <th>Firma de escrituras</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(ingresoCobranza, index) in arrayIngresosCobranza" :key="ingresoCobranza.id">
                                                <td>{{index + 1}}</td>
                                                <td class="td2" v-text="ingresoCobranza.nombre.toUpperCase() + ' ' + ingresoCobranza.apellidos.toUpperCase()"></td>
                                                <td class="td2" v-text="ingresoCobranza.proyecto"></td>
                                                <td class="td2" v-text="ingresoCobranza.num_etapa"></td>
                                                <td class="td2" v-text="ingresoCobranza.manzana"></td>
                                                <td class="td2" v-text="ingresoCobranza.num_lote"></td>
                                                <td class="td2" v-text="'$'+formatNumber(ingresoCobranza.cant_depo)"></td>
                                                <td class="td2" v-text="ingresoCobranza.fecha_deposito"></td>
                                                <td class="td2" v-text="ingresoCobranza.banco"></td>
                                                <td class="td2" v-text="ingresoCobranza.escrituras"></td>
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
               
                arrayIngresosCobranza : [],
                b_fecha1:'',
                b_fecha2:'',
                emp_constructora:''
            }
        },
        computed:{
        },
        methods : {
            /**Metodo para mostrar los registros */
            listarReporte(){
                let me = this;

                me.arrayIngresosCobranza = [];

                var url = '/reprotes/reporteAcumulado?fecha1=' + me.b_fecha1 + '&fecha2=' + me.b_fecha2 + 
                    '&empresa=' + me.emp_constructora  + '&opcion=Ingresos';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;

                    me.arrayIngresosCobranza = respuesta.ingresosCobranza;

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
