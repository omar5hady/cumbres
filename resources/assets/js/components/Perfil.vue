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

             <form method="post"  @submit="formSubmit" enctype="multipart/form-data">
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
                                <img v-if="url" :src="url" class="img-avatar" rounded="circle" width="250" height="250"  />
                                <img v-else :src="'/img/avatars/'+foto_user" class="img-avatar" rounded="circle" width="250" height="250"  />
                            </div>
                        </div>

                         <div class="form-group row">
                            <div class="col-md-6">
                                <input type="file" class="form-control" v-on:change="onImageChange">
                            </div>
                        </div>


                        <div class="form-group row">
                            <div class="col-md-2">
                                <button @click="passwordChange()" :disabled="password == ''" type="submit" class="btn btn-primary">
                                    <i class="icon-save"></i>&nbsp;Aplicar cambios
                                </button>
                            </div>
                        </div>
        </form>
                             


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
               id:0,
               usuario:'',
               password:'',
               nombre:'',
               foto_user: '',
               arrayUsuario: [],
               url: null,

            }
        },
        
        methods : {

            onImageChange(e){

                console.log(e.target.files[0]);

                this.foto_user = e.target.files[0];
                this.url = URL.createObjectURL(this.foto_user);
            },

            formSubmit(e) {

                e.preventDefault();

                
                let formData = new FormData();
           
                formData.append('foto_user',this.foto_user);
                let me = this;
                axios.post('/users/foto/'+this.userId ,formData)
               

               

            },

            passwordChange(){
                 axios.put('/users/update/password',{
                    'password': this.password,
                    'id' :this.userId
                    }) .then(function (response) {
                    location.reload();
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Cambios aplicados correctamente',
                        showConfirmButton: false,
                        timer: 2000
                        })

                })

                .catch(function (error) {

                    currentObj.output = error;

                });
            },


             obtenerUsuario(){
                let me = this;
                me.arrayUsuario=[];
                var url = '/usuario/datos?id=' + this.userId;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayUsuario = respuesta.usuario;
                    me.usuario = me.arrayUsuario[0].usuario;
                    me.nombre = me.arrayUsuario[0].n_completo;
                    me.foto_user = me.arrayUsuario[0].foto_user;
                    me.password = '';
                    
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
