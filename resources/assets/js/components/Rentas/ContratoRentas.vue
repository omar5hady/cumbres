<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <strong><a style="color:#FFFFFF;" href="/">Home</a></strong>
            </li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card scroll-box">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Rentas
                    <!--   Boton agregar    -->
                    <button @click="nuevoContrato()"  type="button" class="btn btn-primary" v-if="listado == 1">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                    <button type="button" @click="salir()" class="btn btn-success" v-if="listado > 1">
                        <i class="fa fa-mail-reply"></i>&nbsp;Regresar
                    </button>
                    <button type="button" @click="actualizar = 1" class="btn btn-warning" v-if="listado > 1 && actualizar == 0 && datosRenta.status == 1">
                        <i class="icon-pencil"></i>&nbsp;Vista actualizar
                    </button>
                    <button type="button" @click="actualizar = 0, getDatos(datosRenta.id)" class="btn btn-warning" v-if="listado > 1 && actualizar == 1 && datosRenta.status == 1">
                        <i class="icon-pencil"></i>&nbsp;Ocultar vista actualizar
                    </button>

                    <div style="text-align: right;" v-if="listado == 2">
                        <label for="text-input"> <strong>Status</strong> </label>
                        <select v-model="datosRenta.status"
                        v-if="datosRenta.status != 0 "
                        @change="selectStatus(datosRenta.status)">
                            <option value="0">Cancelar</option>
                            <option value="1">Pendiente</option>
                            <option value="2">Firmar</option>
                        </select>
                    </div>
                </div>

                <!----------------- Listado Contratos ------------------------------>
                <!-- Div Card Body para listar -->
                <template v-if="listado == 1">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-10">
                                <div class="input-group">
                                    <select class="form-control col-md-5" v-model="b_proyecto" @change="selectEtapas(b_proyecto)">
                                        <option value="">Fraccionamiento</option>
                                        <option v-for="proyecto in $root.$data.proyectos" :key="proyecto.id"  :value="proyecto.id" v-text="proyecto.nombre"></option>
                                    </select>
                                    <select class="form-control col-md-4" v-model="b_etapa">
                                        <option value="">Etapa</option>
                                        <option v-for="etapa in arrayEtapas" :key="etapa.id"  :value="etapa.id" v-text="etapa.num_etapa"></option>
                                    </select>
                                </div>
                                <div class="input-group">
                                    <input type="text" v-model="b_direccion" @keyup.enter="listarContratos(1)" class="form-control" placeholder="Dirección"/>
                                </div>
                                <div class="input-group">
                                    <input type="text" v-model="b_cliente" @keyup.enter="listarContratos(1)" class="form-control" placeholder="Arrendatario"/>
                                </div>
                                <div class="input-group">
                                    <label class="form-control col-md-3">Estatus</label>
                                    <select class="form-control col-md-4" v-model="b_status">
                                        <option value="">Todos</option>
                                        <option value="0">Cancelados</option>
                                        <option value="1">Pendientes</option>
                                        <option value="2">Vigentes</option>
                                        <option value="3">Finalizados</option>
                                    </select>
                                </div>
                                <button type="submit" @click="listarContratos(1)" class="btn btn-primary">
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                        <TableComponent :cabecera="[
                            '', '# Contrato', 'Cliente','Proyecto','Etapa', 'Dirección',
                            'Costo de renta', 'Fecha de termino', 'Status',
                        ]">
                            <template v-slot:tbody>
                                <tr v-for="contrato in arrayContratos.data" :key="contrato.id"
                                    :class="contrato.diff < 0 && contrato.status == 2 ? 'table-danger' : ''"
                                >
                                        <td>
                                            <button
                                                type="button" title="Subir contrato" @click="vistaArchivo(contrato.id)" class="btn btn-danger btn-sm">
                                                <i class="fa fa-files-o"></i>
                                            </button>
                                        </td>
                                        <td class="td2" v-text="contrato.id"></td>
                                        <td class="td2">
                                            <a v-on:dblclick="verContrato(contrato.id)"
                                            title="Ver contrato"
                                            href="#" v-text="contrato.nombre_arrendatario.toUpperCase()"></a>
                                        </td>
                                        <td class="td2" v-text="contrato.proyecto"></td>
                                        <td class="td2" v-text="contrato.etapa"></td>
                                        <td class="td2" v-if="contrato.interior == null" v-text="contrato.calle+' Num. ' + contrato.numero"></td>
                                        <td class="td2" v-else v-text="contrato.calle+' Num. ' + contrato.numero + '-'+ contrato.interior"></td>
                                        <td class="td2" v-text="'$' +$root.formatNumber(contrato.monto_renta)"></td>
                                        <td class="td2" v-text="this.moment(contrato.fecha_fin).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td class="td2">
                                            <span v-if="contrato.status == 0" class="badge badge-danger">Cancelado</span>
                                            <span v-if="contrato.status == 1" class="badge badge-warning">Pendiente</span>
                                            <span v-if="contrato.status == 2" class="badge badge-success">Vigente</span>
                                            <span v-if="contrato.status == 3" class="badge badge-primary">Finalizado</span>
                                        </td>
                                    </tr>
                            </template>
                        </TableComponent>
                        <Nav
                            :current="arrayContratos.current_page ? arrayContratos.current_page : 1"
                            :last="arrayContratos.last_page ? arrayContratos.last_page : 1"
                            @changePage="listarContratos"
                        ></Nav>
                    </div>
                </template>

                <!----------------- Vista para crear un contrato ------------------------------>
                <!-- Div Card Body para registrar simulacion -->
                <template v-if="listado >= 2">
                    <FormContrato
                        :listado="listado"
                        :datosRenta="datosRenta"
                        :actualizar="actualizar"
                        @salir="salir()"
                        @modal="abrirModal"
                    ></FormContrato>
                </template>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>

        <!-- Inicio Modal -->
        <ModalComponent v-if="modal>=1"
            @closeModal="cerrarModal"
            :titulo="tituloModal"
        >
            <template v-slot:body>
                <template v-if="modal<3">
                    <RowModal v-if="datosRenta.tipo_arrendador == 1"
                        label1="Apoderado legal:" clsRow1="col-md-6" id1="apoderado"
                    >
                        <select class="form-control" v-model="apoderado" id="apoderado">
                            <option value="ING. DAVID CALVILLO MARTINEZ">ING. DAVID CALVILLO MARTINEZ</option>
                            <option value="ING. ALEJANDRO F. PEREZ ESPINOSA">ING. ALEJANDRO F. PEREZ ESPINOSA</option>
                            <option value="C.P. MARTIN HERRERA SANCHEZ">C.P. MARTIN HERRERA SANCHEZ</option>
                        </select>
                    </RowModal>

                    <RowModal v-if="modal==1"
                        label1="Testigo:" clsRow1="col-md-6" id1="testigo"
                    >
                        <select class="form-control" v-model="testigo" id="testigo">
                            <option v-for="testigo in arrayTestigos" :key="testigo.id"  :value="testigo.nombre" v-text="testigo.nombre"></option>
                        </select>
                    </RowModal>
                </template>
                <template v-if="modal == 3">
                    <RowModal v-if="datosRenta.status == 2"
                        label1="¿Se requiere factura?" clsRow1="col-md-3" id1="facturar"
                    >
                        <select class="form-control" v-model="datosRenta.facturar" id="facturar">
                            <option value="0">No</option>
                            <option value="1">Si</option>
                        </select>
                    </RowModal>

                    <template v-if="datosRenta.facturar == 1">
                        <hr>
                        <div class="col-md-4">
                            <center><h6>Datos Fiscales</h6></center>
                        </div>
                        <RowModal label1="Correo electrónico" clsRow1="col-md-4" id1="email"
                            label2="Teléfono" clsRow2="col-md-3" id2="telefono"
                        >
                            <input type="email" v-model="datosRenta.email_fisc" class="form-control" placeholder="Email" id="email">
                            <template v-slot:input2>
                                <input id="telefono" type="text" v-on:keypress="$root.isNumber($event)" pattern="\d*" v-model="datosRenta.tel_fisc" class="form-control" maxlength="10" placeholder="Telefono">
                            </template>
                        </RowModal>
                        <RowModal label1="Nombre" clsRow1="col-md-6" id1="nombre">
                            <input id="nombre" type="text" v-model="datosRenta.nombre_fisc" class="form-control" placeholder="Nombre completo">
                        </RowModal>
                        <RowModal label1="Dirección" clsRow1="col-md-6" id1="direccion">
                            <input id="direccion" type="text" v-model="datosRenta.direccion_fisc" class="form-control" placeholder="Dirección">
                        </RowModal>

                        <RowModal label1="Colonia" clsRow1="col-md-4" id1="col_fisc"
                            label2="C.P." clsRow2="col-md-3" id2="cp_fisc"
                        >
                            <input type="text" v-model="datosRenta.col_fisc" class="form-control" placeholder="Colonia" id="col_fisc">
                            <template v-slot:input2>
                                <input id="cp_fisc" type="text" v-on:keypress="$root.isNumber($event)" pattern="\d*" v-model="datosRenta.cp_fisc" class="form-control" maxlength="10" placeholder="Código Postal">
                            </template>
                        </RowModal>
                        <RowModal label1="RFC" clsRow1="col-md-3" id1="rfc"
                            label2="Uso del C.F.D.I." clsRow2="col-md-4" id2="cfdi"
                        >
                            <input type="text" maxlength="13"  style="text-transform:uppercase" id="rfc"
                                v-model="datosRenta.rfc_fisc" class="form-control" placeholder="RFC">
                            <template v-slot:input2>
                                <select class="form-control" v-model="datosRenta.cfi_fisc" id="cfdi">
                                    <option value="">Seleccione</option>
                                    <option v-for="(item, index) in arrayCFI" :value="item" :key="index">{{ item }}</option>
                                </select>
                            </template>
                        </RowModal>
                        <RowModal label1="Régimen Fiscal del cliente" clsRow1="col-md-4" id1="regimen_fisc"
                        >
                            <select class="form-control" v-model="datosRenta.regimen_fisc" id="regimen_fisc">
                                <option value="">Seleccione</option>
                                <option v-for="(item, index) in arrayRegimen" :value="item" :key="index">{{ item }}</option>
                            </select>
                        </RowModal>
                        <RowModal label1="Banco" clsRow1="col-md-4" id1="banco"
                            label2="No. Cuenta" clsRow2="col-md-4" id2="num_cuenta"
                        >
                            <input type="text" id="banco"
                                v-model="datosRenta.banco_fisc" class="form-control" placeholder="Banco">
                            <template v-slot:input2>
                                <input type="text" v-on:keypress="$root.isNumber($event)" pattern="\d*" id="num_cuenta"
                                    v-model="datosRenta.num_cuenta_fisc" class="form-control" placeholder="# Cuenta">
                            </template>
                        </RowModal>
                        <RowModal label1="Clabe" clsRow1="col-md-4" id1="clabe"
                        >
                            <input type="text" id="clabe"
                                v-model="datosRenta.clabe_fisc" class="form-control" placeholder="Clabe">
                        </RowModal>
                    </template>
                    <hr>
                    <RowModal label1="Fecha" clsRow1="col-md-9" id1="fecha_firma">
                        <input type="date" v-model="datosRenta.fecha_firma" class="form-control" id="fecha_firma">
                    </RowModal>
                    <RowModal v-if="datosRenta.status == 0"
                        label1="Motivo de cancelación" clsRow1="col-md-9" id1="motivo_cancel">
                        <textarea rows="3" cols="30" v-model="motivo_cancel" class="form-control"
                            id="motivo_cancel" placeholder="Observaciones"></textarea>
                    </RowModal>
                </template>
                <template v-if="modal == 4">
                    <form  method="post" @submit="formSubmitContrato" enctype="multipart/form-data">
                        <div class="form-group row">
                            <label class="col-md-2 form-control-label" for="text-input">Archivo</label>
                            <div class="col-md-9">
                                <input type="file" accept="application/pdf" class="form-control" v-on:change="onArchivo">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-9"></div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-success">Guardar Archivo.</button>
                            </div>
                        </div>

                        <br/>
                    </form>
                </template>
            </template>
            <template v-slot:buttons-footer>
                <template v-if="modal == 1">
                    <a class="btn btn-primary" v-bind:href="'/rentas/printContrato?id=' + datosRenta.id + '&representante=' + apoderado + '&testigo=' + testigo"  target="_blank">
                        <i></i>Imprimir
                    </a>
                    <a class="btn btn-danger btn-sm" target="_blank"
                        v-if="datosRenta.adendum == 1"
                        v-bind:href="'/rentas/printAdendum?id='+datosRenta.id + '&representante=' + apoderado">ADENDUM
                    </a>
                </template>
                <a v-if="modal == 2" class="btn btn-primary" v-bind:href="'/rentas/printDepositoGarantia?id=' + datosRenta.id + '&representante=' + apoderado"  target="_blank">
                    <i></i>Imprimir
                </a>
                <template v-if="datosRenta.fecha_firma != null && modal == 3">
                    <button type="button" v-if="datosRenta.status == 0 || (datosRenta.status == 2 && datosRenta.facturar == 0)" class="btn btn-primary" @click="changeStatus(datosRenta.status)">Guardar</button>
                    <button type="button"
                        v-if="datosRenta.status == 2 && datosRenta.facturar == 1
                            && datosRenta.email_fisc != '' && datosRenta.email_fisc != null
                            && datosRenta.tel_fisc != '' && datosRenta.tel_fisc != null
                            && datosRenta.nombre_fisc != '' && datosRenta.nombre_fisc != null
                            && datosRenta.direccion_fisc != '' && datosRenta.direccion_fisc != null
                            && datosRenta.regimen_fisc != '' && datosRenta.cfi_fisc != null
                            && datosRenta.cp_fisc != '' && datosRenta.cp_fisc != null"
                        class="btn btn-success" @click="changeStatus(datosRenta.status)"
                    >Guardar</button>

                </template>
                <template v-if="modal == 4 && datosRenta.archivo_contrato != null">
                    <button type="button" class="btn btn-success" @click="verArchivo(datosRenta.archivo_contrato)">Ver archivo</button>
                </template>
            </template>
        </ModalComponent>
        <!--Fin del modal-->

    </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
