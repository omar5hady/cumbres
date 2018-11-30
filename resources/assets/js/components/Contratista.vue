<template>
    <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Contratistas
                        <!--   Boton Nuevo    -->
                        <button type="button" @click="abrirModal('nombre','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-5" @click="limpiarBusqueda()" v-model="criterio">
                                      <option value="nombre">Contratista</option>
                                      <option value="rfc">RFC</option>
                                      <option value="estado">Estado</option>
                                      <option value="ciudad">Ciudad</option>
                                      <option value="tipo">Tipo</option>
                                    </select>&nbsp;&nbsp;&nbsp;
                                    <select class="form-control col-md-5"  v-if="criterio=='tipo'" v-model="buscar" @keyup.enter="listarContratista(1,buscar,criterio)" >
                                        <option value="0" >Persona Moral</option>
                                        <option value="1" >Persona Fisica</option>
                                    </select>
                                    
                                    <input  type="text" v-else v-model="buscar" @keyup.enter="listarContratista(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarContratista(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>RFC</th>
                                    <th>Direccion</th>
                                    <th>Colonia</th>
                                    <th>CP</th>
                                    <th>Estado</th>
                                    <th>Ciudad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="contratista in arrayContratista" :key="contratista.id">
                                    <td>
                                        <button type="button" @click="abrirModal('contratista','actualizar',contratista)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <button type="button" class="btn btn-danger btn-sm" @click="eliminarContratista(contratista)">
                                          <i class="icon-trash"></i>
                                        </button>
                                    </td>
                                    <td v-text="contratista.nombre"></td>
                                    <td v-if="contratista.tipo==1" v-text="'Persona Fisica'"></td>
                                    <td v-if="contratista.tipo==0" v-text="'Persona Moral'"></td>
                                    <td v-text="contratista.rfc"></td>
                                    <td v-text="contratista.direccion"></td>
                                    <td v-text="contratista.colonia"></td>
                                    <td v-text="contratista.cp"></td>
                                    <td v-text="contratista.estado"></td>
                                    <td v-text="contratista.ciudad"></td>
                                </tr>                               
                            </tbody>
                        </table>
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
                                    <label class="col-md-3 form-control-label" for="text-input">Contratista</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre del contratista">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Tipo de razon social</label>
                                    <div class="col-md-6">
                                        <select class="form-control" v-model="tipo">
                                            <option value="1">Persona Fisica</option>
                                            <option value="0">Persona Moral</option>
                                        </select>
                                    </div>
                                </div>
                                 <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">R.F.C</label>
                                    <div class="col-md-6">
                                        <input type="text" maxlength="13" style="text-transform:uppercase" v-model="rfc" class="form-control" placeholder="RFC">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Direccion</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="direccion" class="form-control" placeholder="Calle">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Codigo Postal</label>
                                    <div class="col-md-6">
                                        <input type="text" maxlength="5" v-model="cp" @keyup="selectColonias(cp)" class="form-control" placeholder="Codigo postal">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Colonia</label>
                                    <div class="col-md-6">
                                        <select class="form-control" v-model="colonia">
                                            <option value="0">Seleccione</option>
                                            <option v-for="colonias in arrayColonias" :key="colonias.colonia" :value="colonias.colonia" v-text="colonias.colonia"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Estado</label>
                                    <div class="col-md-6">
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
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Ciudad</label>
                                    <div class="col-md-6">
                                        <select class="form-control" v-model="ciudad">
                                            <option v-for="ciudades in arrayCiudades" :key="ciudades.municipio" :value="ciudades.municipio" v-text="ciudades.municipio"></option>
                                        </select>
                                        <!--<input type="text" v-model="ciudad" class="form-control" placeholder="Ciudad">-->
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Representante</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="representante" class="form-control" placeholder="Nombre del Representante">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">IMSS</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="IMSS" class="form-control" placeholder="IMSS">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Telefono</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="telefono" class="form-control" placeholder="Telefono de contacto">
                                    </div>
                                </div>
                            
                                <!-- Div para mostrar los errores que mande validerFraccionamiento -->
                                <div v-show="errorContratista" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjContratista" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarContratista()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarContratista()">Actualizar</button>
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
                id:0,
                nombre : '',
                representante : '',
                IMSS : '',
                telefono : '',
                tipo : 0,
                rfc : '',
                direccion : '',
                colonia : '',
                cp : 0,
                estado : '',
                ciudad : '',
                arrayContratista : [],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                errorContratista : 0,
                errorMostrarMsjContratista : [],
                pagination : {
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
            }
        },
        methods : {
            /**Metodo para mostrar los registros */
            listarContratista(page, buscar, criterio){
                let me = this;
                var url = '/contratista?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayContratista = respuesta.contratistas.data;
                    me.pagination = respuesta.pagination;
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
                me.listarContratista(page,buscar,criterio);
            },
            /**Metodo para registrar  */
            registrarContratista(){
                if(this.validarContratista()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/contratista/registrar',{
                    'nombre': this.nombre,
                    'tipo': this.tipo,
                    'rfc': this.rfc,
                    'direccion': this.direccion,
                    'colonia': this.colonia,
                    'cp': this.cp,
                    'estado': this.estado,
                    'ciudad': this.ciudad,
                    'representante': this.representante,
                    'IMSS': this.IMSS,
                    'telefono': this.telefono
                }).then(function (response){
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarContratista(1,'','nombre'); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Contratista agregado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            actualizarContratista(){
                if(this.validarContratista()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                let me = this;
                //Con axios se llama el metodo update de FraccionaminetoController
                axios.put('/contratista/actualizar',{
                   'nombre': this.nombre,
                    'tipo': this.tipo,
                    'rfc': this.rfc,
                    'direccion': this.direccion,
                    'colonia': this.colonia,
                    'cp': this.cp,
                    'estado': this.estado,
                    'ciudad': this.ciudad,
                    'id' : this.id,
                    'representante': this.representante,
                    'IMSS': this.IMSS,
                    'telefono': this.telefono
                }).then(function (response){
                    me.cerrarModal();
                    me.listarContratista(1,'','nombre');
                    //window.alert("Cambios guardados correctamente");
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Cambios guardados correctamente :)',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            eliminarContratista(data =[]){
                this.id=data['id'];
                this.nombre=data['nombre'];
                this.tipo=data['tipo'];
                this.rfc=data['rfc'];
                this.direccion=data['direccion'];
                this.colonia=data['colonia'];
                this.cp=data['cp'];
                this.estado=data['estado'];
                this.ciudad=data['ciudad'];
                this.representante=data['representante'];
                this.IMSS=data['IMSS'];
                this.telefono=data['telefono'];
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

                axios.delete('/contratista/eliminar', 
                        {params: {'id': this.id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Contratista eliminado correctamente.',
                        'success'
                        )
                        me.listarContratista(1,'','nombre');
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            validarContratista(){
                this.errorContratista=0;
                this.errorMostrarMsjContratista=[];

                if(!this.nombre) //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjContratista.push("El nombre de Contratista no puede ir vacio.");

                if(this.errorMostrarMsjContratista.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorContratista = 1;

                return this.errorContratista;
            },
            limpiarBusqueda(){
                let me=this;
                me.buscar= "";
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.nombre = '';
                this.tipo = 0;
                this.rfc = '';
                this.direcion = '';
                this.colonia = '';
                this.cp = '';
                this.estado = '';
                this.ciudad = '';
                this.errorContratista = 0;
                this.errorMostrarMsjContratista = [];

            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(modelo, accion,data =[]){
                switch(modelo){
                    case "contratista":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Contratista';
                                this.nombre = '';
                                this.tipo = 0;
                                this.rfc = '';
                                this.direccion = '';
                                this.colonia = '';
                                this.cp = '';
                                this.estado = '';
                                this.ciudad = '';
                                this.representante = '';
                                this.IMSS = '';
                                this.telefono = '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal =1;
                                this.tituloModal='Actualizar Contratista';
                                this.tipoAccion=2;
                                this.id=data['id'];
                                this.nombre=data['nombre'];
                                this.tipo=data['tipo'];
                                this.rfc=data['rfc'];
                                this.direccion=data['direccion'];
                                this.colonia=data['colonia'];
                                this.cp=data['cp'];
                                this.estado=data['estado'];
                                this.ciudad=data['ciudad'];
                                this.representante=data['representante'];
                                this.IMSS=data['IMSS'];
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
            this.listarContratista(1,this.buscar,this.criterio);
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
