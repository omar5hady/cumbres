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
                        <i class="fa fa-align-justify"></i> Descarga de Prediales y Actas de termino
                        <!--   Boton Nuevo    -->
                        
                        <!-- <a :href="'/etapa/excel?buscar=' + buscar + '&buscar2=' + buscar2 + '&criterio=' + criterio"  class="btn btn-success"><i class="fa fa-file-text"></i> Excel </a> -->
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <select class="form-control" @keyup.enter="listarDescargas(1)" v-model="b_busqueda" >
                                        <option value="licencias.fecha_predial">Prediales</option>
                                        <option value="licencias.fecha_licencia">Licencias</option>
                                        <option value="licencias.fecha_acta">Actas de termino</option>
                                    </select>

                                    <select class="form-control"  @click="selectEtapas(b_proyecto)" v-model="b_proyecto" >
                                        <option value="">Fraccionamiento</option>
                                        <option v-for="proyecto in arrayFraccionamientos" :key="proyecto.id" :value="proyecto.id" v-text="proyecto.nombre"></option>
                                    </select>

                                    <select class="form-control" v-model="b_etapa" @click="selectManzanas(b_proyecto,b_etapa)">
                                        <option value="">Etapa</option>
                                        <option v-for="etapa in arrayEtapas" :key="etapa.id" :value="etapa.id" v-text="etapa.num_etapa"></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <select class="form-control" @click="selectLotesManzana(b_proyecto,b_etapa,b_manzana)" @keyup.enter="listarDescargas(1)" v-model="b_manzana" >
                                        <option value="">Manzana</option>
                                        <option v-for="manzana in arrayManzanas" :key="manzana.manzana" :value="manzana.manzana" v-text="manzana.manzana"></option>
                                    </select>
                                    <select class="form-control" @keyup.enter="listarDescargas(1)" v-model="b_lote" >
                                        <option value="">Lote</option>
                                        <option v-for="lotes in arrayLotes" :key="lotes.id" :value="lotes.num_lote" v-text="lotes.num_lote"></option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="date" v-model="b_fecha1" @keyup.enter="listarHistorialDep(1)" class="form-control" >
                                    <input type="date" v-model="b_fecha2" @keyup.enter="listarHistorialDep(1)" class="form-control" >
                                    <button type="submit" @click="listarDescargas(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Fraccionamiento</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th>Lote</th>
                                        <th>Modelo</th>
                                        <th>Fecha de subida </th>
                                        <th>Archivo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="lote in arrayLotes" :key="lote.id">
                                        <td v-text="lote.proyecto"></td>
                                        <td v-text="lote.num_etapa"></td>
                                        <td v-text="lote.manzana"></td>
                                        <td v-text="lote.num_lote"></td>
                                        <td v-text="lote.modelo"></td>
                                        <td v-text="lote.fecha"></td>
                                        <td>
                                            <a v-if="criterio == 'licencias.fecha_predial' " title="Descargar predial" class="btn btn-success btn-sm" v-bind:href="'/downloadPredial/'+lote.archivo">
                                                <i class="fa fa-arrow-circle-down fa-lg"></i>
                                            </a>
                                            <a v-if="criterio == 'licencias.fecha_licencia' " title="Descargar licencia" class="btn btn-dark btn-sm" v-bind:href="'/downloadLicencias/'+lote.archivo">
                                                <i class="fa fa-arrow-circle-down fa-lg"></i>
                                            </a>
                                            <a v-if="criterio == 'licencias.fecha_acta' " title="Descargar acta de termino" class="btn btn-primary btn-sm" v-bind:href="'/downloadActa/'+lote.archivo">
                                                <i class="fa fa-arrow-circle-down fa-lg"></i>
                                            </a>
                                        </td>
                                    </tr>                               
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1)">Sig</a>
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
        data(){
            return{
                contador : 0,
                arrayLotes : [],
                arrayEtapas : [],
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                arrayFraccionamientos : [],
                arrayManzanas : [],
                arrayLotes : [],
                b_proyecto: '',
                b_etapa : '',
                b_manzana : '',
                b_lote : '',
                b_fecha1 : '',
                b_fecha2 : '',
                b_busqueda : 'licencias.fecha_licencia',
                criterio : '',
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
            listarDescargas(page){
                let me = this;
                var url = '/licencias/indexDescargas?page=' + page + '&busqueda=' + me.b_busqueda + '&proyecto=' + me.b_proyecto + 
                        '&etapa=' + me.b_etapa + '&manzana=' + me.b_manzana + '&lote=' + me.b_lote + '&fecha1=' + me.b_fecha1 + '&fecha2=' + me.b_fecha2;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLotes = respuesta.lotes.data;
                    me.pagination = respuesta.pagination;
                    me.criterio = respuesta.criterio;

                })
                .catch(function (error) {
                    console.log(error);
                });
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

            selectEtapas(buscar){
                let me = this;
                me.b_etapa="";
                me.b_manzana="";
                me.b_lote="";
                
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
            //Select todas las manzanas
            selectManzanas(buscar1, buscar2){
                let me = this;
                me.b_manzana="";
                me.b_lote="";

                me.arrayManzanas=[];
                var url = '/select_manzanas_etapa?buscar=' + buscar1 + '&buscar1='+ buscar2;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayManzanas = respuesta.manzana;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            //Select todos los lotes

            selectLotesManzana(buscar1, buscar2, buscar3){
                let me = this;
                me.b_lote="";

                me.arrayLotes=[];
                var url = '/select_lotes_manzana?buscar=' + buscar1 + '&buscar1='+ buscar2 + '&buscar2='+ buscar3;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLotes = respuesta.lote_manzana;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            cambiarPagina(page){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarDescargas(page);
            },
        
            // isNumber: function(evt) {
            //     evt = (evt) ? evt : window.event;
            //     var charCode = (evt.which) ? evt.which : evt.keyCode;
            //     if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
            //         evt.preventDefault();;
            //     } else {
            //         return true;
            //     }
            // },

        },
        mounted() {
            this.listarDescargas(1);
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
