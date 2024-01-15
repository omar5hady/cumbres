<template lang="">
    <TableComponent
    :cabecera="[
        'Clave','Contratista','Fraccionamiento','Superficie total','Importe contrato', 'Importe Final', 'Obras Extra', 'Total',
        'Fecha de inicio ','Fecha de termino', 'Tipo de edificación', 'IVA','Registro de obra', 'Obs'
    ]">
        <template v-slot:tbody>
            <tr v-for="avisoObra in arrayAvisoObra" :key="avisoObra.id" title="Ver detalle">
                <td>
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">{{avisoObra.clave}}</a>
                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
                        <a class="dropdown-item" @click="$emit('ver',avisoObra.id)">Ver Detalle</a>
                        <a class="dropdown-item" :href="'/estimaciones/excelEdoCuenta?clave='+avisoObra.id">Edo. Cuenta</a>
                        <a class="dropdown-item" v-if="avisoObra.documento != '' && avisoObra.documento != null" :href="'/downloadContratoObra/'+avisoObra.documento" target="_blank"><i class="fa fa-download"></i> Contrato</a>
                        <a v-if="avisoObra.adendum != '' && avisoObra.adendum != null" title="Adendum" class="dropdown-item" :href="'/downloadAdendum/'+avisoObra.adendum" target="_blank"><i class="fa fa-download"></i> Adendum</a>
                        <a v-if="avisoObra.acuse_contratista != '' && avisoObra.acuse_contratista != null" title="Acuse de cierre" class="dropdown-item" :href="avisoObra.acuse_contratista" target="_blank"><i class="fa fa-download"></i> Acuse Contratista</a>
                        <a v-if="avisoObra.acuse_cierre != '' && avisoObra.acuse_cierre != null" title="Acuse de cierre" class="dropdown-item" :href="avisoObra.acuse_cierre" target="_blank"><i class="fa fa-download"></i> Acuse Contratante</a>
                        <a v-if="avisoObra.adendum2 != '' && avisoObra.adendum2 != null" title="Adendum" class="dropdown-item" :href="'/downloadAdendum/'+avisoObra.adendum2" target="_blank"><i class="fa fa-download"></i> Adendum Cierre</a>
                    </div>
                </td>
                <td class="td2" v-text="avisoObra.contratista"></td>
                <td class="td2" v-text="avisoObra.proyecto"></td>
                <td class="td2">{{$root.formatNumber(avisoObra.total_superficie)}} m&sup2;</td>
                <td class="td2" v-text="'$'+$root.formatNumber(avisoObra.total_importe)"></td>
                <td class="td2" v-text="'$'+$root.formatNumber(avisoObra.total_original)"></td>
                <td class="td2" v-text="'$'+$root.formatNumber(avisoObra.total_extra)"></td>
                <td class="td2"
                    style="color:rgb(28, 43, 76)"
                    v-text="'$'+$root.formatNumber(avisoObra.total_extra + avisoObra.total_original)"></td>
                <td class="td2" v-text="avisoObra.f_ini"></td>
                <td class="td2" v-text="avisoObra.f_fin2"></td>
                <td class="td2">{{avisoObra.tipo}}</td>
                <td class="td2"> {{avisoObra.iva == 0 ? 'NO' : 'SI'}} </td>
                <td>
                    <a v-if="avisoObra.registro_obra != '' && avisoObra.registro_obra != null"
                        title="Descargar registro de obra" class="btn btn-success btn-sm" :href="'/downloadRegistroObra/'+avisoObra.registro_obra">
                        <i class="fa fa-download"></i> {{ avisoObra.folio_siroc ? 'Registro: '+avisoObra.folio_siroc : 'Descargar Registro' }}
                    </a>
                </td>
                <td>
                    <button class="btn btn-scarlet" title="Ver Comentarios"
                        @click="$emit('abrirModal',{accion:'observaciones',data: avisoObra})"
                    >
                        <i class="icon-eye"></i>
                    </button>
                </td>
            </tr>
        </template>
    </TableComponent>

</template>
<script>
import TableComponent from '../../Componentes/TableComponent.vue';
import ButtonComponent from '../../Componentes/ButtonComponent.vue';
export default {
    props:{
        arrayAvisoObra:{type: Array}
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
