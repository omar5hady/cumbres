<template>
    <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card scroll-box">
                    <div class="card-header">
                    </div>
                    <div class="info-center" v-if="isLoading">
                        <LoadingComponent></LoadingComponent>
                    </div>
                    <div class="card-body" v-else>
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input v-model="b_fecha1" type="date" class="form-control"/>
                                    <input v-model="b_fecha2" type="date" class="form-control"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <input type="text" v-model="b_cliente" @keyup.enter="indexCotizaciones(1)" class="form-control" placeholder="Cliente a buscar">
                                    <Button :icon="'fa fa-search'" @click="indexCotizaciones(1)">Buscar</Button>
                                </div>
                            </div>
                        </div>
                        <TableComponent :cabecera="['Opciones','Cliente','Proyecto','Etapa','Manzana','Lote','Precio total']">
                            <template v-slot:tbody>
                                <tr v-for="cotizacion in cotizaciones.data" :key="cotizacion.id">
                                    <td class="td2">
                                        <a title="Imprimir Cotización" class="btn btn-scarlet" target="_blank"
                                            :href="'/cotizacion/printCotizacion?id='+ cotizacion.id"><i class="fa fa-file-pdf-o"></i></a>
                                    </td>
                                    <td class="td2" v-text="cotizacion.cliente"></td>
                                    <td class="td2" v-text="cotizacion.proyecto"></td>
                                    <td class="td2" v-text="cotizacion.etapa"></td>
                                    <td class="td2" v-text="cotizacion.manzana"></td>
                                    <td class="td2">
                                        {{ cotizacion.num_lote }} {{ cotizacion.sublote ? cotizacion.sublote : '' }}
                                    </td>
                                    <td class="td2" style="font-weight: bold;" v-text="'$'+$root.formatNumber(cotizacion.total)"></td>
                                </tr>
                            </template>
                        </TableComponent>
                        <Nav v-if="cotizaciones"
                            :current="cotizaciones.current_page"
                            :last="cotizaciones.last_page"
                            @changePage="indexCotizaciones"
                        ></Nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>

        </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    import TableComponent from '../Componentes/TableComponent.vue'
    import LoadingComponent from '../Componentes/LoadingComponent.vue'
    import Nav from '../Componentes/NavComponent.vue'
    import Button from '../Componentes/ButtonComponent.vue'

    export default {
        components:{
            TableComponent,
            LoadingComponent,
            Button,
            Nav
        },
        data(){
            return{
                isLoading: true,
                cotizaciones: [],
                b_cliente: '',
                b_fecha1: '',
                b_fecha2: '',
            }
        },
        computed:{

        },
        methods : {
            /**Metodo para mostrar los registros */
            indexCotizaciones(page){
                let me = this;
                me.isLoading = true;
                var url = '/cotizacion/indexCotizaciones?page=' + page + '&b_cliente=' + me.b_cliente + '&b_fecha1=' + me.b_fecha1 + '&b_fecha2=' + me.b_fecha2;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.cotizaciones = respuesta;
                    me.isLoading = false;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        },
        mounted() {
            this.indexCotizaciones(1)
        }
    }
</script>
<style>
    .td2, .th2 {
        border: solid rgb(200, 200, 200) 1px;
        padding: .5rem;
    }
    .td2 {
        white-space: nowrap;
        border-bottom: none;
        color: rgb(20, 20, 20);
    }
    .td2:first-of-type, th:first-of-type {
       border-left: none;
    }
    .td2:last-of-type, th:last-of-type {
       border-right: none;
    }
</style>
