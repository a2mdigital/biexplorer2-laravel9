@extends('layout.users.master')
@section('titulo-pagina', 'Dashboard')
@push('plugin-styles')
  <link href="{{ asset('assets/plugins/jquery-toast-plugin/jquery.toast.css') }}" rel="stylesheet" />
@endpush

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />

<input type="hidden" name="user_id" id="user_id" value="{{$user->id}}">
<!-- FAVORITOS -->
<div class="row">
<div class="col-md-12">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
      <div>
              <h4 class="mb-3 mb-md-0">{{__('messages.favorites')}}</h4>
      </div>
    </div>
</div>
</div>
<div class="linhaRecentes">
@foreach($favoritos as $favorito)
 <div class="cartao">
    <div class="cabecalho-cartao">
      <div class="avatar favorito" data-id="{{$favorito->relatorio_id}}">
      <i data-feather="star"></i>
      </div>
    <a href="{{route('users.tenant.relatorios.visualizar',[$favorito->relatorios->subgrupo_relatorio_id, $favorito->relatorios->id])}}">      
      <div class="cabecalho-texto">
        <div class="texto">
          <span>{{$favorito->relatorios->nome}}</span>
        </div>
      </div>
      <div class="subtitulo">
        @if($favorito->relatorios->tipo == 'relatorio')
        <div class="icone-subtitulo">
        <i class="icon-subtitulo" data-feather="bar-chart"></i>
        </div>
          <span class="texto-subtitulo">{{__('messages.type_report')}}</span>
         @endif
         @if($favorito->relatorios->tipo == 'dashboard')
        <div class="icone-subtitulo">
        <i class="icon-subtitulo" data-feather="pie-chart"></i>
        </div>
          <span class="texto-subtitulo">{{__('messages.type_dashboard')}}</span>
         @endif
      </div>
      <div class="informacoes">
        <div class="texto-informacao">{{__('messages.opened')}}:<br></div>
        <span class="texto-subtitulo">{{date("d/m/Y H:i", strtotime($favorito->ultima_hora_acessada))}}</span>
      </div>
    </a>  
    </div>
 </div>
@endforeach
</div>
<!-- FIM FAVORITOS --> 
<!-- ULTIMOS ACESSADOS -->
<br>
<div class="row">
<div class="col-md-12">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
      <div>
              <h4 class="mb-3 mb-md-0">{{__('messages.last_view_report')}}</h4>
      </div>
    </div>
</div>
</div>
<div class="linhaRecentes">
@foreach($ultimosAcessados as $ultimo)
 <div class="cartao">
  <div class="cabecalho-cartao">
    @if($ultimo->favorito == 'S')
        <div class="avatar favorito" data-id="{{$ultimo->relatorio_id}}">
        <i data-feather="star"></i>
        </div>
        @else  
        <div class="avatar" data-id="{{$ultimo->relatorio_id}}">
        <i data-feather="star"></i>
        </div>
        @endif
   <a href="{{route('users.tenant.relatorios.visualizar',[$ultimo->relatorios->subgrupo_relatorio_id, $ultimo->relatorios->id])}}">         
      <div class="cabecalho-texto">
            <div class="texto">
              <span>{{$ultimo->relatorios->nome}}</span>
            </div>
      </div>
      <div class="subtitulo">
        @if($ultimo->relatorios->tipo == 'relatorio')
          <div class="icone-subtitulo">
            <i class="icon-subtitulo" data-feather="bar-chart"></i>
          </div>
          <span class="texto-subtitulo">{{__('messages.type_report')}}</span>
        @endif
        @if($ultimo->relatorios->tipo == 'dashboard')
          <div class="icone-subtitulo">
            <i class="icon-subtitulo" data-feather="pie-chart"></i>
          </div>
          <span class="texto-subtitulo">{{__('messages.type_dashboard')}}</span>
        @endif
      </div>
      <div class="informacoes">
            <div class="texto-informacao">{{__('messages.opened')}}:<br></div>
            <span class="texto-subtitulo">{{date("d/m/Y H:i", strtotime($ultimo->ultima_hora_acessada))}}</span>
      </div>
    </a>
  </div>  
 </div>
@endforeach
</div>
<!-- FIM ULTIMOS ACESSADOS --> 
<!-- MAIS ACESSADOS -->
<div class="row">
<div class="col-md-12">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
      <div>
              <h4 class="mb-3 mb-md-0">{{__('messages.more_view_report')}}</h4>
      </div>
    </div>
