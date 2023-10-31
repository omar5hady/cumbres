<template>
    <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card scroll-box">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Resumen de Pago
                    </div>
                    <LoadingComponent v-if="loading"></LoadingComponent>
                    <template v-else>
                        <!-- Div Card Body para listar -->
                            <div class="card-body">
                                <div class="form-group row" v-if="rolId != 13">
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <label for="b_contratista" class="form-control col-md-4">Contratista: </label>
                                            <select class="form-control" v-model="b_contratista">
                                                <option value=''> Seleccione </option>
                                                <option v-for="contratista in arrayContratistas" :key="contratista.id" :value="contratista.id" v-text="contratista.nombre"></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input type="date" v-model="b_fecha" @keyup.enter="getResumen()" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <button type="submit" @click="getResumen()" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                            <a class="btn btn-scarlet" :href="'/estimaciones/getResumenPago?fecha_pago='+b_fecha+'&contratista='+b_contratista+'&print=1'"
                                                target="_blank"
                                            >
                                                <i class="fa fa-file-pdf-o"></i>&nbsp;
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12" style="margin-top: 15px; margin-bottom: 30px;">
                                    <center>
                                        <h4 style="color:#1c2b4c">{{ contratista  }}</h4>
                                        <h5 style="color:#1c2b4c">Total: ${{ $root.formatNumber(total=getTotal)  }}</h5>
                                    </center>
                                </div>

                                <TableResumen
                                    :usuario="usuario"
                                    :rolId="rolId"
                                    :arrayResumen="arrayResumen"
                                    :iva="iva"
                                ></TableResumen>

                            </div>

                        <!-- Div Card Body para nuevo registro -->
                        <!-- <AvisoObraFormComponent v-if="listado == 0"
                            :data="avisoObra"
                            @close="closeForm()"
                            :tipoAccion="tipoForm"
                        ></AvisoObraFormComponent> -->


                    </template>

                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
        </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    import vSelect from 'vue-select';
    import Button from '../Componentes/ButtonComponent.vue';
    import LoadingComponent from '../Componentes/LoadingComponent.vue';
    import TableResumen from './components/resumenPago/TableResumen.vue'

    export default {
        props:{
            rolId:{type: String},
            usuario:{type: String}
        },
        data(){
            return{
                id: '',
                arrayResumen : [],
                arrayContratistas: [],

                b_fecha : '',
                contratista: '',
                b_contratista: '',

                loading:false,
                iva: 0,
            }
        },
        components:{
            vSelect,
            LoadingComponent,
            Button,
            TableResumen
        },
        computed:{
            getTotal: function(){
                let total = 0
                this.arrayResumen.forEach(e => {
                    total += (e.total + e.monto_iva)
                });

                return total
            },
        },

        methods : {
            /**Metodo para mostrar los registros */
            getResumen(){
                let me = this;
                me.loading = true;
                var url = '/estimaciones/getResumenPago?fecha_pago='+me.b_fecha+'&contratista='+me.b_contratista;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayResumen = respuesta.resumen
                    me.contratista = respuesta.contratista
                    me.iva = respuesta.iva
                    me.loading = false;
                })
                .catch(function (error) {
                    console.log(error);
                    me.loading = false;
                });
            },
            selectContratistas(){
                let me = this;
                me.arrayContratistas = [];
                var url = '/select_contratistas';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayContratistas = respuesta.contratista;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        },
        mounted() {
            this.selectContratistas();
            // this.getResumen()

        }
    }
</script>
<style>

</style>
