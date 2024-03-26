<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <strong><a style="color:#FFFFFF;" href="/">Home</a></strong>
            </li>
        </ol>

        <LoadingComponent v-if="loading" />
        <div class="container-fluid" v-else>
            <!-- Ejemplo de tabla Listado -->
            <div class="card scroll-box">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i>Solicitudes de Vacaciones
                </div>

                <!-- listado de devoluciones -->
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-9">
                            <div class="input-group">
                                <!--Criterios para el listado de busqueda -->
                                <input type="text"
                                    class="form-control col-md-4"
                                    disabled placeholder="Colaborador:"
                                />
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="busqueda.nombre"
                                    @keyup.enter="getHistorial(1)"
                                    placeholder="Nombre a Buscar"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <!--Criterios para el listado de busqueda -->
                                <input type="text"
                                    class="form-control col-md-4"
                                    disabled placeholder="Status:"
                                />
                                <select class="form-control" v-model="busqueda.status">
                                    <option value="">Todos</option>
                                    <option value="pendiete">Pendientes</option>
                                    <option value="aprobado">Aprobados</option>
                                    <option value="rechazado">Rechazados</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-8">
                            <div class="input-group">
                                <button
                                    type="submit"
                                    @click="getHistorial(1)"
                                    class="btn btn-primary"
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </div>
                    <TableComponent
                        :cabecera="[
                            '',
                            'Colaborador',
                            'Año',
                            'Fecha inicial',
                            'Fecha de regreso',
                            'Dias tomados',
                            'Estatus',
                            'Nota',
                            'Rev. Jefe',
                            'Rev. RH',
                            'Obs'
                        ]"
                    >
                        <template v-slot:tbody>
                            <tr v-for="vacacion in histVacaciones.data" :key="vacacion.id">
                                <td>
                                    <button v-if="vacacion.status == 'pendiente'"
                                        class="btn btn-danger" title="Rechazar solicitud"
                                        @click="rechazar(vacacion.id)"
                                    >
                                        <span><i class="icon-close"></i></span>
                                    </button>
                                    <button class="btn btn-primary" title="Ver Detalle"
                                        @click="verDetalle(vacacion)"
                                    >
                                        <span><i class="icon-eye"></i></span>
                                    </button>
                                </td>
                                <td class="td2">
                                    {{ vacacion.nombre }} {{ vacacion.apellidos }}
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
                                                ? 'badge badge-danger'
                                                : 'badge badge-success'">
                                        {{ vacacion.status.toUpperCase() }}
                                    </span>
                                </td>
                                <td>
                                    {{ vacacion.nota }}
                                </td>
                                <td class="td2">
                                    <button v-if="vacacion.status != 'rechazado' && vacacion.f_jefe == null"
                                        class="btn btn-success" title="Autorizar" @click="autorizarJefe(vacacion.id)">
                                        <span><i class="icon-check"></i></span>
                                    </button>
                                    {{ vacacion.f_jefe ? vacacion.f_jefe : '' }}
                                </td>
                                <td class="td2">
                                    <button v-if="vacacion.status != 'rechazado' && vacacion.f_rh == null && (userName=='marce.gaytan'  || userName=='shady')"
                                        class="btn btn-success" title="Autorizar" @click="revisarRH(vacacion.id)">
                                        <span><i class="icon-check"></i></span>
                                    </button>
                                    {{ vacacion.f_rh ? vacacion.f_rh : '' }}
                                </td>
                                <td>
                                    <button class="btn btn-dark" title="Ver Observaciones" @click="verObs(vacacion.id)">
                                        <span><i class="icon-eye"></i></span>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </TableComponent>
                    <div class="container">
                        <Nav
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
            <!-- Fin ejemplo de tabla Listado -->
        </div>

        <ModalSolicitud v-if="modal.mostrar == 1" :titulo="modal.titulo"
            :accion="modal.accion"
            :data="datos"
            @close="closeModal"
        ></ModalSolicitud>

        <ModalComponent v-if="modal.mostrar == 2"
            :titulo="modal.titulo"
            @closeModal="closeModal"
        >
            <template v-slot:body>
                <RowModal label1="Observación" id1="observacion" clsRow1="col-md-6"
                    label2="" clsRow2="col-md-2"
                >
                    <textarea rows="3" cols="30" v-model="observacion" class="form-control" placeholder="Observacion"></textarea>
                    <template v-slot:input2>
                        <button type="button"  class="btn btn-primary" @click="agregarComentario()">Guardar</button>
                    </template>
                </RowModal>

                <RowModal label1="" clsRow1="col-md-12">
                    <table class="table table-bordered table-striped table-sm" >
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Observacion</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="observacion in arrayObs" :key="observacion.id">

                                <td v-text="observacion.usuario" ></td>
                                <td v-text="observacion.observacion" ></td>
                                <td v-text="observacion.created_at"></td>
                            </tr>
                        </tbody>
                    </table>
                </RowModal>
            </template>
        </ModalComponent>
    </main>
