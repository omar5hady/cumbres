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
                        <button type="button" @click="abrirModal('registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <select class="form-control" v-model="b_empresa" >
                                    <option value="">Empresa</option>
                                    <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <select class="form-control" v-model="b_marca" >
                                    <option value="">Marca</option>
                                    <option v-for="marcas in marcasAutos" :key="marcas" :value="marcas" v-text="marcas"></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text"  v-model="buscar" @keyup.enter="listarVehiculos(1)" class="form-control" placeholder="Vehiculo">
                                    <button type="submit" @click="listarVehiculos(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive"> 
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Vehiculo</th>
                                        <th>Marca</th>
                                        <th>Modelo</th>
                                        <th>Clave</th>
                                        <th># Serie</th>
                                        <th># Motor</th>
                                        <th># Placa</th>
                                        <th>Responsable</th>
                                        <th>Empresa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="vehiculo in arrayVehiculos.data" :key="vehiculo.id">
                                        <td class="td2">
                                            <button type="button" @click="abrirModal('actualizar',vehiculo)" class="btn btn-warning btn-sm">
                                                <i class="icon-pencil"></i>
                                            </button>
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
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
                           <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="arrayVehiculos.current_page > 5" @click="listarVehiculos(1)">
                                    <a class="page-link" href="#" >Inicio</a>
                                </li>
                                <li class="page-item" v-if="arrayVehiculos.current_page > 1"
                                    @click="listarVehiculos(arrayVehiculos.current_page-1)">
                                    <a class="page-link" href="#" >Ant</a>
                                </li>

                                <li class="page-item" v-if="arrayVehiculos.current_page-3 >= 1"
                                    @click="listarVehiculos(arrayVehiculos.current_page-3)">
                                    <a class="page-link" href="#" v-text="arrayVehiculos.current_page-3"></a>
                                </li>
                                <li class="page-item" v-if="arrayVehiculos.current_page-2 >= 1"
                                    @click="listarVehiculos(arrayVehiculos.current_page-2)">
                                    <a class="page-link" href="#" v-text="arrayVehiculos.current_page-2"></a>
                                </li>
                                <li class="page-item" v-if="arrayVehiculos.current_page-1 >= 1"
                                    @click="listarVehiculos(arrayVehiculos.current_page-1)">
                                    <a class="page-link" href="#" v-text="arrayVehiculos.current_page-1"></a>
                                </li>
                                <li class="page-item active" >
                                    <a class="page-link" href="#" v-text="arrayVehiculos.current_page"></a>
                                </li>
                                <li class="page-item" 
                                    v-if="arrayVehiculos.current_page+1 <= arrayVehiculos.last_page">
                                    <a class="page-link" href="#" @click="listarVehiculos(arrayVehiculos.current_page+1)" 
                                    v-text="arrayVehiculos.current_page+1"></a>
                                </li>
                                <li class="page-item" 
                                    v-if="arrayVehiculos.current_page+2 <= arrayVehiculos.last_page">
                                    <a class="page-link" href="#" @click="listarVehiculos(arrayVehiculos.current_page+2)"
                                     v-text="arrayVehiculos.current_page+2"></a>
                                </li>
                                <li class="page-item" 
                                    v-if="arrayVehiculos.current_page+3 <= arrayVehiculos.last_page">
                                    <a class="page-link" href="#" @click="listarVehiculos(arrayVehiculos.current_page+3)"
                                    v-text="arrayVehiculos.current_page+3"></a>
                                </li>

                                <li class="page-item" v-if="arrayVehiculos.current_page < arrayVehiculos.last_page"
                                    @click="listarVehiculos(arrayVehiculos.current_page+1)">
                                    <a class="page-link" href="#" >Sig</a>
                                </li>
                                <li class="page-item" v-if="arrayVehiculos.current_page < 5 && arrayVehiculos.last_page > 5" @click="listarVehiculos(arrayVehiculos.last_page)">
                                    <a class="page-link" href="#" >Ultimo</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
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
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Vehiculo</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="vehiculo" class="form-control" placeholder="Nombre de vehiculo">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Marca</label>
                                    <div class="col-md-6">
                                        <select class="form-control" v-model="marca" >
                                            <option value="">Seleccione</option>
                                            <option v-for="marcas in marcasAutos" :key="marcas" :value="marcas" v-text="marcas"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input" >Modelo</label>
                                    <div class="col-md-3">
                                        <input type="text" v-model="modelo" maxlength="4" v-on:keypress="isNumber($event)" class="form-control" placeholder="Modelo">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Clave</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="clave" maxlength="10" class="form-control" placeholder="Clave">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Número de Serie</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="numero_serie" maxlength="20" class="form-control" placeholder="Número de serie">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Número de Motor</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="numero_motor" maxlength="11" class="form-control" placeholder="Número de motor">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Número de Placa</label>
                                    <div class="col-md-5">
                                        <input type="text" v-model="placas" maxlength="7" class="form-control" placeholder="Número de placa">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Responsable</label>
                                    <div class="col-md-6">
                                        <input v-if="vista==2" disabled type="text" v-model="nombre" class="form-control col-md-8">
                                        <button v-if="vista == 2" class="form-control btn btn-sm btn-secondary col-md-4" @click="vista = 1, responsable_id = ''">Cambiar</button>
                                        <input v-if="vista==1" type="text" name="user" list="usersName" @keyup="selectUsuario(responsable_id)" @change="getNombre(responsable_id)"  class="form-control col-md-8" v-model="responsable_id">
                                        <datalist v-if="vista==1" id="usersName">
                                            <option value="">Seleccione</option>
                                            <option v-for="users in arrayUsers" :key="users.id" :value="users.id" v-text="users.nombre + ' '+ users.apellidos"></option>
                                        </datalist>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Empresa</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="empresa" >
                                            <option value="">Seleccione</option>
                                            <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Div para mostrar los errores que mande validerNotaria -->
                                <div v-show="errorVehiculo" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjVehiculo" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrar()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizar()">Actualizar</button>
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
                id:'',
                vehiculo : '',
                responsable_id : '',
                modelo : '',
                marca: '',
                clave: '',
                placas: '',
                numero_serie : '',
                numero_motor : '',
                empresa : 'Grupo Constructor Cumbres',
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
                errorVehiculo : 0,
                errorMostrarMsjVehiculo : [],
                buscar : '',
                vista: 1,
                nombre:'',
                b_empresa : '',
                b_marca:''
            }
        },
        computed:{
           
        },
        methods : {
            /**Metodo para mostrar los registros */
            listarVehiculos(page){
                let me = this;
                var url = '/vehiculos/index?page=' + page+'&b_vehiculo='
                    +this.buscar + '&b_empresa='+this.b_empresa + '&b_marca='+this.b_marca;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayVehiculos = respuesta;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectUsuario(buscar){
                let me = this;
                
                me.arrayUsers=[];
                var url = '/usuarios/selectUser?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayUsers = respuesta.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getNombre(id){
                let me = this;
                var url = '/usuarios/getNombre?id=' + id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.nombre = respuesta.nombre + ' ' +respuesta.apellidos;
                    me.vista = 2
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
            /**Metodo para registrar  */
            registrar(){
                if(this.validarRegistro()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/vehiculos/store',{
                    'vehiculo' : this.vehiculo,
                    'modelo' : this.modelo,
                    'marca' : this.marca,
                    'clave' : this.clave,
                    'placas' : this.placas,
                    'numero_serie' : this.numero_serie,
                    'numero_motor ' : this.numero_motor,
                    'responsable_id' : this.responsable_id,
                    'empresa' : this.empresa
                }).then(function (response){
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarVehiculos(1); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Vehiculo agregada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            actualizar(){
              
               if(this.validarRegistro()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.put('/vehiculos/update',{
                    'id' : this.id,
                    'vehiculo' : this.vehiculo,
                    'modelo' : this.modelo,
                    'marca' : this.marca,
                    'clave' : this.clave,
                    'placas' : this.placas,
                    'numero_serie' : this.numero_serie,
                    'numero_motor ' : this.numero_motor,
                    'responsable_id' : this.responsable_id,
                    'empresa' : this.empresa
                }).then(function (response){
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarVehiculos(1); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Vehiculo actualizado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
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
            validarRegistro(){
                this.errorVehiculo=0;
                this.errorMostrarMsjVehiculo=[];

                if(!this.vehiculo) //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjVehiculo.push("Nombre de vehiculo");

                if(!this.responsable_id) //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjVehiculo.push("Responsable.");

                if(this.errorMostrarMsjVehiculo.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorVehiculo = 1;

                return this.errorVehiculo;
            },
            cerrarModal(){
               this.modal = 0;

            },
            
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(accion,data =[]){
                switch(accion){
                    case 'registrar':
                    {
                        this.modal = 1;
                        this.tituloModal = 'Registrar Vehiculo';
                        
                        this.tipoAccion = 1;
                        this.vehiculo = '';
                        this.modelo = '';
                        this.marca = '';
                        this.clave = '';
                        this.placas = '';
                        this.numero_serie = '';
                        this.numero_motor = '';
                        this.responsable_id = '';
                        this.empresa = '';
                        this.vista = 1;
                        break;
                    }
                    case 'actualizar':
                    {
                        //console.log(data);
                        this.modal =1;
                        this.tituloModal='Actualizar Vehiculo';
                        this.id = data['id'];
                        this.vehiculo = data['vehiculo'];
                        this.modelo = data['modelo'];
                        this.marca = data['marca'];
                        this.clave = data['clave'];
                        this.placas = data['placas'];
                        this.numero_serie = data['numero_serie'];
                        this.numero_motor = data['numero_motor'];
                        this.responsable_id = data['responsable_id'];
                        this.empresa = data['empresa'];
                        this.vista = 2;
                        this.getNombre(this.responsable_id);
                        this.tipoAccion=2;
                        
                        break;
                    }
                }
            }
        },
        mounted() {
            this.listarVehiculos(1);
            this.getMarcas();
            this.selectUsuario('');
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
        position: fixed !important;
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
    .table2 {
        margin: auto;
        border-collapse: collapse;
        overflow-x: auto;
        display: block;
        width: fit-content;
        max-width: 100%;
        box-shadow: 0 0 1px 1px rgba(0, 0, 0, .1);
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
