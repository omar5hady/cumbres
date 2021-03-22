<template>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card-group mb-0">
                <div class="card text-white bg-dark">
                    <div class="card-body text-center">
                        <div>
                            <h3 center> Encuesta de satisfacción </h3>
                        </div>
                        <p class="text-muted">Sr(a). Nombre Apellido, Bienvenido a la familia Cumbres</p>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-8">
            <div class="card-group mb-0">
                <div class="card">
                    <form class="form-horizontal was-validated" method="POST" >
                    
                    <div class="card-body">
                        <div>
                            <h4><center>Califica a tu asesor de ventas</center></h4>
                        </div>
                        
                        
                        <div class="form-group mb-12">
                            <span class="input-group-addon">1. ¿Nos recomendarias con un amigo y/o familiar?&nbsp;<i class="icon-user"></i></span>
                            <form>
                                <center>
                                    <p class="clasificacion">
                                        <input v-model="resp1" id="radio1" type="radio" name="estrellas" value="5"><!--
                                        --><label for="radio1">★</label><!--
                                        --><input v-model="resp1" id="radio2" type="radio" name="estrellas" value="4"><!--
                                        --><label for="radio2">★</label><!--
                                        --><input v-model="resp1" id="radio3" type="radio" name="estrellas" value="3"><!--
                                        --><label for="radio3">★</label><!--
                                        --><input v-model="resp1" id="radio4" type="radio" name="estrellas" value="2"><!--
                                        --><label for="radio4">★</label><!--
                                        --><input v-model="resp1" id="radio5" type="radio" name="estrellas" value="1"><!--
                                        --><label for="radio5">★</label>
                                    </p>
                                </center>    
                            </form>
                        </div>

                        <div class="form-group mb-12">
                           <form method="post" @submit="formSubmit"  enctype="multipart/form-data">

                                <!-- {{ csrf_field() }} -->
                                Selecciona archivo excel xls/csv: <input type="file" v-on:change="onImageChange" class="form-control">
                                <input  type="submit" value="Cargar" class="btn btn-primary btn-lg" style="margin-top: 3%">
                            </form>
                        </div>


                        <div class="row animated fadeInLeft ">
                            <div class="col-6">
                                <button type="submit" class="btn btn-dark px-4">Acceder</button>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
                <!-- <div class="card text-white bg-dark" style="width:2%">
                    <div class="card-body text-center">
                        <div>
                        <h1 center> SII </h1>
                        <h2 center> CUMBRES </h2>
                       
                        </div>
                    </div>
                </div> -->
            </div>
        </div>

        <!-- <div class="col-md-8">
            <div class="card-group mb-0">
                <div class="card">
                    <form class="form-horizontal was-validated" method="POST" >
                    
                    <div class="card-body">
                        <div>
                            <h4><center>Encuesta de satisfacción</center></h4>
                        </div>
                        
                        <p class="text-muted">Sr(a). Nombre Apellido, Bienvenido a la familia Cumbres</p>
                        <div class="form-group mb-12">
                            <span class="input-group-addon">1. ¿Nos recomendarias con un amigo y/o familiar?<i class="icon-user"></i></span>
                            <table class="table table-bordered table-striped table-sm">
                                <tr>
                                    
                                    <th>Muy probable</th>
                                    <th>Probable</th>
                                    <th>Poco probable</th>
                                    <th>Imposible</th>
                                </tr>
                                    
                                    <td><input type="checkbox" name="" id=""></td>
                                    <td><input type="checkbox" name="" id=""></td>
                                    <td><input type="checkbox" name="" id=""></td>
                                    <td><input type="checkbox" name="" id=""></td>
                                <tr>

                                </tr>
                            </table>
                            <input type="text" name="usuario" id="usuario" class="form-control" placeholder="Usuario">
                        </div>
                        <div class="form-group mb-4">
                            <span class="input-group-addon"><i class="icon-lock"></i></span>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                            <input type="range" name="password" id="password" class="form-control" placeholder="Password">
                        </div>
                        <div class="row animated fadeInLeft slower">
                        <div class="col-6">
                            <button type="submit" class="btn btn-dark px-4">Acceder</button>
                        </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card-group mb-0">
                <div class="card">
                    <form class="form-horizontal was-validated" method="POST" >
                    
                    <div class="card-body">
                        <div>
                            <h4><center>Encuesta de satisfacción</center></h4>
                        </div>
                        
                        <p class="text-muted">Sr(a). Nombre Apellido, Bienvenido a la familia Cumbres</p>
                        <div class="form-group mb-12">
                            <span class="input-group-addon">1. ¿Nos recomendarias con un amigo y/o familiar?<i class="icon-user"></i></span>
                            <table class="table table-bordered table-striped table-sm">
                                <tr>
                                    
                                    <th>Muy probable</th>
                                    <th>Probable</th>
                                    <th>Poco probable</th>
                                    <th>Imposible</th>
                                </tr>
                                    
                                    <td><input type="checkbox" name="" id=""></td>
                                    <td><input type="checkbox" name="" id=""></td>
                                    <td><input type="checkbox" name="" id=""></td>
                                    <td><input type="checkbox" name="" id=""></td>
                                <tr>

                                </tr>
                            </table>
                            <input type="text" name="usuario" id="usuario" class="form-control" placeholder="Usuario">
                        </div>
                        <div class="form-group mb-4">
                            <span class="input-group-addon"><i class="icon-lock"></i></span>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                            <input type="range" name="password" id="password" class="form-control" placeholder="Password">
                        </div>
                        <div class="row animated fadeInLeft slower">
                        <div class="col-6">
                            <button type="submit" class="btn btn-dark px-4">Acceder</button>
                        </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div> -->

        <div class="col-md-8">
            <div class="card-group mb-0">
                <!-- <div class="card">
                    <form class="form-horizontal was-validated" method="POST" >
                    
                    <div class="card-body">
                        <h1>Encuesta de satisfacción</h1>
                        <p class="text-muted">Sr(a). Nombre Apellido, Bienvenido a la familia Cumbres</p>
                        <div class="form-group mb-12">
                            <span class="input-group-addon">1. ¿Nos recomendarias con un amigo y/o familiar?<i class="icon-user"></i></span>
                            <table class="table table-bordered table-striped table-sm">
                                <tr>
                                    
                                    <th>Muy probable</th>
                                    <th>Probable</th>
                                    <th>Poco probable</th>
                                    <th>Imposible</th>
                                </tr>
                                    
                                    <td><input type="checkbox" name="" id=""></td>
                                    <td><input type="checkbox" name="" id=""></td>
                                    <td><input type="checkbox" name="" id=""></td>
                                    <td><input type="checkbox" name="" id=""></td>
                                <tr>

                                </tr>
                            </table>
                            <input type="text" name="usuario" id="usuario" class="form-control" placeholder="Usuario">
                        </div>
                        <div class="form-group mb-4">
                            <span class="input-group-addon"><i class="icon-lock"></i></span>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                            <input type="range" name="password" id="password" class="form-control" placeholder="Password">
                        </div>
                        <div class="row animated fadeInLeft slower">
                        <div class="col-6">
                            <button type="submit" class="btn btn-dark px-4">Acceder</button>
                        </div>
                        </div>
                    </div>
                    </form>
                </div> -->
                <div class="card text-white bg-dark">
                    <div class="card-body text-center">
                        <div>
                        <h1 center> SII </h1>
                        <h2 center> CUMBRES </h2>
                       
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</template>

<script>
    export default {
        data(){
            return{
                resp1:0,
                file:'',
               
            }
        },
        computed:{
            
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
                e.preventDefault();
               
                let formData = new FormData();
                formData.append('file', this.file);
                axios.post('/encuesta1/pruebaExcel',formData)
                .then(function (response) {
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Archivo cargado correctamente',
                        showConfirmButton: false,
                        timer: 2500
                        })

                })

                .catch(function (error) {

                  console.log(error);

                });

            },
            
        },
        mounted() {

        }
    }
</script>

<style>

    #form {
    width: 250px;
    margin: 0 auto;
    height: 50px;
    }

    #form p {
    text-align: center;
    }

    #form label {
    font-size: 20px;
    }

    input[type="radio"] {
    display: none;
    }

    label {
    color: grey;
    font-size: xx-large;
    }

    .clasificacion {
    direction: rtl;
    unicode-bidi: bidi-override;
    }

    label:hover,
    label:hover ~ label {
    color: orange;
    }

    input[type="radio"]:checked ~ label {
    color: orange;
    }

</style>