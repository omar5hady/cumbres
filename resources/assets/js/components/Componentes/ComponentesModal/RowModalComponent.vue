<template>
    <div class="form-group row">
        <label :for="id1" v-if="label1.length > 0" class="col-md-2 form-control-label">
            {{label1}}
            <span v-show="span1" style="color:red">*</span>
        </label>
        <div :class="clsRow1">
            <slot></slot>
        </div>
        <label :for="id2" v-if="label2" class="form-control-label" :class="label2.length >= 8 ? 'col-md-2' : 'col-md-1'">
            {{label2}}
            <span v-show="span2" style="color:red">*</span>
        </label>
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
        span1:{
            type:Boolean,
            required:false,
            default: false
        },
        span2:{
            type:Boolean,
            required:false,
            default: false
        },
        id1:{
            type:String,
            required:false,
            default:"",
        },
        id2:{
            type:String,
            required:false,
            default:"",
        }
    }
};
</script>

<style scoped>
</style>