import TableComponent from '../Componentes/TableComponent.vue'
import Nav from '../Componentes/NavComponent.vue'
import FormContrato from './Components/FormContrato.vue'
import ModalComponent from '../Componentes/ModalComponent.vue';
import RowModal from '../Componentes/ComponentesModal/RowModalComponent.vue';

export default {
    components:{
        TableComponent,
        Nav,
        FormContrato,
        ModalComponent,
        RowModal
    },
    props: {
        rolId: { type: String }
    },
    data() {
        return {
            arrayContratos : [],
            arrayCasas: [],
            datosRenta: {},
            arrayEtapas: [],
            arrayEtapasInv: [],
            arrayDisponibles: [],
            arrayCFI:[
                "Adquisición de mercancias",
                "Devoluciones, Descuentos o bonificaciones",
                "Gastos en general",
                "Construcciones",
                "Mobiliario y equipo de oficina por inversiones",
                "Equipo de transporte",
                "Equipo de computo y accesorios",
                "Dados, troqueles, moldes, matrices y herramental",
                "Comunicaciones telefónicas",
                "Comunicaciones satelitales",
                "Otra maquinaria y equipo",
                "Honorarios médicos, dentales y gastos hospitalarios",
                "Gastos médicos por incapacidad o discapacidad",
                "Gastos funerales",
                "Donativos",
                "Intereses reales efectivamente pagados por créditos hipotecarios",
                "Aportaciones voluntarias al SAR",
                "Primas por seguros de gastos médicos",
                "Gastos de transportación escolar obligatoria",
                "Depósitos en cuentas para el ahorro",
                "Pagos por servicios educativos (colegiaturas)",
                "Sin efectos fiscales",
                "Pagos",
                "Nómina"
            ],
            arrayRegimen:[
                "Sueldos y Salarios e Ingresos Asimilados a Salarios",
                "Arrendamiento",
                "Régimen e Enajenación o Adquisición de Bienes",
                "Demás ingresos",
                "Residentes en el Extranjero sin Establecimiento Permanente en México",
                "Ingresos por Dividendos (socios y accionistas)",
                "Personas Físicas con Actividades Empresariales y Profesionales",
                "Ingresos por intereses",
                "Régimen de los ingresos por obtención de premios",
                "Sin obligaciones fiscales",
                "Incorporación Fiscal",
                "Régimen de las Actividades Empresariales con ingresos a través de Plataforma",
                "Régimen Simplificado de Confianza"
            ],
            arrayColonias: [],
            arrayTestigos : [],

            listado: 1,
            b_cliente: '',
            b_proyecto: '',
            b_etapa: '',
            b_direccion: '',
            b_status: '2',
            b_fecha_fin: '',
            status:0,
            tituloModal: '',
            modal:0,
            fecha_status:'',
            apoderado : 'C.P. MARTIN HERRERA SANCHEZ',
            testigo: 'JUAN URIEL ALFARO GALVAN',
            motivo_cancel : '',
            actualizar : 0,
            archivo: ''
        };
    },
    computed: {

    },
    methods: {
        onArchivo(e){
            this.archivo = e.target.files[0];
        },
        abrirModal(value){
            let me = this;
            me.modal = value
            switch(value){
                case 1:
                    me.tituloModal = 'Imprimir contrato'
                break;
                case 2:
                    me.tituloModal = 'Depósito de Garantia'
                break;
                case 3:
                    me.tituloModal = 'Cambiar Status'
                break;
                case 4:
                    me.tituloModal = 'Subir contrato'
                break;
            }
        },
        cerrarModal(){
            if(this.modal == 3)
                this.getDatos(this.datosRenta.id)
            this.modal = 0

        },
        formSubmitContrato(e) {
            e.preventDefault();
            let currentObj = this;
            let formData = new FormData();

            formData.append('archivo', this.archivo);
            let me = this;
            axios.post('/rentas/formSubmitContrato/'+me.datosRenta.id, formData)
            .then(function (response) {
                currentObj.success = response.data.success;
                swal({
                    position: 'top-end',
                    type: 'success',
                    title: 'Archivo guardado correctamente',
                    showConfirmButton: false,
                    timer: 2000
                    })
                me.salir();
                me.modal = 0;
            }).catch(function (error) {
                currentObj.output = error;
                console.log(error);
            });

        },
        verArchivo(archivo){
            window.open('/files/rentas/contratos/'+archivo, '_blank')
        },
        vistaArchivo(id){
            this.abrirModal(4)
            this.getDatos(id);
            this.archivo = '';
        },
        getTestigos(){
            let me = this;

            me.arrayTestigos=[];
            var url = '/rentas/getTestigos';
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayTestigos = respuesta;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        /**Metodo para mostrar los registros */
        listarContratos(page){
            let me = this;
            me.arrayContratos = [];
            var url = '/rentas/indexRentas?page=' + page
                + '&b_proyecto=' + this.b_proyecto
                + '&b_etapa=' + this.b_etapa
                + '&b_direccion=' + this.b_direccion
                + '&b_status=' + this.b_status
                + '&b_cliente=' + this.b_cliente;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayContratos = respuesta;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        selectStatus(status){
            let me = this;
            if(status==2 || status==0 ){
                me.abrirModal(3)
            }else{
                me.changeStatus(status);
            }
        },
        changeStatus(status){
            let me = this;
            swal({
                title: 'Esta seguro de cambiar el status de este contrato?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    Swal.showLoading()
                    axios.put('/rentas/changeStatus',{
                        'id': this.datosRenta.id,
                        'status': status,
                        'motivo_cancel' : this.motivo_cancel,
                        'datosRenta' : this.datosRenta,
                        }).then(function (response){
                        me.salir();
                        me.modal = 0;
                        Swal.enableLoading()
                        swal(
                            'Cambio de status!',
                            'Cambios realizados con éxito.',
                            'success'
                        )
                    }).catch(function (error){
                        console.log(error);
                        Swal.enableLoading()
                    });

                } else if (result.dismiss === swal.DismissReason.cancel
                    )me.getDatos(me.datosRenta.id);
            })
        },

        //Select todas las etapas
        selectEtapas(buscar){
            let me = this;
            me.b_etapa='';

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

        limpiarDatosRenta(){
            this.datosRenta = {
                'fraccionamiento_id':'',
                'etapa_id': '',
                'lote_id': '',
                'tipo_arrendatario': 0,
                'nombre_arrendatario': '',
                'tel_arrendatario': '',
                'clv_lada_arr':52,
                //Moral arrendatario
                'representante_arrendatario': '',
                'dir_arrendatario': '',
                'cp_arrendatario': '',
                'col_arrendatario': '',
                'estado_arrendatario': '',
                'municipio_arrendatario': '',
                'rfc_arrendatario':'',
                //Aval (Fiador)
                'tipo_aval': 0,
                'nombre_aval': '',
                'tel_aval':'',
                'clv_lada_aval':52,
                //Moral aval
                'representante_aval': '',
                'dir_aval': '',
                'cp_aval': '',
                'col_aval': '',
                'estado_aval': '',
                'municipio_aval': '',
                //Testigo
                'nombre': '',
                //Datos contrato
                'monto_renta' : '',
                'status': '',
                'fecha_firma' : '',
                'fecha_ini' : '',
                'fecha_fin' : '',
                'num_meses' : 0,
                'modelo' : '',
                'pagares': [],
                'dep_garantia' : 0,
                'muebles' : 0,
                'servicios' : 0,
                'luz' : 0,
                'agua' : 0,
                'gas' : 0,
                'television' : 0,
                'telefonia' : 0,
                'adendum'  : 0
            };
        },

        getDatos(id){
            let me = this;
            me.limpiarDatosRenta();
            var url = '/rentas/getDatosRenta?id='+id;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.datosRenta = respuesta;
            })
            .catch(function (error) {
                console.log(error);
            });

        },
        verContrato(id){
            let me = this;
            me.getDatos(id);
            me.listado = 2;
            me.actualizar = 0;

        },
        nuevoContrato(){
            let me = this;
            me.listado = 3;
            me.limpiarDatosRenta();
        },
        salir(){
            let me = this;
            me.limpiarDatosRenta();
            me.listado = 1;
            me.listarContratos();
        }
    },
    mounted() {
        this.$root.selectFraccionamientos();
        this.listarContratos(1);
        this.getTestigos();
    }
};
</script>
<style>

    @media (min-width: 600px) {
        .table2 {
            margin: auto;
            border-collapse: collapse;
            overflow-x: auto;
            display: block;
            width: fit-content;
            max-width: 100%;
            box-shadow: 0 0 1px 1px rgba(0, 0, 0, 0.1);
        }

        .td2,
        .th2 {
            border: solid rgb(200, 200, 200) 1px;
            padding: 0.5rem;
        }

        .td2 {
            white-space: nowrap;
            border-bottom: none;
            color: rgb(20, 20, 20);
        }

        .td2:first-of-type,
        th:first-of-type {
            border-left: none;
        }

        .td2:last-of-type,
        th:last-of-type {
            border-right: none;
        }
    }
</style>
