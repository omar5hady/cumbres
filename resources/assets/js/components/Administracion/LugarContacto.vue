<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Lugares de Contacto
                        <!--   Boton Nuevo    -->
                        <Button :btnClass="'btn-secondary'" @click="abrirModal('registrar')" :icon="'icon-plus'">
                            Nuevo
                        </Button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" v-model="buscar" @keyup.enter="listarLugares(1,buscar,'nombre')" class="form-control" placeholder="Texto a buscar">
                                    <Button @click="listarLugares(1,buscar,'nombre')" :icon="'fa fa-search'">Buscar</Button>
                                </div>
                            </div>
                        </div>
                        <TableComponent :cabecera="['Opciones','Nombre']">
                            <template v-slot:tbody>
                                <tr v-for="lugar in arrayLugares" :key="lugar.id">
                                    <td class="td2" style="width:15%">
                                        <Button title="Editar" :btnClass="'btn-warning'" :size="'btn-sm'" :icon="'icon-pencil'"
                                            @click="abrirModal('actualizar',lugar)"
                                        ></Button>
                                        <Button title="Eliminar" :btnClass="'btn-danger'" :size="'btn-sm'" :icon="'icon-trash'"
                                            @click="eliminar(lugar)"
                                        ></Button>
                                    </td>
                                    <td class="td2" v-text="lugar.nombre"></td>
                                </tr>
                            </template>
                        </TableComponent>
                        <Nav :current="pagination.current_page" :last="pagination.last_page"
                            @changePage="cambiarPagina"
                        ></Nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <ModalComponent v-if="modal"
                :titulo="tituloModal"
                :size="'modal-md'"
                @closeModal="cerrarModal()"
            >
                <template v-slot:body>
                    <RowModal :label1="'Lugar de contacto'" :clsRow1="'col-md-8'">
                        <input type="text" v-model="nombre" class="form-control" placeholder="Lugar de contacto">
                    </RowModal>

                    <div v-show="errorLugarContacto" class="form-group row div-error">
                        <div class="text-center text-error">
                            <div v-for="error in errorMostrarMsjLugar" :key="error" v-text="error"></div>
                        </div>
                    </div>
                </template>
                <template v-slot:buttons-footer>
                    <Button v-if="tipoAccion==1" @click="registrarLugar()">Guardar</Button>
                    <Button v-if="tipoAccion==2" @click="actualizarLugar()">Guardar cambios</Button>
                </template>
            </ModalComponent>
            <!--Fin del modal-->
        </main>
</template>

<script>
import ModalComponent from '../Componentes/ModalComponent.vue'
import TableComponent from '../Componentes/TableComponent.vue'
import Nav from '../Componentes/NavComponent.vue'
import Button from '../Componentes/ButtonComponent.vue'
import RowModal from '../Componentes/ComponentesModal/RowModalComponent.vue'

    export default {
        components:{
            ModalComponent,
            TableComponent,
            Nav, RowModal, Button
        },
        data (){
            return {
                proceso:false,
                id: 0,
                nombre : '',
                arrayLugares : [],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                errorLugarContacto : 0,
                errorMostrarMsjLugar : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nombre',
                buscar : ''
            }
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }

                var from = this.pagination.current_page - this.offset;
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;

            }
        },
        methods : {
            listarLugares (page,buscar,criterio){
                let me=this;
                var url= '/lugar_contacto?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayLugares = respuesta.lugares_contacto.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarLugares(page,me.buscar,me.criterio);
            },
            /**Metodo para registrar  */
            registrarLugar(){
                if (this.proceso === true) {
                    return;
                }
                if(this.validarLugar()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }
                this.proceso= true;

                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/lugar_contacto/registrar',{
                    'nombre': this.nombre,
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarLugares(1,'','nombre'); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Lugar de contacto agregado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            actualizarLugar(){
                if (this.proceso === true) {
                    return;
                }
                if(this.validarLugar()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;
                let me = this;
                //Con axios se llama el metodo update de DepartamentoController
                axios.put('/lugar_contacto/actualizar',{
                    'nombre': this.nombre,
                    'id' : this.id
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal();
                    me.listarLugares(1,'','nombre'); //se enlistan nuevamente los registros
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
            eliminar(data =[]){
                this.id=data['id'];
                this.nombre=data['nombre'];
                swal({
                title: '¿Desea eliminar?',
                text: "Esta acción no se puede revertir!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si, eliminar!'
                }).then((result) => {
                if (result.value) {
                    let me = this;

                axios.delete('/lugar_contacto/eliminar',
                        {params: {'id': this.id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Departamento borrado correctamente.',
                        'success'
                        )
                        me.listarLugares(1,'','nombre'); //se enlistan nuevamente los registros
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            validarLugar(){
                this.errorLugarContacto=0;
                this.errorMostrarMsjLugar=[];

                if(!this.nombre) //Si la variable departamento esta vacia
                    this.errorMostrarMsjLugar.push("El nombre no puede ir vacio.");

                if(this.errorMostrarMsjLugar.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorLugarContacto = 1;

                return this.errorLugarContacto;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.nombre = '';
                this.errorLugarContacto = 0;
                this.errorMostrarMsjLugar = [];

            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(accion,data =[]){
                switch(accion){
                    case 'registrar':
                    {
                        this.modal = 1;
                        this.tituloModal = 'Registrar Lugar de contacto';
                        this.departamento ='';
                        this.tipoAccion = 1;
                        this.proceso=false;
                        break;
                    }
                    case 'actualizar':
                    {
                        //console.log(data);
                        this.modal =1;
                        this.tituloModal='Actualizar Lugar de contacto';
                        this.tipoAccion=2;
                        this.id=data['id'];
                        this.nombre=data['nombre'];
                        this.proceso=false;
                        break;
                    }
                }
            }
        },
        mounted() {
            this.listarLugares(1,this.buscar,this.criterio);
        }
    }
</script>
<style>
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
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
