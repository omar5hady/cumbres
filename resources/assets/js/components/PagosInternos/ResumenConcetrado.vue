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
                    <Button v-if="detalle == 1"
                        :btnClass="'btn-secondary'"
                        @click="detalle = 0"
                        :icon="'icon-close'" >Regresar</Button>
                </div>
                <div class="card-body">
                    <div class="form-group row" v-if="detalle == 0">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input class="form-control col-md-2" type="text" disabled placeholder="Empresa:">
                                <select class="form-control col-md-6" v-model="b_empresa">
                                    <option value="">Seleccione</option>
                                    <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="input-group">
                                <input class="form-control col-md-2" type="text" disabled placeholder="Obra:">
                                <select class="form-control" v-model="b_obra">
                                    <option value="">Seleccione</option>
                                    <option value="OFICINA">OFICINA</option>
                                    <option value="NUEVOS PROYECTOS">NUEVOS PROYECTOS</option>
                                    <option v-for="proyecto in $root.$data.proyectos" :key="proyecto.id" :value="proyecto.nombre" v-text="proyecto.nombre"></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-group">
                                <input class="form-control col-md-2" type="text" disabled placeholder="Fecha:">
                                <input type="date" class="form-control col-md-4" @keyup.enter="indexConcentrado(1)" v-model="b_fecha1">
                                <input type="date" class="form-control col-md-4" @keyup.enter="indexConcentrado(1)" v-model="b_fecha2">
                            </div>
                        </div>

                        <div class="col-md-10">
                            <div class="input-group">
                                <Button :btnClass="'btn-primary'" :icon="'fa fa-search'" @click="indexConcentrado()">
                                    Buscar
                                </Button>
                            </div>
                        </div>
                    </div>

                    <TableComponent
                        v-if="detalle == 0"
                        :cabecera="[
                            '',
                            'Cargo',
                            'Monto',
                        ]">
                        <template v-slot:tbody>
                            <tr v-for="solic in arrayConcentrado" :key="solic.cargo">
                                <td>
                                   <Button v-if="solic.detalle.length"
                                        :btnClass="'btn-primary'"
                                        :icon="'icon-eye'"
                                        @click="verDetalle(solic)"
                                        title="Ver Detalle">
                                    </Button>
                                </td>
                                <td class="td2">
                                    {{solic.cargo}}
                                </td>
                                <th class="td2" v-text="'$'+$root.formatNumber(solic.total)"></th>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <th style="color:darkblue">
                                    ${{ $root.formatNumber(totalPagado) }}
                                </th>
                            </tr>
                        </template>
                    </TableComponent>
                    <TableComponent v-else
                        :cabecera="[
                            'Concepto',
                            'Especificación',
                            'Fecha pago',
                            'Monto',
                            'Solicitante',
                            'Proveedor',
                        ]"
                    >
                        <template v-slot:tbody>
                            <tr v-for="det in arrayDetalle" :key="det.id">
                                <td class="td2">
                                    {{det.concepto}}
                                </td>
                                <td class="td2">
                                    {{det.observacion}}
                                </td>
                                <td class="td2">
                                    {{det.fecha_pago}}
                                </td>
                                <th class="td2" v-text="'$'+$root.formatNumber(det.pago)"></th>
                                <td class="td2">
                                    {{ det.solicitante }}
                                </td>
                                <td class="td2">
                                    {{ det.proveedor }}
                                </td>
                            </tr>
                        </template>
                    </TableComponent>

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
import TableComponent from "../Componentes/TableComponent.vue";
import Button from "../Componentes/ButtonComponent.vue";
// import Nav from "../Componentes/NavComponent.vue";

export default {
    components: {
        TableComponent,
        Button,
    },
    props: {
    },
    data() {
        return {
            empresas : [],
            arrayConcentrado : [],
            arrayDetalle : [],
            detalle : 0,
            b_fecha1 : '',
            b_fecha2 : '',
            b_empresa : '',
            b_obra : ''
        };
    },
    computed: {
        totalPagado: function(){
            let total = 0;
            this.arrayConcentrado.map( (e)=> total+= e.total );
            return total;
        },
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
        indexConcentrado(){
            let me = this;
            me.arrayConcentrado=[];
            var url = '/sp/indexConcentrado?fecha1='+ me.b_fecha1
                + '&fecha2=' + me.b_fecha2
                + '&obra=' + me.b_obra
                +'&empresa='+ me.b_empresa;
            axios.get(url).then(function (response) {
                me.arrayConcentrado = response.data;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        verDetalle(solicitud){
            this.arrayDetalle = [...solicitud.detalle]
            this.detalle = 1;
        }
    },
    mounted() {
        this.indexConcentrado();
        this.getEmpresa();
        this.$root.selectFraccionamientos();
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

