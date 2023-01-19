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
                        <i class="fa fa-align-justify"></i> Departamentos
                        <!--   Boton Nuevo    -->
                        <Button :btnClass="'btn-secondary'" :icon="'icon-plus'" @click="abrirModal('departamento','registrar')">Nuevo</Button>
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-4" v-model="criterio">
                                      <option value="departamento">Departamento</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarDepartamento(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <Button :icon="'fa fa-search'" @click="listarDepartamento(1,buscar,criterio)">Buscar</Button>
                                </div>
                            </div>
                        </div>
                        <TableComponent :cabecera="['Opciones','Departamento','Estado']">
                            <template v-slot:tbody>
                                <tr v-for="departamento in arrayDepartamento" :key="departamento.id_departamento">
                                    <td class="td2" style="width:20%">
                                        <Button :btnClass="'btn-warning'" :size="'btn-sm'" :icon="'icon-pencil'"
                                            @click="abrirModal('departamento','actualizar',departamento)" title="Editar"
                                        ></Button>
                                        <Button :btnClass="'btn-danger'" :size="'btn-sm'" :icon="'icon-trash'"
                                            @click="eliminarDepartamento(departamento)" title="Eliminar"
                                        ></Button>
                                    </td>
                                    <td class="td2" v-text="departamento.departamento" style="width:60%"></td>
                                    <td class="td2">
                                        <span class="badge badge-success">Activo</span>
                                    </td>
                                </tr>
                            </template>
                        </TableComponent>
                        <Nav :current="pagination.current_page"
                            :last="pagination.last_page"
                            @changePage="cambiarPagina"
                        ></Nav>
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
                    <RowModal :label1="'Departamento'" :clsRow1="'col-md-9'">
                        <input type="text" v-model="departamento" class="form-control" placeholder="Nombre de departamento">
                    </RowModal>

                    <!-- Div para mostrar los errores que mande validerDepartamento -->
                    <div v-show="errorDepartamento" class="form-group row div-error">
                        <div class="text-center text-error">
                            <div v-for="error in errorMostrarMsjDepartamento"
                                :key="error" v-text="error">
                            </div>
                        </div>
                    </div>
                </template>
                <template v-slot:buttons-footer>
                    <Button v-if="tipoAccion==1" :icon="'icon-check'" @click="registrarDepartamento()">Guardar</Button>
                    <Button v-if="tipoAccion==2" :icon="'icon-check'" @click="actualizarDepartamento()">Guardar cambios</Button>
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
import Nav from '../Componentes/NavComponent.vue'
import Button from '../Componentes/ButtonComponent.vue'
import RowModal from '../Componentes/ComponentesModal/RowModalComponent.vue'

    export default {
        components:{
            ModalComponent,
            TableComponent,
            Nav, Button, RowModal
        },
        data(){
            return{
                proceso:false,
                departamento_id:0,
                departamento : '',
                user_alta : '',
                arrayDepartamento : [],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                errorDepartamento : 0,
                errorMostrarMsjDepartamento : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'departamento',
                buscar : ''
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
            listarDepartamento(page, buscar, criterio){
                let me = this;
                var url = '/departamento?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayDepartamento = respuesta.departamentos.data;
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
                me.listarDepartamento(page,me.buscar,me.criterio);
            },
            /**Metodo para registrar  */
            registrarDepartamento(){
                if(this.proceso==true){
                    return;
                }
                if(this.validarDepartamento()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;

                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/departamento/registrar',{
                    'departamento': this.departamento,
                    'user_alta': this.user_alta
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarDepartamento(1,'','departamento'); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Departamento agregado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            actualizarDepartamento(){
                if(this.proceso==true){
                    return;
                }
                if(this.validarDepartamento()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;

                let me = this;
                //Con axios se llama el metodo update de DepartamentoController
                axios.put('/departamento/actualizar',{
                    'departamento': this.departamento,
                    'user_alta': this.user_alta,
                    'id_departamento' : this.departamento_id
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal();
                    me.listarDepartamento(1,'','departamento');
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
                    me.proceso=false;
                });
            },
            eliminarDepartamento(data =[]){
                this.departamento_id=data['id_departamento'];
                this.departamento=data['departamento'];
                this.user_alta=data['user_alta'];
                //console.log(this.departamento_id);
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

                axios.delete('/departamento/eliminar',
                        {params: {'id_departamento': this.departamento_id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Departamento borrado correctamente.',
                        'success'
                        )
                        me.listarDepartamento(1,'','departamento');
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            validarDepartamento(){
                this.errorDepartamento=0;
                this.errorMostrarMsjDepartamento=[];

                if(!this.departamento) //Si la variable departamento esta vacia
                    this.errorMostrarMsjDepartamento.push("El nombre del departamento no puede ir vacio.");

                if(this.errorMostrarMsjDepartamento.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorDepartamento = 1;

                return this.errorDepartamento;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.departamento = '';
                this.user_alta = '';
                this.errorDepartamento = 0;
                this.errorMostrarMsjDepartamento = [];

            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(modelo, accion,data =[]){
                switch(modelo){
                    case "departamento":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Departamento';
                                this.departamento ='';
                                this.user_alta ='1';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal =1;
                                this.tituloModal='Actualizar Departamento';
                                this.tipoAccion=2;
                                this.departamento_id=data['id_departamento'];
                                this.departamento=data['departamento'];
                                this.user_alta=data['user_alta'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarDepartamento(1,this.buscar,this.criterio);
        }
    }
</script>
<style>
    .div-error{
        display:flex;
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
