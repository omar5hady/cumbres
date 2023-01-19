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
                        <i class="fa fa-align-justify"></i> Notarias
                        <!--   Boton Nuevo    -->
                        <Button :btnClass="'btn-secondary'" :icon="'icon-plus'" @click="abrirModal('notaria','registrar')">
                            Nuevo
                        </Button>
                        <!---->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-5" v-model="criterio" @click="limpiarBusqueda()">
                                      <option value="notaria">Notaria</option>
                                      <option value="titular">Titular</option>
                                      <option value="direccion">Dirección</option>
                                    </select>

                                    <input type="text"  v-model="buscar" @keyup.enter="listarNotaria(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <Button :icon="'fa fa-search'" click="listarNotaria(1,buscar,criterio)">Buscar</Button>
                                </div>
                            </div>
                        </div>
                        <TableComponent :cabecera="['','Notaria','Titular','Teléfono','Dirección','Colonia','Estado','Ciudad']">
                            <template v-slot:tbody>
                                <tr v-for="notaria in arrayNotaria" :key="notaria.id">
                                    <td class="td2">
                                        <Button :btnClass="'btn-warning'" :size="'btn-sm'" title="Editar" :icon="'icon-pencil'"
                                            @click="abrirModal('notaria','actualizar',notaria)"
                                        ></Button>
                                        <Button :btnClass="'btn-danger'" :size="'btn-sm'" title="Eliminar" :icon="'icon-trash'"
                                            @click="eliminarNotaria(notaria)"
                                        ></Button>
                                    </td>
                                    <td class="td2" v-text="notaria.notaria"></td>
                                    <td class="td2" v-text="notaria.titular"></td>
                                    <td class="td2" v-text="notaria.telefono_1"></td>
                                    <td class="td2" v-text="notaria.direccion"></td>
                                    <td class="td2" v-text="notaria.colonia"></td>
                                    <td class="td2" v-text="notaria.estado"></td>
                                    <td class="td2" v-text="notaria.ciudad"></td>
                                </tr>
                            </template>
                        </TableComponent>
                        <Nav :current="pagination.current_page"
                            :last="pagination.last_page"
                            @changePage="cambiarPagina"
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
                    <RowModal :label1="'Notaria'">
                        <input type="text" v-model="notaria" class="form-control" placeholder="Nombre de la notaria">
                    </RowModal>
                    <RowModal :label1="'Titular'" :clsRow1="'col-md-7'">
                        <input type="text" v-model="titular" class="form-control" placeholder="Nombre del titular">
                    </RowModal>
                    <RowModal :label1="'Teléfono 1'" :clsRow2="'col-md-4'" :label2="'Teléfono 2'">
                        <input type="text" v-model="telefono_1" maxlength="10" v-on:keypress="isNumber($event)" class="form-control" placeholder="Telefono 1">
                        <template v-slot:input2>
                            <input type="text" v-model="telefono_2" maxlength="10" v-on:keypress="isNumber($event)" class="form-control" placeholder="Telefono 2">
                        </template>
                    </RowModal>
                    <RowModal :label1="'Teléfono 3'" :clsRow2="'col-md-4'" :label2="'Teléfono 4'">
                        <input type="text" v-model="telefono_3" maxlength="10" v-on:keypress="isNumber($event)" class="form-control" placeholder="Telefono 3">
                        <template v-slot:input2>
                            <input type="text" v-model="telefono_4" maxlength="10" v-on:keypress="isNumber($event)" class="form-control" placeholder="Telefono 4">
                        </template>
                    </RowModal>
                    <RowModal :clsRow1="'col-md-6'" :label1="'Dirección'">
                        <input type="text" v-model="direccion" class="form-control" placeholder="Dirección">
                    </RowModal>
                    <RowModal :label1="'Colonia'" :clsRow2="'col-md-4'" :label2="'Código postal'">
                        <input type="text" v-model="colonia" class="form-control" placeholder="Colonia">
                        <template v-slot:input2>
                            <input type="text" maxlength="5" v-model="cp" v-on:keypress="isNumber($event)" class="form-control" placeholder="Codigo postal">
                        </template>
                    </RowModal>
                    <RowModal :label1="'Estado'" :clsRow1="'col-md-8'">
                        <select class="form-control" v-model="estado" @click="selectCiudades(estado)">
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
                    <RowModal :clsRow1="'col-md-8'" :label1="'Ciudad'">
                        <select class="form-control" v-model="ciudad">
                            <option v-for="ciudades in arrayCiudades" :key="ciudades.municipio" :value="ciudades.municipio" v-text="ciudades.municipio"></option>
                        </select>
                    </RowModal>

                    <!-- Div para mostrar los errores que mande validerNotaria -->
                    <div v-show="errorNotaria" class="form-group row div-error">
                        <div class="text-center text-error">
                            <div v-for="error in errorMostrarMsjNotaria" :key="error" v-text="error"></div>
                        </div>
                    </div>
                </template>
                <template v-slot:buttons-footer>
                    <Button :icon="'icon-check'" v-if="tipoAccion==1" @click="registrarNotaria()">Guardar</Button>
                    <Button :icon="'icon-check'" v-if="tipoAccion==2" @click="actualizarNotaria()">Guardar cambios</Button>
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
import Button from '../Componentes/ButtonComponent.vue'
import Nav from '../Componentes/NavComponent.vue'
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
                id:0,
                notaria : '',
                titular : '',
                telefono_1 : '',
                telefono_2 : '',
                telefono_3 : '',
                telefono_4 : '',
                estado : 'San Luis Potosí',
                ciudad : '',
                cp: 0,
                direccion: '',
                colonia: '',
                arrayNotaria : [],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                errorNotaria : 0,
                errorMostrarMsjNotaria : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'notaria',
                buscar : '',
                arrayCiudades : []
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
            listarNotaria(page, buscar, criterio){
                let me = this;
                var url = '/notaria?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayNotaria = respuesta.notarias.data;
                    me.pagination = respuesta.pagination;
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
                me.listarNotaria(page,me.buscar,me.criterio);
            },
            /**Metodo para registrar  */
            registrarNotaria(){
                if(this.proceso==true){
                    return;
                }
                if(this.validarNotaria()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                this.proceso=true;
                let me = this;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/notaria/registrar',{
                    'notaria': this.notaria,
                    'titular': this.titular,
                    'telefono_1': this.telefono_1,
                    'telefono_2': this.telefono_2,
                    'telefono_3': this.telefono_3,
                    'telefono_4': this.telefono_4,
                    'colonia': this.colonia,
                    'estado': this.estado,
                    'ciudad': this.ciudad,
                    'direccion' : this.direccion,
                    'cp' : this.cp
                }).then(function (response){
                    me.proceso=false;
                    me.cerrarModal(); //al guardar el registro se cierra el modal
                    me.listarNotaria(1,'','notaria'); //se enlistan nuevamente los registros
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Notaria agregada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },
            actualizarNotaria(){

                if(this.validarNotaria()) //Se verifica si hay un error (campo vacio)
                {
                    return;
                }

                let me = this;
                //Con axios se llama el metodo update de FraccionaminetoController
                axios.put('/notaria/actualizar',{
                    'notaria': this.notaria,
                    'titular': this.titular,
                    'telefono_1': this.telefono_1,
                    'telefono_2': this.telefono_2,
                    'telefono_3': this.telefono_3,
                    'telefono_4': this.telefono_4,
                    'colonia': this.colonia,
                    'estado': this.estado,
                    'ciudad': this.ciudad,
                    'direccion' : this.direccion,
                    'cp' : this.cp,
                    'id' : this.id
                }).then(function (response){

                    me.cerrarModal();
                    me.listarNotaria(1,'','notaria');
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
            eliminarNotaria(data =[]){
                this.id=data['id'];

                //console.log(this.fraccionamiento_id);
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

                axios.delete('/notaria/eliminar',
                        {params: {'id': this.id}}).then(function (response){
                        swal(
                        'Borrado!',
                        'Notaria borrado correctamente.',
                        'success'
                        )
                        me.listarNotaria(1,'','notaria');
                    }).catch(function (error){
                        console.log(error);
                    });
                }
                })
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
             limpiarBusqueda(){
                let me=this;
                me.buscar= "";
            },
            validarNotaria(){
                this.errorNotaria=0;
                this.errorMostrarMsjNotaria=[];

                if(!this.notaria) //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjNotaria.push("El nombre de la Notaria no puede ir vacio.");

                    if(!this.titular) //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjNotaria.push("El nombre del Titular no puede ir vacio.");

                if(this.errorMostrarMsjNotaria.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorNotaria = 1;

                return this.errorNotaria;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.notaria = '';
                this.titular = '';
                this.telefono_1 = '';
                this.telefono_2 = '';
                this.telefono_3 = '';
                this.telefono_4 = '';
                this.colonia = '';
                this.estado = '';
                this.ciudad = '';
                this.direccion = '';
                this.cp = 0;
                this.user_alta = '';
                this.errorNotaria = 0;
                this.errorMostrarMsjNotaria = [];
            },

            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(modelo, accion,data =[]){
                switch(modelo){
                    case "notaria":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Notaria';
                                this.notaria ='';
                                this.titular ='';
                                this.telefono_1 ='';
                                this.telefono_2 ='';
                                this.telefono_3 ='';
                                this.telefono_4 ='';
                                this.colonia ='';
                                this.estado ='San Luis Potosí';
                                this.ciudad ='';
                                this.direccion = '';
                                this.cp = 0;
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.selectCiudades(data['estado'])
                                this.modal =1;
                                this.tituloModal='Actualizar Notaria';
                                this.tipoAccion=2;
                                this.id=data['id'];
                                this.notaria=data['notaria'];
                                this.titular=data['titular'];
                                this.telefono_1=data['telefono_1'];
                                this.telefono_2=data['telefono_2'];
                                this.telefono_3=data['telefono_3'];
                                this.telefono_4=data['telefono_4'];
                                this.colonia=data['colonia'];
                                this.estado=data['estado'];
                                this.ciudad=data['ciudad'];
                                this.direccion=data['direccion'];
                                this.cp=data['cp'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarNotaria(1,this.buscar,this.criterio);
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
