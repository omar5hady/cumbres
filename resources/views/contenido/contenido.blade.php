@extends('principal')
@section('contenido')


@if(Auth::check())

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
   
            
            @elseif(Auth::user()->rol_id == 2)
                
            @elseif(Auth::user()->rol_id == 3)
                
            @elseif(Auth::user()->rol_id == 4)
                
            @elseif(Auth::user()->rol_id == 5)
                

            @endif
        @endif

       
@endsection