<template>
    <div class="card-body">
        <div class="form-group row border">
            <div class="col-md-10">
                <div class="form-group">
                    <label for="">Empresa solicitante </label>
                    <select class="form-control" v-model="data.emp_constructora">
                        <option value="">Seleccione</option>
                        <option v-for="empresa in empresas" :key="empresa" :value="empresa" v-text="empresa"></option>
                    </select>
                </div>
            </div>
            <div class="col-md-9">
                <div class="form-group">
                    <label for="">Contratista </label>
                    <v-select
                        :on-search="selectContratista"
                        label="nombre"
                        :options="arrayContratista"
                        placeholder="Buscar contratista..."
                        :onChange="getDatosContratista"
                    >
                    </v-select>
                    <input v-if="tipoAccion!='create'" class="form-control" readonly  type="text" v-model="data.contratista">
                </div>
            </div>
            <div class="col-md-3">
                <label for="">Clave </label>
                <input type="text" class="form-control" v-model="data.clave" placeholder="CLV-00-00">
            </div>
            <div class="col-md-3">
                <label for="">Fecha de inicio </label>
                <input type="date" class="form-control" v-model="data.f_ini">
            </div>
            <div class="col-md-3">
                <label for="">Fecha de termino </label>
                <input type="date" class="form-control" v-model="data.f_fin">
            </div>
            <div class="col-md-3">
                <label for="">% Anticipo </label>
                <input type="number" pattern="\d*" class="form-control" min="0" max="100" v-model="data.anticipo" v-on:keypress="$root.isNumber($event)">
            </div>
            <div class="col-md-3">
                <label for="">% Costo Indirecto </label>
                <input type="number" class="form-control" min="0" max="100" v-model="data.costo_indirecto_porcentaje" v-on:keypress="$root.isNumber($event)">
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Fraccionamiento </label>
                    <v-select v-if="tipoAccion=='create'"
                        :on-search="selectFraccionamiento"
                        label="nombre"
                        :options="arrayFraccionamientos"
                        placeholder="Buscar proyecto..."
                        :onChange="getDatosFraccionamiento"
                    >
                    </v-select>
                    <button v-else type="button" class="form-control btn btn-primary" @click="selectManzanaLotes(data.fraccionamiento_id)">
                    {{ data.fraccionamiento }}</button>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Dirección del proyecto:</label>
                    <input class="form-control"  type="text" v-model="data.direccion_proy">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Manzana</label>
                    <select class="form-control" v-model="manzana" @change="selectLotes(manzana,data.fraccionamiento_id)">
                        <option value="">Seleccione</option>
                        <option v-for="manzana in arrayManzanaLotes" :key="manzana.id" :value="manzana.manzana" v-text="manzana.manzana"></option>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Entre calle </label>
                    <input class="form-control"  type="text" v-model="data.calle1">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Y Calle </label>
                    <input class="form-control"  type="text" v-model="data.calle2">
                </div>
            </div>



            <div class="col-md-12">
                <!-- Div para mostrar los errores que mande validerFraccionamiento -->
                <div v-show="errorAvisoObra" class="form-group row div-error">
                    <div class="text-center text-error">
                        <div v-for="error in errorMostrarMsjAvisoObra" :key="error" v-text="error">
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="form-group row border">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Lote</label>
                    <div class="form-inline">
                    <select class="form-control" v-model="lote_id" @change="selectDatosLotes(lote_id)">
                        <option value="0">Seleccione</option>
                            <option v-for="lotes in arrayLotes" :key="lotes.id"
                                :value="lotes.id">
                                {{ lotes.num_lote }} {{ lotes.sublote ? lotes.sublote:'' }} ({{ lotes.emp_constructora }}) - {{ lotes.fecha_fin }}
                            </option>
                    </select>

                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Descripcion <span style="color:red;" v-show="descripcion==''">(*Ingrese)</span> </label>
                    <input type="text" class="form-control" v-model="descripcion"  placeholder="Descripcion">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Costo directo<span style="color:red;" v-show="costo_directo==0">(*Ingrese)</span></label>
                    <input type="text" pattern="\d*" class="form-control" v-model="costo_directo" v-on:keypress="$root.isNumber($event)" placeholder="Costo directo">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Costo indirecto <span style="color:red;" v-show="costo_indirecto==0">(*Ingrese)</span></label>
                    <p>{{ costo_indirecto=costo_directo*data.costo_indirecto_porcentaje/100 | currency}}</p>
                    <!--<input type="text" class="form-control" readonly v-model="costo_indirecto" v-on:keypress="isNumber($event)" placeholder="Costo indirecto">-->
                </div>
            </div>

            <div class="col-md-1">
                <div class="form-group">
                    <button v-if="tipoAccion == 'create'"
                        @click="agregarLote()" class="btn btn-success form-control btnagregar">
                        <i class="icon-plus"></i>
                    </button>
                    <button v-else
                        @click="registrarLote()" class="btn btn-success form-control btnagregar">
                        <i class="icon-plus"></i>
                    </button>
                </div>
            </div>

        </div>
        <div class="form-group row">
            <div class="col-md-12">
                <TableComponent :cabecera="[
                    'Opciones','Descripcion','Lote','Manzana','M&sup2;','Costo Directo',
                    'Costo Indirecto','Obra extra','Importe','Termino'
                ]">
                    <template v-slot:tbody>
                        <template v-if="data.lotesContrato.length">
                            <tr v-for="(detalle,index) in data.lotesContrato" :key="detalle.id">
                                <td>
                                    <button v-if="tipoAccion == 'create'"
                                        @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm"
                                        title="Eliminar detalle"
                                        >
                                        <i class="icon-close"></i>
                                    </button>
                                    <button v-else
                                        @click="eliminarLote(detalle)" type="button" class="btn btn-danger btn-sm"
                                        title="Eliminar lote">
                                        <i class="icon-close"></i>
                                    </button>
                                </td>
                                <td>
                                    <input v-model="detalle.descripcion" type="text" class="form-control">
                                </td>
                                <td v-if="detalle.sublote == null" v-text="detalle.lote"></td>
                                <td v-else v-text="detalle.lote + ' ' + detalle.sublote"></td>
                                <td v-text="detalle.manzana"></td>
                                <td v-text="detalle.superficie"></td>
                                <td>
                                    <input v-model="detalle.costo_directo" type="text" class="form-control">
                                </td>
                                <td>
                                    {{'$' +$root.formatNumber(detalle.costo_indirecto=detalle.costo_directo*data.costo_indirecto_porcentaje/100)}}
                                </td>
                                <td>
                                    <input v-model="detalle.obra_extra" type="text" class="form-control">
                                </td>
                                <td>
                                    {{'$' +$root.formatNumber(parseFloat(detalle.costo_directo) + parseFloat(detalle.costo_indirecto))}}
                                </td>
                                <td>
                                    <input type="date" v-model="detalle.fin_obra" class="form-control">
                                </td>
                            </tr>

                            <tr style="background-color: #CEECF5;">

                            <td align="right" colspan="5"> <strong>{{ data.total_construccion=totalSuperficie}}</strong> </td>
                                <td align="right"> <strong>{{ '$' +$root.formatNumber(data.total_costo_directo=totalCostoDirecto) }}</strong> </td>
                                <td align="right"> <strong>{{ '$' +$root.formatNumber(data.total_costo_indirecto=totalCostoIndirecto) }}</strong> </td>
                                <td align="right" colspan="2"> <strong>{{ '$' +$root.formatNumber(data.total_importe=totalImporte) }}</strong> </td>
                            </tr>
                        </template>
                        <template v-else>
                            <tr>
                                <td colspan="9">
                                    No hay lotes seleccionados
                                </td>
                            </tr>
                        </template>
                    </template>
                </TableComponent>
            </div>
        </div>

        <!-- Parametros para contrato -->
        <div class="form-group row border"  v-if="data.lotesContrato.length">
            <div class="col-md-2">
                <div class="form-group">
                    <label>Tipo</label>
                    <div class="form-inline">
                    <select class="form-control" v-model="data.tipo">
                        <option value="Vivienda">Vivienda</option>
                        <option value="Urbanización">Urbanización</option>
                        <option value="Casa club">Casa club</option>
                        <option value="Caseta">Caseta</option>
                        <option value="Locales">Locales</option>
                    </select>

                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <label>IVA</label>
                    <div class="form-inline">
                    <select class="form-control" v-model="data.iva">
                        <option value="0">No</option>
                        <option value="1">Si</option>
                    </select>

                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Descripcion corta para contrato<span style="color:red;" v-show="data.descripcion_corta==''">(*Ingrese)</span> </label>
                    <input type="text" class="form-control" v-model="data.descripcion_corta"  placeholder="Descripcion corta">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Descripcion larga para contrato<span style="color:red;" v-show="data.descripcion_larga==''">(*Ingrese)</span> </label>
                    <input type="text" class="form-control" v-model="data.descripcion_larga"  placeholder="Descripcion larga">
                </div>
            </div>

        </div>
        <div class="form-group row">
            <div class="col-md-12">
                <button type="button" class="btn btn-secondary" @click="$emit('close')"> Cerrar </button>
                <button v-if="tipoAccion=='create'"
                    type="button" class="btn btn-primary" @click="registrarAvisoObra()"> Guardar </button>
                <button v-else
                    type="button" class="btn btn-primary" @click="actualizarAvisoObra()"> Guardar cambios </button>
            </div>
        </div>
    </div>
