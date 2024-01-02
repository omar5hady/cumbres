<template>
    <ModalComponent
        :titulo="titulo"
        @closeModal="$emit('close')"
    >
        <template v-slot:body>
            <template v-if="vista==0">
                <h5 class="text-center" style="margin-bottom: 2rem;"> Equipamiento Incluido</h5>

                <div class="row">
                    <!-- Cocina Tradicional -->
                    <div class="col-sm" v-if="catalogo.cocina_tradicional > 0">
                        <div class="card text-center mb-2" style="width: 12rem;">
                            <div class="card-body">
                                <h5 class="card-title">Cocina Tradicional</h5>
                                <p class="card-text">${{ $root.formatNumber(catalogo.cocina_tradicional) }}</p>
                                <ButtonComponent :btnClass="active_cocina_tradicional ? 'btn-success' : 'btn-danger'"
                                    :icon="active_cocina_tradicional ? 'icon-check' : 'icon-close'"
                                    @click="changeCocinaTracional()"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Vestidor -->
                    <div class="col-sm" v-if="catalogo.vestidor > 0">
                        <div class="card text-center mb-2" style="width: 12rem;">
                            <div class="card-body">
                                <h5 class="card-title">Vestidor</h5>
                                <p class="card-text">${{ $root.formatNumber(catalogo.vestidor) }}</p>
                                <ButtonComponent :btnClass="active_vestidor ? 'btn-success' : 'btn-danger'"
                                    :icon="active_vestidor ? 'icon-check' : 'icon-close'"
                                    @click="changeVestidor()"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Closets -->
                    <div class="col-sm" v-if="catalogo.closets > 0">
                        <div class="card text-center mb-2" style="width: 12rem;">
                            <div class="card-body">
                                <h5 class="card-title">Closets</h5>
                                <p class="card-text">${{ $root.formatNumber(catalogo.closets) }}</p>
                                <ButtonComponent :btnClass="active_closets ? 'btn-success' : 'btn-danger'"
                                    :icon="active_closets ? 'icon-check' : 'icon-close'"
                                    @click="changeClosets()"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <h5 class="text-center" style="margin-bottom: 2rem;"> Otros paquetes</h5>

                <div class="row">
                    <div class="col-sm" v-if="catalogo.canceles > 0">
                        <div class="card text-center mb-2" style="width: 12rem;">
                            <div class="card-body">
                                <h5 class="card-title">Canceles</h5>
                                <p class="card-text">${{ $root.formatNumber(catalogo.canceles) }}</p>
                                <ButtonComponent :btnClass="active_canceles ? 'btn-success' : 'btn-danger'"
                                    :icon="active_canceles ? 'icon-check' : 'icon-close'"
                                    @click="changeCanceles()"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="col-sm" v-if="catalogo.persianas > 0">
                        <div class="card text-center mb-2" style="width: 12rem;">
                            <div class="card-body">
                                <h5 class="card-title">Persianas</h5>
                                <p class="card-text">${{ $root.formatNumber(catalogo.persianas) }}</p>
                                <ButtonComponent :btnClass="active_persianas ? 'btn-success' : 'btn-danger'"
                                    :icon="active_persianas ? 'icon-check' : 'icon-close'"
                                    @click="changePersianas()"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- <div class="col-sm" v-if="catalogo.calentador_paso > 0">
                        <div class="card text-center mb-2" style="width: 12rem;">
                            <div class="card-body">
                                <h5 class="card-title">Calentador de Paso</h5>
                                <p class="card-text">${{ $root.formatNumber(catalogo.calentador_paso) }}</p>
                                <ButtonComponent :btnClass="active_calentador_paso ? 'btn-success' : 'btn-danger'"
                                    :icon="active_calentador_paso ? 'icon-check' : 'icon-close'"
                                    @click="changeCalentadorPaso()"
                                />
                            </div>
                        </div>
                    </div> -->

                    <div class="col-sm" v-if="catalogo.calentador_solar > 0">
                        <div class="card text-center mb-2" style="width: 12rem;">
                            <div class="card-body">
                                <h5 class="card-title">Calentador solar con calentador de paso</h5>
                                <p class="card-text">${{ $root.formatNumber(catalogo.calentador_solar) }}</p>
                                <ButtonComponent :btnClass="active_calentador_solar ? 'btn-success' : 'btn-danger'"
                                    :icon="active_calentador_solar ? 'icon-check' : 'icon-close'"
                                    @click="changeCalentadorSolar()"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="col-sm" v-if="catalogo.espejos > 0">
                        <div class="card text-center mb-2" style="width: 12rem;">
                            <div class="card-body">
                                <h5 class="card-title">Espejos</h5>
                                <p class="card-text">${{ $root.formatNumber(catalogo.espejos) }}</p>
                                <ButtonComponent :btnClass="active_espejos ? 'btn-success' : 'btn-danger'"
                                    :icon="active_espejos ? 'icon-check' : 'icon-close'"
                                    @click="changeEspejos()"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="col-sm" v-if="catalogo.tanque_estacionario > 0">
                        <div class="card text-center mb-2" style="width: 12rem;">
                            <div class="card-body">
                                <h5 class="card-title">Tanque Estacionario</h5>
                                <p class="card-text">${{ $root.formatNumber(catalogo.tanque_estacionario) }}</p>
                                <ButtonComponent :btnClass="active_tanque_estacionario ? 'btn-success' : 'btn-danger'"
                                    :icon="active_tanque_estacionario ? 'icon-check' : 'icon-close'"
                                    @click="changeTanqueEstacionario()"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="col-sm" v-if="catalogo.cocina > 0">
                        <div class="card text-center mb-2" style="width: 12rem;">
                            <div class="card-body">
                                <h5 class="card-title">Cocina como casa muestra</h5>
                                <p class="card-text">${{ $root.formatNumber(catalogo.cocina) }}</p>
                                <ButtonComponent :btnClass="active_cocina ? 'btn-success' : 'btn-danger'"
                                    :icon="active_cocina ? 'icon-check' : 'icon-close'"
                                    @click="changeCocina()"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <h5 class="text-center" style="color: darkblue;">
                    TOTAL: ${{ $root.formatNumber( total = totalPrecio) }}
                </h5>

                <hr>
                <h6 class="text-center">La presente cotización tiene vigencia al {{ this.moment().locale('es').format('DD/MMM/YYYY') }}</h6>
            </template>
            <template v-else>
                <RowModal label1="Cliente:" clsRow1="col-md-5" id1="rfc" maxlength="10">
                    <input type="text" v-model="cliente.rfc" class="form-control" id="rfc"
                    placeholder="Escribe el RFC"
                    @keyup="searchCliente(cliente.rfc)">
                </RowModal>
                <RowModal label1="Nombre:" clsRow1="col-md-4" id1="rfc" clsRow2="col-md-4" label2="Apellidos" id2="apellidos">
                    <input type="text" id="nombre" v-model="cliente.nombre" class="form-control">
                    <template v-slot:input2>
                        <input type="text" v-model="cliente.apellidos" class="form-control" id="apellidos">
                    </template>
                </RowModal>
                <RowModal label1="Fecha de Nacimiento:" clsRow1="col-md-4" id1="f_nacimiento"
                    clsRow2="col-md-4" label2="Sexo" id2="sexo">
                    <input type="date" id="f_nacimiento" v-model="cliente.f_nacimiento" class="form-control">
                    <template v-slot:input2>
                        <select id="sexo" class="form-control" v-model="cliente.sexo">
                            <option value="">Seleccione</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                        </select>
                    </template>
                </RowModal>
                <RowModal label1="Celular:" clsRow1="col-md-4" id1="celular"
                    clsRow2="col-md-4" label2="Email" id2="email">
                    <input type="text" id="celular" v-model="cliente.celular" v-on:keypress="$root.isNumber($event)" class="form-control">
                    <template v-slot:input2>
                        <input type="email" id="email" v-model="cliente.email" class="form-control">
                    </template>
                </RowModal>
                <RowModal label1="Edo. Civil" clsRow1="col-md-4" id1="edo_civil">
                    <select id="sexo" class="form-control" v-model="cliente.edo_civil">
                        <option value="">Seleccione</option>
                        <option value="1">Casado - separacion de bienes</option>
                        <option value="2">Casado - sociedad conyugal</option>
                        <option value="3">Divorciado</option>
                        <option value="4">Soltero</option>
                        <option value="5">Union libre</option>
                        <option value="6">Viudo</option>
                        <option value="7">Otro</option>
                    </select>
                </RowModal>

                <RowModal label1="Medio de publicidad" clsRow1="col-md-4" id1="publicidad_id">
                    <select id="publicidad_id" class="form-control" v-model="cliente.publicidad_id">
                        <option value="">Seleccione</option>
                        <option v-for="medios in arrayMediosPublicidad" :key="medios.id" :value="medios.id" v-text="medios.nombre"></option>
                    </select>
                </RowModal>

                <RowModal label1="Comentarios" clsRow1="col-md-8" id1="comentarios">
                    <textarea class="form-control" cols="30" rows="3" v-model="cliente.observacion"></textarea>
                </RowModal>

                <div v-show="errorCot" class="form-group row div-error">
                    <div class="text-center text-error">
                        <div
                            v-for="error in errorMostrarMsj"
                            :key="error"
                            v-text="error"
                        ></div>
                    </div>
                </div>
            </template>
        </template>
        <template v-slot:buttons-footer>
            <a v-if="doc_equipamiento && vista == 0" title="Descargar catalogo"
                :href="`/downloadCatEquipamiento/${doc_equipamiento}`"
                    target="_blank"
                    class="btn btn-scarlet">
                    <i class="fa fa-file-pdf-o"></i>
            </a>
            <ButtonComponent v-if="vista==0" icon="icon-check" btnClass="btn-success" @click="openSave()">Guardar cotización</ButtonComponent>
            <ButtonComponent v-if="vista==1" icon="icon-check" btnClass="btn-success" @click="printCotizacion()">Imprimir cotización</ButtonComponent>
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
            doc_equipamiento:{
                type: String,
                default: '',
                required: true
            },
            catalogo:{
                type: Object,
                default:{
                    precio_venta: 0,
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
                    lote_id: 0,
                    fraccionamiento_id: 0
                },
                require:false
            }
        },
        components:{
            ModalComponent,
            ButtonComponent,
            RowModal
        },
        data(){
            return{
                active_cocina_tradicional: true,
                active_vestidor: true,
                active_closets: true,
                active_canceles: false,
                active_persianas: false,
                active_calentador_paso: false,
                active_calentador_solar: false,
                active_espejos: false,
                active_tanque_estacionario: false,
                active_cocina: false,
                total: 0,
                vista: 0,
                cliente:{
                    id: '',
                    nombre: '',
                    apellidos: '',
                    rfc: '',
                    celular: '',
                    f_nacimiento: '',
                    email: '',
                    sexo: '',
                    proyecto_interes_id: '',
                    publicidad_id: '',
                    edo_civil: '',
                    vendedor_id: '',
                    observacion: ''
                },
                cotizacion:{},
                errorCot: 0,
                errorMostrarMsj: [],
                proceso: false,
                arrayMediosPublicidad: []

            }
        },
        computed:{
            totalPrecio: function(){
                let total = this.catalogo.precio_venta;

                if(this.active_cocina_tradicional)
                    total+= this.catalogo.cocina_tradicional
                if(this.active_vestidor)
                    total+= this.catalogo.vestidor
                if(this.active_closets)
                    total+= this.catalogo.closets

                if(this.active_canceles)
                    total+= this.catalogo.canceles
                if(this.active_persianas)
                    total+= this.catalogo.persianas
                if(this.active_calentador_paso)
                    total+= this.catalogo.calentador_paso
                if(this.active_calentador_solar)
                    total+= this.catalogo.calentador_solar
                if(this.active_espejos)
                    total+= this.catalogo.espejos
                if(this.active_tanque_estacionario)
                    total+= this.catalogo.tanque_estacionario
                if(this.active_cocina)
                    total+= this.catalogo.cocina

                return total;
            },
        },
        methods : {
            openSave(){
                this.limpiarCliente()
                this.cotizacion = {
                    ...this.catalogo,
                    cocina_tradicional: this.active_cocina_tradicional ? this.catalogo.cocina_tradicional : 0,
                    vestidor: this.active_vestidor ? this.catalogo.vestidor : 0,
                    closets: this.active_closets ? this.catalogo.closets : 0,
                    canceles: this.active_canceles ? this.catalogo.canceles : 0,
                    persianas: this.active_persianas ? this.catalogo.persianas : 0,
                    calentador_solar: this.active_calentador_solar ? this.catalogo.calentador_solar : 0,
                    calentador_paso: this.active_calentador_paso ? this.catalogo.calentador_paso : 0,
                    espejos: this.active_espejos ? this.catalogo.espejos : 0,
                    tanque_estacionario: this.active_tanque_estacionario ? this.catalogo.tanque_estacionario : 0,
                    cocina: this.active_cocina ? this.catalogo.cocina : 0,
                }
                this.vista = 1
            },
            limpiarCliente(){
                this.cliente = {
                    id: '',
                    nombre: '',
                    apellidos: '',
                    rfc: '',
                    celular: '',
                    f_nacimiento: '',
                    email: '',
                    sexo: '',
                    proyecto_interes_id: this.catalogo.fraccionamiento_id,
                    publicidad_id: '',
                    edo_civil: '',
                    vendedor_id: '',
                    observacion: ''
                }
            },
            changeCocinaTracional(){
                this.active_cocina_tradicional = !this.active_cocina_tradicional
            },
            changeVestidor(){
                this.active_vestidor = !this.active_vestidor
            },
            changeClosets(){
                this.active_closets = !this.active_closets
            },
            changeCanceles(){
                this.active_canceles = !this.active_canceles
            },
            changePersianas(){
                this.active_persianas = !this.active_persianas
            },
            changeCalentadorPaso(){
                this.active_calentador_paso = !this.active_calentador_paso
            },
            changeCalentadorSolar(){
                this.active_calentador_solar = !this.active_calentador_solar
            },
            changeEspejos(){
                this.active_espejos = !this.active_espejos
            },
            changeTanqueEstacionario(){
                this.active_tanque_estacionario = !this.active_tanque_estacionario
            },
            changeCocina(){
                this.active_cocina = !this.active_cocina
            },
            selectMedioPublicidad(){
                let me = this;
                me.arrayMediosPublicidad=[];
                var url = '/select_medio_publicidad';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayMediosPublicidad = respuesta.medios_publicitarios;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            searchCliente(rfc){
                if(rfc.length == 10){
                    var url = '/select_rfcs?rfc=' + rfc;
                    let me = this;
                    let encuentraRFC = 0;
                    axios.get(url).then(function (response) {
                        let respuesta = response.data;
                        encuentraRFC = respuesta.rfc1;

                        if(encuentraRFC==1) {
                            let res = respuesta.personaid[0];
                            me.cliente = {
                                ...res,
                                proyecto_interes_id: me.catalogo.fraccionamiento_id,
                                observacion: ''
                            }
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }

            },

            printCotizacion(){
                if (this.validarCotizacion() || this.proceso == true) {
                    //Se verifica si hay un error (campo vacio)
                    return;
                }
                this.proceso = true;

                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios
                    .post("/cat-equipamiento/createCotizacion", {
                        cliente: this.cliente,
                        equipamiento: {
                            ...this.cotizacion,
                        }
                    })
                    .then(function(response) {
                        me.proceso = false;
                        //Se muestra mensaje Success
                        swal({
                            position: "top-end",
                            type: "success",
                            title: "Cotizacion guardada correctamente",
                            showConfirmButton: false,
                            timer: 1500
                        });
                        //llamada a PDF
                        window.open('/cotizacion/printCotizacion?id='+response.data, '_blank');
                    })
                    .catch(function(error) {
                        this.proceso = false
                        console.log(error);
                    });
            },
            validarCotizacion() {
                this.errorCot = 0;
                this.errorMostrarMsj = [];

                if (!this.cliente.nombre || !this.cliente.apellidos)
                    //Si la variable departamento esta vacia
                    this.errorMostrarMsj.push(
                        "Ingresar nombre del cliente"
                    );

                if (!this.cliente.rfc)
                    //Si la variable departamento esta vacia
                    this.errorMostrarMsj.push(
                        "Ingresar RFC del cliente"
                    );

                if (!this.cliente.celular)
                    //Si la variable departamento esta vacia
                    this.errorMostrarMsj.push(
                        "Ingresar celular del cliente"
                    );

                if (!this.cliente.sexo)
                    //Si la variable departamento esta vacia
                    this.errorMostrarMsj.push(
                        "Ingresar Sexo del cliente"
                    );

                if (!this.cliente.email)
                    //Si la variable departamento esta vacia
                    this.errorMostrarMsj.push(
                        "Ingresar Email del cliente"
                    );

                if (!this.cliente.f_nacimiento)
                    //Si la variable departamento esta vacia
                    this.errorMostrarMsj.push(
                        "Ingresar Fecha de nacimiento del cliente"
                    );

                if (!this.cliente.edo_civil)
                    //Si la variable departamento esta vacia
                    this.errorMostrarMsj.push(
                        "Seleccionar estado civil del cliente"
                    );



                if (this.errorMostrarMsj.length)
                    //Si el mensaje tiene almacenado algo en el array
                    this.errorCot = 1;

                return this.errorCot;
            },

        },
        mounted() {
            this.selectMedioPublicidad()
        }
    }
</script>
<style>

</style>
