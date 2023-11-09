<template>
    <ModalComponent
        :titulo="titulo"
        @closeModal="$emit('close')"
    >
        <template v-slot:body>
            <RowModal clsRow1="col-md-4" label1="Nombre:" clsRow2="col-md-4" label2="Apellidos">
                <input
                    type="text"
                    id="nombre"
                    v-model="data.nombre"
                    class="form-control"
                    placeholder="Nombre"
                />
                <template v-slot:input2>
                    <input
                        type="text"
                        id="apellido"
                        v-model="data.apellido"
                        class="form-control"
                        placeholder="Apellidos"
                    />
                </template>
            </RowModal>
            <RowModal clsRow1="col-md-8" label1="Dirección:">
                <input
                    type="text"
                    id="direccion"
                    v-model="data.direccion"
                    class="form-control"
                    placeholder="Direccion"
                />
            </RowModal>
            <RowModal clsRow1="col-md-8" label1="Celular:">
                <input
                    type="text"
                    id="celular"
                    maxlength="10"
                    @keypress="$root.isNumber($event)"
                    v-model="data.celular"
                    class="form-control"
                    placeholder="Celular"
                />
            </RowModal>
            <RowModal :label1="'Foto'">
                <input type="file" accept="image/png, image/gif, image/jpeg" class="form-control" @change="onArchivo">
            </RowModal>
            <hr>
            <center>Vigencia:</center>
            <RowModal clsRow1="col-md-4" label1="Inicio:" clsRow2="col-md-4" label2="Termino">
                <input
                    type="date"
                    id="f_ini"
                    v-model="data.f_ini"
                    class="form-control"
                />
                <template v-slot:input2>
                    <input
                        type="date"
                        id="f_fin"
                        v-model="data.f_fin"
                        class="form-control"
                    />
                </template>
            </RowModal>

            <div v-show="erroAsesor" class="form-group row div-error">
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
                @click="registrarAsesor()"
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
                mobiliaria_id: 0,
                nombre: '',
                apellido: '',
                direccion: '',
                celular: '',
                photo: '',
                f_ini: '',
                f_fin: ''
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
            erroAsesor: 0,
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
        registrarAsesor() {
            if (this.validarDatos() || this.proceso == true) {
                //Se verifica si hay un error (campo vacio)
                return;
            }
            this.proceso = true;

            let me = this;
            let formData = new FormData();

            formData.append('photo', this.archivo);
            formData.append('id', this.data.id);
            formData.append('nombre', this.data.nombre);
            formData.append('apellido', this.data.apellido);
            formData.append('direccion', this.data.direccion);
            formData.append('celular', this.data.celular);
            formData.append('f_ini', this.data.f_ini);
            formData.append('f_fin', this.data.f_fin);
            formData.append('mobiliaria_id', this.data.mobiliaria_id);
            //Con axios se llama el metodo store de DepartamentoController
            axios
                .post("/asesores-externos", formData)
                .then(function(response) {
                    me.proceso = false;
                    //Se muestra mensaje Success
                    swal({
                        position: "top-end",
                        type: "success",
                        title: "Asesor guardado correctamente",
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
            this.erroAsesor = 0;
            this.errorMostrarMsj = [];

            if (this.data.nombre === '')
                //Si la variable departamento esta vacia
                this.errorMostrarMsj.push(
                    "El nombre del asesor no puede ir vacio."
                );

            if (this.data.apellido === '')
                //Si la variable departamento esta vacia
                this.errorMostrarMsj.push(
                    "El apellido del asesor no puede ir vacio."
                );

            if (this.data.f_ini === '' || this.data.f_fin === '')
                //Si la variable departamento esta vacia
                this.errorMostrarMsj.push(
                    "Indicar fecha de inicio y termino de la vigencia"
                );

            if (this.errorMostrarMsj.length)
                //Si el mensaje tiene almacenado algo en el array
                this.erroAsesor = 1;

            return this.erroAsesor;
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
