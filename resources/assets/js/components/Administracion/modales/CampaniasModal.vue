<template>
    <ModalComponent
        :titulo="titulo"
        @closeModal="$emit('close')"
    >
        <template v-slot:body>
            <RowModal :clsRow1="'col-md-9'" :label1="'Nombre de la campaña'">
                <input
                    type="text"
                    id="nombre_campania"
                    v-model="data.nombre_campania"
                    class="form-control"
                    placeholder="Campaña"
                />
            </RowModal>
            <RowModal :label1="'Presupuesto'" :clsRow2="'col-md-4'">
                <input
                    type="text"
                    pattern="\d*"
                    maxlength="10"
                    v-on:keypress="$root.isNumber($event)"
                    id="presupuesto"
                    v-model="data.presupuesto"
                    class="form-control"
                    placeholder="Presupuesto"
                />
                <template v-slot:input2>
                    <p class="form-control">
                        ${{ $root.formatNumber(data.presupuesto) }}
                    </p>
                </template>
            </RowModal>
            <RowModal
                :clsRow1="'col-md-3'"
                :label1="'Fecha de inicio'"
                :clsRow2="'col-md-3'"
                :label2="'Fin'"
            >
                <input
                    type="date"
                    v-model="data.fecha_ini"
                    class="form-control"
                    placeholder="Fecha de inicio"
                />
                <template v-slot:input2>
                    <input
                        type="date"
                        v-model="data.fecha_fin"
                        class="form-control"
                        placeholder="Fecha de finalización"
                    />
                </template>
            </RowModal>

            <template v-if="tipoAccion == 1">
                <RowModal
                    :clsRow1="'col-md-6'"
                    :label1="'Medio publicitario'"
                    :clsRow2="'col-md-3'"
                >
                    <input
                        type="text"
                        name="city"
                        list="cityname"
                        class="form-control"
                        v-model="data.medio_digital"
                        @keyup.enter="addMedio(data.medio_digital)"
                        placeholder="Medio de publicidad"
                    />
                    <datalist id="cityname">
                        <option value="">Seleccione</option>
                        <option
                            v-for="medios in arrayMediosPublicidad"
                            :key="medios.id"
                            :value="medios.nombre"
                            v-text="medios.nombre"
                        ></option>
                    </datalist>
                    <template v-slot:input2>
                        <Button
                            :btnClass="'btn-success'"
                            :icon="'icon-plus'"
                            title="Añadir"
                            @click="addMedio(medio_digital)"
                        ></Button>
                    </template>
                </RowModal>

                <div>
                    <div class="modal-header" v-if="medios">
                        <h5 class="modal-title">Medios elegidos</h5>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <tbody>
                            <tr v-for="(medioD, index) in medios" :key="medioD">
                                <td style="width:25%">
                                    <Button
                                        :btnClass="'btn-danger'"
                                        :size="'btn-sm'"
                                        :icon="'icon-trash'"
                                        @click="quitarMedio(index)"
                                        title="Quitar medio publicitario"
                                    ></Button>
                                </td>
                                <td v-text="medioD"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </template>

            <template v-if="tipoAccion == 2">
                <RowModal :clsRow1="'col-md-6'" :label1="'Medio publicitario'">
                    <input
                        type="text"
                        name="city"
                        list="cityname"
                        class="form-control"
                        v-model="data.medio_digital"
                        laceholder="Medio de publicidad"
                    />
                    <datalist id="cityname">
                        <option value="">Seleccione</option>
                        <option
                            v-for="medios in arrayMediosPublicidad"
                            :key="medios.id"
                            :value="medios.nombre"
                            v-text="medios.nombre"
                        ></option>
                    </datalist>
                </RowModal>
            </template>

            <div v-show="errorCampania" class="form-group row div-error">
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
                v-if="tipoAccion == 1"
                :icon="'icon-check'"
                @click="registrarCampania()"
                >Guardar</Button
            >
            <Button
                v-if="tipoAccion == 2"
                :icon="'icon-check'"
                @click="actualizarCampania()"
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
                nombre_campania : '',
                medio_digital:'',
                fecha_ini:'',
                fecha_fin:'',
                presupuesto:0,
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
            errorCampania: 0,
            errorMostrarMsj: [],
        };
    },
    computed: {},
    methods: {
        addMedio(medio) {
            this.medios.push(medio);
        },
        quitarMedio(index) {
            this.medios.splice(index, 1);
        },
        /**Metodo para registrar  */
        registrarCampania() {
            if (this.validarMedio() || this.proceso == true) {
                //Se verifica si hay un error (campo vacio)
                return;
            }
            this.proceso = true;

            let me = this;
            //Con axios se llama el metodo store de DepartamentoController
            axios
                .post("/campanias/store", {
                    nombre: this.data.nombre_campania,
                    medio_digital: this.data.medios,
                    fecha_ini: this.data.fecha_ini,
                    fecha_fin: this.data.fecha_fin,
                    presupuesto: this.data.presupuesto
                })
                .then(function(response) {
                    me.proceso = false;
                    //Se muestra mensaje Success
                    swal({
                        position: "top-end",
                        type: "success",
                        title: "Campaña guardada correctamente",
                        showConfirmButton: false,
                        timer: 1500
                    });
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        selectMedioPublicidad() {
            let me = this;
            me.arrayMediosPublicidad = [];
            var url = "/select_medio_publicidad";
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.arrayMediosPublicidad = respuesta.medios_publicitarios;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        actualizarCampania() {
            let me = this;
            //Con axios se llama el metodo update de DepartamentoController
            axios
                .put("/campanias/update", {
                    nombre: this.data.nombre_campania,
                    medio_digital: this.data.medio_digital,
                    fecha_ini: this.data.fecha_ini,
                    fecha_fin: this.data.fecha_fin,
                    presupuesto: this.data.presupuesto,
                    id: this.data.id
                })
                .then(function(response) {
                    //window.alert("Cambios guardados correctamente");
                    swal({
                        position: "top-end",
                        type: "success",
                        title: "Cambios guardados correctamente",
                        showConfirmButton: false,
                        timer: 1500
                    });
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        validarMedio() {
            this.errorCampania = 0;
            this.errorMostrarMsj = [];

            if (!this.data.nombre)
                //Si la variable departamento esta vacia
                this.errorMostrarMsj.push(
                    "El nombre de la campaña no puede ir vacio."
                );

            if (this.data.medios.length == 0)
                //Si la variable departamento esta vacia
                this.errorMostrarMsj.push("Elegir un medio digital.");

            if (!this.data.fecha_ini)
                //Si la variable departamento esta vacia
                this.errorMostrarMsj.push(
                    "Registrar la fecha de inico de la campaña."
                );

            if (!this.data.nombre)
                //Si la variable departamento esta vacia
                this.errorMostrarMsj.push(
                    "Registrar la fecha de finalizacion de la campaña."
                );

            if (this.errorMostrarMsj.length)
                //Si el mensaje tiene almacenado algo en el array
                this.errorCampania = 1;

            return this.errorCampania;
        },
    },
    mounted() {
        this.selectMedioPublicidad()
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
