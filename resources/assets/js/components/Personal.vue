<template>
    <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Personal
                        <!--   Boton Nuevo    -->
                        <button type="button" @click="abrirModal('Personal','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-5" v-model="criterio">
                                      <option value="nombre">Personal</option>
                                      <option value="rfc">RFC</option>
                                    </select>
                                    
                                    <input type="text" v-if="criterio=='nombre'" v-model="buscar" @keyup.enter="listarPersonal(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <input type="text" v-if="criterio=='rfc'" v-model="buscar" @keyup.enter="listarPersonal(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                       
                                    <button type="submit" @click="listarPersonal(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>

                                    <th>Personal</th>
                                    <th>Apellido paterno</th>
                                    <th>Apellido materno</th>
                                    <th>Departamento</th>

                                    <th>RFC</th>
                                    <th>Celular</th>
                                    <th>Email</th>
                                    <th>Activo/Inactivo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="Personal in arrayPersonal" :key="Personal.id">
                                    <td>
                                        <button type="button" @click="abrirModal('Personal','actualizar',Personal)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <button type="button" class="btn btn-danger btn-sm" @click="eliminarPersonal(Personal)">
                                          <i class="icon-trash"></i>
                                        </button>
                                    </td>
                                    <td v-text="Personal.nombre"></td>
                                    
                                    <td v-text="Personal.ap_paterno"></td>
                                    <td v-text="Personal.ap_materno"></td>
                                    <td v-text="Personal.departamento"></td>
                                    <td v-text="Personal.rfc"></td>
                                    <td v-text="Personal.celular"></td>
                                    <td v-text="Personal.email"></td>
                                   <td>
                                        <span v-if = "Personal.activo==1" class="badge badge-success">Activo</span>
                                        <span v-if = "Personal.activo==0" class="badge badge-danger">Inactivo</span>
                                    </td>

                                    <!-- <td v-if="Personal.tipo_proyecto==1" v-text="'Lotificación'"></td>
                                    <td v-if="Personal.tipo_proyecto==2" v-text="'Departamento'"></td>
                                    <td v-if="Personal.tipo_proyecto==3" v-text="'Terreno'"></td> -->
                            
                                   
                                </tr>                               
                            </tbody>
                        </table>
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
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                    <!--Criterios para el listado de busqueda -->


                                  <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Apellido paterno</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="ap_paterno" class="form-control" placeholder="Apellido paterno">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Apellido materno</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="ap_materno" class="form-control" placeholder="Apellido paterno">
                                    </div>
                                </div>
                                   <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de nacimiento</label>
                                    <div class="col-md-6">
                                        <input type="date" v-model="f_nacimiento" class="form-control" placeholder="Fecha de nacimiento">
                                    </div>
                                </div>
                                     <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">RFC</label>
                                    <div class="col-md-6">
                                        <input type="text" maxlength="13" v-model="rfc" class="form-control" placeholder="RFC">
                                    </div>
                                </div>

                                         <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Codigo Postal</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="cp" @keyup="selectColonias(cp)" class="form-control" placeholder="Codigo postal">
                                    </div>
                                </div>

                                 <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">colonia</label>
                                    <div class="col-md-7">
                                        <select class="form-control" v-model="colonia">
                                            <option v-for="colonias in arrayColonias" :key="colonias.colonia" :value="colonias.colonia" v-text="colonias.colonia"></option>
                                        </select>
                                        <!--<input type="text" v-model="ciudad" class="form-control" placeholder="Ciudad">-->
                                    </div>
                                </div>
                                         <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Direccion</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="direccion" class="form-control" placeholder="Direccion">
                                    </div>
                                </div>

                                  <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Departamento</label>
                                    <div class="col-md-9">
                                          <select class="form-control" v-model="departamento_id">
                                            <option v-for="departamentos in arrayDepartamentos" :key="departamentos.id_departamento" :value="departamentos.id_departamento" v-text="departamentos.departamento"></option>
                                        </select>
                                        <!-- <input type="text" v-model="departamento_id" class="form-control" placeholder="Departamento"> -->
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Telefono</label>
                                    <div class="col-md-6">
                                        <input type="text" maxlength="8" v-on:keypress="isNumber(event)" class="form-control" v-model="telefono"  placeholder="Telefono">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Extension</label>
                                    <div class="col-md-4">
                                        <input type="text" maxlength="3" v-on:keypress="isNumber(event)" v-model="ext" class="form-control" placeholder="Extension">
                                    </div>
                                </div>
                            
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Celular</label>
                                    <div class="col-md-6">
                                        <input type="text" maxlength="10" v-on:keypress="isNumber(event)" v-model="celular" class="form-control" placeholder="Celular">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Correo electronico</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="email" class="form-control" placeholder="Correo electronico">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Estatus</label>
                                    <div class="col-md-5">
                                        <select class="form-control" v-model="activo">
                                            <option value="1">Activo</option>
                                            <option value="0"> Inactivo</option>
                                        </select>
                                        <!--<input type="text" v-model="estado" class="form-control" placeholder="Estado">-->
                                    </div>
                                </div>
                                <!-- Div para mostrar los errores que mande validerPersonal -->
                                <div v-show="errorPersonal" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjPersonal" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarPersonal()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarPersonal()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
            

        </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    export default {
        data(){
            return{
                id:0,
                departamento_id : 0,
                nombre : '',
                ap_materno : '',
                ap_paterno : '',
                f_nacimiento: '',
                rfc : '',
                colonia : '',
                direccion : '',
                cp: 0,
                telefono: 0,
                ext: 0,
                celular: 0,
                email: '',
                activo: 1, 
                arrayPersonal : [],
                arrayDepartamentos: [],
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
                criterio : 'nombre', 
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
            /**Metodo para mostrar los registros */
            listarPersonal(page, buscar, criterio){
                let me = this;
                var url = '/personal?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPersonal = respuesta.Personales.data;
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
            cambiarPagina(page, buscar, criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarPersonal(page,buscar,criterio);
            },
            /**Metodo para registrar  */
            registrarPersonal(){
                if(this.validarPersonal()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                let me = this;
                //Con axios se llama el metodo store de PersonalController
                axios.post('/personal/registrar',{
                    'departamento_id': this.departamento_id,
                    'nombre': this.nombre,
                    'ap_paterno': this.ap_paterno,
                    'ap_materno': this.ap_materno,
                    'f_nacimiento': this.f_nacimiento,
                    'rfc': this.rfc,
                    'colonia': this.colonia,
                    'direccion': this.direccion,
                    'cp': this.cp,
                    'telefono': this.telefono,
                    'ext': this.ext,
                    'celular': this.celular,
                    'email': this.email,
                    'activo': this.activo
                    
                }).then(function (response){
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
                    console.log(error);
                });
            },
            actualizarPersonal(){
                if(this.validarPersonal()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                let me = this;
                //Con axios se llama el metodo update de PersonalController
                axios.put('/personal/actualizar',{
                    'departamento_id': this.departamento_id,
                    'nombre': this.nombre,
                    'ap_paterno': this.ap_paterno,
                    'ap_materno': this.ap_materno,
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
                    'id' : this.id
                }).then(function (response){
                    me.cerrarModal();
                    me.listarPersonal(1,'','Personal');
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
            eliminarPersonal(data =[]){
                this.id=data['id'];
                this.departamento_id=data['departamento_id'];
                this.nombre=data['nombre'];
                this.ap_paterno=data['ap_paterno'];
                this.ap_materno=data['ap_materno'];
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

                if(!this.nombre || !this.ap_paterno ||!this.ap_materno) //Si la variable Personal esta vacia
                    this.errorMostrarMsjPersonal.push("El nombre del Personal no puede ir vacio.");

                if(!this.email)
                    this.errorMostrarMsjPersonal.push("El correo no debe ir vacio");
                
                if(!this.rfc || this.rfc.length<10)
                    this.errorMostrarMsjPersonal.push("El RFC no debe ir vacio (10 caracteres)");

                if(!this.telefono || this.telefono.length<8)
                    this.errorMostrarMsjPersonal.push("El número de telefono debe ser de 8 digitos");

                
                if(!this.telefono || this.celular.length<10)
                    this.errorMostrarMsjPersonal.push("El número de celular debe ser de 10 digitos");

                if(this.errorMostrarMsjPersonal.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorPersonal = 1;

                return this.errorPersonal;
            },
            isNumber: function(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                    evt.preventDefault();;
                } else {
                    return true;
                }
            },
            cerrarModal(){
                this.modal = 0;
                this.departamento_id = '';
                this.nombre='';
                this.ap_paterno='';
                this.ap_materno='';
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
                this.errorPersonal = 0;
                this.errorMostrarMsjPersonal = [];

            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(modelo, accion,data =[]){
                switch(modelo){
                    case "Personal":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Personal';
                                this.departamento_id = '',
                                this.nombre='';
                                this.ap_paterno='';
                                this.ap_materno='';
                                this.f_nacimiento='';
                                this.rfc='';
                                this.colonia='';
                                this.direccion='';
                                this.cp='';
                                this.telefono='';
                                this.ext='';
                                this.celular='';
                                this.email='';
                                this.activo='1';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal =1;
                                this.tituloModal='Actualizar Personal';
                                this.tipoAccion=2;
                                this.id=data['id'];
                                this.departamento_id=data['departamento_id'];
                                this.nombre=data['nombre'];
                                this.ap_paterno=data['ap_paterno'];
                                this.ap_materno=data['ap_materno'];
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
                                break;
                            }
                        }
                    }
                }
                this.selectDepartamento();
            }
        },
        mounted() {
            this.listarPersonal(1,this.buscar,this.criterio);
        }
    }
</script>
<style>
    .modal-content{
        width: 100% !important;
        position: absolute !important;
        
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
        overflow-y: auto;
    }
    .div-error{
        display:flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }

input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
  -webkit-appearance: none; 
   margin: 0;  
} 


</style>
