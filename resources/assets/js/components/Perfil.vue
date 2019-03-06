<template>
        <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Perfil
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <label class="col-md-12 form-control-label" for="text-input"><strong>Nombre</strong></label>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <input type="text" readonly v-model="nombre" class="form-control" placeholder="Usuario">
                            </div>
                        </div>    

                        <div class="row">
                            <label class="col-md-12 form-control-label" for="text-input"><strong>Usuario</strong></label>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <input type="text" readonly v-model="usuario" class="form-control" placeholder="Usuario">
                            </div>
                        </div>

                        <div class="row">
                            <label class="col-md-12 form-control-label" for="text-input"><strong>Password</strong></label>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <input type="password" v-model="password" class="form-control" placeholder="Password">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <img src="img/avatars/goku.jpg" class="img-avatar" rounded="circle" width="250" height="300"  />
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-2">
                                <button :disabled="password == ''" type="button" class="btn btn-primary">
                                    <i class="icon-save"></i>&nbsp;Aplicar cambios
                                </button>
                            </div>
                        </div>

                             


                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            
        </main>
</template>

<script>
    export default {
        props:{
            userId:{type: String}
        },
        data (){
            return {
               usuario:'',
               password:'',
               nombre:'',

            }
        },
        methods : {
             obtenerUsuario(){
                let me = this;
                var arrayUsuario=[];
                var url = '/usuario/datos?id=' + this.userId;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    arrayUsuario = respuesta.usuario;
                    me.usuario = arrayUsuario[0].usuario;
                    me.nombre = arrayUsuario[0].n_completo;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        },
        mounted() {
            this.obtenerUsuario();
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
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>
