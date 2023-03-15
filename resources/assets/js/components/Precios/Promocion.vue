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
                        <i class="fa fa-align-justify"></i> Promociones
                        <!--   Boton Nuevo    -->
                        <Button :btnClass="'btn-secondary'" :icon="'icon-plus'" @click="abrirModal('promocion','registrar')">Nuevo</Button>
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-4" v-model="criterio">
                                        <option value="promociones.nombre">Promocion</option>
                                        <option value="fraccionamientos.id">Proyecto</option>
                                    </select>
                                    <select class="form-control" v-if="criterio=='fraccionamientos.id'" v-model="buscar" @change="selectEtapa(buscar), buscar2=''">
                                        <option value="">Seleccione</option>
                                        <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                    </select>
                                    <input type="text" v-else v-model="buscar" @keyup.enter="listarPromociones(1,buscar,buscar2,criterio)" class="form-control" placeholder="Texto a buscar">
                                </div>
                                <div class="input-group">
                                    <select class="form-control col-md-6" v-if="criterio=='fraccionamientos.id'" v-model="buscar2" @keyup.enter="listarPromociones(1,buscar,buscar2,criterio)">
                                        <option value="">Etapa</option>
                                        <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                    </select>
                                    <Button @click="listarPromociones(1,buscar,buscar2,criterio)" :icon="'fa fa-search'">Buscar</Button>
                                </div>
                            </div>

                        </div>
                        <TableComponent :cabecera="[
                            'Opciones','Proyecto','Etapa','Promocion','Descripcion','Descuento $','Inicio','Fin','Status',''
                        ]">
                            <template v-slot:tbody>
                                <tr v-for="promocion in arrayPromocion" :key="promocion.id">
                                    <td class="td2" style="width:10%">
                                        <Button :btnClass="'btn-warning'" :size="'btn-sm'" :icon="'icon-pencil'" title="Editar"
                                            @click="abrirModal('promocion','actualizar',promocion)"
                                        ></Button>
                                        <Button :btnClass="'btn-danger'" :size="'btn-sm'" :icon="'icon-trash'" title="Eliminar"
                                            @click="eliminarPromocion(promocion)"
                                        ></Button>
                                        <Button v-if="promocion.is_active == '1'" :btnClass="'btn-success2'" :size="'btn-sm'" :icon="'icon-share'" title="Asignar a Lote"
                                             @click="abrirModal2('lote_promocion','registrar',promocion), listarLotePromociones(1, promocion.id)"
                                        ></Button>
                                    </td>
                                    <td class="td2" v-text="promocion.proyecto" ></td>
                                    <td class="td2" v-text="promocion.etapas" ></td>
                                    <td v-text="promocion.nombre" ></td>
                                    <td v-text="promocion.descripcion" ></td>
                                    <td class="td2" v-text="'$'+formatNumber(promocion.descuento)" ></td>
                                    <td class="td2" v-text="promocion.v_ini" ></td>
                                    <td class="td2" v-text="promocion.v_fin" ></td>
                                    <td class="td2" v-if="promocion.is_active == '1'">
                                        <span class="badge badge-success">Activo</span>
                                    </td>
                                    <td class="td2" v-else>
                                        <span class="badge badge-danger">Desactivado</span>
                                    </td>
                                    <td v-if="promocion.is_active == '1'">
                                    </td>
                                    <td v-else-if="promocion.is_active == '0' && promocion.mostrar == 0">
                                        <button type="button" @click="promocion.mostrar = 1" class="btn btn-default btn-sm">
                                            <i class="icon-eye"></i>
                                        </button>
                                    </td>
                                    <td v-else>
                                        <button type="button" @click="promocion.mostrar = 0" class="btn btn-default btn-sm">
                                            <i class="icon-close"></i>
                                        </button>
                                        <br>
                                        {{promocion.lote}}
                                    </td>
                                </tr>
                            </template>
                        </TableComponent>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar, buscar2,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar, buscar2,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar, buscar2,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>

            <!--Inicio del modal agregar/actualizar-->
            <ModalComponent :titulo="tituloModal"
                @closeModal="cerrarModal()"
                v-if="modal"
            >
                <template v-slot:body>
                    <RowModal :clsRow1="'col-md-8'" :label1="'Nombre de la promoción'">
                        <textarea rows="3" cols="30" v-model="nombre" class="form-control" placeholder="Promoción"></textarea>
                    </RowModal>
                    <RowModal :clsRow1="'col-md-5'" :label1="'Proyecto'" :clsRow2="'col-md-4'" :label2="'Etapa'">
                        <select class="form-control" v-model="fraccionamiento_id" @change="selectEtapa(fraccionamiento_id)" >
                            <option value="0">Seleccione</option>
                            <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                        </select>
                        <template v-slot:input2>
                            <select class="form-control" v-model="etapa_id">
                                <option value="0">Seleccione</option>
                                <option v-for="e in arrayEtapas" :key="e.id" :value="e.id" v-text="e.num_etapa"></option>
                            </select>
                        </template>
                    </RowModal>
                    <RowModal :clsRow1="'col-md-4'" :label1="'Fecha de inicio'" :clsRow2="'col-md-4'" :label2="'Fecha de vencimiento'">
                        <input type="date" v-model="v_ini"  class="form-control" placeholder="Inicio">
                        <template v-slot:input2>
                            <input type="date" v-model="v_fin" class="form-control" placeholder="Vencimiento">
                        </template>
                    </RowModal>
                    <RowModal :label1="'Descuento $'">
                        <input type="text" pattern="\d*" v-model="cant_desc"
                            @change="calcularDesc()" class="form-control" placeholder="Descuento" v-on:keypress="isNumber($event)">
                    </RowModal>
                    <RowModal :label1="'Descuento terreno $'">
                        <input type="text" pattern="\d*" v-model="cant_terreno" @change="calcularDesc()"
                            class="form-control" placeholder="Descuento" v-on:keypress="isNumber($event)">
                    </RowModal>
                    <RowModal :label1="'Descuento ubicación $'">
                        <input type="text" pattern="\d*" v-model="cant_ubicacion" @change="calcularDesc()"
                            class="form-control" placeholder="Descuento" v-on:keypress="isNumber($event)">
                    </RowModal>
                    <RowModal :label1="'Descuento Total $'">
                        <h6><strong>$ {{$root.formatNumber(descuento)}}</strong></h6>
                    </RowModal>
                    <RowModal :clsRow1="'col-md-8'" :label1="'Descripción'">
                        <textarea rows="5" cols="30" v-model="descripcion" class="form-control" placeholder="Descripcion del paquete"></textarea>
                    </RowModal>
                    <RowModal :clsRow1="'col-md-8'" :label1="'Equipamiento incluido'">
                        <textarea rows="4" cols="30" v-model="desc_equipamiento" class="form-control"
                            placeholder="Descripcion del equipamiento incluido"></textarea>
                    </RowModal>

                    <!-- Div para mostrar los errores que mande validerPaquete -->
                    <div v-show="errorPromocion" class="form-group row div-error">
                        <div class="text-center text-error">
                            <div v-for="error in errorMostrarMsjPromocion" :key="error" v-text="error">

                            </div>
                        </div>
                    </div>
                </template>
                <template v-slot:buttons-footer>
                    <Button v-if="tipoAccion==1" :icon="'icon-check'" @click="registrarPromociones()">Guardar</Button>
                    <Button v-if="tipoAccion==2" :icon="'icon-check'" @click="actualizarPromociones()">Actualizar</Button>
                </template>
            </ModalComponent>
            <!--Fin del modal-->


            <!--Inicio del modal asignar Lote-->
            <ModalComponent :titulo="tituloModal2"
                @closeModal="cerrarModal2()"
                v-if="modal2"
            >
                <template v-slot:body>
                    <RowModal :clsRow1="'col-md-5'" :label1="'Proyecto'" :clsRow2="'col-md-4'" :label2="'Etapa'">
                        <select class="form-control" v-model="fraccionamiento_id" @change="selectEtapa(fraccionamiento_id)" disabled>
                            <option value="0">Seleccione</option>
                            <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                        </select>
                        <template v-slot:input2>
                            <select class="form-control" v-model="etapa_id" @change="selectManzanas(fraccionamiento_id,etapa_id)" disabled>
                                <option value="0">Seleccione</option>
                                <option v-for="e in arrayEtapas" :key="e.id" :value="e.id" v-text="e.num_etapa"></option>
                            </select>
                        </template>
                    </RowModal>
                    <RowModal :clsRow1="'col-md-5'" :label1="'Manzana'" :clsRow2="'col-md-4'" :label2="'Lote'">
                        <select class="form-control" v-model="manzana" @change="selectLotesManzana(fraccionamiento_id,etapa_id,manzana)">
                            <option value="">Seleccione</option>
                            <option v-for="manzanas in arrayManzanas" :key="manzanas.id" :value="manzanas.manzana" v-text="manzanas.manzana"></option>
                        </select>
                        <template v-slot:input2>
                            <select class="form-control" name = 'lotes_promo[]' multiple size = 6 v-model="lotes_promo">
                                <option value="0">Seleccione</option>
                                <option v-for="lotes in arrayLotes" :key="lotes.id" :value="lotes.id" v-text="lotes.num_lote"></option>
                            </select>
                        </template>
                    </RowModal>
                    <RowModal :label1="''">
                        <Button :icon="'icon-plus'" @click="registrarLotePromocion()">Agregar Lote</Button>
                    </RowModal>
                    <div v-show="errorLotePromocion" class="form-group row div-error">
                        <div class="text-center text-error">
                            <div v-for="error in errorMostrarMsjLotePromocion" :key="error" v-text="error"></div>
                        </div>
                    </div>
                    <template v-if="mostrar == 1">
                        <div class="modal-header">
                            <h5 class="modal-title"> Lotes con la promoción</h5>
                        </div>
                        <TableComponent :cabecera="['','Manzana','Lote']">
                            <template v-slot:tbody>
                                <tr v-for="lotePromocion in arrayLotePromocion" :key="lotePromocion.id">
                                    <td style="width:25%">
                                        <button type="button" class="btn btn-danger btn-sm" @click="eliminarLotePromocion(lotePromocion)">
                                        <i class="icon-trash"></i>
                                        </button>
                                    </td>
                                    <td v-text="lotePromocion.manzana" ></td>
                                    <td v-text="lotePromocion.lote" ></td>
                                </tr>
                            </template>
                        </TableComponent>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination2.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page - 1,id,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber2" :key="page" :class="[page == isActived2 ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(page,id,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination2.current_page < pagination2.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina2(pagination2.current_page + 1,id,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </template>
                </template>
            </ModalComponent>
            <!--Fin del modal-->

        </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>

import ModalComponent from '../Componentes/ModalComponent.vue'
import RowModal from '../Componentes/ComponentesModal/RowModalComponent.vue'
import TableComponent from '../Componentes/TableComponent.vue'
import Button from '../Componentes/ButtonComponent.vue'
    export default {
        components:{
            ModalComponent,
            RowModal,
            TableComponent,
            Button
        },
        data(){
            return{
                proceso:false,
                id :0,
                fraccionamiento_id:0,
                etapa_id : 0,
                lote_id : 0,
                lote_promocion_id : 0,
                lotes_promo:[],
                nombre : '',
                v_ini : new Date().toISOString().substr(0, 10),
                v_fin : '',
                descuento : '',
                cant_terreno : 0,
                cant_ubicacion : 0,
                cant_desc : 0,
                'desc_equipamiento' : '',
                descripcion : '',
                arrayPromocion : [],
                arrayLotePromocion : [],
                arrayFraccionamientos: [],
                arrayEtapas: [],
                arrayManzanas: [],
                arrayLotes : [],
                modal : 0,
                mostrar : 0,
                manzana : '',
                tituloModal : '',
                modal2 : 0,
                tituloModal2 : '',
                tipoAccion: 0,
                errorPromocion : 0,
                errorLotePromocion : 0,
                errorMostrarMsjPromocion : [],
                errorMostrarMsjLotePromocion : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                 pagination2 : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'promociones.nombre',
                buscar : '',
                buscar2 : '',
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
            ////////////////////////////
            isActived2: function(){
                return this.pagination2.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber2:function(){
                if(!this.pagination2.to){
                    return [];
                }

                var from = this.pagination2.current_page - this.offset;
                if(from < 1){
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.pagination2.last_page){
                    to = this.pagination2.last_page;
                }

                var pagesArray = [];
                while(from <= to){
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            }
        },
        methods : {
            /**Metodo para mostrar los registros */
            listarPromociones(page, buscar,buscar2, criterio){
                let me = this;
                var url = '/promocion?page=' + page + '&buscar=' + buscar + '&buscar2=' + buscar2 + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayPromocion = respuesta.promociones.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listarLotePromociones(page, promocion_id){
                let me = this;
                var url = '/lote_promocion?page=' + page + '&promocion_id=' + promocion_id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLotePromocion = respuesta.lotes_promocion.data;
                    me.pagination2 = respuesta.pagination;
                    if(me.arrayLotePromocion.length>0){
                        me.mostrar = 1;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            isNumber: function(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                    evt.preventDefault();;
                } else {
                    return true;
                }
            },
            formatNumber(value) {
                let val = (value/1).toFixed(2)
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            },
            cambiarPagina(page, buscar, buscar2, criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarPromociones(page,buscar,buscar2,criterio);
            },
            cambiarPagina2(page, buscar){
                let me = this;
                //Actualiza la pagina actual
                me.pagination2.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarLotePromociones(page,buscar);
            },
            calcularDesc(){
                this.descuento = 0;

                this.descuento = parseFloat(this.cant_desc) + parseFloat(this.cant_terreno) + parseFloat(this.cant_ubicacion);
            },
            /**Metodo para registrar  */
            registrarPromociones(){
                if(this.validarPromociones() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;
                let me = this;
                //Con axios se llama el metodo store de DepartamentoController
                axios.post('/promocion/registrar',{
                    'fraccionamiento_id': this.fraccionamiento_id,
                    'etapa_id': this.etapa_id,
                    'nombre': this.nombre,
                    'v_ini': this.v_ini,
                    'v_fin': this.v_fin,
                    'descuento': this.descuento,
                    'cant_terreno' : this.cant_terreno,
                    'cant_ubicacion' : this.cant_ubicacion,
                    'cant_desc' : this.cant_desc ,
                    'desc_equipamiento' : this.desc_equipamiento,
                    'descripcion': this.descripcion
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarPromociones(me.pagination.current_page,me.buscar, me.buscar2,me.criterio); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Promocion creada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            registrarLotePromocion(){
                if(this.validarLotePromociones() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }
                this.proceso=true;
                let me = this;
                //Con axios se llama el metodo update de DepartamentoController

               Swal({
                    title: 'Estas seguro?',
                    animation: false,
                    customClass: 'animated bounceInDown',
                    text: "La promoción se asignara a los lotes seleccionados",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',

                    confirmButtonText: 'Si, asignar!'
                    }).then((result) => {

                    if (result.value) {
                        me.lotes_promo.forEach(element => {
                        axios.post('/lote_promocion/registrar',{
                            'promocion_id': this.id,
                            'lote_id': element
                            });
                        });
                   // me.listarLote(1,'','','','','','','lote');
                    me.proceso=false;
                    me.listarLotePromociones(1,me.id)
                    Swal({
                        title: 'Hecho!',
                        text: 'Promocion asignada correctamente',
                        type: 'success',
                        animation: false,
                        customClass: 'animated bounceInRight'
                        })
                    }})
            },

            actualizarPromociones(){
                if(this.validarPromociones() || this.proceso==true) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;

                let me = this;
                //Con axios se llama el metodo update de DepartamentoController
                axios.put('/promocion/actualizar',{
                   'fraccionamiento_id': this.fraccionamiento_id,
                    'etapa_id': this.etapa_id,
                    'nombre': this.nombre,
                    'v_ini': this.v_ini,
                    'v_fin': this.v_fin,
                    'descuento': this.descuento,
                    'cant_terreno' : this.cant_terreno,
                    'cant_ubicacion' : this.cant_ubicacion,
                    'cant_desc' : this.cant_desc ,
                    'descripcion': this.descripcion,
                    'desc_equipamiento' : this.desc_equipamiento,
                    'id': this.id
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal();
                    me.listarPromociones(me.pagination.current_page,me.buscar,me.buscar2,me.criterio);
                    //window.alert("Cambios guardados correctamente");
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Cambios guardados correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            eliminarPromocion(data =[]){
                this.id=data['id'];
                this.fraccionamiento_id=data['fraccionamiento_id'];
                this.etapa_id=data['etapa_id'];
                this.nombre=data['nombre'];
                this.v_ini=data['v_ini'];
                this.v_fin=data['v_fin'];
                this.descuento=data['descuento'];
                this.descripcion=data['descripcion'];
                //console.log(this.departamento_id);
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
                    let me = this;

                axios.delete('/promocion/eliminar',
                        {params: {'id': this.id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Paquete borrado correctamente.',
                        'success'
                        )
                        me.listarPromociones(me.pagination.current_page,me.buscar,me.buscar2,me.criterio);
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            eliminarLotePromocion(data =[]){
                this.lote_promocion_id=data['id'];
                this.lote_id=data['lote_id'];
                this.promocion_id=data['promocion_id'];
                //console.log(this.departamento_id);
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
                    let me = this;

                axios.delete('/lote_promocion/eliminar',
                        {params: {'id': this.lote_promocion_id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Paquete borrado correctamente.',
                        'success'
                        )
                        me.listarLotePromociones(1,me.promocion_id);
                        if(me.arrayLotePromocion.length==0)
                            me.mostrar = 0;
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
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
            selectLotesManzana(buscar1, buscar2, buscar3){
                let me = this;

                me.arrayLotes=[];
                var url = '/select_lotes_manzana?buscar=' + buscar1 + '&buscar1='+ buscar2 + '&buscar2='+ buscar3;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLotes = respuesta.lote_manzana;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
             selectEtapa(buscar){
                let me = this;

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
            selectManzanas(buscar1, buscar2){
                let me = this;

                me.arrayManzanas=[];
                var url = '/select_manzanas_etapa?buscar=' + buscar1 + '&buscar1='+ buscar2;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayManzanas = respuesta.manzana;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            validarPromociones(){
                this.errorPromocion=0;
                this.errorMostrarMsjPromocion=[];

                if(!this.nombre) //Si la variable departamento esta vacia
                    this.errorMostrarMsjPromocion.push("El nombre de la promoción no puede ir vacio.");

                if(this.fraccionamiento_id==0) //Si la variable departamento esta vacia
                    this.errorMostrarMsjPromocion.push("Debe seleccionar algun fraccionamiento.");

                if(this.etapa_id==0) //Si la variable departamento esta vacia
                    this.errorMostrarMsjPromocion.push("Debe seleccionar la etapa.");

                if(this.descripcion=='') //Si la variable departamento esta vacia
                    this.errorMostrarMsjPromocion.push("Se debe escribir una descripción.");

                if(this.errorMostrarMsjPromocion.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorPromocion = 1;

                return this.errorPromocion;
            },
            validarLotePromociones(){
                this.errorLotePromocion=0;
                this.errorMostrarMsjLotePromocion=[];

                if(!this.lotes_promo) //Si la variable departamento esta vacia
                    this.errorMostrarMsjLotePromocion.push("Selecciona el lote que tendra la promoción.");

                if(this.errorMostrarMsjLotePromocion.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorLotePromocion = 1;

                return this.errorLotePromocion;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.fraccionamiento_id = '';
                this.etapa_id = '';
                this.nombre = '';
                this.v_ini = new Date().toISOString().substr(0, 10);
                this.v_fin = '';
                this.descuento = '';
                this.descripcion = '';
                this.errorPromocion = 0;
                this.errorMostrarMsjPromocion = [];
                this.lotes_promo = [];

            },
            cerrarModal2(){
                this.modal2 = 0;
                this.tituloModal2 = '';
                this.fraccionamiento_id = '';
                this.etapa_id = '';
                this.lote_id = '';
                this.lotes_promo = [];
                this.lote_promocion_id = '';
                this.errorLotePromocion = 0;
                this.errorMostrarMsjLotePromocion = [];
                this.mostrar = 0;
                this.arrayLotes = [];

            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(modelo, accion,data =[]){
                switch(modelo){
                    case "promocion":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar promocion';
                                this.fraccionamiento_id =0;
                                this.etapa_id =0;
                                this.nombre = '';
                                // this.v_ini ='';
                                this.v_fin = '';
                                this.descuento = 0;
                                this.cant_terreno = 0;
                                this.cant_ubicacion = 0;
                                this.cant_desc = 0;
                                this.descripcion = '';
                                this.tipoAccion = 1;
                                this.desc_equipamiento = '';
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal =1;
                                this.tituloModal='Actualizar promocion';
                                this.tipoAccion=2;
                                this.id=data['id'];
                                this.fraccionamiento_id=data['fraccionamiento_id'];
                                this.etapa_id=data['etapa_id'];
                                this.nombre=data['nombre'];
                                this.v_ini=data['v_ini'];
                                this.v_fin=data['v_fin'];
                                this.descuento=data['descuento'];
                                this.cant_terreno = data['cant_terreno'];
                                this.cant_ubicacion = data['cant_ubicacion'];
                                this.cant_desc = data['cant_desc'];
                                this.descripcion=data['descripcion'];
                                this.desc_equipamiento = data['desc_equipamiento'];
                                break;
                            }
                        }
                    }
                }

                this.selectFraccionamientos();
                this.selectEtapa(this.fraccionamiento_id);
            },
            abrirModal2(modelo, accion,data =[]){
                switch(modelo){
                    case "lote_promocion":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal2 = 1;
                                this.tituloModal2 = 'Asignar Promoción:  ' + data['nombre'];;
                                this.fraccionamiento_id=data['fraccionamiento_id'];
                                this.etapa_id=data['etapa_id'];
                                this.id=data['id'];
                                this.lote_id = 0;
                                this.manzana = '';
                                break;
                            }
                        }
                    }
                }

                this.selectFraccionamientos();
                this.selectEtapa(this.fraccionamiento_id);
                this.selectManzanas(this.fraccionamiento_id,this.etapa_id)
            }
        },
        mounted() {
            this.listarPromociones(1,this.buscar,this.buscar2,this.criterio);
            this.selectFraccionamientos();
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
    .btn-success2 {
        color: #fff;
        background-color: #2c309e;
        border-color: #313a98;
    }
    .btn-success2:active, .btn-success2.active, .show > .btn-success2.dropdown-toggle {
        background-color: #2c309e;
        background-image: none;
        border-color: #313a98;
    }
    .btn-success2:focus, .btn-success2.focus {
        box-shadow: 0 0 0 3px rgba(77, 100, 189, 0.5);
    }
    .btn-success2:hover {
        color: #fff;
        background-color: #2c309e;
        border-color: #313a98;
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
