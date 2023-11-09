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
                    <i class="fa fa-align-justify"></i> {{ title }}
                    <template v-if="vistaAsesores">
                        <Button :btnClass="'btn-light'" :icon="'fa fa-arrow-left'" @click="vistaAsesores=false, getData(1)">
                            Regresar
                        </Button>
                        <Button :btnClass="'btn-secondary'" :icon="'icon-plus'" @click="abrirModal('registrar-asesor')">
                            Nuevo Asesor
                        </Button>
                    </template>
                    <Button v-else :btnClass="'btn-secondary'" :icon="'icon-plus'" @click="abrirModal('registrar')">
                        Nuevo Inmobiliaria
                    </Button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" v-model="buscar" @keyup.enter="getData(1)" class="form-control" placeholder="Texto a buscar">
                                <Button   :icon="'fa fa-search'" @click="getData(1)">
                                    Buscar
                                </Button>
                            </div>
                        </div>
                    </div>
                    <template v-if="!vistaAsesores">
                        <TableComponent type="table"
                            :cabecera="['Opciones','Inmobiliaria','Logo','']">
                            <template v-slot:tbody>
                                <tr v-for="inmobiliaria in arrayInmobiliarias.data" :key="inmobiliaria.id">
                                    <td class="td2" style="width:15%">
                                        <Button :btnClass="'btn-warning'" :size="'btn-sm'" :icon="'icon-pencil'" title="Editar"
                                            @click="abrirModal('actualizar',inmobiliaria)"
                                        ></Button>
                                        <Button v-if="inmobiliaria.asesores == 0" :btnClass="'btn-danger'" :size="'btn-sm'" :icon="'icon-trash'" title="Eliminar"
                                            @click="deleteInmobiliaria(inmobiliaria.id)"
                                        ></Button>
                                    </td>
                                    <td class="td2" v-text="inmobiliaria.nombre"></td>
                                    <td class="td2 text-center">
                                        <template v-if="inmobiliaria.logo">
                                            <img :src="`/img/logos/${inmobiliaria.logo}`" style="width: 65px;" alt=""
                                                @mouseover="zoomIn" @mouseleave="zoomOut"
                                            >
                                        </template>
                                    </td>
                                    <td>
                                        <Button btnClass="btn-primary" icon="icon-eye" size="btn-sm"
                                            title="Ver Asesores"
                                            @click="verAsesores(inmobiliaria.id)"
                                        ></Button>
                                    </td>
                                </tr>
                            </template>
                        </TableComponent>
                        <Nav
                            :current="arrayInmobiliarias.current_page ? arrayInmobiliarias.current_page : 1"
                            :last="arrayInmobiliarias.last_page ? arrayInmobiliarias.last_page : 1"
                            @changePage="getData"
                        ></Nav>
                    </template>
                    <template v-else>
                        <TableComponent type="table"
                            :cabecera="['Opciones','Asesor','Celular','Foto']">
                            <template v-slot:tbody>
                                <tr v-for="asesor in arrayAsesores.data" :key="asesor.id">
                                    <td class="td2" style="width:15%">
                                        <Button :btnClass="'btn-warning'" :size="'btn-sm'" :icon="'icon-pencil'" title="Editar"
                                            @click="abrirModal('actualizar-asesor',asesor)"
                                        ></Button>
                                        <Button :btnClass="'btn-danger'" :size="'btn-sm'" :icon="'icon-trash'" title="Eliminar"
                                            @click="deleteAsesor(asesor.id)"
                                        ></Button>
                                    </td>
                                    <td class="td2" v-text="asesor.nombre+' '+asesor.apellido"></td>
                                    <td class="td2">
                                        <a v-if="asesor.celular != null" title="Enviar whatsapp" class="btn btn-success"
                                        target="_blank" :href="'https://api.whatsapp.com/send?phone=+52'+asesor.celular+'&text='">
                                        <i class="fa fa-whatsapp fa-lg"></i>
                                    </a>
                                    </td>
                                    <td class="td2 text-center">
                                        <template v-if="asesor.photo">
                                            <img :src="`/img/externos/${asesor.photo}`" style="width: 65px;" alt=""
                                                @mouseover="zoomIn" @mouseleave="zoomOut"
                                            >
                                        </template>
                                    </td>
                                </tr>
                            </template>
                        </TableComponent>
                        <Nav
                            :current="arrayAsesores.current_page ? arrayAsesores.current_page : 1"
                            :last="arrayAsesores.last_page ? arrayAsesores.last_page : 1"
                            @changePage="getData"
                        ></Nav>
                    </template>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <InmobiliariaModal v-if="modal==1"
            :titulo="tituloModal"
            :tipoAccion="tipoAccion"
            :data="data"
            @close="cerrarModal()"
        ></InmobiliariaModal>
        <AsesoresExternosModal v-if="modal==2"
            :titulo="tituloModal"
            :tipoAccion="tipoAccion"
            :data="dataAsesor"
            @close="cerrarModal()"
        ></AsesoresExternosModal>
    </main>
