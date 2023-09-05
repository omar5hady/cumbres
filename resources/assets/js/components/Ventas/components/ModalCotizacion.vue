<template>
    <ModalComponent
        :titulo="titulo"
        @closeModal="$emit('close')"
    >
        <template v-slot:body>

            <h5 class="text-center" style="margin-bottom: 2rem;"> Equipamiento Incluido</h5>

            <div class="row">
                <!-- Cocina Tradicional -->
                <div class="col-sm">
                    <div class="card text-center mb-2" style="width: 12rem;">
                        <div class="card-body">
                            <h5 class="card-title">Cocina Tradicional</h5>
                            <p class="card-text">{{ $root.formatNumber(catalogo.cocina_tradicional) }}</p>
                            <ButtonComponent :btnClass="active_cocina_tradicional ? 'btn-success' : 'btn-danger'"
                                :icon="active_cocina_tradicional ? 'icon-check' : 'icon-close'"
                                @click="changeCocinaTracional()"
                            />
                        </div>
                    </div>
                </div>

                <!-- Vestidor -->
                <div class="col-sm">
                    <div class="card text-center mb-2" style="width: 12rem;">
                        <div class="card-body">
                            <h5 class="card-title">Vestidor</h5>
                            <p class="card-text">{{ $root.formatNumber(catalogo.vestidor) }}</p>
                            <ButtonComponent :btnClass="active_vestidor ? 'btn-success' : 'btn-danger'"
                                :icon="active_vestidor ? 'icon-check' : 'icon-close'"
                                @click="changeVestidor()"
                            />
                        </div>
                    </div>
                </div>

                <!-- Closets -->
                <div class="col-sm">
                    <div class="card text-center mb-2" style="width: 12rem;">
                        <div class="card-body">
                            <h5 class="card-title">Closets</h5>
                            <p class="card-text">{{ $root.formatNumber(catalogo.closets) }}</p>
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
                <div class="col-sm">
                    <div class="card text-center mb-2" style="width: 12rem;">
                        <div class="card-body">
                            <h5 class="card-title">Canceles</h5>
                            <p class="card-text">{{ $root.formatNumber(catalogo.canceles) }}</p>
                            <ButtonComponent :btnClass="active_canceles ? 'btn-success' : 'btn-danger'"
                                :icon="active_canceles ? 'icon-check' : 'icon-close'"
                                @click="changeCanceles()"
                            />
                        </div>
                    </div>
                </div>

                <div class="col-sm">
                    <div class="card text-center mb-2" style="width: 12rem;">
                        <div class="card-body">
                            <h5 class="card-title">Persianas</h5>
                            <p class="card-text">{{ $root.formatNumber(catalogo.persianas) }}</p>
                            <ButtonComponent :btnClass="active_persianas ? 'btn-success' : 'btn-danger'"
                                :icon="active_persianas ? 'icon-check' : 'icon-close'"
                                @click="changePersianas()"
                            />
                        </div>
                    </div>
                </div>

                <div class="col-sm">
                    <div class="card text-center mb-2" style="width: 12rem;">
                        <div class="card-body">
                            <h5 class="card-title">Calentador de Paso</h5>
                            <p class="card-text">{{ $root.formatNumber(catalogo.calentador_paso) }}</p>
                            <ButtonComponent :btnClass="active_calentador_paso ? 'btn-success' : 'btn-danger'"
                                :icon="active_calentador_paso ? 'icon-check' : 'icon-close'"
                                @click="changeCalentadorPaso()"
                            />
                        </div>
                    </div>
                </div>

                <div class="col-sm">
                    <div class="card text-center mb-2" style="width: 12rem;">
                        <div class="card-body">
                            <h5 class="card-title">Calentador solar</h5>
                            <p class="card-text">{{ $root.formatNumber(catalogo.calentador_solar) }}</p>
                            <ButtonComponent :btnClass="active_calentador_solar ? 'btn-success' : 'btn-danger'"
                                :icon="active_calentador_solar ? 'icon-check' : 'icon-close'"
                                @click="changeCalentadorSolar()"
                            />
                        </div>
                    </div>
                </div>

                <div class="col-sm">
                    <div class="card text-center mb-2" style="width: 12rem;">
                        <div class="card-body">
                            <h5 class="card-title">Espejos</h5>
                            <p class="card-text">{{ $root.formatNumber(catalogo.espejos) }}</p>
                            <ButtonComponent :btnClass="active_espejos ? 'btn-success' : 'btn-danger'"
                                :icon="active_espejos ? 'icon-check' : 'icon-close'"
                                @click="changeEspejos()"
                            />
                        </div>
                    </div>
                </div>

                <div class="col-sm">
                    <div class="card text-center mb-2" style="width: 12rem;">
                        <div class="card-body">
                            <h5 class="card-title">Tanque Estacionario</h5>
                            <p class="card-text">{{ $root.formatNumber(catalogo.tanque_estacionario) }}</p>
                            <ButtonComponent :btnClass="active_tanque_estacionario ? 'btn-success' : 'btn-danger'"
                                :icon="active_tanque_estacionario ? 'icon-check' : 'icon-close'"
                                @click="changeTanqueEstacionario()"
                            />
                        </div>
                    </div>
                </div>

                <div class="col-sm">
                    <div class="card text-center mb-2" style="width: 12rem;">
                        <div class="card-body">
                            <h5 class="card-title">Cocina como casa muestra</h5>
                            <p class="card-text">{{ $root.formatNumber(catalogo.cocina) }}</p>
                            <ButtonComponent :btnClass="active_cocina ? 'btn-success' : 'btn-danger'"
                                :icon="active_cocina ? 'icon-check' : 'icon-close'"
                                @click="changeCocina()"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Div para mostrar los errores que mande validerNotaria -->
        </template>
        <template v-slot:buttons-footer>
            <h5 class="text-center" style="margin-left: 18rem;">
                TOTAL: {{ $root.formatNumber( total = totalPrecio) }}
            </h5>
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
                total: 0
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

        },
        mounted() {
        }
    }
</script>
<style>

</style>
