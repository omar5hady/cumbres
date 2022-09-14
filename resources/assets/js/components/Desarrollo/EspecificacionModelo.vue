<template >
    <main class="main" >
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card scroll-box">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i>Especificaciones de Modelo
                        &nbsp;&nbsp;
                        <!--   Boton   -->
                        <button type="button" class="btn btn-success btn-sm" @click="verEspecificaciones('masa')" v-if="vista==0 && b_modelo != ''">
                            <i class="icon-pencil"></i>&nbsp;Asignar especificaciones
                        </button>

                        <button type="button" v-if="vista == 1" class="btn btn-primary btn-sm" @click="vista = 0">
                            <i class="fa fa-mail-reply"></i>&nbsp;Regresar a listado
                        </button>

                        <!---->
                    </div>
                    <div class="card-body" v-if="vista == 0">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">

                                    <select class="form-control" v-model="buscar" @click="selectEtapa(buscar), selectModelo(buscar)" >
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>

                                    <select class="form-control" v-model="buscar2" @keyup.enter="listarLote(1)">
                                        <option value="">Etapa</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                    </select>
                                </div>
                                <div class="input-group">
                                    <select class="form-control" v-model="b_modelo" @change="listarLote(1)">
                                            <option value="">Modelo</option>
                                            <option v-for="modelos in arrayModelos" :key="modelos.id" :value="modelos.id" v-text="modelos.nombre"></option>
                                        </select>

                                </div>
                                <div class="input-group">
                                    <input type="text" v-model="buscar3" @keyup.enter="listarLote(1)" class="form-control" placeholder="Manzana a buscar">
                                    <input type="text" v-model="b_lote" @keyup.enter="listarLote(1)" class="form-control" placeholder="Lote a buscar">
                                </div>

                                <div class="input-group">
                                    <button type="submit" @click="listarLote(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <TableComponent>
                            <template v-slot:thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" @click="selectAll" v-model="allSelected">
                                    </th>
                                    <th>Proyecto</th>
                                    <th>Etapa</th>
                                    <th>Manzana</th>
                                    <th># Lote</th>
                                    <th>Modelo</th>
                                    <th>Especificaciones</th>
                                </tr>
                            </template>
                            <template v-slot:tbody>
                                <tr v-for="lote in arrayLote" :key="lote.id">
                                    <td class="td2">
                                        <input type="checkbox"  @click="select" :id="lote.id" :value="lote.id" v-model="lotes_ini" >
                                    </td>
                                    <td class="td2" v-text="lote.proyecto"></td>
                                    <td class="td2" v-text="lote.etapa"></td>
                                    <td class="td2" v-text="lote.manzana"></td>
                                    <td class="td2" v-text="lote.num_lote"></td>
                                    <td class="td2" v-text="lote.modelo"></td>
                                    <td>
                                        <button v-if="lote.especificaciones.length"
                                            title="Ver especificaciones" type="button"
                                            @click="verEspecificaciones('especificaciones',lote)" class="btn btn-scarlet btn-sm">
                                            <i class="fa fa-cogs"></i>
                                        </button>
                                    </td>

                                </tr>
                            </template>
                        </TableComponent>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page > 7">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(1)">Inicio</a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1)">Sig</a>
                                </li>
                                <li class="page-item" v-if="pagination.last_page > 7 && pagination.current_page<pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.last_page)">Ultimo</a>
                                </li>
                            </ul>
                        </nav>
                    </div>

                    <div class="card-body" v-if="vista >= 1">

                        <div id="accordion" role="tablist">
                            <div class="card mb-0" v-for="especificacion in especificaciones" :key="especificacion.general">
                                <div class="card-header" id="headingOne" role="tab">
                                    <h5 class="mb-0">
                                        <a href="#" @click="tab=especificacion.general" class="collapsed">{{especificacion.general}}</a>&nbsp;
                                         <button type="button" v-if="tab == especificacion.general"
                                            @click="addRegistro(especificacion.general, id)" class="btn btn-sm btn-success" title="Nuevo">
                                            <i class="icon-plus"></i>
                                        </button>
                                    </h5>
                                </div>
                                <div v-if="tab == especificacion.general" style="">
                                <!--Datos Prospecto-->
                                    <div class="form-group row border border-primary" style="margin-right:0px; margin-left:0px;">
                                        <div class="col-md-12"><br></div>
                                        <template v-if="nuevo == 1">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control" v-model="nuevoDet.subconcepto" placeholder="Subconcepto">
                                                <textarea v-model="nuevoDet.descripcion" rows="4" class="form-control"></textarea>
                                                <div class="form-group">
                                                    <button v-if="vista == 1"
                                                        type="button" @click="guardarNuevo(nuevoDet)" class="btn btn-success">
                                                        <i class="icon-check"></i>&nbsp;Guardar nuevo registro
                                                    </button>
                                                    <button v-if="vista == 2"
                                                        type="button" @click="addNuevo(nuevoDet)" class="btn btn-success">
                                                        <i class="icon-check"></i>&nbsp;Agregar a plantilla
                                                    </button>
                                                    <button type="button" @click="nuevo=0" class="btn btn-scarlet">
                                                        <i class="icon-close"></i>&nbsp;Cancelar
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-md-2"></div>
                                        </template>


                                        <div class="col-md-6" v-for="det in especificacion.detalle" :key="det.id">
                                            <div class="form-group">
                                                <input type="text" class="form-control" v-model="det.subconcepto" placeholder="Subconcepto">
                                                <textarea v-model="det.descripcion" rows="5" class="form-control"></textarea>
                                                <div class="form-group" v-if="vista == 1">
                                                    <button type="button" @click="guardarCambios(det)" class="btn btn-success">
                                                        <i class="icon-check"></i>&nbsp;Guardar cambios
                                                    </button>
                                                    <button type="button" @click="eliminar(det)" class="btn btn-scarlet">
                                                        <i class="icon-trash"></i>&nbsp;Eliminar
                                                    </button>
                                                </div>
                                                <div class="form-group" v-if="vista == 2">
                                                    <button type="button" @click="unset(det)" class="btn btn-scarlet">
                                                        <i class="icon-trash"></i>&nbsp;Eliminar
                                                    </button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                <!-- Fin datos prospecto-->
                                </div>
                            </div>

                            <div class="col-md-12" v-if="vista == 2">
                                <div class="form-group">
                                    <button
                                        type="button" @click="setEspecifiacionesMasa(nuevoDet)" class="btn btn-success">
                                        <i class="icon-check"></i>&nbsp;Guardar plantilla para los lotes seleccionados
                                    </button>
                                </div>
                            </div>
                        </div>


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

