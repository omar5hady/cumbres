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
                        <i class="fa fa-align-justify"></i> Fraccionamientos
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <!--Criterios para el listado de busqueda -->
                                    <select class="form-control col-md-4" v-model="criterio" @change="buscar=''">
                                      <option value="fraccionamientos.nombre">Fraccionamiento</option>
                                      <option value="tipo_proyecto">Tipo de Proyecto</option>
                                    </select>
                                    
                                    <select class="form-control" v-if="criterio=='tipo_proyecto'" v-model="buscar" @keyup.enter="listarFraccionamiento(1,buscar,criterio)" >
                                        <option value="1">Lotificación</option>
                                        <option value="2">Departamento</option>
                                        <option value="3">Terreno</option>
                                    </select>
                                    <input type="text" v-else v-model="buscar" @keyup.enter="listarFraccionamiento(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                </div>
                                <div class="input-group">
                                    <button type="submit" @click="listarFraccionamiento(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <TableComponent :cabecera="['','Nombre','Tipo','Dirección','Logo']">
                            <template v-slot:tbody>
                                <tr v-for="fraccionamiento in arrayFraccionamiento" :key="fraccionamiento.id">
                                    <td>
                                        <button type="button" @click="abrirModal(fraccionamiento)" class="btn btn-info btn-sm">
                                        <i class="icon-cloud-upload"></i>
                                        </button> &nbsp;
                                    </td>
                                    <td v-text="fraccionamiento.nombre"></td>
                                    <td v-if="fraccionamiento.tipo_proyecto==1" v-text="'Lotificación'"></td>
                                    <td v-if="fraccionamiento.tipo_proyecto==2" v-text="'Departamento'"></td>
                                    <td v-if="fraccionamiento.tipo_proyecto==3" v-text="'Terreno'"></td>
                                    <td v-text="fraccionamiento.calle + ' No. ' + fraccionamiento.numero"></td>
                                    <td class="td2" style="width:7%" v-if = "fraccionamiento.logo_fracc"><a target="_blank" class="btn btn-success btn-sm" v-bind:href="'/downloadLogoFraccionamiento/'+fraccionamiento.logo_fracc"><i class="fa fa-download fa-spin"></i></a></td>
                                    <td class="td2" v-else></td>
                                </tr>
                            </template>
                        </TableComponent>
                        <nav>
                            <!--Botones de paginacion -->
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            
            <!-- Modal para la carga de los archivos-->
            <ModalComponent v-if="modal"
                :titulo="tituloModal"
                :size="'modal-md'"
                @closeModal="cerrarModal()"
            >
                <template v-slot:body>

                      <div class="modal-body">
                        <div class="contenedor-modal">

                                <div class="form-sub">
                                    <form  method="post" @submit="formSubmitLogo" enctype="multipart/form-data">
                                         <div class="form-opc"> 
                                            <div class="form-archivo">

                                                <input ref="imageSelectorLogo" v-show="false" type="file"  v-on:change="onImageChangeLogo">

                                                                <label class="label-button"
                                                                    @click="onSelectLogo"
                                                                    >
                                                                    Sube aqui el logo del fraccionamiento
                                                                <i class="fa fa-upload"></i>
                                                                </label>
                                                    <div v-if="nom_archivo=='Seleccione Archivo'" class="text-file-hide"   v-text="nom_archivo" ></div>
                                            
                                                    <div v-else class="text-file"  v-text="nom_archivo"></div>
                                            </div>
                                                <div class="boton-modal">
                                                    <button v-show="nom_archivo!='Seleccione Archivo'" type="submit" class="btn btn-success boton-modal">Subir Archivo</button>
                                                </div>
                                         </div>

                                    </form>

                                </div>
                        
                     
                        
                        </div>

                    
                    </div>
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

    export default {
        components:{
            ModalComponent,
            TableComponent
        },
        data(){
            return{
                proceso:false,
                id:0,
                nombre : '',
                tipo_proyecto : 0,
                calle : '',
               
                archivo_logo: '',
               
                arrayFraccionamiento : [],
                modal : 0,
                modal: 0,
              
                tituloModal: '',
                nom_archivo:'Seleccione Archivo',
                tipoAccion: 0,
                errorFraccionamiento : 0,
                errorMostrarMsjFraccionamiento : [],
                pagination : {
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
            //funciones para carga de los logo del fraccionamiento 
            onImageChangeLogo(e){
                console.log(e.target.files[0]);
                this.archivo_logo = e.target.files[0];
                this.nom_archivo = e.target.files[0].name;
            },
             onSelectLogo(){
                 this.$refs.imageSelectorLogo.click()
                },

            formSubmitLogo(e) {
                e.preventDefault();
                let currentObj = this;
            
                let formData = new FormData();
                formData.append('archivo_logo', this.archivo_logo);
                let me = this;
                axios.post('/formSubmitLogoFraccionamiento/'+this.id, formData)
                .then(function (response) {
                    currentObj.success = response.data.success;
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Logo guardado correctamente',
                        showConfirmButton: false,
                        timer: 2000
                        })
                    me.cerrarModal();
                   me.listarFraccionamiento(1,'','fraccionamiento');

                }).catch(function (error) {
                    currentObj.output = error;

                });
            },

            /**Metodo para mostrar los registros */
            listarFraccionamiento(page, buscar, criterio){
                let me = this;
                var url = '/fraccionamiento?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayFraccionamiento = respuesta.fraccionamientos.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page, buscar, criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esta pagina
                me.listarFraccionamiento(page,buscar,criterio);
            },
            
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.archivo_logo = '';
                this.errorFraccionamiento = 0;
                this.errorMostrarMsjFraccionamiento = [];
                this.nom_archivo = 'Seleccione Archivo';
            },
            /**Metodo para mostrar la ventana modal, dependiendo si es para actualizar o registrar */
            abrirModal(data =[] ){
                this.modal = 1;
                this.tituloModal='Subir Logo';
                this.id=data['id'];
                this.archivo_logo=data['logo_fracc'];
            }
        },
        mounted() {
            this.listarFraccionamiento(1,this.buscar,this.criterio);
        }
    }
</script>
<style scoped>
    .text-formfile{
    
        color: grey;
        display:flex;
        padding-top: 13px;
    /*  background-color: aqua; */
        justify-content: left;
    
    }
    .contenedor-modal{
        
        display: block;
        flex-direction: column;
        
        margin: auto;
        overflow-x: auto;
        width: fit-content;
        max-width: 100%;
    

    }

    .label-button{
    border-style: solid;
    cursor:pointer; 
    color: #fff;
    background-color: #00ADEF;
    border-color: #00ADEF;
    padding: 10px;
    margin: 15px;
    }

    .label-button:hover {
    color: #fff;
    background-color: #1b8eb7;
    border-color: #00b0bb;
    
      
      }
    .form-sub{
        border: 1px solid #c2cfd6;
        margin-top: 20px;
        width: 100%;
        
    
    }
    .form-opc{
        display: flex;
        flex-direction: column;
        
    
    }
    .form-archivo{
        display: flex;
        flex-direction: row;
    
        width: 100%;
    }
    .text-file{
    
        color: rgb(39, 38, 38);
        font-size:12px;
        word-break: break-all;
        font-weight: bold;
        width: 300px;
        padding: 15px;
        
        
    
    }
    .text-file-hide{
    
        color: rgb(127, 130, 134);
        font-size:13px;
        word-break: break-all;
        font-weight: bold;
        width: 300px;
        padding: 15px;
        
        
    }
    .boton-modal{
        margin-top: 15px;
        display: flex;
        flex-direction: row;
        justify-content: center;
    }
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
