<template>
    <ModalComponent
        :titulo="titulo"
        @closeModal="$emit('close')"
    >
        <template v-slot:body>
            <RowModal :clsRow1="'col-md-9'" :label1="'Nombre de la Inmobiliaria'">
                <input
                    type="text"
                    id="nombre"
                    v-model="data.nombre"
                    class="form-control"
                    placeholder="Inmobiliaria"
                />
            </RowModal>
            <RowModal :label1="'Logo'">
                <input type="file" accept="image/png, image/gif, image/jpeg" class="form-control" @change="onArchivo">
            </RowModal>

            <div v-show="errorInmobiliaria" class="form-group row div-error">
                <div class="text-center text-error">
                    <div
                        v-for="error in errorMostrarMsj"
                        :key="error"
                        v-text="error"
                    ></div>
                </div>
            </div>
        </template>
        <template v-slot:buttons-footer>
            <Button
                :icon="'icon-check'"
                @click="registrarInmobiliaria()"
                >
                    {{ tipoAccion == 1 ? 'Guardar' : 'Guardar cambios' }}
                </Button
            >
        </template>
    </ModalComponent>
</template>

<script>
import ModalComponent from "../../Componentes/ModalComponent.vue";
import Button from "../../Componentes/ButtonComponent.vue";
import RowModal from "../../Componentes/ComponentesModal/RowModalComponent.vue";

export default {
    props:{
        titulo:{
            type: String,
            default: '',
            required: true
        },
        tipoAccion:{
            type: Number,
            default: 1,
            required: false
        },
        data:{
            type: Object,
            default:{
                id: 0,
                nombre : '',
                logo: ''
            },
            require:false
        }
    },
    components: {
        ModalComponent,
        Button,
        RowModal
    },
    data() {
        return {
            proceso: false,
            medios: [],
            arrayMediosPublicidad: [],
            errorInmobiliaria: 0,
            errorMostrarMsj: [],
            archivo: ''
        };
    },
    computed: {},
    methods: {
        onArchivo(e){
            this.archivo = e.target.files[0];
        },

        /**Metodo para registrar  */
        registrarInmobiliaria() {
            if (this.validarDatos() || this.proceso == true) {
                //Se verifica si hay un error (campo vacio)
                return;
            }
            this.proceso = true;

            let me = this;
            let formData = new FormData();

            formData.append('logo', this.archivo);
            formData.append('id', this.data.id);
            formData.append('nombre', this.data.nombre);
            //Con axios se llama el metodo store de DepartamentoController
            axios
                .post("/inmobiliarias", formData)
                .then(function(response) {
                    me.proceso = false;
                    //Se muestra mensaje Success
                    swal({
                        position: "top-end",
                        type: "success",
                        title: "Inmobiliaria guardada correctamente",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    me.$emit('close')

                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        validarDatos() {
            this.errorInmobiliaria = 0;
            this.errorMostrarMsj = [];

            if (this.data.nombre === '')
                //Si la variable departamento esta vacia
                this.errorMostrarMsj.push(
                    "El nombre de la campaña no puede ir vacio."
                );

            if (this.errorMostrarMsj.length)
                //Si el mensaje tiene almacenado algo en el array
                this.errorInmobiliaria = 1;

            return this.errorInmobiliaria;
        },
    },
    mounted() {
    }
};
</script>
<style>
.div-error {
    display: flex;
    justify-content: center;
}
.text-error {
    color: red !important;
    font-weight: bold;
}
</style>
