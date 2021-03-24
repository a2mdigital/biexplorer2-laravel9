@extends('layout.users.master')

@push('plugin-styles')
<link href="https://itsjavi.com/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.css?v=1575468400921" rel="stylesheet">
@endpush
@section('content')

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-3 mb-md-0">Relatórios</h4>
  </div>
</div>
<div class="row">
@foreach($grupos as $grupo)
<div class="col-md-3 col-sm-3 col-xs-3">
  <div class="card text-white" style="margin-left:4px; margin-bottom:6px; background-color: {{$grupo->cor}};">
    <div class="card-header">
     <div class="row">
        <div class="col-md-8 col-sm-8 col-xs-8">
        {{$grupo->nome}}
        </div>
     </div>
    </div>
    <div class="card-body">
    <center><a href={{route('users.tenant.relatorios', $grupo->id)}} class="btn btn-outline-light">Acessar</a></center>
    </div>
 </div>   
</div>
@endforeach
</div>
@endsection


