@extends('layout.parceiros.master')

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('parceiro.tenants')}}">Empresas</a></li>
    <li class="breadcrumb-item active" aria-current="page">Editar Empresa</li>
  </ol>
</nav>
<div class="row">
<input type="text" hidden id="verifica_filtro" value="{{$tenant->utiliza_filtro}}">
<input type="hidden" id="utilizarls" value="{{$tenant->utiliza_rls}}" >
  <div class="col-md-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Editar Empresa</h6>
          <form method="POST" action="{{route('parceiro.tenant.atualizar', $tenant->id)}}">
          <input type="text" hidden  name="usuario_admin" value="{{$user->id}}">
          {{ method_field('PUT') }}
          @csrf 
            <div class="row">
              <div class="col-sm-5">
                <div class="form-group {{$errors->has('nome') ? 'has-danger' : ''}}">
                  <label class="control-label">Nome</label>
                  <input type="text" value="{{$tenant->nome}}" class="form-control {{$errors->has('nome') ? 'form-control-danger' : ''}}" name="nome" placeholder="Nome da Empresa">
                  @if($errors->has('nome'))
                    <label id="name-error" class="error mt-2 text-danger" for="nome">
                      {{$errors->first('nome')}}
                    </label>
                  @endif
                </div>
              </div><!-- Col -->
              <div class="col-sm-2">
            
              </div><!-- Col -->
            </div><!-- Row -->
            <div class="row">
            <div class="col-sm-5">
                <div class="form-group {{$errors->has('email_administrador') ? 'has-danger' : ''}}">
                  <label class="control-label">E-mail Administrador</label>
                  <input type="text" value="{{$tenant->email_administrador}}" class="form-control {{$errors->has('email_administrador') ? 'form-control-danger' : ''}}" name="email_administrador" placeholder="E-mail Administrador">
                  @if($errors->has('email_administrador'))
                    <label id="name-error" class="error mt-2 text-danger" for="email_administrador">
                      {{$errors->first('email_administrador')}}
                    </label>
                  @endif
                </div>
              </div><!-- Col -->
              <div class="col-sm-5">
                <div class="form-group {{$errors->has('senha_administrador') ? 'has-danger' : ''}}">
                  <label class="control-label">Senha Administrador</label>
                  <input type="password" value="{{$user->password}}" class="form-control {{$errors->has('senha_administrador') ? 'form-control-danger' : ''}}" name="senha_administrador" placeholder="Senha Administrador">
                  @if($errors->has('senha_administrador'))
                    <label id="name-error" class="error mt-2 text-danger" for="senha_administrador">
                      {{$errors->first('senha_administrador')}}
                    </label>
                  @endif
                </div>
              </div><!-- Col -->
            </div>
            <div class="row">
              <div class="col-sm-2">
                  <div class="form-group">
                    <label class="control-label">Limite de Usuários</label>
                    <input type="number" value="{{$tenant->limite_usuarios}}" class="form-control" name="limite_usuarios" placeholder="Limite de Usuários">
                  </div>
              </div><!-- Col -->
            </div>
            <div class="row">
              <div class="col-sm-2">
              <div class="form-group">
										<div class="custom-control custom-switch">
											<input type="checkbox" {{$tenant->utiliza_filtro == 'S' ? 'checked' : ''}} class="custom-control-input" value="on" name="utilizafiltro" id="utilizaFiltroToggle">
											<label class="custom-control-label" for="utilizaFiltroToggle">Utiliza Filtro</label>
										</div>
									</div>
              </div><!-- Col -->
              <div class="col-sm-2">
              <div class="form-group">
										<div class="custom-control custom-switch">
											<input type="checkbox" {{$tenant->utiliza_rls == 'S' ? 'checked' : ''}} class="custom-control-input" value="on" name="utiliza_rls" id="utilizaRlsToggle">
											<label class="custom-control-label" for="utilizaRlsToggle">Utiliza Rls</label>
										</div>
									</div>
              </div><!-- Col -->
            </div><!-- Row -->
            <div class="row" id="linhaFiltros">
              <div class="col-sm-4">
                <div class="form-group">
                  <label class="control-label">Tabela</label>
                  <input type="text" value="{{$tenant->filtro_tabela}}" name="filtro_tabela" id="tabela" class="form-control" placeholder="Tabela">
                </div>
              </div><!-- Col -->
              <div class="col-sm-4">
                <div class="form-group">
                  <label class="control-label">Coluna</label>
                  <input type="text" value="{{$tenant->filtro_coluna}}"  name="filtro_coluna" id="coluna" class="form-control" placeholder="Coluna">
                </div>
              </div><!-- Col -->
              <div class="col-sm-4">
                <div class="form-group">
                  <label class="control-label">Valor</label>
                  <input type="text" value="{{$tenant->filtro_valor}}"  name="filtro_valor" id="valor" class="form-control" placeholder="Valor">
                </div>
              </div><!-- Col -->
            </div><!-- Row -->
            <div class="row" id="linhaRls">
              <div class="col-sm-4">
                <div class="form-group">
                  <label class="control-label">Regra</label>
                  <input type="text" name="regra_rls" value="{{$tenant->regra_rls}}" id="regra_rls" class="form-control" placeholder="Regra RLS">
                </div>
              </div><!-- Col -->
              <div class="col-sm-4">
                <div class="form-group">
                  <label class="control-label">Username</label>
                  <input type="text" name="username_rls"  value="{{$tenant->username_rls}}"  id="username_rls" class="form-control" placeholder="Username RLS">
                </div>
              </div><!-- Col -->
            </div><!-- Row -->
            <button type="submit" class="btn btn-primary submit">Salvar</button>  
          </form>
      </div>
    </div>
  </div>
</div>
@endsection
@push('custom-scripts')
<script type="text/javascript">
 $(document).ready(function() {
   //esconde os filtros ao carregar a pagina
   var verifica_filtro = $("#verifica_filtro").val();
   if(verifica_filtro == 'S'){
    $("#linhaFiltros").show();
    $("#linhaRls").hide();
   }else{
    $("#linhaFiltros").hide();
   }
    //utilizarls
   var utilizarls = $("#utilizarls").val();
   if(utilizarls == 'S'){
    $("#linhaRls").show();
    $("#linhaFiltros").hide();
   }else{
    $("#linhaRls").hide();
   }
      $("#utilizaFiltroToggle").change(function(){
          //VERIFICA SE UTILIZA FILTRO
          if($(this).prop("checked") == true){
            //se utiliza filtro mostro os campos
            $("#linhaFiltros").show();
            $("#linhaRls").hide();
            $("#utilizaRlsToggle").prop( "checked", false );
            $("#regra_rls").val('');
            $("#username_rls").val('');
          }else{
          //escondo os campos e apago os valores
          $("#linhaFiltros").hide();
          $("#tabela").val('');
          $("#coluna").val('');
          $("#valor").val('');
          }
      });
          //RLS
    $("#utilizaRlsToggle").change(function(){
        //VERIFICA SE UTILIZA FILTRO
        if($(this).prop("checked") == true){
          //se utiliza filtro mostro os campos
          $("#linhaRls").show();
          $("#linhaFiltros").hide();
          $("#utilizaFiltroToggle").prop( "checked", false );
          $("#tabela").val('');
          $("#coluna").val('');
          $("#valor").val('');
        }else{
        //escondo os campos e apago os valores
        $("#linhaRls").hide();
        $("#regra_rls").val('');
        $("#username_rls").val('');
        }
    });
});
</script>
@endpush