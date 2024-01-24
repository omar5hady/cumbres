<template lang="">
    <ModalComponent
        :titulo="titulo"
        @closeModal="$emit('closeModal')"
    >
        <template v-slot:body>
            <ul class="nav nav-tabs" v-if="tipoAccion == 2">
                <li class="nav-item"><a class="nav-link"  v-bind:class="{ 'active': paso==1 }" @click="paso = 1">Generales</a></li>
                <li class="nav-item"><a class="nav-link"  v-bind:class="{ 'active': paso==2 }" @click="paso = 2">Equipamiento Urbano</a></li>
                <li class="nav-item"><a class="nav-link"  v-bind:class="{ 'active': paso==3 }" @click="paso = 3">Restricciones en Construcción</a></li>
            </ul>
            <br>
            <template v-if="paso == 1">
                <RowModal clsRow1="col-md-3" label1="Tipo de Proyecto" id1="tipo_proyecto">
                    <select class="form-control" v-model="data.tipo_proyecto">
                        <option value="0">Seleccione</option>
                        <option value="1">Lotificación</option>
                        <option value="2">Departamento</option>
                        <option value="3">Terreno</option>
                    </select>
                </RowModal>
                <RowModal clsRow1="col-md-9" label1="Nombre" id1="nombre">
                    <input type="text" v-model="data.nombre" class="form-control" placeholder="Nombre del fraccionamiento">
                </RowModal>
                <RowModal clsRow1="col-md-4" label1="Calle" id1="calle"
                    label2="Num." clsRow2="col-md-3" id2="numero"
                >
                    <input type="text" v-model="data.calle" class="form-control" placeholder="Calle">
                    <template v-slot:input2>
                        <input type="number" min="0" v-model="data.numero" class="form-control" placeholder="Numero">
                    </template>
                </RowModal>
                <RowModal clsRow1="col-md-7" label1="Colonia" id1="colonia">
                    <input type="text" v-model="data.colonia" class="form-control" placeholder="Colonia" id="colonia">
                </RowModal>

                <RowModal clsRow1="col-md-4" label1="Estado" id1="estado"
                    clsRow2="col-md-4" label2="Ciudad" id2="ciudad"
                >
                    <select class="form-control" v-model="data.estado" @change="selectCiudades(data.estado)">
                        <option v-for="e in estados" :key="e" :value="e">{{ e }}</option>
                    </select>
                    <template v-slot:input2>
                        <select class="form-control" v-model="data.ciudad">
                            <option v-for="c in arrayCiudades" :key="c.municipio" :value="c.municipio" v-text="c.municipio"></option>
                        </select>
                    </template>
                </RowModal>

                <RowModal label1="Delegación" id1="delegacion" clsRow1="col-md-7">
                    <input type="text" v-model="data.delegacion" class="form-control" placeholder="Delegacion">
                </RowModal>

                <RowModal clsRow1="col-md-6" id1="cp" label1="Codigo Postal">
                    <input type="text" @keypress="$root.isNumber($event)" maxlength="5" v-model="data.cp" class="form-control" placeholder="Codigo postal">
                </RowModal>

                <template v-if="rolId != 3">
                    <RowModal
                        label1="Fecha de inicio (ventas):" id1="fecha_ini_ventas" clsRow1="col-md-6"
                    >
                        <input type="date" v-model="data.fecha_ini_venta" class="form-control" placeholder="Fecha de terminacion">
                    </RowModal>

                    <template v-if="tipoAccion == 2">
                        <RowModal label1="Gerente del proyecto" id1="gerente_id" clsRow1="col-md-5">
                            <select class="form-control" v-model="data.gerente_id">
                                <option value="">Seleccione</option>
                                <option v-for="gerente in arrayGerentes" :key="gerente.id" :value="gerente.id" v-text="gerente.nombre + ' ' + gerente.apellidos"></option>
                            </select>
                        </RowModal>

                        <RowModal label1="Arquitecto del proyecto" id1="arquitecto_id" clsRow1="col-md-5">
                            <select class="form-control" v-model="data.arquitecto_id">
                                <option value="">Seleccione</option>
                                <option v-for="arquitectos in arrayArquitectos" :key="arquitectos.id" :value="arquitectos.id" v-text="'Arq. ' + arquitectos.name"></option>
                            </select>
                        </RowModal>

                        <RowModal label1="Encargado de postventa" id1="postventa_id" clsRow1="col-md-5">
                            <select class="form-control" v-model="data.postventa_id">
                                <option value="">Seleccione</option>
                                <option v-for="postventa in arrayPostVenta" :key="postventa.id" :value="postventa.id" v-text="postventa.name"></option>
                            </select>
                        </RowModal>

                        <hr>

                        <RowModal label1="" clsRow1="col-md-12">
                            <center><h5> Área vendible </h5></center>
                        </RowModal>

                        <RowModal label1="Habitacional" id="area_vendible_habitacional" clsRow1="col-md-4"
                            label2="Precio m2" id2="precio_m2_habitacional" clsRow2="col-md-3"
                        >
                            <input type="text" @keypress="$root.isNumber($event)" maxlength="6" v-model="data.area_vendible_habitacional" class="form-control" placeholder="Área vendible habitacional">
                            <template v-slot:input2>
                                <input type="text" @keypress="$root.isNumber($event)" maxlength="6" v-model="data.precio_m2_habitacional" class="form-control" placeholder="Precio por m2">
                            </template>
                        </RowModal>

                        <RowModal label1="Comercial" id="area_vendible_comercial" clsRow1="col-md-4"
                            label2="Precio m2" id2="precio_m2_comercial" clsRow2="col-md-3"
                        >
                            <input type="text" @keypress="$root.isNumber($event)" maxlength="6" v-model="data.area_vendible_comercial" class="form-control" placeholder="Área vendible comercial">
                            <template v-slot:input2>
                                <input type="text" @keypress="$root.isNumber($event)" maxlength="6" v-model="data.precio_m2_comercial" class="form-control" placeholder="Precio por m2">
                            </template>
                        </RowModal>

                        <RowModal label1="Reserva" id="area_vendible_reserva" clsRow1="col-md-4"
                            label2="Precio m2" id2="precio_m2_reserva" clsRow2="col-md-3"
                        >
                            <input type="text" @keypress="$root.isNumber($event)" maxlength="6" v-model="data.area_vendible_reserva" class="form-control" placeholder="Área vendible reserva">
                            <template v-slot:input2>
                                <input type="text" @keypress="$root.isNumber($event)" maxlength="6" v-model="data.precio_m2_reserva" class="form-control" placeholder="Precio por m2">
                            </template>
                        </RowModal>
                    </template>

                </template>

                <!-- Div para mostrar los errores que mande validerFraccionamiento -->
                <div v-show="error" class="form-group row div-error">
                    <div class="text-center text-error">
                        <div v-for="error in errorMostrarMsjFraccionamiento" :key="error" v-text="error">

                        </div>
                    </div>
                </div>
            </template>
            <template v-if="paso == 2">
                <RowModal label1="Categoría" id1="categoria" clsRow1="col-md-4">
                    <input type="text" name="categoria" list="categoria" class="form-control" v-model="data.nEquipamiento.categoria" placeholder="Categoria">
                    <datalist id="categoria">
                        <option value="">Seleccione</option>
                        <option value="Parques">Parques</option>
                        <option value="Estancias">Estancias</option>
                        <option value="Escuelas">Escuelas</option>
                        <option value="Hospitales">Hospitales</option>
                        <option value="Transportes">Transportes</option>
                    </datalist>
                </RowModal>

                <RowModal :label1="data.nEquipamiento.categoria != 'Transportes' ? 'Lugar' : 'Tipo de transporte'"
                    id1="nombre_lugar" clsRow1="col-md-5"
                >
                    <input type="text" class="form-control"  v-if="data.nEquipamiento.categoria != 'Transportes'" v-model="data.nEquipamiento.nombre" placeholder="Nombre del lugar">
                    <input type="text" class="form-control" v-else v-model="data.nEquipamiento.nombre" placeholder="Publico/Privado">
                </RowModal>

                <RowModal label1="Descripción" id1="desc" clsRow1="col-md-6"
                    label2="" clsRow2="col-md-3"
                >
                    <textarea v-model="data.nEquipamiento.descripcion" cols="40" rows="3" class="form-control" placeholder="Descripción"></textarea>
                    <template v-slot:input2>
                        <Button title="Agregar nuevo"@click="addEquipment(data.nEquipamiento)" btnClass="btn-success btn-sm"
                            icon="icon-check"
                        >Añadir Equipamiento</Button>
                    </template>
                </RowModal>

                <hr>
                <TableComponent v-if="data.equipamientos.length" :cabecera="['',
                    'Categoria','Nombre','Descripción'
                ]">
                    <template v-slot:tbody>
                        <tr v-for="eq in data.equipamientos" :key="eq.id">
                            <td class="td2">
                                <button type="button" class="btn btn-danger btn-sm" @click="eliminarEquipamiento(eq)">
                                    <i class="icon-trash"></i>
                                </button>
                                <button title="Guardar cambios" type="button" @click="actualizarEq(eq)" class="btn btn-success btn-sm">
                                    <i class="icon-check"></i>
                                </button>
                            </td>
                            <td class="td2">
                                <input type="text" class="form-control" v-model="eq.categoria">
                            </td>
                            <td class="td2">
                                <input type="text" class="form-control" v-model="eq.nombre">
                            </td>
                            <td class="td2">
                                <textarea class="form-control" v-model="eq.descripcion"></textarea>
                            </td>
                        </tr>
                    </template>
                </TableComponent>
            </template>
            <template v-if="paso == 3">
                <RowModal label1="Ambientales" id1="rest_ambientales" clsRow1="col-md-8">
                    <textarea v-model="data.rest_ambientales" cols="40" rows="4" class="form-control"
                    placeholder="Restricciones Ambientales"></textarea>
                </RowModal>
                <RowModal label1="Colindancias ecológicas" id1="rest_federales" clsRow1="col-md-8">
                    <textarea v-model="data.rest_federales" cols="40" rows="4" class="form-control"
                    placeholder="Colindancias con zonas ecológicas, reservas forestales y reservas federales"></textarea>
                </RowModal>
                <RowModal label1="Otras" id1="rest_otras" clsRow1="col-md-8">
                    <textarea v-model="data.rest_otras" cols="40" rows="4" class="form-control"
                    placeholder="Cualquier otra limitación decretada por autoridades competentes y/o previstas en la legislación aplicable"></textarea>
                </RowModal>
            </template>
        </template>
        <template v-slot:buttons-footer>
            <template v-if="paso == 1">
                <Button v-if="tipoAccion==1" icon="icon-check" @click="registrarFraccionamiento()">Guardar</Button>
                <Button v-if="tipoAccion==2" icon="icon-check" @click="actualizarFraccionamiento()">Actualizar</Button>
            </template>
            <template v-if="paso == 3">
                <Button btnClass="btn-success" icon="icon-check" @click="updateRestricciones()">Actualizar Restricciones</Button>
            </template>
        </template>
    </ModalComponent>
