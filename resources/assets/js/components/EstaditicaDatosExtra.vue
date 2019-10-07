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
            <div class="car-body">
                
                <div class="row" v-if="mostrar==1">
                    <div class="col-md-6">
                        <div class="card card-chart">
                            <div class="card-header">
                                <h4>Rango de Edades</h4>
                            </div>
                            <div class="card-content">
                                <div class="ct-chart">
                                    <canvas id="edades">                                                
                                    </canvas>
                                </div>
                            </div>
                            <!-- <div class="card-footer">
                                <p>Rango de edades en el fraccionamiento.</p>
                            </div> -->
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-chart">
                            <div class="card-header">
                                <h4>Mascotas</h4>
                            </div>
                            <div class="card-content">
                                <div class="ct-chart">
                                    <canvas id="mascotas">                                                
                                    </canvas>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>

                <div class="row" v-if="mostrar==1">
                    <div class="col-md-6">
                        <div class="card card-chart">
                            <div class="card-header">
                                <h5>Personas con capacidades diferentes</h5>
                            </div>
                            <div class="card-content">
                                <div class="ct-chart">
                                    <canvas id="discapacidad">                                                
                                    </canvas>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-chart">
                            <div class="card-header">
                                <h5>Otros datos importantes</h5>
                            </div>
                            <div class="card-content">
                                <div class="ct-chart">
                                    <canvas id="extras">                                                
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
                varEdades:null,
                charEdades:null,
                edades:[],
                varTotalEdades:[],

                varMascotas:null,
                charMascotas:null,
                mascotas:[],
                varTotalMascotas:[],

                varDiscapacidad:null,
                charDiscapacidad:null,
                discapacidad:0,
                sinDiscap:0,
                silla_ruedas:0,
                promAutos:0,
                promAmasCasa:0,

                varExtra:null,
                charExtra:null,

                arrayFraccionamientos:[],
                arrayAllEtapas: [],
                buscar:'',
                b_etapa: '',
                mostrar:0
            }
        },
        methods : {
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
                    //cargamos los datos del chart
                    me.loadEdades();
                    me.loadMascotas();
                    me.loadDiscap();
                    me.loadExtras();
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
                
                me.varExtra=document.getElementById('extras').getContext('2d');

                me.charExtra = new Chart(me.varExtra, {
                    type: 'bar',
                    data: {
                        labels: ['Amas de casa', 'Promedio' , 'Autos', 'Promedio'],
                        datasets: [{
                            label: ['# '],
                            data: [me.mascotas[0].totalAmaCasa, me.promAmasCasa, me.mascotas[0].totalAutos,me.promAutos],
                            backgroundColor: [
                                                'rgba(35, 102, 40, 0.6)',
                                                'rgba(35, 102, 40, 0.6)',
                                                'rgba(35, 102, 40, 0.8)',
                                                'rgba(35, 102, 40, 0.8)',
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
            loadDiscap(){
                let me=this;
                
                me.varDiscapacidad=document.getElementById('discapacidad').getContext('2d');

                me.charDiscapacidad = new Chart(me.varDiscapacidad, {
                    type: 'bar',
                    data: {
                        labels: ['No', 'Si', 'Silla de ruedas'],
                        datasets: [{
                            label: ['# Casas'],
                            data: [me.sinDiscap, me.discapacidad, me.silla_ruedas],
                            backgroundColor: [
                                                'rgba(44, 142, 144, 0.6)',
                                                'rgba(44, 142, 144, 0.6)',
                                                'rgba(44, 142, 144, 0.6)',
                                                ],
                            borderColor: 'rgba(44, 142, 144, 0.94)',
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
                        }
                    }
                });
            },
            loadEdades(){
                let me=this;
                
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
                        }
                    }
                });
            },
            loadMascotas(){
                let me=this;
                
                me.varMascotas=document.getElementById('mascotas').getContext('2d');

                me.charMascotas = new Chart(me.varMascotas, {
                    type: 'bar',
                    data: {
                        labels: ['Sin Mascotas', 'Con Mascotas', 'Total de Perros', 'Promedio por casa'],
                        datasets: [{
                            label: '# ',
                            data: [me.mascotas[0].sin_mascotas, me.mascotas[0].sumMascota,me.mascotas[0].perros,me.mascotas[0].promedioPerros],
                            backgroundColor: [
                                                'rgba(102, 0, 0, 0.4)',
                                                'rgba(102, 0, 0, 0.4)',
                                                'rgba(121, 1, 1, 0.7)',
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
        },
        mounted() {
            //  this.getIngresos();
            //  this.getVentas();
            this.selectFraccionamientos();
        }
    }
</script>
