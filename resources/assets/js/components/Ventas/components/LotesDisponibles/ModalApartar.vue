<template>
    <ModalComponent :titulo="titulo" @closeModal="$emit('close')">
        <template v-slot:body>
            <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input"
                    >Vendedor</label
                >
                <div class="col-md-6">
                    <select
                        v-model="data.vendedor_id"
                        class="form-control"
                        @change="selectClientes(data.vendedor_id)"
                    >
                        <option value="0">Seleccione</option>
                        <option
                            v-for="vendedor in arrayVendedores"
                            :key="vendedor.id"
                            :value="vendedor.id"
                            v-text="vendedor.n_completo"
                        ></option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input"
                    >Cliente</label
                >
                <div class="col-md-6">
                    <select v-model="data.cliente_id" class="form-control">
                        <option value="0">Seleccione</option>
                        <option
                            v-for="clientes in arrayClientes"
                            :key="clientes.id"
                            :value="clientes.id"
                            v-text="clientes.n_completo"
                        ></option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-secondary" @click="$emit('verObs', data.cliente_id )">Observaciones</button>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input"
                    >Tipo de Crédito</label
                >
                <div class="col-md-6">
                    <select v-model="data.credito" class="form-control">
                        <option value="">Seleccione</option>
                        <option
                            v-for="credito in arrayCreditos"
                            :key="credito.nombre"
                            :value="credito.nombre"
                            v-text="credito.nombre"
                        ></option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input"
                    >Fecha apartado</label
                >
                <div class="col-md-4">
                    <label
                        v-if="tipoAccion == 2"
                        v-text="data.fecha_mostrar"
                    ></label>
                    <label
                        v-if="tipoAccion == 3"
                        v-text="data.fecha_apartado_format"
                    ></label>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input"
                    >Comentario</label
                >
                <div class="col-md-6">
                    <textarea
                        rows="3"
                        cols="30"
                        v-model="data.comentario"
                        class="form-control"
                        placeholder="Comentario"
                    ></textarea>
                </div>
            </div>
        </template>
        <template v-slot:buttons-footer>
            <button
                type="button"
                v-if="tipoAccion == 2"
                class="btn btn-primary"
                @click="apartarLote()"
            >
                Apartar
            </button>
            <button
                type="button"
                v-if="tipoAccion == 3"
                class="btn btn-warning"
                @click="desapartarLote()"
            >
                Desapartar
            </button>
            <button
                type="button"
                v-if="tipoAccion == 3"
                class="btn btn-success"
                @click="actualizarLote()"
            >
                Guardar
            </button>
        </template>
    </ModalComponent>
</template>

