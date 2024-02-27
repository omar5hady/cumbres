<template>
    <ModalComponent
        :titulo="titulo"
        @closeModal="$emit('closeModal')"
    >
        <template v-slot:body>
            <RowModal clsRow1="col-md-9" label1="Paquete" v-if="paquete">
                <textarea rows="3" cols="30" readonly v-model="paquete"
                    class="form-control" placeholder="Descripcion del paquete">
                </textarea>
            </RowModal>
            <RowModal v-if="promocion" clsRow1="col-md-9" label1="Promoción">
                <textarea rows="3" cols="30" readonly v-model="promocion"
                    class="form-control" placeholder="Descripcion del paquete">
                </textarea>
            </RowModal>

            <div class="form-group row line-separator"></div>

            <RowModal clsRow1="col-md-6" label1="Proveedor">
                <select class="form-control" v-model="proveedor"
                    @change="selectEquipamiento(proveedor)">
                    <option value="">Seleccione</option>
                    <option v-for="proveedor in arrayProveedores"
                        :key="proveedor.id" :value="proveedor.id" v-text="proveedor.proveedor">
                    </option>
                </select>
            </RowModal>
            <RowModal clsRow1="col-md-6" label1="Equipamiento">
                <select class="form-control" v-model="equipamiento">
                    <option value="">Seleccione</option>
                    <option v-for="equipamiento in arrayEquipamientos"
                        :key="equipamiento.id" :value="equipamiento.id" v-text="equipamiento.equipamiento">
                    </option>
                </select>
            </RowModal>
            <RowModal clsRow1="col-md-12" label1="">
                <button class="btn btn-primary" @click="solicitarEquipamiento()">
                    Solicitar
                </button>
            </RowModal>

            <TableComponent :cabecera="['', 'Proveedor', 'Equipamiento', 'Fecha de Solicitud']">
                <template v-slot:tbody>
                    <tr v-for="equipamientos in arrayEquipamientosLote" :key="equipamientos.id">
                        <td class="td2">
                            <button type="button" class="btn btn-danger btn-sm"
                                @click="eliminarEquipamiento(equipamientos)">
                                <i class="icon-trash"></i>
                            </button>
                        </td>
                        <td class="td2" v-text="equipamientos.proveedor"></td>
                        <td class="td2" v-text="equipamientos.equipamiento"></td>
                        <td class="td2" v-text="equipamientos.fecha_solicitud"></td>
                    </tr>
                </template>
            </TableComponent>
        </template>
    </ModalComponent>

</template>
<script>
import ModalComponent from '../../../Componentes/ModalComponent.vue';
import RowModal from '../../../Componentes/ComponentesModal/RowModalComponent.vue';
import TableComponent from '../../../Componentes/TableComponent.vue';
export default {
    components:{
        ModalComponent,
        RowModal,
        TableComponent
    },
    props:{
        titulo:{type: String},
        paquete:{type: String},
        promocion:{type: String},
        lote_id:{type: Number},
        contrato_id:{type: Number}
    },
    data() {
        return {
            arrayProveedores: [],
            arrayEquipamientos: [],
            arrayEquipamientosLote: [],
            equipamiento: '',
            proveedor: ''
        }
    },
    methods: {
        selectProveedores(){
            let me = this;

            me.arrayProveedores=[];
            var url = '/select_proveedor?constancia=1&equipamiento=1';
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayProveedores = respuesta.proveedor;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        selectEquipamiento(proveedor){
            let me = this;
            me.equipamiento=""

            me.arrayEquipamientos=[];
            var url = '/select_equipamientos?buscar=' + proveedor;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayEquipamientos = respuesta.equipamiento;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        listarEquipamientosLote(){
            let me = this;
            var url = '/index/equipamiento/lote?lote_id=' + this.lote_id + '&contrato_id=' + this.contrato_id;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayEquipamientosLote = respuesta.equipamientos;
            })
            .catch(function (error) {
                console.log(error);
            });

        },
        solicitarEquipamiento(){
            let me = this;
            //Con axios se llama el metodo update de LoteController
            axios.post('/equipamiento/solicitar_equipamiento',{
                'contrato_id':this.contrato_id,
                'lote_id' : this.lote_id,
                'equipamiento_id': this.equipamiento

            }).then(function (response){
                me.proveedor ='';
                me.equipamiento ='';
                me.listarEquipamientosLote();
                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                    });
                    toast({
                    type: 'success',
                    title: 'Equipamiento solicitado correctamente'
                })
            }).catch(function (error){
                console.log(error);
            });
        },
        eliminarEquipamiento(data = []){
            let me = this;
            this.solicitud_id=data['id'];
            //console.log(this.departamento_id);
            swal({
            title: '¿Desea eliminar?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Si, eliminar!'
            }).then((result) => {
            if (result.value) {

            axios.delete('/equipamiento/lote/eliminar',
                    {params: {'id': this.solicitud_id,'contrato_id':this.contrato_id}}).then(function (response){
                    swal(
                    'Borrado!',
                    'Equipamiento borrado correctamente.',
                    'success'
                    )
                    me.listarEquipamientosLote();
                }).catch(function (error){
                    console.log(error);
                });
            }
            })
        },
    },
    mounted() {
        this.selectProveedores()
        this.listarEquipamientosLote()
    },
}
</script>
<style lang="">

</style>
