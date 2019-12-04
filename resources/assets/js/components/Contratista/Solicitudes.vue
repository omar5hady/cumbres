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
                       Solicitudes de detalles <span style="color:black; font-weight: bold;" v-text="contratista"></span>
                       <button class="btn btn-default" v-if="listado == 2" @click="listado=1">
                           Regresar
                       </button>
                    </div>

                <!-------------------  Div solicitudes  --------------------->
                    <div v-if="listado == 1" class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-5" v-model="criterio" @click="selectFraccionamientos()">
                                        <option value="lotes.fraccionamiento_id">Proyecto</option>
                                        <option value="solic_detalles.cliente">Cliente</option>
                                        <option value="solic_detalles.id"># Folio</option>
                                    </select>

                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar" @click="selectEtapa(buscar)">
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamiento in arrayFraccionamientos2" :key="fraccionamiento.nombre" :value="fraccionamiento.id" v-text="fraccionamiento.nombre"></option>
                                    </select>

                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" @click="selectManzanas(buscar,b_etapa)" v-model="b_etapa"> 
                                        <option value="">Etapa</option>
                                        <option v-for="etapa in arrayEtapas2" :key="etapa.num_etapa" :value="etapa.id" v-text="etapa.num_etapa"></option>
                                    </select>

                                    <input v-else type="text"  v-model="buscar" @keyup.enter="listarContratos(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="form-control" placeholder="Texto a buscar">
                                   
                                </div>
                            </div>
                            <div class="col-md-6" v-if="criterio=='lotes.fraccionamiento_id'">
                                <div class="input-group">
                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" @click="selectLotesManzana(buscar,b_etapa,b_manzana)" @keyup.enter="listarContratos(1,buscar,b_etapa,b_manzana,b_lote,criterio)" v-model="b_manzana" >
                                        <option value="">Manzana</option>
                                        <option v-for="manzana in arrayAllManzanas" :key="manzana.manzana" :value="manzana.manzana" v-text="manzana.manzana"></option>
                                    </select>

                                    <input v-if="criterio=='lotes.fraccionamiento_id'" type="text"  v-model="b_lote" @keyup.enter="listarContratos(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="form-control" placeholder="Lote a buscar">

                                </div>
                            </div>
                            <div class="col-md-8">
                                <button type="submit" @click="listarContratos(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
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
                                        <th>Modelo</th>
                                        <th>Disponibilidad cliente</th>
                                        <th>Fecha solicitud</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="contratos in arrayContratos" :key="contratos.solicitudID" @dblclick="listado=2,listarDetalles(contratos.solicitudID)">
                                       <td v-text="contratos.solicitudID"></td>
                                       <td v-text="contratos.cliente"></td>
                                       <td v-text="contratos.proyecto"></td>
                                       <td v-text="contratos.etapa"></td>
                                       <td v-text="contratos.manzana"></td>   
                                       <td v-text="contratos.num_lote"></td>   
                                       <td v-text="contratos.modelo"></td>
                                       <td>
                                           <p>
                                           <span style="color:red;" v-if="contratos.lunes == 1">Lunes</span>
                                           <span style="color:yellow;" v-if="contratos.martes == 1">Martes</span>
                                           <span style="color:blue;" v-if="contratos.miercoles == 1">Miercoles</span>
                                           <span style="color:green;" v-if="contratos.jueves == 1">Jueves</span>
                                           <span style="color:purple;" v-if="contratos.viernes == 1">Viernes</span>
                                           <span style="color:pink;" v-if="contratos.sabado == 1">Sabado</span>
                                           </p>
                                       </td>                                     
                                        <td v-text="this.moment(contratos.created_at).locale('es').format('DD/MMM/YYYY')"></td>
                                    </tr>
                                </tbody>
                            </table>  
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page > 7">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(1,buscar,b_etapa,b_manzana,b_lote,criterio)">Inicio</a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination2.current_page - 1,buscar,b_etapa,b_manzana,b_lote,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,b_etapa,b_manzana,b_lote,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,b_etapa,b_manzana,b_lote,criterio)">Sig</a>
                                </li>
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page<pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.last_page,buscar,b_etapa,b_manzana,b_lote,criterio)">Ultimo</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                <!-------------------  Fin Div solicitudes --------------------->

                
                <!-------------------  Div detalles  --------------------->
                    <div v-if="listado==2" class="card-body">
                        <div style="text-align:center;">
                             Detalles de &nbsp;<span style="color:#00B0BB; font-weight: bold;" v-text="proyecto"></span>&nbsp; Etapa: &nbsp;<span style="color:#00B0BB; font-weight: bold;" v-text="etapa"></span>&nbsp; Manzana: &nbsp; <span style="color:#00B0BB; font-weight: bold;" v-text="manzana"></span> &nbsp; Lote: &nbsp; <span style="color:#00B0BB; font-weight: bold;" v-text="lote"></span>
                        </div>
                            
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr> 
                                        <th># Ref</th>
                                        <th>Cliente</th>
                                        <th>General</th>
                                        <th>Subconcepto</th>
                                        <th>Detalle</th>
                                        <th>Garantia</th>
                                        <th>Costo</th>
                                     
                                       
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="detalles in arrayDetalles" :key="detalles.solicitud_id">
                                       <td v-text="detalles.solicitud_id"></td>
                                       <td v-text="detalles.cliente"></td>
                                       <td v-text="detalles.general"></td>
                                       <td v-text="detalles.subconcepto"></td>
                                       <td v-text="detalles.detalle"></td> 
                                       <template> 
                                            <td v-if="detalles.garantia == 1"><span class="badge badge-success">Si</span> </td>
                                            <td v-else> <span  class="badge badge-danger">No</span> </td> 
                                       </template>

                                        <template> 
                                            <td v-if="detalles.garantia == 1"><span class="badge badge-success">Con garantia</span></td>  
                                            <td v-else>
                                             <input  type="text" pattern="\d*" @keyup.enter="actualizarCosto($event.target.value,detalles.id,detalles.solicitud_id)" :id="detalles.solicitud_id" :value="detalles.costo" v-on:keypress="isNumber($event)" class="form-control" >
                                            </td> 
                                        </template>                               

                                    </tr>
                                </tbody>
                            </table>  
                        </div>
                    </div>
                <!-------------------  Fin Div detalles --------------------->

                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
         

          
            
         
     </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    export default {
        data(){
            return{

                arrayFraccionamientos2:[],
                arrayEtapas2:[],
                arrayAllManzanas:[],
                arrayContratos:[],
                arrayDetalles:[],


                // Variables para buscar lotes entregados
                arrayLotes:[],
                arrayEtapas:[],
                arrayManzanas:[],
                
                proyecto:'',
                etapa:'',
                manzana:'',
                lote:'',

                listado: 1,
                
                modal: 0,
                modal3: 0,
                tituloModal: '',


                contratista : '',

                arrayListadoDetalles : [],

                folio:0,
                offset : 3,

                // Criterios para historial de contratos
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                criterio : 'lotes.fraccionamiento_id', 
                buscar: '',
                b_etapa: '',
                b_manzana: '',
                b_lote: '',
                tipoAccion:0,
                observacion:'',

                errorEntrega : 0,
                errorMostrarMsjEntrega : [],
               
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
            listarContratos(page, buscar, b_etapa, b_manzana, b_lote, criterio){
                let me = this;
                var url = '/solicitudes/indexContratista?page=' + page + '&buscar=' + buscar + '&b_etapa=' + b_etapa + '&b_manzana=' + b_manzana + '&b_lote=' + b_lote +  '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayContratos = respuesta.contratos.data;
                    me.pagination = respuesta.pagination;
                    me.contratista = me.arrayContratos[0]['contratista'];
                    
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            listarDetalles(solicitud_ID){
                let me = this;
                var url = '/detalles/indexContratista?solicitud_id=' + solicitud_ID;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayDetalles = respuesta.detalles;
                    me.proyecto = me.arrayDetalles[0]['proyecto'];
                    me.etapa = me.arrayDetalles[0]['etapa'];
                    me.manzana = me.arrayDetalles[0]['manzana'];
                    me.lote = me.arrayDetalles[0]['num_lote'];
                    
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectNombreArchivoModelo(id){
                let me = this;
                me.nombre_archivo_modelo='';
                var url = '/contrato/modelo/caracteristicas/pdf/' + id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                        me.nombre_archivo_modelo = respuesta.modelo[0].archivo;
                        window.open('/files/modelos/'+me.nombre_archivo_modelo, '_blank')
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectContratistas(){
                let me = this;
                me.arrayContratistas = [];
                var url = '/select_contratistas';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayContratistas = respuesta.contratista;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            selectFraccionamientos(){
                let me = this;
                me.buscar=""
                
                me.arrayFraccionamientos=[];
                me.arrayFraccionamientos2=[];
                me.arrayEtapas=[];
                var url = '/select_fraccionamiento';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayFraccionamientos = respuesta.fraccionamientos;
                    me.arrayFraccionamientos2 = respuesta.fraccionamientos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectEtapa(buscar){
                let me = this;
                me.b_etapa=""
                
                me.arrayEtapas=[];
                me.arrayEtapas2=[];
                var url = '/select_etapa_proyecto?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEtapas = respuesta.etapas;
                    me.arrayEtapas2 = respuesta.etapas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            //Select todas las manzanas
            selectManzanas(buscar1, buscar2){
                let me = this;
                me.b_manzana2="";
                me.b_lote2="";

                me.arrayAllManzanas=[];
                var url = '/select_manzanas_etapa?buscar=' + buscar1 + '&buscar1='+ buscar2;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayAllManzanas = respuesta.manzana;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

         
            /**Metodo para registrar costo */
            actualizarCosto(costo,id,solicitud){
                let me = this;
                
                axios.put('/detalles/updateCosto',{
                    'costo':costo,
                    'solicitud_id': solicitud,
                    'id' : id
                }).then(function (response){
                    
                    me.listarDetalles(solicitud);
                  
                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000

                    });

                    toast({
                    type: 'success',
                    title: 'Cambios guardados'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            /// SELECT PARA CATALOGO DE DETALLES
                selectGenerales(fraccionamiento){
                    let me = this;
                    
                    me.arrayGenerales=[];
                    var url = '/catalogoDetalle/selectGeneral';
                    axios.get(url).then(function (response) {
                        var respuesta = response.data;
                        me.arrayGenerales = respuesta.general;
                        
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                },
                selectSubconcepto(id_gral){
                    let me = this;
                    
                    me.arraySubconceptos=[];
                    var url = '/catalogoDetalle/selectSub?id_gral=' + id_gral;
                    axios.get(url).then(function (response) {
                        var respuesta = response.data;
                        me.arraySubconceptos = respuesta.subconcepto;
                        
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                },

                getDatosDetalle(id_detalle){
                let me = this;
                me.arrayDatosDetalle=[];
                var url = '/catalogoDetalle/getDatosDetalle?id_detalle=' + id_detalle;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                        me.arrayDatosDetalle = respuesta.datosDetalle;
                        me.detalle = me.arrayDatosDetalle[0]['detalle'];
                        me.dias_garantia = me.arrayDatosDetalle[0]['dias_garantia'];
                        me.subconcepto = me.arrayDatosDetalle[0]['subconcepto'];
                        me.general = me.arrayDatosDetalle[0]['general'];
                        if(me.dias_garantia >= me.dias_entregado)
                            me.garantia = 1;
                        else{
                            me.garantia = 0;
                        }
                        
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

            isNumber: function(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                    evt.preventDefault();;
                } else {
                    return true;
                }
            },

            storeObservacion(){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.post('/postventa/registrarObservacion',{
                    'comentario' : this.observacion,
                    'entrega_id':this.folio,
                    
                }).then(function (response){
                    me.listarObservacion(1,me.folio);
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Observación guardada correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            listarObservacion(page, buscar){
                let me = this;
                var url = '/postventa/indexObservacion?page=' + page + '&buscar=' + buscar ;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayObservacion = respuesta.observacion.data;
                    console.log(url);
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },

            cerrarModal(){
                this.tituloModal = '';
                this.modal = 0;
            },

            abrirModal(accion,data =[]){
                switch(accion){

                    case 'observaciones':{
                        this.modal3 =1;
                        this.tituloModal='Observaciones';
                        this.folio = data['folio'];
                        this.observacion = '';
                        break;
                    }

                    case 'nuevo':{
                        this.modal = 1;
                        this.tituloModal='Nueva solicitud de detalles';
                        this.folio = '';
                        this.observacion = '';
                        this.lote_id = '';
                        break;
                    }
                }
            }
            
        },
       
        mounted() {
            this.listarContratos(1,this.buscar,this.b_etapa,this.b_manzana,this.b_lote,this.criterio);
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
