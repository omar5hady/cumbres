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
                        <div class="row">
                           
                            
                            <input type="text" v-model="b_cliente" @keyup.enter="listarLeads()" placeholder="Nombre Apellidos" class="form-control col-sm-4">

                            
                            <button @click="listarLeads()" class="btn btn-sm btn-primary col-sm-1">
                                <i class="fa fa-search"></i> Buscar
                            </button>
                            
                        </div>
                        <br>

                        <div class="row">
                            <table class="table table-bordered table-striped">
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
                                        <td style="width:15%">
                                            <button title="Editar" type="button" @click="abrirModal('actualizar',campania)" class="btn btn-warning btn-sm">
                                                <i class="icon-pencil"></i>
                                            </button>  
                                            <button type="button" class="btn btn-danger btn-sm" @click="eliminar(campania)" title="Eliminar">
                                                <i class="icon-trash"></i>
                                            </button>                                       
                                        </td>
                                        <td v-if="apellidos != null" v-text="lead.nombre + ' '+lead.apellidos"></td>
                                        <td v-else v-text="lead.nombre"></td>
                                        <td v-if="lead.celular != null"  v-text="lead.celular"></td>
                                       
                                    </tr>
                                </tbody>
                            </table>
                        </div>
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
                            <h4 class="modal-title" v-text="'Nueva solicitud de compra venta'"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- form para solicitud de avaluo -->

                                    <div class="">
                                        <div class="card-body">
                                            <ul class="nav nav-tabs">
                                                <li class="nav-item"><a class="nav-link"  v-bind:class="{ 'active': paso==1 }" @click="paso = 1">Datos del prospecto</a></li>
                                                <li v-if="datos.coacreditado == 1" class="nav-item"><a class="nav-link"  v-bind:class="{ 'active': paso==2 }" @click="paso = 2">Datos coacreditado</a></li>
                                                <li class="nav-item"><a class="nav-link"  v-bind:class="{ 'active': paso==3 }" @click="paso = 3">Referencias familiares</a></li>
                                            </ul>
                                        </div>

                                        <template v-if="paso == 1"> <!-- Datos del prospecto -->

                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Nombre: </label>
                                                <div class="col-md-4">
                                                    <input type="text" disabled v-model="datos.nombre" class="form-control" >
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" disabled v-model="datos.apellidos" class="form-control" >
                                                </div>
                                            </div>
                                            
                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Telefono: </label>
                                                <div class="col-md-3">
                                                    <input type="text" disabled v-model="datos.telefono" class="form-control" >
                                                </div>
                                                <label class="col-md-2 form-control-label" for="text-input">Celular: </label>
                                                <div class="col-md-3">
                                                    <input type="text" disabled v-model="datos.celular" class="form-control" >
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


                                        <template v-if="paso == 3"> <!-- Datos de referencias -->

                                                <div class="form-group row line-separator"></div>

                                                <div class="col-md-12">
                                                    <h6 align="center"><strong> Primera Referencia</strong></h6>
                                                </div>

                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Nombre:  <span style="color:red;" v-show="nombre_primera_ref ==''">(*)</span></label>
                                                <div class="col-md-5">
                                                    <input type="text" v-model="nombre_primera_ref" class="form-control" >
                                                </div>
                                            </div>

                                             <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Telefono:  <span style="color:red;" v-show="telefono_primera_ref ==''">(*)</span></label>
                                                <div class="col-md-3">
                                                    <input type="text" v-model="telefono_primera_ref"  pattern="\d*" 
                                                    maxlength="10" v-on:keypress="isNumber($event)" class="form-control" >
                                                </div>
                                                <label class="col-md-1 form-control-label" for="text-input">Cel:  <span style="color:red;" v-show="celular_primera_ref ==''">(*)</span></label>
                                                <div class="col-md-3">
                                                    <input type="text" v-model="celular_primera_ref"  pattern="\d*" 
                                                    maxlength="10" v-on:keypress="isNumber($event)" class="form-control" >
                                                </div>
                                            </div>

                                            <div class="form-group row line-separator"></div>

                                                <div class="col-md-12">
                                                    <h6 align="center"><strong> Segunda Referencia</strong></h6>
                                                </div>

                                            <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Nombre: <span style="color:red;" v-show="nombre_segunda_ref ==''">(*)</span></label>
                                                <div class="col-md-5">
                                                    <input type="text" v-model="nombre_segunda_ref" class="form-control" >
                                                </div>
                                            </div>

                                             <div class="form-group row">
                                                <label class="col-md-2 form-control-label" for="text-input">Telefono: <span style="color:red;" v-show="telefono_segunda_ref ==''">(*)</span> </label>
                                                <div class="col-md-3">
                                                    <input type="text" v-model="telefono_segunda_ref"  pattern="\d*" 
                                                    maxlength="10" v-on:keypress="isNumber($event)" class="form-control" >
                                                </div>
                                                <label class="col-md-1 form-control-label" for="text-input">Cel: <span style="color:red;" v-show="celular_segunda_ref ==''">(*)</span> </label>
                                                <div class="col-md-3">
                                                    <input type="text" v-model="celular_segunda_ref"  pattern="\d*" 
                                                    maxlength="10" v-on:keypress="isNumber($event)" class="form-control" >
                                                </div>
                                            </div>

                                            
                                                

                                        </template>
                                    </div>

                                
                            <!-- fin del form solicitud de avaluo -->


                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" class="btn btn-primary" @click="aprobar(id)">Asignar</button>
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
            editar:0,
            b_cliente:'',

            datos : [],
            arrayEmpresa: [],
            arrayColonias:[],
            tipo_economia:0,
            puesto:'',
            num_dep_economicos:0,
            nombre_primera_ref:'',
            nombre_segunda_ref:'',
            telefono_primera_ref:'',
            telefono_segunda_ref:'',
            celular_primera_ref:'',
            celular_segunda_ref:'',
           
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
        cerrarModal(){
            this.datos = [];
            this.modal = 0;
        },
        
        abrirModal(){
            this.selectEstados();

            this.id = cotizacion.id;
            this.paso = 1;
            this.modal = 1;
            this.datos = cotizacion;

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
    .card-user2 .avatar.border-white {
        border: 5px solid #fff;
    }
    .card-user2 .avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        position: relative;
        margin-bottom: 15px;
    }
    .card2 .avatar {
        width: 200px;
        height: 200px;
        overflow: hidden;
        border-radius: 50%;
        margin-right: 5px;
    }
    .border-white {
        border-color: #fff!important;
    }
    .card-user2 .author {
        text-align: center;
        text-transform: none;
        margin-top: -65px;
    }
    .card2 .author {
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }
    .card2 {
        border-radius: 6px;
        box-shadow: 0 2px 2px hsla(38,16%,76%,.5);
        background-color: #fff;
        color: #252422;
        margin-bottom: 20px;
        position: relative;
        z-index: 1;
        border: none;
    }
    .card2 .card-image2 img  {
        width: 100%;
    }
    img {
        vertical-align: middle;
        border-style: none;
    }
    .bg-gradient-primary {
        background: #00ADEF!important;
        background: linear-gradient(45deg,#321fdb 0%,#00ADEF 100%)!important;
        border-color: #00ADEF!important;
    }
    .p-3 {
        padding: 1rem!important;
    }
</style>

