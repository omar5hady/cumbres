<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Precios de Terrenos.
                    <button v-if="tipoVista == 2" @click="tipoVista = 1" class="btn btn-sm btn-info"><i class="fa fa-arrow-left"></i> Regresar</button>
                    <button v-if="tipoVista == 2" @click="action = 'add'" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#mAddPrecio">
                        <i class="fa fa-plus"></i> Agregar
                    </button>
                </div>
                <div class="card-body">
                    
                    <div v-if="tipoVista == 1">
                        <div class="row">
                            <select v-model="b_project" class="col-sm-5 form-control">
                                <option value="">Fraccionaiento</option>
                                <option v-for="fracc in arrayFraccionamientos" :key="fracc.id" :value="fracc.nombre" v-text="fracc.nombre"></option>
                            </select>
                            <!--input type="text" v-model="b_text" class="col-sm-2 form-control" placeholder="Buscar"-->
                            <button @click="getModelos()" class="btn btn-sm btn-primary col-sm-1"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                        <br>
                        <div class="row">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Etapa</th>
                                        <th>Fraccionaiento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="modelo in arrayModelos.data" :key="modelo.id" @dblclick="tipoVista=2,arrayPrecios=modelo.precios_terreno, generalId = modelo.id" title="Doble clic">
                                        <td v-text="modelo.id">#</td>
                                        <td><a href="#" v-text="modelo.num_etapa"></a></td>
                                        <td  v-text="modelo.fracc">Fraccionaiento</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>  

                        <div class="row"> <!-- Pagination-->
                            <nav>
                                <!--Botones de paginacion -->
                                <ul class="pagination">
                                    <li class="page-item">
                                        <a class="page-link" href="#" @click.prevent="getModelos(1)">Inicio</a>
                                    </li>
                                    <li v-if="arrayModelos.current_page-3 >= 1">
                                        <a class="page-link" href="#" 
                                        @click.prevent="getModelos(arrayModelos.current_page-3)" 
                                        v-text="arrayModelos.current_page-3" ></a>
                                    </li>
                                    <li v-if="arrayModelos.current_page-2 >= 1">
                                        <a class="page-link" href="#" 
                                        @click.prevent="getModelos(arrayModelos.current_page-2)" 
                                        v-text="arrayModelos.current_page-2" ></a>
                                    </li>
                                    <li v-if="arrayModelos.current_page-1 >= 1">
                                        <a class="page-link" href="#" 
                                        @click.prevent="getModelos(arrayModelos.current_page-1)" 
                                        v-text="arrayModelos.current_page-1" ></a>
                                    </li>
                                    
                                    <li class="page-item active">
                                        <a class="page-link" href="#" v-text="arrayModelos.current_page" ></a>
                                    </li>
                                    
                                    <li v-if="arrayModelos.current_page+1 <= arrayModelos.last_page">
                                        <a class="page-link" href="#" 
                                        @click.prevent="getModelos(arrayModelos.current_page+1)" 
                                        v-text="arrayModelos.current_page+1" ></a>
                                    </li>
                                    <li v-if="arrayModelos.current_page+2 <= arrayModelos.last_page">
                                        <a class="page-link" href="#" 
                                        @click.prevent="getModelos(arrayModelos.current_page+2)" 
                                        v-text="arrayModelos.current_page+2" ></a>
                                    </li>
                                    <li v-if="arrayModelos.current_page+3 <= arrayModelos.last_page">
                                        <a class="page-link" href="#" 
                                        @click.prevent="getModelos(arrayModelos.current_page+3)" 
                                        v-text="arrayModelos.current_page+3" ></a>
                                    </li>
                                    
                                    <li class="page-item">
                                        <a class="page-link" href="#" @click.prevent="getModelos(arrayModelos.last_page)">Ultimo</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    
                    <div class="row" v-if="tipoVista == 2">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>#</th>
                                    <th>Precio mts²</th>
                                    <th>Total Gastos</th>
                                    <th>Fecha creacion</th>
                                    <th>Ultima act.</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="precio in arrayPrecios" :key="precio.id">
                                    <td v-if="precio.estatus">
                                        <button @click="destroy(precio)" type="button" class="btn btn-danger" title="Borra"><i class="fa fa-trash"></i></button>
                                        <button @click="setForm(precio)" type="button" class="btn btn-warning" title="Editar" data-toggle="modal" data-target="#mAddPrecio"><i class="fa fa-pencil-square-o"></i></button>
                                    </td><td v-else></td>
                                    <td v-text="precio.id">#</td>
                                    <td v-text="precio.precio_m2">Precio mts²</td>
                                    <td v-text="precio.total_gastos"></td>
                                    <td>
                                        <span v-if="precio.updated_at" v-text="this.moment(precio.created_at).locale('es').format('DD/MMM/YYYY H:mm:ss')"></span>
                                    </td>
                                    <td>
                                        <span v-if="precio.updated_at" v-text="this.moment(precio.updated_at).locale('es').format('DD/MMM/YYYY H:mm:ss')"></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <!--Inicio del modal-->
        <div class="modal fade" id="mAddPrecio" role="dialog" aria-labelledby="mAddPrecioLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: rgb(0, 173, 239); color: white;">
                        <h5 v-if="action == 'add'" class="modal-title" id="mAddPrecioLabel">Agregar Costo</h5>
                        <h5 v-else class="modal-title" id="mAddPrecioLabel">Editar Costo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="addPrice" @submit="nuevoPrecio" action="" method="post">
                        <div class="modal-body">
                            <div class="row">
                                <label for="" class="col-sm-3">Costo m²</label>
                                <input class="form-control col-sm-8" type="number" name="newPr" id="newPr" step="0.01" required>
                                <input type="hidden" value="" id="idPrecio">
                            </div>
                            <br>
                            <div class="row">
                                <label for="" class="col-sm-3">Total de Gastos</label>
                                <input class="form-control col-sm-8" type="number" name="toGst" id="toGst" step="0.01" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--Fin del modal-->
    </main>
