@extends('layout.administradores.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
@endpush

@section('content')



<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('tenant.gruposrelatorio')}}">{{$grupo->nome}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('messages.title_page_groups')}}</li>
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
                <th>{{__('messages.permissions_report')}}</th>
                <th>{{__('messages.report_table_name')}}</th>
                <th>{{__('messages.report_table_actions')}}</th>
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
            var table = $('#relatoriosTable').DataTable({
              language: {
                  url: getLanguage(),
                },
                processing: true,
                serverSide: true,
                responsive: true,
                columnDefs: [
                    { width: "70%", targets: [1] }
                ],
                ajax: "{{ route('tenant.relatorios', request()->route('id'))}}",
               columns: [
                
                   // {data: 'id', name: 'id', orderable: false, searchable: false},
                    { data: "id", render: function (data, type, row) {
                      var botoes = '<div style="display: flex; justify-content:flex-start;">';
                      botoes += ' <a href="/admin/tenant/relatorio/'+row.id+'/permissoes/usuarios" style="margin-right:5px;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg></a>';
                      botoes += ' <a href="/admin/tenant/relatorio/'+row.id+'/permissoes/departamentos" style="margin-right:5px;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-briefcase link-icon"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg></a>';
                      botoes += '</div>';   
                  
                      return botoes;
                      }
                    },
                    {data: 'nome', name: 'nome'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
              
            });
        });
      </script>
@endpush