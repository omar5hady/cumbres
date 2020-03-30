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
                        <i class="fa fa-align-justify" v-if="verificadora == 0"></i> 
                        <button type="button" v-if="verificadora == 0" @click="verificadora = 1" class="btn btn-primary">
                            Empresas
                        </button>
                        <button type="button" v-if="verificadora == 1" @click="verificadora = 0" class="btn btn-success">
                            Empresas verificadoras
                        </button>
                        <!--   Boton Nuevo    -->
                        <button type="button" v-if="verificadora == 0" @click="abrirModal('empresa','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nueva Empresa
                        </button>
                        <!---->
                        <!--   Boton Nuevo    -->
                        <button type="button" v-if="verificadora == 1" @click="abrirModal('empresaVerif','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nueva Empresa Verificadora
                        </button>
                        <!---->
                    </div>
                    <div class="card-body" v-if="verificadora == 0">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-5" v-model="criterio">
                                      <option value="nombre">Empresa</option>
                                    </select>
                                    
                                    <input type="text" v-model="buscar" @keyup.enter="listarEmpresa(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarEmpresa(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Empresa</th>
                                        <th>Direccion</th>
                                        <th>Colonia</th>
                                        <th>CP</th>
                                        <th>Telefono</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="empresa in arrayEmpresa" :key="empresa.id">
                                        <td>
                                            <button type="button" @click="abrirModal('empresa','actualizar',empresa)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                            </button> &nbsp;
                                            <button type="button" class="btn btn-danger btn-sm" @click="eliminarEmpresa(empresa)">
                                            <i class="icon-trash"></i>
                                            </button>
                                        </td>
                                        <td v-text="empresa.nombre"></td>
                                        <td v-text="empresa.direccion"></td>
                                        <td v-text="empresa.colonia"></td>
                                        <td v-text="empresa.cp"></td>
                                        <td v-text="empresa.telefono"></td>
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

                    <div class="card-body" v-if="verificadora == 1">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" v-model="buscar" @keyup.enter="listarEmpresaVerificadora(1)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarEmpresaVerificadora(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Empresa</th>
                                        <th>Contacto</th>
                                        <th>Telefono</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="empresa in arrayEmpresaVerificadora" :key="empresa.id">
                                        <td>
                                            <button type="button" @click="abrirModal('empresaVerif','actualizar',empresa)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                            </button> &nbsp;
                                            <button type="button" class="btn btn-danger btn-sm" @click="eliminarEmpresaVerif(empresa)">
                                            <i class="icon-trash"></i>
                                            </button>
                                        </td>
                                        <td v-text="empresa.empresa"></td>
                                        <td v-text="empresa.contacto"></td>
                                        <td v-text="empresa.telefono"></td>
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
                                    <label class="col-md-3 form-control-label" for="text-input">Empresa</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de la empresa">
                                    </div>
                                </div>
                                <div class="form-group row" v-if="tipoAccion < 3">
                                    <label class="col-md-3 form-control-label" for="text-input">Direccion</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="direccion" class="form-control" placeholder="Calle">
                                    </div>
                                </div>
                                <div class="form-group row" v-if="tipoAccion > 2">
                                    <label class="col-md-3 form-control-label" for="text-input">Contacto</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="contacto" class="form-control" placeholder="Calle">
                                    </div>
                                </div>
                                <div class="form-group row" v-if="tipoAccion < 3">
                                    <label class="col-md-3 form-control-label" for="text-input">Codigo Postal</label>
                                    <div class="col-md-6">
                                        <input type="text" maxlength="5" v-model="cp" @keyup="selectColonias(cp)" class="form-control" placeholder="Codigo postal">
                                    </div>
                                </div>
                                <div class="form-group row" v-if="tipoAccion < 3">
                                    <label class="col-md-3 form-control-label" for="text-input">Colonia</label>
                                    <div class="col-md-6">
                                        <select class="form-control" v-model="colonia">
                                            <option value="0">Seleccione</option>
                                            <option v-for="colonias in arrayColonias" :key="colonias.colonia" :value="colonias.colonia" v-text="colonias.colonia"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" v-if="tipoAccion < 3">
                                    <label class="col-md-3 form-control-label" for="text-input">Estado</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="estado" @click="selectCiudades(estado)">
                                            <option value="San Luis Potosí">San Luis Potosí</option>
                                            <option value="Baja California">Baja California</option>
                                            <option value="Baja California Sur">Baja California Sur</option>
                                            <option value="Coahuila de Zaragoza">Coahuila de Zaragoza</option>
                                            <option value="Colima">Colima</option>
                                            <option value="Chiapas">Chiapas</option>
                                            <option value="Chihuahua">Chihuahua</option>
                                            <option value="Ciudad de México">Ciudad de México</option>
                                            <option value="Durango">Durango</option>
                                            <option value="Guanajuato">Guanajuato</option>
                                            <option value="Guerrero">Guerrero</option>
                                            <option value="Hidalgo">Hidalgo</option>
                                            <option value="Jalisco">Jalisco</option>
                                            <option value="México">México</option>
                                            <option value="Michoacán de Ocampo">Michoacán de Ocampo</option>
                                            <option value="Morelos">Morelos</option>
                                            <option value="Nayarit">Nayarit</option>
                                            <option value="Nuevo León">Nuevo León</option>
                                            <option value="Oaxaca">Oaxaca</option>
                                            <option value="Puebla">Puebla</option>
                                            <option value="Querétaro">Querétaro</option>
                                            <option value="Quintana Roo">Quintana Roo</option>
                                            <option value="Sinaloa">Sinaloa</option>
                                            <option value="Sonora">Sonora</option>
                                            <option value="Tabasco">Tabasco</option>
                                            <option value="Tamaulipas">Tamaulipas</option>
                                            <option value="Tlaxcala">Tlaxcala</option>
                                            <option value="Veracruz de Ignacio de la Llave">Veracruz de Ignacio de la Llave</option>
                                            <option value="Yucatán">Yucatán</option>
                                            <option value="Zacatecas">Zacatecas</option>
                                        </select>
                                        <!--<input type="text" v-model="estado" class="form-control" placeholder="Estado">-->
                                    </div>
                                </div>
                                <div class="form-group row" v-if="tipoAccion < 3">
                                    <label class="col-md-3 form-control-label" for="text-input">Ciudad</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="ciudad">
                                            <option v-for="ciudades in arrayCiudades" :key="ciudades.municipio" :value="ciudades.municipio" v-text="ciudades.municipio"></option>
                                        </select>
                                        <!--<input type="text" v-model="ciudad" class="form-control" placeholder="Ciudad">-->
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Telefono</label>
                                    <div class="col-md-5">
                                        <input type="text" maxlength="10" v-model="telefono" class="form-control" placeholder="Telefono">
                                    </div>
                                </div>
                                <div class="form-group row" v-if="tipoAccion < 3">
                                    <label class="col-md-3 form-control-label" for="text-input">Extension</label>
                                    <div class="col-md-3">
                                        <input type="text" maxlength="5" v-model="ext" class="form-control" placeholder="Ext">
                                    </div>
                                </div>
                                <!-- Div para mostrar los errores que mande validerFraccionamiento -->
                                <div v-show="errorEmpresa" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjEmpresa" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarEmpresa()">Guardar</button>
                            <button type="button" v-if="tipoAccion==3" class="btn btn-primary" @click="registrarEmpresaVerificadora()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarEmpresa()">Actualizar</button>
                            <button type="button" v-if="tipoAccion==4" class="btn btn-primary" @click="actualizarEmpresaVerif()">Actualizar</button>
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
                verificadora:0,
                id:0,
                nombre : '',
                direccion : '',
                cp : '',
                colonia : '',
                estado : '',
                ciudad : '',
                telefono : '',
                ext : '',
                arrayEmpresa : [],
                arrayEmpresaVerificadora : [],
                modal : 0,
                tituloModal : '',
                contacto: '',
                tipoAccion: 0,
                errorEmpresa : 0,
                errorMostrarMsjEmpresa : [],
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
                criterio : 'nombre', 
                buscar : '',
                arrayCiudades : [],
                arrayColonias : []
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
            /**Metodo para mostrar los registros */
            listarEmpresa(page, buscar, criterio){
                let me = this;
                var url = '/empresa?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEmpresa = respuesta.empresas.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listarEmpresaVerificadora(page){
                let me = this;
                var url = '/empresa/indexVerificadoras?page=' + page + '&buscar=' + me.buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEmpresaVerificadora = respuesta.empresas.data;
                    me.pagination2 = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
             selectColonias(buscar){
                let me = this;
                me.arrayColonias=[];
                var url = '/select_colonias?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayColonias = respuesta.colonias;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectCiudades(buscar){
                let me = this;
                me.arrayCiudades=[];
                var url = '/select_ciudades?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayCiudades = respuesta.ciudades;
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
                me.listarEmpresa(page,buscar,criterio);
            },
            cambiarPagina2(page){
                let me = this;
                //Actualiza la pagina actual
                me.pagination2.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarEmpresaVerificadora(page);
            },
            /**Metodo para registrar  */
            registrarEmpresa(){
                if(this.proceso==true){
                    return;
                }
                if(this.validarEmpresa()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }
                
                this.proceso=true;
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/empresa/registrar',{
                    'nombre': this.nombre,
                    'direccion': this.direccion,
                    'cp': this.cp,
                    'colonia': this.colonia,
                    'estado': this.estado,
                    'ciudad': this.ciudad,
                    'telefono': this.telefono,
                    'ext': this.ext
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarEmpresa(1,'','empresa'); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Empresa agregada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            registrarEmpresaVerificadora(){
                if(this.proceso==true){
                    return;
                }
                if(this.validarEmpresa()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }
                
                this.proceso=true;
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/empresa/storeVerificadora',{
                    'nombre': this.nombre,
                    'contacto': this.contacto,
                    'telefono': this.telefono
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarEmpresaVerificadora(1,); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Empresa agregada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            actualizarEmpresa(){
                if(this.proceso==true){
                    return;
                }
                if(this.validarEmpresa()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;
                let me = this;
                //Con axios se llama el metodo update de FraccionaminetoController
                axios.put('/empresa/actualizar',{
                    'nombre': this.nombre,
                    'direccion': this.direccion,
                    'cp': this.cp,
                    'colonia': this.colonia,
                    'estado': this.estado,
                    'ciudad': this.ciudad,
                    'telefono': this.telefono,
                    'ext': this.ext,
                    'id' : this.id
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal();
                    me.listarEmpresa(1,'','empresa');
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
            actualizarEmpresaVerif(){
                if(this.proceso==true){
                    return;
                }
                if(this.validarEmpresa()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;
                let me = this;
                //Con axios se llama el metodo update de FraccionaminetoController
                axios.put('/empresa/updateVerificadora',{
                    'nombre': this.nombre,
                    'contacto': this.contacto,
                    'telefono': this.telefono,
                    'id' : this.id
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal();
                    me.listarEmpresaVerificadora(1);
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
            eliminarEmpresa(data =[]){
                this.id=data['id'];
                this.nombre=data['nombre'];
                this.direccion=data['direccion'];
                this.cp=data['cp'];
                this.colonia=data['colonia'];
                this.estado=data['estado'];
                this.ciudad=data['ciudad'];
                this.telefono=data['telefono'];
                this.ext=data['ext']
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

                axios.delete('/empresa/eliminar', 
                        {params: {'id': this.id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Empresa borrada correctamente.',
                        'success'
                        )
                        me.listarEmpresa(1,'','empresa');
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            eliminarEmpresaVerif(data =[]){
                this.id=data['id'];
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

                axios.delete('/empresa/destroyVerificadora', 
                        {params: {'id': this.id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Empresa borrada correctamente.',
                        'success'
                        )
                        me.listarEmpresaVerificadora(1);
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            validarEmpresa(){
                this.errorEmpresa=0;
                this.errorMostrarMsjEmpresa=[];

                if(!this.nombre) //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjEmpresa.push("El nombre de la empresa no puede ir vacio.");

                if(this.errorMostrarMsjEmpresa.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorEmpresa = 1;

                return this.errorEmpresa;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.nombre = '';
                this.direcion = 0;
                this.cp = '';
                this.colonia = '';
                this.estado = '';
                this.ciudad = '';
                this.telefono = '';
                this.contacto = '';
                this.ext = '';
                this.errorEmpresa = 0;
                this.errorMostrarMsjEmpresa = [];

            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(modelo, accion,data =[]){
                switch(modelo){
                    case "empresa":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Empresa';
                                this.nombre ='';
                                this.direccion ='';
                                this.cp ='';
                                this.colonia ='';
                                this.estado ='San Luis Potosí';
                                this.ciudad ='';
                                this.telefono = '';
                                this.ext = '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal =1;
                                this.tituloModal='Actualizar Empresa';
                                this.tipoAccion=2;
                                this.id=data['id'];
                                this.nombre=data['nombre'];
                                this.direccion=data['direccion'];
                                this.cp=data['cp'];
                                this.colonia=data['colonia'];
                                this.estado=data['estado'];
                                this.ciudad=data['ciudad'];
                                this.telefono=data['telefono'];
                                this.ext=data['ext'];
                                break;
                            }
                        }
                    }
                    case "empresaVerif":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Empresa Verificadora';
                                this.nombre ='';
                                this.contacto ='';
                                this.telefono = '';
                                this.tipoAccion = 3;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal =1;
                                this.tituloModal='Actualizar Empresa Verificadora';
                                this.tipoAccion=4;
                                this.id=data['id'];
                                this.nombre=data['empresa'];
                                this.contacto=data['contacto'];
                                this.telefono=data['telefono'];
                                break;
                            }
                        }
                    }
                }
                this.selectColonias(this.cp);
                this.selectCiudades(this.estado);
            }
        },
        mounted() {
            this.listarEmpresa(1,this.buscar,this.criterio);
            this.listarEmpresaVerificadora(1);
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
