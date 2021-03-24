@extends('layout.parceiros.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
@endpush

@section('content')


<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('parceiros.listar')}}">Parceiros</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{$parceiro->name}}</li>
  </ol>
</nav>

<div class="row">

  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
      
        <p class="card-description"></p>
        <div class="table-responsive">
          <table id="parceirosTable" class="parceirosTable">
            <thead>
              <tr>
                <th>Nome</th>
                <th>Total de Usuários</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
         <tr>
            <th>TOTAL DE USUÁRIOS</th>
            <th></th>
         </tr>
    </tfoot>
          </table>
        </div>
      </div>
    </div>
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
          
            var table = $('#parceirosTable').DataTable({
                processing: true,
                //serverSide: true,
                ajax: "{{ route('parceiro.tenants.listar', request()->route('id'))}}",
               columns: [
                    {data: 'nome', name: 'nome'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                footerCallback: function( row, data, start, end, display ) {
                  var api = this.api(), data;
 
                     // Total over all pages
                      total = api
                          .column( 1 )
                          .data()
                          .reduce( function (a, b) {
                              return a + b;
                          }, 0 );
                   
                      // Update footer
                      $( api.column( 1 ).footer() ).html(
                          ' ( '+ total +' total)'
                      );
                },
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