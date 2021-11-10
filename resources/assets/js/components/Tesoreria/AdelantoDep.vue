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
                        <button v-if="listado==0" type="button" @click="listarContratos(1),limpiar()" class="btn btn-success">
                            <i class="fa fa-mail-reply"></i> Regresar
                        </button>
                    </div>

                    <template v-if="listado == 1">
                        <!-- listado de contratos -->
                        <div class="card-body">               
                            <div class="form-group row">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <select class="form-control" @change="selectEtapa(b_proyecto)" v-model="b_proyecto">
                                            <option value="">Fraccionamiento</option>
                                            <option v-for="proyecto in arrayFraccionamientos" :key="proyecto.id" :value="proyecto.id" v-text="proyecto.nombre"></option>
                                        </select>

                                        <select class="form-control" v-model="b_etapa" @change="b_manzana = '',b_lote = ''">
                                            <option value="">Etapa</option>
                                            <option v-for="etapa in arrayEtapas" :key="etapa.id" :value="etapa.id" v-text="etapa.num_etapa"></option>
                                        </select>
                                    </div>
                                    <div class="input-group">
                                        <input type="text" v-model="b_manzana" @keyup.enter="listarContratos(1)" class="form-control col-md-4" placeholder="Manzana">
                                        <input type="text" v-model="b_lote" @keyup.enter="listarContratos(1)" class="form-control col-md-3" placeholder="Lote">
                                    </div>
                                    <div class="input-group">
                                        <input type="text" v-model="b_cliente" @keyup.enter="listarContratos(1)" class="form-control col-md-8" placeholder="Cliente">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <button type="submit" @click="listarContratos()" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table2 table-bordered table-striped table-sm">
                                    <thead>
                                        <tr> 
                                            <th>Folio</th>
                                            <th>Cliente</th>
                                            <th>Fraccionamiento</th>
                                            <th>Etapa</th>
                                            <th>Manzana</th>
                                            <th>Lote</th>
                                            <th>Precio terreno</th>
                                            <th>Interés</th>
                                            <th>Saldo</th>
                                        </tr>
                                    </thead>
                                    <tbody v-for="contratos in arrayContratos.data" :key="contratos.folio">
                                        <tr v-if="contratos.liquidado != 1"> 
                                            <td class="td2" v-text="contratos.folio"></td>
                                            <td class="td2" @dblclick="verDetalle(contratos)">
                                                <a href="#" v-text="contratos.nombre.toUpperCase()+' '+contratos.apellidos.toUpperCase()"></a>
                                            </td>
                                            <td class="td2" v-text="contratos.proyecto"></td>
                                            <td class="td2" v-text="contratos.etapa"></td>
                                            <td class="td2" v-text="contratos.manzana"></td>
                                            <td class="td2" v-text="contratos.num_lote"></td>
                                            
                                            <td class="td2" v-text="'$'+formatNumber(contratos.precio_venta)"></td>
                                            <td class="td2" v-text="'$'+formatNumber(contratos.interes)"></td>
                                            <td class="td2" v-text="'$'+formatNumber(contratos.saldo)"></td>
                                        </tr>                               
                                    </tbody>
                                </table>  
                            </div>

                            <nav>
                                <!--Botones de paginacion -->
                                <ul class="pagination">
                                    <li class="page-item" @click="listarContratos(1)">
                                        <a class="page-link" href="#" >Inicio</a>
                                    </li>
                                    <li class="page-item" v-if="arrayContratos.current_page > 1"
                                        @click="listarContratos(arrayContratos.current_page-1)">
                                        <a class="page-link" href="#" >Ant</a>
                                    </li>

                                    <li class="page-item" v-if="arrayContratos.current_page-3 >= 1"
                                        @click="listarContratos(arrayContratos.current_page-3)">
                                        <a class="page-link" href="#" v-text="arrayContratos.current_page-3"></a>
                                    </li>
                                    <li class="page-item" v-if="arrayContratos.current_page-2 >= 1"
                                        @click="listarContratos(arrayContratos.current_page-2)">
                                        <a class="page-link" href="#" v-text="arrayContratos.current_page-2"></a>
                                    </li>
                                    <li class="page-item" v-if="arrayContratos.current_page-1 >= 1"
                                        @click="listarContratos(arrayContratos.current_page-1)">
                                        <a class="page-link" href="#" v-text="arrayContratos.current_page-1"></a>
                                    </li>
                                    <li class="page-item active" >
                                        <a class="page-link" href="#" v-text="arrayContratos.current_page"></a>
                                    </li>
                                    <li class="page-item" 
                                        v-if="arrayContratos.current_page+1 <= arrayContratos.last_page">
                                        <a class="page-link" href="#" @click="listarContratos(arrayContratos.current_page+1)" 
                                        v-text="arrayContratos.current_page+1"></a>
                                    </li>
                                    <li class="page-item" 
                                        v-if="arrayContratos.current_page+2 <= arrayContratos.last_page">
                                        <a class="page-link" href="#" @click="listarContratos(arrayContratos.current_page+2)"
                                        v-text="arrayContratos.current_page+2"></a>
                                    </li>
                                    <li class="page-item" 
                                        v-if="arrayContratos.current_page+3 <= arrayContratos.last_page">
                                        <a class="page-link" href="#" @click="listarContratos(arrayContratos.current_page+3)"
                                        v-text="arrayContratos.current_page+3"></a>
                                    </li>

                                    <li class="page-item" v-if="arrayContratos.current_page < arrayContratos.last_page"
                                        @click="listarContratos(arrayContratos.current_page+1)">
                                        <a class="page-link" href="#" >Sig</a>
                                    </li>
                                    <li class="page-item" @click="listarContratos(arrayContratos.last_page)">
                                        <a class="page-link" href="#" >Ultimo</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </template>

                    <template v-if="listado == 0">
                        <div class="card-body"> 
                            <div class="col-md-12">
                                <div class="form-group">
                                    <center> <h5 style="color: #153157;">Cliente: {{datosContrato.nombre}} {{datosContrato.apellidos}}</h5> </center>
                                </div>
                            </div> 

                            <div class="col-md-12">
                                <div class="form-group row">
                                    <h6 class="col-md-3" style="color: #40434c;">Proyecto: {{datosContrato.proyecto}}</h6>
                                    <h6 class="col-md-3" style="color: #40434c;">Etapa: {{datosContrato.etapa}}</h6>
                                    <h6 class="col-md-3" style="color: #40434c;">Manzana: {{datosContrato.manzana}}</h6>
                                    <h6 class="col-md-3" style="color: #40434c;">Lote: {{datosContrato.num_lote}}</h6>
                                </div>
                            </div> 

                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label class="col-md-2 form-control-label" for="text-input">Precio terreno:</label>
                                        <div class="col-md-4">
                                            $ {{formatNumber(datosContrato.precio_venta)}}
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <label class="col-md-2 form-control-label" for="text-input">Interés:</label>
                                        <div class="col-md-4">
                                            $ {{formatNumber(datosContrato.interes)}}
                                        </div>
                                    </div>
                                    
                                    <div class="input-group">
                                        <label class="col-md-2 form-control-label" for="text-input"><strong>Total</strong></label>
                                        <div class="col-md-4">
                                            <strong>
                                                $ {{formatNumber(datosContrato.precio_venta + datosContrato.interes)}}
                                            </strong>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                            
                            <div class="table-responsive" >
                                <table class="table2 table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Fecha de pago</th>
                                            <th>Monto a Capital</th>
                                            <th>Interés Ordinario</th>
                                            <th>Monto del pagare</th>
                                            <th>Restante</th>
                                            <th>Interés moratorio</th>
                                            <th v-if="ver == 1">Pagado</th>
                                        </tr>
                                        <tr>
                                            <th colspan="12"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="pago in pagosPendientes" :key="pago.id" 
                                        v-bind:style="{ backgroundColor : pago.atrasado == 1 ? '#e27f7f' : '#e8e8e8'}"
                                        >
                                            <td class="td2" v-text="pago.folio"></td>
                                            <td class="td2" v-text="pago.fecha_pago"></td>
                                            <td class="td2">$ {{formatNumber(pago.monto_pago - pago.interes_monto)}}</td>
                                            <td class="td2">$ {{formatNumber(pago.interes_monto)}}</td>
                                            <td class="td2">$ {{formatNumber(pago.monto_pago)}}</td>
                                            <th class="td2">$ {{formatNumber(pago.restante)}}</th>
                                            <td class="td2" v-if="editar == 0" @dblclick="editarMor()">$ {{formatNumber(pago.interes_mor)}}</td>
                                            <td class="td2" v-if="editar == 1">
                                                <input type="text" pattern="\d*" @keyup.enter="editar = 0" v-model="pago.interes_mor" :id="pago.id"  v-on:keypress="isNumber($event)" class="form-control" >
                                            </td>
                                            <td class="td2" v-if="ver == 1">
                                                <span class="badge badge-success" v-if="pago.pagado == 2"><i class="fa fa-check fa-3"></i></span>
                                                <span class="badge badge-primary" v-if="pago.pagado == 1"><i class="fa fa-check fa-3"></i></span>
                                            </td>
                                        </tr>
                                        <tr></tr>
                                        <tr>
                                            <th class="td2" colspan="2">Total:</th>
                                            <td class="td2">$ {{formatNumber(total_capital = totalCapital)}}</td>
                                            <td class="td2">$ {{formatNumber(total_interes = totalInteres)}}</td>
                                            <td class="td2">$ {{formatNumber(total_pago = totalPago)}}</td>
                                            <th class="td2">$ {{formatNumber(total_saldo = totalSaldo)}}</th>
                                            <th class="td2">$ {{formatNumber(total_moratorio = totalMoratorio)}}</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12"><br></div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-2"></div>
                                        <label class="col-md-2 form-control-label" for="text-input">Minimo a depositar:</label>
                                        <div class="col-md-3">
                                            $ {{formatNumber(min_dep = minDep)}}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2"></div>
                                        <label class="col-md-2 form-control-label" for="text-input">Monto a depositar:</label>
                                        <input type="text" v-on:keypress="isNumber($event),validarMonto()" v-model="monto_dep" class="form-control col-md-2" placeholder="Monto a depositar">
                                        <div class="col-md-3">
                                            $ {{formatNumber(monto_dep)}}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6"></div>
                                        <button class="btn btn-primary" v-if="monto_dep > 0 && monto_dep > min_dep" @click="calcular()"><i class="fa fa-calculator"></i>&nbsp;Calcular</button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" v-if="ver == 1">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-2"></div>
                                        <label class="col-md-2 form-control-label" for="text-input">Monto a capital:</label>
                                        <div class="col-md-3">
                                            <strong>$ {{formatNumber(monto_cap)}}</strong>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2"></div>
                                        <label class="col-md-2 form-control-label" for="text-input">Monto a interés:</label>
                                        <div class="col-md-3">
                                            $ {{formatNumber(monto_interes)}}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2"></div>
                                        <label class="col-md-2 form-control-label" for="text-input">Monto a interés moratorio:</label>
                                        <div class="col-md-3">
                                            $ {{formatNumber(monto_intMor)}}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-2"></div>
                                        <label class="col-md-2 form-control-label" for="text-input">Descuento:</label>
                                        <div class="col-md-3">
                                            $ {{formatNumber(desc_interes)}}
                                        </div>
                                    </div>
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
    export default {
        data(){
            return{
                proceso:false,
                id: 0,
                arrayContratos : [],
                arrayCuentas : [],
                arrayFraccionamientos : [],
                arrayEtapas : [],
                pagosPendientes : [],
                b_proyecto : '',
                b_etapa : '',
                b_manzana : '',
                b_lote : '',
                b_cliente : '',

                total_capital:0,
                total_interes:0,
                total_pago:0,
                total_saldo:0,
                total_moratorio:0,

                min_dep:0,

                monto_dep : 0,
                monto_cap : 0,
                monto_interes : 0,
                monto_intMor : 0,
                desc_interes : 0,
                ver:0,
                editar:0,

                listado : 1,
                datosContrato:{
                    'folio':'',
                    'nombre':'',
                    'apellidos':'',
                    'saldo':'',
                    'precio_venta':'',
                    'interes':'',
                    'proyecto':'',
                    'etapa':'',
                    'manzana':'',
                    'num_lote':0,
                },
            }
        },
        computed:{
            totalCapital: function(){
                var montoPagare = 0.0;
                var montoInteres = 0.0;
                var total = 0.0;
                for(var i=0;i<this.pagosPendientes.length;i++){
                    montoPagare += parseFloat(this.pagosPendientes[i].monto_pago)
                    montoInteres += parseFloat(this.pagosPendientes[i].interes_monto)
                }
                total = montoPagare - montoInteres;
                return total;
            },
            totalInteres: function(){
                var montoInteres = 0.0;
                for(var i=0;i<this.pagosPendientes.length;i++){
                    montoInteres += parseFloat(this.pagosPendientes[i].interes_monto)
                }
                return montoInteres;
            },
            totalPago: function(){
                var montoPagare = 0.0;
                for(var i=0;i<this.pagosPendientes.length;i++){
                    montoPagare += parseFloat(this.pagosPendientes[i].monto_pago)
                }
                return montoPagare;
            },
            totalSaldo: function(){
                var total = 0.0;
                for(var i=0;i<this.pagosPendientes.length;i++){
                    total += parseFloat(this.pagosPendientes[i].restante)
                }
                return total;
            },
            totalMoratorio: function(){
                var total = 0.0;
                for(var i=0;i<this.pagosPendientes.length;i++){
                    total += parseFloat(this.pagosPendientes[i].interes_mor)
                }
                return total;
            },
            minDep: function(){
                var total = 0.0;
                for(var i=0;i<this.pagosPendientes.length;i++){
                    if(this.pagosPendientes[i].atrasado == 1){
                        total += parseFloat(this.pagosPendientes[i].interes_mor)
                        total += parseFloat(this.pagosPendientes[i].restante)
                    }
                }
                return total;
            }
        },
        methods : {
            /**Metodo para mostrar los registros */
            listarContratos(page){
                let me = this;
                me.listado = 1;
                me.ver = 0;
                me.monto_dep = 0;
                var url = '/terrenos/getTerrenosAdeudo?page=' + page 
                    + '&cliente=' + me.b_cliente 
                    + '&proyecto=' + me.b_proyecto
                    + '&etapa=' + me.b_etapa
                    + '&manzana=' + me.b_manzana
                    + '&lote=' + me.b_lote;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayContratos = respuesta;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            limpiar(){
                this.total_capital = 0;
                this.total_interes = 0;
                this.total_pago = 0;
                this.total_saldo = 0;
                this.total_moratorio = 0;
                this.monto_cap = 0;
                this.monto_interes  = 0;
                this.monto_intMor = 0;
                this.desc_interes  = 0;
            },
            editarMor(){
                let me = this;
                Swal({
                    title: '¿Desea continuar?',
                    animation: false,
                    customClass: 'animated bounceInDown',
                    text: "Se habilitara la edicion de interés moratorio",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    
                    confirmButtonText: 'Si!'
                    }).then((result) => {
                    
                        me.editar = 1;
                })
            },
            calcular(){
                this.limpiar();
                let me = this;
                let monto = 0;
                me.ver = 1;
                monto = me.monto_dep
                me.pagosPendientes.forEach(element => {
                    element.pagado = 0;
                    //Primero verifica el monto disponible a diferir
                    if(monto > 0){
                        //Validar si el pagare tiene atraso (moratorio)
                        if(element.atrasado == 1){
                            //Caso en el que el monto cubre el total del pagare con el interes moratorio.
                            if(monto >= (element.restante + element.interes_mor)){
                                me.monto_interes = me.monto_interes + element.interes_monto;
                                me.monto_intMor = me.monto_intMor + element.interes_mor;
                                me.monto_cap += (element.restante - element.interes_monto);
                                monto = monto - ( element.interes_monto + element.interes_mor) - (element.restante - element.interes_monto);

                                //El pagare se marca como pagado
                                element.pagado = 2;
                            }
                            //Caso en el que no cubre el total del pagare
                            else{
                                //Se valida si se puede pagar el monto del interes moratorio.
                                if(monto >= element.interes_mor)
                                {
                                    //pagare se marca como abonado.
                                    element.pagado = 1;

                                    me.monto_intMor = me.monto_intMor + element.interes_mor;
                                    monto = monto - element.interes_mor;
                                    //Se valida si el restante cubre el interes ordinario
                                    if(monto >= element.interes_monto){
                                        me.monto_interes = me.monto_interes + element.interes_monto;
                                        monto = monto - element.interes_monto;
                                        //Se valida si el restante cubre el monto a capital.
                                        if(monto > (element.restante - element.interes_monto)){
                                            me.monto_cap = me.monto_cap + (element.restante - element.interes_monto);
                                            monto = monto - (element.restante - element.interes_monto);
                                        }
                                        //No cubre el monto total a capital
                                        else{
                                            me.monto_cap = me.monto_cap + monto;
                                            monto = 0;
                                        }
                                    }
                                    // No cubre el interes ordinario
                                    else{
                                        me.monto_interes = me.monto_interes + monto;
                                        monto = 0;
                                    }
                                }
                                //No cubre el monto interes moratorio
                                else{
                                    me.monto_intMor = me.monto_intMor + monto;
                                    monto = 0;
                                }
                            }
                        }
                        else{
                            if(monto >= (element.restante - element.interes_monto)){
                                me.desc_interes = me.desc_interes + element.interes_monto;
                                me.monto_cap += (element.restante - element.interes_monto);
                                monto = monto - (element.restante - element.interes_monto);;

                                //El pagare se marca como pagado
                                element.pagado = 2;
                                element.restante = 0;
                            }
                            else{
                                let porcentaje = 0;
                                porcentaje = (monto)/(element.restante - element.interes_monto);
                                me.monto_cap += ((element.restante - element.interes_monto)*porcentaje);
                                me.desc_interes = me.desc_interes + (element.interes_monto*porcentaje);
                                element.pagado = 1;

                                element.abonado = ((element.restante - element.interes_monto)*porcentaje) +  (element.interes_monto*porcentaje);

                                monto = 0;
                            }
                        }
                    }
                });
            },
            validarMonto(){
                let me = this;

                let valorTotal = 0;
                valorTotal = me.total_capital + me.total_moratorio;

                me.pagosPendientes.forEach(element => {
                    if(element.atrasado == 1){
                        valorTotal = valorTotal - element.monto_pago + element.interes_monto;
                        valorTotal = valorTotal + element.restante;
                    }
                });

                if(me.monto_dep > valorTotal)
                    me.monto_dep = valorTotal.toFixed(2);
            },
            getPagosPendientes(id){
                let me = this;
                me.arrayFraccionamientos=[];
                var url = '/terrenos/getPagosPendientes?id='+id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.pagosPendientes = respuesta;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },

            isNumber: function(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46 ) {
                    evt.preventDefault();;
                } else {
                    return true;
                }
            },
            selectFraccionamientos(){
                let me = this;
                me.arrayFraccionamientos=[];
                var url = '/select_fraccionamiento?tipo_proyecto=';
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
                me.b_etapa = '';
                me.b_manzana = '';
                me.b_lote = '';
                
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

            selectCuenta(){
                let me = this;
                me.arrayCuentas = [];  
                var url = '/select_cuenta?empresa=';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayCuentas = respuesta.cuentas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            verDetalle(contrato){
                let me = this;
                me.datosContrato = contrato;
                me.listado = 0;
                me.getPagosPendientes(contrato.folio);
            }
        },
       
        mounted() {
            this.listarContratos(1);
            this.selectFraccionamientos();
            this.selectCuenta();
        }
    }
</script>
<style>
    .line-separator{
        height:1px;
        background:#717171;
        border-bottom:1px solid #c2cfd6;
    }
    .form-control:disabled, .form-control[readonly] {
        background-color: rgba(0, 0, 0, 0.06);
        opacity: 1;
        font-size: 1rem;
        color: #27417b;
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

    .badge2 {
        display: inline-block;
        padding: 0.25em 0.4em;
        font-size: 90%;
        font-weight: bold;
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
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
