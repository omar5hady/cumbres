<template>
    <ModalComponent
        :titulo="titulo"
        @closeModal="$emit('close')"
    >
        <template v-slot:body>
            <div class="form-group row">
                <label class="col-md-3 form-control-label" for="vehiculo">
                    Vehiculo <span style="color:red;" v-show="vehiculo.vehiculo==''">*</span>
                </label>
                <div class="col-md-6">
                    <input type="text" id="vehiculo" v-model="vehiculo.vehiculo" class="form-control" placeholder="Nombre de vehiculo">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 form-control-label" for="marca">
                    Marca <span style="color:red;" v-show="vehiculo.marca==''">*</span>
                </label>
                <div class="col-md-6">
                    <select class="form-control" v-model="vehiculo.marca" id="marca">
                        <option value="">Seleccione</option>
                        <option v-for="marcas in marcasAutos" :key="marcas" :value="marcas" v-text="marcas"></option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 form-control-label" for="modelo" >
                    Modelo <span style="color:red;" v-show="vehiculo.modelo==''">*</span>
                </label>
                <div class="col-md-3">
                    <input id="modelo" type="text" v-model="vehiculo.modelo" maxlength="4" v-on:keypress="$root.isNumber($event)" class="form-control" placeholder="Modelo">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 form-control-label" for="clave">Clave</label>
                <div class="col-md-6">
                    <input id="clave" type="text" v-model="vehiculo.clave" maxlength="10" class="form-control" placeholder="Clave">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 form-control-label" for="numero_serie">Número de Serie</label>
                <div class="col-md-6">
                    <input id="numero_serie" type="text" v-model="vehiculo.numero_serie" maxlength="20" class="form-control" placeholder="Número de serie">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 form-control-label" for="numero_motor">Número de Motor</label>
                <div class="col-md-6">
                    <input id="numero_motor" type="text" v-model="vehiculo.numero_motor" maxlength="11" class="form-control" placeholder="Número de motor">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 form-control-label" for="placas">
                    Número de Placa <span style="color:red;" v-show="vehiculo.placas==''">*</span>
                </label>
                <div class="col-md-5">
                    <input id="placas" type="text" v-model="vehiculo.placas" maxlength="7" class="form-control" placeholder="Número de placa">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 form-control-label" for="usersName">
                    Responsable <span style="color:red;" v-show="vehiculo.responsable_id==''">*</span>
                </label>
                <div class="col-md-6">
                    <input v-if="vista==2" disabled type="text" v-model="vehiculo.nombre" class="form-control col-md-8">
                    <button v-if="vista == 2" class="form-control btn btn-sm btn-secondary col-md-4" @click="vista = 1, vehiculo.responsable_id = ''">Cambiar</button>
                    <input v-if="vista==1" type="text" name="user" list="usersName" @keyup="selectUsuario(vehiculo.responsable_id)" @change="getNombre(vehiculo.responsable_id)"  class="form-control col-md-8" v-model="vehiculo.responsable_id">
                    <datalist v-if="vista==1" id="usersName">
                        <option value="">Seleccione</option>
                        <option v-for="users in arrayUsers" :key="users.id" :value="users.id" v-text="users.nombre + ' '+ users.apellidos"></option>
                    </datalist>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 form-control-label" for="comodato">Comodato?</label>
                <div class="col-md-3">
                    <select id="comodato" class="form-control" v-model="vehiculo.comodato" >
                        <option value="0">No</option>
                        <option value="1">Si</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 form-control-label" for="empresa">Empresa</label>
                <div class="col-md-9">
                    <select id="empresa" class="form-control" v-model="vehiculo.empresa" >
                        <option value="">Seleccione</option>
                        <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                    </select>
                </div>
            </div>

            <!-- Div para mostrar los errores que mande validerNotaria -->
            <div v-show="errorVehiculo" class="form-group row div-error">
                <div class="text-center text-error">
                    <div v-for="error in errorMostrarMsjVehiculo" :key="error" v-text="error"></div>
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
            vehiculo:{
                type: Object,
                default:{
                    id:'',
                    vehiculo : '',
                    responsable_id : '',
                    modelo : '',
                    marca: '',
                    clave: '',
                    placas: '',
                    comodato:0,
                    numero_serie : '',
                    numero_motor : '',
                    empresa : 'Grupo Constructor Cumbres',
                    nombre:'',
                },
                require:false
            }
        },
        components:{
            ModalComponent,
            ButtonComponent,
        },
        data(){
            return{
                empresa : 'Grupo Constructor Cumbres',
                arrayUsers : [],
                empresas:[
                    'Grupo Constructor Cumbres',
                    'Ser Cumbres',
                    'Concretania',
                    'Magnacasa',
                    'Ing David'
                ],
                marcasAutos:[],
                errorVehiculo : 0,
                errorMostrarMsjVehiculo : [],
                vista: 1,
            }
        },
        computed:{

        },
        methods : {
            selectUsuario(buscar){
                let me = this;

                me.arrayUsers=[];
                var url = '/usuarios/selectUser?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayUsers = respuesta.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getNombre(id){
                let me = this;
                var url = '/usuarios/getNombre?id=' + id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.nombre = respuesta.nombre + ' ' +respuesta.apellidos;
                    me.vista = 2
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getMarcas(){
                let me = this;
                me.marcas=[];
                var url = '/vehiculos/getMarcas';
                axios.get(url).then(function (response) {
                    var respuesta = response;
                    me.marcasAutos = respuesta.data.marcas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            /**Metodo para registrar  */
            registrar(){
                if(this.validarRegistro()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/vehiculos/store',{
                    'vehiculo' : this.vehiculo.vehiculo,
                    'modelo' : this.vehiculo.modelo,
                    'marca' : this.vehiculo.marca,
                    'clave' : this.vehiculo.clave,
                    'placas' : this.vehiculo.placas,
                    'numero_serie' : this.vehiculo.numero_serie,
                    'numero_motor' : this.vehiculo.numero_motor,
                    'responsable_id' : this.vehiculo.responsable_id,
                    'empresa' : this.vehiculo.empresa,
                    'comodato' : this.vehiculo.comodato
                }).then(function (response){
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Vehiculo agregada correctamente',
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
                axios.put('/vehiculos/update',{
                    'id' : this.vehiculo.id,
                    'vehiculo' : this.vehiculo.vehiculo,
                    'modelo' : this.vehiculo.modelo,
                    'marca' : this.vehiculo.marca,
                    'clave' : this.vehiculo.clave,
                    'placas' : this.vehiculo.placas,
                    'numero_serie' : this.vehiculo.numero_serie,
                    'numero_motor' : this.vehiculo.numero_motor,
                    'responsable_id' : this.vehiculo.responsable_id,
                    'empresa' : this.vehiculo.empresa,
                    'comodato' : this.vehiculo.comodato
                }).then(function (response){
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Vehiculo actualizado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            validarRegistro(){
                this.errorVehiculo=0;
                this.errorMostrarMsjVehiculo=[];

                if(!this.vehiculo.vehiculo) //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjVehiculo.push("Nombre de vehiculo");

                if(!this.vehiculo.responsable_id) //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjVehiculo.push("Responsable.");

                if(this.errorMostrarMsjVehiculo.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorVehiculo = 1;

                return this.errorVehiculo;
            },
        },
        mounted() {
            this.getMarcas()
            if(this.tipoAccion == 2){
                this.vista = 2
                this.getNombre(this.vehiculo.responsable_id)
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
