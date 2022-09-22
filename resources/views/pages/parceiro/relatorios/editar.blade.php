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
                  <select class=" w-100 form-control" name="tipo" id="tipo">
                  @if($relatorio->tipo == 'relatorio')
                  <option value="relatorio" selected="selected">Relatório</option>
                  @else
                  <option value="dashboard" selected="selected">Dashboard</option>
                  @endif
                  </select>
                </div> 
              </div><!-- Col -->
            </div><!-- Row -->
            <div class="row">
            <div class="col-sm-5">
                <div class="form-group {{$errors->has('workspace_id') ? 'has-danger' : ''}}">
                <label>Workspace</label>
                    <select class="workspaces w-100 form-control {{$errors->has('workspace_id') ? 'form-control-danger' : ''}}" name="workspace_id" id="workspace_id">
                    <option value="">Selecione um Workspace </option>
                    @foreach($workspaces["value"] as $work)
         
                        @if($work['id'] == $relatorio->workspace_id)
                                <option value="{{$work['id']}}" selected="selected">{{$work['name']}}</option>
                          @else 
                          <option value="{{$work['id']}}">{{$work['name']}}</option>
                          @endif      
                             
                    @endforeach
                    </select>
                    @if($errors->has('workspace_id'))
                    <label id="name-error" class="error mt-2 text-danger" for="workspace_id">
                      {{$errors->first('workspace_id')}}
                    </label>
                  @endif
               </div>
              </div><!-- Col -->

              <div class="col-sm-5">
              @if($relatorio->tipo == 'relatorio')
                <div class="form-group {{$errors->has('report_id') ? 'has-danger' : ''}}" id="MostrarRelatorio">
                  <label>Relatório</label>
                      <select class="relatorios w-100 form-control {{$errors->has('report_id') ? 'form-control-danger' : ''}}" name="report_id" id="report_id">
                          <option value="">Selecione um Workspace</option>
                          @foreach($todosRelatorios as $todos)
                            @if($todos['id'] == $relatorio->report_id)
                                    <option value="{{$todos['id']}}" dataset="{{$todos['datasetId']}}" selected="selected">{{$todos['name']}}</option>
                              @else 
                              <option value="{{$todos['id']}}" dataset="{{$todos['datasetId']}}">{{$todos['name']}}</option>
                            @endif      
                              
                       @endforeach
                      </select>
                      @if($errors->has('report_id'))
                      <label id="name-error" class="error mt-2 text-danger" for="report_id">
                        {{$errors->first('report_id')}}
                      </label>
                    @endif   
                </div>
               @else
                <div class="form-group {{$errors->has('dashboard_id') ? 'has-danger' : ''}}" id="MostrarDashboard">
                  <label>Dashboard</label>
                      <select class="dashboards w-100 form-control {{$errors->has('dashboard_id') ? 'form-control-danger' : ''}}" name="dashboard_id" id="dashboard_id">
                          <option value="">Selecione um Workspace</option>
                          @foreach($todosRelatorios as $todos)
                            @if($todos['id'] == $relatorio->report_id)
                                    <option value="{{$todos['id']}}" selected="selected">{{$todos['displayName']}}</option>
                              @else 
                              <option value="{{$todos['id']}}">{{$todos['displayName']}}</option>
                            @endif      
                              
                         @endforeach
                      </select>
                      @if($errors->has('dashboard_id'))
                      <label id="name-error" class="error mt-2 text-danger" for="dashboard_id">
                        {{$errors->first('dashboard_id')}}
                      </label>
                    @endif   
                </div>
               @endif
              </div><!-- Col -->
              
            </div>
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
              <div class="col-sm-4">
              <div class="form-group">
										<div class="custom-control custom-switch">
											<input type="checkbox" {{$relatorio->ignora_filtro_rls == 'S' ? 'checked' : ''}} class="custom-control-input" value="on" name="ignora_filtro_rls" id="ignoraRlsToggle">
											<label class="custom-control-label" for="ignoraRlsToggle">Ignora Rls da empresa</label>
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

    if ($(".workspaces").length) {
    $(".workspaces").select2();
    } 
    if ($(".relatorios").length) {
    $(".relatorios").select2();
    } 
    if ($(".dashboards").length) {
    $(".dashboards").select2();
    }
  
  /* SELECIONAR WORKSPACE */
  $('select[name=workspace_id]').change(function () {
            var workspace_id = $(this).val();
            var tipoSelecionado = $("#tipo option:selected").val();
           
            if(tipoSelecionado == 'relatorio'){
            $('#report_id').html('<option>Carregando...</option>');
            //$.get('/admin/admin/powerbi/buscarRelatorios/' + workspace_id, function (relatorios) {
              $.get("{{route('parceiro.powerbi.buscarelatorios', '')}}"+"/"+workspace_id,  function (relatorios) {
                $('select[name=report_id]').empty();
                $('#report_id').html('<option value="">Selecione um Relatorio</option>');
                $.each(relatorios, function (key, value) {
                    $('select[name=report_id]').append('<option value=' + value['id'] + ' dataset='+ value['datasetId'] +' >' + value['name'] + '</option>');
                });
            });
           }else{
            $('#dashboard_id').html('<option>Carregando...</option');
            //$.get('/admin/admin/powerbi/buscarDashboards/' + workspace_id, function (dashboards) {
              $.get("{{route('parceiro.powerbi.buscarDashboards', '')}}"+"/"+workspace_id, function (dashboards) {
                $('select[name=dashboard_id]').empty();
                $('#dashboard_id').html('<option value="">Selecione um Dashboard</option');
                $.each(dashboards, function (key, value) {
                    $('select[name=dashboard_id]').append('<option value=' + value['id'] + '>' + value['displayName'] + '</option>');
                });
            });
           }
    });

          var tipoSelecionado = $("#tipo option:selected").val();
           
           if(tipoSelecionado == 'relatorio'){
             var selected = $("#report_id option:selected").text();
             var dataset_id = $("#report_id option:selected").attr("dataset");
             $("#dataset_id").val(dataset_id);
             $("#nome_relatorio").val(selected);
           }else{
             var selected = $("#dashboard_id option:selected").text();
             $("#nome_relatorio").val(selected);
           } 

    $('select[name=report_id]').change(function () {
             var selected = $(this).find("option:selected").text();
             var dataset_id = $(this).find("option:selected").attr("dataset");
             $("#dataset_id").val(dataset_id);
             $("#nome_relatorio").val(selected);
    });

    $('select[name=dashboard_id]').change(function () {
             var selected = $(this).find("option:selected").text();
             $("#nome_relatorio").val(selected);
    });
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