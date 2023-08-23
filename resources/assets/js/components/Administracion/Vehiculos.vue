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
                        <i class="fa fa-align-justify"></i> Vehiculos
                        <!--   Boton Nuevo    -->
                        <ButtonComponent @click="abrirModal('registrar')" btnClass="btn-secondary" icon="icon-plus" title="Nuevo vehiculo">
                            Nuevo
                        </ButtonComponent>
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-4">
                                <select class="form-control" v-model="b_empresa" >
                                    <option value="">Empresa</option>
                                    <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-4" v-model="b_marca" >
                                        <option value="">Marca</option>
                                        <option v-for="marcas in marcasAutos" :key="marcas" :value="marcas" v-text="marcas"></option>
                                    </select>
                                    <input type="text"  v-model="buscar" @keyup.enter="listarVehiculos(1)" class="form-control" placeholder="Vehiculo">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <select class="form-control" v-model="b_comodato" >
                                        <option value="">Comodato</option>
                                        <option value="0">No</option>
                                        <option value="1">Si</option>
                                    </select>
                                    <ButtonComponent @click="listarVehiculos(1)" icon="fa fa-search">Buscar</ButtonComponent>
                                </div>
                            </div>
                        </div>

                        <TableComponent
                            :cabecera="['Opciones','Vehiculo','Marca','Modelo','Clave','# Serie','# Motor','# Placa','Responsable','Empresa']">
                            <template v-slot:tbody>
                                <tr v-for="vehiculo in arrayVehiculos.data" :key="vehiculo.id">
                                    <td class="td2">
                                        <ButtonComponent @click="abrirModal('actualizar',vehiculo)" title="Editar"
                                            btnClass="btn-warning" size="btn-sm" icon="icon-pencil">
                                        </ButtonComponent>
                                    </td>
                                    <td class="td2" v-text="vehiculo.vehiculo"></td>
                                    <td class="td2" v-text="vehiculo.marca"></td>
                                    <td class="td2" v-text="vehiculo.modelo"></td>
                                    <td class="td2" v-text="vehiculo.clave"></td>
                                    <td class="td2" v-text="vehiculo.numero_serie"></td>
                                    <td class="td2" v-text="vehiculo.numero_motor"></td>
                                    <td class="td2" v-text="vehiculo.placas"></td>
                                    <td class="td2" v-text="vehiculo.nombre + ' '+ vehiculo.apellidos"></td>
                                    <td class="td2" v-text="vehiculo.empresa"></td>
                                </tr>
                            </template>
                        </TableComponent>
                        <NavComponent
                            :current="arrayVehiculos.current_page ? arrayVehiculos.current_page : 1"
                            :last="arrayVehiculos.last_page ? arrayVehiculos.last_page : 1"
                            @changePage="listarVehiculos"
                        ></NavComponent>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <VehiculosModal v-if="modal"
                @close="cerrarModal()"
                :titulo="tituloModal"
                :vehiculo="data"
                :tipoAccion="tipoAccion"
            ></VehiculosModal>

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
    import VehiculosModal from './modales/VehiculosModal.vue'

    export default {
        components:{
            TableComponent,
            ButtonComponent,
            NavComponent,
            VehiculosModal,
            VehiculosModal
        },
        data(){
            return{
                arrayVehiculos : [],
                arrayUsers : [],
                empresas:[
                    'Grupo Constructor Cumbres',
                    'Ser Cumbres',
                    'Concretania',
                    'Magnacasa',
                    'Ing David'
                ],
                marcasAutos:[],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                buscar : '',
                b_empresa : '',
                b_marca:'',
                b_comodato:'',
                data:{
                    id:'',
                    vehiculo : '',
                    responsable_id : '',
                    modelo : '',
                    marca: '',
                    clave: '',
                    placas: '',
                    comodato:0,
                    numero_serie : '',
                    numero_motor : '',
                    empresa : 'Grupo Constructor Cumbres',
                    nombre:'',
                }
            }
        },
        computed:{

        },
        methods : {
            /**Metodo para mostrar los registros */
            listarVehiculos(page){
                let me = this;
                var url = '/vehiculos/index?page=' + page+'&b_vehiculo='
                    +this.buscar + '&b_empresa='+this.b_empresa + '&b_marca='+this.b_marca
                    + '&b_comodato='+this.b_comodato;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayVehiculos = respuesta;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getMarcas(){
                let me = this;
                me.marcas=[];
                var url = '/vehiculos/getMarcas';
                axios.get(url).then(function (response) {
                    var respuesta = response;
                    me.marcasAutos = respuesta.data.marcas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            limpiarVehiculo(){
                this.data.vehiculo = '';
                this.data.modelo = '';
                this.data.marca = '';
                this.data.clave = '';
                this.data.placas = '';
                this.data.numero_serie = '';
                this.data.numero_motor = '';
                this.data.responsable_id = '';
                this.data.comodato = 0;
                this.data.empresa = 'Grupo Constructor Cumbres';
            },
            cerrarModal(){
                this.modal = 0;
                this.limpiarVehiculo()
                this.listarVehiculos(1)
            },

            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(accion,data =[]){
                switch(accion){
                    case 'registrar':
                    {
                        this.limpiarVehiculo()
                        this.modal = 1;
                        this.tituloModal = 'Registrar Vehiculo';
                        this.tipoAccion = 1;
                        this.vista = 1;
                        break;
                    }
                    case 'actualizar':
                    {
                        //console.log(data);
                        this.modal =1;
                        this.tituloModal='Actualizar Vehiculo';
                        this.data = data;
                        this.vista = 2;
                        this.tipoAccion=2;
                        break;
                    }
                }
            }
        },
        mounted() {
            this.listarVehiculos(1);
            this.getMarcas();
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
