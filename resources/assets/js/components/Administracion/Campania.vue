<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Campañas publicitarias
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
                                    <input type="text" v-model="buscar" @keyup.enter="listarCampania(1)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarCampania(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Campaña</th>
                                    <th>Medio digital</th>
                                    <th>Inicio</th>
                                    <th>Termino</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="campania in arrayCampanias.data" :key="campania.id">
                                    <td style="width:15%">
                                        <button title="Editar" type="button" @click="abrirModal('actualizar',campania)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                        </button>  
                                        <button type="button" class="btn btn-danger btn-sm" @click="eliminarCampania(campania)">
                                            <i class="icon-trash"></i>
                                        </button>                                       
                                    </td>
                                    <td v-text="campania.nombre_campania"></td>
                                    <td v-text="campania.medio_digital"></td>
                                    <td v-text="this.moment(campania.fecha_ini).locale('es').format('DD/MMM/YYYY')"></td>
                                    <td v-text="this.moment(campania.fecha_fin).locale('es').format('DD/MMM/YYYY')"></td>
                                </tr>                                
                            </tbody>
                        </table>
                        </div>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item">
                                            <a class="page-link" href="#" @click="listarCampania(1)">Inicio</a>
                                        </li>
                                        <li v-if="arrayCampanias.current_page-3 >= 1">
                                            <a class="page-link" href="#" 
                                            @click="listarCampania(arrayCampanias.current_page-3)" 
                                            v-text="arrayCampanias.current_page-3" ></a>
                                        </li>
                                        <li v-if="arrayCampanias.current_page-2 >= 1">
                                            <a class="page-link" href="#" 
                                            @click="listarCampania(arrayCampanias.current_page-2)" 
                                            v-text="arrayCampanias.current_page-2" ></a>
                                        </li>
                                        <li v-if="arrayCampanias.current_page-1 >= 1">
                                            <a class="page-link" href="#" 
                                            @click="listarCampania(arrayCampanias.current_page-1)" 
                                            v-text="arrayCampanias.current_page-1" ></a>
                                        </li>
                                        
                                        <li class="page-item active">
                                            <a class="page-link" href="#" v-text="arrayCampanias.current_page" ></a>
                                        </li>
                                        
                                        <li v-if="arrayCampanias.current_page+1 <= arrayCampanias.last_page">
                                            <a class="page-link" href="#" 
                                            @click="listarCampania(arrayCampanias.current_page+1)" 
                                            v-text="arrayCampanias.current_page+1" ></a>
                                        </li>
                                        <li v-if="arrayCampanias.current_page+2 <= arrayCampanias.last_page">
                                            <a class="page-link" href="#" 
                                            @click="listarCampania(arrayCampanias.current_page+2)" 
                                            v-text="arrayCampanias.current_page+2" ></a>
                                        </li>
                                        <li v-if="arrayCampanias.current_page+3 <= arrayCampanias.last_page">
                                            <a class="page-link" href="#" 
                                            @click="listarCampania(arrayCampanias.current_page+3)" 
                                            v-text="arrayCampanias.current_page+3" ></a>
                                        </li>
                                        
                                        <li class="page-item">
                                            <a class="page-link" href="#" @click="listarCampania(arrayCampanias.last_page)">Ultimo</a>
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
                            <!--<form action="" method="" enctype="multipart/form-data" class="form-horizontal">-->
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre de la campaña</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Campaña">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de inicio</label>
                                    <div class="col-md-3">
                                        <input type="date" v-model="fecha_ini" class="form-control" placeholder="Fecha de inicio">
                                    </div>
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de finalización</label>
                                    <div class="col-md-3">
                                        <input type="date" v-model="fecha_fin" class="form-control" placeholder="Fecha de inicio">
                                    </div>
                                </div>
                                
                                <template v-if="tipoAccion==1">
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Medio publicitario</label>
                                        <div class="col-md-6">
                                            <input type="text" name="city" list="cityname" class="form-control" v-model="medio_digital" @keyup.enter="addMedio(medio_digital)" placeholder="Medio de publicidad">
                                            <datalist id="cityname">
                                                <option value="">Seleccione</option>
                                                <option v-for="medios in arrayMediosPublicidad" :key="medios.id" :value="medios.nombre" v-text="medios.nombre"></option>
                                            </datalist>
                                        </div>
                                        <div class="col-md-3">
                                            <button type="button" class="btn btn-success btn-sm" @click="addMedio(medio_digital)">
                                                <i class="icon-plus"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="modal-header" v-if="medios">
                                            <h5 class="modal-title"> Medios elegidos </h5>
                                        </div>
                                        <table class="table table-bordered table-striped table-sm">
                                            
                                            <tbody>
                                                <tr v-for="(medioD,index) in medios" :key="medioD">
                                                    <td style="width:25%">
                                                        <button type="button" class="btn btn-danger btn-sm" @click="quitarMedio(index)">
                                                        <i class="icon-trash"></i>
                                                        </button>
                                                    </td>
                                                    <td v-text="medioD" ></td>
                                                </tr>                               
                                            </tbody>
                                        </table>
                                    </div>
                                </template>

                                <template v-if="tipoAccion==2">
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Medio publicitario</label>
                                        <div class="col-md-6">
                                            <input type="text" name="city" list="cityname" class="form-control" v-model="medio_digital" laceholder="Medio de publicidad">
                                            <datalist id="cityname">
                                                <option value="">Seleccione</option>
                                                <option v-for="medios in arrayMediosPublicidad" :key="medios.id" :value="medios.nombre" v-text="medios.nombre"></option>
                                            </datalist>
                                        </div>
                                    </div>
                                </template>

                                
                                <div v-show="errorCampania" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsj" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                           <!-- </form>-->
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarCampania()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarCampania()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
        </main>
