@extends('layout.administradores.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Usuários</a></li>
    <li class="breadcrumb-item active" aria-current="page">Listar Usuários</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
      
        <p class="card-description"></p>
        <div class="table-responsive">
          <table id="usuariosTable" class="usuariosTable">
            <thead>
              <tr>
                <th>Usuário</th>
                <th>E-mail</th>
                <th>Departamento</th>
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
        <a href="{{route('tenant.usuario.cadastrar')}}" class="btn btn-primary btn-icon-text">
                <i class="btn-icon-prepend" data-feather="check-square"></i>
                {{__('messages.btn_add_user')}}
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
            var table = $('#usuariosTable').DataTable({
              "stripeClasses": [ 'strip1', 'strip2'],
                processing: true,
                serverSide: true,
                responsive: true,
                columnDefs: [
                    { width: "33%", targets: [0,1,2] }
                ],
                ajax: "{{ route('tenant.usuarios') }}",
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'departamento', name: 'departamento_id', searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                language: {
                  url: getLanguage(),
                },    
            });
        });
      </script>
@endpush