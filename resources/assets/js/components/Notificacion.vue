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
                    <i class="fa fa-align-justify"></i> Notificaciones
                    <!--   Boton Nuevo    -->
                    <Button icon="icon-plus" btnClass="btn-success" @click="abrirModal('crear')">Nuevo</Button>
                    <!---->
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-8">
                            <div class="input-group">
                                <!--Criterios para el listado de busqueda -->
                                <p type="text" class="form-control label" style="" disabled>Fecha de creación</p>
                                <input @keyup.enter="getNotificaciones(1)" type="date" class="form-control" v-model="fecha_inicio"  >
                                <input @keyup.enter="getNotificaciones(1)" type="date" class="form-control" v-model="fecha_fin" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input id="nombre" @keyup.enter="getNotificaciones(1)" type="text" v-model="b_nombre" class="form-control" placeholder="Nombre">
                                <Button icon="fa fa-search" @click="getNotificaciones(1)">Buscar</Button>
                            </div>
                        </div>
                    </div>
                    <TableComponent
                        :cabecera="['','Nombre','Mensaje','Recurrencia','Fin de Periodo','Fecha de alta']"
                    >
                        <template v-slot:tbody>
                            <tr v-for="notificacion in arrayNotificacion" :key="notificacion.id">
                                <td class="td2">
                                    <button type="button" @click="abrirModal('editar',notificacion)" class="btn btn-warning btn-sm" title="Fecha de finalización">
                                        <i class="icon-pencil"></i>
                                    </button> &nbsp;
                                </td>
                                <td class="td2" v-text="notificacion.nombre+' '+notificacion.apellidos" ></td>
                                <td v-text="notificacion.mensaje"></td>
                                <td class="td2" v-if="notificacion.periodo == 1">Diario</td>
                                <td class="td2" v-if="notificacion.periodo == 7">Semanal</td>
                                <td class="td2" v-if="notificacion.periodo == 30">Mensual</td>
                                <td class="td2" v-text="notificacion.finPeriodo"></td>
                                <td class="td2" v-text="notificacion.created_at"></td>
                            </tr>
                        </template>
                    </TableComponent>
                    <nav>
                        <!--Botones de paginacion -->
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1)">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1)">Sig</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>

        <!--Inicio del modal Crear mensaje-->
        <ModalComponent @closeModal="cerrarModal()"
            v-if="modal"
            :titulo="tituloModal"
        >
            <template v-slot:body>
                <RowModal clsRow1="col-md-6" label1="Mensaje" id1="mensaje">
                    <textarea id="mensaje" rows="10" cols="30" v-model="mensaje" class="form-control" placeholder="Mensaje"></textarea>
                </RowModal>
                <RowModal clsRow1="col-md-6" label1="Recurrencia" id1="recurrencia">
                    <select id="recurrencia" class="form-control" v-model="recurrencia">
                        <option value="0">Unica ocasión</option>
                        <option value="1">Diario </option>
                        <option value="7">Semanal</option>
                        <option value="30">Mensual</option>
                    </select>
                </RowModal>
                <RowModal clsRow1="col-md-6" label1="Fin de periodo" v-if="recurrencia != 0">
                    <input type="date" v-model="finPeriodo" class="form-control">
                </RowModal>
                <RowModal clsRow1="col-md-6" label1="Rol" v-if="tipoAccion == 1">
                    <select class="form-control" v-model="rol_id" @click="getUser(rol_id)">
                        <option value="">Seleccione</option>
                        <option v-for="rol in arrayRol" :key="rol.id" :value="rol.id" v-text="rol.nombre"></option>
                    </select>
                </RowModal>
                <RowModal clsRow1="col-md-6" label1="Nombre" v-if="tipoAccion == 1">
                    <select class="form-control" name = 'arrayPer[]' multiple size = 7 v-model="arrayPer">
                        <option v-for="persona in arrayPersonal" :key="persona.id" :value="persona" v-text="persona.nombre + ' ' + persona.apellidos"></option>
                    </select>
                </RowModal>
                <RowModal clsRow1="col-md-12" :label1="''" v-if="tipoAccion == 1">
                    <div class="table-responsive">
                        <table class="table" >
                            <thead>
                                <tr>
                                    <th style="text-align:center">Seleccionados</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="array in arrayPer" :key="array.id">
                                    <td v-if="array.nombre !='' && array.apellidos !=''" v-text="array.nombre + ' ' + array.apellidos"></td>
                                    <td v-else v-text="array.nombre" ></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </RowModal>
                <div v-show="error" class="form-group row div-error">
                    <div class="text-center text-error">
                        <div v-for="error in errorMostrarMsj" :key="error" v-text="error"></div>
                    </div>
                </div>
            </template>
            <template v-slot:buttons-footer>
                <Button v-if="tipoAccion == 1" icon="icon-check" @click="storeNotificacion()">Guardar</Button>
                <Button v-if="tipoAccion == 2" icon="icon-check" @click="updateNotificacion()">Guardar cambios</Button>
            </template>
        </ModalComponent>
        <!--Fin del modal-->
    </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
