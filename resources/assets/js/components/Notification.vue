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
            <a class="dropdown-item" href="#">
                <i class="fa fa-envelope-o"></i> {{item.pendientes.msj}}
                <span class="badge badge-success">{{item.pendientes.numero}}</span>
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
  props: ['notifications'],
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

