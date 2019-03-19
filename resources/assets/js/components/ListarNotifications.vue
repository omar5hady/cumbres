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
                        <i class="fa fa-align-justify"></i> Listado de notificaciones
                    </div>
                    <div class="card-body">
                        <div class="list-group">
        <div v-if="arrayNotificaciones.length">
            <li v-for="item in arrayNotificaciones" :key="item.id">
                        <a class="list-group-item list-group-item-action flex-column align-items-start active">
                            <div class="message">
                                <div class="py-1 mr-5 float-left">
                                    <div class="avatar avatar2">
                                        <img class="img-avatar img-avatar2" :src="'img/avatars/'+item.data.datos.notificacion.foto" alt="admin@bootstrapmaster.com">
                                        <span class="avatar-status badge-success"></span>
                                    </div>
                                </div>    
                        <div>
                          
                        <small class="text-muted">{{item.data.datos.notificacion.usuario}}</small>
                        <small class="text-muted float-right mt-1" v-text="this.moment(item.data.datos.notificacion.fecha.date,'YYYY-MM-DD hh:mm:ss').locale('es').fromNow()"></small>
                        </div>
                        <div class="font-weight-bold">
                        <span class="fa fa-exclamation text-danger"></span>{{item.data.datos.notificacion.titulo}}
                        </div>
                        <div class="small text-truncate">{{item.data.datos.notificacion.msj}}</div>
                       
                            </div>
                        </a>
                        
            </li>
        </div>
                        </div>

                    </div>
                </div>
            </div>
</main>            
</template>

<script>
export default {
    data() {
        return {
            arrayNotificaciones : [],
            
        }
    },
    methods: {
          listarNotificaciones(){
                let me = this;
                me.arrayNotificaciones = [];
                var url = '/notification/getListado';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayNotificaciones = respuesta;
                })
                .catch(function (error) {
                    console.log(error);
                })
            },
    },

    mounted() {
        this.listarNotificaciones();
    }

}
</script>
<style>
    .avatar2 .img-avatar2 {
        width: 60px;
        height: 60px;
    }
</style>
