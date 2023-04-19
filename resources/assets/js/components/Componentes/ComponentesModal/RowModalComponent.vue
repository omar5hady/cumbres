<template>
    <div class="form-group row">
        <label v-if="label1.length > 0" class="col-md-2 form-control-label" for="text-input">{{label1}}</label>
        <div :class="clsRow1">
            <slot></slot>
        </div>
        <label v-if="label2" class="form-control-label" for="text-input" :class="label2.length >= 8 ? 'col-md-2' : 'col-md-1'">{{label2}}</label>
        <div v-if="clsRow2 != ''" :class="clsRow2">
            <slot name="input2"></slot>
        </div>
    </div>
</template>

<script>
export default {
    props:{
        label1:{
            type: String,
            required: true
        },
        label2:{
            type: String,
            required: false,
            default: null
        },
        clsRow1:{
            type: String,
            required: false,
            default: 'col-md-4'
        },
        clsRow2:{
            type:String,
            required: false,
            default:'',
        },
    }
};
</script>

<style scoped>
</style>
