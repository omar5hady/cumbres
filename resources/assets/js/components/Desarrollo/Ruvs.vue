<template >
    <main class="main" >
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card scroll-box">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i>RUV
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">

                                    <select class="form-control" v-model="buscar">
                                        <option value="">Seleccione Proyecto</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>
                                    <input type="text"  v-model="b_etapa" @keyup.enter="listarRuvs(1)" class="form-control" placeholder="Etapa">

                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->

                                    <input type="text"  v-model="b_manzana" @keyup.enter="listarRuvs(1)" class="form-control" placeholder="Manzana">
                                    <input type="text"  v-model="b_lote" @keyup.enter="listarRuvs(1)" class="form-control" placeholder="# Lote">

                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-5">
                                <div class="input-group">
                                    <input type="text"  v-model="b_paquete" @keyup.enter="listarRuvs(1)" class="form-control" placeholder="Paquete Ruv">

                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control" v-model="b_empresa" >
                                        <option value="">Empresa constructora</option>
                                        <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                    </select>
                                    <button type="submit" @click="listarRuvs(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    <a class="btn btn-success" v-bind:href="'/ruv/excelRuv?buscar=' + buscar + '&b_etapa=' + b_etapa + '&b_manzana='
                                        + b_manzana + '&b_lote=' + b_lote + '&b_paquete=' + b_paquete + '&empresa=' + b_empresa" >
                                        <i class="icon-pencil"></i>&nbsp;Excel
                                    </a>
                                </div>
                            </div>
                        </div>
                        <TableComponent>
                            <template v-slot:thead>
                                <tr>
                                    <th colspan="5"></th>
                                    <th colspan="3" class="text-center">Solicitud</th>
                                    <th colspan="2"></th>
                                    <th colspan="2" class="text-center">Asignación</th>
                                    <th colspan="3"></th>
                                </tr>
                                <tr>
                                    <th>Proyecto</th>
                                    <th>Etapa</th>
                                    <th>Manzana</th>
                                    <th># Lote</th>
                                    <th>Modelo</th>
                                    <th>Paquete </th>
                                    <th>Fecha</th>
                                    <th>Solicitante</th>
                                    <th>Carga de inf.</th>
                                    <th>Num. CUV</th>
                                    <th>Fecha</th>
                                    <th>Empresa</th>
                                    <th>Rev. Documental</th>
                                    <th>DTU</th>
                                    <th>Observaciones</th>
                                </tr>
                            </template>
                            <template v-slot:tbody>
                                <tr v-for="lote in arrayLotes" :key="lote.id">
                                    <td class="td2" v-text="lote.proyecto"></td>
                                    <td class="td2" v-text="lote.num_etapa"></td>
                                    <td class="td2" v-text="lote.manzana"></td>
                                    <td class="td2" v-text="lote.num_lote"></td>
                                    <td class="td2" v-text="lote.modelo"></td>
                                    <td class="td2" v-text="lote.paq_ruv"></td>
                                    <td class="td2" v-text="this.moment(lote.fecha_siembra).locale('es').format('DD/MMM/YYYY')"></td>
                                    <td class="td2" v-text="lote.nombre + ' '+ lote.apellidos"></td>
                                    <td class="td2 text-center" >
                                        <button v-if="!lote.fecha_carga" type="button" @click="abrirModal('cargaInfo',lote)" class="btn btn-primary btn-sm" title="Carga de informacion">
                                            <i class="fa fa-check"></i>
                                        </button>
                                        {{ (lote.fecha_carga) ? this.moment(lote.fecha_carga).locale('es').format('DD/MMM/YYYY'):''}}
                                    </td>
                                    <td class="td2">
                                        <button v-if="!lote.num_cuv" type="button" @click="obtenerCuv(lote.id)" class="btn btn-dark btn-sm" title="Num. CUV">
                                            <i class="fa fa-edit">&nbsp;# CUV</i>
                                        </button>
                                        {{(lote.num_cuv) ? lote.num_cuv : ''}}
                                    </td>
                                    <template v-if="lote.empresa == null">
                                        <td class="td2 text-center" colspan="2">
                                            <button type="button" @click="abrirModal('asignacion',lote)" class="btn btn-primary btn-sm" title="Asignación de verificador">
                                                <i class="fa fa-users">&nbsp;Asignar Verificador</i>
                                            </button>
                                        </td>
                                    </template>
                                    <template v-else>
                                        <td class="td2" v-text="this.moment(lote.fecha_asignacion).locale('es').format('DD/MMM/YYYY')"></td>
                                        <td class="td2" v-text="lote.empresa"></td>
                                    </template>
                                    <td class="td2 text-center">
                                        <button  v-if="!lote.fecha_revision" type="button" @click="revDocumental(lote.id)" class="btn btn-dark btn-sm" title="Revisión Documental">
                                            <i class="fa fa-check"></i>
                                        </button>
                                        {{(lote.fecha_revision) ? this.moment(lote.fecha_revision).locale('es').format('DD/MMM/YYYY') : ''}}
                                    </td>
                                    <td class="td2">
                                        <button  v-if="!lote.fecha_dtu" type="button" @click="abrirModal('dtu',lote)" class="btn btn-primary btn-sm" title="Obtención de DTU">
                                            <i class="fa fa-check"></i>
                                        </button>
                                        {{(lote.fecha_dtu) ? this.moment(lote.fecha_dtu).locale('es').format('DD/MMM/YYYY'):''}}
                                    </td>
                                    <td class="td2">
                                        <button type="button" @click="abrirModal('observaciones',lote)" class="btn btn-warning btn-sm" title="Observaciones">
                                            <i class="fa fa-book">&nbsp;Observaciones</i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </TableComponent>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page > 7">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(1)">Inicio</a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1)">Sig</a>
                                </li>
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page<pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.last_page)">Ultimo</a>
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
                    <div class="form-group row" v-if="tipoAccion == 1">
                        <label class="col-md-3 form-control-label" for="text-input">Fecha de carga</label>
                        <div class="col-md-4">
                            <input type="date" v-model="fecha" class="form-control" >
                        </div>
                    </div>

                        <div class="form-group row" v-if="tipoAccion == 2">
                        <label class="col-md-3 form-control-label" for="text-input">Empresa Verificadora</label>
                        <div class="col-md-5">
                            <select class="form-control" v-model="empresa">
                                <option value="">Seleccione Empresa</option>
                                <option v-for="empresa in arrayEmpresas" :key="empresa.id" :value="empresa.id" v-text="empresa.empresa"></option>
                            </select>
                        </div>
                    </div>

                        <div class="form-group row" v-if="tipoAccion == 3">
                        <label class="col-md-3 form-control-label" for="text-input">Fecha de obtención</label>
                        <div class="col-md-4">
                            <input type="date" v-model="fecha" class="form-control" >
                        </div>
                    </div>
                </template>
                <template v-slot:buttons-footer>
                    <button type="button" v-if="tipoAccion == 1 && fecha != ''" class="btn btn-primary" @click="cargarInformacion()">Guardar</button>
                    <button type="button" v-if="tipoAccion == 2 && empresa != ''" class="btn btn-primary" @click="asignarVerificador()">Guardar</button>
                    <button type="button" v-if="tipoAccion == 3 && fecha != ''" class="btn btn-primary" @click="obtenerDTU()">Guardar</button>
                </template>
            </ModalComponent>
            <!--Fin del modal-->

            <!--Inicio del modal observaciones-->
            <ModalComponent :titulo="tituloModal"
                v-if="modal2"
                @closeModal="cerrarModal()"
            >
                <template v-slot:body>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Observacion</label>
                        <div class="col-md-6">
                                <textarea rows="3" cols="30" v-model="observacion" class="form-control" placeholder="Observacion"></textarea>

                        </div>
                    </div>
                    <!--//////////tabla de consulta de observaciones//////////////-->
                    <TableComponent :cabecera="['Usuario','Observación','Fecha']">
                        <template v-slot:tbody>
                            <tr v-for="observacion in arrayObservacion" :key="observacion.id">
                                <td v-text="observacion.usuario" ></td>
                                <td v-text="observacion.observacion" ></td>
                                <td v-text="observacion.created_at"></td>
                            </tr>
                        </template>
                    </TableComponent>
                </template>
                <template v-slot:buttons-footer>
                    <button type="button" class="btn btn-primary" @click="agregarComentario()">Guardar</button>
                </template>
            </ModalComponent>
            <!--Fin del modal observaciones-->

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
            TableComponent,
            ModalComponent
        },
        props:{
            rolId:{type: String}
        },
        data(){
            return{
                allLic: [],
                allSelected: false,

                proceso:false,
                usuario:'',
                id: 0,
                observacion:'',
                arrayLotes : [],
                arrayFraccionamientos:[],
                arrayObservacion:[],
                arrayEmpresas:[],
                modal : 0,
                modal2 : 0,
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
                criterio : 'lotes.fraccionamiento_id',
                buscar : '',
                buscar2 : '',
                b_etapa:'',
                b_manzana : '',
                b_lote : '',
                b_paquete : '',
                b_empresa: '',
                empresas: [],

                fecha:'',
                empresa:'',

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

        },


        methods : {

            /**Metodo para mostrar los registros */
            listarRuvs(page){
                let me = this;
                var url = '/ruv/indexRuv?page=' + page + '&buscar=' + me.buscar + '&b_etapa=' + me.b_etapa + '&b_manzana=' + me.b_manzana +
                '&b_lote=' + me.b_lote + '&b_paquete=' + me.b_paquete + '&empresa=' + me.b_empresa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLotes = respuesta.lotes.data;
                    me.pagination = respuesta.pagination;
                    console.log(url);
                })
                .catch(function (error) {
                    console.log(error);
                });

            },

            listarObservacion(buscar){
                let me = this;
                var url = '/ruv/indexComentarios?id=' + buscar ;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayObservacion = respuesta.observacion;
                    console.log(url);
                })
                .catch(function (error) {
                    console.log(error);
                });

            },

            agregarComentario(){

                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/ruv/storeComentarios',{
                    'id': this.id,
                    'observacion': this.observacion
                }).then(function (response){
                    me.listarObservacion(me.id);
                    me.observacion = '';
                    //me.cerrarModal3(); //al guardar el registro se cierra el modal

                    const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                    });

                    toast({
                    type: 'success',
                    title: 'Observación Agregada Correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
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

            selectFraccionamientos(){
                let me = this;
                me.buscar="";
                me.b_arquitecto="";
                me.b_manzana="";
                me.b_lote="";
                me.b_modelo="";
                me.buscar2="";
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

            selectEmpresaVerif(){
                let me = this;

                me.arrayEmpresas=[];
                var url = '/empresa/selectEmpresaVerificadora';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEmpresas = respuesta.empresas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            cargarInformacion(){
                if(this.rolId == 1 || this.rolId == 3){
                    let me = this;
                    //Con axios se llama el metodo update de FraccionaminetoController
                    axios.put('/ruv/cargaInfo',{
                        'id' : this.id,
                        'fecha': this.fecha
                    }).then(function (response){
                        me.cerrarModal();
                        me.listarRuvs(1);
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
                }
                else{
                    Swal.fire({
                        type:'warning',
                        title: 'Sin permisos...',
                        text: 'No tiene permisos para realizar esta accion!',
                        })
                }
            },

            asignarVerificador(){
                if(this.rolId == 1 || this.rolId == 3){
                    let me = this;
                    //Con axios se llama el metodo update de FraccionaminetoController
                    axios.put('/ruv/asignarVerificador',{
                        'id' : this.id,
                        'empresa': this.empresa
                    }).then(function (response){
                        me.cerrarModal();
                        me.listarRuvs(1);
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
                }
                else{
                    Swal.fire({
                        type:'warning',
                        title: 'Sin permisos...',
                        text: 'No tiene permisos para realizar esta accion!',
                        })
                }
            },

            obtenerDTU(){
                if(this.rolId == 1 || this.rolId == 5 || this.rolId == 8 ){
                    let me = this;
                    //Con axios se llama el metodo update de FraccionaminetoController
                    axios.put('/ruv/dtu',{
                        'id' : this.id,
                        'fecha': this.fecha
                    }).then(function (response){
                        me.cerrarModal();
                        me.listarRuvs(1);
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
                }
                else{
                    Swal.fire({
                        type:'warning',
                        title: 'Sin permisos...',
                        text: 'No tiene permisos para realizar esta accion!',
                        })
                }
            },

            obtenerCuv(id){
                if(this.rolId == 1 || this.rolId == 3 || this.rolId == 8){
                    let me = this;
                    (async function getFruit () {
                        const {value: numero} = await Swal({
                        title: 'Obtención de #CUV',
                        input: 'text',
                        inputPlaceholder: 'Escribe el numero aqui...',
                        showCancelButton: true,
                        inputValidator: (value) => {
                            return new Promise((resolve) => {
                            if (value != '') {
                                resolve()
                            } else {
                                resolve('Es necesario escribir el numero de CUV')
                            }
                            })
                        }
                        })

                        if (numero) {
                            //Con axios se llama el metodo update de LoteController
                            axios.put('/ruv/obtenerCuv',{
                                'id': id,
                                'numeroCuv': numero
                            }).then(function (response){
                                me.cerrarModal();
                                me.listarRuvs(1);
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
                        }

                        })()
                }
                else{
                    Swal.fire({
                        type:'warning',
                        title: 'Sin permisos...',
                        text: 'No tiene permisos para realizar esta accion!',
                        })
                }
            },

            revDocumental(id){
                if(this.rolId == 1 || this.rolId == 5){
                    let me = this;
                    //Con axios se llama el metodo update de LoteController

                    Swal({
                        title: 'Estas seguro?',
                        animation: false,
                        customClass: 'animated bounceInDown',
                        text: "La revisión documental esta completa",
                        type: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        cancelButtonText: 'Cancelar',

                        confirmButtonText: 'Si!'
                        }).then((result) => {

                        if (result.value) {

                            axios.put('/ruv/revDocumental',{
                                'id':id
                            });

                            me.listarRuvs(me.pagination.current_page);
                            Swal({
                                title: 'Hecho!',
                                text: 'Revisión documental finalizada',
                                type: 'success',
                                animation: false,
                                customClass: 'animated bounceInRight'
                            })
                        }
                    })
                }
                else{
                    Swal.fire({
                        type:'warning',
                        title: 'Sin permisos...',
                        text: 'No tiene permisos para realizar esta accion!',
                        })
                }

            },

            cambiarPagina(page){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarRuvs(page);
            },

            cerrarModal(){
                this.modal = 0;
                this.modal2 = 0;
                this.observacion ='';
                this.tituloModal = '';

            },

            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(accion,data =[]){
                this.selectEmpresaVerif();
                switch(accion){

                    case 'cargaInfo':
                    {
                        if(this.rolId == 1 || this.rolId == 3 || this.rolId == 8){
                            this.modal =1;
                            this.tituloModal='Carga de Informacion RUV';
                            this.fecha='';
                            this.tipoAccion=1;
                            this.id = data['id'];
                        }
                        else{
                            Swal.fire({
                                type:'warning',
                                title: 'Sin permisos...',
                                text: 'No tiene permisos para realizar esta accion!',
                            })
                        }
                        break;
                    }
                    case 'asignacion':
                    {
                        if(this.rolId == 1 || this.rolId == 3 || this.rolId == 8){
                            this.modal =1;
                            this.tituloModal='Asignación de verificador';
                            this.fecha='';
                            this.empresa = '';
                            this.tipoAccion=2;
                            this.id = data['id'];
                            this.selectEmpresaVerif();
                        }
                        else{
                            Swal.fire({
                                type:'warning',
                                title: 'Sin permisos...',
                                text: 'No tiene permisos para realizar esta accion!',
                            })
                        }
                        break;
                    }
                    case 'dtu':{
                        if(this.rolId == 1 || this.rolId == 5 ){
                            this.modal= 1;
                            this.tituloModal='Obtención de DTU';
                            this.fecha='';
                            this.tipoAccion=3;
                            this.id = data['id'];
                        }
                        else{
                            Swal.fire({
                                type:'warning',
                                title: 'Sin permisos...',
                                text: 'No tiene permisos para realizar esta accion!',
                            })
                        }
                        break;
                    }
                    case 'observaciones':{
                        this.modal2= 1;
                        this.tituloModal='Observaciones';
                        this.observacion='';
                        this.id = data['id'];
                        this.listarObservacion(this.id);
                        break;
                    }
                }
            }
        },


        mounted() {
            this.listarRuvs(1);
            this.selectFraccionamientos();
            this.getEmpresa();
        }
    }
</script>
<style>
    .form-control:disabled, .form-control[readonly] {
    background-color: rgba(0, 0, 0, 0.06);
    opacity: 1;
    font-size: 1rem;
    color: #27417b;
    }
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

    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
    }
</style>
