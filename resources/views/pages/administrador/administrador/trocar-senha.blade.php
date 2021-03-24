@extends('layout.administradores.master')
@section('titulo-pagina', 'Atualizar Informações')

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Usuário</a></li>
    <li class="breadcrumb-item active" aria-current="page">Editar Usuário</li>
  </ol>
</nav>
<div class="row">
  <div class="col-md-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Atualizar Informações do Usuário</h6>
          <form method="POST" action="{{route('tenant.usuario.atualizar.senha', $user->id)}}">
          {{ method_field('PUT') }}
          @csrf 
            <div class="row">
              <div class="col-sm-5">
                <div class="form-group {{$errors->has('name') ? 'has-danger' : ''}}">
                  <label class="control-label">Nome</label>
                  <input type="text" value="{{$user->name}}" class="form-control {{$errors->has('name') ? 'form-control-danger' : ''}}" name="name" placeholder="Nome do Usuário">
                  @if($errors->has('name'))
                    <label id="name-error" class="error mt-2 text-danger" for="name">
                      {{$errors->first('name')}}
                    </label>
                  @endif
                </div>
              </div><!-- Col -->
              <div class="col-sm-2">
            
              </div><!-- Col -->
            </div><!-- Row -->
            <div class="row">
            <div class="col-sm-5">
                <div class="form-group {{$errors->has('email') ? 'has-danger' : ''}}">
                  <label class="control-label">E-mail Administrador</label>
                  <input type="text" value="{{$user->email}}" class="form-control {{$errors->has('email') ? 'form-control-danger' : ''}}" name="email" placeholder="E-mail">
                  @if($errors->has('email'))
                    <label id="name-error" class="error mt-2 text-danger" for="email">
                      {{$errors->first('email')}}
                    </label>
                  @endif
                </div>
              </div><!-- Col -->
              <div class="col-sm-5">
                <div class="form-group {{$errors->has('password') ? 'has-danger' : ''}}">
                  <label class="control-label">Senha Administrador</label>
                  <input type="password" value="{{$user->password}}" class="form-control {{$errors->has('password') ? 'form-control-danger' : ''}}" name="password" placeholder="Senha">
                  @if($errors->has('password'))
                    <label id="name-error" class="error mt-2 text-danger" for="password">
                      {{$errors->first('password')}}
                    </label>
                  @endif
                </div>
              </div><!-- Col -->
            </div>
            <div class="row">
              <div class="col-sm-5">
              <p>**Após Trocar a Senha o sistema necessitará fazer o login novamente.</p>
              </div>
            </div>
            <button type="submit" class="btn btn-primary submit">Salvar</button>  
          </form>
      </div>
    </div>
  </div>
</div>
@endsection
