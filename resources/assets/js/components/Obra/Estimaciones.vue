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
                        <i class="fa fa-align-justify"></i> Estimaciones &nbsp;
                        <button v-if="listado == 1" type="button" @click="abrirModal('nuevo')" class="btn btn-primary">
                            <i class="icon-plus"></i>&nbsp;Asignar Partidas
                        </button>
                        <button v-if="listado==0" type="button" @click="indexEstimaciones(1),listado=1" class="btn btn-success">
                            <i class="fa fa-mail-reply"></i> Regresar
                        </button>
                    </div>

            <!----------------- Listado Contratos ------------------------------>
                    <!-- Div Card Body para listar -->
                    <template v-if="listado == 1">
                       <div class="card-body"> 
                            <div class="form-group row">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input  type="text" v-model="buscar" @keyup.enter="indexEstimaciones(1)" class="form-control" placeholder="Texto a buscar">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    
                                    <div class="input-group">
                                        <button type="submit" @click="indexEstimaciones(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table2 table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Clave</th>
                                            <th>Contratista</th>
                                            <th>Fraccionamiento</th>
                                            <th>Importe del contrato</th>
                                            <th>Fondo de Garantía a Retener</th>
                                            <!-- <th>Total por Pagar</th> -->
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="contrato in arrayEstimaciones" :key="contrato.id">
                                            <td class="td2">
                                                    <button type="button" title="Ver estimaciones" 
                                                        @click="verDetalle(contrato)" class="btn btn-primary btn-sm">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                </td>
                                            <td class="td2" v-text="contrato.clave"></td>
                                            <td class="td2" v-text="contrato.contratista"></td>
                                            <td class="td2" v-text="contrato.proyecto"></td>
                                            <td class="td2" v-text="'$'+formatNumber(contrato.total_importe)"></td>
                                            <td class="td2" v-text="'$'+formatNumber(contrato.garantia_ret)"></td>
                                        </tr>                               
                                    </tbody>
                                </table>
                            </div>
                            <nav>
                                <!--Botones de paginacion -->
                                <ul class="pagination">
                                    <li class="page-item" v-if="pagination2.current_page > 1">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page - 1)">Ant</a>
                                    </li>
                                    <li class="page-item" v-for="page2 in pagesNumber2" :key="page2" :class="[page2 == isActived2 ? 'active' : '']">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina2(page2)" v-text="page2"></a>
                                    </li>
                                    <li class="page-item" v-if="pagination2.current_page < pagination2.last_page">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page + 1)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </template>

                    <template v-if="listado == 0">
                       <div class="card-body"> 
                            <!-- <div class="form-group row border border-primary" style="margin-right:0px; margin-left:0px;"> -->


                                <div class="col-md-12">
                                    <div class="form-group">
                                        <center> <h6 style="color: #153157;">Contrato: {{clave}}</h6> </center>
                                    </div>
                                </div> 

                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Importe del Contrato</label>
                                        <div class="col-md-4">
                                             $ {{formatNumber(total_importe)}}
                                        </div>
                                    </div>
                                </div> 


                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Importe del Anticipo</label>
                                        <div class="col-md-4">
                                             $ {{formatNumber(total_anticipo)}}
                                        </div>
                                    </div>
                                </div> 

                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-md-2 form-control-label" for="text-input">Fonde de Garantía a Retención</label>
                                        <div class="col-md-4">
                                             $ {{formatNumber(importe_garantia)}}
                                        </div>

                                        <div class="col-md-1">
                                             
                                        </div>
                                        <label class="col-md-2 form-control-label" for="text-input">Número de casas</label>
                                        <div class="col-md-2">
                                             {{num_casas}}
                                        </div>
                                    </div>
                                </div>

                                <template v-if="nueva == 0">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-md-2 form-control-label" for="text-input">Número de estimacion</label>
                                            <div class="col-md-2">
                                                <select class="form-control" v-model="b_estimacion" @click="getPartidas(aviso_id), n_excel = b_estimacion">
                                                    <option value="">Seleccione</option>
                                                    <option v-for="estimacion in arrayNumEstim" :key="estimacion.num_estimacion" :value="estimacion.num_estimacion" v-text="estimacion.num_estimacion"></option>
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <button type="button" @click="nueva = 1"  class="btn btn-primary">
                                                    <i class="icon-plus"></i>&nbsp;Nueva estimación
                                                </button>
                                            </div>

                                            <div class="col-md-2">
                                                <a  :href="'/estimaciones/excelEstimaciones?clave='+aviso_id+'&numero='+numero+'&num_casas='+num_casas"  
                                                    class="btn btn-success"><i class="fa fa-file-text"></i> Excel
                                                </a>
                                            </div>

                                        </div>
                                    </div> 
                                </template> 

                                <template v-if="nueva == 1">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-md-2 form-control-label" for="text-input">Número de estimacion</label>
                                            <div class="col-md-2">
                                                <input type="number" disabled v-model="num_estimacion" min="0" step="1" class="form-control" placeholder="Núm. Estimación">
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <div class="col-md-2">
                                                        <button type="button" @click="storeEstimacion(arrayPartidas)"  class="btn btn-success">
                                                            <i class="icon-check"></i>&nbsp;Guardar
                                                        </button>
                                                    </div>

                                                    <div class="col-md-1"></div>
                                                    
                                                    <div class="col-md-2">
                                                        <button type="button" @click="nueva = 0"  class="btn btn-warning">
                                                            <i class="icon-close"></i>&nbsp;Cancelar
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div> 
                                </template>

                                <div class="table-responsive" >
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Paquete</th>
                                                <th>P.U. Prorrateado</th>
                                                <th>No. de Viviendas</th>
                                                <th colspan="2" v-if="numero != 0 && nueva == 0">Estimación No. {{numero}}</th>
                                                <th colspan="2" v-else-if="nueva == 1">Estimación No. {{num_estimacion}}</th>
                                                <th colspan="2">Cantidad Tope</th>
                                                <th colspan="2">Acumulado</th>
                                                <th colspan="2">Por Estimar</th>
                                                <!-- <th>Total por Pagar</th> -->
                                                
                                            </tr>
                                            <tr>
                                                <th colspan="12"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(partida,index) in arrayPartidas" :key="partida.id">
                                                <td class="td2" v-text="index+1"></td>
                                                <td class="td2" v-text="partida.partida"></td>
                                                <td class="td2" v-text="'$'+formatNumber(partida.pu_prorrateado)"></td>
                                                <td class="td2" v-text="num_casas"></td>
                                                <template v-if="nueva == 0">
                                                    <td class="td2" v-if="numero != 0" v-text="partida.vol"></td>
                                                    <td class="td2" v-if="numero != 0" v-text="'$' + formatNumber( partida.costoA )"></td>
                                                </template>
                                                <template v-else>
                                                    <td class="td2">
                                                        <input type="number" v-on:change="validar(index)"  v-model="partida.num_estimacion" min="0" step="1" class="form-control" placeholder="Núm. volumen">
                                                    </td>
                                                    <td class="td2" v-text="'$' + formatNumber( partida.costo = partida.num_estimacion * partida.pu_prorrateado )"></td>
                                                </template>
                                                
                                                <td class="td2" v-text="num_casas"></td>
                                                <td class="td2" v-text="'$'+ formatNumber( partida.tope=(partida.pu_prorrateado * num_casas) )"></td>
                                                <td class="td2"> 
                                                    {{ partida.acumVolTotal=parseInt(partida.acumVol) + parseInt(partida.num_estimacion) }} 
                                                </td>
                                                <td class="td2">
                                                    $ {{formatNumber( partida.acumCostoTotal=parseFloat(partida.acumCosto) + parseFloat(partida.costo) )}}
                                                </td>
                                                <td class="td2">
                                                    {{ partida.porEstimarVol=parseInt(num_casas) - parseInt(partida.num_estimacion) - parseInt(partida.acumVol) }}
                                                </td>
                                                <td class="td2">
                                                    $ {{formatNumber( partida.porEstimarCosto=parseFloat(partida.tope) - parseFloat(partida.acumCosto) - parseFloat(partida.costo) )}}
                                                </td>
                                            </tr>

                                            <tr>
                                                <th colspan="2" class="text-right">Gran Total: </th>
                                                <th class="td2">$ {{formatNumber(total1 = totalPU)}}</th>
                                                <template v-if="nueva == 0">
                                                    <template v-if="(total2 = totalEst2) > 0">
                                                        <th colspan="2"></th>
                                                        <th class="td2">$ {{formatNumber(total2)}}</th>
                                                    </template>
                                                    <template v-else>
                                                        <th></th>
                                                    </template>
                                                </template>
                                                <template v-else>
                                                    <th colspan="2"></th>
                                                    <th class="td2">$ {{formatNumber(total2 = totalEst)}}</th>
                                                </template>
                                                <th>Importe: </th>
                                                <th class="td2">$ {{formatNumber(total3 = totalTope)}}</th>
                                                <th></th>
                                                <th class="td2">$ {{formatNumber(to5al4 = totalAcum)}}</th>
                                                <th></th>
                                                <th class="td2">$ {{formatNumber(total3 = totalPorEst)}}</th>

                                            </tr>                         
                                        </tbody>
                                    </table>
                                </div>

                                                         
                                
                            <!-- </div> -->
                        </div>
                    </template>
                        
                    
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>

            <!-- Inicio Modal Asignar Partidas -->
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
                            <form method="post" @submit="formSubmit"  enctype="multipart/form-data">
                        
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">Contrato</label>
                                    <div class="col-md-4">
                                        <v-select 
                                            :on-search="getSinEstimaciones"
                                            label="clave"
                                            :options="arrayContratos"
                                            placeholder="Buscar contrato..."
                                            :onChange="getDatosContrato"
                                        >
                                        </v-select>
                                    </div>
                                </div>

                                <div class="form-group row" v-if="total_importe!=0">
                                    <div class="col-md-6">
                                        <h6 style="color: #153157;">Importe del Contrato  
                                            <strong> ${{formatNumber(total_importe)}} </strong>
                                        </h6>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label" for="text-input">% Garantía a retención</label>
                                    <div class="col-md-2">
                                        <input type="number" min="0" step="0.01" maxlength="5" style="right: 10px;" class="form-control" v-model="porcentaje_garantia">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <h6 >Fondo de Garantia a Retención:  
                                            <strong> ${{formatNumber(garantia_ret=(total_importe*porcentaje_garantia)/100)}} </strong>
                                        </h6>
                                    </div>
                                </div>

                                <div class="form-group row line-separator"></div>

                                <div class="form-group row">
                                    <div class="col-md-6">
                                         Selecciona archivo excel xls/csv: <input type="file" v-on:change="onImageChange" class="form-control">
                                    </div>
                                </div>

                                <input v-if="proceso == false && file!=''" type="submit" value="Cargar" class="btn btn-primary" style="margin-top: 3%">
                            
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
            <!--Fin del modal-->


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
                listado:1,
                nueva:0,
                arrayNumEstim:[],
                b_estimacion:'',
                num_estimacion:0,
                n_excel:0,
                id:0,
                aviso_id:0,
                proceso:false,
                arrayEstimaciones:[],
                
                arrayContratos:[],

                arrayPartidas:[],
                pagination2 : {
                    'total' : 0,         
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                buscar:'',
                clave:'',
                porcentaje_garantia:0,
                total_importe:0,
                garantia_ret:0,
                importe_garantia:0,
                num_casas:0,
                numero:0,
                actual:0,
                offset2 : 3,
                
                modal: 0,
                tituloModal: '',

                modal1:0,

                file:'',
                proceso:false,

                acumuladoVol:0,
                acumCosto:0,
                porEstimarVol:0,
                porEstimarCosto:0,

                total1:0,
                total2:0,
                total3:0,
                total4:0,
                total5:0
                               
               
               
            }
        },
        components:{
            vSelect
        },
        computed:{

            isActived2: function(){
                return this.pagination2.current_page;
            },

            //Calcula los elementos de la paginación
            pagesNumber2:function(){
                if(!this.pagination2.to){
                    return [];
                }

                var from = this.pagination2.current_page - this.offset2;
                if(from < 1){
                    from = 1;
                }

                var to = from + (this.offset2 * 2);
                if(to >= this.pagination2.last_page){
                    to = this.pagination2.last_page;
                }

                var pagesArray2 = [];
                while(from <= to){
                    pagesArray2.push(from);
                    from++;
                }
                return pagesArray2;
            },

            totalPU: function(){
                var total =0.0;
                for(var i=0;i<this.arrayPartidas.length;i++){
                    total += parseFloat(this.arrayPartidas[i].pu_prorrateado)
                }
                total = Math.round(total*100)/100;
                return total;
            },

            totalEst2: function(){
                var total =0.0;
                for(var i=0;i<this.arrayPartidas.length;i++){
                    total += parseFloat(this.arrayPartidas[i].costoA)
                }
                total = Math.round(total*100)/100;
                return total;
            },

            totalEst: function(){
                var total =0.0;
                for(var i=0;i<this.arrayPartidas.length;i++){
                    total += parseFloat(this.arrayPartidas[i].costo)
                }
                total = Math.round(total*100)/100;
                return total;
            },
            totalTope: function(){
                var total =0.0;
                for(var i=0;i<this.arrayPartidas.length;i++){
                    total += parseFloat(this.arrayPartidas[i].tope)
                }
                total = Math.round(total*100)/100;
                return total;
            },
            totalAcum: function(){
                var total =0.0;
                for(var i=0;i<this.arrayPartidas.length;i++){
                    total += parseFloat(this.arrayPartidas[i].acumCostoTotal)
                }
                total = Math.round(total*100)/100;
                return total;
            },
            totalPorEst: function(){
                var total =0.0;
                for(var i=0;i<this.arrayPartidas.length;i++){
                    total += parseFloat(this.arrayPartidas[i].porEstimarCosto)
                }
                total = Math.round(total*100)/100;
                return total;
            },
          
        },
       
        methods : {
            onImageChange(e){

                console.log(e.target.files[0]);

                this.file = e.target.files[0];

                if(this.file==''){
                    return;
                }

            },
           
            formSubmit(e) {

                if(this.proceso==true || this.file==''){
                    return;
                }

                this.proceso=true;

                e.preventDefault();
               
               let formData = new FormData();
                formData.append('file', this.file);
                formData.append('contrato', this.clave);
                formData.append('porcentaje_garantia', this.porcentaje_garantia);
                formData.append('garantia_ret', this.garantia_ret);
                let me = this;
                axios.post('/estimaciones/import',formData)
                .then(function (response) {
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Archivo cargado correctamente',
                        showConfirmButton: false,
                        timer: 2500
                        })
                    me.proceso=false;
                    me.cerrarModal();
                    me.indexEstimaciones(me.pagination2.current_page)
                    //me.listarLote(1,'','','','lote');

                })

                .catch(function (error) {

                  console.log(error);

                });

            },

            indexEstimaciones(page){
                let me = this;
                var url = '/estimaciones/indexEstimaciones?page=' + page + '&buscar=' + me.buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEstimaciones = respuesta.estimaciones.data;
                    me.pagination2 = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                })
            },

            getSinEstimaciones(search, loading){
                let me = this;
                loading(true)

                var url = '/estimaciones/getSinEstimaciones?buscar='+search;
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    q: search
                    me.arrayContratos = respuesta.contratos;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            getDatosContrato(val1){
                let me = this;
                me.loading = true;
                me.clave = val1.id;
                me.total_importe = val1.total_importe;
               
            },

            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
            cambiarPagina2(page){
                let me = this;
                //Actualiza la pagina actual
                me.pagination2.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.indexEstimaciones(page);
            },       

            cerrarModal(){
                this.modal = 0;
                this.modal1 = 0;
                this.tituloModal = '';

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

            getPartidas(id){
                let me = this;
                me.arrayCreditos=[];
                var url = '/estimaciones/getPartidas?clave='+id+'&numero='+this.b_estimacion;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPartidas = respuesta.estimaciones;
                    me.numero = respuesta.numero;
                    me.num_estimacion = respuesta.num_est;
                    me.arrayNumEstim = respuesta.numeros;
                    me.b_estimacion = me.arrayNumEstim[0].num_estimacion;
                    me.actual = respuesta.actual;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            validar(index){
                let me = this;
                var acumulado = parseInt (me.arrayPartidas[index].porEstimarVol);
                var volumen = 0;
                if(acumulado<0){
                    volumen = parseInt( me.arrayPartidas[index].num_estimacion );
                    me.arrayPartidas[index].num_estimacion = volumen + acumulado;
                }
                
            },

            verDetalle(contrato){
                
                this.clave = contrato['clave'];
                this.total_importe = contrato['total_importe'];
                this.total_anticipo = contrato['total_anticipo'];
                this.importe_garantia = contrato['garantia_ret'];
                this.listado = 0;
                this.num_casas = contrato['num_casas'];
                this.aviso_id = (contrato['id']);
                this.getPartidas(contrato['id']);
            },

            abrirModal(accion,data =[]){
                switch(accion){
                    case 'nuevo':
                    {
                        this.modal = 1;
                        this.tituloModal='Asignar Partidas';
                        this.total_importe=0;
                        this.arrayContratos=[];
                        this.porcentaje_garantia=0;
                        this.garantia_ret=0;
                        
                        break;
                    }
                    case 'detalle':{
                        
                        

                        break;
                    }
                    
                }
            },

            storeEstimacion(data){
                let me = this;
                //Con axios se llama el metodo update de LoteController
                
                Swal({
                    title: '¿Desea continuar?',
                    animation: false,
                    customClass: 'animated bounceInDown',
                    text: "Estos cambios no se pueden revertir",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    
                    confirmButtonText: 'Si, guardar!'
                    }).then((result) => {

                    if (result.value) {
                        me.arrayPartidas.forEach(element => {
                            if(element.num_estimacion != 0){
                                axios.post('/estimaciones/store',{
                                    'estimacion_id' : element.id,
                                    'costo' : element.costo,
                                    'vol' : element.num_estimacion,
                                    'num_estimacion' : this.num_estimacion
                                }); 
                                me.nueva = 0;
                                me.b_estimacion = '';
                                me.getPartidas(me.aviso_id);
                                Swal({
                                    title: 'Hecho!',
                                    text: 'Estimacion guardada correctamente',
                                    type: 'success',
                                    animation: false,
                                    customClass: 'animated bounceInRight'
                                })
                            }
                        });
                    }
                })
            }
        
        },
        mounted() {          
            this.indexEstimaciones(1);
        }
    }
</script>
<style>
    .modal-content{
        width: 100% !important;
        position: absolute !important;
       
    }
    .modal-body{
        height: 400px;
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
    
    .badge2 {
        display: inline-block;
        padding: 0.25em 0.4em;
        font-size: 85%;
        font-weight: bold;
        line-height: 1;
        color: #151b1f;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
    }
    .line-separator{
        height:1px;
        background:#717171;
        border-bottom:1px solid #c2cfd6;
    }

</style>
