@extends('layout.parceiros.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
@endpush
@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('parceiro.relatorios', $relatorio->subgrupo_relatorio_id)}}">Relatórios</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{$relatorio->nome}}</li>
  </ol>
</nav>
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-3 mb-md-0">Permissões do Relatório</h4>
  </div>
  <div class="d-flex align-items-center flex-wrap text-nowrap">
    <button type="button" data-toggle="modal" data-target="#relatorioModal" class="btn btn-primary btn-icon-text">
      <i class="btn-icon-prepend" data-feather="check-square"></i>
     Adicionar Empresa
    </button>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
      <div id="relatorioModal" class="modal fade bd-example-modal-lg" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 id="modalTitle2" class="modal-title">Adicionar Empresa</h4>
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">Fechar</span></button>
          </div>
          <div id="modalBody2" class="modal-body">
            <form method="post" action="{{route('parceiro.relatorio.permissao.salvar')}}">
            <input type="hidden" name="id_relatorio" value="{{$relatorio->id}}">
            @csrf
            <div class="form-group {{$errors->has('tenants_id') ? 'has-danger' : ''}}">
                 <label class="control-label">Selecione as Empresas</label>
                    <select multiple="multiple" class="tenants w-100 form-control {{$errors->has('workspace_id') ? 'form-control-danger' : ''}}" name="tenants_id[]" id="tenants_id">
                    <option value="">Selecione as empresas </option>
                     @foreach($tenants as $tenant)
                             <option value="{{$tenant->id}}">{{$tenant->nome}}</option>
                    @endforeach
                    </select>
                    @if($errors->has('tenants_id'))
                    <label id="name-error" class="error mt-2 text-danger" for="tenants_id">
                      {{$errors->first('tenants_id')}}
                    </label>
                  @endif
              </div>
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
                <th>Empresa</th>
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
@if($errors->has('tenants_id'))
    <script type="text/javascript">
        $( document ).ready(function() {
             $('#relatorioModal').modal('show');
        });
    </script>
  @endif
  <script type="text/javascript">
  $(function () {

    if ($(".tenants").length) {
    $(".tenants").select2({
      width: '100%',
      dropdownParent: $('#relatorioModal .modal-content')
    });
    } 
    //tabela permissoes
    var table = $('#permisssoesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('parceiro.relatorio.permissao', request()->route('id'))}}",
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


