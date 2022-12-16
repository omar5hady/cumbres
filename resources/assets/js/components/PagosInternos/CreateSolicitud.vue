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
                    <i class="fa fa-align-justify"></i> Solicitud de Pago
                    <button class="btn btn-success" @click="vistaFormulario('nuevo')" v-if="vista == 0">
                        <i class="icon-plus"></i>&nbsp;Nueva solicitud
                    </button>
                    <button class="btn btn-light" @click="cerrarFormulario()" v-if="vista == 1">
                        <i class="icon-close"></i>&nbsp;Regresar
                    </button>
                </div>
                <div class="card-body">
                    <template v-if="vista == 0">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input type="text" class="form-control col-md-2" disabled placeholder="Proveedor:">
                                    <select class="form-control col-md-7" v-model="b_proveedor">
                                        <option value="">Seleccione</option>
                                        <option v-for="proveedor in arrayProveedores" :key="proveedor.id" :value="proveedor.id" v-text="proveedor.proveedor"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input type="text" class="form-control col-md-2" disabled placeholder="Solicitante">
                                    <input type="text" class="form-control col-md-6" placeholder="Solicitante a buscar">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input type="text" class="form-control col-md-2" disabled placeholder="Fecha:">
                                    <input type="date" class="form-control col-md-4">
                                    <input type="date" class="form-control col-md-4">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-5" v-model="b_status">
                                        <option value="">Status</option>
                                        <option value="0">Pendiente</option>
                                        <option value="1">En Proceso</option>
                                        <option value="2">Revisadas</option>
                                        <option value="3">Aprobadas</option>
                                        <option value="4">Pagados</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <button class="btn btn-primary">
                                        <i class="fa fa-search"></i> Buscar
                                    </button>
                                    <a class="btn btn-success" href="#">
                                        <i class="fa fa-file-text"></i>&nbsp; Excel
                                    </a>
                                </div>
                            </div>
                        </div>
                        <TableComponent :cabecera="[
                            '',
                            'Proveedor',
                            'Solicitante',
                            'Fecha solic',
                            'Importe',
                            'Forma de pago',
                            ' '
                        ]">
                            <template v-slot:tbody>
                                <tr v-for="solic in arraySolic.data" :key="solic.id">
                                    <td class="td2" v-text="solic.proyecto"></td>
                                    <td class="td2" v-text="solic.etapa"></td>
                                    <td class="td2" v-text="solic.manzana"></td>
                                    <td class="td2" v-text="solic.num_lote"></td>
                                    <td class="td2" v-text="solic.modelo"></td>
                                    <td class="td2" v-text="solic.num_inicio"></td>
                                    <td>
                                        <template  v-if="solic.planos.length">
                                            <button
                                                @click="abrirModal('ver',solic)"
                                                title="Ver planos" type="button" class="btn btn-dark btn-sm"
                                            >
                                                <i class="fa fa-map-o"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <span class="badge badge-light">Sin planos</span>
                                        </template>
                                    </td>
                                </tr>
                            </template>
                        </TableComponent>
                        <nav>
                            <!--Botones de paginacion -->
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item"
                                    v-if="arraySolic.current_page > 5"
                                    @click="indexLotes(1)"
                                >
                                    <a class="page-link" href="#">Inicio</a>
                                </li>
                                <li class="page-item"
                                    v-if="arraySolic.current_page > 1"
                                    @click="indexLotes(arraySolic.current_page - 1)"
                                >
                                    <a class="page-link" href="#">Ant</a>
                                </li>

                                <li class="page-item"
                                    v-if="arraySolic.current_page - 3 >= 1"
                                    @click="indexLotes(arraySolic.current_page - 3)"
                                >
                                    <a class="page-link" href="#"
                                        v-text="arraySolic.current_page - 3"
                                    ></a>
                                </li>
                                <li
                                    class="page-item" v-if="arraySolic.current_page - 2 >= 1"
                                    @click="indexLotes(arraySolic.current_page - 2)"
                                >
                                    <a class="page-link" href="#"
                                        v-text="arraySolic.current_page - 2"
                                    ></a>
                                </li>
                                <li class="page-item" v-if="arraySolic.current_page - 1 >= 1"
                                    @click="indexLotes(arraySolic.current_page - 1)"
                                >
                                    <a class="page-link" href="#"
                                        v-text="arraySolic.current_page - 1"
                                    ></a>
                                </li>
                                <li class="page-item active">
                                    <a class="page-link" href="#"
                                        v-text="arraySolic.current_page"
                                    ></a>
                                </li>
                                <li class="page-item"
                                    v-if="arraySolic.current_page + 1 <= arraySolic.last_page"
                                >
                                    <a class="page-link" href="#"
                                        @click="indexLotes(arraySolic.current_page + 1)"
                                        v-text="arraySolic.current_page + 1"
                                    ></a>
                                </li>
                                <li class="page-item"
                                    v-if="arraySolic.current_page + 2 <=arraySolic.last_page"
                                >
                                    <a class="page-link" href="#"
                                        @click="indexLotes(arraySolic.current_page + 2)"
                                        v-text="arraySolic.current_page + 2"
                                    ></a>
                                </li>
                                <li class="page-item"
                                    v-if="arraySolic.current_page + 3 <= arraySolic.last_page"
                                >
                                    <a class="page-link" href="#"
                                        @click="indexLotes(arraySolic.current_page + 3)"
                                        v-text="arraySolic.current_page + 3"
                                    ></a>
                                </li>

                                <li class="page-item"
                                    v-if="arraySolic.current_page < arraySolic.last_page"
                                    @click="indexLotes(arraySolic.current_page + 1)"
                                >
                                    <a class="page-link" href="#">Sig</a>
                                </li>
                                <li class="page-item"
                                    v-if="arraySolic.current_page < 5 && arraySolic.last_page > 5"
                                    @click="indexLotes(arraySolic.last_page)"
                                >
                                    <a class="page-link" href="#">Ultimo</a>
                                </li>
                            </ul>
                        </nav>
                    </template>
                    <template v-if="vista == 1">

                        <div class="form-group row border" style="padding-top:5px; padding-bottom:8px;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Proveedor </label>
                                    <v-select
                                        :on-search="selectProveedores"
                                        label="proveedor"
                                        :options="proveedoresForm"
                                        placeholder="Buscar contratista..."
                                        :onChange="getDatosProveedor"
                                    >
                                    </v-select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label> Forma de pago </label>
                                <select class="form-control" v-model="solicitudData.tipo_pago" @click="selectLotes(manzana,fraccionamiento_id)">
                                    <option value="0">Caja Fuerte</option>
                                    <option value="1">Bancario</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label> Importe </label>
                                <input class="form-control" pattern="\d*" maxlength="10" v-on:keypress="$root.isNumber($event)"
                                    type="text" v-model="solicitudData.importe">
                            </div>

                             <div class="col-md-3" v-if="solicitudData.importe > 0">
                                <label>&nbsp;</label>
                                <h6 class="form-control">${{$root.formatNumber(solicitudData.importe)}}</h6>
                            </div>
                        </div>

                        <div class="form-group row border" style="padding-top:5px; padding-bottom:8px;" v-if="solicitudData.importe > 0">
                            <div class="col-md-12">
                                <center>
                                    <h6 style="color:#"> Detalle de la solicitud </h6>
                                </center>
                            </div>

                            <div class="col-md-4">
                                <label for="">Obra</label>
                                <input type="text" name="obra" list="obraname" class="form-control"
                                    v-model="datosDetalle.obra" @keyup="getProyectos(datosDetalle.obra)" @change="selectEtapa(datosDetalle.obra)">
                                <datalist id="obraname">
                                    <option value="">Seleccione</option>
                                    <option value="OFICINA">OFICINA</option>
                                    <option v-for="proyecto in arrayProyectos" :key="proyecto.id" :value="proyecto.nombre" v-text="proyecto.nombre"></option>
                                </datalist>
                            </div>

                            <div class="col-md-3" v-if="datosDetalle.obra != 'OFICINA' && datosDetalle.obra != ''">
                                <label for="">&nbsp;</label>
                                <select class="form-control" v-model="datosDetalle.sub_obra">
                                    <option value="">Etapa</option>
                                    <option v-for="etapa in arrayEtapas" :key="etapa.id" :value="etapa.num_etapa" v-text="etapa.num_etapa"></option>
                                </select>
                            </div>

                            <div v-if="datosDetalle.obra != 'OFICINA' && datosDetalle.obra != ''" class="col-md-5"></div>
                            <div v-else class="col-md-8"></div>

                            <div class="col-md-5">
                                <label for="">Cargo</label>
                                <select class="form-control" v-model="datosDetalle.cargo" @change="getConceptos(datosDetalle.cargo), datosDetalle.concepto = ''">
                                    <option value="">Seleccione</option>
                                    <option v-for="cargo in arrayCargos" :key="cargo.cargo" :value="cargo.cargo" v-text="cargo.cargo"></option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="">Concepto</label>
                                <select class="form-control" v-model="datosDetalle.concepto">
                                    <option value="">Seleccione</option>
                                    <option v-for="concepto in arrayConceptos" :key="concepto.id" :value="concepto.concepto" v-text="concepto.concepto"></option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="">Tipo de Mov.</label>
                                <select class="form-control" v-model="datosDetalle.tipo_mov">
                                    <option value="0">Anticipo</option>
                                    <option value="1">Liquidación</option>
                                    <option value="2">Pago Cta</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="">Importe total</label>
                                <input class="form-control" pattern="\d*" maxlength="10" v-on:keypress="$root.isNumber($event)"
                                    type="text" v-model="datosDetalle.total">
                            </div>
                            <div class="col-md-2" v-if="datosDetalle.total > 0">
                                <label for="">&nbsp;</label>
                                <label class="form-control">${{$root.formatNumber(datosDetalle.total)}}</label>
                            </div>
                            <div class="col-md-3">
                                <label for="">Este pago</label>
                                <input class="form-control" pattern="\d*" maxlength="10" v-on:keypress="$root.isNumber($event)"
                                    @change="verificarMonto()"
                                    type="text" v-model="datosDetalle.pago">
                            </div>
                            <div class="col-md-2" v-if="datosDetalle.pago > 0">
                                <label for="">&nbsp;</label>
                                <label class="form-control">${{$root.formatNumber(datosDetalle.pago)}}</label>
                            </div>
                            <div class="col-md-2">
                                <label for="">&nbsp;</label>
                                <button class="btn btn-success" @click="addDetalle()" title="Añadir"><i class="icon-plus"></i></button>
                            </div>
                        </div>

                    </template>
                </div>

                <ModalComponent
                    @closeModal="cerrarModal()"
                    :titulo="tituloModal"
                    v-if="modal == 1"
                >
                    <template v-slot:body>
                        <div class="modal-body">
                            <div class="contenedor-modal">
                                <div class="form-sub">
                                    <form method="post" @submit="formSubmitPlano"
                                        enctype="multipart/form-data"
                                    >
                                        <div class="form-opc">
                                            <div class="form-archivo">
                                                <input ref="fileSelector"
                                                    v-show="false"
                                                    type="file" accept="application/pdf"
                                                    v-on:change="onChangeFile"
                                                />

                                                <label class="label-button" @click="onSelectPlano">
                                                    Elige el plano a cargar
                                                    <i class="fa fa-upload"></i>
                                                </label>
                                                <div :class="(newArchivo.nom_archivo == 'Seleccione Archivo')
                                                    ? 'text-file-hide' : 'text-file'"
                                                    v-text="newArchivo.nom_archivo"
                                                ></div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    <label class="col-md-3 form-control-label" for="text-input">Tipo de plano</label>
                                                    <div class="col-md-9">
                                                        <input type="text" name="categoria" list="categoria" class="form-control" v-model="newArchivo.tipo" placeholder="Tipo de Plano">
                                                        <datalist id="categoria" v-if="tipoAccion == 1">
                                                            <option value="OBRA EXTRA">OBRA EXTRA</option>
                                                            <option value="LICENCIA">LICENCIA</option>
                                                            <option value="AUTORIZACIÓN">AUTORIZACION</option>
                                                        </datalist>
                                                        <datalist id="categoria" v-if="tipoAccion == 2">
                                                            <option value="TERRENO">TERRENO</option>
                                                            <option value="LOCALIZACIÓN Y UBICACION">LOCALIZACIÓN Y UBICACION</option>
                                                            <option value="URBANIZACIÓN">URBANIZACIÓN</option>
                                                            <option value="AUTORIZACIÓN">AUTORIZACION</option>
                                                            <option value="LOTIFICACIÓN, EQUIPAMIENTO BASICO">LOTIFICACIÓN, EQUIPAMIENTO BÁSICO Y DE CONSUMO</option>
                                                        </datalist>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-md-3 form-control-label" for="text-input">Descripción</label>
                                                    <div class="col-md-9">
                                                        <textarea class="form-control" v-model="newArchivo.description" rows="3" cols="40" placeholder="Descripción del plano"></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="boton-modal" v-if="cargando==0">
                                                <button v-show="newArchivo.nom_archivo !='Seleccione Archivo' && newArchivo.tipo != ''"
                                                    type="submit" class="btn btn-success boton-modal"
                                                >
                                                    Subir Archivo
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </template>
                </ModalComponent>

                <ModalComponent v-if="modal == 2"
                    :titulo="tituloModal"
                    @closeModal="cerrarModal"
                >
                    <template v-slot:body>
                        <div class="col-md-12">
                            <TableComponent :cabecera="['','Tipo','Descripcion','']">
                                <template v-slot:tbody>
                                    <tr v-for="p in planos" :key="p.id">
                                        <td>
                                            <button title="Eliminar archivo" type="button" @click="deleteFile(p.id)" class="btn btn-danger btn-sm">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </td>
                                        <td class="td2" v-text="p.tipo"></td>
                                        <td class="td2" v-text="p.description"></td>
                                        <td class="td2">
                                            <a :href="p.file.public_url" target="_blank" class="btn btn-success">Ver Plano</a>
                                        </td>
                                    </tr>
                                </template>
                            </TableComponent>
                        </div>
                    </template>
                </ModalComponent>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
    </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
