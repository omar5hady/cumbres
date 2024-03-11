<template>
    <ModalComponent :titulo="titulo" @closeModal="$emit('close')">
        <template v-slot:body>
            <RowModal
                clsRow1="col-md-3"
                label1="Proyecto"
                label2="Etapa"
                clsRow2="col-md-3"
            >
                <input
                    type="text"
                    disabled
                    v-model="datos.proyecto"
                    class="form-control"
                />
                <template v-slot:input2>
                    <input
                        type="text"
                        disabled
                        v-model="datos.etapa"
                        class="form-control"
                    />
                </template>
            </RowModal>

            <RowModal
                clsRow1="col-md-3"
                label1="Manzana"
                clsRow2="col-md-3"
                label2="Lote"
            >
                <input
                    type="text"
                    disabled
                    v-model="datos.manzana"
                    class="form-control"
                />
                <template v-slot:input2>
                    <input
                        type="text"
                        disabled
                        v-model="datos.lote"
                        class="form-control"
                    />
                </template>
            </RowModal>

            <RowModal clsRow1="col-md-6" label1="Cliente">
                <input
                    type="text"
                    disabled
                    v-model="datos.cliente"
                    class="form-control"
                />
            </RowModal>

            <div class="form-group row line-separator"></div>

            <RowModal
                v-if="tipoAccion == 1"
                clsRow1="col-md-4"
                label1="Depositos"
            >
                <h6>
                    <strong> ${{ $root.formatNumber(datos.depositos) }} </strong>
                </h6>
            </RowModal>

            <template v-if="datos.arrayGastos.length > 0 && tipoAccion == 1">
                <RowModal clsRow1="col-md-12" label1="">
                    <h6><strong> GASTOS ADMINISTRATIVOS </strong></h6>
                </RowModal>
                <template v-for="gasto in datos.arrayGastos">
                    <RowModal clsRow1="col-md-3" :label1="gasto.concepto">
                        <h6>${{ $root.formatNumber(gasto.costo) }}</h6>
                    </RowModal>
                </template>
            </template>

            <template v-if="datos.descuento > 0 && tipoAccion == 1">
                <RowModal clsRow1="col-md-12" label1="">
                    <h6><strong> DESCUENTO </strong></h6>
                </RowModal>

                <RowModal
                    clsRow1="col-md-3"
                    :label1="datos.obs_descuento ? datos.obs_descuento : ''"
                    v-if="datos.descuento > 0"
                >
                    <h6>${{ $root.formatNumber(datos.descuento) }}</h6>
                </RowModal>
            </template>

            <template v-if="tipoAccion == 1">
                <RowModal clsRow1="col-md-12" label1="">
                    <h5 align="center"><strong> TOTAL A DEVOLVER </strong></h5>
                </RowModal>

                <RowModal clsRow1="col-md-12" label1="">
                    <h5 align="center">
                        <strong>
                            ${{
                                $root.formatNumber(
                                    (datos.devolver =
                                        datos.descuento +
                                        datos.depositos -
                                        datos.totalGastos)
                                )
                            }}
                        </strong>
                    </h5>
                </RowModal>

                <RowModal
                    clsRow1="col-md-3"
                    label1="Cantidad a devolver"
                    label2=""
                    clsRow2="col-md-3"
                >
                    <input
                        type="text"
                        pattern="\d*"
                        v-on:keypress="$root.isNumber($event)"
                        v-model="datos.cant_dev"
                        class="form-control"
                        placeholder="Concepto"
                    />
                    <template v-slot:input2>
                        <h6>
                            <strong>
                                ${{
                                    $root.formatNumber(
                                        (datos.cant_dev = TotalDev)
                                    )
                                }}
                            </strong>
                        </h6>
                    </template>
                </RowModal>
            </template>

            <template v-if="tipoAccion == 2">
                <RowModal clsRow1="col-md-12" label1="">
                    <h5 align="center"><strong> Cantidad devuelta </strong></h5>
                </RowModal>

                <RowModal clsRow1="col-md-12" label1="">
                    <h5 align="center">
                        <strong>
                            ${{ $root.formatNumber(datos.cant_dev) }}
                        </strong>
                    </h5>
                </RowModal>
            </template>

            <div class="form-group row line-separator"></div>

            <RowModal label1="Fecha" clsRow1="col-md-4">
                <input
                    class="form-control"
                    type="date"
                    :disabled="tipoAccion == 2"
                    v-model="datos.fecha_devolucion"
                />
            </RowModal>

            <RowModal label1="# Cheque" clsRow1="col-md-4">
                <input
                    class="form-control"
                    type="text"
                    :disabled="tipoAccion == 2"
                    v-model="datos.cheque"
                />
            </RowModal>

            <RowModal label1="Cuenta de Banco" clsRow1="col-md-6">
                <select
                    :disabled="tipoAccion == 2"
                    class="form-control"
                    v-model="datos.banco"
                >
                    <option value="">Seleccione</option>
                    <option
                        v-for="(banco,index) in arrayBancos"
                        :key="index"
                        :value="banco.num_cuenta + '-' + banco.banco"
                        v-text="banco.num_cuenta + '-' + banco.banco"
                    ></option>
                </select>
            </RowModal>

            <RowModal clsRow1="col-md-9" label1="Observaciones">
                <textarea
                    :disabled="tipoAccion == 2"
                    rows="3"
                    cols="30"
                    v-model="datos.observaciones"
                    class="form-control"
                    placeholder="Observaciones"
                ></textarea>
            </RowModal>

            <div v-show="errorDev" class="form-group row div-error">
                <div class="text-center text-error">
                    <div
                        v-for="error in errorMostrarMsjDev"
                        :key="error"
                        v-text="error"
                    ></div>
                </div>
            </div>
        </template>
        <template v-slot:buttons-footer>
            <button class="btn btn-primary"
                type="button"
                v-if="tipoAccion == 1"
                @click="generarDevolucion()"
            >
                Generar
            </button>
        </template>
    </ModalComponent>
