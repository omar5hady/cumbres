<template>
    <ModalComponent
        :titulo="titulo"
        @closeModal="$emit('closeModal')"
    >
        <template v-slot:body>
            <RowModal label1="Observación" id1="'observacion'" clsRow1="col-md-8">
                <textarea rows="3" cols="30" v-model="observacion" class="form-control" placeholder="Observación"></textarea>
            </RowModal>
        </template>

        <template v-slot:buttons-footer>
            <Button v-if="observacion != ''" @click="SolicitarEntrega()" icon="icon-check"> Solicitar </Button>
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
        datos: Object,
    },
    data() {
        return {
            proceso : false,
            observacion : ''
        }
    },
    computed:{
    },
    methods: {
        SolicitarEntrega(){
            if(this.proceso==true){
                return;
            }
            this.proceso=true;
            let me = this;
            //Con axios se llama el metodo store de DepartamentoController
            axios.post('/entrega/registrar',{
                'id': this.data.id,
                'comentario': this.observacion
            }).then(function (response){
                me.proceso=false;
                me.$emit('closeModal')

                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                toast({
                    type: 'success',
                    title: 'Solicitud enviada correctamente'
                })
            }).catch(function (error){
                console.log(error);
            });
        },
    },
    mounted() {
        this.data = {...this.datos}
    },
}
</script>
<style>
    @media (min-width: 300px){
        .btnagregar{
            margin-top: 2rem;
        }
    }
</style>
