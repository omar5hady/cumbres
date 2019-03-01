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
                        <i class="fa fa-align-justify"></i> Historial de simulaciones de credito
                
                    </div>

                    <!----------------- Listado de Simulaciones de Credito ------------------------------>
                    <!-- Div Card Body para listar -->
                     <template v-if="listado == 0">
                        <div class="card-body"> 
                            <div class="form-group row">
                                <div class="col-md-8">
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th># Folio</th>
                                            <th>Cliente</th>
                                            <th>Proyecto</th>
                                            <th># Lote</th>
                                            <th>Modelo</th>
                                            <th>Precio Venta</th>
                                            <th>Credito Solicitado</th>
                                            <th>Plazo</th>
                                            <th>Institucion</th>
                                            <th>Tipo de credito</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="prospecto in arraySimulaciones" :key="prospecto.id">
                                            <td v-text="prospecto.id"></td>
                                            <td v-text="prospecto.nombre + ' ' + prospecto.apellidos "></td>
                                            <td v-text="prospecto.proyecto"></td>
                                            <td v-text="prospecto.num_lote"></td>
                                            <td v-text="prospecto.modelo"></td>
                                            <td v-text="'$'+formatNumber(prospecto.precio_venta)"></td>
                                            <td v-text="'$'+formatNumber(prospecto.credito_solic)"></td>
                                            <td v-text="prospecto.plazo + ' años'"></td>
                                            <td v-text="prospecto.institucion"></td>
                                            <td v-text="prospecto.tipo_credito"></td>
                                            <td v-if="prospecto.status == '0'">
                                                <span class="badge badge-danger">Rechazado</span>
                                            </td>
                                            <td v-if="prospecto.status == '2'">
                                                <span class="badge badge-success">Aprobado</span>
                                            </td>
                                        </tr>                               
                                    </tbody>
                                </table>
                            </div>
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
                    </template>

                 
                    
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
           

               <!--Inicio del modal observaciones-->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal3}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal3"></h4>
                            <button type="button" class="close" @click="cerrarModal3()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                
                                <table class="table table-bordered table-striped table-sm" v-if="tipoAccion == 4">
                                    <thead>
                                        <tr>
                                            <th>Usuario</th>
                                            <th>Observacion</th>
                                            <th>Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="observacion in arrayObservacion" :key="observacion.id">
                                            
                                            <td v-text="observacion.usuario" ></td>
                                            <td v-text="observacion.comentario" ></td>
                                            <td v-text="observacion.created_at"></td>
                                        </tr>                               
                                    </tbody>
                                </table>
                                
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal3()">Cerrar</button>
                        </div>
                    </div>
                      <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>

               <!--Inicio del modal Tipos de credito-->
            <div class="modal animated fadeIn" tabindex="-1" :class="{'mostrar': modal2}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal3"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="modal-body">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Tipo de Credito</label>
                                    <div class="col-md-6">
                                        <select class="form-control" v-model="tipo_credito2" @click="selectInstitucion(tipo_credito2)" >
                                            <option value="0">Seleccione</option>
                                            <option v-for="creditos in arrayCreditos" :key="creditos.nombre" :value="creditos.nombre" v-text="creditos.nombre"></option>   
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Institucion Financiera</label>
                                    <div class="col-md-6">
                                        <select class="form-control" v-model="inst_financiera2" >
                                            <option value="">Seleccione</option>
                                            <option v-for="institucion in arrayInstituciones" :key="institucion.institucion_fin" :value="institucion.institucion_fin" v-text="institucion.institucion_fin"></option>      
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <button type="button" class="btn btn-primary" @click="registrarCreditoSelect()" >Guardar</button>
                                </div>
                            
                            <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>

                                            <th width="10%">Opciones</th>
                                            <th>Tipo de Credito</th>
                                            <th>Institucion Financiera</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="tipoCredito in arrayTiposCreditos" :key="tipoCredito.id">
                                             <td>
                                                <template v-if="tipoCredito.elegido==1">
                                                     <button disabled type="button" class="btn btn-success btn-sm">
                                                        <i class="fa fa-check fa-md"></i>
                                                    </button>
                                                </template>
                                                <template v-else>
                                                    <button @click="seleccionarCredito(tipoCredito.id,tipoCredito.institucion,tipoCredito.tipo_credito)" type="button" class="btn btn-primary btn-sm">
                                                        <i class="fa fa-exchange fa-md"></i>
                                                    </button>
                                                </template>
                                            </td>
                                            <td v-text="tipoCredito.tipo_credito" ></td>
                                            <td v-text="tipoCredito.institucion" ></td>
                                        </tr>                               
                                    </tbody>
                                </table>
                            </div>
                                
                            </form>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        </div>
                    </div>
                      <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            

        </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    import vSelect from 'vue-select';
    export default {
        props:{
            rolId:{type: String}
        },
        data(){
            return{
                proceso:false,
                id:0,
                prospecto_id:0,
                clasificacion:1,
                dep_economicos:'',
                nombre:'',
                apellidos:'',
                telefono : '',
                celular : '',
                email:'',
                email_inst:'',
                puesto:'',
                nss:'',
                sexo:'',
                fecha_nac: '',
                curp:'',
                rfc:'',
                homoclave: '',
                e_civil: 0,
                ama_casa:0,
                tipo_casa:0,
                coacreditado: 0,
                publicidad_id: 0,
                proyecto: '',
                empresa: '',
                observacion:'',
                lugar_contacto: 0,
                conyugeNom: '',
                tipo_credito: 0,
                tipo_credito2: 0,
                tipo_economia: 0,
                estado: '',
                ciudad:'',
                cp:'',
                colonia:'',
                direccion: '',
                inst_financiera:'',
                inst_financiera2:'',
                nacionalidad:0,

                nombre_coa:'',
                parentesco_coa:'',
                apellidos_coa:'',
                telefono_coa : '',
                celular_coa : '',
                email_coa:'',
                email_institucional_coa:'',
                nss_coa:'',
                sexo_coa:'',
                fecha_nac_coa: '',
                curp_coa:'',
                rfc_coa:'',
                homoclave_coa: '',
                e_civil_coa: 0,
                tipo_casa_coa:0,
                estado_coa: '',
                ciudad_coa: '',
                cp_coa:'',
                nacionalidad_coa:0,
                direccion_coa:'',
                colonia_coa:'',
                empresa_coa:'',

             

                arrayEmpresa: [],
                arrayMediosPublicidad:[],
                arrayInstituciones: [],
                arrayEstados: [],
                arrayCiudadesCoa: [],
                arrayCiudades:[],
                arrayColoniasCoa: [],
                arrayColonias: [],
                arrayEtapas: [],
                arrayManzanas: [],
                arrayLotes: [],
                arrayDatosLotes: [],
                arrayPaquetes: [],
                arrayDatosPaquetes: [],
                arraySimulaciones:[],
                arrayTiposCreditos:[],

                proyecto_interes_id: 0,
                proyecto:'',
                etapa: '',
                manzana: '',
                lote: '',
                num_lote:'',
                modelo: '',
                superficie: '',
                precioBase: 0,
                precioExcedente: 0,
                precioVenta: 0,
                promocion: '',
                descripcionPromo: '',
                descuentoPromo: 0,
                paquete_id: 0,
                descripcionPaquete: '',
                costoPaquete: 0,
                paquete:'',
                status:'',
                

                nombre_referencia1: '',
                telefono_referencia1: '',
                celular_referencia1: '',
                
                nombre_referencia2: '',
                telefono_referencia2: '',
                celular_referencia2: '',

             
                modal3: 0,
                modal2: 0,
                listado:1,
                tituloModal3 : '',
                tipoAccion: 0,
                errorProspecto : 0,
                errorMostrarMsjProspecto : [],
                errorCoacreditado : 0,
                errorMostrarMsjCoacreditado : [],
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'personal.nombre', 
                buscar : '',
                arrayCoacreditados : [],
                arrayProspectos: [],
                arrayFraccionamientos : [],
                arrayLugarContacto: [],
                arrayFraccionamientos2 : [],
                arrayObservacion: [],
                fraccionamiento:'',
                mascotas:0,
                num_perros:0,
                num_habitantes:0,
                num_folio:0,
                rang0_10:0,
                rang11_20:0,
                rang21:0,
                valHab:0,
                num_vehiculos:0,
                silla_ruedas:0,
                discapacidad:0,
                terreno_tam_excedente:0,
                plazo_credito:0,
                monto_credito:0
                
            }
        },
        components:{
            vSelect
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

            
        totalHabitantes: function(){
            var resultado_habitantes =0;
                resultado_habitantes = parseFloat(resultado_habitantes) + parseInt(this.rang0_10)+parseInt(this.rang11_20)+parseInt(this.rang21); 
            return resultado_habitantes;
        },

        },
       
        methods : {
            /**Metodo para mostrar los registros */
            listarProspectos(page, buscar, criterio){
                let me = this;
                var url = '/clientes_simulacion?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayProspectos = respuesta.personas.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
             listarSimulaciones(){
                let me = this;
                var url = '/historial_simulaciones_credito';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arraySimulaciones = respuesta.creditos;
                    me.listado=0;
                })
                .catch(function (error) {
                    console.log(error);
                })
            },
            selectEmpresaVueselect(search, loading){
                let me = this;
                loading(true)

                var url = '/select_empresas?filtro='+search;
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    q: search
                    me.arrayEmpresa = respuesta.empresas;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },


            
            seleccionarCredito(id,institucion,tipo_credito){
        
                swal({
                title: 'Esta seguro desea asignar este credito?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/creditos/seleccionar',{
                        'id': id,
                        'simulacion_id': this.num_folio
                    }).then(function (response) {
                        me.listarCreditos();     
                        me.tipo_credito = tipo_credito;
                        me.inst_financiera = institucion;
                        swal(
                        'Activado!',
                        'El registro ha sido activado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },


            getDatosEmpresa(val1){
                let me = this;
                me.loading = true;
                me.empresa_coa = val1.nombre;
               
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

            selectEtapa(fraccionamiento){
                let me = this;
                me.arrayEtapas=[];
                var url = '/select_etapas_disp?buscar=' + fraccionamiento;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                     me.arrayEtapas = respuesta.lotes_etapas;
                    
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            selectManzana(etapa){
                let me = this;
                me.arrayManzanas=[];
                var url = '/select_manzanas_disp?buscar=' + etapa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                     me.arrayManzanas = respuesta.lotes_manzanas;
                    
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectPaquetes(etapa){
                let me = this;
                me.arrayPaquetes=[];
                var url = '/select_paquetes?buscar=' + etapa;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                     me.arrayPaquetes = respuesta.paquetes;
                    
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            datosPaquetes(paquete){
                let me = this;
                me.precioVenta = me.precioVenta - me.costoPaquete;
                if(paquete!=0){
                me.arrayDatosPaquetes=[];
                var url = '/select_datos_paquetes?buscar=' + paquete;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                     me.arrayDatosPaquetes = respuesta.datos_paquetes;
                     me.descripcionPaquete = me.arrayDatosPaquetes[0]['descripcion'];
                     me.costoPaquete = me.arrayDatosPaquetes[0]['costo'];
                     me.paquete = me.arrayDatosPaquetes[0]['nombre'];

                    me.precioVenta = me.precioVenta + me.costoPaquete;
                })
                .catch(function (error) {
                    console.log(error);
                });
                }
                else{
                    me.descripcionPaquete='';
                    me.costoPaquete=0;
                }
            },
            
            selectLotes(manzana){
                let me = this;
                me.arrayLotes=[];
                var url = '/select_lotes_disp?buscar=' + manzana;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                     me.arrayLotes = respuesta.lotes_disp;
                    
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            mostrarDatosLote(lote){
                let me = this;
                me.arrayDatosLotes=[];
                var url = '/select_datos_lotes_disp?buscar=' + lote;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                        me.arrayDatosLotes = respuesta.lotes;
                        me.modelo = me.arrayDatosLotes[0]['modelo'];
                        me.superficie = me.arrayDatosLotes[0]['terreno'];
                        me.precioBase = me.arrayDatosLotes[0]['precio_base'];
                        me.precioExcedente = me.arrayDatosLotes[0]['excedente_terreno'];
                        me.precioVenta = me.arrayDatosLotes[0]['precio_venta'];
                        me.promocion = me.arrayDatosLotes[0]['promocion'];
                        me.descripcionPromo = me.arrayDatosLotes[0]['descripcionPromo'];
                        me.descuentoPromo = me.arrayDatosLotes[0]['descuentoPromo'];
                        me.terreno_tam_excedente= me.arrayDatosLotes[0]['terreno_tam_excedente'];
                        me.num_lote = me.arrayDatosLotes[0]['num_lote'];

                        me.precioVenta = me.precioVenta - me.descuentoPromo;
                    
                    
                })
                .catch(function (error) {
                    console.log(error);
                });
                

            },
            LimpiarMascotas(){
                if(this.mascotas=="0"){
                    this.num_perros=0;
                }
            },
            LimpiarSillaRuedas(){
                if(this.discapacidad=="0")
                    this.silla_ruedas=0;
            },

            selectLugarContacto(){
                let me = this;
                me.arrayLugarContacto=[];
                var url = '/select_lugar_contacto';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLugarContacto = respuesta.lugares_contacto;
                })
                .catch(function (error) {
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
           
            selectFraccionamientos2(){
                let me = this;
                me.arrayFraccionamientos2=[];
                var url = '/select_fraccionamiento';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayFraccionamientos2 = respuesta.fraccionamientos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },


            selectEstados(){
                let me = this;
                me.arrayEstados=[];
                
                var url = '/select_estados';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEstados = respuesta.estados;
                    
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            
            selectCiudades(estado,coacreditado){
                let me = this;
                var url = '/select_ciudades?buscar='+estado;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    if(coacreditado==1){
                        me.arrayCiudadesCoa=[];
                        me.arrayCiudadesCoa = respuesta.ciudades;
                    }
                    else{
                        me.arrayCiudades=[];
                        me.arrayCiudades = respuesta.ciudades;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },


            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },

            selectColonias(cp,coacreditado){
                let me = this;
                me.arrayColonias=[];
                var url = '/select_colonias?buscar=' + cp;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    if(coacreditado==1){
                        me.arrayColoniasCoa=[];
                        me.arrayColoniasCoa = respuesta.colonias;
                    }
                    else{
                        me.arrayColonias=[];
                        me.arrayColonias = respuesta.colonias;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },



            listarObservacion(page, buscar){
                let me = this;
                var url = '/clientes/observacion?page=' + page + '&buscar=' + buscar ;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayObservacion = respuesta.observacion.data;
                    me.pagination = respuesta.pagination;
                    console.log(url);
                })
                .catch(function (error) {
                    console.log(error);
                });
                
            },

            selectCreditos(){
                let me = this;
                me.arrayCreditos=[];
                var url = '/select_tipoCredito';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayCreditos = respuesta.Tipos_creditos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectInstitucion(credito){
                let me = this;
                if(me.tipo_credito==0){
                    me.inst_financiera="";
                }
                me.arrayInstituciones=[];
                var url = '/select_institucion?buscar='+credito;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayInstituciones = respuesta.instituciones;
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
                me.listarProspectos(page,buscar,criterio);
            },
            /**Metodo para registrar  */
            actualizarDatosProspecto(){
                //Con axios se llama el metodo store del controller
                axios.put('/creditos/actualizarProspecto',{
                    'id':this.id,
                    'direccion':this.direccion,
                    'cp':this.cp,
                    'colonia':this.colonia,
                    'ciudad':this.ciudad,
                    'estado':this.estado,
                    'nacionalidad':this.nacionalidad,
                    'puesto':this.puesto,

                    'direccion_coa':this.direccion_coa,
                    'colonia_coa':this.colonia_coa,
                    'cp_coa':this.cp_coa,
                    'ciudad_coa':this.ciudad_coa,
                    'estado_coa':this.estado_coa,
                    'empresa_coa':this.empresa_coa,
                    'nacionalidad_coa':this.nacionalidad_coa
                    
                })
            },    
            aceptarSimulacion(){
                let me = this;
                //Con axios se llama el metodo update de DepartamentoController
                axios.put('/creditos/aceptar',{
                    'id': this.num_folio
                }).then(function (response){
                    me.listarSimulaciones();
                    me.limpiarDatos();
                    //window.alert("Cambios guardados correctamente");
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Solicitud aceptada',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            }, 
            rechazarSimulacion(){
                let me = this;
                //Con axios se llama el metodo update de DepartamentoController
                axios.put('/creditos/rechazar',{
                    'id': this.num_folio
                }).then(function (response){
                    me.listarSimulaciones(me.prospecto_id);
                    me.limpiarDatos();
                    //window.alert("Cambios guardados correctamente");
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Solicitud rechazada',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            }, 

               /**Metodo para actualizar  */
            registrarSimulacion(){
                if(this.validarRegistro() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;

                

                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/creditos/registrar',{
                    'prospecto_id':this.id,
                    'dep_economicos':this.dep_economicos,
                    'tipo_economia':this.tipo_economia,
                    'nombre_referencia1':this.nombre_referencia1 ,
                    'telefono_referencia1':this.telefono_referencia1 ,
                    'celular_referencia1':this.celular_referencia1,
                    'nombre_referencia2':this.nombre_referencia2 ,
                    'telefono_referencia2':this.telefono_referencia2 ,
                    'celular_referencia2':this.celular_referencia2,
                    'etapa':this.etapa,
                    'manzana':this.manzana,
                    'lote':this.num_lote,
                    'modelo':this.modelo,
                    'precioBase':this.precioBase,
                    'superficie':this.superficie,
                    'terreno_tam_excedente':this.terreno_tam_excedente,
                    'precioExcedente':this.precioExcedente,
                    'promocion':this.promocion,
                    'descripcionPromo':this.descripcionPromo,
                    'descuentoPromo':this.descuentoPromo,
                    'paquete_id':this.paquete,
                    'descripcionPaquete':this.descripcionPaquete,
                    'costoPaquete':this.costoPaquete,
                    'precioVenta':this.precioVenta,
                    'plazo_credito':this.plazo_credito,
                    'monto_credito':this.monto_credito,
                    'mascotas':this.mascotas,
                    'num_perros':this.num_perros ,
                    'rang0_10':this.rang0_10 ,
                    'rang11_20':this.rang11_20,
                    'rang21':this.rang21,
                    'ama_casa':this.ama_casa,
                    'discapacidad':this.discapacidad,
                    'silla_ruedas':this.silla_ruedas,
                    'tipo_credito':this.tipo_credito,
                    'inst_financiera':this.inst_financiera,
                    'num_vehiculos':this.num_vehiculos
                    
                }).then(function (response){
                    me.proceso=false;
                    me.actualizarDatosProspecto();
                    me.listado=1;
                    me.limpiarDatos();
                    me.listarProspectos(1,'','ini_obras.clave'); //se enlistan nuevamente los registros
                    
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Simulacion enviada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },

            registrarCreditoSelect(){
                if(this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;

                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/creditos_select/registrar',{
                    'credito_id':this.num_folio,
                    'tipo_credito':this.tipo_credito2,
                    'institucion':this.inst_financiera2                    
                }).then(function (response){
                    me.proceso=false;
                    me.listado=5;
                    me.listarCreditos();
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Credito agregado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },

            validarRegistro(){
                this.errorProspecto=0;
                this.errorMostrarMsjProspecto=[];

                if(this.direccion=='' || this.cp=='' || this.colonia=="") 
                    this.errorMostrarMsjProspecto.push("Completar el domicilio del prospecto");
                if(this.ciudad=='' || this.estado=='') 
                    this.errorMostrarMsjProspecto.push("Seleccionar Ciudad y estado del prospecto");
                if(this.tipo_economia=='' || this.puesto=='') 
                    this.errorMostrarMsjProspecto.push("Completar datos para lugar de trabajo.");
                if(this.dep_economicos=='') 
                    this.errorMostrarMsjProspecto.push("Ingresar numero de dependientes económicos.");
                if(this.nombre_referencia1=='' || this.nombre_referencia2=='' || this.telefono_referencia1 == '' 
                        || this.telefono_referencia2 == '' || this.celular_referencia1 == '' || this.celular_referencia2=='') 
                    this.errorMostrarMsjProspecto.push("Completar datos para referencias personales.");
                if(this.etapa=='' || this.manzana=='' || this.lote == '') 
                    this.errorMostrarMsjProspecto.push("Seleccionar lote de interes.");
                if(this.tipo_credito==0 || this.inst_financiera=='') 
                    this.errorMostrarMsjProspecto.push("Seleccionar credito a solicitar");

                if(this.errorMostrarMsjProspecto.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorProspecto = 1;

                return this.errorProspecto;
            },
            validarCoacreditado(){
                this.errorCoacreditado=0;
                this.errorMostrarMsjCoacreditado=[];

                if(this.nombre_coa=='' || this.apellidos_coa=='') 
                    this.errorMostrarMsjCoacreditado.push("El nombre del prospecto no puede ir vacio.");
                if(this.sexo_coa=='') 
                    this.errorMostrarMsjCoacreditado.push("Seleccionar el sexo del prospecto.");
                if(this.celular_coa=='') 
                    this.errorMostrarMsjCoacreditado.push("Ingresar numero de celular.");
                if(this.email_coa=='') 
                    this.errorMostrarMsjCoacreditado.push("Ingresar email personal.");
                if(this.fecha_nac_coa=='') 
                    this.errorMostrarMsjCoacreditado.push("Ingresar fecha de nacimiento.");
                if(this.rfc_coa=='') 
                    this.errorMostrarMsjCoacreditado.push("Ingresar RFC.");
                if(this.nss_coa=='') 
                    this.errorMostrarMsjCoacreditado.push("Ingresar numero de seguro social.");
                if(this.tipo_casa_coa==0) 
                    this.errorMostrarMsjCoacreditado.push("Seleccionar tipo de casa.");
                if(this.e_civil_coa==0) 
                    this.errorMostrarMsjCoacreditado.push("Seleccionar estado civil.");

                if(this.errorMostrarMsjCoacreditado.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorCoacreditado = 1;

                return this.errorCoacreditado;
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
            mostrarDetalle(data = []){
                this.num_folio= data['id'];
                this.nombre = data['nombre'];
                this.apellidos = data['apellidos'];
                this.sexo = data['sexo'];
                this.telefono = data['telefono'];
                this.celular = data['celular'];
                this.email = data['email'];
                this.direccion = data['direccion'];
                this.cp = data['cp'];
                this.colonia = data['colonia'];
                this.estado = data['estado'];
                this.ciudad = data['ciudad'];
                this.fecha_nac = data['f_nacimiento'];
                this.curp = data['curp'];
                this.rfc = data['rfc'];
                this.homoclave = data['homoclave'];
                this.nss = data['nss'];
                this.tipo_economia = data['tipo_economia'];
                this.empresa = data['empresa'];
                this.puesto = data['puesto'];
                this.email_inst = data['email_institucional'];
                this.tipo_casa = data['tipo_casa'];
                this.e_civil = data['edo_civil'];
                this.dep_economicos = data['num_dep_economicos']
                this.status = data['status'];
                
                this.nombre_referencia1 = data['nombre_primera_ref'];
                this.telefono_referencia1 = data['telefono_primera_ref'];
                this.celular_referencia1 = data['celular_primera_ref'];
                this.nombre_referencia2 = data['nombre_segunda_ref'];
                this.telefono_referencia2 = data['telefono_segunda_ref'];
                this.celular_referencia2 = data['celular_segunda_ref'];

                this.coacreditado = data['coacreditado'];
                this.nombre_coa = data['nombre_coa'];
                this.apellidos_coa = data['apellidos_coa'];
                this.conyugeNom = this.nombre_coa + ' ' + this.apellidos_coa;
                this.fecha_nac_coa = data['f_nacimiento_coa'];
                this.rfc_coa = data['rfc_coa'];
                this.homoclave_coa = data['homoclave_coa'];
                this.curp_coa = data['curp_coa'];
                this.nss_coa = data['nss_coa'];
                this.nacionalidad_coa = data['nacionalidad_coa'];
                this.direccion_coa = data['direccion_coa'];
                this.cp_coa = data['cp_coa'];
                this.colonia_coa = data['colonia_coa'];
                this.estado_coa = data['estado_coa'];
                this.ciudad_coa = data['ciudad_coa'];
                this.celular_coa = data['celular_coa'];
                this.telefono_coa = data['telefono_coa'];
                this.email_coa = data['email_coa'];
                this.email_institucional_coa = data['email_institucional_coa'];
                this.empresa_coa = data['empresa_coa'];


                this.proyecto = data['proyecto'];
                this.etapa = data['etapa'];
                this.manzana = data['manzana'];
                this.lote = data['num_lote'];
                this.modelo = data['modelo'];
                this.superficie = data['superficie'];
                this.terreno_tam_excedente = data['terreno_excedente'];
                this.precioExcedente = data['precio_terreno_excedente'];
                this.promocion = data['promocion'];
                this.descripcionPromo = data['descripcion_promocion'];
                this.descuentoPromo = data['descuento_promocion'];
                this.paquete = data['paquete'];
                this.descripcionPaquete = data['descripcion_paquete'];
                this.costoPaquete = data['costo_paquete'];
                this.tipo_credito = data['tipo_credito'];
                this.inst_financiera = data['institucion'];
                this.plazo_credito =data['plazo'];
                this.monto_credito = data['credito_solic'];
                this.mascotas = data['mascota'];
                this.num_perros = data['num_perros'];
                this.rang0_10 = data['rang010'];
                this.rang11_20 = data['rang1120'];
                this.rang21 = data['rang21'];
                this.ama_casa = data['ama_casa'];
                this.discapacidad = data['persona_discap'];
                this.silla_ruedas = data['silla_ruedas'];
                this.num_vehiculos = data['num_vehiculos'];
                 


                this.listado=5;
            },
            limpiarDatos(){
                this.clasificacion=1;
                this.nombre='';
                this.apellidos='';
                this.telefono = '';
                this.celular = '';
                this.email='';
                this.email_inst='';
                this.nss='';
                this.sexo='';
                this.fecha_nac= '';
                this.curp='';
                this.rfc='';
                this.homoclave= '';
                this.e_civil= 0;
                this.tipo_casa=0;
                this.coacreditado= 0;
                this.proyecto_interes_id= 0;
                this.empresa= '';
                this.observacion='';
                this.puesto='';
                this.dep_economicos='';
                this.direccion='';
                this.colonia='';
                this.estado='';
                this.ciudad='';
                this.tipo_economia=0;


                this.nombre_coa='';
                this.n_completo_coa='';
                this.nacionalidad_coa='';
                this.direccion_coa='';
                this.cp_coa='';
                this.colonia_coa='';
                this.estado_coa='';
                this.ciudad_coa='';
                this.empresa_coa='';
                this.parentesco_coa='';
                this.apellidos_coa='';
                this.telefono_coa = '';
                this.celular_coa = '';
                this.email_coa='';
                this.email_institucional_coa='';
                this.nss_coa='';
                this.sexo_coa='';
                this.fecha_nac_coa= '';
                this.curp_coa='';
                this.rfc_coa='';
                this.homoclave_coa= '';
                this.e_civil_coa= 0;
                this.tipo_casa_coa=0;
                this.conyugeNom = '';
                this.proceso=false;

                this.nombre_referencia1='';
                this.telefono_referencia1='';
                this.celular_referencia1='';
                this.nombre_referencia2='';
                this.telefono_referencia2='';
                this.celular_referencia2='';

                this.etapa='';
                this.manzana='';
                this.lote='';
                this.paquete_id=0;
                this.tipo_credito=0;
                this.inst_financiera='';
                this.paquete='';

                this.mascotas=0;
                this.num_habitantes=0;
                this.num_perros=0;
                this.rang0_10=0;
                this.rang11_20=0;
                this.rang21=0;
                this.num_vehiculos=0;


                this.errorProspecto=0;
                this.errorMostrarMsjProspecto=[];
                this.arraySimulaciones=[];
            },
            actualizarProspectoBTN(prospecto){
              
                //let me= this;
                
                var arrayDatosProspecto=[];
                /*var url = '/clientes/obtenerDatos?id=' + id;

                 axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayDatosProspecto = respuesta.personas;*/

                    this.nombre= prospecto['nombre'];
                    this.apellidos= prospecto['apellidos'];
                    this.direccion=prospecto['direccion'];
                    this.cp = prospecto['cp'];
                    this.colonia=prospecto['colonia'];
                    this.estado=prospecto['estado'];
                    this.ciudad=prospecto['ciudad'];
                    this.sexo= prospecto['sexo'];
                    this.telefono= prospecto['telefono'];
                    this.celular= prospecto['celular'];
                    this.email_inst= prospecto['email_institucional'];
                    this.email = prospecto['email'];
                    this.empresa=prospecto['empresa'];
                    this.fecha_nac=prospecto['f_nacimiento'];
                    this.curp=prospecto['curp'];
                    this.rfc=prospecto['rfc'];
                    this.homoclave=prospecto['homoclave'];
                    this.nss=prospecto['nss'];
                    this.lugar_contacto=prospecto['lugar_contacto'];
                    this.clasificacion=prospecto['clasificacion'];
                    this.proyecto_interes_id=prospecto['proyecto_interes_id'];
                    this.publicidad_id=prospecto['publicidad_id'];
                    this.tipo_casa=prospecto['tipo_casa'];
                    this.e_civil=prospecto['edo_civil'];
                    this.parentesco_coa=prospecto['parentesco_coa'];
                    this.coacreditado=prospecto['coacreditado'];
                    this.conyugeNom = prospecto['n_completo_coa'];
                    this.proyecto = prospecto['proyecto'];
                    this.fecha_nac_coa = prospecto['f_nacimiento_coa'];
                    this.rfc_coa = prospecto['rfc_coa'];
                    this.homoclave_coa = prospecto['homoclave_coa'];
                    this.curp_coa = prospecto['curp_coa'];
                    this.nss_coa = prospecto['nss_coa'];
                    this.telefono_coa = prospecto['telefono_coa'];
                    this.celular_coa = prospecto['celular_coa'];
                    this.email_coa = prospecto['email_coa'];
                    this.email_institucional_coa = prospecto['email_institucional_coa'];
                    this.nacionalidad = prospecto['nacionalidad'];
                    this.nacionalidad_coa = prospecto['nacionalidad_coa'];
                    this.puesto = prospecto['puesto'];
                    this.ciudad_coa = prospecto['ciudad_coa'];
                    this.estado_coa = prospecto['estado_coa'];
                    this.cp_coa = prospecto['cp_coa'];
                    this.direccion_coa = prospecto['direccion_coa'];
                    this.colonia_coa = prospecto['colonia_coa'];
                    this.empresa_coa = prospecto['empresa_coa'];
                    this.dep_economicos='';
                    this.rang0_10=0;
                    this.rang11_20=0;
                    this.rang21=0;
                    this.num_habitantes=0;
                    this.valHab=0;
                    this.selectEtapa(this.proyecto_interes_id);
                    
                    this.id=prospecto['id'];
                    this.listado=3;

               /* })
                .catch(function (error) {
                    console.log(error);
                });*/
               

            },

            cerrarModal(){
                this.modal2=0;
                this.tituloModal3 = '';
            },

             cerrarModal3(){
                this.modal3 = 0;
                this.tituloModal3 = '';
              
            },

            listarCreditos(){
                let me = this;
                var url = '/select_tipcreditos_simulacion?simulacion_id=' + this.num_folio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayTiposCreditos = respuesta.creditos_select;
                    // me.modal2=1;
                    // me.tituloModal3 = 'Añadir tipo de credito';
                })
                .catch(function (error) {
                    console.log(error);
                });

            },
            
            abrirModal(){
                let me = this;
                me.arrayTiposCreditos=[];
                var url = '/select_tipcreditos_simulacion?simulacion_id=' + this.num_folio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayTiposCreditos = respuesta.creditos_select;
                    me.modal2=1;
                    me.tituloModal3 = 'Añadir tipo de credito';
                })
                .catch(function (error) {
                    console.log(error);
                });

                
            },
  
             abrirModal3(prospectos,accion,prospecto){
             switch(prospectos){
                    case "prospecto":
                    {
                        switch(accion){
                         
                             case 'ver_todo':
                            {
                                this.modal3 =1;
                                this.tituloModal3='Consulta Observaciones';
                                this.tipoAccion= 4;
                                break;  
                            }
                            
                        }
                    }
                 
             }
                
         },

           
        },
        mounted() {
            this.listarProspectos(1,this.buscar,this.criterio);
            this.selectMedioPublicidad();
            this.selectFraccionamientos();
            this.selectEtapa(this.proyecto_interes_id);
            this.selectManzana(this.etapa);
            this.selectPaquetes(this.etapa);
            this.datosPaquetes(this.paquete_id);
            this.selectLotes(this.manzana);
            this.mostrarDatosLote(this.lote);
            this.selectLugarContacto();
            this.selectCreditos();
            this.selectInstitucion(this.tipo_credito);
            this.selectEstados();
            this.selectCiudades(this.estado,0);
            this.selectColonias(this.cp,0);
            this.listarSimulaciones();
           
        }
    }
</script>
<style>

    .modal-content{
        width: 100% !important;
        position: absolute !important;
       
    }
    .modal-body{
        height: 450px;
        width: 100%;
        overflow-y: auto;
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
    @media (min-width: 600px){
        .btnagregar{
        margin-top: 2rem;
        }
    }
   

</style>
