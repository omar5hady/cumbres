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
                    <i class="fa fa-align-justify"></i> Reporte Archivo
                    &nbsp;&nbsp;
                </div>
                <div class="card-body">

                    <div class="form-group row">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input
                                    type="text"
                                    disabled
                                    class="form-control"
                                    placeholder="Fecha de venta"
                                />
                                <input
                                    type="date"
                                    v-model="b_fecha1"
                                    @keyup.enter="listarReporte()"
                                    class="form-control"
                                />
                                <input
                                    type="date"
                                    v-model="b_fecha2"
                                    @keyup.enter="listarReporte()"
                                    class="form-control"
                                />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-group">
                                <input
                                    type="text"
                                    disabled
                                    class="form-control"
                                    placeholder="Fecha de firma"
                                />
                                <input
                                    type="date"
                                    v-model="b_fecha3"
                                    @keyup.enter="listarReporte()"
                                    class="form-control"
                                />
                                <input
                                    type="date"
                                    v-model="b_fecha4"
                                    @keyup.enter="listarReporte()"
                                    class="form-control"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-8">
                            <div class="input-group">
                                <button
                                    type="submit"
                                    @click="listarReporte()"
                                    class="btn btn-primary"
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla Resumen -->
                    <TableComponent
                        :cabecera="[
                            '',
                            'Cliente',
                            'Proyecto',
                            'Etapa',
                            'Manzana',
                            'Lote',
                            'Fecha de venta',
                            'Fecha de firma'
                        ]"
                    >
                        <template v-slot:tbody>
                            <tr
                                v-for="contrato in reporte.data"
                                :key="contrato.id"
                            >
                                <td>
                                    <Button icon="fa fa-upload" title="Subir archivo"
                                        @click="subirArchivo(contrato.id)"
                                    ></Button>
                                </td>
                                <td>
                                    {{ contrato.nombre }}
                                    {{ contrato.apellidos }}
                                </td>
                                <td>{{ contrato.proyecto }}</td>
                                <td>{{ contrato.etapa }}</td>
                                <td>{{ contrato.manzana }}</td>
                                <td>{{ contrato.num_lote }}</td>
                                <td>{{ contrato.fecha }}</td>
                                <td>{{ contrato.fecha_firma_esc }}</td>
                            </tr>
                        </template>
                    </TableComponent>
                    <NavComponent
                        :current="
                            reporte.current_page ? reporte.current_page : 1
                        "
                        :last="reporte.last_page ? reporte.last_page : 1"
                        @changePage="listarReporte"
                    >
                    </NavComponent>

                    <hr />
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>

        <ModalComponent v-if="modal"
            titulo="Subir archivo"
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
            </template>
        </ModalComponent>
        <!--Fin del modal-->
    </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
import NavComponent from "../Componentes/NavComponent.vue";
import TableComponent from "../Componentes/TableComponent.vue";
import Button from '../Componentes/ButtonComponent.vue'
import ModalComponent from "../Componentes/ModalComponent.vue";
export default {
    components: {
        TableComponent,
        NavComponent,
        ModalComponent,
        Button
    },
    data() {
        return {
            reporte: [],
            b_fecha1: "",
            b_fecha2: "",
            b_fecha3: "",
            b_fecha4: "",
            archivo:'',
            id: '',
            modal: false,
        };
    },
    computed: {},
    methods: {
        subirArchivo(id){
            this.modal = true;
            this.id = id,
            this.archivo = undefined;
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
                    me.modal=false
                    me.listarReporte(1)

                }).catch(function (error) {
                    console.log(error);
                });
            },
        async listarReporte(page) {
            let me = this;
            try {
                const url = `reprotes/reporteSitFG?page=${page}&fecha1=${
                    me.b_fecha1
                }&fecha2=${me.b_fecha2}&fecha3=${me.b_fecha3}&fecha4=${
                    me.b_fecha4
                }`;
                const response = await axios.get(url);
                if (response) me.reporte = response.data;
            } catch (error) {}
        }
    },
    mounted() {
        this.listarReporte(1);
    }
};
</script>
<style scoped>
td {
    white-space: nowrap;
    border-bottom: none;
    color: rgb(20, 20, 20);
    text-align: center;
}
</style>
