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
                        <i class="fa fa-align-justify"></i> Proveedores
                        <!--   Boton Nuevo    -->
                        <button type="button" @click="abrirModal('registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text"  v-model="buscar" @keyup.enter="listarProveedor(1)" class="form-control" placeholder="Producto">
                                    <button type="submit" @click="listarProveedor(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <TableComponent :cabecera="['','Nombre']">
                            <template v-slot:tbody>
                                <tr v-for="proveedor in arrayProveedor.data" :key="proveedor.id">
                                    <td class="td2">
                                        <button 
                                        v-if="userName == 'zaira.valt' || userName == 'shady'"
                                        type="button" @click="abrirModal('actualizar',proveedor)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                        </button>
                                    </td>
                                    <td class="td2" v-text="proveedor.nombre"></td>
                                </tr>  
                            </template>
                        </TableComponent>
                        <nav>
                            <!--Botones de paginacion -->
                           <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="arrayProveedor.current_page > 5" @click="listarProveedor(1)">
                                    <a class="page-link" href="#" >Inicio</a>
                                </li>
                                <li class="page-item" v-if="arrayProveedor.current_page > 1"
                                    @click="listarProveedor(arrayProveedor.current_page-1)">
                                    <a class="page-link" href="#" >Ant</a>
                                </li>

                                <li class="page-item" v-if="arrayProveedor.current_page-3 >= 1"
                                    @click="listarProveedor(arrayProveedor.current_page-3)">
                                    <a class="page-link" href="#" v-text="arrayProveedor.current_page-3"></a>
                                </li>
                                <li class="page-item" v-if="arrayProveedor.current_page-2 >= 1"
                                    @click="listarProveedor(arrayProveedor.current_page-2)">
                                    <a class="page-link" href="#" v-text="arrayProveedor.current_page-2"></a>
                                </li>
                                <li class="page-item" v-if="arrayProveedor.current_page-1 >= 1"
                                    @click="listarProveedor(arrayProveedor.current_page-1)">
                                    <a class="page-link" href="#" v-text="arrayProveedor.current_page-1"></a>
                                </li>
                                <li class="page-item active" >
                                    <a class="page-link" href="#" v-text="arrayProveedor.current_page"></a>
                                </li>
                                <li class="page-item" 
                                    v-if="arrayProveedor.current_page+1 <= arrayProveedor.last_page">
                                    <a class="page-link" href="#" @click="listarProveedor(arrayProveedor.current_page+1)" 
                                    v-text="arrayProveedor.current_page+1"></a>
                                </li>
                                <li class="page-item" 
                                    v-if="arrayProveedor.current_page+2 <= arrayProveedor.last_page">
                                    <a class="page-link" href="#" @click="listarProveedor(arrayProveedor.current_page+2)"
                                     v-text="arrayProveedor.current_page+2"></a>
                                </li>
                                <li class="page-item" 
                                    v-if="arrayProveedor.current_page+3 <= arrayProveedor.last_page">
                                    <a class="page-link" href="#" @click="listarProveedor(arrayProveedor.current_page+3)"
                                    v-text="arrayProveedor.current_page+3"></a>
                                </li>

                                <li class="page-item" v-if="arrayProveedor.current_page < arrayProveedor.last_page"
                                    @click="listarProveedor(arrayProveedor.current_page+1)">
                                    <a class="page-link" href="#" >Sig</a>
                                </li>
                                <li class="page-item" v-if="arrayProveedor.current_page < 5 && arrayProveedor.last_page > 5" @click="listarProveedor(arrayProveedor.last_page)">
                                    <a class="page-link" href="#" >Ultimo</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <ModalComponent v-if="modal"
                :titulo="tituloModal"
                @closeModal="cerrarModal()"
            >
                <template v-slot:body>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">
                            Proveedor <span style="color:red;" v-show="nombre==''">*</span>
                        </label>
                        <div class="col-md-6">
                            <input type="text" v-model="nombre" class="form-control" placeholder="Nombre del proveedor">
                        </div>
                    </div>
                </template>
                <template v-slot:buttons-footer>
                    <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrar()">Guardar</button>
                    <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizar()">Actualizar</button>
                </template>
            </ModalComponent>
            <!--Fin del modal-->
        </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
import ModalComponent from '../Componentes/ModalComponent.vue'
import TableComponent from '../Componentes/TableComponent.vue'

    export default {
        components:{
            ModalComponent,
            TableComponent
        },
        props:{
            userName:{type: String},
        },
        data(){
            return{
                arrayProveedor : [],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                buscar : '',
                nombre : '',
                id : ''
            }
        },
        computed:{
        },
        methods : {
            /**Metodo para mostrar los registros */
            listarProveedor(page){
                let me = this;
                var url = '/inventarios/getProveedores?page=' + page+'&buscar='
                    +this.buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayProveedor = respuesta;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            /**Metodo para registrar  */
            registrar(){
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/inventarios/storeProveedor',{
                    'nombre' : this.nombre
                }).then(function (response){
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarProveedor(1); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Proveedor agregado correctamente',
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
                axios.put('/inventarios/updateProveedor',{
                    'id' : this.id,
                    'nombre' : this.nombre,
                }).then(function (response){
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarProveedor(1); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Proveedor actualizado correctamente',
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
                        this.tituloModal = 'Registrar Proveedor';
                        this.tipoAccion = 1;
                        this.nombre = '';
                        break;
                    }
                    case 'actualizar':
                    {
                        //console.log(data);
                        this.modal =1;
                        this.tituloModal='Actualizar Proveedor';
                        this.id = data['id'];
                        this.nombre = data['nombre'];
                        this.tipoAccion = 2;
                        break;
                    }
                }
            }
        },
        mounted() {
            this.listarProveedor(1);
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
