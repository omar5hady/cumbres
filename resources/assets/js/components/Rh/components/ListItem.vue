<template>
     <li class="list-group-item d-flex justify-content-between align-items-start">
        <div class="ms-2 me-auto">
            <div class="fw-bold">
                <h6>{{antecedente}}</h6>
            </div>
            <div>{{especificacion}}</div>
        </div>
        <span class="badge rounded-pill" :class="estado ? 'bg-success' : 'bg-danger'">
            {{estado ? 'Si' : 'No'}}
        </span>
    </li>
</template>

<script>
import ListGroupVue from './ListGroup.vue';
export default {
    components:{
        ListGroupVue
    },
    props:{
        antecedente:{
            type: String,
            required: true,
            default: ''
        },
        especificacion:{
            type: String,
            required: false,
            default: ''
        },
        estado:{
            type: Number,
            required: true,
            default: 0
        },
    }
};
</script>

<style scoped>
</style>

