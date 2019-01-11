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
                        <i class="fa fa-align-justify"></i> Fraccionamientos
                        <!--   Boton Nuevo    -->
                        <button type="button" @click="abrirModal('fraccionamiento','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-5" v-model="criterio" @click="limpiarBusqueda()">
                                      <option value="nombre">Fraccionamiento</option>
                                      <option value="tipo_proyecto">Tipo de Proyecto</option>
                                    </select>
                                    
                                    
                                    <select class="form-control col-md-5" v-if="criterio=='tipo_proyecto'" v-model="buscar" @keyup.enter="listarFraccionamiento(1,buscar,criterio)" >
                                        <option value="1">Lotificación</option>
                                        <option value="2">Departamento</option>
                                        <option value="3">Terreno</option>
                                    </select>
                                    <input type="text" v-else v-model="buscar" @keyup.enter="listarFraccionamiento(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarFraccionamiento(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Fraccionamiento</th>
                                    <th>Tipo de proyecto</th>
                                    <th>Direccion</th>
                                    <th>Colonia</th>
                                    <th>Estado</th>
                                    <th>Delegacion</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="fraccionamiento in arrayFraccionamiento" :key="fraccionamiento.id">
                                    <td>
                                        <button type="button" @click="abrirModal('fraccionamiento','actualizar',fraccionamiento)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" @click="eliminarFraccionamiento(fraccionamiento)">
                                          <i class="icon-trash"></i>
                                        </button>
                                        <button title="Subir planos y escrituras" type="button" @click="abrirModal('fraccionamiento','subirArchivo',fraccionamiento)" class="btn btn-default btn-sm">
                                          <i class="icon-cloud-upload"></i>
                                        </button>
                                        <a  title="Descargar planos" v-if ="fraccionamiento.archivo_planos" class="btn btn-success btn-sm" v-bind:href="'/downloadPlanos/'+fraccionamiento.archivo_planos">
                                        <i class="fa fa-map fa-lg"></i>
                                        </a>
                                         <a  title="Descargar escrituras" v-if ="fraccionamiento.archivo_escrituras" class="btn btn-warning btn-sm" v-bind:href="'/downloadEscrituras/'+fraccionamiento.archivo_escrituras">
                                        <i class="fa fa-file-archive-o fa-lg"></i>
                                        </a>
                                    </td>
                                    <td v-text="fraccionamiento.nombre"></td>
                                    <td v-if="fraccionamiento.tipo_proyecto==1" v-text="'Lotificación'"></td>
                                    <td v-if="fraccionamiento.tipo_proyecto==2" v-text="'Departamento'"></td>
                                    <td v-if="fraccionamiento.tipo_proyecto==3" v-text="'Terreno'"></td>
                                    <td v-text="fraccionamiento.calle"></td>
                                    <td v-text="fraccionamiento.colonia"></td>
                                    <td v-text="fraccionamiento.estado"></td>
                                    <td v-text="fraccionamiento.delegacion"></td>
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
                                    <label class="col-md-3 form-control-label" for="text-input">Proyecto</label>
                                    <!--Criterios para el listado de busqueda -->
                                    <div class="col-md-3">
                                        <select class="form-control" v-model="tipo_proyecto">
                                            <option value="0">Seleccione</option>
                                            <option value="1">Lotificación</option>
                                            <option value="2">Departamento</option>
                                            <option value="3">Terreno</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fraccionamiento</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre del fraccionamiento">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Calle</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="calle" class="form-control" placeholder="Calle">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Colonia</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="colonia" class="form-control" placeholder="Colonia">
                                    </div>
                                </div>
                                <div class="form-group row">
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
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Ciudad</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="ciudad">
                                            <option v-for="ciudades in arrayCiudades" :key="ciudades.municipio" :value="ciudades.municipio" v-text="ciudades.municipio"></option>
                                        </select>
                                    </div>
                                </div>
                                 <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Delegacion</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="delegacion" class="form-control" placeholder="Delegacion">
                                    </div>
                                </div>
                                 <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Codigo postal</label>
                                    <div class="col-md-9">
                                        <input type="text" maxlength="5" v-model="cp" class="form-control" placeholder="Codigo postal">
                                    </div>
                                </div>
                                <!-- Div para mostrar los errores que mande validerFraccionamiento -->
                                <div v-show="errorFraccionamiento" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjFraccionamiento" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarFraccionamiento()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarFraccionamiento()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
            
            <!-- Modal para la carga de los archivos-->