</template>

<script>
    export default {
        data (){
            return {
                b_project:'',
                //b_text:'',
                generalId:'',
                action:'',
                arrayModelos:[],
                arrayPrecios:[],
                arrayFraccionamientos:[],
                tipoVista:1,
                myAlerts:{
                    popAlert : function(title = 'Alert',type = "success", description =''){
                        swal({
                            title: title,
                            type: type,
                            text: description,
                            showConfirmButton:false,
                            timer:1500,
                        })
                    }
                },
            }
        },
        computed:{
        },
        methods : {
            getModelos(page=1){
                let me = this;

                axios.get('/precio/terrenos/list?page='+page+'&b_project='+this.b_project).then(
                    response => me.arrayModelos = response.data 
                ).catch(error => console.log(error));
            },
            setForm(d){
                document.getElementById('idPrecio').value = d.id;
                document.getElementById('newPr').value = d.precio_m2;
                document.getElementById('toGst').value = d.total_gastos;
                this.action = "edit";
            },
            nuevoPrecio(e){
                e.preventDefault();
                let formData = new FormData();
                let me = this;

                formData.append('idEtapa', this.generalId);
                formData.append('precio', e.target.newPr.value);
                formData.append('tGastos', e.target.toGst.value);

                switch(this.action){
                    case 'add':{
                        axios.post('/precio/terrenos/storage', formData).then(
                            response => {
                                document.getElementById('addPrice').reset();
                                me.arrayPrecios = response.data;
                                me.myAlerts.popAlert('Guardado correctamente');
                                $('#mAddPrecio').modal('hide');
                            }
                        ).catch(error => console.log(error));

                        break;
                    }
                    case 'edit':{

                        axios.put('/precio/terrenos/edit', {
                            'idEtapa':this.generalId,
                            'precio': e.target.newPr.value,
                            'tGastos': e.target.toGst.value,
                            'idPrecio': e.target.idPrecio.value
                        }).then(
                            response => {
                                document.getElementById('addPrice').reset();
                                $('#mAddPrecio').modal('hide');
                                me.arrayPrecios = response.data;
                                me.myAlerts.popAlert('Guardado correctamente');
                            }
                        ).catch(error => console.log(error));

                        break;
                    }
                }
            },
            selectFraccionamiento(){
                let me = this;
                me.arrayFraccionamientos=[];
                var url = '/select_fraccionamiento';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayFraccionamientos = respuesta.fraccionamientos;
                }).catch(function (error) {
                    console.log(error);
                });

            },
            destroy(d){
                let me = this;

                Swal.fire({
                    title: 'Estas Seguro?',
                    text: "Quieres eliminar este registro",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Si, borrar'
                }).then((result) => {
                    if (result.value) {

                        axios.delete('/precio/terrenos/delete?idEtapa='+d.etapa_id+
                            '&idPrecio='+d.id
                        ).then(
                            response => {
                                me.arrayPrecios = response.data;
                                me.myAlerts.popAlert('Eliminado correctamente');
                            }
                        ).catch(error => console.log(error));
                    }
                })
            }
        },
        mounted() {
            this.getModelos();
            this.selectFraccionamiento();
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
</style>
