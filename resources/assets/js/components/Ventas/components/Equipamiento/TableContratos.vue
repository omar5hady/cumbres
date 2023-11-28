<template >
    <TableComponent
    :cabecera="[
        'Clave','Cliente','Fraccionamiento','Etapa',
        'Manzana', 'Lote', 'Equipamiento', 'Avance',
        'Crédito','Fecha firma escrituras', 'Fecha avaluo',
        'Depositado','Status', 'Fecha entrega (Obra)', ''
    ]">
        <template v-slot:tbody>
            <tr v-for="contrato in arrayData"
                    @dblclick="$emit('verHistorial',contrato.folio)"
                    :key="contrato.folio" title="Doble click">
                <td class="td2" v-text="contrato.folio"></td>
                <td class="td2">
                    <a href="#" v-text="contrato.nombre_cliente"></a>
                </td>
                <td class="td2" v-text="contrato.proyecto"></td>
                <td class="td2" v-text="contrato.etapa"></td>
                <td class="td2" v-text="contrato.manzana"></td>
                <td class="td2">
                    {{ contrato.num_lote  }} {{ contrato.sublote ? contrato.sublote : '' }}
                </td>
                <td class="td2" v-text="'Paquete: '+contrato.paquete + ', Promoción: ' + contrato.promocion"></td>
                <td class="td2" v-text="contrato.avance_lote + '%'"></td>
                <td class="td2" v-text="contrato.tipo_credito"></td>
                <td class="td2" v-text="this.moment(contrato.fecha_firma_esc).locale('es').format('DD/MMM/YYYY')"></td>
                <td class="td2" v-if="contrato.visita_avaluo" v-text="this.moment(contrato.visita_avaluo).locale('es').format('DD/MMM/YYYY')"></td>
                <td class="td2" v-else v-text="'Sin fecha'"></td>
                <td v-text="'$'+$root.formatNumber(contrato.totPagare - contrato.totRest)"></td>
                <td class="td2">
                    <span v-if="contrato.status == '1'"
                        class="badge badge-warning">Pendiente</span>
                    <span v-else-if="contrato.status == '3' && !contrato.fecha_firma_esc"
                        class="badge badge-success">Firmado</span>
                    <span v-else-if="contrato.status == '3' && contrato.fecha_firma_esc"
                        class="badge badge-success"> Individualizada </span>
                </td>
                <td class="td2" v-if="contrato.fecha_entrega" v-text="this.moment(contrato.fecha_entrega).locale('es').format('DD/MMM/YYYY')"></td>
                <td class="td2" v-else v-text="'Sin fecha'"></td>
                <td class="td2">
                    <button type="button" @click="$emit('abrirModal',{accion:'solicitar',data: contrato})"
                        class="btn btn-info btn-sm"> Solicitar </button>
                    <button v-if="contrato.equipamiento != 2" type="button"
                        @click="$emit('terminarSolicitud', contrato.folio)"
                        class="btn btn-success btn-sm" title="Finalizar">
                        <i class="fa fa-check"></i>
                    </button>
                </td>
            </tr>
        </template>
    </TableComponent>

</template>
<script>
import TableComponent from '../../../Componentes/TableComponent.vue';
import ButtonComponent from '../../../Componentes/ButtonComponent.vue';
export default {
    props:{
        arrayData:{type: Array}
    },
    components:{
        TableComponent,
        ButtonComponent
    },

}
</script>
<style scoped>
    .td2, .th2 {
        border: solid rgb(200, 200, 200) 1px;
        padding: .5rem;
    }
</style>
