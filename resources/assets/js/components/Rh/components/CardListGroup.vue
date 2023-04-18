<template>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">
                    <font style="vertical-align: inherit;">
                        <font style="vertical-align: inherit;"
                            >{{titulo}} </font
                        >
                    </font>
                </h6>
                <!---->
            </div>
            <div class="card-body row">
                <div class="col-md-6">
                    <ListGroupVue :data="data"></ListGroupVue>
                </div>
                <div class="col-md-6">
                    <ListGroupVue :data="data2"></ListGroupVue>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ListGroupVue from './ListGroup.vue';
export default {
    components:{
        ListGroupVue
    },
    props:{
        titulo:{
            type: String,
            required: false,
            default: ''
        },
        data:{
            type: Array,
            required: false,
        },
        data2:{
            type: Array,
            required: false,
        }
    }
};
</script>

<style scoped>
</style>

