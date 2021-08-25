@extends('principal')
@section('contenido')


    @if(Auth::check())

    <template v-if="menu==0">
        <calendar-component rol-id="{{Auth::user()->rol_id}}"></calendar-component>
    </template>

    

        <template v-if="menu==20">
            <perfil-user user-id="{{Auth::user()->id}}"
                rol-id="{{Auth::user()->rol_id}}"
            ></perfil-user>
        </template>
        <template v-if="menu==100">
            <perfil-user user-id="{{Auth::user()->id}}"
                rol-id="{{Auth::user()->rol_id}}"
             ></perfil-user>
        </template>
        <template v-if="menu==101">
            <listar-notifications user-id="{{Auth::user()->id}}"></listar-notifications>
        </template>
        <template v-if="menu==90">
            <datos-extra></datos-extra>
        </template>
        <template v-if="menu==91">
            <res-proyecto rol-id="{{Auth::user()->rol_id}}"></res-proyecto>
        </template>

        @if(Auth::user()->fraccionamiento == 1)
            <template v-if="menu==1">
                <fraccionamiento rol-id="{{Auth::user()->rol_id}}"></fraccionamiento>
            </template>
        @endif

        @if(Auth::user()->mis_asesores == 1)
            <template v-if="menu==73">
                <asesores></asesores>
            </template>
        @endif

        @if(Auth::user()->etapas == 1)
            <template v-if="menu==2">
                <etapa rol-id="{{Auth::user()->rol_id}}"></etapa>
            </template>
        @endif

        @if(Auth::user()->modelos == 1)
            <template v-if="menu==3">
                <modelo rol-id="{{Auth::user()->rol_id}}"></modelo>
            </template>
        @endif

        @if(Auth::user()->lotes == 1)
            <template v-if="menu==4">
                <lote></lote>
            </template>
        @endif

        @if(Auth::user()->asign_modelos == 1)
            <template v-if="menu==5">
                <asignar-modelo></asignar-modelo>
            </template>
        @endif

        @if(Auth::user()->licencias == 1)
            <template v-if="menu==6">
                <licencias rol-id="{{Auth::user()->rol_id}}"></licencias>
            </template>
        @endif

        @if(Auth::user()->acta_terminacion == 1)
            <template v-if="menu==7">
                <actadeterminacion></actadeterminacion>
            </template>
        @endif

        @if(Auth::user()->cuenta == 1)
            <template v-if="menu==8">
                <cuenta></cuenta>
            </template>
        @endif

        @if(Auth::user()->notaria == 1)
        <template v-if="menu==9">
            <notarias></notarias>
        </template>
        @endif

        

        @if(Auth::user()->departamentos == 1)
            <template v-if="menu==11">
                <departamento></departamento>
            </template>
        @endif

        @if(Auth::user()->personas == 1)
            <template v-if="menu==12">
                <personal></personal>
            </template>
        @endif

        @if(Auth::user()->empresas == 1)
            <template v-if="menu==13">
                <empresa></empresa>
            </template>
        @endif

        @if(Auth::user()->medios_public == 1)
            <template v-if="menu==14">
                <medio-publicitario></medio-publicitario>
            </template>
        @endif

        @if(Auth::user()->lugares_contacto == 1)
            <template v-if="menu==15">
                <lugar-contacto></lugar-contacto>
            </template>
        @endif

        @if(Auth::user()->inst_financiamiento == 1)
            <template v-if="menu==16">
                <institucion-financiamiento></institucion-financiamiento>
            </template>
        @endif

        @if(Auth::user()->tipos_credito == 1)
            <template v-if="menu==17">
                <tipo-credito></tipo-credito>
            </template>
        @endif

        @if(Auth::user()->servicios == 1)
            <template v-if="menu==18">
                <servicio></servicio>
            </template>
        @endif

        @if(Auth::user()->asig_servicios == 1)
            <template v-if="menu==19">
                <asignar-servicio></asignar-servicio>
            </template>
        @endif

        @if(Auth::user()->precios_etapas == 1)
            <template v-if="menu==21">
                <precio-etapa rol-id="{{Auth::user()->rol_id}}"></precio-etapa>
            </template>
        @endif

        @if(Auth::user()->sobreprecios == 1)
            <template v-if="menu==22">
                <sobreprecios></sobreprecios>
            </template>
        @endif

        @if(Auth::user()->paquetes == 1)
            <template v-if="menu==23">
                <paquetes></paquetes>
            </template>
        @endif

        @if(Auth::user()->promociones == 1)
            <template v-if="menu==24">
                <promociones></promociones>
            </template>
        @endif

        @if(Auth::user()->contratistas == 1)
            <template v-if="menu==50">
                <contratistas></contratistas>
            </template>
        @endif

        @if(Auth::user()->ini_obra == 1)
            <template v-if="menu==51">
                <iniobra></iniobra>
            </template>
        @endif

        @if(Auth::user()->partidas == 1)
            <template v-if="menu==52">
                <partidas></partidas>
            </template>
        @endif

        @if(Auth::user()->avance == 1)
            <template v-if="menu==53">
                <avance></avance>
            </template>
        @endif

        @if(Auth::user()->aviso_obra == 1)
            <template v-if="menu==54">
                <aviso-obra rol-id="{{Auth::user()->rol_id}}"></aviso-obra>
            </template>
        @endif

        @if(Auth::user()->avance == 1 || Auth::user()->rol_id == 9)
            <template v-if="menu==55">
                <visita-avaluo></visita-avaluo>
            </template>
        @endif

        @if(Auth::user()->roles == 1)
            <template v-if="menu==71">
                <rol></rol>
            </template>
        @endif

        @if(Auth::user()->usuarios == 1)
            <template v-if="menu==72">
                <usuario></usuario>
            </template>
        @endif

        @if(Auth::user()->lotes_disp == 1)
            <template v-if="menu==59">
                <lote-disponible rol-id="{{Auth::user()->rol_id}}" user-id="{{Auth::user()->id}}"></lote-disponible>
            </template>
        @endif

        @if(Auth::user()->mis_prospectos == 1)
            <template v-if="menu==60">
                <prospectos rol-id="{{Auth::user()->rol_id}}"></prospectos>
            </template>
        @endif
            
        @if(Auth::user()->simulacion_credito == 1)
            <template v-if="menu==61">
                <simulacion rol-id="{{Auth::user()->rol_id}}"></simulacion>
            </template>
        @endif
            
        @if(Auth::user()->hist_simulaciones == 1)
            <template v-if="menu==62">
                <historialsim rol-id="{{Auth::user()->rol_id}}"></historialsim>
            </template>
        @endif

        @if(Auth::user()->hist_creditos == 1)
            <template v-if="menu==63">
                <historialcreditos rol-id="{{Auth::user()->rol_id}}"></historialcreditos>
            </template>
        @endif

        @if(Auth::user()->contratos == 1)
            <template v-if="menu==80">
                <crear-contrato rol-id="{{Auth::user()->rol_id}}"><crear-contrato/>
            </template>
        @endif

        @if(Auth::user()->docs == 1)
            <template v-if="menu==208">
                <docs></docs>
            </template>
        @endif


        @if(Auth::user()->p_etapa == 1)
            <template v-if="menu==111">
                <publicidad-etapa></publicidad-etapa>
            </template>
        @endif

        @if(Auth::user()->p_fraccionamiento == 1)
            <template v-if="menu==112">
                <publicidad-fraccionamiento></publicidad-fraccionamiento>
            </template>
        @endif

        @if(Auth::user()->precios_viviendas == 1)
            <template v-if="menu==25">
                <precios-vivienda></precios-vivienda>
            </template>
        @endif

        @if(Auth::user()->agregar_sobreprecios == 1)
            <template v-if="menu==26">
                <agregar-sobreprecios></agregar-sobreprecios>
            </template>
        @endif

        <template v-if="menu==200">
            <depositos></depositos>
        </template>

        <template v-if="menu==201">
            <expediente></expediente>
        </template>
        <template v-if="menu==202">
            <asignargestor></asignargestor>
        </template>
        <template v-if="menu==203">
            <seguimiento-tramite rol-id="{{Auth::user()->rol_id}}"></seguimiento-tramite>
        </template>
        <template v-if="menu==204">
            <avaluos></avaluos>
        </template>
        <template v-if="menu==205">
            <gastos-admin></gastos-admin>
        </template>
        <template v-if="menu==206">
            <estado-cuenta></estado-cuenta>
        </template>
        <template v-if="menu==207">
            <cobro-credito></cobro-credito>
        </template>
        
        <template v-if="menu==1001">
            <facturacion></facturacion>
        </template>
        
        <template v-if="menu==1002">
            <precios-terrenos></precios-terrenos>
        </template>

        <template v-if="menu==209">
            <devolucion-cancelacion></devolucion-cancelacion>
        </template>

        <template v-if="menu==210">
            <devolucion-credito></devolucion-credito>
        </template>

        <template v-if="menu==211">
            <proveedores></proveedores>
        </template>

        <template v-if="menu==212">
            <solicitar-equipamiento></solicitar-equipamiento>
        </template>

        <template v-if="menu==213">
            <obra-equipamiento></obra-equipamiento>
        </template>

        <template v-if="menu==214">
            <proveedor-seguimiento></proveedor-seguimiento>
        </template>
       
        <template v-if="menu==215">
            <postventa-entrega></postventa-entrega>
        </template>

        <template v-if="menu==216">
            <obra-entrega></obra-entrega>
        </template>
     
        <template v-if="menu==217">
            <contratista-solicitud></contratista-solicitud>
        </template>

        <template v-if="menu==218">
            <postventa-etapa></postventa-etapa>
        </template>

        <template v-if="menu==219">
            <solicitud-detalles></solicitud-detalles>
        </template>

        <template v-if="menu==220">
            <revision-previa></revision-previa>
        </template>

        <template v-if="menu==221">
            <detalles-generales></detalles-generales>
        </template>

        <template v-if="menu==222">
            <catalogo-detalles></catalogo-detalles>
        </template>
        <template v-if="menu==223">
            <contratista-revprevia></contratista-revprevia>
        </template>
        <template v-if="menu==224">
            <uri-equipamiento></uri-equipamiento>
        </template>

        <template v-if="menu==225">
            <res-puplicidad></res-puplicidad>
        </template>

        <template v-if="menu==226">
            <comision-expediente></comision-expediente>
        </template>

        <template v-if="menu==227">
            <comision-asesores></comision-asesores>
        </template>

        <template v-if="menu==228">
            <prospectos-publicidad></prospectos-publicidad>
        </template>

        <template v-if="menu==229">
            <comision-bonos></comision-bonos>
        </template>
        <template v-if="menu==230">
            <asignar-especificaciones></asignar-especificaciones>
        </template>
        <template v-if="menu==231">
            <prediales-descarga></prediales-descarga>
        </template>
        <template v-if="menu==232">
            <rep-inventario></rep-inventario>
        </template>
        <template v-if="menu==233">
            <rep-vendedores></rep-vendedores>
        </template>

        <template v-if="menu==234">
            <lotes-ruv></lotes-ruv>
        </template>
        <template v-if="menu==235">
            <seguimiento-ruv rol-id="{{Auth::user()->rol_id}}"></seguimiento-ruv>
        </template>
        <template v-if="menu==236">
            <generar-solipago></generar-solipago>
        </template>
        <template v-if="menu==237">
            <solicitud-pagos rol-id="{{Auth::user()->rol_id}}"></solicitud-pagos>
        </template>
        <template v-if="menu==238">
            <rep-lotes></rep-lotes>
        </template>
        <template v-if="menu==239">
            <rep-ventascanc></rep-ventascanc>
        </template>
        <template v-if="menu==240">
            <rep-acumulado rol-id="{{Auth::user()->rol_id}}" user-id="{{Auth::user()->id}}"></rep-acumulado>
        </template>

        <template v-if="menu==241">
            <cat-bonos></cat-bonos>
        </template>
        <template v-if="menu==242">
            <bono-recomendado rol-id="{{Auth::user()->rol_id}}"></bono-recomendado>
        </template>
        <template v-if="menu==243">
            <recursos-propios></recursos-propios>
        </template>

        <template v-if="menu==244">
            <repcredito-puente></repcredito-puente>
        </template>

        <template v-if="menu==245">
            <reporte-modelos></reporte-modelos>
        </template>

        <template v-if="menu==246">
            <reporte-detalles></reporte-detalles>
        </template>

        <template v-if="menu==1003">
            <cotizador-lote></cotizador-lote>
        </template>
        <template v-if="menu==1004">
            <cotizador-opciones></cotizador-opciones>
        </template>
        <template v-if="menu==1005">
            <cotizador-editar></cotizador-editar>
        </template>
        <template v-if="menu==247">
            <ingresos-concretania></ingresos-concretania>
        </template>

        <template v-if="menu==248">
            <estimaciones></estimaciones>
        </template>

        <template v-if="menu==249">
            <campanias></campanias>
        </template>

        <template v-if="menu==250">
            <digital-leads rol-id="{{Auth::user()->rol_id}}" user-id="{{Auth::user()->id}}"></digital-leads>
        </template>

        <template v-if="menu==251">
            <rep-ingresos></rep-ingresos>
        </template>

        <template v-if="menu==252">
            <rep-escrituras></rep-escrituras>
        </template>

        <template v-if="menu==253">
            <rep-ingresosenganche></rep-ingresosenganche>
        </template>

        <template v-if="menu==254">
            <asesores-fracc></asesores-fracc>
        </template>

        <template v-if="menu==255">
            <solic-puente user-name="{{Auth::user()->usuario}}"></solic-puente>
        </template>
        <template v-if="menu==256">
            <creditos-puente user-name="{{Auth::user()->usuario}}"></creditos-puente>
        </template>
        <template v-if="menu==257">
            <base-presupuestal></base-presupuestal>
        </template>

        <template v-if="menu==258">
            <reporte-leads></reporte-leads>
        </template>
        <template v-if="menu==259">
            <puente-cuenta user-name="{{Auth::user()->usuario}}"></puente-cuenta>
        </template>
        <template v-if="menu==260">
            <prospectos-reasignados></prospectos-reasignados>
        </template>
        <template v-if="menu==261">
            <puente-avances></puente-avances>
        </template>
        <template v-if="menu==262">
            <puente-resumen user-name="{{Auth::user()->usuario}}"></puente-resumen>
        </template>
        <template v-if="menu==263">
            <puente-bbva user-name="{{Auth::user()->usuario}}"></puente-bbva>
        </template>


        

        
    @endif

       
@endsection