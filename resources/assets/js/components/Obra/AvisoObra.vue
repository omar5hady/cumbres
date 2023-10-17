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
                        <button type="button" v-if="(rolId!=9 && rolId!=13) && listado == 1" @click="mostrarDetalle()" class="btn btn-secondary">
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
                                                <option v-if="rolId!=13" value="contratistas.nombre">Contratista</option>
                                                <option value="ini_obras.f_ini">Fecha de inicio</option>
                                                <option value="ini_obras.f_fin">Fecha de termino</option>
                                                <option value="ini_obras.fraccionamiento_id">Proyecto</option>
                                            </select>
                                            <select class="form-control" v-if="criterio=='ini_obras.fraccionamiento_id'"  @keyup.enter="listarAvisos(1)" v-model="buscar" >
                                                <option value="">Seleccione</option>
                                                <option v-for="proyecto in arrayProyectos" :key="proyecto.id" :value="proyecto.id" v-text="proyecto.nombre"></option>
                                            </select>
                                            <input v-else-if="criterio=='ini_obras.f_ini'" type="date" v-model="buscar" @keyup.enter="listarAvisos(1)" class="form-control">
                                            <input v-else-if="criterio=='ini_obras.f_fin'" type="date" v-model="buscar" @keyup.enter="listarAvisos(1)" class="form-control">
                                            <input v-else type="text" v-model="buscar" @keyup.enter="listarAvisos(1)" class="form-control" placeholder="Texto a buscar">
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
                                            <button type="submit" @click="listarAvisos(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                            <a class="btn btn-success" v-bind:href="'/iniobra/excelAvisos?buscar=' + buscar + '&criterio=' + criterio" >
                                                <i class="icon-pencil"></i>&nbsp;Excel
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <ul class="nav nav-tabs" id="myTab1" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link"
                                        @click="b_status = 0, listarAvisos(1)"
                                        v-bind:class="{ 'text-primary active': b_status === 0}"
                                        role="tab">En Proceso</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                        @click="b_status = 1, listarAvisos(1)"
                                        v-bind:class="{ 'text-primary active': b_status === 1}"
                                        role="tab">Por Cerrar</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                        @click="b_status = 2, listarAvisos(1)"
                                        v-bind:class="{ 'text-primary active': b_status === 2}"
                                        role="tab">Cerradas</a>
                                    </li>
                                </ul>

                                <div class="tab-content" id="myTab1Content">
                                    <!-- Listado por Solicitudes EN PROCESO -->
                                    <div class="tab-pane fade"  v-bind:class="{ 'active show': b_status === 0 }" v-if="b_status ===  0">
                                        <TablePendiente
                                            :usuario="usuario"
                                            :rolId="rolId"
                                            :arrayAvisoObra="arrayAvisoObra"
                                            @sendPorCerrar="sendPorCerrar"
                                            @abrirModal="abrirModal"
                                            @print="print"
                                            @actualizar="actualizarContrato"
                                            @eliminar="eliminarContrato"
                                            @ver="verAviso"
                                        ></TablePendiente>
                                    </div>
                                    <!-- Listado por Solicitudes POR CERRAR -->
                                    <div class="tab-pane fade"  v-bind:class="{ 'active show': b_status === 1 }" v-if="b_status ===  1">
                                        <TablePorCerrar
                                            :usuario="usuario"
                                            :rolId="rolId"
                                            :arrayAvisoObra="arrayAvisoObra"
                                            @abrirModal="abrirModal"
                                            @ver="verAviso"
                                        ></TablePorCerrar>
                                    </div>
                                    <!-- Listado por Solicitudes CERRADAS -->
                                    <div class="tab-pane fade"  v-bind:class="{ 'active show': b_status === 2 }" v-if="b_status ===  2">
                                        <TableCerradas
                                            :arrayAvisoObra="arrayAvisoObra"
                                            @ver="verAviso"
                                        ></TableCerradas>
                                    </div>
                                </div>
                                <NavComponent
                                    :current="pagination.current_page ? pagination.current_page : 1"
                                    :last="pagination.last_page ? pagination.last_page : 1"
                                    @changePage="listarAvisos"
                                ></NavComponent>
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
                            @print="print({tipo:'contrato',id:''})"
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

                            <div class="form-group row">
                                <label class="col-md-2 form-control-label" for="text-input">Registro de obra:</label>
                                <div class="col-md-6">
                                    <input type="text" v-model="folio_siroc" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <input type="file" class="form-control" v-on:change="onImageChange2">
                            </div>

                            <br/>
                            <button v-if="folio_siroc != ''"
                                type="submit" class="btn btn-success">Cargar</button>
                        </form>
                    </div>
                    <div v-if="tipoAccion == 3">
                        <form  method="post" @submit="saveAdendum" enctype="multipart/form-data">

                            <strong>Seleccionar archivo</strong>

                            <input type="file" class="form-control" v-on:change="onImageChange">
                            <br/>
                            <button
                                type="submit" class="btn btn-success">Cargar</button>
                        </form>
                    </div>

                    <div v-if="tipoAccion == 4">
                        <form  method="post" @submit="saveAcuseCierre" enctype="multipart/form-data">

                            <strong>Seleccionar archivo</strong>

                            <input type="file" class="form-control" v-on:change="onImageChange">
                            <br/>
                            <button
                                type="submit" class="btn btn-success">Cargar</button>
                        </form>
                    </div>
                </template>
            </ModalComponent>
            <!--Fin del modal-->

            <!-- Modal para imprimir contrato -->
            <ModalComponent v-if="modal==2"
                titulo="Seleccionar apoderado legal"
                @closeModal="cerrarModal()"
            >
                <template v-slot:body>
                    <div>
                        <select class="form-control" v-model="apoderado">
                            <option value="ING. DAVID CALVILLO MARTINEZ">ING. DAVID CALVILLO MARTINEZ</option>
                            <option value="ING. ALEJANDRO F. PEREZ ESPINOSA">ING. ALEJANDRO F. PEREZ ESPINOSA</option>
                            <option value="ING. JUAN URIEL ALFARO GALVÁN">ING. JUAN URIEL ALFARO GALVÁN</option>
                        </select>
                    </div>
                </template>
                <template v-slot:buttons-footer>
                    <a
                        v-if="tipoAccion==0"
                        class="btn btn-primary" v-bind:href="'/iniobra/pdf?id=' + avisoObra.id + '&apoderado=' + apoderado"  target="_blank">
                        <i></i>Imprimir Contrato
                    </a>
                    <a
                        v-else
                        class="btn btn-primary" v-bind:href="'/iniobra/adendum?id=' + avisoObra.id + '&apoderado=' + apoderado"  target="_blank">
                        <i></i>Imprimir Adendum
                    </a>
                </template>
            </ModalComponent>
            <!--Fin del modal-->

            <!--Inicio del modal observaciones-->
            <ModalComponent v-if="modal == 3"
                :titulo="tituloModal"
                @closeModal="cerrarModal()"
            >
                <template v-slot:body>
                    <RowModal :label1="'Observacion'" :clsRow1="'col-md-6'" :clsRow2="'col-md-3'">
                        <textarea rows="3" cols="30" v-model="comentario" class="form-control" placeholder="Observacion"></textarea>
                        <template v-slot:input2>
                            <Button :btnClass="'btn-primary'" :icon="'icon-plus'" @click="storeObs(id)" title="Guardar observación"></Button>
                        </template>
                    </RowModal>

                    <TableComponent :cabecera="['Usuario','Observación','Fecha']">
                        <template v-slot:tbody>
                            <tr v-for="observacion in arrayObs" :key="observacion.id">
                                <td v-text="observacion.usuario" ></td>
                                <td v-text="observacion.comentario" ></td>
                                <td v-text="observacion.created_at"></td>
                            </tr>
                        </template>
                    </TableComponent>
                </template>
            </ModalComponent>

            <ModalComponent v-if="modal==4"
                @closeModal="cerrarModal"
                :titulo="tituloModal"
            >
                <template v-slot:body>
                    <RowModal label1="Importe de cierre:" clsRow1="col-md-4" clsRow2="col-md-3" label2="">
                        <input type="text" pattern="\d*" class="form-control" v-model="avisoObra.total_original" v-on:keypress="$root.isNumber($event)">
                        <template v-slot:input2>
                            <p class="form-control">{{ $root.formatNumber(avisoObra.total_original) }}</p>
                        </template>
                    </RowModal>
                    <RowModal label1="Importe obra extra:" clsRow1="col-md-4" clsRow2="col-md-3" label2="">
                        <input type="text" pattern="\d*" class="form-control" v-model="avisoObra.total_extra" v-on:keypress="$root.isNumber($event)">
                        <template v-slot:input2>
                            <p class="form-control">{{ $root.formatNumber(avisoObra.total_extra) }}</p>
                        </template>
                    </RowModal>
                </template>
                <template v-slot:buttons-footer>
                    <Button icon="icon-check" @click="changeStatus(avisoObra.id,1)"></Button>
                </template>
            </ModalComponent>



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
    import Button from '../Componentes/ButtonComponent.vue';
    import RowModal from '../Componentes/ComponentesModal/RowModalComponent.vue'
    import LoadingComponent from '../Componentes/LoadingComponent.vue';
    import ModalComponent from '../Componentes/ModalComponent.vue';
    import TableComponent from '../Componentes/TableComponent.vue';
    import NavComponent from '../Componentes/NavComponent.vue';
    import AvisoObraFormComponent from './components/AvisoObraFormComponent.vue'
    import AvisoObraReadComponent from './components/AvisoObraReadComponent.vue'
    import TablePendiente from './components/TablePendientes.vue';
    import TablePorCerrar from './components/TablePorCerrar.vue';
    import TableCerradas from './components/TableCerradas.vue'
    export default {
        props:{
            rolId:{type: String},
            usuario:{type: String}
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
                    total_original: 0,
                    total_extra: 0,
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
                    direccion_proy: '',
                },
                id: '',
                arrayObs:[],
                proceso:false,
                arrayAvisoObra : [],
                modal : 0,
                pdf:'',
                folio_siroc:'',
                acuseContratista:0,
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
                b_status: 0,
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
            AvisoObraReadComponent,
            TablePendiente,
            TablePorCerrar,
            TableCerradas,
            NavComponent,
            Button,
            RowModal
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
            print(opciones){
                const {tipo, id } = {...opciones}
                this.modal = 2;
                if(tipo == 'contrato')
                    this.tipoAccion = 0;
                else{
                    this.avisoObra.id = id;
                    this.tipoAccion = 1;
                }

            },
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
                formData.append('folio_siroc', this.folio_siroc)
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
            sendPorCerrar(contrato){
                if(contrato.f_fin != contrato.f_fin2 && contrato.adendum == null){
                    swal({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Es necesario cargar el Adendum',
                    })
                    return;
                }

                this.abrirModal({accion:'porCerrar',data: contrato})

                // this.changeStatus(contrato.id, 1)
            },
            changeStatus(id,status){
                let me = this;
                swal({
                    title: 'Esta seguro de cambiar el status de este contrato?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar!',
                    cancelButtonText: 'Cancelar',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false,
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        Swal.showLoading()
                        axios.put('/iniobra/changeStatus',{
                            'id': id,
                            'status': status,
                            'total_original': me.avisoObra.total_original ? me.avisoObra.total_original : 0,
                            'total_extra': me.avisoObra.total_extra ? me.avisoObra.total_extra : 0,
                            }).then(function (response){
                            Swal.enableLoading()
                            swal(
                                'Cambio de status!',
                                'Cambios realizados con éxito.',
                                'success'
                            )
                            me.listarAvisos(1)
                            me.cerrarModal()
                        }).catch(function (error){
                            Swal.enableLoading()
                        });

                    } else if (result.dismiss === swal.DismissReason.cancel
                        )me.listarAvisos(1);
                })
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
            saveAcuseCierre(e) {
                e.preventDefault();
                let currentObj = this;

                let formData = new FormData();

                formData.append('pdf', this.pdf);
                formData.append('id', this.id);
                formData.append('contratista', this.acuseContratista);
                axios.post('/saveAcuseCierre', formData)
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
            listarAvisos(page){
                let me = this;
                me.loadingModule = true;
                var url = '/iniobra?page=' + page + '&buscar=' + me.buscar +
                    '&criterio=' + me.criterio + '&empresa=' + me.b_empresa +
                    '&status=' + me.b_status;
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
            closeForm(){
                this.listado = 1;
                this.limpiarDatos();
                this.listarAvisos(1);
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
                    total_extra: 0,
                    total_original: 0,
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
                    direccion_proy: '',
                    folio_siroc:'',
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
                         me.listarAvisos(1);
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            storeObs(id){
            let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/obra/storeObs',{
                    'aviso_id': id,
                    'comentario' : me.comentario
                }).then(function (response){
                    me.listarAvisos(me.pagination.current_page); //se enlistan nuevamente los registros
                    me.comentario = '';
                    me.arrayObs.push(response.data)
                    //me.cerrarModal();
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Observación creada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                });
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
                this.listarAvisos(1);
            },
            abrirModal(opciones){
                console.log(opciones)
                const {accion, data} = {...opciones}
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
                        this.folio_siroc = '';
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
                    case 'subirAcuse':
                    {
                        this.modal =1;
                        this.tipoAccion = 4;
                        this.tituloModal='Subir acuse de cierre';
                        this.id=data['id'];
                        this.acuseContratista = 0;
                        this.pdf='';
                        break;
                    }
                    case 'subirAcuseContratista':{
                        this.modal =1;
                        this.tipoAccion = 4;
                        this.tituloModal='Subir acuse de cierre';
                        this.id=data['id'];
                        this.pdf='';
                        this.acuseContratista = 1;
                        break;
                    }
                    case 'observaciones':{
                        this.modal = 3;
                        this.tituloModal = 'Observaciones';
                        this.arrayObs = data['obs'];
                        this.id=data['id'];
                        this.comentario = '';
                        break;
                    }
                    case 'porCerrar':{
                        this.modal =4;
                        this.tipoAccion = 4;
                        this.tituloModal='Enviar a por cerrar';
                        this.avisoObra.id=data['id'];
                        this.avisoObra.total_original = data['total_original'];
                        this.avisoObra.total_extra = 0;
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
            this.listarAvisos(1);
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

</style>
