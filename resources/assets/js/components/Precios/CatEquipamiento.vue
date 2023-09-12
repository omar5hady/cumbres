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
                        <i class="fa fa-align-justify"></i> Catálogo de Equipamientos
                        <!--   Boton Nuevo    -->
                        <ButtonComponent @click="abrirModal('registrar')" btnClass="btn-secondary" icon="icon-plus" title="Nuevo vehiculo">
                            Nuevo
                        </ButtonComponent>
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <label for="proyecto_id" class="form-control">Proyecto: </label>
                                    <select class="form-control col-md-7" v-model="b_proyecto" @change="$root.selectModelo(b_proyecto)" id="proyecto_id">
                                        <option value="">Seleccione</option>
                                        <option v-for="proyecto in $root.$data.proyectos"
                                            :key="proyecto.id" :value="proyecto.id"
                                            v-text="proyecto.nombre"></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <label for="modelo_id" class="form-control">Modelo: </label>
                                    <select class="form-control col-md-7" v-model="b_modelo" id="modelo_id">
                                        <option value="">Seleccione</option>
                                        <option v-for="modelo in $root.$data.modelos"
                                            :key="modelo.id" :value="modelo.id"
                                            v-text="modelo.nombre"></option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row" v-if="b_status == 0">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input v-model="b_fecha1" type="date" class="form-control"/>
                                    <input v-model="b_fecha2" type="date" class="form-control"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <ButtonComponent  :icon="'fa fa-search'" @click="listarCatalogo(1)">
                                        Buscar
                                    </ButtonComponent>
                                </div>
                            </div>
                        </div>

                        <ul class="nav nav-tabs" id="myTab1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link"
                                @click="b_status = 1, listarCatalogo(1), limpiarFiltro()"
                                v-bind:class="{ 'text-primary active': b_status === 1}"
                                role="tab">{{ (b_status === 1) ? `Activos: ${arrayCatalogo.total ? arrayCatalogo.total : ''}` : 'Activos' }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"
                                @click="b_status = 0, listarCatalogo(1), limpiarFiltro()"
                                v-bind:class="{ 'text-primary active': b_status === 0}"
                                role="tab">{{ (b_status === 0) ? `Historial: ${arrayCatalogo.total ? arrayCatalogo.total : ''}` : 'Historial' }}</a>
                            </li>

                        </ul>

                        <div class="tab-content" id="myTab1Content">
                            <!-- Catalogo Activo -->
                            <div class="tab-pane fade"  v-bind:class="{ 'active show': b_status === 1 }" v-if="b_status ===  1">
                                <TableComponent
                                    :cabecera="['Opciones','Proyecto','Modelo','','Cocina Tradicional','Vestidor','Closets',
                                    'Cocina C.M.','Canceles', 'Persianas', 'Calentador Solar', 'Espejos', 'Tanque Estacionario',
                                    'Fecha de alta']">
                                    <template v-slot:tbody>
                                        <tr v-for="catalogo in arrayCatalogo.data" :key="catalogo.id">
                                            <td class="td2">
                                                <ButtonComponent @click="abrirModal('actualizar',catalogo)" title="Editar"
                                                    btnClass="btn-warning" size="btn-sm" icon="icon-pencil">
                                                </ButtonComponent>
                                            </td>
                                            <td class="td2" v-text="catalogo.proyecto"></td>
                                            <td class="td2" v-text="catalogo.modelo"></td>
                                            <td class="td2">&nbsp;</td>
                                            <td class="td2" v-text="$root.formatNumber(catalogo.cocina_tradicional)"></td>
                                            <td class="td2" v-text="$root.formatNumber(catalogo.vestidor)"></td>
                                            <td class="td2" v-text="$root.formatNumber(catalogo.closets)"></td>
                                            <td></td>
                                            <td class="td2" v-text="$root.formatNumber(catalogo.cocina)"></td>
                                            <td class="td2" v-text="$root.formatNumber(catalogo.canceles)"></td>
                                            <td class="td2" v-text="$root.formatNumber(catalogo.persianas)"></td>
                                            <td class="td2" v-text="$root.formatNumber(catalogo.calentador_solar)"></td>
                                            <td class="td2" v-text="$root.formatNumber(catalogo.espejos)"></td>
                                            <td class="td2" v-text="$root.formatNumber(catalogo.tanque_estacionario)"></td>
                                            <td class="td2" v-text="catalogo.created_at"></td>
                                        </tr>
                                    </template>
                                </TableComponent>
                            </div>
                            <!-- Historial -->
                            <div class="tab-pane fade"  v-bind:class="{ 'active show': b_status === 0 }" v-if="b_status ===  0">
                                <TableComponent
                                    :cabecera="['Opciones','Proyecto','Modelo','','Cocina Tradicional','Vestidor','Closets','Fecha de alta']">
                                    <template v-slot:tbody>
                                        <tr v-for="catalogo in arrayCatalogo.data" :key="catalogo.id">
                                            <td class="td2">
                                                <ButtonComponent @click="abrirModal('ver',catalogo)" title="Ver"
                                                    size="btn-sm" icon="icon-eye">
                                                </ButtonComponent>
                                            </td>
                                            <td class="td2" v-text="catalogo.proyecto"></td>
                                            <td class="td2" v-text="catalogo.modelo"></td>
                                            <td class="td2">&nbsp;</td>
                                            <td class="td2" v-text="$root.formatNumber(catalogo.cocina_tradicional)"></td>
                                            <td class="td2" v-text="$root.formatNumber(catalogo.vestidor)"></td>
                                            <td class="td2" v-text="$root.formatNumber(catalogo.closets)"></td>
                                            <td class="td2" v-text="catalogo.created_at"></td>
                                        </tr>
                                    </template>
                                </TableComponent>
                            </div>
                        </div>
                        <NavComponent
                            :current="arrayCatalogo.current_page ? arrayCatalogo.current_page : 1"
                            :last="arrayCatalogo.last_page ? arrayCatalogo.last_page : 1"
                            @changePage="listarCatalogo"
                        ></NavComponent>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <CatEquipamientoModal v-if="modal"
                @close="cerrarModal()"
                :titulo="tituloModal"
                :catalogo="data"
                :tipoAccion="tipoAccion"
            ></CatEquipamientoModal>

            <!--Fin del modal-->
        </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    import TableComponent from '../Componentes/TableComponent.vue'
    import NavComponent from '../Componentes/NavComponent.vue'
    import ButtonComponent from '../Componentes/ButtonComponent.vue'
    import CatEquipamientoModal from './components/CatEquipamientoModal.vue'

    export default {
        components:{
            TableComponent,
            ButtonComponent,
            NavComponent,
            CatEquipamientoModal
        },
        data(){
            return{
                b_modelo: '',
                b_proyecto: '',
                b_status: 1,
                b_fecha1:'',
                b_fecha2: '',
                modal: false,
                tituloModal:'',
                tipoAccion: 1,
                data: {
                    modelo_id: '',
                    cocina_tradicional: 0,
                    vestidor: 0,
                    closets: 0,
                    canceles: 0,
                    persianas: 0,
                    calentador_paso: 0,
                    calentador_solar: 0,
                    espejos: 0,
                    tanque_estacionario: 0,
                    cocina: 0,
                },
                arrayCatalogo: [],
            }
        },
        computed:{

        },
        methods : {
            /**Metodo para mostrar los registros */
            listarCatalogo(page){
                let me = this;
                var url = '/cat-equipamiento?page=' + page+'&b_status='
                    +this.b_status + '&b_modelo='+this.b_modelo + '&b_proyecto='+this.b_proyecto
                    + '&b_fecha1='+this.b_fecha1 + '&b_fecha2='+this.b_fecha2;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayCatalogo = respuesta;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            limpiarData(){
                this.data = {
                    modelo_id: '',
                    cocina_tradicional: 0,
                    vestidor: 0,
                    closets: 0,
                    canceles: 0,
                    persianas: 0,
                    calentador_paso: 0,
                    calentador_solar: 0,
                    espejos: 0,
                    tanque_estacionario: 0,
                    cocina: 0,
                }
            },
            limpiarFiltro(){
                this.b_fecha1 = '',
                this.b_fecha2 = ''
            },
            cerrarModal(){
                this.modal = 0;
                this.limpiarData()
                this.listarCatalogo(1)
            },

            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(accion,data =[]){
                switch(accion){
                    case 'registrar':
                    {
                        this.limpiarData()
                        this.modal = 1;
                        this.tituloModal = 'Registrar Catálogo';
                        this.tipoAccion = 1;
                        this.vista = 1;
                        break;
                    }
                    case 'actualizar':
                    {
                        //console.log(data);
                        this.modal =1;
                        this.tituloModal='Actualizar Catálogo';
                        this.data = data;
                        this.tipoAccion=2;
                        break;
                    }

                    case 'ver':
                    {
                        //console.log(data);
                        this.modal =1;
                        this.tituloModal='Detalle';
                        this.data = data;
                        this.tipoAccion=3;
                        break;
                    }
                }
            }
        },
        mounted() {
            this.listarCatalogo(1);
            this.$root.selectFraccionamientos();
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
