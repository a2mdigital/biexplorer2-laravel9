<html>
    <head>
        <meta http-equiv='cache-control' content='no-cache'>
        <meta http-equiv='expires' content='0'>
        <meta http-equiv='pragma' content='no-cache'>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <style>
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
<input type="hidden" name="token" id="token" value="{{$token}}">
<input type="hidden" name="tipo" id="tipo" value="{{$relatorio->tipo}}">
<input type="hidden" name="report_id" id="report_id" value="{{$relatorio->report_id}}">
<input type="hidden" name="dataset_id" id="dataset_id" value="{{$relatorio->dataset_id}}">
<input type="hidden" name="habilita_filtro_lateral" id="habilita_filtro_lateral" value="{{$relatorio->filtro_lateral}}">
<input type="hidden" name="ignora_filtro_rls" id="ignora_filtro_rls" value="{{$relatorio->ignora_filtro_rls}}">
<!-- FILTROS EMPRESA -->
<input type="hidden" name="utiliza_filtro_tenant" id="utiliza_filtro_tenant" value="{{$tenant->utiliza_filtro}}">
<input type="hidden" name="filtro_tabela_tenant" id="filtro_tabela_tenant" value="{{$tenant->filtro_tabela}}">
<input type="hidden" name="filtro_coluna_tenant" id="filtro_coluna_tenant" value="{{$tenant->filtro_coluna}}">
<input type="hidden" name="filtro_valor_tenant" id="filtro_valor_tenant" value="{{$tenant->filtro_valor}}">
<!-- RLS EMPRESA -->
<input type="hidden" name="utiliza_rls_tenant" id="utiliza_rls_tenant" value="{{$tenant->utiliza_rls}}">
<input type="hidden" name="regra_rls_tenant" id="regra_rls_tenant" value="{{$tenant->regra_rls}}">
<input type="hidden" name="username_rls_tenant" id="username_rls_tenant" value="{{$tenant->username_rls}}">
    <!-- ABRIR RELATÓRIO -->        
<div id="powerBI" class="powerBIRelatorio"></div>
</div>

</body>

