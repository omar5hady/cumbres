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
                        <i class="fa fa-align-justify"></i>Acta de terminacion

                        <!--   Boton   -->
                        
                        
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-5" v-model="criterio" @click="selectFraccionamientos()">
                                        <option value="lotes.fraccionamiento_id">Proyecto</option>
                                        <option value="licencias.term_ingreso">Fecha ingreso</option>
                                        <option value="licencias.term_salida">Fecha salida</option>
                                        <option value="licencias.avance">Porcentaje de avance</option>
                                    </select>

                                    <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar" >
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>
                                    <input v-if="criterio=='lotes.fraccionamiento_id'" type="text"  v-model="b_manzana" @keyup.enter="listarActa(1,buscar,b_manzana,b_lote,criterio,buscar2)" class="form-control" placeholder="Manzana">
                                    <input v-if="criterio=='lotes.fraccionamiento_id'" type="text"  v-model="b_lote" @keyup.enter="listarActa(1,buscar,b_manzana,b_lote,criterio,buscar2)" class="form-control" placeholder="# Lote">
                                   
                                    <input type="date" v-if="criterio=='licencias.term_ingreso' || criterio== 'licencias.term_salida'" v-model="buscar" @keyup.enter="listarActa(1,buscar,b_manzana,b_lote,criterio,buscar2)" class="form-control col-md-6" placeholder="Desde" >
                                    <input type="date" v-if="criterio=='licencias.term_ingreso' || criterio== 'licencias.term_salida'" v-model="buscar2"  @keyup.enter="listarActa(1,buscar,b_manzana,b_lote,criterio,buscar2)" class="form-control col-md-6" placeholder="Hasta" >

                                    <input v-if="criterio!='lotes.fraccionamiento_id' && criterio!='licencias.term_ingreso' && criterio!='licencias.term_salida'" type="text"  v-model="buscar" @keyup.enter="listarActa(1,buscar,b_manzana,b_lote,criterio,buscar2)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarActa(1,buscar,b_manzana,b_lote,criterio,buscar2)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    
                                    <th>Opciones</th>
                                    <th>Proyecto</th>
                                    <th>Manzana</th>
                                    <th># Lote</th>
                                    <th>Terreno mts&sup2;</th>
                                    <th>Construcción mts&sup2;</th>
                                    <th>Modelo</th>
                                    <th>Avance</th>
                                    <th>Ingreso Act. terminacion</th>
                                    <th>Salida Act. terminacion</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="act_terminacion in arrayActaDeTerminacion" :key="act_terminacion.id">
                                    
                                    <td >
                                        <button title="Editar" type="button" @click="abrirModal('lote','actualizar',act_terminacion)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <button type="button" @click="abrirModal2('lote','ver',act_terminacion)" class="btn btn-info btn-sm">
                                          <i class="icon-magnifier"></i>
                                        </button>
                                    </td>
                                    <td v-text="act_terminacion.proyecto"></td>
                                    <td v-text="act_terminacion.manzana"></td>
                                    <td v-text="act_terminacion.num_lote"></td>
                                    <td v-text="act_terminacion.terreno"></td>
                                    <td v-text="act_terminacion.construccion"></td>
                                    
                                    <!--Modelo-->
                                    <td>
                                        <span v-if = "act_terminacion.modelo!='Por Asignar' && act_terminacion.cambios==0" class="badge badge-success" v-text="act_terminacion.modelo"></span>
                                        <span v-if = "act_terminacion.modelo=='Por Asignar'" class="badge badge-danger">Por Asignar</span>
                                        <span v-if = "act_terminacion.cambios==1" class="badge badge-warning" v-text="act_terminacion.modelo"></span>
                                    </td>

                                    <!--Avance-->
                                     <td v-text="act_terminacion.avance"></td>

                                    <!-- Fecha Ingreso -->
                                    <td v-if="!act_terminacion.term_ingreso" v-text="''"></td>
                                    <td v-else v-text="this.moment(act_terminacion.term_ingreso).locale('es').format('DD/MMM/YYYY')"></td>
                                    <!-- Fecha Salida -->
                                    <td v-if="!act_terminacion.term_salida" v-text="''"></td>
                                    <td v-else v-text="this.moment(act_terminacion.term_salida).locale('es').format('DD/MMM/YYYY')"></td>
                                    
                                </tr>                               
                            </tbody>
                        </table>  
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page > 7">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(1,buscar,b_manzana,b_lote,criterio,buscar2)">Inicio</a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,b_manzana,b_lote,criterio,buscar2)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,b_manzana,b_lote,criterio,buscar2)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,b_manzana,b_lote,criterio,buscar2)">Sig</a>
                                </li>
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page<pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.last_page,buscar,b_manzana,b_lote,criterio,buscar2)">Ultimo</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
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
                                    <label class="col-md-3 form-control-label" for="text-input">Avance</label>
                                    <div class="col-md-6">
                                       <input type="number" v-model="avance" class="form-control" >
                                    </div>
                                </div>

                                  <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Ingreso de acta</label>
                                    <div class="col-md-6">
                                       <input type="date" v-model="term_ingreso" class="form-control" >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Salida de acta</label>
                                    <div class="col-md-6">
                                       <input type="date" v-model="term_salida" class="form-control" >
                                    </div>
                                </div>

                              
                                
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button"  class="btn btn-primary" @click="actualizarActa()">Actualizar</button>
                        </div>
                    </div>
                      <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->

            <!--Inicio del modal consulta-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar': modal2}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal2"></h4>
                            <button type="button" class="close" @click="cerrarModal2()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Proyecto</label>
                                    <div class="col-md-6">
                                       <input type="text" disabled v-model="fraccionamiento"  class="form-control"  placeholder="Proyecto">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Clave catastral</label>
                                    <div class="col-md-4">
                                        <input type="text" disabled v-model="clv_catastral" class="form-control"  placeholder="Clave catastral">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Etapa de servicios</label>
                                    <div class="col-md-4">
                                        <input type="text" disabled v-model="etapa_servicios" class="form-control"  placeholder="Etapa de servicios">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Manzana</label>
                                    <div class="col-md-4">
                                        <input type="text" disabled v-model="manzana" class="form-control" placeholder="Manzana">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input"># Lote</label>
                                    <div class="col-md-4">
                                        <input type="text" disabled v-model="num_lote" class="form-control" placeholder="Numero de Lote">
                                    </div>
                                    <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Duplex</label>
                                    <div class="col-md-4" >
                                        <input type="text" disabled v-model="sublote" style="width:200px;"  class="form-control" placeholder="Duplex">
                                    </div>
                                </div>
                                </div>
                                  <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Prototipo</label>
                                    <div class="col-md-6">
                                       <input type="text" disabled v-model="modelo" class="form-control" placeholder="Prototipo">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Dirección</label>
                                    <div class="col-md-4">
                                        <input type="text" disabled v-model="calle " class="form-control" placeholder="Calle">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" disabled v-model="numero" class="form-control" placeholder="Numero">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" disabled v-model="interior" class="form-control" placeholder="Interior">
                                    </div>
                                </div>
                                
                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Construcción (mts&sup2;)</label>
                                    <div class="col-md-4">

                                        <input type="text" style="width:200px;" disabled v-model="construccion" class="form-control" placeholder="Construccion">
                                  
                                    </div>
                                    <div class="form-group row">
                                    <label class="col-md-4 form-control-label" for="text-input">Terreno(mts&sup2;)</label>
                                    <div class="col-md-3" >
                                     
                                        <input type="text" style="width:150px;" disabled v-model="terreno" class="form-control" placeholder="Terreno">
                                
                                    </div>
                                </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Arquitecto</label>
                                    <div class="col-md-4">

                                        <input type="text"  disabled v-model="arquitecto" class="form-control" placeholder="Arquitecto">
                                  
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Siembra</label>
                                    <div class="col-md-4">

                                        <input type="text" style="width:200px;" disabled v-model="siembra" class="form-control" placeholder="Siembra">
                                  
                                    </div>
                                    <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Planos</label>
                                    <div class="col-md-4">

                                        <input type="text" style="width:200px;" disabled v-model="f_planos" class="form-control" placeholder="Planos">
                                  
                                    </div>
                                </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Ingreso</label>
                                    <div class="col-md-4">

                                        <input type="text" style="width:200px;" disabled v-model="f_ingreso" class="form-control" placeholder="Ingreso">
                                  
                                    </div>
                                    <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Salida</label>
                                    <div class="col-md-4">

                                        <input type="text" style="width:200px;" disabled v-model="f_salida" class="form-control" placeholder="Salida">
                                  
                                    </div>
                                </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Num. Licencia</label>
                                    <div class="col-md-4">

                                        <input type="text"  disabled v-model="num_licencia" class="form-control" placeholder="Num. Licencia">
                                  
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal2()">Cerrar</button>
                           
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
               
                id: 0,
                siembra:'',
                f_planos:'',
                f_ingreso:'',
                f_salida:'',
                term_ingreso:'',
                term_salida:'',
                arquitecto_id:0,
                perito_dro:0,
                arquitecto:'',
                fraccionamiento:'',
                num_licencia:0,
                credito_puente: '',
                etapa_servicios: '',
                clv_catastral: '',
                fraccionamiento_id : 0,
                etapa_id: 0,
                manzana: '',
                num_lote: 0,
                sublote: '',
                modelo: 0,
                avance: 0,
                empresa_id: 0,
                calle: '',
                numero: '',
                interior: '',
                terreno : 0,
                construccion : 0,
                arrayActaDeTerminacion : [],
                arrayArquitectos:[],
                arrayFraccionamientos:[],
                modal : 0,
                modal2 : 0,
                tituloModal : '',
                tituloModal2 : '',
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
                criterio : 'lotes.fraccionanmiento_id', 
                buscar : '',
                buscar2 : '',
                b_manzana : '',
                b_lote : ''
                
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

            onImageChange(e){

                console.log(e.target.files[0]);

                this.file = e.target.files[0];

            },
           

            /**Metodo para mostrar los registros */
            listarActa(page, buscar,b_manzana,b_lote,criterio,buscar2){
                let me = this;
                var url = '/acta_terminacion?page=' + page + '&buscar=' + buscar + '&b_manzana=' + b_manzana + '&b_lote='+ b_lote  + '&criterio=' + criterio + '&buscar2=' + buscar2;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayActaDeTerminacion = respuesta.actas.data;
                    me.pagination = respuesta.pagination;
                    console.log(url);
                })
                .catch(function (error) {
                    console.log(error);
                });
                
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
                me.buscar="";
                me.b_arquitecto="";
                me.b_manzana="";
                me.b_lote="";
                me.b_modelo="";
                me.buscar2="";
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

            cambiarPagina(page,buscar,b_manzana,b_lote,b_modelo,b_arquitecto,criterio,buscar2){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarActa(page,buscar,b_manzana,b_lote,criterio,buscar2);
            },
            
            actualizarActa(){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/acta_terminacion/actualizar',{
                    'id':this.id,
                    'term_ingreso' : this.term_ingreso,
                    'term_salida' : this.term_salida,
                    'avance' : this.avance
                    
                    
                }).then(function (response){
                    me.cerrarModal();
                    me.listarActa(1,'','','','fraccionamientos.nombre','');
                    //window.alert("Cambios guardados correctamente");
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Cambios guardados correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
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
                this.avance = '';
                this.term_ingreso = '';
                this.term_salida = '';
                
                
                
                this.errorLote = 0;
                this.errorMostrarMsjLote = [];

            },
            cerrarModal2(){
                this.modal2 = 0;
                this.tituloModal2 = '';
                this.f_planos='';
                this.f_ingreso='';
                this.f_salida='';
                this.num_licencia='';
                this.arquitecto='';
                this.fraccionamiento='';
                this.etapa_id=0;
                this.manzana='';
                this.num_lote='';
                this.sublote='';
                this.modelo='';
                this.calle='';
                this.numero=''
                this.interior=''
                this.terreno='';
                this.construccion='';
                this.clv_catastral='';
                this.etapa_servicios='';
                this.siembra='';
                this.id=0;
                
            },

           
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(licencias, accion,data =[]){
                switch(licencias){
                    case "lote":
                    {
                        switch(accion){
                           
                            case 'actualizar':
                            {
                                this.modal =1;
                                this.tituloModal='Actualizar Acta de terminación';
                             
                                this.term_ingreso=data['term_ingreso'];
                                this.term_salida=data['term_salida'];
                                this.avance=data['avance'];
                                this.id=data['id'];
                                break;
                            }
                            case 'ver':
                            {

                            }
                        }
                    }
                }this.selectArquitectos();

            },
            abrirModal2(licencias, accion,data =[]){
                switch(licencias){
                    case "lote":
                    {
                        switch(accion){
                           
                            case 'ver':
                            {
                                this.modal2 =1;
                                this.tituloModal2='Consulta ';
                                this.f_planos=moment(data['f_planos']).locale('es').format('DD/MMM/YYYY');
                                this.f_ingreso=moment(data['f_ingreso']).locale('es').format('DD/MMM/YYYY');
                                this.f_salida=moment(data['f_salida']).locale('es').format('DD/MMM/YYYY');
                                this.num_licencia=data['num_licencia'];
                                this.arquitecto=data['arquitecto'];
                                this.fraccionamiento=data['fraccionamiento'];
                                this.etapa_id=data['etapa_id'];
                                this.manzana=data['manzana'];
                                this.num_lote=data['num_lote'];
                                this.sublote=data['sublote'];
                                this.modelo=data['modelo'];
                                this.calle=data['calle'];
                                this.numero=data['numero'];
                                this.interior=data['interior'];
                                this.terreno=data['terreno'];
                                this.construccion=data['construccion'];
                                this.clv_catastral=data['clv_catastral'];
                                this.etapa_servicios=data['etapa_servicios'];
                                this.siembra=moment(data['siembra']).locale('es').format('DD/MMM/YYYY');
                                this.id=data['id'];
                                break;
                            }
                            
                        }
                    }
                }this.selectArquitectos();

            }
        
        },
        
        
        mounted() {
            this.listarActa(1,this.buscar,this.b_manzana,this.b_lote,this.criterio,this.buscar2);
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
        position: center !important;
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
    .scroll-box {
            overflow-x: scroll;
            width: auto;
            padding: 1rem
        }
</style>
