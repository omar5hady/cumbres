<template>
    <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Precios por etapa
                        <!--   Boton Nuevo    -->
                        <button type="button" @click="abrirModal('precio_etapa','registrar')" class="btn btn-secondary">
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
                                      <option value="fraccionamientos.nombre">Proyecto</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarPrecioEtapa(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarPrecioEtapa(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Proyecto</th>
                                    <th>Etapa</th>
                                    <th>Precio mts&sup2; excedente</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="precioEtapa in arrayPrecioEtapa" :key="precioEtapa.id">
                                    <td style="width:20%">
                                        <button type="button" @click="abrirModal('precio_etapa','actualizar',precioEtapa)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" @click="eliminarDepartamento(precioEtapa)">
                                          <i class="icon-trash"></i>
                                        </button>
                                        <button type="button" @click="abrirModal('precio_etapa','modelos',precioEtapa)" class="btn btn-info btn-sm" title="Precios para modelos">
                                          <i class="icon-home"></i>
                                        </button>
                                    </td>
                                    <td v-text="precioEtapa.fraccionamiento"></td>
                                    <td v-text="precioEtapa.etapas"></td>
                                    <td v-text="'$ '+precioEtapa.precio_excedente"></td>
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
           
           <!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
           ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
           ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->

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
                                    <label class="col-md-3 form-control-label" for="text-input">Precio por (mts&sup2;) excedente</label>
                                    <div class="col-md-4" >
                                        <input type="text"  v-model="precio_excedente" class="form-control" placeholder="Precio (mts&sup2;) excedente">
                                    </div>
                                </div>

                                <!-- Div para mostrar los errores que mande validerDepartamento -->
                                <div v-show="errorPrecioEtapa" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjPrecioEtapa" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarPrecioEtapa()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarPrecioEtapa()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->


            <!--Inicio del modal 2-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar': modal2}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal2"></h4>
                            <button type="button" class="close" @click="cerrarModal2()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
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

                                <div class="form-group row" v-for="(modelos,index) in arrayModelos"  :key="modelos.id" :value="modelos.id">
                                    <label class="col-md-3 form-control-label" for="text-input" v-text="modelos.nombre"></label>
                                    <div class="col-md-4" >
                                        <input type="text"  v-model="arrayPreciosModelo[index]" class="form-control" placeholder="Precio modelos">
                                    </div>
                                </div>

                                <!-- Div para mostrar los errores que mande validerDepartamento -->
                                <div v-show="errorPrecioEtapa" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjPrecioEtapa" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal2()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarPrecioEtapa()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarPrecioEtapa()">Actualizar</button>
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
                fraccionamiento_id : '',
                etapa_id : '',
                precio_excedente : 0,
                arrayPrecioEtapa : [],
                arrayFraccionamientos:[],
                arrayModelos:[],
                arrayPreciosModelo:[],
                arrayEtapas:[],
                modal : 0,
                tituloModal : '',
                modal2 : 0,
                tituloModal2 : '',
                tipoAccion: 0,
                errorPrecioEtapa : 0,
                errorMostrarMsjPrecioEtapa : [],
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'fraccionamientos.nombre', 
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
            listarPrecioEtapa(page, buscar, criterio){
                let me = this;
                var url = '/precio_etapa?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPrecioEtapa = respuesta.precios_etapas.data;
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
                me.listarPrecioEtapa(page,buscar,criterio);
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
            selectModelos(buscar1, buscar2){
                let me = this;

                me.arrayModelos=[];
                var url = '/select_modelos_etapa?buscar1=' + buscar1 + '&buscar2='+ buscar2;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayModelos = respuesta.lotes;
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

            /**Metodo para registrar  */
            registrarPrecioEtapa(){
                if(this.validarPrecioEtapa()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/precio_etapa/registrar',{
                    'fraccionamiento_id': this.fraccionamiento_id,
                    'etapa_id': this.etapa_id,
                    'precio_excedente': this.precio_excedente
                }).then(function (response){
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarPrecioEtapa(1,'','fraccionamientos.nombre'); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Precio de etapa agregado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            actualizarPrecioEtapa(){
                if(this.validarPrecioEtapa()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                let me = this;
                //Con axios se llama el metodo update de DepartamentoController
                axios.put('/precio_etapa/actualizar',{
                    'id': this.id,
                    'fraccionamiento_id': this.fraccionamiento_id,
                    'etapa_id': this.etapa_id,
                    'precio_excedente': this.precio_excedente
                }).then(function (response){
                    me.cerrarModal();
                    me.listarPrecioEtapa(1,'','fraccionamientos.nombre');
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
            eliminarDepartamento(data =[]){
                this.id=data['id'];
                this.fraccionamiento_id=data['fraccionamiento_id'];
                this.etapa_id=data['etapa_id'];
                this.precio_excedente=data['precio_excedente']
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

                axios.delete('/precio_etapa/eliminar', 
                        {params: {'id': this.id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Departamento borrado correctamente.',
                        'success'
                        )
                        me.listarPrecioEtapa(1,'','fraccionamientos.nombre');
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            validarPrecioEtapa(){
                this.errorPrecioEtapa=0;
                this.errorMostrarMsjPrecioEtapa=[];

                if(!this.fraccionamiento_id) //Si la variable departamento esta vacia
                    this.errorMostrarMsjPrecioEtapa.push("Seleccione un proyecto.");

                if(!this.etapa_id) //Si la variable departamento esta vacia
                     this.errorMostrarMsjPrecioEtapa.push("Seleccione una etapa.");

                if(this.errorMostrarMsjPrecioEtapa.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorPrecioEtapa = 1;

                return this.errorPrecioEtapa;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.id = '';
                this.fraccionamiento_id = '';
                this.etapa_id = '';
                this.precio_excedente = '';
                this.errorPrecioEtapa = 0;
                this.errorMostrarMsjPrecioEtapa = [];

            },
            cerrarModal2(){
                this.modal2 = 0;
                this.tituloModal2 = '';
                this.id = '';
                this.fraccionamiento_id = '';
                this.etapa_id = '';
                this.errorMostrarMsjPrecioEtapa = [];

            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(modelo, accion,data =[]){
                switch(modelo){
                    case "precio_etapa":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar precio para etapa';
                                this.fraccionamiento_id ='';
                                this.etapa_id ='';
                                this.precio_excedente ='';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal =1;
                                this.tituloModal='Actualizar precio para etapa';
                                this.tipoAccion=2;
                                this.id=data['id'];
                                this.fraccionamiento_id=data['fraccionamiento_id'];
                                this.etapa_id=data['etapa_id'];
                                this.precio_excedente=data['precio_excedente'];
                                break;
                            }
                            case 'modelos':
                            {
                                //console.log(data);
                                this.modal2 =1;
                                this.tituloModal2='Precios Modelos';
                                this.tipoAccion=1;
                                this.id=data['id'];
                                this.fraccionamiento_id=data['fraccionamiento_id'];
                                this.etapa_id=data['etapa_id'];
                                this.precio_excedente=data['precio_excedente'];
                                break;
                            }
                        }
                    }
                }
                this.selectFraccionamientos();
                this.selectEtapa(this.fraccionamiento_id);
                this.selectModelos(this.fraccionamiento_id, this.etapa_id);
            }
        },
        mounted() {
            this.listarPrecioEtapa(1,this.buscar,this.criterio);
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
