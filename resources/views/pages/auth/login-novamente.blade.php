@extends('layout.auth.master-personalizado')
@section('title', 'BI - EXPLORER')
@push('plugin-styles')
<style>
body {
    /* background: rgba(18, 84, 131, 0.89) !important; */
    /*background-image: url(https://dados.app.br/a2m/images/ny.jpg);*/ 
    background-image: url({{asset("assets/images/bg/bg1.jpg")}});
    background-position: center; /* Center the image */
    background-repeat: no-repeat; /* Do not repeat the image */
    background-size: cover; /* Resize the background image to cover the entire container*/
 }
 </style>
  <link href="{{ asset('assets/css/auth-personalizado/auth-personalizado.css') }}" rel="stylesheet" />
@endpush
@section('content')
<div class="page-content d-flex align-items-center justify-content-center">

  <div class="row w-100 mx-0 auth-personalizado">
    <div class="col-md-8 col-xl-6 mx-auto">
      <div class="row">
        <div class="card">
          <div class="col-md-8 pl-md-0 form-login">
  
          <img src="{{asset('assets/images/password-change.png')}}" width="90%" alt="Logo">
     
            <div class="auth-form-wrapper px-4 py-5">
              <h5 class="text-muted font-weight-normal mb-4"><b>Por Favor Faça o Login novamente!</b></h5>
              <form  method="POST" action="{{route('login')}}">
              @csrf 
              <div class="form-group {{$errors->has('email') ? 'has-danger' : ''}}">
                  <label for="exampleInputEmail1">Email</label>
                  <input type="email" class="form-control {{$errors->has('email') ? 'form-control-danger' : ''}}" id="email" name="email" placeholder="Email">
                  @if($errors->has('email'))
                    <label id="name-error" class="error mt-2 text-danger" for="email">
                      {{$errors->first('email')}}
                    </label>
                  @endif 
                </div>
                <div class="form-group {{$errors->has('password') ? 'has-danger' : ''}}">
                  <label for="exampleInputPassword1">Senha</label>
                  <input type="password" class="form-control {{$errors->has('password') ? 'form-control-danger' : ''}}" id="password" name="password" autocomplete="off" placeholder="Password">
                  @if($errors->has('password'))
                    <label id="name-error" class="error mt-2 text-danger" for="password">
                      {{$errors->first('password')}}
                    </label>
                  @endif 
                </div>
                <div class="mt-3">
                  <input type="submit" value="Login" class="btn btn-primary mr-2 mb-2 mb-md-0">
                </div>
             </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection
