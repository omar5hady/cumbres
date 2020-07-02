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
                                <a v-for="item in arrayNotificaciones.slice(0,50)" :key="item.id" class="list-group-item list-group-item-action flex-column align-items-start">
                                    <div class="message">
                                        
                                        <div class="py-2 mr-3 float-left">
                                            <div class="avatar avatar2">
                                                <img class="img-avatar img-avatar2" :src="'img/avatars/'+item.data.datos.notificacion.foto" alt="admin@bootstrapmaster.com">
                                                <span class="avatar-status badge-success"></span>
                                            </div>
                                        </div>    
                                        <div>    
                                            <small class="text-muted">{{item.data.datos.notificacion.usuario}}: &nbsp;</small>
                                            <small class="text-muted mt-1" v-text="this.moment(item.data.datos.notificacion.fecha.date,'YYYY-MM-DD hh:mm:ss').locale('es').fromNow()"></small>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">
                                                <span class="fa fa-exclamation text-danger"></span>{{item.data.datos.notificacion.titulo}}
                                                <small class="text-muted mt-1">&nbsp;{{item.data.datos.notificacion.msj}}</small>
                                            </div>
                                            
                                        </div>
                                        
                                    </div>
                                </a>         
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

            created() {
                let me = this;
                axios.post('notification/get?op=1').then(function(response) {
                    // console.log(response.data);
                    me.arrayNotificaciones = response.data;
                }).catch(function(error) {
                    console.log(error);
                });

                var userId = $('meta[name="userId"]').attr('content');

                window.Echo.private('App.User.' + userId).listen(('PackageNotification'), (e) => {
                    me.notifications.unshift(notification);
        });

            }
    },

    mounted() {
        this.created();
        this.listarNotificaciones();
    }

}
</script>
<style>
    .avatar2 .img-avatar2 {
        width: 35px;
        height: 35px;
    }
</style>