</template>

<script>
    export default {
        data (){
            return {
                proceso:false,
                id: 0,
                nombre : '',
                medio_digital:'',
                fecha_ini:'',
                fecha_fin:'',
                arrayCampanias : [],
                medios:[],
                arrayMediosPublicidad:[],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                errorCampania : 0,
                errorMostrarMsj : [],
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
                buscar : ''
            }
        },
        computed:{
            
        },
        methods : {
            listarCampania (page){
                axios.get('/campanias/index'+
                    '?buscar='+this.buscar+
                    '&page='+page
                    
                ).then(
                    response => this.arrayCampanias = response.data
                ).catch(error => console.log(error));
            },
           
            addMedio(medio){
                this.medios.push(medio);
            },
            quitarMedio(index){
                this.medios.splice(index,1);
            },
            /**Metodo para registrar  */
            registrarCampania(){
                if(this.validarMedio() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }
                this.proceso=true;

                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/campanias/store',{
                    'nombre': this.nombre,
                    'medio_digital':this.medios,
                    'fecha_ini':this.fecha_ini,
                    'fecha_fin':this.fecha_fin
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarCampania(1); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Campaña guardada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            selectMedioPublicidad(){
                let me = this;
                me.arrayMediosPublicidad=[];
                var url = '/select_medio_publicidad';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayMediosPublicidad = respuesta.medios_publicitarios;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            actualizarCampania(){
                

                let me = this;
                //Con axios se llama el metodo update de DepartamentoController
                axios.put('/campanias/update',{
                    'nombre': this.nombre,
                    'medio_digital':this.medio_digital,
                    'fecha_ini':this.fecha_ini,
                    'fecha_fin':this.fecha_fin,
                    'id' : this.id
                }).then(function (response){
                    
                    me.cerrarModal();
                    me.listarCampania(1); //se enlistan nuevamente los registros
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
            eliminarCampania(data =[]){
                this.id=data['id'];
                this.nombre=data['nombre'];
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

                axios.delete('/campanias/delete', 
                        {params: {'id': this.id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Campaña eliminada correctamente.',
                        'success'
                        )
                        me.listarCampania(1); //se enlistan nuevamente los registros
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            validarMedio(){
                this.errorCampania=0;
                this.errorMostrarMsj=[];

                if(!this.nombre) //Si la variable departamento esta vacia
                    this.errorMostrarMsj.push("El nombre de la campaña no puede ir vacio.");

                if(this.medios.length == 0) //Si la variable departamento esta vacia
                    this.errorMostrarMsj.push("Elegir un medio digital.");

                if(!this.fecha_ini) //Si la variable departamento esta vacia
                    this.errorMostrarMsj.push("Registrar la fecha de inico de la campaña.");

                if(!this.nombre) //Si la variable departamento esta vacia
                    this.errorMostrarMsj.push("Registrar la fecha de finalizacion de la campaña.");

                if(this.errorMostrarMsj.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorCampania = 1;

                return this.errorCampania;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.nombre = '';
                this.errorCampania = 0;
                this.errorMostrarMsj = [];

            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(accion,data =[]){
                this.selectMedioPublicidad();
                switch(accion){
                    case 'registrar':
                    {
                        this.modal = 1;
                        this.tituloModal = 'Registrar Campaña';
                        this.fecha_ini ='';
                        this.fecha_fin ='';
                        this.nombre = '';
                        this.medio_digital = '';
                        this.tipoAccion = 1;
                        this.medios = [];
                        break;
                    }
                    case 'actualizar':
                    {
                        //console.log(data);
                        this.modal =1;
                        this.tituloModal='Actualizar Campaña';
                        this.tipoAccion=2;
                        this.id=data['id'];
                        this.fecha_ini =data['fecha_ini'];
                        this.fecha_fin =data['fecha_fin'];
                        this.nombre = data['nombre_campania'];
                        this.medio_digital = data['medio_digital'];
                        break;
                    }
                }
            }
        },
        mounted() {
            this.listarCampania(1);
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
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>
