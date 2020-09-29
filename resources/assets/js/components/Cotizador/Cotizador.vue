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
                    <i class="fa fa-align-justify"></i> Cotizador de lotes
                    &nbsp;
                </div>
                
                <div class="card-body">
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
                        <select v-on:change="getLotes(r_etapa)" class="form-control col-md-2" v-model="r_etapa">
                            <option value="">Etapa</option>
                            <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.num_etapa" v-text="etapas.num_etapa"></option>
                        </select>

                        <select class="form-control col-sm-2" v-model="r_lote" v-on:change="selccionaLote(r_lote)" >
                            <option value="">Lote</option>
                            <option v-for="lote in arrayLotes" :key="lote.id" :value="lote" v-text="lote.num_lote">lote 1</option>
                        </select>
                        
                        <div v-text="r_sup_terreno+' m²'" class="text-info form-control col-sm-2" title="Superficie de Terreno"></div>
                        <div v-text="'Costo m² $ '+r_valor_m2.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2})" class="text-info form-control col-sm-2" title="Costo m²"></div>
                        <div v-text="'Valor de Venta $ '+r_valor_venta.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2})" class="text-info form-control col-sm-3" title="Valor de Venta"></div>

                        <select class="form-control col-sm-2" v-on:change="generarLista()" v-model="r_mensualidad" title="Mensualidades" required>
                            <option value="" disabled selected>Pagos</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
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
                            <!--option value="40">40</option>
                            <option value="40">40</option>
                            <option value="40">40</option-->
                        </select>

                        <button @click="agregaCampoPago()" class="btn btn-sm btn-primary col-sm-2">Agregar pago</button>
                    </div>
                    <br>

                    <div class="row">
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <td class=" text-right">
                                        <strong v-text="'Saldo inicial $ '+r_valor_venta.toLocaleString('es-MX',{minimumFractionDigits:2,maximumFractionDigits:2})"></strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
                                    <td style="padding:0px;">
                                        <select v-model="pago.pago" v-on:keyup="calculaPrecoi(pago)" class="form-control">
                                            <option value="0">Enganche</option>
                                            <option v-for="n in arrayNMensualidad" :key="n.nMensualidad" :value="n.nMensualidad" v-text="'Mensualidad '+n.nMensualidad"></option>
                                        </select>
                                    </td>

                                    <template v-if="pago.folio != 1">
                                        <td style="padding:0px;" v-if="arrayMensualidad[pago.folio-2].cantidad !=0 && arrayMensualidad[pago.folio-2].cantidad !=''">
                                            <input v-model="pago.cantidad" v-on:keyup="calculaPrecoi(pago)" type="number" step=".01" class="form-control" style="height: 45px;">
                                        </td><td v-else></td>
                                    </template>
                                    <td style="padding:0px;" v-else>
                                        <input v-model="pago.cantidad" v-on:keyup="calculaPrecoi(pago)" type="number" step=".01" class="form-control" style="height: 45px;">
                                    </td>

                                    <td style="padding:0px;" class="text-center">
                                        <input v-model="pago.fecha" v-on:change="calculaPrecoi(pago)" type="date" class="form-control" style="height: 45px;">
                                    </td>
                                    <td>
                                        <span v-text="pago.dias" class="badge" v-bind:class="(pago.dias <= 180 && pago.dias > 0) ? 'badge-success' : 'badge-warning'"></span>
                                    </td>

                                    <td v-text="'%'+pago.interesesPor"></td>
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
                                            Nota: la presente cotización tiene vigencia de 14 días. Los gastos de escrituración son a cargo de la parte compradora.
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
                                    <td class="text-center"><strong>Menos de 10 días</strong></td>
                                    <td class="text-center"><strong>De 11 a 30 días</strong></td>
                                    <td class="text-center"><strong>De 31 a 60 días</strong></td>
                                    <td class="text-center"><strong>De 61 a 90 días</strong></td>
                                    <td class="text-center"><strong>De 91 a 120 días</strong></td>
                                    <td class="text-center"><strong>De 121 a 150 días</strong></td>
                                    <td class="text-center"><strong>De 151 a 180 días</strong></td>
                                    <td class="text-center"><strong>De 181 días a 365 días</strong></td>
                                    <td class="text-center"><strong>De 1 año a 2 años</strong></td>
                                    <td class="text-center"><strong>De más de 2 años a 3 años</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-center">%7 de Descuento</td>
                                    <td class="text-center">%6 de Descuento</td>
                                    <td class="text-center">%5 de Descuento</td>
                                    <td class="text-center">%4 de Descuento</td>
                                    <td class="text-center">%3 de Descuento</td>
                                    <td class="text-center">%2 de Descuento</td>
                                    <td class="text-center">%1 de Descuento</td>
                                    <td class="text-center">%11 de Interes de tasa anual</td>
                                    <td class="text-center">%15 de Interes de tasa anual</td>
                                    <td class="text-center">%18 de Interes de tasa anual</td>
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
            r_valor_venta:100000,
            valor_enganche:10000,
            valor_minMens:0,
            r_mensualidad:'',
            arrayMensualidad:[],
            arrayFraccionamientos:[],
            arrayEtapas:[],
            arrayLotes:[],
            arrayListA:[],
            arrayClientes:[],
            arrayNMensualidad:[],
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

            let fullPrice = this.r_valor_venta;

            if(this.r_fecha =="") this.r_fecha = moment().format('YYYY-MM-DD');
            
            if(this.r_mensualidad == 48){
                this.valor_minMens = (fullPrice-(fullPrice*0.3))/this.r_mensualidad;
                this.valor_enganche = fullPrice*0.3;
            }else this.valor_minMens = (fullPrice-10000)/this.r_mensualidad;

            for(let i=0; this.r_mensualidad >=i; i++){

                let monto = i?this.valor_minMens:this.valor_enganche;

                this.arrayMensualidad.push({
                    folio: i+1,
                    pago: i?i:0,
                    cantidad:monto,
                    fecha:'',
                    descuento:0,
                    dias:0,
                    interesesPor:0,
                    interesMont:0,
                    totalAPagar:0,
                    saldo:0
                });
            }

            if(this.r_mensualidad == 1) this.arrayMensualidad.pop();

            for(let i=0; this.r_mensualidad >i; i++){

                this.arrayNMensualidad.push({
                    nMensualidad: i+1,
                });
            }
        },
        calculaPrecoi(index){
            
            let cantidad = parseFloat(index.cantidad);
            let descuento = this.montoDescuento(index);
            let dias = this.dias(this.r_fecha, index.fecha);
            let folio = index.folio-1;

            this.arrayMensualidad[folio].cantidad = cantidad;
            this.arrayMensualidad[folio].fecha = index.fecha;
            //this.arrayMensualidad[folio].saldo = this.r_valor_venta-(cantidad + descuento + saldoPendiente);
            this.arrayMensualidad[folio].interesMont = 0;//this.intereses(index);

            this.arrayMensualidad[folio].totalAPagar = cantidad+this.arrayMensualidad[folio].interesMont;

            this.arrayMensualidad[folio].interesesPor = descuento[1];
            this.arrayMensualidad[folio].descuento = descuento[0];
            this.arrayMensualidad[folio].dias = dias;

            let saldo = this.r_valor_venta;
            this.arrayMensualidad.forEach(
                m => {
                    if(m.cantidad != 0){
                        saldo = saldo - (m.totalAPagar + m.descuento - m.interesMont);
                        m.saldo = saldo;
                    }
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

            if(dias > 0 && dias <= 10){
                descuento = this.arrayListA[0].valor;
                montoDescuento = this.descuento(datos.cantidad, descuento);

            }else if(dias > 10 && dias <= 30){
                descuento = this.arrayListA[1].valor;
                montoDescuento = this.descuento(datos.cantidad, descuento);

            }else if(dias > 30 && dias <= 60){
                descuento = this.arrayListA[2].valor;
                montoDescuento = this.descuento(datos.cantidad, descuento);

            }else if(dias > 60 && dias <= 90){
                descuento = this.arrayListA[3].valor;
                montoDescuento = this.descuento(datos.cantidad, descuento);

            }else if(dias > 90 && dias <= 120){
                descuento = this.arrayListA[4].valor;
                montoDescuento = this.descuento(datos.cantidad, descuento);

            }else if(dias > 120 && dias <= 150){
                descuento = this.arrayListA[5].valor;
                montoDescuento = this.descuento(datos.cantidad, descuento);

            }else if(dias > 150 && dias <= 180){
                descuento = this.arrayListA[6].valor;
                montoDescuento = this.descuento(datos.cantidad, descuento);
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

            let dias = this.dias(this.r_fecha, datos.fecha);
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

            if(dias > 180 && dias <= 365){
                ineres = this.arrayListA[7].valor/100;
                montoInteres = ((saldo*ineres)/365)*dias;
            }else if(dias > 365 && dias <= 730){
                ineres = this.arrayListA[8].valor/100;
                montoInteres = ((saldo*ineres)/365)*dias;
            }else if(dias > 730 && dias <= 1095){
                ineres = this.arrayListA[9].valor/100;
                montoInteres = ((saldo*ineres)/365)*dias;
            }

            montoInteres = Number(montoInteres.toFixed(2));

            return montoInteres;
        },
        actualizar(){
            this.arrayMensualidad.forEach(m => this.calculaPrecoi(m));
        },
        buscaLotes(){
            axios.get('/calc/lotes').then(
                response => this.arrayLotes = response.data
            ).catch(error => console.log(error));
        },
        selccionaLote(data){
            
            this.r_sup_terreno = data.terrenom2;
            this.r_valor_m2 = data.costom2;
            this.r_valor_venta = this.r_sup_terreno*this.r_valor_m2;
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
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        getDatosCliente(cliente){
            console.log(cliente);

            this.r_proyecto = cliente.proyecto_interes_id;
        },
        getLotes(etapa){
            axios.get("/get/lotes/lotes").then(
                response => {
                    this.arrayLotes = respose.data.num_lote;
                    console.log(response.data);
                }
            )
        },
        agregaCampoPago(){

            this.arrayMensualidad.push({
                folio: this.arrayMensualidad.length+1,
                pago: '',
                cantidad:0,
                fecha:'',
                descuento:0,
                dias:0,
                interesesPor:0,
                interesMont:0,
                totalAPagar:0,
                saldo:0
            });
        },
    },
    mounted() {
        this.selectFraccionamientos();
        this.buscaLotes();
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

