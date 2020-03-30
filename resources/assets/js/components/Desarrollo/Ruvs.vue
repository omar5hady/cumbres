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
                        <i class="fa fa-align-justify"></i>RUV
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">

                                    <select class="form-control" v-model="buscar">
                                        <option value="">Seleccione Proyecto</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>
                                    <input type="text"  v-model="b_etapa" @keyup.enter="listarRuvs(1)" class="form-control" placeholder="Etapa">
                                   
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                   
                                    <input type="text"  v-model="b_manzana" @keyup.enter="listarRuvs(1)" class="form-control" placeholder="Manzana">
                                    <input type="text"  v-model="b_lote" @keyup.enter="listarRuvs(1)" class="form-control" placeholder="# Lote">
                                   
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text"  v-model="b_paquete" @keyup.enter="listarRuvs(1)" class="form-control" placeholder="Paquete Ruv">
                                    <button type="submit" @click="listarRuvs(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    <a class="btn btn-success" v-bind:href="'/acta_terminacion/excel?buscar=' + buscar + '&b_manzana=' + b_manzana + '&b_lote='+ b_lote + '&criterio=' + criterio + '&buscar2=' + buscar2" >
                                        <i class="icon-pencil"></i>&nbsp;Excel
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th colspan="5"></th>
                                        <th colspan="3" class="text-center">Solicitud</th>
                                        <th colspan="2"></th>
                                        <th colspan="2" class="text-center">Asignación</th>
                                        <th colspan="3"></th>
                                    </tr>
                                    <tr>
                                        <th>Proyecto</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th># Lote</th>
                                        <th>Modelo</th>
                                        <th>Paquete </th>
                                        <th>Fecha</th>
                                        <th>Solicitante</th>
                                        <th>Carga de inf.</th>
                                        <th>Num. CUV</th>
                                        <th>Fecha</th>  
                                        <th>Empresa</th>
                                        <th>Rev. Documental</th>
                                        <th>DTU</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="lote in arrayLotes" :key="lote.id">
                                        <td class="td2" v-text="lote.proyecto"></td>
                                        <td class="td2" v-text="lote.num_etapa"></td>
                                        <td class="td2" v-text="lote.manzana"></td>
                                        <td class="td2" v-text="lote.num_lote"></td>
                                        <td class="td2" v-text="lote.modelo"></td>
                                        <td class="td2" v-text="lote.paq_ruv"></td>
                                        <td class="td2" v-text="this.moment(lote.fecha_siembra).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td class="td2" v-text="lote.nombre + ' '+ lote.apellidos"></td>
                                        <td class="td2" v-if="lote.fecha_carga == null">
                                            <button type="button" @click="abrirModal('cargaInfo',lote)" class="btn btn-primary btn-sm" title="Carga de informacion">
                                                <i class="fa fa-check"></i>
                                            </button>
                                        </td>
                                        <td class="td2" v-else v-text="this.moment(lote.fecha_carga).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td class="td2" v-if="lote.num_cuv == null">
                                            <button type="button" @click="obtenerCuv(lote.id)" class="btn btn-dark btn-sm" title="Num. CUV">
                                                <i class="fa fa-edit">&nbsp;# CUV</i>
                                            </button>
                                        </td>
                                        <td class="td2" v-else v-text="lote.num_cuv"></td>
                                        <template v-if="lote.empresa == null">
                                            <td class="td2" colspan="2">
                                                <button type="button" @click="abrirModal('asignacion',lote)" class="btn btn-primary btn-sm" title="Asignación de verificador">
                                                    <i class="fa fa-users">&nbsp;Asignar Verificador</i>
                                                </button>
                                            </td>
                                        </template>
                                        <template v-else>
                                            <td class="td2" v-text="lote.fecha_asignacion"></td>
                                            <td class="td2" v-text="lote.empresa"></td>
                                        </template>
                                        
                                        <td class="td2" v-if="lote.fecha_revision == null">
                                            <button type="button" @click="abrirModal('revision',lote)" class="btn btn-dark btn-sm" title="Revisión Documental">
                                                <i class="fa fa-check"></i>
                                            </button>
                                        </td>
                                        <td class="td2" v-else v-text="lote.fecha_revision"></td>
                                        <td class="td2" v-if="lote.fecha_dtu == null">
                                            <button type="button" @click="abrirModal('dtu',lote)" class="btn btn-primary btn-sm" title="Obtención de DTU">
                                                <i class="fa fa-check"></i>
                                            </button>
                                        </td>
                                        <td class="td2" v-else v-text="lote.fecha_dtu"></td>
                                        <td class="td2">
                                            <button type="button" @click="abrirModal('observaciones',lote)" class="btn btn-warning btn-sm" title="Observaciones">
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

                                <div class="form-group row" v-if="tipoAccion == 1">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de carga</label>
                                    <div class="col-md-4">
                                       <input type="date" v-model="fecha" class="form-control" >
                                    </div>
                                </div>
                              
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion == 1 && fecha != ''" class="btn btn-primary" @click="cargarInformacion()">Guardar</button>
                        </div>
                    </div>
                      <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->

            <!--Inicio del modal observaciones-->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal2}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal3()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                <div class="form-group row" v-if="tipoAccion==3">
                                    <label class="col-md-3 form-control-label" for="text-input">Observacion</label>
                                    <div class="col-md-6">
                                         <textarea rows="5" cols="30" v-model="observacion" class="form-control" placeholder="Observacion"></textarea>

                                    </div>
                                </div>
                                <div class="form-group row"  v-if="tipoAccion==3">
                                    <label class="col-md-3 form-control-label" for="text-input">Usuario</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="usuario" class="form-control" placeholder="Usuario">
                                    </div>
                                </div>
                                <!--//////////tabla de consulta de observaciones//////////////-->
                                <table class="table table-bordered table-striped table-sm" v-if="tipoAccion == 4">
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
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal3()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button"  v-if="tipoAccion==3"  class="btn btn-primary" @click="agregarComentario()">Guardar</button>
                        </div>
                    </div>
                      <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal observaciones-->

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
                allLic: [],
                allSelected: false,

                proceso:false,
                id: 0,
                observacion:'',
                arrayLotes : [],
                arrayFraccionamientos:[],
                arrayObservacion:[],
                arrayEmpresas:[],
                modal : 0,
                modal2 : 0,
                tituloModal : '',
                tituloModal2 : '',
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
                buscar2 : '',
                b_etapa:'',
                b_manzana : '',
                b_lote : '',
                b_paquete : '',

                fecha:'',
                empresa:'',
                
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
            listarRuvs(page){
                let me = this;
                var url = '/ruv/indexRuv?page=' + page + '&buscar=' + me.buscar + '&b_etapa=' + me.b_etapa + '&b_manzana=' + me.b_manzana + '&b_lote=' + me.b_lote + '&b_paquete=' + me.b_paquete; 
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLotes = respuesta.lotes.data;
                    me.pagination = respuesta.pagination;
                    console.log(url);
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

            selectEmpresaVerif(){
                let me = this;
               
                me.arrayEmpresas=[];
                var url = '/empresa/selectEmpresaVerificadora';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEmpresas = respuesta.fraccionamientos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            agregarComentario(){
                if (this.proceso === true) {
                    return;
                } 
                this.proceso=true;
                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/observacion/registrar',{
                    'lote_id': this.lote_id,
                    'comentario': this.observacion,
                    'usuario': this.usuario
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal3(); //al guardar el registro se cierra el modal
                    
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

            cargarInformacion(){

                let me = this;
                //Con axios se llama el metodo update de FraccionaminetoController
                axios.put('/ruv/cargaInfo',{
                    'id' : this.id,
                    'fecha': this.fecha
                }).then(function (response){
                    me.cerrarModal();
                    me.listarRuvs(1);
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

            obtenerCuv(id){

                let me = this;
                (async function getFruit () {
                    const {value: numero} = await Swal({
                    title: 'Obtención de #CUV',
                    input: 'text',
                    inputPlaceholder: 'Escribe el numero aqui...',
                    showCancelButton: true,
                    inputValidator: (value) => {
                        return new Promise((resolve) => {
                        if (value != '') {
                            resolve()
                        } else {
                            resolve('Es necesario escribir el numero de CUV')
                        }
                        })
                    }
                    })

                    if (numero) {
                        //Con axios se llama el metodo update de LoteController
                        axios.put('/ruv/obtenerCuv',{
                            'id': id,
                            'numeroCuv': numero
                        }).then(function (response){
                            me.cerrarModal();
                            me.listarRuvs(1);
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
                    }

                    })()

            },

            listarObservacion(page, buscar){
                let me = this;
                var url = '/observacion?page=' + page + '&buscar=' + buscar ;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayObservacion = respuesta.observacion.data;
                    me.pagination = respuesta.pagination;
                    console.log(url);
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
                me.listarRuvs(page);
            },

            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.avance = '';
                this.term_ingreso = '';
                this.term_salida = '';
                this.num_acta='';
                
                this.errorActa = 0;
                this.errorMostrarMsjActa = [];

            },

            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(accion,data =[]){
                this.selectEmpresaVerif();
                switch(accion){
                    
                    case 'cargaInfo':
                    {
                        this.modal =1;
                        this.tituloModal='Carga de Informacion RUV';
                        this.fecha='';
                        this.tipoAccion=1;
                        this.id = data['id'];
                        break;
                    }
                    case 'asignacion':
                    {
                        this.modal =1;
                        this.tituloModal='Asignación de verificador';
                        this.fecha='';
                        this.empresa = '';
                        this.tipoAccion=2;
                        this.id = data['id'];
                        break;
                    }
                }
            }       
        },
        
        
        mounted() {
            this.listarRuvs(1);
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
