<template>
    <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
               <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card scroll-box">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Reserva territorial
                        <button type="button" @click="abrirModal({opcion: 'registrar', data:{} })" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                        <!-- <a :href="'/fraccionamiento/excel?buscar=' + search.buscar" class="btn btn-success"><i class="fa fa-file-text"></i> Excel </a> -->
                    </div>
                    <!-- Listado data -->
                    <div class="container">
                        <ListadoTerrenos :data="listado.data"
                            @getData="getData"
                            @abrirModal="abrirModal"
                        ></ListadoTerrenos>
                        <NavComponent :current="listado.current_page ? listado.current_page : 1"
                            :last="listado.last_page ? listado.last_page : 1"
                            @changePage="listarTerrenos">
                        </NavComponent>
                    </div>
                    <!--  -->
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <ModalTerrenos v-if="modal ==1"
            :datos="data"
            :titulo="tituloModal"
            :tipoAccion="tipoAccion"
            @closeModal=cerrarModal
            ></ModalTerrenos>

            <!--Fin del modal-->
        </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
import ListadoTerrenos from './components/ListadoTerrenos.vue';
import NavComponent from '../Componentes/NavComponent.vue';
import ModalTerrenos from './components/ModalTerrenos.vue';
    export default {
        components:{
            ListadoTerrenos,
            NavComponent,
            ModalTerrenos
        },
        props:{
        },
        data(){
            return{
                listado: {
                    data: []
                },
                search:{
                    buscar: ''
                },
                modal: 0,
                tituloModal: '',
                tipoAccion: 1,
                data: {}
            }
        },
        computed:{

        },
        methods : {
            getData( buscadores ){
                this.search = { ...buscadores}
                this.listarTerrenos(1);
            },
            listarTerrenos(page){
                let me = this;
                var url = '/terrenos-compra?page='+ page +'&buscar=' + this.search.buscar ;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.listado = respuesta;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cerrarModal(){
                this.modal = 0;
                this.listarTerrenos(1);
            },
            abrirModal( opciones ){
                const { opcion, data } = { ...opciones }

                switch( opcion ){
                    case 'registrar':
                        this.data = {}
                        this.tipoAccion = 1;
                        this.tituloModal = 'Nuevo terreno';
                        break;
                    case 'editar':
                        this.data = data;
                        this.tipoAccion = 2;
                        this.tituloModal = 'Editar terreno';
                        break;
                }

                this.modal = 1;
            }
        },
        mounted() {
            this.listarTerrenos(1);
        }
    }
</script>
<style>

</style>
