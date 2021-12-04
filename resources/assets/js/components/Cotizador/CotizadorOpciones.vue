<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><strong><a style="color:#FFFFFF;" href="/">Home</a></strong></li>
        </ol>
        <div class="container-fluid row">
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card scroll-box">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Interes y descuento
                        &nbsp;
                    </div>
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <table class="table2  table-bordered table-striped">
                                    <thead>
                                        <tr class="card-header"><th colspan="3"><i class="fa fa-align-justify"></i> Interes y descuentos</th></tr>
                                        <tr>
                                            <th>Concepto</th>
                                            <th>Porcentaje</th>
                                            <th>Descripcion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="item in arrayListA" :key="item.id">
                                            <td class="td2" v-text="item.clave">Concepto</td>
                                            <td class="td2" style="padding:0px;">
                                                <input v-model="item.valor" v-on:keyup.enter="editaPorcentajes(item)" type="number" min="0" step=".01" class="form-control" style="height: 45px;">
                                            </td>
                                            <td class="td2" v-text="item.descripcion"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-6">
                                <table class="table2  table-bordered table-striped">
                                    <thead>
                                        <tr class="card-header">
                                            <th colspan="4">
                                                <i class="fa fa-align-justify"></i> Costos de lotes    
                                                <button @click="setValues([], 1)" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addCost">
                                                    <i class="fa fa-plus"></i> Agregar
                                                </button>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Fraccionamiento</th>
                                            <th class="text-center">Etapa</th>
                                            <th class="td2" style="width:50%">$ Costo m²</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="item in arrayLotes" :key="item.id">
                                            <td style="width:10%" class="td2 text-center" v-text="item.id"></td>
                                            <td style="width:20%" class="td2 text-center">
                                                <a href="#" v-text="item.nombre" @click="setValues(item, 2), generalId = item.id" data-toggle="modal" data-target="#editCost"></a>
                                            </td>
                                            <td style="width:20%" class="td2 text-center">
                                                <a href="#" v-text="item.num_etapa" @click="setValues(item, 2), generalId = item.id" data-toggle="modal" data-target="#editCost"></a>
                                            </td>
                                            <td style="width:90%;">
                                                <input v-model="item.costom2" v-on:keyup.enter="editaLoteEnter(item)" type="number" min="0" step=".01" class="form-control" >
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
        </div>

        <div class="modal fade" id="addCost" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #00ADEF; color: white;">
                        <h5 class="modal-title" id="exampleModalLabel">Agregar precio.</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    
                    <form action="" @submit="addPrice" method="post">
                        <div class="modal-body">
                            <div class="row">
                                <label for="" class="col-sm-4 text-rigth">Fraccionamiento</label>
                                <select v-model="buscar" @change="selectEtapas(buscar)" class="form-control col-sm-7" required>
                                    <option value="" disabled selected>Seleccione</option>
                                    <option v-for="fraccionamientos in arrayFraccionamientos" 
                                        :key="fraccionamientos.id" 
                                        :value="fraccionamientos.id" 
                                        v-text="fraccionamientos.nombre">
                                    </option>
                                </select>
                            </div>
                            <br>
                            <div class="row">
                                <label for="" class="col-sm-4 text-rigth">Etapa</label>
                                <select v-model="b_etapa" class="form-control col-sm-7" required>
                                    <option value="" disabled selected>Seleccione</option>
                                    <option v-for="etapa in arrayAllEtapas" 
                                        :key="etapa.id" 
                                        :value="etapa.id" 
                                        v-text="etapa.num_etapa">
                                    </option>
                                </select>
                            </div>
                            <br>
                            <div class="row">
                                <label for="" class="col-sm-4 text-rigth">Costo m²</label>
                                <input v-model="addPrecioEtapa" required type="number" min="0" step=".01" class="form-control col-sm-7" placeholder="$0.00">
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Agregar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editCost" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #00ADEF; color: white;">
                        <h5 class="modal-title" id="exampleModalLabel">Editar precio.</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    
                    <form action="" @submit="editPrice" method="post">
                        <div class="modal-body">
                            <div class="row">
                                <label for="" class="col-sm-4 text-rigth">Fraccionamiento</label>
                                <select v-model="buscar" @change="selectEtapas(buscar)" class="form-control col-sm-7" required>
                                    <option value="" disabled selected>Seleccione</option>
                                    <option v-for="fraccionamientos in arrayFraccionamientos" 
                                        :key="fraccionamientos.id" 
                                        :value="fraccionamientos.id" 
                                        v-text="fraccionamientos.nombre">
                                    </option>
                                </select>
                            </div>
                            <br>
                            <div class="row">
                                <label for="" class="col-sm-4 text-rigth">Etapa</label>
                                <select v-model="b_etapa" class="form-control col-sm-7" required>
                                    <option value="" disabled selected>Seleccione</option>
                                    <option v-for="etapa in arrayAllEtapas" 
                                        :key="etapa.id" 
                                        :value="etapa.id" 
                                        v-text="etapa.num_etapa">
                                    </option>
                                </select>
                            </div>
                            <br>
                            <div class="row">
                                <label for="" class="col-sm-4 text-rigth">Costo m²</label>
                                <input v-model="editPrecioEtapa" required type="number" min="0" step=".01" class="form-control col-sm-7" placeholder="$0.00">
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Editar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </main>
</template>

