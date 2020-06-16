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
                        <i class="fa fa-align-justify"></i>Facturacion
                    </div>
                    <div class="card-body">
                        <ul class="nav nav2 nav-tabs" id="myTab1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link text-info active show" id="ingresar-tab" data-toggle="tab" href="#creditos" role="tab" aria-controls="creditos" aria-selected="true" v-text="'Creditos (' + 0 +')'"></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-info" id="autorizado-tab" data-toggle="tab" href="#factura" role="tab" aria-controls="factura" aria-selected="false" v-text="'Contrato (' + 0 +')'"></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-info" id="liquidacion-tab" data-toggle="tab" href="#liquidacion" role="tab" aria-controls="liquidacion" aria-selected="false" v-text="'Escrituras (' + 0 +')'"></a>
                            </li>
                        </ul>

                        <div class="tab-content" id="myTab1Content">

                            <!-- Facturas de creditos -->
                            <div class="tab-pane fade active show" id="creditos" role="tabpanel" aria-labelledby="ingresar-tab">
                                <div class="row">
                                    <select class="form-control col-md-3" v-model="criterio" @click="selectFraccionamientos()">
                                        <option value="lotes.fraccionamiento_id">Proyecto</option>
                                        <option value="c.nombre">Cliente</option>
                                        <option value="contratos.id">Folio Factura</option>
                                        <option value="">Monto de Factura</option>
                                    </select>

                                    <template v-if="criterio=='lotes.fraccionamiento_id'">
                                        <select class="form-control col-md-3" v-model="buscar" @click="selectEtapa(buscar)">
                                            <option value="">Seleccione</option>
                                            <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                        </select>
                                        <select class="form-control col-md-3" v-model="b_etapa"> 
                                            <option value="">Etapa</option>
                                            <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                        </select>
                                    </template>
                                    <input type="text" v-model="b_gen" class="form-control col-sm-3" placeholder="Texto a buscar">
                                </div>
                                <div class="row text-right">
                                    <button type="submit" @click="listarContratos()" class="btn btn-primary col-sm-1"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                                <div class="row">
                                    <table class="table-responsive table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Folio</th>
                                                <th>Cliente</th>
                                                <th>Lote</th>
                                                <th>Fraccionamiento</th>
                                                <th>Etapa</th>
                                                <th>RFC</th>
                                                <th>Credito</th>
                                                <th>Factura</th>
                                                <th>Folio Factura</th>
                                                <th>Valor</th>
                                                <th>F. de carga</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>#</td>
                                                <td>Cliente</td>
                                                <td>Lote</td>
                                                <td>Fraccionamiento</td>
                                                <td>Etapa</td>
                                                <td>RFC</td>
                                                <td>
                                                    <button type="button" class="btn btn-info btn-sm">Ver contrato</button>
                                                </td>
                                                <td>
                                                    <button id="btnFiles" class="dropdown-toggle btn-primary btn btn-sm" data-toggle="dropdown" type="button"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        <i class="fa fa-cloud-upload"></i>
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelleadby="btnFiles">
                                                        <a href="#" class="dropdown-item btn btn-primary text-center" style="background-color: #00B0BB !important;"
                                                            data-toggle="modal" data-target="#modAdvFilesUp" @click="11">
                                                            <i class="fa fa-cloud-upload"></i> Subir archivo
                                                        </a>
                                                        <a v-if="1==1" :href="'/advisor/download/'+1234" class="dropdown-item btn text-info">Descargar</a>
                                                    </div>
                                                </td>
                                                <td>folio</td>
                                                <td>monto</td>
                                                <td>F. de carga</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Facturas de contratos -->
                            <div class="tab-pane fade" id="factura" role="tabpanel" aria-labelledby="autorizado-tab">
                                <div class="row">
                                    <select class="form-control col-md-3" v-model="criterio" @click="selectFraccionamientos()">
                                        <option value="lotes.fraccionamiento_id">Proyecto</option>
                                        <option value="c.nombre">Cliente</option>
                                        <option value="contratos.id">Folio Factura</option>
                                        <option value="">Monto de Factura</option>
                                    </select>

                                    <template v-if="criterio=='lotes.fraccionamiento_id'">
                                        <select class="form-control col-md-3" v-model="buscar" @click="selectEtapa(buscar)">
                                            <option value="">Seleccione</option>
                                            <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                        </select>
                                        <select class="form-control col-md-3" v-model="b_etapa"> 
                                            <option value="">Etapa</option>
                                            <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                        </select>
                                    </template>
                                    <input type="text" v-model="b_gen" class="form-control col-sm-3" placeholder="Texto a buscar">
                                </div>
                                <div class="row text-right">
                                    <button type="submit" @click="listarContratos()" class="btn btn-primary col-sm-1"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                                <div class="row">
                                    <table class="table-responsive table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Folio</th>
                                                <th>Cliente</th>
                                                <th>Lote</th>
                                                <th>Fraccionamiento</th>
                                                <th>Etapa</th>
                                                <th>RFC</th>
                                                <th>Contrato</th>
                                                <th>Factura</th>
                                                <th>Folio Factura</th>
                                                <th>Valor</th>
                                                <th>F. de carga</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-info text-center"><i class="fa fa-spinner fa-spin"></i></td>
                                                <td>Cliente</td>
                                                <td>Lote</td>
                                                <td>Fraccionamiento</td>
                                                <td>Etapa</td>
                                                <td>RFC</td>
                                                <td>
                                                    <button type="button" class="btn btn-info btn-sm">Ver contrato</button>
                                                </td>
                                                <td>
                                                    <button id="btnFiles" class="dropdown-toggle btn-primary btn btn-sm" data-toggle="dropdown" type="button"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        <i class="fa fa-cloud-upload"></i>
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelleadby="btnFiles">
                                                        <a href="#" class="dropdown-item btn btn-primary text-center" style="background-color: #00B0BB !important;"
                                                            data-toggle="modal" data-target="#modAdvFilesUp" @click="11">
                                                            <i class="fa fa-cloud-upload"></i> Subir archivo
                                                        </a>
                                                        <a v-if="1==1" :href="'/advisor/download/'+1234" class="dropdown-item btn text-info">Descargar</a>
                                                    </div>
                                                </td>
                                                <td>folio</td>
                                                <td>monto</td>
                                                <td>F. de carga</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Facturas de firma de contrato -->
                            <div class="tab-pane fade" id="factura" role="tabpanel" aria-labelledby="autorizado-tab">
                                <div class="row">
                                    <select class="form-control col-md-3" v-model="criterio" @click="selectFraccionamientos()">
                                        <option value="lotes.fraccionamiento_id">Proyecto</option>
                                        <option value="c.nombre">Cliente</option>
                                        <option value="contratos.id">Folio Factura</option>
                                        <option value="">Monto de Factura</option>
                                    </select>

                                    <template v-if="criterio=='lotes.fraccionamiento_id'">
                                        <select class="form-control col-md-3" v-model="buscar" @click="selectEtapa(buscar)">
                                            <option value="">Seleccione</option>
                                            <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                        </select>
                                        <select class="form-control col-md-3" v-model="b_etapa"> 
                                            <option value="">Etapa</option>
                                            <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                        </select>
                                    </template>
                                    <input type="text" v-model="b_gen" class="form-control col-sm-3" placeholder="Texto a buscar">
                                </div>
                                <div class="row text-right">
                                    <button type="submit" @click="listarContratos()" class="btn btn-primary col-sm-1"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                                <div class="row">
                                    <table class="table-responsive table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Folio</th>
                                                <th>Cliente</th>
                                                <th>Lote</th>
                                                <th>Fraccionamiento</th>
                                                <th>Etapa</th>
                                                <th>RFC</th>
                                                <th>Contrato</th>
                                                <th>Factura</th>
                                                <th>Folio Factura</th>
                                                <th>Valor</th>
                                                <th>F. de carga</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>#</td>
                                                <td>Cliente</td>
                                                <td>Lote</td>
                                                <td>Fraccionamiento</td>
                                                <td>Etapa</td>
                                                <td>RFC</td>
                                                <td>
                                                    <button type="button" class="btn btn-info btn-sm">Ver contrato</button>
                                                </td>
                                                <td>
                                                    <button id="btnFiles" class="dropdown-toggle btn-primary btn btn-sm" data-toggle="dropdown" type="button"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        <i class="fa fa-cloud-upload"></i>
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelleadby="btnFiles">
                                                        <a href="#" class="dropdown-item btn btn-primary text-center" style="background-color: #00B0BB !important;"
                                                            data-toggle="modal" data-target="#modAdvFilesUp" @click="11">
                                                            <i class="fa fa-cloud-upload"></i> Subir archivo
                                                        </a>
                                                        <a v-if="1==1" :href="'/advisor/download/'+1234" class="dropdown-item btn text-info">Descargar</a>
                                                    </div>
                                                </td>
                                                <td>folio</td>
                                                <td>monto</td>
                                                <td>F. de carga</td>
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

            <!-- Upload adviser files -->
            <div class="modal fade" id="modAdvFilesUp" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="color: #fff; background-color: #00B0BB;">
                            <h5 class="modal-title" id="">Subir documentación</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="" method="post" enctype="multipart/form-data" id="formUpAdvFile" @submit="subirFactura">
                        <div class="modal-body">
                                <div class="row">
                                    <label class="col-sm-4" for="">Factura</label>
                                    <input type="file" name="upfil" id="upfil" style="right: 10px;" class="form-control col-sm-8" required>
                                </div>
                                <br>
                                <div class="row">
                                    <label class="col-sm-4" for="">Folio Factura</label>
                                    <input type="text" name="upFolio" id="upFolio" style="right: 10px;" class="form-control col-sm-8" required>
                                </div>
                                <br>
                                <div class="row">
                                    <label class="col-sm-4" for="">Valor (monto)</label>
                                    <input type="numbuer" name="upMonto" id="upMonto" step="0.01" style="right: 10px;" class="form-control col-sm-8" required>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

     </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    export default {
        props:{
            rolId:{type: String}
        },
        data(){
            return{
                criterio:'lotes.fraccionamiento_id',
                buscar:'',
                b_etapa:'',
                b_gen:'',
                page:1,
                arrayFraccionamientos:[],
                arrayEtapas:[],
            }
        },
        computed:{
        },

        
        methods : {
            selectFraccionamientos(){
                let me = this;
                me.buscar=""
                
                me.arrayFraccionamientos=[];
                var url = '/select_fraccionamiento';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayFraccionamientos = respuesta.fraccionamientos;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            selectEtapa(buscar){
                let me = this;
                me.buscar2=""
                
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
            listarContratos(){
                let me = this;
                
                axios.get('/facturas/contratos/get?page='+this.page).then(
                    response => me.arrayContratos = response.data
                ).catch(error => console.log(error))
            },
            subirFactura(e){

            }
        },
       
        mounted() {
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
    font-size: 0.85rem;
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

 @media (min-width: 300px){
  .btnagregar{
        margin-top: 2rem;
        }

    .nav2 {
        overflow-x: scroll;
    }
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
    /* .nav2 {
    display: -ms-flexbox;
    display: flex;
    padding-left: 0;
    margin-bottom: 0;
    list-style: none;
    overflow-x: scroll;
    } */
</style>
