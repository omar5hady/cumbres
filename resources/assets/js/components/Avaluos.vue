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
                        <i class="fa fa-align-justify"></i>Avaluos

                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-10">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-5" v-model="criterio" @click="selectFraccionamientos()">
                                        <option value="lotes.fraccionamiento_id">Proyecto</option>
                                        <option value="licencias.visita_avaluo">Fecha de visita</option>
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

                                    <input v-else type="date"  v-model="buscar" @keyup.enter="listarAvaluos(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarAvaluos(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                   
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr> 
                                        <th>Folio</th>
                                        <th>Cliente</th>
                                        <th>Proyecto</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th>Lote</th>
                                        <th>Modelo</th>
                                        <th>Avance obra</th>
                                        <th>Solicitud Ventas</th>
                                        <th>Valor Solicitado</th>
                                        <th>Fecha solicitud avaluo</th>
                                        <th>Pago realizado</th>
                                        <th>Fecha de visita</th>
                                        <th>Estatus</th>
                                        <th>Fecha concluido</th>
                                        <th>Seguro de calidad</th>
                                        <th>Valor concluido</th>
                                        <th>Costo</th>
                                        <th>Enviado a ventas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="avaluos in arrayAvaluos" :key="avaluos.folio"> 
                                        
                                        <td class="td2" v-text="avaluos.folio"></td>
                                        <td class="td2" v-text="avaluos.nombre + ' ' + avaluos.apellidos"></td>
                                        <td class="td2" v-text="avaluos.fraccionamiento"></td>
                                        <td class="td2" v-text="avaluos.etapa"></td>
                                        <td class="td2" v-text="avaluos.manzana"></td>
                                        <td class="td2" v-text="avaluos.num_lote"></td>
                                        <td class="td2" v-text="avaluos.modelo"></td>
                                        <td class="td2" v-text="avaluos.avance + '%'"></td>
                                        <td class="td2" v-text="this.moment(avaluos.fecha_solicitud).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td class="td2" v-text="'$'+formatNumber(avaluos.valor_requerido)"></td>

                                        <td class="td2" v-if="avaluos.fecha_ava_sol"
                                            v-text="this.moment(avaluos.fecha_ava_sol).locale('es').format('DD/MMM/YYYY')">
                                        </td>
                                        <td class="td2" v-else>
                                            <button type="button" @click="abrirModal('fecha_ava_sol',avaluos)" class="btn btn-default btn-sm" title="Ingresar fecha de solicitud">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </td>


                                        <template v-if="avaluos.fecha_pago">
                                            <td v-if="avaluos.fecha_pago!='0000-01-01'" class="td2" v-text="this.moment(avaluos.fecha_pago).locale('es').format('DD/MMM/YYYY')"></td>
                                            <td v-if="avaluos.fecha_pago=='0000-01-01'" class="td2" v-text="'No aplica'"></td>
                                        </template>
                                        <template v-else>
                                            <td class="td2">
                                                <button type="button" @click="abrirModal('fecha_pago',avaluos)" class="btn btn-default btn-sm" title="Ingresar fecha de pago">
                                                    <i class="fa fa-calendar"></i>
                                                </button>
                                                <button type="button" @click="noAplicaPago(avaluos)" class="btn btn-danger btn-sm" title="No aplica">
                                                    <i class="fa fa-times-circle"></i>
                                                </button>
                                            </td>
                                        </template> 

                                        <td class="td2" @click="abrirModal('visita_avaluo',avaluos)" v-text="this.moment(avaluos.visita_avaluo).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td class="td2" @click="abrirModal('status',avaluos)" v-text="avaluos.status"></td>
                                        <td class="td2" v-if="avaluos.fecha_concluido"
                                            v-text="this.moment(avaluos.fecha_concluido).locale('es').format('DD/MMM/YYYY')">
                                        </td>
                                        <td class="td2" v-else>
                                            <button type="button" @click="abrirModal('fecha_concluido',avaluos)" class="btn btn-default btn-sm" title="Ingresar fecha concluido">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </td>

                                        <td class="td2" v-if="avaluos.tipo_credito == 'Alia2' || avaluos.tipo_credito == 'Fovissste' " v-text="'Si'"></td>
                                        <td class="td2" v-else>No</td>


                                        <td class="td2" v-text="'$'+formatNumber(avaluos.resultado)"></td>
                                        <td class="td2" v-text="'$'+formatNumber(avaluos.costo)"></td>
                                        <td class="td2" v-if="avaluos.fecha_recibido"
                                            v-text="this.moment(avaluos.fecha_recibido).locale('es').format('DD/MMM/YYYY')">
                                        </td>
                                        <td class="td2" v-else>
                                            <button type="button" @click="enviarVentas(avaluos)" class="btn btn-primary btn-sm" title="Enviar a ventas">
                                                Enviar a ventas
                                            </button>
                                        </td>
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
         

            <!--Inicio del modal avaluo-->
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
                            <!-- form para solicitud de avaluo -->
                            <form v-if="tipoAccion == 1" action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha</label>
                                    <div class="col-md-4">
                                        <input type="date" v-model="fecha_ava_sol" class="form-control">
                                    </div>
                                </div>
                            </form>
                            <!-- fin del form solicitud de avaluo -->

                            <!-- form para solicitud de avaluo -->
                            <form v-if="tipoAccion == 2" action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha</label>
                                    <div class="col-md-4">
                                        <input type="date" v-model="fecha_pago" class="form-control">
                                    </div>
                                </div>
                            </form>
                            <!-- fin del form solicitud de avaluo -->

                            <!-- form para solicitud de avaluo -->
                            <form v-if="tipoAccion == 3" action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha</label>
                                    <div class="col-md-4">
                                        <input type="date" v-model="fecha_concluido" class="form-control">
                                    </div>
                                </div>

                                 <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Valor</label>
                                    <div class="col-md-4">
                                        <input type="text" maxlength="10" v-model="resultado" pattern="\d*" v-on:keypress="isNumber($event)" class="form-control" placeholder="Monto">
                                    </div>
                                    <div class="col-md-3">
                                        <h6 v-text="'$'+formatNumber(resultado)"></h6>
                                    </div>
                                </div>
                            </form>
                            <!-- fin del form solicitud de avaluo -->


                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button v-if="tipoAccion==1" type="button" class="btn btn-primary" @click="asignarFecha()">Actualizar</button>
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
                avaluoId:0,
                visita_avaluo:'',
                

                arrayAvaluos : [],
                arrayFraccionamientos:[],
                arrayEtapas:[],

                arrayStatus:[],
                arrayVisitas:[],

                fecha_ava_sol:'',
                fecha_pago:'',
                fecha_concluido:'',
                costo:0,
                observación:'',
                status:'',
                resultado:0,



                modal : 0,
                modal2: 0,
                modal3: 0,

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
            listarAvaluos(page, buscar, b_etapa, b_manzana, b_lote, criterio){
                let me = this;
                var url = '/avaluos/index?page=' + page + '&buscar=' + buscar + '&b_etapa=' + b_etapa + '&b_manzana=' + b_manzana + '&b_lote=' + b_lote +  '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayAvaluos = respuesta.avaluos.data;
                    me.pagination = respuesta.pagination;
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


            cambiarPagina(page,buscar,b_etapa,b_manzana,b_lote,criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarAvaluos(page,buscar,b_etapa,b_manzana,b_lote,criterio);
            },
       
            asignarFecha(){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/licencias/progFechaVisita',{
                    'id':this.id,
                    'visita_avaluo' : this.visita_avaluo,
                    
                }).then(function (response){
                    me.cerrarModal();
                    me.listarAvaluos(1,me.buscar,me.b_etapa,me.b_manzana,me.b_lote,me.criterio);
                    //window.alert("Cambios guardados correctamente");
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'fecha agregada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
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

            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';   
            },
        
         

      
            abrirModal(accion,data =[]){
                switch(accion){
                    
                    case 'fecha_ava_sol':
                    {
                        this.modal =1;
                        this.tituloModal='Ingresar fecha de solicitud';
                        this.tipoAccion = 1;
                        this.fecha_ava_sol = data['fecha_ava_sol'];
                        this.avaluoId = data['avaluoId'];
                        this.id = data['id'];
                        break;
                    }  
                    
                    case 'fecha_pago':
                    {
                        this.modal =1;
                        this.tituloModal='Ingresar fecha de pago';
                        this.tipoAccion = 2;
                        this.fecha_pago = data['fecha_pago'];
                        this.avaluoId = data['avaluoId'];
                        this.id = data['id'];
                        break;
                    } 

                    case 'fecha_concluido':
                    {
                        this.modal =1;
                        this.tituloModal='Avaluo concluido';
                        this.tipoAccion = 3;
                        this.fecha_concluido = data['fecha_concluido'];
                        this.resultado = data['resultado'];
                        this.avaluoId = data['avaluoId'];
                        this.id = data['id'];
                        break;
                    } 

                    case 'status':
                    {
                        this.modal =1;
                        this.tituloModal='Asignar un gestor';
                        this.tipoAccion = 1;
                        this.visita_avaluo = data['visita_avaluo'];
                        this.avaluoId = data['avaluoId'];
                        this.id = data['id'];
                        break;
                    } 

                    case 'visita_avaluo':
                    {
                        this.modal =1;
                        this.tituloModal='Asignar un gestor';
                        this.tipoAccion = 1;
                        this.visita_avaluo = data['visita_avaluo'];
                        this.avaluoId = data['avaluoId'];
                        this.id = data['id'];
                        break;
                    } 
                } 
            }
            
        
        },
       
        mounted() {
            this.listarAvaluos(1,this.buscar,this.b_etapa,this.b_manzana,this.b_lote,this.criterio);
            this.selectFraccionamientos();
            
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
