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
                        <button type="button" @click="abrirModal('arrendador')" class="btn btn-primary">
                            <i class="icon-plus"></i>&nbsp;Arrendadores
                        </button>
                        <button type="button" @click="abrirModal('testigos')" class="btn btn-primary">
                            <i class="icon-plus"></i>&nbsp;Testigos
                        </button>
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

                                    <input type="text" v-model="b_direccion" @keyup.enter="indexLotesRentas(1)" class="form-control" placeholder="Dirección oficial">
                                    
                                </div>
                                <div class="input-group">
                                    <select class="form-control  col-md-4" v-model="b_status" @keyup.enter="indexLotesRentas(1)">
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
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Proyecto</th>
                                        <th>Etapa</th>
                                        <th>Manzana</th>
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
                                            <button 
                                            type="button" @click="abrirModal('archivo',lote)" class="btn btn-danger btn-sm">
                                                <i class="fa fa-files-o"></i>
                                            </button>
                                        </td>
                                        <td class="td2" v-text="lote.proyecto"></td>
                                        <td class="td2" v-text="lote.num_etapa"></td>
                                        <td class="td2" v-text="lote.manzana"></td>
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
                        <div class="modal-body" v-if="modal == 1">
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
                                        Dirección:
                                    </label>
                                    <div class="col-md-8">
                                        <input type="text" disabled v-model="direccion" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">
                                        Dueño:
                                    </label>
                                    <div class="col-md-6">
                                        <select class="form-control" v-model="datosRenta.duenio_id">
                                            <option value="">Seleccione Dueño</option>
                                            <option v-for="arrendador in arrayArrendador" :key="arrendador.id" :value="arrendador.id" v-text="arrendador.nombre"></option>
                                        </select>
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
                        <div class="modal-body" v-if="modal == 2">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <template v-if="ver==1">
                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">
                                            Tipo
                                        </label>
                                        <div class="col-md-8">
                                            <select class="form-control" v-model="datosArrendador.tipo">
                                                <option value="0">Persona Fisica</option>
                                                <option value="1">Persona Moral</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">
                                            Dueño:
                                        </label>
                                        <div class="col-md-6">
                                            <input type="text" v-model="datosArrendador.nombre" class="form-control">
                                        </div>

                                        <label class="col-md-1 form-control-label" for="text-input">
                                            RFC:
                                        </label>

                                        <div class="col-md-3">
                                            <input type="text" v-model="datosArrendador.rfc" maxlength="13" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">
                                            Direccion:
                                        </label>
                                        <div class="col-md-5">
                                            <input type="text" v-model="datosArrendador.direccion" class="form-control">
                                        </div>
                                        <label class="col-md-1 form-control-label" for="text-input">
                                            CP:
                                        </label>
                                        <div class="col-md-2">
                                            <input type="text" v-model="datosArrendador.cp" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">
                                            Colonia:
                                        </label>
                                        <div class="col-md-5">
                                            <input type="text" v-model="datosArrendador.colonia" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">
                                            Municipio:
                                        </label>
                                        <div class="col-md-4">
                                            <input type="text" v-model="datosArrendador.municipio" class="form-control">
                                        </div>

                                        <label class="col-md-2 form-control-label" for="text-input">
                                            Estado:
                                        </label>

                                        <div class="col-md-4">
                                            <input type="text" v-model="datosArrendador.estado" maxlength="13" class="form-control">
                                        </div>
                                    </div>

                                    <hr>

                                    <center><h6>Datos Bancarios</h6></center>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">
                                            Cuenta:
                                        </label>
                                        <div class="col-md-4">
                                            <input type="text" pattern="\d*" v-on:keypress="isNumber($event)" maxlength="10" v-model="datosArrendador.cuenta" class="form-control">
                                        </div>

                                        <label class="col-md-2 form-control-label" for="text-input">
                                            Clabe:
                                        </label>
                                        <div class="col-md-4">
                                            <input type="text" pattern="\d*" v-on:keypress="isNumber($event)" v-model="datosArrendador.clabe" maxlength="21" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">
                                            Banco:
                                        </label>
                                        <div class="col-md-4">
                                            <input type="text" maxlength="30" v-model="datosArrendador.banco" class="form-control">
                                        </div>
                                    </div>

                                    <hr>
                                </template>

                                <div class="form-group row" v-if="ver == 0">
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-dark" @click="nuevoRegistro()">
                                            <i class="icon-plus"></i>&nbsp;Nuevo
                                        </button>
                                    </div>
                                </div>

                                <div class="form-group row" v-if="ver == 1">
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-danger" @click="ver=0, tipoAccion = 2">
                                            <i class="icon-clse"></i>&nbsp;Cancelar
                                        </button>
                                    </div>
                                </div>
                                

                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Tipo</th>
                                                    <th>Nombre</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="arrendador in arrayArrendador" :key="arrendador.id">
                                                    <td>
                                                        <button type="button" class="btn btn-warning" @click="vistaAct(arrendador,0)">
                                                            <i class="icon-pencil"></i>&nbsp;
                                                        </button>
                                                    </td>
                                                    <td class="td2">
                                                        <span v-if="arrendador.tipo == 0" class="badge badge-primary">Persona Fisica</span>
                                                        <span v-if="arrendador.tipo == 1" class="badge badge-primary">Persona Moral</span>
                                                    </td>
                                                    <td v-text="arrendador.nombre"></td>
                                                </tr>                      
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                            </form>
                        </div>
                        <div class="modal-body" v-if="modal == 3">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <template v-if="ver==1">

                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">
                                            Nombre:
                                        </label>
                                        <div class="col-md-6">
                                            <input type="text" v-model="datosTestigo.nombre" class="form-control">
                                        </div>
                                    </div>
                                </template>

                                <div class="form-group row" v-if="ver == 0">
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-dark" @click="nuevoRegistro()">
                                            <i class="icon-plus"></i>&nbsp;Nuevo
                                        </button>
                                    </div>
                                </div>

                                <div class="form-group row" v-if="ver == 1">
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-danger" @click="ver=0, tipoAccion = 2">
                                            <i class="icon-clse"></i>&nbsp;Cancelar
                                        </button>
                                    </div>
                                </div>
                                

                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Nombre</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="testigo in arrayTestigos" :key="testigo.id">
                                                    <td>
                                                        <button type="button" class="btn btn-warning" @click="vistaAct(testigo,1)">
                                                            <i class="icon-pencil"></i>&nbsp;
                                                        </button>
                                                    </td>
                                                    <td v-text="testigo.nombre"></td>
                                                </tr>                      
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                            </form>
                        </div>

                        <div class="modal-body" v-if="modal == 4">
                            <form  method="post" @submit="formSubmitArchivo" enctype="multipart/form-data">
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Archivo</label>
                                    <div class="col-md-9">
                                        <input type="file" accept="application/pdf" class="form-control" v-on:change="onArchivo">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-9"></div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-success">Guardar Archivo.</button>
                                    </div>
                                </div>
                                
                                <br/>
                            </form>

                        </div>

                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <template v-if="modal != 4">
                                <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizar()">Actualizar</button>
                                <button type="button" v-if="modal == 2 && tipoAccion==1" class="btn btn-primary" @click="updateArrendador()">Actualizar</button>
                                <button type="button" v-if="modal == 2 && tipoAccion==3" class="btn btn-primary" @click="storeArrendador()">Guardar</button>

                                <button type="button" v-if="modal == 3 && tipoAccion==1" class="btn btn-primary" @click="updateTestigo()">Actualizar</button>
                                <button type="button" v-if="modal == 3 && tipoAccion==3" class="btn btn-primary" @click="storeTestigo()">Guardar</button>
                            </template>
                            <template v-if="modal == 4 && datosRenta.archivo_esp != null">
                                <button type="button" class="btn btn-success" @click="verArchivo(datosRenta.archivo_esp)">Ver archivo</button>
                            </template>
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
                arrayArrendador : [],
                arrayTestigos : [],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                criterio:'lotes.fraccionamiento_id',
                buscar : '',
                buscar2 : '',
                b_modelo : '',
                b_direccion : '',
                b_status: '',
                datosRenta: {},
                datosArrendador : {},
                datosTestigo : {},
                direccion: '',
                ver:0,
                archivo : ''
            }
        },
        computed:{
        },
        methods : {
            onArchivo(e){
                this.archivo = e.target.files[0];
            },
            formSubmitArchivo(e) {

                e.preventDefault();

                let currentObj = this;
                let formData = new FormData();
           
                formData.append('archivo', this.archivo);
                let me = this;
                axios.post('/rentas/formSubmitArchivo/'+me.datosRenta.id, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Archivo guardado correctamente',
                        showConfirmButton: false,
                        timer: 2000
                        })
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.indexLotesRentas(1); //se enlistan nuevamente los registros
                }).catch(function (error) {
                    currentObj.output = error;
                    console.log(error);
                });

            },
            verArchivo(archivo){
                window.open('/files/lotes/archivoRentas/'+archivo, '_blank')
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
                   + '&b_direccion=' + this.b_direccion
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
            getArrendador(){
                let me = this;
              
                me.arrayArrendador=[];
                var url = '/rentas/getArrendador';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayArrendador = respuesta;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getTestigos(){
                let me = this;
              
                me.arrayTestigos=[];
                var url = '/rentas/getTestigos';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayTestigos = respuesta;
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
            vistaAct(data,tipo){
                if(tipo == 0)
                    this.datosArrendador = data;
                else
                    this.datosTestigo = data;
                this.ver = 1;
                this.tipoAccion = 1;
            },
            nuevoRegistro(){
                this.datosArrendedor = {};
                this.datosTestigo = {}
                this.ver = 1;
                this.tipoAccion= 3
            },
            actualizar(){
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.put('/lotes/updateDatosRenta',{
                    'id' : this.datosRenta.id,
                    'precio_renta' : this.datosRenta.precio_renta,
                    'comentarios' : this.datosRenta.comentarios,
                    'duenio_id' : this.datosRenta.duenio_id,
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
            updateArrendador(){
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.put('/arrendador/updateArrendador',{
                    'id' : this.datosArrendador.id,
                    'nombre' : this.datosArrendador.nombre,
                    'tipo' : this.datosArrendador.tipo,
                    'rfc' : this.datosArrendador.rfc,
                    'direccion' : this.datosArrendador.direccion,
                    'cp' : this.datosArrendador.cp,
                    'colonia' : this.datosArrendador.colonia,
                    'municipio' : this.datosArrendador.municipio,
                    'cuenta' : this.datosArrendador.cuenta,
                    'clabe' : this.datosArrendador.clabe,
                    'banco' : this.datosArrendador.banco,
                }).then(function (response){
                    me.ver = 0;
                    me.tipoAccion = 2;
                    me.datosArrendador = {};
                    me.getArrendador();
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
            storeArrendador(){
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/arrendador/storeArrendador',{
                    'nombre' : this.datosArrendador.nombre,
                    'tipo' : this.datosArrendador.tipo,
                    'rfc' : this.datosArrendador.rfc,
                    'direccion' : this.datosArrendador.direccion,
                    'cp' : this.datosArrendador.cp,
                    'colonia' : this.datosArrendador.colonia,
                    'municipio' : this.datosArrendador.municipio,
                    'estado' : this.datosArrendador.estado,
                    'cuenta' : this.datosArrendador.cuenta,
                    'clabe' : this.datosArrendador.clabe,
                    'banco' : this.datosArrendador.banco,
                }).then(function (response){
                    me.ver = 0;
                    me.tipoAccion = 2;
                    me.datosArrendador = {};
                    me.getArrendador();
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
            updateTestigo(){
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.put('/testigo/updateTestigo',{
                    'id' : this.datosTestigo.id,
                    'nombre' : this.datosTestigo.nombre,
                }).then(function (response){
                    me.ver = 0;
                    me.tipoAccion = 2;
                    me.datosTestigo = {};
                    me.getTestigos();
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Datos actualizados correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            storeTestigo(){
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/testigo/storeTestigo',{
                    'nombre' : this.datosTestigo.nombre,
                }).then(function (response){
                    me.ver = 0;
                    me.tipoAccion = 2;
                    me.datosTestigo = {};
                    me.getTestigos();
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Datos actualizados correctamente',
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
                        this.direccion = this.datosRenta.calle+ ' No.'+this.datosRenta.numero
                        if(this.datosRenta.interior != null)
                            this.direccion = this.direccion + ' ' + this.datosRenta.interior
                        this.tipoAccion = 2;
                        break;
                    }
                    case 'arrendador':{
                        this.modal = 2;
                        this.tituloModal = 'Arrendadores';
                        this.datosArrendador = {};
                        this.ver=0;
                        break;
                    }
                    case 'testigos':{
                        this.modal = 3;
                        this.tituloModal = 'Testigos';
                        this.datosTestigo = {};
                        this.ver = 0;
                        break;
                    }
                    case 'archivo':{
                        this.modal = 4;
                        this.tituloModal = 'Subir archivo';
                        this.archivo = '';
                        this.datosRenta = data;
                        break;
                    }
                }
            }
        },
        mounted() {
            this.indexLotesRentas(1);
            this.selectFraccionamientos();
            this.getArrendador();
            this.getTestigos();
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
