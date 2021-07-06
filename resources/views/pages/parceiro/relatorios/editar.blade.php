@extends('layout.parceiros.master')
@push('plugin-styles')
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush
@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('parceiro.relatorios', $relatorio->subgrupo_relatorio_id)}}">Relatórios</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{$relatorio->nome}}</li>
  </ol>
</nav>
<div class="row">
  <div class="col-md-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Editar Relatório</h6>
          <form method="POST" action="{{route('parceiro.relatorio.atualizar', $relatorio->id)}}">
          {{ method_field('PUT') }}
          @csrf 
          <input type="hidden" name="nome_relatorio" id="nome_relatorio" value="">
          <input type="hidden" name="dataset_id" id="dataset_id" value="">
          <input type="hidden" name="subgrupo_relatorio_id" id="subgrupo_relatorio_id" value="{{$relatorio->subgrupo_relatorio_id}}">
          <input type="hidden" name="utilizafiltrorls" id="utilizafiltrorls" value="{{$relatorio->utiliza_filtro_rls}}"> 
          <input type="hidden" name="nivelfiltrorls" id="nivelfiltrorls" value="{{$nivel_filtro_rls}}"> 
          <input type="hidden" name="grupo_relatorio_id" id="grupo_relatorio_id" value="{{$grupo_relatorio_id}}">
            <div class="row">
              <div class="col-sm-4">
                <div class="form-group {{$errors->has('nome') ? 'has-danger' : ''}}">
                  <label class="control-label">Nome</label>
                  <input type="text" value="{{$relatorio->nome}}" class="form-control {{$errors->has('nome') ? 'form-control-danger' : ''}}" name="nome" placeholder="Nome do Relatório">
                  @if($errors->has('nome'))
                    <label id="name-error" class="error mt-2 text-danger" for="nome">
                      {{$errors->first('nome')}}
                    </label>
                  @endif
                </div>
              </div><!-- Col -->
              <div class="col-sm-2">
                <div class="form-group">
                  <label class="control-label">Tipo</label>
                  @if($relatorio->tipo == 'relatorio')
                  <input type="text" class="form-control" readonly value="Relatório">
                  @else
                  <input type="text" class="form-control" readonly value="Dashboard">
                  @endif
                </div>
              </div><!-- Col -->
            </div><!-- Row -->
            <div class="row">
              <div class="col-sm-4">
              <div class="form-group">
										<div class="custom-control custom-switch">
											<input type="checkbox" {{$relatorio->filtro_lateral == 'S' ? 'checked' : ''}} class="custom-control-input" value="on" name="filtro_lateral" id="filtroLateralToggle">
											<label class="custom-control-label" for="filtroLateralToggle">Habilitar Filtro Lateral</label>
										</div>
									</div>
              </div><!-- Col -->
            </div><!-- Row -->
            <div class="row">
              <div class="col-sm-2">
              <div class="form-group">
                    <div class="custom-control custom-switch">
											<input type="checkbox" {{$relatorio->utiliza_filtro_rls == 'S' && $nivel_filtro_rls =='filtro' ? 'checked' : ''}} class="custom-control-input" value="on" name="utiliza_filtro" id="utilizaFiltroToggle">
											<label class="custom-control-label" for="utilizaFiltroToggle">Utiliza Filtro</label>
										</div>
									</div>
              </div><!-- Col -->
              <div class="col-sm-2">
              <div class="form-group">
										<div class="custom-control custom-switch">
											<input type="checkbox" {{$relatorio->utiliza_filtro_rls == 'S' && $nivel_filtro_rls =='rls' ? 'checked' : ''}} class="custom-control-input" value="on" name="utiliza_rls" id="utilizaRlsToggle">
											<label class="custom-control-label" for="utilizaRlsToggle">Utiliza Rls</label>
										</div>
									</div>
              </div><!-- Col -->
            </div><!-- Row -->
            <div class="row" id="linhaFiltros">
              <div class="col-sm-2">
                  <div class="form-group">
                    <label class="control-label">Pegar filtros do:</label>
                    <select class="form-control form-control-sm mb-3" name="nivel_filtro" id="nivel_filtro">
                      <option value="filtro_relatorio" {{$relatorio->nivel_filtro_rls == 'filtro_relatorio' ? 'selected' : ''}}>Relatório</option>
                      <option value="filtro_usuario" {{$relatorio->nivel_filtro_rls == 'filtro_usuario' ? 'selected' : ''}}>Usuário</option>
                      <option value="filtro_departamento" {{$relatorio->nivel_filtro_rls == 'filtro_departamento' ? 'selected' : ''}}>Departamento</option>
                    </select>
                  </div>
              </div><!-- Col -->
            </div>
            <div class="row" id="linhaRls">
              <div class="col-sm-2">
                  <div class="form-group">
                    <label class="control-label">Pegar Rls do:</label>
                    <select class="form-control form-control-sm mb-3" name="nivel_rls" id="nivel_rls">
                      <option value="rls_relatorio"  {{$relatorio->nivel_filtro_rls == 'rls_relatorio' ? 'selected' : ''}}>Relatório</option>
                      <option value="rls_usuario"  {{$relatorio->nivel_filtro_rls == 'rls_usuario' ? 'selected' : ''}}>Usuário</option>
                    </select>
                  </div>
              </div><!-- Col -->
            </div>
            <div class="row">
              <div class="col-sm-4">
                  <div class="form-group {{$errors->has('subgrupo_relatorio_id') ? 'has-danger' : ''}}">
                    <label class="control-label">Selecione o Grupo</label>
                        <select class="subgrupo-relatorio w-100 form-control {{$errors->has('subgrupo_relatorio_id') ? 'form-control-danger' : ''}}" name="subgrupo_relatorio_id" id="subgrupo_relatorio_id">
                        <option value="">Selecione o Grupo</option>
                        @foreach($subgrupos as $subgrupo)
                          @if($relatorio->subgrupo_relatorio_id == $subgrupo->id)
                                <option value="{{$subgrupo->id}}" selected="selected">{{$subgrupo->nome}}</option>
                          @else 
                          <option value="{{$subgrupo->id}}">{{$subgrupo->nome}}</option>
                          @endif      
                        @endforeach
                        </select>
                        @if($errors->has('subgrupo_relatorio_id'))
                        <label id="name-error" class="error mt-2 text-danger" for="departamento_id">
                          {{$errors->first('subgrupo_relatorio_id')}}
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
@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
@endpush
@push('custom-scripts')
<script type="text/javascript">
 $(document).ready(function() {
  if ($(".subgrupo-relatorio").length) {
    $(".subgrupo-relatorio").select2({
      tags:true
    });
    } 
      //esconde os filtros ao carregar a pagina
   $("#linhaFiltros").hide();
   $("#linhaRls").hide();   
   var utiliza_filtro_rls = $("#utilizafiltrorls").val();
   var nivel_filtro_rls = $("#nivelfiltrorls").val();
   if(utiliza_filtro_rls == 'S' && nivel_filtro_rls == 'filtro'){
    $("#linhaFiltros").show();
   }else if(utiliza_filtro_rls == 'S' && nivel_filtro_rls == 'rls'){
    $("#linhaRls").show();  
   }
   
      //FILTRO
      $("#utilizaFiltroToggle").change(function(){
          //VERIFICA SE UTILIZA FILTRO
          if($(this).prop("checked") == true){
            //se utiliza filtro mostro os campos
            $("#linhaFiltros").show();
            $("#linhaRls").hide();
            $("#utilizaRlsToggle").prop( "checked", false );
            $("#nivel_rls").val('');
            $("#nivel_filtro").val('filtro_relatorio');
          }else{
          //escondo os campos e apago os valores
          $("#linhaFiltros").hide();
          $("#nivel_filtro").val('');
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
          $("#nivel_filtro").val('');
          $("#nivel_rls").val('rls_relatorio');
        }else{
        //escondo os campos e apago os valores
        $("#linhaRls").hide();
        $("#nivel_rls").val('');
        }
    });
  
});
</script>
@endpush