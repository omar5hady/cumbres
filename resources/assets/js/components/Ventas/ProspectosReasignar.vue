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
                        <i class="fa fa-align-justify"></i> Prospectos a reasignar
                    </div>
                    <!-- Div Card Body para listar -->
                     <template v-if="listado == 1">
                        <div class="card-body"> 
                            <div class="form-group row">
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <input type="text" placeholder="Nombre" v-model="nombre" class="form-control">
                                    </div>
                                    <!-- <div class="input-group">
                                        <input type="text" placeholder="Desde" onfocus="(this.type='date')" onblur="(this.type='text')" v-model="desde" class="form-control">
                                        <input type="text" placeholder="Hasta" onfocus="(this.type='date')" onblur="(this.type='text')" v-model="hasta" class="form-control">
                                    </div> -->
                                    <div class="input-group" >
                                        <select class="form-control" v-model="proyecto" >
                                            <option value="">Seleccione</option>
                                            <option v-for="proyecto in arrayFraccionamientos" :key="proyecto.id" :value="proyecto.id" v-text="proyecto.nombre"></option>
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
                                        <button type="submit" @click="listarProspectos(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table2 table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Nombre</th>
                                            <th>RFC</th>
                                            <th>Celular</th>
                                            <th>Email</th>
                                            <th>Proyecto de interes</th>
                                            <th>Vendedor</th>
                                            <th>Clasificación</th>
                                            <th>Fecha de alta</th>
                                            <th>Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="prospecto in arrayProspectos" :key="prospecto.id">
                                            <td>
                                                <button type="button" @click="getAsesores(prospecto.id,prospecto.proyecto_interes_id)" class="btn btn-primary btn-sm">
                                                    <i class="fa fa-exchange"></i>
                                                </button>
                                            </td>
                                            <td class="td2" v-text="prospecto.cliente"></td>
                                            <td class="td2" v-text="prospecto.rfc"></td>
                                            <td class="td2" v-text="prospecto.celular"></td>
                                            <td class="td2" v-text="prospecto.email"></td>
                                            <td class="td2" v-text="prospecto.proyecto"></td>
                                            <td class="td2" v-text="prospecto.vendedor"></td>
                                            <td class="td2" v-if="prospecto.clasificacion==1">No viable</td>
                                            <td class="td2" v-if="prospecto.clasificacion==2">Tipo A</td>
                                            <td class="td2" v-if="prospecto.clasificacion==3">Tipo B</td>
                                            <td class="td2" v-if="prospecto.clasificacion==4">Tipo C</td>
                                            <td class="td2" v-if="prospecto.clasificacion==5">Ventas</td>
                                            <td class="td2" v-if="prospecto.clasificacion==6">Cancelado</td>
                                            <td class="td2" v-if="prospecto.clasificacion==7">Coacreditado</td>
                                            <td class="td2" v-text="this.moment(prospecto.created_at).locale('es').format('DD/MMM/YYYY')"></td>
                                            <td>
                                                <button title="Ver todas las observaciones" type="button" class="btn btn-info pull-right" 
                                                    @click="observaciones(prospecto.id)">Ver Observaciones</button>
                                            </td>
                                        </tr>                               
                                    </tbody>
                                </table>
                            </div>
                            <nav>
                                <!--Botones de paginacion -->
                                <ul class="pagination">
                                    <li class="page-item" v-if="pagination.current_page > 1">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1)">Ant</a>
                                    </li>
                                    <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                                    </li>
                                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </template>
                </div>
            </div>

             <!--Inicio del modal observaciones-->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title"> Observaciones</h4>
                            <button type="button" class="close" @click="cerrarModal3()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Observacion</label>
                                    <div class="col-md-6">
                                         <textarea rows="3" cols="30" v-model="observacion" class="form-control" placeholder="Observacion"></textarea>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button"  class="btn btn-primary" @click="agregarComentario()">Guardar</button>
                                    </div>
                                </div>

                                
                                <table class="table table-bordered table-striped table-sm" >
                                    <thead>
                                        <tr>
                                            <th>Usuario</th>
                                            <th>Observacion</th>
                                            <th>Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="observacion in arrayObservacion" :key="observacion.id">
                                            
                                            <td v-text="observacion.usuario" ></td>
                                            <td v-text="observacion.comentario" ></td>
                                            <td v-text="observacion.created_at"></td>
                                        </tr>                               
                                    </tbody>
                                </table>
                                
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                        </div>
                    </div>
                      <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
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
                nombre : '',
                desde : '',
                hasta: '',
                proyecto:'',
                arrayProspectos: [],
                arrayFraccionamientos : [],
                arrayLugarContacto: [],
                arrayAsesores : [],
                arrayObservacion : [],
                asesores : {},
                modal : 0,
                id: '',
                observacion : '',
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
            listarProspectos(page){
                let me = this;
                var url = '/clientes/clientesPorReasignar?page=' + page + '&desde=' + me.desde + '&hasta=' + me.hasta + 
                    '&proyecto=' + me.proyecto + '&clasificacion=' + me.clasificacion + '&b_publicidad=' + me.b_publicidad + '&nombre=' + me.nombre;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayProspectos = respuesta.clientes.data;
                    me.pagination = respuesta.pagination;
                    
                
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            getAsesores(id,proyecto){
                let me = this;
                var url = '/prospectos/getAsesores?proyecto='+proyecto;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                        me.arrayAsesores = respuesta.asesores;
                        me.asesores = {};
                        $.map(me.arrayAsesores,
                            function(o) {
                                me.asesores[o.id] = o.asesor;
                            });

                        me.changeProspecto(id);
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            agregarComentario(){
                
                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/clientes/storeObservacion',{
                    'cliente_id': this.id,
                    'observacion': this.observacion
                }).then(function (response){
                    me.listarObservacion(1,me.id);
                    me.observacion = '';
                    //me.cerrarModal3(); //al guardar el registro se cierra el modal
                    
                    const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                    });

                    toast({
                    type: 'success',
                    title: 'Observación Agregada Correctamente'
                    })
                }).catch(function (error){
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

            observaciones(id){
                this.modal =1;
                this.observacion='';
                this.id=id;
                this.listarObservacion(1,id);
            },
            cerrarModal(){
                this.modal = 0;
                this.id = '';
                this.listarProspectos(1);
            },

            listarObservacion(page, buscar){
                let me = this;
                var url = '/clientes/observacion?page=' + page + '&buscar=' + buscar ;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayObservacion = respuesta.observacion.data;
                    console.log(url);
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },

            changeProspecto(id){

                let me = this;
                
                Swal.fire({
                    title: 'Reasignar asesor',
                    input: 'select',
                    inputOptions: this.asesores,
                    inputPlaceholder: 'Selecciona el asesor',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText:'Cancelar',
                    inputValidator: (value) => {
                        return new Promise((resolve) => {
                            //console.log(value);
                            axios.put('/clientes/setVendedorAux',{
                                'id': id,
                                'vendedor' : value
                            }).then(function (response) {
                            me.listarProspectos(1);
                                swal(
                                'Hecho!',
                                'Prospecto reasignado con exito.',
                                'success'
                                )
                            }).catch(function (error) {
                                console.log(error);
                            });
                            resolve();
                        })
                    }
                    })
            },
        
            cambiarPagina(page){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarProspectos(page);
            }

           
        },
        mounted() {
            this.listarProspectos(1);
            this.selectFraccionamientos();
          
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
