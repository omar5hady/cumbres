@extends('auth.contenido')

@section('login')
<div class="row justify-content-center animated fadeInUp slow">
      <div class="col-md-8">
        <div class="card-group mb-0">
          <div class="card p-4">
          <form class="form-horizontal was-validated" method="POST" action="{{route('login')}}">
            {{csrf_field()}}
            <div class="card-body">
              <h1>Acceder</h1>
              <p class="text-muted">Control de acceso al sistema</p>
              <div class="form-group mb-3{{$errors->has('usuario' ? 'is-invalid' : '')}}">
                <span class="input-group-addon"><i class="icon-user"></i></span>
                <input type="text" name="usuario" value="{{old('usuario')}}" id="usuario" class="form-control" placeholder="Usuario">
                {!!$errors->first('usuario','<span class="invalid-feedback">:message</span>')!!}
              </div>
              <div class="form-group mb-4{{$errors->has('password' ? 'is-invalid' : '')}}">
                <span class="input-group-addon"><i class="icon-lock"></i></span>
                <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                {!!$errors->first('password','<span class="invalid-feedback">:message</span>')!!}
              </div>
              <div class="row animated fadeInLeft slower">
                <div class="col-6">
                  <button type="submit" class="btn btn-dark px-4">Acceder</button>
                </div>
              </div>
            </div>
          </form>
          </div>
          <div class="card text-white bg-dark py-5 d-md-down-none" style="width:44%">
            <div class="card-body text-center">
              <div>
                <h1 center> SII </h1>
                <h2 center> CUMBRES </h2>
                <img class="animated fadeInRight slower" id="logo" src="img/logoLogin.png" alt="">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

@endsection
