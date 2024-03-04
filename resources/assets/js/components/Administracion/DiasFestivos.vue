<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <strong><a style="color:#FFFFFF;" href="/">Home</a></strong>
            </li>
        </ol>
        <LoadingComponent v-if="proceso"></LoadingComponent>
        <template v-else>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card scroll-box">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Dias festivos
                        <!--   Boton Nuevo    -->
                        <Button
                            :btnClass="'btn-secondary'"
                            :icon="'icon-plus'"
                            @click="abrirModal('registrar')"
                        >
                            Nuevo
                        </Button>
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <input
                                        type="text"
                                        disabled
                                        class="form-control col-md-3"
                                        placeholder="Fecha de inicio"
                                    />
                                    <input
                                        type="date"
                                        v-model="b_ini"
                                        @keyup.enter="getData(1)"
                                        class="form-control"
                                    />
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <input
                                        type="text"
                                        disabled
                                        class="form-control col-md-3"
                                        placeholder="Fecha de fin"
                                    />
                                    <input
                                        type="date"
                                        v-model="b_fin"
                                        @keyup.enter="getData(1)"
                                        class="form-control"
                                    />
                                    <Button
                                        :icon="'fa fa-search'"
                                        @click="getData(1)"
                                        >Buscar</Button
                                    >
                                </div>
                            </div>
                        </div>
                        <TableComponent
                            :cabecera="[
                                '',
                                'Festividad',
                                'Fecha',
                                'Dia completo'
                            ]"
                        >
                            <template v-slot:tbody>
                                <tr
                                    v-for="dia in arrayDiasFestivos.data"
                                    :key="dia.id"
                                >
                                    <td class="td2">
                                        <Button
                                            :btnClass="'btn-warning'"
                                            :size="'btn-sm'"
                                            title="Editar"
                                            :icon="'icon-pencil'"
                                            @click="
                                                abrirModal('actualizar', dia)
                                            "
                                        ></Button>
                                        <Button
                                            :btnClass="'btn-danger'"
                                            :size="'btn-sm'"
                                            title="Eliminar"
                                            :icon="'icon-trash'"
                                            @click="deleteDia(dia.id)"
                                        ></Button>
                                    </td>
                                    <td class="td2" v-text="dia.nombre"></td>
                                    <td class="td2" v-text="dia.fecha"></td>
                                    <td class="td2">
                                        {{ dia.medio_dia == 1 ? "No" : "Si" }}
                                    </td>
                                </tr>
                            </template>
                        </TableComponent>
                        <Nav v-if="arrayDiasFestivos.length"
                            :current="arrayDiasFestivos.current_page"
                            :last="arrayDiasFestivos.last_page"
                            @changePage="getData"
                        ></Nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <DiaFestivoModal v-if="modal"
                :data="datos"
                :titulo="tituloModal"
                :tipoAccion="tipoAccion"
                @close="cerrarModal()"
            />
            <!--Fin del modal-->
        </template>
    </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
import TableComponent from "../Componentes/TableComponent.vue";
import Button from "../Componentes/ButtonComponent.vue";
import Nav from "../Componentes/NavComponent.vue";
import RowModal from "../Componentes/ComponentesModal/RowModalComponent.vue";
import LoadingComponent from "../Componentes/LoadingComponent.vue";
import DiaFestivoModal from "./modales/DiaFestivoModal.vue"

export default {
    components: {
        TableComponent,
        Nav,
        Button,
        RowModal,
        LoadingComponent,
        DiaFestivoModal
    },
    data() {
        return {
            proceso: false,
            arrayDiasFestivos: [],
            modal: 0,
            tituloModal: "",
            tipoAccion: 0,

            b_ini: "",
            b_fin: "",
            datos: {
                id: "",
                fecha: "",
                nombre: "",
                medio_dia: 0
            }
        };
    },
    computed: {},
    methods: {
        /**Metodo para mostrar los registros */
        getData(page) {
            let me = this;
            me.proceso = true;
            var url =
                "/dias-festivos?page=" +
                page +
                "&b_ini=" +
                me.b_ini +
                "&b_fin=" +
                me.b_fin;
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.arrayDiasFestivos = respuesta;
                    me.proceso = false;
                })
                .catch(function(error) {
                    console.log(error);
                    me.proceso = false;
                });
        },
        deleteDia(id) {
            //console.log(this.fraccionamiento_id);
            swal({
                title: "¿Desea eliminar?",
                text: "Esta acción no se puede revertir!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                cancelButtonText: "Cancelar",
                confirmButtonText: "Si, eliminar!"
            }).then(result => {
                if (result.value) {
                    let me = this;
                    axios
                        .delete(`/dias-festivos/${id}`, { params: { id: id } })
                        .then(function(response) {
                            swal(
                                "Borrado!",
                                "Notaria borrado correctamente.",
                                "success"
                            );
                            me.getData(1);
                        })
                        .catch(function(error) {
                            console.log(error);
                        });
                }
            });
        },
        cerrarModal() {
            this.modal = 0;
            this.tituloModal = "";
            this.getData(1)
        },

        /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
        abrirModal(accion, data = []) {
            switch (accion) {
                case "registrar": {
                    this.tituloModal = "Registrar Dia festivo";
                    this.datos = {
                        id: "",
                        fecha: "",
                        nombre: "",
                        medio_dia: 0
                    }
                    this.tipoAccion = 1;
                    break;
                }
                case "actualizar": {
                    this.tituloModal = "Actualizar Dia festivo";
                    this.tipoAccion = 2;
                    this.datos = data;
                    break;
                }
            }
            this.modal = 1;
        }
    },
    mounted() {
        this.getData(1);
    }
};
</script>
<style>
.div-error {
    display: flex;
    justify-content: center;
}
.text-error {
    color: red !important;
    font-weight: bold;
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
</style>
