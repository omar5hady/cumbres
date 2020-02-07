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
                        <i class="fa fa-align-justify"></i> Descarga de documentos
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-5" v-model="criterio">
                                      <option value="fraccionamientos.id">Proyecto</option>
                                    </select>
                                    <select class="form-control" v-if="criterio=='fraccionamientos.id'" v-model="b_fraccionamiento" @click="selectEtapa(b_fraccionamiento), selectModelo(b_fraccionamiento)">
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>
                                    <select class="form-control" v-if="criterio=='fraccionamientos.id'" v-model="b_etapa"> 
                                        <option value="">Etapa</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                    </select>
                                    <select class="form-control" v-if="criterio=='fraccionamientos.id'" v-model="b_modelo">
                                        <option value="">Modelo</option>
                                        <option v-for="modelos in arrayModelos" :key="modelos.id" :value="modelos.id" v-text="modelos.nombre"></option>
                                    </select>
                                    
                                    <button type="submit" @click="listarArchivos(1,b_fraccionamiento,b_etapa,b_modelo,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Fraccionamiento</th>
                                        <th>Etapa</th>
                                        <th>Modelo</th>
                                        <th>Reglamento de la etapa</th>
                                        <th>Carta de servicios</th>
                                        <th>Carta servicios de telecomunicaciones</th>
                                        <th>Catálogo de especificaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="archivos in arrayArchivos" :key="archivos.id">
                                  
                                        <td v-text="archivos.proyecto"></td>
                                        <td v-text="archivos.num_etapa"></td>
                                        <td v-text="archivos.modelo"></td>
                                        <template>
                                            <td v-if="archivos.archivo_reglamento != null">
                                                <a class="btn btn-danger btn-sm" v-bind:href="'/archivos/reglamentoEtapa/'+archivos.etapaID">Descarga</a>
                                            </td>
                                            <td v-else>
                                                Aun sin cargar
                                            </td>
                                        </template>
                                        <template>
                                            <td v-if="archivos.plantilla_carta_servicios != null && archivos.costo_mantenimiento != null">
                                                <a class="btn btn-primary btn-sm" v-bind:href="'/archivos/cartaServicios/'+archivos.etapaID" target="_blank">Visualizar</a>
                                            </td>
                                            <td v-else>
                                                Aun sin cargar
                                            </td>
                                        </template>
                                        <template>
                                            <td v-if="archivos.plantilla_telecom != null && archivos.empresas_telecom != null">
                                                <a class="btn btn-primary btn-sm" v-bind:href="'/archivos/cartaServiciosTelecomunicaciones/'+archivos.etapaID" target="_blank">Visualizar</a>
                                            </td>
                                            <td v-else>
                                                Aun sin cargar
                                            </td>
                                        </template>
                                        <template>
                                            <td v-if="archivos.archivo != null">
                                                <a class="btn btn-danger btn-sm" v-bind:href="'/archivos/catalogoEspecificaciones/'+archivos.modeloID">Descarga</a>
                                            </td>
                                            <td v-else>
                                                Aun sin cargar
                                            </td>
                                        </template>

                                    </tr>                               
                                </tbody>
                            </table>  
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,b_fraccionamiento,b_etapa,b_modelo,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,b_fraccionamiento,b_etapa,b_modelo,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,b_fraccionamiento,b_etapa,b_modelo,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            
        </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    export default {
         props:{
            rolId:{type: String}
        },
		
        data(){
            return{
                proceso : false,
                id:0,
                arrayFraccionamientos: [],
                arrayEtapas: [],
                arrayModelos: [],
                arrayArchivos: [],

                b_fraccionamiento: '',
                b_etapa: '',
                b_modelo:  '',
                
                errorModelo : 0,
                errorMostrarMsjModelo : [],
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'fraccionamientos.id', 
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

            /**Metodo para mostrar los registros */
            listarArchivos(page, b_fraccionamiento,b_etapa,b_modelo, criterio){
                let me = this;
                var url = '/archivos/indexDocs?page=' + page + '&b_fraccionamiento=' + b_fraccionamiento + '&b_etapa=' + b_etapa + '&b_modelo=' + b_modelo + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayArchivos = respuesta.archivos.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page, b_fraccionamiento,b_etapa,b_modelo, criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarArchivos(page,b_fraccionamiento,b_etapa,b_modelo,criterio);
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
        },
        mounted() {
            this.listarArchivos(1,this.b_fraccionamiento,this.b_etapa,this.b_modelo,this.criterio);
            this.selectFraccionamientos();
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
