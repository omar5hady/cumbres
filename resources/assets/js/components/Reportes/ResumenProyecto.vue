<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Resumen de Proyecto
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <select class="form-control" @change="selectEtapas(b_proyecto)" v-model="b_proyecto" >
                                        <option value="">Fraccionamiento</option>
                                        <option v-for="proyecto in arrayFraccionamientos" :key="proyecto.id" :value="proyecto.id" v-text="proyecto.nombre"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <select class="form-control" v-model="b_etapa" >
                                        <option value="">Etapa</option>
                                        <option v-for="etapa in arrayAllEtapas" :key="etapa.id" :value="etapa.id" v-text="etapa.num_etapa"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <select class="form-control" v-model="bAudit" >
                                        <option value="0">¿Auditado?</option>
                                        <option value="1">Sin Auditar</option>
                                        <option value="2">Auditado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <button type="submit" @click="listarResumen(1,b_proyecto,b_etapa)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row" v-if="mostrar == 1">
                            <div class="col-md-3">
                                <div class="input-group">
                                   <table class="table table-bordered table-striped table-sm">
                                       <tr>
                                           <th>Total de Lotes</th>
                                           <th v-text="lotes"></th>
                                       </tr>
                                       <tr>
                                           <th>Lotes en venta</th>
                                           <th v-text="habilitados"></th>
                                       </tr>
                                       <tr>
                                           <th>Individualizadas</th>
                                           <th v-text="individualizadas"></th>
                                       </tr>
                                       <tr>
                                           <th>Vendidos</th>
                                           <th v-text="vendidas"></th>
                                       </tr>
                                       <tr>
                                           <th>Disponibles</th>
                                           <th v-text="disponibles"></th>
                                       </tr>
                                       <tr>
                                           <th colspan="2"></th>
                                       </tr>
                                       <tr>
                                           <th colspan="2">Fecha de inicio de ventas:</th>
                                       </tr>
                                       <tr>
                                           <th style="text-align: right;" colspan="2" v-text="fecha_inicio"></th>
                                       </tr>
                                       <tr>
                                           <th>Promedio de venta mensual:</th>
                                           <th v-text="promedioMensual"></th>
                                       </tr>
                                       
                                   </table>
                                </div>
                            </div>
                            <div class="col-md-3"></div>
                            <div class="col-md-5">
                                <div class="input-group">
                                   <table class="table table-bordered table-striped table-sm">
                                       <tr>
                                           <th>Total Precio Venta</th>
                                           <th v-text="'$'+formatNumber(precio_venta)"></th>
                                       </tr>
                                       <tr>
                                           <th>Total Enganche</th>
                                           <th v-text="'$'+formatNumber(enganche - excedente - directo)"></th>
                                       </tr>
                                       <tr>
                                        <th>Crédito Directo</th>
                                           <th v-text="'$'+formatNumber(directo)"></th>
                                       </tr>
                                       <tr v-if="excedente > 0">
                                           <th>Enganche excedente por apartados</th>
                                           <th v-text="'$'+formatNumber(excedente)"></th>
                                       </tr>
                                       <tr>
                                           <th>Total Crédito</th>
                                           <th v-text="'$'+formatNumber(credito)"></th>
                                       </tr>
                                       <tr>
                                           <th colspan="2"></th>
                                       </tr>
                                       <tr>
                                           <th>Monto cobrado</th>
                                           <th v-text="'$'+formatNumber(monto_cobrado)"></th>
                                       </tr>
                                       <tr>
                                           <th>Saldo por cobrar</th>
                                           <th v-text="'$'+formatNumber(saldo)"></th>
                                       </tr>
                                       <tr>
                                           <br>
                                           <br>
                                           <br>
                                           <th colspan="2"></th>
                                       </tr>
                                       <tr>
                                           <th>Precio promedio de venta</th>
                                           <th v-text="'$'+formatNumber(precio_venta/(individualizadas+vendidas))"></th>
                                       </tr>
                                   </table>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row" v-if="mostrar == 1">
                            <div class="col-md-3">
                                <a class="btn btn-success" v-bind:href="'/estadisticas/res_proyecto/excel?proyecto='+ b_proyecto + '&etapa='+ b_etapa + '&bAudit='+bAudit">
                                    <i class="fa fa-file-text"></i>&nbsp; Descargar excel
                                </a>
                            </div>
                        </div>

                        <hr>

                        <ul class="nav nav2 nav-tabs" id="myTab1" role="tablist">
                            <li class="nav-item">
                                <a @click="tab = 1" class="nav-link" v-bind:class="{ 'text-info active': tab==1 }" v-text="'Resuemn contratos'"></a>
                            </li>
                            <li class="nav-item">
                                <a @click="tab = 2" class="nav-link" v-bind:class="{ 'text-info active': tab==2 }" v-text="'Lotes disponibles'"></a>
                            </li>
                        </ul>

                        <!-- Resuemn contratos -->
                        <div class="tab-content" id="myTab1Content">
                            <div class="tab-pane fade" v-bind:class="{ 'active show': tab==1 }" v-if="tab == 1">
                                <div class="table-responsive" v-if="mostrar == 1">
                                <table class="table2 table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Manzana</th>
                                            <th># Lote</th>
                                            <th>Modelo</th>
                                            <th>Direccion</th>
                                            <th>Venta</th>
                                            <th>Cliente</th>
                                            <th>Institución</th>
                                            <th>Tipo Crédito</th>
                                            <th>Firma de escrituras</th>
                                            <th>Precio venta</th>
                                            <th>Enganche</th>
                                            <th>Crédito</th>
                                            <th>Saldo</th>
                                            <th v-if="rolId == 1 || rolId == 9">Auditoria</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="contrato in arrayResProyecto" :key="contrato.id">
                                            <td class="td2" v-text="contrato.manzana"></td>
                                            <td class="td2" v-text="contrato.num_lote"></td>
                                            <td class="td2" v-text="contrato.modelo"></td>
                                            <td v-if="contrato.interior == null" class="td2" v-text="contrato.calle + ' Num. '+ contrato.numero"></td>
                                            <td v-else class="td2" v-text="contrato.calle + ' Num. '+ contrato.numero + '-' + contrato.interior"></td>
                                            <td class="td2" v-text="this.moment(contrato.fecha_status).locale('es').format('DD/MMM/YYYY')"></td>
                                            <td class="td2" v-text="contrato.nombre_cliente.toUpperCase()"></td>
                                            <td class="td2" v-text="contrato.institucion"></td>
                                            <td class="td2" v-text="contrato.tipo_credito"></td>
                                            <td v-if="contrato.fecha_firma_esc == null" class="td2" v-text="''"></td>
                                            <td v-else class="td2" v-text="this.moment(contrato.fecha_firma_esc).locale('es').format('DD/MMM/YYYY')"></td>
                                            <td class="td2" v-text="'$'+formatNumber(contrato.precio_venta - contrato.descuento_promocion + contrato.costo_paquete)"></td>
                                            <td class="td2" v-text="'$'+formatNumber(contrato.total_pagar)"></td>
                                            <td class="td2" v-text="'$'+formatNumber(contrato.monto_total_credito)"></td>
                                            <td class="td2" v-text="'$'+formatNumber(contrato.saldo)"></td>
                                            <td v-if="rolId == 1 || rolId == 9" class="td2">
                                                <button class="btn btn-dark" @click="abrirModal('auditar',contrato.id)" v-if="contrato.fecha_audit == null">Auditar</button>
                                                <button class="btn btn-success" @click="abrirModal('observaciones',contrato.id),listarObservacion(contrato.id)" v-else>{{'Auditado el: ' + this.moment(contrato.fecha_audit).locale('es').format('DD/MMM/YYYY')}}</button>
                                            </td>
                                        </tr>                                
                                    </tbody>
                                </table>
                                </div>
                                <nav>
                                    <ul class="pagination">
                                        <li class="page-item" v-if="pagination.current_page > 1">
                                            <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,b_proyecto,b_etapa)">Ant</a>
                                        </li>
                                        <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                            <a class="page-link" href="#" @click.prevent="cambiarPagina(page,b_proyecto,b_etapa)" v-text="page"></a>
                                        </li>
                                        <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                            <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,b_proyecto,b_etapa)">Sig</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>

                        <!-- Resuemn contratos -->
                        <div class="tab-content" id="myTab1Content">
                            <div class="tab-pane fade" v-bind:class="{ 'active show': tab==2 }" v-if="tab == 2">
                                <div class="table-responsive" v-if="mostrar == 1">
                                <table class="table2 table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Manzana</th>
                                            <th># Lote</th>
                                            <th>Modelo</th>
                                            <th>Direccion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="lote in arrayLotesDisponibles.data" :key="lote.id">
                                            <td class="td2" v-text="lote.manzana"></td>
                                            <td class="td2" v-text="lote.num_lote"></td>
                                            <td class="td2" v-text="lote.modelo"></td>
                                            <td v-if="lote.interior == null" class="td2" v-text="lote.calle + ' Num. '+ lote.numero"></td>
                                            <td v-else class="td2" v-text="lote.calle + ' Num. '+ lote.numero + '-' + lote.interior"></td>
                                        </tr>                                
                                    </tbody>
                                </table>
                                </div>
                                <nav>
                                    <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="arrayLotesDisponibles.current_page > 5" @click="listarResumen(1,b_proyecto,b_etapa)">
                                    <a class="page-link" href="#" >Inicio</a>
                                </li>
                                <li class="page-item" v-if="arrayLotesDisponibles.current_page > 1"
                                    @click="listarResumen(arrayLotesDisponibles.current_page-1,b_proyecto,b_etapa)">
                                    <a class="page-link" href="#" >Ant</a>
                                </li>

                                <li class="page-item" v-if="arrayLotesDisponibles.current_page-3 >= 1"
                                    @click="listarResumen(arrayLotesDisponibles.current_page-3,b_proyecto,b_etapa)">
                                    <a class="page-link" href="#" v-text="arrayLotesDisponibles.current_page-3"></a>
                                </li>
                                <li class="page-item" v-if="arrayLotesDisponibles.current_page-2 >= 1"
                                    @click="listarResumen(arrayLotesDisponibles.current_page-2,b_proyecto,b_etapa)">
                                    <a class="page-link" href="#" v-text="arrayLotesDisponibles.current_page-2"></a>
                                </li>
                                <li class="page-item" v-if="arrayLotesDisponibles.current_page-1 >= 1"
                                    @click="listarResumen(arrayLotesDisponibles.current_page-1)">
                                    <a class="page-link" href="#" v-text="arrayLotesDisponibles.current_page-1"></a>
                                </li>
                                <li class="page-item active" >
                                    <a class="page-link" href="#" v-text="arrayLotesDisponibles.current_page"></a>
                                </li>
                                <li class="page-item" 
                                    v-if="arrayLotesDisponibles.current_page+1 <= arrayLotesDisponibles.last_page">
                                    <a class="page-link" href="#" @click="listarResumen(arrayLotesDisponibles.current_page+1,b_proyecto,b_etapa)" 
                                    v-text="arrayLotesDisponibles.current_page+1"></a>
                                </li>
                                <li class="page-item" 
                                    v-if="arrayLotesDisponibles.current_page+2 <= arrayLotesDisponibles.last_page">
                                    <a class="page-link" href="#" @click="listarResumen(arrayLotesDisponibles.current_page+2,b_proyecto,b_etapa)"
                                     v-text="arrayLotesDisponibles.current_page+2"></a>
                                </li>
                                <li class="page-item" 
                                    v-if="arrayLotesDisponibles.current_page+3 <= arrayLotesDisponibles.last_page">
                                    <a class="page-link" href="#" @click="listarResumen(arrayLotesDisponibles.current_page+3,b_proyecto,b_etapa)"
                                    v-text="arrayLotesDisponibles.current_page+3"></a>
                                </li>

                                <li class="page-item" v-if="arrayLotesDisponibles.current_page < arrayLotesDisponibles.last_page"
                                    @click="listarResumen(arrayLotesDisponibles.current_page+1,b_proyecto,b_etapa)">
                                    <a class="page-link" href="#" >Sig</a>
                                </li>
                                <li class="page-item" v-if="arrayLotesDisponibles.current_page < 5 && arrayLotesDisponibles.last_page > 5" @click="listarResumen(arrayLotesDisponibles.last_page,b_proyecto,b_etapa)">
                                    <a class="page-link" href="#" >Ultimo</a>
                                </li>
                            </ul>
                                </nav>
                            </div>
                        </div>


                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>

            <!--Inicio del modal avaluo-->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="titulo"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- form para solicitud de avaluo -->
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Observacion</label>
                                    <div class="col-md-6">
                                         <textarea rows="3" cols="40" v-model="comentario" class="form-control" placeholder="Observacion"></textarea>
                                    </div>
                                    <div class="col-md-2" v-if="tipoAccion == 1">
                                        <button type="button" class="btn btn-primary" @click="agregarComentario()">Guardar</button>
                                    </div>

                                </div>

                                <table v-if="tipoAccion == 1" class="table table-bordered table-striped table-sm" >
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
                                            <td v-text="observacion.comentario" ></td>
                                            <td v-text="observacion.created_at"></td>
                                        </tr>                               
                                    </tbody>
                                </table>

                            </form>
                            <!-- fin del form solicitud de avaluo -->

                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion == 0" class="btn btn-primary" @click="auditar()">Aceptar</button>
                        </div>
                    </div>
                      <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal consulta-->


        </main>
