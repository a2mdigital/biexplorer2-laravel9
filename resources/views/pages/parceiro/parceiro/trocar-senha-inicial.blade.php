@extends('layout.auth.master2')
@section('content')
<div class="page-content d-flex align-items-center justify-content-center">

  <div class="row w-100 mx-0 auth-page">
    <div class="col-md-8 col-xl-6 mx-auto">
      <div class="card">
        <div class="row">
          <div class="col-md-8 pl-md-0 form-login">
            <div class="auth-form-wrapper px-4 py-5">
             <!-- <a href="#" class="noble-ui-logo d-block mb-2">Noble<span>UI</span></a> --> 
             <img src="{{asset('assets/images/'.$imagem_login)}}" width="75%">
            
              <h5 class="text-muted font-weight-normal mb-4"><b>Troque sua senha inicial!</b></h5>
              <form  method="POST" action="{{route('parceiro.atualizar.senha.inicial', $parceiro->id)}}">
              @csrf 
              {{method_field('PUT')}}
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
                  <input type="submit" value="Atualizar Senha" class="btn btn-primary mr-2 mb-2 mb-md-0">
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