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
                        <a class="list-group-item list-group-item-action flex-column align-items-start active" href="#">
                        <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">List group item heading</h5>
                        <small>3 days ago</small>
                        </div>
                        <p class="mb-1">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
                        <small>Donec id elit non mi porta.</small>
                        </a>
                        <a class="list-group-item list-group-item-action flex-column align-items-start" href="#">
                        <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">List group item heading</h5>
                        <small class="text-muted">3 days ago</small>
                        </div>
                        <p class="mb-1">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
                        <small class="text-muted">Donec id elit non mi porta.</small>
                        </a>
                        <a class="list-group-item list-group-item-action flex-column align-items-start" href="#">
                        <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">List group item heading</h5>
                        <small class="text-muted">3 days ago</small>
                        </div>
                        <p class="mb-1">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
                        <small class="text-muted">Donec id elit non mi porta.</small>
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
            arrayNotificaciones : []
        }
    },
    methods: {
          listarNotificaciones(){
                let me = this;
                var url = '/notification/getListado';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayNotificaciones = respuesta.notificaciones.data;
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
