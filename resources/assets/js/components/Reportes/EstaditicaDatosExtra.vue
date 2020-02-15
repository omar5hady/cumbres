<template>
<main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
    </ol>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="form-group row">
                    <div class="col-md-8">
                            <div class="input-group">
                                <select class="form-control" @click="selectEtapas(buscar)" v-model="buscar" >
                                    <option value="">Seleccione el proyecto</option>
                                    <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                </select>
                                <select class="form-control" v-if="buscar!=''" v-model="b_etapa" >
                                    <option value="">Etapa</option>
                                    <option v-for="etapa in arrayAllEtapas" :key="etapa.id" :value="etapa.id" v-text="etapa.num_etapa"></option>
                                </select>
                            <button type="button" @click="mostrarGraficos()" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                
                <div class="row" v-if="mostrar==1">
                    <div class="col-md-4">
                        <div class="input-group">
                            <table class="table table-bordered table-striped table-sm">
                                <tr>
                                    <th>Total de ventas</th>
                                    <th v-text="this.totalEntregas"></th>
                                </tr>
                                <tr>
                                    <th @click="loadGenero()" style="text-align: center;" colspan="2">
                                        <button v-if="ver_genero == 0" @click="loadGenero()" class="btn btn-dark btn-sm"> Genero </button>
                                        <button v-if="ver_genero == 1" @click="loadGenero()" class="btn btn-default btn-sm"> Genero </button></th>
                                </tr>
                                    <tr v-if="ver_genero == 1">
                                        <th>Hombres:</th>
                                        <th v-text="this.hombre"></th>
                                    </tr>
                                    <tr v-if="ver_genero == 1">
                                        <th>Mujeres:</th>
                                        <th v-text="this.mujer"></th>
                                    </tr>

                                <tr>
                                    <th @click="loadOrigen()" style="text-align: center;" colspan="2">
                                        <button v-if="ver_lugarNac == 0" @click="loadOrigen()" class="btn btn-dark btn-sm"> Lugar de nacimiento </button>
                                        <button v-if="ver_lugarNac == 1" @click="loadOrigen()" class="btn btn-default btn-sm"> Lugar de nacimiento </button></th>
                                </tr>
                                    <template v-if="ver_lugarNac == 1">
                                        <tr v-for="lugar in lugarCant" :key="lugar.lugar_nacimiento">
                                            <th v-text="lugar.lugar_nacimiento"></th>
                                            <th v-text="lugar.num"></th>
                                        </tr>
                                    </template>
                                    
                                    
                                    
                                <tr>
                                    <th @click="loadEdoCivil()" style="text-align: center;" colspan="2">
                                        <button v-if="ver_edoCivil == 0" @click="loadEdoCivil()" class="btn btn-dark btn-sm">Estado Civil</button>
                                        <button v-if="ver_edoCivil == 1" @click="loadEdoCivil()" class="btn btn-default btn-sm"> Estado Civil </button></th>
                                </tr>
                                    <tr v-if="ver_edoCivil == 1">
                                        <th>Casado - Separación de bienes:</th>
                                        <th v-text="this.sepBienes"></th>
                                    </tr>
                                    <tr v-if="ver_edoCivil == 1">
                                        <th>Casado - Sociedad Conyugal:</th>
                                        <th v-text="this.conyugal"></th>
                                    </tr>
                                    <tr v-if="ver_edoCivil == 1">
                                        <th>Divorciado:</th>
                                        <th v-text="this.divorciado"></th>
                                    </tr>
                                    <tr v-if="ver_edoCivil == 1">
                                        <th>Soltero:</th>
                                        <th v-text="this.soltero"></th>
                                    </tr>
                                    <tr v-if="ver_edoCivil == 1">
                                        <th>Unión libre:</th>
                                        <th v-text="this.unionLibre"></th>
                                    </tr>
                                    <tr v-if="ver_edoCivil == 1">
                                        <th>Viudo:</th>
                                        <th v-text="this.viudo"></th>
                                    </tr>
                                    <tr v-if="ver_edoCivil == 1">
                                        <th>Otro:</th>
                                        <th v-text="this.otro"></th>
                                    </tr>
                                    
                                <tr>
                                    <th @click="loadEdades()" style="text-align: center;" colspan="2">
                                        <button v-if="ver_edades == 0" @click="loadEdades()" class="btn btn-dark btn-sm">Edades de habitantes</button>
                                        <button v-if="ver_edades == 1" @click="loadEdades()" class="btn btn-default btn-sm">Edades de habitantes</button></th>
                                </tr>
                                    <tr v-if="ver_edades == 1">
                                        <th>Menores de 10 años:</th>
                                        <th v-text="this.edades[0].sum010"></th>
                                    </tr>
                                    <tr v-if="ver_edades == 1">
                                        <th>Entre 11 y 20 años</th>
                                        <th v-text="this.edades[0].sum1120"></th>
                                    </tr>
                                    <tr v-if="ver_edades == 1">
                                        <th>Mayores de 21</th>
                                        <th v-text="this.edades[0].sum21"></th>
                                    </tr>
                                <tr>
                                    <th @click="loadEdadesComprador()" style="text-align: center;" colspan="2">
                                        <button v-if="ver_edadesComp == 0" @click="loadEdadesComprador()" class="btn btn-dark btn-sm">Edades de comprador</button>
                                        <button v-if="ver_edadesComp == 1" @click="loadEdadesComprador()" class="btn btn-default btn-sm">Edades de comprador</button></th>
                                </tr>
                                    <tr v-if="ver_edadesComp == 1">
                                        <th>20 - 25 años:</th>
                                        <th v-text="this.rang1"></th>
                                    </tr>
                                    <tr v-if="ver_edadesComp == 1">
                                        <th>26 - 30 años:</th>
                                        <th v-text="this.rang2"></th>
                                    </tr>
                                    <tr v-if="ver_edadesComp == 1">
                                        <th>31 - 40 años:</th>
                                        <th v-text="this.rang3"></th>
                                    </tr>
                                    <tr v-if="ver_edadesComp == 1">
                                        <th>41 - 50 años:</th>
                                        <th v-text="this.rang4"></th>
                                    </tr>
                                    <tr v-if="ver_edadesComp == 1">
                                        <th>51 - 60 años:</th>
                                        <th v-text="this.rang5"></th>
                                    </tr>
                                    <tr v-if="ver_edadesComp == 1">
                                        <th>61 - 70 años:</th>
                                        <th v-text="this.rang6"></th>
                                    </tr>
                                    <tr v-if="ver_edadesComp == 1">
                                        <th>Mayor a 71:</th>
                                        <th v-text="this.rang7"></th>
                                    </tr>
                                <tr>
                                    <th @click="loadMascotas()" style="text-align: center;" colspan="2">
                                        <button v-if="ver_mascotas == 0" @click="loadMascotas()" class="btn btn-dark btn-sm">Mascotas</button>
                                        <button v-if="ver_mascotas == 1" @click="loadMascotas()" class="btn btn-default btn-sm">Mascotas</button></th>
                                </tr>
                                    <tr v-if="ver_mascotas == 1">
                                        <th>Residentes con mascota: </th>
                                        <th v-text="this.mascotas[0].sin_mascotas"></th>
                                    </tr>
                                    <tr v-if="ver_mascotas == 1">
                                        <th>Residentes sin mascota:</th>
                                        <th v-text="this.mascotas[0].sumMascota"></th>
                                    </tr>
                                    <tr v-if="ver_mascotas == 1">
                                        <th>Total de perros:</th>
                                        <th v-text="this.mascotas[0].perros"></th>
                                    </tr>

                                <tr>
                                    <th @click="loadAutos()" style="text-align: center;" colspan="2">
                                        <button v-if="ver_autos == 0" @click="loadAutos()" class="btn btn-dark btn-sm">Autos</button>
                                        <button v-if="ver_autos == 1" @click="loadAutos()" class="btn btn-default btn-sm">Autos</button></th>
                                </tr>
                                    <tr v-if="ver_autos == 1">
                                        <th>Sin auto: </th>
                                        <th v-text="this.autos.sinAuto"></th>
                                    </tr>
                                    <tr v-if="ver_autos == 1">
                                        <th>Un auto:</th>
                                        <th v-text="this.autos.unAuto"></th>
                                    </tr>
                                    <tr v-if="ver_autos == 1">
                                        <th>Dos autos:</th>
                                        <th v-text="this.autos.dosAuto"></th>
                                    </tr>
                                    <tr v-if="ver_autos == 1">
                                        <th>Tres autos:</th>
                                        <th v-text="this.autos.tresAuto"></th>
                                    </tr>
                                    <tr v-if="ver_autos == 1">
                                        <th>Cuatro autos o más</th>
                                        <th v-text="this.autos.cuatroAuto"></th>
                                    </tr>

                                <tr>
                                    <th @click="loadDiscap()" style="text-align: center;" colspan="2">
                                        <button v-if="ver_discap == 0" @click="loadDiscap()" class="btn btn-dark btn-sm">Capacidades diferentes</button>
                                        <button v-if="ver_discap == 1" @click="loadDiscap()" class="btn btn-default btn-sm">Capacidades diferentes</button></th>
                                </tr>
                                    <tr v-if="ver_discap == 1">
                                        <th>Sin discapacidad:</th>
                                        <th v-text="this.sinDiscap"></th>
                                    </tr>
                                    <tr v-if="ver_discap == 1">
                                        <th>Con capacidad diferente:</th>
                                        <th v-text="this.discapacidad"></th>
                                    </tr>

                                    <tr v-if="ver_discap == 1">
                                        <th>Con necesidad de silla de ruedas:</th>
                                        <th v-text="this.silla_ruedas"></th>
                                    </tr>
                                
                            </table>
                        </div>
                    </div>

                    <!-- GRAFICOS -->
                        <div class="col-md-8" v-if=" grafico == 1">
                            <div class="card card-chart">
                                <div class="card-header">
                                    <h4 v-text="titulo">Genero</h4>
                                </div>
                                <div class="card-content">
                                    <br>
                                    <div class="ct-chart" v-if="ver_edades == 1">
                                        <canvas style="width:100%;" id="edades">                                                
                                        </canvas>
                                    </div>
                                    <div class="ct-chart" v-if="ver_genero == 1">
                                        <canvas style="width:100%;" id="genero">                                                
                                        </canvas>
                                    </div>
                                    <div class="ct-chart" v-if="ver_edadesComp == 1">
                                        <canvas style="width:100%;" id="edadesCompra">                                                
                                        </canvas>
                                    </div>
                                    <div class="ct-chart" v-if="ver_mascotas == 1">
                                        <canvas style="width:100%;" id="mascotas">                                                
                                        </canvas>
                                    </div>
                                    <div class="ct-chart" v-if="ver_edoCivil == 1">
                                        <canvas style="width:100%;" id="edoCivil">                                                
                                        </canvas>
                                    </div>
                                    <div class="ct-chart" v-if="ver_discap == 1">
                                        <canvas style="width:100%;" id="discapacidad">                                                
                                        </canvas>
                                    </div>
                                    <div class="ct-chart" v-if="ver_autos == 1">
                                        <canvas style="width:100%;" id="autos" >                                                
                                        </canvas>
                                    </div>
                                    <div class="ct-chart" v-if="ver_lugarNac == 1">
                                        <canvas style="width:100%;" id="lugar" >                                                
                                        </canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                    
                </div>
            </div>
        </div>
    </div>

