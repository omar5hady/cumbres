<template>
    <ModalComponent
        @closeModal="$emit('close')"
        :titulo="titulo"
    >
        <template v-slot:body>
            <RowModal label1="Precio de renta:" clsRow1="col-md-6" id="monto_renta">
                <h6 style="color:blue;"
                    v-text="'$' +$root.formatNumber(monto_renta)"
                ></h6>
            </RowModal>
            <RowModal clsRow1="col-md-6" label1="Vigencia">
                <select
                    @change="calcularFechaFin()"
                    class="form-control"
                    v-model="num_meses"
                >
                    <option value="0"> Seleccione </option>
                    <option value="1"> 1 mes </option>
                    <option value="2"> 2 meses </option>
                    <option value="3"> 3 meses </option>
                    <option value="4"> 4 meses </option>
                    <option value="5"> 5 meses </option>
                    <option value="6"> 6 meses </option>
                    <option value="7"> 7 meses </option>
                    <option value="8"> 8 meses </option>
                    <option value="9"> 9 meses </option>
                    <option value="10"> 10 meses </option>
                    <option value="11"> 11 meses </option>
                    <option value="12"> 12 meses </option>
                </select>
            </RowModal>
            <RowModal label1="F. Inicio" clsRow1="col-md-3"  id1="fecha_ini"
                label2="F. Fin" clsRow2="col-md-3" id2="fecha_fin">
                <input type="date" id="fecha_ini" class="form-control"
                    @change="calcularFechaFin()" v-model="fecha_ini"
                >
                <template v-slot:input2>
                    <input type="date" id="fecha_fin" class="form-control"
                        disabled v-model="fecha_fin"
                >
                </template>
            </RowModal>
            <RowModal clsRow1="col-md-12" label1="" v-if="pagares.length > 0">
                <div class="table-responsive col-md-12">
                    <table
                        class="table table-bordered table-striped table-sm"
                    >
                        <thead>
                            <tr>
                                <th colspan="3" class="text-center th2"
                                    style="color:white; background:#252f35"
                                >
                                    PAGOS
                                </th>
                            </tr>
                            <tr>
                                <th># Pago</th>
                                <th>Fecha de pago</th>
                                <th>Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="pago in pagares" :key="pago.id">
                                <td
                                    v-text="'Pago no. ' + pago.num_pago"
                                ></td>
                                <td
                                    v-text="this.moment(pago.fecha)
                                            .locale('es')
                                            .format('DD/MMM/YYYY')
                                    "
                                ></td>
                                <td
                                    v-text="'$' +
                                        $root.formatNumber(pago.importe)
                                    "
                                ></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </RowModal>
        </template>
        <template v-slot:buttons-footer>
            <button v-if="pagares.length>0"
                class="btn btn-success" title="Guardar renovación" @click="renovar()">Guardar</button>
        </template>
    </ModalComponent>
</template>

<script>
import ModalComponent from '../../Componentes/ModalComponent.vue';
import RowModal from '../../Componentes/ComponentesModal/RowModalComponent.vue'

export default {
    components:{
        ModalComponent,
        RowModal
    },
    props:{
        datosRenta:{
            type: Object,
            require:true
        },
        titulo:{
            type: String,
            require: true
        }
    },
    data() {
        return {
            fecha_ini: '',
            fecha_fin: '',
            num_meses: '',
            monto_renta: 0,
            pagares: []
        };
    },
    computed: {

    },
    methods: {
        renovar(){
            let me = this;
            //Con axios se llama el metodo store de FraccionaminetoController
            axios.post('/rentas/renovar',{
                'id' : this.datosRenta.id,
                'fecha_ini' : this.fecha_ini,
                'fecha_fin' : this.fecha_fin,
                'num_meses' : this.num_meses,
                'monto_renta' : this.monto_renta,
                'pagares' : this.pagares,
            }).then(function (response){
                me.$emit('close')
                //Se muestra mensaje Success
                swal({
                    position: 'top-end',
                    type: 'success',
                    title: 'Renta renovada correctamente',
                    showConfirmButton: false,
                    timer: 1500
                    })
            }).catch(function (error){
                console.log(error);
            });
        },
        getDatosLoteRenta(){
            let me = this;
            var url = '/lotes/getDatosLoteRenta?id=' + me.datosRenta.lote_id;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.monto_renta = respuesta.precio_renta;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        calcularFechaFin(){
            let me = this;

            if( me.fecha_ini != ''){
                let fecha_fin = new Date(
                    me.fecha_ini.substring(5,7)
                    +'/'+me.fecha_ini.substring(8,10)
                    +'/'+me.fecha_ini.substring(0, 4)
                );
                let mes, dia, anio = ''
                dia = fecha_fin.getDate();
                mes = parseInt(fecha_fin.getMonth()+1);
                anio = fecha_fin.getFullYear();

                me.pagares = [];
                for(let i = 1; i<=me.num_meses; i++){
                    var auxMes = mes + i - 1;
                    var auxAnio = anio;
                    if( auxMes > 12)
                    {
                        auxAnio = auxAnio + 1;
                        auxMes = auxMes - 12;
                    }
                    if(auxMes < 10)
                        auxMes = '0'+(auxMes);

                    me.pagares.push({
                        id:i,
                        num_pago:i,
                        fecha: auxAnio+'-'+auxMes+'-01',
                        importe: me.monto_renta
                    });
                }

                if(dia == 1){
                    dia = 30
                    mes = (mes + parseInt(me.num_meses)-1);
                }
                else {
                    mes = (mes + parseInt(me.num_meses));
                    dia = dia -1;
                }


                if( mes > 12)
                {
                    anio = anio + 1;
                    mes = mes - 12;
                }
                if(mes == 2 && dia > 28)
                    dia = 28;
                if(mes < 10)
                    mes = '0'+(mes);
                if(dia < 10)
                    dia = '0'+dia;

                if(mes == '01' || mes == '03' || mes == '05' || mes == '07' || mes == '08' || mes == '10' || mes == '12')
                    if(dia == 30)
                        dia = 31;

                me.fecha_fin = anio +'-'+ mes +'-'+ dia;
            }
        },
    },
    mounted() {
        this.getDatosLoteRenta(this.datosRenta.lote_id)
    }
};

</script>
