<template >
    <main class="main" >
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card scroll-box">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i>Licencias

                        <!--   Boton   -->
                        
                        
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-5" v-model="criterio" >
                                        <option value="fraccionamientos.nombre">Proyecto</option>
                                        <option value="modelos.nombre">Modelo</option>
                                        <option value="arquitecto">Arquitecto</option>
                                        <option value="licencias.num_licencia">Num.Licencia</option>
                                    </select>                       
                                    <input type="text"  v-model="buscar" @keyup.enter="listarLicencias(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarLicencias(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    
                                    <th>Opciones</th>
                                    <th>Proyecto</th>
                                    <th>Manzana</th>
                                    <th># Lote</th>
                                    <th>Terreno mts&sup2;</th>
                                    <th>Construcción mts&sup2;</th>
                                    <th>Modelo</th>
                                    <th>Arquitecto</th>
                                    <th>Siembra</th>
                                    <th>Planos</th>
                                    <th>Ingreso</th>
                                    <th>Salida</th>
                                    <th>Num. Licencia</th>
                            
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="licencias in arrayLicencias" :key="licencias.id">
                                    
                                    <td >
                                        <center><button title="Editar" type="button" @click="abrirModal('lote','actualizar',licencias)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button></center> &nbsp;
                                    </td>
                                    <td v-text="licencias.proyecto"></td>
                                    <td v-text="licencias.manzana"></td>
                                    <td v-text="licencias.num_lote"></td>
                                    <td v-text="licencias.terreno"></td>
                                    <td v-text="licencias.construccion"></td>
                                    <td v-text="licencias.modelo"></td>
                                    <td v-text="'Arq. '+licencias.arquitecto"></td>
                                    <td v-text="licencias.siembra"></td>
                                    <td v-text="licencias.f_planos"></td>
                                    <td v-text="licencias.f_ingreso"></td>
                                    <td v-text="licencias.f_salida"></td>
                                    <td v-text="licencias.num_licencia"></td>
                                    
                                    
                                    
                                </tr>                               
                            </tbody>
                        </table>  
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page > 7">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(1,buscar,buscar2,buscar3,criterio)">Inicio</a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,buscar2,buscar3,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,buscar2,buscar3,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,buscar2,buscar3,criterio)">Sig</a>
                                </li>
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page<pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.last_page,buscar,buscar2,buscar3,criterio)">Ultimo</a>
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
                                    <label class="col-md-3 form-control-label" for="text-input">Planos</label>
                                    <div class="col-md-6">
                                       <input type="date" v-model="f_planos" class="form-control" >
                                    </div>
                                </div>

                                  <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Ingreso de licencia</label>
                                    <div class="col-md-6">
                                       <input type="date" v-model="f_ingreso" class="form-control" >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Salida</label>
                                    <div class="col-md-6">
                                       <input type="date" v-model="f_salida" class="form-control" >
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Numero de licencia</label>
                                    <div class="col-md-6">
                                       <input type="number" v-model="num_licencia" class="form-control" >
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button"  class="btn btn-primary" @click="actualizarLicencia()">Actualizar</button>
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
               
                id: 0,
                f_planos:'',
                f_ingreso:'',
                f_salida:'',
                num_licencia:0,
                arrayLicencias : [],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                errorLote : 0,
                errorMostrarMsjLote : [],
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'modelos.nombre', 
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
            },

        },

        
        methods : {

            onImageChange(e){

                console.log(e.target.files[0]);

                this.file = e.target.files[0];

            },
           

            /**Metodo para mostrar los registros */
            listarLicencias(page, buscar, criterio){
                let me = this;
                var url = '/licencias?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLicencias = respuesta.licencias.data;
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
                me.listarLicencias(page,buscar,criterio);
            },
            
            actualizarLicencia(){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/licencias/actualizar',{
                    'id':this.id,
                    'f_planos' : this.f_planos,
                    'f_ingreso' : this.f_ingreso,
                    'f_salida' : this.f_salida,
                    'num_licencia' : this.num_licencia,
                    
                    
                }).then(function (response){
                    me.cerrarModal();
                    me.listarLicencias(1,'','fraccionamientos.nombre');
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
            validarLote(){
                this.errorLote=0;
                this.errorMostrarMsjLote=[];

                if(!this.fraccionamiento_id) //Si la variable Lote esta vacia
                    this.errorMostrarMsjLote.push("El nombre del proyecto para el Lote no puede ir vacio.");

                if(this.errorMostrarMsjLote.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorLote = 1;

                return this.errorLote;
            },

            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.f_planos = '';
                this.f_ingreso = '';
                this.f_salida = '';
                this.num_licencia = '';
                
                
                this.errorLote = 0;
                this.errorMostrarMsjLote = [];

            },
            cerrarModal2(){
                this.modal2 = 0;
                this.tituloModal2 = '';
                this.lotes_ini=[];
                this.allSelected = false;
            },

            cerrarModal3(){
                this.modal3 = 0;
                this.tituloModal3 = '';
                this.tipoAccion = 1;
            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(licencias, accion,data =[]){
                switch(licencias){
                    case "lote":
                    {
                        switch(accion){
                           
                            case 'actualizar':
                            {
                                this.modal =1;
                                this.tituloModal='Actualizar Licencia';
                                this.f_planos=data['f_planos'];
                                this.f_ingreso=data['f_ingreso'];
                                this.f_salida=data['f_salida'];
                                this.num_licencia=data['num_licencia'];
                                this.id=data['id'];
                                break;
                            }

                           

                            
                            
                        }
                    }
                }

            }
        },
        mounted() {
            this.listarLicencias(1,this.buscar,this.criterio);
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
        position: center !important;
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
    .scroll-box {
            overflow-x: scroll;
            width: auto;
            padding: 1rem
        }
</style>
