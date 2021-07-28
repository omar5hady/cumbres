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
                        <i class="fa fa-align-justify"></i> Inventario Contable
                        <!--   Boton Nuevo    -->
                        
                        <!-- <a :href="'/etapa/excel?buscar=' + buscar + '&buscar2=' + buscar2 + '&criterio=' + criterio"  class="btn btn-success"><i class="fa fa-file-text"></i> Excel </a> -->
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-10">
                                <div class="input-group">
                                    <select class="form-control" v-model="b_empresa" >
                                        <option value="">Empresa constructora</option>
                                        <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                    </select>
                                    <select class="form-control" v-model="b_empresa2" >
                                        <option value="">Empresa terreno</option>
                                        <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                    </select>
                                </div>
                                <div class="input-group">
                                    <select class="form-control" v-model="b_proyecto" @change="selectEtapa(b_proyecto)">
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>

                                    <select class="form-control" v-model="b_etapa"> 
                                        <option value="">Etapa</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                    </select>
                                </div>
                                <div class="input-group">
                                    <input type="date" v-model="b_fecha1" @keyup.enter="listarDescargas()" class="form-control" >
                                    <input type="date" v-model="b_fecha2" @keyup.enter="listarDescargas()" class="form-control" >
                                    
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <button type="submit" @click="listarDescargas()" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive" v-if="vista == 1">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th colspan="3"></th>
                                        <th colspan="2" class="text-center">Descargas</th>
                                        <th colspan="2"></th>
                                    </tr>
                                    <tr>
                                        <th>Fraccionamiento</th>
                                        <th>Etapa</th>
                                        <th>Total Lotes</th>
                                        <th v-text="'Al ' + fechaAnt"></th>
                                        <th v-text="'Del '+fecha1+' al '+ fecha2"></th>
                                        <th>Total de descargas</th>
                                        <th>Inventario</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="lote.totalLotes != 0" v-for="lote in arrayLotes" :key="lote.id">
                                        <td class="td2" v-text="lote.proyecto"></td>
                                        <td class="td2" v-text="lote.num_etapa"></td>
                                        <td class="td2" v-text="lote.totalLotes"></td>
                                        <td class="td2" v-text="lote.descAnt"></td>
                                        <td class="td2" v-text="lote.descAct"></td>
                                        <td class="td2" v-text="lote.totalDescarga"></td>
                                        <td class="td2" v-text="lote.inventario"></td>
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
                b_empresa:'',
                b_empresa2:'',
                empresas:[],
                b_etapa : '',
                fecha1 : '',
                fecha2 : '',
                fechaAnt : '',
                arrayLotes : [],
                arrayFraccionamientos:[],
                arrayEtapas:[],
                criterio : '',
                vista:0,
            }
        },
        computed:{
        },
        methods : {
            /**Metodo para mostrar los registros */
            listarDescargas(){
                let me = this;
                var url = '/reprotes/inventario?fecha1=' + me.b_fecha1 + '&fecha2=' + me.b_fecha2 + '&proyecto=' + me.b_proyecto 
                        + '&etapa=' + me.b_etapa + '&empresa=' + me.b_empresa + '&empresa2=' + me.b_empresa2;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLotes = respuesta.resumen;
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
            this.selectFraccionamientos();
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
