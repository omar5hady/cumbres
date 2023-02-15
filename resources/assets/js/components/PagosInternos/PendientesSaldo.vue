<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <strong><a style="color:#FFFFFF;" href="/">Home</a></strong>
            </li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card scroll-box">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Solicitud de Pago
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input class="form-control col-md-2" type="text" disabled placeholder="Empresa:">
                                <select class="form-control col-md-6" v-model="b_empresa">
                                    <option value="">Seleccione</option>
                                    <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-group">
                                <input class="form-control col-md-2" type="text" disabled placeholder="Proveedor:">
                                <input type="text" class="form-control col-md-6" @keyup.enter="indexSolicitudes(1)" v-model="b_proveedor" placeholder="Proveedor a buscar">
                            </div>
                        </div>

                        <div class="col-md-10">
                            <div class="input-group">
                                <Button :btnClass="'btn-primary'" :icon="'fa fa-search'" @click="indexSolicitudes(1)">
                                    Buscar
                                </Button>
                            </div>
                        </div>
                    </div>

                    <TableComponent :cabecera="[
                        'Proveedor',
                        'Solicitante',
                        'Obra',
                        '',
                        'Cargo',
                        'Subconcepto',
                        'Obs.',
                        'Fecha solic',
                        'Saldo pendiente',
                        'Status'
                    ]">
                        <template v-slot:tbody>
                            <tr v-for="solic in arrayPendientes.data" :key="solic.id" :class="{ 'table-danger' : solic.extraordinario }">
                                <td class="td2">
                                    {{solic.proveedor}}
                                </td>
                                <td class="td2" v-text="solic.solicitante"></td>
                                <td>
                                    {{solic.sub_obra ? solic.obra + ' ' + solic.sub_obra : solic.obra}}
                                </td>
                                <td class="td2">
                                    {{ solic.contrato_id ? 'Contrato: ' + solic.contrato_id +'. ' : ''}}
                                    {{ solic.lote_id ?
                                        solic.sublote ? 'Mnz: ' + solic.manzana + ' Lote: ' + solic.num_lote + ' ' + solic.sublote
                                        : 'Mnz: ' + solic.manzana + ' Lote: ' + solic.num_lote : ''
                                    }}
                                </td>

                                <td class="td2" v-text="solic.cargo"></td>
                                <td class="td2" v-text="solic.concepto"></td>
                                <td class="td2" v-text="solic.observacion"></td>
                                <td class="td2"
                                    v-text="this.moment(solic.created_at).locale('es').format('DD/MMM/YYYY')">
                                </td>
                                <th class="td2" v-text="'$'+$root.formatNumber(solic.saldo)"></th>
                                <td>
                                    {{
                                        solic.status == 0 ? 'Nueva'
                                        : solic.status == 1 ? 'En Proceso'
                                        : solic.status == 2 ? 'Aprobada'
                                        : solic.status == 3 ? 'Por Pagar'
                                        : 'Pagada: ' + solic.fecha_pago
                                    }}
                                </td>
                            </tr>
                        </template>
                    </TableComponent>

                    <Nav :current="arrayPendientes.current_page ? arrayPendientes.current_page : 1"
                        :last="arrayPendientes.last_page ? arrayPendientes.last_page : 1"
                        @changePage="indexSolicitudes">
                    </Nav>

                </div>

                <!--Inicio del modal observaciones-->
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
    </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
import ModalComponent from "../Componentes/ModalComponent.vue";
import TableComponent from "../Componentes/TableComponent.vue";
import Button from "../Componentes/ButtonComponent.vue";
import Nav from "../Componentes/NavComponent.vue";
import RowModal from "../Componentes/ComponentesModal/RowModalComponent.vue";
import vSelect from 'vue-select';

export default {
    components: {
        ModalComponent,
        TableComponent,
        Button, RowModal,Nav,
        vSelect
    },
    props: {
    },
    data() {
        return {
            empresas : [],
            arrayPendientes : [],
            b_proveedor : '',
            b_empresa : '',
        };
    },
    computed: {
    },
    methods: {
        getEmpresa(){
            let me = this;
            me.empresas=[];
            var url = '/lotes/empresa/select';
            axios.get(url).then(function (response) {
                var respuesta = response;
                me.empresas = respuesta.data.empresas;
            })
            .catch(function (error) {
            });
        },
        indexSolicitudes(page){
            let me = this;
            me.arrayPendientes=[];
            var url = '/sp/indexPendientes?page=' + page
                +'&proveedor='+ me.b_proveedor
                +'&empresa='+ me.b_empresa;;
            axios.get(url).then(function (response) {
                me.arrayPendientes = response.data;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
    },
    mounted() {
        this.indexSolicitudes(1);
        this.getEmpresa();
        // this.$root.selectFraccionamientos();
    }
};
</script>
<style>
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

