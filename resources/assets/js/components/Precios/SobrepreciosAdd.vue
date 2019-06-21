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
                    <i class="fa fa-align-justify"></i> Sobreprecios
                    <!--   Boton Nuevo    -->
                    <button type="button" @click="abrirModal('sobreprecio','registrar')" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                    <!---->
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" v-model="buscar" @keyup.enter="listarSobreprecios(1,buscar,'nombre')" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" @click="listarSobreprecios(1,buscar,'nombre')" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Sobreprecio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="sobreprecio in arraySobreprecios" :key="sobreprecio.id">
                                <td style="width:15%">
                                    <button title="Editar" type="button" @click="abrirModal('sobreprecio','actualizar',sobreprecio)" class="btn btn-warning btn-sm">
                                        <i class="icon-pencil"></i>
                                    </button>  
                                    <!-- <button type="button" class="btn btn-danger btn-sm" @click="eliminarServicio(servicio)">
                                        <i class="icon-trash"></i>
                                    </button>                                        -->
                                </td>
                                <td v-text="sobreprecio.nombre"></td>
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
                                <label class="col-md-3 form-control-label" for="text-input">Sobreprecio</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="sobreprecio" class="form-control" placeholder="Sobreprecio">
                                </div>
                            </div>
                            
                            <div v-show="errorServicio" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjServicio" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div>
                        <!-- </form>-->
                    </div>
                    <!-- Botones del modal -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                        <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarSobreprecio()">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarSobreprecio()">Actualizar</button>
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
                proceso:false,
                id: 0,
                sobreprecio : '',
                arraySobreprecios : [],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                errorServicio : 0,
                errorMostrarMsjServicio : [],
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
            listarSobreprecios (page,buscar,criterio){
                let me=this;
                var url= '/sobreprecios?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arraySobreprecios = respuesta.sobreprecios.data;
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
                me.listarSobreprecios(page,buscar,criterio);
            },
            /**Metodo para registrar  */
            registrarSobreprecio(){
                if(this.validarMedio() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }
                this.proceso=true;

                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/sobreprecios/registrar',{
                    'sobreprecio': this.sobreprecio,
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarSobreprecios(me.pagination.current_page,me.buscar,me.criterio); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Sobreprecio agregado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            actualizarSobreprecio(){
                if(this.validarMedio() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso= true;

                let me = this;
                //Con axios se llama el metodo update de DepartamentoController
                axios.put('/sobreprecios/actualizar',{
                    'sobreprecio': this.sobreprecio,
                    'id' : this.id
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal();
                    me.listarSobreprecios(me.pagination.current_page,me.buscar,me.criterio); //se enlistan nuevamente los registros
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

            validarMedio(){
                this.errorPublicidad=0;
                this.errorMostrarMsjPublicidad=[];

                if(!this.sobreprecio) //Si la variable departamento esta vacia
                    this.errorMostrarMsjPublicidad.push("El nombre del sobreprecio no puede ir vacio.");

                if(this.errorMostrarMsjPublicidad.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorPublicidad = 1;

                return this.errorPublicidad;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.descripcion = '';
                this.errorPublicidad = 0;
                this.errorMostrarMsjPublicidad = [];

            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(medio, accion,data =[]){
                switch(medio){
                    case "sobreprecio":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar sobreprecio';
                                this.sobreprecio ='';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal =1;
                                this.tituloModal='Actualizar sobreprecio';
                                this.tipoAccion=2;
                                this.id=data['id'];
                                this.sobreprecio=data['nombre'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarSobreprecios(1,this.buscar,this.criterio);
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