import _ from 'lodash'
    export default {
        components:{
            TableComponent
        },
        data(){
            return{
                proceso:false,
                allSelected: false,
                lotes_ini : [],
                id: 0,
                b_modelo: '',
                b_habilitado: 1,
                b_lote: '',
                arrayLote : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                buscar2 : '',
                buscar3 : '',
                buscar : '',
                arrayFraccionamientos : [],
                arrayEtapas : [],
                arrayModelos : [],
                arrayManzanas: [],
                especificaciones:{},
                vista:0,
                tab:'',
                nuevoDet:{},
                nuevo:0,
            }
        },
        computed:{

            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber:function(){
                if(!this.pagination.to){
                    return [];
                }

                var from = this.pagination.current_page - this.offset;
                if(from < 1){
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to){
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            },

        },


        methods : {
            selectAll: function() {
            this.lotes_ini = [];

            if (!this.allSelected) {
                for (var lote in this.arrayLote
                ) {
                    this.lotes_ini.push(this.arrayLote[lote].id.toString());
                }
            }
            },

             select: function() {
                this.allSelected = false;
            },

            /**Metodo para mostrar los registros */
            listarLote(page){
                let me = this;
                var url = '/lote/dispEspecificaciones?page=' + page + '&proyecto=' + this.buscar + '&etapa=' + this.buscar2
                            + '&manzana=' + this.buscar3  + '&modelo=' + this.b_modelo + '&lote=' + this.b_lote;

                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLote = respuesta.lotes.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            getEspecificaciones(modelo_id){
                let me = this;
                var url = 'specification?modelo_id=' + modelo_id;

                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.especificaciones = respuesta
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            guardarCambios(especificacion){
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.put('/modelos/updateEspecificacion',{
                    'id': especificacion.id,
                    'subconcepto': especificacion.subconcepto,
                    'descripcion' : especificacion.descripcion,
                    'lote_id' : especificacion.lote_id
                }).then(function (response){
                    me.listarLote(me.pagination.current_page)
                    //Se muestra mensaje Success
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Cambio guardado'
                    })
                }).catch(function (error){
                    console.log(error);
                });

            },

            addNuevo(especificacion){
                let me = this;
                const index = me.especificaciones.map( e => e.general ).indexOf( especificacion.general )
                me.especificaciones[index].detalle.push(
                    especificacion
                )
                me.nuevoDet={};
                me.nuevo = 0;
            },

            guardarNuevo(especificacion){
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/modelos/storeEspecificacion',{
                    'lote_id': especificacion.lote_id,
                    'general': especificacion.general,
                    'subconcepto': especificacion.subconcepto,
                    'descripcion': especificacion.descripcion,
                }).then(function (response){
                    const index = me.especificaciones.map( e => e.general ).indexOf( especificacion.general )
                    me.especificaciones[index].detalle.push(
                       response.data
                    )
                    me.nuevoDet={};
                    me.nuevo = 0;
                    me.listarLote(me.pagination.current_page)
                    //Se muestra mensaje Success
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Especifiación registrada'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            setEspecifiacionesMasa(){
                let me = this;
                swal({
                    title: '¿Desea asignar la plantilla?',
                    text: "Esta acción no se puede revertir!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Si, asignar!'
                }).then((result) => {
                if (result.value) {
                    //Con axios se llama el metodo store de FraccionaminetoController
                    axios.post('/modelos/setEspecifiacionesMasa',{
                        'lotes': me.lotes_ini,
                        'especifiaciones': me.especificaciones
                    }).then(function (response){
                        me.vista = 0;
                        me.especificaciones = {};
                        me.listarLote(me.pagination.current_page);
                        //Se muestra mensaje Success
                        const toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                            });
                            toast({
                            type: 'success',
                            title: 'Especifiaciones asignadas correctamente'
                        })
                    }).catch(function (error){
                        console.log(error);
                    });
                }})


            },

            unset(especificacion){
                let me = this;
                const index = me.especificaciones.map( e => e.general ).indexOf( especificacion.general )
                me.especificaciones[index].detalle = me.especificaciones[index].detalle.filter( a => a.id !== especificacion.id)
            },

            eliminar(especificacion){
                let me = this;

                swal({
                    title: '¿Desea eliminar?',
                    text: "Esta acción no se puede revertir!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Si, eliminar!'
                }).then((result) => {
                if (result.value) {

                    axios.delete('/modelos/deleteEspecificacion', {
                        params: {'id': especificacion.id}
                    }).then(function (response){
                        const index = me.especificaciones.map( e => e.general ).indexOf( especificacion.general )
                        me.especificaciones[index].detalle = me.especificaciones[index].detalle.filter( a => a.id !== especificacion.id)
                        me.listarLote(me.pagination.current_page);
                        //Se muestra mensaje Success
                        const toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                            });
                            toast({
                            type: 'success',
                            title: 'Especificacion eliminada'
                        })
                    }).catch(function (error){
                        console.log(error);
                    });

                }})

            },

            cambiarPagina(page){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarLote(page);
            },

            selectFraccionamientos(){
                let me = this;
                if(me.modal == 0){
                me.buscar=""
                me.buscar2=""
                me.buscar3=""
                }

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
            selectEtapa(buscar){
                let me = this;
                if(me.modal == 0){

                me.buscar2=""
                me.buscar3=""
                }

                me.arrayEtapas=[];
                var url = '/select_etapa_proyecto?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEtapas = respuesta.etapas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectModelo(buscar){
                let me = this;

                me.arrayModelos=[];
                var url = '/select_modelo_proyecto?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayModelos = respuesta.modelos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            addRegistro(general,id){
                this.nuevo = 1;
                this.nuevoDet = {
                    'general' : general,
                    'subconcepto' : 'Subconcepto',
                    'descripcion' : 'Descripcion',
                    'lote_id' : id
                }
            },


            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            verEspecificaciones(opcion, data=[]){

                switch(opcion){
                    case 'especificaciones':{
                        this.vista = 1;
                        this.especificaciones = data['especificaciones'];
                        this.id = data['id'];
                        break;
                    }
                    case 'masa':{
                        if(this.lotes_ini.length<1){
                            Swal({
                                title: 'No se ha seleccionado ningun lote',
                                animation: false,
                                customClass: 'animated tada'
                            })
                            return;
                        }
                        this.vista = 2;
                        this.getEspecificaciones(this.b_modelo);
                        break;
                    }
                }

            }
        },
        mounted() {
            this.selectFraccionamientos();
            this.listarLote(1);
        }
    }
</script>
<style>
    .div-error{
        display:flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
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
    .pointer{
        cursor: pointer;
    }
    .pointer:hover{
        color: #fff;
        background-color: #1b8eb7;
        border-color: #00b0bb;;
    }
</style>
