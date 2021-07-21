<template>
    <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card" >
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Precios por etapa
                        <!--   Boton Nuevo    -->
                        <button v-if="rolId != 9" type="button" @click="abrirModal('precio_etapa','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-9">
                                <div class="input-group">
                                    <select class="form-control" v-model="fraccionamiento_id" @click="selectEtapa(fraccionamiento_id)" >
                                        <option value="0">Seleccione proyecto</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>

                                    <select class="form-control" v-model="etapa_id" @click="selectPrecioEtapa(fraccionamiento_id,etapa_id)">
                                            <option value="0">Seleccione etapa</option>
                                            <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                    </select>
                                    
                                   
                                </div>

                                <div class="input-group">
                                    <input type="text"  class="form-control" disabled placeholder='Precio mt2 excedente:'>
                                    <input type="number" v-model="precio_excedente"  class="form-control" placeholder="Precio excedente">
                                    <button type="button" v-if="rolId != 9"  class="btn btn-primary" @click="actualizarPrecioEtapa(),listarPrecioModelo(1,id)">Guardar</button>
                                </div>

                                
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table bg-light text-dark table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th v-if="rolId != 9">Opciones</th>
                                        <th>Modelo</th>
                                        <th>Precio Modelo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="precioModelo in arrayPrecioModelos" :key="precioModelo.id">
                                        <td style="width:10%" v-if="rolId != 9">
                                            <button type="button" @click="abrirModal('precio_etapa','actualizar',precioModelo)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" @click="eliminarPrecioModelo(precioModelo)">
                                            <i class="icon-trash"></i>
                                            </button>
                                        </td>
                                        <td v-text="precioModelo.modelo"></td>
                                        <td v-text="'$'+formatNumber(precioModelo.precio_modelo)"></td>
                                    </tr>                               
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,id)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,id)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,id)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                        <button class="btn btn-sm btn-default" data-toggle="modal" data-target="#manualId">Manual</button>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
           
           <!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
           ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
           ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->

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
                                    <label class="col-md-3 form-control-label" for="text-input">Proyecto</label>
                                    <div class="col-md-6">
                                       <select class="form-control" v-model="fraccionamiento_id" @change="selectEtapa(fraccionamiento_id), selectModelos(fraccionamiento_id)" >
                                            <option value="0">Seleccione</option>
                                            <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Etapa</label>
                                    <div class="col-md-6">
                                       <select class="form-control" v-model="etapa_id" @change="selectPrecioEtapa(fraccionamiento_id,etapa_id)" >
                                            <option value="0">Seleccione</option>
                                            <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Modelo</label>
                                    <div class="col-md-6">
                                       <select class="form-control" v-model="modelo_id">
                                            <option value="0">Seleccione</option>
                                            <template v-for="modelos in arrayModelos">
                                               <option v-if="modelos.nombre != 'Terreno'" :key="modelos.id" :value="modelos.id" v-text="modelos.nombre"></option>
                                            </template>
                                            
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Precio del modelo</label>
                                    <div class="col-md-4" >
                                        <input type="text" pattern="\d*" v-model="precio_modelo" class="form-control" placeholder="Precio del modelo" v-on:keypress="isNumber($event)">
                                    </div>
                                        <strong> <p v-text="'$'+formatNumber(precio_modelo)"></p> </strong>
                                </div>

                                <!-- Div para mostrar los errores que mande validerDepartamento -->
                                <div v-show="errorPrecioEtapa" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjPrecioEtapa" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarPrecioModelo()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarPrecioModelo()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
        
            <!-- Manual -->
            <div class="modal fade" id="manualId" tabindex="-1" role="dialog" aria-labelledby="manualIdTitle" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="manualIdTitle">Manual</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>
                            Dentro de precios de etapa podrá asignar el precio especifico de un modelo asignado a una etapa, 
                            asignar el precio a un modelo es necesario ya que el sistema utilizara este dato par realizar el 
                            calculo del precio del lote en el momento de la venta.
                        </p>
                        <p>
                            Para poder <strong>asignar un precio a un modelo</strong> debe haber creado ya una o mas etapas y sus respectivos 
                            modelos vea los módulos de <strong>“Desarrollo -> Etapas”</strong> y <strong>“Desarrollo -> Modelos”</strong>.
                        </p>
                        <p>
                            Para agregar un precio a un modelo debe dar clic en el botón <strong>Nuevo</strong> que se encuentra en la esquina superior 
                            izquierda, y debe llenar los campos según sea su necesidad.
                        </p>
                        <p>
                            También podrá <strong>asignar un precio a los metros cuadrados excedentes</strong>, así el sistema podrá 
                            calcular el costo de los metros excedentes de un terreno en caso de que cuente con ellos.
                        </p>
                        <p>
                            Recuerde que el sistema le permitirá asignar mas de un precio a un modelo, sin embargo, 
                            el sistema solo podrá tomar un precio y tomara en automático aquel que sea creado primero, 
                            por lo que no es una práctica recomendable. 
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    </div>
                </div>
            </div>
        </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    export default {
        props:{
            rolId:{type: String}
        },
        data(){
            return{
                proceso:false,
                id_precioModelo: 0,
                id:0,
                fraccionamiento_id : 0,
                etapa_id : 0,
                precio_excedente : 0,
                precio_modelo: 0,
                arrayPrecioEtapa : [],
                arrayPreciosEtapa : [],
                arrayPrecioModelos : [],
                arrayFraccionamientos:[],
                arrayModelos:[],
                modelo_id: 0,
                arrayEtapas:[],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                errorPrecioEtapa : 0,
                errorMostrarMsjPrecioEtapa : [],
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                buscar : '',
                buscar2: ''
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
            listarPrecioModelo(page, buscar){
                let me = this;
                me.arrayPrecioModelos = [];
                var url = '/precio_modelo?page=' + page + '&buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPrecioModelos = respuesta.precios_modelos.data;
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
            cambiarPagina(page, buscar){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarPrecioModelo(page,buscar);
            },
            selectFraccionamientos(){
                let me = this;

                me.arrayFraccionamientos=[];
                var url = '/select_fraccionamiento';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayFraccionamientos = respuesta.fraccionamientos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectModelos(buscar1){
                let me = this;

                me.arrayModelos=[];
                var url = '/select_modelo_proyecto?buscar=' + buscar1;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayModelos = respuesta.modelos;
                    var results = me.arrayModelos[1].modelo_id;
                    //me.modelo_id = results;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectEtapa(buscar){
                let me = this;
                
                me.arrayEtapas=[];
                var url = '/select_etapa_proyecto?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEtapas = respuesta.etapas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectPrecioEtapa(buscar,buscar2){
                let me = this;
                
                me.arrayPreciosEtapa=[];
                me.precio_excedente = 0;
                me.id = 0;
                var url = '/select_precio_etapa?buscar=' + buscar + '&buscar2='+ buscar2;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPreciosEtapa = respuesta.precio_etapa;

                    me.precio_excedente = me.arrayPreciosEtapa[0].precio_excedente;
                    me.id = me.arrayPreciosEtapa[0].id;

                    me.listarPrecioModelo(1,me.id);

                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            /**Metodo para registrar  */
            registrarPrecioEtapa(){
                if(this.validarPrecioEtapa() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }
                this.proceso=true;

                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/precio_etapa/registrar',{
                    'fraccionamiento_id': this.fraccionamiento_id,
                    'etapa_id': this.etapa_id,
                    'precio_excedente': this.precio_excedente
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarPrecioModelo(1,''); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Precio de modelo agregado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
             /**Metodo para registrar  */
            registrarPrecioModelo(){
                if(this.validarPrecioEtapa() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }
                this.proceso=true;
                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/precio_modelo/registrar',{
                    'precio_etapa_id': this.id,
                    'modelo_id': this.modelo_id,
                    'precio_modelo': this.precio_modelo
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarPrecioModelo(1,me.id); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Precio de etapa agregado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            actualizarPrecioEtapa(){
                if(this.validarPrecioEtapa() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }
                this.proceso=true;

                let me = this;
                var proyecto = this.id
                //Con axios se llama el metodo update de DepartamentoController
                axios.put('/precio_etapa/actualizar',{
                    'id': this.id,
                    'fraccionamiento_id': this.fraccionamiento_id,
                    'etapa_id': this.etapa_id,
                    'precio_excedente': this.precio_excedente
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal();
                    me.listarPrecioModelo(1,proyecto);
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
            actualizarPrecioModelo(){
                if(this.validarPrecioEtapa() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }
                this.proceso=true;
                let me = this;
                //Con axios se llama el metodo update de DepartamentoController
                axios.put('/precio_modelo/actualizar',{
                    'id': this.id_precioModelo,
                    'precio_etapa_id': this.id,
                    'modelo_id': this.modelo_id,
                    'precio_modelo': this.precio_modelo
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal();
                    me.listarPrecioModelo(1,me.id);
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
            eliminarPrecioModelo(data =[]){
                this.id_precioModelo=data['id'];
                this.modelo_id=data['modelo_id'];
                this.id=data['precio_etapa_id'];
                this.precio_modelo=data['precio_modelo']
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

                axios.delete('/precio_modelo/eliminar', 
                        {params: {'id': this.id_precioModelo}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Departamento borrado correctamente.',
                        'success'
                        )
                        me.listarPrecioModelo(1,'');
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            validarPrecioEtapa(){
                this.errorPrecioEtapa=0;
                this.errorMostrarMsjPrecioEtapa=[];

                if(!this.fraccionamiento_id) //Si la variable departamento esta vacia
                    this.errorMostrarMsjPrecioEtapa.push("Seleccione un proyecto.");

                if(!this.etapa_id) //Si la variable departamento esta vacia
                     this.errorMostrarMsjPrecioEtapa.push("Seleccione una etapa.");

                if(this.errorMostrarMsjPrecioEtapa.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorPrecioEtapa = 1;

                return this.errorPrecioEtapa;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.errorPrecioEtapa = 0;
                this.errorMostrarMsjPrecioEtapa = [];

            },
            
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(modelo, accion,data =[]){
                switch(modelo){
                    case "precio_etapa":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar precio para modelo';
                                this.precio_modelo = 0;
                                this.modelo_id = 0;
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal =1;
                                this.tituloModal='Actualizar precio para modelo';
                                this.tipoAccion=2;
                                this.id_precioModelo=data['id'];
                                this.modelo_id = data['modelo_id']
                                this.precio_modelo = data['precio_modelo'];
                                break;
                            }
                        }
                    }
                }
                this.selectFraccionamientos();
                this.selectEtapa(this.fraccionamiento_id);
                this.selectModelos(this.fraccionamiento_id);
            }
        },
        mounted() {
            this.selectFraccionamientos();
            this.selectEtapa(this.fraccionamiento_id);
            this.selectModelos(this.fraccionamiento_id);
            this.listarPrecioModelo(1,this.buscar);
        }
    }
</script>
<style>
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
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
