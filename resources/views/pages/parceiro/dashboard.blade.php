@extends('layout.parceiros.master')
@section('titulo-pagina', 'Dashboard')
@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/datatables-net/buttons.dataTables.min.css') }}" rel="stylesheet" />
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
              <h6 class="card-title mb-0">Total de {{__('messages.title_table_users')}}</h6>
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
                  <h6 class="card-title mb-0">{{__('messages.reports_added')}}</h6>
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
        <h6 class="card-title">{{__('messages.title_table_users')}}</h6>
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
                  <td></td>
                      @else
                  <td>{{$usuario->ultimo_login}}</td>
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
<script src="{{ asset('assets/plugins/datatables-net/dataTables.buttons.min.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.21/dataRender/datetime.js"></script>

<script src="{{ asset('assets/plugins/datatables-net/jszip.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net/buttons.html5.min.js') }}"></script>

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

    function getLanguage(){
      var lang = $('html').attr('lang');
      if(lang == 'en'){
        var language = '{{asset("assets/lang/data-table-en.json")}}';
      }else if(lang == 'pt_PT'){
        var language = '{{asset("assets/lang/data-table-pt_pt.json")}}';
      }else{
        var language = '{{asset("assets/lang/data-table-en.json")}}';
      }
      return language
    }
 
    //DATATABLE
     //DATATABLE
     $('#auditoriaTable').DataTable({
      language: {
           url: getLanguage(),
        },
        dom: 'Bfrtip',
        buttons: [
            {
              extend:'excelHtml5',
              title: 'Users'
            },
            {
              extend:'pdfHtml5',
              title: 'Users'
              }
        ], 
      pageLength : 10,
      lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
      columnDefs: [{
        render: $.fn.dataTable.render.moment('YYYY-MM-DD HH:mm:ss', 'DD/MM/YYYY HH:mm:ss'),
        targets: 3
      }], 
    });
    /*
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
    */
  
 });
 </script>
@endpush