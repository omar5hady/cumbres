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
                        <i class="fa fa-align-justify"></i> <strong>Reporte Medios Publicitarios</strong> 
                        <!--   Boton Nuevo    -->
                        &nbsp;&nbsp;
                        <button 
                        v-if="listado == 1"
                        type="button" @click="listado = 0" class="btn btn-secondary">
                            <i class="fa fa-mail-reply"></i>&nbsp;Regresar
                        </button>
                    </div>

                    <div class="card-body" v-if="listado == 0">
                        <div class="form-group row">
                            <div class="input-group">
                                <button title="Mostrar Filtros" type="button" v-if="filtros==0" @click="filtros=1" class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus-square"></i>
                                </button>
                                <button title="Mostrar Filtros" type="button" v-if="filtros==1" @click="filtros=0" class="btn btn-default btn-sm">
                                    <i class="fa fa-minus-square-o"></i>
                                </button>
                                &nbsp;&nbsp;
                                <button v-if="filtros==1" type="submit" @click="getDatos()" class="btn btn-primary"><i class="fa fa-search"></i></button>
                            </div>

                                <div class="col-md-6" v-if="filtros==1">
                                    <label >Proyecto</label>
                                    <div class="input-group">
                                        <!--Criterios para el listado de busqueda -->
                                        <select class="form-control"  @change="selectEtapas(proyecto_id)" v-model="proyecto_id" >
                                            <option value="">Fraccionamiento</option>
                                            <option v-for="proyecto in arrayFraccionamientos" :key="proyecto.id" :value="proyecto.id" v-text="proyecto.nombre"></option>
                                        </select>

                                        <select class="form-control" v-model="etapa_id" >
                                            <option value="">Etapa</option>
                                            <option v-for="etapa in arrayAllEtapas" :key="etapa.id" :value="etapa.id" v-text="etapa.num_etapa"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6" v-if="filtros==1">
                                    <label >Rango de fechas</label>
                                    <div class="input-group">
                                        <!--Criterios para el listado de busqueda -->
                                        <input type="date" v-model="desde" placeholder="Desde" class="form-control">
                                        <input type="date" v-model="hasta" placeholder="Hasta" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-6" v-if="filtros==1">
                                    <label >Asesor de venta</label>
                                    <div class="input-group">
                                        <select class="form-control" v-model="asesor_id" >
                                            <option value="">Seleccione</option>
                                            <option v-for="asesor in arrayAsesores" :key="asesor.id" :value="asesor.id" v-text="asesor.nombre + ' '+ asesor.apellidos"></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                    </div>

                    <div class="card-body" v-if="listado == 0">
                        <div class="form-group row">
                            <div class="col-md-3" >
                                <div class="text-value-sm text-primary"><h6 style="font-weight: bold;">Ventas (Total {{totalVentas}}) {{porcVentas.toFixed(2) + '%'}}</h6></div>
                                <div class="card text-dark bg-light" v-for="venta in arrayDatosVentas" :key="venta.id">
                                    <div class="card-body"
                                        @click="verCliente(venta.clientes,1)"
                                    >
                                        <div class="h6 text-muted text-left mb-2">{{venta.publicidad}}</div>
                                        <div class="text-muted text-uppercase font-weight-bold">{{venta.cant}} ({{((venta.cant/totalVentas)*100).toFixed(2) + '%'}})</div>
                                        <div class="progress progress-primary progress-xs my-2">
                                            <div class="progress-bar" role="progressbar" v-bind:style="{ width: (venta.cant/totalVentas)*100 + '%' }" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3" >
                                <div class="text-value-sm text-primary"><h6 style="font-weight: bold;">Prospectos Nuevos (Total {{totalProspectos}})</h6></div>
                                <div class="card text-light bg-dark" v-for="prospecto in arrayDatosProspectos" :key="prospecto.id">
                                    <div class="card-body text-light"
                                        @click="verCliente(prospecto.clientes,1)"
                                    >
                                        <div class="h6 text-muted2 text-left mb-2">{{prospecto.publicidad}}</div>
                                        <div class="text-muted text-uppercase font-weight-bold">{{prospecto.cant}}</div>
                                        <div class="progress progress-dark progress-xs my-2">
                                            <div class="progress-bar" role="progressbar" v-bind:style="{ width: (prospecto.cant/totalProspectos)*100 + '%' }" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-3" >
                                <div class="text-value-sm text-dark"><h6 style="font-weight: bold;">Prospectos Atendidos (Total {{totalTodos}})</h6></div>
                                <div class="card text-dark bg-dark" v-for="prospecto in arrayTodosPros" :key="prospecto.id">
                                    <div class="card-body text-dark"
                                        @click="verCliente(prospecto.clientes,2)"
                                    >
                                        <div class="h6 text-muted2 text-left mb-2">{{prospecto.publicidad}}</div>
                                        <div class="text-muted text-uppercase font-weight-bold">{{prospecto.cant}}</div>
                                        <div class="progress progress-dark progress-xs my-2">
                                            <div class="progress-bar" role="progressbar" v-bind:style="{ width: (prospecto.cant/totalTodos)*100 + '%' }" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3" >
                                <div class="text-value-sm text-dark"><h6 style="font-weight: bold;">Atendidos Descartados (Total {{totalDesc}})</h6></div>
                                <div class="card text-dark bg-dark" v-for="prospecto in arrayTodosDesc" :key="prospecto.id">
                                    <div class="card-body text-dark"
                                        @click="verCliente(prospecto.clientes,2)"
                                    >
                                        <div class="h6 text-muted2 text-left mb-2">{{prospecto.publicidad}}</div>
                                        <div class="text-muted text-uppercase font-weight-bold">{{prospecto.cant}}</div>
                                        <div class="progress progress-dark progress-xs my-2">
                                            <div class="progress-bar" role="progressbar" v-bind:style="{ width: (prospecto.cant/totalTodos)*100 + '%' }" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        
                    </div>

                    <div class="card-body"  v-if="listado == 1">
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Cliente</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-if="tipo == 2">
                                        <tr v-for="cliente,index in clientes2" :key="cliente.nombre+'-'+cliente.apellidos+index">
                                            <td v-text="parseInt(index+1)"></td>
                                            <td class="td2" v-text="cliente.nombre + ' ' + cliente.apellidos"></td>
                                        </tr>      
                                    </template>    
                                    <template v-if="tipo == 1">
                                        <tr v-for="cliente,index in clientes" :key="index">
                                            <td v-text="parseInt(index+1)"></td>
                                            <td class="td2" v-text="cliente"></td>
                                        </tr>      
                                    </template>                      
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
        data(){
            return{
               arrayDatosProspectos:[],
               arrayFraccionamientos:[],
               arrayDatosVentas:[],
               arrayTodosPros:[],
               arrayTodosDesc:[],
               arrayAllEtapas:[],
               arrayAsesores:[],
               totalVentas:0,
               porcVentas:0,
               totalProspectos:0,
               totalTodos:0,
               totalDesc:0,
               filtros:1,
               desde:'',
               hasta:'',
               etapa_id:'',
               asesor_id:'',
               proyecto_id:'',

               listado : 0,
               clientes: [],
               clientes2 : [],
               tipo:2,
            }
        },
        computed:{
            
        },
        methods : {
            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
            selectFraccionamientos(){
                let me = this;
                me.arrayFraccionamientos=[];
                var url = '/select_fraccionamiento';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayFraccionamientos = respuesta.fraccionamientos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            //Select todas las etapas
            selectEtapas(buscar){
                let me = this;
                me.etapa_id="";
                
                me.arrayAllEtapas=[];
                var url = '/select_etapa_proyecto?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayAllEtapas = respuesta.etapas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectAsesores(){
                let me = this;
                me.arrayAsesores=[];
                var url = '/select/asesores';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayAsesores = respuesta.personas;
                })
                .catch(function (error) {
                    console.log(error);
                });
              
            },
            verCliente(data, tipo){
                this.listado = 1;
                this.tipo = tipo;
                if(tipo == 1)
                    this.clientes = data;
                else
                    this.clientes2 = data;
            },
            getDatos(){
                let me = this;
                me.arrayDatosProspectos=[];
                me.arrayDatosVentas=[];
                me.arrayTodosPros=[];
                me.totalTodos=0;
                me.totalVentas=0;
                me.totalDesc=0;
                me.totalProspectos=0;
                var url = '/estadisticas/publicidad';
                axios.get(url,{params:{
                    'desde'     : this.desde,
                    'hasta'     : this.hasta,
                    'proyecto'  : this.proyecto_id,
                    'etapa'     : this.etapa_id,
                    'asesor'    : this.asesor_id,
                    }
                }).then(function (response) {
                    var respuesta = response.data;
                    me.arrayDatosProspectos = respuesta.publicidadProspectos;
                    me.arrayDatosVentas = respuesta.publicidadVentas;
                    me.arrayTodosPros = respuesta.publicidadAll;
                    me.arrayTodosDesc = respuesta.descartadosAll;

                    me.arrayDatosProspectos.forEach(element => {
                        me.totalProspectos += element.cant;
                    });

                    me.arrayDatosVentas.forEach(element => {
                        me.totalVentas += element.cant;
                    });

                    me.arrayTodosPros.forEach(element => {
                        me.totalTodos += element.cant;
                    });

                    me.arrayTodosDesc.forEach(element => {
                        me.totalDesc += element.cant;
                    });

                    me.porcVentas = (me.totalVentas/me.totalProspectos)*100;

                    me.arrayDatosProspectos.sort((b, a) => a.cant - b.cant);
                    me.arrayDatosVentas.sort((b, a) => a.cant - b.cant);
                    me.arrayTodosPros.sort((b, a) => a.cant - b.cant);
                    me.arrayTodosDesc.sort((b, a) => a.cant - b.cant);
                })
                .catch(function (error) {
                    console.log(error);
                });
              
            },

            
        },
        mounted() {
            //this.getDatos();
            this.selectFraccionamientos();
            this.selectAsesores();
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
    }
    .div-error{
        display:flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
    .text-muted2 {
        color: #c2cfd6  !important;
    }
    
</style>
