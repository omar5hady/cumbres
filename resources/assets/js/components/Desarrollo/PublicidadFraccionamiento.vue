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
                        <i class="fa fa-align-justify"></i> Fraccionamientos
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-5" v-model="criterio" @click="limpiarBusqueda()">
                                      <option value="nombre">Fraccionamiento</option>
                                      <option value="tipo_proyecto">Tipo de Proyecto</option>
                                    </select>
                                    
                                    
                                    <select class="form-control col-md-5" v-if="criterio=='tipo_proyecto'" v-model="buscar" @keyup.enter="listarFraccionamiento(1,buscar,criterio)" >
                                        <option value="1">Lotificación</option>
                                        <option value="2">Departamento</option>
                                        <option value="3">Terreno</option>
                                    </select>
                                    <input type="text" v-else v-model="buscar" @keyup.enter="listarFraccionamiento(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
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
                                        <th>Tipo de proyecto</th>
                                        <th>Direccion</th>
                                        <th>Logo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="fraccionamiento in arrayFraccionamiento" :key="fraccionamiento.id">
                                        <td>
                                           <button type="button" @click="abrirModal('fraccionamiento','subirArchivoLogo',fraccionamiento)" class="btn btn-info btn-sm">
                                            <i class="icon-cloud-upload"></i>
                                            </button> &nbsp;
                                        </td>
                                        <td v-text="fraccionamiento.nombre"></td>
                                        <td v-if="fraccionamiento.tipo_proyecto==1" v-text="'Lotificación'"></td>
                                        <td v-if="fraccionamiento.tipo_proyecto==2" v-text="'Departamento'"></td>
                                        <td v-if="fraccionamiento.tipo_proyecto==3" v-text="'Terreno'"></td>
                                        <td v-text="fraccionamiento.calle"></td>
                                        <td class="td2" style="width:7%" v-if = "fraccionamiento.logo_fracc"><a class="btn btn-success btn-sm" v-bind:href="'/downloadLogoFraccionamiento/'+fraccionamiento.logo_fracc"><i class="fa fa-download fa-spin"></i></a></td>
                                        <td class="td2" v-else></td>
            
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
        
            
            <!-- Modal para la carga de los archivos-->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal4}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal4"></h4>
                            <button type="button" class="close" @click="cerrarModal4()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                     <div style="float:left;">
                            <form  method="post" @submit="formSubmitLogo" enctype="multipart/form-data">

                                    <strong>Sube aqui el logo del fraccionamiento</strong>

                                    <input type="file" class="form-control" v-on:change="onImageChangeLogo">
                                    <br/>
                                    <button type="submit" class="btn btn-success">Cargar</button>
                            </form>
                     </div>

                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal4()">Cerrar</button>
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
                nombre : '',
                tipo_proyecto : 0,
                calle : '',
               
                archivo_logo: '',
               
                arrayFraccionamiento : [],
                modal : 0,
                modal4: 0,
              
                tituloModal4: '',
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
                buscar : '',
              
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

            //funciones para carga de los logo del fraccionamiento 

            onImageChangeLogo(e){
                console.log(e.target.files[0]);
                this.archivo_logo = e.target.files[0];
            },

            formSubmitLogo(e) {
                e.preventDefault();
                let currentObj = this;
            
                let formData = new FormData();
                formData.append('archivo_logo', this.archivo_logo);
                let me = this;
                axios.post('/formSubmitLogoFraccionamiento/'+this.id, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Logo guardado correctamente',
                        showConfirmButton: false,
                        timer: 2000
                        })
                    me.cerrarModal4();
                   me.listarFraccionamiento(1,'','fraccionamiento');

                }).catch(function (error) {
                    currentObj.output = error;

                });

            },


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
            
             limpiarBusqueda(){
                let me=this;
                me.buscar= "";
            },
    
     
            cerrarModal4(){
                this.modal4 = 0;
                this.tituloModal4 = '';
                this.archivo_logo = '';
                this.errorFraccionamiento = 0;
                this.errorMostrarMsjFraccionamiento = [];

            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(modelo, accion,data =[]){
                switch(modelo){
                    case "fraccionamiento":
                    {
                        switch(accion){  
                            case 'subirArchivoLogo':
                            {
                                this.modal4 =1;
                                this.tituloModal4='Subir Logo';
                                this.id=data['id'];
                                this.archivo_logo=data['logo_fracc'];
                                break;
                            }
                        }
                    }
                }
                //this.selectCiudades(this.estado);
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