</template>
<script>
import ModalComponent from '../../../Componentes/ModalComponent.vue';
import RowModal from '../../../Componentes/ComponentesModal/RowModalComponent.vue';
import Button from '../../../Componentes/ButtonComponent.vue';
export default {
    components:{
        ModalComponent,
        RowModal,
        Button
    },
    props:{
        rolId: {typeof: String},
        titulo: {typeof: String},
        tipoAccion: {typeof: Number},
        datos: {typeof: Object},
        arrayGerentes : {typeof: Array},
        arrayArquitectos : {typeof: Array},
        arrayPostVenta : {typeof: Array},
    },
    data() {
        return {
            paso: 1,
            error: 0,
            errorMostrarMsjFraccionamiento: [],
            arrayCiudades: [],
            estados: [
                "San Luis Potosí",
                "Baja California",
                "Baja California Sur",
                "Coahuila de Zaragoza",
                "Colima",
                "Chiapas",
                "Chihuahua",
                "Ciudad de México",
                "Durango",
                "Guanajuato",
                "Guerrero",
                "Hidalgo",
                "Jalisco",
                "México",
                "Michoacán de Ocampo",
                "Morelos",
                "Nayarit",
                "Nuevo León",
                "Oaxaca",
                "Puebla",
                "Querétaro",
                "Quintana Roo",
                "Sinaloa",
                "Sonora",
                "Tabasco",
                "Tamaulipas",
                "Tlaxcala",
                "Veracruz de Ignacio de la Llave",
                "Yucatán",
                "Zacatecas"
            ],
            data:{
                gerente_id : '',
                arquitecto_id :'',
                postventa_id:'',
                nombre : '',
                tipo_proyecto : 0,
                calle : '',
                colonia : '',
                estado : 'San Luis Potosí',
                ciudad : '',
                delegacion: '',
                cp: 0,
                numero : 0,

                rest_ambientales:'',
                rest_federales:'',
                rest_otras:'',
                equipamientos: [],
                nEquipamiento: {},

                area_vendible_habitacional: 0,
                area_vendible_comercial: 0,
                area_vendible_reserva: 0,

                precio_m2_habitacional: 0,
                precio_m2_comercial: 0,
                precio_m2_reserva: 0
            }
        }
    },
    methods: {
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
        /**Metodo para registrar  */
        registrarFraccionamiento(){
            if(this.proceso==true)
                return;
            if(this.validarFraccionamiento()) //Se verifica si hay un error (campo vacio)
                return;

            this.proceso=true;
            let me = this;
            //Con axios se llama el metodo store de FraccionaminetoController
            axios.post('/fraccionamiento/registrar',{
                'nombre': this.data.nombre,
                'tipo_proyecto': this.data.tipo_proyecto,
                'calle': this.data.calle,
                'colonia': this.data.colonia,
                'estado': this.data.estado,
                'ciudad': this.data.ciudad,
                'delegacion' : this.data.delegacion,
                'numero' : this.data.numero,
                'cp' : this.data.cp,
                'area_vendible_habitacional': this.data.area_vendible_habitacional,
                'area_vendible_comercial': this.data.area_vendible_comercial,
                'area_vendible_reserva': this.data.area_vendible_reserva,

                'precio_m2_habitacional': this.data.precio_m2_habitacional,
                'precio_m2_comercial': this.data.precio_m2_comercial,
                'precio_m2_reserva': this.data.precio_m2_reserva,
            }).then(function (response){
                me.proceso=false;
                me.$emit('closeModal');
                //Se muestra mensaje Success
                swal({
                    position: 'top-end',
                    type: 'success',
                    title: 'Fraccionamiento agregado correctamente',
                    showConfirmButton: false,
                    timer: 1500
                    })
            }).catch(function (error){
                console.log(error);
            });
        },
        actualizarEq(equipamiento){
            let me = this;
            //Con axios se llama el metodo store de FraccionaminetoController
            axios.put('/urban-equipment/'+ equipamiento.id,{
                'id' : equipamiento.id,
                'fraccionamiento_id': equipamiento.fraccionamiento_id,
                'categoria': equipamiento.categoria,
                'nombre': equipamiento.nombre,
                'descripcion': equipamiento.descripcion,
            }).then(function (response){
                //Se muestra mensaje Success
                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                toast({
                    type: 'success',
                    title: 'Equipamiento actualizado'
                })
            }).catch(function (error){
                console.log(error);
            });
        },
        addEquipment(equipamiento){
            let me = this;
            //Con axios se llama el metodo store de FraccionaminetoController
            axios.post('/urban-equipment',{
                'fraccionamiento_id': equipamiento.fraccionamiento_id,
                'categoria': equipamiento.categoria,
                'nombre': equipamiento.nombre,
                'descripcion': equipamiento.descripcion,
            }).then(function (response){
                me.equipamientos.push(
                    response.data
                )
                me.nEquipamiento={fraccionamiento_id : me.id};
                //Se muestra mensaje Success
                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                    });
                    toast({
                    type: 'success',
                    title: 'Equipamiento registrado'
                })
            }).catch(function (error){
                console.log(error);
            });
        },

        eliminarEquipamiento(equipamiento){
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

                axios.delete('/urban-equipment/'+equipamiento.id, {
                    params: {'id': equipamiento.id}
                }).then(function (response){
                    me.equipamientos = me.equipamientos.filter( a => a.id !== equipamiento.id)
                    //Se muestra mensaje Success
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                        });
                        toast({
                        type: 'success',
                        title: 'Equipamiento eliminado'
                    })
                }).catch(function (error){
                    console.log(error);
                });

            }})

        },
        actualizarFraccionamiento(){
            if(this.validarFraccionamiento()) //Se verifica si hay un error (campo vacio)
                return;

            let me = this;
            //Con axios se llama el metodo update de FraccionaminetoController
            axios.put('/fraccionamiento/actualizar',{
                'nombre': this.data.nombre,
                'tipo_proyecto': this.data.tipo_proyecto,
                'calle': this.data.calle,
                'colonia': this.data.colonia,
                'estado': this.data.estado,
                'ciudad': this.data.ciudad,
                'delegacion' : this.data.delegacion,
                'cp' : this.data.cp,
                'id' : this.data.id,
                'numero' : this.data.numero,
                'gerente_id' : this.data.gerente_id,
                'arquitecto_id' : this.data.arquitecto_id,
                'postventa_id' : this.data.postventa_id,
                'fecha_ini_venta' : this.data.fecha_ini_venta,

                'area_vendible_habitacional': this.data.area_vendible_habitacional,
                'area_vendible_comercial': this.data.area_vendible_comercial,
                'area_vendible_reserva': this.data.area_vendible_reserva,

                'precio_m2_habitacional': this.data.precio_m2_habitacional,
                'precio_m2_comercial': this.data.precio_m2_comercial,
                'precio_m2_reserva': this.data.precio_m2_reserva,

            }).then(function (response){

                me.$emit('closeModal')
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

        updateRestricciones(){
            let me = this;
            //Con axios se llama el metodo update de FraccionaminetoController
            axios.put('/fraccionamiento/updateRestricciones',{
                'id' : this.data.id,
                'rest_ambientales' : this.data.rest_ambientales,
                'rest_federales' : this.data.rest_federales,
                'rest_otras' : this.data.rest_otras,
            }).then(function (response){

                me.$emit('closeModal');
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

        validarFraccionamiento(){
            this.error=0;
            this.errorMostrarMsjFraccionamiento=[];

            if(!this.data.nombre) //Si la variable Fraccionamiento esta vacia
                this.errorMostrarMsjFraccionamiento.push("El nombre del Fraccionamiento no puede ir vacio.");

            if(this.errorMostrarMsjFraccionamiento.length)//Si el mensaje tiene almacenado algo en el array
                this.errorFraccionamiento = 1;

            return this.errorFraccionamiento;
        },
    },
    mounted() {
        this.data = {...this.data, ...this.datos}
        if(this.tipoAccion == 2){
            this.selectCiudades(this.data.estado)
        }
    },

}
</script>
<style lang="">
    .nav-item{
        cursor: pointer;
    }
</style>
