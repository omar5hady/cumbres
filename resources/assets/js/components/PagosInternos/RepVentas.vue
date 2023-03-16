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
                    <i class="fa fa-align-justify"></i> Reporte de Ventas
                    <Button v-if="vista==1"
                     :btnClass="'btn-danger'" :icon="'fa fa-window-close'" @click="cerrarDetalle()">
                        Cerrar
                    </Button>
                </div>
                <div class="card-body">
                    <template v-if="vista==0">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input class="form-control col-md-2" type="text" disabled placeholder="Proyecto:">
                                    <select class="form-control col-md-5"
                                        v-model="b_proyecto"
                                        @change="$root.selectEtapa(b_proyecto)"
                                    >
                                        <option value="">Fraccionamiento</option>
                                        <option
                                            v-for="proyecto in $root.$data.proyectos"
                                            :key="proyecto.id"
                                            :value="proyecto.id"
                                            v-text="proyecto.nombre"
                                        ></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input class="form-control col-md-2" type="text" disabled placeholder="Etapa:">
                                    <select class="form-control col-md-5" v-model="b_etapa">
                                        <option value="">Etapa</option>
                                        <option
                                            v-for="etapa in $root.$data.etapas"
                                            :key="etapa.id"
                                            :value="etapa.id"
                                            v-text="etapa.num_etapa"
                                        ></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input class="form-control col-md-2" type="text" disabled placeholder="Fecha:">
                                    <input type="date" class="form-control col-md-4" @keyup.enter="getVentas(1)" v-model="f_ini" placeholder="Fecha inicial">
                                    <input type="date" class="form-control col-md-4" @keyup.enter="getVentas(1)" v-model="f_fin" placeholder="Fecha inicial">
                                </div>
                            </div>

                            <div class="col-md-10">
                                <div class="input-group">
                                    <Button :btnClass="'btn-primary'" :icon="'fa fa-search'" @click="getVentas(1)">
                                        Buscar
                                    </Button>
                                </div>
                            </div>
                        </div>

                        <TableComponent :cabecera="[
                            'Proyecto',
                            'Etapa',
                            'Cant. Ventas',
                        ]">
                            <template v-slot:tbody>
                                <tr v-for="venta in listadoEtapas.data" :key="venta.id">
                                    <td class="td2">
                                        {{venta.proyecto}}
                                    </td>
                                    <td class="td2" v-text="venta.etapa"></td>
                                    <td>
                                        <Button @click="verVentas(venta.ventas, venta.num_ventas)" :icon="'fa fa-handshake-o'">{{venta.num_ventas}}</Button>
                                    </td>
                                </tr>
                            </template>
                        </TableComponent>

                        <Nav :current="listadoEtapas.current_page ? listadoEtapas.current_page : 1"
                            :last="listadoEtapas.last_page ? listadoEtapas.last_page : 1"
                            @changePage="getVentas">
                        </Nav>
                    </template>
                    <template v-else>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <center>
                                    <h3>Ventas en {{arrayVentas[0].proyecto}} - {{arrayVentas[0].etapa}}</h3>
                                </center>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <TableComponent :cabecera="[
                                    '', 'Manzana', 'Lote', 'Precio base','T.E', 'O.E', 'Ubic.', 'Paq.', 'Total'
                                ]">
                                    <template v-slot:tbody>
                                        <tr v-for="(venta, index) in arrayVentas" :key="venta.id">
                                            <td class="td2">
                                                {{index+1}}
                                            </td>
                                            <td class="td2" v-text="venta.manzana"></td>
                                            <td class="td2">
                                                {{
                                                    venta.sublote ? venta.num_lote + ' ' +venta.sublote
                                                    : venta.num_lote
                                                }}
                                            </td>
                                            <td class="td2"> ${{ $root.formatNumber(venta.precio_base) }}</td>
                                            <td class="td2"> ${{ $root.formatNumber(venta.terreno) }}</td>
                                            <td class="td2"> ${{ $root.formatNumber(venta.obra_ext) }}</td>
                                            <td class="td2"> ${{ $root.formatNumber(venta.ubicacion) }}</td>
                                            <td class="td2"> ${{ $root.formatNumber(venta.costo_paquete) }}</td>
                                            <td class="td2"> ${{ $root.formatNumber(venta.precio_venta) }}</td>
                                        </tr>
                                        <tr>
                                            <th class="totales"  colspan="3">Total: </th>
                                            <th class="totales" >${{ $root.formatNumber(total_base) }}</th>
                                            <th class="totales" >${{ $root.formatNumber(total_terreno) }}</th>
                                            <th class="totales" >${{ $root.formatNumber(total_obra) }}</th>
                                            <th class="totales" >${{ $root.formatNumber(total_ubicacion) }}</th>
                                            <th class="totales" >${{ $root.formatNumber(total_paquete) }}</th>
                                            <th class="totales" >${{ $root.formatNumber(total_venta) }}</th>
                                        </tr>
                                        <tr>
                                            <th colspan="3">Promedio: </th>
                                            <th>${{ $root.formatNumber(total_base/num_ventas) }}</th>
                                            <th>${{ $root.formatNumber(total_terreno/num_ventas) }}</th>
                                            <th>${{ $root.formatNumber(total_obra/num_ventas) }}</th>
                                            <th>${{ $root.formatNumber(total_ubicacion/num_ventas) }}</th>
                                            <th>${{ $root.formatNumber(total_paquete/num_ventas) }}</th>
                                            <th>${{ $root.formatNumber(total_venta/num_ventas) }}</th>
                                        </tr>
                                    </template>
                                </TableComponent>
                            </div>
                        </div>
                    </template>
                </div>

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

