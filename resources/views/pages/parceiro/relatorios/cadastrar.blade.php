@extends('layout.parceiros.master')
@push('plugin-styles')
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush
@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('parceiro.relatorios', $subgrupo->id)}}">{{$subgrupo->nome}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">Cadastrar Relatório</li>
  </ol>
</nav>
<div class="row">
  <div class="col-md-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Cadastrar Relatório</h6>
          <form method="POST" action="{{route('parceiro.relatorio.salvar')}}">
          @csrf 
          <input type="hidden" name="nome_relatorio" id="nome_relatorio" value="">
          <input type="hidden" name="dataset_id" id="dataset_id" value="">
          <input type="hidden" name="subgrupo_relatorio_id" id="subgrupo_relatorio_id" value="{{$subgrupo->id}}">
            <div class="row">
              <div class="col-sm-5">
                <div class="form-group {{$errors->has('nome') ? 'has-danger' : ''}}">
                  <label class="control-label">Nome</label>
                  <input type="text" value="{{old('nome')}}" class="form-control {{$errors->has('nome') ? 'form-control-danger' : ''}}" name="nome" autofocus placeholder="Nome do Relatório">
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
                  <select class="form-control form-control-sm mb-3" name="tipo" id="tipo">
                    <option value="relatorio" selected>Relatório</option>
                    <option value="dashboard">Dashboard</option>
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
                             <option value="{{$work['id']}}">{{$work['name']}}</option>
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
              <div class="form-group {{$errors->has('report_id') ? 'has-danger' : ''}}" id="MostrarRelatorio">
                <label>Relatório</label>
                    <select class="relatorios w-100 form-control {{$errors->has('report_id') ? 'form-control-danger' : ''}}" name="report_id" id="report_id">
                        <option value="">Selecione um Workspace</option>
                    </select>
                    @if($errors->has('report_id'))
                    <label id="name-error" class="error mt-2 text-danger" for="report_id">
                      {{$errors->first('report_id')}}
                    </label>
                  @endif   
               </div>
               <div class="form-group {{$errors->has('dashboard_id') ? 'has-danger' : ''}}" id="MostrarDashboard">
                <label>Dashboard</label>
                    <select class="dashboards w-100 form-control {{$errors->has('dashboard_id') ? 'form-control-danger' : ''}}" name="dashboard_id" id="dashboard_id">
                        <option value="">Selecione um Workspace</option>
                    </select>
                    @if($errors->has('dashboard_id'))
                    <label id="name-error" class="error mt-2 text-danger" for="dashboard_id">
                      {{$errors->first('dashboard_id')}}
                    </label>
                  @endif   
               </div>
              </div><!-- Col -->
            </div>
            <div class="row">
              <div class="col-sm-4">
              <div class="form-group">
										<div class="custom-control custom-switch">
											<input type="checkbox" class="custom-control-input" value="on" name="filtro_lateral" id="filtroLateralToggle">
											<label class="custom-control-label" for="filtroLateralToggle">Habilitar Filtro Lateral</label>
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
              <div class="col-sm-2">
                  <div class="form-group">
                    <label class="control-label">Pegar filtros do:</label>
                    <select class="form-control form-control-sm mb-3" name="nivel_filtro" id="nivel_filtro">
                      <option value="filtro_relatorio" selected>Relatório</option>
                      <option value="filtro_usuario">Usuário</option>
                      <option value="filtro_departamento">Departamento</option>
                    </select>
                  </div>
              </div><!-- Col -->
            </div>
            <div class="row" id="linhaRls">
              <div class="col-sm-2">
                  <div class="form-group">
                    <label class="control-label">Pegar Rls do:</label>
                    <select class="form-control form-control-sm mb-3" name="nivel_rls" id="nivel_rls">
                      <option value="rls_relatorio" selected>Relatório</option>
                      <option value="rls_usuario">Usuário</option>
                    </select>
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
@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
@endpush
@push('custom-scripts')
<script type="text/javascript">
 $(document).ready(function() {
    if ($(".workspaces").length) {
    $(".workspaces").select2();
    } 
    if ($(".relatorios").length) {
    $(".relatorios").select2();
    } 
    if ($(".dashboards").length) {
    $(".dashboards").select2();
    }
    /* MOSTRAR TIPO DO RELATÓRIO */
    $('#MostrarDashboard').hide();
    $('#tipo').on('change', function() {
    var selecionado = this.value;
    if(selecionado == 'relatorio'){
         $('#MostrarDashboard').hide();
         $('#MostrarRelatorio').show();
         $('#report_id').html('<option>Selecione um Workspace</option');
    }else{
          $('#MostrarDashboard').show();
          $('#MostrarRelatorio').hide();
          $('#report_id').html('<option>Selecione um Workspace</option');
    } 
    }); 


   
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