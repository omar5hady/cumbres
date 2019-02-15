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
                        <i class="fa fa-align-justify"></i> Paquetes
                        <!--   Boton Nuevo    -->
                        <button type="button" @click="abrirModal('paquete','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-4" v-model="criterio">
                                        <option value="paquetes.nombre">Paquete</option>
                                        <option value="fraccionamientos.nombre">Proyecto</option>
                                        <option value="etapas.num_etapa">Etapa</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarPaquetes(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarPaquetes(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Proyecto</th>
                                        <th>Etapa</th>
                                        <th>Paquete</th>
                                        <th>Descripcion</th>
                                        <th>Precio $</th>
                                        <th>Inicio</th>
                                        <th>Fin</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="paquete in arrayPaquete" :key="paquete.id">
                                        <td style="width:10%">
                                            <button type="button" @click="abrirModal('paquete','actualizar',paquete)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                            </button> &nbsp;
                                            <button type="button" class="btn btn-danger btn-sm" @click="eliminarPaquete(paquete)">
                                            <i class="icon-trash"></i>
                                            </button>
                                        </td>
                                        <td v-text="paquete.fraccionamiento" ></td>
                                        <td v-text="paquete.etapa" ></td>
                                        <td v-text="paquete.nombre" ></td>
                                        <td v-text="paquete.descripcion" ></td>
                                        <td v-text="paquete.costo" ></td>
                                        <td v-text="paquete.v_ini" ></td>
                                        <td v-text="paquete.v_fin" ></td>
                                        <td v-if="paquete.is_active == '1'">
                                            <span class="badge badge-success">Activo</span>
                                        </td>
                                        <td v-if="paquete.is_active == '0'">
                                            <span class="badge badge-danger">Desactivado</span>
                                        </td>
                                    </tr>                               
                                </tbody>
                            </table>
                        </div>
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
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre del Paquete</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Paquete">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Proyecto</label>
                                    <div class="col-md-6">
                                       <select class="form-control" v-model="fraccionamiento_id" @click="selectEtapa(fraccionamiento_id)" >
                                            <option value="0">Seleccione</option>
                                            <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Etapa</label>
                                    <div class="col-md-6">
                                       <select class="form-control" v-model="etapa_id">
                                            <option value="0">Seleccione</option>
                                            <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de inicio</label>
                                    <div class="col-md-6">
                                        <input type="date" v-model="v_ini"  class="form-control" placeholder="Inicio">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de vencimiento</label>
                                    <div class="col-md-6">
                                        <input type="date" v-model="v_fin" class="form-control" placeholder="Vencimiento">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Precio $</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="costo" class="form-control" placeholder="Precio del paquete">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Descripcion</label>
                                    <div class="col-md-6">
                                        <textarea rows="5" cols="30" v-model="descripcion" class="form-control" placeholder="Descripcion del paquete"></textarea>
                                    </div>
                                </div>


                                <!-- Div para mostrar los errores que mande validerPaquete -->
                                <div v-show="errorPaquete" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjPaquete" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarPaquetes()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarPaquetes()">Actualizar</button>
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
                proceso:false,
                id :0,
                fraccionamiento_id:0,
                etapa_id : 0,
                nombre : '',
                v_ini : new Date().toISOString().substr(0, 10),
                v_fin : '',
                costo : '',
                descripcion : '',
                arrayPaquete : [],
                arrayFraccionamientos: [],
                arrayEtapas: [],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                errorPaquete : 0,
                errorMostrarMsjPaquete : [],
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'paquetes.nombre', 
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
            listarPaquetes(page, buscar, criterio){
                let me = this;
                var url = '/paquete?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPaquete = respuesta.paquetes.data;
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
                me.listarPaquetes(page,buscar,criterio);
            },
            /**Metodo para registrar  */
            registrarPaquetes(){
                if(this.validarPaquetes() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }
                this.proceso=true;

                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/paquete/registrar',{
                    'fraccionamiento_id': this.fraccionamiento_id,
                    'etapa_id': this.etapa_id,
                    'nombre': this.nombre,
                    'v_ini': this.v_ini,
                    'v_fin': this.v_fin,
                    'costo': this.costo,
                    'descripcion': this.descripcion
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarPaquetes(1,'','nombre'); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Paquete asignado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            actualizarPaquetes(){
                if(this.validarPaquetes() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;

                let me = this;
                //Con axios se llama el metodo update de DepartamentoController
                axios.put('/paquete/actualizar',{
                   'fraccionamiento_id': this.fraccionamiento_id,
                    'etapa_id': this.etapa_id,
                    'nombre': this.nombre,
                    'v_ini': this.v_ini,
                    'v_fin': this.v_fin,
                    'costo': this.costo,
                    'descripcion': this.descripcion,
                    'id': this.id
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal();
                    me.listarPaquetes(1,'','nombre');
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
            eliminarPaquete(data =[]){
                this.id=data['id'];
                this.fraccionamiento_id=data['fraccionamiento_id'];
                this.etapa_id=data['etapa_id'];
                this.nombre=data['nombre'];
                this.v_ini=data['v_ini'];
                this.v_fin=data['v_fin'];
                this.costo=data['costo'];
                this.descripcion=data['descripcion'];
                //console.log(this.departamento_id);
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

                axios.delete('/paquete/eliminar', 
                        {params: {'id': this.id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Paquete borrado correctamente.',
                        'success'
                        )
                        me.listarPaquetes(1,'','nombre');
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
             selectFraccionamientos(){
                let me = this;

                me.arrayFraccionamientos=[];
                var url = '/select_fraccionamiento';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayFraccionamientos = respuesta.fraccionamientos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
             selectEtapa(buscar){
                let me = this;
                
                me.arrayEtapas=[];
                var url = '/select_etapa_proyecto?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEtapas = respuesta.etapas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            validarPaquetes(){
                this.errorPaquete=0;
                this.errorMostrarMsjPaquete=[];

                if(!this.nombre) //Si la variable departamento esta vacia
                    this.errorMostrarMsjPaquete.push("El nombre del paquete no puede ir vacio.");

                if(!this.fraccionamiento_id) //Si la variable departamento esta vacia
                    this.errorMostrarMsjPaquete.push("Seleccionar fraccionamiento.");

                if(!this.etapa_id) //Si la variable departamento esta vacia
                    this.errorMostrarMsjPaquete.push("Seleccionar etapa");

                if(!this.costo) //Si la variable departamento esta vacia
                    this.errorMostrarMsjPaquete.push("El costo del paquete no puede ir vacio.");
                
                if(!this.descripcion) //Si la variable departamento esta vacia
                    this.errorMostrarMsjPaquete.push("La descripción del paquete no puede ir vacia.");

                if(this.errorMostrarMsjPaquete.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorPaquete = 1;

                return this.errorPaquete;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.fraccionamiento_id = '';
                this.etapa_id = '';
                this.nombre = '';
                this.v_ini = new Date().toISOString().substr(0, 10);
                this.v_fin = '';
                this.costo = '';
                this.descripcion = '';
                this.errorPaquete = 0;
                this.errorMostrarMsjPaquete = [];
                this.proceso=false;

            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(modelo, accion,data =[]){
                switch(modelo){
                    case "paquete":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Departamento';
                                this.fraccionamiento_id =0;
                                this.etapa_id =0;
                                this.nombre = '';
                                // this.v_ini ='';
                                this.v_fin = '';
                                this.costo = 0;
                                this.descripcion = '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal =1;
                                this.tituloModal='Actualizar Departamento';
                                this.tipoAccion=2;
                                this.id=data['id'];
                                this.fraccionamiento_id=data['fraccionamiento_id'];
                                this.etapa_id=data['etapa_id'];
                                this.nombre=data['nombre'];
                                this.v_ini=data['v_ini'];
                                this.v_fin=data['v_fin'];
                                this.costo=data['costo'];
                                this.descripcion=data['descripcion'];
                                break;
                            }
                        }
                    }
                }

                this.selectFraccionamientos();
                this.selectEtapa(this.fraccionamiento_id);
            }
        },
        mounted() {
            this.listarPaquetes(1,this.buscar,this.criterio);
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
        display:flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>
