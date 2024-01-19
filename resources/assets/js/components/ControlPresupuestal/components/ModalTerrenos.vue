<template>
    <ModalComponent
        :titulo="titulo"
        @closeModal="$emit('closeModal')"
    >
        <template v-slot:body>

            <RowModal label1="Nombre" id1="nombre" clsRow1="col-md-6">
                <input type="text" v-model="data.nombre" class="form-control">
            </RowModal>

            <RowModal label1="Direccion" id1="direccion" clsRow1="col-md-9">
                <input type="text" v-model="data.direccion" class="form-control">
            </RowModal>

            <RowModal label1="Tamaño m2" id1="tamanio" clsRow1="col-md-4">
                <input type="text" v-model="data.tamanio"  @keypress="$root.isNumber($event)"
                    class="form-control">
            </RowModal>

            <RowModal label1="Comprador" id1="comprador" clsRow1="col-md-8">
                <input type="text" v-model="data.comprador" class="form-control">
            </RowModal>

            <RowModal label1="Vendedor" id1="vendedor" clsRow1="col-md-8">
                <input type="text" v-model="data.vendedor" class="form-control">
            </RowModal>

            <RowModal label1="Valor de compra" id1="valor_compra" clsRow1="col-md-4"
                label2="" clsRow2="col-md-4"
            >
                <input type="text" v-model="data.valor_compra"
                    class="form-control" @keypress="$root.isNumber($event)"
                >
                <template v-slot:input2>
                    {{ $root.formatNumber(data.valor_compra) }}
                </template>
            </RowModal>

            <RowModal label1="Valor de venta" id1="valor_venta" clsRow1="col-md-4"
                label2="" clsRow2="col-md-4"
            >
                <input type="text" v-model="data.valor_venta"
                    class="form-control" @keypress="$root.isNumber($event)"
                >
                <template v-slot:input2>
                    {{ $root.formatNumber(data.valor_venta) }}
                </template>
            </RowModal>

            <RowModal label1="Valor de escritura" id1="valor_escritura" clsRow1="col-md-4"
                label2="" clsRow2="col-md-4"
            >
                <input type="text" v-model="data.valor_escritura"
                    class="form-control" @keypress="$root.isNumber($event)"
                >
                <template v-slot:input2>
                    {{ $root.formatNumber(data.valor_escritura) }}
                </template>
            </RowModal>

            <RowModal label1="Fecha contrato" id1="fecha_firma_promesa" clsRow1="col-md-4"
                label2="Fecha de escrituras" id2="fecha_firma_esc" clsRow2="col-md-4"
            >
                <input type="date" v-model="data.fecha_firma_promesa" class="form-control">
                <template v-slot:input2>
                    <input type="date" v-model="data.fecha_firma_esc" class="form-control">
                </template>
            </RowModal>


                <!-- Div para mostrar los errores que mande validerDepartamento -->
            <div v-show="error" class="form-group row div-error">
                <div class="text-center text-error">
                    <div v-for="error in msjsError" :key="error" v-text="error">
                    </div>
                </div>
            </div>
        </template>

        <template v-slot:buttons-footer>
            <Button @click="saveData()" v-if="tipoAccion == 1" icon="icon-check">Guardar</Button>
            <Button @click="updateData()" v-if="tipoAccion == 2" icon="icon-check">Guardar cambios</Button>
        </template>
    </ModalComponent>
</template>
<script>
import ModalComponent from '../../Componentes/ModalComponent';
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
            error: 0,
            msjsError: [],
            data: {
                id: '',
                nombre: '',
                direccion: '',
                valor_venta: 0,
                valor_compra: 0,
                valor_escritura: 0,
                fecha_firma_promesa: '',
                fecha_firma_esc: '',
                tamanio: 0,
                comprador: '',
                vendedor: ''
            }
        }
    },
    computed:{

    },
    methods: {


        saveData(){
            if(this.validarData()){
                return;
            }

            let me = this;
            Swal.showLoading()
            const url = '/terrenos-compra'
            //Con axios se llama el metodo update de LoteController
            axios.post( url,{
                'nombre': this.data.nombre,
                'direccion': this.data.direccion,
                'valor_venta':this.data.valor_venta,
                'valor_compra':this.data.valor_compra,
                'valor_escritura':this.data.valor_escritura,
                'fecha_firma_promesa': this.data.fecha_firma_promesa,
                'fecha_firma_esc': this.data.fecha_firma_esc,
                'tamanio':this.data.tamanio,
                'comprador': this.data.comprador,
                'vendedor': this.data.vendedor

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
                    title: 'Registro guardado correctamente'
                })
            }).catch(function (error){
                console.log(error);
                Swal.enableLoading()
            });
        },

        updateData(){
            if(this.validarData()){
                return;
            }
            let me = this;

            const url = `/terrenos-compra/${me.data.id}`

            axios.put( url ,{
                'id': me.data.id,
                'nombre': me.data.nombre,
                'direccion': me.data.direccion,
                'valor_venta':me.data.valor_venta,
                'valor_compra':me.data.valor_compra,
                'valor_escritura':me.data.valor_escritura,
                'fecha_firma_promesa': me.data.fecha_firma_promesa,
                'fecha_firma_esc': me.data.fecha_firma_esc,
                'tamanio':me.data.tamanio,
                'comprador': me.data.comprador,
                'vendedor': me.data.vendedor
            }).then(function (response){
                me.$emit('closeModal');
                //Se muestra mensaje Success
                swal({
                    position: 'top-end',
                    type: 'success',
                    title: 'Registro actualizado correctamente',
                    showConfirmButton: false,
                    timer: 1500
                    })
            }).catch(function (error){
            });
        },

        validarData(){
            this.error=0;
            this.msjsError=[];

            if(this.data.valor_compra == 0 || !this.data.valor_compra) //Si la variable departamento esta vacia
                this.msjsError.push("Ingresar monto de compra");

            if(this.data.nombre == '' || !this.data.nombre) //Si la variable departamento esta vacia
                this.msjsError.push("Ingresar nombre del terreno. (identificador)");


            if(this.msjsError.length)//Si el mensaje tiene almacenado algo en el array
                this.error = 1;

            return this.error;
        },
    },
    mounted() {
        this.data = {...this.data, ...this.datos}
    },
}
</script>
