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
                        <i class="fa fa-align-justify"></i> Usuarios
                        <!--   Boton Nuevo    -->
                        <button type="button" @click="abrirModal('registrar')" class="btn btn-secondary" v-if="privilegios==0">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                        <button type="button" @click="abrirModal('Asignar')" class="btn btn-warning" v-if="privilegios==0">
                            <i class="fa fa-user-plus"></i>&nbsp;Asignar rol
                        </button>
                        <button type="button" @click="abrirModal('Importar')" class="btn btn-dark" v-if="privilegios==0">
                            <i class="fa fa-user-plus"></i>&nbsp;Importar contactos
                        </button>
                        <button type="button" @click="cerrarPrivilegios()" class="btn btn-success" v-if="privilegios==1">
                            <i class="fa fa-mail-reply"></i>&nbsp;Regresar
                        </button>
                        <!---->
                    </div>
                    <template v-if="privilegios==0">
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <!--Criterios para el listado de busqueda -->
                                        <select class="form-control col-md-5" @change="selectDepartamento(),limpiarBusqueda()"  v-model="criterio">
                                            <option value="personal.nombre">Nombre</option>
                                            <option value="users.usuario">Usuario</option>
                                            <option value="roles.nombre">Rol</option>
                                        </select>


                                        <select class="form-control" v-if="criterio == 'roles.nombre'" v-model="buscar">
                                            <option value="">Seleccione</option>
                                            <option v-for="rol in arrayRoles" :key="rol.id" :value="rol.nombre" v-text="rol.nombre"></option>
                                        </select>
                                        <input type="text" v-else v-model="buscar" @keyup.enter="listarPersonal(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarPersonal(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <TableComponent :cabecera="['Opciones','Nombre','Usuario','Rol','Status']">
                                <template v-slot:tbody>
                                    <tr v-for="Personal in arrayPersonal" :key="Personal.id" title="Ver privilegios">
                                        <td class="td2" width="25%">
                                            <button type="button" @click="abrirModal('actualizar',Personal)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                            </button>
                                            <template v-if="Personal.condicion">
                                                <button type="button" @click="desactivarPersonal(Personal.id)" class="btn btn-danger btn-sm">
                                                <i class="fa fa-user-times"></i>
                                                </button>
                                            </template>
                                            <template v-else>
                                                <button type="button" @click="activarPersonal(Personal.id)" class="btn btn-success btn-sm">
                                                    <i class="icon-check"></i>
                                                </button>
                                            </template>
                                                <button v-if="Personal.rol_id == 2" title="Asignar asesor a un gerente" type="button" @click="abrirModal('asignarGerente',Personal)" class="btn btn-dark btn-sm">
                                            <i class="icon-share"></i>
                                            </button>

                                        </td>
                                        <td class="td2" width="60%">
                                            <a @click="abrirPrivilegios(Personal.id)" href="#" v-text="Personal.nombre + ' ' + Personal.apellidos"></a>
                                        </td>

                                        <td class="td2" v-text="Personal.usuario"></td>
                                        <td class="td2" v-text="Personal.rol"></td>
                                        <td class="td2">
                                            <span v-if = "Personal.condicion==1" class="badge badge-success">Activo</span>
                                            <span v-if = "Personal.condicion==0" class="badge badge-danger">Inactivo</span>
                                        </td>
                                    </tr>
                                </template>
                            </TableComponent>
                            <nav>
                                <!--Botones de paginacion -->
                                <ul class="pagination">
                                    <li class="page-item" v-if="pagination.current_page > 1">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                    </li>
                                    <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                    </li>
                                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </template>

                    <template v-if="privilegios==1">
                        <PrivilegiosComponent
                            :userId="id"
                            @close="cerrarPrivilegios()"
                        ></PrivilegiosComponent>
                    </template>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <ModalComponent v-if="modal == 1"
                :titulo="tituloModal"
                @closeModal="cerrarModal()"
            >
                <template v-slot:body>
                    <div class="modal-body">
                        <!--Criterios para el listado de busqueda -->
                        <div class="form-group row" v-if="tipoAccion > 1 && tipoAccion < 4">
                            <label class="col-md-3 form-control-label" for="text-input">Apellidos</label>
                            <div class="col-md-9">
                                <input type="text" v-model="apellidos" class="form-control" placeholder="Apellidos" >
                            </div>
                        </div>
                            <div class="form-group row" v-if="tipoAccion > 1 && tipoAccion < 4">
                            <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                            <div class="col-md-9">
                                <input type="text" maxlength="25" v-model="nombre" class="form-control" placeholder="Nombre" >
                            </div>
                        </div>
                        <div class="form-group row" v-if="tipoAccion > 1 && tipoAccion < 4">
                            <label class="col-md-3 form-control-label" for="text-input">Fecha de nacimiento</label>
                            <div class="col-md-6">
                                <input type="date" v-model="f_nacimiento" class="form-control" placeholder="Fecha de nacimiento" >
                            </div>
                        </div>
                                <div class="form-group row" v-if="tipoAccion > 1 && tipoAccion < 4">
                            <label class="col-md-3 form-control-label" for="text-input">RFC</label>
                            <div class="col-md-4">
                                <input type="text" maxlength="10" style="text-transform:uppercase" v-model="rfc" class="form-control" placeholder="RFC" >
                            </div>
                            <div class="col-md-4" >
                                <input type="text" maxlength="3" style="text-transform:uppercase" v-model="homoclave" class="form-control" placeholder="Homoclave" >
                            </div>
                        </div>

                        <div class="form-group row" v-if="tipoAccion > 1 && tipoAccion < 4">
                            <label class="col-md-3 form-control-label" for="text-input">Codigo Postal</label>
                            <div class="col-md-4">
                                <input type="text" pattern="\d*" maxlength="5" v-model="cp" v-on:keypress="$root.isNumber($event)" @keyup="selectColonias(cp)" class="form-control" placeholder="Codigo postal" >
                            </div>
                        </div>

                        <div class="form-group row" v-if="tipoAccion > 1 && tipoAccion < 4">
                            <label class="col-md-3 form-control-label" for="text-input">Colonia</label>
                            <div class="col-md-6">
                                <select class="form-control" v-model="colonia" >
                                    <option value="0">Seleccione</option>
                                    <option v-for="colonias in arrayColonias" :key="colonias.colonia" :value="colonias.colonia" v-text="colonias.colonia"></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row" v-if="tipoAccion > 1 && tipoAccion < 4">
                            <label class="col-md-3 form-control-label" for="text-input">Direccion</label>
                            <div class="col-md-9">
                                <input type="text" v-model="direccion" class="form-control" placeholder="Direccion" >
                            </div>
                        </div>

                        <div class="form-group row" v-if="tipoAccion > 1 && tipoAccion < 4">
                            <label class="col-md-3 form-control-label" for="text-input">Departamento</label>
                            <div class="col-md-6">
                                    <select class="form-control" v-model="departamento_id" >
                                    <option value="0">Seleccione</option>
                                    <option v-for="departamentos in arrayDepartamentos" :key="departamentos.id_departamento" :value="departamentos.id_departamento" v-text="departamentos.departamento"></option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row" v-if="tipoAccion > 1 && tipoAccion < 4">
                            <label class="col-md-3 form-control-label" for="text-input">Telefono</label>
                            <div class="col-md-5">
                                <input type="text" pattern="\d*" maxlength="10" v-on:keypress="$root.isNumber($event)" class="form-control" v-model="telefono"  placeholder="Telefono" >
                            </div>
                        </div>

                        <div class="form-group row" v-if="tipoAccion > 1 && tipoAccion < 4">
                            <label class="col-md-3 form-control-label" for="text-input">Extension</label>
                            <div class="col-md-3">
                                <input type="text" pattern="\d*" maxlength="5" v-on:keypress="$root.isNumber($event)" v-model="ext" class="form-control" placeholder="Extension" >
                            </div>
                        </div>

                        <div class="form-group row" v-if="tipoAccion > 1 && tipoAccion < 4">
                            <label class="col-md-3 form-control-label" for="text-input">Celular</label>
                            <div class="col-md-5">
                                <input type="text" pattern="\d*" maxlength="10" v-on:keypress="$root.isNumber($event)" v-model="celular" class="form-control" placeholder="Celular" >
                            </div>
                        </div>

                        <div class="form-group row" v-if="tipoAccion > 1 && tipoAccion < 4">
                            <label class="col-md-3 form-control-label" for="text-input">Correo electronico</label>
                            <div class="col-md-9">
                                <input type="text" v-model="email" class="form-control" placeholder="Correo electronico" >
                            </div>
                        </div>

                        <div class="form-group row" v-if="tipoAccion == 1">
                            <label class="col-md-3 form-control-label" for="text-input">Personal</label>
                            <div class="col-md-6">
                                    <select class="form-control" v-model="id_persona">
                                    <option value="0">Seleccione</option>
                                    <option v-for="personal in arrayPersonas" :key="personal.personalId" :value="personal.personalId" v-text="personal.nombre"></option>
                                </select>
                            </div>
                        </div>


                        <div class="form-group row" v-if="tipoAccion != 4">
                            <label class="col-md-3 form-control-label" for="text-input">Usuario</label>
                            <div class="col-md-9">
                                <input type="text" v-model="usuario" class="form-control" placeholder="Usuario" >
                            </div>
                        </div>


                        <div class="form-group row" v-if="tipoAccion != 4">
                            <label class="col-md-3 form-control-label" for="text-input">Contraseña</label>
                            <div class="col-md-9">
                                <input type="password" v-model="password" autocomplete="off" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row" v-if="tipoAccion != 4">
                            <label class="col-md-3 form-control-label" for="text-input">Rol</label>
                            <div class="col-md-6">
                                <select class="form-control" v-model="rol_id" >
                                    <option value="0">Seleccione</option>
                                    <option v-for="roles in arrayRoles" :key="roles.id" :value="roles.id" v-text="roles.nombre"></option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row" v-if="rol_id==2 && tipoAccion != 4">
                            <label class="col-md-3 form-control-label" for="text-input">Tipo</label>
                            <div class="col-md-6">
                                <select class="form-control" v-model="tipo_vendedor" >
                                    <option value="0">Interno</option>
                                    <option value="1">Externo</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row" v-if="tipo_vendedor==1 && rol_id==2 && tipoAccion != 4">
                            <label class="col-md-3 form-control-label" for="text-input">Inmobiliaria</label>
                            <div class="col-md-9">
                                <!-- <input type="text" v-model="inmobiliaria" class="form-control" placeholder="Inmobiliaria" > -->
                                <select class="form-control" v-model="inmobiliaria" >
                                    <option value=''>Seleccione</option>
                                    <option v-for="i in arrayInmobiliarias" :key="i.id" :value='i.nombre'>{{ i.nombre }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row" v-if="tipo_vendedor==1 && rol_id==2 && tipoAccion != 4">
                            <label class="col-md-3 form-control-label" for="text-input">Esquema</label>
                            <div class="col-md-2">
                                <select class="form-control" v-model="esquema" >
                                    <option value=2>2%</option>
                                    <option value=3>3%</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row" v-if="tipo_vendedor==1 && rol_id==2 && tipoAccion != 4">
                            <label class="col-md-3 form-control-label" for="text-input">Retencion</label>
                            <div class="col-md-2">
                                <select class="form-control" v-model="retencion" >
                                    <option value=0>No</option>
                                    <option value=1>Si</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row" v-if="tipo_vendedor==1 && rol_id==2 && tipoAccion != 4">
                            <label class="col-md-3 form-control-label" for="text-input">ISR</label>
                            <div class="col-md-2">
                                <select class="form-control" v-model="isr" >
                                    <option value=0>No</option>
                                    <option value=1>Si</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row" v-if="tipoAccion > 1 && tipoAccion < 4">
                            <label class="col-md-3 form-control-label" for="text-input">Estatus</label>
                            <div class="col-md-5">
                                <select class="form-control" v-model="activo" >
                                    <option value="1">Activo</option>
                                    <option value="0"> Inactivo</option>
                                </select>
                                <!--<input type="text" v-model="estado" class="form-control" placeholder="Estado">-->
                            </div>
                        </div>

                            <div v-if="tipoAccion == 4" class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Gerentes</label>
                            <div class="col-md-6">
                                <select class="form-control" v-model="supervisor_id" >
                                    <option value="0">Seleccione</option>
                                    <option v-for="gerentes in arrayGerentes" :key="gerentes.id" :value="gerentes.id" v-text="gerentes.nombre+' '+gerentes.apellidos"></option>
                                </select>
                            </div>
                        </div>
                        <!-- Div para mostrar los errores que mande validerPersonal -->
                        <div v-show="errorPersonal" class="form-group row div-error">
                            <div class="text-center text-error">
                                <div v-for="error in errorMostrarMsjPersonal" :key="error" v-text="error">

                                </div>
                            </div>
                        </div>

                    </div>
                </template>
                <template v-slot:buttons-footer>
                    <button type="button" v-if="tipoAccion==3" class="btn btn-primary" @click="registrarPersonal()">Guardar</button>
                    <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarPersonal()">Actualizar</button>
                    <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="asignarUsuario()">Asignar</button>
                    <button type="button" v-if="tipoAccion==4" class="btn btn-primary" @click="asignarGerente()">Asignar</button>
                </template>
            </ModalComponent>
            <!--Fin del modal-->

            <!-- Modal para el archivo excel -->
            <ModalComponent :titulo="tituloModal"
                @closeModal="cerrarModal()"
                v-if="modal == 2"
            >
                <template v-slot:body>
                    <template>
                        <form method="post" @submit="formSubmit"  enctype="multipart/form-data">

                            <!-- {{ csrf_field() }} -->
                            Selecciona archivo excel xls/csv: <input type="file" v-on:change="onImageChange" class="form-control">

                            <input type="submit" value="Importar Usuarios" class="btn btn-primary btn-lg" style="margin-top: 3%">
                        </form>
                    </template>
                </template>
            </ModalComponent>
            <!--Fin del modal-->

        </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
import ModalComponent from './Componentes/ModalComponent.vue';
import TableComponent from './Componentes/TableComponent.vue';
import PrivilegiosComponent from './PrivilegiosComponent.vue';
    export default {
        components:{
            ModalComponent,
            TableComponent,
            PrivilegiosComponent
        },
        data(){
            return{
                file:'',
                privilegios:0,
                proceso:false,
                id:0,
                id_persona:0,
                usuario: '',
                password: '',
                condicion: 1,
                rol_id: 0,
                departamento_id : 0,
                empresa_id : 1,
                nombre : '',
                apellidos : '',
                f_nacimiento: '',
                rfc : '',
                homoclave : '',
                colonia : '',
                direccion : '',
                cp: 0,
                telefono: 0,
                ext: 0,
                celular: 0,
                email: '',
                esquema: 2,
                activo: 1,
                isr : 0,
                retencion : 0,
                tipo_vendedor:0,
                inmobiliaria:'',
                supervisor_id: 0,
                arrayPersonal : [],
                arrayPersonas : [],
                arrayGerentes: [],
                arrayDepartamentos: [],
                arrayRoles: [],
                arrayEmpresas: [],
                arrayInmobiliarias: [],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                errorPersonal : 0,
                errorMostrarMsjPersonal : [],

                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'personal.nombre',
                buscar : '',
                arrayColonias : []
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
            }
        },
        methods : {
            selectInmobiliarias(){
                let me = this;
                me.arrayInmobiliarias=[];
                var url = '/selectInmobiliarias';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayInmobiliarias = respuesta;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            onImageChange(e){
                this.file = e.target.files[0];
                if(this.file=='')
                    return;
            },
            formSubmit(e) {
                e.preventDefault();
                let formData = new FormData();
                formData.append('file', this.file);
                let me = this;
                axios.post('/import/users',formData)
                .then(function (response) {
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Archivo cargado correctamente',
                        showConfirmButton: false,
                        timer: 2500
                        })
                    me.cerrarModal();
                    me.listarPersonal(1,'','Personal'); //se enlistan nuevamente los registros
                })

                .catch(function (error) {
                  me.proceso=false;
                });

            },
            /**Metodo para mostrar los registros */
            listarPersonal(page, buscar, criterio){
                let me = this;
                var url = '/usuarios?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPersonal = respuesta.personas.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectColonias(buscar){
                let me = this;
                me.arrayColonias=[];
                var url = '/select_colonias?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayColonias = respuesta.colonias;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectDepartamento(){
                let me = this;
                me.arrayDepartamentos=[];
                var url = '/select_departamentos';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayDepartamentos = respuesta.departamentos;
                })
                .catch(function (error) {
                    console.log(error);
                });

            },
            selectRoles(){
                let me = this;
                me.arrayRoles=[];
                var url = '/select_roles';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayRoles = respuesta.roles;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectGerentes(){
                let me = this;
                me.arrayGerentes=[];
                var url = '/select_gerentes';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayGerentes = respuesta.gerentes;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            abrirPrivilegios(id){
                let me = this;
                me.id = id;
                me.privilegios = 1;
            },
            cerrarPrivilegios(){
                let me = this
                me.privilegios = 0;
                me.listarPersonal(1,'','Personal')
            },
            selectPersonas(){
                let me = this;
                me.arrayPersonas=[];
                var url = '/select_personas_sin_user';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPersonas = respuesta.personas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            limpiarBusqueda(){
                let me=this;
                me.buscar= "";
            },
            selectEmpresa(){
                let me = this;
                me.arrayEmpresas=[];
                var url = '/select_empresas';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEmpresas = respuesta.empresas;
                })
                .catch(function (error) {
                    console.log(error);
                });

            },
            cambiarPagina(page, buscar, criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarPersonal(page,buscar,criterio);
            },
            asignarUsuario(){
                if(this.validarUsuario() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }
                this.proceso=true;
                  let me = this;
                //Con axios se llama el metodo store de PersonalController
                axios.post('/usuarios/asignar',{
                    'id_persona' : this.id_persona,
                    'usuario': this.usuario,
                    'password': this.password,
                    'rol_id' : this.rol_id,
                    'condicion':this.condicion

                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarPersonal(1,'','Personal'); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Usuario y rol asignado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){

                    console.log(error);
                    me.proceso=false;
                });
            },
            /**Metodo para registrar  */
            registrarPersonal(){
                if(this.validarPersonal() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;

                let me = this;
                //Con axios se llama el metodo store de PersonalController
                axios.post('/usuarios/registrar',{
                    'departamento_id': this.departamento_id,
                    'nombre': this.nombre,
                    'apellidos': this.apellidos,
                    'f_nacimiento': this.f_nacimiento,
                    'rfc': this.rfc,
                    'colonia': this.colonia,
                    'direccion': this.direccion,
                    'cp': this.cp,
                    'telefono': this.telefono,
                    'ext': this.ext,
                    'celular': this.celular,
                    'email': this.email,
                    'activo': this.activo,
                    'empresa_id': this.empresa_id,
                    'usuario': this.usuario,
                    'password': this.password,
                    'rol_id' : this.rol_id,
                    'condicion':this.condicion,
                    'tipo_vendedor':this.tipo_vendedor,
                    'inmobiliaria':this.inmobiliaria,
                    'esquema':this.esquema,
                    'retencion':this.retencion,
                    'isr':this.isr

                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarPersonal(1,'','Personal'); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Personal agregado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    swal({
                        type: 'error',
                        title: 'Oops...',
                        text: 'El RFC que ha ingresado ya se encuentra registrado!',
                        })
                    console.log(error);
                });
            },
            actualizarPersonal(){
                if(this.validarPersonal() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;

                let me = this;
                //Con axios se llama el metodo update de PersonalController
                axios.put('/usuarios/actualizar',{
                    'departamento_id': this.departamento_id,
                    'nombre': this.nombre,
                    'apellidos': this.apellidos,
                    'f_nacimiento': this.f_nacimiento,
                    'rfc': this.rfc.toUpperCase(),
                    'colonia': this.colonia,
                    'direccion': this.direccion,
                    'cp': this.cp,
                    'telefono': this.telefono,
                    'ext': this.ext,
                    'celular': this.celular,
                    'email': this.email,
                    'activo': this.activo,
                    'id' : this.id,
                    'empresa_id' : this.empresa_id,
                    'usuario': this.usuario,
                    'password': this.password,
                    'rol_id' : this.rol_id,
                    'condicion':this.condicion,
                    'tipo_vendedor':this.tipo_vendedor,
                    'inmobiliaria':this.inmobiliaria,
                    'esquema':this.esquema,
                    'retencion':this.retencion,
                    'isr':this.isr
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal();
                    me.listarPersonal(1,'','nombre');
                    //window.alert("Cambios guardados correctamente");
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Cambios guardados correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            asignarGerente(){

                let me = this;
                //Con axios se llama el metodo update de PersonalController
                axios.put('/usuarios/asignar/gerente',{
                    'supervisor_id': this.supervisor_id,
                    'id' : this.id,
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal();
                    me.listarPersonal(1,'','nombre');
                    //window.alert("Cambios guardados correctamente");
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Cambios guardados correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },

            desactivarPersonal(id){
               swal({
                title: 'Esta seguro de desactivar a este usuario?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/usuarios/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarPersonal(1,'','Personal');
                        swal(
                        'Desactivado!',
                        'El registro ha sido desactivado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });


                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {

                }
                })
            },
            activarPersonal(id){
               swal({
                title: 'Esta seguro de activar a este usuario?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/usuarios/activar',{
                        'id': id
                    }).then(function (response) {
                        me.listarPersonal(1,'','Personal');
                        swal(
                        'Activado!',
                        'El registro ha sido activado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });


                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {

                }
                })
            },
            eliminarPersonal(data =[]){
                this.id=data['id'];
                this.departamento_id=data['departamento_id'];
                this.nombre=data['nombre'];
                this.apellidos=data['apellidos'];
                this.f_nacimiento=data['f_nacimiento'];
                this.rfc=data['rfc'];
                this.colonia=data['colonia'];
                this.direccion=data['direccion'];
                this.cp=data['cp'];
                this.telefono=data['telefono'];
                this.ext=data['ext'];
                this.celular=data['celular'];
                this.email=data['email'];
                this.activo=data['activo'];
                //console.log(this.Personal_id);
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

                axios.delete('/personal/eliminar',
                        {params: {'id': this.id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Personal borrado correctamente.',
                        'success'
                        )
                        me.listarPersonal(1,'','Personal');
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            validarPersonal(){
                this.errorPersonal=0;
                this.errorMostrarMsjPersonal=[];

                if(!this.nombre || !this.apellidos) //Si la variable Personal esta vacia
                    this.errorMostrarMsjPersonal.push("El nombre de la Persona no puede ir vacio.");

                if(!this.email)
                    this.errorMostrarMsjPersonal.push("El correo no debe ir vacio");

                if(!this.rfc || this.rfc.length<10)
                    this.errorMostrarMsjPersonal.push("El RFC no debe ir vacio (10 caracteres)");

                if(!this.celular || this.celular.length<10)
                    this.errorMostrarMsjPersonal.push("El número de celular debe ser de 10 digitos");

                if(!this.direccion)
                    this.errorMostrarMsjPersonal.push("La direccion no puede ir vacio");

                if(!this.departamento_id)
                    this.errorMostrarMsjPersonal.push("Debe seleccionar el departamento");

                if(!this.f_nacimiento)
                    this.errorMostrarMsjPersonal.push("La fecha de nacimiento no puede ir vacia");

                if(!this.usuario)
                    this.errorMostrarMsjPersonal.push("Ingresar nombre de usuario");

                if(!this.password)
                    this.errorMostrarMsjPersonal.push("Ingresar contraseña");

                if(!this.rol_id)
                    this.errorMostrarMsjPersonal.push("Seleccionar rol");

                if(this.errorMostrarMsjPersonal.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorPersonal = 1;

                return this.errorPersonal;
            },
            validarUsuario(){
                this.errorPersonal=0;
                this.errorMostrarMsjPersonal=[];

                if(!this.id_persona)
                    this.errorMostrarMsjPersonal.push("Seleccionar la persona");
                if(!this.usuario)
                    this.errorMostrarMsjPersonal.push("Ingresar nombre de usuario");

                if(!this.password)
                    this.errorMostrarMsjPersonal.push("Ingresar contraseña");

                if(!this.rol_id)
                    this.errorMostrarMsjPersonal.push("Seleccionar rol");

                if(this.errorMostrarMsjPersonal.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorPersonal = 1;

                return this.errorPersonal;
            },

            cerrarModal(){
                this.modal = 0;
                this.departamento_id = '';
                this.empresa_id = 1;
                this.nombre='';
                this.apellidos='';
                this.f_nacimiento='';
                this.rfc='';
                this.colonia='';
                this.direccion='';
                this.cp='';
                this.telefono='';
                this.ext='';
                this.celular='';
                this.email='';
                this.activo='';
                this.id_persona = 0;
                this.password = '';
                this.usuario = '';
                this.condicion = 1;
                this.errorPersonal = 0;
                this.errorMostrarMsjPersonal = [];
                this.inmobiliaria='',
                this.tipo_vendedor=0;
            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(accion,data =[]){
                this.modal = 1;
                switch(accion){
                    case 'registrar':
                    {
                        this.tituloModal = 'Registrar Personal';
                        this.departamento_id = '0',
                        this.empresa_id = 1,
                        this.nombre='';
                        this.apellidos='';
                        this.f_nacimiento='';
                        this.rfc='';
                        this.homoclave='';
                        this.colonia='0';
                        this.direccion='';
                        this.cp='';
                        this.telefono='';
                        this.ext='';
                        this.celular='';
                        this.email='';
                        this.usuario='';
                        this.password='';
                        this.condicion=1;
                        this.rol_id=0;
                        this.inmobiliaria='';
                        this.tipo_vendedor=0;
                        this.esquema=2;
                        this.retencion =0;
                        this.isr = 0;

                        this.activo='1';
                        this.tipoAccion = 3;
                        break;
                    }
                    case 'Importar':{
                        this.modal = 2;
                        this.file = '';
                    }
                    case 'actualizar':
                    {
                        this.tituloModal='Actualizar Personal';
                        this.tipoAccion=2;
                        this.id=data['id'];
                        this.departamento_id=data['departamento_id'];
                        this.empresa_id=data['empresa_id'];
                        this.nombre=data['nombre'];
                        this.apellidos=data['apellidos'];
                        this.f_nacimiento=data['f_nacimiento'];
                        this.rfc=data['rfc'];
                        this.homoclave=data['homoclave'];
                        this.colonia=data['colonia'];
                        this.direccion=data['direccion'];
                        this.cp=data['cp'];
                        this.telefono=data['telefono'];
                        this.ext=data['ext'];
                        this.celular=data['celular'];
                        this.email=data['email'];
                        this.activo=data['activo'];
                        this.usuario=data['usuario'];
                        this.rol_id=data['rol_id'];
                        this.password=data['password'];
                        this.condicion=data['condicion'];
                        this.tipoAccion = 2;
                        this.inmobiliaria=data['inmobiliaria'];
                        this.tipo_vendedor=data['tipo'];
                        this.esquema = data['esquema'];
                        this.retencion = data['retencion'];
                        this.isr = data['isr'];

                        this.selectColonias(this.cp);
                        break;
                    }
                    case 'Asignar':
                    {
                        this.tituloModal='Asignar rol';
                        this.id_persona = 0;
                        this.usuario='';
                        this.password='';
                        this.condicion=1;
                        this.rol_id=0;
                        this.tipoAccion = 1;
                        break;
                    }
                    case 'asignarGerente':
                    {
                        this.tituloModal='Asignar asesor a gerente';
                        this.id=data['id'];
                        this.tipoAccion = 4;
                        break;
                    }
                }
            }
        },
        mounted() {
            this.listarPersonal(1,this.buscar,this.criterio);
            this.selectRoles();
            this.selectDepartamento();
            this.selectEmpresa();
            this.selectPersonas();
            this.selectGerentes();
            this.selectInmobiliarias();
        }
    }
</script>
<style>

    .form-control:disabled, .form-control[readonly] {
        background-color: rgba(0, 0, 0, 0.06);
        opacity: 1;
        font-size: 0.85rem;
        color: #27417b;
    }
    .div-error{
        display:flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }

    .td2, .th2 {
    border: solid rgb(200, 200, 200) 1px;
    padding: .5rem;
    }

input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
  -webkit-appearance: none;
   margin: 0;
}
</style>
