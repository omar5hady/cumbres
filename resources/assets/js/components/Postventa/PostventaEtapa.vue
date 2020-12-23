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
                        <i class="fa fa-align-justify"></i> Carga de archivos
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-4" v-model="criterio" @click="limpiarBusqueda()">
                                        <option value="fraccionamientos.nombre">Fraccionamiento</option>
                                    </select>
                                    <input type="text" v-if="criterio=='fraccionamientos.nombre'"  v-model="buscar" @keyup.enter="listarEtapa(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarEtapa(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Etapa</th>
                                        <th>Fraccionamiento</th>
                                        <th>Carta de bienvenida</th>
                                        <th>Factibilidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="etapa in arrayEtapa" :key="etapa.id">
                                        <td class="td2" style="width:10%">
                                            <button id="btnFiles" class="dropdown-toggle btn-light btn btn-sm" data-toggle="dropdown" type="button"
                                                aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-cloud-upload"></i>
                                            </button>
                                            <div class="dropdown-menu" aria-labelleadby="btnFiles">
                                                <button style="background-color: #1E1E1E !important;" type="button" @click="abrirModal('etapa','subirArchivo',etapa)" class="dropdown-item btn btn-danger btn-sm">
                                                    <i class="icon-cloud-upload">&nbsp;Carta de bienvenida</i>
                                                </button>
                                                <button style="background-color: #1E1E1E !important;" type="button" @click="abrirModal('etapa','factibilidad',etapa)" class="dropdown-item btn btn-primary btn-sm">
                                                    <i class="icon-cloud-upload">&nbsp;Factibilidad</i>
                                                </button>
                                            </div>
                                            
                                        </td>
                                        <td class="td2" v-text="etapa.num_etapa"></td>
                                        <td class="td2" v-text="etapa.fraccionamiento"></td>
                                        <td class="td2" style="width:7%" v-if = "etapa.carta_bienvenida"><a class="btn btn-success btn-sm" v-bind:href="'/downloadCartaBienvenida/'+etapa.carta_bienvenida"><i class="fa fa-download fa-spin"></i></a></td>
                                        <td class="td2" v-else></td>
                                        <td class="td2" style="width:7%" v-if = "etapa.factibilidad"><a class="btn btn-primary btn-sm" v-bind:href="'/downloadFactibilidad/'+etapa.factibilidad"><i class="fa fa-download fa-spin"></i></a></td>
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

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Etapa</label>
                                <div class="col-md-4">
                                    <input type="text" readonly v-model="num_etapa" class="form-control" placeholder="# de etapa">
                                </div>
                            </div>

                                    
                            <div v-if="tipoAccion != 2">
                                <form  method="post" @submit="formSubmit" enctype="multipart/form-data">

                                        <strong>Sube aqui la carta de bienvenida para esta etapa (PDF)</strong>

                                        <input type="file" class="form-control" v-on:change="onImageChange">
                                        <br/>
                                        <button type="submit" class="btn btn-success">Cargar</button>
                                </form>
                            </div>

                            <div v-else>
                                <form  method="post" @submit="formSubmitFactibilidad" enctype="multipart/form-data">

                                        <strong>Sube aqui la factibilidad esta etapa (PDF)</strong>

                                        <input type="file" class="form-control" v-on:change="onImageChangeFactibilidad">
                                        <br/>
                                        <button type="submit" class="btn btn-success">Cargar</button>
                                </form>
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
                proceso : false,
                id:0,
                num_etapa : 0,
                carta_bienvenida: '',
                factibilidad: '',
                arrayEtapa : [],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
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
                buscar : '',
                buscar2: '',
                arrayFraccionamientos : [],

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

            onImageChange(e){
                console.log(e.target.files[0]);
                this.carta_bienvenida = e.target.files[0];
            },

            formSubmit(e) {
                e.preventDefault();
                let currentObj = this;
            
                let formData = new FormData();
                formData.append('carta_bienvenida', this.carta_bienvenida);
                let me = this;
                axios.post('/formSubmitCartaBienvenida/'+this.id, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Archivo guardado correctamente',
                        showConfirmButton: false,
                        timer: 2000
                        })
                    me.cerrarModal();
                    me.listarEtapa(me.pagination.current_page,me.buscar,'fraccionamiento.nombre');

                }).catch(function (error) {
                    currentObj.output = error;

                });

            },

            onImageChangeFactibilidad(e){
                console.log(e.target.files[0]);
                this.factibilidad = e.target.files[0];
            },

            formSubmitFactibilidad(e) {
                e.preventDefault();
                let currentObj = this;
            
                let formData = new FormData();
                formData.append('factibilidad', this.factibilidad);
                let me = this;
                axios.post('/formSubmitFactibilidad/'+this.id, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Archivo guardado correctamente',
                        showConfirmButton: false,
                        timer: 2000
                        })
                    me.cerrarModal();
                    me.listarEtapa(me.pagination.current_page,me.buscar,'fraccionamiento.nombre');

                }).catch(function (error) {
                    currentObj.output = error;

                });

            },


            /**Metodo para mostrar los registros */
            listarEtapa(page, buscar, criterio){
                let me = this;
                var url = '/etapa?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEtapa = respuesta.etapas.data;
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
                me.listarEtapa(page,buscar,criterio);
            },
            limpiarBusqueda(){
                let me=this;
                me.buscar= "";
                me.buscar2="";
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.num_etapa = '';
                this.factibilidad='';
                this.carta_bienvenida='';
            },

            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(modelo, accion,data =[]){
                switch(modelo){
                    case "etapa":
                    {
                        switch(accion){
                            case 'subirArchivo':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Subir carta de bienvenida';
                                this.num_etapa = data['num_etapa'];
                                this.id=data['id'];
                                this.carta_bienvenida=data['carta_bienvenida'];
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'factibilidad':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Subir factibilidad';
                                this.num_etapa = data['num_etapa'];
                                this.id=data['id'];
                                this.carta_bienvenida=data['carta_bienvenida'];
                                this.tipoAccion = 2;
                                break;
                            }
                           
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarEtapa(1,this.buscar,this.criterio);
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

    .table2 {
    margin: auto;
    border-collapse: collapse;
    overflow-x: auto;
    display: block;
    width: fit-content;
    max-width: 100%;
    box-shadow: 0 0 1px 1px rgba(0, 0, 0, .1);
    }

    .td2, .th2 {
    border: solid rgb(200, 200, 200) 1px;
    padding: .5rem;
    }

    .td2 {
    white-space: nowrap;
    border-bottom: none;
    color: rgb(20, 20, 20);
    }

    .td2:first-of-type, th:first-of-type {
    border-left: none;
    }

    .td2:last-of-type, th:last-of-type {
    border-right: none;
    } 
</style>
