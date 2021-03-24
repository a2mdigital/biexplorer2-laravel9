@extends('layout.users.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
@endpush
@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('tenant.relatorios', $relatorio->subgrupo_relatorio_id)}}">Relatórios</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{$relatorio->nome}}</li>
  </ol>
</nav>
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-3 mb-md-0">Permissões do Relatório - Departamentos</h4>
  </div>
  <div class="d-flex align-items-center flex-wrap text-nowrap">
    <button type="button" data-toggle="modal" data-target="#relatorioModal" class="btn btn-primary btn-icon-text">
      <i class="btn-icon-prepend" data-feather="check-square"></i>
     Adicionar Departamento
    </button>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
      <div id="relatorioModal" class="modal fade bd-example-modal-lg" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 id="modalTitle2" class="modal-title">Adicionar Departamento</h4>
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">Fechar</span></button>
          </div>
          <div id="modalBody2" class="modal-body">
            <form method="post" action="{{route('tenant.relatorio.permissao.departamento.salvar')}}">
            <input type="hidden" name="id_relatorio" value="{{$relatorio->id}}">
            @csrf
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group {{$errors->has('departamento_id') ? 'has-danger' : ''}}">
                    <label class="control-label">Selecione um Departamento</label>
                        <select  class="departamentos w-100 form-control {{$errors->has('workspace_id') ? 'form-control-danger' : ''}}" name="departamento_id" id="departamento_id">
                        <option value="">Selecione um Departamento </option>
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
											<label class="custom-control-label" for="utilizaFiltroToggle">Utiliza Filtro</label>
										</div>
									</div>
              </div><!-- Col -->
              <div class="col-sm-4">
              <div class="form-group">
										<div class="custom-control custom-switch">
											<input type="checkbox" class="custom-control-input" value="on" name="utiliza_rls" id="utilizaRlsToggle">
											<label class="custom-control-label" for="utilizaRlsToggle">Utiliza Rls</label>
										</div>
									</div>
              </div><!-- Col -->
            </div><!-- row --> 
            <div class="row" id="linhaFiltros">
              <div class="col-sm-4">
                <div class="form-group">
                  <label class="control-label">Tabela</label>
                  <input type="text" name="filtro_tabela" id="tabela" class="form-control" placeholder="Tabela">
                </div>
              </div><!-- Col -->
              <div class="col-sm-4">
                <div class="form-group">
                  <label class="control-label">Coluna</label>
                  <input type="text" name="filtro_coluna" id="coluna" class="form-control" placeholder="Coluna">
                </div>
              </div><!-- Col -->
              <div class="col-sm-4">
                <div class="form-group">
                  <label class="control-label">Valor</label>
                  <input type="text" name="filtro_valor" id="valor" class="form-control" placeholder="Valor">
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
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            <button type="submit" class="btn btn-primary">Salvar</button>
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
                <th>Usuários</th>
                <th>Ações</th>
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
 
    //tabela permissoes
    var table = $('#permisssoesTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                columnDefs: [
                    { width: "100%", targets: 0 }
                ],
                ajax: "{{ route('tenant.relatorio.permissao.departamentos', request()->route('id'))}}",
               columns: [
                  
                    {data: 'nome', name: 'nome'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                oLanguage: {
                sEmptyTable: "Nenhum registro encontrado",
                sInfo: "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                sInfoEmpty: "Mostrando 0 até 0 de 0 registros",
                sInfoFiltered: "(Filtrados de _MAX_ registros)",
                sInfoPostFix: "",
                sInfoThousands: ".",
                sLengthMenu: "_MENU_ Resultados por página",
                sLoadingRecords: "Carregando...",
                sProcessing: "Processando...",
                sZeroRecords: "Nenhum registro encontrado",
                sSearch: "Pesquisar",
                oPaginate: {
                  sNext: "Próximo",
                  sPrevious: "Anterior",
                  sFirst: "Primeiro",
                  sLast: "Último"
                },
                oAria: {
                  sSortAscending: ": Ordenar colunas de forma ascendente",
                  sSortDescending: ": Ordenar colunas de forma descendente"
                }
              }
            });
            //fim tabela permissoes
  });
</script>  
@endpush


