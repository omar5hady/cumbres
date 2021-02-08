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
                        <i class="fa fa-align-justify"></i> Créditos Puente
                        
                    </div>
                    <!-- Div Card Body para listar -->
                    <template v-if="listado == 1">
                        <div class="card-body"> 
                            <div class="form-group row">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <!--Criterios para el listado de busqueda -->
                                        
                                        <select class="form-control" @keyup.enter="listarAvisos(1)" v-model="buscar" >
                                            <option value="">Seleccione</option>
                                            <option v-for="proyecto in arrayProyectos" :key="proyecto.id" :value="proyecto.id" v-text="proyecto.nombre"></option>
                                        </select>
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
                                        <button type="submit" @click="listarAvisos(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table2 table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Folio</th>
                                            <th>Banco</th>
                                            <th>Tasa de interes</th>
                                            <th>Apertura</th>
                                            <th>Total</th>
                                            <th>Fecha solicitud</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-on:dblclick="verAviso(creditosPuente.id)" v-for="creditosPuente in arrayCreditosPuente" :key="creditosPuente.id" title="Ver detalle">
                                            <td>
                                                <button type="button" class="btn btn-warning btn-sm" @click="actualizarContrato(creditosPuente.id)">
                                                    <i class="icon-pencil"></i>
                                                </button>
                                            </td>
                                            <td>
                                                <a href="#" v-text="creditosPuente.clave"></a>
                                            </td>
                                            <td class="td2" v-text="creditosPuente.contratista"></td>
                                            <td class="td2" v-text="creditosPuente.proyecto"></td>
                                            <td class="td2">{{formatNumber(creditosPuente.total_superficie)}} m&sup2;</td>
                                            <td class="td2" v-text="'$'+formatNumber(creditosPuente.total_importe)"></td>
                                            <td class="td2" v-text="creditosPuente.f_ini"></td>
                                            <td class="td2" v-text="creditosPuente.f_fin"></td>
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
                    </template>

                    <!-- Div Card Body para actualizar registros -->
                    <template v-else-if="listado == 3">
                        <div class="card-body"> 
                            <div class="form-group row border">
                                <div class="col-md-3">
                                    <label for=""># Folio </label>
                                    <input type="text" class="form-control" v-model="clave" placeholder="CLV-00-00">
                                </div> 
                                <div class="col-md-5">
                                    <label for="">Banco </label>
                                    <input type="text" class="form-control" v-model="f_ini">
                                </div>
                                <div class="col-md-4">
                                    <label for="">Fecha de solicitud</label>
                                    <input type="date" class="form-control" v-model="f_fin">
                                </div>
                                <div class="col-md-3">
                                    <label for="">Tasa de Interés </label>
                                    <input type="number" pattern="\d*" class="form-control" min="0" max="100" v-model="anticipo" v-on:keypress="isNumber($event)">
                                </div>
                                <div class="col-md-3">
                                    <label for="">Apertura </label>
                                    <input type="number" class="form-control" min="0" max="100" v-model="costo_indirecto_porcentaje" v-on:keypress="isNumber($event)">
                                </div>
                                <div class="col-md-6"></div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Fraccionamiento </label>
                                        <input type="text" class="form-control" readonly  v-model="fraccionamiento">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Etapa </label>
                                        <input type="text" class="form-control" readonly  v-model="etapa">
                                    </div>
                                </div>

                               
                                 <div class="col-md-12">
                                    <!-- Div para mostrar los errores que mande validerFraccionamiento -->
                                    <div v-show="errorcreditosPuente" class="form-group row div-error">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMostrarMsjcreditosPuente" :key="error" v-text="error">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row border">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Lote</label> 
                                        <div class="form-inline">
                                            <select class="form-control" v-model="lote_id" @click="selectDatosLotes(lote_id)">
                                                <option value="0">Seleccione</option>
                                                <option v-for="lotes in arrayLotes" :key="lotes.id" :value="lotes.id" v-text="lotes.num_lote"></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Manzana</label>
                                        <input type="text" class="form-control" v-model="manzana"  placeholder="Manzana">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Modelo</label>
                                        <input type="text" class="form-control" v-model="modelo" placeholder="Modelo">
                                    </div>
                                </div>
                                

                                <div class="col-md-1">
                                    <div class="form-group">
                                        <button @click="registrarLote()" class="btn btn-success form-control btnagregar"><i class="icon-plus"></i></button>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="form-group row">
                                <div class="table-responsive col-md-12">
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Lote</th>
                                                <th>Modelo</th>
                                                <th>Manzana</th>
                                                <th>M&sup2;</th>
                                                <th>Precio</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="arrayCreditosPuenteLotes.length">
                                            <tr v-for="detalle in arrayCreditosPuenteLotes" :key="detalle.id">
                                                <td>
                                                    <button @click="eliminarLote(detalle)" type="button" class="btn btn-danger btn-sm">
                                                        <i class="icon-close"></i>
                                                    </button>
                                                </td>
                                                <td v-text="detalle.lote"></td>
                                                <td v-text="detalle.descripcion"></td>
                                                
                                                <td v-text="detalle.manzana"></td>
                                                <td style="text-align: right;" v-text="detalle.construccion"></td>
                                            </tr>
                                  
                                            <tr style="background-color: #CEECF5;">
                                                <td align="right" colspan="4"></td>
                                                <td align="right"> <strong>{{ total_construccion=totalConstruccion}} m&sup2;</strong> </td>
                                                <td align="right"> <strong>{{ total_costo_directo=totalCostoDirecto | currency}}</strong> </td>
                                            </tr>
                                        </tbody>

                                        <tbody v-else>
                                            <tr>
                                                <td colspan="6">
                                                    No hay lotes seleccionados
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-secondary" @click="ocultarDetalle()"> Cerrar </button>
                                    <button type="button" class="btn btn-primary" @click="actualizarcreditosPuente()"> Actualizar </button>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!--Div para ver detalle del aviso -->
                    <template v-else-if="listado == 2">
                        <div class="card-body"> 
                            <div class="form-group row border">
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label style="color:#2271b3;" for=""><strong> Contratista </strong></label>
                                        <p v-text="contratista"></p>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label style="color:#2271b3;" for=""><strong>Clave</strong> </label>
                                    <p v-text="clave"></p>
                                </div> 
                                <div class="col-md-3">
                                    <label style="color:#2271b3;" for=""><strong>Fecha de inicio</strong></label>
                                    <p v-text="f_ini"></p>
                                </div>
                                <div class="col-md-3">
                                    <label style="color:#2271b3;" for=""><strong>Fecha de termino </strong></label>
                                    <p v-text="f_fin"></p>
                                </div>
                                <div class="col-md-3">
                                    <label style="color:#2271b3;" for=""><strong>% Anticipo </strong></label>
                                    <p v-text="anticipo+'%'"></p>
                                </div>
                                <div class="col-md-3">
                                    <label style="color:#2271b3;" for=""><strong>% Costo Indirecto </strong></label>
                                    <p v-text="costo_indirecto_porcentaje+'%'"></p>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label style="color:#2271b3;" for=""><strong>Fraccionamiento </strong></label>
                                        <p v-text="fraccionamiento"></p>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label style="color:#2271b3;"><strong>Total de Anticipo</strong></label>
                                        <div class="form-inline">
                                        <p v-text="'$'+formatNumber(total_anticipo)"></p>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>

                            <div class="form-group row">
                                <div class="table-responsive col-md-12">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Descripcion</th>
                                                <th>Lote</th>
                                                <th>Manzana</th>
                                                <th>M&sup2;</th>
                                                <th>Costo Directo</th>
                                                <th>Costo Indirecto</th>
                                                <th>Obra extra</th>
                                                <th>Importe</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="arrayCreditosPuenteLotes.length">
                                            <tr v-for="detalle in arrayCreditosPuenteLotes" :key="detalle.id">
                                                <td v-text="detalle.descripcion"></td>
                                                <td v-text="detalle.lote"></td>
                                                <td v-text="detalle.manzana"></td>
                                                <td style="text-align: right;" v-text="detalle.construccion"></td>
                                                <td style="text-align: right;" v-text="'$'+formatNumber(detalle.costo_directo)"></td>
                                                <td style="text-align: right;" v-text="'$'+formatNumber(detalle.costo_indirecto)"></td>
                                                <td style="text-align: right;" v-text="'$'+formatNumber(detalle.obra_extra)"></td>
                                                <td align="right">
                                                    {{'$'+formatNumber(parseFloat(detalle.costo_directo) + parseFloat(detalle.costo_indirecto))}}
                                                  <!-- <input readonly v-model="detalle.importe" type="text" class="form-control">  -->
                                                </td>
                                            </tr>
                                  
                                            <tr style="background-color: #CEECF5;">
                                                <td align="right" colspan="4"> <strong>{{ formatNumber(total_construccion)}}</strong> </td>
                                                <td align="right"> <strong>${{ formatNumber(total_costo_directo=totalCostoDirecto)}}</strong> </td>
                                                <td align="right"> <strong>${{ formatNumber(total_costo_indirecto=totalCostoIndirecto)}}</strong> </td>
                                                <td align="right" colspan="2"> <strong>${{ formatNumber(total_importe=totalImporte)}}</strong> </td>
                                            </tr>
                                        </tbody>

                                        <tbody v-else>
                                            <tr>
                                                <td colspan="7">
                                                    No hay lotes seleccionados
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-secondary" @click="ocultarDetalle()"> Cerrar </button>
                                </div>
                                   <div class="col-md-9">
                                    <a class="btn btn-success" v-bind:href="'/iniobra/relacion/excel/'+ id " >
                                        <i></i>Exportar relacion en excel
                                    </a>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-primary" @click="modal = 2"><i class="fa fa-print"></i>&nbsp; Imprimir Contrato</button>
                                </div>
                            </div>
                        </div>
                    </template>
                    
                </div>
                <!-- Fin ejemplo de tabla Listado -->
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
                aviso_id:0,
                contratista_id:0,
                etapa_id:0,
                fraccionamiento_id : 0,
                num_etapa : 0,
                calle1:'',
                calle2:'',
                descripcion_larga:'',
                descripcion_corta:'',
                tipo:'Vivienda',
                iva:0,
                clave:'',
                total_importe:0.0,
                total_costo_directo:0.0,
                total_costo_indirecto:0.0,
                total_construccion:0.0,
                anticipo:0,
                costo_indirecto_porcentaje:0,
                total_anticipo:0,
                f_ini : new Date().toISOString().substr(0, 10),
                f_fin : '',
                arrayCreditosPuente : [],
                arrayCreditosPuenteLotes : [],
                arrayContratista : [],
                modal : 0,
                pdf:'',
                listado:1,
                tituloModal : '',
                tipoAccion: 0,
                errorcreditosPuente : 0,
                errorMostrarMsjcreditosPuente : [],
                pagination : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'ini_obras.clave', 
                buscar : '',
                arrayProyectos : [],
                arrayFraccionamientos : [],
                arrayLotes:[],
                arrayEtapas: [],
                arrayDatosLotes: [],
                empresas:[],
                lote_id:0,
                lote:'',
                b_empresa:'',
                manzana:'',
                construccion:'',
                costo_directo:0,
                costo_indirecto:0,
                descripcion: '',
                manzana: '',
                modelo:'',
                importe: 0.0,
                contratista:'',
                fraccionamiento:'',
                empresa_constructora:'',
                
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
            totalCostoDirecto: function(){
                var resultado_costo_directo =0.0;
                for(var i=0;i<this.arrayCreditosPuenteLotes.length;i++){
                    resultado_costo_directo = parseFloat(resultado_costo_directo) + parseFloat(this.arrayCreditosPuenteLotes[i].costo_directo)
                }
                return Math.round(resultado_costo_directo*100)/100;
            },
            totalCostoIndirecto: function(){
                var resultado_costo_indirecto =0.0;
                for(var i=0;i<this.arrayCreditosPuenteLotes.length;i++){
                    resultado_costo_indirecto = parseFloat(resultado_costo_indirecto) + parseFloat(this.arrayCreditosPuenteLotes[i].costo_indirecto) 
                }
                return Math.round(resultado_costo_indirecto*100)/100;
            },
            totalImporte: function(){
                var resultado_importe_total =0.0;
                for(var i=0;i<this.arrayCreditosPuenteLotes.length;i++){
                    resultado_importe_total = parseFloat(resultado_importe_total) + parseFloat(this.arrayCreditosPuenteLotes[i].costo_directo) + parseFloat(this.arrayCreditosPuenteLotes[i].costo_indirecto)
                }
                return Math.round(resultado_importe_total*100)/100;
            },
            totalConstruccion: function(){
                var resultado_construccion_total =0.0;
                for(var i=0;i<this.arrayCreditosPuenteLotes.length;i++){
                    resultado_construccion_total = parseFloat(resultado_construccion_total) + parseFloat(this.arrayCreditosPuenteLotes[i].construccion)
                }
                return Math.round(resultado_construccion_total*100)/100;
            },
            totalSuperficie: function(){
                var resultado_construccion_total =0.0;
                for(var i=0;i<this.arrayCreditosPuenteLotes.length;i++){
                    resultado_construccion_total = parseFloat(resultado_construccion_total) + parseFloat(this.arrayCreditosPuenteLotes[i].superficie)
                }
                return Math.round(resultado_construccion_total*100)/100;
            }
        },
       
        methods : {
            
            /**Metodo para mostrar los registros */
            listarAvisos(page){
                let me = this;
                var url = '/iniobra?page=' + page;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayCreditosPuente = respuesta.ini_obra.data;
                    me.pagination = respuesta.pagination;
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
            selectFraccionamiento(search, loading){
                let me = this;
                loading(true)
                var url = '/select_fraccionamiento2?filtro='+search;
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    q: search
                    me.arrayFraccionamientos = respuesta.fraccionamientos;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectProyectos(){
                let me = this;
                me.arrayProyectos=[];
                var url = '/select_fraccionamiento';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayProyectos = respuesta.fraccionamientos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getDatosFraccionamiento(val1){
                let me = this;
                me.loading = true;
                me.fraccionamiento_id = val1.id;
                me.selectManzanaLotes(me.fraccionamiento_id);
            },
            selectManzanaLotes(buscar){
                let me = this;
              
                me.arrayEtapas=[];
                var url = '/select_manzana_lotes?buscar='+buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEtapas = respuesta.lotesManzana;
                    
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectLotes(buscar , buscar2){
                let me = this;
              
                me.arrayLotes=[];
                var url = '/select_lotes_obra?buscar='+buscar + '&buscar2=' + buscar2;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLotes = respuesta.lotes;
                    
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectDatosLotes(buscar){
                let me = this;
              
                me.arrayDatosLotes = [];
                var url = '/select_datos_lotes?buscar='+buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayDatosLotes = respuesta.lotesDatos;
                    me.lote = me.arrayDatosLotes[0].num_lote;
                    me.construccion = me.arrayDatosLotes[0].construccion;
                    me.manzana = me.arrayDatosLotes[0].manzana;
                    me.modelo=me.arrayDatosLotes[0].modelo;
                    me.descripcion=me.arrayDatosLotes[0].modelo;
                    me.empresa_constructora = me.arrayDatosLotes[0].emp_constructora;
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
                me.listarAvisos(page);
            },
            encuentra(id,emp_constructora){
                var sw=0;
                for(var i=0;i<this.arrayCreditosPuenteLotes.length;i++)
                {
                    if(this.arrayCreditosPuenteLotes[i].lote_id == id )
                    {
                        sw=true;
                    }
                    if(emp_constructora != '' )
                    if(this.arrayCreditosPuenteLotes[i].emp_constructora != emp_constructora){
                        sw=true;
                    }
                }
                return sw;
            },
            registrarLote(){
                let me = this;
                if(me.descripcion == '' || me.costo_directo==0 || me.costo_indirecto==0){
                }else{
                    if(me.encuentra(me.lote_id)){
                         swal({
                        type: 'error',
                        title: 'Error',
                        text: 'Este lote ya se encuentra agregado',
                        })
                    }else{
                    me.costo_indirecto = parseFloat(me.costo_directo) * parseFloat(me.costo_indirecto_porcentaje)/100;
                    me.importe = parseFloat(me.costo_directo) + parseFloat(me.costo_indirecto);
                   
                    axios.post('/iniobra/lote/registrar',{
                        'id': this.id,
                        'lote': this.lote,
                        'manzana' : this.manzana,
                        'modelo' : this.modelo,
                        'superficie' : this.construccion,
                        'costo_directo' : this.costo_directo,
                        'costo_indirecto' : this.costo_indirecto,
                        'importe' : this.importe,
                        'descripcion' : this.descripcion,
                        'lote_id' : this.lote_id,
                    }).then(function (response){
                        //Obtener detalle
                            me.arrayCreditosPuenteLotes=[];
                            var urld = '/iniobra/obtenerDetalles?id=' + me.id;
                            axios.get(urld).then(function (response) {
                                var respuesta = response.data;
                                me.arrayCreditosPuenteLotes = respuesta.detalles;
                            })
                            .catch(function (error) {
                                console.log(error);
                            });
                      
                    }).catch(function (error){
                        console.log(error);
                    });
                    me.lote = '';
                    me.lote_id =0;
                    me.construccion = 0;
                    me.manzana='';
                    me.descripcion='';
                    me.costo_directo = 0;
                    me.costo_indirecto = 0;
                    me.modelo='';
                    }
                }
            },
            eliminarLote(data =[]){
                //this.lote_id=data['id'];
                swal({
                title: '¿Desea remover este lote?',
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
                axios.delete('/iniobra/lote/eliminar', 
                        {params: {'id': data['id']}}).then(function (response){
                        
                         //Obtener detalle
                            me.arrayCreditosPuenteLotes=[];
                            var urld = '/iniobra/obtenerDetalles?id=' + me.id;
                            axios.get(urld).then(function (response) {
                                var respuesta = response.data;
                                me.arrayCreditosPuenteLotes = respuesta.detalles;
                            })
                            .catch(function (error) {
                                console.log(error);
                            });
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
             /**Metodo para actualizar  */
            actualizarcreditosPuente(){
                if(this.proceso==true){
                    return;
                }
                if(this.validarAviso()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }
                
                this.proceso=true;
                let me = this;
                me.total_anticipo=(me.anticipo/100)*me.total_importe;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.put('/iniobra/actualizar',{
                    'id':this.id,
                    'fraccionamiento_id': this.fraccionamiento_id,
                    'contratista_id': this.contratista_id,
                    'calle1': this.calle1,
                    'calle2': this.calle2,
                    'clave': this.clave,
                    'f_ini': this.f_ini,
                    'f_fin': this.f_fin,
                    'total_importe' :this.total_importe,
                    'total_costo_directo':this.total_costo_directo,
                    'total_costo_indirecto':this.total_costo_indirecto,
                    'anticipo':this.anticipo,
                    'total_anticipo':this.total_anticipo,
                    'data':this.arrayCreditosPuenteLotes,
                    'costo_indirecto_porcentaje':this.costo_indirecto_porcentaje,
                    'tipo':this.tipo,
                    'iva':this.iva,
                    'descripcion_larga':this.descripcion_larga,
                    'descripcion_corta':this.descripcion_corta,
                    'total_superficie':this.total_construccion
                }).then(function (response){
                    me.proceso=false;
                    me.listado=1;
                    me.limpiarDatos();
                    me.listarAvisos(1); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Contrato actualizado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            limpiarDatos(){
                this.contratista_id=0;
                this.f_fin='';
                this.clave='';
                this.calle1 = '';
                this.calle2 = '';
                this.fraccionamiento_id=0;
                this.anticipo=0;
                this.total_anticipo=0;
                this.total_importe=0;
                this.total_costo_directo=0;
                this.total_costo_indirecto=0;
                this.manzana='';
                this.lote='';
                this.modelo='';
                this.construccion=0;
                this.descripcion='';
                this.arrayCreditosPuenteLotes=[];
                this.arrayLotes=[];
                this.arrayDatosLotes=[];
                this.arrayEtapas=[];
                this.descripcion_larga='';
                this.descripcion_corta='';
                this.iva=0;
                this.tipo='Vivienda';
                this.total_construccion=0;
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
            mostrarDetalle(){
                this.limpiarDatos();
                this.listado=0;
            },
            ocultarDetalle(){
                this.listado=1;
            },
            verAviso(id){
                let me= this;
                this.listado=2;
                //Obtener datos de cabecera
                var arrayAvisoT=[];
                var url = '/iniobra/obtenerCabecera?id=' + id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayAvisoT = respuesta.ini_obra;
                    me.contratista= me.arrayAvisoT[0]['contratista'];
                    me.clave= me.arrayAvisoT[0]['clave'];
                    me.f_ini= me.arrayAvisoT[0]['f_ini'];
                    me.f_fin= me.arrayAvisoT[0]['f_fin'];
                    me.calle1 = me.arrayAvisoT[0]['calle1'];
                    me.calle2 = me.arrayAvisoT[0]['calle2'];
                    me.anticipo= me.arrayAvisoT[0]['anticipo'];
                    me.fraccionamiento= me.arrayAvisoT[0]['proyecto'];
                    me.total_anticipo = me.arrayAvisoT[0]['total_anticipo'];
                    me.costo_indirecto_porcentaje=me.arrayAvisoT[0]['costo_indirecto_porcentaje'];
                    me.total_construccion=me.arrayAvisoT[0]['total_superficie'];
                    me.id=id;
                })
                .catch(function (error) {
                    console.log(error);
                });
                //Obtener detalle
                var arrayAvisoT=[];
                var urld = '/iniobra/obtenerDetalles?id=' + id;
                axios.get(urld).then(function (response) {
                    var respuesta = response.data;
                    me.arrayCreditosPuenteLotes = respuesta.detalles;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            actualizarContrato(id){
                let me= this;
                this.listado=3;
                //Obtener datos de cabecera
                var arrayAvisoT=[];
                var url = '/iniobra/obtenerCabecera?id=' + id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayAvisoT = respuesta.ini_obra;
                    me.contratista_id= me.arrayAvisoT[0]['contratista_id'];
                    me.clave= me.arrayAvisoT[0]['clave'];
                    me.calle1 = me.arrayAvisoT[0]['calle1'];
                    me.calle2 = me.arrayAvisoT[0]['calle2'];
                    me.f_ini= me.arrayAvisoT[0]['f_ini'];
                    me.f_fin= me.arrayAvisoT[0]['f_fin'];
                    me.anticipo= me.arrayAvisoT[0]['anticipo'];
                    me.fraccionamiento_id= me.arrayAvisoT[0]['fraccionamiento_id'];
                    me.fraccionamiento= me.arrayAvisoT[0]['proyecto'];
                    me.total_anticipo = me.arrayAvisoT[0]['total_anticipo'];
                    me.costo_indirecto_porcentaje=me.arrayAvisoT[0]['costo_indirecto_porcentaje'];
                    me.contratista= me.arrayAvisoT[0]['contratista'];
                    me.descripcion_larga=me.arrayAvisoT[0]['descripcion_larga'];
                    me.descripcion_corta=me.arrayAvisoT[0]['descripcion_corta'];
                    me.tipo=me.arrayAvisoT[0]['tipo'];
                    me.iva=me.arrayAvisoT[0]['iva'];
                    me.selectManzanaLotes(me.fraccionamiento_id);
                    me.id=id;
                  
                })
                .catch(function (error) {
                    console.log(error);
                });
                //Obtener detalle
                var arrayAvisoT=[];
                var urld = '/iniobra/obtenerDetalles?id=' + id;
                axios.get(urld).then(function (response) {
                    var respuesta = response.data;
                    me.arrayCreditosPuenteLotes = respuesta.detalles;
                })
                .catch(function (error) {
                    console.log(error);
                });
            }
           
        },
        mounted() {
            this.listarAvisos(1);
            this.selectProyectos();
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