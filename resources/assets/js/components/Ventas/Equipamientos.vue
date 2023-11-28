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
                        <i class="fa fa-align-justify"></i>Solicitud de Equipamiento
                        <!--   Boton descargar excel    -->
                            <button type="submit" v-if="historial == 0" class="btn btn-dark" @click="listarHistorial(1, buscar2, b_etapa2, b_manzana2, b_lote2, criterio2)">
                                <i class="fa fa-list-alt"></i> Historial de equipamientos
                            </button>
                        <!---->
                        <!--   Boton regresar  -->
                            <button type="submit" v-if="historial == 1" class="btn btn-light" @click="listarContratos(1, buscar, b_etapa, b_manzana, b_lote, criterio)">
                                <i class="icon-action-undo"></i> Regresar
                            </button>
                        <!---->
                    </div>
                <!-------------------  Div para Contratos que tienen paquete o promoción  --------------------->
                    <div class="card-body" v-if="historial == 0">
                        <div class="form-group row">
                            <div class="col-md-5">
                                <div class="input-group">
                                    <select class="form-control" v-model="b_empresa" >
                                        <option value="">Empresa constructora</option>
                                        <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-10">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-3" v-model="criterio" @change="selectFraccionamientos()">
                                        <option value="lotes.fraccionamiento_id">Proyecto</option>
                                        <option value="c.nombre">Cliente</option>
                                        <option value="contratos.id"># Folio</option>
                                    </select>
                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar" @change="selectEtapa(buscar)">
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>
                                    <input v-else type="text"  v-model="buscar" @keyup.enter="listarContratos(1)" class="form-control" placeholder="Texto a buscar">
                                </div>
                            </div>

                            <div class="col-md-10" v-if="criterio=='lotes.fraccionamiento_id'">
                                <div class="input-group">

                                    <select class="form-control" v-model="b_etapa">
                                        <option value="">Etapa</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                    </select>

                                    <input type="text" v-model="b_manzana" class="form-control" placeholder="Manzana a buscar">
                                </div>
                            </div>

                            <div class="col-md-10">
                                <div class="input-group">
                                    <input type="text" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_lote" class="form-control col-md-3" placeholder="Lote a buscar">
                                    <button type="submit" @click="listarContratos(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>

                        <TableContratos
                            :arrayData="arrayContratos"
                            @abrirModal="abrirModal"
                            @verHistorial="verHistorial"
                            @terminarSolicitud="terminarSolicitud"
                        ></TableContratos>
                        <NavComponent
                            :current="pagination.current_page ? pagination.current_page : 1"
                            :last="pagination.last_page ? pagination.last_page : 1"
                            @changePage="listarContratos"
                        ></NavComponent>
                        <button class="btn btn-sm btn-default" data-toggle="modal" data-target="#manualId">Manual</button>
                    </div>
                <!-------------------  Fin Div para Contratos que tienen paquete o promoción  --------------------->

                <!-------------------  Div historial equipamientos  --------------------->
                    <div class="card-body" v-if="historial == 1">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control" v-model="b_empresa" >
                                        <option value="">Empresa constructora</option>
                                        <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-10">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-3" v-model="criterio2" @change="selectFraccionamientos()">
                                        <option value="lotes.fraccionamiento_id">Proyecto</option>
                                        <option value="c.nombre">Cliente</option>
                                        <option value="contratos.id"># Folio</option>
                                        <option value="proveedores.proveedor">Proveedor</option>
                                    </select>


                                    <select class="form-control" v-if="criterio2=='lotes.fraccionamiento_id'" v-model="buscar2" @change="selectEtapa(buscar2)">
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamiento in arrayFraccionamientos2" :key="fraccionamiento.nombre" :value="fraccionamiento.id" v-text="fraccionamiento.nombre"></option>
                                    </select>

                                    <input v-else type="text"  v-model="buscar2" @keyup.enter="listarHistorial(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)" class="form-control" placeholder="Texto a buscar">
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="input-group">

                                    <select class="form-control" v-if="criterio2=='lotes.fraccionamiento_id'" v-model="b_etapa2">
                                        <option value="">Etapa</option>
                                        <option v-for="etapa in arrayEtapas2" :key="etapa.num_etapa" :value="etapa.id" v-text="etapa.num_etapa"></option>
                                    </select>


                                    <input type="text" v-if="criterio2=='lotes.fraccionamiento_id'" v-model="b_manzana2" class="form-control" placeholder="Manzana a buscar">
                                    <input type="text" v-if="criterio2=='lotes.fraccionamiento_id'" v-model="b_lote2" class="form-control" placeholder="Lote a buscar">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-4" v-model="status">
                                        <option value="">Todos</option>
                                        <option value="1">Pendiente</option>
                                        <option value="2">En proceso</option>
                                        <option value="3">Revisión</option>
                                        <option value="4">Aprobados</option>
                                        <option value="0">Rechazados</option>
                                    </select>
                                    <button type="submit" @click="listarHistorial(1,buscar2,b_etapa2,b_manzana2,b_lote2,criterio2)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>

                        <TableHistorial
                            :arrayData="arrayHistorialEquipamientos"
                            @abrirModal="abrirModal"
                            @cargaArchivo="cargaArchivo"
                        ></TableHistorial>
                        <NavComponent
                            :current="pagination2.current_page ? pagination2.current_page : 1"
                            :last="pagination2.last_page ? pagination2.last_page : 1"
                            @changePage="listarHistorial"
                        ></NavComponent>
                        <button class="btn btn-sm btn-default" data-toggle="modal" data-target="#manualId">Manual</button>
                    </div>
                <!-------------------  Fin Div para Contratos que tienen paquete o promoción  --------------------->

                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>


            <!--Inicio del modal para solicitar Equipamiento -->
                <ModalSolicEquip
                    v-if="modal"
                    :lote_id="lote_id"
                    :titulo="tituloModal"
                    @closeModal="cerrarModal"
                    :paquete="paquete"
                    :promocion="promocion"
                    :contrato_id="contrato_id"
                >
                </ModalSolicEquip>
            <!--Fin del modal-->

            <!--Inicio del modal para diversos llenados de tabla en historial -->
                <ModalComponent
                    v-if="modal2"
                    :titulo="tituloModal"
                    @closeModal="cerrarModal"
                >
                    <template v-slot:body>
                        <template v-if="tipoAccion == 1">
                            <RowModal label1="Fecha de anticipo" clsRow1="col-md-4">
                                <input type="date" v-model="fecha_anticipo" class="form-control">
                            </RowModal>

                            <RowModal label1="$ Anticipo" clsRow1="col-md-4" clsRow2="col-md-4">
                                <input type="text" pattern="\d*" maxlength="10" v-on:keypress="$root.isNumber($event)" v-model="anticipo" class="form-control">
                                <template v-slot:input2>
                                    <h6 v-text="'$'+$root.formatNumber(anticipo)"></h6>
                                </template>
                            </RowModal>
                        </template>

                        <template v-if="tipoAccion == 2">
                            <RowModal label1="Fecha de colocacion" clsRow1="col-md-4">
                                <input type="date" v-model="fecha_colocacion" class="form-control">
                            </RowModal>
                            <RowModal label1="Observación" clsRow1="col-md-8">
                                <textarea v-model="observacion" class="form-control" cols="50" rows="4"></textarea>
                            </RowModal>
                        </template>

                        <template v-if="tipoAccion == 3">
                            <RowModal clsRow1="col-md-4" label1="Fecha de liquidación">
                                <input type="date" v-model="fecha_liquidacion" class="form-control">
                            </RowModal>
                            <RowModal clsRow1="col-md-4" label1="$ Liquidación" clsRow2="col-md-4">
                                <input type="text" pattern="\d*" maxlength="10" v-on:keypress="$root.isNumber($event)" v-model="liquidacion" class="form-control">
                                <template v-slot:input2>
                                    <h6 v-text="'$'+$root.formatNumber(liquidacion)"></h6>
                                </template>
                            </RowModal>
                        </template>
                    </template>
                    <template v-slot:buttons-footer>
                        <button type="button" v-if="tipoAccion == 1" class="btn btn-success" @click="actAnticipo()">Guardar</button>
                        <button type="button" v-if="tipoAccion == 2" class="btn btn-success" @click="actColocacion()">Guardar</button>
                        <button type="button" v-if="tipoAccion == 3" class="btn btn-success" @click="actLiquidacion()">Guardar</button>
                    </template>
                </ModalComponent>
            <!--Fin del modal-->

            <!--Inicio del modal observaciones-->
                <ModalComponent
                    v-if="modal3"
                    :titulo="tituloModal"
                    @closeModal="cerrarModal"
                >
                    <template v-slot:body>
                        <RowModal label1="Nueva Observación" clsRow1="col-md-6" clsRow2="col-md-3">
                            <input type="text" v-model="observacion" class="form-control">
                            <template v-slot:input2>
                                <button class="btn btn-primary" @click="storeObservacion()">Guardar</button>
                            </template>
                        </RowModal>

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
                                    <td v-text="observacion.comentario" ></td>
                                    <td v-text="observacion.created_at"></td>
                                </tr>
                            </tbody>
                        </table>
                    </template>
                </ModalComponent>
            <!--Fin del modal-->

            <!--Inicio del modal para reubicar -->
                <ModalComponent
                    v-if="modal4"
                    :titulo="tituloModal"
                    @closeModal="cerrarModal"
                >
                    <template v-slot:body>
                        <RowModal label1="Proyecto" clsRow1="col-md-4" clsRow2="col-md-4">
                            <select class="form-control" v-model="contProy" @click="selectEtapa2(contProy)">
                                <option value="">Proyecto</option>
                                <option v-for="fraccionamiento in arrayFraccionamientos2" :key="fraccionamiento.nombre" :value="fraccionamiento.id" v-text="fraccionamiento.nombre"></option>
                            </select>
                            <template v-slot:input2>
                                <select class="form-control" v-model="contEtapa">
                                    <option value="">Etapa</option>
                                    <option v-for="etapa in arrayEtapas3" :key="etapa.num_etapa" :value="etapa.id" v-text="etapa.num_etapa"></option>
                                </select>
                            </template>
                        </RowModal>
                        <RowModal label1="" clsRow1="col-md-4" clsRow2="col-md-2">
                            <input type="text" v-model="contManzana" class="form-control" placeholder="Manzana">
                            <template v-slot:input2>
                                <button type="submit" @click="listarContRea(contProy,contEtapa,contManzana)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </template>
                        </RowModal>
                        <table class="table2 table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Seleccionar</th>
                                    <th># Ref</th>
                                    <th>Proyecto</th>
                                    <th>Etapa</th>
                                    <th>Manzana</th>
                                    <th>Lote</th>
                                    <th>Paquete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="contReasig in arrayReasignar" :key="contReasig.id">
                                    <td class="td2">
                                        <button v-if="contReasig.id != id_reasig" title="Reasignar" type="button" @click="id_reasig = contReasig.id, lote_reasignar = contReasig.lote_id" class="btn btn-primary btn-sm">
                                            <i class="fa fa-exchange"></i>
                                        </button>
                                            <i v-else class="btn btn-success btn-sm fa fa-check"></i>
                                    </td>
                                    <td class="td2" v-text="contReasig.id" ></td>
                                    <td class="td2" v-text="contReasig.proyecto" ></td>
                                    <td class="td2" v-text="contReasig.etapa"></td>
                                    <td class="td2" v-text="contReasig.manzana"></td>
                                    <td class="td2" v-text="contReasig.num_lote"></td>
                                    <td class="td2" v-text="'Paquete: ' + contReasig.paquete + ' Promo: '+ contReasig.promocion"></td>
                                </tr>
                            </tbody>
                        </table>
                    </template>
                    <template v-slot:buttons-footer>
                        <button type="button" class="btn btn-success" @click="reubicar()">Reasignar</button>
                    </template>
                </ModalComponent>
            <!--Fin del modal-->

            <!-- carga de archivos -->
                <div class="modal fade" id="cargaPago1" role="dialog" aria-labelledby="manualIdTitle" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header" style="color:white; background-color: #00ADEF;">
                            <h5 class="modal-title" id="manualIdTitle" v-text="'Comprobante de pago '+upType+'.'"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form @submit="uploadOrder" id="upFilesForm" method="POST" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="row">
                                    <label for="upFile" class="col-sm-2">Archivo</label>
                                    <input type="file" name="upFile" id="upFile" class="form-control col-sm-8" required>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Subir</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>

            <!-- Manual -->
                <div class="modal fade" id="manualId" tabindex="-1" role="dialog" aria-labelledby="manualIdTitle" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="manualIdTitle">Manual</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>
                                Dentro del módulo de solicitud de equipamiento podrá visualizar una lista de los lotes que
                                podrían aplicar a una instalación de equipamiento.
                            </p>
                            <p>
                                <strong>Saber si un lote requiere una solicitud de equipamiento</strong>, debe observar dentro de
                                la columna “Equipamiento”, podrá observar una descripción que le indicará que equipos
                                deberá solicitar para su instalación.
                            </p>
                            <p>
                                <strong>Solicitar equipamiento</strong>, para solicitar el equipamiento debe dar clic sobre el botón con
                                la leyenda de “Solicitar”, a continuación, aparecerá una ventana donde se le
                                permitirá seleccionar un proveedor, así como el artículo que se debe instalar,
                                debe dar clic sobre el botón de “Solicitar”, ahora la solicitud estará creada
                                (podrá realizar tantas solitudes como sean necesarias, en caso de que no se encuentre
                                el equipamiento o el proveedor dentro de la lista ve a el módulo <strong>“Administración -> Proveedores”</strong>).
                            </p>
                            <p>
                                <strong>Una vez solicitados los equipamientos</strong> a instalar puede dar doble clic sobre el registro
                                y podrá agregar el costo total del equipamiento a instalar, también podrá colocar una
                                “Fecha programada“ para la instalación, ver el estatus de la instalación, ver los saldos
                                pendientes, generar la liquidación (solo en caso de que ya esté terminado),
                                “Imprimir la recepción” (solo en caso de que ya esté terminado) y agregar observaciones
                                si así se requiere.
                            </p>
                            <p>
                                <strong>Nota:</strong> tome en cuenta que el proveedor también podrá editar las fechas tentativas de
                                instalación, así como las fechas definitivas de instalación si así lo requiere
                                (vea el modulo de “Proveedores -> Seguimiento de instalación”).
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                        </div>
                    </div>
                </div>

     </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
