@extends('layout.parceiros.master')

@push('plugin-styles')
<link href="https://itsjavi.com/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.css?v=1575468400921" rel="stylesheet">
@endpush
@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('parceiro.gruposrelatorio')}}">{{$grupo->nome}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">SubGrupos</li>
  </ol>
</nav>
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-3 mb-md-0">SubGrupos</h4>
  </div>
  <div class="d-flex align-items-center flex-wrap text-nowrap">
    <button type="button" data-toggle="modal" data-target="#subGrupoModal" class="btn btn-primary btn-icon-text">
      <i class="btn-icon-prepend" data-feather="check-square"></i>
      Criar SubGrupo
    </button>
    <form action="{{route('parceiro.gruposrelatorio.excluir', $grupo->id)}}" method="POST">
                        @csrf
                        {{ method_field('DELETE') }}
     <button type="submit"  data-toggle="tooltip" data-placement="top" title="Excluir Grupo Atual"  onclick="return confirm('Excluir o Grupo apagará todos os relatórios dentro dele, Excluir?')" class="btn btn-danger btn-icon" style="margin-left: 2px;">
      <i data-feather="trash"></i>
    </button>
    </form>
  </div>
</div>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <!-- MODAL SUBGRUPO CADASTRO -->
      <div id="subGrupoModal" class="modal fade">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h4 id="modalTitle2" class="modal-title">Criar SubGrupo</h4>
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">Fechar</span></button>
          </div>
          <div id="modalBody2" class="modal-body">
            <form method="post" action="{{route('parceiro.subgruporelatorio.salvar')}}">
            <input type="hidden" name="id_grupo" value="{{$grupo->id}}">
            @csrf
              <div class="form-group {{$errors->has('nome') ? 'has-danger' : ''}}">
                <label for="nome">Nome do SubGrupo</label>
                <input type="text" class="form-control {{$errors->has('nome') ? 'form-control-danger' : ''}}" id="nome" name="nome" autofocus placeholder="Nome do SubGrupo">
                @if($errors->has('nome'))
                    <label id="name-error" class="error mt-2 text-danger" for="nome">
                      {{$errors->first('nome')}}
                    </label>
                  @endif
              </div>
              <div class="form-group">
              <label for="nome">Cor</label>
              <div id="cor" class="input-group colorpicker-component">
                    <input type="text" name="cor" value="#727cf5" class="form-control" />
                    <span class="input-group-append">
                <span class="input-group-text colorpicker-input-addon" data-original-title="" title="" tabindex="0"><i style="background-color: rgb(153, 191, 167); background-position: initial initial; background-repeat: initial initial;"></i></span>
              </span>
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            <button type="submit" class="btn btn-primary">Salvar</button>
          </div>
          </form>
        </div>
      </div>
    </div>
    <!-- FIM MODAL SUBGRUPO CADASTRO -->
     <!-- MODAL EDIT -->
     <div id="subGrupoModalEdit" class="modal fade">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h4 id="modalTitle2" class="modal-title">Atualizar SubGrupo</h4>
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">Fechar</span></button>
          </div>
          <div id="modalBody2" class="modal-body">
            <form method="post" action="{{route('parceiro.subgruporelatorio.atualizar')}}">
              <input type="hidden" name="idSubGrupoEdit" id="idSubGrupoEdit" value="">
              {{ method_field('PUT') }}
              @csrf
              <div class="form-group {{$errors->has('nomeEdit') ? 'has-danger' : ''}}">
                <label for="nome">Nome do SubGrupo</label>
                <input type="text" class="form-control {{$errors->has('nome') ? 'form-control-danger' : ''}}" id="nomeEdit" name="nomeEdit" placeholder="Nome do SubGrupo">
                @if($errors->has('nomeEdit'))
                    <label id="name-error" class="error mt-2 text-danger" for="nomeEdit">
                      {{$errors->first('nomeEdit')}}
                    </label>
                  @endif
              </div>
              <div class="form-group">
              <label for="nome">Cor</label>
              <div id="corEdit" class="input-group colorpicker-component">
                    <input type="text" name="corEdit" id="corEditInput" value="#727cf5" class="form-control" />
                    <span class="input-group-append">
                <span class="input-group-text colorpicker-input-addon" data-original-title="" title="" tabindex="0"><i style="background-color: rgb(153, 191, 167); background-position: initial initial; background-repeat: initial initial;"></i></span>
              </span>
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            <button type="submit" class="btn btn-primary">Salvar</button>
          </div>
          </form>
        </div>
      </div>
    </div>
    <!-- FIM MODAL EDIT --> 
  </div>
</div>

<div class="row">
@foreach($subgrupos as $subgrupo)
<div class="col-md-4 col-sm-4 col-xs-3">
  <div class="card text-white" style="margin-left:4px; margin-bottom:6px; background-color: {{$subgrupo->cor}};">
  <div class="card-header">
     <div class="row">
        <div class="col-md-8 col-sm-8 col-xs-8">
        {{$subgrupo->nome}}
        </div>
        <div style="display: flex; justify-content:flex-start" class="col-md-4 col-sm-4 col-xs-4 float-right" id="link-card">
        <a href="#" class="link-card" data-id="{{$subgrupo->id}}" data-nome="{{$subgrupo->nome}}" data-cor="{{$subgrupo->cor}}" data-toggle="modal" data-target="#subGrupoModalEdit"><i data-feather="edit" class="icon-card-grupo" data-toggle="tooltip"  data-placement="top" title="Editar"></i></a>
        <form action="{{route('parceiro.subgruporelatorio.excluir', $subgrupo->id)}}" method="POST">
                       @csrf
                       {{ method_field('DELETE') }}
                       <button type="submit" data-toggle="tooltip"  data-placement="top" title="Excluir" onclick="return confirm('Excluir o SubGrupo apagará todos os relatórios dentro dele, Excluir?')" class="btn btn-excluir-card">
                       <i data-feather="trash"></i>
                        </button>
        </form>
        </div>
     </div>
    </div>
    <div class="card-body">
    <center><a href={{route('parceiro.relatorios',  $subgrupo->id)}} class="btn btn-outline-light">Acessar</a></center>
    </div>
 </div>   
</div>
@endforeach
</div>
@endsection

@push('custom-scripts')
<script src="{{ asset('assets/js/bootstrap-colorpicker.js') }}"></script>
@if($errors->has('nome'))
    <script type="text/javascript">
        $( document ).ready(function() {
             $('#subGrupoModal').modal('show');
        });
    </script>
  @endif
  @if($errors->has('nomeEdit'))
    <script type="text/javascript">
        $( document ).ready(function() {
             $('#subGrupoModalEdit').modal('show');
        });
    </script>
  @endif
  <script type="text/javascript">
  $(function () {
    $('#cor').colorpicker();
    $('#corEdit').colorpicker();
    $('#subGrupoModalEdit').on('show.bs.modal', function(e) {
      let btn = $(e.relatedTarget); 
      let id = btn.data('id'); 
      let nome = btn.data('nome'); 
      let cor= btn.data('cor'); 
      $('#nomeEdit').val(nome);
      $('#corEditInput').val(cor);
      $('#idSubGrupoEdit').val(id);

    });
  });
</script>  
@endpush