export default {
    components: {
        ModalComponent,
        TableComponent,
        Button, RowModal,Nav,
    },
    props: {
    },
    data() {
        return {
            listadoEtapas : [],
            arrayVentas : [],
            vista : 0,
            num_ventas : 0,

            b_proyecto : '',
            b_etapa : '',
            f_ini : '',
            f_fin : '',

            total_base : 0,
            total_terreno : 0,
            total_obra: 0,
            total_ubicacion : 0,
            total_paquete: 0,
            total_venta : 0,
        };
    },
    computed: {
    },
    methods: {
        getVentas(page){
            let me = this;
            me.listadoEtapas=[];
            var url = '/presupuestos/getVentas?page=' + page
                + '&proyecto=' + me.b_proyecto
                + '&etapa=' + me.b_etapa
                + '&f_ini=' + me.f_ini
                + '&f_fin=' + me.f_fin
            axios.get(url).then(function (response) {
                me.listadoEtapas = response.data;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        verVentas(ventas, num_ventas){
            let me = this;
            me.arrayVentas = [...ventas]
            me.num_ventas = num_ventas

            me.arrayVentas.forEach(e=>{
                e.precio_base = (e.precio_base - e.descuento_promocion + e.descuento_terreno + e.descuento_ubicacion);
                e.terreno = e.terreno_exc - e.descuento_terreno
                e.ubicacion = e.sobreprecio - e.descuento_ubicacion

                me.total_base += e.precio_base;
                me.total_terreno += e.terreno;
                me.total_obra += e.obra_ext;
                me.total_ubicacion += e.ubicacion;
                me.total_paquete += e.costo_paquete;
                me.total_venta += e.precio_venta
            })

            me.vista = 1;
        },
        cerrarDetalle(){
            this.total_base = 0
            this.total_terreno = 0
            this.total_obra = 0
            this.total_ubicacion = 0
            this.total_paquete = 0
            this.total_venta = 0
            this.vista = 0;
            this.arrayVentas = [];
        }
    },
    mounted() {
        this.getVentas(1);
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
    .totales{
        background-color: #0C3456;
        color: #fff;
    }
</style>

