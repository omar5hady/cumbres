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
                        <i class="fa fa-align-justify"></i>Seguimiento de Tramite

                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="myTab1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active show" id="ingresar-tab" data-toggle="tab" href="#ingresar" role="tab" aria-controls="ingresar" aria-selected="true" v-text="'Por Ingresar'"></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="preautorizados-tab" data-toggle="tab" href="#preautorizados" role="tab" aria-controls="preautorizados" aria-selected="true" v-text="'Preautorizados'"></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="rechazados-tab" data-toggle="tab" href="#rechazados" role="tab" aria-controls="rechazados" aria-selected="false" v-text="'Rechazados'"></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="autorizado-tab" data-toggle="tab" href="#autorizado" role="tab" aria-controls="autorizado" aria-selected="false" v-text="'Autorizados'"></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="liquidacion-tab" data-toggle="tab" href="#liquidacion" role="tab" aria-controls="liquidacion" aria-selected="false" v-text="'Liquidación'"></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="programacion-tab" data-toggle="tab" href="#programacion" role="tab" aria-controls="programacion" aria-selected="false" v-text="'Programación de firma'"></a>
                            </li>
                        </ul>

                        <div class="tab-content" id="myTab1Content">
                            <div class="tab-pane fade active show" id="ingresar" role="tabpanel" aria-labelledby="ingresar-tab">
                                <div class="form-group row">
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <!--Criterios para el listado de busqueda -->
                                            <select class="form-control col-md-5" v-model="criterio" @click="selectFraccionamientos()">
                                                <option value="lotes.fraccionamiento_id">Proyecto</option>
                                                <option value="c.nombre">Cliente</option>
                                                <option value="g.nombre">Gestor</option>
                                                <option value="contraos.id"># Folio</option>
                                            </select>

                                            
                                            <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar" @click="selectEtapa(buscar)">
                                                <option value="">Seleccione</option>
                                                <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                            </select>

                                            <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_etapa"> 
                                                <option value="">Etapa</option>
                                                <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                            </select>

                                            
                                            <input type="text" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_manzana" class="form-control" placeholder="Manzana a buscar">
                                            <input type="text" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_lote" class="form-control" placeholder="Lote a buscar">

                                            <input v-else type="text"  v-model="buscar" @keyup.enter="listarContratos(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="form-control" placeholder="Texto a buscar">
                                            <button type="submit" @click="listarContratos(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                        
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr> 
                                                
                                                <th># Ref</th>
                                                <th>Cliente</th>
                                                <th>Asesor</th>
                                                <th>Proyecto</th>
                                                <th>Etapa</th>
                                                <th>Manzana</th>
                                                <th># Lote</th>
                                                <th>Dirección</th>
                                                <th>Avance obra</th>
                                                <th>Firma Contrato</th>
                                                <th>Resultado avaluo</th>
                                                <th>Avaluo Catastral</th>
                                                <th>Aviso preventivo</th>
                                                <th>Tipo de Crédito</th>
                                                <th>Valor de la vivienda</th>
                                                <th>Crédito Puente</th>
                                                <th>Observaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="ingresar in arrayPorIngresar" :key="ingresar.id" @dblclick="abrirModal('gestor',ingresar)" > 
                                                
                                                <td class="td2" v-text="ingresar.folio"></td>
                                                <td class="td2" v-text="ingresar.nombre_cliente"></td>
                                                <td class="td2" v-text="ingresar.proyecto"></td>
                                                <td class="td2" v-text="ingresar.etapa"></td>
                                                <td class="td2" v-text="ingresar.manzana"></td>
                                                <td class="td2" v-text="ingresar.num_lote"></td>
                                                <td class="td2" v-text="ingresar.nombre_gestor"></td>
                                                <td class="td2" v-text="ingresar.tipo_credito"></td>
                                                <td class="td2" v-text="ingresar.institucion"></td>
                                            
                                            </tr>                               
                                        </tbody>
                                    </table>  
                                </div>
                                <nav>
                                
                                </nav>
                            </div>

                            <div class="tab-pane fade" id="preautorizados" role="tabpanel" aria-labelledby="preautorizados-tab">
                                <div class="form-group row">
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <!--Criterios para el listado de busqueda -->
                                            <select class="form-control col-md-5" v-model="criterio" @click="selectFraccionamientos()">
                                                <option value="lotes.fraccionamiento_id">Proyecto</option>
                                                <option value="c.nombre">Cliente</option>
                                                <option value="g.nombre">Gestor</option>
                                                <option value="contraos.id"># Folio</option>
                                            </select>

                                            
                                            <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar" @click="selectEtapa(buscar)">
                                                <option value="">Seleccione</option>
                                                <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                            </select>

                                            <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_etapa"> 
                                                <option value="">Etapa</option>
                                                <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                            </select>

                                            
                                            <input type="text" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_manzana" class="form-control" placeholder="Manzana a buscar">
                                            <input type="text" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_lote" class="form-control" placeholder="Lote a buscar">

                                            <input v-else type="text"  v-model="buscar" @keyup.enter="listarContratos(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="form-control" placeholder="Texto a buscar">
                                            <button type="submit" @click="listarContratos(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                        
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr> 
                                                
                                                <th># Ref</th>
                                                <th>Cliente</th>
                                                <th>Asesor</th>
                                                <th>Proyecto</th>
                                                <th>Etapa</th>
                                                <th>Manzana</th>
                                                <th># Lote</th>
                                                <th>Dirección</th>
                                                <th>Avance obra</th>
                                                <th>Firma Contrato</th>
                                                <th>Resultado avaluo</th>
                                                <th>Avaluo Catastral</th>
                                                <th>Aviso preventivo</th>
                                                <th>Valor de la vivienda</th>
                                                <th>Aviso preventivo</th>
                                                <th>Institución</th>
                                                <th>Monto autorizado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="preautorizados in arrayPreautorizados" :key="preautorizados.id" @dblclick="abrirModal('gestor',preautorizados)" > 
                                                
                                                <td class="td2" v-text="preautorizados.folio"></td>
                                                <td class="td2" v-text="preautorizados.nombre_cliente"></td>
                                                <td class="td2" v-text="preautorizados.proyecto"></td>
                                                <td class="td2" v-text="preautorizados.etapa"></td>
                                                <td class="td2" v-text="preautorizados.manzana"></td>
                                                <td class="td2" v-text="preautorizados.num_lote"></td>
                                                <td class="td2" v-text="preautorizados.nombre_gestor"></td>
                                                <td class="td2" v-text="preautorizados.tipo_credito"></td>
                                                <td class="td2" v-text="preautorizados.institucion"></td>
                                            
                                            </tr>                               
                                        </tbody>
                                    </table>  
                                </div>
                                <nav>
                                
                                </nav>
                            </div>

                            <div class="tab-pane fade" id="rechazados" role="tabpanel" aria-labelledby="rechazados-tab">
                                Aqui puro rechazado
                            </div>

                            <div class="tab-pane fade" id="autorizado" role="tabpanel" aria-labelledby="autorizado-tab">
                                <div class="form-group row">
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <!--Criterios para el listado de busqueda -->
                                            <select class="form-control col-md-5" v-model="criterio" @click="selectFraccionamientos()">
                                                <option value="lotes.fraccionamiento_id">Proyecto</option>
                                                <option value="c.nombre">Cliente</option>
                                                <option value="g.nombre">Gestor</option>
                                                <option value="contraos.id"># Folio</option>
                                            </select>

                                            
                                            <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar" @click="selectEtapa(buscar)">
                                                <option value="">Seleccione</option>
                                                <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                            </select>

                                            <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_etapa"> 
                                                <option value="">Etapa</option>
                                                <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                            </select>

                                            
                                            <input type="text" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_manzana" class="form-control" placeholder="Manzana a buscar">
                                            <input type="text" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_lote" class="form-control" placeholder="Lote a buscar">

                                            <input v-else type="text"  v-model="buscar" @keyup.enter="listarContratos(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="form-control" placeholder="Texto a buscar">
                                            <button type="submit" @click="listarContratos(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                        
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr> 
                                                
                                                <th># Ref</th>
                                                <th>Cliente</th>
                                                <th>Asesor</th>
                                                <th>Proyecto</th>
                                                <th>Etapa</th>
                                                <th>Manzana</th>
                                                <th># Lote</th>
                                                <th>Dirección</th>
                                                <th>Avance obra</th>
                                                <th>Firma Contrato</th>
                                                <th>Resultado avaluo</th>
                                                <th>Avaluo Catastral</th>
                                                <th>Aviso preventivo</th>
                                                <th>Valor de la vivienda</th>
                                                <th>Aviso preventivo</th>
                                                <th>Institución</th>
                                                <th>Monto autorizado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="autorizados in arrayAutorizados" :key="autorizados.id" @dblclick="abrirModal('gestor',autorizados)" > 
                                                
                                                <td class="td2" v-text="autorizados.folio"></td>
                                                <td class="td2" v-text="autorizados.nombre_cliente"></td>
                                                <td class="td2" v-text="autorizados.proyecto"></td>
                                                <td class="td2" v-text="autorizados.etapa"></td>
                                                <td class="td2" v-text="autorizados.manzana"></td>
                                                <td class="td2" v-text="autorizados.num_lote"></td>
                                                <td class="td2" v-text="autorizados.nombre_gestor"></td>
                                                <td class="td2" v-text="autorizados.tipo_credito"></td>
                                                <td class="td2" v-text="autorizados.institucion"></td>
                                            
                                            </tr>                               
                                        </tbody>
                                    </table>  
                                </div>
                                <nav>
                                
                                </nav>
                            </div>

                            <div class="tab-pane fade" id="liquidacion" role="tabpanel" aria-labelledby="liquidacion-tab">
                                <div class="form-group row">
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <!--Criterios para el listado de busqueda -->
                                            <select class="form-control col-md-5" v-model="criterio" @click="selectFraccionamientos()">
                                                <option value="lotes.fraccionamiento_id">Proyecto</option>
                                                <option value="c.nombre">Cliente</option>
                                                <option value="g.nombre">Gestor</option>
                                                <option value="contraos.id"># Folio</option>
                                            </select>

                                            
                                            <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar" @click="selectEtapa(buscar)">
                                                <option value="">Seleccione</option>
                                                <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                            </select>

                                            <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_etapa"> 
                                                <option value="">Etapa</option>
                                                <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                            </select>

                                            
                                            <input type="text" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_manzana" class="form-control" placeholder="Manzana a buscar">
                                            <input type="text" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_lote" class="form-control" placeholder="Lote a buscar">

                                            <input v-else type="text"  v-model="buscar" @keyup.enter="listarContratos(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="form-control" placeholder="Texto a buscar">
                                            <button type="submit" @click="listarContratos(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                        
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr> 
                                                
                                                <th># Ref</th>
                                                <th>Cliente</th>
                                                <th>Asesor</th>
                                                <th>Proyecto</th>
                                                <th>Etapa</th>
                                                <th>Manzana</th>
                                                <th># Lote</th>
                                                <th>Dirección</th>
                                                <th>Avance obra</th>
                                                <th>Firma Contrato</th>
                                                <th>Resultado avaluo</th>
                                                <th>Avaluo Catastral</th>
                                                <th>Aviso preventivo</th>
                                                <th>Valor de la vivienda</th>
                                                <th>Aviso preventivo</th>
                                                <th>Institución</th>
                                                <th>Monto autorizado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="liquidados in arrayLiquidados" :key="liquidados.id" @dblclick="abrirModal('gestor',liquidados)" > 
                                                
                                                <td class="td2" v-text="liquidados.folio"></td>
                                                <td class="td2" v-text="liquidados.nombre_cliente"></td>
                                                <td class="td2" v-text="liquidados.proyecto"></td>
                                                <td class="td2" v-text="liquidados.etapa"></td>
                                                <td class="td2" v-text="liquidados.manzana"></td>
                                                <td class="td2" v-text="liquidados.num_lote"></td>
                                                <td class="td2" v-text="liquidados.nombre_gestor"></td>
                                                <td class="td2" v-text="liquidados.tipo_credito"></td>
                                                <td class="td2" v-text="liquidados.institucion"></td>
                                            
                                            </tr>                               
                                        </tbody>
                                    </table>  
                                </div>
                                <nav>
                                
                                </nav>
                            </div>

                            <div class="tab-pane fade" id="programacion" role="tabpanel" aria-labelledby="programacion-tab">
                                <div class="form-group row">
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <!--Criterios para el listado de busqueda -->
                                            <select class="form-control col-md-5" v-model="criterio" @click="selectFraccionamientos()">
                                                <option value="lotes.fraccionamiento_id">Proyecto</option>
                                                <option value="c.nombre">Cliente</option>
                                                <option value="g.nombre">Gestor</option>
                                                <option value="contraos.id"># Folio</option>
                                            </select>

                                            
                                            <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="buscar" @click="selectEtapa(buscar)">
                                                <option value="">Seleccione</option>
                                                <option v-for="fraccionamientos in arrayFraccionamientos" :key="fraccionamientos.id" :value="fraccionamientos.id" v-text="fraccionamientos.nombre"></option>
                                            </select>

                                            <select class="form-control" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_etapa"> 
                                                <option value="">Etapa</option>
                                                <option v-for="etapas in arrayEtapas" :key="etapas.id" :value="etapas.id" v-text="etapas.num_etapa"></option>
                                            </select>

                                            
                                            <input type="text" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_manzana" class="form-control" placeholder="Manzana a buscar">
                                            <input type="text" v-if="criterio=='lotes.fraccionamiento_id'" v-model="b_lote" class="form-control" placeholder="Lote a buscar">

                                            <input v-else type="text"  v-model="buscar" @keyup.enter="listarContratos(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="form-control" placeholder="Texto a buscar">
                                            <button type="submit" @click="listarContratos(1,buscar,b_etapa,b_manzana,b_lote,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                        
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table2 table-bordered table-striped table-sm">
                                        <thead>
                                            <tr> 
                                                
                                                <th># Ref</th>
                                                <th>Cliente</th>
                                                <th>Asesor</th>
                                                <th>Proyecto</th>
                                                <th>Etapa</th>
                                                <th>Manzana</th>
                                                <th># Lote</th>
                                                <th>Dirección</th>
                                                <th>Avance obra</th>
                                                <th>Firma Contrato</th>
                                                <th>Resultado avaluo</th>
                                                <th>Avaluo Catastral</th>
                                                <th>Aviso preventivo</th>
                                                <th>Valor de la vivienda</th>
                                                <th>Aviso preventivo</th>
                                                <th>Institución</th>
                                                <th>Monto autorizado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="programacion in arrayProgramacion" :key="programacion.id" @dblclick="abrirModal('gestor',programacion)" > 
                                                
                                                <td class="td2" v-text="programacion.folio"></td>
                                                <td class="td2" v-text="programacion.nombre_cliente"></td>
                                                <td class="td2" v-text="programacion.proyecto"></td>
                                                <td class="td2" v-text="programacion.etapa"></td>
                                                <td class="td2" v-text="programacion.manzana"></td>
                                                <td class="td2" v-text="programacion.num_lote"></td>
                                                <td class="td2" v-text="programacion.nombre_gestor"></td>
                                                <td class="td2" v-text="programacion.tipo_credito"></td>
                                                <td class="td2" v-text="programacion.institucion"></td>
                                            
                                            </tr>                               
                                        </tbody>
                                    </table>  
                                </div>
                                <nav>
                                
                                </nav>
                            </div>
                        </div>
                        
                        
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
         

            <!--Inicio del modal avaluo-->
           
            <!--Fin del modal consulta-->
            
         
     </main>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    export default {
        data(){
            return{
                arrayPreautorizados: [],
                arrayAutorizados: [],
                arrayLiquidados: [],
                arrayProgramacion: [],
                arrayPorIngresar:[],

                arrayFraccionamientos:[],
                arrayEtapas:[],
                
                criterio:'lotes.fraccionamiento_id',
                buscar:'',
                b_etapa:'',
                b_manzana:'',
                b_lote:'',

               
            }
        },
        computed:{


        },

        
        methods : {
            selectFraccionamientos(){
                let me = this;
                me.buscar=""
                
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
                me.buscar2=""
                
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

        
        },
       
        mounted() {
            this.selectFraccionamientos();
            
        }
    }
</script>
<style>
    .form-control:disabled, .form-control[readonly] {
    background-color: rgba(0, 0, 0, 0.06);
    opacity: 1;
    font-size: 1rem;
    color: #27417b;
    }
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: fixed !important;
        background-color: #3c29297a !important;
         overflow-y: auto;
        
    }
    .div-error{
        display:flex;
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

    .badge2 {
    display: inline-block;
    padding: 0.25em 0.4em;
    font-size: 90%;
    font-weight: bold;
    line-height: 1;
    color: #fff;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
}

    /*th {
    text-align: left;
    background-color: rgb(190, 220, 250);
    text-transform: uppercase;
    padding-top: 1rem;
    padding-bottom: 1rem;
    border-bottom: rgb(50, 50, 100) solid 2px;
    border-top: none;
    }*/

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
