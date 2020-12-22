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
                        <i class="fa fa-align-justify"></i> Asignacion de proyectos
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    
                                    <input type="text" v-model="buscar" @keyup.enter="listarFraccionamiento(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarFraccionamiento(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive"> 
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Fraccionamiento</th>
                                
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="fraccionamiento in arrayFraccionamiento" :key="fraccionamiento.id">
                                        <td>
                                           <button type="button" @click="abrirModal(fraccionamiento)" class="btn btn-info btn-sm">
                                            <i class="icon-eye"></i>
                                            </button> &nbsp;
                                        </td>
                                        <td v-text="fraccionamiento.nombre"></td>
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
                            <h4 class="modal-title">Asignar asesor</h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!--<form action="" method="" enctype="multipart/form-data" class="form-horizontal">-->
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Proyecto</label>
                                    <div class="col-md-9">
                                        <input type="text" disabled v-model="proyecto" class="form-control" placeholder="Proyecto">
                                    </div>
                                </div>

                                
                                
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Asesores</label>
                                        <div class="col-md-6">
                                            <select class="form-control" v-model="asesor_id">
                                                <option value="">Seleccione</option>
                                                <option v-for="asesor in arrayAsesores" :key="asesor.id" :value="asesor.id" v-text="asesor.nombre + ' '+asesor.apellidos"></option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <button type="button" class="btn btn-success btn-sm" @click="addAsesor()">
                                                <i class="icon-plus"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="modal-header" v-if="arraySelecAsesores">
                                            <h5 class="modal-title"> Asesores asignados </h5>
                                        </div>
                                        <table class="table table-bordered table-striped table-sm">
                                            
                                            <tbody>
                                                <tr v-for="selectA in arraySelecAsesores" :key="selectA.id">
                                                    <td style="width:25%">
                                                        <button type="button" class="btn btn-danger btn-sm" @click="quitarAsesor(selectA.id)">
                                                        <i class="icon-trash"></i>
                                                        </button>
                                                    </td>
                                                    <td v-text="selectA.nombre + ' ' + selectA.apellidos" ></td>
                                                </tr>                               
                                            </tbody>
                                        </table>
                                    </div>
                                

                                
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            
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
                id:0,
               
               
                arrayFraccionamiento : [],
                arraySelecAsesores :[],
                arrayAsesores:[],

                asesor_id:'',
                proyecto:'',
                proyecto_id:'',
              
                
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
                buscar : '',
                modal:0,
              
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

            addAsesor(){       
                this.proceso=true;

                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/asesores/asignarProyecto',{
                    'proyecto': this.proyecto_id,
                    'asesor':this.asesor_id
                }).then(function (response){
                    me.proceso=false;
                    me.getAsesoresProyecto(me.proyecto_id);
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Asesor asignado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },

            quitarAsesor(id){
                swal({
                title: '¿Quitar asesor de este proyecto?',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si, quitar!'
                }).then((result) => {
                if (result.value) {
                    let me = this;

                axios.delete('/asesores/deleteAsignarProy', 
                        {params: {'id': id}}).then(function (response){
                        swal(
                        'Hecho!',
                        'El asesor se ha quitado de este proyecto',
                        'success'
                        )
                        me.getAsesoresProyecto(me.proyecto_id);
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },

            cambiarPagina(page, buscar, criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarFraccionamiento(page,buscar,criterio);
            },
            
             limpiarBusqueda(){
                let me=this;
                me.buscar= "";
            },

            abrirModal(data){
                this.proyecto_id = data['id'];
                this.proyecto = data['nombre'];
                this.modal = 1;
                this.getAsesoresProyecto(this.proyecto_id);

            },

            cerrarModal()
            {
                this.proyecto_id = '';
                this.proyecto = '';
                this.modal=0;
                this.arraySelecAsesores=[];
            },

            selectAsesores(){
                let me = this;
                me.arrayAsesores=[];
                var url = '/select/asesores?tipo=0&condicion=1';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayAsesores = respuesta.personas;
                })
                .catch(function (error) {
                    console.log(error);
                });
              
            },

            getAsesoresProyecto(id){
                let me = this;
                me.arraySelecAsesores=[];
                var url = '/asesores/getAsesoresProyecto?proyecto='+id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arraySelecAsesores = respuesta.asesores;
                })
                .catch(function (error) {
                    console.log(error);
                });
              
            },

        },
        mounted() {
            this.listarFraccionamiento(1,this.buscar,this.criterio);
            this.selectAsesores();
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
