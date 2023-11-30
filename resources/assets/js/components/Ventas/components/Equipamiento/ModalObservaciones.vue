<template>
    <ModalComponent
        :titulo="titulo"
        @closeModal="$emit('closeModal')"
    >
        <template v-slot:body>
            <RowModal label1="Nueva Observación" clsRow1="col-md-6" clsRow2="col-md-3">
                <input type="text" v-model="observacion" class="form-control">
                <template v-slot:input2>
                    <button class="btn btn-primary" @click="storeObservacion()">Guardar</button>
                </template>
            </RowModal>

            <table class="table table-bordered table-striped table-sm">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Observacion</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="observacion in arrayObservacion" :key="observacion.id">
                        <td v-text="observacion.usuario" ></td>
                        <td v-text="observacion.comentario" ></td>
                        <td v-text="observacion.created_at"></td>
                    </tr>
                </tbody>
            </table>
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
        solicitud_id:{type: Number}
    },
    data() {
        return {
            observacion : '',
            arrayObservacion : []
        }
    },
    methods: {
        storeObservacion(){
            let me = this;
            //Con axios se llama el metodo update de LoteController
            axios.post('/equipamiento/registrarObservacion',{
                'comentario' : this.observacion,
                'solic_id':this.solicitud_id,

            }).then(function (response){
                me.listarObservacion(1,me.solicitud_id);
                //window.alert("Cambios guardados correctamente");
                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                    });
                    toast({
                    type: 'success',
                    title: 'Observación guardada correctamente'
                })
            }).catch(function (error){
                console.log(error);
            });
        },

        listarObservacion(page, buscar){
            let me = this;
            var url = '/equipamiento/indexObservacion?page=' + page + '&buscar=' + buscar ;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayObservacion = respuesta.observacion.data;
                console.log(url);
            })
            .catch(function (error) {
                console.log(error);
            });

        },
    },
    mounted() {
       this.listarObservacion(1,this.solicitud_id)
    },
}
</script>
<style lang="">

</style>
