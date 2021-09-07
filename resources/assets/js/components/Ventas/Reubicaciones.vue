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
                        <i class="fa fa-align-justify"></i> Contratos
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <!--   Boton agregar    -->
                        <button type="button" @click="listado=0" class="btn btn-success" v-if="listado==1">
                            <i class="fa fa-mail-reply"></i>&nbsp;Regresar
                        </button>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <button type="button" @click="abrirModal()" class="btn btn-primary" v-if="listado==1">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>

            <!----------------- Listado Contratos ------------------------------>
                    <!-- Div Card Body para listar -->
                    <template v-if="listado == 0">
                        <div class="card-body"> 

                            <div class="form-group row">
                                <div class="col-md-8">
                                    <select class="form-control" v-model="b_empresa" >
                                        <option value="">Empresa constructora</option>
                                        <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row" v-if="criterio == 'creditos.id' || criterio == 'personal.nombre'">
                                <div class="col-md-8">
                                       <div class="input-group">
                                        <!--Criterios para el listado de busqueda -->
                                        <select class="form-control col-md-4" v-model="criterio" @change="limpiarBusqueda()">
                                            <option value="creditos.id"># Folio</option>
                                            <option value="personal.nombre">Cliente</option>
                                            <option value="creditos.fraccionamiento">Proyecto</option>
                                        </select>
                                                                               
                                        <input  v-if="criterio=='personal.nombre' || criterio=='v.nombre' || criterio=='creditos.id'" type="text" v-model="buscar" @keyup.enter="listarContratos(1)" class="form-control">
                                        <select class="form-control col-md-4" v-model="b_status">
                                            <option value="">Seleccionar Status</option>
                                            <option value="0">Cancelado</option>
                                            <option value="1">Pendiente</option>
                                            <option value="2">No firmado</option>
                                            <option value="3">Firmado</option>
                                        </select>
                                    </div>
                                    <button type="submit" @click="listarContratos(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                   
                                </div>
                            </div>
                            <div class="form-group row" v-if="criterio=='creditos.fraccionamiento'">
                                <div class="col-md-8">
                                       <div class="input-group">
                                        <!--Criterios para el listado de busqueda -->
                                        <select class="form-control col-md-4" v-model="criterio" @change="limpiarBusqueda()">
                                            <option value="creditos.id"># Folio</option>
                                            <option value="personal.nombre">Cliente</option>
                                            <option value="creditos.fraccionamiento">Proyecto</option>
                                        </select>
                                        <select class="form-control" v-if="criterio=='creditos.fraccionamiento'"  @change="selectEtapas(buscar), selectModelo(buscar)" @keyup.enter="listarContratos(1)" v-model="buscar" >
                                            <option value="">Proyecto</option>
                                            <option v-for="proyecto in arrayFraccionamientos" :key="proyecto.id" :value="proyecto.id" v-text="proyecto.nombre"></option>
                                        </select>

                                        <select class="form-control" v-if="criterio=='creditos.fraccionamiento'" @change="selectManzanas(buscar,b_etapa)" @keyup.enter="listarContratos(1)" v-model="b_etapa" >
                                            <option value="">Etapa</option>
                                            <option v-for="etapa in arrayEtapas" :key="etapa.id" :value="etapa.id" v-text="etapa.num_etapa"></option>
                                        </select>
                                    </div>
                                   
                                </div>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <select class="form-control" v-if="criterio=='creditos.fraccionamiento'" @keyup.enter="listarContratos(1)" v-model="b_manzana" >
                                            <option value="">Manzana</option>
                                            <option v-for="manzana in arrayManzanas" :key="manzana.manzana" :value="manzana.manzana" v-text="manzana.manzana"></option>
                                        </select>

                                        <input type="text" v-model="b_lote" @keyup.enter="listarContratos(1)" class="form-control" placeholder="Lote">
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="input-group">
                                        <select class="form-control col-md-4" v-model="b_status">
                                            <option value="">Seleccionar Status</option>
                                            <option value="0">Cancelado</option>
                                            <option value="1">Pendiente</option>
                                            <option value="2">No firmado</option>
                                            <option value="3">Firmado</option>
                                        </select>
                                    </div>
                                    <div class="input-group">
                                        <button type="submit" @click="listarContratos(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                        <a  :href="'/contratos/excel?buscar=' + buscar + '&buscar3=' + buscar3 + '&b_etapa=' +b_etapa+ '&b_manzana=' + b_manzana + '&b_lote='+ b_lote + '&b_status='+ b_status + '&criterio=' + criterio + '&f_ini=' + b_fecha + '&f_fin=' + b_fecha2 + '&publicidad=' + b_publicidad"  class="btn btn-success"><i class="fa fa-file-text"></i> Excel</a>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table2 table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th># Contrato</th>
                                            <th>Cliente</th>
                                            <th>Vendedor</th>
                                            <th>Proyecto</th>
                                            <th>Etapa</th>
                                            <th>Manzana</th>
                                            <th># Lote</th>
                                            <th>Modelo</th>
                                            <th>Tipo de credito</th>
                                            <th>Institución</th>
                                            <th>Precio de venta</th>
                                            <th>Fecha del contrato</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="contrato in arrayContratos" :key="contrato.id" @click="verContrato(contrato)" v-bind:style="{ backgroundColor : !contrato.detenido ? '#FFFFFF' : '#D23939'}" title="Ver contrato">
                                            <td class="td2" v-text="contrato.id"></td>
                                            <td class="td2">
                                                <a href="#" v-text="contrato.nombre.toUpperCase() + ' ' + contrato.apellidos.toUpperCase() "></a>
                                            </td>
                                            <td class="td2" v-text="contrato.vendedor_nombre + ' ' + contrato.vendedor_apellidos "></td>
                                            <td class="td2" v-text="contrato.fraccionamiento"></td>
                                            <td class="td2" v-text="contrato.etapa"></td>
                                            <td class="td2" v-text="contrato.manzana"></td>
                                            <td class="td2" v-text="contrato.num_lote"></td>
                                            <td class="td2" v-text="contrato.modelo"></td>
                                            <td class="td2" v-text="contrato.tipo_credito"></td>
                                            <td class="td2" v-text="contrato.institucion"></td>
                                            <td class="td2" v-text="'$'+formatNumber(contrato.precio_venta)"></td>
                                            <td class="td2" v-text="this.moment(contrato.fecha).locale('es').format('DD/MMM/YYYY')"></td>
                                            <td class="td2" v-if="contrato.status == '0' ">
                                                <span class="badge badge-danger">Cancelado/ {{this.moment(contrato.fecha_status).locale('es').format('DD/MMM/YYYY')}}</span>
                                            </td>
                                            <td class="td2" v-if="contrato.status == '1'">
                                                <span class="badge badge-warning">Pendiente</span>
                                            </td>
                                            <td class="td2" v-if="contrato.status == '2'">
                                                <span class="badge badge-danger">No firmado/ {{this.moment(contrato.fecha_status).locale('es').format('DD/MMM/YYYY')}}</span>
                                            </td>
                                            <td class="td2" v-if="contrato.status == '3'">
                                                <span class="badge badge-success">Firmado</span>
                                                <span v-if="contrato.status2 == 1" class="badge badge-dark"> INDIVIDUALIZADA </span>
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
                    </template>

                    <template v-if="listado == 1">
                        <div class="card-body"> 
                            <div class="form-group row">
                                <div class="col-md-3">
                                   <h6>Venta actual:</h6>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label style="color:#2271b3;" for=""><strong> Fraccionamiento </strong></label>
                                        <p v-text="contrato.fraccionamiento"></p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label style="color:#2271b3;" for=""><strong> Etapa </strong></label>
                                        <p v-text="contrato.etapa"></p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label style="color:#2271b3;" for=""><strong> Manzana </strong></label>
                                        <p v-text="contrato.manzana"></p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label style="color:#2271b3;" for=""><strong> Lote </strong></label>
                                        <p v-text="contrato.num_lote"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label style="color:#2271b3;" for=""><strong> Cliente </strong></label>
                                        <p v-text="contrato.nombre+' '+contrato.apellidos"></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label style="color:#2271b3;" for=""><strong> Asesor </strong></label>
                                        <p v-text="contrato.vendedor_nombre+' '+contrato.vendedor_apellidos"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label style="color:#2271b3;" for=""><strong> Tipo de Crédito </strong></label>
                                        <p v-text="contrato.tipo_credito"></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label style="color:#2271b3;" for=""><strong> Institucion </strong></label>
                                        <p v-text="contrato.institucion"></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label style="color:#2271b3;" for=""><strong> Valor de terreno </strong></label>
                                        <p v-text="formatNumber(contrato.valor_terreno)"></p>
                                    </div>
                                </div>
                            </div>


                            <div class="table-responsive">
                                <table class="table2 table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Fecha de reubicación</th>
                                            <th>Fraccionamiento</th>
                                            <th>Etapa</th>
                                            <th>Manzana</th>
                                            <th>Lote</th>
                                            <th>Asesor</th>
                                            <th>Cliente</th>
                                            <th>Valor de terreno</th>
                                            <th>Tipo Crédito</th>
                                            <th>Institución</th>
                                            <th>Observación</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="reubicacion in arrayReubicaciones" :key="reubicacion.id">
                                            <td class="td2" v-text="this.moment(reubicacion.fecha_reubicacion).locale('es').format('DD/MMM/YYYY')"></td>
                                            <td class="td2" v-text="reubicacion.fraccionamiento"></td>
                                            <td class="td2" v-text="reubicacion.etapa"></td>
                                            <td class="td2" v-text="reubicacion.manzana"></td>
                                            <td class="td2" v-text="reubicacion.num_lote"></td>
                                            <td class="td2" v-text="reubicacion.a_nombre.toUpperCase() + ' ' + reubicacion.a_apellidos.toUpperCase() "></td>
                                            <td class="td2" v-text="reubicacion.c_nombre.toUpperCase() + ' ' + reubicacion.c_apellidos.toUpperCase() "></td>
                                            <td class="td2" v-text="'$'+formatNumber(reubicacion.valor_terreno)"></td>
                                            <td class="td2" v-text="reubicacion.tipo_credito"></td>
                                            <td class="td2" v-text="reubicacion.institucion"></td>
                                            <td class="td2" v-text="reubicacion.observacion">
                                            </td>
                                        </tr>                               
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </template>
                    
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>

            <!-- Inicio Modal Fecha para firma -->
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
                 
                      <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Fecha de reubicación</label>
                                <div class="col-md-4">
                                    <input type="date" v-model="fecha_reubicacion" class="form-control" placeholder="Fecha status">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Proyecto</label>
                                <div class="col-md-5">
                                   <select class="form-control" @change="selectEtapas(fraccionamiento_id)" v-model="fraccionamiento_id" >
                                        <option value="">Proyecto</option>
                                        <option v-for="proyecto in arrayFraccionamientos" :key="proyecto.id" :value="proyecto.id" v-text="proyecto.nombre"></option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Etapa</label>
                                <div class="col-md-5">
                                    <select class="form-control" @change="selectManzanas(fraccionamiento_id,etapa_id)" v-model="etapa_id" >
                                        <option value="">Etapa</option>
                                        <option v-for="etapa in arrayEtapas" :key="etapa.id" :value="etapa.id" v-text="etapa.num_etapa"></option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Manzana</label>
                                <div class="col-md-4">
                                    <select class="form-control"  @change="selectLotesManzana(fraccionamiento_id, etapa_id, manzana)" v-model="manzana" >
                                        <option value="">Manzana</option>
                                        <option v-for="manzana in arrayManzanas" :key="manzana.manzana" :value="manzana.manzana" v-text="manzana.manzana"></option>
                                    </select>
                                </div>

                                <label class="col-md-2 form-control-label" for="text-input">Lote</label>
                                <div class="col-md-2">
                                    <select class="form-control" v-model="lote_id" @change="getPromociones(lote_id)" >
                                        <option value="">Lote</option>
                                        <option v-for="lote in arrayLotes" :key="lote.id" :value="lote.id" v-text="lote.num_lote"></option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Valor de terreno</label>
                                <div class="col-md-3">
                                    <input type="number" v-model="valor_terreno" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <h6 v-text="'$'+formatNumber(valor_terreno)"></h6>
                                </div>
                                
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Promocion</label>
                                <div class="col-md-4">
                                    <select class="form-control"  v-model="promocion" >
                                        <option value="">Promocion</option>
                                        <option value="Sin Promoción">Sin Promoción</option>
                                        <option v-for="promocion in arrayPromociones" :key="promocion.id" :value="promocion.descripcion" v-text="promocion.descripcion"></option>
                                    </select>
                                </div>
                                
                            </div>

                            <div v-if="promocion != '' && promocion != 'Sin Promoción'" class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Promocion</label>
                                <div class="col-md-9">
                                    <textarea disabled v-model="promocion" cols="80" rows="4"></textarea>
                                </div>
                                
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Tipo de Crédito</label>
                                <div class="col-md-4">
                                    <select class="form-control" v-model="tipo_credito" @change="selectInstitucion(tipo_credito)" >
                                        <option value="">Seleccione</option>
                                        <option v-for="creditos in arrayCreditos" :key="creditos.nombre" :value="creditos.nombre" v-text="creditos.nombre"></option>   
                                    </select>
                                </div>

                                <label class="col-md-2 form-control-label" for="text-input">Institución</label>
                                <div class="col-md-3">
                                    <select class="form-control" v-model="institucion" >
                                        <option value="">Seleccione</option>
                                        <option v-for="institucion in arrayInstituciones" :key="institucion.institucion_fin" :value="institucion.institucion_fin" v-text="institucion.institucion_fin"></option>      
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Asesor</label>
                                <div class="col-md-6">
                                   <select class="form-control" v-model="asesor_id" >
                                        <option value="">Seleccione</option>
                                        <option v-for="asesor in arrayAsesores" :key="asesor.id" :value="asesor.id" v-text="asesor.nombre + ' '+ asesor.apellidos"></option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Cliente</label>
                                <div class="col-md-6">
                                    <v-select 
                                        :on-search="selectCliente"
                                        label="n_completo"
                                        :options="arrayClientes"
                                        placeholder="Buscar Cliente"
                                        :onChange="getDatosCliente"
                                    >
                                    </v-select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Observacion</label>
                                <div class="col-md-6">
                                   <textarea class="form-control" v-model="observacion" cols="30" rows="2"></textarea>
                                </div>
                            </div>

                      </form>

                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" class="btn btn-primary" @click="storeReubicacion()">Guardar</button>
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
    import vSelect from 'vue-select';
    export default {
        props:{
            rolId:{type: String}
        },
        data(){
            return{
                id:0,
                arrayContratos:[],
                arrayReubicaciones:[],
                arrayFraccionamientos: [],
                arrayModelos:[],
                arrayEtapas:[],
                arrayManzanas:[],
                arrayLotes:[],

                arrayAsesores : [],
                arrayClientes : [],
                arrayCreditos : [],
                arrayInstituciones : [],

                arryaEmpresas:[],
                empresas:[],

                
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                myAlerts:{
                    popAlert : function(title = 'Alert',type = "success", description =''){
                        swal({
                            title: title,
                            type: type,
                            text: description,
                            showConfirmButton:false,
                            timer:1500,
                        })
                    }
                },
                offset : 3,
                buscar : '',
                buscar3 : '',
                b_etapa: '',
                b_manzana: '',
                b_publicidad:'',
                b_lote: '',
                b_status:'',
                b_fecha:'',
                b_fecha2:'',
                modal: 0,
                tituloModal: '',
                tipoAccion: 0,
                b_empresa:'',
                criterio:'personal.nombre',
                listado:0,

                contrato:{
                    'id':'',
                    'nombre':'',
                    'apellidos':'',
                    'fraccionamiento':'',
                    'etapa':'',
                    'manzana':'',
                    'num_lote':'',
                    'modelo':''
                },

                fraccionamiento_id:'',
                etapa_id:'',
                manzana:'',
                lote_id:'',
                asesor_id:'',
                cliente_id:'',
                promocion:'',
                tipo_credito:'',
                institucion:'',
                valor_terreno:0,
                observacion:'',
                fecha_reubicacion:'',

                fecha:'',
                observacion:'',
                arrayPromociones:[]
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
        components:{
            vSelect
        },
        methods : {
            listarContratos(page){
                let me = this;
                var url = '/contratos?page=' + page + '&buscar=' + me.buscar + '&buscar3=' + me.buscar3 + '&b_modelo=' + me.b_modelo + 
                    '&b_etapa=' +me.b_etapa+ '&b_manzana=' + me.b_manzana + '&b_lote='+ me.b_lote + '&b_status='+ me.b_status 
                    + '&criterio=' + me.criterio + '&f_ini=' + me.b_fecha + '&f_fin=' + me.b_fecha2 + '&publicidad=' + me.b_publicidad
                    +'&b_empresa='+this.b_empresa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayContratos = respuesta.contratos.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                })
            },

            verContrato(contrato){
                this.listado = 1;
                this.contrato = contrato;
                this.getReubicaciones(contrato.id);
            },

            selectCliente(search, loading){
                let me = this;
                loading(true)
                
                var url = '/clientes?page=1&criterio=personal.nombre&b_clasificacion=&buscar='+search;
                
                axios.get(url).then(function (response) {
                    
                    let respuesta = response.data;
                    q: search
                    me.arrayClientes = respuesta.personas.data;

                    loading(false)
                }).catch(function (error) {
                    console.log(error);
                });
            },
            getDatosCliente(cliente){
                this.cliente_id = cliente.id;
            },

            getReubicaciones(id){
                let me = this;
                var url = '/reubicaciones/getReubicaciones?id=' + id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayReubicaciones = respuesta;
                })
                .catch(function (error) {
                    console.log(error);
                })
            },

            cambiarPagina(page){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarContratos(page);
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
            //Select todas las etapas
            selectEtapas(fraccionamiento){
                let me = this;
                me.b_etapa="";
                me.b_manzana="";
                me.b_lote="";
                me.b_modelo="";

                me.etapa_id = '';
                me.manzana ='';
                me.lote_id = '';
                
                me.arrayEtapas=[];
                var url = '/select_etapa_proyecto?buscar=' + fraccionamiento;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEtapas = respuesta.etapas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            //Select todas las manzanas
            selectManzanas(fraccionamiento, etapa){
                let me = this;
                me.b_manzana="";
                me.b_lote="";
                me.manzana='';
                me.lote_id = '';


                me.arrayManzanas=[];
                var url = '/select_manzanas_etapa?buscar=' + fraccionamiento + '&buscar1='+ etapa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayManzanas = respuesta.manzana;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            //Select todos los lotes
            selectLotesManzana(fraccionamiento, etapa, manzana){
                let me = this;

                me.arrayLotes=[];
                me.lote_id = '';
                var url = '/select_lotes_manzana?buscar=' + fraccionamiento + '&buscar1='+ etapa + '&buscar2='+ manzana;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLotes = respuesta.lote_manzana;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectAsesores(){
                let me = this;
                me.arrayAsesores=[];
                var url = '/select/asesores';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayAsesores = respuesta.personas;
                })
                .catch(function (error) {
                    console.log(error);
                });
              
            },
        /////
            selectEmpresas(){
                let me = this;
                me.arryaEmpresas=[];
                
                var url = '/select_empresas';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arryaEmpresas = respuesta.empresas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectCreditos(){
                let me = this;
                me.arrayCreditos=[];
                var url = '/select_tipoCredito';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayCreditos = respuesta.Tipos_creditos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            storeReubicacion(){
                
                let me = this;
                //Con axios se llama el metodo store del controller
                axios.post('/reubicaciones/store',{
                    'contrato_id' : me.contrato.id,
                    'lote_id' : me.lote_id,
                    'cliente_id': me.cliente_id,
                    'asesor_id' : me.asesor_id,
                    'promocion' : me.promocion,
                    'tipo_credito' : me.tipo_credito,
                    'institucion': me.institucion,
                    'valor_terreno' : me.valor_terreno,
                    'observacion' : me.observacion,
                    'fecha_reubicacion' : me.fecha_reubicacion
                }).then(function (response){
                    me.cerrarModal();
                    //Se muestra mensaje Success
                    me.myAlerts.popAlert('Reubicacion guardada correctamente');
                }).catch(function (error){
                    console.log(error);
                });
            },
            
            abrirModal(){
                this.modal = 1;
                this.tituloModal = 'Registrar reubicación';
                this.arrayEtapas = [];
                this.arrayManzanas = [];
                this.arrayLotes = [];
                this.arrayClientes = [];
                this.arrayInstituciones = [];
                this.arrayPromociones=[];

                this.fraccionamiento_id='';
                this.etapa_id='';
                this.manzana='';
                this.lote_id='';
                this.valor_terreno=0;
                this.promocion='';
                this.credito = '';
                this.institucion ='';
                this.asesor_id = '';
                this.cliente_id = '';
                this.observacion = '';

            },
            cerrarModal(){
                this.modal = 0;
                this.getReubicaciones(this.contrato.id);
            },
            selectInstitucion(credito){
                let me = this;
                if(me.tipo_credito==0){
                    me.institucion="";
                }
                me.arrayInstituciones=[];
                var url = '/select_institucion?buscar='+credito;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayInstituciones = respuesta.instituciones;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            isNumber: function(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                    evt.preventDefault();;
                } else {
                    return true;
                }
            },
            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
            getEmpresa(){
                let me = this;
                me.empresas=[];
                var url = '/lotes/empresa/select';
                axios.get(url).then(function (response) {
                    var respuesta = response;
                    me.empresas = respuesta.data.empresas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getPromociones(lote){
                let me = this;
                me.arrayPromociones=[];
                var url = '/selectPromocion?lote='+lote;
                axios.get(url).then(function (response) {
                    var respuesta = response;
                    me.arrayPromociones = respuesta.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },    
        },
        mounted() {          
            this.listarContratos(1);
            this.selectFraccionamientos();
            this.selectAsesores();
            this.selectCreditos();
            this.getEmpresa();
        }
    }
</script>
<style>

    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .modal-body{
        height: 450px;
        width: 100%;
        overflow-y: auto;
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
    @media (min-width: 600px){
        .btnagregar{
        margin-top: 2rem;
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
    }
</style>
