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
                        <i class="fa fa-align-justify"></i> Administración de rentas
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <select class="form-control" v-model="buscar"  @change="selectEtapa(buscar), selectModelo(buscar)">
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>
                                    
                                </div>
                                <div class="input-group">
                                    <select class="form-control" v-model="buscar2" @keyup.enter="indexLotesRentas(1)"> 
                                        <option value="">Etapa</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                    </select>

                                    <select class="form-control" v-model="b_modelo" @keyup.enter="indexLotesRentas(1)">
                                        <option value="">Modelo</option>
                                        <option v-for="modelos in arrayModelos" :key="modelos.id" :value="modelos.id" v-text="modelos.nombre"></option>
                                    </select>
                                </div>

                                <div class="input-group">                                    

                                    <input type="text" v-model="buscar3" @keyup.enter="indexLotesRentas(1)" class="form-control" placeholder="Manzana a buscar">
                                    <input type="text" v-model="b_lote" @keyup.enter="indexLotesRentas(1)" class="form-control" placeholder="Lote a buscar">
                                    
                                </div>
                                <div class="input-group">
                                    <select class="form-control  col-md-4" v-if="rolId!='2'" v-model="b_status" @keyup.enter="indexLotesRentas(1)">
                                        <option value="">Todos</option>
                                        <option value=0>Disponibles</option>
                                        <option value=1>Rentadas</option>
                                    </select>
                                </div>

                                <div class="input-group">
                                    <button type="submit" @click="indexLotesRentas(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="table-responsive"> 
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Proyecto</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
                                        <th># Lote</th>
                                        <th>Modelo</th>
                                        <th>Dirección oficial</th>
                                        <th>Precio renta mensual</th>
                                        <th>Observaciones</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="lote in arrayLotes.data" :key="lote.id">
                                        <td class="td2">
                                            <button 
                                            type="button" @click="abrirModal('actualizar',lote)" class="btn btn-warning btn-sm">
                                                <i class="icon-pencil"></i>
                                            </button>
                                        </td>
                                        <td class="td2" v-text="lote.proyecto"></td>
                                        <td class="td2" v-text="lote.num_etapa"></td>
                                        <td class="td2" v-text="lote.manzana"></td>
                                        <td class="td2" v-text="lote.num_lote"></td>
                                        <td class="td2" v-text="lote.modelo"></td>
                                        <td v-if="lote.interior == null" class="td2" v-text="lote.calle + ' No.' + lote.numero"></td>
                                        <td v-else class="td2" v-text="lote.calle + ' No.' + lote.numero + ' ' + lote.interior"></td>
                                        <td class="td2" v-text="'$' + formatNumber(lote.precio_renta)"></td>
                                        <td class="td2" v-text="lote.comentarios"></td>
                                        <td class="td2">
                                            <span v-if="lote.contrato == 0" class="badge badge-success">Disponible</span>
                                            <span v-if="lote.contrato == 1" class="badge badge-warning">Rentada</span>
                                        </td>
                                    </tr>                               
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
                           <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="arrayLotes.current_page > 5" @click="indexLotesRentas(1)">
                                    <a class="page-link" href="#" >Inicio</a>
                                </li>
                                <li class="page-item" v-if="arrayLotes.current_page > 1"
                                    @click="indexLotesRentas(arrayLotes.current_page-1)">
                                    <a class="page-link" href="#" >Ant</a>
                                </li>

                                <li class="page-item" v-if="arrayLotes.current_page-3 >= 1"
                                    @click="indexLotesRentas(arrayLotes.current_page-3)">
                                    <a class="page-link" href="#" v-text="arrayLotes.current_page-3"></a>
                                </li>
                                <li class="page-item" v-if="arrayLotes.current_page-2 >= 1"
                                    @click="indexLotesRentas(arrayLotes.current_page-2)">
                                    <a class="page-link" href="#" v-text="arrayLotes.current_page-2"></a>
                                </li>
                                <li class="page-item" v-if="arrayLotes.current_page-1 >= 1"
                                    @click="indexLotesRentas(arrayLotes.current_page-1)">
                                    <a class="page-link" href="#" v-text="arrayLotes.current_page-1"></a>
                                </li>
                                <li class="page-item active" >
                                    <a class="page-link" href="#" v-text="arrayLotes.current_page"></a>
                                </li>
                                <li class="page-item" 
                                    v-if="arrayLotes.current_page+1 <= arrayLotes.last_page">
                                    <a class="page-link" href="#" @click="indexLotesRentas(arrayLotes.current_page+1)" 
                                    v-text="arrayLotes.current_page+1"></a>
                                </li>
                                <li class="page-item" 
                                    v-if="arrayLotes.current_page+2 <= arrayLotes.last_page">
                                    <a class="page-link" href="#" @click="indexLotesRentas(arrayLotes.current_page+2)"
                                     v-text="arrayLotes.current_page+2"></a>
                                </li>
                                <li class="page-item" 
                                    v-if="arrayLotes.current_page+3 <= arrayLotes.last_page">
                                    <a class="page-link" href="#" @click="indexLotesRentas(arrayLotes.current_page+3)"
                                    v-text="arrayLotes.current_page+3"></a>
                                </li>

                                <li class="page-item" v-if="arrayLotes.current_page < arrayLotes.last_page"
                                    @click="indexLotesRentas(arrayLotes.current_page+1)">
                                    <a class="page-link" href="#" >Sig</a>
                                </li>
                                <li class="page-item" v-if="arrayLotes.current_page < 5 && arrayLotes.last_page > 5" @click="indexLotesRentas(arrayLotes.last_page)">
                                    <a class="page-link" href="#" >Ultimo</a>
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
                                <center>
                                    <h5> Datos del Lote </h5><br>
                                </center>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">
                                        Proyecto
                                    </label>
                                    <div class="col-md-4">
                                        <input type="text" disabled v-model="datosRenta.proyecto" class="form-control">
                                    </div>
                                    <label class="col-md-2 form-control-label" for="text-input">
                                        Etapa
                                    </label>
                                    <div class="col-md-4">
                                        <input type="text" disabled v-model="datosRenta.num_etapa" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">
                                        Manzana
                                    </label>
                                    <div class="col-md-4">
                                        <input type="text" disabled v-model="datosRenta.manzana" class="form-control">
                                    </div>
                                    <label class="col-md-2 form-control-label" for="text-input">
                                        Num. Lote
                                    </label>
                                    <div class="col-md-4">
                                        <input type="text" disabled v-model="datosRenta.num_lote" class="form-control">
                                    </div>
                                </div>

                                <hr>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">
                                        Precio renta mensual:
                                    </label>
                                    <div class="col-md-3">
                                        <input type="text" pattern="\d*" v-on:keypress="isNumber($event)" v-model="datosRenta.precio_renta" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <strong>
                                            <p style="color:blue;" v-text="'$'+formatNumber(datosRenta.precio_renta)"></p>
                                        </strong>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">
                                        Observaciones:
                                    </label>
                                    <div class="col-md-5">
                                        <textarea rows="2" cols="40" class="form-control" v-model="datosRenta.comentarios" placeholder="Observaciones"></textarea>
                                    </div>
                                   
                                </div>
                                
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizar()">Actualizar</button>
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
        props:{
            userName:{type: String},
        },
        data(){
            return{
                arrayLotes : [],
                arrayFraccionamientos : [],
                arrayModelos : [],
                arrayEtapas : [],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                criterio:'lotes.fraccionamiento_id',
                buscar : '',
                buscar2 : '',
                b_modelo : '',
                buscar3 : '',
                b_lote : '',
                b_status: '',
                datosRenta: {}
            }
        },
        computed:{
        },
        methods : {
            isNumber: function(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                    evt.preventDefault();;
                } else {
                    return true;
                }
            },

            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
            /**Metodo para mostrar los registros */
            indexLotesRentas(page){
                let me = this;
                var url = '/lotes/indexLotesRentas?page=' + page+'&criterio='+this.criterio
                   + '&buscar=' + this.buscar
                   + '&buscar2=' + this.buscar2
                   + '&buscar3=' + this.buscar3
                   + '&b_lote=' + this.b_lote
                   + '&b_status=' + this.b_status
                   + '&b_modelo=' + this.b_modelo;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLotes = respuesta;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectModelo(buscar){
                let me = this;
              
                me.arrayModelos=[];
                var url = '/select_modelo_proyecto?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayModelos = respuesta.modelos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectFraccionamientos(){
                let me = this;
                if(me.modal == 0){
                    me.buscar="";
                    me.buscar2="";
                    me.buscar3="";
                    me.b_empresa="";
                    me.b_empresa2="";
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
            /**Metodo para registrar  */
            actualizar(){
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.put('/lotes/updateDatosRenta',{
                    'id' : this.datosRenta.id,
                    'precio_renta' : this.datosRenta.precio_renta,
                    'comentarios' : this.datosRenta.comentarios
                }).then(function (response){
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.indexLotesRentas(1); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Lote actualizado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            cerrarModal(){
               this.modal = 0;
            },
            
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(accion,data =[]){
                switch(accion){
                    case 'actualizar':
                    {
                        //console.log(data);
                        this.modal =1;
                        this.tituloModal='Actualizar Lote';
                        this.datosRenta = data;
                        this.tipoAccion = 2;
                        break;
                    }
                }
            }
        },
        mounted() {
            this.indexLotesRentas(1);
            this.selectFraccionamientos();
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
</style>
