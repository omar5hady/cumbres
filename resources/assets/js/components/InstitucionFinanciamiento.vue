<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Instituciones de Financiamiento
                        <!--   Boton Nuevo    -->
                        <button type="button" @click="abrirModal('institucion','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" v-model="buscar" @keyup.enter="listarInstituciones(1,buscar,'nombre')" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarInstituciones(1,buscar,'nombre')" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Institución</th>
                                    <th>Telefono</th>
                                    <th>Pagina web</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="institucion in arrayInstituciones" :key="institucion.id">
                                    <td style="width:15%">
                                        <button title="Editar" type="button" @click="abrirModal('institucion','actualizar',institucion)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                        </button>  
                                        <button type="button" class="btn btn-danger btn-sm" @click="eliminarInstitucion(institucion)">
                                            <i class="icon-trash"></i>
                                        </button>                                       
                                    </td>
                                    <td v-text="institucion.nombre"></td>
                                    <td v-text="institucion.telefono1"></td>
                                    <td v-text="institucion.pagina_web"></td>
                                </tr>                                
                            </tbody>
                        </table>
                        </div>
                        <nav>
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
                            <!--<form action="" method="" enctype="multipart/form-data" class="form-horizontal">-->
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Institucion</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Institucion de Financiamiento">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Tel. 1</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="telefono1" class="form-control" placeholder="Telefono ">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Tel. 2</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="telefono2" class="form-control" placeholder="Telefono">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Tel. 3</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="telefono3" class="form-control" placeholder="Telefono">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Tel. 4</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="telefono4" class="form-control" placeholder="Telefono">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Pagina web</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="pagina_web" class="form-control" placeholder="Pagina web">
                                    </div>
                                </div>
                                
                                <div v-show="errorInstitucion" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjInstitucion" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                           <!-- </form>-->
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarInstitucion()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarInstitucion()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
        </main>
</template>

<script>
    export default {
        data (){
            return {
                id: 0,
                nombre : '',
                telefono1 : '',
                telefono2 : '',
                telefono3 : '',
                telefono4 : '',
                pagina_web : '',
                arrayInstituciones : [],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                errorInstitucion : 0,
                errorMostrarMsjInstitucion : [],
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
                buscar : ''
            }
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;             

            }
        },
        methods : {
            listarInstituciones (page,buscar,criterio){
                let me=this;
                var url= '/institucion_financiamiento?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayInstituciones = respuesta.instituciones_financiamiento.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarInstituciones(page,buscar,criterio);
            },
            /**Metodo para registrar  */
            registrarInstitucion(){
                if(this.validarinstitucion()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/institucion_financiamiento/registrar',{
                    'nombre': this.nombre,
                    'telefono1': this.telefono1,
                    'telefono2': this.telefono2,
                    'telefono3': this.telefono3,
                    'telefono4': this.telefono4,
                    'pagina_web': this.pagina_web
                }).then(function (response){
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarInstituciones(1,'','nombre'); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Institucion de Financiamiento agregado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            actualizarInstitucion(){
                if(this.validarinstitucion()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                let me = this;
                //Con axios se llama el metodo update de DepartamentoController
                axios.put('/institucion_financiamiento/actualizar',{
                    'nombre': this.nombre,
                    'telefono1': this.telefono1,
                    'telefono2': this.telefono2,
                    'telefono3': this.telefono3,
                    'telefono4': this.telefono4,
                    'pagina_web': this.pagina_web,
                    'id' : this.id
                }).then(function (response){
                    me.cerrarModal();
                    me.listarInstituciones(1,'','nombre'); //se enlistan nuevamente los registros
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
            eliminarInstitucion(data =[]){
                this.id=data['id'];
                this.nombre=data['nombre'];
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

                axios.delete('/institucion_financiamiento/eliminar', 
                        {params: {'id': this.id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Departamento borrado correctamente.',
                        'success'
                        )
                        me.listarInstituciones(1,'','nombre'); //se enlistan nuevamente los registros
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            validarinstitucion(){
                this.errorInstitucion=0;
                this.errorMostrarMsjInstitucion=[];

                if(!this.nombre) //Si la variable departamento esta vacia
                    this.errorMostrarMsjInstitucion.push("El nombre de la institución no puede ir vacio.");

                if(this.errorMostrarMsjInstitucion.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorInstitucion = 1;

                return this.errorInstitucion;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.nombre = '';
                this.telefono1 = '';
                this.telefono2 = '';
                this.telefono3 = '';
                this.telefono4 = '';
                this.pagina_web = '';
                this.errorInstitucion = 0;
                this.errorMostrarMsjInstitucion = [];
                this.tipoAccion = 0;

            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(institucion, accion,data =[]){
                switch(institucion){
                    case "institucion":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Institucion de Financiamiento';
                                this.nombre ='';
                                this.telefono1 = '';
                                this.telefono2 = '';
                                this.telefono3 = '';
                                this.telefono4 = '';
                                this.pagina_web = '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal =1;
                                this.tituloModal='Actualizar Institucion de Financiamiento';
                                this.tipoAccion=2;
                                this.id=data['id'];
                                this.nombre=data['nombre'];
                                this.telefono1 = data['telefono1'];
                                this.telefono2 = data['telefono2'];
                                this.telefono3 = data['telefono3'];
                                this.telefono4 = data['telefono4'];
                                this.pagina_web = data['pagina_web'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarInstituciones(1,this.buscar,this.criterio);
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
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>
