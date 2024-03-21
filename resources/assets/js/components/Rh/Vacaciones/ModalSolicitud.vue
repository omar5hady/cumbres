<template>
    <ModalComponent
        :titulo="titulo"
        @closeModal="$emit('close')"
    >
        <template v-slot:body>
            <RowModal clsRow1="col-md-12" label1="">
                <h5 style="text-align: center;"> Dias Disponibles: <strong>{{ datos.dias_disponibles }}</strong></h5>
            </RowModal>
            <hr>

            <RowModal label1="Autorizado por:" clsRow1="col-md-8">
                <select class="form-control" v-model="datos.jefe_id">
                    <option value="">Seleccione...</option>
                    <option v-for="jefe in jefes" :key="jefe.id"
                        :value="jefe.id">{{ jefe.nombre }} {{ jefe.apellidos }}
                    </option>
                </select>
            </RowModal>

            <RowModal clsRow1="col-md-4" label1="F. Inicio: " clsRow2="col-md-4" label2="F. Regreso">
                <input type="date" class="form-control" v-model="datos.f_ini" @change="calcularDias">
                <template v-slot:input2>
                    <input type="date" class="form-control" v-model="datos.f_fin" @change="calcularDias">
                </template>
            </RowModal>

            <hr>

            <template v-if="arrayMediosDias.length > 0">
                <TableComponent :cabecera="['','Fecha', 'Completo', '1/2 Día', 'Horario ausencia']">
                    <template v-slot:tbody>
                        <tr v-for="(dia, index) in arrayMediosDias" :key="dia.fecha">
                            <td class="td2">
                                <button class="btn btn-sm btn-success" v-if="dia.medio_dia != 2"
                                    title="Activo" @click="desactivarDia(index)">
                                    <span><i class="fa fa-power-off"></i></span>
                                </button>
                                <button class="btn btn-sm btn-danger" v-else
                                    title="Activo" @click="dia.medio_dia = 0">
                                    <span><i class="fa fa-power-off"></i></span>
                                </button>
                            </td>
                            <td class="td2">
                                <label :class="dia.medio_dia == 2 ? 'desactivado' : ''">
                                    {{ this.moment(dia.fecha).locale('es').format("dddd DD [de] MMMM [de] YYYY") }}
                                </label>

                            </td>
                            <td class="td2">
                                <input :disabled="dia.medio_dia == 2" class="form-control" type="radio" v-model="dia.medio_dia" :value="0" :name="dia.fecha" :id="`${dia.fecha}1`">
                            </td>
                            <td class="td2">
                                <input v-if="this.moment(dia.fecha).format('dddd') != 'Saturday'"
                                    :disabled="dia.medio_dia == 2" class="form-control" type="radio" v-model="dia.medio_dia" :value="1" :name="dia.fecha" :id="`${dia.fecha}2`">
                            </td>
                            <td class="td2">
                                <select v-if="dia.medio_dia == 1" class="form-control" v-model="dia.horario">
                                    <option value="0">9:00am a 1:00pm</option>
                                    <option value="1">2:00pm a 5:00pm</option>
                                </select>
                            </td>
                        </tr>
                    </template>
                </TableComponent>
            </template>

            <hr>

            <RowModal clsRow1="col-md-12" label1="">
                <h5 style="text-align: center;"> Dias a disfrutar: <strong>{{ datos.dias_tomados = diasTomados }}</strong></h5>
            </RowModal>


            <template  v-if="datos.dias_tomados > 0">
                <RowModal clsRow1="col-md-12" label1="">
                    <h5 style="text-align: center;"> Dias por disfrutar: <strong>{{ datos.saldo = diasPorDisfrutar }}</strong></h5>
                </RowModal>
                <hr>
                <RowModal clsRow1="col-md-6" label1="Notas:">
                    <textarea class="form-control" v-model="datos.nota"></textarea>
                </RowModal>
            </template>

        </template>
        <template v-slot:buttons-footer>
            <Button v-if="arrayMediosDias.length > 0 && datos.saldo >= 0 && datos.jefe_id != ''"
                @click="save()" icon="icon-check">Enviar Solicitud</Button>
        </template>
    </ModalComponent>
