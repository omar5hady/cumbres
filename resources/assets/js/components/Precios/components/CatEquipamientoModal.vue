<template>
    <ModalComponent
        :titulo="titulo"
        @closeModal="$emit('close')"
    >
        <template v-slot:body>
            <RowModal v-if="tipoAccion<3"
                id1="proyecto" id2="modelo" clsRow1="col-md-4" label1="Proyecto" clsRow2="col-md-4" label2="Modelo">
                <select class="form-control" id="proyecto" v-model="proyecto" @change="$root.selectModelo(proyecto)">
                    <option value="">Seleccione</option>
                    <option v-for="proyecto in $root.$data.proyectos" :key="proyecto.id"
                        :value="proyecto.id">{{ proyecto.nombre }}</option>
                </select>
                <template v-slot:input2>
                    <select class="form-control" id="modelo" v-model="catalogo.modelo_id">
                        <option value="">Seleccione</option>
                        <option v-for="modelo in $root.$data.modelos" :key="modelo.id"
                            :value="modelo.id">{{ modelo.nombre }}</option>
                    </select>
                </template>
            </RowModal>

            <RowModal v-else
                clsRow1="col-md-4" label1="Modelo">
                <input type="text" disabled class="form-control" v-model="catalogo.modelo">
            </RowModal>

            <hr>

            <h5 class="text-center"> Equipamiento Basico</h5>

            <RowModal id1="cocina_tradicional" clsRow1="col-md-7"
                label1="Cocina Tradicional">
                <input type="text" id="cocina_tradicional" class="form-control"
                    :disabled="tipoAccion==3"
                    v-model="catalogo.cocina_tradicional"
                    v-on:keypress="$root.isNumber($event)"
                >
            </RowModal>

            <RowModal id1="vestidor" clsRow1="col-md-7"
                label1="Vestidor">
                <input type="text" id="vestidor" class="form-control"
                    :disabled="tipoAccion==3"
                    v-model="catalogo.vestidor"
                    v-on:keypress="$root.isNumber($event)"
                >
            </RowModal>

            <RowModal id1="closets" clsRow1="col-md-7"
                label1="Closets">
                <input type="text" id="closets" class="form-control"
                    :disabled="tipoAccion==3"
                    v-model="catalogo.closets"
                    v-on:keypress="$root.isNumber($event)"
                >
            </RowModal>

            <h5 class="text-center"> Equipamiento Adicional</h5>

            <RowModal id1="canceles" clsRow1="col-md-7"
                label1="Canceles">
                <input type="text" id="canceles" class="form-control"
                    v-model="catalogo.canceles"
                    :disabled="tipoAccion==3"
                    v-on:keypress="$root.isNumber($event)"
                >
            </RowModal>

            <RowModal id1="persianas" clsRow1="col-md-7"
                label1="Persianas">
                <input type="text" id="persianas" class="form-control"
                    v-model="catalogo.persianas"
                    :disabled="tipoAccion==3"
                    v-on:keypress="$root.isNumber($event)"
                >
            </RowModal>

            <!-- <RowModal id1="calentador_paso" clsRow1="col-md-7"
                label1="Calentador de paso">
                <input type="text" id="calentador_paso" class="form-control"
                    v-model="catalogo.calentador_paso"
                    :disabled="tipoAccion==3"
                    v-on:keypress="$root.isNumber($event)"
                >
            </RowModal> -->

            <RowModal id1="calentador_solar" clsRow1="col-md-7"
                label1="Calentador Solar con calentador de paso">
                <input type="text" id="calentador_solar" class="form-control"
                    v-model="catalogo.calentador_solar"
                    :disabled="tipoAccion==3"
                    v-on:keypress="$root.isNumber($event)"
                >
            </RowModal>

            <RowModal id1="espejos" clsRow1="col-md-7"
                label1="Espejos">
                <input type="text" id="espejos" class="form-control"
                    v-model="catalogo.espejos"
                    :disabled="tipoAccion==3"
                    v-on:keypress="$root.isNumber($event)"
                >
            </RowModal>

            <RowModal id1="cocina" clsRow1="col-md-7"
                label1="Cocina como casa muestra">
                <input type="text" id="cocina" class="form-control"
                    v-model="catalogo.cocina"
                    :disabled="tipoAccion==3"
                    v-on:keypress="$root.isNumber($event)"
                >
            </RowModal>

            <RowModal id1="tanque_estacionario" clsRow1="col-md-7"
                label1="Tanque estacionario">
                <input type="text" id="tanque_estacionario" class="form-control"
                    v-model="catalogo.tanque_estacionario"
                    :disabled="tipoAccion==3"
                    v-on:keypress="$root.isNumber($event)"
                >
            </RowModal>



            <!-- Div para mostrar los errores que mande validerNotaria -->
            <div v-show="erroCatalogo" class="form-group row div-error">
                <div class="text-center text-error">
                    <div v-for="error in errorMostrarMsjCatalogo" :key="error" v-text="error"></div>
                </div>
            </div>
        </template>
        <template v-slot:buttons-footer>
            <ButtonComponent v-if="tipoAccion==1" @click="registrar()" icon="icon-check">Guardar</ButtonComponent>
            <ButtonComponent v-if="tipoAccion==2" @click="actualizar()" icon="icon-check">Actualizar</ButtonComponent>
        </template>
    </ModalComponent>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    import ModalComponent from '../../Componentes/ModalComponent.vue'
    import ButtonComponent from '../../Componentes/ButtonComponent.vue'
    import RowModal from '../../Componentes/ComponentesModal/RowModalComponent.vue'

    export default {
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
            catalogo:{
                type: Object,
                default:{
                    id:'',
                    modelo_id: '',
                    cocina_tradicional: 0,
                    vestidor: 0,
                    closets: 0,
                    canceles: 0,
                    persianas: 0,
                    calentador_paso: 0,
                    calentador_solar: 0,
                    espejos: 0,
                    tanque_estacionario: 0,
                    cocina: 0,
                    modelo: ''
                },
                require:false
            }
        },
        components:{
            ModalComponent,
            ButtonComponent,
            RowModal
        },
        emits: ['close'],
        data(){
            return{
                proyecto : '',
                erroCatalogo : 0,
                errorMostrarMsjCatalogo : [],
            }
        },
        computed:{

        },
        methods : {
            /**Metodo para registrar  */
            registrar(){
                if(this.validarRegistro()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/cat-equipamiento',{
                    'modelo_id' : this.catalogo.modelo_id,
                    'cocina_tradicional' : this.catalogo.cocina_tradicional,
                    'vestidor' : this.catalogo.vestidor,
                    'closets' : this.catalogo.closets,
                    'canceles' : this.catalogo.canceles,
                    'persianas' : this.catalogo.persianas,
                    'calentador_paso' : this.catalogo.calentador_paso,
                    'calentador_solar' : this.catalogo.calentador_solar,
                    'espejos' : this.catalogo.espejos,
                    'tanque_estacionario' : this.catalogo.tanque_estacionario,
                    'cocina' : this.catalogo.cocina,
                }).then(function (response){
                    //Se muestra mensaje Success
                    me.$emit('close')
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Catálogo agregada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            actualizar(){

                if(this.validarRegistro()) //Se verifica si hay un error (campo vacio)
                    return;
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.put(`/cat-equipamiento/${me.catalogo.id}`,{
                    'id' : this.catalogo.id,
                    'modelo_id' : this.catalogo.modelo_id,
                    'cocina_tradicional' : this.catalogo.cocina_tradicional,
                    'vestidor' : this.catalogo.vestidor,
                    'closets' : this.catalogo.closets,
                    'canceles' : this.catalogo.canceles,
                    'persianas' : this.catalogo.persianas,
                    'calentador_paso' : this.catalogo.calentador_paso,
                    'calentador_solar' : this.catalogo.calentador_solar,
                    'espejos' : this.catalogo.espejos,
                    'tanque_estacionario' : this.catalogo.tanque_estacionario,
                    'cocina' : this.catalogo.cocina,
                }).then(function (response){
                    me.$emit('close')
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Catálogo actualizado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            validarRegistro(){
                this.erroCatalogo=0;
                this.errorMostrarMsjCatalogo=[];

                if(this.catalogo.modelo_id=='') //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjCatalogo.push("Elija un modelo");

                if(this.catalogo.cocina_tradicional==='' ||
                    this.catalogo.vestidor ==='' ||
                    this.catalogo.closets ==='' ||
                    this.catalogo.canceles ==='' ||
                    this.catalogo.persianas ==='' ||
                    this.catalogo.calentador_solar ==='' ||
                    this.catalogo.espejos ==='' ||
                    this.catalogo.tanque_estacionario ==='' ||
                    this.catalogo.cocina ===''
                ) //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjCatalogo.push("No dejar campos vacios");

                if(this.errorMostrarMsjCatalogo.length)//Si el mensaje tiene almacenado algo en el array
                    this.erroCatalogo = 1;

                return this.erroCatalogo;
            },
        },
        mounted() {
            this.$root.selectFraccionamientos();
            if(this.catalogo.proyecto_id){
                this.proyecto = this.catalogo.proyecto_id
                this.$root.selectModelo(this.catalogo.proyecto_id)
            }
        }
    }
</script>
<style>
    .div-error{
        display:flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>
