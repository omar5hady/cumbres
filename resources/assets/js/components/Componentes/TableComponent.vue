<template>
    <div class="table-responsive">
        <table class="table2 table-bordered table-striped table-sm">
            <thead>
                <template v-if="cabecera">
                    <tr>
                        <th v-for="(titulo,index) in cabecera" :key="index+titulo" v-html="titulo"></th>
                    </tr>
                </template>
                <slot name="thead"></slot>
            </thead>
            <tbody>
                <slot name="tbody"></slot>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props:{
        cabecera:{
            type: Array,
            required: false
        }
    }
};
</script>

<style scoped>
::-webkit-scrollbar {
    width: 2px;
}
  /* Handle */
  ::-webkit-scrollbar-thumb {
    background: rgb(180, 180, 180);
  }

  /* Handle on hover */
  ::-webkit-scrollbar-thumb:hover {
    background: #a5a6a6;
  }

.table2 {
    margin: auto;
    border-collapse: collapse;
    overflow-x: auto;
    display: block;
    width: fit-content;
    max-width: 100%;
    box-shadow: 0 0 1px 1px rgba(0, 0, 0, 0.1);
}

.td2,
.th2 {
    border: solid rgb(200, 200, 200) 1px;
    padding: 0.5rem;
}

.td2 {
    white-space: nowrap;
    border-bottom: none;
    color: rgb(20, 20, 20);
}

.td2:first-of-type,
th:first-of-type {
    border-left: none;
}

.td2:last-of-type,
th:last-of-type {
    border-right: none;
}
</style>
