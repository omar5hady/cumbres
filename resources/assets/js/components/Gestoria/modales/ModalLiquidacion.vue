<template>
    <ModalComponent
        :titulo="titulo"
        @closeModal="$emit('closeModal')"
    >
        <template v-slot:body>
            <RowModal label1="Cliente" id1="cliente" clsRow1="col-md-5"
                label2="Fecha" id2="fecha" clsRow2="col-md-3">
                <input type="text" disabled v-model="data.cliente" class="form-control" placeholder="Cliente" >
                <template v-slot:input2>
                    <input type="date" v-model="data.fecha_liquidacion" class="form-control">
                </template>
            </RowModal>

            <RowModal label1="Tipo de crédito" clsRow1="col-md-3" id1="credito"
                label2="Inst. Financiamiento" clsRow2="col-md-3" id2="int_fin"
            >
                <input type="text" disabled v-model="data.credito" class="form-control" >
                <template v-slot:input2>
                    <input type="text" disabled v-model="data.inst_fin" class="form-control">
                </template>
            </RowModal>

            <RowModal label1="Proyecto" clsRow1="col-md-3" id1="proyecto"
                label2="Etapa" clsRow2="col-md-3" id2="etapa"
            >
                <input type="text" disabled v-model="data.proyecto" class="form-control" >
                <template v-slot:input2>
                    <input type="text" disabled v-model="data.etapa" class="form-control">
                </template>
            </RowModal>

            <RowModal label1="Manzana" id1="manzana" clsRow1="col-md-3"
                label2="Lote" id2="lote" clsRow2="col-md-3"
            >
                <input type="text" disabled v-model="data.manzana" class="form-control" >
                <template v-slot:input2>
                    <input type="text" disabled v-model="data.lote" class="form-control">
                </template>
            </RowModal>

            <RowModal label1="Fecha firma de contrato" id1="fecha_firma_contrato" clsRow1="col-md-3">
                <input type="date" disabled v-model="data.fecha_firma_contrato" class="form-control">
            </RowModal>

            <div class="form-group row line-separator"></div>

            <RowModal label1="Valor de venta" id1="valor_venta" clsRow1="col-md-3">
                <h6 v-text="'$'+ $root.formatNumber(data.valor_venta)"></h6>
            </RowModal>

            <RowModal label1="Valor de escrituración" id1="valor_escrituras" clsRow1="col-md-3"
                label2="" clsRow2="col-md-3"
            >
                <input maxlength="10" v-model="data.valor_escrituras" pattern="\d*"
                    @keypress="$root.isNumber($event)" class="form-control">
                <template v-slot:input2>
                    <h6 v-text="'$'+ $root.formatNumber(data.valor_escrituras)"></h6>
                </template>
            </RowModal>

            <RowModal v-if="data.intereses_terreno>0"
                label1="Intereses" id1="intereses_terreno" clsRow1="col-md-3">
                <h6 v-text="'$'+ $root.formatNumber(data.intereses_terreno)"></h6>
            </RowModal>

            <template v-if="data.arrayGastos.length">
                <RowModal v-for="gasto in arrayGastos" :key="gasto.id"
                    :label1="gasto.concepto" clsRow1="col-md-3"
                >
                    <h6>${{  $root.formatNumber(gasto.costo)}}</h6>
                </RowModal>
            </template>

            <RowModal label1="Enganche" id1="totalEnganche" clsRow1="col-md-3">
                <h6 v-text="'$'+ $root.formatNumber(data.totalEnganghe)"></h6>
            </RowModal>

            <RowModal label1="Pagado" id1="pagado" clsRow1="col-md-3"
                :label2="data.avaluo ? 'Resultado avaluo' : ''" id2="avaluo" :clsRow2="data.avaluo ? 'col-md-2' : ''"
            >
                <h6 v-text="'$'+ $root.formatNumber(data.pagado=data.pagos)"></h6>
                <template v-slot:input2>
                    <h6 v-if="data.avaluo" v-text="'$'+ $root.formatNumber(data.avaluo)"></h6>
                </template>
            </RowModal>

            <RowModal label1="Descuento" id1="descuento" clsRow1="col-md-2"
                label2="" clsRow2="col-md-2"
            >
                <input maxlength="10" v-model="data.descuento" pattern="\d*" @keypress="$root.isNumber($event)" class="form-control">
                <template v-slot:input2>
                    <h6 v-text="'$'+ $root.formatNumber(data.descuento)"></h6>
                </template>
            </RowModal>

            <RowModal v-if="data.descuento > 0"
                label1="Observación del descuento" clsRow1="col-md-8" id1="obs_descuento">
                <textarea rows="2" cols="30" class="form-control"
                    v-model="data.obs_descuento" placeholder="Observaciones"></textarea>
            </RowModal>

            <RowModal label1="Crédito autorizado" clsRow1="col-md-3" id1="monto_credito"
                label2="" clsRow2="col-md-3"
            >
                <input v-model="data.monto_credito" @keyup.enter="updateMontoCredito()" pattern="\d*" @keypress="$root.isNumber($event)" class="form-control">
                <template v-slot:input2>
                    <h6>${{  $root.formatNumber(netoCredito)}}</h6>
                </template>
            </RowModal>

            <RowModal v-if="data.credito=='Alia2' || data.credito=='Respalda2' || data.credito=='INFONAVIT-FOVISSSTE' || data.credito == 'Fovissste para todos'"
                label1="Fovisste" clsRow1="col-md-2" id1="fovisste"
                label2="" clsRow2="col-md-2"
            >
                <input type="text" pattern="\d*" v-model="data.fovissste" maxlength="10"
                    @keypress="$root.isNumber($event)" class="form-control">
                <template v-slot:input2>
                    <h6 v-text="'$'+ $root.formatNumber(data.fovissste)"></h6>
                </template>
            </RowModal>

            <RowModal v-if="data.credito.toLowerCase().includes('cofinavit') || data.credito == 'Infonavit Mas Banco'"
                label1="Infonavit" clsRow1="col-md-2" id1="infonavit"
                label2="" clsRow2="col-md-2"
            >
                <input type="text" pattern="\d*" v-model="data.infonavit" maxlength="10" @keypress="$root.isNumber($event)" class="form-control">
                <template v-slot:input2>
                    <h6 v-text="'$'+ $root.formatNumber(data.infonavit)"></h6>
                </template>
            </RowModal>

            <RowModal label1="Total a liquidar" id1="total_liquidar" clsRow1="col-md-3">
                <h6><strong> ${{  $root.formatNumber(data.total_liquidar=totalLiquidar)}} </strong></h6>
            </RowModal>

            <div class="form-group row line-separator"></div>

            <RowModal label1="Notas" id1="notas_liquidacion" clsRow1="col-md-8">
                <textarea rows="2" cols="30" class="form-control" v-model="data.notas_liquidacionnotas_liquidacion" placeholder="Notas extra"></textarea>
            </RowModal>

            <div class="form-group row line-separator"></div>

            <RowModal v-if="data.arrayPagares.length" label1="" clsRow1="col-md-12">
                <center> <h5>Pagares pendientes</h5> </center>
            </RowModal>

            <RowModal v-if="data.arrayPagares.length" label1="" clsRow1="col-md-12">
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th># Pago</th>
                            <th>Fecha de pago</th>
                            <th>Monto</th>
                        </tr>
                    </thead>
                    <tbody >
                        <tr v-for="(pago,index) in data.arrayPagares" :key="index">
                            <td v-text="'Pago no. ' + parseInt(index+1)"></td>
                            <td v-text="this.moment(pago.fecha_pago).locale('es').format('DD/MMM/YYYY')"></td>
                            <td>
                                {{ pago.monto_pago | currency}}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </RowModal>

            <div v-show="errorLiquidacion" class="form-group row div-error">
                <div class="text-center text-error">
                    <div v-for="error in errorMostrarMsjLiquidacion" :key="error" v-text="error">
                    </div>
                </div>
            </div>

        </template>

        <template v-slot:buttons-footer>
            <Button icon="icon-check"  @click="generarLiquidacion()">Generar</Button>
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
            errorLiquidacion: 0,
            errorMostrarMsjLiquidacion: [],
            data: {
                arrayGastos: [],
                arrayPagares: [],
                credito : '',
                valor_venta: 0,
                intereses_terreno: 0,
                descuento: 0,
                totalGastos: 0,
                monto_credito: 0,
                infonavit: 0,
                fovissste: 0,
                pagos: 0,
                total_liquidar: 0,
                fecha_liquidacion: '',
                valor_escrituras: 0
            }
        }
    },
    computed:{
        totalLiquidar: function(){
                var neto_credito =0;
                let me = this;
                neto_credito = parseFloat(me.data.valor_venta) + parseFloat(me.data.intereses_terreno)
                - parseFloat(me.data.descuento) + parseFloat(me.data.totalGastos) - parseFloat(me.data.monto_credito)
                - parseFloat(me.data.infonavit) - parseFloat(me.data.fovissste) - parseFloat(me.data.pagos);
            return neto_credito;
        },
        netoCredito: function(){
            var total =0;
            total = parseFloat(this.data.infonavit) + parseFloat(this.data.fovissste)
                + parseFloat(this.data.monto_credito);
        return total;
    },
    },
    methods: {
        updateMontoCredito(){
            let me = this;
            //Con axios se llama el metodo update de LoteController
            axios.put('/update/montocredito/liquidacion',{
                'id':this.data.id,
                'monto_credito' : this.data.monto_credito,
            }).then(function (response){
                //window.alert("Cambios guardados correctamente");
                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                    });
                    toast({
                    type: 'success',
                    title: 'Monto actualizado correctamente'
                })
            }).catch(function (error){
                console.log(error);
            });
        },

        validarLiquidacion(){
            this.errorLiquidacion=0;
            this.errorMostrarMsjLiquidacion=[];

            if(this.data.fecha_liquidacion== '')
                this.errorMostrarMsjLiquidacion.push("Ingresar una fecha.");

            if(this.data.valor_escrituras== '')
                this.errorMostrarMsjLiquidacion.push("Ingresar el valor a escriturar.");

            if(this.data.descuento === '')
                this.errorMostrarMsjLiquidacion.push("Ingresar descuento.");

            if(this.errorMostrarMsjLiquidacion.length)//Si el mensaje tiene almacenado algo en el array
                this.errorLiquidacion = 1;

            return this.errorLiquidacion;
        },

        generarLiquidacion(){
            if(this.validarLiquidacion()){
                return;
            }

            let me = this;
            Swal.showLoading()
            //Con axios se llama el metodo update de LoteController
            axios.put('/expediente/generarLiquidacion',{
                'folio':this.data.id,
                'fecha_liquidacion' : this.data.fecha_liquidacion,
                'valor_escrituras' : this.data.valor_escrituras,
                'descuento' : this.data.descuento,
                'total_liquidar' : this.data.total_liquidar,
                'infonavit' : this.data.infonavit,
                'fovissste': this.data.fovissste,
                'monto_credito' : this.data.monto_credito,
                'obs_descuento' : this.data.obs_descuento,
                'notas_liquidacion' : this.data.notas_liquidacion

            }).then(function (response){

                me.$emit('closeModal')
                Swal.enableLoading()
                //window.alert("Cambios guardados correctamente");
                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                    });
                    toast({
                    type: 'success',
                    title: 'Liquidacion generada correctamente'
                })
            }).catch(function (error){
                console.log(error);
                Swal.enableLoading()
            });
        },

        mostrarPagares(precio){
            let me = this;
            var url = '/expediente/pagaresExpediente?folio=' + this.datos.id;
            me.data.arrayPagares = [];
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.data.arrayPagares = respuesta.pagares;
                me.data.totalEnganghe = respuesta.calculos[0].enganche;
                me.data.totalRestante = respuesta.calculos[0].total_restante;
                me.data.pagos = respuesta.depositos.pagado;
                me.data.precio_venta = precio + respuesta.calculos[0].interesGen;
            })
            .catch(function (error) {
                console.log(error);
            });
        },

        listarGastos(){
            let me = this;
            var url = '/expediente/gastosExpediente?folio=' + this.datos.id;
            me.data.arrayGastos = [];
            me.data.totalGastos = 0;
            me.data.totalIntOrd = 0;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.data.arrayGastos = respuesta.gastos;
                if(respuesta.length > 0){
                    me.data.totalGastos = respuesta.totalGastos[0].sumGasto;
                    me.data.totalIntOrd = respuesta.totalIntereses[0].sumIntereses;
                }
            })
            .catch(function (error) {
                console.log(error);
            });
        },
    },
    mounted() {
        this.data = {...this.data, ...this.datos }
        this.mostrarPagares(this.datos.valor_venta);
        this.listarGastos();
    },
}
</script>
