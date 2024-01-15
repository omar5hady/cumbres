<template>
    <ModalComponent
        :titulo="titulo"
        @closeModal="$emit('closeModal')"
    >
        <template v-slot:body>
            <div class="form-group row line-separator"></div>
            <RowModal label1="Total a liquidar" clsRow1="col-md-3" id1="total_liquidar1">
                <h6><strong> ${{  $root.formatNumber(data.total_liquidar1)}} </strong></h6>
            </RowModal>

            <RowModal label1="Intereses Ordinarios (%)" id1="int_ordinario" clsRow1="col-md-2"
                label2="Fecha de inicio de intereses" clsRow2="col-md-3" id2="fecha_ini_interes"
            >
                <input type="text" maxlength="5" @keypress="$root.isNumber($event)" v-model="data.int_oridinario" class="form-control" placeholder="%" >
                <template v-slot:input2>
                    <input type="date" pattern="\d*" @keypress="$root.isNumber($event)" v-model="data.fecha_ini_interes" class="form-control">
                </template>
            </RowModal>

            <RowModal label1="Intereses Moratorios (%)" id1="int_moratorio" clsRow1="col-md-3">
                <input type="text" pattern="\d*" maxlength="3" @keypress="$root.isNumber($event)" v-model="data.int_moratorio" class="form-control" placeholder="%" >
            </RowModal>

            <div class="form-group row line-separator"></div>

            <RowModal label1="" clsRow1="col-md-12">
                <center> <h5>Pagares</h5> </center>
            </RowModal>

            <RowModal v-if="data.fecha_pago != ''" label1="" clsRow1="col-md-12">
                <h6 style="text-align: right;">Restante: </h6>
                <h4 style="text-align: right;"><strong>${{  $root.formatNumber(data.restante_pago)}}</strong></h4>
            </RowModal>

            <RowModal label1="Fecha del pago" id1="fecha_pago" clsRow1="col-md-3">
                <input @change="mostrarRestante()" type="date" v-model="data.fecha_pago" class="form-control">
            </RowModal>

            <RowModal v-if="data.fecha_pago != ''" label1="Monto pago" clsRow1="col-md-3"
                label2="" clsRow2="col-md-4"
            >
                <input type="text" pattern="\d*" v-model="data.monto_pago" maxlength="10" @keypress="$root.isNumber($event)" class="form-control">
                <template v-slot:input2>
                    <h6 style="color:#2271b3;" ><strong> Monto pago </strong></h6>
                    <h6><strong>${{  $root.formatNumber(data.monto_pago)}}</strong></h6>
                </template>
            </RowModal>

            <RowModal label1="" clsRow1="col-md-1" v-if="data.restante_pago>0">
                <Button v-if="data.monto_pago!=''" btnClass="btn-success form-control btnagregar"
                    @click="agregarPago()" icon="icon-plus">
                </Button>
            </RowModal>

            <RowModal label1="" clsRow1="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th># Pago</th>
                                <th>Fecha de pago</th>
                                <th>Dias</th>
                                <th>Pendiente + Intereses</th>
                                <th>Monto</th>
                                <th>Restante</th>
                            </tr>
                        </thead>
                        <tbody >
                            <tr v-for="(pago,index) in data.arrayPagos" :key="pago.fecha_pago">
                                <td>
                                    <button @click="eliminarPago(index)" type="button" class="btn btn-danger btn-sm">
                                        <i class="icon-close"></i>
                                    </button>

                                </td>
                                <td v-text="'Pago no. ' + parseInt(index+1)"></td>
                                <td v-text="this.moment(pago.fecha_pago).locale('es').format('DD/MMM/YYYY')"></td>
                                <td v-text="pago.dias">

                                </td>
                                    <td>
                                    {{pago.restanteAnterior | currency}}
                                </td>
                                <td>
                                    {{ pago.monto_pago | currency}}
                                </td>
                                <td>
                                    {{pago.restante | currency}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </RowModal>

            <div class="form-group row line-separator"></div>

            <RowModal label1="" clsRow1="col-md-12">
                <center> <h5>Primer Aval</h5> </center>
            </RowModal>

            <RowModal label1="Nombre" id1="nombre_aval" clsRow1="col-md-6">
                <input type="text" v-model="data.nombre_aval" class="form-control" placeholder="Nombre">
            </RowModal>

            <RowModal label1="Dirección" id1="direccion_aval" clsRow1="col-md-6">
                <input type="text" v-model="data.direccion_aval" class="form-control" placeholder="Direccion" >
            </RowModal>

            <RowModal label1="Telefono" id1="telefono_aval" clsRow1="col-md-6">
                <input type="text" pattern="\d*" maxlength="10" @keypress="$root.isNumber($event)" v-model="data.telefono_aval" class="form-control" placeholder="Telefono" >
            </RowModal>

            <div class="form-group row line-separator"></div>

            <RowModal label1="" clsRow1="col-md-12">
                <center> <h5>Segundo Aval</h5> </center>
            </RowModal>

            <RowModal label1="Nombre" id1="nombre_aval" clsRow1="col-md-6">
                <input type="text" v-model="data.nombre_aval2" class="form-control" placeholder="Nombre">
            </RowModal>

            <RowModal label1="Dirección" id1="direccion_aval" clsRow1="col-md-6">
                <input type="text" v-model="data.direccion_aval2" class="form-control" placeholder="Direccion" >
            </RowModal>

            <RowModal label1="Telefono" id1="telefono_aval" clsRow1="col-md-6">
                <input type="text" pattern="\d*" maxlength="10" @keypress="$root.isNumber($event)" v-model="data.telefono_aval2" class="form-control" placeholder="Telefono" >
            </RowModal>

        </template>

        <template v-slot:buttons-footer>
            <Button v-if="data.restante_pago == 0" @click="generarPagare()" icon="icon-check">Generar</Button>
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
            dias: 0,
            rest_real: 0,
            data: {
                arrayPagos: [],
                restante_pago: 0,
                monto_pago: 0
            }
        }
    },
    computed:{

    },
    methods: {
        generarPagare(){
            let me = this;
            Swal.showLoading()
            //Con axios se llama el metodo update de LoteController
            axios.post('/expediente/generarPagares',{
                'folio' : me.data.id,
                'intereses_ordinarios' : me.data.int_oridinario,
                'intereses_moratorios' : me.data.int_moratorio,
                'fecha_ini_interes' : me.data.fecha_ini_interes,
                'nombre_aval' : me.data.nombre_aval,
                'direccion_aval' : me.data.direccion_aval,
                'telefono_aval': me.data.telefono_aval,
                'nombre_aval2' : me.data.nombre_aval2,
                'direccion_aval2' : me.data.direccion_aval2,
                'telefono_aval2': me.data.telefono_aval2,
                'pagares' : me.data.arrayPagos
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
                    title: 'Pagares y liquidacion generados correctamente'
                })
            }).catch(function (error){
                console.log(error);
                Swal.enableLoading()
            });
        },

        mostrarRestante(){
            let restante = 0;
            let intereses = 0
            this.dias = 0;
            if(this.data.arrayPagos.length == 0){
                restante = this.data.total_liquidar1;
                var a = moment(this.data.fecha_pago);
                var b = moment(this.data.fecha_ini_interes);
                this.dias = a.diff(b, 'days'); //[days, years, months, seconds, ...]
                intereses = (this.data.int_oridinario / 100) * (this.dias/30) * (this.data.total_liquidar1);
                this.interes = Math.round(intereses*100)/100;

                restante += this.interes;
                restante = Math.round(restante*100)/100;
                this.data.restante_pago = restante;

            }else{
                this.data.restante_pago = this.data.arrayPagos[this.data.arrayPagos.length-1].restante
                var b = moment(this.data.arrayPagos[this.data.arrayPagos.length-1].fecha_pago);
                var a = moment(this.data.fecha_pago);
                this.dias = a.diff(b, 'days'); //[days, years, months, seconds, ...]
                if(this.dias == 0){
                    this.data.restante_pago = this.rest_real;
                }
                restante = this.data.restante_pago;
                intereses = (this.data.int_oridinario / 100) * (this.dias/30) * (restante);
                this.interes = Math.round(intereses*100)/100;

                restante += this.interes;
                this.data.restante_pago = restante;
            }
        },

        calcularRestante(){
            let restante = 0;
            this.dias = 0;
            if(this.data.arrayPagos.length == 0){
                restante = this.data.total_liquidar1;
                var a = moment(this.data.fecha_pago);
                var b = moment(this.data.fecha_ini_interes);
                this.dias = a.diff(b, 'days'); //[days, years, months, seconds, ...]
                var intereses = (this.data.int_oridinario / 100) * (this.dias/30) * (this.data.total_liquidar1);
                this.interes = Math.round(intereses*100)/100;

                if(this.dias > 0){
                    restante += this.interes;
                }

                restante = Math.round(restante*100)/100;
                this.rest_real = restante;
                // this.restante_pago = Restante;
                return restante;

            }else{
                var b = moment(this.data.arrayPagos[this.data.arrayPagos.length-1].fecha_pago);
                var a = moment(this.data.fecha_pago);
                this.dias = a.diff(b, 'days'); //[days, years, months, seconds, ...]
                restante =this.data.restante_pago;

                restante = Math.round(restante*100)/100;
                this.rest_real = restante;
                return restante;

            }
        },

        encuentraFecha(fecha){
            var sw=0;
            for(var i=0; i < this.data.arrayPagos.length; i++)
                if(this.data.arrayPagos[i].fecha_pago == fecha || this.data.arrayPagos[i].fecha_pago > fecha)
                    sw=true;
            return sw;
        },

        agregarPago(){
            let me = this;
            if(me.data.monto_pago == 0 || me.data.monto_pago == '' || me.data.fecha_pago == ''){

            }else{
                if(me.data.monto_pago > Math.round( me.data.restante_pago*100 )/100){
                    swal({
                        type: 'error',
                        title: 'Error',
                        text: 'El monto supera al restante',
                    });
                }
                else
                {
                    if(me.encuentraFecha(me.data.fecha_pago)){
                        swal({
                            type: 'error',
                            title: 'Error',
                            text: 'La fecha de pago ya se enceuntra o es menor',
                        })
                    }
                    else{
                        me.data.restante_pago = me.calcularRestante();
                        me.data.arrayPagos.push({
                            monto_pago: me.data.monto_pago,
                            fecha_pago: me.data.fecha_pago,
                            restanteAnterior: me.data.restante_pago,
                            dias: me.dias,
                            restante: me.data.restante_pago - me.data.monto_pago,
                        });
                        me.data.restante_pago -= me.data.monto_pago;

                        me.data.monto_pago = 0;
                    }
                }
            }
        },


        eliminarPago(index){
            let me = this;
            let restante = 0;
            let intereses = 0;
            let interes1 = 0

            me.data.arrayPagos.splice(index,1);

            if(index != 0 ){
                if(me.data.arrayPagos.length > 1)
                    for(let i=0; i < me.data.arrayPagos.length; i++){
                        let b = me.data.arrayPagos[i].fecha_pago;
                        let a = moment(me.data.arrayPagos[i+1].fecha_pago);
                        me.data.arrayPagos[i+1].dias = a.diff(b, 'days'); //[days, years, months, seconds, ...]
                        restante = me.data.arrayPagos[i].restante;
                        intereses = (me.data.int_oridinario / 100) * (me.data.arrayPagos[i+1].dias/30) * (restante);
                        interes1 = Math.round(intereses*100)/100;
                        restante += interes1;
                        restante = Math.round(restante*100)/100;
                        me.data.arrayPagos[i+1].restanteAnterior = restante;
                        me.data.arrayPagos[i+1].restante = me.data.arrayPagos[i+1].restanteAnterior - me.data.arrayPagos[i+1].monto_pago;
                        me.data.restante_pago =  me.data.arrayPagos[i+1].restante;
                    }
                else{
                    me.data.restante_pago = me.data.arrayPagos[0].restante;
                }

            }
            else{
                if(me.data.arrayPagos.length == 0){
                    restante = this.total_liquidar1;
                    var a = moment(this.fecha_pago);
                    var b = moment(this.fecha_ini_interes);
                    this.dias = a.diff(b, 'days'); //[days, years, months, seconds, ...]
                    intereses = (this.int_oridinario / 100) * (this.dias/30) * (this.total_liquidar1);
                    this.interes = Math.round(intereses*100)/100;


                    restante += this.interes;
                    restante = Math.round(restante*100)/100;
                    me.data.restante_pago = restante;
                }
                else{
                    var b = me.fecha_ini_interes
                    var a = moment(me.data.arrayPagos[0].fecha_pago);
                    me.data.arrayPagos[0].dias = a.diff(b, 'days');
                    restante = me.total_liquidar1;
                    intereses = (me.int_oridinario / 100) * (me.data.arrayPagos[0].dias/30) * (restante);
                    interes1 = Math.round(intereses*100)/100;
                    restante += interes1;
                    restante = Math.round(restante*100)/100;
                    me.data.arrayPagos[0].restanteAnterior = restante;
                    me.data.arrayPagos[0].restante = me.data.arrayPagos[0].restanteAnterior - me.data.arrayPagos[0].monto_pago;

                    for(var i=0; i< me.data.arrayPagos.length; i++){
                        var b = me.data.arrayPagos[i].fecha_pago;
                        var a = moment(me.data.arrayPagos[i+1].fecha_pago);
                        me.data.arrayPagos[i+1].dias = a.diff(b, 'days'); //[days, years, months, seconds, ...]
                        restante = me.data.arrayPagos[i].restante;
                        intereses = (me.int_oridinario / 100) * (me.data.arrayPagos[i+1].dias/30) * (restante);
                        interes1 = Math.round(intereses*100)/100;
                        restante += interes1;
                        restante = Math.round(restante*100)/100;
                        me.data.arrayPagos[i+1].restanteAnterior = restante;
                        me.data.arrayPagos[i+1].restante = me.data.arrayPagos[i+1].restanteAnterior - me.data.arrayPagos[i+1].monto_pago;
                        me.restante_pago =  me.data.arrayPagos[i+1].restante;
                    }
                }
            }
        },
    },
    mounted() {
        this.data = { ...this.data, ...this.datos }
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
