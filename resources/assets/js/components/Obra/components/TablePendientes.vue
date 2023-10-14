<template lang="">
    <TableComponent
    :cabecera="[
        '','Clave','Contratista','Fraccionamiento','Superficie total','Importe total',
        'Fecha de inicio ','Fecha de termino','Registro Obra', 'Obs.'
    ]">
        <template v-slot:tbody>
            <tr :class="avisoObra.diferencia < 0 ? '' : 'table-danger'"
                v-for="avisoObra in arrayAvisoObra" :key="avisoObra.id" title="Ver detalle">
                <td class="td2">
                    <template v-if="avisoObra.documento != '' && avisoObra.documento != null && avisoObra.registro_obra != '' && avisoObra.registro_obra != null">
                        <button
                            v-if="usuario == 'uriel.al' || usuario == 'shady'"
                            @click="$emit('sendPorCerrar',avisoObra)"
                            class="btn btn-success"
                        >
                            <i class="icon-check"></i> Por cerrar
                        </button>
                        <button type="button" v-if="usuario == 'uriel.al'"
                            class="btn btn-warning btn-sm" @click="$emit('actualizar',avisoObra.id)">
                            <i class="icon-pencil"></i>
                        </button>
                    </template>
                    <template v-else>
                        <button type="button" v-if="rolId!=9 && rolId != 11 && rolId!=13"
                            class="btn btn-danger btn-sm" @click="$emit('eliminar',avisoObra)">
                            <i class="icon-trash"></i>
                        </button>
                        <button type="button" v-if="rolId!=9 && rolId != 11 && rolId!=13"
                            class="btn btn-warning btn-sm" @click="$emit('actualizar',avisoObra.id)">
                            <i class="icon-pencil"></i>
                        </button>
                    </template>

                </td>
                <td>
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">{{avisoObra.clave}}</a>
                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
                        <a class="dropdown-item" @click="$emit('ver',avisoObra.id)">Ver Detalle</a>
                        <a class="dropdown-item" :href="'/estimaciones/excelEdoCuenta?clave='+avisoObra.id">Edo. Cuenta</a>
                        <template v-if="avisoObra.f_fin != avisoObra.f_fin2 || avisoObra.total_importe != avisoObra.total_original">
                            <a class="dropdown-item" v-if="rolId!=9 && rolId != 11 && rolId!=13"
                                @click="$emit('abrirModal',{accion:'subirAdendum',data: avisoObra})">
                                <i class="icon-cloud-upload"></i>
                                Subir Adendum
                            </a>
                            <a class="dropdown-item" v-if="avisoObra.adendum == null"
                                @click="$emit('print',{tipo:'adendum', id:avisoObra.id})"><i class="fa fa-print"></i> Imprimir Adendum</a>
                        </template>
                        <a class="dropdown-item" v-if="rolId!=9 && rolId != 11 && rolId!=13"
                            @click="$emit('abrirModal',{accion:'subirArchivo',data:avisoObra})">
                            <i class="icon-cloud-upload"></i>Subir contrato
                        </a>
                        <a class="dropdown-item" v-if="avisoObra.documento != '' && avisoObra.documento != null"
                            :href="'/downloadContratoObra/'+avisoObra.documento"><i class="fa fa-download"></i>
                            Descargar contrato
                        </a>
                        <a v-if="avisoObra.adendum != '' && avisoObra.adendum != null" title="Adendum" class="dropdown-item"
                            :href="'/downloadAdendum/'+avisoObra.adendum"><i class="fa fa-download"></i>
                            Descargar Adendum
                        </a>
                    </div>
                </td>
                <td class="td2" v-text="avisoObra.contratista"></td>
                <td class="td2" v-text="avisoObra.proyecto"></td>
                <td class="td2">{{$root.formatNumber(avisoObra.total_superficie)}} m&sup2;</td>
                <td class="td2" v-text="'$'+$root.formatNumber(avisoObra.total_importe)"></td>
                <td class="td2" v-text="avisoObra.f_ini"></td>
                <td class="td2" v-text="avisoObra.f_fin2"></td>
                <td>
                    <a v-if="avisoObra.registro_obra != '' && avisoObra.registro_obra != null"
                        title="Descargar registro de obra" class="btn btn-success btn-sm"   v-bind:href="'/downloadRegistroObra/'+avisoObra.registro_obra">
                        <i class="fa fa-download"></i>&nbsp;{{ avisoObra.folio_siroc ? 'Registro: '+avisoObra.folio_siroc : '' }}
                    </a>
                    <a v-else class="btn btn-success" v-bind:href="'/avisoObra/siroc?id='+ avisoObra.id">
                        <i class="fa fa-file-text"></i>&nbsp; SIROC
                    </a>
                    <button v-if="rolId!=13"
                        title="Subir registro de obra" type="button"
                        @click="$emit('abrirModal',{accion:'subirArchivo2',data:avisoObra})" class="btn btn-default btn-sm">
                        <i class="icon-cloud-upload"></i>
                    </button>
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
export default {
    props:{
        rolId:{type: String},
        usuario: {type: String},
        arrayAvisoObra:{type: Array}
    },
    components:{
        TableComponent,
    },

}
</script>
<style scoped>
    .td2, .th2 {
        border: solid rgb(200, 200, 200) 1px;
        padding: .5rem;
    }
</style>
