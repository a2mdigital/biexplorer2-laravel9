@extends('layout.parceiros.master')
@section('titulo-pagina', 'Customizar')
@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/datatables-net/buttons.dataTables.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-3 mb-md-0">Customização</h4>
  </div>
</div>
<div class="row">
    <div class="col-12 col-md-4 col-xl-6">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Trocar Imagens</h5>
          <p class="card-text">Customizar Imagens de Login, Background e Logo do Menu</p>
          <br>
          <a href="{{route('parceiro.customize.images')}}" class="btn btn-primary">Personalizar Imagens</a>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-6 col-xl-6">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Trocar Tema</h5>
          <p class="card-text">Customizar Cores do Portal</p>
          <br>
          <a href="{{route('parceiro.tema')}}" class="btn btn-primary">Personalizar Tema</a>
        </div>
      </div>
    </div>
  </div>
@endsection