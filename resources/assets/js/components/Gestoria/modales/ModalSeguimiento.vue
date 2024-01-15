<template>
    <ModalComponent
        :titulo="titulo"
        @closeModal="$emit('closeModal')"
    >
        <template v-slot:body>
            <template v-if="tipoAccion == 2">
                <RowModal clsRow1="col-md-4" label1="Fecha:" id1="fecha">
                    <input type="date"  v-model="data.fecha_ingreso" class="form-control" >
                </RowModal>
                <RowModal clsRow1="col-md-4" label1="Valor a escriturar:" id1="valor_escrituras"
                    clsRow2="col-md-3" label2=""
                >
                    <input type="text" maxlength="10" v-model="data.valor_escrituras" pattern="\d*"
                        @keypress="$root.isNumber($event)" class="form-control" placeholder="Monto">
                    <template v-slot:input2>
                        <h6 v-text="'$'+ $root.formatNumber(data.valor_escrituras)"></h6>
                    </template>
                </RowModal>
            </template>

            <template v-if="tipoAccion == 3">
                <RowModal clsRow1="col-md-4" label1="Fecha de recibido" id1="fecha_recibido">
                    <input type="date" disabled v-model="data.fecha_recibido" class="form-control">
                </RowModal>
            </template>

            <template v-if="tipoAccion == 4">
                <RowModal clsRow1="col-md-4" label1="Fecha de inscripción" id1="fecha_infonavit">
                    <input type="date"  v-model="data.fecha_infonavit" class="form-control">
                </RowModal>
            </template>

            <template v-if="tipoAccion == 5">
                <RowModal label1="Fecha" id1="fecha_concluido" clsRow1="col-md-4" label2="" clsRow2="col-md-3">
                    <input type="date" v-model="data.fecha_concluido" class="form-control">
                </RowModal>

                <RowModal label1="Valor" clsRow1="col-md-4" id1="resultado" clsRow2="col-md-3" label2="">
                    <input type="text" maxlength="10" v-model="data.resultado" pattern="\d*"
                        @keypress="$root.isNumber($event)" class="form-control" placeholder="Monto">
                    <template v-slot:input2>
                        <h6 v-text="'$'+ $root.formatNumber(data.resultado)"></h6>
                    </template>
                </RowModal>
            </template>

             <!-- Div para mostrar los errores que mande validerDepartamento -->
             <div v-show="errorIngreso" class="form-group row div-error"
                v-if="tipoAccion == 2 || tipoAccion == 4"
             >
                <div class="text-center text-error">
                    <div v-for="error in errorMostrarMsjIngreso" :key="error" v-text="error">
                    </div>
                </div>
            </div>
        </template>

        <template v-slot:buttons-footer>
            <Button v-if="tipoAccion==2 && data.valor_escrituras >0" @click="enviarIngreso()" icon="icon-check">
                Ingresar
            </Button>
            <a :href="'/expediente/solicitudPDF/' + data.id" v-if="tipoAccion==3" target="_blank" class="btn btn-primary">
                Imprimir
            </a>
            <Button v-if="tipoAccion==4" @click="inscribirInfonavit()" icon="icon-check">
                Inscribir
            </Button>
            <Button v-if="tipoAccion==5" @click="setFechaConcluido()" icon="icon-check">
                Guardar
            </Button>
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
            errorIngreso: 0,
            errorMostrarMsjIngreso: [],
            data: {}
        }
    },
    methods: {
        validarIngreso(){
            this.errorIngreso=0;
            this.errorMostrarMsjIngreso=[];

            if(this.data.fecha_ingreso== '') //Si la variable departamento esta vacia
                this.errorMostrarMsjIngreso.push("Ingresar una fecha.");

            if(this.data.valor_escrituras== '') //Si la variable departamento esta vacia
                this.errorMostrarMsjIngreso.push("Ingresar el valor a escriturar.");

            if(this.errorMostrarMsjIngreso.length)//Si el mensaje tiene almacenado algo en el array
                this.errorIngreso = 1;

            return this.errorIngreso;
        },

        validarInscripcion(){
            this.errorIngreso=0;
            this.errorMostrarMsjIngreso=[];

            if(this.data.fecha_infonavit== '') //Si la variable departamento esta vacia
                this.errorMostrarMsjIngreso.push("Ingresar la fecha de inscripción.");

            if(this.errorMostrarMsjIngreso.length)//Si el mensaje tiene almacenado algo en el array
                this.errorIngreso = 1;

            return this.errorIngreso;
        },

        enviarIngreso(){
            if(this.validarIngreso()){
                return;
            }

            let me = this;
            //Con axios se llama el metodo update de LoteController
            axios.put('/expediente/ingresarExp',{
                'folio':this.data.id,
                'fecha_ingreso' : this.data.fecha_ingreso,
                'valor_escrituras' : this.data.valor_escrituras,

            }).then(function (response){

                me.$emit('closeModal')
                //window.alert("Cambios guardados correctamente");
                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                    });
                    toast({
                    type: 'success',
                    title: 'Expediente ingresado correctamente'
                })
            }).catch(function (error){
                console.log(error);
            });
        },

        inscribirInfonavit(){
            if(this.validarInscripcion()){
                return;
            }

            let me = this;
            //Con axios se llama el metodo update de LoteController
            axios.put('/expediente/inscInfonavit',{
                'folio':this.data.id,
                'fecha_infonavit' : this.data.fecha_infonavit,

            }).then(function (response){

                me.$emit('closeModal')
                //window.alert("Cambios guardados correctamente");
                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                    });
                    toast({
                    type: 'success',
                    title: 'Expediente ingresado correctamente'
                })
            }).catch(function (error){
                console.log(error);
            });
        },

        setFechaConcluido(){
            let me = this;
            //Con axios se llama el metodo update de LoteController
            axios.put('/avaluos/fechaConcluido',{
                'avaluoId':this.data.avaluoId,
                'fecha_concluido' : this.data.fecha_concluido,
                'resultado' : this.data.resultado,

            }).then(function (response){
                me.$emit('closeModal')
                //window.alert("Cambios guardados correctamente");
                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                    });
                    toast({
                    type: 'success',
                    title: 'Fecha de pago ingresada correctamente'
                })
            }).catch(function (error){
                console.log(error);
            });
        },
    },
    mounted() {
        this.data = { ...this.datos }
    },
}
</script>
