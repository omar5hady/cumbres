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
                        <i class="fa fa-align-justify"></i> Créditos Puente
                        <button v-if="vista == 1" @click="listarAvisos(1)" class="btn btn-secondary">
                            <i class="fa fa-mail-reply"></i>&nbsp;Regresar
                        </button>
                    </div>
                    <!-- Div Card Body para listar -->
                    <template v-if="vista == 0">
                        <div class="card-body"> 
                            <div class="form-group row">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <select class="form-control" @keyup.enter="listarAvisos(1)" v-model="buscar" >
                                            <option value="">Seleccione</option>
                                            <option v-for="proyecto in arrayProyectos" :key="proyecto.id" :value="proyecto.id" v-text="proyecto.nombre"></option>
                                        </select>
                                        <input type="text" class="form-control" v-model="b_folio" @keyup.enter="listarAvisos(1)" placeholder="Folio">
                                    </div>
                                    <div class="input-group">
                                        <button type="submit" @click="listarAvisos(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                           
                            <div class="table-responsive">
                                <table class="table2 table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Folio</th>
                                            <th>Proyecto</th>
                                            <th>Número de Cuenta</th>
                                            <th>Avance Edificación</th>
                                            <th>Avance Urbanizacion</th>
                                            <th></th>
                                            <!-- <th>Fecha solicitud</th>
                                            <th>Status</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="creditosPuente in arrayCreditosPuente" :key="creditosPuente.id" @click="getLotesPuente(creditosPuente)">
                                            <td>
                                                <a href="#" v-text="creditosPuente.folio"></a>
                                            </td>
                                            <td class="td2" v-text="creditosPuente.proyecto"></td>
                                            <td class="td2" v-text="creditosPuente.num_cuenta"></td>
                                            <td class="td2"> {{formatNumber(creditosPuente.avance)}}%</td>
                                            <td class="td2"> {{formatNumber(creditosPuente.avanceUrb)}}%</td>
                                            <td class="td2">
                                                <button type="button" @click="abrirModal('obs',creditosPuente.id)" class="btn btn-dark btn-sm" title="Observaciones">
                                                    <i class="fa fa-book">&nbsp;Observaciones</i>
                                                </button>
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
                    <template v-if="vista == 1">
                        <div class="card-body"> 
                            <div class="col-xl-12 col-lg-5 col-md-4">
                                <div class="card">

                                     <!-- Cabecera con datos generales del crédito -->
                                        <div>
                                             <div class="card-body p-12 d-flex align-items-center">
                                                <div>
                                                    <div class="text-value text-uppercase font-weight-bold text-primary text-center">
                                                        Estado de cuenta del Crédito Puente "{{datosPuente.proyecto}} {{datosPuente.lotes}} VIV"
                                                    </div>
                                                </div>
                                                <br>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-left:0px;">
                                            <div class="card-body p-3 d-flex align-items-center">
                                                <div>
                                                    <div class="text-uppercase font-weight-bold" 
                                                        v-text="'Folio: '">
                                                    </div>
                                                    
                                                    <div class="text-uppercase font-weight-bold" 
                                                        v-text="'Institucion de crédito: '">
                                                    </div>
                                                    <div class="text-uppercase font-weight-bold" 
                                                        v-text="'Número de casas: '">
                                                    </div>
                                                    <div class="text-uppercase font-weight-bold" 
                                                        v-text="'Ingresos a: '">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body p-3 d-flex align-items-center">
                                                <div>
                                                    <div class="text-uppercase" 
                                                        v-text="datosPuente.folio">
                                                    </div>
                                                    <div class="text-uppercase" 
                                                        v-text="datosPuente.banco">
                                                    </div>
                                                    <div class="text-uppercase" 
                                                        v-text="datosPuente.lotes">
                                                    </div>
                                                    <div class="text-uppercase" 
                                                        v-text="'CTA DE '+ datosPuente.banco+' '+datosPuente.num_cuenta">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                </div>
                            </div>
                           
                            <div class="table-responsive">
                                <table class="table2 table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th># Lote</th>
                                            <th>Manzana</th>
                                            <th>Modelo</th>
                                            <th>Avance Edificación</th>
                                            <th>Avance Urbanizacion</th>
                                            <!-- <th>Fecha solicitud</th>
                                            <th>Status</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="lote in arrayLotes" :key="lote.id">
                                            <td v-text="lote.num_lote"></td>
                                            <td v-text="lote.manzana"></td>
                                            <td v-text="lote.modelo"></td>
                                            <td v-bind:class="{'listo':lote.avance >= 99, 'zero':lote.avance <25}"> {{formatNumber(lote.avance)}}%</td>
                                            <td> {{lote.avanceUrb}}/{{lote.conteoUrb}}</td>
                                            
                                        </tr>                               
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </template>
                    
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>

            <!--Inicio del modal obs-->
                <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal == 1}" 
                    role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-primary modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" v-text="tituloModal"></h4>
                                <button type="button" class="close" @click="cerrarModal(0)" aria-label="Close">
                                <span aria-hidden="true">×</span>
                                </button>
                            </div>

                                <div class="modal-body">
                                    <template v-if="tipoAccion == 1">
                                        
                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Nueva observación</label>
                                                <div class="col-md-6">
                                                    <input type="text" v-model="observacion" class="form-control">
                                                </div>

                                                <div class="col-md-3">
                                                    <button v-if="observacion != ''" class="btn btn-primary" @click="storeObs()">Guardar</button>
                                                </div>
                                            </div>

                                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                                <table class="table table-bordered table-striped table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Usuario</th>
                                                            <th>Observacion</th>
                                                            <th>Fecha</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="observacion in arrayObs" :key="observacion.id">
                                                            <td v-text="observacion.usuario" ></td>
                                                            <td v-text="observacion.observacion" ></td>
                                                            <td v-text="observacion.created_at"></td>
                                                        </tr>                               
                                                    </tbody>
                                                </table>
                                            </form>
                                    </template>

                                </div>
                                <!-- Botones del modal -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" @click="cerrarModal(0)">Cerrar</button>
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
        props:{
            userName:{type: String}
        },
        data(){
            return{
                proceso:false,
                id:0,
                arrayCreditosPuente : [],
                vista:0,
                tipoAccion: 0,
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                datosPuente:{
                    'banco':'',
                    'apertura':'',
                    'anticipo':'',
                    'pre_puente':'',
                    'interes':'',
                    'viv_canceladas':'',
                    'viv_por_cancelar':'',
                    'lotes':0,
                    'num_cuenta':'',
                    'credito_otorgado':0,
                },
                offset : 3,
                buscar : '',
                b_folio:'',
                b_status:3,
                arrayProyectos : [],
                arrayCargos : [],
                
                modal:0,
                tituloModal:'',
                tipoAccion:1,
                arrayObs:[],
                arrayLotes:[],
                
                observacion:'',
            }
        },
        components:{
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            totalPagar: function(){
                let me = this;
                var total_pago =0;
                    total_pago =parseFloat(me.interes) + parseFloat(me.cantidad); 
                return total_pago;
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
            listarAvisos(page){
                let me = this;
                me.vista = 0;
                var url = '/cPuentes/indexCreditosAvances?page=' + page + '&fraccionamiento=' + me.buscar + '&folio=' + me.b_folio + '&status=' + me.b_status;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayCreditosPuente = respuesta.creditos.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            getLotesPuente(data){
                let me = this;
                me.id = data.id;
                me.arrayLotes=[];
                me.datosPuente = data;
                me.vista = 1;
                var url = '/cPuentes/lotesAvance?id=' + me.id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLotes = respuesta.lotes;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            getObs(id){
                let me = this;
                me.arrayObs=[];
                var url = '/cPuentes/getObs?id=' + id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayObs = respuesta.obs;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            selectProyectos(){
                let me = this;
                me.arrayProyectos=[];
                var url = '/select_fraccionamiento';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayProyectos = respuesta.fraccionamientos;
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
                me.listarAvisos(page);
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
            
            storeObs(){
                let me = this;
                axios.post('/cPuentes/storeObs',{
                    'id': this.id,
                    'observacion': this.observacion,
                   
                }).then(function (response){
                    //Obtener detalle
                        swal({
                            position: 'top-end',
                            type: 'success',
                            title: 'Comentario guardado correctamente',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        me.getObs(me.id);
                        me.observacion = '';
                    
                }).catch(function (error){
                    console.log(error);
                });
            },
            abrirModal(opcion,id){
                switch(opcion){
                    case 'obs':{
                        this.modal = 1;
                        this.tituloModal = 'Comentarios para el Crédito Puente';
                        this.arrayObs = [];
                        this.id = id;
                        this.tipoAccion = 1;
                        this.getObs(id);
                        break;
                    }
                }
            },istrarAbono(pagoLote){
                let me = this;
                axios.post('/cPuentes/storeAbono',{
                    'id': me.id,
                    'fecha': me.fecha,
                    'concepto' : me.concepto,
                    'monto' : me.cantidad,
                    'tipo' : me.tipo,
                    'interes' : me.interes,
                    'pagoLote' : pagoLote,
                    'lote_id' : me.lote_id,
                    'lotePuenteId' : me.lotePuenteId
                   
                }).then(function (response){
                    //Obtener detalle
                        swal({
                            position: 'top-end',
                            type: 'success',
                            title: 'Abono registrado correctamente',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        me.cerrarModal(1);
                        me.getEdoCuenta(me.datosPuente.id);
                    
                }).catch(function (error){
                    console.log(error);
                });
            },
            cerrarModal(vista){
                this.modal = 0;
                this.tituloModal = '';
                this.observacion = '';
                if(vista == 0)
                    this.listarAvisos(vista);
            },
        },
        mounted() {
            this.listarAvisos(1);
            this.selectProyectos();
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
    }
    .listo{
        color: green;
    }
    .zero{
        color:red;
    }
</style>