<script>
import ModalComponent from "../../../Componentes/ModalComponent.vue";
export default {
    components: {
        ModalComponent
    },
    props: {
        titulo: {
            type: String,
            default: "",
            required: true
        },
        arrayVendedores: {
            type: Array,
            default: [],
            required: true
        },
        arrayCreditos: {
            type: Array,
            default: [],
            required: true
        },
        tipoAccion: {
            type: Number,
            default: "",
            required: true
        },
        datos: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            arrayClientes: [],
            proceso: false,
            errorLote: 0,
            errorMostrarMsjLote: [],
            data: {
                vendedor_id: "",
                cliente_id: "",
                credito: "",
                fecha_mostrar: "",
                fecha_apartado_format: "",
                comentario: "",
                fraccionamiento_id: "",
                lote_id: "",
                apartado_id: ""
            }
        };
    },
    methods: {
        selectClientes(vendedor) {
            let me = this;
            me.arrayClientes = [];
            var url =
                "/select_clientes?vendedor_id=" +
                vendedor +
                "&fraccionamiento_id=" +
                this.data.fraccionamiento_id;
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.arrayClientes = respuesta.clientes;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        selectDatosApartado(lote_id) {
            let me = this;
            let arrayDatosApartado = [];
            var url = "/select_datos_apartado?lote_id=" + lote_id;
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    arrayDatosApartado = respuesta.apartados;
                    me.data.vendedor_id = arrayDatosApartado[0].vendedor_id;
                    me.data.cliente_id = arrayDatosApartado[0].cliente_id;
                    me.data.fecha_apartado =
                        arrayDatosApartado[0].fecha_apartado;
                    me.data.credito = arrayDatosApartado[0].tipo_credito;
                    me.data.comentario = arrayDatosApartado[0].comentario;
                    me.data.fecha_apartado_format = moment(
                        me.data.fecha_apartado
                    )
                        .locale("es")
                        .format("DD [de] MMMM [de] YYYY");
                    me.selectClientes(me.data.vendedor_id);
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        validarLote() {
            this.errorLote = 0;
            this.errorMostrarMsjLote = [];

            if (!this.data.fraccionamiento_id)
                //Si la variable Lote esta vacia
                this.errorMostrarMsjLote.push(
                    "El nombre del proyecto para el Lote no puede ir vacio."
                );

            if (this.errorMostrarMsjLote.length)
                //Si el mensaje tiene almacenado algo en el array
                this.errorLote = 1;

            return this.errorLote;
        },
        apartarLote() {
            if (this.validarLote() || this.proceso == true) {
                //Se verifica si hay un error (campo vacio)
                return;
            }

            this.proceso = true;
            let me = this;
            //Con axios se llama el metodo store de FraccionaminetoController
            axios
                .post("/apartado/registrar", {
                    vendedor_id: this.data.vendedor_id,
                    cliente_id: this.data.cliente_id,
                    tipo_credito: this.data.credito,
                    fecha_apartado: this.data.fecha_apartado,
                    comentario: this.data.comentario,
                    lote_id: this.data.lote_id
                })
                .then(function(response) {
                    me.proceso = false;
                    me.$emit("close");
                    //Se muestra mensaje Success
                    swal({
                        position: "top-end",
                        type: "success",
                        title: "Lote apartado correctamente",
                        showConfirmButton: false,
                        timer: 1500
                    });
                })
                .catch(function(error) {
                    console.log(error);
                    this.proceso = false;
                });
        },
        actualizarLote() {
            if (this.validarLote() || this.proceso == true) {
                //Se verifica si hay un error (campo vacio)
                return;
            }

            this.proceso = true;
            let me = this;
            //Con axios se llama el metodo store de FraccionaminetoController
            axios
                .put("/apartado/actualizar", {
                    vendedor_id: this.data.vendedor_id,
                    cliente_id: this.data.cliente_id,
                    tipo_credito: this.data.credito,
                    fecha_apartado: this.data.fecha_apartado,
                    comentario: this.data.comentario,
                    lote_id: this.data.lote_id,
                    id: this.data.apartado_id
                })
                .then(function(response) {
                    me.proceso = false;
                    me.$emit("close");
                    //Se muestra mensaje Success
                    swal({
                        position: "top-end",
                        type: "success",
                        title: "Registro actualizado correctamente",
                        showConfirmButton: false,
                        timer: 1500
                    });
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        desapartarLote() {
            swal({
                title: "¿Desea desapartar este lote?",
                text: "El lote quedara disponible para apartar!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                cancelButtonText: "Cancelar",
                confirmButtonText: "Si!"
            }).then(result => {
                if (result.value) {
                    let me = this;

                    axios
                        .delete("/apartado/eliminar", {
                            params: {
                                lote_id: this.data.lote_id,
                                id: this.data.apartado_id
                            }
                        })
                        .then(function(response) {
                            swal(
                                "Desapartado!",
                                "Lote desapartado correctamente.",
                                "success"
                            );
                            me.$emit("close");
                        })
                        .catch(function(error) {
                            console.log(error);
                        });
                }
            });
        }
    },
    async mounted() {
        this.data = { ...this.data, ...this.datos };
        if (this.tipoAccion == 3) {
            await this.selectDatosApartado(this.data.lote_id);
        }
    }
};
</script>

<style></style>
