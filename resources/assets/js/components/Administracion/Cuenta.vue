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
                        <button type="button" @click="abrirModal('registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
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
                                    <button type="submit" @click="listarCuentas(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th># Cuenta</th>
                                        <th>Sucursal</th>
                                        <th>Banco</th>
                                        <th>Empresa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="cuenta in arrayCuentas" :key="cuenta.id">
                                        <td style="width:20%">
                                            <button type="button" @click="abrirModal('actualizar',cuenta)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                            </button> &nbsp;
                                            <button type="button" class="btn btn-danger btn-sm" @click="eliminarCuenta(cuenta)">
                                            <i class="icon-trash"></i>
                                            </button>
                                        </td>
                                        <td v-text="cuenta.num_cuenta"></td>
                                        <td v-text="cuenta.sucursal"></td>
                                        <td v-text="cuenta.banco"></td>
                                        <td v-text="cuenta.empresa"></td>
                                    </tr>                               
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
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
                                    <label class="col-md-3 form-control-label" for="text-input"># Cuenta</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="num_cuenta" maxlength="50" class="form-control" placeholder="Numero de cuenta">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Sucursal</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="sucursal" maxlength="50" class="form-control" placeholder="Sucursal">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Banco</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="banco">
                                            <option value="">Seleccione</option>
                                            <option v-for="banco in arrayBancos" :key="banco.id" :value="banco.nombre" v-text="banco.nombre"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Empresa</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="b_empresa" >
                                            <option value="">Empresa</option>
                                            <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Div para mostrar los errores que mande validerDepartamento -->
                                <div v-show="errorCuenta" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjCuenta" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarCuenta()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarCuenta()">Actualizar</button>
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
            cambiarPagina(page, buscar, criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarCuentas(page,buscar,criterio);
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
                    'banco': this.banco
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
                    'id' : this.id
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
                this.num_cuenta=data['num_cuenta'];
                this.sucursal=data['sucursal'];
                this.banco=data['banco'];
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
