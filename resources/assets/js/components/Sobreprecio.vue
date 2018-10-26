<template>
    <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
            </ol>
            <div class="container-fluid row">
                <!-- Ejemplo de tabla Listado -->

                <!-- div para sobreprecio de etapas -->
                <div class="card col-sm-6" >
                    <div class="card-header bg-primary">
                        <i class="fa fa-align-justify"></i> Sobreprecios Etapas
                        
                        <!--   Boton Nuevo    -->
                        <!-- <button type="button" @click="abrirModal('sobreprecio_etapa','registrar')" class="btn btn-warning">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button> -->
                        <!---->
                    </div>
                    <div class="card-body" >
                        <div class="form-group">
                            <div class="col-md-8">
                             

                                    <select class="form-control" v-model="fraccionamiento_id" @click="selectEtapa(fraccionamiento_id)" >
                                        <option value="0">Seleccione proyecto</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>
                                        <br/>
                                    <select class="form-control" v-model="etapa_id">
                                            <option value="0">Seleccione etapa</option>
                                            <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                    </select>
                                        <br/>
                                    <button type="button"  class="btn btn-primary" @click="listarSobrePrecioEtapa(1,etapa_id)">Buscar</button>
                               

                            </div>
                        </div>
                        <table class="table bg-light text-dark table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Sobreprecio</th>
                                    <th> $ </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="sobrePrecioEtapa in arraySobreprecioEtapa" :key="sobrePrecioEtapa.id">
                                    <td style="width:10%">
                                        <button type="button" @click="abrirModal('precio_etapa','actualizarEtapa',sobrePrecioEtapa)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button>
                                    </td>
                                    <td v-text="sobrePrecioEtapa.sobreprecionom"></td>
                                    <td v-text="'$ '+sobrePrecioEtapa.sobreprecio"></td>
                                </tr>                               
                            </tbody>
                        </table>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,etapa_id)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,etapa_id)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,etapa_id)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>

                 <!-- div para sobreprecio modelo -->
                <div class="card col-sm-6">
                    <div class="card-header bg-primary">
                        <i class="fa fa-align-justify"></i> Sobreprecios por vivienda
                
                        <!--   Boton Nuevo    -->
                        <button type="button" @click="abrirModal('precio_etapa','registrar')" class="btn btn-warning">
                            <i class="icon-plus"></i> Nuevo
                        </button>
                        <!---->
                    </div>
                    <div class="card-body" >
                        <div class="form-group">
                            <div class="col-md-8">
                                

                                    <select class="form-control" v-model="fraccionamiento_id" @click="selectEtapa(fraccionamiento_id)" >
                                        <option value="0">Seleccione proyecto</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>
                                        
                                        <br/>

                                    <select class="form-control" v-model="etapa_id">
                                            <option value="0">Seleccione etapa</option>
                                            <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                    </select>

                                        <br/>

                                    <button type="button"  class="btn btn-primary" @click="actualizarPrecioEtapa(),listarSobrePrecioEtapa(1,id)">Buscar</button>
                                

                                <div class="input-group">
                                   
                                </div>
                            </div>
                        </div>
                        <table class="table bg-light text-dark table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Modelo</th>
                                    <th>Precio Modelo</th>
                                </tr>
                            </thead>
                            <!--<tbody>
                                <tr v-for="precioModelo in arrayPrecioModelos" :key="precioModelo.id">
                                    <td style="width:10%">
                                        <button type="button" @click="abrirModal('precio_etapa','actualizar',precioModelo)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" @click="eliminarPrecioModelo(precioModelo)">
                                          <i class="icon-trash"></i>
                                        </button>
                                    </td>
                                    <td v-text="precioModelo.modelo"></td>
                                    <td v-text="'$ '+precioModelo.precio_modelo"></td>
                                </tr>                               
                            </tbody>-->
                        </table>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
           
           <!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
           ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
           ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->

            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
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
                                        <select class="form-control" v-model="fraccionamiento_id" @click="selectEtapa(fraccionamiento_id)" >
                                            <option value="0">Seleccione</option>
                                            <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Etapa</label>
                                    <div class="col-md-6">
                                       <select class="form-control" v-model="etapa_id" @click="selectManzanas(fraccionamiento_id,etapa_id),selectSobrepreciosEtapas(etapa_id)" >
                                            <option value="0">Seleccione</option>
                                            <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Manzanas</label>
                                    <div class="col-md-6">
                                       <select class="form-control" v-model="manzana" @click="selectLotesManzana(fraccionamiento_id,etapa_id,manzana)">
                                            <option value="0">Seleccione</option>
                                            <option v-for="manzanas in arrayManzanas" :key="manzanas.id" :value="manzanas.manzana" v-text="manzanas.manzana"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Lote</label>
                                    <div class="col-md-6">
                                       <select class="form-control" v-model="lote_id">
                                            <option value="0">Seleccione</option>
                                            <option v-for="lotes in arrayLotes" :key="lotes.id" :value="lotes.id" v-text="lotes.num_lote"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Sobreprecios</label>
                                    <div class="col-md-6">
                                       <select class="form-control" v-model="sobreprecioModelo_id">
                                             <option v-for="sobrepreciosM in arraySobreprecioEtapaModelo" :key="sobrepreciosM.id" :value="sobrepreciosM.id" v-text="sobrepreciosM.sobreprecioEtapa"></option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Div para mostrar los errores que mande validerDepartamento -->
                                <div v-show="errorSobrePrecioEtapa" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjSobrePrecioEtapa" :key="error" v-text="error">

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
                            <button type="button" v-if="tipoAccion==3" class="btn btn-primary" @click="actualizarSobrePrecioEtapa()">Actualizar</button>
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
                id_sobrePrecioEtapa: 0,
                id:0,
                fraccionamiento_id : 0,
                etapa_id : 0,
                lote_id : 0,
                manzana : '',
                sobreprecio_id : 0,
                sobreprecioModelo_id : 0,
                precio_excedente : 0,
                precio_modelo: 0,
                arraySobreprecioEtapa : [],
                arraySobreprecioEtapaModelo : [],
                arrayFraccionamientos:[],
                arrayLotes: [],
                arrayManzanas:[],
                modelo_id: 0,
                arrayEtapas:[],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                errorSobrePrecioEtapa : 0,
                errorMostrarMsjSobrePrecioEtapa : [],
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
                buscar1: '',
                buscar2: '',
                buscar3:''
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
            listarSobrePrecioEtapa(page, buscar){
                let me = this;
                var url = '/sobreprecio_etapa?page=' + page + '&buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arraySobreprecioEtapa = respuesta.sobreprecio_etapa.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page, buscar,){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarSobrePrecioEtapa(page,buscar);
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
            selectLotesManzana(buscar1, buscar2, buscar3){
                let me = this;

                me.arrayLotes=[];
                var url = '/select_lotes_manzana?buscar=' + buscar1 + '&buscar1='+ buscar2 + '&buscar2='+ buscar3;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLotes = respuesta.lote_manzana;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectManzanas(buscar1, buscar2){
                let me = this;

                me.arrayManzanas=[];
                var url = '/select_manzanas_etapa?buscar=' + buscar1 + '&buscar1='+ buscar2;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayManzanas = respuesta.manzana;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectSobrepreciosEtapas(buscar1){
                let me = this;

                me.arraySobreprecioEtapaModelo=[];
                var url = '/select_sobreprecios_etapa?buscar=' + buscar1;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arraySobreprecioEtapaModelo = respuesta.sobreprecio_etapaM;
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
             /**Metodo para registrar  */
            registrarPrecioModelo(){
                if(this.validarSobrePrecioEtapa()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/precio_modelo/registrar',{
                    'precio_etapa_id': this.id,
                    'modelo_id': this.modelo_id,
                    'precio_modelo': this.precio_modelo
                }).then(function (response){
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarSobrePrecioEtapa(1,this.id); //se enlistan nuevamente los registros
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
            actualizarSobrePrecioEtapa(){
                if(this.validarSobrePrecioEtapa()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                let me = this;
                var etapa = this.etapa_id
                //Con axios se llama el metodo update de DepartamentoController
                axios.put('/sobreprecio_etapa/actualizar',{
                    'id': this.id_sobrePrecioEtapa,
                    'etapa_id': this.etapa_id,
                    'sobreprecio_id': this.sobreprecio_id,
                    'sobreprecio' : this.sobreprecioEtapa
                }).then(function (response){
                    me.cerrarModal();
                    me.listarSobrePrecioEtapa(1,etapa);
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
                this.id_sobrePrecioEtapa=data['id'];
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
                        {params: {'id': this.id_sobrePrecioEtapa}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Departamento borrado correctamente.',
                        'success'
                        )
                        me.listarSobrePrecioEtapa(1,'');
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            validarSobrePrecioEtapa(){
                this.errorSobrePrecioEtapa=0;
                this.errorMostrarMsjSobrePrecioEtapa=[];

                if(!this.fraccionamiento_id) //Si la variable departamento esta vacia
                    this.errorMostrarMsjSobrePrecioEtapa.push("Seleccione un proyecto.");

                if(!this.etapa_id) //Si la variable departamento esta vacia
                     this.errorMostrarMsjSobrePrecioEtapa.push("Seleccione una etapa.");

                if(this.errorMostrarMsjSobrePrecioEtapa.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorSobrePrecioEtapa = 1;

                return this.errorSobrePrecioEtapa;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.errorSobrePrecioEtapa = 0;
                this.errorMostrarMsjSobrePrecioEtapa = [];

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
                            case 'actualizarEtapa':
                            {
                                //console.log(data);
                                this.modal =1;
                                this.tituloModal='Actualizar Sobreprecio para etapa';
                                this.tipoAccion=3;
                                this.id_sobrePrecioEtapa=data['id'];
                                this.etapa_id = data['etapa_id']
                                this.sobreprecio_id = data['sobreprecio_id'];
                                this.sobreprecioEtapa = data['sobreprecio'];
                                break;
                            }
                            case 'actualizarLote':
                            {
                                //console.log(data);
                                this.modal =1;
                                this.tituloModal='Actualizar precio para modelo';
                                this.tipoAccion=2;
                                this.id_sobrePrecioEtapa=data['id'];
                                this.modelo_id = data['modelo_id']
                                this.precio_modelo = data['precio_modelo'];
                                break;
                            }
                        }
                    }
                }
                this.selectFraccionamientos();
                this.selectEtapa(this.fraccionamiento_id);
                this.selectManzanas(this.fraccionamiento_id, this.etapa_id);
                this.selectLotesManzana(this.fraccionamiento_id, this.etapa_id,this.manzana);
                this.selectSobrepreciosEtapas(this.etapa_id);
            }
        },
        mounted() {
            this.selectFraccionamientos();
            this.selectEtapa(this.fraccionamiento_id);
            this.selectModelos(this.fraccionamiento_id, this.etapa_id);
            this.listarSobrePrecioEtapa(1,this.buscar);
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
        position: absolute !important;
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
