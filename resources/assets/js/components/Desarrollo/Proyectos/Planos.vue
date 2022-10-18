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
                    <i class="fa fa-align-justify"></i> Planos Proyectos
                    <button v-if="lotes_ini.length" class="btn btn-success"
                        @click="abrirModal('planos')"
                    >
                        <i class="icon-plus"></i>&nbsp;Añadir planos
                    </button>
                    <button v-if="b_proyecto != ''" class="btn btn-scarlet"
                        @click="abrirModal('planos_fracc')"
                    >
                        <i class="icon-plus"></i>&nbsp;Añadir planos por Proyecto
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <select class="form-control col-md-4"
                            v-model="b_proyecto"
                            @change="$root.selectEtapa(b_proyecto),
                                    $root.selectModelo(b_proyecto)"
                        >
                            <option value="">Fraccionamiento</option>
                            <option
                                v-for="proyecto in $root.$data.proyectos"
                                :key="proyecto.id"
                                :value="proyecto.id"
                                v-text="proyecto.nombre"
                            ></option>
                        </select>
                        <select class="form-control col-md-3" v-model="b_etapa">
                            <option value="">Etapa</option>
                            <option
                                v-for="etapa in $root.$data.etapas"
                                :key="etapa.id"
                                :value="etapa.id"
                                v-text="etapa.num_etapa"
                            ></option>
                        </select>
                        <select
                            class="form-control col-md-4"
                            v-model="b_modelo"
                        >
                            <option value="">Modelo</option>
                            <option
                                v-for="modelo in $root.$data.modelos"
                                :key="modelo.id"
                                :value="modelo.id"
                                v-text="modelo.nombre"
                            ></option>
                        </select>

                        <input type="text" class="col-md-4 form-control"
                            v-model="b_manzana" placeholder="Manzana"
                        />
                        <input type="text" class="col-md-3 form-control"
                            v-model="b_inicio" placeholder="Num. Inicio"
                        />

                        <div class="input-group">
                            <button type="submit"
                                @click="indexLotes(1)" class="btn btn-primary"
                            >
                                <i class="fa fa-search"></i> Buscar
                            </button>
                        </div>
                    </div>
                    <TableComponent>
                        <template v-slot:thead>
                            <tr>
                                <th>
                                    <input
                                        type="checkbox"
                                        @click="selectAll"
                                        v-model="allSelected"
                                    />
                                </th>
                                <th>Proyecto</th>
                                <th>Etapa</th>
                                <th>Manzana</th>
                                <th># Lote</th>
                                <th>Modelo</th>
                                <th>Num Inicio</th>
                                <th></th>
                            </tr>
                        </template>
                        <template v-slot:tbody>
                            <tr v-for="lote in arrayLotes.data" :key="lote.id">
                                <td class="td2">
                                    <input
                                        type="checkbox"
                                        @click="select"
                                        :id="lote.id"
                                        :value="lote.id"
                                        v-model="lotes_ini"
                                    />
                                </td>
                                <td class="td2" v-text="lote.proyecto"></td>
                                <td class="td2" v-text="lote.etapa"></td>
                                <td class="td2" v-text="lote.manzana"></td>
                                <td class="td2" v-text="lote.num_lote"></td>
                                <td class="td2" v-text="lote.modelo"></td>
                                <td class="td2" v-text="lote.num_inicio"></td>
                                <td>
                                    <template  v-if="lote.planos.length">
                                        <button
                                            @click="abrirModal('ver',lote)"
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
                                v-if="arrayLotes.current_page > 5"
                                @click="indexLotes(1)"
                            >
                                <a class="page-link" href="#">Inicio</a>
                            </li>
                            <li class="page-item"
                                v-if="arrayLotes.current_page > 1"
                                @click="indexLotes(arrayLotes.current_page - 1)"
                            >
                                <a class="page-link" href="#">Ant</a>
                            </li>

                            <li class="page-item"
                                v-if="arrayLotes.current_page - 3 >= 1"
                                @click="indexLotes(arrayLotes.current_page - 3)"
                            >
                                <a class="page-link" href="#"
                                    v-text="arrayLotes.current_page - 3"
                                ></a>
                            </li>
                            <li
                                class="page-item" v-if="arrayLotes.current_page - 2 >= 1"
                                @click="indexLotes(arrayLotes.current_page - 2)"
                            >
                                <a class="page-link" href="#"
                                    v-text="arrayLotes.current_page - 2"
                                ></a>
                            </li>
                            <li class="page-item" v-if="arrayLotes.current_page - 1 >= 1"
                                @click="indexLotes(arrayLotes.current_page - 1)"
                            >
                                <a class="page-link" href="#"
                                    v-text="arrayLotes.current_page - 1"
                                ></a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link" href="#"
                                    v-text="arrayLotes.current_page"
                                ></a>
                            </li>
                            <li class="page-item"
                                v-if="arrayLotes.current_page + 1 <= arrayLotes.last_page"
                            >
                                <a class="page-link" href="#"
                                    @click="indexLotes(arrayLotes.current_page + 1)"
                                    v-text="arrayLotes.current_page + 1"
                                ></a>
                            </li>
                            <li class="page-item"
                                v-if="arrayLotes.current_page + 2 <=arrayLotes.last_page"
                            >
                                <a class="page-link" href="#"
                                    @click="indexLotes(arrayLotes.current_page + 2)"
                                    v-text="arrayLotes.current_page + 2"
                                ></a>
                            </li>
                            <li class="page-item"
                                v-if="arrayLotes.current_page + 3 <= arrayLotes.last_page"
                            >
                                <a class="page-link" href="#"
                                    @click="indexLotes(arrayLotes.current_page + 3)"
                                    v-text="arrayLotes.current_page + 3"
                                ></a>
                            </li>

                            <li class="page-item"
                                v-if="arrayLotes.current_page < arrayLotes.last_page"
                                @click="indexLotes(arrayLotes.current_page + 1)"
                            >
                                <a class="page-link" href="#">Sig</a>
                            </li>
                            <li class="page-item"
                                v-if="arrayLotes.current_page < 5 && arrayLotes.last_page > 5"
                                @click="indexLotes(arrayLotes.last_page)"
                            >
                                <a class="page-link" href="#">Ultimo</a>
                            </li>
                        </ul>
                    </nav>
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
import ModalComponent from "../../Componentes/ModalComponent.vue";
import TableComponent from "../../Componentes/TableComponent.vue";

