<template >
    <main class="main" >
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card scroll-box">
                    <div class="card-header">
                        <i class="fa fa-align-justify"> Reporte de Entregas</i>
                    </div>

                    <!-- listado de devoluciones -->
                    <div class="card-body">               
                        
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control" v-model="buscar"  @keyup.enter="getReporte()" @change="selectEtapa(buscar)">
                                        <option value="">Proyecto</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>
                                   
                                </div>
                                <div class="input-group">
                                    <select class="form-control"  v-model="b_etapa"  @keyup.enter="getReporte()"> 
                                        <option value="">Etapa</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                    </select>
                                </div>
                                <div class="input-group">
                                    <input type="text" disabled class="form-control col-md-4" placeholder="Fecha:">
                                    <input type="date" class="form-control col-md-4" v-model="b_fecha1">
                                    <input type="date" class="form-control col-md-4" v-model="b_fecha2">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <button type="submit" @click="getReporte()" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>

                        <ul class="nav nav2 nav-tabs" id="myTab1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active show" id="convenio-tab" data-toggle="tab" href="#convenio" role="tab" aria-controls="convenio" v-text="'Entregadas'"></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="gcc-tab" data-toggle="tab" href="#gcc" role="tab" aria-controls="gcc" aria-selected="false" v-text="'Programadas sin entregar'"></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="firmadas-tab" data-toggle="tab" href="#firmadas" role="tab" aria-controls="firmadas" aria-selected="false" v-text="'Firmadas sin entregar'"></a>
                            </li>
                        </ul>

                       
                        <div class="tab-content" id="myTab1Content">
                            <!-- Entregadas -->
                            <div class="tab-pane fade active show" id="convenio" role="tabpanel" aria-labelledby="convenio-tab">

                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <div class="input-group">
                                        <table class="table table-bordered table-striped table-sm">
                                            <tr>
                                                <th>Total entregadas</th>
                                                <th v-text="cont_entregas"></th>
                                            </tr>
                                            <tr>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>Entregadas puntuales</td>
                                                <td v-text="cont_puntuales = totalPuntuales"></td>
                                            </tr>
                                            <tr>
                                                <td>Entregas con revisión</td>
                                                <td v-text="cont_rev = totalRevision"></td>
                                            </tr>
                                            <tr>
                                                <td>Entregas sin detalles</td>
                                                <td v-text="cont_detalles = totalDetalles"></td>
                                            </tr>
                                        </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr> 
                                                <th>Folio</th>
                                                <th>Cliente</th>
                                                <th>Fraccionamiento</th>
                                                <th>Etapa</th>
                                                <th>Manzana</th>
                                                <th>Modelo</th>
                                                <th>Lote</th>
                                                <th>Fecha de firma</th>
                                                <th>Fecha progamada</th>
                                                <th>Fecha de entrega</th>
                                                <th>Puntual</th>
                                                <th>Revisión Previa</th>
                                                <th>Con Detalles</th>
                                                <th># Reprogramación</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="contrato in arrayEntregas" :key="contrato.id"> 
                                                <td class="td2" v-text="contrato.folio"></td>
                                                <td class="td2" v-text="contrato.nombre+' '+contrato.apellidos"></td>
                                                <td class="td2" v-text="contrato.proyecto"></td>
                                                <td class="td2" v-text="contrato.etapa"></td>
                                                <td class="td2" v-text="contrato.manzana"></td>
                                                <td class="td2" v-text="contrato.modelo"></td>
                                                <td class="td2" v-text="contrato.num_lote"></td>
                                                <td class="td2" v-text="this.moment(contrato.fecha_firma_esc).locale('es').format('DD/MMM/YYYY')"></td>
                                                <td class="td2" v-text="this.moment(contrato.fecha_program).locale('es').format('DD/MMM/YYYY')"></td>
                                                <td class="td2" v-text="this.moment(contrato.fecha_entrega_real).locale('es').format('DD/MMM/YYYY')"></td>
                                                <td class="td2">
                                                    <span class="badge badge-success" v-if="contrato.puntualidad == 1">Si</span>
                                                    <span class="badge badge-danger" v-if="contrato.puntualidad == 0">No</span>
                                                </td>
                                                <td class="td2">
                                                    <span class="badge badge-danger" v-if="contrato.revision_previa == 0">No</span>
                                                    <span class="badge badge-success" v-if="contrato.revision_previa == 2">Si</span>
                                                    <span class="badge badge-warning" v-if="contrato.revision_previa == 1">Si</span>
                                                </td>
                                                <td class="td2">
                                                    <span class="badge badge-success" v-if="contrato.cero_detalles == 1 || contrato.cero_detalles == 0">Si</span>
                                                    <span class="badge badge-danger" v-if="contrato.cero_detalles == 2">No</span>
                                                </td>
                                                <td class="td2" v-text="contrato.cont_reprogram"></td>
                                            </tr>
                                        </tbody>
                                    </table>  
                                </div>
                            </div>

                            <!-- Sin Entregar -->
                            <div class="tab-pane fade" id="gcc" role="tabpanel" aria-labelledby="gcc-tab">

                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <div class="input-group">
                                        <table class="table table-bordered table-striped table-sm">
                                            <tr>
                                                <th>Total sin entregar</th>
                                                <th v-text="cont_sinEntregar"></th>
                                            </tr>
                                            <tr>
                                                <td></td>
                                            </tr>
                                            
                                            <tr>
                                                <td>Revisadas</td>
                                                <td v-text="cont_revSinEntregar = totalRevision2"></td>
                                            </tr>
                                        </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr> 
                                                <th>Folio</th>
                                                <th>Cliente</th>
                                                <th>Fraccionamiento</th>
                                                <th>Etapa</th>
                                                <th>Manzana</th>
                                                <th>Lote</th>
                                                <th>Fecha de firma</th>
                                                <th>Fecha progamada</th>
                                                <th>Revisión Previa</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="contrato in arraySinEntregar" :key="contrato.id"> 
                                                <td class="td2" v-text="contrato.folio"></td>
                                                <td class="td2" v-text="contrato.nombre+' '+contrato.apellidos"></td>
                                                <td class="td2" v-text="contrato.proyecto"></td>
                                                <td class="td2" v-text="contrato.etapa"></td>
                                                <td class="td2" v-text="contrato.manzana"></td>
                                                <td class="td2" v-text="contrato.num_lote"></td>
                                                <td class="td2" v-if="contrato.fecha_firma_esc == null">Sin firmar</td>
                                                <td class="td2" v-else v-text="this.moment(contrato.fecha_firma_esc).locale('es').format('DD/MMM/YYYY')"></td>
                                                <td class="td2" v-text="this.moment(contrato.fecha_program).locale('es').format('DD/MMM/YYYY')"></td>
                                                <td class="td2">
                                                    <span class="badge badge-danger" v-if="contrato.revision_previa == 0">No</span>
                                                    <span class="badge badge-success" v-if="contrato.revision_previa == 2">Si</span>
                                                    <span class="badge badge-warning" v-if="contrato.revision_previa == 1">Si</span>
                                                </td>
                                            </tr>                     
                                        </tbody>
                                    </table>  
                                </div>
                            </div>

                            <!-- Firmadas Sin Entregar -->
                            <div class="tab-pane fade" id="firmadas" role="tabpanel" aria-labelledby="firmadas-tab">

                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <div class="input-group">
                                        <table class="table table-bordered table-striped table-sm">
                                            <tr>
                                                <th>Total firmadas</th>
                                                <th v-text="cont_firmadas"></th>
                                            </tr>
                                            <tr>
                                                <td></td>
                                            </tr>
                                            
                                            <tr>
                                                <td>Programadas</td>
                                                <td v-text="cont_firmmadasProg = totalFirmadasProg"></td>
                                            </tr>
                                        </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr> 
                                                <th>Folio</th>
                                                <th>Cliente</th>
                                                <th>Fraccionamiento</th>
                                                <th>Etapa</th>
                                                <th>Manzana</th>
                                                <th>Lote</th>
                                                <th>Fecha de firma</th>
                                                <th>Fecha progamada</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="contrato in arrayFirmadas" :key="contrato.id"> 
                                                <td class="td2" v-text="contrato.folio"></td>
                                                <td class="td2" v-text="contrato.nombre+' '+contrato.apellidos"></td>
                                                <td class="td2" v-text="contrato.proyecto"></td>
                                                <td class="td2" v-text="contrato.etapa"></td>
                                                <td class="td2" v-text="contrato.manzana"></td>
                                                <td class="td2" v-text="contrato.num_lote"></td>
                                                <td class="td2" v-text="this.moment(contrato.fecha_firma_esc).locale('es').format('DD/MMM/YYYY')"></td>
                                                <td class="td2" v-if="contrato.fecha_program == null" v-text="'Sin programar'"></td>
                                                <td class="td2" v-else 
                                                    v-text="this.moment(contrato.fecha_program).locale('es').format('DD/MMM/YYYY')">
                                                </td>
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
                arrayEntregas : [],
                arraySinEntregar : [],
                arrayFirmadas : [],
                arrayFraccionamientos:[],
                arrayEtapas:[],
                buscar : '',
                b_etapa: '',
                b_fecha1:'',
                b_fecha2:'',

                cont_entregas:0,
                cont_sinEntregar:0,
                cont_firmadas:0,
                cont_firmmadasProg:0,
                cont_programadas:0,
                cont_puntuales:0,
                cont_revSinEntregar:0,
                cont_rev:0,
                cont_detalles:0

            }
        },
        computed:{
            totalPuntuales: function(){
                var total = 0;
                for(var i=0;i<this.arrayEntregas.length;i++){
                    total +=this.arrayEntregas[i].puntualidad;
                }

                return total;
            },
            totalRevision : function(){
                var total = 0;
                for(var i=0;i<this.arrayEntregas.length;i++){
                    if(this.arrayEntregas[i].revision_previa > 0)
                        total++;
                }
                return total;
            },
            totalRevision2 : function(){
                var total = 0;
                for(var i=0;i<this.arraySinEntregar.length;i++){
                    if(this.arraySinEntregar[i].revision_previa > 0)
                        total++;
                }
                return total;
            },

            totalDetalles : function(){
                var total = 0;
                for(var i=0;i<this.arrayEntregas.length;i++){
                    if(this.arrayEntregas[i].cero_detalles < 2)
                        total++;
                }
                return total;
            },
            totalFirmadasProg : function(){
                var total = 0;
                for(var i=0;i<this.arrayFirmadas.length;i++){
                    if(this.arrayFirmadas[i].fecha_program != null)
                        total++;
                }
                return total;
            }
        },

        methods : {
            /**Metodo para mostrar los registros */
            getReporte(){
                let me = this;
                var url = '/reportes/reporteEntregas?fecha_1='+me.b_fecha1+'&fecha_2='+me.b_fecha2+
                        '&proyecto='+me.buscar+'&etapa='+me.b_etapa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEntregas = respuesta.entregas;
                    me.arraySinEntregar = respuesta.sinEntregar;
                    me.arrayFirmadas = respuesta.firmadas;

                    me.cont_entregas = respuesta.contEntregas;
                    me.cont_sinEntregar = respuesta.contSinEntregar;
                    me.cont_firmadas = respuesta.contFirmadas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
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
        },
       
        mounted() {
            this.selectFraccionamientos();
        }
    }
</script>
<style>
    .line-separator{
        height:1px;
        background:#717171;
        border-bottom:1px solid #c2cfd6;
    }
    .form-control:disabled, .form-control[readonly] {
        background-color: rgba(0, 0, 0, 0.06);
        opacity: 1;
        font-size: 1rem;
        color: #27417b;
    }
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

    .td2, .th2 {
        border: solid rgb(200, 200, 200) 1px;
        padding: .5rem;
    }

    .badge2 {
        display: inline-block;
        padding: 0.25em 0.4em;
        font-size: 90%;
        font-weight: bold;
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
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
