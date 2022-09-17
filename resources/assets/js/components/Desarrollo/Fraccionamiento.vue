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
            <ModalComponent v-if="modal"
                :titulo="tituloModal"
                @closeModal="cerrarModal()"
            >
                <template v-slot:body>
                    <ul class="nav nav-tabs" v-if="tipoAccion == 2">
                        <li class="nav-item"><a class="nav-link"  v-bind:class="{ 'active': paso==1 }" @click="paso = 1">Generales</a></li>
                        <li class="nav-item"><a class="nav-link"  v-bind:class="{ 'active': paso==2 }" @click="paso = 2">Equipamiento Urbano</a></li>
                    </ul>
                    <br>
                    <template v-if="paso == 1">
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
                            <div class="col-md-4">
                                <input type="text" v-model="calle" class="form-control" placeholder="Calle">
                            </div>
                            <label class="col-md-2 form-control-label" for="text-input">Num. </label>
                            <div class="col-md-3">
                                <input type="number" min="0" v-model="numero" class="form-control" placeholder="Numero">
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
                                <select class="form-control" v-model="estado" @change="selectCiudades(estado)">
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
                        <div class="form-group row" v-if="rolId != 3">
                            <label class="col-md-3 form-control-label" for="text-input">Fecha de inicio de ventas</label>
                            <div class="col-md-6">
                                <input type="date" v-model="fecha_ini_venta" class="form-control" placeholder="Fecha de terminacion">
                            </div>
                        </div>
                        <div class="form-group row" v-if="rolId != 3 && tipoAccion == 2" >
                            <label class="col-md-3 form-control-label" for="text-input">Gerente del proyecto</label>
                            <!--Criterios para el listado de busqueda -->
                            <div class="col-md-5">
                                <select class="form-control" v-model="gerente_id">
                                    <option value="">Seleccione</option>
                                    <option v-for="gerente in arrayGerentes" :key="gerente.id" :value="gerente.id" v-text="gerente.nombre + ' ' + gerente.apellidos"></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row" v-if="rolId != 3 && tipoAccion == 2" >
                            <label class="col-md-3 form-control-label" for="text-input">Arquitecto del proyecto</label>
                            <!--Criterios para el listado de busqueda -->
                            <div class="col-md-5">
                                <select class="form-control" v-model="arquitecto_id">
                                    <option value="">Seleccione</option>
                                    <option v-for="arquitectos in arrayArquitectos" :key="arquitectos.id" :value="arquitectos.id" v-text="'Arq. ' + arquitectos.name"></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row" v-if="rolId != 3 && tipoAccion == 2" >
                            <label class="col-md-3 form-control-label" for="text-input">Encargado de postventa</label>
                            <!--Criterios para el listado de busqueda -->
                            <div class="col-md-5">
                                <select class="form-control" v-model="postventa_id">
                                    <option value="">Seleccione</option>
                                    <option v-for="postventa in arrayPostVenta" :key="postventa.id" :value="postventa.id" v-text="postventa.name"></option>
                                </select>
                            </div>
                        </div>
                        <!-- Div para mostrar los errores que mande validerFraccionamiento -->
                        <div v-show="errorFraccionamiento" class="form-group row div-error">
                            <div class="text-center text-error">
                                <div v-for="error in errorMostrarMsjFraccionamiento" :key="error" v-text="error">

                                </div>
                            </div>
                        </div>
                    </template>
                    <template v-if="paso == 2">
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Categoría</label>
                            <!--Criterios para el listado de busqueda -->
                            <div class="col-md-4">
                                <input type="text" name="categoria" list="categoria" class="form-control" v-model="nEquipamiento.categoria" placeholder="Categoria">
                                <datalist id="categoria">
                                    <option value="">Seleccione</option>
                                    <option value="Parques">Parques</option>
                                    <option value="Estancias">Estancias</option>
                                    <option value="Escuelas">Escuelas</option>
                                    <option value="Hospitales">Hospitales</option>
                                    <option value="Transportes">Transportes</option>
                                </datalist>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" v-if="nEquipamiento.categoria != 'Transportes'" for="text-input">Lugar</label>
                            <label class="col-md-3 form-control-label" v-else for="text-input">Tipo de transporte</label>
                            <!--Criterios para el listado de busqueda -->
                            <div class="col-md-5">
                                <input type="text" class="form-control"  v-if="nEquipamiento.categoria != 'Transportes'" v-model="nEquipamiento.nombre" placeholder="Nombre del lugar">
                                <input type="text" class="form-control" v-else v-model="nEquipamiento.nombre" placeholder="Publico/Privado">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Descripción</label>
                            <!--Criterios para el listado de busqueda -->
                            <div class="col-md-6">
                                <textarea v-model="nEquipamiento.descripcion" cols="40" rows="3" class="form-control" placeholder="Descripción"></textarea>
                            </div>
                            <div class="col-md-3" v-if="nEquipamiento.categoria != '' && nEquipamiento.nombre != ''">
                                <button title="Agregar nuevo" type="button" @click="addEquipment(nEquipamiento)" class="btn btn-success btn-sm">
                                    <i class="icon-check"></i>&nbsp;Añadir Equipamiento
                                </button>
                            </div>
                        </div>
                        <hr>
                        <TableComponent v-if="equipamientos.length" :cabecera="['',
                            'Categoria','Nombre','Descripción'
                        ]">
                            <template v-slot:tbody>
                                <tr v-for="eq in equipamientos" :key="eq.id">
                                    <td class="td2">
                                        <button type="button" class="btn btn-danger btn-sm" @click="eliminarEquipamiento(eq)">
                                            <i class="icon-trash"></i>
                                        </button>
                                        <button title="Guardar cambios" type="button" @click="actualizarEq(eq)" class="btn btn-success btn-sm">
                                            <i class="icon-check"></i>
                                        </button>
                                    </td>
                                    <td class="td2">
                                        <input type="text" class="form-control" v-model="eq.categoria">
                                    </td>
                                    <td class="td2">
                                        <input type="text" class="form-control" v-model="eq.nombre">
                                    </td>
                                    <td class="td2">
                                        <textarea class="form-control" v-model="eq.descripcion"></textarea>
                                    </td>
                                </tr>
                            </template>
                        </TableComponent>
                    </template>
                </template>
                <template v-slot:buttons-footer>
                    <template v-if="paso == 1">
                        <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarFraccionamiento()">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarFraccionamiento()">Actualizar</button>
                    </template>
                </template>
            </ModalComponent>
            <!--Fin del modal-->

            <!-- Modal para la carga de los archivos-->
            <ModalComponent v-if="modal4"
                :titulo="tituloModal4"
                @closeModal="cerrarModal4()"
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
                proceso:false,
                id:0,
                nombre : '',
                tipo_proyecto : 0,
                calle : '',
                colonia : '',
                estado : 'San Luis Potosí',
                ciudad : '',
                delegacion: '',
                cp: 0,
                numero : 0,
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
                modal4: 0,
                tituloModal : '',
                tituloModal4: '',
                gerente_id : '',
                arquitecto_id :'',
                postventa_id:'',
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
                criterio : 'fraccionamientos.nombre',
                buscar : '',
                arrayCiudades : [],
                equipamientos: [],
                nEquipamiento: {},
                paso: 1
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
                if(this.proceso==true)
                    return;
                if(this.validarFraccionamiento()) //Se verifica si hay un error (campo vacio)
                    return;

                this.proceso=true;
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
                    'numero' : this.numero,
                    'cp' : this.cp
                }).then(function (response){
                    me.proceso=false;
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
            actualizarEq(equipamiento){
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.put('/urban-equipment/'+equipamiento.id,{
                    'id' : equipamiento.id,
                    'fraccionamiento_id': equipamiento.fraccionamiento_id,
                    'categoria': equipamiento.categoria,
                    'nombre': equipamiento.nombre,
                    'descripcion': equipamiento.descripcion,
                }).then(function (response){
                    me.listarFraccionamiento(me.pagination.current_page,me.buscar,'fraccionamiento');
                    //Se muestra mensaje Success
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Equipamiento actualizado'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            addEquipment(equipamiento){
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/urban-equipment',{
                    'fraccionamiento_id': equipamiento.fraccionamiento_id,
                    'categoria': equipamiento.categoria,
                    'nombre': equipamiento.nombre,
                    'descripcion': equipamiento.descripcion,
                }).then(function (response){
                    me.equipamientos.push(
                       response.data
                    )
                    me.nEquipamiento={};
                    me.listarFraccionamiento(me.pagination.current_page,me.buscar,'fraccionamiento');
                    //Se muestra mensaje Success
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Equipamiento registrado'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            eliminarEquipamiento(equipamiento){
                let me = this;

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

                    axios.delete('/urban-equipment/'+equipamiento.id, {
                        params: {'id': equipamiento.id}
                    }).then(function (response){
                        me.equipamientos = me.equipamientos.filter( a => a.id !== equipamiento.id)
                        me.listarFraccionamiento(me.pagination.current_page,me.buscar,'fraccionamiento');
                        //Se muestra mensaje Success
                        const toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                            });
                            toast({
                            type: 'success',
                            title: 'Equipamiento eliminado'
                        })
                    }).catch(function (error){
                        console.log(error);
                    });

                }})

            },
            actualizarFraccionamiento(){
                if(this.validarFraccionamiento()) //Se verifica si hay un error (campo vacio)
                    return;

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
                    'id' : this.id,
                    'numero' : this.numero,
                    'gerente_id' : this.gerente_id,
                    'arquitecto_id' : this.arquitecto_id,
                    'postventa_id' : this.postventa_id,
                    'fecha_ini_venta' : this.fecha_ini_venta
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
                this.nEquipamiento = {};
                this.equipamientos = [];

            },
            cerrarModal4(){
                this.modal4 = 0;
                this.tituloModal4 = '';
                this.archivo_planos = '';
                this.archivo_escrituras = '';
                this.nombre_escritura = '';
                this.nombre_plano = '';
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
                                this.numero = 0;
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
                                this.id=data['id'];
                                this.nombre=data['nombre'];
                                this.tipo_proyecto=data['tipo_proyecto'];
                                this.calle=data['calle'];
                                this.colonia=data['colonia'];
                                this.estado=data['estado'];
                                this.ciudad=data['ciudad'];
                                this.delegacion=data['delegacion'];
                                this.cp=data['cp'];
                                this.gerente_id=data['gerente_id'];
                                this.arquitecto_id=data['arquitecto_id'];
                                if(this.arquitecto_id == null)
                                    this.arquitecto_id = '';
                                if(this.postventa_id == null)
                                    this.postventa_id = '';
                                this.numero = data['numero'];
                                this.selectCiudades(this.estado);
                                this.nEquipamiento = {
                                    fraccionamiento_id : this.id,
                                    categoria : '',
                                    nombre : '',
                                    descripcion : '',
                                };
                                this.equipamientos = data['equipamiento_urbano'];
                                this.paso = 1;
                                break;
                            }


                            case 'subirArchivo':
                            {
                                this.modal4 =1;
                                this.tituloModal4='Subir Archivos';
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
