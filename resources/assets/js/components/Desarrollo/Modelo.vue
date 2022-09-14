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
                        <i class="fa fa-align-justify"></i> Modelos
                        <!--   Boton Nuevo    -->
                        <button v-if="rolId != '7'" type="button" @click="abrirModal('modelo','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-10">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-4" v-model="criterio" @click="limpiarBusqueda()">
                                      <option value="modelos.nombre">Modelos</option>
                                      <option value="tipo">Tipo de Proyecto</option>
                                      <option value="fraccionamientos.nombre">Proyecto</option>
                                    </select>

                                    <select class="form-control" v-if="criterio=='tipo'" v-model="buscar" @keyup.enter="listarModelo(1,buscar,criterio)" >
                                        <option value="1">Lotificación</option>
                                        <option value="2">Departamento</option>
                                        <option value="3">Terreno</option>
                                    </select>
                                    <input type="text" v-else v-model="buscar" @keyup.enter="listarModelo(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                </div>
                                <div class="input-group">
                                    <button type="submit" @click="listarModelo(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <TableComponent :cabecera="['Opciones','Tipo','Proyecto','Modelo',
                            'Terreno mts&sup2;','Construcción mts&sup2;','Especificaciónes',
                        ]">
                            <template v-slot:tbody>
                                <tr v-for="modelo in arrayModelo" :key="modelo.id">
                                    <td class="td2" style="width:12%">
                                        <button v-if="rolId != '7'" title="Editar" type="button" @click="abrirModal('modelo','actualizar',modelo)" class="btn btn-warning btn-sm">
                                        <i class="icon-pencil"></i>
                                        </button>
                                        <button v-if="rolId != '7'" title="Borrar" type="button" class="btn btn-danger btn-sm" @click="eliminarModelo(modelo)">
                                        <i class="icon-trash"></i>
                                        </button>
                                        <button title="Subir catalogo de especificaciones" type="button" @click="abrirModal('modelo','subirArchivo',modelo)" class="btn btn-default btn-sm">
                                        <i class="icon-cloud-upload"></i>
                                        </button>
                                    </td>
                                    <td class="td2" v-if="modelo.tipo==1" v-text="'Lotificación'"></td>
                                    <td class="td2" v-if="modelo.tipo==2" v-text="'Departamento'"></td>
                                    <td class="td2" v-if="modelo.tipo==3" v-text="'Terreno'"></td>
                                    <td class="td2" v-text="modelo.fraccionamiento"></td>
                                    <td class="td2" v-text="modelo.nombre"></td>
                                    <td class="td2" v-text="modelo.terreno"></td>
                                    <td class="td2" v-text="modelo.construccion"></td>
                                    <td class="td2">
                                        <button v-if="modelo.especificaciones.length"
                                            title="Ver especificaciones" type="button"
                                            @click="abrirModal('modelo','especificaciones',modelo.especificaciones)" class="btn btn-default btn-sm">
                                            <i class="fa fa-cogs"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </TableComponent>
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
                        <button class="btn btn-sm btn-default" @click="modal2=2">Manual</button>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <ModalComponent :titulo="tituloModal"
                @closeModal="cerrarModal()"
                v-if="modal"
            >
                <template v-slot:body>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                        <div class="col-md-9">
                            <input type="text" v-model="nombre" class="form-control" placeholder="Nombre del modelo">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Tipo</label>
                        <div class="col-md-3">
                            <select class="form-control" v-model="tipo" @change="selectFraccionamientos(tipo)">
                                <option value="0">Seleccione</option>
                                <option value="1">Fraccionamiento</option>
                                <option value="2">Departamento</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Proyecto</label>
                        <div class="col-md-6">
                            <select class="form-control" v-model="fraccionamiento_id">
                                <option value="0">Seleccione</option>
                                <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Terreno (mts&sup2;)</label>
                        <div class="col-md-4">
                            <input type="text" v-model="terreno" class="form-control" placeholder="Terreno">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Construcción (mts&sup2;)</label>
                        <div class="col-md-7">
                            <input type="text" v-model="construccion" class="form-control" placeholder="Construccion">
                        </div>
                    </div>
                    <!-- Div para mostrar los errores que mande validerModelo -->
                    <div v-show="errorModelo" class="form-group row div-error">
                        <div class="text-center text-error">
                            <div v-for="error in errorMostrarMsjModelo" :key="error" v-text="error">

                            </div>
                        </div>
                    </div>
                </template>
                <template v-slot:buttons-footer>
                    <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarModelo()">Guardar</button>
                    <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarModelo()">Actualizar</button>
                </template>
            </ModalComponent>
            <!--Fin del modal-->
            <!--Inicio del modal Especificaciones-->
            <ModalComponent :titulo="tituloModal"
                @closeModal="cerrarModal()"
                v-if="modal2 == 3"
            >
                <template v-slot:body>
                    <div class="form-group row">
                        <div  v-for="especificacion in especificaciones" :key="especificacion.general"
                            class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">{{especificacion.general}}</h5>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item pointer" v-for="det in especificacion.detalle" :key="det.id"
                                            @click="verDescripcion(det)"
                                        >
                                            {{det.subconcepto}}
                                        </li>
                                    </ul>
                                    <a href="#"
                                        class="btn "></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </ModalComponent>
            <!-- modal para la carga de archivos -->
            <ModalComponent
                :titulo="tituloModal"

                v-if="modal2 == 1"
                @closeModal="cerrarModal2()"
            >
                <template v-slot:body>
                     <select class=" form-control " @click="limpiaInputArchivos()" v-model="formActive">
                            <option class=" form-control " value="">Seleccione archivo a subir.</option>
                            <option class=" form-control " value="excel">Importar desde excel.</option>
                            <option class=" form-control " value="especifi">Catalogo de especificaciones. </option>
                            <option class=" form-control " @click="getVersiones(id)" value="new_especifi">Añadir nueva version especificaciones. </option>
                            <option class=" form-control " value="obra_especifi">Archivo especificaciones obra. </option>
                        </select>

                        <div class="content-main">
                            <template v-if="formActive">
                                <div class="contenedor-modal">
                                    <div class="form-sub">
                                        <form  method="post" @submit="formSubmit" enctype="multipart/form-data">
                                            <div class="form-opc">
                                                <label v-show="nom_archivo!='Seleccione Archivo' && formActive =='new_especifi'" class="col-md-1 form-control-label" for="text-input"><strong>Version:</strong></label>
                                                <input v-show="nom_archivo!='Seleccione Archivo' && formActive =='new_especifi'" type="text" v-model="version" class="form-control" placeholder="Version del archivo">
                                                <div class="form-archivo">
                                                    <input v-if="formActive != 'excel'" ref="imageSelectorArchivo" v-show="false" type="file"  v-on:change="onImageChange">
                                                    <input v-else ref="imageSelectorArchivo" v-show="false" type="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                                         v-on:change="onImageChange">
                                                    <label class="label-button" @click="onSelectArchivo">
                                                        Sube aqui el archivo {{(formActive == 'excel' ? 'Excel' : 'PDF')}}<br>
                                                        <i class="fa fa-upload"></i>
                                                    </label>
                                                    <div v-if="nom_archivo=='Seleccione Archivo'" class="text-file-hide"  v-text="nom_archivo" ></div>
                                                    <div v-else class="text-file"  v-text="nom_archivo"></div>
                                                </div>
                                                <div class="boton-modal">
                                                    <button v-show="nom_archivo!='Seleccione Archivo'" type="submit"  class="btn btn-success boton-modal">Subir Archivo</button>
                                                </div>
                                            </div>

                                        </form>

                                    </div>

                                </div>
                                <div class="form-table" v-if="arrayVersiones.length && formActive == 'new_especifi'">

                                    <TableComponent :cabecera="['','Versión']">
                                        <template v-slot:tbody>
                                            <tr v-for="version in arrayVersiones" :key="version.id">
                                                <td style="width:12%">
                                                    <button @click="eliminarVersion(version.id)" type="button" class="btn btn-danger btn-sm" title="Quitar archivo">
                                                        <i class="icon-close"></i>
                                                    </button>
                                                </td>
                                                <td>
                                                    <a target="_blank" v-bind:href="'/downloadModelo/'+ version.archivo"> {{version.version}}</a>
                                                </td>
                                            </tr>
                                        </template>
                                    </TableComponent>

                                </div>

                            </template>
                        </div>

                         <div v-show="errorVersion" class="form-group row div-error">
                            <div class="text-center text-error">
                                <div v-text="errorMsjVersion"></div>
                            </div>
                        </div>
                </template>
                <template v-slot:buttons-footer>
                    <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarModelo()">Actualizar</button>
                </template>
            </ModalComponent>
            <!--Fin del modal-->
            <!-- Manual -->
            <ModalComponent v-if="modal2 == 2"
                :titulo="'Manual'"
                @closeModal="cerrarModal2()">
                <template v-slot:body>
                    <p>
                        Para agregar un nuevo modelo solo debe de dar clic sobre el botón agregar y llenar los campos
                        que se solicitan en la ventana emergente.
                    </p>
                    <p>
                        También podrá agregar archivos de especificaciones y en caso de que un modelo cuente con mas
                        de un archivo de especificación el sistema le permitirá agregar mas de uno,
                        posteriormente podrá asignar un archivo de especificación de modelo a cada lote dentro del módulo
                        “Desarrollo -> Especificaciones de Modelo”.
                    </p>
                    <p>
                        En caso de que desee puede descargar las especificaciones cargadas desde el icono de descarga
                        que se encuentra de lado derecho.
                    </p>
                </template>
            </ModalComponent>
        </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