import RowModal from '../Componentes/ComponentesModal/RowModalComponent.vue'
import NavComponent from '../Componentes/NavComponent.vue'
import TableContratos from './components/Equipamiento/TableContratos.vue'
import TableHistorial from './components/Equipamiento/TableHistorial.vue'
import ModalComponent from '../Componentes/ModalComponent.vue'
import TableComponent from '../Componentes/TableComponent.vue'
import ModalSolicEquip from './components/Equipamiento/ModalSolicEquip.vue'

    export default {
        components:{
            ModalComponent,
            TableContratos,
            TableHistorial,
            RowModal,
            NavComponent,
            ModalComponent,
            TableComponent,

            ModalSolicEquip
        },
        data(){
            return{
                observacion:'',
                historial:0,

                arrayContratos : [],
                arrayObservacion : [],

                arrayFraccionamientos:[],
                arrayEtapas:[],
                arrayFraccionamientos2:[],
                arrayEtapas2:[],
                arrayProveedores:[],

                modal: 0,
                modal2: 0,
                modal3: 0,
                modal4: 0,
                tituloModal: '',

                //variables
                paquete:'',
                promocion:'',
                equipamiento: '',
                lote_id: 0,
                contrato_id: 0,
                solicitud_id: 0,

                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'lotes.fraccionamiento_id',
                buscar : '',
                b_etapa: '',
                b_manzana: '',
                b_lote: '',

                contProy:'',
                contEtapa:'',
                contManzana:'',
                id_reasig:'',
                lote_reasignar:'',
                arrayReasignar:[],

                arrayEtapas3:[],

                // Criterios para historial de equipamientos
                pagination2 : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                criterio2 : 'lotes.fraccionamiento_id',
                buscar2 : '',
                b_etapa2: '',
                b_manzana2: '',
                b_lote2: '',
                tipoAccion:0,

                fecha_anticipo:'',
                anticipo:0,
                fecha_liquidacion:'',
                liquidacion:0,
                fecha_colocacion:'',
                observacion:'',
                status:'',


                arrayHistorialEquipamientos : [],
                b_empresa:'',
                empresas:[],
                generalId:0,
                upType:0,
                myAlerts:{
                    popAlert : function(title = 'Alert',type = "success", description =''){
                        swal({
                            title: title,
                            type: type,
                            text: description,
                            showConfirmButton:false,
                            timer:1500,
                        })
                    }
                },
            }
        },
        computed:{
        },
        methods : {
            verHistorial(id){
                this.criterio2 = 'contratos.id'
                this.buscar2 = id;
                this.historial = 1;
                this.listarHistorial(1)
            },
            /**Metodo para mostrar los registros */
            listarContratos(page){
                let me = this;
                me.historial = 0;
                var url = '/equipamiento/indexContrato?page=' + page + '&buscar=' + me.buscar + '&b_etapa=' + me.b_etapa +
                    '&b_manzana=' + me.b_manzana + '&b_lote=' + me.b_lote +  '&criterio=' + me.criterio +'&b_empresa='+this.b_empresa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayContratos = respuesta.contratos.data;
                    me.pagination = respuesta.pagination;
                    for(var i=0;i<me.pagination.total;i++){

                        if(me.arrayContratos[i].paquete == null || me.arrayContratos[i].paquete == ""){
                            me.arrayContratos[i].paquete= 'Sin paquete';
                        }
                         if(me.arrayContratos[i].promocion == null || me.arrayContratos[i].promocion == ""){
                            me.arrayContratos[i].promocion = 'Sin promoción';
                        }
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });

            },

            listarHistorial(page){
                let me = this;
                me.historial = 1;
                var url = '/equipamiento/indexHistorial?page=' + page + '&buscar=' + me.buscar2 + '&b_etapa=' + me.b_etapa2 +
                    '&b_manzana=' + me.b_manzana2 + '&b_lote=' + me.b_lote2 +  '&criterio=' + me.criterio2 +  '&status=' +
                    me.status +'&b_empresa='+this.b_empresa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayHistorialEquipamientos = respuesta.equipamientos.data;
                    me.pagination2 = respuesta.pagination;

                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            listarContRea(proyecto, etapa, manzana){
                let me = this;
                var url = '/equipamiento/contRea?proyecto=' + proyecto + '&etapa=' + etapa + '&manzana=' + manzana;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayReasignar = respuesta.contratos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectFraccionamientos(){
                let me = this;
                me.buscar=""

                me.arrayFraccionamientos=[];
                me.arrayFraccionamientos2=[];
                var url = '/select_fraccionamiento';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayFraccionamientos = respuesta.fraccionamientos;
                    me.arrayFraccionamientos2 = respuesta.fraccionamientos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectEtapa(buscar){
                let me = this;
                me.b_etapa=""

                me.arrayEtapas=[];
                me.arrayEtapas2=[];
                var url = '/select_etapa_proyecto?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEtapas = respuesta.etapas;
                    me.arrayEtapas2 = respuesta.etapas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectEtapa2(buscar){
                let me = this;

                me.arrayEtapas3=[];
                var url = '/select_etapa_proyecto?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEtapas3 = respuesta.etapas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            terminarSolicitud(id){
                this.contrato_id=id;
                //console.log(this.departamento_id);
                swal({
                title: '¿Desea finalizar la solicitud de equipamientos para este contrato?',
                text: "Esta acción no se puede revertir!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si, solicitar!'
                }).then((result) => {
                if (result.value) {
                    let me = this;

                axios.put('/equipamiento/terminarSolicitud',
                         {'id': this.contrato_id}).then(function (response){
                        swal(
                        'Hecho!',
                        'La solicitud de equipamientos ha sido finalizada con exito.',
                        'success'
                        )
                        me.listarContratos(me.pagination.current_page);
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },

            actCosto(id,costo){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/equipamiento/actCosto',{
                    'fecha_anticipo':this.fecha_anticipo,
                    'costo' : costo,
                    'id':id,

                }).then(function (response){
                    me.cerrarModal();
                    me.listarHistorial(me.pagination2.current_page);
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Datos guardados correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            actAnticipo(){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/equipamiento/actAnticipo',{
                    'fecha_anticipo':this.fecha_anticipo,
                    'anticipo' : this.anticipo,
                    'id':this.solicitud_id,

                }).then(function (response){
                    me.cerrarModal();
                    me.listarHistorial(me.pagination2.current_page);
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Datos guardados correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            reubicar(){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/equipamiento/reubicar',{
                    'id':this.solicitud_id,
                    'contrato_id' : this.id_reasig,
                    'lote_id':this.lote_reasignar,

                }).then(function (response){
                    me.cerrarModal();
                    me.listarHistorial(me.pagination2.current_page);
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Equipamiento reasignado'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            actLiquidacion(){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/equipamiento/actLiquidacion',{
                    'fecha_liquidacion':this.fecha_liquidacion,
                    'liquidacion' : this.liquidacion,
                    'id':this.solicitud_id,

                }).then(function (response){
                    me.cerrarModal();
                    me.listarHistorial(me.pagination2.current_page);
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Datos guardados correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            storeObservacion(){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.post('/equipamiento/registrarObservacion',{
                    'comentario' : this.observacion,
                    'solic_id':this.solicitud_id,

                }).then(function (response){
                    me.listarObservacion(1,me.solicitud_id);
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

            actColocacion(){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/equipamiento/actColocacion',{
                    'fecha_colocacion':this.fecha_colocacion,
                    'comentario' : this.observacion,
                    'id':this.solicitud_id,

                }).then(function (response){
                    me.cerrarModal();
                    me.listarHistorial(me.pagination2.current_page);
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Datos guardados correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            listarObservacion(page, buscar){
                let me = this;
                var url = '/equipamiento/indexObservacion?page=' + page + '&buscar=' + buscar ;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayObservacion = respuesta.observacion.data;
                    console.log(url);
                })
                .catch(function (error) {
                    console.log(error);
                });

            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.modal2 = 0;
                this.modal3 = 0;
                this.modal4 = 0;
                this.fecha_anticipo = '';
                this.anticipo = '';
                this.equipamiento = '';
                this.arrayReasignar = [];
                this.id_reasig = '';
                this.lote_reasignar = '';
                this.listarContratos(this.pagination.current_page)
                this.listarContratos2(this.pagination2.current_page)
            },

            cargaArchivo(opciones){
                console.log(opciones)
                this.generalId = opciones.generalId;
                this.upType = opciones.upType;
            },

            abrirModal(opciones){
                    const {accion, data} = {...opciones}
                    switch(accion){
                        case 'solicitar':
                        {
                            this.modal =1;
                            this.tituloModal='Solicitar equipamiento';
                            this.tipoAccion=1;
                            this.paquete = data['paquete'];
                            this.promocion = data['promocion'];
                            this.contrato_id = data['folio'];
                            this.lote_id = data['lote_id'];
                            break;
                        }
                        case 'anticipo':{
                            this.modal2 =1;
                            this.tituloModal='Anticipo';
                            this.tipoAccion=1;
                            this.fecha_anticipo = data['fecha_anticipo'];
                            this.anticipo = data['anticipo'];
                            this.solicitud_id = data['id'];
                            break;
                        }

                        case 'colocacion':{
                            this.modal2 =2;
                            this.tituloModal='Colocación';
                            this.tipoAccion=2;
                            this.fecha_colocacion = data['fecha_colocacion'];
                            this.solicitud_id = data['id'];
                            this.observacion = '';
                            break;
                        }

                        case 'liquidacion':{
                            this.modal2 =1;
                            this.tituloModal='Liquidación';
                            this.tipoAccion=3;
                            this.fecha_liquidacion = data['fecha_liquidacion'];
                            this.liquidacion = data['liquidacion'];
                            this.solicitud_id = data['id'];
                            break;
                        }

                        case 'observaciones':{
                            this.modal3 =1;
                            this.tituloModal='Observaciones';
                            this.solicitud_id = data['id'];
                            this.observacion = '';

                            this.listarObservacion(1, this.solicitud_id)
                            break;
                        }

                        case 'reasignar':{
                            this.modal4 =1;
                            this.tituloModal='Reasignar equipamiento';
                            this.solicitud_id = data['id'];
                            this.lote_reasignar = '';
                            this.id_reasig = '';
                            break;
                        }
                    }
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
            uploadOrder(e){
                e.preventDefault();
                let formData = new FormData();
                let url;
                let me = this;

                formData.append('id', this.generalId);
                formData.append('file', e.target.upFile.files[0]);

                if(this.upType == 1)
                    url = '/equipamiento/indexHistorial/upfile1';
                else
                    url = '/equipamiento/indexHistorial/upfile2';


                axios.post(url, formData).then(
                    () => {
                        me.listarHistorial(1);
                        me.myAlerts.popAlert('Guardado correctamente');
                        document.getElementById("upFilesForm").reset();
                    }
                ).catch(error => console.log(error));
            },
        },
        mounted() {
            this.listarContratos(1);
            this.selectFraccionamientos();
            this.getEmpresa();
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
