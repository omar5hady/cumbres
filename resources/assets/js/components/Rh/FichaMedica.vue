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
                    <i class="fa fa-align-justify"></i> Ficha Tecnica
                    <div class="button-header">
                        <button v-if="busqueda.user_id"
                        type="button" class="btn btn-success btn-sm"
                            @click="nuevoMovimiento()"
                        >
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                        <!---->
                    </div>
                </div>

                <div class="info-center" v-if="loading">
                    <LoadingComponentVue></LoadingComponentVue>
                </div>


                <div class="card-body" v-else>

                    <div class="form-group row">
                        <div class="col-md-6">
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <input v-if="vista==2" disabled type="text" v-model="busqueda.nombre" class="form-control col-md-6">
                                <button v-if="vista == 2" class="form-control btn btn-sm btn-primary col-md-3" @click="vista = 1, busqueda.user_id = ''">Cambiar</button>
                                <input v-if="vista==1" type="text" name="user" list="usersName" @keyup="selectUsuario(busqueda.user_id)" @change="getNombre(busqueda.user_id)"  class="form-control col-md-8" v-model="busqueda.user_id">
                                <datalist v-if="vista==1" id="usersName">
                                    <option value="">Seleccione</option>
                                    <option v-for="users in arrayUsers" :key="users.id" :value="users.id" v-text="users.nombre + ' '+ users.apellidos"></option>
                                </datalist>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                        </div>
                    </div>

                    <div class="info-center" style="margin-top:50px; margin-bottom:50px;" v-if="mostrar == 1">
                        <center><h4>Aun no se ha cargado información para este usuario</h4></center>
                    </div>
                    <div class="row" v-else>
                        <div class="col-md-6">
                            <div class="card card-user">
                                <!---->
                                <div class="card-body">
                                    <div class="author">
                                        <img
                                            :src="`/img/avatars/default-image.gif`"
                                            class="avatar border-white"
                                        />
                                        <h4 class="title">
                                            <font style="vertical-align: inherit;">
                                                <font style="vertical-align: inherit;"
                                                >
                                                    Omar Ramos
                                                </font>
                                            </font>
                                        </h4>
                                    </div>
                                </div>
                                <div class="card-body row">
                                    <div class="col-md-12">
                                        <h5 style="vertical-align: inherit;">
                                            Grupo Sanguineo: <strong>O+</strong>
                                        </h5>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 style="vertical-align: inherit;">
                                            Peso: <strong>67 kg</strong>
                                        </h6>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 style="vertical-align: inherit;">
                                            Talla: <strong>1.67 m</strong>
                                        </h6>
                                    </div>
                                    <div class="col-md-12">
                                        <h6 style="vertical-align: inherit;">
                                            IMC: <strong>1.67 m</strong>
                                        </h6>
                                    </div>
                                    <div class="col-md-12">
                                        <h6 style="vertical-align: inherit;">
                                            Regimen de alimentación: <strong>Dieta baja en sodio</strong>
                                        </h6>
                                    </div>
                                </div>
                                <!---->
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        <font style="vertical-align: inherit;">
                                            <font style="vertical-align: inherit;"
                                                >Afilaciones a servicios de salud </font
                                            >
                                        </font>
                                    </h4>
                                    <!---->
                                </div>
                                <div class="card-body" style="padding-bottom: 0px;">
                                    <TableVue :cabecera="[
                                        'Proveedor', 'No. Poliza', 'Tipo'
                                    ]">
                                        <template v-slot:tbody>
                                            <tr>
                                                <td class="td2">MetLife</td>
                                                <td class="td2">018562245</td>
                                                <td class="td2">Particular</td>
                                            </tr>
                                        </template>
                                    </TableVue>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-lg-12">
                            <div class="card">
                                <div class="card-header" >
                                    <h4 class="card-title">
                                        <font style="vertical-align: inherit;">
                                            <font style="vertical-align: inherit;"
                                                >Alergias </font
                                            >
                                        </font>
                                    </h4>
                                    <!---->
                                </div>
                                <div class="card-body" style="padding-bottom: 0px;">
                                    <h6>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Officia voluptates maxime quae praesentium corporis quo dicta excepturi, ullam maiores minus earum vero? Voluptas itaque esse quos suscipit cumque. Magnam, consequuntur!</h6>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        <font style="vertical-align: inherit;">
                                            <font style="vertical-align: inherit;"
                                                >Tratamientos actuales </font
                                            >
                                        </font>
                                    </h4>
                                    <!---->
                                </div>
                                <div class="card-body" style="padding-bottom: 0px;">
                                    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Officia voluptates maxime quae praesentium corporis quo dicta excepturi, ullam maiores minus earum vero? Voluptas itaque esse quos suscipit cumque. Magnam, consequuntur!</p>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Formularios para historial clinico -->
                    <div id="accordion" role="tablist">
                        <!-- Historial Medico -->
                        <div class="card mb-0">
                            <div class="card-header" id="headingOne" role="tab">
                                <h5 class="mb-0">
                                    <a data-toggle="collapse" href="#collapseOne" aria-expanded="false"
                                        aria-controls="collapseOne" class="collapsed"
                                        >Historial Médico
                                    </a>
                                </h5>
                            </div>
                            <div class="collapse" id="collapseOne" role="tabpanel"
                                aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="form-group row border border-primary" style="margin-right:0px; margin-left:0px;">
                                    <div class="col-md-12">
                                        <div class="form-group"><center><h3></h3></center></div>
                                    </div>
                                    <CardListGroupVue :titulo="'Antecendentes'"
                                        :data="[
                                            {antecedente : 'Diabetes', especificacion: 'Especificacion', estado: false},
                                            {antecedente : 'Hipertensión', especificacion: 'Especificacion', estado: false},
                                            {antecedente : 'Evento epiléptico', especificacion: 'Especificacion', estado: true},
                                            {antecedente : 'Problema cardíaco', especificacion: 'Especificacion', estado: false},
                                            {antecedente : 'Desmayos y/o Mareos', especificacion: 'Especificacion', estado: false},
                                            {antecedente : 'Asma', especificacion: 'Especificacion', estado: true},
                                            {antecedente : 'Toxicomanías', especificacion: 'Especificacion', estado: false},
                                            {antecedente : 'Cirugía reciente', especificacion: 'Especificacion', estado: false},
                                            {antecedente : 'Embarazo y/o Puerperio', especificacion: 'Especificacion', estado: false},
                                            {antecedente : 'Transfusión', especificacion: 'Especificacion', estado: false},
                                            {antecedente : 'Lesión Músculo Esquelética', especificacion: 'Especificacion', estado: false},
                                            {antecedente : 'Ortopédicos', especificacion: 'Especificacion', estado: false},
                                        ]"
                                        :data2="[
                                            {antecedente : 'Respiratorios', especificacion: 'Especificacion', estado: false},
                                            {antecedente : 'Oftálmicos', especificacion: 'Especificacion', estado: false},
                                            {antecedente : 'Naríz y/u Oídos', especificacion: 'Especificacion', estado: true},
                                            {antecedente : 'Neurológicos', especificacion: 'Especificacion', estado: false},
                                            {antecedente : 'Hmetológicos', especificacion: 'Especificacion', estado: false},
                                            {antecedente : 'Hepáticos', especificacion: 'Especificacion', estado: true},
                                            {antecedente : 'Aparato Digestivo', especificacion: 'Especificacion', estado: false},
                                            {antecedente : 'Tiroideo', especificacion: 'Especificacion', estado: false},
                                            {antecedente : 'Dermatológico', especificacion: 'Especificacion', estado: false},
                                            {antecedente : 'Inmunológico', especificacion: 'Especificacion', estado: false},
                                            {antecedente : 'Urinarios', especificacion: 'Especificacion', estado: false},
                                            {antecedente : 'Covid-19', especificacion: 'Especificacion', estado: false},
                                        ]"
                                    ></CardListGroupVue>
                                    <CardListGroupVue :titulo="'Psicologicos/Psquiátricos'"
                                        :data="[
                                            {antecedente : 'Cambios en alimentación', especificacion: 'Especificacion', estado: false},
                                            {antecedente : 'Aislamiento personal', especificacion: 'Especificacion', estado: false},
                                            {antecedente : 'Sensación de vacío o sin importancia', especificacion: 'Especificacion', estado: true},
                                            {antecedente : 'Impotencia o desesperanza', especificacion: 'Especificacion', estado: false},
                                            {antecedente : 'Confusión, Distracción o Irritabilidad', especificacion: 'Especificacion', estado: false},
                                            {antecedente : 'Pensamientos y/o Recuerdos que no salgan de su cabeza', especificacion: 'Especificacion', estado: true},
                                            {antecedente : 'Pensar Lastimarse a Sí Mismo u Otros', especificacion: 'Especificacion', estado: false},
                                        ]"
                                        :data2="[
                                            {antecedente : 'Alteraciones del Sueño', especificacion: 'Especificacion', estado: false},
                                            {antecedente : 'Agotamiento Excesivo', especificacion: 'Especificacion', estado: false},
                                            {antecedente : 'Dolores Inexplicables', especificacion: 'Especificacion', estado: false},
                                            {antecedente : 'Aumento en Toxicomanias', especificacion: 'Especificacion', estado: false},
                                            {antecedente : 'Cambios de humor', especificacion: 'Especificacion', estado: false},
                                            {antecedente : 'Escuchar voces o creer cosas que no son ciertas', especificacion: 'Especificacion', estado: false},
                                            {antecedente : 'Dificultad para realizar tareas diarias', especificacion: 'Especificacion', estado: false},
                                        ]"
                                    ></CardListGroupVue>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>

        <!--Inicio del modal nuevo registro-->
        <ModalComponent v-if="modal.mostrar"
            :titulo="modal.titulo"
            @closeModal="closeModal()"
        >
            <template v-slot:body>
                <div class="card-body">
                    <ul class="nav nav-tabs">
                        <li class="nav-item"><a class="nav-link"  v-bind:class="{ 'active': paso==1 }" @click="paso = 1">Datos Médicos Generales</a></li>
                        <li class="nav-item"><a class="nav-link"  v-bind:class="{ 'active': paso==2 }" @click="paso = 2">Datos personales</a></li>
                        <li class="nav-item"><a class="nav-link"  v-bind:class="{ 'active': paso==3 }" @click="paso = 3">Datos importantes</a></li>
                    </ul>
                    <template v-if="paso == 1"> <!--Datos Generales -->
                        <RowModal :label1="'Grupo y R.H.'">
                            <select class="form-control" v-model="medicalRecord.tipo_sangre">
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                            </select>
                        </RowModal>
                        <RowModal :label1="'Peso'" :clsRow1="'col-md-4'" :clsRow2="'col-md-4'">
                            <select class="form-control" v-model="medicalRecord.tipo_sangre">
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                            </select>
                        </RowModal>
                        <RowModal :label1="'Grupo y R.H.'">
                            <select class="form-control" v-model="medicalRecord.tipo_sangre">
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                            </select>
                        </RowModal>
                    </template>
                </div>

            </template>

            <template v-slot:buttons-footer>
                <button v-if="modal.accion == 'nuevo'" type="button" class="btn btn-success" @click="save()">Guardar</button>
            </template>

        </ModalComponent>
    </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
