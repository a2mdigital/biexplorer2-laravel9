@extends('layout.parceiros.master')

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('parceiros.listar')}}">Parceiros</a></li>
    <li class="breadcrumb-item active" aria-current="page">Editar Parceiro</li>
  </ol>
</nav> 
<div class="row">
  <div class="col-md-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Cadastro de Parceiro</h6>
          <form method="POST" action="{{route('parceiro.atualizar', $parceiro->id)}}">
          @csrf 
          {{method_field('PUT')}}
            <div class="row">
              <div class="col-sm-5">
                <div class="form-group {{$errors->has('name') ? 'has-danger' : ''}}">
                  <label class="control-label">Nome</label>
                  <input type="text" value="{{$parceiro->name}}" class="form-control {{$errors->has('name') ? 'form-control-danger' : ''}}" name="name" placeholder="Nome do Parceiro">
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
                  <label class="control-label">E-mail</label>
                  <input type="text" value="{{$parceiro->email}}" class="form-control {{$errors->has('email') ? 'form-control-danger' : ''}}" name="email" placeholder="E-mail Administrador">
                  @if($errors->has('email'))
                    <label id="name-error" class="error mt-2 text-danger" for="email">
                      {{$errors->first('email')}}
                    </label>
                  @endif
                </div>
             </div><!-- Col -->
              <div class="col-sm-5">
                <div class="form-group {{$errors->has('password') ? 'has-danger' : ''}}">
                  <label class="control-label">Senha</label>
                  <input type="password" value="{{$parceiro->password}}" class="form-control {{$errors->has('password') ? 'form-control-danger' : ''}}" name="password" placeholder="Senha">
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
              <div class="form-group">
					<div class="custom-control custom-switch">
							<input type="checkbox" {{$parceiro->troca_senha == 'S' ? 'checked' : ''}} class="custom-control-input" value="on" name="troca_senha" id="troca_senha">
							<label class="custom-control-label" for="troca_senha">Trocar Senha no Próximo Acesso</label>
					</div>
			    </div>
              </div><!-- Col -->
            </div><!-- Row -->
            <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                  <label class="control-label">Subdomain</label>
                  <input type="text" value="{{$parceiro->subdomain}}" class="form-control" name="subdomain" placeholder="Subdominio">
                </div>
             </div><!-- Col -->
             <div class="col-sm-4">
                <div class="form-group">
                  <label class="control-label">Imagem Login</label>
                  <input type="text" value="{{$parceiro->imagem_login}}" class="form-control" name="imagem_login" placeholder="Imagem Login">
                </div>
             </div><!-- Col -->
             <div class="col-sm-4">
                <div class="form-group">
                  <label class="control-label">Tamanho Imagem</label>
                  <input type="text" value="{{$parceiro->tamanho_imagem_login}}" class="form-control" name="tamanho_imagem_login" placeholder="Tamanho Imagem">
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