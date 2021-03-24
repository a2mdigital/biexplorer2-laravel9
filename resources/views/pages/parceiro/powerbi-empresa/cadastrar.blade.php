@extends('layout.parceiros.master')

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('parceiro.tenants')}}">{{$tenant->nome}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">Cadastrar Conta Power Bi</li>
  </ol>
</nav>
<div class="row">
  <div class="col-md-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Cadastro de Conta Power Bi</h6>
          <form method="POST" action="{{route('parceiro.empresa.powerbi.salvar')}}">
          <input type="hidden" name="tenant_id" value="{{$tenant->id}}">
          @csrf 
          {{ method_field('PUT') }}
            <div class="row">
              <div class="col-sm-5">
                <div class="form-group {{$errors->has('user_powerbi') ? 'has-danger' : ''}}">
                  <label class="control-label">Usuário</label>
                  <input type="text" value="{{$powerbi->user_powerbi}}" class="form-control {{$errors->has('user_powerbi') ? 'form-control-danger' : ''}}" name="user_powerbi" placeholder="Usuário Power BI">
                  @if($errors->has('user_powerbi'))
                    <label id="name-error" class="error mt-2 text-danger" for="user_powerbi">
                      {{$errors->first('user_powerbi')}}
                    </label>
                  @endif
                </div>
              </div><!-- Col -->
              <div class="col-sm-5">
                <div class="form-group {{$errors->has('password_powerbi') ? 'has-danger' : ''}}">
                  <label class="control-label">Senha</label>
                  <input type="password" value="{{$powerbi->password_powerbi}}" class="form-control {{$errors->has('password_powerbi') ? 'form-control-danger' : ''}}" name="password_powerbi" placeholder="Senha">
                  @if($errors->has('password_powerbi'))
                    <label id="name-error" class="error mt-2 text-danger" for="password_powerbi">
                      {{$errors->first('password_powerbi')}}
                    </label>
                  @endif
                </div>
              </div><!-- Col -->  
            </div><!-- Row -->
            <div class="row">
            <div class="col-sm-4">
                <div class="form-group {{$errors->has('client_id') ? 'has-danger' : ''}}">
                  <label class="control-label">Client ID (Azure)</label>
                  <input type="text" value="{{$powerbi->client_id}}" class="form-control {{$errors->has('client_id') ? 'form-control-danger' : ''}}" name="client_id" placeholder="Client ID">
                  @if($errors->has('client_id'))
                    <label id="name-error" class="error mt-2 text-danger" for="client_id">
                      {{$errors->first('client_id')}}
                    </label>
                  @endif
                </div>
              </div><!-- Col -->
              <div class="col-sm-4">
                <div class="form-group {{$errors->has('client_secret') ? 'has-danger' : ''}}">
                  <label class="control-label">Client Secret (Azure)</label>
                  <input type="text" value="{{$powerbi->client_secret}}" class="form-control {{$errors->has('client_secret') ? 'form-control-danger' : ''}}" name="client_secret" placeholder="Client Secret">
                  @if($errors->has('client_secret'))
                    <label id="name-error" class="error mt-2 text-danger" for="client_secret">
                      {{$errors->first('client_secret')}}
                    </label>
                  @endif
                </div>
              </div><!-- Col -->
              <div class="col-sm-4">
                <div class="form-group {{$errors->has('diretorio_id') ? 'has-danger' : ''}}">
                  <label class="control-label">Diretório ID (Azure)</label>
                  <input type="text" value="{{$powerbi->diretorio_id}}" class="form-control {{$errors->has('diretorio_id') ? 'form-control-danger' : ''}}" name="diretorio_id" placeholder="Diretório ID">
                  @if($errors->has('diretorio_id'))
                    <label id="name-error" class="error mt-2 text-danger" for="diretorio_id">
                      {{$errors->first('diretorio_id')}}
                    </label>
                  @endif
                </div>
              </div><!-- Col -->
            </div>
           <button type="submit" class="btn btn-primary submit">Salvar</button>  
          </form>
      </div>
    </div>
  </div>
</div>
@endsection
