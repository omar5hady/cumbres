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
                        <i class="fa fa-align-justify"></i> Contratos &nbsp;
                        <button v-if="historial == 0" type="button" class="btn btn-success" @click="historial=1">
                            <i class="fa fa-history"></i> &nbsp;Historial
                        </button>
                        <button v-if="historial == 1" type="button" class="btn btn-dark" @click="historial=0">
                            <i class="fa fa-mail-reply"></i> &nbsp;Regresar
                        </button>
                    </div>

            <!----------------- Listado Contratos ------------------------------>
                    <!-- Div Card Body para listar -->
                        <div class="card-body" v-if="historial == 0"> 
                            <div class="form-group row">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <select class="form-control"  @click="selectEtapas(buscar2)" @keyup.enter="listarContratos(1)" v-model="buscar2" >
                                            <option value="">Proyecto</option>
                                            <option v-for="proyecto in arrayFraccionamientos" :key="proyecto.id" :value="proyecto.id" v-text="proyecto.nombre"></option>
                                        </select>
                                         <select class="form-control" @click="selectManzanas(buscar2,b_etapa2)" @keyup.enter="listarContratos(1)" v-model="b_etapa2" >
                                            <option value="">Etapa</option>
                                            <option v-for="etapa in arrayAllEtapas" :key="etapa.id" :value="etapa.id" v-text="etapa.num_etapa"></option>
                                        </select>

                                        
                                    </div>
                                   
                                </div>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <select class="form-control"  @keyup.enter="listarContratos(1)" v-model="b_manzana2" >
                                            <option value="">Manzana</option>
                                            <option v-for="manzana in arrayAllManzanas" :key="manzana.manzana" :value="manzana.manzana" v-text="manzana.manzana"></option>
                                        </select>
                                        <input type="text" v-model="b_lote2" @keyup.enter="listarContratos(1)" class="form-control" placeholder="Lote">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <!--Criterios para el listado de busqueda -->
                                        <label class="form-control col-md-4" disabled>
                                            Asesor:
                                        </label>
                                        <select class="form-control"  v-model="asesor_id" >
                                            <option value="">Seleccione</option>
                                            <option v-for="asesor in arrayAsesores" :key="asesor.id" :value="asesor.id" v-text="asesor.nombre + ' '+ asesor.apellidos"></option>
                                        </select>
                                    </div>
                                    <div class="input-group">
                                        <button type="submit" @click="listarContratos(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
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
                                            <th>Fecha del contrato</th>
                                            <th>Avance</th>
                                            <th>Expediente</th>
                                            <th>Observación</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="contrato in arrayContratos" :key="contrato.id">
                                            <td class="td2" v-text="contrato.id"></td>
                                            <td class="td2" v-text="contrato.nombre_cliente"></td>
                                            <td class="td2" v-text="contrato.nom_vendedor "></td>
                                            <td class="td2" v-text="contrato.proyecto"></td>
                                            <td class="td2" v-text="contrato.etapa"></td>
                                            <td class="td2" v-text="contrato.manzana"></td>
                                            <td class="td2" v-text="contrato.num_lote"></td>
                                            <td class="td2" v-text="this.moment(contrato.fecha).locale('es').format('DD/MMM/YYYY')"></td>
                                            <template>
                                                <td class="td2" v-if="contrato.avance_lote<90">
                                                    <span class="badge2 badge-default">Casa en Proceso: {{contrato.avance_lote}}%</span>
                                                </td>
                                                <td class="td2" v-else>
                                                    <span class="badge badge-success">Casa Terminada: {{contrato.avance_lote}}%</span>
                                                </td>
                                            </template>
                                            <td>
                                                <button type="button" @click="abrirModal('ingresar',contrato)" class="btn btn-primary btn-sm">
                                                    <i class="fa fa-folder"></i>
                                                </button>
                                            </td>
                                            <td>
                                                <button type="button" @click="abrirModal('observaciones',contrato)" class="btn btn-danger btn-sm">
                                                    Observaciones
                                                </button>
                                            </td>
                                        </tr>                               
                                    </tbody>
                                </table>
                            </div>
                            <nav>
                                <!--Botones de paginacion -->
                                <ul class="pagination">
                                    <li class="page-item" v-if="pagination2.current_page > 1">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page - 1)">Ant</a>
                                    </li>
                                    <li class="page-item" v-for="page2 in pagesNumber2" :key="page2" :class="[page2 == isActived2 ? 'active' : '']">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina2(page2)" v-text="page2"></a>
                                    </li>
                                    <li class="page-item" v-if="pagination2.current_page < pagination2.last_page">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page + 1)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>

                    <!-- Div Card Body para listar -->
                        <div class="card-body" v-if="historial == 1"> 
                            <div class="form-group row">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <select class="form-control"  @click="selectEtapas(buscar2)" @keyup.enter="listarHistorial(1)" v-model="buscar2" >
                                            <option value="">Proyecto</option>
                                            <option v-for="proyecto in arrayFraccionamientos" :key="proyecto.id" :value="proyecto.id" v-text="proyecto.nombre"></option>
                                        </select>
                                         <select class="form-control" @click="selectManzanas(buscar2,b_etapa2)" @keyup.enter="listarHistorial(1)" v-model="b_etapa2" >
                                            <option value="">Etapa</option>
                                            <option v-for="etapa in arrayAllEtapas" :key="etapa.id" :value="etapa.id" v-text="etapa.num_etapa"></option>
                                        </select>

                                        
                                    </div>
                                   
                                </div>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <select class="form-control"  @keyup.enter="listarHistorial(1)" v-model="b_manzana2" >
                                            <option value="">Manzana</option>
                                            <option v-for="manzana in arrayAllManzanas" :key="manzana.manzana" :value="manzana.manzana" v-text="manzana.manzana"></option>
                                        </select>
                                        <input type="text" v-model="b_lote2" @keyup.enter="listarHistorial(1)" class="form-control" placeholder="Lote">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <!--Criterios para el listado de busqueda -->
                                        <label class="form-control col-md-4" disabled>
                                            Asesor:
                                        </label>
                                        <select class="form-control"  v-model="asesor_id" >
                                            <option value="">Seleccione</option>
                                            <option v-for="asesor in arrayAsesores" :key="asesor.id" :value="asesor.id" v-text="asesor.nombre + ' '+ asesor.apellidos"></option>
                                        </select>
                                    </div>
                                    <div class="input-group">
                                        <button type="submit" @click="listarHistorial(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
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
                                            <th>Fecha del contrato</th>
                                            <th>Avance</th>
                                            <th>Expediente</th>
                                            <th>% Obtenido</th>
                                            <th>Observación</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="contrato in arrayHistorial" :key="contrato.id">
                                            <td class="td2" v-text="contrato.id"></td>
                                            <td class="td2" v-text="contrato.nombre_cliente"></td>
                                            <td class="td2" v-text="contrato.nom_vendedor "></td>
                                            <td class="td2" v-text="contrato.proyecto"></td>
                                            <td class="td2" v-text="contrato.etapa"></td>
                                            <td class="td2" v-text="contrato.manzana"></td>
                                            <td class="td2" v-text="contrato.num_lote"></td>
                                            <td class="td2" v-text="this.moment(contrato.fecha).locale('es').format('DD/MMM/YYYY')"></td>
                                            <template>
                                                <td class="td2" v-if="contrato.avance_lote<90">
                                                    <span class="badge2 badge-default">Casa en Proceso: {{contrato.avance_lote}}%</span>
                                                </td>
                                                <td class="td2" v-else>
                                                    <span class="badge badge-success">Casa Terminada: {{contrato.avance_lote}}%</span>
                                                </td>
                                            </template>
                                            <td class="td2" v-text="this.moment(contrato.fecha_exp).locale('es').format('DD/MMM/YYYY')"></td>
                                            <td class="td2" v-text="contrato.porcentaje_exp+'%'"></td>
                                            <td>
                                                <button type="button" @click="abrirModal('observaciones',contrato)" class="btn btn-danger btn-sm">
                                                    Observaciones
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
                                    <li class="page-item" v-for="page2 in pagesNumber" :key="page2" :class="[page2 == isActived2 ? 'active' : '']">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(page2)" v-text="page2"></a>
                                    </li>
                                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>

            <!-- Inicio Modal Expediente Completo-->
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

                            <div class="form-group row" v-if="tipoAccion==1">
                                <label class="col-md-4 form-control-label" for="text-input">Fecha de entrega (Expediente completo)</label>
                                <div class="col-md-6">
                                    <input type="date" @change="newDiferencia()" v-model="fecha" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row line-separator"></div>


                            <div class="form-group row">
                                <label class="col-md-4 form-control-label" for="text-input">Dias desde fecha de solicitud</label>
                                <div class="col-md-6">
                                    <h6><strong> {{ diferencia}} </strong></h6>
                                </div>
                            </div>

                            <center>
                                <h6>Porcentaje obtenido</h6>
                                <div class="col-md-6">
                                    <h5 style="color: blue;"><strong> {{ porcentaje}}% </strong></h5>
                                </div>
                            </center>

                      </form>

                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" class="btn btn-primary" @click="ingrsarExp()">Ingresar</button>
                         </div>
                    </div> 
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->

            <!--Inicio del modal Observaciones-->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal1}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="'Observaciones'"></h4>
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
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-primary" @click="agregarComentario()">Guardar</button>
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
        props:{
            rolId:{type: String}
        },
        data(){
            return{
                id_contrato:0,
                proceso:false,
                arrayContratos:[],
                arrayDatosSimulacion:[],
                arrayFraccionamientos: [],
                arrayAllEtapas:[],
                arrayAllManzanas:[],
                arrayEtapas:[],
                arrayManzanas:[],
                arrayAsesores:[],  
                arrayHistorial:[],

                historial:0,

                arrayObservacion:[],
                
                //fecha:moment(),
                id:'',
                diferencia:0,
                fecha:'',
                tipo:'', //0 en Proces, 1 Terminada
                porcentaje : 100,
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
                buscar2 : '',
                b_etapa2: '',
                b_manzana2: '',
                b_lote2 : '',
                asesor_id:'',
                modal: 0,
                modal1: 0,
                tituloModal: '',
                tipoAccion: 0,
                comentario : ''
               
            }
        },
        computed:{

            isActived2: function(){
                return this.pagination2.current_page;
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

                var pagesArray2 = [];
                while(from <= to){
                    pagesArray2.push(from);
                    from++;
                }
                return pagesArray2;
            },
            isActived: function(){
                return this.pagination.current_page;
            },

            //Calcula los elementos de la paginación
            pagesNumber:function(){
                if(!this.pagination.to){
                    return [];
                }

                var from = this.pagination.current_page - this.offset2;
                if(from < 1){
                    from = 1;
                }

                var to = from + (this.offset2 * 2);
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }

                var pagesArray2 = [];
                while(from <= to){
                    pagesArray2.push(from);
                    from++;
                }
                return pagesArray2;
            },
           
        },
       
        methods : {
            listarContratos(page){
                let me = this;
                var url = '/comision/listarContratos?page=' + page + '&buscar=' + me.buscar2 + 
                            '&etapa='+ me.b_etapa2 + '&manzana=' + me.b_manzana2 + '&lote=' + me.b_lote2 + '&vendedor=' + me.asesor_id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayContratos = respuesta.contratos.data;
                    me.pagination2 = respuesta.pagination;
                    me.fecha = respuesta.hoy;
                })
                .catch(function (error) {
                    console.log(error);
                })
            },

            listarHistorial(page){
                let me = this;
                var url = '/comision/historialExpedientes?page=' + page + '&buscar=' + me.buscar2 + 
                            '&etapa='+ me.b_etapa2 + '&manzana=' + me.b_manzana2 + '&lote=' + me.b_lote2 + '&vendedor=' + me.asesor_id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayHistorial = respuesta.contratos.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                })
            },

            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
            cambiarPagina2(page){
                let me = this;
                //Actualiza la pagina actual
                me.pagination2.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarContratos(page);
            },
            cambiarPagina(page){
                let me = this;
                //Actualiza la pagina actual
                me.pagination2.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarHistorial(page);
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
            selectEtapas(buscar){
                let me = this;
                me.b_etapa2="";
                me.b_manzana2="";
                me.b_lote2="";
                
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

            actualizarDiferencia(){
                var a = this.fecha;
                var b = this.fechaContrato;
                this.diferencia = a.diff(b,'days');
            },

            newDiferencia(){
                var from = moment(this.fechaContrato),
                    to = moment(this.fecha),
                    days = 0;
                    
                while (!from.isAfter(to,'day')) {
                    // Si no es sabado ni domingo
                    if (from.isoWeekday() !== 6 && from.isoWeekday() !== 7) {
                    days++;
                    }
                    from.add(1, 'days');
                }
                this.diferencia =  days;

                if(this.tipo == 0){
                    if(this.diferencia > 15){
                        this.porcentaje = 65;
                    }
                    else{
                        this.porcentaje = 100;
                    }
                }
                else{
                    if(this.diferencia > 7){
                        this.porcentaje = 65;
                    }
                    else{
                        this.porcentaje = 100;
                    }
                }
            },

            listarObservacion(buscar){
                let me = this;
                var url = '/comision/indexObservacion?id=' + buscar;
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
                axios.post('/comision/registrarObservacion',{
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

            cerrarModal(){
                this.modal = 0;
                this.modal1 = 0;
                this.comentario = '';
                this.tituloModal = '';
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

            ingrsarExp(){
                if(this.proceso==true){
                    return;
                }

                this.proceso=true;
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.put('/comision/agregarExpediente',{
                    'id': this.id,
                    'porcentaje':this.porcentaje,
                    'fecha':this.fecha,
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarContratos(1);
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Notaria agregada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },

            limpiarBusqueda(){
                let me=this;
               
                me.buscar2= "";
                me.b_etapa2='';
                me.b_manzana2='';
                me.b_lote2='';
                
            },
            abrirModal(accion,data =[]){
                switch(accion){
                    case 'ingresar':
                    {
                        this.modal = 1;
                        this.tituloModal='Expediente completo';
                        this.id=data['id'];
                        this.tipoAccion = 1;
                        this.fechaContrato = data['fecha'];
                        this.diferencia=1;
                        var from = moment(this.fechaContrato),
                            to = moment(this.fecha),
                            days = 0;
                            
                        while (!from.isAfter(to,'day')) {
                            // Si no es sabado ni domingo
                            if (from.isoWeekday() !== 6 && from.isoWeekday() !== 7) {
                            days++;
                            }
                            from.add(1, 'days');
                        }
                        this.diferencia =  days;
                        if(data['avance_lote']<90){
                            this.tipo = 0;
                            if(this.diferencia > 15){
                                this.porcentaje = 65;
                            }
                        }
                        else{
                            this.tipo = 1;
                            if(this.diferencia > 7){
                                this.porcentaje = 65;
                            }
                        }

                        break;
                    }
                    case 'observaciones':{
                        this.modal1 = 1;
                        this.id = data['id'];
                        this.comentario = '';
                        this.listarObservacion(this.id);
                        break;
                    }
                }
            }
        
        },
        mounted() {          
            this.listarContratos(1);
            this.selectFraccionamientos();
            this.selectAsesores();
            this.listarHistorial(1);
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
    .badge2 {
        display: inline-block;
        padding: 0.25em 0.4em;
        font-size: 75%;
        font-weight: bold;
        line-height: 1;
        color: #151b1f;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
    }
    }
</style>
