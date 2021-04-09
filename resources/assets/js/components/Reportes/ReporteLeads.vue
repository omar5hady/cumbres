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
                        <i class="fa fa-align-justify"></i> Reporte Digital Leads  &nbsp;&nbsp;
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
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <input type="date" v-model="b_fecha1" @keyup.enter="listarReporte()" class="form-control" >
                                        <input type="date" v-model="b_fecha2" @keyup.enter="listarReporte()" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <select class="form-control" v-model="b_proyecto">
                                            <option value="">Seleccione</option>
                                            <option v-for="proyecto in arrayFraccionamientos" :key="proyecto.id" 
                                                :value="proyecto.id" v-text="proyecto.nombre">
                                            </option>
                                            <option value="0">Otro...</option>
                                        </select>
                                        <button type="submit" @click="listarReporte()" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        

                        <div class="form-group row">
                            <!-- Listado por ingresos -->
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th colspan="3" class="text-center">REPORTE POR CAMPAÑA</th>
                                            </tr>
                                            <tr>
                                                <th> Campaña</th>
                                                <th> # Leads</th>
                                                <th> Canalizados a asesor</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Tráfico organico</td>
                                                <td v-text="camp_org"></td>
                                                <td v-text="asesor_org"></td>
                                            </tr>  
                                            <tr v-if="leads.conteo > 0" v-for="leads in arrayLeads" :key="leads.id">
                                                <td class="td2" v-text="leads.nombre_campania+' ('+leads.medio_digital+')'"></td>
                                                <td class="td2" v-text="leads.conteo"></td>
                                                <td class="td2" v-text="leads.asesor"></td>
                                            </tr>     
                                                                  
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-md-1"></div>

                                
                            <div class="col-md-5">
                                <div class="table-responsive">
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th colspan="3" class="text-center">REPORTE POR ASESOR</th>
                                            </tr>
                                            <tr>
                                                <th> Asesor</th>
                                                <th> # Leads asignados</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="asesor in arrayAsesores" :key="asesor.id">
                                                <td class="td2" v-text="asesor.nombre+' '+asesor.apellidos"></td>
                                                <td class="td2" v-text="asesor.conteo"></td>
                                            </tr>     
                                                                  
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                        </div>

                        <div class="tab-content" id="myTab1Content">
                            <!-- Listado por ingresos -->
                            <div class="tab-pane fade active show" id="ingresos" role="tabpanel" aria-labelledby="ingresos-tab">

                                
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
               
                arrayLeads : [],
                arrayFraccionamientos : [],
                arrayAsesores : [],
                camp_org:0,
                asesor_org:0,
                b_fecha1:'',
                b_fecha2:'',
                b_proyecto:'',
                emp_constructora:''
            }
        },
        computed:{
        },
        methods : {
            /**Metodo para mostrar los registros */
            listarReporte(){
                let me = this;
                me.arrayLeads = [];
                var url = '/reportes/digitalLeads?fecha1=' + me.b_fecha1 + '&fecha2=' + me.b_fecha2 + 
                    '&proyecto=' + me.b_proyecto;

                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLeads = respuesta.campanias;
                    me.asesor_org = respuesta.asesor_org;
                    me.camp_org = respuesta.camp_org;
                    me.arrayAsesores = respuesta.asesores;

                    me.arrayLeads.sort((b, a) => a.conteo - b.conteo);
                    me.arrayAsesores.sort((b, a) => a.conteo - b.conteo);

                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectFraccionamientos(){
                let me = this;
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
