<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
        </ol>

        <div class="container-fluid">
            <div class="card scroll-box">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Cotizador de lotes
                    &nbsp;
                </div>
                
                <div class="card-body">
                    <!--FORMULARIO-->
                    <div class="row">
                        <input type="date" v-model="r_fecha" v-on:change="actualizar()" class="form-control col-sm-2 text-info">
                        
                        <div class="form-control col-md-6" style="padding:0px;">
                            <v-select 
                                :on-search="selectCliente"
                                label="n_completo"
                                :options="arrayClientes"
                                placeholder="Buscar Cliente"
                                :onChange="getDatosCliente"
                            >
                            </v-select>
                        </div>

                        <select class="form-control col-sm-2" v-model="r_proyecto" v-on:change="selectEtapa(r_proyecto)">
                            <option value="">Proyecto</option>
                            <option v-for="proj in arrayFraccionamientos" :key="proj.id" :value="proj.id" v-text="proj.nombre">proyecto 1</option>
                        </select>
                        <select v-on:change="buscaLotes(r_etapa)" class="form-control col-md-2" v-model="r_etapa">
                            <option value="">Etapa</option>
                            <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                        </select>

                        <select class="form-control col-sm-2" v-model="r_lote" v-on:change="selccionaLote(r_lote)" >
                            <option value="">Lote</option>
                            <option v-for="lote in arrayLotes" :key="lote.id" :value="lote" v-text="lote.num_lote">lote 1</option>
                        </select>
                        
                        <div v-text="r_sup_terreno+' m²'" class="text-info form-control col-sm-2" title="Superficie de Terreno"></div>
                        <div v-text="'Costo m² $ '+r_valor_m2.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2})" class="text-info form-control col-sm-2" title="Costo m²"></div>
                        <div v-text="'Valor de Venta $ '+r_valor_venta.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2})" class="text-info form-control col-sm-3" title="Valor de Venta"></div>

                        <select class="form-control col-sm-2" v-on:change="generarLista()" v-model="r_mensualidad" :disabled="r_valor_venta==0" title="Mensualidades" required>
                            <option value="" disabled selected>Pagos</option>
                            <option value="1">0 A 1 MES</option>
                            <option value="6">1 A 6 MESES</option>
                            <option value="12">7 A 12 MESES</option>
                            <option value="24">13 A 24 MESES</option>
                            <option value="36">25 A 36 MESES</option>
                            <option value="48">37 A 48 MESES</option>
                            <!--option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                                <option value="24">24</option>
                                <option value="25">25</option>
                                <option value="26">26</option>
                                <option value="27">27</option>
                                <option value="28">28</option>
                                <option value="29">29</option>
                                <option value="30">30</option>
                                <option value="31">31</option>
                                <option value="32">32</option>
                                <option value="33">33</option>
                                <option value="34">34</option>
                                <option value="35">35</option>
                                <option value="36">36</option>
                                <option value="37">37</option>
                                <option value="38">38</option>
                                <option value="39">39</option>
                                <option value="40">40</option>
                                <option value="41">41</option>
                                <option value="42">42</option>
                                <option value="43">43</option>
                                <option value="44">44</option>
                                <option value="45">45</option>
                                <option value="46">46</option>
                                <option value="47">47</option>
                                <option value="48">48</option>
                                <option value="40">40</option>
                                <option value="40">40</option>
                            <option value="40">40</option-->
                        </select>

                        <button @click="actualizar()" class="btn btn-sm btn-primary col-sm-1">Calcular</button>
                        <button @click="guardaCotizacion()" :disabled="(idCliente==0||arrayMensualidad.length==0)" class="btn btn-sm btn-warning col-sm-1">Guardar</button>
                        <button @click="generarPdf()" :disabled="(idCliente==0||arrayMensualidad.length==0)" class="btn btn-sm btn-success col-sm-1">
                            <i class="fa fa-file-pdf-o"></i> PDF
                        </button>
                        
                    </div>
                    <br>

                    <div class="row">
                        <!--TOTALES A PAGAR-->
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <td class=" text-right">
                                        <strong v-if="r_mensualidad != 1" v-text="'Saldo inicial $ '+r_valor_venta.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2})"></strong>
                                        <template v-else>
                                            <strong v-text="
                                                'Saldo inicial $ '
                                                +r_valor_venta.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2})
                                                +'  -  '"
                                            ></strong>
                                            <strong class="badge-warning" v-text="
                                                'Descuento $ '
                                                +r_valor_descuento.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2})
                                                +'  =  '"
                                            ></strong>
                                            <strong class="badge-info" v-text="
                                                'Total a Pagar $ '
                                                +(r_valor_venta-r_valor_descuento).toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2})
                                            "></strong>
                                        </template>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <!--PAGOS-->
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th># Pago</th>
                                    <th>Mensualidad</th>
                                    <th>Cantidad</th>
                                    <th>Fecha</th>
                                    <th>Dias</th>
                                    <th>% Descuento</th>
                                    <th>Descuento</th>
                                    <th>Interes</th>
                                    <th>Total a Pagar</th>
                                    <th>Saldo Pendiente</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="pago in arrayMensualidad" :key="pago.folio">
                                    <td v-text="pago.folio" class="text-info text-center">#</td>
                                    
                                    <td v-if="pago.pago == 0" >Enganche</td>
                                    <td v-else>Mensualidad</td>
                                    
                                    <td style="padding:0px;">
                                        <input v-model="pago.cantidad" v-on:keyup="calculaPrecoi(pago)" type="number" step=".01" class="form-control" style="height: 45px;">
                                    </td>

                                    <td style="padding:0px;" class="text-center">
                                        <input v-model="pago.fecha" v-on:change="calculaPrecoi(pago)" type="date" class="form-control" style="height: 45px;">
                                    </td>
                                    <td>
                                        <span v-text="pago.dias" class="badge" v-bind:class="!(r_mensualidad>6) ? 'badge-success' : 'badge-warning'"></span>
                                    </td>

                                    <td v-text="pago.interesesPor+'%'"></td>
                                    <td v-text="'$ '+pago.descuento.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2})">Descuento</td>
                                    <td v-text="'$ '+pago.interesMont.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2})">Interes</td>
                                    <td v-text="'$ '+pago.totalAPagar.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2})">Total a Pagar</td>
                                    <td v-text="'$ '+pago.saldo.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2})">Saldo</td>
                                </tr>
                            </tbody>
                        </table>

                        <br>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <td class="text-center" colspan="10">
                                        <strong class="badge badge-warning">
                                            Nota: la presente cotización tiene vigencia de 8 días hábiles posteriores a la emisión y el lote cotizado estará sujeto a disponibilidad.
                                        </strong>
                                    </td>
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <th colspan="10">Plan Comercial de Pagos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center"><strong>De 0 a 1 mes</strong></td>
                                    <td class="text-center"><strong>De 1 a 6 mes</strong></td>
                                    <td class="text-center"><strong>De 7 a 12 meses</strong></td>
                                    <td class="text-center"><strong>De 13 a 24 meses</strong></td>
                                    <td class="text-center"><strong>De 25 a 36 meses</strong></td>
                                    <td class="text-center"><strong>De 37 a 48 meses</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-center">0% de Interes de tasa anual</td>
                                    <td class="text-center">0% de Interes de tasa anual</td>
                                    <td class="text-center">12% de Interes de tasa anual</td>
                                    <td class="text-center">16% de Interes de tasa anual</td>
                                    <td class="text-center">18% de Interes de tasa anual</td>
                                    <td class="text-center">20% de Interes de tasa anual</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
    </main>