</main>
</template>
<script>
    export default {
        data (){
            return {
                grafico:0,
                titulo:'',
                totalEntregas:0,

                varEdades:null,
                charEdades:null,
                edades:[],

                varEdadesCompra:null,
                charEdadesCompra:null,
                edadesCompra:[],
                rang1:0,
                rang2:0,
                rang3:0,
                rang4:0,
                rang5:0,
                rang6:0,
                rang7:0,


                varedoCivil:null,
                charedoCivil:null,
                edoCivil:[],
                sepBienes:0,
                conyugal:0,
                divorciado:0,
                soltero:0,
                unionLibre:0,
                viudo:0,
                otro:0,


                varMascotas:null,
                charMascotas:null,
                mascotas:[],

                varLugar:null,
                charLugar:null,
                lugares:[],
                lugarCant:[],

                varGenero:null,
                charGenero:null,
                genero:[],
                mujer:0,
                hombre:0,

                varDiscapacidad:null,
                charDiscapacidad:null,
                discapacidad:0,
                sinDiscap:0,
                silla_ruedas:0,
                promAutos:0,
                promAmasCasa:0,

                varExtra:null,
                charExtra:null,

                varAuto:null,
                charAuto:null,
                autos:[],
                mascotas : 0,

                lista:[],

                arrayFraccionamientos:[],
                arrayAllEtapas: [],
                buscar:'',
                b_etapa: '',
                mostrar:0,
                ver_genero:0,
                ver_edoCivil:0,
                ver_edades:0,
                ver_edadesComp:0,
                ver_mascotas:0,
                ver_discap:0,
                ver_amasCasa:0,
                ver_autos:0,
                ver_lugarNac:0,
            }
        },
        methods : {
            borrarGraficas(){
                let me = this;
                me.grafico = 0;

                if(me.charGenero)
                    me.charGenero.destroy();

                if( me.charEdades)
                    me.charEdades.destroy();

                if( me.charMascotas)
                    me.charMascotas.destroy();

                if( me.charEdadesCompra)
                    me.charEdadesCompra.destroy(); 

                if( me.charedoCivil)
                    me.charedoCivil.destroy(); 

                if( me.charDiscapacidad)
                    me.charDiscapacidad.destroy(); 

                if( me.charAuto)
                    me.charAuto.destroy(); 

                if( me.charLugar)
                    me.charLugar.destroy();
            },
            getDatos(){
                let me=this;
                var url= '/estadisticas/datos_extra?buscar=' + this.buscar + '&b_etapa=' + this.b_etapa;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.edades = respuesta.edades;
                    me.mascotas = respuesta.mascotas;
                    me.discapacidad = respuesta.discap;
                    me.sinDiscap = respuesta.sinDiscap;
                    me.silla_ruedas = respuesta.silla_ruedas;
                    me.promAutos = respuesta.promedioAutos;
                    me.promAmasCasa = respuesta.promedioAmasCasa;
                    me.totalEntregas = respuesta.total;

                    me.lugares = respuesta.lugarNacimiento;
                    me.lugarCant = respuesta.origen;

                    me.autos = respuesta.autos;

                    me.edoCivil = respuesta.estadoCivil;
                    me.sepBienes = me.edoCivil.separacionBienes;
                    me.conyugal = me.edoCivil.sociedadConyugal;
                    me.divorciado = me.edoCivil.divorciado;
                    me.soltero = me.edoCivil.soltero;
                    me.unionLibre = me.edoCivil.unionLibre;
                    me.viudo = me.edoCivil.viudo;
                    me.otro = me.otro;

                    me.genero = respuesta.genero;
                    me.hombre = me.genero.hombres;
                    me.mujer = me.genero.mujeres;

                    me.rang1 = respuesta.rango1;
                    me.rang2 = respuesta.rango2;
                    me.rang3 = respuesta.rango3;
                    me.rang4 = respuesta.rango4;
                    me.rang5 = respuesta.rango5;
                    me.rang6 = respuesta.rango6;
                    me.rang7 = respuesta.rango7;

                    me.ver_edades=0;
                    me.ver_mascotas=0;
                    me.ver_discap=0;
                    me.ver_amasCasa=0;
                    me.ver_autos=0;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectFraccionamientos(){
                let me = this;
                me.buscar=""
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
               //Select todas las etapas
            selectEtapas(buscar){
                let me = this;  
                me.arrayAllEtapas=[];
                var url = '/select_etapa_proyecto?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayAllEtapas = respuesta.etapas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            mostrarGraficos(){
                this.getDatos();
                //this.getMascotas();
                this.mostrar=1;
            },
            loadExtras(){
                let me=this;
                
                me.varExtra=document.getElementById('amas_casa').getContext('2d');
                if(me.charExtra)
                    me.charExtra.destroy();
                me.charExtra = new Chart(me.varExtra, {
                    type: 'bar',
                    data: {
                        labels: ['Amas de casa', 'Promedio'],
                        datasets: [{
                            label: ['# '],
                            data: [me.mascotas[0].totalAmaCasa, me.promAmasCasa],
                            backgroundColor: [
                                                'rgba(35, 102, 40, 0.6)',
                                                'rgba(35, 102, 40, 0.6)',
                                                ],
                            borderColor: 'rgba(35, 102, 40, 0.94)',
                            borderWidth: 1
                        },]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        },
                        legend: {display:false}
                    }
                });
            },
            loadAutos(){
                let me=this;
                me.borrarGraficas();
                me.titulo = 'Capacidades diferentes';
                me.ver_edades = 0;
                me.ver_edadesComp = 0;
                me.ver_mascotas = 0;
                me.ver_edoCivil = 0;
                me.ver_genero = 0;
                me.ver_discap = 0;
                me.ver_lugarNac = 0;
                me.ver_autos = 1;
                me.grafico = 1;
                
                me.varAuto=document.getElementById('autos').getContext('2d');
                
                me.charAuto = new Chart(me.varAuto, {
                    type: 'bar',
                    data: {
                        labels: ['Sin Auto', 'Un auto', 'Dos autos', 'Tres autos', 'Cuatro autos o más'],
                        datasets: [{
                            label: ['# '],
                            data: [me.autos.sinAuto,me.autos.unAuto, me.autos.dosAuto,me.autos.tresAuto,me.autos.cuatroAuto],
                            backgroundColor: [
                                                'rgba(35, 102, 40, 0.8)',
                                                'rgba(35, 102, 40, 0.8)',
                                                'rgba(35, 102, 40, 0.8)',
                                                'rgba(35, 102, 40, 0.8)',
                                                'rgba(35, 102, 40, 0.8)',
                                                ],
                            borderColor: 'rgba(0, 0, 0, 0.94)',
                            borderWidth: 1
                        },]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        },
                        legend: {display:false}
                    }
                });
            },
            loadDiscap(){
                let me=this;
                me.borrarGraficas();
                me.titulo = 'Capacidades diferentes';
                me.ver_edades = 0;
                me.ver_edadesComp = 0;
                me.ver_mascotas = 0;
                me.ver_edoCivil = 0;
                me.ver_genero = 0;
                me.ver_autos = 0;
                me.ver_lugarNac = 0;
                me.ver_discap = 1;
                me.grafico = 1;
                
                me.varDiscapacidad=document.getElementById('discapacidad').getContext('2d');

                me.charDiscapacidad = new Chart(me.varDiscapacidad, {
                    type: 'bar',
                    data: {
                        labels: ['No', 'Si', 'Silla de ruedas'],
                        datasets: [{
                            label: ['# Casas'],
                            data: [me.sinDiscap, me.discapacidad, me.silla_ruedas],
                            backgroundColor: [
                                                'rgba(39, 149, 50, 0.6)',
                                                'rgba(39, 149, 50, 0.6)',
                                                'rgba(8, 103, 18, 0.8)',
                                                ],
                            borderColor: 'rgba(0, 0, 0, 0.8)',
                            borderWidth: 1
                        },]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        },
                        legend: {display:false}
                    }
                });
            },
            loadEdades(){
                let me=this;
                me.borrarGraficas();
                me.titulo = 'Edades de habitantes';
                me.ver_edadesComp = 0;
                me.ver_mascotas = 0;
                me.ver_genero = 0;
                me.ver_edoCivil = 0;
                me.ver_discap = 0;
                me.ver_autos = 0;
                me.ver_lugarNac = 0;
                me.ver_edades = 1;
                me.grafico = 1;

                if(me.ver_edades == 1){
                    me.varEdades=document.getElementById('edades').getContext('2d');
                    

                    me.charEdades = new Chart(me.varEdades, {
                        type: 'bar',
                        data: {
                            labels: ['Entre 0-10', 'Entre 11-20', 'Mayores de 21'],
                            datasets: [{
                                label: ['# Habitantes'],
                                data: [me.edades[0].sum010, me.edades[0].sum1120, me.edades[0].sum21],
                                backgroundColor: [
                                                    'rgba(33, 30, 188, 0.4)',
                                                    'rgba(33, 30, 188, 0.4)',
                                                    'rgba(33, 30, 188, 0.4)',
                                                    ],
                                borderColor: 'rgba(33, 30, 188, 0.4)',
                                borderWidth: 1
                            },]
                        },
                        options: {
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero:true
                                    }
                                }]
                            },
                            legend: {display:false}
                        }
                    });
                }
            },

            loadEdadesComprador(){
                let me=this;
                me.borrarGraficas();
                me.titulo = 'Edades de comprador';
                me.ver_edades = 0;
                me.ver_mascotas = 0;
                me.ver_genero = 0;
                me.ver_edoCivil = 0;
                me.ver_discap = 0;
                me.ver_autos = 0;
                me.ver_lugarNac = 0;
                me.ver_edadesComp = 1;
                me.grafico = 1;

                if(me.ver_edadesComp == 1){
                    me.varEdadesCompra=document.getElementById('edadesCompra').getContext('2d');
                    

                    me.charEdadesCompra = new Chart(me.varEdadesCompra, {
                        type: 'bar',
                        data: {
                            labels: ['20 - 25 ', '26 - 30', '31 - 40', '41 - 50', '51 - 60', '61 - 70', 'Mayores a 71'],
                            datasets: [{
                                label: [''],
                                data: [me.rang1, me.rang2, me.rang3, me.rang4, me.rang5, me.rang6, me.rang7],
                                backgroundColor: [
                                                    'rgba(33, 30, 188, 0.8)',
                                                    'rgba(33, 30, 188, 0.8)',
                                                    'rgba(33, 30, 188, 0.8)',
                                                    'rgba(33, 30, 188, 0.8)',
                                                    'rgba(33, 30, 188, 0.8)',
                                                    'rgba(33, 30, 188, 0.8)',
                                                    'rgba(33, 30, 188, 0.8)',
                                                    'rgba(33, 30, 188, 0.8)',
                                                    ],
                                borderColor: 'rgba(33, 30, 188, 0.9)',
                                borderWidth: 1
                            },]
                        },
                        options: {
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero:true
                                    }
                                }]
                            },
                            legend: {display:false}
                        }
                    });
                }
            },

            loadEdoCivil(){
                let me=this;
                me.borrarGraficas();
                me.titulo = 'Estado civil';
                me.ver_edadesComp = 0;
                me.ver_edades = 0;
                me.ver_mascotas = 0;
                me.ver_genero = 0;
                me.ver_discap = 0;
                me.ver_autos = 0;
                me.ver_lugarNac = 0;
                me.ver_edoCivil = 1;
                me.grafico = 1;

                if(me.ver_edoCivil == 1){
                    me.varedoCivil=document.getElementById('edoCivil').getContext('2d');
                    

                    me.charedoCivil = new Chart(me.varedoCivil, {
                        type: 'horizontalBar',
                        data: {
                            labels: ['Separación de bienes', 'Separación conyugal', 'Divorciado', 'Soltero', 'Unión libre', 'Viudo', 'Otro'],
                            datasets: [{
                                label: '# ',
                                data: [me.sepBienes, me.conyugal, me.divorciado, me.soltero, me.unionLibre, me.viudo, me.otro],
                                backgroundColor: [
                                                    'rgba(49, 139, 139, 0.8)',
                                                    'rgba(80, 139, 139, 0.8)',
                                                    'rgba(49, 139, 139, 0.8)',
                                                    'rgba(49, 139, 139, 0.8)',
                                                    'rgba(49, 139, 139, 0.8)',
                                                    'rgba(49, 139, 139, 0.8)',
                                                    'rgba(49, 139, 139, 0.8)',
                                                    'rgba(49, 139, 139, 0.8)',
                                                    ],
                                borderColor: 'rgba(0, 0, 0, 0.9)',
                                borderWidth: 1
                            },]
                        },
                        options: {
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero:true
                                    }
                                }]
                            },
                            legend: {display:false}
                        }
                    });
                }
            },

            loadMascotas(){
                let me=this;
                me.borrarGraficas();
                me.titulo = 'Mascotas';
                me.ver_edades = 0;
                me.ver_edadesComp = 0;
                me.ver_genero = 0;
                me.ver_edoCivil = 0;
                me.ver_discap = 0;
                me.ver_autos = 0;
                me.ver_lugarNac = 0;
                me.ver_mascotas = 1;
                me.grafico = 1;
                
                me.varMascotas=document.getElementById('mascotas').getContext('2d');

                me.charMascotas = new Chart(me.varMascotas, {
                    type: 'bar',
                    data: {
                        labels: ['Sin Mascotas', 'Con Mascotas', 'Total de Perros'],
                        datasets: [{
                            label: '# ',
                            data: [me.mascotas[0].sin_mascotas, me.mascotas[0].sumMascota,me.mascotas[0].perros],
                            backgroundColor: [
                                                'rgba(102, 0, 0, 0.4)',
                                                'rgba(102, 0, 0, 0.4)',
                                                'rgba(121, 1, 1, 0.7)',
                                                ],
                            borderColor: 'rgba(102, 0, 0, 1)',
                            borderWidth: 1
                        },
                        ]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        },
                        legend: {display:false}
                    }
                });
            },

            loadOrigen(){
                let me=this;
                me.borrarGraficas();
                me.titulo = 'Lugares de nacimiento';
                me.ver_edades = 0;
                me.ver_edadesComp = 0;
                me.ver_mascotas = 0;
                me.ver_genero = 0;
                me.ver_edoCivil = 0;
                me.ver_discap = 0;
                me.ver_autos = 0;
                me.ver_lugarNac = 1;
                me.grafico = 1;
                
                me.varLugar=document.getElementById('lugar').getContext('2d');

                me.lista = [];
                var i =0;
                me.lugares.forEach(element => {
                    me.lista.push(me.lugarCant[i].num);
                    i++;
                });

                me.charLugar = new Chart(me.varLugar, {
                    type: 'bar',
                    data: {
                        labels: me.lugares,
                        datasets: [{
                            label: '# ',
                            data: me.lista,
                            backgroundColor: 'rgba(102, 0, 0, 0.4)',
                                                
                            borderColor: 'rgba(102, 0, 0, 1)',
                            borderWidth: 1
                        },
                        ]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        },
                        legend: {display:false}
                    }
                });
            },

            loadGenero(){
                let me=this;
                me.borrarGraficas();
                me.titulo = 'Genero';
                me.ver_edades = 0;
                me.ver_edadesComp = 0;
                me.ver_mascotas = 0;
                me.ver_edoCivil = 0;
                me.ver_discap = 0;
                me.ver_autos = 0;
                me.ver_lugarNac = 0;
                me.ver_genero = 1;
                me.grafico = 1;
                
                me.varGenero=document.getElementById('genero').getContext('2d');

                me.charGenero = new Chart(me.varGenero, {
                    type: 'bar',
                    data: {
                        labels: ['Hombres', 'Mujeres'],
                        datasets: [{
                            label: '# ',
                            data: [me.hombre, me.mujer],
                            backgroundColor: [
                                                'rgba(42, 109, 176, 0.6)',
                                                'rgba(185, 53, 185, 0.6)',
                                                ],
                            borderColor: 'rgba(0, 0, 0, 0.7)',
                            borderWidth: 1
                        },
                        ]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        },
                        legend: {display:false}
                    }
                });
            },
        },
        mounted() {
            //  this.getIngresos();
            //  this.getVentas();
            this.selectFraccionamientos();
        }
    }
</script>