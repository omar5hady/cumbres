<template>
    <ModalComponent
        :titulo="titulo"
        @closeModal="$emit('closeModal')"
    >
        <template v-slot:body>
            <RowModal label1="Proyecto" clsRow1="col-md-4" clsRow2="col-md-4">
                <select class="form-control" v-model="contProy" @click="selectEtapa(contProy)">
                    <option value="">Proyecto</option>
                    <option v-for="fraccionamiento in arrayFraccionamientos" :key="fraccionamiento.nombre" :value="fraccionamiento.id" v-text="fraccionamiento.nombre"></option>
                </select>
                <template v-slot:input2>
                    <select class="form-control" v-model="contEtapa">
                        <option value="">Etapa</option>
                        <option v-for="etapa in arrayEtapas" :key="etapa.num_etapa" :value="etapa.id" v-text="etapa.num_etapa"></option>
                    </select>
                </template>
            </RowModal>
            <RowModal label1="" clsRow1="col-md-4" clsRow2="col-md-2">
                <input type="text" v-model="contManzana" class="form-control" placeholder="Manzana">
                <template v-slot:input2>
                    <button type="button" @click="listarContRea(contProy,contEtapa,contManzana)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                </template>
            </RowModal>
            <TableComponent
                :cabecera="[
                    'Seleccionar','# Ref','Proyecto',
                    'Etapa','Manzana','Lote','Paquete'
                ]"
            >
                <template v-slot:tbody>
                    <tr v-for="contReasig in arrayReasignar" :key="contReasig.id">
                        <td class="td2">
                            <button v-if="contReasig.id != id_reasig" title="Reasignar" type="button"
                                @click="id_reasig = contReasig.id, lote_reasignar = contReasig.lote_id"
                                class="btn btn-primary btn-sm">
                                <i class="fa fa-exchange"></i>
                            </button>
                                <i v-else class="btn btn-success btn-sm fa fa-check"></i>
                        </td>
                        <td class="td2" v-text="contReasig.id" ></td>
                        <td class="td2" v-text="contReasig.proyecto" ></td>
                        <td class="td2" v-text="contReasig.etapa"></td>
                        <td class="td2" v-text="contReasig.manzana"></td>
                        <td class="td2" v-text="contReasig.num_lote"></td>
                        <td class="td2" v-text="'Paquete: ' + contReasig.paquete + ' Promo: '+ contReasig.promocion"></td>
                    </tr>
                </template>
            </TableComponent>
        </template>
        <template v-slot:buttons-footer>
            <button type="button" class="btn btn-success" @click="reubicar()">Reasignar</button>
        </template>
    </ModalComponent>
</template>
<script>
import ModalComponent from '../../../Componentes/ModalComponent.vue';
import RowModal from '../../../Componentes/ComponentesModal/RowModalComponent.vue';
import TableComponent from '../../../Componentes/TableComponent.vue';
export default{
    components:{
        ModalComponent,
        RowModal,
        TableComponent
    },
    props:{
        titulo:{type: String},
        tipoAccion:{type: Number},
        solicitud_id:{type: Number},
        arrayFraccionamientos: {type: Array},
    },
    data() {
        return {
            contProy:'',
            contEtapa: '',
            contManzana: '',
            id_reasig: '',
            lote_reasignar: '',
            arrayEtapas: [],
            arrayReasignar: []
        }
    },
    methods: {
        selectEtapa(buscar){
            let me = this;

            me.arrayEtapas3=[];
            var url = '/select_etapa_proyecto?buscar=' + buscar;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayEtapas = respuesta.etapas;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        listarContRea(proyecto, etapa, manzana){
            let me = this;
            var url = '/equipamiento/contRea?proyecto=' + proyecto + '&etapa=' + etapa + '&manzana=' + manzana;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayReasignar = respuesta.contratos;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        reubicar(){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                axios.put('/equipamiento/reubicar',{
                    'id':this.solicitud_id,
                    'contrato_id' : this.id_reasig,
                    'lote_id':this.lote_reasignar,

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
                        title: 'Equipamiento reasignado'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },
    },
    mounted() {
    },
}
</script>
<style lang="">

</style>