import ModalComponent from '../Componentes/ModalComponent.vue'
import TableComponent from '../Componentes/TableComponent.vue'
    export default {
        components:{
            ModalComponent,
            TableComponent
        },
         props:{
            rolId:{type: String}
        },

        data(){
            return{
                proceso : false,
                id:0,
                nombre : '',
                tipo : 0,
                fraccionamiento_id : 0,
                terreno : 0,
                construccion : 0.0,

                nuevo:0,
                version:'',

                archivoOrg:'',

                archivo: '',
                archivoEspeObra: '',
                archivoOtro:'',
            ///// variables form Archivo ////
                nom_archivo:'Seleccione Archivo',
                formActive:'',
                archivo:'',
                errorMsjVersion:'',
                errorVersion:'',

            /////     //////
                success: '',
                arrayModelo : [],
                modal : 0,
                modal2 : 0,
                tituloModal : '',
                tipoAccion: 0,
                errorModelo : 0,
                errorMostrarMsjModelo : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'modelos.nombre',
                buscar : '',
                arrayCiudades : [],
                arrayFraccionamientos : [],
                arrayVersiones : [],
                especificaciones:{}
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
            // Metodos para los archivos
            onImageChange(e){
                this.archivo = e.target.files[0];
                this.nom_archivo = e.target.files[0].name;
            },
              onSelectArchivo(){
                 this.$refs.imageSelectorArchivo.click()
                },

            formSubmit(e) {
                e.preventDefault();
                let currentObj = this;
                let url='';
                let nombre='archivo';

                let formData = new FormData();
                 if (this.formActive == 'especifi' ) {
                     url ='/formSubmitModelo/'+ this.id
                }
                if (this.formActive == 'new_especifi' ) {
                    if(this.validarVersion()){
                        return
                    }
                     url ='/modelos/archivos/formSubmit/'+ this.id + '/' + this.version
                }
                if (this.formActive == 'obra_especifi' ) {
                     nombre ='archivoObra'
                     url ='/formSubmitModelo/especificaciones/obra/'+ this.id
                }

                if (this.formActive == 'excel'){
                    url ='/specification'
                    formData.append( 'modelo_id' , this.id);
                }



                formData.append( nombre , this.archivo);
                let me = this;
                axios.post( url , formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Archivo importado correctamente',
                        showConfirmButton: false,
                        timer: 2000
                        })
                    //me.cerrarModal2();
                    me.limpiaInputArchivos()
                    me.listarModelo(me.pagination.current_page,'','modelos.nombre');


                    if (me.formActive == 'new_especifi') {
                        me.getVersiones(me.id)
                    }

                })
                .catch(function (error) {
                    currentObj.output = error;
                });

            },

            verDescripcion(detalle){
                Swal.fire(
                    detalle.subconcepto,
                    detalle.descripcion
                )
            },

            /**Metodo para mostrar los registros */
            listarModelo(page, buscar, criterio){

                let me = this;
                var url = '/modelo?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayModelo = respuesta.modelos.data;
                    me.pagination = respuesta.pagination;
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
                me.listarModelo(page,buscar,criterio);
            },
            selectFraccionamientos(buscar){
                let me = this;
                me.arrayFraccionamientos=[];
                var url = '/select_fraccionamiento?tipo_proyecto='+buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayFraccionamientos = respuesta.fraccionamientos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            eliminarVersion(id){
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

                axios.delete('/modelos/archivos/delete',
                        {params: {'id': id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Archivo borrado correctamente.',
                        'success'
                        )
                        me.getVersiones(me.id);
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            getVersiones(id){
                let me = this;
                me.nuevo = 1;
                me.arrayVersiones=[];
                var url = '/modelos/archivos/versiones?modelo='+id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayVersiones = respuesta.versiones;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            /**Metodo para registrar  */
            registrarModelo(){
                if(this.validarModelo() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                    return;

                this.proceso=true;

                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/modelo/registrar',{
                    'nombre': this.nombre,
                    'tipo': this.tipo,
                    'fraccionamiento_id': this.fraccionamiento_id,
                    'terreno': this.terreno,
                    'construccion': this.construccion,
                    'archivo': this.archivo
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarModelo(1,'','modelo'); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Modelo agregado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
             limpiarBusqueda(){
                let me=this;
                me.buscar= "";
            },
            actualizarModelo(){
                if(this.validarModelo() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                    return;

                this.proceso=true;

                let me = this;
                //Con axios se llama el metodo update de FraccionaminetoController
                axios.put('/modelo/actualizar',{
                    'nombre': this.nombre,
                    'tipo': this.tipo,
                    'fraccionamiento_id': this.fraccionamiento_id,
                    'terreno': this.terreno,
                    'construccion': this.construccion,
                    'archivo': this.archivo,
                    'id' : this.id
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal();
                    me.listarModelo(me.pagination.current_page,'','modelos.nombre');
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
            eliminarModelo(data =[]){
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

                axios.delete('/modelo/eliminar',
                        {params: {'id': this.id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Modelo borrado correctamente.',
                        'success'
                        )
                        me.listarModelo(me.pagination.current_page,'','modelos.nombre');
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            validarModelo(){
                this.errorModelo=0;
                this.errorMostrarMsjModelo=[];

                if(!this.nombre) //Si la variable Modelo esta vacia
                    this.errorMostrarMsjModelo.push("El nombre del Modelo no puede ir vacio.");

                if(this.errorMostrarMsjModelo.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorModelo = 1;

                return this.errorModelo;
            },
            validarVersion(){
                this.errorVersion=false;
                this.errorMsjVersion='';

                if(!this.version) { //Si la variable version esta vacia
                     this.errorMsjVersion="El campo version no puede ir vacio";
                    this.errorVersion = true;

                    }

                return this.errorVersion;
            },
            limpiaInputArchivos(){
                this.archivo='';
                this.nom_archivo='Seleccione Archivo';
                //this.formActive='';
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.nombre = '';
                this.tipo = 0;
                this.fraccionamiento_id = 0;
                this.terreno = 0;
                this.construccion = 0;
                this.archivo = '';
                this.especificaciones = {}
                this.modal2=0;

                this.errorModelo = 0;
                this.errorMostrarMsjModelo = [];

            },
              cerrarModal2(){
                this.modal2 = 0;
                this.tituloModal = '';
                this.nombre = '';
                this.archivo = '';
                this.errorModelo = 0;
                this.archivoOtro = '';
                this.archivoEspeObra = '';
                this.version = '';
                this.errorMostrarMsjModelo = [];
                this.nom_archivo='Seleccione Archivo';
                this.formActive='';

            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(modelo, accion,data =[]){
                switch(modelo){
                    case "modelo":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Modelo';
                                this.nombre = '';
                                this.tipo = 0;
                                this.fraccionamiento_id = 0;
                                this.terreno = 0;
                                this.construccion = 0;
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal =1;
                                this.tituloModal='Actualizar Modelo';
                                this.tipoAccion=2;
                                this.id=data['id'];
                                this.nombre=data['nombre'];
                                this.tipo=data['tipo'];
                                this.fraccionamiento_id=data['fraccionamiento_id'];
                                this.terreno=data['terreno'];
                                this.construccion=data['construccion'];
                                break;
                            }
                            case 'subirArchivo':
                            {
                                this.modal2 =1;
                                this.tituloModal='Subir Archivo para modelo ' + data['nombre'];
                                this.tipoAccion=3;
                                this.id=data['id'];
                                this.nombre=data['nombre'];
                                this.archivo=data['archivo'];
                                this.version='';
                                this.nuevo = 0;
                                this.archivoOrg = data['archivo'];
                                break;
                            }
                            case 'especificaciones':{
                                this.modal2 = 3
                                this.tituloModal = 'Especificaciones asignadas'
                                this.especificaciones = data;
                                break;
                            }
                        }
                    }
                }
                this.selectFraccionamientos(this.tipo);
            }
        },
        mounted() {
            this.listarModelo(1,this.buscar,this.criterio);
        }
    }
</script>
<style scoped>
    .text-formfile{

        color: grey;
        display:flex;
        padding-top: 13px;
        justify-content: left;

    }
    .content-main{
        display: flex;
        flex-direction: row;
        align-content: left;
        align-items: flex-start;
        justify-content: left;
    }
    .contenedor-modal{
        /* margin: auto; */
        overflow-x: auto;
         width: fit-content;
        max-width: 100%;

    }

      .form-table{

            margin-top: 20px;
            margin-left: 20px;
            width: 50%;

        }
        .form-sub{

            border: 1px solid #c2cfd6;
            margin-top: 20px;
            width: 100%;

        }
        .form-opc{
            display: flex;
            flex-direction: column;


        }
    .tite-form{
        background-color: lightgray;
        color: black;
        font-size: 14px;
        padding-bottom: 15px;
        margin-bottom: 15px;

    }

    .label-button{
    border-style: solid;
    cursor:pointer;
    color: #fff;
    background-color: #00ADEF;
    border-color: #00ADEF;
    padding: 10px;
    margin-left: 15px;
    }

    .label-button:hover {
    color: #fff;
    background-color: #1b8eb7;
    border-color: #00b0bb;;

      }
    .form-archivo{
        margin-top: 15px;
        display: flex;
        flex-direction: row;

        width: 100%;
    }
    .text-file{

        color: rgb(39, 38, 38);
        font-size:12px;
        word-break: break-all;
        font-weight: bold;
        width: 300px;
        padding: 15px;
    }
    .text-file-hide{

        color: rgb(127, 130, 134);
        font-size:13px;
        word-break: break-all;
        font-weight: bold;
        width: 300px;
        padding: 15px;
    }
    .boton-modal{
        margin-top: 15px;
        display: flex;
        flex-direction: row;
        justify-content: center;
    }
    .pointer{
        cursor: pointer;
    }
    .pointer:hover{
        color: #fff;
        background-color: #1b8eb7;
        border-color: #00b0bb;;
    }
    .div-error{
        display:flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
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
