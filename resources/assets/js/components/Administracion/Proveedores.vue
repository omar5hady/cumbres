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
                        <i class="fa fa-align-justify"></i> Proveedores
                        <!--   Boton Nuevo    -->
                        <button type="button" @click="abrirModal('proveedor','registrar')" class="btn btn-secondary">
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
                                        <option value="proveedor">Proveedor</option>
                                        <option value="contacto">Contacto</option>
                                    </select>
                                    
                                    <input type="text" v-model="buscar" @keyup.enter="listarProveedores(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarProveedores(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Proveedor</th>
                                        <th>Contacto</th>
                                        <th>Direccion</th>
                                        <th>Colonia</th>
                                        <th>Telefono</th>
                                        <th>Email</th>
                                        <th>Poliza de Garantia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="proveedor in arrayProveedores" :key="proveedor.id">
                                        <td style="width:10%">
                                            <button type="button" @click="abrirModal('proveedor','actualizar',proveedor)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-success2 btn-sm" @click="abrirModal2('equipamiento','registrar',proveedor), listarEquipamiento(1, proveedor.id)" title="Asignar equipamiento">
                                            <i class="icon-share"></i>
                                            </button>
                                        </td>
                                        <td v-text="proveedor.proveedor" ></td>
                                        <td v-text="proveedor.contacto" ></td>
                                        <td v-text="proveedor.direccion" ></td>
                                        <td v-text="proveedor.colonia" ></td>
                                        <td v-text="proveedor.telefono" ></td>
                                        <td v-text="proveedor.email" ></td>
                                        <td v-text="proveedor.poliza" ></td>
                                        
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
                                    <label class="col-md-3 form-control-label" for="text-input">Proveedor</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="proveedor" class="form-control" placeholder="Proveedor">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Contacto</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="contacto" class="form-control" placeholder="Contacto">
                                    </div>
                                </div>

                                <div class="form-group row" v-if="tipoAccion==1">
                                    <label class="col-md-3 form-control-label" for="text-input">RFC</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="rfc" maxlength="13" class="form-control" placeholder="RFC">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Direccion</label>
                                    <div class="col-md-8">
                                        <input type="text" v-model="direccion" class="form-control" placeholder="Dirección">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Colonia</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="colonia" class="form-control" placeholder="Colonia">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Telefono</label>
                                    <div class="col-md-4">
                                        <input type="text" maxlength="10" pattern="\d*" v-model="telefono" class="form-control" placeholder="Telefono" v-on:keypress="isNumber($event)">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Email</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="email" class="form-control" placeholder="Correo electronico">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Email (Opcional)</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="email2" class="form-control" placeholder="Correo electronico">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Tipo</label>
                                    <div class="col-md-6">
                                        <select class="form-control col-md-4" v-model="tipo">
                                            <option value="0">Interno</option>
                                            <option value="1">Externo</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row" v-if="tipoAccion==1">
                                    <label class="col-md-3 form-control-label" for="text-input">Usuario</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="usuario" class="form-control" placeholder="User">
                                    </div>
                                </div>

                                <div class="form-group row" v-if="tipoAccion==1">
                                    <label class="col-md-3 form-control-label" for="text-input">Contraseña</label>
                                    <div class="col-md-6">
                                        <input type="password" v-model="password" class="form-control" placeholder="Password">
                                    </div>
                                </div>

                                <!-- Div para mostrar los errores que mande validerPaquete -->
                                <div v-show="errorProveedor" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjProveedor" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarProveedores()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarProveedores()">Actualizar</button>
                        </div>
                        
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->


            <!--Inicio del modal asignar equipamiento-->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal2}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
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
                                    <label class="col-md-3 form-control-label" for="text-input">Equipamiento</label>
                                    <div class="col-md-6">
                                       <input type="text" name="city3" list="cityname3" class="form-control" v-model="equipamiento">
                                        <datalist id="cityname3">
                                            <option value="">Seleccione</option>
                                            <option value="Espejos de baño">Espejos de baño</option>
                                            <option value="Closets">Closets</option>
                                            <option value="Vestidores">Vestidores</option>
                                            <option value="Muebles">Muebles</option>
                                        </datalist>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Recepcion de documentos</label>
                                    <div class="col-md-6">
                                        <select class="form-control col-md-4" v-model="tipoRecepcion">
                                            <option value="0">General</option>
                                            <option value="1">Recepción de cocina</option>
                                            <option value="2">Recepción de vestidores</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Div para mostrar los errores que mande validerPaquete -->
                                <div v-show="errorEquipamiento" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjEquipamiento" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal2()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" class="btn btn-primary" @click="registrarEquipamiento()">Guardar</button>

                            </div>
                                <div class="modal-header" v-if="mostrar == 1">
                                    <h5 class="modal-title"> Equipamiento</h5>
                                </div>
                                <table class="table table-bordered table-striped table-sm" v-if="mostrar == 1">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Equipamiento</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="equipamiento in arrayEquipamientos" :key="equipamiento.id">
                                            <td style="width:25%">
                                                <button type="button" v-if="equipamiento.activo == 1" class="btn btn-success2 btn-sm" @click="eliminarEquipamiento(equipamiento)">
                                                <i class="fa fa-check"></i>
                                                </button>
                                                <button type="button" v-if="equipamiento.activo == 0" class="btn btn-danger btn-sm" @click="activarEquipamiento(equipamiento)">
                                                <i class="fa fa-remove"></i>
                                                </button>
                                            </td>
                                            <td v-text="equipamiento.equipamiento" ></td>
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
                proceso:false,
                id :0,
                proveedor : '',
                contacto : '',
                direccion : '',
                colonia : '',
                telefono : '',
                email : '',
                email2 : '',
                tipo: 0,
                usuario : '',
                password : '',
                rfc: '',
                equipamiento : '',
                tipoRecepcion : 0,
                equipamientoId: 0,
               
                arrayProveedores : [],
                arrayEquipamientos : [],
               
                modal : 0,
                mostrar : 0,
                tituloModal : '',
                modal2 : 0,
                tituloModal2 : '',
                tipoAccion: 0,
                errorProveedor : 0,
                errorEquipamiento : 0,
                errorMostrarMsjProveedor : [],
                errorMostrarMsjEquipamiento : [],
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
                criterio : 'proveedor', 
                buscar : '',
                buscar2 : '',
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
            listarProveedores(page, buscar, criterio){
                let me = this;
                var url = '/proveedor?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayProveedores = respuesta.proveedores.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listarEquipamiento(page, proveedor_id){
                let me = this;
                me.equipamiento = '';
                var url = '/equipamiento?page=' + page + '&proveedor_id=' + proveedor_id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEquipamientos = respuesta.equipamientos.data;
                    me.pagination2 = respuesta.pagination;
                    if(me.arrayEquipamientos.length>0){
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
            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
            cambiarPagina(page, buscar, criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarProveedores(page,buscar,criterio);
            },
            cambiarPagina2(page, buscar){
                let me = this;
                //Actualiza la pagina actual
                me.pagination2.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarEquipamiento(page,buscar);
            },
            /**Metodo para registrar  */
            registrarProveedores(){
                if(this.validarProveedor() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;
                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/proveedor/registrar',{
                    'proveedor': this.proveedor,
                    'contacto': this.contacto,
                    'direccion': this.direccion,
                    'colonia': this.colonia,
                    'telefono': this.telefono,
                    'email': this.email,
                    'usuario': this.usuario,
                    'password': this.password,
                    'rfc': this.rfc,
                    'email2': this.email2,
                    'tipo' : this.tipo
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Proveedor agregado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            registrarEquipamiento(){
                if(this.validarEquipamiento() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }
                this.proceso=true;
                let me = this;
                //Con axios se llama el metodo update de DepartamentoController

               Swal({
                    title: 'Estas seguro?',
                    animation: false,
                    customClass: 'animated bounceInDown',
                    text: "El equipamiento se asignara a",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    
                    confirmButtonText: 'Si, asignar!'
                    }).then((result) => {

                    if (result.value) {
                        axios.post('/equipamiento/registrar',{
                            'proveedor_id': this.id,
                            'equipamiento': this.equipamiento
                            }); 
                   // me.listarLote(1,'','','','','','','lote');   
                    me.proceso=false;
                    me.listarEquipamiento(1,me.id);
                        if(me.arrayEquipamientos.length>0)
                            me.mostrar = 1;
                    Swal({
                        title: 'Hecho!',
                        text: 'Equipamiento asignada correctamente',
                        type: 'success',
                        animation: false,
                        customClass: 'animated bounceInRight'
                        })
                    }})
            },
            
            actualizarProveedores(){
                if(this.validarProveedor() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;

                let me = this;
                //Con axios se llama el metodo update de DepartamentoController
                axios.put('/proveedor/actualizar',{
                    'proveedor': this.proveedor,
                    'contacto': this.contacto,
                    'direccion': this.direccion,
                    'colonia': this.colonia,
                    'telefono': this.telefono,
                    'email': this.email,
                    'email2': this.email2,
                    'tipo' : this.tipo,
                    'id': this.id
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal();
                    me.listarProveedores(me.pagination.current_page,me.buscar,me.criterio);
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
            eliminarEquipamiento(data =[]){
                this.equipamientoId=data['id'];
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

                axios.put('/equipamiento/eliminar', 
                         {'id': this.equipamientoId}).then(function (response){
                        swal(
                        'Borrado!',
                        'Equipamiento borrado correctamente.',
                        'success'
                        )
                        me.listarEquipamiento(1,me.id);
                        if(me.arrayEquipamientos.length==0)
                            me.mostrar = 0;
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            activarEquipamiento(data =[]){
                this.equipamientoId=data['id'];
                //console.log(this.departamento_id);
                swal({
                title: '¿Desea activar?',
                text: "Esta acción no se puede revertir!",
                type: 'success',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si, activar!'
                }).then((result) => {
                if (result.value) {
                    let me = this;

                axios.put('/equipamiento/activar', 
                         {'id': this.equipamientoId}).then(function (response){
                        swal(
                        'Activado!',
                        'Equipamiento activado correctamente.',
                        'success'
                        )
                        me.listarEquipamiento(1,me.id);
                        if(me.arrayEquipamientos.length==0)
                            me.mostrar = 0;
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            validarProveedor(){
                this.errorProveedor=0;
                this.errorMostrarMsjProveedor=[];

                if(this.proveedor=='') //Si la variable departamento esta vacia
                    this.errorMostrarMsjProveedor.push("El nombre del proveedor no puede ir vacio.");

                if(this.errorMostrarMsjProveedor.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorProveedor = 1;

                return this.errorProveedor;
            },
            validarEquipamiento(){
                this.errorEquipamiento=0;
                this.errorMostrarMsjEquipamiento=[];

                if(this.equipamiento =='') //Si la variable departamento esta vacia
                    this.errorMostrarMsjEquipamiento.push("Selecciona el equipamiento");
                
                if(this.errorMostrarMsjEquipamiento.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorEquipamiento = 1;

                return this.errorEquipamiento;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.proveedor ='';
                this.contacto = '';
                this.telefono = '';
                this.direccion = '';
                this.colonia = '';
                this.email = '';
                this.email2 = '';
                this.errorProveedor = 0;
                this.errorMostrarMsjProveedor = [];
                this.lotes_promo = [];
                this.listarProveedores(this.pagination.current_page,this.buscar,this.criterio);

            },
            cerrarModal2(){
                this.modal2 = 0;
                this.tituloModal2 = '';
                this.equipamiento = '';
                this.errorEquipamiento = 0;
                this.errorMostrarMsjEquipamiento = [];
                this.mostrar = 0;
                this.arrayLotes = [];
                this.listarProveedores(this.pagination.current_page,this.buscar,this.criterio);

            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(modelo, accion,data =[]){
                switch(modelo){
                    case "proveedor":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar proveedor';
                                this.proveedor = '';
                                this.contacto = '';
                                this.telefono = '';
                                this.direccion = '';
                                this.colonia = '';
                                this.email = '';
                                this.email2 = '';
                                this.tipoAccion = 1;
                                this.usuario ='';
                                this.password ='';
                                this.rfc='';
                                this.tipo = 0;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal =1;
                                this.tituloModal='Actualizar proveedor';
                                this.tipoAccion=2;
                                this.id=data['id'];
                                this.proveedor = data['proveedor'];
                                this.contacto = data['contacto'];
                                this.telefono = data['telefono'];
                                this.direccion = data['direccion'];
                                this.colonia = data['colonia'];
                                this.email = data['email'];
                                this.email2 = data['email2'];
                                this.tipo = data['tipo'];
                                break;
                            }
                        }
                    }
                }

            },
            abrirModal2(modelo, accion,data =[]){
                switch(modelo){
                    case "equipamiento":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal2 = 1;
                                this.tituloModal2 = 'Asignar equipamiento a: ' + data['proveedor'];
                                this.equipamiento = '';
                                this.id = data['id'];
                                break;
                            }
                        }
                    }
                }

            }
        },
        mounted() {
            this.listarProveedores(1,this.buscar,this.criterio);
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
    .btn-success2 {
        color: #fff;
        background-color: #2c309e;
        border-color: #313a98;
    }
    .btn-success2:active, .btn-success2.active, .show > .btn-success2.dropdown-toggle {
        background-color: #2c309e;
        background-image: none;
        border-color: #313a98;
    }
    .btn-success2:focus, .btn-success2.focus {
        box-shadow: 0 0 0 3px rgba(77, 100, 189, 0.5);
    }
    .btn-success2:hover {
        color: #fff;
        background-color: #2c309e;
        border-color: #313a98;
    }
</style>
