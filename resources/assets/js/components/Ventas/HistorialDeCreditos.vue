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
                        <i class="fa fa-align-justify"></i> Historial de creditos 
                
                    </div>

                    <!----------------- Listado de Simulaciones de Credito ------------------------------>
                    <!-- Div Card Body para listar -->
                     <template>
                        <div class="card-body"> 
                            <div class="form-group row">
                                <div class="col-md-8">
                                       <div class="input-group">
                                        <!--Criterios para el listado de busqueda -->
                                        <select class="form-control col-md-4" v-model="criterio" @click="limpiarBusqueda()">
                                            <option value="creditos.id"># Folio</option>
                                            <option value="personal.nombre">Cliente</option>
                                            <option value="v.nombre">Vendedor</option>
                                            <option value="inst_seleccionadas.tipo_credito">Tipo de credito</option>
                                            <option value="inst_seleccionadas.institucion">Institucion</option>
                                        </select>
                                        <input type="text" v-model="buscar" @keyup.enter="listarHistorialCreditos(1,buscar,buscar2,criterio)" class="form-control">
                                        
                                    </div>
                                </div>

                                <div class="col-md-8">
                                       <div class="input-group">
                                        <select class="form-control col-md-4" v-model="buscar2">
                                            <option value="1">Pendientes</option>
                                            <option value="0">Rechazados</option>
                                            <option value="2">Aprobados</option>
                                        </select>
                                        <button type="submit" @click="listarHistorialCreditos(1,buscar,buscar2,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                         <span style="font-size: 1em; text-align:center;" class="badge badge-dark" v-text="'Total: '+ contador"> </span>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table2 table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th># Folio Simulacion</th>
                                            <th>Cliente</th>
                                            <th>Vendedor</th>
                                            <th>Institucion</th>
                                            <th>Tipo de credito</th>
                                            <th>Fecha de solicitud</th>
                                            <th>Monto</th>
                                            <th>Plazo</th>
                                            <th>Status</th>
                                            <th>Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="historial in arrayHistorialCreditos" :key="historial.id" @dblclick="abrirModal('actualizar', historial)" title="Actualizar credito">
                                            <td class="td2" v-text="historial.id_credito" @click="abrirModal('registrar', historial)"></td>
                                            <td class="td2">
                                                <a href="#" v-text="historial.nombre + ' ' + historial.apellidos"></a>
                                            </td>
                                            <td class="td2" v-text="historial.vendedor_nombre + ' ' + historial.vendedor_apellidos "></td>
                                            <td class="td2" v-text="historial.institucion"></td>
                                            <td class="td2" v-text="historial.tipo_credito"></td>
                                            <td class="td2" v-text="historial.fecha_ingreso"></td>
                                            <td class="td2" v-text="'$'+formatNumber(historial.monto_credito)"></td>
                                            <td class="td2" v-text="historial.plazo_credito"></td>
                                            <td class="td2" >
                                                <span v-if="historial.status == '1'" class="badge badge-warning">Pendiente</span>
                                                <span v-if="historial.status == '0'" class="badge badge-danger">Rechazado</span>
                                                <span v-if="historial.status == '2'" class="badge badge-success">Aprobado</span>
                                            </td>
                                           
                                            <td class="td2">
                                                 <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right" @click="listarObservacionIntSelect(historial.id),abrirModal5()">Ver todos</button> 
                                            </td>
                                        </tr>                               
                                    </tbody>
                                </table>
                            </div>
                            <nav>
                                <!--Botones de paginacion -->
                                <ul class="pagination">
                                    <li class="page-item" v-if="pagination.current_page > 1">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,buscar2,criterio)">Ant</a>
                                    </li>
                                    <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,buscar2,criterio)" v-text="page"></a>
                                    </li>
                                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,buscar2,criterio)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </template>

                 
                    
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
           


            <!--Inicio del modal observaciones de creditos-->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal5}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Historial de Observaciones</h4>
                            <button type="button" class="close" @click="cerrarModal5()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
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
                                        <tr v-for="observacion in arrayObservacionCreditos" :key="observacion.id">
                                            
                                            <td v-text="observacion.usuario" ></td>
                                            <td v-text="observacion.comentario" ></td>
                                            <td v-text="observacion.created_at"></td>
                                        </tr>                               
                                    </tbody>
                                </table>
                                
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal5()">Cerrar</button>
                        </div>
                    </div>
                      <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>

            <!--Inicio del modal observaciones de creditos-->
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
                                    <label class="col-md-3 form-control-label" for="text-input">Tipo de credito</label>
                                    <div class="col-md-6">
                                        <select class="form-control" v-model="tipo_credito" @change="selectInstitucion(tipo_credito)" >
                                            <option value="0">Seleccione</option>
                                            <option v-for="creditos in arrayCreditos" :key="creditos.nombre" :value="creditos.nombre" v-text="creditos.nombre"></option>   
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Institución de financiamiento</label>
                                    <div class="col-md-6">
                                        <select class="form-control" v-model="inst_financiera" >
                                            <option value="">Seleccione</option>
                                            <option v-for="institucion in arrayInstituciones" :key="institucion.institucion_fin" :value="institucion.institucion_fin" v-text="institucion.institucion_fin"></option>      
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Plazo (años)</label>
                                    <div class="col-md-4">
                                        <input type="text" maxlength="2" v-model="plazo_credito" pattern="\d*" v-on:keypress="isNumber($event)" class="form-control" placeholder="Plazo">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Monto</label>
                                    <div class="col-md-4">
                                        <input type="text" maxlength="10" v-model="monto_credito" pattern="\d*" v-on:keypress="isNumber($event)" class="form-control" placeholder="Monto">
                                    </div>
                                    <div class="col-md-3">
                                        <h6 v-text="'$'+formatNumber(monto_credito)"></h6>
                                    </div>

                                </div>
                                
                                <div class="form-group row" v-if="tipoAccion==2">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de ingreso</label>
                                    <div class="col-md-6">
                                        <input type="date" v-model="fecha_ingreso" class="form-control" placeholder="Fecha ingreso">
                                    </div>
                                </div>

                                <div class="form-group row" v-if="tipoAccion==2">
                                    <label class="col-md-3 form-control-label" for="text-input">Estatus</label>
                                    <div class="col-md-6">
                                        <select class="form-control" v-model="estatus">
                                            <option value="1">Pendiente</option>
                                            <option value="0">Rechazar</option>
                                            <option value="2">Aceptar</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row" v-if="tipoAccion==2 && tipoAccion == 2">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de vigencia</label>
                                    <div class="col-md-6">
                                        <input type="date" v-model="fecha_vigencia" class="form-control" placeholder="Fecha vigencia">
                                    </div>
                                </div>

                                <div class="form-group row" v-if="tipoAccion==2">
                                    <label class="col-md-3 form-control-label" for="text-input">Observación </label>
                                    <div class="col-md-6">
                                        <textarea rows="3" cols="30" v-model="observacion" class="form-control" placeholder="Observación"></textarea>
                                    </div>
                                </div>

                                 <!-- Div para mostrar los errores -->
                                <div v-show="errorInstSelec" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjInstSelect" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                                
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-success" @click="actualizarDatosCredito()" >Guardar cambios</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-success" @click="regisrarCredito()" >Guardar cambios</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="seleccionarCredito(id, folio)" >Elegir credito</button>
                        </div>
                    </div>
                      <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>

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
                arraySimulaciones:[],
                arrayHistorialCreditos: [],
                arrayObservacion:[],
                arrayFraccionamientos: [],
                arrayObservacionCreditos: [],
                arrayCreditos :[],
                arrayInstituciones :[],

                plazo_credito: 0,
                monto_credito: 0,
                fecha_ingreso : '',
                fecha_vigencia: '',
                estatus: '',
                observacion : '',
                id:0,
                folio:0,
                tipo_credito:'',
                inst_financiera:'',

                errorInstSelec : 0,
                errorMostrarMsjInstSelect : [],

                contadorHistCred: 0,
                contador:0,
                modal: 0,
                modal5: 0,
                listado:1,
                prospecto_id:0,
                observacion:'',
                tituloModal : '',
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
                criterio : 'personal.nombre', 
                buscar : '',
                buscar2: 1,
               
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
             listarHistorialCreditos(page, buscar,buscar2,criterio){
                let me = this;
                var url = '/historial_creditos?page=' + page + '&buscar=' + buscar + '&buscar2=' + buscar2 +'&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayHistorialCreditos = respuesta.Historialcreditos.data;
                    me.pagination = respuesta.pagination;
                    me.contador =respuesta.contadorHistCred;
                })
                .catch(function (error) {
                    console.log(error);
                })
            },
            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
            
            listarObservacionIntSelect(buscar){
                let me = this;
                var url = '/inst_select/observacion?page=' + 1 + '&buscar=' + buscar ;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayObservacionCreditos = respuesta.observacion.data;
                    me.pagination = respuesta.pagination;
                    console.log(url);
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },
            cambiarPagina(page, buscar,buscar2,criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarHistorialCreditos(page,buscar,buscar2,criterio);
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
            selectInstitucion(credito){
                let me = this;
                if(me.tipo_credito==0){
                    me.inst_financiera="";
                }
                me.arrayInstituciones=[];
                var url = '/select_institucion?buscar='+credito;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayInstituciones = respuesta.instituciones;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            validarInstSelect(){
                this.errorInstSelec=0;
                this.errorMostrarMsjInstSelect=[];

                if(this.observacion=='') 
                    this.errorMostrarMsjInstSelect.push("Ingresar una observacion");
                
                if(this.errorMostrarMsjInstSelect.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorInstSelec = 1;

                return this.errorInstSelec;
            },

            actualizarDatosCredito(){
                if(this.validarInstSelect()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                let me = this;
                //Con axios se llama el metodo update de DepartamentoController
                axios.put('/creditos_select/actualizar',{
                    'id': this.id,
                    'tipo_credito': this.tipo_credito,
                    'inst_financiera': this.inst_financiera,
                    'fecha_ingreso': this.fecha_ingreso,
                    'fecha_vigencia' : this.fecha_vigencia,
                    'plazo_credito': this.plazo_credito,
                    'monto_credito': this.monto_credito,
                    'status': this.estatus,
                    'observacion': this.observacion

                }).then(function (response){
                  
                    me.listarHistorialCreditos(1,me.buscar,me.buscar2,me.criterio)
                    me.cerrarModal()
                    //window.alert("Cambios guardados correctamente");
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Cambios guardados',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });

            },

            regisrarCredito(){
                if(this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;

                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/creditos_select/registrar',{
                    'credito_id':this.folio,
                    'tipo_credito':this.tipo_credito,
                    'institucion':this.inst_financiera,
                    'monto_credito':this.monto_credito,
                    'plazo_credito':this.plazo_credito
                }).then(function (response){
                    me.listarHistorialCreditos(1,me.buscar,me.buscar2,me.criterio)
                    me.cerrarModal();
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Credito agregado correctamente',
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

            seleccionarCredito(id,folio){
        
                swal({
                title: 'Esta seguro desea asignar este credito?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/creditos/seleccionar',{
                        'id': id,
                        'simulacion_id': folio
                    }).then(function (response) {
                        me.cerrarModal();
                        swal(
                        'Hecho!',
                        'Este credito ha sido elegido.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },
             
            limpiarBusqueda(){
                let me=this;
                me.buscar= "";
            },
            
            abrirModal5(){
                this.modal5=1;
            },
            cerrarModal5(){
                this.modal5=0;
            },

            abrirModal(accion,data=[]){

                switch(accion){
                    case 'actualizar':{
                        
                        this.plazo_credito=data['plazo_credito'];
                        this.monto_credito=data['monto_credito'];
                        this.fecha_ingreso =data['fecha_ingreso'];
                        this.fecha_vigencia = data['fecha_vigencia'];
                        this.estatus=data['status'];
                        this.observacion ='';
                        this.id=data['id'];
                        this.folio=data['id_credito'];
                        this.modal=1;
                        this.tituloModal = 'Actualizar credito';
                        this.inst_financiera = data['institucion'];
                        this.tipo_credito = data['tipo_credito'];
                        this.tipoAccion = 2;

                        break;
                    }
                    case 'registrar':{
                        this.modal=1;
                        this.tituloModal = 'Registrar credito para folio #' + data['id_credito'];
                        this.plazo_credito=0;
                        this.monto_credito=0;
                        this.fecha_ingreso ='';
                        this.estatus=1;
                        this.observacion ='';
                        this.id=0;
                        this.folio=data['id_credito'];
                        this.inst_financiera = '';
                        this.tipo_credito = '';
                        this.tipoAccion = 1;

                        break;
                    }

                }
                this.selectInstitucion(this.tipo_credito);

            },

            cerrarModal(){
                this.plazo_credito=0;
                this.monto_credito=0;
                this.fecha_ingreso ='';
                this.estatus=0;
                this.observacion ='';
                this.id=0;
                this.folio=0;
                this.modal=0;
                this.tituloModal = '';
            }

           
        },
        mounted() {          
            this.listarHistorialCreditos(1,this.buscar,this.buscar2,this.criterio);
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
    }
</style>
