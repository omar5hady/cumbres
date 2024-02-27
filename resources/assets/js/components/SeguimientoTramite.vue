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
                    <i class="fa fa-align-justify"></i>Seguimiento de Tramite
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab1" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active show" id="ingresar-tab" data-toggle="tab" href="#ingresar" role="tab" aria-controls="ingresar" aria-selected="true" v-text="'Por Ingresar (' + contadorIngresar +')'"></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="autorizado-tab" data-toggle="tab" href="#autorizado" role="tab" aria-controls="autorizado" aria-selected="false" v-text="'Autorizados (' + contadorAutorizados +')'"></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="liquidacion-tab" data-toggle="tab" href="#liquidacion" role="tab" aria-controls="liquidacion" aria-selected="false" v-text="'Liquidación (' + contadorLiquidacion +')'"></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="programacion-tab" data-toggle="tab" href="#programacion" role="tab" aria-controls="programacion" aria-selected="false" v-text="'Programación de firma (' + contadorProgramacion +')'"></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="enviados-tab" data-toggle="tab" href="#enviados" role="tab" aria-controls="enviados" aria-selected="false" v-text="'Historial (' + contadorEnviados +')'"></a>
                        </li>
                    </ul>

                    <div class="tab-content" id="myTab1Content">
                        <!-- Listado por ingresar -->
                        <div class="tab-pane fade active show" id="ingresar" role="tabpanel" aria-labelledby="ingresar-tab">
                            <ListadoPorIngresar :rolId="rolId"
                                :arrayPorIngresar="arrayPorIngresar"
                                @listarData="listarData"
                                @regresarExpediente="regresarExpediente"
                                @cambiarProceso="cambiarProceso"
                                @continuarContrato="continuarContrato"
                                @abrirModal="abrirModal"
                                @abrirPDF="abrirPDF"
                                @catEspecificaciones="catEspecificaciones"
                                @verObs="verObs"
                            />
                        </div>

                        <!-- Listado de autorizados -->
                        <div class="tab-pane fade" id="autorizado" role="tabpanel" aria-labelledby="autorizado-tab">
                            <ListadoAutorizados :rolId="rolId" :arrayData="arrayPreautorizados"
                                @listarData="listarData"
                                @regresarExpediente="regresarExpediente"
                                @cambiarProceso="cambiarProceso"
                                @abrirModal="abrirModal"
                                @abrirPDF="abrirPDF"
                                @catEspecificaciones="catEspecificaciones"
                                @verObs="verObs"
                                @actualizarVigencia="actualizarVigencia"
                            />
                        </div>

                        <!-- Listado de liquidacion -->
                        <div class="tab-pane fade" id="liquidacion" role="tabpanel" aria-labelledby="liquidacion-tab">
                            <ListadoLiquidacion :rolId="rolId" :arrayData="arrayLiquidados"
                                @listarData="listarData"
                                @regresarExpediente="regresarExpediente"
                                @cambiarProceso="cambiarProceso"
                                @abrirModal="abrirModal"
                                @abrirPDF="abrirPDF"
                                @catEspecificaciones="catEspecificaciones"
                                @actualizarVigencia="actualizarVigencia"
                                @verObs="verObs"
                            ></ListadoLiquidacion>
                        </div>

                        <!-- Listado de programacion de firma -->
                        <div class="tab-pane fade" id="programacion" role="tabpanel" aria-labelledby="programacion-tab">
                            <ListadoProgramacion :rolId="rolId" :arrayData="arrayProgramacion"
                                :tipoAccion="tipoAccion"
                                @listarData="listarData"
                                @abrirModal="abrirModal"
                                @abrirPDF="abrirPDF"
                                @catEspecificaciones="catEspecificaciones"
                                @actualizarVigencia="actualizarVigencia"
                                @verObs="verObs"
                            ></ListadoProgramacion>
                        </div>

                        <!-- Listado de Historial -->
                        <div class="tab-pane fade" id="enviados" role="tabpanel" aria-labelledby="enviados-tab">
                            <ListadoHistorial :rolId="rolId" :arrayData="arrayEnviados"
                                @listarData="listarData"
                                @abrirModal="abrirModal"
                                @abrirPDF="abrirPDF"
                                @catEspecificaciones="catEspecificaciones"
                                @verObs="verObs"
                            ></ListadoHistorial>
                            <Nav :current="pagination.current_page ? pagination.current_page : 1"
                                :last="pagination.last_page ? pagination.last_page : 1"
                                @changePage="cambiarPagina">
                            </Nav>
                        </div>
                    </div>
                    <button class="btn btn-sm btn-default" data-toggle="modal" data-target="#manualId">Manual</button>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>

        <!--Inicio modal-->
        <ModalSeguimiento
            v-if="modal==1"
            :titulo="tituloModal"
            :datos="data"
            :tipoAccion="tipoAccion"
            @closeModal="cerrarModal"
        ></ModalSeguimiento>
        <!--Fin del modal -->

        <!--Inicio modal Liquidación-->
        <ModalLiquidacion
            v-if="modal==2"
            :titulo="tituloModal"
            :datos="data"
            @closeModal="cerrarModal"
        />
        <!--Fin del modal -->

        <!--Inicio del modal observaciones-->
        <ModalObservacion
            v-if="modal==3"
            :titulo="tituloModal"
            :id="id"
            @closeModal="cerrarModal"
        />
        <!--  -->

        <!--Inicio modal Intereses (Pagares)-->
        <ModalIntereses
            v-if="modal==4"
            :titulo="tituloModal"
            :datos="data"
            @closeModal="cerrarModal"
        />
        <!--Fin del modal -->

        <!--Inicio modal Firma de escrituras-->
        <ModalFirmaEsc
            v-if="modal==5"
            :titulo="tituloModal"
            :datos="data"
            @closeModal="cerrarModal"
        />
        <!--Fin del modal -->

        <!--Inicio modal Solicitar Entrega -->
        <ModalEntrega
            v-if="modal==6"
            :titulo="tituloModal"
            :datos="data"
            @closeModal="cerrarModal"
        />
        <!--Fin del modal -->

        <!-- Inicio Modal Archivo Fiscal-->
        <ModalComponent v-if="modal==7"
            :titulo="tituloModal"
            @closeModal="cerrarModal()"
        >
            <template v-slot:body>
                <div class="form-group row">
                    <input type="file"
                        v-show="false"
                        ref="fileFgSelector"
                        @change="onSelectedFileFg"
                        accept="image/png, image/jpeg, image/gif, application/pdf"
                    >
                    <div class="col-md-9" v-if="!archivo">
                        <button
                            @click="onSelectFileFg"
                            class="btn btn-scarlet">
                            Seleccionar Archivo
                            <i class="fa fa-upload"></i>
                        </button>

                    </div>

                    <div class="col-md-7" v-else>
                        <h6 style="color:#1e1d40;">Archivo seleccionado: {{archivo.name}}</h6>
                        <button
                            @click="onSelectFileFg"
                            class="btn btn-info">
                            Cambiar Archivo
                            <i class="fa fa-upload"></i>
                        </button>
                    </div>
                    <div class="col-md-3" v-if="archivo">
                        <button
                            @click="saveFileFg"
                            class="btn btn-scarlet">
                            Guardar Archivo
                            <i class="icon-check"></i>
                        </button>
                    </div>
                </div>
            </template>
            <template v-slot:buttons-footer>
                <a v-if="file_fg != null" class="btn btn-primary btn-sm" target="_blank" v-bind:href="'/contratos/downloadFileFg/'+ file_fg">Ver archivo</a>
            </template>
        </ModalComponent>
        <!--Fin del modal-->

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
                        Dentro del módulo de seguimiento de tramite usted podrá dar el seguimiento a la venta de un lote,
                        además en caso de ser necesario usted podrá detener o regresar el registro de lote al modulo de
                        "Expediente" en caso de que sea necesario con el uso de los botones
                        <button type="button" class="btn btn-danger btn-sm" title="Detener solicitud">
                            <i class="fa fa-hand-paper-o"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm">
                            <i class="fa fa-exclamation-triangle"></i>
                        </button>.
                    </p>
                    <p>
                        <strong>Por ingresar:</strong> dentro de la pestaña de por ingresar podrá encontrar todos aquellos lotes que fueron
                        enviados desde el módulo de expediente (vea modulo <strong>“Gestoría -> Expediente”</strong>), además,
                        podrá realizar el cambio de la fecha del “aviso preventivo en caso de que así lo desee,
                        también podrá ver o agregar observaciones e ingresar el expediente a la pestaña de
                        “autorizados” con el botón de la columna “ingresar expediente” .
                    </p>
                    <p>
                        <strong>Autorizados:</strong> dentro del módulo de autorizados usted podrá.
                        <ul>
                            <li>Actualizar la fecha de aviso preventivo.</li>
                            <li>Cambiar la fecha de vigencia.</li>
                            <li>Realizar la inscripción a Infonavit.</li>
                            <li>Agregar observaciones.</li>
                        </ul>
                        Una vez que el lotea sea enviado tenga inscripción a Infonavit (en caso de que no
                        aplique se debe indicar que no aplica) será enviado a la pestaña de “liquidación”.
                    </p>
                    <p>
                        <strong>Liquidación:</strong> dentro del módulo de liquidación usted podrá.
                        <ul>
                            <li>Actualizar la fecha de aviso preventivo.</li>
                            <li>Cambiar la fecha de vigencia.</li>
                            <li>Liquidar</li>
                            <li>Generar intereses.</li>
                            <li>Solicitar la entrega de vivienda.</li>
                            <li>Agregar observaciones.</li>
                        </ul>
                        En caso de que al generar la liquidación quede algún saldo pendiente debe tomar en
                        cuenta que el sistema pedirá generar intereses (si aplica), para esto, aparecerá un
                        botón con la leyenda “Generar Intereses” que le permitirá generar intereses por el
                        adeudo pendiente (si aplica), al dar clic dentro del botón vera una ventana que le
                        indicara el moto y le permitirá llenar los campos según sea necesario
                        (en caso de que no genere intereses solo deje en 0% el interés), al finalizar el registro
                        será enviado a la pestaña de programación de firma. <br>
                        En caso de que el saldo pendiente sea 0, al generar la liquidación el registro del lote
                            será enviado a la pestaña de programación de firma.
                    </p>
                    <p>
                        <strong>Programación de firma:</strong> dentro del módulo de programación de firma usted podrá.
                        <ul>
                            <li>Actualizar la fecha de aviso preventivo.</li>
                            <li>Cambiar la fecha de vigencia.</li>
                            <li>Imprimir liquidación.</li>
                            <li>Programar la fecha de firma de escritura.</li>
                            <li>Solicitar la entrega de vivienda.</li>
                            <li>Agregar observaciones.</li>
                        </ul>
                        Una vez que sea programado la fecha de la firma de escritura el registro del lote será
                        enviado a la pestaña de Historial.
                    </p>
                    <p>
                        <strong>Historial:</strong> dentro de la pestaña historial usted podrá.
                        <ul>
                            <li>Cambiar la fecha de vigencia.</li>
                            <li>Imprimir liquidación.</li>
                            <li>Agregar observaciones.</li>
                        </ul>
                        Adema podrá ver el historial a todos los registros de lotes que se entregaron con anterioridad,
                        además podrá ver el registro del lote en el módulo de <strong>“Obra -> Viviendas por entregar”</strong>.
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
import ModalComponent from './Componentes/ModalComponent.vue';
import TableComponent from './Componentes/TableComponent.vue';
import Nav from './Componentes/NavComponent.vue';
import ListadoPorIngresar from './Gestoria/ListadoPorIngresar.vue';
import ListadoAutorizados from './Gestoria/ListadoAutorizados.vue';
import ListadoLiquidacion from './Gestoria/ListadoLiquidacion.vue';
import ListadoProgramacion from './Gestoria/ListadoProgramacion.vue';
import ListadoHistorial from './Gestoria/ListadoHistorial.vue';
import ModalSeguimiento from './Gestoria/modales/ModalSeguimiento.vue';
import ModalLiquidacion from './Gestoria/modales/ModalLiquidacion.vue';
import ModalIntereses from './Gestoria/modales/ModalIntereses.vue';
import ModalFirmaEsc from './Gestoria/modales/ModalFirmaEsc.vue';
import ModalEntrega  from './Gestoria/modales/ModalEntrega.vue';
import ModalObservacion from './Gestoria/modales/ModalObservacion'

    export default {
        props:{
            rolId:{type: String}
        },
        components:{
            ModalComponent,
            Nav,
            TableComponent,
            ListadoPorIngresar,
            ListadoAutorizados,
            ListadoLiquidacion,
            ListadoProgramacion,
            ListadoHistorial,
            ModalSeguimiento,
            ModalLiquidacion,
            ModalIntereses,
            ModalFirmaEsc,
            ModalEntrega,
            ModalObservacion
        },
        data(){
            return{
                arrayPreautorizados: [],
                arrayAutorizados: [],
                arrayLiquidados: [],
                arrayProgramacion: [],
                arrayPorIngresar:[],
                arrayEnviados:[],

                band: 0,

                archivo:'',
                file_fg:'',

                //variables para filtros de Por ingresar
                criterio:'lotes.fraccionamiento_id',
                buscar: '',
                b_etapa: '',
                b_manzana: '',
                b_lote: '',
                b_empresa: '',

                id: 0,
                tipoAccion: 0,

                modal: 0,
                contadorIngresar : 0,
                contadorAutorizados : 0,
                contadorLiquidacion : 0,
                contadorProgramacion : 0,
                contadorEnviados: 0,
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                data: {}

            }
        },
        computed:{
        },

        methods : {
            listarData( search ){
                this.buscar = search.buscar;
                this.b_etapa = search.b_etapa
                this.b_manzana = search.b_manzana
                this.b_lote = search.b_lote
                this.b_empresa = search.b_empresa
                this.criterio = search.criterio

                this.listarIngresoExp(1);
                this.listarAutorizados(1);
                this.listarLiquidacion(1);
                this.listarProgramacion(1);
                this.listarEnviados(1);

            },
            abrirPDF(contrato){
                if(contrato.sit_fg == 1 && contrato.file_fg == null){
                    Swal.fire({
                        title: 'Lote con falla Geologica, es necesario cargar archivo.',
                        showCancelButton: false,
                        confirmButtonText: 'Enterado',
                        }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result) {
                            const win = window.open('/estadoCuenta/estadoPDF/'+contrato.folio, '_blank');
                        }
                    })
                }
                else{
                    const win = window.open('/estadoCuenta/estadoPDF/'+contrato.folio, '_blank');
                }
            },

            actualizarVigencia( data ){
                const {folio, valor} = {...data}
                let me = this;
                //Con axios se llama el metodo update de PartidaController
                if(valor=="")
                    return
                else{
                    axios.put('/creditos_select/setFechaVigencia',{
                        'folio': folio,
                        'fecha_vigencia': valor,
                    }).then(function (response){

                        me.listarIngresoExp(1);
                        me.listarAutorizados(1);
                        me.listarLiquidacion(1);
                        me.listarProgramacion(1);
                        me.band = 0;
                        //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000

                        });

                        toast({
                        type: 'success',
                        title: 'Cambios guardados'
                        })
                    }).catch(function (error){
                        console.log(error);
                    });
                }
            },

            verObs(folio){
                this.modal = 3;
                this.tituloModal='Observaciones';
                this.id = folio;
            },

            regresarExpediente(folio){
                swal({
                title: '¿Desea regresar este expediente a integración?',
                text: "Esta acción no se puede revertir!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si, regresar!'
                }).then((result) => {
                if (result.value) {
                    let me = this;

                axios.delete('/expediente/regresarExpediente',
                        {params: {'folio': folio}}).then(function (response){
                        swal(
                            'Listo!',
                            'El expediente ha sido regresado a integración.',
                            'success'
                        )
                        me.listarIngresoExp(1);
                        me.listarAutorizados(1);
                        me.listarLiquidacion(1);
                        me.listarProgramacion(1);
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },

            catEspecificaciones(id){
                let nombre_archivo_modelo = '';
                const url = '/contrato/modelo/caracteristicas/pdf/' + id;
                axios.get(url).then(function (response) {
                    const respuesta = response.data;
                        nombre_archivo_modelo = respuesta.modelo[0].archivo;
                        window.open('/files/modelos/' + nombre_archivo_modelo, '_blank')
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            cambiarProceso( data ){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/contrato/cambiarProceso',{
                    'id': data.folio,
                    'detenido' : data.opc

                }).then(function (response){
                    me.listarIngresoExp(1);
                    me.listarAutorizados(1);
                    me.listarLiquidacion(1);
                    me.listarProgramacion(1);
                    //window.alert("Cambios guardados correctamente");
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Contrato detenido correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });

            },

            continuarContrato( data ){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/contrato/cambiarProceso',{
                    'id': data.folio,
                    'detenido' : data.opc

                }).then(function (response){
                    me.listarIngresoExp(1);
                    me.listarAutorizados(1);
                    me.listarLiquidacion(1);
                    me.listarProgramacion(1);
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'El contrato continua correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });

            },
            onSelectFileFg(){
                this.$refs.fileFgSelector.click()
            },
            onSelectedFileFg( event ){
                this.archivo = {}
                this.archivo = event.target.files[0]
            },
            saveFileFg(){
                let formData = new FormData();

                formData.append('archivo', this.archivo);
                formData.append('id', this.id);
                let me = this;
                axios.post('/contratos/formSubmitFileFg/'+me.id, formData)
                .then(function (response) {

                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Archivo Fiscal guardado correctamente',
                        showConfirmButton: false,
                        timer: 2000
                        })
                    me.archivoFileFg = me.archivo.name
                    me.archivo = undefined;
                    me.listarIngresoExp(1);
                    me.listarAutorizados(1);
                    me.listarLiquidacion(1);
                    me.listarProgramacion(1);
                    me.listarEnviados(1);

                }).catch(function (error) {
                    console.log(error);
                });
            },

            listarIngresoExp(page){
                let me = this;
                var url = '/expediente/ingresarIndex?page=' + page +
                    '&buscar=' + me.buscar + '&b_etapa=' + me.b_etapa +
                    '&b_manzana=' + me.b_manzana + '&b_lote=' + me.b_lote +
                    '&criterio=' + me.criterio + '&b_empresa=' + me.b_empresa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPorIngresar = respuesta.contratos;
                    me.contadorIngresar = respuesta.contador;
                })
                .catch(function (error) {
                    console.log(error);
                });

            },

            listarAutorizados(page){
                let me = this;
                var url = '/expediente/autorizadosIndex?page=' + page +
                    '&buscar=' + me.buscar + '&b_etapa=' + me.b_etapa +
                    '&b_manzana=' + me.b_manzana + '&b_lote=' + me.b_lote +
                    '&criterio=' + me.criterio + '&b_empresa=' + me.b_empresa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPreautorizados = respuesta.contratos;
                    me.contadorAutorizados = respuesta.contador;
                })
                .catch(function (error) {
                    console.log(error);
                });

            },

            cambiarPagina(page){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarEnviados(page);
            },

            listarEnviados(page){
                let me = this;
                var url = '/expediente/enviadosIndex?page=' + page +
                '&buscar=' + me.buscar + '&b_etapa=' + me.b_etapa +
                '&b_manzana=' + me.b_manzana + '&b_lote=' + me.b_lote +
                '&criterio=' + me.criterio+'&b_empresa=' + me.b_empresa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEnviados = respuesta.contratos.data;
                    me.pagination = respuesta.pagination;
                    me.contadorEnviados = respuesta.contador;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            listarLiquidacion(page){
                let me = this;
                var url = '/expediente/liquidacionIndex?page=' + page +
                '&buscar=' + me.buscar + '&b_etapa=' + me.b_etapa +
                '&b_manzana=' + me.b_manzana + '&b_lote=' + me.b_lote +
                '&criterio=' + me.criterio +'&b_empresa=' + me.b_empresa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLiquidados = respuesta.contratos;
                    me.contadorLiquidacion = respuesta.contador;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            listarProgramacion(page){
                let me = this;
                var url = '/expediente/ProgramacionIndex?page=' + page +
                '&buscar=' + me.buscar + '&b_etapa=' + me.b_etapa +
                '&b_manzana=' + me.b_manzana + '&b_lote=' + me.b_lote +
                '&criterio=' + me.criterio +'&b_empresa=' + me.b_empresa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayProgramacion = respuesta.contratos;
                    me.contadorProgramacion = respuesta.contador;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            abrirModal( opciones ){
                this.data = {}
                const {opcion, data} = {...opciones}
                switch(opcion){
                    case 'sit_fg':{
                        this.tituloModal = 'Archivo situación geologica';
                        this.id = data['folio'];
                        this.archivo = undefined;
                        this.file_fg = data['file_fg'];
                        this.modal = 7;
                        break;
                    }
                    case 'fecha_recibido':
                    {
                        this.tituloModal='Fecha recibido';
                        this.tipoAccion = 3;
                        this.data.fecha_recibido = data['aviso_prev'];
                        this.data.id = data['folio'];
                        this.modal = 1;
                        break;
                    }
                    case 'ingresar':
                    {
                        this.tituloModal='Ingresar expediente';
                        this.tipoAccion = 2;
                        this.data.fecha_ingreso = '';
                        this.data.valor_escrituras= '0';
                        this.data.id = data['folio'];
                        this.modal = 1;
                        break;
                    }

                    case 'autorizado':
                    {
                        this.tituloModal='Inscripción a Infonavit';
                        this.tipoAccion = 4;
                        this.data.fecha_infonavit = '';
                        this.data.id = data['folio'];
                        this.modal = 1;
                        break;
                    }
                    case 'liquidacion':
                    {
                        this.tituloModal='Generar Liquidación';
                        this.data.id = data['folio'];
                        this.data.fecha_firma_contrato = data['fecha_status'];
                        this.data.cliente = data['nombre_cliente'];
                        this.data.credito = data['tipo_credito'];
                        this.data.inst_fin = data['institucion'];
                        this.data.proyecto = data['proyecto'];
                        this.data.etapa = data['etapa'];
                        this.data.manzana = data['manzana'];
                        this.data.lote = data['num_lote'];
                        this.data.valor_escrituras = data['valor_escrituras'];
                        this.data.valor_venta = data['precio_venta'];
                        this.data.intereses_terreno = data['intereses_terreno'];
                        this.data.descuento = 0;
                        this.data.totalEnganghe = 0;
                        this.data.pagado = 0;
                        this.data.monto_credito = data['credito_solic'];
                        this.data.infonavit = data['infonavit'];
                        this.data.fovissste = data['fovissste'];
                        this.data.avaluo = data['resultado'];
                        this.data.total_liquidar = 0

                        this.modal = 2;
                        break;
                    }

                    case 'intereses':
                    {
                        this.tituloModal='Intereses';
                        this.data.id = data['folio'];
                        this.data.total_liquidar1=data['total_liquidar'];
                        this.data.int_oridinario = 5;
                        this.data.int_moratorio = 5;
                        this.data.fecha_ini_interes = '';
                        this.data.fecha_pago = '';
                        this.data.nombre_aval = '';
                        this.data.direccion_aval = '';
                        this.data.telefono_aval = '';
                        this.data.nombre_aval2 = '';
                        this.data.direccion_aval2 = '';
                        this.data.telefono_aval2 = '';
                        this.data.arrayPagos = [];
                        this.data.restante_pago=this.data.total_liquidar1;
                        this.modal = 4;
                        break;
                    }

                    case 'firma_esc':
                    {
                        if(data['nombre_recomendado'] == null && data['publicidad_id'] == 1 || data['nombre_recomendado'] == '' && data['publicidad_id'] == 1){
                            Swal({
                                    title: 'Recomendado!',
                                    text: 'No se encuentra registrado el nombre de la persona que lo recomienda',
                                    type: 'info',
                                    animation: false,
                                    customClass: 'animated bounceInRight'
                                })
                        }
                        else{
                            this.tipoAccion = 1;
                            this.tituloModal='Instrucción Notarial';
                            this.data.id = data['folio'];
                            this.data.proyecto = data['proyecto'];
                            this.data.etapa = data['etapa'];
                            this.data.manzana = data['manzana'];
                            this.data.lote = data['num_lote'];
                            this.data.valor_venta = data['precio_venta'];
                            this.data.monto_credito = data['credito_solic'];
                            this.data.infonavit = data['infonavit'];
                            this.data.fovissste = data['fovissste'];
                            this.data.diferencia = parseFloat(this.data.valor_venta) - parseFloat(this.data.monto_credito) -
                            parseFloat(this.data.infonavit) - parseFloat(this.data.fovissste);

                            this.data.hora_firma = '';
                            this.data.notaria_id = 0;
                            this.data.estado = '';
                            this.data.ciudad = '';
                            this.data.direccion_firma = ''
                            this.data.notario = ''
                            this.data.notaria = ''
                            this.modal = 5;
                        }
                        break;
                    }

                    case 'firma_esc_act':
                    {
                        this.tipoAccion = 2;
                        this.tituloModal='Instrucción Notarial';
                        this.data.id = data['folio'];
                        this.data.proyecto = data['proyecto'];
                        this.data.etapa = data['etapa'];
                        this.data.manzana = data['manzana'];
                        this.data.lote = data['num_lote'];
                        this.data.valor_venta = data['precio_venta'];
                        this.data.monto_credito = data['credito_solic'];
                        this.data.infonavit = data['infonavit'];
                        this.data.fovissste = data['fovissste'];
                        this.data.diferencia = parseFloat(this.data.valor_venta) - parseFloat(this.data.monto_credito) -
                        parseFloat(this.data.infonavit) - parseFloat(this.data.fovissste);

                        this.data.hora_firma = data['hora_firma'];
                        this.data.notaria_id = data['notaria_id'];
                        this.data.direccion_firma = data['direccion_firma'];
                        this.data.notario = data['notario'];
                        this.data.estado = 'San Luis Potosí';
                        this.data.ciudad = 'San Luis Potosí';
                        this.data.fecha_firma_esc = data['fecha_firma_esc'];

                        this.modal = 5;
                        break;
                    }

                    case 'avaluo':{
                        this.tituloModal='Avaluo concluido';
                        this.tipoAccion = 5;
                        this.data.fecha_concluido = data['fecha_concluido'];
                        this.data.resultado = data['resultado'];
                        this.data.avaluoId = data['avaluoId'];
                        this.modal = 1;
                        break;
                    }

                    case 'solic_entrega':{
                        this.modal = 6;
                        this.data.id = data['folio'];
                        this.tituloModal='Solicitar Entrega';
                        this.observacion='';
                        break;
                    }
                }
            },

            cerrarModal(){
                this.modal = 0;
                this.data = {}
                this.listarIngresoExp(1);
                this.listarAutorizados(1);
                this.listarLiquidacion(1);
                this.listarProgramacion(1);
                this.listarEnviados(1);
            },
        },

        mounted() {
            this.$root.selectFraccionamientos();
            this.$root.selectGestores();
            this.$root.getEmpresa()
            this.listarIngresoExp(1);
            this.listarAutorizados(1);
            this.listarLiquidacion(1);
            this.listarProgramacion(1);
            this.listarEnviados(1);
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
