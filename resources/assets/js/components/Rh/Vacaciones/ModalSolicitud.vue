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
            <RowModal clsRow1="col-md-4" label1="Fecha de inicio de vacaciones">
                <input type="date" class="form-control" v-model="datos.f_ini" @change="calcularDias">
            </RowModal>
            <RowModal clsRow1="col-md-4" label1="Fecha de regreso a oficina">
                <input type="date" class="form-control" v-model="datos.f_fin" @change="calcularDias">
            </RowModal>
            <RowModal clsRow1="col-md-12" label1="">
                <h5 style="text-align: center;"> Dias Disfrutados: <strong>{{ datos.dias_tomados }}</strong></h5>
            </RowModal>
        </template>
    </ModalComponent>
</template>
<script>
import ModalComponent from '../../Componentes/ModalComponent';
import RowModal from '../../Componentes/ComponentesModal/RowModalComponent';
import Button from '../../Componentes/ButtonComponent';
export default {
    components: {
        ModalComponent,
        RowModal,
        Button
    },
    props: {
        data: { type: Object },
        titulo: { type: String },
        accion: { type: String }
    },
    data() {
        return {
            datos: {
                f_ini: '',
                f_fin: '',
                dias_tomados: 0,
                nota: '',
                status: 'pendiente',
                vacation_id: '',
                dias_disponibles: 0
            }
        }
    },
    methods: {
        calcularDias(){
            this.datos.dias_tomados = 0;
            if(this.datos.f_ini != '' && this.datos.f_fin != ''){
                // Definir las dos fechas
                let fechaInicio = moment(this.datos.f_ini);
                let fechaFin = moment(this.datos.f_fin);

                // Asegurarse de que la fecha final sea mayor que la fecha inicial
                if (fechaFin.isBefore(fechaInicio)) {
                    const tempIni = this.datos.f_ini
                    this.datos.f_fin = this.datos.f_ini
                    this.datos.f_ini = tempIni
                    const temp = fechaInicio;
                    fechaInicio = fechaFin;
                    fechaFin = temp;
                }

                // Calcular la diferencia en días
                this.datos.dias_tomados = fechaFin.diff(fechaInicio, 'days');
                const auxDiasTomados = this.datos.dias_tomados;

                // Contar los sábados como 0.5 días cada uno
                let fechaTemp = moment(fechaInicio);
                for (let i = 0; i < auxDiasTomados; i++) {
                    if (fechaTemp.format('dddd') === 'Saturday') { // 6 es sábado
                        this.datos.dias_tomados -= 0.5;
                    }
                    if (fechaTemp.format('dddd') === 'Sunday') { // 7 es domingo
                        this.datos.dias_tomados -= 1;
                    }
                    fechaTemp.add(1, 'days');
                    console.log(fechaTemp.format('dddd'))
                }
            }
        },

    },
    mounted() {
        this.datos = { ...this.datos, ...this.data }
    },

}
</script>
<style lang="">

</style>
