@extends('layout.administradores.master')
@section('titulo-pagina', 'Dashboard')
@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-3 mb-md-0"></h4>
  </div>
</div>
 
<div class="row">
  <div class="col-12 col-xl-12 stretch-card">
    <div class="row flex-grow">
      <div class="col-md-4 grid-margin stretch-card">
      <a href="{{ route('tenant.usuarios') }}">
      <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-baseline">
              <h6 class="card-title mb-0">{{__('messages.total_users')}}</h6>
              <div class="dropdown mb-2"><br></div>
            </div>
            <div class="row">
              <div class="col-6 col-md-12 col-xl-5">
                <h3 class="mb-2 contador">{{count($users)}}</h3> 
              </div>
            </div>
          </div>
        </div>
        </a>
      </div>
      <div class="col-md-4 grid-margin stretch-card">
      <a href="{{ route('tenant.gruposrelatorio') }}">
        <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline">
                  <h6 class="card-title mb-0">{{__('messages.reports_added')}}</h6>
                  <div class="dropdown mb-2"><br></div>
                </div>
                <div class="row">
                  <div class="col-6 col-md-12 col-xl-5">
                    <h3 class="mb-2 contador">{{$total_relatorios_tenant}}</h3>
                  </div>
                </div>
              </div>
        </div>
        </a>
      </div>
    </div>
  </div>
</div> <!-- row -->
<div class="row">
<div class="col-md-12">
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
      <div>
              <h4 class="mb-3 mb-md-0">{{__('messages.last_report_add')}}</h4>
      </div>
    </div>
</div>
</div>
<div class="linhaRecentes">
@foreach($relatorios as $relatorio)
 <div class="cartao">
    <div class="cabecalho-cartao">
      <div class="avatar">
      <i data-feather="star"></i>
      </div>
    <a href="{{ route('tenant.relatorios.visualizar',[$relatorio->subgrupo_relatorio_id ,$relatorio->id]) }}">
      <div class="cabecalho-texto">
        <div class="texto">
          <span>{{$relatorio->nome}}</span>
        </div>
      </div>
      <div class="subtitulo">
        @if($relatorio->tipo == 'relatorio')
        <div class="icone-subtitulo">
        <i class="icon-subtitulo" data-feather="bar-chart"></i>
        </div>
          <span class="texto-subtitulo">{{__('messages.type_report')}}</span>
         @endif
         @if($relatorio->tipo == 'dashboard')
        <div class="icone-subtitulo">
        <i class="icon-subtitulo" data-feather="pie-chart"></i>
        </div>
          <span class="texto-subtitulo">{{__('messages.type_dashboard')}}</span>
         @endif
      </div>
      <div class="informacoes">
        <div class="texto-informacao">{{__('messages.report_add_date')}}:</div>
        <span class="texto-subtitulo">{{$relatorio->created_at->format('d/m/Y')}}</span>
      </div>
      </a>  
    </div>
 </div>
@endforeach
</div>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">{{__('messages.title_table_users')}}</h6>
        <p class="card-description">{{__('messages.information_table_users')}}</p>
        <div class="table-responsive pt-3">
          <table id="auditoriaTable" class="table table-bordered">
            <thead>
              <tr>
                <th>
                  {{__('messages.name')}}
                </th>
                <th>
                  {{__('messages.departaments_table')}}
                </th>
                <th>
                  {{__('messages.email')}}
                </th>
                <th>
                  {{__('messages.last_login')}}
                </th>
              </tr>
            </thead>
            <tbody>
            @foreach($users as $usuario)
              <tr>
                <td>
                 {{$usuario->name}}
                </td>
                <td>
                 {{$usuario->departamento->nome}}
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

    function getLanguage(){
      var lang = $('html').attr('lang');
      if(lang == 'en'){
        var language = '{{asset("assets/lang/data-table-en.json")}}';
      }else{
        var language = '{{asset("assets/lang/data-table-pt.json")}}';
      }
      return language
    }
  
    //DATATABLE
    $('#auditoriaTable').DataTable({
      language: {
           url: getLanguage(),
        },
      pageLength : 5,
      lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
      
    });
  
 });
 </script>
@endpush