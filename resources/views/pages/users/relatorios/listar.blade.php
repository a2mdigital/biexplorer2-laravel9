@extends('layout.users.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
@endpush

@section('content')

 

<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('users.tenant.gruposrelatorio')}}">{{$grupo->nome}}</a></li>
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
                    { width: "70%", targets: 0 }
                ],
                ajax: "{{ route('users.tenant.relatorios', request()->route('id'))}}",
               columns: [
                
                    {data: 'nome', name: 'nome'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                language: {
                  url: getLanguage(),
                },
            });
        });
      </script>
@endpush