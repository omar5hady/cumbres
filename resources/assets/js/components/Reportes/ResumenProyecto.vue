<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Resumen de Proyecto
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <select class="form-control" @click="selectEtapas(b_proyecto)" v-model="b_proyecto" >
                                        <option value="">Fraccionamiento</option>
                                        <option v-for="proyecto in arrayFraccionamientos" :key="proyecto.id" :value="proyecto.id" v-text="proyecto.nombre"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <select class="form-control" v-model="b_etapa" >
                                        <option value="">Etapa</option>
                                        <option v-for="etapa in arrayAllEtapas" :key="etapa.id" :value="etapa.id" v-text="etapa.num_etapa"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <button type="submit" @click="listarResumen(1,b_proyecto,b_etapa)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row" v-if="mostrar == 1">
                            <div class="col-md-3">
                                <div class="input-group">
                                   <table class="table table-bordered table-striped table-sm">
                                       <tr>
                                           <th>Total de Lotes</th>
                                           <th v-text="lotes"></th>
                                       </tr>
                                       <tr>
                                           <th>Lotes en venta</th>
                                           <th v-text="habilitados"></th>
                                       </tr>
                                       <tr>
                                           <th>Individualizadas</th>
                                           <th v-text="individualizadas"></th>
                                       </tr>
                                       <tr>
                                           <th>Vendidos</th>
                                           <th v-text="vendidas"></th>
                                       </tr>
                                       <tr>
                                           <th>Disponibles</th>
                                           <th v-text="disponibles"></th>
                                       </tr>
                                   </table>
                                </div>
                            </div>
                            <div class="col-md-3"></div>
                            <div class="col-md-5">
                                <div class="input-group">
                                   <table class="table table-bordered table-striped table-sm">
                                       <tr>
                                           <th>Total Precio Venta</th>
                                           <th v-text="'$'+formatNumber(precio_venta)"></th>
                                       </tr>
                                       <tr>
                                           <th>Total Enganche</th>
                                           <th v-text="'$'+formatNumber(enganche)"></th>
                                       </tr>
                                       <tr>
                                           <th>Total Crédito</th>
                                           <th v-text="'$'+formatNumber(credito)"></th>
                                       </tr>
                                       <tr>
                                           <th colspan="2"></th>
                                       </tr>
                                       <tr>
                                           <th>Monto cobrado</th>
                                           <th v-text="'$'+formatNumber(monto_cobrado)"></th>
                                       </tr>
                                       <tr>
                                           <th>Saldo por cobrar</th>
                                           <th v-text="'$'+formatNumber(saldo)"></th>
                                       </tr>
                                   </table>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive" v-if="mostrar == 1">
                        <table class="table2 table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Manzana</th>
                                    <th># Lote</th>
                                    <th>Modelo</th>
                                    <th>Direccion</th>
                                    <th>Venta</th>
                                    <th>Cliente</th>
                                    <th>Institución</th>
                                    <th>Tipo Crédito</th>
                                    <th>Precio venta</th>
                                    <th>Enganche</th>
                                    <th>Crédito</th>
                                    <th>Saldo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="contrato in arrayResProyecto" :key="contrato.id">
                                    <td class="td2" v-text="contrato.manzana"></td>
                                    <td class="td2" v-text="contrato.num_lote"></td>
                                    <td class="td2" v-text="contrato.modelo"></td>
                                    <td class="td2" v-text="contrato.calle + ' Num. '+ contrato.numero"></td>
                                    <td class="td2" v-text="contrato.fecha_status"></td>
                                    <td class="td2" v-text="contrato.nombre_cliente"></td>
                                    <td class="td2" v-text="contrato.institucion"></td>
                                    <td class="td2" v-text="contrato.tipo_credito"></td>
                                    <td class="td2" v-text="'$'+formatNumber(contrato.precio_venta - contrato.descuento_promocion + contrato.costo_paquete)"></td>
                                    <td class="td2" v-text="'$'+formatNumber(contrato.total_pagar)"></td>
                                    <td class="td2" v-text="'$'+formatNumber(contrato.monto_total_credito)"></td>
                                    <td class="td2" v-text="'$'+formatNumber(contrato.saldo)"></td>
                                </tr>                                
                            </tbody>
                        </table>
                        </div>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,b_proyecto,b_etapa)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,b_proyecto,b_etapa)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,b_proyecto,b_etapa)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
        </main>
</template>

<script>
    export default {
        data (){
            return {
                proceso:false,
                arrayResProyecto : [],
                arrayFraccionamientos: [],
                arrayAllEtapas:[],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                b_proyecto : '',
                b_etapa : '',
                precio_venta:0,
                enganche:0,
                credito:0,
                saldo:0,
                monto_cobrado:0,
                lotes:0,
                disponibles:0,
                vendidas:0,
                individualizadas:0,
                habilitados:0,
                mostrar : 0,
            }
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;             

            }
        },
        methods : {
            listarResumen (page,proyecto,etapa){
                let me=this;
                var url= '/estadisticas/res_proyecto?page=' + page + '&proyecto='+ proyecto + '&etapa='+ etapa;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayResProyecto = respuesta.resContratos.data;
                    me.pagination= respuesta.pagination;
                    me.lotes = respuesta.lotes;
                    me.disponibles = respuesta.disponibles;
                    me.vendidas = respuesta.vendidas;
                    me.individualizadas = respuesta.individualizadas;
                    me.habilitados = respuesta.habilitados;

                    me.precio_venta = respuesta.sumas[0].precio - respuesta.sumas[0].descuento + respuesta.sumas[0].paquete;
                    me.enganche = respuesta.sumas[0].enganche;
                    me.credito = respuesta.sumas[0].credito_netoSum;
                    me.saldo = respuesta.sumas[0].totSaldo;

                    me.monto_cobrado = me.precio_venta - me.saldo;
                    me.mostrar = 1;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
            selectFraccionamientos(){
                let me = this;
                me.arrayFraccionamientos=[];
                var url = '/select_fraccionamiento';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayFraccionamientos = respuesta.fraccionamientos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectEtapas(buscar){
                let me = this;
                
                me.arrayAllEtapas=[];
                var url = '/select_etapa_proyecto?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayAllEtapas = respuesta.etapas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page,proyecto,etapa){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarResumen(page,proyecto,etapa);
            }
        },
        mounted() {
            this.selectFraccionamientos();
        }
    }
</script>
<style>    
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: fixed !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
    .table2 {
    margin: auto;
    border-collapse: collapse;
    overflow-x: auto;
    display: block;
    width: fit-content;
    max-width: 100%;
    box-shadow: 0 0 1px 1px rgba(0, 0, 0, .1);
    }

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
