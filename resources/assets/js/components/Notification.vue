<template>
        <li class="nav-item ">
        <a class="nav-link" href="#" data-toggle="dropdown">
            <i class="icon-bell"></i>
            <span v-if="notifications.length>0" class="badge badge-pill badge-danger">{{notifications.length}}</span>
            <span v-else class="badge badge-pill badge-danger">0</span>
        </a>
        
        <div class="dropdown-menu dropdown-menu-right">
            <div class="dropdown-header text-center">
                <strong>Notificaciones</strong>
            </div>
            <div v-if="notifications.length">
            <li v-for="item in listar" :key="item.id">
                <!-- Notificacion para nuevas simulaciones -->
                <a class="dropdown-item dropdown-item2" href="">
                    <div class="message">
                        <div class="py-3 mr-3 float-left">
                            <div class="avatar">
                                <img class="img-avatar" :src="'img/avatars/'+item.data.datos.notificacion.foto" alt="admin@bootstrapmaster.com">
                                <span class="avatar-status badge-success"></span>
                            </div>
                        </div>
                        <div>
                            <small class="text-muted">{{item.data.datos.notificacion.usuario}}</small>
                            <small class="text-muted float-right mt-1" v-text="this.moment(item.data.datos.notificacion.fecha.date,'YYYY-MM-DD hh:mm:ss').locale('es').fromNow()"></small>
                        </div>
                        <div class="font-weight-bold">
                            <span class="fa fa-exclamation text-danger"></span> {{item.data.datos.notificacion.titulo}}
                        </div>
                                <div class="small text-truncate">{{item.data.datos.notificacion.msj}}</div>
                    </div>
                </a>

            
            </li>
            </div>
            <div v-else>
                <a class="dropdown-item text-center"><span>No tiene notificaciones</span></a>
            </div>
            <div>
              <a class="dropdown-item text-center" @click="$root.$data.menu=101"><i class="fa fa-user"></i> Ver mas...</a>
            </div>
        </div>
    </li>
</template>

<script>
export default {
  props: {
        notifications:{type: Array},
        rolId:{type: String},
       
  },
  data(){
     return{
         arrayNotificaciones: [],
       
     }
  },
  computed:{
        listar: function(){
            //   return this.notifications[0];
            //this.arrayNotificaciones = Object.values(this.notifications);
            if (this.notifications == ''){
                return this.arrayNotificaciones = [];
            }else{
                if(this.notifications.length < 5)
                    return this.arrayNotificaciones = this.notifications;
                else
                    return this.arrayNotificaciones = this.notifications.slice(0,4);
            //capturo la ultima notificacion agregada
                // this.arrayNotificaciones = Object.values(this.notifications[0]);
                // this.arrayNotificaciones = Object.values(this.notifications[1]);
                // //validacion por indice fuera de rango
                // if(this.arrayNotificaciones.length > 3){
                //     //si el tamaño es > 3 es cuando las notificaciones son obtenidas desde el mismo servidor
                //     return Object.values(this.arrayNotificaciones[4]);
                // } else {
                //     //si el tamaño es < 3 es cuando las notificaciones son obtenidas desde el canal privado con laravel echo
                //     return Object.values(this.arrayNotificaciones[1]);
                // }

            }
        }
    },
       
}
</script>
<style>

    .app-header.navbar .dropdown-item2 {
        min-width: 350px;
    }

     @media screen and (max-width:400px){
        .dropdown-menu {
                width: 280px;
        }
        .app-header.navbar .dropdown-item2 {
            min-width: 250px;
        }
    }
    
    @media screen and (max-width:325px){
        .dropdown-menu {
                width: 220px;
        }
        .app-header.navbar .dropdown-item2 {
            min-width: 200px;
        }
    }

   

    




    
</style>

