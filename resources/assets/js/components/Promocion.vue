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
                        <i class="fa fa-align-justify"></i> Promociones
                        <!--   Boton Nuevo    -->
                        <button type="button" @click="abrirModal('promocion','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-4" v-model="criterio">
                                        <option value="promociones.nombre">Promocion</option>
                                        <option value="fraccionamientos.nombre">Proyecto</option>
                                        <option value="etapas.num_etapa">Etapa</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarPromociones(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarPromociones(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Proyecto</th>
                                        <th>Etapa</th>
                                        <th>Promocion</th>
                                        <th>Descripcion</th>
                                        <th>Descuento $</th>
                                        <th>Inicio</th>
                                        <th>Fin</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="promocion in arrayPromocion" :key="promocion.id">
                                        <td style="width:10%">
                                            <button type="button" @click="abrirModal('promocion','actualizar',promocion)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" @click="eliminarPromocion(promocion)">
                                            <i class="icon-trash"></i>
                                            </button>
                                            <button type="button" class="btn btn-success btn-sm" @click="abrirModal2('lote_promocion','registrar',promocion), listarLotePromociones(1, promocion.id)" title="Asignar a Lote" v-if="promocion.is_active == '1'">
                                            <i class="icon-share"></i>
                                            </button>
                                        </td>
                                        <td v-text="promocion.proyecto" ></td>
                                        <td v-text="promocion.etapas" ></td>
                                        <td v-text="promocion.nombre" ></td>
                                        <td v-text="promocion.descripcion" ></td>
                                        <td v-text="promocion.descuento" ></td>
                                        <td v-text="promocion.v_ini" ></td>
                                        <td v-text="promocion.v_fin" ></td>
                                        <td v-if="promocion.is_active == '1'">
                                            <span class="badge badge-success">Activo</span>
                                        </td>
                                        <td v-if="promocion.is_active == '0'">
                                            <span class="badge badge-danger">Desactivado</span>
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
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre de la promoción</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Promoción">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Proyecto</label>
                                    <div class="col-md-6">
                                       <select class="form-control" v-model="fraccionamiento_id" @click="selectEtapa(fraccionamiento_id)" >
                                            <option value="0">Seleccione</option>
                                            <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Etapa</label>
                                    <div class="col-md-6">
                                       <select class="form-control" v-model="etapa_id">
                                            <option value="0">Seleccione</option>
                                            <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de inicio</label>
                                    <div class="col-md-6">
                                        <input type="date" v-model="v_ini"  class="form-control" placeholder="Inicio">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de vencimiento</label>
                                    <div class="col-md-6">
                                        <input type="date" v-model="v_fin" class="form-control" placeholder="Vencimiento">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Descuento $</label>
                                    <div class="col-md-4">
                                        <input type="text" pattern="\d*" v-model="descuento" class="form-control" placeholder="Descuento" v-on:keypress="isNumber($event)">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Descripcion</label>
                                    <div class="col-md-6">
                                        <textarea rows="5" cols="30" v-model="descripcion" class="form-control" placeholder="Descripcion del paquete"></textarea>
                                    </div>
                                </div>


                                <!-- Div para mostrar los errores que mande validerPaquete -->
                                <div v-show="errorPromocion" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjPromocion" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarPromociones()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarPromociones()">Actualizar</button>
                        </div>
                        
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->


            <!--Inicio del modal asignar Lote-->
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
                                       <select class="form-control" v-model="fraccionamiento_id" @click="selectEtapa(fraccionamiento_id)"  disabled>
                                            <option value="0">Seleccione</option>
                                            <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Etapa</label>
                                    <div class="col-md-6">
                                       <select class="form-control" v-model="etapa_id" @click="selectManzanas(fraccionamiento_id,etapa_id)" disabled>
                                            <option value="0">Seleccione</option>
                                            <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Manzanas</label>
                                    <div class="col-md-6">
                                       <select class="form-control" v-model="manzana" @click="selectLotesManzana(fraccionamiento_id,etapa_id,manzana)">
                                            <option value="">Seleccione</option>
                                            <option v-for="manzanas in arrayManzanas" :key="manzanas.id" :value="manzanas.manzana" v-text="manzanas.manzana"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Lote</label>
                                    <div class="col-md-6">
                                       <select class="form-control" v-model="lote_id">
                                            <option value="0">Seleccione</option>
                                            <option v-for="lotes in arrayLotes" :key="lotes.id" :value="lotes.id" v-text="lotes.num_lote"></option>
                                        </select>
                                    </div>
                                </div>

                                
                                <!-- Div para mostrar los errores que mande validerPaquete -->
                                <div v-show="errorLotePromocion" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjLotePromocion" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal2()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" class="btn btn-primary" @click="registrarLotePromocion()">Guardar</button>

                            </div>
                                <div class="modal-header" v-if="mostrar == 1">
                                    <h5 class="modal-title"> Lotes con la promoción</h5>
                                </div>
                                <table class="table table-bordered table-striped table-sm" v-if="mostrar == 1">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Manzana</th>
                                            <th># Lote</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="lotePromocion in arrayLotePromocion" :key="lotePromocion.id">
                                            <td style="width:25%">
                                                <button type="button" class="btn btn-danger btn-sm" @click="eliminarLotePromocion(lotePromocion)">
                                                <i class="icon-trash"></i>
                                                </button>
                                            </td>
                                            <td v-text="lotePromocion.manzana" ></td>
                                            <td v-text="lotePromocion.lote" ></td>
                                        </tr>                               
                                    </tbody>
                                </table>
                                <nav>
                                    <!--Botones de paginacion -->
                                    <ul class="pagination">
                                        <li class="page-item" v-if="pagination2.current_page > 1">
                                            <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page - 1,id,criterio)">Ant</a>
                                        </li>
                                        <li class="page-item" v-for="page in pagesNumber2" :key="page" :class="[page == isActived2 ? 'active' : '']">
                                            <a class="page-link" href="#" @click.prevent="cambiarPagina2(page,id,criterio)" v-text="page"></a>
                                        </li>
                                        <li class="page-item" v-if="pagination2.current_page < pagination2.last_page">
                                            <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page + 1,id,criterio)">Sig</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                            
                        </div>
                    <!-- /.modal-content -->
                
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
                id :0,
                fraccionamiento_id:0,
                etapa_id : 0,
                lote_id : 0,
                lote_promocion_id : 0,
                nombre : '',
                v_ini : new Date().toISOString().substr(0, 10),
                v_fin : '',
                descuento : '',
                descripcion : '',
                arrayPromocion : [],
                arrayLotePromocion : [],
                arrayFraccionamientos: [],
                arrayEtapas: [],
                arrayManzanas: [],
                arrayLotes : [],
                modal : 0,
                mostrar : 0,
                manzana : '',
                tituloModal : '',
                modal2 : 0,
                tituloModal2 : '',
                tipoAccion: 0,
                errorPromocion : 0,
                errorLotePromocion : 0,
                errorMostrarMsjPromocion : [],
                errorMostrarMsjLotePromocion : [],
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
                criterio : 'promociones.nombre', 
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
            ////////////////////////////
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
            /**Metodo para mostrar los registros */
            listarPromociones(page, buscar, criterio){
                let me = this;
                var url = '/promocion?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPromocion = respuesta.promociones.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listarLotePromociones(page, promocion_id){
                let me = this;
                var url = '/lote_promocion?page=' + page + '&promocion_id=' + promocion_id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLotePromocion = respuesta.lotes_promocion.data;
                    me.pagination2 = respuesta.pagination;
                    if(me.arrayLotePromocion.length>0){
                        me.mostrar = 1;
                    }
                })
                .catch(function (error) {
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
            cambiarPagina(page, buscar, criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarPromociones(page,buscar,criterio);
            },
            cambiarPagina2(page, buscar){
                let me = this;
                //Actualiza la pagina actual
                me.pagination2.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarLotePromociones(page,buscar);
            },
            /**Metodo para registrar  */
            registrarPromociones(){
                if(this.validarPromociones()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/promocion/registrar',{
                    'fraccionamiento_id': this.fraccionamiento_id,
                    'etapa_id': this.etapa_id,
                    'nombre': this.nombre,
                    'v_ini': this.v_ini,
                    'v_fin': this.v_fin,
                    'descuento': this.descuento,
                    'descripcion': this.descripcion
                }).then(function (response){
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarPromociones(1,'','nombre'); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Promocion creada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            registrarLotePromocion(){
                if(this.validarLotePromociones()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/lote_promocion/registrar',{
                    'promocion_id': this.id,
                    'lote_id': this.lote_id
                }).then(function (response){
                    me.cerrarModal2(); //al guardar el registro se cierra el modal
                    me.listarPromociones(1,'','nombre'); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Promocion asignada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            actualizarPromociones(){
                if(this.validarPromociones()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                let me = this;
                //Con axios se llama el metodo update de DepartamentoController
                axios.put('/promocion/actualizar',{
                   'fraccionamiento_id': this.fraccionamiento_id,
                    'etapa_id': this.etapa_id,
                    'nombre': this.nombre,
                    'v_ini': this.v_ini,
                    'v_fin': this.v_fin,
                    'descuento': this.descuento,
                    'descripcion': this.descripcion,
                    'id': this.id
                }).then(function (response){
                    me.cerrarModal();
                    me.listarPromociones(1,'','nombre');
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
            eliminarPromocion(data =[]){
                this.id=data['id'];
                this.fraccionamiento_id=data['fraccionamiento_id'];
                this.etapa_id=data['etapa_id'];
                this.nombre=data['nombre'];
                this.v_ini=data['v_ini'];
                this.v_fin=data['v_fin'];
                this.descuento=data['descuento'];
                this.descripcion=data['descripcion'];
                //console.log(this.departamento_id);
                swal({
                title: '¿Desea eliminar?',
                text: "Esta acción no se puede revertir!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si, eliminar!'
                }).then((result) => {
                if (result.value) {
                    let me = this;

                axios.delete('/promocion/eliminar', 
                        {params: {'id': this.id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Paquete borrado correctamente.',
                        'success'
                        )
                        me.listarPromociones(1,'','nombre');
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            eliminarLotePromocion(data =[]){
                this.lote_promocion_id=data['id'];
                this.lote_id=data['lote_id'];
                this.promocion_id=data['promocion_id'];
                //console.log(this.departamento_id);
                swal({
                title: '¿Desea eliminar?',
                text: "Esta acción no se puede revertir!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si, eliminar!'
                }).then((result) => {
                if (result.value) {
                    let me = this;

                axios.delete('/lote_promocion/eliminar', 
                        {params: {'id': this.lote_promocion_id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Paquete borrado correctamente.',
                        'success'
                        )
                        me.listarLotePromociones(1,me.promocion_id);
                        if(me.arrayLotePromocion.length==0)
                            me.mostrar = 0;
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
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
            selectLotesManzana(buscar1, buscar2, buscar3){
                let me = this;

                me.arrayLotes=[];
                var url = '/select_lotes_manzana?buscar=' + buscar1 + '&buscar1='+ buscar2 + '&buscar2='+ buscar3;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLotes = respuesta.lote_manzana;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
             selectEtapa(buscar){
                let me = this;
                
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
            selectManzanas(buscar1, buscar2){
                let me = this;

                me.arrayManzanas=[];
                var url = '/select_manzanas_etapa?buscar=' + buscar1 + '&buscar1='+ buscar2;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayManzanas = respuesta.manzana;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            validarPromociones(){
                this.errorPromocion=0;
                this.errorMostrarMsjPromocion=[];

                if(!this.nombre) //Si la variable departamento esta vacia
                    this.errorMostrarMsjPromocion.push("El nombre de la promoción no puede ir vacio.");
                
                if(this.fraccionamiento_id==0) //Si la variable departamento esta vacia
                    this.errorMostrarMsjPromocion.push("Debe seleccionar algun fraccionamiento.");

                if(this.etapa_id==0) //Si la variable departamento esta vacia
                    this.errorMostrarMsjPromocion.push("Debe seleccionar la etapa.");

                if(this.errorMostrarMsjPromocion.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorPromocion = 1;

                return this.errorPromocion;
            },
            validarLotePromociones(){
                this.errorLotePromocion=0;
                this.errorMostrarMsjLotePromocion=[];

                if(!this.lote_id) //Si la variable departamento esta vacia
                    this.errorMostrarMsjLotePromocion.push("Selecciona el lote que tendra la promoción.");
                
                if(this.errorMostrarMsjLotePromocion.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorLotePromocion = 1;

                return this.errorLotePromocion;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.fraccionamiento_id = '';
                this.etapa_id = '';
                this.nombre = '';
                this.v_ini = new Date().toISOString().substr(0, 10);
                this.v_fin = '';
                this.descuento = '';
                this.descripcion = '';
                this.errorPromocion = 0;
                this.errorMostrarMsjPromocion = [];

            },
            cerrarModal2(){
                this.modal2 = 0;
                this.tituloModal2 = '';
                this.fraccionamiento_id = '';
                this.etapa_id = '';
                this.lote_id = '';
                this.lote_promocion_id = '';
                this.errorLotePromocion = 0;
                this.errorMostrarMsjLotePromocion = [];
                this.mostrar = 0;

            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(modelo, accion,data =[]){
                switch(modelo){
                    case "promocion":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Departamento';
                                this.fraccionamiento_id =0;
                                this.etapa_id =0;
                                this.nombre = '';
                                // this.v_ini ='';
                                this.v_fin = '';
                                this.descuento = 0;
                                this.descripcion = '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal =1;
                                this.tituloModal='Actualizar Departamento';
                                this.tipoAccion=2;
                                this.id=data['id'];
                                this.fraccionamiento_id=data['fraccionamiento_id'];
                                this.etapa_id=data['etapa_id'];
                                this.nombre=data['nombre'];
                                this.v_ini=data['v_ini'];
                                this.v_fin=data['v_fin'];
                                this.descuento=data['descuento'];
                                this.descripcion=data['descripcion'];
                                break;
                            }
                        }
                    }
                }

                this.selectFraccionamientos();
                this.selectEtapa(this.fraccionamiento_id);
            },
            abrirModal2(modelo, accion,data =[]){
                switch(modelo){
                    case "lote_promocion":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal2 = 1;
                                this.tituloModal2 = 'Asignar Promoción:  ' + data['nombre'];;
                                this.fraccionamiento_id=data['fraccionamiento_id'];
                                this.etapa_id=data['etapa_id'];
                                this.id=data['id'];
                                this.lote_id = 0;
                                this.manzana = '';
                                break;
                            }
                        }
                    }
                }

                this.selectFraccionamientos();
                this.selectEtapa(this.fraccionamiento_id);
                this.selectManzanas(this.fraccionamiento_id,this.etapa_id)
            }
        },
        mounted() {
            this.listarPromociones(1,this.buscar,this.criterio);
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
        position: absolute !important;
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
    
    .btn-success {
        color: #fff;
        background-color: #2c309e;
        border-color: #313a98;
    }
    .btn-success:active, .btn-success.active, .show > .btn-success.dropdown-toggle {
        background-color: #2c309e;
        background-image: none;
        border-color: #313a98;
    }
    .btn-success:focus, .btn-success.focus {
        box-shadow: 0 0 0 3px rgba(77, 100, 189, 0.5);
    }
    .btn-success:hover {
        color: #fff;
        background-color: #2c309e;
        border-color: #313a98;
    }
</style>
