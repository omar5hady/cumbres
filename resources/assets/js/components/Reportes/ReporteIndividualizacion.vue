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
                        <i class="fa fa-align-justify"></i> Reporte de Escrituras &nbsp;&nbsp;
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <select class="form-control"
                                        v-model="b_proyecto"
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
                        </div>
                        <div class="form-group row">
                            <div class="col-md-10">
                                <div class="input-group">
                                    <input type="date" class="form-control col-md-5" v-model="fecha_1" @keyup.enter="listarReporte()">
                                    <input type="date" class="form-control col-md-5" v-model="fecha_2" @keyup.enter="listarReporte()">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-10">
                                <div class="input-group">
                                    <Button icon="fa fa-search" @click="listarReporte()">Buscar</Button>
                                    <a v-if="fecha_1 != '' && fecha_2 != ''"
                                        :href="'/reporte/getIndividualizadas?fecha1=' + fecha_1 + '&fecha2=' + fecha_2 + '&proyecto=' + b_proyecto + '&excel=1'"
                                        class="btn btn-success"><i class="fa fa-file-text"></i> Excel </a>
                                </div>
                            </div>
                        </div>
                        <TableComponent :cabecera="[
                            '',
                            'Nombre','Fecha de cobranza/escrituras','Dirección',
                            'Modelo','Sup. Const.','Sup. Terreno','Valor de escrituras',
                            'Precio de venta','Precio base','Terreno extra','Obra extra',
                            'Sobreprecio','Paquetes','Descuentos'
                            ]"
                        >
                            <template v-slot:tbody>
                                <tr v-for="venta in data.contado" :key="venta.id">
                                    <td class="td2">
                                        <a title="Descargar escrituras"
                                            class="btn btn-default btn-sm" v-if="venta.doc_escrituras"   v-bind:href="'expediente/downloadEscrituras/'+venta.doc_escrituras">
                                            <i class="fa fa-download"></i>
                                        </a>
                                    </td>
                                    <td class="td2"> {{ venta.nombre }} {{venta.apellidos}} </td>
                                    <td class="td2"> {{ venta.fecha }} </td>
                                    <td class="td2"> {{ venta.calle }} Num {{ venta.numero }} {{ venta.interior }} </td>
                                    <td class="td2"> {{ venta.modelo }} </td>
                                    <td class="td2"> {{ venta.construccion }} </td>
                                    <td class="td2"> {{ venta.terreno }} </td>
                                    <td class="td2"> ${{ $root.formatNumber(venta.valor_escrituras) }} </td>
                                    <td class="td2"> ${{ $root.formatNumber(venta.precio_venta - venta.desc_liquidacion) }} </td>
                                    <td class="td2"> ${{ $root.formatNumber(venta.precio_base) }} </td>
                                    <td class="td2"> ${{ $root.formatNumber(venta.precio_terreno_excedente) }} </td>
                                    <td class="td2"> ${{ $root.formatNumber(venta.precio_obra_extra) }} </td>
                                    <td class="td2"> ${{ $root.formatNumber(venta.sobreprecio) }} </td>
                                    <td class="td2"> ${{ $root.formatNumber(venta.costo_paquete) }} </td>
                                    <td class="td2"> ${{ $root.formatNumber(venta.descuento_cant + venta.desc_liquidacion + venta.descuento_promocion) }} </td>
                                </tr>
                                <tr v-for="venta in data.credito" :key="venta.id">
                                    <td class="td2">
                                        <a title="Descargar escrituras"
                                            class="btn btn-default btn-sm" v-if="venta.doc_escrituras"   v-bind:href="'expediente/downloadEscrituras/'+venta.doc_escrituras">
                                            <i class="fa fa-download"></i>
                                        </a>
                                    </td>
                                    <td class="td2"> {{ venta.nombre }} {{venta.apellidos}} </td>
                                    <td class="td2"> {{ venta.fecha }} </td>
                                    <td class="td2"> {{ venta.calle }} Num {{ venta.numero }} {{ venta.interior }} </td>
                                    <td class="td2"> {{ venta.modelo }} </td>
                                    <td class="td2"> {{ venta.construccion }} </td>
                                    <td class="td2"> {{ venta.terreno }} </td>
                                    <td class="td2"> ${{ $root.formatNumber(venta.valor_escrituras) }} </td>
                                    <td class="td2"> ${{ $root.formatNumber(venta.precio_venta - venta.desc_liquidacion) }} </td>
                                    <td class="td2"> ${{ $root.formatNumber(venta.precio_base) }} </td>
                                    <td class="td2"> ${{ $root.formatNumber(venta.precio_terreno_excedente) }} </td>
                                    <td class="td2"> ${{ $root.formatNumber(venta.precio_obra_extra) }} </td>
                                    <td class="td2"> ${{ $root.formatNumber(venta.sobreprecio) }} </td>
                                    <td class="td2"> ${{ $root.formatNumber(venta.costo_paquete) }} </td>
                                    <td class="td2"> ${{ $root.formatNumber(venta.descuento_cant + venta.desc_liquidacion + venta.descuento_promocion) }} </td>
                                </tr>
                                <tr>
                                    <th class="td2" style="text-align: left;" colspan="7">Total</th>
                                    <th class="td2"> ${{ $root.formatNumber(total1 = totalEscrituras) }}</th>
                                    <th class="td2"> ${{ $root.formatNumber(total2 = totalPrecioVenta) }} </th>
                                    <th class="td2"> ${{ $root.formatNumber(total3 = totalPrecioBase) }} </th>
                                    <th class="td2"> ${{ $root.formatNumber(total4 = totalTerreno) }} </th>
                                    <th class="td2"> ${{ $root.formatNumber(total5 = totalObraExtra) }} </th>
                                    <th class="td2"> ${{ $root.formatNumber(total6 = totalSobreprecio) }} </th>
                                    <th class="td2"> ${{ $root.formatNumber(total7 = totalPaquetes) }} </th>
                                    <th class="td2"> ${{ $root.formatNumber(total8 = totalDescuento) }} </th>
                                </tr>
                            </template>
                        </TableComponent>
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
import Button from '../Componentes/ButtonComponent.vue';
import TableComponent from '../Componentes/TableComponent.vue';
    export default {
        components:{
    Button,
    TableComponent
},
        data(){
            return{

                fecha_1:'',
                fecha_2:'',
                b_proyecto : '',
                data: [],
                total1: 0,
                total2: 0,
                total3: 0,
                total4: 0,
                total5: 0,
                total6: 0,
                total7: 0,
                total8: 0,
            }
        },
        computed:{
            totalEscrituras: function(){
                let res = 0;
                if(this.data){
                    this.data.contado.forEach(e => {
                        res+= e.valor_escrituras
                    });
                    this.data.credito.forEach(e => {
                        res+= e.valor_escrituras
                    });
                }
                return Math.round(res*100)/100;
            },
            totalPrecioVenta: function(){
                let res = 0;
                if(this.data){
                    this.data.contado.forEach(e => {
                        res+= (e.precio_venta - e.desc_liquidacion)
                    });
                    this.data.credito.forEach(e => {
                        res+= (e.precio_venta - e.desc_liquidacion)
                    });
                }
                return Math.round(res*100)/100;
            },
            totalPrecioBase: function(){
                let res = 0;
                if(this.data){
                    this.data.contado.forEach(e => {
                        res+= e.precio_base
                    });
                    this.data.credito.forEach(e => {
                        res+= e.precio_base
                    });
                }
                return Math.round(res*100)/100;
            },
            totalTerreno: function(){
                let res = 0;
                if(this.data){
                    this.data.contado.forEach(e => {
                        res+= e.precio_terreno_excedente
                    });
                    this.data.credito.forEach(e => {
                        res+= e.precio_terreno_excedente
                    });
                }
                return Math.round(res*100)/100;
            },
            totalObraExtra: function(){
                let res = 0;
                if(this.data){
                    this.data.contado.forEach(e => {
                        res+= e.precio_obra_extra
                    });
                    this.data.credito.forEach(e => {
                        res+= e.precio_obra_extra
                    });
                }
                return Math.round(res*100)/100;
            },
            totalSobreprecio: function(){
                let res = 0;
                if(this.data){
                    this.data.contado.forEach(e => {
                        res+= e.sobreprecio
                    });
                    this.data.credito.forEach(e => {
                        res+= e.sobreprecio
                    });
                }
                return Math.round(res*100)/100;
            },
            totalPaquetes: function(){
                let res = 0;
                if(this.data){
                    this.data.contado.forEach(e => {
                        res+= e.costo_paquete
                    });
                    this.data.credito.forEach(e => {
                        res+= e.costo_paquete
                    });
                }
                return Math.round(res*100)/100;
            },
            totalDescuento: function(){
                let res = 0;
                if(this.data){
                    this.data.contado.forEach(e => {
                        res+= (e.descuento_cant + e.desc_liquidacion + e.descuento_promocion)
                    });
                    this.data.credito.forEach(e => {
                        res+= (e.descuento_cant + e.desc_liquidacion + e.descuento_promocion)
                    });
                }
                return Math.round(res*100)/100;
            },
        },
        methods : {
            /**Metodo para mostrar los registros */
            listarReporte(){
                let me = this;

                me.arrayEscrituras = [];
                me.arraycontadoSinEscrituras = [];

                var url = '/reporte/getIndividualizadas?fecha1=' + me.fecha_1
                        + '&fecha2=' + me.fecha_2
                        + '&proyecto=' + me.b_proyecto
                        + '&excel=0';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.data = respuesta;

                })
                .catch(function (error) {
                    console.log(error);
                });
            },

        },
        mounted() {
            this.$root.selectFraccionamientos();
            // this.listarReporte()
        }
    }
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
