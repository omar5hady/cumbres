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
                        <i class="fa fa-align-justify"></i> Inicio de obra
                        <!--   Boton Nuevo    -->
                        <button type="button" @click="abrirModal('iniobra','registrar')" class="btn btn-secondary">
                            <i class="icon-envelope-letter"></i> Enviar una solicitud de inicio de obra
                        </button>
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-5" v-model="criterio">
                                      <option value="nombre">Empresa</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarEmpresa(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarEmpresa(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <!--Tabla CRUD para mostrar los datos -->
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Fraccionamiento</th>
                                    <th>Etapa</th>
                                    <th>Manzana</th>
                                    <th>Contratista</th>
                                    <th>Fecha inicio</th>
                                    <th>Fecha fin</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="iniobra in arrayIniobra" :key="iniobra.id">
                                    <td>
                                        <button type="button" @click="abrirModal('iniobra','actualizar',iniobra)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <button type="button" class="btn btn-danger btn-sm" @click="eliminarIniobra(iniobra)">
                                          <i class="icon-trash"></i>
                                        </button>
                                    </td>
                                    <td v-text="iniobra.nombre"></td>
                                    <td v-text="iniobra.direccion"></td>
                                    <td v-text="iniobra.colonia"></td>
                                    <td v-text="iniobra.cp"></td>
                                    <td v-text="iniobra.telefono"></td>
                                </tr>                               
                            </tbody>
                        </table>
                    <!--Botones de paginacion -->
                        <nav>
                            
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
                <!-- Fin ejemplo de tabla Listado -->
                    </div>
                </div>
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
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group ">
                                        <label class=" form-control-label" for="text-input">Fraccionamiento</label>
                                        
                                            <select class="form-control" v-model="fraccionamiento_id" @click="selectEtapa(fraccionamiento_id)" >
                                                <option value="0">Seleccione</option>
                                                <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                            </select>
                                        
                                    </div>
                                    <div class="form-group ">
                                        <label class=" form-control-label" for="text-input">Etapa</label>
                                        
                                        <select class="form-control" v-model="etapa_id" @click="selectManzanas(fraccionamiento_id,etapa_id)" >
                                                <option value="0">Seleccione</option>
                                                <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                            </select>
                                        
                                    </div>
                                    <div class="form-group ">
                                        <label class=" form-control-label" for="text-input">Manzanas</label>
                                        
                                        <select class="form-control" v-model="manzana" @click="selectLotesManzana(fraccionamiento_id,etapa_id,manzana)">
                                                <option value="">Seleccione</option>
                                                <option v-for="manzanas in arrayManzanas" :key="manzanas.id" :value="manzanas.manzana" v-text="manzanas.manzana"></option>
                                            </select>
                                        
                                    </div>
                                    <div class="form-group ">
                                        <label class=" form-control-label" for="text-input">Total costo directo</label>
                                            <input type="text" v-model="total_costo_directo" class="form-control" placeholder="total costo directo">
                                    </div>
                                    <div class="form-group ">
                                        <label class=" form-control-label" for="text-input">Total costo indirecto</label>
                                        <input type="text" v-model="total_costo_indirecto" class="form-control" placeholder="total costo indirecto">
                                    </div>
                                    <div class="form-group ">
                                        <label class=" form-control-label" for="text-input">Total anticipo</label>
                                        <input type="text" v-model="total_anticipo" class="form-control" placeholder="total anticipo">
                                    </div>

                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="form-group ">
                                        <label class=" form-control-label" for="text-input">Contratista</label>
                                        
                                            <select class="form-control" v-model="contratista_id" >
                                                <option value="0">Seleccione</option>
                                                <option v-for="contratistas in arrayContratistas" :key="contratistas.id" :value="contratistas.id" v-text="contratistas.nombre"></option>
                                            </select>
                                        
                                    </div>
                                    <div class="form-group ">
                                        <label class=" form-control-label" for="text-input">Fecha de inicio</label>
                                        
                                            <input type="date" v-model="f_ini"  class="form-control" placeholder="Fecha de inicio">
                                        
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label" for="text-input">Fecha de terminacion</label>
                                        
                                            <input type="date" v-model="f_fin" class="form-control" placeholder="Fecha de terminacion">
                                        
                                    </div>
                                    <div class="form-group ">
                                        <label class=" form-control-label" for="text-input">Clave</label>
                                        
                                            <input type="text" v-model="clave" class="form-control" placeholder="clave">
                                        
                                    </div>
                                    <div class="form-group ">
                                    <label class=" form-control-label" for="text-input">Total importe</label>
                                     <input type="text" v-model="total_importe" class="form-control" placeholder="total importe">
                                </div>
                                <div class="form-group ">
                                    <label class=" form-control-label" for="text-input">Anticipo %</label>
                                    <input type="text" v-model="anticipo" class="form-control" placeholder="anticipo">
                                </div>

                                </div>
                                

                            </div>

                              

                               

                                
                           
                                <li v-for="(inputs,index) in arrayLotes" :key="inputs.id" >   
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input" v-text="'Lote #'+inputs.num_lote"></label>
                                    <div class="col-md-2">
                                        <input type="checkbox" value="inputs.num_lote" v-model.trim="lote_id[index]" class="form-control"  required>
                                    </div> 
                                    <div class="col-md-2">
                                        <input type="text" name="costo_directo[]" v-model.trim="costo_directo[index]" class="form-control" placeholder="Costo directo $" required>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" v-model.trim="costo_indirecto[index]" class="form-control" placeholder="Costo indirecto $" required>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" v-model.trim="importe[index]" class="form-control" placeholder="Importe $" required>
                                    </div>
                                </div>
                                </li>
                                
                                 

                                

                               

                                

              

                                

                                

                                <!-- Div para mostrar los errores que mande validerFraccionamiento -->
                                <div v-show="errorIniobra" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjIniobra" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarIniobra()">Enviar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarIniobra()">Actualizar</button>
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
                //datos tabla ini_obra
                id:0,
                fraccionamiento_id : 0,
                etapa_id : 0,
                contratista_id : 0,
                f_ini : '',
                f_fin : '',
                clave : '',
                total_costo_directo: 0,
                total_costo_indirecto: 0,
                total_importe: 0,
                anticipo: 0,
                total_anticipo: 0,
                iniciado: 0,

                //datos tala ini_obra_lotes
                ini_obra_id : this.id,
                manzana: '',
                lote_id: [{},{}],
                superficie: 0.0,
                costo_directo: [{},{}],
                costo_indirecto: [{},{}],
                importe: [{},{}],
                datosArray: [{},{}],
                descripcion: '',
                iniciado_lote: 0,

                //arrays
                arrayIniobra : [],
                arrayFraccionamientos: [],
                arrayEtapas: [],
                arrayManzanas: [],
                arrayLotes: [],
                arrayContratistas: [],
                
            

                //datos modal y paginacion
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                errorIniobra : 0,
                errorMostrarMsjIniobra : [],
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : '', 
                buscar : '',
                buscar1 : '',
                buscar2 : '',
                buscar3 : ''
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
            listarIniobra(page, buscar, criterio){
                let me = this;
                var url = '/iniobra?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayIniobra = respuesta.ini_obras.data;
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
                me.listarIniobra(page,buscar,criterio);
            },

            /** Funciones Select para los listados */
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

            selectEtapa(buscar){
                let me = this;
                
                me.arrayEtapas=[];
                me.num_lote='';
                var url = '/select_etapa_proyecto?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEtapas = respuesta.etapas;
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
            
            selectContratistas(){
                let me = this;

                me.arrayContratistas=[];
                var url = '/select_contratistas';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayContratistas = respuesta.contratista;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            /**Metodo para registrar  */
            registrarIniobra(){
                if(this.validarIniobra()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/iniobra/registrar',{
                    'fraccionamiento_id': this.fraccionamiento_id,
                    'etapa_id': this.etapa_id,
                    'manzana': this.manzana,
                    'num_lote': this.num_lote,                    
                    'contratista_id': this.contratista_id,
                    'f_ini': this.f_ini,
                    'f_fin': this.f_fin,
                    'clave': this.clave,
                    'total_costo_directo': this.total_costo_directo,                    
                    'total_costo_indirecto': this.total_costo_indirecto,
                    'total_importe': this.total_importe,
                    'anticipo': this.anticipo,
                    'total_anticipo': this.total_anticipo,
                    'iniciado': this.iniciado,
                    'ini_obra_id': this.ini_obra_id,
                    'manzana': this.manzana,
                    'lote_id': this.lote_id,                                        
                    'superficie': this.superficie,                    
                    'costo_directo': this.costo_directo,
                    'costo_indirecto': this.costo_indirecto,
                    'importe': this.importe,
                    'descripcion': this.descripcion,
                    'iniciado_lote': this.iniciado_lote,
                    
                }).then(function (response){
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarIniobra(1,'','iniobra'); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Lotes enviados correctamenta para inicio de obra',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },

             /**Metodo para actualizar  */
            actualizarIniobra(){
                if(this.validarIniobra()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                let me = this;
                axios.put('/iniobra/actualizar',{
                    'fraccionamiento_id': this.fraccionamiento_id,
                    'etapa_id': this.etapa_id,
                    'manzana': this.manzana,
                    'num_lote': this.num_lote,                    
                    'contratista_id': this.contratista_id,
                    'f_ini': this.f_ini,
                    'f_fin': this.f_fin,
                    'clave': this.clave,
                    'total_costo_directo': this.total_costo_directo,                    
                    'total_costo_indirecto': this.total_costo_indirecto,
                    'total_importe': this.total_importe,
                    'anticipo': this.anticipo,
                    'total_anticipo': this.total_anticipo,
                    'iniciado': this.iniciado,
                    'ini_obra_id': this.id,
                    'manzana': this.manzana,
                    'lote_id': this.lote_id,                                        
                    'superficie': this.superficie,                    
                    'costo_directo': this.costo_directo,
                    'costo_indirecto': this.costo_indirecto,
                    'importe': this.importe,
                    'descripcion': this.descripcion,
                    'iniciado_lote': this.iniciado_lote,
                    'id' : this.id
                    
                }).then(function (response){
                    me.cerrarModal();
                    me.listarIniobra(1,'','iniobra');
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

            /**Metodo para eliminar  */
            eliminarIniobra(data =[]){
                this.id=data['id'];
                this.fraccionamiento_id = data['fraccionamiento_id'];
                this.etapa_id = data['etapa_id'];
                this.manzana = data['manzana'];
                this.num_lote = data['num_lote'];                    
                this.contratista_id = data['contratista_id'];
                this.f_ini = data['f_ini'];
                this.f_fin = data['f_fin'];
                this.clave = data['clave'];
                this.total_costo_directo = data['total_costo_directo'];                    
                this.total_costo_indirecto = data['total_costo_indirecto'];
                this.total_importe = data['total_importe'];
                this.anticipo = data['anticipo'];
                this.total_anticipo = data['total_anticipo'];
                this.iniciado = data['iniciado'];
                this.id = data['ini_obra_id']; 
                this.manzana  = data['manzana'];
                this.lote_id  = data['lote_id'];                                       
                this.superficie  = data['superficie'];                    
                this.costo_directo  = data['costo_directo'];
                this.costo_indirecto = data['costo_indirecto'];
                this.importe = data['importe'];
                this.descripcion = data['descripcion'];
                this.iniciado_lote = data['iniciado_lote'];
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

                axios.delete('/iniobra/eliminar', 
                        {params: {'id': this.id},'ini_obra_id':this.id}).then(function (response){
                        swal(
                        'Borrado!',
                        'Inicio de obra cancelado y eliminado',
                        'success'
                        )
                        me.listarIniobra(1,'','iniobra');
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            validarIniobra(){
                this.errorIniobra=0;
                this.errorMostrarMsjIniobra=[];

                if(!this.fraccionamiento_id) //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjIniobra.push("Seleccione porfavor un fraccionamiento");

                if(this.errorMostrarMsjIniobra.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorIniobra = 1;

                return this.errorIniobra;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.fraccionamiento_id = 0;
                this.etapa_id = 0;
                this.manzana = '';
                this.num_lote = '';                    
                this.contratista_id = 0;
                this.f_ini ='';
                this.f_fin = '';
                this.clave = '';
                this.total_costo_directo = 0;                    
                this.total_costo_indirecto = 0;
                this.total_importe = 0;
                this.anticipo = 0;
                this.total_anticipo = 0;
                this.iniciado = 0;
                this.ini_obra_id = 0; 
                this.manzana  = '';
                this.lote_id  = '';                                       
                this.superficie  = 0;                    
                this.costo_directo  = 0;
                this.costo_indirecto = 0;
                this.importe = 0;
                this.descripcion = '';
                this.iniciado_lote = 0;
                this.errorIniobra = 0;
                this.errorMostrarMsjIniobra = [];

            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(modelo, accion,data =[]){
                switch(modelo){
                    case "iniobra":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Enviar un inicio de obra';
                                this.fraccionamiento_id = 0;
                                this.etapa_id = 0;
                                this.manzana = '';
                                this.num_lote = '';                    
                                this.contratista_id = 0;
                                this.f_ini ='';
                                this.f_fin = '';
                                this.clave = '';
                                this.total_costo_directo = 0;                    
                                this.total_costo_indirecto = 0;
                                this.total_importe = 0;
                                this.anticipo = 0;
                                this.total_anticipo = 0;
                                this.iniciado = 0;
                                this.ini_obra_id = 0; 
                                this.manzana  = '';
                                this.lote_id  = [{},{}];                                       
                                this.superficie  = 0;                    
                                this.costo_directo  = [{},{}];
                                this.costo_indirecto = [{},{}];
                                this.importe = [{},{}];
                                this.descripcion = '';
                                this.iniciado_lote = 0;
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal =1;
                                this.tituloModal='Actualizar Empresa';
                                this.tipoAccion=2;
                                this.id=data['id'];
                                this.fraccionamiento_id = data['fraccionamiento_id'];
                                this.etapa_id = data['etapa_id'];
                                this.manzana = data['manzana'];
                                this.num_lote = data['num_lote'];                    
                                this.contratista_id = data['contratista_id'];
                                this.f_ini = data['f_ini'];
                                this.f_fin = data['f_fin'];
                                this.clave = data['clave'];
                                this.total_costo_directo = data['total_costo_directo'];                    
                                this.total_costo_indirecto = data['total_costo_indirecto'];
                                this.total_importe = data['total_importe'];
                                this.anticipo = data['anticipo'];
                                this.total_anticipo = data['total_anticipo'];
                                this.iniciado = data['iniciado'];
                                this.id = data['ini_obra_id']; 
                                this.manzana  = data['manzana'];
                                this.lote_id  = data['lote_id'];                                       
                                this.superficie  = data['superficie'];                    
                                this.costo_directo  = data['costo_directo'];
                                this.costo_indirecto = data['costo_indirecto'];
                                this.importe = data['importe'];
                                this.descripcion = data['descripcion'];
                                this.iniciado_lote = data['iniciado_lote'];
                                break;
                            }
                        }
                    }
                }
                //Funciones Select
                this.selectFraccionamientos();
                this.selectEtapa(this.fraccionamiento_id);
                this.selectManzanas(this.fraccionamiento_id, this.etapa_id);
                this.selectLotesManzana(this.fraccionamiento_id, this.etapa_id,this.manzana);
                this.selectContratistas();
              
            }
        },

        //propiedad mounted
        mounted() {
            this.listarIniobra(1,this.buscar,this.criterio);
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
</style>
