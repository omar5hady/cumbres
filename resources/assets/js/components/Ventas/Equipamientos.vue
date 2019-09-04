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
                        <i class="fa fa-align-justify"></i>Solicitud de Equipamiento

                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-10">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-5" v-model="criterio" @click="selectFraccionamientos()">
                                        <option value="lotes.fraccionamiento_id">Proyecto</option>
                                        <option value="c.nombre">Cliente</option>
                                        <option value="contratos.id"># Folio</option>
                                    </select>

                                    
                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar" @click="selectEtapa(buscar)">
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>

                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_etapa"> 
                                        <option value="">Etapa</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                    </select>

                                    
                                    <input type="text" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_manzana" class="form-control" placeholder="Manzana a buscar">
                                    <input type="text" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_lote" class="form-control" placeholder="Lote a buscar">

                                    <input v-else type="text"  v-model="buscar" @keyup.enter="listarContratos(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <input v-if="criterio=='c.nombre'" type="text"  v-model="b_etapa" @keyup.enter="listarContratos(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="form-control" placeholder="Apellidos">
                                    <button type="submit" @click="listarContratos(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                   
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr> 
                                        <th># Ref</th>
                                        <th>Cliente</th>
                                        <th>Proyecto</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th>Lote</th>
                                        <th>Equipamiento</th>
                                        <th>Avance</th>
                                        <th>Fecha avaluo</th>
                                        <th>Status</th>
                                        <th>Fecha entrega (obra)</th>
                                        <th>Solicitar</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="contratos in arrayContratos" :key="contratos.folio">
                                        <template  v-if="contratos.descripcion_paquete || contratos.descripcion_promocion">
                                            <td class="td2" v-text="contratos.folio"></td>
                                            <td class="td2" v-text="contratos.nombre_cliente"></td>
                                            <td class="td2" v-text="contratos.proyecto"></td>
                                            <td class="td2" v-text="contratos.etapa"></td>
                                            <td class="td2" v-text="contratos.manzana"></td>
                                            <td class="td2" v-text="contratos.num_lote"></td>
                                            <td class="td2" v-text="contratos.descripcion_paquete + ', ' + contratos.descripcion_promocion"></td>
                                            <td class="td2" v-text="contratos.avance_lote + '%'"></td>
                                            <td class="td2" v-if="contratos.visita_avaluo" v-text="this.moment(contratos.visita_avaluo).locale('es').format('DD/MMM/YYYY')"></td>
                                            <td class="td2" v-else v-text="'Sin fecha'"></td>
                                            <template>
                                                <td class="td2" v-if="contratos.status == '1'">
                                                    <span class="badge badge-warning">Pendiente</span>
                                                </td>
                                                <td class="td2" v-if="contratos.status == '3'">
                                                    <span class="badge badge-success">Firmado</span>
                                                </td>
                                            </template>
                                            <td class="td2" v-if="contratos.fecha_entrega" v-text="this.moment(contratos.fecha_entrega).locale('es').format('DD/MMM/YYYY')"></td>
                                            <td class="td2" v-else v-text="'Sin fecha'"></td>
                                            <td class="td2">
                                                <button type="button" @click="abrirModal('solicitar',contratos)" class="btn btn-default btn-sm"> Solicitar </button>
                                            </td>
                                        </template>
                                        
                                    </tr>
                                </tbody>
                            </table>  
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page > 7">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(1,buscar,criterio)">Inicio</a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                </li>
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page<pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.last_page,buscar,criterio)">Ultimo</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
         

                <!--Inicio del modal para mostrar los datos del cliente -->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" v-text="tituloModal"></h5>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row" v-if="paquete">
                                <label class="col-md-2 form-control-label" for="text-input">Paquete</label>
                                <div class="col-md-9">
                                    <textarea rows="3" cols="30" readonly v-model="paquete" class="form-control" placeholder="Descripcion del paquete"></textarea>
                                </div>
                            </div>

                            <div class="form-group row" v-if="promocion">
                                <label class="col-md-2 form-control-label" for="text-input">Promoción</label>
                                <div class="col-md-9">
                                    <textarea rows="3" cols="30" readonly v-model="promocion" class="form-control" placeholder="Descripcion del paquete"></textarea>
                                </div>
                            </div>

                             <div class="form-group row line-separator"></div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Proveedor</label>
                                <div class="col-md-6">
                                    <select class="form-control" v-model="proveedor" @click="selectEquipamiento(proveedor)">
                                        <option value="">Seleccione</option>
                                        <option v-for="proveedor in arrayProveedores" :key="proveedor.id" :value="proveedor.id" v-text="proveedor.proveedor"></option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Equipamiento</label>
                                <div class="col-md-6">
                                    <select class="form-control" v-model="equipamiento">
                                        <option value="">Seleccione</option>
                                        <option v-for="equipamiento in arrayEquipamientos" :key="equipamiento.id" :value="equipamiento.id" v-text="equipamiento.equipamiento"></option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button class="btn btn-primary" @click="SolicitarEquipamiento()">Solicitar</button>
                                </div>
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
                observacion:'',

                arrayContratos : [],

                arrayFraccionamientos:[],
                arrayEtapas:[],
                arrayProveedores:[],
                arrayEquipamientos:[],

                modal: 0,
                tituloModal: '',

                //variables
                paquete:'',
                promocion:'',
                proveedor:'',
                equipamiento:'',
           
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'lotes.fraccionamiento_id', 
                buscar : '',
                b_etapa: '',
                b_manzana: '',
                b_lote: ''
               
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

            /**Metodo para mostrar los registros */
            listarContratos(page, buscar, b_etapa, b_manzana, b_lote, criterio){
                let me = this;
                var url = '/equipamiento/indexContrato?page=' + page + '&buscar=' + buscar + '&b_etapa=' + b_etapa + '&b_manzana=' + b_manzana + '&b_lote=' + b_lote +  '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayContratos = respuesta.contratos.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },
            
            selectFraccionamientos(){
                let me = this;
                me.buscar=""
                
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
                me.b_etapa=""
                
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

            selectProveedores(){
                let me = this;
                me.buscar=""
                
                me.arrayProveedores=[];
                var url = '/select_proveedor';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayProveedores = respuesta.proveedor;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectEquipamiento(proveedor){
                let me = this;
                me.equipamiento=""
                
                me.arrayEquipamientos=[];
                var url = '/select_equipamientos?buscar=' + proveedor;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEquipamientos = respuesta.equipamiento;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            cambiarPagina(page,buscar,b_etapa,b_manzana,b_lote,criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarContratos(page,buscar,b_etapa,b_manzana,b_lote,criterio);
            },

            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
              cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
            },

             abrirModal(accion,data =[]){
                    switch(accion){
                        case 'solicitar':
                        {
                            this.modal =1;
                            this.tituloModal='Solicitar equipamiento';
                            this.tipoAccion=1;
                            this.paquete = data['descripcion_paquete'];
                            this.promocion = data['descripcion_promocion'];
                            this.selectProveedores();
                        }
                    }
                }
            
        },
       
        mounted() {
            this.listarContratos(1,this.buscar,this.b_etapa,this.b_manzana,this.b_lote,this.criterio);
            this.selectFraccionamientos();
        }
    }
</script>
<style>
    .line-separator{
        height:1px;
        background:#717171;
        border-bottom:1px solid #c2cfd6;
    }


    .form-control:disabled, .form-control[readonly] {
    background-color: rgba(0, 0, 0, 0.06);
    opacity: 1;
    font-size: 0.85rem;
    color: #27417b;
    }
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

    .badge2 {
    display: inline-block;
    padding: 0.25em 0.4em;
    font-size: 90%;
    font-weight: bold;
    line-height: 1;
    color: #fff;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
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
