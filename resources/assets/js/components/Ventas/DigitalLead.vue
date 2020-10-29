<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
        </ol>

        <div class="container-fluid">
            <div class="card scroll-box">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Digital Leads
                    &nbsp;
                    <button @click="back()" class="btn btn-primary btn-sm">Regresar</button>
                </div>
                
                <div class="card-body">
                    <!--TOTALES A PAGAR-->
                    <template v-if="editar == 0">
                        <!--FORMULARIO-->
                        <div class="form-group row">
                            <div class="col-md-10">
                                <div class="input-group">
                                    <input type="text" v-model="b_cliente" @keyup.enter="listarLeads(1)" placeholder="Nombre" class="form-control col-sm-4">
                                    <button @click="listarLeads(1)" class="btn btn-sm btn-primary col-sm-1">
                                        <i class="fa fa-search"></i> Buscar
                                    </button>
                                </div>
                            </div>
                        </div>
                        <br>

                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Nombre</th>
                                        <th>Celular</th>
                                        <th>Correo</th>
                                        <th>Campaña</th>
                                        <th>Proyecto interes</th>
                                        <th>Presupuesto</th>
                                        <th>Modelo recomendado</th>
                                        <th>Estatus</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="lead in arrayLeads.data" :key="lead.id">
                                        <td class="td2" style="width:15%">
                                            <button title="Editar" type="button" @click="abrirModal('actualizar',lead)" class="btn btn-warning btn-sm">
                                                <i class="icon-pencil"></i>
                                            </button>  
                                            <button type="button" class="btn btn-danger btn-sm" @click="eliminar(lead)" title="Eliminar">
                                                <i class="icon-trash"></i>
                                            </button>                                       
                                        </td>
                                        <td class="td2" v-if="lead.apellidos != null" v-text="lead.nombre + ' '+lead.apellidos"></td>
                                        <td class="td2" v-else v-text="lead.nombre"></td>
                                        <td class="td2" v-if="lead.celular != null"  v-text="lead.celular"></td><td class="td2" v-else ></td>
                                        <td class="td2" v-if="lead.email != null"  v-text="lead.celular"></td><td class="td2" v-else ></td>
                                        <td class="td2" v-text="lead.nombre_campania + '-'+lead.medio_digital"></td>
                                        <td class="td2" v-text="lead.proyecto"></td>
                                        <td class="td2" v-if="lead.rango != null" v-text="'$'+formatNumber(lead.rango1) + ' - $'+formatNumber(lead.rango2)"></td><td class="td2" v-else ></td>
                                        <td class="td2" v-text="lead.modelo_interes"></td>
                                        <td class="td2" v-if="lead.status == '1'"><span class="badge badge-warning">En Seguimiento</span></td>
                                        <td class="td2" v-if="lead.status == '0'"><span class="badge badge-danger">Descartado</span></td>
                                        <td class="td2" v-if="lead.status == '2'"><span class="badge badge-success">Potencial</span></td>
                                        <td></td>
                                       
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item">
                                    <a class="page-link" href="#" @click="listarLeads(1)">Inicio</a>
                                </li>
                                <li v-if="arrayLeads.current_page-3 >= 1">
                                    <a class="page-link" href="#" 
                                    @click="listarLeads(arrayLeads.current_page-3)" 
                                    v-text="arrayLeads.current_page-3" ></a>
                                </li>
                                <li v-if="arrayLeads.current_page-2 >= 1">
                                    <a class="page-link" href="#" 
                                    @click="listarLeads(arrayLeads.current_page-2)" 
                                    v-text="arrayLeads.current_page-2" ></a>
                                </li>
                                <li v-if="arrayLeads.current_page-1 >= 1">
                                    <a class="page-link" href="#" 
                                    @click="listarLeads(arrayLeads.current_page-1)" 
                                    v-text="arrayLeads.current_page-1" ></a>
                                </li>
                                
                                <li class="page-item active">
                                    <a class="page-link" href="#" v-text="arrayLeads.current_page" ></a>
                                </li>
                                
                                <li v-if="arrayLeads.current_page+1 <= arrayLeads.last_page">
                                    <a class="page-link" href="#" 
                                    @click="listarLeads(arrayLeads.current_page+1)" 
                                    v-text="arrayLeads.current_page+1" ></a>
                                </li>
                                <li v-if="arrayLeads.current_page+2 <= arrayLeads.last_page">
                                    <a class="page-link" href="#" 
                                    @click="listarLeads(arrayLeads.current_page+2)" 
                                    v-text="arrayLeads.current_page+2" ></a>
                                </li>
                                <li v-if="arrayLeads.current_page+3 <= arrayLeads.last_page">
                                    <a class="page-link" href="#" 
                                    @click="listarLeads(arrayLeads.current_page+3)" 
                                    v-text="arrayLeads.current_page+3" ></a>
                                </li>
                                
                                <li class="page-item">
                                    <a class="page-link" href="#" @click="listarLeads(arrayLeads.last_page)">Ultimo</a>
                                </li>
                            </ul>
                        </nav>
                    </template>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>

        <!--Inicio del modal -->
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
                            <!-- form para solicitud de avaluo -->

                                    <div class="">
                                        <div class="card-body">
                                            <ul class="nav nav-tabs">
                                                <li class="nav-item"><a class="nav-link"  v-bind:class="{ 'active': paso==1 }" @click="paso = 1">Lead</a></li>
                                                <li class="nav-item"><a class="nav-link"  v-bind:class="{ 'active': paso==2 }" @click="paso = 2">Datos personales</a></li>
                                                <li class="nav-item"><a class="nav-link"  v-bind:class="{ 'active': paso==3 }" @click="paso = 3">Datos importantes</a></li>
                                            </ul>
                                        </div>

                                        <template v-if="paso == 1"> <!-- Datos del lead -->

                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input"><strong>Nombre:</strong><span style="color:red;" v-show="datos.nombre==''">(*)</span></label>
                                                <div class="col-md-4">
                                                    <input type="text" v-model="datos.nombre" class="form-control" placeholder="Nombre">
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" v-model="datos.apellidos" class="form-control" placeholder="Apellidos">
                                                </div>
                                            </div>
                                            
                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Telefono: </label>
                                                <div class="col-md-3">
                                                    <input type="text" v-model="datos.telefono" class="form-control" placeholder="Telefono">
                                                </div>
                                                <label class="col-md-2 form-control-label" for="text-input">Celular: </label>
                                                <div class="col-md-3">
                                                    <input type="text" v-model="datos.celular" class="form-control" placeholder="Celular">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Correo electrónico: </label>
                                                <div class="col-md-5">
                                                    <input type="text" v-model="datos.email" class="form-control" placeholder="Telefono">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Campaña publicitaria</label>
                                                <div class="col-md-4">
                                                    <select class="form-control" v-model="datos.campania_id">
                                                        <option value="">Seleccione</option>
                                                        <option v-for="medios in arrayCampanias" :key="medios.id" :value="medios.id" v-text="medios.nombre_campania + ' - ' + medios.medio_digital"></option>
                                                    </select>
                                                </div>

                                                <label class="col-md-2 form-control-label" for="text-input">Medio de contacto</label>
                                                <div class="col-md-4">
                                                    <input type="text" name="city" list="cityname" class="form-control" v-model="datos.medio_contacto" laceholder="Medio de publicidad">
                                                    <datalist id="cityname">
                                                        <option value="">Seleccione</option>
                                                        <option value="Facebook">Facebook</option>
                                                        <option value="Instagram">Instagram</option>
                                                        <option value="Pagina web">Pagina web</option>
                                                        <option value="Llamada Telefonica">Llamada Telefónica</option>
                                                        
                                                    </datalist>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input"><strong>Proyecto de interes</strong></label>
                                                <div class="col-md-6">
                                                    <select class="form-control" v-model="datos.proyecto_interes" v-on:change="selectModelo(datos.proyecto_interes)">
                                                        <option value="">Seleccione</option>
                                                        <option v-for="proyecto in arrayFraccionamientos" :key="proyecto.id" 
                                                            :value="proyecto.id" v-text="proyecto.nombre">
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Prototipo recomendado: </label>
                                                <div class="col-md-5">
                                                    <input type="text" name="city" list="modelosName" class="form-control" v-model="datos.modelo_interes" laceholder="Prototipo">
                                                    <datalist id="modelosName">
                                                        <option value="">Modelo</option>
                                                        <option v-for="modelos in arrayModelos" :key="modelos.id" :value="modelos.nombre" v-text="modelos.nombre"></option>
                                                    </datalist>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Presupuesto</label>
                                                <div class="col-md-2">
                                                    <p><strong>${{ formatNumber(datos.rango1)}}</strong></p>
                                                </div>
                                                <div class="col-md-3">
                                                    <input class="form-control" type="text" v-model="datos.rango1" placeholder="Minimo">
                                                    <input class="form-control" type="range" name="price-min" id="price-min" v-model="datos.rango1" min="300000" max="2500000">
                                                </div>
                                                <div class="col-md-2">
                                                    <p><strong>${{ formatNumber(datos.rango2)}}</strong></p>
                                                </div>
                                                <div class="col-md-3">
                                                    <input class="form-control" type="text" v-model="datos.rango2" placeholder="Maximo">
                                                    <input class="form-control" type="range" name="price-max" id="price-max" v-model="datos.rango2" min="300000" max="2500000">
                                                </div>
                                            </div>

                                                <div class="form-group row line-separator"></div>

                                                <div class="col-md-12">
                                                    <h6 align="center"><strong> Lugar de trabajo </strong></h6>
                                                </div>

                                           
                                                

                                        </template>

                                        <template v-if="paso == 2"> <!-- Datos del lead -->

                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Nombre: <span style="color:red;" v-show="datos.nombre==''">(*)</span></label>
                                                <div class="col-md-4">
                                                    <input type="text" v-model="datos.nombre" class="form-control" placeholder="Nombre">
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" v-model="datos.apellidos" class="form-control" placeholder="Apellidos">
                                                </div>
                                            </div>
                                            
                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Telefono: </label>
                                                <div class="col-md-3">
                                                    <input type="text" v-model="datos.telefono" class="form-control" placeholder="Telefono">
                                                </div>
                                                <label class="col-md-2 form-control-label" for="text-input">Celular: </label>
                                                <div class="col-md-3">
                                                    <input type="text" v-model="datos.celular" class="form-control" placeholder="Celular">
                                                </div>
                                            </div>
                                            
                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Dirección: <span style="color:red;" v-show="datos.direccion==''">(*)</span></label>
                                                <div class="col-md-4">
                                                    <input type="text" v-model="datos.direccion" class="form-control" >
                                                </div>
                                                <label class="col-md-1 form-control-label" for="text-input">CP: <span style="color:red;" v-show="datos.cp==''">(*)</span></label>
                                                <div class="col-md-3">
                                                    <input type="text" v-model="datos.cp"  
                                                    pattern="\d*" maxlength="5" v-on:keypress="isNumber($event)" @keyup="selectColonias(datos.cp,0)"
                                                    class="form-control" >
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Colonia: <span style="color:red;" v-show="datos.colonia==''">(*)</span></label>
                                                <div class="col-md-7">
                                                    <input type="text" name="city3" list="cityname3" class="form-control" v-model="datos.colonia">
                                                    <datalist id="cityname3">
                                                        <option value="">Seleccione</option>
                                                        <option v-for="colonias in arrayColonias" :key="colonias.colonia " :value="colonias.colonia" v-text="colonias.colonia"></option>    
                                                    </datalist>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Estado: <span style="color:red;" v-show="datos.estado==''">(*)</span></label>
                                                <div class="col-md-3">
                                                    <select class="form-control" v-model="datos.estado" @click="selectCiudades(datos.estado,0)" >
                                                        <option value="">Seleccione</option>
                                                        <option v-for="estados in arrayEstados" :key="estados.estado" :value="estados.estado" v-text="estados.estado"></option>    
                                                    </select>
                                                </div>
                                                <label class="col-md-1 form-control-label" for="text-input">Ciudad: <span style="color:red;" v-show="datos.ciudad==''">(*)</span></label>
                                                <div class="col-md-5">
                                                    <select class="form-control" v-model="datos.ciudad" >
                                                        <option value="">Seleccione</option>
                                                        <option v-for="ciudades in arrayCiudades" :key="ciudades.municipio" :value="ciudades.municipio" v-text="ciudades.municipio"></option>    
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">CURP: <span style="color:red;" v-show="datos.curp==''">(*)</span></label>
                                                <div class="col-md-4">
                                                    <input type="text" v-model="datos.curp" maxlength="18" class="form-control" >
                                                </div>
                                                <label class="col-md-1 form-control-label" for="text-input">NSS: <span style="color:red;" v-show="datos.nss==''">(*)</span></label>
                                                <div class="col-md-4">
                                                    <input type="text" v-model="datos.nss" maxlength="11" pattern="\d*" class="form-control" v-on:keypress="isNumber($event)">
                                                </div>
                                            </div>

                                                <div class="form-group row line-separator"></div>

                                                <div class="col-md-12">
                                                    <h6 align="center"><strong> Lugar de trabajo </strong></h6>
                                                </div>

                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input" >Tipo de economia <span style="color:red;" v-show="tipo_economia==0">(*)</span></label>
                                                <div class="col-md-3">
                                                    <select class="form-control" v-model="tipo_economia" >
                                                        <option value="0">Seleccione</option>  
                                                        <option value="Formal">Formal</option>
                                                        <option value="Informal">Informal</option>
                                                        <option value="Mixta">Mixta</option>
                                                    </select>
                                                </div>

                                                <label class="col-md-2 form-control-label" for="text-input" v-if="tipo_economia=='Formal' ||tipo_economia=='Mixta'">Puesto: </label>
                                                <label class="col-md-2 form-control-label" for="text-input" v-else>Giro del negocio: </label>
                                                <div class="col-md-4">
                                                    <input type="text" v-model="puesto" maxlength="50" class="form-control" >
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="">Empresa <span style="color:red;" v-show="datos.empresa==0">(*)</span></label>
                                                <div class="col-md-6">
                                                    <v-select 
                                                        :on-search="selectEmpresaVueselect"
                                                        label="nombre"
                                                        :options="arrayEmpresa"
                                                        placeholder="Buscar empresa..."
                                                        :onChange="getDatosEmpresa2"
                                                    ></v-select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-3 form-control-label" for="">Numero de dependientes económicos  <span style="color:red;" v-show="datos.empresa==0">(*)</span></label>
                                                <div class="col-md-2">
                                                    <input type="number" v-model="num_dep_economicos" maxlength="2" class="form-control"  min="0">
                                                </div>
                                            </div>
                                                

                                        </template>


                                    </div>

                                
                            <!-- fin del form solicitud de avaluo -->


                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" class="btn btn-primary" @click="storeLead()">Regisrar</button>
                        </div>
                    </div>
                      <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal consulta-->
    </main>
