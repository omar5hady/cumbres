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
                        <i class="fa fa-align-justify"></i> Solicitud de RUV
                        <!--   Boton Nuevo    -->
                        <button  v-if="historial == 0" type="button" @click="abrirModal('lotes','enviar')" class="btn btn-dark">
                            <i class="icon-envelope-letter"></i> &nbsp;Solicitar
                        </button>
                        &nbsp;
                        <button v-if="historial == 0" type="button" @click="historial=1" class="btn btn-primary">
                            <i class="fa fa-calendar-check-o"></i>&nbsp;Historial
                        </button>
                        <button v-if="historial == 1" type="button" @click="historial=0" class="btn btn-default">
                            <i class="fa fa-mail-reply"></i>&nbsp;Regresar
                        </button>
                        <!---->
                    </div>
                    <div class="card-body" v-if="historial == 0">
                        <div class="form-group row">
                            <div class="col-md-9">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-4" v-model="criterio">
                                      <option value="lotes.fraccionamiento_id">Fraccionamiento</option>
                                    </select>
                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar" @click="selectEtapa(buscar)" >
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>

                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_etapa" @keyup.enter="listarLotes(1)"> 
                                        <option value="">Etapa</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                    </select>
                                    <input type="text" v-if="buscar!=''" v-model="b_manzana" @keyup.enter="listarLotes(1)" class="form-control" placeholder="Manzana a buscar">
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <select class="form-control" v-model="b_empresa" >
                                        <option value="">Empresa constructora</option>
                                        <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                    </select>
                                    <button type="submit" @click="listarLotes(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" @click="selectAll" v-model="allSelected"> Todos
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
                                        <td v-text="lote.num_etapa"></td>
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

                    <div class="card-body" v-if="historial == 1">
                        <div class="form-group row">
                            <div class="col-md-7">
                                <div class="input-group">
                                    <select class="form-control" v-model="b_empresa" >
                                        <option value="">Empresa constructora</option>
                                        <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-4" v-model="criterio">
                                      <option value="lotes.fraccionamiento_id">Fraccionamiento</option>
                                    </select>
                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar" @click="selectEtapa(buscar)" >
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>

                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_etapa" @keyup.enter="listarHistorial(1)"> 
                                        <option value="">Etapa</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                    </select>
                                    <input type="text" v-if="buscar!=''" v-model="b_manzana" @keyup.enter="listarHistorial(1)" class="form-control" placeholder="Manzana a buscar">
                                </div>
                            </div>

                            <div class="col-md-7">
                                <div class="input-group">
                                    <input type="date" v-model="b_fecha" @keyup.enter="listarHistorial(1)" class="form-control">
                                    <input type="date" v-model="b_fecha2" @keyup.enter="listarHistorial(1)" class="form-control">
                                    <button type="submit" @click="listarHistorial(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                            
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Fraccionamiento</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th># Lote</th>
                                        <th>Modelo</th>
                                        <th>Terreno mts&sup2;</th>
                                        <th>Construcción mts&sup2;</th>
                                        <th>Fecha de solicitud</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="lote in arrayHistorial" :key="lote.id">
                                        
                                        <td v-text="lote.proyecto"></td>
                                        <td v-text="lote.num_etapa"></td>
                                        <td v-text="lote.manzana"></td>
                                        <td v-text="lote.num_lote"></td>
                                        <td v-text="lote.modelo"></td>
                                        <td v-text="lote.terreno"></td>
                                        <td v-text="lote.construccion"></td>
                                        <td v-text="this.moment(lote.fecha_siembra).locale('es').format('DD/MMM/YYYY')"></td>
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
                                <li class="page-item" v-for="page in pagesNumber2" :key="page" :class="[page == isActived2 ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(page)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination2.current_page < pagination2.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page + 1)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>



                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal solicitud de RUV-->
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
                                    <label class="col-md-3 form-control-label" for="text-input">Paquete RUV</label>
                                    <div class="col-md-3">
                                        <input type="text" v-model="paquete" class="form-control" placeholder="PROYECTO00">
                                    </div>
                                </div>
                               
                                <!-- Div para mostrar los errores que mande validerDepartamento -->
                                <div v-show="errorLotes" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjLotes" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="solicitarRuv()">Enviar</button>
                            
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
                allSelected: false,
                id:0,
                arrayLotes : [],
                arrayHistorial : [],
                lotes_ini : [],
                arrayEtapas: [],
                arrayFraccionamientos:[],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                historial:0,
                errorLotes : 0,
                errorMostrarMsjLotes : [],
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
                b_etapa:'',
                b_manzana:'',
                b_fecha:'',
                b_fecha2:'',
                paquete:'',
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

            selectFraccionamientos(){
                let me = this;
                if(me.modal == 0){
                me.buscar=""
                me.b_etapa=""
                me.b_manzana=""
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
                
                me.b_etapa=""
                me.b_manzana=""
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
            listarLotes(page){
                let me = this;
                var url = '/ruv/indexLotes?page=' + page + '&buscar=' + me.buscar + '&b_etapa=' + me.b_etapa + '&b_manzana=' + me.b_manzana
                                     + '&b_fecha=' + me.b_fecha + '&b_fecha2=' + me.b_fecha2 + '&empresa=' + me.b_empresa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLotes = respuesta.lotes.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
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

            /**Metodo para mostrar los registros */
            listarHistorial(page){
                let me = this;
                var url = '/ruv/historialSolicitudes?page=' + page + '&buscar=' + me.buscar + '&b_etapa=' + me.b_etapa + 
                    '&b_manzana=' + me.b_manzana + '&fecha=' + me.b_fecha + '&fecha2=' + me.b_fecha2 + '&empresa=' + me.b_empresa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayHistorial = respuesta.lotes.data;
                    me.pagination2 = respuesta.pagination;
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
                me.listarLotes(page);
            },
            cambiarPagina2(page){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarHistorial(page);
            },
            /**Metodo para registrar  */
            solicitarRuv(){
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
                    axios.post('/ruv/solicitar',{
                    'id': element,
                    'paquete' : this.paquete
                    }); 
                    i++;
                });
                Swal({
                title: 'Enviado!',
                text: 'RUVs solicitados correctamente.',
                imageUrl: 'https://d2r6jp7chi630e.cloudfront.net/blog/aritic-pinpoint/wp-content/uploads/sites/3/2016/09/email-gif.gif',
                imageWidth: 800,
                imageHeight: 400,
                imageAlt: 'Custom image',
                animation: true
                })
                me.proceso=false;
                me.cerrarModal();
                me.listarLotes(1);
                
            },
            
            validarInicioObra(){
                this.errorLotes=0;
                this.errorMostrarMsjLotes=[];

                if(!this.paquete || this.paquete == '') //Si la variable departamento esta vacia
                    this.errorMostrarMsjLotes.push("Seleccionar la fecha de inicio.");

                if(this.errorMostrarMsjLotes.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorLotes = 1;

                return this.errorLotes;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.errorLotes = 0;
                this.errorMostrarMsjLotes = [];
                this.lotes_ini = [];
                this.allSelected = false;

            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(modelo, accion,data =[]){
                if(this.lotes_ini.length<1){
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
                                this.tituloModal = 'Solicitar RUV';
                                //this.f_ini = '';
                                this.paquete ='';
                                this.tipoAccion = 1;
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarLotes(1);
            this.listarHistorial(1);
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
