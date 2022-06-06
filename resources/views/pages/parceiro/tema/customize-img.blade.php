@extends('layout.parceiros.master')
@section('titulo-pagina', 'Customizar')
@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/datatables-net/buttons.dataTables.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('parceiro.customize')}}">Customizar</a></li>
    <li class="breadcrumb-item active" aria-current="page">Editar Imagens</li>
  </ol>
</nav> 
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-3 mb-md-0">Trocar Imagens</h4>
  </div>
</div>
<div class="row">
<div class="col-md-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">

        <h6 class="card-title">#</h6>

        <form class="forms-sample">
          <div class="mb-3">
            <label for="exampleInputUsername1" class="form-label">Logo - Página de Login</label>
            <input type="text" class="form-control" id="exampleInputUsername1" autocomplete="off" placeholder="Username">
          </div>
          <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Background - Página de Login</label>
            <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email">
          </div>
          <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Logo - Menu</label>
            <input type="password" class="form-control" id="exampleInputPassword1" autocomplete="off" placeholder="Password">
          </div>
          <div class="form-check mb-3">
            <input type="checkbox" class="form-check-input" id="exampleCheck1">
            <label class="form-check-label" for="exampleCheck1">
              Remember me
            </label>
          </div>
          <button type="submit" class="btn btn-primary me-2">Submit</button>
          <button class="btn btn-secondary">Cancel</button>
        </form>

      </div>
    </div>
  </div>
</div>
@endsection