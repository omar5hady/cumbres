<template lang="">
    <TableComponent
    :cabecera="[
       'Clave', 'Anticipo', 'Estimación','F.G.', 'O. Extra', '','TOTAL'
    ]">
        <template v-slot:tbody>
            <template v-for="p in arrayResumen">
                <tr class="thead-primary" :key="p.id">
                    <th class="text-center" style="color:white;" colspan="6">{{p.fraccionamiento}}</th>
                    <th class="text-center" style="color:white;"
                        :rowspan="p.data.length+1">
                        ${{ $root.formatNumber(p.total) }}
                    </th>
                </tr>
                <tr v-for="d in p.data" :key="d.id">
                    <td class="td2">{{d.clave}}</td>
                    <td class="td2">$ {{ $root.formatNumber(d.anticipo) }}</td>
                    <td class="td2">$ {{ $root.formatNumber(d.total_pagado) }}</td>
                    <td class="td2">$ {{ $root.formatNumber(d.fg) }}</td>
                    <td class="td2">$ {{ $root.formatNumber(d.extra) }}</td>
                    <td></td>
                </tr>
                <tr></tr>
            </template>
        </template>
    </TableComponent>
</template>
<script>
import TableComponent from '../../../Componentes/TableComponent.vue';
import ButtonComponent from '../../../Componentes/ButtonComponent.vue';
export default {
    props:{
        rolId:{type: String},
        usuario: {type: String},
        arrayResumen:{type: Array}
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
    .thead-primary{
        background-color: #1c2b4c !important;
    }
</style>
