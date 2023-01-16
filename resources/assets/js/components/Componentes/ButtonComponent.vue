<template>
    <button class="btn" :class="`${btnClass} ${size}`"
        @click="$emit( 'click' )">
        <i v-if="icon != ''" :class="icon"></i>&nbsp;
        <slot></slot>
    </button>
</template>

<script>
export default {
    props:{
        btnClass:{
            type: String,
            required: false
        },
        icon:{
            type: String,
            required: false
        },
        size:{
            type:String,
            default:'',
        },
    }
};
</script>

<style scoped>
</style>
