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
                        <i class="fa fa-align-justify"></i> Etapas
                        <!--   Boton Nuevo    -->
                        <button type="button" @click="abrirModal('etapa','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                        <a :href="'/etapa/excel?buscar=' + buscar + '&buscar2=' + buscar2 + '&criterio=' + criterio"  class="btn btn-success"><i class="fa fa-file-text"></i> Excel </a>
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-10">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-4" v-model="criterio" @change="limpiarBusqueda()">
                                        <option value="fraccionamientos.nombre">Fraccionamiento</option>
                                        <option value="num_etapa">Etapa</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarEtapa(1,buscar,buscar2,criterio)" class="form-control" placeholder="Texto a buscar">
                                </div>
                                <div class="input-group">
                                    <button type="submit" @click="listarEtapa(1,buscar,buscar2,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <TableComponent>
                            <template v-slot:thead>
                                <tr>
                                    <th></th>
                                    <th>Fraccionamiento</th>
                                    <th>Etapa</th>
                                    <th>Terreno m&sup2;</th>
                                    <th>Fecha de inicio </th>
                                    <th>Fecha de termino</th>
                                    <th>Encargado</th>
                                    <th v-if="rolId!=3">Fecha de inicio de ventas</th>
                                </tr>
                            </template>
                            <template v-slot:tbody>
                                <tr v-for="etapa in arrayEtapa" :key="etapa.id">
                                    <td class="td2">
                                        <button type="button" @click="abrirModal('etapa','actualizar',etapa)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <button type="button" class="btn btn-danger btn-sm" @click="eliminarEtapa(etapa)">
                                            <i class="icon-trash"></i>
                                        </button>
                                        <button type="button" class="btn btn-success btn-sm" @click="abrirModal('etapa','amenidades',etapa)" title="amenidades">
                                            <i class="fa fa-tree"></i>
                                        </button>
                                    </td>
                                    <td class="td2" v-text="etapa.fraccionamiento"></td>
                                    <td class="td2" v-text="etapa.num_etapa"></td>
                                    <td class="td2">{{etapa.terreno_m2}}m&sup2;</td>
                                    <td class="td2" v-text="etapa.f_ini"></td>
                                    <td class="td2" v-text="etapa.f_fin"></td>
                                    <td class="td2" v-text="etapa.name"></td>
                                    <td class="td2" v-if="rolId!=3" v-text="etapa.fecha_ini_venta"></td>
                                </tr>
                            </template>
                        </TableComponent>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,buscar2,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,buscar2,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,buscar2,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                        <button class="btn btn-sm btn-default" @click="modal=2">Manual</button>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <ModalComponent v-if="modal == 1"
                :titulo="tituloModal"
                @closeModal="cerrarModal()"
            >
                <template v-slot:body>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Fraccionamientos</label>
                        <div class="col-md-6">
                            <select class="form-control" @change="selectContador(fraccionamiento_id)" v-model="fraccionamiento_id">
                                <option value="0">Seleccione</option>
                                <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Numero de etapa</label>
                        <div class="col-md-4">
                            <input type="text" v-model="num_etapa" class="form-control" placeholder="# de etapa">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Superficie de terreno</label>
                        <div class="col-md-4">
                            <input type="number" v-model="terreno_m2" class="form-control" placeholder="Terreno">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Fecha de inicio</label>
                        <div class="col-md-6">
                            <input type="date" v-model="f_ini"  class="form-control" placeholder="Fecha de inicio">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Fecha de terminacion</label>
                        <div class="col-md-6">
                            <input type="date" v-model="f_fin" class="form-control" placeholder="Fecha de terminacion">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="text-input">Directivo</label>
                        <div class="col-md-6">
                            <select class="form-control" v-model="personal_id">
                                <option value="0">Seleccione</option>
                                <option v-for="directivos in arrayDirectores" :key="directivos.id" :value="directivos.id" v-text="directivos.name"></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row" v-if="rolId != 3">
                        <label class="col-md-3 form-control-label" for="text-input">Fecha de inicio de ventas</label>
                        <div class="col-md-6">
                            <input type="date" v-model="fecha_ini_venta" class="form-control" placeholder="Fecha de terminacion">
                        </div>
                    </div>
                    <!-- Div para mostrar los errores que mande validerFraccionamiento -->
                    <div v-show="errorEtapa" class="form-group row div-error">
                        <div class="text-center text-error">
                            <div v-for="error in errorMostrarMsjEtapa" :key="error" v-text="error">
                            </div>
                        </div>
                    </div>
                </template>
                <template v-slot:buttons-footer>
                    <!-- Condicion para elegir el boton a mostrar dependiendo de la accion solicitada-->
                    <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarEtapa()">Guardar</button>
                    <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarEtapa()">Actualizar</button>
                </template>
            </ModalComponent>
            <!--Fin del modal-->

            <!-- Modal para amenidades -->
            <ModalComponent v-if="modal == 3"
                @closeModal="cerrarModal()"
                :titulo="tituloModal"
            >
                <template v-slot:body>
                    <div class="amenidades">
                        <div class=" tab-content table-responsive">
                            <table  class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Amenidades</th>
                                    </tr>
                                </thead>
                                <tbody >
                                    <tr v-for="(amenidad, index) in arrayListAmenidades" :key="amenidad.amenidad">
                                        <td class="btn-info table-pointer" v-if="amenidad.activo == 1"
                                            @click="setAmenidad(amenidad.amenidad,index)"
                                        >
                                            {{amenidad.amenidad}}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-center">

                        </div>
                        <div class=" tab-content table-responsive">
                            <table  class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Amenidades en la privada</th>
                                    </tr>
                                </thead>
                                <tbody >
                                    <tr v-for="amenidad in amenidadesEtapa" :key="amenidad.amenidad">
                                        <td class="btn-success table-pointer"
                                            @click="unsetAmenidad(amenidad)"
                                        >
                                            {{amenidad.amenidad}}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </template>
            </ModalComponent>
            <!--fin amenidades-->

            <!-- Manual -->
            <ModalComponent v-if="modal == 2"
                :titulo="'Manual'"
                @closeModal="modal=0">
                <template v-slot:body>
                    <p>
                        Para crear una nueva etapa solo debe dar clic sobre el botón de “Nuevo” y seleccionar el
                        fraccionamiento o proyecto al cual pertenecerá la nueva etapa.
                    </p>
                    <p>
                        Un proyecto puede constar de más de una etapa y usted puede agregar cuantas etapas considere
                        sean necesarias para su proyecto, es recomendable que lleve el orden de sus etapas con el número de etapa.
                    </p>
                </template>
            </ModalComponent>
        </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
