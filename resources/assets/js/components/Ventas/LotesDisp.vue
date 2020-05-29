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
                        <i class="fa fa-align-justify"></i> Casas Disponibles
                        <!--   Boton descargar excel    -->
                         <a class="btn btn-success" v-bind:href="'/lotes/resume_excel_lotes_disp?buscar=' + buscar + '&buscar2=' + buscar2+ '&buscar3=' + buscar3 + '&b_modelo='+ b_modelo + '&b_lote='+ b_lote + '&b_apartado='+ b_apartado +'&criterio=' + criterio + '&rolId=' + rolId + '&casa_muestra=' + casa_muestra">
                            <i class="fa fa-file-text"></i>&nbsp; Descargar relacion
                        </a>
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row" v-if="criterio=='lotes.fraccionamiento_id'" >
                            <div class="col-md-10">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-3" v-model="criterio" @click="selectFraccionamientos()">
                                        <option value="lotes.fraccionamiento_id">Proyecto</option>
                                        <option value="modelos.nombre">Modelo</option>
                                        <option value="lotes.calle">Calle</option>
                                        <option value="lotes.fecha_termino_ventas">Fecha termino</option>
                                    </select>
                                    
                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar" @click="selectEtapa(buscar), selectModelo(buscar)">
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>

                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar2" @keyup.enter="listarLote(1,buscar,buscar2,buscar3,b_modelo,b_lote,criterio,rolId)"> 
                                        <option value="">Etapa</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                    </select>
                                    
                                </div>
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_modelo" @keyup.enter="listarLote(1,buscar,buscar2,buscar3,b_modelo,b_lote,criterio,rolId)">
                                        <option value="">Modelo</option>
                                        <option v-for="modelos in arrayModelos" :key="modelos.id" :value="modelos.id" v-text="modelos.nombre"></option>
                                    </select>

                                    <input type="text" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar3" @keyup.enter="listarLote(1,buscar,buscar2,buscar3,b_modelo,b_lote,criterio,rolId)" class="form-control" placeholder="Manzana a buscar">
                                    
                                </div>
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <input type="text" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_lote" @keyup.enter="listarLote(1,buscar,buscar2,buscar3,b_modelo,b_lote,criterio,rolId)" class="form-control" placeholder="Lote a buscar">
                                    <select class="form-control" v-if="rolId!='2'" v-model="b_apartado" @keyup.enter="listarLote(1,buscar,buscar2,buscar3,b_modelo,b_lote,criterio,rolId)">
                                        <option value="">Todos</option>
                                        <option value=0>Sin apartar</option>
                                        <option value=1>Apartados</option>
                                        
                                    </select>

                                    <select class="form-control" v-model="casa_muestra" @keyup.enter="listarLote(1,buscar,buscar2,buscar3,b_modelo,b_lote,criterio,rolId)">
                                        <option value="">Todos</option>
                                        <option value=0>Lotes en venta</option>
                                        <option value=1>Casas muestra</option>
                                    </select>
                                    
                                </div>
                                <button type="submit" @click="listarLote(1,buscar,buscar2,buscar3,b_modelo,b_lote,criterio,rolId)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                <span style="font-size: 1em; text-align:center;" class="badge badge-dark" v-text="'Total: '+ contador"> </span>
                            </div>
                        </div>
                        <div class="form-group row" v-else >
                            <div class="col-md-8">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-4" v-model="criterio" @click="selectFraccionamientos()">
                                        <option value="lotes.fraccionamiento_id">Proyecto</option>
                                        <option value="modelos.nombre">Modelo</option>
                                        <option value="lotes.calle">Calle</option>
                                        <option value="lotes.fecha_termino_ventas">Fecha termino</option>
                                    </select>
                                    
                                    <input type="text" v-if="criterio=='modelos.nombre'" v-model="buscar" @keyup.enter="listarLote(1,buscar,buscar2,buscar3,b_modelo,b_lote,criterio,rolId)" class="form-control" placeholder="Texto a buscar">
                                    <input type="text" v-if="criterio=='lotes.calle'" v-model="buscar" @keyup.enter="listarLote(1,buscar,buscar2,buscar3,b_modelo,b_lote,criterio,rolId)" class="form-control" placeholder="Texto a buscar">
                                    <input type="date" v-if="criterio=='lotes.fecha_termino_ventas'" v-model="buscar" @keyup.enter="listarLote(1,buscar,buscar2,buscar3,b_modelo,b_lote,criterio,rolId)" class="form-control" placeholder="Texto a buscar">                                    
                                    <select class="form-control" v-if="rolId!='2'" v-model="b_apartado" @keyup.enter="listarLote(1,buscar,buscar2,buscar3,b_modelo,b_lote,criterio,rolId)">
                                        <option value="">Todos</option>
                                        <option value=0>Sin apartar</option>
                                        <option value=1>Apartados</option>
                                    </select>

                                    <select class="form-control" v-model="casa_muestra" @keyup.enter="listarLote(1,buscar,buscar2,buscar3,b_modelo,b_lote,criterio,rolId)">
                                        <option value="">Todos</option>
                                        <option value=0>Lotes en venta</option>
                                        <option value=1>Casas muestra</option>
                                    </select>
                                    
                                </div>
                                <button type="submit" @click="listarLote(1,buscar,buscar2,buscar3,b_modelo,b_lote,criterio,rolId)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                <span style="font-size: 1em; text-align:center;" class="badge badge-dark" v-text="'Total: '+ contador"> </span>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th v-if="rolId != '2'">Opciones</th>
                                        <th>Proyecto</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th># Lote</th>
                                        <th>% Avance</th>
                                        <th style="text-align:center;">Modelo</th>
                                        <th>Calle</th>
                                        <th># Oficial</th>
                                        <th style="width:8%">Terreno m&sup2;</th>
                                        <th>Construc. m&sup2;</th>
                                        <th>Precio Base</th>
                                        <th>Terreno Excedente</th>
                                        <th>Obra extra</th>
                                        <th>Sobreprecios</th>
                                        <th>Precio venta</th>
                                        <th>Promoción</th>
                                        <th>Fecha termino</th>
                                        <th>Canal de venta</th>
                                        <th>% de venta extra</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="lote in arrayLote" :key="lote.id">
                                         
                                        <td class="td2" v-if="rolId != '2'" style="width:5%">
                                            <button v-if="lote.apartado == 0" title="Apartar" type="button" @click="abrirModal('lote','apartar',lote)" class="btn btn-warning btn-sm">
                                            <i class="icon-lock"></i>
                                            </button>
                                            <template v-else>
                                                <button title="Mostrar Apartado" type="button" @click="abrirModal('lote','mostrarApartado',lote)" class="btn btn-primary btn-sm">
                                                <i class="icon-magnifier"></i>
                                                </button>
                                                <span class="badge2 badge-light"> Cliente: {{lote.c_nombre}} {{lote.c_apellidos}}/Vendedor: {{lote.v_nombre}}/ {{lote.fecha_apartado}}</span>
                                            </template>
                                            
                                            
                                        </td>
                                        
                                        <td class="td2" style="width:20%" v-text="lote.proyecto"></td>
                                        <td class="td2" style="width:20%" v-text="lote.etapa"></td>
                                        <td class="td2" v-text="lote.manzana"></td>
                                            <td v-if="!lote.sublote" v-text="lote.num_lote"></td>
                                            <td v-else v-text="lote.num_lote + '-' + lote.sublote"></td>
                                        <td class="td2" v-text="lote.avance + '%'"></td>
                                        <td class="td2">
                                            <span class="badge badge-success" v-text="lote.modelo"></span>
                                            <span v-if="lote.casa_muestra == 1" class="badge badge-danger">Casa muestra</span>
                                        </td>
                                        <td class="td2" v-text="lote.calle"></td>
                                            <td class="td2" v-if="!lote.interior" v-text="lote.numero"></td>
                                            <td class="td2" v-else v-text="lote.numero + '-' + lote.interior" ></td>
                                        <td class="td2" v-text="lote.terreno"></td>
                                        <td class="td2" v-text="lote.construccion"></td>
                                        <td class="td2" v-text="'$'+formatNumber(lote.precio_base)"></td>
                                        <td class="td2" v-text="'$'+formatNumber(lote.excedente_terreno)"></td>
                                        <td class="td2" v-text="'$'+formatNumber(lote.obra_extra)"></td>
                                        <td class="td2" v-text="'$'+formatNumber(lote.sobreprecio)"></td>
                                        <td class="td2" style="width:20%" v-text="'$'+formatNumber(lote.precio_venta)"></td>
                                        <td class="td2" v-if="lote.promocion != 'Sin Promoción'">
                                            <button title="Ver paquete" type="button" class="btn btn-info pull-right" @click="mostrarPromo(lote.promocion)">Ver promoción</button>
                                        </td>
                                        <td class="td2" v-else v-text="lote.promocion"></td>
                                        <td class="td2" v-text="this.moment(lote.fecha_termino_ventas).locale('es').format('MMMM YYYY')"></td>
                                        <td class="td2" style="width:40%" v-text="lote.comentarios"></td>
                                        <td class="td2" v-if="lote.tipo == 0" style="width:40%" v-text="lote.extra + '%'"></td>
                                        <td class="td2" v-else-if="lote.tipo == 1" style="width:40%" v-text="lote.extra_ext + '%'"></td>
                                        <td class="td2" v-else style="width:40%" v-text="'Interno: ' + lote.extra + '% Externo: ' + lote.extra_ext +'%'"></td>
                                    </tr>                               
                                </tbody>
                            </table>  
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page > 7">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(1,buscar,buscar2,buscar3,b_modelo,b_lote,criterio,rolId)">Inicio</a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,buscar2,buscar3,b_modelo,b_lote,criterio,rolId)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,buscar2,buscar3,b_modelo,b_lote,criterio,rolId)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,buscar2,buscar3,b_modelo,b_lote,criterio,rolId)">Sig</a>
                                </li>
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page<pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.last_page,buscar,buscar2,buscar3,b_modelo,b_lote,criterio,rolId)">Ultimo</a>
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
                            
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Vendedor</label>
                                    <div class="col-md-6">
                                       <select v-model="vendedor_id" id="myselect" class="form-control"  @click="selectClientes(vendedor_id)" >
                                            <option value="0">Seleccione</option>
                                            <option v-for="vendedores in arrayVendedores" :key="vendedores.id" :value="vendedores.id" v-text="vendedores.n_completo"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Cliente</label>
                                    <div class="col-md-6">
                                       <select v-model="cliente_id" id="myselect"  class="form-control" >
                                            <option value="0">Seleccione</option>
                                            <option v-for="clientes in arrayClientes" :key="clientes.id" :value="clientes.id" v-text="clientes.n_completo"></option>
                                        </select>
                                    </div>
                                </div>
                             
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Tipo de Crédito</label>
                                    <div class="col-md-6">
                                       <select id="myselect" v-model="credito"  class="form-control" >
                                            <option value="">Seleccione</option>
                                            <option v-for="creditos in arrayCreditos" :key="creditos.nombre" :value="creditos.nombre" v-text="creditos.nombre"></option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha apartado</label>
                                    <div class="col-md-4" >
                                        <label v-if="tipoAccion==2" v-text="fecha_mostrar"></label>
                                        <label v-if="tipoAccion==3" v-text="fecha_apartado_format"></label>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Comentario</label>
                                    <div class="col-md-6" >
                                        <textarea rows="3" cols="30" v-model="comentario" class="form-control" placeholder="Comentario"></textarea>
                                    </div>
                                </div>

                                
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="apartarLote()">Apartar</button>
                            <button type="button" v-if="tipoAccion==3" class="btn btn-warning" @click="desapartarLote()">Desapartar</button>
                            <button type="button" v-if="tipoAccion==3" class="btn btn-success" @click="actualizarLote()">Guardar</button>
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

