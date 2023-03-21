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
                        <i class="fa fa-align-justify"></i> Mis prospectos
                    </div>
                    <!-- Div Card Body para listar -->
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" placeholder="Desde" onfocus="(this.type='date')" onblur="(this.type='text')" v-model="desde" class="form-control">
                                    <input type="text" placeholder="Hasta" onfocus="(this.type='date')" onblur="(this.type='text')" v-model="hasta" class="form-control">
                                </div>
                                <div class="input-group" >
                                    <select class="form-control" v-model="proyecto" >
                                        <option value="">Seleccione</option>
                                        <option v-for="proyecto in arrayFraccionamientos" :key="proyecto.id" :value="proyecto.id" v-text="proyecto.nombre"></option>
                                    </select>
                                    <select class="form-control" v-model="publicidad">
                                        <option value="">Seleccione</option>
                                        <option v-for="medios in arrayMediosPublicidad" :key="medios.id" :value="medios.id" v-text="medios.nombre"></option>
                                    </select>
                                </div>
                                <div class="input-group">
                                    <select class="form-control" v-model="clasificacion" >
                                        <option value="1">No viable</option>
                                        <option value="2">Tipo A</option>
                                        <option value="3">Tipo B</option>
                                        <option value="4">Tipo C</option>
                                        <option value="6">Cancelado</option>
                                        <option value="7">Coacreditado</option>
                                        <option value="5">Ventas</option>
                                    </select>
                                </div>
                                <div class="input-group">

                                    <button type="submit" @click="listarProspectos(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    <a :href="'/personal/excelClientes?desde=' + desde+ '&clasificacion=' + clasificacion + '&publicidad=' + publicidad "  class="btn btn-success"><i class="fa fa-file-text"></i>  Excel</a>
                                    <button disabled class="btn btn-primary">
                                        {{'Total: '+arrayProspectos.total}}
                                    </button>
                                </div>

                            </div>
                        </div>
                        <TableComponent
                            :cabecera="['Nombre','Direccion','Celular','Email',
                                        'Proyecto de interes','Clasificación','Fecha de alta','Publicidad']"
                        >
                            <template v-slot:tbody>
                                <tr v-for="prospecto in arrayProspectos.data" :key="prospecto.id">
                                    <td class="td2" v-text="prospecto.n_completo"></td>
                                    <td class="td2" v-text="prospecto.direccion+' Col. '+prospecto.colonia"></td>
                                    <td class="td2" v-text="'+'+prospecto.clv_lada+prospecto.celular"></td>
                                    <td class="td2" v-text="prospecto.email"></td>
                                    <td class="td2" v-text="prospecto.proyecto"></td>
                                    <td class="td2" v-if="prospecto.clasificacion==1">No viable</td>
                                    <td class="td2" v-if="prospecto.clasificacion==2">Tipo A</td>
                                    <td class="td2" v-if="prospecto.clasificacion==3">Tipo B</td>
                                    <td class="td2" v-if="prospecto.clasificacion==4">Tipo C</td>
                                    <td class="td2" v-if="prospecto.clasificacion==5">Ventas</td>
                                    <td class="td2" v-if="prospecto.clasificacion==6">Cancelado</td>
                                    <td class="td2" v-if="prospecto.clasificacion==7">Coacreditado</td>
                                    <td class="td2" v-text="this.moment(prospecto.created_at).locale('es').format('DD/MMM/YYYY')"></td>
                                    <td class="td2" v-if="rolId != 2" v-text="prospecto.publicidad"></td>
                                </tr>
                            </template>
                        </TableComponent>
                        <Nav :current="arrayProspectos.current_page ? arrayProspectos.current_page : 1"
                            :last="arrayProspectos.last_page ? arrayProspectos.last_page : 1"
                            @changePage="listarProspectos">
                        </Nav>
                    </div>
                </div>
            </div>
        </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    import TableComponent from '../Componentes/TableComponent.vue';
    import Nav from '../Componentes/NavComponent.vue';
    export default {
        props:{
            rolId:{type: String}
        },
        components:{
            TableComponent,
            Nav,
        },
        data(){
            return{
                id:0,
                clasificacion:2,
                b_publicidad:'',

                arrayMediosPublicidad:[],
                asesor_id:0,

                publicidad : '',
                desde : '',
                hasta: '',
                proyecto:'',
                arrayProspectos: [],
                arrayFraccionamientos : [],
                arrayLugarContacto: [],
                arrayAsesores : []

            }
        },
        methods : {
            /**Metodo para mostrar los registros */
            listarProspectos(page){
                let me = this;
                var url = '/personal/indexClientes?page=' + page +
                    '&desde=' + me.desde + '&hasta=' + me.hasta + '&proyecto=' + me.proyecto + '&clasificacion=' + me.clasificacion + '&b_publicidad=' + me.b_publicidad;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayProspectos = respuesta.clientes;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            limpiarBusqueda(){
                let me=this;
                me.desde= "";
                me.clasificacion = "";
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

            selectMedioPublicidad(){
                let me = this;
                me.arrayFraccionamientos=[];
                var url = '/select_medio_publicidad';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayMediosPublicidad = respuesta.medios_publicitarios;
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

        },
        mounted() {
            this.listarProspectos(1);
            this.selectMedioPublicidad();
            this.selectFraccionamientos();
            this.selectAsesores();
        }
    }
</script>
<style>
    .td2, .th2 {
        border: solid rgb(200, 200, 200) 1px;
        padding: .5rem;
    }
</style>
