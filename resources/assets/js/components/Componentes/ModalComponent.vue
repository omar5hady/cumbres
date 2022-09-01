<template>
  <div class="modal mostrar animated fadeIn"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-primary" :class="size"    role="document"> 
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-text="titulo"></h5>
                        <button type="button" class="close" @click="$emit( 'closeModal' )" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <slot name="body"></slot>
                    </div>
                    <!-- Botones del modal -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="$emit( 'closeModal' )">Cerrar</button>
                        <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                        <slot name="buttons-footer"></slot>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
</template>



<script>
export default {
    props: {
        titulo:{
            type: String,
            required: true
        },
        size:{
            type:String,
            default:'modal-lg',
        }
    },

    methods:{
    }

}
</script>

<style scoped>
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: fixed !important;
        background-color: #3c29297a !important;
        overflow-y: auto;
    }
    .div-error{
        display:flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>