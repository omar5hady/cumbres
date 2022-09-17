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
                        <i class="fa fa-align-justify"></i> Tipo de Crédito
                        <!--   Boton Nuevo    -->
                        <button type="button" @click="abrirModal('credito','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                        <option value="nombre">Nombre</option>
                                        <option value="institucion_fin">Institucion Financiera</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarCreditos(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarCreditos(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>

                        <TableComponent :cabecera="['Opciones','Crédito','Institucíon Financiera','Dias para firma']">
                            <template v-slot:thead>
                                <tr v-for="credito in arrayCreditos" :key="credito.id">
                                    <td class="td2" style="width:15%">
                                        <button title="Editar" type="button" @click="abrirModal('credito','actualizar',credito)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" @click="eliminarCredito(credito)">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </td>
                                    <td class="td2" v-text="credito.nombre"></td>
                                    <td class="td2" v-text="credito.institucion_fin"></td>
                                    <td class="td2">{{credito.dias_nat}} días naturales</td>
                                </tr>
                            </template>
                        </TableComponent>

                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
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
                        <label class="col-md-3 form-control-label" for="text-input">Crédito</label>
                        <div class="col-md-9">
                            <input type="text" v-model="nombre" class="form-control" placeholder="Crédito">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Institución Financiera</label>
                        <div class="col-md-9">
                            <select class="form-control" v-model="institucion_fin" >
                                <option value="">Seleccione</option>
                                <option v-for="institucion in arrayInstituciones" :key="institucion.id" :value="institucion.nombre" v-text="institucion.nombre"></option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Tiempo para firma</label>
                        <div class="col-md-4">
                            <input type="number" v-model="dias_nat" class="form-control" placeholder="Dias naturales">
                        </div>
                    </div>

                    <div v-show="errorCredito" class="form-group row div-error">
                        <div class="text-center text-error">
                            <div v-for="error in errorMostrarMsjCredito" :key="error" v-text="error">

                            </div>
                        </div>
                    </div>
                </template>
                <template v-slot:buttons-footer>
                    <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarCredito()">Guardar</button>
                    <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarCredito()">Actualizar</button>
                </template>
            </ModalComponent>
            <!--Fin del modal-->
        </main>
</template>

<script>
import ModalComponent from '../Componentes/ModalComponent.vue'
import TableComponent from '../Componentes/TableComponent.vue'


    export default {
        components:{
            ModalComponent,
            TableComponent
        },
        data (){
            return {
                proceso:false,
                id: 0,
                nombre : '',
                institucion_fin : '',
                dias_nat:0,
                arrayCreditos : [],
                arrayInstituciones : [],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                errorCredito : 0,
                errorMostrarMsjCredito : [],
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
            listarCreditos (page,buscar,criterio){
                let me=this;
                var url= '/tipo_credito?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayCreditos = respuesta.Tipos_creditos.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarCreditos(page,buscar,criterio);
            },
            selectInstitucion(){
                let me = this;
                me.arrayDepartamentos=[];
                var url = '/select_inst_financiamiento';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayInstituciones = respuesta.instituciones_financiamiento;
                })
                .catch(function (error) {
                    console.log(error);
                });

            },
            /**Metodo para registrar  */
            registrarCredito(){
                if(this.validarCredito() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }
                this.proceso=true;

                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/tipo_credito/registrar',{
                    'nombre': this.nombre,
                    'institucion_fin' : this.institucion_fin,
                    'dias_nat' : this.dias_nat
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarCreditos(1,'','nombre'); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Crédito agregado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            actualizarCredito(){
                if(this.validarCredito() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;

                let me = this;
                //Con axios se llama el metodo update de DepartamentoController
                axios.put('/tipo_credito/actualizar',{
                    'nombre': this.nombre,
                    'institucion_fin':this.institucion_fin,
                    'dias_nat' : this.dias_nat,
                    'id' : this.id
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal();
                    me.listarCreditos(1,'','nombre'); //se enlistan nuevamente los registros
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
            eliminarCredito(data =[]){
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

                axios.delete('/tipo_credito/eliminar',
                        {params: {'id': this.id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Crédito borrado correctamente.',
                        'success'
                        )
                        me.listarCreditos(1,'','nombre'); //se enlistan nuevamente los registros
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            validarCredito(){
                this.errorCredito=0;
                this.errorMostrarMsjCredito=[];

                if(!this.nombre) //Si la variable departamento esta vacia
                    this.errorMostrarMsjCredito.push("El nombre de la institución no puede ir vacio.");

                if(this.errorMostrarMsjCredito.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorCredito = 1;

                return this.errorCredito;
            },
            cerrarModal(){
                this.proceso=false;
                this.modal = 0;
                this.tituloModal = '';
                this.nombre = '';
                this.institucion_fin = '';
                this.errorCredito = 0;
                this.errorMostrarMsjCredito = [];
                this.tipoAccion = 0;

            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(credito, accion,data =[]){
                this.selectInstitucion()
                switch(credito){
                    case "credito":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Credito';
                                this.nombre ='';
                                this.institucion_fin='';
                                this.dias_nat = 0;
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal =1;
                                this.tituloModal='Actualizar Credito';
                                this.tipoAccion=2;
                                this.id=data['id'];
                                this.nombre=data['nombre'];
                                this.institucion_fin = data['institucion_fin'];
                                this.dias_nat = data['dias_nat'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarCreditos(1,this.buscar,this.criterio);
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
