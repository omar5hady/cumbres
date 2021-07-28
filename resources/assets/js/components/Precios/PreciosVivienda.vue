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
                        <i class="fa fa-align-justify"></i> Precios vivienda
                        <!--   Boton descargar excel    
                         <a class="btn btn-success" v-bind:href="'/lotes/resume_excel_lotes_disp'">
                            <i class="fa fa-file-text"></i>&nbsp; Descargar relacion
                        </a>
                        -->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    
                                    <select class="form-control"  @change="selectEtapa(buscar)" v-model="buscar" >
                                        <option value="">Proyecto</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.nombre" v-text="fraccionamientos.nombre"></option>
                                    </select>

                                    <select class="form-control"  v-model="b_etapa" > 
                                        <option value="">Etapa</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.num_etapa" v-text="etapas.num_etapa"></option>
                                    </select>

                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" v-model="b_manzana" @keyup.enter="listarLote(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="form-control" placeholder="Manzana">
                                    <input type="text" v-model="b_lote" @keyup.enter="listarLote(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="form-control" placeholder="# Lote">
                                    <input type="text" v-model="b_modelo" @keyup.enter="listarLote(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="form-control" placeholder="Modelo">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <button type="submit" @click="listarLote(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    <a  :href="'/preciosVivienda/excel?buscar=' + buscar + '&b_etapa=' + b_etapa + '&b_manzana=' + b_manzana 
                                        + '&b_lote=' + b_lote + '&modelo=' + b_modelo
                                        + '&criterio=' + criterio"  class="btn btn-success"><i class="fa fa-file-text"></i> Excel</a>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        
                                        <th>Proyecto</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th># Lote</th>
                                        <th>Modelo</th>
                                        <th>Avance</th>
                                        <th>Status</th>
                                        <th>Precio Base</th>
                                        <th class="td2" style="width:50%">Ajuste de precio $</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="lote in arrayLote" :key="lote.id">
                                         
                                        
                                        <td class="td2" style="width:20%" v-text="lote.proyecto"></td>
                                        <td class="td2" style="width:20%" v-text="lote.num_etapa"></td>
                                        <td class="td2" v-text="lote.manzana"></td>
                                            <td v-if="!lote.sublote" v-text="lote.num_lote"></td>
                                            <td v-else v-text="lote.num_lote + '-' + lote.sublote"></td>
                                        <td class="td2">
                                            <span class="badge badge-success" v-text="lote.modelo"></span>
                                        </td>
                                       <td class="td2" v-text="lote.avance+'%'"></td>
                                       <template>
                                            <td v-if="lote.contrato == 0" class="td2">
                                                <span class="badge badge-success"> Disponible </span>
                                            </td>
                                            <td v-else-if="lote.contrato == 1 && lote.firmado == 0" class="td2">
                                                <span class="badge badge-warning"> Vendida </span>
                                            </td>
                                            <td v-else class="td2">
                                                <span class="badge badge-danger"> Individualizada </span>
                                            </td>
                                        </template>
                                        <td class="td2" v-text="'$'+formatNumber(lote.precio_base)"></td>

                                        <td style="width:90%">
                                        <input type="text" pattern="\d*" @keyup.enter="actualizarAjuste(lote.id,$event.target.value)" :id="lote.id" :value="lote.ajuste|currency" step="1"  v-on:keypress="isNumber($event)" class="form-control" >     
                                        </td>
                                    </tr>
                                </tbody>
                            </table>  
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page > 7">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(1,buscar,b_etapa,b_manzana,b_lote,criterio)">Inicio</a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,b_etapa,b_manzana,b_lote,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,b_etapa,b_manzana,b_lote,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,b_etapa,b_manzana,b_lote,criterio)">Sig</a>
                                </li>
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page<pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.last_page,buscar,b_etapa,b_manzana,b_lote,criterio)">Ultimo</a>
                                </li>
                            </ul>
                        </nav>
                        <button class="btn btn-sm btn-default" data-toggle="modal" data-target="#manualId">Manual</button>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            
            <!-- Manual -->
            <div class="modal fade" id="manualId" tabindex="-1" role="dialog" aria-labelledby="manualIdTitle" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="manualIdTitle">Manual</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>
                            En caso de que sea necesario realizar un ajuste al precio de una vivienda o lote en específico, 
                            podrá agregar el precio buscando el lote o vivienda en listado, para agregar el precio solo 
                            debe editar el moto en la columna “ajuste” y presionar la tecla enter para guardar, 
                            el precio será sumado al costo total del lote.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    </div>
                </div>
            </div>
        </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->

<script>
    export default {
        props:{
            rolId:{type: String}
        },
        data(){
            return{
                proceso:false,
                id: 0,
                fraccionamiento_id:0,
                arrayLote : [],
                arrayFraccionamientos :[],
                arrayEtapas: [],
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
                criterio : 'fraccionamientos.nombre', 
                b_etapa: '',
                b_manzana: '',
                b_lote: '',
                b_modelo:'',
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
            listarLote(page, buscar, b_etapa, b_manzana,b_lote, criterio){
                let me = this;
                var url = '/lotes/con_precio_base?page=' + page + '&buscar=' + buscar 
                        + '&b_etapa=' + b_etapa + '&b_manzana=' + b_manzana 
                        + '&b_lote=' + b_lote + '&modelo=' + this.b_modelo
                        + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLote = respuesta.lotes.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectFraccionamientos(){
                let me = this;
                me.buscar=""
                me.buscar2=""
                me.buscar3=""
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
                var url = '/select_etapa?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEtapas = respuesta.etapas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
           
            cambiarPagina(page, buscar, b_etapa,b_manzana,b_lote, criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarLote(page,buscar,b_etapa,b_manzana,b_lote,criterio);
            },
             actualizarAjuste(id,ajuste){
                let me = this;
                axios.put('/lotes/actualizar/ajuste',{
                    'ajuste':ajuste,
                    'id' : id
                }).then(function (response){ 
                me.listarLote(me.pagination.current_page,me.buscar,me.b_etapa,me.b_manzana,me.b_lote,me.criterio);
                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000

                    });

                    toast({
                    type: 'success',
                    title: 'Cambios guardados'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },
            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },

            isNumber: function(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 45) {
                    evt.preventDefault();
                } else {
                    return true;
                }
            },
            
        },
        mounted() {
            this.listarLote(1,this.buscar,this.b_etapa,this.b_manzana,this.b_lote,this.criterio);
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

    /*th {
    text-align: left;
    background-color: rgb(190, 220, 250);
    text-transform: uppercase;
    padding-top: 1rem;
    padding-bottom: 1rem;
    border-bottom: rgb(50, 50, 100) solid 2px;
    border-top: none;
    }*/

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