</template>

<script>
import vSelect from 'vue-select';
export default {
    
    data() {
        return{
            r_fecha:'',
            r_nombre:'',
            r_apellidos:'',
            r_proyecto:'',
            r_etapa:'',
            r_lote:'',
            r_sup_terreno:0,
            r_valor_m2:0,
            r_valor_venta:0,
            valor_enganche:0,
            valor_minMens:0,
            r_mensualidad:'',
            r_valor_descuento:0,
            idCliente:0,
            idLote:0,
            arrayMensualidad:[],
            arrayFraccionamientos:[],
            arrayEtapas:[],
            arrayLotes:[],
            arrayListA:[],
            arrayClientes:[],
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
        }
    },
    computed:{

    },
    components:{
        vSelect
    },
    methods: {
        generarLista(){
            this.arrayMensualidad=[];
            this.arrayNMensualidad=[];
            this.valor_enganche=10000;

            this.r_mensualidad==1?(
                this.r_valor_descuento = this.r_valor_venta*(this.arrayListA[0].valor/100)
            ):this.r_valor_descuento=0;

            let fullPrice = this.r_valor_venta-this.r_valor_descuento;
            let fechaPago = '';

            //asignacion de fecha actual
                if(this.r_fecha =="") this.r_fecha = moment().format('YYYY-MM-DD');
                fechaPago = new Date(
                    this.r_fecha.substring(5,7)
                    +'/'+this.r_fecha.substring(8,10)
                    +'/'+this.r_fecha.substring(0, 4)
                );
            //asignacion de fecha actual

            //asignacion de primer pago o enganche
                if(this.r_mensualidad == 48){
                    this.valor_minMens = (fullPrice-(fullPrice*0.3))/this.r_mensualidad;
                    this.valor_enganche = fullPrice*0.3;

                }else if(this.r_mensualidad == 1){  
                    this.valor_enganche = fullPrice.toFixed(2);

                }else this.valor_minMens = (fullPrice-10000)/this.r_mensualidad;
            //asignacion de primer pago o enganche

            //creacion de array vacio
            for(let i=0; this.r_mensualidad >=i; i++){
                
                let monto = 0.0;
                if(i == this.r_mensualidad && this.r_mensualidad != 1){
                    this.arrayMensualidad.forEach(item =>{
                        monto = monto+parseFloat(item.cantidad);
                    });
                    monto = (fullPrice-monto).toFixed(2);
                }else monto = i?this.valor_minMens:this.valor_enganche;

                //Setear Fecha de pago
                    let mes = '',dia = '';
                    if(i==1)
                        fechaPago.setDate(fechaPago.getDate()+10);
                    else if(i > 1) fechaPago.setMonth(fechaPago.getMonth()+1);
                    if(fechaPago.getMonth()<9)
                        mes = '0'+(fechaPago.getMonth()+1);
                    else mes = fechaPago.getMonth()+1;
                    if(fechaPago.getDate()<10)
                        dia = '0'+fechaPago.getDate();
                    else dia = fechaPago.getDate();
                    let fechaPagoFinal = fechaPago.getFullYear()+'-'+mes+'-'+dia;
                //Setear Fecha de pago fin

                this.arrayMensualidad.push({
                    folio: i+1,
                    pago: this.r_mensualidad==1?1:i?1:0,
                    cantidad:parseFloat(monto).toFixed(2),
                    fecha: fechaPagoFinal,
                    descuento:0,
                    dias:0,
                    interesesPor:0,
                    interesMont:0,
                    totalAPagar:0,
                    saldo:0
                });
            }
            
            //si la mensualidad es 1 se elimina un campo
            if(this.r_mensualidad == 1) this.arrayMensualidad.pop();

            //actualiza los precios
            this.actualizar();
        },
        calculaPrecoi(index){
            
            let cantidad = (index.cantidad=="")?0:parseFloat(index.cantidad);
            let descuento = this.montoDescuento(index);
            let dias = 0;//this.dias(this.r_fecha, index.fecha);
            if(index.folio == 1){
                dias = this.dias(this.r_fecha, index.fecha);
            }else{
                dias = this.dias(this.arrayMensualidad[parseInt(index.folio-2)].fecha, index.fecha);
            }
            let folio = index.folio-1;

            //fechas de pago
                let firstFechaPago = new Date(
                    index.fecha.substring(5,7)
                    +'/'+index.fecha.substring(8,10)
                    +'/'+index.fecha.substring(0, 4)
                );
                let lastFechaPago = new Date(
                    this.r_fecha.substring(5,7)
                    +'/'+this.r_fecha.substring(8,10)
                    +'/'+this.r_fecha.substring(0, 4)
                );
                let fechaFinalPago = index.fecha;

                lastFechaPago.setMonth(lastFechaPago.getMonth()+parseInt(this.r_mensualidad));

                if(lastFechaPago < firstFechaPago){
                    let dia = (lastFechaPago.getDate()<10)?('0'+lastFechaPago.getDate()):('0'+lastFechaPago.getDate());
                    let mes = (lastFechaPago.getMonth()<9)?('0'+(lastFechaPago.getMonth()+1)):(lastFechaPago.getMonth()+1);

                    fechaFinalPago = lastFechaPago.getFullYear()+'-'+mes+'-'+dia;
                }
            //fechas de pago

            this.arrayMensualidad[folio].cantidad = cantidad;
            this.arrayMensualidad[folio].fecha = fechaFinalPago;//index.fecha;

            if(this.r_mensualidad > 6) this.arrayMensualidad[folio].interesMont = this.intereses(index);

            this.arrayMensualidad[folio].totalAPagar = this.arrayMensualidad[folio].interesMont+cantidad;

            this.arrayMensualidad[folio].interesesPor = descuento[1];
            this.arrayMensualidad[folio].descuento = descuento[0];
            this.arrayMensualidad[folio].dias = dias;

            //Calcular saldo pendiente
            let saldo = this.r_valor_venta-this.r_valor_descuento;
            this.arrayMensualidad.forEach(
                m => {
                    saldo = saldo - ((m.totalAPagar + m.descuento) - m.interesMont);
                    m.saldo = saldo;
                }
            );
        },
        selectFraccionamientos(){
            let me = this;
            
            me.arrayFraccionamientos=[];
            var url = '/get/fraccionamientos/lotes';

            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayFraccionamientos = respuesta.fraccionamientos;
            }).catch(function (error) {
                console.log(error);
            });
        },
        selectEtapa(buscar){
            let me = this;
            me.buscar2=""
            
            me.arrayEtapas=[];
            var url = '/get/etapas/lotes?buscar=' + buscar;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayEtapas = respuesta.etapas;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        montoDescuento(datos){

            let dias = this.dias(this.r_fecha, datos.fecha)
            let montoDescuento = 0;
            let descuento = 0;

            let date = new Date(this.r_fecha);
            let days = this.daysInMonth(date.getMonth()+1, date.getFullYear());
            
            //if(this.r_mensualidad == 1 && this.arrayMensualidad[0].pago != 0){
            //    if(dias > 0 && dias <= days){
            //        descuento = this.arrayListA[0].valor;
            //        montoDescuento = this.descuento(datos.cantidad, descuento);
            //    }
            //}else 
            if(datos.pago == "0" && datos.cantidad > 10000 && dias <= 10 && this.r_mensualidad != 48){
                let engancheExed = datos.cantidad-10000;
                
                descuento = 4;
                montoDescuento = this.descuento(engancheExed, descuento);

            }else if(datos.pago == "0" && datos.cantidad > (this.r_valor_venta*.30) && dias <= 10){
                let engancheExed = datos.cantidad-(this.r_valor_venta*.30);
                
                descuento = 4;
                montoDescuento = this.descuento(engancheExed, descuento);
            }

            let array = [montoDescuento, descuento];
            //return montoDescuento;
            return array;
        },
        dias(startDate, endDate){

            if(startDate == '0000-00-00'|| startDate == "") startDate = '2000-01-01';
            if(endDate == '0000-00-00' || endDate == "") endDate = '2000-01-01';

            var from = moment(startDate),
                    to = moment(endDate),
                    days = 0;
                
            while (!from.isAfter(to,'day')) {
                // Si no es sabado ni domingo
                //if (from.isoWeekday() !== 6 && from.isoWeekday() !== 7) {
                    days++;
                //}
                from.add(1, 'days');
            }

            return days;
        },
        descuento(monto, descuento){
            let montoDesc = monto * (descuento/100);
            montoDesc = Number(montoDesc.toFixed(2));
            return montoDesc;
        },
        intereses(datos){

            if(datos.pago == 0) return 0;

            let dias = 0;//this.dias(this.r_fecha, datos.fecha);
            if(index.folio == 1){
                dias = this.dias(this.r_fecha, datos.fecha);
            }else{
                dias = this.dias(this.arrayMensualidad[parseInt(datos.folio-2)].fecha, datos.fecha);
            }
                
            let montoInteres = 0;

            let saldo = 0;
            this.arrayMensualidad.forEach(
                m => {
                    if(m.folio < datos.folio){
                        saldo = saldo + ((m.totalAPagar + m.descuento) - m.interesMont)
                    }
                }
            );

            saldo = this.r_valor_venta-saldo;

            let ineres = 0;

            if(this.r_mensualidad == 12){
                ineres = this.arrayListA[6].valor/100;
                montoInteres = ((saldo*ineres)/365)*dias;

            }else if(this.r_mensualidad == 24){
                ineres = this.arrayListA[7].valor/100;
                montoInteres = ((saldo*ineres)/365)*dias;

            }else if(this.r_mensualidad == 36){
                ineres = this.arrayListA[8].valor/100;
                montoInteres = ((saldo*ineres)/365)*dias;

            }else if(this.r_mensualidad == 48){
                ineres = this.arrayListA[9].valor/100;
                montoInteres = ((saldo*ineres)/365)*dias;
            }

            montoInteres = Number(montoInteres.toFixed(2));

            return montoInteres;
        },
        actualizar(){
            this.arrayMensualidad.forEach(m => this.calculaPrecoi(m));
        },
        buscaLotes(etapa){
            axios.get('/get/lotes/lotes?etapaId='+etapa).then(
                response => this.arrayLotes = response.data
            ).catch(error => console.log(error));
        },
        selccionaLote(data){
            this.r_sup_terreno = data.terreno;
            this.r_valor_m2 = data.precio_base/data.terreno;
            this.r_valor_venta = this.r_sup_terreno*this.r_valor_m2;
            this.idLote = data.id;

            if(this.r_mensualidad != "") this.generarLista();
        },
        listarPorcentajes(){
            axios.get('/calc/descuentos').then(
                response => this.arrayListA = response.data
            ).catch(error => console.log(error));
        },
        selectCliente(search, loading){
            let me = this;
            loading(true)
            
            var url = '/clientes?page=1&criterio=personal.nombre&b_clasificacion=2&buscar='+search;
            
            axios.get(url).then(function (response) {
                
                let respuesta = response.data;
                q: search
                me.arrayClientes = respuesta.personas.data;

                loading(false)
            }).catch(function (error) {
                console.log(error);
            });
        },
        getDatosCliente(cliente){
            this.idCliente = cliente.id;
        },
        daysInMonth (month, year) {
            return new Date(year, month, 0).getDate();
        },
        guardaCotizacion(){
            this.actualizar();
            
            Swal.fire({
                title: '¿Estas seguro?',
                text: "Este cambio no se podrá deshacer!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si guardar!',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.value) {
                    

                    axios.post('/calc/guardar/cotizacion',{
                        'pago':this.arrayMensualidad,
                        'idCliente':this.idCliente,
                        'idLote':this.idLote,
                        'valor_venta':this.r_valor_venta,
                        'valor_descuento':this.r_valor_descuento,
                        'fecha':this.r_fecha,
                        'mensualidades':this.r_mensualidad,
                    }).then(
                        rsponse => {
                            this.myAlerts.popAlert('Guardado correctamente');

                            this.arrayMensualidad = [];
                            this.r_lote = "";
                            this.r_lote = 0;
                            this.r_valor_m2 = 0;
                            this.r_valor_venta = 0;
                            this.valor_enganche = 0;
                            this.valor_minMens = 0;
                            this.r_mensualidad = 0;
                            this.r_valor_descuento = 0;
                            this.idLote = 0;
                            this.r_sup_terreno = 0;

                            return response.data;
                        }
                    ).catch(error => console.log(error));
                }
            });

        },
        generarPdf(){

            Swal.fire({
                title: '¿Guardar cotización y generar PDF?',
                text: "Este cambio no se podrá deshacer!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si guardar!',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.value) {

                    axios.post('/calc/guardar/cotizacion',{
                        'pago':this.arrayMensualidad,
                        'idCliente':this.idCliente,
                        'idLote':this.idLote,
                        'valor_venta':this.r_valor_venta,
                        'valor_descuento':this.r_valor_descuento,
                        'fecha':this.r_fecha,
                        'mensualidades':this.r_mensualidad,
                    }).then(
                        response => {
                            this.myAlerts.popAlert('Guardado correctamente');

                            this.arrayMensualidad = [];
                            this.r_lote = "";
                            this.r_lote = 0;
                            this.r_valor_m2 = 0;
                            this.r_valor_venta = 0;
                            this.valor_enganche = 0;
                            this.valor_minMens = 0;
                            this.r_mensualidad = 0;
                            this.r_valor_descuento = 0;
                            this.idLote = 0;
                            this.r_sup_terreno = 0;

                            //llamada a PDF
                            window.open('/calc/generar/pdf/'+response.data, '_blank');

                        }
                    ).catch(error => console.log(error));
                }
            });

        }
    },
    mounted() {
        this.selectFraccionamientos();
        this.listarPorcentajes();
    }
};
</script>
<style>
.modal-content {
  width: 100% !important;
  position: absolute !important;
}
.mostrar {
  display: list-item !important;
  opacity: 1 !important;
  position: fixed !important;
  background-color: #3c29297a !important;
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

