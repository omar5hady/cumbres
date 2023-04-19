<template>
    <div class="col-sm-6 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <font style="vertical-align: inherit;">
                        <font style="vertical-align: inherit;"
                            >{{title}}</font
                        >
                    </font>
                </h5>
                <!---->
            </div>
            <div class="card-body" style="padding-bottom: 0px;">
                <p>{{description}}</p>
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
        title:{
            type: String,
            required: false,
            default: ''
        },
        description:{
            type: String,
            required: false,
        },
    }
};
</script>

<style scoped>
</style>

