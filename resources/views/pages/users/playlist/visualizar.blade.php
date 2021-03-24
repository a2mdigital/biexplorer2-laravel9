<html>
    <head>
        <meta http-equiv='cache-control' content='no-cache'>
        <meta http-equiv='expires' content='0'>
        <meta http-equiv='pragma' content='no-cache'>
        <link href="{{ asset('assets/css/playlist/playlist.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/playlist/slick.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/css/playlist/slick-theme.css') }}" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <style>
            .btnFullScreen{
                display: block;
                justify-content: center;
                z-index: 3;
                bottom: 6%;
                position: absolute;
                left: 0;
                right: 0;
                text-align: center;
                background: transparent;
                border: none;
            }
            .slideshow{
                position: unset !important;
                z-index: 2;
            }
            .powerBIRelatorio{
                width: 100%;
                height: 100vh;
                z-index:1;
            }
        </style>
        <title>BI| Playlists</title>
    </head>
<body>

<!-- <div class="wrapper">-->
<div>
    <button type="button" id="toggle" name="toggle" class="btn btn-light btn-sm btnFullScreen"  onclick="fullscreen()">Full Screen</button>
    <div id="powerBI" class="powerBIRelatorio"></div>

    <input type="text" id="playlist_array" hidden name="playlist_array" value="{{$itens}}">
    <input type="text" id="tempo_atualizacao" hidden name="tempo_atualizacao" value="{{$tempo_atualizacao}}">
    <input type="text" id="total_itens" hidden name="total_itens" value="{{$total_itens}}">
    <!-- FILTROS EMPRESA -->
    <input type="hidden" name="utiliza_filtro_tenant" id="utiliza_filtro_tenant" value="{{$tenant_user->utiliza_filtro}}">
    <input type="hidden" name="filtro_tabela_tenant" id="filtro_tabela_tenant" value="{{$tenant_user->filtro_tabela}}">
    <input type="hidden" name="filtro_coluna_tenant" id="filtro_coluna_tenant" value="{{$tenant_user->filtro_coluna}}">
    <input type="hidden" name="filtro_valor_tenant" id="filtro_valor_tenant" value="{{$tenant_user->filtro_valor}}">
    <div class="slideshow">
        @foreach( $itens as $item )
        <div>
            <input type="hidden" name="tipo" id="tipo" class="tipo" value="{{$relatorio[$loop->index]->tipo}}">
            <input type="hidden" name="report_id" id="report_id" class="report_id" value="{{$relatorio[$loop->index]->report_id}}">
            <input type="hidden" name="utiliza_filtro" id="utiliza_filtro" class="utiliza_filtro" value="{{$relatorio[$loop->index]->utiliza_filtro}}">
            <input type="hidden" name="navega_paginas" id="navega_paginas" class="navega_paginas" value="{{$item->navega_paginas}}">
        </div>
        @endforeach
    </div>

</div>

</body>
    <script src="{{ asset('assets/js/playlist/jquery.min.js')  }}"></script>
    <script src="{{ asset('assets/js/playlist/jquery-ui-1-12.js') }}"></script>
 
    <script src="{{ asset('assets/js/playlist/playlist.js') }}"></script>
    <script src="{{ asset('assets/js/playlist/jquery.crotator.js') }}"></script>
    <script src="{{ asset('assets/js/playlist/slick.js') }}" type="text/javascript" charset="utf-8"></script>

    <script src="{{ asset('assets/js/powerbi.js') }}"></script>
    <script src="{{ asset('assets/js/powerbi-models.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.powerbi.js') }}"></script>
    <script type="text/javascript">

