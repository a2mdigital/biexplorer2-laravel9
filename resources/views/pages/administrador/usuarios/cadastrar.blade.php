@extends('layout.administradores.master')
@push('plugin-styles')
<link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush
@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('tenant.usuarios')}}">Usuários</a></li>
    <li class="breadcrumb-item active" aria-current="page">Cadastrar Usuário</li>
  </ol>
</nav>
<div class="row">
  <div class="col-md-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Cadastro de Usuários</h6>
          <form method="POST" action="{{route('tenant.usuario.salvar')}}">
          @csrf 
            <div class="row">
              <div class="col-sm-5">
                <div class="form-group {{$errors->has('name') ? 'has-danger' : ''}}">
                  <label class="control-label">Nome</label>
                  <input type="text" value="{{old('name')}}" class="form-control {{$errors->has('name') ? 'form-control-danger' : ''}}" name="name" placeholder="Nome do Usuário">
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
                  <label class="control-label">E-mail </label>
                  <input type="text" value="{{old('email')}}" class="form-control {{$errors->has('email') ? 'form-control-danger' : ''}}" name="email" placeholder="E-mail">
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
                  <input type="password" value="{{old('password')}}" class="form-control {{$errors->has('password') ? 'form-control-danger' : ''}}" name="password" placeholder="Senha ">
                  @if($errors->has('password'))
                    <label id="name-error" class="error mt-2 text-danger" for="password">
                      {{$errors->first('password')}}
                    </label>
                  @endif
                </div>
              </div><!-- Col -->
            </div>
            <div class="row">
              <div class="col-sm-4">
              <div class="form-group">
										<div class="custom-control custom-switch">
											<input type="checkbox" class="custom-control-input" value="on" name="troca_senha" id="troca_senhaToggle">
											<label class="custom-control-label" for="troca_senhaToggle">Trocar Senha no Próximo Acesso</label>
										</div>
									</div>
              </div><!-- Col -->
            </div><!-- Row -->
            <div class="row">
              <div class="col-sm-2">
              <div class="form-group">
                    <div class="custom-control custom-switch">
											<input type="checkbox" class="custom-control-input" value="on" name="utilizafiltro" id="utilizaFiltroToggle">
											<label class="custom-control-label" for="utilizaFiltroToggle">Utiliza Filtro</label>
										</div>
									</div>
              </div><!-- Col -->
              <div class="col-sm-2">
              <div class="form-group">
										<div class="custom-control custom-switch">
											<input type="checkbox" class="custom-control-input" value="on" name="utiliza_rls" id="utilizaRlsToggle">
											<label class="custom-control-label" for="utilizaRlsToggle">Utiliza Rls</label>
										</div>
									</div>
              </div><!-- Col -->
            </div><!-- Row -->
            <div class="row" id="linhaFiltros">
              <div class="col-sm-4">
                <div class="form-group">
                  <label class="control-label">Tabela</label>
                  <input type="text" name="tabela" id="tabela" class="form-control" placeholder="Tabela">
                </div>
              </div><!-- Col -->
              <div class="col-sm-4">
                <div class="form-group">
                  <label class="control-label">Coluna</label>
                  <input type="text" name="coluna" id="coluna" class="form-control" placeholder="Coluna">
                </div>
              </div><!-- Col -->
              <div class="col-sm-4">
                <div class="form-group">
                  <label class="control-label">Valor</label>
                  <input type="text" name="valor" id="valor" class="form-control" placeholder="Valor">
                </div>
              </div><!-- Col -->
            </div><!-- Row -->
            <div class="row" id="linhaRls">
              <div class="col-sm-4">
                <div class="form-group">
                  <label class="control-label">Regra</label>
                  <input type="text" name="regra_rls" id="regra_rls" class="form-control" placeholder="Regra RLS">
                </div>
              </div><!-- Col -->
              <div class="col-sm-4">
                <div class="form-group">
                  <label class="control-label">Username</label>
                  <input type="text" name="username_rls" id="username_rls" class="form-control" placeholder="Username RLS">
                </div>
              </div><!-- Col -->
            </div><!-- Row -->

            <div class="row">
              <div class="col-sm-10">
                  <div class="form-group {{$errors->has('departamento_id') ? 'has-danger' : ''}}">
                    <label class="control-label">Selecione o Departamento</label>
                        <select class="departamentos w-100 form-control {{$errors->has('departamento_id') ? 'form-control-danger' : ''}}" name="departamento_id" id="departamento_id">
                        <option value="">Selecione o Departamento</option>
                        @foreach($departamentos as $departamento)
                                <option value="{{$departamento->id}}">{{$departamento->nome}}</option>
                        @endforeach
                        </select>
                        @if($errors->has('departamento_id'))
                        <label id="name-error" class="error mt-2 text-danger" for="departamento_id">
                          {{$errors->first('departamento_id')}}
                        </label>
                      @endif
                  </div>
              </div>
            </div>
            <button type="submit" class="btn btn-primary submit">Salvar</button>  
          </form>
      </div>
    </div>
  </div>
</div>
@endsection
@push('custom-scripts')
<script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
<script type="text/javascript">
  $(document).ready(function() {
    if ($(".departamentos").length) {
    $(".departamentos").select2({
      tags:true
    });
    } 
   //esconde os filtros ao carregar a pagina
   $("#linhaFiltros").hide();
   $("#linhaRls").hide();
      //FILTRO
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