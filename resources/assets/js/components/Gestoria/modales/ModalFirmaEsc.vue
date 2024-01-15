<template>
    <ModalComponent
        :titulo="titulo"
        @closeModal="$emit('closeModal')"
    >
        <template v-slot:body>
            <RowModal label1="Estado" id1="estado" clsRow1="col-md-3"
                label2="Ciudad" id2="ciudad" clsRow2="col-md-3"
            >
                <select class="form-control" v-model="data.estado" @change="selectCiudades(data.estado)">
                    <option value=""> Seleccione </option>
                    <option v-for="estado in estados" :key="estado">{{ estado }}</option>
                </select>
                <template v-slot:input2>
                    <select class="form-control" v-model="data.ciudad" @change="selectNotarias(data.estado,data.ciudad)">
                        <option value=""> Seleccione </option>
                        <option v-for="ciudad in arrayCiudades" :key="ciudad.municipio" :value="ciudad.municipio" v-text="ciudad.municipio"></option>
                    </select>
                </template>
            </RowModal>

            <RowModal label1="Notaria" id1="notaria_id" clsRow1="col-md-3">
                <select class="form-control" v-model="data.notaria_id" @change="mostrarDatosNotaria(data.notaria_id)">
                    <option :value=0> Seleccione </option>
                    <option v-for="notaria in arrayNotarias" :key="notaria.id" :value="notaria.id" v-text="notaria.notaria"></option>
                </select>
            </RowModal>

            <template>
                <RowModal id1="notario" label1="Notario" clsRow1="col-md-6">
                    <input type="text" disabled v-model="data.notario" class="form-control">
                </RowModal>

                <RowModal id1="direccion_firma" label1="Dirección" clsRow1="col-md-6">
                    <input type="text" v-model="data.direccion_firma" class="form-control">
                </RowModal>
            </template>

            <div class="form-group row line-separator"></div>

            <RowModal label1="Proyecto" id1="proyecto" clsRow1="col-md-3"
                label2="Etapa" id2="etapa" clsRow2="col-md-3"
            >
                <input type="text" disabled v-model="data.proyecto" class="form-control">
                <template v-slot:input2>
                    <input type="text" disabled v-model="data.etapa" class="form-control">
                </template>
            </RowModal>

            <RowModal label1="Manzana" id1="manzana" clsRow1="col-md-3"
                label2="Lote" id2="lote" clsRow2="col-md-3"
            >
                <input type="text" disabled v-model="data.manzana" class="form-control">
                <template v-slot:input2>
                    <input type="text" disabled v-model="data.lote" class="form-control">
                </template>
            </RowModal>

            <div class="form-group row line-separator"></div>

            <RowModal label1="Valor de venta" id1="valor_venta" clsRow1="col-md-3">
                <h6><strong> ${{  $root.formatNumber(data.valor_venta)}} </strong></h6>
            </RowModal>

            <RowModal label1="Crédito Autorizado" id1="monto_credito" clsRow1="col-md-3">
                <h6> ${{  $root.formatNumber(data.monto_credito)}} </h6>
            </RowModal>

            <RowModal v-if="data.infonavit!=0" label1="Infonavit" id1="infonavit" clsRow1="col-md-3">
                <h6> ${{  $root.formatNumber(data.infonavit)}} </h6>
            </RowModal>

            <RowModal v-if="data.fovissste!=0" label1="Fovissste" id1="fovissste" clsRow1="col-md-3">
                <h6> ${{  $root.formatNumber(data.fovissste)}} </h6>
            </RowModal>

            <RowModal label1="Diferencia" id1="diferencia" clsRow1="col-md-3">
                <h6> ${{  $root.formatNumber(data.diferencia)}} </h6>
            </RowModal>

            <div class="form-group row line-separator"></div>

            <RowModal label1="Fecha firma de escrituras" id1="fecha_firma_esc" clsRow1="col-md-3">
                <input type="date" v-model="data.fecha_firma_esc" class="form-control">
            </RowModal>

            <RowModal label1="Hora" id1="hora" clsRow1="col-md-3">
                <input type="time" v-model="data.hora_firma" class="form-control">
            </RowModal>

                <!-- Div para mostrar los errores que mande validerDepartamento -->
            <div v-show="errorProgramacionFirma" class="form-group row div-error">
                <div class="text-center text-error">
                    <div v-for="error in errorMostrarMsjProgramacion" :key="error" v-text="error">
                    </div>
                </div>
            </div>
        </template>

        <template v-slot:buttons-footer>
            <Button @click="generarInstNot()" icon="icon-check">Generar</Button>
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
        tipoAccion: Number
    },
    data() {
        return {

            dias: 0,
            rest_real: 0,
            errorProgramacionFirma: 0,
            errorMostrarMsjProgramacion: [],
            estados: [
                'San Luis Potosí',
                'Baja California',
                'Baja California Sur',
                'Coahuila de Zaragoza',
                'Colima',
                'Chiapas',
                'Chihuahua',
                'Ciudad de México',
                'Durango',
                'Guanajuato',
                'Guerrero',
                'Hidalgo',
                'Jalisco',
                'México',
                'Michoacán de Ocampo',
                'Morelos',
                'Nayarit',
                'Nuevo León',
                'Oaxaca',
                'Puebla',
                'Querétaro',
                'Quintana Roo',
                'Sinaloa',
                'Sonora',
                'Tabasco',
                'Tamaulipas',
                'Tlaxcala',
                'Veracruz de Ignacio de la Llave',
                'Yucatán',
                'Zacatecas'
            ],
            arrayCiudades: [],
            arrayNotarias: [],
            data: {}
        }
    },
    computed:{

    },
    methods: {
        selectCiudades(buscar){
            let me = this;
            me.arrayCiudades=[];
            var url = '/select_ciudades?buscar=' + buscar;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayCiudades = respuesta.ciudades;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        selectNotarias(estado, ciudad){
            let me = this;
            me.arrayNotarias=[];
            var url = '/select_notarias?estado=' + estado + '&ciudad=' + ciudad;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayNotarias = respuesta.notarias;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        mostrarDatosNotaria(notaria){
            let me = this;
             let arrayDatosNotaria=[];
            var url = '/select_datos_notaria?id=' + notaria;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                    arrayDatosNotaria = respuesta.notarias;
                    me.data.notaria = arrayDatosNotaria[0]['notaria'];
                    me.data.notario = arrayDatosNotaria[0]['titular'];
                    me.data.direccion_firma = arrayDatosNotaria[0]['direccion'] + ', ' + arrayDatosNotaria[0]['colonia'] + ', C.P. ' + arrayDatosNotaria[0]['cp'];
                    this.$forceUpdate();
            })
            .catch(function (error) {
                console.log(error);
            });
        },

        generarInstNot(){
            if(this.validarProgramacion()){
                return;
            }

            let me = this;
            Swal.showLoading()
            //Con axios se llama el metodo update de LoteController
            axios.put('/expediente/generarInstruccionNot',{
                'folio':this.data.id,
                'fecha_firma_esc' : this.data.fecha_firma_esc,
                'notaria_id' : this.data.notaria_id,
                'notaria' : this.data.notaria,
                'notario' : this.data.notario,
                'hora_firma' : this.data.hora_firma,
                'direccion_firma': this.data.direccion_firma

            }).then(function (response){

                me.$emit('closeModal');
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
                    title: 'Programacion de firma generada correctamente'
                })
            }).catch(function (error){
                console.log(error);
                Swal.enableLoading()
            });
        },

        validarProgramacion(){
            this.errorProgramacionFirma=0;
            this.errorMostrarMsjProgramacion=[];

            if(this.data.notaria == 0 || !this.data.notaria) //Si la variable departamento esta vacia
                this.errorMostrarMsjProgramacion.push("Seleccionar notaria.");

            if(this.data.fecha_firma_esc== '' || !this.data.fecha_firma_esc) //Si la variable departamento esta vacia
                this.errorMostrarMsjProgramacion.push("Ingresar fecha para firma de escrituras.");


            if(this.errorMostrarMsjProgramacion.length)//Si el mensaje tiene almacenado algo en el array
                this.errorProgramacionFirma = 1;

            return this.errorProgramacionFirma;
        },
    },
    mounted() {
        this.data = {...this.datos}
        if(this.tipoAccion == 2)
        {
            this.selectCiudades(this.data.estado);
            this.selectNotarias(this.data.ciudad);
        }
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
