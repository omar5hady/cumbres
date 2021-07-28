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
                        <i class="fa fa-align-justify"></i> Inicio de Obra
                        <!--   Boton Nuevo    -->
                        <button  v-if="historial == 0" type="button" @click="abrirModal('lotes','enviar')" class="btn btn-success">
                            <i class="icon-envelope-letter"></i>&nbsp;Enviar inicio de obra
                        </button>
                        &nbsp;
                        <button v-if="historial == 0" type="button" @click="historial=1" class="btn btn-primary">
                            <i class="fa fa-calendar-check-o"></i>&nbsp;Historial
                        </button>
                        <button v-if="historial == 1" type="button" @click="historial=0" class="btn btn-default">
                            <i class="fa fa-mail-reply"></i>&nbsp;Regresar
                        </button>
                        <button  v-if="historial == 1" type="button" @click="abrirModal('lotes','editar')" class="btn btn-dark">
                            <i class="icon-pencil"></i>&nbsp;Editar Numero de inicio
                        </button>
                        <!---->
                    </div>
                    <div class="card-body" v-if="historial == 0">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <select class="form-control" v-model="b_empresa" >
                                        <option value="">Empresa constructora</option>
                                        <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                    </select>
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar" @change="selectEtapa(buscar)" >
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>
                                    <input type="text" v-if="buscar!=''" v-model="buscar3" @keyup.enter="listarLotesIniObra(1,buscar,buscar2,buscar3,criterio)" class="form-control" placeholder="Manzana a buscar">
                                </div>
                                <div class="input-group">
                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar2" @keyup.enter="listarLotesIniObra(1,buscar,buscar2,buscar3,criterio)"> 
                                        <option value="">Etapa</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                    </select>
                                    <button type="submit" @click="listarLotesIniObra(1,buscar,buscar2,buscar3,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" @click="selectAll" v-model="allSelected">Todos
                                        </th>
                                        <th>Fraccionamiento</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th># Lote</th>
                                        <th>Modelo</th>
                                        <th>Terreno mts&sup2;</th>
                                        <th>Construcción mts&sup2;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="lote in arrayLotes" :key="lote.id">
                                        <td style="width:8%; ">
                                            <input type="checkbox" @click="select" :id="lote.id" :value="lote.id" v-model="lotes_ini">
                                        </td>
                                        
                                        <td v-text="lote.proyecto"></td>
                                        <td v-text="lote.etapas"></td>
                                        <td v-text="lote.manzana"></td>
                                        <td v-text="lote.num_lote"></td>
                                        <td v-text="lote.modelo"></td>
                                        <td v-text="lote.terreno"></td>
                                        <td v-text="lote.construccion"></td>
                                    </tr>                               
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,buscar2,buscar3,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,buscar2,buscar3,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,buscar2,buscar3,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                        <button class="btn btn-sm btn-default" data-toggle="modal" data-target="#manualId">Manual</button>
                    </div>



                    <div class="card-body" v-if="historial == 1">
                        <div class="form-group row">
                            <div class="col-md-7">
                                <div class="input-group">
                                    <select class="form-control" v-model="b_empresa" >
                                        <option value="">Empresa constructora</option>
                                        <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                    </select>
                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar" @click="selectEtapa(buscar)" >
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>
                                    <input type="text" v-if="buscar!=''" v-model="buscar3" @keyup.enter="listarHistorial(1,buscar,buscar2,buscar3,criterio)" class="form-control" placeholder="Manzana a buscar">
                                </div>
                                <div class="input-group"  v-if="criterio=='lotes.fraccionamiento_id'" >
                                    <select class="form-control col-md-6" v-model="buscar2" @keyup.enter="listarHistorial(1,buscar,buscar2,buscar3,criterio)"> 
                                        <option value="">Etapa</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                    </select>
                                </div>
                                <div class="input-group">
                                    <input type="date" v-model="b_fecha" @keyup.enter="listarHistorial(1,buscar,buscar2,buscar3,criterio)" class="form-control">
                                    <input type="date" v-model="b_fecha2" @keyup.enter="listarHistorial(1,buscar,buscar2,buscar3,criterio)" class="form-control">
                                </div>
                                <div class="input-group">
                                    <button type="submit" @click="listarHistorial(1,buscar,buscar2,buscar3,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" @click="selectAll2" v-model="allSelected2"> Todos
                                        </th>
                                        <th>Fraccionamiento</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th># Lote</th>
                                        <th>Modelo</th>
                                        <th>Terreno mts&sup2;</th>
                                        <th>Construcción mts&sup2;</th>
                                        <th>Fecha de solicitud</th>
                                        <th># Inicio</th>
                                        <th>Fecha de termino</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="lote in arrayHistorial" :key="lote.id">
                                        <td style="width:8%; ">
                                            <input type="checkbox" @click="select2" :id="lote.id" :value="lote.id" v-model="lotes_ini2">
                                        </td>
                                        <td v-text="lote.proyecto"></td>
                                        <td v-text="lote.etapas"></td>
                                        <td v-text="lote.manzana"></td>
                                        <td v-text="lote.num_lote"></td>
                                        <td v-text="lote.modelo"></td>
                                        <td v-text="lote.terreno"></td>
                                        <td v-text="lote.construccion"></td>
                                        <td v-text="lote.ehl_solicitado"></td>
                                        <td v-text="lote.num_inicio"></td>
                                        <td v-text="lote.fecha_termino_ventas"></td>
                                    </tr>                               
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination2.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page - 1,buscar,buscar2,buscar3,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber2" :key="page" :class="[page == isActived2 ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(page,buscar,buscar2,buscar3,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination2.current_page < pagination2.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page + 1,buscar,buscar2,buscar3,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                        <button class="btn btn-sm btn-default" data-toggle="modal" data-target="#manualId">Manual</button>
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
                                    <label class="col-md-3 form-control-label" for="text-input">Numero de inicio</label>
                                    <div class="col-md-3">
                                        <input type="number" v-model="num_inicio" class="form-control" placeholder="Numero de inicio">
                                    </div>
                                </div>
                                <div class="form-group row" v-if="tipoAccion == 1">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de inicio</label>
                                    <div class="col-md-6">
                                        <input type="date" v-model="f_ini" class="form-control" placeholder="Fecha de inicio">
                                    </div>
                                </div>
                                <div class="form-group row" v-if="tipoAccion == 1">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de terminacion</label>
                                    <div class="col-md-6">
                                        <input type="date" v-model="f_fin" class="form-control" placeholder="Fecha de terminacion">
                                    </div>
                                </div>
                                <div class="form-group row" v-if="tipoAccion == 1">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de terminacion ventas</label>
                                    <div class="col-md-6">
                                        <input type="date" v-model="fecha_termino_ventas" class="form-control" placeholder="Fecha de terminacion ventas">
                                    </div>
                                </div>
                                <div class="form-group row" v-if="tipoAccion == 1">
                                    <label class="col-md-3 form-control-label" for="text-input">Arquitectos</label>
                                    <div class="col-md-6">
                                        <select class="form-control" v-model="arquitecto_id">
                                            <option value="0">Seleccione</option>
                                            <option v-for="arquitectos in arrayArquitectos" :key="arquitectos.id" :value="arquitectos.id" v-text="'Arq. ' + arquitectos.name"></option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Div para mostrar los errores que mande validerDepartamento -->
                                <div v-show="errorLotesIniObra" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjLotesIniObra" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarInicioObra()">Enviar</button>
                            <button type="button" v-if="tipoAccion==2 && num_inicio != ''" class="btn btn-primary" @click="actualizarInicio()">Actualizar</button>
                            
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
            
            <!-- Manual -->
            <div class="modal fade" id="manualId" tabindex="-1" role="dialog" aria-labelledby="manualIdTitle" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="manualIdTitle">Manual</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>
                            Para agregar un inicio de obra debe seleccionar aquellos lotes que desee enviar a inicio de obra 
                            dando clic sobre la casilla de selección múltiple, al finalizar la selección de clic sobre el botón de 
                            “Enviar a inicio de obra”.
                        </p>
                        <p>
                            <strong>Para que un lote pueda llegar al módulo de inicio de obra</strong> se le debe de asignar etapa y modelo, 
                            asi como habilitar el lote.
                        </p>
                        <p>
                            Una vez asignado el aviso de obra el lote se envía (o se habilita) al módulo de aviso de obra 
                            donde podrá ser agregado a un inicio de obra.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    </div>
                </div>
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
                proceso:false,
                allSelected: false,
                allSelected2: false,
                id:0,
                f_ini : new Date().toISOString().substr(0, 10),
                f_fin : '',
                fecha_termino_ventas: '',
                arrayLotes : [],
                arrayHistorial : [],
                lotes_ini : [],
                lotes_ini2 : [],
                arrayEtapas: [],
                arrayFraccionamientos:[],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                historial:0,
                errorLotesIniObra : 0,
                errorMostrarMsjLotesIniObra : [],
                arrayArquitectos : [],
                arquitecto_id : 0,
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
                criterio : 'lotes.fraccionamiento_id', 
                buscar : '',
                buscar2:'',
                buscar3:'',
                b_fecha:'',
                b_fecha2:'',
                num_inicio:'',
                b_empresa:'',
                empresas:[],
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

            isActived2: function(){
                return this.pagination2.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber2:function(){
                if(!this.pagination2.to){
                    return [];
                }

                var from = this.pagination2.current_page - this.offset;
                if(from < 1){
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.pagination2.last_page){
                    to = this.pagination2.last_page;
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
            
            selectAll: function() {
                this.lotes_ini = [];

                if (!this.allSelected) {
                    for (var lote in this.arrayLotes) {
                        this.lotes_ini.push(this.arrayLotes[lote].id.toString());
                    }
                }
            },
            select: function() {
                this.allSelected = false;
            },
            selectAll2: function() {
                this.lotes_ini2 = [];

                if (!this.allSelected2) {
                    for (var lote in this.arrayHistorial) {
                        this.lotes_ini2.push(this.arrayHistorial[lote].id.toString());
                    }
                }
            },
            select2: function() {
                this.allSelected2 = false;
            },
            selectArquitectos(){
                let me = this;
                me.arrayArquitectos=[];
                var url = '/select_personal?departamento_id=3';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayArquitectos = respuesta.personal;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectFraccionamientos(){
                let me = this;
                if(me.modal == 0){
                me.buscar=""
                me.buscar2=""
                me.buscar3=""
                }
                
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
                if(me.modal == 0){
                
                me.buscar2=""
                me.buscar3=""
                }
                
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

            /**Metodo para mostrar los registros */
            listarLotesIniObra(page, buscar,buscar2,buscar3, criterio){
                let me = this;
                var url = '/lote_aviso?page=' + page + '&buscar=' + buscar + '&buscar2=' + buscar2 + '&buscar3=' + buscar3 + 
                '&criterio=' + criterio+'&b_empresa='+this.b_empresa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLotes = respuesta.lotes.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            /**Metodo para mostrar los registros */
            listarHistorial(page, buscar,buscar2,buscar3, criterio){
                let me = this;
                var url = '/lote_aviso/historial?page=' + page + '&buscar=' + buscar + '&buscar2=' + buscar2 + '&buscar3=' + buscar3 + 
                '&fecha=' + me.b_fecha + '&fecha2=' + me.b_fecha2 + '&criterio=' + criterio+'&b_empresa='+this.b_empresa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayHistorial = respuesta.lotes.data;
                    me.pagination2 = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },


            cambiarPagina(page, buscar, buscar2, buscar3, criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarLotesIniObra(page,buscar,buscar2,buscar3,criterio);
            },
            cambiarPagina2(page, buscar, buscar2, buscar3, criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarHistorial(page,buscar,buscar2,buscar3,criterio);
            },
            /**Metodo para registrar  */
            registrarInicioObra(){
                if(this.proceso==true){
                    return;
                }
                if(this.validarInicioObra()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }
                this.proceso=true;

                let me = this;
                //Con axios se llama el metodo update de DepartamentoController

                var tamaño=me.lotes_ini.length;
                var i=1;
                var aviso =0;
                me.lotes_ini.forEach(element => {

                    if(i == tamaño)
                        aviso = tamaño;
                    axios.put('/lotes/enviarAviObra',{
                    'arquitecto_id': this.arquitecto_id,
                    'id': element,
                    'fecha_ini' : this.f_ini,
                    'fecha_fin' : this.f_fin,
                    'fecha_termino_ventas' : this.fecha_termino_ventas,
                    'aviso' : aviso,
                    'num_inicio' : this.num_inicio
                    }); 
                    i++;
                });
                Swal({
                title: 'Enviado!',
                text: 'Aviso enviado correctamente.',
                imageUrl: 'https://d2r6jp7chi630e.cloudfront.net/blog/aritic-pinpoint/wp-content/uploads/sites/3/2016/09/email-gif.gif',
                imageWidth: 800,
                imageHeight: 400,
                imageAlt: 'Custom image',
                animation: true
                })
                me.proceso=false;
                me.cerrarModal();
                me.listarLotesIniObra(1,'','fraccionamientos.nombre');
                
            },

            actualizarInicio(){
                if(this.proceso==true){
                    return;
                }
                this.proceso=true;
                
                let me = this;
                //Con axios se llama el metodo update de DepartamentoController
                me.lotes_ini2.forEach(element => {
                    axios.put('/lotes/enviarAviObra/actualizar',{
                    'id': element,
                    'num_inicio' : this.num_inicio
                    }); 
                });
                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                    });

                    toast({
                    type: 'success',
                    title: 'Numeros de inicios actualizados correctamente'
                    })
                me.proceso=false;
                me.cerrarModal();
                me.listarHistorial(1,me.buscar,me.buscar2,me.buscar3,me.criterio);
                
            },
            
            validarInicioObra(){
                this.errorLotesIniObra=0;
                this.errorMostrarMsjLotesIniObra=[];

                if(!this.f_ini) //Si la variable departamento esta vacia
                    this.errorMostrarMsjLotesIniObra.push("Seleccionar la fecha de inicio.");
                
                if(!this.f_fin) //Si la variable departamento esta vacia
                    this.errorMostrarMsjLotesIniObra.push("Seleccionar la fecha de termino.");
                
                if(this.f_fin<this.f_ini) //Si la variable departamento esta vacia
                    this.errorMostrarMsjLotesIniObra.push("La fecha de termino debe ser mayor a la fecha de inicio.");

                if(this.arquitecto_id==0) //Si la variable departamento esta vacia
                    this.errorMostrarMsjLotesIniObra.push("Seleccionar al Arquitecto en cargo.");

                if(this.errorMostrarMsjLotesIniObra.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorLotesIniObra = 1;

                return this.errorLotesIniObra;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.f_fin = '';
                this.f_ini = '';
                this.errorLotesIniObra = 0;
                this.errorMostrarMsjLotesIniObra = [];
                this.lotes_ini = [];
                this.lotes_ini2 = [];
                this.allSelected2 = false;
                this.allSelected = false;

            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(modelo, accion,data =[]){
                if(this.lotes_ini.length<1 && accion == 'enviar'){
                    Swal({
                    title: 'No se ha seleccionado ningun lote',
                    animation: false,
                    customClass: 'animated tada'
                    })
                    return;
                }

                if(this.lotes_ini2.length<1 && accion == 'editar'){
                    Swal({
                    title: 'No se ha seleccionado ningun lote',
                    animation: false,
                    customClass: 'animated tada'
                    })
                    return;
                }
               
                switch(modelo){
                    case "lotes":
                    {
                        switch(accion){
                            case 'enviar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Enviar aviso de obra';
                                //this.f_ini = '';
                                this.f_fin ='';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'editar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Editar numero de inicio de obra';
                                //this.f_ini = '';
                                this.num_inicio = '';
                                this.tipoAccion = 2;
                                break;
                            }
                        }
                    }
                }
                this.selectArquitectos();
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
        },
        mounted() {
            this.listarLotesIniObra(1,this.buscar,this.buscar2,this.buscar3,this.criterio);
            this.listarHistorial(1,this.buscar,this.buscar2,this.buscar3,this.criterio);
            this.selectFraccionamientos();
            this.getEmpresa();
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
        display:flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>
