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
                        <i class="fa fa-align-justify"></i> Reporte por empresa
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <select class="form-control col-md-8" @keyup.enter="getDatos()" v-model="b_proyecto">
                                        <option value="">Fraccionamiento</option>
                                        <option v-for="proyecto in $root.$data.proyectos" :key="proyecto.id"  :value="proyecto.id" v-text="proyecto.nombre"></option>
                                    </select>
                                </div>
                                <div class="input-group">
                                    <input type="text" disabled @keyup.enter="getDatos()" class="form-control col-md-4" placeholder="Empresa">
                                    <input type="text"  v-model="b_empresa" @keyup.enter="getDatos()" class="form-control col-md-6" placeholder="Empresa">
                                </div>
                                <div class="input-group">
                                    <input type="text" disabled @keyup.enter="getDatos()" class="form-control col-md-4" placeholder="Fecha de venta">
                                    <input type="date"  v-model="b_fecha1" @keyup.enter="getDatos()" class="form-control col-md-4" placeholder="Limite minimo">
                                    <input type="date"  v-model="b_fecha2" @keyup.enter="getDatos()" class="form-control col-md-4" placeholder="Limite minimo">
                                </div>
                                <div class="input-group">
                                    <input type="text" disabled @keyup.enter="getDatos()" class="form-control col-md-5" placeholder="Limite minimo">
                                    <input type="number"  v-model="b_limite" @keyup.enter="getDatos()" class="form-control col-md-4" placeholder="Limite minimo">
                                </div>
                                <div class="input-group">
                                    <button type="submit" @click="getDatos()" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    <a class="btn btn-success" 
                                        v-bind:href="'/reprotes/reporteEmpresasExcel?proyecto='+ b_proyecto+
                                            '&b_limite=' + b_limite +
                                            '&b_empresa=' + b_empresa +
                                            '&b_fecha1=' + b_fecha1 +
                                            '&b_fecha2=' + b_fecha2">
                                        <i class="fa fa-file-text"></i>&nbsp; Excel
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="table-responsive"> 
                            <table class="table2 tableScroll table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th colspan="2">Empresa</th>
                                        <th v-for="proyecto in arrayProyectos" :key="proyecto.id" 
                                            class="text-center"
                                            :colspan="proyecto.numEtapas" v-text="proyecto.proyecto">
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan=""></th>
                                        <th colspan="">Total</th>
                                        <template v-for="proyecto in arrayProyectos">
                                            <th v-for="etapa in proyecto.etapas" :key="etapa.id" 
                                                class="text-center td2"
                                                v-text="etapa.num_etapa">
                                            </th>
                                        </template>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="empresa in arrayEmpresas" :key="empresa.id">
                                        <td class="td2" v-text="empresa.nombre"></td>
                                        <td v-text="empresa.total"></td>
                                        <template v-for="proyecto in arrayProyectos">
                                            <td v-for="etapa in proyecto.etapas" :key="etapa.id">{{empresa[etapa.num_etapa+'-'+proyecto.proyecto]}}</td>
                                        </template>
                                    </tr>                               
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            
        </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    export default {
        props:{
            userName:{type: String},
        },
        data(){
            return{
                arrayEmpresas : [],
                arrayProyectos : [],
                b_proyecto : '',
                b_fecha1: '',
                b_fecha2: '',
                b_limite: 10,
                b_empresa: 'BMW',
            }
        },
        computed:{
        },
        methods : {
            /**Metodo para mostrar los registros */
            getDatos(){
                let me = this;
                var url = '/reprotes/reporteEmpresas?proyecto='+ me.b_proyecto+
                    '&b_limite=' + me.b_limite +
                    '&b_empresa=' + me.b_empresa +
                    '&b_fecha1=' + me.b_fecha1 +
                    '&b_fecha2=' + me.b_fecha2
                ;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEmpresas = respuesta.empresas;
                    me.arrayProyectos = respuesta.proyecto;

                    me.arrayEmpresas.sort((b, a) => a.total - b.total);
                })
                .catch(function (error) {
                    console.log(error);
                });
            }
        },
        mounted() {
            this.getDatos(1);
            this.$root.selectFraccionamientos();
        }
    }
</script>
<style>
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: fixed !important;
        background-color: #3c29297a !important;
        overflow-y: auto;
    }
    .div-error{
        display:flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
    .table2 {
        margin: auto;
        border-collapse: collapse;
        overflow-x: auto;
        display: block;
        width: fit-content;
        max-width: 100%;
        box-shadow: 0 0 1px 1px rgba(0, 0, 0, .1);
    }

    .td2, .th2 {
        border: solid rgb(200, 200, 200) 1px;
        padding: .5rem;
    }

    .td2 {
        white-space: nowrap;
        border-bottom: none;
        color: rgb(20, 20, 20);
    }

    .td2:first-of-type, th:first-of-type {
       border-left: none;
    }

    .td2:last-of-type, th:last-of-type {
       border-right: none;
    } 
</style>
