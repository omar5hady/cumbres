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
                        <i class="fa fa-align-justify"></i> Contratos
                        <!--   Boton Nuevo    -->
                        <button type="button" v-if="rolId!=9 && listado == 1" @click="mostrarDetalle()" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                        <!---->
                    </div>
                    <LoadingComponent v-if="loadingModule"></LoadingComponent>
                    <template v-else>
                        <!-- Div Card Body para listar -->
                        <template v-if="listado == 1">
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <!--Criterios para el listado de busqueda -->
                                            <select class="form-control col-md-4" v-model="criterio" @click="limpiarBusqueda()">
                                                <option value="ini_obras.clave">Clave</option>
                                                <option value="contratistas.nombre">Contratista</option>
                                                <option value="ini_obras.f_ini">Fecha de inicio</option>
                                                <option value="ini_obras.f_fin">Fecha de termino</option>
                                                <option value="ini_obras.fraccionamiento_id">Proyecto</option>
                                            </select>
                                            <select class="form-control" v-if="criterio=='ini_obras.fraccionamiento_id'"  @keyup.enter="listarAvisos(1,buscar,criterio)" v-model="buscar" >
                                                <option value="">Seleccione</option>
                                                <option v-for="proyecto in arrayProyectos" :key="proyecto.id" :value="proyecto.id" v-text="proyecto.nombre"></option>
                                            </select>
                                            <input v-else-if="criterio=='ini_obras.f_ini'" type="date" v-model="buscar" @keyup.enter="listarAvisos(1,buscar,criterio)" class="form-control">
                                            <input v-else-if="criterio=='ini_obras.f_fin'" type="date" v-model="buscar" @keyup.enter="listarAvisos(1,buscar,criterio)" class="form-control">
                                            <input v-else type="text" v-model="buscar" @keyup.enter="listarAvisos(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <select class="form-control" v-model="b_empresa" >
                                                <option value="">Empresa constructora</option>
                                                <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                            </select>
                                            <button type="submit" @click="listarAvisos(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                            <a class="btn btn-success" v-bind:href="'/iniobra/excelAvisos?buscar=' + buscar + '&criterio=' + criterio" >
                                                <i class="icon-pencil"></i>&nbsp;Excel
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <TableComponent :cabecera="[
                                    'Opciones','Clave','Contratista','Fraccionamiento','Superficie total','Importe total',
                                    'Fecha de inicio ','Fecha de termino',''
                                ]">
                                    <template v-slot:tbody>
                                        <tr v-on:dblclick="verAviso(avisoObra.id)" v-for="avisoObra in arrayAvisoObra" :key="avisoObra.id" title="Ver detalle">
                                            <td>
                                                <button type="button" v-if="rolId!=9 && rolId != 11" class="btn btn-danger btn-sm" @click="eliminarContrato(avisoObra)">
                                                    <i class="icon-trash"></i>
                                                </button>
                                                <button type="button" v-if="rolId!=9 && rolId != 11" class="btn btn-warning btn-sm" @click="actualizarContrato(avisoObra.id)">
                                                    <i class="icon-pencil"></i>
                                                </button>
                                                <button title="Subir contrato" v-if="rolId!=9 && rolId != 11" type="button" @click="abrirModal('subirArchivo',avisoObra)" class="btn btn-default btn-sm">
                                                    <i class="icon-cloud-upload"></i>
                                                </button>
                                                <button title="Subir Adendum" v-if="rolId!=9 && rolId != 11" type="button" @click="abrirModal('subirAdendum',avisoObra)" class="btn btn-scarlet btn-sm">
                                                    <i class="icon-cloud-upload"></i>
                                                </button>
                                                <a title="Descargar contrato" class="btn btn-default btn-sm" v-if="avisoObra.documento != '' && avisoObra.documento != null"  v-bind:href="'/downloadContratoObra/'+avisoObra.documento">
                                                    <i class="fa fa-download"></i>
                                                </a>
                                                <a title="Descargar adendum" class="btn btn-default btn-sm" v-if="avisoObra.adendum != '' && avisoObra.adendum != null"  v-bind:href="'/downloadAdendum/'+avisoObra.adendum">
                                                    <i class="fa fa-download"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="#" v-text="avisoObra.clave"></a>
                                            </td>
                                            <td class="td2" v-text="avisoObra.contratista"></td>
                                            <td class="td2" v-text="avisoObra.proyecto"></td>
                                            <td class="td2">{{$root.formatNumber(avisoObra.total_superficie)}} m&sup2;</td>
                                            <td class="td2" v-text="'$'+$root.formatNumber(avisoObra.total_importe)"></td>
                                            <td class="td2" v-text="avisoObra.f_ini"></td>
                                            <td class="td2" v-text="avisoObra.f_fin"></td>
                                            <td>
                                                <a class="btn btn-success" v-bind:href="'/avisoObra/siroc?id='+ avisoObra.id">
                                                    <i class="fa fa-file-text"></i>&nbsp; SIROC
                                                </a>
                                                <button title="Subir registro de obra" type="button" @click="abrirModal('subirArchivo2',avisoObra)" class="btn btn-default btn-sm">
                                                    <i class="icon-cloud-upload"></i>
                                                </button>
                                                <a title="Descargar registro de obra" class="btn btn-default btn-sm" v-if="avisoObra.registro_obra != '' && avisoObra.registro_obra != null"  v-bind:href="'/downloadRegistroObra/'+avisoObra.registro_obra">
                                                    <i class="fa fa-download"></i>
                                                </a>
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
                                <button class="btn btn-sm btn-default" data-toggle="modal" data-target="#manualId">Manual</button>
                            </div>
                        </template>

                        <!-- Div Card Body para nuevo registro -->
                        <AvisoObraFormComponent v-if="listado == 0"
                            :data="avisoObra"
                            @close="closeForm()"
                            :tipoAccion="tipoForm"
                        ></AvisoObraFormComponent>

                        <!--Div para ver detalle del aviso -->
                        <AvisoObraReadComponent v-if="listado == 2"
                            :data="avisoObra"
                            @close="closeForm()"
                            @print="modal=2"
                        >
                        </AvisoObraReadComponent>
                    </template>

                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>

            <!-- Modal para la carga pdf -->
            <ModalComponent :titulo="tituloModal"
                v-if="modal == 1"
                @closeModal="cerrarModal()"
            >
                <template v-slot:body>
                    <div v-if="tipoAccion == 1" >
                        <form  method="post" @submit="formSubmit" enctype="multipart/form-data">

                                <strong>Seleccionar contrato</strong>

                                <input type="file" class="form-control" v-on:change="onImageChange">
                                <br/>
                                <button type="submit" class="btn btn-success">Cargar</button>
                        </form>
                    </div>
                    <div v-if="tipoAccion == 2">
                        <form  method="post" @submit="formSubmit2" enctype="multipart/form-data">

                            <strong>Seleccionar archivo</strong>

                            <input type="file" class="form-control" v-on:change="onImageChange2">
                            <br/>
                            <button type="submit" class="btn btn-success">Cargar</button>
                        </form>
                    </div>
                    <div v-if="tipoAccion == 3">
                        <form  method="post" @submit="saveAdendum" enctype="multipart/form-data">

                            <strong>Seleccionar archivo</strong>

                            <input type="file" class="form-control" v-on:change="onImageChange">
                            <br/>
                            <button type="submit" class="btn btn-success">Cargar</button>
                        </form>
                    </div>
                </template>
            </ModalComponent>
            <!--Fin del modal-->

            <!-- Modal para imprimir contrato -->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal == 2}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Seleccionar apoderado legal</h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div>
                                <select class="form-control" v-model="apoderado">
                                    <option value="ING. DAVID CALVILLO MARTINEZ">ING. DAVID CALVILLO MARTINEZ</option>
                                    <option value="ING. ALEJANDRO F. PEREZ ESPINOSA">ING. ALEJANDRO F. PEREZ ESPINOSA</option>
                                    <option value="ING. JUAN URIEL ALFARO GALVÁN">ING. JUAN URIEL ALFARO GALVÁN</option>
                                </select>
                            </div>
                        </div>

                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                             <a class="btn btn-primary" v-bind:href="'/iniobra/pdf?id=' + avisoObra.id + '&apoderado=' + apoderado"  target="_blank">
                                <i></i>Imprimir
                            </a>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->

            <!-- Manual -->
            <div class="modal fade" id="manualId" tabindex="-1" role="dialog" aria-labelledby="manualIdTitle" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="manualIdTitle">Manual</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>
                            Para poder agregar o ver un lote que desea agregar a un aviso de obra asegúrese de que el lote
                            cuenta con el inicio de obra (vea <strong>“Obra -> Inicio de obra”</strong>).
                        </p>
                        <p>
                            <strong>Cuando un lote es agregado a un nuevo aviso de obra</strong>, el lote o lotes agregados al
                            nuevo aviso de obra, podrá ser visto en el listado del módulo partidas,
                            donde podrá asignar o registrar los costos del proyecto.
                            (el lote solo podrá ser visto en el listado si es agregado en un nuevo inicio de obra)
                        </p>
                        <p>
                            En caso de que al guardar cualquier cambio no suceda nada (no parece la ventana que indica
                            la que los cambios fueron guardados correctamente), actualicé la página y asegúrese de
                            estar llenando correctamente los cambios.
                        </p>
                        <p>
                            Para agregar un <strong>nuevo contratista</strong> al listado acceda al módulo de “<strong>Obra -> Contratistas</strong>”.
                        </p>
                        <p>
                            <strong>Nota:</strong> al agregar un nuevo lote a un nuevo aviso de obra podrá encontrar un listado con la etiqueta “Lote”,
                            donde podrá visualizar, el número de lote, el nombre de la compañía propietaria del lote y la fecha de
                            término de aviso de obra.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    </div>
                </div>
            </div>

        </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    import vSelect from 'vue-select';
    import LoadingComponent from '../Componentes/LoadingComponent.vue';
    import ModalComponent from '../Componentes/ModalComponent.vue';
    import TableComponent from '../Componentes/TableComponent.vue';
    import AvisoObraFormComponent from './components/AvisoObraFormComponent.vue'
    import AvisoObraReadComponent from './components/AvisoObraReadComponent.vue'
    export default {
        props:{
            rolId:{type: String}
        },
        data(){
            return{
                tipoForm:'create',
                avisoObra:{
                    id: '',
                    fraccionamiento_id: 0,
                    contratista_id: 0,
                    contratista: '',
                    clave:'',
                    f_ini: new Date().toISOString().substr(0, 10),
                    f_fin: '',
                    calle1: '',
                    calle2: '',
                    total_importe : 0,
                    total_costo_directo: 0,
                    total_costo_indirecto: 0,
                    anticipo: 0,
                    total_anticipo: 0,
                    lotesContrato: [],
                    emp_constructora: 'CONCRETANIA',
                    costo_indirecto_porcentaje: 0,
                    tipo: 'Vivienda',
                    iva:0,
                    descripcion_larga: '',
                    descripcion_corta: '',
                    total_construccion: 0,
                    direccion_proy: ''
                },
                proceso:false,
                arrayAvisoObra : [],
                modal : 0,
                pdf:'',
                listado:1,
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
                criterio : 'ini_obras.clave',
                buscar : '',
                arrayProyectos : [],
                empresas:[],
                b_empresa:'',
                apoderado:'ING. DAVID CALVILLO MARTINEZ',
                loading:true,
                loadingModule:true
            }
        },
        components:{
            vSelect,
            LoadingComponent,
            ModalComponent,
            TableComponent,
            AvisoObraFormComponent,
            AvisoObraReadComponent
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
        },

        methods : {
            onImageChange(e){
                console.log(e.target.files[0]);
                this.pdf = e.target.files[0];
            },
            formSubmit(e) {
                e.preventDefault();
                let currentObj = this;

                let formData = new FormData();

                formData.append('pdf', this.pdf);
                axios.post('/formSubmitContratoObra/'+this.id, formData)
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
                })
                .catch(function (error) {
                    currentObj.output = error;
                });
            },
            onImageChange2(e){
                console.log(e.target.files[0]);
                this.pdf = e.target.files[0];
            },
            formSubmit2(e) {
                e.preventDefault();
                let currentObj = this;

                let formData = new FormData();

                formData.append('pdf', this.pdf);
                axios.post('/formSubmitRegistroObra/'+this.id, formData)
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
                })
                .catch(function (error) {
                    currentObj.output = error;
                });
            },
            saveAdendum(e) {
                e.preventDefault();
                let currentObj = this;

                let formData = new FormData();

                formData.append('pdf', this.pdf);
                axios.post('/formSubmitAdendum/'+this.id, formData)
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
                })
                .catch(function (error) {
                    currentObj.output = error;
                });
            },
            /**Metodo para mostrar los registros */
            listarAvisos(page, buscar, criterio){
                let me = this;
                me.loadingModule = true;
                var url = '/iniobra?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio + '&empresa=' + me.b_empresa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayAvisoObra = respuesta.ini_obra.data;
                    me.pagination = respuesta.pagination;
                    me.loadingModule = false;
                })
                .catch(function (error) {
                    console.log(error);
                    me.loadingModule = false;
                });
            },
            getEmpresa(){
                let me = this;
                me.empresas=[];
                var url = '/lotes/empresa/select';
                axios.get(url).then(function (response) {
                    var respuesta = response;
                    me.empresas = respuesta.data.empresas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectProyectos(){
                let me = this;
                me.arrayProyectos=[];
                var url = '/select_fraccionamiento';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayProyectos = respuesta.fraccionamientos;
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
                me.listarAvisos(page,buscar,criterio);
            },
            closeForm(){
                this.listado = 1;
                this.limpiarDatos();
                this.listarAvisos(1,'','ini_obras.clave');
            },
            limpiarBusqueda(){
                let me=this;
                me.buscar= "";
            },

            limpiarDatos(){
                this.avisoObra = {
                    id: '',
                    fraccionamiento_id: 0,
                    contratista_id: 0,
                    contratista: '',
                    clave:'',
                    f_ini: new Date().toISOString().substr(0, 10),
                    f_fin: '',
                    calle1: '',
                    calle2: '',
                    total_importe : 0,
                    total_costo_directo: 0,
                    total_costo_indirecto: 0,
                    anticipo: 0,
                    total_anticipo: 0,
                    lotesContrato: [],
                    emp_constructora: 'CONCRETANIA',
                    costo_indirecto_porcentaje: 0,
                    tipo: 'Vivienda',
                    iva:0,
                    descripcion_larga: '',
                    descripcion_corta: '',
                    total_construccion: 0,
                    direccion_proy: ''
                }
            },
            eliminarContrato(data =[]){
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
                axios.delete('/iniobra/contrato/eliminar',
                        {params: {'id': this.id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Contrato borrada correctamente.',
                        'success'
                        )
                         me.listarAvisos(1,'','ini_obras.clave');
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            mostrarDetalle(){
                this.tipoForm='create';
                this.limpiarDatos();
                this.listado=0;
            },
            verAviso(id){
                let me= this;
                //Obtener datos de cabecera
                let arrayAvisoT=[];
                let url = '/iniobra/obtenerCabecera?id=' + id;
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    arrayAvisoT = respuesta.ini_obra;
                    me.avisoObra.id = id
                    me.avisoObra.fraccionamiento_id = arrayAvisoT[0]['fraccionamiento_id'];
                    me.avisoObra.contratista_id = arrayAvisoT[0]['contratista_id'];
                    me.avisoObra.contratista = arrayAvisoT[0]['contratista'];
                    me.avisoObra.clave = arrayAvisoT[0]['clave'];
                    me.avisoObra.f_ini = arrayAvisoT[0]['f_ini'];
                    me.avisoObra.f_fin = arrayAvisoT[0]['f_fin'];
                    me.avisoObra.calle1 = arrayAvisoT[0]['calle1'];
                    me.avisoObra.calle2 = arrayAvisoT[0]['calle2'];
                    me.avisoObra.anticipo = arrayAvisoT[0]['anticipo'];
                    me.avisoObra.total_anticipo = arrayAvisoT[0]['total_anticipo'];

                    me.avisoObra.emp_constructora = arrayAvisoT[0]['emp_constructora'];
                    me.avisoObra.costo_indirecto_porcentaje = arrayAvisoT[0]['costo_indirecto_porcentaje'];
                    me.avisoObra.tipo = arrayAvisoT[0]['tipo'];
                    me.avisoObra.iva = arrayAvisoT[0]['iva'];
                    me.avisoObra.descripcion_larga = arrayAvisoT[0]['descripcion_larga'];
                    me.avisoObra.descripcion_corta = arrayAvisoT[0]['descripcion_corta'];
                    me.avisoObra.direccion_proy = arrayAvisoT[0]['direccion_proy'];
                    me.avisoObra.fraccionamiento = arrayAvisoT[0]['proyecto'];
                })
                .catch(function (error) {
                    console.log(error);
                });
                //Obtener detalle
                let urld = '/iniobra/obtenerDetalles?id=' + id;
                axios.get(urld).then(function (response) {
                    let respuesta = response.data;
                    me.avisoObra.lotesContrato = respuesta.detalles;
                })
                .catch(function (error) {
                    console.log(error);
                });
                this.listado = 2
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.pdf='';
            },
            abrirModal(accion,data =[]){
                switch(accion){
                    case 'subirArchivo':
                    {
                        this.modal =1;
                        this.tipoAccion = 1;
                        this.tituloModal='Subir contrato';
                        this.id=data['id'];
                        this.pdf=data['documento'];
                        break;
                    }
                    case 'subirArchivo2':
                    {
                        this.modal =1;
                        this.tipoAccion = 2;
                        this.tituloModal='Subir registro de obra';
                        this.id=data['id'];
                        this.pdf=data['documento'];
                        break;
                    }
                    case 'subirAdendum':
                    {
                        this.modal =1;
                        this.tipoAccion = 3;
                        this.tituloModal='Subir adendum';
                        this.id=data['id'];
                        this.pdf=data['adendum'];
                        break;
                    }
                }
            },
            actualizarContrato(id){
                let me= this;
                //Obtener datos de cabecera
                let arrayAvisoT=[];
                let url = '/iniobra/obtenerCabecera?id=' + id;
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    arrayAvisoT = respuesta.ini_obra;
                    me.avisoObra.id = id
                    me.avisoObra.fraccionamiento_id = arrayAvisoT[0]['fraccionamiento_id'];
                    me.avisoObra.contratista_id = arrayAvisoT[0]['contratista_id'];
                    me.avisoObra.contratista = arrayAvisoT[0]['contratista'];
                    me.avisoObra.clave = arrayAvisoT[0]['clave'];
                    me.avisoObra.f_ini = arrayAvisoT[0]['f_ini'];
                    me.avisoObra.f_fin = arrayAvisoT[0]['f_fin'];
                    me.avisoObra.calle1 = arrayAvisoT[0]['calle1'];
                    me.avisoObra.calle2 = arrayAvisoT[0]['calle2'];
                    me.avisoObra.anticipo = arrayAvisoT[0]['anticipo'];
                    me.avisoObra.total_anticipo = arrayAvisoT[0]['total_anticipo'];

                    me.avisoObra.emp_constructora = arrayAvisoT[0]['emp_constructora'];
                    me.avisoObra.costo_indirecto_porcentaje = arrayAvisoT[0]['costo_indirecto_porcentaje'];
                    me.avisoObra.tipo = arrayAvisoT[0]['tipo'];
                    me.avisoObra.iva = arrayAvisoT[0]['iva'];
                    me.avisoObra.descripcion_larga = arrayAvisoT[0]['descripcion_larga'];
                    me.avisoObra.descripcion_corta = arrayAvisoT[0]['descripcion_corta'];
                    me.avisoObra.direccion_proy = arrayAvisoT[0]['direccion_proy'];
                    me.avisoObra.fraccionamiento = arrayAvisoT[0]['proyecto'];
                })
                .catch(function (error) {
                    console.log(error);
                });
                //Obtener detalle
                let urld = '/iniobra/obtenerDetalles?id=' + id;
                axios.get(urld).then(function (response) {
                    let respuesta = response.data;
                    me.avisoObra.lotesContrato = respuesta.detalles;
                })
                .catch(function (error) {
                    console.log(error);
                });

                this.listado=0;
                this.tipoForm='update'
            }

        },
        mounted() {
            this.listarAvisos(1,this.buscar,this.criterio);
            this.selectProyectos();
            this.getEmpresa();

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
</style>
