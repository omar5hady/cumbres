<template lang="">
    <TableComponent
        :cabecera="[
            '',
            'Clave',
            'Contratista',
            'Fraccionamiento',
            'Importe del contrato',
            'Fondo de Garantía a Retener',
        ]"
    >
        <template v-slot:tbody>
            <tr v-for="contrato in arrayEstimaciones" :key="contrato.id">
                <td class="td2">
                    <ButtonComponent
                        title="Ver estimaciones"
                        @click="$emit('verDetalle',contrato)"
                        icon="fa fa-eye"
                    ></ButtonComponent>
                    <a
                        :href="
                            '/estimaciones/excelEdoCuenta?clave=' +
                                contrato.id
                        "
                        class="btn btn-success"
                        ><i
                            class="fa fa-file-text"
                            title="Descargar estado de cuenta"
                        ></i>
                        Edo. Cuenta
                    </a>
                </td>
                <td class="td2" v-text="contrato.clave"></td>
                <td class="td2" v-text="contrato.contratista"></td>
                <td class="td2" v-text="contrato.proyecto"></td>
                <td
                    class="td2"
                    v-text="'$' + $root.formatNumber(contrato.total_importe)"
                ></td>
                <td
                    class="td2"
                    v-text="'$' + $root.formatNumber(contrato.garantia_ret)"
                ></td>
                <td v-if="contrato.fin_estimaciones == 0 && rolId != 13">
                    <ButtonComponent
                        @click="$emit('finalizarEstimacion',contrato.id)"
                        btnClass="btn-danger"
                        icon="fa fa-check"
                    >
                        Finalizar estimación
                    </ButtonComponent>
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
        arrayEstimaciones:{type: Array},
        rolId:{type: String},
    },
    components:{
        TableComponent,
        ButtonComponent
    }
};
</script>
<style lang="">
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
