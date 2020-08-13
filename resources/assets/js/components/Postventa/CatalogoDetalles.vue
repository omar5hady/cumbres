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
                        <i class="fa fa-align-justify"></i> Catalogo de Detalles 
                        <!--   Boton Nuevo    -->
                        <button type="button" @click="abrirModal('subconcepto','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-4" v-model="b_id_gral">
                                        <option value="">Detalle General</option>
                                        <option v-for="general in arrayGenerales" :key="general.id" :value="general.id" v-text="general.general"></option>
                                    </select>
                                    
                                    <input type="text" v-model="b_subconcepto" @keyup.enter="listarSubConcepto(1,b_id_gral,b_subconcepto)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarSubConcepto(1,b_id_gral,b_subconcepto)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Detalle General</th>
                                        <th>Subconcepto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="sub in arraySubConceptos" :key="sub.id">
                                        <td style="width:10%">
                                            <button type="button" @click="abrirModal('subconcepto','actualizar',sub)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-success2 btn-sm" @click="abrirModal2('detalle','registrar',sub), listarDetalle(1, sub.id)" title="Asignar detalle">
                                            <i class="icon-share"></i>
                                            </button>
                                        </td>
                                        <td style="width:20%" v-text="sub.general" ></td>
                                        <td v-text="sub.subconcepto" ></td>
                                    </tr>                               
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,b_id_gral,b_subconcepto)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,b_id_gral,b_subconcepto)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,b_id_gral,b_subconcepto)">Sig</a>
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
                                    <label class="col-md-3 form-control-label" for="text-input">Concepto General</label>
                                    <div class="col-md-7">
                                         <select class="form-control col-md-7" v-model="id_gral">
                                            <option value="">Seleccionar</option>
                                            <option v-for="general in arrayGenerales" :key="general.id" :value="general.id" v-text="general.general"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Sub concepto</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="subconcepto" class="form-control" placeholder="Supconcepto">
                                    </div>
                                </div>

                                <!-- Div para mostrar los errores que mande validerPaquete -->
                                <div v-show="errorSubconcepto" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjSubconcepto" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarSubConcepto()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarSubconcepto()">Actualizar</button>
                        </div>
                        
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->

            <!--Inicio del modal asignar detalles al subconcepto-->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal2}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal2"></h4>
                            <button type="button" class="close" @click="cerrarModal2()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Detalle</label>
                                    <div class="col-md-6">
                                       <input type="text" v-model="detalle" class="form-control" placeholder="Supconcepto">
                                    </div>
                                </div>
                                
                                <!-- Div para mostrar los errores que mande validerPaquete -->
                                <div v-show="errorDetalle" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjDetalle" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal2()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" class="btn btn-primary" @click="registrarDetalle()">Guardar</button>

                            </div>
                                <div class="modal-header" v-if="mostrar == 1">
                                    <h5 class="modal-title">Detalles</h5>
                                    <button type="button" @click="actualizar=1" v-if="actualizar==0" class="btn btn-warning btn-sm">
                                        <i class="icon-pencil"></i>
                                    </button>
                                </div>
                                <table class="table table-bordered table-striped table-sm" v-if="mostrar == 1">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Detalles</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="detalle in arrayDetalles" :key="detalle.id">
                                            <td style="width:10%">
                                                <button type="button" v-if="detalle.activo == 1" class="btn btn-success2 btn-sm" @click="eliminarDetalle(detalle)">
                                                <i class="fa fa-check"></i>
                                                </button>
                                                <button type="button" v-if="detalle.activo == 0" class="btn btn-danger btn-sm" @click="activarDetalle(detalle)">
                                                <i class="fa fa-remove"></i>
                                                </button>
                                            </td>
                                            <td v-if="actualizar == 0" v-text="detalle.detalle" ></td>
                                            <input v-else type="text" @keyup.enter="actualizarDetalle(detalle.id,$event.target.value)" :id="detalle.id" :value="detalle.detalle" class="form-control" >
                                        </tr>                               
                                    </tbody>
                                </table>
                                <nav>
                                    <!--Botones de paginacion -->
                                    <ul class="pagination">
                                        <li class="page-item" v-if="pagination2.current_page > 1">
                                            <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page - 1,id,criterio)">Ant</a>
                                        </li>
                                        <li class="page-item" v-for="page in pagesNumber2" :key="page" :class="[page == isActived2 ? 'active' : '']">
                                            <a class="page-link" href="#" @click.prevent="cambiarPagina2(page,id,criterio)" v-text="page"></a>
                                        </li>
                                        <li class="page-item" v-if="pagination2.current_page < pagination2.last_page">
                                            <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page + 1,id,criterio)">Sig</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                            
                        </div>
                    <!-- /.modal-content -->
                
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
                b_id_gral : '',
                subconcepto : '',
                detalle : '',
               
                arrayGenerales : [],
                arraySubConceptos : [],
                arrayDetalles : [],
               
                modal : 0,
                mostrar : 0,
                tituloModal : '',
                modal2 : 0,
                tituloModal2 : '',
                tipoAccion: 0,
                errorSubconcepto : 0,
                errorDetalle : 0,
                errorMostrarMsjSubconcepto : [],
                errorMostrarMsjDetalle : [],
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                 pagination2 : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3, 
                id_gral : '',
                b_subconcepto : '',
                id_sub : '',
                b_detalle : '',
                actualizar : 0,
                id_detalle : '',
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
            ////////////////////////////
            isActived2: function(){
                return this.pagination2.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber2:function(){
                if(!this.pagination2.to){
                    return [];
                }

                var from = this.pagination2.current_page - this.offset;
                if(from < 1){
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.pagination2.last_page){
                    to = this.pagination2.last_page;
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
            listarSubConcepto(page, id_gral, b_subconcepto){
                let me = this;
                var url = '/catalogoDetalle/indexSubCategoria?page=' + page + '&id_gral=' + id_gral + '&b_subconcepto=' + b_subconcepto;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arraySubConceptos = respuesta.subconceptos.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listarDetalle(page, id_sub){
                let me = this;
                me.equipamiento = '';
                me.actualizar = 0;
                var url = '/catalogoDetalle/indexDetalles?page=' + page + '&id_sub=' + id_sub;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayDetalles = respuesta.detalles.data;
                    me.pagination2 = respuesta.pagination;
                    if(me.arrayDetalles.length>0){
                        me.mostrar = 1;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectGeneral(){
                let me = this;
                me.arrayGenerales=[];
                var url = '/catalogoDetalle/selectGeneral';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayGenerales = respuesta.general;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page, id_gral, b_subconcepto){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarSubConcepto(page,id_gral,b_subconcepto);
            },
            cambiarPagina2(page, buscar){
                let me = this;
                //Actualiza la pagina actual
                me.pagination2.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarDetalle(page,buscar);
            },
            /**Metodo para registrar  */
            registrarSubConcepto(){
                if(this.validarSubConcepto() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;
                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/catalogoDetalle/storeSubconcepto',{
                    'id_gral': this.id_gral,
                    'subconcepto': this.subconcepto,
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Sub concepto agregado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            registrarDetalle(){
                if(this.validarDetalle() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }
                this.proceso=true;
                let me = this;
                //Con axios se llama el metodo update de DepartamentoController

               Swal({
                    title: 'Estas seguro?',
                    animation: false,
                    customClass: 'animated bounceInDown',
                    text: "El detalle se asignara a",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    
                    confirmButtonText: 'Si, asignar!'
                    }).then((result) => {

                    if (result.value) {
                        axios.post('/catalogoDetalle/storeDetalle',{
                            'id_sub': this.id_sub,
                            'detalle': this.detalle
                            }); 
                   // me.listarLote(1,'','','','','','','lote');   
                    me.proceso=false;
                    me.listarDetalle(1,me.id_sub);
                        if(me.arrayDetalles.length>0)
                            me.mostrar = 1;
                    Swal({
                        title: 'Hecho!',
                        text: 'Detalle asignada correctamente',
                        type: 'success',
                        animation: false,
                        customClass: 'animated bounceInRight'
                        })
                    }})
            },
            
            actualizarSubconcepto(){
                if(this.validarSubConcepto() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;

                let me = this;
                //Con axios se llama el metodo update de DepartamentoController
                axios.put('/catalogoDetalle/updateSubconcepto',{
                    'id_gral': this.id_gral,
                    'subconcepto': this.subconcepto,
                    'id': this.id,
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal();
                    
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
            actualizarDetalle(id, detalle){

                let me = this;
                //Con axios se llama el metodo update de DepartamentoController
                axios.put('/catalogoDetalle/updateDetalle',{
                    'id': id,
                    'detalle': detalle,
                }).then(function (response){
                    me.listarDetalle(1,me.id_sub);
                    
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
            eliminarDetalle(data =[]){
                this.id_detalle=data['id'];
                //console.log(this.departamento_id);
                swal({
                title: '¿Desea desactivar?',
                text: "Esta acción no se puede revertir!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si, desactivar!'
                }).then((result) => {
                if (result.value) {
                    let me = this;

                axios.put('/catalogoDetalle/desactivarDetalle', 
                         {'id': this.id_detalle}).then(function (response){
                        swal(
                        'Borrado!',
                        'Detalle desactivado correctamente.',
                        'success'
                        )
                        me.listarDetalle(1,me.id_sub);
                        if(me.arrayDetalles.length==0)
                            me.mostrar = 0;
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            activarDetalle(data =[]){
                this.id_detalle=data['id'];
                //console.log(this.departamento_id);
                swal({
                title: '¿Desea activar?',
                text: "Esta acción no se puede revertir!",
                type: 'success',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si, activar!'
                }).then((result) => {
                if (result.value) {
                    let me = this;

                axios.put('/catalogoDetalle/activarDetalle', 
                         {'id': this.id_detalle}).then(function (response){
                        swal(
                        'Activado!',
                        'Detalle activado correctamente.',
                        'success'
                        )
                        me.listarDetalle(1,me.id_sub);
                        if(me.arrayDetalles.length==0)
                            me.mostrar = 0;
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            validarSubConcepto(){
                this.errorSubconcepto=0;
                this.errorMostrarMsjSubconcepto=[];

                if(this.subconcepto=='') //Si la variable departamento esta vacia
                    this.errorMostrarMsjSubconcepto.push("Escribir el subconcepto.");
                
                if(this.id_gral=='') //Si la variable departamento esta vacia
                    this.errorMostrarMsjSubconcepto.push("Seleccionar el detalle general.");

                if(this.errorMostrarMsjSubconcepto.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorSubconcepto = 1;

                return this.errorSubconcepto;
            },
            validarDetalle(){
                this.errorDetalle=0;
                this.errorMostrarMsjDetalle=[];

                if(this.detalle =='') //Si la variable departamento esta vacia
                    this.errorMostrarMsjDetalle.push("Escribir el detalle especifico");
                
                if(this.errorMostrarMsjDetalle.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorDetalle = 1;

                return this.errorDetalle;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.id_gral = '';
                this.subconcepto = '';
                
                this.errorSubconcepto = 0;
                this.errorMostrarMsjSubconcepto = [];
                this.listarSubConcepto(this.pagination.current_page,this.b_id_gral,this.b_subconcepto);

            },
            cerrarModal2(){
                this.modal2 = 0;
                this.tituloModal2 = '';
                this.equipamiento = '';
                this.errorDetalle = 0;
                this.errorMostrarMsjDetalle = [];
                this.mostrar = 0;
                this.actualizar = 0;
                this.listarSubConcepto(this.pagination.current_page,this.b_id_gral,this.b_subconcepto);

            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(modelo, accion,data =[]){
                switch(modelo){
                    case "subconcepto":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar subconcepto';
                                this.tipoAccion = 1;
                                this.id_gral = '';
                                this.subconcepto = '';
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal =1;
                                this.tituloModal='Actualizar proveedor';
                                this.tipoAccion=2;
                                this.id_gral = data['id_gral'];
                                this.subconcepto = data['subconcepto'];
                                this.id = data['id'];
                                break;
                            }
                        }
                    }
                }

            },
            abrirModal2(modelo, accion,data =[]){
                switch(modelo){
                    case "detalle":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal2 = 1;
                                this.tituloModal2 = 'Asignar detalle a: ' + data['subconcepto'];
                                this.detalle = '';
                                this.id_sub = data['id'];
                                break;
                            }
                        }
                    }
                }

            }
        },
        mounted() {
            this.listarSubConcepto(1,this.b_id_gral,this.b_detalle);
            this.selectGeneral();
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
        overflow-y: auto;
    }
    .div-error{
        display:flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }    
    .btn-success2 {
        color: #fff;
        background-color: #2c309e;
        border-color: #313a98;
    }
    .btn-success2:active, .btn-success2.active, .show > .btn-success2.dropdown-toggle {
        background-color: #2c309e;
        background-image: none;
        border-color: #313a98;
    }
    .btn-success2:focus, .btn-success2.focus {
        box-shadow: 0 0 0 3px rgba(77, 100, 189, 0.5);
    }
    .btn-success2:hover {
        color: #fff;
        background-color: #2c309e;
        border-color: #313a98;
    }
</style>
