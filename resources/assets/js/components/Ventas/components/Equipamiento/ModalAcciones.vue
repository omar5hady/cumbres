<template>
    <ModalComponent
        :titulo="titulo"
        @closeModal="$emit('closeModal')"
    >
        <template v-slot:body>
            <template v-if="tipoAccion == 1">
                <RowModal label1="Fecha de anticipo" clsRow1="col-md-4">
                    <input type="date" v-model="data.fecha_anticipo" class="form-control">
                </RowModal>

                <RowModal label1="$ Anticipo" clsRow1="col-md-4" clsRow2="col-md-4">
                    <input type="text" pattern="\d*" maxlength="10"
                        @keypress="$root.isNumber($event)" v-model="data.anticipo" class="form-control">
                    <template v-slot:input2>
                        <h6 v-text="'$'+$root.formatNumber(data.anticipo)"></h6>
                    </template>
                </RowModal>
            </template>

            <template v-if="tipoAccion == 2">
                <RowModal label1="Fecha de colocacion" clsRow1="col-md-4">
                    <input type="date" v-model="data.fecha_colocacion" class="form-control">
                </RowModal>
                <RowModal label1="Observación" clsRow1="col-md-8">
                    <textarea v-model="observacion" class="form-control" cols="50" rows="4"></textarea>
                </RowModal>
            </template>

            <template v-if="tipoAccion == 3">
                <RowModal clsRow1="col-md-4" label1="Fecha de liquidación">
                    <input type="date" v-model="data.fecha_liquidacion" class="form-control">
                </RowModal>
                <RowModal clsRow1="col-md-4" label1="$ Liquidación" clsRow2="col-md-4">
                    <input type="text" pattern="\d*" maxlength="10"
                        @keypress="$root.isNumber($event)" v-model="data.liquidacion" class="form-control">
                    <template v-slot:input2>
                        <h6 v-text="'$'+$root.formatNumber(data.liquidacion)"></h6>
                    </template>
                </RowModal>
            </template>
        </template>
        <template v-slot:buttons-footer>
            <button type="button" v-if="tipoAccion == 1" class="btn btn-success" @click="actAnticipo()">Guardar</button>
            <button type="button" v-if="tipoAccion == 2" class="btn btn-success" @click="actColocacion()">Guardar</button>
            <button type="button" v-if="tipoAccion == 3" class="btn btn-success" @click="actLiquidacion()">Guardar</button>
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
        fecha_anticipo:{type: String},
        fecha_colocacion:{type: String},
        fecha_liquidacion:{type: String},
        anticipo:{type: Number},
        liquidacion:{type: Number},
        solicitud_id:{type: Number}
    },
    data() {
        return {
            observacion : '',
            data:{
                fecha_anticipo:'',
                fecha_colocacion: '',
                fecha_liquidacion: '',
                anticipo: 0,
                liquidacion: 0,
            }
        }
    },
    methods: {
        actLiquidacion(){
            let me = this;
            //Con axios se llama el metodo update de LoteController
            axios.put('/equipamiento/actLiquidacion',{
                'fecha_liquidacion':this.data.fecha_liquidacion,
                'liquidacion' : this.data.liquidacion,
                'id':this.solicitud_id,

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
        actAnticipo(){
            let me = this;
            //Con axios se llama el metodo update de LoteController
            axios.put('/equipamiento/actAnticipo',{
                'fecha_anticipo':this.data.fecha_anticipo,
                'anticipo' : this.data.anticipo,
                'id':this.solicitud_id,

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
        actColocacion(){
            let me = this;
            //Con axios se llama el metodo update de LoteController
            axios.put('/equipamiento/actColocacion',{
                'fecha_colocacion':this.data.fecha_colocacion,
                'comentario' : this.observacion,
                'id':this.solicitud_id,

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
    mounted() {
        this.data.fecha_anticipo = this.fecha_anticipo
        this.data.fecha_colocacion = this.fecha_colocacion
        this.data.fecha_liquidacion = this.fecha_liquidacion
        this.data.anticipo = this.anticipo
        this.data.liquidacion = this.liquidacion
    },
}
</script>
<style lang="">

</style>
