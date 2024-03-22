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
                    <i class="fa fa-align-justify"></i> Vacaciones
                    <div class="button-header">
                        <button type="button" class="btn btn-success btn-sm"
                            @click="nuevaSolicitud()"
                            v-if="perfil.dias_disponibles > 0 || userName=='marce.gaytan'"
                        >
                            <i class="icon-plus"></i>&nbsp;Crear solicitud
                        </button>
                        <!---->
                    </div>
                </div>

                <div class="info-center" v-if="loading">
                    <LoadingComponentVue></LoadingComponentVue>
                </div>

                <div class="card-body" v-else>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-user">
                                <!---->
                                <div class="card-body">
                                    <div class="author">
                                        <img :src="`/img/avatars/${perfil.foto}`"
                                            onerror="this.src='/img/avatars/default-image.gif'"
                                            class="avatar border-white"/>
                                        <h4 class="title">
                                            <font
                                                style="vertical-align: inherit;"
                                            >
                                                <font
                                                    v-text="perfil.nombre"
                                                    style="vertical-align: inherit;"
                                                >
                                                </font>
                                            </font>
                                        </h4>
                                        <h5 class="sub-title">
                                            <strong>Dias diponibles: </strong>
                                            {{ perfil.dias_disponibles }}
                                        </h5>
                                    </div>
                                </div>
                                <!---->
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card" style="padding-bottom: 15px;">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <font style="vertical-align: inherit;">
                                            <font
                                                style="vertical-align: inherit;"
                                                >Resumen por año
                                            </font>
                                        </font>
                                    </h5>
                                    <!---->
                                </div>
                                <div class="card-body" style="padding-bottom: 0px;"
                                >
                                    <TableComponent v-if="datosVacaciones.length > 0"
                                        :cabecera="[
                                            'Año',
                                            'Total dias',
                                            'Dias disfrutados',
                                            'Saldo',
                                            'Estatus'
                                        ]">
                                        <template v-slot:tbody>
                                            <tr
                                                v-for="vacacion in datosVacaciones"
                                                :key="vacacion.id"
                                            >
                                                <td class="td2">
                                                    {{ vacacion.anio }}
                                                </td>
                                                <td class="td2">
                                                    {{ vacacion.total_dias }}
                                                </td>
                                                <td class="td2">
                                                    {{ vacacion.dias_tomados }}
                                                </td>
                                                <td class="td2">
                                                    {{ vacacion.saldo }}
                                                </td>
                                                <td class="td2">
                                                    <span :class="vacacion.status == 'activo'
                                                                ? 'badge badge-success'
                                                                : 'badgebadge-danger'"
                                                    >
                                                        {{ vacacion.status.toUpperCase() }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </template>
                                    </TableComponent>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <!-- Buscador por nombre (Solo para RH) -->
                        <div class="col-md-8">
                            <div class="form-group row">
                                <input type="text"
                                    class="form-control col-md-3 col-sm-12"
                                    disabled
                                    placeholder="Colaborador: "/>
                                <v-select :on-search="selectUsers"
                                    class="form-control col-md-6 col-sm-12"
                                    label="nombre_comp"
                                    :options="arrayUser"
                                    placeholder="Buscar Usuario..."
                                    :onChange="searchByUser">
                                </v-select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <input
                                    type="text"
                                    class="form-control col-md-3 col-sm-12"
                                    disabled
                                    placeholder="Fecha: "
                                />
                                <input
                                    type="date"
                                    v-model="busqueda.fechaIni"
                                    @keyup.enter="getData()"
                                    class="form-control col-md-4 col-sm-12"
                                />
                                <input
                                    type="date"
                                    v-model="busqueda.fechaFin"
                                    @keyup.enter="getData()"
                                    class="form-control col-md-4 col-sm-12"
                                />
                            </div>
                        </div>
                    </div>

                    <div id="accordion" role="tablist">
                        <!-- Historial Medico -->
                        <div class="card mb-0">
                            <div class="card-header" id="headingOne" role="tab">
                                <h5 class="mb-0">
                                    <a
                                        data-toggle="collapse"
                                        href="#collapseOne"
                                        aria-expanded="false"
                                        aria-controls="collapseOne"
                                        class="collapsed"
                                        >Historial de Solicitudes
                                    </a>
                                </h5>
                            </div>
                            <div
                                class="collapse"
                                id="collapseOne"
                                role="tabpanel"
                                aria-labelledby="headingOne"
                                data-parent="#accordion"
                            >
                                <div
                                    class="form-group row border border-primary"
                                    style="margin-right:0px; margin-left:0px;"
                                >
                                    <div class="col-sm-6 col-lg-12">
                                        <div class="card" style="border: none;">
                                            <div class="card-body" style="padding-bottom: 15px;">
                                                <TableComponent
                                                    :cabecera="[
                                                        '',
                                                        'Año',
                                                        'Fecha inicial',
                                                        'Fecha de regreso',
                                                        'Dias tomados',
                                                        'Estatus',
                                                        'Nota'
                                                    ]"
                                                >
                                                    <template v-slot:tbody>
                                                        <tr v-for="vacacion in histVacaciones.data" :key="vacacion.id">
                                                            <td>
                                                                <button class="btn btn-primary" title="Ver Detalle"
                                                                    @click="verDetalle(vacacion)"
                                                                >
                                                                    <span><i class="icon-eye"></i></span>
                                                                </button>
                                                            </td>
                                                            <td class="td2">
                                                                {{ vacacion.anio }}
                                                            </td>
                                                            <td class="td2">
                                                                {{ vacacion.f_ini }}
                                                            </td>
                                                            <td class="td2">
                                                                {{ vacacion.f_fin }}
                                                            </td>
                                                            <td class="td2">
                                                                {{ vacacion.dias_tomados }}
                                                            </td>
                                                            <td class="td2">
                                                                <span :class="vacacion.status == 'pendiente'
                                                                            ? 'badge badge-warning'
                                                                            : vacacion.status ==
                                                                              'rechazado'
                                                                            ? 'badgebadge-danger'
                                                                            : 'badgebadge-success'">
                                                                    {{ vacacion.status.toUpperCase() }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                {{ vacacion.nota }}
                                                            </td>
                                                        </tr>
                                                    </template>
                                                </TableComponent>
                                                <NavComponent
                                                    :current="histVacaciones.current_page
                                                            ? histVacaciones.current_page
                                                            : 1"
                                                    :last="histVacaciones.last_page
                                                            ? histVacaciones.last_page
                                                            : 1"
                                                    @changePage="getHistorial"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>

        <ModalSolicitud v-if="modal.mostrar == 1" :titulo="modal.titulo"
            :accion="modal.accion"
            :data="datos"
            @close="closeModal"
        ></ModalSolicitud>
    </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
import LoadingComponentVue from "../../Componentes/LoadingComponent.vue";
import ModalComponent from "../../Componentes/ModalComponent.vue";
import TableComponent from "../../Componentes/TableComponent.vue";
import NavComponent from '../../Componentes/NavComponent.vue';
import ModalSolicitud from './ModalSolicitud.vue'
import vSelect from "vue-select";

export default {
    components: {
        LoadingComponentVue,
        ModalComponent,
        TableComponent,
        NavComponent,
        vSelect,
        ModalSolicitud
    },
    props: {
        userId: { type: String },
        userName: { type: String },
    },
    data() {
        return {
            busqueda: {
                fechaIni: "",
                fechaFin: "",
                status: "",
                user_id: this.userId
            },
            datosVacaciones: [],
            histVacaciones: {},
            arrayUser: [],
            perfil: {
                nombre: "",
                foto: "",
                dias_disponibles: 0
            },
            loading: false,
            modal: {
                mostrar: 0,
                titulo: "",
                accion: "nuevo"
            },
            datos:{
                f_ini: '',
                f_fin: '',
                dias_tomados: 0,
                nota: '',
                status: 'pendiente',
                vacation_id: '',
                dias_disponibles: 0,
                dias_festivos: 0,
                jefe_id: ''
            },
        };
    },
    computed: {},
    methods: {
        getHistorial(page){
            let me = this;

            const url = `/hist-vacaciones?page=${page}&user_id=${me.busqueda.user_id}`
            axios
                .get(url)
                .then(function(response) {
                    const respuesta = response.data;
                    me.histVacaciones = respuesta;
                })
                .catch(function(error) {
                    me.histVacaciones = {};
                });
        },
        getData() {
            let me = this;
            me.datosVacaciones = [];
            me.loading = true;

            const url =
                "/vacaciones?fechaIni=" +
                me.busqueda.fechaIni +
                "&fechaFin=" +
                me.busqueda.fechaFin +
                "&user_id=" +
                me.busqueda.user_id;

            axios
                .get(url)
                .then(function(response) {
                    const respuesta = response.data;
                    me.datosVacaciones = respuesta;
                    if(me.datosVacaciones.length == 0)
                    me.perfil = {
                        nombre: "",
                        foto: "",
                        dias_disponibles: 0
                    }
                    else
                        me.perfil = {
                            nombre: `${me.datosVacaciones[0].nombre} ${
                                me.datosVacaciones[0].apellidos
                            }`,
                            foto: me.datosVacaciones[0].foto_user,
                            dias_disponibles: me.datosVacaciones[0].saldo
                        }
                    me.getHistorial(1)
                    me.loading = false;
                })
                .catch(function(error) {
                    me.datosVacaciones = [];
                    me.perfil = {
                        nombre: "",
                        foto: "",
                        dias_disponibles: 0
                    }
                    me.loading = false;
                });
        },
        searchByUser(usuario) {
            let me = this;
            //me.loading = true;
            me.busqueda.user_id = usuario.id;
            me.getData();
        },
        selectUsers(search, loading) {
            let me = this;
            loading(true);
            me.arrayUser = [];
            var url = "/usuarios/selectUser?buscar=" + search;
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    q: search;
                    const data = [...respuesta.data];
                    me.arrayUser = data;
                    loading(false);
                })
                .catch(function(error) {
                    console.log(error);
                    loading(false);
                });
        },
        nuevaSolicitud() {
            this.modal.mostrar = 1;
            this.modal.titulo = `Nuevo solicitud de vacaciones: ${
                this.perfil.nombre
            }`;
            this.modal.accion = "nuevo";
            this.datos = {
                f_ini: '',
                f_fin: '',
                dias_tomados: 0,
                nota: '',
                status: 'pendiente',
                vacation_id: this.datosVacaciones[0].id,
                dias_disponibles: this.perfil.dias_disponibles,
                dias_festivos: 0,
                jefe_id: '',
            }
        },

        verDetalle(solicitud) {
            this.modal.mostrar = 1;
            this.modal.titulo = `Solicitud de: ${
                this.perfil.nombre
            }`;
            this.modal.accion = "ver";
            this.datos = solicitud;
        },

        closeModal() {
            this.modal.mostrar = 0;
            this.modal.titulo = "";
            this.datos = {
                f_ini: '',
                f_fin: '',
                dias_tomados: 0,
                nota: '',
                status: 'pendiente',
                vacation_id: '',
                dias_disponibles: 0,
                jefe_id: ''
            };
            this.getData();
        },
    },
    mounted() {
        this.getData();
    }
};
</script>
<style scoped>
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
.card .avatar {
    width: 125px;
    height: 125px;
    overflow: hidden;
    border-radius: 80%;
    margin-right: 5px;
}
.card-user .author {
    text-align: center;
    text-transform: none;
    margin-top: -65px;
}
.info-center {
    display: flex;
    justify-content: center;
    width: 100% !important;
}
.button-header {
    display: flex;
    justify-content: flex-end;
    margin-top: -20px;
}
</style>
