<template>

</template>

<script>
import TableComponent from '../Componentes/TableComponent.vue';
import Button from '../Componentes/ButtonComponent.vue';

export default {
    props:{
        rolId: {type: String},
        arrayData: {type: Array}
    },
    components: {
        TableComponent,
        Button
    },
    data() {
        return {
            search: {
                buscar: '',
                b_etapa:'',
                b_manzana: '',
                b_lote: '',
                b_empresa: '',
                criterio: '',
            },
            band: 0
        }
    },
    methods: {

    },
}

</script>

<style>
</style>
