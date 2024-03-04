<template>
    <ModalComponent :titulo="titulo" @closeModal="$emit('close')">
        <template v-slot:body>
            <RowModal :clsRow1="'col-md-9'" :label1="'Nombre de la campaña'">
                <input
                    type="text"
                    id="nombre"
                    v-model="datos.nombre"
                    class="form-control"
                    placeholder="Festividad"
                />
            </RowModal>
            <RowModal :clsRow1="'col-md-6'" :label1="'Fecha'">
                <input
                    type="date"
                    id="fecha"
                    v-model="datos.fecha"
                    class="form-control"
                />
            </RowModal>
            <RowModal :clsRow1="'col-md-6'" :label1="'¿Medio Dia?'">
                <select id="medio_dia" v-model="datos.medio_dia" class="form-control">
                    <option :value="0">No</option>
                    <option :value="1">Si</option>
                </select>
            </RowModal>
            <div v-show="errorDia" class="form-group row div-error">
                <div class="text-center text-error">
                    <div
                        v-for="error in msjError"
                        :key="error"
                        v-text="error"
                    ></div>
                </div>
            </div>
        </template>
        <template v-slot:buttons-footer>
            <Button
                v-if="tipoAccion == 1"
                :icon="'icon-check'"
                @click="registrarDia()"
                >Guardar</Button
            >
            <Button
                v-if="tipoAccion == 2"
                :icon="'icon-check'"
                @click="update()"
                >Guardar cambios</Button
            >
        </template>
    </ModalComponent>
</template>

<script>
import ModalComponent from "../../Componentes/ModalComponent.vue";
import Button from "../../Componentes/ButtonComponent.vue";
import RowModal from "../../Componentes/ComponentesModal/RowModalComponent.vue";

export default {
    props: {
        titulo: {
            type: String,
            default: "",
            required: true
        },
        tipoAccion: {
            type: Number,
            default: 1,
            required: false
        },
        data: {
            type: Object,
            default: {
                id: "",
                nombre: "",
                medio_dia: 0,
                fecha: ""
            },
            require: true
        }
    },
    components: {
        ModalComponent,
        Button,
        RowModal
    },
    data() {
        return {
            datos: {
                id: "",
                fecha: "",
                nombre: "",
                medio_dia: 0
            },
            errorDia: 0,
            msjError: [],
            proceso: false,
        };
    },
    computed: {},
    methods: {
        /**Metodo para registrar  */
        registrarDia() {
            if (this.proceso == true) return;
            if (this.validarDia()) return;

            this.proceso = true;
            let me = this;
            //Con axios se llama el metodo store de FraccionaminetoController
            axios
                .post("/dias-festivos", {
                    fecha: this.datos.fecha,
                    nombre: this.datos.nombre,
                    medio_dia: me.datos.medio_dia,
                })
                .then(function(response) {
                    me.proceso = false;
                    me.$emit('close')
                //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: "top-end",
                        type: "success",
                        title: "Notaria agregada correctamente",
                        showConfirmButton: false,
                        timer: 1500
                    });
                })
                .catch(function(error) {
                    me.proceso = false;
                });
        },
        update() {
            if (this.proceso == true) return;
            if (this.validarDia()) return;
            let me = this;
            me.proceso = true;
            axios
                .put(`/dias-festivos/${me.data.id}`, {
                    nombre: me.datos.nombre,
                    fecha: me.datos.fecha,
                    medio_dia: me.datos.medio_dia,
                    id: me.datos.id
                })
                .then(function(response) {
                    me.proceso = false;
                    me.$emit('close')
                    const toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000
                    });

                    toast({
                        type: "success",
                        title: "Registro actualizado correctamente."
                    });
                })
                .catch(function(error) {
                    me.proceso = false;
                });
        },
        validarDia() {
            this.errorDia = 0;
            this.msjError = [];

            if (!this.datos.fecha || this.datos.fecha == "")
                //Si la variable Fraccionamiento esta vacia
                this.msjError.push("La Fecha no puede ir vacia");

            if (!this.datos.nombre || this.datos.nombre == "")
                //Si la variable Fraccionamiento esta vacia
                this.msjError.push(
                    "Debe ingresar un nombre para identificar el dia"
                );

            if (this.msjError.length)
                //Si el mensaje tiene almacenado algo en el array
                this.errorDia = 1;

            return this.errorDia;
        }
    },
    mounted() {
        this.datos = {...this.datos, ...this.data}
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