</template>

<script>
    export default {
        props:{
            rolId:{type: String}
        },
        data (){
            return {
                proceso:false,
                arrayResProyecto : [],
                arrayLotesDisponibles : [],
                arrayFraccionamientos: [],
                arrayAllEtapas:[],
                arrayObservacion:[],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                b_proyecto : '',
                b_etapa : '',
                precio_venta:0,
                enganche:0,
                directo:0,
                credito:0,
                contratos:0,
                saldo:0,
                monto_cobrado:0,
                lotes:0,
                disponibles:0,
                vendidas:0,
                individualizadas:0,
                habilitados:0,
                mostrar : 0,
                fecha_inicio:'',
                excedente:0,
                modal:0,
                comentario:'',
                id:'',
                tipoAccion : 0,
                titulo : '',
                bAudit: 0,
                tab: 1,
            }
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;             

            }
        },
        methods : {
            listarResumen (page,proyecto,etapa){
                let me=this;
                var url= '/estadisticas/res_proyecto?page=' + page + '&proyecto='+ proyecto + '&etapa='+ etapa+
                        '&bAudit='+this.bAudit;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayResProyecto = respuesta.resContratos.data;
                    me.arrayLotesDisponibles = respuesta.lotesDisp;
                    me.pagination= respuesta.pagination;
                    me.lotes = respuesta.lotes;
                    me.disponibles = respuesta.disponibles;
                    me.vendidas = respuesta.vendidas;
                    me.contratos = respuesta.contratos;
                    me.individualizadas = respuesta.individualizadas;
                    me.habilitados = respuesta.habilitados;
                    me.fecha_inicio = respuesta.fecha_inicio;
                    me.meses = respuesta.diferencia;

                    me.promedioMensual = ((me.individualizadas + me.vendidas + me.contratos)/me.meses).toFixed(2);

                    me.precio_venta = respuesta.sumas[0].precio - respuesta.sumas[0].descuento + respuesta.sumas[0].paquete;
                    me.enganche = respuesta.sumas[0].enganche;
                    me.credito = respuesta.sumas[0].credito_netoSum;
                    me.saldo = respuesta.sumas[0].totSaldo;
                    me.directo = respuesta.directo[0].enganche;
                    

                    me.monto_cobrado = me.precio_venta - me.saldo;
                    me.excedente = (me.enganche + me.credito) - me.precio_venta;
                    me.mostrar = 1;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
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
            listarObservacion(buscar){
                let me = this;
                var url = '/auditar/indexObservacion?id=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayObservacion = respuesta.observacion;
                    console.log(url);
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },
            agregarComentario(){
                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/auditar/registrarObservacion',{
                    'id': this.id,
                    'comentario': this.comentario
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
            selectEtapas(buscar){
                let me = this;
                
                me.arrayAllEtapas=[];
                var url = '/select_etapa_proyecto?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayAllEtapas = respuesta.etapas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            auditar(){
                 
                let me = this;
                //Con axios se llama el metodo update de LoteController
                
                 Swal({
                    title: 'Estas seguro?',
                    animation: false,
                    customClass: 'animated bounceInDown',
                    text: "El contrato quedara auditado",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    
                    confirmButtonText: 'Si, auditar!'
                    }).then((result) => {

                    if (result.value) {
                       
                        axios.post('/contratos/auditar',{
                            'id':this.id,
                            'comentario' : this.comentario,
                        }); 
                        
                        me.cerrarModal();
                        me.listarResumen(me.pagination.current_page,me.b_proyecto,me.b_etapa);
                        Swal({
                            title: 'Hecho!',
                            text: 'Contrato auditado',
                            type: 'success',
                            animation: false,
                            customClass: 'animated bounceInRight'
                        })
                    }
                })
              
            },
            abrirModal(accion,id){
                switch(accion){
                    case 'auditar':{
                        this.modal = 1;
                        this.comentario = '';
                        this.id = id;
                        this.titulo = 'Auditar';
                        this.tipoAccion = 0;
                        break;
                    }
                    case 'observaciones':{
                        this.modal = 1;
                        this.comentario = '';
                        this.titulo = 'Observaciones';
                        this.id = id;
                        this.tipoAccion = 1;
                        //this.listarObservacion(this.id);
                        break;
                    }
                }
                
            },
            cerrarModal(){
                this.modal = 0;
                this.comentario = '';
                this.listarResumen(this.pagination.current_page,this.b_proyecto,this.b_etapa);
                this.id = '';
            },
            cambiarPagina(page,proyecto,etapa){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarResumen(page,proyecto,etapa);
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
    }
    .div-error{
        display: flex;
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
</style>