<script src="https://code.jquery.com/jquery-1.12.4.min.js"   integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="   crossorigin="anonymous"></script>
<script src="https://dados.app.br/js/app.js"></script>
<script src="https://dados.app.br/assets/js/jquery.powerbi.js"></script>
<script src="https://dados.app.br/assets/js/powerbi.js"></script>
<script src="https://dados.app.br/assets/js/powerbi-models.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        //verica se esta habilitado o filtro lateral
        var habilita_filtro_lateral = $('#habilita_filtro_lateral').val();
        var filtro_lateral = false;
        if(habilita_filtro_lateral == 'S'){
            var filtro_lateral = true;
        }else{
            var filtro_lateral = false;
        }
        var token = $('#token').val();
        var report_id = $('#report_id').val(); 
        var dataset_id = $('#dataset_id').val();
        var tipo = $('#tipo').val(); 
        /*RLS DE TENANT */
        var utiliza_rls = $('#utiliza_rls_tenant').val(); 
        //verificar se o relatório ignora o filtro de rls
        var ignora_filtro_rls = $('#ignora_filtro_rls').val(); 
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
        //valida se existe campos em branco nos filtros
        if(utiliza_rls == 'S' && tipo == 'relatorio' && ignora_filtro_rls != 'S'){
            //TENANT UTILIZA O RLS
           abrirRelatorioRls(token, report_id, filtro_lateral);
        }    
        if(utiliza_rls == 'S' && tipo == 'relatorio' && ignora_filtro_rls == 'S'){
            //TENANT UTILIZA O RLS
            abrirRelatorioSemFiltros(token, report_id, filtro_lateral)
        }    
        if(utiliza_rls !='S' && utiliza_filtro_tenant == 'N' && tipo == 'relatorio'){
          
            abrirRelatorioSemFiltros(token, report_id, filtro_lateral)
        }else if(utiliza_filtro_tenant == 'S' && tipo == 'relatorio'){
            abrirRelatorioFiltroTenant(token, report_id, filtro_lateral, filtro_tabela_tenant, filtro_coluna_tenant, array_empresa_formatado)
        }
        if(tipo == 'dashboard'){
         abrirDashboard(token, report_id)
        }
        //ABRIR RELATÓRIO COM RLS
        function abrirRelatorioRls(token, report_id, filtro_lateral){
             //MOSTRAR RELATÓRIO POWER BI COM RLS
             var models = window['powerbi-client'].models;
             var EmbedUrlReport = "https://app.powerbi.com/reportEmbed";
             var tokenTypeReport = 0;
             var url = 'https://app.powerbi.com/reportEmbed?reportId='+report_id;
             var permissions = models.Permissions.All;
              var grTipoPowerBi = 'report';
                var config = {
                  type: 'report',
                  tokenType: tokenTypeReport == '1' ? models.TokenType.Aad : models.TokenType.Embed,
                  accessToken: token,
                  embedUrl: EmbedUrlReport,
                  id: report_id,
                  permissions: permissions,
                  settings: {
                    filterPaneEnabled: filtro_lateral,
                    navContentPaneEnabled: true
                  }
                };

            var embedContainer = $('#powerBI')[0];
            var report = powerbi.embed(embedContainer, config);
        } // FIM ABRIR RELATÓRIO COM RLS
        //ABRIR RELATÓRIO SEM FILTROS DE EMPRESA
        function abrirRelatorioSemFiltros(token, report_id, filtro_lateral){
              alert(token);
              var EmbedUrlReport = "https://app.powerbi.com/reportEmbed";
              var tokenTypeReport = 1;
              var models = window['powerbi-client'].models;
              var permissions = models.Permissions.All;
              var grTipoPowerBi = 'report';
                var config = {
                  type: 'report',
                  tokenType: tokenTypeReport == '1' ? models.TokenType.Aad : models.TokenType.Embed,
                  accessToken: token,
                  embedUrl: EmbedUrlReport,
                  id: report_id,
                  permissions: permissions,
                  settings: {
                    filterPaneEnabled: filtro_lateral,
                    navContentPaneEnabled: true
                  }
                };

            var embedContainer = $('#powerBI')[0];
            var report = powerbi.embed(embedContainer, config);
        }
        /* FIM ABRIR RELATORIO SEM FILTRO */
         //ABRIR RELATÓRIO COM FILTROS DE TENANT
         function abrirRelatorioFiltroTenant(token, report_id, filtro_lateral,filtro_tabela_tenant, filtro_coluna_tenant, array_empresa_formatado){
         
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
              var tokenTypeReport = 1;
              var models = window['powerbi-client'].models;
              var permissions = models.Permissions.All;
              var grTipoPowerBi = 'report';
                var config = {
                  type: 'report',
                  tokenType: tokenTypeReport == '1' ? models.TokenType.Aad : models.TokenType.Embed,
                  accessToken: token,
                  embedUrl: EmbedUrlReport,
                  id: report_id,
                  permissions: permissions,
                  filters:filtros,
                  settings: {
                    filterPaneEnabled: filtro_lateral,
                    navContentPaneEnabled: true
                  }
                };

            var embedContainer = $('#powerBI')[0];
            var report = powerbi.embed(embedContainer, config);
        }
        /* FIM ABRIR RELATORIO COM FILTROS DE TENANT */ 
        /*ABRIR DASHBOARD*/ 
        function abrirDashboard(token, report_id){
          var DashboardId = report_id;
          var EmbedUrlDashboard = 'https://app.powerbi.com/dashboardEmbed?dashboardId='+ DashboardId +'';
          var tokenTypeDashboard = 1;
          var models = window['powerbi-client'].models;
          var config = {
              type: 'dashboard',
              tokenType: tokenTypeDashboard == '1' ? models.TokenType.Aad : models.TokenType.Embed,
              accessToken: token,
              embedUrl: EmbedUrlDashboard,
              id: DashboardId,
              pageView: 'fitToWidth'
          };
        var embedContainer = $('#powerBI')[0];
        var report = powerbi.embed(embedContainer, config);
        }
        //FIM ABRIR DASHBOARD
    });
</script>