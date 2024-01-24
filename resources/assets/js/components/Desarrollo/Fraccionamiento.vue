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
                        <a :href="'/fraccionamiento/excel?buscar=' + buscar + '&criterio=' + criterio"  class="btn btn-success"><i class="fa fa-file-text"></i> Excel </a>
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-10">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-4" v-model="criterio" @click="buscra=''">
                                      <option value="fraccionamientos.nombre">Fraccionamiento</option>
                                      <option value="fraccionamientos.tipo_proyecto">Tipo de Proyecto</option>
                                    </select>

                                    <select class="form-control col-md-6" v-if="criterio=='fraccionamientos.tipo_proyecto'" v-model="buscar" @keyup.enter="listarFraccionamiento(1,buscar,criterio)" >
                                        <option value="1">Lotificación</option>
                                        <option value="2">Departamento</option>
                                        <option value="3">Terreno</option>
                                    </select>
                                    <input type="text" v-else v-model="buscar" @keyup.enter="listarFraccionamiento(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">

                                </div>
                                <div class="input-group">
                                    <button type="submit" @click="listarFraccionamiento(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <TableComponent>
                            <template v-slot:thead>
                                <tr>
                                    <th></th>
                                    <th>Fraccionamiento</th>
                                    <th>Tipo de proyecto</th>
                                    <th>Direccion</th>
                                    <th>Colonia</th>
                                    <th>Estado</th>
                                    <th>Delegacion</th>
                                    <th v-if="rolId != 3">Fecha de inicio de ventas</th>
                                    <th v-if="rolId != 3">Gerente</th>
                                    <th>Arquitecto</th>
                                    <th>Postventa</th>
                                </tr>
                            </template>
                            <template v-slot:tbody>
                                <tr v-for="fraccionamiento in arrayFraccionamiento" :key="fraccionamiento.id">
                                    <td class="td2">
                                        <button type="button" @click="abrirModal('fraccionamiento','actualizar',fraccionamiento)" class="btn btn-warning btn-sm">
                                        <i class="icon-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" @click="eliminarFraccionamiento(fraccionamiento)">
                                        <i class="icon-trash"></i>
                                        </button>
                                        <button title="Planos y escrituras" type="button" @click="abrirModal('fraccionamiento','subirArchivo',fraccionamiento)" class="btn btn-default btn-sm">
                                        <i class="icon-cloud-upload"></i>
                                        </button>
                                    </td>
                                    <td class="td2" v-text="fraccionamiento.nombre"></td>
                                    <td class="td2" v-if="fraccionamiento.tipo_proyecto==1" v-text="'Lotificación'"></td>
                                    <td class="td2" v-if="fraccionamiento.tipo_proyecto==2" v-text="'Departamento'"></td>
                                    <td class="td2" v-if="fraccionamiento.tipo_proyecto==3" v-text="'Terreno'"></td>
                                    <td class="td2" v-text="fraccionamiento.calle + ' No. ' + fraccionamiento.numero"></td>
                                    <td class="td2" v-text="fraccionamiento.colonia"></td>
                                    <td class="td2" v-text="fraccionamiento.estado"></td>
                                    <td class="td2" v-text="fraccionamiento.delegacion"></td>
                                    <td class="td2" v-if="rolId != 3" v-text="fraccionamiento.fecha_ini_venta"></td>
                                    <td class="td2" v-if="rolId != 3" v-text="fraccionamiento.gerente"></td>
                                    <td class="td2" v-text="fraccionamiento.arquitecto"></td>
                                    <td class="td2" v-text="fraccionamiento.postventa"></td>
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
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <FraccionamientoModal v-if="modal==1"
                :rolId="rolId"
                :tipoAccion="tipoAccion"
                :datos="data"
                :titulo="tituloModal"
                :arrayGerentes ="arrayGerentes "
                :arrayArquitectos ="arrayArquitectos "
                :arrayPostVenta ="arrayPostVenta "
                @closeModal="cerrarModal"
            />

            <!--Fin del modal-->

            <!-- Modal para la carga de los archivos-->
            <ModalComponent v-if="modal == 2"
                :titulo="tituloModal"
                @closeModal="cerrarModal()"
            >
                <template v-slot:body>
                    <div class="col-md-6" style="float:left;">
                        <form  method="post" @submit="formSubmitPlanos" enctype="multipart/form-data">
                            <div class="form-group row">
                                <label class="col-md-12 form-control-label" for="text-input">
                                    <strong>Sube aqui los planos</strong>
                                </label>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <input type="text" v-if="plano_original != null" class="form-control" v-model="nombre_plano" placeholder="Versión del plano">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <input type="file" class="form-control" v-on:change="onImageChangePlanos">
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-success">Cargar Planos</button>
                                </div>
                            </div>
                        </form>
                        <br>
                        <TableComponent :cabecera="['','Versión']">
                            <template v-slot:tbody>
                                <tr v-if="plano_original != null">
                                    <td>
                                        <a title="Descargar planos" class="btn btn-success btn-sm" v-bind:href="'/downloadPlanos/'+plano_original">
                                            <i class="fa fa-map fa-md"></i>
                                        </a>
                                    </td>
                                    <td>Primera versión</td>
                                </tr>
                                <tr v-for="plano in arrayPlanos" :key="plano.id">
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm" @click="deletePlanos(plano)">
                                            <i class="icon-trash"></i>
                                        </button>
                                        <a title="Descargar planos" class="btn btn-success btn-sm" v-bind:href="'/downloadPlanos/'+plano.archivo">
                                            <i class="fa fa-map fa-md"></i>
                                        </a>
                                    </td>
                                    <td v-text="plano.version"></td>
                                </tr>
                            </template>
                        </TableComponent>
                    </div>

                    <div class="col-md-6" style="float:right;">
                        <form  method="post" @submit="formSubmitEscrituras" enctype="multipart/form-data">
                            <div class="form-group row">
                                <label class="col-md-12 form-control-label" for="text-input">
                                    <strong>Sube aqui las escrituras</strong>
                                </label>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <input type="text" v-if="escritura_original != null" class="form-control" v-model="nombre_escritura" placeholder="Versión de escritura">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <input type="file" class="form-control" v-on:change="onImageChangeEscrituras">
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-success">Cargar Escrituras</button>
                                </div>
                            </div>
                        </form>
                        <br>
                        <TableComponent :cabecera="['','Versión']">
                            <template v-slot:tbody>
                                <tr v-if="escritura_original != null">
                                        <td>
                                            <a  title="Descargar escrituras" class="btn btn-warning btn-sm" v-bind:href="'/downloadEscrituras/' + escritura_original">
                                                <i class="fa fa-file-archive-o fa-lg"></i>
                                            </a>
                                        </td>
                                        <td>Primera versión</td>
                                    </tr>
                                    <tr v-for="escritura in arrayEscrituras" :key="escritura.id">
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm" @click="deleteEscrituras(escritura)">
                                                <i class="icon-trash"></i>
                                            </button>
                                            <a  title="Descargar escrituras" class="btn btn-warning btn-sm" v-bind:href="'/downloadEscrituras/' + escritura.archivo">
                                                <i class="fa fa-file-archive-o fa-lg"></i>
                                            </a>
                                        </td>
                                        <td v-text="escritura.version"></td>
                                    </tr>
                            </template>
                        </TableComponent>
                    </div>
                </template>
            </ModalComponent>
            <!--Fin del modal-->
        </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
