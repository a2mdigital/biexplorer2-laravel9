@extends('layout.auth.master-personalizado')
@section('title', 'BI - VISIONWAY')
@push('plugin-styles')
  <link href="{{ asset('assets/css/login-personalizado/login-visionway.css') }}" rel="stylesheet" />
@endpush
@section('content')
<div class="page-content d-flex align-items-center justify-content-center">

  <div class="row w-100 mx-0 login-personalizado">
    <div class="col-md-8 col-xl-6 mx-auto">
      <div class="row">
        <div class="card">
          <div class="col-md-8 pl-md-0 form-login">
          <div class="col-md-8 pl-md-0 form-login">  
              <img src="{{asset('assets/images/logo_visionway.png')}}" width="90%" style="margin-bottom: 5px; margin-top:20px;" alt="Logo">
            </div>
            <div class="auth-form-wrapper px-4 py-5">
              <h5 class="text-muted font-weight-normal mb-4">Bem vindo! Entre com seus dados para login!</h5>
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
                  <input type="password" class="form-control {{$errors->has('password') ? 'form-control-danger' : ''}}" id="password" name="password" autocomplete="current-password" placeholder="Password">
                  @if($errors->has('password'))
                    <label id="name-error" class="error mt-2 text-danger" for="password">
                      {{$errors->first('password')}}
                    </label>
                  @endif 
                </div>
                <div class="mt-3">
                  <input type="submit" value="Login" class="btn btn-primary mr-2 mb-2 mb-md-0">
                  <a href="{{route('esqueceu-senha')}}"  class="btn btn-outline-primary btn-icon-text mb-2 mb-md-0">
                    <i class="btn-icon-prepend" data-feather="lock"></i>
                   Esqueceu a senha?
                   </a>
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