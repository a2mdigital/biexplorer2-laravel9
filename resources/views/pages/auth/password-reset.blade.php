@extends('layout.auth.master-personalizado')
@push('plugin-styles') 
<link href="{{ asset('assets/css/auth-personalizado/auth-personalizado.css') }}" rel="stylesheet" />
<style>
body {
    background-image: url({{asset("assets/images/bg/bg2.jpg")}});
    background-position: center; /* Center the image */
    background-repeat: no-repeat; /* Do not repeat the image */
    background-size: cover; /* Resize the background image to cover the entire container*/
 }
 </style>
@endpush
@section('content')
<div class="page-content d-flex align-items-center justify-content-center">

  <div class="row w-100 mx-0 auth-personalizado">
    <div class="col-md-8 col-xl-6 mx-auto">
      <div class="card">
        <div class="row">
          <div class="col-md-8 pl-md-0 form-login">
            <div class="auth-form-wrapper px-4 py-5">
             <img src="{{asset('assets/images/password-change.png')}}" width="90%" style="margin-bottom: 15px; margin-top:10px;" alt="Logo">
              <h5 class="text-muted font-weight-normal mb-4"><b>Troque sua Senha</b></h5>
              <form  method="POST" action="{{route('reset-password')}}">
              <input type="hidden" name="token" value="{{ $token }}">
              @csrf 
              <div class="form-group {{$errors->has('email') ? 'has-danger' : ''}}">
                  <label for="exampleInputEmail1">Email</label>
                  <input type="email" value="{{old('email')}}" class="form-control {{$errors->has('email') ? 'form-control-danger' : ''}}" id="email" name="email" placeholder="Email">
                  @if($errors->has('email'))
                    <label id="name-error" class="error mt-2 text-danger" for="email">
                      {{$errors->first('email')}}
                    </label>
                  @endif 
                </div>
                <div class="form-group {{$errors->has('password') ? 'has-danger' : ''}}">
                  <label for="exampleInputPassword1">Senha</label>
                  <input type="password" class="form-control {{$errors->has('password') ? 'form-control-danger' : ''}}" id="password" name="password" autocomplete="current-password" placeholder="Password">
                  @if($errors->has('password'))
                    <label id="name-error" class="error mt-2 text-danger" for="password">
                      {{$errors->first('password')}}
                    </label>
                  @endif 
                </div>
                <div class="form-group {{$errors->has('password') ? 'has-danger' : ''}}">
                  <label for="exampleInputPassword1">Confirme a Senha</label>
                  <input type="password" class="form-control {{$errors->has('password_confirmation') ? 'form-control-danger' : ''}}" id="password_confirmation" name="password_confirmation" autocomplete="current-password" placeholder="Password">
                  @if($errors->has('password'))
                    <label id="name-error" class="error mt-2 text-danger" for="password">
                      {{$errors->first('password')}}
                    </label>
                  @endif 
                </div>
                <div class="mt-3">
                  <input type="submit" value="Trocar Senha" class="btn btn-primary mr-2 mb-2 mb-md-0">
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