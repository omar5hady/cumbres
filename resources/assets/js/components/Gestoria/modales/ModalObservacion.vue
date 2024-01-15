<template>
    <ModalComponent
        :titulo="titulo"
        @closeModal="$emit('closeModal')"
    >
        <template v-slot:body>
            <RowModal label1="Observación" id1="observacion" clsRow1="col-md-6"
                label2="" clsRow2="col-md-2"
            >
                <textarea rows="3" cols="30" v-model="observacion" class="form-control" placeholder="Observacion"></textarea>
                <template v-slot:input2>
                    <button type="button"  class="btn btn-primary" @click="agregarComentario()">Guardar</button>
                </template>
            </RowModal>

            <RowModal label1="" clsRow1="col-md-12">
                <table class="table table-bordered table-striped table-sm" >
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
                            <td v-text="observacion.observacion" ></td>
                            <td v-text="observacion.created_at"></td>
                        </tr>
                    </tbody>
                </table>
            </RowModal>
        </template>
    </ModalComponent>
</template>
<script>
import ModalComponent from '../../Componentes/ModalComponent.vue';
import RowModal from '../../Componentes/ComponentesModal/RowModalComponent'
import Button from '../../Componentes/ButtonComponent'
export default {
    components:{
        ModalComponent,
        RowModal,
        Button
    },
    props:{
        titulo: String,
        id: Number,
    },
    data() {
        return {
            proceso : false,
            observacion : '',
            arrayObservacion : []
        }
    },
    computed:{
    },
    methods: {
        agregarComentario(){
            if(this.proceso==true){
                return;
            }
            this.proceso=true;
            let me = this;
            //Con axios se llama el metodo store de DepartamentoController
            axios.post('/observacionExpediente/registrar',{
                'folio': this.id,
                'observacion': this.observacion
            }).then(function (response){
                me.proceso=false;
                me.listarObservacion(me.id);
                me.observacion = '';

                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });

                toast({
                    type: 'success',
                    title: 'Observación Agregada Correctamente'
                })
            }).catch(function (error){
                console.log(error);
            });
        },
        listarObservacion(buscar){
            let me = this;
            var url = '/observacionExpediente?folio=' + buscar ;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayObservacion = respuesta.observacion;
                console.log(url);
            })
            .catch(function (error) {
                console.log(error);
            });

        },
    },
    mounted() {
        this.listarObservacion(this.id)
    }
}
</script>
<style>
    @media (min-width: 300px){
        .btnagregar{
            margin-top: 2rem;
        }
    }
</style>
