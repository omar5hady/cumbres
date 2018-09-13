<template>
    <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Lotes
                        <!--   Boton Nuevo    -->
                        <button type="button" @click="abrirModal('lote','registrar')" class="btn btn-secondary">
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
                                    <th>Proyecto</th>
                                    <th>Etapa</th>
                                    <th>Manzana</th>
                                    <th>Numero de lote</th>
                                    <th>Sublote</th>
                                    <th>Modelo</th>
                                    <th>Empresa</th>
                                    <th>Calle</th>
                                    <th>Numero</th>
                                    <th>Interior</th>
                                    <th>Terreno mts&sup2;</th>
                                    <th>Construcción mts&sup2;</th>
                                    <th>Casa Muestra</th>
                                    <th>Lote comercial</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="modelo in arrayModelo" :key="modelo.id">
                                    <td style="width:12%">
                                        <button title="Editar" type="button" @click="abrirModal('modelo','actualizar',modelo)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <button title="Borrar" type="button" class="btn btn-danger btn-sm" @click="eliminarModelo(modelo)">
                                          <i class="icon-trash"></i>
                                        </button> &nbsp;
                                        <button title="Subir archivo" type="button" @click="abrirModal('modelo','subirArchivo',modelo)" class="btn btn-default btn-sm">
                                          <i class="icon-cloud-upload"></i>
                                        </button>
                                    </td>
                                    <td v-text="modelo.nombre"></td>
                                    <td v-if="modelo.tipo==1" v-text="'Lotificación'"></td>
                                    <td v-if="modelo.tipo==2" v-text="'Departamento'"></td>
                                    <td v-if="modelo.tipo==3" v-text="'Terreno'"></td>
                                    <td v-text="modelo.fraccionamiento"></td>
                                    <td v-text="modelo.terreno"></td>
                                    <td v-text="modelo.construccion"></td>
                                    <td style="width:7%" v-if = "modelo.archivo"><a class="btn btn-default btn-sm" v-bind:href="'/download/'+modelo.archivo"><i class="icon-cloud-download"></i></a></td>
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
                                    <label class="col-md-3 form-control-label" for="text-input">Proyecto</label>
                                    <div class="col-md-6">
                                       <select class="form-control" v-model="fraccionamiento_id" >
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
                                            <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.fraccionamiento_id" v-text="etapas.num_etapa"></option>
                                        </select>
                                    </div>
                                </div>

                                  <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Manzana</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="manzana" class="form-control" placeholder="manzana">
                                    </div>
                                </div>

                                   <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Etapa</label>
                                    <div class="col-md-6">
                                       <select class="form-control" v-model="etapa_id">
                                            <option value="0">Seleccione</option>
                                            <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.fraccionamiento_id" v-text="etapas.num_etapa"></option>
                                        </select>
                                    </div>
                                </div>

                                  <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input"># Lote</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="num_lote" class="form-control" placeholder="num_lote">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Sublote</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="sublote" class="form-control" placeholder="sublote">
                                    </div>
                                </div>

                                  <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Modelo</label>
                                    <div class="col-md-6">
                                       <select class="form-control" v-model="etapa_id">
                                            <option value="0">Seleccione</option>
                                            <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.fraccionamiento_id" v-text="etapas.num_etapa"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Tipo</label>
                                    <div class="col-md-3">
                                        <select class="form-control" v-model="tipo" @click="selectFraccionamientos(tipo)">
                                            <option value="0">Seleccione</option>
                                            <option value="1">Fraccionamiento</option>
                                            <option value="2">Departamento</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Proyecto</label>
                                    <div class="col-md-6">
                                       <select class="form-control" v-model="fraccionamiento_id">
                                            <option value="0">Seleccione</option>
                                            <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Terreno (mts&sup2;)</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="terreno" class="form-control" placeholder="Terreno">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Construcción (mts&sup2;)</label>
                                    <div class="col-md-7">
                                        <input type="text" v-model="construccion" class="form-control" placeholder="Construccion">
                                    </div>
                                </div>
                                <!-- Div para mostrar los errores que mande validerModelo -->
                                <div v-show="errorModelo" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjModelo" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarModelo()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarModelo()">Actualizar</button>
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
                etapa_id: 0,
                manzana: '',
                num_lote: 0,
                sublote: '',
                modelo_id: 0,
                empresa_id: 0,
                calle: '',
                numero: '',
                interior: '',
                terreno : 0.0,
                construccion : 0.0,
                casa_muestra: 0,
                lote_comercial: 0,

                arrayLote : [],
                modal : 0,
                tituloModal : '',
                modal2 : 0,
                tituloModal2 : '',
                tipoAccion: 0,
                errorModelo : 0,
                errorMostrarMsjLote : [],
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'lote.proyecto', 
                buscar : '',
                arrayFraccionamientos : [],
                arrayEtapas : [],
                arrayModelos : [],
                arrayEmpresas : []
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
            listarLote(page, buscar, criterio){
                let me = this;
                var url = '/lote?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLote = respuesta.lotes.data;
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
                me.listarLote(page,buscar,criterio);
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
            /**Metodo para registrar  */
            registrarModelo(){
                if(this.validarModelo()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/modelo/registrar',{                 
                    'fraccionamiento_id': this.fraccionamiento_id,
                    'etapa_id': this.etapa_id,
                    'manzana': this.manzana,
                    'num_lote': this.num_lote,
                    'sublote': this.sublote,
                    'modelo_id': this.modelo_id,
                    'empresa_id': this.empresa_id,
                    'calle': this.calle,
                    'numero': this.numero,
                    'interior': this.interior,
                    'terreno': this.terreno,
                    'construccion': this.construccion,
                    'casa_muestra': this.casa_muestra,
                    'lote_comercial': this.lote_comercial
                }).then(function (response){
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarLote(1,'','lote'); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Lote agregado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            actualizarLote(){
                if(this.validarLote()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                let me = this;
                //Con axios se llama el metodo update de FraccionaminetoController
                axios.put('/lote/actualizar',{
                   'fraccionamiento_id': this.fraccionamiento_id,
                    'etapa_id': this.etapa_id,
                    'manzana': this.manzana,
                    'num_lote': this.num_lote,
                    'sublote': this.sublote,
                    'modelo_id': this.modelo_id,
                    'empresa_id': this.empresa_id,
                    'calle': this.calle,
                    'numero': this.numero,
                    'interior': this.interior,
                    'terreno': this.terreno,
                    'construccion': this.construccion,
                    'casa_muestra': this.casa_muestra,
                    'lote_comercial': this.lote_comercial,
                    'id' : this.id
                }).then(function (response){
                    me.cerrarModal();
                    me.listarLote(1,'','lote');
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
            eliminarLote(data =[]){
                this.id=data['id'];
                this.fraccionamiento_id=data['fraccionamiento_id'];
                this.etapa_id=data['etapa_id'];
                this.manzana=data['manzana'];
                this.num_lote=data['num_lote'];
                this.sublote=data['sublote'];
                this.modelo_id=data['modelo_id'];
                this.empresa_id=data['empresa_id'];
                this.calle=data['calle'];
                this.numero=data['numero'];
                this.interior=data['interior'];
                this.terreno=data['terreno'];
                this.construccion=data['construccion'];
                this.casa_muestra=data['casa_muestra'];
                this.lote_comercial=data['lote_comercial'];
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

                axios.delete('/lote/eliminar', 
                        {params: {'id': this.id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Lote borrado correctamente.',
                        'success'
                        )
                        me.listarLote(1,'','lote');
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            validarLote(){
                this.errorLote=0;
                this.errorMostrarMsjLote=[];

                if(!this.fraccionamiento_id) //Si la variable Lote esta vacia
                    this.errorMostrarMsjLote.push("El nombre del proyecto para el Lote no puede ir vacio.");

                if(this.errorMostrarMsjLote.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorLote = 1;

                return this.errorLote;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.fraccionamiento_id = 0;
                this.etapa_id= 0;
                this.manzana= '';
                this.num_lote= 0;
                this.sublote= '';
                this.modelo_id= 0;
                this.empresa_id= 0;
                this.calle= '';
                this.numero= '';
                this.interior= '';
                this.terreno = 0.0;
                this.construccion = 0.0;
                this.casa_muestra= 0;
                this.lote_comercial= 0;
                
                this.errorLote = 0;
                this.errorMostrarMsjLote = [];

            },
              cerrarModal2(){
                this.modal2 = 0;
                this.tituloModal2 = '';
             
                this.errorLote = 0;
                this.errorMostrarMsjLote = [];

            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(lote, accion,data =[]){
                switch(lote){
                    case "lote":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Lote';
                                this.fraccionamiento_id = 0;
                                this.etapa_id= 0;
                                this.manzana= '';
                                this.num_lote= 0;
                                this.sublote= '';
                                this.modelo_id= 0;
                                this.empresa_id= 0;
                                this.calle= '';
                                this.numero= '';
                                this.interior= '';
                                this.terreno = 0.0;
                                this.construccion = 0.0;
                                this.casa_muestra= 0;
                                this.lote_comercial= 0;
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal =1;
                                this.tituloModal='Actualizar Lote';
                                this.tipoAccion=2;
                                this.id=data['id'];
                                this.fraccionamiento_id=data['fraccionamiento_id'];
                                this.etapa_id=data['etapa_id'];
                                this.manzana=data['manzana'];
                                this.num_lote=data['num_lote'];
                                this.sublote=data['sublote'];
                                this.modelo_id=data['modelo_id'];
                                this.empresa_id=data['empresa_id'];
                                this.calle=data['calle'];
                                this.numero=data['numero'];
                                this.interior=data['interior'];
                                this.terreno=data['terreno'];
                                this.construccion=data['construccion'];
                                this.casa_muestra=data['casa_muestra'];
                                this.lote_comercial=data['lote_comercial'];
                                break;
                            }
                            case 'subirArchivo':
                            {
                                this.modal2 =1;
                                this.tituloModal2='Subir Archivo';
                                this.tipoAccion=3;
                                this.id=data['id'];
                            
                                break;
                            }
                        }
                    }
                }
                this.selectFraccionamientos(this.tipo);
            }
        },
        mounted() {
            this.listarLote(1,this.buscar,this.criterio);
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