function fullscreen(){

     if( window.innerHeight == screen.height) {

                       //sai do modo full screen
                  if (document.exitFullscreen) {
                          document.exitFullscreen();
                      } else if (document.mozCancelFullScreen) { /* Firefox */
                          document.mozCancelFullScreen();
                      } else if (document.webkitExitFullscreen) { /* Chrome, Safari and Opera */
                          document.webkitExitFullscreen();
                      } else if (document.msExitFullscreen) { /* IE/Edge */
                          document.msExitFullscreen();
                   }
                  $("#toggle").html('Full Screen');

              }else{
               //
                   //entra em full screen
                      var elem = document.documentElement;
                          if (elem.requestFullscreen) {
                              elem.requestFullscreen();
                          } else if (elem.mozRequestFullScreen) { /* Firefox */
                              elem.mozRequestFullScreen();
                          } else if (elem.webkitRequestFullscreen) { /* Chrome, Safari & Opera */
                              elem.webkitRequestFullscreen();
                          } else if (elem.msRequestFullscreen) { /* IE/Edge */
                              elem.msRequestFullscreen();
                          }
                     $("#toggle").html('Sair do Full Screen');
                  }
}
$(function () {
    var tempo_atualizacao = $("#tempo_atualizacao").val();
    var tempo = tempo_atualizacao * 1000;
    console.log("tempo em segundos: " + tempo);
    $('.slideshow').slick({
        autoplay: true,
        autoplaySpeed: tempo,
        pauseOnHover:false,
    });
    var total_itens = $("#total_itens").val();
    console.log('Total de Slides é:' + total_itens);
    tokenPrimeiroSlide();
    //RODAR PRIMEIRO SLIDE
    function passarPrimeiroSlide(accessToken){
            var total_itens = $("#total_itens").val();    
            var primeiroslide = $('.slideshow').slick('slickCurrentSlide');
            var dados_primeiro_slide = $(".slideshow").slick("getSlick").$slides.eq( primeiroslide );
            var navega_paginas_primeiro_slide = dados_primeiro_slide.find('.navega_paginas').val();
            var tipo_primeiro_slide = dados_primeiro_slide.find('.tipo').val();
            var report_id_primeiro_slide = dados_primeiro_slide.find('.report_id').val();
            var nome_primeiro_slide = dados_primeiro_slide.find('.report_name').val();
            var models = window['powerbi-client'].models;
             /*FILTROS DE TENANT */
            var utiliza_filtro_tenant = $('#utiliza_filtro_tenant').val(); 
            var filtro_tabela_tenant = $('#filtro_tabela_tenant').val(); 
            var filtro_coluna_tenant = $('#filtro_coluna_tenant').val(); 
            var filtro_valor_tenant = $('#filtro_valor_tenant').val().toString().split(',');
        
            /*VERIFICO SE É DIGITADO NUMERO INTEIRO OU STRING*/
            var array_empresa_formatado = [];
            $(filtro_valor_tenant).each(function(key, value) {
                    if($.isNumeric(value)){
                            array_empresa_formatado.push(parseInt(value))
                    }else{
                            array_empresa_formatado.push(value)
                    }
            });
            if(utiliza_filtro_tenant == 'S' && tipo_primeiro_slide == 'relatorio'){
            abrirRelatorioFiltroTenant(accessToken, report_id_primeiro_slide, filtro_tabela_tenant, filtro_coluna_tenant, array_empresa_formatado)
            }
            //verifico se é um Dash ou Relatório
            if(tipo_primeiro_slide == 'relatorio'){
                            var EmbedUrlReport = "https://app.powerbi.com/reportEmbed";
                            var EmbedReportId = report_id_primeiro_slide;
                            var tokenTypeReport = 1;
                            var permissions = models.Permissions.All;
                            var config = {
                            type: 'report',
                            tokenType: tokenTypeReport == '1' ? models.TokenType.Aad : models.TokenType.Embed,
                            accessToken: accessToken,
                            embedUrl: EmbedUrlReport,
                            id: EmbedReportId,
                            permissions: permissions,
                            settings: {
                                filterPaneEnabled: false,
                                navContentPaneEnabled: true
                            }
                        };
                        //FIM RELATÓRIO, INÍCIO DASH
            }else if(tipo_primeiro_slide == 'dashboard'){
                            var DashboardId = report_id_primeiro_slide;
                            var EmbedUrlDashboard = 'https://app.powerbi.com/dashboardEmbed?dashboardId='+ DashboardId +'';
                            var tokenTypeDashboard = 1;
                            var config = {
                            type: 'dashboard',
                            tokenType: tokenTypeDashboard == '1' ? models.TokenType.Aad : models.TokenType.Embed,
                            accessToken: accessToken,
                            embedUrl: EmbedUrlDashboard,
                            id: DashboardId,
                            pageView: 'fitToWidth'
                            };
            } //FIM TIPO DO RELATÓRIO (RELATORIO OU DASH)
            if(navega_paginas_primeiro_slide == 'S'){
                console.log('navegando paginas no primeiro slide');
                $('.slideshow').slick("slickPause");
                //PEGAR RELATÓRIO DA TELA
                var pegaPowerbi = setInterval(function(){
                  
                                    var embedContainer = $('#powerBI')[0];
                                    report = powerbi.get(embedContainer);
                                     console.log("report:: " + report);
                                     report.getPages()
                                                .then(function (pages) {
                                                    PassaPaginas(pages);
                                                    if(pages){

                                                    clearInterval(pegaPowerbi);
                                                    }else{
                                                    
                                                    }
                                                })
                                                .catch(function (error) {
                                               
                                                console.log(error);
                                                });
                },1000);
                //COMEÇO A PASSAR AS PÁGINAS DO RELATÓRIO
                function PassaPaginas(paginas){
                  
                                    var embedContainer = $('#powerBI')[0];
                                    relatorio = powerbi.get(embedContainer);
                                    var totalPaginas =  paginas.length;
                                  
                                    var paginaAtual = 1;
                                    paginas.forEach((report, i) => {
                                      
                                            setTimeout(() => {
                                                //seto a página para rodar na navegação
                                                console.log("Rodando a pagina: " + report.name);
                                                relatorio.setPage(report.name);
                                               if(paginaAtual == totalPaginas){
                                                   console.log("é a ultima pagina");
                                                  //Passar pela ultima pagina do relatório
                                                   relatorio.setPage(report.name);
                                                   setTimeout(() => {
                                                       //Tempo para rodar a ultima pagina e passar para o próximo
                                                        if(total_itens == 1){
                                                            console.log('recarregou a pagina');
                                                             location.reload();
                                                        }else{
                                                            console.log('continua tocar os slides');
                                                            //CONTINUO A TOCAR OS SLIDES
                                                            $('.slideshow').slick("slickNext");
                                                            $('.slideshow').slick("slickPlay");
                                                        }
                                                   }, tempo);
                                               }
                                               paginaAtual ++;
                                            }, i * tempo);

                                            });

                    }

            } //FIM NAVEGA PÁGINAS
             
               var embedContainer = $('#powerBI')[0];
               var report = powerbi.embed(embedContainer, config);
               //se for somente 1 slide e ele não navega nas paginas recarrego a página no tempo
               if(total_itens == 1 && navega_paginas_primeiro_slide !='S'){
                 
                setTimeout(() => {
                    console.log('atualizando a pagina');
                    location.reload();
                },tempo);
               }
          console.log('fim do primeiro slide');
          //FIM PRIMEIRO SLIDE
    }//FIM PASSAR PRIMEIRO SLIDE
    //RODAR PRIMEIRO SLIDE COM FILTRO DE TENANT
    function passarPrimeiroSlideFiltroTenant(accessToken){
            var total_itens = $("#total_itens").val();    
            var primeiroslide = $('.slideshow').slick('slickCurrentSlide');
            var dados_primeiro_slide = $(".slideshow").slick("getSlick").$slides.eq( primeiroslide );
            var navega_paginas_primeiro_slide = dados_primeiro_slide.find('.navega_paginas').val();
            var tipo_primeiro_slide = dados_primeiro_slide.find('.tipo').val();
            var report_id_primeiro_slide = dados_primeiro_slide.find('.report_id').val();
            var nome_primeiro_slide = dados_primeiro_slide.find('.report_name').val();
            var models = window['powerbi-client'].models;
             /*FILTROS DE TENANT */
           
            var filtro_tabela_tenant = $('#filtro_tabela_tenant').val(); 
            var filtro_coluna_tenant = $('#filtro_coluna_tenant').val(); 
            var filtro_valor_tenant = $('#filtro_valor_tenant').val().toString().split(',');
        
            /*VERIFICO SE É DIGITADO NUMERO INTEIRO OU STRING*/
            var array_empresa_formatado = [];
            $(filtro_valor_tenant).each(function(key, value) {
                    if($.isNumeric(value)){
                            array_empresa_formatado.push(parseInt(value))
                    }else{
                            array_empresa_formatado.push(value)
                    }
            });
           
            //verifico se é um Dash ou Relatório
            if(tipo_primeiro_slide == 'relatorio'){
                    var filtros = [{
                        $schema: "http://powerbi.com/product/schema#basic",
                        target: {
                                    table: filtro_tabela_tenant,
                                    column: filtro_coluna_tenant
                                },
                        operator: "In",
                        values: array_empresa_formatado,
                        displaySettings: {
                            isLockedInViewMode: true
                        }
                    }];
                            var EmbedUrlReport = "https://app.powerbi.com/reportEmbed";
                            var EmbedReportId = report_id_primeiro_slide;
                            var tokenTypeReport = 1;
                            var permissions = models.Permissions.All;
                            var config = {
                            type: 'report',
                            tokenType: tokenTypeReport == '1' ? models.TokenType.Aad : models.TokenType.Embed,
                            accessToken: accessToken,
                            embedUrl: EmbedUrlReport,
                            id: EmbedReportId,
                            filters:filtros,
                            permissions: permissions,
                            settings: {
                                filterPaneEnabled: true,
                                navContentPaneEnabled: true
                            }
                        };
                        //FIM RELATÓRIO, INÍCIO DASH
            }else if(tipo_primeiro_slide == 'dashboard'){
                            var DashboardId = report_id_primeiro_slide;
                            var EmbedUrlDashboard = 'https://app.powerbi.com/dashboardEmbed?dashboardId='+ DashboardId +'';
                            var tokenTypeDashboard = 1;
                            var config = {
                            type: 'dashboard',
                            tokenType: tokenTypeDashboard == '1' ? models.TokenType.Aad : models.TokenType.Embed,
                            accessToken: accessToken,
                            embedUrl: EmbedUrlDashboard,
                            id: DashboardId,
                            pageView: 'fitToWidth'
                            };
            } //FIM TIPO DO RELATÓRIO (RELATORIO OU DASH)
            if(navega_paginas_primeiro_slide == 'S'){
                console.log('navegando paginas no primeiro slide');
                $('.slideshow').slick("slickPause");
                //PEGAR RELATÓRIO DA TELA
                var pegaPowerbi = setInterval(function(){
                  
                                    var embedContainer = $('#powerBI')[0];
                                    report = powerbi.get(embedContainer);
                                     console.log("report:: " + report);
                                     report.getPages()
                                                .then(function (pages) {
                                                    PassaPaginas(pages);
                                                    if(pages){

                                                    clearInterval(pegaPowerbi);
                                                    }else{
                                                    
                                                    }
                                                })
                                                .catch(function (error) {
                                               
                                                console.log(error);
                                                });
                },1000);
                //COMEÇO A PASSAR AS PÁGINAS DO RELATÓRIO
                function PassaPaginas(paginas){
                  
                                    var embedContainer = $('#powerBI')[0];
                                    relatorio = powerbi.get(embedContainer);
                                    var totalPaginas =  paginas.length;
                                  
                                    var paginaAtual = 1;
                                    paginas.forEach((report, i) => {
                                      
                                            setTimeout(() => {
                                                //seto a página para rodar na navegação
                                                console.log("Rodando a pagina: " + report.name);
                                                relatorio.setPage(report.name);
                                               if(paginaAtual == totalPaginas){
                                                   console.log("é a ultima pagina");
                                                  //Passar pela ultima pagina do relatório
                                                   relatorio.setPage(report.name);
                                                   setTimeout(() => {
                                                       //Tempo para rodar a ultima pagina e passar para o próximo
                                                        if(total_itens == 1){
                                                            console.log('recarregou a pagina');
                                                             location.reload();
                                                        }else{
                                                            console.log('continua tocar os slides');
                                                            //CONTINUO A TOCAR OS SLIDES
                                                            $('.slideshow').slick("slickNext");
                                                            $('.slideshow').slick("slickPlay");
                                                        }
                                                   }, tempo);
                                               }
                                               paginaAtual ++;
                                            }, i * tempo);

                                            });

                    }

            } //FIM NAVEGA PÁGINAS
             
               var embedContainer = $('#powerBI')[0];
               var report = powerbi.embed(embedContainer, config);
               //se for somente 1 slide e ele não navega nas paginas recarrego a página no tempo
               if(total_itens == 1 && navega_paginas_primeiro_slide !='S'){
                 
                setTimeout(() => {
                    console.log('atualizando a pagina');
                    location.reload();
                },tempo);
               }
          console.log('fim do primeiro slide');
          //FIM PRIMEIRO SLIDE COM FILTRO DE TENANT
    }//FIM PASSAR PRIMEIRO SLIDE COM FILTRO DE TENANT
    //GERA TOKEN PARA OS PRÓXIMOS SLIDES
    getToken();
    function passarSlides(accessToken){
        $('.slideshow').on('afterChange', function(event, slick, currentSlide, nextSlide){
            console.log('o Token é: ' + accessToken);
            console.log('passou o slide');
            var slide_atual = $(".slideshow").slick("getSlick").$slides.eq( currentSlide );
            var navega_paginas = slide_atual.find('.navega_paginas').val();
            var tipo = slide_atual.find('.tipo').val();
            var report_id = slide_atual.find('.report_id').val();
            var utiliza_filtro = slide_atual.find('.utiliza_filtro').val();
            var models = window['powerbi-client'].models;
            if(tipo == 'relatorio'){
                    console.log("Mostrar Relatório");
                    var EmbedUrlReport = "https://app.powerbi.com/reportEmbed";
                    var EmbedReportId = report_id;
                    var tokenTypeReport = 1;
                    var permissions = models.Permissions.All;
                    var config = {
                    type: 'report',
                    tokenType: tokenTypeReport == '1' ? models.TokenType.Aad : models.TokenType.Embed,
                    accessToken: accessToken,
                    embedUrl: EmbedUrlReport,
                    id: EmbedReportId,
                    permissions: permissions,
                    settings: {
                        filterPaneEnabled: false,
                        navContentPaneEnabled: true
                    }
                };
                var embedContainer = $('#powerBI')[0];
                powerbi.reset(embedContainer);
                //FIM RELATÓRIO, INÍCIO DASH
                }else if(tipo == 'dashboard'){
                    console.log("Mostrar Dashboard");
                    var DashboardId = report_id;
                    var EmbedUrlDashboard = 'https://app.powerbi.com/dashboardEmbed?dashboardId='+ DashboardId +'';
                    var tokenTypeDashboard = 1;
                    var config = {
                    type: 'dashboard',
                    tokenType: tokenTypeDashboard == '1' ? models.TokenType.Aad : models.TokenType.Embed,
                    accessToken: accessToken,
                    embedUrl: EmbedUrlDashboard,
                    id: DashboardId,
                    pageView: 'fitToWidth'
                    };
                 

                    var embedContainer = $('#powerBI')[0];
                    powerbi.reset(embedContainer);
            } //FIM TIPO DO RELATÓRIO (RELATORIO OU DASH)

            if(utiliza_filtro == 'S'){

            }




            if(navega_paginas == 'S'){
                $('.slideshow').slick("slickPause");
                //PEGAR RELATÓRIO DA TELA
                var pegaPowerbi = setInterval(function(){
                                    var embedContainer = $('#powerBI')[0];
                                    report = powerbi.get(embedContainer);
                                     report.getPages()
                                                .then(function (pages) {
                                                    PassaPaginas(pages);
                                                    if(pages){

                                                    clearInterval(pegaPowerbi);
                                                    }else{
                                                        console.log("não pegou");
                                                    }
                                                })
                                                .catch(function (error) {
                                                 console.log("erroo");
                                                console.log(error);
                                                });
                },1000);
                //COMEÇO A PASSAR AS PÁGINAS DO RELATÓRIO
                function PassaPaginas(paginas){
                                    var embedContainer = $('#powerBI')[0];
                                    relatorio = powerbi.get(embedContainer);
                                    var totalPaginas =  paginas.length;
                                    var paginaAtual = 1;
                                    paginas.forEach((report, i) => {
                                            setTimeout(() => {
                                                //seto a página para rodar na navegação
                                                relatorio.setPage(report.name);
                                               if(paginaAtual == totalPaginas){
                                                  //Passar pela ultima pagina do relatório
                                                   relatorio.setPage(report.name);
                                                   setTimeout(() => {
                                                       //Tempo para rodar a ultima pagina e passar para o próximo
                                                        if(total_itens == 1){
                                                             location.reload();
                                                        }else{
                                                             //VERIFICO SE É O ULTIMO SLIDE DA PLAYLIST
                                                            var numero_slide = currentSlide + 1;
                                                            if(numero_slide == total_itens){
                                                                //SE FOR O ULTIMO SLIDE E A ULTIMA PAGINA
                                                                //ATUALIZA A PAGINA PARA PEGAR AS MODIFICAÇÕES
                                                                //DE EXCLUSÃO OU ADICIONAR SLIDE NOVO NA PLAYLIST
                                                                console.log("É O ULTIMO SLIDE");
                                                                location.reload();
                                                            }else{
                                                                //CONTINUO A TOCAR OS SLIDES
                                                            $('.slideshow').slick("slickNext");
                                                            $('.slideshow').slick("slickPlay");
                                                            }
                                                        }
                                                   }, tempo);
                                               }
                                               paginaAtual ++;
                                            }, i * tempo);

                                            });

                    }

            } //FIM NAVEGA PÁGINAS

            var embedContainer = $('#powerBI')[0];
            var report = powerbi.embed(embedContainer, config);
            if(navega_paginas == 'N'){
            console.log("Slide  Atual: "+currentSlide);
            var numero_slide = currentSlide + 1;
            if(numero_slide == total_itens){
              atualizaSlides();
            }
           }
            function atualizaSlides(){
                setTimeout(() => {
                    /*
                    *AO RODAR O ULTIMO SLIDE ATUALIZA A PAGINA PARA GARANTIR
                    *QUE AO EXCLUIR UM ITEM DA PLAYLIST ELE NÃO SERÁ MAIS EXIBIDO
                    *NA PRÓXIMA VEZ
                    */
                    location.reload();
                }, tempo);
            }
        });//FIM PASSAR SLIDES
    }//FIM FUNCTION PASSARSLIDES

    //PASSAR SLIDES COM FILTRO DE TENANT
    function passarSlidesFiltroTenant(accessToken){
        $('.slideshow').on('afterChange', function(event, slick, currentSlide, nextSlide){
            var slide_atual = $(".slideshow").slick("getSlick").$slides.eq( currentSlide );
            var navega_paginas = slide_atual.find('.navega_paginas').val();
            var tipo = slide_atual.find('.tipo').val();
            var report_id = slide_atual.find('.report_id').val();
            var utiliza_filtro = slide_atual.find('.utiliza_filtro').val();
            var models = window['powerbi-client'].models;
            var filtro_tabela_tenant = $('#filtro_tabela_tenant').val(); 
            var filtro_coluna_tenant = $('#filtro_coluna_tenant').val(); 
            var filtro_valor_tenant = $('#filtro_valor_tenant').val().toString().split(',');
        
            /*VERIFICO SE É DIGITADO NUMERO INTEIRO OU STRING*/
            var array_empresa_formatado = [];
            $(filtro_valor_tenant).each(function(key, value) {
                    if($.isNumeric(value)){
                            array_empresa_formatado.push(parseInt(value))
                    }else{
                            array_empresa_formatado.push(value)
                    }
            });
           
          
            if(tipo == 'relatorio'){
                var filtros = [{
                        $schema: "http://powerbi.com/product/schema#basic",
                        target: {
                                    table: filtro_tabela_tenant,
                                    column: filtro_coluna_tenant
                                },
                        operator: "In",
                        values: array_empresa_formatado,
                        displaySettings: {
                            isLockedInViewMode: true
                        }
                    }];
                
                    var EmbedUrlReport = "https://app.powerbi.com/reportEmbed";
                    var EmbedReportId = report_id;
                    var tokenTypeReport = 1;
                    var permissions = models.Permissions.All;
                    var config = {
                    type: 'report',
                    tokenType: tokenTypeReport == '1' ? models.TokenType.Aad : models.TokenType.Embed,
                    accessToken: accessToken,
                    embedUrl: EmbedUrlReport,
                    filters:filtros,
                    id: EmbedReportId,
                    permissions: permissions,
                    settings: {
                        filterPaneEnabled: true,
                        navContentPaneEnabled: true
                    }
                };
                var embedContainer = $('#powerBI')[0];
                powerbi.reset(embedContainer);
                //FIM RELATÓRIO, INÍCIO DASH
                }else if(tipo == 'dashboard'){
                  
                    var DashboardId = report_id;
                    var EmbedUrlDashboard = 'https://app.powerbi.com/dashboardEmbed?dashboardId='+ DashboardId +'';
                    var tokenTypeDashboard = 1;
                    var config = {
                    type: 'dashboard',
                    tokenType: tokenTypeDashboard == '1' ? models.TokenType.Aad : models.TokenType.Embed,
                    accessToken: accessToken,
                    embedUrl: EmbedUrlDashboard,
                    id: DashboardId,
                    pageView: 'fitToWidth'
                    };
                 

                    var embedContainer = $('#powerBI')[0];
                    powerbi.reset(embedContainer);
            } //FIM TIPO DO RELATÓRIO (RELATORIO OU DASH)

            if(utiliza_filtro == 'S'){

            }




            if(navega_paginas == 'S'){
                $('.slideshow').slick("slickPause");
                //PEGAR RELATÓRIO DA TELA
                var pegaPowerbi = setInterval(function(){
                                    var embedContainer = $('#powerBI')[0];
                                    report = powerbi.get(embedContainer);
                                     report.getPages()
                                                .then(function (pages) {
                                                    PassaPaginas(pages);
                                                    if(pages){

                                                    clearInterval(pegaPowerbi);
                                                    }else{
                                                     
                                                    }
                                                })
                                                .catch(function (error) {
                                               
                                                console.log(error);
                                                });
                },1000);
                //COMEÇO A PASSAR AS PÁGINAS DO RELATÓRIO
                function PassaPaginas(paginas){
                                    var embedContainer = $('#powerBI')[0];
                                    relatorio = powerbi.get(embedContainer);
                                    var totalPaginas =  paginas.length;
                                    var paginaAtual = 1;
                                    paginas.forEach((report, i) => {
                                            setTimeout(() => {
                                                //seto a página para rodar na navegação
                                                relatorio.setPage(report.name);
                                               if(paginaAtual == totalPaginas){
                                                  //Passar pela ultima pagina do relatório
                                                   relatorio.setPage(report.name);
                                                   setTimeout(() => {
                                                       //Tempo para rodar a ultima pagina e passar para o próximo
                                                        if(total_itens == 1){
                                                             location.reload();
                                                        }else{
                                                             //VERIFICO SE É O ULTIMO SLIDE DA PLAYLIST
                                                            var numero_slide = currentSlide + 1;
                                                            if(numero_slide == total_itens){
                                                                //SE FOR O ULTIMO SLIDE E A ULTIMA PAGINA
                                                                //ATUALIZA A PAGINA PARA PEGAR AS MODIFICAÇÕES
                                                                //DE EXCLUSÃO OU ADICIONAR SLIDE NOVO NA PLAYLIST
                                                                console.log("É O ULTIMO SLIDE");
                                                                location.reload();
                                                            }else{
                                                                //CONTINUO A TOCAR OS SLIDES
                                                            $('.slideshow').slick("slickNext");
                                                            $('.slideshow').slick("slickPlay");
                                                            }
                                                        }
                                                   }, tempo);
                                               }
                                               paginaAtual ++;
                                            }, i * tempo);

                                            });

                    }

            } //FIM NAVEGA PÁGINAS

            var embedContainer = $('#powerBI')[0];
            var report = powerbi.embed(embedContainer, config);
            if(navega_paginas == 'N'){
            var numero_slide = currentSlide + 1;
            if(numero_slide == total_itens){
              atualizaSlides();
            }
           }
            function atualizaSlides(){
                setTimeout(() => {
                    /*
                    *AO RODAR O ULTIMO SLIDE ATUALIZA A PAGINA PARA GARANTIR
                    *QUE AO EXCLUIR UM ITEM DA PLAYLIST ELE NÃO SERÁ MAIS EXIBIDO
                    *NA PRÓXIMA VEZ
                    */
                    location.reload();
                }, tempo);
            }
        });//FIM PASSAR SLIDES
    }//FIM FUNCTION PASSARSLIDES COM FILTRO DE TENANT
    //FIM PASSAR SLIDES COM FILTRO DE TENANT

   //GERA TOKEN DO PRIMEIRO SLIDE 
   function tokenPrimeiroSlide(){
    $.get("/users/tenant/user/powerbi/getTokenPowerBi", function (accessToken) {
        var utiliza_filtro_tenant = $('#utiliza_filtro_tenant').val(); 
        if(utiliza_filtro_tenant == 'S'){
            passarPrimeiroSlideFiltroTenant(accessToken);
        }else{
            passarPrimeiroSlide(accessToken);
        }
    });
   }
    //PEGAR O TOKEN DO POWER BI PARA OS PRÓXIMOS SLIDES
   function getToken(){
    $.get("/users/tenant/user/powerbi/getTokenPowerBi", function (accessToken) {
        var utiliza_filtro_tenant = $('#utiliza_filtro_tenant').val(); 
        if(utiliza_filtro_tenant == 'S'){
            passarSlidesFiltroTenant(accessToken);
        }else{
            passarSlides(accessToken);
        }
    });
   } 
});
</script>