import ModalComponent from "../Componentes/ModalComponent.vue";
import TableComponent from "../Componentes/TableComponent.vue";
import vSelect from 'vue-select';

export default {
    components: {
        ModalComponent,
        TableComponent,
        vSelect
    },
    props: {},
    computed:{},
    data() {
        return {
            arraySolic: [],
            planos: [],
            arrayProveedores : [],
            proveedoresForm : [],
            arrayProyectos : [],
            arrayEtapas : [],
            arrayCargos: [],
            arrayConceptos: [],

            solicitudData:{},
            datosDetalle:{},

            b_proveedor : '',
            b_status : '',
            loading : false,

            modal: 0,
            tituloModal: "",
            newArchivo:{},
            proyectoSel:'',
            etapaSel:'',
            tipoAccion:1,
            cargando:0,

            vista:0
        };
    },
    computed: {},
    methods: {

        onChangeFile(e){
            this.newArchivo.file = e.target.files[0];
            this.newArchivo.nom_archivo = e.target.files[0].name;
        },

        onSelectPlano(){
            this.$refs.fileSelector.click()
        },

        formSubmitPlano(e) {
            e.preventDefault();
            let currentObj = this;
            this.cargando = 1;

            let formData = new FormData();
            formData.append('file', this.newArchivo.file);
            formData.append('ids', this.lotes_ini);
            formData.append('description', this.newArchivo.description);
            formData.append('tipo', this.newArchivo.tipo);
            formData.append('proyecto', this.proyectoSel);
            formData.append('etapa', this.etapaSel);
            let me = this;
            axios.post('/planos-proyectos', formData)
            .then(function (response) {
                me.cargando = 0;
                swal({
                    position: 'top-end',
                    type: 'success',
                    title: 'Plano guardado correctamente',
                    showConfirmButton: false,
                    timer: 2000
                    })
                me.cerrarModal();

            }).catch(function (error) {
                currentObj.output = error;
                this.cargando = 0;
            });
        },
        addDetalle(){
            console.log("OSO")
            if(this.verificarCaptura())
                window.alert("OSO")

        },
        verificarCaptura(){
            let me = this;
            let res = true;
            if( me.datosDetalle.obra != ''
                && me.datosDetalle.cargo != ''
                && me.datosDetalle.concepto != ''
                && me.datosDetalle.total > 0
                && me.datosDetalle.pago > 0
            )res = false;
            return res;
        },
        limpiarFormularioDetalle(){
            this.datosDetalle = {
                obra: '',
                sub_obra: '',
                cargo : '',
                concepto : '',
                observacion : '',
                tipo_mov : 0,
                total : 0,
                pago : 0,
                saldo : 0
            }
        },
        vistaFormulario(accion,data=[]){
            this.vista = 1;
            this.getCargos();
            this.limpiarFormularioDetalle();

            switch(accion){
                case 'nuevo':{
                    this.tipoAccion = 1;
                    this.solicitudData={
                        id : '',
                        empresa_solic : '',
                        proveedor_id : '',
                        importe : 0,
                        saldo : 0,
                        tipo_pago : 0,
                        fecha_compra: '',
                        banco : '',
                        num_cuenta : '',
                        clabe : '',
                        num_factura : '',
                        detalle : []
                    }
                    this.
                    break;
                }
            }
        },
        cerrarFormulario(){
            this.vista = 0;
            this.solicitudData = {};
            this.indexLotes(this.arraySolic.current_page);
        },

        deleteFile(id){
            let me = this;
            axios.delete(`/planos-proyectos/${id}`, {
                params: {'id': id}
            }).then(function (response){
                me.planos = me.planos.filter( e => e.id !== id)
                me.indexLotes(me.pagination.current_page);
                //Se muestra mensaje Success
                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                    });
                    toast({
                    type: 'success',
                    title: 'Archivo eliminado correctamente'
                })
            }).catch(function (error){
                console.log(error);
            });
        },

        /**Metodo para mostrar los registros */
        indexLotes(page) {
            let me = this;
            var url =
                "/planos-proyectos?page=" +
                page;
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.arraySolic = respuesta;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        getProveedores(){
            let me = this;
            me.arrayProveedores=[];
            var url = '/select_proveedor?proveedor';
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayProveedores = respuesta.proveedor;
            })
            .catch(function (error) {
                console.log(error);
            });
        },

        getProyectos(search){
            let me = this;
            me.arrayProyectos = [];
            var url = '/select_fraccionamiento2?filtro='+search;
            axios.get(url).then(function (response) {
                const respuesta = response.data;
                me.arrayProyectos = respuesta.fraccionamientos;
            })
            .catch(function (error) {
                console.log(error);
            });
        },

        verificarMonto(){
            let me = this;

            if(parseFloat(me.datosDetalle.total) < parseFloat(me.datosDetalle.pago))
                me.datosDetalle.pago = me.datosDetalle.total

            me.saldo = parseFloat(me.solicitudData.importe);
            me.solicitudData.detalle.forEach(e => {
                me.saldo -= parseFloat(e.pago);
            });

            if(me.saldo < me.datosDetalle.pago)
                me.datosDetalle.pago = me.saldo;
        },

        getCargos(){
            let me = this;
            me.arrayCargos=[];
            var url = '/sp/getCargos';
            axios.get(url).then(function (response) {
                me.arrayCargos = response.data;
            })
            .catch(function (error) {
                console.log(error);
            });
        },

        getConceptos(cargo){
            let me = this;
            me.arrayConceptos=[];
            var url = '/sp/getConceptos?cargo='+cargo;
            axios.get(url).then(function (response) {
                me.arrayConceptos = response.data;
            })
            .catch(function (error) {
                console.log(error);
            });
        },

        selectEtapa(buscar){
            let me = this;
            me.datosDetalle.sub_obra = '';
            me.arrayEtapas=[];
            var url = '/select_etapa?buscar=' + buscar;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayEtapas = respuesta.etapas;
            })
            .catch(function (error) {
                console.log(error);
            });
        },

        selectProveedores(search,loading){
            let me = this;
            loading(true)
            me.proveedoresForm=[];
            var url = '/select_proveedor?proveedor='+search;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                q: search
                me.proveedoresForm = respuesta.proveedor;
                loading(false)
            })
            .catch(function (error) {
                console.log(error);
                loading(false)
            });
        },

        getDatosProveedor(val1){
                let me = this;
                //me.loading = true;
                me.solicitudData.proveedor_id = val1.id;
                me.solicitudData.proveedor = val1.proveedor;
            },

        cerrarModal() {
            this.modal = 0;
            this.planos = [];
            this.newArchivo = {};
            this.lotes_ini = [];
            this.proyectoSel = '';
            this.etapaSel = '';
            this.indexLotes(this.arraySolic.current_page)
            this.cargando = 0;
        },

        /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
        abrirModal(accion, data = []) {
            switch (accion) {
                case "planos": {
                    this.modal = 1;
                    this.tituloModal = "Nuevo plano";

                    this.newArchivo = {
                        description: "",
                        tipo: "",
                        file: "",
                        nom_archivo: 'Seleccione Archivo'
                    };
                    this.tipoAccion = 1;
                    break;
                }

                case 'ver':{
                    this.planos = data['planos'];
                    this.modal = 2;
                    this.tituloModal = 'Planos del lote: ' + data['num_lote'];
                    break;
                }

                case 'planos_fracc':{
                    this.modal = 1;
                    this.tituloModal = "Nuevo plano por Proyecto";

                    this.newArchivo = {
                        description: "",
                        tipo: "",
                        file: "",
                        nom_archivo: 'Seleccione Archivo'
                    };
                    this.tipoAccion = 2;
                    this.proyectoSel = this.b_proyecto;
                    this.etapaSel = this.b_etapa;
                }
            }
        }
    },
    mounted() {
        this.indexLotes(1);
        this.getProveedores();
        this.$root.selectFraccionamientos();
    }
};
</script>
<style>
.text-formfile{
        color: grey;
        display:flex;
        padding-top: 13px;
        justify-content: left;

    }
    .contenedor-modal{
        display: block;
        flex-direction: column;
        margin: auto;
        overflow-x: auto;
        width: fit-content;
        max-width: 100%;
    }

    .label-button{
        border-style: solid;
        cursor:pointer;
        color: #fff;
        background-color: #00ADEF;
        border-color: #00ADEF;
        padding: 10px;
        margin: 15px;
    }

    .label-button:hover {
        color: #fff;
        background-color: #1b8eb7;
        border-color: #00b0bb;
    }
    .form-sub{
        border: 1px solid #c2cfd6;
        margin-top: 20px;
        width: 100%;
    }
    .form-opc{
        display: flex;
        flex-direction: column;
    }
    .form-archivo{
        display: flex;
        flex-direction: row;
        width: 100%;
    }
    .text-file{
        color: rgb(39, 38, 38);
        font-size:12px;
        word-break: break-all;
        font-weight: bold;
        width: 300px;
        padding: 15px;
    }
    .text-file-hide{
        color: rgb(127, 130, 134);
        font-size:13px;
        word-break: break-all;
        font-weight: bold;
        width: 300px;
        padding: 15px;


    }
    .boton-modal{
        margin-top: 15px;
        display: flex;
        flex-direction: row;
        justify-content: center;
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
