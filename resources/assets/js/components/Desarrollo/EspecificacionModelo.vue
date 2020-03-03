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
                        <i class="fa fa-align-justify"></i>Especificaciones de Modelo
                        <!--   Boton   -->
                        <button type="button" class="btn btn-success" @click="abrirModal('asignar')" >
                            <i class="icon-pencil"></i>&nbsp;Asignar especificaciones
                        </button>
                        
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">

                                    <select class="form-control" v-model="buscar" @click="selectEtapa(buscar), selectModelo(buscar)" >
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>

                                    <select class="form-control" v-model="buscar2" @keyup.enter="listarLote(1)"> 
                                        <option value="">Etapa</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-10">
                                <div class="input-group">
                                    <select class="form-control" v-model="b_modelo" @keyup.enter="listarLote(1)">
                                            <option value="">Modelo</option>
                                            <option v-for="modelos in arrayModelos" :key="modelos.id" :value="modelos.id" v-text="modelos.nombre"></option>
                                        </select>
                                    <input type="text" v-model="buscar3" @keyup.enter="listarLote(1)" class="form-control" placeholder="Manzana a buscar">
                                    <input type="text" v-model="b_lote" @keyup.enter="listarLote(1)" class="form-control" placeholder="Lote a buscar">
                                                                        
                                    <button type="submit" @click="listarLote(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" @click="selectAll" v-model="allSelected">
                                        </th>
                                        <th>Proyecto</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th># Lote</th>
                                        <th>Modelo</th>
                                        <th>Especificaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="lote in arrayLote" :key="lote.id">
                                        <td class="td2">
                                            <input type="checkbox"  @click="select" :id="lote.id" :value="lote.id" v-model="lotes_ini" >
                                        </td>
                                        <td class="td2" v-text="lote.proyecto"></td>
                                        <td class="td2" v-text="lote.etapas"></td> 
                                        <td class="td2" v-text="lote.manzana"></td>
                                        <td class="td2" v-text="lote.num_lote"></td>
                                        <td class="td2" v-text="lote.modelo"></td>
                                        <td class="td2" v-if="lote.nombre_archivo == null">
                                            <a v-bind:href="'/downloadModelo/'+lote.archivo">Versión 1</a>
                                        </td> 
                                        <td class="td2" v-else>
                                            <a v-bind:href="'/downloadModelo/'+lote.archivo">{{lote.nombre_archivo}}</a>
                                        </td> 
                                    </tr>                               
                                </tbody>
                            </table> 
                        </div> 
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page > 7">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(1)">Inicio</a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1)">Sig</a>
                                </li>
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page<pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.last_page)">Ultimo</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>

            <!-- Modal para asignar modelo -->
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
                                <label class="col-md-3 form-control-label" for="text-input">Version de especificaciones</label>
                                <div class="col-md-6">
                                    <select class="form-control" v-model="version">
                                        <option value=''>Version 1</option>
                                        <option v-for="version in arrayVersiones" :key="version.id" :value="version.version" v-text="version.version"></option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button"  class="btn btn-primary" @click="actualizarVersion()">Actualizar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
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
 import _ from 'lodash'
    export default {
        data(){
            return{
                proceso:false,
                allSelected: false,
                lotes_ini : [],
                arrayVersiones : [],
                id: 0,
                b_modelo: '',
                b_habilitado: 1,
                b_lote: '',
                version:'',
                
                arrayLote : [],
                modal : 0,
                tituloModal : '',
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                buscar2 : '',
                buscar3 : '',
                buscar : '',
                arrayFraccionamientos : [],
                arrayEtapas : [],
                arrayModelos : [],
                arrayManzanas: [],
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
            selectAll: function() {
            this.lotes_ini = [];

            if (!this.allSelected) {
                for (var lote in this.arrayLote
                ) {
                    this.lotes_ini.push(this.arrayLote[lote].id.toString());
                }
            }
            },

             select: function() {
                this.allSelected = false;
            },

            /**Metodo para mostrar los registros */
            listarLote(page){
                let me = this;
                var url = '/lote/dispEspecificaciones?page=' + page + '&proyecto=' + this.buscar + '&etapa=' + this.buscar2 
                            + '&manzana=' + this.buscar3  + '&modelo=' + this.b_modelo + '&lote=' + this.b_lote;

                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLote = respuesta.lotes.data;
                    me.pagination = respuesta.pagination;
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
                me.listarLote(page);
            },

            selectFraccionamientos(){
                let me = this;
                if(me.modal == 0){
                me.buscar=""
                me.buscar2=""
                me.buscar3=""
                }
                
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
                if(me.modal == 0){
                
                me.buscar2=""
                me.buscar3=""
                }
                
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

            getVersiones(modelo){
                let me = this;
                
                me.arrayVersiones=[];
                var url = '/modelos/archivos/versiones?modelo=' + modelo;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayVersiones = respuesta.versiones;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            actualizarVersion(){
                let me = this;
                //Con axios se llama el metodo update de DepartamentoController

               Swal({
                    title: 'Estas seguro?',
                    animation: false,
                    customClass: 'animated bounceInDown',
                    text: "Las especificaciones se asignaran a los lotes seleccionados",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    
                    confirmButtonText: 'Si, asignar!'
                    }).then((result) => {

                    if (result.value) {
                        
                        me.lotes_ini.forEach(element => {
                            
                            axios.put('/modelos/archivos/updateVersionLote',{
                            'id': element,
                            'nombre_archivo' : this.version
                            }); 
                        });
                   // me.listarLote(1,'','','','','','','lote');   
                    me.listarLote(me.pagination.current_page);
                    me.cerrarModal();
                    Swal({
                        title: 'Hecho!',
                        text: 'Las especificaciones se han asignado',
                        type: 'success',
                        animation: false,
                        customClass: 'animated bounceInRight'
                    })
                    }})
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
                this.lotes_ini=[];
                this.allSelected = false;
            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(accion,data =[]){
                
                if(this.lotes_ini.length<1 && accion=='asignar'){
                    Swal({
                    title: 'No se ha seleccionado ningun lote',
                    animation: false,
                    customClass: 'animated tada'
                    })
                    return;
                }
                this.getVersiones(this.b_modelo);
                switch(accion){

                    case 'asignar':
                    {
                        this.proceso=false;
                        this.modal =1;
                        this.tituloModal= 'Asignar Modelo';
                        this.version = '';
                        break;
                    }
                    
                }

            }
        },
        mounted() {
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
