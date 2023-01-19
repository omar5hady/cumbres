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
                        <i class="fa fa-align-justify"></i> Instituciones de Financiamiento
                        <!--   Boton Nuevo    -->
                        <Button :btnClass="'btn-secondary'" :icon="'icon-plus'" @click="abrirModal('registrar')" title="Nuevo">
                            Nuevo
                        </Button>
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" v-model="buscar" @keyup.enter="listarInstituciones(1,buscar,'nombre')" class="form-control" placeholder="Texto a buscar">
                                    <Button :icon="'fa fa-search'" @click="listarInstituciones(1,buscar,'nombre')">Buscar</Button>
                                </div>
                            </div>
                        </div>
                        <TableComponent :cabecera="['Opciones','Institución']">
                            <template v-slot:thead>
                                <tr v-for="institucion in arrayInstituciones" :key="institucion.id">
                                    <td class="td2" style="width:15%">
                                        <Button :btnClass="'btn-warning'" :size="'btn-sm'" :icon="'icon-pencil'"
                                            @click="abrirModal('actualizar',institucion)"
                                        ></Button>
                                        <Button :btnClass="'btn-danger'" :size="'btn-sm'" :icon="'icon-trash'"
                                            @click="eliminarInstitucion(institucion)"
                                        ></Button>
                                    </td>
                                    <td class="td2" v-text="institucion.nombre"></td>
                                    <td class="td2"> Licencia {{ lic == 0 ? 'antes' : 'despues' }}</td>
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
                @closeModal="cerrarModal()"
            >
                <template v-slot:body>
                    <RowModal :clsRow1="'col-md-7'" :label1="'Institución'">
                        <input type="text" v-model="nombre" class="form-control" placeholder="Institución de Financiamiento">
                    </RowModal>
                    <RowModal :label1="'Licencia'">
                        <select class="form-control" v-model="lic">
                            <option value="0">Antes</option>
                            <option value="1">Despues</option>
                        </select>
                    </RowModal>
                    <div v-show="errorInstitucion" class="form-group row div-error">
                        <div class="text-center text-error">
                            <div v-for="error in errorMostrarMsjInstitucion" :key="error" v-text="error"></div>
                        </div>
                    </div>
                </template>
                <template v-slot:buttons-footer>
                    <Button v-if="tipoAccion==1" @click="registrarInstitucion()" :icon="'icon-check'">Guardar</Button>
                    <Button v-else @click="actualizarInstitucion()" :icon="'icon-check'">Guardar cambios</Button>
                </template>
            </ModalComponent>
            <!--Fin del modal-->
        </main>
</template>

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
            Button, RowModal, Nav
        },
        data (){
            return {
                proceso:false,
                id: 0,
                nombre : '',
                arrayInstituciones : [],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                errorInstitucion : 0,
                errorMostrarMsjInstitucion : [],
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
                lic:0,
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
            listarInstituciones (page,buscar,criterio){
                let me=this;
                var url= '/institucion_financiamiento?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayInstituciones = respuesta.instituciones_financiamiento.data;
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
                me.listarInstituciones(page,me.buscar,me.criterio);
            },
            /**Metodo para registrar  */
            registrarInstitucion(){
                if(this.validarinstitucion() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;
                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/institucion_financiamiento/registrar',{
                    'nombre': this.nombre,
                    'lic':this.lic
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarInstituciones(1,'','nombre'); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Institucion de Financiamiento agregado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            actualizarInstitucion(){
                if(this.validarinstitucion() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }
                this.proceso=true;

                let me = this;
                //Con axios se llama el metodo update de DepartamentoController
                axios.put('/institucion_financiamiento/actualizar',{
                    'nombre': this.nombre,
                    'id' : this.id,
                    'lic' : this.lic
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal();
                    me.listarInstituciones(1,'','nombre'); //se enlistan nuevamente los registros
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
            eliminarInstitucion(data =[]){
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

                axios.delete('/institucion_financiamiento/eliminar',
                        {params: {'id': this.id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Departamento borrado correctamente.',
                        'success'
                        )
                        me.listarInstituciones(1,'','nombre'); //se enlistan nuevamente los registros
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            validarinstitucion(){
                this.errorInstitucion=0;
                this.errorMostrarMsjInstitucion=[];

                if(!this.nombre) //Si la variable departamento esta vacia
                    this.errorMostrarMsjInstitucion.push("El nombre de la institución no puede ir vacio.");

                if(this.errorMostrarMsjInstitucion.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorInstitucion = 1;

                return this.errorInstitucion;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.nombre = '';
                this.errorInstitucion = 0;
                this.errorMostrarMsjInstitucion = [];
                this.tipoAccion = 0;

            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(accion,data =[]){
                this.modal = 1;
                switch(accion){
                    case 'registrar':
                    {
                        this.tituloModal = 'Registrar Institucion de Financiamiento';
                        this.nombre ='';
                        this.lic = 0;
                        this.tipoAccion = 1;
                        break;
                    }
                    case 'actualizar':
                    {
                        //console.log(data);
                        this.tituloModal='Actualizar Institucion de Financiamiento';
                        this.tipoAccion=2;
                        this.id=data['id'];
                        this.nombre=data['nombre'];
                        this.pagina_web = data['pagina_web'];
                        this.lic = data['lic'];
                        break;
                    }
                }
            }
        },
        mounted() {
            this.listarInstituciones(1,this.buscar,this.criterio);
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
