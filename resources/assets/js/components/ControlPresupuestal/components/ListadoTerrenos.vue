<template>
    <div class="card-body">
        <div class="form-group row">
            <div class="col-md-10">
                <div class="input-group">
                    <input
                        type="text"
                        v-model="search.buscar"
                        @keyup.enter="
                            $emit('getData', search)
                        "
                        class="form-control"
                        placeholder="Texto a buscar"
                    />
                </div>
                <div class="input-group">
                    <Button @click="$emit('getData', search)"
                        icon="fa fa-search">
                        Buscar
                    </Button>
                </div>
            </div>
        </div>
        <TableComponent v-if="data.length > 0">
            <template v-slot:thead>
                <tr>
                    <th></th>
                    <th v-for="columna in columnas" :key="columna.campo" v-html="columna.title"></th>
                </tr>
            </template>
            <template v-slot:tbody>
                <tr v-for="item in data" :key="item.id">
                    <td class="td2">
                        <Button title="Editar"
                            @click="$emit('abrirModal', { opcion: 'editar', data: item })"
                            btnClass="btn-warning btn-sm"
                            icon="icon-pencil">
                        </Button>
                        <Button title="Eliminar"
                            btnClass="btn-danger btn-sm"
                            @click="eliminar(item.id)"
                            icon="icon-trash">
                        </Button>
                    </td>
                    <td class="td2" v-for="columna in columnas" :key="columna.campo">
                        {{ item[columna.campo] }}
                    </td>
                </tr>
            </template>
        </TableComponent>
        <center v-else>
            <h2>Sin resultados</h2>
        </center>
    </div>
</template>
<script>
import TableComponent from "../../Componentes/TableComponent.vue";
import Button from "../../Componentes/ButtonComponent.vue";

export default {
    components: {
        TableComponent,
        Button
    },
    props: {
        data: Array
    },
    data() {
        return {
            search: {
                buscar: "",
            },
            columnas: [
                { title: 'Identificador', campo: 'nombre' },
                { title: 'Dirección', campo: 'direccion' },
                { title: 'Tamaño m<sup>2</sup>', campo: 'tamanio' },
                { title: 'Comprador', campo: 'comprador' },
                { title: 'Vendedor', campo: 'vendedor' },
            ]
        };
    },
    methods: {
        eliminar(id){
            let me = this;

            const url = `/terrenos-compra/${id}`

            axios.delete( url ,{
                params: {'id': id}
            }).then(function (response){
                me.$emit('getData', me.search)
                //Se muestra mensaje Success
                swal({
                    position: 'top-end',
                    type: 'success',
                    title: 'Registro eliminado correctamente',
                    showConfirmButton: false,
                    timer: 1500
                    })
            }).catch(function (error){
            });
        },
    },
};
</script>
<style>
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
