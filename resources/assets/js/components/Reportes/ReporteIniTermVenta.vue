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
                        <i class="fa fa-align-justify"></i> Reporte de Inicio, Termino, Ventas y Cobranza &nbsp;&nbsp;
                        <!--   Boton Nuevo    -->
                         <a class="btn btn-success" v-bind:href="'/reprotes/excelReporteLotesVentas?emp_constructora=' + emp_constructora">
                            <i class="fa fa-file-text"></i>&nbsp; Excel
                        </a>
                        <!-- <a :href="'/etapa/excel?buscar=' + buscar + '&buscar2=' + buscar2 + '&criterio=' + criterio"  class="btn btn-success"><i class="fa fa-file-text"></i> Excel </a> -->
                        <!---->
                    </div>

                    <div class="info-center" v-if="loading">
                        <LoadingComponentVue></LoadingComponentVue>
                    </div>

                    <div class="card-body" v-else>

                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a @click="tab=1" class="btn nav-link" v-bind:class="{ 'text-info active': tab==1 }">
                                    Cobranza
                                    <span v-if="tab ==1" style="font-size: 1em; text-align:center;" class="badge badge-light"></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a @click="tab=2" class="btn nav-link" v-bind:class="{ 'text-info active': tab==2 }">
                                    Inventario
                                    <span v-if="tab ==2" style="font-size: 1em; text-align:center;" class="badge badge-light"></span>
                                </a>
                            </li>
                        </ul>

                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <select class="form-control col-md-4" v-model="emp_constructora">
                                        <option value="">Empresa constructora</option>
                                        <option value="Grupo Constructor Cumbres">Grupo Constructor Cumbres</option>
                                        <option value="CONCRETANIA">CONCRETANIA</option>
                                    </select>

                                    <button v-if="tab == 1" type="submit" @click="listarReporte()" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    <button v-if="tab == 2" type="submit" @click="getReporteInventarios(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>

                        <template v-if="tab == 1">

                            <div class="table-responsive">
                                <table class="table2 tableScroll table-bordered table-striped table-sm">
                                    <thead class="thead2">
                                        <tr>
                                            <th colspan="11" class="text-center"> Reporte al {{fecha}} </th>
                                        </tr>
                                        <tr class="tr2">
                                            <th class="th2">Fraccionamiento</th>
                                            <th class="th2">Etapa</th>
                                            <th class="th2">Total Casas o Lotes</th>
                                            <th class="th2">Cobradas</th>
                                            <th class="th2">Terminadas No Cobradas</th>
                                            <th class="th2">Terminadas Vendidas No Cobradas</th>
                                            <th class="th2">Terminadas Disponibles</th>
                                            <th class="th2">En Proceso de Construccion No Cobradas</th>
                                            <th class="th2">En Proceso Vendidas No Cobradas</th>
                                            <th class="th2">En Proceso Disponible</th>
                                            <th class="th2">Disponibles Sin Avance</th>
                                            <th class="th2">Casa muestra terminada</th>
                                            <th class="th2">Casa muestra en proceso</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="lote in arrayLotes" :key="lote.id">
                                            <template v-if="lote.lotes != 0">
                                                <td class="td2" v-text="lote.proyecto"></td>
                                                <td class="td2" v-text="lote.num_etapa"></td>
                                                <td class="td2 text-center" v-text="lote.lotes"></td>
                                                <td class="td2 text-center" v-text="lote.cobradas"></td>
                                                <td class="td2 text-center" v-text="lote.terminadaNoCobrada"></td>
                                                <td class="td2 text-center" v-text="lote.termVendidaNoCobrada"></td>
                                                <td class="td2 text-center" v-text="lote.terminadaDisponible"></td>
                                                <td class="td2 text-center" v-text="lote.procesoNoCobrada"></td>
                                                <td class="td2 text-center" v-text="lote.procVendidaNoCobrada"></td>
                                                <td class="td2 text-center" v-text="lote.procesoDisponible"></td>
                                                <td class="td2 text-center" v-text="lote.sinAvanceDisponible"></td>
                                                <td class="td2 text-center" v-text="lote.muestraTerminada"></td>
                                                <td class="td2 text-center" v-text="lote.muestraProceso"></td>
                                            </template>

                                        </tr>
                                    </tbody>
                                    <thead>
                                        <tr>
                                            <th colspan="2">Total</th>
                                            <th v-text="total1" class="td2 text-center"></th>
                                            <th v-text="total2" class="td2 text-center"></th>
                                            <th v-text="total3" class="td2 text-center"></th>
                                            <th v-text="total4" class="td2 text-center"></th>
                                            <th v-text="total5" class="td2 text-center"></th>
                                            <th v-text="total6" class="td2 text-center"></th>
                                            <th v-text="total7" class="td2 text-center"></th>
                                            <th v-text="total8" class="td2 text-center"></th>
                                            <th v-text="total9" class="td2 text-center"></th>
                                            <th v-text="total10" class="td2 text-center"></th>
                                            <th v-text="total11" class="td2 text-center"></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </template>

                        <template v-if="tab == 2">
                            <br>
                            <div class="row">
                                <div class="col-md-4">
                                    <TableComponent>
                                        <template v-slot:thead>
                                            <tr><th colspan="2"><br></th></tr>
                                            <tr>
                                                <th colspan="2" style="color:#083145;">INVENTARIOS</th>
                                            </tr>
                                        </template>
                                        <template v-slot:tbody>
                                            <tr>
                                                <th>Terminadas No Cobradas</th>
                                                <td>
                                                    <a href="#" @click="mostrarInfo(1,
                                                        'Terminadas NO COBRADAS')">{{inventarios.terminadas.total}}</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Proceso No Cobradas</th>
                                                <td>
                                                    <a href="#" @click="mostrarInfo(1,
                                                        'En Proceso NO COBRADAS')">{{inventarios.proceso.total}}</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Casas muestra</th>
                                                <td>
                                                    <a href="#" @click="mostrarInfo(1,
                                                        'Casas Muestra')">{{inventarios.muestra.total}}</a>
                                                </td>
                                            </tr>
                                            <tr><th colspan="2"><br></th></tr>
                                            <th>TOTAL:</th><td>
                                                {{ inventarios.terminadas.total + inventarios.proceso.total + inventarios.muestra.total}}
                                            </td>
                                        </template>
                                    </TableComponent>
                                </div>

                                <div class="col-md-4">
                                    <TableComponent>
                                        <template v-slot:thead>
                                            <tr><th colspan="2"><br></th></tr>
                                            <tr>
                                                <th colspan="2" style="color:#083145;">VENDIDAS NO COBRADAS</th>
                                            </tr>
                                        </template>
                                        <template v-slot:tbody>
                                            <tr>
                                                <th>Terminadas</th>
                                                <td>
                                                    <a href="#" @click="mostrarInfo(1,'Vendidas Terminadas NO COBRADAS')">{{noCobradas.terminadas.total}}</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>En Proceso</th>
                                                <td>
                                                    <a href="#" @click="mostrarInfo(1,'Vendidas En Proceso NO COBRADAS')">{{noCobradas.proceso.total}}</a>
                                                </td>
                                            </tr>
                                            <tr><th colspan="2"><br></th></tr>
                                            <th>TOTAL:</th><td>
                                                {{ noCobradas.terminadas.total + noCobradas.proceso.total}}
                                            </td>
                                        </template>
                                    </TableComponent>
                                </div>

                                <div class="col-md-4">
                                    <TableComponent>
                                        <template v-slot:thead>
                                            <tr><th colspan="2"><br></th></tr>
                                            <tr>
                                                <th colspan="2" style="color:#083145;">DISPONIBLES PARA VENTA</th>
                                            </tr>
                                        </template>
                                        <template v-slot:tbody>
                                            <tr>
                                                <th>Terminadas</th>
                                                <td>
                                                    <a href="#" @click="mostrarInfo(1,'Disponibles Terminadas')">{{dispVentas.terminadas.total}}</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>En Proceso</th>
                                                <td>
                                                    <a href="#" @click="mostrarInfo(1,'Disponibles En Proceso')">{{dispVentas.proceso.total}}</a>
                                                </td>
                                            </tr>

                                            <tr><th colspan="2"><br></th></tr>
                                            <th>TOTAL:</th><td>
                                                {{ dispVentas.terminadas.total + dispVentas.proceso.total}}
                                            </td>
                                        </template>
                                    </TableComponent>
                                </div>
                            </div>

                            <div class="form-group row" v-if="mostrar == 1">
                                <div class="col-md-12"><br><br><br></div>
                                 <div class="col-md-12">
                                    <center>
                                    <h6 style="color:#083145">
                                        {{titulo}}
                                    </h6></center>
                                </div>

                                <div class="col-md-12">
                                    <TableComponent :cabecera="[
                                        'Proyecto','Etapa','Manzana','Lote','Modelo'
                                    ]">
                                        <template v-slot:tbody>
                                            <tr v-for="lote in datosInventario.data" :key="lote.id">
                                                <td class="td2" v-text="lote.proyecto"></td>
                                                <td class="td2" v-text="lote.etapa"></td>
                                                <td class="td2" v-text="lote.manzana"></td>
                                                <td class="td2">
                                                    {{(lote.sublote) ? `${lote.num_lote} ${lote.sublote}` : lote.num_lote}}
                                                </td>
                                                <td class="td2" v-text="lote.modelo"></td>
                                                <td v-if="lote.precio_venta"> {{ '$' + formatNumber(lote.precio_venta)}}</td>
                                                <td v-if="lote.promocion">
                                                    <button v-if="lote.promocion != 'Sin Promoción'" class="btn btn-primary" title="Ver Promoción"
                                                        @click="mostrarPromo(lote.promocion)"
                                                    >
                                                        <i class="icon-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                    </TableComponent>
                                </div>
                                <nav>
                                    <ul class="pagination">
                                        <li
                                            class="page-item"
                                            v-if="datosInventario.current_page > 5"
                                            @click="mostrarInfo(1,titulo)"
                                        >
                                            <a class="page-link" href="#">Inicio</a>
                                        </li>
                                        <li
                                            class="page-item"
                                            v-if="datosInventario.prev_page_url"
                                            @click="mostrarInfo(datosInventario.current_page - 1,titulo)"
                                        >
                                            <a class="page-link" href="#">Ant</a>
                                        </li>

                                        <li
                                            class="page-item"
                                            v-if="datosInventario.current_page - 3 >= 1"
                                            @click="mostrarInfo(datosInventario.current_page - 3,titulo)"
                                        >
                                            <a
                                                class="page-link"
                                                href="#"
                                                v-text="datosInventario.current_page - 3"
                                            ></a>
                                        </li>
                                        <li
                                            class="page-item"
                                            v-if="datosInventario.current_page - 2 >= 1"
                                            @click="mostrarInfo(datosInventario.current_page - 2,titulo)"
                                        >
                                            <a
                                                class="page-link"
                                                href="#"
                                                v-text="datosInventario.current_page - 2"
                                            ></a>
                                        </li>
                                        <li
                                            class="page-item"
                                            v-if="datosInventario.current_page - 1 >= 1"
                                            @click="mostrarInfo(datosInventario.current_page - 1,titulo)"
                                        >
                                            <a
                                                class="page-link"
                                                href="#"
                                                v-text="datosInventario.current_page - 1"
                                            ></a>
                                        </li>
                                        <li class="page-item active">
                                            <a
                                                class="page-link"
                                                href="#"
                                                v-text="datosInventario.current_page"
                                            ></a>
                                        </li>
                                        <li
                                            class="page-item"
                                            v-if="datosInventario.current_page + 1 <= datosInventario.last_page"
                                        >
                                            <a
                                                class="page-link"
                                                href="#"
                                                @click="mostrarInfo(datosInventario.current_page + 1,titulo)"
                                                v-text="datosInventario.current_page + 1"
                                            ></a>
                                        </li>
                                        <li
                                            class="page-item"
                                            v-if="datosInventario.current_page + 2 <= datosInventario.last_page"
                                        >
                                            <a
                                                class="page-link"
                                                href="#"
                                                @click="mostrarInfo(datosInventario.current_page + 2,titulo)"
                                                v-text="datosInventario.current_page + 2"
                                            ></a>
                                        </li>
                                        <li
                                            class="page-item"
                                            v-if="datosInventario.current_page + 3 <= datosInventario.last_page"
                                        >
                                            <a
                                                class="page-link"
                                                href="#"
                                                @click="mostrarInfo(datosInventario.current_page + 3,titulo)"
                                                v-text="datosInventario.current_page + 3"
                                            ></a>
                                        </li>
                                        <li
                                            class="page-item"
                                            v-if="datosInventario.next_page_url"
                                            @click="mostrarInfo(datosInventario.current_page + 1,titulo)"
                                        >
                                            <a class="page-link" href="#">Sig</a>
                                        </li>
                                        <li
                                            class="page-item"
                                            v-if="
                                                datosInventario.current_page < 5 &&
                                                    datosInventario.last_page > 5
                                            "
                                            @click="mostrarInfo(datosInventario.last_page,titulo)"
                                        >
                                            <a class="page-link" href="#">Ultimo</a>
                                        </li>
                                    </ul>

                                </nav>
                            </div>
                        </template>
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
    import ModalComponent from '../Componentes/ModalComponent.vue';
    import LoadingComponentVue from '../Componentes/LoadingComponent.vue'
    import TableComponent from '../Componentes/TableComponent.vue';
    export default {
        components:{
            TableComponent,
            ModalComponent,
            LoadingComponentVue
        },
        data(){
            return{

                arrayLotes : [],
                total1:0,
                total2:0,
                total3:0,
                total4:0,
                total5:0,
                total6:0,
                total7:0,
                total8:0,
                total9:0,
                total10:0,
                total11:0,
                fecha:'',

                tab:1,
                mostrar:0,
                titulo:'',

                emp_constructora:'',
                loading: false,

                inventarios:{},
                noCobradas:{},
                dispVentas:{},
                datosInventario:[]
            }
        },
        computed:{
        },
        methods : {
            /**Metodo para mostrar los registros */
            listarReporte(){
                let me = this;
                me.loading = true
                var url = '/reprotes/reporteLotesVentas?emp_constructora=' + this.emp_constructora;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLotes = respuesta.lotes;
                    me.total1 = respuesta.total1;
                    me.total2 = respuesta.total2;
                    me.total3 = respuesta.total3;
                    me.total4 = respuesta.total4;
                    me.total5 = respuesta.total5;
                    me.total6 = respuesta.total6;
                    me.total7 = respuesta.total7;
                    me.total8 = respuesta.total8;
                    me.total9 = respuesta.total9;
                    me.total10 = respuesta.total10;
                    me.total11 = respuesta.total11;

                    me.fecha = moment().locale('es').format('DD-MMMM-YYYY');
                    me.loading = false

                })
                .catch(function (error) {
                    console.log(error);
                    me.loading = false
                });
            },

            mostrarPromo(promo){
                 Swal({
                    title: 'Promoción',
                    html: "<h6 style='color:#111F4F'>" + promo + "</h6>",
                    animation: false,
                    customClass: 'animated tada'
                    })
            },

            getReporteInventarios(page){
                let me = this;
                var url = '/reportes/ventas/repVentasInventarios?page='+page+'&emp_constructora=' + this.emp_constructora;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.inventarios = respuesta.inventarios;
                    me.noCobradas = respuesta.noCobradas;
                    me.dispVentas = respuesta.dispVentas;

                    if(me.titulo != ''){
                        switch(me.titulo){
                            case 'Terminadas NO COBRADAS':{
                                me.datosInventario = me.inventarios.terminadas
                                break;
                            }
                            case 'En Proceso NO COBRADAS':{
                                me.datosInventario = me.inventarios.proceso
                                break;
                            }
                            case 'Casas Muestra':{
                                me.datosInventario = me.inventarios.muestra
                                break;
                            }
                            case 'Vendidas Terminadas NO COBRADAS':{
                                me.datosInventario = me.noCobradas.terminadas
                                break;
                            }
                            case 'Vendidas En Proceso NO COBRADAS':{
                                me.datosInventario = me.noCobradas.proceso
                                break;
                            }
                            case 'Disponibles Terminadas':{
                                me.datosInventario = me.dispVentas.terminadas
                                break;
                            }
                            case 'Disponibles En Proceso':{
                                me.datosInventario = me.dispVentas.proceso
                                break;
                            }
                        }
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            mostrarInfo(page,titulo){
                this.mostrar = 1;
                this.titulo = titulo;
                this.getReporteInventarios(page)
            },
            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },

        },
        mounted() {
            this.listarReporte();
            this.getReporteInventarios(1)
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

    .tableScroll{
        overflow-y: auto;
        height: 400px;
    }

    .thead2 .tr2 .th2 {
        position: sticky;
        top: 0;
        z-index: 10;
        background-color: #ffffff;
    }

    .td2:first-of-type, th:first-of-type {
    border-left: none;
    }

    .td2:last-of-type, th:last-of-type {
    border-right: none;
    }
    .info-center{
        display: flex;
        justify-content: center;
        width: 100% !important;
    }
</style>
