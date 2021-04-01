@extends('layout.users.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">{{__('messages.title_link_playlist')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('messages.actual_page_playlist')}}</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
      
        <p class="card-description"></p>
        <div class="table-responsive">
          <table id="playlistsTable" class="playlistsTable">
            <thead>
              <tr>
                <th>{{__('messages.table_playlist')}}</th>
                <th>{{__('messages.table_time_playlist')}}</th>
                <th>{{__('messages.table_actions_playlist')}}</th>
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
              }else{
                var language = '{{asset("assets/lang/data-table-pt.json")}}';
              }
              return language
            }
            var table = $('#playlistsTable').DataTable({
              language: {
                url: getLanguage(),
              },
              "stripeClasses": [ 'strip1', 'strip2'],
                processing: true,
                serverSide: true,
                responsive: true,
               
                ajax: "{{ route('users.tenant.playlists') }}",
                columns: [
                    {data: 'nome', name: 'nome'},
                    {data: 'tempo_atualizacao', name: 'tempo_atualizacao'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
            });
        });
      </script>
@endpush