import ModalComponent from '../Componentes/ModalComponent.vue'
import TableComponent from '../Componentes/TableComponent.vue'

    export default {
        components:{
            TableComponent,
            ModalComponent
        },
        props:{
            rolId:{type: String}
        },
        data(){
            return{
                proceso : false,
                id:0,
                contador : 0,
                fraccionamiento_id : 0,
                num_etapa : 0,
                f_ini : new Date().toISOString().substr(0, 10),
                f_fin : '',
                fecha_ini_venta:'',
                personal_id : 0,
                arrayEtapa : [],
                modal : 0,
                terreno_m2 : 0,
                tituloModal : '',
                tipoAccion: 0,
                errorEtapa : 0,
                errorMostrarMsjEtapa : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'fraccionamientos.nombre',
                buscar : '',
                buscar2: '',
                arrayFraccionamientos : [],
                arrayDirectores : [],
                arrayListAmenidades : [
                    {
                        amenidad: 'Control de acceso',
                        activo: 1
                    },
                    {
                        amenidad: 'Caseta de vigilancia',
                        activo: 1
                    },
                    {
                        amenidad: 'Barda perimetral',
                        activo: 1
                    },
                    {
                        amenidad: 'Cerco eléctrico perimetral',
                        activo: 1
                    },
                    {
                        amenidad: 'Circuito cerrado de vigilancia',
                        activo: 1
                    },
                    {
                        amenidad: 'Casa Club',
                        activo: 1
                    },
                    {
                        amenidad: 'Terraza',
                        activo: 1
                    },
                    {
                        amenidad: 'Asador',
                        activo: 1
                    },
                    {
                        amenidad: 'Areas verdes',
                        activo: 1
                    },
                    {
                        amenidad: 'Alberca',
                        activo: 1
                    },
                    {
                        amenidad: 'Servicios sanitarios',
                        activo: 1
                    },
                    {
                        amenidad: 'Vapor hombres y mujeres',
                        activo: 1
                    },
                    {
                        amenidad: 'Gimnasio',
                        activo: 1
                    },
                    {
                        amenidad: 'Cancha de usos multiples',
                        activo: 1
                    },
                    {
                        amenidad: 'Juegos infantiles',
                        activo: 1
                    },
                    {
                        amenidad: 'Estacionamiento de visitas',
                        activo: 1
                    },
                    {
                        amenidad: 'Rampa en pavimento para discapacitados',
                        activo: 1
                    },
                    {
                        amenidad: 'Doggy Park',
                        activo: 1
                    },
                    {
                        amenidad: 'Lago Koi',
                        activo: 1
                    },
                    {
                        amenidad: 'Jardín contemplativo',
                        activo: 1
                    },
                ],
                amenidadesEtapa:[]
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
            }
        },
        methods : {
            /**Metodo para mostrar los registros */
            listarEtapa(page, buscar, buscar2, criterio){
                let me = this;
                var url = '/etapa?page=' + page + '&buscar=' + buscar + '&buscar2=' + buscar2 + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEtapa = respuesta.etapas.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
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
            selectContador(fraccionamiento_id){
                let me = this;
                me.contador=0;
                var url = '/contador_etapa?fraccionamiento_id='+fraccionamiento_id;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.contador = respuesta;
                    me.num_etapa = me.contador-1;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectDirectivos(){
                let me = this;
                me.arrayDirectivos=[];
                var url = '/select_personal?departamento_id=1';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayDirectores = respuesta.personal;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page, buscar, buscar2, criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarEtapa(page,buscar,buscar2,criterio);
            },
            /**Metodo para registrar  */
            registrarEtapa(){
                if(this.proceso==true){
                    return;
                }
                if(this.validarEtapa()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/etapa/registrar',{
                    'fraccionamiento_id': this.fraccionamiento_id,
                    'num_etapa': this.num_etapa,
                    'terreno_m2':this.terreno_m2,
                    'f_ini': this.f_ini,
                    'f_fin': this.f_fin,
                    'personal_id': this.personal_id
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarEtapa(1,'','','etapa'); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Etapa agregada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },

            limpiarBusqueda(){
                let me=this;
                me.buscar= "";
                me.buscar2="";
            },
            actualizarEtapa(){
                if(this.proceso==true){
                    return;
                }
                if(this.validarEtapa()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;

                let me = this;
                //Con axios se llama el metodo update de FraccionaminetoController
                axios.put('/etapa/actualizar',{
                    'id' : this.id,
                    'fraccionamiento_id': this.fraccionamiento_id,
                    'num_etapa': this.num_etapa,
                    'terreno_m2':this.terreno_m2,
                    'f_ini': this.f_ini,
                    'f_fin': this.f_fin,
                    'personal_id': this.personal_id,
                    'fecha_ini_venta' : this.fecha_ini_venta
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal();
                    me.listarEtapa(1,'','','etapa');
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
            eliminarEtapa(data =[]){
                this.id=data['id'];
                this.fraccionamiento_id=data['fraccionamiento_id'];
                this.num_etapa=data['num_etapa'];
                this.f_ini=data['f_ini'];
                this.f_fin=data['f_fin'];
                this.personal_id=data['personal_id']
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

                axios.delete('/etapa/eliminar',
                        {params: {'id': this.id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Etapa borrada correctamente.',
                        'success'
                        )
                        me.listarEtapa(1,'','','etapa');
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            validarEtapa(){
                this.errorEtapa=0;
                this.errorMostrarMsjEtapa=[];

                if(!this.num_etapa) //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjEtapa.push("El numero de etapa no puede ir vacio.");



                if(this.errorMostrarMsjEtapa.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorEtapa = 1;

                return this.errorEtapa;
            },

            setAmenidad(amenidad,index){
                this.arrayListAmenidades[index].activo = 0;
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/amenities',{
                    'etapa_id': me.id,
                    'amenidad': amenidad,
                }).then(function (response){
                    me.amenidadesEtapa.push(
                        {
                            id: response.data,
                            amenidad: amenidad,
                            etapa_id: me.id,
                            index: index
                        }
                    )
                    me.listarEtapa(me.pagination.current_page,me.buscar,me.buscar2,'etapa');
                    //Se muestra mensaje Success
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Amenidad asignada'
                    })
                }).catch(function (error){
                    console.log(error);
                });

            },
            unsetAmenidad(amenidad){
                let me = this;
                axios.delete(`/amenities/${amenidad.id}`, {
                    params: {'id': amenidad.id}
                }).then(function (response){
                    const index = me.arrayListAmenidades.map( e => e.amenidad ).indexOf( amenidad.amenidad )
                    me.amenidadesEtapa = me.amenidadesEtapa.filter( a => a.amenidad !== amenidad.amenidad)
                    me.arrayListAmenidades[index].activo = 1;
                    me.listarEtapa(me.pagination.current_page,me.buscar,me.buscar2,'etapa');
                    //Se muestra mensaje Success
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Amenidad removida'
                    })
                }).catch(function (error){
                    console.log(error);
                });
            },

            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.fraccionamiento_id = '';
                this.num_etapa = '';
                this.f_ini = new Date().toISOString().substr(0, 10);
                this.f_fin = '';
                this.personal_id = '';
                this.errorEtapa = 0;
                this.errorMostrarMsjEtapa = [];
                this.contador=0;
                this.terreno_m2=0;
                this.arrayListAmenidades = [
                    {
                        amenidad: 'Control de acceso',
                        activo: 1
                    },
                    {
                        amenidad: 'Caseta de vigilancia',
                        activo: 1
                    },
                    {
                        amenidad: 'Barda perimetral',
                        activo: 1
                    },
                    {
                        amenidad: 'Cerco eléctrico perimetral',
                        activo: 1
                    },
                    {
                        amenidad: 'Circuito cerrado de vigilancia',
                        activo: 1
                    },
                    {
                        amenidad: 'Casa Club',
                        activo: 1
                    },
                    {
                        amenidad: 'Terraza',
                        activo: 1
                    },
                    {
                        amenidad: 'Asador',
                        activo: 1
                    },
                    {
                        amenidad: 'Areas verdes',
                        activo: 1
                    },
                    {
                        amenidad: 'Alberca',
                        activo: 1
                    },
                    {
                        amenidad: 'Servicios sanitarios',
                        activo: 1
                    },
                    {
                        amenidad: 'Vapor hombres y mujeres',
                        activo: 1
                    },
                    {
                        amenidad: 'Gimnasio',
                        activo: 1
                    },
                    {
                        amenidad: 'Cancha de usos multiples',
                        activo: 1
                    },
                    {
                        amenidad: 'Juegos infantiles',
                        activo: 1
                    },
                    {
                        amenidad: 'Estacionamiento de visitas',
                        activo: 1
                    },
                    {
                        amenidad: 'Rampa en pavimento para discapacitados',
                        activo: 1
                    },
                    {
                        amenidad: 'Doggy Park',
                        activo: 1
                    },
                    {
                        amenidad: 'Lago Koi',
                        activo: 1
                    },
                    {
                        amenidad: 'Jardín contemplativo',
                        activo: 1
                    },
                ]

            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(modelo, accion,data =[]){
                switch(modelo){
                    case "etapa":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Etapa';
                                this.fraccionamiento_id = '0';
                                this.num_etapa = this.contador;
                                // this.f_ini = '';
                                this.f_fin = '';
                                this.personal_id = '0';
                                this.tipoAccion = 1;
                                this.terreno_m2 = 0;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal =1;
                                this.tituloModal='Actualizar Etapa';
                                this.tipoAccion=2;
                                this.id=data['id'];
                                this.fraccionamiento_id=data['fraccionamiento_id'];
                                this.num_etapa=data['num_etapa'];
                                this.f_ini=data['f_ini'];
                                this.f_fin=data['f_fin'];
                                this.personal_id=data['personal_id'];
                                this.fecha_ini_venta = data['fecha_ini_venta'];
                                this.terreno_m2 = data['terreno_m2'];
                                break;
                            }
                            case 'amenidades':
                            {
                                //console.log(data);
                                this.modal = 3;
                                this.tituloModal='Amenidades';
                                this.id=data['id'];
                                this.amenidadesEtapa=data['amenidades'];

                                this.amenidadesEtapa.forEach(element => {
                                    let index = this.arrayListAmenidades.map( e => e.amenidad ).indexOf( element.amenidad )
                                    this.arrayListAmenidades[index].activo = 0;
                                });
                                break;
                            }
                        }
                    }
                }
                this.selectFraccionamientos();
                this.selectDirectivos();
            }
        },
        mounted() {
            this.listarEtapa(1,this.buscar,this.buscar2,this.criterio);
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
    .amenidades{
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }
    .modal-center{
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .table-pointer{
        cursor: pointer;
    }
</style>