</template>
<script>
import ModalComponent from '../../Componentes/ModalComponent';
import RowModal from '../../Componentes/ComponentesModal/RowModalComponent';
import Button from '../../Componentes/ButtonComponent';
import TableComponent from '../../Componentes/TableComponent';
export default {
    components: {
        ModalComponent,
        TableComponent,
        RowModal,
        Button,
    },
    computed:{
        diasPorDisfrutar: function(){
            let saldo = 0;
            saldo = this.datos.dias_disponibles - this.datos.dias_tomados
            return saldo;
        },
        diasTomados: function(){
            let suma = 0;
            let medios_dias = 0;

            if(this.arrayMediosDias.length > 0){
                this.arrayMediosDias.forEach(element => {
                    const dia = moment(element.fecha).format('dddd')
                    medios_dias+=element.medio_dia;
                    if(dia === 'Saturday'){
                        medios_dias-=element.medio_dia/2;
                    }

                });
                medios_dias/=2;
            }
            suma = this.datos.dias_elegidos - this.datos.dias_festivos - medios_dias;

            if(suma < 0)
                suma = 0;

            return suma;
        },
    },
    props: {
        data: { type: Object },
        titulo: { type: String },
        accion: { type: String }
    },
    data() {
        return {
            proceso: false,
            datos: {
                f_ini: '',
                f_fin: '',
                nota: '',
                status: 'pendiente',
                vacation_id: '',
                dias_elegidos: 0,
                dias_disponibles: 0,
                dias_festivos: 0,
                dias_tomados: 0,
                saldo: 0,
                jefe_id: ''
            },
            dias_festivos: [],
            arrayMediosDias: [],
            jefes: []
        }
    },
    methods: {
        getJefes(){
            let me = this;

            const url = `/getJefes`
            axios
                .get(url)
                .then(function(response) {
                    const respuesta = response.data;
                    me.jefes = respuesta;
                })
                .catch(function(error) {
                    me.jefes = [];
                });
        },
        desactivarDia(index){
            this.arrayMediosDias[index].medio_dia = 2;
            this.arrayMediosDias[index].horario = '0';
        },
        actualizarArrayMediosDias() {
            let me = this;

            if (me.arrayMediosDias.length > 0 && me.dias_festivos.length > 0) {
                me.arrayMediosDias = me.arrayMediosDias.filter(dia => {
                    const coincide = me.dias_festivos.find(festivo => festivo.fecha === dia.fecha && festivo.medio_dia != 1);
                    return !coincide;
                });
            }
        },
        getDiasFestivos(){
            let me = this;
            me.dias_festivos= [];
            me.datos.dias_festivos = 0;
            let fechaFin = moment(me.datos.f_fin);
            const url = `/searchDiasFestivos?f_ini=${me.datos.f_ini}&f_fin=${me.datos.f_fin}`
            axios
                .get(url)
                .then(function(response) {
                    const respuesta = response.data;
                    me.dias_festivos = respuesta;
                    if( me.dias_festivos.length > 0 ){
                        me.dias_festivos.forEach(dia => {
                            if( dia.fecha != me.datos.f_fin){
                                if(dia.medio_dia === 1)
                                    me.datos.dias_festivos += .5;
                                else{
                                    let fechaTemp = moment(dia.fecha);
                                    if(fechaTemp.format('dddd') === 'Saturday')
                                        me.datos.dias_festivos += .5
                                    else
                                        me.datos.dias_festivos += 1;
                                }
                            }
                        });
                    }
                    me.actualizarArrayMediosDias();

                })
                .catch(function(error) {
                    me.dias_festivos = [];
                });
        },

        save(){
            let me = this;

            if(me.proceso) return;

            me.proceso = true;
            Swal({
                title: '¿Enviar la solicitud?',
                animation: false,
                customClass: 'animated bounceInDown',
                text: "La solicitud sera enviada para su aprobación.",
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',

                confirmButtonText: 'Si, enviar!'
            }).then((result) => {
                if (result.value) {
                    axios.post('/hist-vacaciones',{
                        'datos_dias': me.arrayMediosDias,

                        'f_ini': me.datos.f_ini,
                        'f_fin': me.datos.f_fin,
                        'nota': me.datos.nota,
                        'vacation_id': me.datos.vacation_id,
                        'dias_tomados': me.datos.dias_tomados,
                        'dias_elegidos': me.datos.dias_elegidos,
                        'dias_disponibles': me.datos.dias_disponibles,
                        'dias_festivos': me.datos.dias_festivos,
                        'saldo': me.datos.saldo,
                        'jefe_id': me.datos.jefe_id,
                    }).then(function (response){
                        me.$emit('close')
                        me.proceso = false;

                        const toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });

                        toast({
                            type: 'success',
                            title: 'Movimiento guardado correctamente.'
                        })
                    }).catch(function (error){
                        console.log(error);
                        me.proceso = false;
                    });
                }
            })



        },

        calcularDias(){
            let me = this;
            me.arrayMediosDias = [];
            me.datos.dias_elegidos = 0;
            if(me.datos.f_ini != '' && me.datos.f_fin != ''){
                me.getDiasFestivos();
                // Definir las dos fechas
                let fechaInicio = moment(me.datos.f_ini);
                let fechaFin = moment(me.datos.f_fin);

                // Asegurarse de que la fecha final sea mayor que la fecha inicial
                if (fechaFin.isBefore(fechaInicio)) {
                    const tempIni = me.datos.f_ini
                    me.datos.f_fin = me.datos.f_ini
                    me.datos.f_ini = tempIni
                    const temp = fechaInicio;
                    fechaInicio = fechaFin;
                    fechaFin = temp;
                }

                // Calcular la diferencia en días
                me.datos.dias_elegidos = fechaFin.diff(fechaInicio, 'days');
                const auxDiasTomados = me.datos.dias_elegidos;

                // Contar los sábados como 0.5 días cada uno
                let fechaTemp = moment(fechaInicio);
                for (let i = 0; i < auxDiasTomados; i++) {
                    if (fechaTemp.format('dddd') === 'Saturday') // 6 es sábado
                        me.datos.dias_elegidos -= 0.5;
                    if (fechaTemp.format('dddd') === 'Sunday') // 7 es domingo
                        me.datos.dias_elegidos -= 1;
                    if(fechaTemp.format('dddd') != 'Sunday'){
                        const fechaActual = fechaTemp.format('YYYY-MM-DD');
                        me.arrayMediosDias.push({ fecha: fechaActual, medio_dia: 0, horario: '0' });
                    }

                    fechaTemp.add(1, 'days');
                }
            }
        },

    },
    mounted() {
        this.datos = { ...this.datos, ...this.data }
        this.getJefes()
    },

}
</script>
<style lang="">
    .desactivado{
        color: #bbbbbb
    }
</style>
