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
                        <i class="fa fa-align-justify"></i> Fraccionamientos
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-4" v-model="criterioFracc" @click="limpiarBusqueda()">
                                        <option value="nombre">Fraccionamiento</option>
                                    </select>
                                    <input type="text" v-if="criterioFracc=='nombre'"  v-model="buscarFracc" @keyup.enter="listarFraccionamiento(1,buscarFracc,criterioFracc)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarFraccionamiento(1,buscarFracc,criterioFracc)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                       
                                        <th>Fraccionamiento</th>
                                        <th>Correo de la administración</th>
                                    
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="fraccionamiento in arrayFraccionamiento" :key="fraccionamiento.id">
                                        <td v-text="fraccionamiento.nombre"></td>
                                        <td>
                                        <input type="text" @keyup.enter="actualizarCorreo(fraccionamiento.id,$event.target.value)" :id="fraccionamiento.id" :value="fraccionamiento.email_administracion"  class="form-control" >
                                        </td>
                                    </tr>                               
                                </tbody>
                            </table>
                        </div>
                     <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="paginationFracc.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPaginaFraccionamiento(paginationFracc.current_page - 1,buscarFracc,criterioFracc)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumberFracc" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPaginaFraccionamiento(pageFracc,buscarFracc,criterioFracc)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="paginationFracc.current_page < paginationFracc.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPaginaFraccionamiento(paginationFracc.current_page + 1,buscarFracc,criterioFracc)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>

            <!-- Datos correspondientes a las etapas -->
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card scroll-box">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Etapas
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-4" v-model="criterio" @click="limpiarBusqueda()">
                                        <option value="fraccionamientos.nombre">Fraccionamiento</option>
                                        <option value="f_ini">Fecha de inicio</option>
                                        <option value="f_fin">Fecha de termino</option>
                                    </select>
                                    <input type="date" v-if="criterio=='f_ini'" v-model="buscar" @keyup.enter="listarEtapa(1,buscar,buscar2,criterio)" class="form-control col-md-6" placeholder="fecha inicio" >
                                    <input type="date" v-if="criterio=='f_ini'" v-model="buscar2"  @keyup.enter="listarEtapa(1,buscar,buscar2,criterio)" class="form-control col-md-6" placeholder="fecha fin" >
                                    <input type="date" v-if="criterio=='f_fin'" v-model="buscar" @keyup.enter="listarEtapa(1,buscar,buscar2,criterio)" class="form-control" placeholder="fecha inicio" >
                                    <input type="date" v-if="criterio=='f_fin'" v-model="buscar2"  @keyup.enter="listarEtapa(1,buscar,buscar2,criterio)" class="form-control" placeholder="fecha fin" >
                                    <input type="text" v-if="criterio=='fraccionamientos.nombre'"  v-model="buscar" @keyup.enter="listarEtapa(1,buscar,buscar2,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarEtapa(1,buscar,buscar2,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Etapa</th>
                                        <th>Fraccionamiento</th>
                                        <th>Fecha de inicio </th>
                                        <th>Fecha de termino</th>
                                        <th>Cuenta</th>
                                        <th>CLABE</th>
                                        <th>Banco</th>
                                        <th>Sucursal</th>
                                        <th>Titular</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="etapa in arrayEtapa" :key="etapa.id">
                                        <td class="td2" style="width:10%">
                                            <button title="Asignar datos de transferencia" type="button" @click="abrirModal('etapa','costo',etapa)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                            </button> &nbsp;
                                        </td>
                                        <td class="td2" v-text="etapa.num_etapa"></td>
                                        <td class="td2" v-text="etapa.fraccionamiento"></td>
                                        <td class="td2" v-text="etapa.f_ini"></td>
                                        <td class="td2" v-text="etapa.f_fin"></td>
                                        <td class="td2" v-text="etapa.num_cuenta_admin"></td>
                                        <td class="td2" v-text="etapa.clabe_admin"></td>
                                        <td class="td2" v-text="etapa.banco_admin"></td>
                                        <td class="td2" v-text="etapa.sucursal_admin"></td>
                                        <td class="td2" v-text="etapa.titular_admin"></td>  

                                    </tr>                               
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,buscar2,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,buscar2,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,buscar2,criterio)">Sig</a>
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
                                    <label class="col-md-3 form-control-label" for="text-input">Banco</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="banco">
                                            <option value="">Seleccione</option>
                                            <option v-for="banco in arrayBancos" :key="banco.banco" :value="banco.banco" v-text="banco.banco"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Cuenta</label>
                                    <div class="col-md-4">
                                        <input type="text" v-on:keypress="isNumber($event)" v-model="cuenta" class="form-control" placeholder="No. Cuenta">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">CLABE</label>
                                    <div class="col-md-4">
                                        <input type="text" v-on:keypress="isNumber($event)" v-model="clabe" class="form-control" placeholder="No. CLABE">
                                    </div>
                                </div>
 
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Titular</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="titular" class="form-control" placeholder="Titular">
                                    </div>
                                </div>

                      
                          </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" class="btn btn-info" v-if="tipoAccion==2" @click="registrarDatosCuenta()">Guardar</button>
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
                proceso : false,
                id:0,
                num_etapa : 0,
                banco: '',
                titular: '',
                sucursal: '',
                clabe: '',
                cuenta: '',
                arrayBancos: [],
                arrayEtapa : [],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                paginationFracc : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'fraccionamientos.nombre', 
                buscar : '',
                criterioFracc : 'nombre', 
                buscarFracc : '',
                buscar2: '',
                arrayFraccionamiento : [],

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
            pagesNumberFracc:function(){
                if(!this.paginationFracc.to){
                    return [];
                }

                var from = this.paginationFracc.current_page - this.offset;
                if(from < 1){
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.paginationFracc.last_page){
                    to = this.paginationFracc.last_page;
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

         
             registrarDatosCuenta(){
                if(this.proceso==true){
                    return;
                }
             
                this.proceso=true;
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.put('/postventa/datosDeposito/registrar/',{
                    'id':this.id,
                    'cuenta': this.cuenta,
                    'clabe': this.clabe,
                    'titular': this.titular,
                    'sucursal': this.sucursal,
                    'banco' : this.banco
                    
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarEtapa(1,'','','fraccionamiento.nombre');

                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Datos guardados',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },

            selectCuenta(){
                let me = this;
                me.arrayBancos=[];
                var url = '/select_cuenta';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayBancos = respuesta.cuentas;
                })
                .catch(function (error) {
                    console.log(error);
                });

            },


            /**Metodo para mostrar los registros */
            listarEtapa(page, buscar, buscar2, criterio){
                let me = this;
                var url = '/etapa?page=' + page + '&buscar=' + buscar + '&buscar2=' + buscar2 + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEtapa = respuesta.etapas.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
           
            cambiarPagina(page, buscar, buscar2, criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarEtapa(page,buscar,buscar2,criterio);
            },

            listarFraccionamiento(pageFracc, buscarFracc, criterioFracc){
                let me = this;
                var url = '/fraccionamiento?page=' + pageFracc + '&buscar=' + buscarFracc + '&criterio=' + criterioFracc;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayFraccionamiento = respuesta.fraccionamientos.data;
                    me.paginationFracc = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPaginaFraccionamiento(pageFracc, buscarFracc, criterioFracc){
                let me = this;
                //Actualiza la pagina actual
                me.paginationFracc.current_page = pageFracc;
                //Envia la petición para visualizar la data de esta pagina
                me.listarFraccionamiento(pageFracc,buscarFracc,criterioFracc);
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
            actualizarCorreo(idFracc,correo){
                let me = this;
                //Con axios se llama el metodo update de PartidaController
                axios.put('/postventa/actualizarCorreoAdmin',{
                    'correo':correo,
                    'id' : idFracc
                }).then(function (response){
                    
                   me.listarFraccionamiento(1,'','fraccionamiento');
                    //window.alert("Cambios guardados correctamente");
                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000

                    });

                    toast({
                    type: 'success',
                    title: 'Cambios guardados'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },
             limpiarBusqueda(){
                let me=this;
                me.buscar= "";
                me.buscar2="";
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.num_etapa = '';
            },

            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(modelo, accion,data =[]){
                switch(modelo){
                    case "etapa":
                    {
                        switch(accion){
                          
                             case 'costo':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Datos bancarios para pago de la administración';
                                this.id=data['id'];
                                this.banco = data['banco_admin'];
                                this.cuenta = data['num_cuenta_admin'];
                                this.clabe =data['clabe_admin'];
                                this.titular =data['titular_admin'];
                                this.sucursal =data['sucursal_admin'];
                                this.tipoAccion = 2;
                                break;
                            }
                           
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarEtapa(1,this.buscar,this.buscar2,this.criterio);
            this.listarFraccionamiento(1,this.buscarFracc,this.criterioFracc);
            this.selectCuenta();
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
