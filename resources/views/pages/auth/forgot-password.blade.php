@extends('layout.auth.master-personalizado')
@section('title', 'BI - EXPLORER')
@push('plugin-styles')
<style>
body {
    /* background: rgba(18, 84, 131, 0.89) !important; */
    /*background-image: url(https://dados.app.br/a2m/images/ny.jpg);*/ 
    background-image: url({{asset("assets/images/bg/bg2.jpg")}});
    background-position: center; /* Center the image */
    background-repeat: no-repeat; /* Do not repeat the image */
    background-size: cover; /* Resize the background image to cover the entire container*/
 }
 </style>
  <link href="{{ asset('assets/css/auth-personalizado/auth-personalizado.css') }}" rel="stylesheet" />
@endpush
@section('content')
<div class="page-content d-flex align-items-center justify-content-center">

  <div class="row w-100 mx-0 auth-page">
    <div class="col-md-8 col-xl-6 mx-auto">
      <div class="card">
        <div class="row">
          <!--
          <div class="col-md-4 pr-md-0">
            <div class="auth-left-wrapper" style="background-image: url({{ url('https://via.placeholder.com/219x452') }})">

            </div>
          </div>
          -->
          <div class="col-md-8 pl-md-0 form-login">
            <div class="auth-form-wrapper px-4 py-5">
             <!-- <a href="#" class="noble-ui-logo d-block mb-2">Noble<span>UI</span></a> --> 
             <img src="{{asset('assets/images/email.png')}}" width="90%" alt="Logo">
              <h5 class="text-muted font-weight-normal mb-4"><b>Digite o seu e-mail para recuperação da senha</b></h5>
              <form  method="POST" action="{{route('forget-password')}}">
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
                <div class="mt-3">
                  <input type="submit" value="Enviar link de Recuperação" class="btn btn-primary mr-2 mb-2 mb-md-0">
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