</template>
<script>
import ModalComponent from '../../Componentes/ModalComponent';
import RowModal from '../../Componentes/ComponentesModal/RowModalComponent';
export default {
    components:{
        ModalComponent,
        RowModal
    },
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
            require: false,
            default:{
                id: '',
                proyecto: '',
                etapa: '',
                manzana: '',
                lote: '',
                cliente: '',
                depositos: 0,
                devolver: '',
                cheque: '',
                observaciones: '',
                cant_dev: 0,
                descuento: 0,
                obs_descuento: '',
                arrayGastos: [],
                totalGastos: 0,
            }
        }
    },
    computed:{
        TotalDev: function(){
            var totalDev =0.0;
            if(parseFloat(this.datos.cant_dev) > parseFloat(this.datos.devolver))
                this.datos.cant_dev = this.datos.devolver;
            totalDev = this.datos.cant_dev;
            return totalDev;
        },
    },
    data() {
        return {
            errorDev : 0,
            errorMostrarMsjDev : [],
            arrayBancos: [],
            proceso : false,
            datos:{
                id: '',
                proyecto: '',
                etapa: '',
                manzana: '',
                lote: '',
                cliente: '',
                depositos: 0,
                devolver: '',
                cheque: '',
                observaciones: '',
                cant_dev: 0,
                descuento: 0,
                obs_descuento: '',
                arrayGastos: [],
                totalGastos: 0,
            }
        }
    },
    methods: {
        selectCuenta(){
            let me = this;
            me.arrayBancos=[];
            var url = '/select_cuenta';
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayBancos = respuesta.cuentas;
            })
            .catch(function (error) {
                console.log(error);
            });

        },
        listarGastos(){
            let me = this;
            me.datos.arrayGastos = [];
            var url = '/expediente/gastosExpediente?folio=' + this.data.id;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.datos.arrayGastos = respuesta.gastos;
                me.datos.totalGastos = respuesta.totalGastos[0].sumGasto;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        validarDev(){
            this.errorDev=0;
            this.errorMostrarMsjDev=[];

            if(this.datos.cant_dev== 0) //Si la variable departamento esta vacia
                this.errorMostrarMsjDev.push("Ingresar cantidad a devolver.");

            if(this.datos.fecha_devolucion == '') //Si la variable departamento esta vacia
                this.errorMostrarMsjDev.push("Ingresar fecha de devolución.");

            if(this.datos.cheque == '') //Si la variable departamento esta vacia
                this.errorMostrarMsjDev.push("Ingresar numero de cheque.");

            if(this.datos.banco == '') //Si la variable departamento esta vacia
                this.errorMostrarMsjDev.push("Seleccionar cuenta de banco.");

            if(this.errorMostrarMsjDev.length)//Si el mensaje tiene almacenado algo en el array
                this.errorDev = 1;

            return this.errorDev;
        },

        generarDevolucion(){
            if(this.validarDev()) //Se verifica si hay un error (campo vacio)
            {
                return;
            }
            let me = this;
            me.proceso = true;
            axios.post('/devolucion/registrar',{
            'id': this.datos.id,
            'devolver': this.datos.devolver,
            'cant_dev' : this.datos.cant_dev,
            'fecha': this.datos.fecha_devolucion,
            'cheque': this.datos.cheque,
            'cuenta': this.datos.banco,
            'observaciones': this.datos.observaciones
            }).then(function (response){
            me.proceso=false;
            me.$emit('close'); //al guardar el registro se cierra el modal
            //Se muestra mensaje Success
            swal({
                position: 'top-end',
                type: 'success',
                title: 'Devolucion generada correctamente',
                showConfirmButton: false,
                timer: 1500
                })
            }).catch(function (error){
                console.log(error);
                me.proceso=false;
            });
        },
    },
    async mounted() {
        this.datos = { ...this.datos, ...this.data }
        this.selectCuenta();
        await this.listarGastos();
    },
}
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
