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
                        <i class="fa fa-align-justify"></i> Etapas
                        <!--   Boton Nuevo    -->
                        <button type="button" @click="abrirModal('etapa','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                        <!---->
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
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Fraccionamiento</th>
                                    <th>Numero de etapa</th>
                                    <th>Fecha de inicio </th>
                                    <th>Fecha de termino</th>
                                    <th>Encargado</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="etapa in arrayEtapa" :key="etapa.id">
                                    <td>
                                        <button type="button" @click="abrirModal('etapa','actualizar',etapa)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <button type="button" class="btn btn-danger btn-sm" @click="eliminarEtapa(etapa)">
                                          <i class="icon-trash"></i>
                                        </button>
                                    </td>
                                    <td v-text="etapa.fraccionamiento"></td>
                                    <td v-text="etapa.num_etapa"></td>
                                    <td v-text="etapa.f_ini"></td>
                                    <td v-text="etapa.f_fin"></td>
                                    <td v-text="etapa.name"></td>
                                </tr>                               
                            </tbody>
                        </table>
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
                                    <label class="col-md-3 form-control-label" for="text-input">Fraccionamientos</label>
                                    <div class="col-md-6">
                                       <select class="form-control" @click="selectContador(fraccionamiento_id)" v-model="fraccionamiento_id">
                                            <option value="0">Seleccione</option>
                                            <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Numero de etapa</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="num_etapa" class="form-control" placeholder="# de etapa">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de inicio</label>
                                    <div class="col-md-6">
                                        <input type="date" v-model="f_ini"  class="form-control" placeholder="Fecha de inicio">
                                    </div>
                                </div>
                                   <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de terminacion</label>
                                    <div class="col-md-6">
                                        <input type="date" v-model="f_fin" class="form-control" placeholder="Fecha de terminacion">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Directivo</label>
                                    <div class="col-md-6">
                                        <select class="form-control" v-model="personal_id">
                                            <option value="0">Seleccione</option>
                                            <option v-for="directivos in arrayDirectores" :key="directivos.id" :value="directivos.id" v-text="directivos.name"></option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Div para mostrar los errores que mande validerFraccionamiento -->
                                <div v-show="errorEtapa" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjEtapa" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarEtapa()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarEtapa()">Actualizar</button>
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
                contador : 0,
                fraccionamiento_id : 0,
                num_etapa : 0,
                f_ini : new Date().toISOString().substr(0, 10),
                f_fin : '',
                personal_id : 0,
                arrayEtapa : [],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                errorEtapa : 0,
                errorMostrarMsjEtapa : [],
                pagination : {
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
                buscar2: '',
                arrayFraccionamientos : [],
                arrayDirectores : []
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
            selectContador(fraccionamiento_id){
                let me = this;
                me.contador=0;
                var url = '/contador_etapa?fraccionamiento_id='+fraccionamiento_id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.contador = respuesta;
                    me.num_etapa = me.contador;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectDirectivos(){
                let me = this;
                me.arrayDirectivos=[];
                var url = '/select_personal?departamento_id=1';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayDirectores = respuesta.personal;
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
            /**Metodo para registrar  */
            registrarEtapa(){
                if(this.validarEtapa()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/etapa/registrar',{
                    'fraccionamiento_id': this.fraccionamiento_id,
                    'num_etapa': this.num_etapa,
                    'f_ini': this.f_ini,
                    'f_fin': this.f_fin,
                    'personal_id': this.personal_id
                }).then(function (response){
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarEtapa(1,'','','etapa'); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Etapa agregada correctamente',
                        showConfirmButton: false,
                        timer: 1500
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
            actualizarEtapa(){
                if(this.validarEtapa()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                let me = this;
                //Con axios se llama el metodo update de FraccionaminetoController
                axios.put('/etapa/actualizar',{
                    'id' : this.id,
                    'fraccionamiento_id': this.fraccionamiento_id,
                    'num_etapa': this.num_etapa,
                    'f_ini': this.f_ini,
                    'f_fin': this.f_fin,
                    'personal_id': this.personal_id
                }).then(function (response){
                    me.cerrarModal();
                    me.listarEtapa(1,'','','etapa');
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
            eliminarEtapa(data =[]){
                this.id=data['id'];
                this.fraccionamiento_id=data['fraccionamiento_id'];
                this.num_etapa=data['num_etapa'];
                this.f_ini=data['f_ini'];
                this.f_fin=data['f_fin'];
                this.personal_id=data['personal_id']
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

                axios.delete('/etapa/eliminar', 
                        {params: {'id': this.id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Etapa borrada correctamente.',
                        'success'
                        )
                        me.listarEtapa(1,'','','etapa');
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            validarEtapa(){
                this.errorEtapa=0;
                this.errorMostrarMsjEtapa=[];

                if(!this.num_etapa) //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjEtapa.push("El numero de etapa no puede ir vacio.");

                

                if(this.errorMostrarMsjEtapa.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorEtapa = 1;

                return this.errorEtapa;
            },

            // isNumber: function(evt) {
            //     evt = (evt) ? evt : window.event;
            //     var charCode = (evt.which) ? evt.which : evt.keyCode;
            //     if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
            //         evt.preventDefault();;
            //     } else {
            //         return true;
            //     }
            // },

            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.fraccionamiento_id = '';
                this.num_etapa = '';
                this.f_ini = new Date().toISOString().substr(0, 10);
                this.f_fin = '';
                this.personal_id = '';
                this.errorEtapa = 0;
                this.errorMostrarMsjEtapa = [];
                this.contador=0;

            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(modelo, accion,data =[]){
                switch(modelo){
                    case "etapa":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Etapa';
                                this.fraccionamiento_id = '0';
                                this.num_etapa = this.contador;
                                // this.f_ini = '';
                                this.f_fin = '';
                                this.personal_id = '0';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal =1;
                                this.tituloModal='Actualizar Etapa';
                                this.tipoAccion=2;
                                this.id=data['id'];
                                this.fraccionamiento_id=data['fraccionamiento_id'];
                                this.num_etapa=data['num_etapa'];
                                this.f_ini=data['f_ini'];
                                this.f_fin=data['f_fin'];
                                this.personal_id=data['personal_id'];
                                break;
                            }
                        }
                    }
                }
                this.selectFraccionamientos();
                this.selectDirectivos();
            }
        },
        mounted() {
            this.listarEtapa(1,this.buscar,this.buscar2,this.criterio);
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
    .scroll-box {
            overflow-x: scroll;
            width: auto;
            padding: 1rem
        }
</style>
