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
                        <i class="fa fa-align-justify"></i> Cuentas
                        <!--   Boton Nuevo    -->
                        <Button :btnClass="'btn-secondary'" :icon="'icon-plus'" @click="abrirModal('registrar')">Nuevo</Button>
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-4" v-model="criterio">
                                      <option value="num_cuenta"># Cuenta</option>
                                      <option value="sucursal">Sucursal</option>
                                      <option value="banco">Banco</option>
                                    </select>
                                    <select class="form-control" v-if="criterio=='banco'" v-model="buscar">
                                        <option value="">Seleccione</option>
                                        <option v-for="bancos in arrayBancos" @keyup.enter="listarCuentas(1,buscar,criterio)" :key="bancos.id" :value="bancos.nombre" v-text="bancos.nombre"></option>
                                    </select>
                                    <input type="text" v-else v-model="buscar" @keyup.enter="listarCuentas(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <Button :btnClass="'btn-primary'" :icon="'fa fa-search'" @click="listarCuentas(1,buscar,criterio)">Buscar</Button>
                                </div>
                            </div>
                        </div>
                        <TableComponent :cabecera="['','# Cuenta','Sucursal','Banco','Empresa']">
                            <template v-slot:tbody>
                                <tr v-for="cuenta in arrayCuentas" :key="cuenta.id">
                                    <td class="td2" style="width:20%">
                                        <Button :btnClass="'btn-warning'" :size="'btn-sm'" :icon="'icon-pencil'"
                                            @click="abrirModal('actualizar',cuenta)" title="Actualizar"
                                        ></Button>
                                        <Button :btnClass="'btn-danger'" :size="'btn-sm'" :icon="'icon-trash'"
                                            @click="eliminarCuenta(cuenta)" title="Eliminar"
                                        ></Button>
                                    </td>
                                    <td class="td2" v-text="cuenta.num_cuenta"></td>
                                    <td class="td2" v-text="cuenta.sucursal"></td>
                                    <td class="td2" v-text="cuenta.banco"></td>
                                    <td class="td2" v-text="cuenta.empresa"></td>
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
                    <RowModal :clsRow1="'col-md-5'" :label1="'# Cuenta'">
                        <input type="text" v-model="num_cuenta" maxlength="50" class="form-control" placeholder="Numero de cuenta">
                    </RowModal>
                    <RowModal :clsRow1="'col-md-5'" :label1="'Sucursal'">
                        <input type="text" v-model="sucursal" maxlength="50" class="form-control" placeholder="Sucursal">
                    </RowModal>
                    <RowModal :clsRow1="'col-md-6'" :label1="'Banco'">
                        <select class="form-control" v-model="banco">
                            <option value="">Seleccione</option>
                            <option v-for="banco in arrayBancos" :key="banco.id" :value="banco.nombre" v-text="banco.nombre"></option>
                        </select>
                    </RowModal>
                    <RowModal :clsRow1="'col-md-6'" :label1="'Empresa'">
                        <select class="form-control" v-model="empresa" >
                            <option value="">Empresa</option>
                            <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                        </select>
                    </RowModal>
                    <!-- Div para mostrar los errores que mande validerDepartamento -->
                    <div v-show="errorCuenta" class="form-group row div-error">
                        <div class="text-center text-error">
                            <div v-for="error in errorMostrarMsjCuenta" :key="error" v-text="error">
                            </div>
                        </div>
                    </div>
                </template>
                <template v-slot:buttons-footer>
                    <Button v-if="tipoAccion==1" :btnClass="'btn-primary'" :icon="'icon-check'" @click="registrarCuenta()">Guardar</Button>
                    <Button v-if="tipoAccion==2" :btnClass="'btn-primary'" :icon="'icon-check'" @click="actualizarCuenta()">Guardar cambios</Button>
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
                id:0,
                num_cuenta : '',
                sucursal : '',
                banco : '',
                empresa: 'Grupo Constructor Cumbres',
                arrayCuentas : [],
                arrayBancos : [],
                empresas:[],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                errorCuenta : 0,
                errorMostrarMsjCuenta : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'num_cuenta',
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
            listarCuentas(page, buscar, criterio){
                let me = this;
                var url = '/cuenta?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayCuentas = respuesta.cuentas.data;
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
                me.listarCuentas(page,me.buscar,me.criterio);
            },
            getEmpresa(){
                let me = this;
                me.empresas=[];
                var url = '/lotes/empresa/select';
                axios.get(url).then(function (response) {
                    var respuesta = response;
                    me.empresas = respuesta.data.empresas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectBancos(){
                let me = this;
                me.arrayBancos=[];
                var url = '/select_inst_financiamiento';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayBancos = respuesta.instituciones_financiamiento;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            /**Metodo para registrar  */
            registrarCuenta(){
                if(this.proceso==true){
                    return;
                }
                if(this.validarCuenta()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;

                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/cuenta/registrar',{
                    'num_cuenta': this.num_cuenta,
                    'sucursal': this.sucursal,
                    'banco': this.banco,
                    'empresa' : this.empresa
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarCuentas(1,'','num_cuenta'); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Cuenta agregada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            actualizarCuenta(){
                if(this.proceso==true){
                    return;
                }
                if(this.validarCuenta()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;

                let me = this;
                //Con axios se llama el metodo update de DepartamentoController
                axios.put('/cuenta/actualizar',{
                    'num_cuenta': this.num_cuenta,
                    'sucursal': this.sucursal,
                    'banco': this.banco,
                    'id' : this.id,
                    'empresa' : this.empresa
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal();
                    me.listarCuentas(1,'','num_cuenta');
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
            eliminarCuenta(data =[]){
                this.id=data['id'];
                //console.log(this.id);
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

                axios.delete('/cuenta/eliminar',
                        {params: {'id': this.id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Cuenta borrada correctamente.',
                        'success'
                        )
                        me.listarCuentas(1,'','num_cuenta');
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            validarCuenta(){
                this.errorCuenta=0;
                this.errorMostrarMsjCuenta=[];

                if(!this.num_cuenta) //Si la variable departamento esta vacia
                    this.errorMostrarMsjCuenta.push("El numero de cuenta no puede ir vacia.");

                if(!this.sucursal) //Si la variable departamento esta vacia
                    this.errorMostrarMsjCuenta.push("La sucursal no puede ir vacia.");

                if(this.banco == '') //Si la variable departamento esta vacia
                    this.errorMostrarMsjCuenta.push("El banco no puede ir vacio.");

                if(this.errorMostrarMsjCuenta.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorCuenta = 1;

                return this.errorCuenta;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.sucursal = '';
                this.num_cuenta = '';
                this.banco = '';
                this.errorCuenta = 0;
                this.errorMostrarMsjCuenta = [];

            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(accion,data =[]){
                switch(accion){
                    case 'registrar':
                    {
                        this.modal = 1;
                        this.tituloModal = 'Registrar Cuenta';
                        this.sucursal = '';
                        this.num_cuenta = '';
                        this.banco = '';
                        this.tipoAccion = 1;
                        this.empresa = 'Grupo Constructor Cumbres';
                        break;
                    }
                    case 'actualizar':
                    {
                        //console.log(data);
                        this.modal =1;
                        this.tituloModal='Actualizar Cuenta';
                        this.tipoAccion=2;
                        this.num_cuenta=data['num_cuenta'];
                        this.sucursal=data['sucursal'];
                        this.banco=data['banco'];
                        this.id=data['id'];
                        this.empresa = data['empresa'];
                        break;
                    }
                }
            }
        },
        mounted() {
            this.listarCuentas(1,this.buscar,this.criterio);
            this.selectBancos();
            this.getEmpresa();
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
