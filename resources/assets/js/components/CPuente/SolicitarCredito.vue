<template >
    <main class="main" >
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card scroll-box">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i>Lotes sin Crédito Puente&nbsp;
                        <!--   Boton   -->
                        <button type="button" class="btn btn-success" @click="abrirModal()" >
                            <i class="icon-pencil"></i>&nbsp;Solicitar Crédito Puente
                        </button>
                        
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-10">
                                <div class="input-group">
                                    <select class="form-control"  v-model="buscar" @click="selectEtapa(buscar), selectModelo(buscar), selectPuente(buscar)" >
                                        <option value="">Proyecto</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>

                                    <select class="form-control"  v-model="buscar2" @keyup.enter="listarLote(1)"> 
                                        <option value="">Etapa</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row" >
                            <div class="col-md-8">
                                <div class="input-group">
                                    <select class="form-control"  v-model="b_modelo" @keyup.enter="listarLote(1)">
                                        <option value="">Modelo</option>
                                        <option v-for="modelos in arrayModelos" :key="modelos.id" :value="modelos.id" v-text="modelos.nombre"></option>
                                    </select>
                                    <input type="text"  v-model="buscar3" @keyup.enter="listarLote(1)" class="form-control" placeholder="Manzana">
                                    <input type="text"  v-model="b_lote" @keyup.enter="listarLote(1)" class="form-control" placeholder="Lote">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <select class="form-control" v-model="b_empresa" >
                                        <option value="">Empresa constructora</option>
                                        <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                    </select>
                                    <select class="form-control" v-model="b_empresa2" >
                                        <option value="">Empresa terreno</option>
                                        <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <select class="form-control" v-model="b_puente"> 
                                    <option value="">Credito Puente</option>
                                    <option v-for="puente in arrayPuentes" :key="puente.credito_puente" :value="puente.credito_puente" v-text="puente.credito_puente"></option>
                                </select>
                                <div class="input-group">
                                    <button type="submit" @click="listarLote(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    
                                    <span style="font-size: 1em; text-align:center;" class="badge badge-dark" v-text="'Total: '+ contador"> </span>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table2 table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" @click="selectAll" v-model="allSelected">
                                        </th>
                                        <th>Proyecto</th>
                                        <th>Etapa comercial</th>
                                        <th>Manzana</th>
                                        <th># Lote</th>
                                        <th>Sublote</th>
                                        <th>Modelo</th>
                                        <th>Calle</th>
                                        <th>Numero</th>
                                        <th>Interior</th>
                                        <th>Terreno mts&sup2;</th>
                                        <th>Credito puente</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="lote in arrayLote" :key="lote.id">
                                        <td class="td2">
                                            <input v-if="lote.credito_puente == null" type="checkbox"  @click="select" :id="lote.id" :value="lote.id" v-model="lotes_ini" >
                                        </td>

                                        <td class="td2" v-text="lote.proyecto"></td>
                                        <td class="td2">
                                            <span v-if = "lote.etapas!='Sin Asignar'" class="badge badge-success" v-text="lote.num_etapa"></span>
                                            <span v-else class="badge badge-danger"> Por Asignar </span>
                                        </td> 
                                        <td class="td2" v-text="lote.manzana"></td>
                                        <td class="td2" v-text="lote.num_lote"></td>
                                        <td class="td2" v-text="lote.sublote"></td>
                                        <td class="td2">
                                            <span v-if = "lote.modelo!='Por Asignar'" class="badge badge-success" v-text="lote.modelo"></span>
                                            <span v-else class="badge badge-danger"> Por Asignar </span>
                                        </td> 
                                        <td class="td2" v-text="lote.calle"></td>
                                        <td class="td2" v-text="lote.numero"></td>
                                        <td class="td2" v-text="lote.interior"></td>
                                        <td class="td2" v-text="lote.terreno"></td>
                                        <td class="td2" v-text="lote.credito_puente"></td>
                                    </tr>                               
                                </tbody>
                            </table> 
                        </div> 
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page > 7">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(1)">Inicio</a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1)">Sig</a>
                                </li>
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page<pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.last_page)">Ultimo</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>

            <!-- Modal para asignar modelo -->
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
                            <div >
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Institución Bancaria</label>
                                    <div class="col-md-6">
                                        <select class="form-control" v-model="banco">
                                            <option value="">Seleccione</option>
                                            <option v-for="banco in arrayBancos" :key="banco.nombre" :value="banco.nombre" v-text="banco.nombre"></option>
                                        </select>
                                    </div>
                                </div>
                            
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Tasa de interés </label>
                                    <div class="col-md-4">
                                        TIEE + <input class="form-control" type="number" v-model="tasa_interes">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Comisión por apertura </label>
                                    <div class="col-md-3">
                                        <input class="form-control" type="number" v-model="apertura">
                                    </div>
                                </div>
                            </div>

                            <div class="border">
                                 <div class="col-md-12">
                                    <h6> <center>Modelos del proyecto</center> </h6>
                                </div>
                                <br>
                                <div class="form-group row">
                                    <div class="col-md-3" v-for="modelo in arrayPreciosModelo" :key="modelo.id" v-if="modelo.ver > 0">
                                        <label class="col-md form-control-label" for="text-input">{{modelo.nombre}} </label>
                                        <div class="col-md">
                                            <input class="form-control" type="number" v-model="modelo.precio" @change="setPrice(modelo.id,modelo.precio)">
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>

                            <div class="border">
                                 <div class="col-md-12">
                                    <h6> <center>Lotes seleccionados ({{cantidad}})</center> </h6>
                                </div>
                            </div>

                            <div class="border">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <table class="table2 table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Proyecto</th>
                                                    <th>Etapa</th>
                                                    <th>Manzana</th>
                                                    <th>Modelo</th>
                                                    <th># Lote</th>
                                                    <th>Precio</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(lote,index) in lotesSelec" :key="lote.id">
                                                    <td class="td2">{{index+1}} </td>
                                                    <td class="td2" v-text="lote.proyecto"></td>
                                                    <td class="td2" v-text="lote.num_etapa"></td>
                                                    <td class="td2" v-text="lote.manzana"></td>
                                                    <td class="td2" v-text="lote.modelo"></td>
                                                    <td class="td2" v-text="lote.num_lote"></td>
                                                    <td class="td2" v-text="'$'+formatNumber(lote.precio)"></td>
                                                </tr>                       
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Botones del modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal2()">Cerrar</button>
                            <button type="button"  class="btn btn-primary" @click="solicitarCredito()">Solicitar</button>
                            <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
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
 import _ from 'lodash'
    export default {
        data(){
            return{
                proceso:false,
                allSelected: false,
                lotes_ini : [],
                b_modelo: '',
                b_lote: '',
                arrayLote : [],
                lotesSelec: [],
                arrayPuentes:[],
                modal2: 0,
                tituloModal2: '',
                contador: 0,
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                buscar2 : '',
                buscar3 : '',
                buscar : '',
                b_puente: '',

                banco :'',
                tasa_interes : 0,
                apertura:0,
                cantidad:0,
                fraccionamiento_id:'',
                etapa_id:'',
                proceso : false,

                total_precio:0,

                arrayFraccionamientos : [],
                arrayEtapas : [],
                arrayModelos : [],
                arrayEmpresas : [],
                arrayManzanas: [],
                arrayBancos:[],
                arrayPreciosModelo:[],
                empresas: [],
                b_empresa:'',
                b_empresa2:''
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

            arrayProyectos(){ 
                return _.uniqBy(this.arrayLote, 'proyecto');
            }

        },

        
        methods : {
            selectAll: function() {
                this.lotes_ini = [];

                if (!this.allSelected) {
                    for (var lote in this.arrayLote
                    ) {
                        this.lotes_ini.push(this.arrayLote[lote].id.toString());
                    }
                }
            },

            select: function() {
                this.allSelected = false;
            },
            selectPuente(id){
                let me = this;

                me.arrayPuentes=[];
                var url = '/selectCreditoPuente?fraccionamiento=' + id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPuentes = respuesta.creditos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            solicitarCredito(){
                if(this.proceso==true) //Se verifica si hay un error (campo vacio)
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
                    text: "Los lotes seran registrados en la solicitud del credito puente",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    
                    confirmButtonText: 'Si, continuar!'
                    }).then((result) => {

                    if (result.value) {
                        
                        //Con axios se llama el metodo store de DepartamentoController
                        axios.post('/cPuentes/storeSolicitud',{
                            'modelos': me.arrayPreciosModelo,
                            'lotes': me.lotesSelec,
                            'banco': me.banco,
                            'interes': me.tasa_interes,
                            'apertura': me.apertura,
                            'fraccionamiento': me.fraccionamiento_id,
                            'cantidad': me.cantidad,
                            'total': me.total_precio
                        }).then(function (response){
                            //console.log(response.data);
                            me.proceso=false;
                            me.cerrarModal2();
                            //Se muestra mensaje Success
                            swal({
                                position: 'top-end',
                                type: 'success',
                                title: 'Solicitud creada correctamente',
                                showConfirmButton: false,
                                timer: 1500
                                })
                        }).catch(function (error){
                            console.log(error);
                        });
                    }})
            },

            /**Metodo para mostrar los registros */
            listarLote(page){
                let me = this;
                var url = '/c_puente/indexSinCredito?page=' + page + '&proyecto=' + me.buscar + '&etapa=' + me.buscar2 + 
                    '&manzana=' + me.buscar3 + '&lote=' + me.b_lote + '&modelo=' + me.b_modelo + 
                    '&emp_constructora=' + me.b_empresa + '&emp_terreno=' + me.b_empresa2 + '&puente=' + me.b_puente;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLote = respuesta.lotes.data;
                    me.pagination = respuesta.pagination;
                    me.contador = respuesta.pagination.total;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            getEmpresa(){
                let me = this;
                me.empresas=[];
                var url = '/lotes/empresa/select';
                axios.get(url).then(function (response) {
                    var respuesta = response;
                    me.empresas = respuesta.data.empresas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            getLotes(){
                let me = this;

                me.lotesSelec = [];
                var url = '/cPuentes/getLotes';
                axios.get(url, {params: {'id': this.lotes_ini}}).then(function (response) {
                    var respuesta = response.data;
                    me.lotesSelec = respuesta.lotes;
                    me.lotesSelec.forEach(element=>{
                        element.precio = 0;
                    })
                    me.cantidad = me.lotesSelec.length;
                    me.fraccionamiento_id = me.lotesSelec[0].fraccionamiento_id;

                    me.getModelos(me.fraccionamiento_id);
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            setPrice(id,precio){
                let me = this;
                me.total_precio = 0;
                me.lotesSelec.forEach(element =>{
                    if(element.modelo_id == id)
                        element.precio = parseFloat(precio);
                    me.total_precio += element.precio;
                });
            },

            getModelos(id){
                let me = this;

                me.arrayPreciosModelo = [];
                var url = '/cPuentes/getModelosPuente';
                axios.get(url, {params: {'id': id}}).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPreciosModelo = respuesta.modelos;
                    me.arrayPreciosModelo.forEach(element=>{
                        element.precio = 0;
                        element.ver = 0;
                        me.lotesSelec.forEach(e=>{
                        if(e.modelo == element.nombre){
                            element.ver ++;
                        }
                        
                    })
                    })
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectBancos(){
                let me = this;
                me.arrayBancos=[];
                var url = '/select_inst_financiamiento';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayBancos = respuesta.instituciones_financiamiento;
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
                me.listarLote(page);
            },

            selectFraccionamientos(){
                let me = this;
                    me.buscar="";
                    me.buscar2="";
                    me.buscar3="";
                    me.b_empresa="";
                    me.b_empresa2="";
                    me.b_modelo='';
                    me.b_lote='';
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
                    me.buscar2=""
                    me.buscar3=""
                    me.b_modelo='';
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

            selectModelo(buscar){
                let me = this;
              
                me.arrayModelos=[];
                var url = '/select_modelo_proyecto?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayModelos = respuesta.modelos;
                    
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
            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
            abrirModal(){
                 if(this.lotes_ini.length<1){
                    Swal({
                    title: 'No se ha seleccionado ningun lote',
                    animation: false,
                    customClass: 'animated tada'
                    })
                    return;
                }
                this.modal2 = 1;
                this.tituloModal2 = 'Solicitar Crédito Puente';
                this.banco = '';
                this.apertura = 0;
                this.tasa_interes = 0;
                this.selectBancos();
                this.getLotes();
            },

            cerrarModal2(){
                this.modal2 = 0;
                this.tituloModal2 = '';
                this.lotes_ini=[];
                this.allSelected = false;
                this.banco = '';
                this.lotesSelec = [];
                this.tasa_interes = 0;
                this.apertura = 0;
                this.cantidad = 0;
                this.lotesSelec = [];
                this.fraccionamiento_id = '';

                this.listarLote(1)
            },

        },
        mounted() {
            this.listarLote(1);
            this.selectFraccionamientos();
            this.getEmpresa();
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
