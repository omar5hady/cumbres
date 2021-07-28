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
                        <i class="fa fa-align-justify"></i> Detalles generales
                        <!--   Boton Nuevo    -->
                        <button type="button" @click="abrirModal('general','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-4" v-model="criterio">
                                        <option value="general">Detalle</option>
                                        <option value="dias_garantia">Días de garantía</option>
                                    </select>
                                      <select class="form-control" v-if="criterio=='dias_garantia'" v-model="buscar">
                                            <option value="">Seleccione</option>
                                            <option value="0">0 Dias</option>
                                            <option value="30">1 Mes</option>
                                            <option value="60">2 Mes</option>
                                            <option value="365">1 Año</option>
                                            <option value="730">2 Años</option>
                                            <option value="1095">3 Años</option>
                                            <option value="1460">4 Años</option>
                                            <option value="1825">5 Años</option>
                                        </select>
                                    <input v-else type="text" v-model="buscar" @keyup.enter="listarDetallesGenerales(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarDetallesGenerales(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Detalle general</th>
                                        <th>Garantia (Días)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="general in arrayDetallesGenerales" :key="general.id">
                                        <td style="width:10%">
                                            <button type="button" @click="abrirModal('general','actualizar',general)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                            </button>
                                        </td>
                                        <td v-text="general.general" ></td>
                                        <td v-text="general.dias_garantia" ></td>
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
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Detalle general</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="detalle_general" class="form-control" placeholder="Detalle general">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input"></label>
                                    <div class="col-md-6">
                                        <select class="form-control" v-model="dias_garantia">
                                            <option value="0">0 Dias</option>
                                            <option value="30">1 Mes</option>
                                            <option value="60">2 Mes</option>
                                            <option value="365">1 Año</option>
                                            <option value="730">2 Años</option>
                                            <option value="1095">3 Años</option>
                                            <option value="1460">4 Años</option>
                                            <option value="1825">5 Años</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Div para mostrar los errores que mande validerPaquete -->
                                <div v-show="errorDetalleGeneral" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjDetalleGeneral" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarDetallesGenerales()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarDetallesGenerales()">Actualizar</button>
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
                id :0,
                detalle_general: '',
                dias_garantia: 30,
               
                arrayDetallesGenerales : [],
               
                modal : 0,
                mostrar : 0,
                tituloModal : '',
                tipoAccion: 0,
                errorDetalleGeneral : 0,
                errorMostrarMsjDetalleGeneral : [],
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'general', 
                buscar : '',
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
            },
            
        },
        methods : {
            /**Metodo para mostrar los registros */
            listarDetallesGenerales(page, buscar, criterio){
                let me = this;
                var url = '/detalles/generales?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayDetallesGenerales = respuesta.general.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
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
            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
            cambiarPagina(page, buscar, criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarDetallesGenerales(page,buscar,criterio);
            },
            
            /**Metodo para registrar  */
            registrarDetallesGenerales(){
                if(this.validarDetalle() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;
                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/detalles/generales/registrar',{
                    'detalle_general': this.detalle_general,
                    'dias_garantia': this.dias_garantia,
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Detalle agregado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            actualizarDetallesGenerales(){
                if(this.validarDetalle() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;

                let me = this;
                //Con axios se llama el metodo update de DepartamentoController
                axios.put('/detalles/generales/actualizar',{
                    'detalle_general': this.detalle_general,
                    'dias_garantia': this.dias_garantia,
                    'id': this.id
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal();
                    me.listarDetallesGenerales(me.pagination.current_page,me.buscar,me.criterio);
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
            validarDetalle(){
                this.errorDetalleGeneral=0;
                this.errorMostrarMsjDetalleGeneral=[];

                if(this.detalle_general=='') //Si la variable departamento esta vacia
                    this.errorMostrarMsjDetalleGeneral.push("El nombre del detalle no puede ir vacio.");

                if(this.dias_garantia=='') //Si la variable departamento esta vacia
                    this.errorMostrarMsjDetalleGeneral.push("Selecciona los dias para garantía");

                if(this.errorMostrarMsjDetalleGeneral.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorDetalleGeneral = 1;

                return this.errorDetalleGeneral;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.detalle_general ='';
                this.dias_garantia = 30;
                this.errorMostrarMsjDetalleGeneral = [];
                this.listarDetallesGenerales(this.pagination.current_page,this.buscar,this.criterio);

            },
        
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(modelo, accion,data =[]){
                switch(modelo){
                    case "general":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar detalle general';
                                this.detalle_general = '';
                                this.dias_garantia = 30;
                                this.tipoAccion=1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal =1;
                                this.tituloModal='Actualizar detalle';
                                this.tipoAccion=2;
                                this.id=data['id'];
                                this.detalle_general = data['general'];
                                this.dias_garantia = data['dias_garantia'];
                                break;
                            }
                        }
                    }
                }

            },
        },
        mounted() {
            this.listarDetallesGenerales(1,this.buscar,this.criterio);
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
    .btn-success2 {
        color: #fff;
        background-color: #2c309e;
        border-color: #313a98;
    }
    .btn-success2:active, .btn-success2.active, .show > .btn-success2.dropdown-toggle {
        background-color: #2c309e;
        background-image: none;
        border-color: #313a98;
    }
    .btn-success2:focus, .btn-success2.focus {
        box-shadow: 0 0 0 3px rgba(77, 100, 189, 0.5);
    }
    .btn-success2:hover {
        color: #fff;
        background-color: #2c309e;
        border-color: #313a98;
    }
</style>
