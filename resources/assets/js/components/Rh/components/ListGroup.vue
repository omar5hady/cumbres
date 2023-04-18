<template>
    <ol class="list-group list-group-numbered">
        <list-item-vue
            v-for="e in data" :key="e.antecedente"
            :antecedente="e.antecedente"
            :especificacion="e.especificacion"
            :estado="e.estado"
        ></list-item-vue>
    </ol>
</template>

<script>
import ListItemVue from './ListItem.vue';
export default {
    components:{
        ListItemVue
    },
    props:{
        data:{
            type: Array,
            required: false,
            default: []
        },
    }
};
</script>

<style scoped>
</style>
