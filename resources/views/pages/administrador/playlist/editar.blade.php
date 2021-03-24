@extends('layout.administradores.master')
@push('plugin-styles')
<link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
<style>
.down{
  margin-left: 3px;
}
.delRelatorio{
  margin-left: 3px;
}
</style>
@endpush
@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{route('tenant.playlists')}}">Playlists</a></li>
    <li class="breadcrumb-item active" aria-current="page">Nova Playlist</li>
  </ol>
</nav>
<form method="POST" action="{{route('tenant.playlist.salvar')}}">
@csrf 
<input type="hidden" name="id_playlist" id="id_playlist" value="{{$playlist->id}}">
<div class="row">
  <div class="col-md-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Cadastrar Playlist</h6>
            <div class="row">
              <div class="col-sm-3">
                <div class="form-group {{$errors->has('nome') ? 'has-danger' : ''}}">
                  <label class="control-label">Playlist</label>
                  <input type="text" value="{{$playlist->nome}}" class="form-control {{$errors->has('nome') ? 'form-control-danger' : ''}}" name="nome" placeholder="Nome">
                  @if($errors->has('nome'))
                    <label id="name-error" class="error mt-2 text-danger" for="nome">
                      {{$errors->first('nome')}}
                    </label>
                  @endif
                </div>
              </div><!-- Col -->
              <div class="col-sm-4">
                <div class="form-group {{$errors->has('tempo_atualizacao') ? 'has-danger' : ''}}">
                  <label class="control-label">Tempo de atualizacao(Em Segundos)</label>
                  <input type="number" value="{{$playlist->tempo_atualizacao}}" class="form-control {{$errors->has('tempo_atualizacao') ? 'form-control-danger' : ''}}" name="tempo_atualizacao" placeholder="EX:10">
                  @if($errors->has('tempo_atualizacao'))
                    <label id="name-error" class="error mt-2 text-danger" for="tempo_atualizacao">
                      {{$errors->first('tempo_atualizacao')}}
                    </label>
                  @endif
                </div>
              </div><!-- Col -->
              <div class="col-sm-4">
              <div class="form-group {{$errors->has('user_id') ? 'has-danger' : ''}}">
                    <label class="control-label">Selecione o Usuário</label>
                        <select class="usuarios w-100 form-control {{$errors->has('user_id') ? 'form-control-danger' : ''}}" name="user_id" id="user_id">
                        <option value="">Selecione o Usuário</option>
                        @foreach($usuarios as $usuario)
                          @if($playlist->user_id == $usuario->id)
                                <option value="{{$usuario->id}}" selected="selected">{{$usuario->name}}</option>
                          @else 
                          <option value="{{$usuario->id}}">{{$usuario->name}}</option>
                          @endif      
                        @endforeach
                        </select>
                        @if($errors->has('user_id'))
                        <label id="name-error" class="error mt-2 text-danger" for="user_id">
                          {{$errors->first('user_id')}}
                        </label>
                      @endif
                </div>
            </div>
            </div><!-- Row -->
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Itens da Playlist</h6>      
          <div class="row">
              <div class="col-sm-12">
              <!-- ITENS PLAYLIST --> 
                <div id="itensDash">
                @foreach ($playlistitens as $item)
                    <div class="row linhasItens">
                          <input type='text' name='itensplaylist[uid_dash][]' hidden id='uid_dash' value='{{$item->relatorio_id}}' />
                        <input type='text' name='itensplaylist[ordem][]'  hidden id='ordem' value='{{$item->ordem}}' />
                        <input type='text' name='itensplaylist[nome_dash][]' hidden id='nome_dash' value='{{$item->nome}}' />
                    <div class="col-md-4">
                        <div class='form-group'>
                          <i class="{{$item->tipo == 'relatorio' ? 'mdi mdi-chart-bar' : 'mdi mdi-chart-pie'}}"></i>
                          {{$item->nome}}
                          @if($item->tipo == 'relatorio')
                          <br>
                          <input type="checkbox" {{$item->navega_paginas == 'S' ? 'checked' : ''}} class="form-group" id="navega_paginas" name='itensplaylist[navega_paginas][{{$item->ordem}}]'/><i> Navegar entre páginas</i>
                          @else
                            <br>
                            <input type="checkbox" disabled class="form-group"><del><i>Navegar entre páginas</i></del>
                            <input hidden  id="navega_paginas" name='itensplaylist[navega_paginas][{{$item->ordem}}]' value="N"/>
                          @endif
                    </div>
                    </div>
                    <div class="col-md-4">
                        <div class='form-group'>
                                    <p class='btn btn-primary up'><i class='mdi mdi-arrow-up'></i></p>
                                    <p class='btn btn-primary down'><i class='mdi mdi-arrow-down'></i></p>
                                    <p class='btn btn-danger delRelatorio'><i class='mdi mdi-delete'></i></p>
                         </div>
                    </div>
                </div>
              @endforeach
                </div>
              <!-- FIM PLAYLIST ITENS -->
            </div>
          </div>
      </div>
    </div>
  </div>
