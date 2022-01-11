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
                        <i class="fa fa-align-justify"></i> Datos administración
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-10">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-4" v-model="criterio" @change="limpiarBusqueda()">
                                        <option value="fraccionamientos.nombre">Fraccionamiento</option>
                                        <option value="num_etapa">Etapa</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarEtapa(1)" class="form-control" placeholder="Texto a buscar">
                                </div>
                                <div class="input-group">
                                    <button type="submit" @click="listarEtapa(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Fraccionamiento</th>
                                        <th>Etapa</th>
                                        <th># Cuenta</th>
                                        <th>Clabe interbancaria</th>
                                        <th>Banco</th>
                                        <th>Sucursal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="etapa in arrayEtapa" :key="etapa.id">
                                        <td>
                                            <button type="button" @click="abrirModal('actualizar',etapa)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                            </button> &nbsp;
                                        </td>
                                        <td v-text="etapa.fraccionamiento"></td>
                                        <td v-text="etapa.num_etapa"></td>
                                        <td v-text="etapa.num_cuenta_admin"></td>
                                        <td v-text="etapa.clabe_admin"></td>
                                        <td v-text="etapa.banco_admin"></td>
                                        <td v-text="etapa.sucursal_admin"></td>
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
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Número de cuenta</label>
                                <div class="col-md-5">
                                    <input type="text" pattern="\d*" v-on:keypress="isNumber($event)" v-model="num_cuenta_admin" class="form-control" placeholder="# Cuenta bancaria">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Clabe interbancaria</label>
                                <div class="col-md-5">
                                    <input type="text" pattern="\d*" v-on:keypress="isNumber($event)" v-model="clabe_admin" class="form-control" placeholder="Clabe">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Titular de la cuenta</label>
                                <div class="col-md-5">
                                    <input type="text" v-model="titular_admin" class="form-control" placeholder="Nombre del titular">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Banco</label>
                                <div class="col-md-5">
                                    <input type="text" v-model="banco_admin" class="form-control" placeholder="Institución bancaria">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Sucursal</label>
                                <div class="col-md-5">
                                    <input type="text" v-model="sucursal_admin" class="form-control" placeholder="Sucursal">
                                </div>
                            </div>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" class="btn btn-primary" @click="actualizarEtapa()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->


        </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    export default {
        
        data(){
            return{
                id:'',
                arrayEtapa : [],
                modal : 0,
                tituloModal : '',
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'fraccionamientos.nombre', 
                buscar : '',
                num_cuenta_admin : '',
                clabe_admin : '',
                sucursal_admin : '',
                titular_admin : '',
                banco_admin : ''
            }
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
            }
        },
        methods : {
            /**Metodo para mostrar los registros */
            listarEtapa(page){
                let me = this;
                var url = '/etapa?page=' + page + '&buscar=' + me.buscar + '&criterio=' + me.criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEtapa = respuesta.etapas.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarEtapa(page);
            },
            /**Metodo para registrar  */
            limpiarBusqueda(){
                let me=this;
                me.buscar= "";
            },
            actualizarEtapa(){
                let me = this;
                //Con axios se llama el metodo update de FraccionaminetoController
                axios.put('/postventa/datosDeposito/registrar',{
                    'id' : this.id,
                    'num_cuenta_admin' : this.num_cuenta_admin,
                    'clabe_admin' : this.clabe_admin,
                    'sucursal_admin' : this.sucursal_admin,
                    'titular_admin' : this.titular_admin,
                    'banco_admin' : this.banco_admin
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal();
                    me.listarEtapa(1,'','','etapa');
                    //window.alert("Cambios guardados correctamente");
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Cambios guardados correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },

            isNumber: function(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                    evt.preventDefault();;
                } else {
                    return true;
                }
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(accion,data =[]){
                switch(accion){
                    case 'actualizar':
                    {
                        //console.log(data);
                        this.modal =1;
                        this.tituloModal='Actualizar información de administración';
                        this.id=data['id'];
                        this.num_cuenta_admin = data['num_cuenta_admin'];
                        this.clabe_admin = data['clabe_admin'];
                        this.sucursal_admin = data['sucursal_admin'];
                        this.titular_admin = data['titular_admin'];
                        this.banco_admin = data['banco_admin'];
                        break;
                    }
                }
            }
        },
        mounted() {
            this.listarEtapa(1);
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
</style>
yyy