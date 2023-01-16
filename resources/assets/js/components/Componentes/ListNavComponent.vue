<template>
    <li class="page-item" @click="$emit( 'click' )">
        <a class="page-link" href="#">
            <slot></slot>
        </a>
    </li>
</template>

<script>
export default {
    props:{

    }
};
</script>

<style scoped>
</style>
