@extends('principal')
@section('contenido')


    @if(Auth::check())

        <template v-if="menu==100">
            <perfil-user user-id="{{Auth::user()->id}}"></perfil-user>
        </template>

        @if(Auth::user()->rol_id == 1)
            <template v-if="menu==0">
                <h1>Escritorio</h1>
            </template>

            <template v-if="menu==1">
                <fraccionamiento></fraccionamiento>
            </template>

            <template v-if="menu==2">
                <etapa></etapa>
            </template>

            <template v-if="menu==3">
                <modelo></modelo>
            </template>

            <template v-if="menu==4">
                <lote></lote>
            </template>

            <template v-if="menu==5">
            <asignar-modelo></asignar-modelo>
            </template>

            <template v-if="menu==6">
            <licencias></licencias>
            </template>

            <template v-if="menu==7">
            <actadeterminacion></actadeterminacion>
            </template>

            <template v-if="menu==8">
            <h1>Contenido del menu 8</h1>
                
            </template>

            <template v-if="menu==9">
                <h1>Contenido del menu 9</h1>
            </template>

            <template v-if="menu==10">
                <h1>Contenido del menu 10</h1>
            </template>

            <template v-if="menu==11">
                <departamento></departamento>
            </template>

            <template v-if="menu==12">
                <personal></personal>
            </template>

            <template v-if="menu==13">
                <empresa></empresa>
            </template>
            <template v-if="menu==14">
                <medio-publicitario></medio-publicitario>
            </template>
            <template v-if="menu==15">
                <lugar-contacto></lugar-contacto>
            </template>
            <template v-if="menu==16">
                <institucion-financiamiento></institucion-financiamiento>
            </template>
            <template v-if="menu==17">
                <tipo-credito></tipo-credito>
            </template>

            <template v-if="menu==18">
                <servicio></servicio>
            </template>

            <template v-if="menu==19">
                <asignar-servicio></asignar-servicio>
            </template>

            <template v-if="menu==20">
                <h1>Contenido del menu 20</h1>
            </template>

            <template v-if="menu==21">
                <precio-etapa></precio-etapa>
            </template>

            <template v-if="menu==22">
                <sobreprecios></sobreprecios>
            </template>
            <template v-if="menu==23">
                <paquetes></paquetes>
            </template>
            <template v-if="menu==24">
                <promociones></promociones>
            </template>

            <template v-if="menu==50">
                <contratistas></contratistas>
            </template>

            <template v-if="menu==51">
                <iniobra></iniobra>
            </template>
            <template v-if="menu==52">
                <partidas></partidas>
            </template>

            <template v-if="menu==53">
                <avance></avance>
            </template>

            <template v-if="menu==54">
                <aviso-obra></aviso-obra>
            </template>

            <template v-if="menu==71">
                <rol></rol>
            </template>

            <template v-if="menu==72">
                <usuario></usuario>
            </template>

            <template v-if="menu==59">
                <lote-disponible rol-id="{{Auth::user()->rol_id}}"></lote-disponible>
            </template>

            <template v-if="menu==60">
                <prospectos></prospectos>
            </template>
            
            <template v-if="menu==61">
                <simulacion rol-id="{{Auth::user()->rol_id}}"></simulacion>
            </template>
            
            <template v-if="menu==62">
                <historialsim rol-id="{{Auth::user()->rol_id}}"></historialsim>
            </template>
        
        @elseif(Auth::user()->rol_id == 2) <!--Vendedor -->
            <template v-if="menu==13">
                <empresa></empresa>
            </template>
            <template v-if="menu==14">
                <medio-publicitario></medio-publicitario>
            </template>
            <template v-if="menu==60">
                <prospectos></prospectos>
            </template>
            <template v-if="menu==61">
                <simulacion rol-id="{{Auth::user()->rol_id}}"></simulacion>
            </template>
            <template v-if="menu==59">
                <lote-disponible rol-id="{{Auth::user()->rol_id}}"></lote-disponible>
            </template>
            <template v-if="menu==62">
                <historialsim rol-id="{{Auth::user()->rol_id}}"></historialsim>
            </template>
            
        @elseif(Auth::user()->rol_id == 3) <!--Gerente proyectos -->
            <template v-if="menu==1">
                <fraccionamiento></fraccionamiento>
            </template>

            <template v-if="menu==3">
                <modelo></modelo>
            </template>

            <template v-if="menu==4">
                <lote></lote>
            </template>

            <template v-if="menu==6">
            <licencias></licencias>
            </template>

            <template v-if="menu==7">
            <actadeterminacion></actadeterminacion>
            </template>

            <template v-if="menu==71">
                <rol></rol>
            </template>

            <template v-if="menu==72">
                <usuario></usuario>
            </template>

        @elseif(Auth::user()->rol_id == 4) <!--Gerente ventas -->
            
        @elseif(Auth::user()->rol_id == 5) <!--Gerente obra y construccion -->
            <template v-if="menu==50">
                <contratistas></contratistas>
            </template>

            <template v-if="menu==51">
                <iniobra></iniobra>
            </template>
            <template v-if="menu==52">
                <partidas></partidas>
            </template>

            <template v-if="menu==53">
                <avance></avance>
            </template>

            <template v-if="menu==54">
                <aviso-obra></aviso-obra>
            </template>           

        @endif
    @endif

       
@endsection