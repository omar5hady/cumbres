<template>
    <nav>
        <!--Botones de paginacion -->
        <ul class="pagination">
            <ListNav v-if="current > 5" @click="onClick(1)">
                Inicio
            </ListNav>
            <ListNav v-if="current > 1" @click="onClick(current - 1)">
                Ant
            </ListNav>
            <ListNav v-if="current - 3 >= 1" @click="onClick(current - 3)">
                {{current - 3}}
            </ListNav>
            <ListNav v-if="current - 2 >= 1" @click="onClick(current - 2)">
                {{current - 2}}
            </ListNav>
            <ListNav v-if="current - 1 >= 1" @click="onClick(current - 1)">
                {{current - 1}}
            </ListNav>
            <ListNav class="page-item active" @click="onClick(current)">
                {{current}}
            </ListNav>
            <ListNav v-if="current + 1 <= last" @click="onClick(current + 1)">
                {{current + 1}}
            </ListNav>
            <ListNav v-if="current + 2 <= last" @click="onClick(current + 2)">
                {{current + 2}}
            </ListNav>
            <ListNav v-if="current + 3 <= last" @click="onClick(current + 3)">
                {{current + 3}}
            </ListNav>
            <ListNav v-if="current < last" @click="onClick(current + 1)">
                Sig
            </ListNav>
            <ListNav v-if="current < 5 && last > 5" @click="onClick(last)">
                Último
            </ListNav>
        </ul>
    </nav>
</template>

<script>
import ListNav from "../Componentes/ListNavComponent.vue";
export default {
    components: {
        ListNav
    },
    props: {
        current:{
            type: Number,
            required: true,
        },
        last:{
            type: Number,
            required: true,
        }
    },
  computed: {

  },
  methods: {
    onClick(value) {
      this.$emit('changePage', value);
    },
  },
}
</script>
