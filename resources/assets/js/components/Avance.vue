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
                        <i class="fa fa-align-justify"></i> Avance
                        <!--   Boton Nuevo    -->
                        <button v-if="resumen==0" type="button" @click="MostrarPromedio()" class="btn btn-success">
                            <i class="icon-arrow-left"></i> Resumen de avances
                        </button>
                        <!---->
                    </div>
                    <div class="card-body" v-if="resumen==0">
                        
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Fraccionamiento</th>
                                    <th>Modelo</th>
                                    <th>Manzana</th>
                                    <th>Lote</th>
                                    <th>Partida</th>
                                    <th>Avance</th>
                                    <th>Porcentaje</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="avance in arrayAvance" :key="avance.id">
                                    <td style="width:9%">
                                        <button type="button" @click="abrirModal('avance','actualizar',avance)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <button type="button" class="btn btn-danger btn-sm" @click="eliminarAvance(avance)">
                                          <i class="icon-trash"></i>
                                        </button>
                                    </td>
                                    <td v-text="avance.proyecto"></td>
                                    <td v-text="avance.modelos"></td>
                                    <td v-text="avance.manzana"></td>
                                    <td v-text="avance.lote"></td>
                                    <td v-text="avance.partida" style="width:30%"></td>
                                     <td style="width:8%">
                                     <input v-if="avance.cambio_avance == 1"  type="number" @keyup.enter="actualizarPorcentaje(avance.id,$event.target.value,avance.partida_id,avance.lote)" :id="avance.id" :value="avance.avance" step=".1" min="0" max="1" v-on:keypress="isNumber($event)" class="form-control Fields" > 
                                        <input v-else type="number" @keyup.enter="actualizarPorcentaje(avance.id,$event.target.value,avance.partida_id,avance.lote)" :id="avance.id" :value="avance.avance" step=".1" min="0" max="1" v-on:keypress="isNumber($event)" class="form-control" >
                                    </td>
                                    <td v-text="formatNumber(avance.avance_porcentaje) + '%'"></td>

                                    
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

                    <div class="card-body" v-if="resumen==1">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-4" v-model="criterio" @click="selectFraccionamientosConLote()">
                                      <option value="lotes.fraccionamiento_id">Fraccionamiento</option>
                                      <option value="modelos.nombre">Modelo</option>
                                    </select>
                                     <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar" >
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientosLote" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>
                                    <input v-if="criterio=='lotes.fraccionamiento_id'" type="text" v-model="buscar1" @keyup.enter="listarAvancePromedio(1,buscar,buscar1,buscar2,criterio)" class="form-control" placeholder="Manzana">
                                    <input v-if="criterio=='lotes.fraccionamiento_id'" type="text" v-model="buscar2" @keyup.enter="listarAvancePromedio(1,buscar,buscar1,buscar2,criterio)" class="form-control" placeholder="Lote">
                                    <input v-else type="text" v-model="buscar" @keyup.enter="listarAvancePromedio(1,buscar,buscar1,buscar2,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarAvancePromedio(1,buscar,buscar1,buscar2,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Fraccionamiento</th>
                                    <th>Modelo</th>
                                    <th>Manzana</th>
                                    <th>Lote</th>
                                    <th>Porcentaje de avance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="avancepro in arrayAvanceProm" :key="avancepro.lote_id">
                                    <td style="width:9%">
                                        <button type="button" class="btn btn-primary btn-sm" @click="mostrarPartidas(avancepro.lote_id)">
                                          <i class="icon-eye"></i>
                                        </button>
                                    </td>
                                    <td v-text="avancepro.proyecto"></td>
                                    <td v-text="avancepro.modelos"></td>
                                    <td v-text="avancepro.manzana"></td>
                                    <td v-text="avancepro.lote"></td>
                                    <td v-text="formatNumber(avancepro.porcentajeTotal) + '%'"></td>

                                    
                                </tr>                               
                            </tbody>
                        </table>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="paginationResume.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(paginationResume.current_page - 1,buscar,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber2" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(page,buscar,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="paginationResume.current_page < paginationResume.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(paginationResume.current_page + 1,buscar,criterio)">Sig</a>
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
                                        <input type="text" disabled v-model="fraccionamiento"  class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Modelo</label>
                                    <div class="col-md-6">
                                        <input type="text" disabled v-model="modelos"  class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Partida</label>
                                    <div class="col-md-9">
                                        <input type="text" disabled v-model="partida" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Avance</label>
                                    <div class="col-md-2">
                                        <input type="number" step="0.1" min="0" max="1" onkeydown="return false" v-model="avance" maxlength="3" v-on:keypress="isNumber($event)" class="form-control" placeholder="Costo de la Partida">
                                    </div>
                                </div>

                                <!-- Div para mostrar los errores que mande validerDepartamento -->
                                <div v-show="erroPartida" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjAvance" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarAvance()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarAvance()">Actualizar</button>
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
                resumen:1,
                avance: '',
                avance_porcentaje:'',
                lote_id:'',
                partida : '',
                costo : 0,
                costos : [],
                porcentaje : 0,
                modelo_id : 0,
                fraccionamiento_id:0,
                fraccionamiento:'',
                modelos:'',
                arrayAvance : [],
                arrayAvanceProm : [],
                arrayFraccionamientosLote: [],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                erroPartida : 0,
                errorMostrarMsjAvance : [],
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                paginationResume : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'lotes.fraccionamiento_id', 
                buscar : '',
                buscar1: '',
                buscar2: '',
                buscar3: ''
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
            isActived2: function(){
                return this.paginationResume.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber2:function(){
                if(!this.paginationResume.to){
                    return [];
                }

                var from = this.paginationResume.current_page - this.offset;
                if(from < 1){
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.paginationResume.last_page){
                    to = this.paginationResume.last_page;
                }

                var pagesArray2 = [];
                while(from <= to){
                    pagesArray2.push(from);
                    from++;
                }
                return pagesArray2;
            }
        },
        methods : {
            /**Metodo para mostrar los registros */
            listarAvance(page, buscar, criterio){
                let me = this;
                var url = '/avance?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayAvance = respuesta.avance.data;
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
                me.listarAvance(page,buscar,criterio);
            },
            /**Metodo para mostrar los registros */
            listarAvancePromedio(page, buscar, buscar1, buscar2 ,criterio){
                let me = this;
                var url = '/avanceProm?page=' + page + '&buscar=' + buscar +  '&buscar1=' + buscar1 + '&buscar2=' + buscar2 + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayAvanceProm = respuesta.avance.data;
                    me.paginationResume = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina2(page, buscar, criterio){
                let me = this;
                //Actualiza la pagina actual
                me.paginationResume.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarAvance(page,buscar,criterio);
            },
            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            },
            selectFraccionamientosConLote(){
                let me = this;
                me.buscar1="";
                me.buscar2="";
                me.buscar="";
                me.arrayFraccionamientosLote=[];
                var url = '/select_fraccionamientoLote';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayFraccionamientosLote = respuesta.fraccionamientos;
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
            mostrarPartidas(lote){
                let me = this;
                console.log(lote)
                me.listarAvance(1,lote,'avances.lote_id');
                me.resumen=0;
            },
            MostrarPromedio(){
                let me = this;
                me.resumen=1;
                me.listarAvancePromedio(1,'','','','lotes.num_lote');
            },
            actualizarAvance(){
                if(this.validarAvance()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                let me = this;
                //Con axios se llama el metodo update de PartidaController
                axios.put('/avance/actualizar',{
                    'avance': this.avance,
                    'partida': this.partida,
                    'id' : this.id
                }).then(function (response){
                    me.cerrarModal();
                    me.listarAvance(1,'','fraccionamientos.nombre');
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
            actualizarPorcentaje(id,avance,partida,lote){
                let me = this;
                //Con axios se llama el metodo update de PartidaController
                if(avance>1)
                    avance=1;
                axios.put('/avance/actualizar',{
                    'avance':avance,
                    'partida_id': partida,
                    'id' : id
                }).then(function (response){
                    
                    me.listarAvance(1,'','lotes.num_lote');
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
            eliminarAvance(data =[]){
                this.id=data['id'];

                this.avance=data['avance'];
                this.modelo_id=data['modelo_id'];
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

                axios.delete('/avance/eliminar', 
                        {params: {'id': this.id,'modelo_id':this.modelo_id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Partida borrada correctamente.',
                        'success'
                        )
                        me.listarAvance(1,'','fraccionamientos.nombre');
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            validarAvance(){
                this.erroAvance=0;
                this.errorMostrarMsjAvance=[];

                if(!this.partida) //Si la variable departamento esta vacia
                    this.errorMostrarMsjAvance.push("El nombre de la partida no puede ir vacio.");

                if(this.errorMostrarMsjAvance.length)//Si el mensaje tiene almacenado algo en el array
                    this.erroAvance = 1;

                return this.erroAvance;
            },
           
         
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.partida = '';
                this.modelo_id = 0;
                this.costo=0;
                this.fraccionamiento_id=0;
                this.erroAvance = 0;
                this.errorMostrarMsjAvance = [];

            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(modelo, accion,data =[]){
                switch(modelo){
                    case "avance":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Avance';
                                this.avance=0;
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal =1;
                                this.tituloModal='Actualizar Partida';
                                this.tipoAccion=2;
                                this.id=data['id'];
                                this.modelos=data['modelo_id'];
                                this.fraccionamiento=data['fraccionamiento_id'];
                                this.partida=data['partida'];
                                this.avance = data['avance'];
                                break;
                            }
                        }
                    }
                }
                this.selectFraccionamientosConLote();
                
               
            }
        },
        mounted() {
            this.listarAvance(1,this.buscar,this.criterio);      
            this.listarAvancePromedio(1,this.buscar,this.buscar1,this.buscar2,this.criterio);
            this.selectFraccionamientosConLote();
            
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
    .scroll-box {
            overflow-x: scroll;
            width: auto;
            padding: 1rem;
            
        }
        .Fields {
	/*background-color: #f44242;*/
	border: 2px solid #f40404;

}
</style>
