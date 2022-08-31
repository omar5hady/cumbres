<template>
    <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card scroll-box">


                <!----------------- Listado Contratos ------------------------------>
                <!-- Div Card Body para listar -->
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-7">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select required class="form-control" v-model="b_proyecto" @change="selectCreditosPuente()">
                                        <option value="">Fraccionamiento</option>
                                        <option v-for="proyecto in arrayFraccionamientos" :key="proyecto.id" :value="proyecto.id" v-text="proyecto.nombre"></option>
                                    </select>
                                    &nbsp;&nbsp;&nbsp;
                                    <input type="date" v-model="fecha" class="form-control col-md-3">
                                </div>

                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-7">
                                <div class="input-group">

                                    <button type="submit" @click="getBaseActiva()" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <button v-if="nuevo == 0 && b_proyecto != ''" @click="nuevo=1" class="btn btn-success"><i class="icon-plus"></i> Nueva Base</button>
                                </div>

                            </div>
                        </div>

                        <div class="card">
                            <div class="form-group row" v-if="nuevo == 1">

                                <div class="col-md-4">
                                    <br>
                                    <select v-model="credito" class="form-control">
                                        <option value="">Seleccione Crédito Puente </option>
                                        <option v-for="credito in arrayCreditos" :key="credito.id" :value="credito.id" v-text="credito.folio"></option>
                                    </select>
                                </div>


                                <div class="col-md-6" v-if="credito != ''">
                                    <form method="post" @submit="formSubmit"  enctype="multipart/form-data">
                                        <!-- {{ csrf_field() }} -->
                                        Selecciona archivo excel xls/csv: <input type="file" v-on:change="onImageChange" class="form-control">
                                        <input v-if="file" type="submit" value="Cargar" class="btn btn-primary btn">
                                        <button v-if="nuevo == 1 && b_proyecto != ''" @click="nuevo=0" class="btn btn-danger"><i class="icon-close"></i> Cancelar</button>
                                    </form>
                                </div>
                            </div>

                        </div>


                        <div class="form-group row">
                            <div class="col-xl-6 col-lg-5 col-md-6">
                                <div class="input-group" v-if="arrayBases.length > 0">
                                    <div class="text-muted text-uppercase font-weight-bold" v-text="' Fecha de captura: '+arrayBases[0].created_at"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xl-6 col-lg-5 col-md-6">
                                <div class="input-group" v-if="arrayBases.length > 0">
                                    <div v-if="arrayBases[0].folio != null"
                                        class="text-muted text-uppercase font-weight-bold"
                                        v-text="'Credito puente asignado: '+arrayBases[0].folio">
                                    </div>

                                    <br>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div v-for="base in arrayBases" :key="base.id" class="col-xl-4 col-lg-5 col-md-4">
                                <div class="card">
                                    <div class="card-body p-3 d-flex align-items-center">
                                        <div>
                                            <div class="text-value text-primary"><strong>{{base.nombre}}</strong></div>
                                            <div class="text-muted text-uppercase font-weight-bold" v-text="'Valor de venta: $'+formatNumber(base.valor_venta)"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="card-footer px-3 py-2 text-right">
                                            <TableComponent>
                                                <template v-slot:tbody>
                                                    <!--Comisiones Bancarias--->
                                                    <tr><th colspan="2">Comisiones Bancarias</th></tr>
                                                    <tr>
                                                        <td>Inscripción del conjunto</td>
                                                        <td v-text="'$'+formatNumber(base.insc_conjunto)"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Intereses Nafin o por desplazamiento lento</td>
                                                        <td v-text="'$'+formatNumber(base.int_nafin)"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Gastos de escrituración del Crédito Puente</td>
                                                        <td v-text="'$'+formatNumber(base.gastos_esc)"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Comisión Integral Apertura C. Puente</td>
                                                        <td v-text="'$'+formatNumber(base.comision_int)"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Intereses Crédito Puente</td>
                                                        <td v-text="'$'+formatNumber(base.int_cpuente)"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Intereses por pago del terreno</td>
                                                        <td v-text="'$'+formatNumber(base.int_pago_terreno)"></td>
                                                    </tr>
                                                <!--Bases Presupuestales--->
                                                    <tr><th colspan="2">Bases Presupuestales</th></tr>
                                                    <tr>
                                                        <td>Valor del terreno</td>
                                                        <td v-text="'$'+formatNumber(base.valor_terreno)"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Permisos, Lic. Y Autorizaciones Municipales</td>
                                                        <td v-text="'$'+formatNumber(base.permisos)"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Presupuesto de Urb. E Infraestructura externa</td>
                                                        <td v-text="'$'+formatNumber(base.presupuesto_urb)"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Presupuesto de Edificación de vivienda</td>
                                                        <td v-text="'$'+formatNumber(base.presupuesto_edif)"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Equipamiento</td>
                                                        <td v-text="'$'+formatNumber(base.equipamiento)"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Escritura a GCC, Juicio y Acta de Lotif.</td>
                                                        <td v-text="'$'+formatNumber(base.escritura_gcc)"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Laboratorio</td>
                                                        <td v-text="'$'+formatNumber(base.laboratorio)"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Gastos Ind. De Operación</td>
                                                        <td v-text="'$'+formatNumber(base.gastos_ind_op)"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Gastos de comercialización</td>
                                                        <td v-text="'$'+formatNumber(base.gastos_comerc)"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Comision por ventas (2.5% + 16% IVA)</td>
                                                        <td v-text="'$'+formatNumber(base.comicion_venta)"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Fianzas (Cumplimiento urbanización)</td>
                                                        <td v-text="'$'+formatNumber(base.fianzas)"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Partida Inflacionaria y Obra extra</td>
                                                        <td v-text="'$'+formatNumber(base.partida_inflacionaria)"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Cargos adicionales al terreno</td>
                                                        <td v-text="'$'+formatNumber(base.adicional_terreno)"></td>
                                                    </tr>

                                                <!--Indices Presupuestales--->
                                                    <tr><th colspan="2">Indices Presupuestales</th></tr>
                                                    <tr>
                                                        <td>Costo del Terreno urbanizado por m&sup2; vendible</td>
                                                        <td v-text="'$'+formatNumber(base.ct_urbanizado)"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Costo del Terreno en Breña por m&sup2; vendible</td>
                                                        <td v-text="'$'+formatNumber(base.ct_brena)"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Costo de Pavimentación por m&sup2; vendible</td>
                                                        <td v-text="'$'+formatNumber(base.c_pavimentacion)"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Costo de Edificación por m&sup2; Const. C/Plataforma</td>
                                                        <td v-text="'$'+formatNumber(base.c_edificacion1)"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Costo de Edificación por m&sup2; Const. S/Plataforma</td>
                                                        <td v-text="'$'+formatNumber(base.c_edificacion2)"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Precio de Venta por m&sup2; Construido</td>
                                                        <td v-text="'$'+formatNumber(base.precio_venta_const)"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Costo de Adquisición por m&sup2; en Breña total</td>
                                                        <td v-text="'$'+formatNumber(base.c_adquisicion_brena)"></td>
                                                    </tr>
                                                </template>
                                            </TableComponent>
                                        </div>
                                    </div>
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
import TableComponent from '../Componentes/TableComponent.vue'
    export default {
        components:{
            TableComponent
        },
        props:{
            rolId:{type: String}
        },
        data(){
            return{
                arrayFraccionamientos:[],
                arrayBases:[],
                arrayCreditos:[],
                b_proyecto:'',
                file:'',
                nuevo:0,
                fecha:'',
                credito:''
            }
        },
        computed:{

        },
        methods : {
            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
            isNumber: function(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                    evt.preventDefault();;
                } else {
                    return true;
                }
            },

            onImageChange(e){

                console.log(e.target.files[0]);

                this.file = e.target.files[0];

                if(this.file==''){
                    return;
                }

            },

            formSubmit(e) {
                e.preventDefault();

                let formData = new FormData();
                formData.append('file', this.file);
                formData.append('fraccionamiento', this.b_proyecto);
                formData.append('credito', this.credito);
                axios.post('/basePresupuestal/storeBases',formData)
                .then(function (response) {
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Archivo cargado correctamente',
                        showConfirmButton: false,
                        timer: 2500
                        })
                        this.credito = '';

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

            selectCreditosPuente(){
                let me = this;
                me.arrayCreditos=[];
                var url = '/cPuentes/selectCreditosPuente?fraccionamiento='+me.b_proyecto;
                axios.get(url).then(function (response) {
                    me.arrayCreditos = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            getBaseActiva(){
                let me = this;
                me.arrayBases=[];
                me.credito = '';
                var url = '/basePresupuestal/getBaseActiva?fraccionamiento='+this.b_proyecto+'&fecha='+this.fecha;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayBases = respuesta.bases;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

        },
        mounted() {
            this.selectFraccionamientos();
            this.selectCreditosPuente();
        }
    }
</script>
<style>

    .modal-content{
        width: 100% !important;
        position: absolute !important;

    }
    .modal-body{
        height: 350px;
        width: 100%;
        overflow-y: auto;
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
    @media (min-width: 600px){
        .btnagregar{
        margin-top: 2rem;
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
    .badge2 {
        display: inline-block;
        padding: 0.25em 0.4em;
        font-size: 85%;
        font-weight: bold;
        line-height: 1;
        color: #151b1f;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
    }
    .line-separator{
        height:1px;
        background:#717171;
        border-bottom:1px solid #c2cfd6;
    }
}
</style>
