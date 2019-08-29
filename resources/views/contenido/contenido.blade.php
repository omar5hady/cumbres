@extends('principal')
@section('contenido')


    @if(Auth::check())

        <template v-if="menu==0">
            <h1>Escritorio</h1>
        </template>
        
        <template v-if="menu==10">
            <h1>Contenido del menu 10</h1>
        </template>
        <template v-if="menu==20">
            <h1>Contenido del menu 20</h1>
        </template>
        <template v-if="menu==100">
            <perfil-user user-id="{{Auth::user()->id}}"></perfil-user>
        </template>
        <template v-if="menu==101">
            <listar-notifications user-id="{{Auth::user()->id}}"></listar-notifications>
        </template>
        <template v-if="menu==90">
            <datos-extra></datos-extra>
        </template>

        @if(Auth::user()->fraccionamiento == 1)
            <template v-if="menu==1">
                <fraccionamiento></fraccionamiento>
            </template>
        @endif

        @if(Auth::user()->mis_asesores == 1)
            <template v-if="menu==73">
                <asesores></asesores>
            </template>
        @endif

        @if(Auth::user()->etapas == 1)
            <template v-if="menu==2">
                <etapa></etapa>
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
                <precio-etapa></precio-etapa>
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
                <aviso-obra></aviso-obra>
            </template>
        @endif

        @if(Auth::user()->aviso_obra == 1)
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
                <lote-disponible rol-id="{{Auth::user()->rol_id}}"></lote-disponible>
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
                <crear-contrato><crear-contrato/>
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
            <seguimiento-tramite></seguimiento-tramite>
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

        <template v-if="menu==209">
            <devolucion-cancelacion></devolucion-cancelacion>
        </template>

        <template v-if="menu==210">
            <devolucion-credito></devolucion-credito>
        </template>

        <template v-if="menu==211">
            <proveedores></proveedores>
        </template>
       

        
    @endif

       
@endsection