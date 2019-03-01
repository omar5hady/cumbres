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
                        <i class="fa fa-align-justify"></i> Historial de simulaciones de credito
                
                    </div>

                    <!----------------- Listado de Simulaciones de Credito ------------------------------>
                    <!-- Div Card Body para listar -->
                     <template>
                        <div class="card-body"> 
                            <div class="form-group row">
                                <div class="col-md-8">
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th># Folio</th>
                                            <th>Cliente</th>
                                            <th>Proyecto</th>
                                            <th>Etapa</th>
                                            <th>Manzana</th>
                                            <th># Lote</th>
                                            <th>Modelo</th>
                                            <th>Precio Venta</th>
                                            <th>Credito Solicitado</th>
                                            <th>Plazo</th>
                                            <th>Institucion</th>
                                            <th>Tipo de credito</th>
                                            <th>Status</th>
                                            <th>Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="prospecto in arraySimulaciones" :key="prospecto.id">
                                            <td v-text="prospecto.id"></td>
                                            <td v-text="prospecto.nombre + ' ' + prospecto.apellidos "></td>
                                            <td v-text="prospecto.proyecto"></td>
                                            <td v-text="prospecto.etapa"></td>
                                            <td v-text="prospecto.manzana"></td>
                                            <td v-text="prospecto.num_lote"></td>
                                            <td v-text="prospecto.modelo"></td>
                                            <td v-text="'$'+formatNumber(prospecto.precio_venta)"></td>
                                            <td v-text="'$'+formatNumber(prospecto.credito_solic)"></td>
                                            <td v-text="prospecto.plazo + ' años'"></td>
                                            <td v-text="prospecto.institucion"></td>
                                            <td v-text="prospecto.tipo_credito"></td>
                                            <td v-if="prospecto.status == '0'">
                                                <span class="badge badge-danger">Rechazado</span>
                                            </td>
                                            <td v-if="prospecto.status == '2'">
                                                <span class="badge badge-success">Aprobado</span>
                                            </td>
                                            <td>
                                                 <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right" @click="abrirModal3('prospecto','ver_todo'),listarObservacion(1,prospecto.prospecto_id)">Ver todos</button> 
                                            </td>
                                        </tr>                               
                                    </tbody>
                                </table>
                            </div>
                            <nav>
                                <!--Botones de paginacion -->
                                <ul class="pagination">
                                    <li class="page-item" v-if="pagination.current_page > 1">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                    </li>
                                    <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                    </li>
                                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </template>

                 
                    
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
           

               <!--Inicio del modal observaciones-->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal3}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal3"></h4>
                            <button type="button" class="close" @click="cerrarModal3()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                
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
                arrayObservacion:[],
             
                modal3: 0,
                modal2: 0,
                listado:1,
                tituloModal3 : '',
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
                buscar : ''
               
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
             listarSimulaciones(){
                let me = this;
                var url = '/historial_simulaciones_credito';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arraySimulaciones = respuesta.creditos.data;
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
            listarObservacion(page, buscar){
                let me = this;
                var url = '/clientes/observacion?page=' + page + '&buscar=' + buscar ;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayObservacion = respuesta.observacion.data;
                    console.log(url);
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },

            cambiarPagina(page, buscar, criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarProspectos(page,buscar,criterio);
            },
  
            aceptarSimulacion(){
                let me = this;
                //Con axios se llama el metodo update de DepartamentoController
                axios.put('/creditos/aceptar',{
                    'id': this.num_folio
                }).then(function (response){
                    me.listarSimulaciones();
                    me.limpiarDatos();
                    //window.alert("Cambios guardados correctamente");
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Solicitud aceptada',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            }, 
            rechazarSimulacion(){
                let me = this;
                //Con axios se llama el metodo update de DepartamentoController
                axios.put('/creditos/rechazar',{
                    'id': this.num_folio
                }).then(function (response){
                    me.listarSimulaciones(me.prospecto_id);
                    me.limpiarDatos();
                    //window.alert("Cambios guardados correctamente");
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Solicitud rechazada',
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


             cerrarModal3(){
                this.modal3 = 0;
                this.tituloModal3 = '';
              
            },
            
            abrirModal3(prospectos,accion){
             switch(prospectos){
                    case "prospecto":
                    {
                        switch(accion){
                         
                             case 'ver_todo':
                            {
                                this.modal3 =1;
                                this.tituloModal3='Consulta Observaciones';
                                this.tipoAccion= 4;
                                break;  
                            }
                            
                        }
                    }
                 
             }
                
         },

           
        },
        mounted() {
            //this.listarProspectos(1,this.buscar,this.criterio);           
            this.listarSimulaciones();
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
    }
</style>
