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
                        <i class="fa fa-align-justify"></i> Inicio de Obra
                        <!--   Boton Nuevo    -->
                        <button type="button" @click="abrirModal('lotes','enviar')" class="btn btn-secondary">
                            <i class="icon-envelope-letter"></i>&nbsp;Enviar inicio de obra
                        </button>
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-4" v-model="criterio">
                                      <option value="fraccionamientos.nombre">Fraccionamiento</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarLotesIniObra(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarLotesIniObra(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Seleccionar</th>
                                    <th>Fraccionamiento</th>
                                    <th>Etapa</th>
                                    <th>Manzana</th>
                                    <th># Lote</th>
                                    <th>Modelo</th>
                                    <th>Terreno mts&sup2;</th>
                                    <th>Construcción mts&sup2;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="lote in arrayLotes" :key="lote.id">
                                    <td style="width:8%">
                                        <button type="button" @click="abrirModal('departamento','actualizar',departamento)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                    </td>
                                    
                                    <td v-text="lote.proyecto"></td>
                                    <td v-text="lote.etapas"></td>
                                    <td v-text="lote.manzana"></td>
                                    <td v-text="lote.num_lote"></td>
                                    <td v-text="lote.modelo"></td>
                                    <td v-text="lote.terreno"></td>
                                    <td v-text="lote.construccion"></td>
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
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de inicio</label>
                                    <div class="col-md-6">
                                        <input type="date" v-model="f_ini"  class="form-control" placeholder="Fecha de inicio">
                                    </div>
                                </div>
                                   <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de terminacion</label>
                                    <div class="col-md-6">
                                        <input type="date" v-model="f_fin" class="form-control" placeholder="Fecha de terminacion">
                                    </div>
                                </div>
                                <!-- Div para mostrar los errores que mande validerDepartamento -->
                                <div v-show="errorLotesIniObra" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjLotesIniObra" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarInicioObra()">Enviar</button>
                            
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
                f_ini : '',
                f_fin : '',
                arrayLotes : [],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                errorLotesIniObra : 0,
                errorMostrarMsjLotesIniObra : [],
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
            listarLotesIniObra(page, buscar, criterio){
                let me = this;
                var url = '/lote_aviso?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLotes = respuesta.lotes.data;
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
                me.listarLotesIniObra(page,buscar,criterio);
            },
            /**Metodo para registrar  */
            registrarInicioObra(){
                if(this.validarInicioObra()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                
            },
            validarInicioObra(){
                this.errorLotesIniObra=0;
                this.errorMostrarMsjLotesIniObra=[];

                if(!this.f_ini) //Si la variable departamento esta vacia
                    this.errorMostrarMsjLotesIniObra.push("Seleccionar la fecha de inicio.");
                
                if(!this.f_fin) //Si la variable departamento esta vacia
                    this.errorMostrarMsjLotesIniObra.push("Seleccionar la fecha de termino.");
                
                if(this.f_fin<this.f_ini) //Si la variable departamento esta vacia
                    this.errorMostrarMsjLotesIniObra.push("La fecha de termino debe ser mayor a la fecha de inicio.");

                if(this.errorMostrarMsjLotesIniObra.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorLotesIniObra = 1;

                return this.errorLotesIniObra;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.departamento = '';
                this.user_alta = '';
                this.errorLotesIniObra = 0;
                this.errorMostrarMsjLotesIniObra = [];

            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(modelo, accion,data =[]){
                switch(modelo){
                    case "lotes":
                    {
                        switch(accion){
                            case 'enviar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Enviar aviso de obra';
                                this.f_ini ='';
                                this.f_fin ='';
                                this.tipoAccion = 1;
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarLotesIniObra(1,this.buscar,this.criterio);
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
