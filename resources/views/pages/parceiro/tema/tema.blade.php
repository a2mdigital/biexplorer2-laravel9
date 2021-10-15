@extends('layout.parceiros.master')
@push('plugin-styles')
<link href="https://itsjavi.com/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.css?v=1575468400921" rel="stylesheet">
<link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
<style type="text/css">
  		.ajax-load{
  			background: #f9fafb;
		    padding: 10px 0px;
		    width: 100%;
  		}
  	</style>
@endpush
@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="">Temas</a></li>
    <li class="breadcrumb-item active" aria-current="page">Editar Tema</li>
  </ol>
</nav> 
<div class="row">
  <div class="col-md-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Editar Tema do Portal</h6>
          <form method="POST" action="#">
          @csrf 
          {{method_field('PUT')}}
            <div class="row">
              <div class="col-sm-3">
              <div class="form-group">
              <label for="nome">Cor de Fundo do Menu Lateral</label>
              <div id="cor_fundo_menu" class="input-group colorpicker-component">
                    <input type="text" name="corEdit" id="corEditInput" value="#0c1427" class="form-control" />
                    <span class="input-group-append">
                <span class="input-group-text colorpicker-input-addon" data-original-title="" title="" tabindex="0"><i style="background-color: rgb(12, 20, 39); background-position: initial initial; background-repeat: initial initial;"></i></span>
                </span>
                </div>
              </div>
              </div><!-- Col -->
              <div class="col-sm-3">
              <div class="form-group">
              <label for="nome">Cor do Texto do Menu Lateral</label>
              <div id="cor_texto_menu" class="input-group colorpicker-component">
                    <input type="text" name="corEdit" id="corEditInput" value="#bfc3ce" class="form-control" />
                    <span class="input-group-append">
                <span class="input-group-text colorpicker-input-addon" data-original-title="" title="" tabindex="0"><i style="background-color: rgb(191, 195, 206); background-position: initial initial; background-repeat: initial initial;"></i></span>
                </span>
                </div>
              </div>
              </div><!-- Col -->  
              <div class="col-sm-3">
              <div class="form-group">
              <label for="nome">Cor do Texto do Menu Lateral (Ao passar o mouse)</label>
              <div id="cor_texto_menu_hover" class="input-group colorpicker-component">
                    <input type="text" name="corEdit" id="corEditInput" value="#727cf5" class="form-control" />
                    <span class="input-group-append">
                <span class="input-group-text colorpicker-input-addon" data-original-title="" title="" tabindex="0"><i style="background-color: rgb(114, 124, 245); background-position: initial initial; background-repeat: initial initial;"></i></span>
                </span>
                </div>
              </div>
              </div><!-- Col -->  
            </div><!-- Row -->
            <div class="row">
            <div class="col-sm-3">
              <div class="form-group">
              <label for="nome">Cor de fundo Barra Superior</label>
              <div id="cor_fundo_barra_superior" class="input-group colorpicker-component">
                    <input type="text" name="corEdit" id="corEditInput" value="#0c1427" class="form-control" />
                    <span class="input-group-append">
                <span class="input-group-text colorpicker-input-addon" data-original-title="" title="" tabindex="0"><i style="background-color: rgb(12, 20, 39); background-position: initial initial; background-repeat: initial initial;"></i></span>
                </span>
                </div>
              </div>
              </div><!-- Col -->  
              <div class="col-sm-3">
              <div class="form-group">
              <label for="nome">Cor do Texto Barra Superior</label>
              <div id="cor_texto_barra_superior" class="input-group colorpicker-component">
                    <input type="text" name="corEdit" id="corEditInput" value="#bfc3ce" class="form-control" />
                    <span class="input-group-append">
                <span class="input-group-text colorpicker-input-addon" data-original-title="" title="" tabindex="0"><i style="background-color: rgb(191, 195, 206); background-position: initial initial; background-repeat: initial initial;"></i></span>
                </span>
                </div>
              </div>
              </div><!-- Col -->  
            </div>
            <button type="submit" class="btn btn-primary submit">Salvar</button>  
          </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('custom-scripts')
<script src="{{ asset('assets/js/bootstrap-colorpicker.js') }}"></script>
<script>
  $(function () {

    $('#cor_fundo_menu').colorpicker();
    $('#cor_texto_menu').colorpicker();
    $('#cor_texto_menu_hover').colorpicker();
  });

</script>
@endpush