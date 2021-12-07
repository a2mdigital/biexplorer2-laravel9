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
              <div class="col-sm-4">
              <div class="form-group">
              <label for="cor_titulo_menu_lateral">Cor de Título do Menu Lateral (Main, Personalização, Ajuda)</label>
              <div id="cor_titulo_menu_lateral" class="input-group colorpicker-component">
                    <input type="text" name="cor_titulo_menu_lateral"  onchange="changeColor(this)" id="cor_titulo_menu_input" value="#ffffff" class="form-control" />
                    <span class="input-group-append">
                <span class="input-group-text colorpicker-input-addon" data-original-title="" title="" tabindex="0"><i style="background-color: rgb(255, 255, 255); background-position: initial initial; background-repeat: initial initial;"></i></span>
                </span>
                </div>
              </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-3">
              <div class="form-group">
              <label for="cor_fundo_menu">Cor de Fundo do Menu Lateral</label>
              <div id="cor_fundo_menu_lateral" class="input-group colorpicker-component">
                    <input type="text" name="cor_fundo_menu_lateral" onchange="changeColor(this)" id="cor_fundo_menu_lateral_input" value="#0c1427" class="form-control" />
                    <span class="input-group-append">
                <span class="input-group-text colorpicker-input-addon" data-original-title="" title="" tabindex="0"><i style="background-color: rgb(12, 20, 39); background-position: initial initial; background-repeat: initial initial;"></i></span>
                </span>
                </div>
              </div>
              </div><!-- Col -->
              <div class="col-sm-3">
              <div class="form-group">
              <label for="cor_texto_menu">Cor do Texto do Menu Lateral</label>
              <div id="cor_texto_menu_lateral" class="input-group colorpicker-component">
                    <input type="text" name="cor_texto_menu_lateral" onchange="changeColor(this)" id="cor_texto_menu_lateral_input" value="#bfc3ce" class="form-control" />
                    <span class="input-group-append">
                <span class="input-group-text colorpicker-input-addon" data-original-title="" title="" tabindex="0"><i style="background-color: rgb(191, 195, 206); background-position: initial initial; background-repeat: initial initial;"></i></span>
                </span>
                </div>
              </div>
              </div><!-- Col -->  
              <div class="col-sm-5">
              <div class="form-group">
              <label for="cor_texto_hover_menu_lateral">Cor do Texto do Menu Lateral (Ao passar o mouse)</label>
              <div id="cor_texto_hover_menu_lateral" class="input-group colorpicker-component">
                    <input type="text" name="cor_texto_hover_menu_lateral" onchange="changeColor(this)" id="cor_texto_hover_menu_lateral_input" value="#727cf5" class="form-control" />
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
              <label for="cor_fundo_barra_superior">Cor de fundo Barra Superior</label>
              <div id="cor_fundo_barra_superior" class="input-group colorpicker-component">
                    <input type="text" name="cor_fundo_barra_superior" onchange="changeColor(this)" id="cor_fundo_barra_superior_input" value="#0c1427" class="form-control" />
                    <span class="input-group-append">
                <span class="input-group-text colorpicker-input-addon" data-original-title="" title="" tabindex="0"><i style="background-color: rgb(12, 20, 39); background-position: initial initial; background-repeat: initial initial;"></i></span>
                </span>
                </div>
              </div>
              </div><!-- Col -->  
              <div class="col-sm-3">
              <div class="form-group">
              <label for="cor_texto_barra_superior">Cor do Texto Barra Superior</label>
              <div id="cor_texto_barra_superior" class="input-group colorpicker-component">
                    <input type="text" name="cor_texto_barra_superior" onchange="changeColor(this)" id="cor_texto_barra_superior_input" value="#bfc3ce" class="form-control" />
                    <span class="input-group-append">
                <span class="input-group-text colorpicker-input-addon" data-original-title="" title="" tabindex="0"><i style="background-color: rgb(191, 195, 206); background-position: initial initial; background-repeat: initial initial;"></i></span>
                </span>
                </div>
              </div>
              </div><!-- Col -->  
            </div>
          <div class="row">
            <div class="col-sm-2">
            <div class="custom-control custom-switch">
				    	<input type="checkbox" class="custom-control-input" name="defaultThemeDark">
					    <label class="custom-control-label link-title" for="defaultThemeDark">Padrão Dark</label> 
		        </div>
            </div>
            <div class="col-sm-2">
            <div class="custom-control custom-switch">
				    	<input type="checkbox" class="custom-control-input" name="defaultThemeLight">
					    <label class="custom-control-label link-title" for="defaultThemeDark">Padrão Light</label>
		        </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-4">
              <br>
            </div>
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
    changeColor = (input) => {

          let newColor = input.value;
          let inputName = input.name;
          switch(inputName){
            case 'cor_titulo_menu_lateral':
              $(".sidebar-dark .sidebar .sidebar-body .nav .nav-item.nav-category").attr(
                  'style',
                  'color:'+newColor+' !important'
                );
               
            break;  
            case 'cor_fundo_menu_lateral':
              $(".sidebar-body").attr('style',
                "background: "+newColor+" !important"
              );
            break;
            case 'cor_texto_menu_lateral': 
              $(".sidebar-dark .sidebar .sidebar-body .nav .nav-item .nav-link").attr('style',
                  'color:'+newColor+' !important'
                );
            break;
            case 'cor_texto_hover_menu_lateral': 
              $(".sidebar-dark .sidebar .sidebar-body .nav li.nav-item:hover .nav-link .link-title").attr('style',
                  'color:'+newColor+' !important'
                );
             
              /*
              $(".sidebar-dark .sidebar .sidebar-body .nav .nav-item:hover .nav-link:hover .link-title:hover").attr(
                  'style',
                  'color:'+newColor+' !important'
                );
                $(".sidebar .sidebar-body .nav .nav-item:hover .nav-link .link-icon").attr(
                  'style',
                  'color:'+newColor+' !important'
                );
                */
            break;
            case 'cor_fundo_barra_superior': 
              $(".sidebar-body").attr(
                "background: "+newColor+" !important"
                );
            break;
            case 'cor_texto_barra_superior': 
              $(".sidebar-body").attr(
                "background: "+newColor+" !important"
                );
            break;  
          }
        };

    $('#cor_titulo_menu_lateral').colorpicker();
    $('#cor_fundo_menu_lateral').colorpicker();
    $('#cor_texto_menu_lateral').colorpicker();
    $('#cor_texto_hover_menu_lateral').colorpicker();
    $('#cor_fundo_barra_superior').colorpicker();
    $('#cor_texto_barra_superior').colorpicker();

  
    


  });

</script>
@endpush