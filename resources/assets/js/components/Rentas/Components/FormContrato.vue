<template lang="">
    <div class="card-body">
        <div class="col-md-12" v-if="listado==2">
            <div class="form-group">
                <center>
                    <h5 v-if="datosRenta.status == 0"
                        style="color:red;" align="right"
                        v-text="' Cancelado '"
                    ></h5>
                    <h5 v-if="datosRenta.status == 1"
                        style="color:orange;" align="right"
                        v-text="' Pendiente '"
                    ></h5>
                    <h5 v-else-if="datosRenta.status == 2"
                        style="color:green;" align="right"
                        v-text="' Vigente '"
                    ></h5>
                </center>
            </div>
        </div>
        <!-- Acordeon -->
        <div id="accordion" role="tablist">
            <!--Datos Arrendatario-->
            <div class="card mb-0">
                <div class="card-header" id="headingOne" role="tab">
                    <h5 class="mb-0">
                        <a data-toggle="collapse" href="#collapseOne"
                            aria-expanded="false" aria-controls="collapseOne" class="collapsed"
                            >Arrendatario
                        </a>
                    </h5>
                </div>

                <div
                    class="collapse" id="collapseOne"
                    role="tabpanel" aria-labelledby="headingOne"
                    data-parent="#accordion"
                >
                    <div class="form-group row border border-primary"
                        style="margin-right:0px; margin-left:0px;"
                    >
                        <div class="col-md-12">
                            <div class="form-group">
                                <center><h3></h3></center>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for=""> Tipo
                                    <span style="color:red;"
                                        v-show="datosRenta.tipo_arrendatario == ''"
                                        >(*)
                                    </span>
                                </label>
                                <select :disabled="listado == 2 && actualizar == 0"
                                    v-model="datosRenta.tipo_arrendatario" class="form-control"
                                >
                                    <option value="0">Persona Física</option>
                                    <option value="1">Persona Moral</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="">Nombre
                                    <span style="color:red;" v-show="
                                            datosRenta.nombre_arrendatario == ''">
                                        (*)
                                    </span>
                                </label>
                                <input type="text" v-model="datosRenta.nombre_arrendatario"
                                    :disabled="listado == 2 && actualizar == 0"
                                    class="form-control" placeholder="Nombre"
                                />
                            </div>
                        </div>
                        <div class="col-md-2"></div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Dirección
                                    <span style="color:red;"
                                        v-show="datosRenta.dir_arrendatario == ''"
                                        >(*)
                                    </span>
                                </label>
                                <input type="text"
                                    :disabled="listado == 2 && actualizar == 0"
                                    class="form-control" placeholder="Dirección"
                                    v-model="datosRenta.dir_arrendatario"
                                />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">CP
                                    <span style="color:red;"
                                        v-show="datosRenta.cp_arrendatario == ''"
                                        >(*)
                                    </span>
                                </label>
                                <input
                                    type="text"
                                    :disabled="listado == 2 && actualizar == 0"
                                    @keyup="
                                        selectColonias(
                                            datosRenta.cp_arrendatario,
                                            0
                                        ),
                                            (datosRenta.col_arrendatario = '')
                                    "
                                    class="form-control"
                                    v-model="datosRenta.cp_arrendatario"
                                    placeholder="Código Postal"
                                    maxlength="5"
                                    pattern="\d*"
                                    v-on:keypress="$root.isNumber($event)"
                                />
                            </div>
                        </div>
                        <div class="col-md-3"></div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">
                                    Colonia<span
                                        style="color:red;"
                                        v-show="
                                            datosRenta.col_arrendatario == ''
                                        "
                                        >(*)</span
                                    >
                                </label>
                                <input
                                    type="text"
                                    name="city3"
                                    :disabled="listado == 2 && actualizar == 0"
                                    list="cityname3"
                                    class="form-control"
                                    v-model="datosRenta.col_arrendatario"
                                />
                                <datalist id="cityname3">
                                    <option value="">Seleccione</option>
                                    <option
                                        v-for="colonias in arrayColonias"
                                        :key="colonias.colonia"
                                        :value="colonias.colonia"
                                        v-text="colonias.colonia"
                                    ></option>
                                </datalist>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">
                                    Municipio<span
                                        style="color:red;"
                                        v-show="
                                            datosRenta.municipio_arrendatario ==
                                                ''
                                        "
                                        >(*)</span
                                    >
                                </label>
                                <input
                                    type="text"
                                    :disabled="listado == 2 && actualizar == 0"
                                    class="form-control"
                                    v-model="datosRenta.municipio_arrendatario"
                                    placeholder="Municipio"
                                />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">
                                    Estado<span
                                        style="color:red;"
                                        v-show="
                                            datosRenta.estado_arrendatario == ''
                                        "
                                        >(*)</span
                                    >
                                </label>
                                <input
                                    type="text"
                                    :disabled="listado == 2 && actualizar == 0"
                                    class="form-control"
                                    v-model="datosRenta.estado_arrendatario"
                                    placeholder="Colonia"
                                />
                            </div>
                        </div>
                        <!-- Datos de arrendatario moral -->
                        <template v-if="datosRenta.tipo_arrendatario == 1">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">
                                        RFC<span
                                            style="color:red;"
                                            v-show="
                                                datosRenta.rfc_arrendatario ==
                                                    ''
                                            "
                                            >(*)</span
                                        >
                                    </label>
                                    <input
                                        type="text"
                                        :disabled="
                                            listado == 2 && actualizar == 0
                                        "
                                        maxlength="13"
                                        class="form-control"
                                        v-model="datosRenta.rfc_arrendatario"
                                        placeholder="RFC"
                                    />
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label for="">
                                        Representante<span
                                            style="color:red;"
                                            v-show="
                                                datosRenta.representante_arrendatario ==
                                                    ''
                                            "
                                            >(*)</span
                                        >
                                    </label>
                                    <input
                                        type="text"
                                        :disabled="
                                            listado == 2 && actualizar == 0
                                        "
                                        class="form-control"
                                        v-model="
                                            datosRenta.representante_arrendatario
                                        "
                                        placeholder="Representante legal"
                                    />
                                </div>
                            </div>
                            <div class="col-md-1"></div>
                        </template>

                        <div class="col-md-5">
                            <div class="form-group">
                                <label for=""
                                    >Celular
                                    <span
                                        style="color:red;"
                                        v-show="
                                            datosRenta.tel_arrendatario == ''
                                        "
                                        >(*)</span
                                    >
                                </label>
                                <div class="input-group">
                                    <select
                                        :disabled="
                                            listado == 2 && actualizar == 0
                                        "
                                        v-model="datosRenta.clv_lada_arr"
                                        class="form-control col-md-5"
                                    >
                                        <option value="">Clave lada</option>
                                        <option
                                            v-for="clave in arrayClaves"
                                            :key="clave.clave + clave.pais"
                                            :value="clave.clave"
                                            v-text="
                                                clave.pais + ' +' + clave.clave
                                            "
                                        ></option>
                                    </select>
                                    <input
                                        type="text"
                                        :disabled="
                                            listado == 2 && actualizar == 0
                                        "
                                        pattern="\d*"
                                        maxlength="10"
                                        class="form-control col-md-7"
                                        v-on:keypress="$root.isNumber($event)"
                                        v-model="datosRenta.tel_arrendatario"
                                        placeholder="Celular"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Fin datos prospecto-->
                </div>
            </div>
            <!--Datos Fiador-->
            <div class="card mb-0">
                <div class="card-header" id="headingTwo" role="tab">
                    <h5 class="mb-0">
                        <a
                            data-toggle="collapse"
                            href="#collapseTwo"
                            aria-expanded="false"
                            aria-controls="collapseTwo"
                            class="collapsed"
                            >Fiador
                        </a>
                    </h5>
                </div>
                <div
                    class="collapse"
                    id="collapseTwo"
                    role="tabpanel"
                    aria-labelledby="headingTwo"
                    data-parent="#accordion"
                >
                    <div
                        class="form-group row border border-primary"
                        style="margin-right:0px; margin-left:0px;"
                    >
                        <div class="col-md-12">
                            <div class="form-group">
                                <center><h3></h3></center>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">
                                    Tipo<span
                                        style="color:red;"
                                        v-show="datosRenta.tipo_aval == ''"
                                        >(*)</span
                                    >
                                </label>
                                <select
                                    :disabled="listado == 2 && actualizar == 0"
                                    v-model="datosRenta.tipo_aval"
                                    class="form-control"
                                >
                                    <option value="0">Persona Física</option>
                                    <option value="1">Persona Moral</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="">
                                    Nombre<span
                                        style="color:red;"
                                        v-show="datosRenta.nombre_aval == ''"
                                        >(*)</span
                                    >
                                </label>
                                <input
                                    type="text"
                                    :disabled="listado == 2 && actualizar == 0"
                                    class="form-control"
                                    v-model="datosRenta.nombre_aval"
                                    placeholder="Nombre"
                                />
                            </div>
                        </div>
                        <div class="col-md-2"></div>
                        <!-- Datos de arrendatario moral -->
                        <template v-if="datosRenta.tipo_aval == 1">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">
                                        Representante<span
                                            style="color:red;"
                                            v-show="
                                                datosRenta.representante_aval ==
                                                    ''
                                            "
                                            >(*)</span
                                        >
                                    </label>
                                    <input
                                        type="text"
                                        :disabled="
                                            listado == 2 && actualizar == 0
                                        "
                                        class="form-control"
                                        v-model="datosRenta.representante_aval"
                                        placeholder="Representante legal"
                                    />
                                </div>
                            </div>
                        </template>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">
                                    Dirección<span
                                        style="color:red;"
                                        v-show="datosRenta.dir_aval == ''"
                                        >(*)</span
                                    >
                                </label>
                                <input
                                    type="text"
                                    :disabled="listado == 2 && actualizar == 0"
                                    class="form-control"
                                    v-model="datosRenta.dir_aval"
                                    placeholder="Dirección"
                                />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">
                                    CP<span
                                        style="color:red;"
                                        v-show="datosRenta.cp_aval == ''"
                                        >(*)</span
                                    >
                                </label>
                                <input
                                    type="text"
                                    :disabled="listado == 2 && actualizar == 0"
                                    @keyup="
                                        selectColonias(datosRenta.cp_aval, 1),
                                            (datosRenta.col_aval = '')
                                    "
                                    class="form-control"
                                    v-model="datosRenta.cp_aval"
                                    placeholder="Código Postal"
                                    maxlength="5"
                                    pattern="\d*"
                                    v-on:keypress="$root.isNumber($event)"
                                />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">
                                    Colonia<span
                                        style="color:red;"
                                        v-show="datosRenta.col_aval == ''"
                                        >(*)</span
                                    >
                                </label>
                                <input
                                    type="text"
                                    name="city3"
                                    :disabled="listado == 2 && actualizar == 0"
                                    list="cityname3"
                                    class="form-control"
                                    v-model="datosRenta.col_aval"
                                />
                                <datalist id="cityname3">
                                    <option value="">Seleccione</option>
                                    <option
                                        v-for="colonias in arrayColonias"
                                        :key="colonias.colonia"
                                        :value="colonias.colonia"
                                        v-text="colonias.colonia"
                                    ></option>
                                </datalist>
                            </div>
                        </div>
                        <div class="col-md-3"></div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">
                                    Municipio<span
                                        style="color:red;"
                                        v-show="datosRenta.municipio_aval == ''"
                                        >(*)</span
                                    >
                                </label>
                                <input
                                    type="text"
                                    :disabled="listado == 2 && actualizar == 0"
                                    class="form-control"
                                    v-model="datosRenta.municipio_aval"
                                    placeholder="Municipio"
                                />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">
                                    Estado<span
                                        style="color:red;"
                                        v-show="datosRenta.estado_aval == ''"
                                        >(*)</span
                                    >
                                </label>
                                <input
                                    type="text"
                                    :disabled="listado == 2 && actualizar == 0"
                                    class="form-control"
                                    v-model="datosRenta.estado_aval"
                                    placeholder="Colonia"
                                />
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group">
                                <label for=""
                                    >Celular
                                    <span
                                        style="color:red;"
                                        v-show="datosRenta.tel_aval == ''"
                                        >(*)</span
                                    >
                                </label>
                                <div class="input-group">
                                    <select
                                        :disabled="
                                            listado == 2 && actualizar == 0
                                        "
                                        v-model="datosRenta.clv_lada_aval"
                                        class="form-control col-md-5"
                                    >
                                        <option value="">Clave lada</option>
                                        <option
                                            v-for="clave in arrayClaves"
                                            :key="clave.clave + clave.pais"
                                            :value="clave.clave"
                                            v-text="
                                                clave.pais + ' +' + clave.clave
                                            "
                                        ></option>
                                    </select>
                                    <input
                                        type="text"
                                        :disabled="
                                            listado == 2 && actualizar == 0
                                        "
                                        pattern="\d*"
                                        maxlength="10"
                                        class="form-control col-md-7"
                                        v-on:keypress="$root.isNumber($event)"
                                        v-model="datosRenta.tel_aval"
                                        placeholder="Celular"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Fin datos prospecto-->
                </div>
            </div>
            <!--Datos Testigo-->
            <div class="card mb-0">
                <div class="card-header" id="headingThree" role="tab">
                    <h5 class="mb-0">
                        <a
                            data-toggle="collapse"
                            href="#collapseThree"
                            aria-expanded="false"
                            aria-controls="collapseThree"
                            class="collapsed"
                            >Testigo
                        </a>
                    </h5>
                </div>
                <div
                    class="collapse"
                    id="collapseThree"
                    role="tabpanel"
                    aria-labelledby="headingThree"
                    data-parent="#accordion"
                >
                    <div
                        class="form-group row border border-primary"
                        style="margin-right:0px; margin-left:0px;"
                    >
                        <div class="col-md-12">
                            <div class="form-group">
                                <center><h3></h3></center>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="">
                                    Nombre<span
                                        style="color:red;"
                                        v-show="datosRenta.nombre == ''"
                                        >(*)</span
                                    >
                                </label>
                                <input
                                    type="text"
                                    :disabled="listado == 2 && actualizar == 0"
                                    class="form-control"
                                    v-model="datosRenta.nombre"
                                    placeholder="Nombre"
                                />
                            </div>
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                    <!-- Fin datos prospecto-->
                </div>
            </div>
            <!--Datos Renta-->
            <div class="card mb-0">
                <div class="card-header" id="headingFour" role="tab">
                    <h5 class="mb-0">
                        <a
                            data-toggle="collapse"
                            href="#collapseFour"
                            aria-expanded="false"
                            aria-controls="collapseFour"
                            class="collapsed"
                            >Datos de la Renta
                        </a>
                    </h5>
                </div>
                <!--Datos Renta-->
                <div
                    class="collapse"
                    id="collapseFour"
                    role="tabpanel"
                    aria-labelledby="headingFour"
                    data-parent="#accordion"
                >
                    <div
                        class="form-group row border border-primary"
                        style="margin-right:0px; margin-left:0px;"
                    >
                        <div class="col-md-12">
                            <div class="form-group">
                                <center><h3></h3></center>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="">
                                    Fraccionamiento
                                </label>
                                <select
                                    v-if="listado == 3"
                                    class="form-control"
                                    v-model="datosRenta.fraccionamiento_id"
                                    @change="
                                        selectEtapaDisp(
                                            datosRenta.fraccionamiento_id
                                        )
                                    "
                                >
                                    <option value=""> Seleccione </option>
                                    <option
                                        v-for="proyecto in arrayProyectosInv"
                                        :key="proyecto.id"
                                        :value="proyecto.id"
                                        v-text="proyecto.nombre"
                                    ></option>
                                </select>
                                <input
                                    type="text"
                                    disabled
                                    v-else
                                    class="form-control"
                                    v-model="datosRenta.proyecto"
                                    placeholder="Proyecto"
                                />
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="">
                                    Etapa
                                </label>
                                <select
                                    v-if="listado == 3"
                                    class="form-control"
                                    v-model="datosRenta.etapa_id"
                                    @change="
                                        getRentasDisponibles(
                                            datosRenta.etapa_id,
                                            datosRenta.fraccionamiento_id
                                        )
                                    "
                                >
                                    <option value=""> Seleccione </option>
                                    <option
                                        v-for="etapa in arrayEtapasInv"
                                        :key="etapa.id"
                                        :value="etapa.id"
                                        v-text="etapa.num_etapa"
                                    ></option>
                                </select>
                                <input
                                    type="text"
                                    disabled
                                    v-else
                                    class="form-control"
                                    v-model="datosRenta.etapa"
                                    placeholder="Etapa"
                                />
                            </div>
                        </div>
                        <div class="col-md-2"></div>

                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="">
                                    Casa
                                </label>
                                <select
                                    v-if="listado == 3"
                                    class="form-control"
                                    v-model="datosRenta.lote_id"
                                    @change="getDatosLoteRenta()"
                                >
                                    <option value=""> Seleccione </option>
                                    <template v-for="lote in arrayDisponibles">
                                        <option
                                            v-if="lote.interior == null"
                                            :key="lote.id"
                                            :value="lote.id"
                                            v-text="
                                                lote.calle +
                                                    ' No.' +
                                                    lote.numero
                                            "
                                        ></option>
                                        <option
                                            v-else
                                            :key="lote.id"
                                            :value="lote.id"
                                            v-text="
                                                lote.calle +
                                                    ' Num. ' +
                                                    lote.numero +
                                                    ' ' +
                                                    lote.interior
                                            "
                                        ></option>
                                    </template>
                                </select>
                                <input
                                    type="text"
                                    disabled
                                    v-else
                                    class="form-control"
                                    :value="
                                        datosRenta.calle +
                                            ' Num.' +
                                            datosRenta.numero +
                                            ' ' +
                                            datosRenta.interior
                                    "
                                    placeholder="Etapa"
                                />
                            </div>
                        </div>
                        <div class="col-md-5"></div>

                        <template v-if="datosRenta.lote_id != ''">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Modelo</label>
                                    <input
                                        type="text"
                                        disabled
                                        class="form-control"
                                        v-model="datosRenta.modelo"
                                        placeholder="Modelo"
                                    />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Precio de renta</label>
                                    <h6
                                        style="color:blue;"
                                        v-text="
                                            '$' +
                                                $root.formatNumber(
                                                    datosRenta.monto_renta
                                                )
                                        "
                                    ></h6>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Vigencia del contrato</label>
                                    <select
                                        :disabled="listado == 2"
                                        @change="calcularFechaFin()"
                                        class="form-control"
                                        v-model="datosRenta.num_meses"
                                    >
                                        <option value="0"> Seleccione </option>
                                        <option value="1"> 1 mes </option>
                                        <option value="2"> 2 meses </option>
                                        <option value="3"> 3 meses </option>
                                        <option value="4"> 4 meses </option>
                                        <option value="5"> 5 meses </option>
                                        <option value="6"> 6 meses </option>
                                        <option value="7"> 7 meses </option>
                                        <option value="8"> 8 meses </option>
                                        <option value="9"> 9 meses </option>
                                        <option value="10"> 10 meses </option>
                                        <option value="11"> 11 meses </option>
                                        <option value="12"> 12 meses </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="">Déposito de Garantia</label>
                                    <input
                                        type="text"
                                        v-if="listado == 3"
                                        :disabled="
                                            listado == 2 && actualizar == 0
                                        "
                                        class="form-control"
                                        v-model="datosRenta.dep_garantia"
                                        placeholder="Deposito de garantia"
                                        v-on:keypress="$root.isNumber($event)"
                                    />
                                    <h6
                                        style="color:blue;"
                                        v-text="
                                            '$' +
                                                $root.formatNumber(
                                                    datosRenta.dep_garantia
                                                )
                                        "
                                    ></h6>
                                </div>
                            </div>
                            <div class="col-md-7"></div>

                            <!-- MUEBLES -->
                            <div class="col-md-3">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <button
                                                type="button"
                                                v-if="datosRenta.muebles == 1"
                                                class="btn btn-success"
                                                :disabled="
                                                    listado == 2 &&
                                                        actualizar == 0
                                                "
                                                @click="
                                                    (datosRenta.muebles = 0),
                                                        (adendum = 0)
                                                "
                                            >
                                                Si
                                            </button>
                                            <button
                                                type="button"
                                                v-if="datosRenta.muebles == 0"
                                                class="btn btn-primary"
                                                :disabled="
                                                    listado == 2 &&
                                                        actualizar == 0
                                                "
                                                @click="
                                                    (datosRenta.muebles = 1),
                                                        (adendum = 0)
                                                "
                                            >
                                                No
                                            </button>
                                        </div>
                                    </div>
                                    <input
                                        type="text"
                                        class="form-control"
                                        disabled
                                        placeholder="¿Muebles?"
                                    />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div
                                    class="input-group mb-3"
                                    v-if="datosRenta.muebles == 0"
                                >
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <button
                                                type="button"
                                                v-if="datosRenta.adendum == 1"
                                                class="btn btn-success"
                                                :disabled="
                                                    listado == 2 &&
                                                        actualizar == 0
                                                "
                                                @click="datosRenta.adendum = 0"
                                            >
                                                Si
                                            </button>
                                            <button
                                                type="button"
                                                v-if="datosRenta.adendum == 0"
                                                class="btn btn-primary"
                                                :disabled="
                                                    listado == 2 &&
                                                        actualizar == 0
                                                "
                                                @click="datosRenta.adendum = 1"
                                            >
                                                No
                                            </button>
                                        </div>
                                    </div>
                                    <input
                                        type="text"
                                        class="form-control"
                                        disabled
                                        placeholder="Adendum?"
                                    />
                                </div>
                            </div>
                            <div class="col-md-6"></div>

                            <!-- SERVICIOS -->
                            <div class="col-md-3">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <button
                                                type="button"
                                                v-if="datosRenta.servicios == 1"
                                                class="btn btn-success"
                                                :disabled="
                                                    listado == 2 &&
                                                        actualizar == 0
                                                "
                                                @click="
                                                    datosRenta.servicios = 0
                                                "
                                            >
                                                Si
                                            </button>
                                            <button
                                                type="button"
                                                v-if="datosRenta.servicios == 0"
                                                class="btn btn-primary"
                                                :disabled="
                                                    listado == 2 &&
                                                        actualizar == 0
                                                "
                                                @click="
                                                    (datosRenta.servicios = 1),
                                                        limpiarServicios()
                                                "
                                            >
                                                No
                                            </button>
                                        </div>
                                    </div>
                                    <input
                                        type="text"
                                        class="form-control"
                                        disabled
                                        placeholder="¿Servicios?"
                                    />
                                </div>
                            </div>
                            <div class="col-md-9"></div>

                            <template v-if="datosRenta.servicios == 1">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">Luz</label>
                                        <input
                                            type="text"
                                            v-if="listado == 3"
                                            :disabled="
                                                listado == 2 && actualizar == 0
                                            "
                                            class="form-control"
                                            v-model="datosRenta.luz"
                                            placeholder="Deposito de garantia"
                                            v-on:keypress="$root.isNumber($event)"
                                        />
                                        <h6
                                            style="color:blue;"
                                            v-text="
                                                '$' +
                                                    $root.formatNumber(datosRenta.luz)
                                            "
                                        ></h6>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">Agua</label>
                                        <input
                                            type="text"
                                            v-if="listado == 3"
                                            :disabled="
                                                listado == 2 && actualizar == 0
                                            "
                                            class="form-control"
                                            v-model="datosRenta.agua"
                                            placeholder="Deposito de garantia"
                                            v-on:keypress="$root.isNumber($event)"
                                        />
                                        <h6
                                            style="color:blue;"
                                            v-text="
                                                '$' +
                                                    $root.formatNumber(
                                                        datosRenta.agua
                                                    )
                                            "
                                        ></h6>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">Gas</label>
                                        <input
                                            type="text"
                                            v-if="listado == 3"
                                            :disabled="
                                                listado == 2 && actualizar == 0
                                            "
                                            class="form-control"
                                            v-model="datosRenta.gas"
                                            placeholder="Deposito de garantia"
                                            v-on:keypress="$root.isNumber($event)"
                                        />
                                        <h6
                                            style="color:blue;"
                                            v-text="
                                                '$' +
                                                    $root.formatNumber(datosRenta.gas)
                                            "
                                        ></h6>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">Televisión</label>
                                        <input
                                            type="text"
                                            v-if="listado == 3"
                                            :disabled="
                                                listado == 2 && actualizar == 0
                                            "
                                            class="form-control"
                                            v-model="datosRenta.television"
                                            placeholder="Deposito de garantia"
                                            v-on:keypress="$root.isNumber($event)"
                                        />
                                        <h6
                                            style="color:blue;"
                                            v-text="
                                                '$' +
                                                    $root.formatNumber(
                                                        datosRenta.television
                                                    )
                                            "
                                        ></h6>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">Telefonía</label>
                                        <input
                                            type="text"
                                            v-if="listado == 3"
                                            :disabled="
                                                listado == 2 && actualizar == 0
                                            "
                                            class="form-control"
                                            v-model="datosRenta.telefonia"
                                            placeholder="Deposito de garantia"
                                            v-on:keypress="$root.isNumber($event)"
                                        />
                                        <h6
                                            style="color:blue;"
                                            v-text="
                                                '$' +
                                                    $root.formatNumber(
                                                        datosRenta.telefonia
                                                    )
                                            "
                                        ></h6>
                                    </div>
                                </div>
                            </template>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Fecha de inicio</label>
                                    <input
                                        type="date"
                                        :disabled="listado == 2 && actualizar == 0"
                                        class="form-control"
                                        v-model="datosRenta.fecha_ini"
                                        placeholder="Fecha de inicio"
                                        @change="calcularFechaFin()"
                                    />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Fecha de finalización</label>
                                    <input
                                        type="date"
                                        disabled
                                        class="form-control"
                                        v-model="datosRenta.fecha_fin"
                                        placeholder="Fecha de inicio"
                                    />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div
                                    class="form-group row"
                                    v-if="datosRenta.pagares.length"
                                >
                                    <div class="table-responsive col-md-12">
                                        <table
                                            class="table table-bordered table-striped table-sm"
                                        >
                                            <thead>
                                                <tr>
                                                    <th
                                                        colspan="3"
                                                        class="text-center th2"
                                                        style="color:white; background:#252f35"
                                                    >
                                                        PAGOS
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th># Pago</th>
                                                    <th>Fecha de pago</th>
                                                    <th>Monto</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                    v-for="pago in datosRenta.pagares"
                                                    :key="pago.id"
                                                >
                                                    <td
                                                        v-text="
                                                            'Pago no. ' +
                                                                pago.num_pago
                                                        "
                                                    ></td>
                                                    <td v-if="listado == 2 && actualizar == 1">
                                                        <input type="date" class="form-control"
                                                            v-model="pago.fecha"
                                                        >
                                                    </td>
                                                    <td v-else
                                                        v-text="
                                                            this.moment(
                                                                pago.fecha
                                                            )
                                                                .locale('es')
                                                                .format(
                                                                    'DD/MMM/YYYY'
                                                                )
                                                        "
                                                    ></td>
                                                    <td
                                                        v-text="
                                                            '$' +
                                                                $root.formatNumber(
                                                                    pago.importe
                                                                )
                                                        "
                                                    ></td>
                                                    <!-- <td v-if="listado == 2 && actualizar == 1">
                                                        <button class="btn btn-danger" title="Eliminar"
                                                            @click="eliminarPago(pago.id)"
                                                        >
                                                            <i class="icon-close"></i>
                                                        </button>
                                                    </td> -->
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Fecha de firma</label>
                                    <input
                                        type="date"
                                        :disabled="
                                            listado == 2 && actualizar == 0
                                        "
                                        class="form-control"
                                        v-model="datosRenta.fecha_firma"
                                        placeholder="Fecha de firma"
                                    />
                                </div>
                            </div>
                        </template>
                    </div>
                    <!-- Fin datos prospecto-->
                </div>
            </div>

            <!--- Botones y div para errores -->
            <div class="card-body">
                <div class="form-group">
                    <div class="col-md-12">
                        <!-- Div para mostrar los errores que mande validerFraccionamiento -->
                        <div
                            v-show="errorContrato"
                            class="form-group row div-error"
                        >
                            <div class="text-center text-error">
                                <div
                                    v-for="error in errorMostrarMsjContrato"
                                    :key="error"
                                    v-text="error"
                                ></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            @click="$emit('salir')"
                        >
                            Cerrar
                        </button>
                        <template v-if="datosRenta.diff < 0 && datosRenta.status == 2">

                        </template>
                        <template v-else>
                            <button
                                type="button"
                                v-if="listado == 3"
                                class="btn btn-primary"
                                @click="storeRenta()"
                            >
                                Enviar
                            </button>
                            <button
                                type="button"
                                v-if="listado == 2 && actualizar == 1"
                                class="btn btn-primary"
                                @click="updateRenta()"
                            >
                                Actualizar
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            <div class="form-group" v-if="listado == 2 && actualizar == 0">
                <div class="col-md-12">
                    <div style="text-align: right;">
                        <button
                            type="button"
                            class="btn btn-primary"
                            @click="$emit('modal', 1)"
                        >
                            Imprimir contrato
                        </button>

                        <button
                            type="button"
                            class="btn btn-warning"
                            @click="$emit('modal', 2)"
                        >
                            Depósito de Garantia
                        </button>
                        <a
                            class="btn btn-scarlet btn-sm"
                            target="_blank"
                            v-bind:href="
                                '/rentas/printPagares?id=' + datosRenta.id
                            "
                            >Imprimir pagares</a
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props:{
        datosRenta:{
            type: Object,
            require:true
        },
        listado:{
            type: Number,
            require: true,
            default: 3
        },
        actualizar:{
            type: Number,
            require: false,
            default: 0
        }
    },
    data() {
        return {
            arrayColonias: [],
            arrayProyectosInv: [],
            arrayEtapasInv: [],
            arrayDisponibles: [],
            errorMostrarMsjContrato: [],
            arrayClaves: [],
            errorContrato: 0,
        }
    },
    methods: {
        selectColonias(cp,aval){
            let me = this;
            me.arrayColonias=[];
            var url = '/select_colonias?buscar=' + cp;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayColonias = respuesta.colonias;
                if(me.arrayClaves.length>0){
                    if(aval == 0){
                        me.datosRenta.col_arrendatario = me.arrayColonias[0].colonia;
                        me.datosRenta.municipio_arrendatario = me.arrayColonias[0].ciudad;
                        me.datosRenta.estado_arrendatario = me.arrayColonias[0].estado;
                    }
                    else{
                        me.datosRenta.col_aval = me.arrayColonias[0].colonia;
                        me.datosRenta.municipio_aval = me.arrayColonias[0].ciudad;
                        me.datosRenta.estado_aval = me.arrayColonias[0].estado;
                    }

                }
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        selectFraccionamientosInventario(){
            let me = this;
            me.arrayProyectosInv=[];
            var url = '/selectFraccionamientoConInventario?renta=1';
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayProyectosInv = respuesta.fraccionamientos;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        selectEtapaDisp(buscar){
            let me = this;
            me.arrayEtapasInv=[];
            var url = '/selectEtapaDisp?buscar=' + buscar;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayEtapasInv = respuesta.etapas;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        getRentasDisponibles(etapa, fraccionamiento){
            let me = this;
            me.arrayDisponibles=[];
            var url = '/lotes/getRentasDisponibles?etapa_id=' + etapa + '&fraccionamiento_id=' + fraccionamiento;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayDisponibles = respuesta;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        getDatosLoteRenta(){
            let me = this;
            me.datosRenta.modelo = '';
            me.datosRenta.monto_renta = 0;
            var url = '/lotes/getDatosLoteRenta?id=' + me.datosRenta.lote_id;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.datosRenta.modelo = respuesta.modelo;
                me.datosRenta.monto_renta = respuesta.precio_renta;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        calcularFechaFin(){
            let me = this;

            if( me.datosRenta.fecha_ini != ''){
                let fecha_fin = new Date(
                    me.datosRenta.fecha_ini.substring(5,7)
                    +'/'+me.datosRenta.fecha_ini.substring(8,10)
                    +'/'+me.datosRenta.fecha_ini.substring(0, 4)
                );
                let mes, dia, anio = ''
                dia = fecha_fin.getDate();
                mes = parseInt(fecha_fin.getMonth()+1);
                anio = fecha_fin.getFullYear();

                if(me.listado == 3){
                    me.datosRenta.pagares = [];
                    for(let i = 1; i<=me.datosRenta.num_meses; i++){
                        var auxMes = mes + i - 1;
                        var auxAnio = anio;
                        if( auxMes > 12)
                        {
                            auxAnio = auxAnio + 1;
                            auxMes = auxMes - 12;
                        }
                        if(auxMes < 10)
                            auxMes = '0'+(auxMes);

                        me.datosRenta.pagares.push({
                            id:i,
                            num_pago:i,
                            fecha: auxAnio+'-'+auxMes+'-01',
                            importe: me.datosRenta.monto_renta
                        });
                    }
                }

                if(dia == 1){
                    dia = 30
                    mes = (mes + parseInt(me.datosRenta.num_meses)-1);
                }
                else {
                    mes = (mes + parseInt(me.datosRenta.num_meses));
                    dia = dia -1;
                }


                if( mes > 12)
                {
                    anio = anio + 1;
                    mes = mes - 12;
                }
                if(mes == 2 && dia > 28)
                    dia = 28;
                if(mes < 10)
                    mes = '0'+(mes);
                if(dia < 10)
                    dia = '0'+dia;

                if(mes == '01' || mes == '03' || mes == '05' || mes == '07' || mes == '08' || mes == '10' || mes == '12')
                    if(dia == 30)
                        dia = 31;

                me.datosRenta.fecha_fin = anio +'-'+ mes +'-'+ dia;
            }
        },
        // eliminarPago(id){
        //     let me = this;
        //         me.datosRenta.pagares = me.datosRenta.pagares.filter( a => a.id !== id)
        // },
        limpiarServicios(){
            let me = this;
            me.datosRenta.luz = 0;
            me.datosRenta.agua = 0;
            me.datosRenta.gas = 0;
            me.datosRenta.television = 0;
            me.datosRenta.telefonia = 0;
        },
        validarRegistro(){
            this.errorContrato=0;
            this.errorMostrarMsjContrato=[];

            if(this.datosRenta.lote_id == '')
                this.errorMostrarMsjContrato.push("Seleccionar propiedad a rentar");

            if(this.datosRenta.num_meses == 0 || this.datosRenta.fecha_ini == '')
                this.errorMostrarMsjContrato.push("Completar formulario de renta");

            if(this.datosRenta.nombre_arrendatario == '' || this.datosRenta.dir_arrendatario == '' ||
                this.datosRenta.col_arrendatario == '' || this.datosRenta.tel_arrendatario == ''
            )this.errorMostrarMsjContrato.push("Completar formulario de arrendatario");

            if(this.datosRenta.nombre_aval == '' || this.datosRenta.dir_aval == '' ||
                this.datosRenta.col_aval == '' || this.datosRenta.tel_aval == ''
            )this.errorMostrarMsjContrato.push("Completar formulario de fiador");

            if(this.errorMostrarMsjContrato.length)//Si el mensaje tiene almacenado algo en el array
                this.errorContrato = 1;

            return this.errorContrato;
        },
        storeRenta(){
            let me = this;
            if(this.validarRegistro()) //Se verifica si hay un error (campo vacio)
            {
                return;
            }
            //Con axios se llama el metodo store de FraccionaminetoController
            axios.post('/rentas/storeRenta',{
                'datosRenta' : this.datosRenta,
            }).then(function (response){
                me.$emit('salir')
                //Se muestra mensaje Success
                swal({
                    position: 'top-end',
                    type: 'success',
                    title: 'Renta creada correctamente',
                    showConfirmButton: false,
                    timer: 1500
                    })
            }).catch(function (error){
                console.log(error);
            });
        },
        updateRenta(){
            let me = this;
            if(this.validarRegistro()) //Se verifica si hay un error (campo vacio)
            {
                return;
            }
            //Con axios se llama el metodo store de FraccionaminetoController
            axios.put('/rentas/updateRenta',{
                'datosRenta' : this.datosRenta,
            }).then(function (response){
                me.$emit('salir')
                //Se muestra mensaje Success
                swal({
                    position: 'top-end',
                    type: 'success',
                    title: 'Renta actualizada correctamente',
                    showConfirmButton: false,
                    timer: 1500
                    })
            }).catch(function (error){
                console.log(error);
            });
        },
        getClavesLadas(){
            let me = this;
            me.arrayClaves=[];
            var url = '/getClavesLadas';
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayClaves = respuesta.claves;
            })
            .catch(function (error) {
                console.log(error);
            });

        },

    },
    mounted() {
        this.selectFraccionamientosInventario();
        this.getClavesLadas();
    },
};
</script>
<style scoped>
    .div-error {
        display: flex;
        justify-content: center;
    }
    .text-error {
        color: red !important;
        font-weight: bold;
    }
</style>