<div class="modal fade" tabindex="-1" :class="{'mostrar': modal4}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal4"></h4>
                            <button type="button" class="close" @click="cerrarModal4()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                     <div style="float:left;">
                            <form  method="post" @submit="formSubmitPlanos" enctype="multipart/form-data">

                                    <strong>Sube aqui los planos del fraccionamiento</strong>

                                    <input type="file" class="form-control" v-on:change="onImageChangePlanos">
                                    <br/>
                                    <button type="submit" class="btn btn-success">Cargar</button>
                            </form>
                     </div>

                     <div style="float:right;">
                             <form  method="post" @submit="formSubmitEscrituras" enctype="multipart/form-data">

                                    <strong>Sube aqui los archivos de escrituras del fraccionamiento</strong>

                                    <input type="file" class="form-control" v-on:change="onImageChangeEscrituras">
                                    <br/>
                                    <button type="submit" class="btn btn-success">Cargar</button>
                            </form>
                     </div>

                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal4()">Cerrar</button>
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
                tipo_proyecto : 0,
                calle : '',
                colonia : '',
                estado : 'San Luis Potosí',
                ciudad : '',
                delegacion: '',
                cp: 0,
                archivo_planos: '',
                archivo_escrituras: '',
                arrayFraccionamiento : [],
                modal : 0,
                modal4: 0,
                tituloModal : '',
                tituloModal4: '',
                tipoAccion: 0,
                errorFraccionamiento : 0,
                errorMostrarMsjFraccionamiento : [],
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
                arrayCiudades : []
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

            //funciones para carga de los planos del fraccionamiento 

            onImageChangePlanos(e){
                console.log(e.target.files[0]);
                this.archivo_planos = e.target.files[0];
            },

            formSubmitPlanos(e) {
                e.preventDefault();
                let currentObj = this;
            
                let formData = new FormData();
                formData.append('archivo_planos', this.archivo_planos);
                let me = this;
                axios.post('/formSubmitPlanos/'+this.id, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Archivo guardado correctamente',
                        showConfirmButton: false,
                        timer: 2000
                        })
                    me.cerrarModal4();
                   me.listarFraccionamiento(1,'','fraccionamiento');

                }).catch(function (error) {
                    currentObj.output = error;

                });

            },

