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
                        <i class="fa fa-align-justify"></i> Bonos para recomendados
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
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control" v-model="buscar" @click="selectEtapa(buscar), b_etapa=''">
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>

                                    <select class="form-control" v-model="b_etapa" @keyup.enter="listarCatalogo(1)"> 
                                        <option value="">Etapa</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                    </select>

                                    <button type="submit" @click="listarCatalogo(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th colspan="3"></th>
                                        <th colspan="2" class="text-center">Periodo</th>
                                        <th colspan="3"></th>
                                    </tr>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Proyecto</th>
                                        <th>Etapa</th>
                                        <th>Inicio</th>
                                        <th>Fin</th>
                                        <th>Descripcion</th>
                                        <th>Monto $</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="paquete in arrayCatalogo" :key="paquete.id">
                                        <td class="td2" style="width:10%">
                                            <button type="button" @click="abrirModal('actualizar',paquete)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                            </button> &nbsp;
                                            <button type="button" class="btn btn-danger btn-sm" @click="eliminar(paquete)">
                                            <i class="icon-trash"></i>
                                            </button>
                                        </td>
                                        <td class="td2" v-text="paquete.proyecto" ></td>
                                        <td class="td2" v-text="paquete.num_etapa" ></td>
                                        <td class="td2" v-text="this.moment(paquete.fecha_ini).locale('es').format('DD/MMM/YYYY')" ></td>
                                        <td class="td2" v-text="this.moment(paquete.fecha_fin).locale('es').format('DD/MMM/YYYY')" ></td>
                                        <td v-text="paquete.descripcion" ></td>
                                        <td class="td2" v-text="'$'+formatNumber(paquete.monto)" ></td>
                                        <td class="td2" v-if="paquete.is_active == '1'">
                                            <span class="badge badge-success">Activo</span>
                                        </td>
                                        <td class="td2" v-if="paquete.is_active == '0'">
                                            <span class="badge badge-danger">Desactivado</span>
                                        </td>
                                    </tr>                               
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1)">Sig</a>
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
                                    <label class="col-md-3 form-control-label" for="text-input">Proyecto</label>
                                    <div class="col-md-6">
                                       <select class="form-control" v-model="fraccionamiento_id" @click="selectEtapa(fraccionamiento_id)" >
                                            <option value="">Seleccione</option>
                                            <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Etapa</label>
                                    <div class="col-md-6">
                                       <select class="form-control" v-model="etapa_id">
                                            <option value="">Seleccione</option>
                                            <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de inicio</label>
                                    <div class="col-md-6">
                                        <input type="date" v-model="fecha_ini"  class="form-control" placeholder="Inicio">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de vencimiento</label>
                                    <div class="col-md-6">
                                        <input type="date" v-model="fecha_fin" class="form-control" placeholder="Vencimiento">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Monto $</label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="monto" maxlength="10" v-on:keypress="isNumber($event)" class="form-control" placeholder="Monto del bono">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Descripcion</label>
                                    <div class="col-md-6">
                                        <textarea rows="5" cols="30" v-model="descripcion" class="form-control" placeholder="Descripcion del bono"></textarea>
                                    </div>
                                </div>


                                <!-- Div para mostrar los errores que mande validerPaquete -->
                                <div v-show="errorCatalogo" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjCatalogo" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarcatalogo()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarcatalogo()">Actualizar</button>
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
                fraccionamiento_id:0,
                etapa_id : 0,
                fecha_ini : new Date().toISOString().substr(0, 10),
                fecha_fin : '',
                monto : '',
                descripcion : '',
                arrayCatalogo : [],
                arrayFraccionamientos: [],
                arrayEtapas: [],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                errorCatalogo : 0,
                errorMostrarMsjCatalogo : [],
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
                b_etapa: ''
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
            listarCatalogo(page){
                let me = this;
                var url = '/cat_bono/index?page=' + page + '&buscar=' + me.buscar + '&b_etapa=' + me.b_etapa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayCatalogo = respuesta.catalogo.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarCatalogo(page);
            },
            /**Metodo para registrar  */
            registrarcatalogo(){
                if(this.validarcatalogo() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }
                this.proceso=true;

                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/cat_bono/store',{
                    'fecha_ini': this.fecha_ini,
                    'fecha_fin' : this.fecha_fin,
                    'descripcion' : this.descripcion,
                    'monto' : this.monto,
                    'etapa_id' : this.etapa_id
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarCatalogo(1); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Bono asignado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "'")
            },
            actualizarcatalogo(){
                if(this.validarcatalogo() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;

                let me = this;
                //Con axios se llama el metodo update de DepartamentoController
                axios.put('/cat_bono/update',{
                    'fecha_ini': this.fecha_ini,
                    'fecha_fin' : this.fecha_fin,
                    'descripcion' : this.descripcion,
                    'monto' : this.monto,
                    'etapa_id' : this.etapa_id,
                    'id': this.id
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal();
                    me.listarCatalogo(1);
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
            eliminar(data =[]){
                this.id=data['id'];
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

                axios.delete('/cat_bono/delete', 
                        {params: {'id': this.id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Bono borrado correctamente.',
                        'success'
                        )
                        me.listarCatalogo(1);
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            selectFraccionamientos(){
                let me = this;
                me.b_etapa = '';

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
            validarcatalogo(){
                this.errorCatalogo=0;
                this.errorMostrarMsjCatalogo=[];

                if(!this.fraccionamiento_id) //Si la variable departamento esta vacia
                    this.errorMostrarMsjCatalogo.push("Seleccionar fraccionamiento.");

                if(!this.etapa_id) //Si la variable departamento esta vacia
                    this.errorMostrarMsjCatalogo.push("Seleccionar etapa");

                if(!this.monto) //Si la variable departamento esta vacia
                    this.errorMostrarMsjCatalogo.push("El monto del paquete no puede ir vacio.");
                
                if(!this.descripcion) //Si la variable departamento esta vacia
                    this.errorMostrarMsjCatalogo.push("La descripción del paquete no puede ir vacia.");

                if(this.errorMostrarMsjCatalogo.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorCatalogo = 1;

                return this.errorCatalogo;
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
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.fraccionamiento_id = '';
                this.etapa_id = '';
                this.fecha_ini = new Date().toISOString().substr(0, 10);
                this.fecha_fin = '';
                this.monto = '';
                this.descripcion = '';
                this.errorCatalogo = 0;
                this.errorMostrarMsjCatalogo = [];
                this.proceso=false;

            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(accion,data =[]){
                switch(accion){
                    case 'registrar':
                    {
                        this.modal = 1;
                        this.tituloModal = 'Registrar detalle del bono';
                        this.fraccionamiento_id ='';
                        this.etapa_id ='';
                        // this.fecha_ini ='';
                        this.fecha_fin = '';
                        this.monto = 0;
                        this.descripcion = '';
                        this.tipoAccion = 1;
                        break;
                    }
                    case 'actualizar':
                    {
                        //console.log(data);
                        this.modal =1;
                        this.tituloModal='Actualizar detalle del bono';
                        this.tipoAccion=2;
                        this.id=data['id'];
                        this.fraccionamiento_id=data['fraccionamiento_id'];
                        this.etapa_id=data['etapa_id'];
                        this.fecha_ini=data['fecha_ini'];
                        this.fecha_fin=data['fecha_fin'];
                        this.monto=data['monto'];
                        this.descripcion=data['descripcion'];
                        break;
                    }
                }

                this.selectFraccionamientos();
                this.selectEtapa(this.fraccionamiento_id);
            }
        },
        mounted() {
            this.listarCatalogo(1);
            this.selectFraccionamientos();
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
