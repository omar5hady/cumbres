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
                                       <div class="input-group">
                                        <!--Criterios para el listado de busqueda -->
                                        <select class="form-control col-md-4" v-model="criterio" @click="limpiarBusqueda()">
                                            <option value="creditos.id"># Folio</option>
                                            <option value="personal.nombre">Cliente</option>
                                            <option value="v.nombre">Vendedor</option>
                                            <option value="clientes.proyecto_interes_id">Proyecto</option>
                                            <option value="inst_seleccionadas.tipo_credito">Tipo de credito</option>
                                        </select>
                                          <select class="form-control" v-if="criterio=='clientes.proyecto_interes_id'" v-model="buscar" >
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                         </select>
                                    <input v-if="criterio=='clientes.proyecto_interes_id'" type="text"  v-model="b_etapa" @keyup.enter="listarSimulaciones(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="form-control" placeholder="Etapa">
                                    <input v-if="criterio=='clientes.proyecto_interes_id'" type="text"  v-model="b_manzana" @keyup.enter="listarSimulaciones(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="form-control" placeholder="Manzana">
                                    <input v-if="criterio=='clientes.proyecto_interes_id'" type="text"  v-model="b_lote" @keyup.enter="listarSimulaciones(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="form-control" placeholder="# Lote">
                                        <input  v-else type="text" v-model="buscar" @keyup.enter="listarSimulaciones(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="form-control">
                                        <button type="submit" @click="listarSimulaciones(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                    <button type="submit" @click="abrirPDF()" class="btn btn-primary"><i class="fa fa-search"></i> Abrir</button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table2 table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th># Folio</th>
                                            <th>Cliente</th>
                                            <th>Vendedor</th>
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
                                            <td class="td2" v-text="prospecto.id"></td>
                                            <td class="td2" v-text="prospecto.nombre + ' ' + prospecto.apellidos "></td>
                                            <td class="td2" v-text="prospecto.vendedor_nombre + ' ' + prospecto.vendedor_apellidos "></td>
                                            <td class="td2" v-text="prospecto.proyecto"></td>
                                            <td class="td2" v-text="prospecto.etapa"></td>
                                            <td class="td2" v-text="prospecto.manzana"></td>
                                            <td class="td2" v-text="prospecto.num_lote"></td>
                                            <td class="td2" v-text="prospecto.modelo"></td>
                                            <td class="td2" v-text="'$'+formatNumber(prospecto.precio_venta)"></td>
                                            <td class="td2" v-text="'$'+formatNumber(prospecto.credito_solic)"></td>
                                            <td class="td2" v-text="prospecto.plazo + ' años'"></td>
                                            <td class="td2" v-text="prospecto.institucion" @click="listarObservacionIntSelect(prospecto.inst_credito),abrirModal5()"></td>
                                            <td class="td2" v-text="prospecto.tipo_credito" @click="listarObservacionIntSelect(prospecto.inst_credito),abrirModal5()"></td>
                                            <td class="td2" v-if="prospecto.status == '0'">
                                                <span class="badge badge-danger">Rechazado</span>
                                            </td>
                                            <td class="td2" v-if="prospecto.status == '2'">
                                                <span class="badge badge-success">Aprobado</span>
                                            </td>
                                            <td class="td2">
                                                 <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right" @click="abrirModal3('prospecto','ver_todo',prospecto.prospecto_id),listarObservacion(1,prospecto.prospecto_id)">Ver todos</button> 
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
                            
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nueva observación</label>
                                    <div class="col-md-9">
                                        <textarea rows="1" cols="30" class="form-control" v-model="observacion" placeholder="Observaciones"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary" @click="registrarObservacion()">Guardar</button>
                                    </div>
                                </div>
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
                arrayFraccionamientos: [],
                arrayObservacionCreditos: [],
             
                modal3: 0,
                modal2: 0,
                modal5: 0,
                listado:1,
                prospecto_id:0,
                observacion:'',
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
             listarSimulaciones(page, buscar, b_etapa, b_manzana,b_lote,criterio){
                let me = this;
                var url = '/historial_simulaciones_credito?page=' + page + '&buscar=' + buscar + '&b_etapa=' +b_etapa+ '&b_manzana=' + b_manzana + '&b_lote='+ b_lote + '&criterio=' + criterio;
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
            cambiarPagina(page, buscar, criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarProspectos(page,buscar,criterio);
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
            abrirPDF(){
                window.open('pdf/CUENTA-BANCOMER.pdf', '_blank');
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
            registrarObservacion(){

                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/clientes/storeObservacion',{
                    'cliente_id':this.prospecto_id,
                    'observacion':this.observacion                 
                }).then(function (response){
                    me.listarObservacion(1,me.prospecto_id);
                    me.observacion='';
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Observacion agregada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },


             cerrarModal3(){
                this.modal3 = 0;
                this.tituloModal3 = '';
              
            },
            limpiarBusqueda(){
                let me=this;
                me.buscar= "";
                me.b_etapa='';
                me.b_manzana='';
                me.b_lote='';
            },
            
            abrirModal5(){
                this.modal5=1;
            },
            cerrarModal5(){
                this.modal5=0;
            },
            abrirModal3(prospectos,accion,id){
             switch(prospectos){
                    case "prospecto":
                    {
                        switch(accion){
                         
                             case 'ver_todo':
                            {
                                this.modal3 =1;
                                this.tituloModal3='Consulta Observaciones';
                                this.tipoAccion= 4;
                                this.prospecto_id = id;
                                break;  
                            }
                            
                        }
                    }
                 
             }
                
         },

           
        },
        mounted() {          
            this.listarSimulaciones(1,this.buscar,this.b_etapa,this.b_manzana,this.b_lote,this.criterio);
            this.selectFraccionamientos();
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
