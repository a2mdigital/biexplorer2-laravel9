@extends('layout.parceiros.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
@endpush

@section('content')


<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('parceiro.gruposrelatorio')}}">{{$grupo->nome}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('parceiro.subgrupos.relatorios', $subgrupo->grp_rel_parceiro_id)}}">{{$subgrupo->nome}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">Relatórios</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
      
        <p class="card-description"></p>
        <div class="table-responsive">
          <table id="relatoriosTable" class="relatoriosTable">
            <thead>
              <tr>
                <th>#</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Utiliza Filtro/RLS</th>
                <th>Tipo de Filtro/RLS</th>
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
<div class="row">
<div class="col-md-5">
        <a href="{{route('parceiro.relatorio.cadastrar', $subgrupo->id)}}" class="btn btn-primary btn-icon-text">
                <i class="btn-icon-prepend" data-feather="check-square"></i>
                Cadastrar Relatório
        </a>
</div>
</div>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs4/dataTables.bootstrap4.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
  <script type="text/javascript">
        $(document).ready(function() {
         
            var table = $('#relatoriosTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('parceiro.relatorios', request()->route('id'))}}",
               columns: [
                   // {data: 'id', name: 'id', orderable: false, searchable: false},
                    { data: "id", render: function (data, type, row) {
                      return '<a href="/parceiro/admin/relatorio/'+row.id+'/permissoes" data-toggle="tooltip"  data-placement="top" title="Permissões"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg></a>';
                   
                      }
                    },
                    {data: 'nome', name: 'nome'},
                    {data: 'descricao', name: 'descricao'},
                    {data: "utiliza_filtro_rls",
                        render: function (data, type, row) {
                            if (data == 'S') {
                                return 'Sim';
                            }
                            if (data == 'N') {
                                return 'Não';
                            }
                            if (data == '') {
                                return 'Não';
                            }
                        }
                    },
                    {data: "nivel_filtro_rls",
                        render: function (data, type, row) {
                            if (data == 'filtro_relatorio') {
                                return 'Filtro no Relatório';
                            }
                            if (data == 'filtro_usuario') {
                                return 'Filtro do Usuário';
                            }
                            if (data == 'filtro_departamento') {
                                return 'Filtro do Departamento';
                            }
                            if (data == 'rls_relatorio') {
                                return 'Rls do Relatório';
                            }
                            if (data == 'rls_usuario') {
                                return 'Rls do Usuário';
                            }
                            if(data == ''){
                               return 'Sem Filtro / RLS'
                            }
                        }
                    },
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
        });
      </script>
@endpush