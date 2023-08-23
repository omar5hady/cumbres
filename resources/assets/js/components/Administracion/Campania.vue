<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol>
        <LoadingComponent v-if="loading"></LoadingComponent>
        <div class="container-fluid" v-else>
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Campañas publicitarias
                    <Button :btnClass="'btn-secondary'" :icon="'icon-plus'" @click="abrirModal('registrar')">
                        Nuevo
                    </Button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" v-model="buscar" @keyup.enter="listarCampania(1)" class="form-control" placeholder="Texto a buscar">
                                <Button   :icon="'fa fa-search'" @click="listarCampania(1)">
                                    Buscar
                                </Button>
                            </div>
                        </div>
                    </div>
                    <TableComponent :cabecera="['Opciones','Campaña','Medio Digital','Inicio','Termino','Presupuesto']">
                        <template v-slot:tbody>
                            <tr v-for="campania in arrayCampanias.data" :key="campania.id">
                                <td class="td2" style="width:15%">
                                    <Button :btnClass="'btn-warning'" :size="'btn-sm'" :icon="'icon-pencil'" title="Editar"
                                        @click="abrirModal('actualizar',campania)"
                                    ></Button>
                                    <Button :btnClass="'btn-danger'" :size="'btn-sm'" :icon="'icon-trash'" title="Eliminar"
                                        @click="eliminarCampania(campania)"
                                    ></Button>
                                </td>
                                <td class="td2" v-text="campania.nombre_campania"></td>
                                <td class="td2" v-text="campania.medio_digital"></td>
                                <td class="td2" v-text="this.moment(campania.fecha_ini).locale('es').format('DD/MMM/YYYY')"></td>
                                <td class="td2" v-text="this.moment(campania.fecha_fin).locale('es').format('DD/MMM/YYYY')"></td>
                                <td class="td2" v-text="'$'+$root.formatNumber(campania.presupuesto)"></td>
                            </tr>
                        </template>
                    </TableComponent>
                    <Nav
                        :current="arrayCampanias.current_page ? arrayCampanias.current_page : 1"
                        :last="arrayCampanias.last_page ? arrayCampanias.last_page : 1"
                        @changePage="listarCampania"
                    ></Nav>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <CampaniasModal v-if="modal"
            :titulo="tituloModal"
            :tipoAccion="tipoAccion"
            :data="data"
            @close="cerrarModal()"
        ></CampaniasModal>
    </main>
</template>

<script>
    import TableComponent from '../Componentes/TableComponent.vue'
    import Button from '../Componentes/ButtonComponent.vue'
    import Nav from '../Componentes/NavComponent.vue'
    import LoadingComponent from '../Componentes/LoadingComponent'
    import CampaniasModal from './modales/CampaniasModal.vue'

    export default {
        components:{
            LoadingComponent,
            TableComponent,
            Button, Nav,
            CampaniasModal
        },
        data (){
            return {
                buscar : '',
                proceso:false,
                loading:true,
                data:{
                    id: 0,
                    nombre : '',
                    medio_digital:'',
                    fecha_ini:'',
                    fecha_fin:'',
                    presupuesto:0,
                },
                arrayCampanias : [],
                modal : false,
                tituloModal : '',
                tipoAccion: 0,
                criterio : 'nombre',
            }
        },
        computed:{

        },
        methods : {
            listarCampania (page){
                let me = this;
                me.loading = true;
                axios.get('/campanias/index'+
                    '?buscar='+this.buscar+
                    '&page='+page

                ).then(
                    response => this.arrayCampanias = response.data
                ).catch(error => console.log(error)
                ).finally(
                    me.loading=false
                )
            },
            eliminarCampania(data =[]){
                this.id=data['id'];
                this.nombre=data['nombre'];
                swal({
                title: '¿Desea eliminar?',
                text: "Esta acción no se puede revertir!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si, eliminar!'
                }).then((result) => {
                if (result.value) {
                    let me = this;

                axios.delete('/campanias/delete',
                        {params: {'id': this.id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Campaña eliminada correctamente.',
                        'success'
                        )
                        me.listarCampania(1); //se enlistan nuevamente los registros
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            cerrarModal(){
                this.modal = false;
                this.tituloModal = '';
                this.listarCampania()
            },
            limpiarData(){
                this.data = {
                    id: 0,
                    nombre : '',
                    medio_digital:'',
                    fecha_ini:'',
                    fecha_fin:'',
                    presupuesto:0,
                }
            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(accion,data =[]){
                switch(accion){
                    case 'registrar':
                    {
                        this.tituloModal = 'Registrar Campaña';
                        this.tipoAccion = 1;
                        this.limpiarData()
                        break;
                    }
                    case 'actualizar':
                    {
                        //console.log(data);
                        this.tituloModal='Actualizar Campaña';
                        this.tipoAccion=2;
                        this.data = data;
                        break;
                    }
                }
                this.modal = true;
            }
        },
        mounted() {
            this.listarCampania(1);
        }
    }
</script>
<style>
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
