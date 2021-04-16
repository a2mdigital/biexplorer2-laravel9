@extends('layout.auth.master-personalizado')
@push('plugin-styles')
<style type="text/css">
body {
    /* background: rgba(0, 26, 227, 0.63) !important; */
    background-image:url({{asset('assets/images/bg/'.$background)}}) !important;
    /*background-image: url({{asset("assets/images/bg/bg3.png")}});*/
    background-position:center; /* Center the image */
    background-repeat:no-repeat; /* Do not repeat the image */
    background-size:cover; /* Resize the background image to cover the entire container*/
 }
 </style>
  <link href="{{ asset('assets/css/auth-personalizado/auth-personalizado.css') }}" rel="stylesheet" />
@endpush
@section('content')
<div class="page-content d-flex align-items-center justify-content-center">

  <div class="row w-100 mx-0 auth-page">
    <div class="col-md-3 col-xl-3 mx-auto">
      <div class="card">
        <div class="row">
          <div class="col-md-8 pl-md-0 form-login">
            <div class="auth-form-wrapper px-4 py-5">
             <!-- <a href="#" class="noble-ui-logo d-block mb-2">Noble<span>UI</span></a> --> 
             <img src="{{asset('assets/images/password.png')}}" width="30%"  style="margin-left:35%; padding-bottom:5%;" >
              <h5 class="text-muted font-weight-normal mb-4"><b>{{__('auth.change_password')}}</b></h5>
              <form  method="POST" action="{{route('users.tenant.atualizar.senha.inicial', $usuario->id)}}">
              @csrf 
              {{method_field('PUT')}}
                <div class="form-group {{$errors->has('password') ? 'has-danger' : ''}}">
                  <label for="exampleInputPassword1">{{__('auth.password')}}</label>
                  <input type="password" class="form-control {{$errors->has('password') ? 'form-control-danger' : ''}}" id="password" name="password" autocomplete="current-password" placeholder="Password">
                  @if($errors->has('password'))
                    <label id="name-error" class="error mt-2 text-danger" for="password">
                      {{$errors->first('password')}}
                    </label>
                  @endif 
                </div>
                <div class="form-group {{$errors->has('password') ? 'has-danger' : ''}}">
                  <label for="exampleInputPassword1">{{__('auth.confirm_password')}}</label>
                  <input type="password" class="form-control {{$errors->has('password_confirmation') ? 'form-control-danger' : ''}}" id="password_confirmation" name="password_confirmation" autocomplete="current-password" placeholder="Password">
                  @if($errors->has('password'))
                    <label id="name-error" class="error mt-2 text-danger" for="password">
                      {{$errors->first('password')}}
                    </label>
                  @endif 
                </div>
                <div class="mt-3">
                  <input type="submit" value="{{__('auth.btn_change_password')}}" class="btn btn-primary mr-2 mb-2 mb-md-0">
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