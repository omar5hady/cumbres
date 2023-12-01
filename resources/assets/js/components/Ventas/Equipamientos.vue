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
                            <button type="submit" v-if="historial == 0" class="btn btn-dark" @click="listarHistorial(1)">
                                <i class="fa fa-list-alt"></i> Historial de equipamientos
                            </button>
                        <!---->
                        <!--   Boton regresar  -->
                            <button type="submit" v-if="historial == 1" class="btn btn-light" @click="listarContratos(1)">
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
                                        <option v-for="fraccionamiento in arrayFraccionamientos" :key="fraccionamiento.nombre" :value="fraccionamiento.id" v-text="fraccionamiento.nombre"></option>
                                    </select>

                                    <input v-else type="text"  v-model="buscar2" @keyup.enter="listarHistorial(1)" class="form-control" placeholder="Texto a buscar">
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
                                    <button type="submit" @click="listarHistorial(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>

                        <TableHistorial
                            :arrayData="arrayHistorialEquipamientos"
                            @abrirModal="abrirModal"
                            @cargaArchivo="cargaArchivo"
                            @closeModal="cerrarModal"
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
                <ModalAcciones
                    v-if="modal2"
                    :titulo="tituloModal"
                    :tipoAccion="tipoAccion"
                    :fecha_anticipo="fecha_anticipo"
                    :anticipo="anticipo"
                    :fecha_colocacion="fecha_colocacion"
                    :fecha_liquidacion="fecha_liquidacion"
                    :liquidacion="liquidacion"
                    :solicitud_id="solicitud_id"
                    @closeModal="cerrarModal"
                >
                </ModalAcciones>
            <!--Fin del modal-->

            <!--Inicio del modal observaciones-->
                <ModalObservaciones
                    v-if="modal3"
                    :solicitud_id="solicitud_id"
                    :titulo="tituloModal"
                    @closeModal="cerrarModal"
                >
                </ModalObservaciones>
            <!--Fin del modal-->
                <ModalReubicar
                    v-if="modal4"
                    :titulo="tituloModal"
                    :tipoAccion="tipoAccion"
                    :solicitud_id="solicitud_id"
                    :arrayFraccionamientos="arrayFraccionamientos"
                    @closeModal="cerrarModal"
                >
                </ModalReubicar>
            <!--Inicio del modal para reubicar -->

            <!--Fin del modal-->

            <!-- carga de archivos -->
                <div class="modal fade" id="cargaPago1" role="dialog" aria-labelledby="manualIdTitle" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header" style="color:white; background-color: #00ADEF;">
                            <h5 class="modal-title" id="manualIdTitle">
                                {{ tituloModal }}
                            </h5>
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
import ModalAcciones from './components/Equipamiento/ModalAcciones.vue'
import ModalObservaciones from './components/Equipamiento/ModalObservaciones.vue'
import ModalReubicar from './components/Equipamiento/ModalReubicar.vue'

    export default {
        components:{
            ModalComponent,
            TableContratos,
            TableHistorial,
            RowModal,
            NavComponent,
            ModalComponent,
            TableComponent,

            ModalSolicEquip,
            ModalAcciones,
            ModalObservaciones,
            ModalReubicar
        },
        data(){
            return{
                historial:0,

                arrayContratos : [],

                arrayFraccionamientos:[],
                arrayEtapas:[],
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

            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.modal2 = 0;
                this.modal3 = 0;
                this.modal4 = 0;
                this.fecha_anticipo = '';
                this.anticipo = '';
                this.equipamiento = '';
                this.listarContratos(this.pagination.current_page)
                this.listarHistorial(this.pagination2.current_page)
            },

            cargaArchivo(opciones){
                console.log(opciones)
                this.tituloModal = 'Comprobante de Pago'
                this.generalId = opciones.generalId;
                this.upType = opciones.upType;
                if(this.upType == 3){
                    this.tituloModal = 'Cargar Render'
                }
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
                        this.modal2 =1;
                        this.tituloModal='Colocación';
                        this.tipoAccion=2;
                        this.fecha_colocacion = data['fecha_colocacion'];
                        this.solicitud_id = data['id'];
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
                        break;
                    }
                    case 'reasignar':{
                        this.modal4 =1;
                        this.tituloModal='Reasignar equipamiento';
                        this.solicitud_id = data['id'];
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
                if(this.upType == 2)
                    url = '/equipamiento/indexHistorial/upfile2';
                if(this.upType == 3)
                    url = '/equipamiento/indexHistorial/saveRender';


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
