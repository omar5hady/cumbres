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
                                <select class="form-control" v-model="buscar" >
                                    <option value="">Seleccione el proyecto</option>
                                    <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                </select>
                            <button type="submit" @click="mostrarGraficos()" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
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
                    <div class="col-md-3">
                        
                    </div>
                    <div class="col-md-6">
                        <div class="card card-chart">
                            <div class="card-header">
                                <h4>Otros datos relevantes</h4>
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

                arrayFraccionamientos:[],
                buscar:'',
                mostrar:0
            }
        },
        methods : {
            getEdades(){
                let me=this;
                var url= '/estadisticas/datos_extra?buscar=' + this.buscar;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.edades = respuesta.edades;
                    //cargamos los datos del chart
                    me.loadEdades();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getMascotas(){
                let me=this;
                var url= '/estadisticas/datos_extra?buscar=' + this.buscar;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.mascotas = respuesta.mascotas;
                    //cargamos los datos del chart
                    me.loadMascotas();
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
            mostrarGraficos(){
                this.getEdades();
                this.getMascotas();
                this.mostrar=1;
            },
            loadEdades(){
                let me=this;
                
                me.varEdades=document.getElementById('edades').getContext('2d');

                me.charEdades = new Chart(me.varEdades, {
                    type: 'bar',
                    data: {
                        labels: ['Entre 0-10', 'Entre 11-20', 'Mayores de 21'],
                        datasets: [{
                            label: ['# habitantes'],
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
                            borderColor: 'rgba(33, 30, 188, 0.4)',
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
