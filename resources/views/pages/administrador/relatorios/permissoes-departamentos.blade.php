@extends('layout.administradores.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
@endpush
@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('tenant.relatorios', $relatorio->subgrupo_relatorio_id)}}">{{__('messages.title_link_page_reports_permission')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{$relatorio->nome}}</li>
  </ol>
</nav>
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-3 mb-md-0">{{__('messages.title_page_reports_permission_departaments')}}</h4>
  </div>
  <div class="d-flex align-items-center flex-wrap text-nowrap">
    <button type="button" data-toggle="modal" data-target="#relatorioModal" class="btn btn-primary btn-icon-text">
      <i class="btn-icon-prepend" data-feather="check-square"></i>
      {{__('messages.btn_add_departament_report_permission')}}
    </button>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
      <div id="relatorioModal" class="modal fade bd-example-modal-lg" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 id="modalTitle2" class="modal-title"> {{__('messages.btn_add_departament_report_permission')}}</h4>
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">{{__('messages.btn_close_users_modal_report_permission')}}</span></button>
          </div>
          <div id="modalBody2" class="modal-body">
            <form method="post" action="{{route('tenant.relatorio.permissao.departamento.salvar')}}">
            <input type="hidden" name="id_relatorio" value="{{$relatorio->id}}">
            @csrf
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group {{$errors->has('departamento_id') ? 'has-danger' : ''}}">
                    <label class="control-label">{{__('messages.select_departaments_modal_report_permission')}}</label>
                        <select  class="departamentos w-100 form-control {{$errors->has('workspace_id') ? 'form-control-danger' : ''}}" name="departamento_id" id="departamento_id">
                        <option value="">{{__('messages.select_departaments_modal_report_permission')}}</option>
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
              <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
										<div class="custom-control custom-switch">
											<input type="checkbox" class="custom-control-input" value="on" name="utiliza_filtro" id="utilizaFiltroToggle">
											<label class="custom-control-label" for="utilizaFiltroToggle">{{__('messages.filters_report')}}</label>
										</div>
									</div>
              </div><!-- Col -->
              <div class="col-sm-4">
              <div class="form-group">
										<div class="custom-control custom-switch">
											<input type="checkbox" class="custom-control-input" value="on" name="utiliza_rls" id="utilizaRlsToggle">
											<label class="custom-control-label" for="utilizaRlsToggle">{{__('messages.rls_report')}}</label>
										</div>
									</div>
              </div><!-- Col -->
            </div><!-- row --> 
            <div class="row" id="linhaFiltros">
              <div class="col-sm-4">
                <div class="form-group">
                  <label class="control-label">{{__('messages.table_table_report_permission')}}</label>
                  <input type="text" name="filtro_tabela" id="tabela" class="form-control" placeholder="{{__('messages.table_table_report_permission')}}">
                </div>
              </div><!-- Col -->
              <div class="col-sm-4">
                <div class="form-group">
                  <label class="control-label">{{__('messages.column_table_report_permission')}}</label>
                  <input type="text" name="filtro_coluna" id="coluna" class="form-control" placeholder="{{__('messages.column_table_report_permission')}}">
                </div>
              </div><!-- Col -->
              <div class="col-sm-4">
                <div class="form-group">
                  <label class="control-label">{{__('messages.value_table_report_permission')}}</label>
                  <input type="text" name="filtro_valor" id="valor" class="form-control" placeholder="{{__('messages.value_table_report_permission')}}">
                </div>
              </div><!-- Col -->
            </div><!-- Row -->
            <div class="row" id="linhaRls">
              <div class="col-sm-4">
                <div class="form-group">
                  <label class="control-label">{{__('messages.role_rls_report')}}</label>
                  <input type="text" name="regra_rls" id="regra_rls" class="form-control" placeholder="{{__('messages.role_rls_report')}}">
                </div>
              </div><!-- Col -->
              <div class="col-sm-4">
                <div class="form-group">
                  <label class="control-label">Username</label>
                  <input type="text" name="username_rls" id="username_rls" class="form-control" placeholder="Username RLS">
                </div>
              </div><!-- Col -->
            </div><!-- Row -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">{{__('messages.btn_close_users_modal_report_permission')}}</button>
            <button type="submit" class="btn btn-primary">{{__('messages.btn_save_users_modal_report_permission')}}</button>
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
      
        <p class="card-description"></p>
        <div class="table-responsive">
          <table id="permisssoesTable" class="permisssoesTable">
            <thead>
              <tr>
                <th>{{__('messages.departament_table_report_permission')}}</th>
                <th>{{__('messages.table_table_report_permission')}}</th>
                <th>{{__('messages.column_table_report_permission')}}</th>
                <th>{{__('messages.value_table_report_permission')}}</th>
                <th>Rls</th>
                <th>Username</th>
                <th>{{__('messages.actions_table_report_permission')}}</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection

@push('custom-scripts')
<script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net-bs4/dataTables.bootstrap4.js') }}"></script>
@if($errors->has('departamento_id'))
    <script type="text/javascript">
        $( document ).ready(function() {
             $('#relatorioModal').modal('show');
        });
    </script>
  @endif
  <script type="text/javascript">
  $(function () {

    if ($(".departamentos").length) {
    $(".departamentos").select2({
      width: '100%',
      dropdownParent: $('#relatorioModal .modal-content')
    });
    } 

     //esconde os filtros ao carregar a pagina
     $("#linhaFiltros").hide();
    $("#linhaRls").hide();

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
        $("#filtro_tabela").val('');
        $("#filtro_coluna").val('');
        $("#filtro_valor").val('');
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
    function getLanguage(){
            var lang = $('html').attr('lang');
            if(lang == 'en'){
              var language = '{{asset("assets/lang/data-table-en.json")}}';
            }else{
              var language = '{{asset("assets/lang/data-table-pt.json")}}';
            }
            return language
          }
    //tabela permissoes
    var table = $('#permisssoesTable').DataTable({
              language: {
                  url: getLanguage(),
                },
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('tenant.relatorio.permissao.departamentos', request()->route('id'))}}",
               columns: [
                  
                    {data: 'nome', name: 'nome'},
                    {data: 'filtro_tabela', name: 'filtro_tabela'},
                    {data: 'filtro_coluna', name: 'filtro_coluna'},
                    {data: 'filtro_valor', name: 'filtro_valor'},
                    {data: 'regra_rls', name: 'regra_rls'},
                    {data: 'username_rls', name: 'username_rls'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
               
            });
            //fim tabela permissoes
  });
</script>  
@endpush


