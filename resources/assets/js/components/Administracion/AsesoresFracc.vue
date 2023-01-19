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
                        <i class="fa fa-align-justify"></i> Asignacion de proyectos
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" v-model="buscar" @keyup.enter="listarFraccionamiento(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <Button   @click="listarFraccionamiento(1,buscar,criterio)" :icon="'fa fa-search'">Buscar</Button>
                                </div>
                            </div>
                        </div>
                        <TableComponent :cabecera="['','Fraccionamiento']">
                            <template v-slot:tbody>
                                <tr v-for="fraccionamiento in arrayFraccionamiento" :key="fraccionamiento.id">
                                        <td class="td2">
                                            <Button :btnClass="'btn-info'" @click="abrirModal(fraccionamiento)" title="Ver asesores asignados"
                                                :icon="'icon-eye'" :size="'btn-sm'">
                                            </Button>
                                        </td>
                                        <td class="td2" v-text="fraccionamiento.nombre"></td>
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
                :titulo="'Asignar asesor'"
                @closeModal="cerrarModal()"
            >
                <template v-slot:body>
                    <RowModal :clsRow1="'col-md-7'" :label1="'Proyecto'">
                        <input type="text" disabled v-model="proyecto" class="form-control" placeholder="Proyecto">
                    </RowModal>
                    <RowModal :clsRow1="'col-md-6'" :label1="'Asesores'" :clsRow2="'col-md-3'">
                        <select class="form-control" v-model="asesor_id">
                            <option value="">Seleccione</option>
                            <option v-for="asesor in arrayAsesores" :key="asesor.id" :value="asesor.id" v-text="asesor.nombre + ' '+asesor.apellidos"></option>
                        </select>
                        <template v-slot:input2>
                            <Button :btnClass="'btn-success'" :icon="'icon-plus'" @click="addAsesor()" title="Añadir asesor"></Button>
                        </template>
                    </RowModal>

                    <div>
                        <div class="modal-header" v-if="arraySelecAsesores.length">
                            <h6 class="modal-title"> Asesores asignados </h6>
                        </div>
                        <TableComponent>
                            <template v-slot:tbody>
                                <tr v-for="selectA in arraySelecAsesores" :key="selectA.id">
                                    <td class="td2" style="width:25%">
                                        <Button :btnClass="'btn-danger'" :size="'btn.sm'" :title="'Quitar asesor'"
                                            :icon="'icon-trash'" @click="quitarAsesor(selectA.id)"
                                        ></Button>
                                    </td>
                                    <td class="td2" v-text="selectA.nombre + ' ' + selectA.apellidos" ></td>
                                </tr>
                            </template>
                        </TableComponent>
                    </div>
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
import RowModal from '../Componentes/ComponentesModal/RowModalComponent.vue'
import Button from '../Componentes/ButtonComponent.vue'

    export default {
        components:{
            ModalComponent,
            TableComponent,
            RowModal,
            Button,
            Nav
        },
        data(){
            return{
                proceso:false,
                id:0,
                arrayFraccionamiento : [],
                arraySelecAsesores :[],
                arrayAsesores:[],

                asesor_id:'',
                proyecto:'',
                proyecto_id:'',
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
                buscar : '',
                modal:0,

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
            listarFraccionamiento(page, buscar, criterio){
                let me = this;
                var url = '/fraccionamiento?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayFraccionamiento = respuesta.fraccionamientos.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            addAsesor(){
                this.proceso=true;

                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/asesores/asignarProyecto',{
                    'proyecto': this.proyecto_id,
                    'asesor':this.asesor_id
                }).then(function (response){
                    me.proceso=false;
                    me.getAsesoresProyecto(me.proyecto_id);
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Asesor asignado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },

            quitarAsesor(id){
                swal({
                title: '¿Quitar asesor de este proyecto?',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si, quitar!'
                }).then((result) => {
                if (result.value) {
                    let me = this;

                axios.delete('/asesores/deleteAsignarProy',
                        {params: {'id': id}}).then(function (response){
                        swal(
                        'Hecho!',
                        'El asesor se ha quitado de este proyecto',
                        'success'
                        )
                        me.getAsesoresProyecto(me.proyecto_id);
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },

            cambiarPagina(page){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarFraccionamiento(page,me.buscar,me.criterio);
            },

             limpiarBusqueda(){
                let me=this;
                me.buscar= "";
            },

            abrirModal(data){
                this.proyecto_id = data['id'];
                this.proyecto = data['nombre'];
                this.modal = 1;
                this.getAsesoresProyecto(this.proyecto_id);

            },

            cerrarModal()
            {
                this.proyecto_id = '';
                this.proyecto = '';
                this.modal=0;
                this.arraySelecAsesores=[];
            },

            selectAsesores(){
                let me = this;
                me.arrayAsesores=[];
                var url = '/select/asesores?tipo=0&condicion=1';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayAsesores = respuesta.personas;
                })
                .catch(function (error) {
                    console.log(error);
                });

            },

            getAsesoresProyecto(id){
                let me = this;
                me.arraySelecAsesores=[];
                var url = '/asesores/getAsesoresProyecto?proyecto='+id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arraySelecAsesores = respuesta.asesores;
                })
                .catch(function (error) {
                    console.log(error);
                });

            },

        },
        mounted() {
            this.listarFraccionamiento(1,this.buscar,this.criterio);
            this.selectAsesores();
        }
    }
</script>
<style>
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
    .div-error{
        display:flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>
