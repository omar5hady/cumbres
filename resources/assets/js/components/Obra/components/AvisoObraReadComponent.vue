<template>
    <div class="card-body">
        <div class="form-group row border">
            <div class="col-md-9">
                <div class="form-group">
                    <label style="color:#2271b3;" for=""
                        ><strong> Contratista </strong></label
                    >
                    <p v-text="data.contratista"></p>
                </div>
            </div>
            <div class="col-md-3">
                <label style="color:#2271b3;" for=""
                    ><strong>Clave</strong>
                </label>
                <p v-text="data.clave"></p>
            </div>
            <div class="col-md-3">
                <label style="color:#2271b3;" for=""
                    ><strong>Fecha de inicio</strong></label
                >
                <p v-text="data.f_ini"></p>
            </div>
            <div class="col-md-3">
                <label style="color:#2271b3;" for=""
                    ><strong>Fecha de termino </strong></label
                >
                <p v-text="data.f_fin"></p>
            </div>
            <div class="col-md-3">
                <label style="color:#2271b3;" for=""
                    ><strong>% Anticipo </strong></label
                >
                <p v-text="data.anticipo + '%'"></p>
            </div>
            <div class="col-md-3">
                <label style="color:#2271b3;" for=""
                    ><strong>% Costo Indirecto </strong></label
                >
                <p v-text="data.costo_indirecto_porcentaje + '%'"></p>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label style="color:#2271b3;" for=""
                        ><strong>Fraccionamiento </strong></label
                    >
                    <p v-text="data.fraccionamiento"></p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label style="color:#2271b3;" for=""
                        ><strong>Dirección del proyecto:</strong></label
                    >
                    <p v-text="data.direccion_proy"></p>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label style="color:#2271b3;"
                        ><strong>Total de Anticipo</strong></label
                    >
                    <div class="form-inline">
                        <p v-text="'$' + $root.formatNumber(data.total_anticipo)"></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-12">
                <TableComponent
                    :cabecera="[
                        'Descripcion',
                        'Lote',
                        'Manzana',
                        'M&sup2;',
                        'Costo Directo',
                        'Costo Indirecto',
                        'Obra extra',
                        'Importe',
                        'Obs'
                    ]"
                >
                    <template v-slot:tbody>
                        <template v-if="data.lotesContrato.length">
                            <tr
                                v-for="detalle in data.lotesContrato"
                                :key="detalle.id"
                            >
                                <td v-text="detalle.descripcion"></td>
                                <td v-text="detalle.lote"></td>
                                <td v-text="detalle.manzana"></td>
                                <td
                                    style="text-align: right;"
                                    v-text="detalle.construccion"
                                ></td>
                                <td
                                    style="text-align: right;"
                                    v-text="
                                        '$' +
                                            $root.formatNumber(detalle.costo_directo)
                                    "
                                ></td>
                                <td
                                    style="text-align: right;"
                                    v-text="
                                        '$' +
                                            $root.formatNumber(
                                                detalle.costo_indirecto
                                            )
                                    "
                                ></td>
                                <td
                                    style="text-align: right;"
                                    v-text="
                                        '$' + $root.formatNumber(detalle.obra_extra)
                                    "
                                ></td>
                                <td align="right">
                                    {{
                                        "$" +
                                            $root.formatNumber(
                                                parseFloat(
                                                    detalle.costo_directo
                                                ) +
                                                    parseFloat(
                                                        detalle.costo_indirecto
                                                    )
                                            )
                                    }}
                                </td>
                                <td v-text="detalle.observacion"></td>
                            </tr>

                            <tr style="background-color: #CEECF5;">
                                <td align="right" colspan="4">
                                    <strong>{{
                                        $root.formatNumber(data.total_construccion = totalConstruccion)
                                    }}</strong>
                                </td>
                                <td align="right">
                                    <strong
                                        >${{
                                            $root.formatNumber(
                                                (data.total_costo_directo = totalCostoDirecto)
                                            )
                                        }}</strong
                                    >
                                </td>
                                <td align="right">
                                    <strong
                                        >${{
                                            $root.formatNumber(
                                                (data.total_costo_indirecto = totalCostoIndirecto)
                                            )
                                        }}</strong
                                    >
                                </td>
                                <td align="right" colspan="2">
                                    <strong
                                        >${{
                                            $root.formatNumber(
                                                (data.total_importe = totalImporte)
                                            )
                                        }}</strong
                                    >
                                </td>
                            </tr>
                        </template>
                        <template v-else>
                            <tr>
                                <td colspan="9">
                                    No hay lotes seleccionados
                                </td>
                            </tr>
                        </template>
                    </template>
                </TableComponent>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-1">
                <button
                    type="button"
                    class="btn btn-secondary"
                    @click="$emit('close')"
                >
                    Cerrar
                </button>
            </div>
            <div class="col-md-9">
                <a
                    class="btn btn-success"
                    v-bind:href="'/iniobra/relacion/excel/' + data.id"
                >
                    <i></i>Exportar relacion en excel
                </a>
            </div>
            <div class="col-md-2">
                <button
                    type="button"
                    class="btn btn-primary"
                    @click="$emit('print')"
                >
                    <i class="fa fa-print"></i>&nbsp; Imprimir Contrato
                </button>
            </div>
        </div>
    </div>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