import ModalComponent from './Componentes/ModalComponent'
import RowModal from './Componentes/ComponentesModal/RowModalComponent'
import LoadingComponent from './Componentes/LoadingComponent'
import TableComponent from './Componentes/TableComponent'
import Button from './Componentes/ButtonComponent'

    export default {
        components:{
            LoadingComponent,
            TableComponent,
            ModalComponent,
            RowModal,
            Button
        },
        data(){
            return{
                tituloModal : '',
                mensaje :'',
                rol_id :'',
                periodo : 0,
                fecha_creado : '',
                finPeriodo : '',
                recurrencia :0,

                nombre : '',
                arrayPer : [],

                arrayRol: [],
                arrayPersonal : [],
                arrayNotificacion : [],

                modal : 0,
                mostrar : 0,
                error : 0,
                tipoAccion : 1,

                errorMostrarMsj: [],

                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },

                offset : 3,
                criterio : 'nombre',
                b_nombre :'',
                fecha_inicio : '',
                fecha_fin : '',
                id : ''
            }
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber:function(){
                if(!this.pagination.to){
                    return [];
                }

                var from = this.pagination.current_page - this.offset;
                if(from < 1){
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to){
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            },

        },
        methods : {
            /**Metodo para mostrar los registros */
            cambiarPagina(page){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.getNotificaciones(page);
            },

            selectRol(){
                let me = this;

                me.arrayRol=[];
                var url = '/notification/getRol';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayRol = respuesta.rol;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            getUser(id_rol){
                let me = this;

                me.arrayPersonal=[];
                var url = '/notification/getUser?id='+id_rol;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPersonal = respuesta.personal;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            getNotificaciones(page){
                let me = this;

                me.arrayNotificacion=[];
                var url = '/notificacion/indexAvisos?page=' + page+'&nombre='+me.b_nombre+
                        '&fecha_inicio='+me.fecha_inicio+'&fecha_fin='+me.fecha_fin;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayNotificacion = respuesta.aviso.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            storeNotificacion(){
                let me = this;
                if(this.validar()) //Se verifica si hay un error (campo vacio)
                    return;

               Swal({
                    title: 'Estas seguro?',
                    animation: false,
                    customClass: 'animated bounceInDown',
                    text: "Se programaran las notificaciones para los usuarios seleccionados",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',

                    confirmButtonText: 'Si, enviar notificación!'
                    }).then((result) => {

                    if (result.value) {
                        me.arrayPer.forEach(element => {
                            axios.post('/notificacion/storeAviso',{
                                'user_id': element.id,
                                'periodo' : this.recurrencia,
                                'mensaje' : this.mensaje,
                                'finPeriodo' : this.finPeriodo
                            });
                        });
                        me.cerrarModal();
                        me.getNotificaciones(1);
                        Swal({
                            title: 'Hecho!',
                            text: 'Las notificaciones se han enviado',
                            type: 'success',
                            animation: false,
                            customClass: 'animated bounceInRight'
                        })
                    }})
            },

            updateNotificacion(){
                let me = this;
                //Con axios se llama el metodo update de PersonalController
                axios.put('/notificacion/updateAviso',{
                    'id' : this.id,
                    'periodo' : this.recurrencia,
                    'mensaje' : this.mensaje,
                    'finPeriodo' : this.finPeriodo
                }).then(function (response){
                    me.cerrarModal();
                    me.getNotificaciones(1);
                    Swal({
                        title: 'Hecho!',
                        text: 'La notificación se actualizo',
                        type: 'success',
                        animation: false,
                        customClass: 'animated bounceInRight'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            validar(){
                this.error=0;
                this.errorMostrarMsj=[];

                if(this.mensaje == '') //Si la variable  esta vacia
                    this.errorMostrarMsj.push("Mensaje vacio");
                if(this.arrayPer.length == 0)
                    this.errorMostrarMsj.push("Ningun usuario seleccionado");

                if(this.errorMostrarMsj.length)//Si el mensaje tiene almacenado algo en el array
                    this.error = 1;

                return this.error;
            },

            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.rol_id = '';
                this.etapa_id = '';
                this.nombre = '';
                this.arrayPer = [];
                this.recurrencia = 0;
                this.arrayPersonal = [];

                this.error = 0;
                this.errorMostrarMsj = [];
                this.id = '';
            },

            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(accion,data=[]){
                switch(accion){
                    case 'crear':
                    {
                        this.modal = 1;
                        this.tituloModal = 'Crear mensaje';
                        this.mensaje ='';
                        this.rol_id ='';
                        this.recurrencia = 0;
                        this.fecha_creado = '';
                        this.tipoAccion = 1;
                        this.finPeriodo = '';

                        break;
                    }
                    case 'editar':{
                        this.modal = 1;
                        this.tituloModal = 'Editar mensaje';
                        this.mensaje =data['mensaje'];
                        this.recurrencia = data['periodo'];
                        this.tipoAccion = 2;
                        this.finPeriodo = data['finPeriodo'];
                        this.id = data['id'];

                        break;
                    }
                }
            },
        },
        mounted() {
            this.selectRol();
            this.getNotificaciones();
        }
    }
</script>
<style>
    .div-error{
        display:flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
    .label{
        background-color: #c2cfd6;
    }

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