</template>

<script>
    import TableComponent from '../Componentes/TableComponent.vue'
    import Button from '../Componentes/ButtonComponent.vue'
    import Nav from '../Componentes/NavComponent.vue'
    import LoadingComponent from '../Componentes/LoadingComponent'
    import InmobiliariaModal from './modales/InmobiliariaModal.vue'
    import AsesoresExternosModal from './modales/AsesoresExternosModal.vue'

    export default {
        components:{
            LoadingComponent,
            TableComponent,
            Button, Nav,
            InmobiliariaModal,
            AsesoresExternosModal
        },
        data (){
            return {
                title: 'Inmobiliarias',
                buscar : '',
                proceso:false,
                loading:true,
                vistaAsesores: false,
                data:{
                    id: 0,
                    nombre : '',
                    logo: ''
                },
                arrayInmobiliarias : [],
                arrayAsesores: [],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                inmobiliaria_id: '',
                dataAsesor:{
                    id: 0,
                    mobiliaria_id: 0,
                    nombre: '',
                    apellido: '',
                    direccion: '',
                    celular: '',
                    photo: '',
                    f_ini: '',
                    f_fin: ''
                }
            }
        },
        computed:{
        },
        methods : {
            verAsesores(id){
                this.inmobiliaria_id = id
                this.vistaAsesores = true;
                this.getData(1)
            },
            getData (page){
                if(this.vistaAsesores)
                    this.indexAsesores(page)
                else
                    this.indexInmobiliarias(page)

            },
            indexInmobiliarias(page){
                let me = this;
                this.title = 'Inmobiliarias'
                me.loading = true;
                axios.get('/inmobiliarias'+
                    '?buscar='+this.buscar+
                    '&page='+page

                ).then(
                    response => this.arrayInmobiliarias = response.data
                ).catch(error => console.log(error)
                ).finally(
                    me.loading=false
                )
            },
            indexAsesores(page){
                let me = this;
                this.title = 'Asesores Externos'
                me.loading = true;
                axios.get('/asesores-externos'+
                    '?buscar='+this.buscar+
                    '&inmobiliaria_id='+this.inmobiliaria_id+
                    '&page='+page

                ).then(
                    response => this.arrayAsesores = response.data
                ).catch(error => console.log(error)
                ).finally(
                    me.loading=false
                )
            },
            zoomIn(event) {
                event.target.style.transform = 'scale(2.5)';
                event.target.style.transition = 'transform 0.5s';
            },
            zoomOut(event) {
                event.target.style.transform = 'scale(1)';
            },
            limpiarData(){
                if(this.vistaAsesores){
                    this.dataAsesor = {
                        id: 0,
                        mobiliaria_id: this.inmobiliaria_id,
                        nombre: '',
                        apellido: '',
                        direccion: '',
                        celular: '',
                        photo: '',
                        f_ini: '',
                        f_fin: ''
                    }
                }
                else{
                    this.data = {
                        id: 0,
                        nombre : '',
                        logo: ''
                    }
                }
            },
            cerrarModal(){
                this.modal = false
                this.getData(1)
            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(accion,data =[]){
                switch(accion){
                    case 'registrar':
                    {
                        this.tituloModal = 'Registrar Inmobiliaria';
                        this.tipoAccion = 1;
                        this.limpiarData()
                        this.modal = 1;
                        break;
                    }
                    case 'registrar-asesor':
                    {
                        this.tituloModal = 'Registrar Asesor';
                        this.tipoAccion = 1;
                        this.limpiarData()
                        this.modal = 2
                        break;
                    }
                    case 'actualizar':
                    {
                        //console.log(data);
                        this.tituloModal='Actualizar Inmobiliaria';
                        this.tipoAccion=2;
                        this.data = data;
                        this.modal = 1;
                        break;
                    }
                    case 'actualizar-asesor':
                    {
                        //console.log(data);
                        this.tituloModal='Actualizar Asesor';
                        this.tipoAccion=2;
                        this.dataAsesor = data;
                        this.modal = 2;
                        break;
                    }
                }
            },
            deleteInmobiliaria(id){
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

                axios.delete(`inmobiliarias/${id}`,
                        {params: {'id': id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Inmobiliaria borrado correctamente.',
                        'success'
                        )
                        me.getData(1) //se enlistan nuevamente los registros
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            deleteAsesor(id){
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

                axios.delete(`asesores-externos/${id}`,
                        {params: {'id': id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Asesor eliminado correctamente.',
                        'success'
                        )
                        me.getData(1) //se enlistan nuevamente los registros
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            }
        },
        mounted() {
            this.getData(1);
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
