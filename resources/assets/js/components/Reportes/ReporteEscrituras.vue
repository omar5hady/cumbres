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

                        <a v-if="mes != '' && anio != ''" :href="'/reprotes/excelEscrituras?mes=' + mes + '&anio=' + anio + '&empresa=' + emp_constructora"  class="btn btn-success"><i class="fa fa-file-text"></i> Excel </a>
                       
                    </div>
                    <div class="card-body">
                         <ul class="nav nav2 nav-tabs" id="myTab1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active show" id="escrituras-tab" data-toggle="tab" href="#escrituras" role="tab" aria-controls="escrituras" v-text="'Escrituras'"></a>
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

                            <!-- Listado por escrituras -->
                            <div class="tab-pane fade active show" id="escrituras" role="tabpanel" aria-labelledby="escrituras-tab">

                                <div class="table-responsive">
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th colspan="12" class="text-center"> Reporte Mensual de Escrituras Ventas de Crédito </th>
                                            </tr>
                                            <tr></tr>
                                            <tr>
                                                <th colspan="3"></th>
                                                <th colspan="4" class="text-center">Ubicación</th>
                                                <th colspan="5"></th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th># Referencia</th>
                                                <th>Cliente</th>
                                                <th>Fraccionamiento</th>
                                                <th>Etapa</th>
                                                <th>Manzana</th>
                                                <th>Lote</th>
                                                <th>Crédito</th>
                                                <th v-if="emp_constructora == 'CONCRETANIA'">Valor de terreno</th>
                                                <th v-if="emp_constructora == 'CONCRETANIA'">Valor de construcción</th>
                                                <th>Fecha de firma de escrituras</th>
                                                <th>Valor de escrituración</th>
                                                <th>Notaria</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(escrituras, index) in arrayEscrituras" :key="escrituras.id">
                                                <td class="td2">{{index+1}}
                                                     <button title="Subir escrituras" type="button" @click="abrirModal(escrituras)" class="btn btn-primary btn-sm">
                                                        <i class="icon-cloud-upload"></i>
                                                    </button>
                                                    <a title="Descargar escrituras"  class="btn btn-default btn-sm" v-if="escrituras.doc_escrituras"   v-bind:href="'expediente/downloadEscrituras/'+escrituras.doc_escrituras">
                                                        <i class="fa fa-download"></i>
                                                    </a>
                                                </td>
                                                <td class="td2" v-text="escrituras.id"></td>
                                                <td class="td2" v-text="escrituras.nombre.toUpperCase() + ' '+escrituras.apellidos.toUpperCase()"></td>
                                                <td class="td2" v-text="escrituras.proyecto"></td>
                                                <td class="td2" v-text="escrituras.num_etapa"></td>
                                                <td class="td2" v-text="escrituras.manzana"></td>
                                                <td class="td2" v-text="escrituras.num_lote"></td>
                                                <td class="td2" v-text="escrituras.tipo_credito+' ('+escrituras.institucion+')'"></td>
                                                <td class="td2" v-if="emp_constructora == 'CONCRETANIA'" 
                                                    v-text="'$'+formatNumber(escrituras.valor_terreno)"></td>
                                                <td class="td2" v-if="emp_constructora == 'CONCRETANIA'" 
                                                    v-text="'$'+formatNumber(escrituras.valor_escrituras-escrituras.valor_terreno)"></td>
                                                <td class="td2" v-text="escrituras.fecha_firma_esc"></td>
                                                <td class="td2" v-text="'$'+formatNumber(escrituras.valor_escrituras)"></td>
                                                <td class="td2" v-text="escrituras.notaria"></td>

                                                <td class="td2">
                                                    <button class="btn btn-primary" @click="comentarios(escrituras.id)">Ver Comentarios</button>
                                                </td>
                                                
                                            </tr>                             
                                        </tbody>
                                    </table>
                                </div>

                                <div class="table-responsive">
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th colspan="11" class="text-center"> Contados Pendientes de Escriturar </th>
                                            </tr>
                                            <tr></tr>
                                            <tr>
                                                <th colspan="3"></th>
                                                <th colspan="4" class="text-center">Ubicación</th>
                                                <th colspan="4"></th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th># Referencia</th>
                                                <th>Cliente</th>
                                                <th>Fraccionamiento</th>
                                                <th>Etapa</th>
                                                <th>Manzana</th>
                                                <th>Lote</th>
                                                <th>Crédito</th>
                                                <th>Fecha de Venta</th>
                                                <th>Responsable</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(contadoSinEsc,index) in arraycontadoSinEscrituras" :key="contadoSinEsc.id">
                                                <td>{{index+1}}</td>
                                                <td class="td2" v-text="contadoSinEsc.id"></td>
                                                <td class="td2" v-text="contadoSinEsc.nombre.toUpperCase() + ' '+contadoSinEsc.apellidos.toUpperCase()"></td>
                                                <td class="td2" v-text="contadoSinEsc.proyecto"></td>
                                                <td class="td2" v-text="contadoSinEsc.num_etapa"></td>
                                                <td class="td2" v-text="contadoSinEsc.manzana"></td>
                                                <td class="td2" v-text="contadoSinEsc.num_lote"></td>
                                                <td class="td2" v-text="contadoSinEsc.tipo_credito+' ('+contadoSinEsc.institucion+')'"></td>
                                                <td class="td2" v-text="contadoSinEsc.fecha"></td>
                                                <td class="td2" v-text="contadoSinEsc.nombre_gestor"></td>
                                                <td class="td2">
                                                    <button class="btn btn-primary" @click="comentarios(contadoSinEsc.id)">Ver Comentarios</button>
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

            <!-- Modal para la carga pdf -->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal == 1}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="'Subir escrituras'"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div>
                                <form  method="post" @submit="formSubmit" enctype="multipart/form-data">

                                        <strong>Seleccionar archivo</strong>

                                        <input type="file" class="form-control" v-on:change="onImageChange">
                                        <br/>
                                        <button type="submit" class="btn btn-success">Guardar</button>
                                </form>
                            </div>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            </div>
                    </div> 
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->     

            <!--Inicio del modal observaciones-->
                <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal == 2}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-primary modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" v-text="'Observaciones'"></h4>
                                <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                                <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Nueva observación</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="observacion" class="form-control">
                                    </div>

                                    <div class="col-md-3">
                                        <button class="btn btn-primary" @click="storeObservacion()">Guardar</button>
                                    </div>
                                </div>


                                <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                    
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Usuario</th>
                                                <th>Observacion</th>
                                                <th>Fecha</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="observacion in arrayObservacion" :key="observacion.id">
                                                
                                                <td v-text="observacion.usuario" ></td>
                                                <td v-text="observacion.observacion" ></td>
                                                <td v-text="observacion.created_at"></td>
                                            </tr>                               
                                        </tbody>
                                    </table>
                                    
                                </form>
                            </div>
                            <!-- Botones del modal -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
            <!--Fin del modal-->   

        </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    export default {
        data(){
            return{

                arrayEscrituras:[],
                arraycontadoSinEscrituras:[],

                mes:'',
                anio:'',
                emp_constructora:'',

                id:'',
                archivo:'',
                modal:0,

                observacion:'',
                arrayObservacion: []
            }
        },
        computed:{
        },
        methods : {
            /**Metodo para mostrar los registros */
            listarReporte(){
                let me = this;

               
                me.arrayEscrituras = [];
                me.arraycontadoSinEscrituras = [];
               
                var url = '/reprotes/reporteAcumulado?mes=' + me.mes + '&anio=' + me.anio + '&empresa=' + me.emp_constructora  + '&opcion=Escrituras';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEscrituras = respuesta.escrituras;
                    me.arraycontadoSinEscrituras = respuesta.contadoSinEscrituras;

                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            comentarios(id){
                let me = this;
                var url = '/contrato/getObsExpEntregados?page=1&id=' + id ;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayObservacion = respuesta.observacion.data;
                    console.log(url);
                })
                .catch(function (error) {
                    console.log(error);
                });

                this.modal = 2;
                this.observacion = '';
                this.id = id;
            },

            onImageChange(e){

                console.log(e.target.files[0]);

                this.archivo = e.target.files[0];

            },

            formSubmit(e) {

                e.preventDefault();
                let me = this;

                let currentObj = this;
            
                let formData = new FormData();
           
                formData.append('archivo', this.archivo);
                axios.post('expediente/formSubmitEscrituras/'+this.id, formData)
                .then(function (response) {
                   
                    currentObj.success = response.data.success;
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Archivo guardado correctamente',
                        showConfirmButton: false,
                        timer: 2000
                        })
                    me.cerrarModal();
                    me.listarReporte();

                })

                .catch(function (error) {

                    currentObj.output = error;

                });

            },

            
            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },

            abrirModal(escrituras){
                this.modal =1;
                this.id = escrituras['id'];
                this.archivo = '';
            },
            cerrarModal(){
                this.modal=0;
                this.id = '';
                this.archivo = '';
                this.observacion = '';
            },

            storeObservacion(){
                 let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.post('/contratos/storeObsExpEntregado',{
                    'observacion' : this.observacion,
                    'id':this.id,
                    
                }).then(function (response){
                    me.listarReporte();
                    me.cerrarModal();
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Observación guardada correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
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