import ModalComponent from '../Componentes/ModalComponent.vue'
import TableComponent from '../Componentes/TableComponent.vue'
import FraccionamientoModal from './components/modales/FraccionamientoModal.vue';

    export default {
        components:{
            ModalComponent,
            TableComponent,
            FraccionamientoModal
        },
        props:{
            rolId:{type: String}
        },
        data(){
            return{
                proceso:false,
                id:0,
                fecha_ini_venta:'',
                archivo_planos: '',
                archivo_escrituras: '',
                plano_original : '',
                escritura_original : '',
                nombre_escritura :'',
                nombre_plano : '',
                arrayFraccionamiento : [],
                arrayGerentes : [],
                arrayArquitectos :[],
                arrayPostVenta :[],
                arrayEscrituras : [],
                arrayPlanos : [],
                modal : 0,
                tituloModal : '',

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
                criterio : 'fraccionamientos.nombre',
                buscar : '',
                arrayCiudades : [],

                data: {}
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
                formData.append('version', this.nombre_plano);
                formData.append('fraccionamiento_id', this.id);
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
                    me.cerrarModal();
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
                formData.append('version', this.nombre_escritura);
                formData.append('fraccionamiento_id', this.id);
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
                    me.cerrarModal();
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

            getArchivos(id){
                let me = this;
                me.arrayEscrituras = [];
                me.arrayPlanos = [];
                var url = '/fraccionamiento/getArchivos?id='+id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEscrituras = respuesta.escrituras;
                    me.arrayPlanos = respuesta.planos;
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

            selectPostventa(){
                let me = this;
                me.arrayPostVenta=[];
                var url = '/select_personal?departamento_id=4';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPostVenta = respuesta.personal;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectArquitectos(){
                let me = this;
                me.arrayArquitectos=[];
                var url = '/select_personal?departamento_id=3';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayArquitectos = respuesta.personal;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectGerentesVentas(){
                let me = this;
                me.arrayGerentes=[];
                var url = '/selectGerentesVentas';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayGerentes = respuesta.personas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },


            eliminarFraccionamiento(data =[]){
                this.id=data['id'];
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

            deleteEscrituras(data =[]){
                let folio = data['id'];
                let archivo = data['archivo'];
                swal({
                title: '¿Desea eliminar el archivo?',
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
                    axios.delete('/fraccionamiento/deleteEscrituras',
                        {params: {
                            'id': folio,
                            'archivo' : archivo
                            }
                        }).then(function (response){
                        swal(
                        'Borrado!',
                        'Archivo borrado correctamente.',
                        'success'
                        )
                        me.getArchivos(me.id);
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },

            deletePlanos(data =[]){
                let folio = data['id'];
                let archivo = data['archivo'];
                swal({
                title: '¿Desea eliminar el archivo?',
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
                    axios.delete('/fraccionamiento/deletePlanos',
                        {params: {
                            'id': folio,
                            'archivo' : archivo
                            }
                        }).then(function (response){
                        swal(
                        'Borrado!',
                        'Archivo borrado correctamente.',
                        'success'
                        )
                        me.getArchivos(me.id);
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },

            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.errorFraccionamiento = 0;
                this.errorMostrarMsjFraccionamiento = [];
                this.archivo_planos = '';
                this.archivo_escrituras = '';
                this.nombre_escritura = '';
                this.nombre_plano = '';
                this.data = {};
                this.listarFraccionamiento(1, this.buscar, this.criterio);

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
                                this.data.nombre ='';
                                this.data.tipo_proyecto =0;
                                this.data.calle ='';
                                this.data.colonia ='';
                                this.data.estado ='San Luis Potosí';
                                this.data.ciudad ='';
                                this.data.delegacion = '';
                                this.data.cp = 0;
                                this.data.numero = 0;
                                this.data.precio_m2_habitacional = 0;
                                this.data.precio_m2_comercial = 0;
                                this.data.precio_m2_reserva = 0
                                this.data.area_vendible_habitacional = 0;
                                this.data.area_vendible_comercial = 0;
                                this.data.area_vendible_reserva = 0;
                                this.tipoAccion = 1;
                                this.paso = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal =1;
                                this.tituloModal='Actualizar Fraccionamiento';
                                this.tipoAccion=2;
                                this.paso = 1;
                                this.data.id=data['id'];
                                this.data.nombre=data['nombre'];
                                this.data.tipo_proyecto=data['tipo_proyecto'];
                                this.data.calle=data['calle'];
                                this.data.colonia=data['colonia'];
                                this.data.estado=data['estado'];
                                this.data.ciudad=data['ciudad'];
                                this.data.delegacion=data['delegacion'];
                                this.data.cp=data['cp'];
                                this.data.gerente_id=data['gerente_id'];
                                this.data.arquitecto_id=data['arquitecto_id'];
                                if(this.data.arquitecto_id == null)
                                    this.data.arquitecto_id = '';
                                if(this.data.postventa_id == null)
                                    this.data.postventa_id = '';
                                this.data.numero = data['numero'];
                                this.data.nEquipamiento = {
                                    fraccionamiento_id : this.data.id,
                                    categoria : '',
                                    nombre : '',
                                    descripcion : '',
                                };
                                this.data.equipamientos = data['equipamiento_urbano'];

                                this.data.rest_ambientales = data['rest_ambientales'];
                                this.data.rest_federales = data['rest_federales'];
                                this.data.rest_otras = data['rest_otras'];

                                this.data.area_vendible_habitacional = data['area_vendible_habitacional'];
                                this.data.area_vendible_comercial = data['area_vendible_comercial'];
                                this.data.area_vendible_reserva = data['area_vendible_reserva'];

                                this.data.precio_m2_habitacional = data['precio_m2_habitacional'];
                                this.data.precio_m2_comercial = data['precio_m2_comercial'];
                                this.data.precio_m2_reserva = data['precio_m2_reserva'];
                                break;
                            }


                            case 'subirArchivo':
                            {
                                this.modal = 2;
                                this.tituloModal='Subir Archivos';
                                this.id=data['id'];
                                this.archivo_planos='';
                                this.archivo_escrituras='';
                                this.plano_original = data['archivo_planos'];
                                this.escritura_original=data['archivo_escrituras'];
                                this.getArchivos(this.id);
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
            this.selectGerentesVentas();
            this.selectArquitectos();
            this.selectPostventa();
        }
    }
</script>
<style>
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
