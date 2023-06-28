{{-- LA vista extiende del blade Principal --}}
@extends('principal')
{{-- Se especifica el nombre de la seccion como contenido --}}
@section('evento')
    {{-- Se verifica que el usuario este autenticado (Iniciado sesion) --}}
    @if (Auth::check())
    <template>
        <evento-registro v-if="menu==100" invitado="{{$invitado}}"></evento-registro>
    </template>
    @endif
@endsection
