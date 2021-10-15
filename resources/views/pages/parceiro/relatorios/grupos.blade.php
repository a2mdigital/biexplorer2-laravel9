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

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <h4 class="mb-3 mb-md-0">Grupos
                        <select class="search-grupos w-100 form-control" name="search_grupos" id="search_grupos" style="width:300px;">
                        </select>
     <button type="button" id="ir"  data-toggle="tooltip" data-placement="top" title="Ir"  class="btn btn-primary btn-icon">
      <i data-feather="check-square"></i>
    </button>                   
  </h4>                     
  <div class="d-flex align-items-center flex-wrap text-nowrap">
    <button type="button" data-toggle="modal" data-target="#grupoModal" class="btn btn-primary btn-icon-text">
      <i class="btn-icon-prepend" data-feather="check-square"></i>
      Criar Grupo
    </button>
  </div>
</div>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
  <!-- MODAL CADASTRO -->
      <div id="grupoModal" class="modal fade">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h4 id="modalTitle2" class="modal-title">Criar Grupo</h4>
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">Fechar</span></button>
          </div>
          <div id="modalBody2" class="modal-body">
            <form method="post" action="{{route('parceiro.gruposrelatorio.salvar')}}">
            @csrf
              <div class="form-group {{$errors->has('nome') ? 'has-danger' : ''}}">
                <label for="nome">Nome do Grupo</label>
                <input type="text" class="form-control {{$errors->has('nome') ? 'form-control-danger' : ''}}" id="nome" name="nome" autofocus placeholder="Nome do Grupo">
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
    <!-- FIM MODAL CADASTRO -->
    <!-- MODAL EDIT -->
    <div id="grupoModalEdit" class="modal fade">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h4 id="modalTitle2" class="modal-title">Atualizar Grupo</h4>
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">Fechar</span></button>
          </div>
          <div id="modalBody2" class="modal-body">
            <form method="post" action="{{route('parceiro.gruposrelatorio.atualizar')}}">
              <input type="hidden" name="idGrupoEdit" id="idGrupoEdit" value="">
              {{ method_field('PUT') }}
              @csrf
              <div class="form-group {{$errors->has('nomeEdit') ? 'has-danger' : ''}}">
                <label for="nome">Nome do Grupo</label>
                <input type="text" class="form-control {{$errors->has('nome') ? 'form-control-danger' : ''}}" id="nomeEdit" name="nomeEdit" placeholder="Nome do Grupo">
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
<div class="col-md-12" id="post-data">
		@include('pages.parceiro.relatorios.grupos-scroll')
	</div>
	<div class="ajax-load style="display:none">
    <p style="margin-left:25%;"><img src="{{asset('assets/images/ajax-loader.gif')}}"> Carregando Grupos</p>
  </div>    
@endsection

@push('custom-scripts')
<script src="{{ asset('assets/js/bootstrap-colorpicker.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
@if($errors->has('nome'))
    <script type="text/javascript">
        $( document ).ready(function() {
             $('#grupoModal').modal('show');
        });
    </script>
  @endif
  @if($errors->has('nomeEdit'))
    <script type="text/javascript">
        $( document ).ready(function() {
             $('#grupoModalEdit').modal('show');
        });
    </script>
  @endif
  <script type="text/javascript">
//INFINITY SCROLL
var page = 1;
$('.ajax-load').hide();
	$(window).scroll(function() {
	    if($(window).scrollTop() + $(window).height() >= $(document).height()) {
	        page++;
	        loadMoreData(page);
	    }
	});

	function loadMoreData(page){
	  $.ajax(
	        {
	            url: '?page=' + page,
	            type: "get",
	            beforeSend: function()
	            {
	                $('.ajax-load').show();
	            }
	        })
	        .done(function(data)
	        {
	            if(data.html == " "){
	                $('.ajax-load').html("Registros Carregados");
	                return;
	            }
	            $('.ajax-load').hide();
	            $("#post-data").append(data.html);
	        })
	        .fail(function(jqXHR, ajaxOptions, thrownError)
	        {
	              alert('server not responding...');
	        });
	}

//FIM INFINITY SRCROLL
  $(function () {
    $(".search-grupos").select2({
      placeholder: 'Buscar Grupo',
        ajax: {
            url: "{{route('parceiro.gruposrelatorio.buscar')}}",
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.nome,
                            id: item.id
                        }
                    })
                };
            },
            cache: true
        }
    });
        
    $('#ir').click(function(){
     //PEGAR GRUPO SELECIONADO
     var grupo = $(".search-grupos").val();
     if(grupo == null){
       alert('Selecione um Grupo');
     }else{
      var url = '{{ route("parceiro.subgrupos.relatorios", ":slug") }}';
      url = url.replace(':slug', grupo);
      window.location.href=url;
     }
    });

    $('#cor').colorpicker();
    $('#corEdit').colorpicker();
    $('#grupoModalEdit').on('show.bs.modal', function(e) {
      let btn = $(e.relatedTarget); 
      let id = btn.data('id'); 
      let nome = btn.data('nome'); 
      let cor= btn.data('cor'); 
      $('#nomeEdit').val(nome);
      $('#corEditInput').val(cor);
      $('#idGrupoEdit').val(id);

    });

   
  });
</script>  
@endpush