import LoadingComponentVue from '../Componentes/LoadingComponent.vue'
import ModalComponent from '../Componentes/ModalComponent.vue'
import TableVue from '../Componentes/TableComponent.vue'
import Nav from '../Componentes/NavComponent.vue'
import CardListGroupVue from './components/CardListGroup.vue'
import RowModal from '../Componentes/ComponentesModal/RowModalComponent.vue'

export default {
    components:{
        LoadingComponentVue,
        ModalComponent,
        TableVue,
        Nav,
        CardListGroupVue,
        RowModal
    },
    props: {
        adminMant: { type: String },
        userId: { type: String }
    },
    data() {
        return {
            busqueda: {
                user_id: this.userId,
                nombre:''
            },
            histMedico: {},
            medicalRecord:{},
            arrayUsers: [],
            loading: false,
            vista:2,
            mostrar: 0,
            paso: 1,
            modal:{
                mostrar: 0,
                titulo:'',
                accion:'nuevo'
            }
        };
    },
    computed: {},
    methods: {

        selectUsuario(buscar){
            let me = this;

            me.arrayUsers=[];
            const url = '/usuarios/selectUser?buscar=' + buscar;
            axios.get(url).then(function (response) {
                const respuesta = response.data;
                me.arrayUsers = respuesta.data;
            })
            .catch(function (error) {
                console.log(error);
            });
        },

        nuevoMovimiento(){
            this.modal.mostrar = 1;
            this.modal.titulo = `Nuevo registro para: ${this.busqueda.nombre}`;
            this.modal.accion = 'nuevo'
            this.paso = 1;
            this.medicalRecord = {
                user_id : this.busqueda.user_id,
                alerta : '',
                tipo_sangre : '',
                estatura : 0,
                alergias : '',
                regimen_alimenticio : null,
                ...this.medicalRecord
            }
            this.histMedico = {}

        },

        closeModal(){
            this.modal.mostrar = 0
            this.modal.titulo = ''
            this.medicalRecord = {}
            this.histMedico = {}
        },
        getNombre(id){
            console.log(id)
            let me = this;
            const url = '/usuarios/getNombre?id=' + id;
            axios.get(url).then(function (response) {
                const respuesta = response.data;
                me.busqueda.nombre = respuesta.nombre + ' ' +respuesta.apellidos;
                me.vista = 2
            })
            .catch(function (error) {
                console.log(error);
            });
        },

    },
    created() {
        this.getNombre(this.userId);
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
    width: 120px;
    height: 120px;
    overflow: hidden;
    border-radius: 80%;
    margin-right: 5px;
}
.card-user .author {
    text-align: center;
    text-transform: none;
    margin-top: -65px;
}
.info-center{
    display: flex;
    justify-content: center;
    width: 100% !important;
}
.button-header{
    display: flex;
    justify-content: flex-end;
    margin-top: -20px;
}
</style>