</div>
</div>
<div class="linhaRecentes">
@foreach($maisAcessados as $mais)

 <div class="cartao">
    <div class="cabecalho-cartao">
      @if($mais->favorito == 'S')
      <div class="avatar favorito" data-id="{{$mais->relatorio_id}}">
      @else  
      <div class="avatar" data-id="{{$mais->relatorio_id}}">
      @endif
      <i data-feather="star"></i>
      </div>
  <a href="{{route('users.tenant.relatorios.visualizar',[$mais->relatorios->subgrupo_relatorio_id, $mais->relatorios->id])}}">         
      <div class="cabecalho-texto">
          <div class="texto">
            <span>{{$mais->relatorios->nome}}</span>
          </div>
      </div>
      <div class="subtitulo">
        @if($mais->relatorios->tipo == 'relatorio')
        <div class="icone-subtitulo">
        <i class="icon-subtitulo" data-feather="bar-chart"></i>
        </div>
          <span class="texto-subtitulo">{{__('messages.type_report')}}</span>
         @endif
         @if($mais->relatorios->tipo == 'dashboard')
        <div class="icone-subtitulo">
        <i class="icon-subtitulo" data-feather="pie-chart"></i>
        </div>
          <span class="texto-subtitulo">{{__('messages.type_dashboard')}}</span>
         @endif
      </div>
      <div class="informacoes">
        <div class="texto-informacao">{{__('messages.opened')}}:<br></div>
        <span class="texto-subtitulo">{{date("d/m/Y H:i", strtotime($mais->ultima_hora_acessada))}}</span>
      </div>
    </a>
    </div>
 </div>
@endforeach
</div>
<!-- FIM MAIS ACESSADOS --> 
@endsection
@push('plugin-scripts')
<script src="{{ asset('assets/plugins/jquery-toast-plugin/jquery.toast.js') }}"></script>
@endpush

@push('custom-scripts')
<script type="text/javascript">
 $(document).ready(function() {
    $('.avatar').on('click', function(){
       var relatorio = $(this).attr('data-id');
       var user = $('#user_id').val();
       var favorito;
       if($(this).hasClass("favorito")){
        $(this).removeClass("favorito");
         favorito = "N";
               //ALERTA DE FAVORITO
        $.toast({
                text: "{{__('messages.remove_favorite')}}", // Text that is to be shown in the toast

                icon: 'warning', // Type of toast icon
                showHideTransition: 'fade', // fade, slide or plain
                allowToastClose: true, // Boolean value true or false
                hideAfter: 1500, // false to make it sticky or number representing the miliseconds as time after which toast needs to be hidden
                stack: 5, // false if there should be only one toast at a time or a number representing the maximum number of toasts to be shown at a time
                position: 'top-right', // bottom-left or bottom-right or bottom-center or top-left or top-right or top-center or mid-center or an object representing the left, right, top, bottom values



                textAlign: 'left',  // Text alignment i.e. left, right or center
                loader: true,  // Whether to show loader or not. True by default
                loaderBg: '#9EC600',  // Background color of the toast loader
                beforeShow: function () {}, // will be triggered before the toast is shown
                afterShown: function () {}, // will be triggered after the toat has been shown
                beforeHide: function () {}, // will be triggered before the toast gets hidden
                afterHidden: function () {
                    location.reload();
                }  // will be triggered after the toast has been hidden
            });
         //FIM ALERTA FAVORITO
        //salvar no banco
           $.ajax({
                           headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            method: "PUT",
                            url:'{{route("users.tenant.favorito.salvar")}}',
                            data:{
                                relatorio_id: relatorio,
                                favorito: favorito
                                },
                            success: function(data) {
                                if(data.resposta == 'salvou') {


                                }else{

                                }
                            }
            });
        }else{
            $(this).addClass("favorito");
            favorito = "S";

            //ALERTA DE FAVORITO
        $.toast({
                text: "{{__('messages.add_favorite')}}", // Text that is to be shown in the toast

                icon: 'info', // Type of toast icon
                showHideTransition: 'fade', // fade, slide or plain
                allowToastClose: true, // Boolean value true or false
                hideAfter: 1500, // false to make it sticky or number representing the miliseconds as time after which toast needs to be hidden
                stack: 5, // false if there should be only one toast at a time or a number representing the maximum number of toasts to be shown at a time
                position: 'top-right', // bottom-left or bottom-right or bottom-center or top-left or top-right or top-center or mid-center or an object representing the left, right, top, bottom values



                textAlign: 'left',  // Text alignment i.e. left, right or center
                loader: true,  // Whether to show loader or not. True by default
                loaderBg: '#9EC600',  // Background color of the toast loader
                beforeShow: function () {}, // will be triggered before the toast is shown
                afterShown: function () {}, // will be triggered after the toat has been shown
                beforeHide: function () {}, // will be triggered before the toast gets hidden
                afterHidden: function () {
                    location.reload();
                }  // will be triggered after the toast has been hidden
            });
            //FIM ALERTA FAVORITO
            //SALVAR NO BANCO
            //salvar no banco
           $.ajax({
                           headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            method: "PUT",
                            url:'{{route("users.tenant.favorito.salvar")}}',
                            data:{
                                relatorio_id: relatorio,
                                favorito: favorito
                                },
                            success: function(data) {
                                if(data.resposta == 'salvou') {


                                }else{

                                }
                            }
            });
        }
       

       //FIM FAVORITAR
    });
 });
 </script>
@endpush