</template>

<!-- ************************************************************************************************************************************  -->
<!-- *********************************************************** CODIGO JAVASCRIPT *************************************************************************  -->
<!-- ************************************************************************************************************************************  -->

<script>
    import vSelect from 'vue-select';
    import TableComponent from '../../Componentes/TableComponent.vue';
    export default {
        props:{
            tipoAccion:{
                type: String,
                required: true,
                default: 'create'
            },
            data:{
                type: Object,
                required: true,
                default:{
                    id: '',
                    fraccionamiento_id: 0,
                    contratista_id: 0,
                    contratista: '',
                    clave:'',
                    f_ini: new Date().toISOString().substr(0, 10),
                    f_fin: '',
                    calle1: '',
                    calle2: '',
                    total_importe : 0,
                    total_costo_directo: 0,
                    total_costo_indirecto: 0,
                    anticipo: 0,
                    total_anticipo: 0,
                    lotesContrato: [],
                    emp_constructora: 'CONCRETANIA',
                    costo_indirecto_porcentaje: 0,
                    tipo: 'Vivienda',
                    iva:0,
                    descripcion_larga: '',
                    descripcion_corta: '',
                    total_construccion: 0,
                    direccion_proy: ''
                }
            }

        },
        data(){
            return{
                proceso:false,

                arrayContratista : [],
                errorAvisoObra : 0,
                errorMostrarMsjAvisoObra : [],
                arrayFraccionamientos : [],
                arrayLotes:[],
                arrayManzanaLotes: [],
                arrayDatosLotes: [],
                empresas:[],
                lote_id:0,
                lote:'',
                sublote:'',
                manzana:'',
                construccion:'',
                costo_directo:0,
                costo_indirecto:0,
                descripcion: '',
                modelo:'',
                importe: 0.0,
                loading:true,
            }
        },
        components:{
            vSelect,
            TableComponent
        },
        computed:{
            totalCostoDirecto: function(){
                var resultado_costo_directo =0.0;
                for(var i=0;i<this.data.lotesContrato.length;i++){
                    resultado_costo_directo = parseFloat(resultado_costo_directo) + parseFloat(this.data.lotesContrato[i].costo_directo)
                }
                return Math.round(resultado_costo_directo*100)/100;
            },
            totalCostoIndirecto: function(){
                var resultado_costo_indirecto =0.0;
                for(var i=0;i<this.data.lotesContrato.length;i++){
                    resultado_costo_indirecto = parseFloat(resultado_costo_indirecto) + parseFloat(this.data.lotesContrato[i].costo_indirecto)
                }
                return Math.round(resultado_costo_indirecto*100)/100;
            },
            totalImporte: function(){
                var resultado_importe_total =0.0;
                for(var i=0;i<this.data.lotesContrato.length;i++){
                    resultado_importe_total = parseFloat(resultado_importe_total) + parseFloat(this.data.lotesContrato[i].costo_directo) + parseFloat(this.data.lotesContrato[i].costo_indirecto)
                }
                return Math.round(resultado_importe_total*100)/100;
            },
            totalConstruccion: function(){
                var resultado_construccion_total =0.0;
                for(var i=0;i<this.data.lotesContrato.length;i++){
                    resultado_construccion_total = parseFloat(resultado_construccion_total) + parseFloat(this.data.lotesContrato[i].construccion)
                }
                return Math.round(resultado_construccion_total*100)/100;
            },
            totalSuperficie: function(){
                var resultado_construccion_total =0.0;
                for(var i=0;i<this.data.lotesContrato.length;i++){
                    resultado_construccion_total = parseFloat(resultado_construccion_total) + parseFloat(this.data.lotesContrato[i].superficie)
                }
                return Math.round(resultado_construccion_total*100)/100;
            }
        },

        methods : {
            getEmpresa(){
                let me = this;
                me.empresas=[];
                var url = '/lotes/empresa/select';
                axios.get(url).then(function (response) {
                    var respuesta = response;
                    me.empresas = respuesta.data.empresas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectContratista(search, loading){
                let me = this;
                loading(true)
                var url = '/select_contratistas2?filtro='+search;
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    q: search
                    me.arrayContratista = respuesta.contratistas;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getDatosContratista(val1){
                let me = this;
                me.loading = true;
                me.data.contratista_id = val1.id;
                me.data.contratista = val1.nombre;
            },
            selectFraccionamiento(search, loading){
                let me = this;
                loading(true)
                var url = '/select_fraccionamiento2?filtro='+search;
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    q: search
                    me.arrayFraccionamientos = respuesta.fraccionamientos;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getDatosFraccionamiento(val1){
                let me = this;
                me.loading = true;
                me.data.fraccionamiento_id = val1.id;
                me.selectManzanaLotes(me.data.fraccionamiento_id);
            },
            selectManzanaLotes(buscar){
                let me = this;
                me.manzana = '';
                me.arrayManzanaLotes=[];
                var url = '/select_manzana_lotes?buscar='+buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayManzanaLotes = respuesta.lotesManzana;

                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectLotes(buscar , buscar2){
                let me = this;

                me.arrayLotes=[];
                var url = '/select_lotes_obra?buscar='+buscar + '&buscar2=' + buscar2;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayLotes = respuesta.lotes;

                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectDatosLotes(buscar){
                let me = this;

                me.arrayDatosLotes = [];
                var url = '/select_datos_lotes?buscar='+buscar;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayDatosLotes = respuesta.lotesDatos;
                    me.lote = me.arrayDatosLotes[0].num_lote;
                    me.sublote = me.arrayDatosLotes[0].sublote;
                    me.construccion = me.arrayDatosLotes[0].construccion;
                    me.manzana = me.arrayDatosLotes[0].manzana;
                    me.modelo=me.arrayDatosLotes[0].modelo;
                    me.descripcion=me.arrayDatosLotes[0].modelo;
                    me.empresa_constructora = me.arrayDatosLotes[0].emp_constructora;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            encuentra(id,emp_constructora){
                let sw=0;
                for(var i=0;i<this.data.lotesContrato.length;i++)
                {
                    if(this.data.lotesContrato[i].lote_id == id )
                    {
                        sw=true;
                    }
                    if(emp_constructora != '' )
                    if(this.data.lotesContrato[i].emp_constructora != emp_constructora){
                        sw=true;
                    }
                }
                return sw;
            },
            agregarLote(){
                let me = this;
                if(me.descripcion == '' || me.costo_directo==0 || me.costo_indirecto==0){
                }else{
                    if(me.encuentra(me.lote_id, me.empresa_constructora)){
                         swal({
                        type: 'error',
                        title: 'Error',
                        text: 'Este lote no se puede agregar',
                        })
                    }
                    else{
                        me.costo_indirecto = parseFloat(me.costo_directo) * parseFloat(me.data.costo_indirecto_porcentaje)/100;
                        me.importe = parseFloat(me.costo_directo) + parseFloat(me.costo_indirecto);
                        me.data.lotesContrato.push({
                            lote_id: me.lote_id,
                            lote: me.lote,
                            sublote: me.sublote,
                            superficie: me.construccion,
                            manzana: me.manzana,
                            descripcion: me.descripcion,
                            importe: me.importe,
                            modelo:me.modelo,
                            costo_directo: parseFloat(me.costo_directo),
                            costo_indirecto: parseFloat(me.costo_indirecto),
                            obra_extra:0,
                            emp_constructora:me.empresa_constructora
                        });
                        me.lote = '';
                        me.lote_id =0;
                        me.construccion = 0;
                        me.manzana='';
                        me.descripcion='';
                        me.costo_directo = 0;
                        me.costo_indirecto = 0;
                        me.modelo='';
                        me.empresa_constructora='';
                    }
                }
            },
            registrarLote(){
                let me = this;
                if(me.descripcion == '' || me.costo_directo==0 || me.costo_indirecto==0){
                }else{
                    if(me.encuentra(me.lote_id)){
                         swal({
                        type: 'error',
                        title: 'Error',
                        text: 'Este lote ya se encuentra agregado',
                        })
                    }else{
                        me.costo_indirecto = parseFloat(me.costo_directo) * parseFloat(me.data.costo_indirecto_porcentaje)/100;
                        me.importe = parseFloat(me.costo_directo) + parseFloat(me.costo_indirecto);

                        axios.post('/iniobra/lote/registrar',{
                            'id': this.data.id,
                            'lote': this.lote,
                            'manzana' : this.manzana,
                            'modelo' : this.modelo,
                            'superficie' : this.construccion,
                            'costo_directo' : this.costo_directo,
                            'costo_indirecto' : this.costo_indirecto,
                            'importe' : this.importe,
                            'descripcion' : this.descripcion,
                            'lote_id' : this.lote_id,
                        }).then(function (response){
                            //Obtener detalle
                            me.data.lotesContrato=[];
                            var urld = '/iniobra/obtenerDetalles?id=' + me.data.id;
                            axios.get(urld).then(function (response) {
                                var respuesta = response.data;
                                me.data.lotesContrato = respuesta.detalles;
                            })
                            .catch(function (error) {
                                console.log(error);
                            });

                        }).catch(function (error){
                            console.log(error);
                        });
                        me.lote = '';
                        me.lote_id =0;
                        me.construccion = 0;
                        me.manzana='';
                        me.descripcion='';
                        me.costo_directo = 0;
                        me.costo_indirecto = 0;
                        me.modelo='';
                    }
                }
            },
            eliminarDetalle(index){
                let me = this;
                me.data.lotesContrato.splice(index,1);
            },
            eliminarLote(data =[]){
                //this.lote_id=data['id'];
                swal({
                title: '¿Desea remover este lote?',
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
                        axios.delete('/iniobra/lote/eliminar',
                            {params: {'id': data['id']}}).then(function (response){

                            //Obtener detalle
                            me.data.lotesContrato=[];
                            var urld = '/iniobra/obtenerDetalles?id=' + me.data.id;
                            axios.get(urld).then(function (response) {
                                var respuesta = response.data;
                                me.data.lotesContrato = respuesta.detalles;
                                me.selectManzanaLotes(me.data.fraccionamiento_id);
                            })
                            .catch(function (error) {
                                console.log(error);
                            });
                        }).catch(function (error){
                            console.log(error);
                        });
                    }
                })
            },
            validarAviso(){
                this.errorAvisoObra=0;
                this.errorMostrarMsjAvisoObra=[];
                if(this.data.contratista_id==0) //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjAvisoObra.push("Seleccionar un contratista.");
                if(this.data.fraccionamiento_id==0) //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjAvisoObra.push("Seleccionar un fraccionamiento.");
                if(this.data.clave=='') //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjAvisoObra.push("Ingresar clave.");
                if(this.data.lotesContrato.length<=0) //Si la variable Fraccionamiento esta vacia
                    this.errorMostrarMsjAvisoObra.push("No se ha ingresado ningun lote");
                if(this.errorMostrarMsjAvisoObra.length)//Si el mensaje tiene almacenado algo en el array
                    this.errorAvisoObra = 1;
                return this.errorAvisoObra;
            },
            /**Metodo para registrar  */
            registrarAvisoObra(){
                if(this.proceso || this.validarAviso())
                    return;
                this.proceso=true;
                let me = this;
                me.data.total_anticipo=(me.data.anticipo/100)*me.data.total_importe;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.post('/iniobra/registrar',{
                    'fraccionamiento_id': this.data.fraccionamiento_id,
                    'contratista_id': this.data.contratista_id,
                    'clave': this.data.clave,
                    'f_ini': this.data.f_ini,
                    'f_fin': this.data.f_fin,
                    'calle1': this.data.calle1,
                    'calle2': this.data.calle2,
                    'total_importe' :this.data.total_importe,
                    'total_costo_directo':this.data.total_costo_directo,
                    'total_costo_indirecto':this.data.total_costo_indirecto,
                    'anticipo':this.data.anticipo,
                    'total_anticipo':this.data.total_anticipo,
                    'data':this.data.lotesContrato,
                    'emp_constructora':this.data.emp_constructora,
                    'costo_indirecto_porcentaje':this.data.costo_indirecto_porcentaje,
                    'tipo':this.data.tipo,
                    'iva':this.data.iva,
                    'descripcion_larga':this.data.descripcion_larga,
                    'descripcion_corta':this.data.descripcion_corta,
                    'total_superficie':this.data.total_construccion,
                    'direccion_proy':this.data.direccion_proy
                }).then(function (response){
                    me.proceso=false;
                    this.$emit('close')
                    //Se muestra mensaje Success
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Contrato guardado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                    me.proceso=false;
                });
            },
             /**Metodo para actualizar  */
             actualizarAvisoObra(){
                if(this.proceso || this.validarAviso())
                    return;

                this.proceso=true;
                let me = this;
                me.data.total_anticipo=(me.data.anticipo/100)*me.data.total_importe;
                //Con axios se llama el metodo store de FraccionaminetoController
                axios.put('/iniobra/actualizar',{
                    'id':this.data.id,
                    'fraccionamiento_id': this.data.fraccionamiento_id,
                    'contratista_id': this.data.contratista_id,
                    'calle1': this.data.calle1,
                    'calle2': this.data.calle2,
                    'clave': this.data.clave,
                    'f_ini': this.data.f_ini,
                    'f_fin': this.data.f_fin,
                    'total_importe' :this.data.total_importe,
                    'total_costo_directo':this.data.total_costo_directo,
                    'total_costo_indirecto':this.data.total_costo_indirecto,
                    'anticipo':this.data.anticipo,
                    'total_anticipo':this.data.total_anticipo,
                    'data':this.data.lotesContrato,
                    'costo_indirecto_porcentaje':this.data.costo_indirecto_porcentaje,
                    'tipo':this.data.tipo,
                    'emp_constructora':this.data.emp_constructora,
                    'iva':this.data.iva,
                    'descripcion_larga':this.data.descripcion_larga,
                    'descripcion_corta':this.data.descripcion_corta,
                    'total_superficie':this.data.total_construccion,
                    'direccion_proy':this.data.direccion_proy
                }).then(function (response){
                    me.proceso=false;
                    me.listado=1;
                    me.$emit('close')
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Contrato actualizado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                        })
                }).catch(function (error){
                    console.log(error);
                });
            },

        },
        mounted() {
            this.getEmpresa();
        },
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
</style>