import TableComponent from "../../Componentes/TableComponent.vue";
export default {
    props: {
        data: {
            type: Object,
            required: true,
            default: {
                id: "",
                fraccionamiento_id: 0,
                contratista_id: 0,
                contratista: "",
                clave: "",
                f_ini: new Date().toISOString().substr(0, 10),
                f_fin: "",
                calle1: "",
                calle2: "",
                total_importe: 0,
                total_costo_directo: 0,
                total_costo_indirecto: 0,
                anticipo: 0,
                total_anticipo: 0,
                lotesContrato: [],
                emp_constructora: "CONCRETANIA",
                costo_indirecto_porcentaje: 0,
                tipo: "Vivienda",
                iva: 0,
                descripcion_larga: "",
                descripcion_corta: "",
                total_construccion: 0,
                direccion_proy: ""
            }
        }
    },
    data() {
        return {
            proceso: false
        };
    },
    components: {
        TableComponent
    },
    computed: {
        totalCostoDirecto: function() {
            var resultado_costo_directo = 0.0;
            for (var i = 0; i < this.data.lotesContrato.length; i++) {
                resultado_costo_directo =
                    parseFloat(resultado_costo_directo) +
                    parseFloat(this.data.lotesContrato[i].costo_directo);
            }
            return Math.round(resultado_costo_directo * 100) / 100;
        },
        totalCostoIndirecto: function() {
            var resultado_costo_indirecto = 0.0;
            for (var i = 0; i < this.data.lotesContrato.length; i++) {
                resultado_costo_indirecto =
                    parseFloat(resultado_costo_indirecto) +
                    parseFloat(this.data.lotesContrato[i].costo_indirecto);
            }
            return Math.round(resultado_costo_indirecto * 100) / 100;
        },
        totalImporte: function() {
            var resultado_importe_total = 0.0;
            for (var i = 0; i < this.data.lotesContrato.length; i++) {
                resultado_importe_total =
                    parseFloat(resultado_importe_total) +
                    parseFloat(this.data.lotesContrato[i].costo_directo) +
                    parseFloat(this.data.lotesContrato[i].costo_indirecto);
            }
            return Math.round(resultado_importe_total * 100) / 100;
        },
        totalConstruccion: function() {
            var resultado_construccion_total = 0.0;
            for (var i = 0; i < this.data.lotesContrato.length; i++) {
                resultado_construccion_total =
                    parseFloat(resultado_construccion_total) +
                    parseFloat(this.data.lotesContrato[i].construccion);
            }
            return Math.round(resultado_construccion_total * 100) / 100;
        },
        totalSuperficie: function() {
            var resultado_construccion_total = 0.0;
            for (var i = 0; i < this.data.lotesContrato.length; i++) {
                resultado_construccion_total =
                    parseFloat(resultado_construccion_total) +
                    parseFloat(this.data.lotesContrato[i].superficie);
            }
            return Math.round(resultado_construccion_total * 100) / 100;
        }
    },

    methods: {

    },
    mounted() {

    }
};
</script>
<style>
.div-error {
    display: flex;
    justify-content: center;
}
.text-error {
    color: red !important;
    font-weight: bold;
}
</style>
