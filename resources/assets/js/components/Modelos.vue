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
                        <i class="fa fa-align-justify"></i> Modelos
                        <!--   Boton Nuevo    -->
                        <button type="button" @click="abrirModal('modelo','registrar')" class="btn btn-secondary">
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
                                      <option value="nombre">Modelos</option>
                                      <option value="tipo_proyecto">Tipo de Proyecto</option>
                                    </select>
                                    
                                    <input type="text" v-if="criterio=='nombre'" v-model="buscar" @keyup.enter="listarModelos(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <select class="form-control col-md-5" v-if="criterio=='tipo_proyecto'" v-model="buscar" @keyup.enter="listarModelos(1,buscar,criterio)" >
                                        <option value="1">Lotificación</option>
                                        <option value="2">Departamento</option>
                                        <option value="3">Terreno</option>
                                    </select>
                                    <button type="submit" @click="listarModelos(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Acabado</th>
                                    <th>Tipo</th>
                                    <th>Fraccionamiento</th>
                                    <th>terreno</th>
                                    <th>construccion</th>
                                    <th>Archivo</th>
                                    <th>Planta</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="fraccionamiento in arrayFraccionamiento" :key="fraccionamiento.id">
                                    <td>
                                        <button type="button" @click="abrirModal('fraccionamiento','actualizar',fraccionamiento)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <button type="button" class="btn btn-danger btn-sm" @click="eliminarFraccionamiento(fraccionamiento)">
                                          <i class="icon-trash"></i>
                                        </button>
                                    </td>
                                    <td v-text="fraccionamiento.nombre"></td>
                                    <td v-if="fraccionamiento.tipo_proyecto==1" v-text="'Lotificación'"></td>
                                    <td v-if="fraccionamiento.tipo_proyecto==2" v-text="'Departamento'"></td>
                                    <td v-if="fraccionamiento.tipo_proyecto==3" v-text="'Terreno'"></td>
                                    <td v-text="fraccionamiento.calle"></td>
                                    <td v-text="fraccionamiento.colonia"></td>
                                    <td>
                                        <span class="badge badge-success">Activo</span>
                                    </td>
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
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre del modelo">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Acabado</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="acabado" class="form-control" placeholder="Acabado del modelo">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Tipo</label>
                                    <div class="col-md-3">
                                        <select class="form-control" v-model="tipo">
                                            <option value="0">Seleccione</option>
                                            <option value="1">Fraccionamiento</option>
                                            <option value="2">Departamento</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Terreno</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="terreno" class="form-control" placeholder="Terreno">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Construccion</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="construccion" class="form-control" placeholder="Construccion">
                                    </div>
                                </div>
                                 <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Subir archivo del modelo</label>
                                    <div class="col-md-9">
                                        <input type="file" name="archivo" class="form-control" placeholder="Archivo">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Planta</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="planta">
                                            <option v-for="ciudades in arrayCiudades" :key="ciudades.municipio" :value="ciudades.municipio" v-text="ciudades.municipio"></option>
                                        </select>
                                        <!--<input type="text" v-model="ciudad" class="form-control" placeholder="Ciudad">-->
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
                nombre : '',
                acabado : '',
                tipo : 0,
                fraccionamiento_id : 0,
                terrreno : 0.0,
                construccion : 0.0,
                archivo: '',
                planta: '',
                arrayModelos : [],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                errorModelos : 0,
                errorMostrarMsjModelos : [],
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
                arrayCiudades : []
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
            listarModelo(page, buscar, criterio){
                let me = this;
                var url = '/modelo?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayModelo = respuesta.modelos.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            // selectCiudades(buscar){
            //     let me = this;
            //     me.arrayCiudades=[];
            //     var url = '/select_ciudades?buscar=' + buscar;
            //     axios.get(url).then(function (response) {
            //         var respuesta = response.data;
            //         me.arrayCiudades = respuesta.ciudades;
            //     })
            //     .catch(function (error) {
            //         console.log(error);
            //     });
            // },
            cambiarPagina(page, buscar, criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarFraccionamiento(page,buscar,criterio);
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
                    'nombre': this.nombre,
                    'acabado': this.acabado,
                    'tipo': this.tipo,
                    'fraccionamiento_id': this.fraccionamiento_id,
                    'terreno': this.terreno,
                    'construccion': this.construccion,
                    'archivo': this.archivo,
                    'planta': this.planta
                }).then(function (response){
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarModelo(1,'','modelo'); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Modelo agregado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            actualizarModelo(){
                if(this.validarModelo()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                let me = this;
                //Con axios se llama el metodo update de FraccionaminetoController
                axios.put('/modelo/actualizar',{
                    'nombre': this.nombre,
                    'acabado': this.acabado,
                    'tipo': this.tipo,
                    'fraccionamiento_id': this.fraccionamiento_id,
                    'terreno': this.terreno,
                    'construccion': this.construccion,
                    'archivo': this.archivo,
                    'planta': this.planta,
                    'id' : this.id
                }).then(function (response){
                    me.cerrarModal();
                    me.listarModelo(1,'','modelo');
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
            eliminarFraccionamiento(data =[]){
                this.id=data['id'];
                this.nombre=data['nombre'];
                this.acabado=data['acabado'];
                this.tipo=data['tipo'];
                this.fraccionamiento_id=data['fraccionamiento_id'];
                this.terreno=data['terreno'];
                this.construccion=data['construccion'];
                this.archivo=data['archivo'];
                this.planta=data['planta'];
                //console.log(this.fraccionamiento_id);
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

                axios.delete('/modelo/eliminar', 
                        {params: {'id': this.id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Modelo borrado correctamente.',
                        'success'
                        )
                        me.listarModelo(1,'','modelo');
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            validarModelo(){
                this.errorModelo=0;
                this.errorMostrarMsjModelo=[];

                if(!this.nombre) //Si la variable Modelo esta vacia
                    this.errorMostrarMsjModelo.push("El nombre del Modelo no puede ir vacio.");

                if(this.errorMostrarMsjModelo.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorModelo = 1;

                return this.errorModelo;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.nombre = '';
                this.acabado = '';
                this.tipo = 0;
                this.fraccionamiento_id = 0;
                this.terreno = 0;
                this.construccion = 0;
                this.archivo = '';
                this.planta = '';
                this.errorModelo = 0;
                this.errorMostrarMsjModelo = [];

            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(modelo, accion,data =[]){
                switch(modelo){
                    case "modelo":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Modelo';
                                this.nombre = '';
                                this.acabado = '';
                                this.tipo = 0;
                                this.fraccionamiento_id = 0;
                                this.terreno = 0;
                                this.construccion = 0;
                                this.archivo = '';
                                this.planta = '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal =1;
                                this.tituloModal='Actualizar Modelo';
                                this.tipoAccion=2;
                                this.id=data['id'];
                                this.nombre=data['nombre'];
                                this.acabado=data['acabado'];
                                this.tipo=data['tipo'];
                                this.fraccionamiento_id=data['fraccionamiento_id'];
                                this.terreno=data['terreno'];
                                this.construccion=data['construccion'];
                                this.archivo=data['archivo'];
                                this.planta=data['planta'];
                                break;
                            }
                        }
                    }
                }
                //this.selectCiudades(this.estado);
            }
        },
        mounted() {
            this.listarModelo(1,this.buscar,this.criterio);
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