</template>

<script>
import vSelect from 'vue-select';
export default {
    
    data() {
        return{
            
            arrayLeads:[],
           
            myAlerts:{
                popAlert : function(title = 'Alert',type = "success", description =''){
                    swal({
                        title: title,
                        type: type,
                        text: description,
                        showConfirmButton:false,
                        timer:1500,
                    })
                }
            },

            id:0,
            paso:0,
            modal:0,
            tituloModal:'',
            editar:0,
            b_cliente:'',

            datos : [],
            arrayEmpresa: [],
            arrayColonias:[],
            arrayEstados:[],
            arrayCiudades:[],
            arrayCampanias:[],
            arrayFraccionamientos:[],
            arrayModelos:[],
            datos:[],
            
           
        }
    },
    computed:{

    },
    components:{
        vSelect
    },
    methods: {
        listarLeads (page){
            axios.get('/leads/index'+
                '?buscar='+this.b_cliente+
                '&page='+page
                
            ).then(
                response => this.arrayLeads = response.data
            ).catch(error => console.log(error));
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
        selectModelo(buscar){
                let me = this;
              
                me.arrayModelos=[];
                var url = '/select_modelo_proyecto2?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayModelos = respuesta.modelos;
                })
                .catch(function (error) {
                    console.log(error);
                });
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
        selectCampania(){
            let me = this;

            var url = '/campanias/campaniaActiva';
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayCampanias = respuesta;
                
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        getDatosEmpresa(val1){
            let me = this;
            me.loading = true;
            me.datos.empresa_coa = val1.nombre;
            me.datos.empresaCoa_id = val1.id;
            
        }, 
        getDatosEmpresa2(val1){
            let me = this;
            me.loading = true;
            me.datos.empresa_id = val1.id;
            me.datos.empresa = val1.nombre;
            
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
        selectCiudades(estado){
            let me = this;
            var url = '/select_ciudades?buscar='+estado;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayCiudades=[];
                me.arrayCiudades = respuesta.ciudades;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        selectColonias(cp,coacreditado){
            let me = this;
            me.arrayColonias=[];
            var url = '/select_colonias?buscar=' + cp;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayColonias=[];
                me.arrayColonias = respuesta.colonias;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        cerrarModal(){
            this.datos = [];
            this.modal = 0;
        },
        
        abrirModal(accion,data =[]){
            this.selectEstados();
            this.selectCampania();
            this.selectFraccionamientos();

            switch(accion){
                case 'actualizar':
                {
                    this.tituloModal='Actualizar Lead';
                    this.paso = 1;
                    this.modal = 1;
                    this.datos = data;
                    
                    break;
                }
            }
            

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

        formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },

    
        back(){
            this.editar = 0;

            
        }
    },
    mounted() {
        this.listarLeads(1);
    }
};
</script>
<style>
    .line-separator{
        height:1px;
        background:#717171;
        border-bottom:1px solid #c2cfd6;
    }
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
        overflow-y: auto;
    
    }
    .div-error {
    display: flex;
    justify-content: center;
    }
    .text-error {
    color: red !important;
    font-weight: bold;
    }
    
    .bg-gradient-primary {
        background: #00ADEF!important;
        background: linear-gradient(45deg,#321fdb 0%,#00ADEF 100%)!important;
        border-color: #00ADEF!important;
    }
    .p-3 {
        padding: 1rem!important;
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