<script>
export default {
    
    data() {
        return{
            b_etapa:'',
            buscar:'',
            addPrecioEtapa:'',
            editPrecioEtapa:'',
            generalId:[],
            arrayAllEtapas:[],
            arrayListA:[],
            arrayLotes:[],
            arrayFraccionamientos:[],
            
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
    methods: {
        listarPorcentajes(){
            axios.get('/calc/descuentos').then(
                response => this.arrayListA = response.data
            ).catch(error => console.log(error));
        },
        editaPorcentajes(datos){
            axios.put('/calc/descuentos/edita',{
                'id':datos.id,
                'valor':datos.valor
            }).then(
                this.myAlerts.popAlert('Guardado correctamente')
            ).catch(error => console.log(error));
        },
        editaLoteEnter(datos){
            axios.put('/calc/enter/edita',{
                'id':datos.id,
                'costom2':datos.costom2,
            }).then(
                this.myAlerts.popAlert('Guardado correctamente')
            ).catch(error => console.log(error));
        },
        buscaPrecios(){
            axios.get('/calc/lotes').then(
                response => this.arrayLotes = response.data
            ).catch(error => console.log(error));
        },
        addPrice(e){
            e.preventDefault();

            console.log(this.generalId+' '+this.addPrecioEtapa+' '+this.b_etapa+' ');
            
            axios.put('/calc/window/add',{
                'etapa_id':this.b_etapa,
                'costom2':this.addPrecioEtapa,
            }).then(()=>{
                this.buscaPrecios();
                this.myAlerts.popAlert('Agregado correctamente');
                $('#addCost').modal('hide');
            }).catch(error => console.log(error));
        },
        editPrice(e){
            e.preventDefault();
            
            axios.put('/calc/window/edita',{
                'id':this.generalId,
                'etapa_id':this.b_etapa,
                'costom2':this.editPrecioEtapa,
            }).then(()=>{
                this.buscaPrecios();
                this.myAlerts.popAlert('Agregado correctamente');
                $('#editCost').modal('hide');
            }).catch(error => console.log(error));
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
            me.b_etapa="";
            
            me.arrayAllEtapas=[];
            var url = '/select_etapa_proyecto?buscar=' + buscar;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayAllEtapas = respuesta.etapas;
            }).catch(function (error) {
                console.log(error);
            });
        },
        setValues(values, type){
            if(type == 1){
                this.buscar = '';
                this.b_etapa = '';
                this.addPrecioEtapa = '';
            }else{
                this.buscar = '';
                this.b_etapa = '';
                this.editPrecioEtapa = values.costom2;
            }
        }
    },
    mounted() {
        this.listarPorcentajes();
        this.buscaPrecios();
        this.selectFraccionamientos();
    }
};
</script>
<style>
.modal-content {
  width: 100% !important;
  position: absolute !important;
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
.mostrar {
  display: list-item !important;
  opacity: 1 !important;
  position: fixed !important;
  background-color: #3c29297a !important;
}
.div-error {
  display: flex;
  justify-content: center;
}
.text-error {
  color: red !important;
  font-weight: bold;
}
.card-user2 .avatar.border-white {
    border: 5px solid #fff;
}
.card-user2 .avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    position: relative;
    margin-bottom: 15px;
}
.card2 .avatar {
    width: 200px;
    height: 200px;
    overflow: hidden;
    border-radius: 50%;
    margin-right: 5px;
}
.border-white {
    border-color: #fff!important;
}
.card-user2 .author {
    text-align: center;
    text-transform: none;
    margin-top: -65px;
}
.card2 .author {
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}
.card2 {
    border-radius: 6px;
    box-shadow: 0 2px 2px hsla(38,16%,76%,.5);
    background-color: #fff;
    color: #252422;
    margin-bottom: 20px;
    position: relative;
    z-index: 1;
    border: none;
}
.card2 .card-image2 img  {
    width: 100%;
}
img {
    vertical-align: middle;
    border-style: none;
}
.bg-gradient-primary {
    background: #00ADEF!important;
    background: linear-gradient(45deg,#321fdb 0%,#00ADEF 100%)!important;
    border-color: #00ADEF!important;
}
.p-3 {
    padding: 1rem!important;
}
</style>

