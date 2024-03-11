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
                        <button
                            type="button"
                            class="btn btn-success btn-sm"
                            @click="nuevaSolicitud()"
                            v-if="perfil.dias_disponibles > 0"
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
                                        <img
                                            :src="`/img/avatars/${perfil.foto}`"
                                            onerror="this.src='/img/avatars/default-image.gif'"
                                            class="avatar border-white"
                                        />
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
                                <div
                                    class="card-body"
                                    style="padding-bottom: 0px;"
                                >
                                    <TableComponent
                                        v-if="datosVacaciones.length > 0"
                                        :cabecera="[
                                            'Año',
                                            'Total dias',
                                            'Dias disfrutados',
                                            'Saldo',
                                            'Estatus'
                                        ]"
                                    >
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
                                                    <span
                                                        :class="
                                                            vacacion.status ==
                                                            'activo'
                                                                ? 'badge badge-success'
                                                                : 'badgebadge-danger'
                                                        "
                                                    >
                                                        {{
                                                            vacacion.status.toUpperCase()
                                                        }}
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
                                <input
                                    type="text"
                                    class="form-control col-md-3 col-sm-12"
                                    disabled
                                    placeholder="Colaborador: "
                                />
                                <v-select
                                    :on-search="selectUsers"
                                    class="form-control col-md-6 col-sm-12"
                                    label="nombre_comp"
                                    :options="arrayUser"
                                    placeholder="Buscar Usuario..."
                                    :onChange="searchByUser"
                                >
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
                                            <div
                                                class="card-body"
                                                style="padding-bottom: 15px;"
                                            >
                                                <TableComponent
                                                    :cabecera="[
                                                        'Año',
                                                        'Fecha inicial',
                                                        'Fecha de regreso',
                                                        'Dias tomados',
                                                        'Estatus',
                                                        'Nota'
                                                    ]"
                                                >
                                                    <template v-slot:tbody>
                                                        <tr
                                                            v-for="vacacion in histVacaciones.data"
                                                            :key="vacacion.id"
                                                        >
                                                            <td class="td2">
                                                                {{
                                                                    vacacion.anio
                                                                }}
                                                            </td>
                                                            <td class="td2">
                                                                {{
                                                                    vacacion.f_ini
                                                                }}
                                                            </td>
                                                            <td class="td2">
                                                                {{
                                                                    vacacion.f_fin
                                                                }}
                                                            </td>
                                                            <td class="td2">
                                                                {{
                                                                    vacacion.dias_tomados
                                                                }}
                                                            </td>
                                                            <td class="td2">
                                                                <span
                                                                    :class="
                                                                        vacacion.status ==
                                                                        'pendiente'
                                                                            ? 'badge badge-warning'
                                                                            : vacacion.status ==
                                                                              'rechazado'
                                                                            ? 'badgebadge-danger'
                                                                            : 'badgebadge-success'
                                                                    "
                                                                >
                                                                    {{
                                                                        vacacion.status.toUpperCase()
                                                                    }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                {{
                                                                    vacacion.nota
                                                                }}
                                                            </td>
                                                        </tr>
                                                    </template>
                                                </TableComponent>
                                                <NavComponent
                                                    :current="
                                                        histVacaciones.current_page
                                                            ? histVacaciones.current_page
                                                            : 1
                                                    "
                                                    :last="
                                                        histVacaciones.last_page
                                                            ? histVacaciones.last_page
                                                            : 1
                                                    "
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
import vSelect from "vue-select";

export default {
    components: {
        LoadingComponentVue,
        ModalComponent,
        TableComponent,
        NavComponent,
        vSelect
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
            vista: 2,
            modal: {
                mostrar: 0,
                titulo: "",
                accion: "nuevo"
            }
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
            this.modal.titulo = `Nuevo movimiento para: ${
                this.busqueda.nombre
            }`;
            this.modal.accion = "nuevo";
            this.datosPago = {};
        },

        closeModal() {
            this.modal.mostrar = 0;
            this.modal.titulo = "";
            this.datosPago = {};
            this.getData();
        },
        save() {
            let me = this;
            let fondo_id = "";
            if (me.datosVacaciones) {
                fondo_id = me.datosVacaciones.id;
            }

            axios
                .post("/fondo-pension", {
                    fondo_id: fondo_id,
                    monto: me.datosPago.monto,
                    tipo_movimiento: me.datosPago.tipo_movimiento,
                    fecha_movimiento: me.datosPago.fecha_movimiento,
                    concepto: me.datosPago.concepto,
                    user_id: me.busqueda.user_id
                })
                .then(function(response) {
                    me.closeModal();

                    const toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000
                    });

                    toast({
                        type: "success",
                        title: "Movimiento guardado correctamente."
                    });
                })
                .catch(function(error) {
                    console.log(error);
                    me.closeModal();
                });
        },
        update() {
            let me = this;

            axios
                .put(`/fondo-pension/${me.datosPago.id}`, {
                    fondo_id: me.datosPago.fondo_id,
                    monto: me.datosPago.monto,
                    fecha_movimiento: me.datosPago.fecha_movimiento,
                    concepto: me.datosPago.concepto,
                    id: me.datosPago.id
                })
                .then(function(response) {
                    me.closeModal();

                    const toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000
                    });

                    toast({
                        type: "success",
                        title: "Movimiento actualizado correctamente."
                    });
                })
                .catch(function(error) {
                    console.log(error);
                    me.closeModal();
                });
        },
        deletePago(pago) {
            swal({
                title: "¿Estas seguro de eliminar este movimiento?",
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
                        .delete(`/fondo-pension/${pago.id}`, {
                            params: { id: pago.id, fondo_id: pago.fondo_id }
                        })
                        .then(function(response) {
                            swal(
                                "Borrado!",
                                "Movimiento eliminado correctamente.",
                                "success"
                            );
                            me.getData();
                        })
                        .catch(function(error) {
                            console.log(error);
                        });
                }
            });
        }
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
