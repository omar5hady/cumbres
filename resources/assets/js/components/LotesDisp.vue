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
                        <i class="fa fa-align-justify"></i> Lotes Disponibles
                        <!--   Boton Nuevo    -->
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-5" v-model="criterio" @click="selectFraccionamientos()">
                                        <option value="lotes.fraccionamiento_id">Proyecto</option>
                                        <option value="modelos.nombre">Modelo</option>
                                        <option value="lotes.calle">Calle</option>
                                    </select>
                                    
                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar" >
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>

                                    <input type="text" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar2" @keyup.enter="listarLote(1,buscar,buscar2,buscar3,criterio,rolId)" class="form-control" placeholder="Etapa">
                                    <input type="text" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar3" @keyup.enter="listarLote(1,buscar,buscar2,buscar3,criterio,rolId)" class="form-control" placeholder="Manzana a buscar">

                                    <input type="text" v-if="criterio=='modelos.nombre'" v-model="buscar" @keyup.enter="listarLote(1,buscar,buscar2,buscar3,criterio,rolId)" class="form-control" placeholder="Texto a buscar">
                                    <input type="text" v-if="criterio=='lotes.calle'" v-model="buscar" @keyup.enter="listarLote(1,buscar,buscar2,buscar3,criterio,rolId)" class="form-control" placeholder="Texto a buscar">
                                                                        
                                    <input type="text" v-if="criterio=='fraccionamientos.nombre'" v-model="buscar" @keyup.enter="listarLote(1,buscar,buscar2,buscar3,criterio,rolId)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarLote(1,buscar,buscar2,buscar3,criterio,rolId)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th v-if="rolId == '1'">Opciones</th>
                                        <th>Proyecto</th>
                                        <th>Manzana</th>
                                        <th># Lote</th>
                                        <th>% Avance</th>
                                        <th>Modelo</th>
                                        <th>Calle</th>
                                        <th># Oficial</th>
                                        <th style="width:8%">Terreno m&sup2;</th>
                                        <th>Construc. m&sup2;</th>
                                        <th>Precio Base</th>
                                        <th>Terreno Excedente</th>
                                        <th>Sobreprecios</th>
                                        <th>Precio venta</th>
                                        <th>Promoción</th>
                                        <th>Fecha termino</th>
                                        <th>Canal de venta</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="lote in arrayLote" :key="lote.id">
                                         
                                        <td v-if="rolId == '1'" style="width:5%">
                                            <button title="Apartar" type="button" @click="abrirModal('lote','apartar',lote)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                            </button>
                                        </td>
                                        
                                        
                                        <td style="width:20%" v-text="lote.proyecto"></td>
                                        <td v-text="lote.manzana"></td>
                                            <td v-if="!lote.sublote" v-text="lote.num_lote"></td>
                                            <td v-else v-text="lote.num_lote + '-' + lote.sublote"></td>
                                        <td v-text="lote.avance"></td>
                                        <td>
                                            <span class="badge badge-success" v-text="lote.modelo"></span>
                                        </td>
                                        <td v-text="lote.calle"></td>
                                            <td v-if="!lote.interior" v-text="lote.numero"></td>
                                            <td v-else v-text="lote.numero + '-' + lote.interior" ></td>
                                        <td v-text="lote.terreno"></td>
                                        <td v-text="lote.construccion"></td>
                                        <td v-text="'$'+formatNumber(lote.precio_base)"></td>
                                        <td v-text="'$'+formatNumber(lote.excedente_terreno)"></td>
                                        <td v-text="'$'+formatNumber(lote.sobreprecio)"></td>
                                        <td style="width:20%" v-text="'$'+formatNumber(lote.precio_venta)"></td>
                                        <td v-text="lote.promocion"></td>
                                        <td v-text="this.moment(lote.fecha_fin).locale('es').format('MMMM YYYY')"></td>
                                        <td style="width:40%" v-text="lote.comentarios"></td>
                                    </tr>                               
                                </tbody>
                            </table>  
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page > 7">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(1,buscar,buscar2,buscar3,criterio,rolId)">Inicio</a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,buscar2,buscar3,criterio,rolId)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,buscar2,buscar3,criterio,rolId)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,buscar2,buscar3,criterio,rolId)">Sig</a>
                                </li>
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page<pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.last_page,buscar,buscar2,buscar3,criterio,rolId)">Ultimo</a>
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
                                       <select v-model="vendedor_id" id="myselect" class="form-control" @click="selectClientes(vendedor_id)" >
                                            <option value="0">Seleccione</option>
                                            <option v-for="vendedores in arrayVendedores" :key="vendedores.id" :value="vendedores.id" v-text="vendedores.n_completo"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Cliente</label>
                                    <div class="col-md-6">
                                       <select v-model="cliente_id" id="myselect" class="form-control" >
                                            <option value="0">Seleccione</option>
                                            <option v-for="clientes in arrayClientes" :key="clientes.id" :value="clientes.id" v-text="clientes.n_completo"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Tipo de Crédito</label>
                                    <div class="col-md-6">
                                       <select id="myselect" v-model="credito" class="form-control" >
                                            <option value="">Seleccione</option>
                                            <option v-for="creditos in arrayCreditos" :key="creditos.nombre" :value="creditos.nombre" v-text="creditos.nombre"></option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha apartado</label>
                                    <div class="col-md-4" >
                                        <label v-text="fecha_mostrar"></label>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarLote()">Apartar</button>
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
                vendedor_id:0,
                fraccionamiento_id:0,
                credito:'',
                comentarios: '',
                fecha_apartado:'',
                fecha_mostrar:'',
                arrayLote : [],
                arrayFraccionamientos :[],
                arrayClientes:[],
                arrayVendedores:[],
                arrayCreditos:[],
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
                criterio : 'modelos.nombre', 
                buscar2 : '',
                buscar3 : '',
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
            listarLote(page, buscar, buscar2, buscar3, criterio,rol){
                let me = this;
                var url = '/lotesDisponibles?page=' + page + '&buscar=' + buscar + '&buscar2=' + buscar2+ '&buscar3=' + buscar3 + '&criterio=' + criterio + '&rolId=' + rol;
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
            selectClientes(vendedor){
                let me = this;
                me.arrayClientes=[];
                var url = '/select_clientes?vendedor_id=' + vendedor + '&fraccionamiento_id=' + this.fraccionamiento_id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayClientes = respuesta.clientes;
                })
                .catch(function (error) {
                    console.log(error);
                });
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
            cambiarPagina(page, buscar, buscar2, buscar3, criterio,rol){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarLote(page,buscar,buscar2,buscar3,criterio,rol);
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

            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.fraccionamiento_id=0;
                this.cliente_id=0;
                this.vendedor_id=0;
                this.credito='';
                this.errorLote = 0;
                this.errorMostrarMsjLote = [];

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
                                this.fecha_apartado=moment().locale('es').format('YYYY-MM-DD');
                                this.fecha_mostrar=moment(this.fecha_apartado).locale('es').format("DD [de] MMMM [de] YYYY");
                                this.tipoAccion=2;
                                break;
                            }

                            
                        }
                    }
                }
                this.selectFraccionamientos();
                this.selectVendedores();

            }
        },
        mounted() {
            this.listarLote(1,this.buscar,this.buscar2,this.buscar3,this.criterio,this.rolId);
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
    table {
    margin: auto;
    border-collapse: collapse;
    overflow-x: auto;
    display: block;
    width: fit-content;
    max-width: 100%;
    box-shadow: 0 0 1px 1px rgba(0, 0, 0, .1);
    }

    td, th {
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

    td {
    white-space: nowrap;
    border-bottom: none;
    color: rgb(20, 20, 20);
    }

    td:first-of-type, th:first-of-type {
    border-left: none;
    }

    td:last-of-type, th:last-of-type {
    border-right: none;
    }    
</style>