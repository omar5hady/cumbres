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
                        <button type="button" @click="abrirModal('crear')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <input type="text" class="form-control" disabled value="Fecha de creación"  >
                                    <input @keyup.enter="getNotificaciones(1)" type="date" class="form-control" v-model="fecha_inicio"  >
                                    <input @keyup.enter="getNotificaciones(1)" type="date" class="form-control" v-model="fecha_fin" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input @keyup.enter="getNotificaciones(1)" type="text" v-model="b_nombre" class="form-control" placeholder="Nombre">
                                    <button  @click="getNotificaciones(1)" type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class=" col-md-10 table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Mensaje</th>
                                        <th>Recurrencia</th>
                                        <th>Fecha Creado</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="notificacion in arrayNotificacion" :key="notificacion.id">
                                        <td class="td2" v-text="notificacion.nombre+' '+notificacion.apellidos" ></td>
                                        <td class="td2" v-text="notificacion.mensaje"></td>
                                        <td class="td2" v-if="notificacion.periodo == 1">Diario</td>
                                        <td class="td2" v-if="notificacion.periodo == 7">Semanal</td>
                                        <td class="td2" v-if="notificacion.periodo == 30">Mensual</td>
                                        <td class="td2" v-text="notificacion.created_at"></td>
                                    </tr>                               
                                </tbody>
                            </table>
                        </div>
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
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal" ></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                             <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Mensaje</label>
                                    <div class="col-md-6">
                                        <textarea rows="10" cols="30" v-model="mensaje" class="form-control" placeholder="Mensaje"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Recurrencia</label>
                                    <div class="col-md-6">
                                       <select class="form-control" v-model="recurrencia">
                                            <option value="0">Unica ocasión</option>
                                            <option value="1">Diario </option>
                                            <option value="7">Semanal</option>
                                            <option value="30">Mensual</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Rol</label>
                                    <div class="col-md-6">
                                       <select class="form-control" v-model="rol_id" @click="getUser(rol_id)">
                                            <option value="">Seleccione</option>
                                            <option v-for="rol in arrayRol" :key="rol.id" :value="rol.id" v-text="rol.nombre"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-6">
                                       <select class="form-control" name = 'arrayPer[]' multiple size = 7 v-model="arrayPer">
                                            
                                            <option v-for="persona in arrayPersonal" :key="persona.id" :value="persona" v-text="persona.nombre + ' ' + persona.apellidos"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-6">
                                        <div class="table-responsive">
                                            <table class="table " >
                                                <thead>
                                                    <tr>
                                                        <th style="text-align:center">Seleccionados</th>
                                                        <!--   <th>id</th>  -->
                                                        
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="array in arrayPer" :key="array.id">
                                                        <td v-if="array.nombre !='' && array.apellidos !=''" v-text="array.nombre + ' ' + array.apellidos"></td>
                                                        <td v-else v-text="array.nombre" ></td>
                                                    <!--    <td v-text="array.id"></td> -->
                                                    </tr>                               
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Div para mostrar los errores que mande validerModelo -->
                                <div v-show="error" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsj" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" class="btn btn-primary" @click="storeNotificacion()">Guardar</button>

                            </div>
                             
                            </div>
                            
                        </div>
                    <!-- /.modal-content -->
                
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
                          
                tituloModal : '',
                mensaje :'',
                rol_id :'',
                periodo : 0,
                fecha_creado : '',
                recurrencia :0,

                nombre : '',
                arrayPer : [],
                
                arrayRol: [],
                arrayPersonal : [],
                arrayNotificacion : [],
               
                modal : 0,
                mostrar : 0,
                error : 0,
                
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
                                'mensaje' : this.mensaje
                            }); 
                        });
                        me.cerrarModal();
                        me.getNotificaciones(1);
                        Swal({
                            title: 'Hecho!',
                            text: 'Los modelos se han asignado',
                            type: 'success',
                            animation: false,
                            customClass: 'animated bounceInRight'
                        })
                    }})
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
                

            },
           
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(accion){
                switch(accion){
                    case 'crear':
                    {
                        this.modal = 1;
                        this.tituloModal = 'Crear mensaje';
                        this.mensaje ='';
                        this.rol_id ='';
                        this.periodo = 0;
                        this.fecha_creado = '';
                    
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
    .btn-success2 {
        color: #fff;
        background-color: #2c309e;
        border-color: #313a98;
    }
    .btn-success2:active, .btn-success2.active, .show > .btn-success2.dropdown-toggle {
        background-color: #2c309e;
        background-image: none;
        border-color: #313a98;
    }
    .btn-success2:focus, .btn-success2.focus {
        box-shadow: 0 0 0 3px rgba(77, 100, 189, 0.5);
    }
    .btn-success2:hover {
        color: #fff;
        background-color: #2c309e;
        border-color: #313a98;
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
     .table3 {

    margin-left: auto;
    border-collapse:separate;
    
    display: block;
    width: auto;
    max-width: 80%;
    align-content: center;
    text-align: center;
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
