<template>
    <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Terrenos
                        <!--   Boton Nuevo    -->
                        <button type="button" @click="abrirModal('terreno','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-5" v-model="criterio">
                                      <option value="modelos.nombre">Modelos</option>
                                      <option value="tipo">Tipo de Proyecto</option>
                                      <option value="fraccionamientos.nombre">Proyecto</option>
                                    </select>
                                    
                                    <input type="text" v-if="criterio=='modelos.nombre'" v-model="buscar" @keyup.enter="listarModelo(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <input type="text" v-if="criterio=='fraccionamientos.nombre'" v-model="buscar" @keyup.enter="listarModelo(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <select class="form-control col-md-5" v-if="criterio=='tipo'" v-model="buscar" @keyup.enter="listarModelo(1,buscar,criterio)" >
                                        <option value="1">Lotificación</option>
                                        <option value="2">Departamento</option>
                                        <option value="3">Terreno</option>
                                    </select>
                                    <button type="submit" @click="listarModelo(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Fraccionamiento</th>
                                    <th>Etapa</th>
                                    <th>Manzana</th>
                                    <th>num_lote</th>
                                    <th>Calle</th>
                                    <th>Numero</th>
                                    <th>Interior</th>
                                    <th>Terreno</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="terreno in arrayTerreno" :key="terreno.id">
                                    <td style="width:12%">
                                        <button title="Editar" type="button" @click="abrirModal('terreno','actualizar',terreno)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <button title="Borrar" type="button" class="btn btn-danger btn-sm" @click="eliminarTerreno(terreno)">
                                          <i class="icon-trash"></i>
                                        </button> &nbsp;
                                    </td>

                                    <td v-text="terreno.fraccionamiento_id"></td>
                                    <td v-text="terreno.etapa_id"></td>
                                    <td v-text="terreno.manzana_id"></td>
                                    <td v-text="terreno.num_lote"></td>
                                    <td v-text="terreno.calle"></td>
                                    <td v-text="terreno.numero"></td>
                                    <td v-text="terreno.interior"></td>
                                    <td v-text="terreno.terreno"></td>
                                </tr>                               
                            </tbody>
                        </table>  
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
                                    <label class="col-md-3 form-control-label" for="text-input">Fraccionamiento</label>
                                    <div class="col-md-6">
                                       <select class="form-control" v-model="fraccionamiento_id" @click="selectEtapa(fraccionamiento_id), selectManzana(fraccionamiento_id)" >
                                            <option value="0">Seleccione</option>
                                            <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                 <label class="col-md-3 form-control-label" for="text-input">Etapa</label>
                                <div class="col-md-6">
                                    <select class="form-control" v-model="etapa_id">
                                        <option value="0">Seleccione</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                    </select>
                                </div>
                            </div>

                           <!-- Mostrar aqui las manzanas -->
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Manzana</label>
                                   <div class="col-md-6">
                                <select class="form-control" v-model="manzana_id">
                                    <option value="0">Seleccione</option>
                                    <option v-for="manzanas in arrayManzanas" :key="manzanas.id" :value="manzanas.id" v-text="manzanas.manzana"></option>
                                </select>
                                  </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Numero de lote</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="num_lote" class="form-control" placeholder="num_lote">
                                    </div>
                                </div>

                               <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Calle</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="calle" class="form-control" placeholder="calle">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Numero</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="numero" class="form-control" placeholder="numeo">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Interior</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="interior" class="form-control" placeholder="interior">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Terreno (mts&sup2;)</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="terreno" class="form-control" placeholder="terreno">
                                    </div>
                                </div>

                                <!-- Div para mostrar los errores que mande validerModelo -->
                                <div v-show="errorTerreno" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjTerreno" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarTerreno()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarTerreno()">Actualizar</button>
                        </div>
                    </div>
                      <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->

                    <!-- modal para la carga de archivos -->
            <div class="modal fade" tabindex="-1" :class="{'mostrar': modal2}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal2"></h4>
                            <button type="button" class="close" @click="cerrarModal2()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal2()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarTerreno()">Actualizar</button>
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
                id:0,
                fraccionamiento_id : 0,
                etapa_id : 0,
                manzana_id : 0,
                num_lote : 0,
                empresa_id : 0.0,
                calle: '',
                numero: '',
                interior: '',
                terreno: '',
             
                archivo: '',
                success: '',
                arrayTerreno : [],
                modal : 0,
                tituloModal : '',
                modal2 : 0,
                tituloModal2 : '',
                tipoAccion: 0,
                errorTerreno : 0,
                errorMostrarMsjTerreno : [],
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'terrenos.fraccionamiento_id', 
                buscar : '',
                arrayEtapas : [],
                arrayFraccionamientos : [],
                arrayManzanas: [],
                arrayLotes: []
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
            // Metodos para los archivos
            onImageChange(e){

                console.log(e.target.files[0]);

                this.archivo = e.target.files[0];

            },

            formSubmit(e) {

                e.preventDefault();

                let currentObj = this;
                // const config = {

                //     headers: { 'content-type': 'multipart/form-data' }

                // }
                let formData = new FormData();
               // formData.append('id', this.id);
                formData.append('archivo', this.archivo);
                // formData.append('nombre', this.nombre);
                // formData.append('tipo', this.tipo);
                // formData.append('fraccionamiento_id', this.fraccionamiento_id);
                // formData.append('terreno', this.terreno);
                // formData.append('construccion', this.construccion);
                let me = this;
                axios.post('/formSubmit/'+this.id, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Archivo guardado correctamente',
                        showConfirmButton: false,
                        timer: 2000
                        })
                    me.cerrarModal2();
                    me.listarModelo(1,'','modelo');

                })

                .catch(function (error) {

                    currentObj.output = error;

                });

            },

            /**Metodo para mostrar los registros */
            listarTerreno(page, buscar, criterio){
                let me = this;
                var url = '/terreno?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayTerreno = respuesta.terrenos.data;
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
                me.listarTerreno(page,buscar,criterio);
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

            selectManzana(buscar){
                let me = this;
                
                me.arrayManzanas=[];
                var url = '/select_manzana_proyecto?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayManzanas = respuesta.manzanas;
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
            registrarTerreno(){
                if(this.validarTerreno()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/terreno/registrar',{
                    'fraccionamiento_id': this.fraccionamiento_id,
                    'etapa_id': this.etapa_id,
                    'manzana_id': this.manzana_id,
                    'num_lote': this.num_lote,
                    'empresa_id': this.empresa_id,
                    'calle': this.calle,
                    'numero': this.numero,
                    'interior': this.interior,
                    'terreno': this.terreno
                }).then(function (response){
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarTerreno(1,'','terreno'); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Terreno agregado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            actualizarTerreno(){
                if(this.validarTerreno()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                let me = this;
                //Con axios se llama el metodo update de FraccionaminetoController
                axios.put('/terreno/actualizar',{
                    'fraccionamiento_id': this.fraccionamiento_id,
                    'etapa_id': this.etapa_id,
                    'manzana_id': this.manzana_id,
                    'num_lote': this.num_lote,
                    'empresa_id': this.empresa_id,
                    'calle': this.calle,
                    'numero': this.numero,
                    'interior': this.interior,
                    'terreno': this.terreno,
                    'id' : this.id
                }).then(function (response){
                    me.cerrarModal();
                    me.listarTerreno(1,'','terreno');
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
            eliminarModelo(data =[]){
                this.id=data['id'];
                this.fraccionamiento_id=data['fraccionamiento_id'];
                this.etapa_id=data['etapa_id'];
                this.manzana_id=data['manzana_id'];
                this.num_lote=data['num_lote'];
                this.empresa_id=data['empresa_id'];
                this.calle=data['calle'];
                this.numero=data['numero'];
                this.interior=data['interior'];
                this.terreno=data['terreno'];
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

                axios.delete('/terreno/eliminar', 
                        {params: {'id': this.id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Terreno borrado correctamente.',
                        'success'
                        )
                        me.listarTerreno(1,'','terreno');
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            validarTerreno(){
                this.errorTerreno=0;
                this.errorMostrarMsjTerreno=[];

                if(!this.fraccionamiento_id) //Si la variable Terreno esta vacia
                    this.errorMostrarMsjTerreno.push("El fraccionamiento del Terreno no puede ir vacio.");

                if(this.errorMostrarMsjTerreno.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorTerreno = 1;

                return this.errorTerreno;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.fraccionamiento_id = 0;
                this.etapa_id = 0;
                this.manzana_id = 0;
                this.num_lote = 0;
                this.calle = '';
                this.numero = '';
                this.inteiror = '';
                this.terreno = 0;
                
                this.errorTerreno = 0;
                this.errorMostrarMsjTerreno = [];

            },
              cerrarModal2(){
                this.modal2 = 0;
                this.tituloModal2 = '';
                this.nombre = '';
                this.archivo = '';
                this.errorTerreno = 0;
                this.errorMostrarMsjTerreno = [];

            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(terreno, accion,data =[]){
                switch(terreno){
                    case "terreno":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Modelo';
                                this.nombre = '';
                                this.fraccionamiento_id = 0;
                                this.etapa_id = 0;
                                this.manzana_id = 0;
                                this.num_lote = 0;
                                this.calle = '';
                                this.numero = '';
                                this.inteiror = '';
                                this.terreno = 0;
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal =1;
                                this.tituloModal='Actualizar Modelo';
                                this.tipoAccion=2;
                                this.id=data['id'];
                                this.fraccionamiento_id=data['fraccionamiento_id'];
                                this.etapa_id=data['etapa_id'];
                                this.manzana_id=data['manzana_id'];
                                this.num_lote=data['num_lote'];
                                this.empresa_id=data['empresa_id'];
                                this.calle=data['calle'];
                                this.numero=data['numero'];
                                this.interior=data['interior'];
                                this.terreno=data['terreno'];
                                break;
                            }
                            case 'subirArchivo':
                            {
                                this.modal2 =1;
                                this.tituloModal2='Subir Archivo';
                                this.tipoAccion=3;
                                this.id=data['id'];
                                this.nombre=data['nombre'];
                                this.archivo=data['archivo'];
                                break;
                            }
                        }
                    }
                }
                this.selectFraccionamientos();
                this.selectEtapa(this.fraccionamiento_id);
                this.selectManzana(this.fraccionamiento_id);
            }
        },
        mounted() {
            this.listarTerreno(1,this.buscar,this.criterio);
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