export default {
    components: {
        ModalComponent,
        TableComponent
    },
    props: {},
    data() {
        return {
            arrayLotes: [],
            b_proyecto: "",
            b_etapa: "",
            b_modelo: "",
            b_manzana: "",
            b_inicio: "",

            planos: [],

            allSelected: false,
            lotes_ini: [],

            modal: 0,
            tituloModal: "",
            newArchivo:{},
            proyectoSel:'',
            etapaSel:'',
            tipoAccion:1,
            cargando:0
        };
    },
    computed: {},
    methods: {
        selectAll: function() {
            this.lotes_ini = [];

            if (!this.allSelected) {
                for (var lote in this.arrayLotes.data) {
                    this.lotes_ini.push(
                        this.arrayLotes.data[lote].id.toString()
                    );
                }
            }
        },
        select: function() {
            this.allSelected = false;
        },

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
                page +
                "&b_proyecto=" +
                me.b_proyecto +
                "&b_etapa=" +
                me.b_etapa +
                "&b_manzana=" +
                me.b_manzana +
                "&b_modelo=" +
                me.b_modelo +
                "&b_inicio=" +
                me.b_inicio;
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.arrayLotes = respuesta;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },

        cerrarModal() {
            this.modal = 0;
            this.planos = [];
            this.newArchivo = {};
            this.lotes_ini = [];
            this.proyectoSel = '';
            this.etapaSel = '';
            this.indexLotes(this.arrayLotes.current_page)
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
        this.$root.selectFraccionamientos();
    }
};
</script>
<style>
.text-formfile{

        color: grey;
        display:flex;
        padding-top: 13px;
    /*  background-color: aqua; */
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
