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
                        <i class="fa fa-align-justify"></i>Equipamientos por revisar
                    </div>

                    <div class="info-center" v-if="loading">
                        <LoadingComponentVue></LoadingComponentVue>
                    </div>
                    <div class="card-body" v-else>
                        <template v-if="vista == 1">
                            <div class="form-group row">
                                <select class="form-control col-md-4"
                                    v-model="b_proyecto"
                                    @change="$root.selectEtapa(b_proyecto),
                                            $root.selectModelo(b_proyecto)"
                                >
                                    <option value="">Fraccionamiento</option>
                                    <option
                                        v-for="proyecto in $root.$data.proyectos"
                                        :key="proyecto.id"
                                        :value="proyecto.id"
                                        v-text="proyecto.nombre"
                                    ></option>
                                </select>
                                <select class="form-control col-md-3" v-model="b_etapa">
                                    <option value="">Etapa</option>
                                    <option
                                        v-for="etapa in $root.$data.etapas"
                                        :key="etapa.id"
                                        :value="etapa.id"
                                        v-text="etapa.num_etapa"
                                    ></option>
                                </select>
                                <select
                                    class="form-control col-md-4"
                                    v-model="b_modelo"
                                >
                                    <option value="">Modelo</option>
                                    <option
                                        v-for="modelo in $root.$data.modelos"
                                        :key="modelo.id"
                                        :value="modelo.id"
                                        v-text="modelo.nombre"
                                    ></option>
                                </select>

                                <input type="text" class="col-md-4 form-control"
                                    v-model="b_manzana" placeholder="Manzana"
                                />
                                <input type="text" class="col-md-3 form-control"
                                    v-model="b_lote" placeholder="Num. Lote"
                                />

                                <div class="input-group">
                                    <button type="submit" class="btn btn-primary" @click="getSolicitudes(1)">
                                        <i class="fa fa-search"></i> Buscar
                                    </button>
                                </div>

                            </div>
                            <TableComponent :cabecera="[
                                    'Proyecto','Etapa','Manzana','Lote','Modelo','Proveedor','Equipamiento',
                                    'Fecha fin de instalación','Status','Recepción','Observaciones'
                            ]">
                                <template v-slot:tbody>
                                    <tr v-for="s in arraySolicitudes.data" :key="s.id">
                                        <td class="td2" v-text="s.proyecto"></td>
                                        <td class="td2" v-text="s.etapa"></td>
                                        <td class="td2" v-text="s.manzana"></td>
                                        <td class="td2">{{ (s.sublote) ? `${s.num_lote}-${s.sublote}` : s.num_lote }}</td>
                                        <td class="td2" v-text="s.modelo"></td>
                                        <td class="td2" v-text="s.proveedor"></td>
                                        <td class="td2" v-text="s.equipamiento"></td>
                                        <td class="td2">
                                            <a href="#" v-text=" this.moment(s.fin_instalacion).locale('es').format('DD/MMM/YYYY')"></a>
                                        </td>
                                        <td  class="td2">
                                            <span v-if="s.status == '3'" class="badge badge-primary">En Revisión</span>
                                        </td>

                                        <td class="td2"><!--Imprimir Recepción-->
                                            <button class="btn btn-dark" @click="realizarRecepcion(s)">
                                                <i class="icon-check"></i> Realizar recepción
                                            </button>
                                        </td>

                                        <td class="text-center">
                                            <button class="btn btn-info" @click="abrirModal('obs',s)" title="Ver Observaciones">
                                                <i class="icon-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </TableComponent>
                            <nav>
                                <ul class="pagination">
                                    <li class="page-item"
                                        v-if="arraySolicitudes.current_page > 5"
                                        @click="getSolicitudes(1)"
                                    >
                                        <a class="page-link" href="#">Inicio</a>
                                    </li>
                                    <li class="page-item"
                                        v-if="arraySolicitudes.current_page > 1"
                                        @click="getSolicitudes(arraySolicitudes.current_page - 1)"
                                    >
                                        <a class="page-link" href="#">Ant</a>
                                    </li>

                                    <li class="page-item"
                                        v-if="arraySolicitudes.current_page - 3 >= 1"
                                        @click="getSolicitudes(arraySolicitudes.current_page - 3)"
                                    >
                                        <a class="page-link" href="#"
                                            v-text="arraySolicitudes.current_page - 3"
                                        ></a>
                                    </li>
                                    <li
                                        class="page-item" v-if="arraySolicitudes.current_page - 2 >= 1"
                                        @click="getSolicitudes(arraySolicitudes.current_page - 2)"
                                    >
                                        <a class="page-link" href="#"
                                            v-text="arraySolicitudes.current_page - 2"
                                        ></a>
                                    </li>
                                    <li class="page-item" v-if="arraySolicitudes.current_page - 1 >= 1"
                                        @click="getSolicitudes(arraySolicitudes.current_page - 1)"
                                    >
                                        <a class="page-link" href="#"
                                            v-text="arraySolicitudes.current_page - 1"
                                        ></a>
                                    </li>
                                    <li class="page-item active">
                                        <a class="page-link" href="#"
                                            v-text="arraySolicitudes.current_page"
                                        ></a>
                                    </li>
                                    <li class="page-item"
                                        v-if="arraySolicitudes.current_page + 1 <= arraySolicitudes.last_page"
                                    >
                                        <a class="page-link" href="#"
                                            @click="getSolicitudes(arraySolicitudes.current_page + 1)"
                                            v-text="arraySolicitudes.current_page + 1"
                                        ></a>
                                    </li>
                                    <li class="page-item"
                                        v-if="arraySolicitudes.current_page + 2 <=arraySolicitudes.last_page"
                                    >
                                        <a class="page-link" href="#"
                                            @click="getSolicitudes(arraySolicitudes.current_page + 2)"
                                            v-text="arraySolicitudes.current_page + 2"
                                        ></a>
                                    </li>
                                    <li class="page-item"
                                        v-if="arraySolicitudes.current_page + 3 <= arraySolicitudes.last_page"
                                    >
                                        <a class="page-link" href="#"
                                            @click="getSolicitudes(arraySolicitudes.current_page + 3)"
                                            v-text="arraySolicitudes.current_page + 3"
                                        ></a>
                                    </li>

                                    <li class="page-item"
                                        v-if="arraySolicitudes.current_page < arraySolicitudes.last_page"
                                        @click="getSolicitudes(arraySolicitudes.current_page + 1)"
                                    >
                                        <a class="page-link" href="#">Sig</a>
                                    </li>
                                    <li class="page-item"
                                        v-if="arraySolicitudes.current_page < 5 && arraySolicitudes.last_page > 5"
                                        @click="getSolicitudes(arraySolicitudes.last_page)"
                                    >
                                        <a class="page-link" href="#">Ultimo</a>
                                    </li>
                                </ul>
                            </nav>
                        </template>
                        <template v-if="vista == 2">
                            <template v-if="datosSolicitud.tipoRecepcion == 1">
                                <div class="form-group row border">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <center> <h5>Supervisión de acabados</h5> </center>
                                        </div>
                                    </div>
                                    <div class="col-md-6" v-for="(acabado,index) in checklist.acabados" :key="index+acabado.subcategoria">
                                        <div class="form-group row border">
                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i>&nbsp;&nbsp;{{acabado.subcategoria}}</a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item" v-for="data in acabado.info" :key="data.concepto">
                                                    <a class="nav-link"> <input v-model="data.check1" type="checkbox" value="1"/>&nbsp;&nbsp;{{data.concepto}}</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <center> <h5>Revisión de Puertas, Alacenas y Cajones</h5> </center>
                                        </div>
                                    </div>
                                    <div class="col-md-4" v-for="(rev,index) in checklist.revisiones" :key="index+rev.subcategoria">
                                        <div class="form-group row border">
                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i>&nbsp;&nbsp;{{rev.subcategoria}}</a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item" v-for="data in rev.info" :key="data.concepto">
                                                    <a class="nav-link"> <input v-model="data.check1" type="checkbox" value="1"/>&nbsp;&nbsp;{{data.concepto}}</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <center> <h5>Otras Revisione</h5> </center>
                                        </div>
                                    </div>
                                    <div class="col-md-6" v-for="(rev,index) in checklist.otros" :key="index+rev.subcategoria">
                                        <div class="form-group row border">
                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i>&nbsp;&nbsp;{{rev.subcategoria}}</a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item" v-for="data in rev.info" :key="data.concepto">
                                                    <a class="nav-link"> <input v-model="data.check1" type="checkbox" value="1"/>&nbsp;&nbsp;{{data.concepto}}</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </template>


                            <template v-if="datosSolicitud.tipoRecepcion == 2">
                                <div class="form-group row border">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <center> <h5>Supervisión de acabados</h5> </center>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <center> <h6>Recamara Derecha</h6> </center>
                                        </div>
                                        <div class="form-group row border" v-for="(acabado, index) in checklist.acabados" :key="index+acabado.subcategoria">
                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i>&nbsp;&nbsp;{{acabado.subcategoria}}</a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item" v-for="data in acabado.info" :key="data.concepto">
                                                    <a class="nav-link"> <input v-model="data.check1" type="checkbox" value="1"/>&nbsp;&nbsp;{{data.concepto}}</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <center> <h6>Recamara Izquierda</h6> </center>
                                        </div>
                                        <div class="form-group row border" v-for="(acabado,index) in checklist.acabados" :key="index+acabado.subcategoria">
                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i>&nbsp;&nbsp;{{acabado.subcategoria}}</a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item" v-for="data in acabado.info" :key="data.concepto">
                                                    <a class="nav-link"> <input v-model="data.check2" type="checkbox" value="1"/>&nbsp;&nbsp;{{data.concepto}}</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <center> <h6>Recamara Principal</h6> </center>
                                        </div>
                                        <div class="form-group row border" v-for="(acabado,index) in checklist.acabados" :key="index+acabado.subcategoria">
                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i>&nbsp;&nbsp;{{acabado.subcategoria}}</a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item" v-for="data in acabado.info" :key="data.concepto">
                                                    <a class="nav-link"> <input v-model="data.check3" type="checkbox" value="1"/>&nbsp;&nbsp;{{data.concepto}}</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <center> <h5>Supervisión de Interiores</h5> </center>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <center> <h6>Recamara Derecha</h6> </center>
                                        </div>
                                        <div class="form-group row border" v-for="(interior,index) in checklist.interiores" :key="index+interior.subcategoria">
                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i>&nbsp;&nbsp;{{interior.subcategoria}}</a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item" v-for="data in interior.info" :key="data.concepto">
                                                    <a class="nav-link"> <input v-model="data.check1" type="checkbox" value="1"/>&nbsp;&nbsp;{{data.concepto}}</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <center> <h6>Recamara Izquierda</h6> </center>
                                        </div>
                                        <div class="form-group row border" v-for="(interior,index) in checklist.interiores" :key="index+interior.subcategoria">
                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i>&nbsp;&nbsp;{{interior.subcategoria}}</a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item" v-for="data in interior.info" :key="data.concepto">
                                                    <a class="nav-link"> <input v-model="data.check2" type="checkbox" value="1"/>&nbsp;&nbsp;{{data.concepto}}</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <center> <h6>Recamara Principal</h6> </center>
                                        </div>
                                        <div class="form-group row border" v-for="(interior,index) in checklist.interiores" :key="index+interior.subcategoria">
                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i>&nbsp;&nbsp;{{interior.subcategoria}}</a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item" v-for="data in interior.info" :key="data.concepto">
                                                    <a class="nav-link"> <input v-model="data.check3" type="checkbox" value="1"/>&nbsp;&nbsp;{{data.concepto}}</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <center> <h5>Otras Revisiones</h5> </center>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <center> <h6>Recamara Derecha</h6> </center>
                                        </div>
                                        <div class="form-group row border" v-for="(otro,index) in checklist.otros" :key="index+otro.subcategoria">
                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i>&nbsp;&nbsp;{{otro.subcategoria}}</a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item" v-for="data in otro.info" :key="data.concepto">
                                                    <a class="nav-link"> <input v-model="data.check1" type="checkbox" value="1"/>&nbsp;&nbsp;{{data.concepto}}</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <center> <h6>Recamara Izquierda</h6> </center>
                                        </div>
                                        <div class="form-group row border" v-for="(otro,index) in checklist.otros" :key="index+otro.subcategoria">
                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i>&nbsp;&nbsp;{{otro.subcategoria}}</a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item" v-for="data in otro.info" :key="data.concepto">
                                                    <a class="nav-link"> <input v-model="data.check2" type="checkbox" value="1"/>&nbsp;&nbsp;{{data.concepto}}</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <center> <h6>Recamara Principal</h6> </center>
                                        </div>
                                        <div class="form-group row border" v-for="(otro,index) in checklist.otros" :key="index+otro.subcategoria">
                                            <a class="nav-link nav-dropdown-toggle"><i class="fa fa-certificate fa-spin"></i>&nbsp;&nbsp;{{otro.subcategoria}}</a>
                                            <ul class="nav-dropdown-items">
                                                <li class="nav-item" v-for="data in otro.info" :key="data.concepto">
                                                    <a class="nav-link"> <input v-model="data.check3" type="checkbox" value="1"/>&nbsp;&nbsp;{{data.concepto}}</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                </div>
                            </template>

                            <div class="form-group row border">

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-md-3 form-control-label" for="text-input">Observaciones:</label>
                                        <textarea rows="3" cols="30" v-model="checklist.obs" class="form-control" placeholder="Observaciones"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-9">
                                    <button type="button" class="btn btn-secondary" @click="cerrarRecepcion()"> Cerrar </button>
                                </div>

                                <div class="col-md-3" v-if="checklist.obs != '' && almacenando == false">
                                    <button type="button" class="btn btn-success" @click="finalizarRevision()"> Finalizar Revisión </button>
                                </div>
                            </div>
                        </template>
                    </div>


                    <ModalComponent v-if="modal == 3"
                        :titulo="tituloModal"
                        @closeModal="cerrarModal()"
                    >
                        <template v-slot:body>
                            <!-- Seccion para buscar el lote -->
                            <template>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Nueva observación</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="observacion" class="form-control">
                                    </div>

                                    <div class="col-md-3">
                                        <button class="btn btn-primary" @click="storeObservacion()">Guardar</button>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <TableComponent :cabecera="['Usuario','Observación','Fecha']">
                                            <template v-slot:tbody>
                                                <tr v-for="obs in arrayObs" :key="obs.id">
                                                    <td>{{obs.usuario}}</td>
                                                    <td>{{obs.observacion}}</td>
                                                    <td>{{obs.created_at}}</td>
                                                </tr>
                                            </template>
                                        </TableComponent>
                                    </div>
                                </div>
                            </template>
                        </template>
                    </ModalComponent>

                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
     </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    import ModalComponent from '../Componentes/ModalComponent.vue';
    import LoadingComponentVue from '../Componentes/LoadingComponent.vue'
    import TableComponent from '../Componentes/TableComponent.vue';

    export default {
        components:{
            TableComponent,
            ModalComponent,
            LoadingComponentVue
        },
        data(){
            return{
                arrayManzanas: [],
                arrayLotes: [],
                arraySolicitudes: [],
                arrayObs: [],

                b_etapa: '',
                b_manzana: '',
                b_lote: '',
                b_modelo: '',
                b_proyecto: '',
                b_status: 3,
                loading: false,

                almacenando: false,

                modal: 0,
                tituloModal: '',
                tipoAccion: 0,

                datosSolicitud: {},
                buscadores: {},
                observacion: '',

                vista:1,

                checklist: {}
            }
        },
        computed:{
        },


        methods : {
            getSolicitudes(page){
                let me = this;
                me.loading = true;

                me.arraySolicitudes=[];
                var url = '/equip-lotes?page='+page
                    + '&b_proyecto=' + me.b_proyecto
                    + '&b_etapa=' + me.b_etapa
                    + '&b_modelo=' + me.b_modelo
                    + '&b_lote=' + me.b_lote
                    + '&b_status=' + me.b_status
                ;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arraySolicitudes = respuesta;
                    me.loading = false;
                })
                .catch(function (error) {
                    me.loading = false;
                    console.log(error);
                });
            },
            storeObservacion(){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.post('/equip-lotes/storeObservacion',{
                    'solicitud_id': this.datosSolicitud.id,
                    'observacion': this.observacion
                }).then(function (response){
                    me.getSolicitudes(1);
                    me.cerrarModal();
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Observación guardada correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },
            updateSolicitud(accion){
                let me = this;
                axios.put(`/equip-lotes/${me.datosSolicitud.id}`,{
                        'solicitud': me.datosSolicitud,
                        'accion': accion,
                        'observacion': this.observacion
                    }).then(function (response){
                        me.getSolicitudes();
                        me.cerrarModal();
                        const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });

                        toast({
                            type: 'success',
                            title: 'Cambios guardados correctamente.'
                        })
                    }).catch(function (error){
                        console.log(error);
                        me.getSolicitudes();
                        me.cerrarModal();
                });
            },
            cerrarRecepcion(){
                this.getSolicitudes(this.arraySolicitudes.current_page);
                this.vista = 1;
                this.checklist = {};
            },

            selectManzana(etapa){
                let me = this;
                me.arrayManzanas=[];
                var url = '/select_manzanas_disp?buscar=' + etapa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                     me.arrayManzanas = respuesta.lotes_manzanas;

                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectLotes(manzana,etapa,fraccionamiento){
                let me = this;
                me.arrayLotes=[];
                var url = '/select_lotes_disp?buscar=' + manzana + '&buscar2=' + etapa + '&buscar3=' + fraccionamiento;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                     me.arrayLotes = respuesta.lotes_disp;

                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            generarRecepCocina(){
                this.checklist.acabados = [
                    {
                        'subcategoria' : 'Cubierta',
                        'info' : [
                            {
                                'categoria' : 'Supervición de Acabados',
                                'subcategoria': 'Cubierta',
                                'concepto': 'Uniones',
                                'check1' : 1,
                                'check2' : 0,
                                'check3' : 0
                            },
                            {
                                'categoria' : 'Supervición de Acabados',
                                'subcategoria': 'Cubierta',
                                'concepto': 'Uso silicón',
                                'check1' : 1,
                                'check2' : 0,
                                'check3' : 0
                            },
                            {
                                'categoria' : 'Supervición de Acabados',
                                'subcategoria': 'Cubierta',
                                'concepto': 'Cortes',
                                'check1' : 1,
                                'check2' : 0,
                                'check3' : 0
                            },
                        ]
                    },
                    {
                        'subcategoria' : 'Puertas',
                        'info' : [
                             {
                                'categoria' : 'Supervición de Acabados',
                                'subcategoria': 'Puertas',
                                'concepto': 'Alineadas',
                                'check1' : 1,
                                'check2' : 0,
                                'check3' : 0
                            },
                            {
                                'categoria' : 'Supervición de Acabados',
                                'subcategoria': 'Puertas',
                                'concepto': 'Cantos',
                                'check1' : 1,
                                'check2' : 0,
                                'check3' : 0
                            },
                        ]
                    },
                ];

                this.checklist.revisiones = [
                    //Puertas
                    {
                        'subcategoria' : 'Puertas',
                        'info': [
                            {
                                'categoria' : 'Revisión de puertas, Alacenas y Cajones',
                                'subcategoria': 'Puertas',
                                'concepto': 'Daños',
                                'check1' : 1,
                                'check2' : 0,
                                'check3' : 0
                            },
                            {
                                'categoria' : 'Revisión de puertas, Alacenas y Cajones',
                                'subcategoria': 'Puertas',
                                'concepto': 'Tornillos aju',
                                'check1' : 1,
                                'check2' : 0,
                                'check3' : 0
                            },
                            {
                                'categoria' : 'Revisión de puertas, Alacenas y Cajones',
                                'subcategoria': 'Puertas',
                                'concepto': 'Abatimiento',
                                'check1' : 1,
                                'check2' : 0,
                                'check3' : 0
                            },
                            {
                                'categoria' : 'Revisión de puertas, Alacenas y Cajones',
                                'subcategoria': 'Puertas',
                                'concepto': 'Limpieza',
                                'check1' : 1,
                                'check2' : 0,
                                'check3' : 0
                            },
                            {
                                'categoria' : 'Revisión de puertas, Alacenas y Cajones',
                                'subcategoria': 'Puertas',
                                'concepto': 'Jaladeras',
                                'check1' : 1,
                                'check2' : 0,
                                'check3' : 0
                            },
                            {
                                'categoria' : 'Revisión de puertas, Alacenas y Cajones',
                                'subcategoria': 'Puertas',
                                'concepto': 'Gomas cierr',
                                'check1' : 1,
                                'check2' : 0,
                                'check3' : 0
                            },
                        ]
                    },
                    //Cajones
                    {
                        'subcategoria' : 'Cajones',
                        'info': [
                            {
                                'categoria' : 'Revisión de puertas, Alacenas y Cajones',
                                'subcategoria': 'Cajones',
                                'concepto': 'Uniones',
                                'check1' : 1,
                                'check2' : 0,
                                'check3' : 0
                            },
                            {
                                'categoria' : 'Revisión de puertas, Alacenas y Cajones',
                                'subcategoria': 'Cajones',
                                'concepto': 'Silicón/Past',
                                'check1' : 1,
                                'check2' : 0,
                                'check3' : 0
                            },
                            {
                                'categoria' : 'Revisión de puertas, Alacenas y Cajones',
                                'subcategoria': 'Cajones',
                                'concepto': 'Limpieza',
                                'check1' : 1,
                                'check2' : 0,
                                'check3' : 0
                            },
                            {
                                'categoria' : 'Revisión de puertas, Alacenas y Cajones',
                                'subcategoria': 'Cajones',
                                'concepto': 'Jaladeras',
                                'check1' : 1,
                                'check2' : 0,
                                'check3' : 0
                            },
                            {
                                'categoria' : 'Revisión de puertas, Alacenas y Cajones',
                                'subcategoria': 'Cajones',
                                'concepto': 'Cantos',
                                'check1' : 1,
                                'check2' : 0,
                                'check3' : 0
                            },
                            {
                                'categoria' : 'Revisión de puertas, Alacenas y Cajones',
                                'subcategoria': 'Cajones',
                                'concepto': 'Rieles',
                                'check1' : 1,
                                'check2' : 0,
                                'check3' : 0
                            },
                            {
                                'categoria' : 'Revisión de puertas, Alacenas y Cajones',
                                'subcategoria': 'Cajones',
                                'concepto': 'Estantes',
                                'check1' : 1,
                                'check2' : 0,
                                'check3' : 0
                            },
                            {
                                'categoria' : 'Revisión de puertas, Alacenas y Cajones',
                                'subcategoria': 'Cajones',
                                'concepto': 'Pzas compl',
                                'check1' : 1,
                                'check2' : 0,
                                'check3' : 0
                            },
                        ]
                    },
                    //Alacenas
                    {
                        'subcategoria' : 'Alacenas',
                        'info': [
                            {
                                'categoria' : 'Revisión de puertas, Alacenas y Cajones',
                                'subcategoria': 'Alacenas',
                                'concepto': 'Entrepaños',
                                'check1' : 1,
                                'check2' : 0,
                                'check3' : 0
                            },
                            {
                                'categoria' : 'Revisión de puertas, Alacenas y Cajones',
                                'subcategoria': 'Alacenas',
                                'concepto': 'Pistones',
                                'check1' : 1,
                                'check2' : 0,
                                'check3' : 0
                            },
                            {
                                'categoria' : 'Revisión de puertas, Alacenas y Cajones',
                                'subcategoria': 'Alacenas',
                                'concepto': 'Jaladeras',
                                'check1' : 1,
                                'check2' : 0,
                                'check3' : 0
                            },
                            {
                                'categoria' : 'Revisión de puertas, Alacenas y Cajones',
                                'subcategoria': 'Alacenas',
                                'concepto': 'Hoyo micro',
                                'check1' : 1,
                                'check2' : 0,
                                'check3' : 0
                            },
                            {
                                'categoria' : 'Revisión de puertas, Alacenas y Cajones',
                                'subcategoria': 'Alacenas',
                                'concepto': 'Cantos',
                                'check1' : 1,
                                'check2' : 0,
                                'check3' : 0
                            },
                            {
                                'categoria' : 'Revisión de puertas, Alacenas y Cajones',
                                'subcategoria': 'Alacenas',
                                'concepto': 'Limpieza',
                                'check1' : 1,
                                'check2' : 0,
                                'check3' : 0
                            },
                            {
                                'categoria' : 'Revisión de puertas, Alacenas y Cajones',
                                'subcategoria': 'Alacenas',
                                'concepto': 'Parches tor',
                                'check1' : 1,
                                'check2' : 0,
                                'check3' : 0
                            },
                        ]
                    }

                ];

                this.checklist.otros = [
                    {
                        'subcategoria' : 'Estufa',
                        'info': [
                            {
                                'categoria' : 'Otras Revisiones',
                                'subcategoria': 'Estufa',
                                'concepto': 'Instalación',
                                'check1' : 1,
                                'check2' : 0,
                                'check3' : 0
                            },
                            {
                                'categoria' : 'Otras Revisiones',
                                'subcategoria': 'Estufa',
                                'concepto': 'Pzas extra',
                                'check1' : 1,
                                'check2' : 0,
                                'check3' : 0
                            },
                            {
                                'categoria' : 'Otras Revisiones',
                                'subcategoria': 'Estufa',
                                'concepto': 'Manuales',
                                'check1' : 1,
                                'check2' : 0,
                                'check3' : 0
                            },
                            {
                                'categoria' : 'Otras Revisiones',
                                'subcategoria': 'Estufa',
                                'concepto': 'Daños',
                                'check1' : 1,
                                'check2' : 0,
                                'check3' : 0
                            },
                        ]
                    },
                    {
                        'subcategoria' : 'Tarja',
                        'info' : [
                            {
                                'categoria' : 'Otras Revisiones',
                                'subcategoria': 'Tarja',
                                'concepto': 'Daños',
                                'check1' : 1,
                                'check2' : 0,
                                'check3' : 0
                            },
                            {
                                'categoria' : 'Otras Revisiones',
                                'subcategoria': 'Estufa',
                                'concepto': 'Pzas extra',
                                'check1' : 1,
                                'check2' : 0,
                                'check3' : 0
                            },
                        ]
                    }
                ]
            },
            generarRecepCloset(){
                this.checklist.acabados = [
                    {
                        'subcategoria' : 'Puertas',
                        'info': [
                            {
                                'categoria' : 'Supervición de Acabados',
                                'subcategoria': 'Puertas',
                                'concepto': 'Alineadas',
                                'check1' : 1,
                                'check2' : 1,
                                'check3' : 1,
                            },
                            {
                                'categoria' : 'Supervición de Acabados',
                                'subcategoria': 'Puertas',
                                'concepto': 'Limpieza',
                                'check1' : 1,
                                'check2' : 1,
                                'check3' : 1,
                            },
                            {
                                'categoria' : 'Supervición de Acabados',
                                'subcategoria': 'Puertas',
                                'concepto': 'Uso silicón',
                                'check1' : 1,
                                'check2' : 1,
                                'check3' : 1,
                            },
                        ]
                    },
                    {
                        'subcategoria' : 'Cajones',
                        'info': [
                            {
                                'categoria' : 'Supervición de Acabados',
                                'subcategoria': 'Cajones',
                                'concepto': 'Alineados',
                                'check1' : 1,
                                'check2' : 1,
                                'check3' : 1,
                            },
                            {
                                'categoria' : 'Supervición de Acabados',
                                'subcategoria': 'Cajones',
                                'concepto': 'Cantos',
                                'check1' : 1,
                                'check2' : 1,
                                'check3' : 1,
                            },
                            {
                                'categoria' : 'Supervición de Acabados',
                                'subcategoria': 'Cajones',
                                'concepto': 'Uniones',
                                'check1' : 1,
                                'check2' : 1,
                                'check3' : 1,
                            },
                            {
                                'categoria' : 'Supervición de Acabados',
                                'subcategoria': 'Cajones',
                                'concepto': 'Silicón/Past',
                                'check1' : 1,
                                'check2' : 1,
                                'check3' : 1,
                            },
                            {
                                'categoria' : 'Supervición de Acabados',
                                'subcategoria': 'Cajones',
                                'concepto': 'Limpieza',
                                'check1' : 1,
                                'check2' : 1,
                                'check3' : 1,
                            },
                            {
                                'categoria' : 'Supervición de Acabados',
                                'subcategoria': 'Cajones',
                                'concepto': 'Tornillos aju',
                                'check1' : 1,
                                'check2' : 1,
                                'check3' : 1,
                            },
                            {
                                'categoria' : 'Supervición de Acabados',
                                'subcategoria': 'Cajones',
                                'concepto': 'Parches tor',
                                'check1' : 1,
                                'check2' : 1,
                                'check3' : 1,
                            },
                        ]
                    }
                ];

                this.checklist.interiores = [
                    {
                        'subcategoria' : 'Puertas',
                        'info': [
                            {
                                'categoria' : 'Supervición de Interiores',
                                'subcategoria': 'Puertas',
                                'concepto': 'Tiradores',
                                'check1' : 1,
                                'check2' : 1,
                                'check3' : 1,
                            },
                            {
                                'categoria' : 'Supervición de Interiores',
                                'subcategoria': 'Puertas',
                                'concepto': 'Funcionamiento',
                                'check1' : 1,
                                'check2' : 1,
                                'check3' : 1,
                            },
                        ]
                    },
                    {
                        'subcategoria' : 'Cajones',
                        'info' : [
                                    {
                                'categoria' : 'Supervición de Interiores',
                                'subcategoria': 'Cajones',
                                'concepto': 'Jaladeras',
                                'check1' : 1,
                                'check2' : 1,
                                'check3' : 1,
                            },
                            {
                                'categoria' : 'Supervición de Interiores',
                                'subcategoria': 'Cajones',
                                'concepto': 'Rieles',
                                'check1' : 1,
                                'check2' : 1,
                                'check3' : 1,
                            },
                            {
                                'categoria' : 'Supervición de Interiores',
                                'subcategoria': 'Cajones',
                                'concepto': 'Estantes',
                                'check1' : 1,
                                'check2' : 1,
                                'check3' : 1,
                            },
                            {
                                'categoria' : 'Supervición de Interiores',
                                'subcategoria': 'Cajones',
                                'concepto': 'Entrepaños',
                                'check1' : 1,
                                'check2' : 1,
                                'check3' : 1,
                            },
                            {
                                'categoria' : 'Supervición de Interiores',
                                'subcategoria': 'Cajones',
                                'concepto': 'Tubos colga',
                                'check1' : 1,
                                'check2' : 1,
                                'check3' : 1,
                            },
                            {
                                'categoria' : 'Supervición de Interiores',
                                'subcategoria': 'Cajones',
                                'concepto': 'Daños',
                                'check1' : 1,
                                'check2' : 1,
                                'check3' : 1,
                            },
                            {
                                'categoria' : 'Supervición de Interiores',
                                'subcategoria': 'Cajones',
                                'concepto': 'Abre correct',
                                'check1' : 1,
                                'check2' : 1,
                                'check3' : 1,
                            },
                            {
                                'categoria' : 'Supervición de Interiores',
                                'subcategoria': 'Cajones',
                                'concepto': 'Pzas compl',
                                'check1' : 1,
                                'check2' : 1,
                                'check3' : 1,
                            },
                            {
                                'categoria' : 'Supervición de Interiores',
                                'subcategoria': 'Cajones',
                                'concepto': 'Abatimiento',
                                'check1' : 1,
                                'check2' : 1,
                                'check3' : 1,
                            },
                            {
                                'categoria' : 'Supervición de Interiores',
                                'subcategoria': 'Cajones',
                                'concepto': 'Visagras',
                                'check1' : 1,
                                'check2' : 1,
                                'check3' : 1,
                            },
                        ]
                    },
                ];

                this.checklist.otros = [
                    {
                        'subcategoria': 'Paredes',
                        'info': [
                            {
                                'categoria' : 'Otras Revisiones',
                                'subcategoria': 'Paredes',
                                'concepto': 'Daños',
                                'check1' : 1,
                                'check2' : 1,
                                'check3' : 1,
                            },
                            {
                                'categoria' : 'Otras Revisiones',
                                'subcategoria': 'Paredes',
                                'concepto': 'Limpieza',
                                'check1' : 1,
                                'check2' : 1,
                                'check3' : 1,
                            },
                        ]
                    },
                    {
                        'subcategoria': 'Clósets',
                        'info': [
                            {
                                'categoria' : 'Otras Revisiones',
                                'subcategoria': 'Clósets',
                                'concepto': 'Cenefa sup',
                                'check1' : 1,
                                'check2' : 1,
                                'check3' : 1,
                            },
                            {
                                'categoria' : 'Otras Revisiones',
                                'subcategoria': 'Clósets',
                                'concepto': 'Cenefas inf',
                                'check1' : 1,
                                'check2' : 1,
                                'check3' : 1,
                            },
                            {
                                'categoria' : 'Otras Revisiones',
                                'subcategoria': 'Clósets',
                                'concepto': 'Color madera',
                                'check1' : 1,
                                'check2' : 1,
                                'check3' : 1,
                            },
                            {
                                'categoria' : 'Otras Revisiones',
                                'subcategoria': 'Clósets',
                                'concepto': 'Alineac jalad',
                                'check1' : 1,
                                'check2' : 1,
                                'check3' : 1,
                            },
                            {
                                'categoria' : 'Otras Revisiones',
                                'subcategoria': 'Clósets',
                                'concepto': 'Pandeadura',
                                'check1' : 1,
                                'check2' : 1,
                                'check3' : 1,
                            },
                            {
                                'categoria' : 'Otras Revisiones',
                                'subcategoria': 'Clósets',
                                'concepto': 'Soporte ajust',
                                'check1' : 1,
                                'check2' : 1,
                                'check3' : 1,
                            },
                        ]
                    }
                ]
            },
            realizarRecepcion(solicitud){
                this.datosSolicitud = solicitud;
                this.vista = 2;
                this.checklist = {
                    'obs' : ''
                }
                switch(this.datosSolicitud.tipoRecepcion){
                    case 1:{//Cocina
                        this.generarRecepCocina();
                        break;
                    }
                    case 2:{//Vestidores
                        this.generarRecepCloset();
                        break;
                    }
                }
            },

            finalizarRevision(){
                let me = this;
                me.almacenando = true
                axios.put(`/equip-lotes/${me.datosSolicitud.id}`,{
                        'solicitud': me.datosSolicitud,
                        'accion': 'updateRevision',
                        'revision': me.checklist,
                        'observacion': this.observacion
                    }).then(function (response){
                        me.cerrarRecepcion();
                        me.almacenando = false;
                        const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });

                        toast({
                            type: 'success',
                            title: 'Recepción realizada correctamente.'
                        })
                    }).catch(function (error){
                        me.almacenando = false;
                        me.cerrarRecepcion();
                });
            },

            cerrarModal(){
                this.getSolicitudes(this.arraySolicitudes.current_page)
                this.modal = 0;
                this.tituloModal = '';
                this.datosSolicitud = {};
                this.buscadores = {};
                this.arrayManzanas = [];
                this.arrayLotes = [];
                this.observacion = '';
            },
            abrirModal(accion,data={}){
                this.datosSolicitud = data;
                switch(accion){
                    case 'obs':{
                        this.modal = 3;
                        this.arrayObs = this.datosSolicitud.obs;
                        break;
                    }
                }
            }
        },

        mounted() {
            this.getSolicitudes(1);
            this.$root.selectFraccionamientos();
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

    .info-center{
        display: flex;
        justify-content: center;
        width: 100% !important;
    }
</style>