<script>
    export default {
        props:{
            rolId:{type: String}
        },
        data(){
            return{
                proceso:false,
                id: 0,
                cliente_id:0,
                cliente:'',
                vendedor_id:0,
                lote_id: 0,
                apartado:0,
                b_modelo:'',
                b_lote:'',
                fraccionamiento_id:0,
                contador: 0,
                credito:'',
                fecha_apartado:'',
                fecha_mostrar:'',
                comentario: '',
                arrayLote : [],
                arrayFraccionamientos :[],
                arrayClientes:[],
                arrayVendedores:[],
                arrayCreditos:[],
                arrayDatosApartado: [],
                arrayEtapas: [],
                arrayModelos: [],

                modal : 0,
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
                offset : 3,
                criterio : 'lotes.fraccionamiento_id', 
                buscar2 : '',
                buscar3 : '',
                buscar : '',
                b_apartado : '',
                casa_muestra : 0,
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
            listarLote(page, buscar, buscar2, buscar3, b_modelo, b_lote, criterio,rol){
                let me = this;
                var url = '/lotesDisponibles?page=' + page + '&buscar=' + buscar + '&buscar2=' + buscar2+ '&buscar3=' + buscar3 + '&b_modelo='+ b_modelo + '&b_lote='+ b_lote + '&b_apartado='+ this.b_apartado +'&criterio=' + criterio + '&rolId=' + rol + '&casa_muestra=' + this.casa_muestra ; 
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLote = respuesta.lotes.data;
                    me.pagination = respuesta.pagination;
                    me.contador = me.pagination.total;
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
            selectClientes(vendedor){
                let me = this;
                me.arrayClientes=[];
                var url = '/select_clientes?vendedor_id=' + vendedor +'&fraccionamiento_id=' + this.fraccionamiento_id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayClientes = respuesta.clientes;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectEtapa(buscar){
                let me = this;
                me.buscar2=""
                me.buscar3=""
                
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

            mostrarPromo(promo){
                 Swal({
                    title: promo,
                    animation: false,
                    customClass: 'animated tada'
                    })
            },

            selectVendedores(){
                let me = this;
                me.arrayVendedores=[];
                var url = '/select_vendedores';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayVendedores = respuesta.vendedores;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            selectDatosApartado(lote_id){
                let me = this;
                me.arrayDatosApartado=[];
                var url = '/select_datos_apartado?lote_id=' + lote_id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayDatosApartado = respuesta.apartados;
                    me.vendedor_id = me.arrayDatosApartado[0].vendedor_id;
                    me.cliente_id = me.arrayDatosApartado[0].cliente_id;
                    me.fecha_apartado = me.arrayDatosApartado[0].fecha_apartado;
                    me.credito = me.arrayDatosApartado[0].tipo_credito;
                    me.comentario = me.arrayDatosApartado[0].comentario;
                    me.fecha_apartado_format=moment(me.fecha_apartado).locale('es').format("DD [de] MMMM [de] YYYY");
                    me.selectClientes(me.vendedor_id);
                   
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
            cambiarPagina(page, buscar, buscar2, buscar3,b_modelo, b_lote, criterio,rol){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarLote(page,buscar,buscar2,buscar3,b_modelo, b_lote,criterio,rol);
            },
            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
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
            validarLote(){
                this.errorLote=0;
                this.errorMostrarMsjLote=[];

                if(!this.fraccionamiento_id) //Si la variable Lote esta vacia
                    this.errorMostrarMsjLote.push("El nombre del proyecto para el Lote no puede ir vacio.");

                if(this.errorMostrarMsjLote.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorLote = 1;

                return this.errorLote;
            },

            apartarLote(){
                 if(this.validarLote() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/apartado/registrar',{                 
                    'vendedor_id': this.vendedor_id,
                    'cliente_id': this.cliente_id,
                    'tipo_credito': this.credito,
                    'fecha_apartado': this.fecha_apartado,
                    'comentario': this.comentario,
                    'lote_id': this.lote_id,
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarLote(me.pagination.current_page,me.buscar,me.buscar2,me.buscar3,me.b_modelo,me.b_lote,me.criterio,me.rolId); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Lote apartado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },

            actualizarLote(){
                  if(this.validarLote() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.put('/apartado/actualizar',{                 
                    'vendedor_id': this.vendedor_id,
                    'cliente_id': this.cliente_id,
                    'tipo_credito': this.credito,
                    'fecha_apartado': this.fecha_apartado,
                    'comentario': this.comentario,
                    'lote_id': this.lote_id,
                    'id': this.apartado,
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarLote(me.pagination.current_page,me.buscar,me.buscar2,me.buscar3,me.b_modelo,me.b_lote,me.criterio,me.rolId); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Lote apartado actualizado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },

            desapartarLote(){
                swal({
                title: '¿Desea desapartar este lote?',
                text: "El lote quedara disponible para apartar!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si!'
                }).then((result) => {
                if (result.value) {
                    let me = this;

                axios.delete('/apartado/eliminar', 
                        {params: {'lote_id': this.lote_id,
                        'id':this.apartado
                        }}).then(function (response){
                        swal(
                        'Desapartado!',
                        'Lote desapartado correctamente.',
                        'success'
                        )
                        me.cerrarModal();
                        me.listarLote(me.pagination.current_page,me.buscar,me.buscar2,me.buscar3,me.b_modelo,me.b_lote,me.criterio,me.rolId);
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },

            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.fraccionamiento_id=0;
                this.cliente_id=0;
                this.vendedor_id=0;
                this.credito='';
                this.comentario='';
                this.errorLote = 0;
                this.errorMostrarMsjLote = [];
                this.id=0;
                this.lote_id=0;
                this.cliente="";
                this.apartado=0;

            },
            cerrarModal2(){
                this.modal2 = 0;
                this.tituloModal2 = '';
            },

            cerrarModal3(){
                this.modal3 = 0;
                this.tituloModal3 = '';
                this.tipoAccion = 1;
            },
                cerrarModal4(){
                this.modal4 = 0;
                this.tituloModal4 = '';
                this.buscar_fraccionamientoExcel = 0;
            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(lote, accion,data =[]){
                switch(lote){
                    case "lote":
                    {
                        switch(accion){
                            
                            case 'apartar':
                            {
                                this.modal =1;
                                this.tituloModal='Realizar apartado';
                                this.fraccionamiento_id=data['fraccionamiento_id'];
                                this.lote_id=data['id'];
                                this.fecha_apartado=moment().locale('es').format('YYYY-MM-DD');
                                this.fecha_mostrar=moment(this.fecha_apartado).locale('es').format("DD [de] MMMM [de] YYYY");
                                this.tipoAccion=2;
                                break;
                            }

                             case 'mostrarApartado':
                            {
                                this.selectDatosApartado(data['id']);
                                this.modal =1;
                                this.tituloModal='Lote apartado';
                                this.fraccionamiento_id=data['fraccionamiento_id'];
                                this.lote_id=data['id'];
                                this.apartado=data['apartado'];
                                this.tipoAccion=3;
                                break;
                            }

                            
                        }
                    }
                }
                //this.selectFraccionamientos();
                this.selectVendedores();
                this.selectClientes(this.vendedor_id);

            }
        },
        mounted() {
            this.listarLote(1,this.buscar,this.buscar2,this.buscar3,this.b_modelo,this.b_lote,this.criterio,this.rolId);
            this.selectFraccionamientos();
            this.selectCreditos();
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
    .badge2 {
    display: inline-block;
    padding: 0.25em 0.4em;
    font-size: 90%;
    font-weight: bold;
    line-height: 1;
    color: #080808c9;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
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