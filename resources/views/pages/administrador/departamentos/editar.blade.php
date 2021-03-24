@extends('layout.administradores.master')
@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('tenant.departamentos')}}">Departamentos</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{$departamento->nome}}</li>
  </ol>
</nav>
<div class="row">
  <div class="col-md-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Editar Departamento</h6>
          <form method="POST" action="{{route('tenant.departamento.atualizar', $departamento->id)}}">
          <input type="hidden" id="utilizafiltro" value="{{$departamento->utiliza_filtro}}" >
          @csrf 
          {{ method_field('PUT') }}
            <div class="row">
              <div class="col-sm-5">
                <div class="form-group {{$errors->has('nome') ? 'has-danger' : ''}}">
                  <label class="control-label">Departamento</label>
                  <input type="text" value="{{$departamento->nome}}" class="form-control {{$errors->has('nome') ? 'form-control-danger' : ''}}" name="nome" placeholder="Nome do Departamento">
                  @if($errors->has('nome'))
                    <label id="name-error" class="error mt-2 text-danger" for="nome">
                      {{$errors->first('nome')}}
                    </label>
                  @endif
                </div>
              </div><!-- Col -->
            </div><!-- Row -->
            <div class="row">
            <div class="col-sm-2">
              <div class="form-group">
                    <div class="custom-control custom-switch">
											<input type="checkbox" {{$departamento->utiliza_filtro == 'S' ? 'checked' : ''}} class="custom-control-input" value="on" name="utiliza_filtro" id="utilizaFiltroToggle">
											<label class="custom-control-label" for="utilizaFiltroToggle">Utiliza Filtro</label>
										</div>
								</div>
              </div><!-- Col -->
            </div>
            <div class="row" id="linhaFiltros">
              <div class="col-sm-4">
                <div class="form-group">
                  <label class="control-label">Tabela</label>
                  <input type="text" value="{{$departamento->filtro_tabela}}" name="filtro_tabela" id="tabela" class="form-control" placeholder="Tabela">
                </div>
              </div><!-- Col -->
              <div class="col-sm-4">
                <div class="form-group">
                  <label class="control-label">Coluna</label>
                  <input type="text" value="{{$departamento->filtro_coluna}}" name="filtro_coluna" id="coluna" class="form-control" placeholder="Coluna">
                </div>
              </div><!-- Col -->
              <div class="col-sm-4">
                <div class="form-group">
                  <label class="control-label">Valor</label>
                  <input type="text" value="{{$departamento->filtro_valor}}" name="filtro_valor" id="valor" class="form-control" placeholder="Valor">
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
   
    //utilizafiltro
   var utilizafiltro = $("#utilizafiltro").val();
   if(utilizafiltro == 'S'){
    $("#linhaFiltros").show();
   }else{
    $("#linhaFiltros").hide();
   }
  
      $("#utilizaFiltroToggle").change(function(){
          //VERIFICA SE UTILIZA FILTRO
          if($(this).prop("checked") == true){
            //se utiliza filtro mostro os campos
            $("#linhaFiltros").show();
          }else{
          //escondo os campos e apago os valores
          $("#linhaFiltros").hide();
          $("#tabela").val('');
          $("#coluna").val('');
          $("#valor").val('');
          }
      });
});
  </script>
@endpush