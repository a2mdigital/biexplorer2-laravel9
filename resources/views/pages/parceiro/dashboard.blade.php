@extends('layout.parceiros.master')
@section('titulo-pagina', 'Dashboard')
@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-3 mb-md-0">Dashboard</h4>
  </div>
</div>

<div class="row">
  <div class="col-12 col-xl-12 stretch-card">
    <div class="row flex-grow">
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-baseline">
              <h6 class="card-title mb-0">Empresas Cadastradas</h6>
              <div class="dropdown mb-2"><br></div>
            </div>
            <div class="row">
              <div class="col-6 col-md-12 col-xl-5">
                <h3 class="mb-2 contador">{{$total_empresas}}</h3>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 grid-margin stretch-card">
      <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-baseline">
              <h6 class="card-title mb-0">Total de Usuários</h6>
              <div class="dropdown mb-2"><br></div>
            </div>
            <div class="row">
              <div class="col-6 col-md-12 col-xl-5">
                <h3 class="mb-2 contador">{{$total_users}}</h3> 
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline">
                  <h6 class="card-title mb-0">Relatórios Cadastrados</h6>
                  <div class="dropdown mb-2"><br></div>
                </div>
                <div class="row">
                  <div class="col-6 col-md-12 col-xl-5">
                    <h3 class="mb-2 contador">{{$total_relatorios}}</h3>
                  </div>
                </div>
              </div>
          </div>
      </div>
    </div>
  </div>
</div> <!-- row -->

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Usuários</h6>
        <p class="card-description">Informações Adicionais</p>
        <div class="table-responsive pt-3">
          <table id="auditoriaTable" class="table table-bordered">
            <thead>
              <tr>
                <th>
                  Nome
                </th>
                <th>
                  Empresa
                </th>
                <th>
                  E-mail
                </th>
                <th>
                  Último Login
                </th>
              </tr>
            </thead>
            <tbody>
            @foreach($usuarios as $usuario)
              <tr>
                <td>
                 {{$usuario->name}}
                </td>
                <td>
                 {{$usuario->tenant->nome}}
                </td>
                <td>
                {{$usuario->email}}
                </td>
                @if($usuario->ultimo_login == '')
                  <td> - </td>
                      @else
                  <td>{{date("d/m/Y H:i", strtotime($usuario->ultimo_login))}}</td>
                @endif
              </tr>
            @endforeach
            </tbody>
          
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
  <script src="{{ asset('assets/js/dashboard.js') }}"></script>
  <script type="text/javascript">
 $(document).ready(function() {
      $('.contador').each(function () {
        $(this).prop('Counter',0).animate({
            Counter: $(this).text()
        }, {
            duration: 4000,
            easing: 'swing',
            step: function (now) {
                $(this).text(Math.ceil(now));
            }
        });
    });

    //DATATABLE
    $('#auditoriaTable').DataTable({
     
      "iDisplayLength": 10,
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