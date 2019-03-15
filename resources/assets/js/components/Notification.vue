<template>
        <li class="nav-item d-md-down-none">
        <a class="nav-link" href="#" data-toggle="dropdown">
            <i class="icon-bell"></i>
            <span class="badge badge-pill badge-danger">{{notifications.length}}</span>
        </a>
        
        <div class="dropdown-menu dropdown-menu-right">
            <div class="dropdown-header text-center">
                <strong>Notificaciones</strong>
            </div>
            <div v-if="notifications.length">
            <li v-for="item in listar" :key="item.id">
            <!-- <a v-if="rolId=='1' || rolId=='6' || rolId=='4'" class="dropdown-item" href="#">
                <i class="fa fa-calculator text-danger"></i>{{item.pendientes.msj}} 
                <span class="badge badge-danger rounded">  {{item.pendientes.numero}}</span>
            </a> -->
            <a v-if="rolId=='1' || rolId=='6' || rolId=='4'" class="dropdown-item dropdown-item2" href="">
            <div class="message">
                <div class="py-3 mr-3 float-left">
                    <div class="avatar">
                        <img class="img-avatar" :src="'img/avatars/'+item.pendientes.foto" alt="admin@bootstrapmaster.com">
                        <span class="avatar-status badge-success"></span>
                    </div>
                </div>
                <div>
                    <small class="text-muted">{{item.pendientes.usuario}}</small>
                    <small class="text-muted float-right mt-1" v-text="this.moment(item.pendientes.fecha.date,'YYYY-MM-DD hh:mm:ss').locale('es').fromNow()"></small>
                </div>
                <div class="font-weight-bold">
                    <span class="fa fa-exclamation text-danger"></span> Nueva simulacion
                </div>
                        <div class="small text-truncate">Creó una nueva simulacion</div>
            </div>
            </a>
           
            </li>
            </div>
            <div v-else>
                <a><span>No tiene notificaciones</span></a>
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
         arrayNotificaciones: []
     }
  },
  computed:{
      listar: function(){
        //   return this.notifications[0];
        this.arrayNotificaciones = Object.values(this.notifications[0]);
       if (this.notifications == ''){
           return this.arrayNotificaciones = [];
       }else{
           //capturo la ultima notificacion agregada
            this.arrayNotificaciones = Object.values(this.notifications[0]);
            //validacion por indice fuera de rango
            if(this.arrayNotificaciones.length > 3){
                //si el tamaño es > 3 es cuando las notificaciones son obtenidas desde el mismo servidor
                 return Object.values(this.arrayNotificaciones[4]);
            } else {
                //si el tamaño es < 3 es cuando las notificaciones son obtenidas desde el canal privado con laravel echo
                 return Object.values(this.arrayNotificaciones[0]);
            }

       }
      }
  }  
}
</script>
<style>
.app-header.navbar .dropdown-item2 {
    min-width: 300px;
}
</style>

