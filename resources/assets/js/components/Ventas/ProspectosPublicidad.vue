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
                     <template v-if="listado == 1">
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
                                        <button type="submit" @click="listarProspectos(1,desde,hasta,proyecto,clasificacion,publicidad)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                        <a :href="'/personal/excelClientes?desde=' + desde+ '&clasificacion=' + clasificacion + '&publicidad=' + publicidad "  class="btn btn-success"><i class="fa fa-file-text"></i>  Excel</a>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table2 table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Direccion</th>
                                            <th>Celular</th>
                                            <th>Email</th>
                                            <th>Proyecto de interes</th>
                                            <th>Clasificación</th>
                                            <th>Fecha de alta</th>
                                            <th>Publicidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="prospecto in arrayProspectos" :key="prospecto.id">
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
                                    </tbody>
                                </table>
                            </div>
                            <nav>
                                <!--Botones de paginacion -->
                                <ul class="pagination">
                                    <li class="page-item" v-if="pagination.current_page > 1">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,desde,hasta,proyecto,clasificacion,publicidad)">Ant</a>
                                    </li>
                                    <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(page,desde,hasta,proyecto,clasificacion,publicidad)" v-text="page"></a>
                                    </li>
                                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,desde,hasta,proyecto,clasificacion,publicidad)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </template>
                </div>
            </div>
        </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    import vSelect from 'vue-select';
    export default {
        props:{
            rolId:{type: String}
        },
        data(){
            return{
                id:0,
                clasificacion:2,
                b_publicidad:'',

                
                arrayMediosPublicidad:[],
                asesor_id:0,

                listado:1,
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
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
        components:{
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },

            //Calcula los elementos de la paginación
            pagesNumber:function(){
                if(!this.pagination.to){
                    return [];
                }

                var from = this.pagination.current_page - this.offset;
                if(from < 1){
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to){
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            },
        },
       
        methods : {
            /**Metodo para mostrar los registros */
            listarProspectos(page, desde, hasta, proyecto, clasificacion, publicidad){
                let me = this;
                var url = '/personal/indexClientes?page=' + page + '&desde=' + desde + '&hasta=' + hasta + '&proyecto=' + proyecto + '&clasificacion=' + clasificacion + '&b_publicidad=' + me.b_publicidad + '&publicidad=' + publicidad;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayProspectos = respuesta.clientes.data;
                    me.pagination = respuesta.pagination;
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

            cambiarPagina(page, desde, hasta, proyecto, clasificacion, publicidad){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarProspectos(page,desde,hasta,proyecto,clasificacion,publicidad);
            }

           
        },
        mounted() {
            this.listarProspectos(1,this.desde,this.hasta,this.proyecto,this.clasificacion,this.publicidad);
            this.selectMedioPublicidad();
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
    .modal-body{
        height: 450px;
        width: 100%;
        overflow-y: auto;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: fixed !important;
        background-color: #3c29297a !important;
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
    .div-error{
        display:flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
    @media (min-width: 600px){
        .btnagregar{
        margin-top: 2rem;
        }
    }
   

</style>
