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
                        <i class="fa fa-align-justify"></i> Partidas
                        <!--   Boton Nuevo    -->
                        <button type="button" @click="abrirModal('partida','registrar')" class="btn btn-secondary">
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
                                      <option value="partida">Partidas</option>
                                      <option value="fraccionamientos.nombre">Proyecto</option>
                                      <option value="modelos.nombre">Modelo</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarPartidas(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarPartidas(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Fraccionamiento</th>
                                    <th>Modelo</th>
                                    <th>Partida</th>
                                    <th>Costo</th>
                                    <th>Porcentaje</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="partida in arrayPartidas" :key="partida.id">
                                    <td style="width:9%">
                                        <button type="button" @click="abrirModal('partida','actualizar',partida)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <button type="button" class="btn btn-danger btn-sm" @click="eliminarPartida(partida)">
                                          <i class="icon-trash"></i>
                                        </button>
                                    </td>
                                    <td v-text="partida.proyecto"></td>
                                    <td v-text="partida.modelo"></td>
                                    <td v-text="partida.partida" style="width:30%"></td>
                                    <td v-text="partida.costo"></td>
                                    <td v-text="partida.porcentaje + '%'"></td>
                                    
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

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Proyecto</label>
                                    <div class="col-md-6">
                                       <select class="form-control" v-model="fraccionamiento_id" @click="selectModelo(fraccionamiento_id)">
                                            <option value="0">Seleccione</option>
                                            <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Modelo</label>
                                    <div class="col-md-6">
                                       <select class="form-control" v-model="modelo_id">
                                            <option value="0">Seleccione</option>
                                            <option v-for="modelos in arrayModelos" :key="modelos.id" :value="modelos.id" v-text="modelos.nombre"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Partida</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="partida" class="form-control" placeholder="Nombre de la Partida">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Costo</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="costo" maxlength="9" v-on:keypress="isNumber(event)" class="form-control" placeholder="Costo de la Partida">
                                    </div>
                                </div>

                                <!-- Div para mostrar los errores que mande validerDepartamento -->
                                <div v-show="erroPartida" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjPartida" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarPartida()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarPartida()">Actualizar</button>
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
                partida : '',
                costo : 0,
                porcentaje : 0,
                modelo_id : 0,
                fraccionamiento_id:0,
                arrayPartidas : [],
                arrayFraccionamientos : [],
                arrayModelos : [],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                erroPartida : 0,
                errorMostrarMsjPartida : [],
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'partida', 
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
            listarPartidas(page, buscar, criterio){
                let me = this;
                var url = '/partidas?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPartidas = respuesta.partidas.data;
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
                me.listarPartidas(page,buscar,criterio);
            },
            /**Metodo para registrar  */
            registrarPartida(){
                if(this.validarPartida()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                let me = this;
                //Con axios se llama el metodo store de PartidaController
                axios.post('/partidas/registrar',{
                    'partida': this.partida,
                    'modelo_id': this.modelo_id,
                    'costo':this.costo,
                }).then(function (response){
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarPartidas(1,'','partida'); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Partida agregado correctamente',
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
            actualizarPartida(){
                if(this.validarPartida()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                let me = this;
                //Con axios se llama el metodo update de PartidaController
                axios.put('/partidas/actualizar',{
                    'partida': this.partida,
                    'modelo_id': this.modelo_id,
                    'costo':this.costo,
                    'id' : this.id
                }).then(function (response){
                    me.cerrarModal();
                    me.listarPartidas(1,'','partida');
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
            eliminarPartida(data =[]){
                this.id=data['id'];
                this.partida=data['partida'];
                this.modelo_id=data['modelo_id'];
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

                axios.delete('/partidas/eliminar', 
                        {params: {'id': this.id,'modelo_id':this.modelo_id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Partida borrada correctamente.',
                        'success'
                        )
                        me.listarPartidas(1,'','partida');
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            validarPartida(){
                this.erroPartida=0;
                this.errorMostrarMsjPartida=[];

                if(!this.partida) //Si la variable departamento esta vacia
                    this.errorMostrarMsjPartida.push("El nombre de la partida no puede ir vacio.");

                if(this.errorMostrarMsjPartida.length)//Si el mensaje tiene almacenado algo en el array
                    this.erroPartida = 1;

                return this.erroPartida;
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
            selectModelo(buscar){
                let me = this;
              
                me.arrayModelos=[];
                var url = '/select_modelo_proyecto?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayModelos = respuesta.modelos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.partida = '';
                this.modelo_id = 0;
                this.costo=0;
                this.fraccionamiento_id=0;
                this.erroPartida = 0;
                this.errorMostrarMsjPartida = [];

            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(modelo, accion,data =[]){
                switch(modelo){
                    case "partida":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Partida';
                                this.partida ='';
                                this.fraccionamiento_id = 0;
                                this.modelo_id =0;
                                this.porcentaje=0;
                                this.costo=0;
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal =1;
                                this.tituloModal='Actualizar Partida';
                                this.tipoAccion=2;
                                this.id=data['id'];
                                this.partida=data['partida'];
                                this.modelo_id=data['modelo_id'];
                                this.fraccionamiento_id=data['fraccionamiento_id'];
                                this.costo = data['costo'];
                                break;
                            }
                        }
                    }
                }
                this.selectFraccionamientos();
                this.selectModelo(this.fraccionamiento_id);
            }
        },
        mounted() {
            this.listarPartidas(1,this.buscar,this.criterio);
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
    .scroll-box {
            overflow-x: scroll;
            width: auto;
            padding: 1rem;
            
        }
</style>
