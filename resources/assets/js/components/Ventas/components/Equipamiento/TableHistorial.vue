<template >
    <TableComponent
    :cabecera="[
        '','# Ref','Cliente','Proyecto','Etapa',
        'Manzana','Lote','Avance de obra','Proveedor','Equipamiento',
        'Costo del equipamiento','Anticipo','Comp. de pago 1','Fecha programada',
        'Fecha fin de instalación','Status','# Dias de inst.','Total pagado',
        'Pendiente','Liquidacion','Comp. de pago 2','Imprimir Recepción','Observaciones'
    ]">
        <template v-slot:tbody>
            <tr v-for="equipamientos in arrayData" :key="equipamientos.id">
                <td>
                    <i v-if="equipamientos.control == 0"
                        class="btn btn-success btn-sm fa fa-check"></i>
                    <button v-else-if="equipamientos.control == 1"
                        title="Reasignar" type="button" @click="$emit('abrirModal',{accion:'reasignar', data:equipamientos})" class="btn btn-primary btn-sm">
                        <i class="fa fa-exchange"></i>
                    </button>
                    <i v-else-if="equipamientos.control == 3"
                        class="btn btn-warning btn-sm fa fa-exclamation-triangle"></i>
                    <i v-else
                        title="Cancelado" class="btn btn-danger btn-sm fa fa-exclamation-triangle"></i>
                </td>
                <td class="td2" v-text="equipamientos.folio"></td>
                <td class="td2" v-text="equipamientos.nombre_cliente"></td>
                <td class="td2" v-text="equipamientos.proyecto"></td>
                <td class="td2" v-text="equipamientos.etapa"></td>
                <td class="td2" v-text="equipamientos.manzana"></td>
                <td class="td2">
                    {{ equipamientos.num_lote }} {{ equipamientos.sublote ? equipamientos.sublote : ''}}
                </td>
                <td class="td2" v-text="equipamientos.avance + '%'"></td>
                <td class="td2" v-text="equipamientos.proveedor"></td>
                <td class="td2">
                    {{ equipamientos.equipamiento }}
                    <button v-if="!equipamientos.render" type="button"
                        data-toggle="modal" data-target="#cargaPago1"
                        class="btn btn-primary" title="Cargar Render"
                        @click="$emit('cargaArchivo',{generalId:equipamientos.id, upType:3})">
                        <i class="fa fa-cloud-upload"></i>
                    </button>
                    <a v-else
                        class="btn btn-success" title="Descargar Render"
                        :href="equipamientos.render" target="_blank">
                        <i class="fa fa-cloud-download"></i>
                    </a>
                </td>
                <td class="td2" style="width:40%">
                    <input type="text" pattern="\d*" @keyup.enter="actCosto(equipamientos.id,equipamientos.fecha_anticipo,$event.target.value)" :id="equipamientos.id" :value="equipamientos.costo|currency" maxlength="10" v-on:keypress="$root.isNumber($event)" class="form-control" >
                </td>
                <template>
                    <td v-if="equipamientos.fecha_anticipo && equipamientos.anticipo_cand==0"
                        @click="$emit('abrirModal',{accion:'anticipo', data:equipamientos})" class="td2">
                        <a href="#" v-text=" this.moment(equipamientos.fecha_anticipo).locale('es').format('DD/MMM/YYYY') + ': '+ '$'+$root.formatNumber(equipamientos.anticipo)"></a>
                    </td>
                    <td v-else-if="equipamientos.fecha_anticipo && equipamientos.anticipo_cand==1"
                        class="td2" v-text="
                        this.moment(equipamientos.fecha_anticipo).locale('es').format('DD/MMM/YYYY') + ': '+ '$'+$root.formatNumber(equipamientos.anticipo)"></td>
                    <td v-else class="td2"
                        @click="$emit('abrirModal',{accion:'anticipo', data:equipamientos})">
                        <a href="#" v-text="'Sin anticipo'"></a>
                    </td>
                    <td class="text-center">
                        <button @click="$emit('cargaArchivo',{generalId:equipamientos.id, upType:1})" data-toggle="modal" data-target="#cargaPago1" class="btn btn-sm btn-info" title="Subir archivo">
                            <i class="fa fa-cloud-upload"></i>
                        </button>
                        <a v-if="equipamientos.comp_pago_1" :href="'/equipamiento/indexHistorial/downloadFile1/'+equipamientos.comp_pago_1" class="btn btn-sm btn-primary" title="Descargar archivo">
                            <i class="fa fa-cloud-download"></i>
                        </a>
                    </td>
                </template>
                <template>
                    <td v-if="equipamientos.fecha_colocacion" class="td2"
                        @click="$emit('abrirModal',{accion:'colocacion', data:equipamientos})">
                        <a href="#" v-text=" this.moment(equipamientos.fecha_colocacion).locale('es').format('DD/MMM/YYYY')"></a>
                    </td>
                    <td v-else class="td2"
                        @click="$emit('abrirModal',{accion:'colocacion', data:equipamientos})">
                        <a href="#" v-text="'Sin fecha'"></a>
                    </td>
                </template>
                <template>
                    <td v-if="equipamientos.fin_instalacion" class="td2" v-text="this.moment(equipamientos.fin_instalacion).locale('es').format('DD/MMM/YYYY')"></td>
                    <td v-else class="td2" v-text="'Sin fecha'"></td>
                </template>
                <td class="td2">
                    <span v-if="equipamientos.status == '0'" class="badge badge-warning">Rechazado</span>
                    <span v-if="equipamientos.status == '1'" class="badge badge-primary">Pendiente</span>
                    <span v-if="equipamientos.status == '2'" class="badge badge-primary">En proceso de colocación</span>
                    <span v-if="equipamientos.status == '3'" class="badge badge-primary">En Revisión</span>
                    <span v-if="equipamientos.status == '4'" class="badge badge-success">Aprobado</span>
                    <span v-if="equipamientos.status == '5'" class="badge badge-danger">Cancelado</span>
                </td>
                <td>
                    <span v-if="!equipamientos.fin_instalacion && equipamientos.fecha_anticipo || equipamientos.fin_instalacion && equipamientos.fecha_anticipo && equipamientos.status != '4'"
                        class="badge badge-warning"
                        v-text="equipamientos.diferenciaIni">
                    </span>
                    <span  v-else-if="equipamientos.fin_instalacion && equipamientos.fecha_anticipo && equipamientos.status == '4'"
                        class="badge badge-success"
                        v-text="equipamientos.diferenciaFin">
                    </span>
                    <span v-else>Sin Anticipo</span>
                </td>
                <td class="td2" v-text="'$'+$root.formatNumber(equipamientos.anticipo + equipamientos.liquidacion)"></td>
                <td class="td2" v-text="'$'+$root.formatNumber(equipamientos.costo - equipamientos.anticipo - equipamientos.liquidacion)"></td>
                <template><!--Liquidacion-->
                    <td v-if="equipamientos.fecha_liquidacion && equipamientos.liquidacion_cand == 0"
                        @click="$emit('abrirModal',{accion:'liquidacion', data:equipamientos})" class="td2"
                        v-text="this.moment(equipamientos.fecha_liquidacion).locale('es').format('DD/MMM/YYYY') + ': '+ '$'+$root.formatNumber(equipamientos.liquidacion)"></td>
                    <td v-else-if="equipamientos.fecha_liquidacion && equipamientos.liquidacion_cand == 1" class="td2"
                        v-text=" this.moment(equipamientos.fecha_liquidacion).locale('es').format('DD/MMM/YYYY') + ': '+ '$'+$root.formatNumber(equipamientos.liquidacion)"></td>
                    <td v-else-if="equipamientos.status  > 2">
                        <button title="Realizar recepcion" type="button"
                            @click="$emit('abrirModal',{accion:'liquidacion', data:equipamientos})" class="btn btn-success pull-right">
                            <i class="fa fa-check-square-o"></i> Generar
                        </button>
                    </td>
                    <td v-else v-text="'Sin Liquidación'"></td>
                    <td class="text-center">
                        <button @click="$emit('cargaArchivo',{generalId:equipamientos.id, upType:2})" data-toggle="modal" data-target="#cargaPago1" class="btn btn-sm btn-info" title="Subir archivo">
                            <i class="fa fa-cloud-upload"></i>
                        </button>
                        <a v-if="equipamientos.comp_pago_2" :href="'/equipamiento/indexHistorial/downloadFile2/'+equipamientos.comp_pago_2" class="btn btn-sm btn-primary" title="Descargar archivo">
                            <i class="fa fa-cloud-download"></i>
                        </a>
                    </td>
                </template>
                <template><!--Imprimir Recepción-->
                    <td>
                        <a v-if="equipamientos.tipoRecepcion == 1 && equipamientos.recepcion == 1"
                            class="btn btn-warning btn-sm" target="_blank"
                            :href="'/equipamiento/recepcionCocina/'+equipamientos.id">Ver Recepción</a>
                        <a v-else-if="equipamientos.tipoRecepcion == 2 && equipamientos.recepcion == 1"
                            class="btn btn-warning btn-sm" target="_blank"
                            :href="'/equipamiento/recepcionClosets/'+equipamientos.id">Ver Recepción</a>
                        <a v-else-if="equipamientos.tipoRecepcion == 0 && equipamientos.recepcion == 1"
                            class="btn btn-warning btn-sm" target="_blank"
                            :href="'/equipamiento/recepcionGeneral/'+equipamientos.id">Ver Recepción</a>
                    </td>
                </template>
                <td>
                    <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right"
                        @click="$emit('abrirModal',{accion:'observaciones', data:equipamientos})">Ver observaciones
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
    methods: {
        actCosto(id,fecha_anticipo,costo){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/equipamiento/actCosto',{
                    'fecha_anticipo':fecha_anticipo,
                    'costo' : costo,
                    'id':id,

                }).then(function (response){
                    me.$emit('closeModal')
                    //window.alert("Cambios guardados correctamente");
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Datos guardados correctamente'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },
    },

}
</script>
<style scoped>
    .td2, .th2 {
        border: solid rgb(200, 200, 200) 1px;
        padding: .5rem;
    }
</style>
