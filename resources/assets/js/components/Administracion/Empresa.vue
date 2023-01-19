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
                        <i class="fa fa-align-justify" v-if="verificadora == 0"></i>
                        <Button v-if="verificadora == 0" @click="verificadora = 1">Empresas</Button>
                        <Button v-if="verificadora == 1" @click="verificadora = 0">Empresas verificadoras</Button>
                        <!--   Boton Nuevo    -->
                        <Button :icon="'icon-plus'" :btnClass="'btn-secondary'"
                            v-if="verificadora == 0" @click="abrirModal('empresa','registrar')">
                            Nueva Empresa
                        </Button>
                        <!--   Boton Nuevo    -->
                        <Button :icon="'icon-plus'" :btnClass="'btn-secondary'"
                            v-if="verificadora == 1" @click="abrirModal('empresaVerif','registrar')">
                            Nueva Empresa Verificadora
                        </Button>
                    </div>
                    <div class="card-body" v-if="verificadora == 0">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-5" v-model="criterio">
                                      <option value="nombre">Empresa</option>
                                    </select>

                                    <input type="text" v-model="buscar" @keyup.enter="listarEmpresa(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <Button :icon="'fa fa-search'" @click="listarEmpresa(1,buscar,criterio)">Buscar</Button>
                                </div>
                            </div>
                        </div>
                        <TableComponent :cabecera="['Opciones','Empresa','Direccion','Colonia','CP','Teléfono']">
                            <template v-slot:tbody>
                                <tr v-for="empresa in arrayEmpresa" :key="empresa.id">
                                    <td class="td2">
                                        <Button :btnClass="'btn-warning'" :size="'btn-sm'" :icon="'icon-pencil'"
                                            @click="abrirModal('empresa','actualizar',empresa)" title="Editar"
                                        ></Button>
                                        <Button :btnClass="'btn-danger'" :size="'btn-sm'" :icon="'icon-trash'"
                                            @click="eliminarEmpresa(empresa)" title="Eliminar"
                                        ></Button>
                                    </td>
                                    <td v-text="empresa.nombre"></td>
                                    <td v-text="empresa.direccion"></td>
                                    <td v-text="empresa.colonia"></td>
                                    <td v-text="empresa.cp"></td>
                                    <td v-text="empresa.telefono"></td>
                                </tr>
                            </template>
                        </TableComponent>
                        <Nav :current="pagination.current_page" :last="pagination.last_page"
                            @changePage="cambiarPagina"
                        ></Nav>
                    </div>

                    <div class="card-body" v-if="verificadora == 1">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" v-model="buscar" @keyup.enter="listarEmpresaVerificadora(1)" class="form-control" placeholder="Texto a buscar">
                                    <Button :icon="'fa fa-search'" @click="listarEmpresaVerificadora(1)">Buscar</Button>
                                </div>
                            </div>
                        </div>
                        <TableComponent :cabecera="['Opciones','Empresa','Contacto','Teléfono']">
                            <template v-slot:tbody>
                                <tr v-for="empresa in arrayEmpresaVerificadora" :key="empresa.id">
                                    <td class="td2">
                                        <Button :btnClass="'btn-warning'" :size="'btn-sm'" @click="abrirModal('empresaVerif','actualizar',empresa)"
                                            :icon="'icon-pencil'"
                                        ></Button>
                                        <Button :btnClass="'btn-danger'" :size="'btn-sm'" @click="eliminarEmpresaVerif(empresa)"
                                            :icon="'icon-trash'"
                                        ></Button>
                                    </td>
                                    <td class="td2" v-text="empresa.empresa"></td>
                                    <td class="td2" v-text="empresa.contacto"></td>
                                    <td class="td2" v-text="empresa.telefono"></td>
                                </tr>
                            </template>
                        </TableComponent>
                        <Nav :current="pagination2.current_page"
                            :last="pagination2.last_page"
                            @changePage="cambiarPagina2"
                        ></Nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>

            <!--Inicio del modal agregar/actualizar-->
            <ModalComponent v-if="modal"
                :titulo="tituloModal"
                @closeModal="cerrarModal()"
            >
                <template v-slot:body>
                    <RowModal :clsRow1="'col-md-9'" :label1="'Empresa'">
                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de la empresa">
                    </RowModal>
                    <RowModal :clsRow1="'col-md-9'" :label1="'Dirección'" v-if="tipoAccion < 3">
                        <input type="text" v-model="direccion" class="form-control" placeholder="Calle">
                    </RowModal>
                    <RowModal v-if="tipoAccion > 2" :clsRow1="'col-md-9'" :label1="'Contacto'">
                        <input type="text" v-model="contacto" class="form-control" placeholder="Contacto">
                    </RowModal>
                    <template v-if="tipoAccion < 3">
                        <RowModal :label1="'Codigo Postal'">
                            <input type="text" v-on:keypress="$root.isNumber($event)" maxlength="5"
                                v-model="cp" @keyup="selectColonias(cp)" class="form-control" placeholder="Codigo postal">
                        </RowModal>
                        <RowModal :label1="'Colonia'">
                            <select class="form-control" v-model="colonia">
                                <option value="0">Seleccione</option>
                                <option v-for="c in arrayColonias" :key="c.colonia" :value="c.colonia" v-text="c.colonia"></option>
                            </select>
                        </RowModal>
                        <RowModal :clsRow1="'col-md-9'" :label1="'Estado'">
                            <select class="form-control" v-model="estado" @change="selectCiudades(estado)">
                                <option value="San Luis Potosí">San Luis Potosí</option>
                                <option value="Baja California">Baja California</option>
                                <option value="Baja California Sur">Baja California Sur</option>
                                <option value="Coahuila de Zaragoza">Coahuila de Zaragoza</option>
                                <option value="Colima">Colima</option>
                                <option value="Chiapas">Chiapas</option>
                                <option value="Chihuahua">Chihuahua</option>
                                <option value="Ciudad de México">Ciudad de México</option>
                                <option value="Durango">Durango</option>
                                <option value="Guanajuato">Guanajuato</option>
                                <option value="Guerrero">Guerrero</option>
                                <option value="Hidalgo">Hidalgo</option>
                                <option value="Jalisco">Jalisco</option>
                                <option value="México">México</option>
                                <option value="Michoacán de Ocampo">Michoacán de Ocampo</option>
                                <option value="Morelos">Morelos</option>
                                <option value="Nayarit">Nayarit</option>
                                <option value="Nuevo León">Nuevo León</option>
                                <option value="Oaxaca">Oaxaca</option>
                                <option value="Puebla">Puebla</option>
                                <option value="Querétaro">Querétaro</option>
                                <option value="Quintana Roo">Quintana Roo</option>
                                <option value="Sinaloa">Sinaloa</option>
                                <option value="Sonora">Sonora</option>
                                <option value="Tabasco">Tabasco</option>
                                <option value="Tamaulipas">Tamaulipas</option>
                                <option value="Tlaxcala">Tlaxcala</option>
                                <option value="Veracruz de Ignacio de la Llave">Veracruz de Ignacio de la Llave</option>
                                <option value="Yucatán">Yucatán</option>
                                <option value="Zacatecas">Zacatecas</option>
                            </select>
                        </RowModal>
                        <RowModal :clsRow1="'col-md-9'" :label1="'Ciudad'">
                            <select class="form-control" v-model="ciudad">
                                <option v-for="c in arrayCiudades" :key="c.municipio" :value="c.municipio" v-text="c.municipio"></option>
                            </select>
                        </RowModal>
                    </template>
                    <RowModal :label1="'Teléfono'">
                        <input type="text" maxlength="10" v-on:keypress="$root.isNumber($event)"
                            v-model="telefono" class="form-control" placeholder="Telefono">
                    </RowModal>
                    <RowModal v-if="tipoAccion < 3" :clsRow1="'col-md-3'" :label1="'Extensión'">
                        <input type="text" maxlength="5" v-on:keypress="$root.isNumber($event)"
                            v-model="ext" class="form-control" placeholder="Ext">
                    </RowModal>
                    <!-- Div para mostrar los errores que mande validerFraccionamiento -->
                    <div v-show="errorEmpresa" class="form-group row div-error">
                        <div class="text-center text-error">
                            <div v-for="error in errorMostrarMsjEmpresa" :key="error" v-text="error"></div>
                        </div>
                    </div>
                </template>
                <template v-slot:buttons-footer>
                    <Button v-if="tipoAccion==1" @click="registrarEmpresa()" :icon="'icon-check'">Guardar</Button>
                    <Button v-if="tipoAccion==2" @click="actualizarEmpresa()" :icon="'icon-check'">Guardar cambios</Button>
                    <Button v-if="tipoAccion==3" @click="registrarEmpresaVerificadora()" :icon="'icon-check'">Guardar</Button>
                    <Button v-if="tipoAccion==4" @click="actualizarEmpresaVerif()" :icon="'icon-check'">Guardar cambios</Button>
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
    import TableComponent from '../Componentes/TableComponent.vue'
    import Nav from '../Componentes/NavComponent.vue'
    import Button from '../Componentes/ButtonComponent.vue'
    import RowModal from '../Componentes/ComponentesModal/RowModalComponent.vue'

    export default {
        components:{
            ModalComponent,
            TableComponent,
            Nav, Button, RowModal
        },
        data(){
            return{
                proceso:false,
                verificadora:0,
                id:0,
                nombre : '',
                direccion : '',
                cp : '',
                colonia : '',
                estado : '',
                ciudad : '',
                telefono : '',
                ext : '',
                arrayEmpresa : [],
                arrayEmpresaVerificadora : [],
                modal : 0,
                tituloModal : '',
                contacto: '',
                tipoAccion: 0,
                errorEmpresa : 0,
                errorMostrarMsjEmpresa : [],
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
                criterio : 'nombre',
                buscar : '',
                arrayCiudades : [],
                arrayColonias : []
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
            listarEmpresa(page, buscar, criterio){
                let me = this;
                var url = '/empresa?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEmpresa = respuesta.empresas.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listarEmpresaVerificadora(page){
                let me = this;
                var url = '/empresa/indexVerificadoras?page=' + page + '&buscar=' + me.buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEmpresaVerificadora = respuesta.empresas.data;
                    me.pagination2 = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
             selectColonias(buscar){
                let me = this;
                me.arrayColonias=[];
                var url = '/select_colonias?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayColonias = respuesta.colonias;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectCiudades(buscar){
                let me = this;
                me.arrayCiudades=[];
                var url = '/select_ciudades?buscar=' + buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayCiudades = respuesta.ciudades;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarEmpresa(page,me.buscar,me.criterio);
            },
            cambiarPagina2(page){
                let me = this;
                //Actualiza la pagina actual
                me.pagination2.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarEmpresaVerificadora(page);
            },
            /**Metodo para registrar  */
            registrarEmpresa(){
                if(this.proceso==true){
                    return;
                }
                if(this.validarEmpresa()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/empresa/registrar',{
                    'nombre': this.nombre,
                    'direccion': this.direccion,
                    'cp': this.cp,
                    'colonia': this.colonia,
                    'estado': this.estado,
                    'ciudad': this.ciudad,
                    'telefono': this.telefono,
                    'ext': this.ext
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarEmpresa(1,'','empresa'); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Empresa agregada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            registrarEmpresaVerificadora(){
                if(this.proceso==true){
                    return;
                }
                if(this.validarEmpresa()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/empresa/storeVerificadora',{
                    'nombre': this.nombre,
                    'contacto': this.contacto,
                    'telefono': this.telefono
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarEmpresaVerificadora(1,); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Empresa agregada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            actualizarEmpresa(){
                if(this.proceso==true){
                    return;
                }
                if(this.validarEmpresa()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;
                let me = this;
                //Con axios se llama el metodo update de FraccionaminetoController
                axios.put('/empresa/actualizar',{
                    'nombre': this.nombre,
                    'direccion': this.direccion,
                    'cp': this.cp,
                    'colonia': this.colonia,
                    'estado': this.estado,
                    'ciudad': this.ciudad,
                    'telefono': this.telefono,
                    'ext': this.ext,
                    'id' : this.id
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal();
                    me.listarEmpresa(1,'','empresa');
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
            actualizarEmpresaVerif(){
                if(this.proceso==true){
                    return;
                }
                if(this.validarEmpresa()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;
                let me = this;
                //Con axios se llama el metodo update de FraccionaminetoController
                axios.put('/empresa/updateVerificadora',{
                    'nombre': this.nombre,
                    'contacto': this.contacto,
                    'telefono': this.telefono,
                    'id' : this.id
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal();
                    me.listarEmpresaVerificadora(1);
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
            eliminarEmpresa(data =[]){
                this.id=data['id'];
                this.nombre=data['nombre'];
                this.direccion=data['direccion'];
                this.cp=data['cp'];
                this.colonia=data['colonia'];
                this.estado=data['estado'];
                this.ciudad=data['ciudad'];
                this.telefono=data['telefono'];
                this.ext=data['ext']
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

                axios.delete('/empresa/eliminar',
                        {params: {'id': this.id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Empresa borrada correctamente.',
                        'success'
                        )
                        me.listarEmpresa(1,'','empresa');
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            eliminarEmpresaVerif(data =[]){
                this.id=data['id'];
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

                axios.delete('/empresa/destroyVerificadora',
                        {params: {'id': this.id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Empresa borrada correctamente.',
                        'success'
                        )
                        me.listarEmpresaVerificadora(1);
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
            },
            validarEmpresa(){
                this.errorEmpresa=0;
                this.errorMostrarMsjEmpresa=[];

                if(!this.nombre) //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjEmpresa.push("El nombre de la empresa no puede ir vacio.");

                if(this.errorMostrarMsjEmpresa.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorEmpresa = 1;

                return this.errorEmpresa;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.nombre = '';
                this.direcion = 0;
                this.cp = '';
                this.colonia = '';
                this.estado = '';
                this.ciudad = '';
                this.telefono = '';
                this.contacto = '';
                this.ext = '';
                this.errorEmpresa = 0;
                this.errorMostrarMsjEmpresa = [];

            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(modelo, accion,data =[]){
                switch(modelo){
                    case "empresa":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Empresa';
                                this.nombre ='';
                                this.direccion ='';
                                this.cp ='';
                                this.colonia ='';
                                this.estado ='San Luis Potosí';
                                this.ciudad ='';
                                this.telefono = '';
                                this.ext = '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal =1;
                                this.tituloModal='Actualizar Empresa';
                                this.tipoAccion=2;
                                this.id=data['id'];
                                this.nombre=data['nombre'];
                                this.direccion=data['direccion'];
                                this.cp=data['cp'];
                                this.colonia=data['colonia'];
                                this.estado=data['estado'];
                                this.ciudad=data['ciudad'];
                                this.telefono=data['telefono'];
                                this.ext=data['ext'];
                                break;
                            }
                        }
                        break;
                    }
                    case "empresaVerif":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Empresa Verificadora';
                                this.nombre ='';
                                this.contacto ='';
                                this.telefono = '';
                                this.tipoAccion = 3;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal =1;
                                this.tituloModal='Actualizar Empresa Verificadora';
                                this.tipoAccion=4;
                                this.id=data['id'];
                                this.nombre=data['empresa'];
                                this.contacto=data['contacto'];
                                this.telefono=data['telefono'];
                                break;
                            }
                        }
                    }
                }
                this.selectColonias(this.cp);
                this.selectCiudades(this.estado);
            }
        },
        mounted() {
            this.listarEmpresa(1,this.buscar,this.criterio);
            this.listarEmpresaVerificadora(1);
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
</style>
