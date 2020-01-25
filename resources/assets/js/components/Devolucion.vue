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
                        <i class="fa fa-align-justify"></i>Devoluciones por cancelación
                        <button class="btn btn-danger" v-if="listado == 1" @click="listado = 2 , listarDevoluciones(1, buscar_d, b_etapa_d, b_manzana_d, b_lote_d, criterio_d)">Historial de devoluciones</button>
                        <button class="btn btn-warning" v-if="listado == 2" @click="listado = 1">Volver a las devoluciones</button>
                    </div>

                    <!-- listado de devoluciones -->
                    <div v-if="listado == 1" class="card-body">
                        <div class="form-group row">
                            <div class="col-md-10">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-5" v-model="criterio" @click="selectFraccionamientos()">
                                        <option value="lotes.fraccionamiento_id">Proyecto</option>
                                        <option value="personal.nombre">Cliente</option>
                                        <option value="creditos.id"># Folio</option>
                                    </select>
                                    
                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar"  @keyup.enter="listarContratos(1, buscar, b_etapa, b_manzana, b_lote, criterio)" @click="selectEtapa(buscar)">
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>
                                   
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">

                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_etapa"  @keyup.enter="listarContratos(1, buscar, b_etapa, b_manzana, b_lote, criterio)" @click="selectManzanas(buscar,b_etapa)"> 
                                        <option value="">Etapa</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                    </select>

                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_manzana" >
                                        <option value="">Seleccione</option>
                                        <option v-for="manzana in arrayManzanas" :key="manzana.manzana" :value="manzana.manzana" v-text="manzana.manzana"></option>
                                    </select>

                                    <button type="submit" @click="listarContratos(1, buscar, b_etapa, b_manzana, b_lote, criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    <a :href="'/devolucion/excel?buscar=' + buscar + '&b_etapa=' + b_etapa + '&b_manzana=' + b_manzana + '&b_lote=' + b_lote +  '&criterio=' + criterio"  class="btn btn-success"><i class="fa fa-file-text"></i> Excel</a>
                                   
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
                                        <th>Depositos</th>
                                        <th>Pendiente Devolver</th>
                                        <th>Fecha cancelación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="contratos in arrayContratos" :key="contratos.id" v-on:dblclick="abrirModal('devolucion',contratos)"> 
                                    <template v-if="(contratos.sumaPagares - contratos.sumaRestante) > 0">
                                        <td class="td2" v-text="contratos.id"></td>
                                        <td class="td2" v-text="contratos.nombre_cliente"></td>
                                        <td class="td2" v-text="contratos.proyecto"></td>
                                        <td class="td2" v-text="contratos.etapa"></td>
                                        <td class="td2" v-text="contratos.manzana"></td>
                                        <td class="td2" v-text="contratos.num_lote"></td>
                                        <td class="td2" v-text="'$'+formatNumber(contratos.sumaPagares - contratos.sumaRestante)"></td>
                                        <td class="td2" v-text="'$'+formatNumber(contratos.sumaPagares - contratos.sumaRestante -  contratos.sumGastos)"></td>
                                        <td class="td2" v-text="this.moment(contratos.fecha_status).locale('es').format('DD/MMM/YYYY')"></td>
                                    </template>
                                    </tr>                               
                                </tbody>
                            </table>  
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page > 7">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(1,buscar, b_etapa, b_manzana, b_lote, criterio)">Inicio</a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar, b_etapa, b_manzana, b_lote, criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar, b_etapa, b_manzana, b_lote, criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar, b_etapa, b_manzana, b_lote, criterio)">Sig</a>
                                </li>
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page<pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.last_page,buscar, b_etapa, b_manzana, b_lote, criterio)">Ultimo</a>
                                </li>
                            </ul>
                        </nav>
                    </div>

                    <!-- listado de historial de devoluciones -->
                    <div v-if="listado == 2" class="card-body">
                        <div class="form-group row">
                            <div class="col-md-10">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-5" v-model="criterio_d" @click="selectFraccionamientos()">
                                        <option value="lotes.fraccionamiento_id">Proyecto</option>
                                        <option value="personal.nombre">Cliente</option>
                                        <option value="creditos.id"># Folio</option>
                                    </select>
                                    
                                    <select class="form-control" v-if="criterio_d=='lotes.fraccionamiento_id'" v-model="buscar_d"  @keyup.enter="listarDevoluciones(1, buscar_d, b_etapa_d, b_manzana_d, b_lote_d, criterio_d)" @click="selectEtapa(buscar_d)">
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>

                                    <select class="form-control" v-if="criterio_d=='lotes.fraccionamiento_id'" v-model="b_etapa_d"  @keyup.enter="listarDevoluciones(1, buscar_d, b_etapa_d, b_manzana_d, b_lote_d, criterio_d)" @click="selectManzanas(buscar_d,b_etapa_d)"> 
                                        <option value="">Etapa</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                    </select>

                                    <select class="form-control" v-if="criterio_d=='lotes.fraccionamiento_id'" v-model="b_manzana_d" >
                                        <option value="">Seleccione</option>
                                        <option v-for="manzana in arrayManzanas" :key="manzana.manzana" :value="manzana.manzana" v-text="manzana.manzana"></option>
                                    </select>

                                    <input type="text" v-if="criterio_d=='lotes.fraccionamiento_id'" v-model="b_lote_d" class="form-control" placeholder="Lote a buscar">

                                    <input v-else type="text"  v-model="buscar_d" @keyup.enter="listarDevoluciones(1, buscar_d, b_etapa_d, b_manzana_d, b_lote_d, criterio_d)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarDevoluciones(1, buscar_d, b_etapa_d, b_manzana_d, b_lote_d, criterio_d)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    <a  :href="'/devoluciones/excel?buscar=' + buscar_d + '&b_etapa_d=' + b_etapa_d + '&b_manzana_d=' +b_manzana_d+ '&b_lote_d=' + b_lote_d +'&criterio_d=' + criterio_d "  class="btn btn-success"><i class="fa fa-file-text"></i> Excel</a>
                                   
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
                                        <th>Devuelto</th>
                                        <th>Fecha cancelación</th>
                                        <th>Fecha de devolucion</th>
                                        <th>Cheque</th>
                                        <th>Cuenta</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="devoluciones in arrayDevoluciones" :key="devoluciones.id" v-on:dblclick="abrirModal('info',devoluciones)"> 
                                    <template v-if="(devoluciones.sumaPagares - devoluciones.sumaRestante) > 0">
                                        <td class="td2" v-text="devoluciones.id"></td>
                                        <td class="td2" v-text="devoluciones.nombre_cliente"></td>
                                        <td class="td2" v-text="devoluciones.proyecto"></td>
                                        <td class="td2" v-text="devoluciones.etapa"></td>
                                        <td class="td2" v-text="devoluciones.manzana"></td>
                                        <td class="td2" v-text="devoluciones.num_lote"></td>
                                        <td class="td2" v-text="'$'+formatNumber(devoluciones.devolver)"></td>
                                        <td class="td2" v-text="this.moment(devoluciones.fecha_status).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td class="td2" v-text="this.moment(devoluciones.fecha).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td class="td2" v-text="devoluciones.cheque"></td>
                                        <td class="td2" v-text="devoluciones.cuenta"></td>
                                    </template>
                                    </tr>                               
                                </tbody>
                            </table>  
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination2.last_page > 7 && pagination2.current_page > 7">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(1, buscar_d, b_etapa_d, b_manzana_d, b_lote_d, criterio_d)">Inicio</a>
                                </li>
                                <li class="page-item" v-if="pagination2.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page - 1, buscar_d, b_etapa_d, b_manzana_d, b_lote_d, criterio_d)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber2" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(page, buscar_d, b_etapa_d, b_manzana_d, b_lote_d, criterio_d)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination2.current_page < pagination2.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page + 1, buscar_d, b_etapa_d, b_manzana_d, b_lote_d, criterio_d)">Sig</a>
                                </li>
                                <li class="page-item" v-if="pagination2.last_page > 7 && pagination2.current_page<pagination2.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.last_page, buscar_d, b_etapa_d, b_manzana_d, b_lote_d, criterio_d)">Ultimo</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
         

            <!--Inicio del modal -->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <!-- form para generar devolucion -->
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Proyecto</label>
                                    <div class="col-md-3">
                                        <input type="text" disabled v-model="proyecto" class="form-control" >
                                    </div>

                                    <label class="col-md-1 form-control-label" for="text-input">Etapa</label>
                                    <div class="col-md-2">
                                        <input type="text" disabled v-model="etapa" class="form-control">
                                    </div>

                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Manzana</label>
                                    <div class="col-md-3">
                                        <input type="text" disabled v-model="manzana" class="form-control" >
                                    </div>

                                    <label class="col-md-1 form-control-label" for="text-input">Lote</label>
                                    <div class="col-md-2">
                                        <input type="text" disabled v-model="lote" class="form-control">
                                    </div>

                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Cliente</label>
                                    <div class="col-md-8">
                                        <input type="text" disabled v-model="cliente" class="form-control" >
                                    </div>
                                </div>

                                <div class="form-group row line-separator"></div>

                                <div class="form-group row" v-if="tipoAccion==1">
                                    <label class="col-md-2 form-control-label" for="text-input">Depositos</label>
                                    <div class="col-md-4">
                                        <h6><strong> ${{ formatNumber(depositos)}} </strong></h6>
                                    </div>
                                </div>

                                <div class="form-group row" v-if="arrayGastos.length && tipoAccion==1">
                                    <div class="col-md-12">
                                        <h6><strong> GASTOS ADMINISTRATIVOS </strong></h6>
                                    </div>
                                </div>

                                <div v-if="arrayGastos.length && tipoAccion==1">
                                        <div class="form-group row"  v-for="gasto in arrayGastos" :key="gasto.id">
                                            <label class="col-md-3 form-control-label" for="text-input" v-text="gasto.concepto"></label>
                                            <div class="col-md-3">
                                                <h6>${{ formatNumber(gasto.costo)}}</h6>
                                            </div>
                                        </div>
                                    </div>

                                
                                <div class="form-group row" >
                                    <div class="col-md-12" v-if="tipoAccion==1">
                                        <h5 align="center"><strong> Total a devolver </strong></h5>
                                    </div>
                                    <div class="col-md-12" v-if="tipoAccion==1">
                                        <h5 align="center"><strong> ${{ formatNumber(devolver = depositos - totalGastos)}} </strong></h5>
                                    </div>

                                    <div class="col-md-12" v-if="tipoAccion==2">
                                        <h5 align="center"><strong> Cantidad devuelta </strong></h5>
                                    </div>
                                    <div class="col-md-12" v-if="tipoAccion==2">
                                        <h5 align="center"><strong> ${{ formatNumber(cant_dev)}} </strong></h5>
                                    </div>
                                </div>

                                <div class="form-group row" v-if="tipoAccion==1">
                                    <label class="col-md-3 form-control-label" for="text-input"  v-if="tipoAccion==1">Cantidad a devolver</label>
                                    <div v-if="tipoAccion==1" class="col-md-2">
                                        <input type="text" pattern="\d*" v-on:keypress="isNumber($event)" v-model="cant_dev" class="form-control" placeholder="Concepto" >
                                    </div>
                                    <div class="col-md-2">
                                        <h6><strong> ${{ formatNumber(cant_dev = TotalDev)}} </strong></h6>
                                    </div>
                                </div>
                                

                                <div class="form-group row line-separator"></div>

                                <div class="form-group row"> 
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha</label>
                                    <div class="col-md-4">
                                        <input type="date" :disabled="tipoAccion==2" v-model="fecha_devolucion" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row"> 
                                    <label class="col-md-3 form-control-label" for="text-input"># Cheque</label>
                                    <div class="col-md-4">
                                        <input type="text" :disabled="tipoAccion==2" v-model="cheque" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Cuenta de Banco</label>
                                    <div class="col-md-6">
                                        <select :disabled="tipoAccion==2" class="form-control" v-model="banco">
                                            <option value="">Seleccione</option>
                                            <option v-for="banco in arrayBancos" :key="banco.num_cuenta" :value="banco.num_cuenta + '-' + banco.banco" v-text="banco.num_cuenta + '-' + banco.banco"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Observaciones</label>
                                    <div class="col-md-9">
                                    <textarea :disabled="tipoAccion==2" rows="3" cols="30" v-model="observaciones" class="form-control" placeholder="Observaciones"></textarea>
                                    </div>
                                </div>

                                
                                
                                
                            </form>
                            <!-- fin del form solicitud de avaluo -->

                            <!-- Div para mostrar los errores que mande validerDepartamento -->
                            <div v-show="errorDev" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjDev" :key="error" v-text="error">
                                    </div>
                                </div>
                            </div>


                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" class="btn btn-primary" v-if="tipoAccion==1" @click="generarDevolucion()">Generar</button>
                        </div>
                    </div>
                      <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal consulta-->
            
         
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
                id: 0,
                
                gestor_id: 0,

                arrayContratos : [],
                arrayFraccionamientos:[],
                arrayEtapas:[],
                arrayManzanas:[],
                arrayBancos : [],
                arrayGastos : [],
                arrayDevoluciones: [],

                errorDev:0,
                errorMostrarMsjDev:[],

                modal : 0,
                depositos : 0,
                proyecto : '',
                etapa: '',
                manzana: '',
                lote: '',
                cliente: '',
                banco:'',
                fecha_devolucion:'',
                cheque:'',
                observaciones: '',
                totalGastos:0,
                devolver : 0,
                cant_dev : 0,
                
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
                pagination2 : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                offset2 : 3,
                criterio : 'lotes.fraccionamiento_id', 
                buscar : '',
                b_etapa: '',
                b_manzana: '',
                b_lote: '',
                buscar_d: '',
                b_etapa_d:'',
                b_manzana_d:'',
                b_lote_d:'',
                criterio_d:'lotes.fraccionamiento_id',
                listado : 1
               
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

            // Funciones de la paginacion del historial de devoluciones
            isActived2: function(){
                return this.pagination2.current_page;
            },

            TotalDev: function(){
                var totalDev =0.0;
                if(parseFloat(this.cant_dev) > parseFloat(this.devolver))
                    this.cant_dev = this.devolver;
                totalDev = this.cant_dev;
                return totalDev;
            },

            //Calcula los elementos de la paginación
            pagesNumber2:function(){
                if(!this.pagination2.to){
                    return [];
                }

                var from = this.pagination2.current_page - this.offset2;
                if(from < 1){
                    from = 1;
                }

                var to = from + (this.offset2 * 2);
                if(to >= this.pagination2.last_page){
                    to = this.pagination2.last_page;
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
                var url = '/devolucion/index?page=' + page + '&buscar=' + buscar + '&b_etapa=' + b_etapa + '&b_manzana=' + b_manzana + '&b_lote=' + b_lote +  '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayContratos = respuesta.contratos.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },

            listarDevoluciones(page, buscar_d, b_etapa_d, b_manzana_d, b_lote_d, criterio_d){
                let me = this;
                var url = '/devolucion/indexDevoluciones?page=' + page + '&buscar=' + buscar_d + '&b_etapa=' + b_etapa_d + '&b_manzana=' + b_manzana_d + '&b_lote=' + b_lote_d +  '&criterio=' + criterio_d;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayDevoluciones = respuesta.devoluciones.data;
                    me.pagination2 = respuesta.pagination;
                })
                .catch(function (error) {
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
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46 ) {
                    evt.preventDefault();;
                } else {
                    return true;
                }
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
                me.buscar2=""
                
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

            selectCuenta(){
                let me = this;
                me.arrayBancos=[];
                var url = '/select_cuenta';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayBancos = respuesta.cuentas;
                })
                .catch(function (error) {
                    console.log(error);
                });

            },

            listarGastos(){
                let me = this;
                var url = '/expediente/gastosExpediente?folio=' + this.id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayGastos = respuesta.gastos;
                    me.totalGastos = respuesta.totalGastos[0].sumGasto;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            generarDevolucion(){
                if(this.validarDev()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }
                let me = this;
                me.proceso = true;
                axios.post('/devolucion/registrar',{
                'id': this.id,
                'devolver': this.devolver,
                'cant_dev' : this.cant_dev,
                'fecha': this.fecha_devolucion,
                'cheque': this.cheque,
                'cuenta': this.banco,
                'observaciones': this.observaciones
            }).then(function (response){
                me.proceso=false;
                me.cerrarModal(); //al guardar el registro se cierra el modal
                me.listarContratos(me.pagination.current_page,me.buscar,me.b_etapa, me.b_manzana, me.b_lote, me.criterio); //se enlistan nuevamente los registros
                //Se muestra mensaje Success
                swal({
                    position: 'top-end',
                    type: 'success',
                    title: 'Devolucion generada correctamente',
                    showConfirmButton: false,
                    timer: 1500
                    })
            }).catch(function (error){
                console.log(error);
            });
            },

            cambiarPagina(page,buscar, b_etapa, b_manzana, b_lote, criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarContratos(page,buscar, b_etapa, b_manzana, b_lote, criterio);
            },

            cambiarPagina2(page, buscar_d, b_etapa_d, b_manzana_d, b_lote_d, criterio_d){
                let me = this;
                //Actualiza la pagina actual
                me.pagination2.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarDevoluciones(page, buscar_d, b_etapa_d, b_manzana_d, b_lote_d, criterio_d);
            },

            validarDev(){
                this.errorDev=0;
                this.errorMostrarMsjDev=[];

                if(this.cant_dev== 0) //Si la variable departamento esta vacia
                    this.errorMostrarMsjDev.push("Ingresar cantidad a devolver.");

                if(this.fecha_devolucion == '') //Si la variable departamento esta vacia
                    this.errorMostrarMsjDev.push("Ingresar fecha de devolución.");

                if(this.cheque == '') //Si la variable departamento esta vacia
                    this.errorMostrarMsjDev.push("Ingresar numero de cheque.");

                if(this.banco == '') //Si la variable departamento esta vacia
                    this.errorMostrarMsjDev.push("Seleccionar cuenta de banco.");
              
                if(this.errorMostrarMsjDev.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorDev = 1;

                return this.errorDev;
            },
       
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.depositos = 0;
                this.proyecto = "";
                this.etapa = "";
                this.manzana = "";
                this.lote = "";
                this.cliente = "";
                this.banco = "";
                this.depositos = 0;
                this.fecha_devolucion = '';
                this.cheque = '';
                this.monto_cargo = 0;
                this.totalGastos = 0;
                this.arrayGastos = [];
                this.cheque = '';
                this.cant_dev =0;
                
            },
        
      
            abrirModal(accion,data =[]){
                
                switch(accion){
                    
                    case 'devolucion':
                    {
                        this.modal =1;
                        this.tituloModal='Generar devolución';
                        this.tipoAccion = 1;
                        this.id = data['id'];
                        this.proyecto = data['proyecto'];
                        this.etapa = data['etapa'];
                        this.manzana = data['manzana'];
                        this.lote = data['num_lote'];
                        this.cliente = data['nombre_cliente'];
                        this.depositos = data['sumaPagares'] - data['sumaRestante'];
                        this.devolver = 0;
                        this.cheque ='';
                        this.observaciones = '';
                        this.cant_dev = 0;

                        this.listarGastos();
                        this.selectCuenta();
                        break;
                    }      
                    
                    case 'info':
                    {
                        this.modal =1;
                        this.tituloModal='Devolución';
                        this.tipoAccion = 2;
                        this.id = data['id'];
                        this.proyecto = data['proyecto'];
                        this.etapa = data['etapa'];
                        this.manzana = data['manzana'];
                        this.lote = data['num_lote'];
                        this.cliente = data['nombre_cliente'];
                        this.depositos = data['sumaPagares'] - data['sumaRestante'];
                        this.devolver = data['devolver'];
                        this.cant_dev = data['devolver'];
                        this.cheque =data['cheque'];
                        this.observaciones = data['observaciones'];
                        this.fecha_devolucion = data['fecha'];
                        this.banco = data['cuenta'];

                        this.listarGastos();
                        this.selectCuenta();
                        break;
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
        font-size: 1rem;
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