</div>
<div class="row" style="margin-top: 20px;">
  <div class="col-sm-12">
    <button type="submit" class="btn btn-primary submit">Salvar Playlist</button>  
  </form>
  </div>
</div>
<br>
<div class="row">
    <div class="col-md-4">
        <div class="form-group" id="relatorioAdd" name="relatorioAdd">
                        <label><b>Relatórios</b></label>
                        <select name="relatorio" id="relatorio" class="form-control relatorios" data-show-subtext="true" data-live-search="true">
                            <option value="0">Selecione um Relatório </option>
                            @foreach($relatorios as $relatorio)
                                <option  value="{{$relatorio->id}}" data-icon="{{$relatorio->tipo == 'relatorio' ? 'mdi mdi-chart-bar' : 'mdi mdi-chart-pie'}}" data-tipo="{{$relatorio->tipo == 'relatorio' ? 'relatorio' : 'dashboard'}}">{{$relatorio->nome}}</option>
                            @endforeach
                        </select>
        </div>
    </div>
    <div class="col-md-4">
         <div class="form-group">
                        <label>&nbsp;</label>
            <button class="btn btn-primary form-control adicionarRelPlaylist" disabled='disabled'>Adicionar a Playlist</button>
        </div>
    </div>
</div>
@endsection
@push('custom-scripts')
<script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
  if ($(".usuarios").length) {
    $(".usuarios").select2({
    });
    }
  function iformat(icon) {
    var originalOption = icon.element;
    return $('<span><i class="' + $(originalOption).data('icon') + '"></i> ' + icon.text + '</span>');
    }
  if ($(".relatorios").length) {
    $(".relatorios").select2({
        width: "100%",
        templateSelection: iformat,
        templateResult: iformat,
        allowHtml: true
    });
    }
  //mudança do select dos Relatórios
  $("#relatorio").on("change", function () {
          if($(this).val() == 0 ){
              $('.adicionarRelPlaylist').prop('disabled', true);
          }else{
                $('.adicionarRelPlaylist').prop('disabled', false);
          }
    }); 
      //Botão Adicionar Relatório a playlist
      $(document).on('click', '.adicionarRelPlaylist', function () {
       var relatorioSelecionado = $('#relatorio').find(':selected');
       if(relatorioSelecionado.val() > 0){
       var uid_dash = relatorioSelecionado.val();
       var nome_dash = relatorioSelecionado.text();
       var icon = relatorioSelecionado.attr('data-icon');
       var tipo = relatorioSelecionado.attr('data-tipo');
       relatorioSelecionado.detach();
       $('.adicionarRelPlaylist').prop('disabled', true);
       }

       if(tipo == 'relatorio'){
           var navegacao = "<br>" +
               "<input type='checkbox' id='navega_paginas' class='form-group' name='itensplaylist[navega_paginas][]'/><i> Navegar entre páginas</i>"
       }else{
           var navegacao = "<input hidden id='navega_paginas' name='itensplaylist[navega_paginas][]' value='N'/>"
       }

        var ordem = $('.linhasItens').length + 1;

        var html = "<div class='row linhasItens'>" +
                     "<input type='text' name='itensplaylist[uid_dash][]' hidden id='uid_dash' value='"+ uid_dash +"' />" +
                     "<input type='text' name='itensplaylist[ordem][]' hidden  id='ordem' value='" + ordem +"' />" +
                     "<input type='text' name='itensplaylist[nome_dash][]' hidden id='nome_dash' value='" + nome_dash + "' />" +
                     "<input type='text' name='itensplaylist[tipo][]' hidden id='tipo' value='" + tipo + "' />" +
                     "<div class='col-md-4' >" +
                     "<div class='form-group'>" +
                          "<i class='"+icon+"'></i> " + nome_dash +
		                   navegacao +
                      "</div> " +
		                "</div > " +
                   "<div class='col-md-4'>" +
                      "<div class='form-group'>" +
                        "<p class='btn btn-primary up'><i class='mdi mdi-arrow-up'></i></p>" +
                        "<p class='btn btn-primary down'><i class='mdi mdi-arrow-down'></i></p>" +
                        "<p class='btn btn-danger delRelatorio'><i class='mdi mdi-delete'></i></p>" +
                     "</div>" +
                   "</div></div>"
        ;
        $("#itensDash").append(html);

        $(this).parents('.linhaDash').eq(0).remove();
        mudarOrdem();

      });  
        //clicou no botão UP
        $(document).on('click', '.up', function () {
         var linha = $(this).parents('.linhasItens:first');
         linha.addClass('fadeItensDash');
         linha.insertBefore(linha.prev());
         mudarOrdem();

      });
      //clicou no botão DOWN
      $(document).on('click', '.down', function () {
        var linha = $(this).parents('.linhasItens:first');
        linha.addClass('fadeItensDash');
        linha.insertAfter(linha.next());
        mudarOrdem();

      });
      function mudarOrdem(){

        var totalLinhas = $('.linhasItens').length;
        $('.linhasItens').each(function (index, currentElement) {
          var posicao = index + 1;
          $(this).find('#ordem').val(posicao);
            var arr =   $(this).find('#navega_paginas').attr("name", "itensplaylist[navega_paginas]["+posicao+"]");
          if (posicao == 1) {
            $(this).find('.up').hide();
            $(this).find('.down').show();
          } else if(posicao == totalLinhas) {
            $(this).find('.down').hide();
            $(this).find('.up').show();
          }else{
            $(this).find('.up').show();
            $(this).find('.down').show();
          }

        });

        }
          //clicou no botão para remover o relatório da Lista
       $(document).on('click', '.delRelatorio', function () {
        var linha = $(this).parents('.linhasItens:first');

        var uid_dash = $(this).closest('.linhasItens').find('#uid_dash').val();
        var nome_dash = $(this).closest('.linhasItens').find('#nome_dash').val();
        var tipo_relatorio = $(this).closest('.linhasItens').find('#tipo').val();
        linha.remove();
        mudarOrdem();
        var data = {
                    id: uid_dash,
                    text: nome_dash,
                    tipo_relatorio: tipo_relatorio,
                };
             
         $('#relatorio')
            .append($('<option />')  
              .val(data.id)            
              .text(data.text)           
              .attr({                  
                'data-tipo': data.tipo_relatorio,
                'data-icon': data.tipo_relatorio == 'relatorio' ? 'mdi mdi-chart-bar' : 'mdi mdi-chart-pie'
              })
          );    
      
      });
      
});
</script>
@endpush