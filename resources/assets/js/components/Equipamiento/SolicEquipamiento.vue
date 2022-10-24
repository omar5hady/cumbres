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
                        <i class="fa fa-align-justify"></i>Solicitud de Equipamiento
                        <button type="button" @click="abrirModal('nuevo')" class="btn btn-success">
                            <i class="icon-plus"></i>&nbsp;Nueva Solicitud
                        </button>
                    </div>

                    <div class="info-center" v-if="loading">
                        <LoadingComponentVue></LoadingComponentVue>
                    </div>
                    <div class="card-body" v-else>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control" v-model="b_empresa" >
                                        <option value="">Empresa constructora</option>
                                        <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                    </select>
                                </div>
                            </div>
                        </div>
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
                                <select class="col-md-4 form-control" v-model="b_status">
                                    <option value="">Status</option>
                                    <option value="0">Rechazado</option>
                                    <option value="1">Pendiente</option>
                                    <option value="2">En proceso de colocación</option>
                                    <option value="3">En Revisión</option>
                                    <option value="4">Aprobado</option>
                                    <option value="5">Cancelado</option>

                                </select>
                                <button type="submit" class="btn btn-primary" @click="getSolicitudes(1)">
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                        <TableComponent :cabecera="[
                                '','Proyecto','Etapa','Manzana','Lote','Modelo','Proveedor','Equipamiento',
                                'Costo del equipamiento','Anticipo','Comp. de pago 1','Fecha programada','Fecha fin de instalación',
                                'Status','# Dias de inst.','Total pagado','Pendiente','Liquidacion','Comp. de pago 2',
                                'Imprimir Recepción','Observaciones',
                        ]">
                            <template v-slot:tbody>
                                <tr v-for="s in arraySolicitudes.data" :key="s.id">
                                    <td></td>
                                    <td class="td2" v-text="s.proyecto"></td>
                                    <td class="td2" v-text="s.etapa"></td>
                                    <td class="td2" v-text="s.manzana"></td>
                                    <td class="td2">{{ (s.sublote) ? `${s.num_lote}-${s.sublote}` : s.num_lote }}</td>
                                    <td class="td2" v-text="s.modelo"></td>
                                    <td class="td2" v-text="s.proveedor"></td>
                                    <td class="td2" v-text="s.equipamiento"></td>
                                    <td class="td2" style="width:40%">
                                        <input type="text" pattern="\d*" @keyup.enter="updateCosto(s,$event.target.value)" :value="s.costo|currency" maxlength="10" v-on:keypress="$root.isNumber($event)" class="form-control" >
                                    </td>

                                    <template>
                                        <td @click="abrirModal('anticipo', s)" class="td2">
                                            <a href="#" v-if="s.fecha_anticipo && s.anticipo_cand==0"
                                                v-text=" this.moment(s.fecha_anticipo).locale('es').format('DD/MMM/YYYY') + ': '+ '$'+ $root.formatNumber(s.anticipo)"></a>
                                            <a v-else href="#" v-text="'Sin anticipo'"></a>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-info" title="Subir archivo">
                                                <i class="fa fa-cloud-upload"></i>
                                            </button>
                                            <a v-if="s.comp_pago_1" :href="s.comp_pago_1" class="btn btn-sm btn-primary" title="Descargar archivo">
                                                <i class="fa fa-cloud-download"></i>
                                            </a>
                                        </td>
                                    </template>

                                    <td @click="abrirModal('colocacion', s)" class="td2">
                                        <a v-if="s.fecha_colocacion" href="#" v-text=" this.moment(s.fecha_colocacion).locale('es').format('DD/MMM/YYYY')"></a>
                                        <a v-else href="#" v-text="'Sin fecha de colocación'"></a>
                                    </td>

                                    <td class="td2">
                                        {{ (s.fin_instalacion != null)
                                            ? this.moment(s.fin_instalacion).locale('es').format('DD/MMM/YYYY')
                                            : 'Sin fecha de instalación' }}
                                    </td>
                                    <td  class="td2">
                                        <span v-if="s.status == '0'" class="badge badge-warning">Rechazado</span>
                                        <span v-if="s.status == '1'" class="badge badge-primary">Pendiente</span>
                                        <span v-if="s.status == '2'" class="badge badge-primary">En proceso de colocación</span>
                                        <span v-if="s.status == '3'" class="badge badge-primary">En Revisión</span>
                                        <span v-if="s.status == '4'" class="badge badge-success">Aprobado</span>
                                        <span v-if="s.status == '5'" class="badge badge-danger">Cancelado</span>
                                    </td>
                                    <td>
                                        <span v-if="!s.fin_instalacion && s.fecha_anticipo || s.fin_instalacion && s.fecha_anticipo && s.status != '4'"
                                            class="badge badge-warning" v-text="s.diferenciaIni"></span>
                                        <span v-else-if="s.fin_instalacion && s.fecha_anticipo && s.status == '4'"
                                            class="badge badge-success" v-text="s.diferenciaFin"></span>
                                    </td>
                                    <td class="td2" v-text="'$'+$root.formatNumber(s.anticipo + s.liquidacion)"></td>
                                    <td class="td2" v-text="'$'+$root.formatNumber(s.costo - s.anticipo - s.liquidacion)"></td>

                                    <template><!--Liquidacion-->
                                        <td>
                                            <a v-if="s.fecha_liquidacion" href="#" @click="abrirModal('liquidacion', s)" v-text="
                                                this.moment(s.fecha_liquidacion).locale('es').format('DD/MMM/YYYY') + ': '+ '$'+$root.formatNumber(s.liquidacion)"></a>
                                            <a v-else href="#" v-text="'Sin Liquidacion'"></a>
                                            <button v-if="s.status == 4 && fecha_liquidacion == null" title="Realizar liquidacion" type="button"
                                                @click="abrirModal('liquidacion', s)" class="btn btn-success pull-right">
                                                <i class="fa fa-check-square-o"></i> Generar
                                            </button>
                                        </td>

                                        <td class="text-center">
                                            <button class="btn btn-sm btn-info" title="Subir archivo">
                                                <i class="fa fa-cloud-upload"></i>
                                            </button>
                                            <a v-if="s.comp_pago_2" :href="s.comp_pago_2" class="btn btn-sm btn-primary" title="Descargar archivo">
                                                <i class="fa fa-cloud-download"></i>
                                            </a>
                                        </td>
                                    </template>

                                    <td><!--Imprimir Recepción-->
                                        Imprimir recepcion
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
                    </div>
                    <!-- Modal para nuevo registro -->
                    <ModalComponent v-if="modal== 1"
                        :titulo="tituloModal"
                        @closeModal="cerrarModal()"
                    >
                        <template v-slot:body>
                            <!-- Seccion para buscar el lote -->
                            <div class="form-group row">
                                <label class="col-md-2 form-control-label" for="text-input">Proyecto</label>
                                <div class="col-md-4">
                                    <select class="form-control" v-model="buscadores.proyecto"
                                        @change="$root.selectEtapa(buscadores.proyecto)"
                                    >
                                        <option value="">Proyecto</option>
                                        <option
                                            v-for="proyecto in $root.$data.proyectos" :key="proyecto.id"
                                            :value="proyecto.id" v-text="proyecto.nombre"
                                        ></option>
                                    </select>
                                </div>
                                <label class="col-md-2 form-control-label" for="text-input">Etapa</label>
                                <div class="col-md-4">
                                    <select class="form-control" v-model="buscadores.etapa"
                                        @change="selectManzana(buscadores.etapa)"
                                    >
                                        <option value="">Etapa</option>
                                        <option
                                            v-for="etapa in $root.$data.etapas"
                                            :key="etapa.id"
                                            :value="etapa.num_etapa"
                                            v-text="etapa.num_etapa"
                                        ></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 form-control-label" for="text-input">Manzanas</label>
                                <div class="col-md-4">
                                    <select class="form-control" v-model="buscadores.manzana"
                                    @change="selectLotes(   buscadores.manzana,
                                                            buscadores.etapa,
                                                            buscadores.proyecto
                                                        )">
                                        <option value="">Manzana</option>
                                        <option v-for="manzanas in arrayManzanas" :key="manzanas.manzana" :value="manzanas.manzana" v-text="manzanas.manzana"></option>
                                    </select>
                                </div>
                                <label class="col-md-2 form-control-label" for="text-input">Lote</label>
                                <div class="col-md-4">
                                    <select class="form-control" v-model="datosSolicitud.lote_id">
                                        <option value="">Lote</option>
                                        <template v-for="lotes in arrayLotes">
                                            <option v-if="lotes.sublote == null" :key="lotes.id" :value="lotes.id" v-text="lotes.num_lote"></option>
                                            <option v-else :key="lotes.id" :value="lotes.id" v-text="lotes.num_lote + ' '+ lotes.sublote"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>
                            <!--  -->
                            <hr>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Proveedor</label>
                                <div class="col-md-6">
                                    <select class="form-control" v-model="datosSolicitud.proveedor_id" @change="selectEquipamiento(datosSolicitud.proveedor_id)">
                                        <option value="">Seleccione</option>
                                        <option v-for="proveedor in arrayProveedores" :key="proveedor.id" :value="proveedor.id" v-text="proveedor.proveedor"></option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Equipamiento</label>
                                <div class="col-md-6">
                                    <select class="form-control" v-model="datosSolicitud.equipamiento_id">
                                        <option value="">Seleccione</option>
                                        <option v-for="equipamiento in arrayEquipamientos" :key="equipamiento.id" :value="equipamiento.id" v-text="equipamiento.equipamiento"></option>
                                    </select>
                                </div>
                            </div>
                        </template>
                        <template v-slot:buttons-footer>
                            <button type="button" v-if="datosSolicitud.equipamiento_id != '' && datosSolicitud.lote_id != ''"
                                class="btn btn-success" @click="storeEquipamiento()">Solicitar Equipamiento
                            </button>
                        </template>
                    </ModalComponent>

                    <ModalComponent v-if="modal == 2"
                        :titulo="tituloModal"
                        @closeModal="cerrarModal()"
                    >
                        <template v-slot:body>
                            <!-- Seccion para buscar el lote -->
                            <template v-if="tipoAccion == 1">
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Fecha de anticipo</label>
                                    <div class="col-md-4">
                                        <input type="date" v-model="datosSolicitud.fecha_anticipo" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">$ Anticipo</label>
                                    <div class="col-md-4">
                                        <input type="text" pattern="\d*" maxlength="10" v-on:keypress="$root.isNumber($event)"
                                            v-model="datosSolicitud.anticipo" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <p class="form-control"> {{ $root.formatNumber(datosSolicitud.anticipo) }}</p>
                                    </div>
                                </div>
                            </template>
                            <template v-if="tipoAccion == 2">
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Fecha de colocación</label>
                                    <div class="col-md-4">
                                        <input type="date" v-model="datosSolicitud.fecha_colocacion" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Observación</label>
                                    <div class="col-md-8">
                                        <textarea class="form-control" cols="40" rows="3" v-model="observacion"></textarea>
                                    </div>
                                </div>
                            </template>
                        </template>
                        <template v-slot:buttons-footer>
                            <button type="button"
                                class="btn btn-success" v-if="tipoAccion == 1" @click="updateSolicitud('updateAnticipo')">Guardar cambios
                            </button>
                            <button type="button"
                                class="btn btn-success" v-if="tipoAccion == 2" @click="updateSolicitud('updateColocacion')">Guardar cambios
                            </button>
                        </template>
                    </ModalComponent>

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
                empresas: [],
                arrayProveedores: [],
                arrayEquipamientos: [],
                arrayManzanas: [],
                arrayLotes: [],
                arraySolicitudes: [],
                arrayObs: [],

                b_empresa: '',
                b_etapa: '',
                b_manzana: '',
                b_lote: '',
                b_modelo: '',
                b_proyecto: '',
                b_status: '',
                loading: false,

                modal: 0,
                tituloModal: '',
                tipoAccion: 0,

                datosSolicitud: {},
                buscadores: {},
                observacion: ''
            }
        },
        computed:{
        },


        methods : {
            getSolicitudes(page){
                let me = this;

                me.arraySolicitudes=[];
                var url = '/equip-lotes?page='+page
                    + '&b_proyecto=' + me.b_proyecto
                    + '&b_etapa=' + me.b_etapa
                    + '&b_modelo=' + me.b_modelo
                    + '&b_lote=' + me.b_lote
                    + '&b_status=' + me.b_status
                    + '&b_empresa=' + me.b_empresa
                ;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arraySolicitudes = respuesta;
                })
                .catch(function (error) {
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
            storeEquipamiento(){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.post('/equip-lotes',{
                    'datosSolicitud':this.datosSolicitud

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
                        title: 'Equipamiento solicitado correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },
            updateCosto(solicitud, valor){
                solicitud.costo = valor;
                this.datosSolicitud = solicitud;
                this.updateSolicitud('updateCosto');
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
            selectProveedores(){
                let me = this;

                me.arrayProveedores=[];
                var url = '/select_proveedor';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayProveedores = respuesta.proveedor;
                })
                .catch(function (error) {
                    console.log(error);
                });
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

            selectEquipamiento(proveedor){
                let me = this;

                me.arrayEquipamientos=[];
                var url = '/select_equipamientos?buscar=' + proveedor;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEquipamientos = respuesta.equipamiento;
                })
                .catch(function (error) {
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
            cerrarModal(){
                this.getSolicitudes(this.arraySolicitudes.current_page)
                this.modal = 0;
                this.tituloModal = '';
                this.datosSolicitud = {};
                this.buscadores = {};
                this.arrayEquipamientos = [];
                this.arrayManzanas = [];
                this.arrayLotes = [];
                this.observacion = '';
            },
            abrirModal(accion,data={}){
                this.datosSolicitud = data;
                switch(accion){
                    case 'nuevo':{
                        this.modal = 1;
                        this.tituloModal = 'Nueva solicitud de Equipamiento';
                        this.datosSolicitud = {
                            lote_id: '',
                            proveedor_id: '',
                            equipamiento_id: ''
                        };
                        this.buscadores = {
                            proyecto : '',
                            etapa : '',
                            manzana : ''
                        };
                        break;
                    }
                    case 'anticipo':{
                        this.modal = 2;
                        this.tituloModal = 'Anticipo de la solicitud';
                        this.tipoAccion = 1;
                        break;
                    }
                    case 'colocacion':{
                        this.modal = 2;
                        this.tipoAccion = 'Programar fecha de colocación';
                        this.tipoAccion = 2;
                        break;
                    }
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
            this.selectProveedores();
            this.getEmpresa();
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
