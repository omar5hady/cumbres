<template>
    <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item"><a href="#">Admin</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Fraccionamientos
                        <!--   Boton Nuevo    -->
                        <button type="button" @click="abrirModal('fraccionamiento','registrar')" class="btn btn-secondary">
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
                                      <option value="nombre">Fraccionamiento</option>
                                      <option value="tipo_proyecto">Tipo de Proyecto</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarFraccionamiento(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarFraccionamiento(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Fraccionamiento</th>
                                    <th>Tipo de proyecto</th>
                                    <th>Direccion</th>
                                    <th>Colonia</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="fraccionamiento in arrayFraccionamiento" :key="fraccionamiento.id">
                                    <td>
                                        <button type="button" @click="abrirModal('fraccionamiento','actualizar',fraccionamiento)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <button type="button" class="btn btn-danger btn-sm" @click="eliminarFraccionamiento(fraccionamiento)">
                                          <i class="icon-trash"></i>
                                        </button>
                                    </td>
                                    <td v-text="fraccionamiento.nombre"></td>
                                    <td v-text="fraccionamiento.tipo_proyecto"></td>
                                    <td v-text="fraccionamiento.calle"></td>
                                    <td v-text="fraccionamiento.colonia"></td>
                                    <td>
                                        <span class="badge badge-success">Activo</span>
                                    </td>
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
                                <div class="form-group row input-group">
                                    <label class="col-md-3 form-control-label" for="text-input">Proyecto</label>
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-3" v-model="tipo_proyecto">
                                      <option value="1">Lotificación</option>
                                      <option value="2">Departamento</option>
                                      <option value="3">Terreno</option>
                                    </select>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fraccionamiento</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre del fraccionamiento">
                                    </div>
                                </div>
                                <!--
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Proyecto</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="tipo_proyecto" class="form-control" placeholder="Tipo de proyecto">
                                    </div>
                                </div>-->
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Calle</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="calle" class="form-control" placeholder="Calle">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Colonia</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="colonia" class="form-control" placeholder="Colonia">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Estado</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="estado" class="form-control" placeholder="Estado">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Ciudad</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="ciudad" class="form-control" placeholder="Ciudad">
                                    </div>
                                </div>
                                <!-- Div para mostrar los errores que mande validerFraccionamiento -->
                                <div v-show="errorFraccionamiento" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjFraccionamiento" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarFraccionamiento()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarFraccionamiento()">Actualizar</button>
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
                nombre : '',
                tipo_proyecto : '',
                calle : '',
                colonia : '',
                estado : '',
                ciudad : '',
                arrayFraccionamiento : [],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                errorFraccionamiento : 0,
                errorMostrarMsjFraccionamiento : [],
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
            listarFraccionamiento(page, buscar, criterio){
                let me = this;
                var url = '/fraccionamiento?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayFraccionamiento = respuesta.fraccionamientos.data;
                    me.pagination = respuesta.pagination;
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
                me.listarFraccionamiento(page,buscar,criterio);
            },
            /**Metodo para registrar  */
            registrarFraccionamiento(){
                if(this.validarFraccionamiento()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/fraccionamiento/registrar',{
                    'nombre': this.nombre,
                    'tipo_proyecto': this.tipo_proyecto,
                    'calle': this.calle,
                    'colonia': this.colonia,
                    'estado': this.estado,
                    'ciudad': this.ciudad
                }).then(function (response){
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarFraccionamiento(1,'','fraccionamiento'); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Fraccionamiento agregado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            actualizarFraccionamiento(){
                if(this.validarFraccionamiento()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                let me = this;
                //Con axios se llama el metodo update de FraccionaminetoController
                axios.put('/fraccionamiento/actualizar',{
                    'nombre': this.nombre,
                    'tipo_proyecto': this.tipo_proyecto,
                    'calle': this.calle,
                    'colonia': this.colonia,
                    'estado': this.estado,
                    'ciudad': this.ciudad,
                    'id' : this.id
                }).then(function (response){
                    me.cerrarModal();
                    me.listarFraccionamiento(1,'','fraccionamiento');
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
            eliminarFraccionamiento(data =[]){
                this.id=data['id'];
                this.nombre=data['nombre'];
                this.tipo_proyecto=data['tipo_proyecto'];
                this.calle=data['calle'];
                this.colonia=data['colonia'];
                this.estado=data['estado'];
                this.ciudad=data['ciudad'];
                //console.log(this.fraccionamiento_id);
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

                axios.delete('/fraccionamiento/eliminar', 
                        {params: {'id': this.id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Fraccionamiento borrado correctamente.',
                        'success'
                        )
                        me.listarFraccionamiento(1,'','fraccionamiento');
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            validarFraccionamiento(){
                this.errorFraccionamiento=0;
                this.errorMostrarMsjFraccionamiento=[];

                if(!this.nombre) //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjFraccionamiento.push("El nombre del Fraccionamiento no puede ir vacio.");

                if(this.errorMostrarMsjFraccionamiento.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorFraccionamiento = 1;

                return this.errorFraccionamiento;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.fraccionamiento = '';
                this.user_alta = '';
                this.errorFraccionamiento = 0;
                this.errorMostrarMsjFraccionamiento = [];

            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(modelo, accion,data =[]){
                switch(modelo){
                    case "fraccionamiento":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Fraccionamiento';
                                this.nombre ='';
                                this.tipo_proyecto ='';
                                this.calle ='';
                                this.colonia ='';
                                this.estado ='';
                                this.ciudad ='';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal =1;
                                this.tituloModal='Actualizar Fraccionamiento';
                                this.tipoAccion=2;
                                this.id=data['id'];
                                this.nombre=data['nombre'];
                                this.tipo_proyecto=data['tipo_proyecto'];
                                this.calle=data['calle'];
                                this.colonia=data['colonia'];
                                this.estado=data['estado'];
                                this.ciudad=data['ciudad'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarFraccionamiento(1,this.buscar,this.criterio);
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
    }
    .div-error{
        display:flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>