</template>
<script>
import LoadingComponent from '../../Componentes/LoadingComponent.vue';
import TableComponent from '../../Componentes/TableComponent.vue';
import Button from '../../Componentes/ButtonComponent.vue';
import Nav from '../../Componentes/NavComponent.vue';
import ModalComponent from "../../Componentes/ModalComponent.vue";
import RowModal from '../../Componentes/ComponentesModal/RowModalComponent';
import ModalSolicitud from './ModalSolicitud.vue'
export default {
    props:{
        userName: { type: String },
    },
    components:{
        LoadingComponent,
        TableComponent,
        Button,
        Nav,
        ModalComponent,
        RowModal,
        ModalSolicitud
    },
    data() {
        return {
            loading: true,
            busqueda: {
                fechaIni: "",
                fechaFin: "",
                status: "",
                nombre: ""
            },
            arrayObs: [],
            observacion: '',
            id: '',
            histVacaciones: {},
            modal: {
                mostrar: 0,
                titulo: "",
                accion: "nuevo"
            },
            proceso: false,
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
        }
    },
    methods: {
        getHistorial(page){
            let me = this;
            me.loading = true;
            const url = `/hist-vacaciones?page=${page}&nombre=${me.busqueda.nombre}&status=${me.busqueda.status}&admin=1`
            axios
                .get(url)
                .then(function(response) {
                    const respuesta = response.data;
                    me.histVacaciones = respuesta;
                    me.loading = false;
                })
                .catch(function(error) {
                    me.histVacaciones = {};
                    me.loading = false;
                });
        },
        getObs(id){
            let me = this;

            const url = `/obs-vacaciones?id=${id}`
            axios
                .get(url)
                .then(function(response) {
                    const respuesta = response;
                    me.arrayObs = respuesta.data;
                })
                .catch(function(error) {
                    me.arrayObs = [];
                });
        },
        agregarComentario(){
            if(this.proceso==true){
                return;
            }
            this.proceso=true;
            let me = this;
            //Con axios se llama el metodo store de DepartamentoController
            axios.post('/obs-vacaciones',{
                'id': this.id,
                'observacion': this.observacion
            }).then(function (response){
                me.proceso=false;
                me.getObs(me.id);
                me.observacion = '';

                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });

                toast({
                    type: 'success',
                    title: 'Observación Agregada Correctamente'
                })
            }).catch(function (error){
                console.log(error);
            });
        },
        verObs(id){
            this.id = id;
            this.modal.mostrar = 2;
            this.modal.titulo = `Observaciones de la solictud`;
            this.getObs(id)
        },
        verDetalle(solicitud) {
            this.modal.mostrar = 1;
            this.modal.titulo = `Solicitud de: ${
                solicitud.nombre
            }`;
            this.modal.accion = "ver";
            this.datos = solicitud;
        },
        rechazar(id){
            let me = this;

            if(me.proceso) return;

            me.proceso = true;
            Swal({
                title: 'Rechazar',
                animation: false,
                customClass: 'animated bounceInDown',
                text: "La solicitud sera sera rechazada, ¿Desea continuar?.",
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',

                confirmButtonText: 'Si, rechazar!'
            }).then((result) => {
                if (result.value) {
                    axios.put('/vacation/rechazarSolicitud',{
                        'id': id,
                    }).then(function (response){
                        me.getHistorial(1)
                        me.proceso = false;

                        const toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });

                        toast({
                            type: 'success',
                            title: 'Solicitud actualizada correctamente.'
                        })
                    }).catch(function (error){
                        console.log(error);
                        me.proceso = false;
                    });
                }
            })
        },
        revisarRH(id){
            let me = this;

            if(me.proceso) return;

            me.proceso = true;
            Swal({
                title: 'Revisar',
                animation: false,
                customClass: 'animated bounceInDown',
                text: "La solicitud sera revisada.",
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',

                confirmButtonText: 'Si, revisar!'
            }).then((result) => {
                if (result.value) {
                    axios.put('/vacation/revisarRH',{
                        'id': id,
                    }).then(function (response){
                        me.getHistorial(1)
                        me.proceso = false;

                        const toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });

                        toast({
                            type: 'success',
                            title: 'Solicitud actualizada correctamente.'
                        })
                    }).catch(function (error){
                        console.log(error);
                        me.proceso = false;
                    });
                }
            })
        },
        autorizarJefe(id){
            let me = this;

            if(me.proceso) return;

            me.proceso = true;
            Swal({
                title: 'Autorizar',
                animation: false,
                customClass: 'animated bounceInDown',
                text: "La solicitud sera autorizada, ¿Desea continuar?.",
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',

                confirmButtonText: 'Si, autorizar!'
            }).then((result) => {
                if (result.value) {
                    axios.put('/vacation/autorizarSolicitud',{
                        'id': id,
                    }).then(function (response){
                        me.getHistorial(1)
                        me.proceso = false;

                        const toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });

                        toast({
                            type: 'success',
                            title: 'Solicitud actualizada correctamente.'
                        })
                    }).catch(function (error){
                        console.log(error);
                        me.proceso = false;
                    });
                }
            })
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
            this.getHistorial(1);
        },

    },
    mounted() {
        this.getHistorial(1);
    },

}
</script>
<style lang="">

</style>
