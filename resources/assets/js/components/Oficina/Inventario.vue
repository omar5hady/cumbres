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
                        <i class="fa fa-align-justify"></i> Inventario
                        <!--   Boton Nuevo    -->
                        <button type="button" v-if="userName == 'zaira.valt' || userName == 'shady'" @click="abrirModal('registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text"  v-model="buscar" @keyup.enter="listarProducto(1)" class="form-control" placeholder="Producto">
                                    <button type="submit" @click="listarProducto(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="table-responsive"> 
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Producto</th>
                                        <th>Unidad</th>
                                        <th>Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="inventario in arrayInventario.data" :key="inventario.id">
                                        <td class="td2">
                                            <button 
                                            v-if="userName == 'zaira.valt' || userName == 'shady'"
                                            type="button" @click="abrirModal('actualizar',inventario)" class="btn btn-warning btn-sm">
                                                <i class="icon-pencil"></i>
                                            </button>
                                        </td>
                                        <td class="td2" v-text="inventario.producto"></td>
                                        <td class="td2" v-text="inventario.unidad"></td>
                                        <td class="td2" v-text="inventario.stock"></td>
                                    </tr>                               
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
                           <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="arrayInventario.current_page > 5" @click="listarProducto(1)">
                                    <a class="page-link" href="#" >Inicio</a>
                                </li>
                                <li class="page-item" v-if="arrayInventario.current_page > 1"
                                    @click="listarProducto(arrayInventario.current_page-1)">
                                    <a class="page-link" href="#" >Ant</a>
                                </li>

                                <li class="page-item" v-if="arrayInventario.current_page-3 >= 1"
                                    @click="listarProducto(arrayInventario.current_page-3)">
                                    <a class="page-link" href="#" v-text="arrayInventario.current_page-3"></a>
                                </li>
                                <li class="page-item" v-if="arrayInventario.current_page-2 >= 1"
                                    @click="listarProducto(arrayInventario.current_page-2)">
                                    <a class="page-link" href="#" v-text="arrayInventario.current_page-2"></a>
                                </li>
                                <li class="page-item" v-if="arrayInventario.current_page-1 >= 1"
                                    @click="listarProducto(arrayInventario.current_page-1)">
                                    <a class="page-link" href="#" v-text="arrayInventario.current_page-1"></a>
                                </li>
                                <li class="page-item active" >
                                    <a class="page-link" href="#" v-text="arrayInventario.current_page"></a>
                                </li>
                                <li class="page-item" 
                                    v-if="arrayInventario.current_page+1 <= arrayInventario.last_page">
                                    <a class="page-link" href="#" @click="listarProducto(arrayInventario.current_page+1)" 
                                    v-text="arrayInventario.current_page+1"></a>
                                </li>
                                <li class="page-item" 
                                    v-if="arrayInventario.current_page+2 <= arrayInventario.last_page">
                                    <a class="page-link" href="#" @click="listarProducto(arrayInventario.current_page+2)"
                                     v-text="arrayInventario.current_page+2"></a>
                                </li>
                                <li class="page-item" 
                                    v-if="arrayInventario.current_page+3 <= arrayInventario.last_page">
                                    <a class="page-link" href="#" @click="listarProducto(arrayInventario.current_page+3)"
                                    v-text="arrayInventario.current_page+3"></a>
                                </li>

                                <li class="page-item" v-if="arrayInventario.current_page < arrayInventario.last_page"
                                    @click="listarProducto(arrayInventario.current_page+1)">
                                    <a class="page-link" href="#" >Sig</a>
                                </li>
                                <li class="page-item" v-if="arrayInventario.current_page < 5 && arrayInventario.last_page > 5" @click="listarProducto(arrayInventario.last_page)">
                                    <a class="page-link" href="#" >Ultimo</a>
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
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">
                                        Producto <span style="color:red;" v-show="producto==''">*</span>
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="producto" class="form-control" placeholder="Nombre del producto">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">
                                        Unidad <span style="color:red;" v-show="unidad==''">*</span>
                                    </label>
                                    <div class="col-md-5">
                                        <input type="text" v-model="unidad" class="form-control" placeholder="Unidad">
                                    </div>
                                </div>
                                
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrar()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizar()">Actualizar</button>
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
        props:{
            userName:{type: String},
        },
        data(){
            return{
                arrayInventario : [],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                buscar : '',
                producto : '',
                unidad : '',
                id : ''
            }
        },
        computed:{
        },
        methods : {
            /**Metodo para mostrar los registros */
            listarProducto(page){
                let me = this;
                var url = '/inventarios/getInventario?page=' + page+'&buscar='
                    +this.buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayInventario = respuesta;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            /**Metodo para registrar  */
            registrar(){
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/inventarios/storeInventario',{
                    'producto' : this.producto,
                    'unidad' : this.unidad
                }).then(function (response){
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarProducto(1); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Producto agregado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            actualizar(){
                
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.put('/inventarios/updateInventario',{
                    'id' : this.id,
                    'producto' : this.producto,
                    'unidad' : this.unidad
                }).then(function (response){
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarProducto(1); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Producto actualizado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            cerrarModal(){
               this.modal = 0;
            },
            
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(accion,data =[]){
                switch(accion){
                    case 'registrar':
                    {
                        this.modal = 1;
                        this.tituloModal = 'Registrar Producto';
                        this.tipoAccion = 1;
                        this.producto = '';
                        this.unidad = '';
                        break;
                    }
                    case 'actualizar':
                    {
                        //console.log(data);
                        this.modal =1;
                        this.tituloModal='Actualizar Vehiculo';
                        this.id = data['id'];
                        this.producto = data['producto'];
                        this.unidad = data['unidad'];
                        this.tipoAccion = 2;
                        break;
                    }
                }
            }
        },
        mounted() {
            this.listarProducto(1);
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
