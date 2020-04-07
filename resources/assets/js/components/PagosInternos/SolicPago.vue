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
                        <i class="fa fa-align-justify"></i>Solicitudes de pago
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text"  v-model="buscar" @keyup.enter="listarOrdenes(1)" class="form-control" placeholder="# de orden">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <label class="form-control"> Fecha de solicitud</label>
                                    <input type="date"  v-model="b_fecha1" @keyup.enter="listarOrdenes(1)" class="form-control">
                                    <input type="date"  v-model="b_fecha2" @keyup.enter="listarOrdenes(1)" class="form-control">
                                   
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <button type="submit" @click="listarOrdenes(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    <!-- <a class="btn btn-success" v-bind:href="'/acta_terminacion/excel?buscar=' + buscar + '&b_manzana=' + b_manzana + '&b_lote='+ b_lote + '&criterio=' + criterio + '&buscar2=' + buscar2" >
                                        <i class="icon-pencil"></i>&nbsp;Excel
                                    </a> -->
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th colspan="4"></th>
                                        <th colspan="3" class="text-center">Documentos</th>
                                        <th colspan="3"></th>
                                    </tr>
                                    <tr>
                                        <th>#</th>
                                        <th>Solicitante</th>
                                        <th>Fecha de solicitud</th>
                                        <th>Concepto</th>
                                        <th>Orden compra</th>
                                        <th class="td2">Solicitud de Cheque</th>
                                        <th>Otros</th>
                                        <th>Pago</th>
                                        <th colspan="2">Status</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="orden in arraySolicitud" :key="orden.id">
                                        <td class="td2" v-text="orden.id"></td>
                                        <td class="td2" v-text="orden.nombre+' '+orden.apellidos"></td>
                                        <td class="td2" v-text="this.moment(orden.created_at).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td class="td2" v-text="orden.concepto"></td>

                                        <template v-if="orden.orden_compra == 0">
                                            <td class="td2"> No aplica</td>
                                            <td class="td2 text-center" >
                                                <button type="button" @click="verSoliCheque(orden.solic_cheque)" class="btn btn-primary btn-sm" title="Solicitud de Cheque">
                                                    <i class="fa fa-download"></i>
                                                </button>
                                                <button v-if="orden.check1 == null && orden.status != 5" type="button" @click="vistoBuenoSolicitud(orden.id)" class="btn btn-light btn-sm" title=" Visto bueno de cheque">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                                <button v-if="orden.check2 == null && orden.check1 && orden.status != 5" type="button" @click="autorizarSolicitud(orden.id)" class="btn btn-dark btn-sm" title=" Autorizar solicitud de cheque">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                                <span v-if="orden.check2" style="font-size: 0.85em; text-align:center;" class="badge badge-dark" v-text="'  Autorizado el: ' + this.moment(orden.check2).locale('es').format('DD/MMM/YYYY')"></span>
                                            </td>
                                        </template>

                                        <template v-if="orden.orden_compra == 1">
                                            <td class="td2 text-center"> 
                                                <button type="button" @click="verOrdenCompra(orden.doc_orden)" class="btn btn-success btn-sm" title="Orden de compra">
                                                    <i class="fa fa-download"></i>
                                                </button>
                                                <button v-if="orden.orden_vistoBueno == null && orden.autorizacion_orden == null && orden.status != 5" type="button" @click="vistoBuenoOrden(orden.id)" class="btn btn-default btn-sm" title=" Visto bueno orden de compra">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                                <button v-if="orden.autorizacion_orden == null && orden.orden_vistoBueno && orden.status != 5" type="button" @click="autorizarOrden(orden.id)" class="btn btn-dark btn-sm" title=" Autorizar orden de compra">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                                <span v-if="orden.autorizacion_orden" style="font-size: 0.85em; text-align:center;" class="badge badge-dark" v-text="'  Autorizado el: ' + this.moment(orden.autorizacion_orden).locale('es').format('DD/MMM/YYYY')"></span>
                                            </td>
                                            <td v-if="orden.autorizacion_orden == null && orden.status != 5">
                                                Orden de compra sin autorizacion
                                            </td>
                                            <td v-if="orden.autorizacion_orden == null && orden.status == 5">
                                                <span class="badge badge-danger">Cancelado</span>
                                            </td>
                                            <td v-if="orden.autorizacion_orden && orden.solic_cheque == null && orden.status != 5">
                                                Sin solicitud de cheque
                                            </td>
                                            <td v-if="orden.autorizacion_orden && orden.solic_cheque == null && orden.status == 5">
                                                <span class="badge badge-danger">Cancelado</span>
                                            </td>
                                            <td class="td2 text-center" v-if="orden.autorizacion_orden && orden.solic_cheque">
                                                <button type="button" @click="verSoliCheque(orden.solic_cheque)" class="btn btn-primary btn-sm" title="Solicitud de Cheque">
                                                    <i class="fa fa-download"></i>
                                                </button>
                                                <button v-if="orden.check1 == null && orden.status != 5" type="button" @click="vistoBuenoSolicitud(orden.id)" class="btn btn-light btn-sm" title=" Visto bueno de cheque">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                                <button v-if="orden.check2 == null && orden.check1 && orden.status != 5" type="button" @click="autorizarSolicitud(orden.id)" class="btn btn-dark btn-sm" title=" Autorizar solicitud de cheque">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                                <span v-if="orden.check2" style="font-size: 0.85em; text-align:center;" class="badge badge-dark" v-text="'  Autorizado el: ' + this.moment(orden.check2).locale('es').format('DD/MMM/YYYY')"></span>
                                            </td>
                                        </template>
                                        

                                        <!-- Otros documentos -->
                                            <td class="td2" v-if="orden.autorizacion_orden == null && orden.orden_compra == 1 && orden.status != 5">
                                                Orden de compra sin autorizacion
                                            </td>
                                            <td class="td2" v-else-if="orden.autorizacion_orden == null && orden.orden_compra == 1 && orden.status == 5">
                                                <span class="badge badge-danger">Cancelado</span>
                                            </td>
                                            <td class="td2" v-else-if="orden.autorizacion_orden && orden.solic_cheque == null && orden.status != 5|| orden.solic_cheque == null && orden.orden_compra == 0 && orden.status != 5">
                                                Sin solicitud de cheque
                                            </td>
                                            <td class="td2" v-else-if="orden.autorizacion_orden && orden.solic_cheque == null && orden.status == 5|| orden.solic_cheque == null && orden.orden_compra == 0 && orden.status == 5">
                                                <span class="badge badge-danger">Cancelado</span>
                                            </td>
                                            <td class="td2 text-center" v-else>
                                                <div class="form-group row text-center">
                                                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false" @click="getDocumentos(orden.id)"><i class="icon-cloud-download"> Otros</i></a>
                                                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
                                                        <a v-for="archivo in arrayDocumentos" :key="archivo.id" class="dropdown-item" href="#" v-on:click="verDocumento(archivo.archivo)">{{archivo.nombre}}</a>
                                                    </div>
                                                </div>
                                            </td>
                                        
                                        <!-- Pagar -->
                                            <td class="td2 text-center">
                                                <label v-if="orden.check2 == null && orden.status != 5"> Solicitud sin autorizar</label>
                                                <span v-else-if="orden.check2 == null && orden.status == 5" class="badge badge-danger">Cancelado</span>
                                                <button v-else-if="orden.check3 == null && orden.status != 5" type="button" @click="pagarSolicitud(orden.id)" class="btn btn-success btn-sm" title=" Guardar pago">
                                                    <i class="fa fa-money"></i>
                                                </button>
                                                <span v-else-if="orden.check3 == null && orden.status == 5" class="badge badge-danger">Cancelado</span>
                                                <span v-else class="badge badge-success">Pagado el: {{this.moment(orden.check3).locale('es').format('DD/MMM/YYYY')}}</span>
                                            </td>

                                        <!--STATUS-->
                                            <td class="td2 text-center">
                                                <span v-if="orden.status == null" class="badge badge-warning">Pendiente</span>
                                                <span v-if="orden.status == 0" class="badge badge-warning">Orden de compra en proceso</span>
                                                <span v-if="orden.status == 1" class="badge badge-primary">Orden de compra autorizada</span>
                                                <span v-if="orden.status == 2" class="badge badge-primary">Solicitud de cheque en proceso</span>
                                                <span v-if="orden.status == 3" class="badge badge-primary">Solicitud de cheque autorizado</span>
                                                <span v-if="orden.status == 4" class="badge badge-success">Solicitud pagada</span>
                                                <span v-if="orden.status == 5" class="badge badge-danger">Cancelado</span>
                                            </td>
                                            <td class="td2 text-center">
                                                <button v-if="orden.status < 4" type="button" @click="cancelarSolicitud(orden.id)" class="btn btn-danger btn-sm" title="Cancelar">
                                                    <i class="fa fa-window-close"></i>
                                                </button>
                                                <span v-else-if="orden.status == 5" class="badge badge-danger">Cancelado el: {{this.moment(orden.fecha_status).locale('es').format('DD/MMM/YYYY')}}</span>
                                            </td>
                                        <td>
                                            <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right" 
                                                    @click="abrirModal('observaciones',orden)">Observaciones</button>
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
            <!--Inicio del modal agregar/actualizar-->

            <!--Inicio del modal generacion-->
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
                                    <label class="col-md-3 form-control-label" for="text-input">Observacion</label>
                                    <div class="col-md-6">
                                         <textarea rows="3" cols="30" v-model="observacion" class="form-control" placeholder="Observacion"></textarea>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button"  class="btn btn-primary" @click="agregarComentario()">Guardar</button>
                                    </div>
                                </div>

                                
                                <table class="table table-bordered table-striped table-sm" >
                                    <thead>
                                        <tr>
                                            <th>Usuario</th>
                                            <th>Observacion</th>
                                            <th>Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="observacion in arrayObservacion" :key="observacion.id">
                                            
                                            <td v-text="observacion.usuario" ></td>
                                            <td v-text="observacion.observacion" ></td>
                                            <td v-text="observacion.created_at"></td>
                                        </tr>                               
                                    </tbody>
                                </table>
                                
                            </form>
                              
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

                proceso:false,
                id: 0,
                tituloModal : '',
                arraySolicitud : [],
                arrayDocumentos :[],
                arrayObservacion : [],
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
                criterio : 'lotes.fraccionamiento_id', 
                buscar : '',
                b_fecha1:'',
                b_fecha2:'',
                modal:0,
                observacion:''
                
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

            listarOrdenes(page){
                let me = this;
                var url = '/solic_pago/indexSolicitudes?page=' + page + '&buscar=' + me.buscar + '&fecha1=' + me.b_fecha1 + '&fecha2=' + me.b_fecha2 + '&tipo=1'; 
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arraySolicitud = respuesta.solicitudes.data;
                    me.pagination = respuesta.pagination;
                    console.log(url);
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },

            listarObservacion(buscar){
                let me = this;
                var url = '/solic_pago/indexComentarios?id=' + buscar ;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayObservacion = respuesta.observacion;
                    console.log(url);
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },

            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.observacion = '';

            },

            agregarComentario(){
                
                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/solic_pago/storeComentarios',{
                    'id': this.id,
                    'observacion': this.observacion
                }).then(function (response){
                    me.listarObservacion(me.id);
                    me.observacion = '';
                    //me.cerrarModal3(); //al guardar el registro se cierra el modal
                    
                    const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                    });

                    toast({
                    type: 'success',
                    title: 'Observación Agregada Correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            cambiarPagina(page){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarOrdenes(page);
            },

            autorizarOrden(id){
                 
                let me = this;
                //Con axios se llama el metodo update de LoteController
                
                 Swal({
                    title: 'Estas seguro?',
                    animation: false,
                    customClass: 'animated bounceInDown',
                    text: "La orden de compra quedara autorizada",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    
                    confirmButtonText: 'Si, autorizar!'
                    }).then((result) => {

                    if (result.value) {
                       
                        axios.post('/solic_pago/autorizarOrden',{
                            'id':id
                        }); 
                        
                        me.listarOrdenes(me.pagination.current_page);
                        Swal({
                            title: 'Hecho!',
                            text: 'Orden de compra autorizada',
                            type: 'success',
                            animation: false,
                            customClass: 'animated bounceInRight'
                        })
                    }
                })
              
            },

            autorizarSolicitud(id){
                 
                let me = this;
                //Con axios se llama el metodo update de LoteController
                
                 Swal({
                    title: 'Estas seguro?',
                    animation: false,
                    customClass: 'animated bounceInDown',
                    text: "La solicitud de cheque quedara autorizado",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    
                    confirmButtonText: 'Si, autorizar!'
                    }).then((result) => {

                    if (result.value) {
                       
                        axios.post('/solic_pago/autorizarSolicitud',{
                            'id':id
                        }); 
                        
                        me.listarOrdenes(me.pagination.current_page);
                        Swal({
                            title: 'Hecho!',
                            text: 'Solicitud de cheque autorizado',
                            type: 'success',
                            animation: false,
                            customClass: 'animated bounceInRight'
                        })
                    }
                })
              
            },

            pagarSolicitud(id){
                 
                let me = this;
                //Con axios se llama el metodo update de LoteController
                
                 Swal({
                    title: 'Estas seguro?',
                    animation: false,
                    customClass: 'animated bounceInDown',
                    text: "Se registrara el pago de esta solicitud",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    
                    confirmButtonText: 'Si, guardar!'
                    }).then((result) => {

                    if (result.value) {
                       
                        axios.post('/solic_pago/pagarSolicitud',{
                            'id':id
                        }); 
                        
                        me.listarOrdenes(me.pagination.current_page);
                        Swal({
                            title: 'Hecho!',
                            text: 'Solicitud pagada',
                            type: 'success',
                            animation: false,
                            customClass: 'animated bounceInRight'
                        })
                    }
                })
              
            },

            abrirModal(accion,data =[]){
                switch(accion){
                    
                    case 'observaciones':{
                        this.id = data['id'];
                        this.modal = 1;
                        this.tituloModal = 'Observaciones';
                        this.observacion = '';
                        this.listarObservacion(this.id)
                        break;
                    }
                }
            },

            vistoBuenoSolicitud(id){
                 
                let me = this;
                //Con axios se llama el metodo update de LoteController
                
                 Swal({
                    title: 'Estas seguro?',
                    animation: false,
                    customClass: 'animated bounceInDown',
                    text: "La solicitud de cheque pasara a proceso de autorización",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    
                    confirmButtonText: 'Si, continuar!'
                    }).then((result) => {

                    if (result.value) {
                       
                        axios.post('/solic_pago/vistoBuenoSolicitud',{
                            'id':id
                        }); 
                        
                        me.listarOrdenes(me.pagination.current_page);
                        Swal({
                            title: 'Hecho!',
                            text: 'Solicitud en proceso de autorización',
                            type: 'success',
                            animation: false,
                            customClass: 'animated bounceInRight'
                        })
                    }
                })
              
            },

            vistoBuenoOrden(id){
                 
                let me = this;
                //Con axios se llama el metodo update de LoteController
                
                 Swal({
                    title: 'Estas seguro?',
                    animation: false,
                    customClass: 'animated bounceInDown',
                    text: "La orden de compra pasara a proceso de autorización",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    
                    confirmButtonText: 'Si, continuar!'
                    }).then((result) => {

                    if (result.value) {
                       
                        axios.post('/solic_pago/vistoBuenoOrden',{
                            'id':id
                        }); 
                        
                        me.listarOrdenes(me.pagination.current_page);
                        Swal({
                            title: 'Hecho!',
                            text: 'Orden de compra en proceso de autorización',
                            type: 'success',
                            animation: false,
                            customClass: 'animated bounceInRight'
                        })
                    }
                })
              
            },

            verSoliCheque(nombre){
                window.open('/files/solicPago/solicCheque/'+ nombre, '_blank')
            },

            verCotizacion(nombre){
                window.open('/files/solicPago/cotizacion/'+ nombre, '_blank')
            },

            verPagoPartes(nombre){
                window.open('/files/solicPago/pagoPartes/'+ nombre, '_blank')
            },

            verFactura(nombre){
                window.open('/files/solicPago/factura/'+ nombre, '_blank')
            },

            verDocumento(nombre){
                window.open('/files/solicPago/documentos/'+ nombre, '_blank')
            },

            verOrdenCompra(nombre){
                window.open('/files/solicPago/ordenCompra/'+ nombre, '_blank')
            },

            cancelarSolicitud(id){

                let me = this;
                Swal.fire({
                    type:'warning',
                    title: 'Cancelar solicitud',
                    text: "Este proceso no se puede revertir",
                    showCancelButton : true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    input:'text',
                    inputAttributes:{
                        maxlength:300,
                        autocapitalize:'off'
                    },
                    inputValidator:(text)=>{
                        if(!text) return "Escribir el motivo de cancelación";
                    }
                }).then((text)=>{
                    if(text.value){
                        axios.post('/solic_pago/cancelarSolicitud',{
                            'id': id,
                            'motivo': text.value
                        }).then(function (response){
                            me.cerrarModal();
                            me.listarOrdenes(1);
                            //window.alert("Cambios guardados correctamente");
                            swal({
                                position: 'top-end',
                                type: 'success',
                                title: 'Solcitud cancelada correctamente',
                                showConfirmButton: false,
                                timer: 1500
                                })
                        }).catch(function (error){
                            console.log(error);
                        });
                    }
                })
                    
            },

            
        },
        
        mounted() {
            this.listarOrdenes(1);
        }
    }
</script>
<style>
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
    
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none; 
    margin: 0;  
    }
</style>