//funciones para carga de las escrituras de los fraccionamientos

            onImageChangeEscrituras(e){

                console.log(e.target.files[0]);

                this.archivo_escrituras = e.target.files[0];

            },

            formSubmitEscrituras(e) {

                e.preventDefault();

                let currentObj = this;
            
                let formData = new FormData();
           
                formData.append('archivo_escrituras', this.archivo_escrituras);
                let me = this;
                axios.post('/formSubmitEscrituras/'+this.id, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Archivo guardado correctamente',
                        showConfirmButton: false,
                        timer: 2000
                        })
                    me.cerrarModal4();
                   me.listarFraccionamiento(1,'','fraccionamiento');

                }).catch(function (error) {
                    currentObj.output = error;
                });

            },




            /**Metodo para mostrar los registros */
            listarFraccionamiento(page, buscar, criterio){
                let me = this;
                var url = '/fraccionamiento?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayFraccionamiento = respuesta.fraccionamientos.data;
                    me.pagination = respuesta.pagination;
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
                me.listarFraccionamiento(page,buscar,criterio);
            },
            /**Metodo para registrar  */
            registrarFraccionamiento(){
                if(this.validarFraccionamiento()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/fraccionamiento/registrar',{
                    'nombre': this.nombre,
                    'tipo_proyecto': this.tipo_proyecto,
                    'calle': this.calle,
                    'colonia': this.colonia,
                    'estado': this.estado,
                    'ciudad': this.ciudad,
                    'delegacion' : this.delegacion,
                    'cp' : this.cp
                }).then(function (response){
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarFraccionamiento(1,'','fraccionamiento'); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Fraccionamiento agregado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            actualizarFraccionamiento(){
                if(this.validarFraccionamiento()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                let me = this;
                //Con axios se llama el metodo update de FraccionaminetoController
                axios.put('/fraccionamiento/actualizar',{
                    'nombre': this.nombre,
                    'tipo_proyecto': this.tipo_proyecto,
                    'calle': this.calle,
                    'colonia': this.colonia,
                    'estado': this.estado,
                    'ciudad': this.ciudad,
                    'delegacion' : this.delegacion,
                    'cp' : this.cp,
                    'id' : this.id
                }).then(function (response){
                    me.cerrarModal();
                    me.listarFraccionamiento(1,'','fraccionamiento');
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
            eliminarFraccionamiento(data =[]){
                this.id=data['id'];
                this.nombre=data['nombre'];
                this.tipo_proyecto=data['tipo_proyecto'];
                this.calle=data['calle'];
                this.colonia=data['colonia'];
                this.estado=data['estado'];
                this.ciudad=data['ciudad'];
                this.delegacion=data['delegacion'];
                this.cp=data['cp'];
                //console.log(this.fraccionamiento_id);
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

                axios.delete('/fraccionamiento/eliminar', 
                        {params: {'id': this.id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Fraccionamiento borrado correctamente.',
                        'success'
                        )
                        me.listarFraccionamiento(1,'','fraccionamiento');
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
             limpiarBusqueda(){
                let me=this;
                me.buscar= "";
            },
            validarFraccionamiento(){
                this.errorFraccionamiento=0;
                this.errorMostrarMsjFraccionamiento=[];

                if(!this.nombre) //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjFraccionamiento.push("El nombre del Fraccionamiento no puede ir vacio.");

                if(this.errorMostrarMsjFraccionamiento.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorFraccionamiento = 1;

                return this.errorFraccionamiento;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.nombre = '';
                this.tipo_proyecto = 0;
                this.calle = '';
                this.colonia = '';
                this.estado = '';
                this.ciudad = '';
                this.delegacion = '';
                this.cp = 0;
                this.user_alta = '';
                this.errorFraccionamiento = 0;
                this.errorMostrarMsjFraccionamiento = [];

            },
            cerrarModal4(){
                this.modal4 = 0;
                this.tituloModal4 = '';
                this.archivo_planos = '';
                this.archivo_escrituras = '';
                this.errorFraccionamiento = 0;
                this.errorMostrarMsjFraccionamiento = [];

            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(modelo, accion,data =[]){
                switch(modelo){
                    case "fraccionamiento":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Fraccionamiento';
                                this.nombre ='';
                                this.tipo_proyecto =0;
                                this.calle ='';
                                this.colonia ='';
                                this.estado ='San Luis Potosí';
                                this.ciudad ='';
                                this.delegacion = '';
                                this.cp = 0;
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal =1;
                                this.tituloModal='Actualizar Fraccionamiento';
                                this.tipoAccion=2;
                                this.id=data['id'];
                                this.nombre=data['nombre'];
                                this.tipo_proyecto=data['tipo_proyecto'];
                                this.calle=data['calle'];
                                this.colonia=data['colonia'];
                                this.estado=data['estado'];
                                this.ciudad=data['ciudad'];
                                this.delegacion=data['delegacion'];
                                this.cp=data['cp'];
                                break;
                            }

                            
                            case 'subirArchivo':
                            {
                                this.modal4 =1;
                                this.tituloModal4='Subir Archivos';
                                this.id=data['id'];
                                this.archivo_planos=data['archivo_planos'];
                                this.archivo_escrituras=data['archivo_escrituras'];
                                break;
                            }
                        }
                    }
                }
                //this.selectCiudades(this.estado);
            }
        },
        mounted() {
            this.listarFraccionamiento(1,this.buscar,this.criterio);
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
    .scroll-box {
            overflow-x: scroll;
            width: auto;
            padding: 1rem
        }
</style>
